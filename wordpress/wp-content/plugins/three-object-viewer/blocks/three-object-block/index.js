import { registerBlockType } from '@wordpress/blocks';
import Edit from './Edit';
import Save from './Save';
import { useBlockProps } from '@wordpress/block-editor';

const icon = (
	<svg
		class="custom-icon custom-icon-cube"
		viewBox="0 0 40 40"
		version="1.1"
		xmlns="http://www.w3.org/2000/svg"
	>
		<g transform="matrix(1,0,0,1,-1.1686,0.622128)">
			<path d="M37.485,28.953L21.699,38.067L21.699,19.797L37.485,10.683L37.485,28.953ZM21.218,19.821L21.218,38.065L5.435,28.953L5.435,10.709L21.218,19.821ZM37.207,10.288L21.438,19.392L5.691,10.301L21.46,1.197L37.207,10.288Z" />
		</g>
	</svg>
);

const blockConfig = require( './block.json' );
registerBlockType( blockConfig.name, {
	...blockConfig,
	icon: icon,
	apiVersion: 2,
	edit: Edit,
	save: Save,
	deprecated: [
		{
			attributes: {
				bg_color: {
					type: 'string',
					default: '#FFFFFF',
				},
				zoom: {
					type: 'integer',
					default: 90,
				},
				scale: {
					type: 'integer',
					default: 1,
				},
				positionX: {
					type: 'integer',
					default: 0,
				},
				positionY: {
					type: 'integer',
					default: 0,
				},
				rotationY: {
					type: 'integer',
					default: 0,
				},
				threeObjectUrl: {
					type: 'string',
					default: null,
				},
				hasZoom: {
					type: 'bool',
					default: false,
				},
				hasTip: {
					type: 'bool',
					default: true,
				},
				deviceTarget: {
					type: 'string',
					default: '2d',
				},
			},
			save( props ) {
				return (
					<div { ...useBlockProps.save() }>
						<>
							<div className="three-object-three-app">
								<p className="three-object-block-device-target">
									{ props.attributes.deviceTarget }
								</p>
								<p className="three-object-block-url">
									{ props.attributes.threeObjectUrl }
								</p>
								<p className="three-object-scale">
									{ props.attributes.scale }
								</p>
								<p className="three-object-background-color">
									{ props.attributes.bg_color }
								</p>
								<p className="three-object-zoom">
									{ props.attributes.zoom }
								</p>
								<p className="three-object-has-zoom">
									{ props.attributes.hasZoom ? 1 : 0 }
								</p>
								<p className="three-object-has-tip">
									{ props.attributes.hasTip ? 1 : 0 }
								</p>
								<p className="three-object-position-y">
									{ props.attributes.positionY }
								</p>
								<p className="three-object-rotation-y">
									{ props.attributes.rotationY }
								</p>
								<p className="three-object-scale">
									{ props.attributes.scale }
								</p>
							</div>
						</>
					</div>
				);
			},
		},
		{
			attributes: {
				bg_color: {
					type: 'string',
					default: '#FFFFFF',
				},
				zoom: {
					type: 'integer',
					default: 90,
				},
				scale: {
					type: 'integer',
					default: 1,
				},
				positionX: {
					type: 'integer',
					default: 0,
				},
				positionY: {
					type: 'integer',
					default: 0,
				},
				rotationY: {
					type: 'integer',
					default: 0,
				},
				threeObjectUrl: {
					type: 'string',
					default: null,
				},
				hasZoom: {
					type: 'bool',
					default: false,
				},
				hasTip: {
					type: 'bool',
					default: true,
				},
				deviceTarget: {
					type: 'string',
					default: '2d',
				},
				animations: {
					type: 'string',
					default: '',
				}
			},
			save( props ) {
				return (
						<div { ...useBlockProps.save() }>
							<>
								<div className="three-object-three-app">
									<p className="three-object-block-device-target">
										{ props.attributes.deviceTarget }
									</p>
									<p className="three-object-block-url">
										{ props.attributes.threeObjectUrl }
									</p>
									<p className="three-object-scale">{ props.attributes.scale }</p>
									<p className="three-object-background-color">
										{ props.attributes.bg_color }
									</p>
									<p className="three-object-zoom">{ props.attributes.zoom }</p>
									<p className="three-object-has-zoom">
										{ props.attributes.hasZoom ? 1 : 0 }
									</p>
									<p className="three-object-has-tip">
										{ props.attributes.hasTip ? 1 : 0 }
									</p>
									<p className="three-object-position-y">
										{ props.attributes.positionY }
									</p>
									<p className="three-object-rotation-y">
										{ props.attributes.rotationY }
									</p>
									<p className="three-object-scale">{ props.attributes.scale }</p>
									<p className="three-object-animations">
										{ props.attributes.animations }
									</p>
								</div>
							</>
						</div>
						);
			},
		},
	],
} );
