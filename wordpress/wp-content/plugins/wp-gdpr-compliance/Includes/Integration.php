<?php

namespace WPGDPRC\Includes;

use WPGDPRC\Includes\Extensions\CF7;
use WPGDPRC\Includes\Extensions\GForms;
use WPGDPRC\Includes\Extensions\WC;
use WPGDPRC\Includes\Extensions\WP;

/**
 * Class Integration
 * @package WPGDPRC\Includes
 */
class Integration {
    /** @var null */
    private static $instance = null;

    /**
     * Integration constructor.
     */
    public function __construct() {
        add_action('admin_init', array($this, 'registerSettings'));
        foreach (Helper::getEnabledPlugins() as $plugin) {
            switch ($plugin['id']) {
                case WP::ID :
                    add_filter('comment_form_submit_field', array(WP::getInstance(), 'addField'), 999);
                    add_action('pre_comment_on_post', array(WP::getInstance(), 'checkPost'));
                    add_action('comment_post', array(WP::getInstance(), 'addAcceptedDateToCommentMeta'));
                    add_filter('manage_edit-comments_columns', array(WP::getInstance(), 'displayAcceptedDateColumnInCommentOverview'));
                    add_action('manage_comments_custom_column', array(WP::getInstance(), 'displayAcceptedDateInCommentOverview'), 10, 2);
                    break;
                case CF7::ID :
                    add_action('update_option_' . WP_GDPR_C_PREFIX . '_integrations_' . CF7::ID . '_forms', array(CF7::getInstance(), 'processIntegration'));
                    add_action('update_option_' . WP_GDPR_C_PREFIX . '_integrations_' . CF7::ID . '_form_text', array(CF7::getInstance(), 'processIntegration'));
                    add_action('update_option_' . WP_GDPR_C_PREFIX . '_integrations_' . CF7::ID . '_error_message', array(CF7::getInstance(), 'processIntegration'));
                    add_action('wpcf7_init', array(CF7::getInstance(), 'addFormTagSupport'));
                    add_filter('wpcf7_before_send_mail', array(CF7::getInstance(), 'changeMailBodyOutput'), 999);
                    add_filter('wpcf7_validate_wpgdprc', array(CF7::getInstance(), 'validateField'), 10, 2);
                    break;
                case WC::ID :
                    add_action('woocommerce_checkout_process', array(WC::getInstance(), 'checkPostCheckoutForm'));
                    add_action('woocommerce_register_post', array(WC::getInstance(), 'checkPostRegisterForm'), 10, 3);
                    add_action('woocommerce_review_order_before_submit', array(WC::getInstance(), 'addField'), 999);
                    add_action('woocommerce_register_form', array(WC::getInstance(), 'addField'), 999);
                    add_action('woocommerce_checkout_update_order_meta', array(WC::getInstance(), 'addAcceptedDateToOrderMeta'));
                    add_action('woocommerce_admin_order_data_after_order_details', array(WC::getInstance(), 'displayAcceptedDateInOrderData'));
                    add_filter('manage_edit-shop_order_columns', array(WC::getInstance(), 'displayAcceptedDateColumnInOrderOverview'));
                    add_action('manage_shop_order_posts_custom_column', array(WC::getInstance(), 'displayAcceptedDateInOrderOverview'), 10, 2);
                    break;
                case GForms::ID :
                    add_action('update_option_' . WP_GDPR_C_PREFIX . '_integrations_' . GForms::ID . '_forms', array(GForms::getInstance(), 'processIntegration'));
                    add_action('update_option_' . WP_GDPR_C_PREFIX . '_integrations_' . GForms::ID . '_form_text', array(GForms::getInstance(), 'processIntegration'));
                    add_action('update_option_' . WP_GDPR_C_PREFIX . '_integrations_' . GForms::ID . '_error_message', array(GForms::getInstance(), 'processIntegration'));
                    add_filter('gform_entries_field_value', array(GForms::getInstance(), 'displayAcceptedDateInEntryOverview'), 10, 4);
                    add_filter('gform_get_field_value', array(GForms::getInstance(), 'displayAcceptedDateInEntry'), 10, 2);
                    foreach (GForms::getInstance()->getEnabledForms() as $formId) {
                        add_filter('gform_entry_list_columns_' . $formId, array(GForms::getInstance(), 'displayAcceptedDateColumnInEntryOverview'), 10, 2);
                        add_filter('gform_save_field_value_' . $formId, array(GForms::getInstance(), 'addAcceptedDateToEntry'), 10, 3);
                        add_action('gform_validation_' . $formId, array(GForms::getInstance(), 'overwriteValidationMessage'));
                    }
                    break;
            }
        }
    }

