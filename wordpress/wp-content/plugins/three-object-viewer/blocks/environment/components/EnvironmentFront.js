import * as THREE from "three";
import React, { Suspense, useRef, useState, useEffect, useMemo } from "react";
import { useLoader, useThree } from "@react-three/fiber";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader";
import { TextureLoader } from "three/src/loaders/TextureLoader";
// import { RGBELoader } from "three/examples/jsm/loaders/RGBELoader";
import { Physics, RigidBody, Debug } from "@react-three/rapier";
import * as SkeletonUtils from "three/examples/jsm/utils/SkeletonUtils.js";

// import Networking from "./Networking";

import {
	useAnimations,
	Stats,
	Text,
	Billboard,
	Select
} from "@react-three/drei";
// import { A11y } from "@react-three/a11y";
import { GLTFAudioEmitterExtension } from "three-omi";
import { VRCanvas, DefaultXRControllers, Hands } from "@react-three/xr";
import { Perf } from "r3f-perf";
import { VRMUtils, VRMLoaderPlugin } from "@pixiv/three-vrm";
import TeleportTravel from "./TeleportTravel";
import Player from "./Player";
import defaultVRM from "../../../inc/avatars/3ov_default_avatar.vrm";
import { ItemBaseUI } from "@wordpress/components/build/navigation/styles/navigation-styles";

function parseMatrixUri(uri) {
	const SegmentToSigil = {
		u: "@",
		user: "@",
		r: "#",
		room: "#",
		roomid: "!"
	};

	const url = new URL(uri, window.location.href);

	if (url.protocol === "matrix:") {
		const matches = url.pathname.match(/^(\/\/.+\/)?(.+)$/);

		let authority;
		let path;

		if (matches) {
			if (matches.length === 3) {
				authority = matches[1];
				path = matches[2];
			} else if (matches.length === 2) {
				path = matches[1];
			}
		}

		if (!path) {
			throw new Error(`Invalid matrix uri "${uri}": No path provided`);
		}

		const segments = path.split("/");

		if (segments.length !== 2 && segments.length !== 4) {
			throw new Error(
				`Invalid matrix uri "${uri}": Invalid number of segments`
			);
		}

		const sigil1 = SegmentToSigil[segments[0]];

		if (!sigil1) {
			throw new Error(
				`Invalid matrix uri "${uri}": Invalid segment ${segments[0]}`
			);
		}

		if (!segments[1]) {
			throw new Error(`Invalid matrix uri "${uri}": Empty segment`);
		}

		const mxid1 = `${sigil1}${segments[1]}`;

		let mxid2;

		if (segments.length === 4) {
			if (
				(sigil1 === "!" || sigil1 === "#") &&
				(segments[2] === "e" || segments[2] === "event") &&
				segments[3]
			) {
				mxid2 = `$${segments[3]}`;
			} else {
				throw new Error(
					`Invalid matrix uri "${uri}": Invalid segment ${segments[2]}`
				);
			}
		}
		return { protocol: "matrix:", authority, mxid1, mxid2 };
	}

	return url;
}

function Participant(participant) {
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
		VRMUtils.rotateVRM0(playerController);
		const rotationVRM = playerController.scene.rotation.y;
		playerController.scene.rotation.set(0, rotationVRM, 0);
		playerController.scene.scale.set(1, 1, 1);

		const theScene = useThree();

		participant.p2pcf.on("msg", (peer, data) => {
			const finalData = new TextDecoder("utf-8").decode(data);
			const participantData = JSON.parse(finalData);
			const participantObject = theScene.scene.getObjectByName(
				peer.client_id
			);
			if (participantObject) {
				const loadedProfile = useLoader(
					TextureLoader,
					participantData[peer.client_id][2].profileImage
				);
				if (loadedProfile) {
					participantObject.traverse((obj) => {
						if (
							obj.name === "profile" &&
							obj.material.map === null
						) {
							const newMat = obj.material.clone();
							newMat.map = loadedProfile;
							obj.material = newMat;
							obj.material.map.needsUpdate = true;
						}
					});
				}
				participantObject.position.set(
					participantData[peer.client_id][0].position[0],
					participantData[peer.client_id][0].position[1],
					participantData[peer.client_id][0].position[2]
				);
				participantObject.rotation.set(
					participantData[peer.client_id][1].rotation[0],
					participantData[peer.client_id][1].rotation[1],
					participantData[peer.client_id][1].rotation[2]
				);
			}
		});

		// participant.p2pcf.on('peerclose', peer => {
		// 	const participantObject = theScene.scene.getObjectByName(peer.client_id);
		// 	// theScene.scene.remove(participantObject.name);
		// 	theScene.scene.remove(...participantObject.children);
		// 	// removePeerUi(peer.id)
		// })

		const modelClone = SkeletonUtils.clone(playerController.scene);

		return (
			<>
				{playerController && (
					<primitive name={participant.name} object={modelClone} />
				)}
			</>
		);
	}
}

