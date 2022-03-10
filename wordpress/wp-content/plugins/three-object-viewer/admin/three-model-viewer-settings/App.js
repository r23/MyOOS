import { useState, useEffect } from "@wordpress/element";

//Main component for admin page app
export default function App({ getSettings, updateSettings }) {
	//Track settings state
	const [settings, setSettings] = useState({});
	//Use to show loading spinner

	const [isLoading, setIsLoading] = useState(true);
	//When app loads, get settings
	useEffect(() => {
		getSettings().then((r) => {
			setSettings(r);
			setIsLoading(false);
		});
	}, [getSettings, setSettings]);

    //Function to update settings via API
	const onSave = () => {
		updateSettings(settings).then((r) => {
			setSettings(r);
		});
	};

	//Show a spinner if loading
	if (isLoading) {
		return <div className="spinner" style={{ visibility: "visible" }} />;
	}

	//Show settings if not loading
	return (
		<div>
			<div>{settings.enabled ? "Enabled" : "Not enabled"}</div>
			<div>
				<label htmlFor="enabled">Enable</label>
				<input
					id="enabled"
					type="checkbox"
					name="enabled"
					value={settings.enabled}
					onChange={() => {
						setSettings({ ...settings, enabled: !settings.enabled });
					}}
				/>
			</div>
			<div>
				<label htmlFor="save">Save</label>
				<input id="save" type="submit" name="enabled" onClick={onSave} />
			</div>
		</div>
	);
}
