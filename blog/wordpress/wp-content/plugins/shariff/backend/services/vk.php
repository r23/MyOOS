<?php

// VK

// fetch counts
$vk = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'http://vk.com/share.php?act=count&url=' . $post_url ) ) );
if ( isset( $vk ) ) {
	preg_match( '/^VK.Share.count\((\d+),\s+(\d+)\);$/i', $vk, $matches );
	$vk_count = intval( $matches[2] );
}

// store results, if we have some
if ( isset( $vk_count ) ) {
	$share_counts['vk'] = $vk_count;
}

?>
