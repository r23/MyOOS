<?php
/* ----------------------------------------------------------------------
   $Id: products.php,v 1.1 2007/06/13 17:03:54 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.24 2002/08/17 09:43:33 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('TEXT_NEW_PRODUCT', 'New Product in &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Price:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Tax Class:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Average Rating:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Quantity:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_DATE_AVAILABLE', 'Date Available:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_PRODUCT_MORE_INFORMATION', 'For more information, please visit this products <a href="http://%s" target="blank"><u>webpage</u></a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'This product was added to our catalog on %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'This product will be in stock on %s.');

define('TEXT_TAX_INFO', ' ex VAT:');
define('TEXT_PRODUCTS_LIST_PRICE', 'List:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED', 'Max Discount Allowed:');

define('TEXT_PRODUCTS_BASE_PRICE', 'Base Price ');
define('TEXT_PRODUCTS_BASE_UNIT', 'Base Unit:');
define('TEXT_PRODUCTS_BASE_PRICE_FACTOR', 'Factor to calculate Base Price:');
define('TEXT_PRODUCTS_BASE_QUANTITY', 'Base Quantity:');
define('TEXT_PRODUCTS_PRODUCT_QUANTITY', 'Product Quantity:');
define('TEXT_PRODUCTS_DECIMAL_QUANTITY', 'Decimal Quantity');
define('TEXT_PRODUCTS_UNIT', 'Product Unit');

define('TEXT_PRODUCTS_IMAGE_REMOVE', '<b>Remove</b> this Image from this Product?');
define('TEXT_PRODUCTS_IMAGE_DELETE', '<b>Delete</b> this Image from the Server?');
define('TEXT_PRODUCTS_ZOOMIFY', 'Zoomify');

define('TEXT_PRODUCTS_STATUS', 'Products Status:');
define('TEXT_CATEGORIES', 'Categories:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Date Available:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'not available');
define('TEXT_PRODUCTS_MANUFACTURER', 'Products Manufacturer:');
define('TEXT_PRODUCTS_NAME', 'Products Name:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Products Description:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION_META', 'Category description for Description TAG (max. 250 letters)');
define('TEXT_EDIT_CATEGORIES_KEYWORDS_META', 'Category of search words for Keyword TAG (references by commaseparately - max. 250 letters)');
define('TEXT_PRODUCTS_QUANTITY', 'Products Quantity:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Products Reorder Level:');
define('TEXT_PRODUCTS_MODEL', 'Products Model:');
define('TEXT_PRODUCTS_EAN', 'EAN :');
define('TEXT_PRODUCTS_IMAGE', 'Products Image:');
define('TEXT_PRODUCTS_URL', 'Products URL:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Products Price:');
define('TEXT_PRODUCTS_WEIGHT', 'Products Weight:');
define('TEXT_PRODUCTS_SORT_ORDER', 'Sort Order:');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link products in the same category.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
?>
