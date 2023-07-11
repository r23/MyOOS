/**
 * @license
 * Copyright 2023 CodeSmith LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
import { image, serialization, tidy } from '@tensorflow/tfjs-core';
import { getExactlyOneTensor, getExactlyOneShape } from '../../utils/types_utils';
import { ValueError } from '../../errors';
import { BaseRandomLayer } from '../../engine/base_random_layer';
import { randomUniform } from '@tensorflow/tfjs-core';
const INTERPOLATION_KEYS = ['bilinear', 'nearest'];
export const INTERPOLATION_METHODS = new Set(INTERPOLATION_KEYS);
/**
 * Preprocessing Layer with randomly varies image during training
 *
 * This layer randomly adjusts the width of a batch of images of a
 * batch of images by a random factor.
 *
 * The input should be a 3D (unbatched) or
 * 4D (batched) tensor in the `"channels_last"` image data format. Input pixel
 * values can be of any range (e.g. `[0., 1.)` or `[0, 255]`) and of interger
 * or floating point dtype. By default, the layer will output floats.
 *
 * tf methods implemented in tfjs: 'bilinear', 'nearest',
 * tf methods unimplemented in tfjs: 'bicubic', 'area', 'lanczos3', 'lanczos5',
 *                                   'gaussian', 'mitchellcubic'
 *
 */
