<?php
/* ----------------------------------------------------------------------
   $Id: account_my_wishlist.php 431 2013-06-21 22:03:17Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: wishlist_help.php,v 1  2002/11/09 wib
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }
  // split-page-results
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_my_wishlist.php';

  $customers_wishlisttable = $oostable['customers_wishlist'];
  $wishlist_result_raw = "SELECT products_id, customers_wishlist_date_added
                          FROM $customers_wishlisttable
                          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
                            AND customers_wishlist_link_id = '" . oos_db_input($_SESSION['customer_wishlist_link_id']) . "'
                       ORDER BY customers_wishlist_date_added";
  $wishlist_split = new splitPageResults($_GET['page'], MAX_DISPLAY_WISHLIST_PRODUCTS, $wishlist_result_raw, $wishlist_numrows);
  $wishlist_result = $dbconn->Execute($wishlist_result_raw);

  $aWishlist = array();
  while ($wishlist = $wishlist_result->fields) {
    $wl_products_id = oos_get_product_id($wishlist['products_id']);

    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $sql = "SELECT p.products_id, pd.products_name, pd.products_description, p.products_model,
                   p.products_image, p.products_price, p.products_base_price, p.products_base_unit,
                   p.products_discount_allowed, p.products_tax_class_id, p.products_units_id
            FROM $productstable p,
                 $products_descriptiontable pd
            WHERE p.products_id = '" . intval($wl_products_id) . "'
              AND pd.products_id = p.products_id
              AND pd.products_languages_id = '" .  intval($nLanguageID) . "'";
    $wishlist_product = $dbconn->GetRow($sql);

    $wishlist_product_price = '';
    $wishlist_product_special_price = '';
    $wishlist_base_product_price = '';
    $wishlist_base_product_special_price = '';
    $wishlist_special_price = '';

    $wishlist_product_price = $oCurrencies->display_price($wishlist_product['products_price'], oos_get_tax_rate($wishlist_product['products_tax_class_id']));

    if ($wishlist_special_price = oos_get_products_special_price($wl_products_id)) {
      $wishlist_product_special_price = $oCurrencies->display_price($wishlist_special_price, oos_get_tax_rate($wishlist_product['products_tax_class_id']));
    } 

    if ($wishlist_product['products_base_price'] != 1) {
      $wishlist_base_product_price = $oCurrencies->display_price($wishlist_product['products_price'] * $wishlist_product['products_base_price'], oos_get_tax_rate($wishlist_product['products_tax_class_id']));

      if ($wishlist_special_price != '') {
        $wishlist_base_product_special_price = $oCurrencies->display_price($wishlist_special_price * $wishlist_product['products_base_price'], oos_get_tax_rate($wishlist_product['products_tax_class_id']));
      }
    }

    $customers_wishlist_attributestable = $oostable['customers_wishlist_attributes'];
    $sql = "SELECT products_options_id, products_options_value_id
            FROM $customers_wishlist_attributestable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
              AND customers_wishlist_link_id = '" . oos_db_input($_SESSION['customer_wishlist_link_id']) . "' AND
                  products_id = '" . oos_db_input($wishlist['products_id']) . "'";
    $attributes_result = $dbconn->Execute($sql);
    $attributes_print = '';
    while ($attributes = $attributes_result->fields) {
      $attributes_print .=  oos_draw_hidden_field('id[' . $attributes['products_options_id'] . ']', $attributes['products_options_value_id']);
      $attributes_print .=  '                   <tr>';

      $products_optionstable = $oostable['products_options'];
      $products_options_valuestable = $oostable['products_options_values'];
      $products_attributestable = $oostable['products_attributes'];
      $sql = "SELECT popt.products_options_name,
                     poval.products_options_values_name,
                     pa.options_values_price, pa.price_prefix
              FROM $products_optionstable popt,
                   $products_options_valuestable poval,
                   $products_attributestable pa
             WHERE pa.products_id = '" . intval($wl_products_id) . "'
               AND pa.options_id = '" . oos_db_input($attributes['products_options_id']) . "'
               AND pa.options_id = popt.products_options_id
               AND pa.options_values_id = '" . oos_db_input($attributes['products_options_value_id']) . "'
               AND pa.options_values_id = poval.products_options_values_id
               AND popt.products_options_languages_id = '" .  intval($nLanguageID) . "'
               AND poval.products_options_values_languages_id = '" .  intval($nLanguageID) . "'";
      $option_values = $dbconn->GetRow($sql);

      $attributes_print .=  '<td><br /><small><i> - ' . $option_values['products_options_name'] . ' ' . $option_values['products_options_values_name'] . '</i></small></td>';

      if ($option_values['options_values_price'] != 0) {
        $attributes_print .=  '<td align="right"><small><i>' . $option_values['price_prefix'] . $oCurrencies->display_price($option_values['options_values_price'], oos_get_tax_rate($wishlist_product['products_tax_class_id'])) . '</i></small></td>';
      } else {
        $attributes_print .=  '<td><small><i>&nbsp;</i></small></td>';
      }
      $attributes_print .=  '                   </tr>';
      $attributes_result->MoveNext();
    }

    // Close result set
    $attributes_result->Close();

    // with option $wishlist['products_id'] = 2{3}1
    $aWishlist[] = array('products_id' => $wishlist['products_id'],
                         'wl_products_id' => $wl_products_id,
                         'products_image' => $wishlist_product['products_image'],
                         'products_name' => $wishlist_product['products_name'],
                         'product_price' => $wishlist_product_price,
                         'product_special_price' => $wishlist_product_special_price,
                         'base_product_price' => $wishlist_base_product_price,
                         'base_product_special_price' => $wishlist_base_product_special_price,
                         'products_base_price' => $wishlist_product['products_base_price'],
                         'products_base_unit' => $wishlist_product['products_base_unit'],
                         'attributes_print' => $attributes_print);
    $wishlist_result->MoveNext();
  }

  // Close result set
  $wishlist_result->Close();

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['account_my_wishlist']));

  $aTemplate['page'] = $sTheme . '/modules/my_wishlist.tpl';
  $aTemplate['page_navigation'] = $sTheme . '/heading/page_navigation.tpl';

  $nPageType = OOS_PAGE_TYPE_CATALOG;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

// assign Smarty variables;
  $smarty->assign(
       array(
           'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
           'oos_heading_title' => $aLang['heading_title'],
           'oos_heading_image' => 'wishlist.gif',

           'oos_page_split' => $wishlist_split->display_count($wishlist_numrows, MAX_DISPLAY_WISHLIST_PRODUCTS, $_GET['page'], $aLang['text_display_number_of_wishlist']),
           'oos_display_links' => $wishlist_split->display_links($wishlist_numrows, MAX_DISPLAY_WISHLIST_PRODUCTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_parameters(array('page', 'info'))),
           'oos_page_numrows' => $wishlist_numrows,

           'wishlist_array' => $aWishlist
       )
  );
  $smarty->assign('oosPageNavigation', $smarty->fetch($aTemplate['page_navigation']));

// display the template
$smarty->display($aTemplate['page']);
