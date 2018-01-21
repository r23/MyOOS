<?php
// Pocket

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://getpocket.com/edit' );

	// build button url
	$button_url = $service_url . '?url=' . $share_url . '&title=' . $share_title;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 28"><path d="M24.5 2q1 0 1.7 0.7t0.7 1.7v8.1q0 2.8-1.1 5.3t-2.9 4.3-4.3 2.9-5.2 1.1q-2.7 0-5.2-1.1t-4.3-2.9-2.9-4.3-1.1-5.2v-8.1q0-1 0.7-1.7t1.7-0.7h22zM13.5 18.6q0.7 0 1.3-0.5l6.3-6.1q0.6-0.5 0.6-1.3 0-0.8-0.5-1.3t-1.3-0.5q-0.7 0-1.3 0.5l-5 4.8-5-4.8q-0.5-0.5-1.3-0.5-0.8 0-1.3 0.5t-0.5 1.3q0 0.8 0.6 1.3l6.3 6.1q0.5 0.5 1.3 0.5z"/></svg>';

	// colors
	$main_color = '#ff0000';
	$secondary_color = '#444';

	// backend available?
	$backend_available = '1';

	// button share text
	$button_text_array = array(
		'de' => 'pocket',
		'en' => 'pocket'
	);

	// button title / label
	$button_title_array = array(
		'de' => 'Bei Pocket speichern',
		'en' => 'Save to Pocket',
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// fetch counts
	$pocket = wp_kses_post( wp_remote_retrieve_body( wp_remote_get( 'http://widgets.getpocket.com/v1/button?v=1&count=horizontal&url=' . $post_url ) ) );
	$dom = new DOMDocument();
	$dom->loadHTML( $pocket );
	$xpath = new DOMXpath( $dom );
	$result = $xpath->query('//em[@id="cnt"]');
	if ( $result->length > 0 ) $pocket_count = absint( $result->item(0)->nodeValue );

	// store results, if we have some
	if ( isset( $pocket_count ) ) {
		$share_counts['pocket'] = intval( $pocket_count );
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['pocket'] = $pocket;
	}
}