function ModelObject(model) {
	const [url, set] = useState(model.url);
	useEffect(() => {
		setTimeout(() => set(model.url), 2000);
	}, []);
	const [listener] = useState(() => new THREE.AudioListener());

	useThree(({ camera }) => {
		camera.add(listener);
	});

	const gltf = useLoader(GLTFLoader, url, (loader) => {
		loader.register(
			(parser) => new GLTFAudioEmitterExtension(parser, listener)
		);
		loader.register((parser) => {
			return new VRMLoaderPlugin(parser);
		});
	});

	const { actions } = useAnimations(gltf.animations, gltf.scene);
	const animationClips = gltf.animations;
	const animationList = model.animations ? model.animations.split(",") : "";
	useEffect(() => {
		if (animationList) {
			animationList.forEach((name) => {
				if (Object.keys(actions).includes(name)) {
					console.log(actions[name].play());
				}
			});
		}
	}, []);
	if (gltf?.userData?.gltfExtensions?.VRM) {
		const vrm = gltf.userData.vrm;
		vrm.scene.position.set(
			model.positionX,
			model.positionY,
			model.positionZ
		);
		VRMUtils.rotateVRM0(vrm);
		const rotationVRM = vrm.scene.rotation.y + parseFloat(0);
		vrm.scene.rotation.set(0, rotationVRM, 0);
		vrm.scene.scale.set(1, 1, 1);
		vrm.scene.scale.set(model.scaleX, model.scaleY, model.scaleZ);
		return (
			// <A11y role="content" description={model.alt} showAltText >
			<primitive object={vrm.scene} />
			// </A11y>
		);
	}
	// gltf.scene.castShadow = true;
	// enable shadows @todo figure this out
	// gltf.scene.traverse(function (node) {
	// 	if (node.isMesh) {
	// 		node.castShadow = true;
	// 		node.receiveShadow = true;
	// 	}
	// });

	const copyGltf = useMemo(() => gltf.scene.clone(), [gltf.scene]);
	const modelClone = SkeletonUtils.clone(gltf.scene);
	// modelClone.scene.castShadow = true;
	if (model.collidable === "1") {
		return (
			<>
				<RigidBody
					type="fixed"
					colliders={"trimesh"}
					rotation={[
						model.rotationX,
						model.rotationY,
						model.rotationZ
					]}
					position={[
						model.positionX,
						model.positionY,
						model.positionZ
					]}
					scale={[model.scaleX, model.scaleY, model.scaleZ]}
					// onCollisionEnter={ ( props ) =>(
					// 	// window.location.href = model.destinationUrl
					// 	)
					// }
				>
					<primitive
						object={modelClone}
						// castShadow
						// receiveShadow
						rotation={[
							model.rotationX,
							model.rotationY,
							model.rotationZ
						]}
						position={[
							model.positionX,
							model.positionY,
							model.positionZ
						]}
						scale={[model.scaleX, model.scaleY, model.scaleZ]}
					/>
				</RigidBody>
			</>
		);
	}
	return (
		<>
			<primitive
				object={modelClone}
				// castShadow
				// receiveShadow
				rotation={[model.rotationX, model.rotationY, model.rotationZ]}
				position={[model.positionX, model.positionY, model.positionZ]}
				scale={[model.scaleX, model.scaleY, model.scaleZ]}
			/>
		</>
	);
}

function Portal(model) {
	if (model.object) {
		return (
			<>
				<Billboard
					position={[
						model.positionX + 0.01,
						model.positionY + 0.01,
						model.positionZ + 0.01
					]}
					follow={true}
					lockX={false}
					lockY={false}
					lockZ={false} // Lock the rotation on the z axis (default=false)
				>
					<Text
						scale={[2, 2, 2]}
						maxWidth={1}
						alignX="center"
						// rotation={[model.rotationX , model.rotationY, model.rotationZ]}
						// position={[model.positionX, model.positionY, model.positionZ]}
						color="black"
						position={[0, 0, 0]}
					>
						{model.label
							? model.label + ": "
							: "" + model.destinationUrl}
					</Text>
				</Billboard>
				<RigidBody
					type="fixed"
					colliders={"trimesh"}
					onCollisionEnter={() => {
						const url = new URL(
							model.destinationUrl,
							window.location.href
						);
						if (url.protocol === "matrix:") {
							const destination = parseMatrixUri(
								model.destinationUrl
							);
							window.location.href =
								"https://thirdroom.io/world/" +
								destination.mxid1;
						} else {
							window.location.href = model.destinationUrl;
						}
					}}
				>
					<primitive visible={false} object={model.object} />
				</RigidBody>
			</>
		);
	}
	const [url, set] = useState(model.url);

	useEffect(() => {
		setTimeout(() => set(model.url), 2000);
	}, []);
	const [listener] = useState(() => new THREE.AudioListener());

	useThree(({ camera }) => {
		camera.add(listener);
	});

	const gltf = useLoader(GLTFLoader, url, (loader) => {
		loader.register(
			(parser) => new GLTFAudioEmitterExtension(parser, listener)
		);
		loader.register((parser) => {
			return new VRMLoaderPlugin(parser);
		});
	});

	const { actions } = useAnimations(gltf.animations, gltf.scene);

	const animationList = model.animations ? model.animations.split(",") : "";
	useEffect(() => {
		if (animationList) {
			animationList.forEach((name) => {
				if (Object.keys(actions).includes(name)) {
					actions[name].play();
				}
			});
		}
	}, []);
	// gltf.scene.position.set( model.positionX, model.positionY, model.positionZ );
	// gltf.scene.rotation.set( 0, 0, 0 );
	// gltf.scene.scale.set(model.scaleX, model.scaleY, model.scaleZ);
	// gltf.scene.rotation.set(model.rotationX , model.rotationY, model.rotationZ );
	const copyGltf = useMemo(() => gltf.scene.clone(), [gltf.scene]);

	return (
		<>
			<RigidBody
				type="fixed"
				colliders={"cuboid"}
				onCollisionEnter={(props) =>
					(window.location.href = model.destinationUrl)
				}
				rotation={[model.rotationX, model.rotationY, model.rotationZ]}
				position={[model.positionX, model.positionY, model.positionZ]}
				scale={[model.scaleX, model.scaleY, model.scaleZ]}
			>
				<group
					rotation={[
						model.rotationX,
						model.rotationY,
						model.rotationZ
					]}
					position={[
						model.positionX,
						model.positionY,
						model.positionZ
					]}
					scale={[model.scaleX, model.scaleY, model.scaleZ]}
				>
					<Text
						scale={[2, 2, 2]}
						maxWidth={1}
						alignX="center"
						textAlign="center"
						color={model.labelTextColor}
						position={[
							0 + model.labelOffsetX,
							0 + model.labelOffsetY,
							0 + model.labelOffsetZ
						]}
					>
						{model.label + ": " + model.destinationUrl}
					</Text>
					<primitive object={copyGltf} />
				</group>
			</RigidBody>
		</>
	);
}

