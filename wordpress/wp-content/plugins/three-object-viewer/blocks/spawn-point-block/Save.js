import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<>
				<div className="three-object-three-app-spawn-point-block">
					<p className="spawn-point-block-positionX">
						{attributes.positionX}
					</p>
					<p className="spawn-point-block-positionY">
						{attributes.positionY}
					</p>
					<p className="spawn-point-block-positionZ">
						{attributes.positionZ}
					</p>
					<p className="spawn-point-block-rotationX">
						{attributes.rotationX}
					</p>
					<p className="spawn-point-block-rotationY">
						{attributes.rotationY}
					</p>
					<p className="spawn-point-block-rotationZ">
						{attributes.rotationZ}
					</p>
				</div>
			</>
		</div>
	);
}
