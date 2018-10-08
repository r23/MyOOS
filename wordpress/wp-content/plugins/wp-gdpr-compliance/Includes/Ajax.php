<?php

namespace WPGDPRC\Includes;

/**
 * Class Ajax
 * @package WPGDPRC\Includes
 */
class Ajax {
    /** @var null */
    private static $instance = null;

    public function processAction() {
        check_ajax_referer('wpgdprc', 'security');

        $output = array(
            'message' => '',
            'error' => '',
        );
        $data = (isset($_POST['data']) && (is_array($_POST['data']) || is_string($_POST['data']))) ? $_POST['data'] : false;
        if (is_string($data)) {
            $data = json_decode(stripslashes($data), true);
        }
        $type = (isset($data['type']) && is_string($data['type'])) ? esc_html($data['type']) : false;

        if (!$data) {
            $output['error'] = __('Missing data.', WP_GDPR_C_SLUG);
        }

        if (!$type) {
            $output['error'] = __('Missing type.', WP_GDPR_C_SLUG);
        }

        if (empty($output['error'])) {
            switch ($type) {
                case 'save_setting' :
                    $option = (isset($data['option']) && is_string($data['option'])) ? esc_html($data['option']) : false;
                    $value = (isset($data['value'])) ? self::sanitizeValue($data['value']) : false;
                    $enabled = (isset($data['enabled'])) ? filter_var($data['enabled'], FILTER_VALIDATE_BOOLEAN) : false;
                    $append = (isset($data['append'])) ? filter_var($data['append'], FILTER_VALIDATE_BOOLEAN) : false;

                    if (!$option) {
                        $output['error'] = __('Missing option name.', WP_GDPR_C_SLUG);
                    }

                    if (!isset($data['value'])) {
                        $output['error'] = __('Missing value.', WP_GDPR_C_SLUG);
                    }

                    // Let's do this!
                    if (empty($output['error'])) {
                        if ($append) {
                            $values = (array)get_option($option, array());
                            if ($enabled) {
                                if (!in_array($value, $values)) {
                                    $values[] = $value;
                                }
                            } else {
                                $index = array_search($value, $values);
                                if ($index !== false) {
                                    unset($values[$index]);
                                }
                            }
                            $value = $values;
                        } else {
                            if (isset($data['enabled'])) {
                                $value = $enabled;
                            }
                        }
                        update_option($option, $value);
                        do_action($option, $value);
                    }
                    break;
                case 'access_request' :
                    if (Helper::isEnabled('enable_access_request', 'settings')) {
                        $emailAddress = (isset($data['email']) && is_email($data['email'])) ? $data['email'] : false;
                        $consent = (isset($data['consent'])) ? filter_var($data['consent'], FILTER_VALIDATE_BOOLEAN) : false;

                        if (!$emailAddress) {
                            $output['error'] = __('Missing or incorrect email address.', WP_GDPR_C_SLUG);
                        }

                        if (!$consent) {
                            $output['error'] = __('You need to accept the privacy checkbox.', WP_GDPR_C_SLUG);
                        }

                        // Let's do this!
                        if (empty($output['error'])) {
                            if (!AccessRequest::getInstance()->existsByEmailAddress($emailAddress, true)) {
                                $request = new AccessRequest();
                                $request->setSiteId(get_current_blog_id());
                                $request->setEmailAddress($emailAddress);
                                $request->setSessionId(SessionHelper::getSessionId());
                                $request->setIpAddress(Helper::getClientIpAddress());
                                $request->setExpired(0);
                                $id = $request->save();
                                if ($id !== false) {
                                    $page = Helper::getAccessRequestPage();
                                    if (!empty($page)) {
                                        $deleteRequestPage = sprintf(
                                            '<a target="_blank" href="%s">%s</a>',
                                            add_query_arg(
                                                array(
                                                    'wpgdprc' => base64_encode(serialize(array(
                                                        'email' => $request->getEmailAddress(),
                                                        'sId' => $request->getSessionId()
                                                    )))
                                                ),
                                                get_permalink($page)
                                            ),
                                            __('page', WP_GDPR_C_SLUG)
                                        );
                                        $siteName = Helper::getSiteData('blogname', $request->getSiteId());
                                        $siteEmail = Helper::getSiteData('admin_email', $request->getSiteId());
                                        $siteUrl = Helper::getSiteData('siteurl', $request->getSiteId());
                                        $subject = apply_filters(
                                            'wpgdprc_access_request_mail_subject',
                                            sprintf(__('%s - Your data request', WP_GDPR_C_SLUG), $siteName),
                                            $request,
                                            $siteName
                                        );

                                        $message = sprintf(
                                            __('You have requested to access your data on %s.', WP_GDPR_C_SLUG),
                                            sprintf('<a target="_blank" href="%s">%s</a>', $siteUrl, $siteName)
                                        ) . '<br /><br />';
                                        $message .= sprintf(
                                            __('Please visit this %s to view the data linked to the email address %s.', WP_GDPR_C_SLUG),
                                            $deleteRequestPage,
                                            $emailAddress
                                        ) . '<br /><br />';
                                        $message .= __('This page is available for 24 hours and can only be reached from the same device, IP address and browser session you requested from.', WP_GDPR_C_SLUG)  . '<br /><br />';
                                        $message .= sprintf(
                                            __('If your link is invalid you can fill in a new request after 24 hours: %s.', WP_GDPR_C_SLUG),
                                            sprintf(
                                                '<a target="_blank" href="%s">%s</a>',
                                                get_permalink($page),
                                                get_the_title($page)
                                            )
                                        );
                                        $message = apply_filters('wpgdprc_access_request_mail_content', $message, $request, $deleteRequestPage);
                                        $headers = array(
                                            'Content-Type: text/html; charset=UTF-8',
                                            "From: $siteName <$siteEmail>"
                                        );
                                        $response = wp_mail($emailAddress, $subject, $message, $headers);
                                        if ($response !== false) {
                                            $output['message'] = __('Success. You will receive an email with your data shortly.', WP_GDPR_C_SLUG);
                                        }
                                    }
                                } else {
                                    $output['error'] = __('Something went wrong while saving the request. Please try again.', WP_GDPR_C_SLUG);
                                }
                            } else {
                                $output['error'] = __('You have already requested your data. Please check your mailbox. After 24 hours you can put in a new request.', WP_GDPR_C_SLUG);
                            }
                        }
                    }
                    break;
                case 'delete_request' :
                    if (Helper::isEnabled('enable_access_request', 'settings')) {
                        $session = (isset($data['session'])) ? esc_html($data['session']) : '';
                        $settings = (isset($data['settings']) && is_array($data['settings'])) ? $data['settings'] : array();
                        $type = (isset($settings['type']) && in_array($settings['type'], Data::getPossibleDataTypes())) ? $settings['type'] : '';
                        $value = (isset($data['value']) && is_numeric($data['value'])) ? (int)$data['value'] : 0;

                        if (empty($session)) {
                            $output['error'] = __('Missing session.', WP_GDPR_C_SLUG);
                        }

                        if (empty($type)) {
                            $output['error'] = __('Missing or invalid type.', WP_GDPR_C_SLUG);
                        }

                        if ($value === 0) {
                            $output['error'] = __('No value selected.', WP_GDPR_C_SLUG);
                        }

                        // Let's do this!
                        if (empty($output['error'])) {
                            $accessRequest = unserialize(base64_decode($session));
                            $accessRequest = (!empty($accessRequest)) ? AccessRequest::getInstance()->getByEmailAddressAndSessionId($accessRequest['email'], $accessRequest['sId']) : false;
                            if ($accessRequest !== false) {
                                if (
                                    SessionHelper::checkSession($accessRequest->getSessionId()) &&
                                    Helper::checkIpAddress($accessRequest->getIpAddress())
                                ) {
                                    $request = new DeleteRequest();
                                    $request->setSiteId(get_current_blog_id());
                                    $request->setAccessRequestId($accessRequest->getId());
                                    $request->setSessionId($accessRequest->getSessionId());
                                    $request->setIpAddress($accessRequest->getIpAddress());
                                    $request->setDataId($value);
                                    $request->setType($type);
                                    $id = $request->save();
                                    if ($id === false) {
                                        $output['error'] = __('Something went wrong while saving this request. Please try again.', WP_GDPR_C_SLUG);
                                    } else {
                                        $siteName = Helper::getSiteData('blogname', $request->getSiteId());
                                        $siteEmail = Helper::getSiteData('admin_email', $request->getSiteId());
                                        $siteUrl = Helper::getSiteData('siteurl', $request->getSiteId());
                                        $adminPage = sprintf(
                                            '<a target="_blank" href="%s">%s</a>',
                                            Helper::getPluginAdminUrl('requests'),
                                            __('Requests', WP_GDPR_C_SLUG)
                                        );
                                        $subject = apply_filters(
                                            'wpgdprc_delete_request_admin_mail_subject',
                                            sprintf(__('%s - New anonymise request', WP_GDPR_C_SLUG), $siteName),
                                            $request,
                                            $siteName
                                        );
                                        $message = sprintf(
                                            __('You have received a new anonymise request on %s.', WP_GDPR_C_SLUG),
                                            sprintf('<a target="_blank" href="%s">%s</a>', $siteUrl, $siteName)
                                        ) . '<br /><br />';
                                        $message .= sprintf(
                                            __('You can manage this request in the admin panel: %s', WP_GDPR_C_SLUG),
                                            $adminPage
                                        );
                                        $message = apply_filters('wpgdprc_delete_request_admin_mail_content', $message, $request, $adminPage);
                                        $headers = array(
                                            'Content-Type: text/html; charset=UTF-8',
                                            "From: $siteName <$siteEmail>"
                                        );
                                        wp_mail($siteEmail, $subject, $message, $headers);
                                    }
                                } else {
                                    $output['error'] = __('Session doesn\'t match.', WP_GDPR_C_SLUG);
                                }
                            } else {
                                $output['error'] = __('No session found.', WP_GDPR_C_SLUG);
                            }
                        }
                    }
                    break;
            }
        }

        header('Content-type: application/json');
        echo json_encode($output);
        die();
    }

