<?php
/**
 * The Search Console Module
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\modules
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Search_Console;

use Exception;
use RankMath\Helper;
use RankMath\Module;
use RankMath\Admin\Admin_Helper;
use RankMath\Traits\Ajax;
use MyThemeShop\Admin\Page;
use MyThemeShop\Helpers\Arr;
use MyThemeShop\Helpers\Str;
use MyThemeShop\Helpers\Conditional;

defined( 'ABSPATH' ) || exit;

/**
 * Search_Console class.
 */
class Search_Console extends Module {

	use Ajax;

	/**
	 * Hold search console api client.
	 *
	 * @var Client
	 */
	public $client;

	/**
	 * Hold current tab id.
	 *
	 * @var string
	 */
	public $current_tab;

	/**
	 * Hold current filters.
	 *
	 * @var array
	 */
	public $filters = null;

	/**
	 * The Constructor
	 */
	public function __construct() {
		if ( Conditional::is_heartbeat() ) {
			return;
		}

		$directory = dirname( __FILE__ );
		$this->config(
			[
				'id'        => 'search-console',
				'directory' => $directory,
				'help'      => [
					'title' => esc_html__( 'Search Console', 'rank-math' ),
					'view'  => $directory . '/views/help.php',
				],
			]
		);
		parent::__construct();

		$this->client  = new Client;
		$this->crawler = new Data_Fetcher;

		if ( is_admin() ) {
			$this->action( 'rank_math/dashboard/widget', 'dashboard_widget', 10 );
			$this->filter( 'rank_math/settings/general', 'add_settings' );

			// AJAX.
			$this->ajax( 'search_console_authentication', 'authentication' );
			$this->ajax( 'search_console_deauthentication', 'deauthentication' );
			$this->ajax( 'search_console_get_profiles', 'get_profiles' );
			$this->ajax( 'search_console_delete_cache', 'delete_cache' );
			$this->ajax( 'search_console_get_cache', 'start_background_process' );
			$this->set_current_tab();
		}
	}

	/**
	 * Set current tab field.
	 */
	private function set_current_tab() {
		if ( ! $this->page->is_current_page() ) {
			return;
		}

		$this->get_filters();
		$this->current_tab = isset( $_GET['view'] ) ? $_GET['view'] : 'overview';

		if ( ! $this->client->is_authorized ) {
			return;
		}

		$class = 'RankMath\Search_Console\\' . ucfirst( $this->current_tab );
		if ( class_exists( $class ) ) {
			$this->{$this->current_tab} = new $class( $this->client );
		}
	}

	/**
	 * Render dashboard widget.
	 */
	public function dashboard_widget() {
		$today     = Helper::get_midnight( time() );
		$week      = $today - ( DAY_IN_SECONDS * 7 );
		$data_info = DB::data_info(
			[
				'start_date' => date( 'Y-m-d', $week ),
				'end_date'   => date( 'Y-m-d', $today ),
			]
		);
		?>
		<h3><?php esc_html_e( 'Search Console Stats', 'rank-math' ); ?></h3>
		<ul>
			<li><span><?php esc_html_e( 'Total Keywords', 'rank-math' ); ?></span><?php echo Str::human_number( $data_info['keywords'] ); ?></li>
			<li><span><?php esc_html_e( 'Total Pages', 'rank-math' ); ?></span><?php echo Str::human_number( $data_info['pages'] ); ?></li>
			<li><span><?php esc_html_e( 'Total Clicks', 'rank-math' ); ?></span><?php echo Str::human_number( $data_info['totals']->clicks ); ?></li>
			<li><span><?php esc_html_e( 'Total Impressions', 'rank-math' ); ?></span><?php echo Str::human_number( $data_info['totals']->impressions ); ?></li>
			<li><span><?php esc_html_e( 'Average Position', 'rank-math' ); ?></span><?php echo round( $data_info['totals']->position, 2 ); ?></li>
			<li><span><?php esc_html_e( 'Average CTR', 'rank-math' ); ?></span><?php echo round( $data_info['totals']->ctr, 2 ); ?></li>
		</ul>
		<?php
	}