function Sky(sky) {
	const skyUrl = sky.src[0].querySelector("p.sky-block-url")
		? sky.src[0].querySelector("p.sky-block-url").innerText
		: "";

	const texture1 = useLoader(THREE.TextureLoader, skyUrl);

	return (
		<mesh
			visible
			position={[0, 0, 0]}
			scale={[200, 200, 200]}
			rotation={[0, 0, 0]}
		>
			<sphereGeometry args={[5, 200, 200]} />
			<meshStandardMaterial side={THREE.DoubleSide} map={texture1} />
		</mesh>
	);
}

function ThreeImage(threeImage) {
	const texture2 = useLoader(THREE.TextureLoader, threeImage.url);

	return (
		<mesh
			visible
			position={[
				threeImage.positionX,
				threeImage.positionY,
				threeImage.positionZ
			]}
			scale={[threeImage.scaleX, threeImage.scaleY, threeImage.scaleZ]}
			rotation={[
				threeImage.rotationX,
				threeImage.rotationY,
				threeImage.rotationZ
			]}
		>
			<planeGeometry
				args={[
					threeImage.aspectWidth / 12,
					threeImage.aspectHeight / 12
				]}
			/>
			{threeImage.transparent ? (
				<meshBasicMaterial
					transparent
					side={THREE.DoubleSide}
					map={texture2}
				/>
			) : (
				<meshStandardMaterial side={THREE.DoubleSide} map={texture2} />
			)}
		</mesh>
	);
}

function ThreeVideo(threeVideo) {
	// const pauseImage = useLoader(TextureLoader, "PavingStones092_1K_Color.jpg");
	const play = threeVideo.autoPlay === 0 ? true : false;
	const [clicked, setClickEvent] = useState();
	const [video] = useState(() =>
		Object.assign(document.createElement("video"), {
			src: threeVideo.url,
			crossOrigin: "Anonymous",
			loop: true,
			muted: true
		})
	);

	useEffect(() => void (play && video.play()), [video, play]);

	return (
		<Select
			box
			multiple
			onChange={(e) => {
				if (e.length !== 0) {
					setClickEvent(!clicked);
					if (clicked) {
						video.play();
					} else {
						video.pause();
					}
				}
			}}
			filter={(items) => items}
		>
			<mesh
				scale={[
					threeVideo.scaleX,
					threeVideo.scaleY,
					threeVideo.scaleZ
				]}
				position={[
					threeVideo.positionX,
					threeVideo.positionY,
					threeVideo.positionZ
				]}
				rotation={[
					threeVideo.rotationX,
					threeVideo.rotationY,
					threeVideo.rotationZ
				]}
			>
				<meshBasicMaterial toneMapped={false}>
					<videoTexture
						attach="map"
						args={[video]}
						encoding={THREE.sRGBEncoding}
					/>
				</meshBasicMaterial>
				<planeGeometry
					args={[
						threeVideo.aspectWidth / 12,
						threeVideo.aspectHeight / 12
					]}
				/>
			</mesh>
		</Select>
	);
}

function Floor(props) {
	return (
		<mesh position={[0, 0, 0]} rotation={[-Math.PI / 2, 0, 0]} {...props}>
			<boxBufferGeometry args={[10000, 10000, 1]} attach="geometry" />
			<meshBasicMaterial
				opacity={0}
				transparent={true}
				attach="material"
			/>
		</mesh>
	);
}

