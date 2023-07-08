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
import { getModelArtifactsInfoForJSON } from './io_utils';
import { IORouterRegistry } from './router_registry';
import { CompositeArrayBuffer } from './composite_array_buffer';
const DATABASE_NAME = 'tensorflowjs';
const DATABASE_VERSION = 1;
// Model data and ModelArtifactsInfo (metadata) are stored in two separate
// stores for efficient access of the list of stored models and their metadata.
// 1. The object store for model data: topology, weights and weight manifests.
const MODEL_STORE_NAME = 'models_store';
// 2. The object store for ModelArtifactsInfo, including meta-information such
//    as the type of topology (JSON vs binary), byte size of the topology, byte
//    size of the weights, etc.
const INFO_STORE_NAME = 'model_info_store';
/**
 * Delete the entire database for tensorflow.js, including the models store.
 */
export async function deleteDatabase() {
    const idbFactory = getIndexedDBFactory();
    return new Promise((resolve, reject) => {
        const deleteRequest = idbFactory.deleteDatabase(DATABASE_NAME);
        deleteRequest.onsuccess = () => resolve();
        deleteRequest.onerror = error => reject(error);
    });
}
function getIndexedDBFactory() {
    if (!env().getBool('IS_BROWSER')) {
        // TODO(cais): Add more info about what IOHandler subtypes are available.
        //   Maybe point to a doc page on the web and/or automatically determine
        //   the available IOHandlers and print them in the error message.
        throw new Error('Failed to obtain IndexedDB factory because the current environment' +
            'is not a web browser.');
    }
    // tslint:disable-next-line:no-any
    const theWindow = typeof window === 'undefined' ? self : window;
    const factory = theWindow.indexedDB || theWindow.mozIndexedDB ||
        theWindow.webkitIndexedDB || theWindow.msIndexedDB ||
        theWindow.shimIndexedDB;
    if (factory == null) {
        throw new Error('The current browser does not appear to support IndexedDB.');
    }
    return factory;
}
function setUpDatabase(openRequest) {
    const db = openRequest.result;
    db.createObjectStore(MODEL_STORE_NAME, { keyPath: 'modelPath' });
    db.createObjectStore(INFO_STORE_NAME, { keyPath: 'modelPath' });
}
/**
 * IOHandler subclass: Browser IndexedDB.
 *
 * See the doc string of `browserIndexedDB` for more details.
 */
export class BrowserIndexedDB {
    constructor(modelPath) {
        this.indexedDB = getIndexedDBFactory();
        if (modelPath == null || !modelPath) {
            throw new Error('For IndexedDB, modelPath must not be null, undefined or empty.');
        }
        this.modelPath = modelPath;
    }
    async save(modelArtifacts) {
        // TODO(cais): Support saving GraphDef models.
        if (modelArtifacts.modelTopology instanceof ArrayBuffer) {
            throw new Error('BrowserLocalStorage.save() does not support saving model topology ' +
                'in binary formats yet.');
        }
        return this.databaseAction(this.modelPath, modelArtifacts);
    }
    async load() {
        return this.databaseAction(this.modelPath);
    }
    /**
     * Perform database action to put model artifacts into or read model artifacts
     * from IndexedDB object store.
     *
     * Whether the action is put or get depends on whether `modelArtifacts` is
     * specified. If it is specified, the action will be put; otherwise the action
     * will be get.
     *
     * @param modelPath A unique string path for the model.
     * @param modelArtifacts If specified, it will be the model artifacts to be
     *   stored in IndexedDB.
     * @returns A `Promise` of `SaveResult`, if the action is put, or a `Promise`
     *   of `ModelArtifacts`, if the action is get.
     */
    databaseAction(modelPath, modelArtifacts) {
        return new Promise((resolve, reject) => {
            const openRequest = this.indexedDB.open(DATABASE_NAME, DATABASE_VERSION);
            openRequest.onupgradeneeded = () => setUpDatabase(openRequest);
            openRequest.onsuccess = () => {
                const db = openRequest.result;
                if (modelArtifacts == null) {
                    // Read model out from object store.
                    const modelTx = db.transaction(MODEL_STORE_NAME, 'readonly');
                    const modelStore = modelTx.objectStore(MODEL_STORE_NAME);
                    const getRequest = modelStore.get(this.modelPath);
                    getRequest.onsuccess = () => {
                        if (getRequest.result == null) {
                            db.close();
                            return reject(new Error(`Cannot find model with path '${this.modelPath}' ` +
                                `in IndexedDB.`));
                        }
                        else {
                            resolve(getRequest.result.modelArtifacts);
                        }
                    };
                    getRequest.onerror = error => {
                        db.close();
                        return reject(getRequest.error);
                    };
                    modelTx.oncomplete = () => db.close();
                }
                else {
                    // Put model into object store.
                    // Concatenate all the model weights into a single ArrayBuffer. Large
                    // models (~1GB) have problems saving if they are not concatenated.
                    // TODO(mattSoulanille): Save large models to multiple indexeddb
                    // records.
                    modelArtifacts.weightData = CompositeArrayBuffer.join(modelArtifacts.weightData);
                    const modelArtifactsInfo = getModelArtifactsInfoForJSON(modelArtifacts);
                    // First, put ModelArtifactsInfo into info store.
                    const infoTx = db.transaction(INFO_STORE_NAME, 'readwrite');
                    let infoStore = infoTx.objectStore(INFO_STORE_NAME);
                    let putInfoRequest;
                    try {
                        putInfoRequest =
                            infoStore.put({ modelPath: this.modelPath, modelArtifactsInfo });
                    }
                    catch (error) {
                        return reject(error);
                    }
                    let modelTx;
                    putInfoRequest.onsuccess = () => {
                        // Second, put model data into model store.
                        modelTx = db.transaction(MODEL_STORE_NAME, 'readwrite');
                        const modelStore = modelTx.objectStore(MODEL_STORE_NAME);
                        let putModelRequest;
                        try {
                            putModelRequest = modelStore.put({
                                modelPath: this.modelPath,
                                modelArtifacts,
                                modelArtifactsInfo
                            });
                        }
                        catch (error) {
                            // Sometimes, the serialized value is too large to store.
                            return reject(error);
                        }
                        putModelRequest.onsuccess = () => resolve({ modelArtifactsInfo });
                        putModelRequest.onerror = error => {
                            // If the put-model request fails, roll back the info entry as
                            // well.
                            infoStore = infoTx.objectStore(INFO_STORE_NAME);
                            const deleteInfoRequest = infoStore.delete(this.modelPath);
                            deleteInfoRequest.onsuccess = () => {
                                db.close();
                                return reject(putModelRequest.error);
                            };
                            deleteInfoRequest.onerror = error => {
                                db.close();
                                return reject(putModelRequest.error);
                            };
                        };
                    };
                    putInfoRequest.onerror = error => {
                        db.close();
                        return reject(putInfoRequest.error);
                    };
                    infoTx.oncomplete = () => {
                        if (modelTx == null) {
                            db.close();
                        }
                        else {
                            modelTx.oncomplete = () => db.close();
                        }
                    };
                }
            };
            openRequest.onerror = error => reject(openRequest.error);
        });
    }
}
BrowserIndexedDB.URL_SCHEME = 'indexeddb://';
export const indexedDBRouter = (url) => {
    if (!env().getBool('IS_BROWSER')) {
        return null;
    }
    else {
        if (!Array.isArray(url) && url.startsWith(BrowserIndexedDB.URL_SCHEME)) {
            return browserIndexedDB(url.slice(BrowserIndexedDB.URL_SCHEME.length));
        }
        else {
            return null;
        }
    }
};
IORouterRegistry.registerSaveRouter(indexedDBRouter);
IORouterRegistry.registerLoadRouter(indexedDBRouter);
/**
 * Creates a browser IndexedDB IOHandler for saving and loading models.
 *
 * ```js
 * const model = tf.sequential();
 * model.add(
 *     tf.layers.dense({units: 1, inputShape: [100], activation: 'sigmoid'}));
 *
 * const saveResult = await model.save('indexeddb://MyModel'));
 * console.log(saveResult);
 * ```
 *
 * @param modelPath A unique identifier for the model to be saved. Must be a
 *   non-empty string.
 * @returns An instance of `BrowserIndexedDB` (sublcass of `IOHandler`),
 *   which can be used with, e.g., `tf.Model.save`.
 */
