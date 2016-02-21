<?php

// Twitter

// fetch counts
$twitter = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'http://opensharecount.com/count.json?url=' . $post_url ) ) );
$twitter_json = json_decode( $twitter, true );

// store results, if we have some
if ( isset( $twitter_json['count'] ) && ! isset( $twitter_json['error'] ) ) {
	$share_counts['twitter'] = intval( $twitter_json['count'] );
}

?>
