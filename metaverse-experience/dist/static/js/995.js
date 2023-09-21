/*! For license information please see 995.js.LICENSE.txt */
(self.webpackChunkmetaverse_experience=self.webpackChunkmetaverse_experience||[]).push([[995],{9972:function(t){var e;window,e=function(){return function(t){var e={};function n(i){if(e[i])return e[i].exports;var o=e[i]={i:i,l:!1,exports:{}};return t[i].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=t,n.c=e,n.d=function(t,e,i){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:i})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var i=Object.create(null);if(n.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(i,o,function(e){return t[e]}.bind(null,o));return i},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s="./index.js")}({"./index.js":function(t,e,n){"use strict";var i=AFRAME.utils.styleParser;if("undefined"==typeof AFRAME)throw new Error("Component attempted to register before AFRAME was available.");AFRAME.registerComponent("event-set",{schema:{default:"",parse:function(t){return i.parse(t)}},multiple:!0,init:function(){this.eventHandler=null,this.eventName=null},update:function(t){this.removeEventListener(),this.updateEventListener(),this.addEventListener()},remove:function(){this.removeEventListener()},pause:function(){this.removeEventListener()},play:function(){this.addEventListener()},updateEventListener:function(){var t,e,n,i=this,o=this.data,s=this.el;t=o._event||this.id,e=o._target,n=e?s.sceneEl.querySelector(e):s,this.eventName=t;var r=function(){var t;for(t in o)"_event"!==t&&"_target"!==t&&AFRAME.utils.entity.setComponentProperty.call(i,n,t,o[t])};isNaN(o._delay)?this.eventHandler=r:this.eventHandler=function(){setTimeout(r,parseFloat(o._delay))}},addEventListener:function(){this.el.addEventListener(this.eventName,this.eventHandler)},removeEventListener:function(){this.el.removeEventListener(this.eventName,this.eventHandler)}})}})},t.exports=e()},401:function(t,e,n){n(2406),n(9874),n(9236),n(2578),n(6699)},6453:function(t){t.exports=Object.assign((function(){}),{FACE_1:0,FACE_2:1,FACE_3:2,FACE_4:3,L_SHOULDER_1:4,R_SHOULDER_1:5,L_SHOULDER_2:6,R_SHOULDER_2:7,SELECT:8,START:9,DPAD_UP:12,DPAD_DOWN:13,DPAD_LEFT:14,DPAD_RIGHT:15,VENDOR:16})},4379:function(t){t.exports=function(t,e,n){this.type=t,this.index=e,this.pressed=n.pressed,this.value=n.value}},4657:function(t){function e(t){const e=document.getElementById(t),n=e.parentNode;try{n&&n.removeChild(e)}catch(t){}}function n(t,n,i){return new i((function(i,o){const s=n.timeout||5e3,r="script_"+Date.now()+"_"+Math.ceil(1e5*Math.random()),a=function(t,e){var n=document.createElement("script");return n.type="text/javascript",n.async=!0,n.id=e,n.src=t,n}(t,r),c=setTimeout((function(){o(new Error("Script request to "+t+" timed out")),e(r)}),s),u=function(t){clearTimeout(t)};a.addEventListener("load",(function(t){i({ok:!0}),u(c),e(r)})),a.addEventListener("error",(function(n){o(new Error("Script request to "+t+" failed "+n)),u(c),e(r)})),function(t){const e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(t,e)}(a)}))}t.exports=function(t){return t=t||{},function(e,i){return n(e,i=i||{},t.Promise||Promise)}}},3386:function(t){t.exports=AFRAME.registerComponent("checkpoint-controls",{schema:{enabled:{default:!0},mode:{default:"teleport",oneOf:["teleport","animate"]},animateSpeed:{default:3}},init:function(){this.active=!0,this.checkpoint=null,this.isNavMeshConstrained=!1,this.offset=new THREE.Vector3,this.position=new THREE.Vector3,this.targetPosition=new THREE.Vector3},play:function(){this.active=!0},pause:function(){this.active=!1},setCheckpoint:function(t){const e=this.el;this.active&&this.checkpoint!==t&&(this.checkpoint&&e.emit("navigation-end",{checkpoint:this.checkpoint}),this.checkpoint=t,this.sync(),this.position.distanceTo(this.targetPosition)<.1?this.checkpoint=null:(e.emit("navigation-start",{checkpoint:t}),"teleport"===this.data.mode&&(this.el.setAttribute("position",this.targetPosition),this.checkpoint=null,e.emit("navigation-end",{checkpoint:t}),e.components["movement-controls"].updateNavLocation())))},isVelocityActive:function(){return!(!this.active||!this.checkpoint)},getVelocity:function(){if(!this.active)return;const t=this.data,e=this.offset,n=this.position,i=this.targetPosition,o=this.checkpoint;return this.sync(),n.distanceTo(i)<.1?(this.checkpoint=null,this.el.emit("navigation-end",{checkpoint:o}),e.set(0,0,0)):(e.setLength(t.animateSpeed),e)},sync:function(){const t=this.offset,e=this.position,n=this.targetPosition;e.copy(this.el.getAttribute("position")),this.checkpoint.object3D.getWorldPosition(n),n.add(this.checkpoint.components.checkpoint.getOffset()),t.copy(n).sub(e)}})},5062:function(t,e,n){const i=n(6453),o=n(4379),s=.2,r="left",a="right",c=1,u=2;t.exports=AFRAME.registerComponent("gamepad-controls",{GamepadButton:i,schema:{enabled:{default:!0},rotationSensitivity:{default:2}},init:function(){const t=this.el.sceneEl;this.system=t.systems["tracked-controls-webxr"]||{controllers:[]},this.prevTime=window.performance.now(),this.buttons={};const e=this.el.object3D.rotation;this.pitch=new THREE.Object3D,this.pitch.rotation.x=e.x,this.yaw=new THREE.Object3D,this.yaw.position.y=10,this.yaw.rotation.y=e.y,this.yaw.add(this.pitch),this._lookVector=new THREE.Vector2,this._moveVector=new THREE.Vector2,this._dpadVector=new THREE.Vector2,t.addBehavior(this)},update:function(){this.tick()},tick:function(t,e){this.updateButtonState(),this.updateRotation(e)},remove:function(){},isVelocityActive:function(){if(!this.data.enabled||!this.isConnected())return!1;const t=this._dpadVector,e=this._moveVector;this.getDpad(t),this.getJoystick(c,e);const n=t.x||e.x,i=t.y||e.y;return Math.abs(n)>s||Math.abs(i)>s},getVelocityDelta:function(){const t=this._dpadVector,e=this._moveVector;this.getDpad(t),this.getJoystick(c,e);const n=t.x||e.x,i=t.y||e.y,o=new THREE.Vector3;return Math.abs(n)>s&&(o.x+=n),Math.abs(i)>s&&(o.z+=i),o},isRotationActive:function(){if(!this.data.enabled||!this.isConnected())return!1;const t=this._lookVector;return this.getJoystick(u,t),Math.abs(t.x)>s||Math.abs(t.y)>s},updateRotation:function(t){if(!this.isRotationActive())return;const e=this.data,n=this.yaw,i=this.pitch;n.rotation.y=this.el.object3D.rotation.y,i.rotation.x=this.el.object3D.rotation.x;const o=this._lookVector;this.getJoystick(u,o),Math.abs(o.x)<=s&&(o.x=0),Math.abs(o.y)<=s&&(o.y=0),o.multiplyScalar(e.rotationSensitivity*t/1e3),n.rotation.y-=o.x,i.rotation.x-=o.y,i.rotation.x=Math.max(-Math.PI/2,Math.min(Math.PI/2,i.rotation.x)),this.el.object3D.rotation.set(i.rotation.x,n.rotation.y,0)},updateButtonState:function(){const t=this.getGamepad(a);if(this.data.enabled&&t)for(var e=0;e<t.buttons.length;e++)t.buttons[e].pressed&&!this.buttons[e]?this.emit(new o("gamepadbuttondown",e,t.buttons[e])):!t.buttons[e].pressed&&this.buttons[e]&&this.emit(new o("gamepadbuttonup",e,t.buttons[e])),this.buttons[e]=t.buttons[e].pressed;else Object.keys(this.buttons)&&(this.buttons={})},emit:function(t){this.el.emit(t.type,t),this.el.emit(t.type+":"+t.index,new o(t.type,t.index,t))},getGamepad:function(){const t=[],e=[];return function(n){const i=this.el.sceneEl.components["proxy-controls"],o=i&&i.isConnected()&&i.getGamepad(0);if(o)return o;t.length=0;for(let e=0;e<this.system.controllers.length;e++){const i=this.system.controllers[e],o=i?i.gamepad:null;if(t.push(o),o&&i.handedness===n)return o}const s=navigator.getGamepads?navigator.getGamepads():e;for(let t=0;t<s.length;t++){const e=s[t];if(e&&e.hand===n)return e}return t[0]||s[0]}}(),getButton:function(t){return this.getGamepad(a).buttons[t]},getAxis:function(t){return this.getGamepad(t>1?a:r).axes[t]},getJoystick:function(t,e){const n=this.getGamepad(t===c?r:a);if(!n)return e.set(0,0);if("xr-standard"===n.mapping)switch(t){case c:return e.set(n.axes[2],n.axes[3]);case u:return e.set(n.axes[2],0)}else switch(t){case c:return e.set(n.axes[0],n.axes[1]);case u:return e.set(n.axes[2],n.axes[3])}throw new Error('Unexpected joystick index "%d".',t)},getDpad:function(t){const e=this.getGamepad(r);return e&&e.buttons[i.DPAD_RIGHT]?t.set((e.buttons[i.DPAD_RIGHT].pressed?1:0)+(e.buttons[i.DPAD_LEFT].pressed?-1:0),(e.buttons[i.DPAD_UP].pressed?-1:0)+(e.buttons[i.DPAD_DOWN].pressed?1:0)):t.set(0,0)},isConnected:function(){const t=this.getGamepad(r);return!(!t||!t.connected)},getID:function(){return this.getGamepad(r).id}})},2406:function(t,e,n){n(3386),n(5062),n(2250),n(9048),n(3819),n(9893)}}]);