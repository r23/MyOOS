<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<div class="col-md-3 widget-area" id="secondary" role="complementary">

	<?php dynamic_sidebar( 'sidebar-1' ); ?>

</div><!-- #secondary -->
