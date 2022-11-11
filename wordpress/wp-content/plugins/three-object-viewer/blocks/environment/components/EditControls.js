import React, { useRef, useState } from "react";

import { useFrame, useThree } from "@react-three/fiber";
import { PointerLockControls, OrbitControls } from "@react-three/drei";

const EditControls = (props) => {
	const controlsRef = useRef();
	const isLocked = useRef(false);
	const [moveForward, setMoveForward] = useState(false);
	const [moveBackward, setMoveBackward] = useState(false);
	const [moveLeft, setMoveLeft] = useState(false);
	const [moveRight, setMoveRight] = useState(false);
	const [jump, setJump] = useState(false);

	useFrame(() => {
		const velocity = 0.5;

		if (moveForward) {
			// playerThing.applyImpulse({x:0, y:0, z:0.1}, true);
			controlsRef.current.moveForward(velocity);
		} else if (moveLeft) {
			controlsRef.current.moveRight(-velocity);
		} else if (moveBackward) {
			controlsRef.current.moveForward(-velocity);
		} else if (moveRight) {
			controlsRef.current.moveRight(velocity);
		} else if (jump) {
		}
	});

	const onKeyDown = function (event, props) {
		switch (event.code) {
			case "ArrowUp":
			case "KeyW":
				setMoveForward(true);
				break;

			case "ArrowLeft":
			case "KeyA":
				setMoveLeft(true);
				break;

			case "ArrowDown":
			case "KeyS":
				setMoveBackward(true);
				break;

			case "ArrowRight":
			case "KeyD":
				setMoveRight(true);
				break;
			case "Space":
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

	const onKeyUp = function (event) {
		switch (event.code) {
			case "ArrowUp":
			case "KeyW":
				setMoveForward(false);
				break;

			case "ArrowLeft":
			case "KeyA":
				setMoveLeft(false);
				break;

			case "ArrowDown":
			case "KeyS":
				setMoveBackward(false);
				break;

			case "Space":
				setJump(false);
				break;

			case "ArrowRight":
			case "KeyD":
				setMoveRight(false);
				break;

			default:
		}
	};

	document.addEventListener("keydown", onKeyDown);
	document.addEventListener("keyup", onKeyUp);
	const { gl } = useThree();
	if (gl) {
		return (
			<PointerLockControls
				domElement={gl.domElement}
				onUpdate={() => {
					if (controlsRef.current) {
						renderer.addEventListener("lock", () => {
							console.log("lock");
							isLocked.current = true;
						});
						controlsRef.current.addEventListener("unlock", () => {
							console.log("unlock");
							isLocked.current = false;
						});
					}
				}}
				ref={controlsRef}
			/>
		);
	}
};

export default EditControls;
