import * as THREE from "three";
import React, { Suspense, useRef, useState, useEffect, useMemo } from "react";
import { Canvas, useLoader, useFrame, useThree } from "@react-three/fiber";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
// import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader";
import {
	PerspectiveCamera,
	OrbitControls,
	useAnimations,
	Html,
	TransformControls,
	Stats,
	Select,
	Text,
	useAspect
} from "@react-three/drei";
import { VRMUtils, VRMLoaderPlugin } from "@pixiv/three-vrm";
import { GLTFAudioEmitterExtension } from "three-omi";
// import { A11y } from "@react-three/a11y";
import { Perf } from "r3f-perf";
// import EditControls from "./EditControls";
import { Resizable } from "re-resizable";
import defaultFont from "../../../inc/fonts/roboto.woff";

function TextObject(text) {
	const textObj = useRef();
	const [isSelected, setIsSelected] = useState();
	const textBlockAttributes = wp.data
		.select("core/block-editor")
		.getBlockAttributes(text.htmlobjectId);
	const TransformController = ({ condition, wrap, children }) =>
		condition ? wrap(children) : children;
	if (text) {
		return (
			<Select
				box
				multiple
				onChange={(e) => {
					e.length !== 0 ? setIsSelected(true) : setIsSelected(false);
				}}
				filter={(items) => items}
			>
				<TransformController
					condition={isSelected}
					wrap={(children) => (
						<TransformControls
							mode={text.transformMode}
							object={textObj}
							size={0.5}
							onObjectChange={(e) => {
								const rot = new THREE.Euler(0, 0, 0, "XYZ");
								const scale = e?.target.worldScale;
								rot.setFromQuaternion(
									e?.target.worldQuaternion
								);
								wp.data
									.dispatch("core/block-editor")
									.updateBlockAttributes(text.htmlobjectId, {
										positionX: e?.target.worldPosition.x,
										positionY: e?.target.worldPosition.y,
										positionZ: e?.target.worldPosition.z,
										rotationX: rot.x,
										rotationY: rot.y,
										rotationZ: rot.z,
										scaleX: scale.x,
										scaleY: scale.y,
										scaleZ: scale.z
									});
							}}
						>
							{children}
						</TransformControls>
					)}
				>
					{textBlockAttributes && (
						<group
							ref={textObj}
							position={[
								textBlockAttributes.positionX,
								textBlockAttributes.positionY,
								textBlockAttributes.positionZ
							]}
							rotation={[
								textBlockAttributes.rotationX,
								textBlockAttributes.rotationY,
								textBlockAttributes.rotationZ
							]}
							scale={[text.scaleX, text.scaleY, text.scaleZ]}
						>
							<Text
								font={(threeObjectPlugin + defaultFont)}
								scale={[4, 4, 4]}
								color={text.textColor}
							>
								{text.textContent}
							</Text>
						</group>
					)}
				</TransformController>
			</Select>
		);
	}
}

function Sky(sky) {
	const skyUrl = sky.src.skyUrl;
	if (skyUrl) {
		const texture_1 = useLoader(THREE.TextureLoader, skyUrl);

		return (
			<mesh
				visible
				position={[0, 0, 0]}
				scale={[200, 200, 200]}
				rotation={[0, 0, 0]}
			>
				<sphereGeometry args={[5, 10, 10]} />
				<meshStandardMaterial side={THREE.DoubleSide} map={texture_1} />
			</mesh>
		);
	}
}

