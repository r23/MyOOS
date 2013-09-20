<?php
if ( !defined( 'MYOOS_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

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
        'default-image'  => MYOOS_THEME_URL . '/images/logo.png',
		'type'           => 'theme_mod',
        'capability'     => 'edit_theme_options',
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'header_logo_image', array(
        'label'    => __('Header Logo Image', 'myoos'),
        'section'  => 'myoos_header_options',
		'priority' => 1,
        'settings' => 'header_logo_image',
    )));
	
		    		
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

