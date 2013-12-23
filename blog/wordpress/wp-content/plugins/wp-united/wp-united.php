<?php

/*
Plugin Name: WP-United: phpBB WordPress Integration
Plugin URI: http://www.wp-united.com
Description: WP-United connects to your phpBB forum and integrates user sign-on, behaviour and theming. Once your forum is up and running, you should not disable this plugin.
Author: John Wells
Author URI: http://www.wp-united.com
Version: 0.9.2.5
Text Domain: wp-united
Domain Path: /languages
Last Updated: 26 March 2013
* 
*/


if ( !defined('ABSPATH') ) {
	exit;
}

/** The WP-United class may be called as a base class from the phpBB side, or a fully fledged plugin class from here. 
 *  This file could be invoked from either side to instantiate the object.
 *  The WP-United class then decorates itself with a cross-package settings object.
 */
if( !class_exists( 'WP_United_Plugin' ) ) {
	require_once(plugin_dir_path(__FILE__) . 'base-classes.php');
	require_once(plugin_dir_path(__FILE__) . 'plugin-main.php');
	global $wpUnited;
	$wpUnited = new WP_United_Plugin();	
}
$wpUnited->wp_init();

/**
 * Deactivates WP-United
 * @return void
 */
function wpu_deactivate() {
	delete_option('wpu_enabled');
}

/**
 * Activates WP-United. Nothing really special happens here. We just want to figure out if this is 
 * a new install or a (potentially very) old one. 
 *
 * If it really is new, then we can skip upgrade actions.
 */
function wpu_activate() {

	$checkForThese = array('wpu-version', 'wpu-settings', 'wputd_connection');
	$isNewInstall = true;
	
	foreach($checkForThese as $option) {
		$opt = $option;
		if(!empty($opt)) {
			$isNewInstall = false;
			break;
		}
	}
	
	if($isNewInstall) {
		add_option('wpu-new-install', 'yes');
	}
	
}

/**
 * Removes all WP-United settings.
 * As the plugin is deactivated at this point, we can't reliably uninstall from phpBB (yet)
 * @return string ignored message
 */
function wpu_uninstall() {
	
	if(!defined('WP_UNINSTALL_PLUGIN')) {
		return;
	}
	
	$forum_page_ID = get_option('wpu_set_forum');
	if ( !empty($forum_page_ID) ) {
		@wp_delete_post($forum_page_ID);
	}
	
	$options = array(
		'wpu_set_forum',
		'wpu-settings',
		'wputd_connection',
		'wpu-new-install',
		'wpu-version',
		'wpu-last-run',
		'wpu-enabled',
		'widget_wp-united-loginuser-info',
		'widget_wp-united-latest-topics',
		'widget_wp-united-latest-posts'
	);
	
	foreach($options as $option) {
		delete_option($option);
	}
	
	return 'DEACTIVATED!';

}

register_activation_hook('wp-united/wp-united.php', 'wpu_activate');
register_deactivation_hook('wp-united/wp-united.php', 'wpu_deactivate');
register_uninstall_hook('wp-united/wp-united.php', 'wpu_uninstall');

/**
 * OK, so you came here looking for code. Sorry about that. There's nothing else to see in this file.
 *
 *  DOCUMENTATION FOR DEVELOPERS:
 * 
 * WP-United is a very large plugin. To understand the code, check out the following files:
 * - plugin-main.php: The main plugin class that contains all the hooks and filters which are loaded as needed.
 * - <phpbb>/includes/hooks/hook_wp-united.php: The phpBB hook file that bootstraps WP-United from the phpBB side if needed.
 * - base-classes.php: The underlying base object for $wpUnited. 
 * - phpbb.php: The context switcher that switches between phpBB and WordPress on the fly. 
 *
 * Useful things for hackers:
 * - If you want to drop in functionality don't change WP-United core files -- updates will cause you pain if you do! 
 * - There's an API for that! You can simply drop a package into a directory in wp-united/extras. 
 * - In your directory you just need a main.php file, containing a class called 
 *   WP_United_Extra_dirname. It will be auto-loaded and initialised without you having to do anything.
 * - A bunch of useful events fire on the class too, or you can just add more WordPress hooks/filters there. 
 * - See extras.php for documentation on writing your own WP-United Extras class.
 *
 * Other useful things hackers must know:
 * - If WP-United is bootstrapped, the global object $wpUnited will be available. 
 * - If WP-United is enabled, $wpUnited->is_enabled() will return true.
 * - If WP-United is enabled and the phpBB API has been loaded in WordPress, $wpUnited->is_working() will return true.
 * - If is_working() is true, then you have the whole phpBB environment available to you, but see how to access it below.
 * - Check if a setting is enabled with $wpUnited->get_setting()
 * - You **CAN NOT, MUST NOT EVER** make calls to phpBB code from WordPress without wrapping your code in calls 
 *   to the WP-United context switcher, asking it to change state. Note also that most common phpBB functions are already provided
 *   as an API in WP-United's $phpbbForum object. Use them! There are plenty of examples.
 * - If you need some custom phpBB code, then use the WP-United context switcher as follows:
 *			global $phpbbForum;
 * 			$phpbbForum->foreground();
 *  		// I can call phpBB code in here, e.g. use the global $user, $db and $auth objects
 * 			// I can't use WordPress code in here -- e.g. $wpdb->do_something() will fail
 * 			$phpbbForum->background();
 * 			// Now I can call WordPress code, but not phpBB code -- $user, $db, $config, $auth, $template, and a bunch of others, are unavailable now
 *
 * 	- Using the context switcher as in the above example is all well and good, but it is a bad example. It assumes that you 
 *    know the current state when you call foreground(). In reality, you should **NEVER** assume the current state - 
 *    your function could be called from anywhere, and tracking the state throughout a large application like this is impossible.
 *   Rather than having to figure out the state yourself, you can use the context switcher in the following way:
 *			$changedToken = $phpbbForum->foreground();
 *					// my phpBB code here
 *			$phpbbForum->restore_state($changedToken);
 * 
 *	 Now you can go ahead and write mutiple functions, even nested, and not worry about the phpBB state! Nice, right?
 *
 *   However, bear in mind the following:
 * 	 - Context switching is computationally expensive. Group your phpBB calls together if you can.
 *   - It is *your* responsibility to leave the context in the state you found it! That means you can't just return a 
 *    result from your function without restoring the state first. A calling function could be royally f***ed up if it calls your function, 
 *    and you return after having pulled the current state out from the caller's feet. 		
 */

// That's all. Happy blogging!