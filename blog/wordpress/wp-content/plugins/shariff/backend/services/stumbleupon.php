<?php

// StumbleUpon

// fetch counts
$stumbleupon = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://www.stumbleupon.com/services/1.01/badge.getinfo?url=' . $post_url ) ) );
$stumbleupon_json = json_decode( $stumbleupon, true );

// store results, if we have some
if ( isset( $stumbleupon_json['success'] ) && $stumbleupon_json['success'] == true ) {
	if ( isset( $stumbleupon_json['result']['views'] ) ) {
		$share_counts['stumbleupon'] = intval( $stumbleupon_json['result']['views'] );
	}
	else {
		$share_counts['stumbleupon'] = 0;
	}
}

?>
