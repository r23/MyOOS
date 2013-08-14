<?php
/* ----------------------------------------------------------------------
   $Id: customers.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers.php,v 1.15 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$smarty->assign('heading_customers', oos_href_link_admin($aFilename['customers'], 'selected_box=customers'));  

$smarty->assign('customers', oos_admin_files_boxes('customers', 'selected_box=customers', BOX_CUSTOMERS_CUSTOMERS));
$smarty->assign('orders', oos_admin_files_boxes('orders', 'selected_box=customers', BOX_CUSTOMERS_ORDERS));
$smarty->assign('customers_status', oos_admin_files_boxes('customers_status', 'selected_box=customers', BOX_LOCALIZATION_CUSTOMERS_STATUS));
$smarty->assign('orders_status', oos_admin_files_boxes('orders_status', 'selected_box=customers', BOX_LOCALIZATION_ORDERS_STATUS));
$smarty->assign('campaigns', oos_admin_files_boxes('campaigns', 'selected_box=customers', BOX_CAMPAIGNS));
$smarty->assign('manual_loging', oos_admin_files_boxes('manual_loging', 'selected_box=customers', BOX_ADMIN_LOGIN));

