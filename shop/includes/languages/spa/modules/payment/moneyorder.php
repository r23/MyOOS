<?php
/* ----------------------------------------------------------------------
   $Id: moneyorder.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: moneyorder.php,v 1.7 2003/01/24 21:36:05 thomasamoulton 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('MODULE_PAYMENT_MONEYORDER_STATUS_TITLE', 'Enable Check/Money Order Module');
define('MODULE_PAYMENT_MONEYORDER_STATUS_DESC', 'Do you want to accept Check/Money Order payments?');

define('MODULE_PAYMENT_MONEYORDER_PAYTO_TITLE', 'Make Payable to:');
define('MODULE_PAYMENT_MONEYORDER_PAYTO_DESC', 'Who should payments be made payable to?');

define('MODULE_PAYMENT_MONEYORDER_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_MONEYORDER_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_MONEYORDER_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_MONEYORDER_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_MONEYORDER_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');


$aLang['module_payment_moneyorder_text_title'] = 'Cheque/Transferencia Bancaria';
$aLang['module_payment_moneyorder_text_description'] = 'Pagadero a:&nbsp;' . MODULE_PAYMENT_MONEYORDER_PAYTO . '<br /><br />Enviar a<br />' . nl2br(STORE_NAME_ADDRESS) . '<br /><br />' . '&nbsp;Su pedido se enviar�en cuanto se reciba el pago.';
$aLang['module_payment_moneyorder_text_email_footer'] = "Pagadero a: ". MODULE_PAYMENT_MONEYORDER_PAYTO . "\n\nEnviar a\n" . STORE_NAME_ADDRESS . "\n\n" . 'Su pedido se enviar�en cuanto se reciba el pago.';
?>
