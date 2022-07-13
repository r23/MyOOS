import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	return (
		<div { ...useBlockProps.save() }>
			<>
				<div className="three-object-three-app">
					<p className="three-object-block-device-target">
						{ attributes.deviceTarget }
					</p>
					<p className="three-object-block-url">
						{ attributes.threeObjectUrl }
					</p>
					<p className="three-object-scale">{ attributes.scale }</p>
					<p className="three-object-background-color">
						{ attributes.bg_color }
					</p>
					<p className="three-object-zoom">{ attributes.zoom }</p>
					<p className="three-object-has-zoom">
						{ attributes.hasZoom ? 1 : 0 }
					</p>
					<p className="three-object-has-tip">
						{ attributes.hasTip ? 1 : 0 }
					</p>
					<p className="three-object-position-y">
						{ attributes.positionY }
					</p>
					<p className="three-object-rotation-y">
						{ attributes.rotationY }
					</p>
					<p className="three-object-scale">{ attributes.scale }</p>
					<p className="three-object-animations">
						{ attributes.animations }
					</p>
				</div>
			</>
		</div>
	);
}
