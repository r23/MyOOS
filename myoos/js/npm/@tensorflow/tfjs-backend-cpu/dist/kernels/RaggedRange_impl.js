/**
 * @license
 * Copyright 2022 Google LLC.
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
import { util } from '@tensorflow/tfjs-core';
const INT32_MAX = 2147483647;
export function raggedRangeImpl(starts, startsShape, startsDType, limits, limitsShape, deltas, deltasShape) {
    // Check input tensor shapes.
    if (startsShape.length > 1) {
        throw new Error('starts must be a scalar or vector');
    }
    if (limitsShape.length > 1) {
        throw new Error('limits must be a scalar or vector');
    }
    if (deltasShape.length > 1) {
        throw new Error('deltas must be a scalar or vector');
    }
    // Determine which tensors we need to broadcast.
    const broadcastStarts = startsShape.length === 0;
    const broadcastLimits = limitsShape.length === 0;
    const broadcastDeltas = deltasShape.length === 0;
    // nRows (number of output rows) is the size of the non-broadcast inputs,
    // or 1 if all inputs are scalars.
    const inSizes = [];
    if (!broadcastStarts) {
        inSizes.push(startsShape[0]);
    }
    if (!broadcastLimits) {
        inSizes.push(limitsShape[0]);
    }
    if (!broadcastDeltas) {
        inSizes.push(deltasShape[0]);
    }
    for (let i = 1; i < inSizes.length; ++i) {
        if (inSizes[i] !== inSizes[i - 1]) {
            throw new Error('starts, limits, and deltas must have the same shape');
        }
    }
    const nRows = inSizes.length === 0 ? 1 : inSizes[0];
    // Construct the rtNestedSplits tensor.
    const rtNestedSplits = util.getArrayFromDType('int32', nRows + 1);
    rtNestedSplits[0] = 0;
    for (let row = 0; row < nRows; ++row) {
        const start = broadcastStarts ? starts[0] : starts[row];
        const limit = broadcastLimits ? limits[0] : limits[row];
        const delta = broadcastDeltas ? deltas[0] : deltas[row];
        if (delta === 0) {
            throw new Error('Requires delta != 0');
        }
        let size; // The number of elements in the specified range.
        if (((delta > 0) && (limit < start)) || ((delta < 0) && (limit > start))) {
            size = 0;
        }
        else {
            size = Math.ceil(Math.abs((limit - start) / delta));
            if (size > INT32_MAX) {
                throw new Error(`Requires ((limit - start) / delta) <= ${INT32_MAX}`);
            }
        }
        rtNestedSplits[row + 1] = rtNestedSplits[row] + size;
    }
    const nVals = rtNestedSplits[nRows];
    // Construct the rtDenseValues tensor.
    const rtDenseValues = util.getArrayFromDType(startsDType, nVals);
    let valueIndex = 0;
    for (let row = 0; row < nRows; ++row) {
        const rowSize = rtNestedSplits[row + 1] - rtNestedSplits[row];
        let value = broadcastStarts ? starts[0] : starts[row];
        const delta = broadcastDeltas ? deltas[0] : deltas[row];
        for (let i = 0; i < rowSize; ++i) {
            rtDenseValues[valueIndex++] = value;
            value += delta;
        }
    }
    return [rtNestedSplits, rtDenseValues];
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiUmFnZ2VkUmFuZ2VfaW1wbC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uL3RmanMtYmFja2VuZC1jcHUvc3JjL2tlcm5lbHMvUmFnZ2VkUmFuZ2VfaW1wbC50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7O0dBZUc7QUFFSCxPQUFPLEVBQXVCLElBQUksRUFBQyxNQUFNLHVCQUF1QixDQUFDO0FBRWpFLE1BQU0sU0FBUyxHQUFHLFVBQVUsQ0FBQztBQUU3QixNQUFNLFVBQVUsZUFBZSxDQUMzQixNQUFrQixFQUFFLFdBQXFCLEVBQUUsV0FBcUIsRUFDaEUsTUFBa0IsRUFBRSxXQUFxQixFQUFFLE1BQWtCLEVBQzdELFdBQXFCO0lBQ3ZCLDZCQUE2QjtJQUM3QixJQUFJLFdBQVcsQ0FBQyxNQUFNLEdBQUcsQ0FBQyxFQUFFO1FBQzFCLE1BQU0sSUFBSSxLQUFLLENBQUMsbUNBQW1DLENBQUMsQ0FBQztLQUN0RDtJQUNELElBQUksV0FBVyxDQUFDLE1BQU0sR0FBRyxDQUFDLEVBQUU7UUFDMUIsTUFBTSxJQUFJLEtBQUssQ0FBQyxtQ0FBbUMsQ0FBQyxDQUFDO0tBQ3REO0lBQ0QsSUFBSSxXQUFXLENBQUMsTUFBTSxHQUFHLENBQUMsRUFBRTtRQUMxQixNQUFNLElBQUksS0FBSyxDQUFDLG1DQUFtQyxDQUFDLENBQUM7S0FDdEQ7SUFFRCxnREFBZ0Q7SUFDaEQsTUFBTSxlQUFlLEdBQUcsV0FBVyxDQUFDLE1BQU0sS0FBSyxDQUFDLENBQUM7SUFDakQsTUFBTSxlQUFlLEdBQUcsV0FBVyxDQUFDLE1BQU0sS0FBSyxDQUFDLENBQUM7SUFDakQsTUFBTSxlQUFlLEdBQUcsV0FBVyxDQUFDLE1BQU0sS0FBSyxDQUFDLENBQUM7SUFFakQseUVBQXlFO0lBQ3pFLGtDQUFrQztJQUNsQyxNQUFNLE9BQU8sR0FBYSxFQUFFLENBQUM7SUFDN0IsSUFBSSxDQUFDLGVBQWUsRUFBRTtRQUNwQixPQUFP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LEdBQUcsQ0FBQyxFQUFFLEdBQUcsR0FBRyxLQUFLLEVBQUUsRUFBRSxHQUFHLEVBQUU7UUFDcEMsTUFBTSxPQUFPLEdBQUcsY0FBYyxDQUFDLEdBQUcsR0FBRyxDQUFDLENBQUMsR0FBRyxjQUFjLENBQUMsR0FBRyxDQUFDLENBQUM7UUFDOUQsSUFBSSxLQUFLLEdBQUcsZUFBZSxDQUFDLENBQUMsQ0FBQyxNQUFNLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDLE1BQU0sQ0FBQyxHQUFHLENBQUMsQ0FBQztRQUN0RCxNQUFNLEtBQUssR0FBRyxlQUFlLENBQUMsQ0FBQyxDQUFDLE1BQU0sQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUMsTUFBTSxDQUFDLEdBQUcsQ0FBQyxDQUFDO1FBQ3hELEtBQUssSUFBSSxDQUFDLEdBQUcsQ0FBQyxFQUFFLENBQUMsR0FBRyxPQUFPLEVBQUUsRUFBRSxDQUFDLEVBQUU7WUFDaEMsYUFBYSxDQUFDLFVBQVUsRUFBRSxDQUFDLEdBQUcsS0FBSyxDQUFDO1lBQ3BDLEtBQUssSUFBSSxLQUFLLENBQUM7U0FDaEI7S0FDRjtJQUVELE9BQU8sQ0FBQyxjQUFjLEVBQUUsYUFBYSxDQUFDLENBQUM7QUFDekMsQ0FBQyIsInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICogQGxpY2Vuc2VcbiAqIENvcHlyaWdodCAyMDIyIEdvb2dsZSBMTEMuXG4gKiBMaWNlbnNlZCB1bmRlciB0aGUgQXBhY2hlIExpY2Vuc2UsIFZlcnNpb24gMi4wICh0aGUgXCJMaWNlbnNlXCIpO1xuICogeW91IG1heSBub3QgdXNlIHRoaXMgZmlsZSBleGNlcHQgaW4gY29tcGxpYW5jZSB3aXRoIHRoZSBMaWNlbnNlLlxuICogWW91IG1heSBvYnRhaW4gYSBjb3B5IG9mIHRoZSBMaWNlbnNlIGF0XG4gKlxuICogaHR0cDovL3d3dy5hcGFjaGUub3JnL2xpY2Vuc2VzL0xJQ0VOU0UtMi4wXG4gKlxuICogVW5sZXNzIHJlcXVpcmVkIGJ5IGFwcGxpY2FibGUgbGF3IG9yIGFncmVlZCB0byBpbiB3cml0aW5nLCBzb2Z0d2FyZVxuICogZGlzdHJpYnV0ZWQgdW5kZXIgdGhlIExpY2Vuc2UgaXMgZGlzdHJpYnV0ZWQgb24gYW4gXCJBUyBJU1wiIEJBU0lTLFxuICogV0lUSE9VVCBXQVJSQU5USUVTIE9SIENPTkRJVElPTlMgT0YgQU5ZIEtJTkQsIGVpdGhlciBleHByZXNzIG9yIGltcGxpZWQuXG4gKiBTZWUgdGhlIExpY2Vuc2UgZm9yIHRoZSBzcGVjaWZpYyBsYW5ndWFnZSBnb3Zlcm5pbmcgcGVybWlzc2lvbnMgYW5kXG4gKiBsaW1pdGF0aW9ucyB1bmRlciB0aGUgTGljZW5zZS5cbiAqID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG4gKi9cblxuaW1wb3J0IHtEYXRhVHlwZSwgVHlwZWRBcnJheSwgdXRpbH0gZnJvbSAnQHRlbnNvcmZsb3cvdGZqcy1jb3JlJztcblxuY29uc3QgSU5UMzJfTUFYID0gMjE0NzQ4MzY0NztcblxuZXhwb3J0IGZ1bmN0aW9uIHJhZ2dlZFJhbmdlSW1wbChcbiAgICBzdGFydHM6IFR5cGVkQXJyYXksIHN0YXJ0c1NoYXBlOiBudW1iZXJbXSwgc3RhcnRzRFR5cGU6IERhdGFUeXBlLFxuICAgIGxpbWl0czogVHlwZWRBcnJheSwgbGltaXRzU2hhcGU6IG51bWJlcltdLCBkZWx0YXM6IFR5cGVkQXJyYXksXG4gICAgZGVsdGFzU2hhcGU6IG51bWJlcltdKTogW1R5cGVkQXJyYXksIFR5cGVkQXJyYXldIHtcbiAgLy8gQ2hlY2sgaW5wdXQgdGVuc29yIHNoYXBlcy5cbiAgaWYgKHN0YXJ0c1NoYXBlLmxlbmd0aCA+IDEpIHtcbiAgICB0aHJvdyBuZXcgRXJyb3IoJ3N0YXJ0cyBtdXN0IGJlIGEgc2NhbGFyIG9yIHZlY3RvcicpO1xuICB9XG4gIGlmIChsaW1pdHNTaGFwZS5sZW5ndGggPiAxKSB7XG4gICAgdGhyb3cgbmV3IEVycm9yKCdsaW1pdHMgbXVzdCBiZSBhIHNjYWxhciBvciB2ZWN0b3InKTtcbiAgfVxuICBpZiAoZGVsdGFzU2hhcGUubGVuZ3RoID4gMSkge1xuICAgIHRocm93IG5ldyBFcnJvcignZGVsdGFzIG11c3QgYmUgYSBzY2FsYXIgb3IgdmVjdG9yJyk7XG4gIH1cblxuICAvLyBEZXRlcm1pbmUgd2hpY2ggdGVuc29ycyB3ZSBuZWVkIHRvIGJyb2FkY2FzdC5cbiAgY29uc3QgYnJvYWRjYXN0U3RhcnRzID0gc3RhcnRzU2hhcGUubGVuZ3RoID09PSAwO1xuICBjb25zdCBicm9hZGNhc3RMaW1pdHMgPSBsaW1pdHNTaGFwZS5sZW5ndGggPT09IDA7XG4gIGNvbnN0IGJyb2FkY2FzdERlbHRhcyA9IGRlbHRhc1NoYXBlLmxlbmd0aCA9PT0gMDtcblxuICAvLyBuUm93cyAobnVtYmVyIG9mIG91dHB1dCByb3dzKSBpcyB0aGUgc2l6ZSBvZiB0aGUgbm9uLWJyb2FkY2FzdCBpbnB1dHMsXG4gIC8vIG9yIDEgaWYgYWxsIGlucHV0cyBhcmUgc2NhbGFycy5cbiAgY29uc3QgaW5TaXplczogbnVtYmVyW10gPSBbXTtcbiAgaWYgKCFicm9hZGNhc3RTdGFydHMpIHtcbiAgICBpblNpemVzLnB1c2goc3RhcnRzU2hhcGVbMF0pO1xuICB9XG4gIGlmICghYnJvYWRjYXN0TGltaXRzKSB7XG4gICAgaW5TaXplcy5wdXNoKGxpbWl0c1NoYXBlWzBdKTtcbiAgfVxuICBpZiAoIWJyb2FkY2FzdERlbHRhcykge1xuICAgIGluU2l6ZXMucHVzaChkZWx0YXNTaGFwZVswXSk7XG4gIH1cblxuICBmb3IgKGxldCBpID0gMTsgaSA8IGluU2l6ZXMubGVuZ3RoOyArK2kpIHtcbiAgICBpZiAoaW5TaXplc1tpXSAhPT0gaW5TaXplc1tpIC0gMV0pIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcignc3RhcnRzLCBsaW1pdHMsIGFuZCBkZWx0YXMgbXVzdCBoYXZlIHRoZSBzYW1lIHNoYXBlJyk7XG4gICAgfVxuICB9XG4gIGNvbnN0IG5Sb3dzID0gaW5TaXplcy5sZW5ndGggPT09IDAgPyAxIDogaW5TaXplc1swXTtcblxuICAvLyBDb25zdHJ1Y3QgdGhlIHJ0TmVzdGVkU3BsaXRzIHRlbnNvci5cbiAgY29uc3QgcnROZXN0ZWRTcGxpdHMgPVxuICAgICAgdXRpbC5nZXRBcnJheUZyb21EVHlwZSgnaW50MzInLCBuUm93cyArIDEpIGFzIFR5cGVkQXJyYXk7XG4gIHJ0TmVzdGVkU3BsaXRzWzBdID0gMDtcbiAgZm9yIChsZXQgcm93ID0gMDsgcm93IDwgblJvd3M7ICsrcm93KSB7XG4gICAgY29uc3Qgc3RhcnQgPSBicm9hZGNhc3RTdGFydHMgPyBzdGFydHNbMF0gOiBzdGFydHNbcm93XTtcbiAgICBjb25zdCBsaW1pdCA9IGJyb2FkY2FzdExpbWl0cyA/IGxpbWl0c1swXSA6IGxpbWl0c1tyb3ddO1xuICAgIGNvbnN0IGRlbHRhID0gYnJvYWRjYXN0RGVsdGFzID8gZGVsdGFzWzBdIDogZGVsdGFzW3Jvd107XG4gICAgaWYgKGRlbHRhID09PSAwKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoJ1JlcXVpcmVzIGRlbHRhICE9IDAnKTtcbiAgICB9XG4gICAgbGV0IHNpemU6IG51bWJlcjsgIC8vIFRoZSBudW1iZXIgb2YgZWxlbWVudHMgaW4gdGhlIHNwZWNpZmllZCByYW5nZS5cbiAgICBpZiAoKChkZWx0YSA+IDApICYmIChsaW1pdCA8IHN0YXJ0KSkgfHwgKChkZWx0YSA8IDApICYmIChsaW1pdCA+IHN0YXJ0KSkpIHtcbiAgICAgIHNpemUgPSAwO1xuICAgIH0gZWxzZSB7XG4gICAgICBzaXplID0gTWF0aC5jZWlsKE1hdGguYWJzKChsaW1pdCAtIHN0YXJ0KSAvIGRlbHRhKSk7XG5cbiAgICAgIGlmIChzaXplID4gSU5UMzJfTUFYKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcihgUmVxdWlyZXMgKChsaW1pdCAtIHN0YXJ0KSAvIGRlbHRhKSA8PSAke0lOVDMyX01BWH1gKTtcbiAgICAgIH1cbiAgICB9XG4gICAgcnROZXN0ZWRTcGxpdHNbcm93ICsgMV0gPSBydE5lc3RlZFNwbGl0c1tyb3ddICsgc2l6ZTtcbiAgfVxuXG4gIGNvbnN0IG5WYWxzID0gcnROZXN0ZWRTcGxpdHNbblJvd3NdO1xuXG4gIC8vIENvbnN0cnVjdCB0aGUgcnREZW5zZVZhbHVlcyB0ZW5zb3IuXG4gIGNvbnN0IHJ0RGVuc2VWYWx1ZXMgPVxuICAgICAgdXRpbC5nZXRBcnJheUZyb21EVHlwZShzdGFydHNEVHlwZSwgblZhbHMpIGFzIFR5cGVkQXJyYXk7XG5cbiAgbGV0IHZhbHVlSW5kZXggPSAwO1xuICBmb3IgKGxldCByb3cgPSAwOyByb3cgPCBuUm93czsgKytyb3cpIHtcbiAgICBjb25zdCByb3dTaXplID0gcnROZXN0ZWRTcGxpdHNbcm93ICsgMV0gLSBydE5lc3RlZFNwbGl0c1tyb3ddO1xuICAgIGxldCB2YWx1ZSA9IGJyb2FkY2FzdFN0YXJ0cyA/IHN0YXJ0c1swXSA6IHN0YXJ0c1tyb3ddO1xuICAgIGNvbnN0IGRlbHRhID0gYnJvYWRjYXN0RGVsdGFzID8gZGVsdGFzWzBdIDogZGVsdGFzW3Jvd107XG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCByb3dTaXplOyArK2kpIHtcbiAgICAgIHJ0RGVuc2VWYWx1ZXNbdmFsdWVJbmRleCsrXSA9IHZhbHVlO1xuICAgICAgdmFsdWUgKz0gZGVsdGE7XG4gICAgfVxuICB9XG5cbiAgcmV0dXJuIFtydE5lc3RlZFNwbGl0cywgcnREZW5zZVZhbHVlc107XG59XG4iXX0=