<?php
/**
 * Enqueue scripts and styles.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cpschool_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'cpschool_scripts' );

	/**
	 * Load theme's JavaScript and CSS sources.
	 */
	function cpschool_scripts() {
		// Get the theme data.
		$the_theme     = wp_get_theme();
		$theme_version = $the_theme->get( 'Version' );

		$css_version = $theme_version . '.' . filemtime( get_template_directory() . '/css/theme.min.css' );
		wp_enqueue_style( 'cpschool-styles', get_template_directory_uri() . '/css/theme.min.css', array(), $css_version );

		wp_enqueue_script( 'jquery' );

		if ( get_theme_mod( 'animations' ) ) {
			$css_version = $theme_version . '.' . filemtime( get_template_directory() . '/css/aos.css' );
			wp_enqueue_style( 'aos', get_template_directory_uri() . '/css/aos.css', array(), $css_version );

			$js_version = $theme_version . '.' . filemtime( get_template_directory() . '/js/aos.js' );
			wp_enqueue_script( 'aos', get_template_directory_uri() . '/js/aos.js', array(), $js_version, true );
		}

		$js_version = $theme_version . '.' . filemtime( get_template_directory() . '/js/theme.min.js' );
		wp_enqueue_script( 'cpschool-scripts', get_template_directory_uri() . '/js/theme.min.js', array(), $js_version, true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		wp_localize_script(
			'cpschool-scripts',
			'cpSchoolData',
			array(
				'parallaxHeader' => get_theme_mod( 'hero_main_parallax' ) ? true : false,
				'animations'     => get_theme_mod( 'animations' ) ? true : false,
			)
		);
	}
} // endif function_exists( 'cpschool_scripts' ).

if ( ! function_exists( 'cpschool_print_scripts' ) ) {
	add_action( 'wp_print_scripts', 'cpschool_print_scripts', 100 );

	/**
	 * Print scripts in header
	 */
	function cpschool_print_scripts() {
		echo '<script>window.MSInputMethodContext && document.documentMode && document.write(\'<script src="' . get_template_directory_uri() . '/js/css-vars-ponyfill.min.js' . '"><\x2fscript>\');</script>' . "\n";
	}
} // endif function_exists( 'cpschool_print_scripts' ).
