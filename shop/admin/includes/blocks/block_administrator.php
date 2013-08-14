<?php
/* ----------------------------------------------------------------------
   $Id: administrator.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: administrator.php,v 1.20 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$smarty->assign('heading_administrator', oos_href_link_admin($aFilename['admin_members'], 'selected_box=administrator'));  

$smarty->assign('admin_members', oos_admin_files_boxes('admin_members', 'selected_box=administrator', BOX_ADMINISTRATOR_MEMBERS));
$smarty->assign('admin_files', oos_admin_files_boxes('admin_files', 'selected_box=administrator', BOX_ADMINISTRATOR_BOXES));
