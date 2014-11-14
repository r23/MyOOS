<?php
/* ----------------------------------------------------------------------
   $Id: cross_sell.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being required by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (isset($_GET['products_id'])) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
  } else {
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }

  include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/products_cross_sell.php';

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

    $aOption['template_main'] = $sTheme . '/system/info.html';
    $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

    $nPageType =OOS_PAGE_TYPE_PRODUCTS;

    include_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      include_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
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
      $oBreadcrumb->add($product_info['products_model'], oos_href_link($aModules['products'], $aFilename['product_info'], 'cPath=' . $cPath . '&amp;products_id=' . $nProductsId));
    } else {
      $oBreadcrumb->add($product_info['products_name'], oos_href_link($aModules['products'], $aFilename['product_info'], 'cPath=' . $cPath . '&amp;products_id=' . $nProductsId));
    }

    $aOption['template_main'] = $sTheme . '/products/cross_sell_products.html';
    $aOption['also_purchased_products'] = $sTheme . '/products/also_purchased_products.html';
    $aOption['history_products'] = $sTheme . '/products/history_products.html';
    $aOption['xsell_products'] = $sTheme . '/products/xsell_products.html';
    $aOption['up_sell_products'] = $sTheme . '/products/up_sell_products.html';
    $aOption['featured'] = $sTheme . '/modules/products/featured.html';

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    include_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      include_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    $smarty->assign('oos_breadcrumb', $oBreadcrumb->trail(BREADCRUMB_SEPARATOR));

    include_once MYOOS_INCLUDE_PATH . '/includes/modules/slavery_products.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/modules/history_products.php';

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}

    if (!$smarty->isCached($aOption['xsell_products'], $oos_products_info_cache_id)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/modules/xsell_products.php';
    }
    $smarty->assign('xsell_products', $smarty->fetch($aOption['xsell_products'], $oos_products_info_cache_id));

    if (!$smarty->isCached($aOption['up_sell_products'], $oos_products_info_cache_id)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/modules/up_sell_products.php';
    }
    $smarty->assign('up_sell_products', $smarty->fetch($aOption['up_sell_products'], $oos_products_info_cache_id));

    if (!$smarty->isCached($aOption['also_purchased_products'], $oos_products_info_cache_id)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/modules/also_purchased_products.php';
      $smarty->assign('oos_also_purchased_array', $aPurchased);
    }
    $smarty->assign('also_purchased_products', $smarty->fetch($aOption['also_purchased_products'], $oos_products_info_cache_id));

    if (!$smarty->isCached($aOption['featured'], $oos_modules_cache_id)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/modules/featured.php';
    }
    $smarty->assign('featured', $smarty->fetch($aOption['featured'], $oos_modules_cache_id));

    $smarty->setCaching(false);
  }

  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  // display the template
  include_once MYOOS_INCLUDE_PATH . '/includes/oos_display.php';
