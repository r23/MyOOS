<?php
/* ----------------------------------------------------------------------
   $Id: tools.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: tools.php,v 1.20 2002/03/16 00:20:11 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$smarty->assign('heading_tools', oos_href_link_admin($aFilename['recover_cart_sales'], 'selected_box=tools'));  

// Todo: $smarty->assign('mysqldumper', '<a href="'/mysqldumper/index.php' . '" >' . BOX_TOOLS_BACKUP . '</a>' .
$smarty->assign('mail', oos_admin_files_boxes('mail', 'selected_box=tools', BOX_TOOLS_MAIL));
$smarty->assign('newsletters', oos_admin_files_boxes('newsletters', 'selected_box=tools', BOX_TOOLS_NEWSLETTER_MANAGER));
$smarty->assign('whos_online', oos_admin_files_boxes('whos_online', 'selected_box=tools', BOX_TOOLS_WHOS_ONLINE));
$smarty->assign('recover_cart_sales', oos_admin_files_boxes('recover_cart_sales', 'selected_box=tools', BOX_TOOLS_RECOVER_CART));

