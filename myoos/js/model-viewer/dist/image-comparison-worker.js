(function () {
    'use strict';

    function colorDelta(img1, img2, k, m, yOnly = false) {
        var a1 = img1[k + 3] / 255, a2 = img2[m + 3] / 255, r1 = blend(img1[k + 0], a1), g1 = blend(img1[k + 1], a1), b1 = blend(img1[k + 2], a1), r2 = blend(img2[m + 0], a2), g2 = blend(img2[m + 1], a2), b2 = blend(img2[m + 2], a2), y = rgb2y(r1, g1, b1) - rgb2y(r2, g2, b2);
        if (yOnly)
            return y;
        var i = rgb2i(r1, g1, b1) - rgb2i(r2, g2, b2), q = rgb2q(r1, g1, b1) - rgb2q(r2, g2, b2);
        return 0.5053 * y * y + 0.299 * i * i + 0.1957 * q * q;
    }
    function rgb2y(r, g, b) {
        return r * 0.29889531 + g * 0.58662247 + b * 0.11448223;
    }
    function rgb2i(r, g, b) {
        return r * 0.59597799 - g * 0.27417610 - b * 0.32180189;
    }
    function rgb2q(r, g, b) {
        return r * 0.21147017 - g * 0.52261711 + b * 0.31114694;
    }
    function blend(c, a) {
        return 255 + (c - 255) * a;
    }

    const COMPONENTS_PER_PIXEL = 4;
    const MAX_COLOR_DISTANCE = 35215;
    class ImageComparator {
        constructor(candidateImage, goldenImage, dimensions) {
            this.candidateImage = candidateImage;
            this.goldenImage = goldenImage;
            this.dimensions = dimensions;
            const { width, height } = dimensions;
            this.imagePixels = width * height;
        }
        drawPixel(image, position, r, g, b, a = 255) {
            image[position + 0] = r;
            image[position + 1] = g;
            image[position + 2] = b;
            image[position + 3] = a;
        }
        analyze(threshold, options = {
            generateVisuals: true
        }) {
            const { candidateImage, goldenImage } = this;
            const { width, height } = this.dimensions;
            const { generateVisuals } = options;
            const blackWhiteImage = generateVisuals ?
                new Uint8ClampedArray(this.imagePixels * COMPONENTS_PER_PIXEL) :
                null;
            const deltaImage = generateVisuals ?
                new Uint8ClampedArray(this.imagePixels * COMPONENTS_PER_PIXEL) :
                null;
            const thresholdSquared = threshold * threshold;
            let matched = 0;
            let sum = 0;
            let mismatchingSum = 0;
            let maximumDeltaIntensity = 0;
            if (candidateImage.length != goldenImage.length) {
                throw new Error(`Image sizes do not match (candidate: ${candidateImage.length}, golden: ${goldenImage.length})`);
            }
            for (let y = 0; y < height; ++y) {
                for (let x = 0; x < width; ++x) {
                    const index = y * width + x;
                    const position = index * COMPONENTS_PER_PIXEL;
                    const delta = colorDelta(candidateImage, goldenImage, position, position);
                    const exactlyMatched = (delta <= thresholdSquared ? 1 : 0) * 255;
                    if (exactlyMatched) {
                        matched++;
                    }
                    else {
                        mismatchingSum += delta;
                    }
                    const thresholdDelta = Math.max(0, delta - thresholdSquared);
                    sum += thresholdDelta;
                    if (generateVisuals) {
                        const deltaIntensity = Math.round(255 * thresholdDelta / MAX_COLOR_DISTANCE);
                        maximumDeltaIntensity =
                            Math.max(deltaIntensity, maximumDeltaIntensity);
                        this.drawPixel(blackWhiteImage, position, exactlyMatched, exactlyMatched, exactlyMatched);
                        this.drawPixel(deltaImage, position, 255, 255 - deltaIntensity, 255 - deltaIntensity);
                    }
                }
            }
            if (generateVisuals) {
                for (let y = 0; y < height; ++y) {
                    for (let x = 0; x < width; ++x) {
                        const index = y * width + x;
                        const position = index * COMPONENTS_PER_PIXEL;
                        const absoluteDeltaIntensity = 255 - deltaImage[position + 1];
                        const relativeDeltaIntensity = Math.round(255 - 255 * (absoluteDeltaIntensity / maximumDeltaIntensity));
                        this.drawPixel(deltaImage, position, 255, relativeDeltaIntensity, relativeDeltaIntensity);
                    }
                }
            }
            const mismatchingPixels = this.imagePixels - matched;
            const mismatchingAverageDistanceRatio = mismatchingPixels > 0 ?
                mismatchingSum / (this.imagePixels - matched) / MAX_COLOR_DISTANCE :
                0;
            const averageDistanceRatio = sum / this.imagePixels / MAX_COLOR_DISTANCE;
            return {
                analysis: {
                    matchingRatio: matched / this.imagePixels,
                    averageDistanceRatio,
                    mismatchingAverageDistanceRatio,
                },
                imageBuffers: {
                    delta: deltaImage ? deltaImage.buffer : null,
                    blackWhite: blackWhiteImage ? blackWhiteImage.buffer :
                        null
                }
            };
        }
    }

    class ImageComparisonWorker {
        constructor() {
            this.analyzer = null;
            this.candidateCanvas = null;
            this.candidateContext = null;
            this.goldenCanvas = null;
            this.goldenContext = null;
            this.blackWhiteCanvas = null;
            this.blackWhiteContext = null;
            this.deltaCanvas = null;
            this.deltaContext = null;
            self.onmessage = (event) => this.onGlobalMessage(event);
        }
        onMessage(event, port) {
            const data = event.data;
            switch (data.type) {
                case 'canvases-ready': {
                    const { candidateCanvas, goldenCanvas, blackWhiteCanvas, deltaCanvas } = data;
                    this.candidateCanvas = candidateCanvas;
                    this.candidateContext = candidateCanvas.getContext('2d');
                    this.goldenCanvas = goldenCanvas;
                    this.goldenContext = goldenCanvas.getContext('2d');
                    this.blackWhiteCanvas = blackWhiteCanvas;
                    this.blackWhiteContext = blackWhiteCanvas.getContext('2d');
                    this.deltaCanvas = deltaCanvas;
                    this.deltaContext = deltaCanvas.getContext('2d');
                    break;
                }
                case 'images-assigned': {
                    const { candidateImageBuffer, goldenImageBuffer, dimensions } = data;
                    if (this.candidateCanvas == null || this.goldenCanvas == null ||
                        this.blackWhiteCanvas == null || this.deltaCanvas == null) {
                        console.warn('Images assigned before canvases are available!');
                    }
                    this.candidateCanvas.width = this.goldenCanvas.width =
                        this.blackWhiteCanvas.width = this.deltaCanvas.width =
                            dimensions.width;
                    this.candidateCanvas.height = this.goldenCanvas.height =
                        this.blackWhiteCanvas.height = this.deltaCanvas.height =
                            dimensions.height;
                    const candidateArray = new Uint8ClampedArray(candidateImageBuffer);
                    const goldenArray = new Uint8ClampedArray(goldenImageBuffer);
                    const { width, height } = dimensions;
                    this.analyzer =
                        new ImageComparator(candidateArray, goldenArray, dimensions);
                    this.candidateContext.putImageData(new ImageData(candidateArray, width, height), 0, 0);
                    this.goldenContext.putImageData(new ImageData(goldenArray, width, height), 0, 0);
                    break;
                }
                case 'threshold-changed': {
                    const { threshold } = data;
                    const { analyzer } = this;
                    if (analyzer == null) {
                        console.warn(`Analyzer not created!`);
                        return;
                    }
                    const { width, height } = this.analyzer.dimensions;
                    const result = analyzer.analyze(threshold, { generateVisuals: true });
                    this.blackWhiteContext.putImageData(new ImageData(new Uint8ClampedArray(result.imageBuffers.blackWhite), width, height), 0, 0);
                    this.deltaContext.putImageData(new ImageData(new Uint8ClampedArray(result.imageBuffers.delta), width, height), 0, 0);
                    port.postMessage({ type: 'analysis-completed', result });
                    break;
                }
                default:
                    break;
            }
        }
        onGlobalMessage(event) {
            event.ports.forEach(port => port.onmessage = (event) => this.onMessage(event, port));
        }
    }
    self.imageComparisonWorker = new ImageComparisonWorker();

}());
//# sourceMappingURL=image-comparison-worker.js.map
