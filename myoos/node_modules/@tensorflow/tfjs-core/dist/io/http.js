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
 * IOHandler implementations based on HTTP requests in the web browser.
 *
 * Uses [`fetch`](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API).
 */
import { env } from '../environment';
import { assert } from '../util';
import { getModelArtifactsForJSON, getModelArtifactsInfoForJSON, getModelJSONForModelArtifacts, getWeightSpecs } from './io_utils';
import { CompositeArrayBuffer } from './composite_array_buffer';
import { IORouterRegistry } from './router_registry';
import { loadWeightsAsArrayBuffer } from './weights_loader';
const OCTET_STREAM_MIME_TYPE = 'application/octet-stream';
const JSON_TYPE = 'application/json';
export class HTTPRequest {
    constructor(path, loadOptions) {
        this.DEFAULT_METHOD = 'POST';
        if (loadOptions == null) {
            loadOptions = {};
        }
        this.weightPathPrefix = loadOptions.weightPathPrefix;
        this.onProgress = loadOptions.onProgress;
        this.weightUrlConverter = loadOptions.weightUrlConverter;
        if (loadOptions.fetchFunc != null) {
            assert(typeof loadOptions.fetchFunc === 'function', () => 'Must pass a function that matches the signature of ' +
                '`fetch` (see ' +
                'https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API)');
            this.fetch = loadOptions.fetchFunc;
        }
        else {
            this.fetch = env().platform.fetch;
        }
        assert(path != null && path.length > 0, () => 'URL path for http must not be null, undefined or ' +
            'empty.');
        if (Array.isArray(path)) {
            assert(path.length === 2, () => 'URL paths for http must have a length of 2, ' +
                `(actual length is ${path.length}).`);
        }
        this.path = path;
        if (loadOptions.requestInit != null &&
            loadOptions.requestInit.body != null) {
            throw new Error('requestInit is expected to have no pre-existing body, but has one.');
        }
        this.requestInit = loadOptions.requestInit || {};
    }
    async save(modelArtifacts) {
        if (modelArtifacts.modelTopology instanceof ArrayBuffer) {
            throw new Error('BrowserHTTPRequest.save() does not support saving model topology ' +
                'in binary formats yet.');
        }
        const init = Object.assign({ method: this.DEFAULT_METHOD }, this.requestInit);
        init.body = new FormData();
        const weightsManifest = [{
                paths: ['./model.weights.bin'],
                weights: modelArtifacts.weightSpecs,
            }];
        const modelTopologyAndWeightManifest = getModelJSONForModelArtifacts(modelArtifacts, weightsManifest);
        init.body.append('model.json', new Blob([JSON.stringify(modelTopologyAndWeightManifest)], { type: JSON_TYPE }), 'model.json');
        if (modelArtifacts.weightData != null) {
            // TODO(mattsoulanille): Support saving models over 2GB that exceed
            // Chrome's ArrayBuffer size limit.
            const weightBuffer = CompositeArrayBuffer.join(modelArtifacts.weightData);
            init.body.append('model.weights.bin', new Blob([weightBuffer], { type: OCTET_STREAM_MIME_TYPE }), 'model.weights.bin');
        }
        const response = await this.fetch(this.path, init);
        if (response.ok) {
            return {
                modelArtifactsInfo: getModelArtifactsInfoForJSON(modelArtifacts),
                responses: [response],
            };
        }
        else {
            throw new Error(`BrowserHTTPRequest.save() failed due to HTTP response status ` +
                `${response.status}.`);
        }
    }
    /**
     * Load model artifacts via HTTP request(s).
     *
     * See the documentation to `tf.io.http` for details on the saved
     * artifacts.
     *
     * @returns The loaded model artifacts (if loading succeeds).
     */
    async load() {
        const modelConfigRequest = await this.fetch(this.path, this.requestInit);
        if (!modelConfigRequest.ok) {
            throw new Error(`Request to ${this.path} failed with status code ` +
                `${modelConfigRequest.status}. Please verify this URL points to ` +
                `the model JSON of the model to load.`);
        }
        let modelJSON;
        try {
            modelJSON = await modelConfigRequest.json();
        }
        catch (e) {
            let message = `Failed to parse model JSON of response from ${this.path}.`;
            // TODO(nsthorat): Remove this after some time when we're comfortable that
            // .pb files are mostly gone.
            if (this.path.endsWith('.pb')) {
                message += ' Your path contains a .pb file extension. ' +
                    'Support for .pb models have been removed in TensorFlow.js 1.0 ' +
                    'in favor of .json models. You can re-convert your Python ' +
                    'TensorFlow model using the TensorFlow.js 1.0 conversion scripts ' +
                    'or you can convert your.pb models with the \'pb2json\'' +
                    'NPM script in the tensorflow/tfjs-converter repository.';
            }
            else {
                message += ' Please make sure the server is serving valid ' +
                    'JSON for this request.';
            }
            throw new Error(message);
        }
        // We do not allow both modelTopology and weightsManifest to be missing.
        const modelTopology = modelJSON.modelTopology;
        const weightsManifest = modelJSON.weightsManifest;
        if (modelTopology == null && weightsManifest == null) {
            throw new Error(`The JSON from HTTP path ${this.path} contains neither model ` +
                `topology or manifest for weights.`);
        }
        return getModelArtifactsForJSON(modelJSON, (weightsManifest) => this.loadWeights(weightsManifest));
    }
    async loadWeights(weightsManifest) {
        const weightPath = Array.isArray(this.path) ? this.path[1] : this.path;
        const [prefix, suffix] = parseUrl(weightPath);
        const pathPrefix = this.weightPathPrefix || prefix;
        const weightSpecs = getWeightSpecs(weightsManifest);
        const fetchURLs = [];
        const urlPromises = [];
        for (const weightsGroup of weightsManifest) {
            for (const path of weightsGroup.paths) {
                if (this.weightUrlConverter != null) {
                    urlPromises.push(this.weightUrlConverter(path));
                }
                else {
                    fetchURLs.push(pathPrefix + path + suffix);
                }
            }
        }
        if (this.weightUrlConverter) {
            fetchURLs.push(...await Promise.all(urlPromises));
        }
        const buffers = await loadWeightsAsArrayBuffer(fetchURLs, {
            requestInit: this.requestInit,
            fetchFunc: this.fetch,
            onProgress: this.onProgress
        });
        return [weightSpecs, buffers];
    }
}
HTTPRequest.URL_SCHEME_REGEX = /^https?:\/\//;
/**
 * Extract the prefix and suffix of the url, where the prefix is the path before
 * the last file, and suffix is the search params after the last file.
 * ```
 * const url = 'http://tfhub.dev/model/1/tensorflowjs_model.pb?tfjs-format=file'
 * [prefix, suffix] = parseUrl(url)
 * // prefix = 'http://tfhub.dev/model/1/'
 * // suffix = '?tfjs-format=file'
 * ```
 * @param url the model url to be parsed.
 */
