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
// Workaround for: https://github.com/bazelbuild/rules_nodejs/issues/1265
/// <reference types="@webgpu/types/dist" />
import { getGlobal } from './global_util';
import { tensorToString } from './tensor_format';
import * as util from './util';
import { computeStrides, toNestedArray } from './util';
/**
 * A mutable object, similar to `tf.Tensor`, that allows users to set values
 * at locations before converting to an immutable `tf.Tensor`.
 *
 * See `tf.buffer` for creating a tensor buffer.
 *
 * @doc {heading: 'Tensors', subheading: 'Classes'}
 */
export class TensorBuffer {
    constructor(shape, dtype, values) {
        this.dtype = dtype;
        this.shape = shape.slice();
        this.size = util.sizeFromShape(shape);
        if (values != null) {
            const n = values.length;
            util.assert(n === this.size, () => `Length of values '${n}' does not match the size ` +
                `inferred by the shape '${this.size}'.`);
        }
        if (dtype === 'complex64') {
            throw new Error(`complex64 dtype TensorBuffers are not supported. Please create ` +
                `a TensorBuffer for the real and imaginary parts separately and ` +
                `call tf.complex(real, imag).`);
        }
        this.values = values || util.getArrayFromDType(dtype, this.size);
        this.strides = computeStrides(shape);
    }
    /**
     * Sets a value in the buffer at a given location.
     *
     * @param value The value to set.
     * @param locs  The location indices.
     *
     * @doc {heading: 'Tensors', subheading: 'Creation'}
     */
    set(value, ...locs) {
        if (locs.length === 0) {
            locs = [0];
        }
        util.assert(locs.length === this.rank, () => `The number of provided coordinates (${locs.length}) must ` +
            `match the rank (${this.rank})`);
        const index = this.locToIndex(locs);
        this.values[index] = value;
    }
    /**
     * Returns the value in the buffer at the provided location.
     *
     * @param locs The location indices.
     *
     * @doc {heading: 'Tensors', subheading: 'Creation'}
     */
    get(...locs) {
        if (locs.length === 0) {
            locs = [0];
        }
        let i = 0;
        for (const loc of locs) {
            if (loc < 0 || loc >= this.shape[i]) {
                const msg = `Requested out of range element at ${locs}. ` +
                    `  Buffer shape=${this.shape}`;
                throw new Error(msg);
            }
            i++;
        }
        let index = locs[locs.length - 1];
        for (let i = 0; i < locs.length - 1; ++i) {
            index += this.strides[i] * locs[i];
        }
        return this.values[index];
    }
    locToIndex(locs) {
        if (this.rank === 0) {
            return 0;
        }
        else if (this.rank === 1) {
            return locs[0];
        }
        let index = locs[locs.length - 1];
        for (let i = 0; i < locs.length - 1; ++i) {
            index += this.strides[i] * locs[i];
        }
        return index;
    }
    indexToLoc(index) {
        if (this.rank === 0) {
            return [];
        }
        else if (this.rank === 1) {
            return [index];
        }
        const locs = new Array(this.shape.length);
        for (let i = 0; i < locs.length - 1; ++i) {
            locs[i] = Math.floor(index / this.strides[i]);
            index -= locs[i] * this.strides[i];
        }
        locs[locs.length - 1] = index;
        return locs;
    }
    get rank() {
        return this.shape.length;
    }
    /**
     * Creates an immutable `tf.Tensor` object from the buffer.
     *
     * @doc {heading: 'Tensors', subheading: 'Creation'}
     */
    toTensor() {
        return trackerFn().makeTensor(this.values, this.shape, this.dtype);
    }
}
// For tracking tensor creation and disposal.
let trackerFn = null;
// Used by chaining methods to call into ops.
let opHandler = null;
// Used to warn about deprecated methods.
let deprecationWarningFn = null;
// This here so that we can use this method on dev branches and keep the
// functionality at master.
// tslint:disable-next-line:no-unused-expression
[deprecationWarningFn];
/**
 * An external consumer can register itself as the tensor tracker. This way
 * the Tensor class can notify the tracker for every tensor created and
 * disposed.
 */
export function setTensorTracker(fn) {
    trackerFn = fn;
}
/**
 * An external consumer can register itself as the op handler. This way the
 * Tensor class can have chaining methods that call into ops via the op
 * handler.
 */
export function setOpHandler(handler) {
    opHandler = handler;
}
/**
 * Sets the deprecation warning function to be used by this file. This way the
 * Tensor class can be a leaf but still use the environment.
 */
export function setDeprecationWarningFn(fn) {
    deprecationWarningFn = fn;
}
/**
 * A `tf.Tensor` object represents an immutable, multidimensional array of
 * numbers that has a shape and a data type.
 *
 * For performance reasons, functions that create tensors do not necessarily
 * perform a copy of the data passed to them (e.g. if the data is passed as a
 * `Float32Array`), and changes to the data will change the tensor. This is not
 * a feature and is not supported. To avoid this behavior, use the tensor before
 * changing the input data or create a copy with `copy = tf.add(yourTensor, 0)`.
 *
 * See `tf.tensor` for details on how to create a `tf.Tensor`.
 *
 * @doc {heading: 'Tensors', subheading: 'Classes'}
 */
