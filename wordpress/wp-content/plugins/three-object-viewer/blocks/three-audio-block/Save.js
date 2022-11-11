import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<>
				<div className="three-object-three-app-audio-block">
					<p className="audio-block-url">{attributes.audioUrl}</p>
					<p className="audio-block-scaleX">{attributes.scaleX}</p>
					<p className="audio-block-scaleY">{attributes.scaleY}</p>
					<p className="audio-block-scaleZ">{attributes.scaleZ}</p>
					<p className="audio-block-positionX">
						{attributes.positionX}
					</p>
					<p className="audio-block-positionY">
						{attributes.positionY}
					</p>
					<p className="audio-block-positionZ">
						{attributes.positionZ}
					</p>
					<p className="audio-block-rotationX">
						{attributes.rotationX}
					</p>
					<p className="audio-block-rotationY">
						{attributes.rotationY}
					</p>
					<p className="audio-block-rotationZ">
						{attributes.rotationZ}
					</p>
					<p className="audio-block-aspect-height">
						{attributes.aspectHeight}
					</p>
					<p className="audio-block-aspect-width">
						{attributes.aspectWidth}
					</p>
				</div>
			</>
		</div>
	);
}
