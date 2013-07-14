<?php
/* ----------------------------------------------------------------------
   $Id: secpay.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: secpay.php,v 1.7 2002/11/18 14:45:29 project3000 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('MODULE_PAYMENT_SECPAY_STATUS_TITLE', 'Enable SECpay Module');
define('MODULE_PAYMENT_SECPAY_STATUS_DESC', 'Do you want to accept SECPay payments?');

define('MODULE_PAYMENT_SECPAY_MERCHANT_ID_TITLE', 'Merchant ID');
define('MODULE_PAYMENT_SECPAY_MERCHANT_ID_DESC', 'Merchant ID to use for the SECPay service');

define('MODULE_PAYMENT_SECPAY_CURRENCY_TITLE', 'Transaction Currency');
define('MODULE_PAYMENT_SECPAY_CURRENCY_DESC', 'The currency to use for credit card transactions');

define('MODULE_PAYMENT_SECPAY_TEST_STATUS_TITLE', 'Transaction Mode');
define('MODULE_PAYMENT_SECPAY_TEST_STATUS_DESC', 'Transaction mode to use for the SECPay service');

define('MODULE_PAYMENT_SECPAY_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_SECPAY_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first');

define('MODULE_PAYMENT_SECPAY_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_SECPAY_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_SECPAY_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_SECPAY_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');


$aLang['module_payment_secpay_text_title'] = 'SECPay';
$aLang['module_payment_secpay_text_description'] = 'Credit Card Test Info:<br /><br />CC#: 4444333322221111<br />Expiry: Any';
$aLang['module_payment_secpay_text_error'] = 'Credit Card Error!';
$aLang['module_payment_secpay_text_error_message'] = 'There has been an error processing your credit card. Please try again.';
?>
