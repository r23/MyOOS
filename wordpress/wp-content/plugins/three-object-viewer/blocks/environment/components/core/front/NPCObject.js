import React, { useState, useEffect } from "react";
import { useFrame, useLoader, useThree } from "@react-three/fiber";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import { FBXLoader } from "three/examples/jsm/loaders/FBXLoader";
import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader";
import { AudioListener, Group, Quaternion, VectorKeyframeTrack, QuaternionKeyframeTrack, LoopPingPong, AnimationClip, NumberKeyframeTrack, AnimationMixer, Vector3, BufferGeometry, MeshBasicMaterial, DoubleSide, Mesh, CircleGeometry, sRGBEncoding } from "three";
import { RigidBody } from "@react-three/rapier";
import {
	useAnimations,
	Text
} from "@react-three/drei";
import { GLTFAudioEmitterExtension } from "three-omi";
import { GLTFGoogleTiltBrushMaterialExtension } from "three-icosa";
import { VRMUtils, VRMSchema, VRMLoaderPlugin, VRMExpressionPresetName, VRMHumanBoneName } from "@pixiv/three-vrm";
import idle from "../../../../../inc/avatars/friendly.fbx";
import friendly from "../../../../../inc/avatars/idle.fbx";
import talking from "../../../../../inc/avatars/talking.fbx";

/**
 * A map from Mixamo rig name to VRM Humanoid bone name
 */
const mixamoVRMRigMap = {
	mixamorigHips: 'hips',
	mixamorigSpine: 'spine',
	mixamorigSpine1: 'chest',
	mixamorigSpine2: 'upperChest',
	mixamorigNeck: 'neck',
	mixamorigHead: 'head',
	mixamorigLeftShoulder: 'leftShoulder',
	mixamorigLeftArm: 'leftUpperArm',
	mixamorigLeftForeArm: 'leftLowerArm',
	mixamorigLeftHand: 'leftHand',
	mixamorigLeftHandThumb1: 'leftThumbMetacarpal',
	mixamorigLeftHandThumb2: 'leftThumbProximal',
	mixamorigLeftHandThumb3: 'leftThumbDistal',
	mixamorigLeftHandIndex1: 'leftIndexProximal',
	mixamorigLeftHandIndex2: 'leftIndexIntermediate',
	mixamorigLeftHandIndex3: 'leftIndexDistal',
	mixamorigLeftHandMiddle1: 'leftMiddleProximal',
	mixamorigLeftHandMiddle2: 'leftMiddleIntermediate',
	mixamorigLeftHandMiddle3: 'leftMiddleDistal',
	mixamorigLeftHandRing1: 'leftRingProximal',
	mixamorigLeftHandRing2: 'leftRingIntermediate',
	mixamorigLeftHandRing3: 'leftRingDistal',
	mixamorigLeftHandPinky1: 'leftLittleProximal',
	mixamorigLeftHandPinky2: 'leftLittleIntermediate',
	mixamorigLeftHandPinky3: 'leftLittleDistal',
	mixamorigRightShoulder: 'rightShoulder',
	mixamorigRightArm: 'rightUpperArm',
	mixamorigRightForeArm: 'rightLowerArm',
	mixamorigRightHand: 'rightHand',
	mixamorigRightHandPinky1: 'rightLittleProximal',
	mixamorigRightHandPinky2: 'rightLittleIntermediate',
	mixamorigRightHandPinky3: 'rightLittleDistal',
	mixamorigRightHandRing1: 'rightRingProximal',
	mixamorigRightHandRing2: 'rightRingIntermediate',
	mixamorigRightHandRing3: 'rightRingDistal',
	mixamorigRightHandMiddle1: 'rightMiddleProximal',
	mixamorigRightHandMiddle2: 'rightMiddleIntermediate',
	mixamorigRightHandMiddle3: 'rightMiddleDistal',
	mixamorigRightHandIndex1: 'rightIndexProximal',
	mixamorigRightHandIndex2: 'rightIndexIntermediate',
	mixamorigRightHandIndex3: 'rightIndexDistal',
	mixamorigRightHandThumb1: 'rightThumbMetacarpal',
	mixamorigRightHandThumb2: 'rightThumbProximal',
	mixamorigRightHandThumb3: 'rightThumbDistal',
	mixamorigLeftUpLeg: 'leftUpperLeg',
	mixamorigLeftLeg: 'leftLowerLeg',
	mixamorigLeftFoot: 'leftFoot',
	mixamorigLeftToeBase: 'leftToes',
	mixamorigRightUpLeg: 'rightUpperLeg',
	mixamorigRightLeg: 'rightLowerLeg',
	mixamorigRightFoot: 'rightFoot',
	mixamorigRightToeBase: 'rightToes',
};

