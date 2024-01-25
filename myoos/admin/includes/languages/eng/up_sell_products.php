<?php
/**
   ----------------------------------------------------------------------
   $Id: up_sell_products.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File:  xsell_products.php, v1  2002/09/11
   ----------------------------------------------------------------------
   Cross-Sell

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('UP_SELL_SUCCESS', 'UP Sell Items Successfully Update For UP Sell Product #'.$_GET['add_related_product_ID']);
define('SORT_UP_SELL_SUCCESS', 'Sort Order Successfully Update For UP Sell Product #'.$_GET['add_related_product_ID']);
define('HEADING_TITLE', 'UP-Sell (X-Sell) Admin');
define('TABLE_HEADING_PRODUCT_ID', 'Product Id');
define('TABLE_HEADING_PRODUCT_MODEL', 'Product Model');
define('TABLE_HEADING_PRODUCT_NAME', 'Product Name');
define('TABLE_HEADING_CURRENT_SELLS', 'Current UP-Sells');
define('TABLE_HEADING_UPDATE_SELLS', 'Update UP-Sells');
define('TABLE_HEADING_PRODUCT_IMAGE', 'Product Image');
define('TABLE_HEADING_PRODUCT_PRICE', 'Product Price');
define('TABLE_HEADING_UP_SELL_THIS', 'UP-Sell This?');
define('TEXT_EDIT_SELLS', 'Edit');
define('TEXT_SORT', 'Prioritize');
define('TEXT_SETTING_SELLS', 'Setting UP-Sells For');
define('TEXT_PRODUCT_ID', 'Product Id');
define('TEXT_MODEL', 'Model');
define('TABLE_HEADING_PRODUCT_SORT', 'Sort Order');
define('TEXT_IMAGE_NONEXISTENT', 'No Image');
define('TEXT_UP_SELL', 'UP-Sell');
