<?php
/* ----------------------------------------------------------------------
   $Id: oos_display.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (isset($option) && ($option == 'print')) {
    $oSmarty->display('default/print.html');
  } else {
    if ($oEvent->installed_plugin('banner')) {
      if ($banner = oos_banner_exists('dynamic', '468x60')) {
        $oos_banner = oos_display_banner('static', $banner);
        $oSmarty->assign('oos_banner', $oos_banner);
      }
    }

// load_filter
//    $oSmarty->load_filter('output', 'png_image');
//    $oSmarty->load_filter('output', 'highlight');
//    $oSmarty->load_filter('output', 'trimwhitespace');

    $oSmarty->display($sTheme.'/theme.html');
  }

?>
