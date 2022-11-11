import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<>
				<div className="three-object-three-app-three-text-block">
					<p className="three-text-content">
						{attributes.textContent}
					</p>
					<p className="three-text-positionX">
						{attributes.positionX}
					</p>
					<p className="three-text-positionY">
						{attributes.positionY}
					</p>
					<p className="three-text-positionZ">
						{attributes.positionZ}
					</p>
					<p className="three-text-rotationX">
						{attributes.rotationX}
					</p>
					<p className="three-text-rotationY">
						{attributes.rotationY}
					</p>
					<p className="three-text-rotationZ">
						{attributes.rotationZ}
					</p>
					<p className="three-text-scaleX">{attributes.scaleX}</p>
					<p className="three-text-scaleY">{attributes.scaleY}</p>
					<p className="three-text-scaleZ">{attributes.scaleZ}</p>
					<p className="three-text-color">{attributes.textColor}</p>
				</div>
			</>
		</div>
	);
}
