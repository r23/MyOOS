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
import { env, keep, tidy, util } from '@tensorflow/tfjs-core';
import { getNodeNameAndIndex, getParamValue, getTensor, getTensorsForCurrentContext, parseNodeName } from '../operations/executors/utils';
import { executeOp } from '../operations/operation_executor';
import { ExecutionContext } from './execution_context';
import { getExecutionSubgraph, getNodeLiveUntilMap, getNodesInTopologicalOrder, isControlFlow } from './model_analysis';
export class GraphExecutor {
    get weightIds() {
        return this.parent ? this.parent.weightIds : this._weightIds;
    }
    get functionExecutorMap() {
        return this.parent ? this.parent.functionExecutorMap :
            this._functionExecutorMap;
    }
    get weightMap() {
        return this.parent ? this.parent.weightMap : this._weightMap;
    }
    set weightMap(weightMap) {
        const weightIds = Object.keys(weightMap).map(key => weightMap[key].map(tensor => tensor.id));
        this._weightIds = [].concat(...weightIds);
        this._weightMap = weightMap;
    }
    /**
     * Set `ResourceManager` shared by executors of a model.
     * @param resourceManager: `ResourceManager` of the `GraphModel`.
     */
    set resourceManager(resourceManager) {
        this._resourceManager = resourceManager;
    }
    get inputs() {
        return this._inputs.map(node => {
            return {
                name: node.name,
                shape: node.attrParams['shape'] ?
                    node.attrParams['shape'].value :
                    undefined,
                dtype: node.attrParams['dtype'] ?
                    node.attrParams['dtype'].value :
                    undefined
            };
        });
    }
    get outputs() {
        return this._outputs.map(node => {
            return {
                name: node.name,
                shape: node.attrParams['shape'] ?
                    node.attrParams['shape'].value :
                    undefined,
                dtype: node.attrParams['dtype'] ?
                    node.attrParams['dtype'].value :
                    undefined
            };
        });
    }
    get inputNodes() {
        return this._inputs.map(node => node.signatureKey || node.name);
    }
    get outputNodes() {
        return this._outputs.map((node) => {
            const name = node.signatureKey || node.name;
            return node.defaultOutput ? (`${name}:${node.defaultOutput}`) : name;
        });
    }
    get functions() {
        return Object.keys(this._functions).reduce((map, key) => {
            map[key] = this._functions[key].signature;
            return map;
        }, {});
    }
    /**
     *
     * @param graph Graph the model or function graph to be executed.
     * @param parent When building function exector you need to set the parent
     * executor. Since the weights and function executor maps are set at parant
     * level, that function executor can access the function maps and weight maps
     * through the parent.
     */
    constructor(graph, parent) {
        this.graph = graph;
        this.parent = parent;
        this.compiledMap = new Map();
        this.parseNodeNameCache = new Map();
        this._weightMap = {};
        this.SEPARATOR = ',';
        this._functions = {};
        this._functionExecutorMap = {};
        this.keepIntermediateTensors = false;
        this._outputs = graph.outputs;
        this._inputs = graph.inputs;
        this._initNodes = graph.initNodes;
        this._signature = graph.signature;
        this._functions = graph.functions;
        // create sub-graph executors
        if (graph.functions != null) {
            Object.keys(graph.functions).forEach(name => {
                this._functionExecutorMap[name] =
                    new GraphExecutor(graph.functions[name], this);
            });
        }
    }
    getCompilationKey(inputs, outputs) {
        const sortedInputs = inputs.map(node => node.name).sort();
        const sortedOutputs = outputs.map(node => node.name).sort();
        return sortedInputs.join(this.SEPARATOR) + '--' +
            sortedOutputs.join(this.SEPARATOR);
    }
    /**
     * Compiles the inference graph and returns the minimal set of nodes that are
     * required for execution, in the correct execution order.
     * @returns {Object} compilation The compile result.
     * @returns {Node[]} compilation.orderedNodes Nodes in the correct execution
     *     order.
     * @returns {Map<string, Node[]>} compilation.nodeLiveUntilMap A map from node
     *     to disposable nodes after its execution. That is, for a node `x`,
     *     `nodeLiveUntilMap[x]` indicates all nodes whose intermediate
     *     tensors should be disposed after `x` is executed.
     */
    compile(inputs, outputs) {
        const executionInfo = getExecutionSubgraph(inputs, outputs, this.weightMap, this._initNodes);
        const { missingInputs, dynamicNode, syncInputs } = executionInfo;
        if (dynamicNode != null) {
            throw new Error(`This execution contains the node '${dynamicNode.name}', which has ` +
                `the dynamic op '${dynamicNode.op}'. Please use ` +
                `model.executeAsync() instead. Alternatively, to avoid the ` +
                `dynamic ops, specify the inputs [${syncInputs}]`);
        }
        if (missingInputs.length > 0) {
            const outNames = outputs.map(n => n.name);
            const inNames = Object.keys(inputs);
            throw new Error(`Cannot compute the outputs [${outNames}] from the provided inputs ` +
                `[${inNames}]. Missing the following inputs: [${missingInputs}]`);
        }
        const orderedNodes = getNodesInTopologicalOrder(this.graph, executionInfo);
        const nodeLiveUntilMap = getNodeLiveUntilMap(orderedNodes);
        return { orderedNodes, nodeLiveUntilMap };
    }
    cloneAndKeepTensor(tensor) {
        if (tensor == null) {
            return null;
        }
        const clone = tensor.clone();
        // Keep the clone because`model.execute()` may be called within
        // a `tidy()`, but the user may inspect these tensors after the
        // tidy.
        keep(clone);
        return clone;
    }
    cloneTensorList(tensors) {
        if (!tensors) {
            return null;
        }
        const clonedTensor = tensors.map(tensor => {
            return this.cloneAndKeepTensor(tensor);
        });
        return clonedTensor;
    }
    cloneTensorMap(tensorsMap) {
        return Object.fromEntries(Object.entries(tensorsMap).map(([name, tensorsList]) => {
            return [name, this.cloneTensorList(tensorsList)];
        }));
    }
    /**
     * Executes the inference for given input tensors.
     * @param inputs Tensor map for the model inputs, keyed by the input node
     * names.
     * @param outputs Optional. output node name from the Tensorflow model, if
     * no outputs are specified, the default outputs of the model would be used.
     * You can inspect intermediate nodes of the model by adding them to the
     * outputs array.
     */
    execute(inputs, outputs) {
        // Dispose any tensors from a prior run to avoid leaking them.
        this.disposeIntermediateTensors();
        inputs = this.mapInputs(inputs);
        const names = Object.keys(inputs).sort();
        this.checkInputs(inputs);
        this.checkInputShapeAndType(inputs);
        outputs = this.mapOutputs(outputs);
        this.checkOutputs(outputs);
        const inputNodes = names.map(name => this.graph.nodes[parseNodeName(name)[0]]);
        const outputNodeNames = outputs.map(name => parseNodeName(name)[0]);
        const outputNodeNameSet = new Set(outputNodeNames);
        let outputNodes = outputNodeNames.map(name => this.graph.nodes[name]);
        // If no outputs are specified, then use the default outputs of the model.
        if (outputNodes.length === 0) {
            outputNodes = this._outputs;
        }
        const compilationKey = this.getCompilationKey(inputNodes, outputNodes);
        // Do nothing if the compiled graph cache contains the input.
        let compilation = this.compiledMap.get(compilationKey);
        if (compilation == null) {
            compilation = this.compile(inputs, outputNodes);
            this.compiledMap.set(compilationKey, compilation);
        }
        // Keep tensors if KEEP_INTERMEDIATE_TENSORS is on.
        try {
            this.keepIntermediateTensors = env().getBool('KEEP_INTERMEDIATE_TENSORS');
        }
        catch (e) {
            this.keepIntermediateTensors = false;
            console.warn(e.message);
        }
        const tensorArrayMap = {};
        const tensorListMap = {};
        return tidy(() => {
            const context = new ExecutionContext(this.weightMap, tensorArrayMap, tensorListMap, this.functionExecutorMap, this.parseNodeNameCache);
            const tensorsMap = Object.assign({}, this.weightMap);
            if (this.keepIntermediateTensors) {
                this.clonedTensorsMap = this.cloneTensorMap(this.weightMap);
            }
            Object.keys(inputs).forEach(name => {
                const [nodeName, index] = parseNodeName(name, context);
                const tensors = [];
                tensors[index] = inputs[name];
                tensorsMap[nodeName] = tensors;
                if (this.keepIntermediateTensors) {
                    this.clonedTensorsMap[nodeName] = this.cloneTensorList(tensors);
                }
            });
            const tensorsToKeep = this.getFrozenTensorIds(tensorsMap);
            const { orderedNodes, nodeLiveUntilMap } = compilation;
            for (const node of orderedNodes) {
                if (tensorsMap[node.name]) {
                    continue;
                }
                const tensors = executeOp(node, tensorsMap, context, this._resourceManager);
                if (util.isPromise(tensors)) {
                    throw new Error(`The execution of the op '${node.op}' returned a promise. ` +
                        `Please use model.executeAsync() instead.`);
                }
                tensorsMap[node.name] = tensors;
                if (this.keepIntermediateTensors) {
                    this.clonedTensorsMap[node.name] = this.cloneTensorList(tensors);
                }
                this.checkTensorForDisposalWithNodeLiveUntilInfo(node, tensorsMap, context, tensorsToKeep, outputNodeNameSet, nodeLiveUntilMap.get(node.name));
            }
            // dispose the context for the root executor
            if (this.parent == null) {
                context.dispose(tensorsToKeep);
            }
            return outputs.map(name => getTensor(name, tensorsMap, context));
        });
    }
    getFrozenTensorIds(tensorMap) {
        const ids = [].concat.apply([], Object.keys(tensorMap)
            .map(key => tensorMap[key])
            .map(tensors => tensors.map(tensor => tensor.id)));
        return new Set(ids);
    }
    checkTensorForDisposal(nodeName, node, tensorMap, context, tensorsToKeep, outputNodeNameSet, intermediateTensorConsumerCount) {
        // Skip output nodes and any control flow nodes, since its dependency is
        // tricky to track correctly.
        if (isControlFlow(node) || outputNodeNameSet.has(nodeName)) {
            return;
        }
        for (const tensor of tensorMap[nodeName]) {
            if (tensor == null) {
                continue;
            }
            intermediateTensorConsumerCount[tensor.id] =
                (intermediateTensorConsumerCount[tensor.id] || 0) +
                    node.children.length;
        }
        for (const input of node.inputs) {
            // Skip any control flow nodes, since its dependency is tricky to track
            // correctly.
            if (isControlFlow(input)) {
                continue;
            }
            const tensors = getTensorsForCurrentContext(input.name, tensorMap, context);
            if (tensors == null) {
                continue;
            }
            for (const tensor of tensors) {
                if (!tensor || tensor.kept || tensorsToKeep.has(tensor.id)) {
                    continue;
                }
                // Only intermediate nodes' tensors have counts set, not marked as
                // kept, and not in `tensorsToKeep`.
                // Input and weight nodes' tensors should exist in `tensorsToKeep`.
                // Output and control flow nodes' tensors should never have count set.
                const count = intermediateTensorConsumerCount[tensor.id];
                if (count === 1) {
                    tensor.dispose();
                    delete intermediateTensorConsumerCount[tensor.id];
                }
                else if (count != null) {
                    intermediateTensorConsumerCount[tensor.id]--;
                }
            }
        }
    }
    checkTensorForDisposalWithNodeLiveUntilInfo(node, tensorMap, context, tensorsToKeep, outputNodeNameSet, liveUntilNodes) {
        function isNonDisposableNode(node) {
            // Skip output nodes and any control flow nodes, since its dependency is
            // tricky to track correctly.
            return isControlFlow(node) || outputNodeNameSet.has(node.name);
        }
        if (isControlFlow(node) || liveUntilNodes == null) {
            return;
        }
        for (const nodeToDispose of liveUntilNodes) {
            if (isNonDisposableNode(nodeToDispose)) {
                continue;
            }
            const tensors = getTensorsForCurrentContext(nodeToDispose.name, tensorMap, context);
            for (const tensor of tensors) {
                if (!tensor || tensor.kept || tensorsToKeep.has(tensor.id)) {
                    continue;
                }
                tensor.dispose();
            }
        }
    }
    /**
     * Executes the inference for given input tensors in Async fashion.
     * @param inputs Tensor map for the model inputs, keyed by the input node
     * names.
     * @param outputs output node name from the Tensorflow model, if no outputs
     * are specified, the default outputs of the model would be used. You can
     * inspect intermediate nodes of the model by adding them to the outputs
     * array.
     */
    async executeAsync(inputs, outputs) {
        return this._executeAsync(inputs, outputs);
    }
    disposeIntermediateTensors() {
        if (!this.clonedTensorsMap) {
            return;
        }
        Object.values(this.clonedTensorsMap).forEach(tensorsList => {
            for (const tensor of tensorsList) {
                if (tensor && !tensor.isDisposed) {
                    tensor.dispose();
                }
            }
        });
        this.clonedTensorsMap = null;
    }
    getIntermediateTensors() {
        return this.clonedTensorsMap;
    }
    /**
     * Executes the inference for given input tensors in Async fashion.
     * @param inputs Tensor map for the model inputs, keyed by the input node
     * names.
     * @param outputs Optional. output node name from the Tensorflow model,
     * if no outputs are specified, the default outputs of the model would be
     * used. You can inspect intermediate nodes of the model by adding them to
     * the outputs array.
     * @param isFunctionExecution Optional. Flag for executing a function.
     * @param tensorArrayMap Optional, global TensorArray map by id. Used for
     * function execution.
     * @param tensorArrayMap Optinal global TensorList map by id. Used for
     * function execution.
     */
    async _executeAsync(inputs, outputs, isFunctionExecution = false, tensorArrayMap = {}, tensorListMap = {}) {
        // Dispose any tensors from a prior run to avoid leaking them.
        this.disposeIntermediateTensors();
        if (!isFunctionExecution) {
            inputs = this.mapInputs(inputs);
            this.checkInputs(inputs);
            this.checkInputShapeAndType(inputs);
            outputs = this.mapOutputs(outputs);
            this.checkOutputs(outputs);
        }
        // Keep tensors if KEEP_INTERMEDIATE_TENSORS is on.
        try {
            this.keepIntermediateTensors = env().getBool('KEEP_INTERMEDIATE_TENSORS');
        }
        catch (e) {
            this.keepIntermediateTensors = false;
            console.warn(e.message);
        }
        const context = new ExecutionContext(this.weightMap, tensorArrayMap, tensorListMap, this.functionExecutorMap, this.parseNodeNameCache);
        if (this.keepIntermediateTensors) {
            this.clonedTensorsMap = this.cloneTensorMap(this.weightMap);
        }
        // Graph with control flow op requires runtime evaluation of the execution
        // order, while without control flow the execution order is pre-determined
        // in the compile method.
        const tensorsMap = await this.executeWithControlFlow(inputs, context, outputs, isFunctionExecution);
        const results = outputs.map(name => getTensor(name, tensorsMap, context));
        // dispose all the intermediate tensors
        const outputIds = results.map(t => t.id);
        const inputIds = Object.keys(inputs).map(name => inputs[name].id);
        const keepIds = new Set([...outputIds, ...inputIds, ...this.weightIds]);
        Object.values(tensorsMap).forEach(tensorsList => {
            tensorsList.forEach(tensor => {
                if (tensor && !tensor.isDisposed && !keepIds.has(tensor.id)) {
                    tensor.dispose();
                }
            });
        });
        // dispose the context for the root executor
        if (this.parent == null) {
            context.dispose(keepIds);
        }
        return results;
    }
    async executeFunctionAsync(inputs, tensorArrayMap, tensorListMap) {
        const mappedInputs = inputs.reduce((map, tensor, index) => {
            map[this.inputs[index].name] = tensor;
            return map;
        }, {});
        return this._executeAsync(mappedInputs, this.outputNodes, true, tensorArrayMap, tensorListMap);
    }
    /**
     * When there are control flow nodes in the graph, the graph execution use
     * ExecutionContext to keep track of the frames and loop iterators.
     * @param inputs placeholder tensors for the graph.
     * @param context the execution context object for current execution.
     * @param outputNames Optional. output node name from the Tensorflow model,
     * if no outputs are specified, the default outputs of the model would be
     * used. You can inspect intermediate nodes of the model by adding them to
     * the outputs array.
     * @param isFunctionExecution Flag for executing a function.
     */
    async executeWithControlFlow(inputs, context, outputNames, isFunctionExecution) {
        const names = Object.keys(inputs);
        const inputNodes = names.map(name => this.graph.nodes[parseNodeName(name)[0]]);
        const outputNodeNames = outputNames.map(name => parseNodeName(name)[0]);
        const outputNodeNameSet = new Set(outputNodeNames);
        let outputNodes = outputNodeNames.map(name => this.graph.nodes[name]);
        // If no outputs are specified, then use the default outputs of the model.
        if (outputNodes.length === 0) {
            outputNodes = this._outputs;
        }
        const { usedNodes, missingInputs, dynamicNode, syncInputs } = getExecutionSubgraph(inputs, outputNodes, this.weightMap, this._initNodes);
        // First nodes to execute include inputNodes, weights, and initNodes.
        const stack = [
            ...inputNodes, ...this.graph.weights, ...(this._initNodes || [])
        ].map(node => {
            return { node, contexts: context.currentContext };
        });
        const tensorsMap = Object.assign({}, this.weightMap);
        Object.keys(inputs).forEach(name => {
            const [nodeName, index] = parseNodeName(name);
            const tensors = [];
            tensors[index] = inputs[name];
            tensorsMap[nodeName] = tensors;
        });
        const intermediateTensorConsumerCount = {};
        const tensorsToKeep = this.getFrozenTensorIds(tensorsMap);
        const added = {};
        while (stack.length > 0) {
            const promises = this.processStack(inputNodes, stack, context, tensorsMap, added, tensorsToKeep, outputNodeNameSet, intermediateTensorConsumerCount, usedNodes);
            await Promise.all(promises);
        }
        if (dynamicNode == null && !isFunctionExecution) {
            console.warn(`This model execution did not contain any nodes with control flow ` +
                `or dynamic output shapes. You can use model.execute() instead.`);
        }
        const missingOutputs = outputNodes
            .filter(node => !isControlFlow(node) &&
            !getTensor(node.name, tensorsMap, context))
            .map(node => node.name);
        if (missingOutputs.length > 0) {
            let alternativeMsg = '';
            if (dynamicNode != null) {
                alternativeMsg =
                    `Alternatively, to avoid the dynamic ops, use model.execute() ` +
                        `and specify the inputs [${syncInputs}]`;
            }
            throw new Error(`Cannot compute the outputs [${missingOutputs}] from the provided ` +
                `inputs [${names}]. Consider providing the following inputs: ` +
                `[${missingInputs}]. ${alternativeMsg}`);
        }
        return tensorsMap;
    }
    processStack(inputNodes, stack, context, tensorMap, added, tensorsToKeep, outputNodeNameSet, intermediateTensorConsumerCount, usedNodes) {
        const promises = [];
        while (stack.length > 0) {
            const item = stack.pop();
            context.currentContext = item.contexts;
            let nodeName = '';
            // The tensor of the Enter op with isConstant set should be set
            // in the parent scope, so it will be available as constant for the
            // whole loop.
            if (item.node.op === 'Enter' &&
                getParamValue('isConstant', item.node, tensorMap, context)) {
                [nodeName] = getNodeNameAndIndex(item.node.name, context);
            }
            // only process nodes that are not in the tensorMap yet, this include
            // inputNodes and internal initNodes.
            if (tensorMap[item.node.name] == null) {
                const tensors = executeOp(item.node, tensorMap, context, this._resourceManager);
                if (!nodeName) {
                    [nodeName] = getNodeNameAndIndex(item.node.name, context);
                }
                const currentContext = context.currentContext;
                if (util.isPromise(tensors)) {
                    promises.push(tensors.then(t => {
                        tensorMap[nodeName] = t;
                        if (this.keepIntermediateTensors) {
                            this.clonedTensorsMap[nodeName] = this.cloneTensorList(t);
                        }
                        context.currentContext = currentContext;
                        this.checkTensorForDisposal(nodeName, item.node, tensorMap, context, tensorsToKeep, outputNodeNameSet, intermediateTensorConsumerCount);
                        this.processChildNodes(item.node, stack, context, tensorMap, added, usedNodes);
                        return t;
                    }));
                }
                else {
                    tensorMap[nodeName] = tensors;
                    if (this.keepIntermediateTensors) {
                        this.clonedTensorsMap[nodeName] = this.cloneTensorList(tensors);
                    }
                    this.checkTensorForDisposal(nodeName, item.node, tensorMap, context, tensorsToKeep, outputNodeNameSet, intermediateTensorConsumerCount);
                    this.processChildNodes(item.node, stack, context, tensorMap, added, usedNodes);
                }
            }
            else {
                this.processChildNodes(item.node, stack, context, tensorMap, added, usedNodes);
            }
        }
        return promises;
    }
    processChildNodes(node, stack, context, tensorMap, added, usedNodes) {
        node.children.forEach((childNode) => {
            const [nodeName,] = getNodeNameAndIndex(childNode.name, context);
            if (added[nodeName] || !usedNodes.has(childNode.name)) {
                return;
            }
            // Merge op can be pushed if any of its inputs has value.
            if (childNode.op === 'Merge') {
                if (childNode.inputNames.some(name => {
                    return !!getTensor(name, tensorMap, context);
                })) {
                    added[nodeName] = true;
                    stack.push({ contexts: context.currentContext, node: childNode });
                }
            }
            else // Otherwise all inputs must to have value.
             if (childNode.inputNames.every(name => {
                return !!getTensor(name, tensorMap, context);
            })) {
                added[nodeName] = true;
                stack.push({ contexts: context.currentContext, node: childNode });
            }
        });
    }
    /**
     * Releases the memory used by the weight tensors.
     */
    dispose() {
        Object.keys(this.weightMap)
            .forEach(key => this.weightMap[key].forEach(tensor => tensor.dispose()));
    }
    checkInputShapeAndType(inputs) {
        Object.keys(inputs).forEach(name => {
            const input = inputs[name];
            const [nodeName,] = parseNodeName(name);
            const node = this.graph.nodes[nodeName];
            if (node.attrParams['shape'] && node.attrParams['shape'].value) {
                const shape = node.attrParams['shape'].value;
                const match = shape.length === input.shape.length &&
                    input.shape.every((dim, index) => shape[index] === -1 || shape[index] === dim);
                util.assert(match, () => `The shape of dict['${node.name}'] provided in ` +
                    `model.execute(dict) must be [${shape}], but was ` +
                    `[${input.shape}]`);
            }
            if (node.attrParams['dtype'] && node.attrParams['dtype'].value) {
                util.assert(input.dtype === node.attrParams['dtype'].value, () => `The dtype of dict['${node.name}'] provided in ` +
                    `model.execute(dict) must be ` +
                    `${node.attrParams['dtype'].value}, but was ${input.dtype}`);
            }
        });
    }
    mapInputs(inputs) {
        var _a, _b;
        const result = {};
        for (const inputName in inputs) {
            const tensor = (_b = (_a = this._signature) === null || _a === void 0 ? void 0 : _a.inputs) === null || _b === void 0 ? void 0 : _b[inputName];
            if (tensor != null) {
                result[tensor.name] = inputs[inputName];
            }
            else {
                result[inputName] = inputs[inputName];
            }
        }
        return result;
    }
    checkInputs(inputs) {
        const notInGraph = Object.keys(inputs).filter(name => {
            const [nodeName] = parseNodeName(name);
            return this.graph.nodes[nodeName] == null;
        });
        if (notInGraph.length > 0) {
            throw new Error(`The dict provided in model.execute(dict) has ` +
                `keys: [${notInGraph}] that are not part of graph`);
        }
    }
    mapOutputs(outputs) {
        return outputs.map(name => {
            var _a, _b;
            const tensor = (_b = (_a = this._signature) === null || _a === void 0 ? void 0 : _a.outputs) === null || _b === void 0 ? void 0 : _b[name];
            if (tensor != null) {
                return tensor.name;
            }
            return name;
        }, {});
    }
    checkOutputs(outputs) {
        outputs.forEach(name => {
            const [normalizedName] = parseNodeName(name);
            if (!this.graph.nodes[normalizedName]) {
                throw new Error(`The output '${name}' is not found in the graph`);
            }
        });
    }
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiZ3JhcGhfZXhlY3V0b3IuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlcyI6WyIuLi8uLi8uLi8uLi8uLi8uLi90ZmpzLWNvbnZlcnRlci9zcmMvZXhlY3V0b3IvZ3JhcGhfZXhlY3V0b3IudHMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7Ozs7Ozs7Ozs7Ozs7OztHQWVHO0FBRUgsT0FBTyxFQUFXLEdBQUcsRUFBRSxJQUFJLEVBQTBCLElBQUksRUFBRSxJQUFJLEVBQUMsTUFBTSx1QkFBdUIsQ0FBQztBQUk5RixPQUFPLEVBQUMsbUJBQW1CLEVBQUUsYUFBYSxFQUFFLFNBQVMsRUFBRSwyQkFBMkIsRUFBRSxhQUFhLEVBQUMsTUFBTSwrQkFBK0IsQ0FBQztBQUN4SSxPQUFPLEVBQUMsU0FBUyxFQUFDLE1BQU0sa0NBQWtDLENBQUM7QUFHM0QsT0FBTyxFQUFDLGdCQUFnQixFQUF1QixNQUFNLHFCQUFxQixDQUFDO0FBQzNFLE9BQU8sRUFBQyxvQkFBb0IsRUFBRSxtQkFBbUIsRUFBRSwwQkFBMEIsRUFBRSxhQUFhLEVBQUMsTUFBTSxrQkFBa0IsQ0FBQztBQVN0SCxNQUFNLE9BQU8sYUFBYTtJQWdCeEIsSUFBSSxTQUFTO1FBQ1gsT0FBTyxJQUFJLENBQUMsTUFBTSxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsTUFBTSxDQUFDLFNBQVMsQ0FBQyxDQUFDLENBQUMsSUFBSSxDQUFDLFVBQVUsQ0FBQztJQUMvRCxDQUFDO0lBRUQsSUFBSSxtQkFBbUI7UUFDckIsT0FBTyxJQUFJLENBQUMsTUFBTSxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsTUFBTSxDQUFDLG1CQUFtQixDQUFDLENBQUM7WUFDakMsSUFBSSxDQUFDLG9CQUFvQixDQUFDO0lBQ2pELENBQUM7SUFFRCxJQUFJLFNBQVM7UUFDWCxPQUFPLElBQUksQ0FBQyxNQUFNLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxNQUFNLENBQUMsU0FBUyxDQUFDLENBQUMsQ0FBQyxJQUFJLENBQUMsVUFB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QyxlQUFlLElBQUksNkJBQTZCLENBQUMsQ0FBQzthQUNuRTtRQUNILENBQUMsQ0FBQyxDQUFDO0lBQ0wsQ0FBQztDQUNGIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMTggR29vZ2xlIExMQy4gQWxsIFJpZ2h0cyBSZXNlcnZlZC5cbiAqIExpY2Vuc2VkIHVuZGVyIHRoZSBBcGFjaGUgTGljZW5zZSwgVmVyc2lvbiAyLjAgKHRoZSBcIkxpY2Vuc2VcIik7XG4gKiB5b3UgbWF5IG5vdCB1c2UgdGhpcyBmaWxlIGV4Y2VwdCBpbiBjb21wbGlhbmNlIHdpdGggdGhlIExpY2Vuc2UuXG4gKiBZb3UgbWF5IG9idGFpbiBhIGNvcHkgb2YgdGhlIExpY2Vuc2UgYXRcbiAqXG4gKiBodHRwOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcbiAqXG4gKiBVbmxlc3MgcmVxdWlyZWQgYnkgYXBwbGljYWJsZSBsYXcgb3IgYWdyZWVkIHRvIGluIHdyaXRpbmcsIHNvZnR3YXJlXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXG4gKiBXSVRIT1VUIFdBUlJBTlRJRVMgT1IgQ09ORElUSU9OUyBPRiBBTlkgS0lORCwgZWl0aGVyIGV4cHJlc3Mgb3IgaW1wbGllZC5cbiAqIFNlZSB0aGUgTGljZW5zZSBmb3IgdGhlIHNwZWNpZmljIGxhbmd1YWdlIGdvdmVybmluZyBwZXJtaXNzaW9ucyBhbmRcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxuICogPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cbiAqL1xuXG5pbXBvcnQge0RhdGFUeXBlLCBlbnYsIGtlZXAsIE5hbWVkVGVuc29yTWFwLCBUZW5zb3IsIHRpZHksIHV0aWx9IGZyb20gJ0B0ZW5zb3JmbG93L3RmanMtY29yZSc7XG5cbmltcG9ydCB7SVNpZ25hdHVyZURlZn0gZnJvbSAnLi4vZGF0YS9jb21waWxlZF9hcGknO1xuaW1wb3J0IHtOYW1lZFRlbnNvcnNNYXAsIFRlbnNvckFycmF5TWFwLCBUZW5zb3JJbmZvLCBUZW5zb3JMaXN0TWFwfSBmcm9tICcuLi9kYXRhL3R5cGVzJztcbmltcG9ydCB7Z2V0Tm9kZU5hbWVBbmRJbmRleCwgZ2V0UGFyYW1WYWx1ZSwgZ2V0VGVuc29yLCBnZXRUZW5zb3JzRm9yQ3VycmVudENvbnRleHQsIHBhcnNlTm9kZU5hbWV9IGZyb20gJy4uL29wZXJhdGlvbnMvZXhlY3V0b3JzL3V0aWxzJztcbmltcG9ydCB7ZXhlY3V0ZU9wfSBmcm9tICcuLi9vcGVyYXRpb25zL29wZXJhdGlvbl9leGVjdXRvcic7XG5pbXBvcnQge0dyYXBoLCBOb2RlfSBmcm9tICcuLi9vcGVyYXRpb25zL3R5cGVzJztcblxuaW1wb3J0IHtFeGVjdXRpb25Db250ZXh0LCBFeGVjdXRpb25Db250ZXh0SW5mb30gZnJvbSAnLi9leGVjdXRpb25fY29udGV4dCc7XG5pbXBvcnQge2dldEV4ZWN1dGlvblN1YmdyYXBoLCBnZXROb2RlTGl2ZVVudGlsTWFwLCBnZXROb2Rlc0luVG9wb2xvZ2ljYWxPcmRlciwgaXNDb250cm9sRmxvd30gZnJvbSAnLi9tb2RlbF9hbmFseXNpcyc7XG5pbXBvcnQge1Jlc291cmNlTWFuYWdlcn0gZnJvbSAnLi9yZXNvdXJjZV9tYW5hZ2VyJztcbmltcG9ydCB7RnVuY3Rpb25FeGVjdXRvcn0gZnJvbSAnLi90eXBlcyc7XG5cbmludGVyZmFjZSBOb2RlV2l0aENvbnRleHRzIHtcbiAgY29udGV4dHM6IEV4ZWN1dGlvbkNvbnRleHRJbmZvW107XG4gIG5vZGU6IE5vZGU7XG59XG5cbmV4cG9ydCBjbGFzcyBHcmFwaEV4ZWN1dG9yIGltcGxlbWVudHMgRnVuY3Rpb25FeGVjdXRvciB7XG4gIHByaXZhdGUgY29tcGlsZWRNYXAgPSBuZXcgTWFwPHN0cmluZywgUmV0dXJuVHlwZTx0eXBlb2YgdGhpcy5jb21waWxlPj4oKTtcbiAgcHJpdmF0ZSBwYXJzZU5vZGVOYW1lQ2FjaGUgPSBuZXcgTWFwPHN0cmluZywgW3N0cmluZywgbnVtYmVyLCBzdHJpbmc/XT4oKTtcbiAgcHJpdmF0ZSBfd2VpZ2h0TWFwOiBOYW1lZFRlbnNvcnNNYXAgPSB7fTtcbiAgcHJpdmF0ZSBfd2VpZ2h0SWRzOiBudW1iZXJbXTtcbiAgcHJpdmF0ZSBfc2lnbmF0dXJlOiBJU2lnbmF0dXJlRGVmO1xuICBwcml2YXRlIF9pbnB1dHM6IE5vZGVbXTtcbiAgcHJpdmF0ZSBfb3V0cHV0czogTm9kZVtdO1xuICBwcml2YXRlIF9pbml0Tm9kZXM6IE5vZGVbXTsgIC8vIEludGVybmFsIGluaXQgbm9kZXMgdG8gc3RhcnQgaW5pdGlhbGl6YXRpb24uXG4gIHByaXZhdGUgU0VQQVJBVE9SID0gJywnO1xuICBwcml2YXRlIF9mdW5jdGlvbnM6IHtba2V5OiBzdHJpbmddOiBHcmFwaH0gPSB7fTtcbiAgcHJpdmF0ZSBfZnVuY3Rpb25FeGVjdXRvck1hcDoge1trZXk6IHN0cmluZ106IEZ1bmN0aW9uRXhlY3V0b3J9ID0ge307XG4gIHByaXZhdGUgX3Jlc291cmNlTWFuYWdlcjogUmVzb3VyY2VNYW5hZ2VyO1xuICBwcml2YXRlIGNsb25lZFRlbnNvcnNNYXA6IE5hbWVkVGVuc29yc01hcDtcbiAgcHJpdmF0ZSBrZWVwSW50ZXJtZWRpYXRlVGVuc29ycyA9IGZhbHNlO1xuXG4gIGdldCB3ZWlnaHRJZHMoKTogbnVtYmVyW10ge1xuICAgIHJldHVybiB0aGlzLnBhcmVudCA/IHRoaXMucGFyZW50LndlaWdodElkcyA6IHRoaXMuX3dlaWdodElkcztcbiAgfVxuXG4gIGdldCBmdW5jdGlvbkV4ZWN1dG9yTWFwKCk6IHtba2V5OiBzdHJpbmddOiBGdW5jdGlvbkV4ZWN1dG9yfSB7XG4gICAgcmV0dXJuIHRoaXMucGFyZW50ID8gdGhpcy5wYXJlbnQuZnVuY3Rpb25FeGVjdXRvck1hcCA6XG4gICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5fZnVuY3Rpb25FeGVjdXRvck1hcDtcbiAgfVxuXG4gIGdldCB3ZWlnaHRNYXAoKTogTmFtZWRUZW5zb3JzTWFwIHtcbiAgICByZXR1cm4gdGhpcy5wYXJlbnQgPyB0aGlzLnBhcmVudC53ZWlnaHRNYXAgOiB0aGlzLl93ZWlnaHRNYXA7XG4gIH1cblxuICBzZXQgd2VpZ2h0TWFwKHdlaWdodE1hcDogTmFtZWRUZW5zb3JzTWFwKSB7XG4gICAgY29uc3Qgd2VpZ2h0SWRzID0gT2JqZWN0LmtleXMod2VpZ2h0TWFwKS5tYXAoXG4gICAgICAgIGtleSA9PiB3ZWlnaHRNYXBba2V5XS5tYXAodGVuc29yID0+IHRlbnNvci5pZCkpO1xuICAgIHRoaXMuX3dlaWdodElkcyA9IFtdLmNvbmNhdCguLi53ZWlnaHRJZHMpO1xuICAgIHRoaXMuX3dlaWdodE1hcCA9IHdlaWdodE1hcDtcbiAgfVxuXG4gIC8qKlxuICAgKiBTZXQgYFJlc291cmNlTWFuYWdlcmAgc2hhcmVkIGJ5IGV4ZWN1dG9ycyBvZiBhIG1vZGVsLlxuICAgKiBAcGFyYW0gcmVzb3VyY2VNYW5hZ2VyOiBgUmVzb3VyY2VNYW5hZ2VyYCBvZiB0aGUgYEdyYXBoTW9kZWxgLlxuICAgKi9cbiAgc2V0IHJlc291cmNlTWFuYWdlcihyZXNvdXJjZU1hbmFnZXI6IFJlc291cmNlTWFuYWdlcikge1xuICAgIHRoaXMuX3Jlc291cmNlTWFuYWdlciA9IHJlc291cmNlTWFuYWdlcjtcbiAgfVxuXG4gIGdldCBpbnB1dHMoKTogVGVuc29ySW5mb1tdIHtcbiAgICByZXR1cm4gdGhpcy5faW5wdXRzLm1hcChub2RlID0+IHtcbiAgICAgIHJldHVybiB7XG4gICAgICAgIG5hbWU6IG5vZGUubmFtZSxcbiAgICAgICAgc2hhcGU6IG5vZGUuYXR0clBhcmFtc1snc2hhcGUnXSA/XG4gICAgICAgICAgICBub2RlLmF0dHJQYXJhbXNbJ3NoYXBlJ10udmFsdWUgYXMgbnVtYmVyW10gOlxuICAgICAgICAgICAgdW5kZWZpbmVkLFxuICAgICAgICBkdHlwZTogbm9kZS5hdHRyUGFyYW1zWydkdHlwZSddID9cbiAgICAgICAgICAgIG5vZGUuYXR0clBhcmFtc1snZHR5cGUnXS52YWx1ZSBhcyBEYXRhVHlwZSA6XG4gICAgICAgICAgICB1bmRlZmluZWRcbiAgICAgIH07XG4gICAgfSk7XG4gIH1cblxuICBnZXQgb3V0cHV0cygpOiBUZW5zb3JJbmZvW10ge1xuICAgIHJldHVybiB0aGlzLl9vdXRwdXRzLm1hcChub2RlID0+IHtcbiAgICAgIHJldHVybiB7XG4gICAgICAgIG5hbWU6IG5vZGUubmFtZSxcbiAgICAgICAgc2hhcGU6IG5vZGUuYXR0clBhcmFtc1snc2hhcGUnXSA/XG4gICAgICAgICAgICBub2RlLmF0dHJQYXJhbXNbJ3NoYXBlJ10udmFsdWUgYXMgbnVtYmVyW10gOlxuICAgICAgICAgICAgdW5kZWZpbmVkLFxuICAgICAgICBkdHlwZTogbm9kZS5hdHRyUGFyYW1zWydkdHlwZSddID9cbiAgICAgICAgICAgIG5vZGUuYXR0clBhcmFtc1snZHR5cGUnXS52YWx1ZSBhcyBEYXRhVHlwZSA6XG4gICAgICAgICAgICB1bmRlZmluZWRcbiAgICAgIH07XG4gICAgfSk7XG4gIH1cblxuICBnZXQgaW5wdXROb2RlcygpOiBzdHJpbmdbXSB7XG4gICAgcmV0dXJuIHRoaXMuX2lucHV0cy5tYXAobm9kZSA9PiBub2RlLnNpZ25hdHVyZUtleSB8fCBub2RlLm5hbWUpO1xuICB9XG5cbiAgZ2V0IG91dHB1dE5vZGVzKCk6IHN0cmluZ1tdIHtcbiAgICByZXR1cm4gdGhpcy5fb3V0cHV0cy5tYXAoKG5vZGUpID0+IHtcbiAgICAgIGNvbnN0IG5hbWUgPSBub2RlLnNpZ25hdHVyZUtleSB8fCBub2RlLm5hbWU7XG4gICAgICByZXR1cm4gbm9kZS5kZWZhdWx0T3V0cHV0ID8gKGAke25hbWV9OiR7bm9kZS5kZWZhdWx0T3V0cHV0fWApIDogbmFtZTtcbiAgICB9KTtcbiAgfVxuXG4gIGdldCBmdW5jdGlvbnMoKToge1trZXk6IHN0cmluZ106IElTaWduYXR1cmVEZWZ9IHtcbiAgICByZXR1cm4gT2JqZWN0LmtleXModGhpcy5fZnVuY3Rpb25zKS5yZWR1Y2UoKG1hcCwga2V5KSA9PiB7XG4gICAgICBtYXBba2V5XSA9IHRoaXMuX2Z1bmN0aW9uc1trZXldLnNpZ25hdHVyZTtcbiAgICAgIHJldHVybiBtYXA7XG4gICAgfSwge30gYXMge1trZXk6IHN0cmluZ106IElTaWduYXR1cmVEZWZ9KTtcbiAgfVxuXG4gIC8qKlxuICAgKlxuICAgKiBAcGFyYW0gZ3JhcGggR3JhcGggdGhlIG1vZGVsIG9yIGZ1bmN0aW9uIGdyYXBoIHRvIGJlIGV4ZWN1dGVkLlxuICAgKiBAcGFyYW0gcGFyZW50IFdoZW4gYnVpbGRpbmcgZnVuY3Rpb24gZXhlY3RvciB5b3UgbmVlZCB0byBzZXQgdGhlIHBhcmVudFxuICAgKiBleGVjdXRvci4gU2luY2UgdGhlIHdlaWdodHMgYW5kIGZ1bmN0aW9uIGV4ZWN1dG9yIG1hcHMgYXJlIHNldCBhdCBwYXJhbnRcbiAgICogbGV2ZWwsIHRoYXQgZnVuY3Rpb24gZXhlY3V0b3IgY2FuIGFjY2VzcyB0aGUgZnVuY3Rpb24gbWFwcyBhbmQgd2VpZ2h0IG1hcHNcbiAgICogdGhyb3VnaCB0aGUgcGFyZW50LlxuICAgKi9cbiAgY29uc3RydWN0b3IocHJpdmF0ZSBncmFwaDogR3JhcGgsIHByaXZhdGUgcGFyZW50PzogR3JhcGhFeGVjdXRvcikge1xuICAgIHRoaXMuX291dHB1dHMgPSBncmFwaC5vdXRwdXRzO1xuICAgIHRoaXMuX2lucHV0cyA9IGdyYXBoLmlucHV0cztcbiAgICB0aGlzLl9pbml0Tm9kZXMgPSBncmFwaC5pbml0Tm9kZXM7XG4gICAgdGhpcy5fc2lnbmF0dXJlID0gZ3JhcGguc2lnbmF0dXJlO1xuICAgIHRoaXMuX2Z1bmN0aW9ucyA9IGdyYXBoLmZ1bmN0aW9ucztcbiAgICAvLyBjcmVhdGUgc3ViLWdyYXBoIGV4ZWN1dG9yc1xuICAgIGlmIChncmFwaC5mdW5jdGlvbnMgIT0gbnVsbCkge1xuICAgICAgT2JqZWN0LmtleXMoZ3JhcGguZnVuY3Rpb25zKS5mb3JFYWNoKG5hbWUgPT4ge1xuICAgICAgICB0aGlzLl9mdW5jdGlvbkV4ZWN1dG9yTWFwW25hbWVdID1cbiAgICAgICAgICAgIG5ldyBHcmFwaEV4ZWN1dG9yKGdyYXBoLmZ1bmN0aW9uc1tuYW1lXSwgdGhpcyk7XG4gICAgICB9KTtcbiAgICB9XG4gIH1cblxuICBwcml2YXRlIGdldENvbXBpbGF0aW9uS2V5KGlucHV0czogTm9kZVtdLCBvdXRwdXRzOiBOb2RlW10pOiBzdHJpbmcge1xuICAgIGNvbnN0IHNvcnRlZElucHV0cyA9IGlucHV0cy5tYXAobm9kZSA9PiBub2RlLm5hbWUpLnNvcnQoKTtcbiAgICBjb25zdCBzb3J0ZWRPdXRwdXRzID0gb3V0cHV0cy5tYXAobm9kZSA9PiBub2RlLm5hbWUpLnNvcnQoKTtcbiAgICByZXR1cm4gc29ydGVkSW5wdXRzLmpvaW4odGhpcy5TRVBBUkFUT1IpICsgJy0tJyArXG4gICAgICAgIHNvcnRlZE91dHB1dHMuam9pbih0aGlzLlNFUEFSQVRPUik7XG4gIH1cblxuICAvKipcbiAgICogQ29tcGlsZXMgdGhlIGluZmVyZW5jZSBncmFwaCBhbmQgcmV0dXJucyB0aGUgbWluaW1hbCBzZXQgb2Ygbm9kZXMgdGhhdCBhcmVcbiAgICogcmVxdWlyZWQgZm9yIGV4ZWN1dGlvbiwgaW4gdGhlIGNvcnJlY3QgZXhlY3V0aW9uIG9yZGVyLlxuICAgKiBAcmV0dXJucyB7T2JqZWN0fSBjb21waWxhdGlvbiBUaGUgY29tcGlsZSByZXN1bHQuXG4gICAqIEByZXR1cm5zIHtOb2RlW119IGNvbXBpbGF0aW9uLm9yZGVyZWROb2RlcyBOb2RlcyBpbiB0aGUgY29ycmVjdCBleGVjdXRpb25cbiAgICogICAgIG9yZGVyLlxuICAgKiBAcmV0dXJucyB7TWFwPHN0cmluZywgTm9kZVtdPn0gY29tcGlsYXRpb24ubm9kZUxpdmVVbnRpbE1hcCBBIG1hcCBmcm9tIG5vZGVcbiAgICogICAgIHRvIGRpc3Bvc2FibGUgbm9kZXMgYWZ0ZXIgaXRzIGV4ZWN1dGlvbi4gVGhhdCBpcywgZm9yIGEgbm9kZSBgeGAsXG4gICAqICAgICBgbm9kZUxpdmVVbnRpbE1hcFt4XWAgaW5kaWNhdGVzIGFsbCBub2RlcyB3aG9zZSBpbnRlcm1lZGlhdGVcbiAgICogICAgIHRlbnNvcnMgc2hvdWxkIGJlIGRpc3Bvc2VkIGFmdGVyIGB4YCBpcyBleGVjdXRlZC5cbiAgICovXG4gIHByaXZhdGUgY29tcGlsZShpbnB1dHM6IE5hbWVkVGVuc29yTWFwLCBvdXRwdXRzOiBOb2RlW10pOlxuICAgICAge29yZGVyZWROb2RlczogTm9kZVtdLCBub2RlTGl2ZVVudGlsTWFwOiBNYXA8c3RyaW5nLCBOb2RlW10+fSB7XG4gICAgY29uc3QgZXhlY3V0aW9uSW5mbyA9XG4gICAgICAgIGdldEV4ZWN1dGlvblN1YmdyYXBoKGlucHV0cywgb3V0cHV0cywgdGhpcy53ZWlnaHRNYXAsIHRoaXMuX2luaXROb2Rlcyk7XG4gICAgY29uc3Qge21pc3NpbmdJbnB1dHMsIGR5bmFtaWNOb2RlLCBzeW5jSW5wdXRzfSA9IGV4ZWN1dGlvbkluZm87XG4gICAgaWYgKGR5bmFtaWNOb2RlICE9IG51bGwpIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICBgVGhpcyBleGVjdXRpb24gY29udGFpbnMgdGhlIG5vZGUgJyR7ZHluYW1pY05vZGUubmFtZX0nLCB3aGljaCBoYXMgYCArXG4gICAgICAgICAgYHRoZSBkeW5hbWljIG9wICcke2R5bmFtaWNOb2RlLm9wfScuIFBsZWFzZSB1c2UgYCArXG4gICAgICAgICAgYG1vZGVsLmV4ZWN1dGVBc3luYygpIGluc3RlYWQuIEFsdGVybmF0aXZlbHksIHRvIGF2b2lkIHRoZSBgICtcbiAgICAgICAgICBgZHluYW1pYyBvcHMsIHNwZWNpZnkgdGhlIGlucHV0cyBbJHtzeW5jSW5wdXRzfV1gKTtcbiAgICB9XG5cbiAgICBpZiAobWlzc2luZ0lucHV0cy5sZW5ndGggPiAwKSB7XG4gICAgICBjb25zdCBvdXROYW1lcyA9IG91dHB1dHMubWFwKG4gPT4gbi5uYW1lKTtcbiAgICAgIGNvbnN0IGluTmFtZXMgPSBPYmplY3Qua2V5cyhpbnB1dHMpO1xuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgIGBDYW5ub3QgY29tcHV0ZSB0aGUgb3V0cHV0cyBbJHtvdXROYW1lc31dIGZyb20gdGhlIHByb3ZpZGVkIGlucHV0cyBgICtcbiAgICAgICAgICBgWyR7aW5OYW1lc31dLiBNaXNzaW5nIHRoZSBmb2xsb3dpbmcgaW5wdXRzOiBbJHttaXNzaW5nSW5wdXRzfV1gKTtcbiAgICB9XG5cbiAgICBjb25zdCBvcmRlcmVkTm9kZXMgPSBnZXROb2Rlc0luVG9wb2xvZ2ljYWxPcmRlcih0aGlzLmdyYXBoLCBleGVjdXRpb25JbmZvKTtcbiAgICBjb25zdCBub2RlTGl2ZVVudGlsTWFwID0gZ2V0Tm9kZUxpdmVVbnRpbE1hcChvcmRlcmVkTm9kZXMpO1xuICAgIHJldHVybiB7b3JkZXJlZE5vZGVzLCBub2RlTGl2ZVVudGlsTWFwfTtcbiAgfVxuXG4gIHByaXZhdGUgY2xvbmVBbmRLZWVwVGVuc29yKHRlbnNvcjogVGVuc29yKSB7XG4gICAgaWYgKHRlbnNvciA9PSBudWxsKSB7XG4gICAgICByZXR1cm4gbnVsbDtcbiAgICB9XG4gICAgY29uc3QgY2xvbmUgPSB0ZW5zb3IuY2xvbmUoKTtcbiAgICAvLyBLZWVwIHRoZSBjbG9uZSBiZWNhdXNlYG1vZGVsLmV4ZWN1dGUoKWAgbWF5IGJlIGNhbGxlZCB3aXRoaW5cbiAgICAvLyBhIGB0aWR5KClgLCBidXQgdGhlIHVzZXIgbWF5IGluc3BlY3QgdGhlc2UgdGVuc29ycyBhZnRlciB0aGVcbiAgICAvLyB0aWR5LlxuICAgIGtlZXAoY2xvbmUpO1xuICAgIHJldHVybiBjbG9uZTtcbiAgfVxuXG4gIHByaXZhdGUgY2xvbmVUZW5zb3JMaXN0KHRlbnNvcnM6IFRlbnNvcltdKSB7XG4gICAgaWYgKCF0ZW5zb3JzKSB7XG4gICAgICByZXR1cm4gbnVsbDtcbiAgICB9XG4gICAgY29uc3QgY2xvbmVkVGVuc29yID0gdGVuc29ycy5tYXAodGVuc29yID0+IHtcbiAgICAgIHJldHVybiB0aGlzLmNsb25lQW5kS2VlcFRlbnNvcih0ZW5zb3IpO1xuICAgIH0pO1xuICAgIHJldHVybiBjbG9uZWRUZW5zb3I7XG4gIH1cblxuICBwcml2YXRlIGNsb25lVGVuc29yTWFwKHRlbnNvcnNNYXA6IE5hbWVkVGVuc29yc01hcCk6IE5hbWVkVGVuc29yc01hcCB7XG4gICAgcmV0dXJuIE9iamVjdC5mcm9tRW50cmllcyhcbiAgICAgICAgT2JqZWN0LmVudHJpZXModGVuc29yc01hcCkubWFwKChbbmFtZSwgdGVuc29yc0xpc3RdKSA9PiB7XG4gICAgICAgICAgcmV0dXJuIFtuYW1lLCB0aGlzLmNsb25lVGVuc29yTGlzdCh0ZW5zb3JzTGlzdCldO1xuICAgICAgICB9KSk7XG4gIH1cblxuICAvKipcbiAgICogRXhlY3V0ZXMgdGhlIGluZmVyZW5jZSBmb3IgZ2l2ZW4gaW5wdXQgdGVuc29ycy5cbiAgICogQHBhcmFtIGlucHV0cyBUZW5zb3IgbWFwIGZvciB0aGUgbW9kZWwgaW5wdXRzLCBrZXllZCBieSB0aGUgaW5wdXQgbm9kZVxuICAgKiBuYW1lcy5cbiAgICogQHBhcmFtIG91dHB1dHMgT3B0aW9uYWwuIG91dHB1dCBub2RlIG5hbWUgZnJvbSB0aGUgVGVuc29yZmxvdyBtb2RlbCwgaWZcbiAgICogbm8gb3V0cHV0cyBhcmUgc3BlY2lmaWVkLCB0aGUgZGVmYXVsdCBvdXRwdXRzIG9mIHRoZSBtb2RlbCB3b3VsZCBiZSB1c2VkLlxuICAgKiBZb3UgY2FuIGluc3BlY3QgaW50ZXJtZWRpYXRlIG5vZGVzIG9mIHRoZSBtb2RlbCBieSBhZGRpbmcgdGhlbSB0byB0aGVcbiAgICogb3V0cHV0cyBhcnJheS5cbiAgICovXG4gIGV4ZWN1dGUoaW5wdXRzOiBOYW1lZFRlbnNvck1hcCwgb3V0cHV0cz86IHN0cmluZ1tdKTogVGVuc29yW10ge1xuICAgIC8vIERpc3Bvc2UgYW55IHRlbnNvcnMgZnJvbSBhIHByaW9yIHJ1biB0byBhdm9pZCBsZWFraW5nIHRoZW0uXG4gICAgdGhpcy5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ycygpO1xuICAgIGlucHV0cyA9IHRoaXMubWFwSW5wdXRzKGlucHV0cyk7XG4gICAgY29uc3QgbmFtZXMgPSBPYmplY3Qua2V5cyhpbnB1dHMpLnNvcnQoKTtcbiAgICB0aGlzLmNoZWNrSW5wdXRzKGlucHV0cyk7XG4gICAgdGhpcy5jaGVja0lucHV0U2hhcGVBbmRUeXBlKGlucHV0cyk7XG4gICAgb3V0cHV0cyA9IHRoaXMubWFwT3V0cHV0cyhvdXRwdXRzKTtcbiAgICB0aGlzLmNoZWNrT3V0cHV0cyhvdXRwdXRzKTtcbiAgICBjb25zdCBpbnB1dE5vZGVzID1cbiAgICAgICAgbmFtZXMubWFwKG5hbWUgPT4gdGhpcy5ncmFwaC5ub2Rlc1twYXJzZU5vZGVOYW1lKG5hbWUpWzBdXSk7XG4gICAgY29uc3Qgb3V0cHV0Tm9kZU5hbWVzID0gb3V0cHV0cy5tYXAobmFtZSA9PiBwYXJzZU5vZGVOYW1lKG5hbWUpWzBdKTtcbiAgICBjb25zdCBvdXRwdXROb2RlTmFtZVNldCA9IG5ldyBTZXQob3V0cHV0Tm9kZU5hbWVzKTtcbiAgICBsZXQgb3V0cHV0Tm9kZXMgPSBvdXRwdXROb2RlTmFtZXMubWFwKG5hbWUgPT4gdGhpcy5ncmFwaC5ub2Rlc1tuYW1lXSk7XG4gICAgLy8gSWYgbm8gb3V0cHV0cyBhcmUgc3BlY2lmaWVkLCB0aGVuIHVzZSB0aGUgZGVmYXVsdCBvdXRwdXRzIG9mIHRoZSBtb2RlbC5cbiAgICBpZiAob3V0cHV0Tm9kZXMubGVuZ3RoID09PSAwKSB7XG4gICAgICBvdXRwdXROb2RlcyA9IHRoaXMuX291dHB1dHM7XG4gICAgfVxuXG4gICAgY29uc3QgY29tcGlsYXRpb25LZXkgPSB0aGlzLmdldENvbXBpbGF0aW9uS2V5KGlucHV0Tm9kZXMsIG91dHB1dE5vZGVzKTtcblxuICAgIC8vIERvIG5vdGhpbmcgaWYgdGhlIGNvbXBpbGVkIGdyYXBoIGNhY2hlIGNvbnRhaW5zIHRoZSBpbnB1dC5cbiAgICBsZXQgY29tcGlsYXRpb24gPSB0aGlzLmNvbXBpbGVkTWFwLmdldChjb21waWxhdGlvbktleSk7XG4gICAgaWYgKGNvbXBpbGF0aW9uID09IG51bGwpIHtcbiAgICAgIGNvbXBpbGF0aW9uID0gdGhpcy5jb21waWxlKGlucHV0cywgb3V0cHV0Tm9kZXMpO1xuICAgICAgdGhpcy5jb21waWxlZE1hcC5zZXQoY29tcGlsYXRpb25LZXksIGNvbXBpbGF0aW9uKTtcbiAgICB9XG5cbiAgICAvLyBLZWVwIHRlbnNvcnMgaWYgS0VFUF9JTlRFUk1FRElBVEVfVEVOU09SUyBpcyBvbi5cbiAgICB0cnkge1xuICAgICAgdGhpcy5rZWVwSW50ZXJtZWRpYXRlVGVuc29ycyA9IGVudigpLmdldEJvb2woJ0tFRVBfSU5URVJNRURJQVRFX1RFTlNPUlMnKTtcbiAgICB9IGNhdGNoIChlKSB7XG4gICAgICB0aGlzLmtlZXBJbnRlcm1lZGlhdGVUZW5zb3JzID0gZmFsc2U7XG4gICAgICBjb25zb2xlLndhcm4oZS5tZXNzYWdlKTtcbiAgICB9XG4gICAgY29uc3QgdGVuc29yQXJyYXlNYXA6IFRlbnNvckFycmF5TWFwID0ge307XG4gICAgY29uc3QgdGVuc29yTGlzdE1hcDogVGVuc29yTGlzdE1hcCA9IHt9O1xuXG4gICAgcmV0dXJuIHRpZHkoKCkgPT4ge1xuICAgICAgY29uc3QgY29udGV4dCA9IG5ldyBFeGVjdXRpb25Db250ZXh0KFxuICAgICAgICAgIHRoaXMud2VpZ2h0TWFwLCB0ZW5zb3JBcnJheU1hcCwgdGVuc29yTGlzdE1hcCxcbiAgICAgICAgICB0aGlzLmZ1bmN0aW9uRXhlY3V0b3JNYXAsIHRoaXMucGFyc2VOb2RlTmFtZUNhY2hlKTtcbiAgICAgIGNvbnN0IHRlbnNvcnNNYXA6IE5hbWVkVGVuc29yc01hcCA9IHsuLi50aGlzLndlaWdodE1hcH07XG4gICAgICBpZiAodGhpcy5rZWVwSW50ZXJtZWRpYXRlVGVuc29ycykge1xuICAgICAgICB0aGlzLmNsb25lZFRlbnNvcnNNYXAgPSB0aGlzLmNsb25lVGVuc29yTWFwKHRoaXMud2VpZ2h0TWFwKTtcbiAgICAgIH1cblxuICAgICAgT2JqZWN0LmtleXMoaW5wdXRzKS5mb3JFYWNoKG5hbWUgPT4ge1xuICAgICAgICBjb25zdCBbbm9kZU5hbWUsIGluZGV4XSA9IHBhcnNlTm9kZU5hbWUobmFtZSwgY29udGV4dCk7XG4gICAgICAgIGNvbnN0IHRlbnNvcnM6IFRlbnNvcltdID0gW107XG4gICAgICAgIHRlbnNvcnNbaW5kZXhdID0gaW5wdXRzW25hbWVdO1xuICAgICAgICB0ZW5zb3JzTWFwW25vZGVOYW1lXSA9IHRlbnNvcnM7XG4gICAgICAgIGlmICh0aGlzLmtlZXBJbnRlcm1lZGlhdGVUZW5zb3JzKSB7XG4gICAgICAgICAgdGhpcy5jbG9uZWRUZW5zb3JzTWFwW25vZGVOYW1lXSA9IHRoaXMuY2xvbmVUZW5zb3JMaXN0KHRlbnNvcnMpO1xuICAgICAgICB9XG4gICAgICB9KTtcblxuICAgICAgY29uc3QgdGVuc29yc1RvS2VlcCA9IHRoaXMuZ2V0RnJvemVuVGVuc29ySWRzKHRlbnNvcnNNYXApO1xuICAgICAgY29uc3Qge29yZGVyZWROb2Rlcywgbm9kZUxpdmVVbnRpbE1hcH0gPSBjb21waWxhdGlvbjtcbiAgICAgIGZvciAoY29uc3Qgbm9kZSBvZiBvcmRlcmVkTm9kZXMpIHtcbiAgICAgICAgaWYgKHRlbnNvcnNNYXBbbm9kZS5uYW1lXSkge1xuICAgICAgICAgIGNvbnRpbnVlO1xuICAgICAgICB9XG4gICAgICAgIGNvbnN0IHRlbnNvcnMgPVxuICAgICAgICAgICAgZXhlY3V0ZU9wKG5vZGUsIHRlbnNvcnNNYXAsIGNvbnRleHQsIHRoaXMuX3Jlc291cmNlTWFuYWdlcikgYXNcbiAgICAgICAgICAgIFRlbnNvcltdO1xuICAgICAgICBpZiAodXRpbC5pc1Byb21pc2UodGVuc29ycykpIHtcbiAgICAgICAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICAgICAgICAgIGBUaGUgZXhlY3V0aW9uIG9mIHRoZSBvcCAnJHtub2RlLm9wfScgcmV0dXJuZWQgYSBwcm9taXNlLiBgICtcbiAgICAgICAgICAgICAgYFBsZWFzZSB1c2UgbW9kZWwuZXhlY3V0ZUFzeW5jKCkgaW5zdGVhZC5gKTtcbiAgICAgICAgfVxuICAgICAgICB0ZW5zb3JzTWFwW25vZGUubmFtZV0gPSB0ZW5zb3JzO1xuICAgICAgICBpZiAodGhpcy5rZWVwSW50ZXJtZWRpYXRlVGVuc29ycykge1xuICAgICAgICAgIHRoaXMuY2xvbmVkVGVuc29yc01hcFtub2RlLm5hbWVdID0gdGhpcy5jbG9uZVRlbnNvckxpc3QodGVuc29ycyk7XG4gICAgICAgIH1cbiAgICAgICAgdGhpcy5jaGVja1RlbnNvckZvckRpc3Bvc2FsV2l0aE5vZGVMaXZlVW50aWxJbmZvKFxuICAgICAgICAgICAgbm9kZSwgdGVuc29yc01hcCwgY29udGV4dCwgdGVuc29yc1RvS2VlcCwgb3V0cHV0Tm9kZU5hbWVTZXQsXG4gICAgICAgICAgICBub2RlTGl2ZVVudGlsTWFwLmdldChub2RlLm5hbWUpKTtcbiAgICAgIH1cblxuICAgICAgLy8gZGlzcG9zZSB0aGUgY29udGV4dCBmb3IgdGhlIHJvb3QgZXhlY3V0b3JcbiAgICAgIGlmICh0aGlzLnBhcmVudCA9PSBudWxsKSB7XG4gICAgICAgIGNvbnRleHQuZGlzcG9zZSh0ZW5zb3JzVG9LZWVwKTtcbiAgICAgIH1cblxuICAgICAgcmV0dXJuIG91dHB1dHMubWFwKG5hbWUgPT4gZ2V0VGVuc29yKG5hbWUsIHRlbnNvcnNNYXAsIGNvbnRleHQpKTtcbiAgICB9KTtcbiAgfVxuXG4gIHByaXZhdGUgZ2V0RnJvemVuVGVuc29ySWRzKHRlbnNvck1hcDogTmFtZWRUZW5zb3JzTWFwKTogU2V0PG51bWJlcj4ge1xuICAgIGNvbnN0IGlkcyA9IFtdLmNvbmNhdC5hcHBseShcbiAgICAgICAgW10sXG4gICAgICAgIE9iamVjdC5rZXlzKHRlbnNvck1hcClcbiAgICAgICAgICAgIC5tYXAoa2V5ID0+IHRlbnNvck1hcFtrZXldKVxuICAgICAgICAgICAgLm1hcCh0ZW5zb3JzID0+IHRlbnNvcnMubWFwKHRlbnNvciA9PiB0ZW5zb3IuaWQpKSk7XG4gICAgcmV0dXJuIG5ldyBTZXQoaWRzKTtcbiAgfVxuXG4gIHByaXZhdGUgY2hlY2tUZW5zb3JGb3JEaXNwb3NhbChcbiAgICAgIG5vZGVOYW1lOiBzdHJpbmcsIG5vZGU6IE5vZGUsIHRlbnNvck1hcDogTmFtZWRUZW5zb3JzTWFwLFxuICAgICAgY29udGV4dDogRXhlY3V0aW9uQ29udGV4dCwgdGVuc29yc1RvS2VlcDogU2V0PG51bWJlcj4sXG4gICAgICBvdXRwdXROb2RlTmFtZVNldDogU2V0PHN0cmluZz4sXG4gICAgICBpbnRlcm1lZGlhdGVUZW5zb3JDb25zdW1lckNvdW50OiB7W2tleTogc3RyaW5nXTogbnVtYmVyfSkge1xuICAgIC8vIFNraXAgb3V0cHV0IG5vZGVzIGFuZCBhbnkgY29udHJvbCBmbG93IG5vZGVzLCBzaW5jZSBpdHMgZGVwZW5kZW5jeSBpc1xuICAgIC8vIHRyaWNreSB0byB0cmFjayBjb3JyZWN0bHkuXG4gICAgaWYgKGlzQ29udHJvbEZsb3cobm9kZSkgfHwgb3V0cHV0Tm9kZU5hbWVTZXQuaGFzKG5vZGVOYW1lKSkge1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGZvciAoY29uc3QgdGVuc29yIG9mIHRlbnNvck1hcFtub2RlTmFtZV0pIHtcbiAgICAgIGlmICh0ZW5zb3IgPT0gbnVsbCkge1xuICAgICAgICBjb250aW51ZTtcbiAgICAgIH1cbiAgICAgIGludGVybWVkaWF0ZVRlbnNvckNvbnN1bWVyQ291bnRbdGVuc29yLmlkXSA9XG4gICAgICAgICAgKGludGVybWVkaWF0ZVRlbnNvckNvbnN1bWVyQ291bnRbdGVuc29yLmlkXSB8fCAwKSArXG4gICAgICAgICAgbm9kZS5jaGlsZHJlbi5sZW5ndGg7XG4gICAgfVxuXG4gICAgZm9yIChjb25zdCBpbnB1dCBvZiBub2RlLmlucHV0cykge1xuICAgICAgLy8gU2tpcCBhbnkgY29udHJvbCBmbG93IG5vZGVzLCBzaW5jZSBpdHMgZGVwZW5kZW5jeSBpcyB0cmlja3kgdG8gdHJhY2tcbiAgICAgIC8vIGNvcnJlY3RseS5cbiAgICAgIGlmIChpc0NvbnRyb2xGbG93KGlucHV0KSkge1xuICAgICAgICBjb250aW51ZTtcbiAgICAgIH1cblxuICAgICAgY29uc3QgdGVuc29ycyA9XG4gICAgICAgICAgZ2V0VGVuc29yc0ZvckN1cnJlbnRDb250ZXh0KGlucHV0Lm5hbWUsIHRlbnNvck1hcCwgY29udGV4dCk7XG4gICAgICBpZiAodGVuc29ycyA9PSBudWxsKSB7XG4gICAgICAgIGNvbnRpbnVlO1xuICAgICAgfVxuXG4gICAgICBmb3IgKGNvbnN0IHRlbnNvciBvZiB0ZW5zb3JzKSB7XG4gICAgICAgIGlmICghdGVuc29yIHx8IHRlbnNvci5rZXB0IHx8IHRlbnNvcnNUb0tlZXAuaGFzKHRlbnNvci5pZCkpIHtcbiAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgfVxuXG4gICAgICAgIC8vIE9ubHkgaW50ZXJtZWRpYXRlIG5vZGVzJyB0ZW5zb3JzIGhhdmUgY291bnRzIHNldCwgbm90IG1hcmtlZCBhc1xuICAgICAgICAvLyBrZXB0LCBhbmQgbm90IGluIGB0ZW5zb3JzVG9LZWVwYC5cbiAgICAgICAgLy8gSW5wdXQgYW5kIHdlaWdodCBub2RlcycgdGVuc29ycyBzaG91bGQgZXhpc3QgaW4gYHRlbnNvcnNUb0tlZXBgLlxuICAgICAgICAvLyBPdXRwdXQgYW5kIGNvbnRyb2wgZmxvdyBub2RlcycgdGVuc29ycyBzaG91bGQgbmV2ZXIgaGF2ZSBjb3VudCBzZXQuXG4gICAgICAgIGNvbnN0IGNvdW50ID0gaW50ZXJtZWRpYXRlVGVuc29yQ29uc3VtZXJDb3VudFt0ZW5zb3IuaWRdO1xuICAgICAgICBpZiAoY291bnQgPT09IDEpIHtcbiAgICAgICAgICB0ZW5zb3IuZGlzcG9zZSgpO1xuICAgICAgICAgIGRlbGV0ZSBpbnRlcm1lZGlhdGVUZW5zb3JDb25zdW1lckNvdW50W3RlbnNvci5pZF07XG4gICAgICAgIH0gZWxzZSBpZiAoY291bnQgIT0gbnVsbCkge1xuICAgICAgICAgIGludGVybWVkaWF0ZVRlbnNvckNvbnN1bWVyQ291bnRbdGVuc29yLmlkXS0tO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuICB9XG5cbiAgcHJpdmF0ZSBjaGVja1RlbnNvckZvckRpc3Bvc2FsV2l0aE5vZGVMaXZlVW50aWxJbmZvKFxuICAgICAgbm9kZTogTm9kZSwgdGVuc29yTWFwOiBOYW1lZFRlbnNvcnNNYXAsIGNvbnRleHQ6IEV4ZWN1dGlvbkNvbnRleHQsXG4gICAgICB0ZW5zb3JzVG9LZWVwOiBTZXQ8bnVtYmVyPiwgb3V0cHV0Tm9kZU5hbWVTZXQ6IFNldDxzdHJpbmc+LFxuICAgICAgbGl2ZVVudGlsTm9kZXM/OiBOb2RlW10pIHtcbiAgICBmdW5jdGlvbiBpc05vbkRpc3Bvc2FibGVOb2RlKG5vZGU6IE5vZGUpIHtcbiAgICAgIC8vIFNraXAgb3V0cHV0IG5vZGVzIGFuZCBhbnkgY29udHJvbCBmbG93IG5vZGVzLCBzaW5jZSBpdHMgZGVwZW5kZW5jeSBpc1xuICAgICAgLy8gdHJpY2t5IHRvIHRyYWNrIGNvcnJlY3RseS5cbiAgICAgIHJldHVybiBpc0NvbnRyb2xGbG93KG5vZGUpIHx8IG91dHB1dE5vZGVOYW1lU2V0Lmhhcyhub2RlLm5hbWUpO1xuICAgIH1cblxuICAgIGlmIChpc0NvbnRyb2xGbG93KG5vZGUpIHx8IGxpdmVVbnRpbE5vZGVzID09IG51bGwpIHtcbiAgICAgIHJldHVybjtcbiAgICB9XG5cbiAgICBmb3IgKGNvbnN0IG5vZGVUb0Rpc3Bvc2Ugb2YgbGl2ZVVudGlsTm9kZXMpIHtcbiAgICAgIGlmIChpc05vbkRpc3Bvc2FibGVOb2RlKG5vZGVUb0Rpc3Bvc2UpKSB7XG4gICAgICAgIGNvbnRpbnVlO1xuICAgICAgfVxuICAgICAgY29uc3QgdGVuc29ycyA9IGdldFRlbnNvcnNGb3JDdXJyZW50Q29udGV4dChcbiAgICAgICAgICBub2RlVG9EaXNwb3NlLm5hbWUsIHRlbnNvck1hcCwgY29udGV4dCk7XG4gICAgICBmb3IgKGNvbnN0IHRlbnNvciBvZiB0ZW5zb3JzKSB7XG4gICAgICAgIGlmICghdGVuc29yIHx8IHRlbnNvci5rZXB0IHx8IHRlbnNvcnNUb0tlZXAuaGFzKHRlbnNvci5pZCkpIHtcbiAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgfVxuICAgICAgICB0ZW5zb3IuZGlzcG9zZSgpO1xuICAgICAgfVxuICAgIH1cbiAgfVxuXG4gIC8qKlxuICAgKiBFeGVjdXRlcyB0aGUgaW5mZXJlbmNlIGZvciBnaXZlbiBpbnB1dCB0ZW5zb3JzIGluIEFzeW5jIGZhc2hpb24uXG4gICAqIEBwYXJhbSBpbnB1dHMgVGVuc29yIG1hcCBmb3IgdGhlIG1vZGVsIGlucHV0cywga2V5ZWQgYnkgdGhlIGlucHV0IG5vZGVcbiAgICogbmFtZXMuXG4gICAqIEBwYXJhbSBvdXRwdXRzIG91dHB1dCBub2RlIG5hbWUgZnJvbSB0aGUgVGVuc29yZmxvdyBtb2RlbCwgaWYgbm8gb3V0cHV0c1xuICAgKiBhcmUgc3BlY2lmaWVkLCB0aGUgZGVmYXVsdCBvdXRwdXRzIG9mIHRoZSBtb2RlbCB3b3VsZCBiZSB1c2VkLiBZb3UgY2FuXG4gICAqIGluc3BlY3QgaW50ZXJtZWRpYXRlIG5vZGVzIG9mIHRoZSBtb2RlbCBieSBhZGRpbmcgdGhlbSB0byB0aGUgb3V0cHV0c1xuICAgKiBhcnJheS5cbiAgICovXG4gIGFzeW5jIGV4ZWN1dGVBc3luYyhpbnB1dHM6IE5hbWVkVGVuc29yTWFwLCBvdXRwdXRzPzogc3RyaW5nW10pOlxuICAgICAgUHJvbWlzZTxUZW5zb3JbXT4ge1xuICAgIHJldHVybiB0aGlzLl9leGVjdXRlQXN5bmMoaW5wdXRzLCBvdXRwdXRzKTtcbiAgfVxuXG4gIGRpc3Bvc2VJbnRlcm1lZGlhdGVUZW5zb3JzKCkge1xuICAgIGlmICghdGhpcy5jbG9uZWRUZW5zb3JzTWFwKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuICAgIE9iamVjdC52YWx1ZXModGhpcy5jbG9uZWRUZW5zb3JzTWFwKS5mb3JFYWNoKHRlbnNvcnNMaXN0ID0+IHtcbiAgICAgIGZvciAoY29uc3QgdGVuc29yIG9mIHRlbnNvcnNMaXN0KSB7XG4gICAgICAgIGlmICh0ZW5zb3IgJiYgIXRlbnNvci5pc0Rpc3Bvc2VkKSB7XG4gICAgICAgICAgdGVuc29yLmRpc3Bvc2UoKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0pO1xuXG4gICAgdGhpcy5jbG9uZWRUZW5zb3JzTWFwID0gbnVsbDtcbiAgfVxuXG4gIGdldEludGVybWVkaWF0ZVRlbnNvcnMoKTogTmFtZWRUZW5zb3JzTWFwIHtcbiAgICByZXR1cm4gdGhpcy5jbG9uZWRUZW5zb3JzTWFwO1xuICB9XG5cbiAgLyoqXG4gICAqIEV4ZWN1dGVzIHRoZSBpbmZlcmVuY2UgZm9yIGdpdmVuIGlucHV0IHRlbnNvcnMgaW4gQXN5bmMgZmFzaGlvbi5cbiAgICogQHBhcmFtIGlucHV0cyBUZW5zb3IgbWFwIGZvciB0aGUgbW9kZWwgaW5wdXRzLCBrZXllZCBieSB0aGUgaW5wdXQgbm9kZVxuICAgKiBuYW1lcy5cbiAgICogQHBhcmFtIG91dHB1dHMgT3B0aW9uYWwuIG91dHB1dCBub2RlIG5hbWUgZnJvbSB0aGUgVGVuc29yZmxvdyBtb2RlbCxcbiAgICogaWYgbm8gb3V0cHV0cyBhcmUgc3BlY2lmaWVkLCB0aGUgZGVmYXVsdCBvdXRwdXRzIG9mIHRoZSBtb2RlbCB3b3VsZCBiZVxuICAgKiB1c2VkLiBZb3UgY2FuIGluc3BlY3QgaW50ZXJtZWRpYXRlIG5vZGVzIG9mIHRoZSBtb2RlbCBieSBhZGRpbmcgdGhlbSB0b1xuICAgKiB0aGUgb3V0cHV0cyBhcnJheS5cbiAgICogQHBhcmFtIGlzRnVuY3Rpb25FeGVjdXRpb24gT3B0aW9uYWwuIEZsYWcgZm9yIGV4ZWN1dGluZyBhIGZ1bmN0aW9uLlxuICAgKiBAcGFyYW0gdGVuc29yQXJyYXlNYXAgT3B0aW9uYWwsIGdsb2JhbCBUZW5zb3JBcnJheSBtYXAgYnkgaWQuIFVzZWQgZm9yXG4gICAqIGZ1bmN0aW9uIGV4ZWN1dGlvbi5cbiAgICogQHBhcmFtIHRlbnNvckFycmF5TWFwIE9wdGluYWwgZ2xvYmFsIFRlbnNvckxpc3QgbWFwIGJ5IGlkLiBVc2VkIGZvclxuICAgKiBmdW5jdGlvbiBleGVjdXRpb24uXG4gICAqL1xuICBwcml2YXRlIGFzeW5jIF9leGVjdXRlQXN5bmMoXG4gICAgICBpbnB1dHM6IE5hbWVkVGVuc29yTWFwLCBvdXRwdXRzPzogc3RyaW5nW10sIGlzRnVuY3Rpb25FeGVjdXRpb24gPSBmYWxzZSxcbiAgICAgIHRlbnNvckFycmF5TWFwOiBUZW5zb3JBcnJheU1hcCA9IHt9LFxuICAgICAgdGVuc29yTGlzdE1hcDogVGVuc29yTGlzdE1hcCA9IHt9KTogUHJvbWlzZTxUZW5zb3JbXT4ge1xuICAgIC8vIERpc3Bvc2UgYW55IHRlbnNvcnMgZnJvbSBhIHByaW9yIHJ1biB0byBhdm9pZCBsZWFraW5nIHRoZW0uXG4gICAgdGhpcy5kaXNwb3NlSW50ZXJtZWRpYXRlVGVuc29ycygpO1xuICAgIGlmICghaXNGdW5jdGlvbkV4ZWN1dGlvbikge1xuICAgICAgaW5wdXRzID0gdGhpcy5tYXBJbnB1dHMoaW5wdXRzKTtcbiAgICAgIHRoaXMuY2hlY2tJbnB1dHMoaW5wdXRzKTtcbiAgICAgIHRoaXMuY2hlY2tJbnB1dFNoYXBlQW5kVHlwZShpbnB1dHMpO1xuICAgICAgb3V0cHV0cyA9IHRoaXMubWFwT3V0cHV0cyhvdXRwdXRzKTtcbiAgICAgIHRoaXMuY2hlY2tPdXRwdXRzKG91dHB1dHMpO1xuICAgIH1cblxuICAgIC8vIEtlZXAgdGVuc29ycyBpZiBLRUVQX0lOVEVSTUVESUFURV9URU5TT1JTIGlzIG9uLlxuICAgIHRyeSB7XG4gICAgICB0aGlzLmtlZXBJbnRlcm1lZGlhdGVUZW5zb3JzID0gZW52KCkuZ2V0Qm9vbCgnS0VFUF9JTlRFUk1FRElBVEVfVEVOU09SUycpO1xuICAgIH0gY2F0Y2ggKGUpIHtcbiAgICAgIHRoaXMua2VlcEludGVybWVkaWF0ZVRlbnNvcnMgPSBmYWxzZTtcbiAgICAgIGNvbnNvbGUud2FybihlLm1lc3NhZ2UpO1xuICAgIH1cblxuICAgIGNvbnN0IGNvbnRleHQgPSBuZXcgRXhlY3V0aW9uQ29udGV4dChcbiAgICAgICAgdGhpcy53ZWlnaHRNYXAsIHRlbnNvckFycmF5TWFwLCB0ZW5zb3JMaXN0TWFwLCB0aGlzLmZ1bmN0aW9uRXhlY3V0b3JNYXAsXG4gICAgICAgIHRoaXMucGFyc2VOb2RlTmFtZUNhY2hlKTtcblxuICAgIGlmICh0aGlzLmtlZXBJbnRlcm1lZGlhdGVUZW5zb3JzKSB7XG4gICAgICB0aGlzLmNsb25lZFRlbnNvcnNNYXAgPSB0aGlzLmNsb25lVGVuc29yTWFwKHRoaXMud2VpZ2h0TWFwKTtcbiAgICB9XG5cbiAgICAvLyBHcmFwaCB3aXRoIGNvbnRyb2wgZmxvdyBvcCByZXF1aXJlcyBydW50aW1lIGV2YWx1YXRpb24gb2YgdGhlIGV4ZWN1dGlvblxuICAgIC8vIG9yZGVyLCB3aGlsZSB3aXRob3V0IGNvbnRyb2wgZmxvdyB0aGUgZXhlY3V0aW9uIG9yZGVyIGlzIHByZS1kZXRlcm1pbmVkXG4gICAgLy8gaW4gdGhlIGNvbXBpbGUgbWV0aG9kLlxuICAgIGNvbnN0IHRlbnNvcnNNYXAgPSBhd2FpdCB0aGlzLmV4ZWN1dGVXaXRoQ29udHJvbEZsb3coXG4gICAgICAgIGlucHV0cywgY29udGV4dCwgb3V0cHV0cywgaXNGdW5jdGlvbkV4ZWN1dGlvbik7XG4gICAgY29uc3QgcmVzdWx0cyA9IG91dHB1dHMubWFwKG5hbWUgPT4gZ2V0VGVuc29yKG5hbWUsIHRlbnNvcnNNYXAsIGNvbnRleHQpKTtcblxuICAgIC8vIGRpc3Bvc2UgYWxsIHRoZSBpbnRlcm1lZGlhdGUgdGVuc29yc1xuICAgIGNvbnN0IG91dHB1dElkcyA9IHJlc3VsdHMubWFwKHQgPT4gdC5pZCk7XG4gICAgY29uc3QgaW5wdXRJZHMgPSBPYmplY3Qua2V5cyhpbnB1dHMpLm1hcChuYW1lID0+IGlucHV0c1tuYW1lXS5pZCk7XG4gICAgY29uc3Qga2VlcElkcyA9XG4gICAgICAgIG5ldyBTZXQ8bnVtYmVyPihbLi4ub3V0cHV0SWRzLCAuLi5pbnB1dElkcywgLi4udGhpcy53ZWlnaHRJZHNdKTtcblxuICAgIE9iamVjdC52YWx1ZXModGVuc29yc01hcCkuZm9yRWFjaCh0ZW5zb3JzTGlzdCA9PiB7XG4gICAgICB0ZW5zb3JzTGlzdC5mb3JFYWNoKHRlbnNvciA9PiB7XG4gICAgICAgIGlmICh0ZW5zb3IgJiYgIXRlbnNvci5pc0Rpc3Bvc2VkICYmICFrZWVwSWRzLmhhcyh0ZW5zb3IuaWQpKSB7XG4gICAgICAgICAgdGVuc29yLmRpc3Bvc2UoKTtcbiAgICAgICAgfVxuICAgICAgfSk7XG4gICAgfSk7XG5cbiAgICAvLyBkaXNwb3NlIHRoZSBjb250ZXh0IGZvciB0aGUgcm9vdCBleGVjdXRvclxuICAgIGlmICh0aGlzLnBhcmVudCA9PSBudWxsKSB7XG4gICAgICBjb250ZXh0LmRpc3Bvc2Uoa2VlcElkcyk7XG4gICAgfVxuXG4gICAgcmV0dXJuIHJlc3VsdHM7XG4gIH1cblxuICBhc3luYyBleGVjdXRlRnVuY3Rpb25Bc3luYyhcbiAgICAgIGlucHV0czogVGVuc29yW10sIHRlbnNvckFycmF5TWFwOiBUZW5zb3JBcnJheU1hcCxcbiAgICAgIHRlbnNvckxpc3RNYXA6IFRlbnNvckxpc3RNYXApOiBQcm9taXNlPFRlbnNvcltdPiB7XG4gICAgY29uc3QgbWFwcGVkSW5wdXRzID0gaW5wdXRzLnJlZHVjZSgobWFwLCB0ZW5zb3IsIGluZGV4KSA9PiB7XG4gICAgICBtYXBbdGhpcy5pbnB1dHNbaW5kZXhdLm5hbWVdID0gdGVuc29yO1xuICAgICAgcmV0dXJuIG1hcDtcbiAgICB9LCB7fSBhcyBOYW1lZFRlbnNvck1hcCk7XG5cbiAgICByZXR1cm4gdGhpcy5fZXhlY3V0ZUFzeW5jKFxuICAgICAgICBtYXBwZWRJbnB1dHMsIHRoaXMub3V0cHV0Tm9kZXMsIHRydWUsIHRlbnNvckFycmF5TWFwLCB0ZW5zb3JMaXN0TWFwKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBXaGVuIHRoZXJlIGFyZSBjb250cm9sIGZsb3cgbm9kZXMgaW4gdGhlIGdyYXBoLCB0aGUgZ3JhcGggZXhlY3V0aW9uIHVzZVxuICAgKiBFeGVjdXRpb25Db250ZXh0IHRvIGtlZXAgdHJhY2sgb2YgdGhlIGZyYW1lcyBhbmQgbG9vcCBpdGVyYXRvcnMuXG4gICAqIEBwYXJhbSBpbnB1dHMgcGxhY2Vob2xkZXIgdGVuc29ycyBmb3IgdGhlIGdyYXBoLlxuICAgKiBAcGFyYW0gY29udGV4dCB0aGUgZXhlY3V0aW9uIGNvbnRleHQgb2JqZWN0IGZvciBjdXJyZW50IGV4ZWN1dGlvbi5cbiAgICogQHBhcmFtIG91dHB1dE5hbWVzIE9wdGlvbmFsLiBvdXRwdXQgbm9kZSBuYW1lIGZyb20gdGhlIFRlbnNvcmZsb3cgbW9kZWwsXG4gICAqIGlmIG5vIG91dHB1dHMgYXJlIHNwZWNpZmllZCwgdGhlIGRlZmF1bHQgb3V0cHV0cyBvZiB0aGUgbW9kZWwgd291bGQgYmVcbiAgICogdXNlZC4gWW91IGNhbiBpbnNwZWN0IGludGVybWVkaWF0ZSBub2RlcyBvZiB0aGUgbW9kZWwgYnkgYWRkaW5nIHRoZW0gdG9cbiAgICogdGhlIG91dHB1dHMgYXJyYXkuXG4gICAqIEBwYXJhbSBpc0Z1bmN0aW9uRXhlY3V0aW9uIEZsYWcgZm9yIGV4ZWN1dGluZyBhIGZ1bmN0aW9uLlxuICAgKi9cbiAgcHJpdmF0ZSBhc3luYyBleGVjdXRlV2l0aENvbnRyb2xGbG93KFxuICAgICAgaW5wdXRzOiBOYW1lZFRlbnNvck1hcCwgY29udGV4dDogRXhlY3V0aW9uQ29udGV4dCwgb3V0cHV0TmFtZXM/OiBzdHJpbmdbXSxcbiAgICAgIGlzRnVuY3Rpb25FeGVjdXRpb24/OiBib29sZWFuKTogUHJvbWlzZTxOYW1lZFRlbnNvcnNNYXA+IHtcbiAgICBjb25zdCBuYW1lcyA9IE9iamVjdC5rZXlzKGlucHV0cyk7XG4gICAgY29uc3QgaW5wdXROb2RlcyA9XG4gICAgICAgIG5hbWVzLm1hcChuYW1lID0+IHRoaXMuZ3JhcGgubm9kZXNbcGFyc2VOb2RlTmFtZShuYW1lKVswXV0pO1xuICAgIGNvbnN0IG91dHB1dE5vZGVOYW1lcyA9IG91dHB1dE5hbWVzLm1hcChuYW1lID0+IHBhcnNlTm9kZU5hbWUobmFtZSlbMF0pO1xuICAgIGNvbnN0IG91dHB1dE5vZGVOYW1lU2V0ID0gbmV3IFNldChvdXRwdXROb2RlTmFtZXMpO1xuICAgIGxldCBvdXRwdXROb2RlcyA9IG91dHB1dE5vZGVOYW1lcy5tYXAobmFtZSA9PiB0aGlzLmdyYXBoLm5vZGVzW25hbWVdKTtcblxuICAgIC8vIElmIG5vIG91dHB1dHMgYXJlIHNwZWNpZmllZCwgdGhlbiB1c2UgdGhlIGRlZmF1bHQgb3V0cHV0cyBvZiB0aGUgbW9kZWwuXG4gICAgaWYgKG91dHB1dE5vZGVzLmxlbmd0aCA9PT0gMCkge1xuICAgICAgb3V0cHV0Tm9kZXMgPSB0aGlzLl9vdXRwdXRzO1xuICAgIH1cblxuICAgIGNvbnN0IHt1c2VkTm9kZXMsIG1pc3NpbmdJbnB1dHMsIGR5bmFtaWNOb2RlLCBzeW5jSW5wdXRzfSA9XG4gICAgICAgIGdldEV4ZWN1dGlvblN1YmdyYXBoKFxuICAgICAgICAgICAgaW5wdXRzLCBvdXRwdXROb2RlcywgdGhpcy53ZWlnaHRNYXAsIHRoaXMuX2luaXROb2Rlcyk7XG5cbiAgICAvLyBGaXJzdCBub2RlcyB0byBleGVjdXRlIGluY2x1ZGUgaW5wdXROb2Rlcywgd2VpZ2h0cywgYW5kIGluaXROb2Rlcy5cbiAgICBjb25zdCBzdGFjazogTm9kZVdpdGhDb250ZXh0c1tdID0gW1xuICAgICAgLi4uaW5wdXROb2RlcywgLi4udGhpcy5ncmFwaC53ZWlnaHRzLCAuLi4odGhpcy5faW5pdE5vZGVzIHx8IFtdKVxuICAgIF0ubWFwKG5vZGUgPT4ge1xuICAgICAgcmV0dXJuIHtub2RlLCBjb250ZXh0czogY29udGV4dC5jdXJyZW50Q29udGV4dH07XG4gICAgfSk7XG4gICAgY29uc3QgdGVuc29yc01hcDogTmFtZWRUZW5zb3JzTWFwID0gey4uLnRoaXMud2VpZ2h0TWFwfTtcbiAgICBPYmplY3Qua2V5cyhpbnB1dHMpLmZvckVhY2gobmFtZSA9PiB7XG4gICAgICBjb25zdCBbbm9kZU5hbWUsIGluZGV4XSA9IHBhcnNlTm9kZU5hbWUobmFtZSk7XG4gICAgICBjb25zdCB0ZW5zb3JzOiBUZW5zb3JbXSA9IFtdO1xuICAgICAgdGVuc29yc1tpbmRleF0gPSBpbnB1dHNbbmFtZV07XG4gICAgICB0ZW5zb3JzTWFwW25vZGVOYW1lXSA9IHRlbnNvcnM7XG4gICAgfSk7XG4gICAgY29uc3QgaW50ZXJtZWRpYXRlVGVuc29yQ29uc3VtZXJDb3VudDoge1trZXk6IG51bWJlcl06IG51bWJlcn0gPSB7fTtcbiAgICBjb25zdCB0ZW5zb3JzVG9LZWVwID0gdGhpcy5nZXRGcm96ZW5UZW5zb3JJZHModGVuc29yc01hcCk7XG4gICAgY29uc3QgYWRkZWQ6IHtba2V5OiBzdHJpbmddOiBib29sZWFufSA9IHt9O1xuICAgIHdoaWxlIChzdGFjay5sZW5ndGggPiAwKSB7XG4gICAgICBjb25zdCBwcm9taXNlcyA9IHRoaXMucHJvY2Vzc1N0YWNrKFxuICAgICAgICAgIGlucHV0Tm9kZXMsIHN0YWNrLCBjb250ZXh0LCB0ZW5zb3JzTWFwLCBhZGRlZCwgdGVuc29yc1RvS2VlcCxcbiAgICAgICAgICBvdXRwdXROb2RlTmFtZVNldCwgaW50ZXJtZWRpYXRlVGVuc29yQ29uc3VtZXJDb3VudCwgdXNlZE5vZGVzKTtcbiAgICAgIGF3YWl0IFByb21pc2UuYWxsKHByb21pc2VzKTtcbiAgICB9XG4gICAgaWYgKGR5bmFtaWNOb2RlID09IG51bGwgJiYgIWlzRnVuY3Rpb25FeGVjdXRpb24pIHtcbiAgICAgIGNvbnNvbGUud2FybihcbiAgICAgICAgICBgVGhpcyBtb2RlbCBleGVjdXRpb24gZGlkIG5vdCBjb250YWluIGFueSBub2RlcyB3aXRoIGNvbnRyb2wgZmxvdyBgICtcbiAgICAgICAgICBgb3IgZHluYW1pYyBvdXRwdXQgc2hhcGVzLiBZb3UgY2FuIHVzZSBtb2RlbC5leGVjdXRlKCkgaW5zdGVhZC5gKTtcbiAgICB9XG4gICAgY29uc3QgbWlzc2luZ091dHB1dHMgPVxuICAgICAgICBvdXRwdXROb2Rlc1xuICAgICAgICAgICAgLmZpbHRlcihcbiAgICAgICAgICAgICAgICBub2RlID0+ICFpc0NvbnRyb2xGbG93KG5vZGUpICYmXG4gICAgICAgICAgICAgICAgICAgICFnZXRUZW5zb3Iobm9kZS5uYW1lLCB0ZW5zb3JzTWFwLCBjb250ZXh0KSlcbiAgICAgICAgICAgIC5tYXAobm9kZSA9PiBub2RlLm5hbWUpO1xuICAgIGlmIChtaXNzaW5nT3V0cHV0cy5sZW5ndGggPiAwKSB7XG4gICAgICBsZXQgYWx0ZXJuYXRpdmVNc2cgPSAnJztcbiAgICAgIGlmIChkeW5hbWljTm9kZSAhPSBudWxsKSB7XG4gICAgICAgIGFsdGVybmF0aXZlTXNnID1cbiAgICAgICAgICAgIGBBbHRlcm5hdGl2ZWx5LCB0byBhdm9pZCB0aGUgZHluYW1pYyBvcHMsIHVzZSBtb2RlbC5leGVjdXRlKCkgYCArXG4gICAgICAgICAgICBgYW5kIHNwZWNpZnkgdGhlIGlucHV0cyBbJHtzeW5jSW5wdXRzfV1gO1xuICAgICAgfVxuICAgICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgICAgIGBDYW5ub3QgY29tcHV0ZSB0aGUgb3V0cHV0cyBbJHttaXNzaW5nT3V0cHV0c31dIGZyb20gdGhlIHByb3ZpZGVkIGAgK1xuICAgICAgICAgIGBpbnB1dHMgWyR7bmFtZXN9XS4gQ29uc2lkZXIgcHJvdmlkaW5nIHRoZSBmb2xsb3dpbmcgaW5wdXRzOiBgICtcbiAgICAgICAgICBgWyR7bWlzc2luZ0lucHV0c31dLiAke2FsdGVybmF0aXZlTXNnfWApO1xuICAgIH1cbiAgICByZXR1cm4gdGVuc29yc01hcDtcbiAgfVxuXG4gIHByaXZhdGUgcHJvY2Vzc1N0YWNrKFxuICAgICAgaW5wdXROb2RlczogTm9kZVtdLCBzdGFjazogTm9kZVdpdGhDb250ZXh0c1tdLCBjb250ZXh0OiBFeGVjdXRpb25Db250ZXh0LFxuICAgICAgdGVuc29yTWFwOiBOYW1lZFRlbnNvcnNNYXAsIGFkZGVkOiB7W2tleTogc3RyaW5nXTogYm9vbGVhbn0sXG4gICAgICB0ZW5zb3JzVG9LZWVwOiBTZXQ8bnVtYmVyPiwgb3V0cHV0Tm9kZU5hbWVTZXQ6IFNldDxzdHJpbmc+LFxuICAgICAgaW50ZXJtZWRpYXRlVGVuc29yQ29uc3VtZXJDb3VudDoge1trZXk6IG51bWJlcl06IG51bWJlcn0sXG4gICAgICB1c2VkTm9kZXM6IFNldDxzdHJpbmc+KSB7XG4gICAgY29uc3QgcHJvbWlzZXM6IEFycmF5PFByb21pc2U8VGVuc29yW10+PiA9IFtdO1xuICAgIHdoaWxlIChzdGFjay5sZW5ndGggPiAwKSB7XG4gICAgICBjb25zdCBpdGVtID0gc3RhY2sucG9wKCk7XG4gICAgICBjb250ZXh0LmN1cnJlbnRDb250ZXh0ID0gaXRlbS5jb250ZXh0cztcbiAgICAgIGxldCBub2RlTmFtZSA9ICcnO1xuICAgICAgLy8gVGhlIHRlbnNvciBvZiB0aGUgRW50ZXIgb3Agd2l0aCBpc0NvbnN0YW50IHNldCBzaG91bGQgYmUgc2V0XG4gICAgICAvLyBpbiB0aGUgcGFyZW50IHNjb3BlLCBzbyBpdCB3aWxsIGJlIGF2YWlsYWJsZSBhcyBjb25zdGFudCBmb3IgdGhlXG4gICAgICAvLyB3aG9sZSBsb29wLlxuICAgICAgaWYgKGl0ZW0ubm9kZS5vcCA9PT0gJ0VudGVyJyAmJlxuICAgICAgICAgIGdldFBhcmFtVmFsdWUoJ2lzQ29uc3RhbnQnLCBpdGVtLm5vZGUsIHRlbnNvck1hcCwgY29udGV4dCkpIHtcbiAgICAgICAgW25vZGVOYW1lXSA9IGdldE5vZGVOYW1lQW5kSW5kZXgoaXRlbS5ub2RlLm5hbWUsIGNvbnRleHQpO1xuICAgICAgfVxuXG4gICAgICAvLyBvbmx5IHByb2Nlc3Mgbm9kZXMgdGhhdCBhcmUgbm90IGluIHRoZSB0ZW5zb3JNYXAgeWV0LCB0aGlzIGluY2x1ZGVcbiAgICAgIC8vIGlucHV0Tm9kZXMgYW5kIGludGVybmFsIGluaXROb2Rlcy5cbiAgICAgIGlmICh0ZW5zb3JNYXBbaXRlbS5ub2RlLm5hbWVdID09IG51bGwpIHtcbiAgICAgICAgY29uc3QgdGVuc29ycyA9XG4gICAgICAgICAgICBleGVjdXRlT3AoaXRlbS5ub2RlLCB0ZW5zb3JNYXAsIGNvbnRleHQsIHRoaXMuX3Jlc291cmNlTWFuYWdlcik7XG4gICAgICAgIGlmICghbm9kZU5hbWUpIHtcbiAgICAgICAgICBbbm9kZU5hbWVdID0gZ2V0Tm9kZU5hbWVBbmRJbmRleChpdGVtLm5vZGUubmFtZSwgY29udGV4dCk7XG4gICAgICAgIH1cbiAgICAgICAgY29uc3QgY3VycmVudENvbnRleHQgPSBjb250ZXh0LmN1cnJlbnRDb250ZXh0O1xuICAgICAgICBpZiAodXRpbC5pc1Byb21pc2UodGVuc29ycykpIHtcbiAgICAgICAgICBwcm9taXNlcy5wdXNoKHRlbnNvcnMudGhlbih0ID0+IHtcbiAgICAgICAgICAgIHRlbnNvck1hcFtub2RlTmFtZV0gPSB0O1xuICAgICAgICAgICAgaWYgKHRoaXMua2VlcEludGVybWVkaWF0ZVRlbnNvcnMpIHtcbiAgICAgICAgICAgICAgdGhpcy5jbG9uZWRUZW5zb3JzTWFwW25vZGVOYW1lXSA9IHRoaXMuY2xvbmVUZW5zb3JMaXN0KHQpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgY29udGV4dC5jdXJyZW50Q29udGV4dCA9IGN1cnJlbnRDb250ZXh0O1xuICAgICAgICAgICAgdGhpcy5jaGVja1RlbnNvckZvckRpc3Bvc2FsKFxuICAgICAgICAgICAgICAgIG5vZGVOYW1lLCBpdGVtLm5vZGUsIHRlbnNvck1hcCwgY29udGV4dCwgdGVuc29yc1RvS2VlcCxcbiAgICAgICAgICAgICAgICBvdXRwdXROb2RlTmFtZVNldCwgaW50ZXJtZWRpYXRlVGVuc29yQ29uc3VtZXJDb3VudCk7XG4gICAgICAgICAgICB0aGlzLnByb2Nlc3NDaGlsZE5vZGVzKFxuICAgICAgICAgICAgICAgIGl0ZW0ubm9kZSwgc3RhY2ssIGNvbnRleHQsIHRlbnNvck1hcCwgYWRkZWQsIHVzZWROb2Rlcyk7XG4gICAgICAgICAgICByZXR1cm4gdDtcbiAgICAgICAgICB9KSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgdGVuc29yTWFwW25vZGVOYW1lXSA9IHRlbnNvcnM7XG4gICAgICAgICAgaWYgKHRoaXMua2VlcEludGVybWVkaWF0ZVRlbnNvcnMpIHtcbiAgICAgICAgICAgIHRoaXMuY2xvbmVkVGVuc29yc01hcFtub2RlTmFtZV0gPSB0aGlzLmNsb25lVGVuc29yTGlzdCh0ZW5zb3JzKTtcbiAgICAgICAgICB9XG4gICAgICAgICAgdGhpcy5jaGVja1RlbnNvckZvckRpc3Bvc2FsKFxuICAgICAgICAgICAgICBub2RlTmFtZSwgaXRlbS5ub2RlLCB0ZW5zb3JNYXAsIGNvbnRleHQsIHRlbnNvcnNUb0tlZXAsXG4gICAgICAgICAgICAgIG91dHB1dE5vZGVOYW1lU2V0LCBpbnRlcm1lZGlhdGVUZW5zb3JDb25zdW1lckNvdW50KTtcbiAgICAgICAgICB0aGlzLnByb2Nlc3NDaGlsZE5vZGVzKFxuICAgICAgICAgICAgICBpdGVtLm5vZGUsIHN0YWNrLCBjb250ZXh0LCB0ZW5zb3JNYXAsIGFkZGVkLCB1c2VkTm9kZXMpO1xuICAgICAgICB9XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aGlzLnByb2Nlc3NDaGlsZE5vZGVzKFxuICAgICAgICAgICAgaXRlbS5ub2RlLCBzdGFjaywgY29udGV4dCwgdGVuc29yTWFwLCBhZGRlZCwgdXNlZE5vZGVzKTtcbiAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIHByb21pc2VzO1xuICB9XG5cbiAgcHJpdmF0ZSBwcm9jZXNzQ2hpbGROb2RlcyhcbiAgICAgIG5vZGU6IE5vZGUsIHN0YWNrOiBOb2RlV2l0aENvbnRleHRzW10sIGNvbnRleHQ6IEV4ZWN1dGlvbkNvbnRleHQsXG4gICAgICB0ZW5zb3JNYXA6IE5hbWVkVGVuc29yc01hcCwgYWRkZWQ6IHtba2V5OiBzdHJpbmddOiBib29sZWFufSxcbiAgICAgIHVzZWROb2RlczogU2V0PHN0cmluZz4pIHtcbiAgICBub2RlLmNoaWxkcmVuLmZvckVhY2goKGNoaWxkTm9kZSkgPT4ge1xuICAgICAgY29uc3QgW25vZGVOYW1lLCBdID0gZ2V0Tm9kZU5hbWVBbmRJbmRleChjaGlsZE5vZGUubmFtZSwgY29udGV4dCk7XG4gICAgICBpZiAoYWRkZWRbbm9kZU5hbWVdIHx8ICF1c2VkTm9kZXMuaGFzKGNoaWxkTm9kZS5uYW1lKSkge1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG4gICAgICAvLyBNZXJnZSBvcCBjYW4gYmUgcHVzaGVkIGlmIGFueSBvZiBpdHMgaW5wdXRzIGhhcyB2YWx1ZS5cbiAgICAgIGlmIChjaGlsZE5vZGUub3AgPT09ICdNZXJnZScpIHtcbiAgICAgICAgaWYgKGNoaWxkTm9kZS5pbnB1dE5hbWVzLnNvbWUobmFtZSA9PiB7XG4gICAgICAgICAgICAgIHJldHVybiAhIWdldFRlbnNvcihuYW1lLCB0ZW5zb3JNYXAsIGNvbnRleHQpO1xuICAgICAgICAgICAgfSkpIHtcbiAgICAgICAgICBhZGRlZFtub2RlTmFtZV0gPSB0cnVlO1xuICAgICAgICAgIHN0YWNrLnB1c2goe2NvbnRleHRzOiBjb250ZXh0LmN1cnJlbnRDb250ZXh0LCBub2RlOiBjaGlsZE5vZGV9KTtcbiAgICAgICAgfVxuICAgICAgfSBlbHNlICAvLyBPdGhlcndpc2UgYWxsIGlucHV0cyBtdXN0IHRvIGhhdmUgdmFsdWUuXG4gICAgICAgICAgaWYgKGNoaWxkTm9kZS5pbnB1dE5hbWVzLmV2ZXJ5KG5hbWUgPT4ge1xuICAgICAgICAgICAgICAgIHJldHVybiAhIWdldFRlbnNvcihuYW1lLCB0ZW5zb3JNYXAsIGNvbnRleHQpO1xuICAgICAgICAgICAgICB9KSkge1xuICAgICAgICBhZGRlZFtub2RlTmFtZV0gPSB0cnVlO1xuICAgICAgICBzdGFjay5wdXNoKHtjb250ZXh0czogY29udGV4dC5jdXJyZW50Q29udGV4dCwgbm9kZTogY2hpbGROb2RlfSk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cblxuICAvKipcbiAgICogUmVsZWFzZXMgdGhlIG1lbW9yeSB1c2VkIGJ5IHRoZSB3ZWlnaHQgdGVuc29ycy5cbiAgICovXG4gIGRpc3Bvc2UoKSB7XG4gICAgT2JqZWN0LmtleXModGhpcy53ZWlnaHRNYXApXG4gICAgICAgIC5mb3JFYWNoKFxuICAgICAgICAgICAga2V5ID0+IHRoaXMud2VpZ2h0TWFwW2tleV0uZm9yRWFjaCh0ZW5zb3IgPT4gdGVuc29yLmRpc3Bvc2UoKSkpO1xuICB9XG5cbiAgcHJpdmF0ZSBjaGVja0lucHV0U2hhcGVBbmRUeXBlKGlucHV0czogTmFtZWRUZW5zb3JNYXApIHtcbiAgICBPYmplY3Qua2V5cyhpbnB1dHMpLmZvckVhY2gobmFtZSA9PiB7XG4gICAgICBjb25zdCBpbnB1dCA9IGlucHV0c1tuYW1lXTtcbiAgICAgIGNvbnN0IFtub2RlTmFtZSwgXSA9IHBhcnNlTm9kZU5hbWUobmFtZSk7XG4gICAgICBjb25zdCBub2RlID0gdGhpcy5ncmFwaC5ub2Rlc1tub2RlTmFtZV07XG4gICAgICBpZiAobm9kZS5hdHRyUGFyYW1zWydzaGFwZSddICYmIG5vZGUuYXR0clBhcmFtc1snc2hhcGUnXS52YWx1ZSkge1xuICAgICAgICBjb25zdCBzaGFwZSA9IG5vZGUuYXR0clBhcmFtc1snc2hhcGUnXS52YWx1ZSBhcyBudW1iZXJbXTtcbiAgICAgICAgY29uc3QgbWF0Y2ggPSBzaGFwZS5sZW5ndGggPT09IGlucHV0LnNoYXBlLmxlbmd0aCAmJlxuICAgICAgICAgICAgaW5wdXQuc2hhcGUuZXZlcnkoXG4gICAgICAgICAgICAgICAgKGRpbSwgaW5kZXgpID0+IHNoYXBlW2luZGV4XSA9PT0gLTEgfHwgc2hhcGVbaW5kZXhdID09PSBkaW0pO1xuICAgICAgICB1dGlsLmFzc2VydChcbiAgICAgICAgICAgIG1hdGNoLFxuICAgICAgICAgICAgKCkgPT4gYFRoZSBzaGFwZSBvZiBkaWN0Wycke25vZGUubmFtZX0nXSBwcm92aWRlZCBpbiBgICtcbiAgICAgICAgICAgICAgICBgbW9kZWwuZXhlY3V0ZShkaWN0KSBtdXN0IGJlIFske3NoYXBlfV0sIGJ1dCB3YXMgYCArXG4gICAgICAgICAgICAgICAgYFske2lucHV0LnNoYXBlfV1gKTtcbiAgICAgIH1cbiAgICAgIGlmIChub2RlLmF0dHJQYXJhbXNbJ2R0eXBlJ10gJiYgbm9kZS5hdHRyUGFyYW1zWydkdHlwZSddLnZhbHVlKSB7XG4gICAgICAgIHV0aWwuYXNzZXJ0KFxuICAgICAgICAgICAgaW5wdXQuZHR5cGUgPT09IG5vZGUuYXR0clBhcmFtc1snZHR5cGUnXS52YWx1ZSBhcyBzdHJpbmcsXG4gICAgICAgICAgICAoKSA9PiBgVGhlIGR0eXBlIG9mIGRpY3RbJyR7bm9kZS5uYW1lfSddIHByb3ZpZGVkIGluIGAgK1xuICAgICAgICAgICAgICAgIGBtb2RlbC5leGVjdXRlKGRpY3QpIG11c3QgYmUgYCArXG4gICAgICAgICAgICAgICAgYCR7bm9kZS5hdHRyUGFyYW1zWydkdHlwZSddLnZhbHVlfSwgYnV0IHdhcyAke2lucHV0LmR0eXBlfWApO1xuICAgICAgfVxuICAgIH0pO1xuICB9XG5cbiAgcHJpdmF0ZSBtYXBJbnB1dHMoaW5wdXRzOiBOYW1lZFRlbnNvck1hcCkge1xuICAgIGNvbnN0IHJlc3VsdDogTmFtZWRUZW5zb3JNYXAgPSB7fTtcbiAgICBmb3IgKGNvbnN0IGlucHV0TmFtZSBpbiBpbnB1dHMpIHtcbiAgICAgIGNvbnN0IHRlbnNvciA9IHRoaXMuX3NpZ25hdHVyZSA/LmlucHV0cyA/LltpbnB1dE5hbWVdO1xuICAgICAgaWYgKHRlbnNvciAhPSBudWxsKSB7XG4gICAgICAgIHJlc3VsdFt0ZW5zb3IubmFtZV0gPSBpbnB1dHNbaW5wdXROYW1lXTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHJlc3VsdFtpbnB1dE5hbWVdID0gaW5wdXRzW2lucHV0TmFtZV07XG4gICAgICB9XG4gICAgfVxuICAgIHJldHVybiByZXN1bHQ7XG4gIH1cblxuICBwcml2YXRlIGNoZWNrSW5wdXRzKGlucHV0czogTmFtZWRUZW5zb3JNYXApIHtcbiAgICBjb25zdCBub3RJbkdyYXBoID0gT2JqZWN0LmtleXMoaW5wdXRzKS5maWx0ZXIobmFtZSA9PiB7XG4gICAgICBjb25zdCBbbm9kZU5hbWVdID0gcGFyc2VOb2RlTmFtZShuYW1lKTtcbiAgICAgIHJldHVybiB0aGlzLmdyYXBoLm5vZGVzW25vZGVOYW1lXSA9PSBudWxsO1xuICAgIH0pO1xuICAgIGlmIChub3RJbkdyYXBoLmxlbmd0aCA+IDApIHtcbiAgICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgICAgICBgVGhlIGRpY3QgcHJvdmlkZWQgaW4gbW9kZWwuZXhlY3V0ZShkaWN0KSBoYXMgYCArXG4gICAgICAgICAgYGtleXM6IFske25vdEluR3JhcGh9XSB0aGF0IGFyZSBub3QgcGFydCBvZiBncmFwaGApO1xuICAgIH1cbiAgfVxuXG4gIHByaXZhdGUgbWFwT3V0cHV0cyhvdXRwdXRzOiBzdHJpbmdbXSkge1xuICAgIHJldHVybiBvdXRwdXRzLm1hcChuYW1lID0+IHtcbiAgICAgIGNvbnN0IHRlbnNvciA9IHRoaXMuX3NpZ25hdHVyZSA/Lm91dHB1dHMgPy5bbmFtZV07XG4gICAgICBpZiAodGVuc29yICE9IG51bGwpIHtcbiAgICAgICAgcmV0dXJuIHRlbnNvci5uYW1lO1xuICAgICAgfVxuICAgICAgcmV0dXJuIG5hbWU7XG4gICAgfSwge30pO1xuICB9XG5cbiAgcHJpdmF0ZSBjaGVja091dHB1dHMob3V0cHV0czogc3RyaW5nW10pOiB2b2lkIHtcbiAgICBvdXRwdXRzLmZvckVhY2gobmFtZSA9PiB7XG4gICAgICBjb25zdCBbbm9ybWFsaXplZE5hbWVdID0gcGFyc2VOb2RlTmFtZShuYW1lKTtcbiAgICAgIGlmICghdGhpcy5ncmFwaC5ub2Rlc1tub3JtYWxpemVkTmFtZV0pIHtcbiAgICAgICAgdGhyb3cgbmV3IEVycm9yKGBUaGUgb3V0cHV0ICcke25hbWV9JyBpcyBub3QgZm91bmQgaW4gdGhlIGdyYXBoYCk7XG4gICAgICB9XG4gICAgfSk7XG4gIH1cbn1cbiJdfQ==