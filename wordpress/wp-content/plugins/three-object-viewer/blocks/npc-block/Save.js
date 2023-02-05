import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";

export default function save({ attributes }) {
	return (
		<div {...useBlockProps.save()}>
			<>
				<div className="three-object-three-app-npc-block">
					<p className="npc-block-url">
						{attributes.threeObjectUrl}
					</p>
					<p className="npc-block-position-x">
						{attributes.positionX}
					</p>
					<p className="npc-block-position-y">
						{attributes.positionY}
					</p>
					<p className="npc-block-position-z">
						{attributes.positionZ}
					</p>
					<p className="npc-block-rotation-x">
						{attributes.rotationX}
					</p>
					<p className="npc-block-rotation-y">
						{attributes.rotationY}
					</p>
					<p className="npc-block-rotation-z">
						{attributes.rotationZ}
					</p>
					<p className="npc-block-name">
						{attributes.name}
					</p>
					<p className="npc-block-default-message">
						{attributes.defaultMessage}
					</p>
					<p className="npc-block-personality">
						{attributes.personality}
					</p>
					<p className="npc-block-object-awareness">
						{attributes.objectAwareness ? 1 : 0}
					</p>
				</div>
			</>
		</div>
	);
}
