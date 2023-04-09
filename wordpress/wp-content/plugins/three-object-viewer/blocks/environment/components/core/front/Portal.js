import React, { useState, useEffect, useMemo } from "react";
import { useLoader, useThree } from "@react-three/fiber";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader";
import { DRACOLoader } from "three/examples/jsm/loaders/DRACOLoader";
import { AudioListener, Vector3, BufferGeometry, MeshBasicMaterial, DoubleSide, Mesh, CircleGeometry, sRGBEncoding } from "three";
import { RigidBody } from "@react-three/rapier";
import {
	useAnimations,
	Billboard,
	Text
} from "@react-three/drei";
import { GLTFAudioEmitterExtension } from "three-omi";
import { GLTFGoogleTiltBrushMaterialExtension } from "three-icosa";
import { VRMUtils, VRMLoaderPlugin } from "@pixiv/three-vrm";

/**
 * Parses a Matrix URI and returns a matrix ID.
 *
 * @param {string} uri - The Matrix URI to parse.
 * @return {string} The matrix ID extracted from the URI.
 *
 * @throws {Error} If the provided URI is invalid or has an unsupported format.
 */
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


/**
 * Represents a portal in a virtual reality scene.
 *
 * @param {Object} model - The props for the portal.
 *
 * @return {JSX.Element} The portal.
 */
export function Portal(model) {
	if (model.object && model.defaultFont) {
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
						font={model.threeObjectPlugin + model.defaultFont}
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
	const [listener] = useState(() => new AudioListener());

	useThree(({ camera }) => {
		camera.add(listener);
	});

	const gltf = useLoader(GLTFLoader, url, (loader) => {
		const dracoLoader = new DRACOLoader();
		dracoLoader.setDecoderPath( model.threeObjectPluginRoot + "/inc/utils/draco/");
		dracoLoader.setDecoderConfig({type: 'js'}); // (Optional) Override detection of WASM support.
		loader.setDRACOLoader(dracoLoader);

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
					name="portal"
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
						font={threeObjectPlugin + model.defaultFont}
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