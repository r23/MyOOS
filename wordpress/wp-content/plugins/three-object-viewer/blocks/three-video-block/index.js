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
				videoUrl: {
					type: "string",
					default: null
				  },
				  modelUrl: {
					type: "string",
					default: null
				  },
				  autoPlay: {
					type: "bool",
					default: true
				  },
				  scaleX: {
					type: "int",
					default: 1
				  },
				  scaleY: {
					type: "int",
					default: 1
				  },
				  scaleZ: {
					type: "int",
					default: 1
				  },
				  positionX: {
					type: "int",
					default: 0
				  },
				  positionY: {
					type: "int",
					default: 0
				  },
				  positionZ: {
					type: "int",
					default: 0
				  },
				  rotationX: {
					type: "int",
					default: 0
				  },
				  rotationY: {
					type: "int",
					default: 0
				  },
				  rotationZ: {
					type: "int",
					default: 0
				  },
				  aspectHeight: {
					type: "int",
					default: 0
				  },
				  aspectWidth: {
					type: "int",
					default: 0
				  }
				},
			save(props) {
				return (
					<div {...useBlockProps.save()}>
						<>
							<div className="three-object-three-app-video-block">
								<div className="video-block-url">{props.attributes.videoUrl}</div>
								<p className="video-block-scaleX">{props.attributes.scaleX}</p>
								<p className="video-block-scaleY">{props.attributes.scaleY}</p>
								<p className="video-block-scaleZ">{props.attributes.scaleZ}</p>
								<p className="video-block-positionX">
									{props.attributes.positionX}
								</p>
								<p className="video-block-positionY">
									{props.attributes.positionY}
								</p>
								<p className="video-block-positionZ">
									{props.attributes.positionZ}
								</p>
								<p className="video-block-rotationX">
									{props.attributes.rotationX}
								</p>
								<p className="video-block-rotationY">
									{props.attributes.rotationY}
								</p>
								<p className="video-block-rotationZ">
									{props.attributes.rotationZ}
								</p>
								<p className="video-block-aspect-height">
									{props.attributes.aspectHeight}
								</p>
								<p className="video-block-aspect-width">
									{props.attributes.aspectWidth}
								</p>
								<p className="video-block-autoplay">
									{props.attributes.autoPlay ? 1 : 0}
								</p>
							</div>
						</>
					</div>
				);
			}
		}
	]
});
