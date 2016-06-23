<?php
// Pinterest

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// helper function to get the first image of a post
if ( ! function_exists("catch_image") ) {
	function catch_image() {
		$files = get_children( 'post_parent=' . get_the_ID() . '&post_type=attachment&post_mime_type=image' );
		if ( $files ) {
			$keys = array_reverse( array_keys( $files ) );
			$num = $keys[0];
			$imageurl = wp_get_attachment_url( $num );
			return $imageurl;
		}
	}
}

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://www.pinterest.com/pin/create/link/' );

	// get an image for pinterest (attribut -> featured image -> first image -> default image -> shariff hint)
	if ( array_key_exists( 'services', $atts ) ) if ( strstr( $atts["services"], 'pinterest') ) {
		if ( array_key_exists( 'media', $atts ) ) $share_media = esc_html( $atts['media'] );
		else {
			$feat_image = wp_get_attachment_url( get_post_thumbnail_id() );
			if ( ! empty( $feat_image ) ) $share_media = esc_html( $feat_image );
			else {
				$first_image = catch_image();
				if ( ! empty( $first_image ) ) $share_media = esc_html( $first_image );
				else {
					if ( isset( $shariff3UU["default_pinterest"] ) ) $share_media = $shariff3UU["default_pinterest"];
					else $share_media = plugins_url( '../pictos/defaultHint.png', __FILE__ );
				}
			}
		}
	}

	// build button url
	$button_url = $service_url . '?url=' . $share_url . '&media=' . urlencode( $share_media ) . '&description=' . $share_title;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 32"><path d="M27.4 16q0 3.7-1.8 6.9t-5 5-6.9 1.9q-2 0-3.9-0.6 1.1-1.7 1.4-2.9 0.2-0.6 1-3.8 0.4 0.7 1.3 1.2t2 0.5q2.1 0 3.8-1.2t2.7-3.4 0.9-4.8q0-2-1.1-3.8t-3.1-2.9-4.5-1.2q-1.9 0-3.5 0.5t-2.8 1.4-2 2-1.2 2.3-0.4 2.4q0 1.9 0.7 3.3t2.1 2q0.5 0.2 0.7-0.4 0-0.1 0.1-0.5t0.2-0.5q0.1-0.4-0.2-0.8-0.9-1.1-0.9-2.7 0-2.7 1.9-4.6t4.9-2q2.7 0 4.2 1.5t1.5 3.8q0 3-1.2 5.2t-3.1 2.1q-1.1 0-1.7-0.8t-0.4-1.9q0.1-0.6 0.5-1.7t0.5-1.8 0.2-1.4q0-0.9-0.5-1.5t-1.4-0.6q-1.1 0-1.9 1t-0.8 2.6q0 1.3 0.4 2.2l-1.8 7.5q-0.3 1.2-0.2 3.2-3.7-1.6-6-5t-2.3-7.6q0-3.7 1.9-6.9t5-5 6.9-1.9 6.9 1.9 5 5 1.8 6.9z"/></svg>';

	// colors
	$main_color = '#cb2027';
	$secondary_color = '#e70f18';

	// backend available?
	$backend_available = '1';

	// button share text
	$button_text_array = array(
		'de' => 'pinnen',
		'en' => 'pin it'
	);

	// button title / label
	$button_title_array = array(
		'de' => 'Bei Pinterest pinnen',
		'en' => 'Pin it on Pinterest',
		'es' => 'Compartir en Pinterest',
		'fr' => 'Partager sur Pinterest',
		'it' => 'Condividi su Pinterest',
		'da' => 'Del på Pinterest',
		'nl' => 'Delen op Pinterest'
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// fetch counts
	$pinterest = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'http://api.pinterest.com/v1/urls/count.json?callback=x&url=' . $post_url ) ) );
	$pinterest_json = rtrim( substr( $pinterest, 2 ) , ")" );
	$pinterest_json = json_decode( $pinterest_json, true );

	// store results, if we have some
	if ( isset( $pinterest_json['count'] ) ) {
		$share_counts['pinterest'] = intval( $pinterest_json['count'] );
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['pinterest'] = $pinterest;
	}
}
