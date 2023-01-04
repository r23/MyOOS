import { __ } from "@wordpress/i18n";
import React, { useState, useEffect } from "react";
import { DropZone } from "@wordpress/components";
import { dispatch } from '@wordpress/data';
import "./editor.scss";
import {
	useBlockProps,
	ColorPalette,
	InspectorControls,
	MediaUpload
} from "@wordpress/block-editor";
import {
	Panel,
	PanelBody,
	PanelRow,
	RangeControl,
	ToggleControl,
	SelectControl,
	TextControl,
	TextareaControl
} from "@wordpress/components";
import { more } from "@wordpress/icons";

export default function Edit({ attributes, setAttributes, isSelected, clientId }) {

	const { select, dispatch } = wp.data;

	const { onSelectionChange, getSelectedBlock } = wp.blocks;
	useEffect(() => {
		if( isSelected ){
			dispatch( 'three-object-environment-events' ).setFocusEvent( clientId );
		}
	}, [isSelected]);

	const onChangePositionX = (positionX) => {
		setAttributes({ positionX });
	};
	const onChangePositionY = (positionY) => {
		setAttributes({ positionY });
	};
	const onChangePositionZ = (positionZ) => {
		setAttributes({ positionZ });
	};

	const onChangeAlt = (altValue) => {
		setAttributes({ alt: altValue });
	};

	const onChangeRotationX = (rotationX) => {
		setAttributes({ rotationX });
	};
	const onChangeRotationY = (rotationY) => {
		setAttributes({ rotationY });
	};
	const onChangeRotationZ = (rotationZ) => {
		setAttributes({ rotationZ });
	};

	const onChangeScaleX = (scaleX) => {
		setAttributes({ scaleX });
	};
	const onChangeScaleY = (scaleY) => {
		setAttributes({ scaleY });
	};
	const onChangeScaleZ = (scaleZ) => {
		setAttributes({ scaleZ });
	};

	const onChangeAnimations = (animations) => {
		setAttributes({ animations });
	};

	const onChangeName = (name) => {
		setAttributes({ name });
	};

	const onImageSelect = (imageObject) => {
		setAttributes({ threeObjectUrl: null });
		setAttributes({ threeObjectUrl: imageObject.url });
	};

	const onChangeCollidable = (collidableSetting) => {
		setAttributes({ collidable: collidableSetting });
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

	function handleClick(objectURL) {
		if (objectURL) {
			onImageSelect(objectURL);
		}
		console.log("fail", objectURL);
	}
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
							<TextControl
								label="Name"
								help="Give your object a name."
								value={attributes.name}
								onChange={(value) => onChangeName(value)}
							/>
						</PanelRow>
						<PanelRow>
							<span>
								select a glb file from your media library to
								render an object in the canvas:
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
					</PanelBody>
					<PanelBody
						title="Model Attributes"
						icon={more}
						initialOpen={true}
					>
						<PanelRow>
							<ToggleControl
								label="Collidable"
								help={
									attributes.collidable
										? "Item is currently collidable."
										: "Item is not collidable. Users will walk through it."
								}
								checked={attributes.collidable}
								onChange={(e) => {
									onChangeCollidable(e);
								}}
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
							<TextareaControl
								label="Model Alt Text"
								help="Describe your model to provide context for screen readers."
								value={attributes.alt}
								onChange={(value) => onChangeAlt(value)}
							/>
						</PanelRow>
						<PanelRow>
							<legend className="blocks-base-control__label">
								{__("Position", "three-object-viewer")}
							</legend>
						</PanelRow>
						<PanelRow>
							<TextControl
								className="position-inputs"
								label="X"
								// help="position x"
								value={attributes.positionX}
								onChange={(value) => onChangePositionX(value)}
							/>
							<TextControl
								className="position-inputs"
								label="Y"
								// help="position y"
								value={attributes.positionY}
								onChange={(value) => onChangePositionY(value)}
							/>
							<TextControl
								className="position-inputs"
								label="Z"
								// help="position z"
								value={attributes.positionZ}
								onChange={(value) => onChangePositionZ(value)}
							/>
						</PanelRow>
						<PanelRow>
							<legend className="blocks-base-control__label">
								{__("Rotation", "three-object-viewer")}
							</legend>
						</PanelRow>
						<PanelRow>
							<TextControl
								className="position-inputs"
								label="X"
								// help="position x"
								value={attributes.rotationX}
								onChange={(value) => onChangeRotationX(value)}
							/>
							<TextControl
								className="position-inputs"
								label="Y"
								// help="position y"
								value={attributes.rotationY}
								onChange={(value) => onChangeRotationY(value)}
							/>
							<TextControl
								className="position-inputs"
								label="Z"
								// help="position z"
								value={attributes.rotationZ}
								onChange={(value) => onChangeRotationZ(value)}
							/>
						</PanelRow>
						<PanelRow>
							<legend className="blocks-base-control__label">
								{__("Scale", "three-object-viewer")}
							</legend>
						</PanelRow>
						<PanelRow>
							<TextControl
								className="position-inputs"
								label="X"
								// help="position x"
								value={attributes.scaleX}
								onChange={(value) => onChangeScaleX(value)}
							/>
							<TextControl
								className="position-inputs"
								label="Y"
								// help="position y"
								value={attributes.scaleY}
								onChange={(value) => onChangeScaleY(value)}
							/>
							<TextControl
								className="position-inputs"
								label="Z"
								// help="position z"
								value={attributes.scaleZ}
								onChange={(value) => onChangeScaleZ(value)}
							/>
						</PanelRow>
					</PanelBody>
				</Panel>
			</InspectorControls>
			{isSelected ? (
				<>
					{attributes.threeObjectUrl ? (
						//Not selected
							<div className="three-object-viewer-inner">
								<div className="three-object-viewer-inner-edit-container">
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
									<p>
										<b>Model block</b>
									</p>
								</div>
								{/* <p className="three-object-viewer-model-name">
									{attributes.name}
								</p> */}
							</div>
					) : (
						<div className="three-object-viewer-inner">
							<div className="three-object-viewer-inner-edit-container">
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
												? "Replace Model"
												: "Select Model"}
										</button>
									)}
								/>
							</div>
						</div>
					)}
				</>
			) : (
				<>
					{attributes.threeObjectUrl ? (
							<div className="three-object-viewer-inner">
								<div className="three-object-viewer-inner-edit-container">
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
									<p>
										<b>Model block</b>
									</p>
								</div>
								{/* <p className="three-object-viewer-model-name">
									{attributes.name}
								</p> */}
							</div>
					) : (
						<div className="three-object-viewer-inner">
							<div className="three-object-viewer-inner-edit-container">
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
												? "Replace Model"
												: "Select Model"}
										</button>
									)}
								/>
							</div>
						</div>
					)}
				</>
			)}
		</div>
	);
}
