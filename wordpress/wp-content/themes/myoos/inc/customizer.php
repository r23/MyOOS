<?php
/**
 * Theme Customizer settings added with Kirki Framework.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


// Disable Kiri stats collection.
add_filter( 'kirki_telemetry', '__return_false' );

// Loads some helpful functions.
require_once 'customizer/helpers.php';

// Load additional controls.
require_once 'customizer/custom-controls.php';

// Configure Kiri framework.
Kirki::add_config( 'cpschool', array(
	'capability'  => 'edit_theme_options',
	'option_type' => 'theme_mod',
	'gutenberg_support' => true
) );

if ( ! function_exists( 'cpschool_kirki_config' ) ) {
	add_filter( 'kirki_config', 'cpschool_kirki_config' );

	/**
	 * Modifies kirki framework config to disable loader with branding
	 */
	function cpschool_kirki_config($config) {
		$config['disable_loader'] = true;
		
		return $config;
	}
}

if ( ! function_exists( 'cpschool_customize_preview_init' ) ) {
	add_action( 'customize_preview_init', 'cpschool_customize_preview_init' );

	/**
	 * Enqueue scripts for the customizer preview.
	 *
	 * @return void
	 */
	function cpschool_customize_preview_init() {
		$js_version = filemtime( get_template_directory() . '/js/customize-helper-colors.js' );
		wp_enqueue_script( 'cpschool-customize-preview', get_theme_file_uri( '/js/customize-preview.js' ), array( 'customize-preview', 'jquery' ), $js_version, true );
	}
}

if ( ! function_exists( 'cpschool_customize_controls_enqueue_scripts' ) ) {
	add_action( 'customize_controls_enqueue_scripts', 'cpschool_customize_controls_enqueue_scripts' );

	/**
	 * Enqueues scripts for customizer controls & settings.
	 *
	 * @return void
	 */
	function cpschool_customize_controls_enqueue_scripts() {
		// Add script for color calculations.
		$js_version = filemtime( get_template_directory() . '/js/customize-helper-colors.js' );
		wp_enqueue_script( 'cpschool-customize-helper-colors', get_template_directory_uri() . '/js/customize-helper-colors.js', array( 'wp-color-picker' ), $js_version, false );

		// Add script for controls.
		$js_version = filemtime( get_template_directory() . '/js/customize-controls.js' );
		wp_enqueue_script( 'twentytwenty-customize-controls', get_template_directory_uri() . '/js/customize-controls.js', array( 'cpschool-customize-helper-colors', 'customize-controls', 'underscore', 'jquery' ), $js_version, false );
	}
}

if ( ! function_exists( 'cpschool_theme_customizer_defaults_tweaks' ) ) {
	add_action( 'customize_register', 'cpschool_theme_customizer_defaults_tweaks' );

	/**
	 * Move default WordPress controls to custom theme panels.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer reference.
	 */
	function cpschool_theme_customizer_defaults_tweaks( $wp_customize ) {
		// Move "Site Identity" to "General" panel.
		$wp_customize->remove_section( 'title_tagline' );
		$wp_customize->add_section(
			'title_tagline',
			array(
				'title'    => __( 'Site Identity' ),
				'priority' => 10,
				'panel' => 'general'
			)
		);

		// Move "Colors" to "General" panel.
		$wp_customize->remove_section( 'colors' );
		$wp_customize->add_section(
			'colors',
			array(
				'title'    => __( 'Colors' ),
				'priority' => 30,
				'panel' => 'general',
			)
		);

		// Remove background colors as they are handled 100% by theme.
		$wp_customize->remove_setting('background_color');

		$wp_customize->remove_section( 'background_image' );
		$wp_customize->add_section(
			'background_image',
			array(
				'title'          => __( 'Background Image' ),
				'theme_supports' => 'custom-background',
				'priority'       => 40,
				'panel' => 'general',
			)
		);
	}
}

