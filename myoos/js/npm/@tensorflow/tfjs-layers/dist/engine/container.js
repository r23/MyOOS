/**
 * @license
 * Copyright 2018 Google LLC
 *
 * Use of this source code is governed by an MIT-style
 * license that can be found in the LICENSE file or at
 * https://opensource.org/licenses/MIT.
 * =============================================================================
 */
/* Original source: keras/engine/topology.py */
import { tidy } from '@tensorflow/tfjs-core';
import { getUid } from '../backend/state';
import { NotImplementedError, RuntimeError, ValueError } from '../errors';
import { deserialize as deserializeLayer } from '../layers/serialization';
import * as generic_utils from '../utils/generic_utils';
import { convertTsToPythonic } from '../utils/serialization_utils';
import * as types_utils from '../utils/types_utils';
import { batchSetValue } from '../variables';
import { version as layersVersion } from '../version';
import { execute, FeedDict } from './executor';
import { InputLayer } from './input_layer';
import { Layer, Node } from './topology';
/**
 * A Container is a directed acyclic graph of layers.
 *
 * It is the topological form of a "model". A LayersModel
 * is simply a Container with added training routines.
 *
 */
export class Container extends Layer {
    constructor(args) {
        // No args passed to super's constructor.
        super({});
        this.containerNodes = new Set();
        this.name = args.name;
        if (this.name == null) {
            const prefix = this.getClassName().toLowerCase();
            this.name = getUid(prefix);
        }
        this.supportsMasking = false;
        this.trainable_ = true;
        // TODO(michaelterry): Initialize perInputLosses/Updates here.
        // Container-specific properties.
        if (Array.isArray(args.inputs)) {
            this.inputs = args.inputs.slice();
        }
        else {
            this.inputs = [args.inputs];
        }
        if (Array.isArray(args.outputs)) {
            this.outputs = args.outputs.slice();
        }
        else {
            this.outputs = [args.outputs];
        }
        // Check for redundancy in inputs.
        if (generic_utils.unique(this.inputs).length !== this.inputs.length) {
            throw new ValueError('The list of inputs passed to the model is ' +
                'redundant. All inputs should only appear once. Found: ' +
                `${this.inputs.map(x => x.name)}`);
        }
        // Check for redundancy in outputs.
        if (generic_utils.unique(this.outputs).length !== this.outputs.length) {
            console.warn('The list of outputs passed to the model is redundant. ' +
                'All outputs should only appear once. Found: ' +
                `${this.outputs.map(x => x.name)}`);
        }
        /*
          List of initial layers (1 to 1 mapping with this.inputs, hence the same
          layer might appear twice)
        */
        this.inputLayers = [];
        this.inputLayersNodeIndices = [];
        this.inputLayersTensorIndices = [];
        /*
          List of layers (1 to 1 mapping with this.outputs, hence the same layer
          might appear twice)
        */
        this.outputLayers = [];
        this.outputLayersNodeIndices = [];
        this.outputLayersTensorIndices = [];
        /*
          All layers in order of horizontal graph traversal. Entries are unique.
          Includes input and output layers.
        */
        this.layers = [];
        /*
          References to container layers that were constructed internally. We need
          these to properly dispose of tensors from nested containers.
        */
        this.internalContainerRefs = [];
        // TODO(michaelterry): Determine if caching still needed with eager
        // backend.
        /*
          This is for performance optimization when calling the Container on new
          inputs. Every time the Container is called on a set on input tensors,
          we compute the output tensors, output masks and output shapes in one pass,
          then cache them here. When one of these outputs is queried later,
          we retrieve it from there instead of recomputing it.
        */
        // this.outputTensorCache = {};
        // this.outputShapeCache = {};
        // Build this.outputLayers:
        for (const x of this.outputs) {
            const layer = x.sourceLayer;
            const nodeIndex = x.nodeIndex;
            const tensorIndex = x.tensorIndex;
            this.outputLayers.push(layer);
            this.outputLayersNodeIndices.push(nodeIndex);
            this.outputLayersTensorIndices.push(tensorIndex);
        }
        // TODO(michaelterry): Add output mask cache code.
        // Build this.inputLayers:
        for (const x of this.inputs) {
            const layer = x.sourceLayer;
            const nodeIndex = x.nodeIndex;
            const tensorIndex = x.tensorIndex;
            /*
              It's supposed to be an input layer, so only one node
              and one tensor output.
            */
            generic_utils.assert(nodeIndex === 0, 'input layer has >1 nodes');
            generic_utils.assert(tensorIndex === 0, 'input layer has >1 tensors');
            this.inputLayers.push(layer);
            this.inputLayersNodeIndices.push(nodeIndex);
            this.inputLayersTensorIndices.push(tensorIndex);
        }
        // Build this.inputNames and this.outputNames.
        this.inputNames = [];
        this.outputNames = [];
        this.feedInputShapes = [];
        this.feedInputNames = [];
        this.feedOutputNames = [];
        for (let i = 0; i < this.inputLayers.length; i++) {
            const layer = this.inputLayers[i];
            // Check that layer is an InputLayer.
            if (!(layer instanceof InputLayer)) {
                throw new TypeError('Input layers to a LayersModel must be InputLayer objects. ' +
                    `Received inputs: ${args.inputs}. ` +
                    `Input ${i} (0-based) originates ` +
                    `from layer type ${layer.getClassName()}.`);
            }
            this.inputNames.push(layer.name);
            this.feedInputShapes.push(layer.batchInputShape);
            this.feedInputNames.push(layer.name);
        }
        for (const layer of this.outputLayers) {
            this.outputNames.push(layer.name);
        }
        this.internalInputShapes = this.inputs.map(x => x.shape);
        this.internalOutputShapes = this.outputs.map(x => x.shape);
        /*
          Container_nodes: set of nodes included in the graph (not all nodes
          included in the layers are relevant to the current graph).
        */
        // ids of all nodes relevant to the Container:
        const nodesDepths = {};
        // To recover nodes from their ID.
        const nodeIDToNode = {};
        const layersDepths = {};
        // To layers from their ID.
        const layerIDToLayer = {};
        const layerIndices = {};
        const nodesInDecreasingDepth = [];
        /**
         * Builds a map of the graph of layers.
         *
         * This recursively updates the map `layerIndices`,
         * the list `nodesInDecreasingDepth` and the set `containerNodes`.
         *
         * @param tensor Some tensor in a graph.
         * @param finishedNodes Set of nodes whose subgraphs have been traversed
         *         completely. Useful to prevent duplicated work.
         * @param nodesInProgress Set of nodes that are currently active on the
         *         recursion stack. Useful to detect cycles.
         * @param layer Layer from which `tensor` comes from. If not provided,
         *   will be obtained from tensor.sourceLayer.
         * @param nodeIndex Node index from which `tensor` comes from.
         * @param tensorIndex TensorIndex from which `tensor` comes from.
         *
         * @exception RuntimeError if a cycle is detected.
         */
        const buildMapOfGraph = (tensor, finishedNodes, nodesInProgress, layer, nodeIndex, tensorIndex) => {
            if (layer == null || nodeIndex == null || tensorIndex == null) {
                layer = tensor.sourceLayer;
                nodeIndex = tensor.nodeIndex;
                tensorIndex = tensor.tensorIndex;
            }
            const node = layer.inboundNodes[nodeIndex];
            // Prevent cycles.
            if (nodesInProgress.indexOf(node) !== -1) {
                throw new RuntimeError(`The tensor ${tensor.name} at layer "${layer.name}" ` +
                    'is part of a cycle.');
            }
            // Don't repeat work for shared subgraphs
            if (finishedNodes.indexOf(node) !== -1) {
                return;
            }
            // Update containerNodes.
            this.containerNodes.add(Container.nodeKey(layer, nodeIndex));
            // Store the traversal order for layer sorting.
            if (!(layer.id in layerIndices)) {
                layerIndices[layer.id] = Object.keys(layerIndices).length;
            }
            if (nodesInProgress.indexOf(node) === -1) {
                nodesInProgress.push(node);
            }
            // Propagate to all previous tensors connected to this node.
            const numInboundLayers = node.inboundLayers.length;
            for (let i = 0; i < numInboundLayers; i++) {
                const x = node.inputTensors[i];
                const layer = node.inboundLayers[i];
                const nodeIndex = node.nodeIndices[i];
                const tensorIndex = node.tensorIndices[i];
                buildMapOfGraph(x, finishedNodes, nodesInProgress, layer, nodeIndex, tensorIndex);
            }
            finishedNodes.push(node);
            while (nodesInProgress.indexOf(node) >= 0) {
                nodesInProgress.splice(nodesInProgress.indexOf(node), 1);
            }
            nodesInDecreasingDepth.push(node);
        };
        const finishedNodes = [];
        const nodesInProgress = [];
        for (const x of this.outputs) {
            buildMapOfGraph(x, finishedNodes, nodesInProgress);
        }
        const reversedNodesInDecreasingDepth = nodesInDecreasingDepth.slice().reverse();
        for (const node of reversedNodesInDecreasingDepth) {
            nodeIDToNode[node.id] = node;
            // If the depth is not set, the node has no outbound nodes (depth 0).
            if (!(node.id in nodesDepths)) {
                nodesDepths[node.id] = 0;
            }
            let depth = nodesDepths[node.id];
            // Update the depth of the corresponding layer
            const previousDepth = (layersDepths[node.outboundLayer.id] == null ?
                0 :
                layersDepths[node.outboundLayer.id]);
            /*
              If we've seen this layer before at a higher depth, we should use that
              depth instead of the node depth.  This is necessary for shared layers
              that have inputs at different depth levels in the graph.
            */
            depth = Math.max(depth, previousDepth);
            layersDepths[node.outboundLayer.id] = depth;
            layerIDToLayer[node.outboundLayer.id] = node.outboundLayer;
            nodesDepths[node.id] = depth;
            // Update the depth of inbound nodes.
            for (let i = 0; i < node.inboundLayers.length; i++) {
                const inboundLayer = node.inboundLayers[i];
                const nodeIndex = node.nodeIndices[i];
                const inboundNode = inboundLayer.inboundNodes[nodeIndex];
                const previousDepth = (nodesDepths[inboundNode.id] == null ? 0 :
                    nodesDepths[inboundNode.id]);
                nodesDepths[inboundNode.id] = Math.max(depth + 1, previousDepth);
                nodeIDToNode[inboundNode.id] = inboundNode;
            }
        }
        // Build a dict {depth: list of nodes with this depth}
        const nodesByDepth = {};
        for (const nodeID in nodesDepths) {
            const depth = nodesDepths[nodeID];
            if (!(depth in nodesByDepth)) {
                nodesByDepth[depth] = [];
            }
            nodesByDepth[depth].push(nodeIDToNode[nodeID]);
        }
        // Build a dict {depth: list of layers with this depth}
        const layersByDepth = {};
        for (const layerID in layersDepths) {
            const depth = layersDepths[layerID];
            if (!(depth in layersByDepth)) {
                layersByDepth[depth] = [];
            }
            layersByDepth[depth].push(layerIDToLayer[layerID]);
        }
        // Get sorted list of layer depths.
        let depthKeys = Object.keys(layersByDepth)
            .map(x => parseInt(x, 10))
            .sort(generic_utils.reverseNumberCompare);
        // Set this.layers and this.layersByDepth.
        this.layers = [];
        for (const depth of depthKeys) {
            const layersForDepth = layersByDepth[depth];
            // Container.layers needs to have a deterministic order:
            // here we order them by traversal order.
            layersForDepth.sort((a, b) => {
                const aIndex = layerIndices[a.id];
                const bIndex = layerIndices[b.id];
                if (aIndex < bIndex) {
                    return -1;
                }
                if (aIndex > bIndex) {
                    return 1;
                }
                return 0;
            });
            for (const layer of layersForDepth) {
                if (layer instanceof Container) {
                    this.internalContainerRefs.push(layer);
                }
                this.layers.push(layer);
            }
        }
        this.layersByDepth = layersByDepth;
        // Get sorted list of node depths;
        depthKeys = Object.keys(nodesByDepth)
            .map(x => parseInt(x, 10))
            .sort(generic_utils.reverseNumberCompare);
        // Check that all tensors required are computable.
        // computable_tensors: all tensors in the graph
        // that can be computed from the inputs provided.
        const computableTensors = this.inputs.slice();
        // To provide a better error msg.
        const layersWithCompleteInput = [];
        for (const depth of depthKeys) {
            for (const node of nodesByDepth[depth]) {
                const layer = node.outboundLayer;
                if (layer != null) {
                    for (const x of node.inputTensors) {
                        if (computableTensors.indexOf(x) === -1) {
                            throw new RuntimeError(`Graph disconnected: cannot obtain value for tensor ${x}` +
                                ` at layer "${layer.name}". ` +
                                'The following previous layers were accessed without ' +
                                `issue: ${layersWithCompleteInput}`);
                        }
                    }
                    for (const x of node.outputTensors) {
                        computableTensors.push(x);
                    }
                    layersWithCompleteInput.push(layer.name);
                }
            }
        }
        // Set this.containerNodes and this.nodesByDepth.
        this.nodesByDepth = nodesByDepth;
        // Ensure name unicity, which will be crucial for serialization
        // (since serialized nodes refer to layers by their name).
        const allNames = this.layers.map(x => x.name);
        for (const name of allNames) {
            const numOccurrences = allNames.filter(x => x === name).length;
            if (numOccurrences !== 1) {
                throw new RuntimeError(`The name "${name}" is used ${numOccurrences} times ` +
                    'in the model. All layer names should be unique. Layer names: ' +
                    JSON.stringify(allNames));
            }
        }
        // Layer parameters.
        // The new container starts with a single inbound node
        // for its inputs, and no outbound nodes.
        // Will be appended to by future calls to apply().
        this.outboundNodes = [];
        // Will be appended to below, and by future calls to apply().
        this.inboundNodes = [];
        // Create the node linking internal inputs to internal outputs.
        // (This call has side effects.)
        // tslint:disable-next-line:no-unused-expression
        new Node({
            outboundLayer: this,
            inboundLayers: [],
            nodeIndices: [],
            tensorIndices: [],
            inputTensors: this.inputs,
            outputTensors: this.outputs,
            inputMasks: this.inputs.map(x => null),
            outputMasks: this.outputs.map(x => null),
            inputShapes: this.inputs.map(x => x.shape),
            outputShapes: this.outputs.map(x => x.shape)
        });
        this.built = true;
        this._refCount = 1; // The ref count of a container always start at 1.
    }
    assertNotDisposed() {
        if (this._refCount === 0) {
            throw new Error(`Container '${this.name}' is already disposed.`);
        }
    }
    /**
     * Attempt to dispose a LayersModel's weights.
     *
     * This method decrease the reference count of the LayersModel object by 1.
     *
     * A LayersModel is reference-counted. Its reference count is incremented by 1
     * when it is first constructed and when it is used as a Layer of another
     * LayersModel.
     *
     * If the reference count of a LayersModel becomes 0, the `dispose` method of
     * all its constituent `Layer`s will be called.
     *
     * Note: If the reference count is greater than 0 after the decrement, the
     * `dispose` method of its constituent `Layer`s will *not* be called.
     *
     * After a LayersModel is disposed, it cannot be used in calls such as
     * 'predict`, `evaluate` or `fit` anymore.
     *
     * @returns A DisposeResult Object with the following fields:
     *   - refCountAfterDispose: The reference count of the LayersModel after this
     *     `dispose()` call.
     *   - numDisposedVariables: Number of `tf.Variable`s (i.e., weights) disposed
     *     during this `dispose()` call.
     * @throws {Error} If the layer is not built yet, or if the LayersModel has
     *   already been disposed.
     */
    dispose() {
        this.assertNotDisposed();
        const result = { refCountAfterDispose: null, numDisposedVariables: 0 };
        if (--this._refCount === 0) {
            for (const layer of this.layers) {
                result.numDisposedVariables += layer.dispose().numDisposedVariables;
            }
            // Call dispose on each internally created container layer again to ensure
            // their refCounts hit zero and their tensors are subsequently deleted.
            for (const container of this.internalContainerRefs) {
                result.numDisposedVariables += container.dispose().numDisposedVariables;
            }
        }
        result.refCountAfterDispose = this._refCount;
        return result;
    }
    get trainable() {
        return this.trainable_;
    }
    set trainable(trainable) {
        this.layers.forEach(layer => {
            // tslint:disable-next-line:no-any
            layer._trainableWeights
                .forEach(w => w.trainable = trainable);
        });
        this.trainable_ = trainable;
    }
    get trainableWeights() {
        // Porting Note: This check below is to prevent errors where the
        //   _trainableWeights inherited from the parent class (Layer) gets
        //   inadvertently used.
        if (this._trainableWeights.length > 0) {
            throw new ValueError('Container instance unexpectedly contains _trainableWeights.' +
                'The trainable weights of a Container are a union of the ' +
                'trainable weights of its consituent Layers. Its own ' +
                '_trainableWeights must remain an empty Array.');
        }
        if (!this.trainable) {
            return [];
        }
        let weights = [];
        for (const layer of this.layers) {
            weights = weights.concat(layer.trainableWeights);
        }
        return weights;
    }
    get nonTrainableWeights() {
        const weights = [];
        for (const layer of this.layers) {
            weights.push(...layer.nonTrainableWeights);
        }
        if (!this.trainable) {
            const trainableWeights = [];
            for (const layer of this.layers) {
                trainableWeights.push(...layer.trainableWeights);
            }
            return trainableWeights.concat(weights);
        }
        return weights;
    }
    get weights() {
        return this.trainableWeights.concat(this.nonTrainableWeights);
    }
    /**
     * Loads all layer weights from a JSON object.
     *
     * Porting Note: HDF5 weight files cannot be directly loaded in JavaScript /
     *   TypeScript. The utility script at `scripts/pykeras.py` offers means
     *   to convert them into JSON strings compatible with this method.
     * Porting Note: TensorFlow.js Layers supports only loading by name currently.
     *
     * @param weights A JSON mapping weight names to weight values as nested
     *   arrays of numbers, or a `NamedTensorMap`, i.e., a JSON mapping weight
     *   names to `tf.Tensor` objects.
     * @param strict Require that the provided weights exactly match those
     *   required by the container.  Default: `true`.  Passing `false` means that
     *   extra weights and missing weights will be silently ignored.
     */
    loadWeights(weights, strict = true) {
        const nameToWeight = {};
        let totalWeightsCount = 0;
        for (const layer of this.layers) {
            for (const weight of layer.weights) {
                if (nameToWeight[weight.originalName] != null) {
                    throw new ValueError(`Duplicate weight name: ${weight.originalName}`);
                }
                nameToWeight[weight.originalName] = weight;
                totalWeightsCount++;
            }
        }
        const weightValueTuples = [];
        for (const name in weights) {
            // TF 2.2.0 added cell name to the weight name in the format of
            // layer_name/cell_name/weight_name, we need to remove
            // the inner cell name.
            let validatedName = name;
            if (nameToWeight[name] == null) {
                const tokens = name.split('/');
                const shortenNameArray = tokens.slice(0, -2).concat([tokens[tokens.length - 1]]);
                validatedName = shortenNameArray.join('/');
            }
            if (nameToWeight[validatedName] != null) {
                weightValueTuples.push([nameToWeight[validatedName], weights[name]]);
            }
            else if (strict) {
                throw new ValueError(`Provided weight data has no target variable: ${name}`);
            }
            delete nameToWeight[validatedName];
        }
        if (strict) {
            // Check that all weights are set.
            const unsetNames = [];
            for (const name in nameToWeight) {
                unsetNames.push(name);
            }
            if (unsetNames.length > 0) {
                throw new ValueError(`${unsetNames.length} of ${totalWeightsCount} weights are not set: ` +
                    `${unsetNames}`);
            }
        }
        batchSetValue(weightValueTuples);
    }
    /**
     * Util shared between different serialization methods.
     * @returns LayersModel config with Keras version information added.
     */
    updatedConfig() {
        const theConfig = this.getConfig();
        const modelConfig = {};
        modelConfig['className'] = this.getClassName();
        modelConfig['config'] = theConfig;
        modelConfig['kerasVersion'] = `tfjs-layers ${layersVersion}`;
        // TODO(nielsene): Replace something like K.backend() once
        // possible.
        modelConfig['backend'] = 'TensorFlow.js';
        return modelConfig;
    }
    /**
     * Returns a JSON string containing the network configuration.
     *
     * To load a network from a JSON save file, use
     * models.modelFromJSON(jsonString);
     * @param extraJsonArgs Unused in tfjs-layers, maintained for PyKeras
     * @param returnString Whether the return value should be stringified
     *    (default: `true`).
     * @returns a JSON string if `returnString` (default), or a JSON object if
     *   `!returnString`.
     */
    // tslint:disable-next-line:no-any
    toJSON(unused, returnString = true) {
        const modelConfig = convertTsToPythonic(this.updatedConfig());
        return returnString ? JSON.stringify(modelConfig) : modelConfig;
    }
    /**
     * Call the model on new inputs.
     *
     * In this case `call` just reapplies all ops in the graph to the new inputs
     * (e.g. build a new computational graph from the provided inputs).
     *
     * @param inputs A tensor or list of tensors.
     * @param mask A mask or list of masks. A mask can be either a tensor or null
     *   (no mask).
     *
     * @return A tensor if there is a single output, or a list of tensors if there
     *   are more than one outputs.
     */
    call(inputs, kwargs) {
        return tidy(() => {
            inputs = generic_utils.toList(inputs);
            const feedDict = new FeedDict();
            for (let i = 0; i < this.inputs.length; ++i) {
                feedDict.add(this.inputs[i], inputs[i]);
            }
            return execute(this.outputs, feedDict, kwargs);
        });
    }
    /**
     * Computes an output mask tensor.
     *
     * @param inputs Tensor or list of tensors.
     * @param mask Tensor or list of tensors.
     *
     * @return null or a tensor (or list of tensors, one per output tensor of the
     * layer).
     */
    computeMask(inputs, mask) {
        return tidy(() => {
            inputs = generic_utils.toList(inputs);
            let masks;
            if (mask == null) {
                masks = generic_utils.pyListRepeat(null, inputs.length);
            }
            else {
                masks = generic_utils.toList(mask);
            }
            // TODO(michaelterry): Add support for mask caching.
            return this.runInternalGraph(inputs, masks)[1];
        });
    }
    /**
     * Computes the output shape of the layer.
     *
     * Assumes that the layer will be built to match that input shape provided.
     *
     * @param inputShape A shape (tuple of integers) or a list of shape tuples
     *   (one per output tensor of the layer). Shape tuples can include null for
     *   free dimensions, instead of an integer.
     */
    computeOutputShape(inputShape) {
        const inputShapes = types_utils.normalizeShapeList(inputShape);
        if (inputShapes.length !== this.inputLayers.length) {
            throw new ValueError(`Invalid inputShape argument ${inputShape}: ` +
                `model has ${this.inputLayers.length} tensor inputs.`);
        }
        // TODO(michaelterry): Add caching
        const layersToOutputShapes = {};
        for (let i = 0; i < inputShapes.length; i++) {
            const layer = this.inputLayers[i];
            const inputShape = inputShapes[i];
            // It's an input layer: computeOutputShape is identity,
            // and there is only one node and one tensor output.
            const shapeKey = layer.name + '_0_0';
            layersToOutputShapes[shapeKey] = inputShape;
        }
        const depthKeys = Object.keys(this.nodesByDepth)
            .map(x => parseInt(x, 10))
            .sort(generic_utils.reverseNumberCompare);
        // Iterate over nodes, by depth level.
        if (depthKeys.length > 1) {
            for (const depth of depthKeys) {
                const nodes = this.nodesByDepth[depth];
                for (const node of nodes) {
                    // This is always a single layer, never a list.
                    const layer = node.outboundLayer;
                    if (this.inputLayers.map(x => x.id).indexOf(layer.id) !== -1) {
                        // We've already covered the input layers a few lines above.
                        continue;
                    }
                    // Potentially redundant list, same size of node.inputTensors.
                    const inputShapes = [];
                    for (let j = 0; j < node.inboundLayers.length; j++) {
                        const inboundLayer = node.inboundLayers[j];
                        const nodeIndex = node.nodeIndices[j];
                        const tensorIndex = node.tensorIndices[j];
                        const shapeKey = `${inboundLayer.name}_${nodeIndex}_${tensorIndex}`;
                        const inputShape = layersToOutputShapes[shapeKey];
                        inputShapes.push(inputShape);
                    }
                    const outputShape = layer.computeOutputShape(generic_utils.singletonOrArray(inputShapes));
                    const outputShapes = types_utils.normalizeShapeList(outputShape);
                    const nodeIndex = layer.inboundNodes.indexOf(node);
                    for (let j = 0; j < outputShapes.length; j++) {
                        const shapeKey = `${layer.name}_${nodeIndex}_${j}`;
                        layersToOutputShapes[shapeKey] = outputShapes[j];
                    }
                }
            }
        }
        // Read final output shapes from layersToOutputShapes.
        const outputShapes = [];
        const outputShapeKeys = [];
        for (let i = 0; i < this.outputLayers.length; i++) {
            const layer = this.outputLayers[i];
            const nodeIndex = this.outputLayersNodeIndices[i];
            const tensorIndex = this.outputLayersTensorIndices[i];
            const shapeKey = `${layer.name}_${nodeIndex}_${tensorIndex}`;
            outputShapeKeys.push(shapeKey);
        }
        for (let i = 0; i < outputShapeKeys.length; i++) {
            const key = outputShapeKeys[i];
            generic_utils.assert(key in layersToOutputShapes);
            outputShapes.push(layersToOutputShapes[key]);
        }
        // TODO(michaelterry): Update cache
        return generic_utils.singletonOrArray(outputShapes);
    }
    /**
     * Computes output tensors for new inputs.
     *
     * Note:
     *   - Expects `inputs` to be a list (potentially with 1 element).
     *
     * @param inputs List of tensors
     * @param masks List of masks (tensors or null).
     * @return Three lists: outputTensors, outputMasks, outputShapes
     */
    runInternalGraph(inputs, masks) {
        if (masks == null) {
            masks = generic_utils.pyListRepeat(null, inputs.length);
        }
        // Dictionary mapping reference tensors to tuples
        // (computed tensor, compute mask)
        // we assume a 1:1 mapping from tensor to mask
        // TODO: raise exception when a `.computeMask()` call
        // does not return a list the same size as `call`
        const tensorMap = {};
        for (let i = 0; i < this.inputs.length; ++i) {
            const x = this.inputs[i];
            const y = inputs[i];
            const mask = masks[i];
            tensorMap[x.id] = [y, mask];
        }
        const depthKeys = Object.keys(this.nodesByDepth)
            .map(x => parseInt(x, 10))
            .sort(generic_utils.reverseNumberCompare);
        for (const depth of depthKeys) {
            const nodes = this.nodesByDepth[depth];
            for (const node of nodes) {
                // This is always a single layer, never a list.
                const layer = node.outboundLayer;
                const referenceInputTensors = node.inputTensors;
                const referenceOutputTensors = node.outputTensors;
                // If all previous input tensors are available in tensorMap,
                // then call node.inboundLayer on them.
                // List of tuples [input, mask]:
                const computedData = new Array();
                for (const x of referenceInputTensors) {
                    if (x.id in tensorMap) {
                        computedData.push(tensorMap[x.id]);
                    }
                }
                if (computedData.length === referenceInputTensors.length) {
                    // TODO(michaelterry): Add K.name_scope here, if we need it.
                    let kwargs = {};
                    let computedTensors;
                    let computedMasks;
                    let outputTensors;
                    let outputMasks;
                    // call layer
                    if (node.callArgs != null) {
                        kwargs = node.callArgs;
                    }
                    if (computedData.length === 1) {
                        const [computedTensor, computedMask] = computedData[0];
                        if (kwargs['mask'] == null) {
                            kwargs['mask'] = computedMask;
                        }
                        outputTensors =
                            generic_utils.toList(layer.call(computedTensor, kwargs));
                        outputMasks = generic_utils.toList(layer.computeMask(computedTensor, computedMask));
                        computedTensors = [computedTensor];
                        computedMasks = [computedMask];
                    }
                    else {
                        computedTensors = computedData.map(x => x[0]);
                        computedMasks = computedData.map(x => x[1]);
                        if (kwargs['mask'] == null) {
                            kwargs['mask'] = computedMasks;
                        }
                        outputTensors =
                            generic_utils.toList(layer.call(computedTensors, kwargs));
                        outputMasks = generic_utils.toList(layer.computeMask(computedTensors, computedMasks));
                    }
                    if (layer.activityRegularizer) {
                        throw new NotImplementedError('LayersModel invocation with concrete Tensor value(s) in the ' +
                            'presence of activity regularizer(s) is not supported yet.');
                    }
                    // TODO(michaelterry): Add model updates and losses
                    // Update tensor map.
                    for (let i = 0; i < referenceOutputTensors.length; ++i) {
                        const x = referenceOutputTensors[i];
                        const y = outputTensors[i];
                        const mask = outputMasks[i];
                        tensorMap[x.id] = [y, mask];
                    }
                }
            }
        }
        const outputTensors = [];
        const outputMasks = [];
        const outputShapes = [];
        for (const x of this.outputs) {
            generic_utils.assert(x.id in tensorMap, `Could not compute output ${x.name} : ${x.id}`);
            const [tensor, mask] = tensorMap[x.id];
            outputShapes.push(tensor.shape);
            outputTensors.push(tensor);
            outputMasks.push(mask);
        }
        // TODO(michaelterry): Add support for caches.
        return [outputTensors, outputMasks, outputShapes];
    }
    /**
     * Builds a map of internal node keys to node ordering.
     * Used in serializaion a node orderings may change as unused nodes are
     * dropped. Porting Note:  This helper method was pulled out of getConfig to
     * improve readability.
     * @param layers An array of Layers in the model.
     * @returns Map of Node Keys to index order within the layer.
     */
    buildNodeConversionMap(layers) {
        const nodeConversionMap = {};
        let keptNodes;
        for (const layer of this.layers) {
            keptNodes = layer instanceof Container ? 1 : 0;
            for (let originalNodeIndex = 0; originalNodeIndex < layer.inboundNodes.length; originalNodeIndex++) {
                const nodeKey = Container.nodeKey(layer, originalNodeIndex);
                if (this.containerNodes.has(nodeKey)) {
                    // i.e. we mark it to be saved
                    nodeConversionMap[nodeKey] = keptNodes;
                    keptNodes += 1;
                }
            }
        }
        return nodeConversionMap;
    }
    getLayer(nameOrIndex, index) {
        if (index != null) {
            return this.findLayer(index);
        }
        else {
            if (nameOrIndex == null) {
                throw new ValueError('Provide either a layer name or layer index');
            }
            if (typeof nameOrIndex === 'number') {
                return this.findLayer(nameOrIndex);
            }
        }
        for (const layer of this.layers) {
            if (layer.name === nameOrIndex) {
                return layer;
            }
        }
        throw new ValueError(`No such layer: ${nameOrIndex}`);
    }
    findLayer(index) {
        if (this.layers.length <= index) {
            throw new ValueError(`Was asked to retrieve layer at index ${index}, but model only ` +
                `has ${this.layers.length} layer(s).`);
        }
        else {
            return this.layers[index];
        }
    }
    /**
     * Retrieves the Container's current loss values.
     *
     * Used for regularizers during training.
     */
    calculateLosses() {
        // Porting Node: This is an augmentation to Container.loss in PyKeras.
        //   In PyKeras, Container.loss returns symbolic tensors. Here a concrete
        //   Tensor (specifically Scalar) values are returned. This is due to the
        //   imperative backend.
        return tidy(() => {
            const losses = [];
            for (const layer of this.layers) {
                for (let nodeIndex = 0; nodeIndex < layer.inboundNodes.length; ++nodeIndex) {
                    const nodeKey = Container.nodeKey(layer, nodeIndex);
                    if (this.containerNodes.has(nodeKey)) {
                        losses.push(...layer.calculateLosses());
                    }
                }
            }
            // TODO(cais): Add any unconditional model-level losses?
            return losses;
        });
    }
    getConfig() {
        const config = { name: this.name };
        // Build a map from layer unique name (self._node_key)
        // to the index of the nodes that are saved in the config.
        // Only nodes in container_nodes are saved.
        const nodeConversionMap = this.buildNodeConversionMap(this.layers);
        // Serialize and save the layers in layerConfigs
        const layerConfigs = [];
        for (const layer of this.layers) {
            const layerClassName = layer.getClassName();
            const layerConfig = layer.getConfig();
            const filteredInboundNodes = [];
            for (let originalNodeIndex = 0; originalNodeIndex < layer.inboundNodes.length; originalNodeIndex++) {
                const node = layer.inboundNodes[originalNodeIndex];
                const nodeKey = Container.nodeKey(layer, originalNodeIndex);
                let kwargs = {};
                if (this.containerNodes.has(nodeKey)) {
                    // The node is relevant to the model:
                    // add to filteredInboundNodes.
                    if (node.callArgs) {
                        try {
                            JSON.stringify(node.callArgs);
                            kwargs = node.callArgs;
                        }
                        catch (err) {
                            console.warn(`Layer ${layer.name} was passed ` +
                                `non-serializable keyword arguments: ` +
                                `${node.callArgs}. They will not be included ` +
                                `in the serialized model (and thus will be ` +
                                `missing at deserialization time).`);
                            kwargs = {};
                        }
                    }
                    if (node.inboundLayers.length > 0) {
                        const nodeData = [];
                        for (let i = 0; i < node.inboundLayers.length; i++) {
                            const inboundLayer = node.inboundLayers[i];
                            const nodeIndex = node.nodeIndices[i];
                            const tensorIndex = node.tensorIndices[i];
                            const nodeKey = Container.nodeKey(inboundLayer, nodeIndex);
                            let newNodeIndex = nodeConversionMap[nodeKey];
                            if (newNodeIndex == null) {
                                newNodeIndex = 0;
                            }
                            nodeData.push([inboundLayer.name, newNodeIndex, tensorIndex, kwargs]);
                        }
                        filteredInboundNodes.push(nodeData);
                    }
                }
            }
            const dict = {};
            dict['name'] = layer.name;
            dict['className'] = layerClassName;
            dict['config'] = layerConfig;
            dict['inboundNodes'] = filteredInboundNodes;
            layerConfigs.push(dict);
        }
        config['layers'] = layerConfigs;
        // Gather info about inputs and outputs
        const modelInputs = [];
        for (let i = 0; i < this.inputLayers.length; i++) {
            const layer = this.inputLayers[i];
            const nodeIndex = this.inputLayersNodeIndices[i];
            const nodeKey = Container.nodeKey(layer, nodeIndex);
            if (!this.containerNodes.has(nodeKey)) {
                continue;
            }
            let newNodeIndex = nodeConversionMap[nodeKey];
            if (newNodeIndex === null || newNodeIndex === undefined) {
                newNodeIndex = 0;
            }
            const tensorIndex = this.inputLayersTensorIndices[i];
            modelInputs.push([layer.name, newNodeIndex, tensorIndex]);
        }
        config['inputLayers'] = modelInputs;
        const modelOutputs = [];
        for (let i = 0; i < this.outputLayers.length; i++) {
            const layer = this.outputLayers[i];
            const nodeIndex = this.outputLayersNodeIndices[i];
            const nodeKey = Container.nodeKey(layer, nodeIndex);
            if (!this.containerNodes.has(nodeKey)) {
                continue;
            }
            let newNodeIndex = nodeConversionMap[nodeKey];
            if (newNodeIndex === null || newNodeIndex === undefined) {
                newNodeIndex = 0;
            }
            const tensorIndex = this.outputLayersTensorIndices[i];
            modelOutputs.push([layer.name, newNodeIndex, tensorIndex]);
        }
        config['outputLayers'] = modelOutputs;
        return config;
    }
    /**
     * Instantiates a LayersModel from its config (output of `get_config()`).
     * @param cls the class to create
     * @param config LayersModel config dictionary.
     * @param customObjects An optional dictionary of custom objects.
     * @param fastWeightInit Optional flag to use fast weight initialization
     *   during deserialization. This is applicable to cases in which
     *   the initialization will be immediately overwritten by loaded weight
     *   values. Default: `false`.
     * @returns A LayersModel instance.
     * @throws ValueError: In case of improperly formatted config dict.
     */
    /** @nocollapse */
    static fromConfig(cls, config, customObjects = {}, fastWeightInit = false) {
        // Layer instances created during
        // the graph reconstruction process
        const createdLayers = {};
        // Dictionary mapping layer instances to
        // node data that specifies a layer call.
        // It acts as a queue that maintains any unprocessed
        // layer call until it becomes possible to process it
        // (i.e. until the input tensors to the call all exist).
        const unprocessedNodes = {};
        function addUnprocessedNode(layer, nodeData) {
            if (!(layer.name in unprocessedNodes)) {
                unprocessedNodes[layer.name] = [nodeData];
            }
            else {
                unprocessedNodes[layer.name].push(nodeData);
            }
        }
        function processNode(layer, nodeData) {
            const inputTensors = [];
            let kwargs;
            for (const inputData of nodeData) {
                const inboundLayerName = inputData[0];
                const inboundNodeIndex = inputData[1];
                const inboundTensorIndex = inputData[2];
                kwargs = inputData[3] == null ?
                    {} :
                    inputData[3];
                if (!(inboundLayerName in createdLayers)) {
                    addUnprocessedNode(layer, nodeData);
                    return;
                }
                const inboundLayer = createdLayers[inboundLayerName];
                if (inboundLayer.inboundNodes.length <= inboundNodeIndex) {
                    addUnprocessedNode(layer, nodeData);
                    return;
                }
                const inboundNode = inboundLayer.inboundNodes[inboundNodeIndex];
                inputTensors.push(inboundNode.outputTensors[inboundTensorIndex]);
            }
            // Call layer on its inputs, thus creating the node
            // and building the layer if needed.
            // Note: This has Eager vs Graph Implications.
            if (inputTensors.length > 0) {
                layer.apply(generic_utils.singletonOrArray(inputTensors), kwargs); // was ** kwargs
            }
        }
        /**
         * Deserialize a layer, then call it on appropriate inputs.
         * @param layerData: layer config dict.
         * @throws ValueError: In case of improperly formatted `layer_data`
         * dict.
         */
        function processLayer(layerData) {
            const layerName = layerData['name'];
            // Instantiate layer.
            const layer = deserializeLayer(layerData, config['customObjects'] != null ?
                config['customObjects'] :
                {});
            layer.setFastWeightInitDuringBuild(fastWeightInit);
            createdLayers[layerName] = layer;
            // Gather layer inputs.
            const inboundNodesData = layerData['inboundNodes'];
            inboundNodesData.forEach(nodeData => {
                if (!(nodeData instanceof Array)) {
                    throw new ValueError(`Corrupted configuration, expected array for nodeData: ${nodeData}`);
                }
                // We don't process nodes (i.e. make layer calls)
                // on the fly because the inbound node may not yet exist,
                // in case of layer shared at different topological depths
                // (e.g.a model such as A(B(A(B(x)))))
                addUnprocessedNode(layer, nodeData);
            });
        }
        // First, we create all layers and enqueue nodes to be processed.
        const name = config['name'];
        const layersFromConfig = config['layers'];
        for (const layerData of layersFromConfig) {
            processLayer(layerData);
        }
        // Then we process nodes in order of layer depth.
        // Nodes that cannot yet be processed(if the inbound node
        // does not yet exist) are re - enqueued, and the process
        // is repeated until all nodes are processed.
        while (!generic_utils.isObjectEmpty(unprocessedNodes)) {
            for (const layerData of layersFromConfig) {
                const layer = createdLayers[layerData['name']];
                if (layer.name in unprocessedNodes) {
                    const currentUnprocessedNodesForLayer = unprocessedNodes[layer.name];
                    delete unprocessedNodes[layer.name];
                    for (const nodeData of currentUnprocessedNodesForLayer) {
                        processNode(layer, nodeData);
                    }
                }
            }
        }
        const inputTensors = [];
        const outputTensors = [];
        const inputLayersFromConfig = config['inputLayers'];
        for (const layerData of inputLayersFromConfig) {
            const layerName = layerData[0];
            const nodeIndex = layerData[1];
            const tensorIndex = layerData[2];
            generic_utils.assert(layerName in createdLayers);
            const layer = createdLayers[layerName];
            const layerOutputTensors = layer.inboundNodes[nodeIndex].outputTensors;
            inputTensors.push(layerOutputTensors[tensorIndex]);
        }
        const outputLayersFromConfig = config['outputLayers'];
        for (const layerData of outputLayersFromConfig) {
            const layerName = layerData[0];
            const nodeIndex = layerData[1];
            const tensorIndex = layerData[2];
            generic_utils.assert(layerName in createdLayers);
            const layer = createdLayers[layerName];
            const layerOutputTensors = layer.inboundNodes[nodeIndex].outputTensors;
            outputTensors.push(layerOutputTensors[tensorIndex]);
        }
        return new cls({ inputs: inputTensors, outputs: outputTensors, name });
    }
    /**
     * Determine whether the container is stateful.
     *
     * Porting Note: this is the equivalent of the stateful @property of
     *   the Container class in PyKeras.
     */
    get stateful() {
        // Porting Note: This check is to prevent inadvertent setting of the
        //   _stateful property of the Container instance.
        if (this._stateful) {
            throw new ValueError('Container instance unexpectedly has _stateful = true. The ' +
                'statefulness of a Container is determined by the Layers it ' +
                'contains. Its _stateful property must remain the default false.');
        }
        for (const layer of this.layers) {
            if (layer.stateful) {
                return true;
            }
        }
        return false;
    }
    /**
     * Reset the state of all stateful constituent layers (if any).
     *
     * Examples of stateful layers include RNN layers whose `stateful` property
     * is set as `true`.
     */
    resetStates() {
        tidy(() => {
            this.layers.forEach(layer => {
                // tslint:disable:no-any
                if (layer.stateful) {
                    layer.resetStates();
                }
                // tslint:enable:no-any
            });
        });
    }
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiY29udGFpbmVyLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsiLi4vLi4vLi4vLi4vLi4vLi4vdGZqcy1sYXllcnMvc3JjL2VuZ2luZS9jb250YWluZXIudHMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7Ozs7Ozs7O0dBUUc7QUFFSCwrQ0FBK0M7QUFFL0MsT0FBTyxFQUFnRCxJQUFJLEVBQUMsTUFBTSx1QkFBdUIsQ0FBQztBQUUxRixPQUFPLEVBQUMsTUFBTSxFQUFDLE1BQU0sa0JBQWtCLENBQUM7QUFDeEMsT0FBTyxFQUFDLG1CQUFtQixFQUFFLFlBQVksRUFBRSxVQUFVLEVBQUMsTUFBTSxXQUFXLENBQUM7QUFJeEUsT0FBTyxFQUFDLFdBQVcsSUFBSSxnQkFBZ0IsRUFBQyxNQUFNLHlCQUF5QixDQUFDO0FBRXhFLE9BQU8sS0FBSyxhQUFhLE1BQU0sd0JBQXdCLENBQUM7QUFDeEQsT0FBTyxFQUFDLG1CQUFtQixFQUFDLE1BQU0sOEJBQThCLENBQUM7QUFDakUsT0FBTyxLQUFLLFdBQVcsTUFBTSxzQkFBc0IsQ0FBQztBQUNwRCxPQUFPLEVBQUMsYUFBYSxFQUFnQixNQUFNLGNBQWMsQ0FBQztBQUMxRCxPQUFPLEVBQUMsT0FBTyxJQUFJLGFBQWEsRUFBQyxNQUFNLFlBQVksQ0FBQztBQUVwRCxPQUFPLEVBQUMsT0FBTyxFQUFFLFFBQVEsRUFBQyxNQUFNLFlBQVksQ0FBQztBQUM3QyxPQUFPLEVBQUMsVUFBVSxFQUFDLE1BQU0sZUFBZSxDQUFDO0FBQ3pDLE9BQU8sRUFBZ0IsS0FBSyxFQUFFLElBQUksRUFBaUIsTUFBTSxZQUFZLENBQUM7QUFTdEU7Ozs7OztHQU1HO0FBQ0gsTUFBTSxPQUFnQixTQUFVLFNBQVEsS0FBSztJQW9DM0MsWUFBWSxJQUFtQjtRQUM3Qix5Q0FBeUM7UUFDekMsS0FBSyxDQUFDLEVBQUUsQ0FBQyxDQUFDO1FBcEJaLG1CQUFjLEdBQUcsSUFBSSxHQUFHLEVBQVUsQ0FBQztRQXFCakMsSUFBSSxDQUFDLElBQUksR0FBRyxJQUFJLENBQUMsSUFBSSxDQUFDO1FBQ3RCLElBQUksSUFBSSxDQUFDLElBQUksSUFBSSxJQUFJLEVBQUU7WUFDckIsTUFBTSxNQUFNLEdBQUcsSUFBSSxDQUFDLFlBQVksRUFBRSxDQUFDLFdBQVcsRUFBRSxDQUFDO1lBQ2pELElBQUksQ0FBQyxJQUFJLEdBQUcsTUFBTSxDQUFDLE1BQU0sQ0FBQyxDQUFDO1NBQzVCO1FBRUQsSUFBSSxDQUFDLGVBQWUsR0FBRyxLQUFLLENBQUM7UUFDN0IsSUFBSSxDQUFDLFVBQVUsR0FBRyxJQUFJLENBQUM7UUFFdkIsOERBQThEO1FBRTlELGlDQUFpQztRQUNqQyxJQUFJLEtBQUssQ0FBQyxPQUFPLENBQUMsSUFBSSxDQUFDLE1BQU0sQ0FBQyxFQUFFO1lBQzlCLElBQUksQ0FBQyxNQUFNLEdBQUcsSUFBSSxDQUFDLE1BQU0sQ0FBQyxLQUFLLEVBQUUsQ0FBQztTQUNuQzthQUFNO1lBQ0wsSUFBSSxDQUFDLE1BQU0sR0FBRyxDQUFDLElBQUksQ0FBQyxNQUFNLENBQUMsQ0FBQztTQUM3QjtRQUNELElBQUksS0FBSyxDQUFDLE9BQU8sQ0FBQyxJQUFJLENBQUMsT0FBTyxDQUFDLEVBQUU7WUFDL0IsSUFBSSxDQUFDLE9BQU8sR0FBRyxJQUFJLENBQUMsT0FBTyxDQUFDLEtBQUssRUFBRSxDQUFDO1NBQ3JDO2FBQU07WUFDTCxJQUFJLENBQUMsT0FBTyxHQUFHLENBQUMsSUFBSSxDQUFDLE9BQU8sQ0FBQyxDQUFDO1NBQy9CO1FBRUQsa0NBQWtDO1FBQ2xDLElBQUksYUFBYSxDQUFDLE1BQU0sQ0FBQyxJQUFJLENBQUMsTUFBTSxDQUFDLENBQUMsTUFBTSxLQUFLLElBQUksQ0FBQyxNQUFNLENBQUMsTUFBTSxFQUFFO1lBQ25FLE1BQU0sSUFBSSxVQUFVLENBQ2hCLDRDQUE0QztnQkFDNUMsd0RBQXdEO2dCQUN4RCxHQUFHLElBQUksQ0FBQyxNQUFNLENBQUMsR0FBRyxDQUFDLENBQUMsQ0FBQyxFQUFFLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxFQUFFLENBQUMsQ0FBQztTQUN4QztRQUVELG1DQUFtQztRQUNuQyxJQUFJLGFBQWEsQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLE9BQU8sQ0FBQyxDQUFDLE1BQU0sS0FBSyxJQUFJLENBQUMsT0FBTyxDQUFDLE1BQU0sRUFBRTtZQUNyRSxPQUFPLENBQUMsSUFBSSxDQUNSLHdEQUF3RDtnQkFDeEQsOENBQThDO2dCQUM5QyxHQUFHLElBQUksQ0FBQyxPQUFPLENBQUMsR0FBRyxDQUFDLENBQUMsQ0FBQyxFQUFFLENBQUMsQ0FBQyxDQUFDLElBQUksQ0FBQyxFQUFFLENBQUMsQ0FBQztTQUN6QztRQUVEOzs7VUFHRTtRQUNGLElBQUksQ0FBQyxXQUFXLEdBQUcsRUFBRSxDQUFDO1FBQ3RCLElBQUksQ0FBQyxzQkFBc0IsR0FBRyxFQUFFLENBQUM7UUFDakMsSUFBSSxDQUFDLHdCQUF3QixHQUFHLEVBQUUsQ0FBQztRQUNuQzs7O1VBR0U7UUFDRixJQUFJLENBQUMsWUFBWSxHQUFHLEVBQUUsQ0FBQztRQUN2QixJQUFJLENBQUMsdUJBQXVCLEdBQUcsRUFBRSxDQUFDO1FBQ2xDLElBQUksQ0FBQyx5QkFBeUIsR0FBRyxFQUFFLENBQUM7UUFDcEM7OztVQUdFO1FBQ0YsSUFBSSxDQUFDLE1BQU0sR0FBRyxFQUFFLENBQUM7UUFFakI7OztVQUdFO1FBQ0YsSUFBSSxDQUFDLHFCQUFxQixHQUFHLEVBQUUsQ0FBQztRQUVoQyxtRUFBbUU7UUFDbkUsV0FBVztRQUNYOzs7Ozs7VUFNRTtRQUNGLCtCQUErQjtRQUMvQiw4QkFBOEI7UUFFOUIsMkJBQTJCO1FBQzNCLEtBQUssTUFBTSxDQUFDLElBQUksSUFBSSxDQUFDLE9BQU8sRUFBRTtZQUM1QixNQUFNLEtBQUssR0FBRyxDQUFDLENBQUMsV0FBVyxDQUFDO1lBQzVCLE1BQU0sU0FBUyxHQUFHLENBQUMsQ0FBQyxTQUFTLENBQUM7WUFDOUIsTUFBTSxXQUFXLEdBQUcsQ0FBQyxDQUFDLFdBQVcsQ0FBQztZQUNsQyxJQUFJLENBQUMsWUFBWSxDQUFDLElBQUksQ0FBQyxLQUFLLENBQUMsQ0FBQztZQUM5QixJQUFJLENBQUMsdUJBQXVCLENBQUMsSUFBSSxDQUFDLFNBQVMsQ0FBQyxDQUFDO1lBQzdDLElBQUksQ0FBQyx5QkFBeUIsQ0FBQyxJQUFJLENBQUMsV0FBVyxDQUFDLENBQUM7U0FDbEQ7UUFFRCxrREFBa0Q7UUFFbEQsMEJBQTBCO1FBQzFCLEtBQUssTUFBTSxDQUFDLElBQUksSUFBSSxDQUFDLE1BQU0sRUFBRTtZQUMzQixNQUFNLEtBQUssR0FBRyxDQUFDLENBQUMsV0FBVyxDQUFDO1lBQzVCLE1BQU0sU0FBUyxHQUFHLENBQUMsQ0FBQyxTQUFTLENBQUM7WUFDOUIsTUFBTSxXQUFXLEdBQUcsQ0FBQyxDQUFDLFdBQVcsQ0FBQztZQUNsQzs7O2NBR0U7WUFDRixhQUFhLENBQUMsTUFBTSxDQUFDLFNBQVMsS0FBSyxDQUFDLEVBQUUsMEJBQTBCLENBQUMsQ0FBQztZQUNsRSxhQUFhLENBQUMsTUFBTSxDQUFDLFdBQVcsS0FBSyxDQUFDLEVBQUUsNEJBQTRCLENBQUMsQ0FBQztZQUN0RSxJQUFJLENBQUMsV0FBVyxDQUFDLElBQUksQ0FBQyxLQUFLLENBQUMsQ0FBQztZQUM3QixJQUFJLENBQUMsc0JBQXNCLENBQUMsSUFBSSxDQUFDLFNBQVMsQ0FBQyxDQUFDO1lBQzVDLElBQUksQ0FBQyx3QkFBd0IsQ0FBQyxJQUFJLENBQUMsV0FBVyxDQUFDLENBQUM7U0FDakQ7UUFFRCw4Q0FBOEM7UUFDOUMsSUFBSSxDQUFDLFVBQVUsR0FBRyxFQUFFLENBQUM7UUFDckIsSUFBSSxDQUFDLFdBQVcsR0FBRyxFQUFFLENBQUM7UUFDdEIsSUFBSSxDQUFDLGVBQWUsR0FBRyxFQUFFLENBQUM7UUFDMUIsSUFBSSxDQUFDLGNBQWMsR0FBRyxFQUFFLENBQUM7UUFDekIsSUFBSSxDQUFDLGVBQWUsR0FBRyxFQUFFLENBQUM7UUFDMUIsS0FBSyxJQUFJLENBQUMsR0FBRyxDQUFDLEVBQUUsQ0FBQyxHQUFHLElBQUksQ0FBQyxXQUFXLENBQUMsTUFBTSxFQUFFLENBQUMsRUFBRSxFQUFFO1lBQ2hELE1BQU0sS0FBSyxHQUFHLElBQUksQ0FBQyxXQUFXLENBQUMsQ0FBQyxDQUFDLENBQUM7WUFDbEMscUNBQXFDO1lBQ3JDLElBQUksQ0FBQyxDQUFDLEtBQUssWUFBWSxVQUFVLENBQUMsRUFBRTtnQkFDbEMsTUFBTSxJQUFJLFNBQVMsQ0FDZiw0REFBNEQ7b0JBQzVELG9CQUFvQixJQUFJLENBQUMsTUFBTSxJQUFJO29CQUNuQyxTQUFTLENBQUMsd0JBQXdCO29CQUNsQyxtQkFBbUIsS0FBSyxDQUFDLFlBQVksRUFBRSxHQUFHLENBQUMsQ0FBQzthQUNqRDtZQUNELElBQUksQ0FBQyxVQUFVLENBQUMsSUFBSSxDQUFDLEtBQUssQ0FBQyxJQUFJLENBQUMsQ0FBQztZQUNqQyxJQUFJLENBQUMsZUFBZSxDQUFDLElBQUksQ0FBQyxLQUFLLENBQUMsZUFBZSxDQUFDLENBQUM7WUFFakQsSUFBSSxDQUFDLGNBQWMsQ0FBQyxJQUFJLENBQUMsS0FBSyxDQUFDLElBQUksQ0FBQyxDQUFDO1NBQ3RDO1FBQ0QsS0FBSyxNQUFNLEtBQUssSUFBSSxJQUFJLENBQUMsWUFBWSxFQUFFO1lBQ3JDLElBQUksQ0FBQyxXQUFXLENBQUMsSUFBSSxDQUFDLEtBQUssQ0FBQyxJQUFJLENBQUMsQ0FBQztTQUNuQztRQUVELElBQUksQ0FBQyxtQkFBbUIsR0FBRyxJQUFJLENBQUMsTUFBTSxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsRUFBRSxDQUFDLENBQUMsQ0FBQyxLQUFLLENBQUMsQ0FBQztRQUN6RCxJQUFJLENBQUMsb0JBQW9CLEdBQUcsSUFBSSxDQUFDLE9BQU8sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUMsS0FBSyxDQUFDLENBQUM7UUFFM0Q7OztVQUdFO1FBQ0YsOENBQThDO1FBQzlDLE1BQU0sV0FBVyxHQUErQixFQUFFLENBQUM7UUFDbkQsa0NBQWtDO1FBQ2xDLE1BQU0sWUFBWSxHQUE2QixFQUFFLENBQUM7UUFDbEQsTUFBTSxZQUFZLEdBQWdDLEVBQUUsQ0FBQztRQUNyRCwyQkFBMkI7UUFDM0IsTUFBTSxjQUFjLEdBQStCLEVBQUUsQ0FBQztRQUN0RCxNQUFNLFlBQVksR0FBZ0MsRUFBRSxDQUFDO1FBQ3JELE1BQU0sc0JBQXNCLEdBQVcsRUFBRSxDQUFDO1FBRTFDOzs7Ozs7Ozs7Ozs7Ozs7OztXQWlCRztRQUNILE1BQU0sZUFBZSxHQUNqQixDQUFDLE1BQXNCLEVBQUUsYUFBcUIsRUFBRSxlQUF1QixFQUN0RSxLQUFhLEVBQUUsU0FBa0IsRUFBRSxXQUFvQixFQUFFLEVBQUU7WUFDMUQsSUFBSSxLQUFLLElBQUksSUFBSSxJQUFJLFNBQVMsSUFBSSxJQUFJLElBQUksV0FBVyxJQUFJLElBQUksRUFBRTtnQkFDN0QsS0FBSyxHQUFHLE1BQU0sQ0FBQyxXQUFXLENBQUM7Z0JBQzNCLFNBQVMsR0FBRyxNQUFNLENBQUMsU0FBUyxDQUFDO2dCQUM3QixXQUFXLEdBQUcsTUFBTSxDQUFDLFdBQVcsQ0FBQzthQUNsQztZQUNELE1BQU0sSUFBSSxHQUFHLEtBQUssQ0FBQyxZQUFZLENBQUMsU0FBUyxDQUFDLENBQUM7WUFFM0Msa0JBQWtCO1lBQ2xCLElBQUksZUFBZSxDQUFDLE9BQU8sQ0FBQyxJQUFJLENBQUMsS0FBSyxDQUFDLENBQUMsRUFBRTtnQkFDeEMsTUFBTSxJQUFJLFlBQVksQ0FDbEIsY0FBYyxNQUFNLENBQUMsSUFBSSxjQUFjLEtBQUssQ0FBQyxJQUFJLElBQUk7b0JBQ3JELHFCQUFxQixDQUFDLENBQUM7YUFDNUI7WUFFRCx5Q0FBeUM7WUFDekMsSUFBSSxhQUFhLENBQUMsT0FBTyxDQUFDLElBQUksQ0FBQyxLQUFLLENBQUMsQ0FBQyxFQUFFO2dCQUN0QyxPQUFPO2FBQ1I7WUFFRCx5QkFBeUI7WUFDekIsSUFBSSxDQUFDLGNBQWMsQ0FBQyxHQUFHLENBQUMsU0FBUyxDQUFDLE9BQU8sQ0FBQyxLQUFLLEVBQUUsU0FBUyxDQUFDLENBQUMsQ0FBQztZQUU3RCwrQ0FBK0M7WUFDL0MsSUFBSSxDQUFDLENBQUMsS0FBSyxDQUFDLEVBQUUsSUFBSSxZQUFZLENBQUMsRUFBRTtnQkFDL0IsWUFBWSxDQUFDLEtBQUssQ0FBQyxFQUFFLENBQUMsR0FBRyxNQUFNLENBQUMsSUFBSSxDQUFDLFlBQVksQ0FBQyxDQUFDLE1BQU0sQ0FBQzthQUMzRDtZQUVELElBQUksZUFBZSxDQUFDLE9BQU8sQ0FBQyxJQUFJLENBQUMsS0FBSyxDQUFDLENBQUMsRUFBRTtnQkFDeEMsZUFBZSxDQUFDLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQzthQUM1QjtZQUVELDREQUE0RDtZQUM1RCxNQUFNLGdCQUFnQixHQUFHLElBQUksQ0FBQyxhQUFhLENBQUMsTUFBTSxDQUFDO1lBQ25ELEtBQUssSUFBSSxDQUFDLEdBQUcsQ0FBQyxFQUFFLENBQUMsR0FBRyxnQkFBZ0IsRUFBRSxDQUFDLEVBQUUsRUFBRTtnQkFDekMsTUFBTSxDQUFDLEdBQUcsSUFBSSxDQUFDLFlBQVksQ0FBQyxDQUFDLENBQUMsQ0FBQztnQkFDL0IsTUFBTSxLQUFLLEdBQUcsSUFBSSxDQUFDLGFBQWEsQ0FBQyxDQUFDLENBQUMsQ0FBQztnQkFDcEMsTUFBTSxTQUFTLEdBQUcsSUFBSSxDQUFDLFdBQVcsQ0FBQyxDQUFDLENBQUMsQ0FBQztnQkFDdEMsTUFBTSxXQUFXLEdBQUcsSUFBSSxDQUFDLGFBQWEsQ0FBQyxDQUFDLENBQUMsQ0FBQztnQkFDMUMsZUFBZSxDQUNYLENBQUMsRUFBRSxhQUFhLEVBQUUsZUFBZSxFQUFFLEtBQUssRUFBRSxTQUFTLEVBQ25ELFdBQVcsQ0FBQyxDQUFDO2FBQ2xCO1lBQ0QsYUFBYSxDQUFDLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQztZQUN6QixPQUFPLGVBQWUsQ0FBQyxPQUFPLENBQUMsSUFBSSxDQUFDLElBQUksQ0FBQyxFQUFFO2dCQUN6QyxlQUFlLENBQUMsTUFBTSxDQUFDLGVBQWUsQ0FBQyxPQUFPLENBQUMsSUFBSSxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUM7YUFDMUQ7WUFDRCxzQkFBc0IsQ0FBQyxJQUFJLENBQUMsSUFBSSxDQUFDLENBQUM7UUFDcEMsQ0FBQyxDQUFDO1FBRU4sTUFBTSxhQUFhLEdBQVcsRUFBRSxDQUFDO1FBQ2pDLE1BQU0sZUFBZSxHQUFXLEVBQUUsQ0FBQztRQUNuQyxLQUFLLE1BQU0sQ0FBQyxJQUFJLElBQUksQ0FBQyxPQUFPLEVBQUU7WUFDNUIsZUFBZSxDQUFDLENBQUMsRUFBRSxhQUFhLEVBQUUsZUFBZSxDQUFDLENBQUM7U0FDcEQ7UUFFRCxNQUFNLDhCQUE4QixHQUNoQyxzQkFBc0IsQ0FBQyxLQUFLLEVBQUUsQ0FBQyxPQUFPLEVBQUUsQ0FBQztRQUM3QyxLQUFLLE1BQU0sSUFBSSxJQUFJLDhCQUE4QixFQUFFO1lBQ2pELFlBQVksQ0FBQyxJQUFJLENBQUMsRUFBRSxDQUFDLEdBQUcsSUFBSSxDQUFDO1lBQzdCLHFFQUFxRTtZQUNyRSxJQUFJLENBQUMsQ0FBQyxJQUFJLENBQUMsRUFBRSxJQUFJLFdBQVcsQ0FBQyxFQUFFO2dCQUM3QixXQUFXLENBQUMsSUFBSSxDQUFDLEVBQUUsQ0FBQyxHQUFHLENBQUMsQ0FBQzthQUMxQjtZQUNELElBQUksS0FBSyxHQUFHLFdBQVcsQ0FBQyxJQUFJLENBQUMsRUFBRSxDQUFDLENBQUM7WUFFakMsOENBQThDO1lBQzlDLE1BQU0sYUFBYSxHQUNmLENBQUMsWUFBWSxDQUFDLElBQUksQ0FBQyxhQUFhLENBQUMsRUFBRSxDQUFDLElBQUksSUFBSSxDQUFDLENBQUM7Z0JBQ3pDLENBQUMsQ0FBQyxDQUFDO2dCQUNILFlBQVksQ0FBQyxJQUFJLENBQUMsYUFBYSxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUM7WUFFOUM7Ozs7Y0FJRTtZQUNGLEtBQUssR0FBRyxJQUFJLENBQUMsR0FBRyxDQUFDLEtBQUssRUFBRSxhQUFhLENBQUMsQ0FBQztZQUN2QyxZQUFZLENBQUMsSUFBSSxDQUFDLGFBQWEsQ0FBQyxFQUFFLENBQUMsR0FBRyxLQUFLLENBQUM7WUFDNUMsY0FBYyxDQUFDLElBQUksQ0FBQyxhQUFhLENBQUMsRUFBRSxDQUFDLEdBQUcsSUFBSSxDQUFDLGFBQWEsQ0FBQztZQUMzRCxXQUFXLENBQUMsSUFBSSxDQUFDLEVBQUUsQ0FBQyxHQUFHLEtBQUssQ0FBQztZQUU3QixxQ0FBcUM7WUFDckMsS0FBSyxJQUFJLENBQUMsR0FBRyxDQUFDLEVBQUUsQ0FBQyxHQUFHLElBQUksQ0FBQyxhQUFhLENBQUMsTUFBTSxFQUFFLENBQUMsRUFBRSxFQUFFO2dCQUNsRCxNQUFNLFlBQVksR0FBRyxJQUFJLENBQUMsYUFBYSxDQUFDLENBQUMsQ0FBQyxDQUFDO2dCQUMzQyxNQUFNLFNBQVMsR0FBRyxJQUFJLENBQUMsV0FBVyxDQUFDLENBQUMsQ0FBQyxDQUFDO2dCQUN0QyxNQUFNLFdBQVcsR0FBRyxZQUFZLENBQUMsWUFBWSxDQUFDLFNBQVMsQ0FBQyxDQUFDO2dCQUN6RCxNQUFNLGFBQWEsR0FDZixDQUFDLFdBQVcsQ0FBQyxXQUFXLENBQUMsRUFBRSxDQUFDLElBQUksSUFBSSxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQztvQkFDSCxXQUFXLENBQUMsV0FBVyxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUM7Z0JBQ3hFLFdBQVcsQ0FBQyxXQUFXLENBQUMsRUFBRSxDQUFDLEdBQUcsSUFBSSxDQUFDLEdBQUcsQ0FBQyxLQUFLLEdBQUcsQ0FBQyxFQUFFLGFBQWEsQ0FBQyxDQUFDO2dCQUNqRSxZQUFZLENBQUMsV0FBVyxDQUFDLEVBQUUsQ0FBQyxHQUFHLFdBQVcsQ0FBQzthQUM1QztTQUNGO1FBRUQsc0RBQXNEO1FBQ3RELE1BQU0sWUFBWSxHQUE4QixFQUFFLENBQUM7UUFDbkQsS0FBSyxNQUFNLE1BQU0sSUFBSSxXQUFXLEVBQUU7WUFDaEMsTUFBTSxLQUFLLEdBQUcsV0FBVyxDQUFDLE1BQU0sQ0FBQyxDQUFDO1lBQ2xDLElBQUksQ0FBQyxDQUFDLEtBQUssSUFBSSxZQUFZLENBQUMsRUFBRTtnQkFDNUIsWUFBWSxDQUFDLEtBQUssQ0FBQyxHQUFHLEVBQUUsQ0FBQzthQUMxQjtZQUNELFlBQVksQ0FBQyxLQUFLLENBQUMsQ0FBQyxJQUFJLENBQUMsWUFBWSxDQUFDLE1BQU0sQ0FBQyxDQUFDLENBQUM7U0FDaEQ7UUFFRCx1REFBdUQ7UUFDdkQsTUFBTSxhQUFhLEdBQStCLEVBQUUsQ0FBQztRQUNyRCxLQUFLLE1BQU0sT0FBTyxJQUFJLFlBQVksRUFBRTtZQUNsQyxNQUFNLEtBQUssR0FBRyxZQUFZLENBQUMsT0FBTyxDQUFDLENBQUM7WUFDcEMsSUFBSSxDQUFDLENBQUMsS0FBSyxJQUFJLGFBQWEsQ0FBQyxFQUFFO2dCQUM3QixhQUFhLENBQUMsS0FBSyxDQUFDLEdBQUcsRUFBRSxDQUFDO2FBQzNCO1lBQ0QsYUFBYSxDQUFDLEtBQUssQ0FBQyxDQUFDLElBQUksQ0FBQyxjQUFjLENBQUMsT0FBTyxDQUFDLENBQUMsQ0FBQztTQUNwRDtRQUVELG1DQUFtQztRQUNuQyxJQUFJLFNBQVMsR0FBRyxNQUFNLENBQUMsSUFBSSxDQUFDLGFBQWEsQ0FBQzthQUNyQixHQUFHLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxRQUFRLENBQUMsQ0FBQyxFQUFFLEVBQUUsQ0FBQyxDQUFDO2FBQ3pCLElBQUksQ0FBQyxhQUFhLENBQUMsb0JBQW9CLENBQUMsQ0FBQztRQUU5RCwwQ0FBMEM7UUFDMUMsSUFBSSxDQUFDLE1BQU0sR0FBRyxFQUFFLENBQUM7UUFDakIsS0FBSyxNQUFNLEtBQUssSUFBSSxTQUFTLEVBQUU7WUFDN0IsTUFBTSxjQUFjLEdBQUcsYUFBYSxDQUFDLEtBQUssQ0FBQyxDQUFDO1lBQzVDLHdEQUF3RDtZQUN4RCx5Q0FBeUM7WUFDekMsY0FBYyxDQUFDLElBQUksQ0FBQyxDQUFDLENBQUMsRUFBRSxDQUFDLEVBQUUsRUFBRTtnQkFDM0IsTUFBTSxNQUFNLEdBQUcsWUFBWSxDQUFDLENBQUMsQ0FBQyxFQUFFLENBQUMsQ0FBQztnQkFDbEMsTUFBTSxNQUFNLEdBQUcsWUFBWSxDQUFDLENBQUMsQ0FBQyxFQUFFLENBQUMsQ0FBQztnQkFDbEMsSUFBSSxNQUFNLEdBQUcsTUFBTSxFQUFFO29CQUNuQixPQUFPLENBQUMsQ0FBQyxDQUFDO2lCQUNYO2dCQUNELElBQUksTUFBTSxHQUFHLE1BQU0sRUFBRTtvQkFDbkIsT0FBTyxDQUFDLENBQUM7aUJBQ1Y7Z0JBQ0QsT0FBTyxDQUFDLENBQUM7WUFDWCxDQUFDLENBQUMsQ0FBQztZQUNILEtBQUssTUFBTSxLQUFLLElBQUksY0FBYyxFQUFFO2dCQUNsQyxJQUFJLEtBQUssWUFBWSxTQUFTLEVBQUU7b0JBQzlCLElBQUksQ0FBQyxxQkFBcUIsQ0FBQyxJQUFJLENBQUMsS0FBSyxDQUFDLENBQUM7aUJBQ3hDO2dCQUNELElBQUksQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLEtBQUssQ0FBQyxDQUFDO2FBQ3pCO1NBQ0Y7UUFDRCxJQUFJLENBQUMsYUFBYSxHQUFHLGFBQWEsQ0FBQztRQUVuQyxrQ0FBa0M7UUFDbEMsU0FBUyxHQUFHLE1BQU0sQ0FBQyxJQUFJLENBQUMsWUFBWSxDQUFDO2FBQ3BCLEdBQUcsQ0FBQyxDQUFDLENBQUMsRUFBRSxDQUFDLFFBQVEsQ0FBQyxDQUFDLEVBQUUsRUFBRSxDQUFDLENBQUM7YUFDekIsSUFBSSxDQUFDLGFBQWEsQ0FBQyxvQkFBb0IsQ0FBQyxDQUFDO1FBRTFELGtEQUFrRDtRQUNsRCwrQ0FBK0M7UUFDL0MsaURBQWlEO1FBQ2pELE1BQU0saUJBQWlCLEdBQUcsSUFBSSxDQUFDLE1BQU0sQ0FBQyxLQUFLLEVBQUUsQ0FBQztRQUU5QyxpQ0FBaUM7UUFDakMsTUFBTSx1QkFBdUIsR0FBYSxFQUFFLENBQUM7UUFDN0MsS0FBSyxNQUFNLEtBQUssSUFBSSxTQUFTLEVBQUU7WUFDN0IsS0FBSyxNQUFNLElBQUksSUFBSSxZQUFZLENBQUMsS0FBSyxDQUFDLEVBQUU7Z0JBQ3RDLE1BQU0sS0FBSyxHQUFHLElBQUksQ0FBQyxhQUFhLENBQUM7Z0JBQ2pDLElBQUksS0FBSyxJQUFJLElBQUksRUFBRTtvQkFDakIsS0FBSyxNQUFNLENBQUMsSUFBSSxJQUFJLENBQUMsWUFBWSxFQUFFO3dCQUNqQyxJQUFJLGlCQUFpQixDQUFDLE9BQU8sQ0FBQyxDQUFDLENBQUMsS0FBSyxDQUFDLENBQUMsRUFBRTs0QkFDdkMsTUFBTSxJQUFJLFlBQVksQ0FDbEIsc0RBQXNELENBQUMsRUFBRTtnQ0FDekQsY0FBYyxLQUFLLENBQUMsSUFBSSxLQUFLO2dDQUM3QixzREFBc0Q7Z0NBQ3RELFVBQVUsdUJBQXVCLEVBQUUsQ0FBQyxDQUFDO3lCQUMxQztxQkFDRjtvQkFDRCxLQUFLLE1BQU0sQ0FBQyxJQUFJLElBQUksQ0FBQyxhQUFhLEVBQUU7d0JBQ2xDLGlCQUFpQixDQUFDLElBQUksQ0FBQyxDQUFDLENBQUMsQ0FBQztxQkFDM0I7b0JBQ0QsdUJBQXVCLENBQUMsSUFBSSxDQUFDLEtBQUssQ0FBQyxJQUFJLENBQUMsQ0FBQztpQkFDMUM7YUFDRjtTQUNGO1FBRUQsaURBQWlEO1FBQ2pELElBQUksQ0FBQyxZQUFZLEdBQUcsWUFBWSxDQUFDO1FBRWpDLCtEQUErRDtRQUMvRCwwREFBMEQ7UUFDMUQsTUFBTSxRQUFRLEdBQUcsSUFBSSxDQUFDLE1BQU0sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUMsSUFBSSxDQUFDLENBQUM7UUFDOUMsS0FBSyxNQUFNLElBQUksSUFBSSxRQUFRLEVBQUU7WUFDM0IsTUFBTSxjQUFjLEdBQUcsUUFBUSxDQUFDLE1BQU0sQ0FBQyxDQUFDLENBQUMsRUFBRSxDQUFDLENBQUMsS0FBSyxJQUFJLENBQUMsQ0FBQyxNQUFNLENBQUM7WUFDL0QsSUFBSSxjQUFjLEtBQUssQ0FBQyxFQUFFO2dCQUN4QixNQUFNLElBQUksWUFBWSxDQUNsQixhQUFhLElBQUksYUFBYSxjQUFjLFNBQVM7b0JBQ3JELCtEQUErRDtvQkFDL0QsSUFBSSxDQUFDLFNBQVMsQ0FBQyxRQUFRLENBQUMsQ0FBQyxDQUFDO2FBQy9CO1NBQ0Y7UUFFRCxvQkFBb0I7UUFDcEIsc0RBQXNEO1FBQ3RELHlDQUF5QztRQUN6QyxrREFBa0Q7UUFDbEQsSUFBSSxDQUFDLGFBQWEsR0FBRyxFQUFFLENBQUM7UUFDeEIsNkRBQTZEO1FBQzdELElBQUksQ0FBQyxZQUFZLEdBQUcsRUFBRSxDQUFDO1FBRXZCLCtEQUErRDtRQUMvRCxnQ0FBZ0M7UUFDaEMsZ0RBQWdEO1FBQ2hELElBQUksSUFBSSxDQUFDO1lBQ1AsYUFBYSxFQUFFLElBQUk7WUFDbkIsYUFBYSxFQUFFLEVBQUU7WUFDakIsV0FBVyxFQUFFLEVBQUU7WUFDZixhQUFhLEVBQUUsRUFBRTtZQUNqQixZQUFZLEVBQUUsSUFBSSxDQUFDLE1BQU07WUFDekIsYUFBYSxFQUFFLElBQUksQ0FBQyxPQUFPO1lBQzNCLFVBQVUsRUFBRSxJQUFJLENBQUMsTUFBTSxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsRUFBRSxDQUFDLElBQUksQ0FBQztZQUN0QyxXQUFXLEVBQUUsSUFBSSxDQUFDLE9BQU8sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxJQUFJLENBQUM7WUFDeEMsV0FBVyxFQUFFLElBQUksQ0FBQyxNQUFNLENBQUMsR0FBRyxDQUFDLENBQUMsQ0FBQyxFQUFFLENBQUMsQ0FBQyxDQUFDLEtBQUssQ0FBQztZQUMxQyxZQUFZLEVBQUUsSUFBSSxDQUFDLE9BQU8sQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUMsS0FBSyxDQUFDO1NBQzdDLENBQUMsQ0FBQztRQUNILElBQUksQ0FBQyxLQUFLLEdBQUcsSUFBSSxDQUFDO1FBQ2xCLElBQUksQ0FBQyxTQUFTLEdBQUcsQ0FBQyxDQUFDLENBQUUsa0RBQWtEO0lBQ3pFLENBQUM7SUFFa0IsaUJBQWlCO1FBQ2xDLElBQUksSUFBSSxDQUFDLFNBQVMsS0FBSyxDQUFDLEVBQUU7WUFDeEIsTUFBTSxJQUFJLEtBQUssQ0FBQyxjQUFjLElBQUksQ0FBQyxJQUFJLHdCQUF3QixDQUFDLENBQUM7U0FDbEU7SUFDSCxDQUFDO0lBRUQ7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7T0F5Qkc7SUFDTSxPQUFPO1FBQ2QsSUFBSSxDQUFDLGlCQUFpQixFQUFFLENBQUM7UUFDekIsTUFBTSxNQUFNLEdBQ1EsRUFBQyxvQkFBb0IsRUFBRSxJQUFJLEVBQUUsb0JBQW9CLEVBQUUsQ0FBQyxFQUFDLENBQUM7UUFDMUUsSUFBSSxFQUFFLElBQUksQ0FBQyxTQUFTLEtBQUssQ0FBQyxFQUFFO1lBQzFCLEtBQUssTUFBTSxLQUFLLElBQUksSUFBSSxDQUFDLE1BQU0sRUFBRTtnQkFDL0IsTUFBTSxDQUFDLG9CQUFvQixJQUFJLEtBQUssQ0FBQyxPQUFPLEVBQUUsQ0FBQyxvQkFBb0IsQ0FBQzthQUNyRTtZQUVELDBFQUEwRTtZQUMxRSx1RUFBdUU7WUFDdkUsS0FBSyxNQUFNLFNBQVMsSUFBSSxJQUFJLENBQUMscUJBQXFCLEVBQUU7Z0JBQ2xELE1BQU0sQ0FBQyxvQkFBb0IsSUFBSSxTQUFTLENBQUMsT0FBTyxFQUFFLENBQUMsb0JBQW9CLENBQUM7YUFDekU7U0FDRjtRQUNELE1BQU0sQ0FBQyxvQkFBb0IsR0FBRyxJQUFJLENBQUMsU0FBUyxDQUFDO1FBQzdDLE9BQU8sTUFBTSxDQUFDO0lBQ2hCLENBQUM7SUFFRCxJQUFhLFNBQVM7UUFDcEIsT0FBTyxJQUFJLENBQUMsVUFBVSxDQUFDO0lBQ3pCLENBQUM7SUFFRCxJQUFhLFNBQVMsQ0FBQyxTQUFrQjtRQUN2QyxJQUFJLENBQUMsTUFBTSxDQUFDLE9BQU8sQ0FBQyxLQUFLLENBQUMsRUFBRTtZQUMxQixrQ0FBa0M7WUFDaEMsS0FBYSxDQUFDLGlCQUFxQztpQkFDaEQsT0FBTyxDQUFDLENBQUMsQ0FBQyxFQUFFLENBQUMsQ0FBQyxDQUFDLFNBQVMsR0FBRyxTQUFTLENBQUMsQ0FBQztRQUM3QyxDQUFDLENBQUMsQ0FBQztRQUNILElBQUksQ0FBQyxVQUFVLEdBQUcsU0FBUyxDQUFDO0lBQzlCLENBQUM7SUFFRCxJQUFhLGdCQUFnQjtRQUMzQixnRUFBZ0U7UUFDaEUsbUVBQW1FO1FBQ25FLHdCQUF3QjtRQUN4QixJQUFJLElBQUksQ0FBQyxpQkFBaUIsQ0FBQyxNQUFNLEdBQUcsQ0FBQyxFQUFFO1lBQ3JDLE1BQU0sSUFBSSxVQUFVLENBQ2hCLDZEQUE2RDtnQkFDN0QsMERBQTBEO2dCQUMxRCxzREFBc0Q7Z0JBQ3RELCtDQUErQyxDQUFDLENBQUM7U0FDdEQ7UUFFRCxJQUFJLENBQUMsSUFBSSxDQUFDLFNBQVMsRUFBRTtZQUNuQixPQUFPLEVBQUUsQ0FBQztTQUNYO1FBQ0QsSUFBSSxPQUFPLEdBQW9CLEVBQUUsQ0FBQztRQUNsQyxLQUFLLE1BQU0sS0FBSyxJQUFJLElBQUksQ0FBQyxNQUFNLEVBQUU7WUFDL0IsT0FBTyxHQUFHLE9BQU8sQ0FBQyxNQUFNLENBQUMsS0FBSyxDQUFDLGdCQUFnQixDQUFDLENBQUM7U0FDbEQ7UUFDRCxPQUFPLE9BQU8sQ0FBQztJQUNqQixDQUFDO0lBRUQsSUFBYSxtQkFBbUI7UUFDOUIsTUFBTSxPQUFPLEdBQW9CLEVBQUUsQ0FBQztRQUNwQyxLQUFLLE1BQU0sS0FBSyxJQUFJLElBQUksQ0FBQyxNQUFNLEVBQUU7WUFDL0IsT0FBTyxDQUFDLElBQUksQ0FBQyxHQUFHLEtBQUssQ0FBQyxtQkFBbUIsQ0FBQyxDQUFDO1NBQzVDO1FBQ0QsSUFBSSxDQUFDLElBQUksQ0FBQyxTQUFTLEVBQUU7WUFDbkIsTUFBTSxnQkFBZ0IsR0FBb0IsRUFBRSxDQUFDO1lBQzdDLEtBQUssTUFBTSxLQUFLLElBQUksSUFBSSxDQUFDLE1BQU0sRUFBRTtnQkFDL0IsZ0JBQWdCLENBQUMsSUFBSSxDQUFDLEdBQUcsS0FBSyxDQUFDLGdCQUFnQixDQUFDLENBQUM7YUFDbEQ7WUFDRCxPQUFPLGdCQUFnQixDQUFDLE1BQU0sQ0FBQyxPQUFPLENBQUMsQ0FBQztTQUN6QztRQUNELE9BQU8sT0FBTyxDQUFDO0lBQ2pCLENBQUM7SUFFRCxJQUFhLE9BQU87UUFDbEIsT0FBTyxJQUFJLENBQUMsZ0JBQWdCLENBQUMsTUFBTSxDQUFDLElBQUksQ0FBQyxtQkFBbUIsQ0FBQyxDQUFDO0lBQ2hFLENBQUM7SUFFRDs7Ozs7Ozs7Ozs7Ozs7T0FjRztJQUNILFdBQVcsQ0FBQyxPQUF1QixFQUFFLE1BQU0sR0FBRyxJQUFJO1FBQ2hELE1BQU0sWUFBWSxHQUFvQyxFQUFFLENBQUM7UUFDekQsSUFBSSxpQkFBaUIsR0FBRyxDQUFDLENBQUM7UUFDMUIsS0FBSyxNQUFNLEtBQUssSUFBSSxJQUFJLENBQUMsTUFBTSxFQUFFO1lBQy9CLEtBQUssTUFBTSxNQUFNLElBQUksS0FBSyxDQUFDLE9BQU8sRUFBRTtnQkFDbEMsSUFBSSxZQUFZLENBQUMsTUFBTSxDQUFDLFlBQVksQ0FBQyxJQUFJLElBQUksRUFBRTtvQkFDN0MsTUFBTSxJQUFJLFVBQVUsQ0FBQywwQkFBMEIsTUFBTSxDQUFDLFlBQVksRUFBRSxDQUFDLENBQUM7aUJBQ3ZFO2dCQUNELFlBQVksQ0FBQyxNQUFNLENBQUMsWUFBWSxDQUFDLEdBQUcsTUFBTSxDQUFDO2dCQUMzQyxpQkFBaUIsRUFBRSxDQUFDO2FBQ3JCO1NBQ0Y7UUFFRCxNQUFNLGlCQUFpQixHQUFtQyxFQUFFLENBQUM7UUFDN0QsS0FBSyxNQUFNLElBQUksSUFBSSxPQUFPLEVBQUU7WUFDMUIsK0RBQStEO1lBQy9ELHNEQUFzRDtZQUN0RCx1QkFBdUI7WUFDdkIsSUFBSSxhQUFhLEdBQUcsSUFBSSxDQUFDO1lBQ3pCLElBQUksWUFBWSxDQUFDLElBQUksQ0FBQyxJQUFJLElBQUksRUFBRTtnQkFDOUIsTUFBTSxNQUFNLEdBQUcsSUFBSSxDQUFDLEtBQUssQ0FBQyxHQUFHLENBQUMsQ0FBQztnQkFDL0IsTUFBTSxnQkFBZ0IsR0FDbEIsTUFBTSxDQUFDLEtBQUssQ0FBQyxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUMsQ0FBQyxNQUFNLENBQUMsQ0FBQyxNQUFNLENBQUMsTUFBTSxDQUFDLE1BQU0sR0FBRyxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUM7Z0JBQzVELGFBQWEsR0FBRyxnQkFBZ0IsQ0FBQyxJQUFJLENBQUMsR0FBRyxDQUFDLENBQUM7YUFDNUM7WUFDRCxJQUFJLFlBQVksQ0FBQyxhQUFhLENBQUMsSUFBSSxJQUFJLEVBQUU7Z0JBQ3ZDLGlCQUFpQixDQUFDLElBQUksQ0FBQyxDQUFDLFlBQVksQ0FBQyxhQUFhLENBQUMsRUFBRSxPQUFPLENBQUMsSUFBSSxDQUFDLENBQUMsQ0FBQyxDQUFDO2FBQ3RFO2lCQUFNLElBQUksTUFBTSxFQUFFO2dCQUNqQixNQUFNLElBQUksVUFBVSxDQUNoQixnREFBZ0QsSUFBSSxFQUFFLENBQUMsQ0FBQzthQUM3RDtZQUNELE9BQU8sWUFBWSxDQUFDLGFBQWEsQ0FBQyxDQUFDO1NBQ3BDO1FBRUQsSUFBSSxNQUFNLEVBQUU7WUFDVixrQ0FBa0M7WUFDbEMsTUFBTSxVQUFVLEdBQWEsRUFBRSxDQUFDO1lBQ2hDLEtBQUssTUFBTSxJQUFJLElBQUksWUFBWSxFQUFFO2dCQUMvQixVQUFVLENBQUMsSUFBSSxDQUFDLElBQUksQ0FBQyxDQUFDO2FBQ3ZCO1lBQ0QsSUFBSSxVQUFVLENBQUMsTUFBTSxHQUFHLENBQUMsRUFBRTtnQkFDekIsTUFBTSxJQUFJLFVBQVUsQ0FDaEIsR0FBRyxVQUFVLENBQUMsTUFBTSxPQUNoQixpQkFBaUIsd0JBQXdCO29CQUM3QyxHQUFHLFVBQVUsRUFBRSxDQUFDLENBQUM7YUFDdEI7U0FDRjtRQUVELGFBQWEsQ0FBQyxpQkFBaUIsQ0FBQyxDQUFDO0lBQ25DLENBQUM7SUFFRDs7O09BR0c7SUFDTyxhQUFhO1FBQ3JCLE1BQU0sU0FBUyxHQUFHLElBQUksQ0FBQyxTQUFTLEVBQUUsQ0FBQztRQUNuQyxNQUFNLFdBQVcsR0FBNkIsRUFBRSxDQUFDO1FBQ2pELFdBQVcsQ0FBQyxXQUFXLENBQUMsR0FBRyxJQUFJLENBQUMsWUFBWSxFQUFFLENBQUM7UUFDL0MsV0FBVyxDQUFDLFFBQVEsQ0FBQyxHQUFHLFNBQVMsQ0FBQztRQUNsQyxXQUFXLENBQUMsY0FBYyxDQUFDLEdBQUcsZUFBZSxhQUFhLEVBQUUsQ0FBQztRQUM3RCwwREFBMEQ7UUFDMUQsWUFBWTtRQUNaLFdBQVcsQ0FBQyxTQUFTLENBQUMsR0FBRyxlQUFlLENBQUM7UUFDekMsT0FBTyxXQUFXLENBQUM7SUFDckIsQ0FBQztJQUVEOzs7Ozs7Ozs7O09BVUc7SUFDSCxrQ0FBa0M7SUFDbEMsTUFBTSxDQUFDLE1BQVksRUFBRSxZQUFZLEdBQUcsSUFBSTtRQUN0QyxNQUFNLFdBQVcsR0FBRyxtQkFBbUIsQ0FBQyxJQUFJLENBQUMsYUFBYSxFQUFFLENBQWUsQ0FBQztRQUM1RSxPQUFPLFlBQVksQ0FBQyxDQUFDLENBQUMsSUFBSSxDQUFDLFNBQVMsQ0FBQyxXQUFXLENBQUMsQ0FBQyxDQUFDLENBQUMsV0FBVyxDQUFDO0lBQ2xFLENBQUM7SUFFRDs7Ozs7Ozs7Ozs7O09BWUc7SUFDTSxJQUFJLENBQUMsTUFBdUIsRUFBRSxNQUFjO1FBQ25ELE9BQU8sSUFBSSxDQUFDLEdBQUcsRUFBRTtZQUNmLE1BQU0sR0FBRyxhQUFhLENBQUMsTUFBTSxDQUFDLE1BQU0sQ0FBQyxDQUFDO1lBQ3RDLE1BQU0sUUFBUSxHQUFHLElBQUksUUFBUSxFQUFFLENBQUM7WUFDaEMsS0FBSyxJQUFJLENBQUMsR0FBRyxDQUFDLEVBQUUsQ0FBQyxHQUFHLElBQUksQ0FBQyxNQUFNLENBQUMsTUFBTSxFQUFFLEVBQUUsQ0FBQyxFQUFFO2dCQUMzQyxRQUFRLENBQUMsR0FBRyxDQUFDLElBQUksQ0FBQyxNQUFNLENBQUMsQ0FBQyxDQUFDLEVBQUUsTUFBTSxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUM7YUFDekM7WUFDRCxPQUFPLE9BQU8sQ0FBQyxJQUFJLENBQUMsT0FBTyxFQUFFLFFBQVEsRUFBRSxNQUFNLENBQXNCLENBQUM7UUFDdEUsQ0FBQyxDQUFDLENBQUM7SUFDTCxDQUFDO0lBRUQ7Ozs7Ozs7O09BUUc7SUFDTSxXQUFXLENBQUMsTUFBdUIsRUFBRSxJQUFzQjtRQUVsRSxPQUFPLElBQUksQ0FBQyxHQUFHLEVBQUU7WUFDZixNQUFNLEdBQUcsYUFBYSxDQUFDLE1BQU0sQ0FBQyxNQUFNLENBQUMsQ0FBQztZQUN0QyxJQUFJLEtBQWUsQ0FBQztZQUNwQixJQUFJLElBQUksSUFBSSxJQUFJLEVBQUU7Z0JBQ2hCLEtBQUssR0FBRyxhQUFhLENBQUMsWUFBWSxDQUFDLElBQUksRUFBRSxNQUFNLENBQUMsTUFBTSxDQUFDLENBQUM7YUFDekQ7aUJBQU07Z0JBQ0wsS0FBSyxHQUFHLGFBQWEsQ0FBQyxNQUFNLENBQUMsSUFBSSxDQUFDLENBQUM7YUFDcEM7WUFDRCxvREFBb0Q7WUFDcEQsT0FBTyxJQUFJLENBQUMsZ0JBQWdCLENBQUMsTUFBTSxFQUFFLEtBQUssQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDO1FBQ2pELENBQUMsQ0FBQyxDQUFDO0lBQ0wsQ0FBQztJQUVEOzs7Ozs7OztPQVFHO0lBQ00sa0JBQWtCLENBQUMsVUFBeUI7UUFDbkQsTUFBTSxXQUFXLEdBQUcsV0FBVyxDQUFDLGtCQUFrQixDQUFDLFVBQVUsQ0FBQyxDQUFDO1FBQy9ELElBQUksV0FBVyxDQUFDLE1BQU0sS0FBSyxJQUFJLENBQUMsV0FBVyxDQUFDLE1BQU0sRUFBRTtZQUNsRCxNQUFNLElBQUksVUFBVSxDQUNoQiwrQkFBK0IsVUFBVSxJQUFJO2dCQUM3QyxhQUFhLElBQUksQ0FBQyxXQUFXLENBQUMsTUFBTSxpQkFBaUIsQ0FBQyxDQUFDO1NBQzVEO1FBRUQsa0NBQWtDO1FBQ2xDLE1BQU0sb0JBQW9CLEdBQWdDLEVBQUUsQ0FBQztRQUM3RCxLQUFLLElBQUksQ0FBQyxHQUFHLENBQUMsRUFBRSxDQUFDLEdBQUcsV0FBVyxDQUFDLE1BQU0sRUFBRSxDQUFDLEVBQUUsRUFBRTtZQUMzQyxNQUFNLEtBQUssR0FBRyxJQUFJLENBQUMsV0FBVyxDQUFDLENBQUMsQ0FBQyxDQUFDO1lBQ2xDLE1BQU0sVUFBVSxHQUFHLFdBQVcsQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUNsQyx1REFBdUQ7WUFDdkQsb0RBQW9EO1lBQ3BELE1BQU0sUUFBUSxHQUFHLEtBQUssQ0FBQyxJQUFJLEdBQUcsTUFBTSxDQUFDO1lBQ3JDLG9CQUFvQixDQUFDLFFBQVEsQ0FBQyxHQUFHLFVBQVUsQ0FBQztTQUM3QztRQUVELE1BQU0sU0FBUyxHQUFHLE1BQU0sQ0FBQyxJQUFJLENBQUMsSUFBSSxDQUFDLFlBQVksQ0FBQzthQUN6QixHQUFHLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxRQUFRLENBQUMsQ0FBQyxFQUFFLEVBQUUsQ0FBQyxDQUFDO2FBQ3pCLElBQUksQ0FBQyxhQUFhLENBQUMsb0JBQW9CLENBQUMsQ0FBQztRQUNoRSxzQ0FBc0M7UUFDdEMsSUFBSSxTQUFTLENBQUMsTUFBTSxHQUFHLENBQUMsRUFBRTtZQUN4QixLQUFLLE1BQU0sS0FBSyxJQUFJLFNBQVMsRUFBRTtnQkFDN0IsTUFBTSxLQUFLLEdBQUcsSUFBSSxDQUFDLFlBQVksQ0FBQyxLQUFLLENBQUMsQ0FBQztnQkFDdkMsS0FBSyxNQUFNLElBQUksSUFBSSxLQUFLLEVBQUU7b0JBQ3hCLCtDQUErQztvQkFDL0MsTUFBTSxLQUFLLEdBQUcsSUFBSSxDQUFDLGFBQWEsQ0FBQztvQkFDakMsSUFBSSxJQUFJLENBQUMsV0FBVyxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsRUFBRSxDQUFDLENBQUMsQ0FBQyxFQUFFLENBQUMsQ0FBQyxPQUFPLENBQUMsS0FBSyxDQUFDLEVBQUUsQ0FBQyxLQUFLLENBQUMsQ0FBQyxFQUFFO3dCQUM1RCw0REFBNEQ7d0JBQzVELFNBQVM7cUJBQ1Y7b0JBQ0QsOERBQThEO29CQUM5RCxNQUFNLFdBQVcsR0FBWSxFQUFFLENBQUM7b0JBQ2hDLEtBQUssSUFBSSxDQUFDLEdBQUcsQ0FBQyxFQUFFLENBQUMsR0FBRyxJQUFJLENBQUMsYUFBYSxDQUFDLE1BQU0sRUFBRSxDQUFDLEVBQUUsRUFBRTt3QkFDbEQsTUFBTSxZQUFZLEdBQUcsSUFBSSxDQUFDLGFBQWEsQ0FBQyxDQUFDLENBQUMsQ0FBQzt3QkFDM0MsTUFBTSxTQUFTLEdBQUcsSUFBSSxDQUFDLFdBQVcsQ0FBQyxDQUFDLENBQUMsQ0FBQzt3QkFDdEMsTUFBTSxXQUFXLEdBQUcsSUFBSSxDQUFDLGFBQWEsQ0FBQyxDQUFDLENBQUMsQ0FBQzt3QkFDMUMsTUFBTSxRQUFRLEdBQUcsR0FBRyxZQUFZLENBQUMsSUFBSSxJQUFJLFNBQVMsSUFBSSxXQUFXLEVBQUUsQ0FBQzt3QkFDcEUsTUFBTSxVQUFVLEdBQUcsb0JBQW9CLENBQUMsUUFBUSxDQUFDLENBQUM7d0JBQ2xELFdBQVcsQ0FBQyxJQUFJLENBQUMsVUFBVSxDQUFDLENBQUM7cUJBQzlCO29CQUVELE1BQU0sV0FBVyxHQUFHLEtBQUssQ0FBQyxrQkFBa0IsQ0FDeEMsYUFBYSxDQUFDLGdCQUFnQixDQUFDLFdBQVcsQ0FBQyxDQUFDLENBQUM7b0JBRWpELE1BQU0sWUFBWSxHQUFHLFdBQVcsQ0FBQyxrQkFBa0IsQ0FBQyxXQUFXLENBQUMsQ0FBQztvQkFDakUsTUFBTSxTQUFTLEdBQUcsS0FBSyxDQUFDLFlBQVksQ0FBQyxPQUFPLENBQUMsSUFBSSxDQUFDLENBQUM7b0JBQ25ELEtBQUssSUFBSSxDQUFDLEdBQUcsQ0FBQyxFQUFFLENBQUMsR0FBRyxZQUFZLENBQUMsTUFBTSxFQUFFLENBQUMsRUFBRSxFQUFFO3dCQUM1QyxNQUFNLFFBQVEsR0FBRyxHQUFHLEtBQUssQ0FBQyxJQUFJLElBQUksU0FBUyxJQUFJLENBQUMsRUFBRSxDQUFDO3dCQUNuRCxvQkFBb0IsQ0FBQyxRQUFRLENBQUMsR0FBRyxZQUFZLENBQUMsQ0FBQyxDQUFDLENBQUM7cUJBQ2xEO2lCQUNGO2FBQ0Y7U0FDRjtRQUVELHNEQUFzRDtRQUN0RCxNQUFNLFlBQVksR0FBWSxFQUFFLENBQUM7UUFDakMsTUFBTSxlQUFlLEdBQWEsRUFBRSxDQUFDO1FBQ3JDLEtBQUssSUFBSSxDQUFDLEdBQUcsQ0FBQyxFQUFFLENBQUMsR0FBRyxJQUFJLENBQUMsWUFBWSxDQUFDLE1BQU0sRUFBRSxDQUFDLEVBQUUsRUFBRTtZQUNqRCxNQUFNLEtBQUssR0FBRyxJQUFJLENBQUMsWUFBWSxDQUFDLENBQUMsQ0FBQyxDQUFDO1lBQ25DLE1BQU0sU0FBUyxHQUFHLElBQUksQ0FBQyx1QkFBdUIsQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUNsRCxNQUFNLFdBQVcsR0FBRyxJQUFJLENBQUMseUJBQXlCLENBQUMsQ0FBQyxDQUFDLENBQUM7WUFDdEQsTUFBTSxRQUFRLEdBQUcsR0FBRyxLQUFLLENBQUMsSUFBSSxJQUFJLFNBQVMsSUFBSSxXQUFXLEVBQUUsQ0FBQztZQUM3RCxlQUFlLENBQUMsSUFBSSxDQUFDLFFBQVEsQ0FBQyxDQUFDO1NBQ2hDO1FBRUQsS0FBSyxJQUFJLENBQUMsR0FBRyxDQUFDLEVBQUUsQ0FBQyxHQUFHLGVBQWUsQ0FBQyxNQUFNLEVBQUUsQ0FBQyxFQUFFLEVBQUU7WUFDL0MsTUFBTSxHQUFHLEdBQUcsZUFBZSxDQUFDLENBQUMsQ0FBQyxDQUFDO1lBQy9CLGFBQWEsQ0FBQyxNQUFNLENBQUMsR0FBRyxJQUFJLG9CQUFvQixDQUFDLENBQUM7WUFDbEQsWUFBWSxDQUFDLElBQUksQ0FBQyxvQkFBb0IsQ0FBQyxHQUFHLENBQUMsQ0FBQyxDQUFDO1NBQzlDO1FBRUQsbUNBQW1DO1FBQ25DLE9BQU8sYUFBYSxDQUFDLGdCQUFnQixDQUFDLFlBQVksQ0FBQyxDQUFDO0lBQ3RELENBQUM7SUFFRDs7Ozs7Ozs7O09BU0c7SUFDTyxnQkFBZ0IsQ0FBQyxNQUFnQixFQUFFLEtBQWdCO1FBRTNELElBQUksS0FBSyxJQUFJLElBQUksRUFBRTtZQUNqQixLQUFLLEdBQUcsYUFBYSxDQUFDLFlBQVksQ0FBQyxJQUFJLEVBQUUsTUFBTSxDQUFDLE1BQU0sQ0FBQyxDQUFDO1NBQ3pEO1FBRUQsaURBQWlEO1FBQ2pELGtDQUFrQztRQUNsQyw4Q0FBOEM7UUFDOUMscURBQXFEO1FBQ3JELGlEQUFpRDtRQUNqRCxNQUFNLFNBQVMsR0FBMkMsRUFBRSxDQUFDO1FBQzdELEtBQUssSUFBSSxDQUFDLEdBQUcsQ0FBQyxFQUFFLENBQUMsR0FBRyxJQUFJLENBQUMsTUFBTSxDQUFDLE1BQU0sRUFBRSxFQUFFLENBQUMsRUFBRTtZQUMzQyxNQUFNLENBQUMsR0FBRyxJQUFJLENBQUMsTUFBTSxDQUFDLENBQUMsQ0FBQyxDQUFDO1lBQ3pCLE1BQU0sQ0FBQyxHQUFHLE1BQU0sQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUNwQixNQUFNLElBQUksR0FBRyxLQUFLLENBQUMsQ0FBQyxDQUFDLENBQUM7WUFDdEIsU0FBUyxDQUFDLENBQUMsQ0FBQyxFQUFFLENBQUMsR0FBRyxDQUFDLENBQUMsRUFBRSxJQUFJLENBQUMsQ0FBQztTQUM3QjtRQUVELE1BQU0sU0FBUyxHQUFHLE1BQU0sQ0FBQyxJQUFJLENBQUMsSUFBSSxDQUFDLFlBQVksQ0FBQzthQUN6QixHQUFHLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxRQUFRLENBQUMsQ0FBQyxFQUFFLEVBQUUsQ0FBQyxDQUFDO2FBQ3pCLElBQUksQ0FBQyxhQUFhLENBQUMsb0JBQW9CLENBQUMsQ0FBQztRQUNoRSxLQUFLLE1BQU0sS0FBSyxJQUFJLFNBQVMsRUFBRTtZQUM3QixNQUFNLEtBQUssR0FBRyxJQUFJLENBQUMsWUFBWSxDQUFDLEtBQUssQ0FBQyxDQUFDO1lBQ3ZDLEtBQUssTUFBTSxJQUFJLElBQUksS0FBSyxFQUFFO2dCQUN4QiwrQ0FBK0M7Z0JBQy9DLE1BQU0sS0FBSyxHQUFHLElBQUksQ0FBQyxhQUFhLENBQUM7Z0JBQ2pDLE1BQU0scUJBQXFCLEdBQUcsSUFBSSxDQUFDLFlBQVksQ0FBQztnQkFDaEQsTUFBTSxzQkFBc0IsR0FBRyxJQUFJLENBQUMsYUFBYSxDQUFDO2dCQUVsRCw0REFBNEQ7Z0JBQzVELHVDQUF1QztnQkFDdkMsZ0NBQWdDO2dCQUNoQyxNQUFNLFlBQVksR0FBRyxJQUFJLEtBQUssRUFBb0IsQ0FBQztnQkFDbkQsS0FBSyxNQUFNLENBQUMsSUFBSSxxQkFBcUIsRUFBRTtvQkFDckMsSUFBSSxDQUFDLENBQUMsRUFBRSxJQUFJLFNBQVMsRUFBRTt3QkFDckIsWUFBWSxDQUFDLElBQUksQ0FBQyxTQUFTLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUM7cUJBQ3BDO2lCQUNGO2dCQUNELElBQUksWUFBWSxDQUFDLE1BQU0sS0FBSyxxQkFBcUIsQ0FBQyxNQUFNLEVBQUU7b0JBQ3hELDREQUE0RDtvQkFDNUQsSUFBSSxNQUFNLEdBQVcsRUFBRSxDQUFDO29CQUN4QixJQUFJLGVBQXlCLENBQUM7b0JBQzlCLElBQUksYUFBdUIsQ0FBQztvQkFDNUIsSUFBSSxhQUF1QixDQUFDO29CQUM1QixJQUFJLFdBQXFCLENBQUM7b0JBQzFCLGFBQWE7b0JBQ2IsSUFBSSxJQUFJLENBQUMsUUFBUSxJQUFJLElBQUksRUFBRTt3QkFDekIsTUFBTSxHQUFHLElBQUksQ0FBQyxRQUFRLENBQUM7cUJBQ3hCO29CQUNELElBQUksWUFBWSxDQUFDLE1BQU0sS0FBSyxDQUFDLEVBQUU7d0JBQzdCLE1BQU0sQ0FBQyxjQUFjLEVBQUUsWUFBWSxDQUFDLEdBQUcsWUFBWSxDQUFDLENBQUMsQ0FBQyxDQUFDO3dCQUN2RCxJQUFJLE1BQU0sQ0FBQyxNQUFNLENBQUMsSUFBSSxJQUFJLEVBQUU7NEJBQzFCLE1BQU0sQ0FBQyxNQUFNLENBQUMsR0FBRyxZQUFZLENBQUM7eUJBQy9CO3dCQUNELGFBQWE7NEJBQ1QsYUFBYSxDQUFDLE1BQU0sQ0FBQyxLQUFLLENBQUMsSUFBSSxDQUFDLGNBQWMsRUFBRSxNQUFNLENBQUMsQ0FBQyxDQUFDO3dCQUM3RCxXQUFXLEdBQUcsYUFBYSxDQUFDLE1BQU0sQ0FDOUIsS0FBSyxDQUFDLFdBQVcsQ0FBQyxjQUFjLEVBQUUsWUFBWSxDQUFDLENBQUMsQ0FBQzt3QkFDckQsZUFBZSxHQUFHLENBQUMsY0FBYyxDQUFDLENBQUM7d0JBQ25DLGFBQWEsR0FBRyxDQUFDLFlBQVksQ0FBQyxDQUFDO3FCQUNoQzt5QkFBTTt3QkFDTCxlQUFlLEdBQUcsWUFBWSxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsRUFBRSxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDO3dCQUM5QyxhQUFhLEdBQUcsWUFBWSxDQUFDLEdBQUcsQ0FBQyxDQUFDLENBQUMsRUFBRSxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDO3dCQUM1QyxJQUFJLE1BQU0sQ0FBQyxNQUFNLENBQUMsSUFBSSxJQUFJLEVBQUU7NEJBQzFCLE1BQU0sQ0FBQyxNQUFNLENBQUMsR0FBRyxhQUFhLENBQUM7eUJBQ2hDO3dCQUNELGFBQWE7NEJBQ1QsYUFBYSxDQUFDLE1BQU0sQ0FBQyxLQUFLLENBQUMsSUFBSSxDQUFDLGVBQWUsRUFBRSxNQUFNLENBQUMsQ0FBQyxDQUFDO3dCQUM5RCxXQUFXLEdBQUcsYUFBYSxDQUFDLE1BQU0sQ0FDOUIsS0FBSyxDQUFDLFdBQVcsQ0FBQyxlQUFlLEVBQUUsYUFBYSxDQUFDLENBQUMsQ0FBQztxQkFDeEQ7b0JBRUQsSUFBSSxLQUFLLENBQUMsbUJBQW1CLEVBQUU7d0JBQzdCLE1BQU0sSUFBSSxtQkFBbUIsQ0FDekIsOERBQThEOzRCQUM5RCwyREFBMkQsQ0FBQyxDQUFDO3FCQUNsRTtvQkFDRCxtREFBbUQ7b0JBRW5ELHFCQUFxQjtvQkFDckIsS0FBSyxJQUFJLENBQUMsR0FBRyxDQUFDLEVBQUUsQ0FBQyxHQUFHLHNCQUFzQixDQUFDLE1BQU0sRUFBRSxFQUFFLENBQUMsRUFBRTt3QkFDdEQsTUFBTSxDQUFDLEdBQUcsc0JBQXNCLENBQUMsQ0FBQyxDQUFDLENBQUM7d0JBQ3BDLE1BQU0sQ0FBQyxHQUFHLGFBQWEsQ0FBQyxDQUFDLENBQUMsQ0FBQzt3QkFDM0IsTUFBTSxJQUFJLEdBQUcsV0FBVyxDQUFDLENBQUMsQ0FBQyxDQUFDO3dCQUM1QixTQUFTLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxHQUFHLENBQUMsQ0FBQyxFQUFFLElBQUksQ0FBQyxDQUFDO3FCQUM3QjtpQkFDRjthQUNGO1NBQ0Y7UUFFRCxNQUFNLGFBQWEsR0FBYSxFQUFFLENBQUM7UUFDbkMsTUFBTSxXQUFXLEdBQWEsRUFBRSxDQUFDO1FBQ2pDLE1BQU0sWUFBWSxHQUFZLEVBQUUsQ0FBQztRQUNqQyxLQUFLLE1BQU0sQ0FBQyxJQUFJLElBQUksQ0FBQyxPQUFPLEVBQUU7WUFDNUIsYUFBYSxDQUFDLE1BQU0sQ0FDaEIsQ0FBQyxDQUFDLEVBQUUsSUFBSSxTQUFTLEVBQUUsNEJBQTRCLENBQUMsQ0FBQyxJQUFJLE1BQU0sQ0FBQyxDQUFDLEVBQUUsRUFBRSxDQUFDLENBQUM7WUFDdkUsTUFBTSxDQUFDLE1BQU0sRUFBRSxJQUFJLENBQUMsR0FBRyxTQUFTLENBQUMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxDQUFDO1lBQ3ZDLFlBQVksQ0FBQyxJQUFJLENBQUMsTUFBTSxDQUFDLEtBQUssQ0FBQyxDQUFDO1lBQ2hDLGFBQWEsQ0FBQyxJQUFJLENBQUMsTUFBTSxDQUFDLENBQUM7WUFDM0IsV0FBVyxDQUFDLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQztTQUN4QjtRQUVELDhDQUE4QztRQUM5QyxPQUFPLENBQUMsYUFBYSxFQUFFLFdBQVcsRUFBRSxZQUFZLENBQUMsQ0FBQztJQUNwRCxDQUFDO0lBRUQ7Ozs7Ozs7T0FPRztJQUNLLHNCQUFzQixDQUFDLE1BQWU7UUFDNUMsTUFBTSxpQkFBaUIsR0FBZ0MsRUFBRSxDQUFDO1FBQzFELElBQUksU0FBaUIsQ0FBQztRQUN0QixLQUFLLE1BQU0sS0FBSyxJQUFJLElBQUksQ0FBQyxNQUFNLEVBQUU7WUFDL0IsU0FBUyxHQUFHLEtBQUssWUFBWSxTQUFTLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDLENBQUMsQ0FBQyxDQUFDO1lBQy9DLEtBQUssSUFBSSxpQkFBaUIsR0FBRyxDQUFDLEVBQ3pCLGlCQUFpQixHQUFHLEtBQUssQ0FBQyxZQUFZLENBQUMsTUFBTSxFQUFFLGlCQUFpQixFQUFFLEVBQUU7Z0JBQ3ZFLE1BQU0sT0FBTyxHQUFHLFNBQVMsQ0FBQyxPQUFPLENBQUMsS0FBSyxFQUFFLGlCQUFpQixDQUFDLENBQUM7Z0JBQzVELElBQUksSUFBSSxDQUFDLGNBQWMsQ0FBQyxHQUFHLENBQUMsT0FBTyxDQUFDLEVBQUU7b0JBQ3BDLDhCQUE4QjtvQkFDOUIsaUJBQWlCLENBQUMsT0FBTyxDQUFDLEdBQUcsU0FBUyxDQUFDO29CQUN2QyxTQUFTLElBQUksQ0FBQyxDQUFDO2lCQUNoQjthQUNGO1NBQ0Y7UUFDRCxPQUFPLGlCQUFpQixDQUFDO0lBQzNCLENBQUM7SUF3QkQsUUFBUSxDQUFDLFdBQTJCLEVBQUUsS0FBYztRQUNsRCxJQUFJLEtBQUssSUFBSSxJQUFJLEVBQUU7WUFDakIsT0FBTyxJQUFJLENBQUMsU0FBUyxDQUFDLEtBQUssQ0FBQyxDQUFDO1NBQzlCO2FBQU07WUFDTCxJQUFJLFdBQVcsSUFBSSxJQUFJLEVBQUU7Z0JBQ3ZCLE1BQU0sSUFBSSxVQUFVLENBQUMsNENBQTRDLENBQUMsQ0FBQzthQUNwRTtZQUNELElBQUksT0FBTyxXQUFXLEtBQUssUUFBUSxFQUFFO2dCQUNuQyxPQUFPLElBQUksQ0FBQyxTQUFTLENBQUMsV0FBVyxDQUFDLENBQUM7YUFDcEM7U0FDRjtRQUVELEtBQUssTUFBTSxLQUFLLElBQUksSUFBSSxDQUFDLE1BQU0sRUFBRTtZQUMvQixJQUFJLEtBQUssQ0FBQyxJQUFJLEtBQUssV0FBVyxFQUFFO2dCQUM5QixPQUFPLEtBQUssQ0FBQzthQUNkO1NBQ0Y7UUFDRCxNQUFNLElBQUksVUFBVSxDQUFDLGtCQUFrQixXQUFXLEVBQUUsQ0FBQyxDQUFDO0lBQ3hELENBQUM7SUFFRCxTQUFTLENBQUMsS0FBYTtRQUNyQixJQUFJLElBQUksQ0FBQyxNQUFNLENBQUMsTUFBTSxJQUFJLEtBQUssRUFBRTtZQUMvQixNQUFNLElBQUksVUFBVSxDQUNoQix3Q0FBd0MsS0FBSyxtQkFBbUI7Z0JBQ2hFLE9BQU8sSUFBSSxDQUFDLE1BQU0sQ0FBQyxNQUFNLFlBQVksQ0FBQyxDQUFDO1NBQzVDO2FBQU07WUFDTCxPQUFPLElBQUksQ0FBQyxNQUFNLENBQUMsS0FBSyxDQUFDLENBQUM7U0FDM0I7SUFDSCxDQUFDO0lBRUQ7Ozs7T0FJRztJQUNNLGVBQWU7UUFDdEIsc0VBQXNFO1FBQ3RFLHlFQUF5RTtRQUN6RSx5RUFBeUU7UUFDekUsd0JBQXdCO1FBQ3hCLE9BQU8sSUFBSSxDQUFDLEdBQUcsRUFBRTtZQUNmLE1BQU0sTUFBTSxHQUFhLEVBQUUsQ0FBQztZQUM1QixLQUFLLE1BQU0sS0FBSyxJQUFJLElBQUksQ0FBQyxNQUFNLEVBQUU7Z0JBQy9CLEtBQUssSUFBSSxTQUFTLEdBQUcsQ0FBQyxFQUFFLFNBQVMsR0FBRyxLQUFLLENBQUMsWUFBWSxDQUFDLE1BQU0sRUFDeEQsRUFBRSxTQUFTLEVBQUU7b0JBQ2hCLE1BQU0sT0FBTyxHQUFHLFNBQVMsQ0FBQyxPQUFPLENBQUMsS0FBSyxFQUFFLFNBQVMsQ0FBQyxDQUFDO29CQUNwRCxJQUFJLElBQUksQ0FBQyxjQUFjLENBQUMsR0FBRyxDQUFDLE9BQU8sQ0FBQyxFQUFFO3dCQUNwQyxNQUFNLENBQUMsSUFBSSxDQUFDLEdBQUcsS0FBSyxDQUFDLGVBQWUsRUFBRSxDQUFDLENBQUM7cUJBQ3pDO2lCQUNGO2FBQ0Y7WUFDRCx3REFBd0Q7WUFDeEQsT0FBTyxNQUFNLENBQUM7UUFDaEIsQ0FBQyxDQUFDLENBQUM7SUFDTCxDQUFDO0lBRVEsU0FBUztRQUNoQixNQUFNLE1BQU0sR0FBNkIsRUFBQyxJQUFJLEVBQUUsSUFBSSxDQUFDLElBQUksRUFBQyxDQUFDO1FBRTNELHNEQUFzRDtRQUN0RCwwREFBMEQ7UUFDMUQsMkNBQTJDO1FBQzNDLE1BQU0saUJBQWlCLEdBQ25CLElBQUksQ0FBQyxzQkFBc0IsQ0FBQyxJQUFJLENBQUMsTUFBTSxDQUFDLENBQUM7UUFFN0MsZ0RBQWdEO1FBQ2hELE1BQU0sWUFBWSxHQUFHLEVBQUUsQ0FBQztRQUN4QixLQUFLLE1BQU0sS0FBSyxJQUFJLElBQUksQ0FBQyxNQUFNLEVBQUU7WUFDL0IsTUFBTSxjQUFjLEdBQUcsS0FBSyxDQUFDLFlBQVksRUFBRSxDQUFDO1lBQzVDLE1BQU0sV0FBVyxHQUFHLEtBQUssQ0FBQyxTQUFTLEVBQUUsQ0FBQztZQUN0QyxNQUFNLG9CQUFvQixHQUFHLEVBQUUsQ0FBQztZQUNoQyxLQUFLLElBQUksaUJBQWlCLEdBQUcsQ0FBQyxFQUN6QixpQkFBaUIsR0FBRyxLQUFLLENBQUMsWUFBWSxDQUFDLE1BQU0sRUFBRSxpQkFBaUIsRUFBRSxFQUFFO2dCQUN2RSxNQUFNLElBQUksR0FBRyxLQUFLLENBQUMsWUFBWSxDQUFDLGlCQUFpQixDQUFDLENBQUM7Z0JBQ25ELE1BQU0sT0FBTyxHQUFHLFNBQVMsQ0FBQyxPQUFPLENBQUMsS0FBSyxFQUFFLGlCQUFpQixDQUFDLENBQUM7Z0JBQzVELElBQUksTUFBTSxHQUFHLEVBQUUsQ0FBQztnQkFDaEIsSUFBSSxJQUFJLENBQUMsY0FBYyxDQUFDLEdBQUcsQ0FBQyxPQUFPLENBQUMsRUFBRTtvQkFDcEMscUNBQXFDO29CQUNyQywrQkFBK0I7b0JBQy9CLElBQUksSUFBSSxDQUFDLFFBQVEsRUFBRTt3QkFDakIsSUFBSTs0QkFDRixJQUFJLENBQUMsU0FBUyxDQUFDLElBQUksQ0FBQyxRQUFRLENBQUMsQ0FBQzs0QkFDOUIsTUFBTSxHQUFHLElBQUksQ0FBQyxRQUFRLENBQUM7eUJBQ3hCO3dCQUFDLE9BQU8sR0FBRyxFQUFFOzRCQUNaLE9BQU8sQ0FBQyxJQUFJLENBQ1IsU0FBUyxLQUFLLENBQUMsSUFBSSxjQUFjO2dDQUNqQyxzQ0FBc0M7Z0NBQ3RDLEdBQUcsSUFBSSxDQUFDLFFBQVEsOEJBQThCO2dDQUM5Qyw0Q0FBNEM7Z0NBQzVDLG1DQUFtQyxDQUFDLENBQUM7NEJBQ3pDLE1BQU0sR0FBRyxFQUFFLENBQUM7eUJBQ2I7cUJBQ0Y7b0JBQ0QsSUFBSSxJQUFJLENBQUMsYUFBYSxDQUFDLE1BQU0sR0FBRyxDQUFDLEVBQUU7d0JBQ2pDLE1BQU0sUUFBUSxHQUFHLEVBQUUsQ0FBQzt3QkFDcEIsS0FBSyxJQUFJLENBQUMsR0FBRyxDQUFDLEVBQUUsQ0FBQyxHQUFHLElBQUksQ0FBQyxhQUFhLENBQUMsTUFBTSxFQUFFLENBQUMsRUFBRSxFQUFFOzRCQUNsRCxNQUFNLFlBQVksR0FBRyxJQUFJLENBQUMsYUFBYSxDQUFDLENBQUMsQ0FBQyxDQUFDOzRCQUMzQyxNQUFNLFNBQVMsR0FBRyxJQUFJLENBQUMsV0FBVyxDQUFDLENBQUMsQ0FBQyxDQUFDOzRCQUN0QyxNQUFNLFdBQVcsR0FBRyxJQUFJLENBQUMsYUFBYSxDQUFDLENBQUMsQ0FBQyxDQUFDOzRCQUMxQyxNQUFNLE9BQU8sR0FBRyxTQUFTLENBQUMsT0FBTyxDQUFDLFlBQVksRUFBRSxTQUFTLENBQUMsQ0FBQzs0QkFDM0QsSUFBSSxZQUFZLEdBQUcsaUJBQWlCLENBQUMsT0FBTyxDQUFDLENBQUM7NEJBQzlDLElBQUksWUFBWSxJQUFJLElBQUksRUFBRTtnQ0FDeEIsWUFBWSxHQUFHLENBQUMsQ0FBQzs2QkFDbEI7NEJBQ0QsUUFBUSxDQUFDLElBQUksQ0FDVCxDQUFDLFlBQVksQ0FBQyxJQUFJLEVBQUUsWUFBWSxFQUFFLFdBQVcsRUFBRSxNQUFNLENBQUMsQ0FBQyxDQUFDO3lCQUM3RDt3QkFDRCxvQkFBb0IsQ0FBQyxJQUFJLENBQUMsUUFBUSxDQUFDLENBQUM7cUJBQ3JDO2lCQUNGO2FBQ0Y7WUFDRCxNQUFNLElBQUksR0FBNkIsRUFBRSxDQUFDO1lBQzFDLElBQUksQ0FBQyxNQUFNLENBQUMsR0FBRyxLQUFLLENBQUMsSUFBSSxDQUFDO1lBQzFCLElBQUksQ0FBQyxXQUFXLENBQUMsR0FBRyxjQUFjLENBQUM7WUFDbkMsSUFBSSxDQUFDLFFBQVEsQ0FBQyxHQUFHLFdBQVcsQ0FBQztZQUM3QixJQUFJLENBQUMsY0FBYyxDQUFDLEdBQUcsb0JBQW9CLENBQUM7WUFDNUMsWUFBWSxDQUFDLElBQUksQ0FBQyxJQUFJLENBQUMsQ0FBQztTQUN6QjtRQUNELE1BQU0sQ0FBQyxRQUFRLENBQUMsR0FBRyxZQUFZLENBQUM7UUFDaEMsdUNBQXVDO1FBQ3ZDLE1BQU0sV0FBVyxHQUFHLEVBQUUsQ0FBQztRQUN2QixLQUFLLElBQUksQ0FBQyxHQUFHLENBQUMsRUFBRSxDQUFDLEdBQUcsSUFBSSxDQUFDLFdBQVcsQ0FBQyxNQUFNLEVBQUUsQ0FBQyxFQUFFLEVBQUU7WUFDaEQsTUFBTSxLQUFLLEdBQUcsSUFBSSxDQUFDLFdBQVcsQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUNsQyxNQUFNLFNBQVMsR0FBRyxJQUFJLENBQUMsc0JBQXNCLENBQUMsQ0FBQyxDQUFDLENBQUM7WUFFakQsTUFBTSxPQUFPLEdBQUcsU0FBUyxDQUFDLE9BQU8sQ0FBQyxLQUFLLEVBQUUsU0FBUyxDQUFDLENBQUM7WUFDcEQsSUFBSSxDQUFDLElBQUksQ0FBQyxjQUFjLENBQUMsR0FBRyxDQUFDLE9BQU8sQ0FBQyxFQUFFO2dCQUNyQyxTQUFTO2FBQ1Y7WUFDRCxJQUFJLFlBQVksR0FBRyxpQkFBaUIsQ0FBQyxPQUFPLENBQUMsQ0FBQztZQUM5QyxJQUFJLFlBQVksS0FBSyxJQUFJLElBQUksWUFBWSxLQUFLLFNBQVMsRUFBRTtnQkFDdkQsWUFBWSxHQUFHLENBQUMsQ0FBQzthQUNsQjtZQUNELE1BQU0sV0FBVyxHQUFHLElBQUksQ0FBQyx3QkFBd0IsQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUNyRCxXQUFXLENBQUMsSUFBSSxDQUFDLENBQUMsS0FBSyxDQUFDLElBQUksRUFBRSxZQUFZLEVBQUUsV0FBVyxDQUFDLENBQUMsQ0FBQztTQUMzRDtRQUNELE1BQU0sQ0FBQyxhQUFhLENBQUMsR0FBRyxXQUFXLENBQUM7UUFFcEMsTUFBTSxZQUFZLEdBQUcsRUFBRSxDQUFDO1FBQ3hCLEtBQUssSUFBSSxDQUFDLEdBQUcsQ0FBQyxFQUFFLENBQUMsR0FBRyxJQUFJLENBQUMsWUFBWSxDQUFDLE1BQU0sRUFBRSxDQUFDLEVBQUUsRUFBRTtZQUNqRCxNQUFNLEtBQUssR0FBRyxJQUFJLENBQUMsWUFBWSxDQUFDLENBQUMsQ0FBQyxDQUFDO1lBQ25DLE1BQU0sU0FBUyxHQUFHLElBQUksQ0FBQyx1QkFBdUIsQ0FBQyxDQUFDLENBQUMsQ0FBQztZQUVsRCxNQUFNLE9BQU8sR0FBRyxTQUFTLENBQUMsT0FBTyxDQUFDLEtBQUssRUFBRSxTQUFTLENBQUMsQ0FBQztZQUNwRCxJQUFJLENBQUMsSUFBSSxDQUFDLGNBQWMsQ0FBQyxHQUFHLENBQUMsT0FBTyxDQUFDLEVBQUU7Z0JBQ3JDLFNBQVM7YUFDVjtZQUNELElBQUksWUFBWSxHQUFHLGlCQUFpQixDQUFDLE9BQU8sQ0FBQyxDQUFDO1lBQzlDLElBQUksWUFBWSxLQUFLLElBQUksSUFBSSxZQUFZLEtBQUssU0FBUyxFQUFFO2dCQUN2RCxZQUFZLEdBQUcsQ0FBQyxDQUFDO2FBQ2xCO1lBQ0QsTUFBTSxXQUFXLEdBQUcsSUFBSSxDQUFDLHlCQUF5QixDQUFDLENBQUMsQ0FBQyxDQUFDO1lBQ3RELFlBQVksQ0FBQyxJQUFJLENBQUMsQ0FBQyxLQUFLLENBQUMsSUFBSSxFQUFFLFlBQVksRUFBRSxXQUFXLENBQUMsQ0FBQyxDQUFDO1NBQzVEO1FBQ0QsTUFBTSxDQUFDLGNBQWMsQ0FBQyxHQUFHLFlBQVksQ0FBQztRQUN0QyxPQUFPLE1BQU0sQ0FBQztJQUNoQixDQUFDO0lBRUQ7Ozs7Ozs7Ozs7O09BV0c7SUFDSCxrQkFBa0I7SUFDbEIsTUFBTSxDQUFVLFVBQVUsQ0FDdEIsR0FBNkMsRUFDN0MsTUFBZ0MsRUFDaEMsZ0JBQWdCLEVBQThCLEVBQzlDLGNBQWMsR0FBRyxLQUFLO1FBQ3hCLGlDQUFpQztRQUNqQyxtQ0FBbUM7UUFDbkMsTUFBTSxhQUFhLEdBQWlDLEVBQUUsQ0FBQztRQUV2RCx3Q0FBd0M7UUFDeEMseUNBQXlDO1FBQ3pDLG9EQUFvRDtRQUNwRCxxREFBcUQ7UUFDckQsd0RBQXdEO1FBQ3hELE1BQU0sZ0JBQWdCLEdBQWtELEVBQUUsQ0FBQztRQUMzRSxTQUFTLGtCQUFrQixDQUN2QixLQUFZLEVBQUUsUUFBa0M7WUFDbEQsSUFBSSxDQUFDLENBQUMsS0FBSyxDQUFDLElBQUksSUFBSSxnQkFBZ0IsQ0FBQyxFQUFFO2dCQUNyQyxnQkFBZ0IsQ0FBQyxLQUFLLENBQUMsSUFBSSxDQUFDLEdBQUcsQ0FBQyxRQUFRLENBQUMsQ0FBQzthQUMzQztpQkFBTTtnQkFDTCxnQkFBZ0IsQ0FBQyxLQUFLLENBQUMsSUFBSSxDQUFDLENBQUMsSUFBSSxDQUFDLFFBQVEsQ0FBQyxDQUFDO2FBQzdDO1FBQ0gsQ0FBQztRQUVELFNBQVMsV0FBVyxDQUFDLEtBQVksRUFBRSxRQUFrQztZQUNuRSxNQUFNLFlBQVksR0FBcUIsRUFBRSxDQUFDO1lBQzFDLElBQUksTUFBTSxDQUFDO1lBQ1gsS0FBSyxNQUFNLFNBQVMsSUFBSSxRQUFRLEVBQUU7Z0JBQ2hDLE1BQU0sZ0JBQWdCLEdBQUcsU0FBUyxDQUFDLENBQUMsQ0FBQyxDQUFDO2dCQUN0QyxNQUFNLGdCQUFnQixHQUFHLFNBQVMsQ0FBQyxDQUFDLENBQUMsQ0FBQztnQkFDdEMsTUFBTSxrQkFBa0IsR0FBRyxTQUFTLENBQUMsQ0FBQyxDQUFDLENBQUM7Z0JBRXhDLE1BQU0sR0FBRyxTQUFTLENBQUMsQ0FBQyxDQUFDLElBQUksSUFBSSxDQUFDLENBQUM7b0JBQzNCLEVBQUUsQ0FBQyxDQUFDO29CQUNKLFNBQVMsQ0FBQyxDQUFDLENBQTZCLENBQUM7Z0JBQzdDLElBQUksQ0FBQyxDQUFDLGdCQUFnQixJQUFJLGFBQWEsQ0FBQyxFQUFFO29CQUN4QyxrQkFBa0IsQ0FBQyxLQUFLLEVBQUUsUUFBUSxDQUFDLENBQUM7b0JBQ3BDLE9BQU87aUJBQ1I7Z0JBQ0QsTUFBTSxZQUFZLEdBQUcsYUFBYSxDQUFDLGdCQUFnQixDQUFDLENBQUM7Z0JBQ3JELElBQUksWUFBWSxDQUFDLFlBQVksQ0FBQyxNQUFNLElBQUksZ0JBQWdCLEVBQUU7b0JBQ3hELGtCQUFrQixDQUFDLEtBQUssRUFBRSxRQUFRLENBQUMsQ0FBQztvQkFDcEMsT0FBTztpQkFDUjtnQkFDRCxNQUFNLFdBQVcsR0FBRyxZQUFZLENBQUMsWUFBWSxDQUFDLGdCQUFnQixDQUFDLENBQUM7Z0JBQ2hFLFlBQVksQ0FBQyxJQUFJLENBQUMsV0FBVyxDQUFDLGFBQWEsQ0FBQyxrQkFBa0IsQ0FBQyxDQUFDLENBQUM7YUFDbEU7WUFDRCxtREFBbUQ7WUFDbkQsb0NBQW9DO1lBQ3BDLDhDQUE4QztZQUM5QyxJQUFJLFlBQVksQ0FBQyxNQUFNLEdBQUcsQ0FBQyxFQUFFO2dCQUMzQixLQUFLLENBQUMsS0FBSyxDQUNQLGFBQWEsQ0FBQyxnQkFBZ0IsQ0FBQyxZQUFZLENBQUMsRUFDNUMsTUFBTSxDQUFDLENBQUMsQ0FBRSxnQkFBZ0I7YUFDL0I7UUFDSCxDQUFDO1FBRUQ7Ozs7O1dBS0c7UUFDSCxTQUFTLFlBQVksQ0FBQyxTQUF3QztZQUM1RCxNQUFNLFNBQVMsR0FBRyxTQUFTLENBQUMsTUFBTSxDQUFXLENBQUM7WUFDOUMscUJBQXFCO1lBQ3JCLE1BQU0sS0FBSyxHQUNQLGdCQUFnQixDQUNaLFNBQVMsRUFDVCxNQUFNLENBQUMsZUFBZSxDQUFDLElBQUksSUFBSSxDQUFDLENBQUM7Z0JBQzdCLE1BQU0sQ0FBQyxlQUFlLENBQTZCLENBQUMsQ0FBQztnQkFDckQsRUFBRSxDQUFVLENBQUM7WUFDekIsS0FBSyxDQUFDLDRCQUE0QixDQUFDLGNBQWMsQ0FBQyxDQUFDO1lBQ25ELGFBQWEsQ0FBQyxTQUFTLENBQUMsR0FBRyxLQUFLLENBQUM7WUFDakMsdUJBQXVCO1lBQ3ZCLE1BQU0sZ0JBQWdCLEdBQ2xCLFNBQVMsQ0FBQyxjQUFjLENBQStCLENBQUM7WUFDNUQsZ0JBQWdCLENBQUMsT0FBTyxDQUFDLFFBQVEsQ0FBQyxFQUFFO2dCQUNsQyxJQUFJLENBQUMsQ0FBQyxRQUFRLFlBQVksS0FBSyxDQUFDLEVBQUU7b0JBQ2hDLE1BQU0sSUFBSSxVQUFVLENBQ2hCLHlEQUNJLFFBQVEsRUFBRSxDQUFDLENBQUM7aUJBQ3JCO2dCQUNELGlEQUFpRDtnQkFDakQseURBQXlEO2dCQUN6RCwwREFBMEQ7Z0JBQzFELHNDQUFzQztnQkFDdEMsa0JBQWtCLENBQUMsS0FBSyxFQUFFLFFBQVEsQ0FBQyxDQUFDO1lBQ3RDLENBQUMsQ0FBQyxDQUFDO1FBQ0wsQ0FBQztRQUVELGlFQUFpRTtRQUNqRSxNQUFNLElBQUksR0FBRyxNQUFNLENBQUMsTUFBTSxDQUFDLENBQUM7UUFDNUIsTUFBTSxnQkFBZ0IsR0FBRyxNQUFNLENBQUMsUUFBUSxDQUErQixDQUFDO1FBQ3hFLEtBQUssTUFBTSxTQUFTLElBQUksZ0JBQWdCLEVBQUU7WUFDeEMsWUFBWSxDQUFDLFNBQVMsQ0FBQyxDQUFDO1NBQ3pCO1FBRUQsaURBQWlEO1FBQ2pELHlEQUF5RDtRQUN6RCx5REFBeUQ7UUFDekQsNkNBQTZDO1FBQzdDLE9BQU8sQ0FBQyxhQUFhLENBQUMsYUFBYSxDQUFDLGdCQUFnQixDQUFDLEVBQUU7WUFDckQsS0FBSyxNQUFNLFNBQVMsSUFBSSxnQkFBZ0IsRUFBRTtnQkFDeEMsTUFBTSxLQUFLLEdBQUcsYUFBYSxDQUFDLFNBQVMsQ0FBQyxNQUFNLENBQVcsQ0FBQyxDQUFDO2dCQUN6RCxJQUFJLEtBQUssQ0FBQyxJQUFJLElBQUksZ0JBQWdCLEVBQUU7b0JBQ2xDLE1BQU0sK0JBQStCLEdBQUcsZ0JBQWdCLENBQUMsS0FBSyxDQUFDLElBQUksQ0FBQyxDQUFDO29CQUNyRSxPQUFPLGdCQUFnQixDQUFDLEtBQUssQ0FBQyxJQUFJLENBQUMsQ0FBQztvQkFDcEMsS0FBSyxNQUFNLFFBQVEsSUFBSSwrQkFBK0IsRUFBRTt3QkFDdEQsV0FBVyxDQUFDLEtBQUssRUFBRSxRQUFRLENBQUMsQ0FBQztxQkFDOUI7aUJBQ0Y7YUFDRjtTQUNGO1FBRUQsTUFBTSxZQUFZLEdBQXFCLEVBQUUsQ0FBQztRQUMxQyxNQUFNLGFBQWEsR0FBcUIsRUFBRSxDQUFDO1FBQzNDLE1BQU0scUJBQXFCLEdBQ3ZCLE1BQU0sQ0FBQyxhQUFhLENBQStCLENBQUM7UUFDeEQsS0FBSyxNQUFNLFNBQVMsSUFBSSxxQkFBcUIsRUFBRTtZQUM3QyxNQUFNLFNBQVMsR0FBRyxTQUFTLENBQUMsQ0FBQyxDQUFXLENBQUM7WUFDekMsTUFBTSxTQUFTLEdBQUcsU0FBUyxDQUFDLENBQUMsQ0FBVyxDQUFDO1lBQ3pDLE1BQU0sV0FBVyxHQUFHLFNBQVMsQ0FBQyxDQUFDLENBQVcsQ0FBQztZQUMzQyxhQUFhLENBQUMsTUFBTSxDQUFDLFNBQVMsSUFBSSxhQUFhLENBQUMsQ0FBQztZQUNqRCxNQUFNLEtBQUssR0FBRyxhQUFhLENBQUMsU0FBUyxDQUFDLENBQUM7WUFDdkMsTUFBTSxrQkFBa0IsR0FBRyxLQUFLLENBQUMsWUFBWSxDQUFDLFNBQVMsQ0FBQyxDQUFDLGFBQWEsQ0FBQztZQUN2RSxZQUFZLENBQUMsSUFBSSxDQUFDLGtCQUFrQixDQUFDLFdBQVcsQ0FBQyxDQUFDLENBQUM7U0FDcEQ7UUFDRCxNQUFNLHNCQUFzQixHQUN4QixNQUFNLENBQUMsY0FBYyxDQUErQixDQUFDO1FBQ3pELEtBQUssTUFBTSxTQUFTLElBQUksc0JBQXNCLEVBQUU7WUFDOUMsTUFBTSxTQUFTLEdBQUcsU0FBUyxDQUFDLENBQUMsQ0FBVyxDQUFDO1lBQ3pDLE1BQU0sU0FBUyxHQUFHLFNBQVMsQ0FBQyxDQUFDLENBQVcsQ0FBQztZQUN6QyxNQUFNLFdBQVcsR0FBRyxTQUFTLENBQUMsQ0FBQyxDQUFXLENBQUM7WUFDM0MsYUFBYSxDQUFDLE1BQU0sQ0FBQyxTQUFTLElBQUksYUFBYSxDQUFDLENBQUM7WUFDakQsTUFBTSxLQUFLLEdBQUcsYUFBYSxDQUFDLFNBQVMsQ0FBQyxDQUFDO1lBQ3ZDLE1BQU0sa0JBQWtCLEdBQUcsS0FBSyxDQUFDLFlBQVksQ0FBQyxTQUFTLENBQUMsQ0FBQyxhQUFhLENBQUM7WUFDdkUsYUFBYSxDQUFDLElBQUksQ0FBQyxrQkFBa0IsQ0FBQyxXQUFXLENBQUMsQ0FBQyxDQUFDO1NBQ3JEO1FBQ0QsT0FBTyxJQUFJLEdBQUcsQ0FBQyxFQUFDLE1BQU0sRUFBRSxZQUFZLEVBQUUsT0FBTyxFQUFFLGFBQWEsRUFBRSxJQUFJLEVBQUMsQ0FBQyxDQUFDO0lBQ3ZFLENBQUM7SUFFRDs7Ozs7T0FLRztJQUNILElBQWEsUUFBUTtRQUNuQixvRUFBb0U7UUFDcEUsa0RBQWtEO1FBQ2xELElBQUksSUFBSSxDQUFDLFNBQVMsRUFBRTtZQUNsQixNQUFNLElBQUksVUFBVSxDQUNoQiw0REFBNEQ7Z0JBQzVELDZEQUE2RDtnQkFDN0QsaUVBQWlFLENBQUMsQ0FBQztTQUN4RTtRQUNELEtBQUssTUFBTSxLQUFLLElBQUksSUFBSSxDQUFDLE1BQU0sRUFBRTtZQUMvQixJQUFJLEtBQUssQ0FBQyxRQUFRLEVBQUU7Z0JBQ2xCLE9BQU8sSUFBSSxDQUFDO2FBQ2I7U0FDRjtRQUNELE9BQU8sS0FBSyxDQUFDO0lBQ2YsQ0FBQztJQUVEOzs7OztPQUtHO0lBQ00sV0FBVztRQUNsQixJQUFJLENBQUMsR0FBRyxFQUFFO1lBQ1IsSUFBSSxDQUFDLE1BQU0sQ0FBQyxPQUFPLENBQUMsS0FBSyxDQUFDLEVBQUU7Z0JBQzFCLHdCQUF3QjtnQkFDeEIsSUFBSSxLQUFLLENBQUMsUUFBUSxFQUFFO29CQUNsQixLQUFLLENBQUMsV0FBVyxFQUFFLENBQUM7aUJBQ3JCO2dCQUNELHVCQUF1QjtZQUN6QixDQUFDLENBQUMsQ0FBQztRQUNMLENBQUMsQ0FBQyxDQUFDO0lBQ0wsQ0FBQztDQUNGIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMTggR29vZ2xlIExMQ1xuICpcbiAqIFVzZSBvZiB0aGlzIHNvdXJjZSBjb2RlIGlzIGdvdmVybmVkIGJ5IGFuIE1JVC1zdHlsZVxuICogbGljZW5zZSB0aGF0IGNhbiBiZSBmb3VuZCBpbiB0aGUgTElDRU5TRSBmaWxlIG9yIGF0XG4gKiBodHRwczovL29wZW5zb3VyY2Uub3JnL2xpY2Vuc2VzL01JVC5cbiAqID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XG4gKi9cblxuLyogT3JpZ2luYWwgc291cmNlOiBrZXJhcy9lbmdpbmUvdG9wb2xvZ3kucHkgKi9cblxuaW1wb3J0IHtOYW1lZFRlbnNvck1hcCwgU2NhbGFyLCBzZXJpYWxpemF0aW9uLCBUZW5zb3IsIHRpZHl9IGZyb20gJ0B0ZW5zb3JmbG93L3RmanMtY29yZSc7XG5cbmltcG9ydCB7Z2V0VWlkfSBmcm9tICcuLi9iYWNrZW5kL3N0YXRlJztcbmltcG9ydCB7Tm90SW1wbGVtZW50ZWRFcnJvciwgUnVudGltZUVycm9yLCBWYWx1ZUVycm9yfSBmcm9tICcuLi9lcnJvcnMnO1xuaW1wb3J0IHtTaGFwZX0gZnJvbSAnLi4va2VyYXNfZm9ybWF0L2NvbW1vbic7XG5pbXBvcnQge1RlbnNvcktleVdpdGhBcmdzQXJyYXl9IGZyb20gJy4uL2tlcmFzX2Zvcm1hdC9ub2RlX2NvbmZpZyc7XG5pbXBvcnQge1B5SnNvbkRpY3R9IGZyb20gJy4uL2tlcmFzX2Zvcm1hdC90eXBlcyc7XG5pbXBvcnQge2Rlc2VyaWFsaXplIGFzIGRlc2VyaWFsaXplTGF5ZXJ9IGZyb20gJy4uL2xheWVycy9zZXJpYWxpemF0aW9uJztcbmltcG9ydCB7S3dhcmdzfSBmcm9tICcuLi90eXBlcyc7XG5pbXBvcnQgKiBhcyBnZW5lcmljX3V0aWxzIGZyb20gJy4uL3V0aWxzL2dlbmVyaWNfdXRpbHMnO1xuaW1wb3J0IHtjb252ZXJ0VHNUb1B5dGhvbmljfSBmcm9tICcuLi91dGlscy9zZXJpYWxpemF0aW9uX3V0aWxzJztcbmltcG9ydCAqIGFzIHR5cGVzX3V0aWxzIGZyb20gJy4uL3V0aWxzL3R5cGVzX3V0aWxzJztcbmltcG9ydCB7YmF0Y2hTZXRWYWx1ZSwgTGF5ZXJWYXJpYWJsZX0gZnJvbSAnLi4vdmFyaWFibGVzJztcbmltcG9ydCB7dmVyc2lvbiBhcyBsYXllcnNWZXJzaW9ufSBmcm9tICcuLi92ZXJzaW9uJztcblxuaW1wb3J0IHtleGVjdXRlLCBGZWVkRGljdH0gZnJvbSAnLi9leGVjdXRvcic7XG5pbXBvcnQge0lucHV0TGF5ZXJ9IGZyb20gJy4vaW5wdXRfbGF5ZXInO1xuaW1wb3J0IHtEaXNwb3NlUmVzdWx0LCBMYXllciwgTm9kZSwgU3ltYm9saWNUZW5zb3J9IGZyb20gJy4vdG9wb2xvZ3knO1xuXG4vKiogQ29uc3RydWN0b3IgY29uZmlnIGZvciBDb250YWluZXIuICovXG5leHBvcnQgaW50ZXJmYWNlIENvbnRhaW5lckFyZ3Mge1xuICBpbnB1dHM6IFN5bWJvbGljVGVuc29yfFN5bWJvbGljVGVuc29yW107XG4gIG91dHB1dHM6IFN5bWJvbGljVGVuc29yfFN5bWJvbGljVGVuc29yW107XG4gIG5hbWU/OiBzdHJpbmc7XG59XG5cbi8qKlxuICogQSBDb250YWluZXIgaXMgYSBkaXJlY3RlZCBhY3ljbGljIGdyYXBoIG9mIGxheWVycy5cbiAqXG4gKiBJdCBpcyB0aGUgdG9wb2xvZ2ljYWwgZm9ybSBvZiBhIFwibW9kZWxcIi4gQSBMYXllcnNNb2RlbFxuICogaXMgc2ltcGx5IGEgQ29udGFpbmVyIHdpdGggYWRkZWQgdHJhaW5pbmcgcm91dGluZXMuXG4gKlxuICovXG5leHBvcnQgYWJzdHJhY3QgY2xhc3MgQ29udGFpbmVyIGV4dGVuZHMgTGF5ZXIge1xuICBpbnB1dHM6IFN5bWJvbGljVGVuc29yW107XG4gIG91dHB1dHM6IFN5bWJvbGljVGVuc29yW107XG5cbiAgaW5wdXRMYXllcnM6IExheWVyW107XG4gIGlucHV0TGF5ZXJzTm9kZUluZGljZXM6IG51bWJlcltdO1xuICBpbnB1dExheWVyc1RlbnNvckluZGljZXM6IG51bWJlcltdO1xuXG4gIG91dHB1dExheWVyczogTGF5ZXJbXTtcbiAgb3V0cHV0TGF5ZXJzTm9kZUluZGljZXM6IG51bWJlcltdO1xuICBvdXRwdXRMYXllcnNUZW5zb3JJbmRpY2VzOiBudW1iZXJbXTtcblxuICBsYXllcnM6IExheWVyW107XG4gIGxheWVyc0J5RGVwdGg6IHtbZGVwdGg6IHN0cmluZ106IExheWVyW119O1xuICBub2Rlc0J5RGVwdGg6IHtbZGVwdGg6IHN0cmluZ106IE5vZGVbXX07XG5cbiAgaW50ZXJuYWxDb250YWluZXJSZWZzOiBDb250YWluZXJbXTtcblxuICBjb250YWluZXJOb2RlcyA9IG5ldyBTZXQ8c3RyaW5nPigpO1xuXG4gIC8vIFRPRE8obWljaGFlbHRlcnJ5KTogQWRkIGNhY2hlIHN1cHBvcnRcbiAgLy8gcHJpdmF0ZSBvdXRwdXRNYXNrQ2FjaGU6IGFueTtcbiAgLy8gcHJpdmF0ZSBvdXRwdXRUZW5zb3JDYWNoZTogYW55O1xuICAvLyBwcml2YXRlIG91dHB1dFNoYXBlQ2FjaGU6IGFueTtcblxuICBpbnB1dE5hbWVzOiBzdHJpbmdbXTtcbiAgb3V0cHV0TmFtZXM6IHN0cmluZ1tdO1xuICBmZWVkSW5wdXRTaGFwZXM6IFNoYXBlW107XG5cbiAgcHJvdGVjdGVkIGludGVybmFsSW5wdXRTaGFwZXM6IFNoYXBlW107XG4gIHByb3RlY3RlZCBpbnRlcm5hbE91dHB1dFNoYXBlczogU2hhcGVbXTtcbiAgLy8gVE9ETyhjYWlzKTogTWF5YmUgJ2ZlZWQnIHNob3VsZCBub3QgaW4gdGhlIG5hbWVzIG9mIHRoZXNlIHZhcmlhYmxlcyxcbiAgLy8gICBkdWUgdG8gdGhlIGZhY3QgdGhhdCBvdXIgYmFja2VuZCBpcyBub3Qgc3ltYm9saWMuXG4gIHByb3RlY3RlZCBmZWVkSW5wdXROYW1lczogc3RyaW5nW107XG4gIHByb3RlY3RlZCBmZWVkT3V0cHV0TmFtZXM6IHN0cmluZ1tdO1xuXG4gIGNvbnN0cnVjdG9yKGFyZ3M6IENvbnRhaW5lckFyZ3MpIHtcbiAgICAvLyBObyBhcmdzIHBhc3NlZCB0byBzdXBlcidzIGNvbnN0cnVjdG9yLlxuICAgIHN1cGVyKHt9KTtcbiAgICB0aGlzLm5hbWUgPSBhcmdzLm5hbWU7XG4gICAgaWYgKHRoaXMubmFtZSA9PSBudWxsKSB7XG4gICAgICBjb25zdCBwcmVmaXggPSB0aGlzLmdldENsYXNzTmFtZSgpLnRvTG93ZXJDYXNlKCk7XG4gICAgICB0aGlzLm5hbWUgPSBnZXRVaWQocHJlZml4KTtcbiAgICB9XG5cbiAgICB0aGlzLnN1cHBvcnRzTWFza2luZyA9IGZhbHNlO1xuICAgIHRoaXMudHJhaW5hYmxlXyA9IHRydWU7XG5cbiAgICAvLyBUT0RPKG1pY2hhZWx0ZXJyeSk6IEluaXRpYWxpemUgcGVySW5wdXRMb3NzZXMvVXBkYXRlcyBoZXJlLlxuXG4gICAgLy8gQ29udGFpbmVyLXNwZWNpZmljIHByb3BlcnRpZXMuXG4gICAgaWYgKEFycmF5LmlzQXJyYXkoYXJncy5pbnB1dHMpKSB7XG4gICAgICB0aGlzLmlucHV0cyA9IGFyZ3MuaW5wdXRzLnNsaWNlKCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHRoaXMuaW5wdXRzID0gW2FyZ3MuaW5wdXRzXTtcbiAgICB9XG4gICAgaWYgKEFycmF5LmlzQXJyYXkoYXJncy5vdXRwdXRzKSkge1xuICAgICAgdGhpcy5vdXRwdXRzID0gYXJncy5vdXRwdXRzLnNsaWNlKCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHRoaXMub3V0cHV0cyA9IFthcmdzLm91dHB1dHNdO1xuICAgIH1cblxuICAgIC8vIENoZWNrIGZvciByZWR1bmRhbmN5IGluIGlucHV0cy5cbiAgICBpZiAoZ2VuZXJpY191dGlscy51bmlxdWUodGhpcy5pbnB1dHMpLmxlbmd0aCAhPT0gdGhpcy5pbnB1dHMubGVuZ3RoKSB7XG4gICAgICB0aHJvdyBuZXcgVmFsdWVFcnJvcihcbiAgICAgICAgICAnVGhlIGxpc3Qgb2YgaW5wdXRzIHBhc3NlZCB0byB0aGUgbW9kZWwgaXMgJyArXG4gICAgICAgICAgJ3JlZHVuZGFudC4gQWxsIGlucHV0cyBzaG91bGQgb25seSBhcHBlYXIgb25jZS4gRm91bmQ6ICcgK1xuICAgICAgICAgIGAke3RoaXMuaW5wdXRzLm1hcCh4ID0+IHgubmFtZSl9YCk7XG4gICAgfVxuXG4gICAgLy8gQ2hlY2sgZm9yIHJlZHVuZGFuY3kgaW4gb3V0cHV0cy5cbiAgICBpZiAoZ2VuZXJpY191dGlscy51bmlxdWUodGhpcy5vdXRwdXRzKS5sZW5ndGggIT09IHRoaXMub3V0cHV0cy5sZW5ndGgpIHtcbiAgICAgIGNvbnNvbGUud2FybihcbiAgICAgICAgICAnVGhlIGxpc3Qgb2Ygb3V0cHV0cyBwYXNzZWQgdG8gdGhlIG1vZGVsIGlzIHJlZHVuZGFudC4gJyArXG4gICAgICAgICAgJ0FsbCBvdXRwdXRzIHNob3VsZCBvbmx5IGFwcGVhciBvbmNlLiBGb3VuZDogJyArXG4gICAgICAgICAgYCR7dGhpcy5vdXRwdXRzLm1hcCh4ID0+IHgubmFtZSl9YCk7XG4gICAgfVxuXG4gICAgLypcbiAgICAgIExpc3Qgb2YgaW5pdGlhbCBsYXllcnMgKDEgdG8gMSBtYXBwaW5nIHdpdGggdGhpcy5pbnB1dHMsIGhlbmNlIHRoZSBzYW1lXG4gICAgICBsYXllciBtaWdodCBhcHBlYXIgdHdpY2UpXG4gICAgKi9cbiAgICB0aGlzLmlucHV0TGF5ZXJzID0gW107XG4gICAgdGhpcy5pbnB1dExheWVyc05vZGVJbmRpY2VzID0gW107XG4gICAgdGhpcy5pbnB1dExheWVyc1RlbnNvckluZGljZXMgPSBbXTtcbiAgICAvKlxuICAgICAgTGlzdCBvZiBsYXllcnMgKDEgdG8gMSBtYXBwaW5nIHdpdGggdGhpcy5vdXRwdXRzLCBoZW5jZSB0aGUgc2FtZSBsYXllclxuICAgICAgbWlnaHQgYXBwZWFyIHR3aWNlKVxuICAgICovXG4gICAgdGhpcy5vdXRwdXRMYXllcnMgPSBbXTtcbiAgICB0aGlzLm91dHB1dExheWVyc05vZGVJbmRpY2VzID0gW107XG4gICAgdGhpcy5vdXRwdXRMYXllcnNUZW5zb3JJbmRpY2VzID0gW107XG4gICAgLypcbiAgICAgIEFsbCBsYXllcnMgaW4gb3JkZXIgb2YgaG9yaXpvbnRhbCBncmFwaCB0cmF2ZXJzYWwuIEVudHJpZXMgYXJlIHVuaXF1ZS5cbiAgICAgIEluY2x1ZGVzIGlucHV0IGFuZCBvdXRwdXQgbGF5ZXJzLlxuICAgICovXG4gICAgdGhpcy5sYXllcnMgPSBbXTtcblxuICAgIC8qXG4gICAgICBSZWZlcmVuY2VzIHRvIGNvbnRhaW5lciBsYXllcnMgdGhhdCB3ZXJlIGNvbnN0cnVjdGVkIGludGVybmFsbHkuIFdlIG5lZWRcbiAgICAgIHRoZXNlIHRvIHByb3Blcmx5IGRpc3Bvc2Ugb2YgdGVuc29ycyBmcm9tIG5lc3RlZCBjb250YWluZXJzLlxuICAgICovXG4gICAgdGhpcy5pbnRlcm5hbENvbnRhaW5lclJlZnMgPSBbXTtcblxuICAgIC8vIFRPRE8obWljaGFlbHRlcnJ5KTogRGV0ZXJtaW5lIGlmIGNhY2hpbmcgc3RpbGwgbmVlZGVkIHdpdGggZWFnZXJcbiAgICAvLyBiYWNrZW5kLlxuICAgIC8qXG4gICAgICBUaGlzIGlzIGZvciBwZXJmb3JtYW5jZSBvcHRpbWl6YXRpb24gd2hlbiBjYWxsaW5nIHRoZSBDb250YWluZXIgb24gbmV3XG4gICAgICBpbnB1dHMuIEV2ZXJ5IHRpbWUgdGhlIENvbnRhaW5lciBpcyBjYWxsZWQgb24gYSBzZXQgb24gaW5wdXQgdGVuc29ycyxcbiAgICAgIHdlIGNvbXB1dGUgdGhlIG91dHB1dCB0ZW5zb3JzLCBvdXRwdXQgbWFza3MgYW5kIG91dHB1dCBzaGFwZXMgaW4gb25lIHBhc3MsXG4gICAgICB0aGVuIGNhY2hlIHRoZW0gaGVyZS4gV2hlbiBvbmUgb2YgdGhlc2Ugb3V0cHV0cyBpcyBxdWVyaWVkIGxhdGVyLFxuICAgICAgd2UgcmV0cmlldmUgaXQgZnJvbSB0aGVyZSBpbnN0ZWFkIG9mIHJlY29tcHV0aW5nIGl0LlxuICAgICovXG4gICAgLy8gdGhpcy5vdXRwdXRUZW5zb3JDYWNoZSA9IHt9O1xuICAgIC8vIHRoaXMub3V0cHV0U2hhcGVDYWNoZSA9IHt9O1xuXG4gICAgLy8gQnVpbGQgdGhpcy5vdXRwdXRMYXllcnM6XG4gICAgZm9yIChjb25zdCB4IG9mIHRoaXMub3V0cHV0cykge1xuICAgICAgY29uc3QgbGF5ZXIgPSB4LnNvdXJjZUxheWVyO1xuICAgICAgY29uc3Qgbm9kZUluZGV4ID0geC5ub2RlSW5kZXg7XG4gICAgICBjb25zdCB0ZW5zb3JJbmRleCA9IHgudGVuc29ySW5kZXg7XG4gICAgICB0aGlzLm91dHB1dExheWVycy5wdXNoKGxheWVyKTtcbiAgICAgIHRoaXMub3V0cHV0TGF5ZXJzTm9kZUluZGljZXMucHVzaChub2RlSW5kZXgpO1xuICAgICAgdGhpcy5vdXRwdXRMYXllcnNUZW5zb3JJbmRpY2VzLnB1c2godGVuc29ySW5kZXgpO1xuICAgIH1cblxuICAgIC8vIFRPRE8obWljaGFlbHRlcnJ5KTogQWRkIG91dHB1dCBtYXNrIGNhY2hlIGNvZGUuXG5cbiAgICAvLyBCdWlsZCB0aGlzLmlucHV0TGF5ZXJzOlxuICAgIGZvciAoY29uc3QgeCBvZiB0aGlzLmlucHV0cykge1xuICAgICAgY29uc3QgbGF5ZXIgPSB4LnNvdXJjZUxheWVyO1xuICAgICAgY29uc3Qgbm9kZUluZGV4ID0geC5ub2RlSW5kZXg7XG4gICAgICBjb25zdCB0ZW5zb3JJbmRleCA9IHgudGVuc29ySW5kZXg7XG4gICAgICAvKlxuICAgICAgICBJdCdzIHN1cHBvc2VkIHRvIGJlIGFuIGlucHV0IGxheWVyLCBzbyBvbmx5IG9uZSBub2RlXG4gICAgICAgIGFuZCBvbmUgdGVuc29yIG91dHB1dC5cbiAgICAgICovXG4gICAgICBnZW5lcmljX3V0aWxzLmFzc2VydChub2RlSW5kZXggPT09IDAsICdpbnB1dCBsYXllciBoYXMgPjEgbm9kZXMnKTtcbiAgICAgIGdlbmVyaWNfdXRpbHMuYXNzZXJ0KHRlbnNvckluZGV4ID09PSAwLCAnaW5wdXQgbGF5ZXIgaGFzID4xIHRlbnNvcnMnKTtcbiAgICAgIHRoaXMuaW5wdXRMYXllcnMucHVzaChsYXllcik7XG4gICAgICB0aGlzLmlucHV0TGF5ZXJzTm9kZUluZGljZXMucHVzaChub2RlSW5kZXgpO1xuICAgICAgdGhpcy5pbnB1dExheWVyc1RlbnNvckluZGljZXMucHVzaCh0ZW5zb3JJbmRleCk7XG4gICAgfVxuXG4gICAgLy8gQnVpbGQgdGhpcy5pbnB1dE5hbWVzIGFuZCB0aGlzLm91dHB1dE5hbWVzLlxuICAgIHRoaXMuaW5wdXROYW1lcyA9IFtdO1xuICAgIHRoaXMub3V0cHV0TmFtZXMgPSBbXTtcbiAgICB0aGlzLmZlZWRJbnB1dFNoYXBlcyA9IFtdO1xuICAgIHRoaXMuZmVlZElucHV0TmFtZXMgPSBbXTtcbiAgICB0aGlzLmZlZWRPdXRwdXROYW1lcyA9IFtdO1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgdGhpcy5pbnB1dExheWVycy5sZW5ndGg7IGkrKykge1xuICAgICAgY29uc3QgbGF5ZXIgPSB0aGlzLmlucHV0TGF5ZXJzW2ldO1xuICAgICAgLy8gQ2hlY2sgdGhhdCBsYXllciBpcyBhbiBJbnB1dExheWVyLlxuICAgICAgaWYgKCEobGF5ZXIgaW5zdGFuY2VvZiBJbnB1dExheWVyKSkge1xuICAgICAgICB0aHJvdyBuZXcgVHlwZUVycm9yKFxuICAgICAgICAgICAgJ0lucHV0IGxheWVycyB0byBhIExheWVyc01vZGVsIG11c3QgYmUgSW5wdXRMYXllciBvYmplY3RzLiAnICtcbiAgICAgICAgICAgIGBSZWNlaXZlZCBpbnB1dHM6ICR7YXJncy5pbnB1dHN9LiBgICtcbiAgICAgICAgICAgIGBJbnB1dCAke2l9ICgwLWJhc2VkKSBvcmlnaW5hdGVzIGAgK1xuICAgICAgICAgICAgYGZyb20gbGF5ZXIgdHlwZSAke2xheWVyLmdldENsYXNzTmFtZSgpfS5gKTtcbiAgICAgIH1cbiAgICAgIHRoaXMuaW5wdXROYW1lcy5wdXNoKGxheWVyLm5hbWUpO1xuICAgICAgdGhpcy5mZWVkSW5wdXRTaGFwZXMucHVzaChsYXllci5iYXRjaElucHV0U2hhcGUpO1xuXG4gICAgICB0aGlzLmZlZWRJbnB1dE5hbWVzLnB1c2gobGF5ZXIubmFtZSk7XG4gICAgfVxuICAgIGZvciAoY29uc3QgbGF5ZXIgb2YgdGhpcy5vdXRwdXRMYXllcnMpIHtcbiAgICAgIHRoaXMub3V0cHV0TmFtZXMucHVzaChsYXllci5uYW1lKTtcbiAgICB9XG5cbiAgICB0aGlzLmludGVybmFsSW5wdXRTaGFwZXMgPSB0aGlzLmlucHV0cy5tYXAoeCA9PiB4LnNoYXBlKTtcbiAgICB0aGlzLmludGVybmFsT3V0cHV0U2hhcGVzID0gdGhpcy5vdXRwdXRzLm1hcCh4ID0+IHguc2hhcGUpO1xuXG4gICAgLypcbiAgICAgIENvbnRhaW5lcl9ub2Rlczogc2V0IG9mIG5vZGVzIGluY2x1ZGVkIGluIHRoZSBncmFwaCAobm90IGFsbCBub2Rlc1xuICAgICAgaW5jbHVkZWQgaW4gdGhlIGxheWVycyBhcmUgcmVsZXZhbnQgdG8gdGhlIGN1cnJlbnQgZ3JhcGgpLlxuICAgICovXG4gICAgLy8gaWRzIG9mIGFsbCBub2RlcyByZWxldmFudCB0byB0aGUgQ29udGFpbmVyOlxuICAgIGNvbnN0IG5vZGVzRGVwdGhzOiB7W25vZGVJRDogc3RyaW5nXTogbnVtYmVyfSA9IHt9O1xuICAgIC8vIFRvIHJlY292ZXIgbm9kZXMgZnJvbSB0aGVpciBJRC5cbiAgICBjb25zdCBub2RlSURUb05vZGU6IHtbbm9kZUlEOiBzdHJpbmddOiBOb2RlfSA9IHt9O1xuICAgIGNvbnN0IGxheWVyc0RlcHRoczoge1tsYXllcklEOiBzdHJpbmddOiBudW1iZXJ9ID0ge307XG4gICAgLy8gVG8gbGF5ZXJzIGZyb20gdGhlaXIgSUQuXG4gICAgY29uc3QgbGF5ZXJJRFRvTGF5ZXI6IHtbbGF5ZXJJRDogc3RyaW5nXTogTGF5ZXJ9ID0ge307XG4gICAgY29uc3QgbGF5ZXJJbmRpY2VzOiB7W2xheWVySUQ6IHN0cmluZ106IG51bWJlcn0gPSB7fTtcbiAgICBjb25zdCBub2Rlc0luRGVjcmVhc2luZ0RlcHRoOiBOb2RlW10gPSBbXTtcblxuICAgIC8qKlxuICAgICAqIEJ1aWxkcyBhIG1hcCBvZiB0aGUgZ3JhcGggb2YgbGF5ZXJzLlxuICAgICAqXG4gICAgICogVGhpcyByZWN1cnNpdmVseSB1cGRhdGVzIHRoZSBtYXAgYGxheWVySW5kaWNlc2AsXG4gICAgICogdGhlIGxpc3QgYG5vZGVzSW5EZWNyZWFzaW5nRGVwdGhgIGFuZCB0aGUgc2V0IGBjb250YWluZXJOb2Rlc2AuXG4gICAgICpcbiAgICAgKiBAcGFyYW0gdGVuc29yIFNvbWUgdGVuc29yIGluIGEgZ3JhcGguXG4gICAgICogQHBhcmFtIGZpbmlzaGVkTm9kZXMgU2V0IG9mIG5vZGVzIHdob3NlIHN1YmdyYXBocyBoYXZlIGJlZW4gdHJhdmVyc2VkXG4gICAgICogICAgICAgICBjb21wbGV0ZWx5LiBVc2VmdWwgdG8gcHJldmVudCBkdXBsaWNhdGVkIHdvcmsuXG4gICAgICogQHBhcmFtIG5vZGVzSW5Qcm9ncmVzcyBTZXQgb2Ygbm9kZXMgdGhhdCBhcmUgY3VycmVudGx5IGFjdGl2ZSBvbiB0aGVcbiAgICAgKiAgICAgICAgIHJlY3Vyc2lvbiBzdGFjay4gVXNlZnVsIHRvIGRldGVjdCBjeWNsZXMuXG4gICAgICogQHBhcmFtIGxheWVyIExheWVyIGZyb20gd2hpY2ggYHRlbnNvcmAgY29tZXMgZnJvbS4gSWYgbm90IHByb3ZpZGVkLFxuICAgICAqICAgd2lsbCBiZSBvYnRhaW5lZCBmcm9tIHRlbnNvci5zb3VyY2VMYXllci5cbiAgICAgKiBAcGFyYW0gbm9kZUluZGV4IE5vZGUgaW5kZXggZnJvbSB3aGljaCBgdGVuc29yYCBjb21lcyBmcm9tLlxuICAgICAqIEBwYXJhbSB0ZW5zb3JJbmRleCBUZW5zb3JJbmRleCBmcm9tIHdoaWNoIGB0ZW5zb3JgIGNvbWVzIGZyb20uXG4gICAgICpcbiAgICAgKiBAZXhjZXB0aW9uIFJ1bnRpbWVFcnJvciBpZiBhIGN5Y2xlIGlzIGRldGVjdGVkLlxuICAgICAqL1xuICAgIGNvbnN0IGJ1aWxkTWFwT2ZHcmFwaCA9XG4gICAgICAgICh0ZW5zb3I6IFN5bWJvbGljVGVuc29yLCBmaW5pc2hlZE5vZGVzOiBOb2RlW10sIG5vZGVzSW5Qcm9ncmVzczogTm9kZVtdLFxuICAgICAgICAgbGF5ZXI/OiBMYXllciwgbm9kZUluZGV4PzogbnVtYmVyLCB0ZW5zb3JJbmRleD86IG51bWJlcikgPT4ge1xuICAgICAgICAgIGlmIChsYXllciA9PSBudWxsIHx8IG5vZGVJbmRleCA9PSBudWxsIHx8IHRlbnNvckluZGV4ID09IG51bGwpIHtcbiAgICAgICAgICAgIGxheWVyID0gdGVuc29yLnNvdXJjZUxheWVyO1xuICAgICAgICAgICAgbm9kZUluZGV4ID0gdGVuc29yLm5vZGVJbmRleDtcbiAgICAgICAgICAgIHRlbnNvckluZGV4ID0gdGVuc29yLnRlbnNvckluZGV4O1xuICAgICAgICAgIH1cbiAgICAgICAgICBjb25zdCBub2RlID0gbGF5ZXIuaW5ib3VuZE5vZGVzW25vZGVJbmRleF07XG5cbiAgICAgICAgICAvLyBQcmV2ZW50IGN5Y2xlcy5cbiAgICAgICAgICBpZiAobm9kZXNJblByb2dyZXNzLmluZGV4T2Yobm9kZSkgIT09IC0xKSB7XG4gICAgICAgICAgICB0aHJvdyBuZXcgUnVudGltZUVycm9yKFxuICAgICAgICAgICAgICAgIGBUaGUgdGVuc29yICR7dGVuc29yLm5hbWV9IGF0IGxheWVyIFwiJHtsYXllci5uYW1lfVwiIGAgK1xuICAgICAgICAgICAgICAgICdpcyBwYXJ0IG9mIGEgY3ljbGUuJyk7XG4gICAgICAgICAgfVxuXG4gICAgICAgICAgLy8gRG9uJ3QgcmVwZWF0IHdvcmsgZm9yIHNoYXJlZCBzdWJncmFwaHNcbiAgICAgICAgICBpZiAoZmluaXNoZWROb2Rlcy5pbmRleE9mKG5vZGUpICE9PSAtMSkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgIH1cblxuICAgICAgICAgIC8vIFVwZGF0ZSBjb250YWluZXJOb2Rlcy5cbiAgICAgICAgICB0aGlzLmNvbnRhaW5lck5vZGVzLmFkZChDb250YWluZXIubm9kZUtleShsYXllciwgbm9kZUluZGV4KSk7XG5cbiAgICAgICAgICAvLyBTdG9yZSB0aGUgdHJhdmVyc2FsIG9yZGVyIGZvciBsYXllciBzb3J0aW5nLlxuICAgICAgICAgIGlmICghKGxheWVyLmlkIGluIGxheWVySW5kaWNlcykpIHtcbiAgICAgICAgICAgIGxheWVySW5kaWNlc1tsYXllci5pZF0gPSBPYmplY3Qua2V5cyhsYXllckluZGljZXMpLmxlbmd0aDtcbiAgICAgICAgICB9XG5cbiAgICAgICAgICBpZiAobm9kZXNJblByb2dyZXNzLmluZGV4T2Yobm9kZSkgPT09IC0xKSB7XG4gICAgICAgICAgICBub2Rlc0luUHJvZ3Jlc3MucHVzaChub2RlKTtcbiAgICAgICAgICB9XG5cbiAgICAgICAgICAvLyBQcm9wYWdhdGUgdG8gYWxsIHByZXZpb3VzIHRlbnNvcnMgY29ubmVjdGVkIHRvIHRoaXMgbm9kZS5cbiAgICAgICAgICBjb25zdCBudW1JbmJvdW5kTGF5ZXJzID0gbm9kZS5pbmJvdW5kTGF5ZXJzLmxlbmd0aDtcbiAgICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IG51bUluYm91bmRMYXllcnM7IGkrKykge1xuICAgICAgICAgICAgY29uc3QgeCA9IG5vZGUuaW5wdXRUZW5zb3JzW2ldO1xuICAgICAgICAgICAgY29uc3QgbGF5ZXIgPSBub2RlLmluYm91bmRMYXllcnNbaV07XG4gICAgICAgICAgICBjb25zdCBub2RlSW5kZXggPSBub2RlLm5vZGVJbmRpY2VzW2ldO1xuICAgICAgICAgICAgY29uc3QgdGVuc29ySW5kZXggPSBub2RlLnRlbnNvckluZGljZXNbaV07XG4gICAgICAgICAgICBidWlsZE1hcE9mR3JhcGgoXG4gICAgICAgICAgICAgICAgeCwgZmluaXNoZWROb2Rlcywgbm9kZXNJblByb2dyZXNzLCBsYXllciwgbm9kZUluZGV4LFxuICAgICAgICAgICAgICAgIHRlbnNvckluZGV4KTtcbiAgICAgICAgICB9XG4gICAgICAgICAgZmluaXNoZWROb2Rlcy5wdXNoKG5vZGUpO1xuICAgICAgICAgIHdoaWxlIChub2Rlc0luUHJvZ3Jlc3MuaW5kZXhPZihub2RlKSA+PSAwKSB7XG4gICAgICAgICAgICBub2Rlc0luUHJvZ3Jlc3Muc3BsaWNlKG5vZGVzSW5Qcm9ncmVzcy5pbmRleE9mKG5vZGUpLCAxKTtcbiAgICAgICAgICB9XG4gICAgICAgICAgbm9kZXNJbkRlY3JlYXNpbmdEZXB0aC5wdXNoKG5vZGUpO1xuICAgICAgICB9O1xuXG4gICAgY29uc3QgZmluaXNoZWROb2RlczogTm9kZVtdID0gW107XG4gICAgY29uc3Qgbm9kZXNJblByb2dyZXNzOiBOb2RlW10gPSBbXTtcbiAgICBmb3IgKGNvbnN0IHggb2YgdGhpcy5vdXRwdXRzKSB7XG4gICAgICBidWlsZE1hcE9mR3JhcGgoeCwgZmluaXNoZWROb2Rlcywgbm9kZXNJblByb2dyZXNzKTtcbiAgICB9XG5cbiAgICBjb25zdCByZXZlcnNlZE5vZGVzSW5EZWNyZWFzaW5nRGVwdGggPVxuICAgICAgICBub2Rlc0luRGVjcmVhc2luZ0RlcHRoLnNsaWNlKCkucmV2ZXJzZSgpO1xuICAgIGZvciAoY29uc3Qgbm9kZSBvZiByZXZlcnNlZE5vZGVzSW5EZWNyZWFzaW5nRGVwdGgpIHtcbiAgICAgIG5vZGVJRFRvTm9kZVtub2RlLmlkXSA9IG5vZGU7XG4gICAgICAvLyBJZiB0aGUgZGVwdGggaXMgbm90IHNldCwgdGhlIG5vZGUgaGFzIG5vIG91dGJvdW5kIG5vZGVzIChkZXB0aCAwKS5cbiAgICAgIGlmICghKG5vZGUuaWQgaW4gbm9kZXNEZXB0aHMpKSB7XG4gICAgICAgIG5vZGVzRGVwdGhzW25vZGUuaWRdID0gMDtcbiAgICAgIH1cbiAgICAgIGxldCBkZXB0aCA9IG5vZGVzRGVwdGhzW25vZGUuaWRdO1xuXG4gICAgICAvLyBVcGRhdGUgdGhlIGRlcHRoIG9mIHRoZSBjb3JyZXNwb25kaW5nIGxheWVyXG4gICAgICBjb25zdCBwcmV2aW91c0RlcHRoID1cbiAgICAgICAgICAobGF5ZXJzRGVwdGhzW25vZGUub3V0Ym91bmRMYXllci5pZF0gPT0gbnVsbCA/XG4gICAgICAgICAgICAgICAwIDpcbiAgICAgICAgICAgICAgIGxheWVyc0RlcHRoc1tub2RlLm91dGJvdW5kTGF5ZXIuaWRdKTtcblxuICAgICAgLypcbiAgICAgICAgSWYgd2UndmUgc2VlbiB0aGlzIGxheWVyIGJlZm9yZSBhdCBhIGhpZ2hlciBkZXB0aCwgd2Ugc2hvdWxkIHVzZSB0aGF0XG4gICAgICAgIGRlcHRoIGluc3RlYWQgb2YgdGhlIG5vZGUgZGVwdGguICBUaGlzIGlzIG5lY2Vzc2FyeSBmb3Igc2hhcmVkIGxheWVyc1xuICAgICAgICB0aGF0IGhhdmUgaW5wdXRzIGF0IGRpZmZlcmVudCBkZXB0aCBsZXZlbHMgaW4gdGhlIGdyYXBoLlxuICAgICAgKi9cbiAgICAgIGRlcHRoID0gTWF0aC5tYXgoZGVwdGgsIHByZXZpb3VzRGVwdGgpO1xuICAgICAgbGF5ZXJzRGVwdGhzW25vZGUub3V0Ym91bmRMYXllci5pZF0gPSBkZXB0aDtcbiAgICAgIGxheWVySURUb0xheWVyW25vZGUub3V0Ym91bmRMYXllci5pZF0gPSBub2RlLm91dGJvdW5kTGF5ZXI7XG4gICAgICBub2Rlc0RlcHRoc1tub2RlLmlkXSA9IGRlcHRoO1xuXG4gICAgICAvLyBVcGRhdGUgdGhlIGRlcHRoIG9mIGluYm91bmQgbm9kZXMuXG4gICAgICBmb3IgKGxldCBpID0gMDsgaSA8IG5vZGUuaW5ib3VuZExheWVycy5sZW5ndGg7IGkrKykge1xuICAgICAgICBjb25zdCBpbmJvdW5kTGF5ZXIgPSBub2RlLmluYm91bmRMYXllcnNbaV07XG4gICAgICAgIGNvbnN0IG5vZGVJbmRleCA9IG5vZGUubm9kZUluZGljZXNbaV07XG4gICAgICAgIGNvbnN0IGluYm91bmROb2RlID0gaW5ib3VuZExheWVyLmluYm91bmROb2Rlc1tub2RlSW5kZXhdO1xuICAgICAgICBjb25zdCBwcmV2aW91c0RlcHRoID1cbiAgICAgICAgICAgIChub2Rlc0RlcHRoc1tpbmJvdW5kTm9kZS5pZF0gPT0gbnVsbCA/IDAgOlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbm9kZXNEZXB0aHNbaW5ib3VuZE5vZGUuaWRdKTtcbiAgICAgICAgbm9kZXNEZXB0aHNbaW5ib3VuZE5vZGUuaWRdID0gTWF0aC5tYXgoZGVwdGggKyAxLCBwcmV2aW91c0RlcHRoKTtcbiAgICAgICAgbm9kZUlEVG9Ob2RlW2luYm91bmROb2RlLmlkXSA9IGluYm91bmROb2RlO1xuICAgICAgfVxuICAgIH1cblxuICAgIC8vIEJ1aWxkIGEgZGljdCB7ZGVwdGg6IGxpc3Qgb2Ygbm9kZXMgd2l0aCB0aGlzIGRlcHRofVxuICAgIGNvbnN0IG5vZGVzQnlEZXB0aDoge1tkZXB0aDogc3RyaW5nXTogTm9kZVtdfSA9IHt9O1xuICAgIGZvciAoY29uc3Qgbm9kZUlEIGluIG5vZGVzRGVwdGhzKSB7XG4gICAgICBjb25zdCBkZXB0aCA9IG5vZGVzRGVwdGhzW25vZGVJRF07XG4gICAgICBpZiAoIShkZXB0aCBpbiBub2Rlc0J5RGVwdGgpKSB7XG4gICAgICAgIG5vZGVzQnlEZXB0aFtkZXB0aF0gPSBbXTtcbiAgICAgIH1cbiAgICAgIG5vZGVzQnlEZXB0aFtkZXB0aF0ucHVzaChub2RlSURUb05vZGVbbm9kZUlEXSk7XG4gICAgfVxuXG4gICAgLy8gQnVpbGQgYSBkaWN0IHtkZXB0aDogbGlzdCBvZiBsYXllcnMgd2l0aCB0aGlzIGRlcHRofVxuICAgIGNvbnN0IGxheWVyc0J5RGVwdGg6IHtbZGVwdGg6IHN0cmluZ106IExheWVyW119ID0ge307XG4gICAgZm9yIChjb25zdCBsYXllcklEIGluIGxheWVyc0RlcHRocykge1xuICAgICAgY29uc3QgZGVwdGggPSBsYXllcnNEZXB0aHNbbGF5ZXJJRF07XG4gICAgICBpZiAoIShkZXB0aCBpbiBsYXllcnNCeURlcHRoKSkge1xuICAgICAgICBsYXllcnNCeURlcHRoW2RlcHRoXSA9IFtdO1xuICAgICAgfVxuICAgICAgbGF5ZXJzQnlEZXB0aFtkZXB0aF0ucHVzaChsYXllcklEVG9MYXllcltsYXllcklEXSk7XG4gICAgfVxuXG4gICAgLy8gR2V0IHNvcnRlZCBsaXN0IG9mIGxheWVyIGRlcHRocy5cbiAgICBsZXQgZGVwdGhLZXlzID0gT2JqZWN0LmtleXMobGF5ZXJzQnlEZXB0aClcbiAgICAgICAgICAgICAgICAgICAgICAgIC5tYXAoeCA9PiBwYXJzZUludCh4LCAxMCkpXG4gICAgICAgICAgICAgICAgICAgICAgICAuc29ydChnZW5lcmljX3V0aWxzLnJldmVyc2VOdW1iZXJDb21wYXJlKTtcblxuICAgIC8vIFNldCB0aGlzLmxheWVycyBhbmQgdGhpcy5sYXllcnNCeURlcHRoLlxuICAgIHRoaXMubGF5ZXJzID0gW107XG4gICAgZm9yIChjb25zdCBkZXB0aCBvZiBkZXB0aEtleXMpIHtcbiAgICAgIGNvbnN0IGxheWVyc0ZvckRlcHRoID0gbGF5ZXJzQnlEZXB0aFtkZXB0aF07XG4gICAgICAvLyBDb250YWluZXIubGF5ZXJzIG5lZWRzIHRvIGhhdmUgYSBkZXRlcm1pbmlzdGljIG9yZGVyOlxuICAgICAgLy8gaGVyZSB3ZSBvcmRlciB0aGVtIGJ5IHRyYXZlcnNhbCBvcmRlci5cbiAgICAgIGxheWVyc0ZvckRlcHRoLnNvcnQoKGEsIGIpID0+IHtcbiAgICAgICAgY29uc3QgYUluZGV4ID0gbGF5ZXJJbmRpY2VzW2EuaWRdO1xuICAgICAgICBjb25zdCBiSW5kZXggPSBsYXllckluZGljZXNbYi5pZF07XG4gICAgICAgIGlmIChhSW5kZXggPCBiSW5kZXgpIHtcbiAgICAgICAgICByZXR1cm4gLTE7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKGFJbmRleCA+IGJJbmRleCkge1xuICAgICAgICAgIHJldHVybiAxO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiAwO1xuICAgICAgfSk7XG4gICAgICBmb3IgKGNvbnN0IGxheWVyIG9mIGxheWVyc0ZvckRlcHRoKSB7XG4gICAgICAgIGlmIChsYXllciBpbnN0YW5jZW9mIENvbnRhaW5lcikge1xuICAgICAgICAgIHRoaXMuaW50ZXJuYWxDb250YWluZXJSZWZzLnB1c2gobGF5ZXIpO1xuICAgICAgICB9XG4gICAgICAgIHRoaXMubGF5ZXJzLnB1c2gobGF5ZXIpO1xuICAgICAgfVxuICAgIH1cbiAgICB0aGlzLmxheWVyc0J5RGVwdGggPSBsYXllcnNCeURlcHRoO1xuXG4gICAgLy8gR2V0IHNvcnRlZCBsaXN0IG9mIG5vZGUgZGVwdGhzO1xuICAgIGRlcHRoS2V5cyA9IE9iamVjdC5rZXlzKG5vZGVzQnlEZXB0aClcbiAgICAgICAgICAgICAgICAgICAgLm1hcCh4ID0+IHBhcnNlSW50KHgsIDEwKSlcbiAgICAgICAgICAgICAgICAgICAgLnNvcnQoZ2VuZXJpY191dGlscy5yZXZlcnNlTnVtYmVyQ29tcGFyZSk7XG5cbiAgICAvLyBDaGVjayB0aGF0IGFsbCB0ZW5zb3JzIHJlcXVpcmVkIGFyZSBjb21wdXRhYmxlLlxuICAgIC8vIGNvbXB1dGFibGVfdGVuc29yczogYWxsIHRlbnNvcnMgaW4gdGhlIGdyYXBoXG4gICAgLy8gdGhhdCBjYW4gYmUgY29tcHV0ZWQgZnJvbSB0aGUgaW5wdXRzIHByb3ZpZGVkLlxuICAgIGNvbnN0IGNvbXB1dGFibGVUZW5zb3JzID0gdGhpcy5pbnB1dHMuc2xpY2UoKTtcblxuICAgIC8vIFRvIHByb3ZpZGUgYSBiZXR0ZXIgZXJyb3IgbXNnLlxuICAgIGNvbnN0IGxheWVyc1dpdGhDb21wbGV0ZUlucHV0OiBzdHJpbmdbXSA9IFtdO1xuICAgIGZvciAoY29uc3QgZGVwdGggb2YgZGVwdGhLZXlzKSB7XG4gICAgICBmb3IgKGNvbnN0IG5vZGUgb2Ygbm9kZXNCeURlcHRoW2RlcHRoXSkge1xuICAgICAgICBjb25zdCBsYXllciA9IG5vZGUub3V0Ym91bmRMYXllcjtcbiAgICAgICAgaWYgKGxheWVyICE9IG51bGwpIHtcbiAgICAgICAgICBmb3IgKGNvbnN0IHggb2Ygbm9kZS5pbnB1dFRlbnNvcnMpIHtcbiAgICAgICAgICAgIGlmIChjb21wdXRhYmxlVGVuc29ycy5pbmRleE9mKHgpID09PSAtMSkge1xuICAgICAgICAgICAgICB0aHJvdyBuZXcgUnVudGltZUVycm9yKFxuICAgICAgICAgICAgICAgICAgYEdyYXBoIGRpc2Nvbm5lY3RlZDogY2Fubm90IG9idGFpbiB2YWx1ZSBmb3IgdGVuc29yICR7eH1gICtcbiAgICAgICAgICAgICAgICAgIGAgYXQgbGF5ZXIgXCIke2xheWVyLm5hbWV9XCIuIGAgK1xuICAgICAgICAgICAgICAgICAgJ1RoZSBmb2xsb3dpbmcgcHJldmlvdXMgbGF5ZXJzIHdlcmUgYWNjZXNzZWQgd2l0aG91dCAnICtcbiAgICAgICAgICAgICAgICAgIGBpc3N1ZTogJHtsYXllcnNXaXRoQ29tcGxldGVJbnB1dH1gKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9XG4gICAgICAgICAgZm9yIChjb25zdCB4IG9mIG5vZGUub3V0cHV0VGVuc29ycykge1xuICAgICAgICAgICAgY29tcHV0YWJsZVRlbnNvcnMucHVzaCh4KTtcbiAgICAgICAgICB9XG4gICAgICAgICAgbGF5ZXJzV2l0aENvbXBsZXRlSW5wdXQucHVzaChsYXllci5uYW1lKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH1cblxuICAgIC8vIFNldCB0aGlzLmNvbnRhaW5lck5vZGVzIGFuZCB0aGlzLm5vZGVzQnlEZXB0aC5cbiAgICB0aGlzLm5vZGVzQnlEZXB0aCA9IG5vZGVzQnlEZXB0aDtcblxuICAgIC8vIEVuc3VyZSBuYW1lIHVuaWNpdHksIHdoaWNoIHdpbGwgYmUgY3J1Y2lhbCBmb3Igc2VyaWFsaXphdGlvblxuICAgIC8vIChzaW5jZSBzZXJpYWxpemVkIG5vZGVzIHJlZmVyIHRvIGxheWVycyBieSB0aGVpciBuYW1lKS5cbiAgICBjb25zdCBhbGxOYW1lcyA9IHRoaXMubGF5ZXJzLm1hcCh4ID0+IHgubmFtZSk7XG4gICAgZm9yIChjb25zdCBuYW1lIG9mIGFsbE5hbWVzKSB7XG4gICAgICBjb25zdCBudW1PY2N1cnJlbmNlcyA9IGFsbE5hbWVzLmZpbHRlcih4ID0+IHggPT09IG5hbWUpLmxlbmd0aDtcbiAgICAgIGlmIChudW1PY2N1cnJlbmNlcyAhPT0gMSkge1xuICAgICAgICB0aHJvdyBuZXcgUnVudGltZUVycm9yKFxuICAgICAgICAgICAgYFRoZSBuYW1lIFwiJHtuYW1lfVwiIGlzIHVzZWQgJHtudW1PY2N1cnJlbmNlc30gdGltZXMgYCArXG4gICAgICAgICAgICAnaW4gdGhlIG1vZGVsLiBBbGwgbGF5ZXIgbmFtZXMgc2hvdWxkIGJlIHVuaXF1ZS4gTGF5ZXIgbmFtZXM6ICcgK1xuICAgICAgICAgICAgSlNPTi5zdHJpbmdpZnkoYWxsTmFtZXMpKTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICAvLyBMYXllciBwYXJhbWV0ZXJzLlxuICAgIC8vIFRoZSBuZXcgY29udGFpbmVyIHN0YXJ0cyB3aXRoIGEgc2luZ2xlIGluYm91bmQgbm9kZVxuICAgIC8vIGZvciBpdHMgaW5wdXRzLCBhbmQgbm8gb3V0Ym91bmQgbm9kZXMuXG4gICAgLy8gV2lsbCBiZSBhcHBlbmRlZCB0byBieSBmdXR1cmUgY2FsbHMgdG8gYXBwbHkoKS5cbiAgICB0aGlzLm91dGJvdW5kTm9kZXMgPSBbXTtcbiAgICAvLyBXaWxsIGJlIGFwcGVuZGVkIHRvIGJlbG93LCBhbmQgYnkgZnV0dXJlIGNhbGxzIHRvIGFwcGx5KCkuXG4gICAgdGhpcy5pbmJvdW5kTm9kZXMgPSBbXTtcblxuICAgIC8vIENyZWF0ZSB0aGUgbm9kZSBsaW5raW5nIGludGVybmFsIGlucHV0cyB0byBpbnRlcm5hbCBvdXRwdXRzLlxuICAgIC8vIChUaGlzIGNhbGwgaGFzIHNpZGUgZWZmZWN0cy4pXG4gICAgLy8gdHNsaW50OmRpc2FibGUtbmV4dC1saW5lOm5vLXVudXNlZC1leHByZXNzaW9uXG4gICAgbmV3IE5vZGUoe1xuICAgICAgb3V0Ym91bmRMYXllcjogdGhpcyxcbiAgICAgIGluYm91bmRMYXllcnM6IFtdLFxuICAgICAgbm9kZUluZGljZXM6IFtdLFxuICAgICAgdGVuc29ySW5kaWNlczogW10sXG4gICAgICBpbnB1dFRlbnNvcnM6IHRoaXMuaW5wdXRzLFxuICAgICAgb3V0cHV0VGVuc29yczogdGhpcy5vdXRwdXRzLFxuICAgICAgaW5wdXRNYXNrczogdGhpcy5pbnB1dHMubWFwKHggPT4gbnVsbCksXG4gICAgICBvdXRwdXRNYXNrczogdGhpcy5vdXRwdXRzLm1hcCh4ID0+IG51bGwpLFxuICAgICAgaW5wdXRTaGFwZXM6IHRoaXMuaW5wdXRzLm1hcCh4ID0+IHguc2hhcGUpLFxuICAgICAgb3V0cHV0U2hhcGVzOiB0aGlzLm91dHB1dHMubWFwKHggPT4geC5zaGFwZSlcbiAgICB9KTtcbiAgICB0aGlzLmJ1aWx0ID0gdHJ1ZTtcbiAgICB0aGlzLl9yZWZDb3VudCA9IDE7ICAvLyBUaGUgcmVmIGNvdW50IG9mIGEgY29udGFpbmVyIGFsd2F5cyBzdGFydCBhdCAxLlxuICB9XG5cbiAgcHJvdGVjdGVkIG92ZXJyaWRlIGFzc2VydE5vdERpc3Bvc2VkKCkge1xuICAgIGlmICh0aGlzLl9yZWZDb3VudCA9PT0gMCkge1xuICAgICAgdGhyb3cgbmV3IEVycm9yKGBDb250YWluZXIgJyR7dGhpcy5uYW1lfScgaXMgYWxyZWFkeSBkaXNwb3NlZC5gKTtcbiAgICB9XG4gIH1cblxuICAvKipcbiAgICogQXR0ZW1wdCB0byBkaXNwb3NlIGEgTGF5ZXJzTW9kZWwncyB3ZWlnaHRzLlxuICAgKlxuICAgKiBUaGlzIG1ldGhvZCBkZWNyZWFzZSB0aGUgcmVmZXJlbmNlIGNvdW50IG9mIHRoZSBMYXllcnNNb2RlbCBvYmplY3QgYnkgMS5cbiAgICpcbiAgICogQSBMYXllcnNNb2RlbCBpcyByZWZlcmVuY2UtY291bnRlZC4gSXRzIHJlZmVyZW5jZSBjb3VudCBpcyBpbmNyZW1lbnRlZCBieSAxXG4gICAqIHdoZW4gaXQgaXMgZmlyc3QgY29uc3RydWN0ZWQgYW5kIHdoZW4gaXQgaXMgdXNlZCBhcyBhIExheWVyIG9mIGFub3RoZXJcbiAgICogTGF5ZXJzTW9kZWwuXG4gICAqXG4gICAqIElmIHRoZSByZWZlcmVuY2UgY291bnQgb2YgYSBMYXllcnNNb2RlbCBiZWNvbWVzIDAsIHRoZSBgZGlzcG9zZWAgbWV0aG9kIG9mXG4gICAqIGFsbCBpdHMgY29uc3RpdHVlbnQgYExheWVyYHMgd2lsbCBiZSBjYWxsZWQuXG4gICAqXG4gICAqIE5vdGU6IElmIHRoZSByZWZlcmVuY2UgY291bnQgaXMgZ3JlYXRlciB0aGFuIDAgYWZ0ZXIgdGhlIGRlY3JlbWVudCwgdGhlXG4gICAqIGBkaXNwb3NlYCBtZXRob2Qgb2YgaXRzIGNvbnN0aXR1ZW50IGBMYXllcmBzIHdpbGwgKm5vdCogYmUgY2FsbGVkLlxuICAgKlxuICAgKiBBZnRlciBhIExheWVyc01vZGVsIGlzIGRpc3Bvc2VkLCBpdCBjYW5ub3QgYmUgdXNlZCBpbiBjYWxscyBzdWNoIGFzXG4gICAqICdwcmVkaWN0YCwgYGV2YWx1YXRlYCBvciBgZml0YCBhbnltb3JlLlxuICAgKlxuICAgKiBAcmV0dXJucyBBIERpc3Bvc2VSZXN1bHQgT2JqZWN0IHdpdGggdGhlIGZvbGxvd2luZyBmaWVsZHM6XG4gICAqICAgLSByZWZDb3VudEFmdGVyRGlzcG9zZTogVGhlIHJlZmVyZW5jZSBjb3VudCBvZiB0aGUgTGF5ZXJzTW9kZWwgYWZ0ZXIgdGhpc1xuICAgKiAgICAgYGRpc3Bvc2UoKWAgY2FsbC5cbiAgICogICAtIG51bURpc3Bvc2VkVmFyaWFibGVzOiBOdW1iZXIgb2YgYHRmLlZhcmlhYmxlYHMgKGkuZS4sIHdlaWdodHMpIGRpc3Bvc2VkXG4gICAqICAgICBkdXJpbmcgdGhpcyBgZGlzcG9zZSgpYCBjYWxsLlxuICAgKiBAdGhyb3dzIHtFcnJvcn0gSWYgdGhlIGxheWVyIGlzIG5vdCBidWlsdCB5ZXQsIG9yIGlmIHRoZSBMYXllcnNNb2RlbCBoYXNcbiAgICogICBhbHJlYWR5IGJlZW4gZGlzcG9zZWQuXG4gICAqL1xuICBvdmVycmlkZSBkaXNwb3NlKCk6IERpc3Bvc2VSZXN1bHQge1xuICAgIHRoaXMuYXNzZXJ0Tm90RGlzcG9zZWQoKTtcbiAgICBjb25zdCByZXN1bHQ6XG4gICAgICAgIERpc3Bvc2VSZXN1bHQgPSB7cmVmQ291bnRBZnRlckRpc3Bvc2U6IG51bGwsIG51bURpc3Bvc2VkVmFyaWFibGVzOiAwfTtcbiAgICBpZiAoLS10aGlzLl9yZWZDb3VudCA9PT0gMCkge1xuICAgICAgZm9yIChjb25zdCBsYXllciBvZiB0aGlzLmxheWVycykge1xuICAgICAgICByZXN1bHQubnVtRGlzcG9zZWRWYXJpYWJsZXMgKz0gbGF5ZXIuZGlzcG9zZSgpLm51bURpc3Bvc2VkVmFyaWFibGVzO1xuICAgICAgfVxuXG4gICAgICAvLyBDYWxsIGRpc3Bvc2Ugb24gZWFjaCBpbnRlcm5hbGx5IGNyZWF0ZWQgY29udGFpbmVyIGxheWVyIGFnYWluIHRvIGVuc3VyZVxuICAgICAgLy8gdGhlaXIgcmVmQ291bnRzIGhpdCB6ZXJvIGFuZCB0aGVpciB0ZW5zb3JzIGFyZSBzdWJzZXF1ZW50bHkgZGVsZXRlZC5cbiAgICAgIGZvciAoY29uc3QgY29udGFpbmVyIG9mIHRoaXMuaW50ZXJuYWxDb250YWluZXJSZWZzKSB7XG4gICAgICAgIHJlc3VsdC5udW1EaXNwb3NlZFZhcmlhYmxlcyArPSBjb250YWluZXIuZGlzcG9zZSgpLm51bURpc3Bvc2VkVmFyaWFibGVzO1xuICAgICAgfVxuICAgIH1cbiAgICByZXN1bHQucmVmQ291bnRBZnRlckRpc3Bvc2UgPSB0aGlzLl9yZWZDb3VudDtcbiAgICByZXR1cm4gcmVzdWx0O1xuICB9XG5cbiAgb3ZlcnJpZGUgZ2V0IHRyYWluYWJsZSgpIHtcbiAgICByZXR1cm4gdGhpcy50cmFpbmFibGVfO1xuICB9XG5cbiAgb3ZlcnJpZGUgc2V0IHRyYWluYWJsZSh0cmFpbmFibGU6IGJvb2xlYW4pIHtcbiAgICB0aGlzLmxheWVycy5mb3JFYWNoKGxheWVyID0+IHtcbiAgICAgIC8vIHRzbGludDpkaXNhYmxlLW5leHQtbGluZTpuby1hbnlcbiAgICAgICgobGF5ZXIgYXMgYW55KS5fdHJhaW5hYmxlV2VpZ2h0cyBhcyBMYXllclZhcmlhYmxlW10pXG4gICAgICAgICAgLmZvckVhY2godyA9PiB3LnRyYWluYWJsZSA9IHRyYWluYWJsZSk7XG4gICAgfSk7XG4gICAgdGhpcy50cmFpbmFibGVfID0gdHJhaW5hYmxlO1xuICB9XG5cbiAgb3ZlcnJpZGUgZ2V0IHRyYWluYWJsZVdlaWdodHMoKTogTGF5ZXJWYXJpYWJsZVtdIHtcbiAgICAvLyBQb3J0aW5nIE5vdGU6IFRoaXMgY2hlY2sgYmVsb3cgaXMgdG8gcHJldmVudCBlcnJvcnMgd2hlcmUgdGhlXG4gICAgLy8gICBfdHJhaW5hYmxlV2VpZ2h0cyBpbmhlcml0ZWQgZnJvbSB0aGUgcGFyZW50IGNsYXNzIChMYXllcikgZ2V0c1xuICAgIC8vICAgaW5hZHZlcnRlbnRseSB1c2VkLlxuICAgIGlmICh0aGlzLl90cmFpbmFibGVXZWlnaHRzLmxlbmd0aCA+IDApIHtcbiAgICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKFxuICAgICAgICAgICdDb250YWluZXIgaW5zdGFuY2UgdW5leHBlY3RlZGx5IGNvbnRhaW5zIF90cmFpbmFibGVXZWlnaHRzLicgK1xuICAgICAgICAgICdUaGUgdHJhaW5hYmxlIHdlaWdodHMgb2YgYSBDb250YWluZXIgYXJlIGEgdW5pb24gb2YgdGhlICcgK1xuICAgICAgICAgICd0cmFpbmFibGUgd2VpZ2h0cyBvZiBpdHMgY29uc2l0dWVudCBMYXllcnMuIEl0cyBvd24gJyArXG4gICAgICAgICAgJ190cmFpbmFibGVXZWlnaHRzIG11c3QgcmVtYWluIGFuIGVtcHR5IEFycmF5LicpO1xuICAgIH1cblxuICAgIGlmICghdGhpcy50cmFpbmFibGUpIHtcbiAgICAgIHJldHVybiBbXTtcbiAgICB9XG4gICAgbGV0IHdlaWdodHM6IExheWVyVmFyaWFibGVbXSA9IFtdO1xuICAgIGZvciAoY29uc3QgbGF5ZXIgb2YgdGhpcy5sYXllcnMpIHtcbiAgICAgIHdlaWdodHMgPSB3ZWlnaHRzLmNvbmNhdChsYXllci50cmFpbmFibGVXZWlnaHRzKTtcbiAgICB9XG4gICAgcmV0dXJuIHdlaWdodHM7XG4gIH1cblxuICBvdmVycmlkZSBnZXQgbm9uVHJhaW5hYmxlV2VpZ2h0cygpOiBMYXllclZhcmlhYmxlW10ge1xuICAgIGNvbnN0IHdlaWdodHM6IExheWVyVmFyaWFibGVbXSA9IFtdO1xuICAgIGZvciAoY29uc3QgbGF5ZXIgb2YgdGhpcy5sYXllcnMpIHtcbiAgICAgIHdlaWdodHMucHVzaCguLi5sYXllci5ub25UcmFpbmFibGVXZWlnaHRzKTtcbiAgICB9XG4gICAgaWYgKCF0aGlzLnRyYWluYWJsZSkge1xuICAgICAgY29uc3QgdHJhaW5hYmxlV2VpZ2h0czogTGF5ZXJWYXJpYWJsZVtdID0gW107XG4gICAgICBmb3IgKGNvbnN0IGxheWVyIG9mIHRoaXMubGF5ZXJzKSB7XG4gICAgICAgIHRyYWluYWJsZVdlaWdodHMucHVzaCguLi5sYXllci50cmFpbmFibGVXZWlnaHRzKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiB0cmFpbmFibGVXZWlnaHRzLmNvbmNhdCh3ZWlnaHRzKTtcbiAgICB9XG4gICAgcmV0dXJuIHdlaWdodHM7XG4gIH1cblxuICBvdmVycmlkZSBnZXQgd2VpZ2h0cygpOiBMYXllclZhcmlhYmxlW10ge1xuICAgIHJldHVybiB0aGlzLnRyYWluYWJsZVdlaWdodHMuY29uY2F0KHRoaXMubm9uVHJhaW5hYmxlV2VpZ2h0cyk7XG4gIH1cblxuICAvKipcbiAgICogTG9hZHMgYWxsIGxheWVyIHdlaWdodHMgZnJvbSBhIEpTT04gb2JqZWN0LlxuICAgKlxuICAgKiBQb3J0aW5nIE5vdGU6IEhERjUgd2VpZ2h0IGZpbGVzIGNhbm5vdCBiZSBkaXJlY3RseSBsb2FkZWQgaW4gSmF2YVNjcmlwdCAvXG4gICAqICAgVHlwZVNjcmlwdC4gVGhlIHV0aWxpdHkgc2NyaXB0IGF0IGBzY3JpcHRzL3B5a2VyYXMucHlgIG9mZmVycyBtZWFuc1xuICAgKiAgIHRvIGNvbnZlcnQgdGhlbSBpbnRvIEpTT04gc3RyaW5ncyBjb21wYXRpYmxlIHdpdGggdGhpcyBtZXRob2QuXG4gICAqIFBvcnRpbmcgTm90ZTogVGVuc29yRmxvdy5qcyBMYXllcnMgc3VwcG9ydHMgb25seSBsb2FkaW5nIGJ5IG5hbWUgY3VycmVudGx5LlxuICAgKlxuICAgKiBAcGFyYW0gd2VpZ2h0cyBBIEpTT04gbWFwcGluZyB3ZWlnaHQgbmFtZXMgdG8gd2VpZ2h0IHZhbHVlcyBhcyBuZXN0ZWRcbiAgICogICBhcnJheXMgb2YgbnVtYmVycywgb3IgYSBgTmFtZWRUZW5zb3JNYXBgLCBpLmUuLCBhIEpTT04gbWFwcGluZyB3ZWlnaHRcbiAgICogICBuYW1lcyB0byBgdGYuVGVuc29yYCBvYmplY3RzLlxuICAgKiBAcGFyYW0gc3RyaWN0IFJlcXVpcmUgdGhhdCB0aGUgcHJvdmlkZWQgd2VpZ2h0cyBleGFjdGx5IG1hdGNoIHRob3NlXG4gICAqICAgcmVxdWlyZWQgYnkgdGhlIGNvbnRhaW5lci4gIERlZmF1bHQ6IGB0cnVlYC4gIFBhc3NpbmcgYGZhbHNlYCBtZWFucyB0aGF0XG4gICAqICAgZXh0cmEgd2VpZ2h0cyBhbmQgbWlzc2luZyB3ZWlnaHRzIHdpbGwgYmUgc2lsZW50bHkgaWdub3JlZC5cbiAgICovXG4gIGxvYWRXZWlnaHRzKHdlaWdodHM6IE5hbWVkVGVuc29yTWFwLCBzdHJpY3QgPSB0cnVlKSB7XG4gICAgY29uc3QgbmFtZVRvV2VpZ2h0OiB7W25hbWU6IHN0cmluZ106IExheWVyVmFyaWFibGV9ID0ge307XG4gICAgbGV0IHRvdGFsV2VpZ2h0c0NvdW50ID0gMDtcbiAgICBmb3IgKGNvbnN0IGxheWVyIG9mIHRoaXMubGF5ZXJzKSB7XG4gICAgICBmb3IgKGNvbnN0IHdlaWdodCBvZiBsYXllci53ZWlnaHRzKSB7XG4gICAgICAgIGlmIChuYW1lVG9XZWlnaHRbd2VpZ2h0Lm9yaWdpbmFsTmFtZV0gIT0gbnVsbCkge1xuICAgICAgICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKGBEdXBsaWNhdGUgd2VpZ2h0IG5hbWU6ICR7d2VpZ2h0Lm9yaWdpbmFsTmFtZX1gKTtcbiAgICAgICAgfVxuICAgICAgICBuYW1lVG9XZWlnaHRbd2VpZ2h0Lm9yaWdpbmFsTmFtZV0gPSB3ZWlnaHQ7XG4gICAgICAgIHRvdGFsV2VpZ2h0c0NvdW50Kys7XG4gICAgICB9XG4gICAgfVxuXG4gICAgY29uc3Qgd2VpZ2h0VmFsdWVUdXBsZXM6IEFycmF5PFtMYXllclZhcmlhYmxlLCBUZW5zb3JdPiA9IFtdO1xuICAgIGZvciAoY29uc3QgbmFtZSBpbiB3ZWlnaHRzKSB7XG4gICAgICAvLyBURiAyLjIuMCBhZGRlZCBjZWxsIG5hbWUgdG8gdGhlIHdlaWdodCBuYW1lIGluIHRoZSBmb3JtYXQgb2ZcbiAgICAgIC8vIGxheWVyX25hbWUvY2VsbF9uYW1lL3dlaWdodF9uYW1lLCB3ZSBuZWVkIHRvIHJlbW92ZVxuICAgICAgLy8gdGhlIGlubmVyIGNlbGwgbmFtZS5cbiAgICAgIGxldCB2YWxpZGF0ZWROYW1lID0gbmFtZTtcbiAgICAgIGlmIChuYW1lVG9XZWlnaHRbbmFtZV0gPT0gbnVsbCkge1xuICAgICAgICBjb25zdCB0b2tlbnMgPSBuYW1lLnNwbGl0KCcvJyk7XG4gICAgICAgIGNvbnN0IHNob3J0ZW5OYW1lQXJyYXkgPVxuICAgICAgICAgICAgdG9rZW5zLnNsaWNlKDAsIC0yKS5jb25jYXQoW3Rva2Vuc1t0b2tlbnMubGVuZ3RoIC0gMV1dKTtcbiAgICAgICAgdmFsaWRhdGVkTmFtZSA9IHNob3J0ZW5OYW1lQXJyYXkuam9pbignLycpO1xuICAgICAgfVxuICAgICAgaWYgKG5hbWVUb1dlaWdodFt2YWxpZGF0ZWROYW1lXSAhPSBudWxsKSB7XG4gICAgICAgIHdlaWdodFZhbHVlVHVwbGVzLnB1c2goW25hbWVUb1dlaWdodFt2YWxpZGF0ZWROYW1lXSwgd2VpZ2h0c1tuYW1lXV0pO1xuICAgICAgfSBlbHNlIGlmIChzdHJpY3QpIHtcbiAgICAgICAgdGhyb3cgbmV3IFZhbHVlRXJyb3IoXG4gICAgICAgICAgICBgUHJvdmlkZWQgd2VpZ2h0IGRhdGEgaGFzIG5vIHRhcmdldCB2YXJpYWJsZTogJHtuYW1lfWApO1xuICAgICAgfVxuICAgICAgZGVsZXRlIG5hbWVUb1dlaWdodFt2YWxpZGF0ZWROYW1lXTtcbiAgICB9XG5cbiAgICBpZiAoc3RyaWN0KSB7XG4gICAgICAvLyBDaGVjayB0aGF0IGFsbCB3ZWlnaHRzIGFyZSBzZXQuXG4gICAgICBjb25zdCB1bnNldE5hbWVzOiBzdHJpbmdbXSA9IFtdO1xuICAgICAgZm9yIChjb25zdCBuYW1lIGluIG5hbWVUb1dlaWdodCkge1xuICAgICAgICB1bnNldE5hbWVzLnB1c2gobmFtZSk7XG4gICAgICB9XG4gICAgICBpZiAodW5zZXROYW1lcy5sZW5ndGggPiAwKSB7XG4gICAgICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKFxuICAgICAgICAgICAgYCR7dW5zZXROYW1lcy5sZW5ndGh9IG9mICR7XG4gICAgICAgICAgICAgICAgdG90YWxXZWlnaHRzQ291bnR9IHdlaWdodHMgYXJlIG5vdCBzZXQ6IGAgK1xuICAgICAgICAgICAgYCR7dW5zZXROYW1lc31gKTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICBiYXRjaFNldFZhbHVlKHdlaWdodFZhbHVlVHVwbGVzKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBVdGlsIHNoYXJlZCBiZXR3ZWVuIGRpZmZlcmVudCBzZXJpYWxpemF0aW9uIG1ldGhvZHMuXG4gICAqIEByZXR1cm5zIExheWVyc01vZGVsIGNvbmZpZyB3aXRoIEtlcmFzIHZlcnNpb24gaW5mb3JtYXRpb24gYWRkZWQuXG4gICAqL1xuICBwcm90ZWN0ZWQgdXBkYXRlZENvbmZpZygpOiBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3Qge1xuICAgIGNvbnN0IHRoZUNvbmZpZyA9IHRoaXMuZ2V0Q29uZmlnKCk7XG4gICAgY29uc3QgbW9kZWxDb25maWc6IHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCA9IHt9O1xuICAgIG1vZGVsQ29uZmlnWydjbGFzc05hbWUnXSA9IHRoaXMuZ2V0Q2xhc3NOYW1lKCk7XG4gICAgbW9kZWxDb25maWdbJ2NvbmZpZyddID0gdGhlQ29uZmlnO1xuICAgIG1vZGVsQ29uZmlnWydrZXJhc1ZlcnNpb24nXSA9IGB0ZmpzLWxheWVycyAke2xheWVyc1ZlcnNpb259YDtcbiAgICAvLyBUT0RPKG5pZWxzZW5lKTogUmVwbGFjZSBzb21ldGhpbmcgbGlrZSBLLmJhY2tlbmQoKSBvbmNlXG4gICAgLy8gcG9zc2libGUuXG4gICAgbW9kZWxDb25maWdbJ2JhY2tlbmQnXSA9ICdUZW5zb3JGbG93LmpzJztcbiAgICByZXR1cm4gbW9kZWxDb25maWc7XG4gIH1cblxuICAvKipcbiAgICogUmV0dXJucyBhIEpTT04gc3RyaW5nIGNvbnRhaW5pbmcgdGhlIG5ldHdvcmsgY29uZmlndXJhdGlvbi5cbiAgICpcbiAgICogVG8gbG9hZCBhIG5ldHdvcmsgZnJvbSBhIEpTT04gc2F2ZSBmaWxlLCB1c2VcbiAgICogbW9kZWxzLm1vZGVsRnJvbUpTT04oanNvblN0cmluZyk7XG4gICAqIEBwYXJhbSBleHRyYUpzb25BcmdzIFVudXNlZCBpbiB0ZmpzLWxheWVycywgbWFpbnRhaW5lZCBmb3IgUHlLZXJhc1xuICAgKiBAcGFyYW0gcmV0dXJuU3RyaW5nIFdoZXRoZXIgdGhlIHJldHVybiB2YWx1ZSBzaG91bGQgYmUgc3RyaW5naWZpZWRcbiAgICogICAgKGRlZmF1bHQ6IGB0cnVlYCkuXG4gICAqIEByZXR1cm5zIGEgSlNPTiBzdHJpbmcgaWYgYHJldHVyblN0cmluZ2AgKGRlZmF1bHQpLCBvciBhIEpTT04gb2JqZWN0IGlmXG4gICAqICAgYCFyZXR1cm5TdHJpbmdgLlxuICAgKi9cbiAgLy8gdHNsaW50OmRpc2FibGUtbmV4dC1saW5lOm5vLWFueVxuICB0b0pTT04odW51c2VkPzogYW55LCByZXR1cm5TdHJpbmcgPSB0cnVlKTogc3RyaW5nfFB5SnNvbkRpY3Qge1xuICAgIGNvbnN0IG1vZGVsQ29uZmlnID0gY29udmVydFRzVG9QeXRob25pYyh0aGlzLnVwZGF0ZWRDb25maWcoKSkgYXMgUHlKc29uRGljdDtcbiAgICByZXR1cm4gcmV0dXJuU3RyaW5nID8gSlNPTi5zdHJpbmdpZnkobW9kZWxDb25maWcpIDogbW9kZWxDb25maWc7XG4gIH1cblxuICAvKipcbiAgICogQ2FsbCB0aGUgbW9kZWwgb24gbmV3IGlucHV0cy5cbiAgICpcbiAgICogSW4gdGhpcyBjYXNlIGBjYWxsYCBqdXN0IHJlYXBwbGllcyBhbGwgb3BzIGluIHRoZSBncmFwaCB0byB0aGUgbmV3IGlucHV0c1xuICAgKiAoZS5nLiBidWlsZCBhIG5ldyBjb21wdXRhdGlvbmFsIGdyYXBoIGZyb20gdGhlIHByb3ZpZGVkIGlucHV0cykuXG4gICAqXG4gICAqIEBwYXJhbSBpbnB1dHMgQSB0ZW5zb3Igb3IgbGlzdCBvZiB0ZW5zb3JzLlxuICAgKiBAcGFyYW0gbWFzayBBIG1hc2sgb3IgbGlzdCBvZiBtYXNrcy4gQSBtYXNrIGNhbiBiZSBlaXRoZXIgYSB0ZW5zb3Igb3IgbnVsbFxuICAgKiAgIChubyBtYXNrKS5cbiAgICpcbiAgICogQHJldHVybiBBIHRlbnNvciBpZiB0aGVyZSBpcyBhIHNpbmdsZSBvdXRwdXQsIG9yIGEgbGlzdCBvZiB0ZW5zb3JzIGlmIHRoZXJlXG4gICAqICAgYXJlIG1vcmUgdGhhbiBvbmUgb3V0cHV0cy5cbiAgICovXG4gIG92ZXJyaWRlIGNhbGwoaW5wdXRzOiBUZW5zb3J8VGVuc29yW10sIGt3YXJnczogS3dhcmdzKTogVGVuc29yfFRlbnNvcltdIHtcbiAgICByZXR1cm4gdGlkeSgoKSA9PiB7XG4gICAgICBpbnB1dHMgPSBnZW5lcmljX3V0aWxzLnRvTGlzdChpbnB1dHMpO1xuICAgICAgY29uc3QgZmVlZERpY3QgPSBuZXcgRmVlZERpY3QoKTtcbiAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgdGhpcy5pbnB1dHMubGVuZ3RoOyArK2kpIHtcbiAgICAgICAgZmVlZERpY3QuYWRkKHRoaXMuaW5wdXRzW2ldLCBpbnB1dHNbaV0pO1xuICAgICAgfVxuICAgICAgcmV0dXJuIGV4ZWN1dGUodGhpcy5vdXRwdXRzLCBmZWVkRGljdCwga3dhcmdzKSBhcyBUZW5zb3IgfCBUZW5zb3JbXTtcbiAgICB9KTtcbiAgfVxuXG4gIC8qKlxuICAgKiBDb21wdXRlcyBhbiBvdXRwdXQgbWFzayB0ZW5zb3IuXG4gICAqXG4gICAqIEBwYXJhbSBpbnB1dHMgVGVuc29yIG9yIGxpc3Qgb2YgdGVuc29ycy5cbiAgICogQHBhcmFtIG1hc2sgVGVuc29yIG9yIGxpc3Qgb2YgdGVuc29ycy5cbiAgICpcbiAgICogQHJldHVybiBudWxsIG9yIGEgdGVuc29yIChvciBsaXN0IG9mIHRlbnNvcnMsIG9uZSBwZXIgb3V0cHV0IHRlbnNvciBvZiB0aGVcbiAgICogbGF5ZXIpLlxuICAgKi9cbiAgb3ZlcnJpZGUgY29tcHV0ZU1hc2soaW5wdXRzOiBUZW5zb3J8VGVuc29yW10sIG1hc2s/OiBUZW5zb3J8VGVuc29yW10pOiBUZW5zb3JcbiAgICAgIHxUZW5zb3JbXSB7XG4gICAgcmV0dXJuIHRpZHkoKCkgPT4ge1xuICAgICAgaW5wdXRzID0gZ2VuZXJpY191dGlscy50b0xpc3QoaW5wdXRzKTtcbiAgICAgIGxldCBtYXNrczogVGVuc29yW107XG4gICAgICBpZiAobWFzayA9PSBudWxsKSB7XG4gICAgICAgIG1hc2tzID0gZ2VuZXJpY191dGlscy5weUxpc3RSZXBlYXQobnVsbCwgaW5wdXRzLmxlbmd0aCk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBtYXNrcyA9IGdlbmVyaWNfdXRpbHMudG9MaXN0KG1hc2spO1xuICAgICAgfVxuICAgICAgLy8gVE9ETyhtaWNoYWVsdGVycnkpOiBBZGQgc3VwcG9ydCBmb3IgbWFzayBjYWNoaW5nLlxuICAgICAgcmV0dXJuIHRoaXMucnVuSW50ZXJuYWxHcmFwaChpbnB1dHMsIG1hc2tzKVsxXTtcbiAgICB9KTtcbiAgfVxuXG4gIC8qKlxuICAgKiBDb21wdXRlcyB0aGUgb3V0cHV0IHNoYXBlIG9mIHRoZSBsYXllci5cbiAgICpcbiAgICogQXNzdW1lcyB0aGF0IHRoZSBsYXllciB3aWxsIGJlIGJ1aWx0IHRvIG1hdGNoIHRoYXQgaW5wdXQgc2hhcGUgcHJvdmlkZWQuXG4gICAqXG4gICAqIEBwYXJhbSBpbnB1dFNoYXBlIEEgc2hhcGUgKHR1cGxlIG9mIGludGVnZXJzKSBvciBhIGxpc3Qgb2Ygc2hhcGUgdHVwbGVzXG4gICAqICAgKG9uZSBwZXIgb3V0cHV0IHRlbnNvciBvZiB0aGUgbGF5ZXIpLiBTaGFwZSB0dXBsZXMgY2FuIGluY2x1ZGUgbnVsbCBmb3JcbiAgICogICBmcmVlIGRpbWVuc2lvbnMsIGluc3RlYWQgb2YgYW4gaW50ZWdlci5cbiAgICovXG4gIG92ZXJyaWRlIGNvbXB1dGVPdXRwdXRTaGFwZShpbnB1dFNoYXBlOiBTaGFwZXxTaGFwZVtdKTogU2hhcGV8U2hhcGVbXSB7XG4gICAgY29uc3QgaW5wdXRTaGFwZXMgPSB0eXBlc191dGlscy5ub3JtYWxpemVTaGFwZUxpc3QoaW5wdXRTaGFwZSk7XG4gICAgaWYgKGlucHV0U2hhcGVzLmxlbmd0aCAhPT0gdGhpcy5pbnB1dExheWVycy5sZW5ndGgpIHtcbiAgICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKFxuICAgICAgICAgIGBJbnZhbGlkIGlucHV0U2hhcGUgYXJndW1lbnQgJHtpbnB1dFNoYXBlfTogYCArXG4gICAgICAgICAgYG1vZGVsIGhhcyAke3RoaXMuaW5wdXRMYXllcnMubGVuZ3RofSB0ZW5zb3IgaW5wdXRzLmApO1xuICAgIH1cblxuICAgIC8vIFRPRE8obWljaGFlbHRlcnJ5KTogQWRkIGNhY2hpbmdcbiAgICBjb25zdCBsYXllcnNUb091dHB1dFNoYXBlczoge1tzaGFwZUtleTogc3RyaW5nXTogU2hhcGV9ID0ge307XG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCBpbnB1dFNoYXBlcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29uc3QgbGF5ZXIgPSB0aGlzLmlucHV0TGF5ZXJzW2ldO1xuICAgICAgY29uc3QgaW5wdXRTaGFwZSA9IGlucHV0U2hhcGVzW2ldO1xuICAgICAgLy8gSXQncyBhbiBpbnB1dCBsYXllcjogY29tcHV0ZU91dHB1dFNoYXBlIGlzIGlkZW50aXR5LFxuICAgICAgLy8gYW5kIHRoZXJlIGlzIG9ubHkgb25lIG5vZGUgYW5kIG9uZSB0ZW5zb3Igb3V0cHV0LlxuICAgICAgY29uc3Qgc2hhcGVLZXkgPSBsYXllci5uYW1lICsgJ18wXzAnO1xuICAgICAgbGF5ZXJzVG9PdXRwdXRTaGFwZXNbc2hhcGVLZXldID0gaW5wdXRTaGFwZTtcbiAgICB9XG5cbiAgICBjb25zdCBkZXB0aEtleXMgPSBPYmplY3Qua2V5cyh0aGlzLm5vZGVzQnlEZXB0aClcbiAgICAgICAgICAgICAgICAgICAgICAgICAgLm1hcCh4ID0+IHBhcnNlSW50KHgsIDEwKSlcbiAgICAgICAgICAgICAgICAgICAgICAgICAgLnNvcnQoZ2VuZXJpY191dGlscy5yZXZlcnNlTnVtYmVyQ29tcGFyZSk7XG4gICAgLy8gSXRlcmF0ZSBvdmVyIG5vZGVzLCBieSBkZXB0aCBsZXZlbC5cbiAgICBpZiAoZGVwdGhLZXlzLmxlbmd0aCA+IDEpIHtcbiAgICAgIGZvciAoY29uc3QgZGVwdGggb2YgZGVwdGhLZXlzKSB7XG4gICAgICAgIGNvbnN0IG5vZGVzID0gdGhpcy5ub2Rlc0J5RGVwdGhbZGVwdGhdO1xuICAgICAgICBmb3IgKGNvbnN0IG5vZGUgb2Ygbm9kZXMpIHtcbiAgICAgICAgICAvLyBUaGlzIGlzIGFsd2F5cyBhIHNpbmdsZSBsYXllciwgbmV2ZXIgYSBsaXN0LlxuICAgICAgICAgIGNvbnN0IGxheWVyID0gbm9kZS5vdXRib3VuZExheWVyO1xuICAgICAgICAgIGlmICh0aGlzLmlucHV0TGF5ZXJzLm1hcCh4ID0+IHguaWQpLmluZGV4T2YobGF5ZXIuaWQpICE9PSAtMSkge1xuICAgICAgICAgICAgLy8gV2UndmUgYWxyZWFkeSBjb3ZlcmVkIHRoZSBpbnB1dCBsYXllcnMgYSBmZXcgbGluZXMgYWJvdmUuXG4gICAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgICB9XG4gICAgICAgICAgLy8gUG90ZW50aWFsbHkgcmVkdW5kYW50IGxpc3QsIHNhbWUgc2l6ZSBvZiBub2RlLmlucHV0VGVuc29ycy5cbiAgICAgICAgICBjb25zdCBpbnB1dFNoYXBlczogU2hhcGVbXSA9IFtdO1xuICAgICAgICAgIGZvciAobGV0IGogPSAwOyBqIDwgbm9kZS5pbmJvdW5kTGF5ZXJzLmxlbmd0aDsgaisrKSB7XG4gICAgICAgICAgICBjb25zdCBpbmJvdW5kTGF5ZXIgPSBub2RlLmluYm91bmRMYXllcnNbal07XG4gICAgICAgICAgICBjb25zdCBub2RlSW5kZXggPSBub2RlLm5vZGVJbmRpY2VzW2pdO1xuICAgICAgICAgICAgY29uc3QgdGVuc29ySW5kZXggPSBub2RlLnRlbnNvckluZGljZXNbal07XG4gICAgICAgICAgICBjb25zdCBzaGFwZUtleSA9IGAke2luYm91bmRMYXllci5uYW1lfV8ke25vZGVJbmRleH1fJHt0ZW5zb3JJbmRleH1gO1xuICAgICAgICAgICAgY29uc3QgaW5wdXRTaGFwZSA9IGxheWVyc1RvT3V0cHV0U2hhcGVzW3NoYXBlS2V5XTtcbiAgICAgICAgICAgIGlucHV0U2hhcGVzLnB1c2goaW5wdXRTaGFwZSk7XG4gICAgICAgICAgfVxuXG4gICAgICAgICAgY29uc3Qgb3V0cHV0U2hhcGUgPSBsYXllci5jb21wdXRlT3V0cHV0U2hhcGUoXG4gICAgICAgICAgICAgIGdlbmVyaWNfdXRpbHMuc2luZ2xldG9uT3JBcnJheShpbnB1dFNoYXBlcykpO1xuXG4gICAgICAgICAgY29uc3Qgb3V0cHV0U2hhcGVzID0gdHlwZXNfdXRpbHMubm9ybWFsaXplU2hhcGVMaXN0KG91dHB1dFNoYXBlKTtcbiAgICAgICAgICBjb25zdCBub2RlSW5kZXggPSBsYXllci5pbmJvdW5kTm9kZXMuaW5kZXhPZihub2RlKTtcbiAgICAgICAgICBmb3IgKGxldCBqID0gMDsgaiA8IG91dHB1dFNoYXBlcy5sZW5ndGg7IGorKykge1xuICAgICAgICAgICAgY29uc3Qgc2hhcGVLZXkgPSBgJHtsYXllci5uYW1lfV8ke25vZGVJbmRleH1fJHtqfWA7XG4gICAgICAgICAgICBsYXllcnNUb091dHB1dFNoYXBlc1tzaGFwZUtleV0gPSBvdXRwdXRTaGFwZXNbal07XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuXG4gICAgLy8gUmVhZCBmaW5hbCBvdXRwdXQgc2hhcGVzIGZyb20gbGF5ZXJzVG9PdXRwdXRTaGFwZXMuXG4gICAgY29uc3Qgb3V0cHV0U2hhcGVzOiBTaGFwZVtdID0gW107XG4gICAgY29uc3Qgb3V0cHV0U2hhcGVLZXlzOiBzdHJpbmdbXSA9IFtdO1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgdGhpcy5vdXRwdXRMYXllcnMubGVuZ3RoOyBpKyspIHtcbiAgICAgIGNvbnN0IGxheWVyID0gdGhpcy5vdXRwdXRMYXllcnNbaV07XG4gICAgICBjb25zdCBub2RlSW5kZXggPSB0aGlzLm91dHB1dExheWVyc05vZGVJbmRpY2VzW2ldO1xuICAgICAgY29uc3QgdGVuc29ySW5kZXggPSB0aGlzLm91dHB1dExheWVyc1RlbnNvckluZGljZXNbaV07XG4gICAgICBjb25zdCBzaGFwZUtleSA9IGAke2xheWVyLm5hbWV9XyR7bm9kZUluZGV4fV8ke3RlbnNvckluZGV4fWA7XG4gICAgICBvdXRwdXRTaGFwZUtleXMucHVzaChzaGFwZUtleSk7XG4gICAgfVxuXG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCBvdXRwdXRTaGFwZUtleXMubGVuZ3RoOyBpKyspIHtcbiAgICAgIGNvbnN0IGtleSA9IG91dHB1dFNoYXBlS2V5c1tpXTtcbiAgICAgIGdlbmVyaWNfdXRpbHMuYXNzZXJ0KGtleSBpbiBsYXllcnNUb091dHB1dFNoYXBlcyk7XG4gICAgICBvdXRwdXRTaGFwZXMucHVzaChsYXllcnNUb091dHB1dFNoYXBlc1trZXldKTtcbiAgICB9XG5cbiAgICAvLyBUT0RPKG1pY2hhZWx0ZXJyeSk6IFVwZGF0ZSBjYWNoZVxuICAgIHJldHVybiBnZW5lcmljX3V0aWxzLnNpbmdsZXRvbk9yQXJyYXkob3V0cHV0U2hhcGVzKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBDb21wdXRlcyBvdXRwdXQgdGVuc29ycyBmb3IgbmV3IGlucHV0cy5cbiAgICpcbiAgICogTm90ZTpcbiAgICogICAtIEV4cGVjdHMgYGlucHV0c2AgdG8gYmUgYSBsaXN0IChwb3RlbnRpYWxseSB3aXRoIDEgZWxlbWVudCkuXG4gICAqXG4gICAqIEBwYXJhbSBpbnB1dHMgTGlzdCBvZiB0ZW5zb3JzXG4gICAqIEBwYXJhbSBtYXNrcyBMaXN0IG9mIG1hc2tzICh0ZW5zb3JzIG9yIG51bGwpLlxuICAgKiBAcmV0dXJuIFRocmVlIGxpc3RzOiBvdXRwdXRUZW5zb3JzLCBvdXRwdXRNYXNrcywgb3V0cHV0U2hhcGVzXG4gICAqL1xuICBwcm90ZWN0ZWQgcnVuSW50ZXJuYWxHcmFwaChpbnB1dHM6IFRlbnNvcltdLCBtYXNrcz86IFRlbnNvcltdKTpcbiAgICAgIFtUZW5zb3JbXSwgVGVuc29yW10sIFNoYXBlW11dIHtcbiAgICBpZiAobWFza3MgPT0gbnVsbCkge1xuICAgICAgbWFza3MgPSBnZW5lcmljX3V0aWxzLnB5TGlzdFJlcGVhdChudWxsLCBpbnB1dHMubGVuZ3RoKTtcbiAgICB9XG5cbiAgICAvLyBEaWN0aW9uYXJ5IG1hcHBpbmcgcmVmZXJlbmNlIHRlbnNvcnMgdG8gdHVwbGVzXG4gICAgLy8gKGNvbXB1dGVkIHRlbnNvciwgY29tcHV0ZSBtYXNrKVxuICAgIC8vIHdlIGFzc3VtZSBhIDE6MSBtYXBwaW5nIGZyb20gdGVuc29yIHRvIG1hc2tcbiAgICAvLyBUT0RPOiByYWlzZSBleGNlcHRpb24gd2hlbiBhIGAuY29tcHV0ZU1hc2soKWAgY2FsbFxuICAgIC8vIGRvZXMgbm90IHJldHVybiBhIGxpc3QgdGhlIHNhbWUgc2l6ZSBhcyBgY2FsbGBcbiAgICBjb25zdCB0ZW5zb3JNYXA6IHtbdGVuc29ySUQ6IHN0cmluZ106IFtUZW5zb3IsIFRlbnNvcl19ID0ge307XG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCB0aGlzLmlucHV0cy5sZW5ndGg7ICsraSkge1xuICAgICAgY29uc3QgeCA9IHRoaXMuaW5wdXRzW2ldO1xuICAgICAgY29uc3QgeSA9IGlucHV0c1tpXTtcbiAgICAgIGNvbnN0IG1hc2sgPSBtYXNrc1tpXTtcbiAgICAgIHRlbnNvck1hcFt4LmlkXSA9IFt5LCBtYXNrXTtcbiAgICB9XG5cbiAgICBjb25zdCBkZXB0aEtleXMgPSBPYmplY3Qua2V5cyh0aGlzLm5vZGVzQnlEZXB0aClcbiAgICAgICAgICAgICAgICAgICAgICAgICAgLm1hcCh4ID0+IHBhcnNlSW50KHgsIDEwKSlcbiAgICAgICAgICAgICAgICAgICAgICAgICAgLnNvcnQoZ2VuZXJpY191dGlscy5yZXZlcnNlTnVtYmVyQ29tcGFyZSk7XG4gICAgZm9yIChjb25zdCBkZXB0aCBvZiBkZXB0aEtleXMpIHtcbiAgICAgIGNvbnN0IG5vZGVzID0gdGhpcy5ub2Rlc0J5RGVwdGhbZGVwdGhdO1xuICAgICAgZm9yIChjb25zdCBub2RlIG9mIG5vZGVzKSB7XG4gICAgICAgIC8vIFRoaXMgaXMgYWx3YXlzIGEgc2luZ2xlIGxheWVyLCBuZXZlciBhIGxpc3QuXG4gICAgICAgIGNvbnN0IGxheWVyID0gbm9kZS5vdXRib3VuZExheWVyO1xuICAgICAgICBjb25zdCByZWZlcmVuY2VJbnB1dFRlbnNvcnMgPSBub2RlLmlucHV0VGVuc29ycztcbiAgICAgICAgY29uc3QgcmVmZXJlbmNlT3V0cHV0VGVuc29ycyA9IG5vZGUub3V0cHV0VGVuc29ycztcblxuICAgICAgICAvLyBJZiBhbGwgcHJldmlvdXMgaW5wdXQgdGVuc29ycyBhcmUgYXZhaWxhYmxlIGluIHRlbnNvck1hcCxcbiAgICAgICAgLy8gdGhlbiBjYWxsIG5vZGUuaW5ib3VuZExheWVyIG9uIHRoZW0uXG4gICAgICAgIC8vIExpc3Qgb2YgdHVwbGVzIFtpbnB1dCwgbWFza106XG4gICAgICAgIGNvbnN0IGNvbXB1dGVkRGF0YSA9IG5ldyBBcnJheTxbVGVuc29yLCBUZW5zb3JdPigpO1xuICAgICAgICBmb3IgKGNvbnN0IHggb2YgcmVmZXJlbmNlSW5wdXRUZW5zb3JzKSB7XG4gICAgICAgICAgaWYgKHguaWQgaW4gdGVuc29yTWFwKSB7XG4gICAgICAgICAgICBjb21wdXRlZERhdGEucHVzaCh0ZW5zb3JNYXBbeC5pZF0pO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICBpZiAoY29tcHV0ZWREYXRhLmxlbmd0aCA9PT0gcmVmZXJlbmNlSW5wdXRUZW5zb3JzLmxlbmd0aCkge1xuICAgICAgICAgIC8vIFRPRE8obWljaGFlbHRlcnJ5KTogQWRkIEsubmFtZV9zY29wZSBoZXJlLCBpZiB3ZSBuZWVkIGl0LlxuICAgICAgICAgIGxldCBrd2FyZ3M6IEt3YXJncyA9IHt9O1xuICAgICAgICAgIGxldCBjb21wdXRlZFRlbnNvcnM6IFRlbnNvcltdO1xuICAgICAgICAgIGxldCBjb21wdXRlZE1hc2tzOiBUZW5zb3JbXTtcbiAgICAgICAgICBsZXQgb3V0cHV0VGVuc29yczogVGVuc29yW107XG4gICAgICAgICAgbGV0IG91dHB1dE1hc2tzOiBUZW5zb3JbXTtcbiAgICAgICAgICAvLyBjYWxsIGxheWVyXG4gICAgICAgICAgaWYgKG5vZGUuY2FsbEFyZ3MgIT0gbnVsbCkge1xuICAgICAgICAgICAga3dhcmdzID0gbm9kZS5jYWxsQXJncztcbiAgICAgICAgICB9XG4gICAgICAgICAgaWYgKGNvbXB1dGVkRGF0YS5sZW5ndGggPT09IDEpIHtcbiAgICAgICAgICAgIGNvbnN0IFtjb21wdXRlZFRlbnNvciwgY29tcHV0ZWRNYXNrXSA9IGNvbXB1dGVkRGF0YVswXTtcbiAgICAgICAgICAgIGlmIChrd2FyZ3NbJ21hc2snXSA9PSBudWxsKSB7XG4gICAgICAgICAgICAgIGt3YXJnc1snbWFzayddID0gY29tcHV0ZWRNYXNrO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgb3V0cHV0VGVuc29ycyA9XG4gICAgICAgICAgICAgICAgZ2VuZXJpY191dGlscy50b0xpc3QobGF5ZXIuY2FsbChjb21wdXRlZFRlbnNvciwga3dhcmdzKSk7XG4gICAgICAgICAgICBvdXRwdXRNYXNrcyA9IGdlbmVyaWNfdXRpbHMudG9MaXN0KFxuICAgICAgICAgICAgICAgIGxheWVyLmNvbXB1dGVNYXNrKGNvbXB1dGVkVGVuc29yLCBjb21wdXRlZE1hc2spKTtcbiAgICAgICAgICAgIGNvbXB1dGVkVGVuc29ycyA9IFtjb21wdXRlZFRlbnNvcl07XG4gICAgICAgICAgICBjb21wdXRlZE1hc2tzID0gW2NvbXB1dGVkTWFza107XG4gICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGNvbXB1dGVkVGVuc29ycyA9IGNvbXB1dGVkRGF0YS5tYXAoeCA9PiB4WzBdKTtcbiAgICAgICAgICAgIGNvbXB1dGVkTWFza3MgPSBjb21wdXRlZERhdGEubWFwKHggPT4geFsxXSk7XG4gICAgICAgICAgICBpZiAoa3dhcmdzWydtYXNrJ10gPT0gbnVsbCkge1xuICAgICAgICAgICAgICBrd2FyZ3NbJ21hc2snXSA9IGNvbXB1dGVkTWFza3M7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBvdXRwdXRUZW5zb3JzID1cbiAgICAgICAgICAgICAgICBnZW5lcmljX3V0aWxzLnRvTGlzdChsYXllci5jYWxsKGNvbXB1dGVkVGVuc29ycywga3dhcmdzKSk7XG4gICAgICAgICAgICBvdXRwdXRNYXNrcyA9IGdlbmVyaWNfdXRpbHMudG9MaXN0KFxuICAgICAgICAgICAgICAgIGxheWVyLmNvbXB1dGVNYXNrKGNvbXB1dGVkVGVuc29ycywgY29tcHV0ZWRNYXNrcykpO1xuICAgICAgICAgIH1cblxuICAgICAgICAgIGlmIChsYXllci5hY3Rpdml0eVJlZ3VsYXJpemVyKSB7XG4gICAgICAgICAgICB0aHJvdyBuZXcgTm90SW1wbGVtZW50ZWRFcnJvcihcbiAgICAgICAgICAgICAgICAnTGF5ZXJzTW9kZWwgaW52b2NhdGlvbiB3aXRoIGNvbmNyZXRlIFRlbnNvciB2YWx1ZShzKSBpbiB0aGUgJyArXG4gICAgICAgICAgICAgICAgJ3ByZXNlbmNlIG9mIGFjdGl2aXR5IHJlZ3VsYXJpemVyKHMpIGlzIG5vdCBzdXBwb3J0ZWQgeWV0LicpO1xuICAgICAgICAgIH1cbiAgICAgICAgICAvLyBUT0RPKG1pY2hhZWx0ZXJyeSk6IEFkZCBtb2RlbCB1cGRhdGVzIGFuZCBsb3NzZXNcblxuICAgICAgICAgIC8vIFVwZGF0ZSB0ZW5zb3IgbWFwLlxuICAgICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgcmVmZXJlbmNlT3V0cHV0VGVuc29ycy5sZW5ndGg7ICsraSkge1xuICAgICAgICAgICAgY29uc3QgeCA9IHJlZmVyZW5jZU91dHB1dFRlbnNvcnNbaV07XG4gICAgICAgICAgICBjb25zdCB5ID0gb3V0cHV0VGVuc29yc1tpXTtcbiAgICAgICAgICAgIGNvbnN0IG1hc2sgPSBvdXRwdXRNYXNrc1tpXTtcbiAgICAgICAgICAgIHRlbnNvck1hcFt4LmlkXSA9IFt5LCBtYXNrXTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICB9XG5cbiAgICBjb25zdCBvdXRwdXRUZW5zb3JzOiBUZW5zb3JbXSA9IFtdO1xuICAgIGNvbnN0IG91dHB1dE1hc2tzOiBUZW5zb3JbXSA9IFtdO1xuICAgIGNvbnN0IG91dHB1dFNoYXBlczogU2hhcGVbXSA9IFtdO1xuICAgIGZvciAoY29uc3QgeCBvZiB0aGlzLm91dHB1dHMpIHtcbiAgICAgIGdlbmVyaWNfdXRpbHMuYXNzZXJ0KFxuICAgICAgICAgIHguaWQgaW4gdGVuc29yTWFwLCBgQ291bGQgbm90IGNvbXB1dGUgb3V0cHV0ICR7eC5uYW1lfSA6ICR7eC5pZH1gKTtcbiAgICAgIGNvbnN0IFt0ZW5zb3IsIG1hc2tdID0gdGVuc29yTWFwW3guaWRdO1xuICAgICAgb3V0cHV0U2hhcGVzLnB1c2godGVuc29yLnNoYXBlKTtcbiAgICAgIG91dHB1dFRlbnNvcnMucHVzaCh0ZW5zb3IpO1xuICAgICAgb3V0cHV0TWFza3MucHVzaChtYXNrKTtcbiAgICB9XG5cbiAgICAvLyBUT0RPKG1pY2hhZWx0ZXJyeSk6IEFkZCBzdXBwb3J0IGZvciBjYWNoZXMuXG4gICAgcmV0dXJuIFtvdXRwdXRUZW5zb3JzLCBvdXRwdXRNYXNrcywgb3V0cHV0U2hhcGVzXTtcbiAgfVxuXG4gIC8qKlxuICAgKiBCdWlsZHMgYSBtYXAgb2YgaW50ZXJuYWwgbm9kZSBrZXlzIHRvIG5vZGUgb3JkZXJpbmcuXG4gICAqIFVzZWQgaW4gc2VyaWFsaXphaW9uIGEgbm9kZSBvcmRlcmluZ3MgbWF5IGNoYW5nZSBhcyB1bnVzZWQgbm9kZXMgYXJlXG4gICAqIGRyb3BwZWQuIFBvcnRpbmcgTm90ZTogIFRoaXMgaGVscGVyIG1ldGhvZCB3YXMgcHVsbGVkIG91dCBvZiBnZXRDb25maWcgdG9cbiAgICogaW1wcm92ZSByZWFkYWJpbGl0eS5cbiAgICogQHBhcmFtIGxheWVycyBBbiBhcnJheSBvZiBMYXllcnMgaW4gdGhlIG1vZGVsLlxuICAgKiBAcmV0dXJucyBNYXAgb2YgTm9kZSBLZXlzIHRvIGluZGV4IG9yZGVyIHdpdGhpbiB0aGUgbGF5ZXIuXG4gICAqL1xuICBwcml2YXRlIGJ1aWxkTm9kZUNvbnZlcnNpb25NYXAobGF5ZXJzOiBMYXllcltdKToge1tub2RlS2V5OiBzdHJpbmddOiBudW1iZXJ9IHtcbiAgICBjb25zdCBub2RlQ29udmVyc2lvbk1hcDoge1tub2RlS2V5OiBzdHJpbmddOiBudW1iZXJ9ID0ge307XG4gICAgbGV0IGtlcHROb2RlczogbnVtYmVyO1xuICAgIGZvciAoY29uc3QgbGF5ZXIgb2YgdGhpcy5sYXllcnMpIHtcbiAgICAgIGtlcHROb2RlcyA9IGxheWVyIGluc3RhbmNlb2YgQ29udGFpbmVyID8gMSA6IDA7XG4gICAgICBmb3IgKGxldCBvcmlnaW5hbE5vZGVJbmRleCA9IDA7XG4gICAgICAgICAgIG9yaWdpbmFsTm9kZUluZGV4IDwgbGF5ZXIuaW5ib3VuZE5vZGVzLmxlbmd0aDsgb3JpZ2luYWxOb2RlSW5kZXgrKykge1xuICAgICAgICBjb25zdCBub2RlS2V5ID0gQ29udGFpbmVyLm5vZGVLZXkobGF5ZXIsIG9yaWdpbmFsTm9kZUluZGV4KTtcbiAgICAgICAgaWYgKHRoaXMuY29udGFpbmVyTm9kZXMuaGFzKG5vZGVLZXkpKSB7XG4gICAgICAgICAgLy8gaS5lLiB3ZSBtYXJrIGl0IHRvIGJlIHNhdmVkXG4gICAgICAgICAgbm9kZUNvbnZlcnNpb25NYXBbbm9kZUtleV0gPSBrZXB0Tm9kZXM7XG4gICAgICAgICAga2VwdE5vZGVzICs9IDE7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIG5vZGVDb252ZXJzaW9uTWFwO1xuICB9XG5cbiAgLyoqXG4gICAqIFJldHJpZXZlcyBhIGxheWVyIGJhc2VkIG9uIGVpdGhlciBpdHMgbmFtZSAodW5pcXVlKSBvciBpbmRleC5cbiAgICpcbiAgICogSW5kaWNlcyBhcmUgYmFzZWQgb24gb3JkZXIgb2YgaG9yaXpvbnRhbCBncmFwaCB0cmF2ZXJzYWwgKGJvdHRvbS11cCkuXG4gICAqXG4gICAqIElmIGJvdGggYG5hbWVgIGFuZCBgaW5kZXhgIGFyZSBzcGVjaWZpZWQsIGBpbmRleGAgdGFrZXMgcHJlY2VkZW5jZS5cbiAgICpcbiAgICogQHBhcmFtIG5hbWUgTmFtZSBvZiBsYXllci5cbiAgICogQHBhcmFtIGluZGV4IEluZGV4IG9mIGxheWVyLlxuICAgKiBAcmV0dXJucyBBIExheWVyIGluc3RhbmNlLlxuICAgKiBAdGhyb3dzIFZhbHVlRXJyb3I6IEluIGNhc2Ugb2YgaW52YWxpZCBsYXllciBuYW1lIG9yIGluZGV4LlxuICAgKlxuICAgKiBAZG9jIHtcbiAgICogICAgaGVhZGluZzogJ0xheWVycycsXG4gICAqICAgIHN1YmhlYWRpbmc6ICdDbGFzc2VzJyxcbiAgICogICAgbmFtZXNwYWNlOiAnbGF5ZXJzJyxcbiAgICogICAgc3ViY2xhc3NlczogWydMYXllcnNNb2RlbCddXG4gICAqIH1cbiAgICovXG4gIGdldExheWVyKG5hbWU6IHN0cmluZyk6IExheWVyO1xuICBnZXRMYXllcihpbmRleDogbnVtYmVyKTogTGF5ZXI7XG4gIGdldExheWVyKG5hbWU6IHN0cmluZywgaW5kZXg6IG51bWJlcik6IExheWVyO1xuICBnZXRMYXllcihuYW1lT3JJbmRleD86IHN0cmluZ3xudW1iZXIsIGluZGV4PzogbnVtYmVyKTogTGF5ZXIge1xuICAgIGlmIChpbmRleCAhPSBudWxsKSB7XG4gICAgICByZXR1cm4gdGhpcy5maW5kTGF5ZXIoaW5kZXgpO1xuICAgIH0gZWxzZSB7XG4gICAgICBpZiAobmFtZU9ySW5kZXggPT0gbnVsbCkge1xuICAgICAgICB0aHJvdyBuZXcgVmFsdWVFcnJvcignUHJvdmlkZSBlaXRoZXIgYSBsYXllciBuYW1lIG9yIGxheWVyIGluZGV4Jyk7XG4gICAgICB9XG4gICAgICBpZiAodHlwZW9mIG5hbWVPckluZGV4ID09PSAnbnVtYmVyJykge1xuICAgICAgICByZXR1cm4gdGhpcy5maW5kTGF5ZXIobmFtZU9ySW5kZXgpO1xuICAgICAgfVxuICAgIH1cblxuICAgIGZvciAoY29uc3QgbGF5ZXIgb2YgdGhpcy5sYXllcnMpIHtcbiAgICAgIGlmIChsYXllci5uYW1lID09PSBuYW1lT3JJbmRleCkge1xuICAgICAgICByZXR1cm4gbGF5ZXI7XG4gICAgICB9XG4gICAgfVxuICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKGBObyBzdWNoIGxheWVyOiAke25hbWVPckluZGV4fWApO1xuICB9XG5cbiAgZmluZExheWVyKGluZGV4OiBudW1iZXIpOiBMYXllciB7XG4gICAgaWYgKHRoaXMubGF5ZXJzLmxlbmd0aCA8PSBpbmRleCkge1xuICAgICAgdGhyb3cgbmV3IFZhbHVlRXJyb3IoXG4gICAgICAgICAgYFdhcyBhc2tlZCB0byByZXRyaWV2ZSBsYXllciBhdCBpbmRleCAke2luZGV4fSwgYnV0IG1vZGVsIG9ubHkgYCArXG4gICAgICAgICAgYGhhcyAke3RoaXMubGF5ZXJzLmxlbmd0aH0gbGF5ZXIocykuYCk7XG4gICAgfSBlbHNlIHtcbiAgICAgIHJldHVybiB0aGlzLmxheWVyc1tpbmRleF07XG4gICAgfVxuICB9XG5cbiAgLyoqXG4gICAqIFJldHJpZXZlcyB0aGUgQ29udGFpbmVyJ3MgY3VycmVudCBsb3NzIHZhbHVlcy5cbiAgICpcbiAgICogVXNlZCBmb3IgcmVndWxhcml6ZXJzIGR1cmluZyB0cmFpbmluZy5cbiAgICovXG4gIG92ZXJyaWRlIGNhbGN1bGF0ZUxvc3NlcygpOiBTY2FsYXJbXSB7XG4gICAgLy8gUG9ydGluZyBOb2RlOiBUaGlzIGlzIGFuIGF1Z21lbnRhdGlvbiB0byBDb250YWluZXIubG9zcyBpbiBQeUtlcmFzLlxuICAgIC8vICAgSW4gUHlLZXJhcywgQ29udGFpbmVyLmxvc3MgcmV0dXJucyBzeW1ib2xpYyB0ZW5zb3JzLiBIZXJlIGEgY29uY3JldGVcbiAgICAvLyAgIFRlbnNvciAoc3BlY2lmaWNhbGx5IFNjYWxhcikgdmFsdWVzIGFyZSByZXR1cm5lZC4gVGhpcyBpcyBkdWUgdG8gdGhlXG4gICAgLy8gICBpbXBlcmF0aXZlIGJhY2tlbmQuXG4gICAgcmV0dXJuIHRpZHkoKCkgPT4ge1xuICAgICAgY29uc3QgbG9zc2VzOiBTY2FsYXJbXSA9IFtdO1xuICAgICAgZm9yIChjb25zdCBsYXllciBvZiB0aGlzLmxheWVycykge1xuICAgICAgICBmb3IgKGxldCBub2RlSW5kZXggPSAwOyBub2RlSW5kZXggPCBsYXllci5pbmJvdW5kTm9kZXMubGVuZ3RoO1xuICAgICAgICAgICAgICsrbm9kZUluZGV4KSB7XG4gICAgICAgICAgY29uc3Qgbm9kZUtleSA9IENvbnRhaW5lci5ub2RlS2V5KGxheWVyLCBub2RlSW5kZXgpO1xuICAgICAgICAgIGlmICh0aGlzLmNvbnRhaW5lck5vZGVzLmhhcyhub2RlS2V5KSkge1xuICAgICAgICAgICAgbG9zc2VzLnB1c2goLi4ubGF5ZXIuY2FsY3VsYXRlTG9zc2VzKCkpO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfVxuICAgICAgLy8gVE9ETyhjYWlzKTogQWRkIGFueSB1bmNvbmRpdGlvbmFsIG1vZGVsLWxldmVsIGxvc3Nlcz9cbiAgICAgIHJldHVybiBsb3NzZXM7XG4gICAgfSk7XG4gIH1cblxuICBvdmVycmlkZSBnZXRDb25maWcoKTogc2VyaWFsaXphdGlvbi5Db25maWdEaWN0IHtcbiAgICBjb25zdCBjb25maWc6IHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCA9IHtuYW1lOiB0aGlzLm5hbWV9O1xuXG4gICAgLy8gQnVpbGQgYSBtYXAgZnJvbSBsYXllciB1bmlxdWUgbmFtZSAoc2VsZi5fbm9kZV9rZXkpXG4gICAgLy8gdG8gdGhlIGluZGV4IG9mIHRoZSBub2RlcyB0aGF0IGFyZSBzYXZlZCBpbiB0aGUgY29uZmlnLlxuICAgIC8vIE9ubHkgbm9kZXMgaW4gY29udGFpbmVyX25vZGVzIGFyZSBzYXZlZC5cbiAgICBjb25zdCBub2RlQ29udmVyc2lvbk1hcDoge1tub2RlS2V5OiBzdHJpbmddOiBudW1iZXJ9ID1cbiAgICAgICAgdGhpcy5idWlsZE5vZGVDb252ZXJzaW9uTWFwKHRoaXMubGF5ZXJzKTtcblxuICAgIC8vIFNlcmlhbGl6ZSBhbmQgc2F2ZSB0aGUgbGF5ZXJzIGluIGxheWVyQ29uZmlnc1xuICAgIGNvbnN0IGxheWVyQ29uZmlncyA9IFtdO1xuICAgIGZvciAoY29uc3QgbGF5ZXIgb2YgdGhpcy5sYXllcnMpIHtcbiAgICAgIGNvbnN0IGxheWVyQ2xhc3NOYW1lID0gbGF5ZXIuZ2V0Q2xhc3NOYW1lKCk7XG4gICAgICBjb25zdCBsYXllckNvbmZpZyA9IGxheWVyLmdldENvbmZpZygpO1xuICAgICAgY29uc3QgZmlsdGVyZWRJbmJvdW5kTm9kZXMgPSBbXTtcbiAgICAgIGZvciAobGV0IG9yaWdpbmFsTm9kZUluZGV4ID0gMDtcbiAgICAgICAgICAgb3JpZ2luYWxOb2RlSW5kZXggPCBsYXllci5pbmJvdW5kTm9kZXMubGVuZ3RoOyBvcmlnaW5hbE5vZGVJbmRleCsrKSB7XG4gICAgICAgIGNvbnN0IG5vZGUgPSBsYXllci5pbmJvdW5kTm9kZXNbb3JpZ2luYWxOb2RlSW5kZXhdO1xuICAgICAgICBjb25zdCBub2RlS2V5ID0gQ29udGFpbmVyLm5vZGVLZXkobGF5ZXIsIG9yaWdpbmFsTm9kZUluZGV4KTtcbiAgICAgICAgbGV0IGt3YXJncyA9IHt9O1xuICAgICAgICBpZiAodGhpcy5jb250YWluZXJOb2Rlcy5oYXMobm9kZUtleSkpIHtcbiAgICAgICAgICAvLyBUaGUgbm9kZSBpcyByZWxldmFudCB0byB0aGUgbW9kZWw6XG4gICAgICAgICAgLy8gYWRkIHRvIGZpbHRlcmVkSW5ib3VuZE5vZGVzLlxuICAgICAgICAgIGlmIChub2RlLmNhbGxBcmdzKSB7XG4gICAgICAgICAgICB0cnkge1xuICAgICAgICAgICAgICBKU09OLnN0cmluZ2lmeShub2RlLmNhbGxBcmdzKTtcbiAgICAgICAgICAgICAga3dhcmdzID0gbm9kZS5jYWxsQXJncztcbiAgICAgICAgICAgIH0gY2F0Y2ggKGVycikge1xuICAgICAgICAgICAgICBjb25zb2xlLndhcm4oXG4gICAgICAgICAgICAgICAgICBgTGF5ZXIgJHtsYXllci5uYW1lfSB3YXMgcGFzc2VkIGAgK1xuICAgICAgICAgICAgICAgICAgYG5vbi1zZXJpYWxpemFibGUga2V5d29yZCBhcmd1bWVudHM6IGAgK1xuICAgICAgICAgICAgICAgICAgYCR7bm9kZS5jYWxsQXJnc30uIFRoZXkgd2lsbCBub3QgYmUgaW5jbHVkZWQgYCArXG4gICAgICAgICAgICAgICAgICBgaW4gdGhlIHNlcmlhbGl6ZWQgbW9kZWwgKGFuZCB0aHVzIHdpbGwgYmUgYCArXG4gICAgICAgICAgICAgICAgICBgbWlzc2luZyBhdCBkZXNlcmlhbGl6YXRpb24gdGltZSkuYCk7XG4gICAgICAgICAgICAgIGt3YXJncyA9IHt9O1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH1cbiAgICAgICAgICBpZiAobm9kZS5pbmJvdW5kTGF5ZXJzLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgIGNvbnN0IG5vZGVEYXRhID0gW107XG4gICAgICAgICAgICBmb3IgKGxldCBpID0gMDsgaSA8IG5vZGUuaW5ib3VuZExheWVycy5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICBjb25zdCBpbmJvdW5kTGF5ZXIgPSBub2RlLmluYm91bmRMYXllcnNbaV07XG4gICAgICAgICAgICAgIGNvbnN0IG5vZGVJbmRleCA9IG5vZGUubm9kZUluZGljZXNbaV07XG4gICAgICAgICAgICAgIGNvbnN0IHRlbnNvckluZGV4ID0gbm9kZS50ZW5zb3JJbmRpY2VzW2ldO1xuICAgICAgICAgICAgICBjb25zdCBub2RlS2V5ID0gQ29udGFpbmVyLm5vZGVLZXkoaW5ib3VuZExheWVyLCBub2RlSW5kZXgpO1xuICAgICAgICAgICAgICBsZXQgbmV3Tm9kZUluZGV4ID0gbm9kZUNvbnZlcnNpb25NYXBbbm9kZUtleV07XG4gICAgICAgICAgICAgIGlmIChuZXdOb2RlSW5kZXggPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIG5ld05vZGVJbmRleCA9IDA7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgbm9kZURhdGEucHVzaChcbiAgICAgICAgICAgICAgICAgIFtpbmJvdW5kTGF5ZXIubmFtZSwgbmV3Tm9kZUluZGV4LCB0ZW5zb3JJbmRleCwga3dhcmdzXSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBmaWx0ZXJlZEluYm91bmROb2Rlcy5wdXNoKG5vZGVEYXRhKTtcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICAgIGNvbnN0IGRpY3Q6IHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCA9IHt9O1xuICAgICAgZGljdFsnbmFtZSddID0gbGF5ZXIubmFtZTtcbiAgICAgIGRpY3RbJ2NsYXNzTmFtZSddID0gbGF5ZXJDbGFzc05hbWU7XG4gICAgICBkaWN0Wydjb25maWcnXSA9IGxheWVyQ29uZmlnO1xuICAgICAgZGljdFsnaW5ib3VuZE5vZGVzJ10gPSBmaWx0ZXJlZEluYm91bmROb2RlcztcbiAgICAgIGxheWVyQ29uZmlncy5wdXNoKGRpY3QpO1xuICAgIH1cbiAgICBjb25maWdbJ2xheWVycyddID0gbGF5ZXJDb25maWdzO1xuICAgIC8vIEdhdGhlciBpbmZvIGFib3V0IGlucHV0cyBhbmQgb3V0cHV0c1xuICAgIGNvbnN0IG1vZGVsSW5wdXRzID0gW107XG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCB0aGlzLmlucHV0TGF5ZXJzLmxlbmd0aDsgaSsrKSB7XG4gICAgICBjb25zdCBsYXllciA9IHRoaXMuaW5wdXRMYXllcnNbaV07XG4gICAgICBjb25zdCBub2RlSW5kZXggPSB0aGlzLmlucHV0TGF5ZXJzTm9kZUluZGljZXNbaV07XG5cbiAgICAgIGNvbnN0IG5vZGVLZXkgPSBDb250YWluZXIubm9kZUtleShsYXllciwgbm9kZUluZGV4KTtcbiAgICAgIGlmICghdGhpcy5jb250YWluZXJOb2Rlcy5oYXMobm9kZUtleSkpIHtcbiAgICAgICAgY29udGludWU7XG4gICAgICB9XG4gICAgICBsZXQgbmV3Tm9kZUluZGV4ID0gbm9kZUNvbnZlcnNpb25NYXBbbm9kZUtleV07XG4gICAgICBpZiAobmV3Tm9kZUluZGV4ID09PSBudWxsIHx8IG5ld05vZGVJbmRleCA9PT0gdW5kZWZpbmVkKSB7XG4gICAgICAgIG5ld05vZGVJbmRleCA9IDA7XG4gICAgICB9XG4gICAgICBjb25zdCB0ZW5zb3JJbmRleCA9IHRoaXMuaW5wdXRMYXllcnNUZW5zb3JJbmRpY2VzW2ldO1xuICAgICAgbW9kZWxJbnB1dHMucHVzaChbbGF5ZXIubmFtZSwgbmV3Tm9kZUluZGV4LCB0ZW5zb3JJbmRleF0pO1xuICAgIH1cbiAgICBjb25maWdbJ2lucHV0TGF5ZXJzJ10gPSBtb2RlbElucHV0cztcblxuICAgIGNvbnN0IG1vZGVsT3V0cHV0cyA9IFtdO1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgdGhpcy5vdXRwdXRMYXllcnMubGVuZ3RoOyBpKyspIHtcbiAgICAgIGNvbnN0IGxheWVyID0gdGhpcy5vdXRwdXRMYXllcnNbaV07XG4gICAgICBjb25zdCBub2RlSW5kZXggPSB0aGlzLm91dHB1dExheWVyc05vZGVJbmRpY2VzW2ldO1xuXG4gICAgICBjb25zdCBub2RlS2V5ID0gQ29udGFpbmVyLm5vZGVLZXkobGF5ZXIsIG5vZGVJbmRleCk7XG4gICAgICBpZiAoIXRoaXMuY29udGFpbmVyTm9kZXMuaGFzKG5vZGVLZXkpKSB7XG4gICAgICAgIGNvbnRpbnVlO1xuICAgICAgfVxuICAgICAgbGV0IG5ld05vZGVJbmRleCA9IG5vZGVDb252ZXJzaW9uTWFwW25vZGVLZXldO1xuICAgICAgaWYgKG5ld05vZGVJbmRleCA9PT0gbnVsbCB8fCBuZXdOb2RlSW5kZXggPT09IHVuZGVmaW5lZCkge1xuICAgICAgICBuZXdOb2RlSW5kZXggPSAwO1xuICAgICAgfVxuICAgICAgY29uc3QgdGVuc29ySW5kZXggPSB0aGlzLm91dHB1dExheWVyc1RlbnNvckluZGljZXNbaV07XG4gICAgICBtb2RlbE91dHB1dHMucHVzaChbbGF5ZXIubmFtZSwgbmV3Tm9kZUluZGV4LCB0ZW5zb3JJbmRleF0pO1xuICAgIH1cbiAgICBjb25maWdbJ291dHB1dExheWVycyddID0gbW9kZWxPdXRwdXRzO1xuICAgIHJldHVybiBjb25maWc7XG4gIH1cblxuICAvKipcbiAgICogSW5zdGFudGlhdGVzIGEgTGF5ZXJzTW9kZWwgZnJvbSBpdHMgY29uZmlnIChvdXRwdXQgb2YgYGdldF9jb25maWcoKWApLlxuICAgKiBAcGFyYW0gY2xzIHRoZSBjbGFzcyB0byBjcmVhdGVcbiAgICogQHBhcmFtIGNvbmZpZyBMYXllcnNNb2RlbCBjb25maWcgZGljdGlvbmFyeS5cbiAgICogQHBhcmFtIGN1c3RvbU9iamVjdHMgQW4gb3B0aW9uYWwgZGljdGlvbmFyeSBvZiBjdXN0b20gb2JqZWN0cy5cbiAgICogQHBhcmFtIGZhc3RXZWlnaHRJbml0IE9wdGlvbmFsIGZsYWcgdG8gdXNlIGZhc3Qgd2VpZ2h0IGluaXRpYWxpemF0aW9uXG4gICAqICAgZHVyaW5nIGRlc2VyaWFsaXphdGlvbi4gVGhpcyBpcyBhcHBsaWNhYmxlIHRvIGNhc2VzIGluIHdoaWNoXG4gICAqICAgdGhlIGluaXRpYWxpemF0aW9uIHdpbGwgYmUgaW1tZWRpYXRlbHkgb3ZlcndyaXR0ZW4gYnkgbG9hZGVkIHdlaWdodFxuICAgKiAgIHZhbHVlcy4gRGVmYXVsdDogYGZhbHNlYC5cbiAgICogQHJldHVybnMgQSBMYXllcnNNb2RlbCBpbnN0YW5jZS5cbiAgICogQHRocm93cyBWYWx1ZUVycm9yOiBJbiBjYXNlIG9mIGltcHJvcGVybHkgZm9ybWF0dGVkIGNvbmZpZyBkaWN0LlxuICAgKi9cbiAgLyoqIEBub2NvbGxhcHNlICovXG4gIHN0YXRpYyBvdmVycmlkZSBmcm9tQ29uZmlnPFQgZXh0ZW5kcyBzZXJpYWxpemF0aW9uLlNlcmlhbGl6YWJsZT4oXG4gICAgICBjbHM6IHNlcmlhbGl6YXRpb24uU2VyaWFsaXphYmxlQ29uc3RydWN0b3I8VD4sXG4gICAgICBjb25maWc6IHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCxcbiAgICAgIGN1c3RvbU9iamVjdHMgPSB7fSBhcyBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3QsXG4gICAgICBmYXN0V2VpZ2h0SW5pdCA9IGZhbHNlKTogVCB7XG4gICAgLy8gTGF5ZXIgaW5zdGFuY2VzIGNyZWF0ZWQgZHVyaW5nXG4gICAgLy8gdGhlIGdyYXBoIHJlY29uc3RydWN0aW9uIHByb2Nlc3NcbiAgICBjb25zdCBjcmVhdGVkTGF5ZXJzOiB7W2xheWVyTmFtZTogc3RyaW5nXTogTGF5ZXJ9ID0ge307XG5cbiAgICAvLyBEaWN0aW9uYXJ5IG1hcHBpbmcgbGF5ZXIgaW5zdGFuY2VzIHRvXG4gICAgLy8gbm9kZSBkYXRhIHRoYXQgc3BlY2lmaWVzIGEgbGF5ZXIgY2FsbC5cbiAgICAvLyBJdCBhY3RzIGFzIGEgcXVldWUgdGhhdCBtYWludGFpbnMgYW55IHVucHJvY2Vzc2VkXG4gICAgLy8gbGF5ZXIgY2FsbCB1bnRpbCBpdCBiZWNvbWVzIHBvc3NpYmxlIHRvIHByb2Nlc3MgaXRcbiAgICAvLyAoaS5lLiB1bnRpbCB0aGUgaW5wdXQgdGVuc29ycyB0byB0aGUgY2FsbCBhbGwgZXhpc3QpLlxuICAgIGNvbnN0IHVucHJvY2Vzc2VkTm9kZXM6IHtbbGF5ZXI6IHN0cmluZ106IFRlbnNvcktleVdpdGhBcmdzQXJyYXlbXVtdfSA9IHt9O1xuICAgIGZ1bmN0aW9uIGFkZFVucHJvY2Vzc2VkTm9kZShcbiAgICAgICAgbGF5ZXI6IExheWVyLCBub2RlRGF0YTogVGVuc29yS2V5V2l0aEFyZ3NBcnJheVtdKSB7XG4gICAgICBpZiAoIShsYXllci5uYW1lIGluIHVucHJvY2Vzc2VkTm9kZXMpKSB7XG4gICAgICAgIHVucHJvY2Vzc2VkTm9kZXNbbGF5ZXIubmFtZV0gPSBbbm9kZURhdGFdO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgdW5wcm9jZXNzZWROb2Rlc1tsYXllci5uYW1lXS5wdXNoKG5vZGVEYXRhKTtcbiAgICAgIH1cbiAgICB9XG5cbiAgICBmdW5jdGlvbiBwcm9jZXNzTm9kZShsYXllcjogTGF5ZXIsIG5vZGVEYXRhOiBUZW5zb3JLZXlXaXRoQXJnc0FycmF5W10pIHtcbiAgICAgIGNvbnN0IGlucHV0VGVuc29yczogU3ltYm9saWNUZW5zb3JbXSA9IFtdO1xuICAgICAgbGV0IGt3YXJncztcbiAgICAgIGZvciAoY29uc3QgaW5wdXREYXRhIG9mIG5vZGVEYXRhKSB7XG4gICAgICAgIGNvbnN0IGluYm91bmRMYXllck5hbWUgPSBpbnB1dERhdGFbMF07XG4gICAgICAgIGNvbnN0IGluYm91bmROb2RlSW5kZXggPSBpbnB1dERhdGFbMV07XG4gICAgICAgIGNvbnN0IGluYm91bmRUZW5zb3JJbmRleCA9IGlucHV0RGF0YVsyXTtcblxuICAgICAgICBrd2FyZ3MgPSBpbnB1dERhdGFbM10gPT0gbnVsbCA/XG4gICAgICAgICAgICB7fSA6XG4gICAgICAgICAgICBpbnB1dERhdGFbM10gYXMgc2VyaWFsaXphdGlvbi5Db25maWdEaWN0O1xuICAgICAgICBpZiAoIShpbmJvdW5kTGF5ZXJOYW1lIGluIGNyZWF0ZWRMYXllcnMpKSB7XG4gICAgICAgICAgYWRkVW5wcm9jZXNzZWROb2RlKGxheWVyLCBub2RlRGF0YSk7XG4gICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIGNvbnN0IGluYm91bmRMYXllciA9IGNyZWF0ZWRMYXllcnNbaW5ib3VuZExheWVyTmFtZV07XG4gICAgICAgIGlmIChpbmJvdW5kTGF5ZXIuaW5ib3VuZE5vZGVzLmxlbmd0aCA8PSBpbmJvdW5kTm9kZUluZGV4KSB7XG4gICAgICAgICAgYWRkVW5wcm9jZXNzZWROb2RlKGxheWVyLCBub2RlRGF0YSk7XG4gICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG4gICAgICAgIGNvbnN0IGluYm91bmROb2RlID0gaW5ib3VuZExheWVyLmluYm91bmROb2Rlc1tpbmJvdW5kTm9kZUluZGV4XTtcbiAgICAgICAgaW5wdXRUZW5zb3JzLnB1c2goaW5ib3VuZE5vZGUub3V0cHV0VGVuc29yc1tpbmJvdW5kVGVuc29ySW5kZXhdKTtcbiAgICAgIH1cbiAgICAgIC8vIENhbGwgbGF5ZXIgb24gaXRzIGlucHV0cywgdGh1cyBjcmVhdGluZyB0aGUgbm9kZVxuICAgICAgLy8gYW5kIGJ1aWxkaW5nIHRoZSBsYXllciBpZiBuZWVkZWQuXG4gICAgICAvLyBOb3RlOiBUaGlzIGhhcyBFYWdlciB2cyBHcmFwaCBJbXBsaWNhdGlvbnMuXG4gICAgICBpZiAoaW5wdXRUZW5zb3JzLmxlbmd0aCA+IDApIHtcbiAgICAgICAgbGF5ZXIuYXBwbHkoXG4gICAgICAgICAgICBnZW5lcmljX3V0aWxzLnNpbmdsZXRvbk9yQXJyYXkoaW5wdXRUZW5zb3JzKSxcbiAgICAgICAgICAgIGt3YXJncyk7ICAvLyB3YXMgKioga3dhcmdzXG4gICAgICB9XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogRGVzZXJpYWxpemUgYSBsYXllciwgdGhlbiBjYWxsIGl0IG9uIGFwcHJvcHJpYXRlIGlucHV0cy5cbiAgICAgKiBAcGFyYW0gbGF5ZXJEYXRhOiBsYXllciBjb25maWcgZGljdC5cbiAgICAgKiBAdGhyb3dzIFZhbHVlRXJyb3I6IEluIGNhc2Ugb2YgaW1wcm9wZXJseSBmb3JtYXR0ZWQgYGxheWVyX2RhdGFgXG4gICAgICogZGljdC5cbiAgICAgKi9cbiAgICBmdW5jdGlvbiBwcm9jZXNzTGF5ZXIobGF5ZXJEYXRhOiBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3R8bnVsbCkge1xuICAgICAgY29uc3QgbGF5ZXJOYW1lID0gbGF5ZXJEYXRhWyduYW1lJ10gYXMgc3RyaW5nO1xuICAgICAgLy8gSW5zdGFudGlhdGUgbGF5ZXIuXG4gICAgICBjb25zdCBsYXllciA9XG4gICAgICAgICAgZGVzZXJpYWxpemVMYXllcihcbiAgICAgICAgICAgICAgbGF5ZXJEYXRhLFxuICAgICAgICAgICAgICBjb25maWdbJ2N1c3RvbU9iamVjdHMnXSAhPSBudWxsID9cbiAgICAgICAgICAgICAgICAgIGNvbmZpZ1snY3VzdG9tT2JqZWN0cyddIGFzIHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdCA6XG4gICAgICAgICAgICAgICAgICB7fSkgYXMgTGF5ZXI7XG4gICAgICBsYXllci5zZXRGYXN0V2VpZ2h0SW5pdER1cmluZ0J1aWxkKGZhc3RXZWlnaHRJbml0KTtcbiAgICAgIGNyZWF0ZWRMYXllcnNbbGF5ZXJOYW1lXSA9IGxheWVyO1xuICAgICAgLy8gR2F0aGVyIGxheWVyIGlucHV0cy5cbiAgICAgIGNvbnN0IGluYm91bmROb2Rlc0RhdGEgPVxuICAgICAgICAgIGxheWVyRGF0YVsnaW5ib3VuZE5vZGVzJ10gYXMgVGVuc29yS2V5V2l0aEFyZ3NBcnJheVtdW107XG4gICAgICBpbmJvdW5kTm9kZXNEYXRhLmZvckVhY2gobm9kZURhdGEgPT4ge1xuICAgICAgICBpZiAoIShub2RlRGF0YSBpbnN0YW5jZW9mIEFycmF5KSkge1xuICAgICAgICAgIHRocm93IG5ldyBWYWx1ZUVycm9yKFxuICAgICAgICAgICAgICBgQ29ycnVwdGVkIGNvbmZpZ3VyYXRpb24sIGV4cGVjdGVkIGFycmF5IGZvciBub2RlRGF0YTogJHtcbiAgICAgICAgICAgICAgICAgIG5vZGVEYXRhfWApO1xuICAgICAgICB9XG4gICAgICAgIC8vIFdlIGRvbid0IHByb2Nlc3Mgbm9kZXMgKGkuZS4gbWFrZSBsYXllciBjYWxscylcbiAgICAgICAgLy8gb24gdGhlIGZseSBiZWNhdXNlIHRoZSBpbmJvdW5kIG5vZGUgbWF5IG5vdCB5ZXQgZXhpc3QsXG4gICAgICAgIC8vIGluIGNhc2Ugb2YgbGF5ZXIgc2hhcmVkIGF0IGRpZmZlcmVudCB0b3BvbG9naWNhbCBkZXB0aHNcbiAgICAgICAgLy8gKGUuZy5hIG1vZGVsIHN1Y2ggYXMgQShCKEEoQih4KSkpKSlcbiAgICAgICAgYWRkVW5wcm9jZXNzZWROb2RlKGxheWVyLCBub2RlRGF0YSk7XG4gICAgICB9KTtcbiAgICB9XG5cbiAgICAvLyBGaXJzdCwgd2UgY3JlYXRlIGFsbCBsYXllcnMgYW5kIGVucXVldWUgbm9kZXMgdG8gYmUgcHJvY2Vzc2VkLlxuICAgIGNvbnN0IG5hbWUgPSBjb25maWdbJ25hbWUnXTtcbiAgICBjb25zdCBsYXllcnNGcm9tQ29uZmlnID0gY29uZmlnWydsYXllcnMnXSBhcyBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3RbXTtcbiAgICBmb3IgKGNvbnN0IGxheWVyRGF0YSBvZiBsYXllcnNGcm9tQ29uZmlnKSB7XG4gICAgICBwcm9jZXNzTGF5ZXIobGF5ZXJEYXRhKTtcbiAgICB9XG5cbiAgICAvLyBUaGVuIHdlIHByb2Nlc3Mgbm9kZXMgaW4gb3JkZXIgb2YgbGF5ZXIgZGVwdGguXG4gICAgLy8gTm9kZXMgdGhhdCBjYW5ub3QgeWV0IGJlIHByb2Nlc3NlZChpZiB0aGUgaW5ib3VuZCBub2RlXG4gICAgLy8gZG9lcyBub3QgeWV0IGV4aXN0KSBhcmUgcmUgLSBlbnF1ZXVlZCwgYW5kIHRoZSBwcm9jZXNzXG4gICAgLy8gaXMgcmVwZWF0ZWQgdW50aWwgYWxsIG5vZGVzIGFyZSBwcm9jZXNzZWQuXG4gICAgd2hpbGUgKCFnZW5lcmljX3V0aWxzLmlzT2JqZWN0RW1wdHkodW5wcm9jZXNzZWROb2RlcykpIHtcbiAgICAgIGZvciAoY29uc3QgbGF5ZXJEYXRhIG9mIGxheWVyc0Zyb21Db25maWcpIHtcbiAgICAgICAgY29uc3QgbGF5ZXIgPSBjcmVhdGVkTGF5ZXJzW2xheWVyRGF0YVsnbmFtZSddIGFzIHN0cmluZ107XG4gICAgICAgIGlmIChsYXllci5uYW1lIGluIHVucHJvY2Vzc2VkTm9kZXMpIHtcbiAgICAgICAgICBjb25zdCBjdXJyZW50VW5wcm9jZXNzZWROb2Rlc0ZvckxheWVyID0gdW5wcm9jZXNzZWROb2Rlc1tsYXllci5uYW1lXTtcbiAgICAgICAgICBkZWxldGUgdW5wcm9jZXNzZWROb2Rlc1tsYXllci5uYW1lXTtcbiAgICAgICAgICBmb3IgKGNvbnN0IG5vZGVEYXRhIG9mIGN1cnJlbnRVbnByb2Nlc3NlZE5vZGVzRm9yTGF5ZXIpIHtcbiAgICAgICAgICAgIHByb2Nlc3NOb2RlKGxheWVyLCBub2RlRGF0YSk7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuXG4gICAgY29uc3QgaW5wdXRUZW5zb3JzOiBTeW1ib2xpY1RlbnNvcltdID0gW107XG4gICAgY29uc3Qgb3V0cHV0VGVuc29yczogU3ltYm9saWNUZW5zb3JbXSA9IFtdO1xuICAgIGNvbnN0IGlucHV0TGF5ZXJzRnJvbUNvbmZpZyA9XG4gICAgICAgIGNvbmZpZ1snaW5wdXRMYXllcnMnXSBhcyBzZXJpYWxpemF0aW9uLkNvbmZpZ0RpY3RbXTtcbiAgICBmb3IgKGNvbnN0IGxheWVyRGF0YSBvZiBpbnB1dExheWVyc0Zyb21Db25maWcpIHtcbiAgICAgIGNvbnN0IGxheWVyTmFtZSA9IGxheWVyRGF0YVswXSBhcyBzdHJpbmc7XG4gICAgICBjb25zdCBub2RlSW5kZXggPSBsYXllckRhdGFbMV0gYXMgbnVtYmVyO1xuICAgICAgY29uc3QgdGVuc29ySW5kZXggPSBsYXllckRhdGFbMl0gYXMgbnVtYmVyO1xuICAgICAgZ2VuZXJpY191dGlscy5hc3NlcnQobGF5ZXJOYW1lIGluIGNyZWF0ZWRMYXllcnMpO1xuICAgICAgY29uc3QgbGF5ZXIgPSBjcmVhdGVkTGF5ZXJzW2xheWVyTmFtZV07XG4gICAgICBjb25zdCBsYXllck91dHB1dFRlbnNvcnMgPSBsYXllci5pbmJvdW5kTm9kZXNbbm9kZUluZGV4XS5vdXRwdXRUZW5zb3JzO1xuICAgICAgaW5wdXRUZW5zb3JzLnB1c2gobGF5ZXJPdXRwdXRUZW5zb3JzW3RlbnNvckluZGV4XSk7XG4gICAgfVxuICAgIGNvbnN0IG91dHB1dExheWVyc0Zyb21Db25maWcgPVxuICAgICAgICBjb25maWdbJ291dHB1dExheWVycyddIGFzIHNlcmlhbGl6YXRpb24uQ29uZmlnRGljdFtdO1xuICAgIGZvciAoY29uc3QgbGF5ZXJEYXRhIG9mIG91dHB1dExheWVyc0Zyb21Db25maWcpIHtcbiAgICAgIGNvbnN0IGxheWVyTmFtZSA9IGxheWVyRGF0YVswXSBhcyBzdHJpbmc7XG4gICAgICBjb25zdCBub2RlSW5kZXggPSBsYXllckRhdGFbMV0gYXMgbnVtYmVyO1xuICAgICAgY29uc3QgdGVuc29ySW5kZXggPSBsYXllckRhdGFbMl0gYXMgbnVtYmVyO1xuICAgICAgZ2VuZXJpY191dGlscy5hc3NlcnQobGF5ZXJOYW1lIGluIGNyZWF0ZWRMYXllcnMpO1xuICAgICAgY29uc3QgbGF5ZXIgPSBjcmVhdGVkTGF5ZXJzW2xheWVyTmFtZV07XG4gICAgICBjb25zdCBsYXllck91dHB1dFRlbnNvcnMgPSBsYXllci5pbmJvdW5kTm9kZXNbbm9kZUluZGV4XS5vdXRwdXRUZW5zb3JzO1xuICAgICAgb3V0cHV0VGVuc29ycy5wdXNoKGxheWVyT3V0cHV0VGVuc29yc1t0ZW5zb3JJbmRleF0pO1xuICAgIH1cbiAgICByZXR1cm4gbmV3IGNscyh7aW5wdXRzOiBpbnB1dFRlbnNvcnMsIG91dHB1dHM6IG91dHB1dFRlbnNvcnMsIG5hbWV9KTtcbiAgfVxuXG4gIC8qKlxuICAgKiBEZXRlcm1pbmUgd2hldGhlciB0aGUgY29udGFpbmVyIGlzIHN0YXRlZnVsLlxuICAgKlxuICAgKiBQb3J0aW5nIE5vdGU6IHRoaXMgaXMgdGhlIGVxdWl2YWxlbnQgb2YgdGhlIHN0YXRlZnVsIEBwcm9wZXJ0eSBvZlxuICAgKiAgIHRoZSBDb250YWluZXIgY2xhc3MgaW4gUHlLZXJhcy5cbiAgICovXG4gIG92ZXJyaWRlIGdldCBzdGF0ZWZ1bCgpOiBib29sZWFuIHtcbiAgICAvLyBQb3J0aW5nIE5vdGU6IFRoaXMgY2hlY2sgaXMgdG8gcHJldmVudCBpbmFkdmVydGVudCBzZXR0aW5nIG9mIHRoZVxuICAgIC8vICAgX3N0YXRlZnVsIHByb3BlcnR5IG9mIHRoZSBDb250YWluZXIgaW5zdGFuY2UuXG4gICAgaWYgKHRoaXMuX3N0YXRlZnVsKSB7XG4gICAgICB0aHJvdyBuZXcgVmFsdWVFcnJvcihcbiAgICAgICAgICAnQ29udGFpbmVyIGluc3RhbmNlIHVuZXhwZWN0ZWRseSBoYXMgX3N0YXRlZnVsID0gdHJ1ZS4gVGhlICcgK1xuICAgICAgICAgICdzdGF0ZWZ1bG5lc3Mgb2YgYSBDb250YWluZXIgaXMgZGV0ZXJtaW5lZCBieSB0aGUgTGF5ZXJzIGl0ICcgK1xuICAgICAgICAgICdjb250YWlucy4gSXRzIF9zdGF0ZWZ1bCBwcm9wZXJ0eSBtdXN0IHJlbWFpbiB0aGUgZGVmYXVsdCBmYWxzZS4nKTtcbiAgICB9XG4gICAgZm9yIChjb25zdCBsYXllciBvZiB0aGlzLmxheWVycykge1xuICAgICAgaWYgKGxheWVyLnN0YXRlZnVsKSB7XG4gICAgICAgIHJldHVybiB0cnVlO1xuICAgICAgfVxuICAgIH1cbiAgICByZXR1cm4gZmFsc2U7XG4gIH1cblxuICAvKipcbiAgICogUmVzZXQgdGhlIHN0YXRlIG9mIGFsbCBzdGF0ZWZ1bCBjb25zdGl0dWVudCBsYXllcnMgKGlmIGFueSkuXG4gICAqXG4gICAqIEV4YW1wbGVzIG9mIHN0YXRlZnVsIGxheWVycyBpbmNsdWRlIFJOTiBsYXllcnMgd2hvc2UgYHN0YXRlZnVsYCBwcm9wZXJ0eVxuICAgKiBpcyBzZXQgYXMgYHRydWVgLlxuICAgKi9cbiAgb3ZlcnJpZGUgcmVzZXRTdGF0ZXMoKSB7XG4gICAgdGlkeSgoKSA9PiB7XG4gICAgICB0aGlzLmxheWVycy5mb3JFYWNoKGxheWVyID0+IHtcbiAgICAgICAgLy8gdHNsaW50OmRpc2FibGU6bm8tYW55XG4gICAgICAgIGlmIChsYXllci5zdGF0ZWZ1bCkge1xuICAgICAgICAgIGxheWVyLnJlc2V0U3RhdGVzKCk7XG4gICAgICAgIH1cbiAgICAgICAgLy8gdHNsaW50OmVuYWJsZTpuby1hbnlcbiAgICAgIH0pO1xuICAgIH0pO1xuICB9XG59XG4iXX0=