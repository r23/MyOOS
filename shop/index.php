<?php
/* ----------------------------------------------------------------------
   $Id: index.php,v 1.3 2008/08/15 16:44:22 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';


  $sMp = oos_var_prep_for_os($sMp);
  $sFile = oos_var_prep_for_os($sFile);
  $sLanguage = oos_var_prep_for_os($_SESSION['language']);
  $sTheme = oos_var_prep_for_os($_SESSION['theme']);

  if (file_exists('includes/pages/' . $sMp . '/' . $sFile . '.php')) {
    if (isset($_GET['history_back'])){
      $_SESSION['navigation']->remove_last_page();
    } else {
      $_SESSION['navigation']->add_current_page();
    }

    include 'includes/pages/' . $sMp . '/' . $sFile . '.php';

  } else {
    // Module not found
    if (SEND_404_ERROR == 'true') {
      switch (REPORTLEVEL_404) {
        case 1:
          if (eregi(oos_server_get_var('HTTP_HOST'), oos_server_get_var('HTTP_REFERER'))) {
            oos_error_reporting_mail();
          }
          break;

        case 2:
          oos_error_reporting_mail();
          break;
      }
    }

    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));

  }

  include 'includes/oos_nice_exit.php';

?>
