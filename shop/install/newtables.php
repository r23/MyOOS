<?php
/* ----------------------------------------------------------------------
   $Id: newtables.php 436 2013-06-22 14:39:19Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: oscommerce.sql,v 1.71 2003/02/14 05:58:35 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------

   File: newtables.php,v 1.40.2.1 2002/04/03 21:02:06 
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   Based on:
   PHP-NUKE Web Portal System - http://phpnuke.org/
   Thatware - http://thatware.org/
   ----------------------------------------------------------------------
   LICENSE

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License (GPL)
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   To read the license please visit http://www.gnu.org/copyleft/gpl.html
   ----------------------------------------------------------------------
   Original Author of file:
   Purpose of file: 
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

function dosql($table, $flds) {
   GLOBAL $db;

   $dict = NewDataDictionary($db);
   // $dict->debug = 1;
   $taboptarray = array('mysql' => 'ENGINE=MyISAM DEFAULT CHARSET=utf8;', 'REPLACE'); 

   $sqlarray = $dict->CreateTableSQL($table, $flds, $taboptarray);
   $dict->ExecuteSQLArray($sqlarray); 

   echo '<br><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $table . " " . MADE . '</font>';
}

function idxsql($idxname, $table, $idxflds) {
   GLOBAL $db;

   $dict = NewDataDictionary($db);

   $sqlarray = $dict->CreateIndexSQL($idxname, $table, $idxflds);
   $dict->ExecuteSQLArray($sqlarray);
}


$table = $prefix_table . 'address_book';
$flds = "
   customers_id I NOTNULL DEFAULT '0' PRIMARY,
   address_book_id I NOTNULL DEFAULT '1' PRIMARY,
   entry_gender C(1) NOTNULL,
   entry_company C(32),
   entry_owner C(32),
   entry_firstname C(32) NOTNULL,
   entry_lastname C(32) NOTNULL,
   entry_street_address C(64) NOTNULL,
   entry_suburb C(32),
   entry_postcode C(10) NOTNULL,
   entry_city C(32) NOTNULL,
   entry_state C(32),
   entry_country_id I DEFAULT '0' NOTNULL,
   entry_zone_id I DEFAULT '0' NOTNULL
";
dosql($table, $flds);



$table = $prefix_table . 'address_format';
$flds = "
  address_format_id I NOTNULL AUTO PRIMARY,
  address_format C(128) NOTNULL,
  address_summary C(48) NOTNULL
";
dosql($table, $flds);

$table = $prefix_table . 'admin';
$flds = "
  admin_id I NOTNULL AUTO PRIMARY,
  admin_groups_id I DEFAULT NULL,
  admin_gender C(1) NOTNULL,
  admin_firstname C(32) NOTNULL,
  admin_lastname C(32) NULL,
  admin_email_address C(96) NOTNULL,
  admin_telephone C(32) NOTNULL,
  admin_fax C(32),
  admin_password C(40) NOTNULL,
  admin_created T,
  admin_modified T,
  admin_logdate T,
  admin_lognum I NOTNULL DEFAULT '0'
";
dosql($table, $flds);

$idxname = 'idx_admin_id';
$idxflds = 'admin_id';
idxsql($idxname, $table, $idxflds);

$idxname = 'idx_admin_firstname';
$idxflds = 'admin_firstname';
idxsql($idxname, $table, $idxflds);

$idxname = 'idx_admin_lastname';
$idxflds = 'admin_lastname';
idxsql($idxname, $table, $idxflds);

$idxname = 'idx_admin_email_address';
$idxflds = 'admin_email_address';
idxsql($idxname, $table, $idxflds);

$idxname = 'idx_admin_password';
$idxflds = 'admin_password';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'admin_files';
$flds = "
  admin_files_id I NOTNULL AUTO PRIMARY,
  admin_files_name C(64) DEFAULT '' NOTNULL,
  admin_files_is_boxes I1 NOTNULL DEFAULT '0',
  admin_files_to_boxes I NOTNULL DEFAULT '0',
  admin_groups_id I1 NOTNULL DEFAULT '1'
";
dosql($table, $flds);

$table = $prefix_table . 'admin_groups';
$flds = "
  admin_groups_id I NOTNULL AUTO PRIMARY,
  admin_groups_name C(64) NULL
";
dosql($table, $flds);


$table = $prefix_table . 'banktransfer';
$flds = "
  orders_id I DEFAULT '0' NOTNULL,
  banktransfer_owner C(64),
  banktransfer_number C(24),
  banktransfer_bankname C(64),
  banktransfer_blz C(8),
  banktransfer_status I,
  banktransfer_prz C(2),
  banktransfer_fax C(2)
";
dosql($table, $flds);

$idxname = 'idx_banktransfer_orders_id';
$idxflds = 'orders_id';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'block';
$flds = "
  block_id I NOTNULL AUTO PRIMARY,
  block_side C(32) NOTNULL,
  block_status I2 NOTNULL DEFAULT '1',
  block_file C(32) NOTNULL,
  block_cache C(32) NOTNULL,
  block_type I2 NOTNULL DEFAULT '1',
  block_sort_order I2 DEFAULT NULL,
  block_login_flag I2 NOTNULL DEFAULT '0',
  block_author_name C(32) NOTNULL,
  block_author_www C(255),
  block_modules_group C(32) NOTNULL,
  date_added T,
  last_modified T,
  set_function C(255) NULL
";
dosql($table, $flds);



$table = $prefix_table . 'block_info';
$flds = "
  block_id I DEFAULT '0' NOTNULL PRIMARY,
  block_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  block_name C(255) NOTNULL
";
dosql($table, $flds);


$idxname = 'idx_block_name'; 
$idxflds = 'block_name';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'block_to_page_type';
$flds = "
  block_id I NOTNULL PRIMARY,
  page_type_id I NOTNULL PRIMARY
";
dosql($table, $flds);


$table = $prefix_table . 'campaigns';
$flds = "
   campaigns_id I DEFAULT '0' NOTNULL PRIMARY,
   campaigns_languages_id I NOTNULL DEFAULT '1' PRIMARY,
   campaigns_name C(32) NOTNULL
";
dosql($table, $flds);


$idxname = 'idx_campaigns_name';
$idxflds = 'campaigns_name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'categories';
$flds = "
  categories_id I NOTNULL AUTO PRIMARY,
  categories_image C(64) NULL,
  parent_id I NOTNULL DEFAULT '0',
  access I NOTNULL DEFAULT '0',
  sort_order I1,
  date_added T,
  last_modified T,
  categories_status I1 NOTNULL DEFAULT '0'
";
dosql($table, $flds);


$idxname = 'idx_parent_id';
$idxflds = 'parent_id';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'categories_description';
$flds = "
  categories_id I NOTNULL DEFAULT '0' PRIMARY,
  categories_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  categories_name C(32) NOTNULL,
  categories_heading_title C(64) NULL,
  categories_description X,
  categories_description_meta C(250) NOTNULL,
  categories_keywords_meta C(250) NOTNULL
";
dosql($table, $flds);

$idxname = 'idx_categories_name';
$idxflds = 'categories_name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'configuration';
$flds = "
  configuration_id I NOTNULL AUTO PRIMARY,
  configuration_key C(64) NOTNULL,
  configuration_value C(255) NOTNULL,
  configuration_group_id I NOTNULL,
  sort_order I2 NULL,
  last_modified T,
  date_added T,
  use_function C(255) NULL,
  set_function C(255) NULL
";
dosql($table, $flds);



$table = $prefix_table . 'configuration_group';
$flds = "
  configuration_group_id I NOTNULL AUTO PRIMARY,
  sort_order I2 NULL,
  visible I1 DEFAULT '1' NULL
";
dosql($table, $flds);



$table = $prefix_table . 'countries';
$flds = "
  countries_id I NOTNULL AUTO PRIMARY,
  countries_name C(64) NOTNULL,
  countries_iso_code_2 C(2) NOTNULL,
  countries_iso_code_3 C(3) NOTNULL,
  countries_moneybookers C(3),
  address_format_id I DEFAULT '0' NOTNULL
";
dosql($table, $flds);


$idxname = 'idx_countries_name'; 
$idxflds = 'countries_name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'coupons';
$flds = "
  coupon_id I NOTNULL AUTO PRIMARY,
  coupon_type C(1) NOTNULL DEFAULT 'F',
  coupon_code C(32) NOTNULL,
  coupon_amount N '8.4' NOTNULL DEFAULT '0.0000',
  coupon_minimum_order N '8.4' NOTNULL DEFAULT '0.0000',
  coupon_start_date T,
  coupon_expire_date T,
  uses_per_coupon I2 NOTNULL DEFAULT '1',
  uses_per_user I2 NOTNULL DEFAULT '0',
  restrict_to_products C(255) NULL,
  restrict_to_categories C(255) NULL,
  restrict_to_customers X,
  coupon_active C(1) NOTNULL DEFAULT 'Y',
  date_created T,
  date_modified T
";
dosql($table, $flds);

$table = $prefix_table . 'coupons_description';
$flds = "
  coupon_id I NOTNULL DEFAULT '0' PRIMARY,
  coupon_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  coupon_name C(32) NOTNULL,
  coupon_description X
";
dosql($table, $flds);


$table = $prefix_table . 'coupon_email_track';
$flds = "
  unique_id I NOTNULL AUTO PRIMARY,
  coupon_id I NOTNULL DEFAULT '0',
  customer_id_sent I NOTNULL DEFAULT '0',
  sent_firstname C(32) NULL,
  sent_lastname C(32) NULL,
  emailed_to C(32) NULL,
  date_sent T
";
dosql($table, $flds);


$table = $prefix_table . 'coupon_gv_customer';
$flds = "
  customer_id I NOTNULL DEFAULT '0' PRIMARY,
  amount N '8.4' NOTNULL DEFAULT '0.0000'
";
dosql($table, $flds);


$table = $prefix_table . 'coupon_gv_queue';
$flds = "
  unique_id I2 NOTNULL AUTO PRIMARY,
  customer_id I2 NOTNULL DEFAULT '0',
  order_id I2 NOTNULL DEFAULT '0',
  amount N '8.4' NOTNULL DEFAULT '0.0000',
  date_created T,
  ipaddr C(32) NOTNULL,
  release_flag C(1) NOTNULL DEFAULT 'N'
";
dosql($table, $flds);


$table = $prefix_table . 'coupon_redeem_track';
$flds = "
  unique_id I NOTNULL AUTO PRIMARY,
  coupon_id I NOTNULL DEFAULT '0',
  customer_id I NOTNULL DEFAULT '0',
  redeem_date T,
  redeem_ip C(32) NOTNULL,
  order_id I NOTNULL DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'currencies';
$flds = "
  currencies_id I NOTNULL AUTO PRIMARY,
  title C(32) NOTNULL,
  code C(3) NOTNULL,
  symbol_left C(12),
  symbol_right C(12),
  decimal_point C(1),
  thousands_point C(1),
  decimal_places C(1),
  value N '13.8',
  last_updated T
";
dosql($table, $flds);

$table = $prefix_table . 'customers';
$flds = "
   customers_id I NOTNULL AUTO PRIMARY,
   customers_gender C(1) NOTNULL,
   customers_firstname C(32) NOTNULL,
   customers_lastname C(32) NOTNULL,
   customers_image C(64) NULL,
   customers_number C(16) NOTNULL,
   customers_dob T,
   customers_email_address C(96) NOTNULL,
   customers_default_address_id I2 DEFAULT '1' NOTNULL,
   customers_vat_id C(20) NULL,
   customers_vat_id_status I1 DEFAULT '0' NOTNULL,
   customers_telephone C(32) NOTNULL,
   customers_fax C(32) NULL,
   customers_password C(40) NOTNULL,
   customers_wishlist_link_id C(32) NOTNULL,
   customers_newsletter C(1) NULL,
   customers_status  C(1) DEFAULT '1' NOTNULL,
   customers_login C(1) DEFAULT '0' NOTNULL,
   customers_language C(3),
   customers_max_order N '15.8' NOTNULL DEFAULT '0.00000000'
";
dosql($table, $flds);


$table = $prefix_table . 'customers_basket';
$flds = "
  customers_basket_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL,
  to_wishlist_id C(32) NOTNULL,
  products_id C(32) NOTNULL,
  customers_basket_quantity N '10.2' NOTNULL DEFAULT '1.00',
  final_price N '15.8'  NOTNULL,
  customers_basket_date_added C(8)
";
dosql($table, $flds);


$table = $prefix_table . 'customers_basket_attributes';
$flds = "
  customers_basket_attributes_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL,
  products_id C(32) NOTNULL,
  products_options_id I NOTNULL,
  products_options_value_id I NOTNULL,
  products_options_value_text C(32)
";
dosql($table, $flds);


$table = $prefix_table . 'customers_info';
$flds = "
  customers_info_id I NOTNULL PRIMARY,
  customers_info_date_of_last_logon T,
  customers_info_number_of_logons I2,
  customers_info_date_account_created T,
  customers_info_date_account_last_modified T,
  global_product_notifications I1 DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'customers_status';
$flds = "
  customers_status_id I NOTNULL PRIMARY,
  customers_status_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  customers_status_name C(32) NOTNULL DEFAULT '',
  customers_status_image C(64) DEFAULT NULL,
  customers_status_ot_discount_flag I1 NOTNULL DEFAULT '0',
  customers_status_ot_discount N '4.2' DEFAULT '0',
  customers_status_ot_minimum N '15.2' DEFAULT '100.00',
  customers_status_public I1 NOTNULL DEFAULT '0',
  customers_status_show_price I1 NOTNULL DEFAULT '0',
  customers_status_show_price_tax I1 NOTNULL DEFAULT '0',
  customers_status_qty_discounts I1 NOTNULL DEFAULT '0',
  customers_status_payment C(255) NOTNULL DEFAULT ''
";
dosql($table, $flds);


$idxname = 'idx_customers_status_name'; 
$idxflds = 'customers_status_name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'customers_status_history';
$flds = "
  customers_status_history_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL DEFAULT '0',
  new_value I1 NOTNULL DEFAULT '0',
  old_value I1 DEFAULT NULL,
  date_added T,
  customer_notified I1 DEFAULT '0'
";
dosql($table, $flds);



$table = $prefix_table . 'customers_wishlist';
$flds = "
  customers_wishlist_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL,
  customers_wishlist_link_id C(32) NOTNULL DEFAULT '',
  products_id C(32) NOTNULL,
  customers_wishlist_quantity I1 NOT NULL DEFAULT '0',
  final_price N '15.8'  NOTNULL,
  customers_wishlist_date_added C(8) DEFAULT ''
";
dosql($table, $flds);



$table = $prefix_table . 'customers_wishlist_attributes';
$flds = "
  customers_wishlist_attributes_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL,
  customers_wishlist_link_id C(32) NOTNULL DEFAULT '',
  products_id C(32) NOTNULL,
  products_options_id I NOTNULL DEFAULT '0',
  products_options_value_id I NOTNULL DEFAULT '0',
  customers_wishlist_date_added C(8) DEFAULT NULL
";
dosql($table, $flds);



$table = $prefix_table . 'featured';
$flds = "
  featured_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '0',
  featured_date_added T,
  featured_last_modified T,
  expires_date T,
  date_status_change T,
  status I1 NOTNULL DEFAULT '1'
";
dosql($table, $flds);



$table = $prefix_table . 'files_uploaded';
$flds = "
  files_uploaded_id I NOTNULL AUTO PRIMARY,
  sesskey C(32),
  customers_id I,
  files_uploaded_name C(64) NOTNULL
";
dosql($table, $flds);



$table = $prefix_table . 'geo_zones';
$flds = "
  geo_zone_id I NOTNULL AUTO PRIMARY,
  geo_zone_name C(32) NOTNULL,
  geo_zone_description C(255) NOTNULL,
  last_modified T,
  date_added T
";
dosql($table, $flds);



$table = $prefix_table . 'information';
$flds = "
  information_id I NOTNULL AUTO PRIMARY,
  information_image C(64) NULL,
  sort_order I1,
  date_added T,
  last_modified T,
  status I1 NOTNULL DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'information_description';
$flds = "
  information_id I NOTNULL PRIMARY,
  information_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  information_url C(255) NOTNULL,
  information_name C(64) NULL,
  information_heading_title C(64) NULL,
  information_description X
";
dosql($table, $flds);



$table = $prefix_table . 'languages';
$flds = "
  languages_id I NOTNULL AUTO PRIMARY,
  name C(32) NOTNULL,
  iso_639_2 C(3) NOTNULL,
  iso_639_1 C(2) NOTNULL,
  status I1 DEFAULT '0',
  sort_order I1 NOTNULL DEFAULT '0'
";
dosql($table, $flds);

$idxname = 'idx_name'; 
$idxflds = 'name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'logsql';
$flds = "
   created T NOTNULL,
   sql0 C(250) NOTNULL,
   sql1 X NOTNULL,
   params X NOTNULL,
   tracer X NOTNULL,
   timer N '16.6' NOTNULL
";
dosql($table, $flds);


$table = $prefix_table . 'maillist';
$flds = "
   maillist_id I NOTNULL AUTO PRIMARY,
   customers_gender C(1) NOTNULL,
   customers_firstname C(32) NOTNULL,
   customers_lastname C(32) NOTNULL,
   customers_email_address C(96) NOTNULL,
   customers_newsletter C(1) DEFAULT '0',
   customers_actkey C(32)
";
dosql($table, $flds);


$table = $prefix_table . 'manual_info';
$flds = "
  man_info_id I NOTNULL AUTO PRIMARY,
  man_name C(25) NOTNULL DEFAULT '',
  status I1 NOTNULL DEFAULT '0',
  manual_date_added T,
  manual_last_modified T,
  date_status_change T,
  expires_date T,
  man_key C(24) NOTNULL DEFAULT '0',
  defined C(10) DEFAULT '',
  man_key2 C(24) NOTNULL DEFAULT '0',
  man_key3 C(35) DEFAULT ''
";
dosql($table, $flds);



$table = $prefix_table . 'manufacturers';
$flds = "
  manufacturers_id I NOTNULL AUTO PRIMARY,
  manufacturers_name C(32) NOTNULL,
  manufacturers_image C(64),
  date_added T,
  last_modified T 
";
dosql($table, $flds);


$idxname = 'idx_manufacturers_name'; 
$idxflds = 'manufacturers_name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'manufacturers_info';
$flds = "
  manufacturers_id I NOTNULL PRIMARY,
  manufacturers_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  manufacturers_url C(255) NOTNULL,
  url_clicked I2 NOTNULL DEFAULT '0',
  date_last_click T 
";
dosql($table, $flds);


$table = $prefix_table . 'newsletters';
$flds = "
  newsletters_id I NOTNULL AUTO PRIMARY,
  title C(255) NOTNULL,
  content X NOTNULL,
  module C(255) NOTNULL,
  date_added T,
  date_sent T,
  status I1,
  locked I1 DEFAULT '0'
";
dosql($table, $flds);



$table = $prefix_table . 'orders';
$flds = "
  orders_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL,
  customers_cid I NOTNULL,
  customers_firstname C(32) NOTNULL,
  customers_lastname C(32) NOTNULL,
  customers_name C(64) NOTNULL,
  customers_company C(32),
  customers_street_address C(64) NOTNULL,
  customers_suburb C(32),
  customers_city C(32) NOTNULL,
  customers_postcode C(10) NOTNULL,
  customers_state C(32),
  customers_country C(32) NOTNULL,
  customers_telephone C(32) NOTNULL,
  customers_email_address C(96) NOTNULL,
  customers_address_format_id I2 NOTNULL,
  delivery_firstname C(32) NOTNULL,
  delivery_lastname C(32) NOTNULL,
  delivery_name C(64) NOTNULL,
  delivery_company C(32),
  delivery_street_address C(64) NOTNULL,
  delivery_suburb C(32),
  delivery_city C(32) NOTNULL,
  delivery_postcode C(10) NOTNULL,
  delivery_state C(32),
  delivery_country C(32) NOTNULL,
  delivery_address_format_id I2 NOTNULL,
  billing_firstname C(32) NOTNULL,
  billing_lastname C(32) NOTNULL,
  billing_name C(64) NOTNULL,
  billing_company C(32),
  billing_street_address C(64) NOTNULL,
  billing_suburb C(32),
  billing_city C(32) NOTNULL,
  billing_postcode C(10) NOTNULL,
  billing_state C(32),
  billing_country C(32) NOTNULL,
  billing_address_format_id I2 NOTNULL,
  payment_method C(32) NOTNULL,
  cc_type C(20),
  cc_owner C(64),
  cc_number C(32),
  cc_expires C(4),
  last_modified T,
  date_purchased T,
  campaigns I2 NOTNULL,
  orders_status I2 NOTNULL,
  orders_date_finished T,
  currency C(3),
  currency_value N '14.6',
  orders_language C(3)
";
dosql($table, $flds);



$table = $prefix_table . 'orders_products';
$flds = "
  orders_products_id I NOTNULL AUTO PRIMARY,
  orders_id I NOTNULL,
  products_id I NOTNULL,
  products_model C(12),
  products_ean C(13),
  products_name C(64) NOTNULL,
  products_serial_number C(64),
  products_price N '15.8' NOTNULL DEFAULT '0.00000000',
  final_price N '15.8' NOTNULL DEFAULT '0.00000000',
  products_tax N '7.4' NOTNULL DEFAULT '0.0000',
  products_quantity N '10.2' NOTNULL DEFAULT '1.00'
";
dosql($table, $flds);



$table = $prefix_table . 'orders_status';
$flds = "
   orders_status_id I DEFAULT '0' NOTNULL PRIMARY,
   orders_languages_id I NOTNULL DEFAULT '1' PRIMARY,
   orders_status_name C(32) NOTNULL
";
dosql($table, $flds);


$idxname = 'idx_orders_status_name'; 
$idxflds = 'orders_status_name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'orders_status_history';
$flds = "
   orders_status_history_id I NOTNULL AUTO PRIMARY,
   orders_id I NOTNULL,
   orders_status_id I2 NOTNULL,
   date_added T,
   customer_notified I1 DEFAULT '0',
   comments X
";
dosql($table, $flds);


$table = $prefix_table . 'orders_products_attributes';
$flds = "
  orders_products_attributes_id I NOTNULL AUTO PRIMARY,
  orders_id I NOTNULL,
  orders_products_id I NOTNULL,
  products_options C(32) NOTNULL,
  products_options_values C(32) NOTNULL,
  options_values_price N '15.8' NOTNULL DEFAULT '0.00000000',
  price_prefix C(1) NOTNULL
";
dosql($table, $flds);


$table = $prefix_table . 'orders_products_download';
$flds = "
  orders_products_download_id I NOTNULL AUTO PRIMARY,
  orders_id I NOTNULL DEFAULT '0',
  orders_products_id I NOTNULL DEFAULT '0',
  orders_products_filename C(255) NOTNULL,
  download_maxdays N '2.0' NOTNULL DEFAULT '0',
  download_count N '2.0' NOTNULL DEFAULT '0'
";
dosql($table, $flds);



$table = $prefix_table . 'orders_total';
$flds = "
  orders_total_id I NOTNULL AUTO PRIMARY,
  orders_id I NOTNULL,
  title C(255) NOTNULL,
  text C(255) NOTNULL,
  value N '15.8'  NOTNULL,
  class C(32) NOTNULL,
  sort_order I NOTNULL
";
dosql($table, $flds);


$idxname = 'idx_orders_id'; 
$idxflds = 'orders_id';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'order_transactions';
$flds = "
  orders_id I NOTNULL AUTO PRIMARY,
  content_type C(94),
  shipping_id C(94),
  amount N '15.2' NOTNULL DEFAULT '0.00',
  shipping N '15.2' DEFAULT '0.00',
  tax N '7.2' NOTNULL DEFAULT '0.00'
";
dosql($table, $flds);


$idxname = 'idx_orders_id'; 
$idxflds = 'orders_id';
idxsql($idxname, $table, $idxflds);	  


	  

$table = $prefix_table . 'page_type';
$flds = "
  page_type_id I DEFAULT '0' NOTNULL PRIMARY,
  page_type_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  page_type_name C(32) NOTNULL
";
dosql($table, $flds);


$idxname = 'idx_page_type_name'; 
$idxflds = 'page_type_name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'products';
$flds = "
  products_id I NOTNULL AUTO PRIMARY,
  products_quantity I4 NOTNULL DEFAULT '0',
  products_reorder_level I4 NOTNULL DEFAULT '5',
  products_model C(12) NULL,
  products_ean C(13) NULL,
  products_image C(250) NULL,
  products_price N '15.8' NOTNULL DEFAULT '0.00000000',
  products_base_price N '10.6' NOTNULL DEFAULT '1.000000',
  products_product_quantity N '10.2' NOTNULL DEFAULT '1.00',
  products_base_quantity  N '10.6' NOTNULL DEFAULT '1.000000',
  products_base_unit C(12) NULL,
  products_date_added T,
  products_last_modified T,
  products_date_available T,
  products_weight N '5.2' NOTNULL DEFAULT '0.00',
  products_status I1 NOTNULL DEFAULT '0',
  products_tax_class_id I NOTNULL DEFAULT '0',
  products_units_id I NOTNULL DEFAULT '0',
  manufacturers_id I NULL,
  permissions I1 NOTNULL DEFAULT '0',
  products_ordered I NOTNULL DEFAULT '0',
  products_quantity_decimal I1 NOTNULL DEFAULT '0',
  products_quantity_order_min N '10.2' NOTNULL DEFAULT '1.00',
  products_quantity_order_units I NOTNULL DEFAULT '1',
  products_price_list N '15.8'  DEFAULT '0.0000',
  products_discount_allowed N '15.2' NOTNULL DEFAULT '0.00',
  products_discount1 N '15.8' NOTNULL DEFAULT '0.00000000',
  products_discount2 N '15.8' NOTNULL DEFAULT '0.00000000',
  products_discount3 N '15.8' NOTNULL DEFAULT '0.00000000',
  products_discount4 N '15.8' NOTNULL DEFAULT '0.00000000',
  products_discount1_qty N '10.2' NOTNULL DEFAULT '0.00',
  products_discount2_qty N '10.2' NOTNULL DEFAULT '0.00',
  products_discount3_qty N '10.2' NOTNULL DEFAULT '0.00',
  products_discount4_qty N '10.2' NOTNULL DEFAULT '0.00',
  products_discounts_id I NOTNULL DEFAULT '0',
  products_slave_visible I1 NOTNULL DEFAULT '1',
  products_sort_order I4 NOTNULL DEFAULT '0'
";
dosql($table, $flds);

$idxname = 'idx_products_date_added'; 
$idxflds = 'products_date_added'; 
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'products_attributes';
$flds = "
  products_attributes_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL,
  options_id I NOTNULL,
  options_values_id I NOTNULL,
  options_values_price N '15.8'  NOTNULL,
  price_prefix C(1) NOTNULL,
  options_sort_order I1 DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'products_attributes_download';
$flds = "
  products_attributes_id I NOTNULL PRIMARY,
  products_attributes_filename C(255) NOTNULL,
  products_attributes_maxdays N '2.0' DEFAULT '0',
  products_attributes_maxcount N '2.0' DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'products_description';
$flds = "
  products_id I DEFAULT '0' NOTNULL PRIMARY,
  products_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  products_name C(64) NOTNULL,
  products_description X,
  products_short_description C(255) NULL,
  products_url C(255) NULL,
  products_viewed I2 DEFAULT '0',
  products_description_meta C(250) NULL,
  products_keywords_meta C(250) NULL
";
dosql($table, $flds);

$idxname = 'idx_products_name'; 
$idxflds = 'products_name';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'products_images';
$flds = "
  image_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL,
  image_nr I2 NOTNULL,
  image_name C(250) NULL
";
dosql($table, $flds);


$table = $prefix_table . 'products_notifications';
$flds = "
  products_id I NOTNULL PRIMARY,
  customers_id I NOTNULL PRIMARY,
  date_added T
";
dosql($table, $flds);



$table = $prefix_table . 'products_options';
$flds = "
  products_options_id I NOTNULL DEFAULT '0' PRIMARY,
  products_options_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  products_options_name C(32) NOTNULL,
  products_options_type I NOTNULL DEFAULT '0',
  products_options_length I2 NOTNULL DEFAULT '32',
  products_options_comment C(64) NOTNULL
";
dosql($table, $flds);



$table = $prefix_table . 'products_options_types';
$flds = "
  products_options_types_id I DEFAULT '0' NOTNULL PRIMARY,
  products_options_types_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  products_options_types_name C(32) NOTNULL
";
dosql($table, $flds);

$idxname = 'idx_products_options_types_name'; 
$idxflds = 'products_options_types_name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'products_options_values';
$flds = "
  products_options_values_id I NOTNULL DEFAULT '0' PRIMARY,
  products_options_values_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  products_options_values_name C(64) NOTNULL
";
dosql($table, $flds);



$table = $prefix_table . 'products_options_values_to_products_options';
$flds = "
  products_options_values_to_products_options_id I NOTNULL AUTO PRIMARY,
  products_options_id I NOTNULL,
  products_options_values_id I NOTNULL
";
dosql($table, $flds);



$table = $prefix_table . 'products_status';
$flds = "
   products_status_id I DEFAULT '1' NOTNULL PRIMARY,
   products_status_languages_id I NOTNULL DEFAULT '1' PRIMARY,
   products_status_name C(32) NOTNULL
";
dosql($table, $flds);

$idxname = 'idx_products_status_name'; 
$idxflds = 'products_status_name';
idxsql($idxname, $table, $idxflds);



$table = $prefix_table . 'products_to_categories';
$flds = "
  products_id I NOTNULL PRIMARY,
  categories_id I NOTNULL PRIMARY
";
dosql($table, $flds);


$table = $prefix_table . 'products_to_master';
$flds = "
  slave_id I unsigned NOTNULL DEFAULT '1',
  master_id I unsigned NOTNULL DEFAULT '1'
";
dosql($table, $flds);



$table = $prefix_table . 'products_units';
$flds = "
  products_units_id I DEFAULT '0' NOTNULL PRIMARY,
  languages_id I NOTNULL DEFAULT '1' PRIMARY,
  products_unit_name C(60) NOTNULL
";
dosql($table, $flds);



$table = $prefix_table . 'products_up_sell';
$flds = "
  ID I NOTNULL AUTO PRIMARY,
  products_id I unsigned NOTNULL DEFAULT '1',
  up_sell_id I unsigned NOTNULL DEFAULT '1',
  sort_order I unsigned NOTNULL DEFAULT '1'
";
dosql($table, $flds);


$table = $prefix_table . 'products_xsell';
$flds = "
  ID I NOTNULL AUTO PRIMARY,
  products_id I unsigned NOTNULL DEFAULT '1',
  xsell_id I unsigned NOTNULL DEFAULT '1',
  sort_order I unsigned NOTNULL DEFAULT '1'
";
dosql($table, $flds);


$table = $prefix_table . 'recovercartsales';
$flds = "
  recovercartsales_id I NOTNULL AUTO PRIMARY,
  customers_id I unsigned NOTNULL,
  recovercartsales_date_added C(8) NOTNULL,
  recovercartsales_date_modified C(8) NOTNULL
";
dosql($table, $flds);


$table = $prefix_table . 'reviews';
$flds = "
  reviews_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL,
  customers_id I,
  customers_name C(64) NOTNULL,
  reviews_rating I1,
  date_added T,
  last_modified T,
  reviews_read I2 NOTNULL DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'reviews_description';
$flds = "
  reviews_id I NOTNULL PRIMARY,
  reviews_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  reviews_text X NOTNULL
";
dosql($table, $flds);


$table = $prefix_table . 'specials';
$flds = "
  specials_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL,
  specials_new_products_price N '15.8'  NOTNULL,
  specials_date_added T,
  specials_last_modified T,
  expires_date T,
  date_status_change T,
  status I1 NOTNULL DEFAULT '1'
";
dosql($table, $flds);


$table = $prefix_table . 'tax_class';
$flds = "
  tax_class_id I NOTNULL AUTO PRIMARY,
  tax_class_title C(32) NOTNULL,
  tax_class_description C(255) NOTNULL,
  last_modified T,
  date_added T
";
dosql($table, $flds);



$table = $prefix_table . 'tax_rates';
$flds = "
  tax_rates_id I NOTNULL AUTO PRIMARY,
  tax_zone_id I NOTNULL,
  tax_class_id I NOTNULL,
  tax_priority I2 DEFAULT 1,
  tax_rate N '7.4' NOTNULL,
  tax_description C(255) NOTNULL,
  last_modified T,
  date_added T
";
dosql($table, $flds);


$table = $prefix_table . 'whos_online';
$flds = "
  customer_id I,
  full_name C(64) NOTNULL,
  session_id C(128) NOTNULL,
  ip_address C(15) NOTNULL,
  time_entry C(14) NOTNULL,
  time_last_click C(14) NOTNULL,
  last_page_url C(64) NOTNULL
";
dosql($table, $flds);



$table = $prefix_table . 'zones';
$flds = "
  zone_id I NOTNULL AUTO PRIMARY,
  zone_country_id I NOTNULL,
  zone_code C(32) NOTNULL,
  zone_name C(32) NOTNULL
";
dosql($table, $flds);


$table = $prefix_table . 'zones_to_geo_zones';
$flds = "
   association_id I NOTNULL AUTO PRIMARY,
   zone_country_id I NOTNULL,
   zone_id I NULL,
   geo_zone_id I NULL,
   last_modified T,
   date_added T
";
dosql($table, $flds);


