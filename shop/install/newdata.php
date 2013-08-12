<?php
/* ----------------------------------------------------------------------
   $Id: newdata.php 477 2013-07-14 21:57:50Z r23 $

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

   File: newdata.php,v 1.73.2.4 2002/05/14 18:18:05 byronmhome
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

$today = date("Y-m-d H:i:s");
$server = $_SERVER['HTTP_HOST'];

$address_format = '$firstname $lastname$cr$streets$cr$city, $postcode$cr$statecomma$country';
$address_summary = '$city / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (1, '" . $address_format . "', '" . $address_summary . "')") OR die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country';
$address_summary = '$city, $state / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (2, '" . $address_format . "', '" . $address_summary . "')") OR die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format =  '$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country';
$address_summary = '$state / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (3, '" . $address_format . "', '" . $address_summary . "')") OR die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country';
$address_summary = '$postcode / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (4, '" . $address_format . "', '" . $address_summary . "')") OR die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$postcode $city$cr$country';
$address_summary = '$city / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (5, '" . $address_format . "', '" . $address_summary . "')") OR die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "address_format " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('administrator.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('configuration.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('catalog.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('modules.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('plugins.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('customers.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_admin.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('taxes.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('localization.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('reports.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('tools.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('information.php', 1, 0, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");



//administrator.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('admin_members', 0, 1, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('admin_files', 0, 1, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//configuration.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('configuration', 0, 2, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//catalog.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('categories', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_attributes', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_attributes_add', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('manufacturers', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('reviews', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_status', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_units', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('specials', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_expected', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('featured', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('xsell_products', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('up_sell_products', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('quick_stockupdate', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('export_excel', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('import_excel', 0, 3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");



//modules.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('modules', 0, 4, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//plugins.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('plugins', 0, 5, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//customers.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('customers', 0, 6, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('orders', 0, 6, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('customers_status', 0, 6, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('orders_status', 0, 6, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('manual_loging', 0, 6, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('campaigns', 0, 6, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");



//gv_admin.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('coupon_admin', 0, 9, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_queue', 0, 9, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_mail', 0, 9, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_sent', 0, 9, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('listcategories', 0, 9, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('listproducts', 0, 9, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('validproducts', 0, 9, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('validcategories', 0, 9, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//content.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_block', 0, 10, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_information', 0, 10, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_page_type', 0, 10, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//newsfeed.php

//links.php

//rss_admin.php

//taxes.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('countries', 0, 14, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('zones', 0, 14, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('geo_zones', 0, 14, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('tax_classes', 0, 14, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('tax_rates', 0, 14, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//localization.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('currencies', 0, 15, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('languages', 0, 15, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//reports.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_customers', 0, 16, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_referer', 0, 16, 0)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_products_viewed', 0, 16, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_products_purchased', 0, 16, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_low_stock', 0, 16, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_sales_report2', 0, 16, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_recover_cart_sales', 0, 16, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//tools.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('define_language', 0, 17, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('mail', 0, 17, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('newsletters', 0, 17, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('whos_online', 0, 17, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('recover_cart_sales', 0, 17, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//export.php


//information.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('information', 0, 19, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "admin_files " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_groups (admin_groups_id, admin_groups_name) VALUES (1, 'Top Administrator')") OR die ("<b>".NOTUPDATED . $prefix_table . "admin_groups</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "admin_groups " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (2, 'right', 1, 'customers_status', '', 1, 2, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (3, 'left', 1, 'categories', 'categories', 1, 3, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (4, 'left', 0, 'manufacturers', 'manufacturers', 1, 4, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (5, 'left', 1, 'whats_new', '', 1, 5, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (6, 'left', 1, 'add_a_quickie', 'system', 1, 6, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (8, 'left', 1, 'products_history', '', 1, 10, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (13, 'right', 1, 'shopping_cart', '', 1, 13, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (14, 'right', 1, 'login', '', 1, 14, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (15, 'right', 1, 'myworld', '', 1, 15, 1, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (16, 'right', 0, 'manufacturer_info', 'manufacturer_info', 1, 16, 0, " . $db->DBTimeStamp($today) . ",NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (17, 'right', 1, 'order_history', '', 1, 17, 1, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (18, 'right', 1, 'wishlist', '', 1, 18, 1, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (19, 'right', 1, 'best_sellers', '', 1, 19, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (20, 'right', 1, 'product_notifications', '', 1, 20, 0, " . $db->DBTimeStamp($today) . ",NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (21, 'right', 1, 'tell_a_friend', '', 1, 21, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (22, 'right', 0, 'specials', '', 1, 22, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (23, 'right', 0, 'reviews', '', 1, 23, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (26, 'right', 1, 'currencies', '', 1, 26, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (30, 'right', 0, 'newsletter', 'system', 1, 30, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (31, 'right', 1, 'products_xsell', 'xsell_products', 1, 31, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (34, 'left', 0, 'ads', 'system', 1, 34, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (1, '', 1, 'languages', '', 1, 1, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (27, '', 1, 'information', '', 1, 27, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (7, '', 1, 'search', '', 1, 7, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "block</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "block " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (1, 1, 'Sprachen')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (1, 2, 'Languages')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (2, 1, 'Kunden Info')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (2, 2, 'Customers Info')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (3, 1, 'Kategorien')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (3, 2, 'Categories')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (4, 1, 'Hersteller')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (4, 2, 'Manufacturers')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (5, 1, 'Neue Produkte')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (5, 2, 'What\'s New?')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (6, 1, 'Schnelleinkauf')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (6, 2, 'Add a Quickie!')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (7, 1, 'Schnellsuche')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (7, 2, 'Quick Find')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (8, 1, 'Besuchte Produkte')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (8, 2, 'Products History')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (13, 1, 'Warenkorb')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (13, 2, 'Shopping Cart')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (14, 1, 'Anmelden')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (14, 2, 'Login Here')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (15, 1, 'My World')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (15, 2, 'My World')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (16, 1, 'Hersteller Info')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (16, 2, 'Manufacturer Info')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (17, 1, 'Bestellübersicht')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (17, 2, 'Order History')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (18, 1, 'Wunschzettel')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (18, 2, 'My Wishlist')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (19, 1, 'Bestseller')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (19, 2, 'Bestsellers')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (20, 1, 'Produkt-Info')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (20, 2, 'Notifications')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (21, 1, 'Empfehlen')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (21, 2, 'Tell A Friend')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (22, 1, 'Angebote')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (22, 2, 'Specials')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (23, 1, 'Bewertungen')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (23, 2, 'Reviews')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

// 24 News reviews

// 25 Newsfeed


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (26, 1, 'Währungen')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (26, 2, 'Currencies')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (27, 1, 'Informationen')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (27, 2, 'Information')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

// 28 Babelfish

// 29 Google Translato


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (30, 1, 'Newsletter')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (30, 2, 'Newsletter')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (31, 1, 'Ähnliche Produkte')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (31, 2, 'Family Products')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

// 32 Templates
// 33 Skype


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 1, 'Werbung')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 2, 'ads')") OR die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "block_info " . UPDATED .'</font>'; 

// Languages
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 9)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 9)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 9)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 9)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 9)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (7, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (7, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (7, 9)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (8, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (8, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (8, 9)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 9)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (14, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (14, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (15, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (15, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (15, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (15, 8)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (16, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (16, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (16, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (17, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (17, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (17, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (18, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (18, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (18, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (19, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (20, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (21, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (22, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (22, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (23, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (23, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (26, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (26, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (26, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (26, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (30, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (30, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (30, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (31, 9)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 7)") OR die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "block_to_page_type " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (1, 'Afghanistan', 'AF', 'AFG', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (2, 'Albania', 'AL', 'ALB', 'ALB', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (3, 'Algeria', 'DZ', 'DZA', 'ALG', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (4, 'American Samoa', 'AS', 'ASM', 'AME', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (5, 'Andorra', 'AD', 'AND', 'AND', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (6, 'Angola', 'AO', 'AGO', 'AGL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (7, 'Anguilla', 'AI', 'AIA', 'ANG', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (8, 'Antarctica', 'AQ', 'ATA', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (9, 'Antigua and Barbuda', 'AG', 'ATG', 'ANT', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (10, 'Argentina', 'AR', 'ARG', 'ARG', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (11, 'Armenia', 'AM', 'ARM', 'ARM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (12, 'Aruba', 'AW', 'ABW', 'ARU', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (13, 'Australia', 'AU', 'AUS', 'AUS', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (14, 'Austria', 'AT', 'AUT', 'AUT', 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (15, 'Azerbaijan', 'AZ', 'AZE', 'AZE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (16, 'Bahamas', 'BS', 'BHS', 'BMS', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (17, 'Bahrain', 'BH', 'BHR', 'BAH', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (18, 'Bangladesh', 'BD', 'BGD', 'BAN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (19, 'Barbados', 'BB', 'BRB', 'BAR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (20, 'Belarus', 'BY', 'BLR', 'BLR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (21, 'Belgium', 'BE', 'BEL', 'BGM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (22, 'Belize', 'BZ', 'BLZ', 'BEL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (23, 'Benin', 'BJ', 'BEN', 'BEN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (24, 'Bermuda', 'BM', 'BMU', 'BER', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (25, 'Bhutan', 'BT', 'BTN', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (26, 'Bolivia', 'BO', 'BOL', 'BOL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (27, 'Bosnia and Herzegowina', 'BA', 'BIH', 'BOS', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (28, 'Botswana', 'BW', 'BWA', 'BOT', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (29, 'Bouvet Island', 'BV', 'BVT', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (30, 'Brazil', 'BR', 'BRA', 'BRA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (31, 'British Indian Ocean Territory', 'IO', 'IOT', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (32, 'Brunei Darussalam', 'BN', 'BRN', 'BRU', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (33, 'Bulgaria', 'BG', 'BGR', 'BUL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (34, 'Burkina Faso', 'BF', 'BFA', 'BKF', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (35, 'Burundi', 'BI', 'BDI', 'BUR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (36, 'Cambodia', 'KH', 'KHM', 'CAM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (37, 'Cameroon', 'CM', 'CMR', 'CMR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (38, 'Canada', 'CA', 'CAN', 'CAN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (39, 'Cape Verde', 'CV', 'CPV', 'CAP', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (40, 'Cayman Islands', 'KY', 'CYM', 'CAY', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (41, 'Central African Republic', 'CF', 'CAF', 'CEN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (42, 'Chad', 'TD', 'TCD', 'CHA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (43, 'Chile', 'CL', 'CHL', 'CHL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (44, 'China', 'CN', 'CHN', 'CHN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (45, 'Christmas Island', 'CX', 'CXR', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (46, 'Cocos (Keeling) Islands', 'CC', 'CCK', 'COO', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (47, 'Colombia', 'CO', 'COL', 'COL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (48, 'Comoros', 'KM', 'COM', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (49, 'Congo', 'CG', 'COG', 'CON', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (50, 'Cook Islands', 'CK', 'COK', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (51, 'Costa Rica', 'CR', 'CRI', 'COS', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (52, 'Cote D\'Ivoire', 'CI', 'CIV', 'COT', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (53, 'Croatia', 'HR', 'HRV', 'CRO', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (54, 'Cuba', 'CU', 'CUB', 'CUB', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (55, 'Cyprus', 'CY', 'CYP', 'CYP', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (56, 'Czech Republic', 'CZ', 'CZE', 'CZE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (57, 'Denmark', 'DK', 'DNK', 'DEN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (58, 'Djibouti', 'DJ', 'DJI', 'DJI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (59, 'Dominica', 'DM', 'DMA', 'DOM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (60, 'Dominican Republic', 'DO', 'DOM', 'DRP', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (61, 'East Timor', 'TP', 'TMP', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (62, 'Ecuador', 'EC', 'ECU', 'ECU', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (63, 'Egypt', 'EG', 'EGY', 'EGY', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (64, 'El Salvador', 'SV', 'SLV', 'EL_', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (65, 'Equatorial Guinea', 'GQ', 'GNQ', 'EQU', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (66, 'Eritrea', 'ER', 'ERI', 'ERI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (67, 'Estonia', 'EE', 'EST', 'EST', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (68, 'Ethiopia', 'ET', 'ETH', 'ETH', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (70, 'Faroe Islands', 'FO', 'FRO', 'FAR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (71, 'Fiji', 'FJ', 'FJI', 'FIJ', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (72, 'Finland', 'FI', 'FIN', 'FIN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (73, 'France', 'FR', 'FRA', 'FRA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (74, 'France, Metropolitan', 'FX', 'FXX', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (75, 'French Guiana', 'GF', 'GUF', 'FRE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (76, 'French Polynesia', 'PF', 'PYF', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (77, 'French Southern Territories', 'TF', 'ATF', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (78, 'Gabon', 'GA', 'GAB', 'GAB', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (79, 'Gambia', 'GM', 'GMB', 'GAM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (80, 'Georgia', 'GE', 'GEO', 'GEO', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (81, 'Germany', 'DE', 'DEU', 'GER', 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (82, 'Ghana', 'GH', 'GHA', 'GHA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (83, 'Gibraltar', 'GI', 'GIB', 'GIB', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (84, 'Greece', 'GR', 'GRC', 'GRC', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (85, 'Greenland', 'GL', 'GRL', 'GRL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (86, 'Grenada', 'GD', 'GRD', 'GRE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (87, 'Guadeloupe', 'GP', 'GLP', 'GDL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (88, 'Guam', 'GU', 'GUM', 'GUM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (89, 'Guatemala', 'GT', 'GTM', 'GUA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (90, 'Guinea', 'GN', 'GIN', 'GUI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (91, 'Guinea-bissau', 'GW', 'GNB', 'GBS', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (92, 'Guyana', 'GY', 'GUY', 'GUY', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (93, 'Haiti', 'HT', 'HTI', 'HAI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (94, 'Heard and Mc Donald Islands', 'HM', 'HMD', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (95, 'Honduras', 'HN', 'HND', 'HON', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (96, 'Hong Kong', 'HK', 'HKG', 'HKG', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (97, 'Hungary', 'HU', 'HUN', 'HUN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (98, 'Iceland', 'IS', 'ISL', 'ICE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (99, 'India', 'IN', 'IND', 'IND', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (100, 'Indonesia', 'ID', 'IDN', 'IDS', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (101, 'Iran (Islamic Republic of)', 'IR', 'IRN', 'IRN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (102, 'Iraq', 'IQ', 'IRQ', 'IRA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (103, 'Ireland', 'IE', 'IRL', 'IRE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (104, 'Israel', 'IL', 'ISR', 'ISR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (105, 'Italy', 'IT', 'ITA', 'ITA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (106, 'Jamaica', 'JM', 'JAM', 'JAM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (107, 'Japan', 'JP', 'JPN', 'JAP', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (108, 'Jordan', 'JO', 'JOR', 'JOR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (109, 'Kazakhstan', 'KZ', 'KAZ', 'KAZ', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (110, 'Kenya', 'KE', 'KEN', 'KEN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (111, 'Kiribati', 'KI', 'KIR', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (112, 'Korea, Democratic People\'s Republic of', 'KP', 'PRK', 'SKO', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (113, 'Korea, Republic of', 'KR', 'KOR', 'KOR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (114, 'Kuwait', 'KW', 'KWT', 'KUW', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (115, 'Kyrgyzstan', 'KG', 'KGZ', 'KYR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (116, 'Lao People\'s Democratic Republic', 'LA', 'LAO', 'LAO', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (117, 'Latvia', 'LV', 'LVA', 'LAT', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (118, 'Lebanon', 'LB', 'LBN', 'LEB', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (119, 'Lesotho', 'LS', 'LSO', 'LES', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (120, 'Liberia', 'LR', 'LBR', 'LIB', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', 'LBY', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (122, 'Liechtenstein', 'LI', 'LIE', 'LIE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (123, 'Lithuania', 'LT', 'LTU', 'LIT', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (124, 'Luxembourg', 'LU', 'LUX', 'LUX', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (125, 'Macau', 'MO', 'MAC', 'MAC', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (126, 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MKD', 'F.Y', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (127, 'Madagascar', 'MG', 'MDG', 'MAD', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (128, 'Malawi', 'MW', 'MWI', 'MLW', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (129, 'Malaysia', 'MY', 'MYS', 'MLS', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (130, 'Maldives', 'MV', 'MDV', 'MAL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (131, 'Mali', 'ML', 'MLI', 'MLI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (132, 'Malta', 'MT', 'MLT', 'MLT', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (133, 'Marshall Islands', 'MH', 'MHL', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (134, 'Martinique', 'MQ', 'MTQ', 'MAR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (135, 'Mauritania', 'MR', 'MRT', 'MRT', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (136, 'Mauritius', 'MU', 'MUS', 'MAU', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (137, 'Mayotte', 'YT', 'MYT', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (138, 'Mexico', 'MX', 'MEX', 'MEX', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (139, 'Micronesia, Federated States of', 'FM', 'FSM', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (140, 'Moldova, Republic of', 'MD', 'MDA', 'MOL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (141, 'Monaco', 'MC', 'MCO', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (142, 'Mongolia', 'MN', 'MNG', 'MON', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (143, 'Montserrat', 'MS', 'MSR', 'MTT', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (144, 'Morocco', 'MA', 'MAR', 'MOR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (145, 'Mozambique', 'MZ', 'MOZ', 'MOZ', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (146, 'Myanmar', 'MM', 'MMR', 'MYA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (147, 'Namibia', 'NA', 'NAM', 'NAM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (148, 'Nauru', 'NR', 'NRU', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (149, 'Nepal', 'NP', 'NPL', 'NEP', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (150, 'Netherlands', 'NL', 'NLD', 'NED', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (151, 'Netherlands Antilles', 'AN', 'ANT', 'NET', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (152, 'New Caledonia', 'NC', 'NCL', 'CDN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (153, 'New Zealand', 'NZ', 'NZL', 'NEW', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (154, 'Nicaragua', 'NI', 'NIC', 'NIC', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (155, 'Niger', 'NE', 'NER', 'NIG', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (156, 'Nigeria', 'NG', 'NGA', 'NGR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (157, 'Niue', 'NU', 'NIU', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (158, 'Norfolk Island', 'NF', 'NFK', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (159, 'Northern Mariana Islands', 'MP', 'MNP', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (160, 'Norway', 'NO', 'NOR', 'NWY', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (161, 'Oman', 'OM', 'OMN', 'OMA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (162, 'Pakistan', 'PK', 'PAK', 'PAK', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (163, 'Palau', 'PW', 'PLW', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (164, 'Panama', 'PA', 'PAN', 'PAN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (165, 'Papua New Guinea', 'PG', 'PNG', 'PAP', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (166, 'Paraguay', 'PY', 'PRY', 'PAR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (167, 'Peru', 'PE', 'PER', 'PER', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (168, 'Philippines', 'PH', 'PHL', 'PHI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (169, 'Pitcairn', 'PN', 'PCN', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (170, 'Poland', 'PL', 'POL', 'POL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (171, 'Portugal', 'PT', 'PRT', 'POR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (172, 'Puerto Rico', 'PR', 'PRI', 'PUE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (173, 'Qatar', 'QA', 'QAT', 'QAT', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (174, 'Reunion', 'RE', 'REU', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (175, 'Romania', 'RO', 'ROM', 'ROM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (176, 'Russian Federation', 'RU', 'RUS', 'RUS', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (177, 'Rwanda', 'RW', 'RWA', 'RWA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (178, 'Saint Kitts and Nevis', 'KN', 'KNA', 'SKN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (179, 'Saint Lucia', 'LC', 'LCA', 'SLU', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 'ST.', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (181, 'Samoa', 'WS', 'WSM', 'WES', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (182, 'San Marino', 'SM', 'SMR', 'SAN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (183, 'Sao Tome and Principe', 'ST', 'STP', 'SAO', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (184, 'Saudi Arabia', 'SA', 'SAU', 'SAU', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (185, 'Senegal', 'SN', 'SEN', 'SEN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (186, 'Seychelles', 'SC', 'SYC', 'SEY', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (187, 'Sierra Leone', 'SL', 'SLE', 'SIE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (188, 'Singapore', 'SG', 'SGP', 'SIN', 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 'SLO', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (190, 'Slovenia', 'SI', 'SVN', 'SLV', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (191, 'Solomon Islands', 'SB', 'SLB', 'SOL', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (192, 'Somalia', 'SO', 'SOM', 'SOM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (193, 'South Africa', 'ZA', 'ZAF', 'SOU', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (195, 'Spain', 'ES', 'ESP', 'SPA', 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (196, 'Sri Lanka', 'LK', 'LKA', 'SRI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (197, 'St. Helena', 'SH', 'SHN', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (198, 'St. Pierre and Miquelon', 'PM', 'SPM', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (199, 'Sudan', 'SD', 'SDN', 'SUD', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (200, 'Suriname', 'SR', 'SUR', 'SUR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (202, 'Swaziland', 'SZ', 'SWZ', 'SWA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (203, 'Sweden', 'SE', 'SWE', 'SWE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (204, 'Switzerland', 'CH', 'CHE', 'ZWI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (205, 'Syrian Arab Republic', 'SY', 'SYR', 'SYR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (206, 'Taiwan', 'TW', 'TWN', 'TWN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (207, 'Tajikistan', 'TJ', 'TJK', 'TAJ', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (208, 'Tanzania, United Republic of', 'TZ', 'TZA', 'TAN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (209, 'Thailand', 'TH', 'THA', 'THA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (210, 'Togo', 'TG', 'TGO', 'TOG', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (211, 'Tokelau', 'TK', 'TKL', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (212, 'Tonga', 'TO', 'TON', 'TON', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (213, 'Trinidad and Tobago', 'TT', 'TTO', 'TRI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (214, 'Tunisia', 'TN', 'TUN', 'TUN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (215, 'Turkey', 'TR', 'TUR', 'TUR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (216, 'Turkmenistan', 'TM', 'TKM', 'TKM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (217, 'Turks and Caicos Islands', 'TC', 'TCA', 'TCI', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (218, 'Tuvalu', 'TV', 'TUV', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (219, 'Uganda', 'UG', 'UGA', 'UGA', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (220, 'Ukraine', 'UA', 'UKR', 'UKR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (221, 'United Arab Emirates', 'AE', 'ARE', 'UAE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (222, 'United Kingdom', 'GB', 'GBR', 'GBR', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (223, 'United States', 'US', 'USA', 'UNI', 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (224, 'United States Minor Outlying Islands', 'UM', 'UMI', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (225, 'Uruguay', 'UY', 'URY', 'URU', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (226, 'Uzbekistan', 'UZ', 'UZB', 'UZB', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (227, 'Vanuatu', 'VU', 'VUT', 'VAN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (228, 'Vatican City State (Holy See)', 'VA', 'VAT', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (229, 'Venezuela', 'VE', 'VEN', 'VEN', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (230, 'Viet Nam', 'VN', 'VNM', 'VIE', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (231, 'Virgin Islands (British)', 'VG', 'VGB', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (232, 'Virgin Islands (U.S.)', 'VI', 'VIR', 'US_', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (233, 'Wallis and Futuna Islands', 'WF', 'WLF', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (234, 'Western Sahara', 'EH', 'ESH', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (235, 'Yemen', 'YE', 'YEM', 'YEM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (236, 'Yugoslavia', 'YU', 'YUG', 'YUG', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (237, 'Zaire', 'ZR', 'ZAR', '', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (238, 'Zambia', 'ZM', 'ZMB', 'ZAM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (239, 'Zimbabwe', 'ZW', 'ZWE', 'ZIM', 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "countries " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "currencies (currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value, last_updated) VALUES (1, 'Euro', 'EUR', '', '€', '.', ', ', '2', '1.00000000', " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "currencies</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "currencies (currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value, last_updated) VALUES (2, 'US Dollar', 'USD', '$', '', '.', ', ', '2', '0.98000002', " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "currencies</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "currencies " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 1, 'Admin', 'smile-yellow.gif', '0', '0.00', '0.00', '0', '1', '1', '1', 'banktransfer.php;cod.php;moneyorder.php')") OR die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 2, 'Admin', 'smile-yellow.gif', '0', '0.00', '0.00', '0', '1', '1', '1', 'banktransfer.php;cod.php;moneyorder.php')") OR die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 1, 'gast', 'smile-green.gif', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") OR die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 2, 'guest', 'smile-green.gif', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") OR die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "customers_status " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, date_added) VALUES (1, 'Europaeische Union', 'Fuer alle Kunden innerhalb der europaeische Union', " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "geo_zones " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (1, 'specials.gif', '1', " . $db->DBTimeStamp($today) . ", NULL, '1' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (2, 'specials.gif', '2', " . $db->DBTimeStamp($today) . ", NULL, '1' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (3, 'specials.gif', '3', " . $db->DBTimeStamp($today) . ", NULL, '1' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (4, 'specials.gif', '4', " . $db->DBTimeStamp($today) . ", NULL, '1' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (5, 'specials.gif', '5', " . $db->DBTimeStamp($today) . ", NULL, '1' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "information " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (1, 1, '', 'Liefer- und Versandbedingungen', 'Liefer- und Versandbedingungen', 'Fügen Sie hier Ihre Liefer- und Versandbedingungen ein' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (1, 2, '', 'Shipping & Returns', 'Shipping & Returns', 'Put here your Shipping & Returns information' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (2, 1, '', 'Privatsphäre und Datenschutz', 'Privatsphäre und Datenschutz', 'Fügen Sie hier Ihre Informationen zur Privatsphäre und Datenschutz ein' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (2, 2, '', 'Privacy Notice', 'Privacy Notice', 'Put here your Privacy Notice information' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (3, 1, '', 'Unsere AGB', 'Unsere AGB', 'Fügen Sie hier Ihre AGB ein' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (3, 2, '', 'Conditions of Use', 'Conditions of Use', 'Put here your Conditions of Use information' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (4, 1, '', 'Impressum', 'Impressum', 'Fügen Sie hier Ihre Informationen zum Impressum ein.' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (4, 2, '', 'Imprint', 'Imprint', 'Put here your information about your company' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (5, 1, '', 'Haftungsausschluss', 'Haftungsausschluss', 'Fügen Sie hier Ihren Haftungsausschluss ein' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (5, 2, '', 'Disclaimer', 'Disclaimer', 'Put here your Disclaimer' )") OR die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "information_description " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (1, 'Deutsch', 'deu', 'de', 1, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (2, 'English', 'eng', 'en', 1, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "languages " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "manual_info (man_info_id, man_name, status, manual_date_added, defined) VALUES ('1', 'Manual Entry', 0, " . $db->DBTimeStamp($today) . ",  'admin_log')") OR die ("<b>".NOTUPDATED . $prefix_table . "manual_info</b>");
echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "manual_info " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (1, 2, 'Pending')") OR die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (1, 1, 'Offen')") OR die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (2, 2, 'Processing')") OR die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (2, 1, 'In Bearbeitung')") OR die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (3, 2, 'Delivered')") OR die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (3, 1, 'Versendet')") OR die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "orders_status " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (1, 2, 'Frontpage')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (1, 1, 'Startseite')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (2, 2, 'Shop')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (2, 1, 'Shop')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (3, 2, 'Products')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (3, 1, 'Produkte')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (4, 2, 'News')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (4, 1, 'News')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (5, 2, 'Service')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (5, 1, 'Service')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (6, 2, 'Checkout')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (6, 1, 'Kasse')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (8, 1, 'Kundenkonto')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (8, 2, 'Account')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (9, 1, 'Meinungen: Produkte')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (9, 6, 'Reviews: Products')") OR die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "page_type " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (1, 2, 'Out of Stock')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (1, 1, 'nicht vorrätig')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (2, 2, 'Available Soon')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (2, 1, 'bald verfübar')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (3, 2, 'In Stock')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (3, 1, 'auf Lager')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "products_status " . UPDATED .'</font>';


// products_options_types
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (1, 2, 'Select')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (1, 1, 'Select')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (2, 2, 'Checkbox')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (2, 1, 'Checkbox')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (3, 2, 'Radio')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (3, 1, 'Radio')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (4, 2, 'Text')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (4, 1, 'Text')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (5, 2, 'Textarea')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (5, 1, 'Textarea')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (6, 2, 'File')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (6, 1, 'File')") OR die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "products_options_types " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) VALUES (1, 'German Normal', 'normaler Steuersatz für Dienstleistungen und alle non-food Artikel', NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "tax_class</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) VALUES (2, 'German Vermindert', 'verminderter Steuersatz für Lebensmittel und Bücher', NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "tax_class</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "tax_class " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_priority, tax_rate, tax_description, last_modified, date_added) VALUES (1, 1, 1, 1, '19', 'enthaltene MwSt. 19%', NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_priority, tax_rate, tax_description, last_modified, date_added) VALUES (2, 1, 2, 1, '7', 'enthaltene MwSt. 7%', NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "tax_rates " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (66, 38, 'AB', 'Alberta')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (67, 38, 'BC', 'British Columbia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (68, 38, 'MB', 'Manitoba')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (69, 38, 'NF', 'Newfoundland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (70, 38, 'NB', 'New Brunswick')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (71, 38, 'NS', 'Nova Scotia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (72, 38, 'NT', 'Northwest Territories')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (73, 38, 'NU', 'Nunavut')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (74, 38, 'ON', 'Ontario')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (75, 38, 'PE', 'Prince Edward Island')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (76, 38, 'QC', 'Quebec')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (77, 38, 'SK', 'Saskatchewan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (78, 38, 'YT', 'Yukon Territory')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (79, 81, 'NDS', 'Niedersachsen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (80, 81, 'BAW', 'Baden-Württemberg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (81, 81, 'BAY', 'Bayern')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (82, 81, 'BER', 'Berlin')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (83, 81, 'BRG', 'Brandenburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (84, 81, 'BRE', 'Bremen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (85, 81, 'HAM', 'Hamburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (86, 81, 'HES', 'Hessen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (87, 81, 'MEC', 'Mecklenburg-Vorpommern')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (88, 81, 'NRW', 'Nordrhein-Westfalen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (89, 81, 'RHE', 'Rheinland-Pfalz')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (90, 81, 'SAR', 'Saarland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (91, 81, 'SAS', 'Sachsen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (92, 81, 'SAC', 'Sachsen-Anhalt')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (93, 81, 'SCN', 'Schleswig-Holstein')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (94, 81, 'THE', 'Thüringen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (95, 14, 'WI', 'Wien')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (96, 14, 'NO', 'Nieder&ouml;sterreich')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (97, 14, 'OO', 'Ober&ouml;sterreich')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (98, 14, 'SB', 'Salzburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (99, 14, 'KN', 'Kärnten')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (100, 14, 'ST', 'Steiermark')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (101, 14, 'TI', 'Tirol')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (102, 14, 'BL', 'Burgenland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (103, 14, 'VB', 'Voralberg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (104, 204, 'AG', 'Aargau')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (105, 204, 'AI', 'Appenzell Innerrhoden')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (106, 204, 'AR', 'Appenzell Ausserrhoden')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (107, 204, 'BE', 'Bern')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (108, 204, 'BL', 'Basel-Landschaft')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (109, 204, 'BS', 'Basel-Stadt')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (110, 204, 'FR', 'Freiburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (111, 204, 'GE', 'Genf')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (112, 204, 'GL', 'Glarus')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (113, 204, 'GR', 'Graubünden')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (114, 204, 'JU', 'Jura')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (115, 204, 'LU', 'Luzern')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (116, 204, 'NE', 'Neuenburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (117, 204, 'NW', 'Nidwalden')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (118, 204, 'OW', 'Obwalden')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (119, 204, 'SG', 'St. Gallen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (120, 204, 'SH', 'Schaffhausen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (121, 204, 'SO', 'Solothurn')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (122, 204, 'SZ', 'Schwyz')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (123, 204, 'TG', 'Thurgau')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (124, 204, 'TI', 'Tessin')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (125, 204, 'UR', 'Uri')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (126, 204, 'VD', 'Waadt')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (127, 204, 'VS', 'Wallis')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (128, 204, 'ZG', 'Zug')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (129, 204, 'ZH', 'Zürich')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (130, 195, 'A CoruÃ±a', 'A CoruÃ±a')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (131, 195, 'Alava', 'Alava')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (132, 195, 'Albacete', 'Albacete')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (133, 195, 'Alicante', 'Alicante')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (134, 195, 'Almeria', 'Almeria')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (135, 195, 'Asturias', 'Asturias')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (136, 195, 'Avila', 'Avila')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (137, 195, 'Badajoz', 'Badajoz')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (138, 195, 'Baleares', 'Baleares')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (139, 195, 'Barcelona', 'Barcelona')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (140, 195, 'Burgos', 'Burgos')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (141, 195, 'Caceres', 'Caceres')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (142, 195, 'Cadiz', 'Cadiz')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (143, 195, 'Cantabria', 'Cantabria')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (144, 195, 'Castellon', 'Castellon')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (145, 195, 'Ceuta', 'Ceuta')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (146, 195, 'Ciudad Real', 'Ciudad Real')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (147, 195, 'Cordoba', 'Cordoba')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (148, 195, 'Cuenca', 'Cuenca')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (149, 195, 'Girona', 'Girona')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (150, 195, 'Granada', 'Granada')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (151, 195, 'Guadalajara', 'Guadalajara')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (152, 195, 'Guipuzcoa', 'Guipuzcoa')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (153, 195, 'Huelva', 'Huelva')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (154, 195, 'Huesca', 'Huesca')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (155, 195, 'Jaen', 'Jaen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (156, 195, 'La Rioja', 'La Rioja')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (157, 195, 'Las Palmas', 'Las Palmas')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (158, 195, 'Leon', 'Leon')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (159, 195, 'Lleida', 'Lleida')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (160, 195, 'Lugo', 'Lugo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (161, 195, 'Madrid', 'Madrid')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (162, 195, 'Malaga', 'Malaga')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (163, 195, 'Melilla', 'Melilla')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (164, 195, 'Murcia', 'Murcia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (165, 195, 'Navarra', 'Navarra')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (166, 195, 'Ourense', 'Ourense')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (167, 195, 'Palencia', 'Palencia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (168, 195, 'Pontevedra', 'Pontevedra')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (169, 195, 'Salamanca', 'Salamanca')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (170, 195, 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (171, 195, 'Segovia', 'Segovia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (172, 195, 'Sevilla', 'Sevilla')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (173, 195, 'Soria', 'Soria')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (174, 195, 'Tarragona', 'Tarragona')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (175, 195, 'Teruel', 'Teruel')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (176, 195, 'Toledo', 'Toledo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (177, 195, 'Valencia', 'Valencia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (178, 195, 'Valladolid', 'Valladolid')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (179, 195, 'Vizcaya', 'Vizcaya')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (180, 195, 'Zamora', 'Zamora')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (181, 195, 'Zaragoza', 'Zaragoza')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (182, 103, '01', 'Carlow')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (183, 103, '02', 'Cavan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (184, 103, '03', 'Clare')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (185, 103, '04', 'Cork')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (186, 103, '05', 'Donegal')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (187, 103, '06', 'Dublin')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (188, 103, '07', 'Galway')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (189, 103, '08', 'Kerry')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (190, 103, '09', 'Kildare')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (191, 103, '10', 'Kilkenny')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (192, 103, '11', 'Laois')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (193, 103, '12', 'Leitrim')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (194, 103, '13', 'Limerick')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (195, 103, '14', 'Longford')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (196, 103, '15', 'Louth')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (197, 103, '16', 'Mayo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (198, 103, '17', 'Meath')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (199, 103, '18', 'Monaghan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (200, 103, '19', 'Offaly')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (201, 103, '20', 'Roscommon')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (202, 103, '21', 'Sligo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (203, 103, '22', 'Tipperary')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (204, 103, '23', 'Waterford')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (205, 103, '24', 'Westmeath')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (206, 103, '25', 'Wexford')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (207, 103, '26', 'Wicklow')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (208, 105, 'AG', 'Agrigento')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (209, 105, 'AL', 'Alessandria')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (210, 105, 'AN', 'Ancona')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (211, 105, 'AO', 'Aosta')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (212, 105, 'AR', 'Arezzo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (213, 105, 'AP', 'Ascoli Piceno')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (214, 105, 'AT', 'Asti')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (215, 105, 'AV', 'Avellino')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (216, 105, 'BA', 'Bari')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (217, 105, 'BL', 'Belluno')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (218, 105, 'BN', 'Benevento')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (219, 105, 'BG', 'Bergamo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (220, 105, 'BI', 'Biella')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (221, 105, 'BO', 'Bologna')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (222, 105, 'BZ', 'Bolzano')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (223, 105, 'BS', 'Brescia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (224, 105, 'BR', 'Brindisi')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (225, 105, 'CA', 'Cagliari')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (226, 105, 'CL', 'Caltanissetta')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (227, 105, 'CB', 'Campobasso')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (228, 105, 'CE', 'Caserta')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (229, 105, 'CT', 'Catania')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (230, 105, 'CZ', 'Catanzaro')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (231, 105, 'CH', 'Chieti')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (232, 105, 'CO', 'Como')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (233, 105, 'CS', 'Cosenza')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (234, 105, 'CR', 'Cremona')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (235, 105, 'KR', 'Crotone')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (236, 105, 'CN', 'Cuneo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (237, 105, 'EN', 'Enna')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (238, 105, 'FE', 'Ferrara')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (239, 105, 'FI', 'Firenze')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (240, 105, 'FG', 'Foggia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (241, 105, 'FC', 'ForlÃ¬-Cesena')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (242, 105, 'FR', 'Frosinone')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (243, 105, 'GE', 'Genova')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (244, 105, 'GO', 'Gorizia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (245, 105, 'GR', 'Grosseto')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (246, 105, 'IM', 'Imperia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (247, 105, 'IS', 'Isernia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (248, 105, 'AQ', 'Aquila')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (249, 105, 'SP', 'La Spezia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (250, 105, 'LT', 'Latina')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (251, 105, 'LE', 'Lecce')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (252, 105, 'LC', 'Lecco')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (253, 105, 'LI', 'Livorno')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (254, 105, 'LO', 'Lodi')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (255, 105, 'LU', 'Lucca')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (256, 105, 'MC', 'Macerata')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (257, 105, 'MN', 'Mantova')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (258, 105, 'MS', 'Massa-Carrara')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (259, 105, 'MT', 'Matera')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (260, 105, 'ME', 'Messina')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (261, 105, 'MI', 'Milano')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (262, 105, 'MO', 'Modena')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (263, 105, 'NA', 'Napoli')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (264, 105, 'NO', 'Novara')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (265, 105, 'NU', 'Nuoro')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (266, 105, 'OR', 'Oristano')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (267, 105, 'PD', 'Padova')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (268, 105, 'PA', 'Palermo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (269, 105, 'PR', 'Parma')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (270, 105, 'PG', 'Perugia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (271, 105, 'PV', 'Pavia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (272, 105, 'PS', 'Pesaro e Urbino')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (273, 105, 'PE', 'Pescara')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (274, 105, 'PC', 'Piacenza')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (275, 105, 'PI', 'Pisa')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (276, 105, 'PT', 'Pistoia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (277, 105, 'PN', 'Pordenone')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (278, 105, 'PZ', 'Potenza')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (279, 105, 'PO', 'Prato')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (280, 105, 'RG', 'Ragusa')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (281, 105, 'RA', 'Ravenna')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (282, 105, 'RC', 'Reggio di Calabria')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (283, 105, 'RE', 'Reggio Emilia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (284, 105, 'RI', 'Rieti')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (285, 105, 'RN', 'Rimini')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (286, 105, 'RM', 'Roma')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (287, 105, 'RO', 'Rovigo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (288, 105, 'SA', 'Salerno')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (289, 105, 'SS', 'Sassari')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (290, 105, 'SV', 'Savona')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (291, 105, 'SI', 'Siena')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (292, 105, 'SR', 'Siracusa')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (293, 105, 'SO', 'Sondrio')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (294, 105, 'TA', 'Taranto')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (295, 105, 'TE', 'Teramo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (296, 105, 'TR', 'Terni')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (297, 105, 'TO', 'Torino')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (298, 105, 'TP', 'Trapani')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (299, 105, 'TN', 'Trento')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (300, 105, 'TV', 'Treviso')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (301, 105, 'TS', 'Trieste')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (302, 105, 'UD', 'Udine')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (303, 105, 'VA', 'Varese')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (304, 105, 'VE', 'Venezia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (305, 105, 'VB', 'Verbania')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (306, 105, 'VC', 'Vercelli')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (307, 105, 'VR', 'Verona')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (308, 105, 'VV', 'Vibo Valentia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (309, 105, 'VI', 'Vicenza')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (310, 105, 'VT', 'Viterbo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (311, 150, 'Drenthe', 'Drenthe')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (312, 150, 'Flevoland', 'Flevoland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (313, 150, 'Friesland', 'Friesland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (314, 150, 'Gelderland', 'Gelderland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (315, 150, 'Groningen', 'Groningen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (316, 150, 'Limburg', 'Limburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (317, 150, 'Noord-Brabant', 'Noord-Brabant')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (318, 150, 'Noord-Holland', 'Noord-Holland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (319, 150, 'Overijssel', 'Overijssel')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (320, 150, 'Utrecht', 'Utrecht')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (321, 150, 'Zeeland', 'Zeeland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (322, 150, 'Zuid_Holland', 'Zuid_Holland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (323, 222, 'ALD', 'Alderney')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (324, 222, 'ATM', 'County Antrim')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (325, 222, 'ARM', 'County Armagh')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (326, 222, 'AVN', 'Avon')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (327, 222, 'BFD', 'Bedfordshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (328, 222, 'BRK', 'Berkshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (329, 222, 'BDS', 'Borders')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (330, 222, 'BUX', 'Buckinghamshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (331, 222, 'CBE', 'Cambridgeshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (332, 222, 'CTR', 'Central')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (333, 222, 'CHS', 'Cheshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (334, 222, 'CVE', 'Cleveland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (335, 222, 'CLD', 'Clwyd')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (336, 222, 'CNL', 'Cornwall')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (337, 222, 'CBA', 'Cumbria')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (338, 222, 'DYS', 'Derbyshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (339, 222, 'DVN', 'Devon')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (340, 222, 'DOR', 'Dorse')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (341, 222, 'DWN', 'County Down')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (342, 222, 'DGL', 'Dumfries and Galloway')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (343, 222, 'DHM', 'County Durham')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (344, 222, 'DFD', 'Dyfed')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (345, 222, 'ESX', 'Essex')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (346, 222, 'FMH', 'County Fermanagh')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (347, 222, 'FFE', 'Fife')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (348, 222, 'GNM', 'Mid Glamorgan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (349, 222, 'GNS', 'South Glamorgan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (350, 222, 'GNW', 'West Glamorgan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (351, 222, 'GLR', 'Gloucester')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (352, 222, 'GRN', 'Grampian')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (353, 222, 'GUR', 'Guernsey')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (354, 222, 'GWT', 'Gwent')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (355, 222, 'GDD', 'Gwynedd')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (356, 222, 'HPH', 'Hampshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (357, 222, 'HWR', 'Hereford and Worcester')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (358, 222, 'HFD', 'Hertfordshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (359, 222, 'HLD', 'Highlands')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (360, 222, 'HBS', 'Humberside')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (361, 222, 'IOM', 'Isle of Man')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (362, 222, 'IOW', 'Isle of Wight')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (363, 222, 'JER', 'Jersey')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (364, 222, 'KNT', 'Kent')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (365, 222, 'LNH', 'Lancashire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (366, 222, 'LEC', 'Leicestershire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (367, 222, 'LCN', 'Lincolnshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (368, 222, 'LDN', 'Greater London')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (369, 222, 'LDR', 'County Londonderry')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (370, 222, 'LTH', 'Lothian')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (371, 222, 'MCH', 'Greater Manchester')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (372, 222, 'MSY', 'Merseyside')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (373, 222, 'NOR', 'Norfolk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (374, 222, 'NHM', 'Northamptonshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (375, 222, 'NLD', 'Northumberland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (376, 222, 'NOT', 'Nottinghamshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (377, 222, 'ORK', 'Orkney')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (378, 222, 'OFE', 'Oxfordshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (379, 222, 'PWS', 'Powys')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (380, 222, 'SPE', 'Shropshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (381, 222, 'SRK', 'Sark')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (382, 222, 'SLD', 'Shetland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (383, 222, 'SOM', 'Somerset')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (384, 222, 'SFD', 'Staffordshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (385, 222, 'SCD', 'Strathclyde')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (386, 222, 'SFK', 'Suffolk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (387, 222, 'SRY', 'Surrey')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (388, 222, 'SXE', 'East Sussex')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (389, 222, 'SXW', 'West Sussex')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (390, 222, 'TYS', 'Tayside')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (391, 222, 'TWR', 'Tyne and Wear')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (392, 222, 'TYR', 'County Tyrone')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (393, 222, 'WKS', 'Warwickshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (394, 222, 'WIL', 'Western Isles')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (395, 222, 'WMD', 'West Midlands')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (396, 222, 'WLT', 'Wiltshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (397, 222, 'YSN', 'North Yorkshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (398, 222, 'YSS', 'South Yorkshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (399, 222, 'YSW', 'West Yorkshire')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Belgium
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('400',21,'AN','Antwerpen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('401',21,'BW','Brabant Wallon')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('402',21,'HA','Hainaut')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('403',21,'LG','Liege')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('404',21,'LM','Limburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('405',21,'LX','Luxembourg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('406',21,'NM','Namur')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('407',21,'OV','Oost-Vlaanderen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('408',21,'VB','Vlaams Brabant')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('409',21,'WV','West-Vlaanderen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Denmark
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('410',57,'AR','Arhus')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('420',57,'BO','Bornholm')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('421',57,'FR','Frederiksborg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('422',57,'FY','Fyn')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('423',57,'KO','Kobenhavn')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('424',57,'NO','Nordjylland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('425',57,'RI','Ribe')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('426',57,'RK','Ringkobing')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('427',57,'RO','Roskilde')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('428',57,'SO','Sonderjylland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('429',57,'ST','Storstrom')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('430',57,'VE','Vejle')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('431',57,'VJ','VestjÃ¦lland')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('432',57,'VI','Viborg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Greece
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('433',84,'AI','Aitolia kai Akarnania')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('434',84,'AK','Akhaia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('435',84,'AG','Argolis')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('436',84,'AD','Arkadhia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('437',84,'AR','Arta')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('438',84,'AT','Attiki')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('439',84,'AY','Ayion Oros (Mt. Athos)')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('440',84,'DH','Dhodhekanisos')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('441',84,'DR','Drama')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('442',84,'ET','Evritania')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('443',84,'ES','Evros')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('444',84,'EV','Evvoia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('445',84,'FL','Florina')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('446',84,'FO','Fokis')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('447',84,'FT','Fthiotis')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('448',84,'GR','Grevena')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('449',84,'IL','Ilia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('450',84,'IM','Imathia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('451',84,'IO','Ioannina')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('452',84,'IR','Irakleion')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('453',84,'KA','Kardhitsa')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('454',84,'KS','Kastoria')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('455',84,'KV','Kavala')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('456',84,'KE','Kefallinia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('457',84,'KR','Kerkyra')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('458',84,'KH','Khalkidhiki')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('459',84,'KN','Khania')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('460',84,'KI','Khios')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('461',84,'KK','Kikladhes')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('462',84,'KL','Kilkis')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('463',84,'KO','Korinthia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('464',84,'KZ','Kozani')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('465',84,'LA','Lakonia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('466',84,'LR','Larisa')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('467',84,'LS','Lasithi')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('468',84,'LE','Lesvos')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('469',84,'LV','Levkas')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('470',84,'MA','Magnisia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('471',84,'ME','Messinia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('472',84,'PE','Pella')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('473',84,'PI','Pieria')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('474',84,'PR','Preveza')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('475',84,'RE','Rethimni')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('476',84,'RO','Rodhopi')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('477',84,'SA','Samos')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('478',84,'SE','Serrai')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('479',84,'TH','Thesprotia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('480',84,'TS','Thessaloniki')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('481',84,'TR','Trikala')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('482',84,'VO','Voiotia')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('483',84,'XA','Xanthi')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('484',84,'ZA','Zakinthos')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Luxembourg
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('485',124,'DI','Diekirch')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('486',124,'GR','Grevenmacher')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('487',124,'LU','Luxembourg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Poland
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('488',170,'DO','Dolnoslaskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('489',170,'KM','Kujawsko-Pomorskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('490',170,'LO','Lodzkie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('491',170,'LE','Lubelskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('492',170,'LU','Lubuskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('493',170,'ML','Malopolskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('494',170,'MZ','Mazowieckie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('495',170,'OP','Opolskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('496',170,'PK','Podkarpackie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('497',170,'PL','Podlaskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('498',170,'PM','Pomorskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('499',170,'SL','Slaskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('500',170,'SW','Swietokrzyskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('501',170,'WM','Warminsko-Mazurskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('502',170,'WI','Wielkopolskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('503',170,'ZA','Zachodniopomorskie')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Portugal
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('504',171,'AC','Acores (Azores)')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('505',171,'AV','Aveiro')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('506',171,'BE','Beja')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('507',171,'BR','Braga')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('508',171,'BA','Braganca')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('509',171,'CB','Castelo Branco')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('510',171,'CO','Coimbra')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('511',171,'EV','Evora')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('512',171,'FA','Faro')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('513',171,'GU','Guarda')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('514',171,'LE','Leiria')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('516',171,'LI','Lisboa')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('517',171,'ME','Madeira')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('518',171,'PO','Portalegre')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('519',171,'PR','Porto')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('520',171,'SA','Santarem')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('521',171,'SE','Setubal')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('522',171,'VC','Viana do Castelo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('523',171,'VR','Vila Real')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('524',171,'VI','Viseu')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");


#Russian Federation
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('525',176,'AB','Abakan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('526',176,'AG','Aginskoye')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('527',176,'AN','Anadyr')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('528',176,'AR','Arkahangelsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('529',176,'AS','Astrakhan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('530',176,'BA','Barnaul')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('531',176,'BE','Belgorod')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('532',176,'BI','Birobidzhan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('533',176,'BL','Blagoveshchensk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('534',176,'BR','Bryansk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('535',176,'CH','Cheboksary')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('536',176,'CL','Chelyabinsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('537',176,'CR','Cherkessk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('538',176,'CI','Chita')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('539',176,'DU','Dudinka')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('540',176,'EL','Elista')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('541',176,'GO','Gomo-Altaysk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('542',176,'GA','Gorno-Altaysk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('543',176,'GR','Groznyy')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('544',176,'IR','Irkutsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('545',176,'IV','Ivanovo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('546',176,'IZ','Izhevsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('547',176,'KA','Kalinigrad')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('548',176,'KL','Kaluga')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('549',176,'KS','Kasnodar')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('550',176,'KZ','Kazan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('551',176,'KE','Kemerovo')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('552',176,'KH','Khabarovsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('553',176,'KM','Khanty-Mansiysk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('554',176,'KO','Kostroma')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('555',176,'KR','Krasnodar')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('556',176,'KN','Krasnoyarsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('557',176,'KU','Kudymkar')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('558',176,'KG','Kurgan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('559',176,'KK','Kursk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('560',176,'KY','Kyzyl')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('561',176,'LI','Lipetsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('562',176,'MA','Magadan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('563',176,'MK','Makhachkala')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('564',176,'MY','Maykop')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('565',176,'MO','Moscow')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('566',176,'MU','Murmansk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('567',176,'NA','Nalchik')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('568',176,'NR','Naryan Mar')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('569',176,'NZ','Nazran')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('570',176,'NI','Nizhniy Novgorod')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('571',176,'NO','Novgorod')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('572',176,'NV','Novosibirsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('573',176,'OM','Omsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('574',176,'OR','Orel')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('575',176,'OE','Orenburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('576',176,'PA','Palana')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('577',176,'PE','Penza')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('578',176,'PR','Perm')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('579',176,'PK','Petropavlovsk-Kamchatskiy')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('580',176,'PT','Petrozavodsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('581',176,'PS','Pskov')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('582',176,'RO','Rostov-na-Donu')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('583',176,'RY','Ryazan')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('584',176,'SL','Salekhard')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('585',176,'SA','Samara')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('586',176,'SR','Saransk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('587',176,'SV','Saratov')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('588',176,'SM','Smolensk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('589',176,'SP','St. Petersburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('590',176,'ST','Stavropol')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('591',176,'SY','Syktyvkar')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('592',176,'TA','Tambov')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('593',176,'TO','Tomsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('594',176,'TU','Tula')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('595',176,'TR','Tura')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('596',176,'TV','Tver')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('597',176,'TY','Tyumen')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('598',176,'UF','Ufa')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('599',176,'UL','Ul\'yanovsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('600',176,'UU','Ulan-Ude')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('601',176,'US','Ust\'-Ordynskiy')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('602',176,'VL','Vladikavkaz')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('603',176,'VA','Vladimir')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('604',176,'VV','Vladivostok')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('605',176,'VG','Volgograd')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('606',176,'VD','Vologda')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('607',176,'VO','Voronezh')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('608',176,'VY','Vyatka')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('609',176,'YA','Yakutsk')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('610',176,'YR','Yaroslavl')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('611',176,'YE','Yekaterinburg')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('612',176,'YO','Yoshkar-Ola')") OR die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "zones " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (1, 14, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (2, 21, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (3, 57, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (4, 72, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (5, 73, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (6, 81, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (7, 84, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (8, 103, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (9, 105, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (10, 124, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (11, 150, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (12, 171, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (13, 195, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (14, 203, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (15, 222, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") OR die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "zones_to_geo_zones " . UPDATED .'</font>';

