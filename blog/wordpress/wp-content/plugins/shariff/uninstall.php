<?php 
// delete options upon uninstall to prevent issues with other plugins and leaving trash behind

// exit, if uninstall.php was not called from WordPress
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

$option_name = 'shariff3UU';
$shariff3UU_basic = 'shariff3UU_basic';
$shariff3UU_design = 'shariff3UU_design';
$shariff3UU_advanced = 'shariff3UU_advanced';
$shariff3UU_mailform = 'shariff3UU_mailform';
$widget_name = 'widget_shariff';

// check for multisite
if ( is_multisite() ) {
    global $wpdb;
    $blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);
    if ( $blogs ) {
        foreach( $blogs as $blog ) {
          // switch to each blog
          switch_to_blog( $blog['blog_id'] );
          // delete options from options table
          delete_option( $option_name );
          delete_option( $shariff3UU_basic );
          delete_option( $shariff3UU_design );
          delete_option( $shariff3UU_advanced );
          delete_option( $shariff3UU_mailform );
          delete_option( $widget_name );
          // delete user meta entry shariff3UU_ignore_notice
          $users = get_users('role=administrator');
          foreach ( $users as $user ) { 
            if ( ! get_user_meta( $user, 'shariff3UU_ignore_notice' ) ) { 
              delete_user_meta( $user->ID, 'shariff3UU_ignore_notice' ); 
            } 
          };
          // delete cache dir
          shariff_removecachedir();
          // purge transients
          purge_transients();
          // switch back to main
          restore_current_blog();
        }
    }
} else {
    // delete options from options table
    delete_option( $option_name );
    delete_option( $shariff3UU_basic );
    delete_option( $shariff3UU_design );
    delete_option( $shariff3UU_advanced );
    delete_option( $shariff3UU_mailform );
    delete_option( $widget_name );
    // delete user meta entry shariff3UU_ignore_notice
    $users = get_users('role=administrator');
    foreach ( $users as $user ) { 
      if ( ! get_user_meta( $user, 'shariff3UU_ignore_notice' ) ) { 
        delete_user_meta( $user->ID, 'shariff3UU_ignore_notice' ); 
      } 
    };
    // delete cache dir
    shariff_removecachedir();
    // purge transients
    purge_transients();
}

// delete cache directory
function shariff_removecachedir() {
  $upload_dir = wp_upload_dir();
  $cache_dir = $upload_dir['basedir'] . '/shariff3uu_cache';
  shariff_removefiles( $cache_dir );
  // remove /shariff3uu_cache if empty
  @rmdir( $cache_dir );
}

// helper function to delete .dat files that begin with "Shariff" and empty folders that also start with "Shariff"
function shariff_removefiles( $directory ) {
  foreach( glob( "{$directory}/Shariff*" ) as $file ) {
    if ( is_dir( $file ) ) shariff_removefiles( $file );
    elseif ( substr( $file, -4 ) == '.dat' ) @unlink( $file );
  }
  @rmdir( $directory );
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
