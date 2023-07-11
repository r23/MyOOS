import * as util from '../util';
/**
 * Wraps a list of ArrayBuffers into a `slice()`-able object without allocating
 * a large ArrayBuffer.
 *
 * Allocating large ArrayBuffers (~2GB) can be unstable on Chrome. TFJS loads
 * its weights as a list of (usually) 4MB ArrayBuffers and then slices the
 * weight tensors out of them. For small models, it's safe to concatenate all
 * the weight buffers into a single ArrayBuffer and then slice the weight
 * tensors out of it, but for large models, a different approach is needed.
 */
export class CompositeArrayBuffer {
    /**
     * Concatenate a number of ArrayBuffers into one.
     *
     * @param buffers An array of ArrayBuffers to concatenate, or a single
     *     ArrayBuffer.
     * @returns Result of concatenating `buffers` in order.
     */
    static join(buffers) {
        return new CompositeArrayBuffer(buffers).slice();
    }
    constructor(buffers) {
        this.shards = [];
        this.previousShardIndex = 0;
        if (buffers == null) {
            return;
        }
        // Normalize the `buffers` input to be `ArrayBuffer[]`.
        if (!(buffers instanceof Array)) {
            buffers = [buffers];
        }
        buffers = buffers.map((bufferOrTypedArray) => {
            if (util.isTypedArray(bufferOrTypedArray)) {
                return bufferOrTypedArray.buffer;
            }
            return bufferOrTypedArray;
        });
        // Skip setting up shards if there are no buffers.
        if (buffers.length === 0) {
            return;
        }
        this.bufferUniformSize = buffers[0].byteLength;
        let start = 0;
        for (let i = 0; i < buffers.length; i++) {
            const buffer = buffers[i];
            // Check that all buffers except the last one have the same length.
            if (i !== buffers.length - 1 &&
                buffer.byteLength !== this.bufferUniformSize) {
                // Unset the buffer uniform size, since the buffer sizes are not
                // uniform.
                this.bufferUniformSize = undefined;
            }
            // Create the shards, including their start and end points.
            const end = start + buffer.byteLength;
            this.shards.push({ buffer, start, end });
            start = end;
        }
        // Set the byteLenghth
        if (this.shards.length === 0) {
            this.byteLength = 0;
        }
        this.byteLength = this.shards[this.shards.length - 1].end;
    }
    slice(start = 0, end = this.byteLength) {
        // If there are no shards, then the CompositeArrayBuffer was initialized
        // with no data.
        if (this.shards.length === 0) {
            return new ArrayBuffer(0);
        }
        // NaN is treated as zero for slicing. This matches ArrayBuffer's behavior.
        start = isNaN(Number(start)) ? 0 : start;
        end = isNaN(Number(end)) ? 0 : end;
        // Fix the bounds to within the array.
        start = Math.max(0, start);
        end = Math.min(this.byteLength, end);
        if (end <= start) {
            return new ArrayBuffer(0);
        }
        const startShardIndex = this.findShardForByte(start);
        if (startShardIndex === -1) {
            // This should not happen since the start and end indices are always
            // within 0 and the composite array's length.
            throw new Error(`Could not find start shard for byte ${start}`);
        }
        const size = end - start;
        const outputBuffer = new ArrayBuffer(size);
        const outputArray = new Uint8Array(outputBuffer);
        let sliced = 0;
        for (let i = startShardIndex; i < this.shards.length; i++) {
            const shard = this.shards[i];
            const globalStart = start + sliced;
            const localStart = globalStart - shard.start;
            const outputStart = sliced;
            const globalEnd = Math.min(end, shard.end);
            const localEnd = globalEnd - shard.start;
            const outputSlice = new Uint8Array(shard.buffer, localStart, localEnd - localStart);
            outputArray.set(outputSlice, outputStart);
            sliced += outputSlice.length;
            if (end < shard.end) {
                break;
            }
        }
        return outputBuffer;
    }
    /**
     * Get the index of the shard that contains the byte at `byteIndex`.
     */
    findShardForByte(byteIndex) {
        if (this.shards.length === 0 || byteIndex < 0 ||
            byteIndex >= this.byteLength) {
            return -1;
        }
        // If the buffers have a uniform size, compute the shard directly.
        if (this.bufferUniformSize != null) {
            this.previousShardIndex = Math.floor(byteIndex / this.bufferUniformSize);
            return this.previousShardIndex;
        }
        // If the buffers don't have a uniform size, we need to search for the
        // shard. That means we need a function to check where the byteIndex lies
        // relative to a given shard.
        function check(shard) {
            if (byteIndex < shard.start) {
                return -1;
            }
            if (byteIndex >= shard.end) {
                return 1;
            }
            return 0;
        }
        // For efficiency, try the previous shard first.
        if (check(this.shards[this.previousShardIndex]) === 0) {
            return this.previousShardIndex;
        }
        // Otherwise, use a generic search function.
        // This should almost never end up being used in practice since the weight
        // entries should always be in order.
        const index = search(this.shards, check);
        if (index === -1) {
            return -1;
        }
        this.previousShardIndex = index;
        return this.previousShardIndex;
    }
}
/**
 * Search for an element of a sorted array.
 *
 * @param sortedArray The sorted array to search
 * @param compare A function to compare the current value against the searched
 *     value. Return 0 on a match, negative if the searched value is less than
 *     the value passed to the function, and positive if the searched value is
 *     greater than the value passed to the function.
 * @returns The index of the element, or -1 if it's not in the array.
 */
