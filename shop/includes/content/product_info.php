<?php
/* ----------------------------------------------------------------------
   $Id: product_info.php 431 2013-06-21 22:03:17Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_info.php,v 1.92 2003/02/14 05:51:21 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being required by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!defined('OOS_BASE_PRICE')) {
    define('OOS_BASE_PRICE', 'false');
  }
  if (isset($_GET['products_id'])) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
  } else {
    oos_redirect(oos_href_link($aContents['main']));
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/products_info.php';

  $productstable = $oostable['products'];
  $products_descriptiontable = $oostable['products_description'];
  $product_info_sql = "SELECT p.products_id, pd.products_name, pd.products_description, pd.products_url,
                              pd.products_description_meta, pd.products_keywords_meta, p.products_model,
                              p.products_quantity, p.products_image, p.products_discount_allowed, p.products_price,
                              p.products_base_price, p.products_base_unit, p.products_quantity_order_min, p.products_quantity_order_units,
                              p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4,
                              p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                              p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_date_added,
                              p.products_date_available, p.manufacturers_id, p.products_price_list
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

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    $smarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['text_product_not_found'],
            'oos_heading_image' => 'specials.gif'
        )
    );

  } else {

    $products_descriptiontable = $oostable['products_description'];
    $query = "UPDATE $products_descriptiontable"
        . " SET products_viewed = products_viewed+1"
        . " WHERE products_id = ?"
        . "   AND products_languages_id = ?";
    $result = $dbconn->Execute($query, array((int)$nProductsId, (int)$nLanguageID));

    $product_info = $product_info_result->fields;

    // links breadcrumb
    if (SHOW_PRODUCTS_MODEL == 'true') {
      $oBreadcrumb->add($product_info['products_model'], oos_href_link($aContents['product_info'], 'category=' . $category . '&amp;products_id=' . $nProductsId));
    } else {
      $oBreadcrumb->add($product_info['products_name'], oos_href_link($aContents['product_info'], 'category=' . $category . '&amp;products_id=' . $nProductsId));
    }


    // $oos_pagetitle = OOS_META_TITLE . ' // ' . $oBreadcrumb->trail_title(' &raquo; ');
    $oos_pagetitle =  $product_info['products_name'] . ' - ' . OOS_META_TITLE ;
    $oos_meta_description = $product_info['products_description_meta'];

	
    $aTemplate['page'] = $sTheme . '/products/product_info.tpl';
    $aTemplate['also_purchased_products'] = $sTheme . '/products/also_purchased_products.tpl';
    $aTemplate['xsell_products'] = $sTheme . '/products/xsell_products.tpl';
    $aTemplate['up_sell_products'] = $sTheme . '/products/up_sell_products.tpl';
    $aTemplate['page_heading'] = $sTheme . '/products/product_heading.tpl';

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    // products history
    $_SESSION['products_history']->add_current_products($nProductsId);


    $info_product_price = '';
    $info_product_special_price = '';
    $info_base_product_price = '';
    $info_base_product_special_price = '';
    $info_product_price_list = 0;
    $info_special_price = '';
    $info_product_special_price = '';

    if ($_SESSION['member']->group['show_price'] == 1 ) {
      $info_product_price = $oCurrencies->display_price($product_info['products_price'], oos_get_tax_rate($product_info['products_tax_class_id']));

      if ($info_special_price = oos_get_products_special_price($product_info['products_id'])) {
        $info_product_special_price = $oCurrencies->display_price($info_special_price, oos_get_tax_rate($product_info['products_tax_class_id']));
      }

      if ($product_info['products_base_price'] != 1) {
        $info_base_product_price = $oCurrencies->display_price($product_info['products_price'] * $product_info['products_base_price'], oos_get_tax_rate($product_info['products_tax_class_id']));

        if ($info_product_special_price != '') {
          $info_base_product_special_price = $oCurrencies->display_price($info_product_special_price * $product_info['products_base_price'], oos_get_tax_rate($product_info['products_tax_class_id']));
        }
      }
    }

    // assign Smarty variables;
    $smarty->assign(
        array(
            'info_product_price'              => $info_product_price,
            'info_special_price'              => $info_special_price,
            'info_product_special_price'      => $info_product_special_price,
            'info_base_product_price'         => $info_base_product_price,
            'info_base_product_special_price' => $info_base_product_special_price
        )
    );

    if (OOS_BASE_PRICE == 'false') {
      $info_product_price_list = $oCurrencies->display_price($product_info['products_price_list'], oos_get_tax_rate($product_info['products_tax_class_id']));
      $smarty->assign('info_product_price_list', $info_product_price_list);
    }


    if ($oEvent->installed_plugin('reviews')) {
      $reviewstable = $oostable['reviews'];
      $reviews_sql = "SELECT COUNT(*) AS total FROM $reviewstable WHERE products_id = '" . intval($nProductsId) . "'";
      $reviews = $dbconn->Execute($reviews_sql);
      $reviews_total = $reviews->fields['total'];
      $smarty->assign('reviews_total', $reviews_total);
    }


    $discounts_price = 'false';
    if ( (oos_empty($info_special_price)) && ( ($product_info['products_discount4_qty'] > 0 || $product_info['products_discount3_qty'] > 0 || $product_info['products_discount2_qty'] > 0 || $product_info['products_discount1_qty'] > 0 )) ){
      if ( ($_SESSION['member']->group['show_price'] == 1 ) && ($_SESSION['member']->group['qty_discounts'] == 1) ) {
        $discounts_price = 'true';
        require_once MYOOS_INCLUDE_PATH . '/includes/modules/discounts_price.php';

        if ( $product_info['products_discount4'] > 0 ) {
          $price_discount = $oCurrencies->display_price($product_info['products_discount4'], oos_get_tax_rate($product_info['products_tax_class_id']));
        } elseif ( $product_info['products_discount3'] > 0 ) {
          $price_discount = $oCurrencies->display_price($product_info['products_discount3'], oos_get_tax_rate($product_info['products_tax_class_id']));
        } elseif ( $product_info['products_discount2'] > 0 ) {
          $price_discount = $oCurrencies->display_price($product_info['products_discount2'], oos_get_tax_rate($product_info['products_tax_class_id']));
        } elseif ( $product_info['products_discount1'] > 0 ) {
          $price_discount = $oCurrencies->display_price($product_info['products_discount1'], oos_get_tax_rate($product_info['products_tax_class_id']));
        }
        if (isset($price_discount)) {
          $smarty->assign('price_discount', $price_discount);
        }

      }
    }

    require_once MYOOS_INCLUDE_PATH . '/includes/modules/products_options.php';

    // assign Smarty variables;
    $smarty->assign(array('oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
                           'discounts_price' =>  $discounts_price));

    if (!isset($block_get_parameters)) {
      $block_get_parameters = oos_get_all_get_parameters(array('action'));
      $block_get_parameters = oos_remove_trailing($block_get_parameters);
      $smarty->assign('get_params', $block_get_parameters);
    }

    $smarty->assign('product_info', $product_info);
    $smarty->assign('options', $options);

    $smarty->assign('redirect', oos_href_link($aContents['redirect'], 'action=url&amp;goto=' . urlencode($product_info['products_url']), 'NONSSL', false, false));
    $smarty->assign('oosDate', date('Y-m-d H:i:s'));


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

    require_once MYOOS_INCLUDE_PATH . '/includes/modules/slavery_products.php';

    if (!$smarty->isCached($aTemplate['also_purchased_products'], $oos_products_info_cache_id)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/modules/also_purchased_products.php';
      $smarty->assign('oos_also_purchased_array', $aPurchased);
    }
    $smarty->assign('also_purchased_products', $smarty->fetch($aTemplate['also_purchased_products'], $oos_products_info_cache_id));

    $smarty->setCaching(false);
  }


// display the template
$smarty->display($aTemplate['page']);
