<?php 
// AddThis

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'http://api.addthis.com/oexchange/0.8/offer' );

	// build button url
	$button_url = $service_url . '?url=' . $share_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M28.2 12.8h-8.9v-8.9c0-0.4-0.4-0.8-0.8-0.8h-4.9c-0.4 0-0.8 0.4-0.8 0.8v8.9h-8.9c-0.4 0-0.8 0.4-0.8 0.8v4.9c0 0.4 0.4 0.8 0.8 0.8h8.9v8.9c0 0.4 0.4 0.8 0.8 0.8h4.9c0.4 0 0.8-0.4 0.8-0.8v-8.9h8.9c0.4 0 0.8-0.4 0.8-0.8v-4.9c0-0.4-0.4-0.8-0.8-0.8z"/></svg>';

	// colors
	$main_color = '#f8694d';
	$secondary_color = '#f75b44';

	// backend available?
	$backend_available = '1';

	// button title / label
	$button_title_array = array(
		'bg' => 'Сподели в AddThis',
		'da' => 'Del på AddThis',
		'de' => 'Bei AddThis teilen',
		'en' => 'Share on AddThis',
		'es' => 'Compartir en AddThis',
		'fi' => 'Jaa AddThisissä',
		'fr' => 'Partager sur AddThis',
		'hr' => 'Podijelite na AddThis',
		'hu' => 'Megosztás AddThisen',
		'it' => 'Condividi su AddThis',
		'ja' => 'AddThis上で共有',
		'ko' => 'AddThis에서 공유하기',
		'nl' => 'Delen op AddThis',
		'no' => 'Del på AddThis',
		'pl' => 'Udostępnij przez AddThis',
		'pt' => 'Compartilhar no AddThis',
		'ro' => 'Partajează pe AddThis',
		'ru' => 'Поделиться на AddThis',
		'sk' => 'Zdieľať na AddThis',
		'sl' => 'Deli na AddThis',
		'sr' => 'Podeli na AddThis',
		'sv' => 'Dela på AddThis',
		'tr' => 'AddThis\'ta paylaş',
		'zh' => '在AddThis上分享'
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// fetch counts
	$addthis = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'http://api-public.addthis.com/url/shares.json?url=' . $post_url ) ) );
	$addthis_json = json_decode( $addthis, true );

	// store results, if we have some
	if ( isset( $addthis_json['shares'] ) ) {
		$share_counts['addthis'] = intval( $addthis_json['shares'] );
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['addthis'] = $addthis;
	}
}
