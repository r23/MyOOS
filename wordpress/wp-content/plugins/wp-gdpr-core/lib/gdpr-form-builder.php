<?php


namespace wp_gdpr\lib;

/**
 * Class GDPR_Table_Builder
 * @package wp_gdpr\lib
 *
 * allows to build simple table
 */
class Gdpr_Form_Builder {

    /**
     * GDPR_Form_Builder constructor.
     */
    public function __construct() {
    }

    /**
     * show form
     */
    public function print_form() {
        $this->build_form();
    }

    /**
     * table open tab
     */
    public function build_form() {
        ?>
        <form method="post" action="" class="wp-gdpr_postbox postbox">
            <label for="request_add_on"><?php _e('Request add-on for your plugin', 'wp_gdpr'); ?>:</label>
            <input type="text" name="request_add_on" required></br>
            <label for="email"><?php _e('Email', 'wp_gdpr'); ?>:</label>
            <input type="email" name="email" required>
            <?php
            $string = __('This form collects your email so that we can keep you updated about your request. Check out our %s for more information.', 'wp_gdpr');
            $url = '<a href="https://wp-gdpr.eu/privacy-policy/" target="_blank">' . __('privacy policy','wp_gdpr') . '</a>';
            ?>
            <p><?php echo sprintf($string, $url); ?></p>
            <input name="gdpr" type="checkbox" value="I consent to having WP-GDPR collect my email when provided." required>
            <label for="gdpr" id="gdpr"><?php _e('I consent to having WP-GDPR collect my email when provided.', 'wp_gdpr'); ?></label></br>
            <input type="submit" class="button button-primary" value="<?php _e('submit', 'wp_gdpr'); ?>">
            <input type="hidden" name="mail_action" value="gdpr">
        </form>
        <?php
    }
}
