<?php
/* ----------------------------------------------------------------------
   $Id: taxes.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: taxes.php,v 1.16 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$smarty->assign('heading_taxes', oos_href_link_admin($aFilename['countries'], 'selected_box=taxes'));  

$smarty->assign('countries', oos_admin_files_boxes('countries', 'selected_box=taxes', BOX_TAXES_COUNTRIES));
$smarty->assign('zones', oos_admin_files_boxes('zones', 'selected_box=taxes', BOX_TAXES_ZONES));
$smarty->assign('geo_zones', oos_admin_files_boxes('geo_zones', 'selected_box=taxes', BOX_TAXES_GEO_ZONES));
$smarty->assign('tax_classes', oos_admin_files_boxes('tax_classes', 'selected_box=taxes', BOX_TAXES_TAX_CLASSES));
$smarty->assign('tax_rates', oos_admin_files_boxes('tax_rates', 'selected_box=taxes', BOX_TAXES_TAX_RATES));

