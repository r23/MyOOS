<?php
/* ----------------------------------------------------------------------
   $Id: block_add_a_quickie.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: add_a_quickie.php,v 1.10 2001/12/19 01:37:55 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!isset($block_get_parameters)) {
    $block_get_parameters = oos_get_all_get_parameters(array('action'));
    $block_get_parameters = oos_remove_trailing($block_get_parameters);
    $smarty->assign('get_params', $block_get_parameters);
  }

  $smarty->assign('block_heading_add_product_id', $block_heading);

?>
