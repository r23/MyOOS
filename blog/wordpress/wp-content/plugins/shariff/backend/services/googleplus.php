<?php

// GooglePlus

// set google options
$google_options = array(
	'method' => 'pos.plusones.get',
	'id'     => 'p',
	'params' => array(
		'nolog'   => 'true',
		'id'      => $post_url2,
		'source'  => 'widget',
		'userId'  => '@viewer',
		'groupId' => '@self'
	),
	'jsonrpc'    => '2.0',
	'key'        => 'p',
	'apiVersion' => 'v1'
);

// set post options
$google_post_options = array(
	'method' => 'POST',
	'timeout' => 5,
	'redirection' => 5,
	'httpversion' => '1.0',
	'blocking' => true,
	'headers' => array( 'content-type' => 'application/json' ),
	'body' => json_encode( $google_options )
);

// fetch counts
$googleplus = sanitize_text_field( wp_remote_retrieve_body( wp_remote_post( 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ', $google_post_options ) ) );
$google_json = json_decode( $googleplus, true );

// store results, if we have some
if ( isset( $google_json['result']['metadata']['globalCounts']['count'] ) ) {
	$share_counts['googleplus'] = intval( $google_json['result']['metadata']['globalCounts']['count'] );
}

?>
