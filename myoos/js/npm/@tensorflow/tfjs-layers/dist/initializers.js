/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
import { eye, linalg, mul, ones, randomUniform, scalar, serialization, tidy, truncatedNormal, util, zeros } from '@tensorflow/tfjs-core';
import * as K from './backend/tfjs_backend';
import { checkDataFormat } from './common';
import { NotImplementedError, ValueError } from './errors';
import { VALID_DISTRIBUTION_VALUES, VALID_FAN_MODE_VALUES } from './keras_format/initializer_config';
import { checkStringTypeUnionValue, deserializeKerasObject, serializeKerasObject } from './utils/generic_utils';
import { arrayProd } from './utils/math_utils';
export function checkFanMode(value) {
    checkStringTypeUnionValue(VALID_FAN_MODE_VALUES, 'FanMode', value);
}
export function checkDistribution(value) {
    checkStringTypeUnionValue(VALID_DISTRIBUTION_VALUES, 'Distribution', value);
}
/**
 * Initializer base class.
 *
 * @doc {
 *   heading: 'Initializers', subheading: 'Classes', namespace: 'initializers'}
 */
export class Initializer extends serialization.Serializable {
    fromConfigUsesCustomObjects() {
        return false;
    }
    getConfig() {
        return {};
    }
}
export class Zeros extends Initializer {
    apply(shape, dtype) {
        return zeros(shape, dtype);
    }
}
/** @nocollapse */
Zeros.className = 'Zeros';
serialization.registerClass(Zeros);
export class Ones extends Initializer {
    apply(shape, dtype) {
        return ones(shape, dtype);
    }
}
/** @nocollapse */
Ones.className = 'Ones';
serialization.registerClass(Ones);
export class Constant extends Initializer {
    constructor(args) {
        super();
        if (typeof args !== 'object') {
            throw new ValueError(`Expected argument of type ConstantConfig but got ${args}`);
        }
        if (args.value === undefined) {
            throw new ValueError(`config must have value set but got ${args}`);
        }
        this.value = args.value;
    }
    apply(shape, dtype) {
        return tidy(() => mul(scalar(this.value), ones(shape, dtype)));
    }
    getConfig() {
        return {
            value: this.value,
        };
    }
}
/** @nocollapse */
Constant.className = 'Constant';
serialization.registerClass(Constant);
export class RandomUniform extends Initializer {
    constructor(args) {
        super();
        this.DEFAULT_MINVAL = -0.05;
        this.DEFAULT_MAXVAL = 0.05;
        this.minval = args.minval || this.DEFAULT_MINVAL;
        this.maxval = args.maxval || this.DEFAULT_MAXVAL;
        this.seed = args.seed;
    }
    apply(shape, dtype) {
        return randomUniform(shape, this.minval, this.maxval, dtype, this.seed);
    }
    getConfig() {
        return { minval: this.minval, maxval: this.maxval, seed: this.seed };
    }
}
/** @nocollapse */
RandomUniform.className = 'RandomUniform';
serialization.registerClass(RandomUniform);
export class RandomNormal extends Initializer {
    constructor(args) {
        super();
        this.DEFAULT_MEAN = 0.;
        this.DEFAULT_STDDEV = 0.05;
        this.mean = args.mean || this.DEFAULT_MEAN;
        this.stddev = args.stddev || this.DEFAULT_STDDEV;
        this.seed = args.seed;
    }
    apply(shape, dtype) {
        dtype = dtype || 'float32';
        if (dtype !== 'float32' && dtype !== 'int32') {
            throw new NotImplementedError(`randomNormal does not support dType ${dtype}.`);
        }
        return K.randomNormal(shape, this.mean, this.stddev, dtype, this.seed);
    }
    getConfig() {
        return { mean: this.mean, stddev: this.stddev, seed: this.seed };
    }
}
/** @nocollapse */
RandomNormal.className = 'RandomNormal';
serialization.registerClass(RandomNormal);
export class TruncatedNormal extends Initializer {
    constructor(args) {
        super();
        this.DEFAULT_MEAN = 0.;
        this.DEFAULT_STDDEV = 0.05;
        this.mean = args.mean || this.DEFAULT_MEAN;
        this.stddev = args.stddev || this.DEFAULT_STDDEV;
        this.seed = args.seed;
    }
    apply(shape, dtype) {
        dtype = dtype || 'float32';
        if (dtype !== 'float32' && dtype !== 'int32') {
            throw new NotImplementedError(`truncatedNormal does not support dType ${dtype}.`);
        }
        return truncatedNormal(shape, this.mean, this.stddev, dtype, this.seed);
    }
    getConfig() {
        return { mean: this.mean, stddev: this.stddev, seed: this.seed };
    }
}
/** @nocollapse */
TruncatedNormal.className = 'TruncatedNormal';
serialization.registerClass(TruncatedNormal);
export class Identity extends Initializer {
    constructor(args) {
        super();
        this.gain = args.gain != null ? args.gain : 1.0;
    }
    apply(shape, dtype) {
        return tidy(() => {
            if (shape.length !== 2 || shape[0] !== shape[1]) {
                throw new ValueError('Identity matrix initializer can only be used for' +
                    ' 2D square matrices.');
            }
            else {
                return mul(this.gain, eye(shape[0]));
            }
        });
    }
    getConfig() {
        return { gain: this.gain };
    }
}
/** @nocollapse */
Identity.className = 'Identity';
serialization.registerClass(Identity);
/**
 * Computes the number of input and output units for a weight shape.
 * @param shape Shape of weight.
 * @param dataFormat data format to use for convolution kernels.
 *   Note that all kernels in Keras are standardized on the
 *   CHANNEL_LAST ordering (even when inputs are set to CHANNEL_FIRST).
 * @return An length-2 array: fanIn, fanOut.
 */
