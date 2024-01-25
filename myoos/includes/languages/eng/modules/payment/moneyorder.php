<?php
/**
   ----------------------------------------------------------------------
   $Id: moneyorder.php,v 1.3 2007/06/14 16:15:58 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: moneyorder.php,v 1.6 2003/01/24 21:36:04 thomasamoulton
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


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

$aLang['module_payment_moneyorder_text_title'] = 'Check/Money Order';
$aLang['module_payment_moneyorder_text_description'] = 'Make payable to:&nbsp;' . (defined('MODULE_PAYMENT_MONEYORDER_PAYTO') ? nl2br((string) MODULE_PAYMENT_MONEYORDER_PAYTO) : '') . '<br />Send to:<br /><br />' . nl2br((string) STORE_OWNER) . '<br /><br />' . 'Your order will not ship until we receive payment!';
$aLang['module_payment_moneyorder_text_email_footer'] = "Make payable to: ". (defined('MODULE_PAYMENT_MONEYORDER_PAYTO') ? MODULE_PAYMENT_MONEYORDER_PAYTO : '') . "\n\nSend to:\n" . STORE_OWNER . "\n\n" . 'Your order will not ship until we receive payment';
