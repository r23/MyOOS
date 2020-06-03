<?php
$block_id = get_theme_mod( 'alert_popup_block' );

if ( $block_id ) {
	$block_content = get_post_field( 'post_content', $block_id );
	if ( $block_content ) {
		$alert_ver = md5( $block_content );

		if ( ! isset( $_COOKIE['site_alert_popup_dismiss_ver'] ) || $_COOKIE['site_alert_popup_dismiss_ver'] != $alert_ver || is_customize_preview() ) {
			?>
			<div id="modal-alert" class="modal fade modal-close-inline modal-site-width" tabindex="-1" role="dialog" aria-label="<?php echo esc_attr( 'slide-in menu', 'cpschool' ); ?>" aria-hidden="true"  data-ver="<?php echo $alert_ver; ?>">
				<div class="modal-dialog site-width-max modal-dialog-centered" role="document">
					<div class="modal-content has-background has-color-bg-alt-background-color">
						<div class="modal-header pb-0">
							<button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr( 'Close alert', 'cpschool' ); ?>">
								<i aria-hidden="true" class="cps-icon cps-icon-close"></i>
							</button>
						</div>
						<div class="modal-body">
							<?php echo '<div class="container">' . $block_content . '</div>'; ?>
						</div>
					</div>
				</div>
			</div><!-- #modal-search -->
			<?php
		}
	}
}
