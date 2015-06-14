<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package myoos
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function myoos_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'myoos_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function myoos_jetpack_setup
add_action( 'after_setup_theme', 'myoos_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function myoos_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function myoos_infinite_scroll_render