/* global THREE, mixamoVRMRigMap */

/**
 * Load Mixamo animation, convert for three-vrm use, and return it.
 *
 * @param {string} url A url of mixamo animation data
 * @param {VRM} vrm A target VRM
 * @returns {Promise<AnimationClip>} The converted AnimationClip
 */
function loadMixamoAnimation(url, vrm) {
	const loader = new FBXLoader(); // A loader which loads FBX
	return loader.loadAsync(url).then((asset) => {
		const clip = AnimationClip.findByName(asset.animations, 'mixamo.com'); // extract the AnimationClip
		const tracks = []; // KeyframeTracks compatible with VRM will be added here

		const restRotationInverse = new Quaternion();
		const parentRestWorldRotation = new Quaternion();
		const _quatA = new Quaternion();
		const _vec3 = new Vector3();

		// Adjust with reference to hips height.
		const motionHipsHeight = asset.getObjectByName('mixamorigHips').position.y;
		const vrmHipsY = vrm.humanoid?.getNormalizedBoneNode('hips').getWorldPosition(_vec3).y;
		const vrmRootY = vrm.scene.getWorldPosition(_vec3).y;
		const vrmHipsHeight = Math.abs(vrmHipsY - vrmRootY);
		const hipsPositionScale = vrmHipsHeight / motionHipsHeight;

		clip.tracks.forEach((track) => {
			// Convert each tracks for VRM use, and push to `tracks`
			const trackSplitted = track.name.split('.');
			const mixamoRigName = trackSplitted[0];
			const vrmBoneName = mixamoVRMRigMap[mixamoRigName];
			const vrmNodeName = vrm.humanoid?.getNormalizedBoneNode(vrmBoneName)?.name;
			const mixamoRigNode = asset.getObjectByName(mixamoRigName);

			if (vrmNodeName != null) {

				const propertyName = trackSplitted[1];

				// Store rotations of rest-pose.
				mixamoRigNode.getWorldQuaternion(restRotationInverse).invert();
				mixamoRigNode.parent.getWorldQuaternion(parentRestWorldRotation);

				if (track instanceof QuaternionKeyframeTrack) {

					// Retarget rotation of mixamoRig to NormalizedBone.
					for (let i = 0; i < track.values.length; i += 4) {

						const flatQuaternion = track.values.slice(i, i + 4);

						_quatA.fromArray(flatQuaternion);

						_quatA
							.premultiply(parentRestWorldRotation)
							.multiply(restRotationInverse);

						_quatA.toArray(flatQuaternion);

						flatQuaternion.forEach((v, index) => {

							track.values[index + i] = v;

						});

					}

					tracks.push(
						new QuaternionKeyframeTrack(
							`${vrmNodeName}.${propertyName}`,
							track.times,
							track.values.map((v, i) => (vrm.meta?.metaVersion === '0' && i % 2 === 0 ? - v : v)),
						),
					);

				} else if (track instanceof VectorKeyframeTrack) {
					const value = track.values.map((v, i) => (vrm.meta?.metaVersion === '0' && i % 3 !== 1 ? - v : v) * hipsPositionScale);
					tracks.push(new VectorKeyframeTrack(`${vrmNodeName}.${propertyName}`, track.times, value));
				}

			}

		});

		return new AnimationClip('vrmAnimation', clip.duration, tracks);

	});

}

/**
 * Represents a model object in a virtual reality scene.
 *
 * @param {Object} model - The props for the model object.
 *
 * @return {JSX.Element} The model object.
 */