    public function processDeleteRequest() {
        check_ajax_referer('wpgdprc', 'security');

        $output = array(
            'message' => '',
            'error' => '',
        );

        if (!Helper::isEnabled('enable_access_request', 'settings')) {
            $output['error'] = __('The access request functionality is not enabled.', WP_GDPR_C_SLUG);
        }

        $data = (isset($_POST['data']) && (is_array($_POST['data']) || is_string($_POST['data']))) ? $_POST['data'] : false;
        if (is_string($data)) {
            $data = json_decode(stripslashes($data), true);
        }
        $id = (isset($data['id']) && is_numeric($data['id'])) ? absint($data['id']) : 0;

        if (!$data) {
            $output['error'] = __('Missing data.', WP_GDPR_C_SLUG);
        }

        if ($id === 0 || !DeleteRequest::getInstance()->exists($id)) {
            $output['error'] = __('This request doesn\'t exist.', WP_GDPR_C_SLUG);
        }

        // Let's do this!
        if (empty($output['error'])) {
            $request = new DeleteRequest($id);
            if (!$request->getProcessed()) {
                switch ($request->getType()) {
                    case 'user' :
                        if (current_user_can('edit_users')) {
                            $date = Helper::localDateTime(time());
                            $result = wp_update_user(array(
                                'ID' => $request->getDataId(),
                                'display_name' => 'DISPLAY_NAME',
                                'nickname' => 'NICKNAME',
                                'first_name' => 'FIRST_NAME',
                                'last_name' => 'LAST_NAME',
                                'user_email' => $request->getDataId() . '.' . $date->format('Ymd') . '.' . $date->format('His') . '@example.org'
                            ));
                            if (is_wp_error($result)) {
                                $output['error'] = __('This user doesn\'t exist.', WP_GDPR_C_SLUG);
                            } else {
                                $request->setProcessed(1);
                                $request->save();
                            }
                        } else {
                            $output['error'] = __('You\'re not allowed to edit users.', WP_GDPR_C_SLUG);
                        }
                        break;
                    case 'comment' :
                        if (current_user_can('edit_posts')) {
                            $date = Helper::localDateTime(time());
                            $result = wp_update_comment(array(
                                'comment_ID' => $request->getDataId(),
                                'comment_author' => 'NAME',
                                'comment_author_email' => $request->getDataId() . '.' . $date->format('Ymd') . '.' . $date->format('His') . '@example.org',
                                'comment_author_IP' => '127.0.0.1'
                            ));
                            if ($result === 0) {
                                $output['error'] = __('This comment doesn\'t exist.', WP_GDPR_C_SLUG);
                            } else {
                                $request->setProcessed(1);
                                $request->save();
                            }
                        } else {
                            $output['error'] = __('You\'re not allowed to edit comments.', WP_GDPR_C_SLUG);
                        }
                        break;
                    case 'woocommerce_order' :
                        if (current_user_can('edit_shop_orders')) {
                            $date = Helper::localDateTime(time());
                            $userId = get_post_meta($request->getDataId(), '_customer_user', true);
                            update_post_meta($request->getDataId(), '_billing_first_name', 'FIRST_NAME');
                            update_post_meta($request->getDataId(), '_billing_last_name', 'LAST_NAME');
                            update_post_meta($request->getDataId(), '_billing_company', 'COMPANY_NAME');
                            update_post_meta($request->getDataId(), '_billing_address_1', 'ADDRESS_1');
                            update_post_meta($request->getDataId(), '_billing_address_2', 'ADDRESS_2');
                            update_post_meta($request->getDataId(), '_billing_postcode', 'ZIP_CODE');
                            update_post_meta($request->getDataId(), '_billing_city', 'CITY');
                            update_post_meta($request->getDataId(), '_billing_phone', 'PHONE_NUMBER');
                            update_post_meta($request->getDataId(), '_billing_email', $request->getDataId() . '.' . $date->format('Ymd') . '.' . $date->format('His') . '@example.org');
                            update_post_meta($request->getDataId(), '_shipping_first_name', 'FIRST_NAME');
                            update_post_meta($request->getDataId(), '_shipping_last_name', 'LAST_NAME');
                            update_post_meta($request->getDataId(), '_shipping_company', 'COMPANY_NAME');
                            update_post_meta($request->getDataId(), '_shipping_address_1', 'ADDRESS_1');
                            update_post_meta($request->getDataId(), '_shipping_address_2', 'ADDRESS_2');
                            update_post_meta($request->getDataId(), '_shipping_postcode', 'ZIP_CODE');
                            update_post_meta($request->getDataId(), '_shipping_city', 'CITY');
                            if (!empty($userId) && get_user_by('id', $userId) !== false) {
                                update_user_meta($userId, 'billing_first_name', 'FIRST_NAME');
                                update_user_meta($userId, 'billing_last_name', 'LAST_NAME');
                                update_user_meta($userId, 'billing_company', 'COMPANY_NAME');
                                update_user_meta($userId, 'billing_address_1', 'ADDRESS_1');
                                update_user_meta($userId, 'billing_address_2', 'ADDRESS_2');
                                update_user_meta($userId, 'billing_postcode', 'ZIP_CODE');
                                update_user_meta($userId, 'billing_city', 'CITY');
                                update_user_meta($userId, 'billing_phone', 'PHONE_NUMBER');
                                update_user_meta($userId, 'billing_email', $request->getDataId() . '.' . $date->format('Ymd') . '.' . $date->format('His') . '@example.org');
                                update_user_meta($userId, 'shipping_first_name', 'FIRST_NAME');
                                update_user_meta($userId, 'shipping_last_name', 'LAST_NAME');
                                update_user_meta($userId, 'shipping_company', 'COMPANY_NAME');
                                update_user_meta($userId, 'shipping_address_1', 'ADDRESS_1');
                                update_user_meta($userId, 'shipping_address_2', 'ADDRESS_2');
                                update_user_meta($userId, 'shipping_postcode', 'ZIP_CODE');
                                update_user_meta($userId, 'shipping_city', 'CITY');
                            }
                            $request->setProcessed(1);
                            $request->save();
                        } else {
                            $output['error'] = __('You\'re not allowed to edit WooCommerce orders.', WP_GDPR_C_SLUG);
                        }
                        break;
                }

                if (empty($output['error']) && $request->getProcessed()) {
                    $accessRequest = new AccessRequest($request->getAccessRequestId());
                    $siteName = Helper::getSiteData('blogname', $request->getSiteId());
                    $siteEmail = Helper::getSiteData('admin_email', $request->getSiteId());
                    $siteUrl = Helper::getSiteData('siteurl', $request->getSiteId());
                    $subject = apply_filters(
                        'wpgdprc_delete_request_mail_subject',
                        sprintf(__('%s - Your request', WP_GDPR_C_SLUG), $siteName),
                        $request,
                        $accessRequest
                    );
                    $message = sprintf(
                        __('We have successfully processed your request and your data has been anonymised on %s.', WP_GDPR_C_SLUG),
                        sprintf('<a target="_blank" href="%s">%s</a>', $siteUrl, $siteName)
                    ) . '<br /><br />';
                    $message .= __('The following has been processed:', WP_GDPR_C_SLUG) . '<br />';
                    $message .= sprintf('%s #%d with email address %s.', $request->getNiceTypeLabel(), $request->getDataId(), $accessRequest->getEmailAddress());
                    $message = apply_filters('wpgdprc_delete_request_mail_content', $message, $request, $accessRequest);
                    $headers = array(
                        'Content-Type: text/html; charset=UTF-8',
                        "From: $siteName <$siteEmail>"
                    );
                    $response = wp_mail($accessRequest->getEmailAddress(), $subject, $message, $headers);
                    if ($response !== false) {
                        $output['message'] = __('Successfully sent an confirmation mail to the user.', WP_GDPR_C_SLUG);
                    }
                }
            } else {
                $output['error'] = __('This request has already been processed.', WP_GDPR_C_SLUG);
            }
        }

        header('Content-type: application/json');
        echo json_encode($output);
        die();
    }

    /**
     * @param $value
     * @return mixed
     */
    private static function sanitizeValue($value) {
        if (is_numeric($value)) {
            $value = intval($value);
        }
        if (is_string($value)) {
            $value = esc_html($value);
        }
        return $value;
    }

    /**
     * @return null|Ajax
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}