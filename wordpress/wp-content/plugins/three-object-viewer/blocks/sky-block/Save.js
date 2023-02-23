import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<>
				<div className="three-object-three-app-sky-block">
					<p className="sky-block-url">{attributes.skyUrl}</p>
					<p className="sky-block-distance">{attributes.distance}</p>
					<p className="sky-block-rayleigh">{attributes.rayleigh}</p>
					<p className="sky-block-sunPositionX">{attributes.sunPositionX}</p>
					<p className="sky-block-sunPositionY">{attributes.sunPositionY}</p>
					<p className="sky-block-sunPositionZ">{attributes.sunPositionZ}</p>
				</div>
			</>
		</div>
	);
}
