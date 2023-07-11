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
import { env } from '../environment';
import * as util from '../util';
import { CompositeArrayBuffer } from './composite_array_buffer';
import { decodeWeights } from './io_utils';
import { monitorPromisesProgress } from './progress';
import { DTYPE_VALUE_SIZE_MAP } from './types';
/**
 * Reads binary weights data from a number of URLs.
 *
 * @param fetchURLs URLs to send the HTTP requests at, using `fetch` calls.
 * @param requestOptions RequestInit (options) for the HTTP requests.
 * @param fetchFunc Optional overriding value for the `window.fetch` function.
 * @param onProgress Optional, progress callback function, fired periodically
 *   before the load is completed.
 * @returns A `Promise` of an Array of `ArrayBuffer`. The Array has the same
 *   length as `fetchURLs`.
 */
export async function loadWeightsAsArrayBuffer(fetchURLs, loadOptions) {
    if (loadOptions == null) {
        loadOptions = {};
    }
    const fetchFunc = loadOptions.fetchFunc == null ? env().platform.fetch :
        loadOptions.fetchFunc;
    // Create the requests for all of the weights in parallel.
    const requests = fetchURLs.map(fetchURL => fetchFunc(fetchURL, loadOptions.requestInit, { isBinary: true }));
    const fetchStartFraction = 0;
    const fetchEndFraction = 0.5;
    const responses = loadOptions.onProgress == null ?
        await Promise.all(requests) :
        await monitorPromisesProgress(requests, loadOptions.onProgress, fetchStartFraction, fetchEndFraction);
    const bufferPromises = responses.map(response => response.arrayBuffer());
    const bufferStartFraction = 0.5;
    const bufferEndFraction = 1;
    const buffers = loadOptions.onProgress == null ?
        await Promise.all(bufferPromises) :
        await monitorPromisesProgress(bufferPromises, loadOptions.onProgress, bufferStartFraction, bufferEndFraction);
    return buffers;
}
/**
 * Reads a weights manifest JSON configuration, fetches the weights and
 * returns them as `Tensor`s.
 *
 * @param manifest The weights manifest JSON.
 * @param filePathPrefix The path prefix for filenames given in the manifest.
 *     Defaults to the empty string.
 * @param weightNames The names of the weights to be fetched.
 */
export async function loadWeights(manifest, filePathPrefix = '', weightNames, requestInit) {
    // TODO(nsthorat): Groups are currently fetched atomically. If you need a
    // single weight from a group, the whole group will be fetched. At a future
    // date, we should support fetching only the individual shards within a
    // group that are needed to reconstruct the requested weight.
    // TODO(cais): Use `decodeWeights` for implementation.
    const fetchWeights = (fetchUrls) => loadWeightsAsArrayBuffer(fetchUrls, { requestInit });
    const loadWeights = weightsLoaderFactory(fetchWeights);
    return loadWeights(manifest, filePathPrefix, weightNames);
}
/**
 * Creates a function, which reads a weights manifest JSON configuration,
 * fetches the weight files using the specified function and returns them as
 * `Tensor`s.
 *
 * ```js
 * // example for creating a nodejs weight loader, which reads the weight files
 * // from disk using fs.readFileSync
 *
 * import * as fs from 'fs'
 *
 * const fetchWeightsFromDisk = (filePaths: string[]) =>
 *   filePaths.map(filePath => fs.readFileSync(filePath).buffer)
 *
 * const loadWeights = tf.io.weightsLoaderFactory(fetchWeightsFromDisk)
 *
 * const manifest = JSON.parse(
 *   fs.readFileSync('./my_model-weights_manifest').toString()
 * )
 * const weightMap = await loadWeights(manifest, './')
 * ```
 * @param fetchWeightsFunction The function used for fetching the weight files.
 * @returns Weight loading function.
 */
