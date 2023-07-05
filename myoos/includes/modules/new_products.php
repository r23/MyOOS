<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: new_products.php,v 1.2 2003/01/09 09:40:08 elarifr
   orig: new_products.php,v 1.33 2003/02/12 23:55:58 hpdl
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

if (!is_numeric(MAX_DISPLAY_NEW_PRODUCTS)) {
    return false;
}

use Carbon\Carbon;

Carbon::setLocale(LANG);


if ((!isset($nCurrentCategoryID)) || ($nCurrentCategoryID == 0)) {
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $specialstable = $oostable['specials'];
    $sql = "SELECT p.products_id, pd.products_name, p.products_image,  pd.products_short_description, p.products_tax_class_id, p.products_units_id,
                   p.products_price, p.products_price_list, p.products_base_price, p.products_base_unit, p.products_product_quantity, 
					p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, 
					p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty,					   
				   p.products_quantity_order_min, p.products_quantity_order_max,
                   IF(s.status, s.specials_new_products_price, null) AS specials_new_products_price,
                   IF(s.status, s.specials_cross_out_price, null) AS specials_cross_out_price,				   
				   IF(s.status, s.expires_date, null) AS expires_date
            FROM $productstable p LEFT JOIN
                 $specialstable s ON p.products_id = s.products_id,
                 $products_descriptiontable pd 
            WHERE p.products_setting = '2'
              AND p.products_id = pd.products_id
              AND pd.products_languages_id = '" . intval($nLanguageID) . "'
            ORDER BY p.products_date_added DESC";
} else {
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $specialstable = $oostable['specials'];
    $products_to_categoriestable = $oostable['products_to_categories'];
    $categoriestable = $oostable['categories'];
    $sql = "SELECT DISTINCT p.products_id, pd.products_name, pd.products_short_description, p.products_image, p.products_tax_class_id, p.products_units_id,
                   p.products_price, p.products_price_list, p.products_base_price, p.products_base_unit, p.products_product_quantity,
					p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, 
					p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty,	
				   p.products_quantity_order_min, p.products_quantity_order_max,
                   IF(s.status, s.specials_new_products_price, null) AS specials_new_products_price,
                   IF(s.status, s.specials_cross_out_price, null) AS specials_cross_out_price,			   
				   IF(s.status, s.expires_date, null) AS expires_date				   
            FROM $productstable p LEFT JOIN
                 $specialstable s ON p.products_id = s.products_id,
                 $products_descriptiontable pd, 
				 $products_to_categoriestable p2c,
                 $categoriestable c
            WHERE p.products_id = p2c.products_id
			  AND p2c.categories_id = c.categories_id
			  AND c.parent_id = '" . intval($nCurrentCategoryID) . "'
              AND p.products_setting = '2'			  
              AND p.products_id = pd.products_id
              AND pd.products_languages_id = '" . intval($nLanguageID) . "'
            ORDER BY p.products_date_added DESC";
}

$new_products_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_NEW_PRODUCTS);
$aNewProducts = [];

while ($new_products = $new_products_result->fields) {
    $discount = null;

    $new_product_price = null;
    $new_product_price_list = null;
    $new_product_special_price = null;
    $new_product_discount_price = null;
    $new_base_product_price = null;
    $new_special_price = null;
    $new_special_cross_out_price  = null;
    $only_until = null;

    if ($aUser['show_price'] == 1) {
        $base_product_price = $new_products['products_price'];

        $new_product_price = $oCurrencies->display_price($new_products['products_price'], oos_get_tax_rate($new_products['products_tax_class_id']));

        if ($new_products['products_price_list'] > 0) {
            $new_product_price_list = $oCurrencies->display_price($new_products['products_price_list'], oos_get_tax_rate($new_products['products_tax_class_id']));
        }

        if ($new_products['products_discount4'] > 0) {
            $discount = $new_products['products_discount4'];
        } elseif ($new_products['products_discount3'] > 0) {
            $discount = $new_products['products_discount3'];
        } elseif ($new_products['products_discount2'] > 0) {
            $discount = $new_products['products_discount2'];
        } elseif ($new_products['products_discount1'] > 0) {
            $discount = $new_products['products_discount1'];
        }

        if ($discount > 0) {
            $base_product_price = $discount;
            $new_product_discount_price = $oCurrencies->display_price($discount, oos_get_tax_rate($new_products['products_tax_class_id']));
        }

        $new_special_price = $new_products['specials_new_products_price'];

        if ($new_products['specials_new_products_price'] > 0) {
            $base_product_price = $new_products['specials_new_products_price'];
            $new_product_special_price = $oCurrencies->display_price($new_products['specials_new_products_price'], oos_get_tax_rate($new_products['products_tax_class_id']));

            if ($new_products['specials_cross_out_price'] > 0) {
                $new_special_cross_out_price = $oCurrencies->display_price($new_products['specials_cross_out_price'], oos_get_tax_rate($new_products['products_tax_class_id']));
            }

            $only_until = sprintf($aLang['only_until'], oos_date_short($new_products['expires_date']));
        }

        if ($new_products['products_base_price'] != 1) {
            $new_base_product_price = $oCurrencies->display_price($base_product_price * $new_products['products_base_price'], oos_get_tax_rate($new_products['products_tax_class_id']));
        }
    }

    $order_min = number_format($new_products['products_quantity_order_min']);
    $order_max = number_format($new_products['products_quantity_order_max']);

    $aCategoryPath = [];
    $aCategoryPath = oos_get_category_path($new_products['products_id']);



    $aNewProducts[] = ['products_id' => $new_products['products_id'],
                        'products_image' => $new_products['products_image'],
                        'products_name' => $new_products['products_name'],
                        'products_short_description' => $new_products['products_short_description'],
                        'products_path' => $aCategoryPath['path'],
                        'categories_name' => $aCategoryPath['name'],
                        'order_min' => $order_min,
                        'order_max' => $order_max,
                        'products_product_quantity' => $new_products['products_product_quantity'],
                        'products_base_price' => $new_products['products_base_price'],
                        'products_base_unit' => $new_products['products_base_unit'],
                        'new_product_units' => $new_products['products_units_id'],
                        'new_product_price' => $new_product_price,
                        'new_product_price_list' => $new_product_price_list,
                        'new_product_special_price' => $new_product_special_price,
                        'new_special_cross_out_price' => $new_special_cross_out_price,
                        'expires_date'  => $new_products['expires_date'],
                        'only_until' => $only_until,
                        'new_product_discount_price' => $new_product_discount_price,
                        'new_base_product_price' => $new_base_product_price,
                        'new_special_price' => $new_special_price];

    // Move that ADOdb pointer!
    $new_products_result->MoveNext();
}


$m = date("n");
$monthName = (new Carbon())->setMonth($m)->isoFormat('MMMM');

// assign Smarty variables;
$smarty->assign(
    array(
        'block_heading_new_products' => sprintf($aLang['table_heading_new_products'], $monthName),
        'new_products_array' => $aNewProducts
    )
);
