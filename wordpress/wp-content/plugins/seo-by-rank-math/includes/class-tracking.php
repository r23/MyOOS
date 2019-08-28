<?php
/**
 * This class handles the tracking routine.
 *
 * No personal information is tracked, only basic environment, general settings and user counts and admin email for discount code.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath;

use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Param;

defined( 'ABSPATH' ) || exit;

/**
 * Tracking class.
 */
class Tracking {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->action( 'rank_math/tracker/send_event', 'send' );
		$this->filter( 'rank_math/tracker_data', 'server' );
		$this->filter( 'rank_math/tracker_data', 'wordpress' ); // phpcs:ignore
		$this->filter( 'rank_math/tracker_data', 'theme' );
		$this->filter( 'rank_math/tracker_data', 'plugins' );
	}

	/**
	 * Send tracking data.
	 */
	public function send() {
		/**
		 * Get all the tracking data.
		 *
		 * @param array
		 */
		$data = $this->do_filter( 'tracker_data', [
			'@timestamp'  => (int) date_i18n( 'Uv' ),
			'name'        => get_option( 'blogname' ),
			'url'         => home_url(),
			'admin_url'   => admin_url(),
			'admin_email' => get_option( 'admin_email' ),
		]);

		wp_safe_remote_post( 'https://rankmath.com/wp-json/rankmath/v1/trackingMetrices', [
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => false,
			'sslverify'   => false,
			'headers'     => [ 'user-agent' => 'RankMathTracker/' . md5( esc_url( home_url( '/' ) ) ) . ';' ],
			'body'        => wp_json_encode( $data ),
			'cookies'     => [],
		]);
	}

	/**
	 * Collect server related data.
	 *
	 * @param array $data Array of tracking data.
	 * @return array
	 */
	public function server( $data ) {

		$server = [];
		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) && ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$server['software'] = Param::server( 'SERVER_SOFTWARE' );
		}

		if ( function_exists( 'phpversion' ) ) {
			$server['php_version'] = phpversion();
		}

		if ( function_exists( 'ini_get' ) ) {
			$server['php_post_max_size']  = size_format( Str::let_to_num( ini_get( 'post_max_size' ) ) );
			$server['php_time_limt']      = ini_get( 'max_execution_time' );
			$server['php_max_input_vars'] = ini_get( 'max_input_vars' );
			$server['php_suhosin']        = extension_loaded( 'suhosin' ) ? 'Yes' : 'No';
		}

		// Validate if the server address is a valid IP-address.
		$ipaddress = Param::server( 'SERVER_ADDR', false, FILTER_VALIDATE_IP );
		if ( $ipaddress ) {
			$server['ip']       = $ipaddress;
			$server['hostname'] = gethostbyaddr( $ipaddress );
		}

		$server['curl_version']   = $this->get_curl_info();
		$server['php_extensions'] = [
			'imagick' => extension_loaded( 'imagick' ),
			'filter'  => extension_loaded( 'filter' ),
			'bcmath'  => extension_loaded( 'bcmath' ),
			'modXml'  => extension_loaded( 'modXml' ),
			'pcre'    => extension_loaded( 'pcre' ),
			'xml'     => extension_loaded( 'xml' ),
		];

		$data['server'] = $server;
		return $data;
	}

	/**
	 * Collect WordPress related data.
	 *
	 * @param array $data Array of tracking data.
	 * @return array
	 */
	public function wordpress( $data ) {
		global $wp_version;

		$memory = Str::let_to_num( WP_MEMORY_LIMIT );
		if ( function_exists( 'memory_get_usage' ) ) {
			$system_memory = Str::let_to_num( ini_get( 'memory_limit' ) );
			$memory        = max( $memory, $system_memory );
		}

		$data['wordpress'] = [
			'version'      => $wp_version,
			'multisite'    => is_multisite() ? 'Yes' : 'No',
			'locale'       => get_locale(),
			'memory_limit' => size_format( $memory ),
			'debug_mode'   => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No',
		];

		return $data;
	}

	/**
	 * Collect active theme data.
	 *
	 * @param array $data Array of tracking data.
	 * @return array
	 */
	public function theme( $data ) {

		$theme      = wp_get_theme();
		$theme_data = [
			'name'         => $theme->get( 'Name' ),
			'url'          => $theme->get( 'ThemeURI' ),
			'version'      => $theme->get( 'Version' ),
			'parent_theme' => is_child_theme() ? $theme->get( 'Template' ) : null,
			'wc_support'   => current_theme_supports( 'woocommerce' ) ? 'Yes' : 'No',
		];

		$data['theme'] = $theme_data;
		return $data;
	}

	/**
	 * Collect active plugins data.
	 *
	 * @param array $data Array of tracking data.
	 * @return array
	 */
	public function plugins( $data ) {

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = wp_get_active_and_valid_plugins();
		$plugins = array_map( 'get_plugin_data', $plugins );
		$plugins = array_map( [ $this, 'format_plugin' ], $plugins );

		$data['plugins'] = $plugins;
		return $data;
	}

	/**
	 * Get curl version and SSL support.
	 *
	 * @return array|null The curl info in an array or null when curl isn't available.
	 */
	private function get_curl_info() {

		if ( ! function_exists( 'curl_version' ) ) {
			return null;
		}

		$ssl  = true;
		$curl = curl_version();

		if ( ! $curl['features'] && CURL_VERSION_SSL ) {
			$ssl = false;
		}

		return [
			'version'    => $curl['version'],
			'sslSupport' => $ssl,
		];
	}

	/**
	 * Format the plugin array.
	 *
	 * @param  array $plugin The plugin info.
	 * @return array The formatted array.
	 */
	protected function format_plugin( array $plugin ) {
		return [
			'name'    => $plugin['Name'],
			'url'     => $plugin['PluginURI'],
			'version' => $plugin['Version'],
			'author'  => [
				'name' => strip_tags( $plugin['Author'] ),
				'url'  => $plugin['AuthorURI'],
			],
		];
	}
}
