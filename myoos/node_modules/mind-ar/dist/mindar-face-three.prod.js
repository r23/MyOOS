import { Scene as u, WebGLRenderer as y, sRGBEncoding as g, PerspectiveCamera as w, Mesh as M, MeshStandardMaterial as x, Group as m, BufferGeometry as A, BufferAttribute as b } from "three";
import { CSS3DRenderer as R } from "three/addons/renderers/CSS3DRenderer.js";
import { C as S } from "./controller-0f2b8ff1.js";
import { U as I } from "./ui-85e81035.js";
const E = { BufferGeometry: A, BufferAttribute: b };
class C {
  constructor({
    container: a,
    uiLoading: t = "yes",
    uiScanning: r = "yes",
    uiError: n = "yes",
    filterMinCF: e = null,
    filterBeta: h = null,
    userDeviceId: s = null,
    environmentDeviceId: c = null,
    disableFaceMirror: l = !1
  }) {
    this.container = a, this.ui = new I({ uiLoading: t, uiScanning: r, uiError: n }), this.controller = new S({
      filterMinCF: e,
      filterBeta: h
    }), this.disableFaceMirror = l, this.scene = new u(), this.cssScene = new u(), this.renderer = new y({ antialias: !0, alpha: !0 }), this.cssRenderer = new R({ antialias: !0 }), this.renderer.outputEncoding = g, this.renderer.setPixelRatio(window.devicePixelRatio), this.camera = new w(), this.userDeviceId = s, this.environmentDeviceId = c, this.anchors = [], this.faceMeshes = [], this.container.appendChild(this.renderer.domElement), this.container.appendChild(this.cssRenderer.domElement), this.shouldFaceUser = !0, window.addEventListener("resize", this._resize.bind(this));
  }
  async start() {
    this.ui.showLoading(), await this._startVideo(), await this._startAR(), this.ui.hideLoading();
  }
  stop() {
    this.video.srcObject.getTracks().forEach(function(t) {
      t.stop();
    }), this.video.remove(), this.controller.stopProcessVideo();
  }
  switchCamera() {
    this.shouldFaceUser = !this.shouldFaceUser, this.stop(), this.start();
  }
  addFaceMesh() {
    const a = this.controller.createThreeFaceGeometry(E), t = new M(a, new x({ color: 16777215 }));
    return t.visible = !1, t.matrixAutoUpdate = !1, this.faceMeshes.push(t), t;
  }
  addAnchor(a) {
    const t = new m();
    t.matrixAutoUpdate = !1;
    const r = { group: t, landmarkIndex: a, css: !1 };
    return this.anchors.push(r), this.scene.add(t), r;
  }
  addCSSAnchor(a) {
    const t = new m();
    t.matrixAutoUpdate = !1;
    const r = { group: t, landmarkIndex: a, css: !0 };
    return this.anchors.push(r), this.cssScene.add(t), r;
  }
  _startVideo() {
    return new Promise((a, t) => {
      if (this.video = document.createElement("video"), this.video.setAttribute("autoplay", ""), this.video.setAttribute("muted", ""), this.video.setAttribute("playsinline", ""), this.video.style.position = "absolute", this.video.style.top = "0px", this.video.style.left = "0px", this.video.style.zIndex = "-2", this.container.appendChild(this.video), !navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        this.ui.showCompatibility(), t();
        return;
      }
      const r = {
        audio: !1,
        video: {}
      };
      this.shouldFaceUser ? this.userDeviceId ? r.video.deviceId = { exact: this.userDeviceId } : r.video.facingMode = "user" : this.environmentDeviceId ? r.video.deviceId = { exact: this.environmentDeviceId } : r.video.facingMode = "environment", navigator.mediaDevices.getUserMedia(r).then((n) => {
        this.video.addEventListener("loadedmetadata", () => {
          this.video.setAttribute("width", this.video.videoWidth), this.video.setAttribute("height", this.video.videoHeight), a();
        }), this.video.srcObject = n;
      }).catch((n) => {
        console.log("getUserMedia error", n), t();
      });
    });
  }
  _startAR() {
    return new Promise(async (a, t) => {
      const r = this.video;
      this.container, this.controller.onUpdate = ({ hasFace: e, estimateResult: h }) => {
        for (let s = 0; s < this.anchors.length; s++)
          this.anchors[s].css ? this.anchors[s].group.children.forEach((c) => {
            c.element.style.visibility = e ? "visible" : "hidden";
          }) : this.anchors[s].group.visible = e;
        for (let s = 0; s < this.faceMeshes.length; s++)
          this.faceMeshes[s].visible = e;
        if (e) {
          const { metricLandmarks: s, faceMatrix: c, faceScale: l } = h;
          for (let o = 0; o < this.anchors.length; o++) {
            const d = this.anchors[o].landmarkIndex, i = this.controller.getLandmarkMatrix(d);
            if (this.anchors[o].css) {
              const v = [
                1e-3 * i[0],
                1e-3 * i[1],
                i[2],
                i[3],
                1e-3 * i[4],
                1e-3 * i[5],
                i[6],
                i[7],
                1e-3 * i[8],
                1e-3 * i[9],
                i[10],
                i[11],
                1e-3 * i[12],
                1e-3 * i[13],
                i[14],
                i[15]
              ];
              this.anchors[o].group.matrix.set(...v);
            } else
              this.anchors[o].group.matrix.set(...i);
          }
          for (let o = 0; o < this.faceMeshes.length; o++)
            this.faceMeshes[o].matrix.set(...c);
        }
      }, this._resize();
      const n = this.shouldFaceUser && !this.disableFaceMirror;
      await this.controller.setup(n), await this.controller.dummyRun(r), this._resize(), this.controller.processVideo(r), a();
    });
  }
  _resize() {
    const { renderer: a, cssRenderer: t, camera: r, container: n, video: e } = this;
    if (!e)
      return;
    {
      this.video.setAttribute("width", this.video.videoWidth), this.video.setAttribute("height", this.video.videoHeight), this.controller.onInputResized(e);
      const { fov: i, aspect: f, near: v, far: p } = this.controller.getCameraParams();
      this.camera.fov = i, this.camera.aspect = f, this.camera.near = v, this.camera.far = p, this.camera.updateProjectionMatrix(), this.renderer.setSize(this.video.videoWidth, this.video.videoHeight), this.cssRenderer.setSize(this.video.videoWidth, this.video.videoHeight);
    }
    let h, s;
    const c = e.videoWidth / e.videoHeight, l = n.clientWidth / n.clientHeight;
    c > l ? (s = n.clientHeight, h = s * c) : (h = n.clientWidth, s = h / c), e.style.top = -(s - n.clientHeight) / 2 + "px", e.style.left = -(h - n.clientWidth) / 2 + "px", e.style.width = h + "px", e.style.height = s + "px", this.shouldFaceUser && !this.disableFaceMirror ? e.style.transform = "scaleX(-1)" : e.style.transform = "scaleX(1)";
    const o = a.domElement, d = t.domElement;
    o.style.position = "absolute", o.style.top = e.style.top, o.style.left = e.style.left, o.style.width = e.style.width, o.style.height = e.style.height, d.style.position = "absolute", d.style.top = e.style.top, d.style.left = e.style.left, d.style.transformOrigin = "top left", d.style.transform = "scale(" + h / parseFloat(d.style.width) + "," + s / parseFloat(d.style.height) + ")";
  }
}
window.MINDAR || (window.MINDAR = {});
window.MINDAR.FACE || (window.MINDAR.FACE = {});
window.MINDAR.FACE.MindARThree = C;
export {
  C as MindARThree
};
