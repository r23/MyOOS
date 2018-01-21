<?php
// WhatsApp

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = 'whatsapp://send';

	// build button url
	$button_url = $service_url . '?text=' . $share_title . '%20' . $share_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M17.6 17.4q0.2 0 1.7 0.8t1.6 0.9q0 0.1 0 0.3 0 0.6-0.3 1.4-0.3 0.7-1.3 1.2t-1.8 0.5q-1 0-3.4-1.1-1.7-0.8-3-2.1t-2.6-3.3q-1.3-1.9-1.3-3.5v-0.1q0.1-1.6 1.3-2.8 0.4-0.4 0.9-0.4 0.1 0 0.3 0t0.3 0q0.3 0 0.5 0.1t0.3 0.5q0.1 0.4 0.6 1.6t0.4 1.3q0 0.4-0.6 1t-0.6 0.8q0 0.1 0.1 0.3 0.6 1.3 1.8 2.4 1 0.9 2.7 1.8 0.2 0.1 0.4 0.1 0.3 0 1-0.9t0.9-0.9zM14 26.9q2.3 0 4.3-0.9t3.6-2.4 2.4-3.6 0.9-4.3-0.9-4.3-2.4-3.6-3.6-2.4-4.3-0.9-4.3 0.9-3.6 2.4-2.4 3.6-0.9 4.3q0 3.6 2.1 6.6l-1.4 4.2 4.3-1.4q2.8 1.9 6.2 1.9zM14 2.2q2.7 0 5.2 1.1t4.3 2.9 2.9 4.3 1.1 5.2-1.1 5.2-2.9 4.3-4.3 2.9-5.2 1.1q-3.5 0-6.5-1.7l-7.4 2.4 2.4-7.2q-1.9-3.2-1.9-6.9 0-2.7 1.1-5.2t2.9-4.3 4.3-2.9 5.2-1.1z"/></svg>';

	// colors
	$main_color = '#34af23';
	$secondary_color = '#5cbe4a';

	// mobile only?
	$mobile_only = '1';

	// button title / label
	$button_title_array = array(
		'bg' => 'Сподели в WhatsApp',
		'da' => 'Del på WhatsApp',
		'de' => 'Bei WhatsApp teilen',
		'en' => 'Share on WhatsApp',
		'es' => 'Compartir en WhatsApp',
		'fi' => 'Jaa WhatsApp',
		'fr' => 'Partager sur WhatsApp',
		'hr' => 'Podijelite na WhatsApp',
		'hu' => 'Megosztás WhatsApp',
		'it' => 'Condividi su WhatsApp',
		'ja' => 'WhatsApp上で共有',
		'ko' => 'WhatsApp에서 공유하기',
		'nl' => 'Delen op WhatsApp',
		'no' => 'Del på WhatsApp',
		'pl' => 'Udostępnij przez WhatsApp',
		'pt' => 'Compartilhar no WhatsApp',
		'ro' => 'Partajează pe WhatsApp',
		'ru' => 'Поделиться на WhatsApp',
		'sk' => 'Zdieľať na WhatsApp',
		'sl' => 'Deli na WhatsApp',
		'sr' => 'Podeli na WhatsApp-u',
		'sv' => 'Dela på WhatsApp',
		'tr' => 'WhatsApp\'ta paylaş',
		'zh' => '在WhatsApp上分享'
	);
}
