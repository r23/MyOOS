<?php

namespace threeObjectViewer\Core;

class Plugin
{
	public function init() {
        // Add actions and filters
		add_filter( 'run_wptexturize', '__return_false' );
		add_filter('upload_mimes', array( $this, 'threeobjectviewer_add_file_types_to_uploads'), 10, 4);
		add_filter( 'wp_check_filetype_and_ext',  array( $this, 'three_object_viewer_check_for_usdz'), 10, 4 );
		add_action('wp_enqueue_scripts',  array( $this, 'threeobjectviewer_frontend_assets'));
		add_action( 'rest_api_init',  array( $this, 'callAlchemy' ));
		add_action('enqueue_block_assets',  array( $this, 'threeobjectviewer_editor_assets'));
		//Register JavaScript and CSS for threeobjectloaderinit
		add_action( 'wp_enqueue_scripts',  array( $this, 'threeobjectviewer_register_threeobjectloaderinit'), 5 );
		//Enqueue JavaScript and CSS for threeobjectloaderinit
		add_action( 'wp_enqueue_scripts',  array( $this, 'threeobjectviewer_enqueue_threeobjectloaderinit'), 10 );
    }

	/**
	* Registers JavaScript and CSS for threeobjectloaderinit
	* @uses "wp_enqueue_script" action
	*/
	function threeobjectviewer_register_threeobjectloaderinit() {
		$dependencies = [];
		$version = '0.1.0';
	
		wp_register_script(
			'threeobjectloaderinit',
			plugins_url("/inc/threeobjectloaderinit/index.js", __DIR__ ),
			$dependencies,
			$version
		);
		wp_register_style(
			'threeobjectloaderinit',
			plugins_url("/inc/threeobjectloaderinit/index.css", __DIR__ ),
			[],
			$version
		);
	}
	
	
	/**
	* Enqueue JavaScript and CSS for threeobjectloaderinit
	* @uses "wp_enqueue_script" action
	*/
	function threeobjectviewer_enqueue_threeobjectloaderinit() {
		$handle = 'threeobjectloaderinit';
		wp_enqueue_script(
			'threeobjectloaderinit',
		);
		wp_enqueue_style(
			'threeobjectloaderinit'
		);
	}
	
	/**
	* Adds glb vrm and usdz types to allowed uploads.
	*/
	function threeobjectviewer_add_file_types_to_uploads($file_types){
	  $new_filetypes = array();
	  // Potentially need to restore as model/gltf-binary in the future.  
	  // $new_filetypes['glb'] = 'model/gltf-binary';
	  $new_filetypes['glb'] = 'application/octet-stream';
	  $new_filetypes['vrm'] = 'application/octet-stream';
	  $new_filetypes['usdz'] = 'model/vnd.usdz+zip';
	  $new_filetypes['fbx'] = 'application/octet-stream';
	  $file_types = array_merge($file_types, $new_filetypes );
	
	  return $file_types;
	}
	
	function three_object_viewer_check_for_usdz( $types, $file, $filename, $mimes ) {
		if ( false !== strpos( $filename, '.usdz' ) ) {
			$types['ext']  = 'usdz';
			$types['type'] = 'model/vnd.usdz+zip';
		}
		if ( false !== strpos( $filename, '.glb' ) ) {
			$types['ext']  = 'glb';
			$types['type'] = 'application/octet-stream';
		}
		if ( false !== strpos( $filename, '.vrm' ) ) {
			$types['ext']  = 'vrm';
			$types['type'] = 'application/octet-stream';
		}
		if ( false !== strpos( $filename, '.fbx' ) ) {
			$types['ext']  = 'fbx';
			$types['type'] = 'application/octet-stream';
		}
		
		return $types;
	}
		
