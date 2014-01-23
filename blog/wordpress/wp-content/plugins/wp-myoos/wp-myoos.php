<?php
/*
Plugin Name: MyOOS
Text Domain: myoos
Domain Path: /lang
Description: Required MyOOS extensions.
Author: MyOOS Development Team
Author URI: http://www.oos-shop.de
Version: 2.1.15
*/


/* Quit */
defined('ABSPATH') OR exit;


/**
 * Puts a link to MyOOS Community in the WordPress admin footer.
 */
function myoos_footer_text($default) {
	$default .= ' <span id="footer-myoos">' . __('| MyOOS integration by <a href="http://foren.myoos.de/">MyOOS Community</a>.', 'wp-myoos') . '</span>';
	return $default;
}		

/**
 * removed WordPress SEO from the phpBB3 Page
 */
function remove_wpseo() {
	global $wp_query;
	
	if (defined('WPSEO_VERSION')) {	
		$forum_page_ID = get_option('wpu_set_forum');
		if ( is_page( $forum_page_ID ) ) {

			remove_action( 'wpseo_head', array( $wpseo_front, 'debug_marker' ), 2 );
			remove_action( 'wpseo_head', array( $wpseo_front, 'robots' ), 6 );
			remove_action( 'wpseo_head', array( $wpseo_front, 'metadesc' ), 10 );
			remove_action( 'wpseo_head', array( $wpseo_front, 'metakeywords' ), 11 );
			remove_action( 'wpseo_head', array( $wpseo_front, 'canonical' ), 20 );
			remove_action( 'wpseo_head', array( $wpseo_front, 'adjacent_rel_links' ), 21 );
			remove_action( 'wpseo_head', array( $wpseo_front, 'author' ), 22 );
			remove_action( 'wpseo_head', array( $wpseo_front, 'publisher' ), 23 );
			remove_action( 'wpseo_head', array( $wpseo_front, 'webmaster_tools_authentication' ), 90 );

			$GLOBALS['wp_filter']['wpseo_head'] = array ();
		
		}
	}	
}



if ( is_admin() ) {
	add_filter( 'admin_footer_text', 'myoos_footer_text' );
} else {
	add_filter('wp_title','remove_wpseo',20,3); 
}


