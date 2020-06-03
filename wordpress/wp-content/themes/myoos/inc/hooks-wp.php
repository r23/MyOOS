<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cpschool_body_classes' ) ) {
	add_filter( 'body_class', 'cpschool_body_classes' );

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	function cpschool_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}

		//check if current page has sidebar set
		if ( cpschool_get_active_sidebars() ) {
			$classes[] = 'has-sidebar';
		}

		$hero_style = cpschool_get_hero_style();
		if ( $hero_style ) {
			$classes[] = 'has-hero';

			// Adds a class for single pages with featured images set
			if ( ( is_singular() && has_post_thumbnail() ) || ( $hero_style != 'img-under-title' && get_theme_mod( 'hero_main_default_images' ) ) ) {
				$classes[] = 'has-hero-image';
				if ( has_post_thumbnail() ) {
					$classes[] = 'has-featured-image';
				}
			}
		}

		if ( is_singular() ) {
			$classes[] = 'singular';

			// Adds a class that adjust default top margin for content.
			$top_margin = get_post_meta( get_the_ID(), 'cps_top_margin', true );
			if ( $top_margin == 'remove' ) {
				$classes[] = 'main-wrapper-margin-top-disabled';

				if ( ! $hero_style ) {
					$pull_under = get_post_meta( get_the_ID(), 'cps_content_pull_under', true );
					if ( $pull_under ) {
						$classes[] = 'main-wrapper-pull-under';
						$classes[] = 'has-hero';
					}
				}
			}

			// Adds a class that adjust default bottom margin for content.
			$bottom_margin = get_post_meta( get_the_ID(), 'cps_bottom_margin', true );
			if ( $bottom_margin == 'remove' ) {
				$classes[] = 'main-wrapper-margin-bottom-disabled';
			}
		} else {
			// Adds a class of hfeed to non-singular pages.
			$classes[] = 'hfeed';
			$classes[] = 'entries-list';
		}

		// Adds class for customizer. TODO we schould have special stylesheet to load instead.
		if ( is_customize_preview() ) {
			$classes[] = 'is-customizer';
		}

		return $classes;
	}
}

// Filter custom logo with correct classes.
if ( ! function_exists( 'cpschool_change_logo_class' ) ) {
	add_filter( 'get_custom_logo', 'cpschool_change_logo_class' );

	/**
	 * Replaces logo CSS class.
	 *
	 * @param string $html Markup.
	 *
	 * @return mixed
	 */
	function cpschool_change_logo_class( $html ) {
		$html = str_replace( 'class="custom-logo"', 'class="img-fluid"', $html );
		$html = str_replace( 'class="custom-logo-link"', 'class="' . implode( ' ', cpschool_class( 'navbar-brand', 'navbar-brand custom-logo-link', true ) ) . '"', $html );
		$html = str_replace( 'alt=""', 'title="Home" alt="logo"', $html );

		return $html;
	}
}

if ( ! function_exists( 'cpschool_pingback' ) ) {
	add_action( 'wp_head', 'cpschool_pingback' );

	/**
	 * Add a pingback url auto-discovery header for single posts of any post type.
	 */
	function cpschool_pingback() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="' . esc_url( get_bloginfo( 'pingback_url' ) ) . '">' . "\n";
		}
	}
}

if ( ! function_exists( 'cpschool_mobile_web_app_meta' ) ) {
	add_action( 'wp_head', 'cpschool_mobile_web_app_meta' );

	/**
	 * Add mobile-web-app meta.
	 */
	function cpschool_mobile_web_app_meta() {
		echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr( get_bloginfo( 'name' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '">' . "\n";
	}
}

if ( ! function_exists( 'cpschool_adjacent_post_link_change' ) ) {
	add_filter( 'previous_post_link', 'cpschool_adjacent_post_link_change' );
	add_filter( 'next_post_link', 'cpschool_adjacent_post_link_change' );

	/**
	 * Add Mega Menu Menu items
	 */
	function cpschool_adjacent_post_link_change( $output ) {
		$output = str_replace( 'rel="', 'class="btn btn-secondary" rel="', $output );

		return $output;
	}
}

if ( ! function_exists( 'cpschool_block_editor_settings' ) ) {
	add_filter( 'block_editor_settings', 'cpschool_block_editor_settings', 10, 2 );

	function cpschool_block_editor_settings( $editor_settings, $post ) {
		$editor_settings['styles'][] = array( 'css' => 'body { font-family: "Inter var"; }' );

		return $editor_settings;
	}
}

if ( ! function_exists( 'cpschool_show_reusable_blocks_admin' ) ) {
	add_filter( 'register_post_type_args', 'cpschool_show_reusable_blocks_admin', 10, 2 );

	/**
	 * Add site info hook to WP hook library.
	 */
	function cpschool_show_reusable_blocks_admin( $args, $post_type ) {
		if ( is_admin() && $post_type == 'wp_block' ) {
			$args['show_in_menu']        = true;
			$args['_builtin']            = false;
			$args['labels']['name']      = __( 'Reusable Blocks' );
			$args['labels']['menu_name'] = __( 'Reusable Blocks' );
			$args['menu_icon']           = 'dashicons-screenoptions';
			$args['menu_position']       = 58;
			$args['show_in_nav_menus']   = true;
		}

		return $args;
	}
}


if ( ! function_exists( 'cpschool_custom_excerpt_more' ) ) {
	add_filter( 'excerpt_more', 'cpschool_custom_excerpt_more' );

	/**
	 * Removes the ... from the excerpt read more link
	 *
	 * @param string $more The excerpt.
	 *
	 * @return string
	 */
	function cpschool_custom_excerpt_more( $more ) {
		if ( ! is_admin() ) {
			$more = '';
		}
		return $more;
	}
}

if ( ! function_exists( 'cpschool_all_excerpts_get_more_link' ) ) {
	add_filter( 'wp_trim_excerpt', 'cpschool_all_excerpts_get_more_link' );

	/**
	 * Adds a custom read more link to all excerpts, manually or automatically generated
	 *
	 * @param string $post_excerpt Posts's excerpt.
	 *
	 * @return string
	 */
	function cpschool_all_excerpts_get_more_link( $post_excerpt ) {
		if ( ! is_admin() ) {
			$post_excerpt = $post_excerpt . '...';

			$hide = get_theme_mod( 'entries_lists_hide_continue_reading' );
			if ( ! $hide || is_customize_preview() ) {
				$classes      = cpschool_class( 'read-more-link', 'btn btn-secondary cpschool-read-more-link', true );
				$post_excerpt = $post_excerpt . '<div><a class="' . implode( ' ', $classes ) . '" href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . __(
					'Continue Reading',
					'cpschool'
				) . '</a></div>';
			}
		}
		return $post_excerpt;
	}
}
