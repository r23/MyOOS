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
/**
 * IOHandlers related to files, such as browser-triggered file downloads,
 * user-selected files in browser.
 */
import '../flags';
import { env } from '../environment';
import { basename, getModelArtifactsForJSON, getModelArtifactsInfoForJSON, getModelJSONForModelArtifacts } from './io_utils';
import { IORouterRegistry } from './router_registry';
import { CompositeArrayBuffer } from './composite_array_buffer';
const DEFAULT_FILE_NAME_PREFIX = 'model';
const DEFAULT_JSON_EXTENSION_NAME = '.json';
const DEFAULT_WEIGHT_DATA_EXTENSION_NAME = '.weights.bin';
function defer(f) {
    return new Promise(resolve => setTimeout(resolve)).then(f);
}
export class BrowserDownloads {
    constructor(fileNamePrefix) {
        if (!env().getBool('IS_BROWSER')) {
            // TODO(cais): Provide info on what IOHandlers are available under the
            //   current environment.
            throw new Error('browserDownloads() cannot proceed because the current environment ' +
                'is not a browser.');
        }
        if (fileNamePrefix.startsWith(BrowserDownloads.URL_SCHEME)) {
            fileNamePrefix = fileNamePrefix.slice(BrowserDownloads.URL_SCHEME.length);
        }
        if (fileNamePrefix == null || fileNamePrefix.length === 0) {
            fileNamePrefix = DEFAULT_FILE_NAME_PREFIX;
        }
        this.modelJsonFileName = fileNamePrefix + DEFAULT_JSON_EXTENSION_NAME;
        this.weightDataFileName =
            fileNamePrefix + DEFAULT_WEIGHT_DATA_EXTENSION_NAME;
    }
    async save(modelArtifacts) {
        if (typeof (document) === 'undefined') {
            throw new Error('Browser downloads are not supported in ' +
                'this environment since `document` is not present');
        }
        // TODO(mattsoulanille): Support saving models over 2GB that exceed
        // Chrome's ArrayBuffer size limit.
        const weightBuffer = CompositeArrayBuffer.join(modelArtifacts.weightData);
        const weightsURL = window.URL.createObjectURL(new Blob([weightBuffer], { type: 'application/octet-stream' }));
        if (modelArtifacts.modelTopology instanceof ArrayBuffer) {
            throw new Error('BrowserDownloads.save() does not support saving model topology ' +
                'in binary formats yet.');
        }
        else {
            const weightsManifest = [{
                    paths: ['./' + this.weightDataFileName],
                    weights: modelArtifacts.weightSpecs
                }];
            const modelJSON = getModelJSONForModelArtifacts(modelArtifacts, weightsManifest);
            const modelJsonURL = window.URL.createObjectURL(new Blob([JSON.stringify(modelJSON)], { type: 'application/json' }));
            // If anchor elements are not provided, create them without attaching them
            // to parents, so that the downloaded file names can be controlled.
            const jsonAnchor = this.modelJsonAnchor == null ?
                document.createElement('a') :
                this.modelJsonAnchor;
            jsonAnchor.download = this.modelJsonFileName;
            jsonAnchor.href = modelJsonURL;
            // Trigger downloads by evoking a click event on the download anchors.
            // When multiple downloads are started synchronously, Firefox will only
            // save the last one.
            await defer(() => jsonAnchor.dispatchEvent(new MouseEvent('click')));
            if (modelArtifacts.weightData != null) {
                const weightDataAnchor = this.weightDataAnchor == null ?
                    document.createElement('a') :
                    this.weightDataAnchor;
                weightDataAnchor.download = this.weightDataFileName;
                weightDataAnchor.href = weightsURL;
                await defer(() => weightDataAnchor.dispatchEvent(new MouseEvent('click')));
            }
            return { modelArtifactsInfo: getModelArtifactsInfoForJSON(modelArtifacts) };
        }
    }
}
BrowserDownloads.URL_SCHEME = 'downloads://';
class BrowserFiles {
    constructor(files) {
        if (files == null || files.length < 1) {
            throw new Error(`When calling browserFiles, at least 1 file is required, ` +
                `but received ${files}`);
        }
        this.jsonFile = files[0];
        this.weightsFiles = files.slice(1);
    }
    async load() {
        return new Promise((resolve, reject) => {
            const jsonReader = new FileReader();
            jsonReader.onload = (event) => {
                // tslint:disable-next-line:no-any
                const modelJSON = JSON.parse(event.target.result);
                const modelTopology = modelJSON.modelTopology;
                if (modelTopology == null) {
                    reject(new Error(`modelTopology field is missing from file ${this.jsonFile.name}`));
                    return;
                }
                const weightsManifest = modelJSON.weightsManifest;
                if (weightsManifest == null) {
                    reject(new Error(`weightManifest field is missing from file ${this.jsonFile.name}`));
                    return;
                }
                if (this.weightsFiles.length === 0) {
                    resolve({ modelTopology });
                    return;
                }
                const modelArtifactsPromise = getModelArtifactsForJSON(modelJSON, (weightsManifest) => this.loadWeights(weightsManifest));
                resolve(modelArtifactsPromise);
            };
            jsonReader.onerror = error => reject(`Failed to read model topology and weights manifest JSON ` +
                `from file '${this.jsonFile.name}'. BrowserFiles supports loading ` +
                `Keras-style tf.Model artifacts only.`);
            jsonReader.readAsText(this.jsonFile);
        });
    }
    loadWeights(weightsManifest) {
        const weightSpecs = [];
        const paths = [];
        for (const entry of weightsManifest) {
            weightSpecs.push(...entry.weights);
            paths.push(...entry.paths);
        }
        const pathToFile = this.checkManifestAndWeightFiles(weightsManifest);
        const promises = paths.map(path => this.loadWeightsFile(path, pathToFile[path]));
        return Promise.all(promises).then(buffers => [weightSpecs, buffers]);
    }
    loadWeightsFile(path, file) {
        return new Promise((resolve, reject) => {
            const weightFileReader = new FileReader();
            weightFileReader.onload = (event) => {
                // tslint:disable-next-line:no-any
                const weightData = event.target.result;
                resolve(weightData);
            };
            weightFileReader.onerror = error => reject(`Failed to weights data from file of path '${path}'.`);
            weightFileReader.readAsArrayBuffer(file);
        });
    }
    /**
     * Check the compatibility between weights manifest and weight files.
     */
    checkManifestAndWeightFiles(manifest) {
        const basenames = [];
        const fileNames = this.weightsFiles.map(file => basename(file.name));
        const pathToFile = {};
        for (const group of manifest) {
            group.paths.forEach(path => {
                const pathBasename = basename(path);
                if (basenames.indexOf(pathBasename) !== -1) {
                    throw new Error(`Duplicate file basename found in weights manifest: ` +
                        `'${pathBasename}'`);
                }
                basenames.push(pathBasename);
                if (fileNames.indexOf(pathBasename) === -1) {
                    throw new Error(`Weight file with basename '${pathBasename}' is not provided.`);
                }
                else {
                    pathToFile[path] = this.weightsFiles[fileNames.indexOf(pathBasename)];
                }
            });
        }
        if (basenames.length !== this.weightsFiles.length) {
            throw new Error(`Mismatch in the number of files in weights manifest ` +
                `(${basenames.length}) and the number of weight files provided ` +
                `(${this.weightsFiles.length}).`);
        }
        return pathToFile;
    }
}
export const browserDownloadsRouter = (url) => {
    if (!env().getBool('IS_BROWSER')) {
        return null;
    }
    else {
        if (!Array.isArray(url) && url.startsWith(BrowserDownloads.URL_SCHEME)) {
            return browserDownloads(url.slice(BrowserDownloads.URL_SCHEME.length));
        }
        else {
            return null;
        }
    }
};
IORouterRegistry.registerSaveRouter(browserDownloadsRouter);
/**
 * Creates an IOHandler that triggers file downloads from the browser.
 *
 * The returned `IOHandler` instance can be used as model exporting methods such
 * as `tf.Model.save` and supports only saving.
 *
 * ```js
 * const model = tf.sequential();
 * model.add(tf.layers.dense(
 *     {units: 1, inputShape: [10], activation: 'sigmoid'}));
 * const saveResult = await model.save('downloads://mymodel');
 * // This will trigger downloading of two files:
 * //   'mymodel.json' and 'mymodel.weights.bin'.
 * console.log(saveResult);
 * ```
 *
 * @param fileNamePrefix Prefix name of the files to be downloaded. For use with
 *   `tf.Model`, `fileNamePrefix` should follow either of the following two
 *   formats:
 *   1. `null` or `undefined`, in which case the default file
 *      names will be used:
 *      - 'model.json' for the JSON file containing the model topology and
 *        weights manifest.
 *      - 'model.weights.bin' for the binary file containing the binary weight
 *        values.
 *   2. A single string or an Array of a single string, as the file name prefix.
 *      For example, if `'foo'` is provided, the downloaded JSON
 *      file and binary weights file will be named 'foo.json' and
 *      'foo.weights.bin', respectively.
 * @param config Additional configuration for triggering downloads.
 * @returns An instance of `BrowserDownloads` `IOHandler`.
 *
 * @doc {
 *   heading: 'Models',
 *   subheading: 'Loading',
 *   namespace: 'io',
 *   ignoreCI: true
 * }
 */
