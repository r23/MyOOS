<?php
/**
   ----------------------------------------------------------------------
   $Id: products.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.24 2002/08/17 09:43:33 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'New Product');

define('TEXT_NEW_PRODUCT', 'New Product in &quot;%s&quot;');
define('TEXT_EDIT_PRODUCT', 'Product in &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Products');
define('TEXT_PRODUCTS_DATA', 'Productsdata');
define('TEXT_PRODUCTS_INFORMATION_OBLIGATIONS', 'Information obligations');
define('TEXT_HEADER_INFORMATION_OBLIGATIONS', 'Information obligations');
define('TEXT_OLD_ELECTRICAL_EQUIPMENT_OBLIGATIONS', 'Rücknahmepflicht für Elektroaltgeräte');
define('TEXT_OLD_ELECTRICAL_EQUIPMENT_OBLIGATIONS_NOTE', 'Note text about obligation to take back');
define('TEXT_OFFER_B_WARE_INFO', 'Used goods (B-goods)');
define('TEXT_OFFER_B_WARE_INFO_NOTE', 'Note text about used goods (B-goods)');

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

define('TEXT_PRODUCTS_BASE_PRICE', 'Base Price ');
define('TEXT_PRODUCTS_BASE_UNIT', 'Base Price Units:');
define('TEXT_PRODUCTS_BASE_PRICE_FACTOR', 'Factor to calculate Base Price:');
define('TEXT_PRODUCTS_BASE_QUANTITY', 'Base Quantity:');
define('TEXT_PRODUCTS_PRODUCT_QUANTITY', 'Product Quantity:');
define('TEXT_PRODUCTS_PRODUCT_MINIMUM_ORDER', 'Minimum Order Quantity');
define('TEXT_PRODUCTS_PRODUCT_PACKAGING_UNIT', 'Packaging Unit');
define('TEXT_PRODUCTS_PRODUCT_MAXIMUM_ORDER', 'Maximum Order Quantity');

define('TEXT_PRODUCTS_UNIT', 'Product Unit');

define('TEXT_SOCIAL', 'Social');
define('TEXT_HEADER_FACEBOOK', 'Facebook');
define('TEXT_HEADER_TWITTER', 'Twitter');
define('TEXT_TITLE', 'Title:');
define('TEXT_DESCRIPTION', 'Description:');
define('TEXT_DATA_FROM_FACEBOOK', 'Use Data from Facebook Tab?');

define('TEXT_PRODUCTS_IMAGE_REMOVE', '<b>Remove</b> this Image from this Product?');
define('TEXT_PRODUCTS_BUTTON_DELETE', '<b>Delete</b> this Image from the Server?');
define('TEXT_ADD_MORE_UPLOAD', 'Add more upload boxes');
define('TEXT_NOT_RELOAD', 'Does not reload!');

define('TEXT_INFO_DETAILS', 'Details');
define('TEXT_INFO_PREVIEW', 'Preview');

define('ENTRY_STATUS', 'Status:');
define('TEXT_PRODUCTS_STATUS', 'Products Status:');
define('TEXT_CATEGORIES', 'Categories');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Date Available:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'not available');
define('TEXT_PRODUCTS_MANUFACTURER', 'Products Manufacturer:');
define('TEXT_PRODUCTS_NAME', 'Products Name:');
define('TEXT_PRODUCTS_TITLE', 'Products Title for SEO:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Products Description:');
define('TEXT_PRODUCTS_SHORT_DESCRIPTION', 'Short Products Description:');
define('TEXT_PRODUCTS_ESSENTIAL_CHARACTERISTICS', 'The essential characteristics:');
define('TEXT_PRODUCTS_DESCRIPTION_META', 'Products description for Description TAG (max. 250 letters)');
define('TEXT_PRODUCTS_QUANTITY', 'Products Quantity:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Products Reorder Level:');
define('TEXT_REPLACEMENT_PRODUCT', 'Replacement Product:');
define('TEXT_PRODUCTS_MODEL', 'Products Model:');
define('TEXT_PRODUCTS_EAN', 'EAN :');
define('TEXT_PRODUCTS_IMAGE', 'Products Image');
define('TEXT_PRODUCTS_URL', 'Products URL:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Products Price (Net):');
define('TEXT_PRODUCTS_PRICE_WITH_TAX', 'Products Price (Gross):');
define('TEXT_PRODUCTS_LIST_PRICE', 'Recommended retail price of the manufacturer (Net):');
define('TEXT_PRODUCTS_LIST_PRICE_WITH_TAX', 'recommended retail price of the manufacturer (Gross):');


define('TEXT_PRODUCTS_WEIGHT', 'Products Weight:');
define('TEXT_IMAGE_REMOVE', '<b>Remove</b> this Image from this product?');


define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link products in the same category.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_OUTOFSTOCK', 'Not enough items of this product in stock Available.');
define('ERROR_REPLACEMENT', 'A replacement product was specified. The status has been changed to : No longer available/there is a replacement product.');

define('TEXT_DISCOUNTS_TITLE', 'Quantity Discounts');
define('TEXT_DISCOUNTS_BREAKS', 'Breaks');
define('TEXT_DISCOUNTS_QTY', 'Quantity');
define('TEXT_DISCOUNTS_PRICE', 'Price (Net)');
define('TEXT_DISCOUNTS_PRICE_WITH_TAX', 'Price (Gross):');
