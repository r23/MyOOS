<?php
/**
   ----------------------------------------------------------------------
   $Id: paypal.php,v 1.3 2007/06/14 16:15:58 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: paypal.php,v 1.7 2002/11/01 05:39:27 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_PAYMENT_PAYPAL_API_STATUS_TITLE', 'Enable PayPal Module');
define('MODULE_PAYMENT_PAYPAL_API_STATUS_DESC', 'Do you want to accept PayPal payments?');

define('MODULE_PAYMENT_PAYPAL_API_ID_TITLE', 'E-Mail-Address');
define('MODULE_PAYMENT_PAYPAL_API_ID_DESC', 'The e-mail address to use for the PayPal service');

define('MODULE_PAYMENT_PAYPAL_API_CLIENTID_TITLE', 'ClientId ID');
define('MODULE_PAYMENT_PAYPAL_API_CLIENTID_DESC', 'You will receive this data from  <a href="https://developer.paypal.com/developer/applications/">PayPal</a>');

define('MODULE_PAYMENT_PAYPAL_API_SECURE_TITLE', 'Security Key');
define('MODULE_PAYMENT_PAYPAL_API_SECURE_DESC', 'You will receive this data from <a href="https://developer.paypal.com/developer/applications/">PayPal</a>');

define('MODULE_PAYMENT_PAYPAL_API_MODE_TITLE', 'Transaction Server');
define('MODULE_PAYMENT_PAYPAL_API_MODE_DESC', 'Use the live or testing (sandbox) gateway server to process transactions?');

define('MODULE_PAYMENT_PAYPAL_API_CURRENCY_TITLE', 'Transaction Currency');
define('MODULE_PAYMENT_PAYPAL_API_CURRENCY_DESC', 'The currency to use for credit card transactions');

define('MODULE_PAYMENT_PAYPAL_API_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_PAYPAL_API_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_PAYPAL_API_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_PAYPAL_API_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_PAYPAL_API_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_PAYPAL_API_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

define('MODULE_PAYMENT_PAYPAL_API_ERROR', 'An error has occurred! Paypal is currently not available, choose another payment method.');

$aLang['module_payment_PAYPAL_API_text_title'] = 'PayPal';
$aLang['module_payment_PAYPAL_API_text_description'] = 'PayPal';
