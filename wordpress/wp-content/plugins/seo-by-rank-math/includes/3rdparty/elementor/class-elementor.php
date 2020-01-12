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

		//wp_enqueue_style( 'common' );
		//wp_enqueue_style( 'forms' );
		wp_enqueue_style( 'wp-components' );
		wp_enqueue_style( 'rank-math-post-metabox', rank_math()->plugin_url() . 'assets/admin/css/elementor.css', [], rank_math()->version );
		wp_enqueue_script( 'rank-math-elementor', rank_math()->plugin_url() . 'assets/admin/js/elementor.js', $deps, rank_math()->version, true );
		rank_math()->variables->setup();
		rank_math()->variables->setup_json();
	}
}
