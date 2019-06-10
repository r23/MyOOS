<?php
/**
 * The Updates routine for version 1.0.15.
 *
 * @since      1.0.15
 * @package    RankMath
 * @subpackage RankMath\Updates
 * @author     Rank Math <support@rankmath.com>
 */

/**
 * Delete previous notices.
 */
function rank_math_1_0_15_rseset_options() {
	global $wpdb;

	// Clear sitemap transients.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_sitemap_%'" );

	// Clear SEO Analysis result.
	delete_option( 'rank_math_seo_analysis_results' );
}

rank_math_1_0_15_rseset_options();
