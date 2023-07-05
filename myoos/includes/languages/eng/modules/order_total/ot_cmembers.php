<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_cmembers.php,v 1.3 2007/06/14 16:16:10 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_gv.php,v 1.0 2002/04/03 23:09:49 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_CMEMBERS_STATUS_TITLE', 'Display Total');
define('MODULE_CMEMBERS_STATUS_DESC', 'Do you want to enable the Order Discount?');

define('MODULE_CMEMBERS_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_CMEMBERS_SORT_ORDER_DESC', 'Sort order of display.');

define('MODULE_CMEMBERS_CART_COUNT_TITLE', 'Cart Count');
define('MODULE_CMEMBERS_CART_COUNT_DESC', 'Qty');

define('MODULE_CMEMBERS_OT_DISCOUNT_TITLE', 'Discount');
define('MODULE_CMEMBERS_OT_DISCOUNT_DESC', 'Discount');

define('MODULE_CMEMBERS_INC_SHIPPING_TITLE', 'Include Shipping');
define('MODULE_CMEMBERS_INC_SHIPPING_DESC', 'Include Shipping in calculation');

define('MODULE_CMEMBERS_INC_TAX_TITLE', 'Include Tax');
define('MODULE_CMEMBERS_INC_TAX_DESC', 'Include Tax in calculation');

define('MODULE_CMEMBERS_CALC_TAX_TITLE', 'Calculate Tax');
define('MODULE_CMEMBERS_CALC_TAX_DESC', 'Re-calculate Tax on discounted amount.');

$aLang['module_cmembers_title'] = 'Members Qty Discount';
$aLang['module_cmembers_description'] = 'Members Qty Discount';
$aLang['shipping_not_included'] = ' [Shipping not included]';
$aLang['tax_not_included'] = ' [Tax not included]';
