import { Suspense, useState, useEffect } from "@wordpress/element";
import { Canvas, useLoader, useFrame, useThree } from '@react-three/fiber';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import { VRM, VRMUtils, VRMSchema, VRMLoaderPlugin  } from '@pixiv/three-vrm'
import {
	OrthographicCamera,
	OrbitControls,
	useAnimations,
} from '@react-three/drei';
import * as THREE from 'three';
import defaultProfileVRM from '../../inc/avatars/3ov_default_avatar.vrm';

function SavedObject( props ) {
	const [ url, set ] = useState( props.url );
	useEffect( () => {
		setTimeout( () => set( props.url ), 2000 );
	}, [] );
	const [ listener ] = useState( () => new THREE.AudioListener() );

	useThree( ( { camera } ) => {
		camera.add( listener );
	} );
	const fallbackURL = threeObjectPlugin + defaultProfileVRM;
	const playerURL = props.url ? props.url : fallbackURL;

	const someSceneState = useLoader( GLTFLoader, playerURL, ( loader ) => {
		loader.register( ( parser ) => {
            return new VRMLoaderPlugin( parser );
        } );
	} );

	if(someSceneState?.userData?.gltfExtensions?.VRM){
		const playerController = someSceneState.userData.vrm;
		VRMUtils.rotateVRM0( playerController );
		const rotationVRM = playerController.scene.rotation.y;
		playerController.scene.rotation.set( 0, rotationVRM, 0 );
		playerController.scene.scale.set( 3, 3, 3 );
		playerController.scene.position.set( 0, -2.5, 0 );
		return <><primitive object={ playerController.scene } /></>;    
	}
}

function CreateImage() {
	const { gl, scene, camera } = useThree()
	let getImageData = true;
	if(gl){
		if(getImageData == true) {
			window.setTimeout(function () {
				const url = gl.domElement.toDataURL();
				const link = document.getElementById('download');

				// const link = document.createElement('a');
				link.setAttribute('href', url);
				link.setAttribute('target', '_blank');
				link.setAttribute('download', "download the scene image");	
			}, 200);
			getImageData = false;
		}
	}
}

//Main component for admin page app
export default function App({ getSettings, updateSettings }) {

	let frame

	//Track settings state
	const [settings, setSettings] = useState({});
	//Use to show loading spinner
	const [defaultVRM, setDefaultVRM] = useState();
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
			setDefaultVRM(attachment.url);
			// Send the attachment URL to our custom image input field.
		  });
	  
		  
		// Finally, open the modal on click
		frame.open()
	}
	
	//Show a spinner if loading
	if (isLoading) {
		return <div className="spinner" style={{ visibility: "visible" }} />;
	}

	//Show settings if not loading
	return (
		<div>
			<div>
				<h2>Three Object Viewer Settings</h2>
			</div>
			<div><a id="download">download the thing</a></div>
			<div>
				<h3>Avatar and World Defaults</h3>
				<p>This avatar will be used for guest visitors or logged in users that have not set their main avatar in the user profile page.</p>
			</div>
			<div>
				<label htmlFor="defaultVRM"><b>Default VRM: </b></label>
				<Canvas
           			camera={ { fov: 40, position: [0, 0, 10], zoom: 1} }
					gl={{ preserveDrawingBuffer: true }}
					shadowMap
					style={ {
						backgroundColor: '#6a737c',
						margin: '0',
						height: '450px',
						width: '40%',
					} }
				>				
					<CreateImage/>
					<ambientLight intensity={ 0.5 } />
					<directionalLight
						intensity={ 0.6 }
						position={ [ 0, 2, 2 ] }
						shadow-mapSize-width={ 2048 }
						shadow-mapSize-height={ 2048 }
						castShadow
					/>
					<Suspense fallback={ null }>
						{defaultVRM ?
							<SavedObject
								positionY={ 0 }
								rotationY={ 0 }
								url={ defaultVRM }
								color={ '#6a737c' }
								hasZoom={ 1 }
								scale={ 1 }
								hasTip={ 0 }
								animations={ '' }
							/> :
							<SavedObject
								positionY={ 0 }
								rotationY={ 0 }
								color={ '#6a737c' }
								hasZoom={ 1 }
								scale={ 1 }
								hasTip={ 0 }
								animations={ '' }
							/>
						}
					</Suspense>
					<OrbitControls
						enableZoom={ 1 }
					/>
				</Canvas>
				<p>
				{ defaultVRM && defaultVRM }
				</p>
				<button type='button' onClick={runUploader}>
            		Select Default Avatar
        		</button>
			</div>
			<div>
				<h3>Network Settings</h3>
			</div>
			<div>
			<div>Network Settings</div>
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
				<label htmlFor="networkWorker">Cloudflare Worker URL</label>
				<input
					id="networkWorker"
					type="input"
					name="networkWorker"
					value={settings.networkWorker}
					onChange={() => {
						setSettings({ ...settings, networkWorker: !settings.networkWorker });
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
