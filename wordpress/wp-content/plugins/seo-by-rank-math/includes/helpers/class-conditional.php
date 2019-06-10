<?php
/**
 * The Conditional helpers.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Helpers
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Helpers;

use RankMath\Helper;
use RankMath\Admin\Admin_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Conditional class.
 */
trait Conditional {

	/**
	 * Check if whitelabel filter is active.
	 *
	 * @return boolean
	 */
	public static function is_whitelabel() {
		return apply_filters( 'rank_math/whitelabel', false );
	}

	/**
	 * Is module active.
	 *
	 * @param  string $id ID to get module.
	 * @return boolean
	 */
	public static function is_module_active( $id ) {
		$active_modules = get_option( 'rank_math_modules', [] );
		if ( ! is_array( $active_modules ) || ! isset( rank_math()->manager ) || is_null( rank_math()->manager ) ) {
			return false;
		}

		return in_array( $id, $active_modules ) && array_key_exists( $id, rank_math()->manager->modules );
	}

	/**
	 * Checks if the plugin is configured.
	 *
	 * @param bool $value If this param is set, the option will be updated.
	 * @return bool Return the option value if param is not set.
	 */
	public static function is_configured( $value = null ) {
		$key = 'rank_math_is_configured';
		if ( is_null( $value ) ) {
			$value = get_option( $key );
			return ! empty( $value );
		}
		Helper::schedule_flush_rewrite();
		update_option( $key, $value );
	}

	/**
	 * Check if site is connected to rank math api.
	 *
	 * @return bool
	 */
	public static function is_site_connected() {
		$registered = Admin_Helper::get_registration_data();

		return false !== $registered && $registered['connected'];
	}

	/**
	 * Check that the plugin is licensed properly.
	 *
	 * @return bool
	 */
	public static function is_invalid_registration() {
		$is_skipped = Helper::is_plugin_active_for_network() ? get_blog_option( get_main_site_id(), 'rank_math_registration_skip' ) : get_option( 'rank_math_registration_skip' );
		if ( true === boolval( $is_skipped ) ) {
			return false;
		}

		return ! self::is_site_connected();
	}

	/**
	 * Check if author archive are indexable
	 *
	 * @return bool
	 */
	public static function is_author_archive_indexable() {
		if ( true === Helper::get_settings( 'titles.disable_author_archives' ) ) {
			return false;
		}

		if ( Helper::get_settings( 'titles.author_custom_robots' ) && in_array( 'noindex', (array) Helper::get_settings( 'titles.author_robots' ), true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if the WP-REST-API is available.
	 *
	 * @param  string $minimum_version The minimum version the API should be.
	 * @return bool Returns true if the API is available.
	 */
	public static function is_api_available( $minimum_version = '2.0' ) {
		return ( defined( 'REST_API_VERSION' ) && version_compare( REST_API_VERSION, $minimum_version, '>=' ) );
	}

	/**
	 * Check if AMP module is active.
	 *
	 * @return bool
	 *
	 * @since 1.0.24
	 */
	public static function is_amp_active() {
		if ( ! self::is_module_active( 'amp' ) ) {
			return false;
		}

		if ( function_exists( 'ampforwp_get_setting' ) && 'rank_math' === ampforwp_get_setting( 'ampforwp-seo-selection' ) ) {
			return false;
		}

		return true;
	}
}
