import React, { useEffect, useRef, useState } from "react";
// import { Raycaster, Vector3, Math, Euler } from 'three';
import { Euler, Raycaster, MathUtils } from "three";

import { useFrame, useThree } from "@react-three/fiber";
import { PointerLockControls } from "@react-three/drei";
// import previewOptions from "@wordpress/block-editor/build/components/preview-options";
import { useRapier, useRigidBody } from "@react-three/rapier";

// function touchStarted() {
// 	getAudioContext().resume();
// }

const Controls = (props) => {
	const p2pcf = window.p2pcf;
	const controlsRef = useRef();
	const isLocked = useRef(false);
	const [lock, setLock] = useState(false);
	const [click, setClick] = useState(false);
	const [shiftActive, setShift] = useState(false);
	const [moveForward, setMoveForward] = useState(false);
	const [moveBackward, setMoveBackward] = useState(false);
	const [moveLeft, setMoveLeft] = useState(false);
	const [moveRight, setMoveRight] = useState(false);
	const [spawnPos, setSpawnPos] = useState(props.spawnPoint);
	const [jump, setJump] = useState(false);
	const [thirdPerson, setThirdPerson] = useState(false); // Add this line
	const currentRigidbody = useRigidBody();
	const { world, rapier } = useRapier();
	const ray = new rapier.Ray({ x: 0, y: 0, z: 0 }, { x: 0, y: -1, z: 0 });

	const pointerRay = new rapier.Ray(
		{ x: 0, y: 0, z: 0 },
		{ x: 0, y: 0, z: -0.5 }
	);
	const { camera, scene } = useThree();

	useEffect(() => {

		if(props.mobileControls !== null && props.mobileControls?.direction !== undefined){
			if(props.mobileControls.direction.angle === "down"){
				setMoveForward(false);
				setMoveBackward(true);
				setMoveLeft(false);
				setMoveRight(false);
			} else if(props.mobileControls.direction.angle === "up"){
				setMoveBackward(false);
				setMoveForward(true);
				setMoveLeft(false);
				setMoveRight(false);
			} else if(props.mobileControls.direction.angle === "left"){
				setMoveLeft(true);
				setMoveForward(false);
				setMoveBackward(false);
				setMoveRight(false);
			} else if(props.mobileControls.direction.angle === "right"){
				setMoveRight(true);
				setMoveLeft(false);
				setMoveForward(false);
				setMoveBackward(false);
			} else {
				setMoveForward(false);
				setMoveBackward(false);
				setMoveLeft(false);
				setMoveRight(false);
			}
		} else {
			setMoveForward(false);
			setMoveBackward(false);
			setMoveLeft(false);
			setMoveRight(false);
		}

	}, [props.mobileControls]);

	// useEffect(() => {
	// 	console.log("rot controls", props.mobileRotControls);

	// 	if(props.mobileRotControls !== null && props.mobileRotControls?.direction !== undefined){
	// 		if(props.mobileRotControls.direction.angle === "down"){
	// 			setMoveForward(false);
	// 			setMoveBackward(true);
	// 			setMoveLeft(false);
	// 			setMoveRight(false);
	// 		} else if(props.mobileRotControls.direction.angle === "up"){
	// 			setMoveBackward(false);
	// 			setMoveForward(true);
	// 			setMoveLeft(false);
	// 			setMoveRight(false);
	// 		} else if(props.mobileRotControls.direction.angle === "left"){
	// 			setMoveLeft(true);
	// 			setMoveForward(false);
	// 			setMoveBackward(false);
	// 			setMoveRight(false);
	// 		} else if(props.mobileRotControls.direction.angle === "right"){
	// 			setMoveRight(true);
	// 			setMoveLeft(false);
	// 			setMoveForward(false);
	// 			setMoveBackward(false);
	// 		} else {
	// 			setMoveForward(false);
	// 			setMoveBackward(false);
	// 			setMoveLeft(false);
	// 			setMoveRight(false);
	// 		}
	// 	} else {
	// 		setMoveForward(false);
	// 		setMoveBackward(false);
	// 		setMoveLeft(false);
	// 		setMoveRight(false);
	// 	}

	// }, [props.mobileRotControls]);

	useEffect(() => {
		const playerThing = world.getRigidBody(props.something.current.handle);
		const x = Number(spawnPos[0]);
		const y = Number(spawnPos[1]);
		const z = Number(spawnPos[2]);

		setTimeout(() => {
			let finalPoints = [];
			if (props.spawnPointsToAdd) {
				props.spawnPointsToAdd.forEach((point) => {
					finalPoints.push([Number(point[0]), Number(point[1]), Number(point[2])]);
				});
			}
			finalPoints.push([x, y, z]);
			//pick a random point
			let randomPoint = finalPoints[Math.floor(Math.random() * finalPoints.length)];
			// Check if the converted values are valid and finite
			// Set the camera's position
			camera.position.set(randomPoint[0], randomPoint[1], randomPoint[2]);

			playerThing.setTranslation({
				x: randomPoint[0],
				y: randomPoint[1],
				z: randomPoint[2]
			});
		}, 20);
	}, []);

	const raycaster = new Raycaster();

	useFrame(() => {

		const playerThing = world.getRigidBody(props.something.current.handle);

		// raycaster.set( camera.position, camera.getWorldDirection() );
		// raycast forward from the camera and log hitting any objects
		// var intersects = raycaster.intersectObjects( scene.children );
		// if	(intersects.length > 0) {
		// 	console.log(intersects[0].object);
		// }

		const playerThingColliders = world.getCollider(
			props.something.current.handle
		);
		//lock rotations.
		playerThing.restrictRotations({enableX: false, enableY: false, enableZ: false}, true);
		//maybebringthemback
		// playerThing.lockRotations(true, true);
		if (lock) {
			playerThing.setBodyType(1);
		} else {
			playerThing.setBodyType(0);
		}
		// playerThing.setRotation({x: Math.radToDeg(controlsRef.current.camera.rotation.x), y: Math.radToDeg(controlsRef.current.camera.rotation.y), z: Math.radToDeg(controlsRef.current.camera.rotation.z), w: 0}, true);

		// pointerRay.origin.x = camera.position.x;
		// pointerRay.origin.y = camera.position.y;
		// pointerRay.origin.z = camera.position.z;
		// console.log(pointerRay);

		ray.origin.x = playerThing.translation().x;
		ray.origin.y = playerThing.translation().y;
		ray.origin.z = playerThing.translation().z;
		const velocity = shiftActive ? 0.28 : 0.08;
		world.raw().step();
		const maxToi = 14;
		const solid = true;

		if (click) {
			if (raycaster) {
				raycaster.setFromCamera({ x: 0, y: 0 }, camera);
				const intersects = raycaster.intersectObjects(
					scene.children,
					true
				);
				if (intersects.length > 0) {
					// console.log(intersects[0].object.name);
					const pointHitObject = scene.getObjectByName(
						intersects[0].object.name
					);
					// console.log(pointHitObject);
					// add a rigidbody at the point of intersection
					if (intersects[0].point) {
						const rigidBodyDesc = new rapier.RigidBodyDesc(
							rapier.RigidBodyType.Dynamic
						)
							// The rigid body translation.
							// Default: zero vector.
							.setTranslation(
								intersects[0].point.x,
								intersects[0].point.y,
								intersects[0].point.z
							)
							// The linear velocity of this body.
							// Default: zero velocity.
							.setCanSleep(false)
							// Whether or not CCD is enabled for this rigid-body.
							// Default: false
							.setCcdEnabled(true);
						const rigidBody = world.createRigidBody(rigidBodyDesc);

						const collider = world.createCollider(
							rapier.ColliderDesc.cuboid(0.05, 0.05, 0.05),
							rigidBody
						);

						// collider.setTranslation(intersects[0].point);
						setTimeout(() => {
							// console.log("removing collider", collider);
							world.removeCollider(collider);
							world.removeRigidBody(rigidBody);
						}, 50);

						// world.removeCollider(collider.handle);
					}
				}
			}
			setClick(false);
		}
		if (props.mobileRotControls) {
			const rotationSpeed = 0.03;
			const threshold = 45;

			switch (props.mobileRotControls.direction.angle) {
				case 'left':
				  controlsRef.current.camera.rotation.y += rotationSpeed;
				  break;
				case 'right':
				  controlsRef.current.camera.rotation.y -= rotationSpeed;
				  break;
				//   case 'down':
				// 	controlsRef.current.camera.rotation.x -= rotationSpeed;
				// 	break;
				//   case 'up':
				// 	controlsRef.current.camera.rotation.x += rotationSpeed;
				// 	break;
				  default:
				  break;
			  }		
			}
		  
		if (moveForward) {
			if (playerThing) {
				controlsRef.current.moveForward(velocity);
				const hit = world
					.raw()
					.queryPipeline.castRay(
						world.raw().colliders,
						ray,
						maxToi,
						solid,
						0xfffffffff
					);

				playerThing.lockRotations(true, true);
				if (hit) {
					const hitPoint = ray.pointAt(hit.toi);
					playerThing.setTranslation({
						x: controlsRef.current.camera.position.x,
						y: hitPoint.y,
						z: controlsRef.current.camera.position.z
					});
					camera.position.setY(hitPoint.y + 0.007);
				}
				if (p2pcf) {
					const position = [
						controlsRef.current.camera.position.x,
						controlsRef.current.camera.position.y,
						controlsRef.current.camera.position.z
					];
					const rotation = [
						controlsRef.current.camera.rotation.x,
						controlsRef.current.camera.rotation.y,
						controlsRef.current.camera.rotation.z
					];
					const message =
						`{ "${p2pcf.clientId}": [{ "position" : [` +
						position +
						`]},{ "rotation" : [` +
						rotation +
						`]},{ "profileImage" : ["` +
						userData.profileImage +
						`"]}]}`;
					p2pcf.broadcast(new TextEncoder().encode(message));
				}

				// playerThing.applyImpulse({x:0, y:0, z:0.1}, true);
				// const pointerHit = world
				// 	.raw()
				// 	.queryPipeline.castRay(
				// 		world.raw().colliders,
				// 		pointerRay,
				// 		maxToi,
				// 		solid,
				// 		0xfffffffff
				// 	);

				// playerThing.setRotation({x: 0, y: 1, z: 0, w: 0}, true);
				// if (pointerHit){
				// 	console.log(pointerHit);
				// 	const pointerHitPoint = pointerRay.pointAt(hit.toi);
				// 	console.log(pointerHitPoint);

				// }
			}
		} else if (moveLeft) {
			playerThing.lockRotations(true, true);
			// playerThing.setRotation({x: 0, y: -0.707107, z: 0, w: 0.707107}, true);
			controlsRef.current.moveRight(-velocity);
			const hit = world
				.raw()
				.queryPipeline.castRay(
					world.raw().colliders,
					ray,
					maxToi,
					solid,
					0xfffffffff
				);

			if (hit) {
				const hitPoint = ray.pointAt(hit.toi); // Same as: `ray.origin + ray.dir * toi`
				playerThing.setTranslation({
					x: controlsRef.current.camera.position.x,
					y: hitPoint.y,
					z: controlsRef.current.camera.position.z
				});
			}
			if (p2pcf) {
				const position = [
					controlsRef.current.camera.position.x,
					controlsRef.current.camera.position.y,
					controlsRef.current.camera.position.z
				];
				const rotation = [
					controlsRef.current.camera.rotation.x,
					controlsRef.current.camera.rotation.y,
					controlsRef.current.camera.rotation.z
				];
				const message =
					`{ "${p2pcf.clientId}": [{ "position" : [` +
					position +
					`]},{ "rotation" : [` +
					rotation +
					`]},{ "profileImage" : ["` +
					userData.profileImage +
					`"]}]}`;
				p2pcf.broadcast(new TextEncoder().encode(message));
			}
		} else if (moveBackward) {
			playerThing.lockRotations(true, true);
			// playerThing.setRotation({x: 0, y: 0, z: 0, w: -1}, true);

			controlsRef.current.moveForward(-velocity);
			const hit = world
				.raw()
				.queryPipeline.castRay(
					world.raw().colliders,
					ray,
					maxToi,
					solid,
					0xfffffffff
				);

			if (hit) {
				const hitPoint = ray.pointAt(hit.toi); // Same as: `ray.origin + ray.dir * toi`
				playerThing.setTranslation({
					x: controlsRef.current.camera.position.x,
					y: hitPoint.y,
					z: controlsRef.current.camera.position.z
				});
			}
			if (p2pcf) {
				const position = [
					controlsRef.current.camera.position.x,
					controlsRef.current.camera.position.y,
					controlsRef.current.camera.position.z
				];
				const rotation = [
					controlsRef.current.camera.rotation.x,
					controlsRef.current.camera.rotation.y,
					controlsRef.current.camera.rotation.z
				];
				const message =
					`{ "${p2pcf.clientId}": [{ "position" : [` +
					position +
					`]},{ "rotation" : [` +
					rotation +
					`]},{ "profileImage" : ["` +
					userData.profileImage +
					`"]}]}`;
				p2pcf.broadcast(new TextEncoder().encode(message));
			}
		} else if (moveRight) {
			playerThing.lockRotations(true, true);
			// playerThing.setRotation({x: 0, y: 0.707107, z: 0, w: 0.707107}, true);
			controlsRef.current.moveRight(velocity);
			const hit = world
				.raw()
				.queryPipeline.castRay(
					world.raw().colliders,
					ray,
					maxToi,
					solid,
					0xfffffffff
				);

			if (hit) {
				const hitPoint = ray.pointAt(hit.toi); // Same as: `ray.origin + ray.dir * toi`
				playerThing.setTranslation({
					x: controlsRef.current.camera.position.x,
					y: hitPoint.y,
					z: controlsRef.current.camera.position.z
				});
			}
			// rigidBody.applyImpulse(controlsRef.current.vec);
			if (p2pcf) {
				const position = [
					controlsRef.current.camera.position.x,
					controlsRef.current.camera.position.y,
					controlsRef.current.camera.position.z
				];
				const rotation = [
					controlsRef.current.camera.rotation.x,
					controlsRef.current.camera.rotation.y,
					controlsRef.current.camera.rotation.z
				];
				const message =
					`{ "${p2pcf.clientId}": [{ "position" : [` +
					position +
					`]},{ "rotation" : [` +
					rotation +
					`]},{ "profileImage" : ["` +
					userData.profileImage +
					`"]}]}`;
				p2pcf.broadcast(new TextEncoder().encode(message));
			}
		} else if (jump) {
		}
	});
	const onKeyDown = function (event) {
		if(event.target instanceof HTMLInputElement){
		return
		} else {
			switch (event.code) {
				// case when both shift and the w key are pressed
				case "ShiftLeft":
					setShift(true);
					setLock(false);
					break;
				case "ArrowUp":
				case "KeyW":
					setMoveForward(true);
					setLock(false);
					break;

				case "ArrowLeft":
				case "KeyA":
					setMoveLeft(true);
					setLock(false);
					break;

				case "ArrowDown":
				case "KeyS":
					setMoveBackward(true);
					setLock(false);
					break;

				case "ArrowRight":
				case "KeyD":
					setMoveRight(true);
					setLock(false);
					break;
				case "KeyR":
					if (props.something.current) {
						const playerThing = world.getRigidBody(props.something.current.handle);

						const x = Number(spawnPos[0]);
						const y = Number(spawnPos[1]);
						const z = Number(spawnPos[2]);
						if (props.spawnPointsToAdd) {
							let finalPoints = [];
							props.spawnPointsToAdd.forEach((point) => {
								finalPoints.push([Number(point.position.x), Number(point.position.y), Number(point.position.z)]);
							});
							finalPoints.push([x, y, z]);
							//pick a random point
							let randomPoint = finalPoints[Math.floor(Math.random() * finalPoints.length)];
							// Check if the converted values are valid and finite
							// Set the camera's position
							camera.position.set(randomPoint[0], randomPoint[1], randomPoint[2]);

							playerThing.setTranslation({
								x: randomPoint[0],
								y: randomPoint[1],
								z: randomPoint[2]
							});

						} else {
							// Check if the converted values are valid and finite
							// Set the camera's position
							camera.position.set(x, y, z);

							playerThing.setTranslation({
								x: x,
								y: y,
								z: z
							});
						}
					}
					setLock(false);
					break;
				case "Space":
					setLock(false);
					window.addEventListener("keydown", (e) => {
						if (e.keyCode === 32 && e.target === document.body) {
							e.preventDefault();
						}
					});
					setJump(true);
					break;
				default:
			}
		}
	};

	// listen for a click event on the canvas
	window.addEventListener("click", () => {
		setClick(true);
	});

	const onKeyUp = function (event) {
		switch (event.code) {
			case "ShiftLeft":
				setShift(false);
				setLock(true);
				break;
			case "ArrowUp":
			case "KeyW":
				setMoveForward(false);
				setLock(true);
				break;

			case "ArrowLeft":
			case "KeyA":
				setMoveLeft(false);
				setLock(true);
				break;

			case "ArrowDown":
			case "KeyS":
				setMoveBackward(false);
				setLock(true);
				break;

			case "KeyR":
				setLock(true);
				break;
			case "Space":
				setJump(false);
				setLock(true);
				break;

			case "ArrowRight":
			case "KeyD":
				setMoveRight(false);
				setLock(true);
				break;

			default:
		}
	};

		document.addEventListener("keydown", onKeyDown);
		document.addEventListener("keyup", onKeyUp);
		  
	return (
		<>
			<PointerLockControls
				position={[props.spawnPoint[0], props.spawnPoint[1], props.spawnPoint[2]]}
				// onUpdate={() => {
				// 	if (controlsRef.current) {
				// 		controlsRef.current.addEventListener("lock", () => {
				// 			console.log("lock");
				// 			isLocked.current = true;
				// 			props.setShowUI(true);
				// 			console.log("should be true")
				// 		});
				// 		controlsRef.current.addEventListener("unlock", () => {
				// 			console.log("unlock");
				// 			isLocked.current = false;
				// 			props.setShowUI(false);
				// 			console.log("should be false")
				// 		});
				// 	}
				// 	// prevent default click
				// 	window.addEventListener("click", (e) => {
				// 		if (e.target === document.body) {
				// 			e.preventDefault();
				// 		}
				// 	});
				// }}
				onChange={() => {
					if (p2pcf && controlsRef) {
						const position = [
							controlsRef.current.camera.position.x,
							controlsRef.current.camera.position.y,
							controlsRef.current.camera.position.z
						];
						const rotation = [
							controlsRef.current.camera.rotation.x,
							controlsRef.current.camera.rotation.y,
							controlsRef.current.camera.rotation.z
						];
						const message =
							`{ "${p2pcf.clientId}": [{ "position" : [` +
							position +
							`]},{ "rotation" : [` +
							rotation +
							`]},{ "profileImage" : ["` +
							userData.profileImage +
							`"]}]}`;
						p2pcf.broadcast(new TextEncoder().encode(message));
					}
					const rotatingPlayer = scene.getObjectByName("playerOne");
					const euler = new Euler();
					const rotation = euler.setFromQuaternion(
						controlsRef.current.camera.quaternion
					);
					const radians =
						rotation.z > 0 ? rotation.z : 2 * Math.PI + rotation.z;
					const degrees = MathUtils.radToDeg(radians);
					rotatingPlayer.rotation.set(0, radians, 0);
				}}
				ref={controlsRef}
			/>
	</>
	);
};

export default Controls;
