<?php
/**
   ----------------------------------------------------------------------
   $Id: categories.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

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

define('HEADING_TITLE', 'Categories / Products');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_GOTO', 'Go To:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categories / Products');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_MANUFACTURERS', 'Manufacturers');
define('TABLE_HEADING_PRODUCT_SORT', 'Sort Order');

define('TEXT_NEW_PRODUCT', 'New Product in &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Categories:');
define('TEXT_CATEGORY', 'Category');
define('TEXT_SUBCATEGORIES', 'Subcategories:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Price:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Tax Class:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Average Rating:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Quantity:');
define('TEXT_DATE_ADDED', 'Date Added:');
define('TEXT_DATE_AVAILABLE', 'Date Available:');
define('TEXT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Please insert a new category or product in&nbsp;<b>%s</b>');
define('TEXT_PRODUCT_MORE_INFORMATION', 'For more information, please visit this products <a href="http://%s" target="blank"><u>webpage</u></a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'This product was added to our catalog on %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'This product will be in stock on %s.');

define('TEXT_INFO_PERCENTAGE', 'Percentage:');
define('TEXT_INFO_EXPIRES_DATE', 'Expires At:');

define('TEXT_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_EDIT_CATEGORIES_ID', 'Category ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_EDIT_CATEGORIES_PAGE_TITLE', 'Meta Tag Titlel for SEO:');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Category Headline:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Category Description:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION_META', 'Meta Tag Description:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_EDIT_COLOR', 'Color:');
define('TEXT_EDIT_MENU_TYPE', 'Badge:');
define('TEXT_EDIT_NEW', 'NEW');
define('TEXT_EDIT_PROMO', 'PROMO');
define('TEXT_EDIT_SORT_ORDER', 'Sort Order:');
define('TEXT_EDIT_STATUS', 'Status:');
define('TEXT_TAX_INFO', ' ex VAT:');
define('TEXT_PRODUCTS_LIST_PRICE', 'List:');

define('TEXT_INFO_BANNER', 'Banner');
define('TEXT_INFO_DETAILS', 'Details');
define('TEXT_INFO_PREVIEW', 'Preview');
define('TEXT_INFO_PRIMARY', 'Primary');
define('TEXT_INFO_SLIDER', 'Slider');


define('TEXT_INFO_COPY_TO_INTRO', 'Please choose a new category you wish to copy this product to');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Current Categories:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'New Category');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Edit Category');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Delete Category');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Move Category');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Delete Product');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Move Product');
define('TEXT_INFO_HEADING_COPY_TO', 'Copy To');

define('TEXT_DELETE_CATEGORY_INTRO', 'Are you sure you want to delete this category?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Are you sure you want to permanently delete this product?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s (child-)categories still linked to this category!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> There are %s products still linked to this category!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_MOVE', 'Move <b>%s</b> to:');

define('TEXT_NEW_CATEGORY_INTRO', 'Please fill out the following information for the new category');
define('TEXT_CATEGORIES_NAME', 'Category Name:');
define('TEXT_CATEGORIES_IMAGE', 'Category Image:');
define('TEXT_ADD_MORE_UPLOAD', 'Add more upload boxes');
define('TEXT_NOT_RELOAD', 'Does not reload!');
define('TEXT_SORT_ORDER', 'Sort Order:');

define('TEXT_PRODUCTS_STATUS', 'Products Status:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Date Available:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'not available');
define('TEXT_PRODUCTS_MANUFACTURER', 'Products Manufacturer:');
define('TEXT_PRODUCTS_NAME', 'Products Name:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Products Description:');
define('TEXT_PRODUCTS_DESCRIPTION_META', 'Description of article for Description TAG (max. 250 letters)');
define('TEXT_PRODUCTS_QUANTITY', 'Products Quantity:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Products Reorder Level:');
define('TEXT_PRODUCTS_MODEL', 'Products Model:');
define('TEXT_PRODUCTS_IMAGE', 'Products Image:');
define('TEXT_PRODUCTS_URL', 'Products URL:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Products Price:');
define('TEXT_PRODUCTS_WEIGHT', 'Products Weight:');
define('TEXT_PRODUCTS_STATUS', 'Products Status:');

define('EMPTY_CATEGORY', 'Empty Category');

define('TEXT_HOW_TO_COPY', 'Copy Method:');
define('TEXT_COPY_AS_LINK', 'Link product');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicate product');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link products in the same category.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);

define('TEXT_ADD_SLAVE_PRODUCT', 'Enter in the Product ID to add this product as a slave:');
define('IMAGE_SLAVE', 'Slave Products');
define('TEXT_CURRENT_SLAVE_PRODUCTS', 'Current Slave products:');
define('BUTTON_DELETE_SLAVE', 'Delete this slave product');
define('TEXT_SUCCESSFULLY_SLAVE', 'This product was successfully added as a slave.');
define('TEXT_ERROR_SLAVE', 'This product does not exist or is already a slave.');

define('CAT_CATEGORY_ID_TEXT', 'ID # ');
define('CAT_PRODUCT_ID_TEXT', 'ID # ');
define('CAT_ATTRIBUTES_BASE_PRICE_TEXT', ' Attribute Base Price: ');
define('CAT_LIST_PRICE_TEXT', 'List:');
define('CAT_REBATE_PRICE_TEXT', 'Rebate: ');
define('CAT_QUANTITY_MIN_TEXT', 'Min: ');
define('CAT_QUANTITY_MIN_TEXT', 'Max: ');
define('CAT_QUANTITY_UNITS_TEXT', 'Units: ');

define('TEXT_COPY_ATTRIBUTES_ONLY', 'Only used for Duplicate Products ...');
define('TEXT_COPY_ATTRIBUTES', 'Copy Product Attribuites to Duplicate?');
define('TEXT_COPY_ATTRIBUTES_YES', 'Yes');
define('TEXT_COPY_ATTRIBUTES_NO', 'No');

define('TEXT_SOCIAL', 'Social');
define('TEXT_HEADER_FACEBOOK', 'Facebook');
define('TEXT_HEADER_TWITTER', 'Twitter');
define('TEXT_TITLE', 'Title:');
define('TEXT_DESCRIPTION', 'Description:');
define('TEXT_DATA_FROM_FACEBOOK', 'Use Data from Facebook Tab?');


define('TEXT_DATA', 'Data');
define('TEXT_IMAGES', 'Images');
define('TEXT_UPLOAD', 'File Upload');
define('TEXT_IMAGE_REMOVE', '<b>Remove</b> this Image from this category?');
