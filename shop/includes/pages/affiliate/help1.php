<?php
/* ----------------------------------------------------------------------
   $Id: help1.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_help1.php,v 1.4 2003/02/17 17:21:11 harley_vb 
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  $_SESSION['navigation']->remove_current_page();

  $aOption['popup_help'] = $sTheme . '/modules/affiliate_popup_help.html';

  //smarty
  require 'includes/classes/class_template.php';
  $oSmarty = new Template;

  $oSmarty->caching = true;
  $help_cache_id = $sTheme . '|help|1|' . $sLanguage;

  if (!$oSmarty->is_cached($aOption['popup_help'], $help_cache_id )) {
    require 'includes/languages/' . $sLanguage . '/affiliate_summary.php';


    // assign Smarty variables;
    $oSmarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);
    $oSmarty->assign('lang', $aLang);
    $oSmarty->assign('affiliate_help_text', $aLang['text_impressions_help']);
    $oSmarty->assign('theme_image', 'themes/' . $sTheme . '/images');
    $oSmarty->assign('theme_css', 'themes/' . $sTheme);
  }

  // display the template
  $oSmarty->display($aOption['popup_help'], $help_cache_id);
?>
