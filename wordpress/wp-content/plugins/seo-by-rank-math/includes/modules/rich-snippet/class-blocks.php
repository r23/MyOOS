<?php
/**
 * The Rich Snippet Blocks
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\RichSnippet;

use RankMath\Helper;
use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Blocks class.
 */
class Blocks {

	use Hooker;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->action( 'enqueue_block_assets', 'block_assets' ); // Frontend.
		$this->action( 'enqueue_block_editor_assets', 'editor_assets' ); // Backend.
		$this->filter( 'block_categories', 'block_categories' );
		$this->action( 'init', 'init' );
	}

	/**
	 * Init blocks.
	 */
	public function init() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		new Block_FAQ;
	}

	/**
	 * Add rank math category in gutenberg.
	 *
	 * @param array $categories Array of block categories.
	 *
	 * @return array
	 */
	public function block_categories( $categories ) {
		return array_merge(
			$categories,
			[
				[
					'slug'  => 'rank-math-blocks',
					'title' => __( 'Rank Math', 'rank-math' ),
					'icon'  => 'wordpress',
				],
			]
		);
	}

	/**
	 * Enqueue Styles and Scripts required for blocks at frontend.
	 */
	public function block_assets() {
	}

	/**
	 * Enqueue Styles and Scripts required for blocks at backend.
	 */
	public function editor_assets() {
		if ( ! $this->is_block_faq() && ! $this->is_block_howto() ) {
			return;
		}

		Helper::add_json(
			'blocks',
			[
				'faq'   => $this->is_block_faq(),
				'howTo' => $this->is_block_howto(),
			]
		);

		wp_enqueue_script(
			'rank-math-block-faq',
			rank_math()->plugin_url() . 'assets/admin/js/blocks.js',
			[],
			rank_math()->version,
			true
		);
	}

	/**
	 * Is FAQ Block enabled.
	 *
	 * @return boolean
	 */
	private function is_block_faq() {
		return true;
	}

	/**
	 * Is HowTo Block enabled.
	 *
	 * @return boolean
	 */
	private function is_block_howto() {
		return true;
	}
}
