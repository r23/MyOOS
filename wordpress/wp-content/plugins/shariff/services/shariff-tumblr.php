<?php
// Tumblr

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://www.tumblr.com/widgets/share/tool' );
	
	// domain
	$wpurl = get_bloginfo('wpurl');
	$domain = trim( $wpurl, '/' );
	if ( ! preg_match( '#^http(s)?://#', $domain ) ) {
		$domain = 'http://' . $domain;
	}
	$urlParts = parse_url( $domain );
	$domain = preg_replace('/^www\./', '', $urlParts['host']);

	// build button url
	$button_url = $service_url . '?posttype=link&canonicalUrl=' . $share_url . '&tags=' . urlencode( $domain );

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M18 14l0 7.3c0 1.9 0 2.9 0.2 3.5 0.2 0.5 0.7 1.1 1.2 1.4 0.7 0.4 1.5 0.6 2.4 0.6 1.6 0 2.6-0.2 4.2-1.3v4.8c-1.4 0.6-2.6 1-3.7 1.3-1.1 0.3-2.3 0.4-3.6 0.4-1.5 0-2.3-0.2-3.4-0.6-1.1-0.4-2.1-0.9-2.9-1.6-0.8-0.7-1.3-1.4-1.7-2.2s-0.5-1.9-0.5-3.4v-11.2h-4.3v-4.5c1.3-0.4 2.7-1 3.6-1.8 0.9-0.8 1.6-1.7 2.2-2.7 0.5-1.1 0.9-2.4 1.1-4.1h5.2l0 8h8v6h-8z"/></svg>';

	// colors
	$main_color = '#36465d';
	$secondary_color = '#529ecc';

	// backend available?
	$backend_available = '1';

	// button title / label
	$button_title_array = array(
		'bg' => 'Сподели във Tumblr',
		'da' => 'Del på Tumblr',
		'de' => 'Bei Tumblr teilen',
		'en' => 'Share on Tumblr',
		'es' => 'Compartir en Tumblr',
		'fi' => 'Jaa Tumblrissa',
		'fr' => 'Partager sur Tumblr',
		'hr' => 'Podijelite na Tumblru',
		'hu' => 'Megosztás Tumblron',
		'it' => 'Condividi su Tumblr',
		'ja' => 'フェイスブック上で共有',
		'ko' => '페이스북에서 공유하기',
		'nl' => 'Delen op Tumblr',
		'no' => 'Del på Tumblr',
		'pl' => 'Udostępnij na Tumblru',
		'pt' => 'Compartilhar no Tumblr',
		'ro' => 'Partajează pe Tumblr',
		'ru' => 'Поделиться на Tumblr',
		'sk' => 'Zdieľať na Tumblru',
		'sl' => 'Deli na Tumblru',
		'sr' => 'Podeli na Tumblr-u',
		'sv' => 'Dela på Tumblr',
		'tr' => 'Tumblr\'ta paylaş',
		'zh' => '在Tumblr上分享',
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// fetch counts
	$tumblr = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://api.tumblr.com/v2/share/stats?url=' . $post_url ) ) );
	$tumblr_json = json_decode( $tumblr, true );

	// store results, if we have some
	if ( isset( $tumblr_json['response']['note_count'] ) ) {
		$share_counts['tumblr'] = intval( $tumblr_json['response']['note_count'] );
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['tumblr'] = $tumblr;
	}
}
