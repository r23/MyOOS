<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: whats_new.php,v 1.2 2003/01/09 09:40:07 elarifr
   orig: whats_new.php,v 1.31 2003/02/10 22:31:09 hpdl
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

$whats_new_block = false;

$productstable = $oostable['products'];
$query = "SELECT products_id, products_image, products_tax_class_id, products_units_id, products_price,
				products_discount1, products_discount2,
				products_discount3, products_discount4, products_discount1_qty,
				products_discount2_qty, products_discount3_qty, products_discount4_qty,
				products_price_list, products_base_price, products_base_unit, products_product_quantity
            FROM $productstable
            WHERE products_setting = '2'
            ORDER BY products_date_added DESC";
if ($random_product = oos_random_select($query, MAX_RANDOM_SELECT_NEW)) {
    $whats_new_block = true;

    $random_product['products_name'] = oos_get_products_name($random_product['products_id']);

    $discount = null;

    $whats_new_product_price = null;
    $whats_new_price_list = null;
    $whats_new_product_special_price = null;
    $whats_new_product_discount_price = null;
    $whats_new_base_product_price = null;
    $whats_new_cross_out_price = null;
    $whats_new_until = null;
    $base_product_price = null;

    if ($aUser['show_price'] == 1) {
        $base_product_price = $random_product['products_price'];
        $whats_new_special = oos_get_products_special($random_product['products_id']);
        $whats_new_product_price = $oCurrencies->display_price($random_product['products_price'], oos_get_tax_rate($random_product['products_tax_class_id']));

        if ($random_product['products_price_list'] > 0) {
            $whats_new_price_list = $oCurrencies->display_price($random_product['products_price_list'], oos_get_tax_rate($random_product['products_tax_class_id']));
        }

        if ($random_product['products_discount4'] > 0) {
            $discount = $random_product['products_discount4'];
        } elseif ($random_product['products_discount3'] > 0) {
            $discount = $random_product['products_discount3'];
        } elseif ($random_product['products_discount2'] > 0) {
            $discount = $random_product['products_discount2'];
        } elseif ($random_product['products_discount1'] > 0) {
            $discount = $random_product['products_discount1'];
        }

        if ($discount > 0) {
            $base_product_price = $discount;
            $whats_new_product_discount_price = $oCurrencies->display_price($discount, oos_get_tax_rate($random_product['products_tax_class_id']));
        }


        if ($whats_new_special['specials_new_products_price'] > 0) {
            $base_product_price = $whats_new_special['specials_new_products_price'];
            $whats_new_product_special_price = $oCurrencies->display_price($whats_new_special['specials_new_products_price'], oos_get_tax_rate($random_product['products_tax_class_id']));

            if ($whats_new_special['specials_cross_out_price'] > 0) {
                $whats_new_cross_out_price = $oCurrencies->display_price($whats_new_special['specials_cross_out_price'], oos_get_tax_rate($random_product['products_tax_class_id']));
            }

            $whats_new_until = sprintf($aLang['only_until'], oos_date_short($whats_new_special['expires_date']));
        }

        if ($random_product['products_base_price'] != 1) {
            $whats_new_base_product_price = $oCurrencies->display_price($base_product_price * $random_product['products_base_price'], oos_get_tax_rate($random_product['products_tax_class_id']));
        }
    }
    $smarty->assign(
        array(
            'whats_new_product_special_price'    => $whats_new_product_special_price,
            'whats_new_product_discount_price'    => $whats_new_product_discount_price,
            'whats_new_base_product_price'        => $whats_new_base_product_price,
            'whats_new_product_price'            => $whats_new_product_price,
            'whats_new_product_price_list'         => $whats_new_price_list,
            'whats_new_cross_out_price'            => $whats_new_cross_out_price,
            'whats_new_until'                    => $whats_new_until,

            'random_product'          => $random_product,
            'block_heading_whats_new' => $block_heading
        )
    );
}

$smarty->assign('whats_new_block', $whats_new_block);
