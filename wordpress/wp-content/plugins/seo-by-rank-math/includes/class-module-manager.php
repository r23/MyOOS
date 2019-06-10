<?php
/**
 * The Module Manager
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath;

use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Conditional;

defined( 'ABSPATH' ) || exit;

/**
 * Module_Manager class.
 */
class Module_Manager {

	use Hooker;

	/**
	 * Hold modules.
	 *
	 * @var array
	 */
	public $modules = [];

	/**
	 * Hold module object.
	 *
	 * @var array
	 */
	private $controls = [];

	/**
	 * Hold active module ids.
	 *
	 * @var array
	 */
	private $active = [];

	/**
	 * The Constructor.
	 */
	public function __construct() {
		if ( Conditional::is_heartbeat() ) {
			return;
		}

		$this->action( 'plugins_loaded', 'setup_modules' );
		$this->action( 'plugins_loaded', 'load_modules', 11 );
		add_action( 'rank_math/module_changed', [ '\RankMath\Admin\Watcher', 'module_changed' ], 10, 2 );
	}

	/**
	 * Include default modules support.
	 */
	public function setup_modules() {
		/**
		 * Filters the array of modules available to be activated.
		 *
		 * @param array $modules Array of available modules.
		 */
		$modules = $this->do_filter( 'modules', array(
			'404-monitor'    => array(
				'id'            => '404-monitor',
				'title'         => esc_html__( '404 Monitor', 'rank-math' ),
				'desc'          => esc_html__( 'Records the URLs on which visitors & search engines run into 404 Errors. You can also turn on Redirections to redirect the error causing URLs to other URLs.', 'rank-math' ),
				'class'         => 'RankMath\Monitor\Monitor',
				'icon'          => 'dashicons-dismiss',
				'settings_link' => Helper::get_admin_url( 'options-general' ) . '#setting-panel-404-monitor',
			),

			'local-seo'      => array(
				'id'            => 'local-seo',
				'title'         => esc_html__( 'Local SEO & Google Knowledge Graph', 'rank-math' ),
				'desc'          => esc_html__( 'Dominate the search results for local audience by optimizing your website and posts using this Rank Math module.', 'rank-math' ),
				'class'         => 'RankMath\Local_Seo\Local_Seo',
				'icon'          => 'dashicons-location-alt',
				'settings_link' => Helper::get_admin_url( 'options-titles' ) . '#setting-panel-local',
			),

			'redirections'   => array(
				'id'            => 'redirections',
				'title'         => esc_html__( 'Redirections', 'rank-math' ),
				'desc'          => esc_html__( 'Redirect non-existent content easily with 301 and 302 status code. This can help reduce errors and improve your site ranking.', 'rank-math' ),
				'class'         => 'RankMath\Redirections\Redirections',
				'icon'          => 'dashicons-randomize',
				'settings_link' => Helper::get_admin_url( 'options-general' ) . '#setting-panel-redirections',
			),

			'rich-snippet'   => array(
				'id'            => 'rich-snippet',
				'title'         => esc_html__( 'Rich Snippets', 'rank-math' ),
				'desc'          => esc_html__( 'Enable support for the Rich Snippets, which adds metadata to your website, resulting in rich search results and more traffic.', 'rank-math' ),
				'class'         => 'RankMath\RichSnippet\RichSnippet',
				'icon'          => 'dashicons-awards',
				'settings_link' => Helper::get_admin_url( 'options-titles' ) . '#setting-panel-post-type-post',
			),

			'role-manager'   => array(
				'id'            => 'role-manager',
				'title'         => esc_html__( 'Role Manager', 'rank-math' ),
				'desc'          => esc_html__( 'The Role Manager allows you to use internal WordPress\' roles to control which of your site admins can change Rank Math\'s settings', 'rank-math' ),
				'class'         => 'RankMath\Role_Manager\Role_Manager',
				'icon'          => 'dashicons-admin-users',
				'only'          => 'admin',
				'settings_link' => Helper::get_admin_url( 'role-manager' ),
			),

			'search-console' => array(
				'id'            => 'search-console',
				'title'         => esc_html__( 'Search Console', 'rank-math' ),
				'desc'          => esc_html__( 'Connect Rank Math with Google Search Console to see the most important information from Google directly in your WordPress dashboard.', 'rank-math' ),
				'class'         => 'RankMath\Search_Console\Search_Console',
				'icon'          => 'dashicons-search',
				'only'          => 'admin',
				'settings_link' => Helper::get_admin_url( 'options-general' ) . '#setting-panel-search-console',
			),

			'seo-analysis'   => array(
				'id'            => 'seo-analysis',
				'title'         => esc_html__( 'SEO Analysis', 'rank-math' ),
				'desc'          => esc_html__( 'Let Rank Math analyze your website and your website\'s content using 70+ different tests to provide tailor-made SEO Analysis to you.', 'rank-math' ),
				'class'         => 'RankMath\SEO_Analysis\SEO_Analysis',
				'icon'          => 'dashicons-chart-bar',
				'only'          => 'admin',
				'settings_link' => Helper::get_admin_url( 'seo-analysis' ),
			),

			'sitemap'        => array(
				'id'            => 'sitemap',
				'title'         => esc_html__( 'Sitemap', 'rank-math' ),
				'desc'          => esc_html__( 'Enable Rank Math\'s sitemap feature, which helps search engines index your website\'s content effectively.', 'rank-math' ),
				'class'         => 'RankMath\Sitemap\Sitemap',
				'icon'          => 'dashicons-networking',
				'settings_link' => Helper::get_admin_url( 'options-sitemap' ),
			),

			'amp'            => array(
				'id'    => 'amp',
				'title' => esc_html__( 'AMP', 'rank-math' ),
				'desc'  => sprintf(
					/* translators: Link to AMP plugin */
					esc_html__( 'Install %s from WordPress.org to make Rank Math work with Accelerated Mobile Pages. It is required because AMP are different than WordPress pages and our plugin doesn\'t work with them out-of-the-box.', 'rank-math' ),
					'<a href="' . Helper::get_admin_url( 'help#help-panel-amp' ) . '">' . esc_html__( 'AMP plugin', 'rank-math' ) . '</a>'
				),
				'icon'  => 'dashicons-smartphone',
				'only'  => 'skip',
			),

			'woocommerce'    => array(
				'id'            => 'woocommerce',
				'title'         => esc_html__( 'WooCommerce', 'rank-math' ),
				'desc'          => esc_html__( 'WooCommerce module to use Rank Math to optimize WooCommerce Product Pages.', 'rank-math' ),
				'class'         => 'RankMath\WooCommerce\WooCommerce',
				'icon'          => 'dashicons-cart',
				'disabled'      => ( ! Conditional::is_woocommerce_active() ),
				'disabled_text' => esc_html__( 'Please activate WooCommerce plugin to use this module.', 'rank-math' ),
			),

			'link-counter'   => array(
				'id'    => 'link-counter',
				'title' => esc_html__( 'Link Counter', 'rank-math' ),
				'desc'  => esc_html__( 'Counts the total number of internal, external links, to and from links inside your posts.', 'rank-math' ),
				'class' => 'RankMath\Links\Links',
				'icon'  => 'dashicons-admin-links',
			),
			'bbpress'        => array(
				'id'            => 'bbpress',
				'title'         => esc_html__( 'bbPress', 'rank-math' ),
				'desc'          => esc_html__( 'Add proper Meta tags to your bbPress forum posts, categories, profiles, etc. Get more options to take control of what search engines see and how they see it.', 'rank-math' ),
				'icon'          => 'dashicons-cart',
				'disabled'      => ( ! function_exists( 'is_bbpress' ) ),
				'disabled_text' => esc_html__( 'Please activate bbPress plugin to use this module.', 'rank-math' ),
				'only'          => 'skip',
			),
		) );

		ksort( $modules );
		foreach ( $modules as $module ) {
			$this->add_module( $module );
		}
	}

