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
			$total_weight = $_SESSION['cart']->show_weight();
			$total_count = $_SESSION['cart']->count_contents();
			$subtotal = $_SESSION['cart']->show_subtotal();
			
			// if the order contains only virtual products
			if (($content_type == 'virtual') || ($_SESSION['cart']->show_total() == 0)) {
				$_SESSION['shipping'] = false;
				$_SESSION['sendto'] = false;
				$free_shipping = true;
			}


			$delivery_country_id = isset($_SESSION['delivery_country_id']) ? intval($_SESSION['delivery_country_id']) : STORE_COUNTRY;
			$shipping = isset($_SESSION['shipping']) ? oos_prepare_input($_SESSION['shipping']) : DEFAULT_SHIPPING_METHOD . '_' . DEFAULT_SHIPPING_METHOD;


			// load all enabled shipping modules
			require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shipping.php';
			$shipping_modules = new shipping;


			if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
				switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
					case 'national':
						if ($delivery_country_id == STORE_COUNTRY) $pass = true; break;

					case 'international':
						if ($delivery_country_id != STORE_COUNTRY) $pass = true; break;

					case 'both':
						$pass = true; break;

					default:
						$pass = false; break;
				}

				$free_shipping = false;
				if ( ($pass == true) && ($subtotal >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
					$free_shipping = true;
					$shipping = 'free_free';
					$_SESSION['shipping'] = 'free_free';					
				}
			} else {
				$free_shipping = false;
			}


			if ($shipping == 'free_free') {
				$quote[0]['methods'][0]['title'] = $aLang['free_shipping_title'];
				$quote[0]['methods'][0]['cost'] = '0';
			} else {
				$quote = $shipping_modules->quote(DEFAULT_SHIPPING_METHOD, DEFAULT_SHIPPING_METHOD);
			}

print_r($quote);


// process the selected shipping method
if ( isset($_POST['action']) && ($_POST['action'] == 'process') && 
	( isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) ){	
	
	if ( (oos_count_shipping_modules() > 0) || ($free_shipping == true) ) {
		if ( (isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {
			$_SESSION['shipping'] = oos_prepare_input($_POST['shipping']);

			list($module, $method) = explode('_', $_SESSION['shipping']);
			if ( is_object($$module) || ($_SESSION['shipping'] == 'free_free') ) {
				
				if ($_SESSION['shipping'] == 'free_free') {
					$quote[0]['methods'][0]['title'] = $aLang['free_shipping_title'];
					$quote[0]['methods'][0]['cost'] = '0';
				} else {
					$quote = $shipping_modules->quote($method, $module);
				}
				if (isset($quote['error'])) {
					unset($_SESSION['shipping']);
				} else {
					if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
						$sWay = ''; 
						if (!empty($quote[0]['methods'][0]['title'])) {
							$sWay = ' (' . $quote[0]['methods'][0]['title'] . ')'; 
						}						
						$_SESSION['shipping'] = array('id' => $_SESSION['shipping'],
											'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . $sWay), 
                                            'cost' => $quote[0]['methods'][0]['cost']);

					}
				}
			} else {
				unset($_SESSION['shipping']);
			}
		}
	} else {
		$_SESSION['shipping'] = false;
    }
}

			// get all available shipping quotes
#			$quotes = $shipping_modules->quote();



// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
# if ((!isset($_SESSION['shipping']) || (!isset($_SESSION['shipping']['id']) || $_SESSION['shipping']['id'] == '') && oos_count_shipping_modules() >= 1)) $_SESSION['shipping'] = $shipping_modules->cheapest();

list ($sess_class, $sess_method) = preg_split('/_/', $_SESSION['shipping']['id']);


	
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
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// assign Smarty variables;
$smarty->assign(
      array(
		'breadcrumb'	=> $oBreadcrumb->trail(),
		'heading_title'	=> $aLang['heading_title'],
		'robots'		=> 'noindex,follow,noodp,noydir',
		'cart_active' 	=> 1,
		'canonical'		=> $sCanonical,
			
		'hidden_field'			=> $hidden_field,
		'products'				=> $products,
		'any_out_of_stock'		=> $any_out_of_stock
       )
);


// display the template
$smarty->display($aTemplate['page']);
