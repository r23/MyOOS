<?php
/**
 * The Module
 *
 * @since      1.0.32
 * @package    RankMath
 * @subpackage RankMath\Module
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Module;

use RankMath\Helper;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Conditional;

defined( 'ABSPATH' ) || exit;

/**
 * Manager class.
 */
class Manager {

	use Hooker;

	/**
	 * Holds modules.
	 *
	 * @var array
	 */
	public $modules = [];

	/**
	 * Holds module objects.
	 *
	 * @var array
	 */
	private $controls = [];

	/**
	 * The Constructor.
	 */
	public function __construct() {
		if ( Conditional::is_heartbeat() ) {
			return;
		}

		$this->action( 'plugins_loaded', 'setup_modules' );
		$this->filter( 'rank_math/modules', 'setup_core', 1 );
		$this->filter( 'rank_math/modules', 'setup_admin_only', 1 );
		$this->filter( 'rank_math/modules', 'setup_internals', 1 );
		$this->filter( 'rank_math/modules', 'setup_3rd_party', 1 );

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
		$modules = $this->do_filter( 'modules', [] );

		ksort( $modules );
		foreach ( $modules as $id => $module ) {
			$this->add_module( $id, $module );
		}
	}

	/**
	 * Setup core modules.
	 *
	 * @param array $modules Array of modules.
	 *
	 * @return array
	 */
	public function setup_core( $modules ) {
		$modules['404-monitor'] = [
			'title'    => esc_html__( '404 Monitor', 'rank-math' ),
			'desc'     => esc_html__( 'Records the URLs on which visitors & search engines run into 404 Errors. You can also turn on Redirections to redirect the error causing URLs to other URLs.', 'rank-math' ),
			'class'    => 'RankMath\Monitor\Monitor',
			'icon'     => 'dashicons-dismiss',
			'settings' => Helper::get_admin_url( 'options-general' ) . '#setting-panel-404-monitor',
		];

		$modules['local-seo'] = [
			'title'    => esc_html__( 'Local SEO & Google Knowledge Graph', 'rank-math' ),
			'desc'     => esc_html__( 'Dominate the search results for local audience by optimizing your website and posts using this Rank Math module.', 'rank-math' ),
			'class'    => 'RankMath\Local_Seo\Local_Seo',
			'icon'     => 'dashicons-location-alt',
			'settings' => Helper::get_admin_url( 'options-titles' ) . '#setting-panel-local',
		];

		$modules['redirections'] = [
			'title'    => esc_html__( 'Redirections', 'rank-math' ),
			'desc'     => esc_html__( 'Redirect non-existent content easily with 301 and 302 status code. This can help reduce errors and improve your site ranking.', 'rank-math' ),
			'class'    => 'RankMath\Redirections\Redirections',
			'icon'     => 'dashicons-randomize',
			'settings' => Helper::get_admin_url( 'options-general' ) . '#setting-panel-redirections',
		];

		$modules['rich-snippet'] = [
			'title'    => esc_html__( 'Rich Snippets', 'rank-math' ),
			'desc'     => esc_html__( 'Enable support for the Rich Snippets, which adds metadata to your website, resulting in rich search results and more traffic.', 'rank-math' ),
			'class'    => 'RankMath\RichSnippet\RichSnippet',
			'icon'     => 'dashicons-awards',
			'settings' => Helper::get_admin_url( 'options-titles' ) . '#setting-panel-post-type-post',
		];

		$modules['sitemap'] = [
			'title'    => esc_html__( 'Sitemap', 'rank-math' ),
			'desc'     => esc_html__( 'Enable Rank Math\'s sitemap feature, which helps search engines index your website\'s content effectively.', 'rank-math' ),
			'class'    => 'RankMath\Sitemap\Sitemap',
			'icon'     => 'dashicons-networking',
			'settings' => Helper::get_admin_url( 'options-sitemap' ),
		];

		$modules['link-counter'] = [
			'title' => esc_html__( 'Link Counter', 'rank-math' ),
			'desc'  => esc_html__( 'Counts the total number of internal, external links, to and from links inside your posts.', 'rank-math' ),
			'class' => 'RankMath\Links\Links',
			'icon'  => 'dashicons-admin-links',
		];

		return $modules;
	}

