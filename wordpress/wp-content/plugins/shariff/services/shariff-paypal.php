<?php
// PayPal

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=' );

	// paypal button id
	if ( array_key_exists( 'paypalbuttonid', $atts ) ) $paypalbuttonid = esc_html( $atts['paypalbuttonid'] );
	else $paypalbuttonid = '';

	// build button url
	$button_url = $service_url . $paypalbuttonid;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M19.9 8q0-2.8-4.2-2.8h-1.2q-0.6 0-1.1 0.4t-0.6 0.9l-1.1 4.9q0 0.1 0 0.3 0 0.4 0.3 0.7t0.7 0.3h0.9q1.2 0 2.3-0.2t2-0.7 1.5-1.5 0.5-2.3zM30.6 10.7q0 4.7-3.9 7.6-3.9 2.9-10.9 2.9h-1.1q-0.6 0-1.1 0.4t-0.6 0.9l-1.3 5.6q-0.1 0.6-0.7 1.1t-1.2 0.5h-3.8q-0.6 0-0.9-0.4t-0.4-0.9q0-0.2 0.2-1.2h2.7q0.6 0 1.1-0.4t0.7-1l1.3-5.6q0.1-0.6 0.7-1t1.1-0.4h1.1q7 0 10.8-2.9t3.9-7.5q0-2.3-0.9-3.7 3.3 1.6 3.3 6zM27.4 7.4q0 4.7-3.9 7.6-3.9 2.9-10.9 2.9h-1.1q-0.6 0-1.1 0.4t-0.6 0.9l-1.3 5.6q-0.1 0.6-0.7 1.1t-1.2 0.5h-3.8q-0.6 0-0.9-0.3t-0.4-0.9q0-0.1 0-0.4l5.4-23.2q0.1-0.6 0.7-1.1t1.2-0.5h7.9q1.2 0 2.2 0.1t2.2 0.3 2 0.5 1.7 0.9 1.4 1.3 0.9 1.8 0.3 2.4z"/></svg>';

	// colors
	$main_color = '#009cde';
	$secondary_color = '#0285d2';

	// button share text
	$button_text_array = array(
		'de' => 'spenden',
		'en' => 'donate',
		'fr' => 'faire un don',
		'es' => 'donar'
	);

	// button title / label
	$button_title_array = array(
		'de' => 'Spenden mit PayPal',
		'en' => 'Donate with PayPal',
		'fr' => 'Faire un don via PayPal',
		'es' => 'Donar via PayPal'
	);
}
