<?php
/* ----------------------------------------------------------------------
   $Id: ipayment.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: ipayment.php,v 1.5 2002/11/19 01:34:56 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_IPAYMENT_STATUS_TITLE', 'Enable iPayment Module');
define('MODULE_PAYMENT_IPAYMENT_STATUS_DESC', 'Do you want to accept iPayment payments?');

define('MODULE_PAYMENT_IPAYMENT_ID_TITLE', 'Account Number');
define('MODULE_PAYMENT_IPAYMENT_ID_DESC', 'The account number used for the iPayment service');

define('MODULE_PAYMENT_IPAYMENT_USER_ID_TITLE', 'User ID');
define('MODULE_PAYMENT_IPAYMENT_USER_ID_DESC', 'The user ID for the iPayment service');

define('MODULE_PAYMENT_IPAYMENT_PASSWORD_TITLE', 'User Password');
define('MODULE_PAYMENT_IPAYMENT_PASSWORD_DESC', 'The user password for the iPayment service');

define('MODULE_PAYMENT_IPAYMENT_CURRENCY_TITLE', 'Transaction Currency');
define('MODULE_PAYMENT_IPAYMENT_CURRENCY_DESC', 'The currency to use for credit card transactions');

define('MODULE_PAYMENT_IPAYMENT_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_IPAYMENT_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_IPAYMENT_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_IPAYMENT_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_IPAYMENT_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_IPAYMENT_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');


$aLang['module_payment_ipayment_text_title'] = 'iPayment';
$aLang['module_payment_ipayment_text_description'] = 'Tarjeta de Credito para Pruebas:<br /><br />Numero: 4111111111111111<br />Caducidad: Cualquiera';
$aLang['ipayment_error_heading'] = 'Ha ocurrido un error procesando su tarjeta de credito';
$aLang['ipayment_error_message'] = 'Revise los datos de su tarjeta de credito!';
$aLang['module_payment_ipayment_text_credit_card_owner'] = 'Titular de la Tarjeta:';
$aLang['module_payment_ipayment_text_credit_card_number'] = 'Numero de la Tarjeta:';
$aLang['module_payment_ipayment_text_credit_card_expires'] = 'Fecha de Caducidad:';
$aLang['module_payment_ipayment_text_credit_card_checknumber'] = 'Numero de Comprobacion:';
$aLang['module_payment_ipayment_text_credit_card_checknumber_location'] = '(lo puede encontrar en la parte de atras de la tarjeta de credito)';

$aLang['module_payment_ipayment_text_js_cc_owner'] = '* El nombre del titular de la tarjeta de credito debe de tener al menos ' . CC_OWNER_MIN_LENGTH . ' caracteres.\n';
$aLang['module_payment_ipayment_text_js_cc_number'] = '* El numero de la tarjeta de credito debe tener al menos ' . CC_NUMBER_MIN_LENGTH . ' caracteres.\n';
?>
