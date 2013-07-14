<?php
/*
Plugin Name: WordPress File Monitor Plus
Plugin URI: http://l3rady.com/projects/wordpress-file-monitor-plus/
Description: Monitor your website for added/changed/deleted files
Author: Scott Cariss
Version: 2.2
Author URI: http://l3rady.com/
Text Domain: wordpress-file-monitor-plus
Domain Path: /languages
*/

/*  Copyright 2012  Scott Cariss  (email : scott@l3rady.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Not a WordPress context? Stop.
! defined( 'ABSPATH' ) and exit;

global $current_blog;

define( 'SC_WPFMP_PLUGIN_FILE', __FILE__ );
define( 'SC_WPFMP_PLUGIN_FOLDER', dirname( SC_WPFMP_PLUGIN_FILE ) );
define( 'SC_WPFMP_CLASSES_FOLDER', SC_WPFMP_PLUGIN_FOLDER . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );
define( 'SC_WPFMP_FUNCTIONS_FOLDER', SC_WPFMP_PLUGIN_FOLDER . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR );

// Set data directory
$uploads = wp_upload_dir();
$uploads['basedir'] = str_replace( array('\\', '/'), DIRECTORY_SEPARATOR, $uploads['basedir'] );
define( 'SC_WPFMP_DATA_FOLDER', $uploads['basedir'] . DIRECTORY_SEPARATOR . 'WPFMP_DATA' . DIRECTORY_SEPARATOR );
define( 'SC_WPFMP_DATA_FOLDER_OLD', SC_WPFMP_PLUGIN_FOLDER . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR );
define( 'SC_WPFMP_FILE_SCAN_DATA', SC_WPFMP_DATA_FOLDER . '.sc_wpfmp_scan_data' );
define( 'SC_WPFMP_FILE_ALERT_CONTENT', SC_WPFMP_DATA_FOLDER . '.sc_wpfmp_admin_alert_content' );

// Define the permission to see/read/remove admin alert if not already set in config
if( ! defined( 'SC_WPFMP_ADMIN_ALERT_PERMISSION' ) )
{
    // If multisite then only allow network admins the permission to see alerts.
    if( is_multisite() )
        define( 'SC_WPFMP_ADMIN_ALERT_PERMISSION', 'manage_network_options' );
    else
        define( 'SC_WPFMP_ADMIN_ALERT_PERMISSION', 'manage_options' );
}

require SC_WPFMP_CLASSES_FOLDER . 'wpfmp.class.php';
require SC_WPFMP_CLASSES_FOLDER . 'wpfmp.settings.class.php';

require SC_WPFMP_FUNCTIONS_FOLDER . 'compatability.php';

// Only allow WPFMP to run on single sites or on a multisite if on current blog id.
if( ! is_multisite() || ( is_multisite() && $current_blog->blog_id == BLOG_ID_CURRENT_SITE ) )
{
    sc_WordPressFileMonitorPlus::init();
    sc_WordPressFileMonitorPlusSettings::init();
}
?>