	/**
	 * Load active modules.
	 */
	public function load_modules() {
		$this->active = get_option( 'rank_math_modules', [] );

		foreach ( $this->modules as $id => $module ) {
			if ( false === $this->can_load_module( $id, $module ) ) {
				continue;
			}

			if ( isset( $module['only'] ) && 'admin' === $module['only'] ) {
				if ( class_exists( $module['class'] . '_Common' ) ) {
					$module_common_class               = $module['class'] . '_Common';
					$this->controls[ $id . '_common' ] = new $module_common_class;
				}
				if ( ! is_admin() ) {
					continue;
				}
			}

			if ( isset( $module['class'] ) ) {
				$this->controls[ $id ] = new $module['class'];
			} else {
				$module_init_file      = isset( $module['file'] ) ? $module['file'] : rank_math()->includes_dir() . "modules/{$id}/class-rank-math-{$id}.php";
				$this->controls[ $id ] = require_once $module_init_file;
			}
		}
	}

	/**
	 * Check if we can load the module
	 *
	 * @param  string $module_id ID to get module.
	 * @param  string $module Module arguments.
	 * @return bool
	 */
	private function can_load_module( $module_id, $module ) {
		// If its an internal module should be loaded all the time.
		$is_internal = isset( $module['only'] ) && 'internal' === $module['only'];
		if ( $is_internal ) {
			return true;
		}

		$is_disabled = isset( $module['disabled'] ) && $module['disabled'];
		$can_skip    = isset( $module['only'] ) && 'skip' === $module['only'];
		$inactive    = ! is_array( $this->active ) || ! in_array( $module_id, $this->active );
		if ( $is_disabled || $can_skip || $inactive ) {
			return false;
		}

		return true;
	}

