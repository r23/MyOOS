import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<>
				<div className="three-object-three-app-image-block">
					<p className="image-block-url">{attributes.imageUrl}</p>
					<p className="image-block-scaleX">{attributes.scaleX}</p>
					<p className="image-block-scaleY">{attributes.scaleY}</p>
					<p className="image-block-scaleZ">{attributes.scaleZ}</p>
					<p className="image-block-positionX">
						{attributes.positionX}
					</p>
					<p className="image-block-positionY">
						{attributes.positionY}
					</p>
					<p className="image-block-positionZ">
						{attributes.positionZ}
					</p>
					<p className="image-block-rotationX">
						{attributes.rotationX}
					</p>
					<p className="image-block-rotationY">
						{attributes.rotationY}
					</p>
					<p className="image-block-rotationZ">
						{attributes.rotationZ}
					</p>
					<p className="image-block-aspect-height">
						{attributes.aspectHeight}
					</p>
					<p className="image-block-aspect-width">
						{attributes.aspectWidth}
					</p>
					<p className="image-block-transparent">
						{attributes.transparent ? 1 : 0}
					</p>
				</div>
			</>
		</div>
	);
}
