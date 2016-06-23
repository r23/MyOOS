<?php
// reddit

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://www.reddit.com/submit' );

	// build button url
	$button_url = $service_url . '?url=' . $share_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 42 32"><path d="M14.8 17.2q0 1-0.7 1.7t-1.7 0.7q-1 0-1.7-0.7t-0.7-1.7q0-1 0.7-1.8t1.7-0.7 1.7 0.7 0.7 1.8zM23.1 14.7q1 0 1.7 0.7t0.7 1.8q0 1-0.7 1.7t-1.7 0.7q-1 0-1.8-0.7t-0.7-1.7q0-1 0.8-1.8t1.7-0.7zM35.4 14.5q0 1.1-0.5 2t-1.5 1.5q0.1 0.6 0.1 1.1 0 2.1-1.2 4.1t-3.5 3.5q-2.2 1.5-5.1 2.2t-6 0.8q-3.1 0-6-0.8t-5.1-2.2q-2.3-1.5-3.5-3.4t-1.2-4.1q0-0.5 0.1-1.2-0.9-0.5-1.4-1.5t-0.5-2q0-1.7 1.2-2.8t2.8-1.2q1.5 0 2.7 1 4.4-2.8 10.6-2.9l2.4-7.6q0.1-0.3 0.3-0.4t0.5-0.1l6.2 1.5q0.4-0.9 1.2-1.5t1.9-0.5q1.4 0 2.3 1t1 2.4-1 2.3-2.3 1q-1.4 0-2.3-1t-1-2.3l-5.6-1.3-2.1 6.5q5.9 0.3 10 3 1.1-1.1 2.7-1.1 1.7 0 2.8 1.2t1.2 2.8zM29.7 1.4q-0.8 0-1.4 0.6t-0.6 1.4 0.6 1.4 1.4 0.6 1.4-0.6 0.5-1.4-0.5-1.4-1.4-0.6zM1.4 14.5q0 1.2 0.9 2 0.9-2.3 3.2-4.2-0.6-0.4-1.5-0.4-1.1 0-1.9 0.8t-0.8 1.9zM28 25.6q2-1.3 3.1-3t1.1-3.5-1.1-3.5-3.1-3q-2-1.3-4.7-2t-5.6-0.7-5.6 0.7-4.7 2q-2 1.3-3 3t-1.1 3.5 1.1 3.5 3 3q2.1 1.3 4.7 2t5.6 0.7 5.6-0.7 4.7-2zM33 16.6q1-0.8 1-2.1 0-1.1-0.8-1.9t-1.9-0.8q-0.9 0-1.5 0.5 2.3 1.9 3.2 4.3zM22.5 23.2q0.2-0.2 0.5-0.2t0.5 0.2 0.2 0.5-0.2 0.5q-1.8 1.8-5.7 1.8h0q-3.9 0-5.7-1.8-0.2-0.2-0.2-0.5t0.2-0.5 0.5-0.2 0.5 0.2q1.4 1.4 4.7 1.4h0q3.4 0 4.7-1.4z"/></svg>';

	// colors
	$main_color = '#ff4500';
	$secondary_color = '#ff5700';

	// backend available?
	$backend_available = '1';

	// button title / label
	$button_title_array = array(
		'de' => 'Bei Reddit teilen',
		'en' => 'Share on Reddit',
		'es' => 'Compartir en Reddit',
		'fr' => 'Partager sur Reddit',
		'it' => 'Condividi su Reddit',
		'da' => 'Del på Reddit',
		'nl' => 'Delen op Reddit'
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// fetch counts
	$reddit = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://www.reddit.com/api/info.json?url=' . $post_url ) ) );
	$reddit_json = json_decode( $reddit, true );

	// store results, if we have some
	if ( isset( $reddit_json['data']['children'] ) ) {
		$count = 0;
		foreach ( $reddit_json['data']['children'] as $child ) {
			$count += intval( $child['data']['score'] );
	    }
		$share_counts['reddit'] = $count;
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['reddit'] = $reddit;
	}
}
