<?php
/**
 * Post rendering content according to caller of get_template_part.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<?php get_template_part( 'template-parts/global-templates/pagetitle' ); ?>

	<div class="entry-content">
		<?php do_action( 'cpschool_singular_before_content' ); ?>

		<?php the_content(); ?>

		<?php do_action( 'cpschool_singular_after_content' ); ?>

		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'cpschool' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php
	/*
	<footer class="entry-footer">
		<?php //cpschool_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	*/
	?>
</article><!-- #post-## -->
