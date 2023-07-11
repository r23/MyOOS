/**
 * @license
 * Copyright 2022 CodeSmith LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
import { serialization, unstack, stack, tensor, tidy, range, image } from '@tensorflow/tfjs-core';
import { getExactlyOneShape, getExactlyOneTensor } from '../../utils/types_utils';
import { Layer } from '../../engine/topology';
import * as K from '../../backend/tfjs_backend';
const { resizeBilinear, cropAndResize } = image;
export class CenterCrop extends Layer {
    constructor(args) {
        super(args);
        this.height = args.height;
        this.width = args.width;
    }
    centerCrop(inputs, hBuffer, wBuffer, height, width, inputHeight, inputWidth, dtype) {
        return tidy(() => {
            let input;
            let isRank3 = false;
            const top = hBuffer / inputHeight;
            const left = wBuffer / inputWidth;
            const bottom = ((height) + hBuffer) / inputHeight;
            const right = ((width) + wBuffer) / inputWidth;
            const bound = [top, left, bottom, right];
            const boxesArr = [];
            if (inputs.rank === 3) {
                isRank3 = true;
                input = stack([inputs]);
            }
            else {
                input = inputs;
            }
            for (let i = 0; i < input.shape[0]; i++) {
                boxesArr.push(bound);
            }
            const boxes = tensor(boxesArr, [boxesArr.length, 4]);
            const boxInd = range(0, boxesArr.length, 1, 'int32');
            const cropSize = [height, width];
            const cropped = cropAndResize(input, boxes, boxInd, cropSize, 'nearest');
            if (isRank3) {
                return K.cast(getExactlyOneTensor(unstack(cropped)), dtype);
            }
            return K.cast(cropped, dtype);
        });
    }
    upsize(inputs, height, width, dtype) {
        return tidy(() => {
            const outputs = resizeBilinear(inputs, [height, width]);
            return K.cast(outputs, dtype);
        });
    }
    call(inputs, kwargs) {
        return tidy(() => {
            const rankedInputs = getExactlyOneTensor(inputs);
            const dtype = rankedInputs.dtype;
            const inputShape = rankedInputs.shape;
            const inputHeight = inputShape[inputShape.length - 3];
            const inputWidth = inputShape[inputShape.length - 2];
            let hBuffer = 0;
            if (inputHeight !== this.height) {
                hBuffer = Math.floor((inputHeight - this.height) / 2);
            }
            let wBuffer = 0;
            if (inputWidth !== this.width) {
                wBuffer = Math.floor((inputWidth - this.width) / 2);
                if (wBuffer === 0) {
                    wBuffer = 1;
                }
            }
            if (hBuffer >= 0 && wBuffer >= 0) {
                return this.centerCrop(rankedInputs, hBuffer, wBuffer, this.height, this.width, inputHeight, inputWidth, dtype);
            }
            else {
                return this.upsize(inputs, this.height, this.width, dtype);
            }
        });
    }
    getConfig() {
        const config = {
            'height': this.height,
            'width': this.width
        };
        const baseConfig = super.getConfig();
        Object.assign(config, baseConfig);
        return config;
    }
    computeOutputShape(inputShape) {
        inputShape = getExactlyOneShape(inputShape);
        const hAxis = inputShape.length - 3;
        const wAxis = inputShape.length - 2;
        inputShape[hAxis] = this.height;
        inputShape[wAxis] = this.width;
        return inputShape;
    }
}
/** @nocollapse */
CenterCrop.className = 'CenterCrop';
serialization.registerClass(CenterCrop);
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiY2VudGVyX2Nyb3AuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlcyI6WyIuLi8uLi8uLi8uLi8uLi8uLi8uLi90ZmpzLWxheWVycy9zcmMvbGF5ZXJzL3ByZXByb2Nlc3NpbmcvY2VudGVyX2Nyb3AudHMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7Ozs7Ozs7O0dBUUc7QUFFSCxPQUFPLEVBQUMsYUFBYSxFQUFVLE9BQU8sRUFBQyxLQUFLLEVBQUMsTUFBTSxFQUErQyxJQUFJLEVBQUUsS0FBSyxFQUFFLEtBQUssRUFBQyxNQUFNLHVCQUF1QixDQUFDO0FBQ25KLE9BQU8sRUFBQyxrQkFBa0IsRUFBRSxtQkFBbUIsRUFBQyxNQUFNLHlCQUF5QixDQUFDO0FBQ2hGLE9BQU8sRUFBWSxLQUFLLEVBQUMsTUFBTSx1QkFBdUIsQ0FBQztBQUd2RCxPQUFPLEtBQUssQ0FBQyxNQUFNLDRCQUE0QixDQUFDO0FBRWhELE1BQU0sRUFBQyxjQUFjLEVBQUUsYUFBYSxFQUFDLEdBQUcsS0FBSyxDQUFDO0FBTzlDLE1BQU0sT0FBTyxVQUFXLFNBQVEsS0FBSztJQUtuQyxZQUFZLElBQW9CO1FBQzlCLEtBQUssQ0FBQyxJQUFJLENBQUMsQ0FBQztRQUNaLElBQUksQ0FBQyxNQUFNLEdBQUcsSUFBSSxDQUFDLE1BQU0sQ0FBQztRQUMxQixJQUFJLENBQUMsS0FBSyxHQUFHLElBQUksQ0FBQyxLQUFLLENBQUM7SUFDMUIsQ0FBQztJQUVELFVBQVUsQ0FBQyxNQUEyQixFQUFFLE9BQWUsRUFBRSxPQUFlLEVBQzlELE1BQWMsRUFBRSxLQUFhLEVBQUUsV0FBbUIsRUFDbEQsVUFBa0IsRUFBRSxLQUFlO1FBRTNDLE9BQU8sSUFBSSxDQUFDLEdBQUcsRUFBRTtZQUNmLElBQUksS0FBZSxDQUFDO1lBQ3BCLElBQUksT0FBTyxHQUFRLEtBQUssQ0FBQztZQUN6QixNQUFNLEdBQUcsR0FBUSxPQUFPLEdBQUcsV0FBVyxDQUFDO1lBQ3ZDLE1BQU0sSUFBSSxHQUFPLE9BQU8sR0FB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QUFVLENBQUMsTUFBTSxHQUFHLENBQUMsQ0FBQztRQUNwQyxNQUFNLEtBQUssR0FBRyxVQUFVLENBQUMsTUFBTSxHQUFHLENBQUMsQ0FBQztRQUNwQyxVQUFVLENBQUMsS0FBSyxDQUFDLEdBQUcsSUFBSSxDQUFDLE1BQU0sQ0FBQztRQUNoQyxVQUFVLENBQUMsS0FBSyxDQUFDLEdBQUcsSUFBSSxDQUFDLEtBQUssQ0FBQztRQUMvQixPQUFPLFVBQVUsQ0FBQztJQUNwQixDQUFDOztBQWhIRCxrQkFBa0I7QUFDWCxvQkFBUyxHQUFHLFlBQVksQ0FBQztBQWtIbEMsYUFBYSxDQUFDLGFBQWEsQ0FBQyxVQUFVLENBQUMsQ0FBQyIsInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQGxpY2Vuc2VcbiAqIENvcHlyaWdodCAyMDIyIENvZGVTbWl0aCBMTENcbiAqXG4gKiBVc2Ugb2YgdGhpcyBzb3VyY2UgY29kZSBpcyBnb3Zlcm5lZCBieSBhbiBNSVQtc3R5bGVcbiAqIGxpY2Vuc2UgdGhhdCBjYW4gYmUgZm91bmQgaW4gdGhlIExJQ0VOU0UgZmlsZSBvciBhdFxuICogaHR0cHM6Ly9vcGVuc291cmNlLm9yZy9saWNlbnNlcy9NSVQuXG4gKiA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PVxuICovXG5cbmltcG9ydCB7c2VyaWFsaXphdGlvbixEYXRhVHlwZSx1bnN0YWNrLHN0YWNrLHRlbnNvcixUZW5zb3IsVGVuc29yMUQsVGVuc29yMkQsIFRlbnNvcjNELCBUZW5zb3I0RCwgdGlkeSwgcmFuZ2UsIGltYWdlfSBmcm9tICdAdGVuc29yZmxvdy90ZmpzLWNvcmUnO1xuaW1wb3J0IHtnZXRFeGFjdGx5T25lU2hhcGUsIGdldEV4YWN0bHlPbmVUZW5zb3J9IGZyb20gJy4uLy4uL3V0aWxzL3R5cGVzX3V0aWxzJztcbmltcG9ydCB7TGF5ZXJBcmdzLCBMYXllcn0gZnJvbSAnLi4vLi4vZW5naW5lL3RvcG9sb2d5JztcbmltcG9ydCB7S3dhcmdzfSBmcm9tICcuLi8uLi90eXBlcyc7XG5pbXBvcnQge1NoYXBlfSBmcm9tICcuLi8uLi9rZXJhc19mb3JtYXQvY29tbW9uJztcbmltcG9ydCAqIGFzIEsgZnJvbSAnLi4vLi4vYmFja2VuZC90ZmpzX2JhY2tlbmQnO1xuXG5jb25zdCB7cmVzaXplQmlsaW5lYXIsIGNyb3BBbmRSZXNpemV9ID0gaW1hZ2U7XG5cbmV4cG9ydCBkZWNsYXJlIGludGVyZmFjZSBDZW50ZXJDcm9wQXJncyBleHRlbmRzIExheWVyQXJnc3tcbiAgaGVpZ2h0OiBudW1iZXI7XG4gIHdpZHRoOiBudW1iZXI7XG59XG5cbmV4cG9ydCBjbGFzcyBDZW50ZXJDcm9wIGV4dGVuZHMgTGF5ZXIge1xuICAvKiogQG5vY29sbGFwc2UgKi9cbiAgc3RhdGljIGNsYXNzTmFtZSA9ICdDZW50ZXJDcm9wJztcbiAgcHJpdmF0ZSByZWFkb25seSBoZWlnaHQ6IG51bWJlcjtcbiAgcHJpdmF0ZSByZWFkb25seSB3aWR0aDogbnVtYmVyO1xuICBjb25zdHJ1Y3RvcihhcmdzOiBDZW50ZXJDcm9wQXJncykge1xuICAgIHN1cGVyKGFyZ3MpO1xuICAgIHRoaXMuaGVpZ2h0ID0gYXJncy5oZWlnaHQ7XG4gICAgdGhpcy53aWR0aCA9IGFyZ3Mud2lkdGg7XG4gIH1cblxuICBjZW50ZXJDcm9wKGlucHV0czogVGVuc29yM0QgfCBUZW5zb3I0RCwgaEJ1ZmZlcjogbnVtYmVyLCB3QnVmZmVyOiBudW1iZXIsXG4gICAgICAgICAgICBoZWlnaHQ6IG51bWJlciwgd2lkdGg6IG51bWJlciwgaW5wdXRIZWlnaHQ6IG51bWJlcixcbiAgICAgICAgICAgIGlucHV0V2lkdGg6IG51bWJlciwgZHR5cGU6IERhdGFUeXBlKTogVGVuc29yIHwgVGVuc29yW10ge1xuXG4gICAgcmV0dXJuIHRpZHkoKCkgPT4ge1xuICAgICAgbGV0IGlucHV0OiBUZW5zb3I0RDtcbiAgICAgIGxldCBpc1JhbmszICAgICAgPSBmYWxzZTtcbiAgICAgIGNvbnN0IHRvcCAgICAgID0gaEJ1ZmZlciAvIGlucHV0SGVpZ2h0O1xuICAgICAgY29uc3QgbGVmdCAgICAgPSB3QnVmZmVyIC8gaW5wdXRXaWR0aDtcbiAgICAgIGNvbnN0IGJvdHRvbSAgID0gKChoZWlnaHQpICsgaEJ1ZmZlcikgLyBpbnB1dEhlaWdodDtcbiAgICAgIGNvbnN0IHJpZ2h0ICAgID0gKCh3aWR0aCkgKyB3QnVmZmVyKSAvIGlucHV0V2lkdGg7XG4gICAgICBjb25zdCBib3VuZCAgICA9IFt0b3AsIGxlZnQsIGJvdHRvbSwgcmlnaHRdO1xuICAgICAgY29uc3QgYm94ZXNBcnIgPSBbXTtcblxuICAgICAgaWYoaW5wdXRzLnJhbmsgPT09IDMpIHtcbiAgICAgICAgaXNSYW5rMyAgPSB0cnVlO1xuICAgICAgICBpbnB1dCAgPSBzdGFjayhbaW5wdXRzXSkgYXMgVGVuc29yNEQ7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBpbnB1dCA9IGlucHV0cyBhcyBUZW5zb3I0RDtcbiAgICAgIH1cblxuICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCBpbnB1dC5zaGFwZVswXTsgaSsrKSB7XG4gICAgICAgIGJveGVzQXJyLnB1c2goYm91bmQpO1xuICAgICAgfVxuXG4gICAgICBjb25zdCBib3hlczogVGVuc29yMkQgID0gdGVuc29yKGJveGVzQXJyLCBbYm94ZXNBcnIubGVuZ3RoLCA0XSk7XG4gICAgICBjb25zdCBib3hJbmQ6IFRlbnNvcjFEID0gcmFuZ2UoMCwgYm94ZXNBcnIubGVuZ3RoLCAxLCAnaW50MzInKTtcblxuICAgICAgY29uc3QgY3JvcFNpemU6IFtudW1iZXIsIG51bWJlcl0gPSBbaGVpZ2h0LCB3aWR0aF07XG4gICAgICBjb25zdCBjcm9wcGVkID0gY3JvcEFuZFJlc2l6ZShpbnB1dCwgYm94ZXMsIGJveEluZCwgY3JvcFNpemUsICduZWFyZXN0Jyk7XG5cbiAgICAgIGlmKGlzUmFuazMpIHtcbiAgICAgICAgcmV0dXJuIEsuY2FzdChnZXRFeGFjdGx5T25lVGVuc29yKHVuc3RhY2soY3JvcHBlZCkpLCBkdHlwZSk7XG4gICAgICB9XG4gICAgICByZXR1cm4gSy5jYXN0KGNyb3BwZWQsIGR0eXBlKTtcbiAgIH0pO1xuXG4gIH1cblxuICB1cHNpemUoaW5wdXRzIDogVGVuc29yM0QgfCBUZW5zb3I0RCwgaGVpZ2h0OiBudW1iZXIsXG4gICAgICAgICB3aWR0aDogbnVtYmVyLCBkdHlwZTogRGF0YVR5cGUpOiBUZW5zb3IgfCBUZW5zb3JbXSB7XG5cbiAgICByZXR1cm4gdGlkeSgoKSA9PiB7XG4gICAgICBjb25zdCBvdXRwdXRzID0gcmVzaXplQmlsaW5lYXIoaW5wdXRzLCBbaGVpZ2h0LCB3aWR0aF0pO1xuICAgICAgcmV0dXJuIEsuY2FzdChvdXRwdXRzLCBkdHlwZSk7XG4gIH0pO1xuXG59XG5cbiAgb3ZlcnJpZGUgY2FsbChpbnB1dHM6IFRlbnNvcjNEIHwgVGVuc29yNEQgLCBrd2FyZ3M6IEt3YXJncyk6XG4gICAgICBUZW5zb3JbXSB8IFRlbnNvciB7XG4gICAgcmV0dXJuIHRpZHkoKCkgPT4ge1xuICAgICAgY29uc3QgcmFua2VkSW5wdXRzID0gZ2V0RXhhY3RseU9uZVRlbnNvcihpbnB1dHMpIGFzIFRlbnNvcjNEIHwgVGVuc29yNEQ7XG4gICAgICBjb25zdCBkdHlwZSAgICAgICA9IHJhbmtlZElucHV0cy5kdHlwZTtcbiAgICAgIGNvbnN0IGlucHV0U2hhcGUgID0gcmFua2VkSW5wdXRzLnNoYXBlO1xuICAgICAgY29uc3QgaW5wdXRIZWlnaHQgPSBpbnB1dFNoYXBlW2lucHV0U2hhcGUubGVuZ3RoIC0gM107XG4gICAgICBjb25zdCBpbnB1dFdpZHRoICA9ICBpbnB1dFNoYXBlW2lucHV0U2hhcGUubGVuZ3RoIC0gMl07XG5cbiAgICAgIGxldCBoQnVmZmVyID0gMDtcbiAgICAgIGlmIChpbnB1dEhlaWdodCAhPT0gdGhpcy5oZWlnaHQpIHtcbiAgICAgICAgaEJ1ZmZlciA9ICBNYXRoLmZsb29yKChpbnB1dEhlaWdodCAtIHRoaXMuaGVpZ2h0KSAvIDIpO1xuICAgICAgfVxuXG4gICAgICBsZXQgd0J1ZmZlciA9IDA7XG4gICAgICBpZiAoaW5wdXRXaWR0aCAhPT0gdGhpcy53aWR0aCkge1xuICAgICAgICB3QnVmZmVyID0gTWF0aC5mbG9vcigoaW5wdXRXaWR0aCAtIHRoaXMud2lkdGgpIC8gMik7XG5cbiAgICAgICAgaWYgKHdCdWZmZXIgPT09IDApIHtcbiAgICAgICAgICB3QnVmZmVyID0gMTtcbiAgICAgICAgfVxuICAgICAgfVxuXG4gICAgICBpZihoQnVmZmVyID49IDAgJiYgd0J1ZmZlciA+PSAwKSB7XG4gICAgICAgIHJldHVybiB0aGlzLmNlbnRlckNyb3AocmFua2VkSW5wdXRzLCBoQnVmZmVyLCB3QnVmZmVyLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5oZWlnaHQsIHRoaXMud2lkdGgsIGlucHV0SGVpZ2h0LFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaW5wdXRXaWR0aCwgZHR5cGUpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgcmV0dXJuIHRoaXMudXBzaXplKGlucHV0cywgdGhpcy5oZWlnaHQsIHRoaXMud2lkdGgsIGR0eXBlKTtcbiAgICAgIH1cbiAgIH0pO1xuXG4gIH1cblxuICBvdmVycmlkZSBnZXRDb25maWcoKTogc2VyaWFsaXphdGlvbi5Db25maWdEaWN0e1xuXG4gICAgY29uc3QgY29uZmlnOiBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3QgPSB7XG4gICAgICAnaGVpZ2h0JyA6IHRoaXMuaGVpZ2h0LFxuICAgICAgJ3dpZHRoJyA6IHRoaXMud2lkdGhcbiAgICB9O1xuXG4gICAgY29uc3QgYmFzZUNvbmZpZyA9IHN1cGVyLmdldENvbmZpZygpO1xuICAgIE9iamVjdC5hc3NpZ24oY29uZmlnLCBiYXNlQ29uZmlnKTtcbiAgICByZXR1cm4gY29uZmlnO1xuICB9XG5cbiAgb3ZlcnJpZGUgY29tcHV0ZU91dHB1dFNoYXBlKGlucHV0U2hhcGU6IFNoYXBlIHwgU2hhcGVbXSk6IFNoYXBlIHwgU2hhcGVbXSB7XG4gICAgaW5wdXRTaGFwZSA9IGdldEV4YWN0bHlPbmVTaGFwZShpbnB1dFNoYXBlKTtcbiAgICBjb25zdCBoQXhpcyA9IGlucHV0U2hhcGUubGVuZ3RoIC0gMztcbiAgICBjb25zdCB3QXhpcyA9IGlucHV0U2hhcGUubGVuZ3RoIC0gMjtcbiAgICBpbnB1dFNoYXBlW2hBeGlzXSA9IHRoaXMuaGVpZ2h0O1xuICAgIGlucHV0U2hhcGVbd0F4aXNdID0gdGhpcy53aWR0aDtcbiAgICByZXR1cm4gaW5wdXRTaGFwZTtcbiAgfVxufVxuXG5zZXJpYWxpemF0aW9uLnJlZ2lzdGVyQ2xhc3MoQ2VudGVyQ3JvcCk7XG4iXX0=