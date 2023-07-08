/**
 * @license
 * Copyright 2018 Google LLC. All Rights Reserved.
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
import * as tf from '../index';
import { ALL_ENVS, BROWSER_ENVS, describeWithFlags } from '../jasmine_util';
import { scalar, tensor1d, tensor2d } from '../ops/ops';
import { expectArraysEqual } from '../test_util';
import { expectArraysClose } from '../test_util';
import { encodeString } from '../util';
import { arrayBufferToBase64String, base64StringToArrayBuffer, basename, concatenateArrayBuffers, concatenateTypedArrays, stringByteLength, getFloat16Decoder } from './io_utils';
describe('concatenateTypedArrays', () => {
    it('Single float arrays', () => {
        const x = new Float32Array([1.1, 2.2, 3.3]);
        const buffer = concatenateTypedArrays([x]);
        expect(buffer.byteLength).toEqual(12);
        expect(new Float32Array(buffer, 0, 3)).toEqual(x);
    });
    it('Float arrays', () => {
        const x = new Float32Array([1.1, 2.2, 3.3]);
        const y = new Float32Array([-1.1, -2.2, -3.3]);
        const buffer = concatenateTypedArrays([x, y]);
        expect(buffer.byteLength).toEqual(24);
        expect(new Float32Array(buffer, 0, 3)).toEqual(x);
        expect(new Float32Array(buffer, 12, 3)).toEqual(y);
    });
    it('Single int32 arrays', () => {
        const x = new Int32Array([11, 22, 33]);
        const buffer = concatenateTypedArrays([x]);
        expect(buffer.byteLength).toEqual(12);
        expect(new Int32Array(buffer, 0, 3)).toEqual(x);
    });
    it('Int32 arrays', () => {
        const x = new Int32Array([11, 22, 33]);
        const y = new Int32Array([-11, -22, -33]);
        const buffer = concatenateTypedArrays([x, y]);
        expect(buffer.byteLength).toEqual(24);
        expect(new Int32Array(buffer, 0, 3)).toEqual(x);
        expect(new Int32Array(buffer, 12, 3)).toEqual(y);
    });
    it('Single uint8 arrays', () => {
        const x = new Uint8Array([11, 22, 33]);
        const buffer = concatenateTypedArrays([x]);
        expect(buffer.byteLength).toEqual(3);
        expect(new Uint8Array(buffer, 0, 3)).toEqual(x);
    });
    it('Uint8 arrays', () => {
        const x = new Uint8Array([11, 22, 33]);
        const y = new Uint8Array([111, 122, 133]);
        const buffer = concatenateTypedArrays([x, y]);
        expect(buffer.byteLength).toEqual(6);
        expect(new Uint8Array(buffer, 0, 3)).toEqual(x);
        expect(new Uint8Array(buffer, 3, 3)).toEqual(y);
    });
    it('Mixed Uint8, Int32 and Float32 arrays', () => {
        const x = new Uint8Array([0, 1, 1, 0]);
        const y = new Int32Array([10, 20, 30, 40]);
        const z = new Float32Array([-1.1, -2.2, -3.3, -4.4]);
        const buffer = concatenateTypedArrays([x, y, z]);
        expect(buffer.byteLength).toEqual(1 * 4 + 4 * 4 + 4 * 4);
        expect(new Uint8Array(buffer, 0, 4)).toEqual(x);
        expect(new Int32Array(buffer, 4, 4)).toEqual(y);
        expect(new Float32Array(buffer, 20, 4)).toEqual(z);
    });
    it('Concatenate Float32Arrays from SubArrays', () => {
        const x1 = new Float32Array([1.1, 2.2, 3.3]);
        const x2 = new Float32Array([-1.1, -2.2, -3.3]);
        const xConcatenated = concatenateTypedArrays([x1, x2]);
        const y1 = new Float32Array(xConcatenated, 0, 3);
        const y2 = new Float32Array(xConcatenated, 3 * 4, 3);
        // At this point, the buffer of y1 is longer than than the actual byte
        // length of y1, because of the way y1 is constructed. The same is true for
        // y2.
        expect(y1.buffer.byteLength).toEqual(6 * 4);
        expect(y2.buffer.byteLength).toEqual(6 * 4);
        const yConcatenated = concatenateTypedArrays([y1, y2]);
        expect(yConcatenated.byteLength).toEqual(6 * 4);
        expect(new Float32Array(yConcatenated, 0, 3)).toEqual(x1);
        expect(new Float32Array(yConcatenated, 3 * 4, 3)).toEqual(x2);
    });
    it('Concatenate Int32Array from SubArrays', () => {
        const x1 = new Int32Array([11, 22, 33]);
        const x2 = new Int32Array([-11, -22, -33]);
        const xConcatenated = concatenateTypedArrays([x1, x2]);
        const y1 = new Int32Array(xConcatenated, 0, 3);
        const y2 = new Int32Array(xConcatenated, 3 * 4, 3);
        // At this point, the buffer of y1 is longer than than the actual byte
        // length of y1, because of the way y1 is constructed. The same is true for
        // y2.
        expect(y1.buffer.byteLength).toEqual(6 * 4);
        expect(y2.buffer.byteLength).toEqual(6 * 4);
        const yConcatenated = concatenateTypedArrays([y1, y2]);
        expect(yConcatenated.byteLength).toEqual(6 * 4);
        expect(new Int32Array(yConcatenated, 0, 3)).toEqual(x1);
        expect(new Int32Array(yConcatenated, 3 * 4, 3)).toEqual(x2);
    });
    it('Concatenate Uint8Array from SubArrays', () => {
        const x1 = new Uint8Array([11, 22, 33]);
        const x2 = new Uint8Array([44, 55, 66]);
        const xConcatenated = concatenateTypedArrays([x1, x2]);
        const y1 = new Uint8Array(xConcatenated, 0, 3);
        const y2 = new Uint8Array(xConcatenated, 3, 3);
        // At this point, the buffer of y1 is longer than than the actual byte
        // length of y1, because of the way y1 is constructed. The same is true for
        // y2.
        expect(y1.buffer.byteLength).toEqual(6);
        expect(y2.buffer.byteLength).toEqual(6);
        const yConcatenated = concatenateTypedArrays([y1, y2]);
        expect(yConcatenated.byteLength).toEqual(6);
        expect(new Uint8Array(yConcatenated, 0, 3)).toEqual(x1);
        expect(new Uint8Array(yConcatenated, 3, 3)).toEqual(x2);
    });
    it('Concatenate mixed TypedArrays from SubArrays', () => {
        const x1 = new Uint8Array([11, 22, 33, 44]);
        const x2 = new Int32Array([-44, -55, -66]);
        const x3 = new Float32Array([1.1, 2.2, 3.3]);
        const xConcatenated = concatenateTypedArrays([x1, x2, x3]);
        const y1 = new Uint8Array(xConcatenated, 0, 4);
        const y2 = new Int32Array(xConcatenated, 4, 3);
        const y3 = new Float32Array(xConcatenated, 4 + 3 * 4, 3);
        // At this point, the buffer of y1 is longer than than the actual byte
        // length of y1, because of the way y1 is constructed. The same is true for
        // y2 and y3.
        expect(y1.buffer.byteLength).toEqual(4 + 3 * 4 + 3 * 4);
        expect(y2.buffer.byteLength).toEqual(4 + 3 * 4 + 3 * 4);
        expect(y3.buffer.byteLength).toEqual(4 + 3 * 4 + 3 * 4);
        const yConcatenated = concatenateTypedArrays([y1, y2, y3]);
        expect(yConcatenated.byteLength).toEqual(4 + 3 * 4 + 3 * 4);
        expect(new Uint8Array(yConcatenated, 0, 4)).toEqual(x1);
        expect(new Int32Array(yConcatenated, 4, 3)).toEqual(x2);
        expect(new Float32Array(yConcatenated, 4 + 3 * 4, 3)).toEqual(x3);
    });
    it('null and undefined inputs', () => {
        expect(() => concatenateTypedArrays(null)).toThrow();
        expect(() => concatenateTypedArrays(undefined)).toThrow();
    });
    it('empty input array', () => {
        expect(concatenateTypedArrays([]).byteLength).toEqual(0);
    });
    it('Unsupported dtype', () => {
        const x = new Int16Array([0, 1, 1, 0]);
        // tslint:disable-next-line:no-any
        expect(() => concatenateTypedArrays([x]))
            .toThrowError(/Unsupported TypedArray subtype: Int16Array/);
    });
});
describeWithFlags('encodeWeights', ALL_ENVS, () => {
    it('Float32 tensors as NamedTensorMap', async () => {
        const tensors = {
            x1: tensor2d([[10, 20], [30, 40]]),
            x2: scalar(42),
            x3: tensor1d([-1.3, -3.7, 1.3, 3.7]),
        };
        const dataAndSpecs = await tf.io.encodeWeights(tensors);
        const data = dataAndSpecs.data;
        const specs = dataAndSpecs.specs;
        expect(data.byteLength).toEqual(4 * (4 + 1 + 4));
        expect(new Float32Array(data, 0, 4)).toEqual(new Float32Array([
            10, 20, 30, 40
        ]));
        expect(new Float32Array(data, 16, 1)).toEqual(new Float32Array([42]));
        expect(new Float32Array(data, 20, 4)).toEqual(new Float32Array([
            -1.3, -3.7, 1.3, 3.7
        ]));
        expect(specs).toEqual([
            {
                name: 'x1',
                dtype: 'float32',
                shape: [2, 2],
            },
            {
                name: 'x2',
                dtype: 'float32',
                shape: [],
            },
            {
                name: 'x3',
                dtype: 'float32',
                shape: [4],
            }
        ]);
    });
    it('Float32 tensors as NamedTensor array', async () => {
        const tensors = [
            { name: 'x1234', tensor: tensor2d([[10, 20], [30, 40]]) }, {
                name: 'a42',
                tensor: scalar(42),
            },
            { name: 'b41', tensor: tensor1d([-1.3, -3.7, 1.3, 3.7]) }
        ];
        const dataAndSpecs = await tf.io.encodeWeights(tensors);
        const data = dataAndSpecs.data;
        const specs = dataAndSpecs.specs;
        expect(data.byteLength).toEqual(4 * (4 + 1 + 4));
        expect(new Float32Array(data, 0, 4)).toEqual(new Float32Array([
            10, 20, 30, 40
        ]));
        expect(new Float32Array(data, 16, 1)).toEqual(new Float32Array([42]));
        expect(new Float32Array(data, 20, 4)).toEqual(new Float32Array([
            -1.3, -3.7, 1.3, 3.7
        ]));
        expect(specs).toEqual([
            {
                name: 'x1234',
                dtype: 'float32',
                shape: [2, 2],
            },
            {
                name: 'a42',
                dtype: 'float32',
                shape: [],
            },
            {
                name: 'b41',
                dtype: 'float32',
                shape: [4],
            }
        ]);
    });
    it('Empty NamedTensor array', async () => {
        const tensors = [];
        const dataAndSpecs = await tf.io.encodeWeights(tensors);
        const data = dataAndSpecs.data;
        const specs = dataAndSpecs.specs;
        expect(data.byteLength).toEqual(0);
        expect(specs).toEqual([]);
    });
    it('Int32 tensors', async () => {
        const tensors = {
            x1: tensor2d([[10, 20], [30, 40]], [2, 2], 'int32'),
            x2: scalar(42, 'int32'),
            x3: tensor1d([-1, -3, -3, -7], 'int32'),
        };
        const dataAndSpecs = await tf.io.encodeWeights(tensors);
        const data = dataAndSpecs.data;
        const specs = dataAndSpecs.specs;
        expect(data.byteLength).toEqual(4 * (4 + 1 + 4));
        expect(new Int32Array(data, 0, 4)).toEqual(new Int32Array([
            10, 20, 30, 40
        ]));
        expect(new Int32Array(data, 16, 1)).toEqual(new Int32Array([42]));
        expect(new Int32Array(data, 20, 4)).toEqual(new Int32Array([
            -1, -3, -3, -7
        ]));
        expect(specs).toEqual([
            {
                name: 'x1',
                dtype: 'int32',
                shape: [2, 2],
            },
            {
                name: 'x2',
                dtype: 'int32',
                shape: [],
            },
            {
                name: 'x3',
                dtype: 'int32',
                shape: [4],
            }
        ]);
    });
    it('Bool tensors', async () => {
        const tensors = {
            x1: tensor2d([[true, false], [false, true]], [2, 2], 'bool'),
            x2: scalar(false, 'bool'),
            x3: tensor1d([false, true, true, false], 'bool'),
        };
        const dataAndSpecs = await tf.io.encodeWeights(tensors);
        const data = dataAndSpecs.data;
        const specs = dataAndSpecs.specs;
        expect(data.byteLength).toEqual(4 + 1 + 4);
        expect(new Uint8Array(data, 0, 4)).toEqual(new Uint8Array([1, 0, 0, 1]));
        expect(new Uint8Array(data, 4, 1)).toEqual(new Uint8Array([0]));
        expect(new Uint8Array(data, 5, 4)).toEqual(new Uint8Array([0, 1, 1, 0]));
        expect(specs).toEqual([
            {
                name: 'x1',
                dtype: 'bool',
                shape: [2, 2],
            },
            {
                name: 'x2',
                dtype: 'bool',
                shape: [],
            },
            {
                name: 'x3',
                dtype: 'bool',
                shape: [4],
            }
        ]);
    });
    it('Complex64 tensors', async () => {
        const tensors = {
            x1: tf.complex([1, 2], [1, 2]),
            x2: tf.complex(1, 2),
            x3: tf.complex([[1]], [[2]]),
        };
        const dataAndSpecs = await tf.io.encodeWeights(tensors);
        const data = dataAndSpecs.data;
        const specs = dataAndSpecs.specs;
        expect(data.byteLength).toEqual(8 * 4);
        expect(new Float32Array(data, 0, 4)).toEqual(new Float32Array([
            1, 1, 2, 2
        ]));
        expect(new Float32Array(data, 16, 2)).toEqual(new Float32Array([1, 2]));
        expect(new Float32Array(data, 24, 2)).toEqual(new Float32Array([1, 2]));
        expect(specs).toEqual([
            {
                name: 'x1',
                dtype: 'complex64',
                shape: [2],
            },
            {
                name: 'x2',
                dtype: 'complex64',
                shape: [],
            },
            {
                name: 'x3',
                dtype: 'complex64',
                shape: [1, 1],
            }
        ]);
    });
    it('String tensors', async () => {
        const tensors = {
            x1: tensor2d([['a', 'bc'], ['def', 'g']], [2, 2]),
            x2: scalar(''),
            x3: tensor1d(['здраво', 'поздрав']),
            x4: scalar('正常'),
            x5: scalar('hello') // Single string.
        };
        const dataAndSpecs = await tf.io.encodeWeights(tensors);
        const data = dataAndSpecs.data;
        const specs = dataAndSpecs.specs;
        const x1ByteLength = 7 + 4 * 4; // 7 ascii chars + 4 ints.
        const x2ByteLength = 4; // No chars + 1 int.
        const x3ByteLength = 13 * 2 + 2 * 4; // 13 cyrillic letters + 2 ints.
        const x4ByteLength = 6 + 1 * 4; // 2 east asian letters + 1 int.
        const x5ByteLength = 5 + 1 * 4; // 5 ascii chars + 1 int.
        expect(data.byteLength)
            .toEqual(x1ByteLength + x2ByteLength + x3ByteLength + x4ByteLength +
            x5ByteLength);
        // x1 'a'.
        expect(new Uint32Array(data, 0, 1)[0]).toBe(1);
        expect(new Uint8Array(data, 4, 1)).toEqual(encodeString('a'));
        // x1 'bc'.
        expect(new Uint32Array(data.slice(5, 9))[0]).toBe(2);
        expect(new Uint8Array(data, 9, 2)).toEqual(encodeString('bc'));
        // x1 'def'.
        expect(new Uint32Array(data.slice(11, 15))[0]).toBe(3);
        expect(new Uint8Array(data, 15, 3)).toEqual(encodeString('def'));
        // x1 'g'.
        expect(new Uint32Array(data.slice(18, 22))[0]).toBe(1);
        expect(new Uint8Array(data, 22, 1)).toEqual(encodeString('g'));
        // x2 is empty string.
        expect(new Uint32Array(data.slice(23, 27))[0]).toBe(0);
        // x3 'здраво'.
        expect(new Uint32Array(data.slice(27, 31))[0]).toBe(12);
        expect(new Uint8Array(data, 31, 12)).toEqual(encodeString('здраво'));
        // x3 'поздрав'.
        expect(new Uint32Array(data.slice(43, 47))[0]).toBe(14);
        expect(new Uint8Array(data, 47, 14)).toEqual(encodeString('поздрав'));
        // x4 '正常'.
        expect(new Uint32Array(data.slice(61, 65))[0]).toBe(6);
        expect(new Uint8Array(data, 65, 6)).toEqual(encodeString('正常'));
        // x5 'hello'.
        expect(new Uint32Array(data.slice(71, 75))[0]).toBe(5);
        expect(new Uint8Array(data, 75, 5)).toEqual(encodeString('hello'));
        expect(specs).toEqual([
            { name: 'x1', dtype: 'string', shape: [2, 2] },
            { name: 'x2', dtype: 'string', shape: [] },
            { name: 'x3', dtype: 'string', shape: [2] },
            { name: 'x4', dtype: 'string', shape: [] },
            { name: 'x5', dtype: 'string', shape: [] }
        ]);
    });
    it('Mixed dtype tensors', async () => {
        const tensors = {
            x1: tensor2d([[10, 20], [30, 40]], [2, 2], 'int32'),
            x2: scalar(13.37, 'float32'),
            x3: tensor1d([true, false, false, true], 'bool'),
            x4: tf.complex([1, 1], [2, 2])
        };
        const dataAndSpecs = await tf.io.encodeWeights(tensors);
        const data = dataAndSpecs.data;
        const specs = dataAndSpecs.specs;
        expect(data.byteLength).toEqual(4 * 4 + 4 * 1 + 1 * 4 + 4 * 4);
        expect(new Int32Array(data, 0, 4)).toEqual(new Int32Array([
            10, 20, 30, 40
        ]));
        expect(new Float32Array(data, 16, 1)).toEqual(new Float32Array([13.37]));
        expect(new Uint8Array(data, 20, 4)).toEqual(new Uint8Array([1, 0, 0, 1]));
        expect(new Float32Array(data, 24, 4)).toEqual(new Float32Array([
            1, 2, 1, 2
        ]));
        expect(specs).toEqual([
            {
                name: 'x1',
                dtype: 'int32',
                shape: [2, 2],
            },
            {
                name: 'x2',
                dtype: 'float32',
                shape: [],
            },
            {
                name: 'x3',
                dtype: 'bool',
                shape: [4],
            },
            {
                name: 'x4',
                dtype: 'complex64',
                shape: [2],
            }
        ]);
    });
});
describeWithFlags('decodeWeights', {}, () => {
    it('Mixed dtype tensors', async () => {
        const tensors = {
            x1: tensor2d([[10, 20], [30, 40]], [2, 2], 'int32'),
            x2: scalar(13.37, 'float32'),
            x3: tensor1d([true, false, false], 'bool'),
            x4: tensor2d([['здраво', 'a'], ['b', 'c']], [2, 2], 'string'),
            x5: tensor1d([''], 'string'),
            x6: scalar('hello'),
            y1: tensor2d([-10, -20, -30], [3, 1], 'float32'),
            y2: tf.complex([1, 1], [2, 2])
        };
        const dataAndSpecs = await tf.io.encodeWeights(tensors);
        const data = dataAndSpecs.data;
        const specs = dataAndSpecs.specs;
        const decoded = tf.io.decodeWeights(data, specs);
        expect(Object.keys(decoded).length).toEqual(8);
        expectArraysEqual(await decoded['x1'].data(), await tensors['x1'].data());
        expectArraysEqual(await decoded['x2'].data(), await tensors['x2'].data());
        expectArraysEqual(await decoded['x3'].data(), await tensors['x3'].data());
        expectArraysEqual(await decoded['x4'].data(), await tensors['x4'].data());
        expectArraysEqual(await decoded['x5'].data(), await tensors['x5'].data());
        expectArraysEqual(await decoded['x6'].data(), await tensors['x6'].data());
        expectArraysEqual(await decoded['y1'].data(), await tensors['y1'].data());
        expectArraysEqual(await decoded['y2'].data(), await tensors['y2'].data());
    });
    it('Unsupported dtype raises Error', () => {
        const buffer = new ArrayBuffer(4);
        // tslint:disable-next-line:no-any
        const specs = [
            {
                name: 'x',
                dtype: 'int16',
                shape: [],
            },
            { name: 'y', dtype: 'int16', shape: [] }
        ];
        expect(() => tf.io.decodeWeights(buffer, specs))
            .toThrowError(/Unsupported dtype in weight \'x\': int16/);
    });
    it('support quantization uint8 weights', async () => {
        const manifestSpecs = [
            {
                'name': 'weight0',
                'dtype': 'float32',
                'shape': [3],
                'quantization': { 'min': -1, 'scale': 0.1, 'dtype': 'uint8' }
            },
            {
                'name': 'weight1',
                'dtype': 'int32',
                'shape': [3],
                'quantization': { 'min': -1, 'scale': 0.1, 'dtype': 'uint8' }
            }
        ];
        const data = new Uint8Array([0, 48, 255, 0, 48, 255]);
        const decoded = tf.io.decodeWeights(data.buffer, manifestSpecs);
        const weight0 = decoded['weight0'];
        expectArraysClose(await weight0.data(), [-1, 3.8, 24.5]);
        expect(weight0.shape).toEqual([3]);
        expect(weight0.dtype).toEqual('float32');
        const weight1 = decoded['weight1'];
        expectArraysEqual(await weight1.data(), [-1, 4, 25]);
        expect(weight1.shape).toEqual([3]);
        expect(weight1.dtype).toEqual('int32');
    });
    it('support quantization uint16 weights', async () => {
        const manifestSpecs = [
            {
                'name': 'weight0',
                'dtype': 'float32',
                'shape': [3],
                'quantization': { 'min': -1, 'scale': 0.1, 'dtype': 'uint16' }
            },
            {
                'name': 'weight1',
                'dtype': 'int32',
                'shape': [3],
                'quantization': { 'min': -1, 'scale': 0.1, 'dtype': 'uint16' }
            }
        ];
        const data = new Uint16Array([0, 48, 255, 0, 48, 255]);
        const decoded = tf.io.decodeWeights(data.buffer, manifestSpecs);
        const weight0 = decoded['weight0'];
        expectArraysClose(await weight0.data(), [-1, 3.8, 24.5]);
        expect(weight0.shape).toEqual([3]);
        expect(weight0.dtype).toEqual('float32');
        const weight1 = decoded['weight1'];
        expectArraysEqual(await weight1.data(), [-1, 4, 25]);
        expect(weight1.shape).toEqual([3]);
        expect(weight1.dtype).toEqual('int32');
    });
    it('support quantization float16 weights', async () => {
        const manifestSpecs = [
            {
                name: 'weight0',
                dtype: 'float32',
                shape: [3],
                quantization: { dtype: 'float16' },
            },
        ];
        const data = new Uint16Array([13312, 14336, 14848]);
        const decoded = tf.io.decodeWeights(data.buffer, manifestSpecs);
        const weight0 = decoded['weight0'];
        expectArraysClose(await weight0.data(), [0.25, 0.5, 0.75]);
        expect(weight0.shape).toEqual([3]);
        expect(weight0.dtype).toEqual('float32');
    });
});
describe('stringByteLength', () => {
    it('ASCII only', () => {
        const str = '_Lorem ipsum 1337!';
        expect(stringByteLength(str)).toEqual(str.length);
    });
    it('Mixed narrow and wide chars', () => {
        const str = 'aЖ文1';
        expect(stringByteLength(str.slice(0, 1))).toEqual(1);
        expect(stringByteLength(str.slice(0, 2))).toEqual(3);
        expect(stringByteLength(str.slice(0, 3))).toEqual(6);
        expect(stringByteLength(str.slice(0, 4))).toEqual(7);
    });
});
describeWithFlags('arrayBufferToBase64String-base64StringToArrayBuffer', BROWSER_ENVS, () => {
    it('Round trip', () => {
        // Generate some semi-random binary data.
        const x = [];
        for (let k = 0; k < 2; ++k) {
            for (let i = 0; i < 254; ++i) {
                x.push(i + k);
            }
            for (let i = 254; i >= 0; --i) {
                x.push(i + k);
            }
        }
        const buffer = Uint8Array.from(x).buffer;
        const base64Str = arrayBufferToBase64String(buffer);
        const decoded = Array.from(new Uint8Array(base64StringToArrayBuffer(base64Str)));
        expect(decoded).toEqual(x);
    });
});
describe('concatenateArrayBuffers', () => {
    // TODO(mattSoulanille): Move these tests to CompositeArrayBuffer.join when
    // concatenateArrayBuffers is removed.
    it('Concatenate 3 non-empty ArrayBuffers', () => {
        const buffer1 = new Uint8Array([1, 2, 3]);
        const buffer2 = new Uint8Array([11, 22, 33, 44]);
        const buffer3 = new Uint8Array([111, 222, 100]);
        const out = concatenateArrayBuffers([buffer1.buffer, buffer2.buffer, buffer3.buffer]);
        expect(new Uint8Array(out)).toEqual(new Uint8Array([
            1, 2, 3, 11, 22, 33, 44, 111, 222, 100
        ]));
    });
    it('Concatenate non-empty and empty ArrayBuffers', () => {
        const buffer1 = new Uint8Array([1, 2, 3]);
        const buffer2 = new Uint8Array([11, 22, 33, 44]);
        const buffer3 = new Uint8Array([]);
        const buffer4 = new Uint8Array([150, 100, 50]);
        const out = concatenateArrayBuffers([buffer1.buffer, buffer2.buffer, buffer3.buffer, buffer4.buffer]);
        expect(new Uint8Array(out)).toEqual(new Uint8Array([
            1, 2, 3, 11, 22, 33, 44, 150, 100, 50
        ]));
    });
    it('A single ArrayBuffer', () => {
        const buffer1 = new Uint8Array([1, 3, 3, 7]);
        const out = concatenateArrayBuffers([buffer1.buffer]);
        expect(new Uint8Array(out)).toEqual(buffer1);
    });
    it('Zero ArrayBuffers', () => {
        expect(new Uint8Array(concatenateArrayBuffers([])))
            .toEqual(new Uint8Array([]));
    });
});
describe('basename', () => {
    it('Paths without slashes', () => {
        expect(basename('foo.txt')).toEqual('foo.txt');
        expect(basename('bar')).toEqual('bar');
    });
    it('Paths with slashes', () => {
        expect(basename('qux/foo.txt')).toEqual('foo.txt');
        expect(basename('qux/My Model.json')).toEqual('My Model.json');
        expect(basename('foo/bar/baz')).toEqual('baz');
        expect(basename('/foo/bar/baz')).toEqual('baz');
        expect(basename('foo/bar/baz/')).toEqual('baz');
        expect(basename('foo/bar/baz//')).toEqual('baz');
    });
});
describe('float16', () => {
    it('decodes NaN to float32 NaN', () => {
        const decoder = getFloat16Decoder();
        const float16NaN = 0x00007e00;
        const buffer = new Uint16Array([float16NaN]);
        const f32 = decoder(buffer);
        expect(f32).toEqual(new Float32Array([NaN]));
    });
    it('decodes ±Infinity to float32 ±Infinity', () => {
        const decoder = getFloat16Decoder();
        const positiveInfinity = 0x00007c00;
        const negativeInfinity = 0xfffffc00;
        const buffer = new Uint16Array([positiveInfinity, negativeInfinity]);
        const f32 = decoder(buffer);
        expect(f32).toEqual(new Float32Array([Infinity, -Infinity]));
    });
    it('decodes ±0 to float32 ±0', () => {
        const decoder = getFloat16Decoder();
        const positiveZero = 0x00000000;
        const negativeZero = 0xffff8000;
        const buffer = new Uint16Array([positiveZero, negativeZero]);
        const f32 = decoder(buffer);
        expect(f32).toEqual(new Float32Array([0.0, -0.0]));
    });
    it('decodes -Infinity on underflow', () => {
        const decoder = getFloat16Decoder();
        const minVal = 0xfffffbff;
        const buffer = new Uint16Array([minVal + 1]);
        const f32 = decoder(buffer);
        expect(f32).toEqual(new Float32Array([-Infinity]));
    });
    it('decodes +Infinity on overflow', () => {
        const decoder = getFloat16Decoder();
        const maxVal = 0x00007bff;
        const buffer = new Uint16Array([maxVal + 1]);
        const f32 = decoder(buffer);
        expect(f32).toEqual(new Float32Array([Infinity]));
    });
    it('decodes interpretable float16 to float32', () => {
        const decoder = getFloat16Decoder();
        const buffer = new Uint16Array([
            0x00003400,
            0x00003800,
            0x00003A00,
            0x00003555
        ]);
        const f32 = decoder(buffer);
        expect(f32[0]).toBeCloseTo(0.25);
        expect(f32[1]).toBeCloseTo(0.5);
        expect(f32[2]).toBeCloseTo(0.75);
        expect(f32[3]).toBeCloseTo(0.333);
    });
});
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiaW9fdXRpbHNfdGVzdC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uL3RmanMtY29yZS9zcmMvaW8vaW9fdXRpbHNfdGVzdC50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7O0dBZUc7QUFFSCxPQUFPLEtBQUssRUFBRSxNQUFNLFVBQVUsQ0FBQztBQUMvQixPQUFPLEVBQUMsUUFBUSxFQUFFLFlBQVksRUFBRSxpQkFBaUIsRUFBQyxNQUFNLGlCQUFpQixDQUFDO0FBQzFFLE9BQU8sRUFBQyxNQUFNLEVBQUUsUUFBUSxFQUFFLFFBQVEsRUFBQyxNQUFNLFlBQVksQ0FBQztBQUV0RCxPQUFPLEVBQUMsaUJBQWlCLEVBQUMsTUFBTSxjQUFjLENBQUM7QUFDL0MsT0FBTyxFQUFDLGlCQUFpQixFQUFDLE1BQU0sY0FBYyxDQUFDO0FBQy9DLE9BQU8sRUFBQyxZQUFZLEVBQUMsTUFBTSxTQUFTLENBQUM7QUFFckMsT0FBTyxFQUFDLHlCQUF5QixFQUFFLHlCQUF5QixFQUFFLFFBQVEsRUFBRSx1QkFBdUIsRUFBRSxzQkFBc0IsRUFBRSxnQkFBZ0IsRUFBRSxpQkFBaUIsRUFBQyxNQUFNLFlBQVksQ0FBQztBQUdoTCxRQUFRLENBQUMsd0JBQXdCLEVBQUUsR0FBRyxFQUFFO0lBQ3RDLEVBQUUsQ0FBQyxxQkFBcUIsRUFBRSxHQUFHLEVBQUU7UUFDN0IsTUFBTSxDQUFDLEdBQUcsSUFBSSxZQUFZLENBQUMsQ0FBQyxHQUFHLEVBQUUsR0FBRyxFQUFFLEdBQUcsQ0FBQyxDQUFDLENBQUM7UUFDNUMsTUFBTSxNQUFNLEdBQUcsc0JBQXNCLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDO1FBQzNDLE1BQU0sQ0FBQyxNQUFNLENBQUMsVUFBVSxDQUFDLENBQUMsT0FBTyxDQUFDLEVBQUUsQ0FBQyxDQUFDO1FBQ3RDLE1BQU0sQ0FBQyxJQUFJLFlBQVksQ0FBQyxNQUFNLEVBQUUsQ0FBQyxFQUFFLENBQUMsQ0FBQyxDQUFDLENBQUMsT0FBTyxDQUFDLENBQUMsQ0FBQyxDQUFDO0lBQ3BELENBQUMsQ0FBQyxDQUFDO0lBRUgsRUFBRSxDQUFDLGNBQWMsRUFBRSxHQUFHLEVBQUU7UUFDdEIsTUFBTSxDQUFDLEdBQUcsSUFBSSxZQUFZLENBQUMsQ0FBQyxHQUFHLEVBQUUsR0FBRyxFQUFFLEdBQUcsQ0FBQyxDQUFDLENBQUM7UUFDNUMsTUFBTSxDQUFDLEdBQUcsSUFBSSxZQUFZLENBQUMsQ0FBQyxDQUFDLEdBQUcsRUFBRSxDQUFDLEdBQUcsRUFBRSxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUM7UUFDL0MsTUFBTSxNQUFNLEdBQUcsc0JBQXNCLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUMsQ0FBQztRQUM5QyxNQUFNLENBQUMsTUFBTSxDQUFDLFVBQVUsQ0FBQyxDQUFDLE9BQU8sQ0FBQyxFQUFFLENBQUMsQ0FBQztRQUN0QyxNQUFNLENBQUMsSUFBSSxZQUFZLENBQUMsTUFBTSxFQUFFLENBQUMsRUFBRSxDQUFDLENBQUMsQ0FBQyxDQUFDLE9BQU8sQ0FBQyxDQUFDLENBQUMsQ0FBQztRQUNsRCxNQUFNLENBQUMsSUFBSSxZQUFZLENBQUMsTUFBTSxFQUFFLEVBQUUsRUFBRSxDQUFDLENBQUMsQ0FBQyxDQUFDLE9BQU8sQ0FBQyxDQUFDLENBQUMsQ0FBQztJQUNyRCxDQUFDLENBQUMsQ0FBQztJQUNILEVBQUUsQ0FBQyxxQkFBcUIsRUFBRSxHQUFHLEVBQUU7UUFDN0IsTUFBTSxDQUFDLEdBQUcsSUFBSSxVQUFVLENBQUMsQ0FBQyxFQUFFLEVBQUUsRUFBRSxFQUFFLEVBQUUsQ0FBQyxDQUFDLENBQUM7UUFDdkMsTUFBTSxNQUFNLEdBQUcsc0JBQXNCLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDO1FBQzNDLE1BQU0sQ0FBQyxNQUFNLENBQUMsVUFBVSxDQUFDLENBQUMsT0FBTyxDQUFDLEVBQUUsQ0FBQyxDQUFDO1FBQ3RDLE1BQU0sQ0FBQyxJQUFJLFVBQVUsQ0FBQyxN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TSxPQUFPLEdBQUcsaUJBQWlCLEVBQUUsQ0FBQztRQUNwQyxNQUFNLE1BQU0sR0FBRyxJQUFJLFdBQVcsQ0FBQztZQUM3QixVQUFVO1lBQ1YsVUFBVTtZQUNWLFVBQVU7WUFDVixVQUFVO1NBQ1gsQ0FBQyxDQUFDO1FBQ0gsTUFBTSxHQUFHLEdBQUcsT0FBTyxDQUFDLE1BQU0sQ0FBQyxDQUFDO1FBQzVCLE1BQU0sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxXQUFXLENBQUMsSUFBSSxDQUFDLENBQUM7UUFDakMsTUFBTSxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDLFdBQVcsQ0FBQyxHQUFHLENBQUMsQ0FBQztRQUNoQyxNQUFNLENBQUMsR0FBRyxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUMsV0FBVyxDQUFDLElBQUksQ0FBQyxDQUFDO1FBQ2pDLE1BQU0sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxXQUFXLENBQUMsS0FBSyxDQUFDLENBQUM7SUFDcEMsQ0FBQyxDQUFDLENBQUM7QUFDTCxDQUFDLENBQUMsQ0FBQyIsInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQGxpY2Vuc2VcbiAqIENvcHlyaWdodCAyMDE4IEdvb2dsZSBMTEMuIEFsbCBSaWdodHMgUmVzZXJ2ZWQuXG4gKiBMaWNlbnNlZCB1bmRlciB0aGUgQXBhY2hlIExpY2Vuc2UsIFZlcnNpb24gMi4wICh0aGUgXCJMaWNlbnNlXCIpO1xuICogeW91IG1heSBub3QgdXNlIHRoaXMgZmlsZSBleGNlcHQgaW4gY29tcGxpYW5jZSB3aXRoIHRoZSBMaWNlbnNlLlxuICogWW91IG1heSBvYnRhaW4gYSBjb3B5IG9mIHRoZSBMaWNlbnNlIGF0XG4gKlxuICogaHR0cDovL3d3dy5hcGFjaGUub3JnL2xpY2Vuc2VzL0xJQ0VOU0UtMi4wXG4gKlxuICogVW5sZXNzIHJlcXVpcmVkIGJ5IGFwcGxpY2FibGUgbGF3IG9yIGFncmVlZCB0byBpbiB3cml0aW5nLCBzb2Z0d2FyZVxuICogZGlzdHJpYnV0ZWQgdW5kZXIgdGhlIExpY2Vuc2UgaXMgZGlzdHJpYnV0ZWQgb24gYW4gXCJBUyBJU1wiIEJBU0lTLFxuICogV0lUSE9VVCBXQVJSQU5USUVTIE9SIENPTkRJVElPTlMgT0YgQU5ZIEtJTkQsIGVpdGhlciBleHByZXNzIG9yIGltcGxpZWQuXG4gKiBTZWUgdGhlIExpY2Vuc2UgZm9yIHRoZSBzcGVjaWZpYyBsYW5ndWFnZSBnb3Zlcm5pbmcgcGVybWlzc2lvbnMgYW5kXG4gKiBsaW1pdGF0aW9ucyB1bmRlciB0aGUgTGljZW5zZS5cbiAqID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG4gKi9cblxuaW1wb3J0ICogYXMgdGYgZnJvbSAnLi4vaW5kZXgnO1xuaW1wb3J0IHtBTExfRU5WUywgQlJPV1NFUl9FTlZTLCBkZXNjcmliZVdpdGhGbGFnc30gZnJvbSAnLi4vamFzbWluZV91dGlsJztcbmltcG9ydCB7c2NhbGFyLCB0ZW5zb3IxZCwgdGVuc29yMmR9IGZyb20gJy4uL29wcy9vcHMnO1xuaW1wb3J0IHtOYW1lZFRlbnNvciwgTmFtZWRUZW5zb3JNYXB9IGZyb20gJy4uL3RlbnNvcl90eXBlcyc7XG5pbXBvcnQge2V4cGVjdEFycmF5c0VxdWFsfSBmcm9tICcuLi90ZXN0X3V0aWwnO1xuaW1wb3J0IHtleHBlY3RBcnJheXNDbG9zZX0gZnJvbSAnLi4vdGVzdF91dGlsJztcbmltcG9ydCB7ZW5jb2RlU3RyaW5nfSBmcm9tICcuLi91dGlsJztcblxuaW1wb3J0IHthcnJheUJ1ZmZlclRvQmFzZTY0U3RyaW5nLCBiYXNlNjRTdHJpbmdUb0FycmF5QnVmZmVyLCBiYXNlbmFtZSwgY29uY2F0ZW5hdGVBcnJheUJ1ZmZlcnMsIGNvbmNhdGVuYXRlVHlwZWRBcnJheXMsIHN0cmluZ0J5dGVMZW5ndGgsIGdldEZsb2F0MTZEZWNvZGVyfSBmcm9tICcuL2lvX3V0aWxzJztcbmltcG9ydCB7V2VpZ2h0c01hbmlmZXN0RW50cnl9IGZyb20gJy4vdHlwZXMnO1xuXG5kZXNjcmliZSgnY29uY2F0ZW5hdGVUeXBlZEFycmF5cycsICgpID0+IHtcbiAgaXQoJ1NpbmdsZSBmbG9hdCBhcnJheXMnLCAoKSA9PiB7XG4gICAgY29uc3QgeCA9IG5ldyBGbG9hdDMyQXJyYXkoWzEuMSwgMi4yLCAzLjNdKTtcbiAgICBjb25zdCBidWZmZXIgPSBjb25jYXRlbmF0ZVR5cGVkQXJyYXlzKFt4XSk7XG4gICAgZXhwZWN0KGJ1ZmZlci5ieXRlTGVuZ3RoKS50b0VxdWFsKDEyKTtcbiAgICBleHBlY3QobmV3IEZsb2F0MzJBcnJheShidWZmZXIsIDAsIDMpKS50b0VxdWFsKHgpO1xuICB9KTtcblxuICBpdCgnRmxvYXQgYXJyYXlzJywgKCkgPT4ge1xuICAgIGNvbnN0IHggPSBuZXcgRmxvYXQzMkFycmF5KFsxLjEsIDIuMiwgMy4zXSk7XG4gICAgY29uc3QgeSA9IG5ldyBGbG9hdDMyQXJyYXkoWy0xLjEsIC0yLjIsIC0zLjNdKTtcbiAgICBjb25zdCBidWZmZXIgPSBjb25jYXRlbmF0ZVR5cGVkQXJyYXlzKFt4LCB5XSk7XG4gICAgZXhwZWN0KGJ1ZmZlci5ieXRlTGVuZ3RoKS50b0VxdWFsKDI0KTtcbiAgICBleHBlY3QobmV3IEZsb2F0MzJBcnJheShidWZmZXIsIDAsIDMpKS50b0VxdWFsKHgpO1xuICAgIGV4cGVjdChuZXcgRmxvYXQzMkFycmF5KGJ1ZmZlciwgMTIsIDMpKS50b0VxdWFsKHkpO1xuICB9KTtcbiAgaXQoJ1NpbmdsZSBpbnQzMiBhcnJheXMnLCAoKSA9PiB7XG4gICAgY29uc3QgeCA9IG5ldyBJbnQzMkFycmF5KFsxMSwgMjIsIDMzXSk7XG4gICAgY29uc3QgYnVmZmVyID0gY29uY2F0ZW5hdGVUeXBlZEFycmF5cyhbeF0pO1xuICAgIGV4cGVjdChidWZmZXIuYnl0ZUxlbmd0aCkudG9FcXVhbCgxMik7XG4gICAgZXhwZWN0KG5ldyBJbnQzMkFycmF5KGJ1ZmZlciwgMCwgMykpLnRvRXF1YWwoeCk7XG4gIH0pO1xuXG4gIGl0KCdJbnQzMiBhcnJheXMnLCAoKSA9PiB7XG4gICAgY29uc3QgeCA9IG5ldyBJbnQzMkFycmF5KFsxMSwgMjIsIDMzXSk7XG4gICAgY29uc3QgeSA9IG5ldyBJbnQzMkFycmF5KFstMTEsIC0yMiwgLTMzXSk7XG4gICAgY29uc3QgYnVmZmVyID0gY29uY2F0ZW5hdGVUeXBlZEFycmF5cyhbeCwgeV0pO1xuICAgIGV4cGVjdChidWZmZXIuYnl0ZUxlbmd0aCkudG9FcXVhbCgyNCk7XG4gICAgZXhwZWN0KG5ldyBJbnQzMkFycmF5KGJ1ZmZlciwgMCwgMykpLnRvRXF1YWwoeCk7XG4gICAgZXhwZWN0KG5ldyBJbnQzMkFycmF5KGJ1ZmZlciwgMTIsIDMpKS50b0VxdWFsKHkpO1xuICB9KTtcblxuICBpdCgnU2luZ2xlIHVpbnQ4IGFycmF5cycsICgpID0+IHtcbiAgICBjb25zdCB4ID0gbmV3IFVpbnQ4QXJyYXkoWzExLCAyMiwgMzNdKTtcbiAgICBjb25zdCBidWZmZXIgPSBjb25jYXRlbmF0ZVR5cGVkQXJyYXlzKFt4XSk7XG4gICAgZXhwZWN0KGJ1ZmZlci5ieXRlTGVuZ3RoKS50b0VxdWFsKDMpO1xuICAgIGV4cGVjdChuZXcgVWludDhBcnJheShidWZmZXIsIDAsIDMpKS50b0VxdWFsKHgpO1xuICB9KTtcblxuICBpdCgnVWludDggYXJyYXlzJywgKCkgPT4ge1xuICAgIGNvbnN0IHggPSBuZXcgVWludDhBcnJheShbMTEsIDIyLCAzM10pO1xuICAgIGNvbnN0IHkgPSBuZXcgVWludDhBcnJheShbMTExLCAxMjIsIDEzM10pO1xuICAgIGNvbnN0IGJ1ZmZlciA9IGNvbmNhdGVuYXRlVHlwZWRBcnJheXMoW3gsIHldKTtcbiAgICBleHBlY3QoYnVmZmVyLmJ5dGVMZW5ndGgpLnRvRXF1YWwoNik7XG4gICAgZXhwZWN0KG5ldyBVaW50OEFycmF5KGJ1ZmZlciwgMCwgMykpLnRvRXF1YWwoeCk7XG4gICAgZXhwZWN0KG5ldyBVaW50OEFycmF5KGJ1ZmZlciwgMywgMykpLnRvRXF1YWwoeSk7XG4gIH0pO1xuXG4gIGl0KCdNaXhlZCBVaW50OCwgSW50MzIgYW5kIEZsb2F0MzIgYXJyYXlzJywgKCkgPT4ge1xuICAgIGNvbnN0IHggPSBuZXcgVWludDhBcnJheShbMCwgMSwgMSwgMF0pO1xuICAgIGNvbnN0IHkgPSBuZXcgSW50MzJBcnJheShbMTAsIDIwLCAzMCwgNDBdKTtcbiAgICBjb25zdCB6ID0gbmV3IEZsb2F0MzJBcnJheShbLTEuMSwgLTIuMiwgLTMuMywgLTQuNF0pO1xuICAgIGNvbnN0IGJ1ZmZlciA9IGNvbmNhdGVuYXRlVHlwZWRBcnJheXMoW3gsIHksIHpdKTtcbiAgICBleHBlY3QoYnVmZmVyLmJ5dGVMZW5ndGgpLnRvRXF1YWwoMSAqIDQgKyA0ICogNCArIDQgKiA0KTtcbiAgICBleHBlY3QobmV3IFVpbnQ4QXJyYXkoYnVmZmVyLCAwLCA0KSkudG9FcXVhbCh4KTtcbiAgICBleHBlY3QobmV3IEludDMyQXJyYXkoYnVmZmVyLCA0LCA0KSkudG9FcXVhbCh5KTtcbiAgICBleHBlY3QobmV3IEZsb2F0MzJBcnJheShidWZmZXIsIDIwLCA0KSkudG9FcXVhbCh6KTtcbiAgfSk7XG5cbiAgaXQoJ0NvbmNhdGVuYXRlIEZsb2F0MzJBcnJheXMgZnJvbSBTdWJBcnJheXMnLCAoKSA9PiB7XG4gICAgY29uc3QgeDEgPSBuZXcgRmxvYXQzMkFycmF5KFsxLjEsIDIuMiwgMy4zXSk7XG4gICAgY29uc3QgeDIgPSBuZXcgRmxvYXQzMkFycmF5KFstMS4xLCAtMi4yLCAtMy4zXSk7XG4gICAgY29uc3QgeENvbmNhdGVuYXRlZCA9IGNvbmNhdGVuYXRlVHlwZWRBcnJheXMoW3gxLCB4Ml0pO1xuICAgIGNvbnN0IHkxID0gbmV3IEZsb2F0MzJBcnJheSh4Q29uY2F0ZW5hdGVkLCAwLCAzKTtcbiAgICBjb25zdCB5MiA9IG5ldyBGbG9hdDMyQXJyYXkoeENvbmNhdGVuYXRlZCwgMyAqIDQsIDMpO1xuICAgIC8vIEF0IHRoaXMgcG9pbnQsIHRoZSBidWZmZXIgb2YgeTEgaXMgbG9uZ2VyIHRoYW4gdGhhbiB0aGUgYWN0dWFsIGJ5dGVcbiAgICAvLyBsZW5ndGggb2YgeTEsIGJlY2F1c2Ugb2YgdGhlIHdheSB5MSBpcyBjb25zdHJ1Y3RlZC4gVGhlIHNhbWUgaXMgdHJ1ZSBmb3JcbiAgICAvLyB5Mi5cbiAgICBleHBlY3QoeTEuYnVmZmVyLmJ5dGVMZW5ndGgpLnRvRXF1YWwoNiAqIDQpO1xuICAgIGV4cGVjdCh5Mi5idWZmZXIuYnl0ZUxlbmd0aCkudG9FcXVhbCg2ICogNCk7XG5cbiAgICBjb25zdCB5Q29uY2F0ZW5hdGVkID0gY29uY2F0ZW5hdGVUeXBlZEFycmF5cyhbeTEsIHkyXSk7XG4gICAgZXhwZWN0KHlDb25jYXRlbmF0ZWQuYnl0ZUxlbmd0aCkudG9FcXVhbCg2ICogNCk7XG4gICAgZXhwZWN0KG5ldyBGbG9hdDMyQXJyYXkoeUNvbmNhdGVuYXRlZCwgMCwgMykpLnRvRXF1YWwoeDEpO1xuICAgIGV4cGVjdChuZXcgRmxvYXQzMkFycmF5KHlDb25jYXRlbmF0ZWQsIDMgKiA0LCAzKSkudG9FcXVhbCh4Mik7XG4gIH0pO1xuXG4gIGl0KCdDb25jYXRlbmF0ZSBJbnQzMkFycmF5IGZyb20gU3ViQXJyYXlzJywgKCkgPT4ge1xuICAgIGNvbnN0IHgxID0gbmV3IEludDMyQXJyYXkoWzExLCAyMiwgMzNdKTtcbiAgICBjb25zdCB4MiA9IG5ldyBJbnQzMkFycmF5KFstMTEsIC0yMiwgLTMzXSk7XG4gICAgY29uc3QgeENvbmNhdGVuYXRlZCA9IGNvbmNhdGVuYXRlVHlwZWRBcnJheXMoW3gxLCB4Ml0pO1xuICAgIGNvbnN0IHkxID0gbmV3IEludDMyQXJyYXkoeENvbmNhdGVuYXRlZCwgMCwgMyk7XG4gICAgY29uc3QgeTIgPSBuZXcgSW50MzJBcnJheSh4Q29uY2F0ZW5hdGVkLCAzICogNCwgMyk7XG4gICAgLy8gQXQgdGhpcyBwb2ludCwgdGhlIGJ1ZmZlciBvZiB5MSBpcyBsb25nZXIgdGhhbiB0aGFuIHRoZSBhY3R1YWwgYnl0ZVxuICAgIC8vIGxlbmd0aCBvZiB5MSwgYmVjYXVzZSBvZiB0aGUgd2F5IHkxIGlzIGNvbnN0cnVjdGVkLiBUaGUgc2FtZSBpcyB0cnVlIGZvclxuICAgIC8vIHkyLlxuICAgIGV4cGVjdCh5MS5idWZmZXIuYnl0ZUxlbmd0aCkudG9FcXVhbCg2ICogNCk7XG4gICAgZXhwZWN0KHkyLmJ1ZmZlci5ieXRlTGVuZ3RoKS50b0VxdWFsKDYgKiA0KTtcblxuICAgIGNvbnN0IHlDb25jYXRlbmF0ZWQgPSBjb25jYXRlbmF0ZVR5cGVkQXJyYXlzKFt5MSwgeTJdKTtcbiAgICBleHBlY3QoeUNvbmNhdGVuYXRlZC5ieXRlTGVuZ3RoKS50b0VxdWFsKDYgKiA0KTtcbiAgICBleHBlY3QobmV3IEludDMyQXJyYXkoeUNvbmNhdGVuYXRlZCwgMCwgMykpLnRvRXF1YWwoeDEpO1xuICAgIGV4cGVjdChuZXcgSW50MzJBcnJheSh5Q29uY2F0ZW5hdGVkLCAzICogNCwgMykpLnRvRXF1YWwoeDIpO1xuICB9KTtcblxuICBpdCgnQ29uY2F0ZW5hdGUgVWludDhBcnJheSBmcm9tIFN1YkFycmF5cycsICgpID0+IHtcbiAgICBjb25zdCB4MSA9IG5ldyBVaW50OEFycmF5KFsxMSwgMjIsIDMzXSk7XG4gICAgY29uc3QgeDIgPSBuZXcgVWludDhBcnJheShbNDQsIDU1LCA2Nl0pO1xuICAgIGNvbnN0IHhDb25jYXRlbmF0ZWQgPSBjb25jYXRlbmF0ZVR5cGVkQXJyYXlzKFt4MSwgeDJdKTtcbiAgICBjb25zdCB5MSA9IG5ldyBVaW50OEFycmF5KHhDb25jYXRlbmF0ZWQsIDAsIDMpO1xuICAgIGNvbnN0IHkyID0gbmV3IFVpbnQ4QXJyYXkoeENvbmNhdGVuYXRlZCwgMywgMyk7XG4gICAgLy8gQXQgdGhpcyBwb2ludCwgdGhlIGJ1ZmZlciBvZiB5MSBpcyBsb25nZXIgdGhhbiB0aGFuIHRoZSBhY3R1YWwgYnl0ZVxuICAgIC8vIGxlbmd0aCBvZiB5MSwgYmVjYXVzZSBvZiB0aGUgd2F5IHkxIGlzIGNvbnN0cnVjdGVkLiBUaGUgc2FtZSBpcyB0cnVlIGZvclxuICAgIC8vIHkyLlxuICAgIGV4cGVjdCh5MS5idWZmZXIuYnl0ZUxlbmd0aCkudG9FcXVhbCg2KTtcbiAgICBleHBlY3QoeTIuYnVmZmVyLmJ5dGVMZW5ndGgpLnRvRXF1YWwoNik7XG5cbiAgICBjb25zdCB5Q29uY2F0ZW5hdGVkID0gY29uY2F0ZW5hdGVUeXBlZEFycmF5cyhbeTEsIHkyXSk7XG4gICAgZXhwZWN0KHlDb25jYXRlbmF0ZWQuYnl0ZUxlbmd0aCkudG9FcXVhbCg2KTtcbiAgICBleHBlY3QobmV3IFVpbnQ4QXJyYXkoeUNvbmNhdGVuYXRlZCwgMCwgMykpLnRvRXF1YWwoeDEpO1xuICAgIGV4cGVjdChuZXcgVWludDhBcnJheSh5Q29uY2F0ZW5hdGVkLCAzLCAzKSkudG9FcXVhbCh4Mik7XG4gIH0pO1xuXG4gIGl0KCdDb25jYXRlbmF0ZSBtaXhlZCBUeXBlZEFycmF5cyBmcm9tIFN1YkFycmF5cycsICgpID0+IHtcbiAgICBjb25zdCB4MSA9IG5ldyBVaW50OEFycmF5KFsxMSwgMjIsIDMzLCA0NF0pO1xuICAgIGNvbnN0IHgyID0gbmV3IEludDMyQXJyYXkoWy00NCwgLTU1LCAtNjZdKTtcbiAgICBjb25zdCB4MyA9IG5ldyBGbG9hdDMyQXJyYXkoWzEuMSwgMi4yLCAzLjNdKTtcbiAgICBjb25zdCB4Q29uY2F0ZW5hdGVkID0gY29uY2F0ZW5hdGVUeXBlZEFycmF5cyhbeDEsIHgyLCB4M10pO1xuICAgIGNvbnN0IHkxID0gbmV3IFVpbnQ4QXJyYXkoeENvbmNhdGVuYXRlZCwgMCwgNCk7XG4gICAgY29uc3QgeTIgPSBuZXcgSW50MzJBcnJheSh4Q29uY2F0ZW5hdGVkLCA0LCAzKTtcbiAgICBjb25zdCB5MyA9IG5ldyBGbG9hdDMyQXJyYXkoeENvbmNhdGVuYXRlZCwgNCArIDMgKiA0LCAzKTtcbiAgICAvLyBBdCB0aGlzIHBvaW50LCB0aGUgYnVmZmVyIG9mIHkxIGlzIGxvbmdlciB0aGFuIHRoYW4gdGhlIGFjdHVhbCBieXRlXG4gICAgLy8gbGVuZ3RoIG9mIHkxLCBiZWNhdXNlIG9mIHRoZSB3YXkgeTEgaXMgY29uc3RydWN0ZWQuIFRoZSBzYW1lIGlzIHRydWUgZm9yXG4gICAgLy8geTIgYW5kIHkzLlxuICAgIGV4cGVjdCh5MS5idWZmZXIuYnl0ZUxlbmd0aCkudG9FcXVhbCg0ICsgMyAqIDQgKyAzICogNCk7XG4gICAgZXhwZWN0KHkyLmJ1ZmZlci5ieXRlTGVuZ3RoKS50b0VxdWFsKDQgKyAzICogNCArIDMgKiA0KTtcbiAgICBleHBlY3QoeTMuYnVmZmVyLmJ5dGVMZW5ndGgpLnRvRXF1YWwoNCArIDMgKiA0ICsgMyAqIDQpO1xuXG4gICAgY29uc3QgeUNvbmNhdGVuYXRlZCA9IGNvbmNhdGVuYXRlVHlwZWRBcnJheXMoW3kxLCB5MiwgeTNdKTtcbiAgICBleHBlY3QoeUNvbmNhdGVuYXRlZC5ieXRlTGVuZ3RoKS50b0VxdWFsKDQgKyAzICogNCArIDMgKiA0KTtcbiAgICBleHBlY3QobmV3IFVpbnQ4QXJyYXkoeUNvbmNhdGVuYXRlZCwgMCwgNCkpLnRvRXF1YWwoeDEpO1xuICAgIGV4cGVjdChuZXcgSW50MzJBcnJheSh5Q29uY2F0ZW5hdGVkLCA0LCAzKSkudG9FcXVhbCh4Mik7XG4gICAgZXhwZWN0KG5ldyBGbG9hdDMyQXJyYXkoeUNvbmNhdGVuYXRlZCwgNCArIDMgKiA0LCAzKSkudG9FcXVhbCh4Myk7XG4gIH0pO1xuXG4gIGl0KCdudWxsIGFuZCB1bmRlZmluZWQgaW5wdXRzJywgKCkgPT4ge1xuICAgIGV4cGVjdCgoKSA9PiBjb25jYXRlbmF0ZVR5cGVkQXJyYXlzKG51bGwpKS50b1Rocm93KCk7XG4gICAgZXhwZWN0KCgpID0+IGNvbmNhdGVuYXRlVHlwZWRBcnJheXModW5kZWZpbmVkKSkudG9UaHJvdygpO1xuICB9KTtcblxuICBpdCgnZW1wdHkgaW5wdXQgYXJyYXknLCAoKSA9PiB7XG4gICAgZXhwZWN0KGNvbmNhdGVuYXRlVHlwZWRBcnJheXMoW10pLmJ5dGVMZW5ndGgpLnRvRXF1YWwoMCk7XG4gIH0pO1xuXG4gIGl0KCdVbnN1cHBvcnRlZCBkdHlwZScsICgpID0+IHtcbiAgICBjb25zdCB4ID0gbmV3IEludDE2QXJyYXkoWzAsIDEsIDEsIDBdKTtcbiAgICAvLyB0c2xpbnQ6ZGlzYWJsZS1uZXh0LWxpbmU6bm8tYW55XG4gICAgZXhwZWN0KCgpID0+IGNvbmNhdGVuYXRlVHlwZWRBcnJheXMoW3ggYXMgYW55XSkpXG4gICAgICAgIC50b1Rocm93RXJyb3IoL1Vuc3VwcG9ydGVkIFR5cGVkQXJyYXkgc3VidHlwZTogSW50MTZBcnJheS8pO1xuICB9KTtcbn0pO1xuXG5kZXNjcmliZVdpdGhGbGFncygnZW5jb2RlV2VpZ2h0cycsIEFMTF9FTlZTLCAoKSA9PiB7XG4gIGl0KCdGbG9hdDMyIHRlbnNvcnMgYXMgTmFtZWRUZW5zb3JNYXAnLCBhc3luYyAoKSA9PiB7XG4gICAgY29uc3QgdGVuc29yczogTmFtZWRUZW5zb3JNYXAgPSB7XG4gICAgICB4MTogdGVuc29yMmQoW1sxMCwgMjBdLCBbMzAsIDQwXV0pLFxuICAgICAgeDI6IHNjYWxhcig0MiksXG4gICAgICB4MzogdGVuc29yMWQoWy0xLjMsIC0zLjcsIDEuMywgMy43XSksXG4gICAgfTtcbiAgICBjb25zdCBkYXRhQW5kU3BlY3MgPSBhd2FpdCB0Zi5pby5lbmNvZGVXZWlnaHRzKHRlbnNvcnMpO1xuICAgIGNvbnN0IGRhdGEgPSBkYXRhQW5kU3BlY3MuZGF0YTtcbiAgICBjb25zdCBzcGVjcyA9IGRhdGFBbmRTcGVjcy5zcGVjcztcbiAgICBleHBlY3QoZGF0YS5ieXRlTGVuZ3RoKS50b0VxdWFsKDQgKiAoNCArIDEgKyA0KSk7XG4gICAgZXhwZWN0KG5ldyBGbG9hdDMyQXJyYXkoZGF0YSwgMCwgNCkpLnRvRXF1YWwobmV3IEZsb2F0MzJBcnJheShbXG4gICAgICAxMCwgMjAsIDMwLCA0MFxuICAgIF0pKTtcbiAgICBleHBlY3QobmV3IEZsb2F0MzJBcnJheShkYXRhLCAxNiwgMSkpLnRvRXF1YWwobmV3IEZsb2F0MzJBcnJheShbNDJdKSk7XG4gICAgZXhwZWN0KG5ldyBGbG9hdDMyQXJyYXkoZGF0YSwgMjAsIDQpKS50b0VxdWFsKG5ldyBGbG9hdDMyQXJyYXkoW1xuICAgICAgLTEuMywgLTMuNywgMS4zLCAzLjdcbiAgICBdKSk7XG4gICAgZXhwZWN0KHNwZWNzKS50b0VxdWFsKFtcbiAgICAgIHtcbiAgICAgICAgbmFtZTogJ3gxJyxcbiAgICAgICAgZHR5cGU6ICdmbG9hdDMyJyxcbiAgICAgICAgc2hhcGU6IFsyLCAyXSxcbiAgICAgIH0sXG4gICAgICB7XG4gICAgICAgIG5hbWU6ICd4MicsXG4gICAgICAgIGR0eXBlOiAnZmxvYXQzMicsXG4gICAgICAgIHNoYXBlOiBbXSxcbiAgICAgIH0sXG4gICAgICB7XG4gICAgICAgIG5hbWU6ICd4MycsXG4gICAgICAgIGR0eXBlOiAnZmxvYXQzMicsXG4gICAgICAgIHNoYXBlOiBbNF0sXG4gICAgICB9XG4gICAgXSk7XG4gIH0pO1xuXG4gIGl0KCdGbG9hdDMyIHRlbnNvcnMgYXMgTmFtZWRUZW5zb3IgYXJyYXknLCBhc3luYyAoKSA9PiB7XG4gICAgY29uc3QgdGVuc29yczogTmFtZWRUZW5zb3JbXSA9IFtcbiAgICAgIHtuYW1lOiAneDEyMzQnLCB0ZW5zb3I6IHRlbnNvcjJkKFtbMTAsIDIwXSwgWzMwLCA0MF1dKX0sIHtcbiAgICAgICAgbmFtZTogJ2E0MicsXG4gICAgICAgIHRlbnNvcjogc2NhbGFyKDQyKSxcbiAgICAgIH0sXG4gICAgICB7bmFtZTogJ2I0MScsIHRlbnNvcjogdGVuc29yMWQoWy0xLjMsIC0zLjcsIDEuMywgMy43XSl9XG4gICAgXTtcbiAgICBjb25zdCBkYXRhQW5kU3BlY3MgPSBhd2FpdCB0Zi5pby5lbmNvZGVXZWlnaHRzKHRlbnNvcnMpO1xuICAgIGNvbnN0IGRhdGEgPSBkYXRhQW5kU3BlY3MuZGF0YTtcbiAgICBjb25zdCBzcGVjcyA9IGRhdGFBbmRTcGVjcy5zcGVjcztcbiAgICBleHBlY3QoZGF0YS5ieXRlTGVuZ3RoKS50b0VxdWFsKDQgKiAoNCArIDEgKyA0KSk7XG4gICAgZXhwZWN0KG5ldyBGbG9hdDMyQXJyYXkoZGF0YSwgMCwgNCkpLnRvRXF1YWwobmV3IEZsb2F0MzJBcnJheShbXG4gICAgICAxMCwgMjAsIDMwLCA0MFxuICAgIF0pKTtcbiAgICBleHBlY3QobmV3IEZsb2F0MzJBcnJheShkYXRhLCAxNiwgMSkpLnRvRXF1YWwobmV3IEZsb2F0MzJBcnJheShbNDJdKSk7XG4gICAgZXhwZWN0KG5ldyBGbG9hdDMyQXJyYXkoZGF0YSwgMjAsIDQpKS50b0VxdWFsKG5ldyBGbG9hdDMyQXJyYXkoW1xuICAgICAgLTEuMywgLTMuNywgMS4zLCAzLjdcbiAgICBdKSk7XG4gICAgZXhwZWN0KHNwZWNzKS50b0VxdWFsKFtcbiAgICAgIHtcbiAgICAgICAgbmFtZTogJ3gxMjM0JyxcbiAgICAgICAgZHR5cGU6ICdmbG9hdDMyJyxcbiAgICAgICAgc2hhcGU6IFsyLCAyXSxcbiAgICAgIH0sXG4gICAgICB7XG4gICAgICAgIG5hbWU6ICdhNDInLFxuICAgICAgICBkdHlwZTogJ2Zsb2F0MzInLFxuICAgICAgICBzaGFwZTogW10sXG4gICAgICB9LFxuICAgICAge1xuICAgICAgICBuYW1lOiAnYjQxJyxcbiAgICAgICAgZHR5cGU6ICdmbG9hdDMyJyxcbiAgICAgICAgc2hhcGU6IFs0XSxcbiAgICAgIH1cbiAgICBdKTtcbiAgfSk7XG5cbiAgaXQoJ0VtcHR5IE5hbWVkVGVuc29yIGFycmF5JywgYXN5bmMgKCkgPT4ge1xuICAgIGNvbnN0IHRlbnNvcnM6IE5hbWVkVGVuc29yW10gPSBbXTtcbiAgICBjb25zdCBkYXRhQW5kU3BlY3MgPSBhd2FpdCB0Zi5pby5lbmNvZGVXZWlnaHRzKHRlbnNvcnMpO1xuICAgIGNvbnN0IGRhdGEgPSBkYXRhQW5kU3BlY3MuZGF0YTtcbiAgICBjb25zdCBzcGVjcyA9IGRhdGFBbmRTcGVjcy5zcGVjcztcbiAgICBleHBlY3QoZGF0YS5ieXRlTGVuZ3RoKS50b0VxdWFsKDApO1xuICAgIGV4cGVjdChzcGVjcykudG9FcXVhbChbXSk7XG4gIH0pO1xuXG4gIGl0KCdJbnQzMiB0ZW5zb3JzJywgYXN5bmMgKCkgPT4ge1xuICAgIGNvbnN0IHRlbnNvcnM6IE5hbWVkVGVuc29yTWFwID0ge1xuICAgICAgeDE6IHRlbnNvcjJkKFtbMTAsIDIwXSwgWzMwLCA0MF1dLCBbMiwgMl0sICdpbnQzMicpLFxuICAgICAgeDI6IHNjYWxhcig0MiwgJ2ludDMyJyksXG4gICAgICB4MzogdGVuc29yMWQoWy0xLCAtMywgLTMsIC03XSwgJ2ludDMyJyksXG4gICAgfTtcbiAgICBjb25zdCBkYXRhQW5kU3BlY3MgPSBhd2FpdCB0Zi5pby5lbmNvZGVXZWlnaHRzKHRlbnNvcnMpO1xuICAgIGNvbnN0IGRhdGEgPSBkYXRhQW5kU3BlY3MuZGF0YTtcbiAgICBjb25zdCBzcGVjcyA9IGRhdGFBbmRTcGVjcy5zcGVjcztcbiAgICBleHBlY3QoZGF0YS5ieXRlTGVuZ3RoKS50b0VxdWFsKDQgKiAoNCArIDEgKyA0KSk7XG4gICAgZXhwZWN0KG5ldyBJbnQzMkFycmF5KGRhdGEsIDAsIDQpKS50b0VxdWFsKG5ldyBJbnQzMkFycmF5KFtcbiAgICAgIDEwLCAyMCwgMzAsIDQwXG4gICAgXSkpO1xuICAgIGV4cGVjdChuZXcgSW50MzJBcnJheShkYXRhLCAxNiwgMSkpLnRvRXF1YWwobmV3IEludDMyQXJyYXkoWzQyXSkpO1xuICAgIGV4cGVjdChuZXcgSW50MzJBcnJheShkYXRhLCAyMCwgNCkpLnRvRXF1YWwobmV3IEludDMyQXJyYXkoW1xuICAgICAgLTEsIC0zLCAtMywgLTdcbiAgICBdKSk7XG4gICAgZXhwZWN0KHNwZWNzKS50b0VxdWFsKFtcbiAgICAgIHtcbiAgICAgICAgbmFtZTogJ3gxJyxcbiAgICAgICAgZHR5cGU6ICdpbnQzMicsXG4gICAgICAgIHNoYXBlOiBbMiwgMl0sXG4gICAgICB9LFxuICAgICAge1xuICAgICAgICBuYW1lOiAneDInLFxuICAgICAgICBkdHlwZTogJ2ludDMyJyxcbiAgICAgICAgc2hhcGU6IFtdLFxuICAgICAgfSxcbiAgICAgIHtcbiAgICAgICAgbmFtZTogJ3gzJyxcbiAgICAgICAgZHR5cGU6ICdpbnQzMicsXG4gICAgICAgIHNoYXBlOiBbNF0sXG4gICAgICB9XG4gICAgXSk7XG4gIH0pO1xuXG4gIGl0KCdCb29sIHRlbnNvcnMnLCBhc3luYyAoKSA9PiB7XG4gICAgY29uc3QgdGVuc29yczogTmFtZWRUZW5zb3JNYXAgPSB7XG4gICAgICB4MTogdGVuc29yMmQoW1t0cnVlLCBmYWxzZV0sIFtmYWxzZSwgdHJ1ZV1dLCBbMiwgMl0sICdib29sJyksXG4gICAgICB4Mjogc2NhbGFyKGZhbHNlLCAnYm9vbCcpLFxuICAgICAgeDM6IHRlbnNvcjFkKFtmYWxzZSwgdHJ1ZSwgdHJ1ZSwgZmFsc2VdLCAnYm9vbCcpLFxuICAgIH07XG4gICAgY29uc3QgZGF0YUFuZFNwZWNzID0gYXdhaXQgdGYuaW8uZW5jb2RlV2VpZ2h0cyh0ZW5zb3JzKTtcbiAgICBjb25zdCBkYXRhID0gZGF0YUFuZFNwZWNzLmRhdGE7XG4gICAgY29uc3Qgc3BlY3MgPSBkYXRhQW5kU3BlY3Muc3BlY3M7XG4gICAgZXhwZWN0KGRhdGEuYnl0ZUxlbmd0aCkudG9FcXVhbCg0ICsgMSArIDQpO1xuICAgIGV4cGVjdChuZXcgVWludDhBcnJheShkYXRhLCAwLCA0KSkudG9FcXVhbChuZXcgVWludDhBcnJheShbMSwgMCwgMCwgMV0pKTtcbiAgICBleHBlY3QobmV3IFVpbnQ4QXJyYXkoZGF0YSwgNCwgMSkpLnRvRXF1YWwobmV3IFVpbnQ4QXJyYXkoWzBdKSk7XG4gICAgZXhwZWN0KG5ldyBVaW50OEFycmF5KGRhdGEsIDUsIDQpKS50b0VxdWFsKG5ldyBVaW50OEFycmF5KFswLCAxLCAxLCAwXSkpO1xuICAgIGV4cGVjdChzcGVjcykudG9FcXVhbChbXG4gICAgICB7XG4gICAgICAgIG5hbWU6ICd4MScsXG4gICAgICAgIGR0eXBlOiAnYm9vbCcsXG4gICAgICAgIHNoYXBlOiBbMiwgMl0sXG4gICAgICB9LFxuICAgICAge1xuICAgICAgICBuYW1lOiAneDInLFxuICAgICAgICBkdHlwZTogJ2Jvb2wnLFxuICAgICAgICBzaGFwZTogW10sXG4gICAgICB9LFxuICAgICAge1xuICAgICAgICBuYW1lOiAneDMnLFxuICAgICAgICBkdHlwZTogJ2Jvb2wnLFxuICAgICAgICBzaGFwZTogWzRdLFxuICAgICAgfVxuICAgIF0pO1xuICB9KTtcblxuICBpdCgnQ29tcGxleDY0IHRlbnNvcnMnLCBhc3luYyAoKSA9PiB7XG4gICAgY29uc3QgdGVuc29yczogTmFtZWRUZW5zb3JNYXAgPSB7XG4gICAgICB4MTogdGYuY29tcGxleChbMSwgMl0sIFsxLCAyXSksXG4gICAgICB4MjogdGYuY29tcGxleCgxLCAyKSxcbiAgICAgIHgzOiB0Zi5jb21wbGV4KFtbMV1dLCBbWzJdXSksXG4gICAgfTtcbiAgICBjb25zdCBkYXRhQW5kU3BlY3MgPSBhd2FpdCB0Zi5pby5lbmNvZGVXZWlnaHRzKHRlbnNvcnMpO1xuICAgIGNvbnN0IGRhdGEgPSBkYXRhQW5kU3BlY3MuZGF0YTtcbiAgICBjb25zdCBzcGVjcyA9IGRhdGFBbmRTcGVjcy5zcGVjcztcbiAgICBleHBlY3QoZGF0YS5ieXRlTGVuZ3RoKS50b0VxdWFsKDggKiA0KTtcbiAgICBleHBlY3QobmV3IEZsb2F0MzJBcnJheShkYXRhLCAwLCA0KSkudG9FcXVhbChuZXcgRmxvYXQzMkFycmF5KFtcbiAgICAgIDEsIDEsIDIsIDJcbiAgICBdKSk7XG4gICAgZXhwZWN0KG5ldyBGbG9hdDMyQXJyYXkoZGF0YSwgMTYsIDIpKS50b0VxdWFsKG5ldyBGbG9hdDMyQXJyYXkoWzEsIDJdKSk7XG4gICAgZXhwZWN0KG5ldyBGbG9hdDMyQXJyYXkoZGF0YSwgMjQsIDIpKS50b0VxdWFsKG5ldyBGbG9hdDMyQXJyYXkoWzEsIDJdKSk7XG4gICAgZXhwZWN0KHNwZWNzKS50b0VxdWFsKFtcbiAgICAgIHtcbiAgICAgICAgbmFtZTogJ3gxJyxcbiAgICAgICAgZHR5cGU6ICdjb21wbGV4NjQnLFxuICAgICAgICBzaGFwZTogWzJdLFxuICAgICAgfSxcbiAgICAgIHtcbiAgICAgICAgbmFtZTogJ3gyJyxcbiAgICAgICAgZHR5cGU6ICdjb21wbGV4NjQnLFxuICAgICAgICBzaGFwZTogW10sXG4gICAgICB9LFxuICAgICAge1xuICAgICAgICBuYW1lOiAneDMnLFxuICAgICAgICBkdHlwZTogJ2NvbXBsZXg2NCcsXG4gICAgICAgIHNoYXBlOiBbMSwgMV0sXG4gICAgICB9XG4gICAgXSk7XG4gIH0pO1xuICBpdCgnU3RyaW5nIHRlbnNvcnMnLCBhc3luYyAoKSA9PiB7XG4gICAgY29uc3QgdGVuc29yczogTmFtZWRUZW5zb3JNYXAgPSB7XG4gICAgICB4MTogdGVuc29yMmQoW1snYScsICdiYyddLCBbJ2RlZicsICdnJ11dLCBbMiwgMl0pLFxuICAgICAgeDI6IHNjYWxhcignJyksICAgICAgICAgICAgICAgICAgICAgICAvLyBFbXB0eSBzdHJpbmcuXG4gICAgICB4MzogdGVuc29yMWQoWyfQt9C00YDQsNCy0L4nLCAn0L/QvtC30LTRgNCw0LInXSksICAvLyBDeXJpbGxpYy5cbiAgICAgIHg0OiBzY2FsYXIoJ+ato+W4uCcpLCAgICAgICAgICAgICAgICAgICAvLyBFYXN0IEFzaWFuLlxuICAgICAgeDU6IHNjYWxhcignaGVsbG8nKSAgICAgICAgICAgICAgICAgICAvLyBTaW5nbGUgc3RyaW5nLlxuICAgIH07XG4gICAgY29uc3QgZGF0YUFuZFNwZWNzID0gYXdhaXQgdGYuaW8uZW5jb2RlV2VpZ2h0cyh0ZW5zb3JzKTtcbiAgICBjb25zdCBkYXRhID0gZGF0YUFuZFNwZWNzLmRhdGE7XG4gICAgY29uc3Qgc3BlY3MgPSBkYXRhQW5kU3BlY3Muc3BlY3M7XG4gICAgY29uc3QgeDFCeXRlTGVuZ3RoID0gNyArIDQgKiA0OyAgICAgICAvLyA3IGFzY2lpIGNoYXJzICsgNCBpbnRzLlxuICAgIGNvbnN0IHgyQnl0ZUxlbmd0aCA9IDQ7ICAgICAgICAgICAgICAgLy8gTm8gY2hhcnMgKyAxIGludC5cbiAgICBjb25zdCB4M0J5dGVMZW5ndGggPSAxMyAqIDIgKyAyICogNDsgIC8vIDEzIGN5cmlsbGljIGxldHRlcnMgKyAyIGludHMuXG4gICAgY29uc3QgeDRCeXRlTGVuZ3RoID0gNiArIDEgKiA0OyAgICAgICAvLyAyIGVhc3QgYXNpYW4gbGV0dGVycyArIDEgaW50LlxuICAgIGNvbnN0IHg1Qnl0ZUxlbmd0aCA9IDUgKyAxICogNDsgICAgICAgLy8gNSBhc2NpaSBjaGFycyArIDEgaW50LlxuICAgIGV4cGVjdChkYXRhLmJ5dGVMZW5ndGgpXG4gICAgICAgIC50b0VxdWFsKFxuICAgICAgICAgICAgeDFCeXRlTGVuZ3RoICsgeDJCeXRlTGVuZ3RoICsgeDNCeXRlTGVuZ3RoICsgeDRCeXRlTGVuZ3RoICtcbiAgICAgICAgICAgIHg1Qnl0ZUxlbmd0aCk7XG4gICAgLy8geDEgJ2EnLlxuICAgIGV4cGVjdChuZXcgVWludDMyQXJyYXkoZGF0YSwgMCwgMSlbMF0pLnRvQmUoMSk7XG4gICAgZXhwZWN0KG5ldyBVaW50OEFycmF5KGRhdGEsIDQsIDEpKS50b0VxdWFsKGVuY29kZVN0cmluZygnYScpKTtcbiAgICAvLyB4MSAnYmMnLlxuICAgIGV4cGVjdChuZXcgVWludDMyQXJyYXkoZGF0YS5zbGljZSg1LCA5KSlbMF0pLnRvQmUoMik7XG4gICAgZXhwZWN0KG5ldyBVaW50OEFycmF5KGRhdGEsIDksIDIpKS50b0VxdWFsKGVuY29kZVN0cmluZygnYmMnKSk7XG4gICAgLy8geDEgJ2RlZicuXG4gICAgZXhwZWN0KG5ldyBVaW50MzJBcnJheShkYXRhLnNsaWNlKDExLCAxNSkpWzBdKS50b0JlKDMpO1xuICAgIGV4cGVjdChuZXcgVWludDhBcnJheShkYXRhLCAxNSwgMykpLnRvRXF1YWwoZW5jb2RlU3RyaW5nKCdkZWYnKSk7XG4gICAgLy8geDEgJ2cnLlxuICAgIGV4cGVjdChuZXcgVWludDMyQXJyYXkoZGF0YS5zbGljZSgxOCwgMjIpKVswXSkudG9CZSgxKTtcbiAgICBleHBlY3QobmV3IFVpbnQ4QXJyYXkoZGF0YSwgMjIsIDEpKS50b0VxdWFsKGVuY29kZVN0cmluZygnZycpKTtcblxuICAgIC8vIHgyIGlzIGVtcHR5IHN0cmluZy5cbiAgICBleHBlY3QobmV3IFVpbnQzMkFycmF5KGRhdGEuc2xpY2UoMjMsIDI3KSlbMF0pLnRvQmUoMCk7XG5cbiAgICAvLyB4MyAn0LfQtNGA0LDQstC+Jy5cbiAgICBleHBlY3QobmV3IFVpbnQzMkFycmF5KGRhdGEuc2xpY2UoMjcsIDMxKSlbMF0pLnRvQmUoMTIpO1xuICAgIGV4cGVjdChuZXcgVWludDhBcnJheShkYXRhLCAzMSwgMTIpKS50b0VxdWFsKGVuY29kZVN0cmluZygn0LfQtNGA0LDQstC+JykpO1xuXG4gICAgLy8geDMgJ9C/0L7Qt9C00YDQsNCyJy5cbiAgICBleHBlY3QobmV3IFVpbnQzMkFycmF5KGRhdGEuc2xpY2UoNDMsIDQ3KSlbMF0pLnRvQmUoMTQpO1xuICAgIGV4cGVjdChuZXcgVWludDhBcnJheShkYXRhLCA0NywgMTQpKS50b0VxdWFsKGVuY29kZVN0cmluZygn0L/QvtC30LTRgNCw0LInKSk7XG5cbiAgICAvLyB4NCAn5q2j5bi4Jy5cbiAgICBleHBlY3QobmV3IFVpbnQzMkFycmF5KGRhdGEuc2xpY2UoNjEsIDY1KSlbMF0pLnRvQmUoNik7XG4gICAgZXhwZWN0KG5ldyBVaW50OEFycmF5KGRhdGEsIDY1LCA2KSkudG9FcXVhbChlbmNvZGVTdHJpbmcoJ+ato+W4uCcpKTtcblxuICAgIC8vIHg1ICdoZWxsbycuXG4gICAgZXhwZWN0KG5ldyBVaW50MzJBcnJheShkYXRhLnNsaWNlKDcxLCA3NSkpWzBdKS50b0JlKDUpO1xuICAgIGV4cGVjdChuZXcgVWludDhBcnJheShkYXRhLCA3NSwgNSkpLnRvRXF1YWwoZW5jb2RlU3RyaW5nKCdoZWxsbycpKTtcblxuICAgIGV4cGVjdChzcGVjcykudG9FcXVhbChbXG4gICAgICB7bmFtZTogJ3gxJywgZHR5cGU6ICdzdHJpbmcnLCBzaGFwZTogWzIsIDJdfSxcbiAgICAgIHtuYW1lOiAneDInLCBkdHlwZTogJ3N0cmluZycsIHNoYXBlOiBbXX0sXG4gICAgICB7bmFtZTogJ3gzJywgZHR5cGU6ICdzdHJpbmcnLCBzaGFwZTogWzJdfSxcbiAgICAgIHtuYW1lOiAneDQnLCBkdHlwZTogJ3N0cmluZycsIHNoYXBlOiBbXX0sXG4gICAgICB7bmFtZTogJ3g1JywgZHR5cGU6ICdzdHJpbmcnLCBzaGFwZTogW119XG4gICAgXSk7XG4gIH0pO1xuXG4gIGl0KCdNaXhlZCBkdHlwZSB0ZW5zb3JzJywgYXN5bmMgKCkgPT4ge1xuICAgIGNvbnN0IHRlbnNvcnM6IE5hbWVkVGVuc29yTWFwID0ge1xuICAgICAgeDE6IHRlbnNvcjJkKFtbMTAsIDIwXSwgWzMwLCA0MF1dLCBbMiwgMl0sICdpbnQzMicpLFxuICAgICAgeDI6IHNjYWxhcigxMy4zNywgJ2Zsb2F0MzInKSxcbiAgICAgIHgzOiB0ZW5zb3IxZChbdHJ1ZSwgZmFsc2UsIGZhbHNlLCB0cnVlXSwgJ2Jvb2wnKSxcbiAgICAgIHg0OiB0Zi5jb21wbGV4KFsxLCAxXSwgWzIsIDJdKVxuICAgIH07XG4gICAgY29uc3QgZGF0YUFuZFNwZWNzID0gYXdhaXQgdGYuaW8uZW5jb2RlV2VpZ2h0cyh0ZW5zb3JzKTtcbiAgICBjb25zdCBkYXRhID0gZGF0YUFuZFNwZWNzLmRhdGE7XG4gICAgY29uc3Qgc3BlY3MgPSBkYXRhQW5kU3BlY3Muc3BlY3M7XG4gICAgZXhwZWN0KGRhdGEuYnl0ZUxlbmd0aCkudG9FcXVhbCg0ICogNCArIDQgKiAxICsgMSAqIDQgKyA0ICogNCk7XG4gICAgZXhwZWN0KG5ldyBJbnQzMkFycmF5KGRhdGEsIDAsIDQpKS50b0VxdWFsKG5ldyBJbnQzMkFycmF5KFtcbiAgICAgIDEwLCAyMCwgMzAsIDQwXG4gICAgXSkpO1xuICAgIGV4cGVjdChuZXcgRmxvYXQzMkFycmF5KGRhdGEsIDE2LCAxKSkudG9FcXVhbChuZXcgRmxvYXQzMkFycmF5KFsxMy4zN10pKTtcbiAgICBleHBlY3QobmV3IFVpbnQ4QXJyYXkoZGF0YSwgMjAsIDQpKS50b0VxdWFsKG5ldyBVaW50OEFycmF5KFsxLCAwLCAwLCAxXSkpO1xuICAgIGV4cGVjdChuZXcgRmxvYXQzMkFycmF5KGRhdGEsIDI0LCA0KSkudG9FcXVhbChuZXcgRmxvYXQzMkFycmF5KFtcbiAgICAgIDEsIDIsIDEsIDJcbiAgICBdKSk7XG4gICAgZXhwZWN0KHNwZWNzKS50b0VxdWFsKFtcbiAgICAgIHtcbiAgICAgICAgbmFtZTogJ3gxJyxcbiAgICAgICAgZHR5cGU6ICdpbnQzMicsXG4gICAgICAgIHNoYXBlOiBbMiwgMl0sXG4gICAgICB9LFxuICAgICAge1xuICAgICAgICBuYW1lOiAneDInLFxuICAgICAgICBkdHlwZTogJ2Zsb2F0MzInLFxuICAgICAgICBzaGFwZTogW10sXG4gICAgICB9LFxuICAgICAge1xuICAgICAgICBuYW1lOiAneDMnLFxuICAgICAgICBkdHlwZTogJ2Jvb2wnLFxuICAgICAgICBzaGFwZTogWzRdLFxuICAgICAgfSxcbiAgICAgIHtcbiAgICAgICAgbmFtZTogJ3g0JyxcbiAgICAgICAgZHR5cGU6ICdjb21wbGV4NjQnLFxuICAgICAgICBzaGFwZTogWzJdLFxuICAgICAgfVxuICAgIF0pO1xuICB9KTtcbn0pO1xuXG5kZXNjcmliZVdpdGhGbGFncygnZGVjb2RlV2VpZ2h0cycsIHt9LCAoKSA9PiB7XG4gIGl0KCdNaXhlZCBkdHlwZSB0ZW5zb3JzJywgYXN5bmMgKCkgPT4ge1xuICAgIGNvbnN0IHRlbnNvcnM6IE5hbWVkVGVuc29yTWFwID0ge1xuICAgICAgeDE6IHRlbnNvcjJkKFtbMTAsIDIwXSwgWzMwLCA0MF1dLCBbMiwgMl0sICdpbnQzMicpLFxuICAgICAgeDI6IHNjYWxhcigxMy4zNywgJ2Zsb2F0MzInKSxcbiAgICAgIHgzOiB0ZW5zb3IxZChbdHJ1ZSwgZmFsc2UsIGZhbHNlXSwgJ2Jvb2wnKSxcbiAgICAgIHg0OiB0ZW5zb3IyZChbWyfQt9C00YDQsNCy0L4nLCAnYSddLCBbJ2InLCAnYyddXSwgWzIsIDJdLCAnc3RyaW5nJyksXG4gICAgICB4NTogdGVuc29yMWQoWycnXSwgJ3N0cmluZycpLCAgLy8gRW1wdHkgc3RyaW5nLlxuICAgICAgeDY6IHNjYWxhcignaGVsbG8nKSwgICAgICAgICAgIC8vIFNpbmdsZSBzdHJpbmcuXG4gICAgICB5MTogdGVuc29yMmQoWy0xMCwgLTIwLCAtMzBdLCBbMywgMV0sICdmbG9hdDMyJyksXG4gICAgICB5MjogdGYuY29tcGxleChbMSwgMV0sIFsyLCAyXSlcbiAgICB9O1xuICAgIGNvbnN0IGRhdGFBbmRTcGVjcyA9IGF3YWl0IHRmLmlvLmVuY29kZVdlaWdodHModGVuc29ycyk7XG4gICAgY29uc3QgZGF0YSA9IGRhdGFBbmRTcGVjcy5kYXRhO1xuICAgIGNvbnN0IHNwZWNzID0gZGF0YUFuZFNwZWNzLnNwZWNzO1xuICAgIGNvbnN0IGRlY29kZWQgPSB0Zi5pby5kZWNvZGVXZWlnaHRzKGRhdGEsIHNwZWNzKTtcbiAgICBleHBlY3QoT2JqZWN0LmtleXMoZGVjb2RlZCkubGVuZ3RoKS50b0VxdWFsKDgpO1xuICAgIGV4cGVjdEFycmF5c0VxdWFsKGF3YWl0IGRlY29kZWRbJ3gxJ10uZGF0YSgpLCBhd2FpdCB0ZW5zb3JzWyd4MSddLmRhdGEoKSk7XG4gICAgZXhwZWN0QXJyYXlzRXF1YWwoYXdhaXQgZGVjb2RlZFsneDInXS5kYXRhKCksIGF3YWl0IHRlbnNvcnNbJ3gyJ10uZGF0YSgpKTtcbiAgICBleHBlY3RBcnJheXNFcXVhbChhd2FpdCBkZWNvZGVkWyd4MyddLmRhdGEoKSwgYXdhaXQgdGVuc29yc1sneDMnXS5kYXRhKCkpO1xuICAgIGV4cGVjdEFycmF5c0VxdWFsKGF3YWl0IGRlY29kZWRbJ3g0J10uZGF0YSgpLCBhd2FpdCB0ZW5zb3JzWyd4NCddLmRhdGEoKSk7XG4gICAgZXhwZWN0QXJyYXlzRXF1YWwoYXdhaXQgZGVjb2RlZFsneDUnXS5kYXRhKCksIGF3YWl0IHRlbnNvcnNbJ3g1J10uZGF0YSgpKTtcbiAgICBleHBlY3RBcnJheXNFcXVhbChhd2FpdCBkZWNvZGVkWyd4NiddLmRhdGEoKSwgYXdhaXQgdGVuc29yc1sneDYnXS5kYXRhKCkpO1xuICAgIGV4cGVjdEFycmF5c0VxdWFsKGF3YWl0IGRlY29kZWRbJ3kxJ10uZGF0YSgpLCBhd2FpdCB0ZW5zb3JzWyd5MSddLmRhdGEoKSk7XG4gICAgZXhwZWN0QXJyYXlzRXF1YWwoYXdhaXQgZGVjb2RlZFsneTInXS5kYXRhKCksIGF3YWl0IHRlbnNvcnNbJ3kyJ10uZGF0YSgpKTtcbiAgfSk7XG5cbiAgaXQoJ1Vuc3VwcG9ydGVkIGR0eXBlIHJhaXNlcyBFcnJvcicsICgpID0+IHtcbiAgICBjb25zdCBidWZmZXIgPSBuZXcgQXJyYXlCdWZmZXIoNCk7XG4gICAgLy8gdHNsaW50OmRpc2FibGUtbmV4dC1saW5lOm5vLWFueVxuICAgIGNvbnN0IHNwZWNzOiBhbnkgPSBbXG4gICAgICB7XG4gICAgICAgIG5hbWU6ICd4JyxcbiAgICAgICAgZHR5cGU6ICdpbnQxNicsXG4gICAgICAgIHNoYXBlOiBbXSxcbiAgICAgIH0sXG4gICAgICB7bmFtZTogJ3knLCBkdHlwZTogJ2ludDE2Jywgc2hhcGU6IFtdfVxuICAgIF07XG4gICAgZXhwZWN0KCgpID0+IHRmLmlvLmRlY29kZVdlaWdodHMoYnVmZmVyLCBzcGVjcykpXG4gICAgICAgIC50b1Rocm93RXJyb3IoL1Vuc3VwcG9ydGVkIGR0eXBlIGluIHdlaWdodCBcXCd4XFwnOiBpbnQxNi8pO1xuICB9KTtcblxuICBpdCgnc3VwcG9ydCBxdWFudGl6YXRpb24gdWludDggd2VpZ2h0cycsIGFzeW5jICgpID0+IHtcbiAgICBjb25zdCBtYW5pZmVzdFNwZWNzOiBXZWlnaHRzTWFuaWZlc3RFbnRyeVtdID0gW1xuICAgICAge1xuICAgICAgICAnbmFtZSc6ICd3ZWlnaHQwJyxcbiAgICAgICAgJ2R0eXBlJzogJ2Zsb2F0MzInLFxuICAgICAgICAnc2hhcGUnOiBbM10sXG4gICAgICAgICdxdWFudGl6YXRpb24nOiB7J21pbic6IC0xLCAnc2NhbGUnOiAwLjEsICdkdHlwZSc6ICd1aW50OCd9XG4gICAgICB9LFxuICAgICAge1xuICAgICAgICAnbmFtZSc6ICd3ZWlnaHQxJyxcbiAgICAgICAgJ2R0eXBlJzogJ2ludDMyJyxcbiAgICAgICAgJ3NoYXBlJzogWzNdLFxuICAgICAgICAncXVhbnRpemF0aW9uJzogeydtaW4nOiAtMSwgJ3NjYWxlJzogMC4xLCAnZHR5cGUnOiAndWludDgnfVxuICAgICAgfVxuICAgIF07XG4gICAgY29uc3QgZGF0YSA9IG5ldyBVaW50OEFycmF5KFswLCA0OCwgMjU1LCAwLCA0OCwgMjU1XSk7XG4gICAgY29uc3QgZGVjb2RlZCA9IHRmLmlvLmRlY29kZVdlaWdodHMoZGF0YS5idWZmZXIsIG1hbmlmZXN0U3BlY3MpO1xuICAgIGNvbnN0IHdlaWdodDAgPSBkZWNvZGVkWyd3ZWlnaHQwJ107XG4gICAgZXhwZWN0QXJyYXlzQ2xvc2UoYXdhaXQgd2VpZ2h0MC5kYXRhKCksIFstMSwgMy44LCAyNC41XSk7XG4gICAgZXhwZWN0KHdlaWdodDAuc2hhcGUpLnRvRXF1YWwoWzNdKTtcbiAgICBleHBlY3Qod2VpZ2h0MC5kdHlwZSkudG9FcXVhbCgnZmxvYXQzMicpO1xuXG4gICAgY29uc3Qgd2VpZ2h0MSA9IGRlY29kZWRbJ3dlaWdodDEnXTtcbiAgICBleHBlY3RBcnJheXNFcXVhbChhd2FpdCB3ZWlnaHQxLmRhdGEoKSwgWy0xLCA0LCAyNV0pO1xuICAgIGV4cGVjdCh3ZWlnaHQxLnNoYXBlKS50b0VxdWFsKFszXSk7XG4gICAgZXhwZWN0KHdlaWdodDEuZHR5cGUpLnRvRXF1YWwoJ2ludDMyJyk7XG4gIH0pO1xuXG4gIGl0KCdzdXBwb3J0IHF1YW50aXphdGlvbiB1aW50MTYgd2VpZ2h0cycsIGFzeW5jICgpID0+IHtcbiAgICBjb25zdCBtYW5pZmVzdFNwZWNzOiBXZWlnaHRzTWFuaWZlc3RFbnRyeVtdID0gW1xuICAgICAge1xuICAgICAgICAnbmFtZSc6ICd3ZWlnaHQwJyxcbiAgICAgICAgJ2R0eXBlJzogJ2Zsb2F0MzInLFxuICAgICAgICAnc2hhcGUnOiBbM10sXG4gICAgICAgICdxdWFudGl6YXRpb24nOiB7J21pbic6IC0xLCAnc2NhbGUnOiAwLjEsICdkdHlwZSc6ICd1aW50MTYnfVxuICAgICAgfSxcbiAgICAgIHtcbiAgICAgICAgJ25hbWUnOiAnd2VpZ2h0MScsXG4gICAgICAgICdkdHlwZSc6ICdpbnQzMicsXG4gICAgICAgICdzaGFwZSc6IFszXSxcbiAgICAgICAgJ3F1YW50aXphdGlvbic6IHsnbWluJzogLTEsICdzY2FsZSc6IDAuMSwgJ2R0eXBlJzogJ3VpbnQxNid9XG4gICAgICB9XG4gICAgXTtcbiAgICBjb25zdCBkYXRhID0gbmV3IFVpbnQxNkFycmF5KFswLCA0OCwgMjU1LCAwLCA0OCwgMjU1XSk7XG4gICAgY29uc3QgZGVjb2RlZCA9IHRmLmlvLmRlY29kZVdlaWdodHMoZGF0YS5idWZmZXIsIG1hbmlmZXN0U3BlY3MpO1xuICAgIGNvbnN0IHdlaWdodDAgPSBkZWNvZGVkWyd3ZWlnaHQwJ107XG4gICAgZXhwZWN0QXJyYXlzQ2xvc2UoYXdhaXQgd2VpZ2h0MC5kYXRhKCksIFstMSwgMy44LCAyNC41XSk7XG4gICAgZXhwZWN0KHdlaWdodDAuc2hhcGUpLnRvRXF1YWwoWzNdKTtcbiAgICBleHBlY3Qod2VpZ2h0MC5kdHlwZSkudG9FcXVhbCgnZmxvYXQzMicpO1xuXG4gICAgY29uc3Qgd2VpZ2h0MSA9IGRlY29kZWRbJ3dlaWdodDEnXTtcbiAgICBleHBlY3RBcnJheXNFcXVhbChhd2FpdCB3ZWlnaHQxLmRhdGEoKSwgWy0xLCA0LCAyNV0pO1xuICAgIGV4cGVjdCh3ZWlnaHQxLnNoYXBlKS50b0VxdWFsKFszXSk7XG4gICAgZXhwZWN0KHdlaWdodDEuZHR5cGUpLnRvRXF1YWwoJ2ludDMyJyk7XG4gIH0pO1xuICBpdCgnc3VwcG9ydCBxdWFudGl6YXRpb24gZmxvYXQxNiB3ZWlnaHRzJywgYXN5bmMgKCkgPT4ge1xuICAgIGNvbnN0IG1hbmlmZXN0U3BlY3M6IFdlaWdodHNNYW5pZmVzdEVudHJ5W10gPSBbXG4gICAgICB7XG4gICAgICAgIG5hbWU6ICd3ZWlnaHQwJyxcbiAgICAgICAgZHR5cGU6ICdmbG9hdDMyJyxcbiAgICAgICAgc2hhcGU6IFszXSxcbiAgICAgICAgcXVhbnRpemF0aW9uOiB7IGR0eXBlOiAnZmxvYXQxNicgfSxcbiAgICAgIH0sXG4gICAgXTtcbiAgICBjb25zdCBkYXRhID0gbmV3IFVpbnQxNkFycmF5KFsxMzMxMiwgMTQzMzYsIDE0ODQ4XSk7XG4gICAgY29uc3QgZGVjb2RlZCA9IHRmLmlvLmRlY29kZVdlaWdodHMoZGF0YS5idWZmZXIsIG1hbmlmZXN0U3BlY3MpO1xuICAgIGNvbnN0IHdlaWdodDAgPSBkZWNvZGVkWyd3ZWlnaHQwJ107XG4gICAgZXhwZWN0QXJyYXlzQ2xvc2UoYXdhaXQgd2VpZ2h0MC5kYXRhKCksIFswLjI1LCAwLjUsIDAuNzVdKTtcbiAgICBleHBlY3Qod2VpZ2h0MC5zaGFwZSkudG9FcXVhbChbM10pO1xuICAgIGV4cGVjdCh3ZWlnaHQwLmR0eXBlKS50b0VxdWFsKCdmbG9hdDMyJyk7XG4gIH0pO1xufSk7XG5cbmRlc2NyaWJlKCdzdHJpbmdCeXRlTGVuZ3RoJywgKCkgPT4ge1xuICBpdCgnQVNDSUkgb25seScsICgpID0+IHtcbiAgICBjb25zdCBzdHIgPSAnX0xvcmVtIGlwc3VtIDEzMzchJztcbiAgICBleHBlY3Qoc3RyaW5nQnl0ZUxlbmd0aChzdHIpKS50b0VxdWFsKHN0ci5sZW5ndGgpO1xuICB9KTtcblxuICBpdCgnTWl4ZWQgbmFycm93IGFuZCB3aWRlIGNoYXJzJywgKCkgPT4ge1xuICAgIGNvbnN0IHN0ciA9ICdh0JbmlocxJztcbiAgICBleHBlY3Qoc3RyaW5nQnl0ZUxlbmd0aChzdHIuc2xpY2UoMCwgMSkpKS50b0VxdWFsKDEpO1xuICAgIGV4cGVjdChzdHJpbmdCeXRlTGVuZ3RoKHN0ci5zbGljZSgwLCAyKSkpLnRvRXF1YWwoMyk7XG4gICAgZXhwZWN0KHN0cmluZ0J5dGVMZW5ndGgoc3RyLnNsaWNlKDAsIDMpKSkudG9FcXVhbCg2KTtcbiAgICBleHBlY3Qoc3RyaW5nQnl0ZUxlbmd0aChzdHIuc2xpY2UoMCwgNCkpKS50b0VxdWFsKDcpO1xuICB9KTtcbn0pO1xuXG5kZXNjcmliZVdpdGhGbGFncyhcbiAgICAnYXJyYXlCdWZmZXJUb0Jhc2U2NFN0cmluZy1iYXNlNjRTdHJpbmdUb0FycmF5QnVmZmVyJywgQlJPV1NFUl9FTlZTLCAoKSA9PiB7XG4gICAgICBpdCgnUm91bmQgdHJpcCcsICgpID0+IHtcbiAgICAgICAgLy8gR2VuZXJhdGUgc29tZSBzZW1pLXJhbmRvbSBiaW5hcnkgZGF0YS5cbiAgICAgICAgY29uc3QgeCA9IFtdO1xuICAgICAgICBmb3IgKGxldCBrID0gMDsgayA8IDI7ICsraykge1xuICAgICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgMjU0OyArK2kpIHtcbiAgICAgICAgICAgIHgucHVzaChpICsgayk7XG4gICAgICAgICAgfVxuICAgICAgICAgIGZvciAobGV0IGkgPSAyNTQ7IGkgPj0gMDsgLS1pKSB7XG4gICAgICAgICAgICB4LnB1c2goaSArIGspO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBjb25zdCBidWZmZXIgPSBVaW50OEFycmF5LmZyb20oeCkuYnVmZmVyO1xuICAgICAgICBjb25zdCBiYXNlNjRTdHIgPSBhcnJheUJ1ZmZlclRvQmFzZTY0U3RyaW5nKGJ1ZmZlcik7XG4gICAgICAgIGNvbnN0IGRlY29kZWQgPVxuICAgICAgICAgICAgQXJyYXkuZnJvbShuZXcgVWludDhBcnJheShiYXNlNjRTdHJpbmdUb0FycmF5QnVmZmVyKGJhc2U2NFN0cikpKTtcbiAgICAgICAgZXhwZWN0KGRlY29kZWQpLnRvRXF1YWwoeCk7XG4gICAgICB9KTtcbiAgICB9KTtcblxuZGVzY3JpYmUoJ2NvbmNhdGVuYXRlQXJyYXlCdWZmZXJzJywgKCkgPT4ge1xuICAvLyBUT0RPKG1hdHRTb3VsYW5pbGxlKTogTW92ZSB0aGVzZSB0ZXN0cyB0byBDb21wb3NpdGVBcnJheUJ1ZmZlci5qb2luIHdoZW5cbiAgLy8gY29uY2F0ZW5hdGVBcnJheUJ1ZmZlcnMgaXMgcmVtb3ZlZC5cbiAgaXQoJ0NvbmNhdGVuYXRlIDMgbm9uLWVtcHR5IEFycmF5QnVmZmVycycsICgpID0+IHtcbiAgICBjb25zdCBidWZmZXIxID0gbmV3IFVpbnQ4QXJyYXkoWzEsIDIsIDNdKTtcbiAgICBjb25zdCBidWZmZXIyID0gbmV3IFVpbnQ4QXJyYXkoWzExLCAyMiwgMzMsIDQ0XSk7XG4gICAgY29uc3QgYnVmZmVyMyA9IG5ldyBVaW50OEFycmF5KFsxMTEsIDIyMiwgMTAwXSk7XG4gICAgY29uc3Qgb3V0ID0gY29uY2F0ZW5hdGVBcnJheUJ1ZmZlcnMoXG4gICAgICAgIFtidWZmZXIxLmJ1ZmZlciwgYnVmZmVyMi5idWZmZXIsIGJ1ZmZlcjMuYnVmZmVyXSk7XG4gICAgZXhwZWN0KG5ldyBVaW50OEFycmF5KG91dCkpLnRvRXF1YWwobmV3IFVpbnQ4QXJyYXkoW1xuICAgICAgMSwgMiwgMywgMTEsIDIyLCAzMywgNDQsIDExMSwgMjIyLCAxMDBcbiAgICBdKSk7XG4gIH0pO1xuXG4gIGl0KCdDb25jYXRlbmF0ZSBub24tZW1wdHkgYW5kIGVtcHR5IEFycmF5QnVmZmVycycsICgpID0+IHtcbiAgICBjb25zdCBidWZmZXIxID0gbmV3IFVpbnQ4QXJyYXkoWzEsIDIsIDNdKTtcbiAgICBjb25zdCBidWZmZXIyID0gbmV3IFVpbnQ4QXJyYXkoWzExLCAyMiwgMzMsIDQ0XSk7XG4gICAgY29uc3QgYnVmZmVyMyA9IG5ldyBVaW50OEFycmF5KFtdKTtcbiAgICBjb25zdCBidWZmZXI0ID0gbmV3IFVpbnQ4QXJyYXkoWzE1MCwgMTAwLCA1MF0pO1xuICAgIGNvbnN0IG91dCA9IGNvbmNhdGVuYXRlQXJyYXlCdWZmZXJzKFxuICAgICAgICBbYnVmZmVyMS5idWZmZXIsIGJ1ZmZlcjIuYnVmZmVyLCBidWZmZXIzLmJ1ZmZlciwgYnVmZmVyNC5idWZmZXJdKTtcbiAgICBleHBlY3QobmV3IFVpbnQ4QXJyYXkob3V0KSkudG9FcXVhbChuZXcgVWludDhBcnJheShbXG4gICAgICAxLCAyLCAzLCAxMSwgMjIsIDMzLCA0NCwgMTUwLCAxMDAsIDUwXG4gICAgXSkpO1xuICB9KTtcblxuICBpdCgnQSBzaW5nbGUgQXJyYXlCdWZmZXInLCAoKSA9PiB7XG4gICAgY29uc3QgYnVmZmVyMSA9IG5ldyBVaW50OEFycmF5KFsxLCAzLCAzLCA3XSk7XG4gICAgY29uc3Qgb3V0ID0gY29uY2F0ZW5hdGVBcnJheUJ1ZmZlcnMoW2J1ZmZlcjEuYnVmZmVyXSk7XG4gICAgZXhwZWN0KG5ldyBVaW50OEFycmF5KG91dCkpLnRvRXF1YWwoYnVmZmVyMSk7XG4gIH0pO1xuXG4gIGl0KCdaZXJvIEFycmF5QnVmZmVycycsICgpID0+IHtcbiAgICBleHBlY3QobmV3IFVpbnQ4QXJyYXkoY29uY2F0ZW5hdGVBcnJheUJ1ZmZlcnMoW10pKSlcbiAgICAgICAgLnRvRXF1YWwobmV3IFVpbnQ4QXJyYXkoW10pKTtcbiAgfSk7XG59KTtcblxuZGVzY3JpYmUoJ2Jhc2VuYW1lJywgKCkgPT4ge1xuICBpdCgnUGF0aHMgd2l0aG91dCBzbGFzaGVzJywgKCkgPT4ge1xuICAgIGV4cGVjdChiYXNlbmFtZSgnZm9vLnR4dCcpKS50b0VxdWFsKCdmb28udHh0Jyk7XG4gICAgZXhwZWN0KGJhc2VuYW1lKCdiYXInKSkudG9FcXVhbCgnYmFyJyk7XG4gIH0pO1xuXG4gIGl0KCdQYXRocyB3aXRoIHNsYXNoZXMnLCAoKSA9PiB7XG4gICAgZXhwZWN0KGJhc2VuYW1lKCdxdXgvZm9vLnR4dCcpKS50b0VxdWFsKCdmb28udHh0Jyk7XG4gICAgZXhwZWN0KGJhc2VuYW1lKCdxdXgvTXkgTW9kZWwuanNvbicpKS50b0VxdWFsKCdNeSBNb2RlbC5qc29uJyk7XG4gICAgZXhwZWN0KGJhc2VuYW1lKCdmb28vYmFyL2JheicpKS50b0VxdWFsKCdiYXonKTtcbiAgICBleHBlY3QoYmFzZW5hbWUoJy9mb28vYmFyL2JheicpKS50b0VxdWFsKCdiYXonKTtcbiAgICBleHBlY3QoYmFzZW5hbWUoJ2Zvby9iYXIvYmF6LycpKS50b0VxdWFsKCdiYXonKTtcbiAgICBleHBlY3QoYmFzZW5hbWUoJ2Zvby9iYXIvYmF6Ly8nKSkudG9FcXVhbCgnYmF6Jyk7XG4gIH0pO1xufSk7XG5cbmRlc2NyaWJlKCdmbG9hdDE2JywgKCkgPT4ge1xuICBpdCgnZGVjb2RlcyBOYU4gdG8gZmxvYXQzMiBOYU4nLCAoKSA9PiB7XG4gICAgY29uc3QgZGVjb2RlciA9IGdldEZsb2F0MTZEZWNvZGVyKCk7XG4gICAgY29uc3QgZmxvYXQxNk5hTiA9IDB4MDAwMDdlMDA7XG4gICAgY29uc3QgYnVmZmVyID0gbmV3IFVpbnQxNkFycmF5KFtmbG9hdDE2TmFOXSk7XG4gICAgY29uc3QgZjMyID0gZGVjb2RlcihidWZmZXIpO1xuICAgIGV4cGVjdChmMzIpLnRvRXF1YWwobmV3IEZsb2F0MzJBcnJheShbTmFOXSkpO1xuICB9KTtcblxuICBpdCgnZGVjb2RlcyDCsUluZmluaXR5IHRvIGZsb2F0MzIgwrFJbmZpbml0eScsICgpID0+IHtcbiAgICBjb25zdCBkZWNvZGVyID0gZ2V0RmxvYXQxNkRlY29kZXIoKTtcbiAgICBjb25zdCBwb3NpdGl2ZUluZmluaXR5ID0gMHgwMDAwN2MwMDtcbiAgICBjb25zdCBuZWdhdGl2ZUluZmluaXR5ID0gMHhmZmZmZmMwMDtcbiAgICBjb25zdCBidWZmZXIgPSBuZXcgVWludDE2QXJyYXkoW3Bvc2l0aXZlSW5maW5pdHksIG5lZ2F0aXZlSW5maW5pdHldKTtcbiAgICBjb25zdCBmMzIgPSBkZWNvZGVyKGJ1ZmZlcik7XG4gICAgZXhwZWN0KGYzMikudG9FcXVhbChuZXcgRmxvYXQzMkFycmF5KFtJbmZpbml0eSwgLUluZmluaXR5XSkpO1xuICB9KTtcblxuICBpdCgnZGVjb2RlcyDCsTAgdG8gZmxvYXQzMiDCsTAnLCAoKSA9PiB7XG4gICAgY29uc3QgZGVjb2RlciA9IGdldEZsb2F0MTZEZWNvZGVyKCk7XG4gICAgY29uc3QgcG9zaXRpdmVaZXJvID0gMHgwMDAwMDAwMDtcbiAgICBjb25zdCBuZWdhdGl2ZVplcm8gPSAweGZmZmY4MDAwO1xuICAgIGNvbnN0IGJ1ZmZlciA9IG5ldyBVaW50MTZBcnJheShbcG9zaXRpdmVaZXJvLCBuZWdhdGl2ZVplcm9dKTtcbiAgICBjb25zdCBmMzIgPSBkZWNvZGVyKGJ1ZmZlcik7XG4gICAgZXhwZWN0KGYzMikudG9FcXVhbChuZXcgRmxvYXQzMkFycmF5KFswLjAsIC0wLjBdKSk7XG4gIH0pO1xuXG4gIGl0KCdkZWNvZGVzIC1JbmZpbml0eSBvbiB1bmRlcmZsb3cnLCAoKSA9PiB7XG4gICAgY29uc3QgZGVjb2RlciA9IGdldEZsb2F0MTZEZWNvZGVyKCk7XG4gICAgY29uc3QgbWluVmFsID0gMHhmZmZmZmJmZjtcbiAgICBjb25zdCBidWZmZXIgPSBuZXcgVWludDE2QXJyYXkoW21pblZhbCArIDFdKTtcbiAgICBjb25zdCBmMzIgPSBkZWNvZGVyKGJ1ZmZlcik7XG4gICAgZXhwZWN0KGYzMikudG9FcXVhbChuZXcgRmxvYXQzMkFycmF5KFstSW5maW5pdHldKSk7XG4gIH0pO1xuXG4gIGl0KCdkZWNvZGVzICtJbmZpbml0eSBvbiBvdmVyZmxvdycsICgpID0+IHtcbiAgICBjb25zdCBkZWNvZGVyID0gZ2V0RmxvYXQxNkRlY29kZXIoKTtcbiAgICBjb25zdCBtYXhWYWwgPSAweDAwMDA3YmZmO1xuICAgIGNvbnN0IGJ1ZmZlciA9IG5ldyBVaW50MTZBcnJheShbbWF4VmFsICsgMV0pO1xuICAgIGNvbnN0IGYzMiA9IGRlY29kZXIoYnVmZmVyKTtcbiAgICBleHBlY3QoZjMyKS50b0VxdWFsKG5ldyBGbG9hdDMyQXJyYXkoW0luZmluaXR5XSkpO1xuICB9KTtcblxuICBpdCgnZGVjb2RlcyBpbnRlcnByZXRhYmxlIGZsb2F0MTYgdG8gZmxvYXQzMicsICgpID0+IHtcbiAgICBjb25zdCBkZWNvZGVyID0gZ2V0RmxvYXQxNkRlY29kZXIoKTtcbiAgICBjb25zdCBidWZmZXIgPSBuZXcgVWludDE2QXJyYXkoW1xuICAgICAgMHgwMDAwMzQwMCxcbiAgICAgIDB4MDAwMDM4MDAsXG4gICAgICAweDAwMDAzQTAwLFxuICAgICAgMHgwMDAwMzU1NVxuICAgIF0pO1xuICAgIGNvbnN0IGYzMiA9IGRlY29kZXIoYnVmZmVyKTtcbiAgICBleHBlY3QoZjMyWzBdKS50b0JlQ2xvc2VUbygwLjI1KTtcbiAgICBleHBlY3QoZjMyWzFdKS50b0JlQ2xvc2VUbygwLjUpO1xuICAgIGV4cGVjdChmMzJbMl0pLnRvQmVDbG9zZVRvKDAuNzUpO1xuICAgIGV4cGVjdChmMzJbM10pLnRvQmVDbG9zZVRvKDAuMzMzKTtcbiAgfSk7XG59KTtcbiJdfQ==