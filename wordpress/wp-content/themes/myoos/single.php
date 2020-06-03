<?php
/**
 * The template for displaying all single posts.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="main-wrapper wrapper" id="single-wrapper">
	<div <?php cpschool_class( 'content', 'container' ); ?> id="content">
		<div class="row">
			<!-- Do the left sidebar check -->
			<?php get_template_part( 'template-parts/global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<?php get_template_part( 'template-parts/loop-templates/content-singular', get_post_type() ); ?>

					<?php cpschool_post_nav(); ?>

					<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
				<?php endwhile; // end of the loop. ?>
			</main><!-- #main -->

			<!-- Do the right sidebar check -->
			<?php get_template_part( 'template-parts/global-templates/right-sidebar-check' ); ?>
		</div><!-- .row -->
	</div><!-- #content -->
</div><!-- #single-wrapper -->

<?php
get_footer();
