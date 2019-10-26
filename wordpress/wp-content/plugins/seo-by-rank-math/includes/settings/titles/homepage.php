<?php
/**
 * The homepage/frontpage settings.
 *
 * @package    RankMath
 * @subpackage RankMath\Settings
 */

use RankMath\Helper;

if ( 'page' === get_option( 'show_on_front' ) ) {
	return;
}

$cmb->add_field([
	'id'              => 'homepage_title',
	'type'            => 'text',
	'name'            => esc_html__( 'Homepage Title', 'rank-math' ),
	'desc'            => esc_html__( 'Homepage title tag.', 'rank-math' ),
	'classes'         => 'rank-math-supports-variables rank-math-title',
	'default'         => '%sitename% %page% %sep% %sitedesc%',
	'sanitization_cb' => false,
]);

$cmb->add_field([
	'id'              => 'homepage_description',
	'type'            => 'textarea_small',
	'name'            => esc_html__( 'Homepage Meta Description', 'rank-math' ),
	'desc'            => esc_html__( 'Homepage meta description.', 'rank-math' ),
	'classes'         => 'rank-math-supports-variables rank-math-description',
	'sanitization_cb' => true,
	'attributes'      => [
		'class'             => 'cmb2_textarea wp-exclude-emoji',
		'data-gramm_editor' => 'false',
	],
]);

$cmb->add_field([
	'id'      => 'homepage_custom_robots',
	'type'    => 'switch',
	'name'    => esc_html__( 'Homepage Robots Meta', 'rank-math' ),
	'desc'    => wp_kses_post( __( 'Select custom robots meta for homepage, such as <code>nofollow</code>, <code>noarchive</code>, etc. Otherwise the default meta will be used, as set in the Global Meta tab.', 'rank-math' ) ),
	'options' => [
		'off' => esc_html__( 'Default', 'rank-math' ),
		'on'  => esc_html__( 'Custom', 'rank-math' ),
	],
	'default' => 'off',
]);

$cmb->add_field([
	'id'                => 'homepage_robots',
	'type'              => 'multicheck',
	'name'              => esc_html__( 'Homepage Robots Meta', 'rank-math' ),
	'desc'              => esc_html__( 'Custom values for robots meta tag on homepage.', 'rank-math' ),
	'options'           => Helper::choices_robots(),
	'select_all_button' => false,
	'dep'               => [ [ 'homepage_custom_robots', 'on' ] ],
]);

$cmb->add_field([
	'id'              => 'homepage_advanced_robots',
	'type'            => 'advanced_robots',
	'name'            => esc_html__( 'Homepage Advanced Robots', 'rank-math' ),
	'sanitization_cb' => [ '\RankMath\CMB2', 'sanitize_advanced_robots' ],
	'dep'             => [ [ 'homepage_custom_robots', 'on' ] ],
]);

$cmb->add_field([
	'id'   => 'homepage_facebook_title',
	'type' => 'text',
	'name' => esc_html__( 'Homepage Title for Facebook', 'rank-math' ),
	'desc' => esc_html__( 'Title of your site when shared on Facebook, Twitter and other social networks.', 'rank-math' ),
]);

$cmb->add_field([
	'id'   => 'homepage_facebook_description',
	'type' => 'textarea_small',
	'name' => esc_html__( 'Homepage Description for Facebook', 'rank-math' ),
	'desc' => esc_html__( 'Description of your site when shared on Facebook, Twitter and other social networks.', 'rank-math' ),
]);

$cmb->add_field([
	'id'   => 'homepage_facebook_image',
	'type' => 'file',
	'name' => esc_html__( 'Homepage Thumbnail for Facebook', 'rank-math' ),
	'desc' => esc_html__( 'Image displayed when your homepage is shared on Facebook and other social networks. Use images that are at least 1200 x 630 pixels for the best display on high resolution devices.', 'rank-math' ),
]);
