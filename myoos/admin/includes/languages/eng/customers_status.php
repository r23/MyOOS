<?php
/**
   ----------------------------------------------------------------------
   $Id: customers_status.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers_status.php,v 1.1 2002/09/30
   ----------------------------------------------------------------------
   For Customers Status v3.x

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Customers Status');

define('TABLE_HEADING_CUSTOMERS_STATUS', 'Customers Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_CUSTOMERS_QTY_DISCOUNTS', 'Qty price Discount');
define('TABLE_HEADING_AMOUNT', 'Minimum Amount');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_CUSTOMERS_STATUS_NAME', 'Customers Status:');
define('TEXT_INFO_CUSTOMERS_STATUS_IMAGE', 'Customers Status Image:');
define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE', 'Discount (0 to 100):');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new customers status with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this order status?');
define('TEXT_INFO_HEADING_NEW_CUSTOMERS_STATUS', 'New Customers Status');
define('TEXT_INFO_HEADING_EDIT_CUSTOMERS_STATUS', 'Edit Customers Status');
define('TEXT_INFO_HEADING_DELETE_CUSTOMERS_STATUS', 'Delete Customers Status');

define('TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO', 'You can select an order total discout working with XMember Discount. This discount will apply only as Total Discount and will not change displayed price');
define('ENTRY_OT_XMEMBER', 'Order Total Discount:');

define('TEXT_INFO_CUSTOMERS_STATUS_MINIMUM_AMOUNT_OT_XMEMBER_INTRO', 'Minimum order before discount');
define('ENTRY_MINIMUM_AMOUNT_OT_XMEMBER', 'Minimum Amount');

define('TEXT_INFO_CUSTOMERS_STATUS_STAFFELPREIS_INTRO', 'You can select if this status allow price by quantity known as staffelpreise module. Order total can apply to staffelpreis');
define('ENTRY_STAFFELPREIS', 'Qty price Discount:');

define('TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_INTRO', 'You can set for this customers status the payment method.');
define('ENTRY_CUSTOMERS_STATUS_PAYMENT', 'Payment : ');

define('TEXT_INFO_CUSTOMERS_STATUS_PUBLIC_INTRO', 'You can set for this customers status if these status info will be displayed to customer or not in the account box. ');
define('ENTRY_CUSTOMERS_STATUS_PUBLIC', 'Status Public : ');

define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_INTRO', 'You can set for this customers status if the price will be displayed or not. ');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE', 'Show Price : ');
define('TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE__TAX_INTRO', 'You can set for this customers status if the price will be displayed including tax or not. ');
define('ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX', 'Price with Tax : ');

define('ERROR_REMOVE_DEFAULT_CUSTOMER_STATUS', 'Error: The default customer status can not be removed. Please set another customer status as default, and try again.');
define('ERROR_STATUS_USED_IN_CUSTOMERS', 'Error: This order status is currently used in customers.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Error: This order status is currently used in the order status history.');
