<?php
/* ----------------------------------------------------------------------
   $Id: oos_system.php,v 1.2 2007/11/21 00:42:05 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  //smarty
  require 'includes/classes/class_template.php';
  $oSmarty =& new Template;

  /**
   * Smarty Cache Handler
   * utilizing eAccelerator extension (http://eaccelerator.net/HomeUk)
   */
  if (function_exists( 'eaccelerator' )) {
    $oSmarty->cache_handler_func = 'smarty_cache_eaccelerator';
  }


  //debug
  if ($debug == 'true') {
    $oSmarty->force_compile   = true;
    $oSmarty->debugging       = true;

/*
    $oSmarty->clear_all_cache();
    $oSmarty->clear_compiled_tpl();
*/
  }

  // object register
  $oSmarty->register_object("cart", $_SESSION['cart'],array('count_contents', 'get_products'));
  $oSmarty->assign_by_ref("oEvent", $oEvent);


  // cache_id
  $oos_cache_id                   = $sTheme . '|block|' . $sLanguage;
  $oos_system_cache_id            = $sTheme . '|block|' . $sLanguage;
  $oos_categories_cache_id        = $sTheme . '|block|categories|' . $sLanguage . '|' . $cPath;
  $oos_modules_cache_id           = $sTheme . '|modules|' . $sLanguage . '|' . $_SESSION['currency'];
  $oos_news_cache_id              = $sTheme . '|modules|news|' . $sLanguage;

  if (isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id'])) {
    $nManufacturersId = intval($_GET['manufacturers_id']);
  } else {
    $nManufacturersId  = 0;
  }
  $oos_manufacturers_cache_id     = $sTheme . '|block|manufacturers|' . $sLanguage . '|' . $nManufacturersId;
  $oos_manufacturer_info_cache_id = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . $nManufacturersId;

  if (isset($_GET['products_id'])) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
    $oos_manufacturer_info_cache_id = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . intval($nProductsId);
    $oos_products_info_cache_id     = $sTheme . '|products_info|' . $sLanguage . '|' . intval($nProductsId);
    $oos_xsell_products_cache_id    = $sTheme . '|block|products|' . $sLanguage . '|' . intval($nProductsId);
  }

  // Meta-Tags
  if (empty($oos_pagetitle)) $oos_pagetitle = OOS_META_TITLE;
  if (empty($oos_meta_description)) $oos_meta_description = OOS_META_DESCRIPTION;
  if (empty($oos_meta_keywords)) $oos_meta_keywords = OOS_META_KEYWORDS;

  $oSmarty->assign(
      array(
          'filename'          => $aFilename,
          'modules'           => $aModules,
          'main_page'         => $sMp,
          'page_file'         => $sFile,

          'request_type'      => $request_type,

          'theme_set'         => $sTheme,
          'theme_image'       => 'themes/' . $sTheme . '/images',
          'theme_css'         => 'themes/' . $sTheme,

          'lang'              => $aLang,
          'language'          => $sLanguage,

          'pangv'             => $sPAngV,
          'products_units'    => $products_units,

          'oos_session_name'  => oos_session_name(),
          'oos_session_id'    => oos_session_id(),

          'pagetitle'         => $oos_pagetitle,

          'meta_description'  => $oos_meta_description,
          'meta_keywords'     => $oos_meta_keywords
      )
  );

  $oSmarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);

  // shopping_cart
  $cart_show_total = 0;
  $cart_count_contents = $_SESSION['cart']->count_contents();
  if ($cart_count_contents > 0) {
    $cart_show_total = $oCurrencies->format($_SESSION['cart']->show_total());
  }
  $oSmarty->assign('cart_show_total', $cart_show_total);
  $oSmarty->assign('cart_count_contents', $cart_count_contents);

?>
