<?php
/**
* Plugin Name: Three Object Viewer
* Plugin URI: https://3ov.xyz/
* Description: A plugin for viewing 3D files with support for WebXR and Open Metaverse Interoperability GLTF Extensions.
* Version: 1.0.5
* Requires at least: 5.7
* Requires PHP:      7.1.0
* Author:            antpb
* Author URI:        https://antpb.com
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain:       three-object-viewer
* Domain Path:       /languages
*/

// Include three-object-block
include_once dirname( __FILE__ ) . '/blocks/three-object-block/init.php';

// Include environment
include_once dirname( __FILE__ ) . '/blocks/environment/init.php';

// Include model
include_once dirname( __FILE__ ) . '/blocks/model-block/init.php';

// Include sky
include_once dirname( __FILE__ ) . '/blocks/sky-block/init.php';

// Include image
include_once dirname( __FILE__ ) . '/blocks/three-image-block/init.php';

// Include image
include_once dirname( __FILE__ ) . '/blocks/three-video-block/init.php';

// Include audio
include_once dirname( __FILE__ ) . '/blocks/three-audio-block/init.php';

// Include portal
include_once dirname( __FILE__ ) . '/blocks/three-portal-block/init.php';

// Include html
include_once dirname( __FILE__ ) . '/blocks/three-text-block/init.php';

// Include spawn point
include_once dirname( __FILE__ ) . '/blocks/spawn-point-block/init.php';

/**
* Include the autoloader
*/
add_action( 'plugins_loaded', function () {
    if ( file_exists(__DIR__ . '/vendor/autoload.php' ) ) {
        include __DIR__ . '/vendor/autoload.php';
    }
}, 1 );

include_once dirname( __FILE__ ). '/inc/functions.php';
include_once dirname( __FILE__ ). '/inc/hooks.php';
// include_once dirname( __FILE__ ) . '/admin/three-object-viewer-settings/init.php';
