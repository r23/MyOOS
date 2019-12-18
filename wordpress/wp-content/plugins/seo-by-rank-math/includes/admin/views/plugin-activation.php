<?php
/**
 * Plugin activation template.
 *
 * @package    RankMath
 * @subpackage RankMath\Admin
 */

use RankMath\KB;
use RankMath\Helper;
use RankMath\Admin\Admin_Helper;

$is_registered = Helper::is_site_connected();
$class         = $is_registered ? 'status-green' : 'status-red';
$activate_url  = Admin_Helper::get_activate_url();
?>
<div class="rank-math-box <?php echo $class; ?>">

	<header>

		<h3><?php esc_html_e( 'Plugin Activation', 'rank-math' ); ?></h3>

		<span class="button button-large <?php echo $class; ?>"><?php echo $is_registered ? esc_html__( 'Plugin Activated', 'rank-math' ) : esc_html__( 'Not Activated', 'rank-math' ); ?></span>

	</header>

	<div class="rank-math-box-content rank-math-ui rank-math-validate-field" style="min-height:100px">

		<form method="post" action="">

			<input type="hidden" name="registration-action" value="<?php echo $is_registered ? 'deregister' : 'register'; ?>">

			<?php if ( ! $is_registered ) : ?>
			<a href="<?php echo esc_url( $activate_url ); ?>" class="button button-primary button-xlarge" ><?php esc_html_e( 'Activate Now', 'rank-math' ); ?></a>
			<?php else : ?>
				<h3 style="margin:0 0 20px; display: inline; vertical-align: top;"><?php esc_html_e( 'You have successfully activated Rank Math. If you find the plugin useful, feel free to recommend it to your friends or colleagues.', 'rank-math' ); ?></h3>
				<?php Admin_Helper::get_social_share(); ?>
				<div class="frm-submit">
					<button type="submit" class="button button-primary button-xlarge" name="button"><?php echo esc_html__( 'Deactivate License', 'rank-math' ); ?></button>
				</div>
			<?php endif; ?>
		</form>

	</div>

</div>
