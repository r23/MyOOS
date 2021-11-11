<?php
/**
 * Server-side rendering of the `core/comment-content` block.
 *
 * @package WordPress
 */

/**
 * Renders the `core/comment-content` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 * @return string Return the post comment's content.
 */
function gutenberg_render_block_core_comment_content( $attributes, $content, $block ) {
	if ( ! isset( $block->context['commentId'] ) ) {
		return '';
	}

	$comment = get_comment( $block->context['commentId'] );
	if ( empty( $comment ) ) {
		return '';
	}

	$comment_text = get_comment_text( $comment );
	if ( ! $comment_text ) {
		return '';
	}

	$classes = '';
	if ( isset( $attributes['textAlign'] ) ) {
		$classes .= 'has-text-align-' . $attributes['textAlign'];
	}

	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $classes ) );

	return sprintf(
		'<div %1$s>%2$s</div>',
		$wrapper_attributes,
		$comment_text
	);
}

/**
 * Registers the `core/comment-content` block on the server.
 */
function gutenberg_register_block_core_comment_content() {
	register_block_type_from_metadata(
		__DIR__ . '/comment-content',
		array(
			'render_callback' => 'gutenberg_render_block_core_comment_content',
		)
	);
}
add_action( 'init', 'gutenberg_register_block_core_comment_content', 20 );
