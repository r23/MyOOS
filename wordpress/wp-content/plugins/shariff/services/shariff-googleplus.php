<?php
// GooglePlus

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://plus.google.com/share' );

	// build button url
	$button_url = $service_url . '?url=' . $share_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M31.6 14.7h-3.3v-3.3h-2.6v3.3h-3.3v2.6h3.3v3.3h2.6v-3.3h3.3zM10.8 14v4.1h5.7c-0.4 2.4-2.6 4.2-5.7 4.2-3.4 0-6.2-2.9-6.2-6.3s2.8-6.3 6.2-6.3c1.5 0 2.9 0.5 4 1.6v0l2.9-2.9c-1.8-1.7-4.2-2.7-7-2.7-5.8 0-10.4 4.7-10.4 10.4s4.7 10.4 10.4 10.4c6 0 10-4.2 10-10.2 0-0.8-0.1-1.5-0.2-2.2 0 0-9.8 0-9.8 0z"/></svg>';

	// colors
	$main_color = '#d34836';
	$secondary_color = '#f75b44';

	// backend available?
	$backend_available = '1';

	// button title / label
	$button_title_array = array(
		'bg' => 'Сподели в Google+',
		'da' => 'Del på Google+',
		'de' => 'Bei Google+ teilen',
		'en' => 'Share on Google+',
		'es' => 'Compartir en Google+',
		'fi' => 'Jaa Google+ =>ssa',
		'fr' => 'Partager sur Goolge+',
		'hr' => 'Podijelite na Google+',
		'hu' => 'Megosztás Google+on',
		'it' => 'Condividi su Google+',
		'ja' => 'Google+上で共有',
		'ko' => 'Google+에서 공유하기',
		'nl' => 'Delen op Google+',
		'no' => 'Del på Google+',
		'pl' => 'Udostępnij na Google+',
		'pt' => 'Compartilhar no Google+',
		'ro' => 'Partajează pe Google+',
		'ru' => 'Поделиться на Google+',
		'sk' => 'Zdieľať na Google+',
		'sl' => 'Deli na Google+',
		'sr' => 'Podeli na Google+',
		'sv' => 'Dela på Google+',
		'tr' => 'Google+\'da paylaş',
		'zh' => '在Google+上分享'
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// set google options
	$google_options = array(
		'method' => 'pos.plusones.get',
		'id'     => 'p',
		'params' => array(
			'nolog'   => 'true',
			'id'      => $post_url2,
			'source'  => 'widget',
			'userId'  => '@viewer',
			'groupId' => '@self'
		),
		'jsonrpc'    => '2.0',
		'key'        => 'p',
		'apiVersion' => 'v1'
	);

	// set post options
	$google_post_options = array(
		'method' => 'POST',
		'timeout' => 5,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array( 'content-type' => 'application/json' ),
		'body' => json_encode( $google_options )
	);

	// fetch counts
	$googleplus = sanitize_text_field( wp_remote_retrieve_body( wp_remote_post( 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ', $google_post_options ) ) );
	$google_json = json_decode( $googleplus, true );

	// store results, if we have some
	if ( isset( $google_json['result']['metadata']['globalCounts']['count'] ) ) {
		$share_counts['googleplus'] = intval( $google_json['result']['metadata']['globalCounts']['count'] );
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['googleplus'] = $googleplus;
	}
}
