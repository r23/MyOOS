<?php
/**
 * The Dashboard Widget of the plugin.
 *
 * @since      1.0.81
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath;

use RankMath\Helper;
use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Dashboard_Widget class.
 *
 * @codeCoverageIgnore
 */
class Dashboard_Widget {

	use Hooker;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->action( 'wp_dashboard_setup', 'add_dashboard_widgets' );
		$this->action( 'rank_math/dashboard/widget', 'dashboard_widget_feed', 98 );
		$this->action( 'rank_math/dashboard/widget', 'dashboard_widget_footer', 99 );
	}

	/**
	 * Register dashboard widget.
	 */
	public function add_dashboard_widgets() {
		// Early Bail if action is not registered for the dashboard widget hook.
		if (
			! Helper::is_module_active( '404-monitor' ) &&
			! Helper::is_module_active( 'redirections' ) &&
			! Helper::is_module_active( 'analytics' )
		) {
			return;
		}

		$icon = '<span class="rank-math-icon"><svg viewBox="0 0 462.03 462.03" xmlns="http://www.w3.org/2000/svg" width="20"><g><path d="m462 234.84-76.17 3.43 13.43 21-127 81.18-126-52.93-146.26 60.97 10.14 24.34 136.1-56.71 128.57 54 138.69-88.61 13.43 21z"></path><path d="m54.1 312.78 92.18-38.41 4.49 1.89v-54.58h-96.67zm210.9-223.57v235.05l7.26 3 89.43-57.05v-181zm-105.44 190.79 96.67 40.62v-165.19h-96.67z"></path></g></svg></span>';

		wp_add_dashboard_widget(
			'rank_math_dashboard_widget',
			$icon . esc_html__( 'Rank Math Overview', 'rank-math' ),
			[ $this, 'render_dashboard_widget' ],
			null,
			null,
			'normal',
			'high'
		);
	}

	/**
	 * Render dashboard widget.
	 */
	public function render_dashboard_widget() {
		echo '<div id="rank-math-dashboard-widget" class="rank-math-loading"></div>';
	}

	/**
	 * Add Feed data in the admin dashboard widget.
	 */
	public function dashboard_widget_feed() {
		$posts = $this->get_feed();
		?>
		<h3 class="rank-math-blog-title"><?php esc_html_e( 'Latest Blog Posts from Rank Math', 'rank-math' ); ?></h3>
		<?php if ( false === $posts ) : ?>
			<p><?php esc_html_e( 'Error in fetching.', 'rank-math' ); ?></p>
			<?php
			return;
		endif;

		echo '<ul class="rank-math-blog-list">';
		$is_new = time() - strtotime( $posts[0]['date'] ) < 15 * DAY_IN_SECONDS;
		foreach ( $posts as $index => $post ) :
			?>
			<li class="rank-math-blog-post">
				<h4>
					<?php if ( $is_new ) : ?>
						<span class="rank-math-new-badge"><?php esc_html_e( 'NEW', 'rank-math' ); ?></span>
					<?php endif; ?>
					<a target="_blank" href="<?php echo esc_url( $post['link'] ); ?>?utm_source=Plugin&utm_medium=Dashboard%20Widget%20Post%20<?php echo esc_attr( $index + 1 ); ?>&utm_campaign=WP">
						<?php echo esc_html( $post['title']['rendered'] ); ?>
					</a>
				</h4>
			</li>
			<?php
			$is_new = false;
		endforeach;
		echo '</ul>';
	}

	/**
	 * Add footer in the admin dashboard widget.
	 */
	public function dashboard_widget_footer() {
		?>
		<div class="rank-math-widget-footer">
			<a target="_blank" href="https://rankmath.com/blog/?utm_source=Plugin&utm_medium=Dashboard%20Widget%20Blog&utm_campaign=WP">
				<?php esc_html_e( 'Blog', 'rank-math' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'rank-math' ); ?></span>
				<span aria-hidden="true" class="dashicons dashicons-external"></span>
			</a>
			<a target="_blank" href="https://rankmath.com/kb/?utm_source=Plugin&utm_medium=Dashboard%20Widget%20Help&utm_campaign=WP">
				<?php esc_html_e( 'Help', 'rank-math' ); ?>
				<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'rank-math' ); ?></span>
				<span aria-hidden="true" class="dashicons dashicons-external"></span>
			</a>
			<?php if ( ! defined( 'RANK_MATH_PRO_FILE' ) ) { ?>
				<a target="_blank" href="https://rankmath.com/pricing/?utm_source=Plugin&utm_medium=Dashboard%20Widget%20PRO&utm_campaign=WP" class="rank-math-widget-go-pro">
					<?php esc_html_e( 'Go Pro', 'rank-math' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'rank-math' ); ?></span>
					<span aria-hidden="true" class="dashicons dashicons-external"></span>
				</a>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Get posts.
	 */
	private function get_feed() {
		$cache_key = 'rank_math_feed_posts';
		$cache     = get_transient( $cache_key );
		if ( false !== $cache ) {
			return $cache;
		}

		$response = wp_remote_get( 'https://rankmath.com/wp-json/wp/v2/posts?per_page=3' );

		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			set_transient( $cache_key, [], 2 * HOUR_IN_SECONDS );

			return false;
		}

		$posts = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $posts ) || ! is_array( $posts ) ) {
			set_transient( $cache_key, [], 2 * HOUR_IN_SECONDS );

			return false;
		}

		set_transient( $cache_key, $posts, DAY_IN_SECONDS * 15 );

		return $posts;
	}
}
