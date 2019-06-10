<?php
/**
 * Sitemap - General
 *
 * @package    RankMath
 * @subpackage RankMath\Sitemap
 */

$cmb->add_field( array(
	'id'         => 'items_per_page',
	'type'       => 'text',
	'name'       => esc_html__( 'Links Per Sitemap', 'rank-math' ),
	'desc'       => esc_html__( 'Max number of links on each sitemap page.', 'rank-math' ),
	'default'    => '200',
	'attributes' => array( 'type' => 'number' ),
) );

$cmb->add_field( array(
	'id'      => 'include_images',
	'type'    => 'switch',
	'name'    => esc_html__( 'Images in Sitemaps', 'rank-math' ),
	'desc'    => esc_html__( 'Include reference to images from the post content in sitemaps. This helps search engines index the important images on your pages.', 'rank-math' ),
	'default' => 'on',
) );

$cmb->add_field( array(
	'id'      => 'include_featured_image',
	'type'    => 'switch',
	'name'    => esc_html__( 'Include Featured Images', 'rank-math' ),
	'desc'    => esc_html__( 'Include the Featured Image too, even if it does not appear directly in the post content.', 'rank-math' ),
	'default' => 'off',
	'dep'     => array( array( 'include_images', 'on' ) ),
) );

$cmb->add_field( array(
	'id'   => 'exclude_posts',
	'type' => 'text',
	'name' => esc_html__( 'Exclude Posts', 'rank-math' ),
	'desc' => esc_html__( 'Enter post IDs of posts you want to exclude from the sitemap, separated by commas. This option **applies** to all posts types including posts, pages, and custom post types.', 'rank-math' ),
) );

$cmb->add_field( array(
	'id'   => 'exclude_terms',
	'type' => 'text',
	'name' => esc_html__( 'Exclude Terms', 'rank-math' ),
	'desc' => esc_html__( 'Add term IDs, separated by comma. This option is applied for all taxonomies.', 'rank-math' ),
) );

$cmb->add_field( array(
	'id'      => 'ping_search_engines',
	'type'    => 'switch',
	'name'    => esc_html__( 'Ping Search Engines', 'rank-math' ),
	'desc'    => esc_html__( 'Automatically notify Google &amp; Bing when a sitemap gets updated.', 'rank-math' ),
	'default' => 'on',
) );
