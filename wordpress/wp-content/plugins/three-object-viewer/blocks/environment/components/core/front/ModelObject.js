import React, { useState, useEffect } from "react";
import { useLoader, useThree } from "@react-three/fiber";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader";
import { AudioListener, Vector3, BufferGeometry, MeshBasicMaterial, DoubleSide, Mesh, CircleGeometry, sRGBEncoding } from "three";
import { RigidBody } from "@react-three/rapier";
import {
	useAnimations,
} from "@react-three/drei";
import { GLTFAudioEmitterExtension } from "three-omi";
import { GLTFGoogleTiltBrushMaterialExtension } from "three-icosa";
import { VRMUtils, VRMLoaderPlugin } from "@pixiv/three-vrm";

/**
 * Represents a model object in a virtual reality scene.
 *
 * @param {Object} model - The props for the model object.
 *
 * @return {JSX.Element} The model object.
 */
export function ModelObject(model) {
	const [clicked, setClickEvent] = useState();
	const [url, set] = useState(model.url);
	useEffect(() => {
		setTimeout(() => set(model.url), 2000);
	}, []);
	const [listener] = useState(() => new AudioListener());

	useThree(({ camera }) => {
		camera.add(listener);
	});

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
		if (animationList) {
			animationList.forEach((name) => {
				if (Object.keys(actions).includes(name)) {
					console.log(actions[name].play());
				}
			});
		}
	}, []);

	const generator = gltf.asset.generator;

	// return tilt brush if tilt brush
	if (String(generator).includes("Tilt Brush")) {
		return (
			<primitive
				rotation={[model.rotationX, model.rotationY, model.rotationZ]}
				position={[model.positionX, model.positionY, model.positionZ]}
				scale={[model.scaleX, model.scaleY, model.scaleZ]}
				object={gltf.scene}
			/>
		);
	}

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
	
	if (model.collidable === "1") {
		return (
			<>
				<RigidBody
					type="fixed"
					colliders={audioObject ? "cuboid" : "trimesh"}
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
					scale={[model.scaleX + 0.01, model.scaleY + 0.01, model.scaleZ + 0.01]}
					onCollisionEnter={(manifold, target, other) => {
						setClickEvent(!clicked);
						if(audioObject){
							if (clicked) {
								audioObject.play();
								triangle.material.visible = false;
								circle.material.visible = false;
							} else {
								audioObject.pause();
								triangle.material.visible = true;
								circle.material.visible = true;
							}
						}
					}}	
					// onCollisionEnter={ ( props ) =>(
					// 	// window.location.href = model.destinationUrl
					// 	)
					// }
				>
					<primitive
						object={gltf.scene}
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
				object={gltf.scene}
				// castShadow
				// receiveShadow
				rotation={[model.rotationX, model.rotationY, model.rotationZ]}
				position={[model.positionX, model.positionY, model.positionZ]}
				scale={[model.scaleX, model.scaleY, model.scaleZ]}
			/>
		</>
	);
}