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
	TextControl
} from "@wordpress/components";
import { more } from "@wordpress/icons";

export default function Edit({ attributes, setAttributes, isSelected }) {
	const onImageSelect = (imageObject) => {
		console.log(imageObject);
		setAttributes({ videoUrl: null });
		setAttributes({
			videoUrl: imageObject.url,
			aspectHeight: imageObject.height,
			aspectWidth: imageObject.width
		});
	};
	const onChangePositionX = (positionX) => {
		setAttributes({ positionX });
	};
	const onChangePositionY = (positionY) => {
		setAttributes({ positionY });
	};
	const onChangePositionZ = (positionZ) => {
		setAttributes({ positionZ });
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

	const onChangeAutoPlay = (autoPlaySetting) => {
		setAttributes({ autoPlay: autoPlaySetting });
	};
	const onChangeLoop = (loopSetting) => {
		setAttributes({ loop: loopSetting });
	};
	const onChangePositional = (positionalSetting) => {
		setAttributes({ positional: positionalSetting });
	};
	const onChangeVolume = (volumeSetting) => {
		setAttributes({ volume: volumeSetting });
	};
	const onChangeConeInnerAngle = (coneInnerAngleSetting) => {
		setAttributes({ coneInnerAngle: coneInnerAngleSetting });
	};
	const onChangeConeOuterAngle = (coneOuterAngleSetting) => {
		setAttributes({ coneOuterAngle: coneOuterAngleSetting });
	};
	const onChangeConeOuterGain = (coneOuterGainSetting) => {
		setAttributes({ coneOuterGain: coneOuterGainSetting });
	};
	const onChangeDistanceModel = (distanceModelSetting) => {
		setAttributes({ distanceModel: distanceModelSetting });
	};
	const onChangeMaxDistance = (maxDistanceSetting) => {
		setAttributes({ maxDistance: maxDistanceSetting });
	};
	const onChangeRefDistance = (refDistanceSetting) => {
		setAttributes({ refDistance: refDistanceSetting });
	};

	const onChangeRolloffFactor = (rolloffFactorSetting) => {
		setAttributes({ rolloffFactor: rolloffFactorSetting });
	};

	const { mediaUpload } = wp.editor;

	const ALLOWED_MEDIA_TYPES = ["audio"];

	const MyDropZone = () => {
		const [hasDropped, setHasDropped] = useState(false);
		return (
			<div>
				{hasDropped ? "Dropped!" : "Drop an image here or"}
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
			console.log("success good job", objectURL);
			onImageSelect(objectURL);
		}
		console.log("fail", objectURL);
	}

	return (
		<div {...useBlockProps()}>
			<InspectorControls key="setting">
				<Panel header="Settings">
					<PanelBody
						title="Audio Object"
						icon={more}
						initialOpen={true}
					>
						<PanelRow>
							<span>
								Select an image or object to attach to your
								audio. Leave blank for no mesh.
							</span>
						</PanelRow>
						<PanelRow>
							<MediaUpload
								onSelect={(imageObject) =>
									onImageSelect(imageObject)
								}
								type="audio"
								label="Audio File"
								allowedTypes={ALLOWED_MEDIA_TYPES}
								value={attributes.audioUrl}
								render={({ open }) => (
									<button onClick={open}>
										{attributes.audioUrl
											? "Replace Audio"
											: "Select Audio"}
									</button>
								)}
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
							<ToggleControl
								label="AutoPlay"
								help={
									attributes.autoPlay
										? "Item will autoplay."
										: "Item will not autoplay."
								}
								checked={attributes.autoPlay}
								onChange={(e) => {
									onChangeAutoPlay(e);
								}}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label="Loop"
								help={
									attributes.loop
										? "Item will loop."
										: "Item will not loop."
								}
								checked={attributes.loop}
								onChange={(e) => {
									onChangeLoop(e);
								}}
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label="Positional"
								help={
									attributes.positional
										? "Item will be spatial audio."
										: "Item will be global audio."
								}
								checked={attributes.positional}
								onChange={(e) => {
									onChangePositional(e);
								}}
							/>
						</PanelRow>
						<PanelRow>
							<RangeControl
								className="position-inputs"
								label="Volume"
								help="Unitless multiplier against original source volume for determining emitter loudness."
								max={1}
								min={0}
								step={0.01}
								// help="position x"
								value={attributes.volume}
								onChange={(value) => onChangeVolume(value)}
							/>
						</PanelRow>
					</PanelBody>
					{attributes.positional && (
						<PanelBody
							title="Positional Volume Settings"
							icon={more}
							initialOpen={false}
						>
							<PanelRow>
								<RangeControl
									className="position-inputs"
									label="Cone Inner angle"
									help="The angle, in radians, of a cone outside of which the volume will be reduced to a constant value ofconeOuterGain."
									max={360}
									min={0}
									step={0.01}
									// help="position x"
									value={attributes.coneInnerAngle}
									onChange={(value) =>
										onChangeConeInnerAngle(value)
									}
								/>
							</PanelRow>
							<PanelRow>
								<RangeControl
									className="position-inputs"
									label="Cone Outer Angle"
									help="The angle, in radians, of a cone outside of which the volume will be reduced to a constant value ofconeOuterGain."
									max={360}
									min={0}
									step={0.01}
									value={attributes.coneOuterAngle}
									onChange={(value) =>
										onChangeConeOuterAngle(value)
									}
								/>
							</PanelRow>
							<PanelRow>
								<RangeControl
									className="position-inputs"
									label="Cone Outer Gain"
									help="The gain of the audio emitter set when outside the cone defined by the coneOuterAngle property."
									max={1}
									min={0}
									step={0.01}
									value={attributes.coneOuterGain}
									onChange={(value) =>
										onChangeConeOuterGain(value)
									}
								/>
							</PanelRow>
							<PanelRow>
								<SelectControl
									value={attributes.distanceModel}
									label="Distance Model"
									help="Specifies the distance model for the audio emitter."
									options={[
										{ label: "Inverse", value: "inverse" },
										{ label: "Linear", value: "linear" },
										{
											label: "Exponential",
											value: "exponential"
										}
									]}
									onChange={(target) =>
										onChangeDistanceModel(target)
									}
								/>
							</PanelRow>
							<PanelRow>
								<RangeControl
									className="position-inputs"
									label="Max Distance"
									help="The maximum distance between the emitter and listener, after which the volume will not be reduced any further. maximumDistance may only be applied when the distanceModel is set to linear. Otherwise, it should be ignored."
									max={10000}
									min={0}
									step={0.01}
									value={attributes.maxDistance}
									onChange={(value) =>
										onChangeMaxDistance(value)
									}
								/>
							</PanelRow>
							<PanelRow>
								<RangeControl
									className="position-inputs"
									label="Ref Distance"
									help="A reference distance for reducing volume as the emitter moves further from the listener. For distances less than this, the volume is not reduced."
									max={500}
									min={0}
									step={0.01}
									value={attributes.refDistance}
									onChange={(value) =>
										onChangeRefDistance(value)
									}
								/>
							</PanelRow>
							<PanelRow>
								<RangeControl
									className="position-inputs"
									label="Rolloff Factor"
									help="Describes how quickly the volume is reduced as the emitter moves away from listener. When distanceModel is set to linear, the maximum value is 1 otherwise there is no upper limit."
									max={500}
									min={0}
									step={0.01}
									value={attributes.rolloffFactor}
									onChange={(value) =>
										onChangeRolloffFactor(value)
									}
								/>
							</PanelRow>
						</PanelBody>
					)}
				</Panel>
			</InspectorControls>
			{isSelected ? (
				<>
					{attributes.videoUrl ? (
						<>
							<VideoEdit
								src={attributes.videoUrl}
								aspectHeight={attributes.aspectHeight}
								aspectWidth={attributes.aspectWidth}
							/>
						</>
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
									type="video"
									allowedTypes={ALLOWED_MEDIA_TYPES}
									value={attributes.videoUrl}
									render={({ open }) => (
										<button
											className="three-object-viewer-button"
											onClick={open}
										>
											{attributes.videoUrl
												? "Replace Object"
												: "Select Audio"}
										</button>
									)}
								/>
							</div>
						</div>
					)}
				</>
			) : (
				<>
					{attributes.videoUrl ? (
						<>
							<VideoEdit
								src={attributes.videoUrl}
								aspectHeight={attributes.aspectHeight}
								aspectWidth={attributes.aspectWidth}
							/>
						</>
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
									value={attributes.videoUrl}
									render={({ open }) => (
										<button
											className="three-object-viewer-button"
											onClick={open}
										>
											Select Audio
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
