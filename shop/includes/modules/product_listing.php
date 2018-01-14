<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$nPage = isset($_GET['page']) ? $_GET['page']+0 : 1;

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';   
include_once MYOOS_INCLUDE_PATH . '/includes/functions/function_listing.php';

$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS);

/*
    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = oos_create_sort_heading($_GET['sort'], $col+1, $lc_text);
    }
*/
if ($listing_split->number_of_rows > 0) {
    if (!isset($all_get_listing)) $all_get_listing = oos_get_all_get_parameters(array('action'));

	$aListing = array();
    $listing_result = $dbconn->Execute($listing_split->sql_query);
    while ($listing = $listing_result->fields) {

/*
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_text = '&nbsp;<a href="' . oos_href_link($aContents['shop'], 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;
*/

		$discount = NULL;

		$listing_product_price = NULL;
		$listing_product_special_price = NULL;
		$listing_base_product_price = NULL;
		$listing_base_product_special_price = NULL;
		$listing_special_price = NULL;

		if ($aUser['show_price'] == 1 ) {
			$listing_units = UNITS_DELIMITER . $products_units[$listing['products_units_id']];

			$listing_product_price = $oCurrencies->display_price($listing['products_price'], oos_get_tax_rate($listing['products_tax_class_id']));
			
            if ( $listing['products_discount4'] > 0 ) {
				$discount = $listing['products_discount4'];
            } elseif ( $listing['products_discount3'] > 0 ) {
				$discount = $listing['products_discount3'];
            } elseif ( $listing['products_discount2'] > 0 ) {
				$discount = $listing['products_discount2'];
            } elseif ( $listing['products_discount1'] > 0 ) {
				$discount = $listing['products_discount1'];
            }

            if ( $discount > 0 ) {
				$listing_discount_price = $oCurrencies->display_price($discount, oos_get_tax_rate($listing['products_tax_class_id']));
            } 			

			if (oos_is_not_null($listing['specials_new_products_price'])) {
				$listing_special_price = $listing['specials_new_products_price'];
				$listing_product_special_price = $oCurrencies->display_price($listing['specials_new_products_price'], oos_get_tax_rate($listing['products_tax_class_id']));
			} 

			if ($listing['products_base_price'] != 1) {
				$listing_base_product_price = $oCurrencies->display_price($listing['products_price'] * $listing['products_base_price'], oos_get_tax_rate($listing['products_tax_class_id']));

				if ($listing['specials_new_products_price'] != NULL) {
					$listing_base_product_special_price = $oCurrencies->display_price($listing['specials_new_products_price'] * $listing['products_base_price'], oos_get_tax_rate($listing['products_tax_class_id']));
				}
			}	  
		}			

		if (DECIMAL_CART_QUANTITY == 'true') {
			$order_min = number_format($listing['products_quantity_order_min'], 2);
		} else {
			$order_min = number_format($listing['products_quantity_order_min']);
		}
   
		$aListing[] = array('products_id' => $listing['products_id'],
						'products_image' => $listing['products_image'],
						'products_name' => $listing['products_name'],
						'products_model' => $listing['products_model'],
						'products_description' => oos_remove_tags($listing['products_description']),
						'manufacturers_id' => $listing['manufacturers_id'],
						'manufacturers_name' =>	$listing['manufacturers_name'],				   
						'order_min' => $order_min,					   
						'products_base_price' => $listing['products_base_price'],
						'products_base_unit' => $listing['products_base_unit'],
						'products_units' => $listing_units,
						'listing_product_price' => $listing_product_price,
						'listing_discount_price' => $listing_discount_price,
						'listing_product_special_price' => $listing_product_special_price,
						'listing_base_product_price' => $listing_base_product_price,
						'listing_base_product_special_price' => $listing_base_product_special_price,
						'listing_special_price' => $listing_special_price);			   
			   

      // Move that ADOdb pointer!
      $listing_result->MoveNext();
    }

}

$smarty->assign(array('page_split' 		=> $listing_split->display_count($aLang['text_display_number_of_products']),
                        'display_links' => $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, oos_get_all_get_parameters(array('page', 'info'))),
						'numrows' 		=> $listing_split->number_of_rows,
                        'numpages' 		=> $listing_split->number_of_pages));
						
$smarty->assign('get_params', $all_get_listing);
$smarty->assign('listing', $aListing);

