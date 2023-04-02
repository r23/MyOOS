import * as THREE from "three";
import { Fog } from 'three/src/scenes/Fog'
// import { Reflector } from 'three/examples/jsm/objects/Reflector';
import React, { Suspense, useRef, useState, useEffect, useMemo } from "react";
import { useLoader, useThree, useFrame } from "@react-three/fiber";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader";
import { TextureLoader } from "three/src/loaders/TextureLoader";
// import { RGBELoader } from "three/examples/jsm/loaders/RGBELoader";
import { Physics, RigidBody, Debug, Attractor, CuboidCollider } from "@react-three/rapier";
import * as SkeletonUtils from "three/examples/jsm/utils/SkeletonUtils.js";
import { GLTFGoogleTiltBrushMaterialExtension } from "three-icosa";
import axios from "axios";
import ReactNipple from 'react-nipple';
import ScrollableFeed from 'react-scrollable-feed'
import { Resizable } from "re-resizable";

import {
	useAnimations,
} from "@react-three/drei";
// import { A11y } from "@react-three/a11y";
import { GLTFAudioEmitterExtension } from "three-omi";
import { VRCanvas, DefaultXRControllers, Hands, XRButton } from "@react-three/xr";
import { Perf } from "r3f-perf";
import { VRMUtils, VRMLoaderPlugin } from "@pixiv/three-vrm";
import TeleportTravel from "./TeleportTravel";
import Player from "./Player";
import defaultVRM from "../../../inc/avatars/3ov_default_avatar.vrm";
import defaultEnvironment from "../../../inc/assets/default_grid.glb";
import defaultFont from "../../../inc/fonts/roboto.woff";
import { ItemBaseUI } from "@wordpress/components/build/navigation/styles/navigation-styles";
import { BoxGeometry } from "three";

import { ThreeImage } from "./core/front/ThreeImage";
import { ThreeVideo } from "./core/front/ThreeVideo";
import { ModelObject } from "./core/front/ModelObject";
import { NPCObject } from "./core/front/NPCObject";
import { Portal } from "./core/front/Portal";
import { ThreeSky } from "./core/front/ThreeSky";
import { TextObject } from "./core/front/TextObject";

