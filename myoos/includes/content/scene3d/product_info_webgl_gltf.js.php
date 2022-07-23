<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: webgl_loader_gltf_extension.html
   ----------------------------------------------------------------------
   JavaScript 3D library
   http://threejs.org

   Copyright © 2010-2021 three.js authors
   ----------------------------------------------------------------------
   The MIT License
   ----------------------------------------------------------------------
 */
?>

<script type="module">

    import * as THREE from './js/three/three.module.js';

    import { GUI } from './jsm/libs/dat.gui.module.js';
    import { OrbitControls } from './jsm/controls/OrbitControls.js';
    import { GLTFLoader } from './jsm/loaders/GLTFLoader.js';
    import { DRACOLoader } from './jsm/loaders/DRACOLoader.js';
    import { RGBELoader } from './jsm/loaders/RGBELoader.js';

    let orbitControls;
    let container, camera, scene, renderer, loader;
    let gltf, background, envMap, mixer, gui, extensionControls;

    const clock = new THREE.Clock();

            const scenes = {
                Boombox: {
                    name: '<?php echo $name; ?>',
                    url: './media/models/gltf/<?php echo $url; ?>',
                    author: '<?php echo $model_info['models_author']; ?>',
                    authorURL: '<?php echo $model_info['models_author_url']; ?>',
                    cameraPos: new THREE.Vector3( <?php echo $model_info['models_camera_pos']; ?> ),
                    objectRotation: new THREE.Euler( <?php echo $model_info['models_object_rotation']; ?> ),
                    <?php if ($model_info['models_add_env_map'] == 'true') {
    echo 'addEnvMap: true,';
} ?>
                    extensions: [ 'glTF', 'glTF-pbrSpecularGlossiness', 'glTF-Binary', 'glTF-Draco' ]
                },
            };

            const state = {
                scene: Object.keys( scenes )[ 0 ],
                extension: scenes[ Object.keys( scenes )[ 0 ] ].extensions[ 0 ],
                playAnimation: true
            };

            function onload() {

                container = document.getElementById( 'container' );

                renderer = new THREE.WebGLRenderer( { antialias: true } );
                renderer.setPixelRatio( window.devicePixelRatio );
                renderer.setSize( window.innerWidth, window.innerHeight );
                renderer.outputEncoding = THREE.sRGBEncoding;
                renderer.toneMapping = THREE.ACESFilmicToneMapping;
                renderer.toneMappingExposure = 1;
                renderer.physicallyCorrectLights = true;
                container.appendChild( renderer.domElement );

                window.addEventListener( 'resize', onWindowResize, false );

                // Load background and generate envMap

                new RGBELoader()
                    .setPath( 'media/textures/equirectangular/' )
                    .load( '<?php echo $model_info['models_hdr']; ?>', function ( texture ) {

                        texture.mapping = THREE.EquirectangularReflectionMapping;

                        envMap = texture;
                        background = texture;

                        //

                        // buildGUI();
                        initScene( scenes[ state.scene ] );
                        animate();

                    } );

            }


            function initScene( sceneInfo ) {

                const descriptionEl = document.getElementById( 'description' );

                if ( sceneInfo.author && sceneInfo.authorURL ) {

                    descriptionEl.innerHTML = sceneInfo.name + ' by <a href="' + sceneInfo.authorURL + '" target="_blank" rel="noopener">' + sceneInfo.author + '</a>';

                }

                scene = new THREE.Scene();
                scene.background = new THREE.Color( 0x222222 );

                camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 0.001, 1000 );
                scene.add( camera );

                let spot1;

                if ( sceneInfo.addLights ) {

                    const ambient = new THREE.AmbientLight( 0x222222 );
                    scene.add( ambient );

                    const directionalLight = new THREE.DirectionalLight( 0xdddddd, 4 );
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

                    const groundMaterial = new THREE.MeshPhongMaterial( { color: 0xFFFFFF } );
                    const ground = new THREE.Mesh( new THREE.PlaneGeometry( 512, 512 ), groundMaterial );
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

                const dracoLoader = new DRACOLoader();
                dracoLoader.setDecoderPath( 'js/libs/draco/gltf/' );
                loader.setDRACOLoader( dracoLoader );

                // let url = sceneInfo.url.replace( /%s/g, state.extension );
                let url = sceneInfo.url;
                
                if ( state.extension === 'glTF-Binary' ) {

                    url = url.replace( '.gltf', '.glb' );

                }

                const loadStartTime = performance.now();

                loader.load( url, function ( data ) {

                    gltf = data;

                    const object = gltf.scene;

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

                    const animations = gltf.animations;                

                    if ( animations && animations.length ) {

                        mixer = new THREE.AnimationMixer( object );

                        for ( let i = 0; i < animations.length; i ++ ) {

                            const animation = animations[ i ];

                            // There's .3333 seconds junk at the tail of the Monster animation that
                            // keeps it from looping cleanly. Clip it at 3 seconds
                            if ( sceneInfo.animationTime ) {

                                animation.duration = sceneInfo.animationTime;

                            }

                            const action = mixer.clipAction( animation );

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

                renderer.setSize( window.innerWidth, window.innerHeight );

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

                const sceneCtrl = gui.add( state, 'scene', Object.keys( scenes ) );
                sceneCtrl.onChange( reload );

                const animCtrl = gui.add( state, 'playAnimation' );
                animCtrl.onChange( toggleAnimations );

                updateGUI();

            }            

            function updateGUI() {

                if ( extensionControls ) extensionControls.remove();

                const sceneInfo = scenes[ state.scene ];

                if ( sceneInfo.extensions.indexOf( state.extension ) === - 1 ) {

                    state.extension = sceneInfo.extensions[ 0 ];

                }

                extensionControls = gui.add( state, 'extension', sceneInfo.extensions );
                extensionControls.onChange( reload );

            }

            function toggleAnimations() {

                for ( let i = 0; i < gltf.animations.length; i ++ ) {

                    const clip = gltf.animations[ i ];
                    const action = mixer.existingAction( clip );

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
<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: webgl_loader_gltf.html
   https://github.com/mrdoob/three.js/blob/master/examples/webgl_loader_gltf.html
   ----------------------------------------------------------------------
   JavaScript 3D library
   http://threejs.org

   Copyright © 2010-2022 three.js authors
   ----------------------------------------------------------------------
   The MIT License
   ----------------------------------------------------------------------
 */
?>


##



		<div id="info">
			<a href="https://threejs.org" target="_blank" rel="noopener">three.js</a> - GLTFLoader<br />
			Battle Damaged Sci-fi Helmet by
			<a href="https://sketchfab.com/theblueturtle_" target="_blank" rel="noopener">theblueturtle_</a><br />
			<a href="https://hdrihaven.com/hdri/?h=royal_esplanade" target="_blank" rel="noopener">Royal Esplanade</a> by <a href="https://hdrihaven.com/" target="_blank" rel="noopener">HDRI Haven</a>
		</div>

		<!-- Import maps polyfill -->
		<!-- Remove this when import maps will be widely supported -->
		<script async src="https://unpkg.com/es-module-shims@1.3.6/dist/es-module-shims.js"></script>

		<script type="importmap">
			{
				"imports": {
					"three": "../build/three.module.js"
				}
			}
		</script>

		<script type="module">

			import * as THREE from 'three';

			import { OrbitControls } from './jsm/controls/OrbitControls.js';
			import { GLTFLoader } from './jsm/loaders/GLTFLoader.js';
			import { RGBELoader } from './jsm/loaders/RGBELoader.js';

			let camera, scene, renderer;

			init();
			render();

			function init() {

				const container = document.createElement( 'div' );
				document.body.appendChild( container );

				camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 0.25, 20 );
				camera.position.set( - 1.8, 0.6, 2.7 );

				scene = new THREE.Scene();

				new RGBELoader()
					.setPath( 'textures/equirectangular/' )
					.load( 'royal_esplanade_1k.hdr', function ( texture ) {

						texture.mapping = THREE.EquirectangularReflectionMapping;

						scene.background = texture;
						scene.environment = texture;

						render();

						// model

						const loader = new GLTFLoader().setPath( 'models/gltf/DamagedHelmet/glTF/' );
						loader.load( 'DamagedHelmet.gltf', function ( gltf ) {

							scene.add( gltf.scene );

							render();

						} );

					} );

				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.toneMapping = THREE.ACESFilmicToneMapping;
				renderer.toneMappingExposure = 1;
				renderer.outputEncoding = THREE.sRGBEncoding;
				container.appendChild( renderer.domElement );

				const controls = new OrbitControls( camera, renderer.domElement );
				controls.addEventListener( 'change', render ); // use if there is no animation loop
				controls.minDistance = 2;
				controls.maxDistance = 10;
				controls.target.set( 0, 0, - 0.2 );
				controls.update();

				window.addEventListener( 'resize', onWindowResize );

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

				render();

			}

			//

			function render() {

				renderer.render( scene, camera );

			}

		</script>