import { __ } from "@wordpress/i18n";
import React, { useState } from "react";
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
import { more } from "@wordpress/icons";

import ThreeObjectEdit from "./components/ThreeObjectEdit";

export default function Edit({ attributes, setAttributes, isSelected }) {
	const ALLOWED_BLOCKS = allowed_blocks;
	const onChangeAnimations = (animations) => {
		setAttributes({ animations });
	};

	const onImageSelect = (imageObject) => {
		setAttributes({ threeObjectUrl: null });
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
				<Panel header="Settings">
					<PanelBody
						title="GLB Object"
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
											? "Replace Object"
											: "Select Object"}
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
			{isSelected ? (
				<>
					{attributes.threeObjectUrl ? (
						<ThreeObjectEdit
							url={attributes.threeObjectUrl}
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
							selected={isSelected}
						/>
					) : (
						<div className="glb-preview-container">
							<MyDropZone />

							<div>
								<span>
									Select a glb file to render in the canvas:
								</span>
								{/* <div className="three-object-block-url-input"> 
									<input onChange={(e) => setEnteredURL(e.target.value)}></input> 
									<button 
										className="three-object-viewer-button" 
										onClick={	handleClick(enteredURL) }
									>
										Use URL
									</button>
								</div> */}
								<MediaUpload
									onSelect={(imageObject) =>
										onImageSelect(imageObject)
									}
									type="image"
									allowedTypes={ALLOWED_MEDIA_TYPES}
									value={attributes.threeObjectUrl}
									render={({ open }) => (
										<button
											className="three-object-viewer-button"
											onClick={open}
										>
											{attributes.threeObjectUrl
												? "Replace Object"
												: "Select From Media Library"}
										</button>
									)}
								/>
							</div>
						</div>
					)}
					<InnerBlocks allowedBlocks={ALLOWED_BLOCKS} />
				</>
			) : (
				<>
					{attributes.threeObjectUrl ? (
						<ThreeObjectEdit
							url={attributes.threeObjectUrl}
							backgroundColor={attributes.bg_color}
							deviceTarget={attributes.deviceTarget}
							zoom={attributes.zoom}
							scale={attributes.scale}
							hasZoom={attributes.hasZoom}
							hasTip={attributes.hasTip}
							positionX={attributes.positionX}
							positionY={attributes.positionY}
							animations={attributes.animations}
							rotationY={attributes.rotationY}
							selected={isSelected}
						/>
					) : (
						<div className="glb-preview-container">
							<MyDropZone />
							<div>
								<span>
									Select a glb file to render in the canvas:
								</span>
								{/* <div className="three-object-block-url-input"> 
								<input onChange={(e) => console.log(e.target.value) && setEnteredURL(e.target.value)}></input> 
									<button 
										className="three-object-viewer-button" 
										onClick={	handleClick(enteredURL) }
									>
										Use URL
									</button>
								</div> */}
							</div>
							<MediaUpload
								onSelect={(imageObject) =>
									onImageSelect(imageObject)
								}
								type="image"
								allowedTypes={ALLOWED_MEDIA_TYPES}
								value={attributes.threeObjectUrl}
								render={({ open }) => (
									<button
										className="three-object-viewer-button"
										onClick={open}
									>
										Select From Media Library
									</button>
								)}
							/>
						</div>
					)}
					<InnerBlocks allowedBlocks={ALLOWED_BLOCKS} />
				</>
			)}
		</div>
	);
}
