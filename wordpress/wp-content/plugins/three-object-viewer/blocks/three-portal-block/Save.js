import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<>
				<div className="three-object-three-app-three-portal-block">
					<p className="three-portal-block-url">
						{attributes.threeObjectUrl}
					</p>
					<p className="three-portal-block-destination-url">
						{attributes.destinationUrl}
					</p>
					<p className="three-portal-block-scale-x">
						{attributes.scaleX}
					</p>
					<p className="three-portal-block-scale-y">
						{attributes.scaleY}
					</p>
					<p className="three-portal-block-scale-z">
						{attributes.scaleZ}
					</p>
					<p className="three-portal-block-position-x">
						{attributes.positionX}
					</p>
					<p className="three-portal-block-position-y">
						{attributes.positionY}
					</p>
					<p className="three-portal-block-position-z">
						{attributes.positionZ}
					</p>
					<p className="three-portal-block-rotation-x">
						{attributes.rotationX}
					</p>
					<p className="three-portal-block-rotation-y">
						{attributes.rotationY}
					</p>
					<p className="three-portal-block-rotation-z">
						{attributes.rotationZ}
					</p>
					<p className="three-portal-block-animations">
						{attributes.animations}
					</p>
					<p className="three-portal-block-label">
						{attributes.label}
					</p>
					<p className="three-portal-block-label-offset-x">
						{attributes.labelOffsetX}
					</p>
					<p className="three-portal-block-label-offset-y">
						{attributes.labelOffsetY}
					</p>
					<p className="three-portal-block-label-offset-z">
						{attributes.labelOffsetZ}
					</p>
					<p className="three-portal-block-label-text-color">
						{attributes.labelTextColor}
					</p>
				</div>
			</>
		</div>
	);
}