function TextObject(model) {
	const htmlObj = useRef();
	return (
		<>
			<group
				position={[model.positionX, model.positionY, model.positionZ]}
				rotation={[model.rotationX, model.rotationY, model.rotationZ]}
				scale={[model.scaleX, model.scaleY, model.scaleZ]}
				ref={htmlObj}
			>
				<Text
					className="content"
					// rotation-y={-Math.PI / 2}
					width={10}
					height={10}
					color={model.textColor}
					transform
				>
					{model.textContent}
				</Text>
			</group>
		</>
	);
}

function Participants(props) {
	const [participants, setParticipant] = useState([]);
	const p2pcf = window.p2pcf;
	if (p2pcf) {
		p2pcf.on("peerconnect", (peer) => {
			console.log("connected peer", peer);
			setParticipant((current) => [...current, peer.client_id]);
		});
	}
	return (
		<>
			{participants &&
				participants.map((item, index) => {
					return (
						<>
							<Participant
								key={index}
								name={item}
								p2pcf={p2pcf}
							/>
						</>
					);
				})}
		</>
	);
}

function SavedObject(props) {
	const meshRef = useRef();
	const [url, set] = useState(props.url);
	useEffect(() => {
		setTimeout(() => set(props.url), 2000);
	}, []);
	const [listener] = useState(() => new THREE.AudioListener());
	const [colliders, setColliders] = useState();
	const [meshes, setMeshes] = useState();
	const [portals, setPortals] = useState();

	useThree(({ camera }) => {
		camera.add(listener);
	});

	const gltf = useLoader(GLTFLoader, url, (loader) => {
		const dracoLoader = new DRACOLoader();
		dracoLoader.setDecoderPath(
			"https://www.gstatic.com/draco/v1/decoders/"
		);
		loader.setDRACOLoader(dracoLoader);

		loader.register(
			(parser) => new GLTFAudioEmitterExtension(parser, listener)
		);

		loader.register((parser) => {
			return new VRMLoaderPlugin(parser);
		});
	});
	const meshesScene = new THREE.Object3D();

	useEffect(() => {
		//OMI_collider logic.
		const childrenToParse = [];
		const collidersToAdd = [];
		const meshesToAdd = [];
		const portalsToAdd = [];
		let omiColliders;

		gltf.scene.scale.set(props.scale, props.scale, props.scale);
		gltf.scene.position.set(
			gltf.scene.position.x,
			props.positionY,
			gltf.scene.position.z
		);
		gltf.scene.rotation.set(
			gltf.scene.rotation.x,
			props.rotationY,
			gltf.scene.rotation.z
		);
		if (gltf.userData.gltfExtensions?.OMI_collider) {
			omiColliders = gltf.userData.gltfExtensions.OMI_collider.colliders;
		}

		gltf.scene.traverse((child) => {
			// @todo figure out shadows
			// if (child.isMesh) {
			// 	child.castShadow = true;
			// 	child.receiveShadow = true;
			// }

			if (child.userData.gltfExtensions?.OMI_collider) {
				childrenToParse.push(child);
				// child.parent.remove(child.name);
			}
			if (child.userData.gltfExtensions?.OMI_link) {
				portalsToAdd.push(child);
			} else {
				meshesToAdd.push(child);
			}
		});

		meshesToAdd.forEach((mesh) => {
			meshesScene.attach(mesh);
		});

		childrenToParse.forEach((child) => {
			const index = child.userData.gltfExtensions.OMI_collider.collider;
			collidersToAdd.push([child, omiColliders[index]]);
			// gltf.scene.remove(child.name);
		});
		setColliders(collidersToAdd);
		setMeshes(meshesScene);
		setPortals(portalsToAdd);
		// End OMI_collider logic.
	}, []);

	const { actions } = useAnimations(gltf.animations, gltf.scene);

	const animationList = props.animations ? props.animations.split(",") : "";
	useEffect(() => {
		if (animationList) {
			animationList.forEach((name) => {
				if (Object.keys(actions).includes(name)) {
					actions[name].play();
				}
			});
		}
	}, []);
	// Always seem to need to log this...
	// console.log(gltf);
	// const loader = new THREE.ObjectLoader();
	// const object = await loader.loadAsync("models/json/lightmap/lightmap.json");
	// scene.add(object);

	return (
		<>
			{meshes && colliders.length > 0 && (
				<primitive
					// rotation={finalRotation}
					castShadow
					receiveShadow
					// position={item.getWorldPosition(pos)}
					object={meshes}
				/>
			)}
			{meshes && colliders.length === 0 && (
				<RigidBody type="fixed" colliders="trimesh">
					<primitive object={meshes} />
				</RigidBody>
			)}
			{portals &&
				portals.map((item, index) => {
					const pos = new THREE.Vector3();
					const quat = new THREE.Quaternion();
					const rotation = new THREE.Euler();
					const position = item.getWorldPosition(pos);
					const quaternion = item.getWorldQuaternion(quat);
					const finalRotation =
						rotation.setFromQuaternion(quaternion);
					return (
						<Portal
							key={index}
							positionX={position.x}
							positionY={position.y}
							positionZ={position.z}
							rotationX={finalRotation.x}
							rotationY={finalRotation.y}
							rotationZ={finalRotation.z}
							object={item.parent}
							label={props.label}
							destinationUrl={
								item.userData.gltfExtensions.OMI_link.uri
							}
						/>
					);
				})}
			{colliders &&
				colliders.map((item, index) => {
					const pos = new THREE.Vector3(); // create once an reuse it
					const quat = new THREE.Quaternion(); // create once an reuse it
					const rotation = new THREE.Euler();
					const quaternion = item[0].getWorldQuaternion(quat);
					const finalRotation =
						rotation.setFromQuaternion(quaternion);
					const worldPosition = item[0].getWorldPosition(pos);
					if (item[1].type === "mesh") {
						return (
							<RigidBody type="fixed" colliders="trimesh">
								<primitive
									rotation={finalRotation}
									position={worldPosition}
									object={item[0]}
								/>
							</RigidBody>
						);
					}
					if (item[1].type === "box") {
						return (
							<RigidBody type="fixed" colliders="cuboid">
								<primitive
									rotation={finalRotation}
									position={worldPosition}
									object={item[0]}
								/>
							</RigidBody>
						);
					}
					if (item[1].type === "capsule") {
						return (
							<RigidBody type="fixed" colliders="hull">
								<primitive
									rotation={finalRotation}
									position={worldPosition}
									object={item[0]}
								/>
							</RigidBody>
						);
					}
					if (item[1].type === "sphere") {
						return (
							<RigidBody type="fixed" colliders="ball">
								<primitive
									rotation={finalRotation}
									position={worldPosition}
									object={item[0]}
								/>
							</RigidBody>
						);
					}
				})}
		</>
	);
}

