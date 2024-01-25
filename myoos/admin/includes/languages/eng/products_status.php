<?php
/**
   ----------------------------------------------------------------------
   $Id: products.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.22 2002/08/17 09:43:33 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Products Status');

define('TABLE_HEADING_PRODUCTS_STATUS', 'Products Status');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_PRODUCTS_STATUS_NAME', 'Orders Status:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new products status with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this product status?');
define('TEXT_INFO_HEADING_NEW_PRODUCTS_STATUS', 'New Products Status');
define('TEXT_INFO_HEADING_EDIT_PRODUCTS_STATUS', 'Edit Products Status');
define('TEXT_INFO_HEADING_DELETE_PRODUCTS_STATUS', 'Delete Products Status');

define('ERROR_REMOVE_DEFAULT_ORDER_STATUS', 'Error: The default product status can not be removed. Please set another product status as default, and try again.');
define('ERROR_STATUS_USED_IN_PRODUCTS', 'Error: This product status is currently used in orders.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Error: This product status is currently used in the product status history.');
