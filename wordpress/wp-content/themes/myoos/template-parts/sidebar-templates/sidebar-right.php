<?php
/**
 * The right sidebar containing the main widget area.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-right' ) ) {
	return;
}
?>

<aside <?php cpschool_class( 'sidebar-widget-area', 'col-md-3 widget-area sidebar-widget-area' ); ?> id="right-sidebar">
	<div class="sidebar-widget-area-content">
		<?php dynamic_sidebar( 'sidebar-right' ); ?>
	</div>
</aside><!-- #right-sidebar -->
