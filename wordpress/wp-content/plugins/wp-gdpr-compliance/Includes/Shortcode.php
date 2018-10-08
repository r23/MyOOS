<?php

namespace WPGDPRC\Includes;

/**
 * Class Shortcode
 * @package WPGDPRC\Includes
 */
class Shortcode {
    /** @var null */
    private static $instance = null;

    /**
     * @return string
     */
    private static function getAccessRequestData() {
        $output = '';
        $request = (isset($_REQUEST['wpgdprc'])) ? unserialize(base64_decode(esc_html($_REQUEST['wpgdprc']))) : false;
        $request = (!empty($request)) ? AccessRequest::getInstance()->getByEmailAddressAndSessionId($request['email'], $request['sId']) : false;
        if ($request !== false) {
            if (
                SessionHelper::checkSession($request->getSessionId()) &&
                Helper::checkIpAddress($request->getIpAddress())
            ) {
                $data = new Data($request->getEmailAddress());
                $users = Data::getOutput($data->getUsers(), 'user', $request->getId());
                $comments = Data::getOutput($data->getComments(), 'comment', $request->getId());

                $output .= sprintf(
                    '<div class="wpgdprc-message wpgdprc-message--notice">%s</div>',
                    apply_filters('wpgdprc_the_content', Integration::getDeleteRequestFormExplanationText())
                );

                // WordPress Users
                $output .= sprintf('<h2 class="wpgdprc-title">%s</h2>', __('Users', WP_GDPR_C_SLUG));
                if (!empty($users)) {
                    $output .= $users;
                } else {
                    $output .= sprintf(
                        '<div class="wpgdprc-message wpgdprc-message--notice">%s</div>',
                        sprintf(
                            __('No users found with email address %s.', WP_GDPR_C_SLUG),
                            sprintf('<strong>%s</strong>', $request->getEmailAddress())
                        )
                    );
                }

                // WordPress Comments
                $output .= sprintf('<h2 class="wpgdprc-title">%s</h2>', __('Comments', WP_GDPR_C_SLUG));
                if (!empty($comments)) {
                    $output .= $comments;
                } else {
                    $output .= sprintf(
                        '<div class="wpgdprc-message wpgdprc-message--notice">%s</div>',
                        sprintf(
                            __('No comments found with email address %s.', WP_GDPR_C_SLUG),
                            sprintf('<strong>%s</strong>', $request->getEmailAddress())
                        )
                    );
                }

                // WooCommerce Orders
                if (in_array('woocommerce/woocommerce.php', Helper::getActivePlugins())) {
                    $woocommerceOrders = Data::getOutput($data->getWooCommerceOrders(), 'woocommerce_order', $request->getId());
                    $output .= sprintf('<h2 class="wpgdprc-title">%s</h2>', __('WooCommerce Orders', WP_GDPR_C_SLUG));
                    if (!empty($woocommerceOrders)) {
                        $output .= $woocommerceOrders;
                    } else {
                        $output .= sprintf(
                            '<div class="wpgdprc-message wpgdprc-message--notice">%s</div>',
                            sprintf(
                                __('No WooCommerce orders found with email address %s.', WP_GDPR_C_SLUG),
                                sprintf('<strong>%s</strong>', $request->getEmailAddress())
                            )
                        );
                    }
                }

                $output = apply_filters('wpgdprc_request_data', $output, $data, $request);
            } else {
                $accessRequestPage = Helper::getAccessRequestPage();
                $output .= sprintf(
                    '<div class="wpgdprc-message wpgdprc-message--error"><p>%s</p></div>',
                    sprintf(
                        __('<strong>ERROR</strong>: %s', WP_GDPR_C_SLUG),
                        sprintf(
                            '%s<br /><br />%s',
                            __('You are only able to view your data when visiting this page on the same device with the same IP and in the same browser session as when you performed your request. This is an extra security measure to keep your data safe.', WP_GDPR_C_SLUG),
                            sprintf(
                                __('If needed you can put in a new request after 24 hours here: %s.', WP_GDPR_C_SLUG),
                                sprintf(
                                    '<a target="_blank" href="%s">%s</a>',
                                    get_permalink($accessRequestPage),
                                    get_the_title($accessRequestPage)
                                )
                            )
                        )
                    )
                );
            }
        } else {
            $output .= __('This request is expired or doesn\'t exist.', WP_GDPR_C_SLUG);
        }
        return $output;
    }

    /**
     * @return string
     */
    public function accessRequestForm() {
        $output = '<div class="wpgdprc">';
        if (isset($_REQUEST['wpgdprc'])) {
            $output .= self::getAccessRequestData();
        } else {
            $output .= '<form class="wpgdprc-form wpgdprc-form--access-request" name="wpgdprc_form" method="POST">';
            $output .= apply_filters(
                'wpgdprc_request_form_email_field',
                sprintf(
                    '<p><input type="email" name="wpgdprc_email" id="wpgdprc-form__email" placeholder="%s" required /></p>',
                    apply_filters('wpgdprc_request_form_email_label', esc_attr__('Your Email Address', WP_GDPR_C_SLUG))
                )
            );
            $output .= apply_filters(
                'wpgdprc_request_form_consent_field',
                sprintf(
                    '<p><label><input type="checkbox" name="wpgdprc_consent" id="wpgdprc-form__consent" value="1" required /> %s</label></p>',
                    Integration::getAccessRequestFormCheckboxText()
                )
            );
            $output .= apply_filters(
                'wpgdprc_request_form_submit_field',
                sprintf(
                    '<p><input type="submit" name="wpgdprc_submit" value="%s" /></p>',
                    apply_filters('wpgdprc_request_form_submit_label', esc_attr__('Send', WP_GDPR_C_SLUG))
                )
            );
            $output .= '<div class="wpgdprc-message" style="display: none;"></div>';
            $output .= '</form>';
            $output = apply_filters('wpgdprc_request_form', $output);
        }
        $output .= '</div>';
        return $output;
    }

    /**
     * @param $attributes
     * @param string $label
     * @return string
     */
    public function consentsSettingsLink($attributes, $label = '') {
        $attributes = shortcode_atts(array(
            'class' => '',
        ), $attributes, 'wpgdprc_consents_settings_link');
        $label = (!empty($label)) ? esc_html($label) : __('My settings', WP_GDPR_C_SLUG);
        $classes = explode(',', $attributes['class']);
        $classes[] = 'wpgdprc-consents-settings-link';
        $classes = implode(' ', $classes);
        $output = sprintf(
            '<a class="%s" href="javascript:void(0);" data-micromodal-trigger="wpgdprc-consent-modal">%s</a>',
            esc_attr($classes),
            $label
        );
        return $output;
    }

    /**
     * @return null|Shortcode
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}