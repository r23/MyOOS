<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2021 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: shopping_cart.php,v 1.71 2003/02/14 05:51:28 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/shopping_cart.php';

$hidden_field = '';
$any_out_of_stock = 0;
$order_total_output = array();

if (isset($_SESSION)) { 

	if (is_object($_SESSION['cart'])) {
		if ($_SESSION['cart']->count_contents() > 0) {
			
			// Add Shipping Cost
			require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';
			require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_shipping.php';
			require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/modules/order_total/ot_shipping.php';
		
			// if no shipping destination address was selected, use the customers own address as default
			if (!isset($_SESSION['sendto'])) {
				$_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
			} else {
				// verify the selected shipping address
				$address_booktable = $oostable['address_book'];
				$sql = "SELECT COUNT(*) AS total
						FROM $address_booktable
						WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
						AND address_book_id = '" . intval($_SESSION['sendto']) . "'";
				$check_address_result = $dbconn->Execute($sql);
				$check_address = $check_address_result->fields;

				if ($check_address['total'] != '1') {
					$_SESSION['sendto'] = intval($_SESSION['customer_default_address_id']);
				}
			}
			
			$content_type = $_SESSION['cart']->get_content_type();
			$total_weight = $_SESSION['cart']->info['weight'];
			$total_count = $_SESSION['cart']->count_contents();
			$subtotal = $_SESSION['cart']->info['subtotal'];
			
			// if the order contains only virtual products
			if (($content_type == 'virtual') || ($_SESSION['cart']->show_total() == 0)) {
				$_SESSION['shipping'] = false;
				$_SESSION['sendto'] = false;
				$free_shipping = true;
			}

			$shipping = isset($_SESSION['shipping']['id']) ? oos_prepare_input($_SESSION['shipping']['id']) : DEFAULT_SHIPPING_METHOD . '_' . DEFAULT_SHIPPING_METHOD;
			list($module, $method) = explode('_', $shipping);
	
			// load all enabled shipping modules
			require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shipping.php';
			$shipping_modules = new shipping($module);
			
			// shipping quotes
			$quote = $shipping_modules->quote($method, $module);

			if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {		
				$_SESSION['shipping'] = array('id' => $quote[0]['id'] . '_' . $quote[0]['methods'][0]['id'],
											'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module']), 
                                            'cost' => $quote[0]['methods'][0]['cost']);
			}
					
			// load all enabled order total modules
			require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order_total.php';
			$order_total_modules = new order_total;
			$order_total_modules->shopping_cart_process();
			$order_total_output = $order_total_modules->output();

			/*
			 * Shopping Cart
			*/
			$products = $_SESSION['cart']->get_products();

			$n = count($products);
			for ($i=0, $n; $i<$n; $i++) {

				$hidden_field .= oos_draw_hidden_field('products_id[]', $products[$i]['id']);

				// Display marker if stock quantity insufficient
				if (STOCK_CHECK == 'true') {
					$stock_left = $products[$i]['stock'] - $products[$i]['quantity'];
					if ($stock_left < 0) {					
						$any_out_of_stock = 1;
					}
				}			

				// Wishlist names
				if (oos_is_not_null($products[$i]['towlid'])) {
					$hidden_field .= oos_draw_hidden_field('to_wl_id[]', $products[$i]['towlid']);
				}

				// Push all attributes information in an array
				if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
					foreach($products[$i]['attributes'] as $option => $value) {
						$products_id = oos_get_product_id($products[$i]['id']);

						$products_optionstable = $oostable['products_options'];
						$products_options_valuestable = $oostable['products_options_values'];
						$products_attributestable = $oostable['products_attributes'];

						if ($value == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
							$sql = "SELECT popt.products_options_name,
										pa.options_values_price, pa.price_prefix
									FROM $products_optionstable popt,
										$products_attributestable pa
									WHERE pa.products_id = '" . intval($products_id) . "'
										AND pa.options_id = popt.products_options_id
										AND pa.options_id = '" . oos_db_input($option) . "'
										AND popt.products_options_languages_id = '" . intval($nLanguageID) . "'";
						} else {
							$sql = "SELECT popt.products_options_name,
										poval.products_options_values_name,
										pa.options_values_price, pa.price_prefix
									FROM $products_optionstable popt,
										$products_options_valuestable poval,
										$products_attributestable pa
									WHERE pa.products_id = '" . intval($products_id) . "'
										AND pa.options_id = '" . oos_db_input($option) . "'
										AND pa.options_id = popt.products_options_id
										AND pa.options_values_id = '" . oos_db_input($value) . "'
										AND pa.options_values_id = poval.products_options_values_id
										AND popt.products_options_languages_id = '" . intval($nLanguageID) . "'
										AND poval.products_options_values_languages_id = '" .  intval($nLanguageID) . "'";
						}
						$attributes_values = $dbconn->GetRow($sql);

						if ($value == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
							$hidden_field .=  oos_draw_hidden_field('id[' . $products[$i]['id'] . '][' . TEXT_PREFIX . $option . ']',  $products[$i]['attributes_values'][$option]);
							$attr_value = $products[$i]['attributes_values'][$option];
						} else {
							$hidden_field .= oos_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
							$attr_value = $attributes_values['products_options_values_name'];
						}

						$attr_price = $attributes_values['options_values_price'];

						$products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
						$products[$i][$option]['options_values_id'] = $value;
						$products[$i][$option]['products_options_values_name'] = $attr_value;
						$products[$i][$option]['options_values_price'] = $attr_price;
						$products[$i][$option]['price_prefix'] = $attributes_values['price_prefix']; 						
						
					}
				}
			}  
		}
	}
}


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['shopping_cart']));
$sCanonical = oos_href_link($aContents['shopping_cart'], '', false, true);
  
$aTemplate['page'] = $sTheme . '/page/shopping_cart.html';

$nPageType = OOS_PAGE_TYPE_CATALOG;
$sPagetitle = $aLang['heading_title_cart'] . ' ' . OOS_META_TITLE;


require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// assign Smarty variables;
$smarty->assign(
      array(
		'breadcrumb'	=> $oBreadcrumb->trail(),
		'heading_title'	=> $aLang['heading_title_cart'],
		'robots'		=> 'noindex,follow,noodp,noydir',
		'cart_active' 	=> 1,
		'canonical'		=> $sCanonical,
			
		'hidden_field'			=> $hidden_field,
		'products'				=> $products,
		'any_out_of_stock'		=> $any_out_of_stock,
		'order_total_output'	=> $order_total_output,
       )
);


// display the template
$smarty->display($aTemplate['page']);