function computeFans(shape, dataFormat = 'channelsLast') {
    let fanIn;
    let fanOut;
    checkDataFormat(dataFormat);
    if (shape.length === 2) {
        fanIn = shape[0];
        fanOut = shape[1];
    }
    else if ([3, 4, 5].indexOf(shape.length) !== -1) {
        if (dataFormat === 'channelsFirst') {
            const receptiveFieldSize = arrayProd(shape, 2);
            fanIn = shape[1] * receptiveFieldSize;
            fanOut = shape[0] * receptiveFieldSize;
        }
        else if (dataFormat === 'channelsLast') {
            const receptiveFieldSize = arrayProd(shape, 0, shape.length - 2);
            fanIn = shape[shape.length - 2] * receptiveFieldSize;
            fanOut = shape[shape.length - 1] * receptiveFieldSize;
        }
    }
    else {
        const shapeProd = arrayProd(shape);
        fanIn = Math.sqrt(shapeProd);
        fanOut = Math.sqrt(shapeProd);
    }
    return [fanIn, fanOut];
}
export class VarianceScaling extends Initializer {
    /**
     * Constructor of VarianceScaling.
     * @throws ValueError for invalid value in scale.
     */
    constructor(args) {
        super();
        if (args.scale < 0.0) {
            throw new ValueError(`scale must be a positive float. Got: ${args.scale}`);
        }
        this.scale = args.scale == null ? 1.0 : args.scale;
        this.mode = args.mode == null ? 'fanIn' : args.mode;
        checkFanMode(this.mode);
        this.distribution =
            args.distribution == null ? 'normal' : args.distribution;
        checkDistribution(this.distribution);
        this.seed = args.seed;
    }
    apply(shape, dtype) {
        const fans = computeFans(shape);
        const fanIn = fans[0];
        const fanOut = fans[1];
        let scale = this.scale;
        if (this.mode === 'fanIn') {
            scale /= Math.max(1, fanIn);
        }
        else if (this.mode === 'fanOut') {
            scale /= Math.max(1, fanOut);
        }
        else {
            scale /= Math.max(1, (fanIn + fanOut) / 2);
        }
        if (this.distribution === 'normal') {
            const stddev = Math.sqrt(scale);
            dtype = dtype || 'float32';
            if (dtype !== 'float32' && dtype !== 'int32') {
                throw new NotImplementedError(`${this.getClassName()} does not support dType ${dtype}.`);
            }
            return truncatedNormal(shape, 0, stddev, dtype, this.seed);
        }
        else {
            const limit = Math.sqrt(3 * scale);
            return randomUniform(shape, -limit, limit, dtype, this.seed);
        }
    }
    getConfig() {
        return {
            scale: this.scale,
            mode: this.mode,
            distribution: this.distribution,
            seed: this.seed
        };
    }
}
/** @nocollapse */
VarianceScaling.className = 'VarianceScaling';
serialization.registerClass(VarianceScaling);
export class GlorotUniform extends VarianceScaling {
    /**
     * Constructor of GlorotUniform
     * @param scale
     * @param mode
     * @param distribution
     * @param seed
     */
    constructor(args) {
        super({
            scale: 1.0,
            mode: 'fanAvg',
            distribution: 'uniform',
            seed: args == null ? null : args.seed
        });
    }
    getClassName() {
        // In Python Keras, GlorotUniform is not a class, but a helper method
        // that creates a VarianceScaling object. Use 'VarianceScaling' as
        // class name to be compatible with that.
        return VarianceScaling.className;
    }
}
/** @nocollapse */
GlorotUniform.className = 'GlorotUniform';
serialization.registerClass(GlorotUniform);
export class GlorotNormal extends VarianceScaling {
    /**
     * Constructor of GlorotNormal.
     * @param scale
     * @param mode
     * @param distribution
     * @param seed
     */
    constructor(args) {
        super({
            scale: 1.0,
            mode: 'fanAvg',
            distribution: 'normal',
            seed: args == null ? null : args.seed
        });
    }
    getClassName() {
        // In Python Keras, GlorotNormal is not a class, but a helper method
        // that creates a VarianceScaling object. Use 'VarianceScaling' as
        // class name to be compatible with that.
        return VarianceScaling.className;
    }
}
/** @nocollapse */
GlorotNormal.className = 'GlorotNormal';
serialization.registerClass(GlorotNormal);
export class HeNormal extends VarianceScaling {
    constructor(args) {
        super({
            scale: 2.0,
            mode: 'fanIn',
            distribution: 'normal',
            seed: args == null ? null : args.seed
        });
    }
    getClassName() {
        // In Python Keras, HeNormal is not a class, but a helper method
        // that creates a VarianceScaling object. Use 'VarianceScaling' as
        // class name to be compatible with that.
        return VarianceScaling.className;
    }
}
/** @nocollapse */
HeNormal.className = 'HeNormal';
serialization.registerClass(HeNormal);
export class HeUniform extends VarianceScaling {
    constructor(args) {
        super({
            scale: 2.0,
            mode: 'fanIn',
            distribution: 'uniform',
            seed: args == null ? null : args.seed
        });
    }
    getClassName() {
        // In Python Keras, HeUniform is not a class, but a helper method
        // that creates a VarianceScaling object. Use 'VarianceScaling' as
        // class name to be compatible with that.
        return VarianceScaling.className;
    }
}
/** @nocollapse */
HeUniform.className = 'HeUniform';
serialization.registerClass(HeUniform);
export class LeCunNormal extends VarianceScaling {
    constructor(args) {
        super({
            scale: 1.0,
            mode: 'fanIn',
            distribution: 'normal',
            seed: args == null ? null : args.seed
        });
    }
    getClassName() {
        // In Python Keras, LeCunNormal is not a class, but a helper method
        // that creates a VarianceScaling object. Use 'VarianceScaling' as
        // class name to be compatible with that.
        return VarianceScaling.className;
    }
}
/** @nocollapse */
LeCunNormal.className = 'LeCunNormal';
serialization.registerClass(LeCunNormal);
export class LeCunUniform extends VarianceScaling {
    constructor(args) {
        super({
            scale: 1.0,
            mode: 'fanIn',
            distribution: 'uniform',
            seed: args == null ? null : args.seed
        });
    }
    getClassName() {
        // In Python Keras, LeCunUniform is not a class, but a helper method
        // that creates a VarianceScaling object. Use 'VarianceScaling' as
        // class name to be compatible with that.
        return VarianceScaling.className;
    }
}
/** @nocollapse */
LeCunUniform.className = 'LeCunUniform';
serialization.registerClass(LeCunUniform);
export class Orthogonal extends Initializer {
    constructor(args) {
        super();
        this.DEFAULT_GAIN = 1;
        this.ELEMENTS_WARN_SLOW = 2000;
        this.gain = args.gain == null ? this.DEFAULT_GAIN : args.gain;
        this.seed = args.seed;
    }
    apply(shape, dtype) {
        return tidy(() => {
            if (shape.length < 2) {
                throw new NotImplementedError('Shape must be at least 2D.');
            }
            if (dtype !== 'int32' && dtype !== 'float32' && dtype !== undefined) {
                throw new TypeError(`Unsupported data type ${dtype}.`);
            }
            dtype = dtype;
            // flatten the input shape with the last dimension remaining its
            // original shape so it works for conv2d
            const numRows = util.sizeFromShape(shape.slice(0, -1));
            const numCols = shape[shape.length - 1];
            const numElements = numRows * numCols;
            if (numElements > this.ELEMENTS_WARN_SLOW) {
                console.warn(`Orthogonal initializer is being called on a matrix with more ` +
                    `than ${this.ELEMENTS_WARN_SLOW} (${numElements}) elements: ` +
                    `Slowness may result.`);
            }
            const flatShape = [Math.max(numCols, numRows), Math.min(numCols, numRows)];
            // Generate a random matrix
            const randNormalMat = K.randomNormal(flatShape, 0, 1, dtype, this.seed);
            // Compute QR factorization
            const qr = linalg.qr(randNormalMat, false);
            let qMat = qr[0];
            const rMat = qr[1];
            // Make Q uniform
            const diag = rMat.flatten().stridedSlice([0], [Math.min(numCols, numRows) * Math.min(numCols, numRows)], [Math.min(numCols, numRows) + 1]);
            qMat = mul(qMat, diag.sign());
            if (numRows < numCols) {
                qMat = qMat.transpose();
            }
            return mul(scalar(this.gain), qMat.reshape(shape));
        });
    }
    getConfig() {
        return {
            gain: this.gain,
            seed: this.seed,
        };
    }
}
/** @nocollapse */
Orthogonal.className = 'Orthogonal';
serialization.registerClass(Orthogonal);
// Maps the JavaScript-like identifier keys to the corresponding registry
// symbols.
export const INITIALIZER_IDENTIFIER_REGISTRY_SYMBOL_MAP = {
    'constant': 'Constant',
    'glorotNormal': 'GlorotNormal',
    'glorotUniform': 'GlorotUniform',
    'heNormal': 'HeNormal',
    'heUniform': 'HeUniform',
    'identity': 'Identity',
    'leCunNormal': 'LeCunNormal',
    'leCunUniform': 'LeCunUniform',
    'ones': 'Ones',
    'orthogonal': 'Orthogonal',
    'randomNormal': 'RandomNormal',
    'randomUniform': 'RandomUniform',
    'truncatedNormal': 'TruncatedNormal',
    'varianceScaling': 'VarianceScaling',
    'zeros': 'Zeros'
};
function deserializeInitializer(config, customObjects = {}) {
    return deserializeKerasObject(config, serialization.SerializationMap.getMap().classNameMap, customObjects, 'initializer');
}
export function serializeInitializer(initializer) {
    return serializeKerasObject(initializer);
}
export function getInitializer(identifier) {
    if (typeof identifier === 'string') {
        const className = identifier in INITIALIZER_IDENTIFIER_REGISTRY_SYMBOL_MAP ?
            INITIALIZER_IDENTIFIER_REGISTRY_SYMBOL_MAP[identifier] :
            identifier;
        /* We have four 'helper' classes for common initializers that
        all get serialized as 'VarianceScaling' and shouldn't go through
        the deserializeInitializer pathway. */
        if (className === 'GlorotNormal') {
            return new GlorotNormal();
        }
        else if (className === 'GlorotUniform') {
            return new GlorotUniform();
        }
        else if (className === 'HeNormal') {
            return new HeNormal();
        }
        else if (className === 'HeUniform') {
            return new HeUniform();
        }
        else if (className === 'LeCunNormal') {
            return new LeCunNormal();
        }
        else if (className === 'LeCunUniform') {
            return new LeCunUniform();
        }
        else {
            const config = {};
            config['className'] = className;
            config['config'] = {};
            return deserializeInitializer(config);
        }
    }
    else if (identifier instanceof Initializer) {
        return identifier;
    }
    else {
        return deserializeInitializer(identifier);
    }
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiaW5pdGlhbGl6ZXJzLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsiLi4vLi4vLi4vLi4vLi4vdGZqcy1sYXllcnMvc3JjL2luaXRpYWxpemVycy50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7R0FRRztBQUVILE9BQU8sRUFBVyxHQUFHLEVBQUUsTUFBTSxFQUFFLEdBQUcsRUFBRSxJQUFJLEVBQUUsYUFBYSxFQUFFLE1BQU0sRUFBRSxhQUFhLEVBQVUsSUFBSSxFQUFFLGVBQWUsRUFBRSxJQUFJLEVBQUUsS0FBSyxFQUFDLE1BQU0sdUJBQXVCLENBQUM7QUFFekosT0FBTyxLQUFLLENBQUMsTUFBTSx3QkFBd0IsQ0FBQztBQUM1QyxPQUFPLEVBQUMsZUFBZSxFQUFDLE1BQU0sVUFBVSxDQUFDO0FBQ3pDLE9BQU8sRUFBQyxtQkFBbUIsRUFBRSxVQUFVLEVBQUMsTUFBTSxVQUFVLENBQUM7QUFFekQsT0FBTyxFQUF3Qix5QkFBeUIsRUFBRSxxQkFBcUIsRUFBQyxNQUFNLG1DQUFtQyxDQUFDO0FBQzFILE9BQU8sRUFBQyx5QkFBeUIsRUFBRSxzQkFBc0IsRUFBRSxvQkFBb0IsRUFBQyxNQUFNLHVCQUF1QixDQUFDO0FBQzlHLE9BQU8sRUFBQyxTQUFTLEVBQUMsTUFBTSxvQkFBb0IsQ0FBQztBQUU3QyxNQUFNLFVBQVUsWUFBWSxDQUFDLEtBQWM7SUFDekMseUJBQXlCLENBQUMscUJBQXFCLEVBQUUsU0FBUyxFQUFFLEtBQUssQ0FBQyxDQUFDO0FBQ3JFLENBQUM7QUFFRCxNQUFNLFVBQVUsaUJBQWlCLENBQUMsS0FBYztJQUM5Qyx5QkFBeUIsQ0FBQyx5QkFBeUIsRUFBRSxjQUFjLEVBQUUsS0FBSyxDQUFDLENBQUM7QUFDOUUsQ0FBQztBQUVEOzs7OztHQUtHO0FBQ0gsTUFBTSxPQUFnQixXQUFZLFNBQVEsYUFBYSxDQUFDLFlBQVk7SUFDM0QsMkJBQTJCO1FBQ2hDLE9BQU8sS0FBSyxDQUFDO0lBQ2YsQ0FBQztJQVNELFNBQVM7UUFDUCxPQUFPLEVBQUUsQ0FBQztJQUNaLENBQUM7Q0FDRjtBQUVELE1BQU0sT0FBTyxLQUFNLFNBQVEsV0FBVztJQUlwQyxLQUFLLENBQUMsS0FBWSxFQUFFLEtBQWdCO1FBQ2xDLE9BQU8sS0FBSyxDQUFDLEtBQUssRUFBRSxLQUFLLENBQUMsQ0FBQztJQUM3QixDQUFDOztBQUxELGtCQUFrQjtBQUNYLGVBQVMsR0FBRyxPQUFPLENBQUM7QUFNN0IsYUFBYSxDQUFDLGFBQWEsQ0FBQyxLQUFLLENBQUMsQ0FBQztBQUVuQyxNQUFNLE9BQU8sSUFBSyxTQUFRLFdBQVc7SUFJbkMsS0FBSyxDQUFDLEtBQVksRUFBRSxLQUFnQjtRQUNsQyxPQUFPLElBQUksQ0FBQyxLQUFLLEVBQUUsS0FBSyxDQUFDLENBQUM7SUFDNUIsQ0FBQzs7QUFMRCxrQkFBa0I7QUFDWCxjQUFTLEdBQUcsTUFBTSxDQUFDO0FBTTVCLGFBQWEsQ0FBQyxhQUFhLENBQUMsSUFBSSxDQUFDLENBQUM7QUFPbEMsTUFBTSxPQUFPLFFBQVMsU0FBUSxXQUFXO0lBSXZDLFlBQVksSUFBa0I7UUFDNUIsS0FBSyxFQUFFLENBQUM7UUFDUixJQUFJLE9BQU8sSUFBSSxLQUFLLFFBQVEsRUFBRTtZQUM1QixNQUFNLElBQUksVUFBVSxDQUNoQixvREFBb0QsSUFBSSxFQUFFLENBQUMsQ0FBQztTQUNqRTtRQUNELElBQUksSUFBSSxDQUFDLEtBQUssS0FBSyxTQUFTLEVBQUU7WUFDNUIsTUFBTSxJQUFJLFVBQVUsQ0FBQyxzQ0FBc0MsSUFBSSxFQUFFLENBQUMsQ0FBQztTQUNwRTtRQUNELElBQUksQ0FBQyxLQUFLLEdBQUcsSUFBSSxDQUFDLEtBQUssQ0FBQztJQUMxQixDQUFDO0lBRUQsS0FBSyxDQUFDLEtBQVksRUFBRSxLQUFnQjtRQUNsQyxPQUFPLElBQUksQ0FBQyxHQUFHLEVBQUUsQ0FBQyxHQUFHLENBQUMsTUFBTSxDQUFDLElBQUksQ0FBQyxLQUFLLENBQUMsRUFBRSxJQUFJLENBQUMsS0FBSyxFQUFFLEtBQUssQ0FBQyxDQUFDLENBQUMsQ0FBQztJQUNqRSxDQUFDO0lBRVEsU0FBUztRQUNoQixPQUFPO1lBQ0wsS0FBSyxFQUFFLElBQUksQ0FBQyxLQUFLO1NBQ2xCLENBQUM7SUFDSixDQUFDOztBQXZCRCxrQkFBa0I7QUFDWCxrQkFBUyxHQUFHLFVBQVUsQ0FBQztBQXdCaEMsYUFBYSxDQUFDLGFBQWEsQ0FBQyxRQUFRLENBQUMsQ0FBQztBQVd0QyxNQUFNLE9BQU8sYUFBYyxTQUFRLFdBQVc7SUFTNUMsWUFBWSxJQUF1QjtRQUNqQyxLQUFLLEVBQUUsQ0FBQztRQVBELG1CQUFjLEdBQUcsQ0FBQyxJQUFJLENBQUM7UUFDdkIsbUJBQWMsR0FBRyxJQUFJLENBQUM7UUFPN0IsSUFBSSxDQUFDLE1BQU0sR0FBRyxJQUFJLENBQUMsTUFBTSxJQUFJLElBQUksQ0FBQyxjQUFjLENBQUM7UUFDakQsSUFBSSxDQUFDLE1BQU0sR0FBRyxJQUFJLENBQUMsTUFBTSxJQUFJLElBQUksQ0FBQyxjQUFjLENBQUM7UUFDakQsSUFBSSxDQUFDLElBQUksR0FBRyxJQUFJLENBQUMsSUFBSSxDQUFDO0lBQ3hCLENBQUM7SUFFRCxLQUFLLENBQUMsS0FBWSxFQUFFLEtBQWdCO1FBQ2xDLE9BQU8sYUFBYSxDQUFDLEtBQUssRUFBRSxJQUFJLENBQUMsTUFBTSxFQUFFLElBQUksQ0FBQyxNQUFNLEVBQUUsS0FBSyxFQUFFLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQztJQUMxRSxDQUFDO0lBRVEsU0FBUztRQUNoQixPQUFPLEVBQUMsTUFBTSxFQUFFLElBQUksQ0FBQyxNQUFNLEVBQUUsTUFBTSxFQUFFLElBQUksQ0FBQyxNQUFNLEVBQUUsSUFBSSxFQUFFLElBQUksQ0FBQyxJQUFJLEVBQUMsQ0FBQztJQUNyRSxDQUFDOztBQXJCRCxrQkFBa0I7QUFDWCx1QkFBUyxHQUFHLGVBQWUsQ0FBQztBQXNCckMsYUFBYSxDQUFDLGFBQWEsQ0FBQyxhQUFhLENBQUMsQ0FBQztBQVczQyxNQUFNLE9BQU8sWUFBYSxTQUFRLFdBQVc7SUFTM0MsWUFBWSxJQUFzQjtRQUNoQyxLQUFLLEVBQUUsQ0FBQztRQVBELGlCQUFZLEdBQUcsRUFBRSxDQUFDO1FBQ2xCLG1CQUFjLEdBQUcsSUFBSSxDQUFDO1FBTzdCLElBQUksQ0FBQyxJQUFJLEdBQUcsSUFBSSxDQUFDLElBQUksSUFBSSxJQUFJLENBQUMsWUFBWSxDQUFDO1FBQzNDLElBQUksQ0FBQyxNQUFNLEdBQUcsSUFBSSxDQUFDLE1BQU0sSUFBSSxJQUFJLENBQUMsY0FBYyxDQUFDO1FBQ2pELElBQUksQ0FBQyxJQUFJLEdBQUcsSUFBSSxDQUFDLElBQUksQ0FBQztJQUN4QixDQUFDO0lBRUQsS0FBSyxDQUFDLEtBQVksRUFBRSxLQUFnQjtRQUNsQyxLQUFLLEdBQUcsS0FBSyxJQUFJLFNBQVMsQ0FBQztRQUMzQixJQUFJLEtBQUssS0FBSyxTQUFTLElBQUksS0FBSyxLQUFLLE9BQU8sRUFBRTtZQUM1QyxNQUFNLElBQUksbUJBQW1CLENBQ3pCLHVDQUF1QyxLQUFLLEdBQUcsQ0FBQyxDQUFDO1NBQ3REO1FBRUQsT0FBTyxDQUFDLENBQUMsWUFBWSxDQUFDLEtBQUssRUFBRSxJQUFJLENBQUMsSUFBSSxFQUFFLElBQUksQ0FBQyxNQUFNLEVBQUUsS0FBSyxFQUFFLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQztJQUN6RSxDQUFDO0lBRVEsU0FBUztRQUNoQixPQUFPLEVBQUMsSUFBSSxFQUFFLElBQUksQ0FBQyxJQUFJLEVBQUUsTUFBTSxFQUFFLElBQUksQ0FBQyxNQUFNLEVBQUUsSUFBSSxFQUFFLElBQUksQ0FBQyxJQUFJLEVBQUMsQ0FBQztJQUNqRSxDQUFDOztBQTNCRCxrQkFBa0I7QUFDWCxzQkFBUyxHQUFHLGNBQWMsQ0FBQztBQTRCcEMsYUFBYSxDQUFDLGFBQWEsQ0FBQyxZQUFZLENBQUMsQ0FBQztBQVcxQyxNQUFNLE9BQU8sZUFBZ0IsU0FBUSxXQUFXO0lBVTlDLFlBQVksSUFBeUI7UUFDbkMsS0FBSyxFQUFFLENBQUM7UUFQRCxpQkFBWSxHQUFHLEVBQUUsQ0FBQztRQUNsQixtQkFBYyxHQUFHLElBQUksQ0FBQztRQU83QixJQUFJLENBQUMsSUFBSSxHQUFHLElBQUksQ0FBQyxJQUFJLElBQUksSUFBSSxDQUFDLFlBQVksQ0FBQztRQUMzQyxJQUFJLENBQUMsTUFBTSxHQUFHLElBQUksQ0FBQyxNQUFNLElBQUksSUFBSSxDQUFDLGNBQWMsQ0FBQztRQUNqRCxJQUFJLENBQUMsSUFBSSxHQUFHLElBQUksQ0FBQyxJQUFJLENBQUM7SUFDeEIsQ0FBQztJQUVELEtBQUssQ0FBQyxLQUFZLEVBQUUsS0FBZ0I7UUFDbEMsS0FBSyxHQUFHLEtBQUssSUFBSSxTQUFTLENBQUM7UUFDM0IsSUFBSSxLQUFLLEtBQUssU0FBUyxJQUFJLEtBQUssS0FBSyxPQUFPLEVBQUU7WUFDNUMsTUFBTSxJQUFJLG1CQUFtQixDQUN6QiwwQ0FBMEMsS0FBSyxHQUFHLENBQUMsQ0FBQztTQUN6RDtRQUNELE9BQU8sZUFBZSxDQUFDLEtBQUssRUFBRSxJQUFJLENBQUMsSUFBSSxFQUFFLElBQUksQ0FBQyxNQUFNLEVBQUUsS0FBSyxFQUFFLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQztJQUMxRSxDQUFDO0lBRVEsU0FBUztRQUNoQixPQUFPLEVBQUMsSUFBSSxFQUFFLElBQUksQ0FBQyxJQUFJLEVBQUUsTUFBTSxFQUFFLElBQUksQ0FBQyxNQUFNLEVBQUUsSUFBSSxFQUFFLElBQUksQ0FBQyxJQUFJLEVBQUMsQ0FBQztJQUNqRSxDQUFDOztBQTNCRCxrQkFBa0I7QUFDWCx5QkFBUyxHQUFHLGlCQUFpQixDQUFDO0FBNEJ2QyxhQUFhLENBQUMsYUFBYSxDQUFDLGVBQWUsQ0FBQyxDQUFDO0FBUzdDLE1BQU0sT0FBTyxRQUFTLFNBQVEsV0FBVztJQUl2QyxZQUFZLElBQWtCO1FBQzVCLEtBQUssRUFBRSxDQUFDO1FBQ1IsSUFBSSxDQUFDLElBQUksR0FBRyxJQUFJLENBQUMsSUFBSSxJQUFJLElBQUksQ0FBQyxDQUFDLENBQUMsSUFBSSxDQUFDLElBQUksQ0FBQyxDQUFDLENBQUMsR0FBRyxDQUFDO0lBQ2xELENBQUM7SUFFRCxLQUFLLENBQUMsS0FBWSxFQUFFLEtBQWdCO1FBQ2xDLE9BQU8sSUFBSSxDQUFDLEdBQUcsRUFBRTtZQUNmLElBQUksS0FBSyxDQUFDLE1BQU0sS0FBSyxDQUFDLElBQUksS0FBSyxDQUFDLENBQUMsQ0FBQyxLQUFLLEtBQUssQ0FBQyxDQUFDLENBQUMsRUFBRTtnQkFDL0MsTUFBTSxJQUFJLFVBQVUsQ0FDaEIsa0RBQWtEO29CQUNsRCxzQkFBc0IsQ0FBQyxDQUFDO2FBQzdCO2lCQUFNO2dCQUNMLE9BQU8sR0FBRyxDQUFDLElBQUksQ0FBQyxJQUFJLEVBQUUsR0FBRyxDQUFDLEtBQUssQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUM7YUFDdEM7UUFDSCxDQUFDLENBQUMsQ0FBQztJQUNMLENBQUM7SUFFUSxTQUFTO1FBQ2hCLE9BQU8sRUFBQyxJQUFJLEVBQUUsSUFBSSxDQUFDLElBQUksRUFBQyxDQUFDO0lBQzNCLENBQUM7O0FBdEJELGtCQUFrQjtBQUNYLGtCQUFTLEdBQUcsVUFBVSxDQUFDO0FBdUJoQyxhQUFhLENBQUMsYUFBYSxDQUFDLFFBQVEsQ0FBQyxDQUFDO0FBRXRDOzs7Ozs7O0dBT0c7QUFDSCxTQUFTLFdBQVcsQ0FDaEIsS0FBWSxFQUFFLGFBQXlCLGNBQWM7SUFDdkQsSUFBSSxLQUFhLENBQUM7SUFDbEIsSUFBSSxNQUFjLENBQUM7SUFDbkIsZUFBZSxDQUFDLFVBQVUsQ0FBQyxDQUFDO0lBQzVCLElBQUksS0FBSyxDQUFDLE1BQU0sS0FBSyxDQUFDLEVBQUU7UUFDdEIsS0FBSyxHQUFHLEtBQUssQ0FBQyxDQUFDLENBQUMsQ0FBQztRQUNqQixNQUFNLEdBQUcsS0FBSyxDQUFDLENBQUMsQ0FBQyxDQUFDO0tBQ25CO1NBQU0sSUFBSSxDQUFDLENBQUMsRUFBRSxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUMsT0FBTyxDQUFDLEtBQUssQ0FBQyxNQUFNLENBQUMsS0FBSyxDQUFDLENBQUMsRUFBRTtRQUNqRCxJQUFJLFVBQVUsS0FBSyxlQUFlLEVBQUU7WUFDbEMsTUFBTSxrQkFBa0IsR0FBRyxTQUFTLENBQUMsS0FBSyxFQUFFLENBQUMsQ0FBQyxDQUFDO1lBQy9DLEtBQUssR0FBRyxLQUFLLENBQUMsQ0FBQyxDQUFDLEdBQUcsa0JBQWtCLENBQUM7WUFDdEMsTUFBTSxHQUFHLEtBQUssQ0FBQyxDQUFDLENBQUMsR0FBRyxrQkFBa0IsQ0FBQztTQUN4QzthQUFNLElBQUksVUFBVSxLQUFLLGNBQWMsRUFBRTtZQUN4QyxNQUFNLGtCQUFrQixHQUFHLFNBQVMsQ0FBQyxLQUFLLEVBQUUsQ0FBQyxFQUFFLEtBQUssQ0FBQyxNQUFNLEdBQUcsQ0FBQyxDQUFDLENBQUM7WUFDakUsS0FBSyxHQUFHLEtBQUssQ0FBQyxLQUFLLENBQUMsTUFBTSxHQUFHLENBQUMsQ0FBQyxHQUFHLGtCQUFrQixDQUFDO1lBQ3JELE1BQU0sR0FBRyxLQUFLLENBQUMsS0FBSyxDQUFDLE1BQU0sR0FBRyxDQUFDLENBQUMsR0FBRyxrQkFBa0IsQ0FBQztTQUN2RDtLQUNGO1NBQU07UUFDTCxNQUFNLFNBQVMsR0FBRyxTQUFTLENBQUMsS0FBSyxDQUFDLENBQUM7UUFDbkMsS0FBSyxHQUFHLElBQUksQ0FBQyxJQUFJLENBQUMsU0FBUyxDQUFDLENBQUM7UUFDN0IsTUFBTSxHQUFHLElBQUksQ0FBQyxJQUFJLENBQUMsU0FBUyxDQUFDLENBQUM7S0FDL0I7SUFFRCxPQUFPLENBQUMsS0FBSyxFQUFFLE1BQU0sQ0FBQyxDQUFDO0FBQ3pCLENBQUM7QUFnQkQsTUFBTSxPQUFPLGVBQWdCLFNBQVEsV0FBVztJQVE5Qzs7O09BR0c7SUFDSCxZQUFZLElBQXlCO1FBQ25DLEtBQUssRUFBRSxDQUFDO1FBQ1IsSUFBSSxJQUFJLENBQUMsS0FBSyxHQUFHLEdBQUcsRUFBRTtZQUNwQixNQUFNLElBQUksVUFBVSxDQUNoQix3Q0FBd0MsSUFBSSxDQUFDLEtBQUssRUFBRSxDQUFDLENBQUM7U0FDM0Q7UUFDRCxJQUFJLENBQUMsS0FBSyxHQUFHLElBQUksQ0FBQyxLQUFLLElBQUksSUFBSSxDQUFDLENBQUMsQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxLQUFLLENBQUM7UUFDbkQsSUFBSSxDQUFDLElBQUksR0FBRyxJQUFJLENBQUMsSUFBSSxJQUFJLElBQUksQ0FBQyxDQUFDLENBQUMsT0FBTyxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsSUFBSSxDQUFDO1FBQ3BELFlBQVksQ0FBQyxJQUFJLENBQUMsSUFBSSxDQUFDLENBQUM7UUFDeEIsSUFBSSxDQUFDLFlBQVk7WUFDYixJQUFJLENBQUMsWUFBWSxJQUFJLElBQUksQ0FBQyxDQUFDLENBQUMsUUFBUSxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsWUFBWSxDQUFDO1FBQzdELGlCQUFpQixDQUFDLElBQUksQ0FBQyxZQUFZLENBQUMsQ0FBQztRQUNyQyxJQUFJLENBQUMsSUFBSSxHQUFHLElBQUksQ0FBQyxJQUFJLENBQUM7SUFDeEIsQ0FBQztJQUVELEtBQUssQ0FBQyxLQUFZLEVBQUUsS0FBZ0I7UUFDbEMsTUFBTSxJQUFJLEdBQUcsV0FBVyxDQUFDLEtBQUssQ0FBQyxDQUFDO1FBQ2hDLE1BQU0sS0FBSyxHQUFHLElBQUksQ0FBQyxDQUFDLENBQUMsQ0FBQztRQUN0QixNQUFNLE1BQU0sR0FBRyxJQUFJLENBQUMsQ0FBQyxDQUFDLENBQUM7UUFDdkIsSUFBSSxLQUFLLEdBQUcsSUFBSSxDQUFDLEtBQUssQ0FBQztRQUN2QixJQUFJLElBQUksQ0FBQyxJQUFJLEtBQUssT0FBTyxFQUFFO1lBQ3pCLEtBQUssSUFBSSxJQUFJLENBQUMsR0FBRyxDQUFDLENBQUMsRUFBRSxLQUFLLENBQUMsQ0FBQztTQUM3QjthQUFNLElBQUksSUFBSSxDQUFDLElBQUksS0FBSyxRQUFRLEVBQUU7WUFDakMsS0FBSyxJQUFJLElBQUksQ0FBQyxHQUFHLENBQUMsQ0FBQyxFQUFFLE1BQU0sQ0FBQyxDQUFDO1NBQzlCO2FBQU07WUFDTCxLQUFLLElBQUksSUFBSSxDQUFDLEdBQUcsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxLQUFLLEdBQUcsTUFBTSxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUM7U0FDNUM7UUFFRCxJQUFJLElBQUksQ0FBQyxZQUFZLEtBQUssUUFBUSxFQUFFO1lBQ2xDLE1BQU0sTUFBTSxHQUFHLElBQUksQ0FBQyxJQUFJLENBQUMsS0FBSyxDQUFDLENBQUM7WUFDaEMsS0FBSyxHQUFHLEtBQUssSUFBSSxTQUFTLENBQUM7WUFDM0IsSUFBSSxLQUFLLEtBQUssU0FBUyxJQUFJLEtBQUssS0FBSyxPQUFPLEVBQUU7Z0JBQzVDLE1BQU0sSUFBSSxtQkFBbUIsQ0FDekIsR0FBRyxJQUFJLENBQUMsWUFBWSxFQUFFLDJCQUEyQixLQUFLLEdBQUcsQ0FBQyxDQUFDO2FBQ2hFO1lBQ0QsT0FBTyxlQUFlLENBQUMsS0FBSyxFQUFFLENBQUMsRUFBRSxNQUFNLEVBQUUsS0FBSyxFQUFFLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQztTQUM1RDthQUFNO1lBQ0wsTUFBTSxLQUFLLEdBQUcsSUFBSSxDQUFDLElBQUksQ0FBQyxDQUFDLEdBQUcsS0FBSyxDQUFDLENBQUM7WUFDbkMsT0FBTyxhQUFhLENBQUMsS0FBSyxFQUFFLENBQUMsS0FBSyxFQUFFLEtBQUssRUFBRSxLQUFLLEVBQUUsSUFBSSxDQUFDLElBQUksQ0FBQyxDQUFDO1NBQzlEO0lBQ0gsQ0FBQztJQUVRLFNBQVM7UUFDaEIsT0FBTztZQUNMLEtBQUssRUFBRSxJQUFJLENBQUMsS0FBSztZQUNqQixJQUFJLEVBQUUsSUFBSSxDQUFDLElBQUk7WUFDZixZQUFZLEVBQUUsSUFBSSxDQUFDLFlBQVk7WUFDL0IsSUFBSSxFQUFFLElBQUksQ0FBQyxJQUFJO1NBQ2hCLENBQUM7SUFDSixDQUFDOztBQTVERCxrQkFBa0I7QUFDWCx5QkFBUyxHQUFHLGlCQUFpQixDQUFDO0FBNkR2QyxhQUFhLENBQUMsYUFBYSxDQUFDLGVBQWUsQ0FBQyxDQUFDO0FBTzdDLE1BQU0sT0FBTyxhQUFjLFNBQVEsZUFBZTtJQUloRDs7Ozs7O09BTUc7SUFDSCxZQUFZLElBQThCO1FBQ3hDLEtBQUssQ0FBQztZQUNKLEtBQUssRUFBRSxHQUFHO1lBQ1YsSUFBSSxFQUFFLFFBQVE7WUFDZCxZQUFZLEVBQUUsU0FBUztZQUN2QixJQUFJLEVBQUUsSUFBSSxJQUFJLElBQUksQ0FBQyxDQUFDLENBQUMsSUFBSSxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsSUFBSTtTQUN0QyxDQUFDLENBQUM7SUFDTCxDQUFDO0lBRVEsWUFBWTtRQUNuQixxRUFBcUU7UUFDckUsa0VBQWtFO1FBQ2xFLHlDQUF5QztRQUN6QyxPQUFPLGVBQWUsQ0FBQyxTQUFTLENBQUM7SUFDbkMsQ0FBQzs7QUF4QkQsa0JBQWtCO0FBQ0YsdUJBQVMsR0FBRyxlQUFlLENBQUM7QUF5QjlDLGFBQWEsQ0FBQyxhQUFhLENBQUMsYUFBYSxDQUFDLENBQUM7QUFFM0MsTUFBTSxPQUFPLFlBQWEsU0FBUSxlQUFlO0lBSS9DOzs7Ozs7T0FNRztJQUNILFlBQVksSUFBOEI7UUFDeEMsS0FBSyxDQUFDO1lBQ0osS0FBSyxFQUFFLEdBQUc7WUFDVixJQUFJLEVBQUUsUUFBUTtZQUNkLFlBQVksRUFBRSxRQUFRO1lBQ3RCLElBQUksRUFBRSxJQUFJLElBQUksSUFBSSxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxJQUFJO1NBQ3RDLENBQUMsQ0FBQztJQUNMLENBQUM7SUFFUSxZQUFZO1FBQ25CLG9FQUFvRTtRQUNwRSxrRUFBa0U7UUFDbEUseUNBQXlDO1FBQ3pDLE9BQU8sZUFBZSxDQUFDLFNBQVMsQ0FBQztJQUNuQyxDQUFDOztBQXhCRCxrQkFBa0I7QUFDRixzQkFBUyxHQUFHLGNBQWMsQ0FBQztBQXlCN0MsYUFBYSxDQUFDLGFBQWEsQ0FBQyxZQUFZLENBQUMsQ0FBQztBQUUxQyxNQUFNLE9BQU8sUUFBUyxTQUFRLGVBQWU7SUFJM0MsWUFBWSxJQUE4QjtRQUN4QyxLQUFLLENBQUM7WUFDSixLQUFLLEVBQUUsR0FBRztZQUNWLElBQUksRUFBRSxPQUFPO1lBQ2IsWUFBWSxFQUFFLFFBQVE7WUFDdEIsSUFBSSxFQUFFLElBQUksSUFBSSxJQUFJLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxDQUFDLENBQUMsSUFBSSxDQUFDLElBQUk7U0FDdEMsQ0FBQyxDQUFDO0lBQ0wsQ0FBQztJQUVRLFlBQVk7UUFDbkIsZ0VBQWdFO1FBQ2hFLGtFQUFrRTtRQUNsRSx5Q0FBeUM7UUFDekMsT0FBTyxlQUFlLENBQUMsU0FBUyxDQUFDO0lBQ25DLENBQUM7O0FBakJELGtCQUFrQjtBQUNGLGtCQUFTLEdBQUcsVUFBVSxDQUFDO0FBa0J6QyxhQUFhLENBQUMsYUFBYSxDQUFDLFFBQVEsQ0FBQyxDQUFDO0FBRXRDLE1BQU0sT0FBTyxTQUFVLFNBQVEsZUFBZTtJQUk1QyxZQUFZLElBQThCO1FBQ3hDLEtBQUssQ0FBQztZQUNKLEtBQUssRUFBRSxHQUFHO1lBQ1YsSUFBSSxFQUFFLE9BQU87WUFDYixZQUFZLEVBQUUsU0FBUztZQUN2QixJQUFJLEVBQUUsSUFBSSxJQUFJLElBQUksQ0FBQyxDQUFDLENBQUMsSUFBSSxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsSUFBSTtTQUN0QyxDQUFDLENBQUM7SUFDTCxDQUFDO0lBRVEsWUFBWTtRQUNuQixpRUFBaUU7UUFDakUsa0VBQWtFO1FBQ2xFLHlDQUF5QztRQUN6QyxPQUFPLGVBQWUsQ0FBQyxTQUFTLENBQUM7SUFDbkMsQ0FBQzs7QUFqQkQsa0JBQWtCO0FBQ0YsbUJBQVMsR0FBRyxXQUFXLENBQUM7QUFrQjFDLGFBQWEsQ0FBQyxhQUFhLENBQUMsU0FBUyxDQUFDLENBQUM7QUFFdkMsTUFBTSxPQUFPLFdBQVksU0FBUSxlQUFlO0lBSTlDLFlBQVksSUFBOEI7UUFDeEMsS0FBSyxDQUFDO1lBQ0osS0FBSyxFQUFFLEdBQUc7WUFDVixJQUFJLEVBQUUsT0FBTztZQUNiLFlBQVksRUFBRSxRQUFRO1lBQ3RCLElBQUksRUFBRSxJQUFJLElBQUksSUFBSSxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxJQUFJO1NBQ3RDLENBQUMsQ0FBQztJQUNMLENBQUM7SUFFUSxZQUFZO1FBQ25CLG1FQUFtRTtRQUNuRSxrRUFBa0U7UUFDbEUseUNBQXlDO1FBQ3pDLE9BQU8sZUFBZSxDQUFDLFNBQVMsQ0FBQztJQUNuQyxDQUFDOztBQWpCRCxrQkFBa0I7QUFDRixxQkFBUyxHQUFHLGFBQWEsQ0FBQztBQWtCNUMsYUFBYSxDQUFDLGFBQWEsQ0FBQyxXQUFXLENBQUMsQ0FBQztBQUV6QyxNQUFNLE9BQU8sWUFBYSxTQUFRLGVBQWU7SUFJL0MsWUFBWSxJQUE4QjtRQUN4QyxLQUFLLENBQUM7WUFDSixLQUFLLEVBQUUsR0FBRztZQUNWLElBQUksRUFBRSxPQUFPO1lBQ2IsWUFBWSxFQUFFLFNBQVM7WUFDdkIsSUFBSSxFQUFFLElBQUksSUFBSSxJQUFJLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxDQUFDLENBQUMsSUFBSSxDQUFDLElBQUk7U0FDdEMsQ0FBQyxDQUFDO0lBQ0wsQ0FBQztJQUVRLFlBQVk7UUFDbkIsb0VBQW9FO1FBQ3BFLGtFQUFrRTtRQUNsRSx5Q0FBeUM7UUFDekMsT0FBTyxlQUFlLENBQUMsU0FBUyxDQUFDO0lBQ25DLENBQUM7O0FBakJELGtCQUFrQjtBQUNGLHNCQUFTLEdBQUcsY0FBYyxDQUFDO0FBa0I3QyxhQUFhLENBQUMsYUFBYSxDQUFDLFlBQVksQ0FBQyxDQUFDO0FBUzFDLE1BQU0sT0FBTyxVQUFXLFNBQVEsV0FBVztJQVF6QyxZQUFZLElBQXFCO1FBQy9CLEtBQUssRUFBRSxDQUFDO1FBTkQsaUJBQVksR0FBRyxDQUFDLENBQUM7UUFDakIsdUJBQWtCLEdBQUcsSUFBSSxDQUFDO1FBTWpDLElBQUksQ0FBQyxJQUFJLEdBQUcsSUFBSSxDQUFDLElBQUksSUFBSSxJQUFJLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxZQUFZLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxJQUFJLENBQUM7UUFDOUQsSUFBSSxDQUFDLElBQUksR0FBRyxJQUFJLENBQUMsSUFBSSxDQUFDO0lBQ3hCLENBQUM7SUFFRCxLQUFLLENBQUMsS0FBWSxFQUFFLEtBQWdCO1FBQ2xDLE9BQU8sSUFBSSxDQUFDLEdBQUcsRUFBRTtZQUNmLElBQUksS0FBSyxDQUFDLE1BQU0sR0FBRyxDQUFDLEVBQUU7Z0JBQ3BCLE1BQU0sSUFBSSxtQkFBbUIsQ0FBQyw0QkFBNEIsQ0FBQyxDQUFDO2FBQzdEO1lBQ0QsSUFBSSxLQUFLLEtBQUssT0FBTyxJQUFJLEtBQUssS0FBSyxTQUFTLElBQUksS0FBSyxLQUFLLFNBQVMsRUFBRTtnQkFDbkUsTUFBTSxJQUFJLFNBQVMsQ0FBQyx5QkFBeUIsS0FBSyxHQUFHLENBQUMsQ0FBQzthQUN4RDtZQUNELEtBQUssR0FBRyxLQUF3QyxDQUFDO1lBRWpELGdFQUFnRTtZQUNoRSx3Q0FBd0M7WUFDeEMsTUFBTSxPQUFPLEdBQUcsSUFBSSxDQUFDLGFBQWEsQ0FBQyxLQUFLLENBQUMsS0FBSyxDQUFDLENBQUMsRUFBRSxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUM7WUFDdkQsTUFBTSxPQUFPLEdBQUcsS0FBSyxDQUFDLEtBQUssQ0FBQyxNQUFNLEdBQUcsQ0FBQyxDQUFDLENBQUM7WUFDeEMsTUFBTSxXQUFXLEdBQUcsT0FBTyxHQUFHLE9BQU8sQ0FBQztZQUN0QyxJQUFJLFdBQVcsR0FBRyxJQUFJLENBQUMsa0JBQWtCLEVBQUU7Z0JBQ3pDLE9BQU8sQ0FBQyxJQUFJLENBQ1IsK0RBQStEO29CQUMvRCxRQUFRLElBQUksQ0FBQyxrQkFBa0IsS0FBSyxXQUFXLGNBQWM7b0JBQzdELHNCQUFzQixDQUFDLENBQUM7YUFDN0I7WUFDRCxNQUFNLFNBQVMsR0FDWCxDQUFDLElBQUksQ0FBQyxHQUFHLENBQUMsT0FBTyxFQUFFLE9BQU8sQ0FBQyxFQUFFLElBQUksQ0FBQyxHQUFHLENBQUMsT0FBTyxFQUFFLE9BQU8sQ0FBQyxDQUFDLENBQUM7WUFFN0QsMkJBQTJCO1lBQzNCLE1BQU0sYUFBYSxHQUFHLENBQUMsQ0FBQyxZQUFZLENBQUMsU0FBUyxFQUFFLENBQUMsRUFBRSxDQUFDLEVBQUUsS0FBSyxFQUFFLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQztZQUV4RSwyQkFBMkI7WUFDM0IsTUFBTSxFQUFFLEdBQUcsTUFBTSxDQUFDLEVBQUUsQ0FBQyxhQUFhLEVBQUUsS0FBSyxDQUFDLENBQUM7WUFDM0MsSUFBSSxJQUFJLEdBQUcsRUFBRSxDQUFDLENBQUMsQ0FBQyxDQUFDO1lBQ2pCLE1BQU0sSUFBSSxHQUFHLEVBQUUsQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUVuQixpQkFBaUI7WUFDakIsTUFBTSxJQUFJLEdBQUcsSUFBSSxDQUFDLE9BQU8sRUFBRSxDQUFDLFlBQVksQ0FDcEMsQ0FBQyxDQUFDLENBQUMsRUFBRSxDQUFDLElBQUksQ0FBQyxHQUFHLENBQUMsT0FBTyxFQUFFLE9BQU8sQ0FBQyxHQUFHLElBQUksQ0FBQyxHQUFHLENBQUMsT0FBTyxFQUFFLE9BQU8sQ0FBQyxDQUFDLEVBQzlELENBQUMsSUFBSSxDQUFDLEdBQUcsQ0FBQyxPQUFPLEVBQUUsT0FBTyxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUN0QyxJQUFJLEdBQUcsR0FBRyxDQUFDLElBQUksRUFBRSxJQUFJLENBQUMsSUFBSSxFQUFFLENBQUMsQ0FBQztZQUM5QixJQUFJLE9BQU8sR0FBRyxPQUFPLEVBQUU7Z0JBQ3JCLElBQUksR0FBRyxJQUFJLENBQUMsU0FBUyxFQUFFLENBQUM7YUFDekI7WUFFRCxPQUFPLEdBQUcsQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLElBQUksQ0FBQyxFQUFFLElBQUksQ0FBQyxPQUFPLENBQUMsS0FBSyxDQUFDLENBQUMsQ0FBQztRQUNyRCxDQUFDLENBQUMsQ0FBQztJQUNMLENBQUM7SUFFUSxTQUFTO1FBQ2hCLE9BQU87WUFDTCxJQUFJLEVBQUUsSUFBSSxDQUFDLElBQUk7WUFDZixJQUFJLEVBQUUsSUFBSSxDQUFDLElBQUk7U0FDaEIsQ0FBQztJQUNKLENBQUM7O0FBL0RELGtCQUFrQjtBQUNYLG9CQUFTLEdBQUcsWUFBWSxDQUFDO0FBZ0VsQyxhQUFhLENBQUMsYUFBYSxDQUFDLFVBQVUsQ0FBQyxDQUFDO0FBUXhDLHlFQUF5RTtBQUN6RSxXQUFXO0FBQ1gsTUFBTSxDQUFDLE1BQU0sMENBQTBDLEdBQ0Q7SUFDaEQsVUFBVSxFQUFFLFVBQVU7SUFDdEIsY0FBYyxFQUFFLGNBQWM7SUFDOUIsZUFBZSxFQUFFLGVBQWU7SUFDaEMsVUFBVSxFQUFFLFVBQVU7SUFDdEIsV0FBVyxFQUFFLFdBQVc7SUFDeEIsVUFBVSxFQUFFLFVBQVU7SUFDdEIsYUFBYSxFQUFFLGFBQWE7SUFDNUIsY0FBYyxFQUFFLGNBQWM7SUFDOUIsTUFBTSxFQUFFLE1BQU07SUFDZCxZQUFZLEVBQUUsWUFBWTtJQUMxQixjQUFjLEVBQUUsY0FBYztJQUM5QixlQUFlLEVBQUUsZUFBZTtJQUNoQyxpQkFBaUIsRUFBRSxpQkFBaUI7SUFDcEMsaUJBQWlCLEVBQUUsaUJBQWlCO0lBQ3BDLE9BQU8sRUFBRSxPQUFPO0NBQ2pCLENBQUM7QUFFTixTQUFTLHNCQUFzQixDQUMzQixNQUFnQyxFQUNoQyxnQkFBMEMsRUFBRTtJQUM5QyxPQUFPLHNCQUFzQixDQUN6QixNQUFNLEVBQUUsYUFBYSxDQUFDLGdCQUFnQixDQUFDLE1BQU0sRUFBRSxDQUFDLFlBQVksRUFDNUQsYUFBYSxFQUFFLGFBQWEsQ0FBQyxDQUFDO0FBQ3BDLENBQUM7QUFFRCxNQUFNLFVBQVUsb0JBQW9CLENBQUMsV0FBd0I7SUFFM0QsT0FBTyxvQkFBb0IsQ0FBQyxXQUFXLENBQUMsQ0FBQztBQUMzQyxDQUFDO0FBRUQsTUFBTSxVQUFVLGNBQWMsQ0FBQyxVQUN3QjtJQUNyRCxJQUFJLE9BQU8sVUFBVSxLQUFLLFFBQVEsRUFBRTtRQUNsQyxNQUFNLFNBQVMsR0FBRyxVQUFVLElBQUksMENBQTBDLENBQUMsQ0FBQztZQUN4RSwwQ0FBMEMsQ0FBQyxVQUFVLENBQUMsQ0FBQyxDQUFDO1lBQ3hELFVBQVUsQ0FBQztRQUNmOzs4Q0FFc0M7UUFDdEMsSUFBSSxTQUFTLEtBQUssY0FBYyxFQUFFO1lBQ2hDLE9BQU8sSUFBSSxZQUFZLEVBQUUsQ0FBQztTQUMzQjthQUFNLElBQUksU0FBUyxLQUFLLGVBQWUsRUFBRTtZQUN4QyxPQUFPLElBQUksYUFBYSxFQUFFLENBQUM7U0FDNUI7YUFBTSxJQUFJLFNBQVMsS0FBSyxVQUFVLEVBQUU7WUFDbkMsT0FBTyxJQUFJLFFBQVEsRUFBRSxDQUFDO1NBQ3ZCO2FBQU0sSUFBSSxTQUFTLEtBQUssV0FBVyxFQUFFO1lBQ3BDLE9BQU8sSUFBSSxTQUFTLEVBQUUsQ0FBQztTQUN4QjthQUFNLElBQUksU0FBUyxLQUFLLGFBQWEsRUFBRTtZQUN0QyxPQUFPLElBQUksV0FBVyxFQUFFLENBQUM7U0FDMUI7YUFBTSxJQUFJLFNBQVMsS0FBSyxjQUFjLEVBQUU7WUFDdkMsT0FBTyxJQUFJLFlBQVksRUFBRSxDQUFDO1NBQzNCO2FBQU07WUFDTCxNQUFNLE1BQU0sR0FBNkIsRUFBRSxDQUFDO1lBQzVDLE1BQU0sQ0FBQyxXQUFXLENBQUMsR0FBRyxTQUFTLENBQUM7WUFDaEMsTUFBTSxDQUFDLFFBQVEsQ0FBQyxHQUFHLEVBQUUsQ0FBQztZQUN0QixPQUFPLHNCQUFzQixDQUFDLE1BQU0sQ0FBQyxDQUFDO1NBQ3ZDO0tBQ0Y7U0FBTSxJQUFJLFVBQVUsWUFBWSxXQUFXLEVBQUU7UUFDNUMsT0FBTyxVQUFVLENBQUM7S0FDbkI7U0FBTTtRQUNMLE9BQU8sc0JBQXNCLENBQUMsVUFBVSxDQUFDLENBQUM7S0FDM0M7QUFDSCxDQUFDIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMTggR29vZ2xlIExMQ1xuICpcbiAqIFVzZSBvZiB0aGlzIHNvdXJjZSBjb2RlIGlzIGdvdmVybmVkIGJ5IGFuIE1JVC1zdHlsZVxuICogbGljZW5zZSB0aGF0IGNhbiBiZSBmb3VuZCBpbiB0aGUgTElDRU5TRSBmaWxlIG9yIGF0XG4gKiBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL01JVC5cbiAqID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG4gKi9cblxuaW1wb3J0IHtEYXRhVHlwZSwgZXllLCBsaW5hbGcsIG11bCwgb25lcywgcmFuZG9tVW5pZm9ybSwgc2NhbGFyLCBzZXJpYWxpemF0aW9uLCBUZW5zb3IsIHRpZHksIHRydW5jYXRlZE5vcm1hbCwgdXRpbCwgemVyb3N9IGZyb20gJ0B0ZW5zb3JmbG93L3RmanMtY29yZSc7XG5cbmltcG9ydCAqIGFzIEsgZnJvbSAnLi9iYWNrZW5kL3RmanNfYmFja2VuZCc7XG5pbXBvcnQge2NoZWNrRGF0YUZvcm1hdH0gZnJvbSAnLi9jb21tb24nO1xuaW1wb3J0IHtOb3RJbXBsZW1lbnRlZEVycm9yLCBWYWx1ZUVycm9yfSBmcm9tICcuL2Vycm9ycyc7XG5pbXBvcnQge0RhdGFGb3JtYXQsIFNoYXBlfSBmcm9tICcuL2tlcmFzX2Zvcm1hdC9jb21tb24nO1xuaW1wb3J0IHtEaXN0cmlidXRpb24sIEZhbk1vZGUsIFZBTElEX0RJU1RSSUJVVElPTl9WQUxVRVMsIFZBTElEX0ZBTl9NT0RFX1ZBTFVFU30gZnJvbSAnLi9rZXJhc19mb3JtYXQvaW5pdGlhbGl6ZXJfY29uZmlnJztcbmltcG9ydCB7Y2hlY2tTdHJpbmdUeXBlVW5pb25WYWx1ZSwgZGVzZXJpYWxpemVLZXJhc09iamVjdCwgc2VyaWFsaXplS2VyYXNPYmplY3R9IGZyb20gJy4vdXRpbHMvZ2VuZXJpY191dGlscyc7XG5pbXBvcnQge2FycmF5UHJvZH0gZnJvbSAnLi91dGlscy9tYXRoX3V0aWxzJztcblxuZXhwb3J0IGZ1bmN0aW9uIGNoZWNrRmFuTW9kZSh2YWx1ZT86IHN0cmluZyk6IHZvaWQge1xuICBjaGVja1N0cmluZ1R5cGVVbmlvblZhbHVlKFZBTElEX0ZBTl9NT0RFX1ZBTFVFUywgJ0Zhbk1vZGUnLCB2YWx1ZSk7XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBjaGVja0Rpc3RyaWJ1dGlvbih2YWx1ZT86IHN0cmluZyk6IHZvaWQge1xuICBjaGVja1N0cmluZ1R5cGVVbmlvblZhbHVlKFZBTElEX0RJU1RSSUJVVElPTl9WQUxVRVMsICdEaXN0cmlidXRpb24nLCB2YWx1ZSk7XG59XG5cbi8qKlxuICogSW5pdGlhbGl6ZXIgYmFzZSBjbGFzcy5cbiAqXG4gKiBAZG9jIHtcbiAqICAgaGVhZGluZzogJ0luaXRpYWxpemVycycsIHN1YmhlYWRpbmc6ICdDbGFzc2VzJywgbmFtZXNwYWNlOiAnaW5pdGlhbGl6ZXJzJ31cbiAqL1xuZXhwb3J0IGFic3RyYWN0IGNsYXNzIEluaXRpYWxpemVyIGV4dGVuZHMgc2VyaWFsaXphdGlvbi5TZXJpYWxpemFibGUge1xuICBwdWJsaWMgZnJvbUNvbmZpZ1VzZXNDdXN0b21PYmplY3RzKCk6IGJvb2xlYW4ge1xuICAgIHJldHVybiBmYWxzZTtcbiAgfVxuICAvKipcbiAgICogR2VuZXJhdGUgYW4gaW5pdGlhbCB2YWx1ZS5cbiAgICogQHBhcmFtIHNoYXBlXG4gICAqIEBwYXJhbSBkdHlwZVxuICAgKiBAcmV0dXJuIFRoZSBpbml0IHZhbHVlLlxuICAgKi9cbiAgYWJzdHJhY3QgYXBwbHkoc2hhcGU6IFNoYXBlLCBkdHlwZT86IERhdGFUeXBlKTogVGVuc29yO1xuXG4gIGdldENvbmZpZygpOiBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3Qge1xuICAgIHJldHVybiB7fTtcbiAgfVxufVxuXG5leHBvcnQgY2xhc3MgWmVyb3MgZXh0ZW5kcyBJbml0aWFsaXplciB7XG4gIC8qKiBAbm9jb2xsYXBzZSAqL1xuICBzdGF0aWMgY2xhc3NOYW1lID0gJ1plcm9zJztcblxuICBhcHBseShzaGFwZTogU2hhcGUsIGR0eXBlPzogRGF0YVR5cGUpOiBUZW5zb3Ige1xuICAgIHJldHVybiB6ZXJvcyhzaGFwZSwgZHR5cGUpO1xuICB9XG59XG5zZXJpYWxpemF0aW9uLnJlZ2lzdGVyQ2xhc3MoWmVyb3MpO1xuXG5leHBvcnQgY2xhc3MgT25lcyBleHRlbmRzIEluaXRpYWxpemVyIHtcbiAgLyoqIEBub2NvbGxhcHNlICovXG4gIHN0YXRpYyBjbGFzc05hbWUgPSAnT25lcyc7XG5cbiAgYXBwbHkoc2hhcGU6IFNoYXBlLCBkdHlwZT86IERhdGFUeXBlKTogVGVuc29yIHtcbiAgICByZXR1cm4gb25lcyhzaGFwZSwgZHR5cGUpO1xuICB9XG59XG5zZXJpYWxpemF0aW9uLnJlZ2lzdGVyQ2xhc3MoT25lcyk7XG5cbmV4cG9ydCBpbnRlcmZhY2UgQ29uc3RhbnRBcmdzIHtcbiAgLyoqIFRoZSB2YWx1ZSBmb3IgZWFjaCBlbGVtZW50IGluIHRoZSB2YXJpYWJsZS4gKi9cbiAgdmFsdWU6IG51bWJlcjtcbn1cblxuZXhwb3J0IGNsYXNzIENvbnN0YW50IGV4dGVuZHMgSW5pdGlhbGl6ZXIge1xuICAvKiogQG5vY29sbGFwc2UgKi9cbiAgc3RhdGljIGNsYXNzTmFtZSA9ICdDb25zdGFudCc7XG4gIHByaXZhdGUgdmFsdWU6IG51bWJlcjtcbiAgY29uc3RydWN0b3IoYXJnczogQ29uc3RhbnRBcmdzKSB7XG4gICAgc3VwZXIoKTtcbiAgICBpZiAodHlwZW9mIGFyZ3MgIT09ICdvYmplY3QnKSB7XG4gICAgICB0aHJvdyBuZXcgVmFsdWVFcnJvcihcbiAgICAgICAgICBgRXhwZWN0ZWQgYXJndW1lbnQgb2YgdHlwZSBDb25zdGFudENvbmZpZyBidXQgZ290ICR7YXJnc31gKTtcbiAgICB9XG4gICAgaWYgKGFyZ3MudmFsdWUgPT09IHVuZGVmaW5lZCkge1xuICAgICAgdGhyb3cgbmV3IFZhbHVlRXJyb3IoYGNvbmZpZyBtdXN0IGhhdmUgdmFsdWUgc2V0IGJ1dCBnb3QgJHthcmdzfWApO1xuICAgIH1cbiAgICB0aGlzLnZhbHVlID0gYXJncy52YWx1ZTtcbiAgfVxuXG4gIGFwcGx5KHNoYXBlOiBTaGFwZSwgZHR5cGU/OiBEYXRhVHlwZSk6IFRlbnNvciB7XG4gICAgcmV0dXJuIHRpZHkoKCkgPT4gbXVsKHNjYWxhcih0aGlzLnZhbHVlKSwgb25lcyhzaGFwZSwgZHR5cGUpKSk7XG4gIH1cblxuICBvdmVycmlkZSBnZXRDb25maWcoKTogc2VyaWFsaXphdGlvbi5Db25maWdEaWN0IHtcbiAgICByZXR1cm4ge1xuICAgICAgdmFsdWU6IHRoaXMudmFsdWUsXG4gICAgfTtcbiAgfVxufVxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKENvbnN0YW50KTtcblxuZXhwb3J0IGludGVyZmFjZSBSYW5kb21Vbmlmb3JtQXJncyB7XG4gIC8qKiBMb3dlciBib3VuZCBvZiB0aGUgcmFuZ2Ugb2YgcmFuZG9tIHZhbHVlcyB0byBnZW5lcmF0ZS4gKi9cbiAgbWludmFsPzogbnVtYmVyO1xuICAvKiogVXBwZXIgYm91bmQgb2YgdGhlIHJhbmdlIG9mIHJhbmRvbSB2YWx1ZXMgdG8gZ2VuZXJhdGUuICovXG4gIG1heHZhbD86IG51bWJlcjtcbiAgLyoqIFVzZWQgdG8gc2VlZCB0aGUgcmFuZG9tIGdlbmVyYXRvci4gKi9cbiAgc2VlZD86IG51bWJlcjtcbn1cblxuZXhwb3J0IGNsYXNzIFJhbmRvbVVuaWZvcm0gZXh0ZW5kcyBJbml0aWFsaXplciB7XG4gIC8qKiBAbm9jb2xsYXBzZSAqL1xuICBzdGF0aWMgY2xhc3NOYW1lID0gJ1JhbmRvbVVuaWZvcm0nO1xuICByZWFkb25seSBERUZBVUxUX01JTlZBTCA9IC0wLjA1O1xuICByZWFkb25seSBERUZBVUxUX01BWFZBTCA9IDAuMDU7XG4gIHByaXZhdGUgbWludmFsOiBudW1iZXI7XG4gIHByaXZhdGUgbWF4dmFsOiBudW1iZXI7XG4gIHByaXZhdGUgc2VlZDogbnVtYmVyO1xuXG4gIGNvbnN0cnVjdG9yKGFyZ3M6IFJhbmRvbVVuaWZvcm1BcmdzKSB7XG4gICAgc3VwZXIoKTtcbiAgICB0aGlzLm1pbnZhbCA9IGFyZ3MubWludmFsIHx8IHRoaXMuREVGQVVMVF9NSU5WQUw7XG4gICAgdGhpcy5tYXh2YWwgPSBhcmdzLm1heHZhbCB8fCB0aGlzLkRFRkFVTFRfTUFYVkFMO1xuICAgIHRoaXMuc2VlZCA9IGFyZ3Muc2VlZDtcbiAgfVxuXG4gIGFwcGx5KHNoYXBlOiBTaGFwZSwgZHR5cGU/OiBEYXRhVHlwZSk6IFRlbnNvciB7XG4gICAgcmV0dXJuIHJhbmRvbVVuaWZvcm0oc2hhcGUsIHRoaXMubWludmFsLCB0aGlzLm1heHZhbCwgZHR5cGUsIHRoaXMuc2VlZCk7XG4gIH1cblxuICBvdmVycmlkZSBnZXRDb25maWcoKTogc2VyaWFsaXphdGlvbi5Db25maWdEaWN0IHtcbiAgICByZXR1cm4ge21pbnZhbDogdGhpcy5taW52YWwsIG1heHZhbDogdGhpcy5tYXh2YWwsIHNlZWQ6IHRoaXMuc2VlZH07XG4gIH1cbn1cbnNlcmlhbGl6YXRpb24ucmVnaXN0ZXJDbGFzcyhSYW5kb21Vbmlmb3JtKTtcblxuZXhwb3J0IGludGVyZmFjZSBSYW5kb21Ob3JtYWxBcmdzIHtcbiAgLyoqIE1lYW4gb2YgdGhlIHJhbmRvbSB2YWx1ZXMgdG8gZ2VuZXJhdGUuICovXG4gIG1lYW4/OiBudW1iZXI7XG4gIC8qKiBTdGFuZGFyZCBkZXZpYXRpb24gb2YgdGhlIHJhbmRvbSB2YWx1ZXMgdG8gZ2VuZXJhdGUuICovXG4gIHN0ZGRldj86IG51bWJlcjtcbiAgLyoqIFVzZWQgdG8gc2VlZCB0aGUgcmFuZG9tIGdlbmVyYXRvci4gKi9cbiAgc2VlZD86IG51bWJlcjtcbn1cblxuZXhwb3J0IGNsYXNzIFJhbmRvbU5vcm1hbCBleHRlbmRzIEluaXRpYWxpemVyIHtcbiAgLyoqIEBub2NvbGxhcHNlICovXG4gIHN0YXRpYyBjbGFzc05hbWUgPSAnUmFuZG9tTm9ybWFsJztcbiAgcmVhZG9ubHkgREVGQVVMVF9NRUFOID0gMC47XG4gIHJlYWRvbmx5IERFRkFVTFRfU1REREVWID0gMC4wNTtcbiAgcHJpdmF0ZSBtZWFuOiBudW1iZXI7XG4gIHByaXZhdGUgc3RkZGV2OiBudW1iZXI7XG4gIHByaXZhdGUgc2VlZDogbnVtYmVyO1xuXG4gIGNvbnN0cnVjdG9yKGFyZ3M6IFJhbmRvbU5vcm1hbEFyZ3MpIHtcbiAgICBzdXBlcigpO1xuICAgIHRoaXMubWVhbiA9IGFyZ3MubWVhbiB8fCB0aGlzLkRFRkFVTFRfTUVBTjtcbiAgICB0aGlzLnN0ZGRldiA9IGFyZ3Muc3RkZGV2IHx8IHRoaXMuREVGQVVMVF9TVERERVY7XG4gICAgdGhpcy5zZWVkID0gYXJncy5zZWVkO1xuICB9XG5cbiAgYXBwbHkoc2hhcGU6IFNoYXBlLCBkdHlwZT86IERhdGFUeXBlKTogVGVuc29yIHtcbiAgICBkdHlwZSA9IGR0eXBlIHx8ICdmbG9hdDMyJztcbiAgICBpZiAoZHR5cGUgIT09ICdmbG9hdDMyJyAmJiBkdHlwZSAhPT0gJ2ludDMyJykge1xuICAgICAgdGhyb3cgbmV3IE5vdEltcGxlbWVudGVkRXJyb3IoXG4gICAgICAgICAgYHJhbmRvbU5vcm1hbCBkb2VzIG5vdCBzdXBwb3J0IGRUeXBlICR7ZHR5cGV9LmApO1xuICAgIH1cblxuICAgIHJldHVybiBLLnJhbmRvbU5vcm1hbChzaGFwZSwgdGhpcy5tZWFuLCB0aGlzLnN0ZGRldiwgZHR5cGUsIHRoaXMuc2VlZCk7XG4gIH1cblxuICBvdmVycmlkZSBnZXRDb25maWcoKTogc2VyaWFsaXphdGlvbi5Db25maWdEaWN0IHtcbiAgICByZXR1cm4ge21lYW46IHRoaXMubWVhbiwgc3RkZGV2OiB0aGlzLnN0ZGRldiwgc2VlZDogdGhpcy5zZWVkfTtcbiAgfVxufVxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKFJhbmRvbU5vcm1hbCk7XG5cbmV4cG9ydCBpbnRlcmZhY2UgVHJ1bmNhdGVkTm9ybWFsQXJncyB7XG4gIC8qKiBNZWFuIG9mIHRoZSByYW5kb20gdmFsdWVzIHRvIGdlbmVyYXRlLiAqL1xuICBtZWFuPzogbnVtYmVyO1xuICAvKiogU3RhbmRhcmQgZGV2aWF0aW9uIG9mIHRoZSByYW5kb20gdmFsdWVzIHRvIGdlbmVyYXRlLiAqL1xuICBzdGRkZXY/OiBudW1iZXI7XG4gIC8qKiBVc2VkIHRvIHNlZWQgdGhlIHJhbmRvbSBnZW5lcmF0b3IuICovXG4gIHNlZWQ/OiBudW1iZXI7XG59XG5cbmV4cG9ydCBjbGFzcyBUcnVuY2F0ZWROb3JtYWwgZXh0ZW5kcyBJbml0aWFsaXplciB7XG4gIC8qKiBAbm9jb2xsYXBzZSAqL1xuICBzdGF0aWMgY2xhc3NOYW1lID0gJ1RydW5jYXRlZE5vcm1hbCc7XG5cbiAgcmVhZG9ubHkgREVGQVVMVF9NRUFOID0gMC47XG4gIHJlYWRvbmx5IERFRkFVTFRfU1REREVWID0gMC4wNTtcbiAgcHJpdmF0ZSBtZWFuOiBudW1iZXI7XG4gIHByaXZhdGUgc3RkZGV2OiBudW1iZXI7XG4gIHByaXZhdGUgc2VlZDogbnVtYmVyO1xuXG4gIGNvbnN0cnVjdG9yKGFyZ3M6IFRydW5jYXRlZE5vcm1hbEFyZ3MpIHtcbiAgICBzdXBlcigpO1xuICAgIHRoaXMubWVhbiA9IGFyZ3MubWVhbiB8fCB0aGlzLkRFRkFVTFRfTUVBTjtcbiAgICB0aGlzLnN0ZGRldiA9IGFyZ3Muc3RkZGV2IHx8IHRoaXMuREVGQVVMVF9TVERERVY7XG4gICAgdGhpcy5zZWVkID0gYXJncy5zZWVkO1xuICB9XG5cbiAgYXBwbHkoc2hhcGU6IFNoYXBlLCBkdHlwZT86IERhdGFUeXBlKTogVGVuc29yIHtcbiAgICBkdHlwZSA9IGR0eXBlIHx8ICdmbG9hdDMyJztcbiAgICBpZiAoZHR5cGUgIT09ICdmbG9hdDMyJyAmJiBkdHlwZSAhPT0gJ2ludDMyJykge1xuICAgICAgdGhyb3cgbmV3IE5vdEltcGxlbWVudGVkRXJyb3IoXG4gICAgICAgICAgYHRydW5jYXRlZE5vcm1hbCBkb2VzIG5vdCBzdXBwb3J0IGRUeXBlICR7ZHR5cGV9LmApO1xuICAgIH1cbiAgICByZXR1cm4gdHJ1bmNhdGVkTm9ybWFsKHNoYXBlLCB0aGlzLm1lYW4sIHRoaXMuc3RkZGV2LCBkdHlwZSwgdGhpcy5zZWVkKTtcbiAgfVxuXG4gIG92ZXJyaWRlIGdldENvbmZpZygpOiBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3Qge1xuICAgIHJldHVybiB7bWVhbjogdGhpcy5tZWFuLCBzdGRkZXY6IHRoaXMuc3RkZGV2LCBzZWVkOiB0aGlzLnNlZWR9O1xuICB9XG59XG5zZXJpYWxpemF0aW9uLnJlZ2lzdGVyQ2xhc3MoVHJ1bmNhdGVkTm9ybWFsKTtcblxuZXhwb3J0IGludGVyZmFjZSBJZGVudGl0eUFyZ3Mge1xuICAvKipcbiAgICogTXVsdGlwbGljYXRpdmUgZmFjdG9yIHRvIGFwcGx5IHRvIHRoZSBpZGVudGl0eSBtYXRyaXguXG4gICAqL1xuICBnYWluPzogbnVtYmVyO1xufVxuXG5leHBvcnQgY2xhc3MgSWRlbnRpdHkgZXh0ZW5kcyBJbml0aWFsaXplciB7XG4gIC8qKiBAbm9jb2xsYXBzZSAqL1xuICBzdGF0aWMgY2xhc3NOYW1lID0gJ0lkZW50aXR5JztcbiAgcHJpdmF0ZSBnYWluOiBudW1iZXI7XG4gIGNvbnN0cnVjdG9yKGFyZ3M6IElkZW50aXR5QXJncykge1xuICAgIHN1cGVyKCk7XG4gICAgdGhpcy5nYWluID0gYXJncy5nYWluICE9IG51bGwgPyBhcmdzLmdhaW4gOiAxLjA7XG4gIH1cblxuICBhcHBseShzaGFwZTogU2hhcGUsIGR0eXBlPzogRGF0YVR5cGUpOiBUZW5zb3Ige1xuICAgIHJldHVybiB0aWR5KCgpID0+IHtcbiAgICAgIGlmIChzaGFwZS5sZW5ndGggIT09IDIgfHwgc2hhcGVbMF0gIT09IHNoYXBlWzFdKSB7XG4gICAgICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKFxuICAgICAgICAgICAgJ0lkZW50aXR5IG1hdHJpeCBpbml0aWFsaXplciBjYW4gb25seSBiZSB1c2VkIGZvcicgK1xuICAgICAgICAgICAgJyAyRCBzcXVhcmUgbWF0cmljZXMuJyk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICByZXR1cm4gbXVsKHRoaXMuZ2FpbiwgZXllKHNoYXBlWzBdKSk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cblxuICBvdmVycmlkZSBnZXRDb25maWcoKTogc2VyaWFsaXphdGlvbi5Db25maWdEaWN0IHtcbiAgICByZXR1cm4ge2dhaW46IHRoaXMuZ2Fpbn07XG4gIH1cbn1cbnNlcmlhbGl6YXRpb24ucmVnaXN0ZXJDbGFzcyhJZGVudGl0eSk7XG5cbi8qKlxuICogQ29tcHV0ZXMgdGhlIG51bWJlciBvZiBpbnB1dCBhbmQgb3V0cHV0IHVuaXRzIGZvciBhIHdlaWdodCBzaGFwZS5cbiAqIEBwYXJhbSBzaGFwZSBTaGFwZSBvZiB3ZWlnaHQuXG4gKiBAcGFyYW0gZGF0YUZvcm1hdCBkYXRhIGZvcm1hdCB0byB1c2UgZm9yIGNvbnZvbHV0aW9uIGtlcm5lbHMuXG4gKiAgIE5vdGUgdGhhdCBhbGwga2VybmVscyBpbiBLZXJhcyBhcmUgc3RhbmRhcmRpemVkIG9uIHRoZVxuICogICBDSEFOTkVMX0xBU1Qgb3JkZXJpbmcgKGV2ZW4gd2hlbiBpbnB1dHMgYXJlIHNldCB0byBDSEFOTkVMX0ZJUlNUKS5cbiAqIEByZXR1cm4gQW4gbGVuZ3RoLTIgYXJyYXk6IGZhbkluLCBmYW5PdXQuXG4gKi9cbmZ1bmN0aW9uIGNvbXB1dGVGYW5zKFxuICAgIHNoYXBlOiBTaGFwZSwgZGF0YUZvcm1hdDogRGF0YUZvcm1hdCA9ICdjaGFubmVsc0xhc3QnKTogbnVtYmVyW10ge1xuICBsZXQgZmFuSW46IG51bWJlcjtcbiAgbGV0IGZhbk91dDogbnVtYmVyO1xuICBjaGVja0RhdGFGb3JtYXQoZGF0YUZvcm1hdCk7XG4gIGlmIChzaGFwZS5sZW5ndGggPT09IDIpIHtcbiAgICBmYW5JbiA9IHNoYXBlWzBdO1xuICAgIGZhbk91dCA9IHNoYXBlWzFdO1xuICB9IGVsc2UgaWYgKFszLCA0LCA1XS5pbmRleE9mKHNoYXBlLmxlbmd0aCkgIT09IC0xKSB7XG4gICAgaWYgKGRhdGFGb3JtYXQgPT09ICdjaGFubmVsc0ZpcnN0Jykge1xuICAgICAgY29uc3QgcmVjZXB0aXZlRmllbGRTaXplID0gYXJyYXlQcm9kKHNoYXBlLCAyKTtcbiAgICAgIGZhbkluID0gc2hhcGVbMV0gKiByZWNlcHRpdmVGaWVsZFNpemU7XG4gICAgICBmYW5PdXQgPSBzaGFwZVswXSAqIHJlY2VwdGl2ZUZpZWxkU2l6ZTtcbiAgICB9IGVsc2UgaWYgKGRhdGFGb3JtYXQgPT09ICdjaGFubmVsc0xhc3QnKSB7XG4gICAgICBjb25zdCByZWNlcHRpdmVGaWVsZFNpemUgPSBhcnJheVByb2Qoc2hhcGUsIDAsIHNoYXBlLmxlbmd0aCAtIDIpO1xuICAgICAgZmFuSW4gPSBzaGFwZVtzaGFwZS5sZW5ndGggLSAyXSAqIHJlY2VwdGl2ZUZpZWxkU2l6ZTtcbiAgICAgIGZhbk91dCA9IHNoYXBlW3NoYXBlLmxlbmd0aCAtIDFdICogcmVjZXB0aXZlRmllbGRTaXplO1xuICAgIH1cbiAgfSBlbHNlIHtcbiAgICBjb25zdCBzaGFwZVByb2QgPSBhcnJheVByb2Qoc2hhcGUpO1xuICAgIGZhbkluID0gTWF0aC5zcXJ0KHNoYXBlUHJvZCk7XG4gICAgZmFuT3V0ID0gTWF0aC5zcXJ0KHNoYXBlUHJvZCk7XG4gIH1cblxuICByZXR1cm4gW2ZhbkluLCBmYW5PdXRdO1xufVxuXG5leHBvcnQgaW50ZXJmYWNlIFZhcmlhbmNlU2NhbGluZ0FyZ3Mge1xuICAvKiogU2NhbGluZyBmYWN0b3IgKHBvc2l0aXZlIGZsb2F0KS4gKi9cbiAgc2NhbGU/OiBudW1iZXI7XG5cbiAgLyoqIEZhbm5pbmcgbW9kZSBmb3IgaW5wdXRzIGFuZCBvdXRwdXRzLiAqL1xuICBtb2RlPzogRmFuTW9kZTtcblxuICAvKiogUHJvYmFiaWxpc3RpYyBkaXN0cmlidXRpb24gb2YgdGhlIHZhbHVlcy4gKi9cbiAgZGlzdHJpYnV0aW9uPzogRGlzdHJpYnV0aW9uO1xuXG4gIC8qKiBSYW5kb20gbnVtYmVyIGdlbmVyYXRvciBzZWVkLiAqL1xuICBzZWVkPzogbnVtYmVyO1xufVxuXG5leHBvcnQgY2xhc3MgVmFyaWFuY2VTY2FsaW5nIGV4dGVuZHMgSW5pdGlhbGl6ZXIge1xuICAvKiogQG5vY29sbGFwc2UgKi9cbiAgc3RhdGljIGNsYXNzTmFtZSA9ICdWYXJpYW5jZVNjYWxpbmcnO1xuICBwcml2YXRlIHNjYWxlOiBudW1iZXI7XG4gIHByaXZhdGUgbW9kZTogRmFuTW9kZTtcbiAgcHJpdmF0ZSBkaXN0cmlidXRpb246IERpc3RyaWJ1dGlvbjtcbiAgcHJpdmF0ZSBzZWVkOiBudW1iZXI7XG5cbiAgLyoqXG4gICAqIENvbnN0cnVjdG9yIG9mIFZhcmlhbmNlU2NhbGluZy5cbiAgICogQHRocm93cyBWYWx1ZUVycm9yIGZvciBpbnZhbGlkIHZhbHVlIGluIHNjYWxlLlxuICAgKi9cbiAgY29uc3RydWN0b3IoYXJnczogVmFyaWFuY2VTY2FsaW5nQXJncykge1xuICAgIHN1cGVyKCk7XG4gICAgaWYgKGFyZ3Muc2NhbGUgPCAwLjApIHtcbiAgICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKFxuICAgICAgICAgIGBzY2FsZSBtdXN0IGJlIGEgcG9zaXRpdmUgZmxvYXQuIEdvdDogJHthcmdzLnNjYWxlfWApO1xuICAgIH1cbiAgICB0aGlzLnNjYWxlID0gYXJncy5zY2FsZSA9PSBudWxsID8gMS4wIDogYXJncy5zY2FsZTtcbiAgICB0aGlzLm1vZGUgPSBhcmdzLm1vZGUgPT0gbnVsbCA/ICdmYW5JbicgOiBhcmdzLm1vZGU7XG4gICAgY2hlY2tGYW5Nb2RlKHRoaXMubW9kZSk7XG4gICAgdGhpcy5kaXN0cmlidXRpb24gPVxuICAgICAgICBhcmdzLmRpc3RyaWJ1dGlvbiA9PSBudWxsID8gJ25vcm1hbCcgOiBhcmdzLmRpc3RyaWJ1dGlvbjtcbiAgICBjaGVja0Rpc3RyaWJ1dGlvbih0aGlzLmRpc3RyaWJ1dGlvbik7XG4gICAgdGhpcy5zZWVkID0gYXJncy5zZWVkO1xuICB9XG5cbiAgYXBwbHkoc2hhcGU6IFNoYXBlLCBkdHlwZT86IERhdGFUeXBlKTogVGVuc29yIHtcbiAgICBjb25zdCBmYW5zID0gY29tcHV0ZUZhbnMoc2hhcGUpO1xuICAgIGNvbnN0IGZhbkluID0gZmFuc1swXTtcbiAgICBjb25zdCBmYW5PdXQgPSBmYW5zWzFdO1xuICAgIGxldCBzY2FsZSA9IHRoaXMuc2NhbGU7XG4gICAgaWYgKHRoaXMubW9kZSA9PT0gJ2ZhbkluJykge1xuICAgICAgc2NhbGUgLz0gTWF0aC5tYXgoMSwgZmFuSW4pO1xuICAgIH0gZWxzZSBpZiAodGhpcy5tb2RlID09PSAnZmFuT3V0Jykge1xuICAgICAgc2NhbGUgLz0gTWF0aC5tYXgoMSwgZmFuT3V0KTtcbiAgICB9IGVsc2Uge1xuICAgICAgc2NhbGUgLz0gTWF0aC5tYXgoMSwgKGZhbkluICsgZmFuT3V0KSAvIDIpO1xuICAgIH1cblxuICAgIGlmICh0aGlzLmRpc3RyaWJ1dGlvbiA9PT0gJ25vcm1hbCcpIHtcbiAgICAgIGNvbnN0IHN0ZGRldiA9IE1hdGguc3FydChzY2FsZSk7XG4gICAgICBkdHlwZSA9IGR0eXBlIHx8ICdmbG9hdDMyJztcbiAgICAgIGlmIChkdHlwZSAhPT0gJ2Zsb2F0MzInICYmIGR0eXBlICE9PSAnaW50MzInKSB7XG4gICAgICAgIHRocm93IG5ldyBOb3RJbXBsZW1lbnRlZEVycm9yKFxuICAgICAgICAgICAgYCR7dGhpcy5nZXRDbGFzc05hbWUoKX0gZG9lcyBub3Qgc3VwcG9ydCBkVHlwZSAke2R0eXBlfS5gKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiB0cnVuY2F0ZWROb3JtYWwoc2hhcGUsIDAsIHN0ZGRldiwgZHR5cGUsIHRoaXMuc2VlZCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIGNvbnN0IGxpbWl0ID0gTWF0aC5zcXJ0KDMgKiBzY2FsZSk7XG4gICAgICByZXR1cm4gcmFuZG9tVW5pZm9ybShzaGFwZSwgLWxpbWl0LCBsaW1pdCwgZHR5cGUsIHRoaXMuc2VlZCk7XG4gICAgfVxuICB9XG5cbiAgb3ZlcnJpZGUgZ2V0Q29uZmlnKCk6IHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCB7XG4gICAgcmV0dXJuIHtcbiAgICAgIHNjYWxlOiB0aGlzLnNjYWxlLFxuICAgICAgbW9kZTogdGhpcy5tb2RlLFxuICAgICAgZGlzdHJpYnV0aW9uOiB0aGlzLmRpc3RyaWJ1dGlvbixcbiAgICAgIHNlZWQ6IHRoaXMuc2VlZFxuICAgIH07XG4gIH1cbn1cbnNlcmlhbGl6YXRpb24ucmVnaXN0ZXJDbGFzcyhWYXJpYW5jZVNjYWxpbmcpO1xuXG5leHBvcnQgaW50ZXJmYWNlIFNlZWRPbmx5SW5pdGlhbGl6ZXJBcmdzIHtcbiAgLyoqIFJhbmRvbSBudW1iZXIgZ2VuZXJhdG9yIHNlZWQuICovXG4gIHNlZWQ/OiBudW1iZXI7XG59XG5cbmV4cG9ydCBjbGFzcyBHbG9yb3RVbmlmb3JtIGV4dGVuZHMgVmFyaWFuY2VTY2FsaW5nIHtcbiAgLyoqIEBub2NvbGxhcHNlICovXG4gIHN0YXRpYyBvdmVycmlkZSBjbGFzc05hbWUgPSAnR2xvcm90VW5pZm9ybSc7XG5cbiAgLyoqXG4gICAqIENvbnN0cnVjdG9yIG9mIEdsb3JvdFVuaWZvcm1cbiAgICogQHBhcmFtIHNjYWxlXG4gICAqIEBwYXJhbSBtb2RlXG4gICAqIEBwYXJhbSBkaXN0cmlidXRpb25cbiAgICogQHBhcmFtIHNlZWRcbiAgICovXG4gIGNvbnN0cnVjdG9yKGFyZ3M/OiBTZWVkT25seUluaXRpYWxpemVyQXJncykge1xuICAgIHN1cGVyKHtcbiAgICAgIHNjYWxlOiAxLjAsXG4gICAgICBtb2RlOiAnZmFuQXZnJyxcbiAgICAgIGRpc3RyaWJ1dGlvbjogJ3VuaWZvcm0nLFxuICAgICAgc2VlZDogYXJncyA9PSBudWxsID8gbnVsbCA6IGFyZ3Muc2VlZFxuICAgIH0pO1xuICB9XG5cbiAgb3ZlcnJpZGUgZ2V0Q2xhc3NOYW1lKCk6IHN0cmluZyB7XG4gICAgLy8gSW4gUHl0aG9uIEtlcmFzLCBHbG9yb3RVbmlmb3JtIGlzIG5vdCBhIGNsYXNzLCBidXQgYSBoZWxwZXIgbWV0aG9kXG4gICAgLy8gdGhhdCBjcmVhdGVzIGEgVmFyaWFuY2VTY2FsaW5nIG9iamVjdC4gVXNlICdWYXJpYW5jZVNjYWxpbmcnIGFzXG4gICAgLy8gY2xhc3MgbmFtZSB0byBiZSBjb21wYXRpYmxlIHdpdGggdGhhdC5cbiAgICByZXR1cm4gVmFyaWFuY2VTY2FsaW5nLmNsYXNzTmFtZTtcbiAgfVxufVxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKEdsb3JvdFVuaWZvcm0pO1xuXG5leHBvcnQgY2xhc3MgR2xvcm90Tm9ybWFsIGV4dGVuZHMgVmFyaWFuY2VTY2FsaW5nIHtcbiAgLyoqIEBub2NvbGxhcHNlICovXG4gIHN0YXRpYyBvdmVycmlkZSBjbGFzc05hbWUgPSAnR2xvcm90Tm9ybWFsJztcblxuICAvKipcbiAgICogQ29uc3RydWN0b3Igb2YgR2xvcm90Tm9ybWFsLlxuICAgKiBAcGFyYW0gc2NhbGVcbiAgICogQHBhcmFtIG1vZGVcbiAgICogQHBhcmFtIGRpc3RyaWJ1dGlvblxuICAgKiBAcGFyYW0gc2VlZFxuICAgKi9cbiAgY29uc3RydWN0b3IoYXJncz86IFNlZWRPbmx5SW5pdGlhbGl6ZXJBcmdzKSB7XG4gICAgc3VwZXIoe1xuICAgICAgc2NhbGU6IDEuMCxcbiAgICAgIG1vZGU6ICdmYW5BdmcnLFxuICAgICAgZGlzdHJpYnV0aW9uOiAnbm9ybWFsJyxcbiAgICAgIHNlZWQ6IGFyZ3MgPT0gbnVsbCA/IG51bGwgOiBhcmdzLnNlZWRcbiAgICB9KTtcbiAgfVxuXG4gIG92ZXJyaWRlIGdldENsYXNzTmFtZSgpOiBzdHJpbmcge1xuICAgIC8vIEluIFB5dGhvbiBLZXJhcywgR2xvcm90Tm9ybWFsIGlzIG5vdCBhIGNsYXNzLCBidXQgYSBoZWxwZXIgbWV0aG9kXG4gICAgLy8gdGhhdCBjcmVhdGVzIGEgVmFyaWFuY2VTY2FsaW5nIG9iamVjdC4gVXNlICdWYXJpYW5jZVNjYWxpbmcnIGFzXG4gICAgLy8gY2xhc3MgbmFtZSB0byBiZSBjb21wYXRpYmxlIHdpdGggdGhhdC5cbiAgICByZXR1cm4gVmFyaWFuY2VTY2FsaW5nLmNsYXNzTmFtZTtcbiAgfVxufVxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKEdsb3JvdE5vcm1hbCk7XG5cbmV4cG9ydCBjbGFzcyBIZU5vcm1hbCBleHRlbmRzIFZhcmlhbmNlU2NhbGluZyB7XG4gIC8qKiBAbm9jb2xsYXBzZSAqL1xuICBzdGF0aWMgb3ZlcnJpZGUgY2xhc3NOYW1lID0gJ0hlTm9ybWFsJztcblxuICBjb25zdHJ1Y3RvcihhcmdzPzogU2VlZE9ubHlJbml0aWFsaXplckFyZ3MpIHtcbiAgICBzdXBlcih7XG4gICAgICBzY2FsZTogMi4wLFxuICAgICAgbW9kZTogJ2ZhbkluJyxcbiAgICAgIGRpc3RyaWJ1dGlvbjogJ25vcm1hbCcsXG4gICAgICBzZWVkOiBhcmdzID09IG51bGwgPyBudWxsIDogYXJncy5zZWVkXG4gICAgfSk7XG4gIH1cblxuICBvdmVycmlkZSBnZXRDbGFzc05hbWUoKTogc3RyaW5nIHtcbiAgICAvLyBJbiBQeXRob24gS2VyYXMsIEhlTm9ybWFsIGlzIG5vdCBhIGNsYXNzLCBidXQgYSBoZWxwZXIgbWV0aG9kXG4gICAgLy8gdGhhdCBjcmVhdGVzIGEgVmFyaWFuY2VTY2FsaW5nIG9iamVjdC4gVXNlICdWYXJpYW5jZVNjYWxpbmcnIGFzXG4gICAgLy8gY2xhc3MgbmFtZSB0byBiZSBjb21wYXRpYmxlIHdpdGggdGhhdC5cbiAgICByZXR1cm4gVmFyaWFuY2VTY2FsaW5nLmNsYXNzTmFtZTtcbiAgfVxufVxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKEhlTm9ybWFsKTtcblxuZXhwb3J0IGNsYXNzIEhlVW5pZm9ybSBleHRlbmRzIFZhcmlhbmNlU2NhbGluZyB7XG4gIC8qKiBAbm9jb2xsYXBzZSAqL1xuICBzdGF0aWMgb3ZlcnJpZGUgY2xhc3NOYW1lID0gJ0hlVW5pZm9ybSc7XG5cbiAgY29uc3RydWN0b3IoYXJncz86IFNlZWRPbmx5SW5pdGlhbGl6ZXJBcmdzKSB7XG4gICAgc3VwZXIoe1xuICAgICAgc2NhbGU6IDIuMCxcbiAgICAgIG1vZGU6ICdmYW5JbicsXG4gICAgICBkaXN0cmlidXRpb246ICd1bmlmb3JtJyxcbiAgICAgIHNlZWQ6IGFyZ3MgPT0gbnVsbCA/IG51bGwgOiBhcmdzLnNlZWRcbiAgICB9KTtcbiAgfVxuXG4gIG92ZXJyaWRlIGdldENsYXNzTmFtZSgpOiBzdHJpbmcge1xuICAgIC8vIEluIFB5dGhvbiBLZXJhcywgSGVVbmlmb3JtIGlzIG5vdCBhIGNsYXNzLCBidXQgYSBoZWxwZXIgbWV0aG9kXG4gICAgLy8gdGhhdCBjcmVhdGVzIGEgVmFyaWFuY2VTY2FsaW5nIG9iamVjdC4gVXNlICdWYXJpYW5jZVNjYWxpbmcnIGFzXG4gICAgLy8gY2xhc3MgbmFtZSB0byBiZSBjb21wYXRpYmxlIHdpdGggdGhhdC5cbiAgICByZXR1cm4gVmFyaWFuY2VTY2FsaW5nLmNsYXNzTmFtZTtcbiAgfVxufVxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKEhlVW5pZm9ybSk7XG5cbmV4cG9ydCBjbGFzcyBMZUN1bk5vcm1hbCBleHRlbmRzIFZhcmlhbmNlU2NhbGluZyB7XG4gIC8qKiBAbm9jb2xsYXBzZSAqL1xuICBzdGF0aWMgb3ZlcnJpZGUgY2xhc3NOYW1lID0gJ0xlQ3VuTm9ybWFsJztcblxuICBjb25zdHJ1Y3RvcihhcmdzPzogU2VlZE9ubHlJbml0aWFsaXplckFyZ3MpIHtcbiAgICBzdXBlcih7XG4gICAgICBzY2FsZTogMS4wLFxuICAgICAgbW9kZTogJ2ZhbkluJyxcbiAgICAgIGRpc3RyaWJ1dGlvbjogJ25vcm1hbCcsXG4gICAgICBzZWVkOiBhcmdzID09IG51bGwgPyBudWxsIDogYXJncy5zZWVkXG4gICAgfSk7XG4gIH1cblxuICBvdmVycmlkZSBnZXRDbGFzc05hbWUoKTogc3RyaW5nIHtcbiAgICAvLyBJbiBQeXRob24gS2VyYXMsIExlQ3VuTm9ybWFsIGlzIG5vdCBhIGNsYXNzLCBidXQgYSBoZWxwZXIgbWV0aG9kXG4gICAgLy8gdGhhdCBjcmVhdGVzIGEgVmFyaWFuY2VTY2FsaW5nIG9iamVjdC4gVXNlICdWYXJpYW5jZVNjYWxpbmcnIGFzXG4gICAgLy8gY2xhc3MgbmFtZSB0byBiZSBjb21wYXRpYmxlIHdpdGggdGhhdC5cbiAgICByZXR1cm4gVmFyaWFuY2VTY2FsaW5nLmNsYXNzTmFtZTtcbiAgfVxufVxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKExlQ3VuTm9ybWFsKTtcblxuZXhwb3J0IGNsYXNzIExlQ3VuVW5pZm9ybSBleHRlbmRzIFZhcmlhbmNlU2NhbGluZyB7XG4gIC8qKiBAbm9jb2xsYXBzZSAqL1xuICBzdGF0aWMgb3ZlcnJpZGUgY2xhc3NOYW1lID0gJ0xlQ3VuVW5pZm9ybSc7XG5cbiAgY29uc3RydWN0b3IoYXJncz86IFNlZWRPbmx5SW5pdGlhbGl6ZXJBcmdzKSB7XG4gICAgc3VwZXIoe1xuICAgICAgc2NhbGU6IDEuMCxcbiAgICAgIG1vZGU6ICdmYW5JbicsXG4gICAgICBkaXN0cmlidXRpb246ICd1bmlmb3JtJyxcbiAgICAgIHNlZWQ6IGFyZ3MgPT0gbnVsbCA/IG51bGwgOiBhcmdzLnNlZWRcbiAgICB9KTtcbiAgfVxuXG4gIG92ZXJyaWRlIGdldENsYXNzTmFtZSgpOiBzdHJpbmcge1xuICAgIC8vIEluIFB5dGhvbiBLZXJhcywgTGVDdW5Vbmlmb3JtIGlzIG5vdCBhIGNsYXNzLCBidXQgYSBoZWxwZXIgbWV0aG9kXG4gICAgLy8gdGhhdCBjcmVhdGVzIGEgVmFyaWFuY2VTY2FsaW5nIG9iamVjdC4gVXNlICdWYXJpYW5jZVNjYWxpbmcnIGFzXG4gICAgLy8gY2xhc3MgbmFtZSB0byBiZSBjb21wYXRpYmxlIHdpdGggdGhhdC5cbiAgICByZXR1cm4gVmFyaWFuY2VTY2FsaW5nLmNsYXNzTmFtZTtcbiAgfVxufVxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKExlQ3VuVW5pZm9ybSk7XG5cbmV4cG9ydCBpbnRlcmZhY2UgT3J0aG9nb25hbEFyZ3MgZXh0ZW5kcyBTZWVkT25seUluaXRpYWxpemVyQXJncyB7XG4gIC8qKlxuICAgKiBNdWx0aXBsaWNhdGl2ZSBmYWN0b3IgdG8gYXBwbHkgdG8gdGhlIG9ydGhvZ29uYWwgbWF0cml4LiBEZWZhdWx0cyB0byAxLlxuICAgKi9cbiAgZ2Fpbj86IG51bWJlcjtcbn1cblxuZXhwb3J0IGNsYXNzIE9ydGhvZ29uYWwgZXh0ZW5kcyBJbml0aWFsaXplciB7XG4gIC8qKiBAbm9jb2xsYXBzZSAqL1xuICBzdGF0aWMgY2xhc3NOYW1lID0gJ09ydGhvZ29uYWwnO1xuICByZWFkb25seSBERUZBVUxUX0dBSU4gPSAxO1xuICByZWFkb25seSBFTEVNRU5UU19XQVJOX1NMT1cgPSAyMDAwO1xuICBwcm90ZWN0ZWQgcmVhZG9ubHkgZ2FpbjogbnVtYmVyO1xuICBwcm90ZWN0ZWQgcmVhZG9ubHkgc2VlZDogbnVtYmVyO1xuXG4gIGNvbnN0cnVjdG9yKGFyZ3M/OiBPcnRob2dvbmFsQXJncykge1xuICAgIHN1cGVyKCk7XG4gICAgdGhpcy5nYWluID0gYXJncy5nYWluID09IG51bGwgPyB0aGlzLkRFRkFVTFRfR0FJTiA6IGFyZ3MuZ2FpbjtcbiAgICB0aGlzLnNlZWQgPSBhcmdzLnNlZWQ7XG4gIH1cblxuICBhcHBseShzaGFwZTogU2hhcGUsIGR0eXBlPzogRGF0YVR5cGUpOiBUZW5zb3Ige1xuICAgIHJldHVybiB0aWR5KCgpID0+IHtcbiAgICAgIGlmIChzaGFwZS5sZW5ndGggPCAyKSB7XG4gICAgICAgIHRocm93IG5ldyBOb3RJbXBsZW1lbnRlZEVycm9yKCdTaGFwZSBtdXN0IGJlIGF0IGxlYXN0IDJELicpO1xuICAgICAgfVxuICAgICAgaWYgKGR0eXBlICE9PSAnaW50MzInICYmIGR0eXBlICE9PSAnZmxvYXQzMicgJiYgZHR5cGUgIT09IHVuZGVmaW5lZCkge1xuICAgICAgICB0aHJvdyBuZXcgVHlwZUVycm9yKGBVbnN1cHBvcnRlZCBkYXRhIHR5cGUgJHtkdHlwZX0uYCk7XG4gICAgICB9XG4gICAgICBkdHlwZSA9IGR0eXBlIGFzICdpbnQzMicgfCAnZmxvYXQzMicgfCB1bmRlZmluZWQ7XG5cbiAgICAgIC8vIGZsYXR0ZW4gdGhlIGlucHV0IHNoYXBlIHdpdGggdGhlIGxhc3QgZGltZW5zaW9uIHJlbWFpbmluZyBpdHNcbiAgICAgIC8vIG9yaWdpbmFsIHNoYXBlIHNvIGl0IHdvcmtzIGZvciBjb252MmRcbiAgICAgIGNvbnN0IG51bVJvd3MgPSB1dGlsLnNpemVGcm9tU2hhcGUoc2hhcGUuc2xpY2UoMCwgLTEpKTtcbiAgICAgIGNvbnN0IG51bUNvbHMgPSBzaGFwZVtzaGFwZS5sZW5ndGggLSAxXTtcbiAgICAgIGNvbnN0IG51bUVsZW1lbnRzID0gbnVtUm93cyAqIG51bUNvbHM7XG4gICAgICBpZiAobnVtRWxlbWVudHMgPiB0aGlzLkVMRU1FTlRTX1dBUk5fU0xPVykge1xuICAgICAgICBjb25zb2xlLndhcm4oXG4gICAgICAgICAgICBgT3J0aG9nb25hbCBpbml0aWFsaXplciBpcyBiZWluZyBjYWxsZWQgb24gYSBtYXRyaXggd2l0aCBtb3JlIGAgK1xuICAgICAgICAgICAgYHRoYW4gJHt0aGlzLkVMRU1FTlRTX1dBUk5fU0xPV30gKCR7bnVtRWxlbWVudHN9KSBlbGVtZW50czogYCArXG4gICAgICAgICAgICBgU2xvd25lc3MgbWF5IHJlc3VsdC5gKTtcbiAgICAgIH1cbiAgICAgIGNvbnN0IGZsYXRTaGFwZSA9XG4gICAgICAgICAgW01hdGgubWF4KG51bUNvbHMsIG51bVJvd3MpLCBNYXRoLm1pbihudW1Db2xzLCBudW1Sb3dzKV07XG5cbiAgICAgIC8vIEdlbmVyYXRlIGEgcmFuZG9tIG1hdHJpeFxuICAgICAgY29uc3QgcmFuZE5vcm1hbE1hdCA9IEsucmFuZG9tTm9ybWFsKGZsYXRTaGFwZSwgMCwgMSwgZHR5cGUsIHRoaXMuc2VlZCk7XG5cbiAgICAgIC8vIENvbXB1dGUgUVIgZmFjdG9yaXphdGlvblxuICAgICAgY29uc3QgcXIgPSBsaW5hbGcucXIocmFuZE5vcm1hbE1hdCwgZmFsc2UpO1xuICAgICAgbGV0IHFNYXQgPSBxclswXTtcbiAgICAgIGNvbnN0IHJNYXQgPSBxclsxXTtcblxuICAgICAgLy8gTWFrZSBRIHVuaWZvcm1cbiAgICAgIGNvbnN0IGRpYWcgPSByTWF0LmZsYXR0ZW4oKS5zdHJpZGVkU2xpY2UoXG4gICAgICAgICAgWzBdLCBbTWF0aC5taW4obnVtQ29scywgbnVtUm93cykgKiBNYXRoLm1pbihudW1Db2xzLCBudW1Sb3dzKV0sXG4gICAgICAgICAgW01hdGgubWluKG51bUNvbHMsIG51bVJvd3MpICsgMV0pO1xuICAgICAgcU1hdCA9IG11bChxTWF0LCBkaWFnLnNpZ24oKSk7XG4gICAgICBpZiAobnVtUm93cyA8IG51bUNvbHMpIHtcbiAgICAgICAgcU1hdCA9IHFNYXQudHJhbnNwb3NlKCk7XG4gICAgICB9XG5cbiAgICAgIHJldHVybiBtdWwoc2NhbGFyKHRoaXMuZ2FpbiksIHFNYXQucmVzaGFwZShzaGFwZSkpO1xuICAgIH0pO1xuICB9XG5cbiAgb3ZlcnJpZGUgZ2V0Q29uZmlnKCk6IHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGdhaW46IHRoaXMuZ2FpbixcbiAgICAgIHNlZWQ6IHRoaXMuc2VlZCxcbiAgICB9O1xuICB9XG59XG5zZXJpYWxpemF0aW9uLnJlZ2lzdGVyQ2xhc3MoT3J0aG9nb25hbCk7XG5cbi8qKiBAZG9jaW5saW5lICovXG5leHBvcnQgdHlwZSBJbml0aWFsaXplcklkZW50aWZpZXIgPVxuICAgICdjb25zdGFudCd8J2dsb3JvdE5vcm1hbCd8J2dsb3JvdFVuaWZvcm0nfCdoZU5vcm1hbCd8J2hlVW5pZm9ybSd8J2lkZW50aXR5J3xcbiAgICAnbGVDdW5Ob3JtYWwnfCdsZUN1blVuaWZvcm0nfCdvbmVzJ3wnb3J0aG9nb25hbCd8J3JhbmRvbU5vcm1hbCd8XG4gICAgJ3JhbmRvbVVuaWZvcm0nfCd0cnVuY2F0ZWROb3JtYWwnfCd2YXJpYW5jZVNjYWxpbmcnfCd6ZXJvcyd8c3RyaW5nO1xuXG4vLyBNYXBzIHRoZSBKYXZhU2NyaXB0LWxpa2UgaWRlbnRpZmllciBrZXlzIHRvIHRoZSBjb3JyZXNwb25kaW5nIHJlZ2lzdHJ5XG4vLyBzeW1ib2xzLlxuZXhwb3J0IGNvbnN0IElOSVRJQUxJWkVSX0lERU5USUZJRVJfUkVHSVNUUllfU1lNQk9MX01BUDpcbiAgICB7W2lkZW50aWZpZXIgaW4gSW5pdGlhbGl6ZXJJZGVudGlmaWVyXTogc3RyaW5nfSA9IHtcbiAgICAgICdjb25zdGFudCc6ICdDb25zdGFudCcsXG4gICAgICAnZ2xvcm90Tm9ybWFsJzogJ0dsb3JvdE5vcm1hbCcsXG4gICAgICAnZ2xvcm90VW5pZm9ybSc6ICdHbG9yb3RVbmlmb3JtJyxcbiAgICAgICdoZU5vcm1hbCc6ICdIZU5vcm1hbCcsXG4gICAgICAnaGVVbmlmb3JtJzogJ0hlVW5pZm9ybScsXG4gICAgICAnaWRlbnRpdHknOiAnSWRlbnRpdHknLFxuICAgICAgJ2xlQ3VuTm9ybWFsJzogJ0xlQ3VuTm9ybWFsJyxcbiAgICAgICdsZUN1blVuaWZvcm0nOiAnTGVDdW5Vbmlmb3JtJyxcbiAgICAgICdvbmVzJzogJ09uZXMnLFxuICAgICAgJ29ydGhvZ29uYWwnOiAnT3J0aG9nb25hbCcsXG4gICAgICAncmFuZG9tTm9ybWFsJzogJ1JhbmRvbU5vcm1hbCcsXG4gICAgICAncmFuZG9tVW5pZm9ybSc6ICdSYW5kb21Vbmlmb3JtJyxcbiAgICAgICd0cnVuY2F0ZWROb3JtYWwnOiAnVHJ1bmNhdGVkTm9ybWFsJyxcbiAgICAgICd2YXJpYW5jZVNjYWxpbmcnOiAnVmFyaWFuY2VTY2FsaW5nJyxcbiAgICAgICd6ZXJvcyc6ICdaZXJvcydcbiAgICB9O1xuXG5mdW5jdGlvbiBkZXNlcmlhbGl6ZUluaXRpYWxpemVyKFxuICAgIGNvbmZpZzogc2VyaWFsaXphdGlvbi5Db25maWdEaWN0LFxuICAgIGN1c3RvbU9iamVjdHM6IHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCA9IHt9KTogSW5pdGlhbGl6ZXIge1xuICByZXR1cm4gZGVzZXJpYWxpemVLZXJhc09iamVjdChcbiAgICAgIGNvbmZpZywgc2VyaWFsaXphdGlvbi5TZXJpYWxpemF0aW9uTWFwLmdldE1hcCgpLmNsYXNzTmFtZU1hcCxcbiAgICAgIGN1c3RvbU9iamVjdHMsICdpbml0aWFsaXplcicpO1xufVxuXG5leHBvcnQgZnVuY3Rpb24gc2VyaWFsaXplSW5pdGlhbGl6ZXIoaW5pdGlhbGl6ZXI6IEluaXRpYWxpemVyKTpcbiAgICBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3RWYWx1ZSB7XG4gIHJldHVybiBzZXJpYWxpemVLZXJhc09iamVjdChpbml0aWFsaXplcik7XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBnZXRJbml0aWFsaXplcihpZGVudGlmaWVyOiBJbml0aWFsaXplcklkZW50aWZpZXJ8SW5pdGlhbGl6ZXJ8XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2VyaWFsaXphdGlvbi5Db25maWdEaWN0KTogSW5pdGlhbGl6ZXIge1xuICBpZiAodHlwZW9mIGlkZW50aWZpZXIgPT09ICdzdHJpbmcnKSB7XG4gICAgY29uc3QgY2xhc3NOYW1lID0gaWRlbnRpZmllciBpbiBJTklUSUFMSVpFUl9JREVOVElGSUVSX1JFR0lTVFJZX1NZTUJPTF9NQVAgP1xuICAgICAgICBJTklUSUFMSVpFUl9JREVOVElGSUVSX1JFR0lTVFJZX1NZTUJPTF9NQVBbaWRlbnRpZmllcl0gOlxuICAgICAgICBpZGVudGlmaWVyO1xuICAgIC8qIFdlIGhhdmUgZm91ciAnaGVscGVyJyBjbGFzc2VzIGZvciBjb21tb24gaW5pdGlhbGl6ZXJzIHRoYXRcbiAgICBhbGwgZ2V0IHNlcmlhbGl6ZWQgYXMgJ1ZhcmlhbmNlU2NhbGluZycgYW5kIHNob3VsZG4ndCBnbyB0aHJvdWdoXG4gICAgdGhlIGRlc2VyaWFsaXplSW5pdGlhbGl6ZXIgcGF0aHdheS4gKi9cbiAgICBpZiAoY2xhc3NOYW1lID09PSAnR2xvcm90Tm9ybWFsJykge1xuICAgICAgcmV0dXJuIG5ldyBHbG9yb3ROb3JtYWwoKTtcbiAgICB9IGVsc2UgaWYgKGNsYXNzTmFtZSA9PT0gJ0dsb3JvdFVuaWZvcm0nKSB7XG4gICAgICByZXR1cm4gbmV3IEdsb3JvdFVuaWZvcm0oKTtcbiAgICB9IGVsc2UgaWYgKGNsYXNzTmFtZSA9PT0gJ0hlTm9ybWFsJykge1xuICAgICAgcmV0dXJuIG5ldyBIZU5vcm1hbCgpO1xuICAgIH0gZWxzZSBpZiAoY2xhc3NOYW1lID09PSAnSGVVbmlmb3JtJykge1xuICAgICAgcmV0dXJuIG5ldyBIZVVuaWZvcm0oKTtcbiAgICB9IGVsc2UgaWYgKGNsYXNzTmFtZSA9PT0gJ0xlQ3VuTm9ybWFsJykge1xuICAgICAgcmV0dXJuIG5ldyBMZUN1bk5vcm1hbCgpO1xuICAgIH0gZWxzZSBpZiAoY2xhc3NOYW1lID09PSAnTGVDdW5Vbmlmb3JtJykge1xuICAgICAgcmV0dXJuIG5ldyBMZUN1blVuaWZvcm0oKTtcbiAgICB9IGVsc2Uge1xuICAgICAgY29uc3QgY29uZmlnOiBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3QgPSB7fTtcbiAgICAgIGNvbmZpZ1snY2xhc3NOYW1lJ10gPSBjbGFzc05hbWU7XG4gICAgICBjb25maWdbJ2NvbmZpZyddID0ge307XG4gICAgICByZXR1cm4gZGVzZXJpYWxpemVJbml0aWFsaXplcihjb25maWcpO1xuICAgIH1cbiAgfSBlbHNlIGlmIChpZGVudGlmaWVyIGluc3RhbmNlb2YgSW5pdGlhbGl6ZXIpIHtcbiAgICByZXR1cm4gaWRlbnRpZmllcjtcbiAgfSBlbHNlIHtcbiAgICByZXR1cm4gZGVzZXJpYWxpemVJbml0aWFsaXplcihpZGVudGlmaWVyKTtcbiAgfVxufVxuIl19