import * as THREE from 'three';
import React, { Suspense, useRef, useState, useEffect } from 'react';
import { Canvas, useLoader, useFrame, useThree } from '@react-three/fiber';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import { Physics, RigidBody } from "@react-three/rapier";
import { USDZLoader } from 'three/examples/jsm/loaders/USDZLoader';
import { GLTFGoogleTiltBrushMaterialExtension } from "three-icosa";

import {
	OrthographicCamera,
	OrbitControls,
	useAnimations,
} from '@react-three/drei';
import { GLTFAudioEmitterExtension } from 'three-omi';
import {
	VRCanvas,
	ARCanvas,
	DefaultXRControllers,
	Hands,
} from '@react-three/xr';
import { VRM, VRMUtils, VRMSchema, VRMLoaderPlugin  } from '@pixiv/three-vrm'
import TeleportTravel from './TeleportTravel';
import { PlaneGeometry } from 'three';

function SavedObject( props ) {
	const [ url, set ] = useState( props.url );
	useEffect( () => {
		setTimeout( () => set( props.url ), 2000 );
	}, [] );
	const [ listener ] = useState( () => new THREE.AudioListener() );

	useThree( ( { camera } ) => {
		camera.add( listener );
	} );

	// USDZ loader.
	if(props.url.split(/[#?]/)[0].split('.').pop().trim() === "usdz") {

		const usdz = useLoader( USDZLoader, url);

        return <primitive scale={[ props.scale, props.scale, props.scale ]} position={[ 0, props.positionY, 0 ]} rotation={[ 0, props.rotationY, 0 ]} object={ usdz } />;
	}

	const gltf = useLoader( GLTFLoader, url, ( loader ) => {
		if(openbrushEnabled === true){
			loader.register(
				(parser) =>
					new GLTFGoogleTiltBrushMaterialExtension(parser, openbrushDirectory)
			);	
		}
		loader.register(
			( parser ) => new GLTFAudioEmitterExtension( parser, listener )
		);
		loader.register( ( parser ) => {

            return new VRMLoaderPlugin( parser );
        } );
	} );

	const { actions } = useAnimations( gltf.animations, gltf.scene );

	const animationList = props.animations ? props.animations.split( ',' ) : '';
	useEffect( () => {
		if ( animationList ) {
			animationList.forEach( ( name ) => {
				if ( Object.keys( actions ).includes( name ) ) {
					actions[ name ].play();
				}
			} );
		}
	}, [] );

	const generator = gltf.asset.generator;
	if (String(generator).includes("Tilt Brush")) {
		gltf.scene.position.set( 0, props.positionY, 0 );
		gltf.scene.rotation.set( 0, props.rotationY, 0 );
		gltf.scene.scale.set( props.scale, props.scale, props.scale );
	
		return (
			<primitive 
			object={gltf.scene} />
		);
	} 

    if(gltf?.userData?.gltfExtensions?.VRM){
			const vrm = gltf.userData.vrm;
			vrm.scene.position.set( 0, props.positionY, 0 );
			VRMUtils.rotateVRM0( vrm );
			const rotationVRM = vrm.scene.rotation.y + parseFloat(props.rotationY);
			vrm.scene.rotation.set( 0, rotationVRM, 0 );
			vrm.scene.scale.set( props.scale, props.scale, props.scale );
			return <primitive object={ vrm.scene } />;    
    }
    gltf.scene.position.set( 0, props.positionY, 0 );
    gltf.scene.rotation.set( 0, props.rotationY, 0 );
    gltf.scene.scale.set( props.scale, props.scale, props.scale );
	return <primitive object={ gltf.scene } />;
}

function Floor( props ) {
	return (
		<mesh position={ [ 0, -2, 0 ] } rotation={ [ -Math.PI / 2, 0, 0 ] } { ...props }>
			<PlaneGeometry args={ [ 1000, 1000 ] } attach="geometry" />
			<meshBasicMaterial
				opacity={ 0 }
				transparent={ true }
				attach="material"
			/>
		</mesh>
	);
}

export default function ThreeObjectFront( props ) {
	if ( props.deviceTarget === 'vr' ) {
		return (
			<>
				<VRCanvas
					camera={ { fov: 40, zoom: props.zoom, position: [ 0, 0, 20 ] } }
					shadowMap
					style={ {
						backgroundColor: props.backgroundColor,
						margin: '0 Auto',
						height: '500px',
						width: '90%',
					} }
				>
					<Hands />
					<DefaultXRControllers />
					<ambientLight intensity={ 0.5 } />
					<directionalLight
						intensity={ 0.6 }
						position={ [ 0, 2, 2 ] }
						shadow-mapSize-width={ 2048 }
						shadow-mapSize-height={ 2048 }
						castShadow
					/>			
					<Suspense fallback={ null }>
					<Physics>			
							{ props.threeUrl && (
								<>						
									<TeleportTravel useNormal={ false }>
										<RigidBody type="kinematicPosition">
											<SavedObject
											positionY={ props.positionY }
											rotationY={ props.rotationY }
											url={ props.threeUrl }
											color={ props.backgroundColor }
											hasZoom={ props.hasZoom }
											scale={ props.scale }
											hasTip={ props.hasTip }
											animations={ props.animations }
											/>
										</RigidBody>
									</TeleportTravel>
									<RigidBody>
											<Floor rotation={[-Math.PI / 2, 0, 0]} />
									</RigidBody>
								</>
							) }
					</Physics>
					</Suspense>
					<OrbitControls
						enableZoom={ props.hasZoom === '1' ? true : false }
					/>
				</VRCanvas>
				{ props.hasTip === '1' ? (
					<p className="three-object-block-tip">Click and drag ^</p>
				) : (
					<p></p>
				) }
			</>
		);
	}
	if ( props.deviceTarget === 'ar' ) {
		return (
			<>
				<ARCanvas
          			camera={ { fov: 40, zoom: props.zoom, position: [ 0, 0, 20 ] } }
					shadowMap
					style={ {
						backgroundColor: props.backgroundColor,
						margin: '0 Auto',
						height: '500px',
						width: '90%',
					} }
				>
					<ambientLight intensity={ 0.5 } />
					<directionalLight
						intensity={ 0.6 }
						position={ [ 0, 2, 2 ] }
						shadow-mapSize-width={ 2048 }
						shadow-mapSize-height={ 2048 }
						castShadow
					/>
					<Suspense fallback={ null }>
						{ props.threeUrl && (
							<SavedObject
								positionY={ props.positionY }
								rotationY={ props.rotationY }
								url={ props.threeUrl }
								color={ props.backgroundColor }
								hasZoom={ props.hasZoom }
								scale={ props.scale }
								hasTip={ props.hasTip }
								animations={ props.animations }
							/>
						) }
					</Suspense>
					<OrbitControls
						enableZoom={ props.hasZoom === '1' ? true : false }
					/>
				</ARCanvas>
				{ props.hasTip === '1' ? (
					<p className="three-object-block-tip">Click and drag ^</p>
				) : (
					<p></p>
				) }
			</>
		);
	}
	if ( props.deviceTarget === '2d' ) {
		return (
			<>
				<Canvas
          camera={ { fov: 40, position: [0, 0, 20], zoom: props.zoom} }
					shadowMap
					style={ {
						backgroundColor: props.backgroundColor,
						margin: '0 Auto',
						height: '500px',
						width: '90%',
					} }
				>
					<ambientLight intensity={ 0.5 } />
					<directionalLight
						intensity={ 0.6 }
						position={ [ 0, 2, 2 ] }
						shadow-mapSize-width={ 2048 }
						shadow-mapSize-height={ 2048 }
						castShadow
					/>
					<Suspense fallback={ null }>
						{ props.threeUrl && (
							<SavedObject
								positionY={ props.positionY }
								rotationY={ props.rotationY }
								url={ props.threeUrl }
								color={ props.backgroundColor }
								hasZoom={ props.hasZoom }
								scale={ props.scale }
								hasTip={ props.hasTip }
								animations={ props.animations }
							/>
						) }
					</Suspense>
					<OrbitControls
						enableZoom={ props.hasZoom === '1' ? true : false }
					/>
				</Canvas>
				{ props.hasTip === '1' ? (
					<p className="three-object-block-tip">Click and drag ^</p>
				) : (
					<p></p>
				) }
			</>
		);
	}
}
