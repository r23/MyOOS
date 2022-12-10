import React from "react";
import { useLoader, useThree } from "@react-three/fiber";
import { TextureLoader, DoubleSide } from "three";

/**
 * Renders an image in a three.js scene.
 *
 * @param {Object} threeImage - The props for the image.
 *
 * @return {JSX.Element} The image.
 */
export function ThreeImage(threeImage) {
	const texture2 = useLoader(TextureLoader, threeImage.url);
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
			{threeImage.transparent == "1" ? (
				<meshBasicMaterial
					transparent
					side={DoubleSide}
					map={texture2}
				/>
			) : (
				<meshStandardMaterial side={DoubleSide} map={texture2} />
			)}
		</mesh>
	);
}