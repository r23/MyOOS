<?php
/* ----------------------------------------------------------------------
   $Id: secpay.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: secpay.php,v 1.7 2002/11/19 01:34:56 dgw_ 
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
$aLang['module_payment_secpay_text_description'] = 'Tarjeta de Credito para Pruebas:<br /><br />Numero: 4444333322221111<br />Caducidad Cualquiera';
$aLang['module_payment_secpay_text_error'] = 'Error de Tarjeta de Credito!';
$aLang['module_payment_secpay_text_error_message'] = 'Ha ocurrido un error procesando su tarjeta de credito. Por favor, intentelo de nuevo.';
?>
