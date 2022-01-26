<?php
/**
 * IndexNow Settings.
 *
 * @since      1.0.56
 * @package    RankMath
 * @subpackage RankMath\Settings
 */

use RankMath\Helper;

defined( 'ABSPATH' ) || exit;

$cmb->add_field(
	[
		'id'      => 'bing_post_types',
		'type'    => 'multicheck',
		'name'    => esc_html__( 'Auto-Submit Post Types', 'rank-math' ),
		'desc'    => esc_html__( 'Submit posts from these post types automatically to the IndexNow API when a post is published, updated, or trashed.', 'rank-math' ),
		'options' => Helper::choices_post_types(),
		'default' => [ 'post', 'page' ],
	]
);

$cmb->add_field(
	[
		'id'   => 'indexnow_api_key',
		'type' => 'hidden',
	]
);
