<?php
/**
 * The admin engine of the plugin.
 *
 * @since      1.0.9
 * @package    RankMath
 * @subpackage RankMath\Admin
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Admin;

use RankMath\Helper;
use RankMath\Updates;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Param;
use MyThemeShop\Helpers\Conditional;
use RankMath\Search_Console\Search_Console;

defined( 'ABSPATH' ) || exit;

/**
 * Engine class.
 *
 * @codeCoverageIgnore
 */
class Engine {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {

		rank_math()->admin        = new Admin;
		rank_math()->admin_assets = new Assets;

		$this->load_review_tab();
		$this->load_setup_wizard();
		$this->search_console_ajax();

		$runners = [
			rank_math()->admin,
			rank_math()->admin_assets,
			new Admin_Menu,
			new Option_Center,
			new Notices,
			new CMB2_Fields,
			new Deactivate_Survey,
			new Metabox,
			new Post_Columns,
			new Post_Filters,
			new Import_Export,
			new Updates,
			new Watcher,
		];

		foreach ( $runners as $runner ) {
			$runner->hooks();
		}

		/**
		 * Fires when admin is loaded.
		 */
		$this->do_action( 'admin/loaded' );
	}

	/**
	 * Load setup wizard.
	 */
	private function load_setup_wizard() {
		if ( filter_input( INPUT_GET, 'page' ) === 'rank-math-wizard' || filter_input( INPUT_POST, 'action' ) === 'rank_math_save_wizard' ) {
			new Setup_Wizard;
		}
	}

	/**
	 * Search console ajax handler.
	 */
	private function search_console_ajax() {
		if ( ! Conditional::is_ajax() || class_exists( 'Search_Console' ) ) {
			return;
		}

		$action = Param::post( 'action' );
		if ( $action && in_array( $action, [ 'rank_math_search_console_authentication', 'rank_math_search_console_deauthentication', 'rank_math_search_console_get_profiles' ], true ) ) {
			Helper::update_modules( [ 'search-console' => 'on' ] );
			new Search_Console;
		}
	}

	/**
	 * Load review tab in metabox.
	 */
	private function load_review_tab() {
		if (
			get_option( 'rank_math_already_reviewed' ) ||
			get_option( 'rank_math_install_date' ) + ( 2 * WEEK_IN_SECONDS ) > current_time( 'timestamp' )
		) {
			return;
		}

		$review = new Ask_Review;
		$review->hooks();
	}
}
