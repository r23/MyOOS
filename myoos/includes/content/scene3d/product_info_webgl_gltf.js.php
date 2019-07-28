<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: webgl_loader_gltf_extension.html 
   ----------------------------------------------------------------------
   JavaScript 3D library
   http://threejs.org

   Copyright © 2010-2019 three.js authors
   ----------------------------------------------------------------------
   The MIT License
   ---------------------------------------------------------------------- */
?>

<script type="module">

	import * as THREE from './js/three/three.module.js';

	import Stats from './jsm/libs/stats.module.js';

	import { GUI } from './jsm/libs/dat.gui.module.js';
	import { OrbitControls } from './jsm/controls/OrbitControls.js';
	import { GLTFLoader } from './jsm/loaders/GLTFLoader.js';
	import { DDSLoader } from './jsm/loaders/DDSLoader.js';
	import { DRACOLoader } from './jsm/loaders/DRACOLoader.js';
	import { RGBELoader } from './jsm/loaders/RGBELoader.js';
	import { EquirectangularToCubeGenerator } from './jsm/loaders/EquirectangularToCubeGenerator.js';
	import { PMREMGenerator } from './jsm/pmrem/PMREMGenerator.js';
	import { PMREMCubeUVPacker } from './jsm/pmrem/PMREMCubeUVPacker.js';

	var orbitControls;
	var container, camera, scene, renderer, loader;
	var gltf, background, envMap, mixer, gui, extensionControls;

	var clock = new THREE.Clock();


	var scenes = {
				Boombox: {
					name: '<?php echo $name; ?>',
					url: './media/models/gltf/<?php echo $url; ?>',
					author: '<?php echo $models['models_author']; ?>',
					authorURL: '<?php echo $models['models_author_url']; ?>',
					cameraPos: new THREE.Vector3( <?php echo $models['models_camera_pos']; ?> ),
					objectRotation: new THREE.Euler( <?php echo $models['models_object_rotation']; ?> ),
					<?php if ($models['models_add_lights'] == 'true') echo 'addLights: true,'; ?>	
					<?php if ($models['models_add_ground'] == 'true') echo 'addGround: true,'; ?>	
					<?php if ($models['models_shadows'] == 'true') echo 'shadows: true,'; ?>							
					<?php if ($models['models_add_env_map'] == 'true') echo 'addEnvMap: true,'; ?>					
					extensions: [ 'glTF', 'glTF-pbrSpecularGlossiness', 'glTF-Binary', 'glTF-dds' ]
				},
	};

			var state = {
				scene: Object.keys( scenes )[ 0 ],
				extension: scenes[ Object.keys( scenes )[ 0 ] ].extensions[ 0 ],
				playAnimation: true
			};

			function onload() {

				container = document.getElementById( 'scene3d' );

				var CANVAS_WIDTH = 1024;
				var CANVAS_HEIGHT = 576;


				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( CANVAS_WIDTH, CANVAS_HEIGHT );
				renderer.gammaOutput = true;
				renderer.physicallyCorrectLights = true;
				container.appendChild( renderer.domElement );

				window.addEventListener( 'resize', onWindowResize, false );

				// Load background and generate envMap

				new RGBELoader()
					.setType( THREE.UnsignedByteType )
					.setPath( 'media/textures/equirectangular/' )
					.load( '<?php echo $models['models_hdr']; ?>', function ( texture ) {

						var cubeGenerator = new EquirectangularToCubeGenerator( texture, { resolution: 1024 } );
						cubeGenerator.update( renderer );

						background = cubeGenerator.renderTarget;

						var pmremGenerator = new PMREMGenerator( cubeGenerator.renderTarget.texture );
						pmremGenerator.update( renderer );

						var pmremCubeUVPacker = new PMREMCubeUVPacker( pmremGenerator.cubeLods );
						pmremCubeUVPacker.update( renderer );

						envMap = pmremCubeUVPacker.CubeUVRenderTarget.texture;

						pmremGenerator.dispose();
						pmremCubeUVPacker.dispose();

						//

						// buildGUI();
						initScene( scenes[ state.scene ] );
						animate();

					} );

			}

			function initScene( sceneInfo ) {

				var descriptionEl = document.getElementById( 'description' );

				if ( sceneInfo.author && sceneInfo.authorURL ) {

					descriptionEl.innerHTML = sceneInfo.name + ' by <a href="' + sceneInfo.authorURL + '" target="_blank" rel="noopener">' + sceneInfo.author + '</a>';

				}

				scene = new THREE.Scene();
				scene.background = new THREE.Color( 0x222222 );

				camera = new THREE.PerspectiveCamera( 45, container.offsetWidth / container.offsetHeight, 0.001, 1000 );
				scene.add( camera );

				var spot1;

				if ( sceneInfo.addLights ) {

					var ambient = new THREE.AmbientLight( 0x222222 );
					scene.add( ambient );

					var directionalLight = new THREE.DirectionalLight( 0xdddddd, 4 );
					directionalLight.position.set( 0, 0, 1 ).normalize();
					scene.add( directionalLight );

					spot1 = new THREE.SpotLight( 0xffffff, 1 );
					spot1.position.set( 5, 10, 5 );
					spot1.angle = 0.50;
					spot1.penumbra = 0.75;
					spot1.intensity = 100;
					spot1.decay = 2;

					if ( sceneInfo.shadows ) {

						spot1.castShadow = true;
						spot1.shadow.bias = 0.0001;
						spot1.shadow.mapSize.width = 2048;
						spot1.shadow.mapSize.height = 2048;

					}

					scene.add( spot1 );

				}

				if ( sceneInfo.shadows ) {

					renderer.shadowMap.enabled = true;
					renderer.shadowMap.type = THREE.PCFSoftShadowMap;

				}

				// TODO: Reuse existing OrbitControls, GLTFLoaders, and so on

				orbitControls = new OrbitControls( camera, renderer.domElement );

				if ( sceneInfo.addGround ) {

					var groundMaterial = new THREE.MeshPhongMaterial( { color: 0xFFFFFF } );
					var ground = new THREE.Mesh( new THREE.PlaneBufferGeometry( 512, 512 ), groundMaterial );
					ground.receiveShadow = !! sceneInfo.shadows;

					if ( sceneInfo.groundPos ) {

						ground.position.copy( sceneInfo.groundPos );

					} else {

						ground.position.z = - 70;

					}

					ground.rotation.x = - Math.PI / 2;

					scene.add( ground );

				}

				loader = new GLTFLoader();

				DRACOLoader.setDecoderPath( 'js/libs/draco/gltf/' );
				loader.setDRACOLoader( new DRACOLoader() );
				loader.setDDSLoader( new DDSLoader() );

				//	var url = sceneInfo.url.replace( /%s/g, state.extension );
				var url = sceneInfo.url;
				if ( state.extension === 'glTF-Binary' ) {

					url = url.replace( '.gltf', '.glb' );

				}

				var loadStartTime = performance.now();

				loader.load( url, function ( data ) {

					gltf = data;

					var object = gltf.scene;

					// console.info( 'Load time: ' + ( performance.now() - loadStartTime ).toFixed( 2 ) + ' ms.' );

					if ( sceneInfo.cameraPos ) {

						camera.position.copy( sceneInfo.cameraPos );

					}

					if ( sceneInfo.center ) {

						orbitControls.target.copy( sceneInfo.center );

					}

					if ( sceneInfo.objectPosition ) {

						object.position.copy( sceneInfo.objectPosition );

						if ( spot1 ) {

							spot1.target.position.copy( sceneInfo.objectPosition );

						}

					}

					if ( sceneInfo.objectRotation ) {

						object.rotation.copy( sceneInfo.objectRotation );

					}

					if ( sceneInfo.objectScale ) {

						object.scale.copy( sceneInfo.objectScale );

					}

					if ( sceneInfo.addEnvMap ) {

						object.traverse( function ( node ) {

							if ( node.material && ( node.material.isMeshStandardMaterial ||
								 ( node.material.isShaderMaterial && node.material.envMap !== undefined ) ) ) {

								node.material.envMap = envMap;
								node.material.envMapIntensity = 1.5; // boombox seems too dark otherwise

							}

						} );

						scene.background = background;

					}

					object.traverse( function ( node ) {

						if ( node.isMesh || node.isLight ) node.castShadow = true;

					} );

					var animations = gltf.animations;

					if ( animations && animations.length ) {

						mixer = new THREE.AnimationMixer( object );

						for ( var i = 0; i < animations.length; i ++ ) {

							var animation = animations[ i ];

							// There's .3333 seconds junk at the tail of the Monster animation that
							// keeps it from looping cleanly. Clip it at 3 seconds
							if ( sceneInfo.animationTime ) {

								animation.duration = sceneInfo.animationTime;

							}

							var action = mixer.clipAction( animation );

							if ( state.playAnimation ) action.play();

						}

					}

					scene.add( object );
					onWindowResize();

				}, undefined, function ( error ) {

					console.error( error );

				} );

			}

			function onWindowResize() {

				camera.aspect = container.offsetWidth / container.offsetHeight;
				camera.updateProjectionMatrix();
 
				const container = renderer.domElement;
				const width = container.clientWidth;
				const height = container.clientHeight;
				const needResize = container.width !== width || container.height !== height;
				if (needResize) {
					renderer.setSize(width, height, false);
				}
				return needResize;
				
			}

			function animate() {

				requestAnimationFrame( animate );

				if ( mixer ) mixer.update( clock.getDelta() );

				orbitControls.update();

				render();

			}

			function render() {

				renderer.render( scene, camera );

			}

			function buildGUI() {

				gui = new GUI( { width: 0 } );
				gui.domElement.parentElement.style.zIndex = 101;

				var sceneCtrl = gui.add( state, 'scene', Object.keys( scenes ) );
				sceneCtrl.onChange( reload );

				var animCtrl = gui.add( state, 'playAnimation' );
				animCtrl.onChange( toggleAnimations );

				updateGUI();

			}

			function updateGUI() {

				if ( extensionControls ) extensionControls.remove();

				var sceneInfo = scenes[ state.scene ];

				if ( sceneInfo.extensions.indexOf( state.extension ) === - 1 ) {

					state.extension = sceneInfo.extensions[ 0 ];

				}

				extensionControls = gui.add( state, 'extension', sceneInfo.extensions );
				extensionControls.onChange( reload );

			}

			function toggleAnimations() {

				for ( var i = 0; i < gltf.animations.length; i ++ ) {

					var clip = gltf.animations[ i ];
					var action = mixer.existingAction( clip );

					state.playAnimation ? action.play() : action.stop();

				}

			}

			function reload() {

				if ( loader && mixer ) mixer.stopAllAction();

				// updateGUI();
				initScene( scenes[ state.scene ] );

			}

			onload();
		</script>
