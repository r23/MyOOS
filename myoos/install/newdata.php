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
   ----------------------------------------------------------------------
 */

$today = date("Y-m-d H:i:s");
$server = $_SERVER['HTTP_HOST'];

//
// 1 - Default, 2 Latvia, 3-4 Historic, 5 - Germany, 6 - UK/GB default, 7 - USA / Austrailia, 8 - Hong Kong, 9 - Italy, 10 - Singapore, 11 - Brazil, 12 - Peru, 13 - Nigeria, 14 - Panama, 15 - Oman, 16 - Venezuela, 17 - Philippians, 18 - Vietnam, 19 - Hungary, 20 - Spain
$address_format = '$firstname $lastname$cr$streets$cr$city, $postcode$cr$statecomma$country';
$address_summary = 'Default $city, $postcode / $state, $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (1, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country';
$address_summary = 'city, $state / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (2, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format =  '$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country';
$address_summary = 'Historic $city / $postcode - $statecomma$country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (3, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country';
$address_summary = 'Historic $city ($postcode)';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (4, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$postcode $city$cr$country';
$address_summary = '$city / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (5, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city$cr$state$cr$postcode$cr$country';
$address_summary = '$city / $state / $postcode';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (6, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city $state $postcode$cr$country';
$address_summary = '$city, $state / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (7, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city$cr$postcode $country';
$address_summary = '$city';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (8, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$postcode $city $state $postcode$cr$country';
$address_summary = '$postcode $city $state';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (9, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city $postcode$cr$country';
$address_summary = '$city / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (10, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city $state$cr$postcode$cr$country';
$address_summary = '$city $state / $postcode';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (11, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_summary = '$firstname $lastname$cr$streets$cr$postcode$cr$city $state$cr$country';
$address_summary = '$postcode / $city / $state';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (12, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city $postcode$cr$state$cr$country';
$address_summary = '$city $postcode / $state';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (13, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$postcode $city$cr$state$cr$country';
$address_summary = '$postcode $city / $state';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (14, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$postcode$cr$city$cr$state$cr$country';
$address_summary = '$postcode / $city / $state';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (15, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city $postcode $state$cr$country';
$address_summary = ' $city $postcode $state';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (16, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city$cr$postcode $state$cr$country';
$address_summary = '$city / $postcode $state';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (17, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city$cr$state $postcode$cr$country';
$address_summary = '$city / $state $postcode';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (18, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$city$cr$streets$cr$postcode$cr$country';
$address_summary = '$city $street / $postcode';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (19, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$postcode $city ($state)$cr$country';
$address_summary = '$postcode $city ($state)';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (20, '" . $address_format . "', '" . $address_summary . "')") or die("<b>".NOTUPDATED . $prefix_table . "address_format</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "address_format " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('administrator.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('configuration.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('catalog.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('modules.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('plugins.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('customers.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_admin.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('taxes.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('localization.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('export.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('reports.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('tools.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('information.php', 1, 0, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//administrator.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('admin_members', 0, 1, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('admin_files', 0, 1, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//configuration.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('configuration', 0, 2, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//catalog.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('categories', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('categories_slider', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('categories_panorama', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_attributes', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_properties', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('product_model_viewer', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('product_video', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('product_webgl_gltf', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('manufacturers', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('reviews', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_status', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_units', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('specials', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_expected', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('featured', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('wastebasket', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//modules.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('modules', 0, 4, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//plugins.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('plugins', 0, 5, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//customers.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('customers', 0, 6, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('orders', 0, 6, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('customers_status', 0, 6, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('orders_status', 0, 6, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('manual_loging', 0, 6, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//gv_admin.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('coupon_admin', 0, 9, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_queue', 0, 9, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_mail', 0, 9, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_sent', 0, 9, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('listcategories', 0, 9, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('listproducts', 0, 9, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('validproducts', 0, 9, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('validcategories', 0, 9, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//content.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_block', 0, 10, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_information', 0, 10, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_page_type', 0, 10, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//taxes.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('countries', 0, 14, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('zones', 0, 14, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('geo_zones', 0, 14, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('tax_classes', 0, 14, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('tax_rates', 0, 14, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//localization.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('currencies', 0, 15, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('languages', 0, 15, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//reports.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_customers', 0, 16, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_products_viewed', 0, 16, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_products_purchased', 0, 16, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_low_stock', 0, 16, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_sales_report2', 0, 16, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

// export.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('export_excel', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('import_excel', 0, 3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");


//tools.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('mail', 0, 17, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('newsletters', 0, 17, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//information.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('information', 0, 19, 1)") or die("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "admin_files " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_groups (admin_groups_id, admin_groups_name) VALUES (1, 'Top Administrator')") or die("<b>".NOTUPDATED . $prefix_table . "admin_groups</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "admin_groups " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (1, '', 1, 'languages', 'system', 1, 1, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (2, '', 1, 'currencies', 'system', 1, 2, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (3, '', 1, 'information', 'system', 1, 3, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (4, 'sidebar', 1, 'categories', 'categories', 1, 4, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (5, 'sidebar', 1, 'manufacturers', 'manufacturers', 1, 5, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (6, 'sidebar', 1, 'whats_new', '', 1, 6, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (7, 'sidebar', 1, 'add_a_quickie', 'system', 1, 7, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (8, 'sidebar', 1, 'products_history', '', 1, 8, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (9, 'sidebar', 1, 'best_sellers', '', 1, 10, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (10, 'sidebar', 1, 'specials', '', 1, 11, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (11, 'sidebar', 1, 'reviews', '', 1, 12, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'sidebar\'),')") or die("<b>".NOTUPDATED . $prefix_table . "block</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "block " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (1, 1, 'Sprachen')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (1, 2, 'Languages')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (2, 1, 'Währungen')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (2, 2, 'Currencies')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (3, 1, 'Informationen')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (3, 2, 'Information')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (4, 1, 'Kategorien')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (4, 2, 'Categories')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (5, 1, 'Hersteller')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (5, 2, 'Manufacturers')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (6, 1, 'Neue Produkte')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (6, 2, 'What\'s New?')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (8, 1, 'Besuchte Produkte')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (8, 2, 'Products History')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (9, 1, 'Bestseller')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (9, 2, 'Bestsellers')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (10, 1, 'Angebote')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (10, 2, 'Specials')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (11, 1, 'Bewertungen')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (11, 2, 'Reviews')") or die("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "block_info " . UPDATED .'</font>';

// Languages
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 1)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 4)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 5)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 6)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 7)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 1)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 4)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 5)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 6)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 7)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 1)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 4)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 5)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 6)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 7)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 4)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 5)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 6)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 7)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 4)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 5)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 6)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 7)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 4)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 7)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (7, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (7, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (7, 7)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (8, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (8, 5)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (8, 6)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (9, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (9, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (10, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (10, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 2)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 3)") or die("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");



echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "block_to_page_type " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (1, 'Afghanistan', 'AF', 'AFG', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (2, 'Albania', 'AL', 'ALB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (3, 'Algeria', 'DZ', 'DZA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (4, 'American Samoa', 'AS', 'ASM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (5, 'Andorra', 'AD', 'AND', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (6, 'Angola', 'AO', 'AGO', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (7, 'Anguilla', 'AI', 'AIA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (8, 'Antarctica', 'AQ', 'ATA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (9, 'Antigua and Barbuda', 'AG', 'ATG', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (10, 'Argentina', 'AR', 'ARG', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (11, 'Armenia', 'AM', 'ARM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (12, 'Aruba', 'AW', 'ABW', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (13, 'Australia', 'AU', 'AUS', 6)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (14, 'Austria', 'AT', 'AUT', 5)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (15, 'Azerbaijan', 'AZ', 'AZE', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (16, 'Bahamas', 'BS', 'BHS', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (17, 'Bahrain', 'BH', 'BHR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (18, 'Bangladesh', 'BD', 'BGD', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (19, 'Barbados', 'BB', 'BRB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (20, 'Belarus', 'BY', 'BLR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (21, 'Belgium', 'BE', 'BEL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (22, 'Belize', 'BZ', 'BLZ', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (23, 'Benin', 'BJ', 'BEN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (24, 'Bermuda', 'BM', 'BMU',  1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (25, 'Bhutan', 'BT', 'BTN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (26, 'Bolivia', 'BO', 'BOL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (27, 'Bosnia and Herzegowina', 'BA', 'BIH', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (28, 'Botswana', 'BW', 'BWA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (29, 'Bouvet Island', 'BV', 'BVT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (30, 'Brazil', 'BR', 'BRA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (31, 'British Indian Ocean Territory', 'IO', 'IOT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (32, 'Brunei Darussalam', 'BN', 'BRN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (33, 'Bulgaria', 'BG', 'BGR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (34, 'Burkina Faso', 'BF', 'BFA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (35, 'Burundi', 'BI', 'BDI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (36, 'Cambodia', 'KH', 'KHM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (37, 'Cameroon', 'CM', 'CMR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (38, 'Canada', 'CA', 'CAN', 2)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (39, 'Cape Verde', 'CV', 'CPV', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (40, 'Cayman Islands', 'KY', 'CYM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (41, 'Central African Republic', 'CF', 'CAF', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (42, 'Chad', 'TD', 'TCD', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (43, 'Chile', 'CL', 'CHL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (44, 'China', 'CN', 'CHN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (45, 'Christmas Island', 'CX', 'CXR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (46, 'Cocos (Keeling) Islands', 'CC', 'CCK', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (47, 'Colombia', 'CO', 'COL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (48, 'Comoros', 'KM', 'COM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (49, 'Congo', 'CG', 'COG', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (50, 'Cook Islands', 'CK', 'COK', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (51, 'Costa Rica', 'CR', 'CRI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (52, 'Cote D\'Ivoire', 'CI', 'CIV', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (53, 'Croatia', 'HR', 'HRV', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (54, 'Cuba', 'CU', 'CUB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (55, 'Cyprus', 'CY', 'CYP', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (56, 'Czech Republic', 'CZ', 'CZE', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (57, 'Denmark', 'DK', 'DNK', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (58, 'Djibouti', 'DJ', 'DJI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (59, 'Dominica', 'DM', 'DMA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (60, 'Dominican Republic', 'DO', 'DOM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (61, 'East Timor', 'TP', 'TMP', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (62, 'Ecuador', 'EC', 'ECU',  1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (63, 'Egypt', 'EG', 'EGY', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (64, 'El Salvador', 'SV', 'SLV', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (65, 'Equatorial Guinea', 'GQ', 'GNQ', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (66, 'Eritrea', 'ER', 'ERI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (67, 'Estonia', 'EE', 'EST', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (68, 'Ethiopia', 'ET', 'ETH', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (70, 'Faroe Islands', 'FO', 'FRO', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (71, 'Fiji', 'FJ', 'FJI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (72, 'Finland', 'FI', 'FIN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (73, 'France', 'FR', 'FRA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (74, 'France, Metropolitan', 'FX', 'FXX', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (75, 'French Guiana', 'GF', 'GUF', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (76, 'French Polynesia', 'PF', 'PYF', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (77, 'French Southern Territories', 'TF', 'ATF', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (78, 'Gabon', 'GA', 'GAB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (79, 'Gambia', 'GM', 'GMB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (80, 'Georgia', 'GE', 'GEO', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (81, 'Germany', 'DE', 'DEU', 5)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (82, 'Ghana', 'GH', 'GHA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (83, 'Gibraltar', 'GI', 'GIB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (84, 'Greece', 'GR', 'GRC', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (85, 'Greenland', 'GL', 'GRL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (86, 'Grenada', 'GD', 'GRD', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (87, 'Guadeloupe', 'GP', 'GLP', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (88, 'Guam', 'GU', 'GUM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (89, 'Guatemala', 'GT', 'GTM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (90, 'Guinea', 'GN', 'GIN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (91, 'Guinea-bissau', 'GW', 'GNB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (92, 'Guyana', 'GY', 'GUY', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (93, 'Haiti', 'HT', 'HTI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (94, 'Heard and Mc Donald Islands', 'HM', 'HMD', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (95, 'Honduras', 'HN', 'HND', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (96, 'Hong Kong', 'HK', 'HKG', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (97, 'Hungary', 'HU', 'HUN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (98, 'Iceland', 'IS', 'ISL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (99, 'India', 'IN', 'IND', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (100, 'Indonesia', 'ID', 'IDN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (101, 'Iran (Islamic Republic of)', 'IR', 'IRN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (102, 'Iraq', 'IQ', 'IRQ', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (103, 'Ireland', 'IE', 'IRL', 9)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (104, 'Israel', 'IL', 'ISR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (105, 'Italy', 'IT', 'ITA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (106, 'Jamaica', 'JM', 'JAM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (107, 'Japan', 'JP', 'JPN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (108, 'Jordan', 'JO', 'JOR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (109, 'Kazakhstan', 'KZ', 'KAZ', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (110, 'Kenya', 'KE', 'KEN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (111, 'Kiribati', 'KI', 'KIR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (112, 'Korea, Democratic People\'s Republic of', 'KP', 'PRK', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (113, 'Korea, Republic of', 'KR', 'KOR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (114, 'Kuwait', 'KW', 'KWT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (115, 'Kyrgyzstan', 'KG', 'KGZ', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (116, 'Lao People\'s Democratic Republic', 'LA', 'LAO', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (117, 'Latvia', 'LV', 'LVA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (118, 'Lebanon', 'LB', 'LBN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (119, 'Lesotho', 'LS', 'LSO', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (120, 'Liberia', 'LR', 'LBR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (122, 'Liechtenstein', 'LI', 'LIE', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (123, 'Lithuania', 'LT', 'LTU', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (124, 'Luxembourg', 'LU', 'LUX', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (125, 'Macau', 'MO', 'MAC', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (126, 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MKD', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (127, 'Madagascar', 'MG', 'MDG', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (128, 'Malawi', 'MW', 'MWI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (129, 'Malaysia', 'MY', 'MYS', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (130, 'Maldives', 'MV', 'MDV', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (131, 'Mali', 'ML', 'MLI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (132, 'Malta', 'MT', 'MLT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (133, 'Marshall Islands', 'MH', 'MHL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (134, 'Martinique', 'MQ', 'MTQ', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (135, 'Mauritania', 'MR', 'MRT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (136, 'Mauritius', 'MU', 'MUS', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (137, 'Mayotte', 'YT', 'MYT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (138, 'Mexico', 'MX', 'MEX', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (139, 'Micronesia, Federated States of', 'FM', 'FSM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (140, 'Moldova, Republic of', 'MD', 'MDA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (141, 'Monaco', 'MC', 'MCO', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (142, 'Mongolia', 'MN', 'MNG', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (143, 'Montserrat', 'MS', 'MSR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (144, 'Morocco', 'MA', 'MAR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (145, 'Mozambique', 'MZ', 'MOZ', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (146, 'Myanmar', 'MM', 'MMR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (147, 'Namibia', 'NA', 'NAM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (148, 'Nauru', 'NR', 'NRU', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (149, 'Nepal', 'NP', 'NPL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (150, 'Netherlands', 'NL', 'NLD', 10)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (151, 'Netherlands Antilles', 'AN', 'ANT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (152, 'New Caledonia', 'NC', 'NCL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (153, 'New Zealand', 'NZ', 'NZL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (154, 'Nicaragua', 'NI', 'NIC', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (155, 'Niger', 'NE', 'NER', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (156, 'Nigeria', 'NG', 'NGA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (157, 'Niue', 'NU', 'NIU', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (158, 'Norfolk Island', 'NF', 'NFK', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (159, 'Northern Mariana Islands', 'MP', 'MNP', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (160, 'Norway', 'NO', 'NOR',  1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (161, 'Oman', 'OM', 'OMN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (162, 'Pakistan', 'PK', 'PAK', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (163, 'Palau', 'PW', 'PLW', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (164, 'Panama', 'PA', 'PAN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (165, 'Papua New Guinea', 'PG', 'PNG', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (166, 'Paraguay', 'PY', 'PRY', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (167, 'Peru', 'PE', 'PER', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (168, 'Philippines', 'PH', 'PHL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (169, 'Pitcairn', 'PN', 'PCN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (170, 'Poland', 'PL', 'POL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (171, 'Portugal', 'PT', 'PRT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (172, 'Puerto Rico', 'PR', 'PRI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (173, 'Qatar', 'QA', 'QAT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (174, 'Reunion', 'RE', 'REU', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (175, 'Romania', 'RO', 'ROM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (176, 'Russian Federation', 'RU', 'RUS', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (177, 'Rwanda', 'RW', 'RWA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (178, 'Saint Kitts and Nevis', 'KN', 'KNA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (179, 'Saint Lucia', 'LC', 'LCA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (181, 'Samoa', 'WS', 'WSM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (182, 'San Marino', 'SM', 'SMR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (183, 'Sao Tome and Principe', 'ST', 'STP', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (184, 'Saudi Arabia', 'SA', 'SAU', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (185, 'Senegal', 'SN', 'SEN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (186, 'Seychelles', 'SC', 'SYC', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (187, 'Sierra Leone', 'SL', 'SLE', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (188, 'Singapore', 'SG', 'SGP', 7)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (190, 'Slovenia', 'SI', 'SVN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (191, 'Solomon Islands', 'SB', 'SLB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (192, 'Somalia', 'SO', 'SOM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (193, 'South Africa', 'ZA', 'ZAF', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (195, 'Spain', 'ES', 'ESP', 3)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (196, 'Sri Lanka', 'LK', 'LKA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (197, 'St. Helena', 'SH', 'SHN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (198, 'St. Pierre and Miquelon', 'PM', 'SPM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (199, 'Sudan', 'SD', 'SDN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (200, 'Suriname', 'SR', 'SUR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (202, 'Swaziland', 'SZ', 'SWZ', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (203, 'Sweden', 'SE', 'SWE', 5)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (204, 'Switzerland', 'CH', 'CHE', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (205, 'Syrian Arab Republic', 'SY', 'SYR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (206, 'Taiwan', 'TW', 'TWN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (207, 'Tajikistan', 'TJ', 'TJK', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (208, 'Tanzania, United Republic of', 'TZ', 'TZA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (209, 'Thailand', 'TH', 'THA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (210, 'Togo', 'TG', 'TGO', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (211, 'Tokelau', 'TK', 'TKL', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (212, 'Tonga', 'TO', 'TON', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (213, 'Trinidad and Tobago', 'TT', 'TTO', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (214, 'Tunisia', 'TN', 'TUN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (215, 'Turkey', 'TR', 'TUR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (216, 'Turkmenistan', 'TM', 'TKM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (217, 'Turks and Caicos Islands', 'TC', 'TCA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (218, 'Tuvalu', 'TV', 'TUV', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (219, 'Uganda', 'UG', 'UGA', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (220, 'Ukraine', 'UA', 'UKR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (221, 'United Arab Emirates', 'AE', 'ARE', 6)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (222, 'United Kingdom', 'GB', 'GBR', 7)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (223, 'United States', 'US', 'USA', 2)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (224, 'United States Minor Outlying Islands', 'UM', 'UMI', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (225, 'Uruguay', 'UY', 'URY', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (226, 'Uzbekistan', 'UZ', 'UZB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (227, 'Vanuatu', 'VU', 'VUT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (228, 'Vatican City State (Holy See)', 'VA', 'VAT', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (229, 'Venezuela', 'VE', 'VEN', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (230, 'Viet Nam', 'VN', 'VNM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (231, 'Virgin Islands (British)', 'VG', 'VGB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (232, 'Virgin Islands (U.S.)', 'VI', 'VIR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (233, 'Wallis and Futuna Islands', 'WF', 'WLF', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (234, 'Western Sahara', 'EH', 'ESH', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (235, 'Yemen', 'YE', 'YEM', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (236, 'Yugoslavia', 'YU', 'YUG', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (237, 'Zaire', 'ZR', 'ZAR', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (238, 'Zambia', 'ZM', 'ZMB', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES (239, 'Zimbabwe', 'ZW', 'ZWE', 1)") or die("<b>".NOTUPDATED . $prefix_table . "countries</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "countries " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "currencies (currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value, last_updated) VALUES (1, 'Euro', 'EUR', '', '€', ',', '.', '2', '1.00000000', " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "currencies</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "currencies (currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value, last_updated) VALUES (2, 'US Dollar', 'USD', '$', '', '.', ', ', '2', '0.98000002', " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "currencies</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "currencies (currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value, last_updated) VALUES (3, 'Schweizer Franken', 'CHF', 'CHF', '', '.', ', ', '2', '1.2044', " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "currencies</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "currencies " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 1, 'Admin', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 2, 'Admin', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 1, 'gast', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 2, 'guest', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "customers_status " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, sort_order, date_added, last_modified, status) VALUES (1,  '1', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, sort_order, date_added, last_modified, status) VALUES (2,  '2', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, sort_order, date_added, last_modified, status) VALUES (3,  '3', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, sort_order, date_added, last_modified, status) VALUES (4,  '4', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, sort_order, date_added, last_modified, status) VALUES (5,  '5', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, sort_order, date_added, last_modified, status) VALUES (6,  '6', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, sort_order, date_added, last_modified, status) VALUES (7,  '7', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die("<b>".NOTUPDATED . $prefix_table . "information</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "information " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (1, 1, 'Impressum', 'Impressum', 'Fügen Sie hier Ihre Informationen zum Impressum ein.' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (1, 2, 'Imprint', 'Imprint', 'Put here your information about your company' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (2, 1, 'AGB', 'Unsere AGB', 'Fügen Sie hier Ihre AGB ein' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (2, 2, 'Conditions of Use', 'Conditions of Use', 'Put here your Conditions of Use information' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (3, 1, 'Widerrufsbelehrung ', 'Widerrufsbelehrung', 'Fügen Sie hier Ihre Widerrufsbelehrung ein' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (3, 2, 'Power of Revocation', 'Power of Revocation', 'Put here your Power of Revocation information' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (4, 1, 'Datenschutz', 'Datenschutzbelehrung', 'Fügen Sie hier Ihre Informationen zum Datenschutz ein' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (4, 2, 'Data Security Statement', 'Privacy Notice', 'Put here your Privacy Notice information' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (5, 1, 'Liefer- und Versandbedingungen', 'Liefer- und Versandbedingungen', 'Fügen Sie hier Ihre Liefer- und Versandbedingungen ein' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (5, 2, 'Shipping & Returns', 'Shipping & Returns', 'Put here your Shipping & Returns information' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (6, 1, 'Zahlungsarten', 'Zahlungsarten', 'Fügen Sie hier Ihre Informationen über Ihre Zahlungsarten ein' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (6, 2, 'Payment Methods', 'Payment Methods', 'Put here your Payment Methods information' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (7, 1, 'Kundenbewertungen', 'Informationen zur Echtheit von Kundenbewertungen', 'Fügen Sie hier Ihre Informationen zur Echtheit von Kundenbewertungen ein.' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_name, information_heading_title, information_description) VALUES (7, 2, 'Customer reviews', 'Information on the authenticity of customer reviews', 'Insert your information about the authenticity of customer reviews here.' )") or die("<b>".NOTUPDATED . $prefix_table . "information_description</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "information_description " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, iso_3166_1, status, sort_order) VALUES (1, 'Deutsch', 'deu', 'de', 'de', 1, 1)") or die("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, iso_3166_1, status, sort_order) VALUES (2, 'English', 'eng', 'en', 'gb', 1, 2)") or die("<b>".NOTUPDATED . $prefix_table . "languages</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "languages " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "manual_info (man_info_id, man_name, status, manual_date_added, defined) VALUES ('1', 'Manual Entry', 0, " . $db->DBTimeStamp($today) . ",  'admin_log')") or die("<b>".NOTUPDATED . $prefix_table . "manual_info</b>");
echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "manual_info " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (1, 2, 'Pending')") or die("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (1, 1, 'Offen')") or die("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (2, 2, 'Processing')") or die("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (2, 1, 'In Bearbeitung')") or die("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (3, 2, 'Delivered')") or die("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (3, 1, 'Versendet')") or die("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "orders_status " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (1, 2, 'Frontpage')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (1, 1, 'Startseite')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (2, 2, 'Shop')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (2, 1, 'Shop')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (3, 2, 'Products')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (3, 1, 'Produkte')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

// news

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (5, 2, 'Service')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (5, 1, 'Service')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (6, 2, 'Checkout')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (6, 1, 'Kasse')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (8, 1, 'Kundenkonto')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (8, 2, 'Account')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (9, 1, 'Meinungen: Produkte')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (9, 2, 'Reviews: Products')") or die("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "page_type " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (1, 2, 'Out of Stock')") or die("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (1, 1, 'nicht vorrätig')") or die("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (2, 2, 'Available Soon')") or die("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (2, 1, 'bald verfügbar')") or die("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (3, 2, 'In Stock')") or die("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (3, 1, 'auf Lager')") or die("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (4, 2, 'No longer available / there is a replacement product')") or die("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (4, 1, 'Nicht mehr verfügbar/Es gibt ein Ersatzprodukt')") or die("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "products_status " . UPDATED .'</font>';


// products_options_types
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (1, 2, 'Select')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (1, 1, 'Select')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (2, 2, 'Checkbox')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (2, 1, 'Checkbox')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (3, 2, 'Radio')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (3, 1, 'Radio')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (4, 2, 'Text')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (4, 1, 'Text')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (5, 2, 'Textarea')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (5, 1, 'Textarea')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (6, 2, 'File')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (6, 1, 'File')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "products_options_types " . UPDATED .'</font>';

// products_options
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options (products_options_id,products_options_languages_id,products_options_name,products_options_type,products_options_length,products_options_comment) VALUES ('1','1','Farbe','1','32','')") or die("<b>".NOTUPDATED . $prefix_table . "products_options</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options (products_options_id,products_options_languages_id,products_options_name,products_options_type,products_options_length,products_options_comment) VALUES ('1','2','Color','1','32','')") or die("<b>".NOTUPDATED . $prefix_table . "products_options</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options (products_options_id,products_options_languages_id,products_options_name,products_options_type,products_options_length,products_options_comment) VALUES ('2','1','Größe','1','32','')") or die("<b>".NOTUPDATED . $prefix_table . "products_options</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options (products_options_id,products_options_languages_id,products_options_name,products_options_type,products_options_length,products_options_comment) VALUES ('2','2','Size','1','32','')") or die("<b>".NOTUPDATED . $prefix_table . "products_options</b>");
echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "products_options " . UPDATED .'</font>';

// products_options_values
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('1','1','dunkel')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('1','2','dark')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('2','1','rot')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('2','2','red')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('3','1','weiß')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('3','2','white')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('4','1','beige')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('4','2','beige')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('5','1','SM')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('5','2','SM')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('6','1','M')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('6','2','M')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('7','1','XS')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('7','2','XS')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('8','1','XS')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('8','2','XS')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('9','1','XL')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('9','2','XL')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('10','1','XXL')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_values (products_options_values_id,products_options_values_languages_id,products_options_values_name) VALUES ('10','2','XXL')") or die("<b>".NOTUPDATED . $prefix_table . "products_options_values</b>");
echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "products_options_values " . UPDATED .'</font>';




$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (1, 1, 'kg', '1 Kilogramm ')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (1, 2, 'kg', '1 kilogram ')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (2, 1, 'g', '1 Kilogramm ')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (2, 2, 'g', '1 kilogram ')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (3, 1, 'l', '1 Liter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (3, 2, 'l', '1 liter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (4, 1, 'ml', '1 Liter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (4, 2, 'ml', '1 liter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (5, 1, 'cm', '1 Meter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (5, 2, 'cm', '1 meter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (6, 1, 'mm', '1 Meter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (6, 2, 'mm', '1 meter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (7, 1, 'm', '1 Meter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (7, 2, 'm', '1 meter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (8, 1, 'm²', '1 Quadratmeter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_units (products_units_id, languages_id, products_unit_name, unit_of_measure) VALUES (8, 2, 'm²', '1 square meter')") or die("<b>".NOTUPDATED . $prefix_table . "products_units</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "products_units " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "setting (setting_id, setting_languages_id, setting_name) VALUES (0, 2, 'Trash')") or die("<b>".NOTUPDATED . $prefix_table . "setting</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "setting (setting_id, setting_languages_id, setting_name) VALUES (0, 1, 'Papierkorb')") or die("<b>".NOTUPDATED . $prefix_table . "setting</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "setting (setting_id, setting_languages_id, setting_name) VALUES (1, 2, 'Draft')") or die("<b>".NOTUPDATED . $prefix_table . "setting</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "setting (setting_id, setting_languages_id, setting_name) VALUES (1, 1, 'Entwurf')") or die("<b>".NOTUPDATED . $prefix_table . "setting</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "setting (setting_id, setting_languages_id, setting_name) VALUES (2, 2, 'Published')") or die("<b>".NOTUPDATED . $prefix_table . "setting</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "setting (setting_id, setting_languages_id, setting_name) VALUES (2, 1, 'Veröffentlicht')") or die("<b>".NOTUPDATED . $prefix_table . "setting</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "setting " . UPDATED .'</font>';


// Australian states
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (1, 13, 'VIC', 'Victoria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (2, 13, 'NSW', 'New South Wales')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (3, 13, 'QLD', 'Queensland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (4, 13, 'SA', 'South Australia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (5, 13, 'NT', 'Northern Territory')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (6, 13, 'TAS', 'Tasmania')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (7, 13, 'ACT', 'Australian Capital Territory')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (8, 13, 'WA', 'Western Australia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (66, 38, 'AB', 'Alberta')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (67, 38, 'BC', 'British Columbia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (68, 38, 'MB', 'Manitoba')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (69, 38, 'NF', 'Newfoundland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (70, 38, 'NB', 'New Brunswick')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (71, 38, 'NS', 'Nova Scotia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (72, 38, 'NT', 'Northwest Territories')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (73, 38, 'NU', 'Nunavut')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (74, 38, 'ON', 'Ontario')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (75, 38, 'PE', 'Prince Edward Island')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (76, 38, 'QC', 'Quebec')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (77, 38, 'SK', 'Saskatchewan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (78, 38, 'YT', 'Yukon Territory')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (79, 81, 'NDS', 'Niedersachsen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (80, 81, 'BAW', 'Baden-Württemberg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (81, 81, 'BAY', 'Bayern')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (82, 81, 'BER', 'Berlin')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (83, 81, 'BRG', 'Brandenburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (84, 81, 'BRE', 'Bremen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (85, 81, 'HAM', 'Hamburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (86, 81, 'HES', 'Hessen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (87, 81, 'MEC', 'Mecklenburg-Vorpommern')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (88, 81, 'NRW', 'Nordrhein-Westfalen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (89, 81, 'RHE', 'Rheinland-Pfalz')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (90, 81, 'SAR', 'Saarland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (91, 81, 'SAS', 'Sachsen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (92, 81, 'SAC', 'Sachsen-Anhalt')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (93, 81, 'SCN', 'Schleswig-Holstein')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (94, 81, 'THE', 'Thüringen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (95, 14, 'WI', 'Wien')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (96, 14, 'NO', 'Niederösterreich')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (97, 14, 'OO', 'Oberösterreich')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (98, 14, 'SB', 'Salzburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (99, 14, 'KN', 'Kärnten')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (100, 14, 'ST', 'Steiermark')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (101, 14, 'TI', 'Tirol')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (102, 14, 'BL', 'Burgenland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (103, 14, 'VB', 'Voralberg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (104, 204, 'AG', 'Aargau')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (105, 204, 'AI', 'Appenzell Innerrhoden')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (106, 204, 'AR', 'Appenzell Ausserrhoden')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (107, 204, 'BE', 'Bern')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (108, 204, 'BL', 'Basel-Landschaft')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (109, 204, 'BS', 'Basel-Stadt')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (110, 204, 'FR', 'Freiburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (111, 204, 'GE', 'Genf')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (112, 204, 'GL', 'Glarus')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (113, 204, 'GR', 'Graubünden')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (114, 204, 'JU', 'Jura')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (115, 204, 'LU', 'Luzern')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (116, 204, 'NE', 'Neuenburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (117, 204, 'NW', 'Nidwalden')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (118, 204, 'OW', 'Obwalden')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (119, 204, 'SG', 'St. Gallen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (120, 204, 'SH', 'Schaffhausen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (121, 204, 'SO', 'Solothurn')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (122, 204, 'SZ', 'Schwyz')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (123, 204, 'TG', 'Thurgau')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (124, 204, 'TI', 'Tessin')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (125, 204, 'UR', 'Uri')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (126, 204, 'VD', 'Waadt')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (127, 204, 'VS', 'Wallis')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (128, 204, 'ZG', 'Zug')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (129, 204, 'ZH', 'Zürich')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (130, 195, 'A Coruña', 'A Coruña')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (131, 195, 'Alava', 'Alava')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (132, 195, 'Albacete', 'Albacete')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (133, 195, 'Alicante', 'Alicante')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (134, 195, 'Almeria', 'Almeria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (135, 195, 'Asturias', 'Asturias')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (136, 195, 'Avila', 'Avila')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (137, 195, 'Badajoz', 'Badajoz')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (138, 195, 'Baleares', 'Baleares')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (139, 195, 'Barcelona', 'Barcelona')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (140, 195, 'Burgos', 'Burgos')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (141, 195, 'Caceres', 'Caceres')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (142, 195, 'Cadiz', 'Cadiz')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (143, 195, 'Cantabria', 'Cantabria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (144, 195, 'Castellon', 'Castellon')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (145, 195, 'Ceuta', 'Ceuta')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (146, 195, 'Ciudad Real', 'Ciudad Real')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (147, 195, 'Cordoba', 'Cordoba')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (148, 195, 'Cuenca', 'Cuenca')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (149, 195, 'Girona', 'Girona')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (150, 195, 'Granada', 'Granada')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (151, 195, 'Guadalajara', 'Guadalajara')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (152, 195, 'Guipuzcoa', 'Guipuzcoa')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (153, 195, 'Huelva', 'Huelva')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (154, 195, 'Huesca', 'Huesca')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (155, 195, 'Jaen', 'Jaen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (156, 195, 'La Rioja', 'La Rioja')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (157, 195, 'Las Palmas', 'Las Palmas')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (158, 195, 'Leon', 'Leon')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (159, 195, 'Lleida', 'Lleida')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (160, 195, 'Lugo', 'Lugo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (161, 195, 'Madrid', 'Madrid')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (162, 195, 'Malaga', 'Malaga')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (163, 195, 'Melilla', 'Melilla')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (164, 195, 'Murcia', 'Murcia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (165, 195, 'Navarra', 'Navarra')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (166, 195, 'Ourense', 'Ourense')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (167, 195, 'Palencia', 'Palencia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (168, 195, 'Pontevedra', 'Pontevedra')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (169, 195, 'Salamanca', 'Salamanca')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (170, 195, 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (171, 195, 'Segovia', 'Segovia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (172, 195, 'Sevilla', 'Sevilla')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (173, 195, 'Soria', 'Soria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (174, 195, 'Tarragona', 'Tarragona')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (175, 195, 'Teruel', 'Teruel')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (176, 195, 'Toledo', 'Toledo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (177, 195, 'Valencia', 'Valencia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (178, 195, 'Valladolid', 'Valladolid')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (179, 195, 'Vizcaya', 'Vizcaya')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (180, 195, 'Zamora', 'Zamora')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (181, 195, 'Zaragoza', 'Zaragoza')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (182, 103, '01', 'Carlow')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (183, 103, '02', 'Cavan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (184, 103, '03', 'Clare')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (185, 103, '04', 'Cork')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (186, 103, '05', 'Donegal')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (187, 103, '06', 'Dublin')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (188, 103, '07', 'Galway')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (189, 103, '08', 'Kerry')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (190, 103, '09', 'Kildare')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (191, 103, '10', 'Kilkenny')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (192, 103, '11', 'Laois')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (193, 103, '12', 'Leitrim')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (194, 103, '13', 'Limerick')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (195, 103, '14', 'Longford')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (196, 103, '15', 'Louth')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (197, 103, '16', 'Mayo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (198, 103, '17', 'Meath')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (199, 103, '18', 'Monaghan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (200, 103, '19', 'Offaly')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (201, 103, '20', 'Roscommon')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (202, 103, '21', 'Sligo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (203, 103, '22', 'Tipperary')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (204, 103, '23', 'Waterford')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (205, 103, '24', 'Westmeath')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (206, 103, '25', 'Wexford')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (207, 103, '26', 'Wicklow')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (208, 105, 'AG', 'Agrigento')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (209, 105, 'AL', 'Alessandria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (210, 105, 'AN', 'Ancona')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (211, 105, 'AO', 'Aosta')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (212, 105, 'AR', 'Arezzo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (213, 105, 'AP', 'Ascoli Piceno')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (214, 105, 'AT', 'Asti')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (215, 105, 'AV', 'Avellino')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (216, 105, 'BA', 'Bari')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (217, 105, 'BL', 'Belluno')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (218, 105, 'BN', 'Benevento')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (219, 105, 'BG', 'Bergamo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (220, 105, 'BI', 'Biella')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (221, 105, 'BO', 'Bologna')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (222, 105, 'BZ', 'Bolzano')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (223, 105, 'BS', 'Brescia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (224, 105, 'BR', 'Brindisi')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (225, 105, 'CA', 'Cagliari')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (226, 105, 'CL', 'Caltanissetta')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (227, 105, 'CB', 'Campobasso')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (228, 105, 'CE', 'Caserta')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (229, 105, 'CT', 'Catania')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (230, 105, 'CZ', 'Catanzaro')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (231, 105, 'CH', 'Chieti')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (232, 105, 'CO', 'Como')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (233, 105, 'CS', 'Cosenza')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (234, 105, 'CR', 'Cremona')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (235, 105, 'KR', 'Crotone')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (236, 105, 'CN', 'Cuneo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (237, 105, 'EN', 'Enna')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (238, 105, 'FE', 'Ferrara')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (239, 105, 'FI', 'Firenze')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (240, 105, 'FG', 'Foggia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (241, 105, 'FO', 'Forlì-Cesena')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (242, 105, 'FR', 'Frosinone')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (243, 105, 'GE', 'Genova')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (244, 105, 'GO', 'Gorizia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (245, 105, 'GR', 'Grosseto')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (246, 105, 'IM', 'Imperia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (247, 105, 'IS', 'Isernia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (248, 105, 'AQ', 'Aquila')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (249, 105, 'SP', 'La Spezia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (250, 105, 'LT', 'Latina')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (251, 105, 'LE', 'Lecce')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (252, 105, 'LC', 'Lecco')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (253, 105, 'LI', 'Livorno')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (254, 105, 'LO', 'Lodi')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (255, 105, 'LU', 'Lucca')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (256, 105, 'MC', 'Macerata')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (257, 105, 'MN', 'Mantova')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (258, 105, 'MS', 'Massa-Carrara')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (259, 105, 'MT', 'Matera')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (260, 105, 'ME', 'Messina')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (261, 105, 'MI', 'Milano')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (262, 105, 'MO', 'Modena')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (263, 105, 'NA', 'Napoli')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (264, 105, 'NO', 'Novara')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (265, 105, 'NU', 'Nuoro')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (266, 105, 'OR', 'Oristano')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (267, 105, 'PD', 'Padova')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (268, 105, 'PA', 'Palermo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (269, 105, 'PR', 'Parma')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (270, 105, 'PG', 'Perugia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (271, 105, 'PV', 'Pavia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (272, 105, 'PS', 'Pesaro e Urbino')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (273, 105, 'PE', 'Pescara')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (274, 105, 'PC', 'Piacenza')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (275, 105, 'PI', 'Pisa')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (276, 105, 'PT', 'Pistoia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (277, 105, 'PN', 'Pordenone')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (278, 105, 'PZ', 'Potenza')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (279, 105, 'PO', 'Prato')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (280, 105, 'RG', 'Ragusa')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (281, 105, 'RA', 'Ravenna')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (282, 105, 'RC', 'Reggio di Calabria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (283, 105, 'RE', 'Reggio Emilia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (284, 105, 'RI', 'Rieti')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (285, 105, 'RN', 'Rimini')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (286, 105, 'RM', 'Roma')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (287, 105, 'RO', 'Rovigo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (288, 105, 'SA', 'Salerno')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (289, 105, 'SS', 'Sassari')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (290, 105, 'SV', 'Savona')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (291, 105, 'SI', 'Siena')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (292, 105, 'SR', 'Siracusa')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (293, 105, 'SO', 'Sondrio')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (294, 105, 'TA', 'Taranto')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (295, 105, 'TE', 'Teramo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (296, 105, 'TR', 'Terni')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (297, 105, 'TO', 'Torino')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (298, 105, 'TP', 'Trapani')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (299, 105, 'TN', 'Trento')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (300, 105, 'TV', 'Treviso')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (301, 105, 'TS', 'Trieste')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (302, 105, 'UD', 'Udine')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (303, 105, 'VA', 'Varese')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (304, 105, 'VE', 'Venezia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (305, 105, 'VB', 'Verbania')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (306, 105, 'VC', 'Vercelli')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (307, 105, 'VR', 'Verona')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (308, 105, 'VV', 'Vibo Valentia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (309, 105, 'VI', 'Vicenza')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (310, 105, 'VT', 'Viterbo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (311, 150, 'Drenthe', 'Drenthe')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (312, 150, 'Flevoland', 'Flevoland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (313, 150, 'Friesland', 'Friesland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (314, 150, 'Gelderland', 'Gelderland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (315, 150, 'Groningen', 'Groningen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (316, 150, 'Limburg', 'Limburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (317, 150, 'Noord-Brabant', 'Noord-Brabant')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (318, 150, 'Noord-Holland', 'Noord-Holland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (319, 150, 'Overijssel', 'Overijssel')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (320, 150, 'Utrecht', 'Utrecht')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (321, 150, 'Zeeland', 'Zeeland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (322, 150, 'Zuid_Holland', 'Zuid_Holland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (323, 222, 'ALD', 'Alderney')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (324, 222, 'ATM', 'County Antrim')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (325, 222, 'ARM', 'County Armagh')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (326, 222, 'AVN', 'Avon')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (327, 222, 'BFD', 'Bedfordshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (328, 222, 'BRK', 'Berkshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (329, 222, 'BDS', 'Borders')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (330, 222, 'BUX', 'Buckinghamshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (331, 222, 'CBE', 'Cambridgeshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (332, 222, 'CTR', 'Central')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (333, 222, 'CHS', 'Cheshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (334, 222, 'CVE', 'Cleveland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (335, 222, 'CLD', 'Clwyd')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (336, 222, 'CNL', 'Cornwall')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (337, 222, 'CBA', 'Cumbria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (338, 222, 'DYS', 'Derbyshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (339, 222, 'DVN', 'Devon')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (340, 222, 'DOR', 'Dorse')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (341, 222, 'DWN', 'County Down')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (342, 222, 'DGL', 'Dumfries and Galloway')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (343, 222, 'DHM', 'County Durham')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (344, 222, 'DFD', 'Dyfed')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (345, 222, 'ESX', 'Essex')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (346, 222, 'FMH', 'County Fermanagh')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (347, 222, 'FFE', 'Fife')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (348, 222, 'GNM', 'Mid Glamorgan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (349, 222, 'GNS', 'South Glamorgan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (350, 222, 'GNW', 'West Glamorgan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (351, 222, 'GLR', 'Gloucester')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (352, 222, 'GRN', 'Grampian')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (353, 222, 'GUR', 'Guernsey')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (354, 222, 'GWT', 'Gwent')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (355, 222, 'GDD', 'Gwynedd')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (356, 222, 'HPH', 'Hampshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (357, 222, 'HWR', 'Hereford and Worcester')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (358, 222, 'HFD', 'Hertfordshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (359, 222, 'HLD', 'Highlands')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (360, 222, 'HBS', 'Humberside')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (361, 222, 'IOM', 'Isle of Man')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (362, 222, 'IOW', 'Isle of Wight')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (363, 222, 'JER', 'Jersey')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (364, 222, 'KNT', 'Kent')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (365, 222, 'LNH', 'Lancashire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (366, 222, 'LEC', 'Leicestershire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (367, 222, 'LCN', 'Lincolnshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (368, 222, 'LDN', 'Greater London')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (369, 222, 'LDR', 'County Londonderry')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (370, 222, 'LTH', 'Lothian')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (371, 222, 'MCH', 'Greater Manchester')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (372, 222, 'MSY', 'Merseyside')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (373, 222, 'NOR', 'Norfolk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (374, 222, 'NHM', 'Northamptonshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (375, 222, 'NLD', 'Northumberland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (376, 222, 'NOT', 'Nottinghamshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (377, 222, 'ORK', 'Orkney')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (378, 222, 'OFE', 'Oxfordshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (379, 222, 'PWS', 'Powys')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (380, 222, 'SPE', 'Shropshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (381, 222, 'SRK', 'Sark')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (382, 222, 'SLD', 'Shetland')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (383, 222, 'SOM', 'Somerset')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (384, 222, 'SFD', 'Staffordshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (385, 222, 'SCD', 'Strathclyde')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (386, 222, 'SFK', 'Suffolk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (387, 222, 'SRY', 'Surrey')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (388, 222, 'SXE', 'East Sussex')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (389, 222, 'SXW', 'West Sussex')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (390, 222, 'TYS', 'Tayside')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (391, 222, 'TWR', 'Tyne and Wear')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (392, 222, 'TYR', 'County Tyrone')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (393, 222, 'WKS', 'Warwickshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (394, 222, 'WIL', 'Western Isles')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (395, 222, 'WMD', 'West Midlands')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (396, 222, 'WLT', 'Wiltshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (397, 222, 'YSN', 'North Yorkshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (398, 222, 'YSS', 'South Yorkshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (399, 222, 'YSW', 'West Yorkshire')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

// Belgium
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('400',21,'AN','Antwerpen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('401',21,'BW','Brabant Wallon')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('402',21,'HA','Hainaut')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('403',21,'LG','Liege')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('404',21,'LM','Limburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('405',21,'LX','Luxembourg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('406',21,'NM','Namur')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('407',21,'OV','Oost-Vlaanderen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('408',21,'VB','Vlaams Brabant')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('409',21,'WV','West-Vlaanderen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

// Greece
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('433',84,'AI','Aitolia kai Akarnania')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('434',84,'AK','Akhaia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('435',84,'AG','Argolis')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('436',84,'AD','Arkadhia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('437',84,'AR','Arta')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('438',84,'AT','Attiki')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('439',84,'AY','Ayion Oros (Mt. Athos)')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('440',84,'DH','Dhodhekanisos')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('441',84,'DR','Drama')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('442',84,'ET','Evritania')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('443',84,'ES','Evros')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('444',84,'EV','Evvoia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('445',84,'FL','Florina')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('446',84,'FO','Fokis')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('447',84,'FT','Fthiotis')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('448',84,'GR','Grevena')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('449',84,'IL','Ilia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('450',84,'IM','Imathia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('451',84,'IO','Ioannina')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('452',84,'IR','Irakleion')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('453',84,'KA','Kardhitsa')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('454',84,'KS','Kastoria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('455',84,'KV','Kavala')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('456',84,'KE','Kefallinia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('457',84,'KR','Kerkyra')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('458',84,'KH','Khalkidhiki')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('459',84,'KN','Khania')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('460',84,'KI','Khios')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('461',84,'KK','Kikladhes')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('462',84,'KL','Kilkis')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('463',84,'KO','Korinthia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('464',84,'KZ','Kozani')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('465',84,'LA','Lakonia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('466',84,'LR','Larisa')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('467',84,'LS','Lasithi')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('468',84,'LE','Lesvos')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('469',84,'LV','Levkas')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('470',84,'MA','Magnisia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('471',84,'ME','Messinia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('472',84,'PE','Pella')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('473',84,'PI','Pieria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('474',84,'PR','Preveza')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('475',84,'RE','Rethimni')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('476',84,'RO','Rodhopi')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('477',84,'SA','Samos')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('478',84,'SE','Serrai')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('479',84,'TH','Thesprotia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('480',84,'TS','Thessaloniki')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('481',84,'TR','Trikala')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('482',84,'VO','Voiotia')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('483',84,'XA','Xanthi')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('484',84,'ZA','Zakinthos')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

// Luxembourg
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('485',124,'DI','Diekirch')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('486',124,'GR','Grevenmacher')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('487',124,'LU','Luxembourg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

// Poland
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('488',170,'DO','Dolnoslaskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('489',170,'KM','Kujawsko-Pomorskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('490',170,'LO','Lodzkie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('491',170,'LE','Lubelskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('492',170,'LU','Lubuskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('493',170,'ML','Malopolskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('494',170,'MZ','Mazowieckie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('495',170,'OP','Opolskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('496',170,'PK','Podkarpackie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('497',170,'PL','Podlaskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('498',170,'PM','Pomorskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('499',170,'SL','Slaskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('500',170,'SW','Swietokrzyskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('501',170,'WM','Warminsko-Mazurskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('502',170,'WI','Wielkopolskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('503',170,'ZA','Zachodniopomorskie')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");

// Portugal
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('504',171,'AC','Acores (Azores)')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('505',171,'AV','Aveiro')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('506',171,'BE','Beja')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('507',171,'BR','Braga')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('508',171,'BA','Braganca')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('509',171,'CB','Castelo Branco')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('510',171,'CO','Coimbra')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('511',171,'EV','Evora')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('512',171,'FA','Faro')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('513',171,'GU','Guarda')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('514',171,'LE','Leiria')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('516',171,'LI','Lisboa')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('517',171,'ME','Madeira')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('518',171,'PO','Portalegre')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('519',171,'PR','Porto')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('520',171,'SA','Santarem')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('521',171,'SE','Setubal')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('522',171,'VC','Viana do Castelo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('523',171,'VR','Vila Real')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('524',171,'VI','Viseu')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");


// Russian Federation
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('525',176,'AB','Abakan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('526',176,'AG','Aginskoye')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('527',176,'AN','Anadyr')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('528',176,'AR','Arkahangelsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('529',176,'AS','Astrakhan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('530',176,'BA','Barnaul')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('531',176,'BE','Belgorod')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('532',176,'BI','Birobidzhan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('533',176,'BL','Blagoveshchensk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('534',176,'BR','Bryansk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('535',176,'CH','Cheboksary')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('536',176,'CL','Chelyabinsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('537',176,'CR','Cherkessk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('538',176,'CI','Chita')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('539',176,'DU','Dudinka')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('540',176,'EL','Elista')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('541',176,'GO','Gomo-Altaysk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('542',176,'GA','Gorno-Altaysk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('543',176,'GR','Groznyy')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('544',176,'IR','Irkutsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('545',176,'IV','Ivanovo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('546',176,'IZ','Izhevsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('547',176,'KA','Kalinigrad')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('548',176,'KL','Kaluga')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('549',176,'KS','Kasnodar')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('550',176,'KZ','Kazan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('551',176,'KE','Kemerovo')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('552',176,'KH','Khabarovsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('553',176,'KM','Khanty-Mansiysk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('554',176,'KO','Kostroma')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('555',176,'KR','Krasnodar')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('556',176,'KN','Krasnoyarsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('557',176,'KU','Kudymkar')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('558',176,'KG','Kurgan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('559',176,'KK','Kursk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('560',176,'KY','Kyzyl')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('561',176,'LI','Lipetsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('562',176,'MA','Magadan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('563',176,'MK','Makhachkala')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('564',176,'MY','Maykop')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('565',176,'MO','Moscow')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('566',176,'MU','Murmansk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('567',176,'NA','Nalchik')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('568',176,'NR','Naryan Mar')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('569',176,'NZ','Nazran')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('570',176,'NI','Nizhniy Novgorod')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('571',176,'NO','Novgorod')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('572',176,'NV','Novosibirsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('573',176,'OM','Omsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('574',176,'OR','Orel')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('575',176,'OE','Orenburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('576',176,'PA','Palana')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('577',176,'PE','Penza')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('578',176,'PR','Perm')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('579',176,'PK','Petropavlovsk-Kamchatskiy')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('580',176,'PT','Petrozavodsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('581',176,'PS','Pskov')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('582',176,'RO','Rostov-na-Donu')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('583',176,'RY','Ryazan')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('584',176,'SL','Salekhard')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('585',176,'SA','Samara')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('586',176,'SR','Saransk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('587',176,'SV','Saratov')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('588',176,'SM','Smolensk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('589',176,'SP','St. Petersburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('590',176,'ST','Stavropol')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('591',176,'SY','Syktyvkar')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('592',176,'TA','Tambov')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('593',176,'TO','Tomsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('594',176,'TU','Tula')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('595',176,'TR','Tura')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('596',176,'TV','Tver')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('597',176,'TY','Tyumen')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('598',176,'UF','Ufa')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('599',176,'UL','Ul\'yanovsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('600',176,'UU','Ulan-Ude')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('601',176,'US','Ust\'-Ordynskiy')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('602',176,'VL','Vladikavkaz')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('603',176,'VA','Vladimir')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('604',176,'VV','Vladivostok')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('605',176,'VG','Volgograd')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('606',176,'VD','Vologda')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('607',176,'VO','Voronezh')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('608',176,'VY','Vyatka')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('609',176,'YA','Yakutsk')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('610',176,'YR','Yaroslavl')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('611',176,'YE','Yekaterinburg')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('612',176,'YO','Yoshkar-Ola')") or die("<b>".NOTUPDATED . $prefix_table . "zones</b>");


// Tax
$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) VALUES (1, 'Standard Steuersatz', 'normaler Steuersatz für Dienstleistungen und alle non-food Artikel', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_class</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) VALUES (2, 'Ermäßigter Steuersatz', 'verminderter Steuersatz für Lebensmittel und Bücher', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_class</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) VALUES (3, 'Steuerfrei', 'Steuerfrei', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_class</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "tax_class " . UPDATED .'</font>';

if (isset($_POST['gst'])) {
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(4, 2, 2, 7.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(5, 2, 1, 19.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(6, 2, 3, 0.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(7, 3, 1, 21.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(8, 3, 2, 12.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(9, 3, 3, 0.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(10, 4, 1, 20.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(11, 4, 2, 9.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(12, 5, 1, 25.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(13, 5, 3, 0.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(14, 6, 1, 20.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(15, 6, 2, 9.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(16, 7, 1, 24.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(17, 7, 2, 14.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(18, 7, 3, 0.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(19, 8, 1, 20.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(20, 8, 2, 10.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(21, 9, 1, 24.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(22, 9, 2, 13.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(23, 10, 1, 23.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(24, 10, 2, 13.5000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(25, 10, 3, 0.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(26, 11, 1, 22.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(27, 11, 2, 10.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(28, 12, 1,  25.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(29, 12, 2, 13.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(30, 13, 1, 21.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(31, 13, 2, 12.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(32, 14, 1, 21.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(33, 14, 2, 9.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(34, 15, 2, 14.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(35, 15, 1, 17.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(36, 16, 1, 18.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(37, 16, 2, 7.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(38, 16, 3, 0.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(39, 17, 1, 21.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(40, 17, 2, 9.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(41, 18, 1, 20.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(42, 18, 2, 5.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(43, 18, 3, 0.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(44, 20, 1, 20.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(45, 20, 2, 13.5000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(46, 21, 1, 23.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(47, 21, 2, 8.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(48, 22, 1, 23.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(49, 22, 2, 13.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(50, 23, 1, 19.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(51, 23, 2, 9.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(52, 24, 1, 25.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(53, 24, 2, 12.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(54, 24, 3, 0.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(55, 25, 1, 20.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(56, 25, 2, 10.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(57, 26, 1, 22.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(58, 26, 2, 9.5000, '',  NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(59, 27, 1, 21.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(60, 27, 2, 15.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(61, 28, 1, 21.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(62, 28, 2, 15.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(63, 29, 1, 27.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(64, 29, 2, 18.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(65, 30, 1, 19.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES(66, 30, 2, 9.0000, '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");

    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "tax_rates " . UPDATED .'</font>';

    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(2, 'Germany', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(3, 'Belgium', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(4, 'Bulgaria', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(5, 'Denmark', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(6, 'Estonia', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(7, 'Finland', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(8, 'France', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(9, 'Greece', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(10, 'Ireland', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(11, 'Italy', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(12, 'Croatia', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(13, 'Latvia', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(14, 'Lithuania', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(15, 'Luxembourg', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(16, 'Malta', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(17, 'Netherlands', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(18, 'Northern Ireland', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(20, 'Austria', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(21, 'Poland', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(22, 'Portugal', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(23, 'Romania', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(24, 'Sweden', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(25, 'Slovakia', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(26, 'Slovenia', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(27, 'Spain', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(28, 'Czech Republic', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(29, 'Hungary', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added) VALUES(30, 'Cyprus', '', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");

    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "geo_zones " . UPDATED .'</font>';


    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(30, 81, 0, 2, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(31, 21, 0, 3, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(32, 33, 0, 4, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(33, 57, 0, 5, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(34, 67, 0, 6, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(35, 72, 0, 7, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(36, 73, 0, 8, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(37, 84, 0, 9, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(38, 103, 0, 10, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(39, 105, 0, 11, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(40, 53, 0, 12, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(41, 117, 0, 13, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(42, 123, 0, 14, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(43, 124, 0, 15, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(44, 132, 0, 16, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(45, 150, 0, 17, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(46, 243, 0, 18, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(47, 170, 0, 21, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(48, 171, 0, 22, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(49, 203, 0, 24, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(50, 189, 0, 25, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(51, 190, 0, 26, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(52, 195, 0, 27, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(53, 97, 0, 29, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(54, 55, 0, 30, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(55, 14, 0, 20, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES(56, 56, 0, 28, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");

    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "zones_to_geo_zones " . UPDATED .'</font>';

} else {

    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES (1, 1, 1, '19', 'enthaltene MwSt. 19%', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES (2, 1, 1, '7', 'enthaltene MwSt. 7%', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_rate, tax_description, last_modified, date_added) VALUES (3, 1, 1, '0', 'Steuerfrei', NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");

    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "tax_rates " . UPDATED .'</font>';


    $result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, date_added) VALUES (1, 'Europäische Union', 'Für alle Kunden innerhalb der europäischen Union', " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");

    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "geo_zones " . UPDATED .'</font>';


    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (1, 14, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (2, 21, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (3, 33, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (4, 55, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (5, 56, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (6, 81, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (7, 57, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (8, 67, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (9, 195, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (10, 72, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (11, 73, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (12, 222, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (13, 84, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (14, 97, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (15, 53, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (16, 103, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (17, 105, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (18, 123, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (19, 124, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (20, 117, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (21, 132, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (22, 150, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (23, 170, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (24, 171, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (25, 175, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (26, 203, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (27, 190, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (28, 189, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");

    // Switzerland
    $result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (29, 204, 0, 2, NULL, " . $db->DBTimeStamp($today) . ")") or die("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");

    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "zones_to_geo_zones " . UPDATED .'</font>';

}
