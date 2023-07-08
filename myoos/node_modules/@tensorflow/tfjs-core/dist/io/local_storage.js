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
import '../flags';
import { env } from '../environment';
import { assert } from '../util';
import { arrayBufferToBase64String, base64StringToArrayBuffer, getModelArtifactsInfoForJSON } from './io_utils';
import { CompositeArrayBuffer } from './composite_array_buffer';
import { IORouterRegistry } from './router_registry';
const PATH_SEPARATOR = '/';
const PATH_PREFIX = 'tensorflowjs_models';
const INFO_SUFFIX = 'info';
const MODEL_TOPOLOGY_SUFFIX = 'model_topology';
const WEIGHT_SPECS_SUFFIX = 'weight_specs';
const WEIGHT_DATA_SUFFIX = 'weight_data';
const MODEL_METADATA_SUFFIX = 'model_metadata';
/**
 * Purge all tensorflow.js-saved model artifacts from local storage.
 *
 * @returns Paths of the models purged.
 */
export function purgeLocalStorageArtifacts() {
    if (!env().getBool('IS_BROWSER') || typeof window === 'undefined' ||
        typeof window.localStorage === 'undefined') {
        throw new Error('purgeLocalStorageModels() cannot proceed because local storage is ' +
            'unavailable in the current environment.');
    }
    const LS = window.localStorage;
    const purgedModelPaths = [];
    for (let i = 0; i < LS.length; ++i) {
        const key = LS.key(i);
        const prefix = PATH_PREFIX + PATH_SEPARATOR;
        if (key.startsWith(prefix) && key.length > prefix.length) {
            LS.removeItem(key);
            const modelName = getModelPathFromKey(key);
            if (purgedModelPaths.indexOf(modelName) === -1) {
                purgedModelPaths.push(modelName);
            }
        }
    }
    return purgedModelPaths;
}
function getModelKeys(path) {
    return {
        info: [PATH_PREFIX, path, INFO_SUFFIX].join(PATH_SEPARATOR),
        topology: [PATH_PREFIX, path, MODEL_TOPOLOGY_SUFFIX].join(PATH_SEPARATOR),
        weightSpecs: [PATH_PREFIX, path, WEIGHT_SPECS_SUFFIX].join(PATH_SEPARATOR),
        weightData: [PATH_PREFIX, path, WEIGHT_DATA_SUFFIX].join(PATH_SEPARATOR),
        modelMetadata: [PATH_PREFIX, path, MODEL_METADATA_SUFFIX].join(PATH_SEPARATOR)
    };
}
function removeItems(keys) {
    for (const key of Object.values(keys)) {
        window.localStorage.removeItem(key);
    }
}
/**
 * Get model path from a local-storage key.
 *
 * E.g., 'tensorflowjs_models/my/model/1/info' --> 'my/model/1'
 *
 * @param key
 */
function getModelPathFromKey(key) {
    const items = key.split(PATH_SEPARATOR);
    if (items.length < 3) {
        throw new Error(`Invalid key format: ${key}`);
    }
    return items.slice(1, items.length - 1).join(PATH_SEPARATOR);
}
function maybeStripScheme(key) {
    return key.startsWith(BrowserLocalStorage.URL_SCHEME) ?
        key.slice(BrowserLocalStorage.URL_SCHEME.length) :
        key;
}
/**
 * IOHandler subclass: Browser Local Storage.
 *
 * See the doc string to `browserLocalStorage` for more details.
 */
export class BrowserLocalStorage {
    constructor(modelPath) {
        if (!env().getBool('IS_BROWSER') || typeof window === 'undefined' ||
            typeof window.localStorage === 'undefined') {
            // TODO(cais): Add more info about what IOHandler subtypes are
            // available.
            //   Maybe point to a doc page on the web and/or automatically determine
            //   the available IOHandlers and print them in the error message.
            throw new Error('The current environment does not support local storage.');
        }
        this.LS = window.localStorage;
        if (modelPath == null || !modelPath) {
            throw new Error('For local storage, modelPath must not be null, undefined or empty.');
        }
        this.modelPath = modelPath;
        this.keys = getModelKeys(this.modelPath);
    }
    /**
     * Save model artifacts to browser local storage.
     *
     * See the documentation to `browserLocalStorage` for details on the saved
     * artifacts.
     *
     * @param modelArtifacts The model artifacts to be stored.
     * @returns An instance of SaveResult.
     */
    async save(modelArtifacts) {
        if (modelArtifacts.modelTopology instanceof ArrayBuffer) {
            throw new Error('BrowserLocalStorage.save() does not support saving model topology ' +
                'in binary formats yet.');
        }
        else {
            const topology = JSON.stringify(modelArtifacts.modelTopology);
            const weightSpecs = JSON.stringify(modelArtifacts.weightSpecs);
            const modelArtifactsInfo = getModelArtifactsInfoForJSON(modelArtifacts);
            // TODO(mattsoulanille): Support saving models over 2GB that exceed
            // Chrome's ArrayBuffer size limit.
            const weightBuffer = CompositeArrayBuffer.join(modelArtifacts.weightData);
            try {
                this.LS.setItem(this.keys.info, JSON.stringify(modelArtifactsInfo));
                this.LS.setItem(this.keys.topology, topology);
                this.LS.setItem(this.keys.weightSpecs, weightSpecs);
                this.LS.setItem(this.keys.weightData, arrayBufferToBase64String(weightBuffer));
                // Note that JSON.stringify doesn't write out keys that have undefined
                // values, so for some keys, we set undefined instead of a null-ish
                // value.
                const metadata = {
                    format: modelArtifacts.format,
                    generatedBy: modelArtifacts.generatedBy,
                    convertedBy: modelArtifacts.convertedBy,
                    signature: modelArtifacts.signature != null ?
                        modelArtifacts.signature :
                        undefined,
                    userDefinedMetadata: modelArtifacts.userDefinedMetadata != null ?
                        modelArtifacts.userDefinedMetadata :
                        undefined,
                    modelInitializer: modelArtifacts.modelInitializer != null ?
                        modelArtifacts.modelInitializer :
                        undefined,
                    initializerSignature: modelArtifacts.initializerSignature != null ?
                        modelArtifacts.initializerSignature :
                        undefined,
                    trainingConfig: modelArtifacts.trainingConfig != null ?
                        modelArtifacts.trainingConfig :
                        undefined
                };
                this.LS.setItem(this.keys.modelMetadata, JSON.stringify(metadata));
                return { modelArtifactsInfo };
            }
            catch (err) {
                // If saving failed, clean up all items saved so far.
                removeItems(this.keys);
                throw new Error(`Failed to save model '${this.modelPath}' to local storage: ` +
                    `size quota being exceeded is a possible cause of this failure: ` +
                    `modelTopologyBytes=${modelArtifactsInfo.modelTopologyBytes}, ` +
                    `weightSpecsBytes=${modelArtifactsInfo.weightSpecsBytes}, ` +
                    `weightDataBytes=${modelArtifactsInfo.weightDataBytes}.`);
            }
        }
    }
    /**
     * Load a model from local storage.
     *
     * See the documentation to `browserLocalStorage` for details on the saved
     * artifacts.
     *
     * @returns The loaded model (if loading succeeds).
     */
    async load() {
        const info = JSON.parse(this.LS.getItem(this.keys.info));
        if (info == null) {
            throw new Error(`In local storage, there is no model with name '${this.modelPath}'`);
        }
        if (info.modelTopologyType !== 'JSON') {
            throw new Error('BrowserLocalStorage does not support loading non-JSON model ' +
                'topology yet.');
        }
        const out = {};
        // Load topology.
        const topology = JSON.parse(this.LS.getItem(this.keys.topology));
        if (topology == null) {
            throw new Error(`In local storage, the topology of model '${this.modelPath}' ` +
                `is missing.`);
        }
        out.modelTopology = topology;
        // Load weight specs.
        const weightSpecs = JSON.parse(this.LS.getItem(this.keys.weightSpecs));
        if (weightSpecs == null) {
            throw new Error(`In local storage, the weight specs of model '${this.modelPath}' ` +
                `are missing.`);
        }
        out.weightSpecs = weightSpecs;
        // Load meta-data fields.
        const metadataString = this.LS.getItem(this.keys.modelMetadata);
        if (metadataString != null) {
            const metadata = JSON.parse(metadataString);
            out.format = metadata.format;
            out.generatedBy = metadata.generatedBy;
            out.convertedBy = metadata.convertedBy;
            if (metadata.signature != null) {
                out.signature = metadata.signature;
            }
            if (metadata.userDefinedMetadata != null) {
                out.userDefinedMetadata = metadata.userDefinedMetadata;
            }
            if (metadata.modelInitializer != null) {
                out.modelInitializer = metadata.modelInitializer;
            }
            if (metadata.initializerSignature != null) {
                out.initializerSignature = metadata.initializerSignature;
            }
            if (metadata.trainingConfig != null) {
                out.trainingConfig = metadata.trainingConfig;
            }
        }
        // Load weight data.
        const weightDataBase64 = this.LS.getItem(this.keys.weightData);
        if (weightDataBase64 == null) {
            throw new Error(`In local storage, the binary weight values of model ` +
                `'${this.modelPath}' are missing.`);
        }
        out.weightData = base64StringToArrayBuffer(weightDataBase64);
        return out;
    }
}
BrowserLocalStorage.URL_SCHEME = 'localstorage://';
export const localStorageRouter = (url) => {
    if (!env().getBool('IS_BROWSER')) {
        return null;
    }
    else {
        if (!Array.isArray(url) && url.startsWith(BrowserLocalStorage.URL_SCHEME)) {
            return browserLocalStorage(url.slice(BrowserLocalStorage.URL_SCHEME.length));
        }
        else {
            return null;
        }
    }
};
IORouterRegistry.registerSaveRouter(localStorageRouter);
IORouterRegistry.registerLoadRouter(localStorageRouter);
/**
 * Factory function for local storage IOHandler.
 *
 * This `IOHandler` supports both `save` and `load`.
 *
 * For each model's saved artifacts, four items are saved to local storage.
 *   - `${PATH_SEPARATOR}/${modelPath}/info`: Contains meta-info about the
 *     model, such as date saved, type of the topology, size in bytes, etc.
 *   - `${PATH_SEPARATOR}/${modelPath}/topology`: Model topology. For Keras-
 *     style models, this is a stringized JSON.
 *   - `${PATH_SEPARATOR}/${modelPath}/weight_specs`: Weight specs of the
 *     model, can be used to decode the saved binary weight values (see
 *     item below).
 *   - `${PATH_SEPARATOR}/${modelPath}/weight_data`: Concatenated binary
 *     weight values, stored as a base64-encoded string.
 *
 * Saving may throw an `Error` if the total size of the artifacts exceed the
 * browser-specific quota.
 *
 * @param modelPath A unique identifier for the model to be saved. Must be a
 *   non-empty string.
 * @returns An instance of `IOHandler`, which can be used with, e.g.,
 *   `tf.Model.save`.
 */
