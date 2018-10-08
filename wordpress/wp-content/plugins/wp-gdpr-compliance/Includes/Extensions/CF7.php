<?php

namespace WPGDPRC\Includes\Extensions;

use WPGDPRC\Includes\Helper;
use WPGDPRC\Includes\Integration;

/**
 * Class CF7
 * @package WPGDPRC\Includes\Extensions
 */
class CF7 {
    const ID = 'contact-form-7';
    const SUPPORTED_VERSION = '4.6';
    /** @var null */
    private static $instance = null;

    public function processIntegration() {
        $this->removeFormTagFromForms();
        $this->removeAcceptedDateFromForms();
        if (Helper::isEnabled(self::ID)) {
            $this->addFormTagToForms();
            $this->addAcceptedDateToForms();
        }
    }

    /**
     * Add [wpgdprc] string to enabled forms
     */
    public function addFormTagToForms() {
        foreach ($this->getEnabledForms() as $formId) {
            $tag = '[wpgdprc "' . $this->getCheckboxText($formId) . '"]';
            $output = get_post_meta($formId, '_form', true);
            preg_match('/(\[wpgdprc?.*\])/', $output, $matches);
            if (!empty($matches)) {
                $output = str_replace($matches[0], $tag, $output);
            } else {
                $pattern = '/(\[submit?.*\])/';
                preg_match($pattern, $output, $matches);
                if (!empty($matches)) {
                    $output = preg_replace($pattern, "$tag\n\n" . $matches[0], $output);
                } else {
                    $output = $output . "\n\n$tag";
                }
            }
            update_post_meta($formId, '_form', $output);
        }
    }

    /**
     * Add [wpgdprc] string to enabled forms
     */
    public function addAcceptedDateToForms() {
        foreach ($this->getEnabledForms() as $formId) {
            $output = get_post_meta($formId, '_mail', true);
            if (!empty($output)) {
                $tag = '[wpgdprc]';
                $body = $output['body'];
                preg_match('/(\[wpgdprc\])/', $body, $matches);
                if (empty($matches)) {
                    $pattern = '/(--)/';
                    preg_match($pattern, $body, $matches);
                    if (!empty($matches)) {
                        $body = preg_replace($pattern, "$tag\n\n" . $matches[0], $body);
                    } else {
                        $body = $body . "\n\n$tag";
                    }
                }
                $output['body'] = $body;
                update_post_meta($formId, '_mail', $output);
            }
        }
    }

    /**
     * Remove [wpgdprc] string from disabled forms
     */
    public function removeFormTagFromForms() {
        foreach (CF7::getInstance()->getForms() as $formId) {
            $output = get_post_meta($formId, '_form', true);
            $pattern = '/(\n\n\[wpgdprc?.*\])/';
            preg_match($pattern, $output, $matches);
            if (!empty($matches)) {
                $output = preg_replace($pattern, '', $output);
                update_post_meta($formId, '_form', $output);
            }
        }
    }

    /**
     * Remove [wpgdprc] string from disabled forms
     */
    public function removeAcceptedDateFromForms() {
        foreach (CF7::getInstance()->getForms() as $formId) {
            $output = get_post_meta($formId, '_mail', true);
            $pattern = '/(\n\n\[wpgdprc\])/';
            preg_match($pattern, $output['body'], $matches);
            if (!empty($matches)) {
                $output['body'] = preg_replace($pattern, '', $output['body']);
                update_post_meta($formId, '_mail', $output);
            }
        }
    }

    public function addFormTagSupport() {
        wpcf7_add_form_tag('wpgdprc', array($this, 'addFormTagHandler'));
    }

    /**
     * @param \WPCF7_FormTag|array $tag
     * @return string
     */
    public function addFormTagHandler($tag) {
        $tag = (is_array($tag)) ? new \WPCF7_FormTag($tag) : $tag;
        $output = '';
        switch ($tag->type) {
            case 'wpgdprc' :
                $tag->name = 'wpgdprc';
                $label = (!empty($tag->labels[0])) ? esc_html($tag->labels[0]) : self::getCheckboxText();
                $class = wpcf7_form_controls_class($tag->type, 'wpcf7-validates-as-required');
                $validation_error = wpcf7_get_validation_error($tag->name);
                if ($validation_error) {
                    $class .= ' wpcf7-not-valid';
                }
                $label_first = $tag->has_option('label_first');
                $use_label_element = $tag->has_option('use_label_element');
                $atts = wpcf7_format_atts(array(
                    'class' => $tag->get_class_option($class),
                    'id' => $tag->get_id_option(),
                ));
                $item_atts = wpcf7_format_atts(array(
                    'type' => 'checkbox',
                    'name' => $tag->name,
                    'value' => 1,
                    'tabindex' => $tag->get_option('tabindex', 'signed_int', true),
                    'aria-required' => 'true',
                    'aria-invalid' => ($validation_error) ? 'true' : 'false',
                ));

                if ($label_first) { // put label first, input last
                    $output = sprintf(
                        '<span class="wpcf7-list-item-label">%1$s</span><input %2$s />',
                        esc_html($label),
                        $item_atts
                    );
                } else {
                    $output = sprintf(
                        '<input %2$s /><span class="wpcf7-list-item-label">%1$s</span>',
                        esc_html($label),
                        $item_atts
                    );
                }

                if ($use_label_element) {
                    $output = '<label>' . $output . '</label>';
                }

                $output = '<span class="wpcf7-list-item">' . $output . '</span>';
                $output = sprintf(
                    '<span class="wpcf7-form-control-wrap %1$s"><span %2$s>%3$s</span>%4$s</span>',
                    sanitize_html_class($tag->name),
                    $atts,
                    $output,
                    $validation_error
                );
                break;
        }
        return $output;
    }