	/**
	 * Register admin page.
	 */
	public function register_admin_page() {
		$plugin_uri = untrailingslashit( plugin_dir_url( __FILE__ ) );
		$this->page = new Page(
			'rank-math-search-console',
			esc_html__( 'Search Console', 'rank-math' ),
			[
				'position'   => 12,
				'parent'     => 'rank-math',
				'capability' => 'rank_math_search_console',
				'render'     => $this->directory . '/views/main.php',
				'classes'    => [ 'rank-math-page' ],
				'help'       => [
					'search-console-overview'  => [
						'title'   => esc_html__( 'Overview', 'rank-math' ),
						'content' => '<p>' . esc_html__( 'Connect Rank Math with Google Search Console to see the most important information from Google directly in your WordPress dashboard.', 'rank-math' ) . '</p>',
					],
					'search-console-analytics' => [
						'title'   => esc_html__( 'Screen Content', 'rank-math' ),
						'content' => '<p>' . esc_html__( 'The Search Analytics tab will give you insights about how your site performs in search engines: you can see the top search queries to find your site and your most popular landing pages.', 'rank-math' ) . '</p>',
					],
					'search-console-sitemaps'  => [
						'title'   => esc_html__( 'Available Actions', 'rank-math' ),
						'content' => '<p>' . esc_html__( 'The Sitemaps tab gives you an overview of the sitemaps submitted to the Search Console.', 'rank-math' ) . '</p>',
					],
				],
				'assets'     => [
					'styles'  => [
						'font-awesome'             => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
						'jquery-date-range-picker' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-date-range-picker/0.16.1/daterangepicker.min.css',
						'rank-math-search-console' => $plugin_uri . '/assets/search-console.css',
					],
					'scripts' => [
						'rank-math-common' => '',
						'momentjs'         => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js',
						'date-picker'      => 'https://cdnjs.cloudflare.com/ajax/libs/jquery-date-range-picker/0.16.1/jquery.daterangepicker.min.js',
						'google-charts'    => '//www.gstatic.com/charts/loader.js',
						'rank-math-sc'     => $plugin_uri . '/assets/search-console.js',
					],
				],
			]
		);
	}

	/**
	 * Add module settings into general optional panel.
	 *
	 * @param array $tabs Array of option panel tabs.
	 *
	 * @return array
	 */
	public function add_settings( $tabs ) {
		Arr::insert(
			$tabs,
			[
				'search-console' => [
					'icon'  => 'fa fa-search-plus',
					'title' => esc_html__( 'Search Console', 'rank-math' ),
					/* translators: Link to kb article */
					'desc'  => sprintf( esc_html__( 'Connect Rank Math with your Google Search Console profile to see the most important information from Google directly in your WordPress dashboard. %s.', 'rank-math' ), '<a href="' . \RankMath\KB::get( 'search-console-settings' ) . '" target="_blank">' . esc_html__( 'Learn more', 'rank-math' ) . '</a>' ),
					'file'  => $this->directory . '/views/options.php',
				],
			],
			9
		);

		return $tabs;
	}

