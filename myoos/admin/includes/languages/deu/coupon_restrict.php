<?php
/**
   ----------------------------------------------------------------------
   $Id: coupon_restrict.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: coupon_restrict.php,v 1.1.2.1 2003/05/10 16:10:21 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('TOP_BAR_TITLE', 'Statistik');
define('HEADING_TITLE', 'Gutscheine');
define('HEADING_TITLE_STATUS', 'Status : ');
define('TEXT_CUSTOMER', 'Customer:');
define('TEXT_COUPON', 'Coupon Name');
define('TEXT_COUPON_ALL', 'All Coupons');
define('TEXT_COUPON_ACTIVE', 'Active Coupons');
define('TEXT_COUPON_INACTIVE', 'Inactive Coupons');
define('TEXT_SUBJECT', 'Subject:');
define('TEXT_FROM', 'From:');
define('TEXT_FREE_SHIPPING', 'Free Shipping');
define('TEXT_MESSAGE', 'Message:');
define('TEXT_SELECT_CUSTOMER', 'Select Customer');
define('TEXT_ALL_CUSTOMERS', 'All Customers');
define('TEXT_NEWSLETTER_CUSTOMERS', 'To All Newsletter Subscribers');
define('TEXT_CONFIRM_DELETE', 'Are you sure you want to delete this Coupon?');

define('TEXT_TO_REDEEM', 'You can redeem this coupon during checkout. Just enter the code in the box provided, and click on the redeem button.');
define('TEXT_IN_CASE', ' in case you have any problems. ');
define('TEXT_VOUCHER_IS', 'The coupon code is ');
define('TEXT_REMEMBER', 'Don\'t lose the coupon code, make sure to keep the code safe so you can benefit from this special offer.');
define('TEXT_VISIT', 'when you visit ' . OOS_HTTPS_SERVER . OOS_SHOP);
define('TEXT_ENTER_CODE', ' and enter the code ');

define('TABLE_HEADING_ACTION', 'Action');



define('NOTICE_EMAIL_SENT_TO', 'Notice: Email sent to: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Error: No customer has been selected.');
define('COUPON_NAME', 'Coupon Name');
//define('COUPON_VALUE', 'Coupon Value');
define('COUPON_AMOUNT', 'Coupon Amount');
define('COUPON_CODE', 'Coupon Code');
define('COUPON_STARTDATE', 'Start Date');
define('COUPON_FINISHDATE', 'End Date');
define('COUPON_FREE_SHIP', 'Free Shipping');
define('COUPON_DESC', 'Coupon Description');
define('COUPON_MIN_ORDER', 'Coupon Minimum Order');
define('COUPON_USES_COUPON', 'Uses per Coupon');
define('COUPON_USES_USER', 'Uses per Customer');
define('COUPON_PRODUCTS', 'Valid Product List');
define('COUPON_CATEGORIES', 'Valid Categories List');
define('VOUCHER_NUMBER_USED', 'Number Used');
define('DATE_CREATED', 'Date Created');
define('DATE_MODIFIED', 'Date Modified');
define('TEXT_HEADING_NEW_COUPON', 'Create New Coupon');
define('TEXT_NEW_INTRO', 'Please fill out the following information for the new coupon.<br>');


define('COUPON_NAME_HELP', 'A short name for the coupon');
define('COUPON_AMOUNT_HELP', 'The value of the discount for the coupon, either fixed or add a % on the end for a percentage discount.');
define('COUPON_CODE_HELP', 'You can enter your own code here, or leave blank for an auto generated one.');
define('COUPON_STARTDATE_HELP', 'The date the coupon will be valid from');
define('COUPON_FINISHDATE_HELP', 'The date the coupon expires');
define('COUPON_FREE_SHIP_HELP', 'The coupon gives free shiiping on an order. Note. This overides the coupon_amount figure but respects the minimum order value');
define('COUPON_DESC_HELP', 'A description of the coupon for the customer');
define('COUPON_MIN_ORDER_HELP', 'The minimum order value before the coupon is valid');
define('COUPON_USES_COUPON_HELP', 'The maximum number of times the coupon can be used, leave blank if you want no limit.');
define('COUPON_USES_USER_HELP', 'Number of times a user can use the coupon, leave blank for no limit.');
define('COUPON_PRODUCTS_HELP', 'A comma separated list of product_ids that this coupon can be used with. Leave blank for no restrictions.');
define('COUPON_CATEGORIES_HELP', 'A comma separated list of cpaths that this coupon can be used with, leave blank for no restrictions.');
