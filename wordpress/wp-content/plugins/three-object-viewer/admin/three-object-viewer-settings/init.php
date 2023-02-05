<?php
//Register assets for 3OV Settings
add_action('init', function () {
    wp_enqueue_media();
    $handle = 'three-object-viewer-settings';
    if( file_exists(dirname(__FILE__, 3). "/build/admin-page-$handle.asset.php" ) ){
        $assets = include dirname(__FILE__, 3). "/build/admin-page-$handle.asset.php";
        $dependencies = $assets['dependencies'];
        wp_register_script(
            $handle,
            plugins_url("/build/admin-page-$handle.js", dirname(__FILE__, 2)),
            $dependencies,
            $assets['version']
        );
        $three_object_plugin = plugins_url() . '/three-object-viewer/build/';
        wp_localize_script( $handle, 'threeObjectPlugin', $three_object_plugin );
    
    }
});

//Register API Route to read and update settings.
add_action('rest_api_init', function (){
    //Register route
    register_rest_route( 'three-object-viewer/v1' , '/three-object-viewer-settings/', [
        //Endpoint to get settings from
        [
            'methods' => ['GET'],
			'callback' => function($request){
				return rest_ensure_response( [
					'enabled' => get_option( '3ov_ai_enabled', false ),
					'networkWorker' => get_option( '3ov_mp_networkWorker', '' ),
					'openApiKey' => three_decrypt ( get_option( '3ov_ai_openApiKey', '' ) ),
					'allowPublicAI' => get_option( '3ov_ai_allow', '' ),
					'defaultVRM' => get_option( '3ov_defaultVRM', '' ),
				], 200);
			},
					'permission_callback' => function(){
                return current_user_can('manage_options');
            }
        ],
        //Endpoint to update settings at
        [
            'methods' => ['POST'],
			'callback' => function($request){
				$data = $request->get_json_params();
				update_option( '3ov_ai_enabled', $data['enabled'] );
				update_option( '3ov_mp_networkWorker', $data['networkWorker'] );
				update_option( '3ov_defaultVRM', $data['defaultVRM'] );
				update_option( '3ov_ai_allow', $data['allowPublicAI'] );
				update_option( '3ov_ai_openApiKey', three_encrypt( $data['openApiKey'] ) );
				return rest_ensure_response( $data, 200);
			},
			'permission_callback' => function(){
                return current_user_can('manage_options');
            }
        ]
    ]);
});

//Enqueue assets for 3OV Settings on admin page only
add_action('admin_enqueue_scripts', function ($hook) {
    if ('toplevel_page_three-object-viewer-settings' != $hook) {
        return;
    }
    wp_enqueue_script('three-object-viewer-settings');
});

//Register 3OV Settings menu page
add_action('admin_menu', function () {
    add_menu_page(
        __('3OV Settings', 'three-object-viewer'),
        __('3OV Settings', 'three-object-viewer'),
        'manage_options',
        'three-object-viewer-settings',
        function () {
            //React root
            echo '<div id="three-object-viewer-settings"></div>';
        }
    );
});

function three_encrypt($value = ""){
    if( empty( $value ) ) {
        return $value;
    }
    
    $output = null;
    $secret_key = defined('AUTH_KEY') ? AUTH_KEY : "";
    $secret_iv = defined('SECURE_AUTH_KEY') ? SECURE_AUTH_KEY : "";
    $key = hash('sha256',$secret_key);
    $iv = substr(hash('sha256',$secret_iv),0,16);
    return base64_encode(openssl_encrypt($value,"AES-256-CBC",$key,0,$iv));
}

function three_decrypt($value = ""){
    if( empty( $value ) ) {
        return $value;
    }

    $output = null;
    $secret_key = defined('AUTH_KEY') ? AUTH_KEY : "";
    $secret_iv = defined('SECURE_AUTH_KEY') ? SECURE_AUTH_KEY : "";
    $key = hash('sha256',$secret_key);
    $iv = substr(hash('sha256',$secret_iv),0,16);

    return openssl_decrypt(base64_decode($value),"AES-256-CBC",$key,0,$iv);
}