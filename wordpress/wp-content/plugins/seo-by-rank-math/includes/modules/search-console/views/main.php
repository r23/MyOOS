<?php
/**
 * Search console main screen template.
 *
 * @package Rank_Math
 */

use RankMath\Helper;

$dir = dirname( __FILE__ ) . '/';
$tab = isset( $_GET['view'] ) ? $_GET['view'] : 'overview';
?>
<div class="wrap rank-math-wrap rank-math-search-console rank-math-search-console-<?php echo $tab; ?>">

	<br>

	<span class="wp-header-end"></span>

	<?php
	Helper::search_console()->display_nav();

	if ( Helper::search_console()->client->is_authorized ) {
		$allowed_tabs = [ 'overview', 'analytics', 'tracker' ];

		// phpcs:disable
		// Search Console - Analytics Tab
		if ( 'analytics' === $tab ) {
			Helper::search_console()->analytics->display_table();

		// Search Console - Sitemaps Tab
		} elseif ( 'sitemaps' === $tab ) {
			echo '<p>' . esc_html__( 'Review sitemaps submitted to your Search Console account.', 'rank-math' ) . '</p>';
			Helper::search_console()->sitemaps->display_table();

		} elseif ( in_array( $tab, $allowed_tabs ) ) {
			include_once $dir . "search-console-{$tab}.php";
		}
		// phpcs:enable
	} else {
		include_once $dir . 'search-console-noauth.php';
	}
	?>
</div>