export default function EnvironmentFront(props) {
	const [loaded, setLoaded] = useState(false);
	if (loaded === true) {
		if (props.deviceTarget === "vr") {
			return (
				<>
					<VRCanvas
						camera={{
							fov: 50,
							zoom: 1,
							far: 2000,
							position: [0, 0, 20]
						}}
						// shadowMap
						// linear={true}
						// shadows={{ type: "PCFSoftShadowMap" }}
						style={{
							backgroundColor: props.backgroundColor,
							margin: "0",
							height: "100vh",
							width: "100%",
							padding: "0"
						}}
					>
						{/* <Perf className="stats" /> */}
						{/* <XRButton className="enter-vr" /> */}
						<Hands />
						<DefaultXRControllers />
						<ambientLight intensity={0.5} />
						<directionalLight
							intensity={0.6}
							position={[0, 2, 2]}
							// shadow-mapSize-width={512}
							// shadow-mapSize-height={512}
							// shadow-camera-far={5000}
							// shadow-camera-fov={15}
							// shadow-camera-near={0.5}
							// shadow-camera-left={-50}
							// shadow-camera-bottom={-50}
							// shadow-camera-right={50}
							// shadow-camera-top={50}
							// shadow-radius={1}
							// shadow-bias={-0.001}
							// castShadow
						/>
						<Suspense fallback={null}>
							<Physics>
								<RigidBody></RigidBody>
								{/* Debug physics */}
								{/* <Debug /> */}
								{props.threeUrl && (
									<>
										<TeleportTravel useNormal={false}>
											<Player
												spawnPoint={props.spawnPoint}
											/>
											<Participants />
											<SavedObject
												positionY={props.positionY}
												rotationY={props.rotationY}
												url={props.threeUrl}
												color={props.backgroundColor}
												hasZoom={props.hasZoom}
												scale={props.scale}
												hasTip={props.hasTip}
												animations={props.animations}
												playerData={props.userData}
											/>
											{Object.values(props.sky).map(
												(item, index) => {
													return (
														<>
															<Sky
																src={props.sky}
															/>
														</>
													);
												}
											)}
											{Object.values(
												props.imagesToAdd
											).map((item, index) => {
												const imagePosX =
													item.querySelector(
														"p.image-block-positionX"
													)
														? item.querySelector(
																"p.image-block-positionX"
														  ).innerText
														: "";

												const imagePosY =
													item.querySelector(
														"p.image-block-positionY"
													)
														? item.querySelector(
																"p.image-block-positionY"
														  ).innerText
														: "";

												const imagePosZ =
													item.querySelector(
														"p.image-block-positionZ"
													)
														? item.querySelector(
																"p.image-block-positionZ"
														  ).innerText
														: "";

												const imageScaleX =
													item.querySelector(
														"p.image-block-scaleX"
													)
														? item.querySelector(
																"p.image-block-scaleX"
														  ).innerText
														: "";

												const imageScaleY =
													item.querySelector(
														"p.image-block-scaleY"
													)
														? item.querySelector(
																"p.image-block-scaleY"
														  ).innerText
														: "";

												const imageScaleZ =
													item.querySelector(
														"p.image-block-scaleZ"
													)
														? item.querySelector(
																"p.image-block-scaleZ"
														  ).innerText
														: "";

												const imageRotationX =
													item.querySelector(
														"p.image-block-rotationX"
													)
														? item.querySelector(
																"p.image-block-rotationX"
														  ).innerText
														: "";

												const imageRotationY =
													item.querySelector(
														"p.image-block-rotationY"
													)
														? item.querySelector(
																"p.image-block-rotationY"
														  ).innerText
														: "";

												const imageRotationZ =
													item.querySelector(
														"p.image-block-rotationZ"
													)
														? item.querySelector(
																"p.image-block-rotationZ"
														  ).innerText
														: "";

												const imageUrl =
													item.querySelector(
														"p.image-block-url"
													)
														? item.querySelector(
																"p.image-block-url"
														  ).innerText
														: "";

												const aspectHeight =
													item.querySelector(
														"p.image-block-aspect-height"
													)
														? item.querySelector(
																"p.image-block-aspect-height"
														  ).innerText
														: "";

												const aspectWidth =
													item.querySelector(
														"p.image-block-aspect-width"
													)
														? item.querySelector(
																"p.image-block-aspect-width"
														  ).innerText
														: "";

												const transparent =
													item.querySelector(
														"p.image-block-transparent"
													)
														? item.querySelector(
																"p.image-block-transparent"
														  ).innerText
														: false;
												return (
													<ThreeImage
														key={index}
														url={imageUrl}
														positionX={imagePosX}
														positionY={imagePosY}
														positionZ={imagePosZ}
														scaleX={imageScaleX}
														scaleY={imageScaleY}
														scaleZ={imageScaleZ}
														rotationX={
															imageRotationX
														}
														rotationY={
															imageRotationY
														}
														rotationZ={
															imageRotationZ
														}
														aspectHeight={
															aspectHeight
														}
														aspectWidth={
															aspectWidth
														}
														transparent={
															transparent
														}
													/>
												);
											})}
											{Object.values(
												props.videosToAdd
											).map((item, index) => {
												const videoPosX =
													item.querySelector(
														"p.video-block-positionX"
													)
														? item.querySelector(
																"p.video-block-positionX"
														  ).innerText
														: "";

												const videoPosY =
													item.querySelector(
														"p.video-block-positionY"
													)
														? item.querySelector(
																"p.video-block-positionY"
														  ).innerText
														: "";

												const videoPosZ =
													item.querySelector(
														"p.video-block-positionZ"
													)
														? item.querySelector(
																"p.video-block-positionZ"
														  ).innerText
														: "";

												const videoScaleX =
													item.querySelector(
														"p.video-block-scaleX"
													)
														? item.querySelector(
																"p.video-block-scaleX"
														  ).innerText
														: "";

												const videoScaleY =
													item.querySelector(
														"p.video-block-scaleY"
													)
														? item.querySelector(
																"p.video-block-scaleY"
														  ).innerText
														: "";

												const videoScaleZ =
													item.querySelector(
														"p.video-block-scaleZ"
													)
														? item.querySelector(
																"p.video-block-scaleZ"
														  ).innerText
														: "";

												const videoRotationX =
													item.querySelector(
														"p.video-block-rotationX"
													)
														? item.querySelector(
																"p.video-block-rotationX"
														  ).innerText
														: "";

												const videoRotationY =
													item.querySelector(
														"p.video-block-rotationY"
													)
														? item.querySelector(
																"p.video-block-rotationY"
														  ).innerText
														: "";

												const videoRotationZ =
													item.querySelector(
														"p.video-block-rotationZ"
													)
														? item.querySelector(
																"p.video-block-rotationZ"
														  ).innerText
														: "";

												const videoUrl =
													item.querySelector(
														"div.video-block-url"
													)
														? item.querySelector(
																"div.video-block-url"
														  ).innerText
														: "";

												const aspectHeight =
													item.querySelector(
														"p.video-block-aspect-height"
													)
														? item.querySelector(
																"p.video-block-aspect-height"
														  ).innerText
														: "";

												const aspectWidth =
													item.querySelector(
														"p.video-block-aspect-width"
													)
														? item.querySelector(
																"p.video-block-aspect-width"
														  ).innerText
														: "";

												const autoPlay =
													item.querySelector(
														"p.video-block-autoplay"
													)
														? item.querySelector(
																"p.video-block-autoplay"
														  ).innerText
														: false;

												return (
													<ThreeVideo
														key={index}
														url={videoUrl}
														positionX={videoPosX}
														positionY={videoPosY}
														positionZ={videoPosZ}
														scaleX={videoScaleX}
														scaleY={videoScaleY}
														scaleZ={videoScaleZ}
														rotationX={
															videoRotationX
														}
														rotationY={
															videoRotationY
														}
														rotationZ={
															videoRotationZ
														}
														aspectHeight={
															aspectHeight
														}
														aspectWidth={
															aspectWidth
														}
														autoPlay={autoPlay}
													/>
												);
											})}

											{Object.values(
												props.modelsToAdd
											).map((model, index) => {
												const modelPosX =
													model.querySelector(
														"p.model-block-position-x"
													)
														? model.querySelector(
																"p.model-block-position-x"
														  ).innerText
														: "";

												const modelPosY =
													model.querySelector(
														"p.model-block-position-y"
													)
														? model.querySelector(
																"p.model-block-position-y"
														  ).innerText
														: "";

												const modelPosZ =
													model.querySelector(
														"p.model-block-position-z"
													)
														? model.querySelector(
																"p.model-block-position-z"
														  ).innerText
														: "";

												const modelScaleX =
													model.querySelector(
														"p.model-block-scale-x"
													)
														? model.querySelector(
																"p.model-block-scale-x"
														  ).innerText
														: "";

												const modelScaleY =
													model.querySelector(
														"p.model-block-scale-y"
													)
														? model.querySelector(
																"p.model-block-scale-y"
														  ).innerText
														: "";

												const modelScaleZ =
													model.querySelector(
														"p.model-block-scale-z"
													)
														? model.querySelector(
																"p.model-block-scale-z"
														  ).innerText
														: "";

												const modelRotationX =
													model.querySelector(
														"p.model-block-rotation-x"
													)
														? model.querySelector(
																"p.model-block-rotation-x"
														  ).innerText
														: "";

												const modelRotationY =
													model.querySelector(
														"p.model-block-rotation-y"
													)
														? model.querySelector(
																"p.model-block-rotation-y"
														  ).innerText
														: "";

												const modelRotationZ =
													model.querySelector(
														"p.model-block-rotation-z"
													)
														? model.querySelector(
																"p.model-block-rotation-z"
														  ).innerText
														: "";

												const url = model.querySelector(
													"p.model-block-url"
												)
													? model.querySelector(
															"p.model-block-url"
													  ).innerText
													: "";

												const animations =
													model.querySelector(
														"p.model-block-animations"
													)
														? model.querySelector(
																"p.model-block-animations"
														  ).innerText
														: "";

												const alt = model.querySelector(
													"p.model-block-alt"
												)
													? model.querySelector(
															"p.model-block-alt"
													  ).innerText
													: "";

												const collidable =
													model.querySelector(
														"p.model-block-collidable"
													)
														? model.querySelector(
																"p.model-block-collidable"
														  ).innerText
														: false;

												return (
													<ModelObject
														key={index}
														url={url}
														positionX={modelPosX}
														positionY={modelPosY}
														positionZ={modelPosZ}
														scaleX={modelScaleX}
														scaleY={modelScaleY}
														scaleZ={modelScaleZ}
														rotationX={
															modelRotationX
														}
														rotationY={
															modelRotationY
														}
														rotationZ={
															modelRotationZ
														}
														alt={alt}
														animations={animations}
														collidable={collidable}
													/>
												);
											})}
											{Object.values(props.htmlToAdd).map(
												(model, index) => {
													const textContent =
														model.querySelector(
															"p.three-text-content"
														)
															? model.querySelector(
																	"p.three-text-content"
															  ).innerText
															: "";
													const rotationX =
														model.querySelector(
															"p.three-text-rotationX"
														)
															? model.querySelector(
																	"p.three-text-rotationX"
															  ).innerText
															: "";
													const rotationY =
														model.querySelector(
															"p.three-text-rotationY"
														)
															? model.querySelector(
																	"p.three-text-rotationY"
															  ).innerText
															: "";
													const rotationZ =
														model.querySelector(
															"p.three-text-rotationZ"
														)
															? model.querySelector(
																	"p.three-text-rotationZ"
															  ).innerText
															: "";
													const positionX =
														model.querySelector(
															"p.three-text-positionX"
														)
															? model.querySelector(
																	"p.three-text-positionX"
															  ).innerText
															: "";
													const positionY =
														model.querySelector(
															"p.three-text-positionY"
														)
															? model.querySelector(
																	"p.three-text-positionY"
															  ).innerText
															: "";
													const positionZ =
														model.querySelector(
															"p.three-text-positionZ"
														)
															? model.querySelector(
																	"p.three-text-positionZ"
															  ).innerText
															: "";
													const scaleX =
														model.querySelector(
															"p.three-text-scaleX"
														)
															? model.querySelector(
																	"p.three-text-scaleX"
															  ).innerText
															: "";
													const scaleY =
														model.querySelector(
															"p.three-text-scaleY"
														)
															? model.querySelector(
																	"p.three-text-scaleY"
															  ).innerText
															: "";
													const scaleZ =
														model.querySelector(
															"p.three-text-scaleZ"
														)
															? model.querySelector(
																	"p.three-text-scaleZ"
															  ).innerText
															: "";

													const textColor =
														model.querySelector(
															"p.three-text-color"
														)
															? model.querySelector(
																	"p.three-text-color"
															  ).innerText
															: "";

													return (
														<TextObject
															key={index}
															textContent={
																textContent
															}
															positionX={
																positionX
															}
															positionY={
																positionY
															}
															positionZ={
																positionZ
															}
															scaleX={scaleX}
															scaleY={scaleY}
															scaleZ={scaleZ}
															textColor={
																textColor
															}
															rotationX={
																rotationX
															}
															rotationY={
																rotationY
															}
															rotationZ={
																rotationZ
															}
															// alt={alt}
															// animations={animations}
														/>
													);
												}
											)}
											{Object.values(
												props.portalsToAdd
											).map((model, index) => {
												const modelPosX =
													model.querySelector(
														"p.three-portal-block-position-x"
													)
														? model.querySelector(
																"p.three-portal-block-position-x"
														  ).innerText
														: "";

												const modelPosY =
													model.querySelector(
														"p.three-portal-block-position-y"
													)
														? model.querySelector(
																"p.three-portal-block-position-y"
														  ).innerText
														: "";

												const modelPosZ =
													model.querySelector(
														"p.three-portal-block-position-z"
													)
														? model.querySelector(
																"p.three-portal-block-position-z"
														  ).innerText
														: "";

												const modelScaleX =
													model.querySelector(
														"p.three-portal-block-scale-x"
													)
														? model.querySelector(
																"p.three-portal-block-scale-x"
														  ).innerText
														: "";

												const modelScaleY =
													model.querySelector(
														"p.three-portal-block-scale-y"
													)
														? model.querySelector(
																"p.three-portal-block-scale-y"
														  ).innerText
														: "";

												const modelScaleZ =
													model.querySelector(
														"p.three-portal-block-scale-z"
													)
														? model.querySelector(
																"p.three-portal-block-scale-z"
														  ).innerText
														: "";

												const modelRotationX =
													model.querySelector(
														"p.three-portal-block-rotation-x"
													)
														? model.querySelector(
																"p.three-portal-block-rotation-x"
														  ).innerText
														: "";

												const modelRotationY =
													model.querySelector(
														"p.three-portal-block-rotation-y"
													)
														? model.querySelector(
																"p.three-portal-block-rotation-y"
														  ).innerText
														: "";

												const modelRotationZ =
													model.querySelector(
														"p.three-portal-block-rotation-z"
													)
														? model.querySelector(
																"p.three-portal-block-rotation-z"
														  ).innerText
														: "";

												const url = model.querySelector(
													"p.three-portal-block-url"
												)
													? model.querySelector(
															"p.three-portal-block-url"
													  ).innerText
													: "";

												const destinationUrl =
													model.querySelector(
														"p.three-portal-block-destination-url"
													)
														? model.querySelector(
																"p.three-portal-block-destination-url"
														  ).innerText
														: "";

												const animations =
													model.querySelector(
														"p.three-portal-block-animations"
													)
														? model.querySelector(
																"p.three-portal-block-animations"
														  ).innerText
														: "";

												const label =
													model.querySelector(
														"p.three-portal-block-label"
													)
														? model.querySelector(
																"p.three-portal-block-label"
														  ).innerText
														: "";

												const labelOffsetX =
													model.querySelector(
														"p.three-portal-block-label-offset-x"
													)
														? model.querySelector(
																"p.three-portal-block-label-offset-x"
														  ).innerText
														: "";

												const labelOffsetY =
													model.querySelector(
														"p.three-portal-block-label-offset-y"
													)
														? model.querySelector(
																"p.three-portal-block-label-offset-y"
														  ).innerText
														: "";

												const labelOffsetZ =
													model.querySelector(
														"p.three-portal-block-label-offset-z"
													)
														? model.querySelector(
																"p.three-portal-block-label-offset-z"
														  ).innerText
														: "";
												const labelTextColor =
													model.querySelector(
														"p.three-portal-block-label-text-color"
													)
														? model.querySelector(
																"p.three-portal-block-label-text-color"
														  ).innerText
														: "";

												return (
													<Portal
														key={index}
														url={url}
														destinationUrl={
															destinationUrl
														}
														positionX={modelPosX}
														positionY={modelPosY}
														animations={animations}
														positionZ={modelPosZ}
														scaleX={modelScaleX}
														scaleY={modelScaleY}
														scaleZ={modelScaleZ}
														rotationX={
															modelRotationX
														}
														rotationY={
															modelRotationY
														}
														rotationZ={
															modelRotationZ
														}
														label={label}
														labelOffsetX={
															labelOffsetX
														}
														labelOffsetY={
															labelOffsetY
														}
														labelOffsetZ={
															labelOffsetZ
														}
														labelTextColor={
															labelTextColor
														}
													/>
												);
											})}
											{/* <RigidBody 
												position={[0, -3, 0]}
												type="fixed"
											>
													<Floor rotation={[-Math.PI / 2, 0, 0]} />
											</RigidBody> */}
										</TeleportTravel>
									</>
								)}
							</Physics>
						</Suspense>
						{/* <OrbitControls
							enableZoom={ true }
						/> */}
					</VRCanvas>
				</>
			);
		}
	} else {
		return (
			<div
				style={{
					backgroundColor: props.backgroundColor,
					backgroundImage: `url(${props.previewImage})`,
					backgroundPosition: "center",
					margin: "0",
					height: "900px",
					width: "100%",
					padding: "0",
					alignItems: "center",
					justifyContent: "center"
				}}
			>
				<div
					style={{
						height: "20px",
						width: "200px",
						position: "relative",
						top: "50%",
						left: "50%",
						padding: "0"
					}}
				>
					<button
						onClick={() => setLoaded(true)}
						style={{
							margin: "0 auto",
							padding: "10px"
						}}
					>
						{" "}
						Load World{" "}
					</button>
				</div>
			</div>
		);
	}
}
