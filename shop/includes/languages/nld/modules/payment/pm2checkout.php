<?php
/* ----------------------------------------------------------------------
   $Id: pm2checkout.php,v 1.3 2007/08/04 04:52:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: pm2checkout.php,v 1.4 2002/11/01 22:19:27 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
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


$aLang['module_payment_2checkout_text_title'] = '2Checkout';
$aLang['module_payment_2checkout_text_description'] = 'Credietkaarttest info:<br /><br />CC#: 4111111111111111<br />Geldig tot: Altijd';
$aLang['module_payment_2checkout_text_type'] = 'Type:';
$aLang['module_payment_2checkout_text_credit_card_owner'] = 'Credietkaarteigenaar:';
$aLang['module_payment_2checkout_text_credit_card_owner_first_name'] = 'Credietkaarteigenaar voornaam:';
$aLang['module_payment_2checkout_text_credit_card_owner_last_name'] = 'Credietkaarteigenaar achternaam:';
$aLang['module_payment_2checkout_text_credit_card_number'] = 'Credietkaartnr.:';
$aLang['module_payment_2checkout_text_credit_card_expires'] = 'Geldig tot:';
$aLang['module_payment_2checkout_text_credit_card_checknumber'] = 'Kaart-testfnummer:';
$aLang['module_payment_2checkout_text_credit_card_checknumber_location'] = '(Op de kaartachterkant in handtekeningsveld)';
$aLang['module_payment_2checkout_text_js_cc_number'] = '* Het \'credietkaartnr.\' moet minstens uit ' . CC_NUMBER_MIN_LENGTH . ' cijfers bestaan.\n';
$aLang['module_payment_2checkout_text_error_message'] = 'Bij het controleren van uw credietkaart is een fout opgetreden! Probeer het a.u.b. nog een keer.';
$aLang['module_payment_2checkout_text_error'] = 'Fout bij de controle van de credietkaart!';
?>
