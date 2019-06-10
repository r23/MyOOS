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
			<strong><?php esc_html_e( 'Rank Math Email/Username', 'rank-math' ); ?></strong><br>
			<input class="regular-text fullwidth required" data-rule-required="true" type="text" name="connect-username" value="">
			<br><br>
			<strong><?php esc_html_e( 'Rank Math Password', 'rank-math' ); ?></strong><br>
			<input class="regular-text fullwidth required" data-rule-required="true" type="password" name="connect-password" value="">
			<br><br>
			<div class="frm-gather-data">
				<input type="checkbox" class="cmb2-option cmb2-list" name="rank-math-usage-tracking" id="rank-math-usage-tracking" value="on" checked="checked" />
				<label for="rank-math-usage-tracking">
					<?php
					/* translators: link to privacy policy */
					echo sprintf( __( 'Gathering usage data helps us make Rank Math SEO plugin better - for you. By understanding how you use Rank Math, we can introduce new features and find out if existing features are working well for you. If you don’t want us to collect data from your website, uncheck the tickbox. Please note that licensing information may still be sent back to us for authentication. We collect data anonymously, read more %s.', 'rank-math' ), '<a href="' . KB::get( 'rm-privacy' ) . '" target="_blank">here</a>' );
					?>
				</label>
			</div>
			<?php else : ?>
				<h3 style="margin:0 0 20px; display: inline; vertical-align: top;"><?php esc_html_e( 'You have successfully activated Rank Math. If you find the plugin useful, feel free to recommend it to your friends or colleagues.', 'rank-math' ); ?></h3>
				<?php Admin_Helper::get_social_share(); ?>
			<?php endif; ?>
			<div class="frm-submit">
				<button type="submit" class="button button-primary button-xlarge" name="button"><?php echo $is_registered ? esc_html__( 'Deactivate License', 'rank-math' ) : esc_html__( 'Connect Your Account', 'rank-math' ); ?></button>
				<?php if ( ! $is_registered ) { ?>
					<p><strong><?php esc_html_e( 'Don’t have an account?', 'rank-math' ); ?></strong> <em><a href="https://rankmath.com/#signup" target="_blank"><?php esc_html_e( 'Click here', 'rank-math' ); ?></a></em></p>
				<?php } ?>
			</div>
		</form>

	</div>

</div>
