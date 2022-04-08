<?php
/**
 * Show Analytics stats on frontend.
 *
 * @since      1.0.86
 * @package    RankMath
 * @subpackage RankMath\Analytics
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Analytics;

use RankMath\Helper;
use RankMath\Traits\Hooker;
use RankMath\Google\Authentication;
use MyThemeShop\Helpers\Conditional;

defined( 'ABSPATH' ) || exit;

/**
 * Analytics_Stats class.
 */
class Analytics_Stats {

	use Hooker;

	/**
	 * The Constructor
	 */
	public function __construct() {
		if (
			Conditional::is_heartbeat() ||
			! Authentication::is_authorized() ||
			! Helper::has_cap( 'analytics' ) ||
			! Helper::get_settings( 'general.analytics_stats' ) ||
			! \RankMath\Google\Console::is_console_connected()
		) {
			return;
		}

		$this->action( 'wp_enqueue_scripts', 'enqueue' );
	}

	/**
	 * Enqueue Styles and Scripts
	 */
	public function enqueue() {
		if ( ! is_singular() || is_admin() || is_preview() ) {
			return;
		}

		$uri = untrailingslashit( plugin_dir_url( __FILE__ ) );
		wp_enqueue_style( 'rank-math-analytics-stats', $uri . '/assets/css/admin-bar.css', null, rank_math()->version );
		wp_enqueue_script( 'rank-math-analytics-stats', $uri . '/assets/js/admin-bar.js', [ 'jquery', 'wp-api-fetch', 'wp-element', 'wp-components' ], rank_math()->version, true );

		Helper::add_json( 'isAnalyticsConnected', \RankMath\Google\Analytics::is_analytics_connected() );
	}
}