export function browserDownloads(fileNamePrefix = 'model') {
    return new BrowserDownloads(fileNamePrefix);
}
/**
 * Creates an IOHandler that loads model artifacts from user-selected files.
 *
 * This method can be used for loading from files such as user-selected files
 * in the browser.
 * When used in conjunction with `tf.loadLayersModel`, an instance of
 * `tf.LayersModel` (Keras-style) can be constructed from the loaded artifacts.
 *
 * ```js
 * // Note: This code snippet won't run properly without the actual file input
 * //   elements in the HTML DOM.
 *
 * // Suppose there are two HTML file input (`<input type="file" ...>`)
 * // elements.
 * const uploadJSONInput = document.getElementById('upload-json');
 * const uploadWeightsInput = document.getElementById('upload-weights');
 * const model = await tf.loadLayersModel(tf.io.browserFiles(
 *     [uploadJSONInput.files[0], uploadWeightsInput.files[0]]));
 * ```
 *
 * @param files `File`s to load from. Currently, this function supports only
 *   loading from files that contain Keras-style models (i.e., `tf.Model`s), for
 *   which an `Array` of `File`s is expected (in that order):
 *   - A JSON file containing the model topology and weight manifest.
 *   - Optionally, one or more binary files containing the binary weights.
 *     These files must have names that match the paths in the `weightsManifest`
 *     contained by the aforementioned JSON file, or errors will be thrown
 *     during loading. These weights files have the same format as the ones
 *     generated by `tensorflowjs_converter` that comes with the `tensorflowjs`
 *     Python PIP package. If no weights files are provided, only the model
 *     topology will be loaded from the JSON file above.
 * @returns An instance of `Files` `IOHandler`.
 *
 * @doc {
 *   heading: 'Models',
 *   subheading: 'Loading',
 *   namespace: 'io',
 *   ignoreCI: true
 * }
 */
