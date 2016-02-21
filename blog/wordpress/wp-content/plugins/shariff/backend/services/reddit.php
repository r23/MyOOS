<?php

// Reddit

// fetch counts
$reddit = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://www.reddit.com/api/info.json?url=' . $post_url ) ) );
$reddit_json = json_decode( $reddit, true );

// store results, if we have some
if ( isset( $reddit_json['data']['children'] ) ) {
	$count = 0;
	foreach ( $reddit_json['data']['children'] as $child ) {
		$count += intval( $child['data']['score'] );
    }
	$share_counts['reddit'] = $count;
}

?>
