<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: order_details.php,v 1.2 2003/01/09 09:40:08 elarifr
   orig: order_details.php,v 1.7 2003/02/13 01:46:54 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  $shopping_cart_detail = '';
  $shopping_cart_detail .= '  <tr>' . "\n";

  $colspan = 3;

  $colspan++;
  $shopping_cart_detail .= '    <td align="center" class="smallText"></td>' . "\n";


  if ($sContent == $aContents['shopping_cart']) {
    $colspan++;
    $shopping_cart_detail .= '    <td align="center" class="smallText"><strong>' . $aLang['table_heading_remove'] . '</strong></td>' . "\n";
  }

  $shopping_cart_detail .= '    <td align="center" class="tableHeading">' . $aLang['table_heading_quantity'] . '</td>' . "\n";

  if (PRODUCT_LIST_MODEL > 0) {
    if ($sContent == $aContents['shopping_cart']) {
      $colspan++;
      $shopping_cart_detail .= '    <td class="tableHeading">' . $aLang['table_heading_model'] . '</td>' . "\n";
    }
  }

  $shopping_cart_detail .= '    <td class="tableHeading">' . $aLang['table_heading_products'] . '</td>' . "\n";

  if ($sContent != $aContents['shopping_cart']) {
    $colspan++;
    $shopping_cart_detail .= '    <td align="center" class="tableHeading">' . $aLang['table_heading_tax'] . '</td>' . "\n";
  }

  $shopping_cart_detail .= '    <td align="right" class="tableHeading">' . $aLang['table_heading_total'] . '</td>' . "\n" .
       '  </tr>' . "\n" .
       '  <tr>' . "\n" .
       '    <td colspan="' . $colspan . '">&nbsp;</td>' . "\n" .
       '  </tr>' . "\n";

  for ($i=0, $n=count($products); $i<$n; $i++) {
    $shopping_cart_detail .= '  <tr>' . "\n";

    if (SHOPPING_CART_IMAGE_ON == 'true') {
      $shopping_cart_detail .= '    <td align="center" valign="top" class="main"><a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $products[$i]['id']) . '">';
      $shopping_cart_detail .=  oos_image(OOS_IMAGES . 'product/small/' . $products[$i]['image'], $products[$i]['name']) . '</a></td>' . "\n";
    }


    // Delete box only for shopping cart
    if ($sContent == $aContents['shopping_cart']) {
      $shopping_cart_detail .= '    <td align="center" valign="top">' . oos_draw_checkbox_field('cart_delete[]', $products[$i]['id']) . '</td>' . "\n";
    }

    // Quantity box or information as an input box or text
    $quantity = number_format($products[$i]['quantity']);

    if ($sContent == $aContents['shopping_cart']) {
      $shopping_cart_detail .= '    <td align="center" valign="top" class ="main">' . oos_draw_input_field('cart_quantity[]', $quantity, 'size="4"') . oos_draw_hidden_field('products_id[]', $products[$i]['id']) .  '<br />' . (oos_get_products_quantity_order_min($products[$i]['id']) > 1 ? $aLang['products_order_qty_min_text_cart_short'] . oos_get_products_quantity_order_min($products[$i]['id']) : '') . (oos_get_products_quantity_order_units($products[$i]['id']) > 1 ? $aLang['products_order_qty_unit_text_cart_short'] . oos_get_products_quantity_order_units($products[$i]['id']) : "") . '</td>' . "\n";
    } else {
      $shopping_cart_detail .= '    <td align="center" valign="top" class ="main">' . $quantity . '</td>' . "\n";
    }

    if (PRODUCT_LIST_MODEL > 0) {
      if ($sContent == $aContents['shopping_cart']) {
        $shopping_cart_detail .= '    <td valign="top" class="main"><a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['model'] . '</a></td>' . "\n";
      }
    }

    // Product name, with or without link
    if ($sContent == $aContents['shopping_cart']) {
      $shopping_cart_detail .= '    <td valign="top" class="main"><a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $products[$i]['id']) . '"><strong>' . $products[$i]['name'] . '</strong></a>';
    } else {
      $shopping_cart_detail .= '    <td valign="top" class="main"><strong>' . $products[$i]['name'] . '</strong>';
    }

    // Display marker if stock quantity insufficient
    if (STOCK_CHECK == 'true') {
      $shopping_cart_detail .= $stock_check = oos_check_stock($products[$i]['id'], $products[$i]['quantity']);
      if ($stock_check) $any_out_of_stock = 1;
    }


    // Wishlist names
	/*
    if (oos_is_not_null($products[$i]['towlid'])) {
      $shopping_cart_detail .= '<br /><a href="' . oos_href_link($aContents['wishlist'], 'wlid=' . $products[$i]['towlid']) . '">' . oos_image(OOS_IMAGES . 'wl.gif', oos_get_wishlist_name($products[$i]['towlid'])) . '</a>' . "\n";
      $shopping_cart_detail .= '<small><i><a href="' . oos_href_link($aContents['wishlist'], 'wlid=' . $products[$i]['towlid']) . '">' . oos_get_wishlist_name($products[$i]['towlid']) . '</a></i></small>';
      $shopping_cart_detail .= oos_draw_hidden_field('to_wl_id[]', $products[$i]['towlid']);
    }
	*/


     // Product options names
    if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
      reset($products[$i]['attributes']);
      foreach($products[$i]['attributes'] as $option => $value) {		  
        $shopping_cart_detail .= '<br /><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
      }
    }

    $shopping_cart_detail .= '</td>' . "\n";


    // Tax (not in shopping cart, tax rate may be unknown)
    if ($sContent != $aContents['shopping_cart']) {
      $shopping_cart_detail .= '    <td align="center" valign="top" class="main">' . number_format($products[$i]['tax'], TAX_DECIMAL_PLACES) . '%</td>' . "\n";
    }

    // Product price
    if ($sContent != $aContents['account_history_info']) {
      $shopping_cart_detail .= '    <td align="right" valign="top" class="main"><strong>' . $oCurrencies->display_price($products[$i]['price'], oos_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</strong>';
    } else {
      $shopping_cart_detail .= '    <td align="right" valign="top" class="main"><strong>' . $oCurrencies->display_price($products[$i]['price'], $products[$i]['tax'], $products[$i]['quantity']) . '</strong>';
    }

    // Product options prices
    if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
      reset($products[$i]['attributes']);
      foreach($products[$i]['attributes'] as $option => $value) {		  
        if ($products[$i][$option]['options_values_price'] != 0) {
          if ($sContent != $aContents['account_history_info']) {
            $shopping_cart_detail .= '<br /><small><i>' . $products[$i][$option]['price_prefix'] . $oCurrencies->display_price($products[$i][$option]['options_values_price'], oos_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</i></small>';
          } else {
            $shopping_cart_detail .= '<br /><small><i>' . $products[$i][$option]['price_prefix'] . $oCurrencies->display_price($products[$i][$option]['options_values_price'], $products[$i]['tax'], $products[$i]['quantity']) . '</i></small>';
          }
        } else {
          // Keep price aligned with corresponding option
          $shopping_cart_detail .= '<br /><small><i>&nbsp;</i></small>';
        }
      }
    }

    $shopping_cart_detail .= '</td>' . "\n" .
                             '  </tr>' . "\n";
  }

