<?php

namespace wp_gdpr\lib;

Class Gdpr_Helper {

	/**
	 * This gets the version of the core plugin
	 *
	 * @return mixed
	 *
	 * @since   1.5
	 */
	public static function get_core_version() {
		$plugin_data = get_plugin_data( GDPR_DIR . 'wp-gdpr-core.php' );

		return $plugin_data['Version'];
	}


	/**
	 * Gets plugins.json data and validate
	 *
	 * @return array    array  validate list of the plugins
	 *
	 * @since    1.5
	 */
	public static function get_plugin_addon_status() {
		if ( is_file( GDPR_DIR . 'assets/json/plugins.json' ) ) {
			$plugin_json  = file_get_contents( GDPR_DIR . 'assets/json/plugins.json' );
			$plugin_json  = json_decode( $plugin_json, true );
		} else {
			$plugin_json  = array();
		}

		$plugins = static::check_plugin_active($plugin_json);

		return $plugins;
	}

	/**
	 * Validate if the plugin and the add-on exist
	 *
	 * @param $plugin   array   list of the add-ons
	 *
	 * @return array    array   validated list of the add-ons
	 * @since   1.5
	 */
	private static function check_plugin_active($plugin){
		return array_map( function ( $data ) {
			$all_plugins    = get_plugins();
			if ( isset( $data['name'], $data['data_stored_in'] ) ) {
				if ( is_plugin_active( $data['plugin_name'] ) === true ) {
					$data['status_related_plugin'] = 'active';
				} else {
					$data['status_related_plugin'] = 'inactive';
				}

				if ( isset( $all_plugins[ $data['plugin_wp_gdpr'] ] ) ) {
					if ( is_plugin_active( $data['plugin_wp_gdpr'] ) === true ) {
						$data['status'] = 'active';
					} else {
						$data['status'] = 'inactive';
					}
				} else {
					$data['status'] = 'not-installed';
				}

				return $data;
			} else {
				$data['status_related_plugin'] = 'name-not-given';
				$data['status'] = 'related-plugin-not-installed';
				return $data;
			}

		}, $plugin );
	}

	/**
	 * Generate an activation URL for a plugin like the ones found in WordPress plugin administration screen.
	 *
	 * @param  string $plugin = myplugin/myplugin.php
	 *
	 * @return string         The plugin activation url
	 *
	 * @since 1.5
	 */
	public static function generatePluginActivationLinkUrl($plugin) {

		$activateUrl = wp_nonce_url(admin_url('plugins.php?action=activate&plugin='.$plugin), 'activate-plugin_'.$plugin);

		return $activateUrl;
	}
}
