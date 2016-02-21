<?php

// Tumblr

// fetch counts
$tumblr = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://api.tumblr.com/v2/share/stats?url=' . $post_url ) ) );
$tumblr_json = json_decode( $tumblr, true );

// store results, if we have some
if ( isset( $tumblr_json['response']['note_count'] ) ) {
	$share_counts['tumblr'] = intval( $tumblr_json['response']['note_count'] );
}

?>
