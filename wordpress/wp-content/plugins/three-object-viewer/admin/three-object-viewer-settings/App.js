import { Suspense, useState, useEffect } from "@wordpress/element";

//Main component for admin page app
export default function App({ getSettings, updateSettings }) {

	let frame

	//Track settings state
	const [settings, setSettings] = useState({});
	//Use to show loading spinner
	const [isLoading, setIsLoading] = useState(true);
	const [isOpenApiKeyVisible, setIsOpenApiKeyVisible] = useState(false);

	const [defaultVRM, setDefaultVRM] = useState();

	//When app loads, get settings
	useEffect(() => {
		getSettings().then((r) => {
			setSettings(r);
			setIsLoading(false);
		});
	}, [getSettings, setSettings]);

    //Function to update settings via API
	const onSave = async (event) => {
		event.preventDefault();
		let response = await updateSettings(settings)
		setSettings(response);
	};

	const runUploader = (event) => {
		event.preventDefault()
	
		// If the media frame already exists, reopen it.
		if (frame) {
			frame.open()
			return
		}
	
		// Create a new media frame
		frame = wp.media({
			title: 'Select or Upload Media',
			button: {
				text: 'Use this media',
			},
			multiple: false, // Set to true to allow multiple files to be selected
		})
		frame.on( 'select', function() {
      
			// Get media attachment details from the frame state
			var attachment = frame.state().get('selection').first().toJSON();
			setSettings({ ...settings, defaultVRM: attachment.url });
			// Send the attachment URL to our custom image input field.
		  });
	  
		  
		// Finally, open the modal on click
		frame.open()
	}

	//Show a spinner if loading
	if (isLoading) {
		return <div className="spinner" style={{ visibility: "visible" }} />;
	}
	const clearDefaultAnimation = () => {
		setSettings({ ...settings, defaultVRM: "" });
	  }
	  
	//Show settings if not loading
	return (
		<>
		<form autocomplete="off">
		<table class="form-table">
			<tbody>
				<tr>
					<td>
						<div><h2>3OV Settings</h2></div>
						<div><p>Here you can manage the settings for 3OV to tweak global configuration options and save your API keys for connected serivces.</p></div>
					</td>
				</tr>
				<tr>
					<td><h3>Avatar Settings</h3></td>
				</tr>
				<tr>
					<td>
						<label htmlFor="defaultVRM"><b>Default animation</b></label>
					</td>
				</tr>
				<tr>
					<td>
						{ settings.defaultVRM ? settings.defaultVRM : "No custom default animation set"}
					</td>
				</tr>
				<tr>
					<td>
						<button type='button' onClick={runUploader}>
							Set Default Animation
						</button>
					</td>
				</tr>
				<tr>
					<td>
						<button type='button' onClick={clearDefaultAnimation}>
							Clear Default Animation
						</button>
					</td>
				</tr>
				<tr>
					<td><h3>AI Settings</h3></td>
				</tr>
				<tr>
					<td>NPC Settings</td>
				</tr>
				<tr>
					<td>
						<label htmlFor="enabled">Enable</label>
						<input
							id="enabled"
							type="checkbox"
							name="enabled"
							value={settings.enabled}
							checked={settings.enabled}
							onChange={(event) => {
								setSettings({ ...settings, enabled: event.target.checked });
							}}
						/>
					</td>
				</tr>
				<tr>
					<td>
						<label htmlFor="networkWorker">AI Endpoint URL</label>
						<input
							id="networkWorker"
							type="input"
							className="regular-text"
							name="networkWorker"
							autoComplete="off"
							value={settings.networkWorker}
							onChange={(event) => {
								setSettings({ ...settings, networkWorker: event.target.value });
							}}
						/>
					</td>
				</tr>
				<tr>
					<td>
						<label htmlFor="openApiKey">OpenAI API Token</label>
						{isOpenApiKeyVisible ? (
							<input
							id="openApiKey"
							type="text"
							name="openApiKey"
							autoComplete="off"
							value={settings.openApiKey}
							onChange={(event) => {
								setSettings({ ...settings, openApiKey: event.target.value });
							}}
							/>
						) : (
							<input
							id="openApiKey"
							type="password"
							name="openApiKey"
							autoComplete="off"
							value={settings.openApiKey}
							onChange={(event) => {
								setSettings({ ...settings, openApiKey: event.target.value });
							}}
							/>
						)}
						<button type="button" onClick={() => setIsOpenApiKeyVisible(!isOpenApiKeyVisible)}>
						{isOpenApiKeyVisible ? 'Hide' : 'Show'} Key
						</button>
					</td>
				</tr>
				{/* Select element with three options for AI type public, or logged in */}
				<tr>
					<td>
						<label htmlFor="aiType">AI Access Level</label>
						<select
							id="aiType"
							name="aiType"
							value={settings.allowPublicAI}
							onChange={(event) => {
								setSettings({ ...settings, allowPublicAI: event.target.value });
							}}
						>
							<option value="public">Public</option>
							<option value="loggedIn">Logged In</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><input id="save" className="button button-small button-primary" type="submit" name="enabled" onClick={onSave} /></td>
				</tr>
			</tbody>
		</table>
		</form>
		</>	
	);
}
