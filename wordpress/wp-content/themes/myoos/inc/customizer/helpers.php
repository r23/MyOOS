<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cpschool_get_customizer_fonts_options' ) ) {
	// Generates array with options used with typography control so all font lists have the same choises.
	function cpschool_get_customizer_fonts_options($context = false, $default = false) {
		if($default) {
			$default = array('inherit' => esc_html__( 'Default font', 'myoos' ));
		}
		else {
			$default = array();
		}
		if($context == 'header') {
			return $default + array( 
				'public_sans' => 'Public Sans',
				'amstelvar' => 'Amstelvar',
				'commissioner' => 'Commissioner',
				'epilogue' => 'Epilogue',
				'gelasio' => 'Gelasio',
				'hepta_slab' => 'Hepta Slab',
				'inter' => 'Inter',
				'lexend' => 'Lexend',
				'manrope' => 'Manrope',
				'merriweather' => 'Merriweather',
				'mohave' => 'Mohave',
				'petrona' => 'Petrona',
				'russolo' => 'Russolo',
				'space_grotesk' => 'Space Grotesk',
				//'urbanist' => 'Urbanist',
			);
		}
		else {
			return $default + array( 
				'public_sans' => 'Public Sans',
				'amstelvar' => 'Amstelvar',
				'commissioner' => 'Commissioner',
				//'epilogue' => 'Epilogue',
				//'gelasio' => 'Gelasio',
				'hepta_slab' => 'Hepta Slab',
				'inter' => 'Inter',
				'lexend' => 'Lexend',
				'manrope' => 'Manrope',
				'merriweather' => 'Merriweather',
				//'mohave' => 'Mohave',
				'petrona' => 'Petrona',
				//'russolo' => 'Russolo',
				//'space_grotesk' => 'Space Grotesk',
				//'urbanist' => 'Urbanist',
			);
		}
	}
}

if ( ! function_exists( 'cpschool_generate_customizer_color_settings' ) ) {
	// Generates customizer options used to generate additional colors from source colors.
	function cpschool_generate_customizer_color_settings($prefix) {
		$settings_to_generate = array(
			'contrast', 
			'accent', 
			'accent-a', 
			'accent-contrast', 
			'accent-hl', 
			'accent-hl-a', 
			'accent-hl-contrast'
		);

		foreach( $settings_to_generate as $setting ) {
			$name = $prefix.'-'.$setting;
			$css_name = '--'.$name;
			$setting_name = str_replace( '-', '_', $name );

			Kirki::add_field( 'myoos', [
				'type'        => 'hidden',
				'settings'    => $setting_name,
				'section'     => 'colors',
				'transport'   => 'auto',
				'output' => array( 
					array(
						'element'  => ':root',
						'property' => $css_name,
						'context' => array( 'editor', 'front' )
					),
				)
			] );
		}
	}
}

// Generate customizer options that are common for all sections under "Content" panel.
if ( ! function_exists( 'cpschool_generate_content_common_settings' ) ) {
	function cpschool_generate_content_common_settings( $section, $post_type, $options = array() ) {
		// Sidebar settings.
		if( isset( $options['sidebars'] ) ) {
			Kirki::add_field( 'myoos', [
				'type'        => 'multicheck',
				'settings'    => $section.'_sidebars',
				'label'       => esc_html__( 'Sidebars', 'myoos' ),
				'description' => esc_html__( 'Choose which sidebars should be displayed on this page type. Empty sidebars won\'t be visible even when enabled.', 'myoos' ),
				'section'     => $section,
				'default' =>  $options['sidebars'],
				'choices'     => [
					'sidebar-left' => esc_html__( 'Left Sidebar', 'myoos' ),
					'sidebar-right' => esc_html__( 'Right Sidebar', 'myoos' ),
				],
			] );
		}

		// Meta data settings.
		if( isset( $options['meta'] ) ) {
			$choices = array();
			if( post_type_supports( $post_type, 'author' ) ) {
				$choices['author'] = esc_html__( 'Author', 'myoos' );
				$choices['author-avatar'] = esc_html__( 'Author Avatar', 'myoos' );
			}

			$choices['post-date'] = esc_html__( 'Publication Date', 'myoos' );
			$choices['post-modified'] = esc_html__( 'Latest Modification Date', 'myoos' );

			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			foreach( $taxonomies as $taxonomy ) {
				if( $taxonomy->show_ui && $taxonomy->public ) {
					$choices['tax-'.$taxonomy->name] = $taxonomy->label;
				}
			}

			if( post_type_supports( $post_type, 'comments' ) ) {
				$choices['comments'] = esc_html__( 'Comments', 'myoos' );
			}

			if( $post_type == 'post' ) {
				$choices['sticky'] = esc_html__( 'Sticky', 'myoos' );
			} 
	
			Kirki::add_field( 'myoos', [
				'type'        => 'multicheck',
				'settings'    => $section.'_meta',
				'label'       => esc_html__( 'Meta', 'myoos' ),
				'description' => esc_html__( 'Choose which meta data elements to display.', 'myoos' ),
				'section'     => $section,
				'default' =>  $options['meta'],
				'choices'     => $choices,
			] );
		}

		// Post Navigation Settings.
		if( isset( $options['navigation'] ) ) {
			Kirki::add_field( 'myoos', array(
				'type'        => 'toggle',
				'settings'    => $section.'_navigation',
				'label'       => __( 'Show Navigation', 'myoos' ),
				'description' => esc_html__( 'Shows previous and next button for easy navigation.', 'myoos' ),
				'section'     => $section,
				'default' => $options['navigation'],
			) );
		}
    }
}