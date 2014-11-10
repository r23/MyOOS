<?php
/* ----------------------------------------------------------------------
   $Id: psigate.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: psigate.php,v 1.3 2002/11/19 01:34:56 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_PSIGATE_STATUS_TITLE', 'Enable PSiGate Module');
define('MODULE_PAYMENT_PSIGATE_STATUS_DESC', 'Do you want to accept PSiGate payments?');
      
define('MODULE_PAYMENT_PSIGATE_MERCHANT_ID_TITLE', 'Merchant ID');
define('MODULE_PAYMENT_PSIGATE_MERCHANT_ID_DESC', 'Merchant ID used for the PSiGate service');

define('MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE_TITLE', 'Transaction Mode');
define('MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE_DESC', 'Transaction mode to use for the PSiGate service');

define('MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE_TITLE', 'Transaction Type');
define('MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE_DESC', 'Transaction type to use for the PSiGate service');

define('MODULE_PAYMENT_PSIGATE_INPUT_MODE_TITLE', 'Credit Card Collection');
define('MODULE_PAYMENT_PSIGATE_INPUT_MODE_DESC', 'Should the credit card details be collected locally or remotely at PSiGate?');

define('MODULE_PAYMENT_PSIGATE_CURRENCY_TITLE', 'Transaction Currency');
define('MODULE_PAYMENT_PSIGATE_CURRENCY_DESC', 'The currency to use for credit card transactions');

define('MODULE_PAYMENT_PSIGATE_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_PSIGATE_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_PSIGATE_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_PSIGATE_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');


$aLang['module_payment_psigate_text_title'] = 'PSiGate';
$aLang['module_payment_psigate_text_description'] = 'Tarjeta de Credito para Pruebas:<br /><br />Numero: 4111111111111111<br />Caducidad: Cualquiera';
$aLang['module_payment_psigate_text_credit_card_owner'] = 'Titular de la Tarjeta:';
$aLang['module_payment_psigate_text_credit_card_number'] = 'Numero de la Tarjeta:';
$aLang['module_payment_psigate_text_credit_card_expires'] = 'Fecha de Caducidad:';
$aLang['module_payment_psigate_text_type'] = 'Tipo de Tarjeta:';
$aLang['module_payment_psigate_text_js_cc_number'] = '* El numero de la tarjeta de credito debe de tener al menos ' . CC_NUMBER_MIN_LENGTH . ' numeros.\n';
$aLang['module_payment_psigate_text_error_message'] = 'Ha ocurrido un error procesando su tarjeta de credito, por favor intentelo de nuevo.';
$aLang['module_payment_psigate_text_error'] = 'Error en Tarjeta de Credito!';
?>
