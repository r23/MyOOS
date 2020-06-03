<?php
/**
 * Custom hooks.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cpschool_add_site_info' ) ) {
	add_action( 'cpschool_site_info', 'cpschool_add_site_info' );

	/**
	 * Add site info content.
	 */
	function cpschool_add_site_info() {
		$site_info = get_theme_mod( 'footer_main_custom_html' );

		if ( ! $site_info ) {
			$site_info = sprintf(
				'<a href="%1$s">%2$s</a><span class="sep"> | </span>%3$s',
				esc_url( __( 'http://wordpress.org/', 'cpschool' ) ),
				sprintf(
					/* translators:*/
					esc_html__( 'Powered by %s', 'cpschool' ),
					'WordPress'
				),
				sprintf( // WPCS: XSS ok.
					/* translators:*/
					esc_html__( 'Theme created by %1$s.', 'cpschool' ),
					'<a href="' . esc_url( __( 'https://campuspress.com/', 'cpschool' ) ) . '">CampusPress</a>'
				)
			);
		}

		echo apply_filters( 'cpschool_site_info_content', $site_info ); // WPCS: XSS ok.
	}
}

if ( ! function_exists( 'cpschool_get_menu_icons_colors' ) ) {
	add_action( 'wpmi_predefined_colors', 'cpschool_get_menu_icons_colors', 10, 0 );

	/**
	 * Sets colors that can be selected for menu icons and its background.
	 *
	 * @return array
	 */
	function cpschool_get_menu_icons_colors() {
		$default = get_theme_mod( 'header_main_bg_color_contrast' );
		if ( ! $default ) {
			$default = get_theme_mod( 'color_bg_alt_contrast' );
		}

		$accent = get_theme_mod( 'header_main_bg_color_accent' );
		if ( ! $accent ) {
			$accent = get_theme_mod( 'color_bg_alt_accent' );
		}

		$accent_hl = get_theme_mod( 'header_main_bg_color_accent_hl' );
		if ( ! $accent_hl ) {
			$accent_hl = get_theme_mod( 'color_bg_alt_accent_hl' );
		}

		$colors = array(
			''          => array(
				'hex'  => $default,
				'name' => esc_html__( 'Default', 'cpschool' ),
			),
			'accent'    => array(
				'hex'  => $accent,
				'name' => esc_html__( 'Accent', 'cpschool' ),
			),
			'accent-hl' => array(
				'hex'  => $accent_hl,
				'name' => esc_html__( 'Highlight Accent', 'cpschool' ),
			),
		);

		return $colors;
	}
}
