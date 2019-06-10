<?php
/**
 * The Redirections Module
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Redirections
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Redirections;

use RankMath\Helper;
use RankMath\Traits\Hooker;
use MyThemeShop\Helpers\Conditional;

/**
 * Redirections class.
 *
 * @codeCoverageIgnore
 */
class Redirections {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->load_admin();

		if ( ! is_admin() ) {
			$this->action( 'wp', 'do_redirection' );
		}

		if ( Helper::has_cap( 'redirections' ) ) {
			$this->filter( 'rank_math/admin_bar/items', 'admin_bar_items', 11 );
		}

		if ( $this->disable_auto_redirect() ) {
			remove_action( 'template_redirect', 'wp_old_slug_redirect' );
		}
	}

	/**
	 * Load redirection admin and rest api.
	 */
	private function load_admin() {
		if ( is_admin() ) {
			$this->admin = new Admin;
		}

		if ( is_admin() || Conditional::is_rest() ) {
			new Watcher;
		}
	}

	/**
	 * Do redirection on frontend.
	 */
	public function do_redirection() {
		if ( is_customize_preview() || Conditional::is_ajax() || ! isset( $_SERVER['REQUEST_URI'] ) || empty( $_SERVER['REQUEST_URI'] ) || $this->is_script_uri_or_http_x() ) {
			return;
		}

		$redirector = new Redirector;
	}

	/**
	 * Add admin bar item.
	 *
	 * @param array $items Array of admin bar nodes.
	 * @return array
	 */
	public function admin_bar_items( $items ) {

		$items['redirections'] = [
			'id'        => 'rank-math-redirections',
			'title'     => esc_html__( 'Redirections', 'rank-math' ),
			'href'      => Helper::get_admin_url( 'redirections' ),
			'parent'    => 'rank-math',
			'meta'      => [ 'title' => esc_html__( 'Create and edit redirections', 'rank-math' ) ],
			'_priority' => 50,
		];

		$items['redirections-child'] = [
			'id'        => 'rank-math-redirections-child',
			'title'     => esc_html__( 'Manage Redirections', 'rank-math' ),
			'href'      => Helper::get_admin_url( 'redirections' ),
			'parent'    => 'rank-math-redirections',
			'meta'      => [ 'title' => esc_html__( 'Create and edit redirections', 'rank-math' ) ],
			'_priority' => 51,
		];

		$items['redirections-settings'] = [
			'id'        => 'rank-math-redirections-settings',
			'title'     => esc_html__( 'Redirection Settings', 'rank-math' ),
			'href'      => Helper::get_admin_url( 'options-general' ) . '#setting-panel-redirections',
			'parent'    => 'rank-math-redirections',
			'meta'      => [ 'title' => esc_html__( 'Redirection Settings', 'rank-math' ) ],
			'_priority' => 52,
		];

		if ( ! is_admin() ) {
			$items['redirections-redirect-me'] = [
				'id'        => 'rank-math-redirections-redirect-me',
				'title'     => esc_html__( '&raquo; Redirect this page', 'rank-math' ),
				'href'      => add_query_arg( 'url', urlencode( ltrim( $_SERVER['REQUEST_URI'], '/' ) ), Helper::get_admin_url( 'redirections' ) ),
				'parent'    => 'rank-math-redirections',
				'meta'      => [ 'title' => esc_html__( 'Redirect the current URL', 'rank-math' ) ],
				'_priority' => 53,
			];
		}

		return $items;
	}

	/**
	 * Is script uri or http-x request
	 *
	 * @return boolean
	 */
	private function is_script_uri_or_http_x() {
		if ( isset( $_SERVER['SCRIPT_URI'] ) && ! empty( $_SERVER['SCRIPT_URI'] ) && admin_url( 'admin-ajax.php' ) === $_SERVER['SCRIPT_URI'] ) {
			return true;
		}

		if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' ) {
			return true;
		}

		return false;
	}

	/**
	 * Disable Auto-Redirect.
	 *
	 * @return bool
	 */
	private function disable_auto_redirect() {
		return get_option( 'permalink_structure' ) && Helper::get_settings( 'general.redirections_post_redirect' );
	}
}
