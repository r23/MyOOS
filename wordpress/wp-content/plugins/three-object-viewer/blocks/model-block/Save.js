import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<>
				<div className="three-object-three-app-model-block">
					<p className="model-block-url">
						{attributes.threeObjectUrl}
					</p>
					<p className="model-block-scale-x">{attributes.scaleX}</p>
					<p className="model-block-scale-y">{attributes.scaleY}</p>
					<p className="model-block-scale-z">{attributes.scaleZ}</p>
					<p className="model-block-position-x">
						{attributes.positionX}
					</p>
					<p className="model-block-position-y">
						{attributes.positionY}
					</p>
					<p className="model-block-position-z">
						{attributes.positionZ}
					</p>
					<p className="model-block-rotation-x">
						{attributes.rotationX}
					</p>
					<p className="model-block-rotation-y">
						{attributes.rotationY}
					</p>
					<p className="model-block-rotation-z">
						{attributes.rotationZ}
					</p>
					<p className="model-block-animations">
						{attributes.animations}
					</p>
					<p className="model-block-collidable">
						{attributes.collidable ? 1 : 0}
					</p>
					<p className="model-block-alt">{attributes.alt}</p>
				</div>
			</>
		</div>
	);
}
