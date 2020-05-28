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
<div class="rank-math-ui dashboard-wrapper container help">
	<div class="rank-math-box <?php echo $class; ?>">

		<header>

			<h3><?php esc_html_e( 'Plugin Activation', 'rank-math' ); ?></h3>

			<span class="button button-large <?php echo $class; ?>"><?php echo $is_registered ? '<i class="rm-icon rm-icon-tick"></i>' . esc_html__( 'Plugin Activated', 'rank-math' ) : '<i class="rm-icon rm-icon-cross"></i>' . esc_html__( 'Not Activated', 'rank-math' ); ?></span>

		</header>

		<div class="rank-math-box-content rank-math-ui rank-math-validate-field">

			<form method="post" action="">

				<input type="hidden" name="registration-action" value="<?php echo $is_registered ? 'deregister' : 'register'; ?>">
				<?php wp_nonce_field( 'rank_math_register_product' ); ?>

				<?php if ( ! $is_registered ) : ?>
					<?php // translators: variables used to wrap the text in the strong tag. ?>
					<p><?php printf( wp_kses_post( 'The plugin is currently not activated, click on the button below to login or register for FREE using your %1$sGoogle account, Facebook account%2$s or %1$syour email account%2$s.', 'rank-math' ), '<strong>', '</strong>' ); ?></p>
					<div class="consent-box">
						<input type="checkbox" name="rank-math-usage-tracking" id="rank-math-usage-tracking" value="on" <?php checked( Helper::get_settings( 'general.usage_tracking' ) ); ?>>
						<?php // translators: Privacy Policy link. ?>
						<label for="rank-math-usage-tracking"><p><?php printf( __( 'Gathering usage data helps us make Rank Math SEO plugin better - for you. By understanding how you use Rank Math, we can introduce new features and find out if existing features are working well for you. If you don’t want us to collect data from your website, uncheck the tickbox. Please note that licensing information may still be sent back to us for authentication. We collect data anonymously, read more %s.', 'rank-math' ), '<a href="' . KB::get( 'rm-privacy' ) . '" target="_blank">' . esc_attr__( 'here', 'rank-math' ) . '</a>' ); ?><p></label>
					</div>
					<a href="<?php echo esc_url( $activate_url ); ?>" class="button button-primary button-animated" ><?php esc_html_e( 'Activate Now', 'rank-math' ); ?></a>
				<?php else : ?>
					<?php // translators: variables used to wrap the text in the strong tag. ?>
					<p><?php printf( wp_kses_post( 'You have successfully activated Rank Math. If you find the plugin useful, %1$s feel free to recommend it to your friends or colleagues %2$s.', 'rank-math' ), '<strong>', '</strong>' ); ?><?php Admin_Helper::get_social_share(); ?></p>
					<div class="frm-submit">
						<button type="submit" class="button button-primary button-xlarge" name="button"><?php echo esc_html__( 'Deactivate License', 'rank-math' ); ?></button>
					</div>
				<?php endif; ?>
			</form>

		</div>

	</div>
