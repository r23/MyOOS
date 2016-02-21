<?php

// Xing

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

?>
