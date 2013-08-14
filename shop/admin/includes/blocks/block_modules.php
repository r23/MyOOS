<?php
/* ----------------------------------------------------------------------
   $Id: modules.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: modules.php,v 1.15 2002/04/03 23:25:41 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$smarty->assign('heading_modules', oos_href_link_admin($aFilename['modules'], 'set=payment&amp;selected_box=modules'));  

$smarty->assign('payment', '<a href="' . oos_href_link_admin($aFilename['modules'], 'set=payment&amp;selected_box=modules', 'NONSSL') . '" title="' . BOX_MODULES_PAYMENT . '">' . BOX_MODULES_PAYMENT . '</a>');
$smarty->assign('shipping', '<a href="' . oos_href_link_admin($aFilename['modules'], 'set=shipping&amp;selected_box=modules', 'NONSSL') . '" title="' . BOX_MODULES_SHIPPING . '">' . BOX_MODULES_SHIPPING . '</a>');
$smarty->assign('ordertotal', '<a href="' . oos_href_link_admin($aFilename['modules'], 'set=ordertotal&amp;selected_box=modules', 'NONSSL') . '" title="' . BOX_MODULES_ORDER_TOTAL . '">' . BOX_MODULES_ORDER_TOTAL . '</a>');

