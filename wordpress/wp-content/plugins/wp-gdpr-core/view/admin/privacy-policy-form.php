<?php
/**
 * form to update privacy policy link in admin page settings
 */

use wp_gdpr\model\Request_Form;

?>
<form method="post" action="" class="postbox postbox" id="gdpr_admin_privacy_policy">
    <div class="tooltip">
    <h3><?php _e('Settings for', 'wp_gdpr'); ?>&nbsp;<b><?php _e('WordPress comments'); ?></b>&nbsp;<span class="dashicons dashicons-info"></span>
    </h3><span class="tooltiptext">These settings apply to the consent checkbox that is automatically created under your WordPress comment fields.</span>
    </div>
    <hr>
    <div class="postbox-group postbox-group">
        <label for="gdpr_priv_pov_label"><?php _e( 'Privacy policy label', 'wp_gdpr' ); ?>:</label>
        <textarea name="gdpr_priv_pov_label"><?php echo $privacy_policy_strings[0]; ?></textarea>
    </div>
    <div class="postbox-group">
        <label for="gdpr_priv_pov_text"><?php _e( 'Privacy policy text', 'wp_gdpr' ); ?>:</label>
		<?php $args = array(
			'media_buttons' => false,
			'textarea_rows' => '3',
			'tinymce'       => array(
				'plugins'                 => 'wordpress, wplink, wpdialogs',
				'theme_advanced_buttons1' => 'bold, italic, underline, strikethrough, forecolor, separator, bullist, numlist, separator, link, unlink, image',
				'theme_advanced_buttons2' => ''
			),
			'quicktags'     => array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close' ),
			'textarea_name' => 'gdpr_priv_pov_text',
		); ?>

		<?php wp_editor( wp_unslash( $privacy_policy_strings[1] ), 'gdpr_priv_pov_text', $args ); ?>

    </div>
    <div class="postbox-group postbox-group">
        <label for="gdpr_priv_pov_checkbox"><?php _e( 'Privacy policy checkbox', 'wp_gdpr' ); ?>:</label>
        <textarea name="gdpr_priv_pov_checkbox"><?php echo stripslashes( $privacy_policy_strings[2] ); ?></textarea>
    </div>
    <div class="postbox-spacing"></div>
<div class="tooltip">
    <h3><?php _e('Settings for', 'wp_gdpr'); ?> <b><?php _e( 'Personal Data Request Page', 'wp_gdpr'); ?></b>&nbsp;<small><a href="<?php echo Request_Form::get_personal_data_page_url('') ?>" style="color: #1F87B6;">view page</a></small>&nbsp;<span class="dashicons dashicons-info"></span>
    </h3>
    <span class="tooltiptext">These settings apply to the consent checkbox that is automatically created on your Personal Data Request Page or every page where you use the shortcode for the Request Form.</span>
</div>


    <hr>
    <div class="postbox-group">
        <label for="gdpr_priv_pov_text_data_request"><?php _e( 'Privacy policy text', 'wp_gdpr' ); ?>:</label>
		<?php $args = array(
			'media_buttons' => false,
			'textarea_rows' => '3',
			'tinymce'       => array(
				'plugins'                 => 'wordpress, wplink, wpdialogs',
				'theme_advanced_buttons1' => 'bold, italic, underline, strikethrough, forecolor, separator, bullist, numlist, separator, link, unlink, image',
				'theme_advanced_buttons2' => ''
			),
			'quicktags'     => array( 'buttons' => 'strong,em,link,block,del,ins,img,ul,ol,li,code,close' ),
			'textarea_name' => 'gdpr_priv_pov_text_data_request',
		); ?>

		<?php wp_editor( wp_unslash( $privacy_policy_strings[3] ), 'gdpr_priv_pov_text_data_request', $args ); ?>

    </div>

	<?php do_action( 'gdpr_display_custom_privacy_policy' ); ?>

    <input type="submit" class="button button-primary" name="gdpr_save_priv_pol_settings"
           value="<?php _e( 'Update privacy policy settings', 'wp_gdpr' ); ?>">
</form>