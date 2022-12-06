import { Raycaster, Vector3 } from "three";
import { useXR, Interactive } from "@react-three/xr";
import { useFrame, useThree } from "@react-three/fiber";
import { useCallback, useRef, useState, useEffect } from "react";
import { useRapier, useRigidBody, RigidBody } from "@react-three/rapier";

export function TeleportIndicator(props) {
	return (
		<>
			<pointLight position={[0, 0.5, 0]} args={[0xff00ff, 2, 0.6]} />
			<mesh position={[0, 0.25, 0]}>
				<coneBufferGeometry args={[0.1, 0.5, 6]} attach="geometry" />
				<meshBasicMaterial attach="material" color={0xff00ff} />
			</mesh>
		</>
	);
}
export function ClickIndicatorObject(props) {
	return (
		<>
			<mesh position={[0, 0, -0.005]}>
				<boxGeometry args={[0.08, 0.08, 0.08]} attach="geometry" />
				<meshBasicMaterial attach="material" color={0x26ff00} />
			</mesh>
		</>
	);
}

export default function TeleportTravel(props) {
	const { scene } = useThree();
	const {
		centerOnTeleport,
		Indicator = TeleportIndicator,
		ClickIndicator = ClickIndicatorObject,
		useNormal = true
	} = props;
	const [isHovered, setIsHovered] = useState(false);
	const [canTeleport, setCanTeleport] = useState(true);
	const [canInteract, setCanInteract] = useState(false);
	const [intersectionPoint, setIntersectionPoint] = useState();
	const target = useRef();
	const targetLoc = useRef();
	const ray = useRef(new Raycaster());
	const { world, rapier } = useRapier();

	const rayDir = useRef({
		pos: new Vector3(),
		dir: new Vector3()
	});

	const { controllers, player } = useXR();
	// Set a variable finding an object in the three.js scene that is named reticle.
	useEffect(() => {
		// Remove the reticle when the controllers are registered.
		const reticle = scene.getObjectByName("reticle");
		if (controllers.length > 0 && reticle) {
			reticle.visible = false;
		}
	}, [controllers]);

	useFrame(() => {
		if (
			isHovered &&
			controllers.length > 0 &&
			ray.current &&
			target.current &&
			targetLoc.current
		) {
			controllers[0].controller.getWorldDirection(rayDir.current.dir);
			controllers[0].controller.getWorldPosition(rayDir.current.pos);
			// ray.far = 0.05;
			// ray.near = 0.01;
			rayDir.current.dir.multiplyScalar(-1);
			ray.current.set(rayDir.current.pos, rayDir.current.dir);

			const [intersection] = ray.current.intersectObject(target.current);

			if (
				intersection &&
				intersection.distance < 100 &&
				intersection.distance > .5
			) {
				const intersectionObject = intersection.object;
				let containsInteractiveObject = false;
				intersectionObject.traverseAncestors((parent) => {
					if (parent.name === "video") {
						containsInteractiveObject = true;
					}
					if (parent.name === "portal") {
						containsInteractiveObject = true;
					}
				});
				if (containsInteractiveObject) {
					setCanInteract(true);
					setCanTeleport(false);
				} else {
					setCanInteract(false);
					setCanTeleport(true);
				}
				if (useNormal) {
					const p = intersection.point;
					setIntersectionPoint(p);
					// targetLoc.current.position.set(0, 0, 0);

					// const n = intersection.face.normal.clone();
					// n.transformDirection(intersection.object.matrixWorld);

					// targetLoc.current.lookAt(n);
					// targetLoc.current.rotateOnAxis(
					// 	new Vector3(1, 0, 0),
					// 	Math.PI / 2
					// );
					targetLoc.current.position.copy(p);
				} else {
					targetLoc.current.position.copy(intersection.point);
				}
			}
		}
	});

	const click = useCallback(() => {
		if (isHovered && !canInteract) {
			targetLoc.current.position.set(
				targetLoc.current.position.x,
				targetLoc.current.position.y + 1.1,
				targetLoc.current.position.z
			);
			if (canTeleport) {
				player.position.copy(targetLoc.current.position);
			}
		}
		if (isHovered && canInteract) {
			if (controllers.length > 0) {
				const rigidBodyDesc = new rapier.RigidBodyDesc(
					rapier.RigidBodyType.Static
				)
					// The rigid body translation.
					// Default: zero vector.
					.setTranslation(
						targetLoc.current.position.x,
						targetLoc.current.position.y,
						targetLoc.current.position.z - 0.01
					)
					.setLinvel(0, 0, 0)
					// The linear velocity of this body.
					// .setLinvel(targetLoc.current.position.x, targetLoc.current.position.y - 1.1, targetLoc.current.position.z)
					// Default: zero vector.
					.setGravityScale(1)
					// Default: zero velocity.
					.setCanSleep(false)
					// Whether or not CCD is enabled for this rigid-body.
					// Default: false
					.setCcdEnabled(true);
				const rigidBody = world.createRigidBody(rigidBodyDesc);

				const collider = world.createCollider(
					rapier.ColliderDesc.cuboid(0.05, 0.05, 0.05),
					rigidBody
					// rapier.ColliderDesc.capsule(0.5, 0.5), rigidBody
				);

				collider.setFriction(0.1);
				collider.setRestitution(0);
				// collider.setSensor(true);
				// collider.setTranslation(intersects[0].point);
				setTimeout(() => {
					world.removeCollider(collider);
					world.removeRigidBody(rigidBody);
				}, 50);
			}
		}
	}, [isHovered, canTeleport, canInteract]);

	return (
		<>
			{isHovered && canTeleport && (
				<group ref={targetLoc}>
					<Indicator />
				</group>
			)}
			{isHovered && canInteract && (
				<group ref={targetLoc}>
					<ClickIndicator />
				</group>
			)}
			<Interactive
				onSelect={click}
				onHover={(e) => {
					setIsHovered(true);
				}}
				onBlur={() => {
					setIsHovered(false);
					setCanTeleport(true);
					setCanInteract(false);
				}}
			>
				<group ref={target}>{props.children}</group>
			</Interactive>
		</>
	);
}
