<?php
/**
 * The 404 Monitor Module
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Monitor
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Monitor;

use RankMath\Helper;
use RankMath\Module\Base;
use MyThemeShop\Admin\Page;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Arr;
use MyThemeShop\Helpers\Param;
use MyThemeShop\Helpers\WordPress;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class.
 */
class Admin extends Base {

	/**
	 * The Constructor.
	 *
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		$directory = dirname( __FILE__ );
		$this->config(
			[
				'id'             => '404-monitor',
				'directory'      => $directory,
				'table'          => 'RankMath\Monitor\Table',
				'help'           => [
					'title' => esc_html__( '404 Monitor', 'rank-math' ),
					'view'  => $directory . '/views/help.php',
				],
				'screen_options' => [
					'id'      => 'rank_math_404_monitor_per_page',
					'default' => 100,
				],
			]
		);
		parent::__construct();

		if ( $this->page->is_current_page() ) {
			$this->action( 'init', 'init' );
		}

		if ( Helper::has_cap( '404_monitor' ) ) {
			$this->action( 'rank_math/dashboard/widget', 'dashboard_widget', 11 );
			$this->filter( 'rank_math/settings/general', 'add_settings' );
		}
	}

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public function init() {
		$action = WordPress::get_request_action();
		if ( false === $action || ! in_array( $action, [ 'delete', 'clear_log' ], true ) ) {
			return;
		}

		if ( ! check_admin_referer( 'bulk-events' ) ) {
			check_admin_referer( '404_delete_log', 'security' );
		}

		$action = 'do_' . $action;
		$this->$action();
	}

	/**
	 * Delete selected log.
	 */
	protected function do_delete() {
		$log = Param::request( 'log', '', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		if ( empty( $log ) ) {
			return;
		}

		$count = DB::delete_log( $log );
		if ( $count > 0 ) {
			Helper::add_notification(
				/* translators: delete counter */
				sprintf( esc_html__( '%d log(s) deleted.', 'rank-math' ), $count ),
				[ 'type' => 'success' ]
			);
		}
	}

	/**
	 * Clear logs.
	 */
	protected function do_clear_log() {
		$count = DB::get_count();
		DB::clear_logs();

		Helper::add_notification(
			/* translators: delete counter */
			sprintf( esc_html__( 'Log cleared - %d items deleted.', 'rank-math' ), $count ),
			[ 'type' => 'success' ]
		);
	}

	/**
	 * Register admin page.
	 *
	 * @codeCoverageIgnore
	 */
	public function register_admin_page() {

		$dir = $this->directory . '/views/';
		$uri = untrailingslashit( plugin_dir_url( __FILE__ ) );

		$this->page = new Page( 'rank-math-404-monitor', esc_html__( '404 Monitor', 'rank-math' ), [
			'position'   => 12,
			'parent'     => 'rank-math',
			'capability' => 'rank_math_404_monitor',
			'render'     => $dir . 'main.php',
			'classes'    => [ 'rank-math-page' ],
			'help'       => [
				'404-overview'       => [
					'title' => esc_html__( 'Overview', 'rank-math' ),
					'view'  => $dir . 'help-tab-overview.php',
				],
				'404-screen-content' => [
					'title' => esc_html__( 'Screen Content', 'rank-math' ),
					'view'  => $dir . 'help-tab-screen-content.php',
				],
				'404-actions'        => [
					'title' => esc_html__( 'Available Actions', 'rank-math' ),
					'view'  => $dir . 'help-tab-actions.php',
				],
				'404-bulk'           => [
					'title' => esc_html__( 'Bulk Actions', 'rank-math' ),
					'view'  => $dir . 'help-tab-bulk.php',
				],
			],
			'assets'     => [
				'styles'  => [ 'rank-math-common' => '' ],
				'scripts' => [ 'rank-math-404-monitor' => $uri . '/assets/404-monitor.js' ],
			],
		]);

		if ( $this->page->is_current_page() ) {
			Helper::add_json( 'logConfirmClear', esc_html__( 'Are you sure you wish to delete all 404 error logs?', 'rank-math' ) );
			Helper::add_json( 'redirectionsUri', Helper::get_admin_url( 'redirections' ) );
		}
	}

	/**
	 * Add module settings into general optional panel.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param array $tabs Array of option panel tabs.
	 *
	 * @return array
	 */
	public function add_settings( $tabs ) {

		Arr::insert( $tabs, [
			'404-monitor' => [
				'icon'  => 'dashicons dashicons-no',
				'title' => esc_html__( '404 Monitor', 'rank-math' ),
				/* translators: 1. Link to kb article 2. Link to redirection setting scree */
				'desc'  => sprintf( esc_html__( 'The 404 monitor lets you see the URLs where visitors and search engine crawlers run into 404 not found errors on your site. %1$s. Turn on %2$s too to redirect the faulty URLs easily.', 'rank-math' ), '<a href="' . \RankMath\KB::get( '404-monitor-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>', '<a href="' . Helper::get_admin_url( 'options-general#setting-panel-redirections' ) . '" target="_blank">' . esc_html__( 'Redirections', 'rank-math' ) . '</a>' ),
				'file'  => $this->directory . '/views/options.php',
			],
		], 7 );

		return $tabs;
	}

	/**
	 * Add stats into admin dashboard.
	 *
	 * @codeCoverageIgnore
	 */
	public function dashboard_widget() {
		$data = DB::get_stats();
		?>
		<br />
		<h3><?php esc_html_e( '404 Monitor Stats', 'rank-math' ); ?></h3>
		<ul>
			<li><span><?php esc_html_e( '404 Monitor Log Count', 'rank-math' ); ?></span><?php echo Str::human_number( $data->total ); ?></li>
			<li><span><?php esc_html_e( '404 URI Hits', 'rank-math' ); ?></span><?php echo Str::human_number( $data->hits ); ?></li>
		</ul>
		<?php
	}
}