	/**
	 * Setup admin only modules.
	 *
	 * @param array $modules Array of modules.
	 *
	 * @return array
	 */
	public function setup_admin_only( $modules ) {

		$modules['role-manager'] = [
			'title'    => esc_html__( 'Role Manager', 'rank-math' ),
			'desc'     => esc_html__( 'The Role Manager allows you to use internal WordPress\' roles to control which of your site admins can change Rank Math\'s settings', 'rank-math' ),
			'class'    => 'RankMath\Role_Manager\Role_Manager',
			'icon'     => 'dashicons-admin-users',
			'only'     => 'admin',
			'settings' => Helper::get_admin_url( 'role-manager' ),
		];

		$modules['search-console'] = [
			'title'    => esc_html__( 'Search Console', 'rank-math' ),
			'desc'     => esc_html__( 'Connect Rank Math with Google Search Console to see the most important information from Google directly in your WordPress dashboard.', 'rank-math' ),
			'class'    => 'RankMath\Search_Console\Search_Console',
			'icon'     => 'dashicons-search',
			'only'     => 'admin',
			'settings' => Helper::get_admin_url( 'options-general' ) . '#setting-panel-search-console',
		];

		$modules['seo-analysis'] = [
			'title'    => esc_html__( 'SEO Analysis', 'rank-math' ),
			'desc'     => esc_html__( 'Let Rank Math analyze your website and your website\'s content using 70+ different tests to provide tailor-made SEO Analysis to you.', 'rank-math' ),
			'class'    => 'RankMath\SEO_Analysis\SEO_Analysis',
			'icon'     => 'dashicons-chart-bar',
			'only'     => 'admin',
			'settings' => Helper::get_admin_url( 'seo-analysis' ),
		];

		return $modules;
	}

	/**
	 * Setup internal modules.
	 *
	 * @param array $modules Array of modules.
	 *
	 * @return array
	 */
	public function setup_internals( $modules ) {

		$modules['robots-txt'] = [
			'title' => esc_html__( 'Robotx Txt', 'rank-math' ),
			'only'  => 'internal',
			'class' => 'RankMath\Robots_Txt',
		];

		$modules['status'] = [
			'title' => esc_html__( 'Status', 'rank-math' ),
			'only'  => 'internal',
			'class' => 'RankMath\Status\Status',
		];

		return $modules;
	}

	/**
	 * Setup 3rd party modules.
	 *
	 * @param array $modules Array of modules.
	 *
	 * @return array
	 */
	public function setup_3rd_party( $modules ) {

		$modules['amp'] = [
			'title' => esc_html__( 'AMP', 'rank-math' ),
			'desc'  => sprintf(
				/* translators: Link to AMP plugin */
				esc_html__( 'Install %s from WordPress.org to make Rank Math work with Accelerated Mobile Pages. It is required because AMP are different than WordPress pages and our plugin doesn\'t work with them out-of-the-box.', 'rank-math' ),
				'<a href="' . Helper::get_admin_url( 'help#help-panel-amp' ) . '">' . esc_html__( 'AMP plugin', 'rank-math' ) . '</a>'
			),
			'icon'  => 'dashicons-smartphone',
			'only'  => 'skip',
		];

		$modules['bbpress'] = [
			'title'         => esc_html__( 'bbPress', 'rank-math' ),
			'desc'          => esc_html__( 'Add proper Meta tags to your bbPress forum posts, categories, profiles, etc. Get more options to take control of what search engines see and how they see it.', 'rank-math' ),
			'icon'          => 'dashicons-cart',
			'disabled'      => ( ! function_exists( 'is_bbpress' ) ),
			'disabled_text' => esc_html__( 'Please activate bbPress plugin to use this module.', 'rank-math' ),
			'only'          => 'skip',
		];

		$modules['buddypress'] = [
			'title'         => esc_html__( 'BuddyPress', 'rank-math' ),
			'desc'          => esc_html__( 'Add proper Meta tags to your BuddyPress pages.', 'rank-math' ),
			'icon'          => 'dashicons-cart',
			'class'         => 'RankMath\BuddyPress\BuddyPress',
			'disabled'      => ! class_exists( 'BuddyPress' ),
			'disabled_text' => esc_html__( 'Please activate BuddyPress plugin to use this module.', 'rank-math' ),
		];

		$modules['woocommerce'] = [
			'title'         => esc_html__( 'WooCommerce', 'rank-math' ),
			'desc'          => esc_html__( 'WooCommerce module to use Rank Math to optimize WooCommerce Product Pages.', 'rank-math' ),
			'class'         => 'RankMath\WooCommerce\WooCommerce',
			'icon'          => 'dashicons-cart',
			'disabled'      => ( ! Conditional::is_woocommerce_active() ),
			'disabled_text' => esc_html__( 'Please activate WooCommerce plugin to use this module.', 'rank-math' ),
		];

		$modules['acf'] = [
			'title'         => esc_html__( 'ACF', 'rank-math' ),
			'desc'          => esc_html__( 'ACF support helps Rank Math SEO read and analyze content written in the Advanced Custom Fields. If your theme uses ACF, you should enable this option.', 'rank-math' ),
			'class'         => 'RankMath\ACF\ACF',
			'icon'          => 'dashicons-text',
			'disabled'      => ( ! function_exists( 'acf' ) ),
			'disabled_text' => esc_html__( 'Please activate ACF plugin to use this module.', 'rank-math' ),
		];

		return $modules;
	}

