<?php
/**
 * The Analytics helpers.
 *
 * @since      1.0.86.2
 * @package    RankMath
 * @subpackage RankMath\Helpers
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Helpers;

use RankMath\Helper;
use RankMath\Google\Authentication;
use RankMath\Google\Console;

defined( 'ABSPATH' ) || exit;

/**
 * Analytics class.
 */
trait Analytics {

	/**
	 * Can add Analytics Frontend stats.
	 *
	 * @return bool
	 */
	public static function can_add_frontend_stats() {
		return Authentication::is_authorized() &&
			Console::is_console_connected() &&
			Helper::has_cap( 'analytics' ) &&
			apply_filters( 'rank_math/analytics/frontend_stats', Helper::get_settings( 'general.analytics_stats' ) );
	}
}