export function search(sortedArray, compare) {
    // Binary search
    let min = 0;
    let max = sortedArray.length;
    while (min <= max) {
        const middle = Math.floor((max - min) / 2) + min;
        const side = compare(sortedArray[middle]);
        if (side === 0) {
            return middle;
        }
        else if (side < 0) {
            max = middle;
        }
        else {
            min = middle + 1;
        }
    }
    return -1;
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiY29tcG9zaXRlX2FycmF5X2J1ZmZlci5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uLy4uLy4uL3RmanMtY29yZS9zcmMvaW8vY29tcG9zaXRlX2FycmF5X2J1ZmZlci50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFpQkEsT0FBTyxLQUFLLElBQUksTUFBTSxTQUFTLENBQUM7QUFRaEM7Ozs7Ozs7OztHQVNHO0FBRUgsTUFBTSxPQUFPLG9CQUFvQjtJQU0vQjs7Ozs7O09BTUc7SUFDSCxNQUFNLENBQUMsSUFBSSxDQUFDLE9BQXFDO1FBQy9DLE9BQU8sSUFBSSxvQkFBb0IsQ0FBQyxPQUFPLENBQUMsQ0FBQyxLQUFLLEVBQUUsQ0FBQztJQUNuRCxDQUFDO0lBRUQsWUFBWSxPQUNFO1FBakJOLFdBQU0sR0FBa0IsRUFBRSxDQUFDO1FBQzNCLHVCQUFrQixHQUFHLENBQUMsQ0FBQztRQWlCN0IsSUFBSSxPQUFPLElBQUksSUFBSSxFQUFFO1lBQ25CLE9BQU87U0FDUjtRQUNELHVEQUF1RDtRQUN2RCxJQUFJLENBQUMsQ0FBQyxPQUFPLFlBQVksS0FBSyxDQUFDLEVBQUU7WUFDL0IsT0FBTyxHQUFHLENBQUMsT0FBTyxDQUFDLENBQUM7U0FDckI7UUFDRCxPQUFPLEdBQUcsT0FBTyxDQUFDLEdBQUcsQ0FBQyxDQUFDLGtCQUFrQixFQUFFLEVBQUU7WUFDM0MsSUFBSSxJQUFJLENBQUMsWUFBWSxDQUFDLGtCQUFrQixDQUFDLEVBQUU7Z0JBQ3pDLE9BQU8sa0JBQWtCLENBQUMsTUFBTSxDQUFDO2FBQ2xDO1lBQ0QsT0FBTyxrQkFBa0IsQ0FBQztRQUM1QixDQUFDLENBQUMsQ0FBQztRQUVILGtEQUFrRDtRQUNsRCxJQUFJLE9BQU8sQ0FBQyxNQUFNLEtBQUssQ0FBQyxFQUFFO1lBQ3hCLE9BQU87U0FDUjtRQUVELElBQUksQ0FBQyxpQkFBaUIsR0FBRyxPQUFPLENBQUMsQ0FBQyxDQUFDLENBQUMsVUFBVSxDQUFDO1FBQy9DLElB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QU0sSUFBSSxHQUFHLE9BQU8sQ0FBQyxXQUFXLENBQUMsTUFBTSxDQUFDLENBQUMsQ0FBQztRQUUxQyxJQUFJLElBQUksS0FBSyxDQUFDLEVBQUU7WUFDZCxPQUFPLE1BQU0sQ0FBQztTQUNmO2FBQU0sSUFBSSxJQUFJLEdBQUcsQ0FBQyxFQUFFO1lBQ25CLEdBQUcsR0FBRyxNQUFNLENBQUM7U0FDZDthQUFNO1lBQ0wsR0FBRyxHQUFHLE1BQU0sR0FBRyxDQUFDLENBQUM7U0FDbEI7S0FDRjtJQUNELE9BQU8sQ0FBQyxDQUFDLENBQUM7QUFDWixDQUFDIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMjMgR29vZ2xlIExMQy4gQWxsIFJpZ2h0cyBSZXNlcnZlZC5cbiAqIExpY2Vuc2VkIHVuZGVyIHRoZSBBcGFjaGUgTGljZW5zZSwgVmVyc2lvbiAyLjAgKHRoZSBcIkxpY2Vuc2VcIik7XG4gKiB5b3UgbWF5IG5vdCB1c2UgdGhpcyBmaWxlIGV4Y2VwdCBpbiBjb21wbGlhbmNlIHdpdGggdGhlIExpY2Vuc2UuXG4gKiBZb3UgbWF5IG9idGFpbiBhIGNvcHkgb2YgdGhlIExpY2Vuc2UgYXRcbiAqXG4gKiBodHRwOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcbiAqXG4gKiBVbmxlc3MgcmVxdWlyZWQgYnkgYXBwbGljYWJsZSBsYXcgb3IgYWdyZWVkIHRvIGluIHdyaXRpbmcsIHNvZnR3YXJlXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXG4gKiBXSVRIT1VUIFdBUlJBTlRJRVMgT1IgQ09ORElUSU9OUyBPRiBBTlkgS0lORCwgZWl0aGVyIGV4cHJlc3Mgb3IgaW1wbGllZC5cbiAqIFNlZSB0aGUgTGljZW5zZSBmb3IgdGhlIHNwZWNpZmljIGxhbmd1YWdlIGdvdmVybmluZyBwZXJtaXNzaW9ucyBhbmRcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxuICogPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cbiAqL1xuaW1wb3J0IHtUeXBlZEFycmF5fSBmcm9tICcuLi90eXBlcyc7XG5pbXBvcnQgKiBhcyB1dGlsIGZyb20gJy4uL3V0aWwnO1xuXG50eXBlIEJ1ZmZlclNoYXJkID0ge1xuICBzdGFydDogbnVtYmVyLFxuICBlbmQ6IG51bWJlcixcbiAgYnVmZmVyOiBBcnJheUJ1ZmZlcixcbn07XG5cbi8qKlxuICogV3JhcHMgYSBsaXN0IG9mIEFycmF5QnVmZmVycyBpbnRvIGEgYHNsaWNlKClgLWFibGUgb2JqZWN0IHdpdGhvdXQgYWxsb2NhdGluZ1xuICogYSBsYXJnZSBBcnJheUJ1ZmZlci5cbiAqXG4gKiBBbGxvY2F0aW5nIGxhcmdlIEFycmF5QnVmZmVycyAofjJHQikgY2FuIGJlIHVuc3RhYmxlIG9uIENocm9tZS4gVEZKUyBsb2Fkc1xuICogaXRzIHdlaWdodHMgYXMgYSBsaXN0IG9mICh1c3VhbGx5KSA0TUIgQXJyYXlCdWZmZXJzIGFuZCB0aGVuIHNsaWNlcyB0aGVcbiAqIHdlaWdodCB0ZW5zb3JzIG91dCBvZiB0aGVtLiBGb3Igc21hbGwgbW9kZWxzLCBpdCdzIHNhZmUgdG8gY29uY2F0ZW5hdGUgYWxsXG4gKiB0aGUgd2VpZ2h0IGJ1ZmZlcnMgaW50byBhIHNpbmdsZSBBcnJheUJ1ZmZlciBhbmQgdGhlbiBzbGljZSB0aGUgd2VpZ2h0XG4gKiB0ZW5zb3JzIG91dCBvZiBpdCwgYnV0IGZvciBsYXJnZSBtb2RlbHMsIGEgZGlmZmVyZW50IGFwcHJvYWNoIGlzIG5lZWRlZC5cbiAqL1xuXG5leHBvcnQgY2xhc3MgQ29tcG9zaXRlQXJyYXlCdWZmZXIge1xuICBwcml2YXRlIHNoYXJkczogQnVmZmVyU2hhcmRbXSA9IFtdO1xuICBwcml2YXRlIHByZXZpb3VzU2hhcmRJbmRleCA9IDA7XG4gIHByaXZhdGUgYnVmZmVyVW5pZm9ybVNpemU/OiBudW1iZXI7XG4gIHB1YmxpYyByZWFkb25seSBieXRlTGVuZ3RoOiBudW1iZXI7XG5cbiAgLyoqXG4gICAqIENvbmNhdGVuYXRlIGEgbnVtYmVyIG9mIEFycmF5QnVmZmVycyBpbnRvIG9uZS5cbiAgICpcbiAgICogQHBhcmFtIGJ1ZmZlcnMgQW4gYXJyYXkgb2YgQXJyYXlCdWZmZXJzIHRvIGNvbmNhdGVuYXRlLCBvciBhIHNpbmdsZVxuICAgKiAgICAgQXJyYXlCdWZmZXIuXG4gICAqIEByZXR1cm5zIFJlc3VsdCBvZiBjb25jYXRlbmF0aW5nIGBidWZmZXJzYCBpbiBvcmRlci5cbiAgICovXG4gIHN0YXRpYyBqb2luKGJ1ZmZlcnM/OiBBcnJheUJ1ZmZlcltdIHwgQXJyYXlCdWZmZXIpIHtcbiAgICByZXR1cm4gbmV3IENvbXBvc2l0ZUFycmF5QnVmZmVyKGJ1ZmZlcnMpLnNsaWNlKCk7XG4gIH1cblxuICBjb25zdHJ1Y3RvcihidWZmZXJzPzogQXJyYXlCdWZmZXIgfCBBcnJheUJ1ZmZlcltdIHwgVHlwZWRBcnJheSB8XG4gICAgVHlwZWRBcnJheVtdKSB7XG4gICAgaWYgKGJ1ZmZlcnMgPT0gbnVsbCkge1xuICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICAvLyBOb3JtYWxpemUgdGhlIGBidWZmZXJzYCBpbnB1dCB0byBiZSBgQXJyYXlCdWZmZXJbXWAuXG4gICAgaWYgKCEoYnVmZmVycyBpbnN0YW5jZW9mIEFycmF5KSkge1xuICAgICAgYnVmZmVycyA9IFtidWZmZXJzXTtcbiAgICB9XG4gICAgYnVmZmVycyA9IGJ1ZmZlcnMubWFwKChidWZmZXJPclR5cGVkQXJyYXkpID0+IHtcbiAgICAgIGlmICh1dGlsLmlzVHlwZWRBcnJheShidWZmZXJPclR5cGVkQXJyYXkpKSB7XG4gICAgICAgIHJldHVybiBidWZmZXJPclR5cGVkQXJyYXkuYnVmZmVyO1xuICAgICAgfVxuICAgICAgcmV0dXJuIGJ1ZmZlck9yVHlwZWRBcnJheTtcbiAgICB9KTtcblxuICAgIC8vIFNraXAgc2V0dGluZyB1cCBzaGFyZHMgaWYgdGhlcmUgYXJlIG5vIGJ1ZmZlcnMuXG4gICAgaWYgKGJ1ZmZlcnMubGVuZ3RoID09PSAwKSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgdGhpcy5idWZmZXJVbmlmb3JtU2l6ZSA9IGJ1ZmZlcnNbMF0uYnl0ZUxlbmd0aDtcbiAgICBsZXQgc3RhcnQgPSAwO1xuXG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCBidWZmZXJzLmxlbmd0aDsgaSsrKSB7XG4gICAgICBjb25zdCBidWZmZXIgPSBidWZmZXJzW2ldO1xuICAgICAgLy8gQ2hlY2sgdGhhdCBhbGwgYnVmZmVycyBleGNlcHQgdGhlIGxhc3Qgb25lIGhhdmUgdGhlIHNhbWUgbGVuZ3RoLlxuICAgICAgaWYgKGkgIT09IGJ1ZmZlcnMubGVuZ3RoIC0gMSAmJlxuICAgICAgICBidWZmZXIuYnl0ZUxlbmd0aCAhPT0gdGhpcy5idWZmZXJVbmlmb3JtU2l6ZSkge1xuICAgICAgICAvLyBVbnNldCB0aGUgYnVmZmVyIHVuaWZvcm0gc2l6ZSwgc2luY2UgdGhlIGJ1ZmZlciBzaXplcyBhcmUgbm90XG4gICAgICAgIC8vIHVuaWZvcm0uXG4gICAgICAgIHRoaXMuYnVmZmVyVW5pZm9ybVNpemUgPSB1bmRlZmluZWQ7XG4gICAgICB9XG5cbiAgICAgIC8vIENyZWF0ZSB0aGUgc2hhcmRzLCBpbmNsdWRpbmcgdGhlaXIgc3RhcnQgYW5kIGVuZCBwb2ludHMuXG4gICAgICBjb25zdCBlbmQgPSBzdGFydCArIGJ1ZmZlci5ieXRlTGVuZ3RoO1xuICAgICAgdGhpcy5zaGFyZHMucHVzaCh7IGJ1ZmZlciwgc3RhcnQsIGVuZCB9KTtcbiAgICAgIHN0YXJ0ID0gZW5kO1xuICAgIH1cblxuICAgIC8vIFNldCB0aGUgYnl0ZUxlbmdodGhcbiAgICBpZiAodGhpcy5zaGFyZHMubGVuZ3RoID09PSAwKSB7XG4gICAgICB0aGlzLmJ5dGVMZW5ndGggPSAwO1xuICAgIH1cbiAgICB0aGlzLmJ5dGVMZW5ndGggPSB0aGlzLnNoYXJkc1t0aGlzLnNoYXJkcy5sZW5ndGggLSAxXS5lbmQ7XG4gIH1cblxuICBzbGljZShzdGFydCA9IDAsIGVuZCA9IHRoaXMuYnl0ZUxlbmd0aCk6IEFycmF5QnVmZmVyIHtcbiAgICAvLyBJZiB0aGVyZSBhcmUgbm8gc2hhcmRzLCB0aGVuIHRoZSBDb21wb3NpdGVBcnJheUJ1ZmZlciB3YXMgaW5pdGlhbGl6ZWRcbiAgICAvLyB3aXRoIG5vIGRhdGEuXG4gICAgaWYgKHRoaXMuc2hhcmRzLmxlbmd0aCA9PT0gMCkge1xuICAgICAgcmV0dXJuIG5ldyBBcnJheUJ1ZmZlcigwKTtcbiAgICB9XG5cbiAgICAvLyBOYU4gaXMgdHJlYXRlZCBhcyB6ZXJvIGZvciBzbGljaW5nLiBUaGlzIG1hdGNoZXMgQXJyYXlCdWZmZXIncyBiZWhhdmlvci5cbiAgICBzdGFydCA9IGlzTmFOKE51bWJlcihzdGFydCkpID8gMCA6IHN0YXJ0O1xuICAgIGVuZCA9IGlzTmFOKE51bWJlcihlbmQpKSA/IDAgOiBlbmQ7XG5cbiAgICAvLyBGaXggdGhlIGJvdW5kcyB0byB3aXRoaW4gdGhlIGFycmF5LlxuICAgIHN0YXJ0ID0gTWF0aC5tYXgoMCwgc3RhcnQpO1xuICAgIGVuZCA9IE1hdGgubWluKHRoaXMuYnl0ZUxlbmd0aCwgZW5kKTtcbiAgICBpZiAoZW5kIDw9IHN0YXJ0KSB7XG4gICAgICByZXR1cm4gbmV3IEFycmF5QnVmZmVyKDApO1xuICAgIH1cblxuICAgIGNvbnN0IHN0YXJ0U2hhcmRJbmRleCA9IHRoaXMuZmluZFNoYXJkRm9yQnl0ZShzdGFydCk7XG4gICAgaWYgKHN0YXJ0U2hhcmRJbmRleCA9PT0gLTEpIHtcbiAgICAgIC8vIFRoaXMgc2hvdWxkIG5vdCBoYXBwZW4gc2luY2UgdGhlIHN0YXJ0IGFuZCBlbmQgaW5kaWNlcyBhcmUgYWx3YXlzXG4gICAgICAvLyB3aXRoaW4gMCBhbmQgdGhlIGNvbXBvc2l0ZSBhcnJheSdzIGxlbmd0aC5cbiAgICAgIHRocm93IG5ldyBFcnJvcihgQ291bGQgbm90IGZpbmQgc3RhcnQgc2hhcmQgZm9yIGJ5dGUgJHtzdGFydH1gKTtcbiAgICB9XG5cbiAgICBjb25zdCBzaXplID0gZW5kIC0gc3RhcnQ7XG4gICAgY29uc3Qgb3V0cHV0QnVmZmVyID0gbmV3IEFycmF5QnVmZmVyKHNpemUpO1xuICAgIGNvbnN0IG91dHB1dEFycmF5ID0gbmV3IFVpbnQ4QXJyYXkob3V0cHV0QnVmZmVyKTtcbiAgICBsZXQgc2xpY2VkID0gMDtcbiAgICBmb3IgKGxldCBpID0gc3RhcnRTaGFyZEluZGV4OyBpIDwgdGhpcy5zaGFyZHMubGVuZ3RoOyBpKyspIHtcbiAgICAgIGNvbnN0IHNoYXJkID0gdGhpcy5zaGFyZHNbaV07XG5cbiAgICAgIGNvbnN0IGdsb2JhbFN0YXJ0ID0gc3RhcnQgKyBzbGljZWQ7XG4gICAgICBjb25zdCBsb2NhbFN0YXJ0ID0gZ2xvYmFsU3RhcnQgLSBzaGFyZC5zdGFydDtcbiAgICAgIGNvbnN0IG91dHB1dFN0YXJ0ID0gc2xpY2VkO1xuXG4gICAgICBjb25zdCBnbG9iYWxFbmQgPSBNYXRoLm1pbihlbmQsIHNoYXJkLmVuZCk7XG4gICAgICBjb25zdCBsb2NhbEVuZCA9IGdsb2JhbEVuZCAtIHNoYXJkLnN0YXJ0O1xuXG4gICAgICBjb25zdCBvdXRwdXRTbGljZSA9IG5ldyBVaW50OEFycmF5KHNoYXJkLmJ1ZmZlciwgbG9jYWxTdGFydCxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbG9jYWxFbmQgLSBsb2NhbFN0YXJ0KTtcbiAgICAgIG91dHB1dEFycmF5LnNldChvdXRwdXRTbGljZSwgb3V0cHV0U3RhcnQpO1xuICAgICAgc2xpY2VkICs9IG91dHB1dFNsaWNlLmxlbmd0aDtcblxuICAgICAgaWYgKGVuZCA8IHNoYXJkLmVuZCkge1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIG91dHB1dEJ1ZmZlcjtcbiAgfVxuXG4gIC8qKlxuICAgKiBHZXQgdGhlIGluZGV4IG9mIHRoZSBzaGFyZCB0aGF0IGNvbnRhaW5zIHRoZSBieXRlIGF0IGBieXRlSW5kZXhgLlxuICAgKi9cbiAgcHJpdmF0ZSBmaW5kU2hhcmRGb3JCeXRlKGJ5dGVJbmRleDogbnVtYmVyKTogbnVtYmVyIHtcbiAgICBpZiAodGhpcy5zaGFyZHMubGVuZ3RoID09PSAwIHx8IGJ5dGVJbmRleCA8IDAgfHxcbiAgICAgIGJ5dGVJbmRleCA+PSB0aGlzLmJ5dGVMZW5ndGgpIHtcbiAgICAgIHJldHVybiAtMTtcbiAgICB9XG5cbiAgICAvLyBJZiB0aGUgYnVmZmVycyBoYXZlIGEgdW5pZm9ybSBzaXplLCBjb21wdXRlIHRoZSBzaGFyZCBkaXJlY3RseS5cbiAgICBpZiAodGhpcy5idWZmZXJVbmlmb3JtU2l6ZSAhPSBudWxsKSB7XG4gICAgICB0aGlzLnByZXZpb3VzU2hhcmRJbmRleCA9IE1hdGguZmxvb3IoYnl0ZUluZGV4IC8gdGhpcy5idWZmZXJVbmlmb3JtU2l6ZSk7XG4gICAgICByZXR1cm4gdGhpcy5wcmV2aW91c1NoYXJkSW5kZXg7XG4gICAgfVxuXG4gICAgLy8gSWYgdGhlIGJ1ZmZlcnMgZG9uJ3QgaGF2ZSBhIHVuaWZvcm0gc2l6ZSwgd2UgbmVlZCB0byBzZWFyY2ggZm9yIHRoZVxuICAgIC8vIHNoYXJkLiBUaGF0IG1lYW5zIHdlIG5lZWQgYSBmdW5jdGlvbiB0byBjaGVjayB3aGVyZSB0aGUgYnl0ZUluZGV4IGxpZXNcbiAgICAvLyByZWxhdGl2ZSB0byBhIGdpdmVuIHNoYXJkLlxuICAgIGZ1bmN0aW9uIGNoZWNrKHNoYXJkOiBCdWZmZXJTaGFyZCkge1xuICAgICAgaWYgKGJ5dGVJbmRleCA8IHNoYXJkLnN0YXJ0KSB7XG4gICAgICAgIHJldHVybiAtMTtcbiAgICAgIH1cbiAgICAgIGlmIChieXRlSW5kZXggPj0gc2hhcmQuZW5kKSB7XG4gICAgICAgIHJldHVybiAxO1xuICAgICAgfVxuICAgICAgcmV0dXJuIDA7XG4gICAgfVxuXG4gICAgLy8gRm9yIGVmZmljaWVuY3ksIHRyeSB0aGUgcHJldmlvdXMgc2hhcmQgZmlyc3QuXG4gICAgaWYgKGNoZWNrKHRoaXMuc2hhcmRzW3RoaXMucHJldmlvdXNTaGFyZEluZGV4XSkgPT09IDApIHtcbiAgICAgIHJldHVybiB0aGlzLnByZXZpb3VzU2hhcmRJbmRleDtcbiAgICB9XG5cbiAgICAvLyBPdGhlcndpc2UsIHVzZSBhIGdlbmVyaWMgc2VhcmNoIGZ1bmN0aW9uLlxuICAgIC8vIFRoaXMgc2hvdWxkIGFsbW9zdCBuZXZlciBlbmQgdXAgYmVpbmcgdXNlZCBpbiBwcmFjdGljZSBzaW5jZSB0aGUgd2VpZ2h0XG4gICAgLy8gZW50cmllcyBzaG91bGQgYWx3YXlzIGJlIGluIG9yZGVyLlxuICAgIGNvbnN0IGluZGV4ID0gc2VhcmNoKHRoaXMuc2hhcmRzLCBjaGVjayk7XG4gICAgaWYgKGluZGV4ID09PSAtMSkge1xuICAgICAgcmV0dXJuIC0xO1xuICAgIH1cblxuICAgIHRoaXMucHJldmlvdXNTaGFyZEluZGV4ID0gaW5kZXg7XG4gICAgcmV0dXJuIHRoaXMucHJldmlvdXNTaGFyZEluZGV4O1xuICB9XG59XG5cbi8qKlxuICogU2VhcmNoIGZvciBhbiBlbGVtZW50IG9mIGEgc29ydGVkIGFycmF5LlxuICpcbiAqIEBwYXJhbSBzb3J0ZWRBcnJheSBUaGUgc29ydGVkIGFycmF5IHRvIHNlYXJjaFxuICogQHBhcmFtIGNvbXBhcmUgQSBmdW5jdGlvbiB0byBjb21wYXJlIHRoZSBjdXJyZW50IHZhbHVlIGFnYWluc3QgdGhlIHNlYXJjaGVkXG4gKiAgICAgdmFsdWUuIFJldHVybiAwIG9uIGEgbWF0Y2gsIG5lZ2F0aXZlIGlmIHRoZSBzZWFyY2hlZCB2YWx1ZSBpcyBsZXNzIHRoYW5cbiAqICAgICB0aGUgdmFsdWUgcGFzc2VkIHRvIHRoZSBmdW5jdGlvbiwgYW5kIHBvc2l0aXZlIGlmIHRoZSBzZWFyY2hlZCB2YWx1ZSBpc1xuICogICAgIGdyZWF0ZXIgdGhhbiB0aGUgdmFsdWUgcGFzc2VkIHRvIHRoZSBmdW5jdGlvbi5cbiAqIEByZXR1cm5zIFRoZSBpbmRleCBvZiB0aGUgZWxlbWVudCwgb3IgLTEgaWYgaXQncyBub3QgaW4gdGhlIGFycmF5LlxuICovXG5leHBvcnQgZnVuY3Rpb24gc2VhcmNoPFQ+KHNvcnRlZEFycmF5OiBUW10sIGNvbXBhcmU6ICh0OiBUKSA9PiBudW1iZXIpOiBudW1iZXIge1xuICAvLyBCaW5hcnkgc2VhcmNoXG4gIGxldCBtaW4gPSAwO1xuICBsZXQgbWF4ID0gc29ydGVkQXJyYXkubGVuZ3RoO1xuXG4gIHdoaWxlIChtaW4gPD0gbWF4KSB7XG4gICAgY29uc3QgbWlkZGxlID0gTWF0aC5mbG9vcigobWF4IC0gbWluKSAvIDIpICsgbWluO1xuICAgIGNvbnN0IHNpZGUgPSBjb21wYXJlKHNvcnRlZEFycmF5W21pZGRsZV0pO1xuXG4gICAgaWYgKHNpZGUgPT09IDApIHtcbiAgICAgIHJldHVybiBtaWRkbGU7XG4gICAgfSBlbHNlIGlmIChzaWRlIDwgMCkge1xuICAgICAgbWF4ID0gbWlkZGxlO1xuICAgIH0gZWxzZSB7XG4gICAgICBtaW4gPSBtaWRkbGUgKyAxO1xuICAgIH1cbiAgfVxuICByZXR1cm4gLTE7XG59XG4iXX0=