export function parseUrl(url) {
    const lastSlash = url.lastIndexOf('/');
    const lastSearchParam = url.lastIndexOf('?');
    const prefix = url.substring(0, lastSlash);
    const suffix = lastSearchParam > lastSlash ? url.substring(lastSearchParam) : '';
    return [prefix + '/', suffix];
}
export function isHTTPScheme(url) {
    return url.match(HTTPRequest.URL_SCHEME_REGEX) != null;
}
export const httpRouter = (url, loadOptions) => {
    if (typeof fetch === 'undefined' &&
        (loadOptions == null || loadOptions.fetchFunc == null)) {
        // `http` uses `fetch` or `node-fetch`, if one wants to use it in
        // an environment that is not the browser or node they have to setup a
        // global fetch polyfill.
        return null;
    }
    else {
        let isHTTP = true;
        if (Array.isArray(url)) {
            isHTTP = url.every(urlItem => isHTTPScheme(urlItem));
        }
        else {
            isHTTP = isHTTPScheme(url);
        }
        if (isHTTP) {
            return http(url, loadOptions);
        }
    }
    return null;
};
IORouterRegistry.registerSaveRouter(httpRouter);
IORouterRegistry.registerLoadRouter(httpRouter);
/**
 * Creates an IOHandler subtype that sends model artifacts to HTTP server.
 *
 * An HTTP request of the `multipart/form-data` mime type will be sent to the
 * `path` URL. The form data includes artifacts that represent the topology
 * and/or weights of the model. In the case of Keras-style `tf.Model`, two
 * blobs (files) exist in form-data:
 *   - A JSON file consisting of `modelTopology` and `weightsManifest`.
 *   - A binary weights file consisting of the concatenated weight values.
 * These files are in the same format as the one generated by
 * [tfjs_converter](https://js.tensorflow.org/tutorials/import-keras.html).
 *
 * The following code snippet exemplifies the client-side code that uses this
 * function:
 *
 * ```js
 * const model = tf.sequential();
 * model.add(
 *     tf.layers.dense({units: 1, inputShape: [100], activation: 'sigmoid'}));
 *
 * const saveResult = await model.save(tf.io.http(
 *     'http://model-server:5000/upload', {requestInit: {method: 'PUT'}}));
 * console.log(saveResult);
 * ```
 *
 * If the default `POST` method is to be used, without any custom parameters
 * such as headers, you can simply pass an HTTP or HTTPS URL to `model.save`:
 *
 * ```js
 * const saveResult = await model.save('http://model-server:5000/upload');
 * ```
 *
 * The following GitHub Gist
 * https://gist.github.com/dsmilkov/1b6046fd6132d7408d5257b0976f7864
 * implements a server based on [flask](https://github.com/pallets/flask) that
 * can receive the request. Upon receiving the model artifacts via the requst,
 * this particular server reconstitutes instances of [Keras
 * Models](https://keras.io/models/model/) in memory.
 *
 *
 * @param path A URL path to the model.
 *   Can be an absolute HTTP path (e.g.,
 *   'http://localhost:8000/model-upload)') or a relative path (e.g.,
 *   './model-upload').
 * @param requestInit Request configurations to be used when sending
 *    HTTP request to server using `fetch`. It can contain fields such as
 *    `method`, `credentials`, `headers`, `mode`, etc. See
 *    https://developer.mozilla.org/en-US/docs/Web/API/Request/Request
 *    for more information. `requestInit` must not have a body, because the
 * body will be set by TensorFlow.js. File blobs representing the model
 * topology (filename: 'model.json') and the weights of the model (filename:
 * 'model.weights.bin') will be appended to the body. If `requestInit` has a
 * `body`, an Error will be thrown.
 * @param loadOptions Optional configuration for the loading. It includes the
 *   following fields:
 *   - weightPathPrefix Optional, this specifies the path prefix for weight
 *     files, by default this is calculated from the path param.
 *   - fetchFunc Optional, custom `fetch` function. E.g., in Node.js,
 *     the `fetch` from node-fetch can be used here.
 *   - onProgress Optional, progress callback function, fired periodically
 *     before the load is completed.
 * @returns An instance of `IOHandler`.
 *
 * @doc {
 *   heading: 'Models',
 *   subheading: 'Loading',
 *   namespace: 'io',
 *   ignoreCI: true
 * }
 */
export function http(path, loadOptions) {
    return new HTTPRequest(path, loadOptions);
}
/**
 * Deprecated. Use `tf.io.http`.
 * @param path
 * @param loadOptions
 */
