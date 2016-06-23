<?php
// Stumbleupon

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://www.stumbleupon.com/submit' );

	// build button url
	$button_url = $service_url . '?url=' . $share_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 37 32"><path d="M19 12.7v-2.1q0-0.7-0.5-1.3t-1.3-0.5-1.3 0.5-0.5 1.3v10.9q0 3.1-2.2 5.3t-5.4 2.2q-3.2 0-5.4-2.2t-2.2-5.4v-4.8h5.9v4.7q0 0.8 0.5 1.3t1.3 0.5 1.3-0.5 0.5-1.3v-11.1q0-3 2.2-5.2t5.4-2.1q3.1 0 5.4 2.2t2.3 5.2v2.4l-3.5 1zM28.4 16.7h5.9v4.8q0 3.2-2.2 5.4t-5.4 2.2-5.4-2.2-2.2-5.4v-4.8l2.3 1.1 3.5-1v4.8q0 0.7 0.5 1.3t1.3 0.5 1.3-0.5 0.5-1.3v-4.9z"/></svg>';

	// colors
	$main_color = '#eb4b24';
	$secondary_color = '#e1370e';

	// backend available?
	$backend_available = '1';

	// button title / label
	$button_title_array = array(
		'de' => 'Bei StumbleUpon teilen',
		'en' => 'Share on StumbleUpon',
		'es' => 'Compartir en StumbleUpon',
		'fr' => 'Partager sur StumbleUpon',
		'it' => 'Condividi su StumbleUpon',
		'da' => 'Del på StumbleUpon',
		'nl' => 'Delen op StumbleUpon'
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// fetch counts
	$stumbleupon = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $post_url ) ) );
	$stumbleupon_json = json_decode( $stumbleupon, true );

	// store results, if we have some
	if ( isset( $stumbleupon_json['success'] ) && $stumbleupon_json['success'] == true ) {
		if ( isset( $stumbleupon_json['result']['views'] ) ) {
			$share_counts['stumbleupon'] = intval( $stumbleupon_json['result']['views'] );
		}
		else {
			$share_counts['stumbleupon'] = 0;
		}
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['stumbleupon'] = $stumbleupon;
	}
}