function Spawn(spawn) {
	const spawnObj = useRef();
	const [isSelected, setIsSelected] = useState();
	const spawnBlockAttributes = wp.data
		.select("core/block-editor")
		.getBlockAttributes(spawn.spawnpointID);
	const TransformController = ({ condition, wrap, children }) =>
		condition ? wrap(children) : children;
	if (spawn) {
		return (
			<Select
				box
				multiple
				onChange={(e) => {
					e.length !== 0 ? setIsSelected(true) : setIsSelected(false);
				}}
				filter={(items) => items}
			>
				<TransformController
					condition={isSelected}
					wrap={(children) => (
						<TransformControls
							mode={spawn.transformMode}
							object={spawnObj}
							size={0.5}
							onObjectChange={(e) => {
								const rot = new THREE.Euler(0, 0, 0, "XYZ");
								const scale = e?.target.worldScale;
								rot.setFromQuaternion(
									e?.target.worldQuaternion
								);
								wp.data
									.dispatch("core/block-editor")
									.updateBlockAttributes(spawn.spawnpointID, {
										positionX: e?.target.worldPosition.x,
										positionY: e?.target.worldPosition.y,
										positionZ: e?.target.worldPosition.z,
										rotationX: rot.x,
										rotationY: rot.y,
										rotationZ: rot.z,
										scaleX: scale.x,
										scaleY: scale.y,
										scaleZ: scale.z
									});
							}}
						>
							{children}
						</TransformControls>
					)}
				>
					{spawnBlockAttributes && (
						<mesh
							ref={spawnObj}
							visible
							position={[
								spawnBlockAttributes.positionX,
								spawnBlockAttributes.positionY,
								spawnBlockAttributes.positionZ
							]}
							scale={[1, 1, 1]}
							rotation={[0, 0, 0]}
						>
							<boxGeometry args={[1, 0.2, 1]} />
							<meshStandardMaterial
								side={THREE.DoubleSide}
								color={0xff3399}
							/>
						</mesh>
					)}
				</TransformController>
			</Select>
		);
	}
}

function ImageObject(threeImage) {
	const texture2 = useLoader(THREE.TextureLoader, threeImage.url);
	const imgObj = useRef();
	const [isSelected, setIsSelected] = useState();
	const threeImageBlockAttributes = wp.data
		.select("core/block-editor")
		.getBlockAttributes(threeImage.imageID);
	const TransformController = ({ condition, wrap, children }) =>
		condition ? wrap(children) : children;

	return (
		<Select
			box
			multiple
			onChange={(e) => {
				e.length !== 0 ? setIsSelected(true) : setIsSelected(false);
			}}
			filter={(items) => items}
		>
			<TransformController
				condition={isSelected}
				wrap={(children) => (
					<TransformControls
						mode={threeImage.transformMode}
						object={imgObj}
						size={0.5}
						onObjectChange={(e) => {
							const rot = new THREE.Euler(0, 0, 0, "XYZ");
							const scale = e?.target.worldScale;
							rot.setFromQuaternion(e?.target.worldQuaternion);
							wp.data
								.dispatch("core/block-editor")
								.updateBlockAttributes(threeImage.imageID, {
									positionX: e?.target.worldPosition.x,
									positionY: e?.target.worldPosition.y,
									positionZ: e?.target.worldPosition.z,
									rotationX: rot.x,
									rotationY: rot.y,
									rotationZ: rot.z,
									scaleX: scale.x,
									scaleY: scale.y,
									scaleZ: scale.z
								});
						}}
					>
						{children}
					</TransformControls>
				)}
			>
				{threeImageBlockAttributes && (
					<mesh
						ref={imgObj}
						visible
						position={[
							threeImageBlockAttributes.positionX,
							threeImageBlockAttributes.positionY,
							threeImageBlockAttributes.positionZ
						]}
						scale={[
							threeImageBlockAttributes.scaleX,
							threeImageBlockAttributes.scaleY,
							threeImageBlockAttributes.scaleZ
						]}
						rotation={[
							threeImageBlockAttributes.rotationX,
							threeImageBlockAttributes.rotationY,
							threeImageBlockAttributes.rotationZ
						]}
					>
						<planeGeometry
							args={[
								threeImageBlockAttributes.aspectWidth / 12,
								threeImageBlockAttributes.aspectHeight / 12
							]}
						/>
						{threeImageBlockAttributes.transparent ? (
							<meshBasicMaterial
								transparent
								side={THREE.DoubleSide}
								map={texture2}
							/>
						) : (
							<meshStandardMaterial
								side={THREE.DoubleSide}
								map={texture2}
							/>
						)}
					</mesh>
				)}
			</TransformController>
		</Select>
	);
}

