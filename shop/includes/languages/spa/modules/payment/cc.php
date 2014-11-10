<?php
/* ----------------------------------------------------------------------
   $Id: cc.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: cc.php,v 1.10 2002/11/19 01:34:56 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
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


$aLang['module_payment_cc_text_title'] = 'Tarjeta de Credito';
$aLang['module_payment_cc_text_description'] = 'Tarjeta de Credito para Pruebas:<br /><br />Numero: 4111111111111111<br />Caducidad: Cualquiera';
$aLang['module_payment_cc_text_credit_card_type'] = 'Tipo de Tarjeta:';
$aLang['module_payment_cc_text_credit_card_owner'] = 'Titular de la Tarjeta:';
$aLang['module_payment_cc_text_credit_card_number'] = 'Numero de la Tarjeta:';
$aLang['module_payment_cc_text_credit_card_expires'] = 'Fecha de Caducidad:';
$aLang['module_payment_cc_text_js_cc_owner'] = '* El titular de la tarjeta de credito debe de tener al menos ' . CC_OWNER_MIN_LENGTH . ' letras.\n';
$aLang['module_payment_cc_text_js_cc_number'] = '* El numero de la tarjeta de credito debe de tener al menos ' . CC_NUMBER_MIN_LENGTH . ' numeros.\n';
$aLang['module_payment_cc_text_error'] = 'Error en Tarjeta de Credito!';
?>
