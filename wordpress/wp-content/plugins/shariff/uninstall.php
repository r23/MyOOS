<?php 
// delete options upon uninstall to prevent issues with other plugins and leaving trash behind

// exit, if uninstall.php was not called from WordPress
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { die(); }

// check for multisite
if ( is_multisite() ) {
	global $wpdb;
	$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
	if ( $blogs ) {
		foreach( $blogs as $blog ) {
			// switch to each blog
			switch_to_blog( $blog['blog_id'] );
			// delete options from options table
			delete_option( 'shariff3UU' );
			delete_option( 'shariff3UU_basic' );
			delete_option( 'shariff3UU_design' );
			delete_option( 'shariff3UU_advanced' );
			delete_option( 'shariff3UU_mailform' );
			delete_option( 'shariff3UU_statistic' );
			delete_option( 'widget_shariff' );
			delete_option( 'shariff3UU_hide_update_notice' );
			// purge transients
			purge_transients();
			// remove cron job
			wp_clear_scheduled_hook( 'shariff3UU_fill_cache' );
			// switch back to main
			restore_current_blog();
		}
	}
} else {
	// delete options from options table
	delete_option( 'shariff3UU' );
	delete_option( 'shariff3UU_basic' );
	delete_option( 'shariff3UU_design' );
	delete_option( 'shariff3UU_advanced' );
	delete_option( 'shariff3UU_mailform' );
	delete_option( 'shariff3UU_statistic' );
	delete_option( 'widget_shariff' );
	delete_option( 'shariff3UU_hide_update_notice' );
	delete_option( 'shariff3UU_hide_update_notice' );
	// purge transients
	purge_transients();
	// remove cron job
	wp_clear_scheduled_hook( 'shariff3UU_fill_cache' );
}

// purge all the transients associated with our plugin
function purge_transients() {
	global $wpdb;
	// delete transients
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_timeout_shariff%"';
	$wpdb->query($sql);
	$sql = 'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE "_transient_shariff%"';
	$wpdb->query($sql);
	// clear object cache
	wp_cache_flush();
}

?>
