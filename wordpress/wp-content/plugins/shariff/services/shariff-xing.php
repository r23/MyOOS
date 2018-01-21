<?php
// Xing

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://www.xing.com/social_plugins/share' );

	// build button url
	$button_url = $service_url . '?url=' . $share_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 32"><path d="M10.7 11.9q-0.2 0.3-4.6 8.2-0.5 0.8-1.2 0.8h-4.3q-0.4 0-0.5-0.3t0-0.6l4.5-8q0 0 0 0l-2.9-5q-0.2-0.4 0-0.7 0.2-0.3 0.5-0.3h4.3q0.7 0 1.2 0.8zM25.1 0.4q0.2 0.3 0 0.7l-9.4 16.7 6 11q0.2 0.4 0 0.6-0.2 0.3-0.6 0.3h-4.3q-0.7 0-1.2-0.8l-6-11.1q0.3-0.6 9.5-16.8 0.4-0.8 1.2-0.8h4.3q0.4 0 0.5 0.3z"/></svg>';

	// colors
	$main_color = '#126567';
	$secondary_color = '#29888a';

	// backend available?
	$backend_available = '1';

	// button title / label
	$button_title_array = array(
		'bg' => 'Сподели във Xing',
	    'da' => 'Del på Xing',
	    'de' => 'Bei Xing teilen',
	    'en' => 'Share on Xing',
	    'es' => 'Compartir en Xing',
	    'fi' => 'Jaa Xingissa',
	    'fr' => 'Partager sur Xing',
	    'hr' => 'Podijelite na Xingu',
	    'hu' => 'Megosztás Xingon',
	    'it' => 'Condividi su Xing',
	    'ja' => 'フェイスブック上で共有',
	    'ko' => '페이스북에서 공유하기',
	    'nl' => 'Delen op Xing',
	    'no' => 'Del på Xing',
	    'pl' => 'Udostępnij na Xingu',
	    'pt' => 'Compartilhar no Xing',
	    'ro' => 'Partajează pe Xing',
	    'ru' => 'Поделиться на Xing',
	    'sk' => 'Zdieľať na Xingu',
	    'sl' => 'Deli na Xingu',
	    'sr' => 'Podeli na Xing-u',
	    'sv' => 'Dela på Xing',
	    'tr' => 'Xing\'ta paylaş',
	    'zh' => '在Xing上分享',
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// set xing options
	$xing_json = array(
		'url' => $post_url2
	);

	// set post options
	$xing_post_options = array(
		'method' => 'POST',
		'timeout' => 5,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array( 'content-type' => 'application/json' ),
		'body' => json_encode( $xing_json )
	);

	// fetch counts
	$xing = sanitize_text_field( wp_remote_retrieve_body( wp_remote_post( 'https://www.xing-share.com/spi/shares/statistics', $xing_post_options ) ) );
	$xing_json = json_decode( $xing, true );

	// store results, if we have some
	if ( isset( $xing_json['share_counter'] ) ) {
		$share_counts['xing'] = intval( $xing_json['share_counter'] );
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['xing'] = $xing;
	}
}
