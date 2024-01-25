<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_coupon.php,v 1.3 2007/06/14 16:16:10 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_coupon.php,v 1.1.2.5 2003/05/14 22:52:59 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_ORDER_TOTAL_COUPON_STATUS_TITLE', 'Display Total');
define('MODULE_ORDER_TOTAL_COUPON_STATUS_DESC', 'Do you want to display the Discount Coupon value?');

define('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER_DESC', 'Sort order of display.');

define('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING_TITLE', 'Include Shipping');
define('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING_DESC', 'Include Shipping in calculation');

$aLang['module_order_total_coupon_title'] = 'Discount Coupons';
$aLang['module_order_total_coupon_header'] = 'Gift Vouchers/Discount Coupons';
$aLang['module_order_total_coupon_description'] = 'Discount Coupon';
$aLang['shipping_not_included'] = ' [Shipping not included]';

$aLang['module_order_total_coupon_user_prompt'] = '';
$aLang['error_no_invalid_redeem_coupon'] = 'Invalid Coupon Code';
$aLang['error_invalid_startdate_coupon'] = 'This coupon is not available yet';
$aLang['error_invalid_finisdate_coupon'] = 'This coupon has expired';
$aLang['error_invalid_uses_coupon'] = 'This coupon could only be used %s times.';
$aLang['error_invalid_uses_user_coupon'] = 'You have used the coupon the maximum number of times allowed per customer.';
$aLang['error_coupon_minimum_order'] = 'Please note the minimum order value of %s. Still%s and your voucher will be redeemed!';

$aLang['redeemed_coupon'] = 'a coupon worth ';
$aLang['redeemed_min_order'] = 'on orders over ';
$aLang['redeemed_restrictions'] = ' [Product-Category restrictions apply]';
$aLang['text_enter_coupon_code'] = 'Enter Redeem Code&nbsp;&nbsp;';
