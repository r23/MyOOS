import * as THREE from 'three';
import { USDZLoader } from 'three/examples/jsm/loaders/USDZLoader';
import React, { Suspense, useRef, useState, useEffect } from 'react';
import { Canvas, useLoader, useFrame, useThree } from '@react-three/fiber';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import {
	OrthographicCamera,
	PerspectiveCamera,
	OrbitControls,
	useAnimations,
} from '@react-three/drei';
import { VRM, VRMUtils, VRMSchema, VRMLoaderPlugin  } from '@pixiv/three-vrm'
import { GLTFAudioEmitterExtension } from 'three-omi';


function ThreeObject( props ) {
	const [ url, set ] = useState( props.url );
	const {scene} = useThree();
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

export default function ThreeObjectEdit( props ) {
	return (
		<>
			<Canvas
				camera={ { fov: 40, zoom: props.zoom, position: [ 0, 0, 20 ] } }
				shadowMap
				style={ {
					backgroundColor: props.backgroundColor,
					margin: '0 Auto',
					height: '500px',
					width: '90%',
				} }
			>
				<PerspectiveCamera fov={40} position={[0,0,20]} makeDefault zoom={props.zoom} />
				<ambientLight intensity={ 0.5 } />
				<directionalLight
					intensity={ 0.6 }
					position={ [ 0, 2, 2 ] }
					shadow-mapSize-width={ 2048 }
					shadow-mapSize-height={ 2048 }
					castShadow
				/>
					{ props.url && (
						<Suspense fallback={ null }>
								<ThreeObject
									url={ props.url }
									positionX={ props.positionX }
									positionY={ props.positionY }
									rotationY={ props.rotationY }
									scale={ props.scale }
									zoom={props.zoom}
									animations={ props.animations }
								/>
						</Suspense>
					) }
				<OrbitControls enableZoom={ props.hasZoom } />
			</Canvas>
			{ props.hasTip && (
				<p className="three-object-block-tip">Click and drag ^</p>
			) }
		</>
	);
}
