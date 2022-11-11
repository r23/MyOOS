import React, { useEffect, useRef, useState } from "react";
// import { Raycaster, Vector3, Math, Euler } from 'three';
import * as THREE from "three";

import { useFrame, useThree } from "@react-three/fiber";
import { PointerLockControls } from "@react-three/drei";
// import previewOptions from "@wordpress/block-editor/build/components/preview-options";
import { useRapier, useRigidBody } from "@react-three/rapier";

function touchStarted() {
	getAudioContext().resume();
}

const Controls = (props) => {
	const p2pcf = window.p2pcf;
	const controlsRef = useRef();
	const isLocked = useRef(false);
	const [lock, setLock] = useState(false);
	const [moveForward, setMoveForward] = useState(false);
	const [moveBackward, setMoveBackward] = useState(false);
	const [moveLeft, setMoveLeft] = useState(false);
	const [moveRight, setMoveRight] = useState(false);
	const [spawnPos, setSpawnPos] = useState();
	const [jump, setJump] = useState(false);
	const currentRigidbody = useRigidBody();
	const { world, rapier } = useRapier();
	const ray = new rapier.Ray({ x: 0, y: 0, z: 0 }, { x: 0, y: -1, z: 0 });
	const { camera, scene } = useThree();

	useEffect(() => {
		setSpawnPos(props.spawnPoint);
		const playerThing = world.getRigidBody(props.something.current.handle);
		// if (playerThing) {
		// 	playerThing.setTranslation({
		// 		x: props.spawnPoint[0],
		// 		y: props.spawnPoint[1],
		// 		z: props.spawnPoint[2]
		// 	});
		// }

		// controlsRef.current
		// 	.getObject()
		// 	.parent.position.set(
		// 		props.spawnPoint[0],
		// 		props.spawnPoint[1],
		// 		props.spawnPoint[2]
		// 	);
	}, []);

	useFrame(() => {
		const playerThing = world.getRigidBody(props.something.current.handle);
		const playerThingColliders = world.getCollider(
			props.something.current.handle
		);

		// playerThing.restrictRotations({enableX: false, enableY: false, enableZ: false}, true);
		//maybebringthemback
		// playerThing.lockRotations(true, true);
		if (lock) {
			playerThing.setBodyType(1);
		} else {
			playerThing.setBodyType(0);
		}
		// playerThing.setRotation({x: Math.radToDeg(controlsRef.current.camera.rotation.x), y: Math.radToDeg(controlsRef.current.camera.rotation.y), z: Math.radToDeg(controlsRef.current.camera.rotation.z), w: 0}, true);
		ray.origin.x = playerThing.translation().x;
		ray.origin.y = playerThing.translation().y;
		ray.origin.z = playerThing.translation().z;
		const velocity = 0.25;
		world.raw().step();
		const maxToi = 14;
		const solid = true;

		if (moveForward) {
			// playerThing.applyImpulse({x:0, y:0, z:0.1}, true);
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
			// playerThing.setRotation({x: 0, y: 1, z: 0, w: 0}, true);

			if (hit) {
				const hitPoint = ray.pointAt(hit.toi);
				playerThing.setTranslation({
					x: controlsRef.current.camera.position.x,
					y: hitPoint.y,
					z: controlsRef.current.camera.position.z
				});
				camera.position.setY(hitPoint.y + 0.001);
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
		switch (event.code) {
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
				// @todo revisit the respawn logic
				// console.log(props);
				// if (props.something.current) {
				// 	const playerThing = world.getRigidBody(
				// 		props.something.current.handle
				// 	);
				// 	if (playerThing) {
				// 		playerThing.setTranslation({
				// 			x: props.spawnPoint[0],
				// 			y: props.spawnPoint[1],
				// 			z: props.spawnPoint[2]
				// 		});
				// 		if (controlsRef.current) {
				// 			console.log(controlsRef.current.getObject());
				// 			controlsRef.current
				// 				.getObject()
				// 				.parent.position.set(
				// 					props.spawnPoint[0],
				// 					props.spawnPoint[1],
				// 					props.spawnPoint[2]
				// 				);
				// 		}
				// 	}
				// }
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
	};

	const onKeyUp = function (event, props) {
		switch (event.code) {
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

			// case "KeyR":
			// 	setLock(true);
			// 	break;

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
		<PointerLockControls
			onUpdate={() => {
				if (controlsRef.current) {
					controlsRef.current.addEventListener("lock", () => {
						console.log("lock");
						isLocked.current = true;
					});
					controlsRef.current.addEventListener("unlock", () => {
						console.log("unlock");
						isLocked.current = false;
					});
				}
			}}
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
				const euler = new THREE.Euler();
				const rotation = euler.setFromQuaternion(
					controlsRef.current.camera.quaternion
				);
				const radians =
					rotation.z > 0 ? rotation.z : 2 * Math.PI + rotation.z;
				const degrees = THREE.MathUtils.radToDeg(radians);
				rotatingPlayer.rotation.set(0, radians, 0);
			}}
			ref={controlsRef}
		/>
	);
};

export default Controls;
