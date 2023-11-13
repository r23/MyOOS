<?php

namespace PayPal\Core;

/**
 * Class PayPalConstants
 * Placeholder for Paypal Constants
 *
 * @package PayPal\Core
 */
class PayPalConstants
{
    final public const SDK_NAME = 'PayPal-PHP-SDK';
    final public const SDK_VERSION = '1.14.0';

    /**
     * Approval URL for Payment
     */
    final public const APPROVAL_URL = 'approval_url';

    final public const REST_SANDBOX_ENDPOINT = "https://api.sandbox.paypal.com/";
    final public const OPENID_REDIRECT_SANDBOX_URL = "https://www.sandbox.paypal.com";

    final public const REST_LIVE_ENDPOINT = "https://api.paypal.com/";
    final public const OPENID_REDIRECT_LIVE_URL = "https://www.paypal.com";
}
