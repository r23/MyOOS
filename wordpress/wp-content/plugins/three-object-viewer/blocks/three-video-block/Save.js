import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<>
				<div className="three-object-three-app-video-block">
					<div className="video-block-url">{attributes.videoUrl}</div>
					<p className="video-block-scaleX">{attributes.scaleX}</p>
					<p className="video-block-scaleY">{attributes.scaleY}</p>
					<p className="video-block-scaleZ">{attributes.scaleZ}</p>
					<p className="video-block-positionX">
						{attributes.positionX}
					</p>
					<p className="video-block-positionY">
						{attributes.positionY}
					</p>
					<p className="video-block-positionZ">
						{attributes.positionZ}
					</p>
					<p className="video-block-rotationX">
						{attributes.rotationX}
					</p>
					<p className="video-block-rotationY">
						{attributes.rotationY}
					</p>
					<p className="video-block-rotationZ">
						{attributes.rotationZ}
					</p>
					<p className="video-block-autoplay">
						{attributes.autoPlay ? 1 : 0}
					</p>
					<p className="video-block-custom-model">
						{attributes.customModel ? 1 : 0}
					</p>
					<div className="video-block-model-url">{attributes.modelUrl}</div>
				</div>
			</>
		</div>
	);
}