export function weightsLoaderFactory(fetchWeightsFunction) {
    return async (manifest, filePathPrefix = '', weightNames) => {
        // Collect all the groups, weights, and their relative offsets to be
        // fetched.
        const groupIndicesToFetchMap = manifest.map(() => false);
        const groupWeightsToFetch = {};
        const weightsFound = weightNames != null ? weightNames.map(() => false) : [];
        const allManifestWeightNames = [];
        manifest.forEach((manifestGroupConfig, groupIndex) => {
            let groupOffset = 0;
            manifestGroupConfig.weights.forEach(weightsEntry => {
                const rawDtype = ('quantization' in weightsEntry) ?
                    weightsEntry.quantization.dtype :
                    weightsEntry.dtype;
                const weightsBytes = DTYPE_VALUE_SIZE_MAP[rawDtype] *
                    util.sizeFromShape(weightsEntry.shape);
                const enqueueWeightsForFetchingFn = () => {
                    groupIndicesToFetchMap[groupIndex] = true;
                    if (groupWeightsToFetch[groupIndex] == null) {
                        groupWeightsToFetch[groupIndex] = [];
                    }
                    groupWeightsToFetch[groupIndex].push({
                        manifestEntry: weightsEntry,
                        groupOffset,
                        sizeBytes: weightsBytes
                    });
                };
                if (weightNames != null) {
                    weightNames.forEach((weightName, weightIndex) => {
                        if (weightName === weightsEntry.name) {
                            enqueueWeightsForFetchingFn();
                            weightsFound[weightIndex] = true;
                        }
                    });
                }
                else {
                    enqueueWeightsForFetchingFn();
                }
                allManifestWeightNames.push(weightsEntry.name);
                groupOffset += weightsBytes;
            });
        });
        if (!weightsFound.every(found => found)) {
            const weightsNotFound = weightNames.filter((_, i) => !weightsFound[i]);
            throw new Error(`Could not find weights in manifest with names: ` +
                `${weightsNotFound.join(', ')}. \n` +
                `Manifest JSON has weights with names: ` +
                `${allManifestWeightNames.join(', ')}.`);
        }
        // Convert the one-hot boolean groupId => shouldFetch map to a list of group
        // IDs.
        const groupIndicesToFetch = groupIndicesToFetchMap.reduce((accumulator, shouldFetch, i) => {
            if (shouldFetch) {
                accumulator.push(i);
            }
            return accumulator;
        }, []);
        const fetchUrls = [];
        groupIndicesToFetch.forEach(i => {
            manifest[i].paths.forEach(filepath => {
                const fetchUrl = filePathPrefix +
                    (!filePathPrefix.endsWith('/') ? '/' : '') + filepath;
                fetchUrls.push(fetchUrl);
            });
        });
        const buffers = await fetchWeightsFunction(fetchUrls);
        const weightsTensorMap = {};
        let bufferIndexOffset = 0;
        groupIndicesToFetch.forEach(i => {
            const numBuffers = manifest[i].paths.length;
            const weightsBuffer = new CompositeArrayBuffer(buffers.slice(bufferIndexOffset, bufferIndexOffset + numBuffers));
            const weightsEntries = groupWeightsToFetch[i];
            weightsEntries.forEach(weightsEntry => {
                const byteBuffer = weightsBuffer.slice(weightsEntry.groupOffset, weightsEntry.groupOffset + weightsEntry.sizeBytes);
                const nameToTensorMap = decodeWeights(byteBuffer, [weightsEntry.manifestEntry]);
                for (const name in nameToTensorMap) {
                    weightsTensorMap[name] = nameToTensorMap[name];
                }
            });
            bufferIndexOffset += numBuffers;
        });
        return weightsTensorMap;
    };
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoid2VpZ2h0c19sb2FkZXIuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlcyI6WyIuLi8uLi8uLi8uLi8uLi8uLi90ZmpzLWNvcmUvc3JjL2lvL3dlaWdodHNfbG9hZGVyLnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBOzs7Ozs7Ozs7Ozs7Ozs7R0FlRztBQUVILE9BQU8sRUFBQyxHQUFHLEVBQUMsTUFBTSxnQkFBZ0IsQ0FBQztBQUduQyxPQUFPLEtBQUssSUFBSSxNQUFNLFNBQVMsQ0FBQztBQUNoQyxPQUFPLEVBQUMsb0JBQW9CLEVBQUMsTUFBTSwwQkFBMEIsQ0FBQztBQUM5RCxPQUFPLEVBQUMsYUFBYSxFQUFDLE1BQU0sWUFBWSxDQUFDO0FBQ3pDLE9BQU8sRUFBQyx1QkFBdUIsRUFBQyxNQUFNLFlBQVksQ0FBQztBQUNuRCxPQUFPLEVBQUMsb0JBQW9CLEVBQTJELE1BQU0sU0FBUyxDQUFDO0FBRXZHOzs7Ozs7Ozs7O0dBVUc7QUFDSCxNQUFNLENBQUMsS0FBSyxVQUFVLHdCQUF3QixDQUM1QyxTQUFtQixFQUFFLFdBQXlCO0lBQzlDLElBQUksV0FBVyxJQUFJLElBQUksRUFBRTtRQUN2QixXQUFXLEdBQUcsRUFBRSxDQUFDO0tBQ2xCO0lBRUQsTUFBTSxTQUFTLEdBQUcsV0FBVyxDQUFDLFNBQVMsSUFBSSxJQUFJLENBQUMsQ0FBQyxDQUFDLEdBQUcsRUFBRSxDQUFDLFFBQVEsQ0FBQyxLQUFLLENBQUMsQ0FBQztRQUN0RSxXQUFXLENBQUMsU0FBUyxDQUFDO0lBRXhCLDBEQUEwRDtJQUMxRCxNQUFNLFFBQVEsR0FBRyxTQUFTLENBQUMsR0FBRyxDQUM1QixRQUFRLENBQUMsRUFBRSxDQUNULFNBQVMsQ0FBQyxRQUFRLEVBQUUsV0FBVyxDQUFDLFdBQVcsRUFBRSxFQUFFLFFBQVEsRUFBRSxJQUFJLEVBQUUsQ0FBQyxDQUFDLENBQUM7SUFFdEUsTUFBTSxrQkFBa0IsR0FBRyxDQUFDLENBQUM7SUFDN0IsTUFBTSxnQkFBZ0IsR0FBRyxHQUFH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QUFhLEdBQUcsSUFBSSxvQkFBb0IsQ0FDNUMsT0FBTyxDQUFDLEtBQUssQ0FBQyxpQkFBaUIsRUFBRSxpQkFBaUIsR0FBRyxVQUFVLENBQUMsQ0FBQyxDQUFDO1lBRXBFLE1BQU0sY0FBYyxHQUFHLG1CQUFtQixDQUFDLENBQUMsQ0FBQyxDQUFDO1lBRTlDLGNBQWMsQ0FBQyxPQUFPLENBQUMsWUFBWSxDQUFDLEVBQUU7Z0JBQ3BDLE1BQU0sVUFBVSxHQUFHLGFBQWEsQ0FBQyxLQUFLLENBQ3BDLFlBQVksQ0FBQyxXQUFXLEVBQ3hCLFlBQVksQ0FBQyxXQUFXLEdBQUcsWUFBWSxDQUFDLFNBQVMsQ0FBQyxDQUFDO2dCQUNyRCxNQUFNLGVBQWUsR0FDbkIsYUFBYSxDQUFDLFVBQVUsRUFBRSxDQUFDLFlBQVksQ0FBQyxhQUFhLENBQUMsQ0FBQyxDQUFDO2dCQUMxRCxLQUFLLE1BQU0sSUFBSSxJQUFJLGVBQWUsRUFBRTtvQkFDbEMsZ0JBQWdCLENBQUMsSUFBSSxDQUFDLEdBQUcsZUFBZSxDQUFDLElBQUksQ0FBQyxDQUFDO2lCQUNoRDtZQUNILENBQUMsQ0FBQyxDQUFDO1lBRUgsaUJBQWlCLElBQUksVUFBVSxDQUFDO1FBQ2xDLENBQUMsQ0FBQyxDQUFDO1FBRUgsT0FBTyxnQkFBZ0IsQ0FBQztJQUMxQixDQUFDLENBQUM7QUFDSixDQUFDIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMTggR29vZ2xlIExMQy4gQWxsIFJpZ2h0cyBSZXNlcnZlZC5cbiAqIExpY2Vuc2VkIHVuZGVyIHRoZSBBcGFjaGUgTGljZW5zZSwgVmVyc2lvbiAyLjAgKHRoZSBcIkxpY2Vuc2VcIik7XG4gKiB5b3UgbWF5IG5vdCB1c2UgdGhpcyBmaWxlIGV4Y2VwdCBpbiBjb21wbGlhbmNlIHdpdGggdGhlIExpY2Vuc2UuXG4gKiBZb3UgbWF5IG9idGFpbiBhIGNvcHkgb2YgdGhlIExpY2Vuc2UgYXRcbiAqXG4gKiBodHRwOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcbiAqXG4gKiBVbmxlc3MgcmVxdWlyZWQgYnkgYXBwbGljYWJsZSBsYXcgb3IgYWdyZWVkIHRvIGluIHdyaXRpbmcsIHNvZnR3YXJlXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXG4gKiBXSVRIT1VUIFdBUlJBTlRJRVMgT1IgQ09ORElUSU9OUyBPRiBBTlkgS0lORCwgZWl0aGVyIGV4cHJlc3Mgb3IgaW1wbGllZC5cbiAqIFNlZSB0aGUgTGljZW5zZSBmb3IgdGhlIHNwZWNpZmljIGxhbmd1YWdlIGdvdmVybmluZyBwZXJtaXNzaW9ucyBhbmRcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxuICogPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cbiAqL1xuXG5pbXBvcnQge2Vudn0gZnJvbSAnLi4vZW52aXJvbm1lbnQnO1xuXG5pbXBvcnQge05hbWVkVGVuc29yTWFwfSBmcm9tICcuLi90ZW5zb3JfdHlwZXMnO1xuaW1wb3J0ICogYXMgdXRpbCBmcm9tICcuLi91dGlsJztcbmltcG9ydCB7Q29tcG9zaXRlQXJyYXlCdWZmZXJ9IGZyb20gJy4vY29tcG9zaXRlX2FycmF5X2J1ZmZlcic7XG5pbXBvcnQge2RlY29kZVdlaWdodHN9IGZyb20gJy4vaW9fdXRpbHMnO1xuaW1wb3J0IHttb25pdG9yUHJvbWlzZXNQcm9ncmVzc30gZnJvbSAnLi9wcm9ncmVzcyc7XG5pbXBvcnQge0RUWVBFX1ZBTFVFX1NJWkVfTUFQLCBMb2FkT3B0aW9ucywgV2VpZ2h0c01hbmlmZXN0Q29uZmlnLCBXZWlnaHRzTWFuaWZlc3RFbnRyeX0gZnJvbSAnLi90eXBlcyc7XG5cbi8qKlxuICogUmVhZHMgYmluYXJ5IHdlaWdodHMgZGF0YSBmcm9tIGEgbnVtYmVyIG9mIFVSTHMuXG4gKlxuICogQHBhcmFtIGZldGNoVVJMcyBVUkxzIHRvIHNlbmQgdGhlIEhUVFAgcmVxdWVzdHMgYXQsIHVzaW5nIGBmZXRjaGAgY2FsbHMuXG4gKiBAcGFyYW0gcmVxdWVzdE9wdGlvbnMgUmVxdWVzdEluaXQgKG9wdGlvbnMpIGZvciB0aGUgSFRUUCByZXF1ZXN0cy5cbiAqIEBwYXJhbSBmZXRjaEZ1bmMgT3B0aW9uYWwgb3ZlcnJpZGluZyB2YWx1ZSBmb3IgdGhlIGB3aW5kb3cuZmV0Y2hgIGZ1bmN0aW9uLlxuICogQHBhcmFtIG9uUHJvZ3Jlc3MgT3B0aW9uYWwsIHByb2dyZXNzIGNhbGxiYWNrIGZ1bmN0aW9uLCBmaXJlZCBwZXJpb2RpY2FsbHlcbiAqICAgYmVmb3JlIHRoZSBsb2FkIGlzIGNvbXBsZXRlZC5cbiAqIEByZXR1cm5zIEEgYFByb21pc2VgIG9mIGFuIEFycmF5IG9mIGBBcnJheUJ1ZmZlcmAuIFRoZSBBcnJheSBoYXMgdGhlIHNhbWVcbiAqICAgbGVuZ3RoIGFzIGBmZXRjaFVSTHNgLlxuICovXG5leHBvcnQgYXN5bmMgZnVuY3Rpb24gbG9hZFdlaWdodHNBc0FycmF5QnVmZmVyKFxuICBmZXRjaFVSTHM6IHN0cmluZ1tdLCBsb2FkT3B0aW9ucz86IExvYWRPcHRpb25zKTogUHJvbWlzZTxBcnJheUJ1ZmZlcltdPiB7XG4gIGlmIChsb2FkT3B0aW9ucyA9PSBudWxsKSB7XG4gICAgbG9hZE9wdGlvbnMgPSB7fTtcbiAgfVxuXG4gIGNvbnN0IGZldGNoRnVuYyA9IGxvYWRPcHRpb25zLmZldGNoRnVuYyA9PSBudWxsID8gZW52KCkucGxhdGZvcm0uZmV0Y2ggOlxuICAgIGxvYWRPcHRpb25zLmZldGNoRnVuYztcblxuICAvLyBDcmVhdGUgdGhlIHJlcXVlc3RzIGZvciBhbGwgb2YgdGhlIHdlaWdodHMgaW4gcGFyYWxsZWwuXG4gIGNvbnN0IHJlcXVlc3RzID0gZmV0Y2hVUkxzLm1hcChcbiAgICBmZXRjaFVSTCA9PlxuICAgICAgZmV0Y2hGdW5jKGZldGNoVVJMLCBsb2FkT3B0aW9ucy5yZXF1ZXN0SW5pdCwgeyBpc0JpbmFyeTogdHJ1ZSB9KSk7XG5cbiAgY29uc3QgZmV0Y2hTdGFydEZyYWN0aW9uID0gMDtcbiAgY29uc3QgZmV0Y2hFbmRGcmFjdGlvbiA9IDAuNTtcblxuICBjb25zdCByZXNwb25zZXMgPSBsb2FkT3B0aW9ucy5vblByb2dyZXNzID09IG51bGwgP1xuICAgIGF3YWl0IFByb21pc2UuYWxsKHJlcXVlc3RzKSA6XG4gICAgYXdhaXQgbW9uaXRvclByb21pc2VzUHJvZ3Jlc3MoXG4gICAgICByZXF1ZXN0cywgbG9hZE9wdGlvbnMub25Qcm9ncmVzcywgZmV0Y2hTdGFydEZyYWN0aW9uLFxuICAgICAgZmV0Y2hFbmRGcmFjdGlvbik7XG5cbiAgY29uc3QgYnVmZmVyUHJvbWlzZXMgPSByZXNwb25zZXMubWFwKHJlc3BvbnNlID0+IHJlc3BvbnNlLmFycmF5QnVmZmVyKCkpO1xuXG4gIGNvbnN0IGJ1ZmZlclN0YXJ0RnJhY3Rpb24gPSAwLjU7XG4gIGNvbnN0IGJ1ZmZlckVuZEZyYWN0aW9uID0gMTtcblxuICBjb25zdCBidWZmZXJzID0gbG9hZE9wdGlvbnMub25Qcm9ncmVzcyA9PSBudWxsID9cbiAgICBhd2FpdCBQcm9taXNlLmFsbChidWZmZXJQcm9taXNlcykgOlxuICAgIGF3YWl0IG1vbml0b3JQcm9taXNlc1Byb2dyZXNzKFxuICAgICAgYnVmZmVyUHJvbWlzZXMsIGxvYWRPcHRpb25zLm9uUHJvZ3Jlc3MsIGJ1ZmZlclN0YXJ0RnJhY3Rpb24sXG4gICAgICBidWZmZXJFbmRGcmFjdGlvbik7XG4gIHJldHVybiBidWZmZXJzO1xufVxuXG4vKipcbiAqIFJlYWRzIGEgd2VpZ2h0cyBtYW5pZmVzdCBKU09OIGNvbmZpZ3VyYXRpb24sIGZldGNoZXMgdGhlIHdlaWdodHMgYW5kXG4gKiByZXR1cm5zIHRoZW0gYXMgYFRlbnNvcmBzLlxuICpcbiAqIEBwYXJhbSBtYW5pZmVzdCBUaGUgd2VpZ2h0cyBtYW5pZmVzdCBKU09OLlxuICogQHBhcmFtIGZpbGVQYXRoUHJlZml4IFRoZSBwYXRoIHByZWZpeCBmb3IgZmlsZW5hbWVzIGdpdmVuIGluIHRoZSBtYW5pZmVzdC5cbiAqICAgICBEZWZhdWx0cyB0byB0aGUgZW1wdHkgc3RyaW5nLlxuICogQHBhcmFtIHdlaWdodE5hbWVzIFRoZSBuYW1lcyBvZiB0aGUgd2VpZ2h0cyB0byBiZSBmZXRjaGVkLlxuICovXG5leHBvcnQgYXN5bmMgZnVuY3Rpb24gbG9hZFdlaWdodHMoXG4gIG1hbmlmZXN0OiBXZWlnaHRzTWFuaWZlc3RDb25maWcsIGZpbGVQYXRoUHJlZml4ID0gJycsXG4gIHdlaWdodE5hbWVzPzogc3RyaW5nW10sXG4gIHJlcXVlc3RJbml0PzogUmVxdWVzdEluaXQpOiBQcm9taXNlPE5hbWVkVGVuc29yTWFwPiB7XG4gIC8vIFRPRE8obnN0aG9yYXQpOiBHcm91cHMgYXJlIGN1cnJlbnRseSBmZXRjaGVkIGF0b21pY2FsbHkuIElmIHlvdSBuZWVkIGFcbiAgLy8gc2luZ2xlIHdlaWdodCBmcm9tIGEgZ3JvdXAsIHRoZSB3aG9sZSBncm91cCB3aWxsIGJlIGZldGNoZWQuIEF0IGEgZnV0dXJlXG4gIC8vIGRhdGUsIHdlIHNob3VsZCBzdXBwb3J0IGZldGNoaW5nIG9ubHkgdGhlIGluZGl2aWR1YWwgc2hhcmRzIHdpdGhpbiBhXG4gIC8vIGdyb3VwIHRoYXQgYXJlIG5lZWRlZCB0byByZWNvbnN0cnVjdCB0aGUgcmVxdWVzdGVkIHdlaWdodC5cbiAgLy8gVE9ETyhjYWlzKTogVXNlIGBkZWNvZGVXZWlnaHRzYCBmb3IgaW1wbGVtZW50YXRpb24uXG5cbiAgY29uc3QgZmV0Y2hXZWlnaHRzID0gKGZldGNoVXJsczogc3RyaW5nW10pID0+XG4gICAgbG9hZFdlaWdodHNBc0FycmF5QnVmZmVyKGZldGNoVXJscywgeyByZXF1ZXN0SW5pdCB9KTtcbiAgY29uc3QgbG9hZFdlaWdodHMgPSB3ZWlnaHRzTG9hZGVyRmFjdG9yeShmZXRjaFdlaWdodHMpO1xuXG4gIHJldHVybiBsb2FkV2VpZ2h0cyhtYW5pZmVzdCwgZmlsZVBhdGhQcmVmaXgsIHdlaWdodE5hbWVzKTtcbn1cblxuLyoqXG4gKiBDcmVhdGVzIGEgZnVuY3Rpb24sIHdoaWNoIHJlYWRzIGEgd2VpZ2h0cyBtYW5pZmVzdCBKU09OIGNvbmZpZ3VyYXRpb24sXG4gKiBmZXRjaGVzIHRoZSB3ZWlnaHQgZmlsZXMgdXNpbmcgdGhlIHNwZWNpZmllZCBmdW5jdGlvbiBhbmQgcmV0dXJucyB0aGVtIGFzXG4gKiBgVGVuc29yYHMuXG4gKlxuICogYGBganNcbiAqIC8vIGV4YW1wbGUgZm9yIGNyZWF0aW5nIGEgbm9kZWpzIHdlaWdodCBsb2FkZXIsIHdoaWNoIHJlYWRzIHRoZSB3ZWlnaHQgZmlsZXNcbiAqIC8vIGZyb20gZGlzayB1c2luZyBmcy5yZWFkRmlsZVN5bmNcbiAqXG4gKiBpbXBvcnQgKiBhcyBmcyBmcm9tICdmcydcbiAqXG4gKiBjb25zdCBmZXRjaFdlaWdodHNGcm9tRGlzayA9IChmaWxlUGF0aHM6IHN0cmluZ1tdKSA9PlxuICogICBmaWxlUGF0aHMubWFwKGZpbGVQYXRoID0+IGZzLnJlYWRGaWxlU3luYyhmaWxlUGF0aCkuYnVmZmVyKVxuICpcbiAqIGNvbnN0IGxvYWRXZWlnaHRzID0gdGYuaW8ud2VpZ2h0c0xvYWRlckZhY3RvcnkoZmV0Y2hXZWlnaHRzRnJvbURpc2spXG4gKlxuICogY29uc3QgbWFuaWZlc3QgPSBKU09OLnBhcnNlKFxuICogICBmcy5yZWFkRmlsZVN5bmMoJy4vbXlfbW9kZWwtd2VpZ2h0c19tYW5pZmVzdCcpLnRvU3RyaW5nKClcbiAqIClcbiAqIGNvbnN0IHdlaWdodE1hcCA9IGF3YWl0IGxvYWRXZWlnaHRzKG1hbmlmZXN0LCAnLi8nKVxuICogYGBgXG4gKiBAcGFyYW0gZmV0Y2hXZWlnaHRzRnVuY3Rpb24gVGhlIGZ1bmN0aW9uIHVzZWQgZm9yIGZldGNoaW5nIHRoZSB3ZWlnaHQgZmlsZXMuXG4gKiBAcmV0dXJucyBXZWlnaHQgbG9hZGluZyBmdW5jdGlvbi5cbiAqL1xuZXhwb3J0IGZ1bmN0aW9uIHdlaWdodHNMb2FkZXJGYWN0b3J5KFxuICBmZXRjaFdlaWdodHNGdW5jdGlvbjogKGZldGNoVXJsczogc3RyaW5nW10pID0+IFByb21pc2U8QXJyYXlCdWZmZXJbXT4pOlxuICAobWFuaWZlc3Q6IFdlaWdodHNNYW5pZmVzdENvbmZpZywgZmlsZVBhdGhQcmVmaXg/OiBzdHJpbmcsXG4gICAgd2VpZ2h0TmFtZXM/OiBzdHJpbmdbXSkgPT4gUHJvbWlzZTxOYW1lZFRlbnNvck1hcD4ge1xuICByZXR1cm4gYXN5bmMgKFxuICAgIG1hbmlmZXN0OiBXZWlnaHRzTWFuaWZlc3RDb25maWcsIGZpbGVQYXRoUHJlZml4ID0gJycsXG4gICAgd2VpZ2h0TmFtZXM/OiBzdHJpbmdbXSk6IFByb21pc2U8TmFtZWRUZW5zb3JNYXA+ID0+IHtcbiAgICAvLyBDb2xsZWN0IGFsbCB0aGUgZ3JvdXBzLCB3ZWlnaHRzLCBhbmQgdGhlaXIgcmVsYXRpdmUgb2Zmc2V0cyB0byBiZVxuICAgIC8vIGZldGNoZWQuXG4gICAgY29uc3QgZ3JvdXBJbmRpY2VzVG9GZXRjaE1hcCA9IG1hbmlmZXN0Lm1hcCgoKSA9PiBmYWxzZSk7XG4gICAgY29uc3QgZ3JvdXBXZWlnaHRzVG9GZXRjaDoge1xuICAgICAgW2dyb3VwOiBudW1iZXJdOiBBcnJheTx7XG4gICAgICAgIG1hbmlmZXN0RW50cnk6IFdlaWdodHNNYW5pZmVzdEVudHJ5OyBncm91cE9mZnNldDogbnVtYmVyO1xuICAgICAgICBzaXplQnl0ZXM6IG51bWJlcjtcbiAgICAgIH0+XG4gICAgfSA9IHt9O1xuICAgIGNvbnN0IHdlaWdodHNGb3VuZCA9XG4gICAgICB3ZWlnaHROYW1lcyAhPSBudWxsID8gd2VpZ2h0TmFtZXMubWFwKCgpID0+IGZhbHNlKSA6IFtdO1xuICAgIGNvbnN0IGFsbE1hbmlmZXN0V2VpZ2h0TmFtZXM6IHN0cmluZ1tdID0gW107XG4gICAgbWFuaWZlc3QuZm9yRWFjaCgobWFuaWZlc3RHcm91cENvbmZpZywgZ3JvdXBJbmRleCkgPT4ge1xuICAgICAgbGV0IGdyb3VwT2Zmc2V0ID0gMDtcbiAgICAgIG1hbmlmZXN0R3JvdXBDb25maWcud2VpZ2h0cy5mb3JFYWNoKHdlaWdodHNFbnRyeSA9PiB7XG4gICAgICAgIGNvbnN0IHJhd0R0eXBlID0gKCdxdWFudGl6YXRpb24nIGluIHdlaWdodHNFbnRyeSkgP1xuICAgICAgICAgIHdlaWdodHNFbnRyeS5xdWFudGl6YXRpb24uZHR5cGUgOlxuICAgICAgICAgIHdlaWdodHNFbnRyeS5kdHlwZTtcblxuICAgICAgICBjb25zdCB3ZWlnaHRzQnl0ZXMgPSBEVFlQRV9WQUxVRV9TSVpFX01BUFtyYXdEdHlwZV0gKlxuICAgICAgICAgIHV0aWwuc2l6ZUZyb21TaGFwZSh3ZWlnaHRzRW50cnkuc2hhcGUpO1xuXG4gICAgICAgIGNvbnN0IGVucXVldWVXZWlnaHRzRm9yRmV0Y2hpbmdGbiA9ICgpID0+IHtcbiAgICAgICAgICBncm91cEluZGljZXNUb0ZldGNoTWFwW2dyb3VwSW5kZXhdID0gdHJ1ZTtcbiAgICAgICAgICBpZiAoZ3JvdXBXZWlnaHRzVG9GZXRjaFtncm91cEluZGV4XSA9PSBudWxsKSB7XG4gICAgICAgICAgICBncm91cFdlaWdodHNUb0ZldGNoW2dyb3VwSW5kZXhdID0gW107XG4gICAgICAgICAgfVxuXG4gICAgICAgICAgZ3JvdXBXZWlnaHRzVG9GZXRjaFtncm91cEluZGV4XS5wdXNoKHtcbiAgICAgICAgICAgIG1hbmlmZXN0RW50cnk6IHdlaWdodHNFbnRyeSxcbiAgICAgICAgICAgIGdyb3VwT2Zmc2V0LFxuICAgICAgICAgICAgc2l6ZUJ5dGVzOiB3ZWlnaHRzQnl0ZXNcbiAgICAgICAgICB9KTtcbiAgICAgICAgfTtcblxuICAgICAgICBpZiAod2VpZ2h0TmFtZXMgIT0gbnVsbCkge1xuICAgICAgICAgIHdlaWdodE5hbWVzLmZvckVhY2goKHdlaWdodE5hbWUsIHdlaWdodEluZGV4KSA9PiB7XG4gICAgICAgICAgICBpZiAod2VpZ2h0TmFtZSA9PT0gd2VpZ2h0c0VudHJ5Lm5hbWUpIHtcbiAgICAgICAgICAgICAgZW5xdWV1ZVdlaWdodHNGb3JGZXRjaGluZ0ZuKCk7XG4gICAgICAgICAgICAgIHdlaWdodHNGb3VuZFt3ZWlnaHRJbmRleF0gPSB0cnVlO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH0pO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGVucXVldWVXZWlnaHRzRm9yRmV0Y2hpbmdGbigpO1xuICAgICAgICB9XG5cbiAgICAgICAgYWxsTWFuaWZlc3RXZWlnaHROYW1lcy5wdXNoKHdlaWdodHNFbnRyeS5uYW1lKTtcbiAgICAgICAgZ3JvdXBPZmZzZXQgKz0gd2VpZ2h0c0J5dGVzO1xuICAgICAgfSk7XG4gICAgfSk7XG5cbiAgICBpZiAoIXdlaWdodHNGb3VuZC5ldmVyeShmb3VuZCA9PiBmb3VuZCkpIHtcbiAgICAgIGNvbnN0IHdlaWdodHNOb3RGb3VuZCA9IHdlaWdodE5hbWVzLmZpbHRlcigoXywgaSkgPT4gIXdlaWdodHNGb3VuZFtpXSk7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgIGBDb3VsZCBub3QgZmluZCB3ZWlnaHRzIGluIG1hbmlmZXN0IHdpdGggbmFtZXM6IGAgK1xuICAgICAgICBgJHt3ZWlnaHRzTm90Rm91bmQuam9pbignLCAnKX0uIFxcbmAgK1xuICAgICAgICBgTWFuaWZlc3QgSlNPTiBoYXMgd2VpZ2h0cyB3aXRoIG5hbWVzOiBgICtcbiAgICAgICAgYCR7YWxsTWFuaWZlc3RXZWlnaHROYW1lcy5qb2luKCcsICcpfS5gKTtcbiAgICB9XG5cbiAgICAvLyBDb252ZXJ0IHRoZSBvbmUtaG90IGJvb2xlYW4gZ3JvdXBJZCA9PiBzaG91bGRGZXRjaCBtYXAgdG8gYSBsaXN0IG9mIGdyb3VwXG4gICAgLy8gSURzLlxuICAgIGNvbnN0IGdyb3VwSW5kaWNlc1RvRmV0Y2ggPVxuICAgICAgZ3JvdXBJbmRpY2VzVG9GZXRjaE1hcC5yZWR1Y2UoKGFjY3VtdWxhdG9yLCBzaG91bGRGZXRjaCwgaSkgPT4ge1xuICAgICAgICBpZiAoc2hvdWxkRmV0Y2gpIHtcbiAgICAgICAgICBhY2N1bXVsYXRvci5wdXNoKGkpO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBhY2N1bXVsYXRvcjtcbiAgICAgIH0sIFtdKTtcblxuICAgIGNvbnN0IGZldGNoVXJsczogc3RyaW5nW10gPSBbXTtcbiAgICBncm91cEluZGljZXNUb0ZldGNoLmZvckVhY2goaSA9PiB7XG4gICAgICBtYW5pZmVzdFtpXS5wYXRocy5mb3JFYWNoKGZpbGVwYXRoID0+IHtcbiAgICAgICAgY29uc3QgZmV0Y2hVcmwgPSBmaWxlUGF0aFByZWZpeCArXG4gICAgICAgICAgKCFmaWxlUGF0aFByZWZpeC5lbmRzV2l0aCgnLycpID8gJy8nIDogJycpICsgZmlsZXBhdGg7XG4gICAgICAgIGZldGNoVXJscy5wdXNoKGZldGNoVXJsKTtcbiAgICAgIH0pO1xuICAgIH0pO1xuICAgIGNvbnN0IGJ1ZmZlcnMgPSBhd2FpdCBmZXRjaFdlaWdodHNGdW5jdGlvbihmZXRjaFVybHMpO1xuXG4gICAgY29uc3Qgd2VpZ2h0c1RlbnNvck1hcDogTmFtZWRUZW5zb3JNYXAgPSB7fTtcbiAgICBsZXQgYnVmZmVySW5kZXhPZmZzZXQgPSAwO1xuICAgIGdyb3VwSW5kaWNlc1RvRmV0Y2guZm9yRWFjaChpID0+IHtcbiAgICAgIGNvbnN0IG51bUJ1ZmZlcnMgPSBtYW5pZmVzdFtpXS5wYXRocy5sZW5ndGg7XG5cbiAgICAgIGNvbnN0IHdlaWdodHNCdWZmZXIgPSBuZXcgQ29tcG9zaXRlQXJyYXlCdWZmZXIoXG4gICAgICAgIGJ1ZmZlcnMuc2xpY2UoYnVmZmVySW5kZXhPZmZzZXQsIGJ1ZmZlckluZGV4T2Zmc2V0ICsgbnVtQnVmZmVycykpO1xuXG4gICAgICBjb25zdCB3ZWlnaHRzRW50cmllcyA9IGdyb3VwV2VpZ2h0c1RvRmV0Y2hbaV07XG5cbiAgICAgIHdlaWdodHNFbnRyaWVzLmZvckVhY2god2VpZ2h0c0VudHJ5ID0+IHtcbiAgICAgICAgY29uc3QgYnl0ZUJ1ZmZlciA9IHdlaWdodHNCdWZmZXIuc2xpY2UoXG4gICAgICAgICAgd2VpZ2h0c0VudHJ5Lmdyb3VwT2Zmc2V0LFxuICAgICAgICAgIHdlaWdodHNFbnRyeS5ncm91cE9mZnNldCArIHdlaWdodHNFbnRyeS5zaXplQnl0ZXMpO1xuICAgICAgICBjb25zdCBuYW1lVG9UZW5zb3JNYXAgPVxuICAgICAgICAgIGRlY29kZVdlaWdodHMoYnl0ZUJ1ZmZlciwgW3dlaWdodHNFbnRyeS5tYW5pZmVzdEVudHJ5XSk7XG4gICAgICAgIGZvciAoY29uc3QgbmFtZSBpbiBuYW1lVG9UZW5zb3JNYXApIHtcbiAgICAgICAgICB3ZWlnaHRzVGVuc29yTWFwW25hbWVdID0gbmFtZVRvVGVuc29yTWFwW25hbWVdO1xuICAgICAgICB9XG4gICAgICB9KTtcblxuICAgICAgYnVmZmVySW5kZXhPZmZzZXQgKz0gbnVtQnVmZmVycztcbiAgICB9KTtcblxuICAgIHJldHVybiB3ZWlnaHRzVGVuc29yTWFwO1xuICB9O1xufVxuIl19