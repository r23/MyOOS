<?php
/**
 * Check and setup theme's default settings
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cpschool_setup_theme_settings' ) ) {
	function cpschool_setup_theme_settings() {
		$settings_ver = get_option( 'cpschool_theme_settings_ver', false );
		// Sets the default theme options.
		if ( ! $settings_ver ) {
			$default_options = array(
				'color_bg'                        => '#ffffff',
				'color_bg_contrast'               => '#000000',
				'color_bg_accent_a'               => 'rgba(180,17,17,0.5)',
				'color_bg_accent_contrast'        => '#ffffff',
				'color_bg_accent_hl_a'            => 'rgba(33,104,144,0.5)',
				'color_bg_accent_hl_contrast'     => '#ffffff',
				'color_accent_source'             => '#b41111',
				'color_bg_accent'                 => '#b41111',
				'color_bg_accent_hl'              => '#216890',
				'color_bg_alt_contrast'           => '#000000',
				'color_bg_alt_accent'             => '#b11010',
				'color_bg_alt_accent_a'           => 'rgba(177,16,16,0.5)',
				'color_bg_alt_accent_contrast'    => '#ffffff',
				'color_bg_alt_accent_hl'          => '#1d5c80',
				'color_bg_alt_accent_hl_a'        => 'rgba(29,92,128,0.5)',
				'color_bg_alt_accent_hl_contrast' => '#ffffff',
				'color_accent_hl_source'          => '#3a95c9',
				'color_bg_alt'                    => '#eeeeec',
				'color_bg_alt_accent_source'      => '',
			);

			foreach ( $default_options as $name => $value ) {
				$current_value = get_theme_mod( $name );
				if ( '' == $current_value ) {
					set_theme_mod( $name, $value );
				}
			}

			update_option( 'cpschool_theme_settings_ver', 1 );
		}
	}
}

if ( ! function_exists( 'cpschool_set_body_theme_classes' ) ) {

	add_filter( 'body_class', 'cpschool_set_body_theme_classes', 10, 2 );
	/**
	 * Sets body classes based on customizer settings.
	 */
	function cpschool_set_body_theme_classes( $classes ) {
		$classes = cpschool_set_theme_classes( $classes, 'body' );

		return $classes;
	}
}

if ( ! function_exists( 'cpschool_set_theme_classes' ) ) {
	add_filter( 'cpschool_class', 'cpschool_set_theme_classes', 10, 2 );

	/**
	 * Hooks up all classes customizer is setting to HTML elements that make use of "cpschool_class" function.
	 *
	 * @param [type] $classes
	 * @param [type] $context
	 * @return void
	 */
	function cpschool_set_theme_classes( $classes, $context ) {
		global $wp_query;

		$context_to_option_map = array(
			//'header_main_logo_position' => 'navbar-main',
			'header_main_stick'          => 'navbar-main-wrapper',
			'header_main_bg_transparent' => 'body',
			'hero_main_style'            => 'hero-main',
			'hero_main_content_align'    => 'hero-main',
			'entries_lists_enable_bg'    => 'entries-row',
			'entries_lists_row_count'    => 'entry-col',
		);

		$fields = Kirki::$fields;
		foreach ( $fields as $field ) {
			if ( isset( $field['js_vars'] ) && ! empty( $field['js_vars'] ) ) {
				$theme_mod_value = null;
				$count           = 0;

				foreach ( $field['js_vars'] as $js_var ) {
					if ( ! isset( $js_var['function'] ) || $js_var['function'] != 'toggleClass' ) {
						continue;
					}

					if ( ! empty( $js_var['customizer_only'] ) && ! is_customize_preview() ) {
						continue;
					}

					if ( ! empty( $js_var['context_check'] ) ) {
						$function_name = $js_var['context_check'];
						// Checks if we are looking for false or true.
						if ( substr( $function_name, 0, 1 ) == '!' ) {
							$function_name      = substr( $function_name, 1 );
							$function_condition = false;
						} else {
							$function_condition = true;
						}
						if ( method_exists( $wp_query, $function_name ) ) {
							if ( $function_condition && ! $wp_query->$function_name() ) {
								continue;
							} elseif ( ! $function_condition && $wp_query->$function_name() ) {
								continue;
							}
						}
					}

					if ( ! empty( $js_var['context'] ) ) {
						$js_var_context = $js_var['context'];
						if ( ! is_array( $js_var_context ) ) {
							$js_var_context = array( $js_var_context );
						}
					} else {
						if ( ! isset( $context_to_option_map[ $field['settings'] ] ) ) {
							continue;
						}

						$js_var_context = array( $context_to_option_map[ $field['settings'] ] );
					}

					if ( ! in_array( $context, $js_var_context ) ) {
						continue;
					}

					$count ++;

					$values = $js_var['value'];
					if ( ! is_array( $values ) ) {
						$values = array( $values );
					}

					$class_values = $js_var['class'];
					if ( ! is_array( $class_values ) ) {
						$class_values = array( $class_values );
					}

					if ( $theme_mod_value === null ) {
						$theme_mod_default = null;
						if ( isset( $field['default'] ) ) {
							$theme_mod_default = $field['default'];
						}
						$theme_mod_value = get_theme_mod( $field['settings'], $theme_mod_default );
					}

					if ( in_array( $theme_mod_value, $values ) ) {
						$class_count = 0;

						foreach ( $class_values as $class_value ) {
							$count ++;
							$classes[ $field['settings'] . '-' . $count . '-' . $class_count ] = $class_value;
						}
					}
				}
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'cpschool_set_theme_mods_defaults' ) ) {
	add_action( 'init', 'cpschool_set_theme_mods_defaults', 10, 2 );

	/**
	 * Takes default from customizer options and sets them for theme mods
	 *
	 * @return void
	 */
	function cpschool_set_theme_mods_defaults() {
		$theme_slug = get_option( 'stylesheet' );

		add_filter(
			"option_theme_mods_{$theme_slug}",
			function( $value ) {
				$fields = Kirki::$fields;
				foreach ( $fields as $field ) {
					if ( isset( $field['settings'] ) && ! isset( $value[ $field['settings'] ] ) && isset( $field['default'] ) ) {
						$value[ $field['settings'] ] = $field['default'];
					}
				}

				return $value;
			}
		);
	}
}
