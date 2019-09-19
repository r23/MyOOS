<?php
/**
 * Methods for frontend and backend in admin-only module
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\modules
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Search_Console;

use RankMath\Helper;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Conditional;

defined( 'ABSPATH' ) || exit;

/**
 * Search_Console class.
 */
class Search_Console_Common {

	use Hooker;

	/**
	 * The Constructor
	 */
	public function __construct() {
		if ( Conditional::is_heartbeat() ) {
			return;
		}

		if ( Helper::has_cap( 'search_console' ) ) {
			$this->filter( 'rank_math/admin_bar/items', 'admin_bar_items', 11 );
		}
		$this->action( 'rank_math/search_console/get_analytics', 'add_day_crawler' );
	}

	/**
	 * Add admin bar item.
	 *
	 * @param array $items Array of admin bar nodes.
	 *
	 * @return array
	 */
	public function admin_bar_items( $items ) {
		// Add link only if connected?
		$items['search-console'] = [
			'id'        => 'rank-math-search-console',
			'title'     => esc_html__( 'Search Console', 'rank-math' ),
			'href'      => Helper::get_admin_url( 'search-console' ),
			'parent'    => 'rank-math',
			'meta'      => [ 'title' => esc_html__( 'Review analytics and sitemaps', 'rank-math' ) ],
			'_priority' => 50,
		];

		return $items;
	}

	/**
	 * CRON Job.
	 */
	public function add_day_crawler() {
		$crawler = new Data_Fetcher;
		$start   = Helper::get_midnight( time() - DAY_IN_SECONDS );

		$crawler->push_to_queue( date_i18n( 'Y-m-d', $start - ( DAY_IN_SECONDS * 2 ) ) );
		$crawler->save()->dispatch();
	}
}
