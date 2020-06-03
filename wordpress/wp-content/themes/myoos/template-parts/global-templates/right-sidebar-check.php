<?php
/**
 * Right sidebar check.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

</div><!-- #closing the primary container from /templates/global-templates/left-sidebar-check.php -->

<?php
if ( in_array( 'sidebar-right', cpschool_get_active_sidebars() ) ) :
	get_template_part( 'template-parts/sidebar-templates/sidebar', 'right' );
endif;
