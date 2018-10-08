<?php
/**
 *  FORM to send request for access to data about user
 */

?>
<?php if ( 'GET' == $_SERVER['REQUEST_METHOD'] && ! isset( $_REQUEST['thank_you'] ) ) : ?>
    <form method="post">
		<?php _e( 'Email', 'wp_gdpr' ); ?>:<br>
        <input type="email" name="email" required>
        <br><br>
        <input type="checkbox" name="checkbox_gdpr" id="checkbox_gdpr" required>
        <label for="checkbox_gdpr">
			<?php echo wp_unslash( $privacy_policy_strings[3] ); ?>
        </label>
        <br><br>
        <input type="hidden" name="gdpr_translation" value="<?php echo $pieces; ?>">
        <input type="hidden" name="mail_action" value="gdpr">
        <input type="submit" name="gdpr_req" value="<?php echo $submit_custom_text; ?>">
    </form>
<?php else: ?>
    <h3><?php _e( 'Thank You! We will send you an email with a link to access your personal data.', 'wp_gdpr' ); ?></h3>
    <p>
        <b><?php echo $warning_custom_text; ?></b> <?php echo $link_custom_text; ?>
    </p>
<?php endif; ?>


