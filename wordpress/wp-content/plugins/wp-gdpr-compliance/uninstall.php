<?php

// If uninstall is not called from WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die();
}

global $wpdb;

// Pages
$accessRequest = get_pages(array(
    'post_type' => 'page',
    'post_status' => 'publish,private,draft',
    'number' => 1,
    'meta_key' => '_wpgdprc_access_request',
    'meta_value' => '1'
));
if (!empty($accessRequest)) {
    wp_trash_post($accessRequest[0]->ID);
}

// Options
$wpdb->query("DELETE FROM `$wpdb->options` WHERE `option_name` LIKE 'wpgdprc\_%';");

// Tables
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->base_prefix}wpgdprc_access_requests`");
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->base_prefix}wpgdprc_delete_requests`");
$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->base_prefix}wpgdprc_consents`");

// Cronjobs
wp_clear_scheduled_hook('wpgdprc_deactivate_access_requests');

// Clear any cached data that has been removed
wp_cache_flush();