<?php
/**
 * Left sidebar check.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$active_sidebars   = cpschool_get_active_sidebars();
$content_col_width = 12 - 3 * count( $active_sidebars );
?>

<?php if ( in_array( 'sidebar-left', $active_sidebars ) ) : ?>
	<?php get_template_part( 'template-parts/sidebar-templates/sidebar', 'left' ); ?>
<?php endif; ?>

<div class="col-md-<?php echo $content_col_width; ?> content-area" id="primary">
