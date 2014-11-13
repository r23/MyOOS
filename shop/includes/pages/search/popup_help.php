<?php
/* ----------------------------------------------------------------------
   $Id: popup_help.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: popup_search_help.php,v 1.3 2003/02/13 03:10:56 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


  $aOption['popup_help'] = $sTheme . '/system/popup_help.html';

  //smarty
  require 'includes/classes/class_template.php';
  $oSmarty = new Template;

  $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
  $help_cache_id = $sTheme . '|popup|search|' . $sLanguage;

  if (!$smarty->isCached($aOption['popup_help'], $help_cache_id )) {
    require 'includes/languages/' . $sLanguage . '/search_advanced.php';

    // assign Smarty variables;
    $smarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);
    $smarty->assign('lang', $aLang);
    $smarty->assign('heading_titel', $aLang['heading_search_help']);
    $smarty->assign('help_text', $aLang['text_search_help']);
    $smarty->assign('theme_image', 'themes/' . $sTheme . '/images');
    $smarty->assign('theme_css', 'themes/' . $sTheme);
  }

  // display the template
  $smarty->display($aOption['popup_help'], $help_cache_id);

