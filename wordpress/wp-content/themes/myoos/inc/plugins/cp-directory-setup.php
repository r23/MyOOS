<?php

if ( ! function_exists( 'cpschool_cp_directory_setup' ) ) {
	// Load CP Directory and sets up the basics.
	add_action( 'after_setup_theme', 'cpschool_cp_directory_setup' );
	function cpschool_cp_directory_setup() {
		if ( ! class_exists( 'CPDirectory' ) ) {
			// Load the plugin.
			include_once( 'cp-directory/cp-directory.php' );
		}

		register_post_type(
			'cp_school_directory',
			array(
				'label'     => __( 'Directory', 'cpschool' ),
				'public'    => true,
				'supports'  => array( 'thumbnail', 'title', 'editor' ),
				'menu_icon' => 'dashicons-index-card',
			)
		);
		register_taxonomy(
			'cp_directory_category',
			'cp_school_directory',
			array(
				'label'        => __( 'Categories', 'cpschool' ),
				'public'       => true,
				'hierarchical' => true,
				'show_in_rest' => true,
			)
		);
		add_filter(
			'cp_dir_sources',
			function( $sources ) {
				$sources[] = 'cp_school_directory';
				return $sources;
			}
		);
		add_filter(
			'cpschool_post_meta_disallowed_post_types',
			function( $disallowed_post_types ) {
				$disallowed_post_types[] = 'cp_school_directory';

				return $disallowed_post_types;
			}
		);
		add_filter(
			'cpschool_disallowed_post_types_for_post_nav',
			function( $disallowed_post_types ) {
				$disallowed_post_types[] = 'cp_school_directory';

				return $disallowed_post_types;
			}
		);
		add_filter(
			'cp_dir_link_class',
			function( $class ) {
				if ( $class ) {
					$class .= ' ';
				}
				$class .= 'btn btn-secondary';

				return $class;
			}
		);

		// Sets default custom fields.
		if ( function_exists( 'acf_add_local_field_group' ) ) :

			acf_add_local_field_group(
				array(
					'key'                   => 'group_5e7c8869bb8e2',
					'title'                 => 'Entry Details',
					'fields'                => array(
						array(
							'key'               => 'field_5e7c88725153f',
							'label'             => 'Date',
							'name'              => 'cp_dir_date',
							'type'              => 'date_picker',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'display_format'    => 'F j, Y',
							'return_format'     => 'F j, Y',
							'first_day'         => 1,
						),
						array(
							'key'               => 'field_5e7c891651540',
							'label'             => 'Phone',
							'name'              => 'cp_dir_phone',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_5e7c894151541',
							'label'             => 'Email',
							'name'              => 'cp_dir_email',
							'type'              => 'email',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'post_type',
								'operator' => '==',
								'value'    => 'cp_school_directory',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
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