export class RandomWidth extends BaseRandomLayer {
    constructor(args) {
        super(args);
        const { factor, interpolation = 'bilinear' } = args;
        this.factor = factor;
        if (Array.isArray(this.factor) && this.factor.length === 2) {
            this.widthLower = this.factor[0];
            this.widthUpper = this.factor[1];
        }
        else if (!Array.isArray(this.factor) && this.factor > 0) {
            this.widthLower = -this.factor;
            this.widthUpper = this.factor;
        }
        else {
            throw new ValueError(`Invalid factor: ${this.factor}. Must be positive number or tuple of 2 numbers`);
        }
        if (this.widthLower < -1.0 || this.widthUpper < -1.0) {
            throw new ValueError(`factor must have values larger than -1. Got: ${this.factor}`);
        }
        if (this.widthUpper < this.widthLower) {
            throw new ValueError(`factor cannot have upper bound less than lower bound.
        Got upper bound: ${this.widthUpper}.
        Got lower bound: ${this.widthLower}
      `);
        }
        if (interpolation) {
            if (INTERPOLATION_METHODS.has(interpolation)) {
                this.interpolation = interpolation;
            }
            else {
                throw new ValueError(`Invalid interpolation parameter: ${interpolation} is not implemented`);
            }
        }
    }
    getConfig() {
        const config = {
            'factor': this.factor,
            'interpolation': this.interpolation,
        };
        const baseConfig = super.getConfig();
        Object.assign(config, baseConfig);
        return config;
    }
    computeOutputShape(inputShape) {
        inputShape = getExactlyOneShape(inputShape);
        const numChannels = inputShape[2];
        return [this.imgHeight, -1, numChannels];
    }
    call(inputs, kwargs) {
        return tidy(() => {
            const input = getExactlyOneTensor(inputs);
            this.imgHeight = input.shape[input.shape.length - 3];
            const imgWidth = input.shape[input.shape.length - 2];
            this.widthFactor = randomUniform([1], (1.0 + this.widthLower), (1.0 + this.widthUpper), 'float32', this.randomGenerator.next());
            let adjustedWidth = this.widthFactor.dataSync()[0] * imgWidth;
            adjustedWidth = Math.round(adjustedWidth);
            const size = [this.imgHeight, adjustedWidth];
            switch (this.interpolation) {
                case 'bilinear':
                    return image.resizeBilinear(inputs, size);
                case 'nearest':
                    return image.resizeNearestNeighbor(inputs, size);
                default:
                    throw new Error(`Interpolation is ${this.interpolation}
          but only ${[...INTERPOLATION_METHODS]} are supported`);
            }
        });
    }
}
/** @nocollapse */
RandomWidth.className = 'RandomWidth';
serialization.registerClass(RandomWidth);
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoicmFuZG9tX3dpZHRoLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsiLi4vLi4vLi4vLi4vLi4vLi4vLi4vdGZqcy1sYXllcnMvc3JjL2xheWVycy9wcmVwcm9jZXNzaW5nL3JhbmRvbV93aWR0aC50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7R0FRRztBQUVILE9BQU8sRUFBRSxLQUFLLEVBQVEsYUFBYSxFQUFVLElBQUksRUFBRSxNQUFNLHVCQUF1QixDQUFDO0FBQ2pGLE9BQU8sRUFBRSxtQkFBbUIsRUFBRSxrQkFBa0IsRUFBRSxNQUFNLHlCQUF5QixDQUFDO0FBR2xGLE9BQU8sRUFBRSxVQUFVLEVBQUUsTUFBTSxjQUFjLENBQUM7QUFDMUMsT0FBTyxFQUF1QixlQUFlLEVBQUUsTUFBTSxnQ0FBZ0MsQ0FBQztBQUN0RixPQUFPLEVBQUUsYUFBYSxFQUFFLE1BQU0sdUJBQXVCLENBQUM7QUFTdEQsTUFBTSxrQkFBa0IsR0FBRyxDQUFDLFVBQVUsRUFBRSxTQUFTLENBQVUsQ0FBQztBQUM1RCxNQUFNLENBQUMsTUFBTSxxQkFBcUIsR0FBRyxJQUFJLEdBQUcsQ0FBQyxrQkFBa0IsQ0FBQyxDQUFDO0FBR2pFOzs7Ozs7Ozs7Ozs7Ozs7R0FlRztBQUVILE1BQU0sT0FBTyxXQUFZLFNBQVEsZUFBZTtJQVU5QyxZQUFZLElBQXFCO1FBQy9CLEtBQUssQ0FBQyxJQUFJLENBQUMsQ0FBQztRQUNaLE1BQU0sRUFBQyxNQUFNLEVBQUUsYUFBYSxHQUFHLFVBQVUsRUFBQyxHQUFHLElBQUksQ0FBQztRQUVsRCxJQUFJLENBQUMsTUFBTSxHQUFHLE1BQU0sQ0FBQztRQUVyQixJQUFJLEtBQUssQ0FBQyxPQUFPLENBQUMsSUFBSSxDQUFDLE1BQU0sQ0FBQyxJQUFJLElBQUksQ0FBQyxNQUFNLENBQUMsTUFBTSxLQUFLLENBQUMsRUFBRTtZQUMxRCxJQUFJLENBQUMsVUFBVSxHQUFHLElBQUksQ0FBQyxNQUFNLENBQUMsQ0FBQyxDQUFDLENB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WUFDOUQsYUFBYSxHQUFHLElBQUksQ0FBQyxLQUFLLENBQUMsYUFBYSxDQUFDLENBQUM7WUFFMUMsTUFBTSxJQUFJLEdBQW9CLENBQUMsSUFBSSxDQUFDLFNBQVMsRUFBRSxhQUFhLENBQUMsQ0FBQztZQUU5RCxRQUFRLElBQUksQ0FBQyxhQUFhLEVBQUU7Z0JBQzFCLEtBQUssVUFBVTtvQkFDYixPQUFPLEtBQUssQ0FBQyxjQUFjLENBQUMsTUFBTSxFQUFFLElBQUksQ0FBQyxDQUFDO2dCQUM1QyxLQUFLLFNBQVM7b0JBQ1osT0FBTyxLQUFLLENBQUMscUJBQXFCLENBQUMsTUFBTSxFQUFFLElBQUksQ0FBQyxDQUFDO2dCQUNuRDtvQkFDRSxNQUFNLElBQUksS0FBSyxDQUFDLG9CQUFvQixJQUFJLENBQUMsYUFBYTtxQkFDM0MsQ0FBQyxHQUFHLHFCQUFxQixDQUFDLGdCQUFnQixDQUFDLENBQUM7YUFDMUQ7UUFDSCxDQUFDLENBQUMsQ0FBQztJQUNMLENBQUM7O0FBL0ZELGtCQUFrQjtBQUNGLHFCQUFTLEdBQUcsYUFBYSxDQUFDO0FBaUc1QyxhQUFhLENBQUMsYUFBYSxDQUFDLFdBQVcsQ0FBQyxDQUFDIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMjMgQ29kZVNtaXRoIExMQ1xuICpcbiAqIFVzZSBvZiB0aGlzIHNvdXJjZSBjb2RlIGlzIGdvdmVybmVkIGJ5IGFuIE1JVC1zdHlsZVxuICogbGljZW5zZSB0aGF0IGNhbiBiZSBmb3VuZCBpbiB0aGUgTElDRU5TRSBmaWxlIG9yIGF0XG4gKiBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL01JVC5cbiAqID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG4gKi9cblxuaW1wb3J0IHsgaW1hZ2UsIFJhbmssIHNlcmlhbGl6YXRpb24sIFRlbnNvciwgdGlkeSB9IGZyb20gJ0B0ZW5zb3JmbG93L3RmanMtY29yZSc7XG5pbXBvcnQgeyBnZXRFeGFjdGx5T25lVGVuc29yLCBnZXRFeGFjdGx5T25lU2hhcGUgfSBmcm9tICcuLi8uLi91dGlscy90eXBlc191dGlscyc7XG5pbXBvcnQgeyBTaGFwZSB9IGZyb20gJy4uLy4uL2tlcmFzX2Zvcm1hdC9jb21tb24nO1xuaW1wb3J0IHsgS3dhcmdzIH0gZnJvbSAnLi4vLi4vdHlwZXMnO1xuaW1wb3J0IHsgVmFsdWVFcnJvciB9IGZyb20gJy4uLy4uL2Vycm9ycyc7XG5pbXBvcnQgeyBCYXNlUmFuZG9tTGF5ZXJBcmdzLCBCYXNlUmFuZG9tTGF5ZXIgfSBmcm9tICcuLi8uLi9lbmdpbmUvYmFzZV9yYW5kb21fbGF5ZXInO1xuaW1wb3J0IHsgcmFuZG9tVW5pZm9ybSB9IGZyb20gJ0B0ZW5zb3JmbG93L3RmanMtY29yZSc7XG5cbmV4cG9ydCBkZWNsYXJlIGludGVyZmFjZSBSYW5kb21XaWR0aEFyZ3MgZXh0ZW5kcyBCYXNlUmFuZG9tTGF5ZXJBcmdzIHtcbiAgIGZhY3RvcjogbnVtYmVyIHwgW251bWJlciwgbnVtYmVyXTtcbiAgIGludGVycG9sYXRpb24/OiBJbnRlcnBvbGF0aW9uVHlwZTsgLy8gZGVmYXVsdCA9ICdiaWxpbmVhcic7XG4gICBzZWVkPzogbnVtYmVyOyAvLyBkZWZhdWx0ID0gbnVsbDtcbiAgIGF1dG9WZWN0b3JpemU/OiBib29sZWFuO1xufVxuXG5jb25zdCBJTlRFUlBPTEFUSU9OX0tFWVMgPSBbJ2JpbGluZWFyJywgJ25lYXJlc3QnXSBhcyBjb25zdDtcbmV4cG9ydCBjb25zdCBJTlRFUlBPTEFUSU9OX01FVEhPRFMgPSBuZXcgU2V0KElOVEVSUE9MQVRJT05fS0VZUyk7XG50eXBlIEludGVycG9sYXRpb25UeXBlID0gdHlwZW9mIElOVEVSUE9MQVRJT05fS0VZU1tudW1iZXJdO1xuXG4vKipcbiAqIFByZXByb2Nlc3NpbmcgTGF5ZXIgd2l0aCByYW5kb21seSB2YXJpZXMgaW1hZ2UgZHVyaW5nIHRyYWluaW5nXG4gKlxuICogVGhpcyBsYXllciByYW5kb21seSBhZGp1c3RzIHRoZSB3aWR0aCBvZiBhIGJhdGNoIG9mIGltYWdlcyBvZiBhXG4gKiBiYXRjaCBvZiBpbWFnZXMgYnkgYSByYW5kb20gZmFjdG9yLlxuICpcbiAqIFRoZSBpbnB1dCBzaG91bGQgYmUgYSAzRCAodW5iYXRjaGVkKSBvclxuICogNEQgKGJhdGNoZWQpIHRlbnNvciBpbiB0aGUgYFwiY2hhbm5lbHNfbGFzdFwiYCBpbWFnZSBkYXRhIGZvcm1hdC4gSW5wdXQgcGl4ZWxcbiAqIHZhbHVlcyBjYW4gYmUgb2YgYW55IHJhbmdlIChlLmcuIGBbMC4sIDEuKWAgb3IgYFswLCAyNTVdYCkgYW5kIG9mIGludGVyZ2VyXG4gKiBvciBmbG9hdGluZyBwb2ludCBkdHlwZS4gQnkgZGVmYXVsdCwgdGhlIGxheWVyIHdpbGwgb3V0cHV0IGZsb2F0cy5cbiAqXG4gKiB0ZiBtZXRob2RzIGltcGxlbWVudGVkIGluIHRmanM6ICdiaWxpbmVhcicsICduZWFyZXN0JyxcbiAqIHRmIG1ldGhvZHMgdW5pbXBsZW1lbnRlZCBpbiB0ZmpzOiAnYmljdWJpYycsICdhcmVhJywgJ2xhbmN6b3MzJywgJ2xhbmN6b3M1JyxcbiAqICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAnZ2F1c3NpYW4nLCAnbWl0Y2hlbGxjdWJpYydcbiAqXG4gKi9cblxuZXhwb3J0IGNsYXNzIFJhbmRvbVdpZHRoIGV4dGVuZHMgQmFzZVJhbmRvbUxheWVyIHtcbiAgLyoqIEBub2NvbGxhcHNlICovXG4gIHN0YXRpYyBvdmVycmlkZSBjbGFzc05hbWUgPSAnUmFuZG9tV2lkdGgnO1xuICBwcml2YXRlIHJlYWRvbmx5IGZhY3RvcjogbnVtYmVyIHwgW251bWJlciwgbnVtYmVyXTtcbiAgcHJpdmF0ZSByZWFkb25seSBpbnRlcnBvbGF0aW9uPzogSW50ZXJwb2xhdGlvblR5cGU7ICAvLyBkZWZ1YWx0ID0gJ2JpbGluZWFyXG4gIHByaXZhdGUgd2lkdGhMb3dlcjogbnVtYmVyO1xuICBwcml2YXRlIHdpZHRoVXBwZXI6IG51bWJlcjtcbiAgcHJpdmF0ZSBpbWdIZWlnaHQ6IG51bWJlcjtcbiAgcHJpdmF0ZSB3aWR0aEZhY3RvcjogVGVuc29yPFJhbmsuUjE+O1xuXG4gIGNvbnN0cnVjdG9yKGFyZ3M6IFJhbmRvbVdpZHRoQXJncykge1xuICAgIHN1cGVyKGFyZ3MpO1xuICAgIGNvbnN0IHtmYWN0b3IsIGludGVycG9sYXRpb24gPSAnYmlsaW5lYXInfSA9IGFyZ3M7XG5cbiAgICB0aGlzLmZhY3RvciA9IGZhY3RvcjtcblxuICAgIGlmIChBcnJheS5pc0FycmF5KHRoaXMuZmFjdG9yKSAmJiB0aGlzLmZhY3Rvci5sZW5ndGggPT09IDIpIHtcbiAgICAgIHRoaXMud2lkdGhMb3dlciA9IHRoaXMuZmFjdG9yWzBdO1xuICAgICAgdGhpcy53aWR0aFVwcGVyID0gdGhpcy5mYWN0b3JbMV07XG4gICAgfSBlbHNlIGlmICghQXJyYXkuaXNBcnJheSh0aGlzLmZhY3RvcikgJiYgdGhpcy5mYWN0b3IgPiAwKXtcbiAgICAgIHRoaXMud2lkdGhMb3dlciA9IC10aGlzLmZhY3RvcjtcbiAgICAgIHRoaXMud2lkdGhVcHBlciA9IHRoaXMuZmFjdG9yO1xuICAgIH0gZWxzZSB7XG4gICAgICB0aHJvdyBuZXcgVmFsdWVFcnJvcihcbiAgICAgICAgYEludmFsaWQgZmFjdG9yOiAke3RoaXMuZmFjdG9yfS4gTXVzdCBiZSBwb3NpdGl2ZSBudW1iZXIgb3IgdHVwbGUgb2YgMiBudW1iZXJzYFxuICAgICAgKTtcbiAgICB9XG4gICAgaWYgKHRoaXMud2lkdGhMb3dlciA8IC0xLjAgfHwgdGhpcy53aWR0aFVwcGVyIDwgLTEuMCkge1xuICAgICAgdGhyb3cgbmV3IFZhbHVlRXJyb3IoXG4gICAgICAgIGBmYWN0b3IgbXVzdCBoYXZlIHZhbHVlcyBsYXJnZXIgdGhhbiAtMS4gR290OiAke3RoaXMuZmFjdG9yfWBcbiAgICAgICk7XG4gICAgfVxuXG4gICAgaWYgKHRoaXMud2lkdGhVcHBlciA8IHRoaXMud2lkdGhMb3dlcikge1xuICAgICAgdGhyb3cgbmV3IFZhbHVlRXJyb3IoXG4gICAgICAgIGBmYWN0b3IgY2Fubm90IGhhdmUgdXBwZXIgYm91bmQgbGVzcyB0aGFuIGxvd2VyIGJvdW5kLlxuICAgICAgICBHb3QgdXBwZXIgYm91bmQ6ICR7dGhpcy53aWR0aFVwcGVyfS5cbiAgICAgICAgR290IGxvd2VyIGJvdW5kOiAke3RoaXMud2lkdGhMb3dlcn1cbiAgICAgIGApO1xuICAgIH1cblxuICAgIGlmIChpbnRlcnBvbGF0aW9uKSB7XG4gICAgICBpZiAoSU5URVJQT0xBVElPTl9NRVRIT0RTLmhhcyhpbnRlcnBvbGF0aW9uKSkge1xuICAgICAgICB0aGlzLmludGVycG9sYXRpb24gPSBpbnRlcnBvbGF0aW9uO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgdGhyb3cgbmV3IFZhbHVlRXJyb3IoYEludmFsaWQgaW50ZXJwb2xhdGlvbiBwYXJhbWV0ZXI6ICR7XG4gICAgICAgICAgICBpbnRlcnBvbGF0aW9ufSBpcyBub3QgaW1wbGVtZW50ZWRgKTtcbiAgICAgIH1cbiAgICB9IFxuICB9XG5cbiAgb3ZlcnJpZGUgZ2V0Q29uZmlnKCk6IHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCB7XG4gICAgY29uc3QgY29uZmlnOiBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3QgPSB7XG4gICAgICAnZmFjdG9yJzogdGhpcy5mYWN0b3IsXG4gICAgICAnaW50ZXJwb2xhdGlvbic6IHRoaXMuaW50ZXJwb2xhdGlvbixcbiAgICB9O1xuXG4gICAgY29uc3QgYmFzZUNvbmZpZyA9IHN1cGVyLmdldENvbmZpZygpO1xuICAgIE9iamVjdC5hc3NpZ24oY29uZmlnLCBiYXNlQ29uZmlnKTtcbiAgICByZXR1cm4gY29uZmlnO1xuICB9XG5cbiAgb3ZlcnJpZGUgY29tcHV0ZU91dHB1dFNoYXBlKGlucHV0U2hhcGU6IFNoYXBlfFNoYXBlW10pOiBTaGFwZXxTaGFwZVtdIHtcbiAgICBpbnB1dFNoYXBlID0gZ2V0RXhhY3RseU9uZVNoYXBlKGlucHV0U2hhcGUpO1xuICAgIGNvbnN0IG51bUNoYW5uZWxzID0gaW5wdXRTaGFwZVsyXTtcbiAgICByZXR1cm4gW3RoaXMuaW1nSGVpZ2h0LCAtMSwgbnVtQ2hhbm5lbHNdO1xuICB9XG5cbiAgb3ZlcnJpZGUgY2FsbChpbnB1dHM6IFRlbnNvcjxSYW5rLlIzPnxUZW5zb3I8UmFuay5SND4sXG4gICAga3dhcmdzOiBLd2FyZ3MpOiBUZW5zb3JbXXxUZW5zb3Ige1xuXG4gICAgcmV0dXJuIHRpZHkoKCkgPT4ge1xuICAgICAgY29uc3QgaW5wdXQgPSBnZXRFeGFjdGx5T25lVGVuc29yKGlucHV0cyk7XG4gICAgICB0aGlzLmltZ0hlaWdodCA9IGlucHV0LnNoYXBlW2lucHV0LnNoYXBlLmxlbmd0aCAtIDNdO1xuICAgICAgY29uc3QgaW1nV2lkdGggPSBpbnB1dC5zaGFwZVtpbnB1dC5zaGFwZS5sZW5ndGggLSAyXTtcblxuICAgICAgdGhpcy53aWR0aEZhY3RvciA9IHJhbmRvbVVuaWZvcm0oWzFdLFxuICAgICAgICAoMS4wICsgdGhpcy53aWR0aExvd2VyKSwgKDEuMCArIHRoaXMud2lkdGhVcHBlciksXG4gICAgICAgICdmbG9hdDMyJywgdGhpcy5yYW5kb21HZW5lcmF0b3IubmV4dCgpXG4gICAgICApO1xuXG4gICAgICBsZXQgYWRqdXN0ZWRXaWR0aCA9IHRoaXMud2lkdGhGYWN0b3IuZGF0YVN5bmMoKVswXSAqIGltZ1dpZHRoO1xuICAgICAgYWRqdXN0ZWRXaWR0aCA9IE1hdGgucm91bmQoYWRqdXN0ZWRXaWR0aCk7XG5cbiAgICAgIGNvbnN0IHNpemU6W251bWJlciwgbnVtYmVyXSA9IFt0aGlzLmltZ0hlaWdodCwgYWRqdXN0ZWRXaWR0aF07XG5cbiAgICAgIHN3aXRjaCAodGhpcy5pbnRlcnBvbGF0aW9uKSB7XG4gICAgICAgIGNhc2UgJ2JpbGluZWFyJzpcbiAgICAgICAgICByZXR1cm4gaW1hZ2UucmVzaXplQmlsaW5lYXIoaW5wdXRzLCBzaXplKTtcbiAgICAgICAgY2FzZSAnbmVhcmVzdCc6XG4gICAgICAgICAgcmV0dXJuIGltYWdlLnJlc2l6ZU5lYXJlc3ROZWlnaGJvcihpbnB1dHMsIHNpemUpO1xuICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgIHRocm93IG5ldyBFcnJvcihgSW50ZXJwb2xhdGlvbiBpcyAke3RoaXMuaW50ZXJwb2xhdGlvbn1cbiAgICAgICAgICBidXQgb25seSAke1suLi5JTlRFUlBPTEFUSU9OX01FVEhPRFNdfSBhcmUgc3VwcG9ydGVkYCk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cbn1cblxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKFJhbmRvbVdpZHRoKTtcbiJdfQ==