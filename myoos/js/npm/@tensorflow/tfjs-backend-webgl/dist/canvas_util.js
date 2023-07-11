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
import { env } from '@tensorflow/tfjs-core';
const contexts = {};
const WEBGL_ATTRIBUTES = {
    alpha: false,
    antialias: false,
    premultipliedAlpha: false,
    preserveDrawingBuffer: false,
    depth: false,
    stencil: false,
    failIfMajorPerformanceCaveat: true
};
export function clearWebGLContext(webGLVersion) {
    delete contexts[webGLVersion];
}
export function setWebGLContext(webGLVersion, gl) {
    contexts[webGLVersion] = gl;
}
export function getWebGLContext(webGLVersion, customCanvas) {
    if (!(webGLVersion in contexts) || customCanvas != null) {
        const newCtx = getWebGLRenderingContext(webGLVersion, customCanvas);
        if (newCtx !== null) {
            contexts[webGLVersion] = newCtx;
        }
        else {
            console.log('Could not get context for WebGL version', webGLVersion);
            return null;
        }
    }
    const gl = contexts[webGLVersion];
    if (gl == null || gl.isContextLost()) {
        delete contexts[webGLVersion];
        return getWebGLContext(webGLVersion);
    }
    gl.disable(gl.DEPTH_TEST);
    gl.disable(gl.STENCIL_TEST);
    gl.disable(gl.BLEND);
    gl.disable(gl.DITHER);
    gl.disable(gl.POLYGON_OFFSET_FILL);
    gl.disable(gl.SAMPLE_COVERAGE);
    gl.enable(gl.SCISSOR_TEST);
    gl.enable(gl.CULL_FACE);
    gl.cullFace(gl.BACK);
    return contexts[webGLVersion];
}
function createCanvas(webGLVersion) {
    // Use canvas element for Safari, since its offscreen canvas does not support
    // fencing.
    if (!env().getBool('IS_SAFARI') && typeof OffscreenCanvas !== 'undefined' &&
        webGLVersion === 2) {
        return new OffscreenCanvas(300, 150);
    }
    else if (typeof document !== 'undefined') {
        return document.createElement('canvas');
    }
    else {
        throw new Error('Cannot create a canvas in this context');
    }
}
function getWebGLRenderingContext(webGLVersion, customCanvas) {
    if (webGLVersion !== 1 && webGLVersion !== 2) {
        throw new Error('Cannot get WebGL rendering context, WebGL is disabled.');
    }
    const canvas = customCanvas == null ? createCanvas(webGLVersion) : customCanvas;
    canvas.addEventListener('webglcontextlost', (ev) => {
        ev.preventDefault();
        delete contexts[webGLVersion];
    }, false);
    if (env().getBool('SOFTWARE_WEBGL_ENABLED')) {
        WEBGL_ATTRIBUTES.failIfMajorPerformanceCaveat = false;
    }
    if (webGLVersion === 1) {
        return (canvas.getContext('webgl', WEBGL_ATTRIBUTES) ||
            canvas
                .getContext('experimental-webgl', WEBGL_ATTRIBUTES));
    }
    return canvas.getContext('webgl2', WEBGL_ATTRIBUTES);
}
//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiY2FudmFzX3V0aWwuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlcyI6WyIuLi8uLi8uLi8uLi8uLi90ZmpzLWJhY2tlbmQtd2ViZ2wvc3JjL2NhbnZhc191dGlsLnRzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBOzs7Ozs7Ozs7Ozs7Ozs7R0FlRztBQUVILE9BQU8sRUFBQyxHQUFHLEVBQUMsTUFBTSx1QkFBdUIsQ0FBQztBQUUxQyxNQUFNLFFBQVEsR0FBMkMsRUFBRSxDQUFDO0FBRTVELE1BQU0sZ0JBQWdCLEdBQTJCO0lBQy9DLEtBQUssRUFBRSxLQUFLO0lBQ1osU0FBUyxFQUFFLEtBQUs7SUFDaEIsa0JBQWtCLEVBQUUsS0FBSztJQUN6QixxQkFBcUIsRUFBRSxLQUFLO0lBQzVCLEtBQUssRUFBRSxLQUFLO0lBQ1osT0FBTyxFQUFFLEtBQUs7SUFDZCw0QkFBNEIsRUFBRSxJQUFJO0NBQ25DLENBQUM7QUFFRixNQUFNLFVBQVUsaUJBQWlCLENBQUMsWUFBb0I7SUFDcEQsT0FBTyxRQUFRLENBQUMsWUFBWSxDQUFDLENBQUM7QUFDaEMsQ0FBQztBQUVELE1BQU0sVUFBVSxlQUFlLENBQzNCLFlBQW9CLEVBQUUsRUFBeUI7SUFDakQsUUFBUSxDQUFDLFlBQVksQ0FBQyxHQUFHLEVBQUUsQ0FBQztBQUM5QixDQUFDO0FBRUQsTUFBTSxVQUFVLGVBQWUsQ0FDM0IsWUFBb0IsRUFDcEIsWUFBZ0Q7SUFDbEQsSUFBSSxDQUFDLENBQUMsWUFBWSxJQUFJLFFBQVEsQ0FBQyxJQUFJLFlBQVksSUFBSSxJQUFJLEVBQUU7UUFDdkQsTUFBTSxNQUFNLEdBQUcsd0JBQXdCLENBQUMsWUFBWSxFQUFFLFlBQVksQ0FBQyxDQUFDO1FBQ3BFLElBQUksTUFBTSxLQUFLLElBQUksRUFBRTtZQUNuQixRQUFRLENBQUMsWUFBWSxDQUFDLEdBQUcsTUFBTSxDQUFDO1NBQ2pDO2FBQU07WUFDTCxPQUFPLENBQUMsR0FBRyxDQUFDLHlDQUF5QyxFQUFFLFlBQVksQ0FBQyxD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b3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEBsaWNlbnNlXG4gKiBDb3B5cmlnaHQgMjAxOCBHb29nbGUgTExDLiBBbGwgUmlnaHRzIFJlc2VydmVkLlxuICogTGljZW5zZWQgdW5kZXIgdGhlIEFwYWNoZSBMaWNlbnNlLCBWZXJzaW9uIDIuMCAodGhlIFwiTGljZW5zZVwiKTtcbiAqIHlvdSBtYXkgbm90IHVzZSB0aGlzIGZpbGUgZXhjZXB0IGluIGNvbXBsaWFuY2Ugd2l0aCB0aGUgTGljZW5zZS5cbiAqIFlvdSBtYXkgb2J0YWluIGEgY29weSBvZiB0aGUgTGljZW5zZSBhdFxuICpcbiAqIGh0dHA6Ly93d3cuYXBhY2hlLm9yZy9saWNlbnNlcy9MSUNFTlNFLTIuMFxuICpcbiAqIFVubGVzcyByZXF1aXJlZCBieSBhcHBsaWNhYmxlIGxhdyBvciBhZ3JlZWQgdG8gaW4gd3JpdGluZywgc29mdHdhcmVcbiAqIGRpc3RyaWJ1dGVkIHVuZGVyIHRoZSBMaWNlbnNlIGlzIGRpc3RyaWJ1dGVkIG9uIGFuIFwiQVMgSVNcIiBCQVNJUyxcbiAqIFdJVEhPVVQgV0FSUkFOVElFUyBPUiBDT05ESVRJT05TIE9GIEFOWSBLSU5ELCBlaXRoZXIgZXhwcmVzcyBvciBpbXBsaWVkLlxuICogU2VlIHRoZSBMaWNlbnNlIGZvciB0aGUgc3BlY2lmaWMgbGFuZ3VhZ2UgZ292ZXJuaW5nIHBlcm1pc3Npb25zIGFuZFxuICogbGltaXRhdGlvbnMgdW5kZXIgdGhlIExpY2Vuc2UuXG4gKiA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PVxuICovXG5cbmltcG9ydCB7ZW52fSBmcm9tICdAdGVuc29yZmxvdy90ZmpzLWNvcmUnO1xuXG5jb25zdCBjb250ZXh0czoge1trZXk6IHN0cmluZ106IFdlYkdMUmVuZGVyaW5nQ29udGV4dH0gPSB7fTtcblxuY29uc3QgV0VCR0xfQVRUUklCVVRFUzogV2ViR0xDb250ZXh0QXR0cmlidXRlcyA9IHtcbiAgYWxwaGE6IGZhbHNlLFxuICBhbnRpYWxpYXM6IGZhbHNlLFxuICBwcmVtdWx0aXBsaWVkQWxwaGE6IGZhbHNlLFxuICBwcmVzZXJ2ZURyYXdpbmdCdWZmZXI6IGZhbHNlLFxuICBkZXB0aDogZmFsc2UsXG4gIHN0ZW5jaWw6IGZhbHNlLFxuICBmYWlsSWZNYWpvclBlcmZvcm1hbmNlQ2F2ZWF0OiB0cnVlXG59O1xuXG5leHBvcnQgZnVuY3Rpb24gY2xlYXJXZWJHTENvbnRleHQod2ViR0xWZXJzaW9uOiBudW1iZXIpIHtcbiAgZGVsZXRlIGNvbnRleHRzW3dlYkdMVmVyc2lvbl07XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBzZXRXZWJHTENvbnRleHQoXG4gICAgd2ViR0xWZXJzaW9uOiBudW1iZXIsIGdsOiBXZWJHTFJlbmRlcmluZ0NvbnRleHQpIHtcbiAgY29udGV4dHNbd2ViR0xWZXJzaW9uXSA9IGdsO1xufVxuXG5leHBvcnQgZnVuY3Rpb24gZ2V0V2ViR0xDb250ZXh0KFxuICAgIHdlYkdMVmVyc2lvbjogbnVtYmVyLFxuICAgIGN1c3RvbUNhbnZhcz86IEhUTUxDYW52YXNFbGVtZW50fE9mZnNjcmVlbkNhbnZhcyk6IFdlYkdMUmVuZGVyaW5nQ29udGV4dCB7XG4gIGlmICghKHdlYkdMVmVyc2lvbiBpbiBjb250ZXh0cykgfHwgY3VzdG9tQ2FudmFzICE9IG51bGwpIHtcbiAgICBjb25zdCBuZXdDdHggPSBnZXRXZWJHTFJlbmRlcmluZ0NvbnRleHQod2ViR0xWZXJzaW9uLCBjdXN0b21DYW52YXMpO1xuICAgIGlmIChuZXdDdHggIT09IG51bGwpIHtcbiAgICAgIGNvbnRleHRzW3dlYkdMVmVyc2lvbl0gPSBuZXdDdHg7XG4gICAgfSBlbHNlIHtcbiAgICAgIGNvbnNvbGUubG9nKCdDb3VsZCBub3QgZ2V0IGNvbnRleHQgZm9yIFdlYkdMIHZlcnNpb24nLCB3ZWJHTFZlcnNpb24pO1xuICAgICAgcmV0dXJuIG51bGw7XG4gICAgfVxuICB9XG4gIGNvbnN0IGdsID0gY29udGV4dHNbd2ViR0xWZXJzaW9uXTtcbiAgaWYgKGdsID09IG51bGwgfHwgZ2wuaXNDb250ZXh0TG9zdCgpKSB7XG4gICAgZGVsZXRlIGNvbnRleHRzW3dlYkdMVmVyc2lvbl07XG4gICAgcmV0dXJuIGdldFdlYkdMQ29udGV4dCh3ZWJHTFZlcnNpb24pO1xuICB9XG5cbiAgZ2wuZGlzYWJsZShnbC5ERVBUSF9URVNUKTtcbiAgZ2wuZGlzYWJsZShnbC5TVEVOQ0lMX1RFU1QpO1xuICBnbC5kaXNhYmxlKGdsLkJMRU5EKTtcbiAgZ2wuZGlzYWJsZShnbC5ESVRIRVIpO1xuICBnbC5kaXNhYmxlKGdsLlBPTFlHT05fT0ZGU0VUX0ZJTEwpO1xuICBnbC5kaXNhYmxlKGdsLlNBTVBMRV9DT1ZFUkFHRSk7XG4gIGdsLmVuYWJsZShnbC5TQ0lTU09SX1RFU1QpO1xuICBnbC5lbmFibGUoZ2wuQ1VMTF9GQUNFKTtcbiAgZ2wuY3VsbEZhY2UoZ2wuQkFDSyk7XG5cbiAgcmV0dXJuIGNvbnRleHRzW3dlYkdMVmVyc2lvbl07XG59XG5cbmZ1bmN0aW9uIGNyZWF0ZUNhbnZhcyh3ZWJHTFZlcnNpb246IG51bWJlcikge1xuICAvLyBVc2UgY2FudmFzIGVsZW1lbnQgZm9yIFNhZmFyaSwgc2luY2UgaXRzIG9mZnNjcmVlbiBjYW52YXMgZG9lcyBub3Qgc3VwcG9ydFxuICAvLyBmZW5jaW5nLlxuICBpZiAoIWVudigpLmdldEJvb2woJ0lTX1NBRkFSSScpICYmIHR5cGVvZiBPZmZzY3JlZW5DYW52YXMgIT09ICd1bmRlZmluZWQnICYmXG4gICAgICB3ZWJHTFZlcnNpb24gPT09IDIpIHtcbiAgICByZXR1cm4gbmV3IE9mZnNjcmVlbkNhbnZhcygzMDAsIDE1MCk7XG4gIH0gZWxzZSBpZiAodHlwZW9mIGRvY3VtZW50ICE9PSAndW5kZWZpbmVkJykge1xuICAgIHJldHVybiBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdjYW52YXMnKTtcbiAgfSBlbHNlIHtcbiAgICB0aHJvdyBuZXcgRXJyb3IoJ0Nhbm5vdCBjcmVhdGUgYSBjYW52YXMgaW4gdGhpcyBjb250ZXh0Jyk7XG4gIH1cbn1cblxuZnVuY3Rpb24gZ2V0V2ViR0xSZW5kZXJpbmdDb250ZXh0KFxuICAgIHdlYkdMVmVyc2lvbjogbnVtYmVyLFxuICAgIGN1c3RvbUNhbnZhcz86IEhUTUxDYW52YXNFbGVtZW50fE9mZnNjcmVlbkNhbnZhcyk6IFdlYkdMUmVuZGVyaW5nQ29udGV4dCB7XG4gIGlmICh3ZWJHTFZlcnNpb24gIT09IDEgJiYgd2ViR0xWZXJzaW9uICE9PSAyKSB7XG4gICAgdGhyb3cgbmV3IEVycm9yKCdDYW5ub3QgZ2V0IFdlYkdMIHJlbmRlcmluZyBjb250ZXh0LCBXZWJHTCBpcyBkaXNhYmxlZC4nKTtcbiAgfVxuICBjb25zdCBjYW52YXMgPVxuICAgICAgY3VzdG9tQ2FudmFzID09IG51bGwgPyBjcmVhdGVDYW52YXMod2ViR0xWZXJzaW9uKSA6IGN1c3RvbUNhbnZhcztcblxuICBjYW52YXMuYWRkRXZlbnRMaXN0ZW5lcignd2ViZ2xjb250ZXh0bG9zdCcsIChldjogRXZlbnQpID0+IHtcbiAgICBldi5wcmV2ZW50RGVmYXVsdCgpO1xuICAgIGRlbGV0ZSBjb250ZXh0c1t3ZWJHTFZlcnNpb25dO1xuICB9LCBmYWxzZSk7XG5cbiAgaWYgKGVudigpLmdldEJvb2woJ1NPRlRXQVJFX1dFQkdMX0VOQUJMRUQnKSkge1xuICAgIFdFQkdMX0FUVFJJQlVURVMuZmFpbElmTWFqb3JQZXJmb3JtYW5jZUNhdmVhdCA9IGZhbHNlO1xuICB9XG5cbiAgaWYgKHdlYkdMVmVyc2lvbiA9PT0gMSkge1xuICAgIHJldHVybiAoXG4gICAgICAgIGNhbnZhcy5nZXRDb250ZXh0KCd3ZWJnbCcsIFdFQkdMX0FUVFJJQlVURVMpIHx8XG4gICAgICAgIChjYW52YXMgYXMgSFRNTENhbnZhc0VsZW1lbnQpXG4gICAgICAgICAgICAuZ2V0Q29udGV4dCgnZXhwZXJpbWVudGFsLXdlYmdsJywgV0VCR0xfQVRUUklCVVRFUykpO1xuICB9XG4gIHJldHVybiBjYW52YXMuZ2V0Q29udGV4dCgnd2ViZ2wyJywgV0VCR0xfQVRUUklCVVRFUykgYXMgV2ViR0xSZW5kZXJpbmdDb250ZXh0O1xufVxuIl19