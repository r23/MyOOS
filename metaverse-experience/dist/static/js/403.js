(self.webpackChunkmetaverse_experience=self.webpackChunkmetaverse_experience||[]).push([[403],{1416:function(t){t.exports=function(){function t(t,e){return 0===e.length||-1!==e.indexOf(t.detail.buttonEvent.type)}return{schema:{startButtons:{default:[]},endButtons:{default:[]}},startButtonOk:function(e){return t(e,this.data.startButtons)},endButtonOk:function(e){return t(e,this.data.endButtons)}}}()},2945:function(t){t.exports={schema:{usePhysics:{default:"ifavailable"}},physicsInit:function(){this.constraints=new Map},physicsUpdate:function(){"never"===this.data.usePhysics&&this.constraints.size&&this.physicsClear()},physicsRemove:function(){this.physicsClear()},physicsStart:function(t){if("never"!==this.data.usePhysics&&this.el.body&&t.detail.hand.body&&!this.constraints.has(t.detail.hand)){const e=Math.random().toString(36).substr(2,9);return this.el.setAttribute("constraint__"+e,{target:t.detail.hand}),this.constraints.set(t.detail.hand,e),!0}return"only"===this.data.usePhysics},physicsEnd:function(t){const e=this.constraints.get(t.detail.hand);e&&(this.el.removeAttribute("constraint__"+e),this.constraints.delete(t.detail.hand))},physicsClear:function(){if(this.el.body)for(const t of this.constraints.values())this.el.body.world.removeConstraint(t);this.constraints.clear()},physicsIsConstrained:function(t){return this.constraints.has(t)},physicsIsGrabbing(){return this.constraints.size>0}}},4336:function(t,e,s){const n=AFRAME.utils.extendDeep,r=n({},s(1416));AFRAME.registerComponent("stretchable",n(r,{schema:{usePhysics:{default:"ifavailable"},invert:{default:!1},physicsUpdateRate:{default:100}},init:function(){this.STRETCHED_STATE="stretched",this.STRETCH_EVENT="stretch-start",this.UNSTRETCH_EVENT="stretch-end",this.stretched=!1,this.stretchers=[],this.scale=new THREE.Vector3,this.handPos=new THREE.Vector3,this.otherHandPos=new THREE.Vector3,this.start=this.start.bind(this),this.end=this.end.bind(this),this.el.addEventListener(this.STRETCH_EVENT,this.start),this.el.addEventListener(this.UNSTRETCH_EVENT,this.end)},update:function(t){this.updateBodies=AFRAME.utils.throttleTick(this._updateBodies,this.data.physicsUpdateRate,this)},tick:function(t,e){if(!this.stretched)return;this.scale.copy(this.el.getAttribute("scale")),this.stretchers[0].object3D.getWorldPosition(this.handPos),this.stretchers[1].object3D.getWorldPosition(this.otherHandPos);const s=this.handPos.distanceTo(this.otherHandPos);let n=1;null!==this.previousStretch&&0!==s&&(n=Math.pow(s/this.previousStretch,this.data.invert?-1:1)),this.previousStretch=s,null==this.previousPhysicsStretch&&(this.previousPhysicsStretch=s),this.scale.multiplyScalar(n),this.el.setAttribute("scale",this.scale),this.updateBodies(t,e)},remove:function(){this.el.removeEventListener(this.STRETCH_EVENT,this.start),this.el.removeEventListener(this.UNSTRETCH_EVENT,this.end)},start:function(t){this.stretched||this.stretchers.includes(t.detail.hand)||!this.startButtonOk(t)||t.defaultPrevented||(this.stretchers.push(t.detail.hand),2===this.stretchers.length&&(this.stretched=!0,this.previousStretch=null,this.previousPhysicsStretch=null,this.el.addState(this.STRETCHED_STATE)),t.preventDefault&&t.preventDefault())},end:function(t){const e=this.stretchers.indexOf(t.detail.hand);!t.defaultPrevented&&this.endButtonOk(t)&&(-1!==e&&(this.stretchers.splice(e,1),this.stretched=!1,this.el.removeState(this.STRETCHED_STATE),this._updateBodies()),t.preventDefault&&t.preventDefault())},_updateBodies:function(){if(!this.el.body||"never"===this.data.usePhysics)return;const t=this.previousStretch;let e=1;if(null!==this.previousPhysicsStretch&&t>0&&(e=Math.pow(t/this.previousPhysicsStretch,this.data.invert?-1:1)),this.previousPhysicsStretch=t,1!==e){for(const t of this.el.childNodes)this.stretchBody(t,e);this.stretchBody(this.el,e)}},stretchBody:function(t,e){if(!t.body)return;let s,n;for(let r=0;r<t.body.shapes.length;r++)s=t.body.shapes[r],s.halfExtents?(s.halfExtents.scale(e,s.halfExtents),s.updateConvexPolyhedronRepresentation()):s.radius?(s.radius*=e,s.updateBoundingSphereRadius()):this.shapeWarned||(console.warn("Unable to stretch physics body: unsupported shape"),this.shapeWarned=!0),n=t.body.shapeOffsets[r],n.scale(e,n);t.body.updateBoundingRadius()}}))},2040:function(){AFRAME.registerSystem("super-hands",{init:function(){this.superHands=[]},registerMe:function(t){1===this.superHands.length&&(this.superHands[0].otherSuperHand=t,t.otherSuperHand=this.superHands[0]),this.superHands.push(t)},unregisterMe:function(t){const e=this.superHands.indexOf(t);-1!==e&&this.superHands.splice(e,1),this.superHands.forEach((e=>{e.otherSuperHand===t&&(e.otherSuperHand=null)}))}})},6594:function(t,e,s){"use strict";s.r(e),s.d(e,{Pathfinding:function(){return h},PathfindingHelper:function(){return c}});var n=s(9477);class r{static roundNumber(t,e){const s=Math.pow(10,e);return Math.round(t*s)/s}static sample(t){return t[Math.floor(Math.random()*t.length)]}static distanceToSquared(t,e){var s=t.x-e.x,n=t.y-e.y,r=t.z-e.z;return s*s+n*n+r*r}static isPointInPoly(t,e){for(var s=!1,n=-1,r=t.length,i=r-1;++n<r;i=n)(t[n].z<=e.z&&e.z<t[i].z||t[i].z<=e.z&&e.z<t[n].z)&&e.x<(t[i].x-t[n].x)*(e.z-t[n].z)/(t[i].z-t[n].z)+t[n].x&&(s=!s);return s}static isVectorInPolygon(t,e,s){var n=1e5,r=-1e5,i=[];return e.vertexIds.forEach((t=>{n=Math.min(s[t].y,n),r=Math.max(s[t].y,r),i.push(s[t])})),!!(t.y<r+.5&&t.y>n-.5&&this.isPointInPoly(i,t))}static triarea2(t,e,s){return(s.x-t.x)*(e.z-t.z)-(e.x-t.x)*(s.z-t.z)}static vequal(t,e){return this.distanceToSquared(t,e)<1e-5}static mergeVertices(t,e=1e-4){e=Math.max(e,Number.EPSILON);for(var s={},r=t.getIndex(),i=t.getAttribute("position"),o=r?r.count:i.count,h=0,a=[],c=[],u=Math.log10(1/e),l=Math.pow(10,u),d=0;d<o;d++){var p=r?r.getX(d):d,f="";f+=~~(i.getX(p)*l)+",",f+=~~(i.getY(p)*l)+",",(f+=~~(i.getZ(p)*l)+",")in s?a.push(s[f]):(c.push(i.getX(p)),c.push(i.getY(p)),c.push(i.getZ(p)),s[f]=h,a.push(h),h++)}const v=new n.TlE(new Float32Array(c),i.itemSize,i.normalized),g=new n.u9r;return g.setAttribute("position",v),g.setIndex(a),g}}class i{constructor(t){this.content=[],this.scoreFunction=t}push(t){this.content.push(t),this.sinkDown(this.content.length-1)}pop(){const t=this.content[0],e=this.content.pop();return this.content.length>0&&(this.content[0]=e,this.bubbleUp(0)),t}remove(t){const e=this.content.indexOf(t),s=this.content.pop();e!==this.content.length-1&&(this.content[e]=s,this.scoreFunction(s)<this.scoreFunction(t)?this.sinkDown(e):this.bubbleUp(e))}size(){return this.content.length}rescoreElement(t){this.sinkDown(this.content.indexOf(t))}sinkDown(t){const e=this.content[t];for(;t>0;){const s=(t+1>>1)-1,n=this.content[s];if(!(this.scoreFunction(e)<this.scoreFunction(n)))break;this.content[s]=e,this.content[t]=n,t=s}}bubbleUp(t){const e=this.content.length,s=this.content[t],n=this.scoreFunction(s);for(;;){const r=t+1<<1,i=r-1;let o,h=null;if(i<e&&(o=this.scoreFunction(this.content[i]),o<n&&(h=i)),r<e&&this.scoreFunction(this.content[r])<(null===h?n:o)&&(h=r),null===h)break;this.content[t]=this.content[h],this.content[h]=s,t=h}}}class o{constructor(){this.portals=[]}push(t,e){void 0===e&&(e=t),this.portals.push({left:t,right:e})}stringPull(){const t=this.portals,e=[];let s,n,i,o=0,h=0,a=0;s=t[0].left,n=t[0].left,i=t[0].right,e.push(s);for(let c=1;c<t.length;c++){const u=t[c].left,l=t[c].right;if(r.triarea2(s,i,l)<=0){if(!(r.vequal(s,i)||r.triarea2(s,n,l)>0)){e.push(n),s=n,o=h,n=s,i=s,h=o,a=o,c=o;continue}i=l,a=c}if(r.triarea2(s,n,u)>=0){if(!(r.vequal(s,n)||r.triarea2(s,i,u)<0)){e.push(i),s=i,o=a,n=s,i=s,h=o,a=o,c=o;continue}n=u,h=c}}return 0!==e.length&&r.vequal(e[e.length-1],t[t.length-1].left)||e.push(t[t.length-1].left),this.path=e,e}}class h{constructor(){this.zones={}}static createZone(t,e=1e-4){return class{static buildZone(t,e){const s=this._buildNavigationMesh(t,e),i={};s.vertices.forEach((t=>{t.x=r.roundNumber(t.x,2),t.y=r.roundNumber(t.y,2),t.z=r.roundNumber(t.z,2)})),i.vertices=s.vertices;const o=this._buildPolygonGroups(s);return i.groups=new Array(o.length),o.forEach(((t,e)=>{const s=new Map;t.forEach(((t,e)=>{s.set(t,e)}));const o=new Array(t.length);t.forEach(((t,e)=>{const h=[];t.neighbours.forEach((t=>h.push(s.get(t))));const a=[];t.neighbours.forEach((e=>a.push(this._getSharedVerticesInOrder(t,e))));const c=new n.Pa4(0,0,0);c.add(i.vertices[t.vertexIds[0]]),c.add(i.vertices[t.vertexIds[1]]),c.add(i.vertices[t.vertexIds[2]]),c.divideScalar(3),c.x=r.roundNumber(c.x,2),c.y=r.roundNumber(c.y,2),c.z=r.roundNumber(c.z,2),o[e]={id:e,neighbours:h,vertexIds:t.vertexIds,centroid:c,portals:a}})),i.groups[e]=o})),i}static _buildNavigationMesh(t,e){return t=r.mergeVertices(t,e),this._buildPolygonsFromGeometry(t)}static _spreadGroupId(t){let e=new Set([t]);for(;e.size>0;){const s=e;e=new Set,s.forEach((s=>{s.group=t.group,s.neighbours.forEach((t=>{void 0===t.group&&e.add(t)}))}))}}static _buildPolygonGroups(t){const e=[];return t.polygons.forEach((t=>{void 0!==t.group?e[t.group].push(t):(t.group=e.length,this._spreadGroupId(t),e.push([t]))})),e}static _buildPolygonNeighbours(t,e){const s=new Set,n=e[t.vertexIds[1]],r=e[t.vertexIds[2]];return e[t.vertexIds[0]].forEach((e=>{e!==t&&(n.includes(e)||r.includes(e))&&s.add(e)})),n.forEach((e=>{e!==t&&r.includes(e)&&s.add(e)})),s}static _buildPolygonsFromGeometry(t){const e=[],s=[],r=t.attributes.position,i=t.index,o=[];for(let t=0;t<r.count;t++)s.push((new n.Pa4).fromBufferAttribute(r,t)),o[t]=[];for(let s=0;s<t.index.count;s+=3){const t=i.getX(s),n=i.getX(s+1),r=i.getX(s+2),h={vertexIds:[t,n,r],neighbours:null};e.push(h),o[t].push(h),o[n].push(h),o[r].push(h)}return e.forEach((t=>{t.neighbours=this._buildPolygonNeighbours(t,o)})),{polygons:e,vertices:s}}static _getSharedVerticesInOrder(t,e){const s=t.vertexIds,n=s[0],r=s[1],i=s[2],o=e.vertexIds,h=o.includes(n),a=o.includes(r),c=o.includes(i);return h&&a&&c?Array.from(s):h&&a?[n,r]:a&&c?[r,i]:h&&c?[i,n]:(console.warn("Error processing navigation mesh neighbors; neighbors with <2 shared vertices found."),[])}}.buildZone(t,e)}setZoneData(t,e){this.zones[t]=e}getRandomNode(t,e,s,i){if(!this.zones[t])return new n.Pa4;s=s||null,i=i||0;const o=[];return this.zones[t].groups[e].forEach((t=>{s&&i?r.distanceToSquared(s,t.centroid)<i*i&&o.push(t.centroid):o.push(t.centroid)})),r.sample(o)||new n.Pa4}getClosestNode(t,e,s,n=!1){const i=this.zones[e].vertices;let o=null,h=1/0;return this.zones[e].groups[s].forEach((e=>{const s=r.distanceToSquared(e.centroid,t);s<h&&(!n||r.isVectorInPolygon(t,e,i))&&(o=e,h=s)})),o}findPath(t,e,s,h){const a=this.zones[s].groups[h],c=this.zones[s].vertices,u=this.getClosestNode(t,s,h,!0),l=this.getClosestNode(e,s,h,!0);if(!u||!l)return null;const d=class{static init(t){for(let e=0;e<t.length;e++){const s=t[e];s.f=0,s.g=0,s.h=0,s.cost=1,s.visited=!1,s.closed=!1,s.parent=null}}static cleanUp(t){for(let e=0;e<t.length;e++){const s=t[e];delete s.f,delete s.g,delete s.h,delete s.cost,delete s.visited,delete s.closed,delete s.parent}}static heap(){return new i((function(t){return t.f}))}static search(t,e,s){this.init(t);const n=this.heap();for(n.push(e);n.size()>0;){const e=n.pop();if(e===s){let t=e;const s=[];for(;t.parent;)s.push(t),t=t.parent;return this.cleanUp(s),s.reverse()}e.closed=!0;const r=this.neighbours(t,e);for(let t=0,i=r.length;t<i;t++){const i=r[t];if(i.closed)continue;const o=e.g+i.cost,h=i.visited;if(!h||o<i.g){if(i.visited=!0,i.parent=e,!i.centroid||!s.centroid)throw new Error("Unexpected state");i.h=i.h||this.heuristic(i.centroid,s.centroid),i.g=o,i.f=i.g+i.h,h?n.rescoreElement(i):n.push(i)}}}return[]}static heuristic(t,e){return r.distanceToSquared(t,e)}static neighbours(t,e){const s=[];for(let n=0;n<e.neighbours.length;n++)s.push(t[e.neighbours[n]]);return s}}.search(a,u,l),p=function(t,e){for(var s=0;s<t.neighbours.length;s++)if(t.neighbours[s]===e.id)return t.portals[s]},f=new o;f.push(t);for(let t=0;t<d.length;t++){const e=d[t],s=d[t+1];if(s){const t=p(e,s);f.push(c[t[0]],c[t[1]])}}f.push(e),f.stringPull();const v=f.path.map((t=>new n.Pa4(t.x,t.y,t.z)));return v.shift(),v}}h.prototype.getGroup=function(){const t=new n.JOQ;return function(e,s,n=!1){if(!this.zones[e])return null;let i=null,o=Math.pow(50,2);const h=this.zones[e];for(let e=0;e<h.groups.length;e++){const a=h.groups[e];for(const c of a){if(n&&(t.setFromCoplanarPoints(h.vertices[c.vertexIds[0]],h.vertices[c.vertexIds[1]],h.vertices[c.vertexIds[2]]),Math.abs(t.distanceToPoint(s))<.01)&&r.isPointInPoly([h.vertices[c.vertexIds[0]],h.vertices[c.vertexIds[1]],h.vertices[c.vertexIds[2]]],s))return e;const a=r.distanceToSquared(c.centroid,s);a<o&&(i=e,o=a)}}return i}}(),h.prototype.clampStep=function(){const t=new n.Pa4,e=new n.JOQ,s=new n.CJI,r=new n.Pa4;let i,o,h=new n.Pa4;return function(n,a,c,u,l,d){const p=this.zones[u].vertices,f=this.zones[u].groups[l],v=[c],g={};g[c.id]=0,i=void 0,h.set(0,0,0),o=1/0,e.setFromCoplanarPoints(p[c.vertexIds[0]],p[c.vertexIds[1]],p[c.vertexIds[2]]),e.projectPoint(a,t),r.copy(t);for(let e=v.pop();e;e=v.pop()){s.set(p[e.vertexIds[0]],p[e.vertexIds[1]],p[e.vertexIds[2]]),s.closestPointToPoint(r,t),t.distanceToSquared(r)<o&&(i=e,h.copy(t),o=t.distanceToSquared(r));const n=g[e.id];if(!(n>2))for(let t=0;t<e.neighbours.length;t++){const s=f[e.neighbours[t]];s.id in g||(v.push(s),g[s.id]=n+1)}}return d.copy(h),i}}();const a={PLAYER:new n.Ilk(15631215).convertSRGBToLinear().getHex(),TARGET:new n.Ilk(14469912).convertSRGBToLinear().getHex(),PATH:new n.Ilk(41903).convertSRGBToLinear().getHex(),WAYPOINT:new n.Ilk(41903).convertSRGBToLinear().getHex(),CLAMPED_STEP:new n.Ilk(14472114).convertSRGBToLinear().getHex(),CLOSEST_NODE:new n.Ilk(4417387).convertSRGBToLinear().getHex()};class c extends n.Tme{constructor(){super(),this._playerMarker=new n.Kj0(new n.xo$(.25,32,32),new n.vBJ({color:a.PLAYER})),this._targetMarker=new n.Kj0(new n.DvJ(.3,.3,.3),new n.vBJ({color:a.TARGET})),this._nodeMarker=new n.Kj0(new n.DvJ(.1,.8,.1),new n.vBJ({color:a.CLOSEST_NODE})),this._stepMarker=new n.Kj0(new n.DvJ(.1,1,.1),new n.vBJ({color:a.CLAMPED_STEP})),this._pathMarker=new n.Tme,this._pathLineMaterial=new n.nls({color:a.PATH,linewidth:2}),this._pathPointMaterial=new n.vBJ({color:a.WAYPOINT}),this._pathPointGeometry=new n.xo$(.08),this._markers=[this._playerMarker,this._targetMarker,this._nodeMarker,this._stepMarker,this._pathMarker],this._markers.forEach((t=>{t.visible=!1,this.add(t)}))}setPath(t){for(;this._pathMarker.children.length;)this._pathMarker.children[0].visible=!1,this._pathMarker.remove(this._pathMarker.children[0]);t=[this._playerMarker.position].concat(t);const e=new n.u9r;e.setAttribute("position",new n.TlE(new Float32Array(3*t.length),3));for(let s=0;s<t.length;s++)e.attributes.position.setXYZ(s,t[s].x,t[s].y+.2,t[s].z);this._pathMarker.add(new n.x12(e,this._pathLineMaterial));for(let e=0;e<t.length-1;e++){const s=new n.Kj0(this._pathPointGeometry,this._pathPointMaterial);s.position.copy(t[e]),s.position.y+=.2,this._pathMarker.add(s)}return this._pathMarker.visible=!0,this}setPlayerPosition(t){return this._playerMarker.position.copy(t),this._playerMarker.visible=!0,this}setTargetPosition(t){return this._targetMarker.position.copy(t),this._targetMarker.visible=!0,this}setNodePosition(t){return this._nodeMarker.position.copy(t),this._nodeMarker.visible=!0,this}setStepPosition(t){return this._stepMarker.position.copy(t),this._stepMarker.visible=!0,this}reset(){for(;this._pathMarker.children.length;)this._pathMarker.children[0].visible=!1,this._pathMarker.remove(this._pathMarker.children[0]);return this._markers.forEach((t=>{t.visible=!1})),this}}},8034:function(t){var e=arguments[3],s=arguments[4],n=arguments[5],r=JSON.stringify;t.exports=function(t,i){for(var o,h=Object.keys(n),a=0,c=h.length;a<c;a++){var u=h[a],l=n[u].exports;if(l===t||l&&l.default===t){o=u;break}}if(!o){o=Math.floor(Math.pow(16,8)*Math.random()).toString(16);var d={};for(a=0,c=h.length;a<c;a++){d[u=h[a]]=u}s[o]=["function(require,module,exports){"+t+"(self); }",d]}var p=Math.floor(Math.pow(16,8)*Math.random()).toString(16),f={};f[o]=o,s[p]=["function(require,module,exports){var f = require("+r(o)+");(f.default ? f.default : f)(self);}",f];var v={};!function t(e){for(var n in v[e]=!0,s[e][1]){var r=s[e][1][n];v[r]||t(r)}}(p);var g="("+e+")({"+Object.keys(v).map((function(t){return r(t)+":["+s[t][0]+","+r(s[t][1])+"]"})).join(",")+"},{},["+r(p)+"])",b=window.URL||window.webkitURL||window.mozURL||window.msURL,y=new Blob([g],{type:"text/javascript"});if(i&&i.bare)return y;var w=b.createObjectURL(y),E=new Worker(w);return E.objectURL=w,E}}}]);