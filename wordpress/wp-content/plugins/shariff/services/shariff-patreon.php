<?php
// Patreon

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://www.patreon.com/' );

	// patreon id
	if ( array_key_exists( 'patreonid', $atts ) ) $patreonid = esc_html( $atts['patreonid'] );
	else $patreonid = '';

	// build button url
	$button_url = $service_url . $patreonid;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M10.4 1.5c-4.4 1.8-6.8 4.1-8.7 8.4-0.9 2-1 2.9-1.2 11.9l-0.2 9.7h3.2l0.1-9.6c0.1-8.8 0.2-9.6 1.1-11.3 1.6-2.9 3.1-4.4 5.9-5.8 3.7-1.9 7.1-1.9 10.8 0 3.1 1.5 4.6 3.1 6.1 6.5 0.9 2 1.1 2.8 0.9 5.3-0.4 4.7-2.5 8.1-6.6 10.3-2.1 1.1-2.8 1.3-5.9 1.3-2 0-3.7-0.1-3.9-0.3-0.2-0.1-0.3-1.5-0.4-3.1 0-2.5 0-2.8 0.8-2.6 3.2 0.6 5.7 0.5 7.1-0.4 4.3-2.6 4.3-9.2 0-11.8-3.1-1.9-7.7-0.8-9.6 2.3-0.8 1.3-0.9 2.2-0.9 10.3v8.9l5.4-0.2c4.7-0.1 5.7-0.3 7.8-1.3 3.5-1.6 5.8-3.9 7.5-7.3 1.3-2.6 1.4-3.3 1.4-6.9 0-3.3-0.2-4.3-1.1-6.4-1.6-3.5-3.9-5.8-7.3-7.5-4-2-8.7-2.2-12.5-0.6z"/></svg>';

	// colors
	$main_color = '#e6461a';
	$secondary_color = '#FF794D';

	// button share text
	$button_text_array = array(
		'de' => 'patreon',
		'en' => 'patreon'
	);

	// button title / label
	$button_title_array = array(
		'de' => 'Werde ein patron!',
		'en' => 'Become a patron!',
		'es' => 'Conviértete en un patron!',
		'fr' => 'Devenez un patron!',
	);
}
