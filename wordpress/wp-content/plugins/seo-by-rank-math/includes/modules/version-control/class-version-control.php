<?php
/**
 * The Version Control Class.
 *
 * @package    RankMath
 * @subpackage RankMath\Version_Control
 */

namespace RankMath;

use RankMath\Helper;
use RankMath\Module\Base;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Param;
use MyThemeShop\Helpers\Conditional;

/**
 * Version_Control class.
 */
class Version_Control {

	use Hooker;

	/**
	 * Plugin info transient key.
	 *
	 * @var string
	 */
	const TRANSIENT = 'rank_math_wporg_plugin_info';

	/**
	 * Wp.org plugins api URL.
	 *
	 * @var string
	 */
	const API_URL = 'https://api.wordpress.org/plugins/info/1.0/seo-by-rank-math.json';

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( Conditional::is_heartbeat() ) {
			return;
		}

		if ( Conditional::is_rest() ) {
			return;
		}

		$directory = dirname( __FILE__ );
		$this->config(
			[
				'id'        => 'status',
				'directory' => $directory,
			]
		);

		$this->hooks();

		$this->maybe_save_beta_optin();
		$this->maybe_save_auto_update();
	}

	/**
	 * Change beta_optin setting.
	 *
	 * @return bool Change successful.
	 */
	public function maybe_save_beta_optin() {
		if ( ! Param::post( 'beta_optin' ) || ! Param::post( '_wpnonce' ) ) {
			return false;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'rank-math-beta-optin' ) ) {
			return false;
		}

		// Sanitize input.
		$new_value = Param::post( 'beta_optin' ) === 'on' ? 'on' : 'off';

		$settings               = get_option( 'rank-math-options-general', array() );
		$settings['beta_optin'] = $new_value;
		rank_math()->settings->set( 'general', 'beta_optin', 'on' === $new_value ? true : false );
		update_option( 'rank-math-options-general', $settings );

		return true;
	}

	/**
	 * Change enable_auto_update setting.
	 *
	 * @return bool Change successful.
	 */
	public function maybe_save_auto_update() {
		if ( ! Param::post( 'enable_auto_update' ) || ! Param::post( '_wpnonce' ) ) {
			return false;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'rank-math-auto-update' ) ) {
			return false;
		}

		// Sanitize input.
		$new_value = Param::post( 'enable_auto_update' ) === 'on' ? 'on' : 'off';

		$settings                       = get_option( 'rank-math-options-general', array() );
		$settings['enable_auto_update'] = $new_value;
		rank_math()->settings->set( 'general', 'enable_auto_update', 'on' === $new_value ? true : false );
		update_option( 'rank-math-options-general', $settings );

		return true;
	}

	/**
	 * Register version control hooks.
	 */
	public function hooks() {
		if ( Helper::get_settings( 'general.beta_optin' ) ) {
			$beta_optin = new Beta_Optin();
			$beta_optin->hooks();
		}

		$this->filter( 'rank_math/tools/pages', 'add_status_page', 20 );
		$this->filter( 'rank_math/tools/default_tab', 'change_default_tab' );
		$this->action( 'admin_enqueue_scripts', 'enqueue', 20 );

		/* translators: Placeholder is version number. */
		Helper::add_json( 'rollbackConfirm', esc_html__( 'Are you sure you want to install version %s?', 'rank-math' ) );
	}

	/**
	 * Add subpage to Status & Tools screen.
	 *
	 * @param array $pages Pages.
	 * @return array       New pages.
	 */
	public function add_status_page( $pages ) {
		$new_pages = [];

		$new_pages['version_control'] = [
			'url'   => 'status',
			'args'  => 'view=version_control',
			'cap'   => 'install_plugins',
			'title' => __( 'Version Control', 'rank-math' ),
			'class' => '\\RankMath\\Version_Control',
		];

		$new_pages = array_merge( $new_pages, $pages );
		return $new_pages;
	}

	/**
	 * Change default tab on the Status & Tools screen.
	 *
	 * @param string $default Default tab.
	 * @return string         New default tab.
	 */
	public function change_default_tab( $default ) {
		return 'version_control';
	}

	/**
	 * Enqueue CSS & JS.
	 *
	 * @return void
	 */
	public function enqueue() {
		$uri = untrailingslashit( plugin_dir_url( __FILE__ ) );
		wp_enqueue_style( 'rank-math-cmb2' );
		wp_enqueue_style( 'rank-math-version-control', $uri . '/assets/version-control.css', array(), rank_math()->version );
		wp_enqueue_script( 'rank-math-version-control', $uri . '/assets/version-control.js', array( 'jquery' ), rank_math()->version, true );
	}

	/**
	 * Get Rank Math plugin information.
	 *
	 * @return mixed Plugin information array or false on fail.
	 */
	public static function get_plugin_info() {
		$cache = get_transient( self::TRANSIENT );
		if ( $cache ) {
			return $cache;
		}

		$request = wp_remote_get( self::API_URL, [ 'timeout' => 20 ] );
		if ( ! is_wp_error( $request ) && is_array( $request ) ) {
			$response = json_decode( $request['body'], true );
			set_transient( self::TRANSIENT, $response, ( 12 * HOUR_IN_SECONDS ) );
			return $response;
		}

		return false;
	}

	/**
	 * Get plugin data to use in the `update_plugins` transient.
	 *
	 * @param  string $version New version.
	 * @param  string $package New version download URL.
	 * @return array           An array of plugin metadata.
	 */
	public static function get_plugin_data( $version, $package ) {
		return [
			'id'          => 'w.org/plugins/seo-by-rank-math',
			'slug'        => 'seo-by-rank-math',
			'plugin'      => 'seo-by-rank-math/rank-math.php',
			'new_version' => $version,
			'url'         => 'https://wordpress.org/plugins/seo-by-rank-math/',
			'package'     => $package,
			'icons'       =>
			[
				'2x'  => 'https://ps.w.org/seo-by-rank-math/assets/icon-256x256.png?rev=2034417',
				'1x'  => 'https://ps.w.org/seo-by-rank-math/assets/icon.svg?rev=2034417',
				'svg' => 'https://ps.w.org/seo-by-rank-math/assets/icon.svg?rev=2034417',
			],
			'banners'     =>
			[
				'2x' => 'https://ps.w.org/seo-by-rank-math/assets/banner-1544x500.png?rev=2034417',
				'1x' => 'https://ps.w.org/seo-by-rank-math/assets/banner-772x250.png?rev=2034417',
			],
			'banners_rtl' => [],
		];
	}

	/**
	 * Display forms.
	 */
	public function display() {
		if ( Rollback_Version::should_rollback() ) {
			$rollback = new Rollback_Version();
			$rollback->rollback();
			return;
		}

		$directory       = dirname( __FILE__ );
		$beta_optin      = boolval( Helper::get_settings( 'general.beta_optin' ) );
		$auto_update     = boolval( Helper::get_settings( 'general.enable_auto_update' ) );
		$versions        = array_reverse( array_keys( Beta_Optin::get_available_versions( $beta_optin ) ) );
		$current_version = rank_math()->version;
		$latest_version  = Beta_Optin::get_latest_version();
		array_splice( $versions, 10 );

		include_once( $directory . '/views/version-control-panel.php' );
		include_once( $directory . '/views/beta-optin-panel.php' );
		include_once( $directory . '/views/auto-update-panel.php' );
	}

}
