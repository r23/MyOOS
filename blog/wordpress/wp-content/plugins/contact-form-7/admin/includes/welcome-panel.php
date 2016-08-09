<?php

function wpcf7_welcome_panel() {
	$classes = 'welcome-panel';

	$vers = (array) get_user_meta( get_current_user_id(),
		'wpcf7_hide_welcome_panel_on', true );

	if ( wpcf7_version_grep( wpcf7_version( 'only_major=1' ), $vers ) ) {
		$classes .= ' hidden';
	}

?>
<div id="welcome-panel" class="<?php echo esc_attr( $classes ); ?>">
	<?php wp_nonce_field( 'wpcf7-welcome-panel-nonce', 'welcomepanelnonce', false ); ?>
	<a class="welcome-panel-close" href="<?php echo esc_url( menu_page_url( 'wpcf7', false ) ); ?>"><?php echo esc_html( __( 'Dismiss', 'contact-form-7' ) ); ?></a>

	<div class="welcome-panel-content">
		<div class="welcome-panel-column-container">
			<div class="welcome-panel-column">
				<h3><?php echo esc_html( __( 'Contact Form 7 Needs Your Support', 'contact-form-7' ) ); ?></h3>
				<p class="message"><?php echo esc_html( __( "It is hard to continue development and support for this plugin without contributions from users like you. If you enjoy using Contact Form 7 and find it useful, please consider making a donation.", 'contact-form-7' ) ); ?></p>
				<p><?php echo wpcf7_link( __( 'http://contactform7.com/donate/', 'contact-form-7' ), __( 'Donate', 'contact-form-7' ), array( 'class' => 'button button-primary' ) ); ?></p>
			</div>

			<div class="welcome-panel-column">
				<h3><?php echo esc_html( __( 'Get Started', 'contact-form-7' ) ); ?></h3>
				<ul>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/getting-started-with-contact-form-7/', 'contact-form-7' ), __( 'Getting Started with Contact Form 7', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/admin-screen/', 'contact-form-7' ), __( 'Admin Screen', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/tag-syntax/', 'contact-form-7' ), __( 'How Tags Work', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/setting-up-mail/', 'contact-form-7' ), __( 'Setting Up Mail', 'contact-form-7' ) ); ?></li>
				</ul>
			</div>

			<div class="welcome-panel-column">
				<h3><?php echo esc_html( __( 'Did You Know?', 'contact-form-7' ) ); ?></h3>
				<ul>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/spam-filtering-with-akismet/', 'contact-form-7' ), __( 'Spam Filtering with Akismet', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/save-submitted-messages-with-flamingo/', 'contact-form-7' ), __( 'Save Messages with Flamingo', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/selectable-recipient-with-pipes/', 'contact-form-7' ), __( 'Selectable Recipient with Pipes', 'contact-form-7' ) ); ?></li>
					<li><?php echo wpcf7_link( __( 'http://contactform7.com/tracking-form-submissions-with-google-analytics/', 'contact-form-7' ), __( 'Tracking with Google Analytics', 'contact-form-7' ) ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php
}

add_action( 'wp_ajax_wpcf7-update-welcome-panel', 'wpcf7_admin_ajax_welcome_panel' );

function wpcf7_admin_ajax_welcome_panel() {
	check_ajax_referer( 'wpcf7-welcome-panel-nonce', 'welcomepanelnonce' );

	$vers = get_user_meta( get_current_user_id(),
		'wpcf7_hide_welcome_panel_on', true );

	if ( empty( $vers ) || ! is_array( $vers ) ) {
		$vers = array();
	}

	if ( empty( $_POST['visible'] ) ) {
		$vers[] = WPCF7_VERSION;
	}

	$vers = array_unique( $vers );

	update_user_meta( get_current_user_id(), 'wpcf7_hide_welcome_panel_on', $vers );

	wp_die( 1 );
}
