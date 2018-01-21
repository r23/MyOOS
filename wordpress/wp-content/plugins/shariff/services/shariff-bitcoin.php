<?php
// Bitcoin

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( plugins_url( '../', __FILE__ ) . 'bitcoin.php' );

	// bitcoin address
	if ( array_key_exists( 'bitcoinaddress', $atts ) ) $bitcoinaddress = esc_html( $atts['bitcoinaddress'] );
	else $bitcoinaddress = '';

	// build button url
	$button_url = $service_url . '?bitcoinaddress=' . $bitcoinaddress;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 23 32"><path d="M20.8 11.4q0.3 3.3-2.3 4.6 2.1 0.5 3.1 1.8t0.8 3.8q-0.1 1.3-0.6 2.2t-1.2 1.6-1.7 1-2.2 0.6-2.6 0.3v4.6h-2.7v-4.5q-1.4 0-2.2 0v4.5h-2.7v-4.6q-0.3 0-1 0t-1 0h-3.6l0.6-3.3h2q0.9 0 1-0.9v-7.2h0.3q-0.1 0-0.3 0v-5.1q-0.2-1.2-1.6-1.2h-2v-2.9l3.8 0q1.1 0 1.7 0v-4.5h2.8v4.4q1.5 0 2.2 0v-4.4h2.8v4.5q1.4 0.1 2.5 0.4t2 0.8 1.5 1.4 0.7 2zM17 21.2q0-0.6-0.3-1.1t-0.7-0.8-1-0.5-1.2-0.3-1.3-0.2-1.2-0.1-1.2 0-0.8 0v6q0.1 0 0.7 0t0.9 0 0.9 0 1-0.1 1-0.2 1-0.2 0.8-0.4 0.7-0.5 0.4-0.7 0.2-0.9zM15.7 12.7q0-0.6-0.2-1t-0.5-0.7-0.9-0.5-1-0.3-1.1-0.1-1 0-1 0-0.7 0v5.5q0.1 0 0.6 0t0.8 0 0.9 0 1-0.1 0.9-0.2 0.9-0.3 0.7-0.5 0.5-0.7 0.2-0.9z"/></svg>';

	// colors
	$main_color = '#f7931a';
	$secondary_color = '#191919';

	// button share text
	$button_text_array = array(
		'de' => 'spenden',
		'en' => 'donate',
		'fr' => 'faire un don',
		'es' => 'donar'
	);

	// button title / label
	$button_title_array = array(
		'de' => 'Spenden mit Bitcoin',
		'en' => 'Donate with Bitcoin',
		'fr' => 'Faire un don via Bitcoin',
		'es' => 'Donar via Bitcoin'
	);
}