export function browserFiles(files) {
    return new BrowserFiles(files);
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYnJvd3Nlcl9maWxlcy5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uL3RmanMtY29yZS9zcmMvaW8vYnJvd3Nlcl9maWxlcy50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7O0dBZUc7QUFFSDs7O0dBR0c7QUFFSCxPQUFPLFVBQVUsQ0FBQztBQUNsQixPQUFPLEVBQUMsR0FBRyxFQUFDLE1BQU0sZ0JBQWdCLENBQUM7QUFFbkMsT0FBTyxFQUFDLFFBQVEsRUFBRSx3QkFBd0IsRUFBRSw0QkFBNEIsRUFBRSw2QkFBNkIsRUFBQyxNQUFNLFlBQVksQ0FBQztBQUMzSCxPQUFPLEVBQVcsZ0JBQWdCLEVBQUMsTUFBTSxtQkFBbUIsQ0FBQztBQUU3RCxPQUFPLEVBQUMsb0JBQW9CLEVBQUMsTUFBTSwwQkFBMEIsQ0FBQztBQUU5RCxNQUFNLHdCQUF3QixHQUFHLE9BQU8sQ0FBQztBQUN6QyxNQUFNLDJCQUEyQixHQUFHLE9BQU8sQ0FBQztBQUM1QyxNQUFNLGtDQUFrQyxHQUFHLGNBQWMsQ0FBQztBQUUxRCxTQUFTLEtBQUssQ0FBSSxDQUFVO0lBQzFCLE9BQU8sSUFBSSxPQUFPLENBQUMsT0FBTyxDQUFDLEVBQUUsQ0FBQyxVQUFVLENBQUMsT0FBTyxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsQ0FBQyxDQUFDLENBQUM7QUFDN0QsQ0FBQztBQUVELE1BQU0sT0FBTyxnQkFBZ0I7SUFRM0IsWUFBWSxjQUF1QjtRQUNqQyxJQUFJLENBQUMsR0FBRyxFQUFFLENBQUMsT0FBTyxDQUFDLFlBQVksQ0FBQyxFQUFFO1lBQ2hDLHNFQUFzRTtZQUN0RSx5QkFBeUI7WUFDekIsTUFBTSxJQUFJLEtBQUssQ0FDWCxvRUFBb0U7Z0JBQ3BFLG1CQUFtQixDQUFDLENBQUM7U0FDMUI7UUFFRCxJQUFJLGNBQWMsQ0FBQyxVQUFVLENBQUMsZ0JBQWdCLENBQUMsVUFBVSxDQUFDLEVBQUU7WUFDMUQsY0FBYyxH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QUFDLEVBQUU7UUFDaEMsT0FBTyxJQUFJLENBQUM7S0FDYjtTQUFNO1FBQ0wsSUFBSSxDQUFDLEtBQUssQ0FBQyxPQUFPLENBQUMsR0FBRyxDQUFDLElBQUksR0FBRyxDQUFDLFVBQVUsQ0FBQyxnQkFBZ0IsQ0FBQyxVQUFVLENBQUMsRUFBRTtZQUN0RSxPQUFPLGdCQUFnQixDQUFDLEdBQUcsQ0FBQyxLQUFLLENBQUMsZ0JBQWdCLENBQUMsVUFBVSxDQUFDLE1BQU0sQ0FBQyxDQUFDLENBQUM7U0FDeEU7YUFBTTtZQUNMLE9BQU8sSUFBSSxDQUFDO1NBQ2I7S0FDRjtBQUNILENBQUMsQ0FBQztBQUNGLGdCQUFnQixDQUFDLGtCQUFrQixDQUFDLHNCQUFzQixDQUFDLENBQUM7QUFFNUQ7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0dBc0NHO0FBQ0gsTUFBTSxVQUFVLGdCQUFnQixDQUFDLGNBQWMsR0FBRyxPQUFPO0lBQ3ZELE9BQU8sSUFBSSxnQkFBZ0IsQ0FBQyxjQUFjLENBQUMsQ0FBQztBQUM5QyxDQUFDO0FBRUQ7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztHQXVDRztBQUNILE1BQU0sVUFBVSxZQUFZLENBQUMsS0FBYTtJQUN4QyxPQUFPLElBQUksWUFBWSxDQUFDLEtBQUssQ0FBQyxDQUFDO0FBQ2pDLENBQUMiLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEBsaWNlbnNlXG4gKiBDb3B5cmlnaHQgMjAxOCBHb29nbGUgTExDLiBBbGwgUmlnaHRzIFJlc2VydmVkLlxuICogTGljZW5zZWQgdW5kZXIgdGhlIEFwYWNoZSBMaWNlbnNlLCBWZXJzaW9uIDIuMCAodGhlIFwiTGljZW5zZVwiKTtcbiAqIHlvdSBtYXkgbm90IHVzZSB0aGlzIGZpbGUgZXhjZXB0IGluIGNvbXBsaWFuY2Ugd2l0aCB0aGUgTGljZW5zZS5cbiAqIFlvdSBtYXkgb2J0YWluIGEgY29weSBvZiB0aGUgTGljZW5zZSBhdFxuICpcbiAqIGh0dHA6Ly93d3cuYXBhY2hlLm9yZy9saWNlbnNlcy9MSUNFTlNFLTIuMFxuICpcbiAqIFVubGVzcyByZXF1aXJlZCBieSBhcHBsaWNhYmxlIGxhdyBvciBhZ3JlZWQgdG8gaW4gd3JpdGluZywgc29mdHdhcmVcbiAqIGRpc3RyaWJ1dGVkIHVuZGVyIHRoZSBMaWNlbnNlIGlzIGRpc3RyaWJ1dGVkIG9uIGFuIFwiQVMgSVNcIiBCQVNJUyxcbiAqIFdJVEhPVVQgV0FSUkFOVElFUyBPUiBDT05ESVRJT05TIE9GIEFOWSBLSU5ELCBlaXRoZXIgZXhwcmVzcyBvciBpbXBsaWVkLlxuICogU2VlIHRoZSBMaWNlbnNlIGZvciB0aGUgc3BlY2lmaWMgbGFuZ3VhZ2UgZ292ZXJuaW5nIHBlcm1pc3Npb25zIGFuZFxuICogbGltaXRhdGlvbnMgdW5kZXIgdGhlIExpY2Vuc2UuXG4gKiA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PVxuICovXG5cbi8qKlxuICogSU9IYW5kbGVycyByZWxhdGVkIHRvIGZpbGVzLCBzdWNoIGFzIGJyb3dzZXItdHJpZ2dlcmVkIGZpbGUgZG93bmxvYWRzLFxuICogdXNlci1zZWxlY3RlZCBmaWxlcyBpbiBicm93c2VyLlxuICovXG5cbmltcG9ydCAnLi4vZmxhZ3MnO1xuaW1wb3J0IHtlbnZ9IGZyb20gJy4uL2Vudmlyb25tZW50JztcblxuaW1wb3J0IHtiYXNlbmFtZSwgZ2V0TW9kZWxBcnRpZmFjdHNGb3JKU09OLCBnZXRNb2RlbEFydGlmYWN0c0luZm9Gb3JKU09OLCBnZXRNb2RlbEpTT05Gb3JNb2RlbEFydGlmYWN0c30gZnJvbSAnLi9pb191dGlscyc7XG5pbXBvcnQge0lPUm91dGVyLCBJT1JvdXRlclJlZ2lzdHJ5fSBmcm9tICcuL3JvdXRlcl9yZWdpc3RyeSc7XG5pbXBvcnQge0lPSGFuZGxlciwgTW9kZWxBcnRpZmFjdHMsIE1vZGVsSlNPTiwgU2F2ZVJlc3VsdCwgV2VpZ2h0RGF0YSwgV2VpZ2h0c01hbmlmZXN0Q29uZmlnLCBXZWlnaHRzTWFuaWZlc3RFbnRyeX0gZnJvbSAnLi90eXBlcyc7XG5pbXBvcnQge0NvbXBvc2l0ZUFycmF5QnVmZmVyfSBmcm9tICcuL2NvbXBvc2l0ZV9hcnJheV9idWZmZXInO1xuXG5jb25zdCBERUZBVUxUX0ZJTEVfTkFNRV9QUkVGSVggPSAnbW9kZWwnO1xuY29uc3QgREVGQVVMVF9KU09OX0VYVEVOU0lPTl9OQU1FID0gJy5qc29uJztcbmNvbnN0IERFRkFVTFRfV0VJR0hUX0RBVEFfRVhURU5TSU9OX05BTUUgPSAnLndlaWdodHMuYmluJztcblxuZnVuY3Rpb24gZGVmZXI8VD4oZjogKCkgPT4gVCk6IFByb21pc2U8VD4ge1xuICByZXR1cm4gbmV3IFByb21pc2UocmVzb2x2ZSA9PiBzZXRUaW1lb3V0KHJlc29sdmUpKS50aGVuKGYpO1xufVxuXG5leHBvcnQgY2xhc3MgQnJvd3NlckRvd25sb2FkcyBpbXBsZW1lbnRzIElPSGFuZGxlciB7XG4gIHByaXZhdGUgcmVhZG9ubHkgbW9kZWxKc29uRmlsZU5hbWU6IHN0cmluZztcbiAgcHJpdmF0ZSByZWFkb25seSB3ZWlnaHREYXRhRmlsZU5hbWU6IHN0cmluZztcbiAgcHJpdmF0ZSByZWFkb25seSBtb2RlbEpzb25BbmNob3I6IEhUTUxBbmNob3JFbGVtZW50O1xuICBwcml2YXRlIHJlYWRvbmx5IHdlaWdodERhdGFBbmNob3I6IEhUTUxBbmNob3JFbGVtZW50O1xuXG4gIHN0YXRpYyByZWFkb25seSBVUkxfU0NIRU1FID0gJ2Rvd25sb2FkczovLyc7XG5cbiAgY29uc3RydWN0b3IoZmlsZU5hbWVQcmVmaXg/OiBzdHJpbmcpIHtcbiAgICBpZiAoIWVudigpLmdldEJvb2woJ0lTX0JST1dTRVInKSkge1xuICAgICAgLy8gVE9ETyhjYWlzKTogUHJvdmlkZSBpbmZvIG9uIHdoYXQgSU9IYW5kbGVycyBhcmUgYXZhaWxhYmxlIHVuZGVyIHRoZVxuICAgICAgLy8gICBjdXJyZW50IGVudmlyb25tZW50LlxuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgICdicm93c2VyRG93bmxvYWRzKCkgY2Fubm90IHByb2NlZWQgYmVjYXVzZSB0aGUgY3VycmVudCBlbnZpcm9ubWVudCAnICtcbiAgICAgICAgICAnaXMgbm90IGEgYnJvd3Nlci4nKTtcbiAgICB9XG5cbiAgICBpZiAoZmlsZU5hbWVQcmVmaXguc3RhcnRzV2l0aChCcm93c2VyRG93bmxvYWRzLlVSTF9TQ0hFTUUpKSB7XG4gICAgICBmaWxlTmFtZVByZWZpeCA9IGZpbGVOYW1lUHJlZml4LnNsaWNlKEJyb3dzZXJEb3dubG9hZHMuVVJMX1NDSEVNRS5sZW5ndGgpO1xuICAgIH1cbiAgICBpZiAoZmlsZU5hbWVQcmVmaXggPT0gbnVsbCB8fCBmaWxlTmFtZVByZWZpeC5sZW5ndGggPT09IDApIHtcbiAgICAgIGZpbGVOYW1lUHJlZml4ID0gREVGQVVMVF9GSUxFX05BTUVfUFJFRklYO1xuICAgIH1cblxuICAgIHRoaXMubW9kZWxKc29uRmlsZU5hbWUgPSBmaWxlTmFtZVByZWZpeCArIERFRkFVTFRfSlNPTl9FWFRFTlNJT05fTkFNRTtcbiAgICB0aGlzLndlaWdodERhdGFGaWxlTmFtZSA9XG4gICAgICAgIGZpbGVOYW1lUHJlZml4ICsgREVGQVVMVF9XRUlHSFRfREFUQV9FWFRFTlNJT05fTkFNRTtcbiAgfVxuXG4gIGFzeW5jIHNhdmUobW9kZWxBcnRpZmFjdHM6IE1vZGVsQXJ0aWZhY3RzKTogUHJvbWlzZTxTYXZlUmVzdWx0PiB7XG4gICAgaWYgKHR5cGVvZiAoZG9jdW1lbnQpID09PSAndW5kZWZpbmVkJykge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgICdCcm93c2VyIGRvd25sb2FkcyBhcmUgbm90IHN1cHBvcnRlZCBpbiAnICtcbiAgICAgICAgICAndGhpcyBlbnZpcm9ubWVudCBzaW5jZSBgZG9jdW1lbnRgIGlzIG5vdCBwcmVzZW50Jyk7XG4gICAgfVxuXG4gICAgLy8gVE9ETyhtYXR0c291bGFuaWxsZSk6IFN1cHBvcnQgc2F2aW5nIG1vZGVscyBvdmVyIDJHQiB0aGF0IGV4Y2VlZFxuICAgIC8vIENocm9tZSdzIEFycmF5QnVmZmVyIHNpemUgbGltaXQuXG4gICAgY29uc3Qgd2VpZ2h0QnVmZmVyID0gQ29tcG9zaXRlQXJyYXlCdWZmZXIuam9pbihtb2RlbEFydGlmYWN0cy53ZWlnaHREYXRhKTtcblxuICAgIGNvbnN0IHdlaWdodHNVUkwgPSB3aW5kb3cuVVJMLmNyZWF0ZU9iamVjdFVSTChuZXcgQmxvYihcbiAgICAgICAgW3dlaWdodEJ1ZmZlcl0sIHt0eXBlOiAnYXBwbGljYXRpb24vb2N0ZXQtc3RyZWFtJ30pKTtcblxuICAgIGlmIChtb2RlbEFydGlmYWN0cy5tb2RlbFRvcG9sb2d5IGluc3RhbmNlb2YgQXJyYXlCdWZmZXIpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICAnQnJvd3NlckRvd25sb2Fkcy5zYXZlKCkgZG9lcyBub3Qgc3VwcG9ydCBzYXZpbmcgbW9kZWwgdG9wb2xvZ3kgJyArXG4gICAgICAgICAgJ2luIGJpbmFyeSBmb3JtYXRzIHlldC4nKTtcbiAgICB9IGVsc2Uge1xuICAgICAgY29uc3Qgd2VpZ2h0c01hbmlmZXN0OiBXZWlnaHRzTWFuaWZlc3RDb25maWcgPSBbe1xuICAgICAgICBwYXRoczogWycuLycgKyB0aGlzLndlaWdodERhdGFGaWxlTmFtZV0sXG4gICAgICAgIHdlaWdodHM6IG1vZGVsQXJ0aWZhY3RzLndlaWdodFNwZWNzXG4gICAgICB9XTtcbiAgICAgIGNvbnN0IG1vZGVsSlNPTjogTW9kZWxKU09OID1cbiAgICAgICAgICBnZXRNb2RlbEpTT05Gb3JNb2RlbEFydGlmYWN0cyhtb2RlbEFydGlmYWN0cywgd2VpZ2h0c01hbmlmZXN0KTtcblxuICAgICAgY29uc3QgbW9kZWxKc29uVVJMID0gd2luZG93LlVSTC5jcmVhdGVPYmplY3RVUkwoXG4gICAgICAgICAgbmV3IEJsb2IoW0pTT04uc3RyaW5naWZ5KG1vZGVsSlNPTildLCB7dHlwZTogJ2FwcGxpY2F0aW9uL2pzb24nfSkpO1xuXG4gICAgICAvLyBJZiBhbmNob3IgZWxlbWVudHMgYXJlIG5vdCBwcm92aWRlZCwgY3JlYXRlIHRoZW0gd2l0aG91dCBhdHRhY2hpbmcgdGhlbVxuICAgICAgLy8gdG8gcGFyZW50cywgc28gdGhhdCB0aGUgZG93bmxvYWRlZCBmaWxlIG5hbWVzIGNhbiBiZSBjb250cm9sbGVkLlxuICAgICAgY29uc3QganNvbkFuY2hvciA9IHRoaXMubW9kZWxKc29uQW5jaG9yID09IG51bGwgP1xuICAgICAgICAgIGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2EnKSA6XG4gICAgICAgICAgdGhpcy5tb2RlbEpzb25BbmNob3I7XG4gICAgICBqc29uQW5jaG9yLmRvd25sb2FkID0gdGhpcy5tb2RlbEpzb25GaWxlTmFtZTtcbiAgICAgIGpzb25BbmNob3IuaHJlZiA9IG1vZGVsSnNvblVSTDtcbiAgICAgIC8vIFRyaWdnZXIgZG93bmxvYWRzIGJ5IGV2b2tpbmcgYSBjbGljayBldmVudCBvbiB0aGUgZG93bmxvYWQgYW5jaG9ycy5cbiAgICAgIC8vIFdoZW4gbXVsdGlwbGUgZG93bmxvYWRzIGFyZSBzdGFydGVkIHN5bmNocm9ub3VzbHksIEZpcmVmb3ggd2lsbCBvbmx5XG4gICAgICAvLyBzYXZlIHRoZSBsYXN0IG9uZS5cbiAgICAgIGF3YWl0IGRlZmVyKCgpID0+IGpzb25BbmNob3IuZGlzcGF0Y2hFdmVudChuZXcgTW91c2VFdmVudCgnY2xpY2snKSkpO1xuXG4gICAgICBpZiAobW9kZWxBcnRpZmFjdHMud2VpZ2h0RGF0YSAhPSBudWxsKSB7XG4gICAgICAgIGNvbnN0IHdlaWdodERhdGFBbmNob3IgPSB0aGlzLndlaWdodERhdGFBbmNob3IgPT0gbnVsbCA/XG4gICAgICAgICAgICBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdhJykgOlxuICAgICAgICAgICAgdGhpcy53ZWlnaHREYXRhQW5jaG9yO1xuICAgICAgICB3ZWlnaHREYXRhQW5jaG9yLmRvd25sb2FkID0gdGhpcy53ZWlnaHREYXRhRmlsZU5hbWU7XG4gICAgICAgIHdlaWdodERhdGFBbmNob3IuaHJlZiA9IHdlaWdodHNVUkw7XG4gICAgICAgIGF3YWl0IGRlZmVyKFxuICAgICAgICAgICAgKCkgPT4gd2VpZ2h0RGF0YUFuY2hvci5kaXNwYXRjaEV2ZW50KG5ldyBNb3VzZUV2ZW50KCdjbGljaycpKSk7XG4gICAgICB9XG5cbiAgICAgIHJldHVybiB7bW9kZWxBcnRpZmFjdHNJbmZvOiBnZXRNb2RlbEFydGlmYWN0c0luZm9Gb3JKU09OKG1vZGVsQXJ0aWZhY3RzKX07XG4gICAgfVxuICB9XG59XG5cbmNsYXNzIEJyb3dzZXJGaWxlcyBpbXBsZW1lbnRzIElPSGFuZGxlciB7XG4gIHByaXZhdGUgcmVhZG9ubHkganNvbkZpbGU6IEZpbGU7XG4gIHByaXZhdGUgcmVhZG9ubHkgd2VpZ2h0c0ZpbGVzOiBGaWxlW107XG5cbiAgY29uc3RydWN0b3IoZmlsZXM6IEZpbGVbXSkge1xuICAgIGlmIChmaWxlcyA9PSBudWxsIHx8IGZpbGVzLmxlbmd0aCA8IDEpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICBgV2hlbiBjYWxsaW5nIGJyb3dzZXJGaWxlcywgYXQgbGVhc3QgMSBmaWxlIGlzIHJlcXVpcmVkLCBgICtcbiAgICAgICAgICBgYnV0IHJlY2VpdmVkICR7ZmlsZXN9YCk7XG4gICAgfVxuICAgIHRoaXMuanNvbkZpbGUgPSBmaWxlc1swXTtcbiAgICB0aGlzLndlaWdodHNGaWxlcyA9IGZpbGVzLnNsaWNlKDEpO1xuICB9XG5cbiAgYXN5bmMgbG9hZCgpOiBQcm9taXNlPE1vZGVsQXJ0aWZhY3RzPiB7XG4gICAgcmV0dXJuIG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcbiAgICAgIGNvbnN0IGpzb25SZWFkZXIgPSBuZXcgRmlsZVJlYWRlcigpO1xuICAgICAganNvblJlYWRlci5vbmxvYWQgPSAoZXZlbnQ6IEV2ZW50KSA9PiB7XG4gICAgICAgIC8vIHRzbGludDpkaXNhYmxlLW5leHQtbGluZTpuby1hbnlcbiAgICAgICAgY29uc3QgbW9kZWxKU09OID0gSlNPTi5wYXJzZSgoZXZlbnQudGFyZ2V0IGFzIGFueSkucmVzdWx0KSBhcyBNb2RlbEpTT047XG5cbiAgICAgICAgY29uc3QgbW9kZWxUb3BvbG9neSA9IG1vZGVsSlNPTi5tb2RlbFRvcG9sb2d5O1xuICAgICAgICBpZiAobW9kZWxUb3BvbG9neSA9PSBudWxsKSB7XG4gICAgICAgICAgcmVqZWN0KG5ldyBFcnJvcihgbW9kZWxUb3BvbG9neSBmaWVsZCBpcyBtaXNzaW5nIGZyb20gZmlsZSAke1xuICAgICAgICAgICAgICB0aGlzLmpzb25GaWxlLm5hbWV9YCkpO1xuICAgICAgICAgIHJldHVybjtcbiAgICAgICAgfVxuXG4gICAgICAgIGNvbnN0IHdlaWdodHNNYW5pZmVzdCA9IG1vZGVsSlNPTi53ZWlnaHRzTWFuaWZlc3Q7XG4gICAgICAgIGlmICh3ZWlnaHRzTWFuaWZlc3QgPT0gbnVsbCkge1xuICAgICAgICAgIHJlamVjdChuZXcgRXJyb3IoYHdlaWdodE1hbmlmZXN0IGZpZWxkIGlzIG1pc3NpbmcgZnJvbSBmaWxlICR7XG4gICAgICAgICAgICAgIHRoaXMuanNvbkZpbGUubmFtZX1gKSk7XG4gICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKHRoaXMud2VpZ2h0c0ZpbGVzLmxlbmd0aCA9PT0gMCkge1xuICAgICAgICAgIHJlc29sdmUoe21vZGVsVG9wb2xvZ3l9KTtcbiAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICBjb25zdCBtb2RlbEFydGlmYWN0c1Byb21pc2UgPSBnZXRNb2RlbEFydGlmYWN0c0ZvckpTT04oXG4gICAgICAgICAgICBtb2RlbEpTT04sICh3ZWlnaHRzTWFuaWZlc3QpID0+IHRoaXMubG9hZFdlaWdodHMod2VpZ2h0c01hbmlmZXN0KSk7XG4gICAgICAgIHJlc29sdmUobW9kZWxBcnRpZmFjdHNQcm9taXNlKTtcbiAgICAgIH07XG5cbiAgICAgIGpzb25SZWFkZXIub25lcnJvciA9IGVycm9yID0+IHJlamVjdChcbiAgICAgICAgICBgRmFpbGVkIHRvIHJlYWQgbW9kZWwgdG9wb2xvZ3kgYW5kIHdlaWdodHMgbWFuaWZlc3QgSlNPTiBgICtcbiAgICAgICAgICBgZnJvbSBmaWxlICcke3RoaXMuanNvbkZpbGUubmFtZX0nLiBCcm93c2VyRmlsZXMgc3VwcG9ydHMgbG9hZGluZyBgICtcbiAgICAgICAgICBgS2VyYXMtc3R5bGUgdGYuTW9kZWwgYXJ0aWZhY3RzIG9ubHkuYCk7XG4gICAgICBqc29uUmVhZGVyLnJlYWRBc1RleHQodGhpcy5qc29uRmlsZSk7XG4gICAgfSk7XG4gIH1cblxuICBwcml2YXRlIGxvYWRXZWlnaHRzKHdlaWdodHNNYW5pZmVzdDogV2VpZ2h0c01hbmlmZXN0Q29uZmlnKTogUHJvbWlzZTxbXG4gICAgLyogd2VpZ2h0U3BlY3MgKi8gV2VpZ2h0c01hbmlmZXN0RW50cnlbXSwgV2VpZ2h0RGF0YSxcbiAgXT4ge1xuICAgIGNvbnN0IHdlaWdodFNwZWNzOiBXZWlnaHRzTWFuaWZlc3RFbnRyeVtdID0gW107XG4gICAgY29uc3QgcGF0aHM6IHN0cmluZ1tdID0gW107XG4gICAgZm9yIChjb25zdCBlbnRyeSBvZiB3ZWlnaHRzTWFuaWZlc3QpIHtcbiAgICAgIHdlaWdodFNwZWNzLnB1c2goLi4uZW50cnkud2VpZ2h0cyk7XG4gICAgICBwYXRocy5wdXNoKC4uLmVudHJ5LnBhdGhzKTtcbiAgICB9XG5cbiAgICBjb25zdCBwYXRoVG9GaWxlOiB7W3BhdGg6IHN0cmluZ106IEZpbGV9ID1cbiAgICAgICAgdGhpcy5jaGVja01hbmlmZXN0QW5kV2VpZ2h0RmlsZXMod2VpZ2h0c01hbmlmZXN0KTtcblxuICAgIGNvbnN0IHByb21pc2VzOiBBcnJheTxQcm9taXNlPEFycmF5QnVmZmVyPj4gPVxuICAgICAgICBwYXRocy5tYXAocGF0aCA9PiB0aGlzLmxvYWRXZWlnaHRzRmlsZShwYXRoLCBwYXRoVG9GaWxlW3BhdGhdKSk7XG5cbiAgICByZXR1cm4gUHJvbWlzZS5hbGwocHJvbWlzZXMpLnRoZW4oXG4gICAgICAgIGJ1ZmZlcnMgPT4gW3dlaWdodFNwZWNzLCBidWZmZXJzXSk7XG4gIH1cblxuICBwcml2YXRlIGxvYWRXZWlnaHRzRmlsZShwYXRoOiBzdHJpbmcsIGZpbGU6IEZpbGUpOiBQcm9taXNlPEFycmF5QnVmZmVyPiB7XG4gICAgcmV0dXJuIG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpID0+IHtcbiAgICAgIGNvbnN0IHdlaWdodEZpbGVSZWFkZXIgPSBuZXcgRmlsZVJlYWRlcigpO1xuICAgICAgd2VpZ2h0RmlsZVJlYWRlci5vbmxvYWQgPSAoZXZlbnQ6IEV2ZW50KSA9PiB7XG4gICAgICAgIC8vIHRzbGludDpkaXNhYmxlLW5leHQtbGluZTpuby1hbnlcbiAgICAgICAgY29uc3Qgd2VpZ2h0RGF0YSA9IChldmVudC50YXJnZXQgYXMgYW55KS5yZXN1bHQgYXMgQXJyYXlCdWZmZXI7XG4gICAgICAgIHJlc29sdmUod2VpZ2h0RGF0YSk7XG4gICAgICB9O1xuICAgICAgd2VpZ2h0RmlsZVJlYWRlci5vbmVycm9yID0gZXJyb3IgPT5cbiAgICAgICAgICByZWplY3QoYEZhaWxlZCB0byB3ZWlnaHRzIGRhdGEgZnJvbSBmaWxlIG9mIHBhdGggJyR7cGF0aH0nLmApO1xuICAgICAgd2VpZ2h0RmlsZVJlYWRlci5yZWFkQXNBcnJheUJ1ZmZlcihmaWxlKTtcbiAgICB9KTtcbiAgfVxuXG4gIC8qKlxuICAgKiBDaGVjayB0aGUgY29tcGF0aWJpbGl0eSBiZXR3ZWVuIHdlaWdodHMgbWFuaWZlc3QgYW5kIHdlaWdodCBmaWxlcy5cbiAgICovXG4gIHByaXZhdGUgY2hlY2tNYW5pZmVzdEFuZFdlaWdodEZpbGVzKG1hbmlmZXN0OiBXZWlnaHRzTWFuaWZlc3RDb25maWcpOlxuICAgICAge1twYXRoOiBzdHJpbmddOiBGaWxlfSB7XG4gICAgY29uc3QgYmFzZW5hbWVzOiBzdHJpbmdbXSA9IFtdO1xuICAgIGNvbnN0IGZpbGVOYW1lcyA9IHRoaXMud2VpZ2h0c0ZpbGVzLm1hcChmaWxlID0+IGJhc2VuYW1lKGZpbGUubmFtZSkpO1xuICAgIGNvbnN0IHBhdGhUb0ZpbGU6IHtbcGF0aDogc3RyaW5nXTogRmlsZX0gPSB7fTtcbiAgICBmb3IgKGNvbnN0IGdyb3VwIG9mIG1hbmlmZXN0KSB7XG4gICAgICBncm91cC5wYXRocy5mb3JFYWNoKHBhdGggPT4ge1xuICAgICAgICBjb25zdCBwYXRoQmFzZW5hbWUgPSBiYXNlbmFtZShwYXRoKTtcbiAgICAgICAgaWYgKGJhc2VuYW1lcy5pbmRleE9mKHBhdGhCYXNlbmFtZSkgIT09IC0xKSB7XG4gICAgICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgICAgICBgRHVwbGljYXRlIGZpbGUgYmFzZW5hbWUgZm91bmQgaW4gd2VpZ2h0cyBtYW5pZmVzdDogYCArXG4gICAgICAgICAgICAgIGAnJHtwYXRoQmFzZW5hbWV9J2ApO1xuICAgICAgICB9XG4gICAgICAgIGJhc2VuYW1lcy5wdXNoKHBhdGhCYXNlbmFtZSk7XG4gICAgICAgIGlmIChmaWxlTmFtZXMuaW5kZXhPZihwYXRoQmFzZW5hbWUpID09PSAtMSkge1xuICAgICAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICAgICAgYFdlaWdodCBmaWxlIHdpdGggYmFzZW5hbWUgJyR7cGF0aEJhc2VuYW1lfScgaXMgbm90IHByb3ZpZGVkLmApO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIHBhdGhUb0ZpbGVbcGF0aF0gPSB0aGlzLndlaWdodHNGaWxlc1tmaWxlTmFtZXMuaW5kZXhPZihwYXRoQmFzZW5hbWUpXTtcbiAgICAgICAgfVxuICAgICAgfSk7XG4gICAgfVxuXG4gICAgaWYgKGJhc2VuYW1lcy5sZW5ndGggIT09IHRoaXMud2VpZ2h0c0ZpbGVzLmxlbmd0aCkge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgIGBNaXNtYXRjaCBpbiB0aGUgbnVtYmVyIG9mIGZpbGVzIGluIHdlaWdodHMgbWFuaWZlc3QgYCArXG4gICAgICAgICAgYCgke2Jhc2VuYW1lcy5sZW5ndGh9KSBhbmQgdGhlIG51bWJlciBvZiB3ZWlnaHQgZmlsZXMgcHJvdmlkZWQgYCArXG4gICAgICAgICAgYCgke3RoaXMud2VpZ2h0c0ZpbGVzLmxlbmd0aH0pLmApO1xuICAgIH1cbiAgICByZXR1cm4gcGF0aFRvRmlsZTtcbiAgfVxufVxuXG5leHBvcnQgY29uc3QgYnJvd3NlckRvd25sb2Fkc1JvdXRlcjogSU9Sb3V0ZXIgPSAodXJsOiBzdHJpbmd8c3RyaW5nW10pID0+IHtcbiAgaWYgKCFlbnYoKS5nZXRCb29sKCdJU19CUk9XU0VSJykpIHtcbiAgICByZXR1cm4gbnVsbDtcbiAgfSBlbHNlIHtcbiAgICBpZiAoIUFycmF5LmlzQXJyYXkodXJsKSAmJiB1cmwuc3RhcnRzV2l0aChCcm93c2VyRG93bmxvYWRzLlVSTF9TQ0hFTUUpKSB7XG4gICAgICByZXR1cm4gYnJvd3NlckRvd25sb2Fkcyh1cmwuc2xpY2UoQnJvd3NlckRvd25sb2Fkcy5VUkxfU0NIRU1FLmxlbmd0aCkpO1xuICAgIH0gZWxzZSB7XG4gICAgICByZXR1cm4gbnVsbDtcbiAgICB9XG4gIH1cbn07XG5JT1JvdXRlclJlZ2lzdHJ5LnJlZ2lzdGVyU2F2ZVJvdXRlcihicm93c2VyRG93bmxvYWRzUm91dGVyKTtcblxuLyoqXG4gKiBDcmVhdGVzIGFuIElPSGFuZGxlciB0aGF0IHRyaWdnZXJzIGZpbGUgZG93bmxvYWRzIGZyb20gdGhlIGJyb3dzZXIuXG4gKlxuICogVGhlIHJldHVybmVkIGBJT0hhbmRsZXJgIGluc3RhbmNlIGNhbiBiZSB1c2VkIGFzIG1vZGVsIGV4cG9ydGluZyBtZXRob2RzIHN1Y2hcbiAqIGFzIGB0Zi5Nb2RlbC5zYXZlYCBhbmQgc3VwcG9ydHMgb25seSBzYXZpbmcuXG4gKlxuICogYGBganNcbiAqIGNvbnN0IG1vZGVsID0gdGYuc2VxdWVudGlhbCgpO1xuICogbW9kZWwuYWRkKHRmLmxheWVycy5kZW5zZShcbiAqICAgICB7dW5pdHM6IDEsIGlucHV0U2hhcGU6IFsxMF0sIGFjdGl2YXRpb246ICdzaWdtb2lkJ30pKTtcbiAqIGNvbnN0IHNhdmVSZXN1bHQgPSBhd2FpdCBtb2RlbC5zYXZlKCdkb3dubG9hZHM6Ly9teW1vZGVsJyk7XG4gKiAvLyBUaGlzIHdpbGwgdHJpZ2dlciBkb3dubG9hZGluZyBvZiB0d28gZmlsZXM6XG4gKiAvLyAgICdteW1vZGVsLmpzb24nIGFuZCAnbXltb2RlbC53ZWlnaHRzLmJpbicuXG4gKiBjb25zb2xlLmxvZyhzYXZlUmVzdWx0KTtcbiAqIGBgYFxuICpcbiAqIEBwYXJhbSBmaWxlTmFtZVByZWZpeCBQcmVmaXggbmFtZSBvZiB0aGUgZmlsZXMgdG8gYmUgZG93bmxvYWRlZC4gRm9yIHVzZSB3aXRoXG4gKiAgIGB0Zi5Nb2RlbGAsIGBmaWxlTmFtZVByZWZpeGAgc2hvdWxkIGZvbGxvdyBlaXRoZXIgb2YgdGhlIGZvbGxvd2luZyB0d29cbiAqICAgZm9ybWF0czpcbiAqICAgMS4gYG51bGxgIG9yIGB1bmRlZmluZWRgLCBpbiB3aGljaCBjYXNlIHRoZSBkZWZhdWx0IGZpbGVcbiAqICAgICAgbmFtZXMgd2lsbCBiZSB1c2VkOlxuICogICAgICAtICdtb2RlbC5qc29uJyBmb3IgdGhlIEpTT04gZmlsZSBjb250YWluaW5nIHRoZSBtb2RlbCB0b3BvbG9neSBhbmRcbiAqICAgICAgICB3ZWlnaHRzIG1hbmlmZXN0LlxuICogICAgICAtICdtb2RlbC53ZWlnaHRzLmJpbicgZm9yIHRoZSBiaW5hcnkgZmlsZSBjb250YWluaW5nIHRoZSBiaW5hcnkgd2VpZ2h0XG4gKiAgICAgICAgdmFsdWVzLlxuICogICAyLiBBIHNpbmdsZSBzdHJpbmcgb3IgYW4gQXJyYXkgb2YgYSBzaW5nbGUgc3RyaW5nLCBhcyB0aGUgZmlsZSBuYW1lIHByZWZpeC5cbiAqICAgICAgRm9yIGV4YW1wbGUsIGlmIGAnZm9vJ2AgaXMgcHJvdmlkZWQsIHRoZSBkb3dubG9hZGVkIEpTT05cbiAqICAgICAgZmlsZSBhbmQgYmluYXJ5IHdlaWdodHMgZmlsZSB3aWxsIGJlIG5hbWVkICdmb28uanNvbicgYW5kXG4gKiAgICAgICdmb28ud2VpZ2h0cy5iaW4nLCByZXNwZWN0aXZlbHkuXG4gKiBAcGFyYW0gY29uZmlnIEFkZGl0aW9uYWwgY29uZmlndXJhdGlvbiBmb3IgdHJpZ2dlcmluZyBkb3dubG9hZHMuXG4gKiBAcmV0dXJucyBBbiBpbnN0YW5jZSBvZiBgQnJvd3NlckRvd25sb2Fkc2AgYElPSGFuZGxlcmAuXG4gKlxuICogQGRvYyB7XG4gKiAgIGhlYWRpbmc6ICdNb2RlbHMnLFxuICogICBzdWJoZWFkaW5nOiAnTG9hZGluZycsXG4gKiAgIG5hbWVzcGFjZTogJ2lvJyxcbiAqICAgaWdub3JlQ0k6IHRydWVcbiAqIH1cbiAqL1xuZXhwb3J0IGZ1bmN0aW9uIGJyb3dzZXJEb3dubG9hZHMoZmlsZU5hbWVQcmVmaXggPSAnbW9kZWwnKTogSU9IYW5kbGVyIHtcbiAgcmV0dXJuIG5ldyBCcm93c2VyRG93bmxvYWRzKGZpbGVOYW1lUHJlZml4KTtcbn1cblxuLyoqXG4gKiBDcmVhdGVzIGFuIElPSGFuZGxlciB0aGF0IGxvYWRzIG1vZGVsIGFydGlmYWN0cyBmcm9tIHVzZXItc2VsZWN0ZWQgZmlsZXMuXG4gKlxuICogVGhpcyBtZXRob2QgY2FuIGJlIHVzZWQgZm9yIGxvYWRpbmcgZnJvbSBmaWxlcyBzdWNoIGFzIHVzZXItc2VsZWN0ZWQgZmlsZXNcbiAqIGluIHRoZSBicm93c2VyLlxuICogV2hlbiB1c2VkIGluIGNvbmp1bmN0aW9uIHdpdGggYHRmLmxvYWRMYXllcnNNb2RlbGAsIGFuIGluc3RhbmNlIG9mXG4gKiBgdGYuTGF5ZXJzTW9kZWxgIChLZXJhcy1zdHlsZSkgY2FuIGJlIGNvbnN0cnVjdGVkIGZyb20gdGhlIGxvYWRlZCBhcnRpZmFjdHMuXG4gKlxuICogYGBganNcbiAqIC8vIE5vdGU6IFRoaXMgY29kZSBzbmlwcGV0IHdvbid0IHJ1biBwcm9wZXJseSB3aXRob3V0IHRoZSBhY3R1YWwgZmlsZSBpbnB1dFxuICogLy8gICBlbGVtZW50cyBpbiB0aGUgSFRNTCBET00uXG4gKlxuICogLy8gU3VwcG9zZSB0aGVyZSBhcmUgdHdvIEhUTUwgZmlsZSBpbnB1dCAoYDxpbnB1dCB0eXBlPVwiZmlsZVwiIC4uLj5gKVxuICogLy8gZWxlbWVudHMuXG4gKiBjb25zdCB1cGxvYWRKU09OSW5wdXQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgndXBsb2FkLWpzb24nKTtcbiAqIGNvbnN0IHVwbG9hZFdlaWdodHNJbnB1dCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCd1cGxvYWQtd2VpZ2h0cycpO1xuICogY29uc3QgbW9kZWwgPSBhd2FpdCB0Zi5sb2FkTGF5ZXJzTW9kZWwodGYuaW8uYnJvd3NlckZpbGVzKFxuICogICAgIFt1cGxvYWRKU09OSW5wdXQuZmlsZXNbMF0sIHVwbG9hZFdlaWdodHNJbnB1dC5maWxlc1swXV0pKTtcbiAqIGBgYFxuICpcbiAqIEBwYXJhbSBmaWxlcyBgRmlsZWBzIHRvIGxvYWQgZnJvbS4gQ3VycmVudGx5LCB0aGlzIGZ1bmN0aW9uIHN1cHBvcnRzIG9ubHlcbiAqICAgbG9hZGluZyBmcm9tIGZpbGVzIHRoYXQgY29udGFpbiBLZXJhcy1zdHlsZSBtb2RlbHMgKGkuZS4sIGB0Zi5Nb2RlbGBzKSwgZm9yXG4gKiAgIHdoaWNoIGFuIGBBcnJheWAgb2YgYEZpbGVgcyBpcyBleHBlY3RlZCAoaW4gdGhhdCBvcmRlcik6XG4gKiAgIC0gQSBKU09OIGZpbGUgY29udGFpbmluZyB0aGUgbW9kZWwgdG9wb2xvZ3kgYW5kIHdlaWdodCBtYW5pZmVzdC5cbiAqICAgLSBPcHRpb25hbGx5LCBvbmUgb3IgbW9yZSBiaW5hcnkgZmlsZXMgY29udGFpbmluZyB0aGUgYmluYXJ5IHdlaWdodHMuXG4gKiAgICAgVGhlc2UgZmlsZXMgbXVzdCBoYXZlIG5hbWVzIHRoYXQgbWF0Y2ggdGhlIHBhdGhzIGluIHRoZSBgd2VpZ2h0c01hbmlmZXN0YFxuICogICAgIGNvbnRhaW5lZCBieSB0aGUgYWZvcmVtZW50aW9uZWQgSlNPTiBmaWxlLCBvciBlcnJvcnMgd2lsbCBiZSB0aHJvd25cbiAqICAgICBkdXJpbmcgbG9hZGluZy4gVGhlc2Ugd2VpZ2h0cyBmaWxlcyBoYXZlIHRoZSBzYW1lIGZvcm1hdCBhcyB0aGUgb25lc1xuICogICAgIGdlbmVyYXRlZCBieSBgdGVuc29yZmxvd2pzX2NvbnZlcnRlcmAgdGhhdCBjb21lcyB3aXRoIHRoZSBgdGVuc29yZmxvd2pzYFxuICogICAgIFB5dGhvbiBQSVAgcGFja2FnZS4gSWYgbm8gd2VpZ2h0cyBmaWxlcyBhcmUgcHJvdmlkZWQsIG9ubHkgdGhlIG1vZGVsXG4gKiAgICAgdG9wb2xvZ3kgd2lsbCBiZSBsb2FkZWQgZnJvbSB0aGUgSlNPTiBmaWxlIGFib3ZlLlxuICogQHJldHVybnMgQW4gaW5zdGFuY2Ugb2YgYEZpbGVzYCBgSU9IYW5kbGVyYC5cbiAqXG4gKiBAZG9jIHtcbiAqICAgaGVhZGluZzogJ01vZGVscycsXG4gKiAgIHN1YmhlYWRpbmc6ICdMb2FkaW5nJyxcbiAqICAgbmFtZXNwYWNlOiAnaW8nLFxuICogICBpZ25vcmVDSTogdHJ1ZVxuICogfVxuICovXG5leHBvcnQgZnVuY3Rpb24gYnJvd3NlckZpbGVzKGZpbGVzOiBGaWxlW10pOiBJT0hhbmRsZXIge1xuICByZXR1cm4gbmV3IEJyb3dzZXJGaWxlcyhmaWxlcyk7XG59XG4iXX0=