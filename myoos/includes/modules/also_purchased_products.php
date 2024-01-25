<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: also_purchased_products.php,v 1.21 2003/02/12 23:55:58 hpdl
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

$aPurchased = [];

if (isset($_GET['products_id']) && is_numeric(MAX_DISPLAY_ALSO_PURCHASED)) {
    if (!isset($nProductsID)) {
        $products_id = filter_string_polyfill(filter_input(INPUT_GET, 'products_id'));
        $nProductsID = oos_get_product_id($products_id);
    }

    $orders_productstable = $oostable['orders_products'];
    $orderstable = $oostable['orders'];
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $sql = "SELECT p.products_id, p.products_image, pd.products_name
            FROM $orders_productstable opa,
                 $orders_productstable opb,
                 $orderstable o,
                 $productstable p LEFT JOIN
				 $products_descriptiontable pd ON p.products_id = pd.products_id
            WHERE opa.products_id = '" . intval($nProductsID) . "'
              AND opa.orders_id = opb.orders_id
              AND opb.products_id != '" . intval($nProductsID) . "'
              AND opb.products_id = p.products_id
              AND opb.orders_id = o.orders_id
              AND p.products_setting = '2'
			  AND pd.products_languages_id = '" . intval($nLanguageID) . "'
            GROUP BY p.products_id
            ORDER BY o.date_purchased DESC";
    $orders_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_ALSO_PURCHASED);

    $num_products_ordered = $orders_result->RecordCount();
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
        while ($orders = $orders_result->fields) {
            $aPurchased[] = ['products_name' => $orders['products_name'], 'products_id' => $orders['products_id'], 'products_image' => $orders['products_image']];

            // Move that ADOdb pointer!
            $orders_result->MoveNext();
        }
    }
}
