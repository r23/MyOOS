<?php
/**
 * Metabox - Course Rich Snippet
 *
 * @package    RankMath
 * @subpackage RankMath\RichSnippet
 */

$course_dep = [ [ 'rank_math_rich_snippet', 'course' ] ];

$cmb->add_field([
	'id'      => 'rank_math_snippet_course_provider_type',
	'type'    => 'radio_inline',
	'name'    => esc_html__( 'Course Provider', 'rank-math' ),
	'options' => [
		'Organization' => esc_html__( 'Organization', 'rank-math' ),
		'Person'       => esc_html__( 'Person', 'rank-math' ),
	],
	'classes' => 'cmb-row-33 nob',
	'default' => 'Organization',
	'dep'     => $course_dep,
]);

$cmb->add_field([
	'id'   => 'rank_math_snippet_course_provider',
	'type' => 'text',
	'name' => esc_html__( 'Course Provider Name', 'rank-math' ),
	'dep'  => $course_dep,
]);

$cmb->add_field([
	'id'         => 'rank_math_snippet_course_provider_url',
	'type'       => 'text_url',
	'name'       => esc_html__( 'Course Provider URL', 'rank-math' ),
	'dep'        => $course_dep,
	'attributes' => [
		'data-rule-url' => 'true',
	],
	'classes'    => 'nob rank-math-validate-field',
]);
