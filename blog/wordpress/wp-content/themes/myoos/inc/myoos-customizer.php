<?php

function myoos_custom_customize_register($wp_customize) {
   
   // Sitewide General Settings
   $wp_customize->add_section( 'myoos_general_options' , array(
    'title'      => __('Sitewide General Options','myoos'),
    'priority'   => 30,
   ) );
   // Setting group for header
   $wp_customize->add_section( 'myoos_header_options' , array(
    'title'      => __('Logo Options','myoos'),
    'priority'   => 31,
   ) );
   
   $wp_customize->add_section( 'myoos_featured_options' , array(
    'title'      => __('Front Page Content Options','myoos'),
    'priority'   => 32,
   ) );
      
   // Setting group for social icons
   $wp_customize->add_section( 'myoos_social_options' , array(
    'title'      => __('Social Options','myoos'),
    'priority'   => 36,
   ) );
   
   $wp_customize->add_section( 'myoos_footer_options' , array(
    'title'      => __('Footer Options','myoos'),
    'priority'   => 37,
   ) );

/**
 * Lets begin adding our own settings and controls for this theme
 * Plus organize it in sequence in each setting group with a priority level
 */
		
	// Begin Home Posts Settings
	
	$wp_customize->add_setting(
    'myoos_single_thumb_visibility'
    );

    $wp_customize->add_control(
    'myoos_single_thumb_visibility',
    array(
        'type'     => 'checkbox',
        'label'    => __('Hide Thumbnails On Single Post?', 'myoos'),
        'section'  => 'myoos_general_options',
		'priority' => 2,
        )
    );
	
	$wp_customize->add_setting(
    'myoos_attachment_commentform_visibility'
    );

    $wp_customize->add_control(
    'myoos_attachment_commentform_visibility',
    array(
        'type'     => 'checkbox',
        'label'    => __('Hide Comment Form on the Attachment page', 'myoos'),
        'section'  => 'myoos_general_options',
		'priority' => 3,
        )
    );
	
	// === Begin The Logo Section === //
	
    //  Logo Image Upload
    $wp_customize->add_setting('header_logo_image', array(
        'default-image'  => get_template_directory_uri() . '/images/logo.png',
		'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'header_logo_image', array(
        'label'    => __('Header Logo Image', 'myoos'),
        'section'  => 'myoos_header_options',
		'priority' => 1,
        'settings' => 'header_logo_image',
    )));
	
	
	
	// Begin Front Page Content Section
	$wp_customize->add_setting(
    'myoos_featured_visibility'
    );

    $wp_customize->add_control(
    'myoos_featured_visibility',
    array(
        'type' => 'checkbox',
        'label' => __('Hide The Entire Featured Section?', 'myoos'),
        'section' => 'myoos_featured_options',
		'priority' => 1,
        )
    );
	
	$wp_customize->add_setting(
    'myoos_top_featured_visibility'
    );

    $wp_customize->add_control(
    'myoos_top_featured_visibility',
    array(
        'type' => 'checkbox',
        'label' => __('Hide The Top Featured Section Only?', 'myoos'),
        'section' => 'myoos_featured_options',
		'priority' => 2,
        )
    );
	
	$wp_customize->add_setting(
    'myoos_secondary_featured_visibility'
    );

    $wp_customize->add_control(
    'myoos_secondary_featured_visibility',
    array(
        'type' => 'checkbox',
        'label' => __('Hide Secondary Featured Section Only?', 'myoos'),
        'section' => 'myoos_featured_options',
		'priority' => 3,
        )
    );
	
	$wp_customize->add_setting(
    'myoos_home_content_visibility'
    );

    $wp_customize->add_control(
    'myoos_home_content_visibility',
    array(
        'type'     => 'checkbox',
        'label'    => __('Hide Page Content on front? Default is "Show" - This will also hide the sidebar', 'myoos'),
        'section'  => 'myoos_featured_options',
		'priority' => 4,
        )
    );
	
	$wp_customize->add_setting(
    'myoos_featured_first_banner_visibility'
    );

    $wp_customize->add_control(
    'myoos_featured_first_banner_visibility',
    array(
        'type' => 'checkbox',
        'label' => __('Hide Featured Banner On Main Featured Post? (Refresh required)', 'myoos'),
        'section' => 'myoos_featured_options',
		'priority' => 5,
        )
    );
	
	$wp_customize->add_setting(
    'myoos_featured_secondary_banner_visibility'
    );

    $wp_customize->add_control(
    'myoos_featured_secondary_banner_visibility',
    array(
        'type' => 'checkbox',
        'label' => __('Hide Featured Banner On Secondary Rows? (Refresh required)', 'myoos'),
        'section' => 'myoos_featured_options',
		'priority' => 6,
        )
    );
	
	$wp_customize->add_setting(
    'myoos_featured_number',
    array(
        'default' => '5',
    ));
	
	$wp_customize->add_control(
    'myoos_featured_number',
    array(
        'label' => __('Number of Featured posts i.e 9, 13, 17','myoos'),
        'section' => 'myoos_featured_options',
		'priority' => 7,
        'type' => 'text',
    ));
	
	// Featured Section Order By.
	$wp_customize->add_setting( 'myoos_featured_orderby', array(
		'default' => 'none',
	) );
	
	$wp_customize->add_control( 'myoos_featured_orderby', array(
    'label'   => __( 'Featured Content Order By', 'myoos' ),
    'section' => 'myoos_featured_options',
	'priority' => 8,
    'type'    => 'radio',
        'choices' => array(
            'none'             => __( 'Oldest First', 'myoos' ),
			'rand'             => __( 'Random Sticky Posts', 'myoos' ),
			'date'             => __( 'Order By Date - Newest First', 'myoos' ),
        ),
    ));
	    		
	// == Social Links Icons Section == //
    // Begin Header Social Icons	
	$wp_customize->add_setting(
    'myoos_social_visibility'
    );

    $wp_customize->add_control(
    'myoos_social_visibility',
    array(
        'type' => 'checkbox',
        'label' => __('Show Social Icons?','myoos'),
        'section' => 'myoos_social_options',
		'priority' => 10,
        )
    );
	$wp_customize->add_setting(
    'myoos_facebook_url',
    array(
        'default' => '',
    ));
	
	$wp_customize->add_control(
    'myoos_facebook_url',
    array(
        'label' => __('Facebook URL','myoos'),
        'section' => 'myoos_social_options',
		'priority' => 11,
        'type' => 'text',
    ));
	
	$wp_customize->add_setting(
    'myoos_gplus_url',
    array(
        'default' => '',
    ));
	
	$wp_customize->add_control(
    'myoos_gplus_url',
    array(
        'label' => __('Google+ URL','myoos'),
        'section' => 'myoos_social_options',
		'priority' => 12,
        'type' => 'text',
    ));
	
	$wp_customize->add_setting(
    'myoos_twitter_url',
    array(
        'default' => '',
    ));
	
	$wp_customize->add_control(
    'myoos_twitter_url',
    array(
        'label' => __('Twitter URL','myoos'),
        'section' => 'myoos_social_options',
		'priority' => 13,
        'type' => 'text',
    ));
	
	$wp_customize->add_setting(
    'myoos_pinterest_url',
    array(
        'default' => '',
    ));
	
	$wp_customize->add_control(
    'myoos_pinterest_url',
    array(
        'label' => __('Pinterest URL','myoos'),
        'section' => 'myoos_social_options',
		'priority' => 14,
        'type' => 'text',
    ));
	
	$wp_customize->add_setting(
    'myoos_linkedin_url',
    array(
        'default' => '',
    ));
	
	$wp_customize->add_control(
    'myoos_linkedin_url',
    array(
        'label' => __('LinkedIn URL','myoos'),
        'section' => 'myoos_social_options',
		'priority' => 15,
        'type' => 'text',
    ));
	
	$wp_customize->add_setting(
    'myoos_youtube_url',
    array(
        'default' => '',
    ));
}
add_action( 'customize_register', 'myoos_custom_customize_register' );

