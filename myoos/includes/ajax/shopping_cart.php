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

			$products = $_SESSION['cart']->get_products();

			$n = count($products);
			for ($i=0, $n; $i<$n; $i++) {
				
				
//  (oos_get_products_quantity_order_min($products[$i]['id']) > 1 ? $aLang['products_order_qty_min_text_cart_short'] . oos_get_products_quantity_order_min($products[$i]['id']) : '') . (oos_get_products_quantity_order_units($products[$i]['id']) > 1 ? $aLang['products_order_qty_unit_text_cart_short'] . oos_get_products_quantity_order_units($products[$i]['id']) : "")

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
			
			
	  $shopping_cart_detail = '';


  $contents = '<div class="media"><!-- cart item -->
						<div class="featured-entry-thumb mr-3"><a href="http://localhost/ent/MyOOS/MyOOS/myoos/index.php?content=product_info&amp;products_id=1&amp;PHOENIXSID=nk0o6vvkg59mst936j5sed7mi2">
							<img id="" class="img-fluid " src="images/product/min/p8_(1)_(1)_(1)_(1)_(1).jpg" alt="2-in-1-Shirt"  />					
							</a></div>
						<div class="media-body">
							<h6 class="featured-entry-title"><a href="http://localhost/ent/MyOOS/MyOOS/myoos/index.php?content=product_info&amp;products_id=1&amp;PHOENIXSID=nk0o6vvkg59mst936j5sed7mi2">2-in-1-Shirt</a></h6>
							<p class="featured-entry-meta">1&nbsp; <span class="text-muted">x</span> 22,00 €</p>
						</div>
						<div class="text-right"><span class="item-remove-btn" data-id="1" role="button"> <i class="fa fa-trash" aria-hidden="true"></i></span></div>
					</div>	<!-- /cart item -->																
				
				<hr>
				<div class="d-flex justify-content-between align-items-center py-3">
				<div class="font-size-sm"> <span class="mr-2">Summe:</span><span class="font-weight-semibold text-dark">22,00 €</span></div><a class="btn btn-outline-secondary btn-sm" href="http://localhost/ent/MyOOS/MyOOS/myoos/index.php?content=shopping_cart&amp;PHOENIXSID=nk0o6vvkg59mst936j5sed7mi2">Warenkorb<i class="mr-n2" data-feather="chevron-right"></i></a>
				</div><a class="btn btn-primary btn-sm btn-block" href="http://localhost/ent/MyOOS/MyOOS/myoos/index.php?content=checkout_shipping&amp;PHOENIXSID=nk0o6vvkg59mst936j5sed7mi2"><i class="mr-1" data-feather="credit-card"></i>Kasse</a>			</div>		
				</div>
';
echo json_encode($contents); 



/*
  for ($i=0, $n=count($products); $i<$n; $i++) {
		  
		 $shopping_cart_detail .= '  <tr>' . "\n";

    if (SHOPPING_CART_IMAGE_ON == 'true') {
      $shopping_cart_detail .= '    <td align="center" valign="top" class="main"><div class="row align-items-center d-none d-sm-block">
	  
	  <a href="' . oos_href_link($aModules['products'], $aFilename['product_info'], 'products_id=' . $products[$i]['id'], 'NONSSL') . '">';
      if ($products[$i]['image'] != '') {
        $sProductImage = $products[$i]['image'];
      } else {
        if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'no_picture_' . $sLanguage . '.png')) {
          $sProductImage = 'no_picture_' . $sLanguage . '.png';
        } else {
          $sProductImage = 'no_picture.png';
        }
      }
      $shopping_cart_detail .=  oos_image(OOS_IMAGES . $sProductImage, $products[$i]['name'], '', '150') . '</a></div></td>' . "\n";
    }


    // Delete box only for shopping cart
    if ($sFile == $aFilename['main_shopping_cart']) {
      $shopping_cart_detail .= '    <td align="center" valign="top">' . oos_draw_checkbox_field('cart_delete[]', $products[$i]['id']) . '</td>' . "\n";
    }

    // Quantity box or information as an input box or text
    if (DECIMAL_CART_QUANTITY == 'true') {
      $quantity = number_format($products[$i]['quantity'], 2);
    } else {
      $quantity = number_format($products[$i]['quantity']);
    }

      $shopping_cart_detail .= '    <td align="center" valign="top" class ="main">' . oos_draw_input_field('cart_quantity[]', $quantity, 'maxlength="2" size="1"') . oos_draw_hidden_field('products_id[]', $products[$i]['id']) .  '<br />' . (oos_get_products_quantity_order_min($products[$i]['id']) > 1 ? $aLang['products_order_qty_min_text_cart_short'] . oos_get_products_quantity_order_min($products[$i]['id']) : '') . (oos_get_products_quantity_order_units($products[$i]['id']) > 1 ? $aLang['products_order_qty_unit_text_cart_short'] . oos_get_products_quantity_order_units($products[$i]['id']) : "") . '</td>' . "\n";


    if (PRODUCT_LIST_MODEL > 0) {
      if ($sFile == $aFilename['main_shopping_cart']) {
        $shopping_cart_detail .= '    <td valign="top" class="main"><a href="' . oos_href_link($aModules['products'], $aFilename['product_info'], 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['model'] . '</a></td>' . "\n";
      }
    }

    // Product name, with or without link
      $shopping_cart_detail .= '    <td valign="top" class="main"><a href="' . oos_href_link($aModules['products'], $aFilename['product_info'], 'products_id=' . $products[$i]['id']) . '"><b>' . $products[$i]['name'] . '</b></a>';


    // Display marker if stock quantity insufficient
    if (STOCK_CHECK == 'true') {
      $shopping_cart_detail .= $stock_check = oos_check_stock($products[$i]['id'], $products[$i]['quantity']);
      if ($stock_check) $any_out_of_stock = 1;
    }


     // Product options names
    if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
      reset($products[$i]['attributes']);
      while (list($option, $value) = each($products[$i]['attributes'])) {
        $shopping_cart_detail .= '<br /><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
      }
    }

    $shopping_cart_detail .= '</td>' . "\n";

    if ($sFile == $aFilename['main_shopping_cart']) {
      if ($_SESSION['member']->group['discount'] != 0) {
        $max_product_discount = min($products[$i]['discount_allowed'] , $_SESSION['member']->group['discount']);
        if ( ($max_product_discount > 0) && ($products[$i]['spezial'] == 'false') ) {
          $shopping_cart_detail .= '    <td align="right" valign="top" class="main">-' . number_format($max_product_discount, 2) . '%</td>';
        } else {
          $shopping_cart_detail .= '    <td align="right" valign="top" class="main">&nbsp</td>';
        }
      }
    }

    // Tax (not in shopping cart, tax rate may be unknown)
    if ($sFile != $aFilename['main_shopping_cart']) {
      $shopping_cart_detail .= '    <td align="center" valign="top" class="main">tt' . number_format($products[$i]['tax'], TAX_DECIMAL_PLACES) . '%</td>' . "\n";
    }



    // Product price 
    if ($sFile != $aFilename['account_history_info']) {
      $shopping_cart_detail .= '    <td align="right" valign="top" class="main"><b>' . $oCurrencies->display_price($products[$i]['price'], oos_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>';
    } else {
      $shopping_cart_detail .= '    <td align="right" valign="top" class="main"><b>' . $oCurrencies->display_price($products[$i]['price'], $products[$i]['tax'], $products[$i]['quantity']) . '</b>';
    }

    // Product options prices
    if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
      reset($products[$i]['attributes']);
      while (list($option, $value) = each($products[$i]['attributes'])) {
        if ($products[$i][$option]['options_values_price'] != 0) {
            $shopping_cart_detail .= '<br /><small><i>' . $products[$i][$option]['price_prefix'] . $oCurrencies->display_price($products[$i][$option]['options_values_price'], $products[$i]['tax'], $products[$i]['quantity']) . '</i></small>';
        } else {
          // Keep price aligned with corresponding option
          $shopping_cart_detail .= '<br /><small><i>&nbsp;</i></small>';
        }
      }
    }



    $shopping_cart_detail .= '</td>' . "\n" .
                             '  </tr>' . "\n";
  }
*/			
		
			
		}
	}
}

