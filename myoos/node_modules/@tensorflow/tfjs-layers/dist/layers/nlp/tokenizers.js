/**
 * @license
 * Copyright 2023 Google LLC.
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
import { Layer } from '../../engine/topology';
import { NotImplementedError, ValueError } from '../../errors';
/**
 * Base class for Tokenizers.
 *
 *  Tokenizers in the tfjs library should all subclass this layer.
 *  The class provides two core methods `tokenize()` and `detokenize()` for
 *  going from plain text to sequences and back. A tokenizer is a subclass of
 *  `Layer` and can be combined with other layers in a `tf.sequential` model.
 *
 *  Subclassers should always implement the `tokenize()` method, which will also
 *  be the default when calling the layer directly on inputs.
 *
 *  Subclassers can optionally implement the `detokenize()` method if the
 *  tokenization is reversible. Otherwise, this can be skipped.
 *
 *  Subclassers should implement `get_vocabulary()`, `vocabulary_size()`,
 *  `token_to_id()` and `id_to_token()` if applicable. For some simple
 *  "vocab free" tokenizers, such as a whitespace splitter shown below, these
 *  methods do not apply and can be skipped.
 *
 *  Example:
 *
 *  ```js
 *  class WhitespaceSplitterTokenizer extends Tokenizer {
 *    tokenize(inputs: Tensor1D): Tensor1D[] {
 *      const stringInputs = inputs.dataSync() as unknown as string[];
 *      return stringInputs.map(input => tensor1d(input.split(' ')));
 *    }
 *
 *    override detokenize(inputs: Tensor1D[]): Tensor1D {
 *      const stringInputs = inputs.map(
 *        input => input.dataSync() as unknown as string[]);
 *      return tensor1d(stringInputs.map(str => str.join(' ')));
 *    }
 *  }
 *
 * const tokenizer = new WhitespaceSplitterTokenizer();
 *
 * tokenizer.tokenize(tensor1d(['this is a test']))[0].print();
 *
 * tokenizer.detokenize([tensor1d(['this', 'is', 'a', 'test'])]).print();
 * ```
 */
