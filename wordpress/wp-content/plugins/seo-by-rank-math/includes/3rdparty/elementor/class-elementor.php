<?php
/**
 * Elementor integration.
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\Core
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Elementor;

use RankMath\Traits\Hooker;
use RankMath\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Elementor class.
 */
class Elementor {

	use Hooker;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		if ( ! $this->can_add_seo_tab() ) {
			return;
		}

		$this->action( 'elementor/editor/before_enqueue_scripts', 'enqueue' );
		add_action( 'elementor/editor/footer', [ rank_math()->json, 'output' ], 0 );
		$this->action( 'elementor/editor/footer', 'start_capturing', 0 );
		$this->action( 'elementor/editor/footer', 'end_capturing', 999 );
	}

	/**
	 * Start capturing buffer.
	 */
	public function start_capturing() {
		ob_start();
	}

	/**
	 * End capturing buffer and add button.
	 */
	public function end_capturing() {
		$output  = \ob_get_clean();
		$search  = '/(<div class="elementor-component-tab elementor-panel-navigation-tab" data-tab="global">.*<\/div>)/m';
		$replace = '${1}<div class="elementor-component-tab elementor-panel-navigation-tab" data-tab="rankMath">SEO</div>';
		echo \preg_replace(
			$search,
			$replace,
			$output
		);
	}

	/**
	 * Enqueue scripts.
	 */
	public function enqueue() {
		$deps = [
			'tagify',
			'wp-core-data',
			'wp-components',
			'wp-block-editor',
			'wp-element',
			'wp-data',
			'wp-api-fetch',
			'rank-math-analyzer',
			'backbone-marionette',
			'elementor-common-modules',
		];

		wp_enqueue_style( 'wp-components' );
		wp_enqueue_style( 'rank-math-post-metabox', rank_math()->plugin_url() . 'assets/admin/css/elementor.css', [], rank_math()->version );
		wp_enqueue_script( 'rank-math-elementor', rank_math()->plugin_url() . 'assets/admin/js/elementor.js', $deps, rank_math()->version, true );
		rank_math()->variables->setup();
		rank_math()->variables->setup_json();
	}

	/**
	 * Can add SEO tab in Elementor Page Builder.
	 *
	 * @return bool
	 */
	private function can_add_seo_tab() {
		$post_type = isset( $_GET['post'] ) ? get_post_type( $_GET['post'] ) : '';
		if ( $post_type && ! Helper::get_settings( 'titles.pt_' . $post_type . '_add_meta_box' ) ) {
			return false;
		}

		return true;
	}
}
