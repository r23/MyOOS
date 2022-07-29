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
<!-- Import maps polyfill -->
<!-- Remove this when import maps will be widely supported -->
<script async src="<?php echo OOS_HTTPS_SERVER . OOS_SHOP . 'js/es-module-shims/dist/es-module-shims.js'; ?>"></script>

<script type="importmap">
    {
        "imports": {
                "three": "./js/three/three.module.js"
        }
    }
</script>

<script type="module">

    import * as THREE from 'three';

    import { OrbitControls } from './jsm/controls/OrbitControls.js';
    import { GLTFLoader } from './jsm/loaders/GLTFLoader.js';
    import { RGBELoader } from './jsm/loaders/RGBELoader.js';
	import { DRACOLoader } from './jsm/loaders/DRACOLoader.js';

    let camera, scene, renderer;

    init();
    render();

    function init() {

        const container = document.createElement( 'div' );
        document.body.appendChild( container );

        camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 1000 );
        camera.position.set( <?php echo $model_info['models_camera_pos']; ?> );

        scene = new THREE.Scene();



        new RGBELoader()
            .setPath( 'media/textures/equirectangular/' )
            .load( '<?php echo $model_info['models_hdr']; ?>', function ( texture ) {

                texture.mapping = THREE.EquirectangularReflectionMapping;

                scene.background = texture;
                scene.environment = texture;

                render();

                // model

                const loader = new GLTFLoader().setPath( '<?php echo $model_path; ?>' );

				// Optional: Provide a DRACOLoader instance to decode compressed mesh data
				const dracoLoader = new DRACOLoader();
				dracoLoader.setDecoderPath( './js/libs/draco/' );
				loader.setDRACOLoader( dracoLoader );

				
                loader.load( '<?php echo $model_info['models_webgl_gltf']; ?>', function ( gltf ) {

					var model = gltf.scene;
					model.rotation.y = Math.PI;
					model.scale.setScalar( 150 );
					scene.add( model );


                   // scene.add( gltf.scene );

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
		controls.target.set( 0, 0.2, 0 );


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