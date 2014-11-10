<?php
/* ----------------------------------------------------------------------
   $Id: pm2checkout.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: pm2checkout.php,v 1.3 2002/11/19 01:34:56 dgw_ 
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


$aLang['module_payment_2checkout_text_title'] = '2CheckOut';
$aLang['module_payment_2checkout_text_description'] = 'Tarjeta de Credito para Pruebas:<br /><br />Numero: 4111111111111111<br />Caducidad: Cualquiera';
$aLang['module_payment_2checkout_text_type'] = 'Tipo:';
$aLang['module_payment_2checkout_text_credit_card_owner'] = 'Titular de la Tarjeta:';
$aLang['module_payment_2checkout_text_credit_card_owner_first_name'] = 'Nombre del Titular:';
$aLang['module_payment_2checkout_text_credit_card_owner_last_name'] = 'Apellidos del Titular:';
$aLang['module_payment_2checkout_text_credit_card_number'] = 'Numero de la Tarjeta:';
$aLang['module_payment_2checkout_text_credit_card_expires'] = 'Fecha de Caducidad:';
$aLang['module_payment_2checkout_text_credit_card_checknumber'] = 'Numero de comprobacion:';
$aLang['module_payment_2checkout_text_credit_card_checknumber_location'] = '(lo puede encontrar en la parte de atras de la tarjeta de credito)';
$aLang['module_payment_2checkout_text_js_cc_number'] = '* El numero de la tarjeta de credito debe tener al menos ' . CC_NUMBER_MIN_LENGTH . ' caracteres.\n';
$aLang['module_payment_2checkout_text_error_message'] = 'Ha ocurrido un error procesando su tarjeta de credito, por favor intentelo de nuevo.';
$aLang['module_payment_2checkout_text_error'] = 'Error de Tarjeta de Credito!';
?>
