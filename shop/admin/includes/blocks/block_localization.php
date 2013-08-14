<?php
/* ----------------------------------------------------------------------
   $Id: localization.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: localization.php,v 1.15 2002/03/16 00:20:11 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$smarty->assign('heading_currencies', oos_href_link_admin($aFilename['currencies'], 'selected_box=localization')); 

$smarty->assign('currencies', oos_admin_files_boxes('currencies', 'selected_box=localization', BOX_LOCALIZATION_CURRENCIES));
$smarty->assign('languages', oos_admin_files_boxes('languages', 'selected_box=localization', BOX_LOCALIZATION_LANGUAGES));

