<?php
/**
 * The Redirector
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Redirections
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Redirections;

use WP_Query;
use RankMath\Helper;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Str;

defined( 'ABSPATH' ) || exit;

/**
 * Redirector class.
 */
class Redirector {

	use Hooker;

	/**
	 * Matched redirection.
	 *
	 * @var array
	 */
	private $matched = false;

	/**
	 * Redirect to this url.
	 *
	 * @var string
	 */
	private $redirect_to;

	/**
	 * Current request uri.
	 *
	 * @var string
	 */
	private $uri = '';

	/**
	 * From cache.
	 *
	 * @var bool
	 */
	private $cache = false;

	/**
	 * Sets the error template to include.
	 *
	 * @var string
	 */
	protected $template_file_path;

	/**
	 * The Construct
	 */
	public function __construct() {
		$this->start();
		$this->flow();
		if ( is_array( $this->matched ) ) {
			$this->redirect();
		}
	}

	/**
	 * Set the required values.
	 */
	private function start() {
		$this->uri = str_replace( site_url( '/' ), '', $_SERVER['REQUEST_URI'] );
		$this->uri = trim( $this->uri, '/' );
		$this->uri = urldecode( $this->uri );
		$this->uri = trim( Redirection::strip_subdirectory( $this->uri ), '/' );
	}

	/**
	 * Run the system flow.
	 */
	private function flow() {
		$flow = [ 'from_cahce', 'everything', 'fallback' ];
		foreach ( $flow as $func ) {
			if ( false !== $this->matched ) {
				break;
			}

			$this->$func();
		}
	}

	/**
	 * If we got a match redirect.
	 */
	private function redirect() {
		if ( isset( $this->matched['id'], $this->matched['url_to'] ) ) {
			DB::update_access( $this->matched );
		}

		$header_code = $this->get_header_code();
		if ( in_array( $header_code, [ 410, 451 ], true ) ) {
			$this->redirect_without_target( $header_code );
			return;
		}

		// Debug if on.
		$this->do_debugging();

		// @codeCoverageIgnoreStart
		$x_redirect_by = 'WordPress';
		if ( true === $this->do_filter( 'redirection/add_redirect_header', true ) ) {
			$x_redirect_by = 'Rank Math SEO';
		}

		if ( wp_redirect( $this->redirect_to, $header_code, $x_redirect_by ) ) {
			exit;
		}
		// @codeCoverageIgnoreEnd
	}

	/**
	 * Handles the redirects without a target by setting the needed hooks.
	 *
	 * @param string $header_code The type of the redirect.
	 *
	 * @return void
	 */
	private function redirect_without_target( $header_code ) {
		$has_include_hook = $this->set_template_include_hook( $header_code );
		if ( ! $has_include_hook ) {
			$this->set_404();
		}

		if ( 410 === $header_code ) {
			status_header( 410 );
		}

		if ( 451 === $header_code ) {
			status_header( 451, 'Unavailable For Legal Reasons' );
		}
	}

	/**
	 * Sets the hook for setting the template include. This is the file that we want to show.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param string $template The template to look for.
	 *
	 * @return bool True when template should be included.
	 */
	protected function set_template_include_hook( $template ) {
		$this->template_file_path = get_query_template( $template );
		if ( ! empty( $this->template_file_path ) ) {
			$this->filter( 'template_include', 'set_template_include' );
			return true;
		}

		return false;
	}

	/**
	 * Returns the template that should be included.
	 *
	 * @codeCoverageIgnore
	 *
	 * @param string $template The template that will included before executing hook.
	 *
	 * @return string Returns the template that should be included.
	 */
	public function set_template_include( $template ) {
		if ( ! empty( $this->template_file_path ) ) {
			return $this->template_file_path;
		}

		return $template;
	}

	/**
	 * Search from cache.
	 */
	private function from_cahce() {
		// If there is a queried object.
		$object_id = get_queried_object_id();
		if ( $object_id ) {
			$redirection = Cache::get_by_object_id( $object_id, $this->current_object_type() );
			if ( $redirection && trim( $redirection->from_url, '/' ) === $this->uri ) {
				$this->cache = true;
				$this->set_redirection( $redirection->redirection_id );
				return;
			}
		}

		$redirection = Cache::get_by_url( $this->uri );
		if ( $redirection ) {
			$this->cache = true;
			$this->set_redirection( $redirection->redirection_id );
			return;
		}
	}