	/**
	 * Add module.
	 *
	 * @param string $id   Module unique id.
	 * @param array  $args Module configuration.
	 */
	public function add_module( $id, $args = [] ) {
		$this->modules[ $id ] = new Module( $id, $args );
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
			foreach ( $this->modules as $module ) :
				if ( ! $module->can_display() ) {
					continue;
				}

				$is_active   = $module->is_active();
				$is_disabled = $module->is_disabled();
				?>
				<div class="col">

					<div class="rank-math-box <?php echo $is_active ? 'active' : ''; ?>">

						<span class="dashicons <?php echo $module->get_icon(); ?>"></span>

						<header>

							<h3><?php echo $module->get( 'title' ); ?></h3>

							<p><em><?php echo $module->get( 'desc' ); ?></em></p>

							<?php $module->the_link(); ?>

						</header>

						<div class="status wp-clearfix">

							<span class="rank-math-switch">
								<input type="checkbox" class="rank-math-modules" id="module-<?php echo $module->get_id(); ?>" name="modules[]" value="<?php echo $module->get_id(); ?>"<?php checked( $is_active ); ?> <?php disabled( $is_disabled, true ); ?>>
								<label for="module-<?php echo $module->get_id(); ?>" class="<?php echo $is_disabled ? 'rank-math-tooltip' : ''; ?>"><?php esc_html_e( 'Toggle', 'rank-math' ); ?>
									<?php echo $module->has( 'disabled_text' ) ? '<span>' . $module->get( 'disabled_text' ) . '</span>' : ''; ?>
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
	 * Load active modules.
	 */
	public function load_modules() {
		foreach ( $this->modules as $id => $module ) {
			if ( false === $module->can_load_module() ) {
				continue;
			}

			$this->load_module( $id, $module );
		}
	}

	/**
	 * Load single module.
	 *
	 * @param string $id ID of module.
	 * @param Module $module Module instance.
	 */
	private function load_module( $id, $module ) {
		$object_class = $module->get( 'class' );
		if ( $module->is_admin() ) {
			$this->load_module_common( $module );
			if ( ! is_admin() ) {
				return;
			}
		}

		if ( class_exists( $object_class ) ) {
			$this->controls[ $id ] = new $object_class;
		}
	}

	/**
	 * Load module common file.
	 *
	 * @param Module $module Module instance.
	 */
	public function load_module_common( $module ) {
		$object_class = $module->get( 'class' );
		if ( class_exists( $object_class . '_Common' ) ) {
			$module_common_class                             = $object_class . '_Common';
			$this->controls[ $module->get_id() . '_common' ] = new $module_common_class;
		}
	}

	/**
	 * Get module by ID.
	 *
	 * @param string $id ID to get module.
	 *
	 * @return object Module class object.
	 */
	public function get_module( $id ) {
		return isset( $this->controls[ $id ] ) ? $this->controls[ $id ] : false;
	}
}
