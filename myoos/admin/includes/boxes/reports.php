<?php
/**
   ----------------------------------------------------------------------
   $Id: reports.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: reports.php,v 1.4 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
$bActive = ($_SESSION['selected_box'] == 'reports') ? true : false;

$aBlocks[] = ['heading' => BOX_HEADING_REPORTS, 'link' => oos_href_link_admin(basename($php_self), oos_get_all_get_params(['selected_box']) . 'selected_box=reports'), 'icon' => 'fas fa-chart-bar', 'active' => $bActive, 'contents' => [['code' => $aContents['stats_products_purchased'], 'title' => BOX_REPORTS_PRODUCTS_PURCHASED, 'link' => oos_admin_files_boxes('stats_products_purchased', 'selected_box=reports')], ['code' => $aContents['stats_products_viewed'], 'title' => BOX_REPORTS_PRODUCTS_VIEWED, 'link' =>  oos_admin_files_boxes('stats_products_viewed', 'selected_box=reports')], ['code' => $aContents['stats_low_stock'], 'title' => BOX_REPORTS_STOCK_LEVEL, 'link' => oos_admin_files_boxes('stats_low_stock', 'selected_box=reports')], ['code' => $aContents['stats_customers'], 'title' => BOX_REPORTS_ORDERS_TOTAL, 'link' => oos_admin_files_boxes('stats_customers', 'selected_box=reports')], ['code' => $aContents['stats_sales_report2'], 'title' => BOX_REPORTS_SALES_REPORT2, 'link' => oos_admin_files_boxes('stats_sales_report2', 'selected_box=reports')]]];
