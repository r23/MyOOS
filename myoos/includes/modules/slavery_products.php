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

// create column list
$aDefineList = [];
$aDefineList = ['PRODUCT_LIST_MODEL' => '1', 'PRODUCT_LIST_NAME' => '2', 'PRODUCT_LIST_MANUFACTURER' => '3', 'PRODUCT_LIST_PRICE' => '4', 'PRODUCT_LIST_QUANTITY' => '5', 'PRODUCT_LIST_WEIGHT' => '6', 'PRODUCT_LIST_IMAGE' => '7', 'PRODUCT_SLAVE_BUY_NOW' => '8'];
asort($aDefineList);

$column_list = [];
reset($aDefineList);
foreach ($aDefineList as $column => $value) {
    if ($value) {
        $column_list[] = $column;
    }
}

$select_column_list = '';

for ($col = 0, $n = count($column_list); $col < $n; $col++) {
    if (($column_list[$col] == 'PRODUCT_SLAVE_BUY_NOW')
        || ($column_list[$col] == 'PRODUCT_LIST_PRICE')
    ) {
        continue;
    }
    if (oos_is_not_null($select_column_list)) {
        $select_column_list .= ', ';
    }

    match ($column_list[$col]) {
        'PRODUCT_LIST_MODEL' => $select_column_list .= 'p.products_model',
        'PRODUCT_LIST_NAME' => $select_column_list .= 'pd.products_name',
        'PRODUCT_LIST_MANUFACTURER' => $select_column_list .= 'm.manufacturers_name',
        'PRODUCT_LIST_QUANTITY' => $select_column_list .= 'p.products_quantity',
        'PRODUCT_LIST_IMAGE' => $select_column_list .= 'p.products_image',
        'PRODUCT_LIST_WEIGHT' => $select_column_list .= 'p.products_weight',
        default => $select_column_list .= "pd.products_name",
    };
}

if (oos_is_not_null($select_column_list)) {
    $select_column_list .= ', ';
}

if (!isset($nProductsID)) {
    $products_id = filter_string_polyfill(filter_input(INPUT_GET, 'products_id'));
    $nProductsID = oos_get_product_id($products_id);
}

$productstable = $oostable['products'];
$products_to_mastertable = $oostable['products_to_master'];
$products_descriptiontable = $oostable['products_description'];
$manufacturerstable = $oostable['manufacturers'];
$specialstable = $oostable['specials'];
$listing_sql = "SELECT " . $select_column_list . " p.products_id, p.products_replacement_product_id, p.manufacturers_id,
                         p.products_price, p.products_price_list, p.products_base_price, p.products_base_unit,
						 p.products_quantity_order_min, p.products_quantity_order_max, p.products_product_quantity,
                         p.products_discount1, p.products_discount2,
                         p.products_discount3, p.products_discount4, p.products_discount1_qty,
                         p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty,
                         p.products_tax_class_id, p.products_units_id, 
                         IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
						IF(s.status, s.specials_cross_out_price, null) AS specials_cross_out_price,			   
						IF(s.status, s.expires_date, null) AS expires_date,							 
                         IF(s.status, s.specials_new_products_price, p.products_price) AS final_price,
                         pm.master_id, pm.slave_id
                  FROM $productstable p LEFT JOIN
                       $manufacturerstable m ON p.manufacturers_id = m.manufacturers_id LEFT JOIN
                       $specialstable s ON p.products_id = s.products_id,
                       $products_to_mastertable pm,
                       $products_descriptiontable pd
                  WHERE p.products_setting = '2'
                    AND pd.products_id = p.products_id
                    AND pd.products_languages_id = '" . intval($nLanguageID) . "'
                    AND p.products_id = pm.slave_id AND
                      pm.master_id = '" . intval($nProductsID) . "'";

if ((!isset($_GET['sort'])) || (!preg_match('/[1-8][ad]/', (string) $_GET['sort'])) || (substr((string) $_GET['sort'], 0, 1) > count($column_list))) {
    for ($col = 0, $n = count($column_list); $col < $n; $col++) {
        if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
            $_GET['sort'] = $col + 1 . 'a';
            $listing_sql .= " ORDER BY pd.products_name";
            break;
        }
    }
} else {
    $sort_col = substr((string) $_GET['sort'], 0, 1);
    $sort_order = substr((string) $_GET['sort'], 1);
    $listing_sql .= ' ORDER BY ';

    match ($column_list[$sort_col - 1]) {
        'PRODUCT_LIST_MODEL' => $listing_sql .= "p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name",
        'PRODUCT_LIST_NAME' => $listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : ''),
        'PRODUCT_LIST_MANUFACTURER' => $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name",
        'PRODUCT_LIST_QUANTITY' => $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name",
        'PRODUCT_LIST_IMAGE' => $listing_sql .= "pd.products_name",
        'PRODUCT_LIST_WEIGHT' => $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name",
        'PRODUCT_LIST_PRICE' => $listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name",
        default => $listing_sql .= "pd.products_name",
    };
}


$aOption['slavery_products'] = $sTheme . '/products/_slavery_product_listing.html';
$aOption['slavery_page_navigation'] = $sTheme . '/system/_pagination.htm';

require_once MYOOS_INCLUDE_PATH . '/includes/modules/product_listing.php';

$smarty->assign('slavery_products', $smarty->fetch($aOption['slavery_products']));
