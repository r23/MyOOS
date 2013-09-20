<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package myoos
 */
if ( !defined( 'MYOOS_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function myoos_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'myoos_jetpack_setup' );
