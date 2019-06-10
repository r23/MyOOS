<?php
/**
 * The public-facing template tags.
 *
 * @package    RankMath
 * @subpackage RankMath\Frontend
 */

use RankMath\Sitemap\Router;
use RankMath\Frontend\Breadcrumbs;

/**
 * Get breadcrumbs.
 *
 * @param array $args Array of arguments.
 * @return string Breadcrumbs HTML output
 */
function rank_math_get_breadcrumbs( $args = [] ) {
	return Breadcrumbs::get() ? Breadcrumbs::get()->get_breadcrumb( $args ) : '';
}

/**
 * Output breadcrumbs.
 *
 * @param array $args Array of arguments.
 */
function rank_math_the_breadcrumbs( $args = [] ) {
	echo rank_math_get_breadcrumbs( $args );
}

/**
 * Get sitemap url.
 *
 * @return string
 */
function rank_math_get_sitemap_url() {
	return Router::get_base_url( 'sitemap_index.xml' );
}

/**
 * Get SEO score for a post.
 *
 * @param array $args Array of arguments.
 * @return string Breadcrumbs HTML output
 */
function rank_math_get_seo_score( $args = [] ) {
	return rank_math()->frontend_seo_score->get_output( $args );
}

/**
 * Output SEO score for a post.
 *
 * @param array $args Array of arguments.
 */
function rank_math_the_seo_score( $args = [] ) {
	echo rank_math_get_seo_score( $args );
}

/**
 * Register new replacement %variables%.
 * For use by other plugins/themes to register extra variables.
 *
 * @codeCoverageIgnore
 *
 * @param  string $var       The name of the variable to replace, i.e. '%var%'
 *                           - the surrounding % are optional.
 * @param  mixed  $callback  Function or method to call to retrieve the replacement value for the variable
 *                           and should *return* the replacement value. DON'T echo it.
 * @param  array  $args      Array with title, desc and example values.
 *
 * @return bool Whether the replacement function was succesfully registered.
 */
function rank_math_register_var_replacement( $var, $callback, $args = [] ) {
  RankMath\Replace_Vars::register_replacement( $var, $callback, $args );
}