export class Tensor {
    constructor(shape, dtype, dataId, id) {
        /** Whether this tensor has been globally kept. */
        this.kept = false;
        this.isDisposedInternal = false;
        this.shape = shape.slice();
        this.dtype = dtype || 'float32';
        this.size = util.sizeFromShape(shape);
        this.strides = computeStrides(shape);
        this.dataId = dataId;
        this.id = id;
        this.rankType = (this.rank < 5 ? this.rank.toString() : 'higher');
    }
    get rank() {
        return this.shape.length;
    }
    /**
     * Returns a promise of `tf.TensorBuffer` that holds the underlying data.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    async buffer() {
        const vals = await this.data();
        return opHandler.buffer(this.shape, this.dtype, vals);
    }
    /**
     * Returns a `tf.TensorBuffer` that holds the underlying data.
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    bufferSync() {
        return opHandler.buffer(this.shape, this.dtype, this.dataSync());
    }
    /**
     * Returns the tensor data as a nested array. The transfer of data is done
     * asynchronously.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    async array() {
        const vals = await this.data();
        return toNestedArray(this.shape, vals, this.dtype === 'complex64');
    }
    /**
     * Returns the tensor data as a nested array. The transfer of data is done
     * synchronously.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    arraySync() {
        return toNestedArray(this.shape, this.dataSync(), this.dtype === 'complex64');
    }
    /**
     * Asynchronously downloads the values from the `tf.Tensor`. Returns a
     * promise of `TypedArray` that resolves when the computation has finished.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    async data() {
        this.throwIfDisposed();
        const data = trackerFn().read(this.dataId);
        if (this.dtype === 'string') {
            const bytes = await data;
            try {
                return bytes.map(b => util.decodeString(b));
            }
            catch (_a) {
                throw new Error('Failed to decode the string bytes into utf-8. ' +
                    'To get the original bytes, call tensor.bytes().');
            }
        }
        return data;
    }
    /**
     * Copy the tensor's data to a new GPU resource. Comparing to the `dataSync()`
     * and `data()`, this method prevents data from being downloaded to CPU.
     *
     * For WebGL backend, the data will be stored on a densely packed texture.
     * This means that the texture will use the RGBA channels to store value.
     *
     * For WebGPU backend, the data will be stored on a buffer. There is no
     * parameter, so can not use a user-defined size to create the buffer.
     *
     * @param options:
     *     For WebGL,
     *         - customTexShape: Optional. If set, will use the user defined
     *     texture shape to create the texture.
     *
     * @returns For WebGL backend, a GPUData contains the new texture and
     *     its information.
     *     {
     *        tensorRef: The tensor that is associated with this texture,
     *        texture: WebGLTexture,
     *        texShape: [number, number] // [height, width]
     *     }
     *
     *     For WebGPU backend, a GPUData contains the new buffer.
     *     {
     *        tensorRef: The tensor that is associated with this buffer,
     *        buffer: GPUBuffer,
     *     }
     *
     *     Remember to dispose the GPUData after it is used by
     *     `res.tensorRef.dispose()`.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    dataToGPU(options) {
        this.throwIfDisposed();
        return trackerFn().readToGPU(this.dataId, options);
    }
    /**
     * Synchronously downloads the values from the `tf.Tensor`. This blocks the
     * UI thread until the values are ready, which can cause performance issues.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    dataSync() {
        this.throwIfDisposed();
        const data = trackerFn().readSync(this.dataId);
        if (this.dtype === 'string') {
            try {
                return data.map(b => util.decodeString(b));
            }
            catch (_a) {
                throw new Error('Failed to decode the string bytes into utf-8. ' +
                    'To get the original bytes, call tensor.bytes().');
            }
        }
        return data;
    }
    /** Returns the underlying bytes of the tensor's data. */
    async bytes() {
        this.throwIfDisposed();
        const data = await trackerFn().read(this.dataId);
        if (this.dtype === 'string') {
            return data;
        }
        else {
            return new Uint8Array(data.buffer);
        }
    }
    /**
     * Disposes `tf.Tensor` from memory.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    dispose() {
        if (this.isDisposed) {
            return;
        }
        trackerFn().disposeTensor(this);
        this.isDisposedInternal = true;
    }
    get isDisposed() {
        return this.isDisposedInternal;
    }
    throwIfDisposed() {
        if (this.isDisposed) {
            throw new Error(`Tensor is disposed.`);
        }
    }
    /**
     * Prints the `tf.Tensor`. See `tf.print` for details.
     *
     * @param verbose Whether to print verbose information about the tensor,
     *    including dtype and size.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    print(verbose = false) {
        return opHandler.print(this, verbose);
    }
    /**
     * Returns a copy of the tensor. See `tf.clone` for details.
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    clone() {
        this.throwIfDisposed();
        return opHandler.clone(this);
    }
    /**
     * Returns a human-readable description of the tensor. Useful for logging.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    toString(verbose = false) {
        const vals = this.dataSync();
        return tensorToString(vals, this.shape, this.dtype, verbose);
    }
    cast(dtype) {
        this.throwIfDisposed();
        return opHandler.cast(this, dtype);
    }
    variable(trainable = true, name, dtype) {
        this.throwIfDisposed();
        return trackerFn().makeVariable(this, trainable, name, dtype);
    }
}
Object.defineProperty(Tensor, Symbol.hasInstance, {
    value: (instance) => {
        // Implementation note: we should use properties of the object that will be
        // defined before the constructor body has finished executing (methods).
        // This is because when this code is transpiled by babel, babel will call
        // classCallCheck before the constructor body is run.
        // See https://github.com/tensorflow/tfjs/issues/3384 for backstory.
        return !!instance && instance.data != null && instance.dataSync != null &&
            instance.throwIfDisposed != null;
    }
});
export function getGlobalTensorClass() {
    // Use getGlobal so that we can augment the Tensor class across package
    // boundaries becase the node resolution alg may result in different modules
    // being returned for this file depending on the path they are loaded from.
    return getGlobal('Tensor', () => {
        return Tensor;
    });
}
// Global side effect. Cache global reference to Tensor class
getGlobalTensorClass();
/**
 * A mutable `tf.Tensor`, useful for persisting state, e.g. for training.
 *
 * @doc {heading: 'Tensors', subheading: 'Classes'}
 */
export class Variable extends Tensor {
    constructor(initialValue, trainable, name, tensorId) {
        super(initialValue.shape, initialValue.dtype, initialValue.dataId, tensorId);
        this.trainable = trainable;
        this.name = name;
    }
    /**
     * Assign a new `tf.Tensor` to this variable. The new `tf.Tensor` must have
     * the same shape and dtype as the old `tf.Tensor`.
     *
     * @param newValue New tensor to be assigned to this variable.
     *
     * @doc {heading: 'Tensors', subheading: 'Classes'}
     */
    assign(newValue) {
        if (newValue.dtype !== this.dtype) {
            throw new Error(`dtype of the new value (${newValue.dtype}) and ` +
                `previous value (${this.dtype}) must match`);
        }
        if (!util.arraysEqual(newValue.shape, this.shape)) {
            throw new Error(`shape of the new value (${newValue.shape}) and ` +
                `previous value (${this.shape}) must match`);
        }
        trackerFn().disposeTensor(this);
        this.dataId = newValue.dataId;
        trackerFn().incRef(this, null /* backend */);
    }
    dispose() {
        trackerFn().disposeVariable(this);
        this.isDisposedInternal = true;
    }
}
Object.defineProperty(Variable, Symbol.hasInstance, {
    value: (instance) => {
        return instance instanceof Tensor && instance.assign != null &&
            instance.assign instanceof Function;
    }
});
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoidGVuc29yLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsiLi4vLi4vLi4vLi4vLi4vdGZqcy1jb3JlL3NyYy90ZW5zb3IudHMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7Ozs7Ozs7Ozs7Ozs7OztHQWVHO0FBRUgseUVBQXlFO0FBQ3pFLDRDQUE0QztBQUU1QyxPQUFPLEVBQUMsU0FBUyxFQUFDLE1BQU0sZUFBZSxDQUFDO0FBQ3hDLE9BQU8sRUFBQyxjQUFjLEVBQUMsTUFBTSxpQkFBaUIsQ0FBQztBQUcvQyxPQUFPLEtBQUssSUFBSSxNQUFNLFFBQVEsQ0FBQztBQUMvQixPQUFPLEVBQUMsY0FBYyxFQUFFLGFBQWEsRUFBQyxNQUFNLFFBQVEsQ0FBQztBQVdyRDs7Ozs7OztHQU9HO0FBQ0gsTUFBTSxPQUFPLFlBQVk7SUFNdkIsWUFBWSxLQUFrQixFQUFTLEtBQVEsRUFBRSxNQUF1QjtRQUFqQyxVQUFLLEdBQUwsS0FBSyxDQUFHO1FBQzdDLElBQUksQ0FBQyxLQUFLLEdBQUcsS0FBSyxDQUFDLEtBQUssRUFBaUIsQ0FBQztRQUMxQyxJQUFJLENBQUMsSUFBSSxHQUFHLElBQUksQ0FBQyxhQUFhLENBQUMsS0FBSyxDQUFDLENBQUM7UUFFdEMsSUFBSSxNQUFNLElBQUksSUFBSSxFQUFFO1lBQ2xCLE1BQU0sQ0FBQyxHQUFHLE1BQU0sQ0FBQyxNQUFNLENBQUM7WUFDeEIsSUFBSSxDQUFDLE1BQU0sQ0FDUCxDQUFDLEtBQUssSUFBSSxDQUFDLElBQUksRUFDZixHQUFHLEVBQUUsQ0FBQyxxQkFBcUIsQ0FBQyw0QkFBNEI7Z0JBQ3BELDBCQUEwQixJQUFJLENBQUMsSUFBSSxJQUFJLENBQUMsQ0FBQztTQUNsRDtRQUNELElBQUksS0FBSyxLQUFLLFdBQVcsRUFBRTtZQUN6QixNQUFNLElBQUksS0FBSyxDQUNYLGlFQUFpRTtnQkFDakUsaUVBQWlFO2dCQUNqRSw4QkFBOEIsQ0FBQyxDQUFDO1NBQ3JDO1FBQ0QsSUFBSSxDQUFDLE1BQU0sR0FB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LENBQUM7SUFDMUMsQ0FBQztDQUNGLENBQUMsQ0FBQyIsInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQGxpY2Vuc2VcbiAqIENvcHlyaWdodCAyMDE3IEdvb2dsZSBMTEMuIEFsbCBSaWdodHMgUmVzZXJ2ZWQuXG4gKiBMaWNlbnNlZCB1bmRlciB0aGUgQXBhY2hlIExpY2Vuc2UsIFZlcnNpb24gMi4wICh0aGUgXCJMaWNlbnNlXCIpO1xuICogeW91IG1heSBub3QgdXNlIHRoaXMgZmlsZSBleGNlcHQgaW4gY29tcGxpYW5jZSB3aXRoIHRoZSBMaWNlbnNlLlxuICogWW91IG1heSBvYnRhaW4gYSBjb3B5IG9mIHRoZSBMaWNlbnNlIGF0XG4gKlxuICogaHR0cDovL3d3dy5hcGFjaGUub3JnL2xpY2Vuc2VzL0xJQ0VOU0UtMi4wXG4gKlxuICogVW5sZXNzIHJlcXVpcmVkIGJ5IGFwcGxpY2FibGUgbGF3IG9yIGFncmVlZCB0byBpbiB3cml0aW5nLCBzb2Z0d2FyZVxuICogZGlzdHJpYnV0ZWQgdW5kZXIgdGhlIExpY2Vuc2UgaXMgZGlzdHJpYnV0ZWQgb24gYW4gXCJBUyBJU1wiIEJBU0lTLFxuICogV0lUSE9VVCBXQVJSQU5USUVTIE9SIENPTkRJVElPTlMgT0YgQU5ZIEtJTkQsIGVpdGhlciBleHByZXNzIG9yIGltcGxpZWQuXG4gKiBTZWUgdGhlIExpY2Vuc2UgZm9yIHRoZSBzcGVjaWZpYyBsYW5ndWFnZSBnb3Zlcm5pbmcgcGVybWlzc2lvbnMgYW5kXG4gKiBsaW1pdGF0aW9ucyB1bmRlciB0aGUgTGljZW5zZS5cbiAqID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG4gKi9cblxuLy8gV29ya2Fyb3VuZCBmb3I6IGh0dHBzOi8vZ2l0aHViLmNvbS9iYXplbGJ1aWxkL3J1bGVzX25vZGVqcy9pc3N1ZXMvMTI2NVxuLy8vIDxyZWZlcmVuY2UgdHlwZXM9XCJAd2ViZ3B1L3R5cGVzL2Rpc3RcIiAvPlxuXG5pbXBvcnQge2dldEdsb2JhbH0gZnJvbSAnLi9nbG9iYWxfdXRpbCc7XG5pbXBvcnQge3RlbnNvclRvU3RyaW5nfSBmcm9tICcuL3RlbnNvcl9mb3JtYXQnO1xuaW1wb3J0IHtEYXRhSWQsIFRlbnNvckluZm99IGZyb20gJy4vdGVuc29yX2luZm8nO1xuaW1wb3J0IHtBcnJheU1hcCwgQmFja2VuZFZhbHVlcywgRGF0YVR5cGUsIERhdGFUeXBlTWFwLCBEYXRhVmFsdWVzLCBOdW1lcmljRGF0YVR5cGUsIFJhbmssIFNoYXBlTWFwLCBTaW5nbGVWYWx1ZU1hcCwgVHlwZWRBcnJheX0gZnJvbSAnLi90eXBlcyc7XG5pbXBvcnQgKiBhcyB1dGlsIGZyb20gJy4vdXRpbCc7XG5pbXBvcnQge2NvbXB1dGVTdHJpZGVzLCB0b05lc3RlZEFycmF5fSBmcm9tICcuL3V0aWwnO1xuXG5leHBvcnQgaW50ZXJmYWNlIFRlbnNvckRhdGE8RCBleHRlbmRzIERhdGFUeXBlPiB7XG4gIGRhdGFJZD86IERhdGFJZDtcbiAgdmFsdWVzPzogRGF0YVR5cGVNYXBbRF07XG59XG5cbi8vIFRoaXMgaW50ZXJmYWNlIG1pbWljcyBLZXJuZWxCYWNrZW5kIChpbiBiYWNrZW5kLnRzKSwgd2hpY2ggd291bGQgY3JlYXRlIGFcbi8vIGNpcmN1bGFyIGRlcGVuZGVuY3kgaWYgaW1wb3J0ZWQuXG5leHBvcnQgaW50ZXJmYWNlIEJhY2tlbmQge31cblxuLyoqXG4gKiBBIG11dGFibGUgb2JqZWN0LCBzaW1pbGFyIHRvIGB0Zi5UZW5zb3JgLCB0aGF0IGFsbG93cyB1c2VycyB0byBzZXQgdmFsdWVzXG4gKiBhdCBsb2NhdGlvbnMgYmVmb3JlIGNvbnZlcnRpbmcgdG8gYW4gaW1tdXRhYmxlIGB0Zi5UZW5zb3JgLlxuICpcbiAqIFNlZSBgdGYuYnVmZmVyYCBmb3IgY3JlYXRpbmcgYSB0ZW5zb3IgYnVmZmVyLlxuICpcbiAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NsYXNzZXMnfVxuICovXG5leHBvcnQgY2xhc3MgVGVuc29yQnVmZmVyPFIgZXh0ZW5kcyBSYW5rLCBEIGV4dGVuZHMgRGF0YVR5cGUgPSAnZmxvYXQzMic+IHtcbiAgc2l6ZTogbnVtYmVyO1xuICBzaGFwZTogU2hhcGVNYXBbUl07XG4gIHN0cmlkZXM6IG51bWJlcltdO1xuICB2YWx1ZXM6IERhdGFUeXBlTWFwW0RdO1xuXG4gIGNvbnN0cnVjdG9yKHNoYXBlOiBTaGFwZU1hcFtSXSwgcHVibGljIGR0eXBlOiBELCB2YWx1ZXM/OiBEYXRhVHlwZU1hcFtEXSkge1xuICAgIHRoaXMuc2hhcGUgPSBzaGFwZS5zbGljZSgpIGFzIFNoYXBlTWFwW1JdO1xuICAgIHRoaXMuc2l6ZSA9IHV0aWwuc2l6ZUZyb21TaGFwZShzaGFwZSk7XG5cbiAgICBpZiAodmFsdWVzICE9IG51bGwpIHtcbiAgICAgIGNvbnN0IG4gPSB2YWx1ZXMubGVuZ3RoO1xuICAgICAgdXRpbC5hc3NlcnQoXG4gICAgICAgICAgbiA9PT0gdGhpcy5zaXplLFxuICAgICAgICAgICgpID0+IGBMZW5ndGggb2YgdmFsdWVzICcke259JyBkb2VzIG5vdCBtYXRjaCB0aGUgc2l6ZSBgICtcbiAgICAgICAgICAgICAgYGluZmVycmVkIGJ5IHRoZSBzaGFwZSAnJHt0aGlzLnNpemV9Jy5gKTtcbiAgICB9XG4gICAgaWYgKGR0eXBlID09PSAnY29tcGxleDY0Jykge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgIGBjb21wbGV4NjQgZHR5cGUgVGVuc29yQnVmZmVycyBhcmUgbm90IHN1cHBvcnRlZC4gUGxlYXNlIGNyZWF0ZSBgICtcbiAgICAgICAgICBgYSBUZW5zb3JCdWZmZXIgZm9yIHRoZSByZWFsIGFuZCBpbWFnaW5hcnkgcGFydHMgc2VwYXJhdGVseSBhbmQgYCArXG4gICAgICAgICAgYGNhbGwgdGYuY29tcGxleChyZWFsLCBpbWFnKS5gKTtcbiAgICB9XG4gICAgdGhpcy52YWx1ZXMgPSB2YWx1ZXMgfHwgdXRpbC5nZXRBcnJheUZyb21EVHlwZShkdHlwZSwgdGhpcy5zaXplKTtcbiAgICB0aGlzLnN0cmlkZXMgPSBjb21wdXRlU3RyaWRlcyhzaGFwZSk7XG4gIH1cblxuICAvKipcbiAgICogU2V0cyBhIHZhbHVlIGluIHRoZSBidWZmZXIgYXQgYSBnaXZlbiBsb2NhdGlvbi5cbiAgICpcbiAgICogQHBhcmFtIHZhbHVlIFRoZSB2YWx1ZSB0byBzZXQuXG4gICAqIEBwYXJhbSBsb2NzICBUaGUgbG9jYXRpb24gaW5kaWNlcy5cbiAgICpcbiAgICogQGRvYyB7aGVhZGluZzogJ1RlbnNvcnMnLCBzdWJoZWFkaW5nOiAnQ3JlYXRpb24nfVxuICAgKi9cbiAgc2V0KHZhbHVlOiBTaW5nbGVWYWx1ZU1hcFtEXSwgLi4ubG9jczogbnVtYmVyW10pOiB2b2lkIHtcbiAgICBpZiAobG9jcy5sZW5ndGggPT09IDApIHtcbiAgICAgIGxvY3MgPSBbMF07XG4gICAgfVxuICAgIHV0aWwuYXNzZXJ0KFxuICAgICAgICBsb2NzLmxlbmd0aCA9PT0gdGhpcy5yYW5rLFxuICAgICAgICAoKSA9PiBgVGhlIG51bWJlciBvZiBwcm92aWRlZCBjb29yZGluYXRlcyAoJHtsb2NzLmxlbmd0aH0pIG11c3QgYCArXG4gICAgICAgICAgICBgbWF0Y2ggdGhlIHJhbmsgKCR7dGhpcy5yYW5rfSlgKTtcblxuICAgIGNvbnN0IGluZGV4ID0gdGhpcy5sb2NUb0luZGV4KGxvY3MpO1xuICAgIHRoaXMudmFsdWVzW2luZGV4XSA9IHZhbHVlIGFzIG51bWJlcjtcbiAgfVxuXG4gIC8qKlxuICAgKiBSZXR1cm5zIHRoZSB2YWx1ZSBpbiB0aGUgYnVmZmVyIGF0IHRoZSBwcm92aWRlZCBsb2NhdGlvbi5cbiAgICpcbiAgICogQHBhcmFtIGxvY3MgVGhlIGxvY2F0aW9uIGluZGljZXMuXG4gICAqXG4gICAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NyZWF0aW9uJ31cbiAgICovXG4gIGdldCguLi5sb2NzOiBudW1iZXJbXSk6IFNpbmdsZVZhbHVlTWFwW0RdIHtcbiAgICBpZiAobG9jcy5sZW5ndGggPT09IDApIHtcbiAgICAgIGxvY3MgPSBbMF07XG4gICAgfVxuICAgIGxldCBpID0gMDtcbiAgICBmb3IgKGNvbnN0IGxvYyBvZiBsb2NzKSB7XG4gICAgICBpZiAobG9jIDwgMCB8fCBsb2MgPj0gdGhpcy5zaGFwZVtpXSkge1xuICAgICAgICBjb25zdCBtc2cgPSBgUmVxdWVzdGVkIG91dCBvZiByYW5nZSBlbGVtZW50IGF0ICR7bG9jc30uIGAgK1xuICAgICAgICAgICAgYCAgQnVmZmVyIHNoYXBlPSR7dGhpcy5zaGFwZX1gO1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IobXNnKTtcbiAgICAgIH1cbiAgICAgIGkrKztcbiAgICB9XG4gICAgbGV0IGluZGV4ID0gbG9jc1tsb2NzLmxlbmd0aCAtIDFdO1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgbG9jcy5sZW5ndGggLSAxOyArK2kpIHtcbiAgICAgIGluZGV4ICs9IHRoaXMuc3RyaWRlc1tpXSAqIGxvY3NbaV07XG4gICAgfVxuICAgIHJldHVybiB0aGlzLnZhbHVlc1tpbmRleF0gYXMgU2luZ2xlVmFsdWVNYXBbRF07XG4gIH1cblxuICBsb2NUb0luZGV4KGxvY3M6IG51bWJlcltdKTogbnVtYmVyIHtcbiAgICBpZiAodGhpcy5yYW5rID09PSAwKSB7XG4gICAgICByZXR1cm4gMDtcbiAgICB9IGVsc2UgaWYgKHRoaXMucmFuayA9PT0gMSkge1xuICAgICAgcmV0dXJuIGxvY3NbMF07XG4gICAgfVxuICAgIGxldCBpbmRleCA9IGxvY3NbbG9jcy5sZW5ndGggLSAxXTtcbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IGxvY3MubGVuZ3RoIC0gMTsgKytpKSB7XG4gICAgICBpbmRleCArPSB0aGlzLnN0cmlkZXNbaV0gKiBsb2NzW2ldO1xuICAgIH1cbiAgICByZXR1cm4gaW5kZXg7XG4gIH1cblxuICBpbmRleFRvTG9jKGluZGV4OiBudW1iZXIpOiBudW1iZXJbXSB7XG4gICAgaWYgKHRoaXMucmFuayA9PT0gMCkge1xuICAgICAgcmV0dXJuIFtdO1xuICAgIH0gZWxzZSBpZiAodGhpcy5yYW5rID09PSAxKSB7XG4gICAgICByZXR1cm4gW2luZGV4XTtcbiAgICB9XG4gICAgY29uc3QgbG9jczogbnVtYmVyW10gPSBuZXcgQXJyYXkodGhpcy5zaGFwZS5sZW5ndGgpO1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgbG9jcy5sZW5ndGggLSAxOyArK2kpIHtcbiAgICAgIGxvY3NbaV0gPSBNYXRoLmZsb29yKGluZGV4IC8gdGhpcy5zdHJpZGVzW2ldKTtcbiAgICAgIGluZGV4IC09IGxvY3NbaV0gKiB0aGlzLnN0cmlkZXNbaV07XG4gICAgfVxuICAgIGxvY3NbbG9jcy5sZW5ndGggLSAxXSA9IGluZGV4O1xuICAgIHJldHVybiBsb2NzO1xuICB9XG5cbiAgZ2V0IHJhbmsoKSB7XG4gICAgcmV0dXJuIHRoaXMuc2hhcGUubGVuZ3RoO1xuICB9XG5cbiAgLyoqXG4gICAqIENyZWF0ZXMgYW4gaW1tdXRhYmxlIGB0Zi5UZW5zb3JgIG9iamVjdCBmcm9tIHRoZSBidWZmZXIuXG4gICAqXG4gICAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NyZWF0aW9uJ31cbiAgICovXG4gIHRvVGVuc29yKCk6IFRlbnNvcjxSPiB7XG4gICAgcmV0dXJuIHRyYWNrZXJGbigpLm1ha2VUZW5zb3IodGhpcy52YWx1ZXMsIHRoaXMuc2hhcGUsIHRoaXMuZHR5cGUpIGFzXG4gICAgICAgIFRlbnNvcjxSPjtcbiAgfVxufVxuXG5leHBvcnQgaW50ZXJmYWNlIERhdGFUb0dQVVdlYkdMT3B0aW9uIHtcbiAgY3VzdG9tVGV4U2hhcGU/OiBbbnVtYmVyLCBudW1iZXJdO1xufVxuXG5leHBvcnQgdHlwZSBEYXRhVG9HUFVPcHRpb25zID0gRGF0YVRvR1BVV2ViR0xPcHRpb247XG5cbmV4cG9ydCBpbnRlcmZhY2UgR1BVRGF0YSB7XG4gIHRlbnNvclJlZjogVGVuc29yO1xuICB0ZXh0dXJlPzogV2ViR0xUZXh0dXJlO1xuICBidWZmZXI/OiBHUFVCdWZmZXI7XG4gIHRleFNoYXBlPzogW251bWJlciwgbnVtYmVyXTtcbn1cblxuZXhwb3J0IGludGVyZmFjZSBUZW5zb3JUcmFja2VyIHtcbiAgbWFrZVRlbnNvcihcbiAgICAgIHZhbHVlczogRGF0YVZhbHVlcywgc2hhcGU6IG51bWJlcltdLCBkdHlwZTogRGF0YVR5cGUsXG4gICAgICBiYWNrZW5kPzogQmFja2VuZCk6IFRlbnNvcjtcbiAgbWFrZVZhcmlhYmxlKFxuICAgICAgaW5pdGlhbFZhbHVlOiBUZW5zb3IsIHRyYWluYWJsZT86IGJvb2xlYW4sIG5hbWU/OiBzdHJpbmcsXG4gICAgICBkdHlwZT86IERhdGFUeXBlKTogVmFyaWFibGU7XG4gIGluY1JlZihhOiBUZW5zb3IsIGJhY2tlbmQ6IEJhY2tlbmQpOiB2b2lkO1xuICBkaXNwb3NlVGVuc29yKHQ6IFRlbnNvcik6IHZvaWQ7XG4gIGRpc3Bvc2VWYXJpYWJsZSh2OiBWYXJpYWJsZSk6IHZvaWQ7XG4gIHJlYWQoZGF0YUlkOiBEYXRhSWQpOiBQcm9taXNlPEJhY2tlbmRWYWx1ZXM+O1xuICByZWFkU3luYyhkYXRhSWQ6IERhdGFJZCk6IEJhY2tlbmRWYWx1ZXM7XG4gIHJlYWRUb0dQVShkYXRhSWQ6IERhdGFJZCwgb3B0aW9ucz86IERhdGFUb0dQVU9wdGlvbnMpOiBHUFVEYXRhO1xufVxuXG4vKipcbiAqIFRoZSBUZW5zb3IgY2xhc3MgY2FsbHMgaW50byB0aGlzIGhhbmRsZXIgdG8gZGVsZWdhdGUgY2hhaW5pbmcgb3BlcmF0aW9ucy5cbiAqL1xuZXhwb3J0IGludGVyZmFjZSBPcEhhbmRsZXIge1xuICBjYXN0PFQgZXh0ZW5kcyBUZW5zb3I+KHg6IFQsIGR0eXBlOiBEYXRhVHlwZSk6IFQ7XG4gIGJ1ZmZlcjxSIGV4dGVuZHMgUmFuaywgRCBleHRlbmRzIERhdGFUeXBlPihcbiAgICAgIHNoYXBlOiBTaGFwZU1hcFtSXSwgZHR5cGU6IEQsXG4gICAgICB2YWx1ZXM/OiBEYXRhVHlwZU1hcFtEXSk6IFRlbnNvckJ1ZmZlcjxSLCBEPjtcbiAgcHJpbnQ8VCBleHRlbmRzIFRlbnNvcj4oeDogVCwgdmVyYm9zZTogYm9vbGVhbik6IHZvaWQ7XG4gIGNsb25lPFQgZXh0ZW5kcyBUZW5zb3I+KHg6IFQpOiBUO1xuICAvLyBUT0RPKHlhc3NvZ2JhKSBicmluZyByZXNoYXBlIGJhY2s/XG59XG5cbi8vIEZvciB0cmFja2luZyB0ZW5zb3IgY3JlYXRpb24gYW5kIGRpc3Bvc2FsLlxubGV0IHRyYWNrZXJGbjogKCkgPT4gVGVuc29yVHJhY2tlciA9IG51bGw7XG4vLyBVc2VkIGJ5IGNoYWluaW5nIG1ldGhvZHMgdG8gY2FsbCBpbnRvIG9wcy5cbmxldCBvcEhhbmRsZXI6IE9wSGFuZGxlciA9IG51bGw7XG4vLyBVc2VkIHRvIHdhcm4gYWJvdXQgZGVwcmVjYXRlZCBtZXRob2RzLlxubGV0IGRlcHJlY2F0aW9uV2FybmluZ0ZuOiAobXNnOiBzdHJpbmcpID0+IHZvaWQgPSBudWxsO1xuLy8gVGhpcyBoZXJlIHNvIHRoYXQgd2UgY2FuIHVzZSB0aGlzIG1ldGhvZCBvbiBkZXYgYnJhbmNoZXMgYW5kIGtlZXAgdGhlXG4vLyBmdW5jdGlvbmFsaXR5IGF0IG1hc3Rlci5cbi8vIHRzbGludDpkaXNhYmxlLW5leHQtbGluZTpuby11bnVzZWQtZXhwcmVzc2lvblxuW2RlcHJlY2F0aW9uV2FybmluZ0ZuXTtcblxuLyoqXG4gKiBBbiBleHRlcm5hbCBjb25zdW1lciBjYW4gcmVnaXN0ZXIgaXRzZWxmIGFzIHRoZSB0ZW5zb3IgdHJhY2tlci4gVGhpcyB3YXlcbiAqIHRoZSBUZW5zb3IgY2xhc3MgY2FuIG5vdGlmeSB0aGUgdHJhY2tlciBmb3IgZXZlcnkgdGVuc29yIGNyZWF0ZWQgYW5kXG4gKiBkaXNwb3NlZC5cbiAqL1xuZXhwb3J0IGZ1bmN0aW9uIHNldFRlbnNvclRyYWNrZXIoZm46ICgpID0+IFRlbnNvclRyYWNrZXIpIHtcbiAgdHJhY2tlckZuID0gZm47XG59XG5cbi8qKlxuICogQW4gZXh0ZXJuYWwgY29uc3VtZXIgY2FuIHJlZ2lzdGVyIGl0c2VsZiBhcyB0aGUgb3AgaGFuZGxlci4gVGhpcyB3YXkgdGhlXG4gKiBUZW5zb3IgY2xhc3MgY2FuIGhhdmUgY2hhaW5pbmcgbWV0aG9kcyB0aGF0IGNhbGwgaW50byBvcHMgdmlhIHRoZSBvcFxuICogaGFuZGxlci5cbiAqL1xuZXhwb3J0IGZ1bmN0aW9uIHNldE9wSGFuZGxlcihoYW5kbGVyOiBPcEhhbmRsZXIpIHtcbiAgb3BIYW5kbGVyID0gaGFuZGxlcjtcbn1cblxuLyoqXG4gKiBTZXRzIHRoZSBkZXByZWNhdGlvbiB3YXJuaW5nIGZ1bmN0aW9uIHRvIGJlIHVzZWQgYnkgdGhpcyBmaWxlLiBUaGlzIHdheSB0aGVcbiAqIFRlbnNvciBjbGFzcyBjYW4gYmUgYSBsZWFmIGJ1dCBzdGlsbCB1c2UgdGhlIGVudmlyb25tZW50LlxuICovXG5leHBvcnQgZnVuY3Rpb24gc2V0RGVwcmVjYXRpb25XYXJuaW5nRm4oZm46IChtc2c6IHN0cmluZykgPT4gdm9pZCkge1xuICBkZXByZWNhdGlvbldhcm5pbmdGbiA9IGZuO1xufVxuXG4vLyBEZWNsYXJlIHRoaXMgbmFtZXNwYWNlIHRvIG1ha2UgVGVuc29yIGNsYXNzIGF1Z21lbnRhdGlvbiB3b3JrIGluIGdvb2dsZTMuXG5leHBvcnQgZGVjbGFyZSBuYW1lc3BhY2UgVGVuc29yIHt9XG4vKipcbiAqIEEgYHRmLlRlbnNvcmAgb2JqZWN0IHJlcHJlc2VudHMgYW4gaW1tdXRhYmxlLCBtdWx0aWRpbWVuc2lvbmFsIGFycmF5IG9mXG4gKiBudW1iZXJzIHRoYXQgaGFzIGEgc2hhcGUgYW5kIGEgZGF0YSB0eXBlLlxuICpcbiAqIEZvciBwZXJmb3JtYW5jZSByZWFzb25zLCBmdW5jdGlvbnMgdGhhdCBjcmVhdGUgdGVuc29ycyBkbyBub3QgbmVjZXNzYXJpbHlcbiAqIHBlcmZvcm0gYSBjb3B5IG9mIHRoZSBkYXRhIHBhc3NlZCB0byB0aGVtIChlLmcuIGlmIHRoZSBkYXRhIGlzIHBhc3NlZCBhcyBhXG4gKiBgRmxvYXQzMkFycmF5YCksIGFuZCBjaGFuZ2VzIHRvIHRoZSBkYXRhIHdpbGwgY2hhbmdlIHRoZSB0ZW5zb3IuIFRoaXMgaXMgbm90XG4gKiBhIGZlYXR1cmUgYW5kIGlzIG5vdCBzdXBwb3J0ZWQuIFRvIGF2b2lkIHRoaXMgYmVoYXZpb3IsIHVzZSB0aGUgdGVuc29yIGJlZm9yZVxuICogY2hhbmdpbmcgdGhlIGlucHV0IGRhdGEgb3IgY3JlYXRlIGEgY29weSB3aXRoIGBjb3B5ID0gdGYuYWRkKHlvdXJUZW5zb3IsIDApYC5cbiAqXG4gKiBTZWUgYHRmLnRlbnNvcmAgZm9yIGRldGFpbHMgb24gaG93IHRvIGNyZWF0ZSBhIGB0Zi5UZW5zb3JgLlxuICpcbiAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NsYXNzZXMnfVxuICovXG5leHBvcnQgY2xhc3MgVGVuc29yPFIgZXh0ZW5kcyBSYW5rID0gUmFuaz4gaW1wbGVtZW50cyBUZW5zb3JJbmZvIHtcbiAgLyoqIFVuaXF1ZSBpZCBvZiB0aGlzIHRlbnNvci4gKi9cbiAgcmVhZG9ubHkgaWQ6IG51bWJlcjtcbiAgLyoqXG4gICAqIElkIG9mIHRoZSBidWNrZXQgaG9sZGluZyB0aGUgZGF0YSBmb3IgdGhpcyB0ZW5zb3IuIE11bHRpcGxlIGFycmF5cyBjYW5cbiAgICogcG9pbnQgdG8gdGhlIHNhbWUgYnVja2V0IChlLmcuIHdoZW4gY2FsbGluZyBhcnJheS5yZXNoYXBlKCkpLlxuICAgKi9cbiAgZGF0YUlkOiBEYXRhSWQ7XG4gIC8qKiBUaGUgc2hhcGUgb2YgdGhlIHRlbnNvci4gKi9cbiAgcmVhZG9ubHkgc2hhcGU6IFNoYXBlTWFwW1JdO1xuICAvKiogTnVtYmVyIG9mIGVsZW1lbnRzIGluIHRoZSB0ZW5zb3IuICovXG4gIHJlYWRvbmx5IHNpemU6IG51bWJlcjtcbiAgLyoqIFRoZSBkYXRhIHR5cGUgZm9yIHRoZSBhcnJheS4gKi9cbiAgcmVhZG9ubHkgZHR5cGU6IERhdGFUeXBlO1xuICAvKiogVGhlIHJhbmsgdHlwZSBmb3IgdGhlIGFycmF5IChzZWUgYFJhbmtgIGVudW0pLiAqL1xuICByZWFkb25seSByYW5rVHlwZTogUjtcblxuICAvKiogV2hldGhlciB0aGlzIHRlbnNvciBoYXMgYmVlbiBnbG9iYWxseSBrZXB0LiAqL1xuICBrZXB0ID0gZmFsc2U7XG4gIC8qKiBUaGUgaWQgb2YgdGhlIHNjb3BlIHRoaXMgdGVuc29yIGlzIGJlaW5nIHRyYWNrZWQgaW4uICovXG4gIHNjb3BlSWQ6IG51bWJlcjtcblxuICAvKipcbiAgICogTnVtYmVyIG9mIGVsZW1lbnRzIHRvIHNraXAgaW4gZWFjaCBkaW1lbnNpb24gd2hlbiBpbmRleGluZy4gU2VlXG4gICAqIGh0dHBzOi8vZG9jcy5zY2lweS5vcmcvZG9jL251bXB5L3JlZmVyZW5jZS9nZW5lcmF0ZWQvXFxcbiAgICogbnVtcHkubmRhcnJheS5zdHJpZGVzLmh0bWxcbiAgICovXG4gIHJlYWRvbmx5IHN0cmlkZXM6IG51bWJlcltdO1xuXG4gIGNvbnN0cnVjdG9yKHNoYXBlOiBTaGFwZU1hcFtSXSwgZHR5cGU6IERhdGFUeXBlLCBkYXRhSWQ6IERhdGFJZCwgaWQ6IG51bWJlcikge1xuICAgIHRoaXMuc2hhcGUgPSBzaGFwZS5zbGljZSgpIGFzIFNoYXBlTWFwW1JdO1xuICAgIHRoaXMuZHR5cGUgPSBkdHlwZSB8fCAnZmxvYXQzMic7XG4gICAgdGhpcy5zaXplID0gdXRpbC5zaXplRnJvbVNoYXBlKHNoYXBlKTtcbiAgICB0aGlzLnN0cmlkZXMgPSBjb21wdXRlU3RyaWRlcyhzaGFwZSk7XG4gICAgdGhpcy5kYXRhSWQgPSBkYXRhSWQ7XG4gICAgdGhpcy5pZCA9IGlkO1xuICAgIHRoaXMucmFua1R5cGUgPSAodGhpcy5yYW5rIDwgNSA/IHRoaXMucmFuay50b1N0cmluZygpIDogJ2hpZ2hlcicpIGFzIFI7XG4gIH1cblxuICBnZXQgcmFuaygpOiBudW1iZXIge1xuICAgIHJldHVybiB0aGlzLnNoYXBlLmxlbmd0aDtcbiAgfVxuXG4gIC8qKlxuICAgKiBSZXR1cm5zIGEgcHJvbWlzZSBvZiBgdGYuVGVuc29yQnVmZmVyYCB0aGF0IGhvbGRzIHRoZSB1bmRlcmx5aW5nIGRhdGEuXG4gICAqXG4gICAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NsYXNzZXMnfVxuICAgKi9cbiAgYXN5bmMgYnVmZmVyPEQgZXh0ZW5kcyBEYXRhVHlwZSA9ICdmbG9hdDMyJz4oKTogUHJvbWlzZTxUZW5zb3JCdWZmZXI8UiwgRD4+IHtcbiAgICBjb25zdCB2YWxzID0gYXdhaXQgdGhpcy5kYXRhPEQ+KCk7XG4gICAgcmV0dXJuIG9wSGFuZGxlci5idWZmZXIodGhpcy5zaGFwZSwgdGhpcy5kdHlwZSBhcyBELCB2YWxzKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBSZXR1cm5zIGEgYHRmLlRlbnNvckJ1ZmZlcmAgdGhhdCBob2xkcyB0aGUgdW5kZXJseWluZyBkYXRhLlxuICAgKiBAZG9jIHtoZWFkaW5nOiAnVGVuc29ycycsIHN1YmhlYWRpbmc6ICdDbGFzc2VzJ31cbiAgICovXG4gIGJ1ZmZlclN5bmM8RCBleHRlbmRzIERhdGFUeXBlID0gJ2Zsb2F0MzInPigpOiBUZW5zb3JCdWZmZXI8UiwgRD4ge1xuICAgIHJldHVybiBvcEhhbmRsZXIuYnVmZmVyKHRoaXMuc2hhcGUsIHRoaXMuZHR5cGUgYXMgRCwgdGhpcy5kYXRhU3luYygpKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBSZXR1cm5zIHRoZSB0ZW5zb3IgZGF0YSBhcyBhIG5lc3RlZCBhcnJheS4gVGhlIHRyYW5zZmVyIG9mIGRhdGEgaXMgZG9uZVxuICAgKiBhc3luY2hyb25vdXNseS5cbiAgICpcbiAgICogQGRvYyB7aGVhZGluZzogJ1RlbnNvcnMnLCBzdWJoZWFkaW5nOiAnQ2xhc3Nlcyd9XG4gICAqL1xuICBhc3luYyBhcnJheSgpOiBQcm9taXNlPEFycmF5TWFwW1JdPiB7XG4gICAgY29uc3QgdmFscyA9IGF3YWl0IHRoaXMuZGF0YSgpO1xuICAgIHJldHVybiB0b05lc3RlZEFycmF5KHRoaXMuc2hhcGUsIHZhbHMsIHRoaXMuZHR5cGUgPT09ICdjb21wbGV4NjQnKSBhc1xuICAgICAgICBBcnJheU1hcFtSXTtcbiAgfVxuXG4gIC8qKlxuICAgKiBSZXR1cm5zIHRoZSB0ZW5zb3IgZGF0YSBhcyBhIG5lc3RlZCBhcnJheS4gVGhlIHRyYW5zZmVyIG9mIGRhdGEgaXMgZG9uZVxuICAgKiBzeW5jaHJvbm91c2x5LlxuICAgKlxuICAgKiBAZG9jIHtoZWFkaW5nOiAnVGVuc29ycycsIHN1YmhlYWRpbmc6ICdDbGFzc2VzJ31cbiAgICovXG4gIGFycmF5U3luYygpOiBBcnJheU1hcFtSXSB7XG4gICAgcmV0dXJuIHRvTmVzdGVkQXJyYXkoXG4gICAgICAgICAgICAgICB0aGlzLnNoYXBlLCB0aGlzLmRhdGFTeW5jKCksIHRoaXMuZHR5cGUgPT09ICdjb21wbGV4NjQnKSBhc1xuICAgICAgICBBcnJheU1hcFtSXTtcbiAgfVxuXG4gIC8qKlxuICAgKiBBc3luY2hyb25vdXNseSBkb3dubG9hZHMgdGhlIHZhbHVlcyBmcm9tIHRoZSBgdGYuVGVuc29yYC4gUmV0dXJucyBhXG4gICAqIHByb21pc2Ugb2YgYFR5cGVkQXJyYXlgIHRoYXQgcmVzb2x2ZXMgd2hlbiB0aGUgY29tcHV0YXRpb24gaGFzIGZpbmlzaGVkLlxuICAgKlxuICAgKiBAZG9jIHtoZWFkaW5nOiAnVGVuc29ycycsIHN1YmhlYWRpbmc6ICdDbGFzc2VzJ31cbiAgICovXG4gIGFzeW5jIGRhdGE8RCBleHRlbmRzIERhdGFUeXBlID0gTnVtZXJpY0RhdGFUeXBlPigpOiBQcm9taXNlPERhdGFUeXBlTWFwW0RdPiB7XG4gICAgdGhpcy50aHJvd0lmRGlzcG9zZWQoKTtcbiAgICBjb25zdCBkYXRhID0gdHJhY2tlckZuKCkucmVhZCh0aGlzLmRhdGFJZCk7XG4gICAgaWYgKHRoaXMuZHR5cGUgPT09ICdzdHJpbmcnKSB7XG4gICAgICBjb25zdCBieXRlcyA9IGF3YWl0IGRhdGEgYXMgVWludDhBcnJheVtdO1xuICAgICAgdHJ5IHtcbiAgICAgICAgcmV0dXJuIGJ5dGVzLm1hcChiID0+IHV0aWwuZGVjb2RlU3RyaW5nKGIpKSBhcyBEYXRhVHlwZU1hcFtEXTtcbiAgICAgIH0gY2F0Y2gge1xuICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgICAnRmFpbGVkIHRvIGRlY29kZSB0aGUgc3RyaW5nIGJ5dGVzIGludG8gdXRmLTguICcgK1xuICAgICAgICAgICAgJ1RvIGdldCB0aGUgb3JpZ2luYWwgYnl0ZXMsIGNhbGwgdGVuc29yLmJ5dGVzKCkuJyk7XG4gICAgICB9XG4gICAgfVxuICAgIHJldHVybiBkYXRhIGFzIFByb21pc2U8RGF0YVR5cGVNYXBbRF0+O1xuICB9XG5cbiAgLyoqXG4gICAqIENvcHkgdGhlIHRlbnNvcidzIGRhdGEgdG8gYSBuZXcgR1BVIHJlc291cmNlLiBDb21wYXJpbmcgdG8gdGhlIGBkYXRhU3luYygpYFxuICAgKiBhbmQgYGRhdGEoKWAsIHRoaXMgbWV0aG9kIHByZXZlbnRzIGRhdGEgZnJvbSBiZWluZyBkb3dubG9hZGVkIHRvIENQVS5cbiAgICpcbiAgICogRm9yIFdlYkdMIGJhY2tlbmQsIHRoZSBkYXRhIHdpbGwgYmUgc3RvcmVkIG9uIGEgZGVuc2VseSBwYWNrZWQgdGV4dHVyZS5cbiAgICogVGhpcyBtZWFucyB0aGF0IHRoZSB0ZXh0dXJlIHdpbGwgdXNlIHRoZSBSR0JBIGNoYW5uZWxzIHRvIHN0b3JlIHZhbHVlLlxuICAgKlxuICAgKiBGb3IgV2ViR1BVIGJhY2tlbmQsIHRoZSBkYXRhIHdpbGwgYmUgc3RvcmVkIG9uIGEgYnVmZmVyLiBUaGVyZSBpcyBub1xuICAgKiBwYXJhbWV0ZXIsIHNvIGNhbiBub3QgdXNlIGEgdXNlci1kZWZpbmVkIHNpemUgdG8gY3JlYXRlIHRoZSBidWZmZXIuXG4gICAqXG4gICAqIEBwYXJhbSBvcHRpb25zOlxuICAgKiAgICAgRm9yIFdlYkdMLFxuICAgKiAgICAgICAgIC0gY3VzdG9tVGV4U2hhcGU6IE9wdGlvbmFsLiBJZiBzZXQsIHdpbGwgdXNlIHRoZSB1c2VyIGRlZmluZWRcbiAgICogICAgIHRleHR1cmUgc2hhcGUgdG8gY3JlYXRlIHRoZSB0ZXh0dXJlLlxuICAgKlxuICAgKiBAcmV0dXJucyBGb3IgV2ViR0wgYmFja2VuZCwgYSBHUFVEYXRhIGNvbnRhaW5zIHRoZSBuZXcgdGV4dHVyZSBhbmRcbiAgICogICAgIGl0cyBpbmZvcm1hdGlvbi5cbiAgICogICAgIHtcbiAgICogICAgICAgIHRlbnNvclJlZjogVGhlIHRlbnNvciB0aGF0IGlzIGFzc29jaWF0ZWQgd2l0aCB0aGlzIHRleHR1cmUsXG4gICAqICAgICAgICB0ZXh0dXJlOiBXZWJHTFRleHR1cmUsXG4gICAqICAgICAgICB0ZXhTaGFwZTogW251bWJlciwgbnVtYmVyXSAvLyBbaGVpZ2h0LCB3aWR0aF1cbiAgICogICAgIH1cbiAgICpcbiAgICogICAgIEZvciBXZWJHUFUgYmFja2VuZCwgYSBHUFVEYXRhIGNvbnRhaW5zIHRoZSBuZXcgYnVmZmVyLlxuICAgKiAgICAge1xuICAgKiAgICAgICAgdGVuc29yUmVmOiBUaGUgdGVuc29yIHRoYXQgaXMgYXNzb2NpYXRlZCB3aXRoIHRoaXMgYnVmZmVyLFxuICAgKiAgICAgICAgYnVmZmVyOiBHUFVCdWZmZXIsXG4gICAqICAgICB9XG4gICAqXG4gICAqICAgICBSZW1lbWJlciB0byBkaXNwb3NlIHRoZSBHUFVEYXRhIGFmdGVyIGl0IGlzIHVzZWQgYnlcbiAgICogICAgIGByZXMudGVuc29yUmVmLmRpc3Bvc2UoKWAuXG4gICAqXG4gICAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NsYXNzZXMnfVxuICAgKi9cbiAgZGF0YVRvR1BVKG9wdGlvbnM/OiBEYXRhVG9HUFVPcHRpb25zKTogR1BVRGF0YSB7XG4gICAgdGhpcy50aHJvd0lmRGlzcG9zZWQoKTtcbiAgICByZXR1cm4gdHJhY2tlckZuKCkucmVhZFRvR1BVKHRoaXMuZGF0YUlkLCBvcHRpb25zKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBTeW5jaHJvbm91c2x5IGRvd25sb2FkcyB0aGUgdmFsdWVzIGZyb20gdGhlIGB0Zi5UZW5zb3JgLiBUaGlzIGJsb2NrcyB0aGVcbiAgICogVUkgdGhyZWFkIHVudGlsIHRoZSB2YWx1ZXMgYXJlIHJlYWR5LCB3aGljaCBjYW4gY2F1c2UgcGVyZm9ybWFuY2UgaXNzdWVzLlxuICAgKlxuICAgKiBAZG9jIHtoZWFkaW5nOiAnVGVuc29ycycsIHN1YmhlYWRpbmc6ICdDbGFzc2VzJ31cbiAgICovXG4gIGRhdGFTeW5jPEQgZXh0ZW5kcyBEYXRhVHlwZSA9IE51bWVyaWNEYXRhVHlwZT4oKTogRGF0YVR5cGVNYXBbRF0ge1xuICAgIHRoaXMudGhyb3dJZkRpc3Bvc2VkKCk7XG4gICAgY29uc3QgZGF0YSA9IHRyYWNrZXJGbigpLnJlYWRTeW5jKHRoaXMuZGF0YUlkKTtcbiAgICBpZiAodGhpcy5kdHlwZSA9PT0gJ3N0cmluZycpIHtcbiAgICAgIHRyeSB7XG4gICAgICAgIHJldHVybiAoZGF0YSBhcyBVaW50OEFycmF5W10pLm1hcChiID0+IHV0aWwuZGVjb2RlU3RyaW5nKGIpKSBhc1xuICAgICAgICAgICAgRGF0YVR5cGVNYXBbRF07XG4gICAgICB9IGNhdGNoIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgICAgJ0ZhaWxlZCB0byBkZWNvZGUgdGhlIHN0cmluZyBieXRlcyBpbnRvIHV0Zi04LiAnICtcbiAgICAgICAgICAgICdUbyBnZXQgdGhlIG9yaWdpbmFsIGJ5dGVzLCBjYWxsIHRlbnNvci5ieXRlcygpLicpO1xuICAgICAgfVxuICAgIH1cbiAgICByZXR1cm4gZGF0YSBhcyBEYXRhVHlwZU1hcFtEXTtcbiAgfVxuXG4gIC8qKiBSZXR1cm5zIHRoZSB1bmRlcmx5aW5nIGJ5dGVzIG9mIHRoZSB0ZW5zb3IncyBkYXRhLiAqL1xuICBhc3luYyBieXRlcygpOiBQcm9taXNlPFVpbnQ4QXJyYXlbXXxVaW50OEFycmF5PiB7XG4gICAgdGhpcy50aHJvd0lmRGlzcG9zZWQoKTtcbiAgICBjb25zdCBkYXRhID0gYXdhaXQgdHJhY2tlckZuKCkucmVhZCh0aGlzLmRhdGFJZCk7XG4gICAgaWYgKHRoaXMuZHR5cGUgPT09ICdzdHJpbmcnKSB7XG4gICAgICByZXR1cm4gZGF0YSBhcyBVaW50OEFycmF5W107XG4gICAgfSBlbHNlIHtcbiAgICAgIHJldHVybiBuZXcgVWludDhBcnJheSgoZGF0YSBhcyBUeXBlZEFycmF5KS5idWZmZXIpO1xuICAgIH1cbiAgfVxuXG4gIC8qKlxuICAgKiBEaXNwb3NlcyBgdGYuVGVuc29yYCBmcm9tIG1lbW9yeS5cbiAgICpcbiAgICogQGRvYyB7aGVhZGluZzogJ1RlbnNvcnMnLCBzdWJoZWFkaW5nOiAnQ2xhc3Nlcyd9XG4gICAqL1xuICBkaXNwb3NlKCk6IHZvaWQge1xuICAgIGlmICh0aGlzLmlzRGlzcG9zZWQpIHtcbiAgICAgIHJldHVybjtcbiAgICB9XG4gICAgdHJhY2tlckZuKCkuZGlzcG9zZVRlbnNvcih0aGlzKTtcbiAgICB0aGlzLmlzRGlzcG9zZWRJbnRlcm5hbCA9IHRydWU7XG4gIH1cblxuICBwcm90ZWN0ZWQgaXNEaXNwb3NlZEludGVybmFsID0gZmFsc2U7XG4gIGdldCBpc0Rpc3Bvc2VkKCk6IGJvb2xlYW4ge1xuICAgIHJldHVybiB0aGlzLmlzRGlzcG9zZWRJbnRlcm5hbDtcbiAgfVxuXG4gIHRocm93SWZEaXNwb3NlZCgpIHtcbiAgICBpZiAodGhpcy5pc0Rpc3Bvc2VkKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoYFRlbnNvciBpcyBkaXNwb3NlZC5gKTtcbiAgICB9XG4gIH1cblxuICAvKipcbiAgICogUHJpbnRzIHRoZSBgdGYuVGVuc29yYC4gU2VlIGB0Zi5wcmludGAgZm9yIGRldGFpbHMuXG4gICAqXG4gICAqIEBwYXJhbSB2ZXJib3NlIFdoZXRoZXIgdG8gcHJpbnQgdmVyYm9zZSBpbmZvcm1hdGlvbiBhYm91dCB0aGUgdGVuc29yLFxuICAgKiAgICBpbmNsdWRpbmcgZHR5cGUgYW5kIHNpemUuXG4gICAqXG4gICAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NsYXNzZXMnfVxuICAgKi9cbiAgcHJpbnQodmVyYm9zZSA9IGZhbHNlKTogdm9pZCB7XG4gICAgcmV0dXJuIG9wSGFuZGxlci5wcmludCh0aGlzLCB2ZXJib3NlKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBSZXR1cm5zIGEgY29weSBvZiB0aGUgdGVuc29yLiBTZWUgYHRmLmNsb25lYCBmb3IgZGV0YWlscy5cbiAgICogQGRvYyB7aGVhZGluZzogJ1RlbnNvcnMnLCBzdWJoZWFkaW5nOiAnQ2xhc3Nlcyd9XG4gICAqL1xuICBjbG9uZTxUIGV4dGVuZHMgVGVuc29yPih0aGlzOiBUKTogVCB7XG4gICAgdGhpcy50aHJvd0lmRGlzcG9zZWQoKTtcbiAgICByZXR1cm4gb3BIYW5kbGVyLmNsb25lKHRoaXMpO1xuICB9XG5cbiAgLyoqXG4gICAqIFJldHVybnMgYSBodW1hbi1yZWFkYWJsZSBkZXNjcmlwdGlvbiBvZiB0aGUgdGVuc29yLiBVc2VmdWwgZm9yIGxvZ2dpbmcuXG4gICAqXG4gICAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NsYXNzZXMnfVxuICAgKi9cbiAgdG9TdHJpbmcodmVyYm9zZSA9IGZhbHNlKTogc3RyaW5nIHtcbiAgICBjb25zdCB2YWxzID0gdGhpcy5kYXRhU3luYygpO1xuICAgIHJldHVybiB0ZW5zb3JUb1N0cmluZyh2YWxzLCB0aGlzLnNoYXBlLCB0aGlzLmR0eXBlLCB2ZXJib3NlKTtcbiAgfVxuXG4gIGNhc3Q8VCBleHRlbmRzIHRoaXM+KGR0eXBlOiBEYXRhVHlwZSk6IFQge1xuICAgIHRoaXMudGhyb3dJZkRpc3Bvc2VkKCk7XG4gICAgcmV0dXJuIG9wSGFuZGxlci5jYXN0KHRoaXMgYXMgVCwgZHR5cGUpO1xuICB9XG4gIHZhcmlhYmxlKHRyYWluYWJsZSA9IHRydWUsIG5hbWU/OiBzdHJpbmcsIGR0eXBlPzogRGF0YVR5cGUpOiBWYXJpYWJsZTxSPiB7XG4gICAgdGhpcy50aHJvd0lmRGlzcG9zZWQoKTtcbiAgICByZXR1cm4gdHJhY2tlckZuKCkubWFrZVZhcmlhYmxlKHRoaXMsIHRyYWluYWJsZSwgbmFtZSwgZHR5cGUpIGFzXG4gICAgICAgIFZhcmlhYmxlPFI+O1xuICB9XG59XG5cbk9iamVjdC5kZWZpbmVQcm9wZXJ0eShUZW5zb3IsIFN5bWJvbC5oYXNJbnN0YW5jZSwge1xuICB2YWx1ZTogKGluc3RhbmNlOiBUZW5zb3IpID0+IHtcbiAgICAvLyBJbXBsZW1lbnRhdGlvbiBub3RlOiB3ZSBzaG91bGQgdXNlIHByb3BlcnRpZXMgb2YgdGhlIG9iamVjdCB0aGF0IHdpbGwgYmVcbiAgICAvLyBkZWZpbmVkIGJlZm9yZSB0aGUgY29uc3RydWN0b3IgYm9keSBoYXMgZmluaXNoZWQgZXhlY3V0aW5nIChtZXRob2RzKS5cbiAgICAvLyBUaGlzIGlzIGJlY2F1c2Ugd2hlbiB0aGlzIGNvZGUgaXMgdHJhbnNwaWxlZCBieSBiYWJlbCwgYmFiZWwgd2lsbCBjYWxsXG4gICAgLy8gY2xhc3NDYWxsQ2hlY2sgYmVmb3JlIHRoZSBjb25zdHJ1Y3RvciBib2R5IGlzIHJ1bi5cbiAgICAvLyBTZWUgaHR0cHM6Ly9naXRodWIuY29tL3RlbnNvcmZsb3cvdGZqcy9pc3N1ZXMvMzM4NCBmb3IgYmFja3N0b3J5LlxuICAgIHJldHVybiAhIWluc3RhbmNlICYmIGluc3RhbmNlLmRhdGEgIT0gbnVsbCAmJiBpbnN0YW5jZS5kYXRhU3luYyAhPSBudWxsICYmXG4gICAgICAgIGluc3RhbmNlLnRocm93SWZEaXNwb3NlZCAhPSBudWxsO1xuICB9XG59KTtcblxuZXhwb3J0IGZ1bmN0aW9uIGdldEdsb2JhbFRlbnNvckNsYXNzKCkge1xuICAvLyBVc2UgZ2V0R2xvYmFsIHNvIHRoYXQgd2UgY2FuIGF1Z21lbnQgdGhlIFRlbnNvciBjbGFzcyBhY3Jvc3MgcGFja2FnZVxuICAvLyBib3VuZGFyaWVzIGJlY2FzZSB0aGUgbm9kZSByZXNvbHV0aW9uIGFsZyBtYXkgcmVzdWx0IGluIGRpZmZlcmVudCBtb2R1bGVzXG4gIC8vIGJlaW5nIHJldHVybmVkIGZvciB0aGlzIGZpbGUgZGVwZW5kaW5nIG9uIHRoZSBwYXRoIHRoZXkgYXJlIGxvYWRlZCBmcm9tLlxuICByZXR1cm4gZ2V0R2xvYmFsKCdUZW5zb3InLCAoKSA9PiB7XG4gICAgcmV0dXJuIFRlbnNvcjtcbiAgfSk7XG59XG5cbi8vIEdsb2JhbCBzaWRlIGVmZmVjdC4gQ2FjaGUgZ2xvYmFsIHJlZmVyZW5jZSB0byBUZW5zb3IgY2xhc3NcbmdldEdsb2JhbFRlbnNvckNsYXNzKCk7XG5cbmV4cG9ydCBpbnRlcmZhY2UgTnVtZXJpY1RlbnNvcjxSIGV4dGVuZHMgUmFuayA9IFJhbms+IGV4dGVuZHMgVGVuc29yPFI+IHtcbiAgZHR5cGU6IE51bWVyaWNEYXRhVHlwZTtcbiAgZGF0YVN5bmM8RCBleHRlbmRzIERhdGFUeXBlID0gTnVtZXJpY0RhdGFUeXBlPigpOiBEYXRhVHlwZU1hcFtEXTtcbiAgZGF0YTxEIGV4dGVuZHMgRGF0YVR5cGUgPSBOdW1lcmljRGF0YVR5cGU+KCk6IFByb21pc2U8RGF0YVR5cGVNYXBbRF0+O1xuICBkYXRhVG9HUFUob3B0aW9ucz86IERhdGFUb0dQVU9wdGlvbnMpOiBHUFVEYXRhO1xufVxuXG5leHBvcnQgaW50ZXJmYWNlIFN0cmluZ1RlbnNvcjxSIGV4dGVuZHMgUmFuayA9IFJhbms+IGV4dGVuZHMgVGVuc29yPFI+IHtcbiAgZHR5cGU6ICdzdHJpbmcnO1xuICBkYXRhU3luYzxEIGV4dGVuZHMgRGF0YVR5cGUgPSAnc3RyaW5nJz4oKTogRGF0YVR5cGVNYXBbRF07XG4gIGRhdGE8RCBleHRlbmRzIERhdGFUeXBlID0gJ3N0cmluZyc+KCk6IFByb21pc2U8RGF0YVR5cGVNYXBbRF0+O1xufVxuXG4vKiogQGRvY2xpbmsgVGVuc29yICovXG5leHBvcnQgdHlwZSBTY2FsYXIgPSBUZW5zb3I8UmFuay5SMD47XG4vKiogQGRvY2xpbmsgVGVuc29yICovXG5leHBvcnQgdHlwZSBUZW5zb3IxRCA9IFRlbnNvcjxSYW5rLlIxPjtcbi8qKiBAZG9jbGluayBUZW5zb3IgKi9cbmV4cG9ydCB0eXBlIFRlbnNvcjJEID0gVGVuc29yPFJhbmsuUjI+O1xuLyoqIEBkb2NsaW5rIFRlbnNvciAqL1xuZXhwb3J0IHR5cGUgVGVuc29yM0QgPSBUZW5zb3I8UmFuay5SMz47XG4vKiogQGRvY2xpbmsgVGVuc29yICovXG5leHBvcnQgdHlwZSBUZW5zb3I0RCA9IFRlbnNvcjxSYW5rLlI0Pjtcbi8qKiBAZG9jbGluayBUZW5zb3IgKi9cbmV4cG9ydCB0eXBlIFRlbnNvcjVEID0gVGVuc29yPFJhbmsuUjU+O1xuLyoqIEBkb2NsaW5rIFRlbnNvciAqL1xuZXhwb3J0IHR5cGUgVGVuc29yNkQgPSBUZW5zb3I8UmFuay5SNj47XG5cbi8qKlxuICogQSBtdXRhYmxlIGB0Zi5UZW5zb3JgLCB1c2VmdWwgZm9yIHBlcnNpc3Rpbmcgc3RhdGUsIGUuZy4gZm9yIHRyYWluaW5nLlxuICpcbiAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NsYXNzZXMnfVxuICovXG5leHBvcnQgY2xhc3MgVmFyaWFibGU8UiBleHRlbmRzIFJhbmsgPSBSYW5rPiBleHRlbmRzIFRlbnNvcjxSPiB7XG4gIG5hbWU6IHN0cmluZztcblxuICBjb25zdHJ1Y3RvcihcbiAgICAgIGluaXRpYWxWYWx1ZTogVGVuc29yPFI+LCBwdWJsaWMgdHJhaW5hYmxlOiBib29sZWFuLCBuYW1lOiBzdHJpbmcsXG4gICAgICB0ZW5zb3JJZDogbnVtYmVyKSB7XG4gICAgc3VwZXIoXG4gICAgICAgIGluaXRpYWxWYWx1ZS5zaGFwZSwgaW5pdGlhbFZhbHVlLmR0eXBlLCBpbml0aWFsVmFsdWUuZGF0YUlkLCB0ZW5zb3JJZCk7XG4gICAgdGhpcy5uYW1lID0gbmFtZTtcbiAgfVxuXG4gIC8qKlxuICAgKiBBc3NpZ24gYSBuZXcgYHRmLlRlbnNvcmAgdG8gdGhpcyB2YXJpYWJsZS4gVGhlIG5ldyBgdGYuVGVuc29yYCBtdXN0IGhhdmVcbiAgICogdGhlIHNhbWUgc2hhcGUgYW5kIGR0eXBlIGFzIHRoZSBvbGQgYHRmLlRlbnNvcmAuXG4gICAqXG4gICAqIEBwYXJhbSBuZXdWYWx1ZSBOZXcgdGVuc29yIHRvIGJlIGFzc2lnbmVkIHRvIHRoaXMgdmFyaWFibGUuXG4gICAqXG4gICAqIEBkb2Mge2hlYWRpbmc6ICdUZW5zb3JzJywgc3ViaGVhZGluZzogJ0NsYXNzZXMnfVxuICAgKi9cbiAgYXNzaWduKG5ld1ZhbHVlOiBUZW5zb3I8Uj4pOiB2b2lkIHtcbiAgICBpZiAobmV3VmFsdWUuZHR5cGUgIT09IHRoaXMuZHR5cGUpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICBgZHR5cGUgb2YgdGhlIG5ldyB2YWx1ZSAoJHtuZXdWYWx1ZS5kdHlwZX0pIGFuZCBgICtcbiAgICAgICAgICBgcHJldmlvdXMgdmFsdWUgKCR7dGhpcy5kdHlwZX0pIG11c3QgbWF0Y2hgKTtcbiAgICB9XG4gICAgaWYgKCF1dGlsLmFycmF5c0VxdWFsKG5ld1ZhbHVlLnNoYXBlLCB0aGlzLnNoYXBlKSkge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgIGBzaGFwZSBvZiB0aGUgbmV3IHZhbHVlICgke25ld1ZhbHVlLnNoYXBlfSkgYW5kIGAgK1xuICAgICAgICAgIGBwcmV2aW91cyB2YWx1ZSAoJHt0aGlzLnNoYXBlfSkgbXVzdCBtYXRjaGApO1xuICAgIH1cbiAgICB0cmFja2VyRm4oKS5kaXNwb3NlVGVuc29yKHRoaXMpO1xuICAgIHRoaXMuZGF0YUlkID0gbmV3VmFsdWUuZGF0YUlkO1xuICAgIHRyYWNrZXJGbigpLmluY1JlZih0aGlzLCBudWxsIC8qIGJhY2tlbmQgKi8pO1xuICB9XG5cbiAgb3ZlcnJpZGUgZGlzcG9zZSgpOiB2b2lkIHtcbiAgICB0cmFja2VyRm4oKS5kaXNwb3NlVmFyaWFibGUodGhpcyk7XG4gICAgdGhpcy5pc0Rpc3Bvc2VkSW50ZXJuYWwgPSB0cnVlO1xuICB9XG59XG5cbk9iamVjdC5kZWZpbmVQcm9wZXJ0eShWYXJpYWJsZSwgU3ltYm9sLmhhc0luc3RhbmNlLCB7XG4gIHZhbHVlOiAoaW5zdGFuY2U6IFZhcmlhYmxlKSA9PiB7XG4gICAgcmV0dXJuIGluc3RhbmNlIGluc3RhbmNlb2YgVGVuc29yICYmIGluc3RhbmNlLmFzc2lnbiAhPSBudWxsICYmXG4gICAgICAgIGluc3RhbmNlLmFzc2lnbiBpbnN0YW5jZW9mIEZ1bmN0aW9uO1xuICB9XG59KTtcbiJdfQ==