	/**
	 * Display tabs.
	 */
	public function display_nav() {
		$tabs = [
			'overview'  => esc_html__( 'Overview', 'rank-math' ),
			'analytics' => esc_html__( 'Search Analytics', 'rank-math' ),
			'sitemaps'  => esc_html__( 'Sitemaps', 'rank-math' ),
			'tracker'   => esc_html__( 'Keyword Tracker', 'rank-math' ),
		];

		$this->is_sitemap_available( $tabs );
		$filters = $this->get_filters();
		?>
		<div class="rank-math-date-selector-container">
			<strong><?php echo esc_html( get_admin_page_title() ); ?></strong>
			<?php foreach ( $tabs as $id => $label ) : ?>
			<a class="<?php echo $id === $this->current_tab ? 'active' : ''; ?>" href="<?php echo esc_url( Helper::get_admin_url( 'search-console', 'view=' . $id ) ); ?>" title="<?php echo $label; ?>"><?php echo $label; ?></a>
			<?php endforeach; ?>

			<?php if ( in_array( $this->current_tab, [ 'overview', 'analytics' ], true ) ) : ?>
			<form method="post" action="" class="date-selector">
				<?php if ( 'analytics' === $this->current_tab ) : ?>
				<input type="text" id="rank-math-search" name="s" class="regular-text" placeholder="Search&hellip;" value="<?php echo isset( $_POST['s'] ) ? esc_attr( $_POST['s'] ) : ''; ?>">
				<select id="rank-math-overview-type" name="dimension">
					<option value="query"<?php selected( 'query', $filters['dimension'] ); ?>>Keywords</option>
					<option value="page"<?php selected( 'page', $filters['dimension'] ); ?>>Pages</option>
				</select>
				<?php endif; ?>
				<span class="input-group">
					<span class="dashicons dashicons-calendar-alt"></span>
					<input type="text" id="rank-math-date-selector" value="<?php echo $filters['picker']; ?>">
				</span>
				<input type="hidden" id="rank-math-start-date" name="start_date" value="<?php echo $filters['start']; ?>">
				<input type="hidden" id="rank-math-end-date" name="end_date" value="<?php echo $filters['end']; ?>">
			</form>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Authenticate google oauth code.
	 */
	public function authentication() {
		check_ajax_referer( 'rank-math-ajax-nonce', 'security' );
		$this->has_cap_ajax( 'search_console' );

		$code = isset( $_POST['code'] ) ? trim( wp_unslash( $_POST['code'] ) ) : false;
		if ( ! $code ) {
			$this->error( esc_html__( 'No authentication code found.', 'rank-math' ) );
		}

		$this->success( $this->client->get_access_token( $code ) );
	}

	/**
	 * Disconnect google authentication.
	 */
	public function deauthentication() {
		check_ajax_referer( 'rank-math-ajax-nonce', 'security' );
		$this->has_cap_ajax( 'search_console' );
		$this->client->disconnect();
		$this->crawler->kill_process();
		$this->success( 'done' );
	}

	/**
	 * Get profiles list.
	 */
	public function get_profiles() {
		check_ajax_referer( 'rank-math-ajax-nonce', 'security' );
		$this->has_cap_ajax( 'search_console' );

		$profiles = $this->client->get_profiles();
		if ( empty( $profiles ) ) {
			$this->error( 'No profiles found.' );
		}

		foreach ( $profiles as $key => $value ) {
			$profiles[ $key ] = str_replace( 'sc-domain:', __( 'Domain Property: ', 'rank-math' ), $value );
		}

		$this->success(
			[
				'profiles' => $profiles,
				'selected' => $this->select_profile( $profiles ),
			]
		);
	}

	/**
	 * Select profile
	 *
	 * @param array $profiles Array of fetched profiles.
	 *
	 * @return string
	 */
	private function select_profile( $profiles ) {
		$home_url = home_url( '/', 'https' );
		if ( in_array( $home_url, $profiles, true ) ) {
			return $home_url;
		}

		$home_url = home_url( '/', 'http' );
		if ( in_array( $home_url, $profiles, true ) ) {
			return $home_url;
		}

		return '';
	}

	/**
	 * Delete cache.
	 */
	public function delete_cache() {
		check_ajax_referer( 'rank-math-ajax-nonce', 'security' );
		$this->has_cap_ajax( 'search_console' );

		$days = isset( $_GET['days'] ) ? $_GET['days'] : false;
		if ( ! $days ) {
			$this->error( esc_html__( 'Not a valid settings founds to delete cache.', 'rank-math' ) );
		}

		DB::delete( intval( $days ) );
		$db_info            = DB::info();
		$db_info['message'] = sprintf( '<div class="rank-math-console-db-info"><span class="dashicons dashicons-calendar-alt"></span> Cached Days: <strong>%s</strong></div>', $db_info['days'] ) .
		sprintf( '<div class="rank-math-console-db-info"><span class="dashicons dashicons-editor-ul"></span> Data Rows: <strong>%s</strong></div>', Str::human_number( $db_info['rows'] ) ) .
		sprintf( '<div class="rank-math-console-db-info"><span class="dashicons dashicons-editor-code"></span> Size: <strong>%s</strong></div>', size_format( $db_info['size'] ) );

		$this->success( $db_info );
	}

	/**
	 * Get cache progressively.
	 */
	public function start_background_process() {
		check_ajax_referer( 'rank-math-ajax-nonce', 'security' );
		$this->has_cap_ajax( 'search_console' );

		if ( ! $this->client->is_authorized ) {
			$this->error( esc_html__( 'Google oAuth is not authorized.', 'rank-math' ) );
		}

		try {
			$days  = isset( $_GET['days'] ) ? $_GET['days'] : 90;
			$start = Helper::get_midnight( time() - DAY_IN_SECONDS );

			for ( $current = 1; $current <= $days; $current++ ) {
				$this->crawler->push_to_queue( date( 'Y-m-d', $start - ( DAY_IN_SECONDS * $current ) ) );
			}
			$this->crawler->save()->dispatch();
			$this->success( 'Data fetching started in the background.' );
		} catch ( Exception $error ) {
			$this->error( $error->getMessage() );
		}
	}

	/**
	 * Get analytics data.
	 *
	 * @param string $current Date to fetch data for.
	 */
	public function get_analytics_data( $current ) {
		set_time_limit( 300 );
		if ( DB::date_exists( $current ) ) {
			return true;
		}

		foreach ( [ 'page', 'query', 'date' ] as $metric ) {
			$rows = $this->query_analytics_data( $current, $current, $metric );
			foreach ( $rows as $row ) {
				DB::insert( $row, $current, $metric );
			}
		}

		DB::purge_cache();

		// Sleep to not hit 5 QPS Limit.
		sleep( 2 );

		return true;
	}

	/**
	 * Get current filters.
	 *
	 * @return array
	 */
	public function get_filters() {
		if ( ! is_null( $this->filters ) ) {
			return $this->filters;
		}

		$today     = Helper::get_midnight( time() );
		$end       = $this->get_filter_data( 'end_date', ( $today - ( DAY_IN_SECONDS * 1 ) ) );
		$start     = $this->get_filter_data( 'start_date', ( $today - ( DAY_IN_SECONDS * 30 ) ) );
		$dimension = $this->get_filter_data( 'dimension', 'query' );

		$start_date = date( 'Y-m-d', $start );
		$end_date   = date( 'Y-m-d', $end );
		$picker     = $start_date . ' to ' . $end_date;

		// Previous Dates.
		$prev_end_date   = $start - ( DAY_IN_SECONDS * 1 );
		$prev_start_date = $prev_end_date - abs( $start - $end );
		$prev_end_date   = date( 'Y-m-d', $prev_end_date );
		$prev_start_date = date( 'Y-m-d', $prev_start_date );

		// Difference.
		$diff          = abs( $start - $end ) / DAY_IN_SECONDS;
		$this->filters = compact( 'dimension', 'diff', 'picker', 'today', 'start', 'end', 'start_date', 'end_date', 'prev_start_date', 'prev_end_date' );

		return $this->filters;
	}

	/**
	 * If sitemap not available remove tab.
	 *
	 * @param array $tabs Array of tabs.
	 */
	private function is_sitemap_available( &$tabs ) {
		if ( ! $this->client->is_authorized ) {
			return;
		}

		$this->sitemaps = new Sitemaps( $this->client );
		if ( $this->sitemaps->selected_site_is_domain_property() ) {
			unset( $tabs['sitemaps'] );
		}
	}

	/**
	 * Get filter data.
	 *
	 * @param string $filter  Filter key.
	 * @param string $default Filter default value.
	 *
	 * @return mixed
	 */
	private function get_filter_data( $filter, $default ) {
		$cookie_key = 'rank_math_sc_' . $filter;
		if ( isset( $_POST[ $filter ] ) && ! empty( $_POST[ $filter ] ) ) {
			$value = $_POST[ $filter ];
			setcookie( $cookie_key, $value, time() + ( HOUR_IN_SECONDS * 30 ), COOKIEPATH, COOKIE_DOMAIN );
			return $value;
		}

		if ( ! empty( $_COOKIE[ $cookie_key ] ) ) {
			$value = $_COOKIE[ $cookie_key ];
			return $value;
		}

		return $default;
	}

	/**
	 * Query analytics data from google client api.
	 *
	 * @param string  $start_date Start date.
	 * @param string  $end_date   End date.
	 * @param string  $dimension  Dimension of data.
	 * @param integer $limit      Number of rows.
	 *
	 * @return array
	 */
	private function query_analytics_data( $start_date, $end_date, $dimension, $limit = 5000 ) {
		$response = $this->client->post(
			'https://www.googleapis.com/webmasters/v3/sites/' . urlencode( $this->client->profile ) . '/searchAnalytics/query',
			[
				'startDate'  => $start_date,
				'endDate'    => $end_date,
				'rowLimit'   => $limit,
				'dimensions' => [ $dimension ],
			]
		);

		$rows = false;
		if ( 'success' === $response['status'] ) {
			if ( isset( $response['body']['rows'] ) ) {
				$rows = $response['body']['rows'];
				$rows = $this->normalize_analytics_data( $rows );
			}
		}

		return $rows ? $rows : [];
	}

	/**
	 * Normalize analytics data.
	 *
	 * @param array $rows Array of rows.
	 *
	 * @return array
	 */
	private function normalize_analytics_data( $rows ) {
		foreach ( $rows as &$row ) {
			$row['ctr']      = round( $row['ctr'] * 100, 2 );
			$row['position'] = round( $row['position'], 2 );
		}

		return $rows;
	}
}