if ( get_theme_mod( 'myoos_featured_first_banner_visibility' ) != 0 ) { 
function myoos_featured_first_custom_css() {
?>             
<style>
.featured-content .has-post-thumbnail .entry-thumbnail:before {
   display: none;
}
</style>
<?php }
 
} else {
function myoos_featured_first_custom_css() { ?>             
<style>
.featured-content .has-post-thumbnail .entry-thumbnail:before {
  background: url( get_template_directory() . '/images/featured.png' ) center center no-repeat;
  z-index: 999;
}
</style>
<?php }
}
add_action('wp_head', 'myoos_featured_first_custom_css');

if ( get_theme_mod( 'myoos_featured_secondary_banner_visibility' ) != 0 ) { 
function myoos_featured_secondary_custom_css() {
?>             
<style>
.featured-content-secondary .has-post-thumbnail .entry-thumbnail:before {
   display: none;
}
</style>
<?php }
 
} else {
function myoos_featured_secondary_custom_css() { ?>             
<style>
.featured-content-secondary .has-post-thumbnail .entry-thumbnail:before {
  background: url( get_template_directory() . '/images/featured.png' ) center center no-repeat;
  z-index: 999;
}
</style>
<?php }
}
add_action('wp_head', 'myoos_featured_secondary_custom_css');