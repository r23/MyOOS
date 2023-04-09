import React, { useState, useEffect } from "react";
import { useLoader, useThree } from "@react-three/fiber";
import { VideoTexture, Vector3, BufferGeometry, MeshBasicMaterial, DoubleSide, Mesh, CircleGeometry, sRGBEncoding } from "three";
import { RigidBody } from "@react-three/rapier";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import { VRMUtils, VRMLoaderPlugin } from "@pixiv/three-vrm";
import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader";

/**
 * Renders a video in a three.js scene.
 *
 * @param {Object} threeVideo - The props for the video.
 *
 * @return {JSX.Element} The video.
 */
export function ThreeVideo(threeVideo) {
	const play = threeVideo.autoPlay === "1" ? true : false;
	const { scene } = useThree();
	const [clicked, setClickEvent] = useState();
	const [screen, setScreen] = useState(null);
	const [screenParent, setScreenParent] = useState(null);

	const [video] = useState(() =>
		Object.assign(document.createElement("video"), {
			src: threeVideo.url,
			crossOrigin: "Anonymous",
			loop: true,
			muted: true
		})
	);
		const gltf = (threeVideo.customModel === "1") ? useLoader(GLTFLoader, threeVideo.modelUrl, (loader) => {
			const dracoLoader = new DRACOLoader();
			dracoLoader.setDecoderPath( threeVideo.threeObjectPluginRoot + "/inc/utils/draco/");
			dracoLoader.setDecoderConfig({type: 'js'}); // (Optional) Override detection of WASM support.
			loader.setDRACOLoader(dracoLoader);
	
			loader.register((parser) => {
			return new VRMLoaderPlugin(parser);
			});
		}) : null;
	useEffect(() => {
		if (threeVideo.customModel === "1") {
			if (gltf.scene) {
				let foundScreen;
				gltf.scene.traverse((child) => {
					if (child.name === "screen") {
					  foundScreen = child;
					}
				});
				if (foundScreen) {
					setScreen(foundScreen);
					setScreenParent(foundScreen.parent);
					// Update screen's material with video texture
					const videoTexture = new VideoTexture(video);
					videoTexture.encoding = sRGBEncoding;
					const material = new MeshBasicMaterial({ map: videoTexture, toneMapped: false });
					foundScreen.material = material;
				}
			}

		}

	}, [gltf]);

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

	useEffect(() => {
		if (play) {
			triangle.material.visible = false;
			circle.material.visible = false;
			video.play();
		} else {
			triangle.material.visible = true;
			circle.material.visible = true;
		}
	}, [video, play]);

	return (
		// <Select
		// 	box
		// 	multiple
		// 	onChange={(e) => {
		// 		if (e.length !== 0) {
		// 			setClickEvent(!clicked);
		// 			if (clicked) {
		// 				video.play();
		// 				triangle.material.visible = false;
		// 				circle.material.visible = false;
		// 			} else {
		// 				video.pause();
		// 				triangle.material.visible = true;
		// 				circle.material.visible = true;
		// 			}
		// 		}
		// 	}}
		// 	filter={(items) => items}
		// >
		<group
			name="video"
			scale={[threeVideo.scaleX, threeVideo.scaleY, threeVideo.scaleZ]}
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
			{threeVideo.customModel === "1" && gltf ? (
						<primitive object={gltf.scene} />
						) : (
			<RigidBody
				type="fixed"
				colliders={"cuboid"}
				ccd={true}
				onCollisionExit={(manifold, target, other) => {
					setClickEvent(!clicked);
					if (clicked) {
						video.play();
						triangle.material.visible = false;
						circle.material.visible = false;
					} else {
						video.pause();
						triangle.material.visible = true;
						circle.material.visible = true;
					}
				}}
			>
				<object3D>
						<mesh>
							<meshBasicMaterial toneMapped={false}>
								<videoTexture
									attach="map"
									args={[video]}
									encoding={sRGBEncoding}
								/>
							</meshBasicMaterial>
							<planeGeometry
								args={[
									threeVideo.aspectWidth / 12,
									threeVideo.aspectHeight / 12
								]}
							/>
						</mesh>
				</object3D>
			</RigidBody>)}
			<primitive position={[-1.5, 0, 0.1]} object={triangle} />
			<primitive position={[0, 0, 0.05]} object={circle} />
		</group>
		// </Select>
	); 
}