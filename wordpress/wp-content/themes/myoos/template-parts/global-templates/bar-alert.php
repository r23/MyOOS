<?php
$alert = get_theme_mod( 'alert_html' );

if ( $alert ) {
	$alert_ver = md5( $alert );

	if ( ! isset( $_COOKIE['site_alert_bar_dismiss_ver'] ) || $_COOKIE['site_alert_bar_dismiss_ver'] != $alert_ver || is_customize_preview() ) {
		?>
		<div id="site-alert" class="alert alert-dismissible has-background has-alert-bg-color-background-color" role="alert" data-ver="<?php echo $alert_ver; ?>">
			<div class="container text-center">
				<button type="button" <?php cpschool_class( 'site-alert-close', 'close' ); ?> data-dismiss="alert" aria-label="Close">
					<i aria-hidden="true" class="cps-icon cps-icon-close"></i>
				</button>

				<?php echo wpautop( $alert ); ?>
			</div>
		</div>
		<?php
	}
}
