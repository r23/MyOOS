<?php
/* ----------------------------------------------------------------------
   $Id: info.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!defined('OOS_BASE_PRICE')) {
    define('OOS_BASE_PRICE', 'false');
  }
  if (isset($_GET['products_id'])) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
  } else {
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }

  require 'includes/languages/' . $sLanguage . '/products_info.php';

  $productstable = $oostable['products'];
  $products_descriptiontable = $oostable['products_description'];
  $product_info_sql = "SELECT p.products_id, pd.products_name, pd.products_description, pd.products_url,
                              pd.products_description_meta, p.products_model,
                              p.products_quantity, p.products_image, p.products_subimage1, p.products_subimage2,
                              p.products_subimage3, p.products_subimage4, p.products_subimage5, p.products_subimage6,
                              p.products_movie, p.products_zoomify, p.products_discount_allowed, p.products_price,
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

    $aOption['template_main'] = $sTheme . '/system/info.html';
    $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require 'includes/oos_system.php';
    if (!isset($option)) {
      require 'includes/info_message.php';
      require 'includes/oos_blocks.php';
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


    if (is_dir(OOS_IMAGES . 'zoomify/')) {
      if ($product_info['products_zoomify'] == '') {
        if (oos_is_not_null($product_info['products_image'])){
          $sImage = $product_info['products_image'];
          $sDir = substr($sImage, 0, strrpos($sImage, '.'));
          if ( file_exists(OOS_IMAGES . 'zoomify/' .  $sDir  . '/ImageProperties.xml') ) {
            $sImagePath = $sDir;
          }
        }

        if (!isset($sImagePath)) {
          $sName = $product_info['products_name'];
          $sDir = oos_strip_all($product_info['products_name']);
          if ( file_exists(OOS_IMAGES . 'zoomify/' .  $sDir  . '/ImageProperties.xml') ) {
            $sImagePath = $sDir;
          }
        }

        if (isset($sImagePath)) {
          $productstable = $oostable['products'];
          $query = "UPDATE $productstable"
              . " SET products_zoomify = ?"
              . " WHERE products_id = ?";
          $result = $dbconn->Execute($query, array((string)$sImagePath, (int)$nProductsId));

          $product_info['products_zoomify'] = $sImagePath;
        }
      }
    }


    // links breadcrumb
    if (SHOW_PRODUCTS_MODEL == 'true') {
      $oBreadcrumb->add($product_info['products_model'], oos_href_link($aModules['products'], $aFilename['product_info'], 'cPath=' . $cPath . '&amp;products_id=' . $nProductsId));
    } else {
      $oBreadcrumb->add($product_info['products_name'], oos_href_link($aModules['products'], $aFilename['product_info'], 'cPath=' . $cPath . '&amp;products_id=' . $nProductsId));
    }


    // $oos_pagetitle = OOS_META_TITLE . ' // ' . $oBreadcrumb->trail_title(' &raquo; ');
    $oos_pagetitle = OOS_META_TITLE . ' - ' . $product_info['products_name'];


    // todo multilanguage support
    if (OOS_META_PRODUKT == "description tag by article description replace") {
      $oos_meta_description = substr(strip_tags(preg_replace('!(\r\n|\r|\n)!', '',$product_info['products_description'])),0 , 250);
    } elseif (OOS_META_PRODUKT == "Meta Tag with article edit") {
      $oos_meta_description = $product_info['products_description_meta'];
      $oos_meta_keywords = $product_info['products_keywords_meta'];
    }

    $aOption['template_main'] = $sTheme . '/products/product_info.html';
    $aOption['also_purchased_products'] = $sTheme . '/products/also_purchased_products.html';
    $aOption['xsell_products'] = $sTheme . '/products/xsell_products.html';
    $aOption['up_sell_products'] = $sTheme . '/products/up_sell_products.html';
    $aOption['page_heading'] = $sTheme . '/products/product_heading.html';

    if (SOCIAL_BOOKMARKS == 'true') {
      $aOption['social_bookmarks'] = 'default/products/social_bookmarks.html';
    }

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require 'includes/oos_system.php';
    if (!isset($option)) {
      require 'includes/info_message.php';
      require 'includes/oos_blocks.php';
    }

    // products history
    $_SESSION['products_history']->add_current_products($nProductsId);

    // JavaScript 
    $smarty->assign('popup_window', 'popup_window.js');

    $info_product_price = '';
    $info_product_special_price = '';
    $info_product_discount = 0;
    $info_product_discount_price = '';
    $info_base_product_price = '';
    $info_base_product_special_price = '';
    $info_product_price_list = 0;
    $info_special_price = '';
    $info_product_special_price = '';

    if ($_SESSION['member']->group['show_price'] == 1 ) {
      $info_product_price = $oCurrencies->display_price($product_info['products_price'], oos_get_tax_rate($product_info['products_tax_class_id']));

      if ($info_special_price = oos_get_products_special_price($product_info['products_id'])) {
        $info_product_special_price = $oCurrencies->display_price($info_special_price, oos_get_tax_rate($product_info['products_tax_class_id']));
      } else {
        $info_product_discount = min($product_info['products_discount_allowed'], $_SESSION['member']->group['discount']);

        if ($info_product_discount != 0 ) {
          $info_product_special_price = $product_info['products_price']*(100-$info_product_discount)/100;
          $info_product_discount_price = $oCurrencies->display_price($info_product_special_price, oos_get_tax_rate($product_info['products_tax_class_id']));
        }

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
            'info_max_product_discount'       => $info_product_discount,
            'info_product_discount_price'     => $info_product_discount_price,
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
        require 'includes/modules/discounts_price.php';

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

    require 'includes/modules/products_options.php';

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

    $smarty->assign('redirect', oos_href_link($aModules['main'], $aFilename['redirect'], 'action=url&amp;goto=' . urlencode($product_info['products_url']), 'NONSSL', false, false));
    $smarty->assign('oosDate', date('Y-m-d H:i:s'));

    if (SOCIAL_BOOKMARKS == 'true') {
      $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);

      $oos_social_bookmarks_cache_id = '|social_bookmarks|' . $sLanguage . '|' . intval($nProductsId);
      if (!$smarty->isCached($aOption['social_bookmarks'], $oos_social_bookmarks_cache_id)) {
        $smarty->assign('bookmark', oos_href_link($aModules['products'], $aFilename['product_info'], 'products_id=' . intval($nProductsId), 'NONSSL', false));
        $smarty->assign('bookmarktitle', STORE_NAME . ' - ' . $product_info['products_name']);
      }
      $smarty->assign('social_bookmarks', $smarty->fetch($aOption['social_bookmarks'], $oos_social_bookmarks_cache_id));

      $smarty->setCaching(false);
    }



if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}
    if (!$smarty->isCached($aOption['xsell_products'], $oos_products_info_cache_id)) {
      require 'includes/modules/xsell_products.php';
    }
    $smarty->assign('xsell_products', $smarty->fetch($aOption['xsell_products'], $oos_products_info_cache_id));

    if (!$smarty->isCached($aOption['up_sell_products'], $oos_products_info_cache_id)) {
      require 'includes/modules/up_sell_products.php';
    }
    $smarty->assign('up_sell_products', $smarty->fetch($aOption['up_sell_products'], $oos_products_info_cache_id));

    require 'includes/modules/slavery_products.php';

    if (!$smarty->isCached($aOption['also_purchased_products'], $oos_products_info_cache_id)) {
      require 'includes/modules/also_purchased_products.php';
      $smarty->assign('oos_also_purchased_array', $aPurchased);
    }
    $smarty->assign('also_purchased_products', $smarty->fetch($aOption['also_purchased_products'], $oos_products_info_cache_id));

    $smarty->setCaching(false);
  }

  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