export function browserHTTPRequest(path, loadOptions) {
    return http(path, loadOptions);
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiaHR0cC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uL3RmanMtY29yZS9zcmMvaW8vaHR0cC50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7O0dBZUc7QUFFSDs7OztHQUlHO0FBRUgsT0FBTyxFQUFDLEdBQUcsRUFBQyxNQUFNLGdCQUFnQixDQUFDO0FBRW5DLE9BQU8sRUFBQyxNQUFNLEVBQUMsTUFBTSxTQUFTLENBQUM7QUFDL0IsT0FBTyxFQUFDLHdCQUF3QixFQUFFLDRCQUE0QixFQUFFLDZCQUE2QixFQUFFLGNBQWMsRUFBQyxNQUFNLFlBQVksQ0FBQztBQUNqSSxPQUFPLEVBQUMsb0JBQW9CLEVBQUMsTUFBTSwwQkFBMEIsQ0FBQztBQUM5RCxPQUFPLEVBQVcsZ0JBQWdCLEVBQUMsTUFBTSxtQkFBbUIsQ0FBQztBQUU3RCxPQUFPLEVBQUMsd0JBQXdCLEVBQUMsTUFBTSxrQkFBa0IsQ0FBQztBQUUxRCxNQUFNLHNCQUFzQixHQUFHLDBCQUEwQixDQUFDO0FBQzFELE1BQU0sU0FBUyxHQUFHLGtCQUFrQixDQUFDO0FBQ3JDLE1BQU0sT0FBTyxXQUFXO0lBY3RCLFlBQVksSUFBWSxFQUFFLFdBQXlCO1FBUDFDLG1CQUFjLEdBQUcsTUFBTSxDQUFDO1FBUS9CLElBQUksV0FBVyxJQUFJLElBQUksRUFBRTtZQUN2QixXQUFXLEdBQUcsRUFBRSxDQUFDO1NBQ2xCO1FBQ0QsSUFBSSxDQUFDLGdCQUFnQixHQUFHLFdBQVcsQ0FBQyxnQkFBZ0IsQ0FBQztRQUNyRCxJQUFJLENBQUMsVUFBVSxHQUFHLFdBQVcsQ0FBQyxVQUFVLENBQUM7UUFDekMsSUFBSSxDQUFDLGtCQUFrQixHQUFHLFdBQVcsQ0FBQyxrQkFBa0IsQ0FBQztRQUV6RCxJQUFJLFdBQVcsQ0FBQyxTQUFTLElBQUksSUFBSSxFQUFFO1lBQ2pDLE1BQU0sQ0FDRixPQUFPLFdBQVcsQ0FBQyxTQUFTLEtBQUssVUFBVSxFQUMz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LENBQUMsSUFBSSxFQUFFLFdBQVcsQ0FBQyxDQUFDO0FBQ2pDLENBQUMiLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEBsaWNlbnNlXG4gKiBDb3B5cmlnaHQgMjAxOCBHb29nbGUgTExDLiBBbGwgUmlnaHRzIFJlc2VydmVkLlxuICogTGljZW5zZWQgdW5kZXIgdGhlIEFwYWNoZSBMaWNlbnNlLCBWZXJzaW9uIDIuMCAodGhlIFwiTGljZW5zZVwiKTtcbiAqIHlvdSBtYXkgbm90IHVzZSB0aGlzIGZpbGUgZXhjZXB0IGluIGNvbXBsaWFuY2Ugd2l0aCB0aGUgTGljZW5zZS5cbiAqIFlvdSBtYXkgb2J0YWluIGEgY29weSBvZiB0aGUgTGljZW5zZSBhdFxuICpcbiAqIGh0dHA6Ly93d3cuYXBhY2hlLm9yZy9saWNlbnNlcy9MSUNFTlNFLTIuMFxuICpcbiAqIFVubGVzcyByZXF1aXJlZCBieSBhcHBsaWNhYmxlIGxhdyBvciBhZ3JlZWQgdG8gaW4gd3JpdGluZywgc29mdHdhcmVcbiAqIGRpc3RyaWJ1dGVkIHVuZGVyIHRoZSBMaWNlbnNlIGlzIGRpc3RyaWJ1dGVkIG9uIGFuIFwiQVMgSVNcIiBCQVNJUyxcbiAqIFdJVEhPVVQgV0FSUkFOVElFUyBPUiBDT05ESVRJT05TIE9GIEFOWSBLSU5ELCBlaXRoZXIgZXhwcmVzcyBvciBpbXBsaWVkLlxuICogU2VlIHRoZSBMaWNlbnNlIGZvciB0aGUgc3BlY2lmaWMgbGFuZ3VhZ2UgZ292ZXJuaW5nIHBlcm1pc3Npb25zIGFuZFxuICogbGltaXRhdGlvbnMgdW5kZXIgdGhlIExpY2Vuc2UuXG4gKiA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PVxuICovXG5cbi8qKlxuICogSU9IYW5kbGVyIGltcGxlbWVudGF0aW9ucyBiYXNlZCBvbiBIVFRQIHJlcXVlc3RzIGluIHRoZSB3ZWIgYnJvd3Nlci5cbiAqXG4gKiBVc2VzIFtgZmV0Y2hgXShodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9BUEkvRmV0Y2hfQVBJKS5cbiAqL1xuXG5pbXBvcnQge2Vudn0gZnJvbSAnLi4vZW52aXJvbm1lbnQnO1xuXG5pbXBvcnQge2Fzc2VydH0gZnJvbSAnLi4vdXRpbCc7XG5pbXBvcnQge2dldE1vZGVsQXJ0aWZhY3RzRm9ySlNPTiwgZ2V0TW9kZWxBcnRpZmFjdHNJbmZvRm9ySlNPTiwgZ2V0TW9kZWxKU09ORm9yTW9kZWxBcnRpZmFjdHMsIGdldFdlaWdodFNwZWNzfSBmcm9tICcuL2lvX3V0aWxzJztcbmltcG9ydCB7Q29tcG9zaXRlQXJyYXlCdWZmZXJ9IGZyb20gJy4vY29tcG9zaXRlX2FycmF5X2J1ZmZlcic7XG5pbXBvcnQge0lPUm91dGVyLCBJT1JvdXRlclJlZ2lzdHJ5fSBmcm9tICcuL3JvdXRlcl9yZWdpc3RyeSc7XG5pbXBvcnQge0lPSGFuZGxlciwgTG9hZE9wdGlvbnMsIE1vZGVsQXJ0aWZhY3RzLCBNb2RlbEpTT04sIE9uUHJvZ3Jlc3NDYWxsYmFjaywgU2F2ZVJlc3VsdCwgV2VpZ2h0RGF0YSwgV2VpZ2h0c01hbmlmZXN0Q29uZmlnLCBXZWlnaHRzTWFuaWZlc3RFbnRyeX0gZnJvbSAnLi90eXBlcyc7XG5pbXBvcnQge2xvYWRXZWlnaHRzQXNBcnJheUJ1ZmZlcn0gZnJvbSAnLi93ZWlnaHRzX2xvYWRlcic7XG5cbmNvbnN0IE9DVEVUX1NUUkVBTV9NSU1FX1RZUEUgPSAnYXBwbGljYXRpb24vb2N0ZXQtc3RyZWFtJztcbmNvbnN0IEpTT05fVFlQRSA9ICdhcHBsaWNhdGlvbi9qc29uJztcbmV4cG9ydCBjbGFzcyBIVFRQUmVxdWVzdCBpbXBsZW1lbnRzIElPSGFuZGxlciB7XG4gIHByb3RlY3RlZCByZWFkb25seSBwYXRoOiBzdHJpbmc7XG4gIHByb3RlY3RlZCByZWFkb25seSByZXF1ZXN0SW5pdDogUmVxdWVzdEluaXQ7XG5cbiAgcHJpdmF0ZSByZWFkb25seSBmZXRjaDogRnVuY3Rpb247XG4gIHByaXZhdGUgcmVhZG9ubHkgd2VpZ2h0VXJsQ29udmVydGVyOiAod2VpZ2h0TmFtZTogc3RyaW5nKSA9PiBQcm9taXNlPHN0cmluZz47XG5cbiAgcmVhZG9ubHkgREVGQVVMVF9NRVRIT0QgPSAnUE9TVCc7XG5cbiAgc3RhdGljIHJlYWRvbmx5IFVSTF9TQ0hFTUVfUkVHRVggPSAvXmh0dHBzPzpcXC9cXC8vO1xuXG4gIHByaXZhdGUgcmVhZG9ubHkgd2VpZ2h0UGF0aFByZWZpeDogc3RyaW5nO1xuICBwcml2YXRlIHJlYWRvbmx5IG9uUHJvZ3Jlc3M6IE9uUHJvZ3Jlc3NDYWxsYmFjaztcblxuICBjb25zdHJ1Y3RvcihwYXRoOiBzdHJpbmcsIGxvYWRPcHRpb25zPzogTG9hZE9wdGlvbnMpIHtcbiAgICBpZiAobG9hZE9wdGlvbnMgPT0gbnVsbCkge1xuICAgICAgbG9hZE9wdGlvbnMgPSB7fTtcbiAgICB9XG4gICAgdGhpcy53ZWlnaHRQYXRoUHJlZml4ID0gbG9hZE9wdGlvbnMud2VpZ2h0UGF0aFByZWZpeDtcbiAgICB0aGlzLm9uUHJvZ3Jlc3MgPSBsb2FkT3B0aW9ucy5vblByb2dyZXNzO1xuICAgIHRoaXMud2VpZ2h0VXJsQ29udmVydGVyID0gbG9hZE9wdGlvbnMud2VpZ2h0VXJsQ29udmVydGVyO1xuXG4gICAgaWYgKGxvYWRPcHRpb25zLmZldGNoRnVuYyAhPSBudWxsKSB7XG4gICAgICBhc3NlcnQoXG4gICAgICAgICAgdHlwZW9mIGxvYWRPcHRpb25zLmZldGNoRnVuYyA9PT0gJ2Z1bmN0aW9uJyxcbiAgICAgICAgICAoKSA9PiAnTXVzdCBwYXNzIGEgZnVuY3Rpb24gdGhhdCBtYXRjaGVzIHRoZSBzaWduYXR1cmUgb2YgJyArXG4gICAgICAgICAgICAgICdgZmV0Y2hgIChzZWUgJyArXG4gICAgICAgICAgICAgICdodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9BUEkvRmV0Y2hfQVBJKScpO1xuICAgICAgdGhpcy5mZXRjaCA9IGxvYWRPcHRpb25zLmZldGNoRnVuYztcbiAgICB9IGVsc2Uge1xuICAgICAgdGhpcy5mZXRjaCA9IGVudigpLnBsYXRmb3JtLmZldGNoO1xuICAgIH1cblxuICAgIGFzc2VydChcbiAgICAgICAgcGF0aCAhPSBudWxsICYmIHBhdGgubGVuZ3RoID4gMCxcbiAgICAgICAgKCkgPT4gJ1VSTCBwYXRoIGZvciBodHRwIG11c3Qgbm90IGJlIG51bGwsIHVuZGVmaW5lZCBvciAnICtcbiAgICAgICAgICAgICdlbXB0eS4nKTtcblxuICAgIGlmIChBcnJheS5pc0FycmF5KHBhdGgpKSB7XG4gICAgICBhc3NlcnQoXG4gICAgICAgICAgcGF0aC5sZW5ndGggPT09IDIsXG4gICAgICAgICAgKCkgPT4gJ1VSTCBwYXRocyBmb3IgaHR0cCBtdXN0IGhhdmUgYSBsZW5ndGggb2YgMiwgJyArXG4gICAgICAgICAgICAgIGAoYWN0dWFsIGxlbmd0aCBpcyAke3BhdGgubGVuZ3RofSkuYCk7XG4gICAgfVxuICAgIHRoaXMucGF0aCA9IHBhdGg7XG5cbiAgICBpZiAobG9hZE9wdGlvbnMucmVxdWVzdEluaXQgIT0gbnVsbCAmJlxuICAgICAgICBsb2FkT3B0aW9ucy5yZXF1ZXN0SW5pdC5ib2R5ICE9IG51bGwpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICAncmVxdWVzdEluaXQgaXMgZXhwZWN0ZWQgdG8gaGF2ZSBubyBwcmUtZXhpc3RpbmcgYm9keSwgYnV0IGhhcyBvbmUuJyk7XG4gICAgfVxuICAgIHRoaXMucmVxdWVzdEluaXQgPSBsb2FkT3B0aW9ucy5yZXF1ZXN0SW5pdCB8fCB7fTtcbiAgfVxuXG4gIGFzeW5jIHNhdmUobW9kZWxBcnRpZmFjdHM6IE1vZGVsQXJ0aWZhY3RzKTogUHJvbWlzZTxTYXZlUmVzdWx0PiB7XG4gICAgaWYgKG1vZGVsQXJ0aWZhY3RzLm1vZGVsVG9wb2xvZ3kgaW5zdGFuY2VvZiBBcnJheUJ1ZmZlcikge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgICdCcm93c2VySFRUUFJlcXVlc3Quc2F2ZSgpIGRvZXMgbm90IHN1cHBvcnQgc2F2aW5nIG1vZGVsIHRvcG9sb2d5ICcgK1xuICAgICAgICAgICdpbiBiaW5hcnkgZm9ybWF0cyB5ZXQuJyk7XG4gICAgfVxuXG4gICAgY29uc3QgaW5pdCA9IE9iamVjdC5hc3NpZ24oe21ldGhvZDogdGhpcy5ERUZBVUxUX01FVEhPRH0sIHRoaXMucmVxdWVzdEluaXQpO1xuICAgIGluaXQuYm9keSA9IG5ldyBGb3JtRGF0YSgpO1xuXG4gICAgY29uc3Qgd2VpZ2h0c01hbmlmZXN0OiBXZWlnaHRzTWFuaWZlc3RDb25maWcgPSBbe1xuICAgICAgcGF0aHM6IFsnLi9tb2RlbC53ZWlnaHRzLmJpbiddLFxuICAgICAgd2VpZ2h0czogbW9kZWxBcnRpZmFjdHMud2VpZ2h0U3BlY3MsXG4gICAgfV07XG4gICAgY29uc3QgbW9kZWxUb3BvbG9neUFuZFdlaWdodE1hbmlmZXN0OiBNb2RlbEpTT04gPVxuICAgICAgICBnZXRNb2RlbEpTT05Gb3JNb2RlbEFydGlmYWN0cyhtb2RlbEFydGlmYWN0cywgd2VpZ2h0c01hbmlmZXN0KTtcblxuICAgIGluaXQuYm9keS5hcHBlbmQoXG4gICAgICAgICdtb2RlbC5qc29uJyxcbiAgICAgICAgbmV3IEJsb2IoXG4gICAgICAgICAgICBbSlNPTi5zdHJpbmdpZnkobW9kZWxUb3BvbG9neUFuZFdlaWdodE1hbmlmZXN0KV0sXG4gICAgICAgICAgICB7dHlwZTogSlNPTl9UWVBFfSksXG4gICAgICAgICdtb2RlbC5qc29uJyk7XG5cbiAgICBpZiAobW9kZWxBcnRpZmFjdHMud2VpZ2h0RGF0YSAhPSBudWxsKSB7XG4gICAgICAvLyBUT0RPKG1hdHRzb3VsYW5pbGxlKTogU3VwcG9ydCBzYXZpbmcgbW9kZWxzIG92ZXIgMkdCIHRoYXQgZXhjZWVkXG4gICAgICAvLyBDaHJvbWUncyBBcnJheUJ1ZmZlciBzaXplIGxpbWl0LlxuICAgICAgY29uc3Qgd2VpZ2h0QnVmZmVyID0gQ29tcG9zaXRlQXJyYXlCdWZmZXIuam9pbihtb2RlbEFydGlmYWN0cy53ZWlnaHREYXRhKTtcblxuICAgICAgaW5pdC5ib2R5LmFwcGVuZChcbiAgICAgICAgICAnbW9kZWwud2VpZ2h0cy5iaW4nLFxuICAgICAgICAgIG5ldyBCbG9iKFt3ZWlnaHRCdWZmZXJdLCB7dHlwZTogT0NURVRfU1RSRUFNX01JTUVfVFlQRX0pLFxuICAgICAgICAgICdtb2RlbC53ZWlnaHRzLmJpbicpO1xuICAgIH1cblxuICAgIGNvbnN0IHJlc3BvbnNlID0gYXdhaXQgdGhpcy5mZXRjaCh0aGlzLnBhdGgsIGluaXQpO1xuXG4gICAgaWYgKHJlc3BvbnNlLm9rKSB7XG4gICAgICByZXR1cm4ge1xuICAgICAgICBtb2RlbEFydGlmYWN0c0luZm86IGdldE1vZGVsQXJ0aWZhY3RzSW5mb0ZvckpTT04obW9kZWxBcnRpZmFjdHMpLFxuICAgICAgICByZXNwb25zZXM6IFtyZXNwb25zZV0sXG4gICAgICB9O1xuICAgIH0gZWxzZSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgYEJyb3dzZXJIVFRQUmVxdWVzdC5zYXZlKCkgZmFpbGVkIGR1ZSB0byBIVFRQIHJlc3BvbnNlIHN0YXR1cyBgICtcbiAgICAgICAgICBgJHtyZXNwb25zZS5zdGF0dXN9LmApO1xuICAgIH1cbiAgfVxuXG4gIC8qKlxuICAgKiBMb2FkIG1vZGVsIGFydGlmYWN0cyB2aWEgSFRUUCByZXF1ZXN0KHMpLlxuICAgKlxuICAgKiBTZWUgdGhlIGRvY3VtZW50YXRpb24gdG8gYHRmLmlvLmh0dHBgIGZvciBkZXRhaWxzIG9uIHRoZSBzYXZlZFxuICAgKiBhcnRpZmFjdHMuXG4gICAqXG4gICAqIEByZXR1cm5zIFRoZSBsb2FkZWQgbW9kZWwgYXJ0aWZhY3RzIChpZiBsb2FkaW5nIHN1Y2NlZWRzKS5cbiAgICovXG4gIGFzeW5jIGxvYWQoKTogUHJvbWlzZTxNb2RlbEFydGlmYWN0cz4ge1xuICAgIGNvbnN0IG1vZGVsQ29uZmlnUmVxdWVzdCA9IGF3YWl0IHRoaXMuZmV0Y2godGhpcy5wYXRoLCB0aGlzLnJlcXVlc3RJbml0KTtcblxuICAgIGlmICghbW9kZWxDb25maWdSZXF1ZXN0Lm9rKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgYFJlcXVlc3QgdG8gJHt0aGlzLnBhdGh9IGZhaWxlZCB3aXRoIHN0YXR1cyBjb2RlIGAgK1xuICAgICAgICAgIGAke21vZGVsQ29uZmlnUmVxdWVzdC5zdGF0dXN9LiBQbGVhc2UgdmVyaWZ5IHRoaXMgVVJMIHBvaW50cyB0byBgICtcbiAgICAgICAgICBgdGhlIG1vZGVsIEpTT04gb2YgdGhlIG1vZGVsIHRvIGxvYWQuYCk7XG4gICAgfVxuICAgIGxldCBtb2RlbEpTT046IE1vZGVsSlNPTjtcbiAgICB0cnkge1xuICAgICAgbW9kZWxKU09OID0gYXdhaXQgbW9kZWxDb25maWdSZXF1ZXN0Lmpzb24oKTtcbiAgICB9IGNhdGNoIChlKSB7XG4gICAgICBsZXQgbWVzc2FnZSA9IGBGYWlsZWQgdG8gcGFyc2UgbW9kZWwgSlNPTiBvZiByZXNwb25zZSBmcm9tICR7dGhpcy5wYXRofS5gO1xuICAgICAgLy8gVE9ETyhuc3Rob3JhdCk6IFJlbW92ZSB0aGlzIGFmdGVyIHNvbWUgdGltZSB3aGVuIHdlJ3JlIGNvbWZvcnRhYmxlIHRoYXRcbiAgICAgIC8vIC5wYiBmaWxlcyBhcmUgbW9zdGx5IGdvbmUuXG4gICAgICBpZiAodGhpcy5wYXRoLmVuZHNXaXRoKCcucGInKSkge1xuICAgICAgICBtZXNzYWdlICs9ICcgWW91ciBwYXRoIGNvbnRhaW5zIGEgLnBiIGZpbGUgZXh0ZW5zaW9uLiAnICtcbiAgICAgICAgICAgICdTdXBwb3J0IGZvciAucGIgbW9kZWxzIGhhdmUgYmVlbiByZW1vdmVkIGluIFRlbnNvckZsb3cuanMgMS4wICcgK1xuICAgICAgICAgICAgJ2luIGZhdm9yIG9mIC5qc29uIG1vZGVscy4gWW91IGNhbiByZS1jb252ZXJ0IHlvdXIgUHl0aG9uICcgK1xuICAgICAgICAgICAgJ1RlbnNvckZsb3cgbW9kZWwgdXNpbmcgdGhlIFRlbnNvckZsb3cuanMgMS4wIGNvbnZlcnNpb24gc2NyaXB0cyAnICtcbiAgICAgICAgICAgICdvciB5b3UgY2FuIGNvbnZlcnQgeW91ci5wYiBtb2RlbHMgd2l0aCB0aGUgXFwncGIyanNvblxcJycgK1xuICAgICAgICAgICAgJ05QTSBzY3JpcHQgaW4gdGhlIHRlbnNvcmZsb3cvdGZqcy1jb252ZXJ0ZXIgcmVwb3NpdG9yeS4nO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgbWVzc2FnZSArPSAnIFBsZWFzZSBtYWtlIHN1cmUgdGhlIHNlcnZlciBpcyBzZXJ2aW5nIHZhbGlkICcgK1xuICAgICAgICAgICAgJ0pTT04gZm9yIHRoaXMgcmVxdWVzdC4nO1xuICAgICAgfVxuICAgICAgdGhyb3cgbmV3IEVycm9yKG1lc3NhZ2UpO1xuICAgIH1cblxuICAgIC8vIFdlIGRvIG5vdCBhbGxvdyBib3RoIG1vZGVsVG9wb2xvZ3kgYW5kIHdlaWdodHNNYW5pZmVzdCB0byBiZSBtaXNzaW5nLlxuICAgIGNvbnN0IG1vZGVsVG9wb2xvZ3kgPSBtb2RlbEpTT04ubW9kZWxUb3BvbG9neTtcbiAgICBjb25zdCB3ZWlnaHRzTWFuaWZlc3QgPSBtb2RlbEpTT04ud2VpZ2h0c01hbmlmZXN0O1xuICAgIGlmIChtb2RlbFRvcG9sb2d5ID09IG51bGwgJiYgd2VpZ2h0c01hbmlmZXN0ID09IG51bGwpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICBgVGhlIEpTT04gZnJvbSBIVFRQIHBhdGggJHt0aGlzLnBhdGh9IGNvbnRhaW5zIG5laXRoZXIgbW9kZWwgYCArXG4gICAgICAgICAgYHRvcG9sb2d5IG9yIG1hbmlmZXN0IGZvciB3ZWlnaHRzLmApO1xuICAgIH1cblxuICAgIHJldHVybiBnZXRNb2RlbEFydGlmYWN0c0ZvckpTT04oXG4gICAgICAgIG1vZGVsSlNPTiwgKHdlaWdodHNNYW5pZmVzdCkgPT4gdGhpcy5sb2FkV2VpZ2h0cyh3ZWlnaHRzTWFuaWZlc3QpKTtcbiAgfVxuXG4gIHByaXZhdGUgYXN5bmMgbG9hZFdlaWdodHMod2VpZ2h0c01hbmlmZXN0OiBXZWlnaHRzTWFuaWZlc3RDb25maWcpOlxuICAgIFByb21pc2U8W1dlaWdodHNNYW5pZmVzdEVudHJ5W10sIFdlaWdodERhdGFdPiB7XG4gICAgY29uc3Qgd2VpZ2h0UGF0aCA9IEFycmF5LmlzQXJyYXkodGhpcy5wYXRoKSA/IHRoaXMucGF0aFsxXSA6IHRoaXMucGF0aDtcbiAgICBjb25zdCBbcHJlZml4LCBzdWZmaXhdID0gcGFyc2VVcmwod2VpZ2h0UGF0aCk7XG4gICAgY29uc3QgcGF0aFByZWZpeCA9IHRoaXMud2VpZ2h0UGF0aFByZWZpeCB8fCBwcmVmaXg7XG5cbiAgICBjb25zdCB3ZWlnaHRTcGVjcyA9IGdldFdlaWdodFNwZWNzKHdlaWdodHNNYW5pZmVzdCk7XG5cbiAgICBjb25zdCBmZXRjaFVSTHM6IHN0cmluZ1tdID0gW107XG4gICAgY29uc3QgdXJsUHJvbWlzZXM6IEFycmF5PFByb21pc2U8c3RyaW5nPj4gPSBbXTtcbiAgICBmb3IgKGNvbnN0IHdlaWdodHNHcm91cCBvZiB3ZWlnaHRzTWFuaWZlc3QpIHtcbiAgICAgIGZvciAoY29uc3QgcGF0aCBvZiB3ZWlnaHRzR3JvdXAucGF0aHMpIHtcbiAgICAgICAgaWYgKHRoaXMud2VpZ2h0VXJsQ29udmVydGVyICE9IG51bGwpIHtcbiAgICAgICAgICB1cmxQcm9taXNlcy5wdXNoKHRoaXMud2VpZ2h0VXJsQ29udmVydGVyKHBhdGgpKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBmZXRjaFVSTHMucHVzaChwYXRoUHJlZml4ICsgcGF0aCArIHN1ZmZpeCk7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICB9XG5cbiAgICBpZiAodGhpcy53ZWlnaHRVcmxDb252ZXJ0ZXIpIHtcbiAgICAgIGZldGNoVVJMcy5wdXNoKC4uLmF3YWl0IFByb21pc2UuYWxsKHVybFByb21pc2VzKSk7XG4gICAgfVxuXG4gICAgY29uc3QgYnVmZmVycyA9IGF3YWl0IGxvYWRXZWlnaHRzQXNBcnJheUJ1ZmZlcihmZXRjaFVSTHMsIHtcbiAgICAgIHJlcXVlc3RJbml0OiB0aGlzLnJlcXVlc3RJbml0LFxuICAgICAgZmV0Y2hGdW5jOiB0aGlzLmZldGNoLFxuICAgICAgb25Qcm9ncmVzczogdGhpcy5vblByb2dyZXNzXG4gICAgfSk7XG4gICAgcmV0dXJuIFt3ZWlnaHRTcGVjcywgYnVmZmVyc107XG4gIH1cbn1cblxuLyoqXG4gKiBFeHRyYWN0IHRoZSBwcmVmaXggYW5kIHN1ZmZpeCBvZiB0aGUgdXJsLCB3aGVyZSB0aGUgcHJlZml4IGlzIHRoZSBwYXRoIGJlZm9yZVxuICogdGhlIGxhc3QgZmlsZSwgYW5kIHN1ZmZpeCBpcyB0aGUgc2VhcmNoIHBhcmFtcyBhZnRlciB0aGUgbGFzdCBmaWxlLlxuICogYGBgXG4gKiBjb25zdCB1cmwgPSAnaHR0cDovL3RmaHViLmRldi9tb2RlbC8xL3RlbnNvcmZsb3dqc19tb2RlbC5wYj90ZmpzLWZvcm1hdD1maWxlJ1xuICogW3ByZWZpeCwgc3VmZml4XSA9IHBhcnNlVXJsKHVybClcbiAqIC8vIHByZWZpeCA9ICdodHRwOi8vdGZodWIuZGV2L21vZGVsLzEvJ1xuICogLy8gc3VmZml4ID0gJz90ZmpzLWZvcm1hdD1maWxlJ1xuICogYGBgXG4gKiBAcGFyYW0gdXJsIHRoZSBtb2RlbCB1cmwgdG8gYmUgcGFyc2VkLlxuICovXG5leHBvcnQgZnVuY3Rpb24gcGFyc2VVcmwodXJsOiBzdHJpbmcpOiBbc3RyaW5nLCBzdHJpbmddIHtcbiAgY29uc3QgbGFzdFNsYXNoID0gdXJsLmxhc3RJbmRleE9mKCcvJyk7XG4gIGNvbnN0IGxhc3RTZWFyY2hQYXJhbSA9IHVybC5sYXN0SW5kZXhPZignPycpO1xuICBjb25zdCBwcmVmaXggPSB1cmwuc3Vic3RyaW5nKDAsIGxhc3RTbGFzaCk7XG4gIGNvbnN0IHN1ZmZpeCA9XG4gICAgICBsYXN0U2VhcmNoUGFyYW0gPiBsYXN0U2xhc2ggPyB1cmwuc3Vic3RyaW5nKGxhc3RTZWFyY2hQYXJhbSkgOiAnJztcbiAgcmV0dXJuIFtwcmVmaXggKyAnLycsIHN1ZmZpeF07XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBpc0hUVFBTY2hlbWUodXJsOiBzdHJpbmcpOiBib29sZWFuIHtcbiAgcmV0dXJuIHVybC5tYXRjaChIVFRQUmVxdWVzdC5VUkxfU0NIRU1FX1JFR0VYKSAhPSBudWxsO1xufVxuXG5leHBvcnQgY29uc3QgaHR0cFJvdXRlcjogSU9Sb3V0ZXIgPVxuICAgICh1cmw6IHN0cmluZywgbG9hZE9wdGlvbnM/OiBMb2FkT3B0aW9ucykgPT4ge1xuICAgICAgaWYgKHR5cGVvZiBmZXRjaCA9PT0gJ3VuZGVmaW5lZCcgJiZcbiAgICAgICAgICAobG9hZE9wdGlvbnMgPT0gbnVsbCB8fCBsb2FkT3B0aW9ucy5mZXRjaEZ1bmMgPT0gbnVsbCkpIHtcbiAgICAgICAgLy8gYGh0dHBgIHVzZXMgYGZldGNoYCBvciBgbm9kZS1mZXRjaGAsIGlmIG9uZSB3YW50cyB0byB1c2UgaXQgaW5cbiAgICAgICAgLy8gYW4gZW52aXJvbm1lbnQgdGhhdCBpcyBub3QgdGhlIGJyb3dzZXIgb3Igbm9kZSB0aGV5IGhhdmUgdG8gc2V0dXAgYVxuICAgICAgICAvLyBnbG9iYWwgZmV0Y2ggcG9seWZpbGwuXG4gICAgICAgIHJldHVybiBudWxsO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgbGV0IGlzSFRUUCA9IHRydWU7XG4gICAgICAgIGlmIChBcnJheS5pc0FycmF5KHVybCkpIHtcbiAgICAgICAgICBpc0hUVFAgPSB1cmwuZXZlcnkodXJsSXRlbSA9PiBpc0hUVFBTY2hlbWUodXJsSXRlbSkpO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGlzSFRUUCA9IGlzSFRUUFNjaGVtZSh1cmwpO1xuICAgICAgICB9XG4gICAgICAgIGlmIChpc0hUVFApIHtcbiAgICAgICAgICByZXR1cm4gaHR0cCh1cmwsIGxvYWRPcHRpb25zKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgcmV0dXJuIG51bGw7XG4gICAgfTtcbklPUm91dGVyUmVnaXN0cnkucmVnaXN0ZXJTYXZlUm91dGVyKGh0dHBSb3V0ZXIpO1xuSU9Sb3V0ZXJSZWdpc3RyeS5yZWdpc3RlckxvYWRSb3V0ZXIoaHR0cFJvdXRlcik7XG5cbi8qKlxuICogQ3JlYXRlcyBhbiBJT0hhbmRsZXIgc3VidHlwZSB0aGF0IHNlbmRzIG1vZGVsIGFydGlmYWN0cyB0byBIVFRQIHNlcnZlci5cbiAqXG4gKiBBbiBIVFRQIHJlcXVlc3Qgb2YgdGhlIGBtdWx0aXBhcnQvZm9ybS1kYXRhYCBtaW1lIHR5cGUgd2lsbCBiZSBzZW50IHRvIHRoZVxuICogYHBhdGhgIFVSTC4gVGhlIGZvcm0gZGF0YSBpbmNsdWRlcyBhcnRpZmFjdHMgdGhhdCByZXByZXNlbnQgdGhlIHRvcG9sb2d5XG4gKiBhbmQvb3Igd2VpZ2h0cyBvZiB0aGUgbW9kZWwuIEluIHRoZSBjYXNlIG9mIEtlcmFzLXN0eWxlIGB0Zi5Nb2RlbGAsIHR3b1xuICogYmxvYnMgKGZpbGVzKSBleGlzdCBpbiBmb3JtLWRhdGE6XG4gKiAgIC0gQSBKU09OIGZpbGUgY29uc2lzdGluZyBvZiBgbW9kZWxUb3BvbG9neWAgYW5kIGB3ZWlnaHRzTWFuaWZlc3RgLlxuICogICAtIEEgYmluYXJ5IHdlaWdodHMgZmlsZSBjb25zaXN0aW5nIG9mIHRoZSBjb25jYXRlbmF0ZWQgd2VpZ2h0IHZhbHVlcy5cbiAqIFRoZXNlIGZpbGVzIGFyZSBpbiB0aGUgc2FtZSBmb3JtYXQgYXMgdGhlIG9uZSBnZW5lcmF0ZWQgYnlcbiAqIFt0ZmpzX2NvbnZlcnRlcl0oaHR0cHM6Ly9qcy50ZW5zb3JmbG93Lm9yZy90dXRvcmlhbHMvaW1wb3J0LWtlcmFzLmh0bWwpLlxuICpcbiAqIFRoZSBmb2xsb3dpbmcgY29kZSBzbmlwcGV0IGV4ZW1wbGlmaWVzIHRoZSBjbGllbnQtc2lkZSBjb2RlIHRoYXQgdXNlcyB0aGlzXG4gKiBmdW5jdGlvbjpcbiAqXG4gKiBgYGBqc1xuICogY29uc3QgbW9kZWwgPSB0Zi5zZXF1ZW50aWFsKCk7XG4gKiBtb2RlbC5hZGQoXG4gKiAgICAgdGYubGF5ZXJzLmRlbnNlKHt1bml0czogMSwgaW5wdXRTaGFwZTogWzEwMF0sIGFjdGl2YXRpb246ICdzaWdtb2lkJ30pKTtcbiAqXG4gKiBjb25zdCBzYXZlUmVzdWx0ID0gYXdhaXQgbW9kZWwuc2F2ZSh0Zi5pby5odHRwKFxuICogICAgICdodHRwOi8vbW9kZWwtc2VydmVyOjUwMDAvdXBsb2FkJywge3JlcXVlc3RJbml0OiB7bWV0aG9kOiAnUFVUJ319KSk7XG4gKiBjb25zb2xlLmxvZyhzYXZlUmVzdWx0KTtcbiAqIGBgYFxuICpcbiAqIElmIHRoZSBkZWZhdWx0IGBQT1NUYCBtZXRob2QgaXMgdG8gYmUgdXNlZCwgd2l0aG91dCBhbnkgY3VzdG9tIHBhcmFtZXRlcnNcbiAqIHN1Y2ggYXMgaGVhZGVycywgeW91IGNhbiBzaW1wbHkgcGFzcyBhbiBIVFRQIG9yIEhUVFBTIFVSTCB0byBgbW9kZWwuc2F2ZWA6XG4gKlxuICogYGBganNcbiAqIGNvbnN0IHNhdmVSZXN1bHQgPSBhd2FpdCBtb2RlbC5zYXZlKCdodHRwOi8vbW9kZWwtc2VydmVyOjUwMDAvdXBsb2FkJyk7XG4gKiBgYGBcbiAqXG4gKiBUaGUgZm9sbG93aW5nIEdpdEh1YiBHaXN0XG4gKiBodHRwczovL2dpc3QuZ2l0aHViLmNvbS9kc21pbGtvdi8xYjYwNDZmZDYxMzJkNzQwOGQ1MjU3YjA5NzZmNzg2NFxuICogaW1wbGVtZW50cyBhIHNlcnZlciBiYXNlZCBvbiBbZmxhc2tdKGh0dHBzOi8vZ2l0aHViLmNvbS9wYWxsZXRzL2ZsYXNrKSB0aGF0XG4gKiBjYW4gcmVjZWl2ZSB0aGUgcmVxdWVzdC4gVXBvbiByZWNlaXZpbmcgdGhlIG1vZGVsIGFydGlmYWN0cyB2aWEgdGhlIHJlcXVzdCxcbiAqIHRoaXMgcGFydGljdWxhciBzZXJ2ZXIgcmVjb25zdGl0dXRlcyBpbnN0YW5jZXMgb2YgW0tlcmFzXG4gKiBNb2RlbHNdKGh0dHBzOi8va2VyYXMuaW8vbW9kZWxzL21vZGVsLykgaW4gbWVtb3J5LlxuICpcbiAqXG4gKiBAcGFyYW0gcGF0aCBBIFVSTCBwYXRoIHRvIHRoZSBtb2RlbC5cbiAqICAgQ2FuIGJlIGFuIGFic29sdXRlIEhUVFAgcGF0aCAoZS5nLixcbiAqICAgJ2h0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9tb2RlbC11cGxvYWQpJykgb3IgYSByZWxhdGl2ZSBwYXRoIChlLmcuLFxuICogICAnLi9tb2RlbC11cGxvYWQnKS5cbiAqIEBwYXJhbSByZXF1ZXN0SW5pdCBSZXF1ZXN0IGNvbmZpZ3VyYXRpb25zIHRvIGJlIHVzZWQgd2hlbiBzZW5kaW5nXG4gKiAgICBIVFRQIHJlcXVlc3QgdG8gc2VydmVyIHVzaW5nIGBmZXRjaGAuIEl0IGNhbiBjb250YWluIGZpZWxkcyBzdWNoIGFzXG4gKiAgICBgbWV0aG9kYCwgYGNyZWRlbnRpYWxzYCwgYGhlYWRlcnNgLCBgbW9kZWAsIGV0Yy4gU2VlXG4gKiAgICBodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9BUEkvUmVxdWVzdC9SZXF1ZXN0XG4gKiAgICBmb3IgbW9yZSBpbmZvcm1hdGlvbi4gYHJlcXVlc3RJbml0YCBtdXN0IG5vdCBoYXZlIGEgYm9keSwgYmVjYXVzZSB0aGVcbiAqIGJvZHkgd2lsbCBiZSBzZXQgYnkgVGVuc29yRmxvdy5qcy4gRmlsZSBibG9icyByZXByZXNlbnRpbmcgdGhlIG1vZGVsXG4gKiB0b3BvbG9neSAoZmlsZW5hbWU6ICdtb2RlbC5qc29uJykgYW5kIHRoZSB3ZWlnaHRzIG9mIHRoZSBtb2RlbCAoZmlsZW5hbWU6XG4gKiAnbW9kZWwud2VpZ2h0cy5iaW4nKSB3aWxsIGJlIGFwcGVuZGVkIHRvIHRoZSBib2R5LiBJZiBgcmVxdWVzdEluaXRgIGhhcyBhXG4gKiBgYm9keWAsIGFuIEVycm9yIHdpbGwgYmUgdGhyb3duLlxuICogQHBhcmFtIGxvYWRPcHRpb25zIE9wdGlvbmFsIGNvbmZpZ3VyYXRpb24gZm9yIHRoZSBsb2FkaW5nLiBJdCBpbmNsdWRlcyB0aGVcbiAqICAgZm9sbG93aW5nIGZpZWxkczpcbiAqICAgLSB3ZWlnaHRQYXRoUHJlZml4IE9wdGlvbmFsLCB0aGlzIHNwZWNpZmllcyB0aGUgcGF0aCBwcmVmaXggZm9yIHdlaWdodFxuICogICAgIGZpbGVzLCBieSBkZWZhdWx0IHRoaXMgaXMgY2FsY3VsYXRlZCBmcm9tIHRoZSBwYXRoIHBhcmFtLlxuICogICAtIGZldGNoRnVuYyBPcHRpb25hbCwgY3VzdG9tIGBmZXRjaGAgZnVuY3Rpb24uIEUuZy4sIGluIE5vZGUuanMsXG4gKiAgICAgdGhlIGBmZXRjaGAgZnJvbSBub2RlLWZldGNoIGNhbiBiZSB1c2VkIGhlcmUuXG4gKiAgIC0gb25Qcm9ncmVzcyBPcHRpb25hbCwgcHJvZ3Jlc3MgY2FsbGJhY2sgZnVuY3Rpb24sIGZpcmVkIHBlcmlvZGljYWxseVxuICogICAgIGJlZm9yZSB0aGUgbG9hZCBpcyBjb21wbGV0ZWQuXG4gKiBAcmV0dXJucyBBbiBpbnN0YW5jZSBvZiBgSU9IYW5kbGVyYC5cbiAqXG4gKiBAZG9jIHtcbiAqICAgaGVhZGluZzogJ01vZGVscycsXG4gKiAgIHN1YmhlYWRpbmc6ICdMb2FkaW5nJyxcbiAqICAgbmFtZXNwYWNlOiAnaW8nLFxuICogICBpZ25vcmVDSTogdHJ1ZVxuICogfVxuICovXG5leHBvcnQgZnVuY3Rpb24gaHR0cChwYXRoOiBzdHJpbmcsIGxvYWRPcHRpb25zPzogTG9hZE9wdGlvbnMpOiBJT0hhbmRsZXIge1xuICByZXR1cm4gbmV3IEhUVFBSZXF1ZXN0KHBhdGgsIGxvYWRPcHRpb25zKTtcbn1cblxuLyoqXG4gKiBEZXByZWNhdGVkLiBVc2UgYHRmLmlvLmh0dHBgLlxuICogQHBhcmFtIHBhdGhcbiAqIEBwYXJhbSBsb2FkT3B0aW9uc1xuICovXG5leHBvcnQgZnVuY3Rpb24gYnJvd3NlckhUVFBSZXF1ZXN0KFxuICAgIHBhdGg6IHN0cmluZywgbG9hZE9wdGlvbnM/OiBMb2FkT3B0aW9ucyk6IElPSGFuZGxlciB7XG4gIHJldHVybiBodHRwKHBhdGgsIGxvYWRPcHRpb25zKTtcbn1cbiJdfQ==