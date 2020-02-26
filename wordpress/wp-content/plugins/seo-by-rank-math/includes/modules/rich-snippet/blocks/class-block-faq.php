<?php
/**
 * The FAQ Block
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
 * Block_FAQ class.
 */
class Block_FAQ {

	/**
	 * The Constructor.
	 */
	public function __construct() {
		wp_register_style( 'rank-math-block-admin', rank_math()->plugin_url() . 'assets/admin/css/blocks.css', null, rank_math()->version );

		register_block_type(
			'rank-math/faq-block',
			[
				'render_callback' => [ $this, 'render' ],
				'editor_style'    => 'rank-math-block-admin',
				'attributes'      => [
					'listStyle'         => [
						'type'    => 'string',
						'default' => '',
					],
					'titleWrapper'      => [
						'type'    => 'string',
						'default' => 'h3',
					],
					'sizeSlug'          => [
						'type'    => 'string',
						'default' => 'thumbnail',
					],
					'questions'         => [
						'type'    => 'array',
						'default' => [],
						'items'   => [ 'type' => 'object' ],
					],
					'listCssClasses'    => [
						'type'    => 'string',
						'default' => '',
					],
					'titleCssClasses'   => [
						'type'    => 'string',
						'default' => '',
					],
					'contentCssClasses' => [
						'type'    => 'string',
						'default' => '',
					],
					'textAlign'         => [
						'type'    => 'string',
						'default' => 'left',
					],
				],
			]
		);

		add_filter( 'rank_math/schema/block/faq-block', [ $this, 'add_graph' ], 10, 2 );
	}

	/**
	 * FAQ rich snippet.
	 *
	 * @param array $data  Array of JSON-LD data.
	 * @param array $block JsonLD Instance.
	 *
	 * @return array
	 */
	public function add_graph( $data, $block ) {
		// Early bail!!!
		if ( ! $this->has_questions( $block['attrs'] ) ) {
			return $data;
		}

		if ( ! isset( $data['faqs'] ) ) {
			$data['faqs'] = [
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => [],
			];
		}

		foreach ( $block['attrs']['questions'] as $question ) {
			if ( empty( $question['title'] ) || empty( $question['content'] ) || empty( $question['visible'] ) ) {
				continue;
			}

			$data['faqs']['mainEntity'][] = [
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $question['title'] ),
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text'  => strip_tags( $question['content'], '<h1><h2><h3><h4><h5><h6><br><ol><ul><li><a><p><b><strong><i><em>' ),
				],
			];
		}

		return $data;
	}

	/**
	 * Render block content
	 *
	 * @param array $attributes Array of atributes.
	 *
	 * @return string
	 */
	public function render( $attributes ) {
		// Early bail!!!
		if ( ! $this->has_questions( $attributes ) ) {
			return '';
		}

		$list_tag = $this->get_list_style( $attributes['listStyle'] );
		$item_tag = $this->get_list_item_style( $attributes['listStyle'] );

		// HTML.
		$out   = [];
		$out[] = sprintf( '<div id="rank-math-faq" class="rank-math-block"%s>', $this->get_styles( $attributes ) );
		$out[] = sprintf( '<%1$s class="rank-math-list %2$s">', $list_tag, $attributes['listCssClasses'] );

		// Questions.
		foreach ( $attributes['questions'] as $question ) {
			if ( empty( $question['title'] ) || empty( $question['content'] ) || empty( $question['visible'] ) ) {
				continue;
			}

			$out[] = sprintf( '<%1$s class="rank-math-list-item">', $item_tag );

			$out[] = sprintf(
				'<%1$s class="rank-math-question %2$s">%3$s</%1$s>',
				$attributes['titleWrapper'],
				$attributes['titleCssClasses'],
				$question['title']
			);

			$out[] = sprintf(
				'<div class="rank-math-answer %2$s">%4$s%3$s</div>',
				$attributes['titleWrapper'],
				$attributes['contentCssClasses'],
				wpautop( $question['content'] ),
				$this->get_image( $question, $attributes['sizeSlug'] )
			);

			$out[] = sprintf( '</%1$s>', $item_tag );
		}

		$out[] = sprintf( '</%1$s>', $list_tag );
		$out[] = '</div>';

		return join( "\n", $out );
	}

	/**
	 * [get_image description]
	 *
	 * @param array  $question [description].
	 * @param string $size     [description].
	 *
	 * @return [type]           [description]
	 */
	private function get_image( $question, $size = 'thumbnail' ) {
		if ( ! isset( $question['imageID'] ) ) {
			return '';
		}

		$image_id = absint( $question['imageID'] );
		if ( ! ( $image_id > 0 ) ) {
			return '';
		}

		$html = wp_get_attachment_image( $image_id, $size, false, 'class=alignright' );

		return $html ? $html : wp_get_attachment_image( $image_id, 'full', false, 'class=alignright' );
	}

	/**
	 * Get styles
	 *
	 * @param array $attributes Array of attributes.
	 *
	 * @return string
	 */
	private function get_styles( $attributes ) {
		$out = [];

		if ( ! empty( $attributes['textAlign'] ) && 'left' !== $attributes['textAlign'] ) {
			$out[] = 'text-align:' . $attributes['textAlign'];
		}

		return empty( $out ) ? '' : ' style="' . join( ';', $out ) . '"';
	}

	/**
	 * Get list style
	 *
	 * @param string $style Style.
	 *
	 * @return string
	 */
	private function get_list_style( $style ) {
		if ( 'numbered' === $style ) {
			return 'ol';
		}

		if ( 'unordered' === $style ) {
			return 'ul';
		}

		return 'div';
	}

	/**
	 * Get list item style
	 *
	 * @param string $style Style.
	 *
	 * @return string
	 */
	private function get_list_item_style( $style ) {
		if ( 'numbered' === $style || 'unordered' === $style ) {
			return 'li';
		}

		return 'div';
	}

	/**
	 * Has questions.
	 *
	 * @param array $attributes Array of attributes.
	 *
	 * @return boolean
	 */
	private function has_questions( $attributes ) {
		return ! isset( $attributes['questions'] ) || empty( $attributes['questions'] ) ? false : true;
	}
}
