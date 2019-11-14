<?php
/**
 * Metabox - Rich Snippet Tab
 *
 * @package    RankMath
 * @subpackage RankMath\RichSnippet
 */

use RankMath\Helper;
use RankMath\KB;
use MyThemeShop\Helpers\WordPress;

if ( ! Helper::has_cap( 'onpage_snippet' ) ) {
	return;
}

$post_type = WordPress::get_post_type();

if ( ( class_exists( 'WooCommerce' ) && 'product' === $post_type ) || ( class_exists( 'Easy_Digital_Downloads' ) && 'download' === $post_type ) ) {

	$cmb->add_field([
		'id'      => 'rank_math_woocommerce_notice',
		'type'    => 'notice',
		'what'    => 'info',
		'content' => '<span class="dashicons dashicons-yes"></span> ' . esc_html__( 'Rank Math automatically inserts additional Rich Snippet meta data for WooCommerce products. You can set the Rich Snippet Type to "None" to disable this feature and just use the default data added by WooCommerce.', 'rank-math' ),
	]);

	$cmb->add_field([
		'id'      => 'rank_math_rich_snippet',
		'type'    => 'radio_inline',
		'name'    => esc_html__( 'Rich Snippet Type', 'rank-math' ),
		/* translators: link to title setting screen */
		'desc'    => sprintf( wp_kses_post( __( 'Rich Snippets help you stand out in SERPs. <a href="%s" target="_blank">Learn more</a>.', 'rank-math' ) ), KB::get( 'rich-snippets' ) ),
		'options' => [
			'off'     => esc_html__( 'None', 'rank-math' ),
			'product' => esc_html__( 'Product', 'rank-math' ),
		],
		'default' => Helper::get_settings( "titles.pt_{$post_type}_default_rich_snippet" ),
	]);

	return;
}

$cmb->add_field([
	'id'      => 'rank_math_rich_snippet',
	'type'    => 'select',
	'name'    => esc_html__( 'Rich Snippet Type', 'rank-math' ),
	/* translators: link to title setting screen */
	'desc'    => sprintf( wp_kses_post( __( 'Rich Snippets help you stand out in SERPs. <a href="%s" target="_blank">Learn more</a>.', 'rank-math' ) ), KB::get( 'rich-snippets' ) ),
	'options' => Helper::choices_rich_snippet_types( esc_html__( 'None', 'rank-math' ) ),
	'default' => Helper::get_settings( "titles.pt_{$post_type}_default_rich_snippet" ),
]);

// Common fields.
$cmb->add_field([
	'id'      => 'rank_math_snippet_location',
	'name'    => esc_html__( 'Review Location', 'rank-math' ),
	'desc'    => esc_html__( 'The review or rating must be displayed on the page to comply with Google\'s Rich Snippet guidelines.', 'rank-math' ),
	'type'    => 'select',
	'dep'     => [ [ 'rank_math_rich_snippet', 'book,course,event,product,recipe,software', '=' ] ],
	'classes' => 'nob',
	'default' => 'bottom',
	'options' => [
		'bottom' => esc_html__( 'Below Content', 'rank-math' ),
		'top'    => esc_html__( 'Above Content', 'rank-math' ),
		'both'   => esc_html__( 'Above & Below Content', 'rank-math' ),
		'custom' => esc_html__( 'Custom (use shortcode)', 'rank-math' ),
	],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_shortcode',
	'name'       => esc_html__( 'Shortcode', 'rank-math' ),
	'type'       => 'text',
	'desc'       => esc_html__( 'Copy & paste this shortcode in the content.', 'rank-math' ),
	'dep'        => [
		'relation' => 'or',
		[ 'rank_math_rich_snippet', 'off,article,review,book,course,event,product,recipe,software', '!=' ],
		[ 'rank_math_snippet_location', 'custom' ],
	],
	'attributes' => [
		'readonly' => 'readonly',
		'value'    => '[rank_math_rich_snippet]',
	],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_name',
	'type'       => 'text',
	'name'       => esc_html__( 'Headline', 'rank-math' ),
	'dep'        => [ [ 'rank_math_rich_snippet', 'off', '!=' ] ],
	'attributes' => [ 'placeholder' => Helper::get_settings( "titles.pt_{$post_type}_default_snippet_name", '' ) ],
	'classes'    => 'rank-math-supports-variables',
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_desc',
	'type'       => 'textarea',
	'name'       => esc_html__( 'Description', 'rank-math' ),
	'attributes' => [
		'rows'            => 3,
		'data-autoresize' => true,
		'placeholder'     => Helper::get_settings( "titles.pt_{$post_type}_default_snippet_desc", '' ),
	],
	'classes'    => 'rank-math-supports-variables',
	'dep'        => [ [ 'rank_math_rich_snippet', 'off,book,local', '!=' ] ],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_url',
	'type'       => 'text_url',
	'name'       => esc_html__( 'URL', 'rank-math' ),
	'attributes' => [
		'rows'            => 3,
		'data-autoresize' => true,
		'data-rule-url'   => true,
	],
	'classes'    => 'rank-math-validate-field',
	'dep'        => [ [ 'rank_math_rich_snippet', 'book,event,local,music' ] ],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_author',
	'type'       => 'text',
	'name'       => esc_html__( 'Author', 'rank-math' ),
	'attributes' => [
		'rows'            => 3,
		'data-autoresize' => true,
	],
	'dep'        => [ [ 'rank_math_rich_snippet', 'book' ] ],
]);

include_once 'article.php';
include_once 'book.php';
include_once 'course.php';
include_once 'event.php';
include_once 'job-posting.php';
include_once 'local.php';
include_once 'music.php';
include_once 'product.php';
include_once 'recipe.php';
include_once 'restaurant.php';
include_once 'video.php';
include_once 'person.php';
include_once 'review.php';
include_once 'software.php';
include_once 'service.php';
