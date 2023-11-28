<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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
   ----------------------------------------------------------------------
 */

function dosql($table, $flds)
{
    global $db;

    $dict = NewDataDictionary($db);

    // $dict->debug = 1;
    $taboptarray = ['mysql' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;', 'REPLACE'];

    $sqlarray = $dict->createTableSQL($table, $flds, $taboptarray);
    $dict->executeSqlArray($sqlarray);

    echo '<br><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $table . " " . MADE . '</font>';
}

function idxsql($idxname, $table, $idxflds)
{
    global $db;

    $dict = NewDataDictionary($db);

    $sqlarray = $dict->CreateIndexSQL($idxname, $table, $idxflds);
    $dict->executeSqlArray($sqlarray);
}


$table = $prefix_table . 'address_book';
$flds = "
   address_book_id I NOTNULL AUTO PRIMARY,
   customers_id I NOTNULL DEFAULT '0' PRIMARY,
   entry_gender C(1),
   entry_company C(32),
   entry_owner C(32),
   entry_vat_id C(20) NULL,
   entry_vat_id_status I1 DEFAULT '0' NOTNULL,
   entry_firstname C(32) NOTNULL,
   entry_lastname C(32) NOTNULL,
   entry_street_address C(64) DEFAULT '' NOTNULL,
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
  admin_gender C(1),
  admin_firstname C(32) NOTNULL,
  admin_lastname C(32) NULL,
  admin_email_address C(96) NOTNULL,
  admin_2fa C(96) NULL,
  admin_2fa_active C(1) DEFAULT '0' NOTNULL,
  admin_telephone C(32),
  admin_password C(255) NOTNULL,
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
  block_author_name C(32),
  block_author_www C(255),
  block_modules_group C(32),
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



$table = $prefix_table . 'categories';
$flds = "
  categories_id I NOTNULL AUTO PRIMARY,
  categories_image C(250), 
  categories_banner C(250), 
  color C(23), 
  menu_type C(23),  
  parent_id I NOTNULL DEFAULT '0',
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
  categories_page_title C(255) NULL,
  categories_heading_title C(250),
  categories_description XL,
  categories_description_meta C(250),
  categories_facebook_title C(255) NULL,
  categories_facebook_description C(255) NULL,
  categories_twitter_title C(255) NULL,
  categories_twitter_description C(255) NULL 
";
dosql($table, $flds);

$idxname = 'idx_categories_name';
$idxflds = 'categories_name';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'categories_images';
$flds = "
  categories_images_id I NOTNULL AUTO PRIMARY,
  categories_id I NOTNULL,
  categories_image C(250),
  sort_order I1,
  date_added T,
  last_modified T
";
dosql($table, $flds);

$table = $prefix_table . 'categories_images_description';
$flds = "
  categories_images_id I NOTNULL DEFAULT '0' PRIMARY,
  categories_images_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  categories_images_title C(250),  
  categories_images_caption C(250),
  categories_images_description X
";
dosql($table, $flds);

$idxname = 'idx_categories_images_title';
$idxflds = 'categories_images_title';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'categories_panorama';
$flds = "
  panorama_id I I NOTNULL AUTO PRIMARY,
  categories_id I NOTNULL DEFAULT '1' PRIMARY,
  panorama_preview C(255) NULL,
  panorama_author C(255) NULL,
  panorama_autoload C(5) DEFAULT 'false',  
  panorama_autorotates C(5) DEFAULT '-2',  
  panorama_date_added T,
  panorama_last_modified T 
";
dosql($table, $flds);

$table = $prefix_table . 'categories_panorama_description';
$flds = "
  panorama_id I DEFAULT '0' NOTNULL PRIMARY,
  panorama_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  panorama_name C(64) NOTNULL,
  panorama_title C(255) NULL,
  panorama_viewed I2 DEFAULT '0',
  panorama_description_meta C(250) NULL,
  panorama_keywords C(250) NULL
";
dosql($table, $flds);

$idxname = 'idx_panorama_name';
$idxflds = 'panorama_name';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'categories_panorama_scene';
$flds = "
  scene_id I NOTNULL AUTO PRIMARY,
  panorama_id I NOTNULL DEFAULT '1' PRIMARY,
  scene_image C(255) NULL,
  scene_type C(24) NULL,
  scene_hfov  N '6.2' NOTNULL DEFAULT '0.0',
  scene_pitch N '6.2' NOTNULL DEFAULT '0.0',
  scene_yaw N '6.2' NOTNULL DEFAULT '0.0',
  scene_default I1 NOTNULL DEFAULT '0'
";
dosql($table, $flds);

$idxname = 'idx_scene_image';
$idxflds = 'scene_image';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'categories_panorama_scene_hotspot';
$flds = "
  hotspot_id I I NOTNULL AUTO PRIMARY,
  panorama_id I NOTNULL DEFAULT '1' PRIMARY,
  scene_id I NOTNULL DEFAULT '1' PRIMARY,
  hotspot_pitch N '6.2' NOTNULL DEFAULT '0.0',
  hotspot_yaw N '6.2' NOTNULL DEFAULT '0.0',
  hotspot_type C(24) NULL,
  hotspot_icon_class C(24) NULL,
  products_id I,
  categories_id I,
  hotspot_url C(255) NULL
";
dosql($table, $flds);


$table = $prefix_table . 'categories_panorama_scene_hotspot_text';
$flds = "
  hotspot_id I DEFAULT '0' NOTNULL PRIMARY,
  hotspot_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  hotspot_text C(255) NULL
";
dosql($table, $flds);


$table = $prefix_table . 'categories_slider';
$flds = "
  slider_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '0',
  slider_image C(255),
  slider_date_added T,
  slider_last_modified T,
  expires_date T,
  date_status_change T,
  status I1 NOTNULL DEFAULT '1'
";
dosql($table, $flds);




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
   customers_gender C(1),
   customers_firstname C(32) NOTNULL,
   customers_lastname C(32) NOTNULL,
   customers_dob T,
   customers_email_address C(96),
   customers_2fa C(96) NULL,
   customers_2fa_active C(1) DEFAULT '0' NOTNULL,
   guest_email_address C(96),  
   customers_default_address_id I2 DEFAULT '1' NOTNULL,
   customers_telephone C(32),
   customers_password C(255) NOTNULL,
   customers_wishlist_link_id C(32) NOTNULL,
   customers_status  C(1) DEFAULT '1' NOTNULL,
   customers_login C(1) DEFAULT '0' NOTNULL,
   customers_language C(3),
   customers_max_order N '8.4' NOTNULL DEFAULT '0.00000000'
";
dosql($table, $flds);


$table = $prefix_table . 'customers_basket';
$flds = "
  customers_basket_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL,
  to_wishlist_id C(32) NOTNULL,
  products_id C(32) NOTNULL,
  customers_basket_quantity I2 NOTNULL DEFAULT '1',
  free_redemption C(1) DEFAULT '',
  final_price N '10.4' NOTNULL DEFAULT '0.0000',
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
  customers_wishlist_quantity I2 NOTNULL DEFAULT '1', 
  free_redemption C(1) DEFAULT '',
  final_price N '10.4' NOTNULL DEFAULT '0.0000',
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
  sesskey C(252),
  customers_id I,
  files_uploaded_name C(164) NOTNULL
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
  information_name C(64) NULL,
  information_heading_title C(64) NULL,
  information_description XL
";
dosql($table, $flds);



$table = $prefix_table . 'languages';
$flds = "
  languages_id I NOTNULL AUTO PRIMARY,
  name C(32) NOTNULL,
  iso_639_2 C(3) NOTNULL,
  iso_639_1 C(2) NOTNULL,
  iso_3166_1 C(2) NOTNULL,
  status I1 DEFAULT '0',
  sort_order I1 NOTNULL DEFAULT '0'
";
dosql($table, $flds);

$idxname = 'idx_name';
$idxflds = 'name';
idxsql($idxname, $table, $idxflds);



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
  manufacturers_image C(255),
  teaser_brand_image C(255),
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


$table = $prefix_table . 'newsletter_recipients';
$flds = "
	recipients_id I NOTNULL AUTO PRIMARY,
	customers_gender C(1),
	customers_firstname C(32) NOTNULL,
	customers_lastname C(32) NOTNULL,
	customers_email_address C(96) NOTNULL,
	date_added T,
	mail_key C(32) NOTNULL,
	mail_sha1 C(232) NOTNULL,
	key_sent T,
	status I1 DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'newsletter_recipients_history';
$flds = "
  recipients_status_history_id I NOTNULL AUTO PRIMARY,
  recipients_id I NOTNULL DEFAULT '0',
  new_value I1 NOTNULL DEFAULT '0',
  old_value I1 DEFAULT NULL,
  date_added T,
  customer_notified I1 DEFAULT '0'
";
dosql($table, $flds);



$table = $prefix_table . 'orders';
$flds = "
  orders_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL,
  customers_firstname C(32) NOTNULL,
  customers_lastname C(32) NOTNULL,
  customers_name C(64) NOTNULL,
  customers_company C(32),
  customers_street_address C(64) NOTNULL,
  customers_city C(32) NOTNULL,
  customers_postcode C(10) NOTNULL,
  customers_state C(32),
  customers_country C(32) NOTNULL,
  customers_telephone C(32),
  customers_email_address C(96) NOTNULL,
  customers_address_format_id I2 NOTNULL,
  delivery_firstname C(32) NOTNULL,
  delivery_lastname C(32) NOTNULL,
  delivery_name C(64) NOTNULL,
  delivery_company C(32),
  delivery_street_address C(64) NOTNULL,
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
  billing_city C(32) NOTNULL,
  billing_postcode C(10) NOTNULL,
  billing_state C(32),
  billing_country C(32) NOTNULL,
  billing_address_format_id I2 NOTNULL,
  payment_method C(32) NOTNULL,
  last_modified T,
  date_purchased T,
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
  products_image C(255),
  products_serial_number C(250),
  products_free_redemption C(1) DEFAULT '',
  products_old_electrical_equipment C(1) DEFAULT '',
  products_price N '10.4' NOTNULL DEFAULT '0.0000',
  final_price N '10.4' NOTNULL DEFAULT '0.0000',
  products_tax N '7.4' NOTNULL DEFAULT '0.0000',
  products_quantity I2 NOTNULL DEFAULT '1'
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
  options_values_price N '10.4' NOTNULL DEFAULT '0.00000000',
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
  value N '10.4'  NOTNULL,
  class C(32) NOTNULL,
  sort_order I NOTNULL
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
  products_replacement_product_id I NULL,
  products_ean C(13) NULL,
  products_image C(255) NULL,
  products_average_rating N '10.2', 
  products_price N '10.4' NOTNULL DEFAULT '0.0000',
  products_base_price N '10.6' NOTNULL DEFAULT '1.000000',
  products_product_quantity N '8.4' NULL DEFAULT NULL,
  products_base_quantity I2 NOTNULL DEFAULT '1', 
  products_base_unit C(12) NULL,
  products_date_added T,
  products_last_modified T,
  products_date_available T,
  products_weight N '5.2' NOTNULL DEFAULT '0.00',
  products_status I1 NOTNULL DEFAULT '0',
  products_setting I1 NOTNULL DEFAULT '0',
  products_tax_class_id I NOTNULL DEFAULT '0',
  products_units_id I NOTNULL DEFAULT '0',
  products_old_electrical_equipment I1 NOTNULL DEFAULT '0',
  products_used_goods I1 NOTNULL DEFAULT '0', 
  manufacturers_id I NULL,
  permissions I1 NOTNULL DEFAULT '0',
  products_ordered I NOTNULL DEFAULT '0',
  products_quantity_order_min I2 NOTNULL DEFAULT '1', 
  products_quantity_order_max I4 NOTNULL DEFAULT '30',    
  products_quantity_order_units I NOTNULL DEFAULT '1',
  products_price_list N '10.4'  DEFAULT '0.0000',
  products_discount1 N '10.4' NOTNULL DEFAULT '0.0000',
  products_discount2 N '10.4' NOTNULL DEFAULT '0.0000',
  products_discount3 N '10.4' NOTNULL DEFAULT '0.0000',
  products_discount4 N '10.4' NOTNULL DEFAULT '0.0000',
  products_discount1_qty I2 NOTNULL DEFAULT '0',
  products_discount2_qty I2 NOTNULL DEFAULT '0',
  products_discount3_qty I2 NOTNULL DEFAULT '0',
  products_discount4_qty I2 NOTNULL DEFAULT '0',
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
  options_values_model C(12) NULL,
  options_values_image C(255) NULL,
  options_values_id I NOTNULL,
  options_values_status C(1) NOTNULL DEFAULT '1',
  options_values_price N '10.4'  NOTNULL,
  options_values_quantity I2 NOTNULL DEFAULT '1', 
  options_values_base_price N '10.6' NOTNULL DEFAULT '1.0000',
  options_values_base_quantity N '8.4' NULL DEFAULT NULL,
  options_values_base_unit C(12) DEFAULT NULL,  
  options_values_units_id I NOTNULL DEFAULT '0',  
  price_prefix C(1) NOTNULL DEFAULT '+',
  options_sort_order I1 DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'products_attributes_download';
$flds = "
  download_id I NOTNULL AUTO PRIMARY,
  products_attributes_id I NOTNULL,
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
  products_title C(255) NULL,
  products_description XL,
  products_short_description X,  
  products_essential_characteristics X,
  products_old_electrical_equipment_description X,
  products_used_goods_description X,
  products_url C(255) NULL,
  products_viewed I2 DEFAULT '0',
  products_description_meta C(250) NULL,
  products_facebook_title C(255) NULL,
  products_facebook_description C(255) NULL,
  products_twitter_title C(255) NULL,
  products_twitter_description C(255) NULL,   
  products_keywords C(255) NULL
";
dosql($table, $flds);

$idxname = 'idx_products_name';
$idxflds = 'products_name';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'products_gallery';
$flds = "
  image_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '1' PRIMARY,
  image_name C(255) NOTNULL,
  sort_order I1 DEFAULT '0'
";
dosql($table, $flds);



$table = $prefix_table . 'products_models';
$flds = "
  models_id I I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '1' PRIMARY,
  models_webgl_gltf C(255) NULL,
  models_author C(255) NULL,
  models_author_url C(255) NULL,
  models_camera_pos C(24) NULL,
  models_object_rotation C(25) NULL,
  models_object_scalar C(4) NULL,
  models_add_lights C(5) DEFAULT 'true',
  models_add_ground C(5) DEFAULT 'true',
  models_shadows C(5) DEFAULT 'true',
  models_add_env_map C(5) DEFAULT 'true',  
  models_extensions C(54) NULL,
  models_hdr C(255) NULL, 
  models_hdr_name C(255) NULL, 
  models_hdr_url C(255) NULL,
  models_hdr_author C(255) NULL,
  models_hdr_author_url C(255) NULL,
  models_date_added T,
  models_last_modified T 
";
dosql($table, $flds);


$table = $prefix_table . 'products_models_description';
$flds = "
  models_id I DEFAULT '0' NOTNULL PRIMARY,
  models_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  models_name C(64) NOTNULL,
  models_title C(255) NULL,
  models_viewed I2 DEFAULT '0',
  models_description_meta C(250) NULL,
  models_keywords C(250) NULL
";
dosql($table, $flds);

$idxname = 'idx_models_name';
$idxflds = 'models_name';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'products_model_viewer';
$flds = "
  model_viewer_id I I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '1' PRIMARY,
  model_viewer_glb C(255) NULL,
  model_viewer_usdz C(255) NULL,
  model_viewer_background_color C(8) DEFAULT '#222',  
  model_viewer_auto_rotate C(5) DEFAULT 'true',
  model_viewer_scale C(5) DEFAULT 'auto',
  model_viewer_hdr C(255) NULL,
  model_viewer_date_added T,
  model_viewer_last_modified T 
";
dosql($table, $flds);


$table = $prefix_table . 'products_model_viewer_description';
$flds = "
  model_viewer_id I DEFAULT '0' NOTNULL PRIMARY,
  model_viewer_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  model_viewer_title C(255) NULL,
  model_viewer_description X, 
  model_viewer_viewed I2 DEFAULT '0',
  model_viewer_keywords C(250) NULL
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



$table = $prefix_table . 'products_price_alarm';
$flds = "
  products_price_alarm_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL PRIMARY,
  price_alarm_recipients_id I NOTNULL PRIMARY,
  products_price N '10.4' NOTNULL DEFAULT '0.0000',
  date_added T
";
dosql($table, $flds);

$table = $prefix_table . 'products_price_alarm_history';
$flds = "
  price_alarm_recipients_status_history_id I NOTNULL AUTO PRIMARY,
  price_alarm_recipients_id I NOTNULL DEFAULT '0',
  new_value I1 NOTNULL DEFAULT '0',
  old_value I1 DEFAULT NULL,
  date_added T,
  customer_notified I1 DEFAULT '0'
";
dosql($table, $flds);

$table = $prefix_table . 'products_price_alarm_recipients';
$flds = "
  price_alarm_recipients_id I NOTNULL AUTO PRIMARY,
  price_alert_receiver_email_address C(96) NOTNULL,
  price_alert_receiver_password C(255),	
  date_added T,
  mail_key C(32) NOTNULL,
  mail_sha1 C(232) NOTNULL,
  key_sent T,
  status I1 DEFAULT '0'
";
dosql($table, $flds);



$table = $prefix_table . 'products_price_history';
$flds = "
  products_price_history_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '0',
  products_price N '10.4' NOTNULL DEFAULT '0.0000',
  date_added T
";
dosql($table, $flds);

$idxname = 'idx_date_added';
$idxflds = 'date_added';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'products_status';
$flds = "
   products_status_id I DEFAULT '1' NOTNULL PRIMARY,
   products_status_languages_id I NOTNULL DEFAULT '1' PRIMARY,
   products_status_name C(62) NOTNULL
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
  products_unit_name C(60) NOTNULL,
  unit_of_measure C(30) NOTNULL
";
dosql($table, $flds);


$table = $prefix_table . 'products_video';
$flds = "
  video_id I I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL DEFAULT '1' PRIMARY,
  video_source C(255) NULL,
  video_mp4 C(255) NULL,
  video_webm C(255) NULL,
  video_ogv C(255) NULL,  
  video_poster C(255) NULL,
  video_preload C(10) DEFAULT 'auto',
  video_data_setup C(255) NULL,
  video_date_added T,
  video_last_modified T 
";
dosql($table, $flds);


$table = $prefix_table . 'products_video_description';
$flds = "
  video_id I DEFAULT '0' NOTNULL PRIMARY,
  video_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  video_title C(255) NULL,
  video_description X, 
  video_vtt C(255) NULL,
  video_viewed I2 DEFAULT '0'
";
dosql($table, $flds);


$idxname = 'idx_video_title';
$idxflds = 'video_title';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'reviews';
$flds = "
  reviews_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL,
  customers_id I,
  customers_name C(64) NOTNULL,
  verified I2 NOTNULL DEFAULT '0',
  reviews_rating I1,
  date_added T,
  last_modified T,
  reviews_read I2 NOTNULL DEFAULT '0',
  reviews_status I2 NOTNULL DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'reviews_description';
$flds = "
  reviews_id I NOTNULL PRIMARY,
  reviews_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  reviews_headline C(255) NOTNULL,
  reviews_text XL NOTNULL
";
dosql($table, $flds);


$table = $prefix_table . 'setting';
$flds = "
   setting_id I DEFAULT '1' NOTNULL PRIMARY,
   setting_languages_id I NOTNULL DEFAULT '1' PRIMARY,
   setting_name C(62) NOTNULL
";
dosql($table, $flds);

$idxname = 'idx_setting_name';
$idxflds = 'setting_name';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'specials';
$flds = "
  specials_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL,
  specials_new_products_price N '10.4'  NOTNULL,
  specials_cross_out_price N '10.4', 
  specials_date_added T,
  specials_start_date T,
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
  tax_rate N '7.4' NOTNULL,
  tax_description C(255) NOTNULL,
  last_modified T,
  date_added T
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
