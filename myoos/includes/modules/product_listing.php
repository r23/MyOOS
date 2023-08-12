<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_listing.php,v 1.2 2003/01/09 09:40:08 elarifr
   orig: product_listing.php,v 1.41 2003/02/12 23:55:58 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_listing.php';

$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS);

/*
    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = oos_create_sort_heading($_GET['sort'], $col+1, $lc_text);
    }
*/
if ($listing_split->number_of_rows > 0) {
    if (!isset($all_get_listing)) {
        $all_get_listing = oos_get_all_get_parameters(array('action'));
    }

    $aListing = [];
    $listing_result = $dbconn->Execute($listing_split->sql_query);
    while ($listing = $listing_result->fields) {

        /*
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_text = '&nbsp;<a href="' . oos_href_link($aContents['shop'], 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;
        */

        $discount = null;

        $listing_product_price = null;
        $listing_product_price_list = null;
        $listing_product_special_price = null;
        $listing_base_product_price = null;
        $base_product_price = $listing['products_price'];
        $listing_until = null;
        $listing_new_cross_out_price = null;


        if ($aUser['show_price'] == 1) {
            $listing_product_price = $oCurrencies->display_price($listing['products_price'], oos_get_tax_rate($listing['products_tax_class_id']));

            if ($listing['products_price_list'] > 0) {
                $listing_product_price_list = $oCurrencies->display_price($listing['products_price_list'], oos_get_tax_rate($listing['products_tax_class_id']));
            }

            if ($listing['products_discount4'] > 0) {
                $discount = $listing['products_discount4'];
            } elseif ($listing['products_discount3'] > 0) {
                $discount = $listing['products_discount3'];
            } elseif ($listing['products_discount2'] > 0) {
                $discount = $listing['products_discount2'];
            } elseif ($listing['products_discount1'] > 0) {
                $discount = $listing['products_discount1'];
            }

            if ($discount > 0) {
                $base_product_price = $discount;
                $listing_discount_price = $oCurrencies->display_price($discount, oos_get_tax_rate($listing['products_tax_class_id']));
            }

            if ($listing['specials_new_products_price'] > 0) {
                $base_product_price = $listing['specials_new_products_price'];
                $listing_product_special_price = $oCurrencies->display_price($listing['specials_new_products_price'], oos_get_tax_rate($listing['products_tax_class_id']));

                if ($listing['specials_cross_out_price'] > 0) {
                    $listing_cross_out_price = $oCurrencies->display_price($listing['specials_cross_out_price'], oos_get_tax_rate($listing['products_tax_class_id']));
                }

                $listing_until = sprintf($aLang['only_until'], oos_date_short($listing['expires_date']));
            }

            if ($listing['products_base_price'] != 1) {
                $listing_base_product_price = $oCurrencies->display_price($base_product_price * $listing['products_base_price'], oos_get_tax_rate($listing['products_tax_class_id']));
            }
        }

        $order_min = number_format($listing['products_quantity_order_min']);
        $order_max = number_format($listing['products_quantity_order_max']);

        $aListing[] = ['products_id' => $listing['products_id'],
                        'products_image' => $listing['products_image'],
                        'products_name' => $listing['products_name'],
                        'products_model' => $listing['products_model'],
                        'products_short_description' => $listing['products_short_description'],
                        'manufacturers_id' => $listing['manufacturers_id'],
                        'manufacturers_name' =>    $listing['manufacturers_name'],
                        'order_min' => $order_min,
                        'order_max' => $order_max,
                        'product_quantity' => $listing['products_product_quantity'],
                        'products_base_price' => $listing['products_base_price'],
                        'products_base_unit' => $listing['products_base_unit'],
                        'products_units' => $listing['products_units_id'],
                        'listing_until' => $listing_until,
                        'listing_cross_out_price'    => $listing_cross_out_price,
                        'listing_product_price' => $listing_product_price,
                        'listing_product_price_list' => $listing_product_price_list,
                        'listing_discount_price' => $listing_discount_price,
                        'listing_product_special_price' => $listing_product_special_price,
                        'listing_base_product_price' => $listing_base_product_price];


        // Move that ADOdb pointer!
        $listing_result->MoveNext();
    }
}

$smarty->assign(
    ['page_split'         => $listing_split->display_count($aLang['text_display_number_of_products']),
                'display_links' => $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, oos_get_all_get_parameters(array('page', 'info'))),
                'numrows'         => $listing_split->number_of_rows,
    'numpages'         => $listing_split->number_of_pages]
);

$smarty->assign('get_params', $all_get_listing);
$smarty->assign('listing', $aListing);
