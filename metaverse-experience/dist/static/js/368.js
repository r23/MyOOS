(self.webpackChunkmetaverse_experience=self.webpackChunkmetaverse_experience||[]).push([[368],{2182:function(e,t,i){i(3687);var a=i(1389),r=a.GRAVITY,n=a.CONTACT_MATERIAL,o=i(598),s=i(7810),p=i(8254),l=i(6203);e.exports=AFRAME.registerSystem("physics",{schema:{driver:{default:"local",oneOf:["local","worker","network","ammo"]},networkUrl:{default:"",if:{driver:"network"}},workerFps:{default:60,if:{driver:"worker"}},workerInterpolate:{default:!0,if:{driver:"worker"}},workerInterpBufferSize:{default:2,if:{driver:"worker"}},workerEngine:{default:"cannon",if:{driver:"worker"},oneOf:["cannon"]},workerDebug:{default:!1,if:{driver:"worker"}},gravity:{default:r},iterations:{default:a.ITERATIONS},friction:{default:n.friction},restitution:{default:n.restitution},contactEquationStiffness:{default:n.contactEquationStiffness},contactEquationRelaxation:{default:n.contactEquationRelaxation},frictionEquationStiffness:{default:n.frictionEquationStiffness},frictionEquationRegularization:{default:n.frictionEquationRegularization},maxInterval:{default:4/60},debug:{default:!1},debugDrawMode:{default:THREE.AmmoDebugConstants.NoDebug},maxSubSteps:{default:4},fixedTimeStep:{default:1/60}},async init(){var e=this.data;switch(this.debug=e.debug,this.callbacks={beforeStep:[],step:[],afterStep:[]},this.listeners={},this.driver=null,e.driver){case"local":this.driver=new o;break;case"ammo":this.driver=new l;break;case"network":this.driver=new p(e.networkUrl);break;case"worker":this.driver=new s({fps:e.workerFps,engine:e.workerEngine,interpolate:e.workerInterpolate,interpolationBufferSize:e.workerInterpBufferSize,debug:e.workerDebug});break;default:throw new Error('[physics] Driver not recognized: "%s".',e.driver)}"ammo"!==e.driver?(await this.driver.init({quatNormalizeSkip:0,quatNormalizeFast:!1,solverIterations:e.iterations,gravity:e.gravity}),this.driver.addMaterial({name:"defaultMaterial"}),this.driver.addMaterial({name:"staticMaterial"}),this.driver.addContactMaterial("defaultMaterial","defaultMaterial",{friction:e.friction,restitution:e.restitution,contactEquationStiffness:e.contactEquationStiffness,contactEquationRelaxation:e.contactEquationRelaxation,frictionEquationStiffness:e.frictionEquationStiffness,frictionEquationRegularization:e.frictionEquationRegularization}),this.driver.addContactMaterial("staticMaterial","defaultMaterial",{friction:1,restitution:0,contactEquationStiffness:e.contactEquationStiffness,contactEquationRelaxation:e.contactEquationRelaxation,frictionEquationStiffness:e.frictionEquationStiffness,frictionEquationRegularization:e.frictionEquationRegularization})):await this.driver.init({gravity:e.gravity,debugDrawMode:e.debugDrawMode,solverIterations:e.iterations,maxSubSteps:e.maxSubSteps,fixedTimeStep:e.fixedTimeStep}),this.initialized=!0,this.debug&&this.setDebug(!0)},tick:function(e,t){if(this.initialized&&t){var i,a=this.callbacks;for(i=0;i<this.callbacks.beforeStep.length;i++)this.callbacks.beforeStep[i].beforeStep(e,t);for(this.driver.step(Math.min(t/1e3,this.data.maxInterval)),i=0;i<a.step.length;i++)a.step[i].step(e,t);for(i=0;i<a.afterStep.length;i++)a.afterStep[i].afterStep(e,t)}},setDebug:function(e){this.debug=e,"ammo"===this.data.driver&&this.initialized&&(e&&!this.debugDrawer?(this.debugDrawer=this.driver.getDebugDrawer(this.el.object3D),this.debugDrawer.enable()):this.debugDrawer&&(this.debugDrawer.disable(),this.debugDrawer=null))},addBody:function(e,t,i){var a=this.driver;"local"===this.data.driver&&(e.__applyImpulse=e.applyImpulse,e.applyImpulse=function(){a.applyBodyMethod(e,"applyImpulse",arguments)},e.__applyForce=e.applyForce,e.applyForce=function(){a.applyBodyMethod(e,"applyForce",arguments)},e.updateProperties=function(){a.updateBodyProperties(e)},this.listeners[e.id]=function(t){e.el.emit("collide",t)},e.addEventListener("collide",this.listeners[e.id])),this.driver.addBody(e,t,i)},removeBody:function(e){this.driver.removeBody(e),"local"!==this.data.driver&&"worker"!==this.data.driver||(e.removeEventListener("collide",this.listeners[e.id]),delete this.listeners[e.id],e.applyImpulse=e.__applyImpulse,delete e.__applyImpulse,e.applyForce=e.__applyForce,delete e.__applyForce,delete e.updateProperties)},addConstraint:function(e){this.driver.addConstraint(e)},removeConstraint:function(e){this.driver.removeConstraint(e)},addComponent:function(e){var t=this.callbacks;e.beforeStep&&t.beforeStep.push(e),e.step&&t.step.push(e),e.afterStep&&t.afterStep.push(e)},removeComponent:function(e){var t=this.callbacks;e.beforeStep&&t.beforeStep.splice(t.beforeStep.indexOf(e),1),e.step&&t.step.splice(t.step.indexOf(e),1),e.afterStep&&t.afterStep.splice(t.afterStep.indexOf(e),1)},getContacts:function(){return this.driver.getContacts()},getMaterial:function(e){return this.driver.getMaterial(e)}})},7558:function(e){e.exports.slerp=function(e,t,i){if(i<=0)return e;if(i>=1)return t;var a=e[0],r=e[1],n=e[2],o=e[3],s=o*t[3]+a*t[0]+r*t[1]+n*t[2];if(!(s<0))return t;if((e=e.slice())[3]=-t[3],e[0]=-t[0],e[1]=-t[1],e[2]=-t[2],(s=-s)>=1)return e[3]=o,e[0]=a,e[1]=r,e[2]=n,this;var p=Math.sqrt(1-s*s);if(Math.abs(p)<.001)return e[3]=.5*(o+e[3]),e[0]=.5*(a+e[0]),e[1]=.5*(r+e[1]),e[2]=.5*(n+e[2]),this;var l=Math.atan2(p,s),u=Math.sin((1-i)*l)/p,d=Math.sin(i*l)/p;return e[3]=o*u+e[3]*d,e[0]=a*u+e[0]*d,e[1]=r*u+e[1]*d,e[2]=n*u+e[2]*d,e}},779:function(e,t,i){var a=i(3687),r=i(7558),n="__id";e.exports.ID=n;var o={};function s(e){var t={type:e.type};if(e.type===a.Shape.types.BOX)t.halfExtents=l(e.halfExtents);else if(e.type===a.Shape.types.SPHERE)t.radius=e.radius;else{if(e._type!==a.Shape.types.CYLINDER)throw new Error("Unimplemented shape type: %s",e.type);t.type=a.Shape.types.CYLINDER,t.radiusTop=e.radiusTop,t.radiusBottom=e.radiusBottom,t.height=e.height,t.numSegments=e.numSegments}return t}function p(e){var t;if(e.type===a.Shape.types.BOX)t=new a.Box(u(e.halfExtents));else if(e.type===a.Shape.types.SPHERE)t=new a.Sphere(e.radius);else{if(e.type!==a.Shape.types.CYLINDER)throw new Error("Unimplemented shape type: %s",e.type);(t=new a.Cylinder(e.radiusTop,e.radiusBottom,e.height,e.numSegments))._type=a.Shape.types.CYLINDER}return t}function l(e){return e.toArray()}function u(e){return new a.Vec3(e[0],e[1],e[2])}function d(e){return e.toArray()}function c(e){return new a.Quaternion(e[0],e[1],e[2],e[3])}e.exports.assignID=function(e,t){t[n]||(o[e]=o[e]||1,t[n]=e+"_"+o[e]++)},e.exports.serializeBody=function(e){return{shapes:e.shapes.map(s),shapeOffsets:e.shapeOffsets.map(l),shapeOrientations:e.shapeOrientations.map(d),position:l(e.position),quaternion:e.quaternion.toArray(),velocity:l(e.velocity),angularVelocity:l(e.angularVelocity),id:e[n],mass:e.mass,linearDamping:e.linearDamping,angularDamping:e.angularDamping,fixedRotation:e.fixedRotation,allowSleep:e.allowSleep,sleepSpeedLimit:e.sleepSpeedLimit,sleepTimeLimit:e.sleepTimeLimit}},e.exports.deserializeBodyUpdate=function(e,t){return t.position.set(e.position[0],e.position[1],e.position[2]),t.quaternion.set(e.quaternion[0],e.quaternion[1],e.quaternion[2],e.quaternion[3]),t.velocity.set(e.velocity[0],e.velocity[1],e.velocity[2]),t.angularVelocity.set(e.angularVelocity[0],e.angularVelocity[1],e.angularVelocity[2]),t.linearDamping=e.linearDamping,t.angularDamping=e.angularDamping,t.fixedRotation=e.fixedRotation,t.allowSleep=e.allowSleep,t.sleepSpeedLimit=e.sleepSpeedLimit,t.sleepTimeLimit=e.sleepTimeLimit,t.mass!==e.mass&&(t.mass=e.mass,t.updateMassProperties()),t},e.exports.deserializeInterpBodyUpdate=function(e,t,i,a){var n=1-a,o=a;i.position.set(e.position[0]*n+t.position[0]*o,e.position[1]*n+t.position[1]*o,e.position[2]*n+t.position[2]*o);var s=r.slerp(e.quaternion,t.quaternion,a);return i.quaternion.set(s[0],s[1],s[2],s[3]),i.velocity.set(e.velocity[0]*n+t.velocity[0]*o,e.velocity[1]*n+t.velocity[1]*o,e.velocity[2]*n+t.velocity[2]*o),i.angularVelocity.set(e.angularVelocity[0]*n+t.angularVelocity[0]*o,e.angularVelocity[1]*n+t.angularVelocity[1]*o,e.angularVelocity[2]*n+t.angularVelocity[2]*o),i.linearDamping=t.linearDamping,i.angularDamping=t.angularDamping,i.fixedRotation=t.fixedRotation,i.allowSleep=t.allowSleep,i.sleepSpeedLimit=t.sleepSpeedLimit,i.sleepTimeLimit=t.sleepTimeLimit,i.mass!==t.mass&&(i.mass=t.mass,i.updateMassProperties()),i},e.exports.deserializeBody=function(e){for(var t,i=new a.Body({mass:e.mass,position:u(e.position),quaternion:c(e.quaternion),velocity:u(e.velocity),angularVelocity:u(e.angularVelocity),linearDamping:e.linearDamping,angularDamping:e.angularDamping,fixedRotation:e.fixedRotation,allowSleep:e.allowSleep,sleepSpeedLimit:e.sleepSpeedLimit,sleepTimeLimit:e.sleepTimeLimit}),r=0;t=e.shapes[r];r++)i.addShape(p(t),u(e.shapeOffsets[r]),c(e.shapeOrientations[r]));return i[n]=e.id,i},e.exports.serializeShape=s,e.exports.deserializeShape=p,e.exports.serializeConstraint=function(e){var t={id:e[n],type:e.type,maxForce:e.maxForce,bodyA:e.bodyA[n],bodyB:e.bodyB[n]};switch(e.type){case"LockConstraint":break;case"DistanceConstraint":t.distance=e.distance;break;case"HingeConstraint":case"ConeTwistConstraint":t.axisA=l(e.axisA),t.axisB=l(e.axisB),t.pivotA=l(e.pivotA),t.pivotB=l(e.pivotB);break;case"PointToPointConstraint":t.pivotA=l(e.pivotA),t.pivotB=l(e.pivotB);break;default:throw new Error("Unexpected constraint type: "+e.type+'. You may need to manually set `constraint.type = "FooConstraint";`.')}return t},e.exports.deserializeConstraint=function(e,t){var i,r=a[e.type],o=t[e.bodyA],s=t[e.bodyB];switch(e.type){case"LockConstraint":i=new a.LockConstraint(o,s,e);break;case"DistanceConstraint":i=new a.DistanceConstraint(o,s,e.distance,e.maxForce);break;case"HingeConstraint":case"ConeTwistConstraint":i=new r(o,s,{pivotA:u(e.pivotA),pivotB:u(e.pivotB),axisA:u(e.axisA),axisB:u(e.axisB),maxForce:e.maxForce});break;case"PointToPointConstraint":i=new a.PointToPointConstraint(o,u(e.pivotA),s,u(e.pivotB),e.maxForce);break;default:throw new Error("Unexpected constraint type: "+e.type)}return i[n]=e.id,i},e.exports.serializeContact=function(e){return{bi:e.bi[n],bj:e.bj[n],ni:l(e.ni),ri:l(e.ri),rj:l(e.rj)}},e.exports.deserializeContact=function(e,t){return{bi:t[e.bi],bj:t[e.bj],ni:u(e.ni),ri:u(e.ri),rj:u(e.rj)}},e.exports.serializeVec3=l,e.exports.deserializeVec3=u,e.exports.serializeQuaternion=d,e.exports.deserializeQuaternion=c}}]);