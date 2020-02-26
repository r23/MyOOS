<?php
/**
 * The Yoast Block Converter.
 *
 * @since      1.0.37
 * @package    RankMath
 * @subpackage RankMath\Status
 * @author     Rank Math <support@rankmath.com>
 */

namespace RankMath\Status;

use RankMath\Helper;
use RankMath\Traits\Hooker;

/**
 * Yoast_Blocks class.
 */
class Yoast_Blocks {

	/**
	 * FAQ Converter.
	 *
	 * @var Yoast_FAQ_Converter
	 */
	private $faq_converter;

	/**
	 * Run all
	 */
	public function run() {
		$posts = $this->find_posts();
		if ( empty( $posts ) ) {
			return esc_html__( 'No posts found to convert.', 'rank-math' );
		}

		$count               = 0;
		$this->faq_converter = new Yoast_FAQ_Converter;
		foreach ( $posts as $post ) {
			$dirty  = false;
			$blocks = $this->parse_blocks( $post->post_content );

			if ( isset( $blocks['yoast/faq-block'] ) && ! empty( $blocks['yoast/faq-block'] ) ) {
				$dirty   = true;
				$content = $this->faq_converter->replace( $post->post_content, $blocks['yoast/faq-block'] );
			}

			if ( $dirty ) {
				$count++;
				$post->post_content = $content;
				wp_update_post( $post );
			}
		}

		return $count . ' post converted';
	}

	/**
	 * Find posts with yoast blocks.
	 *
	 * @return array
	 */
	private function find_posts() {
		$posts = get_posts( 's=wp:yoast/faq-block&post_status=any' );

		return $posts;
	}

	/**
	 * Parse blocks to get data
	 *
	 * @param string $content Post content to parse.
	 *
	 * @return array
	 */
	private function parse_blocks( $content ) {
		$parsed_blocks = parse_blocks( $content );

		$blocks = [];
		foreach ( $parsed_blocks as $block ) {
			if ( empty( $block['blockName'] ) ) {
				continue;
			}

			$name = strtolower( $block['blockName'] );
			if ( ! isset( $blocks[ $name ] ) || ! is_array( $blocks[ $name ] ) ) {
				$blocks[ $name ] = [];
			}

			if ( 'yoast/faq-block' === $name ) {
				$block             = $this->faq_converter->convert( $block );
				$blocks[ $name ][] = $this->serialize_block( $block );
			}
		}

		return $blocks;
	}

	/**
	 * Serializes a block.
	 *
	 * @param array $block Block object.
	 *
	 * @return string String representing the block.
	 */
	private function serialize_block( $block ) {
		if ( ! isset( $block['blockName'] ) ) {
			return false;
		}

		$name = $block['blockName'];

		$opening_tag_suffix = '';
		if ( ! empty( $block['attrs'] ) ) {
			$opening_tag_suffix = ' ' . json_encode( array_filter( $block['attrs'] ) );
		}

		if ( ! isset( $block['innerHTML'] ) ) {
			$block['innerHTML'] = '';
		}

		return sprintf(
			'<!-- wp:%1$s%2$s -->%3$s<!-- /wp:%1$s -->',
			$name,
			$opening_tag_suffix,
			$block['innerHTML']
		);
	}
}
