<?php
// VK

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://vk.com/share.php' );

	// build button url
	$button_url = $service_url . '?url=' . $share_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 32"><path d="M34.2 9.3q0.4 1.1-2.7 5.3-0.4 0.6-1.2 1.5-1.4 1.8-1.6 2.3-0.3 0.7 0.3 1.4 0.3 0.4 1.4 1.5h0l0.1 0.1q2.5 2.3 3.4 3.9 0.1 0.1 0.1 0.2t0.1 0.5 0 0.6-0.4 0.5-1.1 0.2l-4.6 0.1q-0.4 0.1-1-0.1t-0.9-0.4l-0.4-0.2q-0.5-0.4-1.2-1.1t-1.2-1.4-1.1-1-1-0.3q-0.1 0-0.1 0.1t-0.3 0.3-0.4 0.5-0.3 0.9-0.1 1.4q0 0.3-0.1 0.5t-0.1 0.3l-0.1 0.1q-0.3 0.3-0.9 0.4h-2.1q-1.3 0.1-2.6-0.3t-2.3-0.9-1.8-1.2-1.3-1l-0.4-0.4q-0.2-0.2-0.5-0.5t-1.3-1.6-1.9-2.7-2.2-3.8-2.3-4.9q-0.1-0.3-0.1-0.5t0.1-0.3l0.1-0.1q0.3-0.3 1-0.3l4.9 0q0.2 0 0.4 0.1t0.3 0.2l0.1 0.1q0.3 0.2 0.4 0.6 0.4 0.9 0.8 1.8t0.7 1.5l0.3 0.5q0.5 1.1 1 1.9t0.9 1.2 0.7 0.7 0.6 0.3 0.5-0.1q0 0 0.1-0.1t0.2-0.4 0.2-0.8 0.2-1.4 0-2.2q0-0.7-0.2-1.3t-0.2-0.8l-0.1-0.2q-0.4-0.6-1.5-0.8-0.2 0 0.1-0.4 0.3-0.3 0.7-0.5 0.9-0.5 4.3-0.4 1.5 0 2.4 0.2 0.4 0.1 0.6 0.2t0.4 0.4 0.2 0.6 0.1 0.8 0 1 0 1.3 0 1.5q0 0.2 0 0.8t0 0.9 0.1 0.7 0.2 0.7 0.4 0.4q0.1 0 0.3 0.1t0.5-0.2 0.7-0.6 0.9-1.2 1.2-1.9q1.1-1.9 1.9-4 0.1-0.2 0.2-0.3t0.2-0.2l0.1-0.1 0.1 0t0.2-0.1 0.4 0l5.1 0q0.7-0.1 1.1 0t0.6 0.3z"/></svg>';

	// colors
	$main_color = '#527498';
	$secondary_color = '#4273c8';

	// backend available?
	$backend_available = '1';

	// button title / label
	$button_title_array = array(
		'bg' => 'Сподели във VK',
		'da' => 'Del på VK',
		'de' => 'Bei VK teilen',
		'en' => 'Share on VK',
		'es' => 'Compartir en VK',
		'fi' => 'Jaa VKissa',
		'fr' => 'Partager sur VK',
		'hr' => 'Podijelite na VKu',
		'hu' => 'Megosztás VKon',
		'it' => 'Condividi su VK',
		'ja' => 'フェイスブック上で共有',
		'ko' => '페이스북에서 공유하기',
		'nl' => 'Delen op VK',
		'no' => 'Del på VK',
		'pl' => 'Udostępnij na VKu',
		'pt' => 'Compartilhar no VK',
		'ro' => 'Partajează pe VK',
		'ru' => 'Поделиться на VK',
		'sk' => 'Zdieľať na VKu',
		'sl' => 'Deli na VKu',
		'sr' => 'Podeli na VK-u',
		'sv' => 'Dela på VK',
		'tr' => 'VK\'ta paylaş',
		'zh' => '在VK上分享',
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// fetch counts
	$vk = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'http://vk.com/share.php?act=count&url=' . $post_url ) ) );
	if ( isset( $vk ) ) {
		preg_match( '/^VK.Share.count\((\d+),\s+(\d+)\);$/i', $vk, $matches );
		$vk_count = intval( $matches[2] );
	}

	// store results, if we have some
	if ( isset( $vk_count ) ) {
		$share_counts['vk'] = $vk_count;
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['vk'] = $vk;
	}
}
