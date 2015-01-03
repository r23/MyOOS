<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being require_onced by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!defined('OOS_BASE_PRICE')) {
    define('OOS_BASE_PRICE', 'false');
  }

  if (isset($_GET['products_id'])) {
    if (!isset($nProductsID)) $nProductsID = oos_get_product_id($_GET['products_id']);
  }

  $aTemplate['popup_print'] = $sTheme . '/products/popup_print.html';

  //smarty
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
 $smarty = new myOOS_Smarty();

  /**
   * Smarty Cache Handler
   * utilizing eAccelerator extension (http://eaccelerator.net/HomeUk)
   */
  if (function_exists( 'eaccelerator' )) {
    $smarty->cache_handler_func = 'smarty_cache_eaccelerator';
  }

  $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);

  $sGroup = trim($_SESSION['user']->group['text']);
  $popup_cache_id = $sTheme . '|products|' . $sGroup . '|print|' . $nProductsID . '|' . $sLanguage;

  if (!$smarty->isCached($aTemplate['popup_print'], $popup_cache_id )) {
    require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/products_info.php';

    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $product_info_sql = "SELECT p.products_id, pd.products_name, pd.products_description, pd.products_url,
                                pd.products_description_meta, pd.products_keywords_meta, p.products_model,
                                p.products_quantity, p.products_image, 
                                p.products_discount_allowed, p.products_price, p.products_base_price, p.products_base_unit,
                                p.products_quantity_order_min, p.products_quantity_order_units,
                                p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4,
                                p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                                p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_date_added,
                                p.products_date_available, p.manufacturers_id, p.products_price_list
                          FROM $productstable p,
                               $products_descriptiontable pd
                          WHERE p.products_status >= '1' 
                            AND p.products_id = '" . intval($nProductsID) . "' 
                            AND pd.products_id = p.products_id 
                            AND pd.products_languages_id = '" . intval($nLanguageID) . "'";
    $product_info_result = $dbconn->Execute($product_info_sql);

    if (!$product_info_result->RecordCount()) {
      // product not found
      $aLang['text_information'] = $aLang['text_product_not_found'];

      $smarty->assign(
          array(
              'breadcrumb'    => $oBreadcrumb->trail(),
              'heading_title' => $aLang['text_product_not_found']
          )
      );
    } else {

      $product_info = $product_info_result->fields;

      $info_product_price = '';
      $info_product_special_price = '';
      $info_product_discount_price = '';
      $info_base_product_price = '';
      $info_base_product_special_price = '';
      $info_product_price_list = 0;
      $info_special_price = '';
      $info_product_special_price = '';

      if ($_SESSION['user']->group['show_price'] == 1 ) {
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


      if (OOS_BASE_PRICE == 'false') {
        $info_product_price_list = $oCurrencies->display_price($product_info['products_price_list'], oos_get_tax_rate($product_info['products_tax_class_id']));
        $smarty->assign('info_product_price_list', $info_product_price_list);
      }

      // assign Smarty variables;
      $smarty->assign_by_ref("oEvent", $oEvent);

      $smarty->assign('product_info', $product_info);
      $smarty->assign('oosDate', date('Y-m-d H:i:s'));
      $smarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);

      $smarty->assign(
          array(
              'filename'                        => $aContents,

              'request_type'                    => $request_type,

              'theme_set'                       => $sTheme,
              'theme_image'                     => 'themes/' . $sTheme . '/images',
              'theme_css'                       => 'themes/' . $sTheme,

              'lang'                            => $aLang,

              'info_product_price'              => $info_product_price,
              'info_special_price'              => $info_special_price,
              'info_product_special_price'      => $info_product_special_price,
              'info_product_discount_price'     => $info_product_discount_price,
              'info_base_product_price'         => $info_base_product_price,
              'info_base_product_special_price' => $info_base_product_special_price
          )
      );
    }
  }

  // display the template
  $smarty->display($aTemplate['popup_print'], $popup_cache_id);
