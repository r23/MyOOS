<?php
/**
 * Dashboard page template.
 *
 * @package    RankMath
 * @subpackage RankMath\Admin
 */

use RankMath\Admin\Admin_Helper;
use RankMath\Admin\System_Info;

$is_network_admin  = is_network_admin();
$is_network_active = RankMath\Helper::is_plugin_active_for_network();
$current_tab       = $is_network_active && $is_network_admin ? 'help' : ( isset( $_GET['view'] ) ? filter_input( INPUT_GET, 'view' ) : 'modules' );
?>
<div class="wrap rank-math-wrap">

	<span class="wp-header-end"></span>

	<h1><?php esc_html_e( 'Welcome to Rank Math!', 'rank-math' ); ?></h1>

	<div class="rank-math-text">
		<?php esc_html_e( 'The most complete WordPress SEO plugin to convert your website into a traffic generating machine.', 'rank-math' ); ?>
	</div>


	<?php
	if ( ! ( $is_network_active && $is_network_admin ) ) {
		rank_math()->admin->display_dashboard_nav();
	}
	?>

	<?php
	if ( $is_network_active && ! $is_network_admin && 'help' === $current_tab ) {
		return;
	}

	// phpcs:disable
	// Display modules activation and deactivation form.
	if ( 'modules' === $current_tab ) {
		rank_math()->manager->display_form();

	// Others.
	} else {
		include_once Admin_Helper::get_view( "dashboard-{$current_tab}" );
	}
	// phpcs:enable
	?>
</div>