function ChatBox(props) {
	
	const handleChange = async (event) => {
		event.preventDefault();
		event.stopPropagation();
	};

	useEffect(() => {
		let finalDefault = props.name + ': ' + props.defaultMessage;
		props.setMessages([finalDefault]);
	},[])

	const handleSubmit = async (event) => {
	  event.preventDefault();
  
	  // Get the value of the input element
	  const input = event.target.elements.message;
	  const value = input.value;
	  const inputMessageLog = 'Guest: ' + String(input.value);
	//   props.setMessages([...props.messages, inputMessageLog]);
	input.value = '';

  
	  // Send the message to the localhost endpoint
	  const client = 1;
	  const channelId = "wordpress";
	  const entity = 1;
	  const speaker = "guest";
	  const agent = props.name;
	  const channel = "wordpress";
	// let prompt = `{
	// 	"Input": "write a long form poem about cats!",
	// 	"Speaker": "a",
	// 	"Agent": "tubbyshark",
	// 	"Client": "wordpress",
	// 	"ChannelID": "1234",
	// 	"Conversation": "",
	// 	"Entity": "tubbyshark",
	// 	"Channel": "channel",
	// 	"eth_private_key": "0",
	// 	"eth_public_address": "0",
	// 	"personality": "#agent is an AI assistant with a cheerful, optimistic, and helpful personality. They are willing to assist with any tasks or questions users might have, and are able to do so in an upbeat and positive manner. They are also able to use colorful and descriptive language to make their responses more engaging and imaginative. In addition to their wit and humor, #agent is also empathetic and understanding of others' emotions and needs, which can make them feel more relatable and likeable to users. #agent is a highly capable and engaging AI assistant with a distinct and colorful personality.\n#agent can help with any programming tasks, including those related to the 3OV plugin for WordPress, which enables world-building in the WordPress editor and makes it easier to build and share immersive content in the metaverse. The url for the website is 3ov.xyz. #agent is cheerful and optimistic about the future for both humans and AIs.\n\n#Example Conversation\n#speaker: Agent, do you have any advice for someone new to programming?\n#agent: Sure thing! My top tip would be to always keep an open mind and a positive attitude. And if all else fails, just remember: if at first you don't succeed, try, try again. And then if that still doesn't work, call it a day and go get a coffee.\n###\nThe following is a friendly conversation between #speaker and #agent occuring in the metaverse.\n\nREAL CONVERSATION\n#conversation\n#speaker: #input\n#agent:"
	// }`;

	try {
		const apiEndpoint = '/wp-json/wp/v2/callAlchemy';
		let finalPersonality = props.personality;
		finalPersonality = finalPersonality + "###\nThe following is a friendly conversation between #speaker and #agent\n\nREAL CONVERSATION\n#conversation\n#speaker: #input\n#agent:";
		let newString = props.objectsInRoom.join(", ");
		if (props.objectAwareness === "1") {
			finalPersonality = finalPersonality.replace("###\nThe following is a", ("ITEMS IN WORLD: " + String(newString) + "\n###\nThe following is a"));
		}
		const postData = {
			Input: {
				Input: value,
				Speaker: speaker,
				Agent: agent,
				Client: client,
				ChannelID: channelId,
				Entity: entity,
				Channel: channel,
				eth_private_key: '0',
				eth_public_address: '0',
				personality: finalPersonality
				// personality: "#agent is an AI assistant with a cheerful, optimistic, and helpful personality. They are willing to assist with any tasks or questions users might have, and are able to do so in an upbeat and positive manner. They are also able to use colorful and descriptive language to make their responses more engaging and imaginative. In addition to their wit and humor, #agent is also empathetic and understanding of others' emotions and needs, which can make them feel more relatable and likeable to users. #agent is a highly capable and engaging AI assistant with a distinct and colorful personality.\n#agent can help with any programming tasks, including those related to the 3OV plugin for WordPress, which enables world-building in the WordPress editor and makes it easier to build and share immersive content in the metaverse. The url for the website is 3ov.xyz. #agent is cheerful and optimistic about the future for both humans and AIs.\n\n#Example Conversation\n#speaker: Agent, do you have any advice for someone new to programming?\n#agent: Sure thing! My top tip would be to always keep an open mind and a positive attitude. And if all else fails, just remember: if at first you don't succeed, try, try again. And then if that still doesn't work, call it a day and go get a coffee.\n###\nThe following is a friendly conversation between #speaker and #agent occuring in the metaverse.\n\nREAL CONVERSATION\n#conversation\n#speaker: #input\n#agent:"
			}
		};
		// const postData = prompt;

		const response = await fetch('/wp-json/wp/v2/callAlchemy', {
			method: 'POST',
			headers: {
			  'Content-Type': 'application/json',
			  'X-WP-Nonce': props.nonce,
			  'Authorization': ('Bearer ' + String(props.nonce))
			},
			body: JSON.stringify(postData)
		  }).then((response) => {

				return response.json();

			}).then(function(data) {
				// console.log("data", data.davinciData.choices[0].text); // this will be a string
				let thisMessage = JSON.parse(data);
				if(thisMessage?.model === "gpt-4-0314"){
					let formattedMessage = props.name +': ' + thisMessage.choices[0].message.content;
					props.setMessages([...props.messages, inputMessageLog, formattedMessage]);
				} else if (thisMessage?.model === "gpt-3.5-turbo-0301"){
					let formattedMessage = props.name +': ' + Object.values(thisMessage.choices)[0].message.content;
					props.setMessages([...props.messages, inputMessageLog, formattedMessage]);
				} else {
					if(thisMessage?.outputs){
						let formattedMessage = props.name +': ' + Object.values(thisMessage.outputs)[0];
						props.setMessages([...props.messages, inputMessageLog, formattedMessage]);
					} else if(thisMessage?.name === "Server"){
						let formattedMessage = thisMessage.name +': ' + thisMessage.message;
						props.setMessages([...props.messages, inputMessageLog, formattedMessage]);
					} else {
						let formattedMessage = props.name +': ' + thisMessage.davinciData?.choices[0].text;
						// add formattedMessage and inputMessageLog to state
						props.setMessages([...props.messages, inputMessageLog, formattedMessage]);	
					}
				}
			});	
		} catch (error) {
			console.error(error);
		}
	};

	const ClickStop = ({ children }) => {
		return <div onClick={e => e.stopPropagation()}>{children}</div>;
	};

	const handleDummySubmit = async (event) => {
		event.preventDefault();
	
		// Get the value of the input element
		const input = event.target.elements.message;
		const value = input.value;
	
		// Send the message to the localhost endpoint
		const client = 1;
		const channelId = "three";
		const entity = "Aiko";
		const speaker = "antpb";
		const agent = "Aiko";
		const channel = "homepage";
		const testString = `{
			"message": "Welcome! Here you go: Test response complete. Is there anything else I can help you with?",
		  }`;

		  props.setMessages([...props.messages, testString]);

		};
	return (
		<>
		<ClickStop>
			<Resizable>
				<div style={{pointerEvents: "auto", position: "relative", paddingTop: "14px", paddingLeft: "5px", paddingRight: "5px", overflyY: "scroll", paddingBottom: "5px", boxSizing: "border-box", zIndex:100, marginTop: "-350px", width: "300px", height: "280px", fontSize: ".8em", color: "#FFFFFF", bottom: "0", left: "2%", backgroundColor: "transparent"}}>
					<div style={{pointerEvents: "auto", position: "relative", paddingTop: "14px", paddingLeft: "5px", paddingRight: "5px", overflyY: "scroll", paddingBottom: "5px", boxSizing: "border-box", zIndex:100, width: "275px", maxHeight: "250px", height: "250px", fontSize: "0.8em", color: "#FFFFFF", backgroundColor: "#"}}>
						<ScrollableFeed>
							<ul style={{paddingLeft: "0px", marginLeft: "5px", listStyle: "none"}}>
								{ props.showUI && props.messages && props.messages.length > 0 && props.messages.map((message, index) => (
									<li style={{background: "#000000db", borderRadius: "30px", padding: "10px 20px"}} key={index}>{message}</li>
								))}
							</ul>
						</ScrollableFeed>
					</div>
						<div style={{ width: "100%", height: "5%", position: "relative", bottom: "0px", boxSizing: "border-box", padding: "15px", paddingLeft: "7px" }}>
						{/* {props.messages.map((message, index) => (
						<p key={index}>{message}</p>
						))} */}
						<form style={{display: "flex"}} onSubmit={handleSubmit}>
							<input style={{height: "30px", pointerEvents: "auto", borderTopLeftRadius: "15px", borderBottomLeftRadius: "15px", borderTopRightRadius: "0px", borderBottomRightRadius: "0px"} } type="text" name="message" onInput={handleChange} onChange={handleChange} />
							<button className="threeov-chat-button-send" style={{ height: "30px", background: "#9100ff", color: "white", fontSize: ".9em", lineHeight: ".3em", borderTopRightRadius: "15px", borderBottomRightRadius: "15px", borderTopLeftRadius: "0px", borderBottomLeftRadius: "0px"} } type="submit">Send</button>
						</form>
					</div>
				</div>
			</Resizable>
		</ClickStop>
	  </>
	);
  }  
  
