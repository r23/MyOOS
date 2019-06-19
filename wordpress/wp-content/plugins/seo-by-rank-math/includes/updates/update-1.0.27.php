<?php
/**
 * The Updates routine for version 1.0.27.
 *
 * @since      1.0.27
 * @package    RankMath
 * @subpackage RankMath\Updates
 * @author     Rank Math <support@rankmath.com>
 */

/**
 * // Clear SEO Analysis result.
 */
function rank_math_1_0_27_rseset_options() {
  delete_option( 'rank_math_seo_analysis_results' );
}
rank_math_1_0_27_rseset_options();