function VideoObject(threeVideo) {
	const clicked = true;
	const [video] = useState(() =>
		Object.assign(document.createElement("video"), {
			src: threeVideo.url,
			crossOrigin: "Anonymous",
			loop: true,
			muted: true
		})
	);
	const videoObj = useRef();
	const [isSelected, setIsSelected] = useState();
	const [threeVideoBlockAttributes, setThreeVideoBlockAttributes] = useState(
		wp.data
			.select("core/block-editor")
			.getBlockAttributes(threeVideo.videoID)
	);
	const TransformController = ({ condition, wrap, children }) =>
		condition ? wrap(children) : children;

	useEffect(() => void (clicked && video.play()), [video, clicked]);

	return (
		<Select
			box
			multiple
			onChange={(e) => {
				e.length !== 0 ? setIsSelected(true) : setIsSelected(false);
			}}
			filter={(items) => items}
		>
			<TransformController
				condition={isSelected}
				wrap={(children) => (
					<TransformControls
						enabled={isSelected}
						mode={
							threeVideo.transformMode
								? threeVideo.transformMode
								: "translate"
						}
						object={videoObj}
						size={0.5}
						onMouseUp={(e) => {
							const rot = new THREE.Euler(0, 0, 0, "XYZ");
							const scale = e?.target.worldScale;
							rot.setFromQuaternion(e?.target.worldQuaternion);
							wp.data
								.dispatch("core/block-editor")
								.updateBlockAttributes(threeVideo.videoID, {
									positionX: e?.target.worldPosition.x,
									positionY: e?.target.worldPosition.y,
									positionZ: e?.target.worldPosition.z,
									rotationX: rot.x,
									rotationY: rot.y,
									rotationZ: rot.z,
									scaleX: scale.x,
									scaleY: scale.y,
									scaleZ: scale.z
								});
							setThreeVideoBlockAttributes(
								wp.data
									.select("core/block-editor")
									.getBlockAttributes(threeVideo.videoID)
							);

							// if (threeVideo.shouldFocus) {
							// 	setFocusPosition([
							// 		e?.target.worldPosition.x,
							// 		e?.target.worldPosition.y,
							// 		e?.target.worldPosition.z
							// 	]);
							// 	camera.position.set(threeVideo.focusPosition);
							// }
						}}
					>
						{children}
					</TransformControls>
				)}
			>
				{threeVideoBlockAttributes && (
					<mesh
						ref={videoObj}
						scale={[
							threeVideoBlockAttributes.scaleX,
							threeVideoBlockAttributes.scaleY,
							threeVideoBlockAttributes.scaleZ
						]}
						position={[
							threeVideoBlockAttributes.positionX,
							threeVideoBlockAttributes.positionY,
							threeVideoBlockAttributes.positionZ
						]}
						rotation={[
							threeVideoBlockAttributes.rotationX,
							threeVideoBlockAttributes.rotationY,
							threeVideoBlockAttributes.rotationZ
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
								threeVideoBlockAttributes.aspectWidth / 12,
								threeVideoBlockAttributes.aspectHeight / 12
							]}
						/>
					</mesh>
				)}
			</TransformController>
		</Select>
	);
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
	const { camera } = useThree();

	const gltf = useLoader(GLTFLoader, model.url, (loader) => {
		if(listener){
			loader.register(
				(parser) => new GLTFAudioEmitterExtension(parser, listener)
			);	
		}
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
			// <A11y role="content" description={model.alt} >
			<primitive object={vrm.scene} />
			// </A11y>
		);
	}
	gltf.scene.rotation.set(0, 0, 0);
	const obj = useRef();
	// const copyGltf = useMemo(() => gltf.scene.clone(), [gltf.scene]);
	const [isSelected, setIsSelected] = useState();
	const [modelBlockAttributes, setModelBlockAttributes] = useState(
		wp.data.select("core/block-editor").getBlockAttributes(model.modelId)
	);
	const TransformController = ({ condition, wrap, children }) =>
		condition ? wrap(children) : children;

	return (
		<>
			<Select
				box
				multiple
				onChange={(e) => {
					e.length !== 0 ? setIsSelected(true) : setIsSelected(false);
				}}
				filter={(items) => items}
			>
				<TransformController
					condition={isSelected}
					wrap={(children) => (
						<TransformControls
							enabled={isSelected}
							mode={
								model.transformMode
									? model.transformMode
									: "translate"
							}
							object={obj}
							size={0.5}
							onMouseUp={(e) => {
								const rot = new THREE.Euler(0, 0, 0, "XYZ");
								const scale = e?.target.worldScale;
								rot.setFromQuaternion(
									e?.target.worldQuaternion
								);
								wp.data
									.dispatch("core/block-editor")
									.updateBlockAttributes(model.modelId, {
										positionX: e?.target.worldPosition.x,
										positionY: e?.target.worldPosition.y,
										positionZ: e?.target.worldPosition.z,
										rotationX: rot.x,
										rotationY: rot.y,
										rotationZ: rot.z,
										scaleX: scale.x,
										scaleY: scale.y,
										scaleZ: scale.z
									});
								setModelBlockAttributes(
									wp.data
										.select("core/block-editor")
										.getBlockAttributes(model.modelId)
								);

								// if (model.shouldFocus) {
								// 	setFocusPosition([
								// 		e?.target.worldPosition.x,
								// 		e?.target.worldPosition.y,
								// 		e?.target.worldPosition.z
								// 	]);
								// 	camera.position.set(model.focusPosition);
								// }
							}}
						>
							{children}
						</TransformControls>
					)}
				>
					{modelBlockAttributes && (
						<group
							ref={obj}
							position={[
								modelBlockAttributes.positionX,
								modelBlockAttributes.positionY,
								modelBlockAttributes.positionZ
							]}
							rotation={[
								modelBlockAttributes.rotationX,
								modelBlockAttributes.rotationY,
								modelBlockAttributes.rotationZ
							]}
							scale={[
								modelBlockAttributes.scaleX,
								modelBlockAttributes.scaleY,
								modelBlockAttributes.scaleZ
							]}
						>
							<primitive object={gltf.scene} />
						</group>
					)}
				</TransformController>
			</Select>
		</>
	);
}