    /**
     * @param \WPCF7_ContactForm $contactForm
     * @return \WPCF7_ContactForm
     */
    public function changeMailBodyOutput(\WPCF7_ContactForm $contactForm) {
        $mail = $contactForm->prop('mail');
        if (!empty($mail['body'])) {
            $submission = \WPCF7_Submission::get_instance();
            if (!empty($submission)) {
                $data = $submission->get_posted_data();
                if (isset($data['wpgdprc']) && $data['wpgdprc'] == 1) {
                    $value = Helper::localDateFormat(get_option('date_format') . ' ' . get_option('time_format'), time());
                } else {
                    $value = __('Not accepted.', WP_GDPR_C_SLUG);
                }
                $output = apply_filters(
                    'wpgdprc_cf7_mail_body_output',
                    __('GDPR accepted on:', WP_GDPR_C_SLUG) . "\n$value",
                    $data,
                    $submission
                );
                $mail['body'] = str_replace('[wpgdprc]', $output, $mail['body']);
                $contactForm->set_properties(array('mail' => $mail));
            }
        }
        return $contactForm;
    }

    /**
     * @param \WPCF7_Validation $result
     * @param \WPCF7_FormTag|array $tag
     * @return \WPCF7_Validation
     */
    public function validateField(\WPCF7_Validation $result, $tag) {
        $tag = (gettype($tag) == 'array') ? new \WPCF7_FormTag($tag) : $tag;
        $formId = (isset($_POST['_wpcf7']) && is_numeric($_POST['_wpcf7'])) ? (int)$_POST['_wpcf7'] : 0;
        switch ($tag->type) {
            case 'wpgdprc' :
                $tag->name = 'wpgdprc';
                $name = $tag->name;
                $value = (isset($_POST[$name])) ? filter_var($_POST[$name], FILTER_VALIDATE_BOOLEAN) : false;
                if ($value === false) {
                    $result->invalidate($tag, self::getErrorMessage($formId));
                }
                break;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getForms() {
        return get_posts(array(
            'post_type' => 'wpcf7_contact_form',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));
    }

    /**
     * @return array
     */
    public function getEnabledForms() {
        return (array)get_option(WP_GDPR_C_PREFIX . '_integrations_' . self::ID . '_forms', array());
    }

    /**
     * @return array
     */
    public function getFormTexts() {
        return (array)get_option(WP_GDPR_C_PREFIX . '_integrations_' . self::ID . '_form_text', array());
    }

    /**
     * @return array
     */
    public function getFormErrorMessages() {
        return (array)get_option(WP_GDPR_C_PREFIX . '_integrations_' . self::ID . '_error_message', array());
    }

    /**
     * @param int $formId
     * @param bool $insertPrivacyPolicyLink
     * @return string
     */
    public function getCheckboxText($formId = 0, $insertPrivacyPolicyLink = true) {
        if (!empty($formId)) {
            $texts = $this->getFormTexts();
            if (!empty($texts[$formId])) {
                $result = esc_html($texts[$formId]);
                $result = ($insertPrivacyPolicyLink === true) ? Integration::insertPrivacyPolicyLink($result) : $result;
                return apply_filters('wpgdprc_cf7_checkbox_text', $result, $formId);
            }
        }
        return Integration::getCheckboxText();
    }

    /**
     * @param int $formId
     * @return string
     */
    public function getErrorMessage($formId = 0) {
        if (!empty($formId)) {
            $errors = $this->getFormErrorMessages();
            if (!empty($errors[$formId])) {
                $result = esc_html($errors[$formId]);
                return apply_filters('wpgdprc_cf7_error_message', $result, $formId);
            }
        }
        return Integration::getErrorMessage();
    }

    /**
     * @return null|CF7
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}