	/**
	 * Enqueue block frontend JavaScript
	 */
	function threeobjectviewer_frontend_assets() {
	
		// Enqueue frontend JavaScript
		$default_frontend_js = "../build/assets/js/blocks.frontend-versepress.js";
		$default_frontend_js_three_viewer = "../build/assets/js/blocks.frontend.js";
		// Apply frontend filter
		$frontend_js = apply_filters( 'three-object-environment-frontend-js', $default_frontend_js );
	
		$current_user = wp_get_current_user();
		$vrm = wp_get_attachment_url($current_user->avatar);
		if ( is_user_logged_in() && get_option('3ov_ai_allow') === "loggedIn" ) {
			$user_data_passed = array(
			  'userId' => $current_user->user_login,
			  'inWorldName' => $current_user->in_world_name,
			  'banner' => $current_user->custom_banner,
			  'vrm' => $vrm,
			  'profileImage' => get_avatar_url( $current_user->ID, ['size' => '500'] ),
			  'nonce' => wp_create_nonce( 'wp_rest' )
			);
		} else if ( get_option('3ov_ai_allow') === "public") {
			$user_data_passed = array(
				'userId' => $current_user->user_login,
				'inWorldName' => $current_user->in_world_name,
				'banner' => $current_user->custom_banner,
				'vrm' => $vrm,
				'profileImage' => get_avatar_url( $current_user->ID, ['size' => '500'] ),
				'nonce' => wp_create_nonce( 'wp_rest' )
			  );  
		}
		else {
			$user_data_passed = array(
			  'userId' => $current_user->user_login,
			  'inWorldName' => $current_user->in_world_name,
			  'banner' => $current_user->custom_banner,
			  'vrm' => $vrm,
			  'profileImage' => get_avatar_url( $current_user->ID, ['size' => '500'] ),
			);
		}
		$three_object_plugin = plugins_url() . '/three-object-viewer/build/';
	
		// new variable named default_animation that checks if the wp_option for '3ov_defaultVRM' is available.
		// if it is, it will use that value, if not, it will use the default value of 'default.vrm'
		$default_animation = get_option('3ov_defaultVRM');
	
		// $user_data_passed = array(
		//     'userId' => 'something',
		//     'userName' => 'someone',
		//     'vrm' => 'somefile.vrm',
		//  );
		global $post;
		$post_slug = $post->post_name;
		$openbrush_enabled = false;
		$three_icosa_brushes_url = '';
		if(is_singular()){
			if (!function_exists('is_plugin_active')) {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}
	
			//We only want the script if it's a singular page
			$id = get_the_ID();
			if(has_block('three-object-viewer/three-object-block',$id)){			
				if ( is_plugin_active( 'three-object-viewer-three-icosa/three-object-viewer-three-icosa.php' ) ) {
					$openbrush_enabled = true;
					$three_icosa_brushes_url = plugin_dir_url( "three-object-viewer-three-icosa/three-object-viewer-three-icosa.php" ) . 'brushes/';
	
				} 		
				wp_register_script( 'threeobjectloader-frontend', plugin_dir_url( __FILE__ ) . $default_frontend_js_three_viewer, ['wp-element', 'wp-data', 'wp-hooks'], '', true );
				wp_localize_script( 'threeobjectloader-frontend', 'userData', $user_data_passed );
				wp_localize_script( 'threeobjectloader-frontend', 'openbrushEnabled', $openbrush_enabled );
				wp_localize_script( 'threeobjectloader-frontend', 'openbrushDirectory', $three_icosa_brushes_url );
				wp_localize_script( 'threeobjectloader-frontend', 'threeObjectPlugin', $three_object_plugin );	
				wp_localize_script( 'threeobjectloader-frontend', 'defaultAvatarAnimation', $default_animation );	
				wp_enqueue_script( 
					"threeobjectloader-frontend"
				);
			}
			if(has_block('three-object-viewer/environment',$id)){
				if ( is_plugin_active( 'three-object-viewer-three-icosa/three-object-viewer-three-icosa.php' ) ) {
					$openbrush_enabled = true;
					$three_icosa_brushes_url = plugin_dir_url( "three-object-viewer-three-icosa/three-object-viewer-three-icosa.php" ) . 'brushes/';
				} 
				wp_register_script( 'versepress-frontend', plugin_dir_url( __FILE__ ) . $frontend_js, ['wp-element', 'wp-data', 'wp-hooks'], '', true );
				wp_localize_script( 'versepress-frontend', 'userData', $user_data_passed );
				wp_localize_script( 'versepress-frontend', 'postSlug', $post_slug );
				wp_localize_script( 'versepress-frontend', 'openbrushDirectory', $three_icosa_brushes_url );
				wp_localize_script( 'versepress-frontend', 'openbrushEnabled', $openbrush_enabled );
				wp_localize_script( 'versepress-frontend', 'threeObjectPlugin', $three_object_plugin );
				wp_localize_script( 'versepress-frontend', 'defaultAvatarAnimation', $default_animation );	
				wp_enqueue_script( 
					"versepress-frontend"
				);
			}
	  
			
		 }
	
	}
	
