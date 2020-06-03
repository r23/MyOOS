<?php
/**
 * Sidebar setup for footer full.
 *
 * @package cpschool
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<?php
if ( is_active_sidebar( 'footerfull' ) ) :
	?>

	<!-- ******************* The Footer Full-width Widget Area ******************* -->

	<div id="footer-full">

		<div class="<?php echo esc_attr( 'container' ); ?>" id="footer-full-content" tabindex="-1">

			<div class="row">

				<?php dynamic_sidebar( 'footerfull' ); ?>

			</div>

		</div>

	</div><!-- #wrapper-footer-full -->

	<?php
endif;
