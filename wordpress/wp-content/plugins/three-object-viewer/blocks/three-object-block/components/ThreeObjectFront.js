import * as THREE from 'three';
import { AudioListener, Group, Quaternion, VectorKeyframeTrack, QuaternionKeyframeTrack, LoopPingPong, AnimationClip, NumberKeyframeTrack, AnimationMixer, Vector3, BufferGeometry, MeshBasicMaterial, DoubleSide, Mesh, CircleGeometry, sRGBEncoding } from "three";
import React, { Suspense, useRef, useState, useEffect } from 'react';
import { Canvas, useLoader, useFrame, useThree } from '@react-three/fiber';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import { FBXLoader } from "three/examples/jsm/loaders/FBXLoader";
import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader";
import { Physics, RigidBody } from "@react-three/rapier";
import { USDZLoader } from 'three/examples/jsm/loaders/USDZLoader';
import { GLTFGoogleTiltBrushMaterialExtension } from "three-icosa";
import idle from "../../../inc/avatars/friendly.fbx";

import {
	OrthographicCamera,
	OrbitControls,
	useAnimations,
} from '@react-three/drei';
import { GLTFAudioEmitterExtension } from 'three-omi';
import {
	VRCanvas,
	ARCanvas,
	DefaultXRControllers,
	Hands,
} from '@react-three/xr';
import { VRMUtils, VRMSchema, VRMLoaderPlugin, VRMExpressionPresetName } from "@pixiv/three-vrm";
import TeleportTravel from './TeleportTravel';
import { PlaneGeometry } from 'three';

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


/**
 * Load Mixamo animation, convert for three-vrm use, and return it.
 *
 * @param {string} url A url of mixamo animation data
 * @param {VRM} vrm A target VRM
 * @returns {Promise<AnimationClip>} The converted AnimationClip
 */
