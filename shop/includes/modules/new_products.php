<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

if (!is_numeric(MAX_DISPLAY_NEW_PRODUCTS)) return FALSE;

if ( (!isset($nCurrentCategoryID)) || ($nCurrentCategoryID == '0') ) {
	$productstable = $oostable['products'];
	$products_descriptiontable = $oostable['products_description'];
	$specialstable = $oostable['specials'];
	$sql = "SELECT p.products_id, pd.products_name, p.products_image, p.products_tax_class_id, p.products_units_id,
                   p.products_price, p.products_base_price, p.products_base_unit,
				   substring(pd.products_description, 1, 150) AS products_description,
                   IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price
            FROM $productstable p LEFT JOIN
                 $specialstable s ON p.products_id = s.products_id,
                 $products_descriptiontable pd 
            WHERE p.products_status >= '1'
              AND p.products_id = pd.products_id
              AND pd.products_languages_id = '" . intval($nLanguageID) . "'
            ORDER BY p.products_date_added DESC";	
} else {
	$productstable = $oostable['products'];
	$products_descriptiontable = $oostable['products_description'];
	$specialstable = $oostable['specials'];
	$products_to_categoriestable = $oostable['products_to_categories'];
	$categoriestable = $oostable['categories'];
	$sql = "SELECT DISTINCT p.products_id, pd.products_name, p.products_image, p.products_tax_class_id, p.products_units_id,
                   p.products_price, p.products_base_price, p.products_base_unit, 
				   substring(pd.products_description, 1, 150) AS products_description,
                   IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price
            FROM $productstable p LEFT JOIN
                 $specialstable s ON p.products_id = s.products_id,
                 $products_descriptiontable pd, 
				 $products_to_categoriestable p2c,
                 $categoriestable c
            WHERE p.products_id = p2c.products_id
			  AND p2c.categories_id = c.categories_id
			  AND c.parent_id = '" . intval($nCurrentCategoryID) . "'
              AND p.products_status >= '1'			  
              AND p.products_id = pd.products_id
              AND pd.products_languages_id = '" . intval($nLanguageID) . "'
            ORDER BY p.products_date_added DESC";
}
  
$new_products_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_NEW_PRODUCTS);
$aNewProducts = array();

while ($new_products = $new_products_result->fields) {

	$new_product_price = '';
	$new_product_special_price = '';
	$new_product_discount_price = '';
	$new_base_product_price = '';
	$new_base_product_special_price = '';
	$new_special_price = '';

	$new_product_units = UNITS_DELIMITER . $products_units[$new_products['products_units_id']];

	$new_product_price = $oCurrencies->display_price($new_products['products_price'], oos_get_tax_rate($new_products['products_tax_class_id']));
	$new_special_price = $new_products['specials_new_products_price'];

    if (oos_is_not_null($new_special_price)) {
		$new_product_special_price = $oCurrencies->display_price($new_special_price, oos_get_tax_rate($new_products['products_tax_class_id']));
    } 

    if ($new_products['products_base_price'] != 1) {
		$new_base_product_price = $oCurrencies->display_price($new_products['products_price'] * $new_products['products_base_price'], oos_get_tax_rate($new_products['products_tax_class_id']));

		if ($new_special_price != '') {
			$new_base_product_special_price = $oCurrencies->display_price($new_special_price * $new_products['products_base_price'], oos_get_tax_rate($new_products['products_tax_class_id']));
		}
    }

	$aNewProducts[] = array('products_id' => $new_products['products_id'],
                                  'products_image' => $new_products['products_image'],
                                  'products_name' => $new_products['products_name'],
                                  'products_description' => oos_remove_tags($new_products['products_description']),
                                  'products_base_price' => $new_products['products_base_price'],
                                  'products_base_unit' => $new_products['products_base_unit'],
                                  'new_product_units' => $new_product_units,
                                  'new_product_price' => $new_product_price,
                                  'new_product_special_price' => $new_product_special_price,
                                  'new_product_discount_price' => $new_product_discount_price,
                                  'new_base_product_price' => $new_base_product_price,
                                  'new_base_product_special_price' => $new_base_product_special_price,
                                  'new_special_price' => $new_special_price);

    // Move that ADOdb pointer!
	$new_products_result->MoveNext();
}

// assign Smarty variables;
$smarty->assign(
	array(
		'block_heading_new_products' => sprintf($aLang['table_heading_new_products'], strftime('%B')),
		'new_products_array' => $aNewProducts
	)
);

