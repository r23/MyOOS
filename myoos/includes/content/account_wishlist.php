<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: wishlist_help.php,v 1  2002/11/09 wib
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

if (!isset($_SESSION['customer_id'])) {
    // navigation history
    if (!isset($_SESSION['navigation'])) {
        $_SESSION['navigation'] = new navigationHistory();
    }

    $_SESSION['info_message'] = $aLang['info_login_for_wichlist'];
    $_SESSION['guest_login'] = 'off';

    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login']));
}

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;


// split-page-results
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_wishlist.php';

$customers_wishlisttable = $oostable['customers_wishlist'];
$wishlist_result_raw = "SELECT products_id, customers_wishlist_date_added
                          FROM $customers_wishlisttable
                          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
                            AND customers_wishlist_link_id = '" . oos_db_input($_SESSION['customer_wishlist_link_id']) . "' 
                       ORDER BY customers_wishlist_date_added";
$wishlist_split = new splitPageResults($wishlist_result_raw, MAX_DISPLAY_WISHLIST_PRODUCTS);
$wishlist_result = $dbconn->Execute($wishlist_split->sql_query);

$aWishlist = [];
while ($wishlist = $wishlist_result->fields) {
    $wl_products_id = oos_get_product_id($wishlist['products_id']);

    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $sql = "SELECT p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_replacement_product_id,
                   p.products_image, p.products_price, p.products_base_price, p.products_base_unit, p.products_product_quantity, 
					p.products_discount1, p.products_discount2,
					p.products_discount3, p.products_discount4, p.products_discount1_qty,
					p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty,				   
				   p.products_quantity_order_min, p.products_quantity_order_max,
                   p.products_tax_class_id, p.products_units_id
            FROM $productstable p,
                 $products_descriptiontable pd
            WHERE p.products_id = '" . intval($wl_products_id) . "'
              AND pd.products_id = p.products_id
              AND pd.products_languages_id = '" .  intval($nLanguageID) . "'";
    $wishlist_product = $dbconn->GetRow($sql);

    $discount = null;

    $wishlist_product_price = null;
    $wishlist_product_special_price = null;
    $wishlist_product_discount_price = null;
    $wishlist_price_list = null;
    $wishlist_base_product_price = null;
    $wishlist_special_price = null;
    $wishlist_cross_out_price = null;
    $wishlist_until = null;

    if ($aUser['show_price'] == 1) {
        $base_product_price = $wishlist_product['products_price'];
        $wishlist_special = oos_get_products_special($wishlist_product['products_id']);

        if ($wishlist_product['products_price_list'] > 0) {
            $wishlist_price_list = $oCurrencies->display_price($wishlist_product['products_price_list'], oos_get_tax_rate($wishlist_product['products_tax_class_id']));
        }

        if ($wishlist_product['products_discount4'] > 0) {
            $discount = $wishlist_product['products_discount4'];
        } elseif ($wishlist_product['products_discount3'] > 0) {
            $discount = $wishlist_product['products_discount3'];
        } elseif ($wishlist_product['products_discount2'] > 0) {
            $discount = $wishlist_product['products_discount2'];
        } elseif ($wishlist_product['products_discount1'] > 0) {
            $discount = $wishlist_product['products_discount1'];
        }

        if ($discount > 0) {
            $base_product_price = $discount;
            $wishlist_product_discount_price = $oCurrencies->display_price($discount, oos_get_tax_rate($wishlist_product['products_tax_class_id']));
        }


        if ($wishlist_special['specials_new_products_price'] > 0) {
            $base_product_price = $wishlist_special['specials_new_products_price'];
            $wishlist_product_special_price = $oCurrencies->display_price($wishlist_special['specials_new_products_price'], oos_get_tax_rate($wishlist_product['products_tax_class_id']));

            if ($wishlist_special['specials_cross_out_price'] > 0) {
                $wishlist_cross_out_price = $oCurrencies->display_price($wishlist_special['specials_cross_out_price'], oos_get_tax_rate($wishlist_product['products_tax_class_id']));
            }

            $wishlist_until = sprintf($aLang['only_until'], oos_date_short($wishlist_special['expires_date']));
        }
    }


    $customers_wishlist_attributestable = $oostable['customers_wishlist_attributes'];
    $sql = "SELECT products_options_id, products_options_value_id
            FROM $customers_wishlist_attributestable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
              AND customers_wishlist_link_id = '" . oos_db_input($_SESSION['customer_wishlist_link_id']) . "'
              AND products_id = '" . oos_db_input($wishlist['products_id']) . "'";
    $attributes_result = $dbconn->Execute($sql);

    $attributes_print = '';
    $attributes_hidden_field = '';

    while ($attributes = $attributes_result->fields) {
        $attributes_hidden_field .=  oos_draw_hidden_field('id[' . $attributes['products_options_id'] . ']', $attributes['products_options_value_id']);
        $attributes_print .=  '<ul class="list-unstyled mb-0">';

        $products_optionstable = $oostable['products_options'];
        $products_options_valuestable = $oostable['products_options_values'];
        $products_attributestable = $oostable['products_attributes'];
        $sql = "SELECT popt.products_options_name,
                     poval.products_options_values_name,
                     pa.options_values_price, pa.price_prefix, pa.options_values_image
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

        // image
        if ($option_values['options_values_image'] != '') {
            $attributes_image = $option_values['options_values_image'];
        }

        $attributes_print .=  '<li> - ' . $option_values['products_options_name'] . ' ' . $option_values['products_options_values_name'] . ' ';

        if ($option_values['options_values_price'] != 0) {
            //    $attributes_print .=  $option_values['price_prefix'] . $oCurrencies->display_price($option_values['options_values_price'], oos_get_tax_rate($wishlist_product['products_tax_class_id'])) . '</li>';
            $sAttributesPrice = $option_values['options_values_price'];
            if ($option_values['price_prefix'] == '+') {
                $base_product_price += $sAttributesPrice;
            } else {
                $base_product_price -= $sAttributesPrice;
            }
        } else {
            $attributes_print .=  '</li>';
        }
        $attributes_print .=  '</ul>';

        $attributes_result->MoveNext();
    }


    // image
    $image = $attributes_image ?? $wishlist_product['products_image'];

    if ($aUser['show_price'] == 1) {
        // price wirth attribute price
        $wishlist_product_price = $oCurrencies->display_price($base_product_price, oos_get_tax_rate($wishlist_product['products_tax_class_id']));


        if ($wishlist_product['products_base_price'] != 1) {
            $wishlist_base_product_price = $oCurrencies->display_price($base_product_price * $wishlist_product['products_base_price'], oos_get_tax_rate($wishlist_product['products_tax_class_id']));
        }
    }

    // price wirth attribute price
    $wishlist_product_price = $oCurrencies->display_price($base_product_price, oos_get_tax_rate($wishlist_product['products_tax_class_id']));


    if ($wishlist_product['products_base_price'] != 1) {
        $wishlist_base_product_price = $oCurrencies->display_price($base_product_price * $wishlist_product['products_base_price'], oos_get_tax_rate($wishlist_product['products_tax_class_id']));
    }



    $order_min = number_format($wishlist_product['products_quantity_order_min']);
    $order_max = number_format($wishlist_product['products_quantity_order_max']);

    // with option $wishlist['products_id'] = 2{3}1
    $aWishlist[] = ['products_id' => $wishlist['products_id'], 'wl_products_id' => $wl_products_id, 'products_image' => $image, 'products_name' => $wishlist_product['products_name'], 'order_min' => $order_min, 'order_max' => $order_max, 'product_quantity' => $wishlist_product['products_product_quantity'], 'products_units_id' => $wishlist_product['products_units_id'], 'product_price' => $wishlist_product_price, 'product_special_price' => $wishlist_product_special_price, 'product_discount_price' => $wishlist_product_discount_price, 'base_product_price' => $wishlist_base_product_price, 'products_base_price' => $wishlist_product['products_base_price'], 'products_base_unit' => $wishlist_product['products_base_unit'], 'product_price_list'    => $wishlist_price_list, 'cross_out_price'    => $wishlist_cross_out_price, 'until'    => $wishlist_until, 'attributes_print' => $attributes_print, 'attributes_hidden' => $attributes_hidden_field];
    $wishlist_result->MoveNext();
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['account_wishlist']));
$sCanonical = oos_href_link($aContents['specials'], 'page='. $nPage, false, true);

$aTemplate['page'] = $sTheme . '/page/account_wishlist.html';
$aTemplate['pagination'] = $sTheme . '/system/_pagination.html';

$nPageType = OOS_PAGE_TYPE_CATALOG;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'        => $oBreadcrumb->trail(), 'heading_title'     => $aLang['heading_title'], 'robots'            => 'noindex,nofollow,noodp,noydir', 'canonical'            => $sCanonical, 'account_active'    => 1, 'page_split'        => $wishlist_split->display_count($aLang['text_display_number_of_wishlist']), 'display_links'     => $wishlist_split->display_links(MAX_DISPLAY_PAGE_LINKS, oos_get_all_get_parameters(['page', 'info'])), 'numrows'             => $wishlist_split->number_of_rows, 'numpages'             => $wishlist_split->number_of_pages, 'page'                => $nPage, 'wishlist'             => $aWishlist]
);

$smarty->assign('pagination', $smarty->fetch($aTemplate['pagination']));

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-$nonce' 'unsafe-eval'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