export function browserLocalStorage(modelPath) {
    return new BrowserLocalStorage(modelPath);
}
export class BrowserLocalStorageManager {
    constructor() {
        assert(env().getBool('IS_BROWSER'), () => 'Current environment is not a web browser');
        assert(typeof window === 'undefined' ||
            typeof window.localStorage !== 'undefined', () => 'Current browser does not appear to support localStorage');
        this.LS = window.localStorage;
    }
    async listModels() {
        const out = {};
        const prefix = PATH_PREFIX + PATH_SEPARATOR;
        const suffix = PATH_SEPARATOR + INFO_SUFFIX;
        for (let i = 0; i < this.LS.length; ++i) {
            const key = this.LS.key(i);
            if (key.startsWith(prefix) && key.endsWith(suffix)) {
                const modelPath = getModelPathFromKey(key);
                out[modelPath] = JSON.parse(this.LS.getItem(key));
            }
        }
        return out;
    }
    async removeModel(path) {
        path = maybeStripScheme(path);
        const keys = getModelKeys(path);
        if (this.LS.getItem(keys.info) == null) {
            throw new Error(`Cannot find model at path '${path}'`);
        }
        const info = JSON.parse(this.LS.getItem(keys.info));
        removeItems(keys);
        return info;
    }
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoibG9jYWxfc3RvcmFnZS5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uL3RmanMtY29yZS9zcmMvaW8vbG9jYWxfc3RvcmFnZS50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7O0dBZUc7QUFFSCxPQUFPLFVBQVUsQ0FBQztBQUNsQixPQUFPLEVBQUMsR0FBRyxFQUFDLE1BQU0sZ0JBQWdCLENBQUM7QUFFbkMsT0FBTyxFQUFDLE1BQU0sRUFBQyxNQUFNLFNBQVMsQ0FBQztBQUMvQixPQUFPLEVBQUMseUJBQXlCLEVBQUUseUJBQXlCLEVBQUUsNEJBQTRCLEVBQUMsTUFBTSxZQUFZLENBQUM7QUFDOUcsT0FBTyxFQUFDLG9CQUFvQixFQUFDLE1BQU0sMEJBQTBCLENBQUM7QUFDOUQsT0FBTyxFQUFXLGdCQUFnQixFQUFDLE1BQU0sbUJBQW1CLENBQUM7QUFHN0QsTUFBTSxjQUFjLEdBQUcsR0FBRyxDQUFDO0FBQzNCLE1BQU0sV0FBVyxHQUFHLHFCQUFxQixDQUFDO0FBQzFDLE1BQU0sV0FBVyxHQUFHLE1BQU0sQ0FBQztBQUMzQixNQUFNLHFCQUFxQixHQUFHLGdCQUFnQixDQUFDO0FBQy9DLE1BQU0sbUJBQW1CLEdBQUcsY0FBYyxDQUFDO0FBQzNDLE1BQU0sa0JBQWtCLEdBQUcsYUFBYSxDQUFDO0FBQ3pDLE1BQU0scUJBQXFCLEdBQUcsZ0JBQWdCLENBQUM7QUFFL0M7Ozs7R0FJRztBQUNILE1BQU0sVUFBVSwwQkFBMEI7SUFDeEMsSUFBSSxDQUFDLEdBQUcsRUFBRSxDQUFDLE9BQU8sQ0FBQyxZQUFZLENBQUMsSUFBSSxPQUFPLE1BQU0sS0FBSyxXQUFXO1FBQzdELE9BQU8sTUFBTSxDQUFDLFlBQVksS0FBSyxXQUFXLEVBQUU7UUFDOUMsTUFBTSxJQUFJLEtBQUssQ0FDWCxvRUFBb0U7WUFDcEUseUNBQXlDLENBQUMsQ0FBQztLQUNoRDtJQUNELE1BQU0sRUFBRSxHQUFHLE1BQU0s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LElBQUksQ0FBQyxFQUFFLENBQUMsTUFBTSxFQUFFLEVBQUUsQ0FBQyxFQUFFO1lBQ3ZDLE1BQU0sR0FBRyxHQUFHLElBQUksQ0FBQyxFQUFFLENBQUMsR0FBRyxDQUFDLENBQUMsQ0FBQyxDQUFDO1lBQzNCLElBQUksR0FBRyxDQUFDLFVBQVUsQ0FBQyxNQUFNLENBQUMsSUFBSSxHQUFHLENBQUMsUUFBUSxDQUFDLE1BQU0sQ0FBQyxFQUFFO2dCQUNsRCxNQUFNLFNBQVMsR0FBRyxtQkFBbUIsQ0FBQyxHQUFHLENBQUMsQ0FBQztnQkFDM0MsR0FBRyxDQUFDLFNBQVMsQ0FBQyxHQUFHLElBQUksQ0FBQyxLQUFLLENBQUMsSUFBSSxDQUFDLEVBQUUsQ0FBQyxPQUFPLENBQUMsR0FBRyxDQUFDLENBQXVCLENBQUM7YUFDekU7U0FDRjtRQUNELE9BQU8sR0FBRyxDQUFDO0lBQ2IsQ0FBQztJQUVELEtBQUssQ0FBQyxXQUFXLENBQUMsSUFBWTtRQUM1QixJQUFJLEdBQUcsZ0JBQWdCLENBQUMsSUFBSSxDQUFDLENBQUM7UUFDOUIsTUFBTSxJQUFJLEdBQUcsWUFBWSxDQUFDLElBQUksQ0FBQyxDQUFDO1FBQ2hDLElBQUksSUFBSSxDQUFDLEVBQUUsQ0FBQyxPQUFPLENBQUMsSUFBSSxDQUFDLElBQUksQ0FBQyxJQUFJLElBQUksRUFBRTtZQUN0QyxNQUFNLElBQUksS0FBSyxDQUFDLDhCQUE4QixJQUFJLEdBQUcsQ0FBQyxDQUFDO1NBQ3hEO1FBQ0QsTUFBTSxJQUFJLEdBQUcsSUFBSSxDQUFDLEtBQUssQ0FBQyxJQUFJLENBQUMsRUFBRSxDQUFDLE9BQU8sQ0FBQyxJQUFJLENBQUMsSUFBSSxDQUFDLENBQXVCLENBQUM7UUFDMUUsV0FBVyxDQUFDLElBQUksQ0FBQyxDQUFDO1FBQ2xCLE9BQU8sSUFBSSxDQUFDO0lBQ2QsQ0FBQztDQUNGIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMTggR29vZ2xlIExMQy4gQWxsIFJpZ2h0cyBSZXNlcnZlZC5cbiAqIExpY2Vuc2VkIHVuZGVyIHRoZSBBcGFjaGUgTGljZW5zZSwgVmVyc2lvbiAyLjAgKHRoZSBcIkxpY2Vuc2VcIik7XG4gKiB5b3UgbWF5IG5vdCB1c2UgdGhpcyBmaWxlIGV4Y2VwdCBpbiBjb21wbGlhbmNlIHdpdGggdGhlIExpY2Vuc2UuXG4gKiBZb3UgbWF5IG9idGFpbiBhIGNvcHkgb2YgdGhlIExpY2Vuc2UgYXRcbiAqXG4gKiBodHRwOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcbiAqXG4gKiBVbmxlc3MgcmVxdWlyZWQgYnkgYXBwbGljYWJsZSBsYXcgb3IgYWdyZWVkIHRvIGluIHdyaXRpbmcsIHNvZnR3YXJlXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXG4gKiBXSVRIT1VUIFdBUlJBTlRJRVMgT1IgQ09ORElUSU9OUyBPRiBBTlkgS0lORCwgZWl0aGVyIGV4cHJlc3Mgb3IgaW1wbGllZC5cbiAqIFNlZSB0aGUgTGljZW5zZSBmb3IgdGhlIHNwZWNpZmljIGxhbmd1YWdlIGdvdmVybmluZyBwZXJtaXNzaW9ucyBhbmRcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxuICogPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cbiAqL1xuXG5pbXBvcnQgJy4uL2ZsYWdzJztcbmltcG9ydCB7ZW52fSBmcm9tICcuLi9lbnZpcm9ubWVudCc7XG5cbmltcG9ydCB7YXNzZXJ0fSBmcm9tICcuLi91dGlsJztcbmltcG9ydCB7YXJyYXlCdWZmZXJUb0Jhc2U2NFN0cmluZywgYmFzZTY0U3RyaW5nVG9BcnJheUJ1ZmZlciwgZ2V0TW9kZWxBcnRpZmFjdHNJbmZvRm9ySlNPTn0gZnJvbSAnLi9pb191dGlscyc7XG5pbXBvcnQge0NvbXBvc2l0ZUFycmF5QnVmZmVyfSBmcm9tICcuL2NvbXBvc2l0ZV9hcnJheV9idWZmZXInO1xuaW1wb3J0IHtJT1JvdXRlciwgSU9Sb3V0ZXJSZWdpc3RyeX0gZnJvbSAnLi9yb3V0ZXJfcmVnaXN0cnknO1xuaW1wb3J0IHtJT0hhbmRsZXIsIE1vZGVsQXJ0aWZhY3RzLCBNb2RlbEFydGlmYWN0c0luZm8sIE1vZGVsSlNPTiwgTW9kZWxTdG9yZU1hbmFnZXIsIFNhdmVSZXN1bHR9IGZyb20gJy4vdHlwZXMnO1xuXG5jb25zdCBQQVRIX1NFUEFSQVRPUiA9ICcvJztcbmNvbnN0IFBBVEhfUFJFRklYID0gJ3RlbnNvcmZsb3dqc19tb2RlbHMnO1xuY29uc3QgSU5GT19TVUZGSVggPSAnaW5mbyc7XG5jb25zdCBNT0RFTF9UT1BPTE9HWV9TVUZGSVggPSAnbW9kZWxfdG9wb2xvZ3knO1xuY29uc3QgV0VJR0hUX1NQRUNTX1NVRkZJWCA9ICd3ZWlnaHRfc3BlY3MnO1xuY29uc3QgV0VJR0hUX0RBVEFfU1VGRklYID0gJ3dlaWdodF9kYXRhJztcbmNvbnN0IE1PREVMX01FVEFEQVRBX1NVRkZJWCA9ICdtb2RlbF9tZXRhZGF0YSc7XG5cbi8qKlxuICogUHVyZ2UgYWxsIHRlbnNvcmZsb3cuanMtc2F2ZWQgbW9kZWwgYXJ0aWZhY3RzIGZyb20gbG9jYWwgc3RvcmFnZS5cbiAqXG4gKiBAcmV0dXJucyBQYXRocyBvZiB0aGUgbW9kZWxzIHB1cmdlZC5cbiAqL1xuZXhwb3J0IGZ1bmN0aW9uIHB1cmdlTG9jYWxTdG9yYWdlQXJ0aWZhY3RzKCk6IHN0cmluZ1tdIHtcbiAgaWYgKCFlbnYoKS5nZXRCb29sKCdJU19CUk9XU0VSJykgfHwgdHlwZW9mIHdpbmRvdyA9PT0gJ3VuZGVmaW5lZCcgfHxcbiAgICAgIHR5cGVvZiB3aW5kb3cubG9jYWxTdG9yYWdlID09PSAndW5kZWZpbmVkJykge1xuICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgJ3B1cmdlTG9jYWxTdG9yYWdlTW9kZWxzKCkgY2Fubm90IHByb2NlZWQgYmVjYXVzZSBsb2NhbCBzdG9yYWdlIGlzICcgK1xuICAgICAgICAndW5hdmFpbGFibGUgaW4gdGhlIGN1cnJlbnQgZW52aXJvbm1lbnQuJyk7XG4gIH1cbiAgY29uc3QgTFMgPSB3aW5kb3cubG9jYWxTdG9yYWdlO1xuICBjb25zdCBwdXJnZWRNb2RlbFBhdGhzOiBzdHJpbmdbXSA9IFtdO1xuICBmb3IgKGxldCBpID0gMDsgaSA8IExTLmxlbmd0aDsgKytpKSB7XG4gICAgY29uc3Qga2V5ID0gTFMua2V5KGkpO1xuICAgIGNvbnN0IHByZWZpeCA9IFBBVEhfUFJFRklYICsgUEFUSF9TRVBBUkFUT1I7XG4gICAgaWYgKGtleS5zdGFydHNXaXRoKHByZWZpeCkgJiYga2V5Lmxlbmd0aCA+IHByZWZpeC5sZW5ndGgpIHtcbiAgICAgIExTLnJlbW92ZUl0ZW0oa2V5KTtcbiAgICAgIGNvbnN0IG1vZGVsTmFtZSA9IGdldE1vZGVsUGF0aEZyb21LZXkoa2V5KTtcbiAgICAgIGlmIChwdXJnZWRNb2RlbFBhdGhzLmluZGV4T2YobW9kZWxOYW1lKSA9PT0gLTEpIHtcbiAgICAgICAgcHVyZ2VkTW9kZWxQYXRocy5wdXNoKG1vZGVsTmFtZSk7XG4gICAgICB9XG4gICAgfVxuICB9XG4gIHJldHVybiBwdXJnZWRNb2RlbFBhdGhzO1xufVxuXG50eXBlIExvY2FsU3RvcmFnZUtleXMgPSB7XG4gIC8qKiBLZXkgb2YgdGhlIGxvY2FsU3RvcmFnZSBlbnRyeSBzdG9yaW5nIGBNb2RlbEFydGlmYWN0c0luZm9gLiAqL1xuICBpbmZvOiBzdHJpbmcsXG4gIC8qKlxuICAgKiBLZXkgb2YgdGhlIGxvY2FsU3RvcmFnZSBlbnRyeSBzdG9yaW5nIHRoZSAnbW9kZWxUb3BvbG9neScga2V5IG9mXG4gICAqIGBtb2RlbC5qc29uYFxuICAgKi9cbiAgdG9wb2xvZ3k6IHN0cmluZyxcbiAgLyoqXG4gICAqIEtleSBvZiB0aGUgbG9jYWxTdG9yYWdlIGVudHJ5IHN0b3JpbmcgdGhlIGB3ZWlnaHRzTWFuaWZlc3Qud2VpZ2h0c2AgZW50cmllc1xuICAgKiBvZiBgbW9kZWwuanNvbmBcbiAgICovXG4gIHdlaWdodFNwZWNzOiBzdHJpbmcsXG4gIC8qKiBLZXkgb2YgdGhlIGxvY2FsU3RvcmFnZSBlbnRyeSBzdG9yaW5nIHRoZSB3ZWlnaHQgZGF0YSBpbiBCYXNlNjQgKi9cbiAgd2VpZ2h0RGF0YTogc3RyaW5nLFxuICAvKipcbiAgICogS2V5IG9mIHRoZSBsb2NhbFN0b3JhZ2UgZW50cnkgc3RvcmluZyB0aGUgcmVtYWluaW5nIGZpZWxkcyBvZiBgbW9kZWwuanNvbmBcbiAgICogQHNlZSB7QGxpbmsgTW9kZWxNZXRhZGF0YX1cbiAgICovXG4gIG1vZGVsTWV0YWRhdGE6IHN0cmluZyxcbn07XG5cbnR5cGUgTW9kZWxNZXRhZGF0YSA9IE9taXQ8TW9kZWxKU09OLCAnbW9kZWxUb3BvbG9neSd8J3dlaWdodHNNYW5pZmVzdCc+O1xuXG5mdW5jdGlvbiBnZXRNb2RlbEtleXMocGF0aDogc3RyaW5nKTogTG9jYWxTdG9yYWdlS2V5cyB7XG4gIHJldHVybiB7XG4gICAgaW5mbzogW1BBVEhfUFJFRklYLCBwYXRoLCBJTkZPX1NVRkZJWF0uam9pbihQQVRIX1NFUEFSQVRPUiksXG4gICAgdG9wb2xvZ3k6IFtQQVRIX1BSRUZJWCwgcGF0aCwgTU9ERUxfVE9QT0xPR1lfU1VGRklYXS5qb2luKFBBVEhfU0VQQVJBVE9SKSxcbiAgICB3ZWlnaHRTcGVjczogW1BBVEhfUFJFRklYLCBwYXRoLCBXRUlHSFRfU1BFQ1NfU1VGRklYXS5qb2luKFBBVEhfU0VQQVJBVE9SKSxcbiAgICB3ZWlnaHREYXRhOiBbUEFUSF9QUkVGSVgsIHBhdGgsIFdFSUdIVF9EQVRBX1NVRkZJWF0uam9pbihQQVRIX1NFUEFSQVRPUiksXG4gICAgbW9kZWxNZXRhZGF0YTpcbiAgICAgICAgW1BBVEhfUFJFRklYLCBwYXRoLCBNT0RFTF9NRVRBREFUQV9TVUZGSVhdLmpvaW4oUEFUSF9TRVBBUkFUT1IpXG4gIH07XG59XG5cbmZ1bmN0aW9uIHJlbW92ZUl0ZW1zKGtleXM6IExvY2FsU3RvcmFnZUtleXMpOiB2b2lkIHtcbiAgZm9yIChjb25zdCBrZXkgb2YgT2JqZWN0LnZhbHVlcyhrZXlzKSkge1xuICAgIHdpbmRvdy5sb2NhbFN0b3JhZ2UucmVtb3ZlSXRlbShrZXkpO1xuICB9XG59XG5cbi8qKlxuICogR2V0IG1vZGVsIHBhdGggZnJvbSBhIGxvY2FsLXN0b3JhZ2Uga2V5LlxuICpcbiAqIEUuZy4sICd0ZW5zb3JmbG93anNfbW9kZWxzL215L21vZGVsLzEvaW5mbycgLS0+ICdteS9tb2RlbC8xJ1xuICpcbiAqIEBwYXJhbSBrZXlcbiAqL1xuZnVuY3Rpb24gZ2V0TW9kZWxQYXRoRnJvbUtleShrZXk6IHN0cmluZykge1xuICBjb25zdCBpdGVtcyA9IGtleS5zcGxpdChQQVRIX1NFUEFSQVRPUik7XG4gIGlmIChpdGVtcy5sZW5ndGggPCAzKSB7XG4gICAgdGhyb3cgbmV3IEVycm9yKGBJbnZhbGlkIGtleSBmb3JtYXQ6ICR7a2V5fWApO1xuICB9XG4gIHJldHVybiBpdGVtcy5zbGljZSgxLCBpdGVtcy5sZW5ndGggLSAxKS5qb2luKFBBVEhfU0VQQVJBVE9SKTtcbn1cblxuZnVuY3Rpb24gbWF5YmVTdHJpcFNjaGVtZShrZXk6IHN0cmluZykge1xuICByZXR1cm4ga2V5LnN0YXJ0c1dpdGgoQnJvd3NlckxvY2FsU3RvcmFnZS5VUkxfU0NIRU1FKSA/XG4gICAgICBrZXkuc2xpY2UoQnJvd3NlckxvY2FsU3RvcmFnZS5VUkxfU0NIRU1FLmxlbmd0aCkgOlxuICAgICAga2V5O1xufVxuXG4vKipcbiAqIElPSGFuZGxlciBzdWJjbGFzczogQnJvd3NlciBMb2NhbCBTdG9yYWdlLlxuICpcbiAqIFNlZSB0aGUgZG9jIHN0cmluZyB0byBgYnJvd3NlckxvY2FsU3RvcmFnZWAgZm9yIG1vcmUgZGV0YWlscy5cbiAqL1xuZXhwb3J0IGNsYXNzIEJyb3dzZXJMb2NhbFN0b3JhZ2UgaW1wbGVtZW50cyBJT0hhbmRsZXIge1xuICBwcm90ZWN0ZWQgcmVhZG9ubHkgTFM6IFN0b3JhZ2U7XG4gIHByb3RlY3RlZCByZWFkb25seSBtb2RlbFBhdGg6IHN0cmluZztcbiAgcHJvdGVjdGVkIHJlYWRvbmx5IGtleXM6IExvY2FsU3RvcmFnZUtleXM7XG5cbiAgc3RhdGljIHJlYWRvbmx5IFVSTF9TQ0hFTUUgPSAnbG9jYWxzdG9yYWdlOi8vJztcblxuICBjb25zdHJ1Y3Rvcihtb2RlbFBhdGg6IHN0cmluZykge1xuICAgIGlmICghZW52KCkuZ2V0Qm9vbCgnSVNfQlJPV1NFUicpIHx8IHR5cGVvZiB3aW5kb3cgPT09ICd1bmRlZmluZWQnIHx8XG4gICAgICAgIHR5cGVvZiB3aW5kb3cubG9jYWxTdG9yYWdlID09PSAndW5kZWZpbmVkJykge1xuICAgICAgLy8gVE9ETyhjYWlzKTogQWRkIG1vcmUgaW5mbyBhYm91dCB3aGF0IElPSGFuZGxlciBzdWJ0eXBlcyBhcmVcbiAgICAgIC8vIGF2YWlsYWJsZS5cbiAgICAgIC8vICAgTWF5YmUgcG9pbnQgdG8gYSBkb2MgcGFnZSBvbiB0aGUgd2ViIGFuZC9vciBhdXRvbWF0aWNhbGx5IGRldGVybWluZVxuICAgICAgLy8gICB0aGUgYXZhaWxhYmxlIElPSGFuZGxlcnMgYW5kIHByaW50IHRoZW0gaW4gdGhlIGVycm9yIG1lc3NhZ2UuXG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgJ1RoZSBjdXJyZW50IGVudmlyb25tZW50IGRvZXMgbm90IHN1cHBvcnQgbG9jYWwgc3RvcmFnZS4nKTtcbiAgICB9XG4gICAgdGhpcy5MUyA9IHdpbmRvdy5sb2NhbFN0b3JhZ2U7XG5cbiAgICBpZiAobW9kZWxQYXRoID09IG51bGwgfHwgIW1vZGVsUGF0aCkge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgICdGb3IgbG9jYWwgc3RvcmFnZSwgbW9kZWxQYXRoIG11c3Qgbm90IGJlIG51bGwsIHVuZGVmaW5lZCBvciBlbXB0eS4nKTtcbiAgICB9XG4gICAgdGhpcy5tb2RlbFBhdGggPSBtb2RlbFBhdGg7XG4gICAgdGhpcy5rZXlzID0gZ2V0TW9kZWxLZXlzKHRoaXMubW9kZWxQYXRoKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBTYXZlIG1vZGVsIGFydGlmYWN0cyB0byBicm93c2VyIGxvY2FsIHN0b3JhZ2UuXG4gICAqXG4gICAqIFNlZSB0aGUgZG9jdW1lbnRhdGlvbiB0byBgYnJvd3NlckxvY2FsU3RvcmFnZWAgZm9yIGRldGFpbHMgb24gdGhlIHNhdmVkXG4gICAqIGFydGlmYWN0cy5cbiAgICpcbiAgICogQHBhcmFtIG1vZGVsQXJ0aWZhY3RzIFRoZSBtb2RlbCBhcnRpZmFjdHMgdG8gYmUgc3RvcmVkLlxuICAgKiBAcmV0dXJucyBBbiBpbnN0YW5jZSBvZiBTYXZlUmVzdWx0LlxuICAgKi9cbiAgYXN5bmMgc2F2ZShtb2RlbEFydGlmYWN0czogTW9kZWxBcnRpZmFjdHMpOiBQcm9taXNlPFNhdmVSZXN1bHQ+IHtcbiAgICBpZiAobW9kZWxBcnRpZmFjdHMubW9kZWxUb3BvbG9neSBpbnN0YW5jZW9mIEFycmF5QnVmZmVyKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgJ0Jyb3dzZXJMb2NhbFN0b3JhZ2Uuc2F2ZSgpIGRvZXMgbm90IHN1cHBvcnQgc2F2aW5nIG1vZGVsIHRvcG9sb2d5ICcgK1xuICAgICAgICAgICdpbiBiaW5hcnkgZm9ybWF0cyB5ZXQuJyk7XG4gICAgfSBlbHNlIHtcbiAgICAgIGNvbnN0IHRvcG9sb2d5ID0gSlNPTi5zdHJpbmdpZnkobW9kZWxBcnRpZmFjdHMubW9kZWxUb3BvbG9neSk7XG4gICAgICBjb25zdCB3ZWlnaHRTcGVjcyA9IEpTT04uc3RyaW5naWZ5KG1vZGVsQXJ0aWZhY3RzLndlaWdodFNwZWNzKTtcblxuICAgICAgY29uc3QgbW9kZWxBcnRpZmFjdHNJbmZvOiBNb2RlbEFydGlmYWN0c0luZm8gPVxuICAgICAgICAgIGdldE1vZGVsQXJ0aWZhY3RzSW5mb0ZvckpTT04obW9kZWxBcnRpZmFjdHMpO1xuXG4gICAgICAvLyBUT0RPKG1hdHRzb3VsYW5pbGxlKTogU3VwcG9ydCBzYXZpbmcgbW9kZWxzIG92ZXIgMkdCIHRoYXQgZXhjZWVkXG4gICAgICAvLyBDaHJvbWUncyBBcnJheUJ1ZmZlciBzaXplIGxpbWl0LlxuICAgICAgY29uc3Qgd2VpZ2h0QnVmZmVyID0gQ29tcG9zaXRlQXJyYXlCdWZmZXIuam9pbihtb2RlbEFydGlmYWN0cy53ZWlnaHREYXRhKTtcblxuICAgICAgdHJ5IHtcbiAgICAgICAgdGhpcy5MUy5zZXRJdGVtKHRoaXMua2V5cy5pbmZvLCBKU09OLnN0cmluZ2lmeShtb2RlbEFydGlmYWN0c0luZm8pKTtcbiAgICAgICAgdGhpcy5MUy5zZXRJdGVtKHRoaXMua2V5cy50b3BvbG9neSwgdG9wb2xvZ3kpO1xuICAgICAgICB0aGlzLkxTLnNldEl0ZW0odGhpcy5rZXlzLndlaWdodFNwZWNzLCB3ZWlnaHRTcGVjcyk7XG4gICAgICAgIHRoaXMuTFMuc2V0SXRlbShcbiAgICAgICAgICAgIHRoaXMua2V5cy53ZWlnaHREYXRhLFxuICAgICAgICAgICAgYXJyYXlCdWZmZXJUb0Jhc2U2NFN0cmluZyh3ZWlnaHRCdWZmZXIpKTtcblxuICAgICAgICAvLyBOb3RlIHRoYXQgSlNPTi5zdHJpbmdpZnkgZG9lc24ndCB3cml0ZSBvdXQga2V5cyB0aGF0IGhhdmUgdW5kZWZpbmVkXG4gICAgICAgIC8vIHZhbHVlcywgc28gZm9yIHNvbWUga2V5cywgd2Ugc2V0IHVuZGVmaW5lZCBpbnN0ZWFkIG9mIGEgbnVsbC1pc2hcbiAgICAgICAgLy8gdmFsdWUuXG4gICAgICAgIGNvbnN0IG1ldGFkYXRhOiBSZXF1aXJlZDxNb2RlbE1ldGFkYXRhPiA9IHtcbiAgICAgICAgICBmb3JtYXQ6IG1vZGVsQXJ0aWZhY3RzLmZvcm1hdCxcbiAgICAgICAgICBnZW5lcmF0ZWRCeTogbW9kZWxBcnRpZmFjdHMuZ2VuZXJhdGVkQnksXG4gICAgICAgICAgY29udmVydGVkQnk6IG1vZGVsQXJ0aWZhY3RzLmNvbnZlcnRlZEJ5LFxuICAgICAgICAgIHNpZ25hdHVyZTogbW9kZWxBcnRpZmFjdHMuc2lnbmF0dXJlICE9IG51bGwgP1xuICAgICAgICAgICAgICBtb2RlbEFydGlmYWN0cy5zaWduYXR1cmUgOlxuICAgICAgICAgICAgICB1bmRlZmluZWQsXG4gICAgICAgICAgdXNlckRlZmluZWRNZXRhZGF0YTogbW9kZWxBcnRpZmFjdHMudXNlckRlZmluZWRNZXRhZGF0YSAhPSBudWxsID9cbiAgICAgICAgICAgICAgbW9kZWxBcnRpZmFjdHMudXNlckRlZmluZWRNZXRhZGF0YSA6XG4gICAgICAgICAgICAgIHVuZGVmaW5lZCxcbiAgICAgICAgICBtb2RlbEluaXRpYWxpemVyOiBtb2RlbEFydGlmYWN0cy5tb2RlbEluaXRpYWxpemVyICE9IG51bGwgP1xuICAgICAgICAgICAgICBtb2RlbEFydGlmYWN0cy5tb2RlbEluaXRpYWxpemVyIDpcbiAgICAgICAgICAgICAgdW5kZWZpbmVkLFxuICAgICAgICAgIGluaXRpYWxpemVyU2lnbmF0dXJlOiBtb2RlbEFydGlmYWN0cy5pbml0aWFsaXplclNpZ25hdHVyZSAhPSBudWxsID9cbiAgICAgICAgICAgICAgbW9kZWxBcnRpZmFjdHMuaW5pdGlhbGl6ZXJTaWduYXR1cmUgOlxuICAgICAgICAgICAgICB1bmRlZmluZWQsXG4gICAgICAgICAgdHJhaW5pbmdDb25maWc6IG1vZGVsQXJ0aWZhY3RzLnRyYWluaW5nQ29uZmlnICE9IG51bGwgP1xuICAgICAgICAgICAgICBtb2RlbEFydGlmYWN0cy50cmFpbmluZ0NvbmZpZyA6XG4gICAgICAgICAgICAgIHVuZGVmaW5lZFxuICAgICAgICB9O1xuICAgICAgICB0aGlzLkxTLnNldEl0ZW0odGhpcy5rZXlzLm1vZGVsTWV0YWRhdGEsIEpTT04uc3RyaW5naWZ5KG1ldGFkYXRhKSk7XG5cbiAgICAgICAgcmV0dXJuIHttb2RlbEFydGlmYWN0c0luZm99O1xuICAgICAgfSBjYXRjaCAoZXJyKSB7XG4gICAgICAgIC8vIElmIHNhdmluZyBmYWlsZWQsIGNsZWFuIHVwIGFsbCBpdGVtcyBzYXZlZCBzbyBmYXIuXG4gICAgICAgIHJlbW92ZUl0ZW1zKHRoaXMua2V5cyk7XG5cbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgICAgYEZhaWxlZCB0byBzYXZlIG1vZGVsICcke3RoaXMubW9kZWxQYXRofScgdG8gbG9jYWwgc3RvcmFnZTogYCArXG4gICAgICAgICAgICBgc2l6ZSBxdW90YSBiZWluZyBleGNlZWRlZCBpcyBhIHBvc3NpYmxlIGNhdXNlIG9mIHRoaXMgZmFpbHVyZTogYCArXG4gICAgICAgICAgICBgbW9kZWxUb3BvbG9neUJ5dGVzPSR7bW9kZWxBcnRpZmFjdHNJbmZvLm1vZGVsVG9wb2xvZ3lCeXRlc30sIGAgK1xuICAgICAgICAgICAgYHdlaWdodFNwZWNzQnl0ZXM9JHttb2RlbEFydGlmYWN0c0luZm8ud2VpZ2h0U3BlY3NCeXRlc30sIGAgK1xuICAgICAgICAgICAgYHdlaWdodERhdGFCeXRlcz0ke21vZGVsQXJ0aWZhY3RzSW5mby53ZWlnaHREYXRhQnl0ZXN9LmApO1xuICAgICAgfVxuICAgIH1cbiAgfVxuXG4gIC8qKlxuICAgKiBMb2FkIGEgbW9kZWwgZnJvbSBsb2NhbCBzdG9yYWdlLlxuICAgKlxuICAgKiBTZWUgdGhlIGRvY3VtZW50YXRpb24gdG8gYGJyb3dzZXJMb2NhbFN0b3JhZ2VgIGZvciBkZXRhaWxzIG9uIHRoZSBzYXZlZFxuICAgKiBhcnRpZmFjdHMuXG4gICAqXG4gICAqIEByZXR1cm5zIFRoZSBsb2FkZWQgbW9kZWwgKGlmIGxvYWRpbmcgc3VjY2VlZHMpLlxuICAgKi9cbiAgYXN5bmMgbG9hZCgpOiBQcm9taXNlPE1vZGVsQXJ0aWZhY3RzPiB7XG4gICAgY29uc3QgaW5mbyA9XG4gICAgICAgIEpTT04ucGFyc2UodGhpcy5MUy5nZXRJdGVtKHRoaXMua2V5cy5pbmZvKSkgYXMgTW9kZWxBcnRpZmFjdHNJbmZvO1xuICAgIGlmIChpbmZvID09IG51bGwpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICBgSW4gbG9jYWwgc3RvcmFnZSwgdGhlcmUgaXMgbm8gbW9kZWwgd2l0aCBuYW1lICcke3RoaXMubW9kZWxQYXRofSdgKTtcbiAgICB9XG5cbiAgICBpZiAoaW5mby5tb2RlbFRvcG9sb2d5VHlwZSAhPT0gJ0pTT04nKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgJ0Jyb3dzZXJMb2NhbFN0b3JhZ2UgZG9lcyBub3Qgc3VwcG9ydCBsb2FkaW5nIG5vbi1KU09OIG1vZGVsICcgK1xuICAgICAgICAgICd0b3BvbG9neSB5ZXQuJyk7XG4gICAgfVxuXG4gICAgY29uc3Qgb3V0OiBNb2RlbEFydGlmYWN0cyA9IHt9O1xuXG4gICAgLy8gTG9hZCB0b3BvbG9neS5cbiAgICBjb25zdCB0b3BvbG9neSA9IEpTT04ucGFyc2UodGhpcy5MUy5nZXRJdGVtKHRoaXMua2V5cy50b3BvbG9neSkpO1xuICAgIGlmICh0b3BvbG9neSA9PSBudWxsKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgYEluIGxvY2FsIHN0b3JhZ2UsIHRoZSB0b3BvbG9neSBvZiBtb2RlbCAnJHt0aGlzLm1vZGVsUGF0aH0nIGAgK1xuICAgICAgICAgIGBpcyBtaXNzaW5nLmApO1xuICAgIH1cbiAgICBvdXQubW9kZWxUb3BvbG9neSA9IHRvcG9sb2d5O1xuXG4gICAgLy8gTG9hZCB3ZWlnaHQgc3BlY3MuXG4gICAgY29uc3Qgd2VpZ2h0U3BlY3MgPSBKU09OLnBhcnNlKHRoaXMuTFMuZ2V0SXRlbSh0aGlzLmtleXMud2VpZ2h0U3BlY3MpKTtcbiAgICBpZiAod2VpZ2h0U3BlY3MgPT0gbnVsbCkge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgIGBJbiBsb2NhbCBzdG9yYWdlLCB0aGUgd2VpZ2h0IHNwZWNzIG9mIG1vZGVsICcke3RoaXMubW9kZWxQYXRofScgYCArXG4gICAgICAgICAgYGFyZSBtaXNzaW5nLmApO1xuICAgIH1cbiAgICBvdXQud2VpZ2h0U3BlY3MgPSB3ZWlnaHRTcGVjcztcblxuICAgIC8vIExvYWQgbWV0YS1kYXRhIGZpZWxkcy5cbiAgICBjb25zdCBtZXRhZGF0YVN0cmluZyA9IHRoaXMuTFMuZ2V0SXRlbSh0aGlzLmtleXMubW9kZWxNZXRhZGF0YSk7XG4gICAgaWYgKG1ldGFkYXRhU3RyaW5nICE9IG51bGwpIHtcbiAgICAgIGNvbnN0IG1ldGFkYXRhID0gSlNPTi5wYXJzZShtZXRhZGF0YVN0cmluZykgYXMgTW9kZWxNZXRhZGF0YTtcbiAgICAgIG91dC5mb3JtYXQgPSBtZXRhZGF0YS5mb3JtYXQ7XG4gICAgICBvdXQuZ2VuZXJhdGVkQnkgPSBtZXRhZGF0YS5nZW5lcmF0ZWRCeTtcbiAgICAgIG91dC5jb252ZXJ0ZWRCeSA9IG1ldGFkYXRhLmNvbnZlcnRlZEJ5O1xuICAgICAgaWYgKG1ldGFkYXRhLnNpZ25hdHVyZSAhPSBudWxsKSB7XG4gICAgICAgIG91dC5zaWduYXR1cmUgPSBtZXRhZGF0YS5zaWduYXR1cmU7XG4gICAgICB9XG4gICAgICBpZiAobWV0YWRhdGEudXNlckRlZmluZWRNZXRhZGF0YSAhPSBudWxsKSB7XG4gICAgICAgIG91dC51c2VyRGVmaW5lZE1ldGFkYXRhID0gbWV0YWRhdGEudXNlckRlZmluZWRNZXRhZGF0YTtcbiAgICAgIH1cbiAgICAgIGlmIChtZXRhZGF0YS5tb2RlbEluaXRpYWxpemVyICE9IG51bGwpIHtcbiAgICAgICAgb3V0Lm1vZGVsSW5pdGlhbGl6ZXIgPSBtZXRhZGF0YS5tb2RlbEluaXRpYWxpemVyO1xuICAgICAgfVxuICAgICAgaWYgKG1ldGFkYXRhLmluaXRpYWxpemVyU2lnbmF0dXJlICE9IG51bGwpIHtcbiAgICAgICAgb3V0LmluaXRpYWxpemVyU2lnbmF0dXJlID0gbWV0YWRhdGEuaW5pdGlhbGl6ZXJTaWduYXR1cmU7XG4gICAgICB9XG4gICAgICBpZiAobWV0YWRhdGEudHJhaW5pbmdDb25maWcgIT0gbnVsbCkge1xuICAgICAgICBvdXQudHJhaW5pbmdDb25maWcgPSBtZXRhZGF0YS50cmFpbmluZ0NvbmZpZztcbiAgICAgIH1cbiAgICB9XG5cbiAgICAvLyBMb2FkIHdlaWdodCBkYXRhLlxuICAgIGNvbnN0IHdlaWdodERhdGFCYXNlNjQgPSB0aGlzLkxTLmdldEl0ZW0odGhpcy5rZXlzLndlaWdodERhdGEpO1xuICAgIGlmICh3ZWlnaHREYXRhQmFzZTY0ID09IG51bGwpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICBgSW4gbG9jYWwgc3RvcmFnZSwgdGhlIGJpbmFyeSB3ZWlnaHQgdmFsdWVzIG9mIG1vZGVsIGAgK1xuICAgICAgICAgIGAnJHt0aGlzLm1vZGVsUGF0aH0nIGFyZSBtaXNzaW5nLmApO1xuICAgIH1cbiAgICBvdXQud2VpZ2h0RGF0YSA9IGJhc2U2NFN0cmluZ1RvQXJyYXlCdWZmZXIod2VpZ2h0RGF0YUJhc2U2NCk7XG5cbiAgICByZXR1cm4gb3V0O1xuICB9XG59XG5cbmV4cG9ydCBjb25zdCBsb2NhbFN0b3JhZ2VSb3V0ZXI6IElPUm91dGVyID0gKHVybDogc3RyaW5nfHN0cmluZ1tdKSA9PiB7XG4gIGlmICghZW52KCkuZ2V0Qm9vbCgnSVNfQlJPV1NFUicpKSB7XG4gICAgcmV0dXJuIG51bGw7XG4gIH0gZWxzZSB7XG4gICAgaWYgKCFBcnJheS5pc0FycmF5KHVybCkgJiYgdXJsLnN0YXJ0c1dpdGgoQnJvd3NlckxvY2FsU3RvcmFnZS5VUkxfU0NIRU1FKSkge1xuICAgICAgcmV0dXJuIGJyb3dzZXJMb2NhbFN0b3JhZ2UoXG4gICAgICAgICAgdXJsLnNsaWNlKEJyb3dzZXJMb2NhbFN0b3JhZ2UuVVJMX1NDSEVNRS5sZW5ndGgpKTtcbiAgICB9IGVsc2Uge1xuICAgICAgcmV0dXJuIG51bGw7XG4gICAgfVxuICB9XG59O1xuSU9Sb3V0ZXJSZWdpc3RyeS5yZWdpc3RlclNhdmVSb3V0ZXIobG9jYWxTdG9yYWdlUm91dGVyKTtcbklPUm91dGVyUmVnaXN0cnkucmVnaXN0ZXJMb2FkUm91dGVyKGxvY2FsU3RvcmFnZVJvdXRlcik7XG5cbi8qKlxuICogRmFjdG9yeSBmdW5jdGlvbiBmb3IgbG9jYWwgc3RvcmFnZSBJT0hhbmRsZXIuXG4gKlxuICogVGhpcyBgSU9IYW5kbGVyYCBzdXBwb3J0cyBib3RoIGBzYXZlYCBhbmQgYGxvYWRgLlxuICpcbiAqIEZvciBlYWNoIG1vZGVsJ3Mgc2F2ZWQgYXJ0aWZhY3RzLCBmb3VyIGl0ZW1zIGFyZSBzYXZlZCB0byBsb2NhbCBzdG9yYWdlLlxuICogICAtIGAke1BBVEhfU0VQQVJBVE9SfS8ke21vZGVsUGF0aH0vaW5mb2A6IENvbnRhaW5zIG1ldGEtaW5mbyBhYm91dCB0aGVcbiAqICAgICBtb2RlbCwgc3VjaCBhcyBkYXRlIHNhdmVkLCB0eXBlIG9mIHRoZSB0b3BvbG9neSwgc2l6ZSBpbiBieXRlcywgZXRjLlxuICogICAtIGAke1BBVEhfU0VQQVJBVE9SfS8ke21vZGVsUGF0aH0vdG9wb2xvZ3lgOiBNb2RlbCB0b3BvbG9neS4gRm9yIEtlcmFzLVxuICogICAgIHN0eWxlIG1vZGVscywgdGhpcyBpcyBhIHN0cmluZ2l6ZWQgSlNPTi5cbiAqICAgLSBgJHtQQVRIX1NFUEFSQVRPUn0vJHttb2RlbFBhdGh9L3dlaWdodF9zcGVjc2A6IFdlaWdodCBzcGVjcyBvZiB0aGVcbiAqICAgICBtb2RlbCwgY2FuIGJlIHVzZWQgdG8gZGVjb2RlIHRoZSBzYXZlZCBiaW5hcnkgd2VpZ2h0IHZhbHVlcyAoc2VlXG4gKiAgICAgaXRlbSBiZWxvdykuXG4gKiAgIC0gYCR7UEFUSF9TRVBBUkFUT1J9LyR7bW9kZWxQYXRofS93ZWlnaHRfZGF0YWA6IENvbmNhdGVuYXRlZCBiaW5hcnlcbiAqICAgICB3ZWlnaHQgdmFsdWVzLCBzdG9yZWQgYXMgYSBiYXNlNjQtZW5jb2RlZCBzdHJpbmcuXG4gKlxuICogU2F2aW5nIG1heSB0aHJvdyBhbiBgRXJyb3JgIGlmIHRoZSB0b3RhbCBzaXplIG9mIHRoZSBhcnRpZmFjdHMgZXhjZWVkIHRoZVxuICogYnJvd3Nlci1zcGVjaWZpYyBxdW90YS5cbiAqXG4gKiBAcGFyYW0gbW9kZWxQYXRoIEEgdW5pcXVlIGlkZW50aWZpZXIgZm9yIHRoZSBtb2RlbCB0byBiZSBzYXZlZC4gTXVzdCBiZSBhXG4gKiAgIG5vbi1lbXB0eSBzdHJpbmcuXG4gKiBAcmV0dXJucyBBbiBpbnN0YW5jZSBvZiBgSU9IYW5kbGVyYCwgd2hpY2ggY2FuIGJlIHVzZWQgd2l0aCwgZS5nLixcbiAqICAgYHRmLk1vZGVsLnNhdmVgLlxuICovXG5leHBvcnQgZnVuY3Rpb24gYnJvd3NlckxvY2FsU3RvcmFnZShtb2RlbFBhdGg6IHN0cmluZyk6IElPSGFuZGxlciB7XG4gIHJldHVybiBuZXcgQnJvd3NlckxvY2FsU3RvcmFnZShtb2RlbFBhdGgpO1xufVxuXG5leHBvcnQgY2xhc3MgQnJvd3NlckxvY2FsU3RvcmFnZU1hbmFnZXIgaW1wbGVtZW50cyBNb2RlbFN0b3JlTWFuYWdlciB7XG4gIHByaXZhdGUgcmVhZG9ubHkgTFM6IFN0b3JhZ2U7XG5cbiAgY29uc3RydWN0b3IoKSB7XG4gICAgYXNzZXJ0KFxuICAgICAgICBlbnYoKS5nZXRCb29sKCdJU19CUk9XU0VSJyksXG4gICAgICAgICgpID0+ICdDdXJyZW50IGVudmlyb25tZW50IGlzIG5vdCBhIHdlYiBicm93c2VyJyk7XG4gICAgYXNzZXJ0KFxuICAgICAgICB0eXBlb2Ygd2luZG93ID09PSAndW5kZWZpbmVkJyB8fFxuICAgICAgICAgICAgdHlwZW9mIHdpbmRvdy5sb2NhbFN0b3JhZ2UgIT09ICd1bmRlZmluZWQnLFxuICAgICAgICAoKSA9PiAnQ3VycmVudCBicm93c2VyIGRvZXMgbm90IGFwcGVhciB0byBzdXBwb3J0IGxvY2FsU3RvcmFnZScpO1xuICAgIHRoaXMuTFMgPSB3aW5kb3cubG9jYWxTdG9yYWdlO1xuICB9XG5cbiAgYXN5bmMgbGlzdE1vZGVscygpOiBQcm9taXNlPHtbcGF0aDogc3RyaW5nXTogTW9kZWxBcnRpZmFjdHNJbmZvfT4ge1xuICAgIGNvbnN0IG91dDoge1twYXRoOiBzdHJpbmddOiBNb2RlbEFydGlmYWN0c0luZm99ID0ge307XG4gICAgY29uc3QgcHJlZml4ID0gUEFUSF9QUkVGSVggKyBQQVRIX1NFUEFSQVRPUjtcbiAgICBjb25zdCBzdWZmaXggPSBQQVRIX1NFUEFSQVRPUiArIElORk9fU1VGRklYO1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgdGhpcy5MUy5sZW5ndGg7ICsraSkge1xuICAgICAgY29uc3Qga2V5ID0gdGhpcy5MUy5rZXkoaSk7XG4gICAgICBpZiAoa2V5LnN0YXJ0c1dpdGgocHJlZml4KSAmJiBrZXkuZW5kc1dpdGgoc3VmZml4KSkge1xuICAgICAgICBjb25zdCBtb2RlbFBhdGggPSBnZXRNb2RlbFBhdGhGcm9tS2V5KGtleSk7XG4gICAgICAgIG91dFttb2RlbFBhdGhdID0gSlNPTi5wYXJzZSh0aGlzLkxTLmdldEl0ZW0oa2V5KSkgYXMgTW9kZWxBcnRpZmFjdHNJbmZvO1xuICAgICAgfVxuICAgIH1cbiAgICByZXR1cm4gb3V0O1xuICB9XG5cbiAgYXN5bmMgcmVtb3ZlTW9kZWwocGF0aDogc3RyaW5nKTogUHJvbWlzZTxNb2RlbEFydGlmYWN0c0luZm8+IHtcbiAgICBwYXRoID0gbWF5YmVTdHJpcFNjaGVtZShwYXRoKTtcbiAgICBjb25zdCBrZXlzID0gZ2V0TW9kZWxLZXlzKHBhdGgpO1xuICAgIGlmICh0aGlzLkxTLmdldEl0ZW0oa2V5cy5pbmZvKSA9PSBudWxsKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoYENhbm5vdCBmaW5kIG1vZGVsIGF0IHBhdGggJyR7cGF0aH0nYCk7XG4gICAgfVxuICAgIGNvbnN0IGluZm8gPSBKU09OLnBhcnNlKHRoaXMuTFMuZ2V0SXRlbShrZXlzLmluZm8pKSBhcyBNb2RlbEFydGlmYWN0c0luZm87XG4gICAgcmVtb3ZlSXRlbXMoa2V5cyk7XG4gICAgcmV0dXJuIGluZm87XG4gIH1cbn1cbiJdfQ==