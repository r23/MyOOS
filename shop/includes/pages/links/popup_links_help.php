<?php
/* ----------------------------------------------------------------------
   $Id: popup_links_help.php,v 1.1 2007/06/07 16:47:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: popup_links_help.php,v 1.00 2003/10/03 
   ----------------------------------------------------------------------
   Links Manager

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  $_SESSION['navigation']->remove_current_page();

  $aOption['popup_help'] = $sTheme . '/system/popup_help.html';

  //smarty
  require 'includes/classes/class_template.php';
  $oSmarty =& new Template;

  $oSmarty->caching = true;
  $help_cache_id = $sTheme . '|popup|links_help|' . $sLanguage;

  if (!$oSmarty->is_cached($aOption['popup_help'], $help_cache_id )) {
    require 'includes/languages/' . $sLanguage . '/links_submit.php';

    // assign Smarty variables;
    $oSmarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);
    $oSmarty->assign('lang', $aLang);
    $oSmarty->assign('heading_titel', $aLang['heading_links_help']);
    $oSmarty->assign('help_text', $aLang['text_links_help']);
    $oSmarty->assign('theme_image', 'themes/' . $sTheme . '/images');
    $oSmarty->assign('theme_css', 'themes/' . $sTheme);
  }

  // display the template
  $oSmarty->display($aOption['popup_help'], $help_cache_id);
?>
