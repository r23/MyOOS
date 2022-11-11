import * as THREE from "three";
import React, { Suspense, useRef, useState, useEffect } from "react";
import { Canvas, useLoader, useFrame, useThree } from "@react-three/fiber";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import {
	OrthographicCamera,
	PerspectiveCamera,
	OrbitControls,
	useAnimations
} from "@react-three/drei";
import { VRM, VRMUtils, VRMSchema, VRMLoaderPlugin } from "@pixiv/three-vrm";
import { GLTFAudioEmitterExtension } from "three-omi";

function ThreeObject(props) {
	const [url, set] = useState(props.url);
	useEffect(() => {
		setTimeout(() => set(props.url), 2000);
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

	if (gltf?.userData?.gltfExtensions?.VRM) {
		const vrm = gltf.userData.vrm;
		vrm.scene.position.set(0, props.positionY, 0);
		VRMUtils.rotateVRM0(vrm);
		const rotationVRM = vrm.scene.rotation.y + parseFloat(props.rotationY);
		vrm.scene.rotation.set(0, rotationVRM, 0);
		// vrm.scene.scale.set( props.scaleX, props.scaleY, props.scaleZ );
		return <primitive object={vrm.scene} />;
	}
	gltf.scene.position.set(0, 0, 0);
	gltf.scene.rotation.set(0, 0, 0);
	gltf.scene.scale.set(1, 1, 1);
	return <primitive object={gltf.scene} />;
}

export default function ModelEdit(props) {
	return (
		<>
			<Canvas
				camera={{ fov: 40, zoom: 1, position: [0, 0, 20] }}
				shadowMap
				style={{
					backgroundColor: props.backgroundColor,
					margin: "0 Auto",
					height: "500px",
					width: "90%"
				}}
			>
				<PerspectiveCamera
					fov={40}
					position={[0, 0, 20]}
					makeDefault
					zoom={props.zoom}
				/>
				<ambientLight intensity={0.5} />
				<directionalLight
					intensity={0.6}
					position={[0, 2, 2]}
					shadow-mapSize-width={2048}
					shadow-mapSize-height={2048}
					castShadow
				/>
				{props.url && (
					<Suspense fallback={null}>
						<ThreeObject
							url={props.url}
							scale={props.scale}
							animations={props.animations}
						/>
					</Suspense>
				)}
				<OrbitControls enableZoom={props.hasZoom} />
			</Canvas>
			{props.hasTip && (
				<p className="three-object-block-tip">Click and drag ^</p>
			)}
		</>
	);
}
