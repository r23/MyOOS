<?php
/* ----------------------------------------------------------------------
   $Id: block_translate_google.php,v 1.1 2007/06/07 11:55:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Google Infobox Translator Contribution 1.3 Dec29 2004.

   By: Apisith Chawla
   Email: apisith@gmail.com

   Tweaked By: David Hanwell
   eMail: webmaster@linemanhut.co.uk

   Variable pass through By: Tom St.Croix
   eMail: management@betterthannature.com

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
  $oSmarty->assign('block_heading_translate_google', $block_heading);

?>
