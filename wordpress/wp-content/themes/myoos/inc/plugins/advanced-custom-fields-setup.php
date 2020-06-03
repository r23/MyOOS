<?php
/**
 * Setup things related to Advanced Custom Fields Plugin
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cpschool_acf_setup' ) ) {
	// Load the ACF and set it up if it still does not exists.
	add_action( 'after_setup_theme', 'cpschool_acf_setup' );
	function cpschool_acf_setup() {
		if ( ! class_exists( 'ACF' ) ) {

			// Load the plugin.
			include_once( 'advanced-custom-fields/acf.php' );

			// Customize the url setting to fix incorrect asset URLs in ACF.
			add_filter( 'acf/settings/url', 'cpschool_acf_settings_url' );

			// Disables ACF menu item.
			add_filter( 'acf/settings/show_admin', '__return_false' );
		}
	}
}

if ( ! function_exists( 'cpschool_acf_settings_url' ) ) {
	function cpschool_acf_settings_url( $url ) {
		return get_template_directory_uri() . '/inc/plugins/advanced-custom-fields/';
	}
}

if ( ! function_exists( 'cpschool_acf_register_settings' ) ) {
	// Registers all custom ACF settings.
	add_action( 'after_setup_theme', 'cpschool_acf_register_settings' );
	function cpschool_acf_register_settings() {

		if ( function_exists( 'acf_add_local_field_group' ) ) :

			acf_add_local_field_group(
				array(
					'key'                   => 'group_5e33ff2033e95',
					'title'                 => 'Customizations',
					'fields'                => array(
						array(
							'key'               => 'field_5e33ffb3dd746',
							'label'             => 'Overwrite Sidebars Visibility',
							'name'              => 'cps_sidebars_custom',
							'type'              => 'true_false',
							'instructions'      => 'Enable to overwrite sidebars settings configured in "Customizer".',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'message'           => '',
							'default_value'     => 0,
							'ui'                => 1,
							'ui_on_text'        => '',
							'ui_off_text'       => '',
						),
						array(
							'key'               => 'field_5e340051dd747',
							'label'             => 'Sidebars',
							'name'              => 'cps_sidebars',
							'type'              => 'checkbox',
							'instructions'      => 'Choose which sidebars should be displayed.',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5e33ffb3dd746',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'sidebar-left'  => 'Sidebar Left',
								'sidebar-right' => 'Sidebar Right',
							),
							'allow_custom'      => 0,
							'default_value'     => array(),
							'layout'            => 'vertical',
							'toggle'            => 0,
							'return_format'     => 'value',
							'save_custom'       => 0,
						),
						array(
							'key'               => 'field_5e37f96eed963',
							'label'             => 'Disable Page Title/Hero Area',
							'name'              => 'cps_hero_title_disable',
							'type'              => 'true_false',
							'instructions'      => 'Removes page title and hero area (if enabled).',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'message'           => '',
							'default_value'     => 0,
							'ui'                => 1,
							'ui_on_text'        => '',
							'ui_off_text'       => '',
						),
						array(
							'key'               => 'field_5e38003fee736',
							'label'             => 'Custom Top Margin',
							'name'              => 'cps_top_margin',
							'type'              => 'select',
							'instructions'      => 'Select to overwrite default spacing between header and content.',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'remove' => 'Remove Spacing',
							),
							'default_value'     => array(),
							'allow_null'        => 1,
							'multiple'          => 0,
							'ui'                => 0,
							'return_format'     => 'value',
							'ajax'              => 0,
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_5e417f79c6d7b',
							'label'             => 'Pull Content Under Header',
							'name'              => 'cps_content_pull_under',
							'type'              => 'true_false',
							'instructions'      => 'Pull content under header so it can be used instead of "Hero Area".',
							'required'          => 0,
							'conditional_logic' => array(
								array(
									array(
										'field'    => 'field_5e38003fee736',
										'operator' => '==',
										'value'    => 'remove',
									),
								),
								array(
									array(
										'field'    => 'field_5e37f96eed963',
										'operator' => '==',
										'value'    => '1',
									),
								),
							),
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'message'           => '',
							'default_value'     => 0,
							'ui'                => 1,
							'ui_on_text'        => '',
							'ui_off_text'       => '',
						),
						array(
							'key'               => 'field_5e417eb8615c7',
							'label'             => 'Custom Bottom Margin',
							'name'              => 'cps_bottom_margin',
							'type'              => 'select',
							'instructions'      => 'Select to overwrite default spacing between footer and content.',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'remove' => 'Remove Spacing',
							),
							'default_value'     => array(),
							'allow_null'        => 1,
							'multiple'          => 0,
							'ui'                => 0,
							'return_format'     => 'value',
							'ajax'              => 0,
							'placeholder'       => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'post',
							),
						),
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'page',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'side',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'field',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);

			acf_add_local_field_group(
				array(
					'key'                   => 'group_5e3d490c7cd53',
					'title'                 => 'Menu settings',
					'fields'                => array(
						array(
							'key'               => 'field_5e3d56a4add35',
							'label'             => '',
							'name'              => 'cps_custom_styling',
							'type'              => 'select',
							'instructions'      => 'Custom Menu Item Styling',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'btn'    => 'Button',
								'btn-hl' => 'Highlighted Button',
							),
							'default_value'     => array(),
							'allow_null'        => 1,
							'multiple'          => 0,
							'ui'                => 0,
							'return_format'     => 'value',
							'ajax'              => 0,
							'placeholder'       => '',
						),
						array(
							'key'               => 'field_5e3d491d90b6e',
							'label'             => '',
							'name'              => 'cps_custom_action',
							'type'              => 'select',
							'instructions'      => 'Custom Action On Click',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'choices'           => array(
								'modal-search'        => 'Open Search Modal',
								'modal-slide-in-menu' => 'Open Slide In Menu',
								'dropdown-translate'  => 'Open Translate Dropdown',
							),
							'default_value'     => array(),
							'allow_null'        => 1,
							'multiple'          => 0,
							'ui'                => 0,
							'return_format'     => 'value',
							'ajax'              => 0,
							'placeholder'       => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'nav_menu_item',
								'operator' => '==',
								'value'    => 'all',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'acf_after_title',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);

		endif;

	}
}
