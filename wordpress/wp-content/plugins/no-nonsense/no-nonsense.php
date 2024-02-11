<?php
/*
Plugin Name: No Nonsense
Plugin URI: https://nononsensewp.com
Description: The fastest, cleanest way to get rid of the parts of WordPress you don't need.
Version: 3.3.1
Author: Room 34 Creative Services, LLC
Author URI: https://room34.com
License: GPLv2
Text Domain: no-nonsense
Domain Path: /i18n/languages/
*/

/*
  Copyright 2023 Room 34 Creative Services, LLC (email: info@room34.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


// Don't load directly
if (!defined('ABSPATH')) { exit; }


// Load required files
require_once(plugin_dir_path(__FILE__) . 'functions.php');
require_once(plugin_dir_path(__FILE__) . 'class-r34nono.php');


// Initialize plugin functionality
add_action('plugins_loaded', 'r34nono_plugins_loaded');
function r34nono_plugins_loaded() {

	// Instantiate class
	global $r34nono;
	$r34nono = new R34NoNo();
	
	// Load text domain
	load_plugin_textdomain('no-nonsense', false, basename(plugin_dir_path(__FILE__)) . '/i18n/languages/');
	
	// Conditionally run update function
	if (is_admin() && version_compare(get_option('r34nono_version'), @R34NoNo::VERSION, '<')) { r34nono_update(); }
	
}


// Install
register_activation_hook(__FILE__, 'r34nono_install');
function r34nono_install() {

	// Flush rewrite rules
	flush_rewrite_rules();
	
	// Set version
	update_option('r34nono_version', @R34NoNo::VERSION);

	// Admin notice with link to settings
	$notices = get_option('r34nono_deferred_admin_notices', array());
	$notices[] = array(
		'content' => '<p>' . sprintf(__('Thank you for installing %1$sNo Nonsense%2$s. To get started, please visit the %3$sSettings%4$s page.'), '<strong>', '</strong>', '<a href="' . admin_url('options-general.php?page=no-nonsense') . '"><strong>', '</strong></a>') . '</p>',
		'status' => 'info'
	);
	update_option('r34nono_deferred_admin_notices', $notices);
	
}


// Updates
function r34nono_update() {

	// Version-specific updates (checking against old version number; must run *before* updating option)
	if (version_compare(@R34NoNo::VERSION, '1.4.0', '<')) {
		if (get_option('r34nono_xmlrpc_disabled', null) !== null && get_option('r34nono_xmlrpc_enabled')) {
			update_option('r34nono_xmlrpc_disabled', get_option('r34nono_xmlrpc_enabled'));
			delete_option('r34nono_xmlrpc_enabled');
		}
		if (get_option('r34nono_login_replace_wp_logo_link', null) !== null && get_option('r34nono_login_remove_wp_logo')) {
			update_option('r34nono_login_replace_wp_logo_link', get_option('r34nono_login_remove_wp_logo'));
			delete_option('r34nono_login_remove_wp_logo');
		}
	}

	// Update version
	update_option('r34nono_version', @R34NoNo::VERSION);
	
}


// Deferred install/update admin notices
add_action('admin_notices', 'r34nono_deferred_admin_notices');
function r34nono_deferred_admin_notices() {
	if ($notices = get_option('r34nono_deferred_admin_notices', array())) {
		foreach ((array)$notices as $notice) {
			echo '<div class="notice notice-' . esc_attr($notice['status']) . ' is-dismissible r34nono-admin-notice">' . wp_kses_post($notice['content']) . '</div>';
		}
	}
	delete_option('r34nono_deferred_admin_notices');
}
