/**
 * @license
 * Copyright 2022 Google LLC. All Rights Reserved.
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
import { backend_util, TensorScatterUpdate } from '@tensorflow/tfjs-core';
import { ScatterProgram } from '../scatter_gpu';
import { reshape } from './Reshape';
export function tensorScatterUpdate(args) {
    const { inputs, backend, attrs } = args;
    const { tensor, indices, updates } = inputs;
    const {} = attrs;
    const { sliceRank, numUpdates, sliceSize, strides, outputSize } = backend_util.calculateShapes(updates, indices, tensor.shape);
    const flattenShape = [outputSize / sliceSize, sliceSize];
    if (outputSize === 0) {
        return backend.makeTensorInfo(tensor.shape, indices.dtype);
    }
    const flattenIndices = reshape({ inputs: { x: indices }, backend, attrs: { shape: [numUpdates, sliceRank] } });
    const flattenX = reshape({ inputs: { x: updates }, backend, attrs: { shape: [numUpdates, sliceSize] } });
    const flattenTensor = reshape({ inputs: { x: tensor }, backend, attrs: { shape: flattenShape } });
    const program = new ScatterProgram(numUpdates, sliceRank, flattenIndices.shape.length, flattenX.shape.length, strides, flattenShape, false, true);
    const res = backend.runWebGLProgram(program, [flattenX, flattenIndices, flattenTensor], flattenTensor.dtype);
    const reshaped = reshape({ inputs: { x: res }, backend, attrs: { shape: tensor.shape } });
    backend.disposeIntermediateTensorInfo(flattenIndices);
    backend.disposeIntermediateTensorInfo(flattenX);
    backend.disposeIntermediateTensorInfo(flattenTensor);
    backend.disposeIntermediateTensorInfo(res);
    return reshaped;
}
export const tensorScatterUpdateConfig = {
    kernelName: TensorScatterUpdate,
    backendName: 'webgl',
    kernelFunc: tensorScatterUpdate
};
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiVGVuc29yU2NhdHRlclVwZGF0ZS5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uL3RmanMtYmFja2VuZC13ZWJnbC9zcmMva2VybmVscy9UZW5zb3JTY2F0dGVyVXBkYXRlLnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBOzs7Ozs7Ozs7Ozs7Ozs7R0FlRztBQUVILE9BQU8sRUFBQyxZQUFZLEVBQXdDLG1CQUFtQixFQUFzRCxNQUFNLHVCQUF1QixDQUFDO0FBR25LLE9BQU8sRUFBQyxjQUFjLEVBQUMsTUFBTSxnQkFBZ0IsQ0FBQztBQUU5QyxPQUFPLEVBQUMsT0FBTyxFQUFDLE1BQU0sV0FBVyxDQUFDO0FBRWxDLE1BQU0sVUFBVSxtQkFBbUIsQ0FBQyxJQUluQztJQUNDLE1BQU0sRUFBQyxNQUFNLEVBQUUsT0FBTyxFQUFFLEtBQUssRUFBQyxHQUFHLElBQUksQ0FBQztJQUN0QyxNQUFNLEVBQUMsTUFBTSxFQUFFLE9BQU8sRUFBRSxPQUFPLEVBQUMsR0FBRyxNQUFNLENBQUM7SUFDMUMsTUFBTSxFQUFFLEdBQUcsS0FBSyxDQUFDO0lBRWpCLE1BQU0sRUFBQyxTQUFTLEVBQUUsVUFBVSxFQUFFLFNBQVMsRUFBRSxPQUFPLEVBQUUsVUFBVSxFQUFDLEdBQ3pELFlBQVksQ0FBQyxlQUFlLENBQUMsT0FBTyxFQUFFLE9BQU8sRUFBRSxNQUFNLENBQUMsS0FBSyxDQUFDLENBQUM7SUFFakUsTUFBTSxZQUFZLEdBQUcsQ0FBQyxVQUFVLEdBQUcsU0FBUyxFQUFFLFNBQVMsQ0FBQyxDQUFDO0lBRXpELElBQUksVUFBVSxLQUFLLENBQUMsRUFBRTtRQUNwQixPQUFPLE9BQU8sQ0FBQyxjQUFjLENBQUMsTUFBTSxDQUFDLEtBQUssRUFBRSxPQUFPLENBQUMsS0FBSyxDQUFDLENBQUM7S0FDNUQ7SUFFRCxNQUFNLGNBQWMsR0FBRyxPQUFPLENBQzFCLEVBQUMsTUFBTSxFQUFFLEVBQUMsQ0FBQyxFQUFFLE9BQU8sRUFBQyxFQUFFLE9BQU8sRUFBRSxLQUFLLEVBQUUsRUFBQyxLQUFLLEVBQUUsQ0FBQyxVQUFVLEVBQUUsU0FBUyxDQUFDLEVBQUMsRUFBQyxDQUFDLENBQUM7SUFDOUUsTUFBTSxRQUFRLEdBQUcsT0FBTyxDQUNwQixFQUFDLE1BQU0sRUFBRSxFQUFDLENBQUMsRUFBRSxPQUFPLEVBQUMsRUFBRSxPQUFPLEVBQUUsS0FBSyxFQUFFLEVBQUMsS0FBSyxFQUFFLENBQUMsVUFBVSxFQUFFLFNBQVMsQ0FBQyxFQUFDLEVBQUMsQ0FBQyxDQUFDO0lBQzlFLE1BQU0sYUFBYSxHQUNmLE9BQU8sQ0FBQyxFQUFDLE1BQU0sRUFBRSxFQUFDLENBQUMsRUFBRSxNQUFNLEVBQUMsRUFBRSxPQUFPLEVBQUUsS0FBSyxFQUFFLEVBQUMsS0FBSyxFQUFFLFlBQVksRUFBQyxFQUFDLENBQUMsQ0FBQztJQUMxRSxNQUFNLE9BQU8sR0FBRyxJQUFJLGNBQWMsQ0FDOUIsVUFBVSxFQUFFLFNBQVMsRUFBRSxjQUFjLENBQUMsS0FBSyxDQUFDLE1BQU0sRUFBRSxRQUFRLENBQUMsS0FBSyxDQUFDLE1BQU0sRUFDekUsT0FBTyxFQUFFLFlBQVksRUFBRSxLQUFLLEVBQUUsSUFBSSxDQUFDLENBQUM7SUFDeEMsTUFBTSxHQUFHLEdBQUcsT0FBTyxDQUFDLGVBQWUsQ0FDL0IsT0FBTyxFQUFFLENBQUMsUUFBUSxFQUFFLGNBQWMsRUFBRSxhQUFhLENBQUMsRUFBRSxhQUFhLENBQUMsS0FBSyxDQUFDLENBQUM7SUFFN0UsTUFBTSxRQUFRLEdBQ1YsT0FBTyxDQUFDLEVBQUMsTUFBTSxFQUFFLEVBQUMsQ0FBQyxFQUFFLEdBQUcsRUFBQyxFQUFFLE9BQU8sRUFBRSxLQUFLLEVBQUUsRUFBQyxLQUFLLEVBQUUsTUFBTSxDQUFDLEtBQUssRUFBQyxFQUFDLENBQUMsQ0FBQztJQUV2RSxPQUFPLENBQUMsNkJBQTZCLENBQUMsY0FBYyxDQUFDLENBQUM7SUFDdEQsT0FBTyxDQUFDLDZCQUE2QixDQUFDLFFBQVEsQ0FBQyxDQUFDO0lBQ2hELE9BQU8sQ0FBQyw2QkFBNkIsQ0FBQyxhQUFhLENBQUMsQ0FBQztJQUNyRCxPQUFPLENBQUMsNkJBQTZCLENBQUMsR0FBRyxDQUFDLENBQUM7SUFFM0MsT0FBTyxRQUFRLENBQUM7QUFDbEIsQ0FBQztBQUVELE1BQU0sQ0FBQyxNQUFNLHlCQUF5QixHQUFpQjtJQUNyRCxVQUFVLEVBQUUsbUJBQW1CO0lBQy9CLFdBQVcsRUFBRSxPQUFPO0lBQ3BCLFVBQVUsRUFBRSxtQkFBNEM7Q0FDekQsQ0FBQyIsInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQGxpY2Vuc2VcbiAqIENvcHlyaWdodCAyMDIyIEdvb2dsZSBMTEMuIEFsbCBSaWdodHMgUmVzZXJ2ZWQuXG4gKiBMaWNlbnNlZCB1bmRlciB0aGUgQXBhY2hlIExpY2Vuc2UsIFZlcnNpb24gMi4wICh0aGUgXCJMaWNlbnNlXCIpO1xuICogeW91IG1heSBub3QgdXNlIHRoaXMgZmlsZSBleGNlcHQgaW4gY29tcGxpYW5jZSB3aXRoIHRoZSBMaWNlbnNlLlxuICogWW91IG1heSBvYnRhaW4gYSBjb3B5IG9mIHRoZSBMaWNlbnNlIGF0XG4gKlxuICogaHR0cDovL3d3dy5hcGFjaGUub3JnL2xpY2Vuc2VzL0xJQ0VOU0UtMi4wXG4gKlxuICogVW5sZXNzIHJlcXVpcmVkIGJ5IGFwcGxpY2FibGUgbGF3IG9yIGFncmVlZCB0byBpbiB3cml0aW5nLCBzb2Z0d2FyZVxuICogZGlzdHJpYnV0ZWQgdW5kZXIgdGhlIExpY2Vuc2UgaXMgZGlzdHJpYnV0ZWQgb24gYW4gXCJBUyBJU1wiIEJBU0lTLFxuICogV0lUSE9VVCBXQVJSQU5USUVTIE9SIENPTkRJVElPTlMgT0YgQU5ZIEtJTkQsIGVpdGhlciBleHByZXNzIG9yIGltcGxpZWQuXG4gKiBTZWUgdGhlIExpY2Vuc2UgZm9yIHRoZSBzcGVjaWZpYyBsYW5ndWFnZSBnb3Zlcm5pbmcgcGVybWlzc2lvbnMgYW5kXG4gKiBsaW1pdGF0aW9ucyB1bmRlciB0aGUgTGljZW5zZS5cbiAqID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG4gKi9cblxuaW1wb3J0IHtiYWNrZW5kX3V0aWwsIEtlcm5lbENvbmZpZywgS2VybmVsRnVuYywgVGVuc29ySW5mbywgVGVuc29yU2NhdHRlclVwZGF0ZSwgVGVuc29yU2NhdHRlclVwZGF0ZUF0dHJzLCBUZW5zb3JTY2F0dGVyVXBkYXRlSW5wdXRzfSBmcm9tICdAdGVuc29yZmxvdy90ZmpzLWNvcmUnO1xuXG5pbXBvcnQge01hdGhCYWNrZW5kV2ViR0x9IGZyb20gJy4uL2JhY2tlbmRfd2ViZ2wnO1xuaW1wb3J0IHtTY2F0dGVyUHJvZ3JhbX0gZnJvbSAnLi4vc2NhdHRlcl9ncHUnO1xuXG5pbXBvcnQge3Jlc2hhcGV9IGZyb20gJy4vUmVzaGFwZSc7XG5cbmV4cG9ydCBmdW5jdGlvbiB0ZW5zb3JTY2F0dGVyVXBkYXRlKGFyZ3M6IHtcbiAgaW5wdXRzOiBUZW5zb3JTY2F0dGVyVXBkYXRlSW5wdXRzLFxuICBiYWNrZW5kOiBNYXRoQmFja2VuZFdlYkdMLFxuICBhdHRyczogVGVuc29yU2NhdHRlclVwZGF0ZUF0dHJzXG59KTogVGVuc29ySW5mbyB7XG4gIGNvbnN0IHtpbnB1dHMsIGJhY2tlbmQsIGF0dHJzfSA9IGFyZ3M7XG4gIGNvbnN0IHt0ZW5zb3IsIGluZGljZXMsIHVwZGF0ZXN9ID0gaW5wdXRzO1xuICBjb25zdCB7fSA9IGF0dHJzO1xuXG4gIGNvbnN0IHtzbGljZVJhbmssIG51bVVwZGF0ZXMsIHNsaWNlU2l6ZSwgc3RyaWRlcywgb3V0cHV0U2l6ZX0gPVxuICAgICAgYmFja2VuZF91dGlsLmNhbGN1bGF0ZVNoYXBlcyh1cGRhdGVzLCBpbmRpY2VzLCB0ZW5zb3Iuc2hhcGUpO1xuXG4gIGNvbnN0IGZsYXR0ZW5TaGFwZSA9IFtvdXRwdXRTaXplIC8gc2xpY2VTaXplLCBzbGljZVNpemVdO1xuXG4gIGlmIChvdXRwdXRTaXplID09PSAwKSB7XG4gICAgcmV0dXJuIGJhY2tlbmQubWFrZVRlbnNvckluZm8odGVuc29yLnNoYXBlLCBpbmRpY2VzLmR0eXBlKTtcbiAgfVxuXG4gIGNvbnN0IGZsYXR0ZW5JbmRpY2VzID0gcmVzaGFwZShcbiAgICAgIHtpbnB1dHM6IHt4OiBpbmRpY2VzfSwgYmFja2VuZCwgYXR0cnM6IHtzaGFwZTogW251bVVwZGF0ZXMsIHNsaWNlUmFua119fSk7XG4gIGNvbnN0IGZsYXR0ZW5YID0gcmVzaGFwZShcbiAgICAgIHtpbnB1dHM6IHt4OiB1cGRhdGVzfSwgYmFja2VuZCwgYXR0cnM6IHtzaGFwZTogW251bVVwZGF0ZXMsIHNsaWNlU2l6ZV19fSk7XG4gIGNvbnN0IGZsYXR0ZW5UZW5zb3IgPVxuICAgICAgcmVzaGFwZSh7aW5wdXRzOiB7eDogdGVuc29yfSwgYmFja2VuZCwgYXR0cnM6IHtzaGFwZTogZmxhdHRlblNoYXBlfX0pO1xuICBjb25zdCBwcm9ncmFtID0gbmV3IFNjYXR0ZXJQcm9ncmFtKFxuICAgICAgbnVtVXBkYXRlcywgc2xpY2VSYW5rLCBmbGF0dGVuSW5kaWNlcy5zaGFwZS5sZW5ndGgsIGZsYXR0ZW5YLnNoYXBlLmxlbmd0aCxcbiAgICAgIHN0cmlkZXMsIGZsYXR0ZW5TaGFwZSwgZmFsc2UsIHRydWUpO1xuICBjb25zdCByZXMgPSBiYWNrZW5kLnJ1bldlYkdMUHJvZ3JhbShcbiAgICAgIHByb2dyYW0sIFtmbGF0dGVuWCwgZmxhdHRlbkluZGljZXMsIGZsYXR0ZW5UZW5zb3JdLCBmbGF0dGVuVGVuc29yLmR0eXBlKTtcblxuICBjb25zdCByZXNoYXBlZCA9XG4gICAgICByZXNoYXBlKHtpbnB1dHM6IHt4OiByZXN9LCBiYWNrZW5kLCBhdHRyczoge3NoYXBlOiB0ZW5zb3Iuc2hhcGV9fSk7XG5cbiAgYmFja2VuZC5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ySW5mbyhmbGF0dGVuSW5kaWNlcyk7XG4gIGJhY2tlbmQuZGlzcG9zZUludGVybWVkaWF0ZVRlbnNvckluZm8oZmxhdHRlblgpO1xuICBiYWNrZW5kLmRpc3Bvc2VJbnRlcm1lZGlhdGVUZW5zb3JJbmZvKGZsYXR0ZW5UZW5zb3IpO1xuICBiYWNrZW5kLmRpc3Bvc2VJbnRlcm1lZGlhdGVUZW5zb3JJbmZvKHJlcyk7XG5cbiAgcmV0dXJuIHJlc2hhcGVkO1xufVxuXG5leHBvcnQgY29uc3QgdGVuc29yU2NhdHRlclVwZGF0ZUNvbmZpZzogS2VybmVsQ29uZmlnID0ge1xuICBrZXJuZWxOYW1lOiBUZW5zb3JTY2F0dGVyVXBkYXRlLFxuICBiYWNrZW5kTmFtZTogJ3dlYmdsJyxcbiAga2VybmVsRnVuYzogdGVuc29yU2NhdHRlclVwZGF0ZSBhcyB1bmtub3duIGFzIEtlcm5lbEZ1bmNcbn07XG4iXX0=