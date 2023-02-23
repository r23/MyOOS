import { __ } from "@wordpress/i18n";
import React, { useState } from "react";
import { DropZone } from "@wordpress/components";
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
	NumberControl,
} from "@wordpress/components";
import { more } from "@wordpress/icons";

export default function Edit({ attributes, setAttributes, isSelected }) {
	const onImageSelect = (imageObject) => {
		setAttributes({ skyUrl: null });
		setAttributes({ skyUrl: imageObject.url });
	};
	const removeImage = (imageObject) => {
		setAttributes({ skyUrl: null });
	};

	const onChangeDistance = (distance) => {
		setAttributes({ distance });
	};
	const onChangeRayleigh = (rayleigh) => {
		setAttributes({ rayleigh });
	};
	const onChangeSunPositionX = (sunPositionX) => {
		setAttributes({ sunPositionX });
	};
	const onChangeSunPositionY = (sunPositionY) => {
		setAttributes({ sunPositionY });
	};
	const onChangeSunPositionZ = (sunPositionZ) => {
		setAttributes({ sunPositionZ });
	};

	const { mediaUpload } = wp.editor;

	const ALLOWED_MEDIA_TYPES = ["image"];

	return (
		<div {...useBlockProps()}>
			<InspectorControls key="setting">
				<Panel 
					header="Settings"
					className="three-object-environment-edit-container"
				>
					<PanelBody
						title="Sky Object"
						icon={more}
						initialOpen={true}
					>
						<PanelRow>
							<span>
								Select an image to be used as your skybox. 360
								spherical panoramics recommended:
							</span>
						</PanelRow>
						<PanelRow>
							<MediaUpload
								onSelect={(imageObject) =>
									onImageSelect(imageObject)
								}
								type="image"
								label="Sky File"
								allowedTypes={ALLOWED_MEDIA_TYPES}
								value={attributes.skyUrl}
								render={({ open }) => (
									<>
									<button onClick={open}>
										{attributes.skyUrl
											? "Replace Sky"
											: "Select Sky"}
									</button>
									{attributes.skyUrl && (
									<button onClick={removeImage}>		
											Remove Image
									</button>
									)}
									</>
								)}
							/>
						</PanelRow>
						<PanelRow>
							<RangeControl
								label="distance"
								default={400000}
								min={1}
								max={400000}
								value={ attributes.distance }
								onChange={ onChangeDistance }
							/>
						</PanelRow>
						<PanelRow>
							<RangeControl
								label="rayleigh"
								default={2}
								min={0}
								max={10}
								value={ attributes.rayleigh }
								onChange={ onChangeRayleigh }
							/>
						</PanelRow>
						<PanelRow>
							<legend className="blocks-base-control__label">
								{__("Sun Position", "three-object-viewer")}
							</legend>
						</PanelRow>
						<PanelRow className="threeov-three-number-settings">
							<TextControl
								className="position-inputs"
								label="X"
								default={0}
								value={attributes.sunPositionX}
								onChange={(value) => onChangeSunPositionX(value)}
							/>
							<TextControl
								className="position-inputs"
								label="Y"
								default={10000}
								// help="position y"
								value={attributes.sunPositionY}
								onChange={(value) => onChangeSunPositionY(value)}
							/>
							<TextControl
								className="position-inputs"
								label="Z"
								default={-10000}
								// help="position z"
								value={attributes.sunPositionZ}
								onChange={(value) => onChangeSunPositionZ(value)}
							/>
						</PanelRow>

					</PanelBody>
				</Panel>
			</InspectorControls>
			{isSelected ? (
				<>
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
								<b>Sky block</b>
							</p>
							{/* <p>URL: {attributes.skyUrl}</p> */}
						</div>
					</div>
				</>
			) : (
				<>
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
								<b>Sky block</b>
							</p>
							{/* <p>URL: {attributes.skyUrl}</p> */}
						</div>
					</div>
				</>
			)}
		</div>
	);
}
