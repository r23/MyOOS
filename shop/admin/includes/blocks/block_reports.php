<?php
/* ----------------------------------------------------------------------
   $Id: reports.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: reports.php,v 1.4 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$smarty->assign('heading_reports', oos_href_link_admin($aFilename['admin_members'], 'selected_box=reports'));  

$smarty->assign('stats_products_viewed', oos_admin_files_boxes('stats_products_viewed', 'selected_box=reports', BOX_REPORTS_PRODUCTS_VIEWED));
$smarty->assign('stats_products_purchased', oos_admin_files_boxes('stats_products_purchased', 'selected_box=reports', BOX_REPORTS_PRODUCTS_PURCHASED));
$smarty->assign('stats_low_stock', oos_admin_files_boxes('stats_low_stock', 'selected_box=reports', BOX_REPORTS_STOCK_LEVEL));
$smarty->assign('stats_customers', oos_admin_files_boxes('stats_customers', 'selected_box=reports', BOX_REPORTS_ORDERS_TOTAL));
$smarty->assign('stats_sales_report2', oos_admin_files_boxes('stats_sales_report2', 'selected_box=reports', BOX_REPORTS_SALES_REPORT2));
$smarty->assign('stats_recover_cart_sales', oos_admin_files_boxes('stats_recover_cart_sales', 'selected_box=reports', BOX_REPORTS_RECOVER_CART_SALES));

