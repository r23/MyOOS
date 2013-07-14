<?php
/* ----------------------------------------------------------------------
   $Id: ot_gv.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_gv.php,v 1.2.2.4 2003/05/14 22:52:59 wilt 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_ORDER_TOTAL_GV_STATUS_TITLE', 'Display Total');
define('MODULE_ORDER_TOTAL_GV_STATUS_DESC', 'Do you want to display the Gift Voucher value?');

define('MODULE_ORDER_TOTAL_GV_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_ORDER_TOTAL_GV_SORT_ORDER_DESC', 'Sort order of display.');

define('MODULE_ORDER_TOTAL_GV_QUEUE_TITLE', 'Queue Purchases');
define('MODULE_ORDER_TOTAL_GV_QUEUE_DESC', 'Do you want to queue purchases of the Gift Voucher?');

define('MODULE_ORDER_TOTAL_GV_INC_SHIPPING_TITLE', 'Include Shipping');
define('MODULE_ORDER_TOTAL_GV_INC_SHIPPING_DESC', 'Include Shipping in calculation');

define('MODULE_ORDER_TOTAL_GV_INC_TAX_TITLE', 'Include Tax');
define('MODULE_ORDER_TOTAL_GV_INC_TAX_DESC', 'Include Tax in calculation.');

define('MODULE_ORDER_TOTAL_GV_CALC_TAX_TITLE', 'Re-calculate Tax');
define('MODULE_ORDER_TOTAL_GV_CALC_TAX_DESC', 'Re-Calculate Tax');

define('MODULE_ORDER_TOTAL_GV_TAX_CLASS_TITLE', 'Tax Class');
define('MODULE_ORDER_TOTAL_GV_TAX_CLASS_DESC', 'Use the following tax class when treating Gift Voucher as Credit Note.');

define('MODULE_ORDER_TOTAL_GV_CREDIT_TAX_TITLE', 'Credit including Tax');
define('MODULE_ORDER_TOTAL_GV_CREDIT_TAX_DESC', 'Add tax to purchased Gift Voucher when crediting to Account');


$aLang['module_order_total_gv_title'] = 'Gift Vouchers';
$aLang['module_order_total_gv_header'] = 'Gift Vouchers/Discount Coupons';
$aLang['module_order_total_gv_description'] = 'Gift Vouchers';
$aLang['shipping_not_included'] = ' [Shipping not included]';
$aLang['tax_not_included'] = ' [Tax not included]';
$aLang['module_order_total_gv_user_prompt'] = 'Tick to use Gift Voucher account balance ->&nbsp;';
$aLang['text_enter_gv_code'] = 'Enter Redeem Code&nbsp;&nbsp;';
?>