if ( ! function_exists( 'cpschool_theme_customizer' ) ) {
	add_action( 'init', 'cpschool_theme_customizer' );

	/**
	 * Register individual settings through Kiri's API.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer reference.
	 */
	function cpschool_theme_customizer() {
		// PANEL - General
		Kirki::add_panel( 'general', array(
			'priority'    => 10,
			'title'       => esc_html__( 'General', 'cpschool' ),
			'description' => esc_html__( 'Settings related to all elements on site.', 'cpschool' ),
		) );

			// SECTION - Site Identity (WP)

				// SETTING - Site Title Font Family
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'site_title_font_family',
					'label'       => __( 'Site Title Font Family', 'cpschool' ),
					'section'     => 'title_tagline',
					'transport'   => 'auto',
					'choices'     => cpschool_get_customizer_fonts_options('header', true),
					'default' => 'inherit',
					'output' => array(
						array(
							'element' => array( '.logo-font' ),
							'property' => 'font-family',
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'custom_logo',
							'operator' => '==',
							'value'    => false,
						)
					),
				) );

			// SECTION - Site Layout & Stylings
			Kirki::add_section( 'site_layout', array(
				'title'       => esc_html__( 'Site Layout & Stylings', 'cpschool' ),
				'description' => esc_html__( 'Settings related to site layout and general stylings.', 'cpschool' ),
				'panel'       => 'general',
				'priority'    => 20,
			) );

				// SETTING - Site Width
				Kirki::add_field( 'cpschool', array(
					'type'        => 'slider',
					'settings'    => 'site_width',
					'label'       => esc_html__( 'Site Width (px)', 'cpschool' ),
					'description' => esc_html__( 'Maximum site container width.', 'cpschool' ),
					'section'     => 'site_layout',
					'default'     => 1140,
					'choices'     => array(
						'min'  => 720,
						'max'  => 1920,
						'step' => 1,
					),
					'transport'   => 'auto',
					'output' => array(
						array(
							'element'  => '.container',
							'property' => 'max-width',
							'units' => 'px',
						),
						array(
							'element'  => ':root',
							'property' => '--site-width',
							'units' => 'px',
							'context' => array('editor', 'front')
						),
					),
				) );

				// SETTING - Content Width
				Kirki::add_field( 'cpschool', array(
					'type'        => 'slider',
					'settings'    => 'content_width',
					'label'       => esc_html__( 'Content Width (px)', 'cpschool' ),
					'description' => esc_html__( 'Maximum content container width. This only works for single posts and pages without sidebar.', 'cpschool' ),
					'section'     => 'site_layout',
					'default'     => '1140',
					'choices'     => array(
						'min'  => 600,
						'max'  => 1330,
						'step' => 1,
					),
					'transport'   => 'auto',
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--content-width',
							'units' => 'px',
							'context' => array('editor', 'front')
						),
					),
				) );

				// SETTING - Boxed Design
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'boxed_design',
					'label'       => esc_html__( 'Boxed Design', 'cpschool' ),
					'description' => esc_html__( 'Wraps everything in a container and adds the same width for all elements on the site.', 'cpschool' ),
					'section'     => 'site_layout',
					'default'     => false,
					'transport'   => 'postMessage',
					'js_vars'   => array(
						array(
							'element'  => '.site',
							'function' => 'toggleClass',
							'class' => 'container-fluid',
							'context' => 'site-page-wrapper',
							'value' => true
						),
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'site-boxed',
							'context' => 'body',
							'value' => true
						),
					)
				) );

				// SETTING - Box Width
				Kirki::add_field( 'cpschool', array(
					'type'        => 'slider',
					'settings'    => 'box_width',
					'label'       => esc_html__( 'Box Width (px)', 'cpschool' ),
					'description' => esc_html__( 'Maximum box width.', 'cpschool' ),
					'section'     => 'site_layout',
					'default'     => '1280',
					'choices'     => array(
						'min'  => 720,
						'max'  => 1920,
						'step' => 1,
					),
					'transport'   => 'auto',
					'active_callback' => array(
						array(
							'setting'  => 'boxed_design',
							'operator' => '==',
							'value'    => true,
						)
					),
					'output' => array(
						array(
							'element'  => '.site-boxed .site',
							'property' => 'max-width',
							'units' => 'px',
						),
						array(
							'element'  => ':root',
							'property' => '--site-box-width',
							'units' => 'px',
						),
					),
				) );


				// SEPARATOR - Fonts
				Kirki::add_field( 'cpschool', array(
					'settings' => 'layout_fonts',
					'type'        => 'separator',
					'label'       => __( 'Fonts', 'cpschool' ),
					'description'       => __( 'Default font settings applied on site. Some elements like headings can have font customized in their own settings sections.', 'cpschool' ),
					'section'     => 'site_layout',
				) );

				// SETTING - Default Font Family
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'body_font_family',
					'label'       => __( 'Default Font Family', 'cpschool' ),
					'description' => __( 'Choose default font used on site.', 'cpschool' ),
					'section'     => 'site_layout',
					'transport'   => 'postMessage',
					'choices'     => cpschool_get_customizer_fonts_options(),
					'default' => 'public_sans',
					'output' => array(
						array(
							'element' => array( 'body' ),
							'property' => 'font-family',
							'context' => array('editor', 'front')
						),
					),
					'js_vars'   => array(
						// Workaround for Kirki bug. We need to duplicate output functionality in js_var for it to work in customizer.
						array(
							'function' => 'style',
							'element'  => 'body',
							'property' => 'font-family',
						),
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'font-up',
							'value' => array('epilogue', 'gelasio', ''),
							'context' => 'body',
						),
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'font-down',
							'value' => array('mohave', 'russolo', 'space_grotesk'),
							'context' => 'body',
						),
					)
				) );

				// SETTINGS - Default Font Size
				Kirki::add_field( 'cpschool', array(
					'type'        => 'slider',
					'settings'    => 'body_font_size',
					'label'       => esc_html__( 'Default Font Size', 'cpschool' ),
					'description' => esc_attr__( 'Customize default font size.', 'cpschool' ),
					'section'     => 'site_layout',
					'default'     => 100,
					'choices'     => array(
						'min'  => 70,
						'max'  => 130,
						'step' => 2,
					),
					'transport'   => 'auto',
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--body-font-size',
							'context' => array('editor', 'front')
						),
					),
				) );


				// SEPARATOR - Others
				Kirki::add_field( 'cpschool', array(
					'settings' => 'layout_others',
					'type'        => 'separator',
					'label'       => __( 'Others', 'cpschool' ),
					'section'     => 'site_layout',
				) );

				// SETTING - Roundness
				Kirki::add_field( 'cpschool', array(
					'type'        => 'slider',
					'settings'    => 'roundness',
					'label'       => esc_html__( 'Roundness', 'cpschool' ),
					'description' => esc_html__( 'Choose how strongly various elements across the site should be rounded.', 'cpschool' ),
					'section'     => 'site_layout',
					'default'     => 3,
					'choices'     => array(
						'min'  => 0,
						'max'  => 15,
						'step' => 1,
					),
					'transport' => 'auto',
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--roundness',
							'units' => 'px',
							'context' => array('editor', 'front')
						),
					),
				) );

				// SETTING - Shadows
				Kirki::add_field( 'cpschool', array(
					'type'        => 'slider',
					'settings'    => 'shadows',
					'label'       => esc_html__( 'Shadows', 'cpschool' ),
					'description' => esc_html__( 'Choose how big shadows should be applied to various elements across the site.', 'cpschool' ),
					'section'     => 'site_layout',
					'default'     => 4,
					'choices'     => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 2,
					),
					'transport'   => 'postMessage',
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--shadows',
							'units' => 'px',
							'context' => array('editor', 'front')
						),
					),
					'js_vars'   => array(
						// Workaround for Kirki bug. We need to duplicate output functionality in js_var for it to work in customizer.
						array(
							'function' => 'style',
							'element'  => ':root',
							'property' => '--shadows',
							'units' => 'px',
						),
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'shadows-disabled',
							'value' => '0',
							'context' => 'body',
						),
					)
				) );

				// SETTING - Animations
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'animations',
					'label'       => __( 'Animations', 'cpschool' ),
					'description'       => __( 'Automatically adds animations to some main site elements and aligned content blocks.', 'cpschool' ),
					'section'     => 'site_layout',
					'transport'   => 'refresh',
					'default' => true,
					'js_vars'   => array(
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'animations-enabled',
							'value' => true,
							'context' => 'body',
						),
					),
				) );


			// SECTION - Colors (WP)

				// SEPARATOR - Primary Colors
				Kirki::add_field( 'cpschool', array(
					'settings' => 'colors_primary',
					'type'        => 'separator',
					'label'       => __( 'Primary Colors', 'cpschool' ),
					'section'     => 'colors',
				) );

				// SETTING - Primary Background (partially handled by WP)
				Kirki::add_field( 'cpschool', array(
					'type'        => 'color',
					'settings'    => 'color_bg',
					'label'       => __( 'Primary Background', 'cpschool' ),
					'description' => esc_html__( 'Background color used for main content.', 'cpschool' ),
					'section'     => 'colors',
					'default'     => '#ffffff',
					'transport'   => 'auto',
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--color-bg',
							'context' => array('editor', 'front')
						),
					),
				) );

				// SETTING - Secondary Background (used for boxed design)
				Kirki::add_field( 'cpschool', array(
					'type'        => 'color',
					'settings'    => 'color_bg_secondary',
					'label'       => __( 'Secondary Background', 'cpschool' ),
					'description' => esc_html__( 'Background color used under content in box.', 'cpschool' ),
					'section'     => 'colors',
					'default'     => '#eaeaea',
					'transport'   => 'auto',
					'active_callback' => array(
						array(
							'setting'  => 'boxed_design',
							'operator' => '==',
							'value'    => true,
						)
					),
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--color-bg-secondary',
							'context' => array('editor', 'front')
						),
					),
				) );

				// SETTING - Primary Accent
				Kirki::add_field( 'cpschool', array(
					'type'        => 'color',
					'settings'    => 'color_accent_source',
					'label'       => __( 'Primary Accent', 'cpschool' ),
					'description' => esc_html__( 'Color used to style various site elements like buttons.', 'cpschool' ),
					'default' => '#b41111',
					'section'     => 'colors',
					//'mode'        => 'hue',
					'transport'   => 'postMessage',
				) );

				// SETTING - Highlight Accent
				Kirki::add_field( 'cpschool', array(
					'type'        => 'color',
					'settings'    => 'color_accent_hl_source',
					'label'       => __( 'Highlight Accent', 'cpschool' ),
					'description' => esc_html__( 'Color used to highlight important elements like primary buttons.', 'cpschool' ),
					'section'     => 'colors',
					'default' => '#216890',
					'transport'   => 'postMessage',
					'choices'     => array(
						'alpha' => false,
					),
				) );

				// SEPARATOR - Alternative Colors
				Kirki::add_field( 'cpschool', array(
					'settings' => 'colors_alternative',
					'type'        => 'separator',
					'label'       => __( 'Alternative Colors', 'cpschool' ),
					'section'     => 'colors',
				) );
				
				// SETTING - Alternative Background
				Kirki::add_field( 'cpschool', array(
					'type'        => 'color',
					'settings'    => 'color_bg_alt',
					'label'       => __( 'Alternative Background', 'cpschool' ),
					'description' => esc_html__( 'Alternative background color used mainly for header and footer.', 'cpschool' ),
					'section'     => 'colors',
					'default'     => '#eeeeec',
					'transport'   => 'auto',
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--color-bg-alt',
							'context' => array('editor', 'front')
						),
					),
				) );

				// SETTING - Custom Accent For Alternative Background
				Kirki::add_field( 'cpschool', array(
					'type'        => 'color',
					'settings'    => 'color_bg_alt_accent_source',
					'label'       => __( 'Custom Accent For Alternative Background', 'cpschool' ),
					'description' => esc_html__( 'Overwrites primary accent color in areas that are using alternative background.', 'cpschool' ),
					'section'     => 'colors',
					'transport'   => 'postMessage',
					'choices'     => array(
						'alpha' => false,
					),
				) );

					cpschool_generate_customizer_color_settings('color-bg');
					cpschool_generate_customizer_color_settings('color-bg-alt');

				// SEPARATOR - Other Colors
				Kirki::add_field( 'cpschool', array(
					'settings' => 'colors_boxes',
					'type'        => 'separator',
					'label'       => __( 'Other Colors', 'cpschool' ),
					'section'     => 'colors',
				) );

				// SETTING - Content Boxes Background
				Kirki::add_field( 'cpschool', array(
					'type'        => 'color',
					'settings'    => 'color_boxes',
					'label'       => __( 'Content Boxes Background', 'cpschool' ),
					'description' => esc_html__( 'Background color for elements in boxes like pagination or sidebar.', 'cpschool' ),
					'section'     => 'colors',
					'transport'   => 'auto',
					'default'     => '',
					'choices'     => array(
						'alpha' => false,
					),
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--color-boxes',
							'context' => array('editor', 'front')
						),
					),
				) );

					cpschool_generate_customizer_color_settings('color-boxes');
				

		// PANEL - Main Header
		Kirki::add_section( 'header_main', array(
			'priority'    => 20,
			'title'       => esc_html__( 'Main Header', 'cpschool' ),
			'description' => esc_html__( 'Settings for main header, place that holds logo and main navigation links.', 'cpschool' ),
		) );

			// Hidden - Higlighted text color for main color
			Kirki::add_field( 'cpschool', array(
				'type'        => 'hidden',
				'settings'    => 'header_main_height',
				'section'     => 'header_main',
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-main-height',
						'units' => 'px',
					),
				),
			) );

			// SETTING - Logo Positon
			Kirki::add_field( 'cpschool', array(
				'type'        => 'select',
				'settings'    => 'header_main_logo_position',
				'label'       => esc_attr__( 'Logo Positon', 'cpschool' ),
				'description' => esc_html__( 'Choose how logo should be positioned and styled in header.', 'cpschool' ),
				'section'     => 'header_main',
				'transport'   => 'postMessage',
				'choices'     => array(
					'left'   => esc_html__( 'On The Left', 'cpschool' ),
					'center' => esc_html__( 'In The Center With Menu Underneath', 'cpschool' ),
					'dropbox'  => esc_html__( 'On The Left In Drop Box', 'cpschool' ),
				),
				'js_vars'   => array(
					array(
						'element'  => '#wrapper-navbar-main-top',
						'function' => 'toggleClass',
						'class' => 'disable-delay',
						'value' => 'center',
						'context' => 'navbar-main-wrapper-top',
					),
					array(
						'element'  => '#wrapper-navbar-main',
						'function' => 'toggleClass',
						'class' => 'navbar-style-center',
						'value' => 'center',
						'context' => 'navbar-main-wrapper',
					),
					array(
						'element'  => '#wrapper-navbar-main',
						'function' => 'toggleClass',
						'class' => 'navbar-style-dropbox',
						'value' => 'dropbox',
						'context' => 'navbar-main-wrapper',
					),
				)
			) );

			// SETTING - Custom Logo Image Size
			// TODO overwrite default with actual logo image width on the fly
			Kirki::add_field( 'cpschool', array(
				'type'        => 'slider',
				'settings'    => 'header_main_logo_image_width',
				'label'       => esc_html__( 'Custom Logo Image Size (px)', 'cpschool' ),
				'description' => esc_html__( 'Width of the logo image.', 'cpschool' ),
				'section'     => 'header_main',
				'default'     => '250',
				'choices'     => array(
					'min'  => 100,
					'max'  => 400,
					'step' => 2,
				),
				'transport'   => 'auto',
				'active_callback' => array(
					array(
						'setting'  => 'custom_logo',
						'operator' => '!=',
						'value'    => false,
					)
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-main-logo-image-width',
						'units' => 'px',
					),
				),
			) );

			// SETTING - Drop Box Max Width
			Kirki::add_field( 'cpschool', array(
				'type'        => 'slider',
				'settings'    => 'header_main_dropbox_max_width',
				'label'       => esc_html__( 'Drop Box Max Width (px)', 'cpschool' ),
				'description' => esc_html__( 'Maximum width Drop Box can have.', 'cpschool' ),
				'section'     => 'header_main',
				'default'     => 250,
				'choices'     => array(
					'min'  => 100,
					'max'  => 400,
					'step' => 2,
				),
				'transport'   => 'auto',
				'active_callback' => array(
					array(
						'setting'  => 'header_main_logo_position',
						'operator' => '==',
						'value'    => 'dropbox',
					),
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-main-dropbox-max-width',
						'units' => 'px',
					),
				),
			) );

			// Hidden - Header Main Gap Height
			Kirki::add_field( 'cpschool', array(
				'type'        => 'hidden',
				'settings'    => 'header_main_gap_height',
				'section'     => 'header_main',
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-main-gap-height',
						'units' => 'px',
					),
				),
			) );

			// SETTING - Show Tagline
			Kirki::add_field( 'cpschool', array(
				'type'        => 'toggle',
				'settings'    => 'header_main_show_tagline',
				'label'       => __( 'Show Tagline', 'cpschool' ),
				'section'     => 'header_main',
				'transport'   => 'postMessage',
				'default' => true,
				'js_vars'   => array(
					array(
						'customizer_only' => true,
						'element'  => '.navbar-brand-subtext',
						'function' => 'toggleClass',
						'class' => 'd-none',
						'value' => false,
						'context' => 'navbar-brand-subtext',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'custom_logo',
						'operator' => '==',
						'value'    => false,
					),
				),
			) );

			// SETTING - Show Search Button
			Kirki::add_field( 'cpschool', array(
				'type'        => 'toggle',
				'settings'    => 'header_main_enable_search',
				'label'       => __( 'Show Search Button', 'cpschool' ),
				'section'     => 'header_main',
				'transport'   => 'postMessage',
				'default' => true,
				'js_vars'   => array(
					array(
						'customizer_only' => true,
						'element'  => '#navbar-main-btn-search',
						'function' => 'toggleClass',
						'class' => 'd-none',
						'value' => false,
						'context' => 'navbar-main-btn-search',
					),
				)
			) );

			// SETTING - Stick To The Top
			Kirki::add_field( 'cpschool', array(
				'type'        => 'toggle',
				'settings'    => 'header_main_stick',
				'label'       => __( 'Stick To The Top', 'cpschool' ),
				'section'     => 'header_main',
				'transport'   => 'postMessage',
				'default' => false,
				'js_vars'   => array(
					array(
						'element'  => 'body',
						'function' => 'toggleClass',
						'class' => 'navbar-main-sticky-top',
						'context' => 'body',
						'value' => true
					),
				)
			) );

			// SETTING - Stretch To Full Width
			Kirki::add_field( 'cpschool', array(
				'type'        => 'toggle',
				'settings'    => 'header_main_stretch',
				'label'       => __( 'Stretch To Full Width', 'cpschool' ),
				'section'     => 'header_main',
				'transport'   => 'postMessage',
				'default' => false,
				'js_vars'   => array(
					array(
						'element'  => '#navbar-main .navbar-container',
						'function' => 'toggleClass',
						'class' => 'container-fluid',
						'context' => 'navbar-main-container',
						'value' => true
					),
					array(
						'element'  => '#navbar-main .navbar-container',
						'function' => 'toggleClass',
						'class' => 'container',
						'context' => 'navbar-main-container',
						'value' => false
					),
					array(
						'element'  => 'body',
						'function' => 'toggleClass',
						'class' => 'navbar-main-strech-to-full',
						'context' => 'body',
						'value' => true
					),
				)
			) );

			// SEPARATOR - Colors
			Kirki::add_field( 'cpschool', array(
				'settings' => 'main_header_colors',
				'type'        => 'separator',
				'label'       => __( 'Colors', 'cpschool' ),
				'section'     => 'header_main',
			) );

			// SETTING - Custom Background Color
			Kirki::add_field( 'cpschool', array(
				'type'        => 'color',
				'settings'    => 'header_main_bg_color',
				'label'       => __( 'Custom Background Color', 'cpschool' ),
				'section'     => 'header_main',
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-main-bg-color',
						'context' => array('editor', 'front')
					),
				),
				'choices'     => array(
					'alpha' => false,
				),
			) );

				cpschool_generate_customizer_color_settings('header-main-bg-color');

			// SETTING - Custom Logo Text Color
			Kirki::add_field( 'cpschool', array(
				'type'        => 'color',
				'settings'    => 'header_main_logo_text_color',
				'label'       => __( 'Custom Logo Text Color', 'cpschool' ),
				'section'     => 'header_main',
				'transport'   => 'auto',
				'active_callback' => array(
					array(
						'setting'  => 'custom_logo',
						'operator' => '==',
						'value'    => false,
					)
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-main-logo-text-color',
					),
					array(
						'element'  => ':root',
						'property' => '--header-main-dropbox-logo-text-color',
					),
				),
			) );

			// SETTING - Custom Logo Drop Box Background Color
			Kirki::add_field( 'cpschool', array(
				'type'        => 'color',
				'settings'    => 'header_main_logo_dropbox_bg_color',
				'label'       => __( 'Custom Logo Drop Box Background Color', 'cpschool' ),
				'section'     => 'header_main',
				'transport'   => 'postMessage',
				'choices'     => array(
					'alpha' => true,
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_main_logo_position',
						'operator' => '==',
						'value'    => 'dropbox',
					)
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-main-dropbox-bg-color',
					),
				),
				'js_vars'   => array(
					// Workaround for Kirki bug. We need to duplicate output functionality in js_var for it to work in customizer.
					array(
						'function' => 'style',
						'element'  => ':root',
						'property' => '--header-main-dropbox-bg-color',
					),
					array(
						'element'  => '#navbar-main .navbar-brand',
						'function' => 'toggleClass',
						'class' => 'custom-bg-disabled',
						'value' => '',
						'context' => 'navbar-brand',
					),
				)
			) );

			// SETTING - Custom Logo Drop Box Shadow Color
			Kirki::add_field( 'cpschool', array(
				'type'        => 'color',
				'settings'    => 'header_main_logo_dropbox_shadow_color',
				'label'       => __( 'Custom Logo Drop Box Shadow Color', 'cpschool' ),
				'section'     => 'header_main',
				'default' => '',
				'choices'     => array(
					'alpha' => true,
				),
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => '.navbar-style-dropbox .navbar-brand::before',
						'property' => 'box-shadow',
						'value_pattern' => '0 0 15px $ !important',
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'header_main_logo_position',
						'operator' => '==',
						'value'    => 'dropbox',
					)
				),
			) );

			// Hidden - Fonts
			Kirki::add_field( 'cpschool', array(
				'settings' => 'main_header_fonts',
				'type'        => 'separator',
				'label'       => __( 'Fonts', 'cpschool' ),
				'section'     => 'header_main',
			) );

			// SETTING - Logo Font Size
			Kirki::add_field( 'cpschool', array(
				'type'        => 'slider',
				'settings'    => 'header_main_logo_text_size',
				'label'       => esc_html__( 'Logo Font Size', 'cpschool' ),
				'description' => esc_html__( 'Logo can have font family customized in "General" > "Site Identity" > "Site Title Font Family".', 'cpschool' ),
				'section'     => 'header_main',
				'default'     => '100',
				'choices'     => array(
					'min'  => 70,
					'max'  => 130,
					'step' => 2,
				),
				'transport'   => 'auto',
				'active_callback' => array(
					array(
						'setting'  => 'custom_logo',
						'operator' => '==',
						'value'    => false,
					)
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-main-logo-font-size',
					),
				),
			) );

			// SETTING - Navigation Font Family
			Kirki::add_field( 'cpschool', array(
				'type'        => 'select',
				'settings'    => 'header_main_font_family',
				'label'       => __( 'Navigation Font Family', 'cpschool' ),
				'section'     => 'header_main',
				'transport'   => 'auto',
				'choices'     => cpschool_get_customizer_fonts_options('text', true),
				'default' => 'inherit',
				'output' => array(
					array(
						'element' => array( '#navbar-main .nav' ),
						'property' => 'font-family',
						'context' => array('editor', 'front')
					),
				),
			) );

			// SETTINGS - Navigation Font Size
			Kirki::add_field( 'cpschool', array(
				'type'        => 'slider',
				'settings'    => 'header_main_font_size',
				'label'       => esc_html__( 'Navigation Font Size', 'cpschool' ),
				'section'     => 'header_main',
				'default'     => 100,
				'choices'     => array(
					'min'  => 70,
					'max'  => 130,
					'step' => 2,
				),
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-main-font-size',
					),
				),
			) );

		// PANEL - Secondary Header
		Kirki::add_section( 'header_secondary', array(
			'priority'    => 30,
			'title'       => esc_html__( 'Secondary Header', 'cpschool' ),
			'description' => esc_html__( 'Settings for secondary header, place for less important navigation elements. Please keep in mind that it will be visible only when it has at least one menu attached.', 'cpschool' ),
		) );
			
			// SETTING - Stretch To Full Width
			Kirki::add_field( 'cpschool', array(
				'type'        => 'toggle',
				'settings'    => 'header_secondary_stretch',
				'label'       => __( 'Stretch To Full Width', 'cpschool' ),
				'section'     => 'header_secondary',
				'transport'   => 'postMessage',
				'default' => false,
				'js_vars'   => array(
					array(
						'element'  => '#navbar-secondary .navbar-container',
						'function' => 'toggleClass',
						'class' => 'container-fluid',
						'context' => 'navbar-secondary-container',
						'value' => true
					),
					array(
						'element'  => '#navbar-secondary .navbar-container',
						'function' => 'toggleClass',
						'class' => 'container',
						'context' => 'navbar-secondary-container',
						'value' => false
					),
					array(
						'element'  => 'body',
						'function' => 'toggleClass',
						'class' => 'navbar-secondary-strech-to-full',
						'context' => 'body',
						'value' => true
					),
				)
			) );

			// SETTING - Custom Background Color
			Kirki::add_field( 'cpschool', array(
				'type'        => 'color',
				'settings'    => 'header_secondary_bg_color',
				'label'       => __( 'Custom Background Color', 'cpschool' ),
				'section'     => 'header_secondary',
				'transport'   => 'auto',
				'choices'     => array(
					'alpha' => false,
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-secondary-bg-color',
						'context' => array('editor', 'front')
					),
				),
			) );

				cpschool_generate_customizer_color_settings('header-secondary-bg-color');

			// SETTINGS - Font Size
			Kirki::add_field( 'cpschool', array(
				'type'        => 'slider',
				'settings'    => 'header_secondary_font_size',
				'label'       => esc_html__( 'Font Size', 'cpschool' ),
				'section'     => 'header_secondary',
				'default'     => 100,
				'choices'     => array(
					'min'  => 70,
					'max'  => 130,
					'step' => 2,
				),
				'transport'   => 'auto',
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--header-secondary-font-size',
					),
				),
			) );

		// Section - Hero
		Kirki::add_section( 'hero_main', array(
			'priority'    => 40,
			'title'       => esc_html__( 'Hero', 'cpschool' ),
			'description' => esc_html__( 'Settings for hero, place where current page details are put in center of attention.', 'cpschool' ),
		) );

			// SETTING - Style
			Kirki::add_field( 'cpschool', array(
				'type'        => 'select',
				'settings'    => 'hero_main_style',
				'label'       => __( 'Style', 'cpschool' ),
				'section'     => 'hero_main',
				'default' => 'full-title-over-img',
				'transport'   => 'postMessage',
				'choices'     => array(
					'full-title-over-img'   => esc_html__( 'Full Width Image With Title Over It', 'cpschool' ),
					'full-title-under-img' => esc_html__( 'Full Width Image With Title Under It', 'cpschool' ),
					'img-under-title'  => esc_html__( 'Image Under Title', 'cpschool' ),
					'disabled'  => esc_html__( 'Disabled' ),
				),
				'js_vars'   => array(
					array(
						'element'  => 'body',
						'function' => 'toggleClass',
						'class' => 'has-hero',
						'value' => array('full-title-over-img', 'full-title-under-img', 'img-under-title'),
					),
					array(
						'element'  => '#hero-main',
						'function' => 'toggleClass',
						'class' => 'hero-full',
						'value' => array('full-title-over-img', 'full-title-under-img'),
					),
					array(
						'element'  => '#hero-main',
						'function' => 'toggleClass',
						'class' => 'hero-full-title-over-img',
						'value' => 'full-title-over-img'
					),
					array(
						'element'  => '#hero-main',
						'function' => 'toggleClass',
						'class' => 'hero-full-title-under-img',
						'value' => 'full-title-under-img'
					),
					array(
						'element'  => '#hero-main',
						'function' => 'toggleClass',
						'class' => 'hero-img-under-title',
						'value' => 'img-under-title'
					),
					array(
						'element'  => '.page-header',
						'function' => 'toggleClass',
						'class' => 'd-none',
						'value' => array('full-title-over-img', 'img-under-title'),
						'context' => 'page-header',
						'customizer_only' => true,
					),
					array(
						'element'  => 'body.singular .entry-featured-image',
						'function' => 'toggleClass',
						'class' => 'd-none',
						'value' => array('full-title-over-img', 'full-title-under-img', 'img-under-title'),
						'context' => 'entry-single-featured-image',
						'customizer_only' => true,
					),
					array(
						'element'  => '.hero-default-image-holder',
						'function' => 'toggleClass',
						'class' => 'd-none',
						'value' => 'img-under-title',
						'context' => 'hero-main-default-image-holder',
						'customizer_only' => true,
					),
					array(
						'element'  => '.page-header .breadcrumbs',
						'function' => 'toggleClass',
						'class' => 'hero-enabled',
						'value' => array('full-title-over-img', 'full-title-under-img', 'img-under-title'),
						'context' => 'page-breadcrumb',
						'customizer_only' => true,
					),
					array(
						'element'  => '#hero-main',
						'function' => 'toggleClass',
						'class' => 'd-none',
						'value' => 'disabled',
						'customizer_only' => true,
					),
				)
			) );

			// SETTING - Content Align
			Kirki::add_field( 'cpschool', array(
				'type'        => 'select',
				'settings'    => 'hero_main_content_align',
				'label'       => __( 'Content Align', 'cpschool' ),
				'section'     => 'hero_main',
				'transport'   => 'postMessage',
				'default' => 'center',
				'choices'     => array(
					'left'   => esc_html__( 'Left', 'cpschool' ),
					'center' => esc_html__( 'Center', 'cpschool' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'hero_main_style',
						'operator' => 'in',
						'value'    => array('img-under-title', 'full-title-over-img'),
					),
				),
				'js_vars'   => array(
					array(
						'element'  => '#hero-main',
						'function' => 'toggleClass',
						'class' => 'text-center',
						'value' => 'center'
					),
				)
			) );

			/*
			// SETTING - Parallax Effect For Image
			Kirki::add_field( 'cpschool', array(
				'type'        => 'toggle',
				'settings'    => 'hero_main_parallax',
				'label'       => __( 'Parallax Effect For Image', 'cpschool' ),
				'section'     => 'hero_main',
				'transport'   => 'postMessage',
				'default' => false,
				'active_callback' => array(
					array(
						'setting'  => 'hero_main_style',
						'operator' => '!=',
						'value'    => 'disabled',
					),
				),
			) );
			*/

			// SETTING - Background Color
			Kirki::add_field( 'cpschool', array(
				'type'        => 'color',
				'settings'    => 'hero_main_bg_color',
				'label'       => __( 'Background Color', 'cpschool' ),
				'description' => esc_html__( 'Hero background color that is also used as overlay when image is part of background.', 'cpschool' ),
				'section'     => 'hero_main',
				'transport'   => 'auto',
				'choices'     => array(
					'alpha' => false,
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--hero-main-bg-color',
						'context' => array('editor', 'front')
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'hero_main_style',
						'operator' => 'in',
						'value'    => array('full-title-over-img', 'full-title-under-img', 'img-under-title'),
					),
				),
			) );

				cpschool_generate_customizer_color_settings('hero-main-bg-color');

			// SETTING - Featured Image Opacity
			Kirki::add_field( 'cpschool', array(
				'type'        => 'slider',
				'settings'    => 'hero_main_img_opacity',
				'label'       => esc_html__( 'Featured Image Opacity', 'cpschool' ),
				'description' => esc_html__( 'Opacity used for hero featured image.', 'cpschool' ),
				'section'     => 'hero_main',
				'default'     => '10',
				'choices'     => array(
					'min'  => 10,
					'max'  => 90,
					'step' => 5,
				),
				'transport'   => 'auto',
				'active_callback' => array(
					array(
						'setting'  => 'hero_main_style',
						'operator' => 'in',
						'value'    => array('full-title-over-img', 'full-title-under-img'),
					),
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--hero-main-img-opacity',
						'units' => '%',
					),
				),
			) );

			// SETTING - Main Header Opacity
			Kirki::add_field( 'cpschool', array(
				'type'        => 'slider',
				'settings'    => 'hero_main_header_main_opacity',
				'label'       => esc_html__( 'Main Header Opacity', 'cpschool' ),
				'description' => esc_html__( 'Choose main header opacity set when hero area uses image in background.', 'cpschool' ),
				'section'     => 'hero_main',
				'default'     => '100',
				'choices'     => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 5,
				),
				'transport'   => 'auto',
				'active_callback' => array(
					array(
						'setting'  => 'hero_main_style',
						'operator' => 'in',
						'value'    => array('full-title-over-img', 'full-title-under-img'),
					),
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--hero-main-header-main-opacity',
						'units' => '%',
					),
				),
			) );

				// Hidden - Hero Main Header Main BG Transparent
				Kirki::add_field( 'cpschool', array(
					'type'        => 'hidden',
					'settings'    => 'hero_main_header_main_bg_transparent',
					'section'     => 'hero_main',
					'default' => false,
					'transport'   => 'postMessage',
					'js_vars'   => array(
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'navbar-main-has-hero-transparency',
							'context' => 'body',
							'value' => true
						),
					)
				) );

			// SETTING - Defaul Hero Images
			Kirki::add_field( 'cpschool', array(
				'type'        => 'repeater',
				'label'       => esc_html__( 'Defaul Hero Images', 'cpschool' ),
				'description' => esc_html__( 'Set images that will be randomly used when featured image is not set.', 'cpschool' ),
				'section'     => 'hero_main',
				'row_label' => array(
					'type'  => 'text',
					'value' => esc_html__( 'Image', 'cpschool' ),
				),
				'button_label' => esc_html__('Add Image', 'cpschool' ),
				'settings'     => 'hero_main_default_images',
				'fields' => array(
					'id' => array(
						'type'        => 'image',
						'label'       => esc_html__( 'Image', 'cpschool' ),
					),
				),
				'active_callback' => array(
					array(
						'setting'  => 'hero_main_style',
						'operator' => 'in',
						'value'    => array('full-title-over-img', 'full-title-under-img'),
					),
				),
			) );

			// SETTING - Breadcrumb Styling
			Kirki::add_field( 'cpschool', array(
				'type'        => 'select',
				'settings'    => 'hero_main_breadcrumb_style',
				'label'       => __( 'Breadcrumb Styling', 'cpschool' ),
				'description' => esc_html__( 'Please keep in mind that breadcrumb will only be visible when enabled for currently viewed page type in "Content" section.', 'cpschool' ),
				'section'     => 'hero_main',
				'transport'   => 'postMessage',
				'default' => 'above_title_no_bg',
				'choices'     => array(
					'above_title_no_bg'   => esc_html__( 'Above Title', 'cpschool' ),
					'top_right' => esc_html__( 'Top Right Corner', 'cpschool' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'hero_main_style',
						'operator' => 'in',
						'value'    => array('img-under-title', 'full-title-over-img', 'full-title-under-img'),
					),
				),
				'js_vars'   => array(
					array(
						'element'  => '#hero-main',
						'function' => 'toggleClass',
						'class' => 'hero-breadcrumb-above-title',
						'value' => 'above_title_no_bg',
						'context' => 'hero-main',
					),
					array(
						'element'  => '#hero-main',
						'function' => 'toggleClass',
						'class' => 'hero-breadcrumb-top-right',
						'value' => 'top_right',
						'context' => 'hero-main',
					),
					array(
						'customizer_only' => true,
						'element'  => '.page-header .breadcrumbs',
						'function' => 'toggleClass',
						'class' => 'hero-has-breadcrumb-top-right',
						'value' => 'top_right',
						'context' => 'page-breadcrumb',
					),
				)
			) );

		// Panel - Content
		Kirki::add_panel( 'content_area', array(
			'priority'    => 50,
			'title'       => esc_html__( 'Content', 'cpschool' ),
			'description' => esc_html__( 'Settings related to content area.', 'cpschool' ),
		) );

			// Section - Posts List
			Kirki::add_section( 'entries_lists', array(
				'title'       => esc_html__( 'Posts List', 'cpschool' ),
				'description' => esc_html__( 'Settings related to pages that are listing posts.', 'cpschool' ),
				'panel' => 'content_area'
			) );
			
				cpschool_generate_content_common_settings( 'entries_lists', 'post', array( 
					'sidebars' =>  array('sidebar-right'), 
					'meta' => array( 'author', 'post-date', 'tax-category', 'comments', 'sticky' ),
				) );

				// SETTING - Posts In a Row
				Kirki::add_field( 'cpschool', array(
					'type'        => 'slider',
					'settings'    => 'entries_lists_row_count',
					'label'       => esc_html__( 'Posts In a Row', 'cpschool' ),
					'section'     => 'entries_lists',
					'default'     => 1,
					'choices'     => array(
						'min'  => 1,
						'max'  => 4,
						'step' => 1,
					),
					'transport'   => 'postMessage',
					'js_vars'   => array(
						array(
							'element'  => '.entry-col',
							'function' => 'toggleClass',
							'class' => array('col-md-6', 'col-lg-3'),
							'value' => '4'
						),
						array(
							'element'  => '.entry-col',
							'function' => 'toggleClass',
							'class' => 'col-md-4',
							'value' => '3'
						),
						array(
							'element'  => '.entry-col',
							'function' => 'toggleClass',
							'class' => 'col-md-6',
							'value' => '2'
						),
						array(
							'element'  => '.entry-col',
							'function' => 'toggleClass',
							'class' => 'col-12',
							'value' => '1'
						),
					),
				) );

				// SETTING - Boxed Design
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'entries_lists_enable_bg',
					'label'       => __( 'Boxed Design', 'cpschool' ),
					'description' => __( 'Enable to put posts content in a box. Background for boxes can be customized under "General" > "Colors" > "Content Boxes Background". ', 'cpschool' ),
					'section'     => 'entries_lists',
					'transport'   => 'postMessage',
					'default' => false,
					'js_vars'   => array(
						array(
							'element'  => '.entries-row',
							'function' => 'toggleClass',
							'class' => 'entry-col-boxed',
							'value' => true
						),
					),
				) );

				// SETTING - Content Type
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'entries_lists_content_type',
					'label'       => __( 'Content Type', 'cpschool' ),
					'section'     => 'entries_lists',
					'default' => 'excerpt',
					'transport'   => 'refresh',
					'choices'     => array(
						'excerpt'   => esc_html__( 'Excerpt', 'cpschool' ),
						'content' => esc_html__( 'Content', 'cpschool' ),
						'no-content' => esc_html__( 'No Content', 'cpschool' ),
					),
				) );

				// SETTING - Hide "Continue Reading"
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'entries_lists_hide_continue_reading',
					'label'       => __( 'Hide "Continue Reading"', 'cpschool' ),
					'description' => __( 'Enable to remove "Continue Reading" button. Posts can still be opened by clicking on title. ', 'cpschool' ),
					'section'     => 'entries_lists',
					'transport'   => 'postMessage',
					'default' => false,
					'js_vars'   => array(
						array(
							'element'  => '.cpschool-read-more-link',
							'function' => 'toggleClass',
							'class' => 'd-none',
							'value' => true,
							'context' => 'read-more-link',
							'customizer_only' => true,
						),
					),
					'active_callback' => array(
						array(
							'setting'  => 'entries_lists',
							'operator' => '==',
							'value'    => 'excerpt',
						)
					),
				) );

				// SETTING - Featured Image Style
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'entries_lists_featured_image_style',
					'label'       => __( 'Featured Image Style', 'cpschool' ),
					'section'     => 'entries_lists',
					'default'     => 'on_top',
					'transport'   => 'postMessage',
					'choices'     => array(
						''  => 'Under Title',
						'on_top' => esc_html__( 'Above Title', 'cpschool' ),
						'disabled' => esc_html__( 'Disabled', 'cpschool' ),
					),
					'js_vars'   => array(
						array(
							'element'  => '.entries-row',
							'function' => 'toggleClass',
							'class' => 'image-on-top',
							'value' => 'on_top',
							'context' => 'entries-row',
						),
						array(
							'element'  => '.entries-row',
							'function' => 'toggleClass',
							'class' => 'image-disabled',
							'value' => 'disabled',
							'context' => 'entries-row',
							'customizer_only' => true,
						),
					)
				) );

				// SETTING - Fixed Featured Image Height
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'entries_lists_featured_image_height_enable',
					'label'       => esc_html__( 'Fixed Featured Image Height', 'cpschool' ),
					'section'     => 'entries_lists',
					'default'     => false,
					'transport'   => 'postMessage',
					'active_callback' => array(
						array(
							'setting'  => 'entries_lists_featured_image_style',
							'operator' => '!=',
							'value'    => 'disabled',
						)
					),
					'js_vars'   => array(
						array(
							'element'  => '.entries-row',
							'function' => 'toggleClass',
							'class' => 'fixed-image-height',
							'value' => true,
							'context' => 'entries-row',
						),
					)
				) );

				// SETTING - Featured Image Height
				Kirki::add_field( 'cpschool', array(
					'type'        => 'slider',
					'settings'    => 'entries_lists_featured_image_height',
					'label'       => esc_html__( 'Featured Image Height (px)', 'cpschool' ),
					'section'     => 'entries_lists',
					'default'     => '240',
					'choices'     => array(
						'min'  => 100,
						'max'  => 768,
						'step' => 1,
					),
					'transport'   => 'auto',
					'active_callback' => array(
						array(
							'setting'  => 'entries_lists_featured_image_height_enable',
							'operator' => '==',
							'value'    => true,
						)
					),
					'output' => array(
						array(
							'element'  => '.fixed-image-height .entry-featured-image img',
							'property' => 'height',
							'units' => 'px',
						),
					),
				) );

				// SETTING - Show Breadcrumb
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'entries_lists_breadcrumb',
					'label'       => __( 'Show Breadcrumb', 'cpschool' ),
					'section'     => 'entries_lists',
					'transport'   => 'postMessage',
					'default' => true,
					'js_vars'   => array(
						array(
							'customizer_only' => true,
							'element'  => 'body.entries-list .breadcrumbs',
							'function' => 'toggleClass',
							'class' => 'd-none',
							'value' => false,
							'context' => array('hero-breadcrumb', 'page-breadcrumb'),
							'context_check' => '!is_singular',
						),
					),
				) );

				// SEPARATOR - Main Posts Page
				Kirki::add_field( 'cpschool', array(
					'settings' => 'posts_main',
					'type'        => 'separator',
					'label'       => __( 'Main Posts Page', 'cpschool' ),
					'description' => esc_html__( 'Configure settings for main page displaying recent posts. It can be homepage or "Posts page" configured in "Homepage Settings" section.', 'cpschool' ),
					'section'     => 'entries_lists',
				) );

				// SETTING - Show Page Title / Hero Area
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'posts_main_hero',
					'label'       => __( 'Show Page Title / Hero Area', 'cpschool' ),
					'section'     => 'entries_lists',
					'transport'   => 'postMessage',
					'default' => false,
					'js_vars'   => array(
						array(
							'customizer_only' => true,
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'hide-hero-page-title',
							'value' => false,
							'context' => array('body'),
							'context_check' => 'is_home',
						),
						array(
							'customizer_only' => true,
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'has-hero',
							'value' => true,
							'context' => array('body'),
							'context_check' => 'is_home',
						),
					),
				) );

				// SETTING - Custom Page Title
				Kirki::add_field( 'cpschool', array(
					'type'        => 'text',
					'settings'    => 'posts_main_hero_title',
					'label'       => __( 'Custom Page Title', 'cpschool' ),
					'section'     => 'entries_lists',
					'transport'   => 'postMessage',
					'default' => '',
					'js_vars'   => array(
						array(
							'element'  => '.hero-content .page-title, .page-header .page-title',
							'function' => 'html',
						),
					),
				) );

				// SETTING - Custom Sub Title
				Kirki::add_field( 'cpschool', array(
					'type'        => 'text',
					'settings'    => 'posts_main_hero_subtitle',
					'label'       => __( 'Custom Sub Title', 'cpschool' ),
					'section'     => 'entries_lists',
					'transport'   => 'postMessage',
					'default' => '',
					'js_vars'   => array(
						array(
							'element'  => '.hero-content .page-meta, .page-header .page-meta',
							'function' => 'html',
						),
					),
				) );

			// Section - Single Posts
			Kirki::add_section( 'posts', array(
				'title'       => esc_html__( 'Single Posts', 'cpschool' ),
				'description' => esc_html__( 'Settings related to single posts.', 'cpschool' ),
				'panel' => 'content_area'
			) );

				cpschool_generate_content_common_settings( 'posts', 'post', array( 
					'sidebars' =>  array('sidebar-right'),
					'meta' => array( 'author', 'post-date', 'tax-category', 'tax-post_tag', 'comments', 'sticky' ), 
					'navigation' => true
				) );

				// SETTING - Show Breadcrumb
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'posts_breadcrumb',
					'label'       => __( 'Show Breadcrumb', 'cpschool' ),
					'section'     => 'posts',
					'transport'   => 'postMessage',
					'default' => true,
					'js_vars'   => array(
						array(
							'customizer_only' => true,
							'element'  => 'body.single .breadcrumbs',
							'function' => 'toggleClass',
							'class' => 'd-none',
							'value' => false,
							'context' => array('hero-breadcrumb', 'page-breadcrumb'),
							'context_check' => 'is_single',
						),
					),
				) );

			// Section - Pages
			Kirki::add_section( 'pages', array(
				'title'       => esc_html__( 'Pages', 'cpschool' ),
				'description' => esc_html__( 'Settings related to pages.', 'cpschool' ),
				'panel' => 'content_area'
			) );

				cpschool_generate_content_common_settings( 'pages', 'page',  array( 
					'sidebars' =>  array('sidebar-left'),
					'meta' => array(  )
				) );

				// SETTING - Show Breadcrumb
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'pages_breadcrumb',
					'label'       => __( 'Show Breadcrumb', 'cpschool' ),
					'section'     => 'pages',
					'transport'   => 'postMessage',
					'default' => true,
					'js_vars'   => array(
						array(
							'customizer_only' => true,
							'element'  => 'body.page .breadcrumbs',
							'function' => 'toggleClass',
							'class' => 'd-none',
							'value' => false,
							'context' => array('hero-breadcrumb', 'page-breadcrumb'),
							'context_check' => 'is_page',
						),
					),
				) );

			// Section - Alerts
			Kirki::add_section( 'alerts', array(
				'title'       => esc_html__( 'Alerts', 'cpschool' ),
				'description' => esc_html__( 'Configure alerts visible for all visitors.', 'cpschool' ),
				'panel' => 'content_area'
			) );

				// SEPARATOR - Alert Bar
				Kirki::add_field( 'cpschool', array(
					'settings' => 'alert_bar',
					'type'        => 'separator',
					'label'       => __( 'Alert Bar', 'cpschool' ),
					'description' => esc_html__( 'Configure dismissable alert visible at the top of the page.', 'cpschool' ),
					'section'     => 'alerts',
				) );

				// SETTING - Content
				Kirki::add_field( 'cpschool', array(
					'type'        => 'editor',
					'settings'    => 'alert_html',
					'label'       => __( 'Content', 'cpschool' ),
					'description' => esc_html__( 'Leave empty to disable.', 'cpschool' ),
					'section'     => 'alerts',
					'transport'   => 'refresh',
				) );
				
				// SETTING - Custom Alert Bar Background Color
				Kirki::add_field( 'cpschool', array(
					'type'        => 'color',
					'settings'    => 'alert_bg_color',
					'label'       => __( 'Custom Alert Bar Background Color', 'cpschool' ),
					'section'     => 'alerts',
					'transport'   => 'auto',
					'default'     => '',
					'choices'     => array(
						'alpha' => false,
					),
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--alert-bg-color',
						),
					),
				) );

					cpschool_generate_customizer_color_settings('alert-bg-color');

				// SETTING - Allow Dismissal
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'alerts_dismissable',
					'label'       => __( 'Allow Dismissal', 'cpschool' ),
					'description' => esc_html__( 'Enable to allow alert to be dismissed. Changing the content will result in the alert showing up again after it has been dismissed.', 'cpschool' ),
					'section'     => 'alerts',
					'transport'   => 'postMessage',
					'default' => false,
					'js_vars'   => array(
						array(
							'element'  => '#site-alert .close',
							'function' => 'toggleClass',
							'class' => 'd-none',
							'value' => false,
							'context' => 'site-alert-close'
						),
					),
				) );


				// SEPARATOR - Popup With Alert
				Kirki::add_field( 'cpschool', array(
					'settings' => 'alert_popup',
					'type'        => 'separator',
					'label'       => __( 'Popup With Alert', 'cpschool' ),
					'section'     => 'alerts',
				) );

				// SETTING - Content
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'alert_popup_block',
					'label'       => __( 'Content', 'cpschool' ),
					'description' => __( 'Choose "Reusable Block" to display as dismissable popup. You can create new ones in WordPress Admin > "Reusable Blocks". Changing block content will result in popup showing up again.', 'cpschool' ),
					'section'     => 'alerts',
					'transport'   => 'refresh',
					'choices'     => array(),
				) );

		// Panel - Elements
		Kirki::add_panel( 'elements', array(
			'priority'    => 60,
			'title'       => esc_html__( 'Elements', 'cpschool' ),
			'description' => esc_html__( 'Settings for elements used across site.', 'cpschool' ),
		) );

			// Section - Headings
			Kirki::add_section( 'headers', array(
				'title'       => esc_html__( 'Headings', 'cpschool' ),
				'description' => esc_html__( 'Settings related to appearance of headers.', 'cpschool' ),
				'panel' => 'elements'
			) );

				// SETTING - Header Styling
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'h_style',
					'label'       => __( 'Header Styling', 'cpschool' ),
					'section'     => 'headers',
					'default'     => 'separator',
					'transport'   => 'postMessage',
					'choices'     => array(
						''   => esc_html__( 'Default', 'cpschool' ),
						'separator' => esc_html__( 'With Separator', 'cpschool' ),
					),
					'js_vars'   => array(
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'h-style-separator',
							'context' => 'body',
							'value' => 'separator'
						),
					)
				) );

				// SETTING - Header Separator Position
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'h_sep_pos',
					'label'       => __( 'Header Separator Position', 'cpschool' ),
					'section'     => 'headers',
					'default'     => 'bottom',
					'transport'   => 'postMessage',
					'choices'     => array(
						''   => esc_html__( 'Top', 'cpschool' ),
						'bottom' => esc_html__( 'Bottom', 'cpschool' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'h_style',
							'operator' => '==',
							'value'    => 'separator',
						)
					),
					'js_vars'   => array(
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'h-style-separator-bottom',
							'context' => 'body',
							'value' => 'bottom'
						),
					),
				) );

				// SETTING - Header Separator Color
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'h_sep_color',
					'label'       => __( 'Header Separator Color', 'cpschool' ),
					'section'     => 'headers',
					'default'     => '',
					'transport'   => 'postMessage',
					'choices'     => array(
						''   => esc_html__( 'Accent', 'cpschool' ),
						'hl' => esc_html__( 'Highlight Accent', 'cpschool' ),
					),
					'active_callback' => array(
						array(
							'setting'  => 'h_style',
							'operator' => '==',
							'value'    => 'separator',
						)
					),
					'js_vars'   => array(
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'h-style-separator-hl',
							'context' => 'body',
							'value' => 'hl'
						),
					),
				) );

				// SEPARATOR - Fonts
				Kirki::add_field( 'cpschool', array(
					'settings' => 'headers_font',
					'type'        => 'separator',
					'label'       => __( 'Fonts', 'cpschool' ),
					'section'     => 'headers',
				) );
				
				// SETTING - Font Family
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'headers_font_family',
					'label'       => __( 'Font Family', 'cpschool' ),
					'section'     => 'headers',
					'transport'   => 'auto',
					'choices'     => cpschool_get_customizer_fonts_options('header', true),
					'default' => 'inherit',
					'output' => array(
						array(
							'element' => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', '.h1', '.h2', '.h3', '.h4', '.h5', '.h6' ),
							'property' => 'font-family',
							'context' => array('editor', 'front')
						),
					),
				) );

				// SETTING - Font Size
				Kirki::add_field( 'cpschool', array(
					'type'        => 'slider',
					'settings'    => 'headers_font_size',
					'label'       => esc_html__( 'Font Size', 'cpschool' ),
					'section'     => 'headers',
					'default'     => 100,
					'choices'     => array(
						'min'  => 70,
						'max'  => 130,
						'step' => 2,
					),
					'transport'   => 'auto',
					'output' => array(
						array(
							'element'  => ':root',
							'property' => '--headers-font-size',
							'context' => array('editor', 'front')
						),
					),
				) );

			// Section - Buttons
			Kirki::add_section( 'buttons', array(
				'title'       => esc_html__( 'Buttons', 'cpschool' ),
				'description' => esc_html__( 'Settings related to buttons appearance.', 'cpschool' ),
				'panel' => 'elements'
			) );

				// SETTING - Buttons Styling
				Kirki::add_field( 'cpschool', array(
					'type'        => 'select',
					'settings'    => 'buttons_style',
					'label'       => __( 'Buttons Styling', 'cpschool' ),
					'section'     => 'buttons',
					'default'     => '',
					'transport'   => 'postMessage',
					'choices'     => array(
						''   => esc_html__( 'Default', 'cpschool' ),
						'outline' => esc_html__( 'Outline', 'cpschool' ),
					),
					'js_vars'   => array(
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'btn-style-outline',
							'context' => 'body',
							'value' => 'outline'
						),
					)
				) );

			// Section - Sidebars
			Kirki::add_section( 'sidebars', array(
				'title'       => esc_html__( 'Sidebars', 'cpschool' ),
				'description' => esc_html__( 'Settings related to sidebars appearance and behavior.', 'cpschool' ),
				'panel' => 'elements'
			) );

				// SETTING - Sticky Sidebars
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'sidebars_sticky',
					'label'       => esc_html__( 'Sticky Sidebars', 'cpschool' ),
					'description' => esc_html__( 'Stick sidebars to the top when scrolling. Only works when sidebar content can fit in browser window.', 'cpschool' ),
					'section'     => 'sidebars',
					'default'     => true,
					'transport'   => 'postMessage',
					'js_vars'   => array(
						array(
							'element'  => 'body',
							'function' => 'toggleClass',
							'class' => 'sidebars-check-sticky',
							'context' => 'body',
							'value' => true
						),
					)
				) );

				// SETTING - Boxed Design
				Kirki::add_field( 'cpschool', array(
					'type'        => 'toggle',
					'settings'    => 'sidebars_enable_bg',
					'label'       => __( 'Boxed Design', 'cpschool' ),
					'description' => __( 'Enable to put sidebar content in a box. Background can be customized under "General" > "Colors" > "Content Boxes Background". ', 'cpschool' ),
					'section'     => 'sidebars',
					'transport'   => 'postMessage',
					'default' => true,
					'js_vars'   => array(
						array(
							'element'  => '.sidebar-widget-area',
							'function' => 'toggleClass',
							'class' => 'sidebar-widget-area-boxed',
							'value' => true,
							'context' => 'sidebar-widget-area'
						),
					),
				) );

		// Section - Footer
		Kirki::add_section( 'footer_main_area', array(
			'priority'    => 70,
			'title'       => esc_html__( 'Footer', 'cpschool' ),
			'description' => esc_html__( 'Settings for footer.', 'cpschool' ),
		) );

			// SETTING - Background/Overlay Color
			Kirki::add_field( 'cpschool', array(
				'type'        => 'color',
				'settings'    => 'footer_main_bg_color',
				'label'       => __( 'Background/Overlay Color', 'cpschool' ),
				'section'     => 'footer_main_area',
				'transport'   => 'auto',
				'choices'     => array(
					'alpha' => true,
				),
				'output' => array(
					array(
						'element'  => ':root',
						'property' => '--footer-main-bg-color',
						'context' => array('editor', 'front')
					),
				),
			) );

				cpschool_generate_customizer_color_settings('footer-main-bg-color');

			// SETTING - Background Image
			Kirki::add_field( 'cpschool', array(
				'type'        => 'image',
				'settings'    => 'footer_main_bg_image',
				'label'       => __( 'Background Image', 'cpschool' ),
				'section'     => 'footer_main_area',
				'transport'   => 'refresh',
				'default' => '',
				'choices'     => array(
					'save_as' => 'array',
				),
			) );

			// SETTING - Include Reusable Block
			Kirki::add_field( 'cpschool', array(
				'type'        => 'select',
				'settings'    => 'footer_main_block',
				'label'       => __( 'Include Reusable Block', 'cpschool' ),
				'description' => __( 'Choose "Reusable Block" that will be displayed in footer after widgets. You can create new ones in WordPress Admin > "Reusable Blocks".', 'cpschool' ),
				'section'     => 'footer_main_area',
				'transport'   => 'refresh',
				'choices'     => array(),
			) );

			// SETTING - Custom Footer Text
			Kirki::add_field( 'cpschool', array(
				'type'        => 'editor',
				'settings'    => 'footer_main_custom_html',
				'label'       => __( 'Custom Footer Text', 'cpschool' ),
				'section'     => 'footer_main_area',
				'transport'   => 'refresh',
			) );
	}
}

if ( ! function_exists( 'cpschool_reusable_block_integration' ) ) {
	add_filter('kirki_control_select_to_json', 'cpschool_reusable_block_integration', 10, 2);

	/**
	 * Makes Kirki pull blocks only while on Customizer.
	 *
	 * @param [type] $json
	 * @param [type] $settings
	 * @return void
	 */
	function cpschool_reusable_block_integration( $json, $settings ) {
		$settings_with_blocks = array('footer_main_block', 'alert_popup_block');
		if( in_array($settings['default']->id, $settings_with_blocks)) {
			static $reusable_blocks_choices = array();
			if( !$reusable_blocks_choices ) {
				$reusable_blocks_choices =  array(0 => __( 'Disable', 'cpschool' )) + Kirki_Helper::get_posts( array('post_type' => 'wp_block', 'numberposts' => 100) );
			}

			$json['choices'] = $reusable_blocks_choices;
		}

		return $json;
	}
}