<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>



<div class="wrapper has-background has-footer-main-bg-color-background-color" id="wrapper-footer">
	<div id="footer-content" data-aos="fade" data-aos-duration="1000" data-aos-anchor-placement="top">
		<?php get_template_part( 'template-parts/sidebar-templates/sidebar', 'footerfull' ); ?>

		<?php
		$footer_block_id = get_theme_mod( 'footer_main_block' );
		if ( $footer_block_id ) {
			echo '<div class="container" id="footer-block">' . apply_filters( 'the_content', get_post_field( 'post_content', $footer_block_id ) ) . '</div>';
		}
		?>
			

		<div class="container" id="footer-site-info">
			<div class="row">
				<div class="col-md-12">
					<footer class="site-footer" id="colophon">
						<div class="site-info">
							<?php cpschool_site_info(); ?>
						</div><!-- .site-info -->
					</footer><!-- #colophon -->
				</div><!--col end -->
			</div><!-- row end -->
		</div><!-- container end -->
	</div>

	<div id="footer-image-holder">
		<?php
		$footer_image = get_theme_mod( 'footer_main_bg_image' );
		if ( isset( $footer_image['id'] ) ) {
			echo wp_get_attachment_image( $footer_image['id'], 'full' );
		}
		?>
	</div>
</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->


<?php
// TODO - We should have hook to trigger to load those only when required
get_template_part( 'template-parts/global-templates/modal', 'alert' );
get_template_part( 'template-parts/global-templates/modal', 'slide-in-menu' );
get_template_part( 'template-parts/global-templates/modal', 'search' );
?>

<?php wp_footer(); ?>

</body>

</html>

