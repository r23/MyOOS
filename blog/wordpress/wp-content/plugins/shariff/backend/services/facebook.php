<?php

// Facebook

// if we have a fb id and secret, use it
if ( isset( $shariff3UU_statistic['fb_id'] ) && isset( $shariff3UU_statistic['fb_secret'] ) ) {
	// on 32-bit PHP the constant is 4 and we have to disable the check because the id is too long
	if ( ( defined( PHP_INT_SIZE ) && PHP_INT_SIZE === '4' ) || ! defined( PHP_INT_SIZE ) ) {
		$fb_app_id = $shariff3UU_statistic['fb_id'];
	}
	else {
		$fb_app_id = absint( $shariff3UU_statistic['fb_id'] );
	}
	$fb_app_secret = sanitize_text_field( $shariff3UU_statistic['fb_secret'] );
	// get fb access token
	$fb_token = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/oauth/access_token?client_id=' .  $fb_app_id . '&client_secret=' . $fb_app_secret . '&grant_type=client_credentials' ) ) );
	// use token to get share counts
	$facebook = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/v2.2/?id=' . $post_url . '&' . $fb_token ) ) );
	$facebook_json = json_decode( $facebook, true );
}
// otherwise use the normal way
else { 
	$facebook = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/fql?q=SELECT%20total_count%20FROM%20link_stat%20WHERE%20url="' . $post_url . '"' ) ) );
	$facebook_json = json_decode( $facebook, true );
}

// store results - use total_count if it exists, otherwise use share_count - ordered based on proximity of occurrence
if ( isset( $facebook_json['data'] ) && isset( $facebook_json['data'][0] ) && isset( $facebook_json['data'][0]['total_count'] ) ) {
	$share_counts['facebook'] = intval( $facebook_json['data'][0]['total_count'] );
}
elseif ( isset($facebook_json['share'] ) && isset( $facebook_json['share']['share_count'] ) ) {
	$share_counts['facebook'] = intval( $facebook_json['share']['share_count'] );
}
elseif ( isset($facebook_json['data'] ) && isset( $facebook_json['data'][0] ) && isset( $facebook_json['data'][0]['share_count'] ) ) {
	$share_counts['facebook'] = intval( $facebook_json['data'][0]['share_count'] );
}

?>
