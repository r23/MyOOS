<?php
/**
 * Adjust editor
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cpschool_add_editor_styles' ) ) {
	add_action( 'enqueue_block_editor_assets', 'cpschool_add_editor_styles' );

	/**
	 * Registers blocks stylesheet for the theme.
	 */
	function cpschool_add_editor_styles() {
		$css_version = filemtime( get_template_directory() . '/css/block-editor.min.css' );
		wp_enqueue_style( 'cpschool-gutenberg', get_theme_file_uri( 'css/block-editor.min.css' ), false, $css_version );
	}
}

if ( ! function_exists( 'cpschool_block_editor_settings' ) ) {
	add_filter( 'block_editor_settings', 'cpschool_block_editor_settings', 10, 2 );

	function cpschool_block_editor_settings( $editor_settings, $post ) {
		$editor_settings['styles'][] = array( 'css' => 'body { font-family: "Inter var"; }' );

		return $editor_settings;
	}
}
