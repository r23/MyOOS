(self.webpackChunkmetaverse_experience=self.webpackChunkmetaverse_experience||[]).push([[378],{4476:(t,e,i)=>{"use strict";i.r(e);var o=i(9694),s=i.n(o);AFRAME.registerComponent("nipple-controls",{schema:{enabled:{default:!0},mode:{default:"dynamic",oneOf:["static","semi","dynamic"]},rotationSensitivity:{default:1},moveJoystickEnabled:{default:!0},lookJoystickEnabled:{default:!0},sideMargin:{default:"30px"},bottomMargin:{default:"70px"},moveJoystickPosition:{default:"left",oneOf:["left","right"]},lookJoystickPosition:{default:"right",oneOf:["left","right"]}},init(){this.dVelocity=new THREE.Vector3,this.lookVector=new THREE.Vector2;const t=this.el.querySelector("[look-controls]").components["look-controls"];this.pitchObject=t.pitchObject,this.yawObject=t.yawObject,this.rigRotation=this.el.object3D.rotation,this.moveData=void 0,this.lookData=void 0,this.moving=!1,this.rotating=!1},update(t){this.data.moveJoystickPosition===t.moveJoystickPosition&&this.data.sideMargin===t.sideMargin&&this.data.bottomMargin===t.bottomMargin&&this.data.mode===t.mode||this.removeMoveJoystick(),this.data.lookJoystickPosition===t.lookJoystickPosition&&this.data.sideMargin===t.sideMargin&&this.data.bottomMargin===t.bottomMargin&&this.data.mode===t.mode||this.removeLookJoystick(),this.data.enabled&&this.data.moveJoystickEnabled?this.createMoveJoystick():this.removeMoveJoystick(),this.data.enabled&&this.data.lookJoystickEnabled?this.createLookJoystick():this.removeLookJoystick()},pause(){this.moving=!1,this.rotating=!1},remove(){this.removeMoveJoystick(),this.removeLookJoystick()},isVelocityActive(){return this.data.enabled&&this.moving},getVelocityDelta(){if(this.dVelocity.set(0,0,0),this.isVelocityActive()){const t=this.moveData.force<1?this.moveData.force:1,e=this.moveData.angle.radian,i=Math.cos(e)*t,o=-Math.sin(e)*t;this.dVelocity.set(i,0,o)}return this.dVelocity},isRotationActive(){return this.data.enabled&&this.rotating},updateRotation(t){if(!this.isRotationActive())return;const e=this.lookData.force<1?this.lookData.force:1,i=this.lookData.angle.radian,o=this.lookVector;o.x=Math.cos(i)*e,o.y=Math.sin(i)*e,o.multiplyScalar(this.data.rotationSensitivity*t/1e3),this.yawObject.rotation.y-=o.x;let s=this.pitchObject.rotation.x+o.y;s=Math.max(-Math.PI/2,Math.min(Math.PI/2,s)),this.pitchObject.rotation.x=s},tick:function(t,e){this.updateRotation(e)},initLeftZone(){const t=document.createElement("div");t.setAttribute("id","joystickLeftZone"),t.setAttribute("style",`position:absolute;${this.data.moveJoystickPosition}:${this.data.sideMargin};bottom:${this.data.bottomMargin};z-index:1`),document.body.appendChild(t),this.leftZone=t},initRightZone(){const t=document.createElement("div");t.setAttribute("id","joystickRightZone"),t.setAttribute("style",`position:absolute;${this.data.lookJoystickPosition}:${this.data.sideMargin};bottom:${this.data.bottomMargin};z-index:1`),document.body.appendChild(t),this.rightZone=t},createMoveJoystick(){if(this.moveJoystick)return;this.initLeftZone();const t={mode:this.data.mode,zone:this.leftZone,color:"white",fadeTime:0};this.leftZone.style.width="100px","static"===this.data.mode?(this.leftZone.style.height="100px",t.position={left:"50%",bottom:"50%"}):this.leftZone.style.height="400px",this.moveJoystick=s().create(t),this.moveJoystick.on("move",((t,e)=>{this.moveData=e,this.moving=!0})),this.moveJoystick.on("end",((t,e)=>{this.moving=!1}))},createLookJoystick(){if(this.lookJoystick)return;this.initRightZone();const t={mode:this.data.mode,zone:this.rightZone,color:"white",fadeTime:0};this.rightZone.style.width="100px","static"===this.data.mode?(this.rightZone.style.height="100px",t.position={left:"50%",bottom:"50%"}):this.rightZone.style.height="400px",this.lookJoystick=s().create(t),this.lookJoystick.on("move",((t,e)=>{this.lookData=e,this.rotating=!0})),this.lookJoystick.on("end",((t,e)=>{this.rotating=!1}))},removeMoveJoystick(){this.moveJoystick&&(this.moveJoystick.destroy(),this.moveJoystick=void 0),this.moveData=void 0,this.leftZone&&this.leftZone.parentNode&&(this.leftZone.remove(),this.leftZone=void 0)},removeLookJoystick(){this.lookJoystick&&(this.lookJoystick.destroy(),this.lookJoystick=void 0),this.lookData=void 0,this.rightZone&&this.rightZone.parentNode&&(this.rightZone.remove(),this.rightZone=void 0)}})},9048:t=>{t.exports=AFRAME.registerComponent("touch-controls",{schema:{enabled:{default:!0},reverseEnabled:{default:!0}},init:function(){this.dVelocity=new THREE.Vector3,this.bindMethods(),this.direction=0},play:function(){this.addEventListeners()},pause:function(){this.removeEventListeners(),this.dVelocity.set(0,0,0)},remove:function(){this.pause()},addEventListeners:function(){const t=this.el.sceneEl,e=t.canvas;if(!e)return void t.addEventListener("render-target-loaded",this.addEventListeners.bind(this));e.addEventListener("touchstart",this.onTouchStart),e.addEventListener("touchend",this.onTouchEnd);const i=t.getAttribute("vr-mode-ui");i&&i.cardboardModeEnabled&&t.addEventListener("enter-vr",this.onEnterVR)},removeEventListeners:function(){const t=this.el.sceneEl&&this.el.sceneEl.canvas;t&&(t.removeEventListener("touchstart",this.onTouchStart),t.removeEventListener("touchend",this.onTouchEnd),this.el.sceneEl.removeEventListener("enter-vr",this.onEnterVR))},isVelocityActive:function(){return this.data.enabled&&!!this.direction},getVelocityDelta:function(){return this.dVelocity.z=this.direction,this.dVelocity.clone()},bindMethods:function(){this.onTouchStart=this.onTouchStart.bind(this),this.onTouchEnd=this.onTouchEnd.bind(this),this.onEnterVR=this.onEnterVR.bind(this)},onTouchStart:function(t){this.direction=-1,this.data.reverseEnabled&&t.touches&&2===t.touches.length&&(this.direction=1),t.preventDefault()},onTouchEnd:function(t){this.direction=0,t.preventDefault()},onEnterVR:function(){const t=this.el.sceneEl.xrSession;t&&(t.addEventListener("selectstart",this.onTouchStart),t.addEventListener("selectend",this.onTouchEnd))}})},9893:t=>{t.exports=AFRAME.registerComponent("trackpad-controls",{schema:{enabled:{default:!0},enableNegX:{default:!0},enablePosX:{default:!0},enableNegZ:{default:!0},enablePosZ:{default:!0},mode:{default:"touch",oneOf:["swipe","touch","press"]}},init:function(){this.dVelocity=new THREE.Vector3,this.zVel=0,this.xVel=0,this.bindMethods()},play:function(){this.addEventListeners()},pause:function(){this.removeEventListeners(),this.dVelocity.set(0,0,0)},remove:function(){this.pause()},addEventListeners:function(){const t=this.data,e=this.el.sceneEl;switch(e.addEventListener("axismove",this.onAxisMove),t.mode){case"swipe":case"touch":e.addEventListener("trackpadtouchstart",this.onTouchStart),e.addEventListener("trackpadtouchend",this.onTouchEnd);break;case"press":e.addEventListener("trackpaddown",this.onTouchStart),e.addEventListener("trackpadup",this.onTouchEnd)}},removeEventListeners:function(){const t=this.el.sceneEl;t.removeEventListener("axismove",this.onAxisMove),t.removeEventListener("trackpadtouchstart",this.onTouchStart),t.removeEventListener("trackpadtouchend",this.onTouchEnd),t.removeEventListener("trackpaddown",this.onTouchStart),t.removeEventListener("trackpadup",this.onTouchEnd)},isVelocityActive:function(){return this.data.enabled&&this.isMoving},getVelocityDelta:function(){return this.dVelocity.z=this.isMoving?-this.zVel:1,this.dVelocity.x=this.isMoving?this.xVel:1,this.dVelocity.clone()},bindMethods:function(){this.onTouchStart=this.onTouchStart.bind(this),this.onTouchEnd=this.onTouchEnd.bind(this),this.onAxisMove=this.onAxisMove.bind(this)},onTouchStart:function(t){switch(this.data.mode){case"swipe":this.canRecordAxis=!0,this.startingAxisData=[];break;case"touch":case"press":this.isMoving=!0}t.preventDefault()},onTouchEnd:function(t){"swipe"==this.data.mode&&(this.startingAxisData=[]),this.isMoving=!1,t.preventDefault()},onAxisMove:function(t){switch(this.data.mode){case"swipe":return this.handleSwipeAxis(t);case"touch":case"press":return this.handleTouchAxis(t)}},handleSwipeAxis:function(t){const e=this.data,i=t.detail.axis;if(0===this.startingAxisData.length&&this.canRecordAxis&&(this.canRecordAxis=!1,this.startingAxisData[0]=i[0],this.startingAxisData[1]=i[1]),this.startingAxisData.length>0){let t=0,o=0;e.enableNegX&&i[0]<this.startingAxisData[0]&&(t=-1),e.enablePosX&&i[0]>this.startingAxisData[0]&&(t=1),e.enablePosZ&&i[1]>this.startingAxisData[1]&&(o=-1),e.enableNegZ&&i[1]<this.startingAxisData[1]&&(o=1);const s=Math.abs(this.startingAxisData[1]-i[1]);Math.abs(this.startingAxisData[0]-i[0])>s?(this.zVel=0,this.xVel=t,this.isMoving=!0):(this.xVel=0,this.zVel=o,this.isMoving=!0)}},handleTouchAxis:function(t){const e=this.data,i=t.detail.axis;let o=0,s=0;e.enableNegX&&i[0]<0&&(o=-1),e.enablePosX&&i[0]>0&&(o=1),e.enablePosZ&&i[1]>0&&(s=-1),e.enableNegZ&&i[1]<0&&(s=1),Math.abs(i[0])>Math.abs(i[1])?(this.zVel=0,this.xVel=o):(this.xVel=0,this.zVel=s)}})},6591:t=>{const e={once:THREE.LoopOnce,repeat:THREE.LoopRepeat,pingpong:THREE.LoopPingPong};function i(t){return t.replace(/[|\\{}()[\]^$+*?.]/g,"\\$&")}t.exports=AFRAME.registerComponent("animation-mixer",{schema:{clip:{default:"*"},duration:{default:0},clampWhenFinished:{default:!1,type:"boolean"},crossFadeDuration:{default:0},loop:{default:"repeat",oneOf:Object.keys(e)},repetitions:{default:1/0,min:0},timeScale:{default:1},startAt:{default:0}},init:function(){this.model=null,this.mixer=null,this.activeActions=[];const t=this.el.getObject3D("mesh");t?this.load(t):this.el.addEventListener("model-loaded",(t=>{this.load(t.detail.model)}))},load:function(t){const e=this.el;this.model=t,this.mixer=new THREE.AnimationMixer(t),this.mixer.addEventListener("loop",(t=>{e.emit("animation-loop",{action:t.action,loopDelta:t.loopDelta})})),this.mixer.addEventListener("finished",(t=>{e.emit("animation-finished",{action:t.action,direction:t.direction})})),this.data.clip&&this.update({})},remove:function(){this.mixer&&this.mixer.stopAllAction()},update:function(t){if(!t)return;const i=this.data,o=AFRAME.utils.diff(i,t);if("clip"in o)return this.stopAction(),void(i.clip&&this.playAction());this.activeActions.forEach((t=>{"duration"in o&&i.duration&&t.setDuration(i.duration),"clampWhenFinished"in o&&(t.clampWhenFinished=i.clampWhenFinished),("loop"in o||"repetitions"in o)&&t.setLoop(e[i.loop],i.repetitions),"timeScale"in o&&t.setEffectiveTimeScale(i.timeScale)}))},stopAction:function(){const t=this.data;for(let e=0;e<this.activeActions.length;e++)t.crossFadeDuration?this.activeActions[e].fadeOut(t.crossFadeDuration):this.activeActions[e].stop();this.activeActions.length=0},playAction:function(){if(!this.mixer)return;const t=this.model,o=this.data,s=t.animations||(t.geometry||{}).animations||[];if(!s.length)return;const n=(a=o.clip,new RegExp("^"+a.split(/\*+/).map(i).join(".*")+"$"));var a;for(let i,a=0;i=s[a];a++)if(i.name.match(n)){const s=this.mixer.clipAction(i,t);s.enabled=!0,s.clampWhenFinished=o.clampWhenFinished,o.duration&&s.setDuration(o.duration),1!==o.timeScale&&s.setEffectiveTimeScale(o.timeScale),s.startAt(this.mixer.time-o.startAt/1e3),s.setLoop(e[o.loop],o.repetitions).fadeIn(o.crossFadeDuration).play(),this.activeActions.push(s)}},tick:function(t,e){this.mixer&&!isNaN(e)&&this.mixer.update(e/1e3)}})},2290:(t,e,i)=>{THREE.ColladaLoader=i(203),t.exports.Component=AFRAME.registerComponent("collada-model-legacy",{schema:{type:"asset"},init:function(){this.model=null,this.loader=new THREE.ColladaLoader},update:function(){var t=this,e=this.el,i=this.data,o=this.el.sceneEl.systems.renderer;i&&(this.remove(),this.loader.load(i,(function(i){t.model=i.scene,t.model.traverse((function(t){if(t.isMesh){var e=t.material;e.color&&o.applyColorCorrection(e.color),e.map&&o.applyColorCorrection(e.map),e.emissive&&o.applyColorCorrection(e.emissive),e.emissiveMap&&o.applyColorCorrection(e.emissiveMap)}})),e.setObject3D("mesh",t.model),e.emit("model-loaded",{format:"collada",model:t.model})})))},remove:function(){this.model&&this.el.removeObject3D("mesh")}})},5310:(t,e,i)=>{THREE.FBXLoader=i(9434),t.exports=AFRAME.registerComponent("fbx-model",{schema:{src:{type:"asset"},crossorigin:{default:""}},init:function(){this.model=null},update:function(){const t=this.data;if(!t.src)return;this.remove();const e=new THREE.FBXLoader;t.crossorigin&&e.setCrossOrigin(t.crossorigin),e.load(t.src,this.load.bind(this))},load:function(t){this.model=t,this.el.setObject3D("mesh",t),this.el.emit("model-loaded",{format:"fbx",model:t})},remove:function(){this.model&&this.el.removeObject3D("mesh")}})},8837:(t,e,i)=>{const o=i(4657)(),s=function(){let t;return function(){return t=t||o("https://cdn.jsdelivr.net/gh/mrdoob/three.js@r86/examples/js/loaders/GLTFLoader.js"),t}}();t.exports=AFRAME.registerComponent("gltf-model-legacy",{schema:{type:"model"},init:function(){this.model=null,this.loader=null,this.loaderPromise=s().then((()=>{this.loader=new THREE.GLTFLoader,this.loader.setCrossOrigin("Anonymous")}))},update:function(){const t=this,e=this.el,i=this.data;i&&(this.remove(),this.loaderPromise.then((()=>{this.loader.load(i,(function(i){t.model=i.scene,t.model.animations=i.animations,e.setObject3D("mesh",t.model),e.emit("model-loaded",{format:"gltf",model:t.model})}))})))},remove:function(){this.model&&this.el.removeObject3D("mesh")}})},9874:(t,e,i)=>{i(6591),i(2290),i(5310),i(8837),i(171)},171:t=>{t.exports=AFRAME.registerComponent("object-model",{schema:{src:{type:"asset"},crossorigin:{default:""}},init:function(){this.model=null},update:function(){let t;const e=this.data;e.src&&(this.remove(),t=new THREE.ObjectLoader,e.crossorigin&&t.setCrossOrigin(e.crossorigin),t.load(e.src,(t=>{t.traverse((t=>{t instanceof THREE.SkinnedMesh&&t.material&&(t.material.skinning=!!(t.geometry&&t.geometry.bones||[]).length)})),this.load(t)})))},load:function(t){this.model=t,this.el.setObject3D("mesh",t),this.el.emit("model-loaded",{format:"json",model:t})},remove:function(){this.model&&this.el.removeObject3D("mesh")}})}}]);