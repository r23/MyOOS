import React from 'react';
import { render } from '@wordpress/element';
import App from './App';
import apiFetch from '@wordpress/api-fetch';

window.addEventListener( 'load', async function () {
	//Endpoint URL
	const path = '/three-object-viewer/v1/three-object-viewer-settings/';

	//Get settings from the REST API endpoint
	const getSettings = async () => {
		let data = await apiFetch( {
			path,
			method: 'GET',
		} );
		return data;
	};

	//Update settings via the REST API endpoint
	const updateSettings = async ( data ) => {
		let updatedData = apiFetch( {
			path,
			data,
			method: 'POST',
		} );
		return updatedData;
	};

	render(
		<App getSettings={ getSettings } updateSettings={ updateSettings } />,
		document.getElementById( 'three-object-viewer-settings' )
	);
} );