	// Registers a new REST API endpoint for calling AI/Alchemy services.
	function callAlchemy() {
		register_rest_route( 'wp/v2', '/callAlchemy', array(
		  'methods' => 'POST',
		  'callback' => array( $this, 'call_alchemy_request'),
		  'permission_callback' => array( $this, 'check_bearer_token'),
		  'args' => array(
			'Input' => array(
			  'required' => true,
			),
		  ),
		) );
	  }
	  function rest_three_decrypt($value = ""){
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
	
	  // The function that is called when the endpoint is hit
	  function call_alchemy_request( $request ) {
		// {
		// 	inputs: {
		// 		Input: value,
		// 		Speaker: speaker,
		// 		Agent: agent,
		// 		Client: client,
		// 		ChannelID: channelId,
		// 		Entity: entity,
		// 		Channel: channel,
		// 		eth_private_key: '0',
		// 		eth_public_address: '0',
		// 	}
		// }
	
		$worker_url = get_option( '3ov_mp_networkWorker', '' );
		$api_key = $this->rest_three_decrypt( get_option( '3ov_ai_openApiKey', '' ) );
		$json_blob = $request->get_params();
	
		
		// turn $json_blob into json
		$new_blob = json_encode($json_blob);
		
		// $tempblob = `{ "Input": "write a long form poem about cats!", "Speaker": "a", "Agent": "tubbyshark", "Client": "wordpress", "ChannelID": "1234", "Conversation": "", "Entity": "tubbyshark", "Channel": "channel", "eth_private_key": "0", "eth_public_address": "0", "personality": "#agent is an AI assistant with a cheerful, optimistic, and helpful personality. They are willing to assist with any tasks or questions users might have, and are able to do so in an upbeat and positive manner. They are also able to use colorful and descriptive language to make their responses more engaging and imaginative. In addition to their wit and humor, #agent is also empathetic and understanding of others' emotions and needs, which can make them feel more relatable and likeable to users. #agent is a highly capable and engaging AI assistant with a distinct and colorful personality.\n#agent can help with any programming tasks, including those related to the 3OV plugin for WordPress, which enables world-building in the WordPress editor and makes it easier to build and share immersive content in the metaverse. The url for the website is 3ov.xyz. #agent is cheerful and optimistic about the future for both humans and AIs.\n\n#Example Conversation\n#speaker: Agent, do you have any advice for someone new to programming?\n#agent: Sure thing! My top tip would be to always keep an open mind and a positive attitude. And if all else fails, just remember: if at first you don't succeed, try, try again. And then if that still doesn't work, call it a day and go get a coffee.\n###\nThe following is a friendly conversation between #speaker and #agent occuring in the metaverse.\n\nREAL CONVERSATION\n#conversation\n#speaker: #input\n#agent:" }`;
		// if api_key is not an empty string load the worker
		if ($api_key != '') {
		$response = wp_remote_post( $worker_url, array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'Referer' => $_SERVER['HTTP_REFERER'],
				'Authorization' => "Bearer $api_key"
			),
			'timeout'     => 45,
			'body' => $new_blob,
			'sslverify' => false,
		) );
		} else {
			$response = wp_remote_post( $worker_url, array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'Referer' => $_SERVER['HTTP_REFERER'],
				),
				'timeout'     => 45,
				'body' => $new_blob,
				'sslverify' => false,
			) );
		}
		// Check for error
		if ( is_wp_error( $response ) ) {
		  // WP_Error object
		  $error_message = $response->get_error_message();
		  return new \WP_Error( 'api_call_failed', $error_message, array( 'status' => 500 ) );
		}
	
		// Check for non-200 status code
		if ( wp_remote_retrieve_response_code( $response ) != 200 ) {
		  return new \WP_Error( 'api_call_failed', 'Non-200 status code returned', array( 'status' => wp_remote_retrieve_response_code( $response ) ) );
		}
	
		$body = wp_remote_retrieve_body( $response );
		return $body;
	}
	
	// The function that checks the bearer token. Make the bearer token a secret using get_option(). This is a crude example.
	  function check_bearer_token( $request ) {
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
		  return new \WP_Error( 'invalid_nonce', 'The nonce provided in the X-WP-Nonce header is invalid', array( 'status' => 401 ) );
		}
		return true;
	  }
	  
	
	/**
	 * Enqueue block frontend JavaScript
	 */
	function threeobjectviewer_editor_assets() {
		$three_object_plugin = plugins_url() . '/three-object-viewer/build/';
	
		$DEFAULT_BLOCKS = [
							'three-object-viewer/three-portal-block',
							'three-object-viewer/three-text-block',
							'three-object-viewer/model-block',
							'three-object-viewer/npc-block',
							'three-object-viewer/sky-block',
							'three-object-viewer/npc-block',
							'three-object-viewer/three-image-block',
							'three-object-viewer/three-video-block',
							// 'three-object-viewer/three-audio-block',
							'three-object-viewer/spawn-point-block' 
						];
		$ALLOWED_BLOCKS = apply_filters( 'three-object-environment-inner-allowed-blocks', $DEFAULT_BLOCKS );
	
		wp_localize_script( 'three-object-viewer-three-object-block-editor-script', 'threeObjectPlugin', $three_object_plugin );	
		wp_localize_script( 'three-object-viewer-three-object-block-editor-script', 'allowed_blocks', $ALLOWED_BLOCKS );
	
	}
	}