function PortalObject(model) {
	const [isSelected, setIsSelected] = useState();
	const [portalBlockAttributes, setPortalBlockAttributes] = useState(
		wp.data.select("core/block-editor").getBlockAttributes(model.portalID)
	);
	const TransformController = ({ condition, wrap, children }) =>
		condition ? wrap(children) : children;

	const [url, set] = useState(model.url);
	useEffect(() => {
		setTimeout(() => set(model.url), 2000);
	}, []);
	const [listener] = useState(() => new THREE.AudioListener());

	useThree(({ camera }) => {
		camera.add(listener);
	});
	const { camera } = useThree();

	const gltf = useLoader(GLTFLoader, model.url, (loader) => {
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
			// <A11y role="content" description={model.alt} >
			<primitive object={vrm.scene} />
			// </A11y>
		);
	}
	gltf.scene.rotation.set(0, 0, 0);
	const obj = useRef();
	const copyGltf = useMemo(() => gltf.scene.clone(), [gltf.scene]);

	return (
		<>
			<Select
				box
				multiple
				onChange={(e) => {
					e.length !== 0 ? setIsSelected(true) : setIsSelected(false);
				}}
				filter={(items) => items}
			>
				<TransformController
					condition={isSelected}
					wrap={(children) => (
						<TransformControls
							enabled={isSelected}
							mode={
								model.transformMode
									? model.transformMode
									: "translate"
							}
							object={obj}
							size={0.5}
							onMouseUp={(e) => {
								const rot = new THREE.Euler(0, 0, 0, "XYZ");
								const scale = e?.target.worldScale;
								rot.setFromQuaternion(
									e?.target.worldQuaternion
								);
								wp.data
									.dispatch("core/block-editor")
									.updateBlockAttributes(model.portalID, {
										positionX: e?.target.worldPosition.x,
										positionY: e?.target.worldPosition.y,
										positionZ: e?.target.worldPosition.z,
										rotationX: rot.x,
										rotationY: rot.y,
										rotationZ: rot.z,
										scaleX: scale.x,
										scaleY: scale.y,
										scaleZ: scale.z
									});
								setPortalBlockAttributes(
									wp.data
										.select("core/block-editor")
										.getBlockAttributes(model.portalID)
								);

								// if (model.shouldFocus) {
								// 	setFocusPosition([
								// 		e?.target.worldPosition.x,
								// 		e?.target.worldPosition.y,
								// 		e?.target.worldPosition.z
								// 	]);
								// 	camera.position.set(model.focusPosition);
								// }
							}}
						>
							{children}
						</TransformControls>
					)}
				>
					{portalBlockAttributes && (
						<group
							ref={obj}
							position={[
								portalBlockAttributes.positionX,
								portalBlockAttributes.positionY,
								portalBlockAttributes.positionZ
							]}
							rotation={[
								portalBlockAttributes.rotationX,
								portalBlockAttributes.rotationY,
								portalBlockAttributes.rotationZ
							]}
							scale={[
								portalBlockAttributes.scaleX,
								portalBlockAttributes.scaleY,
								portalBlockAttributes.scaleZ
							]}
						>
							<Text
								font={(threeObjectPlugin + defaultFont)}
								scale={[2, 2, 2]}
								color={portalBlockAttributes.labelTextColor}
								maxWidth={1}
								alignX="center"
								textAlign="center"
								position={[
									0 + portalBlockAttributes.labelOffsetX,
									0 + portalBlockAttributes.labelOffsetY,
									0 + portalBlockAttributes.labelOffsetZ
								]}
							>
								{portalBlockAttributes.label +
									": " +
									portalBlockAttributes.destinationUrl}
							</Text>
							<primitive object={copyGltf} />
						</group>
					)}
				</TransformController>
			</Select>
		</>
	);
}

