/**
 * @license
 * Copyright 2017 Google LLC. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * =============================================================================
 */
// Import webgl flags.
import './flags_webgl';
import { backend_util, buffer, DataStorage, engine, env, kernel_impls, KernelBackend, nextFrame, scalar, tidy, util } from '@tensorflow/tfjs-core';
import { getWebGLContext } from './canvas_util';
import { DecodeMatrixProgram } from './decode_matrix_gpu';
import { DecodeMatrixPackedProgram } from './decode_matrix_packed_gpu';
import { EncodeFloatProgram } from './encode_float_gpu';
import { EncodeFloatPackedProgram } from './encode_float_packed_gpu';
import { EncodeMatrixProgram } from './encode_matrix_gpu';
import { EncodeMatrixPackedProgram } from './encode_matrix_packed_gpu';
import { GPGPUContext } from './gpgpu_context';
import * as gpgpu_math from './gpgpu_math';
import { getUniformLocations } from './gpgpu_math';
import { simpleAbsImplCPU } from './kernel_utils/shared';
import { PackProgram } from './pack_gpu';
import { ReshapePackedProgram } from './reshape_packed_gpu';
import * as tex_util from './tex_util';
import { TextureUsage } from './tex_util';
import { TextureManager } from './texture_manager';
import * as unary_op from './unaryop_gpu';
import { UnaryOpProgram } from './unaryop_gpu';
import { UnaryOpPackedProgram } from './unaryop_packed_gpu';
import { UnpackProgram } from './unpack_gpu';
import * as webgl_util from './webgl_util';
const whereImpl = kernel_impls.whereImpl;
export const EPSILON_FLOAT32 = 1e-7;
export const EPSILON_FLOAT16 = 1e-4;
const binaryCaches = {};
export function getBinaryCache(webGLVersion) {
    if (webGLVersion in binaryCaches) {
        return binaryCaches[webGLVersion];
    }
    binaryCaches[webGLVersion] = {};
    return binaryCaches[webGLVersion];
}
// Empirically determined constant used to determine size threshold for handing
// off execution to the CPU.
const CPU_HANDOFF_SIZE_THRESHOLD = env().getNumber('CPU_HANDOFF_SIZE_THRESHOLD');
// Empirically determined constant used to decide the number of MB on GPU
// before we warn about high memory use. The MB are this constant * screen area
// * dpi / 1024 / 1024.
const BEFORE_PAGING_CONSTANT = 600;
function numMBBeforeWarning() {
    if (env().global.screen == null) {
        return 1024; // 1 GB.
    }
    return (env().global.screen.height * env().global.screen.width *
        window.devicePixelRatio) *
        BEFORE_PAGING_CONSTANT / 1024 / 1024;
}
export class MathBackendWebGL extends KernelBackend {
    nextDataId() {
        return MathBackendWebGL.nextDataId++;
    }
    constructor(gpuResource) {
        super();
        // Maps data ids that have a pending read operation, to list of subscribers.
        this.pendingRead = new WeakMap();
        // List of data ids that are scheduled for disposal, but are waiting on a
        // pending read operation.
        this.pendingDisposal = new WeakSet();
        // Used to count the number of 'shallow' sliced tensors that point to the
        // same data id.
        this.dataRefCount = new WeakMap();
        this.numBytesInGPU = 0;
        // Accumulated time spent (including blocking) in uploading data to webgl.
        this.uploadWaitMs = 0;
        // Accumulated time spent (including blocking in downloading data from webgl.
        this.downloadWaitMs = 0;
        // record the last manual GL Flush time.
        this.lastGlFlushTime = 0;
        this.warnedAboutMemory = false;
        this.pendingDeletes = 0;
        this.disposed = false;
        if (!env().getBool('HAS_WEBGL')) {
            throw new Error('WebGL is not supported on this device');
        }
        let newGPGPU;
        if (gpuResource != null) {
            if (gpuResource instanceof GPGPUContext) {
                newGPGPU = gpuResource;
            }
            else {
                const gl = getWebGLContext(env().getNumber('WEBGL_VERSION'), gpuResource);
                newGPGPU = new GPGPUContext(gl);
            }
            this.binaryCache = {};
            this.gpgpuCreatedLocally = false;
        }
        else {
            const gl = getWebGLContext(env().getNumber('WEBGL_VERSION'));
            newGPGPU = new GPGPUContext(gl);
            this.binaryCache = getBinaryCache(env().getNumber('WEBGL_VERSION'));
            this.gpgpuCreatedLocally = true;
        }
        this.gpgpu = newGPGPU;
        this.canvas = this.gpgpu.gl.canvas;
        this.textureManager = new TextureManager(this.gpgpu);
        this.numMBBeforeWarning = numMBBeforeWarning();
        this.texData = new DataStorage(this, engine());
    }
    numDataIds() {
        return this.texData.numDataIds() - this.pendingDeletes;
    }
    // Writes a new entry to the data store with a WebGL texture, and registers it
    // to the texture manager.
    writeTexture(texture, shape, dtype, texHeight, texWidth, channels) {
        // Temporarily create an tensor info to make the texture compatible with
        // the runWebGLProgram's input.
        const input = this.makeTensorInfo(shape, dtype);
        const inData = this.texData.get(input.dataId);
        // Even though the input texture could be unpacked or dense packed, it is
        // always considered as unpacked for EncodeMatrixProgram.
        inData.isPacked = false;
        // Bind texture to the input tensor.
        inData.texture = { texture, texShape: [texHeight, texWidth] };
        inData.texShape = [texHeight, texWidth];
        const shapeAs3D = webgl_util.getShapeAs3D(shape);
        const program = new EncodeMatrixProgram(shapeAs3D, false /* isByteArray */, channels);
        const output = this.runWebGLProgram(program, [input], dtype, [[texHeight, texWidth]]);
        output.shape = shape;
        // Unbind the texture from the input tensor to avoid the texture being
        // released.
        inData.texture = null;
        this.disposeIntermediateTensorInfo(input);
        return output.dataId;
    }
    write(values, shape, dtype) {
        if (env().getBool('WEBGL_CHECK_NUMERICAL_PROBLEMS') ||
            env().getBool('DEBUG')) {
            this.checkNumericalProblems(values);
        }
        if (dtype === 'complex64' && values != null) {
            throw new Error(`Cannot write to a complex64 dtype. ` +
                `Please use tf.complex(real, imag).`);
        }
        const dataId = { id: this.nextDataId() };
        this.texData.set(dataId, { shape, dtype, values, usage: TextureUsage.UPLOAD, refCount: 1 });
        return dataId;
    }
    /** Return refCount of a `TensorData`. */
    refCount(dataId) {
        if (this.texData.has(dataId)) {
            const tensorData = this.texData.get(dataId);
            return tensorData.refCount;
        }
        return 0;
    }
    /** Increase refCount of a `TextureData`. */
    incRef(dataId) {
        const texData = this.texData.get(dataId);
        texData.refCount++;
    }
    /** Decrease refCount of a `TextureData`. */
    decRef(dataId) {
        if (this.texData.has(dataId)) {
            const texData = this.texData.get(dataId);
            texData.refCount--;
        }
    }
    move(dataId, values, shape, dtype, refCount) {
        if (env().getBool('DEBUG')) {
            this.checkNumericalProblems(values);
        }
        if (dtype === 'complex64') {
            throw new Error(`Cannot write to a complex64 dtype. ` +
                `Please use tf.complex(real, imag).`);
        }
        this.texData.set(dataId, { shape, dtype, values, usage: TextureUsage.UPLOAD, refCount });
    }
    disposeIntermediateTensorInfo(tensorInfo) {
        this.disposeData(tensorInfo.dataId);
    }
    readSync(dataId) {
        const texData = this.texData.get(dataId);
        const { values, dtype, complexTensorInfos, slice, shape, isPacked } = texData;
        // The presence of `slice` indicates this tensor is a shallow slice of a
        // different tensor, and is using that original tensor's texture. Run
        // `clone` in order to copy that texture and read from it.
        if (slice != null) {
            let program;
            if (isPacked) {
                program = new UnaryOpPackedProgram(shape, unary_op.CLONE);
            }
            else {
                program = new UnaryOpProgram(shape, unary_op.CLONE);
            }
            const res = this.runWebGLProgram(program, [{ dataId, shape, dtype }], dtype);
            const data = this.readSync(res.dataId);
            this.disposeIntermediateTensorInfo(res);
            return data;
        }
        if (values != null) {
            return this.convertAndCacheOnCPU(dataId);
        }
        if (dtype === 'string') {
            return values;
        }
        const shouldTimeProgram = this.activeTimers != null;
        let start;
        if (shouldTimeProgram) {
            start = util.now();
        }
        let result;
        if (dtype === 'complex64') {
            const realValues = this.readSync(complexTensorInfos.real.dataId);
            const imagValues = this.readSync(complexTensorInfos.imag.dataId);
            result = backend_util.mergeRealAndImagArrays(realValues, imagValues);
        }
        else {
            result = this.getValuesFromTexture(dataId);
        }
        if (shouldTimeProgram) {
            this.downloadWaitMs += util.now() - start;
        }
        return this.convertAndCacheOnCPU(dataId, result);
    }
    async read(dataId) {
        if (this.pendingRead.has(dataId)) {
            const subscribers = this.pendingRead.get(dataId);
            return new Promise(resolve => subscribers.push(resolve));
        }
        const texData = this.texData.get(dataId);
        const { values, shape, slice, dtype, complexTensorInfos, isPacked } = texData;
        // The presence of `slice` indicates this tensor is a shallow slice of a
        // different tensor, and is using that original tensor's texture. Run
        // `clone` in order to copy that texture and read from it.
        if (slice != null) {
            let program;
            if (isPacked) {
                program = new UnaryOpPackedProgram(shape, unary_op.CLONE);
            }
            else {
                program = new UnaryOpProgram(shape, unary_op.CLONE);
            }
            const res = this.runWebGLProgram(program, [{ dataId, shape, dtype }], dtype);
            const data = this.read(res.dataId);
            this.disposeIntermediateTensorInfo(res);
            return data;
        }
        if (values != null) {
            return this.convertAndCacheOnCPU(dataId);
        }
        if (env().getBool('DEBUG')) {
            // getBool('WEBGL_DOWNLOAD_FLOAT_ENABLED') caused a blocking GPU call.
            // For performance reason, only check it for debugging. In production,
            // it doesn't handle this use case anyway, so behavior is not changed.
            if (!env().getBool('WEBGL_DOWNLOAD_FLOAT_ENABLED') &&
                env().getNumber('WEBGL_VERSION') === 2) {
                throw new Error(`tensor.data() with WEBGL_DOWNLOAD_FLOAT_ENABLED=false and ` +
                    `WEBGL_VERSION=2 not yet supported.`);
            }
        }
        let buffer = null;
        let tmpDownloadTarget;
        if (dtype !== 'complex64' && env().get('WEBGL_BUFFER_SUPPORTED')) {
            // Possibly copy the texture into a buffer before inserting a fence.
            tmpDownloadTarget = this.decode(dataId);
            const tmpData = this.texData.get(tmpDownloadTarget.dataId);
            buffer = this.gpgpu.createBufferFromTexture(tmpData.texture.texture, ...tex_util.getDenseTexShape(shape));
        }
        this.pendingRead.set(dataId, []);
        if (dtype !== 'complex64') {
            // Create a fence and wait for it to resolve.
            await this.gpgpu.createAndWaitForFence();
        }
        // Download the values from the GPU.
        let vals;
        if (dtype === 'complex64') {
            const ps = await Promise.all([
                this.read(complexTensorInfos.real.dataId),
                this.read(complexTensorInfos.imag.dataId)
            ]);
            const realValues = ps[0];
            const imagValues = ps[1];
            vals = backend_util.mergeRealAndImagArrays(realValues, imagValues);
        }
        else if (buffer == null) {
            vals = this.getValuesFromTexture(dataId);
        }
        else {
            const size = util.sizeFromShape(shape);
            vals = this.gpgpu.downloadFloat32MatrixFromBuffer(buffer, size);
        }
        if (tmpDownloadTarget != null) {
            this.disposeIntermediateTensorInfo(tmpDownloadTarget);
        }
        if (buffer != null) {
            const gl = this.gpgpu.gl;
            webgl_util.callAndCheck(gl, () => gl.deleteBuffer(buffer));
        }
        const dTypeVals = this.convertAndCacheOnCPU(dataId, vals);
        const subscribers = this.pendingRead.get(dataId);
        this.pendingRead.delete(dataId);
        // Notify all pending reads.
        subscribers.forEach(resolve => resolve(dTypeVals));
        if (this.pendingDisposal.has(dataId)) {
            this.pendingDisposal.delete(dataId);
            if (this.disposeData(dataId)) {
                engine().removeDataId(dataId, this);
            }
            this.pendingDeletes--;
        }
        return dTypeVals;
    }
    /**
     * Read tensor to a new texture that is densely packed for ease of use.
     * @param dataId The source tensor.
     * @param options
     *     customTexShape: Optional. If set, will use the user defined texture
     *     shape to create the texture.
     */
    readToGPU(dataId, options = {}) {
        const texData = this.texData.get(dataId);
        const { values, shape, slice, dtype, isPacked, texture } = texData;
        if (dtype === 'complex64') {
            throw new Error('Does not support reading texture for complex64 dtype.');
        }
        // The presence of `slice` indicates this tensor is a shallow slice of a
        // different tensor, and is using that original tensor's texture. Run
        // `clone` in order to copy that texture and read from it.
        if (slice != null) {
            let program;
            if (isPacked) {
                program = new UnaryOpPackedProgram(shape, unary_op.CLONE);
            }
            else {
                program = new UnaryOpProgram(shape, unary_op.CLONE);
            }
            const res = this.runWebGLProgram(program, [{ dataId, shape, dtype }], dtype);
            const gpuResouorce = this.readToGPU(res, options);
            this.disposeIntermediateTensorInfo(res);
            return gpuResouorce;
        }
        if (texture == null) {
            if (values != null) {
                throw new Error('Data is not on GPU but on CPU.');
            }
            else {
                throw new Error('There is no data on GPU or CPU.');
            }
        }
        // Decode the texture so that it is stored densely (using four channels).
        const tmpTarget = this.decode(dataId, options.customTexShape);
        // Make engine track this tensor, so that we can dispose it later.
        const tensorRef = engine().makeTensorFromTensorInfo(tmpTarget);
        const tmpData = this.texData.get(tmpTarget.dataId);
        return Object.assign({ tensorRef }, tmpData.texture);
    }
    bufferSync(t) {
        const data = this.readSync(t.dataId);
        if (t.dtype === 'string') {
            try {
                // Decode the bytes into string.
                const strings = data.map(d => util.decodeString(d));
                return buffer(t.shape, t.dtype, strings);
            }
            catch (_a) {
                throw new Error('Failed to decode encoded string bytes into utf-8');
            }
        }
        return buffer(t.shape, t.dtype, data);
    }
    checkNumericalProblems(values) {
        if (values == null) {
            return;
        }
        for (let i = 0; i < values.length; i++) {
            const num = values[i];
            if (!webgl_util.canBeRepresented(num)) {
                if (env().getBool('WEBGL_RENDER_FLOAT32_CAPABLE')) {
                    throw Error(`The value ${num} cannot be represented with your ` +
                        `current settings. Consider enabling float32 rendering: ` +
                        `'tf.env().set('WEBGL_RENDER_FLOAT32_ENABLED', true);'`);
                }
                throw Error(`The value ${num} cannot be represented on this device.`);
            }
        }
    }
    getValuesFromTexture(dataId) {
        const { shape, dtype, isPacked } = this.texData.get(dataId);
        const size = util.sizeFromShape(shape);
        if (env().getBool('WEBGL_DOWNLOAD_FLOAT_ENABLED')) {
            const tmpTarget = this.decode(dataId);
            const tmpData = this.texData.get(tmpTarget.dataId);
            const vals = this.gpgpu
                .downloadMatrixFromPackedTexture(tmpData.texture.texture, ...tex_util.getDenseTexShape(shape))
                .subarray(0, size);
            this.disposeIntermediateTensorInfo(tmpTarget);
            return vals;
        }
        const shouldUsePackedProgram = env().getBool('WEBGL_PACK') && isPacked === true;
        const outputShape = shouldUsePackedProgram ? webgl_util.getShapeAs3D(shape) : shape;
        const program = shouldUsePackedProgram ?
            new EncodeFloatPackedProgram(outputShape) :
            new EncodeFloatProgram(outputShape);
        const output = this.runWebGLProgram(program, [{ shape: outputShape, dtype, dataId }], 'float32');
        const tmpData = this.texData.get(output.dataId);
        const vals = this.gpgpu
            .downloadByteEncodedFloatMatrixFromOutputTexture(tmpData.texture.texture, tmpData.texShape[0], tmpData.texShape[1])
            .subarray(0, size);
        this.disposeIntermediateTensorInfo(output);
        return vals;
    }
    timerAvailable() {
        return env().getNumber('WEBGL_DISJOINT_QUERY_TIMER_EXTENSION_RELIABLE') > 0;
    }
    time(f) {
        const oldActiveTimers = this.activeTimers;
        const newActiveTimers = [];
        let outerMostTime = false;
        if (this.programTimersStack == null) {
            this.programTimersStack = newActiveTimers;
            outerMostTime = true;
        }
        else {
            this.activeTimers.push(newActiveTimers);
        }
        this.activeTimers = newActiveTimers;
        f();
        // needing to split these up because util.flatten only accepts certain types
        const flattenedActiveTimerQueries = util.flatten(this.activeTimers.map((d) => d.query))
            .filter(d => d != null);
        const flattenedActiveTimerNames = util.flatten(this.activeTimers.map((d) => d.name))
            .filter(d => d != null);
        this.activeTimers = oldActiveTimers;
        if (outerMostTime) {
            this.programTimersStack = null;
        }
        const res = {
            uploadWaitMs: this.uploadWaitMs,
            downloadWaitMs: this.downloadWaitMs,
            kernelMs: null,
            wallMs: null // will be filled by the engine
        };
        return (async () => {
            if (env().getNumber('WEBGL_DISJOINT_QUERY_TIMER_EXTENSION_RELIABLE') >
                0) {
                const kernelMs = await Promise.all(flattenedActiveTimerQueries);
                res['kernelMs'] = util.sum(kernelMs);
                res['getExtraProfileInfo'] = () => kernelMs
                    .map((d, i) => ({ name: flattenedActiveTimerNames[i], ms: d }))
                    .map(d => `${d.name}: ${d.ms}`)
                    .join(', ');
            }
            else {
                res['kernelMs'] = {
                    error: 'WebGL query timers are not supported in this environment.'
                };
            }
            this.uploadWaitMs = 0;
            this.downloadWaitMs = 0;
            return res;
        })();
    }
    memory() {
        return {
            unreliable: false,
            numBytesInGPU: this.numBytesInGPU,
            numBytesInGPUAllocated: this.textureManager.numBytesAllocated,
            numBytesInGPUFree: this.textureManager.numBytesFree
        };
    }
    startTimer() {
        if (env().getNumber('WEBGL_DISJOINT_QUERY_TIMER_EXTENSION_RELIABLE') > 0) {
            return this.gpgpu.beginQuery();
        }
        return { startMs: util.now(), endMs: null };
    }
    endTimer(query) {
        if (env().getNumber('WEBGL_DISJOINT_QUERY_TIMER_EXTENSION_RELIABLE') > 0) {
            this.gpgpu.endQuery();
            return query;
        }
        query.endMs = util.now();
        return query;
    }
    async getQueryTime(query) {
        if (env().getNumber('WEBGL_DISJOINT_QUERY_TIMER_EXTENSION_RELIABLE') > 0) {
            return this.gpgpu.waitForQueryAndGetTime(query);
        }
        const timerQuery = query;
        return timerQuery.endMs - timerQuery.startMs;
    }
    /**
     * Decrease the RefCount on the dataId and dispose the memory if the dataId
     * has 0 refCount. If there are pending read on the data, the disposal would
     * added to the pending delete queue. Return true if the dataId is removed
     * from backend or the backend does not contain the dataId, false if the
     * dataId is not removed. Memory may or may not be released even when dataId
     * is removed, which also depends on dataRefCount, see `releaseGPU`.
     * @param dataId
     * @oaram force Optional, remove the data regardless of refCount
     */
    disposeData(dataId, force = false) {
        if (this.pendingDisposal.has(dataId)) {
            return false;
        }
        // No-op if already disposed.
        if (!this.texData.has(dataId)) {
            return true;
        }
        // if force flag is set, change refCount to 0, this would ensure disposal
        // when added to the pendingDisposal queue. Memory may or may not be
        // released, which also depends on dataRefCount, see `releaseGPU`.
        if (force) {
            this.texData.get(dataId).refCount = 0;
        }
        else {
            this.texData.get(dataId).refCount--;
        }
        if (!force && this.texData.get(dataId).refCount > 0) {
            return false;
        }
        if (this.pendingRead.has(dataId)) {
            this.pendingDisposal.add(dataId);
            this.pendingDeletes++;
            return false;
        }
        this.releaseGPUData(dataId);
        const { complexTensorInfos } = this.texData.get(dataId);
        if (complexTensorInfos != null) {
            this.disposeData(complexTensorInfos.real.dataId, force);
            this.disposeData(complexTensorInfos.imag.dataId, force);
        }
        this.texData.delete(dataId);
        return true;
    }
    releaseGPUData(dataId) {
        const { texture, dtype, texShape, usage, isPacked, slice } = this.texData.get(dataId);
        const key = slice && slice.origDataId || dataId;
        const refCount = this.dataRefCount.get(key);
        if (refCount > 1) {
            this.dataRefCount.set(key, refCount - 1);
        }
        else {
            this.dataRefCount.delete(key);
            if (texture != null) {
                this.numBytesInGPU -= this.computeBytes(texShape, dtype);
                this.textureManager.releaseTexture(texture, texShape, usage, isPacked);
            }
        }
        const texData = this.texData.get(dataId);
        texData.texture = null;
        texData.texShape = null;
        texData.isPacked = false;
        texData.slice = null;
    }
    getTexture(dataId) {
        this.uploadToGPU(dataId);
        return this.texData.get(dataId).texture.texture;
    }
    /**
     * Returns internal information for the specific data bucket. Used in unit
     * tests.
     */
    getDataInfo(dataId) {
        return this.texData.get(dataId);
    }
    /*
    Tests whether all the inputs to an op are small and on the CPU. This heuristic
    determines when it would be faster to execute a kernel on the CPU. WebGL
    kernels opt into running this check and forwarding when appropriate.
    TODO(https://github.com/tensorflow/tfjs/issues/872): Develop a more
    sustainable strategy for optimizing backend execution of ops.
     */
    shouldExecuteOnCPU(inputs, sizeThreshold = CPU_HANDOFF_SIZE_THRESHOLD) {
        return env().getBool('WEBGL_CPU_FORWARD') &&
            inputs.every(input => this.texData.get(input.dataId).texture == null &&
                util.sizeFromShape(input.shape) < sizeThreshold);
    }
    getGPGPUContext() {
        return this.gpgpu;
    }
    where(condition) {
        backend_util.warn('tf.where() in webgl locks the UI thread. ' +
            'Call tf.whereAsync() instead');
        const condVals = condition.dataSync();
        return whereImpl(condition.shape, condVals);
    }
    packedUnaryOp(x, op, dtype) {
        const program = new UnaryOpPackedProgram(x.shape, op);
        const outInfo = this.compileAndRun(program, [x], dtype);
        return engine().makeTensorFromTensorInfo(outInfo);
    }
    // TODO(msoulanille) remove this once the backend has been modularized
    // a copy is needed here to break a circular dependency.
    // Also remove the op from unary_op.
    abs(x) {
        // TODO: handle cases when x is complex.
        if (this.shouldExecuteOnCPU([x]) && x.dtype !== 'complex64') {
            const outValues = simpleAbsImplCPU(this.texData.get(x.dataId).values);
            return this.makeOutput(x.shape, x.dtype, outValues);
        }
        if (env().getBool('WEBGL_PACK_UNARY_OPERATIONS')) {
            return this.packedUnaryOp(x, unary_op.ABS, x.dtype);
        }
        const program = new UnaryOpProgram(x.shape, unary_op.ABS);
        const outInfo = this.compileAndRun(program, [x]);
        return engine().makeTensorFromTensorInfo(outInfo);
    }
    makeTensorInfo(shape, dtype, values) {
        let dataId;
        if (dtype === 'string' && values != null && values.length > 0 &&
            util.isString(values[0])) {
            const encodedValues = values.map(d => util.encodeString(d));
            dataId = this.write(encodedValues, shape, dtype);
        }
        else {
            dataId = this.write(values, shape, dtype);
        }
        this.texData.get(dataId).usage = null;
        return { dataId, shape, dtype };
    }
    makeOutput(shape, dtype, values) {
        return engine().makeTensorFromTensorInfo(this.makeTensorInfo(shape, dtype, values), this);
    }
    unpackTensor(input) {
        const program = new UnpackProgram(input.shape);
        return this.runWebGLProgram(program, [input], input.dtype);
    }
    packTensor(input) {
        const program = new PackProgram(input.shape);
        const preventEagerUnpackingOutput = true;
        return this.runWebGLProgram(program, [input], input.dtype, null /* customUniformValues */, preventEagerUnpackingOutput);
    }
    packedReshape(input, afterShape) {
        const input3DShape = [
            webgl_util.getBatchDim(input.shape),
            ...webgl_util.getRowsCols(input.shape)
        ];
        const input3D = {
            dtype: input.dtype,
            shape: input3DShape,
            dataId: input.dataId
        };
        const afterShapeAs3D = [
            webgl_util.getBatchDim(afterShape), ...webgl_util.getRowsCols(afterShape)
        ];
        const program = new ReshapePackedProgram(afterShapeAs3D, input3DShape);
        const preventEagerUnpackingOfOutput = true;
        const customValues = [input3DShape];
        const output = this.runWebGLProgram(program, [input3D], input.dtype, customValues, preventEagerUnpackingOfOutput);
        return { dataId: output.dataId, shape: afterShape, dtype: output.dtype };
    }
    decode(dataId, customTexShape) {
        const texData = this.texData.get(dataId);
        const { isPacked, shape, dtype } = texData;
        if (customTexShape != null) {
            const size = util.sizeFromShape(shape);
            const texSize = customTexShape[0] * customTexShape[1] * 4;
            util.assert(size <= texSize, () => 'customTexShape is too small. ' +
                'Row * Column * 4 should be equal or larger than the ' +
                'size of the tensor data.');
        }
        const shapeAs3D = webgl_util.getShapeAs3D(shape);
        let program;
        if (isPacked) {
            program = new DecodeMatrixPackedProgram(shapeAs3D);
        }
        else {
            program = new DecodeMatrixProgram(shapeAs3D);
        }
        const preventEagerUnpackingOfOutput = true;
        const customValues = [customTexShape != null ? customTexShape :
                tex_util.getDenseTexShape(shapeAs3D)];
        const out = this.runWebGLProgram(program, [{ shape: shapeAs3D, dtype, dataId }], dtype, customValues, preventEagerUnpackingOfOutput, customTexShape);
        return { dtype, shape, dataId: out.dataId };
    }
    runWebGLProgram(program, inputs, outputDtype, customUniformValues, preventEagerUnpackingOfOutput = false, customTexShape) {
        const output = this.makeTensorInfo(program.outputShape, outputDtype);
        const outData = this.texData.get(output.dataId);
        if (program.packedOutput) {
            outData.isPacked = true;
        }
        if (program.outPackingScheme === tex_util.PackingScheme.DENSE) {
            const texelShape = customTexShape != null ?
                customTexShape :
                tex_util.getDenseTexShape(program.outputShape);
            // For a densely packed output, we explicitly set texShape
            // so it doesn't get assigned later according to our typical packing
            // scheme wherein a single texel can only contain values from adjacent
            // rows/cols.
            outData.texShape = texelShape.map(d => d * 2);
        }
        if (program.outTexUsage != null) {
            outData.usage = program.outTexUsage;
        }
        if (util.sizeFromShape(output.shape) === 0) {
            // Short-circuit the computation since the result is empty (has 0 in its
            // shape).
            outData.values =
                util.getTypedArrayFromDType(output.dtype, 0);
            return output;
        }
        const dataToDispose = [];
        const inputsData = inputs.map(input => {
            if (input.dtype === 'complex64') {
                throw new Error(`GPGPUProgram does not support complex64 input. For complex64 ` +
                    `dtypes, please separate the program into real and imaginary ` +
                    `parts.`);
            }
            let texData = this.texData.get(input.dataId);
            if (texData.texture == null) {
                if (!program.packedInputs &&
                    util.sizeFromShape(input.shape) <=
                        env().getNumber('WEBGL_SIZE_UPLOAD_UNIFORM')) {
                    // Upload small tensors that live on the CPU as uniforms, not as
                    // textures. Do this only when the environment supports 32bit floats
                    // due to problems when comparing 16bit floats with 32bit floats.
                    // TODO(https://github.com/tensorflow/tfjs/issues/821): Make it
                    // possible for packed shaders to sample from uniforms.
                    return {
                        shape: input.shape,
                        texData: null,
                        isUniform: true,
                        uniformValues: texData.values
                    };
                }
                // This ensures that if a packed program's inputs have not yet been
                // uploaded to the GPU, they get uploaded as packed right off the bat.
                if (program.packedInputs) {
                    texData.isPacked = true;
                    texData.shape = input.shape;
                }
            }
            this.uploadToGPU(input.dataId);
            if (!!texData.isPacked !== !!program.packedInputs) {
                input = texData.isPacked ? this.unpackTensor(input) :
                    this.packTensor(input);
                dataToDispose.push(input);
                texData = this.texData.get(input.dataId);
            }
            else if (texData.isPacked &&
                !webgl_util.isReshapeFree(texData.shape, input.shape)) {
                // This is a special case where a texture exists for a tensor
                // but the shapes are incompatible (due to packing constraints) because
                // the tensor did not have a chance to go through the packed reshape
                // shader. This only happens when we reshape the *same* tensor to form
                // *distinct* inputs to an op, e.g. dotting a vector with itself. This
                // case will disappear once packed uploading is the default.
                const savedInput = input;
                const targetShape = input.shape;
                input.shape = texData.shape;
                input = this.packedReshape(input, targetShape);
                dataToDispose.push(input);
                texData = this.texData.get(input.dataId);
                savedInput.shape = targetShape;
            }
            return { shape: input.shape, texData, isUniform: false };
        });
        this.uploadToGPU(output.dataId);
        const outputData = { shape: output.shape, texData: outData, isUniform: false };
        const key = gpgpu_math.makeShaderKey(program, inputsData, outputData);
        const binary = this.getAndSaveBinary(key, () => {
            return gpgpu_math.compileProgram(this.gpgpu, program, inputsData, outputData);
        });
        const shouldTimeProgram = this.activeTimers != null;
        let query;
        if (shouldTimeProgram) {
            query = this.startTimer();
        }
        if (!env().get('ENGINE_COMPILE_ONLY')) {
            gpgpu_math.runProgram(this.gpgpu, binary, inputsData, outputData, customUniformValues);
        }
        dataToDispose.forEach(info => this.disposeIntermediateTensorInfo(info));
        if (shouldTimeProgram) {
            query = this.endTimer(query);
            this.activeTimers.push({ name: program.constructor.name, query: this.getQueryTime(query) });
        }
        const glFlushThreshold = env().get('WEBGL_FLUSH_THRESHOLD');
        // Manually GL flush requested
        if (glFlushThreshold > 0) {
            const time = util.now();
            if ((time - this.lastGlFlushTime) > glFlushThreshold) {
                this.gpgpu.gl.flush();
                this.lastGlFlushTime = time;
            }
        }
        if (!env().getBool('WEBGL_LAZILY_UNPACK') && outData.isPacked &&
            preventEagerUnpackingOfOutput === false) {
            const unpacked = this.unpackTensor(output);
            this.disposeIntermediateTensorInfo(output);
            return unpacked;
        }
        return output;
    }
    compileAndRun(program, inputs, outputDtype, customUniformValues, preventEagerUnpackingOfOutput = false) {
        outputDtype = outputDtype || inputs[0].dtype;
        const outInfo = this.runWebGLProgram(program, inputs, outputDtype, customUniformValues, preventEagerUnpackingOfOutput);
        return outInfo;
    }
    getAndSaveBinary(key, getBinary) {
        if (!(key in this.binaryCache)) {
            this.binaryCache[key] = getBinary();
        }
        return this.binaryCache[key];
    }
    getTextureManager() {
        return this.textureManager;
    }
    dispose() {
        if (this.disposed) {
            return;
        }
        // Avoid disposing the compiled webgl programs during unit testing because
        // it slows down test execution.
        if (!env().getBool('IS_TEST')) {
            const allKeys = Object.keys(this.binaryCache);
            allKeys.forEach(key => {
                this.gpgpu.deleteProgram(this.binaryCache[key].webGLProgram);
                delete this.binaryCache[key];
            });
        }
        this.textureManager.dispose();
        if (this.canvas != null &&
            (typeof (HTMLCanvasElement) !== 'undefined' &&
                this.canvas instanceof HTMLCanvasElement)) {
            this.canvas.remove();
        }
        else {
            this.canvas = null;
        }
        if (this.gpgpuCreatedLocally) {
            this.gpgpu.program = null;
            this.gpgpu.dispose();
        }
        this.disposed = true;
    }
    floatPrecision() {
        if (this.floatPrecisionValue == null) {
            this.floatPrecisionValue = tidy(() => {
                if (!env().get('WEBGL_RENDER_FLOAT32_ENABLED')) {
                    // Momentarily switching DEBUG flag to false so we don't throw an
                    // error trying to upload a small value.
                    const debugFlag = env().getBool('DEBUG');
                    env().set('DEBUG', false);
                    const underflowCheckValue = this.abs(scalar(1e-8)).dataSync()[0];
                    env().set('DEBUG', debugFlag);
                    if (underflowCheckValue > 0) {
                        return 32;
                    }
                }
                return 16;
            });
        }
        return this.floatPrecisionValue;
    }
    /** Returns the smallest representable number.  */
    epsilon() {
        return this.floatPrecision() === 32 ? EPSILON_FLOAT32 : EPSILON_FLOAT16;
    }
    uploadToGPU(dataId) {
        const texData = this.texData.get(dataId);
        const { shape, dtype, values, texture, usage, isPacked } = texData;
        if (texture != null) {
            // Array is already on GPU. No-op.
            return;
        }
        const shouldTimeProgram = this.activeTimers != null;
        let start;
        if (shouldTimeProgram) {
            start = util.now();
        }
        let texShape = texData.texShape;
        if (texShape == null) {
            // This texShape may not be the final texture shape. For packed or dense
            // textures, the texShape will be changed when textures are created.
            texShape = webgl_util.getTextureShapeFromLogicalShape(shape, isPacked);
            texData.texShape = texShape;
        }
        if (values != null) {
            const shapeAs3D = webgl_util.getShapeAs3D(shape);
            let program;
            let width = texShape[1], height = texShape[0];
            const isByteArray = values instanceof Uint8Array || values instanceof Uint8ClampedArray;
            // texture for float array is PhysicalTextureType.PACKED_2X2_FLOAT32, we
            // need to make sure the upload uses the same packed size
            if (isPacked || !isByteArray) {
                [width, height] = tex_util.getPackedMatrixTextureShapeWidthHeight(texShape[0], texShape[1]);
            }
            if (isPacked) {
                program = new EncodeMatrixPackedProgram(shapeAs3D, isByteArray);
            }
            else {
                program = new EncodeMatrixProgram(shapeAs3D, isByteArray);
            }
            // TexShape for float array needs to be the original shape, which byte
            // array needs to be packed size. This allow the data upload shape to be
            // matched with texture creation logic.
            const tempDenseInputTexShape = isByteArray ? [height, width] : texShape;
            const tempDenseInputHandle = this.makeTensorInfo(tempDenseInputTexShape, dtype);
            const tempDenseInputTexData = this.texData.get(tempDenseInputHandle.dataId);
            if (isByteArray) {
                tempDenseInputTexData.usage = TextureUsage.PIXELS;
            }
            else {
                tempDenseInputTexData.usage = TextureUsage.UPLOAD;
            }
            tempDenseInputTexData.texShape = tempDenseInputTexShape;
            this.gpgpu.uploadDenseMatrixToTexture(this.getTexture(tempDenseInputHandle.dataId), width, height, values);
            const customValues = [[height, width]];
            // We want the output to remain packed regardless of the value of
            // WEBGL_PACK.
            const preventEagerUnpacking = true;
            const encodedOutputTarget = this.runWebGLProgram(program, [tempDenseInputHandle], dtype, customValues, preventEagerUnpacking);
            // Have the original texture assume the identity of the encoded output.
            const outputTexData = this.texData.get(encodedOutputTarget.dataId);
            texData.texShape = outputTexData.texShape;
            texData.isPacked = outputTexData.isPacked;
            texData.usage = outputTexData.usage;
            if (!env().get('ENGINE_COMPILE_ONLY')) {
                texData.texture = outputTexData.texture;
                // Once uploaded, don't store the values on cpu.
                texData.values = null;
                this.texData.delete(encodedOutputTarget.dataId);
            }
            else {
                this.disposeData(encodedOutputTarget.dataId);
            }
            this.disposeIntermediateTensorInfo(tempDenseInputHandle);
            if (shouldTimeProgram) {
                this.uploadWaitMs += util.now() - start;
            }
        }
        else {
            const newTexture = this.acquireTexture(texShape, usage, dtype, isPacked);
            texData.texture = newTexture;
        }
    }
    convertAndCacheOnCPU(dataId, float32Values) {
        const texData = this.texData.get(dataId);
        const { dtype } = texData;
        if (float32Values != null) {
            texData.values = float32ToTypedArray(float32Values, dtype);
        }
        return texData.values;
    }
    acquireTexture(texShape, texType, dtype, isPacked) {
        this.numBytesInGPU += this.computeBytes(texShape, dtype);
        if (!this.warnedAboutMemory &&
            this.numBytesInGPU > this.numMBBeforeWarning * 1024 * 1024) {
            const mb = (this.numBytesInGPU / 1024 / 1024).toFixed(2);
            this.warnedAboutMemory = true;
            console.warn(`High memory usage in GPU: ${mb} MB, ` +
                `most likely due to a memory leak`);
        }
        return this.textureManager.acquireTexture(texShape, texType, isPacked);
    }
    computeBytes(shape, dtype) {
        return shape[0] * shape[1] * util.bytesPerElement(dtype);
    }
    checkCompileCompletion() {
        for (const [, binary] of Object.entries(this.binaryCache)) {
            this.checkCompletion_(binary);
        }
    }
    async checkCompileCompletionAsync() {
        const ps = [];
        if (this.gpgpu.parallelCompilationExtension) {
            for (const [, binary] of Object.entries(this.binaryCache)) {
                ps.push(this.checkCompletionAsync_(binary));
            }
            return Promise.all(ps);
        }
        else {
            for (const [, binary] of Object.entries(this.binaryCache)) {
                const p = new Promise((resolve) => {
                    try {
                        this.checkCompletion_(binary);
                        resolve(true);
                    }
                    catch (error) {
                        throw error;
                    }
                });
                ps.push(p);
            }
            return Promise.all(ps);
        }
    }
    async checkCompletionAsync_(binary) {
        if (this.gpgpu.gl.getProgramParameter(binary.webGLProgram, this.gpgpu.parallelCompilationExtension.COMPLETION_STATUS_KHR)) {
            return this.checkCompletion_(binary);
        }
        else {
            await nextFrame();
            return this.checkCompletionAsync_(binary);
        }
    }
    checkCompletion_(binary) {
        if (this.gpgpu.gl.getProgramParameter(binary.webGLProgram, this.gpgpu.gl.LINK_STATUS) === false) {
            console.log(this.gpgpu.gl.getProgramInfoLog(binary.webGLProgram));
            if (this.gpgpu.gl.getShaderParameter(binary.fragmentShader, this.gpgpu.gl.COMPILE_STATUS) === false) {
                webgl_util.logShaderSourceAndInfoLog(binary.source, this.gpgpu.gl.getShaderInfoLog(binary.fragmentShader));
                throw new Error('Failed to compile fragment shader.');
            }
            throw new Error('Failed to link vertex and fragment shaders.');
        }
        return true;
    }
    getUniformLocations() {
        for (const binary of Object.values(this.binaryCache)) {
            // TODO: Iterating through all binaries to build VAOs is supposed to be in
            // a seperate function, like 'setVaos'. However, to avoid breaking changes
            // for the users using parallel compile feature now, buildVao is silently
            // added here.
            this.gpgpu.buildVao(binary.webGLProgram);
            const { variablesLocations, customUniformLocations, infLoc, nanLoc, outShapeLocation, outShapeStridesLocation, outTexShapeLocation } = getUniformLocations(this.gpgpu, binary.program, binary.webGLProgram);
            binary.variablesLocations = variablesLocations;
            binary.customUniformLocations = customUniformLocations;
            binary.infLoc = infLoc;
            binary.nanLoc = nanLoc;
            binary.outShapeLocation = outShapeLocation;
            binary.outShapeStridesLocation = outShapeStridesLocation;
            binary.outTexShapeLocation = outTexShapeLocation;
        }
    }
    /**
     * Create a TF.js tensor out of an existing WebGL texture. A new texture will
     * be created.
     */
    createTensorFromGPUData(values, shape, dtype) {
        values.channels = values.channels || 'RGBA';
        const { texture, height, width, channels } = values;
        const backend = engine().backend;
        // Have to throw an error, otherwise WebGL just warns and returns wrong
        // values.
        if (!backend.gpgpu.gl.isTexture(texture)) {
            throw new Error(`The texture is invalid. Also, please make sure the texture and ` +
                `the TFJS WebGL backend are using the same canvas. If you want to ` +
                `use your own custom canvas, you have to create and use the custom ` +
                `TFJS WebGL backend created from the canvas through ` +
                `'new tf.MathBackendWebGL(customCanvas)'.`);
        }
        const dataId = backend.writeTexture(texture, shape, dtype, height, width, channels);
        return engine().makeTensorFromDataId(dataId, shape, dtype, backend);
    }
}
MathBackendWebGL.nextDataId = 0;
function float32ToTypedArray(a, dtype) {
    if (dtype === 'float32' || dtype === 'complex64') {
        return a;
    }
    else if (dtype === 'int32' || dtype === 'bool') {
        const result = (dtype === 'int32') ? new Int32Array(a.length) :
            new Uint8Array(a.length);
        for (let i = 0; i < result.length; ++i) {
            result[i] = Math.round(a[i]);
        }
        return result;
    }
    else {
        throw new Error(`Unknown dtype ${dtype}`);
    }
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYmFja2VuZF93ZWJnbC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uL3RmanMtYmFja2VuZC13ZWJnbC9zcmMvYmFja2VuZF93ZWJnbC50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7O0dBZUc7QUFFSCxzQkFBc0I7QUFDdEIsT0FBTyxlQUFlLENBQUM7QUFHdkIsT0FBTyxFQUFDLFlBQVksRUFBaUIsTUFBTSxFQUFVLFdBQVcsRUFBa0MsTUFBTSxFQUFFLEdBQUcsRUFBVyxZQUFZLEVBQUUsYUFBYSxFQUFjLFNBQVMsRUFBeUMsTUFBTSxFQUF3RCxJQUFJLEVBQTBCLElBQUksRUFBWSxNQUFNLHVCQUF1QixDQUFDO0FBQzdWLE9BQU8sRUFBQyxlQUFlLEVBQUMsTUFBTSxlQUFlLENBQUM7QUFDOUMsT0FBTyxFQUFDLG1CQUFtQixFQUFDLE1BQU0scUJBQXFCLENBQUM7QUFDeEQsT0FBTyxFQUFDLHlCQUF5QixFQUFDLE1BQU0sNEJBQTRCLENBQUM7QUFDckUsT0FBTyxFQUFDLGtCQUFrQixFQUFDLE1BQU0sb0JBQW9CLENBQUM7QUFDdEQsT0FBTyxFQUFDLHdCQUF3QixFQUFDLE1BQU0sMkJBQTJCLENBQUM7QUFDbkUsT0FBTyxFQUFDLG1CQUFtQixFQUFDLE1BQU0scUJBQXFCLENBQUM7QUFDeEQsT0FBTyxFQUFDLHlCQUF5QixFQUFDLE1BQU0sNEJBQTRCLENBQUM7QUFDckUsT0FBTyxFQUFDLFlBQVksRUFBQyxNQUFNLGlCQUFpQixDQUFDO0FBQzdDLE9BQU8sS0FBSyxVQUFVLE1BQU0sY0FBYyxDQUFDO0FBQzNDLE9BQU8sRUFBQyxtQkFBbUIsRUFBd0MsTUFBTSxjQUFjLENBQUM7QUFDeEYsT0FBTyxFQUFDLGdCQUFnQixFQUFDLE1BQU0sdUJBQXVCLENBQUM7QUFDdkQsT0FBTyxFQUFDLFdBQVcsRUFBQyxNQUFNLFlBQVksQ0FBQztBQUN2QyxPQUFPLEVBQUMsb0JBQW9CLEVBQUMs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KlxuICogQGxpY2Vuc2VcbiAqIENvcHlyaWdodCAyMDE3IEdvb2dsZSBMTEMuIEFsbCBSaWdodHMgUmVzZXJ2ZWQuXG4gKiBMaWNlbnNlZCB1bmRlciB0aGUgQXBhY2hlIExpY2Vuc2UsIFZlcnNpb24gMi4wICh0aGUgXCJMaWNlbnNlXCIpO1xuICogeW91IG1heSBub3QgdXNlIHRoaXMgZmlsZSBleGNlcHQgaW4gY29tcGxpYW5jZSB3aXRoIHRoZSBMaWNlbnNlLlxuICogWW91IG1heSBvYnRhaW4gYSBjb3B5IG9mIHRoZSBMaWNlbnNlIGF0XG4gKlxuICogaHR0cDovL3d3dy5hcGFjaGUub3JnL2xpY2Vuc2VzL0xJQ0VOU0UtMi4wXG4gKlxuICogVW5sZXNzIHJlcXVpcmVkIGJ5IGFwcGxpY2FibGUgbGF3IG9yIGFncmVlZCB0byBpbiB3cml0aW5nLCBzb2Z0d2FyZVxuICogZGlzdHJpYnV0ZWQgdW5kZXIgdGhlIExpY2Vuc2UgaXMgZGlzdHJpYnV0ZWQgb24gYW4gXCJBUyBJU1wiIEJBU0lTLFxuICogV0lUSE9VVCBXQVJSQU5USUVTIE9SIENPTkRJVElPTlMgT0YgQU5ZIEtJTkQsIGVpdGhlciBleHByZXNzIG9yIGltcGxpZWQuXG4gKiBTZWUgdGhlIExpY2Vuc2UgZm9yIHRoZSBzcGVjaWZpYyBsYW5ndWFnZSBnb3Zlcm5pbmcgcGVybWlzc2lvbnMgYW5kXG4gKiBsaW1pdGF0aW9ucyB1bmRlciB0aGUgTGljZW5zZS5cbiAqID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG4gKi9cblxuLy8gSW1wb3J0IHdlYmdsIGZsYWdzLlxuaW1wb3J0ICcuL2ZsYWdzX3dlYmdsJztcblxuaW1wb3J0ICogYXMgdGYgZnJvbSAnQHRlbnNvcmZsb3cvdGZqcy1jb3JlJztcbmltcG9ydCB7YmFja2VuZF91dGlsLCBCYWNrZW5kVmFsdWVzLCBidWZmZXIsIERhdGFJZCwgRGF0YVN0b3JhZ2UsIERhdGFUb0dQVVdlYkdMT3B0aW9uLCBEYXRhVHlwZSwgZW5naW5lLCBlbnYsIEdQVURhdGEsIGtlcm5lbF9pbXBscywgS2VybmVsQmFja2VuZCwgTWVtb3J5SW5mbywgbmV4dEZyYW1lLCBOdW1lcmljRGF0YVR5cGUsIFJhbmssIFJlY3Vyc2l2ZUFycmF5LCBzY2FsYXIsIFNoYXBlTWFwLCBUZW5zb3IsIFRlbnNvcjJELCBUZW5zb3JCdWZmZXIsIFRlbnNvckluZm8sIHRpZHksIFRpbWluZ0luZm8sIFR5cGVkQXJyYXksIHV0aWwsIFdlYkdMRGF0YX0gZnJvbSAnQHRlbnNvcmZsb3cvdGZqcy1jb3JlJztcbmltcG9ydCB7Z2V0V2ViR0xDb250ZXh0fSBmcm9tICcuL2NhbnZhc191dGlsJztcbmltcG9ydCB7RGVjb2RlTWF0cml4UHJvZ3JhbX0gZnJvbSAnLi9kZWNvZGVfbWF0cml4X2dwdSc7XG5pbXBvcnQge0RlY29kZU1hdHJpeFBhY2tlZFByb2dyYW19IGZyb20gJy4vZGVjb2RlX21hdHJpeF9wYWNrZWRfZ3B1JztcbmltcG9ydCB7RW5jb2RlRmxvYXRQcm9ncmFtfSBmcm9tICcuL2VuY29kZV9mbG9hdF9ncHUnO1xuaW1wb3J0IHtFbmNvZGVGbG9hdFBhY2tlZFByb2dyYW19IGZyb20gJy4vZW5jb2RlX2Zsb2F0X3BhY2tlZF9ncHUnO1xuaW1wb3J0IHtFbmNvZGVNYXRyaXhQcm9ncmFtfSBmcm9tICcuL2VuY29kZV9tYXRyaXhfZ3B1JztcbmltcG9ydCB7RW5jb2RlTWF0cml4UGFja2VkUHJvZ3JhbX0gZnJvbSAnLi9lbmNvZGVfbWF0cml4X3BhY2tlZF9ncHUnO1xuaW1wb3J0IHtHUEdQVUNvbnRleHR9IGZyb20gJy4vZ3BncHVfY29udGV4dCc7XG5pbXBvcnQgKiBhcyBncGdwdV9tYXRoIGZyb20gJy4vZ3BncHVfbWF0aCc7XG5pbXBvcnQge2dldFVuaWZvcm1Mb2NhdGlvbnMsIEdQR1BVQmluYXJ5LCBHUEdQVVByb2dyYW0sIFRlbnNvckRhdGF9IGZyb20gJy4vZ3BncHVfbWF0aCc7XG5pbXBvcnQge3NpbXBsZUFic0ltcGxDUFV9IGZyb20gJy4va2VybmVsX3V0aWxzL3NoYXJlZCc7XG5pbXBvcnQge1BhY2tQcm9ncmFtfSBmcm9tICcuL3BhY2tfZ3B1JztcbmltcG9ydCB7UmVzaGFwZVBhY2tlZFByb2dyYW19IGZyb20gJy4vcmVzaGFwZV9wYWNrZWRfZ3B1JztcbmltcG9ydCAqIGFzIHRleF91dGlsIGZyb20gJy4vdGV4X3V0aWwnO1xuaW1wb3J0IHtUZXh0dXJlLCBUZXh0dXJlRGF0YSwgVGV4dHVyZVVzYWdlfSBmcm9tICcuL3RleF91dGlsJztcbmltcG9ydCB7VGV4dHVyZU1hbmFnZXJ9IGZyb20gJy4vdGV4dHVyZV9tYW5hZ2VyJztcbmltcG9ydCAqIGFzIHVuYXJ5X29wIGZyb20gJy4vdW5hcnlvcF9ncHUnO1xuaW1wb3J0IHtVbmFyeU9wUHJvZ3JhbX0gZnJvbSAnLi91bmFyeW9wX2dwdSc7XG5pbXBvcnQge1VuYXJ5T3BQYWNrZWRQcm9ncmFtfSBmcm9tICcuL3VuYXJ5b3BfcGFja2VkX2dwdSc7XG5pbXBvcnQge1VucGFja1Byb2dyYW19IGZyb20gJy4vdW5wYWNrX2dwdSc7XG5pbXBvcnQgKiBhcyB3ZWJnbF91dGlsIGZyb20gJy4vd2ViZ2xfdXRpbCc7XG5cbmNvbnN0IHdoZXJlSW1wbCA9IGtlcm5lbF9pbXBscy53aGVyZUltcGw7XG5cbmV4cG9ydCBjb25zdCBFUFNJTE9OX0ZMT0FUMzIgPSAxZS03O1xuZXhwb3J0IGNvbnN0IEVQU0lMT05fRkxPQVQxNiA9IDFlLTQ7XG5cbnR5cGUgS2VybmVsSW5mbyA9IHtcbiAgbmFtZTogc3RyaW5nOyBxdWVyeTogUHJvbWlzZTxudW1iZXI+O1xufTtcblxuZXhwb3J0IHR5cGUgVGltZXJOb2RlID0gUmVjdXJzaXZlQXJyYXk8S2VybmVsSW5mbz58S2VybmVsSW5mbztcbmV4cG9ydCBpbnRlcmZhY2UgQ1BVVGltZXJRdWVyeSB7XG4gIHN0YXJ0TXM6IG51bWJlcjtcbiAgZW5kTXM/OiBudW1iZXI7XG59XG5cbmV4cG9ydCBpbnRlcmZhY2UgV2ViR0xNZW1vcnlJbmZvIGV4dGVuZHMgTWVtb3J5SW5mbyB7XG4gIG51bUJ5dGVzSW5HUFU6IG51bWJlcjtcbiAgLy8gVHJhY2tzIHRoZSB0b3RhbCBudW1iZXIgb2YgYnl0ZXMgYWxsb2NhdGVkIG9uIHRoZSBHUFUsIGFjY291bnRpbmcgZm9yIHRoZVxuICAvLyBwaHlzaWNhbCB0ZXh0dXJlIHR5cGUuXG4gIG51bUJ5dGVzSW5HUFVBbGxvY2F0ZWQ6IG51bWJlcjtcbiAgLy8gVHJhY2tzIGJ5dGUgc2l6ZSBvZiB0ZXh0dXJlcyB0aGF0IHdlcmUgY3JlYXRlZCBhbmQgdGhlbiBtYWRlIGF2YWlsYWJsZSBmb3JcbiAgLy8gcmV1c2UgKGRpc3Bvc2VkKS5cbiAgbnVtQnl0ZXNJbkdQVUZyZWU6IG51bWJlcjtcbiAgdW5yZWxpYWJsZTogYm9vbGVhbjtcbn1cblxuZXhwb3J0IGludGVyZmFjZSBXZWJHTFRpbWluZ0luZm8gZXh0ZW5kcyBUaW1pbmdJbmZvIHtcbiAgdXBsb2FkV2FpdE1zOiBudW1iZXI7XG4gIGRvd25sb2FkV2FpdE1zOiBudW1iZXI7XG59XG5cbmNvbnN0IGJpbmFyeUNhY2hlczoge1t3ZWJHTFZlcnNpb246IHN0cmluZ106IHtba2V5OiBzdHJpbmddOiBHUEdQVUJpbmFyeX19ID0ge307XG5cbmV4cG9ydCBmdW5jdGlvbiBnZXRCaW5hcnlDYWNoZSh3ZWJHTFZlcnNpb246IG51bWJlcikge1xuICBpZiAod2ViR0xWZXJzaW9uIGluIGJpbmFyeUNhY2hlcykge1xuICAgIHJldHVybiBiaW5hcnlDYWNoZXNbd2ViR0xWZXJzaW9uXTtcbiAgfVxuICBiaW5hcnlDYWNoZXNbd2ViR0xWZXJzaW9uXSA9IHt9O1xuICByZXR1cm4gYmluYXJ5Q2FjaGVzW3dlYkdMVmVyc2lvbl07XG59XG5cbi8vIEVtcGlyaWNhbGx5IGRldGVybWluZWQgY29uc3RhbnQgdXNlZCB0byBkZXRlcm1pbmUgc2l6ZSB0aHJlc2hvbGQgZm9yIGhhbmRpbmdcbi8vIG9mZiBleGVjdXRpb24gdG8gdGhlIENQVS5cbmNvbnN0IENQVV9IQU5ET0ZGX1NJWkVfVEhSRVNIT0xEID1cbiAgICBlbnYoKS5nZXROdW1iZXIoJ0NQVV9IQU5ET0ZGX1NJWkVfVEhSRVNIT0xEJyk7XG5cbi8vIEVtcGlyaWNhbGx5IGRldGVybWluZWQgY29uc3RhbnQgdXNlZCB0byBkZWNpZGUgdGhlIG51bWJlciBvZiBNQiBvbiBHUFVcbi8vIGJlZm9yZSB3ZSB3YXJuIGFib3V0IGhpZ2ggbWVtb3J5IHVzZS4gVGhlIE1CIGFyZSB0aGlzIGNvbnN0YW50ICogc2NyZWVuIGFyZWFcbi8vICogZHBpIC8gMTAyNCAvIDEwMjQuXG5jb25zdCBCRUZPUkVfUEFHSU5HX0NPTlNUQU5UID0gNjAwO1xuZnVuY3Rpb24gbnVtTUJCZWZvcmVXYXJuaW5nKCk6IG51bWJlciB7XG4gIGlmIChlbnYoKS5nbG9iYWwuc2NyZWVuID09IG51bGwpIHtcbiAgICByZXR1cm4gMTAyNDsgIC8vIDEgR0IuXG4gIH1cbiAgcmV0dXJuIChlbnYoKS5nbG9iYWwuc2NyZWVuLmhlaWdodCAqIGVudigpLmdsb2JhbC5zY3JlZW4ud2lkdGggKlxuICAgICAgICAgIHdpbmRvdy5kZXZpY2VQaXhlbFJhdGlvKSAqXG4gICAgICBCRUZPUkVfUEFHSU5HX0NPTlNUQU5UIC8gMTAyNCAvIDEwMjQ7XG59XG5cbmV4cG9ydCBjbGFzcyBNYXRoQmFja2VuZFdlYkdMIGV4dGVuZHMgS2VybmVsQmFja2VuZCB7XG4gIHRleERhdGE6IERhdGFTdG9yYWdlPFRleHR1cmVEYXRhPjtcbiAgZ3BncHU6IEdQR1BVQ29udGV4dDtcblxuICBwcml2YXRlIHN0YXRpYyBuZXh0RGF0YUlkID0gMDtcbiAgcHJpdmF0ZSBuZXh0RGF0YUlkKCk6IG51bWJlciB7XG4gICAgcmV0dXJuIE1hdGhCYWNrZW5kV2ViR0wubmV4dERhdGFJZCsrO1xuICB9XG4gIC8vIE1hcHMgZGF0YSBpZHMgdGhhdCBoYXZlIGEgcGVuZGluZyByZWFkIG9wZXJhdGlvbiwgdG8gbGlzdCBvZiBzdWJzY3JpYmVycy5cbiAgcHJpdmF0ZSBwZW5kaW5nUmVhZCA9IG5ldyBXZWFrTWFwPERhdGFJZCwgQXJyYXk8KGFycjogVHlwZWRBcnJheSkgPT4gdm9pZD4+KCk7XG4gIC8vIExpc3Qgb2YgZGF0YSBpZHMgdGhhdCBhcmUgc2NoZWR1bGVkIGZvciBkaXNwb3NhbCwgYnV0IGFyZSB3YWl0aW5nIG9uIGFcbiAgLy8gcGVuZGluZyByZWFkIG9wZXJhdGlvbi5cbiAgcHJpdmF0ZSBwZW5kaW5nRGlzcG9zYWwgPSBuZXcgV2Vha1NldDxEYXRhSWQ+KCk7XG5cbiAgLy8gVXNlZCB0byBjb3VudCB0aGUgbnVtYmVyIG9mICdzaGFsbG93JyBzbGljZWQgdGVuc29ycyB0aGF0IHBvaW50IHRvIHRoZVxuICAvLyBzYW1lIGRhdGEgaWQuXG4gIGRhdGFSZWZDb3VudCA9IG5ldyBXZWFrTWFwPERhdGFJZCwgbnVtYmVyPigpO1xuICBwcml2YXRlIG51bUJ5dGVzSW5HUFUgPSAwO1xuXG4gIHByaXZhdGUgY2FudmFzOiBIVE1MQ2FudmFzRWxlbWVudHxPZmZzY3JlZW5DYW52YXM7XG5cbiAgcHJpdmF0ZSBwcm9ncmFtVGltZXJzU3RhY2s6IFRpbWVyTm9kZVtdO1xuICBwcml2YXRlIGFjdGl2ZVRpbWVyczogVGltZXJOb2RlW107XG4gIC8vIEFjY3VtdWxhdGVkIHRpbWUgc3BlbnQgKGluY2x1ZGluZyBibG9ja2luZykgaW4gdXBsb2FkaW5nIGRhdGEgdG8gd2ViZ2wuXG4gIHByaXZhdGUgdXBsb2FkV2FpdE1zID0gMDtcbiAgLy8gQWNjdW11bGF0ZWQgdGltZSBzcGVudCAoaW5jbHVkaW5nIGJsb2NraW5nIGluIGRvd25sb2FkaW5nIGRhdGEgZnJvbSB3ZWJnbC5cbiAgcHJpdmF0ZSBkb3dubG9hZFdhaXRNcyA9IDA7XG5cbiAgLy8gcmVjb3JkIHRoZSBsYXN0IG1hbnVhbCBHTCBGbHVzaCB0aW1lLlxuICBwcml2YXRlIGxhc3RHbEZsdXNoVGltZSA9IDA7XG5cbiAgLy8gTnVtYmVyIG9mIGJpdHMgb2YgcHJlY2lzaW9uIG9mIHRoaXMgYmFja2VuZC5cbiAgcHJpdmF0ZSBmbG9hdFByZWNpc2lvblZhbHVlOiAzMnwxNjtcblxuICBwcml2YXRlIHRleHR1cmVNYW5hZ2VyOiBUZXh0dXJlTWFuYWdlcjtcbiAgcHJpdmF0ZSBiaW5hcnlDYWNoZToge1trZXk6IHN0cmluZ106IEdQR1BVQmluYXJ5fTtcbiAgcHJpdmF0ZSBncGdwdUNyZWF0ZWRMb2NhbGx5OiBib29sZWFuO1xuICBwcml2YXRlIG51bU1CQmVmb3JlV2FybmluZzogbnVtYmVyO1xuICBwcml2YXRlIHdhcm5lZEFib3V0TWVtb3J5ID0gZmFsc2U7XG5cbiAgY29uc3RydWN0b3IoZ3B1UmVzb3VyY2U/OiBHUEdQVUNvbnRleHR8SFRNTENhbnZhc0VsZW1lbnR8T2Zmc2NyZWVuQ2FudmFzKSB7XG4gICAgc3VwZXIoKTtcbiAgICBpZiAoIWVudigpLmdldEJvb2woJ0hBU19XRUJHTCcpKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoJ1dlYkdMIGlzIG5vdCBzdXBwb3J0ZWQgb24gdGhpcyBkZXZpY2UnKTtcbiAgICB9XG5cbiAgICBsZXQgbmV3R1BHUFU7XG4gICAgaWYgKGdwdVJlc291cmNlICE9IG51bGwpIHtcbiAgICAgIGlmIChncHVSZXNvdXJjZSBpbnN0YW5jZW9mIEdQR1BVQ29udGV4dCkge1xuICAgICAgICBuZXdHUEdQVSA9IGdwdVJlc291cmNlO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgY29uc3QgZ2wgPVxuICAgICAgICAgICAgZ2V0V2ViR0xDb250ZXh0KGVudigpLmdldE51bWJlcignV0VCR0xfVkVSU0lPTicpLCBncHVSZXNvdXJjZSk7XG4gICAgICAgIG5ld0dQR1BVID0gbmV3IEdQR1BVQ29udGV4dChnbCk7XG4gICAgICB9XG4gICAgICB0aGlzLmJpbmFyeUNhY2hlID0ge307XG4gICAgICB0aGlzLmdwZ3B1Q3JlYXRlZExvY2FsbHkgPSBmYWxzZTtcbiAgICB9IGVsc2Uge1xuICAgICAgY29uc3QgZ2wgPSBnZXRXZWJHTENvbnRleHQoZW52KCkuZ2V0TnVtYmVyKCdXRUJHTF9WRVJTSU9OJykpO1xuICAgICAgbmV3R1BHUFUgPSBuZXcgR1BHUFVDb250ZXh0KGdsKTtcbiAgICAgIHRoaXMuYmluYXJ5Q2FjaGUgPSBnZXRCaW5hcnlDYWNoZShlbnYoKS5nZXROdW1iZXIoJ1dFQkdMX1ZFUlNJT04nKSk7XG4gICAgICB0aGlzLmdwZ3B1Q3JlYXRlZExvY2FsbHkgPSB0cnVlO1xuICAgIH1cblxuICAgIHRoaXMuZ3BncHUgPSBuZXdHUEdQVTtcbiAgICB0aGlzLmNhbnZhcyA9IHRoaXMuZ3BncHUuZ2wuY2FudmFzO1xuICAgIHRoaXMudGV4dHVyZU1hbmFnZXIgPSBuZXcgVGV4dHVyZU1hbmFnZXIodGhpcy5ncGdwdSk7XG4gICAgdGhpcy5udW1NQkJlZm9yZVdhcm5pbmcgPSBudW1NQkJlZm9yZVdhcm5pbmcoKTtcbiAgICB0aGlzLnRleERhdGEgPSBuZXcgRGF0YVN0b3JhZ2UodGhpcywgZW5naW5lKCkpO1xuICB9XG5cbiAgb3ZlcnJpZGUgbnVtRGF0YUlkcygpIHtcbiAgICByZXR1cm4gdGhpcy50ZXhEYXRhLm51bURhdGFJZHMoKSAtIHRoaXMucGVuZGluZ0RlbGV0ZXM7XG4gIH1cblxuICAvLyBXcml0ZXMgYSBuZXcgZW50cnkgdG8gdGhlIGRhdGEgc3RvcmUgd2l0aCBhIFdlYkdMIHRleHR1cmUsIGFuZCByZWdpc3RlcnMgaXRcbiAgLy8gdG8gdGhlIHRleHR1cmUgbWFuYWdlci5cbiAgd3JpdGVUZXh0dXJlKFxuICAgICAgdGV4dHVyZTogV2ViR0xUZXh0dXJlLCBzaGFwZTogbnVtYmVyW10sIGR0eXBlOiBEYXRhVHlwZSxcbiAgICAgIHRleEhlaWdodDogbnVtYmVyLCB0ZXhXaWR0aDogbnVtYmVyLCBjaGFubmVsczogc3RyaW5nKTogRGF0YUlkIHtcbiAgICAvLyBUZW1wb3JhcmlseSBjcmVhdGUgYW4gdGVuc29yIGluZm8gdG8gbWFrZSB0aGUgdGV4dHVyZSBjb21wYXRpYmxlIHdpdGhcbiAgICAvLyB0aGUgcnVuV2ViR0xQcm9ncmFtJ3MgaW5wdXQuXG4gICAgY29uc3QgaW5wdXQgPSB0aGlzLm1ha2VUZW5zb3JJbmZvKHNoYXBlLCBkdHlwZSk7XG4gICAgY29uc3QgaW5EYXRhID0gdGhpcy50ZXhEYXRhLmdldChpbnB1dC5kYXRhSWQpO1xuICAgIC8vIEV2ZW4gdGhvdWdoIHRoZSBpbnB1dCB0ZXh0dXJlIGNvdWxkIGJlIHVucGFja2VkIG9yIGRlbnNlIHBhY2tlZCwgaXQgaXNcbiAgICAvLyBhbHdheXMgY29uc2lkZXJlZCBhcyB1bnBhY2tlZCBmb3IgRW5jb2RlTWF0cml4UHJvZ3JhbS5cbiAgICBpbkRhdGEuaXNQYWNrZWQgPSBmYWxzZTtcblxuICAgIC8vIEJpbmQgdGV4dHVyZSB0byB0aGUgaW5wdXQgdGVuc29yLlxuICAgIGluRGF0YS50ZXh0dXJlID0ge3RleHR1cmUsIHRleFNoYXBlOiBbdGV4SGVpZ2h0LCB0ZXhXaWR0aF19O1xuICAgIGluRGF0YS50ZXhTaGFwZSA9IFt0ZXhIZWlnaHQsIHRleFdpZHRoXTtcblxuICAgIGNvbnN0IHNoYXBlQXMzRCA9IHdlYmdsX3V0aWwuZ2V0U2hhcGVBczNEKHNoYXBlKTtcbiAgICBjb25zdCBwcm9ncmFtID1cbiAgICAgICAgbmV3IEVuY29kZU1hdHJpeFByb2dyYW0oc2hhcGVBczNELCBmYWxzZSAvKiBpc0J5dGVBcnJheSAqLywgY2hhbm5lbHMpO1xuICAgIGNvbnN0IG91dHB1dCA9XG4gICAgICAgIHRoaXMucnVuV2ViR0xQcm9ncmFtKHByb2dyYW0sIFtpbnB1dF0sIGR0eXBlLCBbW3RleEhlaWdodCwgdGV4V2lkdGhdXSk7XG4gICAgb3V0cHV0LnNoYXBlID0gc2hhcGU7XG5cbiAgICAvLyBVbmJpbmQgdGhlIHRleHR1cmUgZnJvbSB0aGUgaW5wdXQgdGVuc29yIHRvIGF2b2lkIHRoZSB0ZXh0dXJlIGJlaW5nXG4gICAgLy8gcmVsZWFzZWQuXG4gICAgaW5EYXRhLnRleHR1cmUgPSBudWxsO1xuICAgIHRoaXMuZGlzcG9zZUludGVybWVkaWF0ZVRlbnNvckluZm8oaW5wdXQpO1xuXG4gICAgcmV0dXJuIG91dHB1dC5kYXRhSWQ7XG4gIH1cblxuICBvdmVycmlkZSB3cml0ZSh2YWx1ZXM6IEJhY2tlbmRWYWx1ZXMsIHNoYXBlOiBudW1iZXJbXSwgZHR5cGU6IERhdGFUeXBlKTpcbiAgICAgIERhdGFJZCB7XG4gICAgaWYgKGVudigpLmdldEJvb2woJ1dFQkdMX0NIRUNLX05VTUVSSUNBTF9QUk9CTEVNUycpIHx8XG4gICAgICAgIGVudigpLmdldEJvb2woJ0RFQlVHJykpIHtcbiAgICAgIHRoaXMuY2hlY2tOdW1lcmljYWxQcm9ibGVtcyh2YWx1ZXMpO1xuICAgIH1cbiAgICBpZiAoZHR5cGUgPT09ICdjb21wbGV4NjQnICYmIHZhbHVlcyAhPSBudWxsKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgYENhbm5vdCB3cml0ZSB0byBhIGNvbXBsZXg2NCBkdHlwZS4gYCArXG4gICAgICAgICAgYFBsZWFzZSB1c2UgdGYuY29tcGxleChyZWFsLCBpbWFnKS5gKTtcbiAgICB9XG4gICAgY29uc3QgZGF0YUlkID0ge2lkOiB0aGlzLm5leHREYXRhSWQoKX07XG4gICAgdGhpcy50ZXhEYXRhLnNldChcbiAgICAgICAgZGF0YUlkLFxuICAgICAgICB7c2hhcGUsIGR0eXBlLCB2YWx1ZXMsIHVzYWdlOiBUZXh0dXJlVXNhZ2UuVVBMT0FELCByZWZDb3VudDogMX0pO1xuICAgIHJldHVybiBkYXRhSWQ7XG4gIH1cblxuICAvKiogUmV0dXJuIHJlZkNvdW50IG9mIGEgYFRlbnNvckRhdGFgLiAqL1xuICBvdmVycmlkZSByZWZDb3VudChkYXRhSWQ6IERhdGFJZCk6IG51bWJlciB7XG4gICAgaWYgKHRoaXMudGV4RGF0YS5oYXMoZGF0YUlkKSkge1xuICAgICAgY29uc3QgdGVuc29yRGF0YSA9IHRoaXMudGV4RGF0YS5nZXQoZGF0YUlkKTtcbiAgICAgIHJldHVybiB0ZW5zb3JEYXRhLnJlZkNvdW50O1xuICAgIH1cbiAgICByZXR1cm4gMDtcbiAgfVxuXG4gIC8qKiBJbmNyZWFzZSByZWZDb3VudCBvZiBhIGBUZXh0dXJlRGF0YWAuICovXG4gIG92ZXJyaWRlIGluY1JlZihkYXRhSWQ6IERhdGFJZCk6IHZvaWQge1xuICAgIGNvbnN0IHRleERhdGEgPSB0aGlzLnRleERhdGEuZ2V0KGRhdGFJZCk7XG4gICAgdGV4RGF0YS5yZWZDb3VudCsrO1xuICB9XG5cbiAgLyoqIERlY3JlYXNlIHJlZkNvdW50IG9mIGEgYFRleHR1cmVEYXRhYC4gKi9cbiAgZGVjUmVmKGRhdGFJZDogRGF0YUlkKTogdm9pZCB7XG4gICAgaWYgKHRoaXMudGV4RGF0YS5oYXMoZGF0YUlkKSkge1xuICAgICAgY29uc3QgdGV4RGF0YSA9IHRoaXMudGV4RGF0YS5nZXQoZGF0YUlkKTtcbiAgICAgIHRleERhdGEucmVmQ291bnQtLTtcbiAgICB9XG4gIH1cblxuICBvdmVycmlkZSBtb3ZlKFxuICAgICAgZGF0YUlkOiBEYXRhSWQsIHZhbHVlczogQmFja2VuZFZhbHVlcywgc2hhcGU6IG51bWJlcltdLCBkdHlwZTogRGF0YVR5cGUsXG4gICAgICByZWZDb3VudDogbnVtYmVyKTogdm9pZCB7XG4gICAgaWYgKGVudigpLmdldEJvb2woJ0RFQlVHJykpIHtcbiAgICAgIHRoaXMuY2hlY2tOdW1lcmljYWxQcm9ibGVtcyh2YWx1ZXMpO1xuICAgIH1cbiAgICBpZiAoZHR5cGUgPT09ICdjb21wbGV4NjQnKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgYENhbm5vdCB3cml0ZSB0byBhIGNvbXBsZXg2NCBkdHlwZS4gYCArXG4gICAgICAgICAgYFBsZWFzZSB1c2UgdGYuY29tcGxleChyZWFsLCBpbWFnKS5gKTtcbiAgICB9XG4gICAgdGhpcy50ZXhEYXRhLnNldChcbiAgICAgICAgZGF0YUlkLCB7c2hhcGUsIGR0eXBlLCB2YWx1ZXMsIHVzYWdlOiBUZXh0dXJlVXNhZ2UuVVBMT0FELCByZWZDb3VudH0pO1xuICB9XG5cbiAgZGlzcG9zZUludGVybWVkaWF0ZVRlbnNvckluZm8odGVuc29ySW5mbzogVGVuc29ySW5mbyk6IHZvaWQge1xuICAgIHRoaXMuZGlzcG9zZURhdGEodGVuc29ySW5mby5kYXRhSWQpO1xuICB9XG5cbiAgb3ZlcnJpZGUgcmVhZFN5bmMoZGF0YUlkOiBEYXRhSWQpOiBCYWNrZW5kVmFsdWVzIHtcbiAgICBjb25zdCB0ZXhEYXRhID0gdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpO1xuICAgIGNvbnN0IHt2YWx1ZXMsIGR0eXBlLCBjb21wbGV4VGVuc29ySW5mb3MsIHNsaWNlLCBzaGFwZSwgaXNQYWNrZWR9ID0gdGV4RGF0YTtcblxuICAgIC8vIFRoZSBwcmVzZW5jZSBvZiBgc2xpY2VgIGluZGljYXRlcyB0aGlzIHRlbnNvciBpcyBhIHNoYWxsb3cgc2xpY2Ugb2YgYVxuICAgIC8vIGRpZmZlcmVudCB0ZW5zb3IsIGFuZCBpcyB1c2luZyB0aGF0IG9yaWdpbmFsIHRlbnNvcidzIHRleHR1cmUuIFJ1blxuICAgIC8vIGBjbG9uZWAgaW4gb3JkZXIgdG8gY29weSB0aGF0IHRleHR1cmUgYW5kIHJlYWQgZnJvbSBpdC5cbiAgICBpZiAoc2xpY2UgIT0gbnVsbCkge1xuICAgICAgbGV0IHByb2dyYW07XG4gICAgICBpZiAoaXNQYWNrZWQpIHtcbiAgICAgICAgcHJvZ3JhbSA9IG5ldyBVbmFyeU9wUGFja2VkUHJvZ3JhbShzaGFwZSwgdW5hcnlfb3AuQ0xPTkUpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgcHJvZ3JhbSA9IG5ldyBVbmFyeU9wUHJvZ3JhbShzaGFwZSwgdW5hcnlfb3AuQ0xPTkUpO1xuICAgICAgfVxuICAgICAgY29uc3QgcmVzID1cbiAgICAgICAgICB0aGlzLnJ1bldlYkdMUHJvZ3JhbShwcm9ncmFtLCBbe2RhdGFJZCwgc2hhcGUsIGR0eXBlfV0sIGR0eXBlKTtcbiAgICAgIGNvbnN0IGRhdGEgPSB0aGlzLnJlYWRTeW5jKHJlcy5kYXRhSWQpO1xuICAgICAgdGhpcy5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ySW5mbyhyZXMpO1xuICAgICAgcmV0dXJuIGRhdGE7XG4gICAgfVxuICAgIGlmICh2YWx1ZXMgIT0gbnVsbCkge1xuICAgICAgcmV0dXJuIHRoaXMuY29udmVydEFuZENhY2hlT25DUFUoZGF0YUlkKTtcbiAgICB9XG4gICAgaWYgKGR0eXBlID09PSAnc3RyaW5nJykge1xuICAgICAgcmV0dXJuIHZhbHVlcztcbiAgICB9XG4gICAgY29uc3Qgc2hvdWxkVGltZVByb2dyYW0gPSB0aGlzLmFjdGl2ZVRpbWVycyAhPSBudWxsO1xuICAgIGxldCBzdGFydDogbnVtYmVyO1xuICAgIGlmIChzaG91bGRUaW1lUHJvZ3JhbSkge1xuICAgICAgc3RhcnQgPSB1dGlsLm5vdygpO1xuICAgIH1cblxuICAgIGxldCByZXN1bHQ6IEZsb2F0MzJBcnJheTtcbiAgICBpZiAoZHR5cGUgPT09ICdjb21wbGV4NjQnKSB7XG4gICAgICBjb25zdCByZWFsVmFsdWVzID1cbiAgICAgICAgICB0aGlzLnJlYWRTeW5jKGNvbXBsZXhUZW5zb3JJbmZvcy5yZWFsLmRhdGFJZCkgYXMgRmxvYXQzMkFycmF5O1xuICAgICAgY29uc3QgaW1hZ1ZhbHVlcyA9XG4gICAgICAgICAgdGhpcy5yZWFkU3luYyhjb21wbGV4VGVuc29ySW5mb3MuaW1hZy5kYXRhSWQpIGFzIEZsb2F0MzJBcnJheTtcbiAgICAgIHJlc3VsdCA9IGJhY2tlbmRfdXRpbC5tZXJnZVJlYWxBbmRJbWFnQXJyYXlzKHJlYWxWYWx1ZXMsIGltYWdWYWx1ZXMpO1xuICAgIH0gZWxzZSB7XG4gICAgICByZXN1bHQgPSB0aGlzLmdldFZhbHVlc0Zyb21UZXh0dXJlKGRhdGFJZCk7XG4gICAgfVxuXG4gICAgaWYgKHNob3VsZFRpbWVQcm9ncmFtKSB7XG4gICAgICB0aGlzLmRvd25sb2FkV2FpdE1zICs9IHV0aWwubm93KCkgLSBzdGFydDtcbiAgICB9XG4gICAgcmV0dXJuIHRoaXMuY29udmVydEFuZENhY2hlT25DUFUoZGF0YUlkLCByZXN1bHQpO1xuICB9XG5cbiAgb3ZlcnJpZGUgYXN5bmMgcmVhZChkYXRhSWQ6IERhdGFJZCk6IFByb21pc2U8QmFja2VuZFZhbHVlcz4ge1xuICAgIGlmICh0aGlzLnBlbmRpbmdSZWFkLmhhcyhkYXRhSWQpKSB7XG4gICAgICBjb25zdCBzdWJzY3JpYmVycyA9IHRoaXMucGVuZGluZ1JlYWQuZ2V0KGRhdGFJZCk7XG4gICAgICByZXR1cm4gbmV3IFByb21pc2U8VHlwZWRBcnJheT4ocmVzb2x2ZSA9PiBzdWJzY3JpYmVycy5wdXNoKHJlc29sdmUpKTtcbiAgICB9XG4gICAgY29uc3QgdGV4RGF0YSA9IHRoaXMudGV4RGF0YS5nZXQoZGF0YUlkKTtcbiAgICBjb25zdCB7dmFsdWVzLCBzaGFwZSwgc2xpY2UsIGR0eXBlLCBjb21wbGV4VGVuc29ySW5mb3MsIGlzUGFja2VkfSA9IHRleERhdGE7XG5cbiAgICAvLyBUaGUgcHJlc2VuY2Ugb2YgYHNsaWNlYCBpbmRpY2F0ZXMgdGhpcyB0ZW5zb3IgaXMgYSBzaGFsbG93IHNsaWNlIG9mIGFcbiAgICAvLyBkaWZmZXJlbnQgdGVuc29yLCBhbmQgaXMgdXNpbmcgdGhhdCBvcmlnaW5hbCB0ZW5zb3IncyB0ZXh0dXJlLiBSdW5cbiAgICAvLyBgY2xvbmVgIGluIG9yZGVyIHRvIGNvcHkgdGhhdCB0ZXh0dXJlIGFuZCByZWFkIGZyb20gaXQuXG4gICAgaWYgKHNsaWNlICE9IG51bGwpIHtcbiAgICAgIGxldCBwcm9ncmFtO1xuICAgICAgaWYgKGlzUGFja2VkKSB7XG4gICAgICAgIHByb2dyYW0gPSBuZXcgVW5hcnlPcFBhY2tlZFByb2dyYW0oc2hhcGUsIHVuYXJ5X29wLkNMT05FKTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHByb2dyYW0gPSBuZXcgVW5hcnlPcFByb2dyYW0oc2hhcGUsIHVuYXJ5X29wLkNMT05FKTtcbiAgICAgIH1cbiAgICAgIGNvbnN0IHJlcyA9XG4gICAgICAgICAgdGhpcy5ydW5XZWJHTFByb2dyYW0ocHJvZ3JhbSwgW3tkYXRhSWQsIHNoYXBlLCBkdHlwZX1dLCBkdHlwZSk7XG4gICAgICBjb25zdCBkYXRhID0gdGhpcy5yZWFkKHJlcy5kYXRhSWQpO1xuICAgICAgdGhpcy5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ySW5mbyhyZXMpO1xuICAgICAgcmV0dXJuIGRhdGE7XG4gICAgfVxuXG4gICAgaWYgKHZhbHVlcyAhPSBudWxsKSB7XG4gICAgICByZXR1cm4gdGhpcy5jb252ZXJ0QW5kQ2FjaGVPbkNQVShkYXRhSWQpO1xuICAgIH1cblxuICAgIGlmIChlbnYoKS5nZXRCb29sKCdERUJVRycpKSB7XG4gICAgICAvLyBnZXRCb29sKCdXRUJHTF9ET1dOTE9BRF9GTE9BVF9FTkFCTEVEJykgY2F1c2VkIGEgYmxvY2tpbmcgR1BVIGNhbGwuXG4gICAgICAvLyBGb3IgcGVyZm9ybWFuY2UgcmVhc29uLCBvbmx5IGNoZWNrIGl0IGZvciBkZWJ1Z2dpbmcuIEluIHByb2R1Y3Rpb24sXG4gICAgICAvLyBpdCBkb2Vzbid0IGhhbmRsZSB0aGlzIHVzZSBjYXNlIGFueXdheSwgc28gYmVoYXZpb3IgaXMgbm90IGNoYW5nZWQuXG4gICAgICBpZiAoIWVudigpLmdldEJvb2woJ1dFQkdMX0RPV05MT0FEX0ZMT0FUX0VOQUJMRUQnKSAmJlxuICAgICAgICAgIGVudigpLmdldE51bWJlcignV0VCR0xfVkVSU0lPTicpID09PSAyKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICAgIGB0ZW5zb3IuZGF0YSgpIHdpdGggV0VCR0xfRE9XTkxPQURfRkxPQVRfRU5BQkxFRD1mYWxzZSBhbmQgYCArXG4gICAgICAgICAgICBgV0VCR0xfVkVSU0lPTj0yIG5vdCB5ZXQgc3VwcG9ydGVkLmApO1xuICAgICAgfVxuICAgIH1cblxuICAgIGxldCBidWZmZXI6IFdlYkdMQnVmZmVyID0gbnVsbDtcbiAgICBsZXQgdG1wRG93bmxvYWRUYXJnZXQ6IFRlbnNvckluZm87XG5cbiAgICBpZiAoZHR5cGUgIT09ICdjb21wbGV4NjQnICYmIGVudigpLmdldCgnV0VCR0xfQlVGRkVSX1NVUFBPUlRFRCcpKSB7XG4gICAgICAvLyBQb3NzaWJseSBjb3B5IHRoZSB0ZXh0dXJlIGludG8gYSBidWZmZXIgYmVmb3JlIGluc2VydGluZyBhIGZlbmNlLlxuICAgICAgdG1wRG93bmxvYWRUYXJnZXQgPSB0aGlzLmRlY29kZShkYXRhSWQpO1xuICAgICAgY29uc3QgdG1wRGF0YSA9IHRoaXMudGV4RGF0YS5nZXQodG1wRG93bmxvYWRUYXJnZXQuZGF0YUlkKTtcblxuICAgICAgYnVmZmVyID0gdGhpcy5ncGdwdS5jcmVhdGVCdWZmZXJGcm9tVGV4dHVyZShcbiAgICAgICAgICB0bXBEYXRhLnRleHR1cmUudGV4dHVyZSwgLi4udGV4X3V0aWwuZ2V0RGVuc2VUZXhTaGFwZShzaGFwZSkpO1xuICAgIH1cblxuICAgIHRoaXMucGVuZGluZ1JlYWQuc2V0KGRhdGFJZCwgW10pO1xuXG4gICAgaWYgKGR0eXBlICE9PSAnY29tcGxleDY0Jykge1xuICAgICAgLy8gQ3JlYXRlIGEgZmVuY2UgYW5kIHdhaXQgZm9yIGl0IHRvIHJlc29sdmUuXG4gICAgICBhd2FpdCB0aGlzLmdwZ3B1LmNyZWF0ZUFuZFdhaXRGb3JGZW5jZSgpO1xuICAgIH1cblxuICAgIC8vIERvd25sb2FkIHRoZSB2YWx1ZXMgZnJvbSB0aGUgR1BVLlxuICAgIGxldCB2YWxzOiBGbG9hdDMyQXJyYXk7XG4gICAgaWYgKGR0eXBlID09PSAnY29tcGxleDY0Jykge1xuICAgICAgY29uc3QgcHMgPSBhd2FpdCBQcm9taXNlLmFsbChbXG4gICAgICAgIHRoaXMucmVhZChjb21wbGV4VGVuc29ySW5mb3MucmVhbC5kYXRhSWQpLFxuICAgICAgICB0aGlzLnJlYWQoY29tcGxleFRlbnNvckluZm9zLmltYWcuZGF0YUlkKVxuICAgICAgXSk7XG5cbiAgICAgIGNvbnN0IHJlYWxWYWx1ZXMgPSBwc1swXTtcbiAgICAgIGNvbnN0IGltYWdWYWx1ZXMgPSBwc1sxXTtcbiAgICAgIHZhbHMgPSBiYWNrZW5kX3V0aWwubWVyZ2VSZWFsQW5kSW1hZ0FycmF5cyhcbiAgICAgICAgICByZWFsVmFsdWVzIGFzIEZsb2F0MzJBcnJheSwgaW1hZ1ZhbHVlcyBhcyBGbG9hdDMyQXJyYXkpO1xuICAgIH0gZWxzZSBpZiAoYnVmZmVyID09IG51bGwpIHtcbiAgICAgIHZhbHMgPSB0aGlzLmdldFZhbHVlc0Zyb21UZXh0dXJlKGRhdGFJZCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIGNvbnN0IHNpemUgPSB1dGlsLnNpemVGcm9tU2hhcGUoc2hhcGUpO1xuICAgICAgdmFscyA9IHRoaXMuZ3BncHUuZG93bmxvYWRGbG9hdDMyTWF0cml4RnJvbUJ1ZmZlcihidWZmZXIsIHNpemUpO1xuICAgIH1cbiAgICBpZiAodG1wRG93bmxvYWRUYXJnZXQgIT0gbnVsbCkge1xuICAgICAgdGhpcy5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ySW5mbyh0bXBEb3dubG9hZFRhcmdldCk7XG4gICAgfVxuICAgIGlmIChidWZmZXIgIT0gbnVsbCkge1xuICAgICAgY29uc3QgZ2wgPSB0aGlzLmdwZ3B1LmdsO1xuICAgICAgd2ViZ2xfdXRpbC5jYWxsQW5kQ2hlY2soZ2wsICgpID0+IGdsLmRlbGV0ZUJ1ZmZlcihidWZmZXIpKTtcbiAgICB9XG4gICAgY29uc3QgZFR5cGVWYWxzID0gdGhpcy5jb252ZXJ0QW5kQ2FjaGVPbkNQVShkYXRhSWQsIHZhbHMpO1xuXG4gICAgY29uc3Qgc3Vic2NyaWJlcnMgPSB0aGlzLnBlbmRpbmdSZWFkLmdldChkYXRhSWQpO1xuICAgIHRoaXMucGVuZGluZ1JlYWQuZGVsZXRlKGRhdGFJZCk7XG5cbiAgICAvLyBOb3RpZnkgYWxsIHBlbmRpbmcgcmVhZHMuXG4gICAgc3Vic2NyaWJlcnMuZm9yRWFjaChyZXNvbHZlID0+IHJlc29sdmUoZFR5cGVWYWxzKSk7XG4gICAgaWYgKHRoaXMucGVuZGluZ0Rpc3Bvc2FsLmhhcyhkYXRhSWQpKSB7XG4gICAgICB0aGlzLnBlbmRpbmdEaXNwb3NhbC5kZWxldGUoZGF0YUlkKTtcbiAgICAgIGlmICh0aGlzLmRpc3Bvc2VEYXRhKGRhdGFJZCkpIHtcbiAgICAgICAgZW5naW5lKCkucmVtb3ZlRGF0YUlkKGRhdGFJZCwgdGhpcyk7XG4gICAgICB9XG4gICAgICB0aGlzLnBlbmRpbmdEZWxldGVzLS07XG4gICAgfVxuICAgIHJldHVybiBkVHlwZVZhbHM7XG4gIH1cblxuICAvKipcbiAgICogUmVhZCB0ZW5zb3IgdG8gYSBuZXcgdGV4dHVyZSB0aGF0IGlzIGRlbnNlbHkgcGFja2VkIGZvciBlYXNlIG9mIHVzZS5cbiAgICogQHBhcmFtIGRhdGFJZCBUaGUgc291cmNlIHRlbnNvci5cbiAgICogQHBhcmFtIG9wdGlvbnNcbiAgICogICAgIGN1c3RvbVRleFNoYXBlOiBPcHRpb25hbC4gSWYgc2V0LCB3aWxsIHVzZSB0aGUgdXNlciBkZWZpbmVkIHRleHR1cmVcbiAgICogICAgIHNoYXBlIHRvIGNyZWF0ZSB0aGUgdGV4dHVyZS5cbiAgICovXG4gIG92ZXJyaWRlIHJlYWRUb0dQVShkYXRhSWQ6IERhdGFJZCwgb3B0aW9uczogRGF0YVRvR1BVV2ViR0xPcHRpb24gPSB7fSk6XG4gICAgICBHUFVEYXRhIHtcbiAgICBjb25zdCB0ZXhEYXRhID0gdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpO1xuICAgIGNvbnN0IHt2YWx1ZXMsIHNoYXBlLCBzbGljZSwgZHR5cGUsIGlzUGFja2VkLCB0ZXh0dXJlfSA9IHRleERhdGE7XG5cbiAgICBpZiAoZHR5cGUgPT09ICdjb21wbGV4NjQnKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoJ0RvZXMgbm90IHN1cHBvcnQgcmVhZGluZyB0ZXh0dXJlIGZvciBjb21wbGV4NjQgZHR5cGUuJyk7XG4gICAgfVxuXG4gICAgLy8gVGhlIHByZXNlbmNlIG9mIGBzbGljZWAgaW5kaWNhdGVzIHRoaXMgdGVuc29yIGlzIGEgc2hhbGxvdyBzbGljZSBvZiBhXG4gICAgLy8gZGlmZmVyZW50IHRlbnNvciwgYW5kIGlzIHVzaW5nIHRoYXQgb3JpZ2luYWwgdGVuc29yJ3MgdGV4dHVyZS4gUnVuXG4gICAgLy8gYGNsb25lYCBpbiBvcmRlciB0byBjb3B5IHRoYXQgdGV4dHVyZSBhbmQgcmVhZCBmcm9tIGl0LlxuICAgIGlmIChzbGljZSAhPSBudWxsKSB7XG4gICAgICBsZXQgcHJvZ3JhbTtcbiAgICAgIGlmIChpc1BhY2tlZCkge1xuICAgICAgICBwcm9ncmFtID0gbmV3IFVuYXJ5T3BQYWNrZWRQcm9ncmFtKHNoYXBlLCB1bmFyeV9vcC5DTE9ORSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBwcm9ncmFtID0gbmV3IFVuYXJ5T3BQcm9ncmFtKHNoYXBlLCB1bmFyeV9vcC5DTE9ORSk7XG4gICAgICB9XG4gICAgICBjb25zdCByZXMgPVxuICAgICAgICAgIHRoaXMucnVuV2ViR0xQcm9ncmFtKHByb2dyYW0sIFt7ZGF0YUlkLCBzaGFwZSwgZHR5cGV9XSwgZHR5cGUpO1xuICAgICAgY29uc3QgZ3B1UmVzb3VvcmNlID0gdGhpcy5yZWFkVG9HUFUocmVzLCBvcHRpb25zKTtcbiAgICAgIHRoaXMuZGlzcG9zZUludGVybWVkaWF0ZVRlbnNvckluZm8ocmVzKTtcbiAgICAgIHJldHVybiBncHVSZXNvdW9yY2U7XG4gICAgfVxuXG4gICAgaWYgKHRleHR1cmUgPT0gbnVsbCkge1xuICAgICAgaWYgKHZhbHVlcyAhPSBudWxsKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcignRGF0YSBpcyBub3Qgb24gR1BVIGJ1dCBvbiBDUFUuJyk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ1RoZXJlIGlzIG5vIGRhdGEgb24gR1BVIG9yIENQVS4nKTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICAvLyBEZWNvZGUgdGhlIHRleHR1cmUgc28gdGhhdCBpdCBpcyBzdG9yZWQgZGVuc2VseSAodXNpbmcgZm91ciBjaGFubmVscykuXG4gICAgY29uc3QgdG1wVGFyZ2V0ID0gdGhpcy5kZWNvZGUoZGF0YUlkLCBvcHRpb25zLmN1c3RvbVRleFNoYXBlKTtcblxuICAgIC8vIE1ha2UgZW5naW5lIHRyYWNrIHRoaXMgdGVuc29yLCBzbyB0aGF0IHdlIGNhbiBkaXNwb3NlIGl0IGxhdGVyLlxuICAgIGNvbnN0IHRlbnNvclJlZiA9IGVuZ2luZSgpLm1ha2VUZW5zb3JGcm9tVGVuc29ySW5mbyh0bXBUYXJnZXQpO1xuXG4gICAgY29uc3QgdG1wRGF0YSA9IHRoaXMudGV4RGF0YS5nZXQodG1wVGFyZ2V0LmRhdGFJZCk7XG4gICAgcmV0dXJuIHt0ZW5zb3JSZWYsIC4uLnRtcERhdGEudGV4dHVyZX07XG4gIH1cblxuICBidWZmZXJTeW5jPFIgZXh0ZW5kcyBSYW5rLCBEIGV4dGVuZHMgRGF0YVR5cGU+KHQ6IFRlbnNvckluZm8pOlxuICAgICAgVGVuc29yQnVmZmVyPFIsIEQ+IHtcbiAgICBjb25zdCBkYXRhID0gdGhpcy5yZWFkU3luYyh0LmRhdGFJZCk7XG4gICAgaWYgKHQuZHR5cGUgPT09ICdzdHJpbmcnKSB7XG4gICAgICB0cnkge1xuICAgICAgICAvLyBEZWNvZGUgdGhlIGJ5dGVzIGludG8gc3RyaW5nLlxuICAgICAgICBjb25zdCBzdHJpbmdzID0gKGRhdGEgYXMgVWludDhBcnJheVtdKS5tYXAoZCA9PiB1dGlsLmRlY29kZVN0cmluZyhkKSk7XG4gICAgICAgIHJldHVybiBidWZmZXIodC5zaGFwZSBhcyBTaGFwZU1hcFtSXSwgdC5kdHlwZSwgc3RyaW5ncykgYXNcbiAgICAgICAgICAgIFRlbnNvckJ1ZmZlcjxSLCBEPjtcbiAgICAgIH0gY2F0Y2gge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0ZhaWxlZCB0byBkZWNvZGUgZW5jb2RlZCBzdHJpbmcgYnl0ZXMgaW50byB1dGYtOCcpO1xuICAgICAgfVxuICAgIH1cbiAgICByZXR1cm4gYnVmZmVyKHQuc2hhcGUgYXMgU2hhcGVNYXBbUl0sIHQuZHR5cGUsIGRhdGEgYXMgVHlwZWRBcnJheSkgYXNcbiAgICAgICAgVGVuc29yQnVmZmVyPFIsIEQ+O1xuICB9XG5cbiAgcHJpdmF0ZSBjaGVja051bWVyaWNhbFByb2JsZW1zKHZhbHVlczogQmFja2VuZFZhbHVlcyk6IHZvaWQge1xuICAgIGlmICh2YWx1ZXMgPT0gbnVsbCkge1xuICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IHZhbHVlcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29uc3QgbnVtID0gdmFsdWVzW2ldIGFzIG51bWJlcjtcbiAgICAgIGlmICghd2ViZ2xfdXRpbC5jYW5CZVJlcHJlc2VudGVkKG51bSkpIHtcbiAgICAgICAgaWYgKGVudigpLmdldEJvb2woJ1dFQkdMX1JFTkRFUl9GTE9BVDMyX0NBUEFCTEUnKSkge1xuICAgICAgICAgIHRocm93IEVycm9yKFxuICAgICAgICAgICAgICBgVGhlIHZhbHVlICR7bnVtfSBjYW5ub3QgYmUgcmVwcmVzZW50ZWQgd2l0aCB5b3VyIGAgK1xuICAgICAgICAgICAgICBgY3VycmVudCBzZXR0aW5ncy4gQ29uc2lkZXIgZW5hYmxpbmcgZmxvYXQzMiByZW5kZXJpbmc6IGAgK1xuICAgICAgICAgICAgICBgJ3RmLmVudigpLnNldCgnV0VCR0xfUkVOREVSX0ZMT0FUMzJfRU5BQkxFRCcsIHRydWUpOydgKTtcbiAgICAgICAgfVxuICAgICAgICB0aHJvdyBFcnJvcihgVGhlIHZhbHVlICR7bnVtfSBjYW5ub3QgYmUgcmVwcmVzZW50ZWQgb24gdGhpcyBkZXZpY2UuYCk7XG4gICAgICB9XG4gICAgfVxuICB9XG5cbiAgcHJpdmF0ZSBnZXRWYWx1ZXNGcm9tVGV4dHVyZShkYXRhSWQ6IERhdGFJZCk6IEZsb2F0MzJBcnJheSB7XG4gICAgY29uc3Qge3NoYXBlLCBkdHlwZSwgaXNQYWNrZWR9ID0gdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpO1xuICAgIGNvbnN0IHNpemUgPSB1dGlsLnNpemVGcm9tU2hhcGUoc2hhcGUpO1xuICAgIGlmIChlbnYoKS5nZXRCb29sKCdXRUJHTF9ET1dOTE9BRF9GTE9BVF9FTkFCTEVEJykpIHtcbiAgICAgIGNvbnN0IHRtcFRhcmdldCA9IHRoaXMuZGVjb2RlKGRhdGFJZCk7XG4gICAgICBjb25zdCB0bXBEYXRhID0gdGhpcy50ZXhEYXRhLmdldCh0bXBUYXJnZXQuZGF0YUlkKTtcbiAgICAgIGNvbnN0IHZhbHMgPVxuICAgICAgICAgIHRoaXMuZ3BncHVcbiAgICAgICAgICAgICAgLmRvd25sb2FkTWF0cml4RnJvbVBhY2tlZFRleHR1cmUoXG4gICAgICAgICAgICAgICAgICB0bXBEYXRhLnRleHR1cmUudGV4dHVyZSwgLi4udGV4X3V0aWwuZ2V0RGVuc2VUZXhTaGFwZShzaGFwZSkpXG4gICAgICAgICAgICAgIC5zdWJhcnJheSgwLCBzaXplKTtcblxuICAgICAgdGhpcy5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ySW5mbyh0bXBUYXJnZXQpO1xuXG4gICAgICByZXR1cm4gdmFscztcbiAgICB9XG5cbiAgICBjb25zdCBzaG91bGRVc2VQYWNrZWRQcm9ncmFtID1cbiAgICAgICAgZW52KCkuZ2V0Qm9vbCgnV0VCR0xfUEFDSycpICYmIGlzUGFja2VkID09PSB0cnVlO1xuICAgIGNvbnN0IG91dHB1dFNoYXBlID1cbiAgICAgICAgc2hvdWxkVXNlUGFja2VkUHJvZ3JhbSA/IHdlYmdsX3V0aWwuZ2V0U2hhcGVBczNEKHNoYXBlKSA6IHNoYXBlO1xuICAgIGNvbnN0IHByb2dyYW0gPSBzaG91bGRVc2VQYWNrZWRQcm9ncmFtID9cbiAgICAgICAgbmV3IEVuY29kZUZsb2F0UGFja2VkUHJvZ3JhbShvdXRwdXRTaGFwZSBhcyBbbnVtYmVyLCBudW1iZXIsIG51bWJlcl0pIDpcbiAgICAgICAgbmV3IEVuY29kZUZsb2F0UHJvZ3JhbShvdXRwdXRTaGFwZSk7XG4gICAgY29uc3Qgb3V0cHV0ID0gdGhpcy5ydW5XZWJHTFByb2dyYW0oXG4gICAgICAgIHByb2dyYW0sIFt7c2hhcGU6IG91dHB1dFNoYXBlLCBkdHlwZSwgZGF0YUlkfV0sICdmbG9hdDMyJyk7XG4gICAgY29uc3QgdG1wRGF0YSA9IHRoaXMudGV4RGF0YS5nZXQob3V0cHV0LmRhdGFJZCk7XG4gICAgY29uc3QgdmFscyA9IHRoaXMuZ3BncHVcbiAgICAgICAgICAgICAgICAgICAgIC5kb3dubG9hZEJ5dGVFbmNvZGVkRmxvYXRNYXRyaXhGcm9tT3V0cHV0VGV4dHVyZShcbiAgICAgICAgICAgICAgICAgICAgICAgICB0bXBEYXRhLnRleHR1cmUudGV4dHVyZSwgdG1wRGF0YS50ZXhTaGFwZVswXSxcbiAgICAgICAgICAgICAgICAgICAgICAgICB0bXBEYXRhLnRleFNoYXBlWzFdKVxuICAgICAgICAgICAgICAgICAgICAgLnN1YmFycmF5KDAsIHNpemUpO1xuICAgIHRoaXMuZGlzcG9zZUludGVybWVkaWF0ZVRlbnNvckluZm8ob3V0cHV0KTtcblxuICAgIHJldHVybiB2YWxzO1xuICB9XG5cbiAgb3ZlcnJpZGUgdGltZXJBdmFpbGFibGUoKTogYm9vbGVhbiB7XG4gICAgcmV0dXJuIGVudigpLmdldE51bWJlcignV0VCR0xfRElTSk9JTlRfUVVFUllfVElNRVJfRVhURU5TSU9OX1JFTElBQkxFJykgPiAwO1xuICB9XG5cbiAgb3ZlcnJpZGUgdGltZShmOiAoKSA9PiB2b2lkKTogUHJvbWlzZTxXZWJHTFRpbWluZ0luZm8+IHtcbiAgICBjb25zdCBvbGRBY3RpdmVUaW1lcnMgPSB0aGlzLmFjdGl2ZVRpbWVycztcbiAgICBjb25zdCBuZXdBY3RpdmVUaW1lcnM6IFRpbWVyTm9kZVtdID0gW107XG5cbiAgICBsZXQgb3V0ZXJNb3N0VGltZSA9IGZhbHNlO1xuICAgIGlmICh0aGlzLnByb2dyYW1UaW1lcnNTdGFjayA9PSBudWxsKSB7XG4gICAgICB0aGlzLnByb2dyYW1UaW1lcnNTdGFjayA9IG5ld0FjdGl2ZVRpbWVycztcbiAgICAgIG91dGVyTW9zdFRpbWUgPSB0cnVlO1xuICAgIH0gZWxzZSB7XG4gICAgICB0aGlzLmFjdGl2ZVRpbWVycy5wdXNoKG5ld0FjdGl2ZVRpbWVycyk7XG4gICAgfVxuICAgIHRoaXMuYWN0aXZlVGltZXJzID0gbmV3QWN0aXZlVGltZXJzO1xuXG4gICAgZigpO1xuXG4gICAgLy8gbmVlZGluZyB0byBzcGxpdCB0aGVzZSB1cCBiZWNhdXNlIHV0aWwuZmxhdHRlbiBvbmx5IGFjY2VwdHMgY2VydGFpbiB0eXBlc1xuICAgIGNvbnN0IGZsYXR0ZW5lZEFjdGl2ZVRpbWVyUXVlcmllcyA9XG4gICAgICAgIHV0aWwuZmxhdHRlbih0aGlzLmFjdGl2ZVRpbWVycy5tYXAoKGQ6IEtlcm5lbEluZm8pID0+IGQucXVlcnkpKVxuICAgICAgICAgICAgLmZpbHRlcihkID0+IGQgIT0gbnVsbCk7XG4gICAgY29uc3QgZmxhdHRlbmVkQWN0aXZlVGltZXJOYW1lcyA9XG4gICAgICAgIHV0aWwuZmxhdHRlbih0aGlzLmFjdGl2ZVRpbWVycy5tYXAoKGQ6IEtlcm5lbEluZm8pID0+IGQubmFtZSkpXG4gICAgICAgICAgICAuZmlsdGVyKGQgPT4gZCAhPSBudWxsKTtcblxuICAgIHRoaXMuYWN0aXZlVGltZXJzID0gb2xkQWN0aXZlVGltZXJzO1xuXG4gICAgaWYgKG91dGVyTW9zdFRpbWUpIHtcbiAgICAgIHRoaXMucHJvZ3JhbVRpbWVyc1N0YWNrID0gbnVsbDtcbiAgICB9XG5cbiAgICBjb25zdCByZXM6IFdlYkdMVGltaW5nSW5mbyA9IHtcbiAgICAgIHVwbG9hZFdhaXRNczogdGhpcy51cGxvYWRXYWl0TXMsXG4gICAgICBkb3dubG9hZFdhaXRNczogdGhpcy5kb3dubG9hZFdhaXRNcyxcbiAgICAgIGtlcm5lbE1zOiBudWxsLFxuICAgICAgd2FsbE1zOiBudWxsICAvLyB3aWxsIGJlIGZpbGxlZCBieSB0aGUgZW5naW5lXG4gICAgfTtcblxuICAgIHJldHVybiAoYXN5bmMgKCkgPT4ge1xuICAgICAgaWYgKGVudigpLmdldE51bWJlcignV0VCR0xfRElTSk9JTlRfUVVFUllfVElNRVJfRVhURU5TSU9OX1JFTElBQkxFJykgPlxuICAgICAgICAgIDApIHtcbiAgICAgICAgY29uc3Qga2VybmVsTXMgPSBhd2FpdCBQcm9taXNlLmFsbChmbGF0dGVuZWRBY3RpdmVUaW1lclF1ZXJpZXMpO1xuXG4gICAgICAgIHJlc1sna2VybmVsTXMnXSA9IHV0aWwuc3VtKGtlcm5lbE1zKTtcbiAgICAgICAgcmVzWydnZXRFeHRyYVByb2ZpbGVJbmZvJ10gPSAoKSA9PlxuICAgICAgICAgICAga2VybmVsTXNcbiAgICAgICAgICAgICAgICAubWFwKChkLCBpKSA9PiAoe25hbWU6IGZsYXR0ZW5lZEFjdGl2ZVRpbWVyTmFtZXNbaV0sIG1zOiBkfSkpXG4gICAgICAgICAgICAgICAgLm1hcChkID0+IGAke2QubmFtZX06ICR7ZC5tc31gKVxuICAgICAgICAgICAgICAgIC5qb2luKCcsICcpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgcmVzWydrZXJuZWxNcyddID0ge1xuICAgICAgICAgIGVycm9yOiAnV2ViR0wgcXVlcnkgdGltZXJzIGFyZSBub3Qgc3VwcG9ydGVkIGluIHRoaXMgZW52aXJvbm1lbnQuJ1xuICAgICAgICB9O1xuICAgICAgfVxuXG4gICAgICB0aGlzLnVwbG9hZFdhaXRNcyA9IDA7XG4gICAgICB0aGlzLmRvd25sb2FkV2FpdE1zID0gMDtcbiAgICAgIHJldHVybiByZXM7XG4gICAgfSkoKTtcbiAgfVxuICBvdmVycmlkZSBtZW1vcnkoKTogV2ViR0xNZW1vcnlJbmZvIHtcbiAgICByZXR1cm4ge1xuICAgICAgdW5yZWxpYWJsZTogZmFsc2UsXG4gICAgICBudW1CeXRlc0luR1BVOiB0aGlzLm51bUJ5dGVzSW5HUFUsXG4gICAgICBudW1CeXRlc0luR1BVQWxsb2NhdGVkOiB0aGlzLnRleHR1cmVNYW5hZ2VyLm51bUJ5dGVzQWxsb2NhdGVkLFxuICAgICAgbnVtQnl0ZXNJbkdQVUZyZWU6IHRoaXMudGV4dHVyZU1hbmFnZXIubnVtQnl0ZXNGcmVlXG4gICAgfSBhcyBXZWJHTE1lbW9yeUluZm87XG4gIH1cblxuICBwcml2YXRlIHN0YXJ0VGltZXIoKTogV2ViR0xRdWVyeXxDUFVUaW1lclF1ZXJ5IHtcbiAgICBpZiAoZW52KCkuZ2V0TnVtYmVyKCdXRUJHTF9ESVNKT0lOVF9RVUVSWV9USU1FUl9FWFRFTlNJT05fUkVMSUFCTEUnKSA+IDApIHtcbiAgICAgIHJldHVybiB0aGlzLmdwZ3B1LmJlZ2luUXVlcnkoKTtcbiAgICB9XG4gICAgcmV0dXJuIHtzdGFydE1zOiB1dGlsLm5vdygpLCBlbmRNczogbnVsbH07XG4gIH1cblxuICBwcml2YXRlIGVuZFRpbWVyKHF1ZXJ5OiBXZWJHTFF1ZXJ5fENQVVRpbWVyUXVlcnkpOiBXZWJHTFF1ZXJ5fENQVVRpbWVyUXVlcnkge1xuICAgIGlmIChlbnYoKS5nZXROdW1iZXIoJ1dFQkdMX0RJU0pPSU5UX1FVRVJZX1RJTUVSX0VYVEVOU0lPTl9SRUxJQUJMRScpID4gMCkge1xuICAgICAgdGhpcy5ncGdwdS5lbmRRdWVyeSgpO1xuICAgICAgcmV0dXJuIHF1ZXJ5O1xuICAgIH1cbiAgICAocXVlcnkgYXMgQ1BVVGltZXJRdWVyeSkuZW5kTXMgPSB1dGlsLm5vdygpO1xuICAgIHJldHVybiBxdWVyeTtcbiAgfVxuXG4gIHByaXZhdGUgYXN5bmMgZ2V0UXVlcnlUaW1lKHF1ZXJ5OiBXZWJHTFF1ZXJ5fENQVVRpbWVyUXVlcnkpOiBQcm9taXNlPG51bWJlcj4ge1xuICAgIGlmIChlbnYoKS5nZXROdW1iZXIoJ1dFQkdMX0RJU0pPSU5UX1FVRVJZX1RJTUVSX0VYVEVOU0lPTl9SRUxJQUJMRScpID4gMCkge1xuICAgICAgcmV0dXJuIHRoaXMuZ3BncHUud2FpdEZvclF1ZXJ5QW5kR2V0VGltZShxdWVyeSBhcyBXZWJHTFF1ZXJ5KTtcbiAgICB9XG4gICAgY29uc3QgdGltZXJRdWVyeSA9IHF1ZXJ5IGFzIENQVVRpbWVyUXVlcnk7XG4gICAgcmV0dXJuIHRpbWVyUXVlcnkuZW5kTXMgLSB0aW1lclF1ZXJ5LnN0YXJ0TXM7XG4gIH1cblxuICBwcml2YXRlIHBlbmRpbmdEZWxldGVzID0gMDtcblxuICAvKipcbiAgICogRGVjcmVhc2UgdGhlIFJlZkNvdW50IG9uIHRoZSBkYXRhSWQgYW5kIGRpc3Bvc2UgdGhlIG1lbW9yeSBpZiB0aGUgZGF0YUlkXG4gICAqIGhhcyAwIHJlZkNvdW50LiBJZiB0aGVyZSBhcmUgcGVuZGluZyByZWFkIG9uIHRoZSBkYXRhLCB0aGUgZGlzcG9zYWwgd291bGRcbiAgICogYWRkZWQgdG8gdGhlIHBlbmRpbmcgZGVsZXRlIHF1ZXVlLiBSZXR1cm4gdHJ1ZSBpZiB0aGUgZGF0YUlkIGlzIHJlbW92ZWRcbiAgICogZnJvbSBiYWNrZW5kIG9yIHRoZSBiYWNrZW5kIGRvZXMgbm90IGNvbnRhaW4gdGhlIGRhdGFJZCwgZmFsc2UgaWYgdGhlXG4gICAqIGRhdGFJZCBpcyBub3QgcmVtb3ZlZC4gTWVtb3J5IG1heSBvciBtYXkgbm90IGJlIHJlbGVhc2VkIGV2ZW4gd2hlbiBkYXRhSWRcbiAgICogaXMgcmVtb3ZlZCwgd2hpY2ggYWxzbyBkZXBlbmRzIG9uIGRhdGFSZWZDb3VudCwgc2VlIGByZWxlYXNlR1BVYC5cbiAgICogQHBhcmFtIGRhdGFJZFxuICAgKiBAb2FyYW0gZm9yY2UgT3B0aW9uYWwsIHJlbW92ZSB0aGUgZGF0YSByZWdhcmRsZXNzIG9mIHJlZkNvdW50XG4gICAqL1xuICBvdmVycmlkZSBkaXNwb3NlRGF0YShkYXRhSWQ6IERhdGFJZCwgZm9yY2UgPSBmYWxzZSk6IGJvb2xlYW4ge1xuICAgIGlmICh0aGlzLnBlbmRpbmdEaXNwb3NhbC5oYXMoZGF0YUlkKSkge1xuICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH1cblxuICAgIC8vIE5vLW9wIGlmIGFscmVhZHkgZGlzcG9zZWQuXG4gICAgaWYgKCF0aGlzLnRleERhdGEuaGFzKGRhdGFJZCkpIHtcbiAgICAgIHJldHVybiB0cnVlO1xuICAgIH1cblxuICAgIC8vIGlmIGZvcmNlIGZsYWcgaXMgc2V0LCBjaGFuZ2UgcmVmQ291bnQgdG8gMCwgdGhpcyB3b3VsZCBlbnN1cmUgZGlzcG9zYWxcbiAgICAvLyB3aGVuIGFkZGVkIHRvIHRoZSBwZW5kaW5nRGlzcG9zYWwgcXVldWUuIE1lbW9yeSBtYXkgb3IgbWF5IG5vdCBiZVxuICAgIC8vIHJlbGVhc2VkLCB3aGljaCBhbHNvIGRlcGVuZHMgb24gZGF0YVJlZkNvdW50LCBzZWUgYHJlbGVhc2VHUFVgLlxuICAgIGlmIChmb3JjZSkge1xuICAgICAgdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpLnJlZkNvdW50ID0gMDtcbiAgICB9IGVsc2Uge1xuICAgICAgdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpLnJlZkNvdW50LS07XG4gICAgfVxuXG4gICAgaWYgKCFmb3JjZSAmJiB0aGlzLnRleERhdGEuZ2V0KGRhdGFJZCkucmVmQ291bnQgPiAwKSB7XG4gICAgICByZXR1cm4gZmFsc2U7XG4gICAgfVxuXG4gICAgaWYgKHRoaXMucGVuZGluZ1JlYWQuaGFzKGRhdGFJZCkpIHtcbiAgICAgIHRoaXMucGVuZGluZ0Rpc3Bvc2FsLmFkZChkYXRhSWQpO1xuICAgICAgdGhpcy5wZW5kaW5nRGVsZXRlcysrO1xuICAgICAgcmV0dXJuIGZhbHNlO1xuICAgIH1cblxuICAgIHRoaXMucmVsZWFzZUdQVURhdGEoZGF0YUlkKTtcbiAgICBjb25zdCB7Y29tcGxleFRlbnNvckluZm9zfSA9IHRoaXMudGV4RGF0YS5nZXQoZGF0YUlkKTtcbiAgICBpZiAoY29tcGxleFRlbnNvckluZm9zICE9IG51bGwpIHtcbiAgICAgIHRoaXMuZGlzcG9zZURhdGEoY29tcGxleFRlbnNvckluZm9zLnJlYWwuZGF0YUlkLCBmb3JjZSk7XG4gICAgICB0aGlzLmRpc3Bvc2VEYXRhKGNvbXBsZXhUZW5zb3JJbmZvcy5pbWFnLmRhdGFJZCwgZm9yY2UpO1xuICAgIH1cblxuICAgIHRoaXMudGV4RGF0YS5kZWxldGUoZGF0YUlkKTtcblxuICAgIHJldHVybiB0cnVlO1xuICB9XG5cbiAgcHJpdmF0ZSByZWxlYXNlR1BVRGF0YShkYXRhSWQ6IERhdGFJZCk6IHZvaWQge1xuICAgIGNvbnN0IHt0ZXh0dXJlLCBkdHlwZSwgdGV4U2hhcGUsIHVzYWdlLCBpc1BhY2tlZCwgc2xpY2V9ID1cbiAgICAgICAgdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpO1xuICAgIGNvbnN0IGtleSA9IHNsaWNlICYmIHNsaWNlLm9yaWdEYXRhSWQgfHwgZGF0YUlkO1xuICAgIGNvbnN0IHJlZkNvdW50ID0gdGhpcy5kYXRhUmVmQ291bnQuZ2V0KGtleSk7XG5cbiAgICBpZiAocmVmQ291bnQgPiAxKSB7XG4gICAgICB0aGlzLmRhdGFSZWZDb3VudC5zZXQoa2V5LCByZWZDb3VudCAtIDEpO1xuICAgIH0gZWxzZSB7XG4gICAgICB0aGlzLmRhdGFSZWZDb3VudC5kZWxldGUoa2V5KTtcbiAgICAgIGlmICh0ZXh0dXJlICE9IG51bGwpIHtcbiAgICAgICAgdGhpcy5udW1CeXRlc0luR1BVIC09IHRoaXMuY29tcHV0ZUJ5dGVzKHRleFNoYXBlLCBkdHlwZSk7XG4gICAgICAgIHRoaXMudGV4dHVyZU1hbmFnZXIucmVsZWFzZVRleHR1cmUodGV4dHVyZSwgdGV4U2hhcGUsIHVzYWdlLCBpc1BhY2tlZCk7XG4gICAgICB9XG4gICAgfVxuXG4gICAgY29uc3QgdGV4RGF0YSA9IHRoaXMudGV4RGF0YS5nZXQoZGF0YUlkKTtcbiAgICB0ZXhEYXRhLnRleHR1cmUgPSBudWxsO1xuICAgIHRleERhdGEudGV4U2hhcGUgPSBudWxsO1xuICAgIHRleERhdGEuaXNQYWNrZWQgPSBmYWxzZTtcbiAgICB0ZXhEYXRhLnNsaWNlID0gbnVsbDtcbiAgfVxuXG4gIGdldFRleHR1cmUoZGF0YUlkOiBEYXRhSWQpOiBXZWJHTFRleHR1cmUge1xuICAgIHRoaXMudXBsb2FkVG9HUFUoZGF0YUlkKTtcbiAgICByZXR1cm4gdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpLnRleHR1cmUudGV4dHVyZTtcbiAgfVxuXG4gIC8qKlxuICAgKiBSZXR1cm5zIGludGVybmFsIGluZm9ybWF0aW9uIGZvciB0aGUgc3BlY2lmaWMgZGF0YSBidWNrZXQuIFVzZWQgaW4gdW5pdFxuICAgKiB0ZXN0cy5cbiAgICovXG4gIGdldERhdGFJbmZvKGRhdGFJZDogRGF0YUlkKTogVGV4dHVyZURhdGEge1xuICAgIHJldHVybiB0aGlzLnRleERhdGEuZ2V0KGRhdGFJZCk7XG4gIH1cblxuICAvKlxuICBUZXN0cyB3aGV0aGVyIGFsbCB0aGUgaW5wdXRzIHRvIGFuIG9wIGFyZSBzbWFsbCBhbmQgb24gdGhlIENQVS4gVGhpcyBoZXVyaXN0aWNcbiAgZGV0ZXJtaW5lcyB3aGVuIGl0IHdvdWxkIGJlIGZhc3RlciB0byBleGVjdXRlIGEga2VybmVsIG9uIHRoZSBDUFUuIFdlYkdMXG4gIGtlcm5lbHMgb3B0IGludG8gcnVubmluZyB0aGlzIGNoZWNrIGFuZCBmb3J3YXJkaW5nIHdoZW4gYXBwcm9wcmlhdGUuXG4gIFRPRE8oaHR0cHM6Ly9naXRodWIuY29tL3RlbnNvcmZsb3cvdGZqcy9pc3N1ZXMvODcyKTogRGV2ZWxvcCBhIG1vcmVcbiAgc3VzdGFpbmFibGUgc3RyYXRlZ3kgZm9yIG9wdGltaXppbmcgYmFja2VuZCBleGVjdXRpb24gb2Ygb3BzLlxuICAgKi9cbiAgc2hvdWxkRXhlY3V0ZU9uQ1BVKFxuICAgICAgaW5wdXRzOiBUZW5zb3JJbmZvW10sXG4gICAgICBzaXplVGhyZXNob2xkID0gQ1BVX0hBTkRPRkZfU0laRV9USFJFU0hPTEQpOiBib29sZWFuIHtcbiAgICByZXR1cm4gZW52KCkuZ2V0Qm9vbCgnV0VCR0xfQ1BVX0ZPUldBUkQnKSAmJlxuICAgICAgICBpbnB1dHMuZXZlcnkoXG4gICAgICAgICAgICBpbnB1dCA9PiB0aGlzLnRleERhdGEuZ2V0KGlucHV0LmRhdGFJZCkudGV4dHVyZSA9PSBudWxsICYmXG4gICAgICAgICAgICAgICAgdXRpbC5zaXplRnJvbVNoYXBlKGlucHV0LnNoYXBlKSA8IHNpemVUaHJlc2hvbGQpO1xuICB9XG5cbiAgZ2V0R1BHUFVDb250ZXh0KCk6IEdQR1BVQ29udGV4dCB7XG4gICAgcmV0dXJuIHRoaXMuZ3BncHU7XG4gIH1cblxuICB3aGVyZShjb25kaXRpb246IFRlbnNvcik6IFRlbnNvcjJEIHtcbiAgICBiYWNrZW5kX3V0aWwud2FybihcbiAgICAgICAgJ3RmLndoZXJlKCkgaW4gd2ViZ2wgbG9ja3MgdGhlIFVJIHRocmVhZC4gJyArXG4gICAgICAgICdDYWxsIHRmLndoZXJlQXN5bmMoKSBpbnN0ZWFkJyk7XG4gICAgY29uc3QgY29uZFZhbHMgPSBjb25kaXRpb24uZGF0YVN5bmMoKTtcbiAgICByZXR1cm4gd2hlcmVJbXBsKGNvbmRpdGlvbi5zaGFwZSwgY29uZFZhbHMpO1xuICB9XG5cbiAgcHJpdmF0ZSBwYWNrZWRVbmFyeU9wKHg6IFRlbnNvckluZm8sIG9wOiBzdHJpbmcsIGR0eXBlOiBEYXRhVHlwZSkge1xuICAgIGNvbnN0IHByb2dyYW0gPSBuZXcgVW5hcnlPcFBhY2tlZFByb2dyYW0oeC5zaGFwZSwgb3ApO1xuICAgIGNvbnN0IG91dEluZm8gPSB0aGlzLmNvbXBpbGVBbmRSdW4ocHJvZ3JhbSwgW3hdLCBkdHlwZSk7XG4gICAgcmV0dXJuIGVuZ2luZSgpLm1ha2VUZW5zb3JGcm9tVGVuc29ySW5mbyhvdXRJbmZvKTtcbiAgfVxuXG4gIC8vIFRPRE8obXNvdWxhbmlsbGUpIHJlbW92ZSB0aGlzIG9uY2UgdGhlIGJhY2tlbmQgaGFzIGJlZW4gbW9kdWxhcml6ZWRcbiAgLy8gYSBjb3B5IGlzIG5lZWRlZCBoZXJlIHRvIGJyZWFrIGEgY2lyY3VsYXIgZGVwZW5kZW5jeS5cbiAgLy8gQWxzbyByZW1vdmUgdGhlIG9wIGZyb20gdW5hcnlfb3AuXG4gIGFiczxUIGV4dGVuZHMgVGVuc29yPih4OiBUKTogVCB7XG4gICAgLy8gVE9ETzogaGFuZGxlIGNhc2VzIHdoZW4geCBpcyBjb21wbGV4LlxuICAgIGlmICh0aGlzLnNob3VsZEV4ZWN1dGVPbkNQVShbeF0pICYmIHguZHR5cGUgIT09ICdjb21wbGV4NjQnKSB7XG4gICAgICBjb25zdCBvdXRWYWx1ZXMgPVxuICAgICAgICAgIHNpbXBsZUFic0ltcGxDUFUodGhpcy50ZXhEYXRhLmdldCh4LmRhdGFJZCkudmFsdWVzIGFzIFR5cGVkQXJyYXkpO1xuICAgICAgcmV0dXJuIHRoaXMubWFrZU91dHB1dCh4LnNoYXBlLCB4LmR0eXBlLCBvdXRWYWx1ZXMpO1xuICAgIH1cblxuICAgIGlmIChlbnYoKS5nZXRCb29sKCdXRUJHTF9QQUNLX1VOQVJZX09QRVJBVElPTlMnKSkge1xuICAgICAgcmV0dXJuIHRoaXMucGFja2VkVW5hcnlPcCh4LCB1bmFyeV9vcC5BQlMsIHguZHR5cGUpIGFzIFQ7XG4gICAgfVxuXG4gICAgY29uc3QgcHJvZ3JhbSA9IG5ldyBVbmFyeU9wUHJvZ3JhbSh4LnNoYXBlLCB1bmFyeV9vcC5BQlMpO1xuICAgIGNvbnN0IG91dEluZm8gPSB0aGlzLmNvbXBpbGVBbmRSdW4ocHJvZ3JhbSwgW3hdKTtcbiAgICByZXR1cm4gZW5naW5lKCkubWFrZVRlbnNvckZyb21UZW5zb3JJbmZvKG91dEluZm8pIGFzIFQ7XG4gIH1cblxuICBtYWtlVGVuc29ySW5mbyhcbiAgICAgIHNoYXBlOiBudW1iZXJbXSwgZHR5cGU6IERhdGFUeXBlLFxuICAgICAgdmFsdWVzPzogQmFja2VuZFZhbHVlc3xzdHJpbmdbXSk6IFRlbnNvckluZm8ge1xuICAgIGxldCBkYXRhSWQ7XG4gICAgaWYgKGR0eXBlID09PSAnc3RyaW5nJyAmJiB2YWx1ZXMgIT0gbnVsbCAmJiB2YWx1ZXMubGVuZ3RoID4gMCAmJlxuICAgICAgICB1dGlsLmlzU3RyaW5nKHZhbHVlc1swXSkpIHtcbiAgICAgIGNvbnN0IGVuY29kZWRWYWx1ZXMgPVxuICAgICAgICAgICh2YWx1ZXMgYXMgdW5rbm93biBhcyBzdHJpbmdbXSkubWFwKGQgPT4gdXRpbC5lbmNvZGVTdHJpbmcoZCkpO1xuXG4gICAgICBkYXRhSWQgPSB0aGlzLndyaXRlKGVuY29kZWRWYWx1ZXMsIHNoYXBlLCBkdHlwZSk7XG4gICAgfSBlbHNlIHtcbiAgICAgIGRhdGFJZCA9IHRoaXMud3JpdGUodmFsdWVzIGFzIFR5cGVkQXJyYXksIHNoYXBlLCBkdHlwZSk7XG4gICAgfVxuXG4gICAgdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpLnVzYWdlID0gbnVsbDtcbiAgICByZXR1cm4ge2RhdGFJZCwgc2hhcGUsIGR0eXBlfTtcbiAgfVxuXG4gIHByaXZhdGUgbWFrZU91dHB1dDxUIGV4dGVuZHMgVGVuc29yPihcbiAgICAgIHNoYXBlOiBudW1iZXJbXSwgZHR5cGU6IERhdGFUeXBlLCB2YWx1ZXM/OiBCYWNrZW5kVmFsdWVzKTogVCB7XG4gICAgcmV0dXJuIGVuZ2luZSgpLm1ha2VUZW5zb3JGcm9tVGVuc29ySW5mbyhcbiAgICAgICAgICAgICAgIHRoaXMubWFrZVRlbnNvckluZm8oc2hhcGUsIGR0eXBlLCB2YWx1ZXMpLCB0aGlzKSBhcyBUO1xuICB9XG5cbiAgdW5wYWNrVGVuc29yKGlucHV0OiBUZW5zb3JJbmZvKTogVGVuc29ySW5mbyB7XG4gICAgY29uc3QgcHJvZ3JhbSA9IG5ldyBVbnBhY2tQcm9ncmFtKGlucHV0LnNoYXBlKTtcbiAgICByZXR1cm4gdGhpcy5ydW5XZWJHTFByb2dyYW0ocHJvZ3JhbSwgW2lucHV0XSwgaW5wdXQuZHR5cGUpO1xuICB9XG5cbiAgcGFja1RlbnNvcihpbnB1dDogVGVuc29ySW5mbyk6IFRlbnNvckluZm8ge1xuICAgIGNvbnN0IHByb2dyYW0gPSBuZXcgUGFja1Byb2dyYW0oaW5wdXQuc2hhcGUpO1xuICAgIGNvbnN0IHByZXZlbnRFYWdlclVucGFja2luZ091dHB1dCA9IHRydWU7XG4gICAgcmV0dXJuIHRoaXMucnVuV2ViR0xQcm9ncmFtKFxuICAgICAgICBwcm9ncmFtLCBbaW5wdXRdLCBpbnB1dC5kdHlwZSwgbnVsbCAvKiBjdXN0b21Vbmlmb3JtVmFsdWVzICovLFxuICAgICAgICBwcmV2ZW50RWFnZXJVbnBhY2tpbmdPdXRwdXQpO1xuICB9XG5cbiAgcHJpdmF0ZSBwYWNrZWRSZXNoYXBlKGlucHV0OiBUZW5zb3JJbmZvLCBhZnRlclNoYXBlOiBudW1iZXJbXSk6IFRlbnNvckluZm8ge1xuICAgIGNvbnN0IGlucHV0M0RTaGFwZSA9IFtcbiAgICAgIHdlYmdsX3V0aWwuZ2V0QmF0Y2hEaW0oaW5wdXQuc2hhcGUpLFxuICAgICAgLi4ud2ViZ2xfdXRpbC5nZXRSb3dzQ29scyhpbnB1dC5zaGFwZSlcbiAgICBdIGFzIFtudW1iZXIsIG51bWJlciwgbnVtYmVyXTtcbiAgICBjb25zdCBpbnB1dDNEOiBUZW5zb3JJbmZvID0ge1xuICAgICAgZHR5cGU6IGlucHV0LmR0eXBlLFxuICAgICAgc2hhcGU6IGlucHV0M0RTaGFwZSxcbiAgICAgIGRhdGFJZDogaW5wdXQuZGF0YUlkXG4gICAgfTtcbiAgICBjb25zdCBhZnRlclNoYXBlQXMzRCA9IFtcbiAgICAgIHdlYmdsX3V0aWwuZ2V0QmF0Y2hEaW0oYWZ0ZXJTaGFwZSksIC4uLndlYmdsX3V0aWwuZ2V0Um93c0NvbHMoYWZ0ZXJTaGFwZSlcbiAgICBdIGFzIFtudW1iZXIsIG51bWJlciwgbnVtYmVyXTtcblxuICAgIGNvbnN0IHByb2dyYW0gPSBuZXcgUmVzaGFwZVBhY2tlZFByb2dyYW0oYWZ0ZXJTaGFwZUFzM0QsIGlucHV0M0RTaGFwZSk7XG4gICAgY29uc3QgcHJldmVudEVhZ2VyVW5wYWNraW5nT2ZPdXRwdXQgPSB0cnVlO1xuICAgIGNvbnN0IGN1c3RvbVZhbHVlcyA9IFtpbnB1dDNEU2hhcGVdO1xuICAgIGNvbnN0IG91dHB1dCA9IHRoaXMucnVuV2ViR0xQcm9ncmFtKFxuICAgICAgICBwcm9ncmFtLCBbaW5wdXQzRF0sIGlucHV0LmR0eXBlLCBjdXN0b21WYWx1ZXMsXG4gICAgICAgIHByZXZlbnRFYWdlclVucGFja2luZ09mT3V0cHV0KTtcbiAgICByZXR1cm4ge2RhdGFJZDogb3V0cHV0LmRhdGFJZCwgc2hhcGU6IGFmdGVyU2hhcGUsIGR0eXBlOiBvdXRwdXQuZHR5cGV9O1xuICB9XG5cbiAgcHJpdmF0ZSBkZWNvZGUoZGF0YUlkOiBEYXRhSWQsIGN1c3RvbVRleFNoYXBlPzogW251bWJlciwgbnVtYmVyXSk6XG4gICAgICBUZW5zb3JJbmZvIHtcbiAgICBjb25zdCB0ZXhEYXRhID0gdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpO1xuICAgIGNvbnN0IHtpc1BhY2tlZCwgc2hhcGUsIGR0eXBlfSA9IHRleERhdGE7XG4gICAgaWYgKGN1c3RvbVRleFNoYXBlICE9IG51bGwpIHtcbiAgICAgIGNvbnN0IHNpemUgPSB1dGlsLnNpemVGcm9tU2hhcGUoc2hhcGUpO1xuICAgICAgY29uc3QgdGV4U2l6ZSA9IGN1c3RvbVRleFNoYXBlWzBdICogY3VzdG9tVGV4U2hhcGVbMV0gKiA0O1xuICAgICAgdXRpbC5hc3NlcnQoXG4gICAgICAgICAgc2l6ZSA8PSB0ZXhTaXplLFxuICAgICAgICAgICgpID0+ICdjdXN0b21UZXhTaGFwZSBpcyB0b28gc21hbGwuICcgK1xuICAgICAgICAgICAgICAnUm93ICogQ29sdW1uICogNCBzaG91bGQgYmUgZXF1YWwgb3IgbGFyZ2VyIHRoYW4gdGhlICcgK1xuICAgICAgICAgICAgICAnc2l6ZSBvZiB0aGUgdGVuc29yIGRhdGEuJyk7XG4gICAgfVxuICAgIGNvbnN0IHNoYXBlQXMzRCA9XG4gICAgICAgIHdlYmdsX3V0aWwuZ2V0U2hhcGVBczNEKHNoYXBlKSBhcyBbbnVtYmVyLCBudW1iZXIsIG51bWJlcl07XG4gICAgbGV0IHByb2dyYW07XG4gICAgaWYgKGlzUGFja2VkKSB7XG4gICAgICBwcm9ncmFtID0gbmV3IERlY29kZU1hdHJpeFBhY2tlZFByb2dyYW0oc2hhcGVBczNEKTtcbiAgICB9IGVsc2Uge1xuICAgICAgcHJvZ3JhbSA9IG5ldyBEZWNvZGVNYXRyaXhQcm9ncmFtKHNoYXBlQXMzRCk7XG4gICAgfVxuICAgIGNvbnN0IHByZXZlbnRFYWdlclVucGFja2luZ09mT3V0cHV0ID0gdHJ1ZTtcbiAgICBjb25zdCBjdXN0b21WYWx1ZXMgPVxuICAgICAgICBbY3VzdG9tVGV4U2hhcGUgIT0gbnVsbCA/IGN1c3RvbVRleFNoYXBlIDpcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0ZXhfdXRpbC5nZXREZW5zZVRleFNoYXBlKHNoYXBlQXMzRCldO1xuICAgIGNvbnN0IG91dCA9IHRoaXMucnVuV2ViR0xQcm9ncmFtKFxuICAgICAgICBwcm9ncmFtLCBbe3NoYXBlOiBzaGFwZUFzM0QsIGR0eXBlLCBkYXRhSWR9XSwgZHR5cGUsIGN1c3RvbVZhbHVlcyxcbiAgICAgICAgcHJldmVudEVhZ2VyVW5wYWNraW5nT2ZPdXRwdXQsIGN1c3RvbVRleFNoYXBlKTtcbiAgICByZXR1cm4ge2R0eXBlLCBzaGFwZSwgZGF0YUlkOiBvdXQuZGF0YUlkfTtcbiAgfVxuXG4gIHJ1bldlYkdMUHJvZ3JhbShcbiAgICAgIHByb2dyYW06IEdQR1BVUHJvZ3JhbSwgaW5wdXRzOiBUZW5zb3JJbmZvW10sIG91dHB1dER0eXBlOiBEYXRhVHlwZSxcbiAgICAgIGN1c3RvbVVuaWZvcm1WYWx1ZXM/OiBudW1iZXJbXVtdLCBwcmV2ZW50RWFnZXJVbnBhY2tpbmdPZk91dHB1dCA9IGZhbHNlLFxuICAgICAgY3VzdG9tVGV4U2hhcGU/OiBbbnVtYmVyLCBudW1iZXJdKTogVGVuc29ySW5mbyB7XG4gICAgY29uc3Qgb3V0cHV0ID0gdGhpcy5tYWtlVGVuc29ySW5mbyhwcm9ncmFtLm91dHB1dFNoYXBlLCBvdXRwdXREdHlwZSk7XG4gICAgY29uc3Qgb3V0RGF0YSA9IHRoaXMudGV4RGF0YS5nZXQob3V0cHV0LmRhdGFJZCk7XG4gICAgaWYgKHByb2dyYW0ucGFja2VkT3V0cHV0KSB7XG4gICAgICBvdXREYXRhLmlzUGFja2VkID0gdHJ1ZTtcbiAgICB9XG4gICAgaWYgKHByb2dyYW0ub3V0UGFja2luZ1NjaGVtZSA9PT0gdGV4X3V0aWwuUGFja2luZ1NjaGVtZS5ERU5TRSkge1xuICAgICAgY29uc3QgdGV4ZWxTaGFwZSA9IGN1c3RvbVRleFNoYXBlICE9IG51bGwgP1xuICAgICAgICAgIGN1c3RvbVRleFNoYXBlIDpcbiAgICAgICAgICB0ZXhfdXRpbC5nZXREZW5zZVRleFNoYXBlKHByb2dyYW0ub3V0cHV0U2hhcGUpO1xuICAgICAgLy8gRm9yIGEgZGVuc2VseSBwYWNrZWQgb3V0cHV0LCB3ZSBleHBsaWNpdGx5IHNldCB0ZXhTaGFwZVxuICAgICAgLy8gc28gaXQgZG9lc24ndCBnZXQgYXNzaWduZWQgbGF0ZXIgYWNjb3JkaW5nIHRvIG91ciB0eXBpY2FsIHBhY2tpbmdcbiAgICAgIC8vIHNjaGVtZSB3aGVyZWluIGEgc2luZ2xlIHRleGVsIGNhbiBvbmx5IGNvbnRhaW4gdmFsdWVzIGZyb20gYWRqYWNlbnRcbiAgICAgIC8vIHJvd3MvY29scy5cbiAgICAgIG91dERhdGEudGV4U2hhcGUgPSB0ZXhlbFNoYXBlLm1hcChkID0+IGQgKiAyKSBhcyBbbnVtYmVyLCBudW1iZXJdO1xuICAgIH1cbiAgICBpZiAocHJvZ3JhbS5vdXRUZXhVc2FnZSAhPSBudWxsKSB7XG4gICAgICBvdXREYXRhLnVzYWdlID0gcHJvZ3JhbS5vdXRUZXhVc2FnZTtcbiAgICB9XG5cbiAgICBpZiAodXRpbC5zaXplRnJvbVNoYXBlKG91dHB1dC5zaGFwZSkgPT09IDApIHtcbiAgICAgIC8vIFNob3J0LWNpcmN1aXQgdGhlIGNvbXB1dGF0aW9uIHNpbmNlIHRoZSByZXN1bHQgaXMgZW1wdHkgKGhhcyAwIGluIGl0c1xuICAgICAgLy8gc2hhcGUpLlxuICAgICAgb3V0RGF0YS52YWx1ZXMgPVxuICAgICAgICAgIHV0aWwuZ2V0VHlwZWRBcnJheUZyb21EVHlwZShvdXRwdXQuZHR5cGUgYXMgJ2Zsb2F0MzInLCAwKTtcbiAgICAgIHJldHVybiBvdXRwdXQ7XG4gICAgfVxuXG4gICAgY29uc3QgZGF0YVRvRGlzcG9zZTogVGVuc29ySW5mb1tdID0gW107XG4gICAgY29uc3QgaW5wdXRzRGF0YTogVGVuc29yRGF0YVtdID0gaW5wdXRzLm1hcChpbnB1dCA9PiB7XG4gICAgICBpZiAoaW5wdXQuZHR5cGUgPT09ICdjb21wbGV4NjQnKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICAgIGBHUEdQVVByb2dyYW0gZG9lcyBub3Qgc3VwcG9ydCBjb21wbGV4NjQgaW5wdXQuIEZvciBjb21wbGV4NjQgYCArXG4gICAgICAgICAgICBgZHR5cGVzLCBwbGVhc2Ugc2VwYXJhdGUgdGhlIHByb2dyYW0gaW50byByZWFsIGFuZCBpbWFnaW5hcnkgYCArXG4gICAgICAgICAgICBgcGFydHMuYCk7XG4gICAgICB9XG5cbiAgICAgIGxldCB0ZXhEYXRhID0gdGhpcy50ZXhEYXRhLmdldChpbnB1dC5kYXRhSWQpO1xuXG4gICAgICBpZiAodGV4RGF0YS50ZXh0dXJlID09IG51bGwpIHtcbiAgICAgICAgaWYgKCFwcm9ncmFtLnBhY2tlZElucHV0cyAmJlxuICAgICAgICAgICAgdXRpbC5zaXplRnJvbVNoYXBlKGlucHV0LnNoYXBlKSA8PVxuICAgICAgICAgICAgICAgIGVudigpLmdldE51bWJlcignV0VCR0xfU0laRV9VUExPQURfVU5JRk9STScpKSB7XG4gICAgICAgICAgLy8gVXBsb2FkIHNtYWxsIHRlbnNvcnMgdGhhdCBsaXZlIG9uIHRoZSBDUFUgYXMgdW5pZm9ybXMsIG5vdCBhc1xuICAgICAgICAgIC8vIHRleHR1cmVzLiBEbyB0aGlzIG9ubHkgd2hlbiB0aGUgZW52aXJvbm1lbnQgc3VwcG9ydHMgMzJiaXQgZmxvYXRzXG4gICAgICAgICAgLy8gZHVlIHRvIHByb2JsZW1zIHdoZW4gY29tcGFyaW5nIDE2Yml0IGZsb2F0cyB3aXRoIDMyYml0IGZsb2F0cy5cbiAgICAgICAgICAvLyBUT0RPKGh0dHBzOi8vZ2l0aHViLmNvbS90ZW5zb3JmbG93L3RmanMvaXNzdWVzLzgyMSk6IE1ha2UgaXRcbiAgICAgICAgICAvLyBwb3NzaWJsZSBmb3IgcGFja2VkIHNoYWRlcnMgdG8gc2FtcGxlIGZyb20gdW5pZm9ybXMuXG4gICAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIHNoYXBlOiBpbnB1dC5zaGFwZSxcbiAgICAgICAgICAgIHRleERhdGE6IG51bGwsXG4gICAgICAgICAgICBpc1VuaWZvcm06IHRydWUsXG4gICAgICAgICAgICB1bmlmb3JtVmFsdWVzOiB0ZXhEYXRhLnZhbHVlcyBhcyBUeXBlZEFycmF5XG4gICAgICAgICAgfTtcbiAgICAgICAgfVxuXG4gICAgICAgIC8vIFRoaXMgZW5zdXJlcyB0aGF0IGlmIGEgcGFja2VkIHByb2dyYW0ncyBpbnB1dHMgaGF2ZSBub3QgeWV0IGJlZW5cbiAgICAgICAgLy8gdXBsb2FkZWQgdG8gdGhlIEdQVSwgdGhleSBnZXQgdXBsb2FkZWQgYXMgcGFja2VkIHJpZ2h0IG9mZiB0aGUgYmF0LlxuICAgICAgICBpZiAocHJvZ3JhbS5wYWNrZWRJbnB1dHMpIHtcbiAgICAgICAgICB0ZXhEYXRhLmlzUGFja2VkID0gdHJ1ZTtcbiAgICAgICAgICB0ZXhEYXRhLnNoYXBlID0gaW5wdXQuc2hhcGU7XG4gICAgICAgIH1cbiAgICAgIH1cblxuICAgICAgdGhpcy51cGxvYWRUb0dQVShpbnB1dC5kYXRhSWQpO1xuICAgICAgaWYgKCEhdGV4RGF0YS5pc1BhY2tlZCAhPT0gISFwcm9ncmFtLnBhY2tlZElucHV0cykge1xuICAgICAgICBpbnB1dCA9IHRleERhdGEuaXNQYWNrZWQgPyB0aGlzLnVucGFja1RlbnNvcihpbnB1dCkgOlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnBhY2tUZW5zb3IoaW5wdXQpO1xuICAgICAgICBkYXRhVG9EaXNwb3NlLnB1c2goaW5wdXQpO1xuICAgICAgICB0ZXhEYXRhID0gdGhpcy50ZXhEYXRhLmdldChpbnB1dC5kYXRhSWQpO1xuICAgICAgfSBlbHNlIGlmIChcbiAgICAgICAgICB0ZXhEYXRhLmlzUGFja2VkICYmXG4gICAgICAgICAgIXdlYmdsX3V0aWwuaXNSZXNoYXBlRnJlZSh0ZXhEYXRhLnNoYXBlLCBpbnB1dC5zaGFwZSkpIHtcbiAgICAgICAgLy8gVGhpcyBpcyBhIHNwZWNpYWwgY2FzZSB3aGVyZSBhIHRleHR1cmUgZXhpc3RzIGZvciBhIHRlbnNvclxuICAgICAgICAvLyBidXQgdGhlIHNoYXBlcyBhcmUgaW5jb21wYXRpYmxlIChkdWUgdG8gcGFja2luZyBjb25zdHJhaW50cykgYmVjYXVzZVxuICAgICAgICAvLyB0aGUgdGVuc29yIGRpZCBub3QgaGF2ZSBhIGNoYW5jZSB0byBnbyB0aHJvdWdoIHRoZSBwYWNrZWQgcmVzaGFwZVxuICAgICAgICAvLyBzaGFkZXIuIFRoaXMgb25seSBoYXBwZW5zIHdoZW4gd2UgcmVzaGFwZSB0aGUgKnNhbWUqIHRlbnNvciB0byBmb3JtXG4gICAgICAgIC8vICpkaXN0aW5jdCogaW5wdXRzIHRvIGFuIG9wLCBlLmcuIGRvdHRpbmcgYSB2ZWN0b3Igd2l0aCBpdHNlbGYuIFRoaXNcbiAgICAgICAgLy8gY2FzZSB3aWxsIGRpc2FwcGVhciBvbmNlIHBhY2tlZCB1cGxvYWRpbmcgaXMgdGhlIGRlZmF1bHQuXG5cbiAgICAgICAgY29uc3Qgc2F2ZWRJbnB1dCA9IGlucHV0O1xuICAgICAgICBjb25zdCB0YXJnZXRTaGFwZSA9IGlucHV0LnNoYXBlO1xuXG4gICAgICAgIGlucHV0LnNoYXBlID0gdGV4RGF0YS5zaGFwZTtcbiAgICAgICAgaW5wdXQgPSB0aGlzLnBhY2tlZFJlc2hhcGUoaW5wdXQgYXMgVGVuc29yLCB0YXJnZXRTaGFwZSk7XG4gICAgICAgIGRhdGFUb0Rpc3Bvc2UucHVzaChpbnB1dCk7XG4gICAgICAgIHRleERhdGEgPSB0aGlzLnRleERhdGEuZ2V0KGlucHV0LmRhdGFJZCk7XG5cbiAgICAgICAgc2F2ZWRJbnB1dC5zaGFwZSA9IHRhcmdldFNoYXBlO1xuICAgICAgfVxuXG4gICAgICByZXR1cm4ge3NoYXBlOiBpbnB1dC5zaGFwZSwgdGV4RGF0YSwgaXNVbmlmb3JtOiBmYWxzZX07XG4gICAgfSk7XG5cbiAgICB0aGlzLnVwbG9hZFRvR1BVKG91dHB1dC5kYXRhSWQpO1xuICAgIGNvbnN0IG91dHB1dERhdGE6XG4gICAgICAgIFRlbnNvckRhdGEgPSB7c2hhcGU6IG91dHB1dC5zaGFwZSwgdGV4RGF0YTogb3V0RGF0YSwgaXNVbmlmb3JtOiBmYWxzZX07XG4gICAgY29uc3Qga2V5ID0gZ3BncHVfbWF0aC5tYWtlU2hhZGVyS2V5KHByb2dyYW0sIGlucHV0c0RhdGEsIG91dHB1dERhdGEpO1xuICAgIGNvbnN0IGJpbmFyeSA9IHRoaXMuZ2V0QW5kU2F2ZUJpbmFyeShrZXksICgpID0+IHtcbiAgICAgIHJldHVybiBncGdwdV9tYXRoLmNvbXBpbGVQcm9ncmFtKFxuICAgICAgICAgIHRoaXMuZ3BncHUsIHByb2dyYW0sIGlucHV0c0RhdGEsIG91dHB1dERhdGEpO1xuICAgIH0pO1xuICAgIGNvbnN0IHNob3VsZFRpbWVQcm9ncmFtID0gdGhpcy5hY3RpdmVUaW1lcnMgIT0gbnVsbDtcbiAgICBsZXQgcXVlcnk6IFdlYkdMUXVlcnl8Q1BVVGltZXJRdWVyeTtcbiAgICBpZiAoc2hvdWxkVGltZVByb2dyYW0pIHtcbiAgICAgIHF1ZXJ5ID0gdGhpcy5zdGFydFRpbWVyKCk7XG4gICAgfVxuXG4gICAgaWYgKCFlbnYoKS5nZXQoJ0VOR0lORV9DT01QSUxFX09OTFknKSkge1xuICAgICAgZ3BncHVfbWF0aC5ydW5Qcm9ncmFtKFxuICAgICAgICAgIHRoaXMuZ3BncHUsIGJpbmFyeSwgaW5wdXRzRGF0YSwgb3V0cHV0RGF0YSwgY3VzdG9tVW5pZm9ybVZhbHVlcyk7XG4gICAgfVxuXG4gICAgZGF0YVRvRGlzcG9zZS5mb3JFYWNoKGluZm8gPT4gdGhpcy5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ySW5mbyhpbmZvKSk7XG5cbiAgICBpZiAoc2hvdWxkVGltZVByb2dyYW0pIHtcbiAgICAgIHF1ZXJ5ID0gdGhpcy5lbmRUaW1lcihxdWVyeSk7XG4gICAgICB0aGlzLmFjdGl2ZVRpbWVycy5wdXNoKFxuICAgICAgICAgIHtuYW1lOiBwcm9ncmFtLmNvbnN0cnVjdG9yLm5hbWUsIHF1ZXJ5OiB0aGlzLmdldFF1ZXJ5VGltZShxdWVyeSl9KTtcbiAgICB9XG5cbiAgICBjb25zdCBnbEZsdXNoVGhyZXNob2xkID0gZW52KCkuZ2V0KCdXRUJHTF9GTFVTSF9USFJFU0hPTEQnKTtcbiAgICAvLyBNYW51YWxseSBHTCBmbHVzaCByZXF1ZXN0ZWRcbiAgICBpZiAoZ2xGbHVzaFRocmVzaG9sZCA+IDApIHtcbiAgICAgIGNvbnN0IHRpbWUgPSB1dGlsLm5vdygpO1xuICAgICAgaWYgKCh0aW1lIC0gdGhpcy5sYXN0R2xGbHVzaFRpbWUpID4gZ2xGbHVzaFRocmVzaG9sZCkge1xuICAgICAgICB0aGlzLmdwZ3B1LmdsLmZsdXNoKCk7XG4gICAgICAgIHRoaXMubGFzdEdsRmx1c2hUaW1lID0gdGltZTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICBpZiAoIWVudigpLmdldEJvb2woJ1dFQkdMX0xBWklMWV9VTlBBQ0snKSAmJiBvdXREYXRhLmlzUGFja2VkICYmXG4gICAgICAgIHByZXZlbnRFYWdlclVucGFja2luZ09mT3V0cHV0ID09PSBmYWxzZSkge1xuICAgICAgY29uc3QgdW5wYWNrZWQgPSB0aGlzLnVucGFja1RlbnNvcihvdXRwdXQpO1xuICAgICAgdGhpcy5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ySW5mbyhvdXRwdXQpO1xuICAgICAgcmV0dXJuIHVucGFja2VkO1xuICAgIH1cbiAgICByZXR1cm4gb3V0cHV0O1xuICB9XG5cbiAgY29tcGlsZUFuZFJ1bihcbiAgICAgIHByb2dyYW06IEdQR1BVUHJvZ3JhbSwgaW5wdXRzOiBUZW5zb3JJbmZvW10sIG91dHB1dER0eXBlPzogRGF0YVR5cGUsXG4gICAgICBjdXN0b21Vbmlmb3JtVmFsdWVzPzogbnVtYmVyW11bXSxcbiAgICAgIHByZXZlbnRFYWdlclVucGFja2luZ09mT3V0cHV0ID0gZmFsc2UpOiBUZW5zb3JJbmZvIHtcbiAgICBvdXRwdXREdHlwZSA9IG91dHB1dER0eXBlIHx8IGlucHV0c1swXS5kdHlwZTtcbiAgICBjb25zdCBvdXRJbmZvID0gdGhpcy5ydW5XZWJHTFByb2dyYW0oXG4gICAgICAgIHByb2dyYW0sIGlucHV0cywgb3V0cHV0RHR5cGUsIGN1c3RvbVVuaWZvcm1WYWx1ZXMsXG4gICAgICAgIHByZXZlbnRFYWdlclVucGFja2luZ09mT3V0cHV0KTtcbiAgICByZXR1cm4gb3V0SW5mbztcbiAgfVxuXG4gIHByaXZhdGUgZ2V0QW5kU2F2ZUJpbmFyeShrZXk6IHN0cmluZywgZ2V0QmluYXJ5OiAoKSA9PiBHUEdQVUJpbmFyeSk6XG4gICAgICBHUEdQVUJpbmFyeSB7XG4gICAgaWYgKCEoa2V5IGluIHRoaXMuYmluYXJ5Q2FjaGUpKSB7XG4gICAgICB0aGlzLmJpbmFyeUNhY2hlW2tleV0gPSBnZXRCaW5hcnkoKTtcbiAgICB9XG4gICAgcmV0dXJuIHRoaXMuYmluYXJ5Q2FjaGVba2V5XTtcbiAgfVxuXG4gIGdldFRleHR1cmVNYW5hZ2VyKCk6IFRleHR1cmVNYW5hZ2VyIHtcbiAgICByZXR1cm4gdGhpcy50ZXh0dXJlTWFuYWdlcjtcbiAgfVxuXG4gIHByaXZhdGUgZGlzcG9zZWQgPSBmYWxzZTtcblxuICBvdmVycmlkZSBkaXNwb3NlKCkge1xuICAgIGlmICh0aGlzLmRpc3Bvc2VkKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuICAgIC8vIEF2b2lkIGRpc3Bvc2luZyB0aGUgY29tcGlsZWQgd2ViZ2wgcHJvZ3JhbXMgZHVyaW5nIHVuaXQgdGVzdGluZyBiZWNhdXNlXG4gICAgLy8gaXQgc2xvd3MgZG93biB0ZXN0IGV4ZWN1dGlvbi5cbiAgICBpZiAoIWVudigpLmdldEJvb2woJ0lTX1RFU1QnKSkge1xuICAgICAgY29uc3QgYWxsS2V5cyA9IE9iamVjdC5rZXlzKHRoaXMuYmluYXJ5Q2FjaGUpO1xuICAgICAgYWxsS2V5cy5mb3JFYWNoKGtleSA9PiB7XG4gICAgICAgIHRoaXMuZ3BncHUuZGVsZXRlUHJvZ3JhbSh0aGlzLmJpbmFyeUNhY2hlW2tleV0ud2ViR0xQcm9ncmFtKTtcbiAgICAgICAgZGVsZXRlIHRoaXMuYmluYXJ5Q2FjaGVba2V5XTtcbiAgICAgIH0pO1xuICAgIH1cbiAgICB0aGlzLnRleHR1cmVNYW5hZ2VyLmRpc3Bvc2UoKTtcbiAgICBpZiAodGhpcy5jYW52YXMgIT0gbnVsbCAmJlxuICAgICAgICAodHlwZW9mIChIVE1MQ2FudmFzRWxlbWVudCkgIT09ICd1bmRlZmluZWQnICYmXG4gICAgICAgICB0aGlzLmNhbnZhcyBpbnN0YW5jZW9mIEhUTUxDYW52YXNFbGVtZW50KSkge1xuICAgICAgdGhpcy5jYW52YXMucmVtb3ZlKCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHRoaXMuY2FudmFzID0gbnVsbDtcbiAgICB9XG4gICAgaWYgKHRoaXMuZ3BncHVDcmVhdGVkTG9jYWxseSkge1xuICAgICAgdGhpcy5ncGdwdS5wcm9ncmFtID0gbnVsbDtcbiAgICAgIHRoaXMuZ3BncHUuZGlzcG9zZSgpO1xuICAgIH1cbiAgICB0aGlzLmRpc3Bvc2VkID0gdHJ1ZTtcbiAgfVxuXG4gIG92ZXJyaWRlIGZsb2F0UHJlY2lzaW9uKCk6IDE2fDMyIHtcbiAgICBpZiAodGhpcy5mbG9hdFByZWNpc2lvblZhbHVlID09IG51bGwpIHtcbiAgICAgIHRoaXMuZmxvYXRQcmVjaXNpb25WYWx1ZSA9IHRpZHkoKCkgPT4ge1xuICAgICAgICBpZiAoIWVudigpLmdldCgnV0VCR0xfUkVOREVSX0ZMT0FUMzJfRU5BQkxFRCcpKSB7XG4gICAgICAgICAgLy8gTW9tZW50YXJpbHkgc3dpdGNoaW5nIERFQlVHIGZsYWcgdG8gZmFsc2Ugc28gd2UgZG9uJ3QgdGhyb3cgYW5cbiAgICAgICAgICAvLyBlcnJvciB0cnlpbmcgdG8gdXBsb2FkIGEgc21hbGwgdmFsdWUuXG4gICAgICAgICAgY29uc3QgZGVidWdGbGFnID0gZW52KCkuZ2V0Qm9vbCgnREVCVUcnKTtcbiAgICAgICAgICBlbnYoKS5zZXQoJ0RFQlVHJywgZmFsc2UpO1xuICAgICAgICAgIGNvbnN0IHVuZGVyZmxvd0NoZWNrVmFsdWUgPSB0aGlzLmFicyhzY2FsYXIoMWUtOCkpLmRhdGFTeW5jKClbMF07XG4gICAgICAgICAgZW52KCkuc2V0KCdERUJVRycsIGRlYnVnRmxhZyk7XG5cbiAgICAgICAgICBpZiAodW5kZXJmbG93Q2hlY2tWYWx1ZSA+IDApIHtcbiAgICAgICAgICAgIHJldHVybiAzMjtcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIDE2O1xuICAgICAgfSk7XG4gICAgfVxuICAgIHJldHVybiB0aGlzLmZsb2F0UHJlY2lzaW9uVmFsdWU7XG4gIH1cblxuICAvKiogUmV0dXJucyB0aGUgc21hbGxlc3QgcmVwcmVzZW50YWJsZSBudW1iZXIuICAqL1xuICBvdmVycmlkZSBlcHNpbG9uKCk6IG51bWJlciB7XG4gICAgcmV0dXJuIHRoaXMuZmxvYXRQcmVjaXNpb24oKSA9PT0gMzIgPyBFUFNJTE9OX0ZMT0FUMzIgOiBFUFNJTE9OX0ZMT0FUMTY7XG4gIH1cblxuICB1cGxvYWRUb0dQVShkYXRhSWQ6IERhdGFJZCk6IHZvaWQge1xuICAgIGNvbnN0IHRleERhdGEgPSB0aGlzLnRleERhdGEuZ2V0KGRhdGFJZCk7XG4gICAgY29uc3Qge3NoYXBlLCBkdHlwZSwgdmFsdWVzLCB0ZXh0dXJlLCB1c2FnZSwgaXNQYWNrZWR9ID0gdGV4RGF0YTtcblxuICAgIGlmICh0ZXh0dXJlICE9IG51bGwpIHtcbiAgICAgIC8vIEFycmF5IGlzIGFscmVhZHkgb24gR1BVLiBOby1vcC5cbiAgICAgIHJldHVybjtcbiAgICB9XG4gICAgY29uc3Qgc2hvdWxkVGltZVByb2dyYW0gPSB0aGlzLmFjdGl2ZVRpbWVycyAhPSBudWxsO1xuICAgIGxldCBzdGFydDogbnVtYmVyO1xuICAgIGlmIChzaG91bGRUaW1lUHJvZ3JhbSkge1xuICAgICAgc3RhcnQgPSB1dGlsLm5vdygpO1xuICAgIH1cblxuICAgIGxldCB0ZXhTaGFwZSA9IHRleERhdGEudGV4U2hhcGU7XG4gICAgaWYgKHRleFNoYXBlID09IG51bGwpIHtcbiAgICAgIC8vIFRoaXMgdGV4U2hhcGUgbWF5IG5vdCBiZSB0aGUgZmluYWwgdGV4dHVyZSBzaGFwZS4gRm9yIHBhY2tlZCBvciBkZW5zZVxuICAgICAgLy8gdGV4dHVyZXMsIHRoZSB0ZXhTaGFwZSB3aWxsIGJlIGNoYW5nZWQgd2hlbiB0ZXh0dXJlcyBhcmUgY3JlYXRlZC5cbiAgICAgIHRleFNoYXBlID0gd2ViZ2xfdXRpbC5nZXRUZXh0dXJlU2hhcGVGcm9tTG9naWNhbFNoYXBlKHNoYXBlLCBpc1BhY2tlZCk7XG4gICAgICB0ZXhEYXRhLnRleFNoYXBlID0gdGV4U2hhcGU7XG4gICAgfVxuXG4gICAgaWYgKHZhbHVlcyAhPSBudWxsKSB7XG4gICAgICBjb25zdCBzaGFwZUFzM0QgPSB3ZWJnbF91dGlsLmdldFNoYXBlQXMzRChzaGFwZSk7XG5cbiAgICAgIGxldCBwcm9ncmFtO1xuICAgICAgbGV0IHdpZHRoID0gdGV4U2hhcGVbMV0sIGhlaWdodCA9IHRleFNoYXBlWzBdO1xuICAgICAgY29uc3QgaXNCeXRlQXJyYXkgPVxuICAgICAgICAgIHZhbHVlcyBpbnN0YW5jZW9mIFVpbnQ4QXJyYXkgfHwgdmFsdWVzIGluc3RhbmNlb2YgVWludDhDbGFtcGVkQXJyYXk7XG5cbiAgICAgIC8vIHRleHR1cmUgZm9yIGZsb2F0IGFycmF5IGlzIFBoeXNpY2FsVGV4dHVyZVR5cGUuUEFDS0VEXzJYMl9GTE9BVDMyLCB3ZVxuICAgICAgLy8gbmVlZCB0byBtYWtlIHN1cmUgdGhlIHVwbG9hZCB1c2VzIHRoZSBzYW1lIHBhY2tlZCBzaXplXG4gICAgICBpZiAoaXNQYWNrZWQgfHwgIWlzQnl0ZUFycmF5KSB7XG4gICAgICAgIFt3aWR0aCwgaGVpZ2h0XSA9IHRleF91dGlsLmdldFBhY2tlZE1hdHJpeFRleHR1cmVTaGFwZVdpZHRoSGVpZ2h0KFxuICAgICAgICAgICAgdGV4U2hhcGVbMF0sIHRleFNoYXBlWzFdKTtcbiAgICAgIH1cblxuICAgICAgaWYgKGlzUGFja2VkKSB7XG4gICAgICAgIHByb2dyYW0gPSBuZXcgRW5jb2RlTWF0cml4UGFja2VkUHJvZ3JhbShzaGFwZUFzM0QsIGlzQnl0ZUFycmF5KTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHByb2dyYW0gPSBuZXcgRW5jb2RlTWF0cml4UHJvZ3JhbShzaGFwZUFzM0QsIGlzQnl0ZUFycmF5KTtcbiAgICAgIH1cblxuICAgICAgLy8gVGV4U2hhcGUgZm9yIGZsb2F0IGFycmF5IG5lZWRzIHRvIGJlIHRoZSBvcmlnaW5hbCBzaGFwZSwgd2hpY2ggYnl0ZVxuICAgICAgLy8gYXJyYXkgbmVlZHMgdG8gYmUgcGFja2VkIHNpemUuIFRoaXMgYWxsb3cgdGhlIGRhdGEgdXBsb2FkIHNoYXBlIHRvIGJlXG4gICAgICAvLyBtYXRjaGVkIHdpdGggdGV4dHVyZSBjcmVhdGlvbiBsb2dpYy5cbiAgICAgIGNvbnN0IHRlbXBEZW5zZUlucHV0VGV4U2hhcGU6IFtudW1iZXIsIG51bWJlcl0gPVxuICAgICAgICAgIGlzQnl0ZUFycmF5ID8gW2hlaWdodCwgd2lkdGhdIDogdGV4U2hhcGU7XG4gICAgICBjb25zdCB0ZW1wRGVuc2VJbnB1dEhhbmRsZSA9XG4gICAgICAgICAgdGhpcy5tYWtlVGVuc29ySW5mbyh0ZW1wRGVuc2VJbnB1dFRleFNoYXBlLCBkdHlwZSk7XG4gICAgICBjb25zdCB0ZW1wRGVuc2VJbnB1dFRleERhdGEgPVxuICAgICAgICAgIHRoaXMudGV4RGF0YS5nZXQodGVtcERlbnNlSW5wdXRIYW5kbGUuZGF0YUlkKTtcbiAgICAgIGlmIChpc0J5dGVBcnJheSkge1xuICAgICAgICB0ZW1wRGVuc2VJbnB1dFRleERhdGEudXNhZ2UgPSBUZXh0dXJlVXNhZ2UuUElYRUxTO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgdGVtcERlbnNlSW5wdXRUZXhEYXRhLnVzYWdlID0gVGV4dHVyZVVzYWdlLlVQTE9BRDtcbiAgICAgIH1cbiAgICAgIHRlbXBEZW5zZUlucHV0VGV4RGF0YS50ZXhTaGFwZSA9IHRlbXBEZW5zZUlucHV0VGV4U2hhcGU7XG4gICAgICB0aGlzLmdwZ3B1LnVwbG9hZERlbnNlTWF0cml4VG9UZXh0dXJlKFxuICAgICAgICAgIHRoaXMuZ2V0VGV4dHVyZSh0ZW1wRGVuc2VJbnB1dEhhbmRsZS5kYXRhSWQpLCB3aWR0aCwgaGVpZ2h0LFxuICAgICAgICAgIHZhbHVlcyBhcyBUeXBlZEFycmF5KTtcblxuICAgICAgY29uc3QgY3VzdG9tVmFsdWVzID0gW1toZWlnaHQsIHdpZHRoXV07XG4gICAgICAvLyBXZSB3YW50IHRoZSBvdXRwdXQgdG8gcmVtYWluIHBhY2tlZCByZWdhcmRsZXNzIG9mIHRoZSB2YWx1ZSBvZlxuICAgICAgLy8gV0VCR0xfUEFDSy5cbiAgICAgIGNvbnN0IHByZXZlbnRFYWdlclVucGFja2luZyA9IHRydWU7XG4gICAgICBjb25zdCBlbmNvZGVkT3V0cHV0VGFyZ2V0ID0gdGhpcy5ydW5XZWJHTFByb2dyYW0oXG4gICAgICAgICAgcHJvZ3JhbSwgW3RlbXBEZW5zZUlucHV0SGFuZGxlXSwgZHR5cGUsIGN1c3RvbVZhbHVlcyxcbiAgICAgICAgICBwcmV2ZW50RWFnZXJVbnBhY2tpbmcpO1xuXG4gICAgICAvLyBIYXZlIHRoZSBvcmlnaW5hbCB0ZXh0dXJlIGFzc3VtZSB0aGUgaWRlbnRpdHkgb2YgdGhlIGVuY29kZWQgb3V0cHV0LlxuICAgICAgY29uc3Qgb3V0cHV0VGV4RGF0YSA9IHRoaXMudGV4RGF0YS5nZXQoZW5jb2RlZE91dHB1dFRhcmdldC5kYXRhSWQpO1xuICAgICAgdGV4RGF0YS50ZXhTaGFwZSA9IG91dHB1dFRleERhdGEudGV4U2hhcGU7XG4gICAgICB0ZXhEYXRhLmlzUGFja2VkID0gb3V0cHV0VGV4RGF0YS5pc1BhY2tlZDtcbiAgICAgIHRleERhdGEudXNhZ2UgPSBvdXRwdXRUZXhEYXRhLnVzYWdlO1xuXG4gICAgICBpZiAoIWVudigpLmdldCgnRU5HSU5FX0NPTVBJTEVfT05MWScpKSB7XG4gICAgICAgIHRleERhdGEudGV4dHVyZSA9IG91dHB1dFRleERhdGEudGV4dHVyZTtcbiAgICAgICAgLy8gT25jZSB1cGxvYWRlZCwgZG9uJ3Qgc3RvcmUgdGhlIHZhbHVlcyBvbiBjcHUuXG4gICAgICAgIHRleERhdGEudmFsdWVzID0gbnVsbDtcbiAgICAgICAgdGhpcy50ZXhEYXRhLmRlbGV0ZShlbmNvZGVkT3V0cHV0VGFyZ2V0LmRhdGFJZCk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aGlzLmRpc3Bvc2VEYXRhKGVuY29kZWRPdXRwdXRUYXJnZXQuZGF0YUlkKTtcbiAgICAgIH1cblxuICAgICAgdGhpcy5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ySW5mbyh0ZW1wRGVuc2VJbnB1dEhhbmRsZSk7XG5cbiAgICAgIGlmIChzaG91bGRUaW1lUHJvZ3JhbSkge1xuICAgICAgICB0aGlzLnVwbG9hZFdhaXRNcyArPSB1dGlsLm5vdygpIC0gc3RhcnQ7XG4gICAgICB9XG4gICAgfSBlbHNlIHtcbiAgICAgIGNvbnN0IG5ld1RleHR1cmUgPSB0aGlzLmFjcXVpcmVUZXh0dXJlKHRleFNoYXBlLCB1c2FnZSwgZHR5cGUsIGlzUGFja2VkKTtcbiAgICAgIHRleERhdGEudGV4dHVyZSA9IG5ld1RleHR1cmU7XG4gICAgfVxuICB9XG5cbiAgcHJpdmF0ZSBjb252ZXJ0QW5kQ2FjaGVPbkNQVShkYXRhSWQ6IERhdGFJZCwgZmxvYXQzMlZhbHVlcz86IEZsb2F0MzJBcnJheSk6XG4gICAgICBUeXBlZEFycmF5IHtcbiAgICBjb25zdCB0ZXhEYXRhID0gdGhpcy50ZXhEYXRhLmdldChkYXRhSWQpO1xuICAgIGNvbnN0IHtkdHlwZX0gPSB0ZXhEYXRhO1xuXG4gICAgaWYgKGZsb2F0MzJWYWx1ZXMgIT0gbnVsbCkge1xuICAgICAgdGV4RGF0YS52YWx1ZXMgPSBmbG9hdDMyVG9UeXBlZEFycmF5KGZsb2F0MzJWYWx1ZXMsIGR0eXBlIGFzICdmbG9hdDMyJyk7XG4gICAgfVxuICAgIHJldHVybiB0ZXhEYXRhLnZhbHVlcyBhcyBUeXBlZEFycmF5O1xuICB9XG5cbiAgcHJpdmF0ZSBhY3F1aXJlVGV4dHVyZShcbiAgICAgIHRleFNoYXBlOiBbbnVtYmVyLCBudW1iZXJdLCB0ZXhUeXBlOiBUZXh0dXJlVXNhZ2UsIGR0eXBlOiBEYXRhVHlwZSxcbiAgICAgIGlzUGFja2VkOiBib29sZWFuKTogVGV4dHVyZSB7XG4gICAgdGhpcy5udW1CeXRlc0luR1BVICs9IHRoaXMuY29tcHV0ZUJ5dGVzKHRleFNoYXBlLCBkdHlwZSk7XG4gICAgaWYgKCF0aGlzLndhcm5lZEFib3V0TWVtb3J5ICYmXG4gICAgICAgIHRoaXMubnVtQnl0ZXNJbkdQVSA+IHRoaXMubnVtTUJCZWZvcmVXYXJuaW5nICogMTAyNCAqIDEwMjQpIHtcbiAgICAgIGNvbnN0IG1iID0gKHRoaXMubnVtQnl0ZXNJbkdQVSAvIDEwMjQgLyAxMDI0KS50b0ZpeGVkKDIpO1xuICAgICAgdGhpcy53YXJuZWRBYm91dE1lbW9yeSA9IHRydWU7XG4gICAgICBjb25zb2xlLndhcm4oXG4gICAgICAgICAgYEhpZ2ggbWVtb3J5IHVzYWdlIGluIEdQVTogJHttYn0gTUIsIGAgK1xuICAgICAgICAgIGBtb3N0IGxpa2VseSBkdWUgdG8gYSBtZW1vcnkgbGVha2ApO1xuICAgIH1cbiAgICByZXR1cm4gdGhpcy50ZXh0dXJlTWFuYWdlci5hY3F1aXJlVGV4dHVyZSh0ZXhTaGFwZSwgdGV4VHlwZSwgaXNQYWNrZWQpO1xuICB9XG5cbiAgcHJpdmF0ZSBjb21wdXRlQnl0ZXMoc2hhcGU6IFtudW1iZXIsIG51bWJlcl0sIGR0eXBlOiBEYXRhVHlwZSkge1xuICAgIHJldHVybiBzaGFwZVswXSAqIHNoYXBlWzFdICogdXRpbC5ieXRlc1BlckVsZW1lbnQoZHR5cGUpO1xuICB9XG5cbiAgY2hlY2tDb21waWxlQ29tcGxldGlvbigpIHtcbiAgICBmb3IgKGNvbnN0IFssIGJpbmFyeV0gb2YgT2JqZWN0LmVudHJpZXModGhpcy5iaW5hcnlDYWNoZSkpIHtcbiAgICAgIHRoaXMuY2hlY2tDb21wbGV0aW9uXyhiaW5hcnkpO1xuICAgIH1cbiAgfVxuXG4gIGFzeW5jIGNoZWNrQ29tcGlsZUNvbXBsZXRpb25Bc3luYygpOiBQcm9taXNlPGJvb2xlYW5bXT4ge1xuICAgIGNvbnN0IHBzID0gW107XG4gICAgaWYgKHRoaXMuZ3BncHUucGFyYWxsZWxDb21waWxhdGlvbkV4dGVuc2lvbikge1xuICAgICAgZm9yIChjb25zdCBbLCBiaW5hcnldIG9mIE9iamVjdC5lbnRyaWVzKHRoaXMuYmluYXJ5Q2FjaGUpKSB7XG4gICAgICAgIHBzLnB1c2godGhpcy5jaGVja0NvbXBsZXRpb25Bc3luY18oYmluYXJ5KSk7XG4gICAgICB9XG4gICAgICByZXR1cm4gUHJvbWlzZS5hbGwocHMpO1xuICAgIH0gZWxzZSB7XG4gICAgICBmb3IgKGNvbnN0IFssIGJpbmFyeV0gb2YgT2JqZWN0LmVudHJpZXModGhpcy5iaW5hcnlDYWNoZSkpIHtcbiAgICAgICAgY29uc3QgcDogUHJvbWlzZTxib29sZWFuPiA9IG5ldyBQcm9taXNlKChyZXNvbHZlKSA9PiB7XG4gICAgICAgICAgdHJ5IHtcbiAgICAgICAgICAgIHRoaXMuY2hlY2tDb21wbGV0aW9uXyhiaW5hcnkpO1xuICAgICAgICAgICAgcmVzb2x2ZSh0cnVlKTtcbiAgICAgICAgICB9IGNhdGNoIChlcnJvcikge1xuICAgICAgICAgICAgdGhyb3cgZXJyb3I7XG4gICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICAgICAgcHMucHVzaChwKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiBQcm9taXNlLmFsbChwcyk7XG4gICAgfVxuICB9XG5cbiAgcHJpdmF0ZSBhc3luYyBjaGVja0NvbXBsZXRpb25Bc3luY18oYmluYXJ5OiBHUEdQVUJpbmFyeSk6IFByb21pc2U8Ym9vbGVhbj4ge1xuICAgIGlmICh0aGlzLmdwZ3B1LmdsLmdldFByb2dyYW1QYXJhbWV0ZXIoXG4gICAgICAgICAgICBiaW5hcnkud2ViR0xQcm9ncmFtLFxuICAgICAgICAgICAgdGhpcy5ncGdwdS5wYXJhbGxlbENvbXBpbGF0aW9uRXh0ZW5zaW9uLkNPTVBMRVRJT05fU1RBVFVTX0tIUikpIHtcbiAgICAgIHJldHVybiB0aGlzLmNoZWNrQ29tcGxldGlvbl8oYmluYXJ5KTtcbiAgICB9IGVsc2Uge1xuICAgICAgYXdhaXQgbmV4dEZyYW1lKCk7XG4gICAgICByZXR1cm4gdGhpcy5jaGVja0NvbXBsZXRpb25Bc3luY18oYmluYXJ5KTtcbiAgICB9XG4gIH1cblxuICBwcml2YXRlIGNoZWNrQ29tcGxldGlvbl8oYmluYXJ5OiBHUEdQVUJpbmFyeSk6IGJvb2xlYW4ge1xuICAgIGlmICh0aGlzLmdwZ3B1LmdsLmdldFByb2dyYW1QYXJhbWV0ZXIoXG4gICAgICAgICAgICBiaW5hcnkud2ViR0xQcm9ncmFtLCB0aGlzLmdwZ3B1LmdsLkxJTktfU1RBVFVTKSA9PT0gZmFsc2UpIHtcbiAgICAgIGNvbnNvbGUubG9nKHRoaXMuZ3BncHUuZ2wuZ2V0UHJvZ3JhbUluZm9Mb2coYmluYXJ5LndlYkdMUHJvZ3JhbSkpO1xuICAgICAgaWYgKHRoaXMuZ3BncHUuZ2wuZ2V0U2hhZGVyUGFyYW1ldGVyKFxuICAgICAgICAgICAgICBiaW5hcnkuZnJhZ21lbnRTaGFkZXIsIHRoaXMuZ3BncHUuZ2wuQ09NUElMRV9TVEFUVVMpID09PSBmYWxzZSkge1xuICAgICAgICB3ZWJnbF91dGlsLmxvZ1NoYWRlclNvdXJjZUFuZEluZm9Mb2coXG4gICAgICAgICAgICBiaW5hcnkuc291cmNlLFxuICAgICAgICAgICAgdGhpcy5ncGdwdS5nbC5nZXRTaGFkZXJJbmZvTG9nKGJpbmFyeS5mcmFnbWVudFNoYWRlcikpO1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoJ0ZhaWxlZCB0byBjb21waWxlIGZyYWdtZW50IHNoYWRlci4nKTtcbiAgICAgIH1cbiAgICAgIHRocm93IG5ldyBFcnJvcignRmFpbGVkIHRvIGxpbmsgdmVydGV4IGFuZCBmcmFnbWVudCBzaGFkZXJzLicpO1xuICAgIH1cbiAgICByZXR1cm4gdHJ1ZTtcbiAgfVxuXG4gIGdldFVuaWZvcm1Mb2NhdGlvbnMoKSB7XG4gICAgZm9yIChjb25zdCBiaW5hcnkgb2YgT2JqZWN0LnZhbHVlcyh0aGlzLmJpbmFyeUNhY2hlKSkge1xuICAgICAgLy8gVE9ETzogSXRlcmF0aW5nIHRocm91Z2ggYWxsIGJpbmFyaWVzIHRvIGJ1aWxkIFZBT3MgaXMgc3VwcG9zZWQgdG8gYmUgaW5cbiAgICAgIC8vIGEgc2VwZXJhdGUgZnVuY3Rpb24sIGxpa2UgJ3NldFZhb3MnLiBIb3dldmVyLCB0byBhdm9pZCBicmVha2luZyBjaGFuZ2VzXG4gICAgICAvLyBmb3IgdGhlIHVzZXJzIHVzaW5nIHBhcmFsbGVsIGNvbXBpbGUgZmVhdHVyZSBub3csIGJ1aWxkVmFvIGlzIHNpbGVudGx5XG4gICAgICAvLyBhZGRlZCBoZXJlLlxuICAgICAgdGhpcy5ncGdwdS5idWlsZFZhbyhiaW5hcnkud2ViR0xQcm9ncmFtKTtcblxuICAgICAgY29uc3Qge1xuICAgICAgICB2YXJpYWJsZXNMb2NhdGlvbnMsXG4gICAgICAgIGN1c3RvbVVuaWZvcm1Mb2NhdGlvbnMsXG4gICAgICAgIGluZkxvYyxcbiAgICAgICAgbmFuTG9jLFxuICAgICAgICBvdXRTaGFwZUxvY2F0aW9uLFxuICAgICAgICBvdXRTaGFwZVN0cmlkZXNMb2NhdGlvbixcbiAgICAgICAgb3V0VGV4U2hhcGVMb2NhdGlvblxuICAgICAgfSA9IGdldFVuaWZvcm1Mb2NhdGlvbnModGhpcy5ncGdwdSwgYmluYXJ5LnByb2dyYW0sIGJpbmFyeS53ZWJHTFByb2dyYW0pO1xuICAgICAgYmluYXJ5LnZhcmlhYmxlc0xvY2F0aW9ucyA9IHZhcmlhYmxlc0xvY2F0aW9ucztcbiAgICAgIGJpbmFyeS5jdXN0b21Vbmlmb3JtTG9jYXRpb25zID0gY3VzdG9tVW5pZm9ybUxvY2F0aW9ucztcbiAgICAgIGJpbmFyeS5pbmZMb2MgPSBpbmZMb2M7XG4gICAgICBiaW5hcnkubmFuTG9jID0gbmFuTG9jO1xuICAgICAgYmluYXJ5Lm91dFNoYXBlTG9jYXRpb24gPSBvdXRTaGFwZUxvY2F0aW9uO1xuICAgICAgYmluYXJ5Lm91dFNoYXBlU3RyaWRlc0xvY2F0aW9uID0gb3V0U2hhcGVTdHJpZGVzTG9jYXRpb247XG4gICAgICBiaW5hcnkub3V0VGV4U2hhcGVMb2NhdGlvbiA9IG91dFRleFNoYXBlTG9jYXRpb247XG4gICAgfVxuICB9XG5cbiAgLyoqXG4gICAqIENyZWF0ZSBhIFRGLmpzIHRlbnNvciBvdXQgb2YgYW4gZXhpc3RpbmcgV2ViR0wgdGV4dHVyZS4gQSBuZXcgdGV4dHVyZSB3aWxsXG4gICAqIGJlIGNyZWF0ZWQuXG4gICAqL1xuICBvdmVycmlkZSBjcmVhdGVUZW5zb3JGcm9tR1BVRGF0YShcbiAgICAgIHZhbHVlczogV2ViR0xEYXRhLCBzaGFwZTogbnVtYmVyW10sIGR0eXBlOiBEYXRhVHlwZSk6IFRlbnNvciB7XG4gICAgdmFsdWVzLmNoYW5uZWxzID0gdmFsdWVzLmNoYW5uZWxzIHx8ICdSR0JBJztcbiAgICBjb25zdCB7dGV4dHVyZSwgaGVpZ2h0LCB3aWR0aCwgY2hhbm5lbHN9ID0gdmFsdWVzO1xuICAgIGNvbnN0IGJhY2tlbmQgPSBlbmdpbmUoKS5iYWNrZW5kIGFzIE1hdGhCYWNrZW5kV2ViR0w7XG5cbiAgICAvLyBIYXZlIHRvIHRocm93IGFuIGVycm9yLCBvdGhlcndpc2UgV2ViR0wganVzdCB3YXJucyBhbmQgcmV0dXJucyB3cm9uZ1xuICAgIC8vIHZhbHVlcy5cbiAgICBpZiAoIWJhY2tlbmQuZ3BncHUuZ2wuaXNUZXh0dXJlKHRleHR1cmUpKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgYFRoZSB0ZXh0dXJlIGlzIGludmFsaWQuIEFsc28sIHBsZWFzZSBtYWtlIHN1cmUgdGhlIHRleHR1cmUgYW5kIGAgK1xuICAgICAgICAgIGB0aGUgVEZKUyBXZWJHTCBiYWNrZW5kIGFyZSB1c2luZyB0aGUgc2FtZSBjYW52YXMuIElmIHlvdSB3YW50IHRvIGAgK1xuICAgICAgICAgIGB1c2UgeW91ciBvd24gY3VzdG9tIGNhbnZhcywgeW91IGhhdmUgdG8gY3JlYXRlIGFuZCB1c2UgdGhlIGN1c3RvbSBgICtcbiAgICAgICAgICBgVEZKUyBXZWJHTCBiYWNrZW5kIGNyZWF0ZWQgZnJvbSB0aGUgY2FudmFzIHRocm91Z2ggYCArXG4gICAgICAgICAgYCduZXcgdGYuTWF0aEJhY2tlbmRXZWJHTChjdXN0b21DYW52YXMpJy5gKTtcbiAgICB9XG5cbiAgICBjb25zdCBkYXRhSWQgPVxuICAgICAgICBiYWNrZW5kLndyaXRlVGV4dHVyZSh0ZXh0dXJlLCBzaGFwZSwgZHR5cGUsIGhlaWdodCwgd2lkdGgsIGNoYW5uZWxzKTtcbiAgICByZXR1cm4gZW5naW5lKCkubWFrZVRlbnNvckZyb21EYXRhSWQoZGF0YUlkLCBzaGFwZSwgZHR5cGUsIGJhY2tlbmQpO1xuICB9XG59XG5cbmZ1bmN0aW9uIGZsb2F0MzJUb1R5cGVkQXJyYXk8RCBleHRlbmRzIE51bWVyaWNEYXRhVHlwZT4oXG4gICAgYTogRmxvYXQzMkFycmF5LCBkdHlwZTogRCk6IHRmLkRhdGFUeXBlTWFwW0RdIHtcbiAgaWYgKGR0eXBlID09PSAnZmxvYXQzMicgfHwgZHR5cGUgPT09ICdjb21wbGV4NjQnKSB7XG4gICAgcmV0dXJuIGEgYXMgdGYuRGF0YVR5cGVNYXBbRF07XG4gIH0gZWxzZSBpZiAoZHR5cGUgPT09ICdpbnQzMicgfHwgZHR5cGUgPT09ICdib29sJykge1xuICAgIGNvbnN0IHJlc3VsdCA9IChkdHlwZSA9PT0gJ2ludDMyJykgPyBuZXcgSW50MzJBcnJheShhLmxlbmd0aCkgOlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXcgVWludDhBcnJheShhLmxlbmd0aCk7XG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCByZXN1bHQubGVuZ3RoOyArK2kpIHtcbiAgICAgIHJlc3VsdFtpXSA9IE1hdGgucm91bmQoYVtpXSk7XG4gICAgfVxuICAgIHJldHVybiByZXN1bHQgYXMgdGYuRGF0YVR5cGVNYXBbRF07XG4gIH0gZWxzZSB7XG4gICAgdGhyb3cgbmV3IEVycm9yKGBVbmtub3duIGR0eXBlICR7ZHR5cGV9YCk7XG4gIH1cbn1cbiJdfQ==