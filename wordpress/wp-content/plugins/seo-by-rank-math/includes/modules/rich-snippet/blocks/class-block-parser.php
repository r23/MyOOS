<?php
/**
 * The Block Parser
 *
 * @since      0.9.0
 * @package    RankMath
 * @subpackage RankMath\RichSnippet
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\RichSnippet;

use RankMath\Helper;
use MyThemeShop\Helpers\Str;
use RankMath\Traits\Hooker;

defined( 'ABSPATH' ) || exit;

/**
 * Block_Parser class.
 */
class Block_Parser {

	use Hooker;

	/**
	 * Holds the parsed blocks.
	 *
	 * @var array
	 */
	private $blocks = [];

	/**
	 * The Constructor.
	 */
	public function __construct() {
		$this->action( 'rank_math/json_ld', 'parse' );
	}

	/**
	 * Parse the blocks.
	 *
	 * @param array $data Array of json-ld data.
	 *
	 * @return array
	 */
	public function parse( $data ) {
		if ( ! function_exists( 'parse_blocks' ) || ! is_singular() ) {
			return $data;
		}

		$this->get_parsed_blocks();

		foreach ( $this->blocks as $block_type => $blocks ) {
			foreach ( $blocks as $block ) {
				/**
				 * Filter: 'rank_math/schema/block/<block-type>' - Allows filtering graph output per block.
				 *
				 * @param array $data  Array of json-ld data.
				 * @param array $block The block.
				 */
				$data = $this->do_filter( 'schema/block/' . $block_type, $data, $block );
			}
		}

		return $data;
	}

	/**
	 * Parse the blocks and loop through them.
	 */
	private function get_parsed_blocks() {
		$post          = get_post();
		$parsed_blocks = parse_blocks( $post->post_content );

		foreach ( $parsed_blocks as $block ) {
			if ( ! $this->is_valid_block( $block ) ) {
				continue;
			}

			$name = \str_replace( 'rank-math/', '', $block['blockName'] );
			$name = strtolower( $name );
			if ( ! isset( $this->blocks[ $name ] ) || ! is_array( $this->blocks[ $name ] ) ) {
				$this->blocks[ $name ] = [];
			}

			$this->blocks[ $name ][] = $block;
		}
	}

	/**
	 * Is block valid.
	 *
	 * @param array $block Block.
	 *
	 * @return boolean
	 */
	private function is_valid_block( $block ) {
		return ! empty( $block['blockName'] ) && Str::starts_with( 'rank-math', $block['blockName'] );
	}
}
