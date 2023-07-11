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
 * This layer randomly adjusts the height of a
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
export class RandomHeight extends BaseRandomLayer {
    constructor(args) {
        super(args);
        const { factor, interpolation = 'bilinear' } = args;
        this.factor = factor;
        if (Array.isArray(this.factor) && this.factor.length === 2) {
            this.heightLower = this.factor[0];
            this.heightUpper = this.factor[1];
        }
        else if (!Array.isArray(this.factor) && this.factor > 0) {
            this.heightLower = -this.factor;
            this.heightUpper = this.factor;
        }
        else {
            throw new ValueError(`Invalid factor: ${this.factor}. Must be positive number or tuple of 2 numbers`);
        }
        if (this.heightLower < -1.0 || this.heightUpper < -1.0) {
            throw new ValueError(`factor must have values larger than -1. Got: ${this.factor}`);
        }
        if (this.heightUpper < this.heightLower) {
            throw new ValueError(`factor cannot have upper bound less than lower bound.
        Got upper bound: ${this.heightUpper}.
        Got lower bound: ${this.heightLower}
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
        return [-1, this.imgWidth, numChannels];
    }
    call(inputs, kwargs) {
        return tidy(() => {
            const input = getExactlyOneTensor(inputs);
            this.imgWidth = input.shape[input.shape.length - 2];
            const imgHeight = input.shape[input.shape.length - 3];
            this.heightFactor = randomUniform([1], (1.0 + this.heightLower), (1.0 + this.heightUpper), 'float32', this.randomGenerator.next());
            let adjustedHeight = this.heightFactor.dataSync()[0] * imgHeight;
            adjustedHeight = Math.round(adjustedHeight);
            const size = [adjustedHeight, this.imgWidth];
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
RandomHeight.className = 'RandomHeight';
serialization.registerClass(RandomHeight);
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoicmFuZG9tX2hlaWdodC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uLy4uL3RmanMtbGF5ZXJzL3NyYy9sYXllcnMvcHJlcHJvY2Vzc2luZy9yYW5kb21faGVpZ2h0LnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBOzs7Ozs7OztHQVFHO0FBRUgsT0FBTyxFQUFFLEtBQUssRUFBUSxhQUFhLEVBQVUsSUFBSSxFQUFFLE1BQU0sdUJBQXVCLENBQUM7QUFDakYsT0FBTyxFQUFFLG1CQUFtQixFQUFFLGtCQUFrQixFQUFFLE1BQU0seUJBQXlCLENBQUM7QUFHbEYsT0FBTyxFQUFFLFVBQVUsRUFBRSxNQUFNLGNBQWMsQ0FBQztBQUMxQyxPQUFPLEVBQXVCLGVBQWUsRUFBRSxNQUFNLGdDQUFnQyxDQUFDO0FBQ3RGLE9BQU8sRUFBRSxhQUFhLEVBQUUsTUFBTSx1QkFBdUIsQ0FBQztBQVN0RCxNQUFNLGtCQUFrQixHQUFHLENBQUMsVUFBVSxFQUFFLFNBQVMsQ0FBVSxDQUFDO0FBQzVELE1BQU0sQ0FBQyxNQUFNLHFCQUFxQixHQUFHLElBQUksR0FBRyxDQUFDLGtCQUFrQixDQUFDLENBQUM7QUFHakU7Ozs7Ozs7Ozs7Ozs7OztHQWVHO0FBRUgsTUFBTSxPQUFPLFlBQWEsU0FBUSxlQUFlO0lBVS9DLFlBQVksSUFBc0I7UUFDaEMsS0FBSyxDQUFDLElBQUksQ0FBQyxDQUFDO1FBQ1osTUFBTSxFQUFDLE1BQU0sRUFBRSxhQUFhLEdBQUcsVUFBVSxFQUFDLEdBQUcsSUFBSSxDQUFDO1FBRWxELElBQUksQ0FBQyxNQUFNLEdBQUcsTUFBTSxDQUFDO1FBRXJCLElBQUksS0FBSyxDQUFDLE9BQU8sQ0FBQyxJQUFJLENBQUMsTUFBTSxDQUFDLElBQUksSUFBSSxDQUFDLE1BQU0sQ0FBQyxNQUFNLEtBQUssQ0FBQyxFQUFFO1lBQzFELElBQUksQ0FBQyxXQUFXLEdBQUcsSUFBSSxDQUFDLE1BQU0sQ0FBQyxDQUFDLENBQUMs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QUM7WUFDakUsY0FBYyxHQUFHLElBQUksQ0FBQyxLQUFLLENBQUMsY0FBYyxDQUFDLENBQUM7WUFFNUMsTUFBTSxJQUFJLEdBQW9CLENBQUMsY0FBYyxFQUFFLElBQUksQ0FBQyxRQUFRLENBQUMsQ0FBQztZQUU5RCxRQUFRLElBQUksQ0FBQyxhQUFhLEVBQUU7Z0JBQzFCLEtBQUssVUFBVTtvQkFDYixPQUFPLEtBQUssQ0FBQyxjQUFjLENBQUMsTUFBTSxFQUFFLElBQUksQ0FBQyxDQUFDO2dCQUM1QyxLQUFLLFNBQVM7b0JBQ1osT0FBTyxLQUFLLENBQUMscUJBQXFCLENBQUMsTUFBTSxFQUFFLElBQUksQ0FBQyxDQUFDO2dCQUNuRDtvQkFDRSxNQUFNLElBQUksS0FBSyxDQUFDLG9CQUFvQixJQUFJLENBQUMsYUFBYTtxQkFDM0MsQ0FBQyxHQUFHLHFCQUFxQixDQUFDLGdCQUFnQixDQUFDLENBQUM7YUFDMUQ7UUFDSCxDQUFDLENBQUMsQ0FBQztJQUNMLENBQUM7O0FBL0ZELGtCQUFrQjtBQUNGLHNCQUFTLEdBQUcsY0FBYyxDQUFDO0FBaUc3QyxhQUFhLENBQUMsYUFBYSxDQUFDLFlBQVksQ0FBQyxDQUFDIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMjMgQ29kZVNtaXRoIExMQ1xuICpcbiAqIFVzZSBvZiB0aGlzIHNvdXJjZSBjb2RlIGlzIGdvdmVybmVkIGJ5IGFuIE1JVC1zdHlsZVxuICogbGljZW5zZSB0aGF0IGNhbiBiZSBmb3VuZCBpbiB0aGUgTElDRU5TRSBmaWxlIG9yIGF0XG4gKiBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL01JVC5cbiAqID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG4gKi9cblxuaW1wb3J0IHsgaW1hZ2UsIFJhbmssIHNlcmlhbGl6YXRpb24sIFRlbnNvciwgdGlkeSB9IGZyb20gJ0B0ZW5zb3JmbG93L3RmanMtY29yZSc7XG5pbXBvcnQgeyBnZXRFeGFjdGx5T25lVGVuc29yLCBnZXRFeGFjdGx5T25lU2hhcGUgfSBmcm9tICcuLi8uLi91dGlscy90eXBlc191dGlscyc7XG5pbXBvcnQgeyBTaGFwZSB9IGZyb20gJy4uLy4uL2tlcmFzX2Zvcm1hdC9jb21tb24nO1xuaW1wb3J0IHsgS3dhcmdzIH0gZnJvbSAnLi4vLi4vdHlwZXMnO1xuaW1wb3J0IHsgVmFsdWVFcnJvciB9IGZyb20gJy4uLy4uL2Vycm9ycyc7XG5pbXBvcnQgeyBCYXNlUmFuZG9tTGF5ZXJBcmdzLCBCYXNlUmFuZG9tTGF5ZXIgfSBmcm9tICcuLi8uLi9lbmdpbmUvYmFzZV9yYW5kb21fbGF5ZXInO1xuaW1wb3J0IHsgcmFuZG9tVW5pZm9ybSB9IGZyb20gJ0B0ZW5zb3JmbG93L3RmanMtY29yZSc7XG5cbmV4cG9ydCBkZWNsYXJlIGludGVyZmFjZSBSYW5kb21IZWlnaHRBcmdzIGV4dGVuZHMgQmFzZVJhbmRvbUxheWVyQXJncyB7XG4gICBmYWN0b3I6IG51bWJlciB8IFtudW1iZXIsIG51bWJlcl07XG4gICBpbnRlcnBvbGF0aW9uPzogSW50ZXJwb2xhdGlvblR5cGU7IC8vIGRlZmF1bHQgPSAnYmlsaW5lYXInO1xuICAgc2VlZD86IG51bWJlcjsgLy8gZGVmYXVsdCA9IG51bGw7XG4gICBhdXRvVmVjdG9yaXplPzogYm9vbGVhbjtcbn1cblxuY29uc3QgSU5URVJQT0xBVElPTl9LRVlTID0gWydiaWxpbmVhcicsICduZWFyZXN0J10gYXMgY29uc3Q7XG5leHBvcnQgY29uc3QgSU5URVJQT0xBVElPTl9NRVRIT0RTID0gbmV3IFNldChJTlRFUlBPTEFUSU9OX0tFWVMpO1xudHlwZSBJbnRlcnBvbGF0aW9uVHlwZSA9IHR5cGVvZiBJTlRFUlBPTEFUSU9OX0tFWVNbbnVtYmVyXTtcblxuLyoqXG4gKiBQcmVwcm9jZXNzaW5nIExheWVyIHdpdGggcmFuZG9tbHkgdmFyaWVzIGltYWdlIGR1cmluZyB0cmFpbmluZ1xuICpcbiAqIFRoaXMgbGF5ZXIgcmFuZG9tbHkgYWRqdXN0cyB0aGUgaGVpZ2h0IG9mIGFcbiAqIGJhdGNoIG9mIGltYWdlcyBieSBhIHJhbmRvbSBmYWN0b3IuXG4gKlxuICogVGhlIGlucHV0IHNob3VsZCBiZSBhIDNEICh1bmJhdGNoZWQpIG9yXG4gKiA0RCAoYmF0Y2hlZCkgdGVuc29yIGluIHRoZSBgXCJjaGFubmVsc19sYXN0XCJgIGltYWdlIGRhdGEgZm9ybWF0LiBJbnB1dCBwaXhlbFxuICogdmFsdWVzIGNhbiBiZSBvZiBhbnkgcmFuZ2UgKGUuZy4gYFswLiwgMS4pYCBvciBgWzAsIDI1NV1gKSBhbmQgb2YgaW50ZXJnZXJcbiAqIG9yIGZsb2F0aW5nIHBvaW50IGR0eXBlLiBCeSBkZWZhdWx0LCB0aGUgbGF5ZXIgd2lsbCBvdXRwdXQgZmxvYXRzLlxuICpcbiAqIHRmIG1ldGhvZHMgaW1wbGVtZW50ZWQgaW4gdGZqczogJ2JpbGluZWFyJywgJ25lYXJlc3QnLFxuICogdGYgbWV0aG9kcyB1bmltcGxlbWVudGVkIGluIHRmanM6ICdiaWN1YmljJywgJ2FyZWEnLCAnbGFuY3pvczMnLCAnbGFuY3pvczUnLFxuICogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICdnYXVzc2lhbicsICdtaXRjaGVsbGN1YmljJ1xuICpcbiAqL1xuXG5leHBvcnQgY2xhc3MgUmFuZG9tSGVpZ2h0IGV4dGVuZHMgQmFzZVJhbmRvbUxheWVyIHtcbiAgLyoqIEBub2NvbGxhcHNlICovXG4gIHN0YXRpYyBvdmVycmlkZSBjbGFzc05hbWUgPSAnUmFuZG9tSGVpZ2h0JztcbiAgcHJpdmF0ZSByZWFkb25seSBmYWN0b3I6IG51bWJlciB8IFtudW1iZXIsIG51bWJlcl07XG4gIHByaXZhdGUgcmVhZG9ubHkgaW50ZXJwb2xhdGlvbj86IEludGVycG9sYXRpb25UeXBlOyAgLy8gZGVmdWFsdCA9ICdiaWxpbmVhclxuICBwcml2YXRlIGhlaWdodExvd2VyOiBudW1iZXI7XG4gIHByaXZhdGUgaGVpZ2h0VXBwZXI6IG51bWJlcjtcbiAgcHJpdmF0ZSBpbWdXaWR0aDogbnVtYmVyO1xuICBwcml2YXRlIGhlaWdodEZhY3RvcjogVGVuc29yPFJhbmsuUjE+O1xuXG4gIGNvbnN0cnVjdG9yKGFyZ3M6IFJhbmRvbUhlaWdodEFyZ3MpIHtcbiAgICBzdXBlcihhcmdzKTtcbiAgICBjb25zdCB7ZmFjdG9yLCBpbnRlcnBvbGF0aW9uID0gJ2JpbGluZWFyJ30gPSBhcmdzO1xuXG4gICAgdGhpcy5mYWN0b3IgPSBmYWN0b3I7XG5cbiAgICBpZiAoQXJyYXkuaXNBcnJheSh0aGlzLmZhY3RvcikgJiYgdGhpcy5mYWN0b3IubGVuZ3RoID09PSAyKSB7XG4gICAgICB0aGlzLmhlaWdodExvd2VyID0gdGhpcy5mYWN0b3JbMF07XG4gICAgICB0aGlzLmhlaWdodFVwcGVyID0gdGhpcy5mYWN0b3JbMV07XG4gICAgfSBlbHNlIGlmICghQXJyYXkuaXNBcnJheSh0aGlzLmZhY3RvcikgJiYgdGhpcy5mYWN0b3IgPiAwKXtcbiAgICAgIHRoaXMuaGVpZ2h0TG93ZXIgPSAtdGhpcy5mYWN0b3I7XG4gICAgICB0aGlzLmhlaWdodFVwcGVyID0gdGhpcy5mYWN0b3I7XG4gICAgfSBlbHNlIHtcbiAgICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKFxuICAgICAgICBgSW52YWxpZCBmYWN0b3I6ICR7dGhpcy5mYWN0b3J9LiBNdXN0IGJlIHBvc2l0aXZlIG51bWJlciBvciB0dXBsZSBvZiAyIG51bWJlcnNgXG4gICAgICApO1xuICAgIH1cbiAgICBpZiAodGhpcy5oZWlnaHRMb3dlciA8IC0xLjAgfHwgdGhpcy5oZWlnaHRVcHBlciA8IC0xLjApIHtcbiAgICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKFxuICAgICAgICBgZmFjdG9yIG11c3QgaGF2ZSB2YWx1ZXMgbGFyZ2VyIHRoYW4gLTEuIEdvdDogJHt0aGlzLmZhY3Rvcn1gXG4gICAgICApO1xuICAgIH1cblxuICAgIGlmICh0aGlzLmhlaWdodFVwcGVyIDwgdGhpcy5oZWlnaHRMb3dlcikge1xuICAgICAgdGhyb3cgbmV3IFZhbHVlRXJyb3IoXG4gICAgICAgIGBmYWN0b3IgY2Fubm90IGhhdmUgdXBwZXIgYm91bmQgbGVzcyB0aGFuIGxvd2VyIGJvdW5kLlxuICAgICAgICBHb3QgdXBwZXIgYm91bmQ6ICR7dGhpcy5oZWlnaHRVcHBlcn0uXG4gICAgICAgIEdvdCBsb3dlciBib3VuZDogJHt0aGlzLmhlaWdodExvd2VyfVxuICAgICAgYCk7XG4gICAgfVxuXG4gICAgaWYgKGludGVycG9sYXRpb24pIHtcbiAgICAgIGlmIChJTlRFUlBPTEFUSU9OX01FVEhPRFMuaGFzKGludGVycG9sYXRpb24pKSB7XG4gICAgICAgIHRoaXMuaW50ZXJwb2xhdGlvbiA9IGludGVycG9sYXRpb247XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aHJvdyBuZXcgVmFsdWVFcnJvcihgSW52YWxpZCBpbnRlcnBvbGF0aW9uIHBhcmFtZXRlcjogJHtcbiAgICAgICAgICAgIGludGVycG9sYXRpb259IGlzIG5vdCBpbXBsZW1lbnRlZGApO1xuICAgICAgfVxuICAgIH0gXG4gIH1cblxuICBvdmVycmlkZSBnZXRDb25maWcoKTogc2VyaWFsaXphdGlvbi5Db25maWdEaWN0IHtcbiAgICBjb25zdCBjb25maWc6IHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCA9IHtcbiAgICAgICdmYWN0b3InOiB0aGlzLmZhY3RvcixcbiAgICAgICdpbnRlcnBvbGF0aW9uJzogdGhpcy5pbnRlcnBvbGF0aW9uLFxuICAgIH07XG5cbiAgICBjb25zdCBiYXNlQ29uZmlnID0gc3VwZXIuZ2V0Q29uZmlnKCk7XG4gICAgT2JqZWN0LmFzc2lnbihjb25maWcsIGJhc2VDb25maWcpO1xuICAgIHJldHVybiBjb25maWc7XG4gIH1cblxuICBvdmVycmlkZSBjb21wdXRlT3V0cHV0U2hhcGUoaW5wdXRTaGFwZTogU2hhcGV8U2hhcGVbXSk6IFNoYXBlfFNoYXBlW10ge1xuICAgIGlucHV0U2hhcGUgPSBnZXRFeGFjdGx5T25lU2hhcGUoaW5wdXRTaGFwZSk7XG4gICAgY29uc3QgbnVtQ2hhbm5lbHMgPSBpbnB1dFNoYXBlWzJdO1xuICAgIHJldHVybiBbLTEsIHRoaXMuaW1nV2lkdGgsIG51bUNoYW5uZWxzXTtcbiAgfVxuXG4gIG92ZXJyaWRlIGNhbGwoaW5wdXRzOiBUZW5zb3I8UmFuay5SMz58VGVuc29yPFJhbmsuUjQ+LFxuICAgIGt3YXJnczogS3dhcmdzKTogVGVuc29yW118VGVuc29yIHtcblxuICAgIHJldHVybiB0aWR5KCgpID0+IHtcbiAgICAgIGNvbnN0IGlucHV0ID0gZ2V0RXhhY3RseU9uZVRlbnNvcihpbnB1dHMpO1xuICAgICAgdGhpcy5pbWdXaWR0aCA9IGlucHV0LnNoYXBlW2lucHV0LnNoYXBlLmxlbmd0aCAtIDJdO1xuICAgICAgY29uc3QgaW1nSGVpZ2h0ID0gaW5wdXQuc2hhcGVbaW5wdXQuc2hhcGUubGVuZ3RoIC0gM107XG5cbiAgICAgIHRoaXMuaGVpZ2h0RmFjdG9yID0gcmFuZG9tVW5pZm9ybShbMV0sXG4gICAgICAgICgxLjAgKyB0aGlzLmhlaWdodExvd2VyKSwgKDEuMCArIHRoaXMuaGVpZ2h0VXBwZXIpLFxuICAgICAgICAnZmxvYXQzMicsIHRoaXMucmFuZG9tR2VuZXJhdG9yLm5leHQoKVxuICAgICAgKTtcblxuICAgICAgbGV0IGFkanVzdGVkSGVpZ2h0ID0gdGhpcy5oZWlnaHRGYWN0b3IuZGF0YVN5bmMoKVswXSAqIGltZ0hlaWdodDtcbiAgICAgIGFkanVzdGVkSGVpZ2h0ID0gTWF0aC5yb3VuZChhZGp1c3RlZEhlaWdodCk7XG5cbiAgICAgIGNvbnN0IHNpemU6W251bWJlciwgbnVtYmVyXSA9IFthZGp1c3RlZEhlaWdodCwgdGhpcy5pbWdXaWR0aF07XG5cbiAgICAgIHN3aXRjaCAodGhpcy5pbnRlcnBvbGF0aW9uKSB7XG4gICAgICAgIGNhc2UgJ2JpbGluZWFyJzpcbiAgICAgICAgICByZXR1cm4gaW1hZ2UucmVzaXplQmlsaW5lYXIoaW5wdXRzLCBzaXplKTtcbiAgICAgICAgY2FzZSAnbmVhcmVzdCc6XG4gICAgICAgICAgcmV0dXJuIGltYWdlLnJlc2l6ZU5lYXJlc3ROZWlnaGJvcihpbnB1dHMsIHNpemUpO1xuICAgICAgICBkZWZhdWx0OlxuICAgICAgICAgIHRocm93IG5ldyBFcnJvcihgSW50ZXJwb2xhdGlvbiBpcyAke3RoaXMuaW50ZXJwb2xhdGlvbn1cbiAgICAgICAgICBidXQgb25seSAke1suLi5JTlRFUlBPTEFUSU9OX01FVEhPRFNdfSBhcmUgc3VwcG9ydGVkYCk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cbn1cblxuc2VyaWFsaXphdGlvbi5yZWdpc3RlckNsYXNzKFJhbmRvbUhlaWdodCk7XG4iXX0=