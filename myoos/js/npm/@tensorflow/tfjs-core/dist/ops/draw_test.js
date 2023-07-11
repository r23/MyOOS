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
import * as tf from '../index';
import { BROWSER_ENVS, describeWithFlags } from '../jasmine_util';
import { expectArraysClose, expectArraysEqual } from '../test_util';
class MockContext {
    getImageData() {
        return this.data;
    }
    putImageData(data, x, y) {
        this.data = data;
    }
}
class MockCanvas {
    constructor(width, height) {
        this.width = width;
        this.height = height;
    }
    getContext(type) {
        if (this.context == null) {
            this.context = new MockContext();
        }
        return this.context;
    }
}
describeWithFlags('Draw on 2d context', BROWSER_ENVS, () => {
    it('draw image with 4 channels and int values', async () => {
        const data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];
        const img = tf.tensor3d(data, [2, 2, 4], 'int32');
        const canvas = new MockCanvas(2, 2);
        const ctx = canvas.getContext('2d');
        // tslint:disable-next-line:no-any
        tf.browser.draw(img, canvas, { contextOptions: { contextType: '2d' } });
        expectArraysEqual(ctx.getImageData().data, data);
    });
    it('draw image with 4 channels and float values', async () => {
        const data = [.1, .2, .3, .4, .5, .6, .7, .8, .9, .1, .11, .12, .13, .14, .15, .16];
        const img = tf.tensor3d(data, [2, 2, 4]);
        const canvas = new MockCanvas(2, 2);
        const ctx = canvas.getContext('2d');
        // tslint:disable-next-line:no-any
        tf.browser.draw(img, canvas, { contextOptions: { contextType: '2d' } });
        const actualData = ctx.getImageData().data;
        const expectedData = data.map(e => Math.round(e * 255));
        expectArraysClose(actualData, expectedData, 1);
    });
    it('draw 2D image in grayscale', async () => {
        const data = [1, 2, 3, 4];
        const img = tf.tensor2d(data, [2, 2], 'int32');
        const canvas = new MockCanvas(2, 2);
        const ctx = canvas.getContext('2d');
        // tslint:disable-next-line:no-any
        tf.browser.draw(img, canvas, { contextOptions: { contextType: '2d' } });
        const actualData = ctx.getImageData().data;
        const expectedData = [1, 1, 1, 255, 2, 2, 2, 255, 3, 3, 3, 255, 4, 4, 4, 255];
        expectArraysEqual(actualData, expectedData);
    });
    it('draw image with alpha=0.5', async () => {
        const data = [1, 2, 3, 4];
        const img = tf.tensor3d(data, [2, 2, 1], 'int32');
        const canvas = new MockCanvas(2, 2);
        const ctx = canvas.getContext('2d');
        const drawOptions = {
            contextOptions: { contextType: '2d' },
            imageOptions: { alpha: 0.5 }
        };
        // tslint:disable-next-line:no-any
        tf.browser.draw(img, canvas, drawOptions);
        const actualData = ctx.getImageData().data;
        const expectedData = [1, 1, 1, 128, 2, 2, 2, 128, 3, 3, 3, 128, 4, 4, 4, 128];
        expectArraysEqual(actualData, expectedData);
    });
});
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiZHJhd190ZXN0LmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXMiOlsiLi4vLi4vLi4vLi4vLi4vLi4vdGZqcy1jb3JlL3NyYy9vcHMvZHJhd190ZXN0LnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBOzs7Ozs7Ozs7Ozs7Ozs7R0FlRztBQUVILE9BQU8sS0FBSyxFQUFFLE1BQU0sVUFBVSxDQUFDO0FBQy9CLE9BQU8sRUFBQyxZQUFZLEVBQUUsaUJBQWlCLEVBQUMsTUFBTSxpQkFBaUIsQ0FBQztBQUNoRSxPQUFPLEVBQUMsaUJBQWlCLEVBQUUsaUJBQWlCLEVBQUMsTUFBTSxjQUFjLENBQUM7QUFFbEUsTUFBTSxXQUFXO0lBR2YsWUFBWTtRQUNWLE9BQU8sSUFBSSxDQUFDLElBQUksQ0FBQztJQUNuQixDQUFDO0lBRUQsWUFBWSxDQUFDLElBQWUsRUFBRSxDQUFTLEVBQUUsQ0FBUztRQUNoRCxJQUFJLENBQUMsSUFBSSxHQUFHLElBQUksQ0FBQztJQUNuQixDQUFDO0NBQ0Y7QUFFRCxNQUFNLFVBQVU7SUFHZCxZQUFtQixLQUFhLEVBQVMsTUFBYztRQUFwQyxVQUFLLEdBQUwsS0FBSyxDQUFRO1FBQVMsV0FBTSxHQUFOLE1BQU0sQ0FBUTtJQUFHLENBQUM7SUFFM0QsVUFBVSxDQUFDLElBQVU7UUFDbkIsSUFBSSxJQUFJLENBQUMsT0FBTyxJQUFJLElBQUksRUFBRTtZQUN4QixJQUFJLENBQUMsT0FBTyxHQUFHLElBQUksV0FBVyxFQUFFLENBQUM7U0FDbEM7UUFDRCxPQUFPLElBQUksQ0FBQyxPQUFPLENBQUM7SUFDdEIsQ0FBQztDQUNGO0FBRUQsaUJBQWlCLENBQUMsb0JBQW9CLEVBQUUsWUFBWSxFQUFFLEdBQUcsRUFBRTtJQUN6RCxFQUFFLENBQUMsMkNBQTJDLEVBQUUsS0FBSyxJQUFJLEVBQUU7UUFDekQsTUFBTSxJQUFJLEdBQUcsQ0FBQyxDQUFDLEVBQUUsQ0FBQyxFQUFFLENBQUMsRUFBRSxDQUFDLEVBQUUsQ0FBQyxFQUFFLENBQUMs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SSxDQUFDO1FBQzNDLE1BQU0sWUFBWSxHQUNkLENBQUMsQ0FBQyxFQUFFLENBQUMsRUFBRSxDQUFDLEVBQUUsR0FBRyxFQUFFLENBQUMsRUFBRSxDQUFDLEVBQUUsQ0FBQyxFQUFFLEdBQUcsRUFBRSxDQUFDLEVBQUUsQ0FBQyxFQUFFLENBQUMsRUFBRSxHQUFHLEVBQUUsQ0FBQyxFQUFFLENBQUMsRUFBRSxDQUFDLEVBQUUsR0FBRyxDQUFDLENBQUM7UUFDN0QsaUJBQWlCLENBQUMsVUFBVSxFQUFFLFlBQVksQ0FBQyxDQUFDO0lBQzlDLENBQUMsQ0FBQyxDQUFDO0FBQ0wsQ0FBQyxDQUFDLENBQUMiLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEBsaWNlbnNlXG4gKiBDb3B5cmlnaHQgMjAyMyBHb29nbGUgTExDLlxuICogTGljZW5zZWQgdW5kZXIgdGhlIEFwYWNoZSBMaWNlbnNlLCBWZXJzaW9uIDIuMCAodGhlIFwiTGljZW5zZVwiKTtcbiAqIHlvdSBtYXkgbm90IHVzZSB0aGlzIGZpbGUgZXhjZXB0IGluIGNvbXBsaWFuY2Ugd2l0aCB0aGUgTGljZW5zZS5cbiAqIFlvdSBtYXkgb2J0YWluIGEgY29weSBvZiB0aGUgTGljZW5zZSBhdFxuICpcbiAqIGh0dHA6Ly93d3cuYXBhY2hlLm9yZy9saWNlbnNlcy9MSUNFTlNFLTIuMFxuICpcbiAqIFVubGVzcyByZXF1aXJlZCBieSBhcHBsaWNhYmxlIGxhdyBvciBhZ3JlZWQgdG8gaW4gd3JpdGluZywgc29mdHdhcmVcbiAqIGRpc3RyaWJ1dGVkIHVuZGVyIHRoZSBMaWNlbnNlIGlzIGRpc3RyaWJ1dGVkIG9uIGFuIFwiQVMgSVNcIiBCQVNJUyxcbiAqIFdJVEhPVVQgV0FSUkFOVElFUyBPUiBDT05ESVRJT05TIE9GIEFOWSBLSU5ELCBlaXRoZXIgZXhwcmVzcyBvciBpbXBsaWVkLlxuICogU2VlIHRoZSBMaWNlbnNlIGZvciB0aGUgc3BlY2lmaWMgbGFuZ3VhZ2UgZ292ZXJuaW5nIHBlcm1pc3Npb25zIGFuZFxuICogbGltaXRhdGlvbnMgdW5kZXIgdGhlIExpY2Vuc2UuXG4gKiA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PVxuICovXG5cbmltcG9ydCAqIGFzIHRmIGZyb20gJy4uL2luZGV4JztcbmltcG9ydCB7QlJPV1NFUl9FTlZTLCBkZXNjcmliZVdpdGhGbGFnc30gZnJvbSAnLi4vamFzbWluZV91dGlsJztcbmltcG9ydCB7ZXhwZWN0QXJyYXlzQ2xvc2UsIGV4cGVjdEFycmF5c0VxdWFsfSBmcm9tICcuLi90ZXN0X3V0aWwnO1xuXG5jbGFzcyBNb2NrQ29udGV4dCB7XG4gIGRhdGE6IEltYWdlRGF0YTtcblxuICBnZXRJbWFnZURhdGEoKSB7XG4gICAgcmV0dXJuIHRoaXMuZGF0YTtcbiAgfVxuXG4gIHB1dEltYWdlRGF0YShkYXRhOiBJbWFnZURhdGEsIHg6IG51bWJlciwgeTogbnVtYmVyKSB7XG4gICAgdGhpcy5kYXRhID0gZGF0YTtcbiAgfVxufVxuXG5jbGFzcyBNb2NrQ2FudmFzIHtcbiAgY29udGV4dDogTW9ja0NvbnRleHQ7XG5cbiAgY29uc3RydWN0b3IocHVibGljIHdpZHRoOiBudW1iZXIsIHB1YmxpYyBoZWlnaHQ6IG51bWJlcikge31cblxuICBnZXRDb250ZXh0KHR5cGU6ICcyZCcpOiBNb2NrQ29udGV4dCB7XG4gICAgaWYgKHRoaXMuY29udGV4dCA9PSBudWxsKSB7XG4gICAgICB0aGlzLmNvbnRleHQgPSBuZXcgTW9ja0NvbnRleHQoKTtcbiAgICB9XG4gICAgcmV0dXJuIHRoaXMuY29udGV4dDtcbiAgfVxufVxuXG5kZXNjcmliZVdpdGhGbGFncygnRHJhdyBvbiAyZCBjb250ZXh0JywgQlJPV1NFUl9FTlZTLCAoKSA9PiB7XG4gIGl0KCdkcmF3IGltYWdlIHdpdGggNCBjaGFubmVscyBhbmQgaW50IHZhbHVlcycsIGFzeW5jICgpID0+IHtcbiAgICBjb25zdCBkYXRhID0gWzEsIDIsIDMsIDQsIDUsIDYsIDcsIDgsIDksIDEwLCAxMSwgMTIsIDEzLCAxNCwgMTUsIDE2XTtcbiAgICBjb25zdCBpbWcgPSB0Zi50ZW5zb3IzZChkYXRhLCBbMiwgMiwgNF0sICdpbnQzMicpO1xuICAgIGNvbnN0IGNhbnZhcyA9IG5ldyBNb2NrQ2FudmFzKDIsIDIpO1xuICAgIGNvbnN0IGN0eCA9IGNhbnZhcy5nZXRDb250ZXh0KCcyZCcpO1xuXG4gICAgLy8gdHNsaW50OmRpc2FibGUtbmV4dC1saW5lOm5vLWFueVxuICAgIHRmLmJyb3dzZXIuZHJhdyhpbWcsIGNhbnZhcyBhcyBhbnksIHtjb250ZXh0T3B0aW9uczoge2NvbnRleHRUeXBlOiAnMmQnfX0pO1xuICAgIGV4cGVjdEFycmF5c0VxdWFsKGN0eC5nZXRJbWFnZURhdGEoKS5kYXRhLCBkYXRhKTtcbiAgfSk7XG5cbiAgaXQoJ2RyYXcgaW1hZ2Ugd2l0aCA0IGNoYW5uZWxzIGFuZCBmbG9hdCB2YWx1ZXMnLCBhc3luYyAoKSA9PiB7XG4gICAgY29uc3QgZGF0YSA9XG4gICAgICAgIFsuMSwgLjIsIC4zLCAuNCwgLjUsIC42LCAuNywgLjgsIC45LCAuMSwgLjExLCAuMTIsIC4xMywgLjE0LCAuMTUsIC4xNl07XG4gICAgY29uc3QgaW1nID0gdGYudGVuc29yM2QoZGF0YSwgWzIsIDIsIDRdKTtcbiAgICBjb25zdCBjYW52YXMgPSBuZXcgTW9ja0NhbnZhcygyLCAyKTtcbiAgICBjb25zdCBjdHggPSBjYW52YXMuZ2V0Q29udGV4dCgnMmQnKTtcblxuICAgIC8vIHRzbGludDpkaXNhYmxlLW5leHQtbGluZTpuby1hbnlcbiAgICB0Zi5icm93c2VyLmRyYXcoaW1nLCBjYW52YXMgYXMgYW55LCB7Y29udGV4dE9wdGlvbnM6IHtjb250ZXh0VHlwZTogJzJkJ319KTtcbiAgICBjb25zdCBhY3R1YWxEYXRhID0gY3R4LmdldEltYWdlRGF0YSgpLmRhdGE7XG4gICAgY29uc3QgZXhwZWN0ZWREYXRhID0gZGF0YS5tYXAoZSA9PiBNYXRoLnJvdW5kKGUgKiAyNTUpKTtcbiAgICBleHBlY3RBcnJheXNDbG9zZShhY3R1YWxEYXRhLCBleHBlY3RlZERhdGEsIDEpO1xuICB9KTtcblxuICBpdCgnZHJhdyAyRCBpbWFnZSBpbiBncmF5c2NhbGUnLCBhc3luYyAoKSA9PiB7XG4gICAgY29uc3QgZGF0YSA9IFsxLCAyLCAzLCA0XTtcbiAgICBjb25zdCBpbWcgPSB0Zi50ZW5zb3IyZChkYXRhLCBbMiwgMl0sICdpbnQzMicpO1xuICAgIGNvbnN0IGNhbnZhcyA9IG5ldyBNb2NrQ2FudmFzKDIsIDIpO1xuICAgIGNvbnN0IGN0eCA9IGNhbnZhcy5nZXRDb250ZXh0KCcyZCcpO1xuXG4gICAgLy8gdHNsaW50OmRpc2FibGUtbmV4dC1saW5lOm5vLWFueVxuICAgIHRmLmJyb3dzZXIuZHJhdyhpbWcsIGNhbnZhcyBhcyBhbnksIHtjb250ZXh0T3B0aW9uczoge2NvbnRleHRUeXBlOiAnMmQnfX0pO1xuICAgIGNvbnN0IGFjdHVhbERhdGEgPSBjdHguZ2V0SW1hZ2VEYXRhKCkuZGF0YTtcbiAgICBjb25zdCBleHBlY3RlZERhdGEgPVxuICAgICAgICBbMSwgMSwgMSwgMjU1LCAyLCAyLCAyLCAyNTUsIDMsIDMsIDMsIDI1NSwgNCwgNCwgNCwgMjU1XTtcbiAgICBleHBlY3RBcnJheXNFcXVhbChhY3R1YWxEYXRhLCBleHBlY3RlZERhdGEpO1xuICB9KTtcblxuICBpdCgnZHJhdyBpbWFnZSB3aXRoIGFscGhhPTAuNScsIGFzeW5jICgpID0+IHtcbiAgICBjb25zdCBkYXRhID0gWzEsIDIsIDMsIDRdO1xuICAgIGNvbnN0IGltZyA9IHRmLnRlbnNvcjNkKGRhdGEsIFsyLCAyLCAxXSwgJ2ludDMyJyk7XG4gICAgY29uc3QgY2FudmFzID0gbmV3IE1vY2tDYW52YXMoMiwgMik7XG4gICAgY29uc3QgY3R4ID0gY2FudmFzLmdldENvbnRleHQoJzJkJyk7XG5cbiAgICBjb25zdCBkcmF3T3B0aW9ucyA9IHtcbiAgICAgIGNvbnRleHRPcHRpb25zOiB7Y29udGV4dFR5cGU6ICcyZCd9LFxuICAgICAgaW1hZ2VPcHRpb25zOiB7YWxwaGE6IDAuNX1cbiAgICB9O1xuICAgIC8vIHRzbGludDpkaXNhYmxlLW5leHQtbGluZTpuby1hbnlcbiAgICB0Zi5icm93c2VyLmRyYXcoaW1nLCBjYW52YXMgYXMgYW55LCBkcmF3T3B0aW9ucyk7XG4gICAgY29uc3QgYWN0dWFsRGF0YSA9IGN0eC5nZXRJbWFnZURhdGEoKS5kYXRhO1xuICAgIGNvbnN0IGV4cGVjdGVkRGF0YSA9XG4gICAgICAgIFsxLCAxLCAxLCAxMjgsIDIsIDIsIDIsIDEyOCwgMywgMywgMywgMTI4LCA0LCA0LCA0LCAxMjhdO1xuICAgIGV4cGVjdEFycmF5c0VxdWFsKGFjdHVhbERhdGEsIGV4cGVjdGVkRGF0YSk7XG4gIH0pO1xufSk7XG4iXX0=