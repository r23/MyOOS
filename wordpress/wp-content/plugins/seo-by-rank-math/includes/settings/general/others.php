<?php
/**
 * The misc settings.
 *
 * @package    RankMath
 * @subpackage RankMath\Settings
 */

use RankMath\KB;
use RankMath\Helper;

$cmb->add_field([
	'id'      => 'usage_tracking',
	'type'    => 'switch',
	'name'    => esc_html__( 'Usage Tracking', 'rank-math' ),
	'desc'    => esc_html__( 'Help make Rank Math even more powerful by allowing us to collect non-sensitive diagnostic data and usage information.', 'rank-math' ) . ' <a href="' . KB::get( 'rm-privacy' ) . '" target="_blank">' . esc_html__( 'Find out more.', 'rank-math' ) . '</a>',
	'default' => 'on',
]);

$cmb->add_field([
	'id'      => 'frontend_seo_score',
	'type'    => 'switch',
	'name'    => esc_html__( 'Show SEO Score', 'rank-math' ),
	'desc'    => sprintf(
		/* translators: %s is the shortcode */
		esc_html__( 'Show the calculated SEO Score as a badge on the front end for selected post types. It can be disabled for specific posts.', 'rank-math' ),
		'<code>[rank_math_seo_score]</code>'
	),
	'default' => 'off',
]);

$cmb->add_field([
	'id'         => 'frontend_seo_score_post_types',
	'type'       => 'multicheck',
	'name'       => esc_html__( 'SEO Score Post Types', 'rank-math' ),
	'options'    => Helper::choices_post_types(),
	'default_cb' => '\\RankMath\\Frontend_SEO_Score::post_types_field_default',
	'dep'        => [ [ 'frontend_seo_score', 'on' ] ],
]);

$cmb->add_field([
	'id'      => 'frontend_seo_score_template',
	'type'    => 'switch',
	'name'    => esc_html__( 'SEO Score Template', 'rank-math' ),
	'desc'    => sprintf( esc_html__( 'Change the styling for the front end SEO score badge.', 'rank-math' ), '<code>nofollow</code>' ),
	'options' => [
		'circle' => esc_html__( 'Circle', 'rank-math' ),
		'square' => esc_html__( 'Square', 'rank-math' ),
	],
	'default' => 'circle',
	'dep'     => [ [ 'frontend_seo_score', 'on' ] ],
]);

$cmb->add_field([
	'id'      => 'frontend_seo_score_position',
	'type'    => 'switch',
	'name'    => esc_html__( 'SEO Score Position', 'rank-math' ),
	'desc'    => sprintf(
		/* translators: %1$s is the shortcode */
		esc_html__( 'Display the badges automatically, or insert the %1$s shortcode in your posts and the %2$s template tag in your theme template files.', 'rank-math' ),
		'<code>[rank_math_seo_score]</code>',
		'<code>&lt;?php&nbsp;rank_math_the_seo_score();&nbsp;?&gt;</code>'
	),
	'classes' => 'nob',
	'default' => 'bottom',
	'options' => [
		'bottom' => esc_html__( 'Below Content', 'rank-math' ),
		'top'    => esc_html__( 'Above Content', 'rank-math' ),
		'both'   => esc_html__( 'Above & Below Content', 'rank-math' ),
		'custom' => esc_html__( 'Custom (use shortcode)', 'rank-math' ),
	],
	'dep'     => [ [ 'frontend_seo_score', 'on' ] ],
]);

$cmb->add_field([
	'id'      => 'support_rank_math',
	'type'    => 'switch',
	'name'    => esc_html__( 'Support Us with a Link', 'rank-math' ),
	/* Translators: %s is th word "nofollow" inside a HTML tag */
	'desc'    => sprintf( esc_html__( 'If you are showing the SEO scores on the front end, this option will insert a %s backlink to RankMath.com to show your support.', 'rank-math' ), '<code>nofollow</code>' ),
	'default' => 'on',
	'dep'     => [ [ 'frontend_seo_score', 'on' ] ],
]);

$cmb->add_field([
	'id'              => 'rss_before_content',
	'type'            => 'textarea_small',
	'name'            => esc_html__( 'RSS Before Content', 'rank-math' ),
	'desc'            => esc_html__( 'Add content before each post in your site feeds.', 'rank-math' ),
	'sanitization_cb' => false,
]);

$cmb->add_field([
	'id'              => 'rss_after_content',
	'type'            => 'textarea_small',
	'name'            => esc_html__( 'RSS After Content', 'rank-math' ),
	'desc'            => esc_html__( 'Add content after each post in your site feeds.', 'rank-math' ),
	'sanitization_cb' => false,
]);

$cmb->add_field([
	'id'              => 'rss_after_content',
	'type'            => 'textarea_small',
	'name'            => esc_html__( 'RSS After Content', 'rank-math' ),
	'desc'            => esc_html__( 'Add content after each post in your site feeds.', 'rank-math' ),
	'classes'         => 'nob',
	'sanitization_cb' => false,
]);

$cmb->add_field([
	'id'   => 'rank_math_serp_preview',
	'type' => 'raw',
	'file' => rank_math()->includes_dir() . 'settings/general/rss-vars-table.php',
]);