export class Tokenizer extends Layer {
    /**
     * Transform tokens back into strings.
     *
     * @param inputs Input tensor.
     * @param kwargs Additional keyword arguments.
     */
    detokenize(inputs) {
        throw new NotImplementedError(`No implementation of 'detokenize()' was found for
      ${this.constructor.name}.`);
    }
    /**
     * Get the tokenizer vocabulary as a list of strings terms.
     */
    get vocabulary() {
        throw new NotImplementedError(`No implementation of 'vocabulary()' was found for
      ${this.constructor.name}.`);
    }
    /**
     * Returns the total size of the token id space.
     */
    get vocabularySize() {
        throw new NotImplementedError(`No implementation of 'vocabularySize()' was found for
      ${this.constructor.name}.`);
    }
    /**
     * Convert an integer id to a string token.
     */
    idToToken(id) {
        throw new NotImplementedError(`No implementation of 'idToToken()' was found for
      ${this.constructor.name}.`);
    }
    /**
     * Convert an integer id to a string token.
     */
    tokenToId(token) {
        throw new NotImplementedError(`No implementation of 'tokenToId()' was found for
      ${this.constructor.name}.`);
    }
    call(inputs, { mode = 'tokenize' } = {}) {
        if (mode === 'tokenize') {
            if (inputs instanceof Array) {
                throw new ValueError(`tokenize expects Tensor1D, not Tensor1D[].`);
            }
            return this.tokenize(inputs);
        }
        if (mode === 'detokenize') {
            if (!(inputs instanceof Array)) {
                throw new ValueError(`detokenize expects Tensor1D[], not Tensor1D.`);
            }
            return this.detokenize(inputs);
        }
        throw new ValueError(`Input mode=${mode} is not supported.`);
    }
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoidG9rZW5pemVycy5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uLy4uL3RmanMtbGF5ZXJzL3NyYy9sYXllcnMvbmxwL3Rva2VuaXplcnMudHMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7Ozs7Ozs7Ozs7Ozs7OztHQWVHO0FBU0gsT0FBTyxFQUFFLEtBQUssRUFBRSxNQUFNLHVCQUF1QixDQUFDO0FBQzlDLE9BQU8sRUFBRSxtQkFBbUIsRUFBRSxVQUFVLEVBQUUsTUFBTSxjQUFjLENBQUM7QUFNL0Q7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0dBeUNHO0FBQ0gsTUFBTSxPQUFnQixTQUFVLFNBQVEsS0FBSztJQVMzQzs7Ozs7T0FLRztJQUNILFVBQVUsQ0FBQyxNQUFrQjtRQUMzQixNQUFNLElBQUksbUJBQW1CLENBQzNCO1FBQ0UsSUFBSSxDQUFDLFdBQVcsQ0FBQyxJQUFJLEdBQUcsQ0FDM0IsQ0FBQztJQUNKLENBQUM7SUFFRDs7T0FFRztJQUNILElBQUksVUFBVTtRQUNaLE1BQU0sSUFBSSxtQkFBbUIsQ0FDM0I7UUFDRSxJQUFJLENBQUMsV0FBVyxDQUFDLElBQUksR0FBRyxDQUMzQixDQUFDO0lBQ0osQ0FBQztJQUVEOztPQUVHO0lBQ0gsSUFBSSxjQUFjO1FBQ2hCLE1BQU0sSUFBSSxtQkFBbUIsQ0FDM0I7UUFDRSxJQUFJLENBQUMsV0FBVyxDQUFDLElBQUksR0FBRyxDQUMzQixDQUFDO0lBQ0osQ0FBQztJQUVEOztPQUVHO0lBQ0gsU0FBUyxDQUFDLEVBQVU7UUFDbEIsTUFBTSxJQUFJLG1CQUFtQixDQUMzQjtRQUNFLElBQUksQ0FBQyxXQUFXLENBQUMsSUFBSSxHQUFHLENBQzNCLENBQUM7SUFDSixDQUFDO0lBRUQ7O09BRUc7SUFDSCxTQUFTLENBQUMsS0FBYTtRQUNyQixNQUFNLElBQUksbUJBQW1CLENBQzNCO1FBQ0UsSUFBSSxDQUFDLFdBQVcsQ0FBQyxJQUFJLEdBQUcsQ0FDM0IsQ0FBQztJQUNKLENBQUM7SUFFUSxJQUFJLENBQ1gsTUFBMkIsRUFDM0IsRUFBQyxJQUFJLEdBQUcsVUFBVSxLQUFvQixFQUFFO1FBR3hDLElBQUksSUFBSSxLQUFLLFVBQVUsRUFBRTtZQUN2QixJQUFJLE1BQU0sWUFBWSxLQUFLLEVBQUU7Z0JBQzNCLE1BQU0sSUFBSSxVQUFVLENBQUMsNENBQTRDLENBQUMsQ0FBQzthQUNwRTtZQUNELE9BQU8sSUFBSSxDQUFDLFFBQVEsQ0FBQyxNQUFNLENBQUMsQ0FBQztTQUM5QjtRQUVELElBQUksSUFBSSxLQUFLLFlBQVksRUFBRTtZQUN6QixJQUFJLENBQUMsQ0FBQyxNQUFNLFlBQVksS0FBSyxDQUFDLEVBQUU7Z0JBQzlCLE1BQU0sSUFBSSxVQUFVLENBQUMsOENBQThDLENBQUMsQ0FBQzthQUN0RTtZQUNELE9BQU8sSUFBSSxDQUFDLFVBQVUsQ0FBQyxNQUFNLENBQUMsQ0FBQztTQUNoQztRQUVELE1BQU0sSUFBSSxVQUFVLENBQUMsY0FBYyxJQUFJLG9CQUFvQixDQUFDLENBQUM7SUFDL0QsQ0FBQztDQUNGIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMjMgR29vZ2xlIExMQy5cbiAqIExpY2Vuc2VkIHVuZGVyIHRoZSBBcGFjaGUgTGljZW5zZSwgVmVyc2lvbiAyLjAgKHRoZSBcIkxpY2Vuc2VcIik7XG4gKiB5b3UgbWF5IG5vdCB1c2UgdGhpcyBmaWxlIGV4Y2VwdCBpbiBjb21wbGlhbmNlIHdpdGggdGhlIExpY2Vuc2UuXG4gKiBZb3UgbWF5IG9idGFpbiBhIGNvcHkgb2YgdGhlIExpY2Vuc2UgYXRcbiAqXG4gKiBodHRwOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcbiAqXG4gKiBVbmxlc3MgcmVxdWlyZWQgYnkgYXBwbGljYWJsZSBsYXcgb3IgYWdyZWVkIHRvIGluIHdyaXRpbmcsIHNvZnR3YXJlXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXG4gKiBXSVRIT1VUIFdBUlJBTlRJRVMgT1IgQ09ORElUSU9OUyBPRiBBTlkgS0lORCwgZWl0aGVyIGV4cHJlc3Mgb3IgaW1wbGllZC5cbiAqIFNlZSB0aGUgTGljZW5zZSBmb3IgdGhlIHNwZWNpZmljIGxhbmd1YWdlIGdvdmVybmluZyBwZXJtaXNzaW9ucyBhbmRcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxuICogPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cbiAqL1xuXG4vKipcbiAqICBUb2tlbml6ZXIgbGF5ZXJzLlxuICovXG5cbi8qIE9yaWdpbmFsIHNvdXJjZToga2VyYXMtbmxwL3Rva2VuaXplci5weSAqL1xuaW1wb3J0IHsgVGVuc29yMUQgfSBmcm9tICdAdGVuc29yZmxvdy90ZmpzLWNvcmUnO1xuXG5pbXBvcnQgeyBMYXllciB9IGZyb20gJy4uLy4uL2VuZ2luZS90b3BvbG9neSc7XG5pbXBvcnQgeyBOb3RJbXBsZW1lbnRlZEVycm9yLCBWYWx1ZUVycm9yIH0gZnJvbSAnLi4vLi4vZXJyb3JzJztcblxuZXhwb3J0IGRlY2xhcmUgaW50ZXJmYWNlIFRva2VuaXplck9wdGlvbnMge1xuICBtb2RlPzogJ3Rva2VuaXplJyB8ICdkZXRva2VuaXplJztcbn1cblxuLyoqXG4gKiBCYXNlIGNsYXNzIGZvciBUb2tlbml6ZXJzLlxuICpcbiAqICBUb2tlbml6ZXJzIGluIHRoZSB0ZmpzIGxpYnJhcnkgc2hvdWxkIGFsbCBzdWJjbGFzcyB0aGlzIGxheWVyLlxuICogIFRoZSBjbGFzcyBwcm92aWRlcyB0d28gY29yZSBtZXRob2RzIGB0b2tlbml6ZSgpYCBhbmQgYGRldG9rZW5pemUoKWAgZm9yXG4gKiAgZ29pbmcgZnJvbSBwbGFpbiB0ZXh0IHRvIHNlcXVlbmNlcyBhbmQgYmFjay4gQSB0b2tlbml6ZXIgaXMgYSBzdWJjbGFzcyBvZlxuICogIGBMYXllcmAgYW5kIGNhbiBiZSBjb21iaW5lZCB3aXRoIG90aGVyIGxheWVycyBpbiBhIGB0Zi5zZXF1ZW50aWFsYCBtb2RlbC5cbiAqXG4gKiAgU3ViY2xhc3NlcnMgc2hvdWxkIGFsd2F5cyBpbXBsZW1lbnQgdGhlIGB0b2tlbml6ZSgpYCBtZXRob2QsIHdoaWNoIHdpbGwgYWxzb1xuICogIGJlIHRoZSBkZWZhdWx0IHdoZW4gY2FsbGluZyB0aGUgbGF5ZXIgZGlyZWN0bHkgb24gaW5wdXRzLlxuICpcbiAqICBTdWJjbGFzc2VycyBjYW4gb3B0aW9uYWxseSBpbXBsZW1lbnQgdGhlIGBkZXRva2VuaXplKClgIG1ldGhvZCBpZiB0aGVcbiAqICB0b2tlbml6YXRpb24gaXMgcmV2ZXJzaWJsZS4gT3RoZXJ3aXNlLCB0aGlzIGNhbiBiZSBza2lwcGVkLlxuICpcbiAqICBTdWJjbGFzc2VycyBzaG91bGQgaW1wbGVtZW50IGBnZXRfdm9jYWJ1bGFyeSgpYCwgYHZvY2FidWxhcnlfc2l6ZSgpYCxcbiAqICBgdG9rZW5fdG9faWQoKWAgYW5kIGBpZF90b190b2tlbigpYCBpZiBhcHBsaWNhYmxlLiBGb3Igc29tZSBzaW1wbGVcbiAqICBcInZvY2FiIGZyZWVcIiB0b2tlbml6ZXJzLCBzdWNoIGFzIGEgd2hpdGVzcGFjZSBzcGxpdHRlciBzaG93biBiZWxvdywgdGhlc2VcbiAqICBtZXRob2RzIGRvIG5vdCBhcHBseSBhbmQgY2FuIGJlIHNraXBwZWQuXG4gKlxuICogIEV4YW1wbGU6XG4gKlxuICogIGBgYGpzXG4gKiAgY2xhc3MgV2hpdGVzcGFjZVNwbGl0dGVyVG9rZW5pemVyIGV4dGVuZHMgVG9rZW5pemVyIHtcbiAqICAgIHRva2VuaXplKGlucHV0czogVGVuc29yMUQpOiBUZW5zb3IxRFtdIHtcbiAqICAgICAgY29uc3Qgc3RyaW5nSW5wdXRzID0gaW5wdXRzLmRhdGFTeW5jKCkgYXMgdW5rbm93biBhcyBzdHJpbmdbXTtcbiAqICAgICAgcmV0dXJuIHN0cmluZ0lucHV0cy5tYXAoaW5wdXQgPT4gdGVuc29yMWQoaW5wdXQuc3BsaXQoJyAnKSkpO1xuICogICAgfVxuICpcbiAqICAgIG92ZXJyaWRlIGRldG9rZW5pemUoaW5wdXRzOiBUZW5zb3IxRFtdKTogVGVuc29yMUQge1xuICogICAgICBjb25zdCBzdHJpbmdJbnB1dHMgPSBpbnB1dHMubWFwKFxuICogICAgICAgIGlucHV0ID0+IGlucHV0LmRhdGFTeW5jKCkgYXMgdW5rbm93biBhcyBzdHJpbmdbXSk7XG4gKiAgICAgIHJldHVybiB0ZW5zb3IxZChzdHJpbmdJbnB1dHMubWFwKHN0ciA9PiBzdHIuam9pbignICcpKSk7XG4gKiAgICB9XG4gKiAgfVxuICpcbiAqIGNvbnN0IHRva2VuaXplciA9IG5ldyBXaGl0ZXNwYWNlU3BsaXR0ZXJUb2tlbml6ZXIoKTtcbiAqXG4gKiB0b2tlbml6ZXIudG9rZW5pemUodGVuc29yMWQoWyd0aGlzIGlzIGEgdGVzdCddKSlbMF0ucHJpbnQoKTtcbiAqXG4gKiB0b2tlbml6ZXIuZGV0b2tlbml6ZShbdGVuc29yMWQoWyd0aGlzJywgJ2lzJywgJ2EnLCAndGVzdCddKV0pLnByaW50KCk7XG4gKiBgYGBcbiAqL1xuZXhwb3J0IGFic3RyYWN0IGNsYXNzIFRva2VuaXplciBleHRlbmRzIExheWVyIHtcbiAgLyoqXG4gICAqIFRyYW5zZm9ybSBpbnB1dCB0ZW5zb3JzIG9mIHN0cmluZ3MgaW50byBvdXRwdXQgdG9rZW5zLlxuICAgKlxuICAgKiBAcGFyYW0gaW5wdXRzIElucHV0IHRlbnNvci5cbiAgICogQHBhcmFtIGt3YXJncyBBZGRpdGlvbmFsIGtleXdvcmQgYXJndW1lbnRzLlxuICAgKi9cbiAgYWJzdHJhY3QgdG9rZW5pemUoaW5wdXRzOiBUZW5zb3IxRCk6IFRlbnNvcjFEW107XG5cbiAgLyoqXG4gICAqIFRyYW5zZm9ybSB0b2tlbnMgYmFjayBpbnRvIHN0cmluZ3MuXG4gICAqXG4gICAqIEBwYXJhbSBpbnB1dHMgSW5wdXQgdGVuc29yLlxuICAgKiBAcGFyYW0ga3dhcmdzIEFkZGl0aW9uYWwga2V5d29yZCBhcmd1bWVudHMuXG4gICAqL1xuICBkZXRva2VuaXplKGlucHV0czogVGVuc29yMURbXSk6IFRlbnNvcjFEIHtcbiAgICB0aHJvdyBuZXcgTm90SW1wbGVtZW50ZWRFcnJvcihcbiAgICAgIGBObyBpbXBsZW1lbnRhdGlvbiBvZiAnZGV0b2tlbml6ZSgpJyB3YXMgZm91bmQgZm9yXG4gICAgICAke3RoaXMuY29uc3RydWN0b3IubmFtZX0uYFxuICAgICk7XG4gIH1cblxuICAvKipcbiAgICogR2V0IHRoZSB0b2tlbml6ZXIgdm9jYWJ1bGFyeSBhcyBhIGxpc3Qgb2Ygc3RyaW5ncyB0ZXJtcy5cbiAgICovXG4gIGdldCB2b2NhYnVsYXJ5KCk6IHN0cmluZ1tdIHtcbiAgICB0aHJvdyBuZXcgTm90SW1wbGVtZW50ZWRFcnJvcihcbiAgICAgIGBObyBpbXBsZW1lbnRhdGlvbiBvZiAndm9jYWJ1bGFyeSgpJyB3YXMgZm91bmQgZm9yXG4gICAgICAke3RoaXMuY29uc3RydWN0b3IubmFtZX0uYFxuICAgICk7XG4gIH1cblxuICAvKipcbiAgICogUmV0dXJucyB0aGUgdG90YWwgc2l6ZSBvZiB0aGUgdG9rZW4gaWQgc3BhY2UuXG4gICAqL1xuICBnZXQgdm9jYWJ1bGFyeVNpemUoKTogbnVtYmVyIHtcbiAgICB0aHJvdyBuZXcgTm90SW1wbGVtZW50ZWRFcnJvcihcbiAgICAgIGBObyBpbXBsZW1lbnRhdGlvbiBvZiAndm9jYWJ1bGFyeVNpemUoKScgd2FzIGZvdW5kIGZvclxuICAgICAgJHt0aGlzLmNvbnN0cnVjdG9yLm5hbWV9LmBcbiAgICApO1xuICB9XG5cbiAgLyoqXG4gICAqIENvbnZlcnQgYW4gaW50ZWdlciBpZCB0byBhIHN0cmluZyB0b2tlbi5cbiAgICovXG4gIGlkVG9Ub2tlbihpZDogbnVtYmVyKTogc3RyaW5nIHtcbiAgICB0aHJvdyBuZXcgTm90SW1wbGVtZW50ZWRFcnJvcihcbiAgICAgIGBObyBpbXBsZW1lbnRhdGlvbiBvZiAnaWRUb1Rva2VuKCknIHdhcyBmb3VuZCBmb3JcbiAgICAgICR7dGhpcy5jb25zdHJ1Y3Rvci5uYW1lfS5gXG4gICAgKTtcbiAgfVxuXG4gIC8qKlxuICAgKiBDb252ZXJ0IGFuIGludGVnZXIgaWQgdG8gYSBzdHJpbmcgdG9rZW4uXG4gICAqL1xuICB0b2tlblRvSWQodG9rZW46IHN0cmluZyk6IG51bWJlciB7XG4gICAgdGhyb3cgbmV3IE5vdEltcGxlbWVudGVkRXJyb3IoXG4gICAgICBgTm8gaW1wbGVtZW50YXRpb24gb2YgJ3Rva2VuVG9JZCgpJyB3YXMgZm91bmQgZm9yXG4gICAgICAke3RoaXMuY29uc3RydWN0b3IubmFtZX0uYFxuICAgICk7XG4gIH1cblxuICBvdmVycmlkZSBjYWxsKFxuICAgIGlucHV0czogVGVuc29yMUR8VGVuc29yMURbXSxcbiAgICB7bW9kZSA9ICd0b2tlbml6ZSd9OiBUb2tlbml6ZXJPcHRpb25zPXt9XG4gICk6IFRlbnNvcjFEfFRlbnNvcjFEW10ge1xuXG4gICAgaWYgKG1vZGUgPT09ICd0b2tlbml6ZScpIHtcbiAgICAgIGlmIChpbnB1dHMgaW5zdGFuY2VvZiBBcnJheSkge1xuICAgICAgICB0aHJvdyBuZXcgVmFsdWVFcnJvcihgdG9rZW5pemUgZXhwZWN0cyBUZW5zb3IxRCwgbm90IFRlbnNvcjFEW10uYCk7XG4gICAgICB9XG4gICAgICByZXR1cm4gdGhpcy50b2tlbml6ZShpbnB1dHMpO1xuICAgIH1cblxuICAgIGlmIChtb2RlID09PSAnZGV0b2tlbml6ZScpIHtcbiAgICAgIGlmICghKGlucHV0cyBpbnN0YW5jZW9mIEFycmF5KSkge1xuICAgICAgICB0aHJvdyBuZXcgVmFsdWVFcnJvcihgZGV0b2tlbml6ZSBleHBlY3RzIFRlbnNvcjFEW10sIG5vdCBUZW5zb3IxRC5gKTtcbiAgICAgIH1cbiAgICAgIHJldHVybiB0aGlzLmRldG9rZW5pemUoaW5wdXRzKTtcbiAgICB9XG5cbiAgICB0aHJvdyBuZXcgVmFsdWVFcnJvcihgSW5wdXQgbW9kZT0ke21vZGV9IGlzIG5vdCBzdXBwb3J0ZWQuYCk7XG4gIH1cbn1cbiJdfQ==