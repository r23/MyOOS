<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

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

if (!$oEvent->installed_plugin('featured')) {
    return false;
}
if (!is_numeric(MAX_DISPLAY_FEATURED_PRODUCTS)) {
    return false;
}

$productstable = $oostable['products'];
$products_descriptiontable = $oostable['products_description'];
$featuredtable = $oostable['featured'];
$sql = "SELECT p.products_id, p.products_image, p.products_price, p.products_price_list, p.products_tax_class_id,
                 p.products_units_id, p.products_base_price, p.products_base_unit, 
				 p.products_quantity_order_min, p.products_quantity_order_max,
				 p.products_product_quantity, pd.products_name,
                 pd.products_short_description
          FROM $productstable p,
               $products_descriptiontable pd,
               $featuredtable f
          WHERE p.products_setting = '2' 
            AND f.products_id = p.products_id
            AND p.products_id = pd.products_id
            AND pd.products_languages_id = '" . intval($nLanguageID) . "'
            AND f.status = '1'
          ORDER BY f.featured_date_added DESC";
$featured_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_FEATURED_PRODUCTS);

// MIN_DISPLAY_FEATURED
if ($featured_result->RecordCount() >= 1) {
    $aFeatured = [];
    while ($featured = $featured_result->fields) {
        $featured_product_price = null;
        $featured_price_list = null;
        $featured_product_special_price = null;
        $featured_base_product_price = null;
        $featured_cross_out_price  = null;
        $featured_until = null;
        $featured_new_cross_out_price = null;


        if ($aUser['show_price'] == 1) {
            $base_product_price = $featured['products_price'];
            $featured_product_price = $oCurrencies->display_price($featured['products_price'], oos_get_tax_rate($featured['products_tax_class_id']));

            if ($featured['products_price_list'] > 0) {
                $featured_price_list = $oCurrencies->display_price($featured['products_price_list'], oos_get_tax_rate($featured['products_tax_class_id']));
            }

            $featured_special = oos_get_products_special($featured['products_id']);
            if ($featured_special['specials_new_products_price'] > 0) {
                $base_product_price = $featured_special['specials_new_products_price'];
                $featured_product_special_price = $oCurrencies->display_price($featured_special['specials_new_products_price'], oos_get_tax_rate($featured['products_tax_class_id']));

                if ($featured_special['specials_cross_out_price'] > 0) {
                    $featured_cross_out_price = $oCurrencies->display_price($featured_special['specials_cross_out_price'], oos_get_tax_rate($featured['products_tax_class_id']));
                }

                $featured_until = sprintf($aLang['only_until'], oos_date_short($featured_special['expires_date']));
            }

            if ($featured['products_base_price'] != 1) {
                $featured_base_product_price = $oCurrencies->display_price($base_product_price * $featured['products_base_price'], oos_get_tax_rate($featured['products_tax_class_id']));
            }
        }

        $order_min = number_format($featured['products_quantity_order_min']);
        $order_max = number_format($listing['products_quantity_order_max']);

        $aCategoryPath = [];
        $aCategoryPath = oos_get_category_path($featured['products_id']);

        $aFeatured[] = ['products_id' => $featured['products_id'], 'products_image' => $featured['products_image'], 'products_name' => $featured['products_name'], 'products_short_description' => $featured['products_short_description'], 'products_path' => $aCategoryPath['path'], 'categories_name' => $aCategoryPath['name'], 'order_min' => $order_min, 'order_max' => $order_max, 'product_quantity' => $featured['products_product_quantity'], 'products_base_price' => $featured['products_base_price'], 'products_base_unit' => $featured['products_base_unit'], 'products_units' => $featured['products_units_id'], 'featured_until' => $featured_until, 'featured_cross_out_price'    => $featured_cross_out_price, 'featured_product_price_list' => $featured_price_list, 'featured_product_price' => $featured_product_price, 'featured_product_special_price' => $featured_product_special_price, 'featured_base_product_price' => $featured_base_product_price];
        // Move that ADOdb pointer!
        $featured_result->MoveNext();
    }

    $smarty->assign('featured', $aFeatured);
}
