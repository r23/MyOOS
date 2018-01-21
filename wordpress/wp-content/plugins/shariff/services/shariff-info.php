<?php
// Info

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'http://ct.de/-2467514' );

	// set custom info url
	if ( array_key_exists( 'info_url', $atts ) ) $service_url = esc_url( $atts['info_url'] );

	// build button url
	$button_url = $service_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11 32"';
	// default theme?
	if ( ! array_key_exists( 'maincolor', $atts ) && ( ( array_key_exists( 'theme', $atts ) && $atts['theme'] == "default" || ( array_key_exists( 'theme', $atts ) && $atts['theme'] == "round" ) ) || ! array_key_exists( 'theme', $atts ) ) ) {
		$svg_icon .= ' style="fill:#999"';
	}
	$svg_icon .= '><path d="M11.4 24v2.3q0 0.5-0.3 0.8t-0.8 0.4h-9.1q-0.5 0-0.8-0.4t-0.4-0.8v-2.3q0-0.5 0.4-0.8t0.8-0.4h1.1v-6.8h-1.1q-0.5 0-0.8-0.4t-0.4-0.8v-2.3q0-0.5 0.4-0.8t0.8-0.4h6.8q0.5 0 0.8 0.4t0.4 0.8v10.3h1.1q0.5 0 0.8 0.4t0.3 0.8zM9.2 3.4v3.4q0 0.5-0.4 0.8t-0.8 0.4h-4.6q-0.4 0-0.8-0.4t-0.4-0.8v-3.4q0-0.4 0.4-0.8t0.8-0.4h4.6q0.5 0 0.8 0.4t0.4 0.8z"/></svg>';

	// colors
	$main_color = '#999';
	$secondary_color = '#a8a8a8';

	// button share text
	$button_text_array = array(
		'de' => 'info',
		'en' => 'info'
	);

	// button title / label
	$button_title_array = array(
		'de' => 'Weitere Informationen über diese Buttons.',
		'en' => 'More information about these buttons.',
		'es' => 'más informaciones',
		'fr' => 'plus d\'informations',
		'it' => 'maggiori informazioni',
		'da' => 'flere oplysninger',
		'nl' => 'verdere informatie'
	);
}
