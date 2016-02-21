<?php

// Flattr

// fetch counts
$flattr = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://api.flattr.com/rest/v2/things/lookup/?url=' . $post_url ) ) );
$flattr_json = json_decode( $flattr, true );

// store results, if we have some
if ( isset( $flattr_json['flattrs'] ) ) {
	$share_counts['flattr'] = intval( $flattr_json['flattrs'] );
}

// if no thing was found, set it to 0
elseif ( isset( $flattr_json['description'] ) && $flattr_json['description'] == 'No thing was found' ) {
	$share_counts['flattr'] = 0;
}

?>