    public function registerSettings() {
        foreach (self::getSupportedIntegrations() as $plugin) {
            register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'], 'intval');
            switch ($plugin['id']) {
                case CF7::ID :
                    add_action('update_option_' . WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'], array(CF7::getInstance(), 'processIntegration'));
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_forms');
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_form_text', array('sanitize_callback' => array(Helper::getInstance(), 'sanitizeData')));
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_error_message', array('sanitize_callback' => array(Helper::getInstance(), 'sanitizeData')));
                    break;
                case GForms::ID :
                    add_action('update_option_' . WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'], array(GForms::getInstance(), 'processIntegration'));
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_forms');
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_form_text');
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_error_message');
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_required_message');
                    break;
                default :
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_text');
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_error_message');
                    register_setting(WP_GDPR_C_SLUG . '_integrations', WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'] . '_required_message');
                    break;
            }
        }
    }

    /**
     * @param string $plugin
     * @return string
     */
    public static function getSupportedPluginOptions($plugin = '') {
        $output = '';
        switch ($plugin) {
            case CF7::ID :
                $forms = CF7::getInstance()->getForms();
                if (!empty($forms)) {
                    $optionNameForms = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_forms';
                    $optionNameFormText = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_form_text';
                    $optionNameErrorMessage = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_error_message';
                    $enabledForms = CF7::getInstance()->getEnabledForms();
                    $output .= '<ul class="wpgdprc-checklist-options">';
                    foreach ($forms as $form) {
                        $formSettingId = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_form_' . $form;
                        $textSettingId = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_form_text_' . $form;
                        $errorSettingId = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_error_message_' . $form;
                        $enabled = in_array($form, $enabledForms);
                        $text = CF7::getInstance()->getCheckboxText($form, false);
                        $errorMessage = CF7::getInstance()->getErrorMessage($form);
                        $output .= '<li class="wpgdprc-clearfix">';
                        $output .= '<div class="wpgdprc-checkbox">';
                        $output .= '<input type="checkbox" name="' . $optionNameForms . '[]" id="' . $formSettingId . '" value="' . $form . '" tabindex="1" data-type="save_setting" data-option="' . $optionNameForms . '" data-append="1" ' . checked(true, $enabled, false) . ' />';
                        $output .= '<label for="' . $formSettingId . '"><strong>' . sprintf(__('Form: %s', WP_GDPR_C_SLUG), get_the_title($form)) . '</strong></label>';
                        $output .= '<span class="wpgdprc-instructions">' . __('Activate for this form:', WP_GDPR_C_SLUG) . '</span>';
                        $output .= '</div>';
                        $output .= '<div class="wpgdprc-setting">';
                        $output .= '<label for="' . $textSettingId . '">' . __('Checkbox text', WP_GDPR_C_SLUG) . '</label>';
                        $output .= '<div class="wpgdprc-options">';
                        $output .= '<textarea name="' . $optionNameFormText . '[' . $form . ']' . '" class="regular-text" id="' . $textSettingId . '" placeholder="' . $text . '">' . $text . '</textarea>';
                        $output .= '</div>';
                        $output .= '</div>';
                        $output .= '<div class="wpgdprc-setting">';
                        $output .= '<label for="' . $errorSettingId . '">' . __('Error message', WP_GDPR_C_SLUG) . '</label>';
                        $output .= '<div class="wpgdprc-options">';
                        $output .= '<input type="text" name="' . $optionNameErrorMessage . '[' . $form . ']' . '" class="regular-text" id="' . $errorSettingId . '" placeholder="' . $errorMessage . '" value="' . $errorMessage . '" />';
                        $output .= '</div>';
                        $output .= '</div>';
                        $output .= Helper::getAllowedHTMLTagsOutput($plugin);
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                } else {
                    $output = '<p>' . __('No forms found.', WP_GDPR_C_SLUG) . '</p>';
                }
                break;
            case GForms::ID :
                $forms = GForms::getInstance()->getForms();
                if (!empty($forms)) {
                    $optionNameForms = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_forms';
                    $optionNameFormText = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_form_text';
                    $optionNameErrorMessage = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_error_message';
                    $optionNameRequiredMessage = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_required_message';
                    $enabledForms = GForms::getInstance()->getEnabledForms();
                    $output .= '<ul class="wpgdprc-checklist-options">';
                    foreach ($forms as $form) {
                        $formSettingId = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_form_' . $form['id'];
                        $textSettingId = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_form_text_' . $form['id'];
                        $errorSettingId = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_error_message_' . $form['id'];
                        $requiredSettingId = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_required_message_' . $form['id'];
                        $enabled = in_array($form['id'], $enabledForms);
                        $text = esc_html(GForms::getInstance()->getCheckboxText($form['id'], false));
                        $errorMessage = esc_html(GForms::getInstance()->getErrorMessage($form['id']));
                        $requiredMessage = esc_html(GForms::getInstance()->getRequiredMessage($form['id']));
                        $output .= '<li class="wpgdprc-clearfix">';
                        $output .= '<div class="wpgdprc-checkbox">';
                        $output .= '<input type="checkbox" name="' . $optionNameForms . '[]" id="' . $formSettingId . '" value="' . $form['id'] . '" tabindex="1" data-type="save_setting" data-option="' . $optionNameForms . '" data-append="1" ' . checked(true, $enabled, false) . ' />';
                        $output .= '<label for="' . $formSettingId . '"><strong>' . sprintf(__('Form: %s', WP_GDPR_C_SLUG), $form['title']) . '</strong></label>';
                        $output .= '<span class="wpgdprc-instructions">' . __('Activate for this form:', WP_GDPR_C_SLUG) . '</span>';
                        $output .= '</div>';
                        $output .= '<div class="wpgdprc-setting">';
                        $output .= '<label for="' . $textSettingId . '">' . __('Checkbox text', WP_GDPR_C_SLUG) . '</label>';
                        $output .= '<div class="wpgdprc-options">';
                        $output .= '<textarea name="' . $optionNameFormText . '[' . $form['id'] . ']' . '" class="regular-text" id="' . $textSettingId . '" placeholder="' . $text . '">' . $text . '</textarea>';
                        $output .= '</div>';
                        $output .= '</div>';
                        $output .= '<div class="wpgdprc-setting">';
                        $output .= '<label for="' . $errorSettingId . '">' . __('Error message', WP_GDPR_C_SLUG) . '</label>';
                        $output .= '<div class="wpgdprc-options">';
                        $output .= '<input type="text" name="' . $optionNameErrorMessage . '[' . $form['id'] . ']' . '" class="regular-text" id="' . $errorSettingId . '" placeholder="' . $errorMessage . '" value="' . $errorMessage . '" />';
                        $output .= '</div>';
                        $output .= '</div>';
                        $output .= '<div class="wpgdprc-setting">';
                        $output .= '<label for="' . $requiredSettingId . '">' . __('Required message', WP_GDPR_C_SLUG) . '</label>';
                        $output .= '<div class="wpgdprc-options">';
                        $output .= '<input type="text" name="' . $optionNameRequiredMessage . '[' . $form['id'] . ']' . '" class="regular-text" id="' . $requiredSettingId . '" placeholder="' . $requiredMessage . '" value="' . $requiredMessage . '" />';
                        $output .= '</div>';
                        $output .= '</div>';
                        $output .= Helper::getAllowedHTMLTagsOutput($plugin);
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                } else {
                    $output = '<p>' . __('No forms found.', WP_GDPR_C_SLUG) . '</p>';
                }
                break;
            default :
                $optionNameText = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_text';
                $optionNameErrorMessage = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_error_message';
                $text = esc_html(self::getCheckboxText($plugin, false));
                $errorMessage = esc_html(self::getErrorMessage($plugin));
                $optionNameRequiredMessage = WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_required_message';
                $requiredMessage = esc_html(self::getRequiredMessage($plugin));
                $output .= '<ul class="wpgdprc-checklist-options">';
                $output .= '<li class="wpgdprc-clearfix">';
                $output .= '<div class="wpgdprc-setting">';
                $output .= '<label for="' . $optionNameText . '">' . __('Checkbox text', WP_GDPR_C_SLUG) . '</label>';
                $output .= '<div class="wpgdprc-options">';
                $output .= '<textarea name="' . $optionNameText . '" class="regular-text" id="' . $optionNameText . '" placeholder="' . $text . '">' . $text . '</textarea>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="wpgdprc-setting">';
                $output .= '<label for="' . $optionNameErrorMessage . '">' . __('Error message', WP_GDPR_C_SLUG) . '</label>';
                $output .= '<div class="wpgdprc-options">';
                $output .= '<input type="text" name="' . $optionNameErrorMessage . '" class="regular-text" id="' . $optionNameErrorMessage . '" placeholder="' . $errorMessage . '" value="' . $errorMessage . '" />';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '<div class="wpgdprc-setting">';
                $output .= '<label for="' . $optionNameRequiredMessage . '">' . __('Required message', WP_GDPR_C_SLUG) . '</label>';
                $output .= '<div class="wpgdprc-options">';
                $output .= '<input type="text" name="' . $optionNameRequiredMessage . '" class="regular-text" id="' . $optionNameRequiredMessage . '" placeholder="' . $requiredMessage . '" value="' . $requiredMessage . '" />';
                $output .= '</div>';
                $output .= '</div>';
                $output .= Helper::getAllowedHTMLTagsOutput($plugin);
                $output .= '</li>';
                $output .= '</ul>';
                break;
        }
        return $output;
    }

    /**
     * @param string $plugin
     * @param bool $insertPrivacyPolicyLink
     * @return string
     */
    public static function getCheckboxText($plugin = '', $insertPrivacyPolicyLink = true) {
        $output = '';
        if (!empty($plugin)) {
            $output = get_option(WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_text');
            $output = ($insertPrivacyPolicyLink === true) ? self::insertPrivacyPolicyLink($output) : $output;
            $output = apply_filters('wpgdprc_' . $plugin . '_checkbox_text', $output);
        }
        if (empty($output)) {
            $output = __('By using this form you agree with the storage and handling of your data by this website.', WP_GDPR_C_SLUG);
        }
        $output = wp_kses($output, Helper::getAllowedHTMLTags($plugin));
        return apply_filters('wpgdprc_checkbox_text', $output);
    }

    /**
     * @param string $plugin
     * @return mixed
     */
    public static function getErrorMessage($plugin = '') {
        $output = '';
        if (!empty($plugin)) {
            $output = get_option(WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_error_message');
            $output = apply_filters('wpgdprc_' . $plugin . '_error_message', $output);
        }
        if (empty($output)) {
            $output = __('Please accept the privacy checkbox.', WP_GDPR_C_SLUG);
        }
        return apply_filters('wpgdprc_error_message', wp_kses($output, Helper::getAllowedHTMLTags($plugin)));
    }

      /**
     * @param string $plugin
     * @return mixed
     */
    public static function getRequiredMessage($plugin = '') {
        $output = '';
        if (!empty($plugin)) {
            $output = get_option(WP_GDPR_C_PREFIX . '_integrations_' . $plugin . '_required_message');
            $output = apply_filters('wpgdprc_' . $plugin . '_required_message', $output);
        }
        if (empty($output)) {
            $output = __('You need to accept this checkbox.', WP_GDPR_C_SLUG);
        }
        return apply_filters('wpgdprc_required_message', esc_attr($output));
    }

    /**
     * @return mixed
     */
    public static function getPrivacyPolicyText() {
        $output = get_option(WP_GDPR_C_PREFIX . '_settings_privacy_policy_text');
        if (empty($output)) {
            $output = __('Privacy Policy', WP_GDPR_C_SLUG);
        }
        return apply_filters('wpgdprc_privacy_policy_text', $output);
    }

    /**
     * @param bool $insertPrivacyPolicyLink
     * @return mixed
     */
    public static function getAccessRequestFormCheckboxText($insertPrivacyPolicyLink = true) {
        $output = get_option(WP_GDPR_C_PREFIX . '_settings_access_request_form_checkbox_text');
        if (empty($output)) {
            $output = __('By using this form you agree with the storage and handling of your data by this website.', WP_GDPR_C_SLUG);
        }
        $output = ($insertPrivacyPolicyLink === true) ? self::insertPrivacyPolicyLink($output) : $output;
        return apply_filters('wpgdprc_access_request_form_checkbox_text', wp_kses($output, Helper::getAllowedHTMLTags()));
    }

    /**
     * @param bool $insertPrivacyPolicyLink
     * @return mixed
     */
    public static function getDeleteRequestFormExplanationText($insertPrivacyPolicyLink = true) {
        $output = get_option(WP_GDPR_C_PREFIX . '_settings_delete_request_form_explanation_text');
        if (empty($output)) {
            $output = sprintf(
                __('Below we show you all of the data stored by %s on %s. Select the data you wish the site owner to anonymise so it cannot be linked to your email address any longer. It is the site\'s owner responsibility to act upon your request. When your data is anonymised you will receive an email confirmation.', WP_GDPR_C_SLUG),
                get_option('blogname'),
                get_option('siteurl')
            );
        }
        $output = ($insertPrivacyPolicyLink === true) ? self::insertPrivacyPolicyLink($output) : $output;
        return apply_filters('wpgdprc_delete_request_form_explanation_text', wp_kses($output, Helper::getAllowedHTMLTags()));
    }

    /**
     * @param string $content
     * @return mixed|string
     */
    public static function insertPrivacyPolicyLink($content = '') {
        $page = get_option(WP_GDPR_C_PREFIX . '_settings_privacy_policy_page');
        $text = Integration::getPrivacyPolicyText();
        if (!empty($page) && !empty($text)) {
            $link = apply_filters(
                'wpgdprc_privacy_policy_link',
                sprintf(
                    '<a target="_blank" href="%s" rel="noopener noreferrer">%s</a>',
                    get_page_link($page),
                    esc_html($text)
                ),
                $page,
                $text
            );
            $content = str_replace('%privacy_policy%', $link, $content);
        }
        return $content;
    }

    /**
     * @return array
     */
    public static function getSupportedWordPressFunctionality() {
        return array(
            array(
                'id' => 'wordpress',
                'name' => __('WordPress Comments', WP_GDPR_C_SLUG),
                'description' => __('When activated the GDPR checkbox will be added automatically just above the submit button.', WP_GDPR_C_SLUG),
            )
        );
    }

    /**
     * @return array
     */
    public static function getSupportedPlugins() {
        return array(
            array(
                'id' => CF7::ID,
                'supported_version' => CF7::SUPPORTED_VERSION,
                'file' => 'contact-form-7/wp-contact-form-7.php',
                'name' => __('Contact Form 7', WP_GDPR_C_SLUG),
                'description' => __('A GDPR form tag will be automatically added to every form you activate.', WP_GDPR_C_SLUG),
            ),
            array(
                'id' => GForms::ID,
                'supported_version' => GForms::SUPPORTED_VERSION,
                'file' => 'gravityforms/gravityforms.php',
                'name' => __('Gravity Forms', WP_GDPR_C_SLUG),
                'description' => __('A GDPR form tag will be automatically added to every form you activate.', WP_GDPR_C_SLUG),
            ),
            array(
                'id' => WC::ID,
                'supported_version' => WC::SUPPORTED_VERSION,
                'file' => 'woocommerce/woocommerce.php',
                'name' => __('WooCommerce', WP_GDPR_C_SLUG),
                'description' => __('The GDPR checkbox will be added automatically at the end of your checkout page.', WP_GDPR_C_SLUG),
            )
        );
    }

    /**
     * @return array
     */
    public static function getSupportedIntegrations() {
        return array_merge(self::getSupportedPlugins(), self::getSupportedWordPressFunctionality());
    }

    /**
     * @return array
     */
    public static function getSupportedIntegrationsLabels() {
        $output = array();
        $supportedIntegrations = self::getSupportedIntegrations();
        foreach ($supportedIntegrations as $supportedIntegration) {
            $output[] = $supportedIntegration['name'];
        }
        return $output;
    }

    /**
     * @return null|Integration
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}