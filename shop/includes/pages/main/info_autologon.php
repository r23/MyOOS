<?php
/* ----------------------------------------------------------------------
   $Id: info_autologon.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: info_autologon.php,v 1.01 2002/10/08 12:00:00
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 osCommerce
   Copyright (c) 2002 HMCservices
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  $_SESSION['navigation']->remove_current_page();

  $aOption['info_autologon'] = $sTheme . '/system/info_autologon.html';

  //smarty
  require 'includes/classes/class_template.php';
  $oSmarty =& new Template;

  $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
  $info_autologon_id = $sTheme . '|info_autologon|' . $sLanguage;

  if (!$smarty->isCached($aOption['info_autologon'], $info_autologon_id )) {
    require 'includes/languages/' . $sLanguage . '/main_info_autologon.php';

    // assign Smarty variables;
    $smarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);
    $smarty->assign('lang', $aLang);
    $smarty->assign('theme_image', 'themes/' . $sTheme . '/images');
    $smarty->assign('theme_css', 'themes/' . $sTheme);
  }

  // display the template
  $smarty->display($aOption['info_autologon'], $info_autologon_id);
?>