export function browserIndexedDB(modelPath) {
    return new BrowserIndexedDB(modelPath);
}
function maybeStripScheme(key) {
    return key.startsWith(BrowserIndexedDB.URL_SCHEME) ?
        key.slice(BrowserIndexedDB.URL_SCHEME.length) :
        key;
}
export class BrowserIndexedDBManager {
    constructor() {
        this.indexedDB = getIndexedDBFactory();
    }
    async listModels() {
        return new Promise((resolve, reject) => {
            const openRequest = this.indexedDB.open(DATABASE_NAME, DATABASE_VERSION);
            openRequest.onupgradeneeded = () => setUpDatabase(openRequest);
            openRequest.onsuccess = () => {
                const db = openRequest.result;
                const tx = db.transaction(INFO_STORE_NAME, 'readonly');
                const store = tx.objectStore(INFO_STORE_NAME);
                // tslint:disable:max-line-length
                // Need to cast `store` as `any` here because TypeScript's DOM
                // library does not have the `getAll()` method even though the
                // method is supported in the latest version of most mainstream
                // browsers:
                // https://developer.mozilla.org/en-US/docs/Web/API/IDBObjectStore/getAll
                // tslint:enable:max-line-length
                // tslint:disable-next-line:no-any
                const getAllInfoRequest = store.getAll();
                getAllInfoRequest.onsuccess = () => {
                    const out = {};
                    for (const item of getAllInfoRequest.result) {
                        out[item.modelPath] = item.modelArtifactsInfo;
                    }
                    resolve(out);
                };
                getAllInfoRequest.onerror = error => {
                    db.close();
                    return reject(getAllInfoRequest.error);
                };
                tx.oncomplete = () => db.close();
            };
            openRequest.onerror = error => reject(openRequest.error);
        });
    }
    async removeModel(path) {
        path = maybeStripScheme(path);
        return new Promise((resolve, reject) => {
            const openRequest = this.indexedDB.open(DATABASE_NAME, DATABASE_VERSION);
            openRequest.onupgradeneeded = () => setUpDatabase(openRequest);
            openRequest.onsuccess = () => {
                const db = openRequest.result;
                const infoTx = db.transaction(INFO_STORE_NAME, 'readwrite');
                const infoStore = infoTx.objectStore(INFO_STORE_NAME);
                const getInfoRequest = infoStore.get(path);
                let modelTx;
                getInfoRequest.onsuccess = () => {
                    if (getInfoRequest.result == null) {
                        db.close();
                        return reject(new Error(`Cannot find model with path '${path}' ` +
                            `in IndexedDB.`));
                    }
                    else {
                        // First, delete the entry in the info store.
                        const deleteInfoRequest = infoStore.delete(path);
                        const deleteModelData = () => {
                            // Second, delete the entry in the model store.
                            modelTx = db.transaction(MODEL_STORE_NAME, 'readwrite');
                            const modelStore = modelTx.objectStore(MODEL_STORE_NAME);
                            const deleteModelRequest = modelStore.delete(path);
                            deleteModelRequest.onsuccess = () => resolve(getInfoRequest.result.modelArtifactsInfo);
                            deleteModelRequest.onerror = error => reject(getInfoRequest.error);
                        };
                        // Proceed with deleting model data regardless of whether deletion
                        // of info data succeeds or not.
                        deleteInfoRequest.onsuccess = deleteModelData;
                        deleteInfoRequest.onerror = error => {
                            deleteModelData();
                            db.close();
                            return reject(getInfoRequest.error);
                        };
                    }
                };
                getInfoRequest.onerror = error => {
                    db.close();
                    return reject(getInfoRequest.error);
                };
                infoTx.oncomplete = () => {
                    if (modelTx == null) {
                        db.close();
                    }
                    else {
                        modelTx.oncomplete = () => db.close();
                    }
                };
            };
            openRequest.onerror = error => reject(openRequest.error);
        });
    }
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiaW5kZXhlZF9kYi5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uL3RmanMtY29yZS9zcmMvaW8vaW5kZXhlZF9kYi50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7O0dBZUc7QUFFSCxPQUFPLFVBQVUsQ0FBQztBQUVsQixPQUFPLEVBQUMsR0FBRyxFQUFDLE1BQU0sZ0JBQWdCLENBQUM7QUFFbkMsT0FBTyxFQUFDLDRCQUE0QixFQUFDLE1BQU0sWUFBWSxDQUFDO0FBQ3hELE9BQU8sRUFBVyxnQkFBZ0IsRUFBQyxNQUFNLG1CQUFtQixDQUFDO0FBRTdELE9BQU8sRUFBQyxvQkFBb0IsRUFBQyxNQUFNLDBCQUEwQixDQUFDO0FBRTlELE1BQU0sYUFBYSxHQUFHLGNBQWMsQ0FBQztBQUNyQyxNQUFNLGdCQUFnQixHQUFHLENBQUMsQ0FBQztBQUUzQiwwRUFBMEU7QUFDMUUsK0VBQStFO0FBQy9FLDhFQUE4RTtBQUM5RSxNQUFNLGdCQUFnQixHQUFHLGNBQWMsQ0FBQztBQUN4Qyw4RUFBOEU7QUFDOUUsK0VBQStFO0FBQy9FLCtCQUErQjtBQUMvQixNQUFNLGVBQWUsR0FBRyxrQkFBa0IsQ0FBQztBQUUzQzs7R0FFRztBQUNILE1BQU0sQ0FBQyxLQUFLLFVBQVUsY0FBYztJQUNsQyxNQUFNLFVBQVUsR0FBRyxtQkFBbUIsRUFBRSxDQUFDO0lBRXpDLE9BQU8sSUFBSSxPQUFPLENBQU8sQ0FBQyxPQUFPLEVBQUUsTUFBTSxFQUFFLEVBQUU7UUFDM0MsTUFBTSxhQUFhLEdBQUcsVUFBVSxDQUFDLGNBQWMsQ0FBQyxhQUFhLENBQUMsQ0FBQztRQUMvRCxhQUFhLENBQUMsU0FBUyxHQUFHLEdBQUcsRUFBRSxDQUFDLE9BQU8sRUFBRSxDQUFDO1FBQzFDLGFBQWEsQ0FBQyxPQUFPLEdBQUcsS0FBSyxDQUFDLEVBQUUsQ0FBQyxNQUFNLENBQUMsS0FBSyxDQUFDLENBQUM7SUFDakQsQ0FBQyxDQUFD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QUFFO3dCQUNuQixFQUFFLENBQUMsS0FBSyxFQUFFLENBQUM7cUJBQ1o7eUJBQU07d0JBQ0wsT0FBTyxDQUFDLFVBQVUsR0FBRyxHQUFHLEVBQUUsQ0FBQyxFQUFFLENBQUMsS0FBSyxFQUFFLENBQUM7cUJBQ3ZDO2dCQUNILENBQUMsQ0FBQztZQUNKLENBQUMsQ0FBQztZQUNGLFdBQVcsQ0FBQyxPQUFPLEdBQUcsS0FBSyxDQUFDLEVBQUUsQ0FBQyxNQUFNLENBQUMsV0FBVyxDQUFDLEtBQUssQ0FBQyxDQUFDO1FBQzNELENBQUMsQ0FBQyxDQUFDO0lBQ0wsQ0FBQztDQUNGIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMTggR29vZ2xlIExMQy4gQWxsIFJpZ2h0cyBSZXNlcnZlZC5cbiAqIExpY2Vuc2VkIHVuZGVyIHRoZSBBcGFjaGUgTGljZW5zZSwgVmVyc2lvbiAyLjAgKHRoZSBcIkxpY2Vuc2VcIik7XG4gKiB5b3UgbWF5IG5vdCB1c2UgdGhpcyBmaWxlIGV4Y2VwdCBpbiBjb21wbGlhbmNlIHdpdGggdGhlIExpY2Vuc2UuXG4gKiBZb3UgbWF5IG9idGFpbiBhIGNvcHkgb2YgdGhlIExpY2Vuc2UgYXRcbiAqXG4gKiBodHRwOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcbiAqXG4gKiBVbmxlc3MgcmVxdWlyZWQgYnkgYXBwbGljYWJsZSBsYXcgb3IgYWdyZWVkIHRvIGluIHdyaXRpbmcsIHNvZnR3YXJlXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXG4gKiBXSVRIT1VUIFdBUlJBTlRJRVMgT1IgQ09ORElUSU9OUyBPRiBBTlkgS0lORCwgZWl0aGVyIGV4cHJlc3Mgb3IgaW1wbGllZC5cbiAqIFNlZSB0aGUgTGljZW5zZSBmb3IgdGhlIHNwZWNpZmljIGxhbmd1YWdlIGdvdmVybmluZyBwZXJtaXNzaW9ucyBhbmRcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxuICogPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cbiAqL1xuXG5pbXBvcnQgJy4uL2ZsYWdzJztcblxuaW1wb3J0IHtlbnZ9IGZyb20gJy4uL2Vudmlyb25tZW50JztcblxuaW1wb3J0IHtnZXRNb2RlbEFydGlmYWN0c0luZm9Gb3JKU09OfSBmcm9tICcuL2lvX3V0aWxzJztcbmltcG9ydCB7SU9Sb3V0ZXIsIElPUm91dGVyUmVnaXN0cnl9IGZyb20gJy4vcm91dGVyX3JlZ2lzdHJ5JztcbmltcG9ydCB7SU9IYW5kbGVyLCBNb2RlbEFydGlmYWN0cywgTW9kZWxBcnRpZmFjdHNJbmZvLCBNb2RlbFN0b3JlTWFuYWdlciwgU2F2ZVJlc3VsdH0gZnJvbSAnLi90eXBlcyc7XG5pbXBvcnQge0NvbXBvc2l0ZUFycmF5QnVmZmVyfSBmcm9tICcuL2NvbXBvc2l0ZV9hcnJheV9idWZmZXInO1xuXG5jb25zdCBEQVRBQkFTRV9OQU1FID0gJ3RlbnNvcmZsb3dqcyc7XG5jb25zdCBEQVRBQkFTRV9WRVJTSU9OID0gMTtcblxuLy8gTW9kZWwgZGF0YSBhbmQgTW9kZWxBcnRpZmFjdHNJbmZvIChtZXRhZGF0YSkgYXJlIHN0b3JlZCBpbiB0d28gc2VwYXJhdGVcbi8vIHN0b3JlcyBmb3IgZWZmaWNpZW50IGFjY2VzcyBvZiB0aGUgbGlzdCBvZiBzdG9yZWQgbW9kZWxzIGFuZCB0aGVpciBtZXRhZGF0YS5cbi8vIDEuIFRoZSBvYmplY3Qgc3RvcmUgZm9yIG1vZGVsIGRhdGE6IHRvcG9sb2d5LCB3ZWlnaHRzIGFuZCB3ZWlnaHQgbWFuaWZlc3RzLlxuY29uc3QgTU9ERUxfU1RPUkVfTkFNRSA9ICdtb2RlbHNfc3RvcmUnO1xuLy8gMi4gVGhlIG9iamVjdCBzdG9yZSBmb3IgTW9kZWxBcnRpZmFjdHNJbmZvLCBpbmNsdWRpbmcgbWV0YS1pbmZvcm1hdGlvbiBzdWNoXG4vLyAgICBhcyB0aGUgdHlwZSBvZiB0b3BvbG9neSAoSlNPTiB2cyBiaW5hcnkpLCBieXRlIHNpemUgb2YgdGhlIHRvcG9sb2d5LCBieXRlXG4vLyAgICBzaXplIG9mIHRoZSB3ZWlnaHRzLCBldGMuXG5jb25zdCBJTkZPX1NUT1JFX05BTUUgPSAnbW9kZWxfaW5mb19zdG9yZSc7XG5cbi8qKlxuICogRGVsZXRlIHRoZSBlbnRpcmUgZGF0YWJhc2UgZm9yIHRlbnNvcmZsb3cuanMsIGluY2x1ZGluZyB0aGUgbW9kZWxzIHN0b3JlLlxuICovXG5leHBvcnQgYXN5bmMgZnVuY3Rpb24gZGVsZXRlRGF0YWJhc2UoKTogUHJvbWlzZTx2b2lkPiB7XG4gIGNvbnN0IGlkYkZhY3RvcnkgPSBnZXRJbmRleGVkREJGYWN0b3J5KCk7XG5cbiAgcmV0dXJuIG5ldyBQcm9taXNlPHZvaWQ+KChyZXNvbHZlLCByZWplY3QpID0+IHtcbiAgICBjb25zdCBkZWxldGVSZXF1ZXN0ID0gaWRiRmFjdG9yeS5kZWxldGVEYXRhYmFzZShEQVRBQkFTRV9OQU1FKTtcbiAgICBkZWxldGVSZXF1ZXN0Lm9uc3VjY2VzcyA9ICgpID0+IHJlc29sdmUoKTtcbiAgICBkZWxldGVSZXF1ZXN0Lm9uZXJyb3IgPSBlcnJvciA9PiByZWplY3QoZXJyb3IpO1xuICB9KTtcbn1cblxuZnVuY3Rpb24gZ2V0SW5kZXhlZERCRmFjdG9yeSgpOiBJREJGYWN0b3J5IHtcbiAgaWYgKCFlbnYoKS5nZXRCb29sKCdJU19CUk9XU0VSJykpIHtcbiAgICAvLyBUT0RPKGNhaXMpOiBBZGQgbW9yZSBpbmZvIGFib3V0IHdoYXQgSU9IYW5kbGVyIHN1YnR5cGVzIGFyZSBhdmFpbGFibGUuXG4gICAgLy8gICBNYXliZSBwb2ludCB0byBhIGRvYyBwYWdlIG9uIHRoZSB3ZWIgYW5kL29yIGF1dG9tYXRpY2FsbHkgZGV0ZXJtaW5lXG4gICAgLy8gICB0aGUgYXZhaWxhYmxlIElPSGFuZGxlcnMgYW5kIHByaW50IHRoZW0gaW4gdGhlIGVycm9yIG1lc3NhZ2UuXG4gICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAnRmFpbGVkIHRvIG9idGFpbiBJbmRleGVkREIgZmFjdG9yeSBiZWNhdXNlIHRoZSBjdXJyZW50IGVudmlyb25tZW50JyArXG4gICAgICAgICdpcyBub3QgYSB3ZWIgYnJvd3Nlci4nKTtcbiAgfVxuICAvLyB0c2xpbnQ6ZGlzYWJsZS1uZXh0LWxpbmU6bm8tYW55XG4gIGNvbnN0IHRoZVdpbmRvdzogYW55ID0gdHlwZW9mIHdpbmRvdyA9PT0gJ3VuZGVmaW5lZCcgPyBzZWxmIDogd2luZG93O1xuICBjb25zdCBmYWN0b3J5ID0gdGhlV2luZG93LmluZGV4ZWREQiB8fCB0aGVXaW5kb3cubW96SW5kZXhlZERCIHx8XG4gICAgICB0aGVXaW5kb3cud2Via2l0SW5kZXhlZERCIHx8IHRoZVdpbmRvdy5tc0luZGV4ZWREQiB8fFxuICAgICAgdGhlV2luZG93LnNoaW1JbmRleGVkREI7XG4gIGlmIChmYWN0b3J5ID09IG51bGwpIHtcbiAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICdUaGUgY3VycmVudCBicm93c2VyIGRvZXMgbm90IGFwcGVhciB0byBzdXBwb3J0IEluZGV4ZWREQi4nKTtcbiAgfVxuICByZXR1cm4gZmFjdG9yeTtcbn1cblxuZnVuY3Rpb24gc2V0VXBEYXRhYmFzZShvcGVuUmVxdWVzdDogSURCUmVxdWVzdCkge1xuICBjb25zdCBkYiA9IG9wZW5SZXF1ZXN0LnJlc3VsdCBhcyBJREJEYXRhYmFzZTtcbiAgZGIuY3JlYXRlT2JqZWN0U3RvcmUoTU9ERUxfU1RPUkVfTkFNRSwge2tleVBhdGg6ICdtb2RlbFBhdGgnfSk7XG4gIGRiLmNyZWF0ZU9iamVjdFN0b3JlKElORk9fU1RPUkVfTkFNRSwge2tleVBhdGg6ICdtb2RlbFBhdGgnfSk7XG59XG5cbi8qKlxuICogSU9IYW5kbGVyIHN1YmNsYXNzOiBCcm93c2VyIEluZGV4ZWREQi5cbiAqXG4gKiBTZWUgdGhlIGRvYyBzdHJpbmcgb2YgYGJyb3dzZXJJbmRleGVkREJgIGZvciBtb3JlIGRldGFpbHMuXG4gKi9cbmV4cG9ydCBjbGFzcyBCcm93c2VySW5kZXhlZERCIGltcGxlbWVudHMgSU9IYW5kbGVyIHtcbiAgcHJvdGVjdGVkIHJlYWRvbmx5IGluZGV4ZWREQjogSURCRmFjdG9yeTtcbiAgcHJvdGVjdGVkIHJlYWRvbmx5IG1vZGVsUGF0aDogc3RyaW5nO1xuXG4gIHN0YXRpYyByZWFkb25seSBVUkxfU0NIRU1FID0gJ2luZGV4ZWRkYjovLyc7XG5cbiAgY29uc3RydWN0b3IobW9kZWxQYXRoOiBzdHJpbmcpIHtcbiAgICB0aGlzLmluZGV4ZWREQiA9IGdldEluZGV4ZWREQkZhY3RvcnkoKTtcblxuICAgIGlmIChtb2RlbFBhdGggPT0gbnVsbCB8fCAhbW9kZWxQYXRoKSB7XG4gICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgJ0ZvciBJbmRleGVkREIsIG1vZGVsUGF0aCBtdXN0IG5vdCBiZSBudWxsLCB1bmRlZmluZWQgb3IgZW1wdHkuJyk7XG4gICAgfVxuICAgIHRoaXMubW9kZWxQYXRoID0gbW9kZWxQYXRoO1xuICB9XG5cbiAgYXN5bmMgc2F2ZShtb2RlbEFydGlmYWN0czogTW9kZWxBcnRpZmFjdHMpOiBQcm9taXNlPFNhdmVSZXN1bHQ+IHtcbiAgICAvLyBUT0RPKGNhaXMpOiBTdXBwb3J0IHNhdmluZyBHcmFwaERlZiBtb2RlbHMuXG4gICAgaWYgKG1vZGVsQXJ0aWZhY3RzLm1vZGVsVG9wb2xvZ3kgaW5zdGFuY2VvZiBBcnJheUJ1ZmZlcikge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgICdCcm93c2VyTG9jYWxTdG9yYWdlLnNhdmUoKSBkb2VzIG5vdCBzdXBwb3J0IHNhdmluZyBtb2RlbCB0b3BvbG9neSAnICtcbiAgICAgICAgICAnaW4gYmluYXJ5IGZvcm1hdHMgeWV0LicpO1xuICAgIH1cblxuICAgIHJldHVybiB0aGlzLmRhdGFiYXNlQWN0aW9uKHRoaXMubW9kZWxQYXRoLCBtb2RlbEFydGlmYWN0cykgYXNcbiAgICAgICAgUHJvbWlzZTxTYXZlUmVzdWx0PjtcbiAgfVxuXG4gIGFzeW5jIGxvYWQoKTogUHJvbWlzZTxNb2RlbEFydGlmYWN0cz4ge1xuICAgIHJldHVybiB0aGlzLmRhdGFiYXNlQWN0aW9uKHRoaXMubW9kZWxQYXRoKSBhcyBQcm9taXNlPE1vZGVsQXJ0aWZhY3RzPjtcbiAgfVxuXG4gIC8qKlxuICAgKiBQZXJmb3JtIGRhdGFiYXNlIGFjdGlvbiB0byBwdXQgbW9kZWwgYXJ0aWZhY3RzIGludG8gb3IgcmVhZCBtb2RlbCBhcnRpZmFjdHNcbiAgICogZnJvbSBJbmRleGVkREIgb2JqZWN0IHN0b3JlLlxuICAgKlxuICAgKiBXaGV0aGVyIHRoZSBhY3Rpb24gaXMgcHV0IG9yIGdldCBkZXBlbmRzIG9uIHdoZXRoZXIgYG1vZGVsQXJ0aWZhY3RzYCBpc1xuICAgKiBzcGVjaWZpZWQuIElmIGl0IGlzIHNwZWNpZmllZCwgdGhlIGFjdGlvbiB3aWxsIGJlIHB1dDsgb3RoZXJ3aXNlIHRoZSBhY3Rpb25cbiAgICogd2lsbCBiZSBnZXQuXG4gICAqXG4gICAqIEBwYXJhbSBtb2RlbFBhdGggQSB1bmlxdWUgc3RyaW5nIHBhdGggZm9yIHRoZSBtb2RlbC5cbiAgICogQHBhcmFtIG1vZGVsQXJ0aWZhY3RzIElmIHNwZWNpZmllZCwgaXQgd2lsbCBiZSB0aGUgbW9kZWwgYXJ0aWZhY3RzIHRvIGJlXG4gICAqICAgc3RvcmVkIGluIEluZGV4ZWREQi5cbiAgICogQHJldHVybnMgQSBgUHJvbWlzZWAgb2YgYFNhdmVSZXN1bHRgLCBpZiB0aGUgYWN0aW9uIGlzIHB1dCwgb3IgYSBgUHJvbWlzZWBcbiAgICogICBvZiBgTW9kZWxBcnRpZmFjdHNgLCBpZiB0aGUgYWN0aW9uIGlzIGdldC5cbiAgICovXG4gIHByaXZhdGUgZGF0YWJhc2VBY3Rpb24obW9kZWxQYXRoOiBzdHJpbmcsIG1vZGVsQXJ0aWZhY3RzPzogTW9kZWxBcnRpZmFjdHMpOlxuICAgICAgUHJvbWlzZTxNb2RlbEFydGlmYWN0c3xTYXZlUmVzdWx0PiB7XG4gICAgcmV0dXJuIG5ldyBQcm9taXNlPE1vZGVsQXJ0aWZhY3RzfFNhdmVSZXN1bHQ+KChyZXNvbHZlLCByZWplY3QpID0+IHtcbiAgICAgIGNvbnN0IG9wZW5SZXF1ZXN0ID0gdGhpcy5pbmRleGVkREIub3BlbihEQVRBQkFTRV9OQU1FLCBEQVRBQkFTRV9WRVJTSU9OKTtcbiAgICAgIG9wZW5SZXF1ZXN0Lm9udXBncmFkZW5lZWRlZCA9ICgpID0+IHNldFVwRGF0YWJhc2Uob3BlblJlcXVlc3QpO1xuXG4gICAgICBvcGVuUmVxdWVzdC5vbnN1Y2Nlc3MgPSAoKSA9PiB7XG4gICAgICAgIGNvbnN0IGRiID0gb3BlblJlcXVlc3QucmVzdWx0O1xuXG4gICAgICAgIGlmIChtb2RlbEFydGlmYWN0cyA9PSBudWxsKSB7XG4gICAgICAgICAgLy8gUmVhZCBtb2RlbCBvdXQgZnJvbSBvYmplY3Qgc3RvcmUuXG4gICAgICAgICAgY29uc3QgbW9kZWxUeCA9IGRiLnRyYW5zYWN0aW9uKE1PREVMX1NUT1JFX05BTUUsICdyZWFkb25seScpO1xuICAgICAgICAgIGNvbnN0IG1vZGVsU3RvcmUgPSBtb2RlbFR4Lm9iamVjdFN0b3JlKE1PREVMX1NUT1JFX05BTUUpO1xuICAgICAgICAgIGNvbnN0IGdldFJlcXVlc3QgPSBtb2RlbFN0b3JlLmdldCh0aGlzLm1vZGVsUGF0aCk7XG4gICAgICAgICAgZ2V0UmVxdWVzdC5vbnN1Y2Nlc3MgPSAoKSA9PiB7XG4gICAgICAgICAgICBpZiAoZ2V0UmVxdWVzdC5yZXN1bHQgPT0gbnVsbCkge1xuICAgICAgICAgICAgICBkYi5jbG9zZSgpO1xuICAgICAgICAgICAgICByZXR1cm4gcmVqZWN0KG5ldyBFcnJvcihcbiAgICAgICAgICAgICAgICAgIGBDYW5ub3QgZmluZCBtb2RlbCB3aXRoIHBhdGggJyR7dGhpcy5tb2RlbFBhdGh9JyBgICtcbiAgICAgICAgICAgICAgICAgIGBpbiBJbmRleGVkREIuYCkpO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgcmVzb2x2ZShnZXRSZXF1ZXN0LnJlc3VsdC5tb2RlbEFydGlmYWN0cyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfTtcbiAgICAgICAgICBnZXRSZXF1ZXN0Lm9uZXJyb3IgPSBlcnJvciA9PiB7XG4gICAgICAgICAgICBkYi5jbG9zZSgpO1xuICAgICAgICAgICAgcmV0dXJuIHJlamVjdChnZXRSZXF1ZXN0LmVycm9yKTtcbiAgICAgICAgICB9O1xuICAgICAgICAgIG1vZGVsVHgub25jb21wbGV0ZSA9ICgpID0+IGRiLmNsb3NlKCk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgLy8gUHV0IG1vZGVsIGludG8gb2JqZWN0IHN0b3JlLlxuXG4gICAgICAgICAgLy8gQ29uY2F0ZW5hdGUgYWxsIHRoZSBtb2RlbCB3ZWlnaHRzIGludG8gYSBzaW5nbGUgQXJyYXlCdWZmZXIuIExhcmdlXG4gICAgICAgICAgLy8gbW9kZWxzICh+MUdCKSBoYXZlIHByb2JsZW1zIHNhdmluZyBpZiB0aGV5IGFyZSBub3QgY29uY2F0ZW5hdGVkLlxuICAgICAgICAgIC8vIFRPRE8obWF0dFNvdWxhbmlsbGUpOiBTYXZlIGxhcmdlIG1vZGVscyB0byBtdWx0aXBsZSBpbmRleGVkZGJcbiAgICAgICAgICAvLyByZWNvcmRzLlxuICAgICAgICAgIG1vZGVsQXJ0aWZhY3RzLndlaWdodERhdGEgPSBDb21wb3NpdGVBcnJheUJ1ZmZlci5qb2luKFxuICAgICAgICAgICAgICBtb2RlbEFydGlmYWN0cy53ZWlnaHREYXRhKTtcbiAgICAgICAgICBjb25zdCBtb2RlbEFydGlmYWN0c0luZm86IE1vZGVsQXJ0aWZhY3RzSW5mbyA9XG4gICAgICAgICAgICAgIGdldE1vZGVsQXJ0aWZhY3RzSW5mb0ZvckpTT04obW9kZWxBcnRpZmFjdHMpO1xuICAgICAgICAgIC8vIEZpcnN0LCBwdXQgTW9kZWxBcnRpZmFjdHNJbmZvIGludG8gaW5mbyBzdG9yZS5cbiAgICAgICAgICBjb25zdCBpbmZvVHggPSBkYi50cmFuc2FjdGlvbihJTkZPX1NUT1JFX05BTUUsICdyZWFkd3JpdGUnKTtcbiAgICAgICAgICBsZXQgaW5mb1N0b3JlID0gaW5mb1R4Lm9iamVjdFN0b3JlKElORk9fU1RPUkVfTkFNRSk7XG4gICAgICAgICAgbGV0IHB1dEluZm9SZXF1ZXN0OiBJREJSZXF1ZXN0PElEQlZhbGlkS2V5PjtcbiAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgcHV0SW5mb1JlcXVlc3QgPVxuICAgICAgICAgICAgICBpbmZvU3RvcmUucHV0KHttb2RlbFBhdGg6IHRoaXMubW9kZWxQYXRoLCBtb2RlbEFydGlmYWN0c0luZm99KTtcbiAgICAgICAgICB9IGNhdGNoIChlcnJvcikge1xuICAgICAgICAgICAgcmV0dXJuIHJlamVjdChlcnJvcik7XG4gICAgICAgICAgfVxuICAgICAgICAgIGxldCBtb2RlbFR4OiBJREJUcmFuc2FjdGlvbjtcbiAgICAgICAgICBwdXRJbmZvUmVxdWVzdC5vbnN1Y2Nlc3MgPSAoKSA9PiB7XG4gICAgICAgICAgICAvLyBTZWNvbmQsIHB1dCBtb2RlbCBkYXRhIGludG8gbW9kZWwgc3RvcmUuXG4gICAgICAgICAgICBtb2RlbFR4ID0gZGIudHJhbnNhY3Rpb24oTU9ERUxfU1RPUkVfTkFNRSwgJ3JlYWR3cml0ZScpO1xuICAgICAgICAgICAgY29uc3QgbW9kZWxTdG9yZSA9IG1vZGVsVHgub2JqZWN0U3RvcmUoTU9ERUxfU1RPUkVfTkFNRSk7XG4gICAgICAgICAgICBsZXQgcHV0TW9kZWxSZXF1ZXN0OiBJREJSZXF1ZXN0PElEQlZhbGlkS2V5PjtcbiAgICAgICAgICAgIHRyeSB7XG4gICAgICAgICAgICAgIHB1dE1vZGVsUmVxdWVzdCA9IG1vZGVsU3RvcmUucHV0KHtcbiAgICAgICAgICAgICAgICBtb2RlbFBhdGg6IHRoaXMubW9kZWxQYXRoLFxuICAgICAgICAgICAgICAgIG1vZGVsQXJ0aWZhY3RzLFxuICAgICAgICAgICAgICAgIG1vZGVsQXJ0aWZhY3RzSW5mb1xuICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH0gY2F0Y2ggKGVycm9yKSB7XG4gICAgICAgICAgICAgIC8vIFNvbWV0aW1lcywgdGhlIHNlcmlhbGl6ZWQgdmFsdWUgaXMgdG9vIGxhcmdlIHRvIHN0b3JlLlxuICAgICAgICAgICAgICByZXR1cm4gcmVqZWN0KGVycm9yKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHB1dE1vZGVsUmVxdWVzdC5vbnN1Y2Nlc3MgPSAoKSA9PiByZXNvbHZlKHttb2RlbEFydGlmYWN0c0luZm99KTtcbiAgICAgICAgICAgIHB1dE1vZGVsUmVxdWVzdC5vbmVycm9yID0gZXJyb3IgPT4ge1xuICAgICAgICAgICAgICAvLyBJZiB0aGUgcHV0LW1vZGVsIHJlcXVlc3QgZmFpbHMsIHJvbGwgYmFjayB0aGUgaW5mbyBlbnRyeSBhc1xuICAgICAgICAgICAgICAvLyB3ZWxsLlxuICAgICAgICAgICAgICBpbmZvU3RvcmUgPSBpbmZvVHgub2JqZWN0U3RvcmUoSU5GT19TVE9SRV9OQU1FKTtcbiAgICAgICAgICAgICAgY29uc3QgZGVsZXRlSW5mb1JlcXVlc3QgPSBpbmZvU3RvcmUuZGVsZXRlKHRoaXMubW9kZWxQYXRoKTtcbiAgICAgICAgICAgICAgZGVsZXRlSW5mb1JlcXVlc3Qub25zdWNjZXNzID0gKCkgPT4ge1xuICAgICAgICAgICAgICAgIGRiLmNsb3NlKCk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlamVjdChwdXRNb2RlbFJlcXVlc3QuZXJyb3IpO1xuICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICBkZWxldGVJbmZvUmVxdWVzdC5vbmVycm9yID0gZXJyb3IgPT4ge1xuICAgICAgICAgICAgICAgIGRiLmNsb3NlKCk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHJlamVjdChwdXRNb2RlbFJlcXVlc3QuZXJyb3IpO1xuICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICB9O1xuICAgICAgICAgIHB1dEluZm9SZXF1ZXN0Lm9uZXJyb3IgPSBlcnJvciA9PiB7XG4gICAgICAgICAgICBkYi5jbG9zZSgpO1xuICAgICAgICAgICAgcmV0dXJuIHJlamVjdChwdXRJbmZvUmVxdWVzdC5lcnJvcik7XG4gICAgICAgICAgfTtcbiAgICAgICAgICBpbmZvVHgub25jb21wbGV0ZSA9ICgpID0+IHtcbiAgICAgICAgICAgIGlmIChtb2RlbFR4ID09IG51bGwpIHtcbiAgICAgICAgICAgICAgZGIuY2xvc2UoKTtcbiAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgIG1vZGVsVHgub25jb21wbGV0ZSA9ICgpID0+IGRiLmNsb3NlKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgfTtcbiAgICAgICAgfVxuICAgICAgfTtcbiAgICAgIG9wZW5SZXF1ZXN0Lm9uZXJyb3IgPSBlcnJvciA9PiByZWplY3Qob3BlblJlcXVlc3QuZXJyb3IpO1xuICAgIH0pO1xuICB9XG59XG5cbmV4cG9ydCBjb25zdCBpbmRleGVkREJSb3V0ZXI6IElPUm91dGVyID0gKHVybDogc3RyaW5nfHN0cmluZ1tdKSA9PiB7XG4gIGlmICghZW52KCkuZ2V0Qm9vbCgnSVNfQlJPV1NFUicpKSB7XG4gICAgcmV0dXJuIG51bGw7XG4gIH0gZWxzZSB7XG4gICAgaWYgKCFBcnJheS5pc0FycmF5KHVybCkgJiYgdXJsLnN0YXJ0c1dpdGgoQnJvd3NlckluZGV4ZWREQi5VUkxfU0NIRU1FKSkge1xuICAgICAgcmV0dXJuIGJyb3dzZXJJbmRleGVkREIodXJsLnNsaWNlKEJyb3dzZXJJbmRleGVkREIuVVJMX1NDSEVNRS5sZW5ndGgpKTtcbiAgICB9IGVsc2Uge1xuICAgICAgcmV0dXJuIG51bGw7XG4gICAgfVxuICB9XG59O1xuSU9Sb3V0ZXJSZWdpc3RyeS5yZWdpc3RlclNhdmVSb3V0ZXIoaW5kZXhlZERCUm91dGVyKTtcbklPUm91dGVyUmVnaXN0cnkucmVnaXN0ZXJMb2FkUm91dGVyKGluZGV4ZWREQlJvdXRlcik7XG5cbi8qKlxuICogQ3JlYXRlcyBhIGJyb3dzZXIgSW5kZXhlZERCIElPSGFuZGxlciBmb3Igc2F2aW5nIGFuZCBsb2FkaW5nIG1vZGVscy5cbiAqXG4gKiBgYGBqc1xuICogY29uc3QgbW9kZWwgPSB0Zi5zZXF1ZW50aWFsKCk7XG4gKiBtb2RlbC5hZGQoXG4gKiAgICAgdGYubGF5ZXJzLmRlbnNlKHt1bml0czogMSwgaW5wdXRTaGFwZTogWzEwMF0sIGFjdGl2YXRpb246ICdzaWdtb2lkJ30pKTtcbiAqXG4gKiBjb25zdCBzYXZlUmVzdWx0ID0gYXdhaXQgbW9kZWwuc2F2ZSgnaW5kZXhlZGRiOi8vTXlNb2RlbCcpKTtcbiAqIGNvbnNvbGUubG9nKHNhdmVSZXN1bHQpO1xuICogYGBgXG4gKlxuICogQHBhcmFtIG1vZGVsUGF0aCBBIHVuaXF1ZSBpZGVudGlmaWVyIGZvciB0aGUgbW9kZWwgdG8gYmUgc2F2ZWQuIE11c3QgYmUgYVxuICogICBub24tZW1wdHkgc3RyaW5nLlxuICogQHJldHVybnMgQW4gaW5zdGFuY2Ugb2YgYEJyb3dzZXJJbmRleGVkREJgIChzdWJsY2FzcyBvZiBgSU9IYW5kbGVyYCksXG4gKiAgIHdoaWNoIGNhbiBiZSB1c2VkIHdpdGgsIGUuZy4sIGB0Zi5Nb2RlbC5zYXZlYC5cbiAqL1xuZXhwb3J0IGZ1bmN0aW9uIGJyb3dzZXJJbmRleGVkREIobW9kZWxQYXRoOiBzdHJpbmcpOiBJT0hhbmRsZXIge1xuICByZXR1cm4gbmV3IEJyb3dzZXJJbmRleGVkREIobW9kZWxQYXRoKTtcbn1cblxuZnVuY3Rpb24gbWF5YmVTdHJpcFNjaGVtZShrZXk6IHN0cmluZykge1xuICByZXR1cm4ga2V5LnN0YXJ0c1dpdGgoQnJvd3NlckluZGV4ZWREQi5VUkxfU0NIRU1FKSA/XG4gICAgICBrZXkuc2xpY2UoQnJvd3NlckluZGV4ZWREQi5VUkxfU0NIRU1FLmxlbmd0aCkgOlxuICAgICAga2V5O1xufVxuXG5leHBvcnQgY2xhc3MgQnJvd3NlckluZGV4ZWREQk1hbmFnZXIgaW1wbGVtZW50cyBNb2RlbFN0b3JlTWFuYWdlciB7XG4gIHByaXZhdGUgaW5kZXhlZERCOiBJREJGYWN0b3J5O1xuXG4gIGNvbnN0cnVjdG9yKCkge1xuICAgIHRoaXMuaW5kZXhlZERCID0gZ2V0SW5kZXhlZERCRmFjdG9yeSgpO1xuICB9XG5cbiAgYXN5bmMgbGlzdE1vZGVscygpOiBQcm9taXNlPHtbcGF0aDogc3RyaW5nXTogTW9kZWxBcnRpZmFjdHNJbmZvfT4ge1xuICAgIHJldHVybiBuZXcgUHJvbWlzZTx7W3BhdGg6IHN0cmluZ106IE1vZGVsQXJ0aWZhY3RzSW5mb30+KFxuICAgICAgICAocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XG4gICAgICAgICAgY29uc3Qgb3BlblJlcXVlc3QgPVxuICAgICAgICAgICAgICB0aGlzLmluZGV4ZWREQi5vcGVuKERBVEFCQVNFX05BTUUsIERBVEFCQVNFX1ZFUlNJT04pO1xuICAgICAgICAgIG9wZW5SZXF1ZXN0Lm9udXBncmFkZW5lZWRlZCA9ICgpID0+IHNldFVwRGF0YWJhc2Uob3BlblJlcXVlc3QpO1xuXG4gICAgICAgICAgb3BlblJlcXVlc3Qub25zdWNjZXNzID0gKCkgPT4ge1xuICAgICAgICAgICAgY29uc3QgZGIgPSBvcGVuUmVxdWVzdC5yZXN1bHQ7XG4gICAgICAgICAgICBjb25zdCB0eCA9IGRiLnRyYW5zYWN0aW9uKElORk9fU1RPUkVfTkFNRSwgJ3JlYWRvbmx5Jyk7XG4gICAgICAgICAgICBjb25zdCBzdG9yZSA9IHR4Lm9iamVjdFN0b3JlKElORk9fU1RPUkVfTkFNRSk7XG4gICAgICAgICAgICAvLyB0c2xpbnQ6ZGlzYWJsZTptYXgtbGluZS1sZW5ndGhcbiAgICAgICAgICAgIC8vIE5lZWQgdG8gY2FzdCBgc3RvcmVgIGFzIGBhbnlgIGhlcmUgYmVjYXVzZSBUeXBlU2NyaXB0J3MgRE9NXG4gICAgICAgICAgICAvLyBsaWJyYXJ5IGRvZXMgbm90IGhhdmUgdGhlIGBnZXRBbGwoKWAgbWV0aG9kIGV2ZW4gdGhvdWdoIHRoZVxuICAgICAgICAgICAgLy8gbWV0aG9kIGlzIHN1cHBvcnRlZCBpbiB0aGUgbGF0ZXN0IHZlcnNpb24gb2YgbW9zdCBtYWluc3RyZWFtXG4gICAgICAgICAgICAvLyBicm93c2VyczpcbiAgICAgICAgICAgIC8vIGh0dHBzOi8vZGV2ZWxvcGVyLm1vemlsbGEub3JnL2VuLVVTL2RvY3MvV2ViL0FQSS9JREJPYmplY3RTdG9yZS9nZXRBbGxcbiAgICAgICAgICAgIC8vIHRzbGludDplbmFibGU6bWF4LWxpbmUtbGVuZ3RoXG4gICAgICAgICAgICAvLyB0c2xpbnQ6ZGlzYWJsZS1uZXh0LWxpbmU6bm8tYW55XG4gICAgICAgICAgICBjb25zdCBnZXRBbGxJbmZvUmVxdWVzdCA9IChzdG9yZSBhcyBhbnkpLmdldEFsbCgpIGFzIElEQlJlcXVlc3Q7XG4gICAgICAgICAgICBnZXRBbGxJbmZvUmVxdWVzdC5vbnN1Y2Nlc3MgPSAoKSA9PiB7XG4gICAgICAgICAgICAgIGNvbnN0IG91dDoge1twYXRoOiBzdHJpbmddOiBNb2RlbEFydGlmYWN0c0luZm99ID0ge307XG4gICAgICAgICAgICAgIGZvciAoY29uc3QgaXRlbSBvZiBnZXRBbGxJbmZvUmVxdWVzdC5yZXN1bHQpIHtcbiAgICAgICAgICAgICAgICBvdXRbaXRlbS5tb2RlbFBhdGhdID0gaXRlbS5tb2RlbEFydGlmYWN0c0luZm87XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgcmVzb2x2ZShvdXQpO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIGdldEFsbEluZm9SZXF1ZXN0Lm9uZXJyb3IgPSBlcnJvciA9PiB7XG4gICAgICAgICAgICAgIGRiLmNsb3NlKCk7XG4gICAgICAgICAgICAgIHJldHVybiByZWplY3QoZ2V0QWxsSW5mb1JlcXVlc3QuZXJyb3IpO1xuICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIHR4Lm9uY29tcGxldGUgPSAoKSA9PiBkYi5jbG9zZSgpO1xuICAgICAgICAgIH07XG4gICAgICAgICAgb3BlblJlcXVlc3Qub25lcnJvciA9IGVycm9yID0+IHJlamVjdChvcGVuUmVxdWVzdC5lcnJvcik7XG4gICAgICAgIH0pO1xuICB9XG5cbiAgYXN5bmMgcmVtb3ZlTW9kZWwocGF0aDogc3RyaW5nKTogUHJvbWlzZTxNb2RlbEFydGlmYWN0c0luZm8+IHtcbiAgICBwYXRoID0gbWF5YmVTdHJpcFNjaGVtZShwYXRoKTtcbiAgICByZXR1cm4gbmV3IFByb21pc2U8TW9kZWxBcnRpZmFjdHNJbmZvPigocmVzb2x2ZSwgcmVqZWN0KSA9PiB7XG4gICAgICBjb25zdCBvcGVuUmVxdWVzdCA9IHRoaXMuaW5kZXhlZERCLm9wZW4oREFUQUJBU0VfTkFNRSwgREFUQUJBU0VfVkVSU0lPTik7XG4gICAgICBvcGVuUmVxdWVzdC5vbnVwZ3JhZGVuZWVkZWQgPSAoKSA9PiBzZXRVcERhdGFiYXNlKG9wZW5SZXF1ZXN0KTtcblxuICAgICAgb3BlblJlcXVlc3Qub25zdWNjZXNzID0gKCkgPT4ge1xuICAgICAgICBjb25zdCBkYiA9IG9wZW5SZXF1ZXN0LnJlc3VsdDtcbiAgICAgICAgY29uc3QgaW5mb1R4ID0gZGIudHJhbnNhY3Rpb24oSU5GT19TVE9SRV9OQU1FLCAncmVhZHdyaXRlJyk7XG4gICAgICAgIGNvbnN0IGluZm9TdG9yZSA9IGluZm9UeC5vYmplY3RTdG9yZShJTkZPX1NUT1JFX05BTUUpO1xuXG4gICAgICAgIGNvbnN0IGdldEluZm9SZXF1ZXN0ID0gaW5mb1N0b3JlLmdldChwYXRoKTtcbiAgICAgICAgbGV0IG1vZGVsVHg6IElEQlRyYW5zYWN0aW9uO1xuICAgICAgICBnZXRJbmZvUmVxdWVzdC5vbnN1Y2Nlc3MgPSAoKSA9PiB7XG4gICAgICAgICAgaWYgKGdldEluZm9SZXF1ZXN0LnJlc3VsdCA9PSBudWxsKSB7XG4gICAgICAgICAgICBkYi5jbG9zZSgpO1xuICAgICAgICAgICAgcmV0dXJuIHJlamVjdChuZXcgRXJyb3IoXG4gICAgICAgICAgICAgICAgYENhbm5vdCBmaW5kIG1vZGVsIHdpdGggcGF0aCAnJHtwYXRofScgYCArXG4gICAgICAgICAgICAgICAgYGluIEluZGV4ZWREQi5gKSk7XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIC8vIEZpcnN0LCBkZWxldGUgdGhlIGVudHJ5IGluIHRoZSBpbmZvIHN0b3JlLlxuICAgICAgICAgICAgY29uc3QgZGVsZXRlSW5mb1JlcXVlc3QgPSBpbmZvU3RvcmUuZGVsZXRlKHBhdGgpO1xuICAgICAgICAgICAgY29uc3QgZGVsZXRlTW9kZWxEYXRhID0gKCkgPT4ge1xuICAgICAgICAgICAgICAvLyBTZWNvbmQsIGRlbGV0ZSB0aGUgZW50cnkgaW4gdGhlIG1vZGVsIHN0b3JlLlxuICAgICAgICAgICAgICBtb2RlbFR4ID0gZGIudHJhbnNhY3Rpb24oTU9ERUxfU1RPUkVfTkFNRSwgJ3JlYWR3cml0ZScpO1xuICAgICAgICAgICAgICBjb25zdCBtb2RlbFN0b3JlID0gbW9kZWxUeC5vYmplY3RTdG9yZShNT0RFTF9TVE9SRV9OQU1FKTtcbiAgICAgICAgICAgICAgY29uc3QgZGVsZXRlTW9kZWxSZXF1ZXN0ID0gbW9kZWxTdG9yZS5kZWxldGUocGF0aCk7XG4gICAgICAgICAgICAgIGRlbGV0ZU1vZGVsUmVxdWVzdC5vbnN1Y2Nlc3MgPSAoKSA9PlxuICAgICAgICAgICAgICAgICAgcmVzb2x2ZShnZXRJbmZvUmVxdWVzdC5yZXN1bHQubW9kZWxBcnRpZmFjdHNJbmZvKTtcbiAgICAgICAgICAgICAgZGVsZXRlTW9kZWxSZXF1ZXN0Lm9uZXJyb3IgPSBlcnJvciA9PlxuICAgICAgICAgICAgICAgICAgcmVqZWN0KGdldEluZm9SZXF1ZXN0LmVycm9yKTtcbiAgICAgICAgICAgIH07XG4gICAgICAgICAgICAvLyBQcm9jZWVkIHdpdGggZGVsZXRpbmcgbW9kZWwgZGF0YSByZWdhcmRsZXNzIG9mIHdoZXRoZXIgZGVsZXRpb25cbiAgICAgICAgICAgIC8vIG9mIGluZm8gZGF0YSBzdWNjZWVkcyBvciBub3QuXG4gICAgICAgICAgICBkZWxldGVJbmZvUmVxdWVzdC5vbnN1Y2Nlc3MgPSBkZWxldGVNb2RlbERhdGE7XG4gICAgICAgICAgICBkZWxldGVJbmZvUmVxdWVzdC5vbmVycm9yID0gZXJyb3IgPT4ge1xuICAgICAgICAgICAgICBkZWxldGVNb2RlbERhdGEoKTtcbiAgICAgICAgICAgICAgZGIuY2xvc2UoKTtcbiAgICAgICAgICAgICAgcmV0dXJuIHJlamVjdChnZXRJbmZvUmVxdWVzdC5lcnJvcik7XG4gICAgICAgICAgICB9O1xuICAgICAgICAgIH1cbiAgICAgICAgfTtcbiAgICAgICAgZ2V0SW5mb1JlcXVlc3Qub25lcnJvciA9IGVycm9yID0+IHtcbiAgICAgICAgICBkYi5jbG9zZSgpO1xuICAgICAgICAgIHJldHVybiByZWplY3QoZ2V0SW5mb1JlcXVlc3QuZXJyb3IpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGluZm9UeC5vbmNvbXBsZXRlID0gKCkgPT4ge1xuICAgICAgICAgIGlmIChtb2RlbFR4ID09IG51bGwpIHtcbiAgICAgICAgICAgIGRiLmNsb3NlKCk7XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIG1vZGVsVHgub25jb21wbGV0ZSA9ICgpID0+IGRiLmNsb3NlKCk7XG4gICAgICAgICAgfVxuICAgICAgICB9O1xuICAgICAgfTtcbiAgICAgIG9wZW5SZXF1ZXN0Lm9uZXJyb3IgPSBlcnJvciA9PiByZWplY3Qob3BlblJlcXVlc3QuZXJyb3IpO1xuICAgIH0pO1xuICB9XG59XG4iXX0=