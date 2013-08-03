<?php
/* ----------------------------------------------------------------------
   $Id: cross_sell.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being required by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (isset($_GET['products_id'])) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
  } else {
    oos_redirect(oos_href_link($aContents['main']));
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/products_cross_sell.php';

  $productstable = $oostable['products'];
  $products_descriptiontable = $oostable['products_description'];
  $product_info_sql = "SELECT p.products_id, pd.products_name, p.products_model
                        FROM $productstable p,
                             $products_descriptiontable pd
                        WHERE p.products_status >= '1'
                          AND p.products_id = '" . intval($nProductsId) . "'
                          AND pd.products_id = p.products_id
                          AND pd.products_languages_id = '" . intval($nLanguageID) . "'";
  $product_info_result = $dbconn->Execute($product_info_sql);

  if (!$product_info_result->RecordCount()) {

    // product not found
    $aLang['text_information'] = $aLang['text_product_not_found'];

    $aTemplate['page'] = $sTheme . '/system/info.tpl';

    $nPageType =OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    // assign Smarty variables;
    $smarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['text_product_not_found'],
            'oos_heading_image' => 'specials.gif'
        )
    );

  } else {
    $product_info = $product_info_result->fields;

    // links breadcrumb
    if (SHOW_PRODUCTS_MODEL == 'true') {
      $oBreadcrumb->add($product_info['products_model'], oos_href_link($aContents['product_info'], 'category=' . $category . '&amp;products_id=' . $nProductsId));
    } else {
      $oBreadcrumb->add($product_info['products_name'], oos_href_link($aContents['product_info'], 'category=' . $category . '&amp;products_id=' . $nProductsId));
    }

    $aTemplate['page'] = $sTheme . '/products/cross_sell_products.tpl';
    $aTemplate['also_purchased_products'] = $sTheme . '/products/also_purchased_products.tpl';
    $aTemplate['history_products'] = $sTheme . '/products/history_products.tpl';
    $aTemplate['xsell_products'] = $sTheme . '/products/xsell_products.tpl';
    $aTemplate['up_sell_products'] = $sTheme . '/products/up_sell_products.tpl';
    $aTemplate['featured'] = $sTheme . '/modules/products/featured.tpl';

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    $smarty->assign('oos_breadcrumb', $oBreadcrumb->trail(BREADCRUMB_SEPARATOR));

    require_once MYOOS_INCLUDE_PATH . '/includes/modules/slavery_products.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/modules/history_products.php';

    if ( (USE_CACHE == 'true') && (!SID) ) {
      $smarty->setCaching(true);
    }

    if (!$smarty->isCached($aTemplate['xsell_products'], $oos_products_info_cache_id)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/modules/xsell_products.php';
    }
    $smarty->assign('xsell_products', $smarty->fetch($aTemplate['xsell_products'], $oos_products_info_cache_id));

    if (!$smarty->isCached($aTemplate['up_sell_products'], $oos_products_info_cache_id)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/modules/up_sell_products.php';
    }
    $smarty->assign('up_sell_products', $smarty->fetch($aTemplate['up_sell_products'], $oos_products_info_cache_id));

    if (!$smarty->isCached($aTemplate['also_purchased_products'], $oos_products_info_cache_id)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/modules/also_purchased_products.php';
      $smarty->assign('oos_also_purchased_array', $aPurchased);
    }
    $smarty->assign('also_purchased_products', $smarty->fetch($aTemplate['also_purchased_products'], $oos_products_info_cache_id));

    if (!$smarty->isCached($aTemplate['featured'], $oos_modules_cache_id)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/modules/featured.php';
    }
    $smarty->assign('featured', $smarty->fetch($aTemplate['featured'], $oos_modules_cache_id));

    $smarty->setCaching(false);
  }

// display the template
$smarty->display($aTemplate['page']);
