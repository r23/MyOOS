<?php
/**
 * Theme basic setup.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = get_theme_mod( 'content_width' ); /* pixels */
}

if ( ! function_exists( 'cpschool_setup' ) ) {
	add_action( 'after_setup_theme', 'cpschool_setup' );

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 */
	function cpschool_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on CampuPress Flex, use a find and replace
		 * to change 'cpschool' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'cpschool', get_template_directory() . '/inc/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// Refister all menus used in theme.
		register_nav_menus(
			array(
				'desktop'          => __( 'Desktop Main Menu', 'cpschool' ),
				'desktop-extended' => __( 'Desktop Slide-In Menu', 'cpschool' ),
				'mobile'           => __( 'Mobile Menu', 'cpschool' ),
				'secondary-left'   => __( 'Secondary Header - Left', 'cpschool' ),
				'secondary-right'  => __( 'Secondary Header - Right', 'cpschool' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Adding Thumbnail basic support
		 */
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'hero', 2560 );

		/*
		 * Adding support for Widget edit icons in customizer
		 */
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'cpschool_custom_background_args',
				array(
					'default-image' => '',
				)
			)
		);

		// Set up the WordPress Theme logo feature.
		add_theme_support( 'custom-logo' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Check and setup theme settings.
		cpschool_setup_theme_settings();

		// Add excerpt support for pages.
		add_post_type_support( 'page', 'excerpt' );

		// Add support for wide blocks - it only works when sidebars are not displayed.
		add_theme_support( 'align-wide' );

		// Add custom colors to blocks.
		$color_pallete = array(
			array(
				'name'  => __( 'Main', 'cpschool' ),
				'slug'  => 'color-bg-alt',
				'color' => get_theme_mod( 'color_bg_alt' ),
			),
			array(
				'name'  => __( 'Main Header', 'cpschool' ),
				'slug'  => 'header-main-bg-color',
				'color' => get_theme_mod( 'header_main_bg_color' ),
			),
			array(
				'name'  => __( 'Secondary Header', 'cpschool' ),
				'slug'  => 'header-secondary-bg-color',
				'color' => get_theme_mod( 'header_secondary_bg_color' ),
			),
			array(
				'name'  => __( 'Hero', 'cpschool' ),
				'slug'  => 'hero-main-bg-color',
				'color' => get_theme_mod( 'hero_main_bg_color' ),
			),
			array(
				'name'  => __( 'Entries', 'cpschool' ),
				'slug'  => 'color-boxes',
				'color' => get_theme_mod( 'color_boxes' ),
			),
			array(
				'name'  => __( 'Footer', 'cpschool' ),
				'slug'  => 'footer-main-bg-color',
				'color' => get_theme_mod( 'footer_main_bg_color' ),
			),
			array(
				'name'  => __( 'Background', 'cpschool' ),
				'slug'  => 'color-bg',
				'color' => get_theme_mod( 'color_bg' ),
			),
		);
		// Skip the ones that are not set.
		foreach ( $color_pallete as $color_key => $color ) {
			if ( ! $color['color'] ) {
				unset( $color_pallete[ $color_key ] );
			}
		}

		// Blocks editor goes crazy when array keys don't have correct order.
		$color_pallete = array_values( $color_pallete );
		add_theme_support( 'editor-color-palette', $color_pallete );

		// Set fonts sizes based on font size customizer setting.
		$font_size_modifier = get_theme_mod( 'body_font_size' );
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name' => __( 'Small', 'cpschool' ),
					'size' => 13 * $font_size_modifier / 100,
					'slug' => 'small',
				),
				array(
					'name' => __( 'Normal', 'cpschool' ),
					'slug' => 'normal',
				),
				array(
					'name' => __( 'Medium', 'cpschool' ),
					'size' => 20 * $font_size_modifier / 100,
					'slug' => 'medium',
				),
				array(
					'name' => __( 'Large', 'cpschool' ),
					'size' => 36 * $font_size_modifier / 100,
					'slug' => 'large',
				),
				array(
					'name' => __( 'Huge', 'cpschool' ),
					'size' => 48 * $font_size_modifier / 100,
					'slug' => 'larger',
				),
			)
		);

		/**
		 * Filter UnderStrap custom-header support arguments.
		 *
		 * @since UnderStrap 0.5.2
		 *
		 * @param array $args {
		 *     An array of custom-header support arguments.
		 *
		 *     @type string $default-image          Default image of the header.
		 *     @type string $default_text_color     Default color of the header text.
		 *     @type int    $width                  Width in pixels of the custom header image. Default 954.
		 *     @type int    $height                 Height in pixels of the custom header image. Default 1300.
		 *     @type string $wp-head-callback       Callback function used to styles the header image and text
		 *                                          displayed on the blog.
		 *     @type string $flex-height            Flex support for height of header.
		 * }
		 */
		/*
		add_theme_support(
			'custom-header',
			apply_filters(
				'cpschool_custom_header_args',
				array(
					'default-image' => get_parent_theme_file_uri( '/img/header.jpg' ),
					'width'         => 2000,
					'height'        => 1200,
					'flex-height'   => true,
				)
			)
		);

		register_default_headers(
			array(
				'default-image' => array(
					'url'           => '%s/img/header.jpg',
					'thumbnail_url' => '%s/img/header.jpg',
					'description'   => __( 'Default Header Image', 'cpschool' ),
				),
			)
		);
		*/
	}
}
