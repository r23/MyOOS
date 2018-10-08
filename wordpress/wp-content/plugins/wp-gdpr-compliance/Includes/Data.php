<?php

namespace WPGDPRC\Includes;

use WPGDPRC\Includes\Data\Comment;
use WPGDPRC\Includes\Data\User;
use WPGDPRC\Includes\Data\WooCommerceOrder;

/**
 * Class Data
 * @package WPGDPRC\Includes
 */
class Data {
    /** @var null */
    private static $instance = null;
    /** @var string */
    protected $emailAddress = '';

    /**
     * Data constructor.
     * @param string $emailAddress
     */
    public function __construct($emailAddress = '') {
        if (empty($emailAddress)) {
            wp_die(
                '<p>' . sprintf(
                    __('<strong>ERROR</strong>: %s', WP_GDPR_C_SLUG),
                    __('Email Address is required.', WP_GDPR_C_SLUG)
                ) . '</p>'
            );
            exit;
        }
        $this->setEmailAddress($emailAddress);
    }

    /**
     * @return array
     */
    public static function getPossibleDataTypes() {
        return array('user', 'comment', 'woocommerce_order');
    }

    /**
     * @param string $type
     * @return array
     */
    private static function getOutputColumns($type = '') {
        $output = array();
        switch ($type) {
            case 'user' :
                $output = array(
                    __('Username', WP_GDPR_C_SLUG),
                    __('Display Name', WP_GDPR_C_SLUG),
                    __('Email Address', WP_GDPR_C_SLUG),
                    __('Website', WP_GDPR_C_SLUG),
                    __('Registered on', WP_GDPR_C_SLUG)
                );
                break;
            case 'comment' :
                $output = array(
                    __('Author', WP_GDPR_C_SLUG),
                    __('Content', WP_GDPR_C_SLUG),
                    __('Email Address', WP_GDPR_C_SLUG),
                    __('IP Address', WP_GDPR_C_SLUG)
                );
                break;
            case 'woocommerce_order' :
                $output = array(
                    __('Order', WP_GDPR_C_SLUG),
                    __('Email Address', WP_GDPR_C_SLUG),
                    __('Name', WP_GDPR_C_SLUG),
                    __('Address', WP_GDPR_C_SLUG),
                    __('Postcode / ZIP', WP_GDPR_C_SLUG),
                    __('City', WP_GDPR_C_SLUG)
                );
                break;
        }
        $output['checkbox'] = '<input type="checkbox" class="wpgdprc-select-all" />';
        return $output;
    }

    /**
     * @param array $data
     * @param string $type
     * @param int $requestId
     * @return array
     */
    private static function getOutputData($data = array(), $type = '', $requestId = 0) {
        $output = array();
        $action = '<input type="checkbox" name="' . WP_GDPR_C_PREFIX . '_remove[]" class="wpgdprc-checkbox" value="%d" tabindex="1" />';
        switch ($type) {
            case 'user' :
                /** @var User $user */
                foreach ($data as $user) {
                    $request = DeleteRequest::getInstance()->getByTypeAndDataIdAndAccessRequestId($type, $user->getId(), $requestId);
                    $output[$user->getId()] = array(
                        $user->getUsername(),
                        $user->getDisplayName(),
                        $user->getEmailAddress(),
                        $user->getWebsite(),
                        $user->getRegisteredDate(),
                        (($request === false) ? sprintf($action, $user->getId()) : '&nbsp;')
                    );
                }
                break;
            case 'comment' :
                /** @var Comment $comment */
                foreach ($data as $comment) {
                    $request = DeleteRequest::getInstance()->getByTypeAndDataIdAndAccessRequestId($type, $comment->getId(), $requestId);
                    $output[$comment->getId()] = array(
                        $comment->getAuthorName(),
                        Helper::shortenStringByWords(wp_strip_all_tags($comment->getContent(), true), 5),
                        $comment->getEmailAddress(),
                        $comment->getIpAddress(),
                        (($request === false) ? sprintf($action, $comment->getId()) : '&nbsp;')
                    );
                }
                break;
            case 'woocommerce_order' :
                /** @var WooCommerceOrder $woocommerceOrder */
                foreach ($data as $woocommerceOrder) {
                    $request = DeleteRequest::getInstance()->getByTypeAndDataIdAndAccessRequestId($type, $woocommerceOrder->getOrderId(), $requestId);
                    $billingAddressTwo = $woocommerceOrder->getBillingAddressTwo();
                    $address = (!empty($billingAddressTwo)) ? sprintf('%s,<br />%s', $woocommerceOrder->getBillingAddressOne(), $billingAddressTwo) : $woocommerceOrder->getBillingAddressOne();
                    $output[$woocommerceOrder->getOrderId()] = array(
                        sprintf('#%d', $woocommerceOrder->getOrderId()),
                        $woocommerceOrder->getBillingEmailAddress(),
                        sprintf('%s %s', $woocommerceOrder->getBillingFirstName(), $woocommerceOrder->getBillingLastName()),
                        $address,
                        $woocommerceOrder->getBillingPostCode(),
                        $woocommerceOrder->getBillingCity(),
                        (($request === false) ? sprintf($action, $woocommerceOrder->getOrderId()) : '&nbsp;')
                    );
                }
                break;
        }
        return $output;
    }

