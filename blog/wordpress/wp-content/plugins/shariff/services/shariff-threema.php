<?php
// Threema

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = 'threema://compose';

	// build button url
	$button_url = $service_url . '?text=' . $share_title . '%20' . $share_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M30.8 10.9c-0.3-1.4-0.9-2.6-1.8-3.8-2-2.6-5.5-4.5-9.4-5.2-1.3-0.2-1.9-0.3-3.5-0.3s-2.2 0-3.5 0.3c-4 0.7-7.4 2.6-9.4 5.2-0.9 1.2-1.5 2.4-1.8 3.8-0.1 0.5-0.2 1.2-0.2 1.6 0 0.4 0.1 1.1 0.2 1.6 0.4 1.9 1.3 3.4 2.9 5 0.8 0.8 0.8 0.8 0.7 1.3 0 0.6-0.5 1.6-1.7 3.6-0.3 0.5-0.5 0.9-0.5 0.9 0 0.1 0.1 0.1 0.5 0 0.8-0.2 2.3-0.6 5.6-1.6 1.1-0.3 1.3-0.4 2.3-0.4 0.8 0 1.1 0 2.3 0.2 1.5 0.2 3.5 0.2 4.9 0 5.1-0.6 9.3-2.9 11.4-6.3 0.5-0.9 0.9-1.8 1.1-2.8 0.1-0.5 0.2-1.1 0.2-1.6 0-0.7-0.1-1.1-0.2-1.6-0.3-1.4 0.1 0.5 0 0zM20.6 17.3c0 0.4-0.4 0.8-0.8 0.8h-7.7c-0.4 0-0.8-0.4-0.8-0.8v-4.6c0-0.4 0.4-0.8 0.8-0.8h0.2l0-1.6c0-0.9 0-1.8 0.1-2 0.1-0.6 0.6-1.2 1.1-1.7s1.1-0.7 1.9-0.8c1.8-0.3 3.7 0.7 4.2 2.2 0.1 0.3 0.1 0.7 0.1 2.1v0 1.7h0.1c0.4 0 0.8 0.4 0.8 0.8v4.6zM15.6 7.3c-0.5 0.1-0.8 0.3-1.2 0.6s-0.6 0.8-0.7 1.3c0 0.2 0 0.8 0 1.5l0 1.2h4.6v-1.3c0-1 0-1.4-0.1-1.6-0.3-1.1-1.5-1.9-2.6-1.7zM25.8 28.2c0 1.2-1 2.2-2.1 2.2s-2.1-1-2.1-2.1c0-1.2 1-2.1 2.2-2.1s2.2 1 2.2 2.2zM18.1 28.2c0 1.2-1 2.2-2.1 2.2s-2.1-1-2.1-2.1c0-1.2 1-2.1 2.2-2.1s2.2 1 2.2 2.2zM10.4 28.2c0 1.2-1 2.2-2.1 2.2s-2.1-1-2.1-2.1c0-1.2 1-2.1 2.2-2.1s2.2 1 2.2 2.2z"/></svg>';

	// colors
	$main_color = '#1f1f1f';
	$secondary_color = '#4fbc24';

	// mobile only?
	$mobile_only = '1';

	// button title / label
	$button_title_array = array(
		'bg' => 'Сподели в Threema',
		'da' => 'Del på Threema',
		'de' => 'Bei Threema teilen',
		'en' => 'Share on Threema',
		'es' => 'Compartir en Threema',
		'fi' => 'Jaa Threemaissä',
		'fr' => 'Partager sur Threema',
		'hr' => 'Podijelite na Threema',
		'hu' => 'Megosztás Threemaen',
		'it' => 'Condividi su Threema',
		'ja' => 'Threema上で共有',
		'ko' => 'Threema에서 공유하기',
		'nl' => 'Delen op Threema',
		'no' => 'Del på Threema',
		'pl' => 'Udostępnij przez Threema',
		'pt' => 'Compartilhar no Threema',
		'ro' => 'Partajează pe Threema',
		'ru' => 'Поделиться на Threema',
		'sk' => 'Zdieľať na Threema',
		'sl' => 'Deli na Threema',
		'sr' => 'Podeli na Threema-u',
		'sv' => 'Dela på Threema',
		'tr' => 'Threema\'ta paylaş',
		'zh' => '在Threema上分享'
	);
}
