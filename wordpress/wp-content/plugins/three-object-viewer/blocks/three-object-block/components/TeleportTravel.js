import { Raycaster, Vector3 } from "three";
import { useXR, Interactive } from "@react-three/xr";
import { useFrame } from "@react-three/fiber";
import { useCallback, useRef, useState } from "react";

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

export default function TeleportTravel(props) {
	const {
		centerOnTeleport,
		Indicator = TeleportIndicator,
		useNormal = true
	} = props;
	const [isHovered, setIsHovered] = useState(false);
	const target = useRef();
	const targetLoc = useRef();
	const ray = useRef(new Raycaster());

	const rayDir = useRef({
		pos: new Vector3(),
		dir: new Vector3()
	});

	const { controllers, player } = useXR();

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
			rayDir.current.dir.multiplyScalar(-1);
			ray.current.set(rayDir.current.pos, rayDir.current.dir);

			const [intersection] = ray.current.intersectObject(target.current);

			if (intersection) {
				if (useNormal) {
					const p = intersection.point;

					targetLoc.current.position.set(0, 0, 0);

					const n = intersection.face.normal.clone();
					n.transformDirection(intersection.object.matrixWorld);

					targetLoc.current.lookAt(n);
					targetLoc.current.rotateOnAxis(
						new Vector3(1, 0, 0),
						Math.PI / 2
					);
					targetLoc.current.position.copy(p);
				} else {
					targetLoc.current.position.copy(intersection.point);
				}
			}
		}
	});

	const click = useCallback(() => {
		if (isHovered) {
			player.position.copy(targetLoc.current.position);
		}
	}, [centerOnTeleport, isHovered, useNormal]);

	return (
		<>
			{isHovered && (
				<group ref={targetLoc}>
					<Indicator />
				</group>
			)}
			<Interactive
				onSelect={click}
				onHover={() => setIsHovered(true)}
				onBlur={() => setIsHovered(false)}
			>
				<group ref={target}>{props.children}</group>
			</Interactive>
		</>
	);
}
