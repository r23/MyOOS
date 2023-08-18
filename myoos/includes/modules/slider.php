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

$productstable = $oostable['products'];
$products_descriptiontable = $oostable['products_description'];
$slidertable = $oostable['categories_slider'];
$sql = "SELECT p.products_id, s.slider_image, p.products_price, p.products_price_list, p.products_tax_class_id,
                 p.products_units_id, p.products_base_price, p.products_base_unit, 
				 p.products_quantity_order_min, p.products_quantity_order_max,
				 p.products_product_quantity, pd.products_name,
                 pd.products_short_description
          FROM $productstable p,
               $products_descriptiontable pd,
               $slidertable s
          WHERE p.products_setting = '2' 
            AND s.products_id = p.products_id
            AND p.products_id = pd.products_id
            AND pd.products_languages_id = '" . intval($nLanguageID) . "'
            AND s.status = '1'
          ORDER BY s.slider_date_added DESC";
$slider_result = $dbconn->Execute($sql);

if ($slider_result->RecordCount() >= 1) {
    $aSlider = [];
    while ($slider = $slider_result->fields) {
        $slider_product_price = null;
        $slider_price_list = null;
        $slider_product_special_price = null;
        $slider_base_product_price = null;
        $slider_cross_out_price  = null;
        $slider_until = null;
        $slider_new_cross_out_price = null;


        if ($aUser['show_price'] == 1) {
            $base_product_price = $slider['products_price'];
            $slider_product_price = $oCurrencies->display_price($slider['products_price'], oos_get_tax_rate($slider['products_tax_class_id']));

            if ($slider['products_price_list'] > 0) {
                $slider_price_list = $oCurrencies->display_price($slider['products_price_list'], oos_get_tax_rate($slider['products_tax_class_id']));
            }

            $slider_special = oos_get_products_special($slider['products_id']);
            if ($slider_special['specials_new_products_price'] > 0) {
                $base_product_price = $slider_special['specials_new_products_price'];
                $slider_product_special_price = $oCurrencies->display_price($slider_special['specials_new_products_price'], oos_get_tax_rate($slider['products_tax_class_id']));

                if ($slider_special['specials_cross_out_price'] > 0) {
                    $slider_cross_out_price = $oCurrencies->display_price($slider_special['specials_cross_out_price'], oos_get_tax_rate($slider['products_tax_class_id']));
                }

                $slider_until = sprintf($aLang['only_until'], oos_date_short($slider_special['expires_date']));
            }

            if ($slider['products_base_price'] != 1) {
                $slider_base_product_price = $oCurrencies->display_price($base_product_price * $slider['products_base_price'], oos_get_tax_rate($slider['products_tax_class_id']));
            }
        }

        $order_min = number_format($slider['products_quantity_order_min']);
        $order_max = number_format($listing['products_quantity_order_max']);

        $aCategoryPath = [];
        $aCategoryPath = oos_get_category_path($slider['products_id']);

        $aSlider[] = ['products_id' => $slider['products_id'], 'slider_image' => $slider['slider_image'], 'products_name' => $slider['products_name'], 'products_short_description' => $slider['products_short_description'], 'products_path' => $aCategoryPath['path'], 'categories_name' => $aCategoryPath['name'], 'order_min' => $order_min, 'order_max' => $order_max, 'product_quantity' => $slider['products_product_quantity'], 'products_base_price' => $slider['products_base_price'], 'products_base_unit' => $slider['products_base_unit'], 'products_units' => $slider['products_units_id'], 'slider_until' => $slider_until, 'slider_cross_out_price'    => $slider_cross_out_price, 'slider_product_price_list' => $slider_price_list, 'slider_product_price' => $slider_product_price, 'slider_product_special_price' => $slider_product_special_price, 'slider_base_product_price' => $slider_base_product_price];
        // Move that ADOdb pointer!
        $slider_result->MoveNext();
    }

    $smarty->assign('slider', $aSlider);
}
