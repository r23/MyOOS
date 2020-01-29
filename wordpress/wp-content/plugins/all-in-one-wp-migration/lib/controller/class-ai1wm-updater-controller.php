<?php
/**
 * Copyright (C) 2014-2020 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}

class Ai1wm_Updater_Controller {

	public static function plugins_api( $result, $action = null, $args = null ) {
		return Ai1wm_Updater::plugins_api( $result, $action, $args );
	}

	public static function pre_update_plugins( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// Check for updates
		Ai1wm_Updater::check_for_updates();

		return $transient;
	}

	public static function update_plugins( $transient ) {
		return Ai1wm_Updater::update_plugins( $transient );
	}

	public static function check_for_updates() {
		return Ai1wm_Updater::check_for_updates();
	}

	public static function plugin_row_meta( $links, $file ) {
		return Ai1wm_Updater::plugin_row_meta( $links, $file );
	}

	public static function updater( $params = array() ) {
		if ( check_ajax_referer( 'ai1wm_updater', 'ai1wm_nonce' ) ) {
			ai1wm_setup_environment();

			// Set params
			if ( empty( $params ) ) {
				$params = stripslashes_deep( $_POST );
			}

			// Set uuid
			$uuid = null;
			if ( isset( $params['ai1wm_uuid'] ) ) {
				$uuid = trim( $params['ai1wm_uuid'] );
			}

			// Set extension
			$extension = null;
			if ( isset( $params['ai1wm_extension'] ) ) {
				$extension = trim( $params['ai1wm_extension'] );
			}

			$extensions = Ai1wm_Extensions::get();

			// Verify whether extension exists
			if ( isset( $extensions[ $extension ] ) ) {
				update_option( $extensions[ $extension ]['key'], $uuid );
			}
		}
	}

	public static function upgrader_process_complete( $upgrader_object, $options ) {
		if ( ! isset( $options['action'], $options['type'], $options['plugins'] ) ) {
			return;
		}

		if ( $options['action'] !== 'update' ) {
			return;
		}

		if ( $options['type'] !== 'plugin' ) {
			return;
		}

		// Check if base plugin is updated
		if ( ! in_array( AI1WM_PLUGIN_BASENAME, $options['plugins'] ) ) {
			return;
		}

		// Check if storage folder is created
		if ( ! is_dir( AI1WM_STORAGE_PATH ) ) {
			Ai1wm_Directory::create( AI1WM_STORAGE_PATH );
		}

		// Check if backups folder is created
		if ( ! is_dir( AI1WM_BACKUPS_PATH ) ) {
			Ai1wm_Directory::create( AI1WM_BACKUPS_PATH );
		}

		Ai1wm_File_Index::create( AI1WM_STORAGE_INDEX_PHP );
		Ai1wm_File_Index::create( AI1WM_STORAGE_INDEX_HTML );
		Ai1wm_File_Index::create( AI1WM_BACKUPS_INDEX_PHP );
		Ai1wm_File_Index::create( AI1WM_BACKUPS_INDEX_HTML );
		Ai1wm_File_Htaccess::create( AI1WM_BACKUPS_HTACCESS );
		Ai1wm_File_Webconfig::create( AI1WM_BACKUPS_WEBCONFIG );
	}
}
