<?php
/* ----------------------------------------------------------------------
   $Id: info_shopping_cart.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: info_shopping_cart.php,v 1.19 2003/02/13 03:01:48 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  $aTemplate['info_shopping_cart'] = $sTheme . '/system/info_shopping_cart.html';

  //smarty
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
  $oSmarty =& new Template;

  $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
  $info_shopping_cart_id = $sTheme . '|info_shopping_cart|' . $sLanguage;

  if (!$smarty->isCached($aTemplate['info_shopping_cart'], $info_shopping_cart_id )) {
    require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/main_info_shopping_cart.php';

    // assign Smarty variables;
    $smarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);
    $smarty->assign('lang', $aLang);
    $smarty->assign('theme_image', 'themes/' . $sTheme . '/images');
    $smarty->assign('theme_css', 'themes/' . $sTheme);
  }

  // display the template
  $smarty->display($aTemplate['info_shopping_cart'], $info_shopping_cart_id);

