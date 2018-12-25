<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

if (!$oEvent->installed_plugin('featured')) return FALSE;
if (!is_numeric(MAX_DISPLAY_FEATURED_PRODUCTS)) return FALSE;

$productstable = $oostable['products'];
$products_descriptiontable = $oostable['products_description'];
$featuredtable = $oostable['featured'];
$sql = "SELECT p.products_id, p.products_image, p.products_price, p.products_tax_class_id,
                 p.products_units_id, p.products_base_price, p.products_base_unit, 
				 p.products_quantity_order_min, p.products_quantity_order_max,
				 p.products_product_quantity, pd.products_name,
                 substring(pd.products_description, 1, 150) AS products_description
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
	
    $aFeatured = array();
    while ($featured = $featured_result->fields) {

		$featured_product_price = NULL;
		$featured_product_special_price = NULL;
		$featured_base_product_price = NULL;
		$featured_special_price = NULL;

		if ($aUser['show_price'] == 1 ) {
			$base_product_price = $featured['products_price'];
			
			$featured_product_price = $oCurrencies->display_price($featured['products_price'], oos_get_tax_rate($featured['products_tax_class_id']));
			$featured_special_price = oos_get_products_special_price($featured['products_id']);

			if (oos_is_not_null($featured_special_price)) {
				$base_product_price = $featured_special_price;
				$featured_product_special_price = $oCurrencies->display_price($featured_special_price, oos_get_tax_rate($featured['products_tax_class_id']));
			} 

			if ($featured['products_base_price'] != 1) {
				$featured_base_product_price = $oCurrencies->display_price($base_product_price * $featured['products_base_price'], oos_get_tax_rate($featured['products_tax_class_id']));
			}	  
		}
		
		$order_min = number_format($featured['products_quantity_order_min']);
		$order_max = number_format($listing['products_quantity_order_max']);

		$aFeatured[] = array('products_id' => $featured['products_id'],
                           'products_image' => $featured['products_image'],
                           'products_name' => $featured['products_name'],
                           'products_description' => oos_remove_tags($featured['products_description']),
                           'order_min' => $order_min,
                           'order_max' => $order_max,
						   'product_quantity' => $featured['products_product_quantity'],
                           'products_base_price' => $featured['products_base_price'],
                           'products_base_unit' => $featured['products_base_unit'],
                           'products_units' => $featured['products_units_id'],
                           'featured_product_price' => $featured_product_price,
                           'featured_product_special_price' => $featured_product_special_price,
                           'featured_base_product_price' => $featured_base_product_price);
		// Move that ADOdb pointer!
		$featured_result->MoveNext();
    }

    $smarty->assign('featured', $aFeatured);
}