	/**
	 * Display module form to enable/disable them.
	 *
	 * @codeCoverageIgnore
	 */
	public function display_form() {
		if ( ! current_user_can( 'manage_options' ) ) {
			echo 'You cant access this page.';
			return;
		}
		?>
		<div class="rank-math-ui module-listing">

			<div class="two-col">
			<?php
			foreach ( $this->modules as $module_id => $module ) :
				if ( isset( $module['only'] ) && 'internal' === $module['only'] ) {
					continue;
				}

				$is_active   = is_array( $this->active ) && in_array( $module_id, $this->active );
				$label_class = '';
				if ( isset( $module['disabled'] ) && $module['disabled'] ) {
					$is_active   = false;
					$label_class = 'rank-math-tooltip';
				}
				?>
				<div class="col">
					<div class="rank-math-box <?php echo $is_active ? 'active' : ''; ?>">

						<span class="dashicons <?php echo isset( $module['icon'] ) ? $module['icon'] : 'dashicons-category'; ?>"></span>

						<header>
							<h3><?php echo $module['title']; ?></h3>

							<p><em><?php echo $module['desc']; ?></em></p>

							<?php if ( ! empty( $module['settings_link'] ) ) : ?>
								<a class="module-settings" href="<?php echo esc_url( $module['settings_link'] ); ?>"><?php esc_html_e( 'Settings', 'rank-math' ); ?></a>
							<?php endif; ?>

						</header>
						<div class="status wp-clearfix">
							<span class="rank-math-switch">
								<input type="checkbox" class="rank-math-modules" id="module-<?php echo $module_id; ?>" name="modules[]" value="<?php echo $module_id; ?>"<?php checked( $is_active ); ?> <?php disabled( ( isset( $module['disabled'] ) && $module['disabled'] ), true ); ?>>
								<label for="module-<?php echo $module_id; ?>" class="<?php echo $label_class; ?>"><?php esc_html_e( 'Toggle', 'rank-math' ); ?>
									<?php echo isset( $module['disabled_text'] ) ? '<span>' . $module['disabled_text'] . '</span>' : ''; ?>
								</label>
								<span class="input-loading"></span>
							</span>
							<label>
								<?php esc_html_e( 'Status:', 'rank-math' ); ?>
								<span class="module-status active-text"><?php echo esc_html__( 'Active', 'rank-math' ); ?> </span>
								<span class="module-status inactive-text"><?php echo esc_html__( 'Inactive', 'rank-math' ); ?> </span>
							</label>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			</div>

		</div>
		<?php
	}

	/**
	 * Get active modules.
	 *
	 * @codeCoverageIgnore
	 *
	 * @return array
	 */
	public function get_active_modules() {
		return $this->active;
	}

	/**
	 * Get module by id.
	 *
	 * @param  string $id ID to get module.
	 * @return object     Module class object.
	 */
	public function get_module( $id ) {
		return isset( $this->controls[ $id ] ) ? $this->controls[ $id ] : false;
	}

	/**
	 * Add module.
	 *
	 * @param array $args Module configuration.
	 */
	public function add_module( $args = [] ) {
		$this->modules[ $args['id'] ] = $args;
	}
}
