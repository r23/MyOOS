<?php
/* ----------------------------------------------------------------------
   $Id: popup_search_help.php 407 2013-06-11 14:57:53Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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


  $aOption['popup_help'] = $sTheme . '/system/popup_help.tpl';

  //smarty
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
  $oSmarty = new myOOS_Smarty;

  $oSmarty->setCaching(true);
  $help_cache_id = $sTheme . '|popup|search|' . $sLanguage;

  if (!$oSmarty->isCached($aOption['popup_help'], $help_cache_id )) {
    require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/search_advanced.php';

    // assign Smarty variables;
    $oSmarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);
    $oSmarty->assign('lang', $aLang);
    $oSmarty->assign('heading_titel', $aLang['heading_search_help']);
    $oSmarty->assign('help_text', $aLang['text_search_help']);
    $oSmarty->assign('theme_image', 'themes/' . $sTheme . '/images');
    $oSmarty->assign('theme_css', 'themes/' . $sTheme);
  }

// display the template
  $oSmarty->display($aOption['popup_help'], $help_cache_id);