	/**
	 * Search for everything rules.
	 */
	private function everything() {
		$redirection = DB::match_redirections( $this->uri );
		if ( $redirection ) {
			Cache::add([
				'from_url'       => $this->uri,
				'redirection_id' => $redirection['id'],
				'object_id'      => 0,
				'object_type'    => 'any',
				'is_redirected'  => '1',
			]);
			$this->set_redirection( $redirection );
		}
	}

	/**
	 * Do the fallback strategy here.
	 *
	 * @codeCoverageIgnore
	 */
	private function fallback() {
		if ( ! is_404() ) {
			return;
		}

		$behavior = Helper::get_settings( 'general.redirections_fallback' );
		if ( 'default' === $behavior ) {
			return;
		}

		if ( 'homepage' === $behavior ) {
			$this->matched     = true;
			$this->redirect_to = site_url();
			return;
		}

		$custom_url = Helper::get_settings( 'general.redirections_custom_url' );
		if ( ! empty( $custom_url ) ) {
			$this->matched     = true;
			$this->redirect_to = $custom_url;
		}
	}

	/**
	 * Do debugging
	 *
	 * @codeCoverageIgnore
	 */
	private function do_debugging() {
		if ( ! Helper::get_settings( 'general.redirections_debug' ) | ! Helper::has_cap( 'redirections' ) ) {
			return;
		}

		$this->filter( 'user_has_cap', 'filter_user_has_cap' );

		if ( ! function_exists( 'get_current_screen' ) ) {
			require_once ABSPATH . 'wp-admin/includes/screen.php';
		}

		$assets_uri = untrailingslashit( plugin_dir_url( __FILE__ ) );
		include_once \dirname( __FILE__ ) . '/views/debugging.php';
		exit;
	}

	/**
	 * Set redirection by id.
	 *
	 * @param integer $redirection Redirection id to set for.
	 */
	private function set_redirection( $redirection ) {
		if ( ! is_array( $redirection ) ) {
			$redirection = DB::get_redirection_by_id( $redirection, 'active' );
		}

		if ( false !== $redirection && isset( $redirection['url_to'] ) ) {
			$this->matched = $redirection;
			$this->set_redirect_to();
		}
	}

	/**
	 * Set redirect to.
	 */
	private function set_redirect_to() {
		$this->redirect_to = $this->matched['url_to'];
		foreach ( $this->matched['sources'] as $source ) {
			$this->set_redirect_to_regex( $source );
		}
	}

	/**
	 * Set redirect to by replacing using regex.
	 *
	 * @param array $source Source to check.
	 */
	private function set_redirect_to_regex( $source ) {
		if ( 'regex' !== $source['comparison'] ) {
			return;
		}

		$pattern = DB::get_clean_pattern( $source['pattern'], $source['comparison'] );
		if ( Str::comparison( $pattern, $this->uri, $source['comparison'] ) ) {
			$this->redirect_to = preg_replace( $pattern, $this->redirect_to, $this->uri );
		}
	}

	/**
	 * Get the object type for the current page.
	 *
	 * @codeCoverageIgnore
	 *
	 * @return string object type name.
	 */
	private function current_object_type() {
		$hash   = [
			'WP_Post' => 'post',
			'WP_Term' => 'term',
			'WP_User' => 'user',
		];
		$object = get_queried_object();
		$object = get_class( $object );

		return isset( $hash[ $object ] ) ? $hash[ $object ] : 'any';
	}

	/**
	 * Sets the wp_query to 404 when this is an object.
	 */
	private function set_404() {
		global $wp_query;

		$wp_query         = is_object( $wp_query ) ? $wp_query : new WP_Query;
		$wp_query->is_404 = true;
	}

	/**
	 * Get header code.
	 *    1. From matched redirection.
	 *    2. From optgeneral options.
	 *
	 * @return int
	 */
	private function get_header_code() {
		$header_code = isset( $this->matched['header_code'] ) ? $this->matched['header_code'] : Helper::get_settings( 'general.redirections_header_code' );

		return absint( $header_code );
	}
}
