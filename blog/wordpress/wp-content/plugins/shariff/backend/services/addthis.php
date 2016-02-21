<?php

// AddThis

// fetch counts
$addthis = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'http://api-public.addthis.com/url/shares.json?url=' . $post_url ) ) );
$addthis_json = json_decode( $addthis, true );

// store results, if we have some
if ( isset( $addthis_json['shares'] ) ) {
	$share_counts['addthis'] = intval( $addthis_json['shares'] );
}

?>
