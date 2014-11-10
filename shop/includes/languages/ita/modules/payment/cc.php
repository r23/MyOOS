<?php
/* ----------------------------------------------------------------------
   $Id: cc.php,v 1.3 2007/06/12 17:30:00 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: cc.php,v 1.10 2002/11/01 05:14:11 hpdl 
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

define('MODULE_PAYMENT_CC_STATUS_TITLE', 'Enable Credit Card Module');
define('MODULE_PAYMENT_CC_STATUS_DESC', 'Do you want to accept credit card payments?');

define('MODULE_PAYMENT_CC_EMAIL_TITLE', 'Split Credit Card E-Mail Address');
define('MODULE_PAYMENT_CC_EMAIL_DESC', 'If an e-mail address is entered, the middle digits of the credit card number will be sent to the e-mail address (the outside digits are stored in the database with the middle digits censored)');

define('MODULE_PAYMENT_CC_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_CC_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_CC_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_CC_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_CC_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_CC_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');


$aLang['module_payment_cc_text_title'] = 'Credit Card';
$aLang['module_payment_cc_text_description'] = 'Credit Card Test Info:<br /><br />CC#: 4111111111111111<br />Expiry: Any';
$aLang['module_payment_cc_text_credit_card_type'] = 'Credit Card Type:';
$aLang['module_payment_cc_text_credit_card_owner'] = 'Credit Card Owner:';
$aLang['module_payment_cc_text_credit_card_number'] = 'Credit Card Number:';
$aLang['module_payment_cc_text_credit_card_expires'] = 'Credit Card Expiry Date:';
$aLang['module_payment_cc_text_js_cc_owner'] = '* The owner\'s name of the credit card must be at least ' . CC_OWNER_MIN_LENGTH . ' characters.\n';
$aLang['module_payment_cc_text_js_cc_number'] = '* The credit card number must be at least ' . CC_NUMBER_MIN_LENGTH . ' characters.\n';
$aLang['module_payment_cc_text_error'] = 'Credit Card Error!';

?>