function loadMixamoAnimation(url, vrm, positionY, positionX, positionZ, scaleX, scaleY, scaleZ, rotationX, rotationY, rotationZ, rotationW) {
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

function SavedObject( props ) {
	const [idleFile, setIdleFile] = useState(props.threeObjectPlugin + idle);
	const [ url, set ] = useState( props.url );
	useEffect( () => {
		setTimeout( () => set( props.url ), 2000 );
	}, [] );
	const [ listener ] = useState( () => new THREE.AudioListener() );

	useThree( ( { camera } ) => {
		camera.add( listener );
	} );

	// USDZ loader.
	if(props.url.split(/[#?]/)[0].split('.').pop().trim() === "usdz") {

		const usdz = useLoader( USDZLoader, url);

        return <primitive scale={[ props.scale, props.scale, props.scale ]} position={[ 0, props.positionY, 0 ]} rotation={[ 0, props.rotationY, 0 ]} object={ usdz } />;
	}

	const gltf = useLoader( GLTFLoader, url, ( loader ) => {
		if(openbrushEnabled === true){
			loader.register(
				(parser) =>
					new GLTFGoogleTiltBrushMaterialExtension(parser, openbrushDirectory)
			);	
		}
		loader.register(
			( parser ) => new GLTFAudioEmitterExtension( parser, listener )
		);
		loader.register( ( parser ) => {

            return new VRMLoaderPlugin( parser );
        } );
	} );

	const { actions } = useAnimations( gltf.animations, gltf.scene );

	const animationList = props.animations ? props.animations.split( ',' ) : '';
	useEffect( () => {
		if ( animationList ) {
			animationList.forEach( ( name ) => {
				if ( Object.keys( actions ).includes( name ) ) {
					actions[ name ].play();
				}
			} );
		}
	}, [] );

	const generator = gltf.asset.generator;
	if (String(generator).includes("Tilt Brush")) {
		gltf.scene.position.set( 0, props.positionY, 0 );
		gltf.scene.rotation.set( 0, props.rotationY, 0 );
		gltf.scene.scale.set( props.scale, props.scale, props.scale );
	
		return (
			<primitive 
			object={gltf.scene} />
		);
	} 

    if(gltf?.userData?.gltfExtensions?.VRM){
		const vrm = gltf.userData.vrm;
		VRMUtils.rotateVRM0(vrm);
		// Disable frustum culling
		vrm.scene.traverse((obj) => {
			obj.frustumCulled = false;
		});

		// scene.add(vrm.scene);

		const currentVrm = vrm;
		const currentMixer = new AnimationMixer(currentVrm.scene);
		console.log(currentVrm)
		// Load animation
		useFrame((state, delta) => {
			if (currentVrm) {
				currentVrm.expressionManager.setValue( VRMExpressionPresetName.Neutral, 0 );
				currentVrm.expressionManager.setValue( VRMExpressionPresetName.Relaxed, 0.8 );
				currentVrm.update(delta);
			}
			if (currentMixer) {
				currentMixer.update(delta);
			}
		});

		// retarget the animations from mixamo to the current vrm
		useEffect(() => {
		if (currentVrm) {
			if ( props.defaultAvatarAnimation ) {
				loadMixamoAnimation(props.defaultAvatarAnimation, currentVrm, 0, props.positionY, props.positionZ, props.scale, props.scale, props.scale).then((clip) => {
					currentMixer.clipAction(clip).play();
					currentMixer.update(clock.getDelta());
				});
			} else {
				loadMixamoAnimation(idleFile, currentVrm, 0, props.positionY, props.positionZ, props.scale, props.scale, props.scale).then((clip) => {
					currentMixer.clipAction(clip).play();
					currentMixer.update(clock.getDelta());
				});
			}
		}
		}, []);
		return (
			<group
				position={[0, props.positionY, 0]}
				rotation={[0, props.rotationY, 0]}
			>
				<primitive object={vrm.scene} />
			</group>
		);
    }
    gltf.scene.position.set( 0, props.positionY, 0 );
    gltf.scene.rotation.set( 0, props.rotationY, 0 );
    gltf.scene.scale.set( props.scale, props.scale, props.scale );
	return <primitive object={ gltf.scene } />;
}

function Floor( props ) {
	return (
		<mesh position={ [ 0, -2, 0 ] } rotation={ [ -Math.PI / 2, 0, 0 ] } { ...props }>
			<PlaneGeometry args={ [ 1000, 1000 ] } attach="geometry" />
			<meshBasicMaterial
				opacity={ 0 }
				transparent={ true }
				attach="material"
			/>
		</mesh>
	);
}

export default function ThreeObjectFront( props ) {
	if ( props.deviceTarget === 'vr' ) {
		return (
			<>
				<VRCanvas
					camera={ { fov: 40, zoom: props.zoom, position: [ 0, 0, 20 ] } }
					shadowMap
					style={ {
						backgroundColor: props.backgroundColor,
						margin: '0 Auto',
						height: '500px',
						width: '90%',
					} }
				>
					<Hands />
					<DefaultXRControllers />
					<ambientLight intensity={ 0.4 } />
					<directionalLight
						intensity={ 0.4 }
						position={ [ 0, 2, 2 ] }
						shadow-mapSize-width={ 2048 }
						shadow-mapSize-height={ 2048 }
						castShadow
					/>			
					<Suspense fallback={ null }>
					<Physics>			
							{ props.threeUrl && (
								<>						
									<TeleportTravel useNormal={ false }>
										<RigidBody type="kinematicPosition">
											<SavedObject
											positionY={ props.positionY }
											rotationY={ props.rotationY }
											url={ props.threeUrl }
											color={ props.backgroundColor }
											hasZoom={ props.hasZoom }
											scale={ props.scale }
											hasTip={ props.hasTip }
											animations={ props.animations }
											threeObjectPlugin={ props.threeObjectPlugin }
											defaultAvatarAnimation={ props.defaultAvatarAnimation }
											/>
										</RigidBody>
									</TeleportTravel>
									<RigidBody>
											<Floor rotation={[-Math.PI / 2, 0, 0]} />
									</RigidBody>
								</>
							) }
					</Physics>
					</Suspense>
					<OrbitControls
						enableZoom={ props.hasZoom === '1' ? true : false }
					/>
				</VRCanvas>
				{ props.hasTip === '1' ? (
					<p className="three-object-block-tip">Click and drag ^</p>
				) : (
					<p></p>
				) }
			</>
		);
	}
	if ( props.deviceTarget === 'ar' ) {
		return (
			<>
				<ARCanvas
          			camera={ { fov: 40, zoom: props.zoom, position: [ 0, 0, 20 ] } }
					shadowMap
					style={ {
						backgroundColor: props.backgroundColor,
						margin: '0 Auto',
						height: '500px',
						width: '90%',
					} }
				>
					<ambientLight intensity={ 0.4 } />
					<directionalLight
						intensity={ 0.4 }
						position={ [ 0, 2, 2 ] }
						shadow-mapSize-width={ 2048 }
						shadow-mapSize-height={ 2048 }
						castShadow
					/>
					<Suspense fallback={ null }>
						{ props.threeUrl && (
							<SavedObject
								positionY={ props.positionY }
								rotationY={ props.rotationY }
								url={ props.threeUrl }
								color={ props.backgroundColor }
								hasZoom={ props.hasZoom }
								scale={ props.scale }
								hasTip={ props.hasTip }
								threeObjectPlugin={ props.threeObjectPlugin }
								animations={ props.animations }
								defaultAvatarAnimation={ props.defaultAvatarAnimation }
							/>
						) }
					</Suspense>
					<OrbitControls
						enableZoom={ props.hasZoom === '1' ? true : false }
					/>
				</ARCanvas>
				{ props.hasTip === '1' ? (
					<p className="three-object-block-tip">Click and drag ^</p>
				) : (
					<p></p>
				) }
			</>
		);
	}
	if ( props.deviceTarget === '2d' ) {
		return (
			<>
				<Canvas
          camera={ { fov: 40, position: [0, 0, 20], zoom: props.zoom} }
					shadowMap
					style={ {
						backgroundColor: props.backgroundColor,
						margin: '0 Auto',
						height: '500px',
						width: '90%',
					} }
				>
					<ambientLight intensity={ 0.4 } />
					<directionalLight
						intensity={ 0.4 }
						position={ [ 0, 2, 2 ] }
						shadow-mapSize-width={ 2048 }
						shadow-mapSize-height={ 2048 }
						castShadow
					/>
					<Suspense fallback={ null }>
						{ props.threeUrl && (
							<SavedObject
								positionY={ props.positionY }
								rotationY={ props.rotationY }
								url={ props.threeUrl }
								color={ props.backgroundColor }
								hasZoom={ props.hasZoom }
								scale={ props.scale }
								hasTip={ props.hasTip }
								threeObjectPlugin={ props.threeObjectPlugin }
								animations={ props.animations }
								defaultAvatarAnimation={ props.defaultAvatarAnimation }
							/>
						) }
					</Suspense>
					<OrbitControls
						enableZoom={ props.hasZoom === '1' ? true : false }
					/>
				</Canvas>
				{ props.hasTip === '1' ? (
					<p className="three-object-block-tip">Click and drag ^</p>
				) : (
					<p></p>
				) }
			</>
		);
	}
}