function ThreeObject(props) {
	let skyobject;
	let skyobjectId;

	let spawnpoint;
	let spawnpointID;

	let modelobject;
	let modelID;
	const editorModelsToAdd = [];

	let portalobject;
	let portalID;
	const editorPortalsToAdd = [];

	let imageID;
	const imageElementsToAdd = [];
	let imageobject;

	let videoID;
	let videoobject;
	const videoElementsToAdd = [];

	const editorHtmlToAdd = [];
	let htmlobject;
	let htmlobjectId;

	const currentBlocks = wp.data.select("core/block-editor").getBlocks();
	if (currentBlocks) {
		currentBlocks.forEach((block) => {
			if (block.name === "three-object-viewer/environment") {
				const currentInnerBlocks = block.innerBlocks;
				if (currentInnerBlocks) {
					currentInnerBlocks.forEach((innerBlock) => {
						if (
							innerBlock.name === "three-object-viewer/sky-block"
						) {
							skyobject = innerBlock.attributes;
							skyobjectId = innerBlock.clientId;
						}
						if (
							innerBlock.name ===
							"three-object-viewer/spawn-point-block"
						) {
							spawnpoint = innerBlock.attributes;
							spawnpointID = innerBlock.clientId;
						}
						if (
							innerBlock.name ===
							"three-object-viewer/model-block"
						) {
							modelobject = innerBlock.attributes;
							modelID = innerBlock.clientId;
							const something = [{ modelobject, modelID }];
							editorModelsToAdd.push({ modelobject, modelID });
						}
						if (
							innerBlock.name ===
							"three-object-viewer/three-image-block"
						) {
							imageobject = innerBlock.attributes;
							imageID = innerBlock.clientId;
							const something = [{ imageobject, imageID }];
							imageElementsToAdd.push({ imageobject, imageID });
						}
						if (
							innerBlock.name ===
							"three-object-viewer/three-video-block"
						) {
							videoobject = innerBlock.attributes;
							videoID = innerBlock.clientId;
							const something = [{ videoobject, videoID }];
							videoElementsToAdd.push({ videoobject, videoID });
						}
						if (
							innerBlock.name ===
							"three-object-viewer/three-portal-block"
						) {
							portalobject = innerBlock.attributes;
							portalID = innerBlock.clientId;
							const something = [{ portalobject, portalID }];
							editorPortalsToAdd.push({ portalobject, portalID });
						}
						if (
							innerBlock.name ===
							"three-object-viewer/three-text-block"
						) {
							htmlobject = innerBlock.attributes;
							htmlobjectId = innerBlock.clientId;
							editorHtmlToAdd.push({ htmlobject, htmlobjectId });
						}
					});
				}
			}
		});
	}

	const [url, set] = useState(props.url);
	useEffect(() => {
		setTimeout(() => set(props.url), 2000);
	}, []);
	const [listener] = useState(() => new THREE.AudioListener());

	useThree(({ camera }) => {
		camera.add(listener);
	});

	const gltf = useLoader(GLTFLoader, url, (loader) => {
		// const dracoLoader = new DRACOLoader();
		// dracoLoader.setDecoderPath('https://www.gstatic.com/draco/v1/decoders/');
		// loader.setDRACOLoader(dracoLoader);

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
		vrm.scene.scale.set(props.scale, props.scale, props.scale);
		return <primitive object={vrm.scene} />;
	}
	gltf.scene.position.set(0, props.positionY, 0);
	gltf.scene.rotation.set(0, props.rotationY, 0);
	gltf.scene.scale.set(props.scale, props.scale, props.scale);
	// const copyGltf = useMemo(() => gltf.scene.clone(), [gltf.scene])

	return (
		<>
			{skyobject && <Sky skyobjectId={skyobjectId} src={skyobject} />}
			{spawnpoint && (
				<Spawn
					spawnpointID={spawnpointID}
					positionX={spawnpoint.positionX}
					positionY={spawnpoint.positionY}
					positionZ={spawnpoint.positionZ}
					transformMode={props.transformMode}
					// setFocusPosition={props.setFocusPosition}
					shouldFocus={props.shouldFocus}
				/>
			)}
			{Object.values(editorModelsToAdd).map((model, index) => {
				if (model.modelobject.threeObjectUrl) {
					return (
						<ModelObject
							url={model.modelobject.threeObjectUrl}
							positionX={model.modelobject.positionX}
							positionY={model.modelobject.positionY}
							positionZ={model.modelobject.positionZ}
							scaleX={model.modelobject.scaleX}
							scaleY={model.modelobject.scaleY}
							scaleZ={model.modelobject.scaleZ}
							rotationX={model.modelobject.rotationX}
							rotationY={model.modelobject.rotationY}
							rotationZ={model.modelobject.rotationZ}
							alt={model.modelobject.alt}
							animations={model.modelobject.animations}
							selected={props.selected}
							modelId={model.modelID}
							transformMode={props.transformMode}
							// setFocusPosition={props.setFocusPosition}
							shouldFocus={props.shouldFocus}
						/>
					);
				}
			})}
			{Object.values(editorPortalsToAdd).map((model, index) => {
				if (model.portalobject.threeObjectUrl) {
					return (
						<PortalObject
							url={model.portalobject.threeObjectUrl}
							positionX={model.portalobject.positionX}
							positionY={model.portalobject.positionY}
							positionZ={model.portalobject.positionZ}
							scaleX={model.portalobject.scaleX}
							scaleY={model.portalobject.scaleY}
							scaleZ={model.portalobject.scaleZ}
							rotationX={model.portalobject.rotationX}
							rotationY={model.portalobject.rotationY}
							rotationZ={model.portalobject.rotationZ}
							alt={model.portalobject.alt}
							animations={model.portalobject.animations}
							selected={props.selected}
							portalID={model.portalID}
							transformMode={props.transformMode}
							// setFocusPosition={props.setFocusPosition}
							shouldFocus={props.shouldFocus}
						/>
					);
				}
			})}
			{Object.values(imageElementsToAdd).map((model, index) => {
				if (model.imageobject.imageUrl) {
					return (
						<ImageObject
							url={model.imageobject.imageUrl}
							positionX={model.imageobject.positionX}
							positionY={model.imageobject.positionY}
							positionZ={model.imageobject.positionZ}
							scaleX={model.imageobject.scaleX}
							scaleY={model.imageobject.scaleY}
							scaleZ={model.imageobject.scaleZ}
							rotationX={model.imageobject.rotationX}
							rotationY={model.imageobject.rotationY}
							rotationZ={model.imageobject.rotationZ}
							alt={model.imageobject.alt}
							animations={model.imageobject.animations}
							selected={props.selected}
							imageID={model.imageID}
							aspectHeight={model.imageobject.aspectHeight}
							aspectWidth={model.imageobject.aspectWidth}
							transformMode={props.transformMode}
							// setFocusPosition={props.setFocusPosition}
							shouldFocus={props.shouldFocus}
						/>
					);
				}
			})}
			{Object.values(videoElementsToAdd).map((model, index) => {
				if (model.videoobject.videoUrl) {
					return (
						<VideoObject
							url={model.videoobject.videoUrl}
							positionX={model.videoobject.positionX}
							positionY={model.videoobject.positionY}
							positionZ={model.videoobject.positionZ}
							scaleX={model.videoobject.scaleX}
							scaleY={model.videoobject.scaleY}
							scaleZ={model.videoobject.scaleZ}
							rotationX={model.videoobject.rotationX}
							rotationY={model.videoobject.rotationY}
							rotationZ={model.videoobject.rotationZ}
							selected={props.selected}
							videoID={model.videoID}
							aspectHeight={model.videoobject.aspectHeight}
							aspectWidth={model.videoobject.aspectWidth}
							transformMode={props.transformMode}
							// setFocusPosition={props.setFocusPosition}
							shouldFocus={props.shouldFocus}
						/>
					);
				}
			})}
			{Object.values(editorHtmlToAdd).map((text, index) => {
				return (
					<TextObject
						key={index}
						textContent={text.htmlobject.textContent}
						positionX={text.htmlobject.positionX}
						positionY={text.htmlobject.positionY}
						positionZ={text.htmlobject.positionZ}
						scaleX={text.htmlobject.scaleX}
						scaleY={text.htmlobject.scaleY}
						scaleZ={text.htmlobject.scaleZ}
						rotationX={text.htmlobject.rotationX}
						rotationY={text.htmlobject.rotationY}
						rotationZ={text.htmlobject.rotationZ}
						textColor={text.htmlobject.textColor}
						htmlobjectId={text.htmlobjectId}
						transformMode={props.transformMode}
					/>
				);
			})}
			{/* {modelobject && props.transformMode && modelobject.threeObjectUrl && 
				<ModelObject 
					url={modelobject.threeObjectUrl} 
					positionX={modelobject.positionX} 
					positionY={modelobject.positionY} 
					positionZ={modelobject.positionZ} 
					scaleX={modelobject.scaleX} 
					scaleY={modelobject.scaleY} 
					scaleZ={modelobject.scaleZ} 
					rotationX={modelobject.rotationX} 
					rotationY={modelobject.rotationY} 
					rotationZ={modelobject.rotationZ} 
					alt={modelobject.alt}
					animations={modelobject.animations}
					selected={props.selected}
					modelId={modelID}
					transformMode={props.transformMode}
				/>
			} */}
			<primitive object={gltf.scene} />
		</>
	);
}

export default function ThreeObjectEdit(props) {
	const [transformMode, setTransformMode] = useState("translate");
	const [focusPosition, setFocusPosition] = useState([0, 0, 0]);
	const [shouldFocus, setShouldFocus] = useState(false);
	const onKeyUp = function (event) {
		switch (event.code) {
			case "KeyT":
				setTransformMode("translate");
				break;
			case "KeyR":
				setTransformMode("rotate");
				break;
			case "KeyS":
				setTransformMode("scale");
				break;
			case "KeyF":
				setShouldFocus(true);
				break;
			default:
		}
	};
	document.addEventListener("keyup", onKeyUp);

	return (
		<>
			<Resizable
				defaultSize={{
					height: 550
				}}
				enable={{
					top: false,
					right: false,
					bottom: true,
					left: false,
					topRight: false,
					bottomRight: false,
					bottomLeft: false,
					topLeft: false
				}}
			>
				<Canvas
					name={"maincanvas"}
					camera={{
						fov: 50,
						near: 0.1,
						far: 1000,
						zoom: props.zoom,
						position: [0, 0, 20]
					}}
					shadowMap
					performance={{ min: 0.5 }}
					style={{
						margin: "0 Auto",
						height: "100%",
						width: "100%"
					}}
				>
					{/* <Perf className="stats" /> */}
					<PerspectiveCamera
						fov={50}
						position={[0, 0, 20]}
						makeDefault
						zoom={1}
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
							{/* <EditControls/> */}
							<ThreeObject
								url={props.url}
								positionY={props.positionY}
								rotationY={props.rotationY}
								scale={props.scale}
								animations={props.animations}
								transformMode={transformMode}
								// setFocusPosition={setFocusPosition}
								shouldFocus={shouldFocus}
							/>
						</Suspense>
					)}
					<OrbitControls makeDefault enableZoom={props.selected} />
				</Canvas>
			</Resizable>
		</>
	);
}
