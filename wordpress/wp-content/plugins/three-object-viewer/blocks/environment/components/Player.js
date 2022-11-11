import { TextureLoader } from "three/src/loaders/TextureLoader";
// import { useXR, Interactive } from "@react-three/xr";
import { useFrame, useLoader, useThree } from "@react-three/fiber";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import Controls from "./Controls";

import { useRef, useState, useEffect } from "react";
import { RigidBody, CapsuleCollider } from "@react-three/rapier";
import defaultVRM from "../../../inc/avatars/3ov_default_avatar.vrm";
import { VRMUtils, VRMLoaderPlugin } from "@pixiv/three-vrm";

export default function Player(props) {
	const { camera, scene } = useThree();
	const participantObject = scene.getObjectByName("playerOne");
	const [rapierId, setRapierId] = useState("");
	const [contactPoint, setContactPoint] = useState("");
	const [headPoint, setHeadPoint] = useState("");
	const rigidRef = useRef();

	useFrame(() => {
		if (participantObject) {
			const posY = participantObject.parent.position.y;
			// var posY = participantObject.userData.vrm.firstPerson.humanoid.humanBones.head.position.y;
			// camera.position.setY( posY + 1.5 );
			camera.position.setY(posY + 1.5);
			// participantObject.rotation.set([0, camera.rotation.y, 0]);
			// participantObject.rotation.set(camera.rotation);
		}
	});

	// Participant VRM.
	const fallbackURL = threeObjectPlugin + defaultVRM;
	const playerURL = userData.vrm ? userData.vrm : fallbackURL;

	const someSceneState = useLoader(GLTFLoader, playerURL, (loader) => {
		loader.register((parser) => {
			return new VRMLoaderPlugin(parser);
		});
	});

	if (someSceneState?.userData?.gltfExtensions?.VRM) {
		const playerController = someSceneState.userData.vrm;
		const loadedProfile = useLoader(TextureLoader, userData.profileImage);
		playerController.scene.traverse((obj) => {
			if (obj.name === "profile") {
				const newMat = obj.material.clone();
				newMat.map = loadedProfile;
				obj.material = newMat;
				obj.material.map.needsUpdate = true;
			}
		});
		VRMUtils.rotateVRM0(playerController);
		useEffect(() => {
			setHeadPoint(
				playerController.firstPerson.humanoid.humanBones.head.node
					.position.y
			);
		}, []);
		playerController.firstPerson.humanoid.humanBones.head.node.scale.set([
			0, 0, 0
		]);
		// const rotationVRM = playerController.scene.rotation.y;
		// playerController.scene.rotation.set( 0, rotationVRM, 0 );
		// playerController.scene.scale.set( 1, 1, 1 );

		return (
			<>
				{playerController && (
					<>
						<RigidBody
							colliders={false}
							linearDamping={100}
							angularDamping={0}
							friction={0}
							ref={rigidRef}
							mass={0}
							type={"dynamic"}
							onCollisionEnter={({ manifold, target }) => {
								setRapierId(target.colliderSet.map.data[1]);
								setContactPoint(manifold.solverContactPoint(0));
							}}
							// onCollisionExit={ () => {
							// 	console.log('Collision at world position');
							// }}
						>
							<CapsuleCollider
								position={[0, 0.5, 0]}
								args={[1, 1]}
							/>
							<Controls
								id={rapierId}
								point={contactPoint}
								something={rigidRef}
								spawnPoint={props.spawnPoint}
							/>
							<primitive
								visible={false}
								name="playerOne"
								object={playerController.scene}
							/>
						</RigidBody>
					</>
				)}
			</>
		);
	}
}
