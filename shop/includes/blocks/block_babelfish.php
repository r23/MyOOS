<?php
/* ----------------------------------------------------------------------
   $Id: block_babelfish.php,v 1.1 2007/06/07 11:55:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Babelfish mod v2.0a

   Babelfish is a registered trademark of Altavista.

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!isset($block_get_parameters)) {
    $block_get_parameters = oos_get_all_get_parameters(array('action'));
    $block_get_parameters = oos_remove_trailing($block_get_parameters);
    $oSmarty->assign('get_params', $block_get_parameters);
  }
  $oSmarty->assign('block_heading_babelfish', $block_heading);

?>
