<?php
/**
 * The Beta Opt-in Class.
 *
 * @package    RankMath
 * @subpackage RankMath\Version_Control
 */

namespace RankMath;

use RankMath\Helper;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Param;

/**
 * Beta_Optin class.
 */
class Beta_Optin {

	use Hooker;

	/**
	 * Beta changelog URL.
	 *
	 * @var string
	 */
	const BETA_CHANGELOG_URL = 'https://rankmath.com/beta-changelog.txt';

	/**
	 * Actions and filters.
	 *
	 * @return void
	 */
	public function hooks() {
		$this->filter( 'site_transient_update_plugins', 'transient_update_plugins' );
		$this->action( 'in_plugin_update_message-seo-by-rank-math/rank-math.php', 'plugin_update_message', 10, 2 );
		$this->action( 'install_plugins_pre_plugin-information', 'beta_plugin_information' );
	}

	/**
	 * Replace plugin info popup for beta versions.
	 */
	public function beta_plugin_information() {
		if ( 'seo-by-rank-math' !== Param::request( 'plugin' ) ) {
			return;
		}

		$transient = get_site_transient( 'update_plugins' );
		if ( self::is_beta_update( $transient ) ) {
			$changelog_request = wp_remote_get( self::BETA_CHANGELOG_URL, [ 'timeout' => 15 ] );
			if ( ! is_array( $changelog_request ) || is_wp_error( $changelog_request ) ) {
				return;
			}
			echo '<html><head></head><body><pre>' . esc_html( $changelog_request['body'] ) . '</pre></body></html>';
			exit;
		}
	}

	/**
	 * Check if Rank Math update is a beta update in the transient.
	 *
	 * @param  mixed $transient Transient value.
	 * @return boolean          If it is a beta update or not.
	 */
	public function is_beta_update( $transient ) {
		return (
			is_object( $transient )
			&& ! empty( $transient->response )
			&& ! empty( $transient->response['seo-by-rank-math/rank-math.php'] )
			&& ! empty( $transient->response['seo-by-rank-math/rank-math.php']->is_beta )
		);
	}

	/**
	 * Get all available versions of Rank Math.
	 *
	 * @param boolean $beta  Include beta versions.
	 *
	 * @return array List of versions and download URLs.
	 */
	public static function get_available_versions( $beta = false ) {
		$versions    = array();
		$plugin_info = Version_Control::get_plugin_info();

		foreach ( (array) $plugin_info['versions'] as $version => $url ) {
			if ( ! self::is_eligible_version( $version, $beta ) ) {
				continue;
			}
			$versions[ $version ] = $url;
		}

		uksort( $versions, 'version_compare' );

		return $versions;
	}

	/**
	 * Check if version should be in the dropdown.
	 *
	 * @param  string  $version Version number.
	 * @param  boolean $beta    If beta versions should be included or not.
	 *
	 * @return boolean          If version should be in the dropdown.
	 */
	public static function is_eligible_version( $version, $beta ) {
		if ( 'trunk' === $version ) {
			return false;
		}

		if ( ! $beta && Str::contains( 'beta', $version ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get latest version available.
	 *
	 * @return string Latest version number.
	 */
	public static function get_latest_version() {
		$plugin_info = Version_Control::get_plugin_info();
		return $plugin_info['version'];
	}

	/**
	 * Get latest beta version available.
	 *
	 * @return string Latest beta version number.
	 */
	public function get_latest_beta_version() {
		$plugin_info = Version_Control::get_plugin_info();
		$versions    = $plugin_info['versions'];

		uksort( $versions, 'version_compare' );

		$versions = array_keys( $versions );
		$beta     = reset( $versions );

		foreach ( (array) $plugin_info['versions'] as $version => $url ) {
			if ( Str::contains( 'beta', $version ) ) {
				$beta = $version;
			}
		}

		return $beta;
	}

	/**
	 * Inject beta in the `update_plugins` transient to be able to update to it.
	 *
	 * @param  mixed $value Original value.
	 *
	 * @return mixed New value.
	 */
	public function transient_update_plugins( $value ) {
		$beta_version = $this->get_latest_beta_version();
		if ( version_compare( $beta_version, rank_math()->version, '>' ) ) {
			$value = $this->inject_beta( $value, $beta_version );
		}

		return $value;
	}

	/**
	 * Inject beta update in the transient value.
	 *
	 * @param  mixed  $value        Transient value.
	 * @param  string $beta_version Beta version number.
	 *
	 * @return mixed New transient value.
	 */
	public function inject_beta( $value, $beta_version ) {
		if ( empty( $value ) ) {
			$value = new \stdClass();
		}

		if ( empty( $value->response ) ) {
			$value->response = [];
		}

		$versions = self::get_available_versions( true );
		$value->response['seo-by-rank-math/rank-math.php'] = new \stdClass();

		$plugin_data = Version_Control::get_plugin_data( $beta_version, $versions[ $beta_version ] );
		foreach ( $plugin_data as $prop_key => $prop_value ) {
			$value->response['seo-by-rank-math/rank-math.php']->{$prop_key} = $prop_value;
		}

		$value->response['seo-by-rank-math/rank-math.php']->is_beta = true;

		if ( empty( $value->no_update ) ) {
			$value->no_update = [];
		}

		unset( $value->no_update['seo-by-rank-math/rank-math.php'] );

		return $value;
	}

	/**
	 * Add warning about beta version in the update notice.
	 *
	 * @param  array $plugin_data An array of plugin metadata.
	 * @param  array $response    An array of metadata about the available plugin update.
	 * @return void
	 */
	public function plugin_update_message( $plugin_data, $response ) {
		if ( empty( $plugin_data['is_beta'] ) ) {
			return;
		}

		printf(
			'</p><p class="rank-math-beta-update-notice">%s',
			esc_html__( 'This update will install a beta version of Rank Math.', 'rank-math' )
		);
	}
}
