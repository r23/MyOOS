<?php
/* ----------------------------------------------------------------------
   $Id: pm2checkout.php,v 1.3 2007/06/12 17:30:00 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: pm2checkout.php,v 1.3 2002/11/18 14:45:23 project3000 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_2CHECKOUT_STATUS_TITLE', 'Enable 2CheckOut Module');
define('MODULE_PAYMENT_2CHECKOUT_STATUS_DESC', 'Do you want to accept 2CheckOut payments?');

define('MODULE_PAYMENT_2CHECKOUT_LOGIN_TITLE', 'Login/Store Number');
define('MODULE_PAYMENT_2CHECKOUT_LOGIN_DESC', 'Login/Store Number used for the 2CheckOut service');

define('MODULE_PAYMENT_2CHECKOUT_TESTMODE_TITLE', 'Transaction Mode');
define('MODULE_PAYMENT_2CHECKOUT_TESTMODE_DESC', 'Transaction mode used for the 2Checkout service');


define('MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT_TITLE', 'Merchant Notifications');
define('MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT_DESC', 'Should 2CheckOut e-mail a receipt to the store owner?');

define('MODULE_PAYMENT_2CHECKOUT_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_2CHECKOUT_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_2CHECKOUT_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_2CHECKOUT_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');


$aLang['module_payment_2checkout_text_title'] = '2CheckOut';
$aLang['module_payment_2checkout_text_description'] = 'Credit Card Test Info:<br /><br />CC#: 4111111111111111<br />Expiry: Any';
$aLang['module_payment_2checkout_text_type'] = 'Type:';
$aLang['module_payment_2checkout_text_credit_card_owner'] = 'Credit Card Owner:';
$aLang['module_payment_2checkout_text_credit_card_owner_first_name'] = 'Credit Card Owner First Name:';
$aLang['module_payment_2checkout_text_credit_card_owner_last_name'] = 'Credit Card Owner Last Name:';
$aLang['module_payment_2checkout_text_credit_card_number'] = 'Credit Card Number:';
$aLang['module_payment_2checkout_text_credit_card_expires'] = 'Credit Card Expiry Date:';
$aLang['module_payment_2checkout_text_credit_card_checknumber'] = 'Credit Card Checknumber:';
$aLang['module_payment_2checkout_text_credit_card_checknumber_location'] = '(located at the back of the credit card)';
$aLang['module_payment_2checkout_text_js_cc_number'] = '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n';
$aLang['module_payment_2checkout_text_error_message'] = 'There has been an error processing your credit card. Please try again.';
$aLang['module_payment_2checkout_text_error'] = 'Credit Card Error!';

?>
