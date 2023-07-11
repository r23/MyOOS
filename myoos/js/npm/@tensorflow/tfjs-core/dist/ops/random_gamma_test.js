/**
 * @license
 * Copyright 2020 Google LLC. All Rights Reserved.
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
import * as tf from '../index';
import { ALL_ENVS, describeWithFlags } from '../jasmine_util';
import { expectValuesInRange } from '../test_util';
const GAMMA_MIN = 0;
const GAMMA_MAX = 40;
describeWithFlags('randomGamma', ALL_ENVS, () => {
    it('should return a random 1D float32 array', async () => {
        const shape = [10];
        // Ensure defaults to float32 w/o type:
        let result = tf.randomGamma(shape, 2, 2);
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
        result = tf.randomGamma(shape, 2, 2, 'float32');
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should return a random 1D int32 array', async () => {
        const shape = [10];
        const result = tf.randomGamma(shape, 2, 2, 'int32');
        expect(result.dtype).toBe('int32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should return a random 2D float32 array', async () => {
        const shape = [3, 4];
        // Ensure defaults to float32 w/o type:
        let result = tf.randomGamma(shape, 2, 2);
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
        result = tf.randomGamma(shape, 2, 2, 'float32');
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should return a random 2D int32 array', async () => {
        const shape = [3, 4];
        const result = tf.randomGamma(shape, 2, 2, 'int32');
        expect(result.dtype).toBe('int32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should return a random 3D float32 array', async () => {
        const shape = [3, 4, 5];
        // Ensure defaults to float32 w/o type:
        let result = tf.randomGamma(shape, 2, 2);
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
        result = tf.randomGamma(shape, 2, 2, 'float32');
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should return a random 3D int32 array', async () => {
        const shape = [3, 4, 5];
        const result = tf.randomGamma(shape, 2, 2, 'int32');
        expect(result.dtype).toBe('int32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should return a random 4D float32 array', async () => {
        const shape = [3, 4, 5, 6];
        // Ensure defaults to float32 w/o type:
        let result = tf.randomGamma(shape, 2, 2);
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
        result = tf.randomGamma(shape, 2, 2, 'float32');
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should return a random 4D int32 array', async () => {
        const shape = [3, 4, 5, 6];
        const result = tf.randomGamma(shape, 2, 2, 'int32');
        expect(result.dtype).toBe('int32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should return a random 5D float32 array', async () => {
        const shape = [2, 3, 4, 5, 6];
        // Ensure defaults to float32 w/o type:
        let result = tf.randomGamma(shape, 2, 2);
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
        result = tf.randomGamma(shape, 2, 2, 'float32');
        expect(result.dtype).toBe('float32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should return a random 5D int32 array', async () => {
        const shape = [2, 3, 4, 5, 6];
        const result = tf.randomGamma(shape, 2, 2, 'int32');
        expect(result.dtype).toBe('int32');
        expectValuesInRange(await result.data(), GAMMA_MIN, GAMMA_MAX);
    });
    it('should throw error when shape is not integer', () => {
        expect(() => tf.randomGamma([2, 2.22, 3.33], 2, 2)).toThrow();
    });
});
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoicmFuZG9tX2dhbW1hX3Rlc3QuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlcyI6WyIuLi8uLi8uLi8uLi8uLi8uLi90ZmpzLWNvcmUvc3JjL29wcy9yYW5kb21fZ2FtbWFfdGVzdC50cyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7O0dBZUc7QUFFSCxPQUFPLEtBQUssRUFBRSxNQUFNLFVBQVUsQ0FBQztBQUMvQixPQUFPLEVBQUMsUUFBUSxFQUFFLGlCQUFpQixFQUFDLE1BQU0saUJBQWlCLENBQUM7QUFDNUQsT0FBTyxFQUFDLG1CQUFtQixFQUFDLE1BQU0sY0FBYyxDQUFDO0FBRWpELE1BQU0sU0FBUyxHQUFHLENBQUMsQ0FBQztBQUNwQixNQUFNLFNBQVMsR0FBRyxFQUFFLENBQUM7QUFFckIsaUJBQWlCLENBQUMsYUFBYSxFQUFFLFFBQVEsRUFBRSxHQUFHLEVBQUU7SUFDOUMsRUFBRSxDQUFDLHlDQUF5QyxFQUFFLEtBQUssSUFBSSxFQUFFO1FBQ3ZELE1BQU0sS0FBSyxHQUFhLENBQUMsRUFBRSxDQUFDLENBQUM7UUFFN0IsdUNBQXVDO1FBQ3ZDLElBQUksTUFBTSxHQUFHLEVBQUUsQ0FBQyxXQUFXLENBQUMsS0FBSyxFQUFFLENBQUMsRUFBRSxDQUFDLENBQUMsQ0FBQztRQUN6QyxNQUFNLENBQUMsTUFBTSxDQUFDLEtBQUssQ0FBQyxDQUFDLElBQUksQ0FBQyxTQUFTLENBQUMsQ0FBQztRQUNyQyxtQkFBbUIsQ0FBQyxNQUFNLE1BQU0sQ0FBQyxJQUFJLEVBQUUsRUFBRSxTQUFTLEVBQUUsU0FBUyxDQUFDLENBQUM7UUFFL0QsTUFBTSxHQUFHLEVBQUUsQ0FBQyxXQUFXLENBQUMsS0FBSyxFQUFFLENBQUMsRUFBRSxDQUFDLEVBQUUsU0FBUyxDQUFDLENBQUM7UUFDaEQsTUFBTSxDQUFDLE1BQU0sQ0FBQyxLQUFLLENBQUMsQ0FBQyxJQUFJLENBQUMsU0FBUyxDQUFDLENBQUM7UUFDckMsbUJBQW1CLENBQUMsTUFB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QUFDO1FBQ2hELE1BQU0sQ0FBQyxNQUFNLENBQUMsS0FBSyxDQUFDLENBQUMsSUFBSSxDQUFDLFNBQVMsQ0FBQyxDQUFDO1FBQ3JDLG1CQUFtQixDQUFDLE1BQU0sTUFBTSxDQUFDLElBQUksRUFBRSxFQUFFLFNBQVMsRUFBRSxTQUFTLENBQUMsQ0FBQztJQUNqRSxDQUFDLENBQUMsQ0FBQztJQUVILEVBQUUsQ0FBQyx1Q0FBdUMsRUFBRSxLQUFLLElBQUksRUFBRTtRQUNyRCxNQUFNLEtBQUssR0FBNkMsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxFQUFFLENBQUMsRUFBRSxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUM7UUFDeEUsTUFBTSxNQUFNLEdBQUcsRUFBRSxDQUFDLFdBQVcsQ0FBQyxLQUFLLEVBQUUsQ0FBQyxFQUFFLENBQUMsRUFBRSxPQUFPLENBQUMsQ0FBQztRQUNwRCxNQUFNLENBQUMsTUFBTSxDQUFDLEtBQUssQ0FBQyxDQUFDLElBQUksQ0FBQyxPQUFPLENBQUMsQ0FBQztRQUNuQyxtQkFBbUIsQ0FBQyxNQUFNLE1BQU0sQ0FBQyxJQUFJLEVBQUUsRUFBRSxTQUFTLEVBQUUsU0FBUyxDQUFDLENBQUM7SUFDakUsQ0FBQyxDQUFDLENBQUM7SUFFSCxFQUFFLENBQUMsOENBQThDLEVBQUUsR0FBRyxFQUFFO1FBQ3RELE1BQU0sQ0FBQyxHQUFHLEVBQUUsQ0FBQyxFQUFFLENBQUMsV0FBVyxDQUFDLENBQUMsQ0FBQyxFQUFFLElBQUksRUFBRSxJQUFJLENBQUMsRUFBRSxDQUFDLEVBQUUsQ0FBQyxDQUFDLENBQUMsQ0FBQyxPQUFPLEVBQUUsQ0FBQztJQUNoRSxDQUFDLENBQUMsQ0FBQztBQUNMLENBQUMsQ0FBQyxDQUFDIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBAbGljZW5zZVxuICogQ29weXJpZ2h0IDIwMjAgR29vZ2xlIExMQy4gQWxsIFJpZ2h0cyBSZXNlcnZlZC5cbiAqIExpY2Vuc2VkIHVuZGVyIHRoZSBBcGFjaGUgTGljZW5zZSwgVmVyc2lvbiAyLjAgKHRoZSBcIkxpY2Vuc2VcIik7XG4gKiB5b3UgbWF5IG5vdCB1c2UgdGhpcyBmaWxlIGV4Y2VwdCBpbiBjb21wbGlhbmNlIHdpdGggdGhlIExpY2Vuc2UuXG4gKiBZb3UgbWF5IG9idGFpbiBhIGNvcHkgb2YgdGhlIExpY2Vuc2UgYXRcbiAqXG4gKiBodHRwOi8vd3d3LmFwYWNoZS5vcmcvbGljZW5zZXMvTElDRU5TRS0yLjBcbiAqXG4gKiBVbmxlc3MgcmVxdWlyZWQgYnkgYXBwbGljYWJsZSBsYXcgb3IgYWdyZWVkIHRvIGluIHdyaXRpbmcsIHNvZnR3YXJlXG4gKiBkaXN0cmlidXRlZCB1bmRlciB0aGUgTGljZW5zZSBpcyBkaXN0cmlidXRlZCBvbiBhbiBcIkFTIElTXCIgQkFTSVMsXG4gKiBXSVRIT1VUIFdBUlJBTlRJRVMgT1IgQ09ORElUSU9OUyBPRiBBTlkgS0lORCwgZWl0aGVyIGV4cHJlc3Mgb3IgaW1wbGllZC5cbiAqIFNlZSB0aGUgTGljZW5zZSBmb3IgdGhlIHNwZWNpZmljIGxhbmd1YWdlIGdvdmVybmluZyBwZXJtaXNzaW9ucyBhbmRcbiAqIGxpbWl0YXRpb25zIHVuZGVyIHRoZSBMaWNlbnNlLlxuICogPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cbiAqL1xuXG5pbXBvcnQgKiBhcyB0ZiBmcm9tICcuLi9pbmRleCc7XG5pbXBvcnQge0FMTF9FTlZTLCBkZXNjcmliZVdpdGhGbGFnc30gZnJvbSAnLi4vamFzbWluZV91dGlsJztcbmltcG9ydCB7ZXhwZWN0VmFsdWVzSW5SYW5nZX0gZnJvbSAnLi4vdGVzdF91dGlsJztcblxuY29uc3QgR0FNTUFfTUlOID0gMDtcbmNvbnN0IEdBTU1BX01BWCA9IDQwO1xuXG5kZXNjcmliZVdpdGhGbGFncygncmFuZG9tR2FtbWEnLCBBTExfRU5WUywgKCkgPT4ge1xuICBpdCgnc2hvdWxkIHJldHVybiBhIHJhbmRvbSAxRCBmbG9hdDMyIGFycmF5JywgYXN5bmMgKCkgPT4ge1xuICAgIGNvbnN0IHNoYXBlOiBbbnVtYmVyXSA9IFsxMF07XG5cbiAgICAvLyBFbnN1cmUgZGVmYXVsdHMgdG8gZmxvYXQzMiB3L28gdHlwZTpcbiAgICBsZXQgcmVzdWx0ID0gdGYucmFuZG9tR2FtbWEoc2hhcGUsIDIsIDIpO1xuICAgIGV4cGVjdChyZXN1bHQuZHR5cGUpLnRvQmUoJ2Zsb2F0MzInKTtcbiAgICBleHBlY3RWYWx1ZXNJblJhbmdlKGF3YWl0IHJlc3VsdC5kYXRhKCksIEdBTU1BX01JTiwgR0FNTUFfTUFYKTtcblxuICAgIHJlc3VsdCA9IHRmLnJhbmRvbUdhbW1hKHNoYXBlLCAyLCAyLCAnZmxvYXQzMicpO1xuICAgIGV4cGVjdChyZXN1bHQuZHR5cGUpLnRvQmUoJ2Zsb2F0MzInKTtcbiAgICBleHBlY3RWYWx1ZXNJblJhbmdlKGF3YWl0IHJlc3VsdC5kYXRhKCksIEdBTU1BX01JTiwgR0FNTUFfTUFYKTtcbiAgfSk7XG5cbiAgaXQoJ3Nob3VsZCByZXR1cm4gYSByYW5kb20gMUQgaW50MzIgYXJyYXknLCBhc3luYyAoKSA9PiB7XG4gICAgY29uc3Qgc2hhcGU6IFtudW1iZXJdID0gWzEwXTtcbiAgICBjb25zdCByZXN1bHQgPSB0Zi5yYW5kb21HYW1tYShzaGFwZSwgMiwgMiwgJ2ludDMyJyk7XG4gICAgZXhwZWN0KHJlc3VsdC5kdHlwZSkudG9CZSgnaW50MzInKTtcbiAgICBleHBlY3RWYWx1ZXNJblJhbmdlKGF3YWl0IHJlc3VsdC5kYXRhKCksIEdBTU1BX01JTiwgR0FNTUFfTUFYKTtcbiAgfSk7XG5cbiAgaXQoJ3Nob3VsZCByZXR1cm4gYSByYW5kb20gMkQgZmxvYXQzMiBhcnJheScsIGFzeW5jICgpID0+IHtcbiAgICBjb25zdCBzaGFwZTogW251bWJlciwgbnVtYmVyXSA9IFszLCA0XTtcblxuICAgIC8vIEVuc3VyZSBkZWZhdWx0cyB0byBmbG9hdDMyIHcvbyB0eXBlOlxuICAgIGxldCByZXN1bHQgPSB0Zi5yYW5kb21HYW1tYShzaGFwZSwgMiwgMik7XG4gICAgZXhwZWN0KHJlc3VsdC5kdHlwZSkudG9CZSgnZmxvYXQzMicpO1xuICAgIGV4cGVjdFZhbHVlc0luUmFuZ2UoYXdhaXQgcmVzdWx0LmRhdGEoKSwgR0FNTUFfTUlOLCBHQU1NQV9NQVgpO1xuXG4gICAgcmVzdWx0ID0gdGYucmFuZG9tR2FtbWEoc2hhcGUsIDIsIDIsICdmbG9hdDMyJyk7XG4gICAgZXhwZWN0KHJlc3VsdC5kdHlwZSkudG9CZSgnZmxvYXQzMicpO1xuICAgIGV4cGVjdFZhbHVlc0luUmFuZ2UoYXdhaXQgcmVzdWx0LmRhdGEoKSwgR0FNTUFfTUlOLCBHQU1NQV9NQVgpO1xuICB9KTtcblxuICBpdCgnc2hvdWxkIHJldHVybiBhIHJhbmRvbSAyRCBpbnQzMiBhcnJheScsIGFzeW5jICgpID0+IHtcbiAgICBjb25zdCBzaGFwZTogW251bWJlciwgbnVtYmVyXSA9IFszLCA0XTtcbiAgICBjb25zdCByZXN1bHQgPSB0Zi5yYW5kb21HYW1tYShzaGFwZSwgMiwgMiwgJ2ludDMyJyk7XG4gICAgZXhwZWN0KHJlc3VsdC5kdHlwZSkudG9CZSgnaW50MzInKTtcbiAgICBleHBlY3RWYWx1ZXNJblJhbmdlKGF3YWl0IHJlc3VsdC5kYXRhKCksIEdBTU1BX01JTiwgR0FNTUFfTUFYKTtcbiAgfSk7XG5cbiAgaXQoJ3Nob3VsZCByZXR1cm4gYSByYW5kb20gM0QgZmxvYXQzMiBhcnJheScsIGFzeW5jICgpID0+IHtcbiAgICBjb25zdCBzaGFwZTogW251bWJlciwgbnVtYmVyLCBudW1iZXJdID0gWzMsIDQsIDVdO1xuXG4gICAgLy8gRW5zdXJlIGRlZmF1bHRzIHRvIGZsb2F0MzIgdy9vIHR5cGU6XG4gICAgbGV0IHJlc3VsdCA9IHRmLnJhbmRvbUdhbW1hKHNoYXBlLCAyLCAyKTtcbiAgICBleHBlY3QocmVzdWx0LmR0eXBlKS50b0JlKCdmbG9hdDMyJyk7XG4gICAgZXhwZWN0VmFsdWVzSW5SYW5nZShhd2FpdCByZXN1bHQuZGF0YSgpLCBHQU1NQV9NSU4sIEdBTU1BX01BWCk7XG5cbiAgICByZXN1bHQgPSB0Zi5yYW5kb21HYW1tYShzaGFwZSwgMiwgMiwgJ2Zsb2F0MzInKTtcbiAgICBleHBlY3QocmVzdWx0LmR0eXBlKS50b0JlKCdmbG9hdDMyJyk7XG4gICAgZXhwZWN0VmFsdWVzSW5SYW5nZShhd2FpdCByZXN1bHQuZGF0YSgpLCBHQU1NQV9NSU4sIEdBTU1BX01BWCk7XG4gIH0pO1xuXG4gIGl0KCdzaG91bGQgcmV0dXJuIGEgcmFuZG9tIDNEIGludDMyIGFycmF5JywgYXN5bmMgKCkgPT4ge1xuICAgIGNvbnN0IHNoYXBlOiBbbnVtYmVyLCBudW1iZXIsIG51bWJlcl0gPSBbMywgNCwgNV07XG4gICAgY29uc3QgcmVzdWx0ID0gdGYucmFuZG9tR2FtbWEoc2hhcGUsIDIsIDIsICdpbnQzMicpO1xuICAgIGV4cGVjdChyZXN1bHQuZHR5cGUpLnRvQmUoJ2ludDMyJyk7XG4gICAgZXhwZWN0VmFsdWVzSW5SYW5nZShhd2FpdCByZXN1bHQuZGF0YSgpLCBHQU1NQV9NSU4sIEdBTU1BX01BWCk7XG4gIH0pO1xuXG4gIGl0KCdzaG91bGQgcmV0dXJuIGEgcmFuZG9tIDREIGZsb2F0MzIgYXJyYXknLCBhc3luYyAoKSA9PiB7XG4gICAgY29uc3Qgc2hhcGU6IFtudW1iZXIsIG51bWJlciwgbnVtYmVyLCBudW1iZXJdID0gWzMsIDQsIDUsIDZdO1xuXG4gICAgLy8gRW5zdXJlIGRlZmF1bHRzIHRvIGZsb2F0MzIgdy9vIHR5cGU6XG4gICAgbGV0IHJlc3VsdCA9IHRmLnJhbmRvbUdhbW1hKHNoYXBlLCAyLCAyKTtcbiAgICBleHBlY3QocmVzdWx0LmR0eXBlKS50b0JlKCdmbG9hdDMyJyk7XG4gICAgZXhwZWN0VmFsdWVzSW5SYW5nZShhd2FpdCByZXN1bHQuZGF0YSgpLCBHQU1NQV9NSU4sIEdBTU1BX01BWCk7XG5cbiAgICByZXN1bHQgPSB0Zi5yYW5kb21HYW1tYShzaGFwZSwgMiwgMiwgJ2Zsb2F0MzInKTtcbiAgICBleHBlY3QocmVzdWx0LmR0eXBlKS50b0JlKCdmbG9hdDMyJyk7XG4gICAgZXhwZWN0VmFsdWVzSW5SYW5nZShhd2FpdCByZXN1bHQuZGF0YSgpLCBHQU1NQV9NSU4sIEdBTU1BX01BWCk7XG4gIH0pO1xuXG4gIGl0KCdzaG91bGQgcmV0dXJuIGEgcmFuZG9tIDREIGludDMyIGFycmF5JywgYXN5bmMgKCkgPT4ge1xuICAgIGNvbnN0IHNoYXBlOiBbbnVtYmVyLCBudW1iZXIsIG51bWJlciwgbnVtYmVyXSA9IFszLCA0LCA1LCA2XTtcbiAgICBjb25zdCByZXN1bHQgPSB0Zi5yYW5kb21HYW1tYShzaGFwZSwgMiwgMiwgJ2ludDMyJyk7XG4gICAgZXhwZWN0KHJlc3VsdC5kdHlwZSkudG9CZSgnaW50MzInKTtcbiAgICBleHBlY3RWYWx1ZXNJblJhbmdlKGF3YWl0IHJlc3VsdC5kYXRhKCksIEdBTU1BX01JTiwgR0FNTUFfTUFYKTtcbiAgfSk7XG5cbiAgaXQoJ3Nob3VsZCByZXR1cm4gYSByYW5kb20gNUQgZmxvYXQzMiBhcnJheScsIGFzeW5jICgpID0+IHtcbiAgICBjb25zdCBzaGFwZTogW251bWJlciwgbnVtYmVyLCBudW1iZXIsIG51bWJlciwgbnVtYmVyXSA9IFsyLCAzLCA0LCA1LCA2XTtcblxuICAgIC8vIEVuc3VyZSBkZWZhdWx0cyB0byBmbG9hdDMyIHcvbyB0eXBlOlxuICAgIGxldCByZXN1bHQgPSB0Zi5yYW5kb21HYW1tYShzaGFwZSwgMiwgMik7XG4gICAgZXhwZWN0KHJlc3VsdC5kdHlwZSkudG9CZSgnZmxvYXQzMicpO1xuICAgIGV4cGVjdFZhbHVlc0luUmFuZ2UoYXdhaXQgcmVzdWx0LmRhdGEoKSwgR0FNTUFfTUlOLCBHQU1NQV9NQVgpO1xuXG4gICAgcmVzdWx0ID0gdGYucmFuZG9tR2FtbWEoc2hhcGUsIDIsIDIsICdmbG9hdDMyJyk7XG4gICAgZXhwZWN0KHJlc3VsdC5kdHlwZSkudG9CZSgnZmxvYXQzMicpO1xuICAgIGV4cGVjdFZhbHVlc0luUmFuZ2UoYXdhaXQgcmVzdWx0LmRhdGEoKSwgR0FNTUFfTUlOLCBHQU1NQV9NQVgpO1xuICB9KTtcblxuICBpdCgnc2hvdWxkIHJldHVybiBhIHJhbmRvbSA1RCBpbnQzMiBhcnJheScsIGFzeW5jICgpID0+IHtcbiAgICBjb25zdCBzaGFwZTogW251bWJlciwgbnVtYmVyLCBudW1iZXIsIG51bWJlciwgbnVtYmVyXSA9IFsyLCAzLCA0LCA1LCA2XTtcbiAgICBjb25zdCByZXN1bHQgPSB0Zi5yYW5kb21HYW1tYShzaGFwZSwgMiwgMiwgJ2ludDMyJyk7XG4gICAgZXhwZWN0KHJlc3VsdC5kdHlwZSkudG9CZSgnaW50MzInKTtcbiAgICBleHBlY3RWYWx1ZXNJblJhbmdlKGF3YWl0IHJlc3VsdC5kYXRhKCksIEdBTU1BX01JTiwgR0FNTUFfTUFYKTtcbiAgfSk7XG5cbiAgaXQoJ3Nob3VsZCB0aHJvdyBlcnJvciB3aGVuIHNoYXBlIGlzIG5vdCBpbnRlZ2VyJywgKCkgPT4ge1xuICAgIGV4cGVjdCgoKSA9PiB0Zi5yYW5kb21HYW1tYShbMiwgMi4yMiwgMy4zM10sIDIsIDIpKS50b1Rocm93KCk7XG4gIH0pO1xufSk7XG4iXX0=