export function NPCObject(model) {
	const [idleFile, setIdleFile] = useState(model.threeObjectPlugin + idle);
	const [clicked, setClickEvent] = useState();
	const [activeMessage, setActiveMessage] = useState([]);
	const [headPositionY, setHeadPositionY] = useState([]);
	const [url, set] = useState(model.url);
	useEffect(() => {
		setTimeout(() => set(model.url), 2000);
	}, []);	

	// useEffect(() => {
	// 	if (activeMessage?.tone){
	// 		if ( activeMessage.tone === "neutral" || activeMessage.tone === "idle" ){
	// 			currentVrm.expressionManager.setValue( VRMExpressionPresetName.Happy, 0 );
	// 			currentVrm.update(clock.getDelta());
	// 		}
	// 		else if ( activeMessage.tone === "confused" ){
	// 			currentVrm.expressionManager.setValue( VRMExpressionPresetName.Surprised, 1 );
	// 			currentVrm.update(clock.getDelta());
		
	// 		} else if ( activeMessage.tone === "friendly" ){
	// 			currentVrm.expressionManager.setValue( VRMExpressionPresetName.Happy, 1 );
	// 			currentVrm.update(clock.getDelta());
	// 		} else if ( activeMessage.tone === "angry" ){
	// 			currentVrm.expressionManager.setValue( VRMExpressionPresetName.Angry, 1 );
	// 			currentVrm.update(clock.getDelta());
		
	// 		}	
	// 	}
	// 	// create variable that converts activeMessage to json
	// }, [activeMessage]);

	const [listener] = useState(() => new AudioListener());
	const { scene, clock } = useThree();
	useThree(({ camera }) => {
		camera.add(listener);
	});
	// vrm helpers
	// const helperRoot = new Group();
	// helperRoot.renderOrder = 10000;
	// scene.add(helperRoot);

	const gltf = useLoader(GLTFLoader, url, (loader) => {
		// const dracoLoader = new DRACOLoader();
		// dracoLoader.setDecoderPath(
		// 	"https://www.gstatic.com/draco/v1/decoders/"
		// );
		// loader.setDRACOLoader(dracoLoader);

		loader.register(
			(parser) => new GLTFAudioEmitterExtension(parser, listener)
		);
		if (openbrushEnabled === true) {
			loader.register(
				(parser) =>
					new GLTFGoogleTiltBrushMaterialExtension(
						parser,
						openbrushDirectory
					)
			);
		}
		loader.register((parser) => {
			return new VRMLoaderPlugin(parser);
		});
	});

	const audioObject = gltf.scene.getObjectByProperty('type', 'Audio');

	const { actions } = useAnimations(gltf.animations, gltf.scene);
	const animationClips = gltf.animations;
	const animationList = model.animations ? model.animations.split(",") : "";


	useEffect(() => {
		// console.log("allmessages", model)

		setActiveMessage(model.messages[model.messages.length - 1]);
		// console.log("activemessage", activeMessage)
	}, [model.messages]);

	useEffect(() => {
		if (animationList) {
			animationList.forEach((name) => {
				if (Object.keys(actions).includes(name)) {
					console.log(actions[name].play());
				}
			});
		}
	}, []);

	const generator = gltf.asset.generator;

	if (gltf?.userData?.gltfExtensions?.VRM) {	
		const vrm = gltf.userData.vrm;
		VRMUtils.rotateVRM0(vrm);
		// Disable frustum culling
		vrm.scene.traverse((obj) => {
			obj.frustumCulled = false;
		});
		vrm.scene.name = "assistant";

		// scene.add(vrm.scene);


		const currentVrm = vrm;
		const currentMixer = new AnimationMixer(currentVrm.scene);

		useEffect(() => {
			// if (currentVrm) {
			// 	setHeadPositionY(currentVrm.humanoid.getRawBoneNode(VRMHumanBoneName.Head).position.y);
			// }
			if (currentVrm) {
				let head = currentVrm.humanoid.getRawBoneNode(VRMHumanBoneName.Head);
				let worldPos = new Vector3();
				head.getWorldPosition(worldPos);
				setHeadPositionY(worldPos.y);
			}
		}, [currentVrm]);

		// Load animation
		useFrame((state, delta) => {
			if (currentVrm) {

				let outputJson;
				if (activeMessage) {
					// messageObject = JSON.parse(activeMessage);
					// const outputString = messageObject.outputs.Output;
					try {
						const outputJSON = JSON.parse(activeMessage);
						console.log(outputJSON);
					} catch (e) {
						const outputJSON = JSON.parse("null");
					}

					currentVrm.expressionManager.setValue( VRMExpressionPresetName.Neutral, 0 );
					currentVrm.expressionManager.setValue( VRMExpressionPresetName.Relaxed, 0.8 );
						currentVrm.update(clock.getDelta());
			
					// if(outputJSON.tone){
					// 	//convert outputJSON.tone to lowercase
					// 	outputJSON.tone = outputJSON.tone.toLowerCase();

					// 	// Extract the Output parameter
					// 	if(outputJSON.tone.toLowerCase() === "neutral" ){
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Surprised, 0 );
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Happy, 0 );
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Angry, 0 );
					// 	} else if (outputJSON.tone.toLowerCase() === "confused" ){
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Surprised, 1 );
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Happy, 1 );
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Angry, 0 );
					// 	} else if (outputJSON.tone.toLowerCase() === "friendly" ){
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Surprised, 0 );
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Happy, 1 );
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Angry, 0 );
					// 	} else if (outputJSON.tone.toLowerCase() === "angry" ){
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Surprised, 0 );
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Happy, 0 );
					// 		currentVrm.expressionManager.setValue( VRMExpressionPresetName.Angry, 1 );
					// 	}
					// }
				}
				currentVrm.update(delta);
			}
			if (currentMixer) {
				currentMixer.update(delta);
			}
		});

		// retarget the animations from mixamo to the current vrm 
		if (model.defaultAvatarAnimation){
			loadMixamoAnimation(model.defaultAvatarAnimation, currentVrm).then((clip) => {
				currentMixer.clipAction(clip).play();
				currentMixer.update(clock.getDelta());
			});	
		} else {
			loadMixamoAnimation(idleFile, currentVrm).then((clip) => {
				currentMixer.clipAction(clip).play();
				currentMixer.update(clock.getDelta());
			});	
		}

		let testObject;
		let outputJSON;
		if (activeMessage && activeMessage?.length > 0) {
			testObject = activeMessage;
			const outputString = testObject;
			outputJSON = outputString;
			// outputJSON = outputString;

			// Extract the Output parameter
			// console.log("that obj", outputJSON);

		}

		return (
			<group
				position={[model.positionX, model.positionY, model.positionZ]}
				rotation={[model.rotationX, model.rotationY, model.rotationZ]}
			>
				<Text
					font={model.threeObjectPlugin + model.defaultFont}
					position={[0.6, headPositionY, 0]}
					className="content"
					scale={[0.5, 0.5, 0.5]}
					// rotation-y={-Math.PI / 2}
					width={0.1}
					maxWidth={1}
					wrap={0.1}
					height={0.1}
					color={0xffffff}
					transform
				>
					{outputJSON && String(outputJSON)}
				</Text>
				<mesh position={[0.6, headPositionY, -0.01]}>
					<planeGeometry attach="geometry" args={[0.65, 1.5]} />
					<meshBasicMaterial attach="material" color={0x000000} opacity={0.5}	transparent={ true } />
				</mesh>
				<primitive object={vrm.scene} />
			</group>
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

	// @todo figure out how to clone gltf proper with extensions and animations
	// const copyGltf = useMemo(() => gltf.scene.clone(), [gltf.scene]);
	// const modelClone = SkeletonUtils.clone(gltf.scene);
	// modelClone.scene.castShadow = true;

	//audioObject
	// Add a triangle mesh on top of the video
	const [triangle] = useState(() => {
		const points = [];
		points.push(
			new Vector3(0, -3, 0),
			new Vector3(0, 3, 0),
			new Vector3(4, 0, 0)
		);
		const geometry = new BufferGeometry().setFromPoints(points);
		const material = new MeshBasicMaterial({
			color: 0x00000,
			side: DoubleSide
		});
		const triangle = new Mesh(geometry, material);
		return triangle;
	});

	const [circle] = useState(() => {
		const geometryCircle = new CircleGeometry(5, 32);
		const materialCircle = new MeshBasicMaterial({
			color: 0xfffff,
			side: DoubleSide
		});
		const circle = new Mesh(geometryCircle, materialCircle);
		return circle;
	});

	let outputJSON;
	let testObject;
	if (activeMessage && activeMessage?.length > 0) {
		testObject = activeMessage;
		const outputString = testObject;
		outputJSON = outputString;
		// outputJSON = outputString;

		// Extract the Output parameter
		// console.log("that obj", outputJSON);
	}

	return (
		<>
			<group
				position={[model.positionX, model.positionY, model.positionZ]}
				rotation={[model.rotationX, model.rotationY, model.rotationZ]}
			>
				<Text
					font={model.threeObjectPlugin + model.defaultFont}
					position={[0.6, 0.9, 0]}
					className="content"
					scale={[0.5, 0.5, 0.5]}
					// rotation-y={-Math.PI / 2}
					width={0.1}
					maxWidth={1}
					wrap={0.1}
					height={0.1}
					color={0xffffff}
					transform
				>
					{outputJSON && String(outputJSON)}
					{/* {outputJSON && ("Tone: " + String(outputJSON.tone))} */}
				</Text>
				<mesh position={[0.6, 0.9, -0.01]}>
					<planeGeometry attach="geometry" args={[0.65, 1.5]} />
					<meshBasicMaterial attach="material" color={0x000000} opacity={0.5}	transparent={ true } />
				</mesh>
				<primitive object={gltf.scene} />
			</group>
		</>
	);
}