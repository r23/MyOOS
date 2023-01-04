import * as THREE from "three";
import { TextureLoader } from "three/src/loaders/TextureLoader";
import { useFrame, useLoader, useThree, Interactive } from "@react-three/fiber";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import Controls from "./Controls";

import { useRef, useState, useEffect } from "react";
import { RigidBody, CapsuleCollider } from "@react-three/rapier";
import defaultVRM from "../../../inc/avatars/3ov_default_avatar.vrm";
import { VRMUtils, VRMLoaderPlugin } from "@pixiv/three-vrm";
import { useXR } from "@react-three/xr";

function Reticle() {
	const { camera } = useThree();
	var reticle = new THREE.Mesh(
		new THREE.RingGeometry( 0.85 * 5, 5, 32),
		new THREE.MeshBasicMaterial( {color: 0xffffff, side: THREE.DoubleSide })
	);
	reticle.position.z = -1000;
	reticle.name = "reticle";
	reticle.frustumCulled = false;
	reticle.renderOrder = 1000;
	reticle.lookAt(camera.position)
	reticle.material.depthTest = false;
	reticle.material.depthWrite = false;
	reticle.material.opacity = 0.025;

	return reticle;
}

export default function Player(props) {
	const { controllers } = useXR();
	const { camera, scene } = useThree();
	const participantObject = scene.getObjectByName("playerOne");
	const [rapierId, setRapierId] = useState("");
	const [contactPoint, setContactPoint] = useState("");
	const [headPoint, setHeadPoint] = useState("");
	const rigidRef = useRef();
	if (!scene.getObjectByName("reticle")){
		camera.add(Reticle());
	}

	if ( controllers.length > 0 ) {
		// var reticle = Reticle();
		scene.remove(scene.getObjectByName("reticle"));
	}


	useFrame(() => {
		if (participantObject) {
			const posY = participantObject.parent.position.y;
			// var posY = participantObject.userData.vrm.firstPerson.humanoid.humanBones.head.position.y;
			// camera.position.setY( posY + 1.5 );
			camera.position.setY(posY + 0.15);
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
		// Check if the avatar is reachable with a 200 response code.
		const fetchProfile = async () => {
			fetch(userData.profileImage)
			.then((response) => {
				if (response.status === 200) {
					const loadedProfile = useLoader(TextureLoader, userData.profileImage);

					playerController.scene.traverse((obj) => {
						if (obj.name === "profile") {
							const newMat = obj.material.clone();
							newMat.map = loadedProfile;
							obj.material = newMat;
							obj.material.map.needsUpdate = true;
						}
					});			
				} 
				return response;
			}).catch(err => {
				return Promise.reject(err)
		   })
		};
			
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
							type={"static"}
							onCollisionEnter={({ manifold, target }) => {
								setRapierId(target.colliderSet.map.data[1]);
								setContactPoint(manifold.solverContactPoint(0));
							}}
							// onCollisionExit={ () => {
							// 	console.log('Collision at world position');
							// }}
						>
							<CapsuleCollider
								position={[0, 0, 0]}
								args={[0.7, 0.7]}
							/>
							<Controls
								id={rapierId}
								point={contactPoint}
								something={rigidRef}
								spawnPoint={props.spawnPoint}
								spawnPointsToAdd={props.spawnPointsToAdd}
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
