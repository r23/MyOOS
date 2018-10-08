<?php

namespace WPGDPRC\Includes\Extensions;

use WPGDPRC\Includes\Helper;
use WPGDPRC\Includes\Integration;

/**
 * Class GForms
 * @package WPGDPRC\Includes\Extensions
 */
class GForms {
    const ID = 'gravity-forms';
    const SUPPORTED_VERSION = '1.9';
    /** @var null */
    private static $instance = null;

    public function processIntegration() {
        if (!class_exists('\GFAPI')) {
            return;
        }
        foreach (self::getForms() as $form) {
            if (in_array($form['id'], self::getEnabledForms()) && Helper::isEnabled(self::ID)) {
                self::addField($form);
            } else {
                self::removeField($form);
            }
        }
    }

    /**
     * @param array $form
     */
    public function addField($form = array()) {
        $isUpdated = false;
        $lastFieldId = 0;
        $choices = array(
            array(
                'text' => self::getCheckboxText($form['id']) . ' <abbr class="wpgdprc-required" title="' . self::getRequiredMessage($form['id']) . '">*</abbr>',
                'value' => 'true',
                'isSelected' => false
            )
        );
        foreach ($form['fields'] as &$field) {
            if ($field->id > $lastFieldId) {
                $lastFieldId = intval($field->id);
            }
            if (isset($field->wpgdprc) && $field->wpgdprc === true) {
                $field['choices'] = $choices;
                $isUpdated = true;
            }
        }
        if (!$isUpdated) {
            $id = ((int)$lastFieldId > 0) ? $lastFieldId + 1 : 99;
            $args = array(
                'id' => $id,
                'type' => 'checkbox',
                'label' => __('Privacy', WP_GDPR_C_SLUG),
                'labelPlacement' => 'hidden_label',
                'isRequired' => true,
                'enableChoiceValue' => true,
                'choices' => $choices,
                'inputs' => array(
                    array(
                        'id' => $id . '.1',
                        'label' => self::getCheckboxText($form['id']),
                        'name' => 'wpgdprc'
                    )
                ),
                'wpgdprc' => true
            );
            $form['fields'][] = apply_filters('wpgdprc_gforms_field_args', $args, $form);
        }
        \GFAPI::update_form($form, $form['id']);
    }

    /**
     * @param array $form
     */
    public function removeField($form = array()) {
        foreach ($form['fields'] as $index => $field) {
            if (isset($field['wpgdprc']) && $field['wpgdprc'] === true) {
                unset($form['fields'][$index]);
            }
        }
        \GFAPI::update_form($form, $form['id']);
    }

    /**
     * @param array $columns
     * @param int $formId
     * @return array
     */
    public function displayAcceptedDateColumnInEntryOverview($columns = array(), $formId = 0) {
        $key = array_search(self::getCheckboxText($formId), $columns);
        if (!empty($key) && isset($columns[$key])) {
            $columns[$key] = apply_filters('wpgdprc_gforms_accepted_date_column_in_entry_overview', __('Privacy', WP_GDPR_C_SLUG), $columns[$key], $formId);
        }
        return $columns;
    }

    /**
     * @param string $value
     * @param int $formId
     * @param int $fieldId
     * @param array $entry
     * @return string
     */
    public function displayAcceptedDateInEntryOverview($value = '', $formId = 0, $fieldId = 0, $entry = array()) {
        if (empty($value)) {
            $id = self::getFieldIdByFormId($formId);
            if (!empty($id) && $fieldId === $id) {
                $value = (!empty($entry[$fieldId])) ? $entry[$fieldId] : __('Not accepted.', WP_GDPR_C_SLUG);
                $value = apply_filters('wpgdprc_gforms_accepted_date_in_entry_overview', $value, $fieldId, $formId, $entry);
            }
        }
        return $value;
    }

    /**
     * @param mixed $value
     * @param array $entry
     * @return string
     */
    public function displayAcceptedDateInEntry($value, $entry = array()) {
        $fieldId = self::getFieldIdByFormId($entry['form_id']);
        if (!empty($fieldId) && isset($value[$fieldId])) {
            if (empty($value[$fieldId])) {
                $value = __('Not accepted.', WP_GDPR_C_SLUG);
            }
            $value = apply_filters('wpgdprc_gforms_accepted_date_in_entry', $value, $fieldId, $entry);
        }
        return $value;
    }

    /**
     * @param string $value
     * @param array $lead
     * @param mixed $field
     * @return string
     */
    public function addAcceptedDateToEntry($value = '', $lead = array(), $field) {
        if ($field instanceof \GF_Field) {
            if (isset($field['wpgdprc']) && $field['wpgdprc'] === true) {
                if (!empty($value)) {
                    $date = Helper::localDateFormat(get_option('date_format') . ' ' . get_option('time_format'), time());
                    $value = sprintf(__('Accepted on %s.', WP_GDPR_C_SLUG), $date);
                } else {
                    $value = __('Not accepted.', WP_GDPR_C_SLUG);
                }
                $value = apply_filters('wpgdprc_gforms_accepted_date_to_entry', $value, $field, $lead);
            }
        }
        return $value;
    }

    /**
     * @param array $validation_result
     * @return array
     */
    public function overwriteValidationMessage($validation_result = array()) {
        $form = $validation_result['form'];
        foreach ($form['fields'] as &$field) {
            if (isset($field['wpgdprc']) && $field['wpgdprc'] === true) {
                if (isset($field['failed_validation']) && $field['failed_validation'] === true) {
                    $field['validation_message'] = apply_filters('wpgdprc_gforms_validation_message', self::getErrorMessage($form['id']), $field, $form);
                }
            }
        }
        $validation_result['form'] = $form;
        return $validation_result;
    }

    /**
     * @return array
     */
    public function getForms() {
        $output = array();
        if (class_exists('\GFAPI')) {
            $forms = \GFAPI::get_forms();
            foreach ($forms as $form) {
                $output[] = $form;
            }
        }
        return $output;
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
     * @return array
     */
    public function getFormRequiredMessages() {
        return (array)get_option(WP_GDPR_C_PREFIX . '_integrations_' . self::ID . '_required_message', array());
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
                $result = wp_kses($texts[$formId], Helper::getAllowedHTMLTags(self::ID));
                $result = ($insertPrivacyPolicyLink === true) ? Integration::insertPrivacyPolicyLink($result) : $result;
                return apply_filters('wpgdprc_gforms_checkbox_text', $result, $formId);
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
                $result = wp_kses($errors[$formId], Helper::getAllowedHTMLTags(self::ID));
                return apply_filters('wpgdprc_gforms_error_message', $result, $formId);
            }
        }
        return Integration::getErrorMessage();
    }

     /**
     * @param int $formId
     * @return string
     */
    public function getRequiredMessage($formId = 0) {
        if (!empty($formId)) {
            $errors = $this->getFormRequiredMessages();
            if (!empty($errors[$formId])) {
                $result = esc_attr($errors[$formId]);
                return apply_filters('wpgdprc_gforms_required_message', $result, $formId);
            }
        }
        return Integration::getRequiredMessage();
    }

    /**
     * @param int $formId
     * @return int
     */
    private static function getFieldIdByFormId($formId = 0) {
        $form = \GFFormsModel::get_form_meta($formId);
        foreach ($form['fields'] as $field) {
            if (isset($field['wpgdprc']) && $field['wpgdprc'] === true) {
                if (isset($field['inputs'][0]['id'])) {
                    return $field['inputs'][0]['id'];
                }
            }
        }
        return 0;
    }

    /**
     * @return null|GForms
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}