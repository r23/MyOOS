<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<section class="no-results not-found">

<?php get_template_part( 'template-parts/global-templates/pagetitle' ); ?>

<div class="page-content">
	<div class="row">
		<div class="col-12 mb-3">
			<?php get_search_form(); ?>
		</div>

		<div class="col-6">
			<?php the_widget( 'WP_Widget_Recent_Posts' ); ?>

			<?php if ( cpschool_categorized_blog() ) : // Only show the widget if site has multiple categories. ?>
				<div class="widget widget_categories">
					<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'cpschool' ); ?></h2>

					<ul>
						<?php
						wp_list_categories(
							array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 10,
							)
						);
						?>
					</ul>

				</div><!-- .widget -->
			<?php endif; ?>
		</div>

		<div class="col-6">
			<?php
			/* translators: %1$s: smiley */
			$archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'cpschool' ), convert_smilies( ':)' ) ) . '</p>';
			the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$archive_content" );

			the_widget( 'WP_Widget_Tag_Cloud' );
			?>
		</div>
	</div>
</div><!-- .page-content -->

</section><!-- .no-results -->
