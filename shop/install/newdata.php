<?php
/* ----------------------------------------------------------------------
   $Id: newdata.php,v 1.4 2009/01/16 13:37:39 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2009 by the OOS Development Team.
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
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (1, '" . $address_format . "', '" . $address_summary . "')") or die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city, $state    $postcode$cr$country';
$address_summary = '$city, $state / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (2, '" . $address_format . "', '" . $address_summary . "')") or die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format =  '$firstname $lastname$cr$streets$cr$city$cr$postcode - $statecomma$country';
$address_summary = '$state / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (3, '" . $address_format . "', '" . $address_summary . "')") or die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$city ($postcode)$cr$country';
$address_summary = '$postcode / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (4, '" . $address_format . "', '" . $address_summary . "')") or die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

$address_format = '$firstname $lastname$cr$streets$cr$postcode $city$cr$country';
$address_summary = '$city / $country';
$result = $db->Execute("INSERT INTO " . $prefix_table . "address_format (address_format_id, address_format, address_summary) VALUES (5, '" . $address_format . "', '" . $address_summary . "')") or die ("<b>".NOTUPDATED . $prefix_table . "address_format</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "address_format " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('administrator.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('configuration.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('catalog.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('modules.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('plugins.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('customers.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('ticket.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate.php', 1, 0, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_admin.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('newsfeed.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('links.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('rss_admin.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('taxes.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('localization.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('reports.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('tools.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('export.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('information.php', 1, 0, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");



//administrator.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('admin_members', 0, 1, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('admin_files', 0, 1, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//configuration.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('configuration', 0, 2, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//catalog.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('categories', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_attributes', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_attributes_add', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('manufacturers', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('reviews', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_status', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_units', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('specials', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('products_expected', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('easypopulate', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('featured', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('xsell_products', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('up_sell_products', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('quick_stockupdate', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('export_excel', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('import_excel', 0, 3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");



//modules.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('modules', 0, 4, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//plugins.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('plugins', 0, 5, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//customers.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('customers', 0, 6, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('orders', 0, 6, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('customers_status', 0, 6, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('orders_status', 0, 6, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('manual_loging', 0, 6, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('popup_google_map', 0, 6, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('campaigns', 0, 6, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");


//ticket.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('ticket_admin', 0, 7, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('ticket_department', 0, 7, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('ticket_priority', 0, 7, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('ticket_reply', 0, 7, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('ticket_status', 0, 7, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('ticket_view', 0, 7, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//affiliate.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_banners', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_banners_manager', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_clicks', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_contact', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_help1', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_help2', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_help3', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_help4', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_help5', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_help6', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_help7', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_help8', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_invoice', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_payment', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_popup_image', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_sales', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_statistics', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_summary', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('affiliate_reset', 0, 8, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//gv_admin.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('coupon_admin', 0, 9, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_queue', 0, 9, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_mail', 0, 9, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('gv_sent', 0, 9, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('listcategories', 0, 9, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('listproducts', 0, 9, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('validproducts', 0, 9, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('validcategories', 0, 9, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//content.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_block', 0, 10, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_information', 0, 10, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_news', 0, 10, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('content_page_type', 0, 10, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//newsfeed.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('newsfeed_manager', 0, 11, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('newsfeed_categories', 0, 11, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('newsfeed_view', 0, 11, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//links.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('links_categories', 0, 12, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('links', 0, 12, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('links_contact', 0, 12, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//rss_admin.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('rss_conf', 0, 13, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//taxes.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('countries', 0, 14, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('zones', 0, 14, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('geo_zones', 0, 14, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('tax_classes', 0, 14, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('tax_rates', 0, 14, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//localization.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('currencies', 0, 15, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('languages', 0, 15, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//reports.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_customers', 0, 16, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_referer', 0, 16, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_keywords', 0, 16, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_products_viewed', 0, 16, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_products_purchased', 0, 16, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_low_stock', 0, 16, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_sales_report2', 0, 16, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('stats_recover_cart_sales', 0, 16, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//tools.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('banner_manager', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('define_language', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('file_manager', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('mail', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('newsletters', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('server_info', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('whos_online', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('keyword_show', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('banner_statistics', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('recover_cart_sales', 0, 17, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");

//export.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('export_preissuchmaschine', 0, 18, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('export_googlebase', 0, 18, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");


//information.php
$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_files (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('information', 0, 19, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "admin_files</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "admin_files " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "admin_groups (admin_groups_id, admin_groups_name) VALUES (1, 'Top Administrator')") or die ("<b>".NOTUPDATED . $prefix_table . "admin_groups</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "admin_groups " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "affiliate_payment_status (affiliate_payment_status_id, affiliate_languages_id, affiliate_payment_status_name) VALUES (0, 2, 'Pending')") or die ("<b>".NOTUPDATED . $prefix_table . "affiliate_payment_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "affiliate_payment_status (affiliate_payment_status_id, affiliate_languages_id, affiliate_payment_status_name) VALUES (0, 1, 'Offen')") or die ("<b>".NOTUPDATED . $prefix_table . "affiliate_payment_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "affiliate_payment_status (affiliate_payment_status_id, affiliate_languages_id, affiliate_payment_status_name) VALUES (0, 6, 'Pendiente')") or die ("<b>".NOTUPDATED . $prefix_table . "affiliate_payment_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "affiliate_payment_status (affiliate_payment_status_id, affiliate_languages_id, affiliate_payment_status_name) VALUES (0, 3, 'In afwachting')") or die ("<b>".NOTUPDATED . $prefix_table . "affiliate_payment_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "affiliate_payment_status (affiliate_payment_status_id, affiliate_languages_id, affiliate_payment_status_name) VALUES (1, 2, 'Paid')") or die ("<b>".NOTUPDATED . $prefix_table . "affiliate_payment_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "affiliate_payment_status (affiliate_payment_status_id, affiliate_languages_id, affiliate_payment_status_name) VALUES (1, 1, 'Ausgezahlt')") or die ("<b>".NOTUPDATED . $prefix_table . "affiliate_payment_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "affiliate_payment_status (affiliate_payment_status_id, affiliate_languages_id, affiliate_payment_status_name) VALUES (1, 6, 'Pagado')") or die ("<b>".NOTUPDATED . $prefix_table . "affiliate_payment_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "affiliate_payment_status (affiliate_payment_status_id, affiliate_languages_id, affiliate_payment_status_name) VALUES (1, 3, 'Betaald')") or die ("<b>".NOTUPDATED . $prefix_table . "affiliate_payment_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "affiliate_payment_status " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "banners (banners_id, banners_title, banners_url, banners_image, banners_group, banners_html_text, expires_impressions, expires_date, date_scheduled, date_added, date_status_change, status) VALUES (1, 'OOS [OSIS Online Shop]', 'http://www.oos-shop.de/', 'banners/oos_banner_1.gif', '468x60', '', NULL, NULL, NULL, " . $db->DBTimeStamp($today) . ", NULL, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "banners</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "banners (banners_id, banners_title, banners_url, banners_image, banners_group, banners_html_text, expires_impressions, expires_date, date_scheduled, date_added, date_status_change, status) VALUES (2, 'OOS [OSIS Online Shop]', 'http://www.oos-shop.de/', 'banners/oos_banner_2.gif', '468x60', '', NULL, NULL, NULL, " . $db->DBTimeStamp($today) . ", NULL, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "banners</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "banners " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (1, 'right', 1, 'languages', 'system', 1, 1, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (2, 'right', 1, 'customers_status', '', 1, 2, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (3, 'left', 1, 'categories', 'categories', 1, 3, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (4, 'left', 0, 'manufacturers', 'manufacturers', 1, 4, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (5, 'left', 1, 'whats_new', '', 1, 5, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (6, 'left', 1, 'add_a_quickie', 'system', 1, 6, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (7, 'left', 1, 'search', 'system', 1, 7, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (8, 'left', 1, 'products_history', '', 1, 10, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (9, 'left', 1, 'ticket', 'system', 1, 10, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (10, 'left', 0, 'affiliate', '', 1, 10, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (11, 'left', 0, 'web_search', 'system', 0, 11, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (12, 'left', 1, 'service', 'system', 1, 12, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (13, 'right', 1, 'shopping_cart', '', 1, 13, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (14, 'right', 1, 'login', '', 1, 14, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (15, 'right', 1, 'myworld', '', 1, 15, 1, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (16, 'right', 0, 'manufacturer_info', 'manufacturer_info', 1, 16, 0, " . $db->DBTimeStamp($today) . ",NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (17, 'right', 1, 'order_history', '', 1, 17, 1, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (18, 'right', 1, 'wishlist', '', 1, 18, 1, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (19, 'right', 1, 'best_sellers', '', 1, 19, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (20, 'right', 1, 'product_notifications', '', 1, 20, 0, " . $db->DBTimeStamp($today) . ",NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (21, 'right', 1, 'tell_a_friend', '', 1, 21, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (22, 'right', 0, 'specials', '', 1, 22, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (23, 'right', 0, 'reviews', '', 1, 23, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (24, 'right', 0, 'news_reviews', '', 1, 24, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (25, 'right', 0, 'newsfeeds', '', 1, 25, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (26, 'right', 1, 'currencies', '', 1, 26, 0, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (27, 'left', 1, 'information', 'system', 1, 27, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (28, 'right', 0, 'babelfish', 'system', 1, 28, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (29, 'right', 0, 'translate_google', 'system', 1, 29, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (30, 'right', 0, 'newsletter', 'system', 1, 30, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (31, 'right', 1, 'products_xsell', 'xsell_products', 1, 31, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (32, 'left', 1, 'change_template', 'system', 1, 32, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (33, 'left', 0, 'skype', 'system', 1, 33, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (34, 'left', 0, 'ads', 'system', 1, 34, 0, " . $db->DBTimeStamp($today) . ",  NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block (block_id, block_side, block_status, block_file, block_cache, block_type, block_sort_order, block_login_flag, date_added, last_modified, set_function) VALUES (35, 'right', 0, 'account', '', 1, 35, 1, " . $db->DBTimeStamp($today) . ", NULL, 'oos_block_select_option(array(\'left\', \'right\'),')") or die ("<b>".NOTUPDATED . $prefix_table . "block</b>");



echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "block " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (1, 6, 'Idiomas')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (1, 1, 'Sprachen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (1, 2, 'Languages')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (1, 3, 'Talen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (2, 6, 'Customers Info')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (2, 1, 'Kunden Info')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (2, 2, 'Customers Info')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (2, 3, 'Klanten info')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (3, 6, 'Categorias')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (3, 1, 'Kategorien')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (3, 2, 'Categories')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (3, 3, 'Categorie&euml;n')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (4, 6, 'Fabricantes')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (4, 1, 'Hersteller')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (4, 2, 'Manufacturers')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (4, 3, 'Fabrikanten')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (5, 6, 'Novedades')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (5, 1, 'Neue Produkte')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (5, 2, 'What\'s New?')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (5, 3, 'Nieuwe produkten')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (6, 6, 'Add a Quickie!')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (6, 1, 'Schnelleinkauf')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (6, 2, 'Add a Quickie!')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (6, 3, 'Spoedinkoop!')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (7, 6, 'Busqueda Rapida')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (7, 1, 'Schnellsuche')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (7, 2, 'Quick Find')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (7, 3, 'Snelzoeken')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (8, 6, 'Products History')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (8, 1, 'Besuchte Produkte')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (8, 2, 'Products History')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (8, 3, 'Produktgeschiedenis')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (9, 6, 'Support Ticket')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (9, 1, 'Support')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (9, 2, 'Support Ticket')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (9, 3, 'Hulpaanvraag')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (10, 6, 'Affiliate')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (10, 1, 'Partner')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (10, 2, 'Affiliate')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (10, 3, 'Affiliate')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (11, 6, 'Search the Web')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (11, 1, 'Search the Web')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (11, 2, 'Search the Web')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (11, 3, 'Zoeken op het web')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (12, 6, 'Service')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (12, 1, 'Service')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (12, 2, 'Service')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (12, 3, 'Service')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (13, 6, 'Compras')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (13, 1, 'Warenkorb')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (13, 2, 'Shopping Cart')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (13, 3, 'Winkelwagen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (14, 6, 'Login Here')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (14, 1, 'Anmelden')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (14, 2, 'Login Here')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (14, 3, 'Hier inloggen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (15, 6, 'My World')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (15, 1, 'My World')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (15, 2, 'My World')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (15, 3, 'My World')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (16, 6, 'Fabricante')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (16, 1, 'Hersteller Info')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (16, 2, 'Manufacturer Info')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (16, 3, 'Fabrikanteninfo')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (17, 6, 'Mis Pedidos')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (17, 1, 'Bestell&uuml;bersicht')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (17, 2, 'Order History')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (17, 3, 'Besteloverzicht')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (18, 6, 'My Wishlist')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (18, 1, 'Wunschzettel')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (18, 2, 'My Wishlist')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (18, 3, 'Verlanglijst')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (19, 6, 'Los Mas Vendidos')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (19, 1, 'Bestseller')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (19, 2, 'Bestsellers')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (19, 3, 'Verkoopsuccessen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (20, 6, 'Notificaciones')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (20, 1, 'Produkt-Info')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (20, 2, 'Notifications')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (20, 3, 'Produktinformatie')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (21, 6, 'Diselo a un Amigo')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (21, 1, 'Empfehlen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (21, 2, 'Tell A Friend')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (21, 3, 'Aanraden aan vrienden')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (22, 6, 'Ofertas')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (22, 1, 'Angebote')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (22, 2, 'Specials')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (22, 3, 'Aanbiedingen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (23, 6, 'Comentarios')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (23, 1, 'Bewertungen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (23, 2, 'Reviews')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (23, 3, 'Beoordelingen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (24, 1, 'News Bewertungen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (24, 2, 'News reviews')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (24, 3, 'Nieuwsbeoordelingen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (24, 4, 'News reviews')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (24, 5, 'News reviews')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (24, 6, 'News Comentarios')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (25, 1, 'Newsfeed')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (25, 2, 'Newsfeed')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (25, 3, 'Nieuwsmeldingen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (25, 4, 'Newsfeed')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (25, 5, 'Newsfeed')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (25, 6, 'Newsfeed')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (26, 1, 'W&auml;hrungen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (26, 2, 'Currencies')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (26, 3, 'Valuta')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (26, 4, 'Currencies')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (26, 5, 'Currencies')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (26, 6, 'Monedas')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (27, 1, 'Informationen')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (27, 2, 'Information')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (27, 3, 'Informatie')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (27, 4, 'Information')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (27, 5, 'Information')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (27, 6, 'InformaciÃ³n')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (28, 1, 'Babelfish')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (28, 2, 'Babelfish')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (28, 3, 'Babelfish Vertaler')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (28, 4, 'Babelfish')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (28, 5, 'Babelfish')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (28, 6, 'Babelfish')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (29, 1, 'Google Translator')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (29, 2, 'Google Translator')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (29, 3, 'Google Vertaler')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (29, 4, 'Google Translator')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (29, 5, 'Google Translator')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (29, 6, 'Google Translator')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (30, 1, 'Newsletter')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (30, 2, 'Newsletter')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (30, 3, 'Newsletter')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (30, 4, 'Newsletter')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (30, 5, 'Newsletter')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (30, 6, 'Newsletter')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (31, 1, '&Auml;hnliche Produkte')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (31, 2, 'Family Products')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (31, 3, 'Soortgelijke produkten')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (31, 4, 'Family Products')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (31, 5, 'Family Products')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (31, 6, 'Family Products')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (32, 1, 'Templates')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (32, 2, 'Templates')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (32, 3, 'Templates')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (32, 4, 'Templates')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (32, 5, 'Templates')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (32, 6, 'Templates')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (33, 1, 'Skype')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (33, 2, 'Skype')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (33, 3, 'Skype')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (33, 4, 'Skype')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (33, 5, 'Skype')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (33, 6, 'Skype')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 1, 'Werbung')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 2, 'ads')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 3, 'ads')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 4, 'ads')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 5, 'ads')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (34, 6, 'ads')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (35, 1, 'Mein Konto')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (35, 2, 'Account')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (35, 3, 'Mijn rekening')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (35, 4, 'Account')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (35, 5, 'Account')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_info (block_id, block_languages_id, block_name) VALUES (35, 6, 'Account')") or die ("<b>".NOTUPDATED . $prefix_table . "block_info</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "block_info " . UPDATED .'</font>';

// Languages
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (1, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (2, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (3, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (4, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (5, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (6, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (7, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (7, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (7, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (8, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (8, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (8, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (9, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (9, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (9, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (9, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (9, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (9, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (9, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (10, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (10, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (10, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (11, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (12, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (12, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (12, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (12, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (12, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (12, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (12, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (12, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (13, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (14, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (14, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (15, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (15, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (15, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (15, 8)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (16, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (16, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (16, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (17, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (17, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (17, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (18, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (18, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (18, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (19, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (20, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (21, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (22, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (22, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (23, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (23, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (24, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (24, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (24, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (24, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (25, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (25, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (25, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (26, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (26, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (26, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (26, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

//Information
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (27, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (28, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (28, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (28, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (28, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (28, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (28, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (28, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (29, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (29, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (29, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (29, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (29, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (29, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (29, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (30, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (30, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (30, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (31, 9)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (32, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (33, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (34, 7)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (35, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (35, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "block_to_page_type (block_id, page_type_id) VALUES (35, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "block_to_page_type</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "block_to_page_type " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (1, 'Afghanistan', 'AF', 'AFG', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (2, 'Albania', 'AL', 'ALB', 'ALB', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (3, 'Algeria', 'DZ', 'DZA', 'ALG', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (4, 'American Samoa', 'AS', 'ASM', 'AME', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (5, 'Andorra', 'AD', 'AND', 'AND', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (6, 'Angola', 'AO', 'AGO', 'AGL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (7, 'Anguilla', 'AI', 'AIA', 'ANG', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (8, 'Antarctica', 'AQ', 'ATA', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (9, 'Antigua and Barbuda', 'AG', 'ATG', 'ANT', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (10, 'Argentina', 'AR', 'ARG', 'ARG', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (11, 'Armenia', 'AM', 'ARM', 'ARM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (12, 'Aruba', 'AW', 'ABW', 'ARU', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (13, 'Australia', 'AU', 'AUS', 'AUS', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (14, 'Austria', 'AT', 'AUT', 'AUT', 5)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (15, 'Azerbaijan', 'AZ', 'AZE', 'AZE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (16, 'Bahamas', 'BS', 'BHS', 'BMS', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (17, 'Bahrain', 'BH', 'BHR', 'BAH', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (18, 'Bangladesh', 'BD', 'BGD', 'BAN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (19, 'Barbados', 'BB', 'BRB', 'BAR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (20, 'Belarus', 'BY', 'BLR', 'BLR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (21, 'Belgium', 'BE', 'BEL', 'BGM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (22, 'Belize', 'BZ', 'BLZ', 'BEL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (23, 'Benin', 'BJ', 'BEN', 'BEN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (24, 'Bermuda', 'BM', 'BMU', 'BER', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (25, 'Bhutan', 'BT', 'BTN', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (26, 'Bolivia', 'BO', 'BOL', 'BOL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (27, 'Bosnia and Herzegowina', 'BA', 'BIH', 'BOS', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (28, 'Botswana', 'BW', 'BWA', 'BOT', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (29, 'Bouvet Island', 'BV', 'BVT', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (30, 'Brazil', 'BR', 'BRA', 'BRA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (31, 'British Indian Ocean Territory', 'IO', 'IOT', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (32, 'Brunei Darussalam', 'BN', 'BRN', 'BRU', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (33, 'Bulgaria', 'BG', 'BGR', 'BUL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (34, 'Burkina Faso', 'BF', 'BFA', 'BKF', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (35, 'Burundi', 'BI', 'BDI', 'BUR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (36, 'Cambodia', 'KH', 'KHM', 'CAM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (37, 'Cameroon', 'CM', 'CMR', 'CMR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (38, 'Canada', 'CA', 'CAN', 'CAN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (39, 'Cape Verde', 'CV', 'CPV', 'CAP', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (40, 'Cayman Islands', 'KY', 'CYM', 'CAY', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (41, 'Central African Republic', 'CF', 'CAF', 'CEN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (42, 'Chad', 'TD', 'TCD', 'CHA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (43, 'Chile', 'CL', 'CHL', 'CHL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (44, 'China', 'CN', 'CHN', 'CHN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (45, 'Christmas Island', 'CX', 'CXR', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (46, 'Cocos (Keeling) Islands', 'CC', 'CCK', 'COO', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (47, 'Colombia', 'CO', 'COL', 'COL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (48, 'Comoros', 'KM', 'COM', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (49, 'Congo', 'CG', 'COG', 'CON', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (50, 'Cook Islands', 'CK', 'COK', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (51, 'Costa Rica', 'CR', 'CRI', 'COS', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (52, 'Cote D\'Ivoire', 'CI', 'CIV', 'COT', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (53, 'Croatia', 'HR', 'HRV', 'CRO', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (54, 'Cuba', 'CU', 'CUB', 'CUB', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (55, 'Cyprus', 'CY', 'CYP', 'CYP', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (56, 'Czech Republic', 'CZ', 'CZE', 'CZE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (57, 'Denmark', 'DK', 'DNK', 'DEN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (58, 'Djibouti', 'DJ', 'DJI', 'DJI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (59, 'Dominica', 'DM', 'DMA', 'DOM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (60, 'Dominican Republic', 'DO', 'DOM', 'DRP', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (61, 'East Timor', 'TP', 'TMP', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (62, 'Ecuador', 'EC', 'ECU', 'ECU', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (63, 'Egypt', 'EG', 'EGY', 'EGY', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (64, 'El Salvador', 'SV', 'SLV', 'EL_', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (65, 'Equatorial Guinea', 'GQ', 'GNQ', 'EQU', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (66, 'Eritrea', 'ER', 'ERI', 'ERI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (67, 'Estonia', 'EE', 'EST', 'EST', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (68, 'Ethiopia', 'ET', 'ETH', 'ETH', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (70, 'Faroe Islands', 'FO', 'FRO', 'FAR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (71, 'Fiji', 'FJ', 'FJI', 'FIJ', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (72, 'Finland', 'FI', 'FIN', 'FIN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (73, 'France', 'FR', 'FRA', 'FRA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (74, 'France, Metropolitan', 'FX', 'FXX', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (75, 'French Guiana', 'GF', 'GUF', 'FRE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (76, 'French Polynesia', 'PF', 'PYF', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (77, 'French Southern Territories', 'TF', 'ATF', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (78, 'Gabon', 'GA', 'GAB', 'GAB', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (79, 'Gambia', 'GM', 'GMB', 'GAM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (80, 'Georgia', 'GE', 'GEO', 'GEO', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (81, 'Germany', 'DE', 'DEU', 'GER', 5)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (82, 'Ghana', 'GH', 'GHA', 'GHA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (83, 'Gibraltar', 'GI', 'GIB', 'GIB', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (84, 'Greece', 'GR', 'GRC', 'GRC', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (85, 'Greenland', 'GL', 'GRL', 'GRL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (86, 'Grenada', 'GD', 'GRD', 'GRE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (87, 'Guadeloupe', 'GP', 'GLP', 'GDL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (88, 'Guam', 'GU', 'GUM', 'GUM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (89, 'Guatemala', 'GT', 'GTM', 'GUA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (90, 'Guinea', 'GN', 'GIN', 'GUI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (91, 'Guinea-bissau', 'GW', 'GNB', 'GBS', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (92, 'Guyana', 'GY', 'GUY', 'GUY', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (93, 'Haiti', 'HT', 'HTI', 'HAI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (94, 'Heard and Mc Donald Islands', 'HM', 'HMD', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (95, 'Honduras', 'HN', 'HND', 'HON', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (96, 'Hong Kong', 'HK', 'HKG', 'HKG', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (97, 'Hungary', 'HU', 'HUN', 'HUN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (98, 'Iceland', 'IS', 'ISL', 'ICE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (99, 'India', 'IN', 'IND', 'IND', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (100, 'Indonesia', 'ID', 'IDN', 'IDS', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (101, 'Iran (Islamic Republic of)', 'IR', 'IRN', 'IRN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (102, 'Iraq', 'IQ', 'IRQ', 'IRA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (103, 'Ireland', 'IE', 'IRL', 'IRE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (104, 'Israel', 'IL', 'ISR', 'ISR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (105, 'Italy', 'IT', 'ITA', 'ITA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (106, 'Jamaica', 'JM', 'JAM', 'JAM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (107, 'Japan', 'JP', 'JPN', 'JAP', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (108, 'Jordan', 'JO', 'JOR', 'JOR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (109, 'Kazakhstan', 'KZ', 'KAZ', 'KAZ', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (110, 'Kenya', 'KE', 'KEN', 'KEN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (111, 'Kiribati', 'KI', 'KIR', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (112, 'Korea, Democratic People\'s Republic of', 'KP', 'PRK', 'SKO', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (113, 'Korea, Republic of', 'KR', 'KOR', 'KOR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (114, 'Kuwait', 'KW', 'KWT', 'KUW', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (115, 'Kyrgyzstan', 'KG', 'KGZ', 'KYR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (116, 'Lao People\'s Democratic Republic', 'LA', 'LAO', 'LAO', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (117, 'Latvia', 'LV', 'LVA', 'LAT', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (118, 'Lebanon', 'LB', 'LBN', 'LEB', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (119, 'Lesotho', 'LS', 'LSO', 'LES', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (120, 'Liberia', 'LR', 'LBR', 'LIB', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', 'LBY', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (122, 'Liechtenstein', 'LI', 'LIE', 'LIE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (123, 'Lithuania', 'LT', 'LTU', 'LIT', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (124, 'Luxembourg', 'LU', 'LUX', 'LUX', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (125, 'Macau', 'MO', 'MAC', 'MAC', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (126, 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MKD', 'F.Y', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (127, 'Madagascar', 'MG', 'MDG', 'MAD', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (128, 'Malawi', 'MW', 'MWI', 'MLW', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (129, 'Malaysia', 'MY', 'MYS', 'MLS', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (130, 'Maldives', 'MV', 'MDV', 'MAL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (131, 'Mali', 'ML', 'MLI', 'MLI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (132, 'Malta', 'MT', 'MLT', 'MLT', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (133, 'Marshall Islands', 'MH', 'MHL', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (134, 'Martinique', 'MQ', 'MTQ', 'MAR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (135, 'Mauritania', 'MR', 'MRT', 'MRT', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (136, 'Mauritius', 'MU', 'MUS', 'MAU', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (137, 'Mayotte', 'YT', 'MYT', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (138, 'Mexico', 'MX', 'MEX', 'MEX', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (139, 'Micronesia, Federated States of', 'FM', 'FSM', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (140, 'Moldova, Republic of', 'MD', 'MDA', 'MOL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (141, 'Monaco', 'MC', 'MCO', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (142, 'Mongolia', 'MN', 'MNG', 'MON', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (143, 'Montserrat', 'MS', 'MSR', 'MTT', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (144, 'Morocco', 'MA', 'MAR', 'MOR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (145, 'Mozambique', 'MZ', 'MOZ', 'MOZ', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (146, 'Myanmar', 'MM', 'MMR', 'MYA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (147, 'Namibia', 'NA', 'NAM', 'NAM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (148, 'Nauru', 'NR', 'NRU', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (149, 'Nepal', 'NP', 'NPL', 'NEP', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (150, 'Netherlands', 'NL', 'NLD', 'NED', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (151, 'Netherlands Antilles', 'AN', 'ANT', 'NET', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (152, 'New Caledonia', 'NC', 'NCL', 'CDN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (153, 'New Zealand', 'NZ', 'NZL', 'NEW', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (154, 'Nicaragua', 'NI', 'NIC', 'NIC', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (155, 'Niger', 'NE', 'NER', 'NIG', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (156, 'Nigeria', 'NG', 'NGA', 'NGR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (157, 'Niue', 'NU', 'NIU', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (158, 'Norfolk Island', 'NF', 'NFK', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (159, 'Northern Mariana Islands', 'MP', 'MNP', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (160, 'Norway', 'NO', 'NOR', 'NWY', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (161, 'Oman', 'OM', 'OMN', 'OMA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (162, 'Pakistan', 'PK', 'PAK', 'PAK', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (163, 'Palau', 'PW', 'PLW', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (164, 'Panama', 'PA', 'PAN', 'PAN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (165, 'Papua New Guinea', 'PG', 'PNG', 'PAP', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (166, 'Paraguay', 'PY', 'PRY', 'PAR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (167, 'Peru', 'PE', 'PER', 'PER', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (168, 'Philippines', 'PH', 'PHL', 'PHI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (169, 'Pitcairn', 'PN', 'PCN', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (170, 'Poland', 'PL', 'POL', 'POL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (171, 'Portugal', 'PT', 'PRT', 'POR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (172, 'Puerto Rico', 'PR', 'PRI', 'PUE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (173, 'Qatar', 'QA', 'QAT', 'QAT', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (174, 'Reunion', 'RE', 'REU', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (175, 'Romania', 'RO', 'ROM', 'ROM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (176, 'Russian Federation', 'RU', 'RUS', 'RUS', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (177, 'Rwanda', 'RW', 'RWA', 'RWA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (178, 'Saint Kitts and Nevis', 'KN', 'KNA', 'SKN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (179, 'Saint Lucia', 'LC', 'LCA', 'SLU', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 'ST.', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (181, 'Samoa', 'WS', 'WSM', 'WES', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (182, 'San Marino', 'SM', 'SMR', 'SAN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (183, 'Sao Tome and Principe', 'ST', 'STP', 'SAO', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (184, 'Saudi Arabia', 'SA', 'SAU', 'SAU', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (185, 'Senegal', 'SN', 'SEN', 'SEN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (186, 'Seychelles', 'SC', 'SYC', 'SEY', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (187, 'Sierra Leone', 'SL', 'SLE', 'SIE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (188, 'Singapore', 'SG', 'SGP', 'SIN', 4)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 'SLO', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (190, 'Slovenia', 'SI', 'SVN', 'SLV', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (191, 'Solomon Islands', 'SB', 'SLB', 'SOL', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (192, 'Somalia', 'SO', 'SOM', 'SOM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (193, 'South Africa', 'ZA', 'ZAF', 'SOU', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (194, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (195, 'Spain', 'ES', 'ESP', 'SPA', 3)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (196, 'Sri Lanka', 'LK', 'LKA', 'SRI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (197, 'St. Helena', 'SH', 'SHN', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (198, 'St. Pierre and Miquelon', 'PM', 'SPM', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (199, 'Sudan', 'SD', 'SDN', 'SUD', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (200, 'Suriname', 'SR', 'SUR', 'SUR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (202, 'Swaziland', 'SZ', 'SWZ', 'SWA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (203, 'Sweden', 'SE', 'SWE', 'SWE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (204, 'Switzerland', 'CH', 'CHE', 'ZWI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (205, 'Syrian Arab Republic', 'SY', 'SYR', 'SYR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (206, 'Taiwan', 'TW', 'TWN', 'TWN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (207, 'Tajikistan', 'TJ', 'TJK', 'TAJ', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (208, 'Tanzania, United Republic of', 'TZ', 'TZA', 'TAN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (209, 'Thailand', 'TH', 'THA', 'THA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (210, 'Togo', 'TG', 'TGO', 'TOG', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (211, 'Tokelau', 'TK', 'TKL', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (212, 'Tonga', 'TO', 'TON', 'TON', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (213, 'Trinidad and Tobago', 'TT', 'TTO', 'TRI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (214, 'Tunisia', 'TN', 'TUN', 'TUN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (215, 'Turkey', 'TR', 'TUR', 'TUR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (216, 'Turkmenistan', 'TM', 'TKM', 'TKM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (217, 'Turks and Caicos Islands', 'TC', 'TCA', 'TCI', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (218, 'Tuvalu', 'TV', 'TUV', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (219, 'Uganda', 'UG', 'UGA', 'UGA', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (220, 'Ukraine', 'UA', 'UKR', 'UKR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (221, 'United Arab Emirates', 'AE', 'ARE', 'UAE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (222, 'United Kingdom', 'GB', 'GBR', 'GBR', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (223, 'United States', 'US', 'USA', 'UNI', 2)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (224, 'United States Minor Outlying Islands', 'UM', 'UMI', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (225, 'Uruguay', 'UY', 'URY', 'URU', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (226, 'Uzbekistan', 'UZ', 'UZB', 'UZB', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (227, 'Vanuatu', 'VU', 'VUT', 'VAN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (228, 'Vatican City State (Holy See)', 'VA', 'VAT', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (229, 'Venezuela', 'VE', 'VEN', 'VEN', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (230, 'Viet Nam', 'VN', 'VNM', 'VIE', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (231, 'Virgin Islands (British)', 'VG', 'VGB', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (232, 'Virgin Islands (U.S.)', 'VI', 'VIR', 'US_', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (233, 'Wallis and Futuna Islands', 'WF', 'WLF', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (234, 'Western Sahara', 'EH', 'ESH', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (235, 'Yemen', 'YE', 'YEM', 'YEM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (236, 'Yugoslavia', 'YU', 'YUG', 'YUG', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (237, 'Zaire', 'ZR', 'ZAR', '', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (238, 'Zambia', 'ZM', 'ZMB', 'ZAM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "countries (countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, countries_moneybookers, address_format_id) VALUES (239, 'Zimbabwe', 'ZW', 'ZWE', 'ZIM', 1)") or die ("<b>".NOTUPDATED . $prefix_table . "countries</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "countries " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "currencies (currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value, last_updated) VALUES (1, 'Euro', 'EUR', '', 'EUR', '.', ', ', '2', '1.00000000', " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "currencies</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "currencies (currencies_id, title, code, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value, last_updated) VALUES (2, 'US Dollar', 'USD', '$', '', '.', ', ', '2', '0.98000002', " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "currencies</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "currencies " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 1, 'Admin', 'smile-yellow.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'banktransfer.php;cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 2, 'Admin', 'smile-yellow.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'banktransfer.php;cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 3, 'Admin', 'smile-yellow.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'banktransfer.php;cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 4, 'Admin', 'smile-yellow.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'banktransfer.php;cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 5, 'Admin', 'smile-yellow.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'banktransfer.php;cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (1, 6, 'Admin', 'smile-yellow.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'banktransfer.php;cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 1, 'gast', 'smile-green.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 2, 'guest', 'smile-green.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 3, 'bezoeker', 'smile-green.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 4, 'guest', 'smile-green.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 5, 'guest', 'smile-green.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "customers_status (customers_status_id, customers_status_languages_id, customers_status_name, customers_status_image, customers_status_discount, customers_status_ot_discount_flag, customers_status_ot_discount, customers_status_ot_minimum, customers_status_public, customers_status_show_price, customers_status_show_price_tax, customers_status_qty_discounts, customers_status_payment) VALUES (2, 6, 'guest', 'smile-green.gif', '0.00', '0', '0.00', '0.00', '0', '1', '1', '1', 'cod.php;moneyorder.php')") or die ("<b>".NOTUPDATED . $prefix_table . "customers_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "customers_status " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "geo_zones (geo_zone_id, geo_zone_name, geo_zone_description, date_added) VALUES (1, 'Europaeische Union', 'Fuer alle Kunden innerhalb der europaeische Union', " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "geo_zones</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "geo_zones " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (1, 'specials.gif', '1', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die ("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (2, 'specials.gif', '2', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die ("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (3, 'specials.gif', '3', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die ("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (4, 'specials.gif', '4', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die ("<b>".NOTUPDATED . $prefix_table . "information</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information (information_id, information_image, sort_order, date_added, last_modified, status) VALUES (5, 'specials.gif', '5', " . $db->DBTimeStamp($today) . ", NULL, '1' )") or die ("<b>".NOTUPDATED . $prefix_table . "information</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "information " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (1, 1, '', 'Liefer- und Versandbedingungen', 'Liefer- und Versandbedingungen', 'F&uuml;gen Sie hier Ihre Liefer- und Versandbedingungen ein' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (1, 2, '', 'Shipping & Returns', 'Shipping & Returns', 'Put here your Shipping & Returns information' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (1, 3, '', 'Aflevering en terugname', 'Aflevering en terugname', 'Voeg hier uw informatie over de voorwaarden die gelden voor aflevering en terugname van onze produkten in' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (1, 4, '', 'Shipping & Returns', 'Shipping & Returns', 'Put here your Shipping & Returns information' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (1, 5, '', 'Shipping & Returns', 'Shipping & Returns', 'Put here your Shipping & Returns information' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (1, 6, '', 'Envios/Devoluciones', 'Envios/Devoluciones', 'Ponga aqui informacion sobre los Envios y Devoluciones' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (2, 1, '', 'Privatsph&auml;re und Datenschutz', 'Privatsph&auml;re und Datenschutz', 'F&uuml;gen Sie hier Ihre Informationen zur Privatsph&auml;re und Datenschutz ein' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (2, 2, '', 'Privacy Notice', 'Privacy Notice', 'Put here your Privacy Notice information' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (2, 3, '', 'Privacy', 'Privacy', 'Voer hier uw informatie over privacy en gegevensbescherming in' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (2, 4, '', 'Privacy Notice', 'Privacy Notice', 'Put here your Privacy Notice information' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (2, 5, '', 'Privacy Notice', 'Privacy Notice', 'Put here your Privacy Notice information' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (2, 6, '', 'Confidencialidad', 'Confidencialidad', 'Ponga aqui informacion sobre el tratamiento de los datos' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (3, 1, '', 'Unsere AGB', 'Unsere AGB', 'F&uuml;gen Sie hier Ihre AGB ein' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (3, 2, '', 'Conditions of Use', 'Conditions of Use', 'Put here your Conditions of Use information' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (3, 3, '', 'Leveringsvoorwaarden', 'Leveringsvoorwaarden', 'Voer hier uw algemene leveringsvoorwaarden in' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (3, 4, '', 'Conditions of Use', 'Conditions of Use', 'Put here your Conditions of Use information' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (3, 5, '', 'Conditions of Use', 'Conditions of Use', 'Put here your Conditions of Use information' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (3, 6, '', 'Condiciones de uso', 'Condiciones de uso', 'Ponga aqui sus condiciones de uso' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (4, 1, '', 'Impressum', 'Impressum', 'F&uuml;gen Sie hier Ihre Informationen zum Impressum ein.' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (4, 2, '', 'Imprint', 'Imprint', 'Put here your information about your company' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (4, 3, '', 'Bedrijsgegevens', 'Bedrijsgegevens', 'Voer hier uw informatie over deze impressie in' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (4, 4, '', 'Imprint', 'Imprint', 'Put here your information about your company' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (4, 5, '', 'Imprint', 'Imprint', 'Put here your information about your company' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (4, 6, '', 'Imprint', 'Imprint', 'Put here your information about your company' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (5, 1, '', 'Haftungsausschluss', 'Haftungsausschluss', 'F&uuml;gen Sie hier Ihren Haftungsausschluss ein' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (5, 2, '', 'Disclaimer', 'Disclaimer', 'Put here your Disclaimer' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (5, 3, '', 'Disclaimer', 'Disclaimer', 'Put here your Disclaimer' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (5, 4, '', 'Disclaimer', 'Disclaimer', 'Put here your Disclaimer' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (5, 5, '', 'Disclaimer', 'Disclaimer', 'Put here your Disclaimer' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "information_description (information_id, information_languages_id, information_url, information_name, information_heading_title, information_description) VALUES (5, 6, '', 'Disclaimer', 'Disclaimer', 'Put here your Disclaimer' )") or die ("<b>".NOTUPDATED . $prefix_table . "information_description</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "information_description " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (1, 'Deutsch', 'deu', 'de', 1, 1)") or die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (2, 'English', 'eng', 'en', 1, 2)") or die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (3, 'Nederlands', 'nld', 'nl', 1, 3)") or die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (4, 'Polski', 'pol', 'pl', 0, 4)") or die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (5, 'Russian', 'rus', 'ru', 0, 5)") or die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (6, 'Spanish', 'spa', 'es', 1, 6)") or die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "languages " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (1, 1, 'Pending')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (1, 2, 'Pending')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (1, 3, 'In afwachting')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (1, 4, 'Pending')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (1, 5, 'Pending')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (1, 6, 'Pending')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (2, 1, 'Approved')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (2, 2, 'Approved')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (2, 3, 'Goedgekeurd')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (2, 4, 'Approved')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (2, 5, 'Approved')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (2, 6, 'Approved')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");


$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (3, 1, 'Disabled')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (3, 2, 'Disabled')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (3, 3, 'Uitgeschakeld')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (3, 4, 'Disabled')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (3, 5, 'Disabled')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "links_status (links_status_id, links_status_languages_id, links_status_name) VALUES (3, 6, 'Disabled')") or die ("<b>".NOTUPDATED . $prefix_table . "links_status</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "links_status " . UPDATED .'</font>';





$result = $db->Execute("INSERT INTO " . $prefix_table . "manual_info (man_info_id, man_name, status, manual_date_added, defined) VALUES ('1', 'Manual Entry', 0, " . $db->DBTimeStamp($today) . ",  'admin_log')") or die ("<b>".NOTUPDATED . $prefix_table . "manual_info</b>");
echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "manual_info " . UPDATED .'</font>';



# $result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed (newsfeed_id, newsfeed_image, newsfeed_type, date_added, last_modified) VALUES (1, 'logo_rss.gif', 'products_new', " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed (newsfeed_id, newsfeed_image, newsfeed_type, date_added) VALUES (1, 'logo_rss.gif', 'products_new', " . $db->DBTimeStamp($today) . ")");

if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "newsfeed " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('1', '10', 'Linux Today', 'http://linuxtoday.com/backend/my-netscape.rdf', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('2', '10', 'Web Developer News', 'http://headlines.internet.com/internetnews/wd-news/news.rss', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('3', '10', 'Linux Central:New Products', 'http://linuxcentral.com/backend/lcnew.rdf', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('4', '10', 'Linux Central:Daily Specials', 'http://linuxcentral.com/backend/lcspecialns.rdf', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('5', '10', 'Security Forums', 'http://www.security-forums.com/klip/content_feed.php', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('6', '10', 'BSD Today', 'http://www.bsdtoday.com/backend/bt.rdf', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('7', '1', 'Cars Everything', 'http://www.carseverything.com/data/headlines.rdf', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('8', '10', 'Car Survey', 'http://www.carsurvey.org/carsurvey.rss', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('9', '1', 'Garden Guides', 'http://www.gardenguides.com/ggrdf.cdf', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('10', '3', 'Internet:Finance News', 'http://headlines.internet.com/internetnews/fina-news/news.rss', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('11', '3', 'Bloomberg', 'http://myrss.com/f/b/l/bloombergH8n6k63.rss', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('12', '3', 'Asia Street Intelligence Ezine', 'http://www.apmforum.com/channel.xml', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('13', '4', 'NHL', 'http://www.sportingnews.com/klip/foods/sportingNewsNHL.food', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('14', '4', 'NASCAR', 'http://www.sportingnews.com/klip/foods/sportingNewsNASCAR.food', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('15', '4', 'NFL', 'http://www.sportingnews.com/klip/foods/sportingNewsNFL.food', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('16', '4', 'NBA', 'http://www.sportingnews.com/klip/foods/sportingNewsNBA.food', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('17', '4', 'Cricket', 'http://www.newsisfree.com/HPE/xml/feeds/24/1324.xml', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('18', '6', 'Motley Fool', 'http://www.fool.com/xml/foolnews_rss091.xml', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('19', '6', 'Digital Theatre', 'http://www.dtheatre.com/backend.php?xml=yes', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('20', '10', 'KDE News', 'http://www.kdenews.org/rdf', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('21', '10', 'Freshmeat.net', 'http://freshmeat.net/backend/fm.rdf', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('22', '10', 'PHP-homepage.de', 'http://www.php-homepage.de/backend/rdf.php', 1, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('23', '10', 'PHPBuilder', 'http://www.phpbuilder.com/rss_feed.php?type=articles&limit=10', 2, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('24', '5', 'Heise', 'http://www.heise.de/newsticker/heise.rdf', 1, '3', '3600', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
if ($server == 'localhost') {
  $result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('25', '8', 'OOS [OSIS Online Shop]', 'http://www.oos-shop.de/backend.php', 1, '8', '86400', '0', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
} else {
  $result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_manager (newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name, newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles, newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added, newsfeed_manager_last_modified, newsfeed_manager_sort_order) VALUES ('25', '8', 'OOS [OSIS Online Shop]', 'http://www.oos-shop.de/backend.php', 1, '8', '86400', '1', " . $db->DBTimeStamp($today) . ", NULL, '0')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_manager</b>");
}

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "newsfeed_manager " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (1, 1, 'Lifestyle')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (1, 2, 'Lifestyle')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (1, 6, 'Lifestyle')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (1, 3, 'Lifestyle')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (2, 1, 'Industry')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (2, 2, 'Industry')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (2, 6, 'Industry')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (2, 3, 'Industrie')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (3, 1, 'Finance')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (3, 2, 'Finance')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (3, 6, 'Finance')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (3, 3, 'Financieel')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (4, 1, 'Sports')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (4, 2, 'Sports')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (4, 6, 'Sports')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (4, 3, 'Sport')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (5, 1, 'Technology')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (5, 2, 'Technology')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (5, 6, 'Technology')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (5, 3, 'Technologie')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (6, 1, 'Entertainment')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (6, 2, 'Entertainment')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (6, 6, 'Entertainment')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (6, 3, 'Amusement')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (7, 1, 'Science')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (7, 2, 'Science')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (7, 6, 'Science')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (7, 3, 'Wetenschap')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (8, 1, 'Business')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (8, 2, 'Business')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (8, 6, 'Business')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (8, 3, 'Zaken')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (9, 1, 'Top Stories')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (9, 2, 'Top Stories')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (9, 6, 'Top Stories')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (9, 3, 'Top verhalen')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (10, 1, 'Internet')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (10, 2, 'Internet')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (10, 6, 'Internet')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_categories (newsfeed_categories_id, newsfeed_categories_languages_id, newsfeed_categories_name) VALUES (10, 3, 'Internet')") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_categories</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "newsfeed_categories " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_info (newsfeed_id, newsfeed_name, newsfeed_title, newsfeed_description, newsfeed_languages_id, newsfeed_viewed) VALUES (1, 'products_rss.php', 'osis online shop', 'Der Treffpunkt f&uuml;r Einsteiger und Profis', 1, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_info (newsfeed_id, newsfeed_name, newsfeed_title, newsfeed_description, newsfeed_languages_id, newsfeed_viewed) VALUES (1, 'products_rss.php', 'osis online shop', 'Der Treffpunkt f&uuml;r Einsteiger und Profis', 2, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_info (newsfeed_id, newsfeed_name, newsfeed_title, newsfeed_description, newsfeed_languages_id, newsfeed_viewed) VALUES (1, 'products_rss.php', 'osis online shop', 'Der Treffpunkt f&uuml;r Einsteiger und Profis', 6, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_info</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "newsfeed_info (newsfeed_id, newsfeed_name, newsfeed_title, newsfeed_description, newsfeed_languages_id, newsfeed_viewed) VALUES (1, 'products_rss.php', 'Uw bedrijf', 'Het trefpunt voor beginners en profs', 3, 0)") or die ("<b>".NOTUPDATED . $prefix_table . "newsfeed_info</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "newsfeed_info " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (1, 2, 'Pending')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (1, 1, 'Offen')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (1, 6, 'Pendiente')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (1, 3, 'In afwachting')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (2, 2, 'Processing')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (2, 1, 'In Bearbeitung')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (2, 6, 'Proceso')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (2, 3, 'In behandeling')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (3, 2, 'Delivered')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (3, 1, 'Versendet')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (3, 6, 'Entregado')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "orders_status (orders_status_id, orders_languages_id, orders_status_name) VALUES (3, 3, 'Geleverd')") or die ("<b>".NOTUPDATED . $prefix_table . "orders_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "orders_status " . UPDATED .'</font>';



$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (1, 2, 'Frontpage')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (1, 1, 'Startseite')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (1, 6, 'Frontpage')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (1, 3, 'Startpagina')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (2, 2, 'Shop')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (2, 1, 'Shop')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (2, 6, 'Shop')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (2, 3, 'Winkel')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (3, 2, 'Products')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (3, 1, 'Produkte')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (3, 6, 'Products')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (3, 3, 'Produkten')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (4, 2, 'News')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (4, 1, 'News')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (4, 6, 'News')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (4, 3, 'Nieuws')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (5, 2, 'Service')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (5, 1, 'Service')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (5, 6, 'Service')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (5, 3, 'Service')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (6, 2, 'Checkout')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (6, 1, 'Kasse')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (6, 6, 'Realizar Pedido')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (6, 3, 'Kassa')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (7, 6, 'Affiliate')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (7, 1, 'Partner')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (7, 2, 'Affiliate')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (7, 3, 'Affiliate')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (8, 6, 'Account')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (8, 1, 'Kundenkonto')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (8, 2, 'Account')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (8, 3, 'Klantenrekening')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (9, 2, 'Reviews: Products')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (9, 1, 'Meinungen: Produkte')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (9, 6, 'Reviews: Products')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "page_type (page_type_id, page_type_languages_id, page_type_name) VALUES (9, 3, 'Reviews: Produkten')") or die ("<b>".NOTUPDATED . $prefix_table . "page_type</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "page_type " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (1, 2, 'Out of Stock')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (1, 1, 'nicht vorr&auml;tig')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (1, 6, 'Agotado')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (1, 3, 'Niet op voorraad')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (2, 2, 'Available Soon')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (2, 1, 'bald verf&uuml;bar')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (2, 6, 'Available Soon')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (2, 3, 'Binnenkort leverbaar')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (3, 2, 'In Stock')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (3, 1, 'auf Lager')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (3, 6, 'Disponible')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_status (products_status_id, products_status_languages_id, products_status_name) VALUES (3, 3, 'Op voorraad')") or die ("<b>".NOTUPDATED . $prefix_table . "products_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "products_status " . UPDATED .'</font>';


// products_options_types
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (1, 2, 'Select')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (1, 1, 'Select')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (1, 6, 'Select')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (1, 3, 'Selecteer')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (2, 2, 'Checkbox')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (2, 1, 'Checkbox')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (2, 6, 'Checkbox')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (2, 3, 'Aanvinkveld')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (3, 2, 'Radio')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (3, 1, 'Radio')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (3, 6, 'Radio')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (3, 3, 'Radio')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (4, 2, 'Text')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (4, 1, 'Text')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (4, 6, 'Text')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (4, 3, 'Tekst')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (5, 2, 'File')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (5, 1, 'File')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (5, 6, 'File')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "products_options_types (products_options_types_id, products_options_types_languages_id, products_options_types_name) VALUES (5, 3, 'Bestand')") or die ("<b>".NOTUPDATED . $prefix_table . "products_options_types</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "products_options_types " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) VALUES (1, 'German Normal', 'normaler Steuersatz f&uuml;r Dienstleistungen und alle non-food Artikel', NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "tax_class</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) VALUES (2, 'German Vermindert', 'verminderter Steuersatz f&uuml;r Lebensmittel und B&uuml;cher', NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "tax_class</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "tax_class " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_priority, tax_rate, tax_description, last_modified, date_added) VALUES (1, 1, 1, 1, '19', 'enthaltene MwSt. 19%', NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_priority, tax_rate, tax_description, last_modified, date_added) VALUES (2, 1, 2, 1, '7', 'enthaltene MwSt. 7%', NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "tax_rates</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "tax_rates " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_admins (ticket_admin_id, ticket_languages_id, ticket_admin_name) VALUES (1, 2, 'John Doe<br>Support')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_admins</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_admins (ticket_admin_id, ticket_languages_id, ticket_admin_name) VALUES (1, 1, 'John Doe<br>Support')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_admins</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_admins (ticket_admin_id, ticket_languages_id, ticket_admin_name) VALUES (1, 6, 'John Doe<br>Support')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_admins</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_admins (ticket_admin_id, ticket_languages_id, ticket_admin_name) VALUES (1, 3, 'Anoniem<br>Support')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_admins</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "ticket_admins " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_department (ticket_department_id, ticket_languages_id, ticket_department_name) VALUES (1, 2, 'Sale')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_department</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_department (ticket_department_id, ticket_languages_id, ticket_department_name) VALUES (1, 1, 'Verkauf')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_department</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_department (ticket_department_id, ticket_languages_id, ticket_department_name) VALUES (1, 6, 'Sale')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_department</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_department (ticket_department_id, ticket_languages_id, ticket_department_name) VALUES (1, 3, 'Verkoop')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_department</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_department (ticket_department_id, ticket_languages_id, ticket_department_name) VALUES (2, 2, 'Affiliate')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_department</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_department (ticket_department_id, ticket_languages_id, ticket_department_name) VALUES (2, 1, 'Affiliate')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_department</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_department (ticket_department_id, ticket_languages_id, ticket_department_name) VALUES (2, 6, 'Affiliate')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_department</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_department (ticket_department_id, ticket_languages_id, ticket_department_name) VALUES (2, 3, 'Partner')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_department</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "ticket_department " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_priority (ticket_priority_id, ticket_languages_id, ticket_priority_name) VALUES (1, 2, 'High')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_priority</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_priority (ticket_priority_id, ticket_languages_id, ticket_priority_name) VALUES (1, 1, 'Hoch')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_priority</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_priority (ticket_priority_id, ticket_languages_id, ticket_priority_name) VALUES (1, 6, 'High')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_priority</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_priority (ticket_priority_id, ticket_languages_id, ticket_priority_name) VALUES (1, 3, 'Hoog')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_priority</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_priority (ticket_priority_id, ticket_languages_id, ticket_priority_name) VALUES (2, 2, 'Low')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_priority</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_priority (ticket_priority_id, ticket_languages_id, ticket_priority_name) VALUES (2, 1, 'Low')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_priority</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_priority (ticket_priority_id, ticket_languages_id, ticket_priority_name) VALUES (2, 6, 'Low')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_priority</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_priority (ticket_priority_id, ticket_languages_id, ticket_priority_name) VALUES (2, 3, 'Laag')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_priority</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "ticket_priority " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_reply (ticket_reply_id, ticket_languages_id, ticket_reply_name, ticket_reply_text) VALUES (1, 2, 'A Reply', 'This is a reply you can insert by pressing a button')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_reply</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_reply (ticket_reply_id, ticket_languages_id, ticket_reply_name, ticket_reply_text) VALUES (1, 1, 'Eine Antwort', 'Dies ist eine Antwort die per Knopfdruck eingespielt werden kann')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_reply</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_reply (ticket_reply_id, ticket_languages_id, ticket_reply_name, ticket_reply_text) VALUES (1, 6, 'A Reply', 'This is a reply you can insert by pressing a button')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_reply</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_reply (ticket_reply_id, ticket_languages_id, ticket_reply_name, ticket_reply_text) VALUES (1, 3, 'Een antwoord', 'Dit is een antwoord dat per knopdruk ingevoegd kan worden')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_reply</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "ticket_reply " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_status (ticket_status_id, ticket_languages_id, ticket_status_name) VALUES (1, 2, 'Open')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_status (ticket_status_id, ticket_languages_id, ticket_status_name) VALUES (1, 1, 'Offen')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_status (ticket_status_id, ticket_languages_id, ticket_status_name) VALUES (1, 6, 'Open')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_status (ticket_status_id, ticket_languages_id, ticket_status_name) VALUES (1, 3, 'Open')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_status</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_status (ticket_status_id, ticket_languages_id, ticket_status_name) VALUES (2, 2, 'Closed')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_status (ticket_status_id, ticket_languages_id, ticket_status_name) VALUES (2, 1, 'Geschlossen')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_status (ticket_status_id, ticket_languages_id, ticket_status_name) VALUES (2, 6, 'Closed')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_status</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "ticket_status (ticket_status_id, ticket_languages_id, ticket_status_name) VALUES (2, 3, 'Gesloten')") or die ("<b>".NOTUPDATED . $prefix_table . "ticket_status</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "ticket_status " . UPDATED .'</font>';

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (66, 38, 'AB', 'Alberta')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (67, 38, 'BC', 'British Columbia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (68, 38, 'MB', 'Manitoba')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (69, 38, 'NF', 'Newfoundland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (70, 38, 'NB', 'New Brunswick')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (71, 38, 'NS', 'Nova Scotia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (72, 38, 'NT', 'Northwest Territories')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (73, 38, 'NU', 'Nunavut')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (74, 38, 'ON', 'Ontario')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (75, 38, 'PE', 'Prince Edward Island')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (76, 38, 'QC', 'Quebec')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (77, 38, 'SK', 'Saskatchewan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (78, 38, 'YT', 'Yukon Territory')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (79, 81, 'NDS', 'Niedersachsen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (80, 81, 'BAW', 'Baden-W&uuml;rttemberg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (81, 81, 'BAY', 'Bayern')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (82, 81, 'BER', 'Berlin')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (83, 81, 'BRG', 'Brandenburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (84, 81, 'BRE', 'Bremen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (85, 81, 'HAM', 'Hamburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (86, 81, 'HES', 'Hessen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (87, 81, 'MEC', 'Mecklenburg-Vorpommern')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (88, 81, 'NRW', 'Nordrhein-Westfalen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (89, 81, 'RHE', 'Rheinland-Pfalz')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (90, 81, 'SAR', 'Saarland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (91, 81, 'SAS', 'Sachsen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (92, 81, 'SAC', 'Sachsen-Anhalt')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (93, 81, 'SCN', 'Schleswig-Holstein')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (94, 81, 'THE', 'Th&uuml;ringen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (95, 14, 'WI', 'Wien')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (96, 14, 'NO', 'Nieder&ouml;sterreich')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (97, 14, 'OO', 'Ober&ouml;sterreich')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (98, 14, 'SB', 'Salzburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (99, 14, 'KN', 'K&auml;rnten')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (100, 14, 'ST', 'Steiermark')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (101, 14, 'TI', 'Tirol')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (102, 14, 'BL', 'Burgenland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (103, 14, 'VB', 'Voralberg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (104, 204, 'AG', 'Aargau')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (105, 204, 'AI', 'Appenzell Innerrhoden')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (106, 204, 'AR', 'Appenzell Ausserrhoden')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (107, 204, 'BE', 'Bern')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (108, 204, 'BL', 'Basel-Landschaft')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (109, 204, 'BS', 'Basel-Stadt')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (110, 204, 'FR', 'Freiburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (111, 204, 'GE', 'Genf')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (112, 204, 'GL', 'Glarus')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (113, 204, 'GR', 'Graub&uuml;nden')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (114, 204, 'JU', 'Jura')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (115, 204, 'LU', 'Luzern')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (116, 204, 'NE', 'Neuenburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (117, 204, 'NW', 'Nidwalden')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (118, 204, 'OW', 'Obwalden')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (119, 204, 'SG', 'St. Gallen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (120, 204, 'SH', 'Schaffhausen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (121, 204, 'SO', 'Solothurn')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (122, 204, 'SZ', 'Schwyz')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (123, 204, 'TG', 'Thurgau')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (124, 204, 'TI', 'Tessin')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (125, 204, 'UR', 'Uri')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (126, 204, 'VD', 'Waadt')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (127, 204, 'VS', 'Wallis')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (128, 204, 'ZG', 'Zug')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (129, 204, 'ZH', 'Z&uuml;rich')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (130, 195, 'A CoruÃ±a', 'A CoruÃ±a')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (131, 195, 'Alava', 'Alava')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (132, 195, 'Albacete', 'Albacete')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (133, 195, 'Alicante', 'Alicante')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (134, 195, 'Almeria', 'Almeria')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (135, 195, 'Asturias', 'Asturias')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (136, 195, 'Avila', 'Avila')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (137, 195, 'Badajoz', 'Badajoz')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (138, 195, 'Baleares', 'Baleares')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (139, 195, 'Barcelona', 'Barcelona')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (140, 195, 'Burgos', 'Burgos')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (141, 195, 'Caceres', 'Caceres')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (142, 195, 'Cadiz', 'Cadiz')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (143, 195, 'Cantabria', 'Cantabria')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (144, 195, 'Castellon', 'Castellon')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (145, 195, 'Ceuta', 'Ceuta')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (146, 195, 'Ciudad Real', 'Ciudad Real')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (147, 195, 'Cordoba', 'Cordoba')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (148, 195, 'Cuenca', 'Cuenca')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (149, 195, 'Girona', 'Girona')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (150, 195, 'Granada', 'Granada')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (151, 195, 'Guadalajara', 'Guadalajara')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (152, 195, 'Guipuzcoa', 'Guipuzcoa')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (153, 195, 'Huelva', 'Huelva')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (154, 195, 'Huesca', 'Huesca')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (155, 195, 'Jaen', 'Jaen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (156, 195, 'La Rioja', 'La Rioja')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (157, 195, 'Las Palmas', 'Las Palmas')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (158, 195, 'Leon', 'Leon')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (159, 195, 'Lleida', 'Lleida')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (160, 195, 'Lugo', 'Lugo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (161, 195, 'Madrid', 'Madrid')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (162, 195, 'Malaga', 'Malaga')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (163, 195, 'Melilla', 'Melilla')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (164, 195, 'Murcia', 'Murcia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (165, 195, 'Navarra', 'Navarra')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (166, 195, 'Ourense', 'Ourense')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (167, 195, 'Palencia', 'Palencia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (168, 195, 'Pontevedra', 'Pontevedra')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (169, 195, 'Salamanca', 'Salamanca')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (170, 195, 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (171, 195, 'Segovia', 'Segovia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (172, 195, 'Sevilla', 'Sevilla')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (173, 195, 'Soria', 'Soria')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (174, 195, 'Tarragona', 'Tarragona')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (175, 195, 'Teruel', 'Teruel')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (176, 195, 'Toledo', 'Toledo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (177, 195, 'Valencia', 'Valencia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (178, 195, 'Valladolid', 'Valladolid')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (179, 195, 'Vizcaya', 'Vizcaya')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (180, 195, 'Zamora', 'Zamora')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (181, 195, 'Zaragoza', 'Zaragoza')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (182, 103, '01', 'Carlow')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (183, 103, '02', 'Cavan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (184, 103, '03', 'Clare')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (185, 103, '04', 'Cork')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (186, 103, '05', 'Donegal')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (187, 103, '06', 'Dublin')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (188, 103, '07', 'Galway')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (189, 103, '08', 'Kerry')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (190, 103, '09', 'Kildare')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (191, 103, '10', 'Kilkenny')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (192, 103, '11', 'Laois')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (193, 103, '12', 'Leitrim')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (194, 103, '13', 'Limerick')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (195, 103, '14', 'Longford')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (196, 103, '15', 'Louth')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (197, 103, '16', 'Mayo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (198, 103, '17', 'Meath')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (199, 103, '18', 'Monaghan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (200, 103, '19', 'Offaly')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (201, 103, '20', 'Roscommon')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (202, 103, '21', 'Sligo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (203, 103, '22', 'Tipperary')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (204, 103, '23', 'Waterford')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (205, 103, '24', 'Westmeath')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (206, 103, '25', 'Wexford')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (207, 103, '26', 'Wicklow')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (208, 105, 'AG', 'Agrigento')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (209, 105, 'AL', 'Alessandria')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (210, 105, 'AN', 'Ancona')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (211, 105, 'AO', 'Aosta')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (212, 105, 'AR', 'Arezzo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (213, 105, 'AP', 'Ascoli Piceno')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (214, 105, 'AT', 'Asti')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (215, 105, 'AV', 'Avellino')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (216, 105, 'BA', 'Bari')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (217, 105, 'BL', 'Belluno')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (218, 105, 'BN', 'Benevento')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (219, 105, 'BG', 'Bergamo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (220, 105, 'BI', 'Biella')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (221, 105, 'BO', 'Bologna')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (222, 105, 'BZ', 'Bolzano')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (223, 105, 'BS', 'Brescia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (224, 105, 'BR', 'Brindisi')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (225, 105, 'CA', 'Cagliari')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (226, 105, 'CL', 'Caltanissetta')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (227, 105, 'CB', 'Campobasso')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (228, 105, 'CE', 'Caserta')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (229, 105, 'CT', 'Catania')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (230, 105, 'CZ', 'Catanzaro')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (231, 105, 'CH', 'Chieti')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (232, 105, 'CO', 'Como')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (233, 105, 'CS', 'Cosenza')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (234, 105, 'CR', 'Cremona')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (235, 105, 'KR', 'Crotone')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (236, 105, 'CN', 'Cuneo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (237, 105, 'EN', 'Enna')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (238, 105, 'FE', 'Ferrara')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (239, 105, 'FI', 'Firenze')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (240, 105, 'FG', 'Foggia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (241, 105, 'FO', 'Forlï¿½')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (242, 105, 'FR', 'Frosinone')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (243, 105, 'GE', 'Genova')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (244, 105, 'GO', 'Gorizia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (245, 105, 'GR', 'Grosseto')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (246, 105, 'IM', 'Imperia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (247, 105, 'IS', 'Isernia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (248, 105, 'AQ', 'Aquila')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (249, 105, 'SP', 'La Spezia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (250, 105, 'LT', 'Latina')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (251, 105, 'LE', 'Lecce')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (252, 105, 'LC', 'Lecco')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (253, 105, 'LI', 'Livorno')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (254, 105, 'LO', 'Lodi')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (255, 105, 'LU', 'Lucca')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (256, 105, 'MC', 'Macerata')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (257, 105, 'MN', 'Mantova')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (258, 105, 'MS', 'Massa-Carrara')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (259, 105, 'MT', 'Matera')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (260, 105, 'ME', 'Messina')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (261, 105, 'MI', 'Milano')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (262, 105, 'MO', 'Modena')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (263, 105, 'NA', 'Napoli')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (264, 105, 'NO', 'Novara')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (265, 105, 'NU', 'Nuoro')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (266, 105, 'OR', 'Oristano')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (267, 105, 'PD', 'Padova')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (268, 105, 'PA', 'Palermo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (269, 105, 'PR', 'Parma')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (270, 105, 'PG', 'Perugia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (271, 105, 'PV', 'Pavia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (272, 105, 'PS', 'Pesaro e Urbino')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (273, 105, 'PE', 'Pescara')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (274, 105, 'PC', 'Piacenza')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (275, 105, 'PI', 'Pisa')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (276, 105, 'PT', 'Pistoia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (277, 105, 'PN', 'Pordenone')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (278, 105, 'PZ', 'Potenza')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (279, 105, 'PO', 'Prato')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (280, 105, 'RG', 'Ragusa')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (281, 105, 'RA', 'Ravenna')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (282, 105, 'RC', 'Reggio di Calabria')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (283, 105, 'RE', 'Reggio Emilia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (284, 105, 'RI', 'Rieti')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (285, 105, 'RN', 'Rimini')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (286, 105, 'RM', 'Roma')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (287, 105, 'RO', 'Rovigo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (288, 105, 'SA', 'Salerno')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (289, 105, 'SS', 'Sassari')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (290, 105, 'SV', 'Savona')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (291, 105, 'SI', 'Siena')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (292, 105, 'SR', 'Siracusa')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (293, 105, 'SO', 'Sondrio')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (294, 105, 'TA', 'Taranto')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (295, 105, 'TE', 'Teramo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (296, 105, 'TR', 'Terni')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (297, 105, 'TO', 'Torino')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (298, 105, 'TP', 'Trapani')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (299, 105, 'TN', 'Trento')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (300, 105, 'TV', 'Treviso')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (301, 105, 'TS', 'Trieste')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (302, 105, 'UD', 'Udine')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (303, 105, 'VA', 'Varese')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (304, 105, 'VE', 'Venezia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (305, 105, 'VB', 'Verbania')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (306, 105, 'VC', 'Vercelli')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (307, 105, 'VR', 'Verona')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (308, 105, 'VV', 'Vibo Valentia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (309, 105, 'VI', 'Vicenza')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (310, 105, 'VT', 'Viterbo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (311, 150, 'Drenthe', 'Drenthe')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (312, 150, 'Flevoland', 'Flevoland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (313, 150, 'Friesland', 'Friesland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (314, 150, 'Gelderland', 'Gelderland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (315, 150, 'Groningen', 'Groningen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (316, 150, 'Limburg', 'Limburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (317, 150, 'Noord-Brabant', 'Noord-Brabant')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (318, 150, 'Noord-Holland', 'Noord-Holland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (319, 150, 'Overijssel', 'Overijssel')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (320, 150, 'Utrecht', 'Utrecht')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (321, 150, 'Zeeland', 'Zeeland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (322, 150, 'Zuid_Holland', 'Zuid_Holland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (323, 222, 'ALD', 'Alderney')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (324, 222, 'ATM', 'County Antrim')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (325, 222, 'ARM', 'County Armagh')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (326, 222, 'AVN', 'Avon')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (327, 222, 'BFD', 'Bedfordshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (328, 222, 'BRK', 'Berkshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (329, 222, 'BDS', 'Borders')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (330, 222, 'BUX', 'Buckinghamshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (331, 222, 'CBE', 'Cambridgeshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (332, 222, 'CTR', 'Central')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (333, 222, 'CHS', 'Cheshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (334, 222, 'CVE', 'Cleveland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (335, 222, 'CLD', 'Clwyd')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (336, 222, 'CNL', 'Cornwall')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (337, 222, 'CBA', 'Cumbria')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (338, 222, 'DYS', 'Derbyshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (339, 222, 'DVN', 'Devon')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (340, 222, 'DOR', 'Dorse')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (341, 222, 'DWN', 'County Down')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (342, 222, 'DGL', 'Dumfries and Galloway')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (343, 222, 'DHM', 'County Durham')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (344, 222, 'DFD', 'Dyfed')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (345, 222, 'ESX', 'Essex')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (346, 222, 'FMH', 'County Fermanagh')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (347, 222, 'FFE', 'Fife')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (348, 222, 'GNM', 'Mid Glamorgan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (349, 222, 'GNS', 'South Glamorgan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (350, 222, 'GNW', 'West Glamorgan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (351, 222, 'GLR', 'Gloucester')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (352, 222, 'GRN', 'Grampian')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (353, 222, 'GUR', 'Guernsey')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (354, 222, 'GWT', 'Gwent')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (355, 222, 'GDD', 'Gwynedd')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (356, 222, 'HPH', 'Hampshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (357, 222, 'HWR', 'Hereford and Worcester')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (358, 222, 'HFD', 'Hertfordshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (359, 222, 'HLD', 'Highlands')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (360, 222, 'HBS', 'Humberside')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (361, 222, 'IOM', 'Isle of Man')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (362, 222, 'IOW', 'Isle of Wight')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (363, 222, 'JER', 'Jersey')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (364, 222, 'KNT', 'Kent')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (365, 222, 'LNH', 'Lancashire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (366, 222, 'LEC', 'Leicestershire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (367, 222, 'LCN', 'Lincolnshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (368, 222, 'LDN', 'Greater London')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (369, 222, 'LDR', 'County Londonderry')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (370, 222, 'LTH', 'Lothian')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (371, 222, 'MCH', 'Greater Manchester')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (372, 222, 'MSY', 'Merseyside')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (373, 222, 'NOR', 'Norfolk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (374, 222, 'NHM', 'Northamptonshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (375, 222, 'NLD', 'Northumberland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (376, 222, 'NOT', 'Nottinghamshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (377, 222, 'ORK', 'Orkney')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (378, 222, 'OFE', 'Oxfordshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (379, 222, 'PWS', 'Powys')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (380, 222, 'SPE', 'Shropshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (381, 222, 'SRK', 'Sark')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (382, 222, 'SLD', 'Shetland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (383, 222, 'SOM', 'Somerset')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (384, 222, 'SFD', 'Staffordshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (385, 222, 'SCD', 'Strathclyde')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (386, 222, 'SFK', 'Suffolk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (387, 222, 'SRY', 'Surrey')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (388, 222, 'SXE', 'East Sussex')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (389, 222, 'SXW', 'West Sussex')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (390, 222, 'TYS', 'Tayside')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (391, 222, 'TWR', 'Tyne and Wear')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (392, 222, 'TYR', 'County Tyrone')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (393, 222, 'WKS', 'Warwickshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (394, 222, 'WIL', 'Western Isles')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (395, 222, 'WMD', 'West Midlands')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (396, 222, 'WLT', 'Wiltshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (397, 222, 'YSN', 'North Yorkshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (398, 222, 'YSS', 'South Yorkshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name) VALUES (399, 222, 'YSW', 'West Yorkshire')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Belgium
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('400',21,'AN','Antwerpen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('401',21,'BW','Brabant Wallon')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('402',21,'HA','Hainaut')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('403',21,'LG','Liege')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('404',21,'LM','Limburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('405',21,'LX','Luxembourg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('406',21,'NM','Namur')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('407',21,'OV','Oost-Vlaanderen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('408',21,'VB','Vlaams Brabant')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('409',21,'WV','West-Vlaanderen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Denmark
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('410',57,'AR','Arhus')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('420',57,'BO','Bornholm')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('421',57,'FR','Frederiksborg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('422',57,'FY','Fyn')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('423',57,'KO','Kobenhavn')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('424',57,'NO','Nordjylland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('425',57,'RI','Ribe')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('426',57,'RK','Ringkobing')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('427',57,'RO','Roskilde')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('428',57,'SO','Sonderjylland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('429',57,'ST','Storstrom')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('430',57,'VE','Vejle')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('431',57,'VJ','VestjÃ¦lland')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('432',57,'VI','Viborg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Greece
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('433',84,'AI','Aitolia kai Akarnania')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('434',84,'AK','Akhaia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('435',84,'AG','Argolis')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('436',84,'AD','Arkadhia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('437',84,'AR','Arta')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('438',84,'AT','Attiki')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('439',84,'AY','Ayion Oros (Mt. Athos)')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('440',84,'DH','Dhodhekanisos')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('441',84,'DR','Drama')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('442',84,'ET','Evritania')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('443',84,'ES','Evros')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('444',84,'EV','Evvoia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('445',84,'FL','Florina')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('446',84,'FO','Fokis')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('447',84,'FT','Fthiotis')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('448',84,'GR','Grevena')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('449',84,'IL','Ilia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('450',84,'IM','Imathia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('451',84,'IO','Ioannina')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('452',84,'IR','Irakleion')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('453',84,'KA','Kardhitsa')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('454',84,'KS','Kastoria')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('455',84,'KV','Kavala')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('456',84,'KE','Kefallinia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('457',84,'KR','Kerkyra')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('458',84,'KH','Khalkidhiki')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('459',84,'KN','Khania')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('460',84,'KI','Khios')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('461',84,'KK','Kikladhes')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('462',84,'KL','Kilkis')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('463',84,'KO','Korinthia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('464',84,'KZ','Kozani')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('465',84,'LA','Lakonia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('466',84,'LR','Larisa')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('467',84,'LS','Lasithi')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('468',84,'LE','Lesvos')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('469',84,'LV','Levkas')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('470',84,'MA','Magnisia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('471',84,'ME','Messinia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('472',84,'PE','Pella')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('473',84,'PI','Pieria')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('474',84,'PR','Preveza')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('475',84,'RE','Rethimni')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('476',84,'RO','Rodhopi')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('477',84,'SA','Samos')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('478',84,'SE','Serrai')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('479',84,'TH','Thesprotia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('480',84,'TS','Thessaloniki')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('481',84,'TR','Trikala')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('482',84,'VO','Voiotia')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('483',84,'XA','Xanthi')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('484',84,'ZA','Zakinthos')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Luxembourg
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('485',124,'DI','Diekirch')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('486',124,'GR','Grevenmacher')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('487',124,'LU','Luxembourg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Poland
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('488',170,'DO','Dolnoslaskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('489',170,'KM','Kujawsko-Pomorskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('490',170,'LO','Lodzkie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('491',170,'LE','Lubelskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('492',170,'LU','Lubuskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('493',170,'ML','Malopolskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('494',170,'MZ','Mazowieckie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('495',170,'OP','Opolskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('496',170,'PK','Podkarpackie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('497',170,'PL','Podlaskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('498',170,'PM','Pomorskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('499',170,'SL','Slaskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('500',170,'SW','Swietokrzyskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('501',170,'WM','Warminsko-Mazurskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('502',170,'WI','Wielkopolskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('503',170,'ZA','Zachodniopomorskie')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");

#Portugal
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('504',171,'AC','Acores (Azores)')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('505',171,'AV','Aveiro')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('506',171,'BE','Beja')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('507',171,'BR','Braga')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('508',171,'BA','Braganca')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('509',171,'CB','Castelo Branco')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('510',171,'CO','Coimbra')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('511',171,'EV','Evora')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('512',171,'FA','Faro')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('513',171,'GU','Guarda')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('514',171,'LE','Leiria')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('516',171,'LI','Lisboa')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('517',171,'ME','Madeira')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('518',171,'PO','Portalegre')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('519',171,'PR','Porto')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('520',171,'SA','Santarem')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('521',171,'SE','Setubal')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('522',171,'VC','Viana do Castelo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('523',171,'VR','Vila Real')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('524',171,'VI','Viseu')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");


#Russian Federation
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('525',176,'AB','Abakan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('526',176,'AG','Aginskoye')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('527',176,'AN','Anadyr')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('528',176,'AR','Arkahangelsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('529',176,'AS','Astrakhan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('530',176,'BA','Barnaul')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('531',176,'BE','Belgorod')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('532',176,'BI','Birobidzhan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('533',176,'BL','Blagoveshchensk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('534',176,'BR','Bryansk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('535',176,'CH','Cheboksary')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('536',176,'CL','Chelyabinsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('537',176,'CR','Cherkessk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('538',176,'CI','Chita')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('539',176,'DU','Dudinka')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('540',176,'EL','Elista')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('541',176,'GO','Gomo-Altaysk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('542',176,'GA','Gorno-Altaysk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('543',176,'GR','Groznyy')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('544',176,'IR','Irkutsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('545',176,'IV','Ivanovo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('546',176,'IZ','Izhevsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('547',176,'KA','Kalinigrad')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('548',176,'KL','Kaluga')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('549',176,'KS','Kasnodar')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('550',176,'KZ','Kazan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('551',176,'KE','Kemerovo')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('552',176,'KH','Khabarovsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('553',176,'KM','Khanty-Mansiysk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('554',176,'KO','Kostroma')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('555',176,'KR','Krasnodar')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('556',176,'KN','Krasnoyarsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('557',176,'KU','Kudymkar')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('558',176,'KG','Kurgan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('559',176,'KK','Kursk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('560',176,'KY','Kyzyl')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('561',176,'LI','Lipetsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('562',176,'MA','Magadan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('563',176,'MK','Makhachkala')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('564',176,'MY','Maykop')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('565',176,'MO','Moscow')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('566',176,'MU','Murmansk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('567',176,'NA','Nalchik')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('568',176,'NR','Naryan Mar')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('569',176,'NZ','Nazran')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('570',176,'NI','Nizhniy Novgorod')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('571',176,'NO','Novgorod')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('572',176,'NV','Novosibirsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('573',176,'OM','Omsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('574',176,'OR','Orel')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('575',176,'OE','Orenburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('576',176,'PA','Palana')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('577',176,'PE','Penza')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('578',176,'PR','Perm')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('579',176,'PK','Petropavlovsk-Kamchatskiy')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('580',176,'PT','Petrozavodsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('581',176,'PS','Pskov')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('582',176,'RO','Rostov-na-Donu')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('583',176,'RY','Ryazan')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('584',176,'SL','Salekhard')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('585',176,'SA','Samara')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('586',176,'SR','Saransk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('587',176,'SV','Saratov')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('588',176,'SM','Smolensk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('589',176,'SP','St. Petersburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('590',176,'ST','Stavropol')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('591',176,'SY','Syktyvkar')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('592',176,'TA','Tambov')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('593',176,'TO','Tomsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('594',176,'TU','Tula')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('595',176,'TR','Tura')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('596',176,'TV','Tver')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('597',176,'TY','Tyumen')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('598',176,'UF','Ufa')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('599',176,'UL','Ul\'yanovsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('600',176,'UU','Ulan-Ude')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('601',176,'US','Ust\'-Ordynskiy')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('602',176,'VL','Vladikavkaz')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('603',176,'VA','Vladimir')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('604',176,'VV','Vladivostok')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('605',176,'VG','Volgograd')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('606',176,'VD','Vologda')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('607',176,'VO','Voronezh')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('608',176,'VY','Vyatka')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('609',176,'YA','Yakutsk')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('610',176,'YR','Yaroslavl')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('611',176,'YE','Yekaterinburg')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones (zone_id, zone_country_id, zone_code, zone_name ) VALUES ('612',176,'YO','Yoshkar-Ola')") or die ("<b>".NOTUPDATED . $prefix_table . "zones</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "zones " . UPDATED .'</font>';


$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (1, 14, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (2, 21, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (3, 57, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (4, 72, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (5, 73, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (6, 81, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (7, 84, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (8, 103, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (9, 105, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (10, 124, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (11, 150, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (12, 171, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (13, 195, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (14, 203, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "zones_to_geo_zones (association_id, zone_country_id, zone_id, geo_zone_id, last_modified, date_added) VALUES (15, 222, 0, 1, NULL, " . $db->DBTimeStamp($today) . ")") or die ("<b>".NOTUPDATED . $prefix_table . "zones_to_geo_zones</b>");

echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "zones_to_geo_zones " . UPDATED .'</font>';

?>