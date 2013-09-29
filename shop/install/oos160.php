<?php
/* ----------------------------------------------------------------------
   $Id: oos160.php 476 2013-07-13 08:22:48Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: pn64.php,v 1.45 2002/03/16 15:24:37 johnnyrocket
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

global $db, $prefix_table, $currentlang;

if (!$prefix_table == '') $prefix_table = $prefix_table . '_';

$table = $prefix_table . 'sessions';
$result = $db->Execute("DROP TABLE " . $table . "");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

include_once 'oostables160.php';

$table = $prefix_table . 'configuration';
$aKeys = array('EMAIL_FROM',
               'SEND_EMAILS',
               'SEND_EXTRA_ORDER_EMAILS_TO',
               'SEND_BANKINFO_TO_ADMIN',
               'EMAIL_TRANSPORT',
               'EMAIL_LINEFEED',
               'EMAIL_USE_HTML',
               'ENTRY_EMAIL_ADDRESS_CHECK',
               'OOS_META_LANGUAGE',
               'OOS_META_DETAILS',
			   'OOS_META_KEYWORDS',
               'DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE',
               'DOWNLOADS_CONTROLLER_ON_HOLD_MSG',
               'WEB_PRINTER',
               'WEB_PRINTER_EMAIL',
               'WEB_PRINTER_FTP',
               'WEB_PRINTER_XML',
               'PRINTER_EMAIL',
               'OOS_PRINTER_TEMP',
			   'OOS_GD_LIB_VERSION',
               'PRINTER_DELETE_FILE',
               'PRINTER_STORE_NAME',
               'PRINTER_STORE_STREET_ADDRESS',
               'PRINTER_STORE_STREET_POSTCODE',
               'PRINTER_STORE_STREET_CITY',
               'PRINTER_STORE_COUNTRY',
               'MAX_DISPLAY_MANUFACTURERS_IN_A_LIST',
               'MAX_MANUFACTURERS_LIST',
			   'STORE_NAME_ADDRESS',
			   'OOS_IMAGE_SWF',
			   'OOS_SWF_MOVIECLIP',
			   'OOS_SWF_BGCOLOUR_R',
			   'OOS_SWF_BGCOLOUR_G',
			   'OOS_SWF_BGCOLOUR_B',
			   'OOS_RANDOM_PICTURE_NAME',
               'MAX_DISPLAY_MANUFACTURER_NAME_LEN');

$db->Execute("DELETE FROM " . $table . " WHERE configuration_key in ('" . implode("', '", $aKeys) . "')");


$table = $prefix_table . 'admin_files';
$result = $db->Execute("DELETE FROM " . $table . " WHERE admin_files_name = 'backup'");

// campaigns
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('campaigns', 0, 6, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

// products_units.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_units', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

// products_attributes_add.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_attributes_add', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

// excel.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('export_excel', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('import_excel', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('export_googlebase', 0, 18, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (33, 'left', 1, 'skype', 'system', 1, 33, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\',),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (34, 'left', 1, 'ads', '', 1, 34, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\',),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (35, 'left', 1, 'myworld', '', 1, 35, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\',),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");


$table = $prefix_table . 'block';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `block_author_name` VARCHAR(32) NOT NULL AFTER `block_login_flag`");
$result = $db->Execute("ALTER TABLE " . $table . " ADD `block_author_www` VARCHAR( 255 ) NOT NULL AFTER `block_author_name`");
$result = $db->Execute("ALTER TABLE " . $table . " ADD `block_modules_group` VARCHAR( 32 ) DEFAULT 'block' NOT NULL AFTER `block_author_www`");

$result = $db->Execute("UPDATE " . $table . " SET block_author_name = 'OOS [OSIS Online Shop]' WHERE block_author_name = ''");
$result = $db->Execute("UPDATE " . $table . " SET block_author_www = 'http://www.oos-shop.de' WHERE block_author_www = ''");
$result = $db->Execute("UPDATE " . $table . " SET block_modules_group = 'block' WHERE block_modules_group = ''");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $prefix_table . 'block ' . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (33, 1, 'Skype')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (33, 2, 'Skype')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 1, 'Werbung')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 2, 'ads')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (35, 1, 'My World')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (35, 2, 'My World')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $prefix_table . 'block_info ' . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 8)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 8)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (35, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (35, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (35, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $prefix_table . "block_to_page_type " . UPDATED .'</font>';

$table = $prefix_table . 'categories';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `access` INT( 11 ) DEFAULT '0' NOT NULL AFTER `parent_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$table = $prefix_table . 'customers';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `customers_image` VARCHAR(64) AFTER `customers_lastname`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_languages_id, customers_status_name, customers_status_image, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 1, 'Admin', 'smile-yellow.gif', '0', '0.00', '0.00', '0', '1', '1', '1', '')") OR die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_languages_id, customers_status_name, customers_status_image, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 2, 'Admin', 'smile-yellow.gif', '0', '0.00', '0.00', '0', '1', '1', '1', '')") OR die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_languages_id, customers_status_name, customers_status_image, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 3, 'Admin', 'smile-yellow.gif', '0', '0.00', '0.00', '0', '1', '1', '1', '')") OR die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_languages_id, customers_status_name, customers_status_image, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 6, 'Admin', 'smile-yellow.gif', '0', '0.00', '0.00', '0', '1', '1', '1', '')") OR die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $prefix_table . "customers_status " . UPDATED .'</font>';


$table = $prefix_table . 'configuration';
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('SKYPE_ME', '', 1, 19, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('DEFAULT_PRODUCTS_UNITS_ID', '1', 6, 0, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('PRODUCT_LIST_UVP', '0', 8, 5, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('ACCOUNT_VAT_ID', 'true', 5, 9, NULL, " . $db->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('STORE_OWNER_VAT_ID', '', 1, 4, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('DEFAULT_CAMPAIGNS_ID', '1', 6, 0, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OOS_SMALL_IMAGE_WIDTH', '110',  21, 3, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OOS_SMALL_IMAGE_HEIGHT', '110', 21, 4, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");

$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('STORE_ADDRESS_STREET', 'Street', 1, 5, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('STORE_ADDRESS_POSTCODE', 'Postcode', 1, 6, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('STORE_ADDRESS_CITY', 'City', 1, 7, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('STORE_ADDRESS_COUNTRY', 'Country', 1, 8, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('STORE_ADDRESS_TELEPHONE_NUMBER', 'Telepfone', 1, 9, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('STORE_ADDRESS_EMAIL', 'service@localhost', 1, 10, NULL, " . $db->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");


$table = $prefix_table . 'customers';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `customers_language` VARCHAR(3) AFTER `customers_login`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `customers_vat_id` VARCHAR(20) AFTER `customers_default_address_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `customers_vat_id_status` TINYINT DEFAULT '0' NOTNULL AFTER `customers_vat_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'information';
$result = $db->Execute("ALTER TABLE " . $table . " DROP `information_name`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$table = $prefix_table . 'products';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_zoomify` VARCHAR(64) AFTER `products_subimage6`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_movie` VARCHAR(64) AFTER `products_subimage6`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_units_id` INT( 11 ) DEFAULT '0' NOT NULL AFTER `products_tax_class_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_quantity_order_min` `products_quantity_order_min` DECIMAL( 10, 2 ) DEFAULT '1' NOT NULL ");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

 $result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_discount1_qty` `products_discount1_qty` DECIMAL( 10, 2 ) DEFAULT '0' NOT NULL ");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_discount2_qty` `products_discount2_qty` DECIMAL( 10, 2 ) DEFAULT '0' NOT NULL ");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}


$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_discount3_qty` `products_discount3_qty` DECIMAL( 10, 2 ) DEFAULT '0' NOT NULL ");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_discount4_qty` `products_discount4_qty` DECIMAL( 10, 2 ) DEFAULT '0' NOT NULL ");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `permissions` TINYINT DEFAULT '0' NOTNULL AFTER `manufacturers_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_quantity_decimal` TINYINT DEFAULT '0' NOTNULL AFTER `products_ordered`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$table = $prefix_table . 'products_description';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_short_description` VARCHAR(400) AFTER `products_description`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}

$table = $prefix_table . 'customers_basket';
$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `customers_basket_quantity` `customers_basket_quantity` DECIMAL( 10, 2 ) NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'orders';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `campaigns` SMALLINT( 6 ) NOT NULL AFTER `date_purchased`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `customers_cid` TINYINT DEFAULT '0' NOTNULL AFTER `customers_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `customers_firstname`  VARCHAR(32) AFTER `customers_cid`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `customers_lastname`  VARCHAR(32) AFTER `customers_firstname`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `delivery_firstname`  VARCHAR(32) AFTER `customers_address_format_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `delivery_lastname`  VARCHAR(32) AFTER `delivery_firstname`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `billing_firstname`  VARCHAR(32) AFTER `delivery_address_format_id`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE " . $table . " ADD `billing_lastname`  VARCHAR(32) AFTER `billing_firstname`");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$table = $prefix_table . 'orders_products';
$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_quantity` `products_quantity` DECIMAL( 10, 2 ) NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

require 'configuration_upgrade.php';

