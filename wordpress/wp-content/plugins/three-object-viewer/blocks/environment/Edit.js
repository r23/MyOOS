import { __ } from "@wordpress/i18n";
import React, { useState, useEffect } from "react";
import "./editor.scss";
import {
	useBlockProps,
	ColorPalette,
	InspectorControls,
	MediaUpload,
	InnerBlocks
} from "@wordpress/block-editor";
import {
	Panel,
	PanelBody,
	PanelRow,
	RangeControl,
	ToggleControl,
	SelectControl,
	TextControl,
	DropZone
} from "@wordpress/components";
import { Icon, moveTo, more, rotateLeft, resizeCornerNE } from "@wordpress/icons";
import * as THREE from "three";
import defaultEnvironment from "../../inc/assets/default_grid.glb";

import ThreeObjectEdit from "./components/ThreeObjectEdit";

export default function Edit({ attributes, setAttributes, isSelected }) {
	
	const ALLOWED_BLOCKS = allowed_blocks;
	const [focusPosition, setFocusPosition] = useState(new THREE.Vector3());
	const [focusPoint, setFocus] = useState(new THREE.Vector3());
	const [mainModel, setMainModel] = useState(attributes.threeObjectUrl ? attributes.threeObjectUrl : (threeObjectPlugin + defaultEnvironment));
	const changeFocusPoint = (newValue) => {
		setFocusPosition(newValue);
	}


	// useEffect to initialize the value of the threeObjectUrl attribute if it is not set
	useEffect(() => {
		if (!attributes.threeObjectUrl) {
			setAttributes({ threeObjectUrl: (threeObjectPlugin + defaultEnvironment) });
		}
	}, []);

	const onChangeAnimations = (animations) => {
		setAttributes({ animations });
	};

	const onImageSelect = (imageObject) => {
		setAttributes({ threeObjectUrl: null });
		setMainModel(null);
		setMainModel(imageObject.url);
		setAttributes({ threeObjectUrl: imageObject.url });
	};

	const onPreviewImageSelect = (imageObject) => {
		setAttributes({ threePreviewImage: null });
		setAttributes({ threePreviewImage: imageObject.url });
	};

	const onChangePositionY = (posy) => {
		setAttributes({ positionY: posy });
	};

	const onChangeScale = (scale) => {
		setAttributes({ scale });
	};

	const onChangerotationY = (rotz) => {
		setAttributes({ rotationY: rotz });
	};

	const setDeviceTarget = (target) => {
		setAttributes({ deviceTarget: target });
	};

	const [enteredURL, setEnteredURL] = useState("");

	const { mediaUpload } = wp.editor;

	const ALLOWED_MEDIA_TYPES = [
		"model/gltf-binary",
		"application/octet-stream"
	];

	const TEMPLATE = [            
		['three-object-viewer/spawn-point-block', { positionX: "0", positionY: "1.3", positionZ: "-5", rotationX: "0", rotationY: "0", rotationZ: "0"}],
	];	  

	const MyDropZone = () => {
		const [hasDropped, setHasDropped] = useState(false);
		return (
			<div>
				{hasDropped ? "Dropped!" : "Drop a glb here or"}
				<DropZone
					onFilesDrop={(files) =>
						mediaUpload({
							allowedTypes: ALLOWED_MEDIA_TYPES,
							filesList: files,
							onFileChange: ([images]) => {
								onImageSelect(images);
							}
						})
					}
				/>
			</div>
		);
	};

	return (
		<div {...useBlockProps()}>
			<InspectorControls key="setting">
				<Panel
					className="three-object-environment-edit-container"
					header="Settings"
				>
					<PanelBody
						title="Environment Object (Changing this value changes your scenes ground planes)"
						icon={more}
						initialOpen={true}
					>
						<PanelRow>
							<span>
								Select a glb file from your media library. This
								will be treated as a collidable mesh that
								visitors can walk on:
							</span>
						</PanelRow>
						<PanelRow>
							<MediaUpload
								onSelect={(imageObject) =>
									onImageSelect(imageObject)
								}
								type="image"
								label="GLB File"
								allowedTypes={ALLOWED_MEDIA_TYPES}
								value={attributes.threeObjectUrl}
								render={({ open }) => (
									<button onClick={open}>
										{attributes.threeObjectUrl
											? "Replace Environment"
											: "Select Environment"}
									</button>
								)}
							/>
						</PanelRow>
						<PanelRow>
							<span>
								Select an image to be used as the preview image:
							</span>
						</PanelRow>
						<PanelRow>
							<span>
								<img
									alt="Preview"
									src={
										attributes.threePreviewImage
											? attributes.threePreviewImage
											: ""
									}
									style={{
										maxHeight: "150px"
									}}
								/>
							</span>
						</PanelRow>
						<PanelRow>
							<MediaUpload
								onSelect={(imageObject) =>
									onPreviewImageSelect(imageObject)
								}
								type="image"
								label="Image File"
								// allowedTypes={ ALLOWED_MEDIA_TYPES }
								value={attributes.threePreviewImage}
								render={({ open }) => (
									<button onClick={open}>
										{attributes.threePreviewImage
											? "Replace Image"
											: "Select Image"}
									</button>
								)}
							/>
						</PanelRow>
					</PanelBody>
					<PanelBody
						title="Scene Settings"
						icon={more}
						initialOpen={true}
					>
						<PanelRow>
							<span>Object Display Type:</span>
						</PanelRow>
						<PanelRow>
							<SelectControl
								// label="Device Target"
								value={attributes.deviceTarget}
								options={[{ label: "VR", value: "vr" }]}
								onChange={(target) => setDeviceTarget(target)}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label="Loop Animations"
								help="Separate each animation name you wish to loop with a comma"
								value={attributes.animations}
								onChange={(value) => onChangeAnimations(value)}
							/>
						</PanelRow>
						<PanelRow>
							<RangeControl
								label="scale"
								value={attributes.scale}
								min={0}
								max={200}
								onChange={onChangeScale}
							/>
						</PanelRow>
						<PanelRow>
							<RangeControl
								label="positionY"
								value={attributes.positionY}
								min={-100}
								max={100}
								step={0.01}
								onChange={onChangePositionY}
							/>
						</PanelRow>
						<PanelRow>
							<RangeControl
								label="rotationY"
								value={attributes.rotationY}
								min={-10}
								max={10}
								step={0.001}
								onChange={onChangerotationY}
							/>
						</PanelRow>
					</PanelBody>
				</Panel>
			</InspectorControls>
				<>					
				<InnerBlocks
					renderAppender={ InnerBlocks.ButtonBlockAppender }
					allowedBlocks={ALLOWED_BLOCKS}
					template={TEMPLATE}
				/>
					{mainModel && (
						<ThreeObjectEdit
							url={mainModel}
							deviceTarget={attributes.deviceTarget}
							backgroundColor={attributes.bg_color}
							zoom={attributes.zoom}
							scale={attributes.scale}
							hasZoom={attributes.hasZoom}
							hasTip={attributes.hasTip}
							positionX={attributes.positionX}
							positionY={attributes.positionY}
							animations={attributes.animations}
							rotationY={attributes.rotationY}
							setFocusPosition={setFocusPosition}
							setFocus={setFocus}
							changeFocusPoint={changeFocusPoint}
							focusPosition={focusPosition}
							focusPoint={focusPoint}
							selected={isSelected}
						/>
					)}
				</>
		</div>
	);
}
