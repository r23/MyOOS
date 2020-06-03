<?php
/**
 * The template for displaying the author pages.
 *
 * Learn more: https://codex.wordpress.org/Author_Templates
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="main-wrapper wrapper" id="author-wrapper">
	<div class="<?php echo esc_attr( 'container' ); ?>" id="content">
		<div class="row">
			<!-- Do the left sidebar check -->
			<?php get_template_part( 'template-parts/global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">
				<?php
				if ( isset( $_GET['author_name'] ) ) {
					$curauth = get_user_by( 'slug', $author_name );
				} else {
					$curauth = get_userdata( intval( $author ) );
				}
				?>

				<h2><?php echo esc_html( 'Posts by', 'cpschool' ) . ' ' . esc_html( $curauth->nickname ); ?>:</h2>

				<ul class="mb-3">
					<!-- The Loop -->
					<?php if ( have_posts() ) : ?>
						<?php
						while ( have_posts() ) :
							the_post();
							?>
							<li>
								<?php
								printf(
									'<a rel="bookmark" href="%1$s" title="%2$s %3$s">%3$s</a>',
									esc_url( apply_filters( 'the_permalink', get_permalink( $post ), $post ) ),
									esc_attr( __( 'Permanent Link:', 'cpschool' ) ),
									the_title( '', '', false )
								);
								?>
								<?php cpschool_posted_on(); ?>
								<?php esc_html_e( 'in', 'cpschool' ); ?>
								<?php the_category( '&' ); ?>
							</li>
						<?php endwhile; ?>
					<?php else : ?>
						<?php get_template_part( 'template-parts/loop-templates/content', 'none' ); ?>
					<?php endif; ?>
					<!-- End Loop -->
				</ul>
			</main><!-- #main -->

			<!-- The pagination component -->
			<?php cpschool_pagination(); ?>

			<!-- Do the right sidebar check -->
			<?php get_template_part( 'template-parts/global-templates/right-sidebar-check' ); ?>
		</div> <!-- .row -->
	</div><!-- #content -->
</div><!-- #author-wrapper -->

<?php
get_footer();
