<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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

    $aTemplate['page'] = $sTheme . '/page/info.html';
    $aTemplate['page_heading'] = $sTheme . '/heading/page_heading.html';

    $nPageType =OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    // assign Smarty variables;
    $smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(),
            'heading_title' => $aLang['text_product_not_found']
        )
    );

  } else {
    $product_info = $product_info_result->fields;

    // links breadcrumb
    if (SHOW_PRODUCTS_MODEL == 'true') {
      $oBreadcrumb->add($product_info['products_model'], oos_href_link($aContents['product_info'], 'category=' . $sCategory . '&amp;products_id=' . $nProductsId));
    } else {
      $oBreadcrumb->add($product_info['products_name'], oos_href_link($aContents['product_info'], 'category=' . $sCategory . '&amp;products_id=' . $nProductsId));
    }

    $aTemplate['page'] = $sTheme . '/products/cross_sell_products.html';
    $aTemplate['also_purchased_products'] = $sTheme . '/products/also_purchased_products.html';
    $aTemplate['history_products'] = $sTheme . '/products/history_products.html';
    $aTemplate['xsell_products'] = $sTheme . '/products/xsell_products.html';
    $aTemplate['up_sell_products'] = $sTheme . '/products/up_sell_products.html';
    $aTemplate['featured'] = $sTheme . '/page/products/featured.html';

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    $smarty->assign('breadcrumb', $oBreadcrumb->trail());

    require_once MYOOS_INCLUDE_PATH . '/includes/modules/slavery_products.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/modules/history_products.php';

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
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

    if (!$smarty->isCached($aTemplate['featured'], $nModulesCacheID)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/modules/featured.php';
    }
    $smarty->assign('featured', $smarty->fetch($aTemplate['featured'], $nModulesCacheID));

    $smarty->setCaching(false);
  }

  $smarty->assign('oosPageHeading', $smarty->fetch($aTemplate['page_heading']));


  // display the template
$smarty->display($aTemplate['page']);
