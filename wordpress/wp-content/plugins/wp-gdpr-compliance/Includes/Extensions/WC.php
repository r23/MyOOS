<?php

namespace WPGDPRC\Includes\Extensions;

use WPGDPRC\Includes\Helper;
use WPGDPRC\Includes\Integration;

/**
 * Class WC
 * @package WPGDPRC\Includes\Extensions
 */
class WC {
    const ID = 'woocommerce';
    const SUPPORTED_VERSION = '2.5.0';
    /** @var null */
    private static $instance = null;

    /**
     * Add WP GDPR field before submit button
     */
    public function addField() {
        $args = array(
            'type' => 'checkbox',
            'class' => array('wpgdprc-checkbox'),
            'label' => Integration::getCheckboxText(self::ID) . ' <abbr class="wpgdprc-required required" title="' . Integration::getRequiredMessage(self::ID) . '">*</abbr>',
            'required' => true
        );
        woocommerce_form_field('wpgdprc', apply_filters('wpgdprc_woocommerce_field_args', $args));
    }

    /**
     * Check if WP GDPR checkbox is checked
     */
    public function checkPostCheckoutForm() {
        if (!isset($_POST['wpgdprc'])) {
            wc_add_notice(Integration::getErrorMessage(self::ID), 'error');
        }
    }

    /**
     * Check if WP GDPR checkbox is checked on register
     *
     * @param string $username
     * @param string $emailAddress
     * @param \WP_Error $errors
     */
    public function checkPostRegisterForm($username = '', $emailAddress = '', \WP_Error $errors) {
        if (!isset($_POST['wpgdprc'])) {
            $errors->add('wpgdprc_error', Integration::getErrorMessage(self::ID));
        }
    }

    /**
     * @param int $orderId
     */
    public function addAcceptedDateToOrderMeta($orderId = 0) {
        if (isset($_POST['wpgdprc']) && !empty($orderId)) {
            update_post_meta($orderId, '_wpgdprc', time());
        }
    }

    /**
     * @param \WC_Order $order
     */
    public function displayAcceptedDateInOrderData(\WC_Order $order) {
        $orderId = (method_exists($order, 'get_id')) ? $order->get_id() : $order->id;
        $label = __('GDPR accepted on:', WP_GDPR_C_SLUG);
        $date = get_post_meta($orderId, '_wpgdprc', true);
        $value = (!empty($date)) ? Helper::localDateFormat(get_option('date_format') . ' ' . get_option('time_format'), $date) : __('Not accepted.', WP_GDPR_C_SLUG);
        echo apply_filters(
            'wpgdprc_woocommerce_accepted_date_in_order_data',
            sprintf('<p class="form-field form-field-wide wpgdprc-accepted-date"><strong>%s</strong><br />%s</p>', $label, $value),
            $label,
            $value,
            $order
        );
    }

    /**
     * @param array $columns
     * @return array
     */
    public function displayAcceptedDateColumnInOrderOverview($columns = array()) {
        $columns['wpgdprc-privacy'] = apply_filters('wpgdprc_accepted_date_column_in_woocommerce_order_overview', __('Privacy', WP_GDPR_C_SLUG));
        return $columns;
    }

    /**
     * @param string $column
     * @param int $orderId
     * @return string
     */
    public function displayAcceptedDateInOrderOverview($column = '', $orderId = 0) {
        if ($column === 'wpgdprc-privacy') {
            $date = get_post_meta($orderId, '_wpgdprc', true);
            $value = (!empty($date)) ? Helper::localDateFormat(get_option('date_format') . ' ' . get_option('time_format'), $date) : __('Not accepted.', WP_GDPR_C_SLUG);
            echo apply_filters('wpgdprc_accepted_date_in_woocommerce_order_overview', $value, $orderId);
        }
        return $column;
    }

    /**
     * @return null|WC
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
