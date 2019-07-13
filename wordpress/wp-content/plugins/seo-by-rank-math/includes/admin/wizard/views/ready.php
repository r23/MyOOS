<?php
/**
 * Setup wizard ready step.
 *
 * @package    RankMath
 * @subpackage RankMath\Admin\Wizard
 */

use RankMath\Helper;
use RankMath\KB;
?>
<header>
	<h1>
		<i class="dashicons dashicons-yes"></i> <?php esc_html_e( 'Your site is ready!', 'rank-math' ); ?>
		<?php \RankMath\Admin\Admin_Helper::get_social_share(); ?>
	</h1>
</header>
<div class="rank-math-additional-options">
	<div class="rank-math-auto-update-wrapper">
		<h3><?php esc_html_e( 'Enable auto update of the plugin', 'rank-math' ); ?></h3>
		<label class="switch">
			<input class="switch-input" type="checkbox" id="auto-update" <?php echo Helper::get_settings( 'general.enable_auto_update' ) ? 'checked="checked"' : ''; ?> />
			<span class="switch-label" data-on="Yes" data-off="No"></span>
			<span class="switch-handle"></span>
		</label>
	</div>
	<div class="rank-math-score-wrapper">
		<h3><?php esc_html_e( 'Proudly Show the SEO Score to Your Visitors', 'rank-math' ); ?></h3>
		<label class="switch">
			<input class="switch-input" type="checkbox" id="show-seo-score" <?php echo Helper::get_settings( 'general.frontend_seo_score' ) ? 'checked="checked"' : ''; ?> />
			<span class="switch-label" data-on="Yes" data-off="No"></span>
			<span class="switch-handle"></span>
		</label>
		<div class="rank-math-score-image">
			<img src="<?php echo rank_math()->plugin_url(); ?>/assets/admin/img/wizard-seo-score.png" />
		</div>
	</div>
</div>
<br class="clear">
<?php if ( ! Helper::is_whitelabel() ) : ?>

	<div class="wizard-next-steps wp-clearfix">
		<div class="score-100">
			<a href="<?php KB::the( 'score-100' ); ?>" target="_blank">
				<img src="<?php echo rank_math()->plugin_url(); ?>/assets/admin/img/score-100.png">
			</a>
		</div>
		<div class="learn-more">
			<h2><?php esc_html_e( 'Learn more', 'rank-math' ); ?></h2>
			<ul>
				<li>
					<span class="dashicons dashicons-facebook"></span><a href="<?php KB::the( 'fb-group' ); ?>" target="_blank"><strong><?php esc_html_e( 'Join FREE Facebook Group', 'rank-math' ); ?></strong></a>
				</li>
				<li>
					<span class="dashicons dashicons-welcome-learn-more"></span><a href="<?php KB::the( 'rm-kb' ); ?>" target="_blank"><?php esc_html_e( 'Rank Math Knowledge Base', 'rank-math' ); ?></a>
				</li>
				<li>
					<span class="dashicons dashicons-video-alt3"></span><a href="<?php KB::the( 'wp-error-fixes' ); ?>" target="_blank"><?php esc_html_e( 'Common WordPress Errors & Fixes', 'rank-math' ); ?></a>
				</li>
				<li>
					<span class="dashicons dashicons-sos"></span><a href="<?php KB::the( 'rm-support' ); ?>" target="_blank"><?php esc_html_e( 'Get 24x7 Support', 'rank-math' ); ?></a>
				</li>
			</ul>
		</div>
	</div>

	<footer class="form-footer wp-core-ui rank-math-ui">
		<a href="<?php echo esc_url( Helper::get_dashboard_url() ); ?>" class="button button-secondary"><?php esc_html_e( 'Return to dashboard', 'rank-math' ); ?></a>
		<a href="<?php echo esc_url( Helper::get_admin_url( 'help' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Proceed to Help Page', 'rank-math' ); ?></a>
		<a href="<?php echo esc_url( $wizard->step_next_link() ); ?>" class="button button-primary"><?php esc_html_e( 'Setup Advanced Options', 'rank-math' ); ?></a>
		<?php do_action( 'rank_math/wizard/ready_footer', $wizard ); ?>
	</footer>
<?php else : ?>
	<p><?php esc_html_e( 'Your site is now optimized.', 'rank-math' ); ?></p>
	<footer class="form-footer wp-core-ui rank-math-ui">
		<a href="<?php echo esc_url( Helper::get_admin_url( 'options-general' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Proceed to Settings', 'rank-math' ); ?></a>
	</footer>
	<?php
endif;