    /**
     * @param array $data
     * @param string $type
     * @param int $requestId
     * @return string
     */
    public static function getOutput($data = array(), $type = '', $requestId = 0) {
        $output = '';
        if (!empty($data)) {
            $output .= sprintf(
                '<form class="wpgdprc-form wpgdprc-form--delete-request" data-wpgdprc=\'%s\' method="POST" novalidate="novalidate">',
                json_encode(array(
                    'type' => $type
                ))
            );
            $output .= '<div class="wpgdprc-message" style="display: none;"></div>';
            $output .= '<table class="wpgdprc-table">';
            $output .= '<thead>';
            $output .= '<tr>';
            foreach (self::getOutputColumns($type) as $key => $column) {
                $class = (is_string($key)) ? $key : sanitize_title($column);
                $output .= sprintf('<th class="wpgdprc-table__head wpgdprc-table__head--%s" scope="col">%s</th>', $class, $column);
            }
            $output .= '</tr>';
            $output .= '</thead>';
            $output .= '<tbody>';
            foreach (self::getOutputData($data, $type, $requestId) as $id => $row) {
                $output .= sprintf('<tr data-id="%d">', $id);
                foreach ($row as $value) {
                    $output .= sprintf('<td>%s</td>', $value);
                }
                $output .= '</tr>';
            }
            $output .= '</tbody>';
            $output .= '</table>';
            $output .= sprintf(
                '<p><input type="submit" class="wpgdprc-remove" value="%s" /></p>',
                sprintf(
                    __('Anonymise selected %s(s)', WP_GDPR_C_SLUG),
                    str_replace('_', ' ', $type)
                )
            );
            $output .= '</form>';
        }
        return $output;
    }

    /**
     * @return User[]
     */
    public function getUsers() {
        global $wpdb;
        $output = array();
        $query = "SELECT * FROM `" . $wpdb->users . "` WHERE `user_email` = '%s'";
        $results = $wpdb->get_results($wpdb->prepare($query, $this->getEmailAddress()));
        if ($results !== null) {
            foreach ($results as $row) {
                $object = new User($row->ID);
                $output[] = $object;
            }
        }
        return $output;
    }

    /**
     * @return Comment[]
     */
    public function getComments() {
        global $wpdb;
        $output = array();
        $query = "SELECT * FROM " . $wpdb->comments . " WHERE `comment_author_email` = '%s'";
        $results = $wpdb->get_results($wpdb->prepare($query, $this->getEmailAddress()));
        if ($results !== null) {
            foreach ($results as $row) {
                $object = new Comment();
                $object->loadByRow($row);
                $output[] = $object;
            }
        }
        return $output;
    }

    /**
     * @return WooCommerceOrder[]
     */
    public function getWooCommerceOrders() {
        global $wpdb;
        $output = array();
        $query = "SELECT * FROM " . $wpdb->postmeta . " WHERE `meta_key` = '_billing_email' AND `meta_value` = '%s'";
        $results = $wpdb->get_results($wpdb->prepare($query, $this->getEmailAddress()));
        if ($results !== null) {
            foreach ($results as $row) {
                $output[] = new WooCommerceOrder($row->post_id);
            }
        }
        return $output;
    }

    /**
     * @return string
     */
    public function getEmailAddress() {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress) {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return null|Data
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}