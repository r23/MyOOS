<?php
/**
 * Metabox - Software Application Rich Snippet
 *
 * @package    RankMath
 * @subpackage RankMath\RichSnippet
 */

$software = [ [ 'rank_math_rich_snippet', 'software' ] ];

$cmb->add_field([
	'id'         => 'rank_math_snippet_software_price',
	'type'       => 'text',
	'name'       => esc_html__( 'Price', 'rank-math' ),
	'dep'        => $software,
	'classes'    => 'cmb-row-50',
	'attributes' => [
		'type' => 'number',
		'step' => 'any',
	],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_software_price_currency',
	'type'       => 'text',
	'name'       => esc_html__( 'Price Currency', 'rank-math' ),
	'desc'       => esc_html__( 'ISO 4217 Currency code. Example: EUR', 'rank-math' ),
	'classes'    => 'cmb-row-50 rank-math-validate-field',
	'attributes' => [
		'data-rule-regex'       => 'true',
		'data-validate-pattern' => '^[A-Z]{3}$',
		'data-msg-regex'        => esc_html__( 'Please use the correct format. Example: EUR', 'rank-math' ),
	],
	'dep'        => $software,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_software_operating_system',
	'name'    => esc_html__( 'Operating System', 'rank-math' ),
	'type'    => 'text',
	'desc'    => esc_html__( 'For example, "Windows 7", "OSX 10.6", "Android 1.6"', 'rank-math' ),
	'classes' => 'cmb-row-50',
	'dep'     => $software,
]);

$cmb->add_field([
	'id'      => 'rank_math_snippet_software_application_category',
	'name'    => esc_html__( 'Application Category', 'rank-math' ),
	'type'    => 'text',
	'desc'    => esc_html__( 'For example, "Game", "Multimedia"', 'rank-math' ),
	'classes' => 'cmb-row-50',
	'dep'     => $software,
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_software_rating_value',
	'name'       => esc_html__( 'Rating', 'rank-math' ),
	'desc'       => esc_html__( 'Average of all ratings (1-5). Example: 4.7', 'rank-math' ),
	'type'       => 'text',
	'dep'        => $software,
	'classes'    => 'cmb-row-50',
	'attributes' => [
		'type' => 'number',
		'min'  => 1,
		'max'  => 5,
		'step' => 'any',
	],
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_software_rating_count',
	'name'       => esc_html__( 'Rating Count', 'rank-math' ),
	'desc'       => esc_html__( 'Number of ratings', 'rank-math' ),
	'type'       => 'text',
	'dep'        => $software,
	'classes'    => 'cmb-row-50',
	'attributes' => [ 'type' => 'number' ],
]);
