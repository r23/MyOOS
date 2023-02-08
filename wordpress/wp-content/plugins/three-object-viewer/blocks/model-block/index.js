import { registerBlockType } from "@wordpress/blocks";
import Edit from "./Edit";
import Save from "./Save";
import { useBlockProps } from "@wordpress/block-editor";

const icon = (
	<svg
		className="custom-icon custom-icon-cube"
		viewBox="0 0 40 40"
		version="1.1"
		xmlns="http://www.w3.org/2000/svg"
	>
		<g transform="matrix(1,0,0,1,-1.1686,0.622128)">
			<path d="M37.485,28.953L21.699,38.067L21.699,19.797L37.485,10.683L37.485,28.953ZM21.218,19.821L21.218,38.065L5.435,28.953L5.435,10.709L21.218,19.821ZM37.207,10.288L21.438,19.392L5.691,10.301L21.46,1.197L37.207,10.288Z" />
		</g>
	</svg>
);

const blockConfig = require("./block.json");
registerBlockType(blockConfig.name, {
	...blockConfig,
	icon,
	apiVersion: 2,
	edit: Edit,
	save: Save,
	deprecated: [
		{
			attributes: {
				scaleX: {
					type: "int",
					default:1
				},
				name: {
					type: "string"
				},
				scaleY: {
					type: "int",
					default:1
				},
				scaleZ: {
					type: "int",
					default:1
				},
				positionX: {
					type: "int",
					default:0
				},
				positionY: {
					type: "int",
					default:0
				},
				positionZ: {
					type: "int",
					default:0
				},
				rotationX: {
					type: "int",
					default:0
				},
				rotationY: {
					type: "int",
					default:0
				},
				rotationZ: {
					type: "int",
					default:0
				},
				threeObjectUrl: {
					type: "string",
					default: null
				},
				animations: {
					type: "string",
					default: ""
				},
				alt: {
					type: "string",
					default: ""
				},
				collidable: {
					type: "boolean",
					default: false
				}
			},
			save(props) {
				return (
					<div {...useBlockProps.save()}>
						<>
							<div className="three-object-three-app-model-block">
								<p className="model-block-url">
									{props.attributes.threeObjectUrl}
								</p>
								<p className="model-block-scale-x">{props.attributes.scaleX}</p>
								<p className="model-block-scale-y">{props.attributes.scaleY}</p>
								<p className="model-block-scale-z">{props.attributes.scaleZ}</p>
								<p className="model-block-position-x">
									{props.attributes.positionX}
								</p>
								<p className="model-block-position-y">
									{props.attributes.positionY}
								</p>
								<p className="model-block-position-z">
									{props.attributes.positionZ}
								</p>
								<p className="model-block-rotation-x">
									{props.attributes.rotationX}
								</p>
								<p className="model-block-rotation-y">
									{props.attributes.rotationY}
								</p>
								<p className="model-block-rotation-z">
									{props.attributes.rotationZ}
								</p>
								<p className="model-block-animations">
									{props.attributes.animations}
								</p>
								<p className="model-block-collidable">
									{props.attributes.collidable ? 1 : 0}
								</p>
								<p className="model-block-alt">{props.attributes.alt}</p>
							</div>
						</>
					</div>
				);
			}
		}
	]
});