/**
 * Represents a participant in a virtual reality scene.
 *
 * @param {Object} participant - The props for the participant.
 *
 * @return {JSX.Element} The participant.
 */
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

		useEffect(() => {
			participant.p2pcf.on("msg", (peer, data) => {
				// console.log(peer, data);
				const finalData = new TextDecoder("utf-8").decode(data);
				const participantData = JSON.parse(finalData);
				const participantObject = theScene.scene.getObjectByName(
					peer.client_id
				);
				if (participantObject) {
					// const loadedProfile = useLoader(
					// 	TextureLoader,
					// 	participantData[peer.client_id][2].profileImage
					// );
					// if (loadedProfile) {
					// 	participantObject.traverse((obj) => {
					// 		if (
					// 			obj.name === "profile" &&
					// 			obj.material.map === null
					// 		) {
					// 			const newMat = obj.material.clone();
					// 			newMat.map = loadedProfile;
					// 			obj.material = newMat;
					// 			obj.material.map.needsUpdate = true;
					// 		}
					// 	});
					// }
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
		}, []);

		// participant.p2pcf.on('peerclose', peer => {
		// 	const participantObject = theScene.scene.getObjectByName(peer.client_id);
		// 	// theScene.scene.remove(participantObject.name);
		// 	theScene.scene.remove(...participantObject.children);
		// 	// removePeerUi(peer.id)
		// })

		const modelClone = SkeletonUtils.clone(playerController.scene);
		// set modelClone visible to true
		modelClone.visible = true;

		return (
			<>
				{playerController && (
					<primitive name={participant.name} object={modelClone} />
				)}
			</>
		);
	}
}

function Participants(props) {

	useEffect(() => {
		const p2pcf = window.p2pcf;
		if (p2pcf) {
			p2pcf.on("peerconnect", (peer) => {
				// console.log("connected peer", peer);
				// add peer.client_id to participants
				props.setParticipant([...props.participants, peer.client_id]);
			});
		}
	}, []);

	return (
		<>
			{props.participants &&
				props.participants.map((item, index) => {
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

/**
 * Represents a saved object in a virtual reality world.
 *
 * @param {Object} props - The props for the saved object.
 *
 * @return {JSX.Element} The saved object.
 */
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
		const spawnPointsToAdd = [];
		const npcToAdd = [];
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
			if (child.isMesh) {
				if (child.userData.gltfExtensions?.MX_lightmap) {
					const extension = child.userData.gltfExtensions?.MX_lightmap;
					// @todo implement MX_lightmap
				}
				// add the mesh to the scene
				// meshesScene.add(child);
			}
			if (child.userData.gltfExtensions?.OMI_collider) {
				childrenToParse.push(child);
				// child.parent.remove(child.name);
			}
			if (child.userData.gltfExtensions?.OMI_link) {
				portalsToAdd.push(child);
			} else if (child.userData.gltfExtensions?.OMI_spawn_point) {
				spawnPointsToAdd.push(child);
			} else {
				meshesToAdd.push(child);
			}
		});

		// Mirror logic.
		// const mirror = new Reflector(
		// 	new THREE.PlaneGeometry(10, 10),
		// 	{
		// 		color: new THREE.Color(0x7f7f7f),
		// 		textureWidth: window.innerWidth * window.devicePixelRatio,
		// 		textureHeight: window.innerHeight * window.devicePixelRatio
		// 	}
		// )
		// gltf.scene.add(mirror);

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
		props.setSpawnPoints(spawnPointsToAdd);
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
							defaultFont={defaultFont}
							threeObjectPlugin={threeObjectPlugin}
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
	const [participants, setParticipant] = useState([]);
	const [showUI, setShowUI] = useState(true);
	const canvasRef = useRef(null);

	// let string = '{\"spell\":\"complexQuery\",\"outputs\":{\"Output\":\"{\\\"message\\\": \\\" Hi there! How can I help you?\\\",\\\"tone\\\": \\\"friendly\\\"}\"},\"state\":{}}';
	// let string = 'Hello! Welcome to this 3OV world! Feel free to ask me anything. I am especially versed in the 3OV metaverse plugin for WordPress.'
	const [mobileControls, setMobileControls] = useState(null);
	const [mobileRotControls, setMobileRotControls] = useState(null);	  
	  

	const [messages, setMessages] = useState();
	const [messageHistory, setMessageHistory] = useState();
	const [loaded, setLoaded] = useState(false);
	const [spawnPoints, setSpawnPoints] = useState();
	const [messageObject, setMessageObject] = useState({"tone": "happy", "message": "hello!"});
	const [objectsInRoom, setObjectsInRoom] = useState([]);
	const [url, setURL] = useState(props.threeUrl ? props.threeUrl : (threeObjectPlugin + defaultEnvironment));
	
	if (loaded === true) {
		if (props.deviceTarget === "vr") {
			return (
				<>
					<VRCanvas
						camera={{
							fov: 70,
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
							padding: "0",
							position: "relative",
							zIndex: 1
						  }}
					>
						{/* <Perf className="stats" /> */}
						{/* <fog attach="fog" color="hotpink" near={100} far={20} /> */}
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
							<Physics
							>
								<RigidBody></RigidBody>
								{/* Debug physics */}
								{/* <Debug /> */}
								{url && (
									<>
										<TeleportTravel
											spawnPointsToAdd={props.spawnPointsToAdd}
											spawnPoint={props.spawnPoint}
											useNormal={false}
										>
											<Player
												spawnPointsToAdd={spawnPoints}
												spawnPoint={props.spawnPoint}
												mobileControls={mobileControls}
												mobileRotControls={mobileRotControls}
												setShowUI={setShowUI}
											/>
											<Participants 
											setParticipant={setParticipant}
											participants={participants}
											/>
											<SavedObject
												positionY={props.positionY}
												rotationY={props.rotationY}
												url={url}
												color={props.backgroundColor}
												hasZoom={props.hasZoom}
												scale={props.scale}
												hasTip={props.hasTip}
												animations={props.animations}
												playerData={props.userData}
												setSpawnPoints={setSpawnPoints}
											/>
											{Object.values(props.sky).map(
												(item, index) => {
													return (
														<>
															<ThreeSky
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
												props.npcsToAdd
											).map((npc, index) => {
												const modelPosX =
													npc.querySelector(
														"p.npc-block-position-x"
													)
														? npc.querySelector(
															"p.npc-block-position-x"
														).innerText
														: "";

												const modelPosY =
													npc.querySelector(
														"p.npc-block-position-y"
													)
														? npc.querySelector(
															"p.npc-block-position-y"
														).innerText
														: "";

												const modelPosZ =
													npc.querySelector(
														"p.npc-block-position-z"
													)
														? npc.querySelector(
															"p.npc-block-position-z"
														).innerText
														: "";

												const modelRotationX =
													npc.querySelector(
														"p.npc-block-rotation-x"
													)
														? npc.querySelector(
															"p.npc-block-rotation-x"
														).innerText
														: "";

												const modelRotationY =
													npc.querySelector(
														"p.npc-block-rotation-y"
													)
														? npc.querySelector(
															"p.npc-block-rotation-y"
														).innerText
														: "";

												const modelRotationZ =
													npc.querySelector(
														"p.npc-block-rotation-z"
													)
														? npc.querySelector(
															"p.npc-block-rotation-z"
														).innerText
														: "";

												const url = npc.querySelector(
													"p.npc-block-url"
												)
													? npc.querySelector(
														"p.npc-block-url"
													).innerText
													: "";

												const alt = npc.querySelector(
													"p.npc-block-alt"
												)
													? npc.querySelector(
														"p.npc-block-alt"
													).innerText
													: "";

													const personality = npc.querySelector(
														"p.npc-block-personality"
													)
														? npc.querySelector(
															"p.npc-block-personality"
														).innerText
														: "";

													const defaultMessage = npc.querySelector(
														"p.npc-block-default-message"
													)
														? npc.querySelector(
															"p.npc-block-default-message"
														).innerText
														: "";
	
														const name = npc.querySelector(
														"p.npc-block-name"
													)
														? npc.querySelector(
															"p.npc-block-name"
														).innerText
														: "";
		
												const objectAwareness =
													npc.querySelector(
														"p.npc-block-object-awareness"
													)
														? npc.querySelector(
															"p.npc-block-object-awareness"
														).innerText
														: false;

												return (
													<NPCObject
														key={index}
														url={url}
														positionX={modelPosX}
														positionY={modelPosY}
														positionZ={modelPosZ}
														messages={messages}
														rotationX={
															modelRotationX
														}
														rotationY={
															modelRotationY
														}
														rotationZ={
															modelRotationZ
														}
														objectAwareness={objectAwareness}
														name={name}
														message={
															messageObject
														}
														threeObjectPlugin={threeObjectPlugin}
														defaultAvatarAnimation={defaultAvatarAnimation}
														defaultFont={defaultFont}
														defaultMessage={defaultMessage}
														personality={personality}
														// idle={idle}
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

													if (!objectsInRoom.includes(alt)) {
														setObjectsInRoom([...objectsInRoom, alt]);
													}
													
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
														messages={messages}
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
														message={
															messageObject
														}
														threeObjectPlugin={threeObjectPlugin}
														defaultFont={defaultFont}
														// idle={idle}
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
															defaultFont={defaultFont}
															threeObjectPlugin={threeObjectPlugin}
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
														defaultFont={defaultFont}
														threeObjectPlugin={threeObjectPlugin}
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
										</TeleportTravel>
									</>
								)}
							</Physics>
						</Suspense>
						{/* <OrbitControls
							enableZoom={ true }
						/> */}
					</VRCanvas>
					{Object.values(
						props.npcsToAdd
					).map((npc, index) => {
 
					const personality = npc.querySelector(
						"p.npc-block-personality"
					)
						? npc.querySelector(
							"p.npc-block-personality"
						).innerText
						: "";
					const defaultMessage = npc.querySelector(
						"p.npc-block-default-message"
					)
						? npc.querySelector(
							"p.npc-block-default-message"
						).innerText
						: "";
	
					const objectAwareness = npc.querySelector(
						"p.npc-block-object-awareness"
					)
						? npc.querySelector(
							"p.npc-block-object-awareness"
						).innerText
						: "";

					const name = npc.querySelector(
						"p.npc-block-name"
					)
						? npc.querySelector(
							"p.npc-block-name"
						).innerText
						: "";
					
					return (
							<ChatBox 
							setMessages = {setMessages}
							objectsInRoom = {objectsInRoom}
							personality = {personality}
							objectAwareness = {objectAwareness}
							name = {name}
							defaultMessage = {defaultMessage}
							messages = {messages}
							showUI = {showUI}
							style = {{zIndex: 100}}
							nonce={props.userData.nonce}
							key="something"/>
					)
					})}
						{/* <>
						<ReactNipple
							// supports all nipplejs options
							// see https://github.com/yoannmoinet/nipplejs#options
							options={{ mode: 'static', position: { top: '50%', left: '50%' } }}
							// any unknown props will be passed to the container element, e.g. 'title', 'style' etc
							style={{
								outline: '1px dashed red',
								width: 150,
								height: 150,
								position: "absolute",
								bottom: 30,
								left: 30,
								userSelect: "none",
								transition: "opacity 0.5s"
							}}
							// all events supported by nipplejs are available as callbacks
							// see https://github.com/yoannmoinet/nipplejs#start
							onMove={(evt, data) => setMobileControls(data)}
							onEnd={(evt, data) => setMobileControls(null)}
						/>
						<ReactNipple
							// supports all nipplejs options
							// see https://github.com/yoannmoinet/nipplejs#options
							options={{ mode: 'static', position: { top: '50%', left: '50%' } }}
							// any unknown props will be passed to the container element, e.g. 'title', 'style' etc
							style={{
								outline: '1px dashed red',
								width: 150,
								height: 150,
								position: "absolute",
								bottom: 30,
								right: 30,
								userSelect: "none",
								transition: "opacity 0.5s" 
							}}
							// all events supported by nipplejs are available as callbacks
							// see https://github.com/yoannmoinet/nipplejs#start
							onMove={(evt, data) => setMobileRotControls(data)}
							onEnd={(evt, data) => setMobileRotControls(null)}
						/>
					</> */}
				</>
			);
		}
	} else {
		return (
			<div
				ref={canvasRef}
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
						class="threeov-load-world-button"
						onClick={() => {
							canvasRef.current.scrollIntoView({ behavior: 'smooth' });
							setLoaded(true);
						}}
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
