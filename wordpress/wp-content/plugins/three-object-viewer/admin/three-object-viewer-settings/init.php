<?php
//Register assets for Model Viewer Settings
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
                    'data' => [
                        'enabled' => false,
                    ]
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
                return rest_ensure_response( $request->get_params(), 200);
            },
            'permission_callback' => function(){
                return current_user_can('manage_options');
            }
        ]
    ]);
});

//Enqueue assets for Model Viewer Settings on admin page only
add_action('admin_enqueue_scripts', function ($hook) {
    if ('toplevel_page_three-object-viewer-settings' != $hook) {
        return;
    }
    wp_enqueue_script('three-object-viewer-settings');
});

//Register Model Viewer Settings menu page
add_action('admin_menu', function () {
    add_menu_page(
        __('Model Viewer Settings', 'three-object-viewer'),
        __('Model Viewer Settings', 'three-object-viewer'),
        'manage_options',
        'three-object-viewer-settings',
        function () {
            //React root
            echo '<div id="three-object-viewer-settings"></div>';
        }
    );
});
