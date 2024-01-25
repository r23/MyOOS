<?php
/**
   ----------------------------------------------------------------------
   $Id: specials.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: specials.php,v 1.10 2002/03/16 15:07:21 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Specials');

define('TABLE_HEADING_PRODUCTS', 'Products');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Products Price');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_SPECIALS_PRODUCT', 'Product:');
define('TEXT_TAX_INFO', ' ex VAT:');
define('TEXT_SPECIALS_SPECIAL_PRICE', 'Special Price (Net):');
define('TEXT_SPECIALS_SPECIAL_PRICE_WITH_TAX', 'Special Price (Gross):');
define('TEXT_SPECIALS_CROSS_OUT_PRICE', 'Strike price:');
define('TEXT_SPECIALS_EXPIRES_DATE', 'Expiry Date:<br><small>(YYYY-MM-DD)</small>');
define('TEXT_SPECIALS_PRICE_TIP', '<b>Specials Notes:</b><ul><li>You can enter a percentage to deduct in the Specials Price (Net) field, for example: <b>20%</b></li><li>If you enter a new price, the decimal separator must be a \'.\' (decimal-point), example: <b>49.99</b></li></ul>');

define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_NEW_PRICE', 'New Price:');
define('TEXT_INFO_ORIGINAL_PRICE', 'Original Price:');
define('TEXT_INFO_PERCENTAGE', 'Percentage:');
define('TEXT_INFO_EXPIRES_DATE', 'Expires At:');
define('TEXT_INFO_STATUS_CHANGE', 'Status Change:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');

define('TEXT_INFO_HEADING_DELETE_SPECIALS', 'Delete Special');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete the special products price?');

define('TEXT_EXPIRES_DATE_ERROR', '<strong>Error:</strong> The validity date is missing!');
define('TEXT_PRODUCT_ERROR', '<strong>Error:</strong> The product has not yet been published in the online store for 30 days!');
define('TEXT_PRICE_ERROR', '<strong>Fehler:</strong> The price of the special offer is not lower than the lowest total price of the last 30 days!');
