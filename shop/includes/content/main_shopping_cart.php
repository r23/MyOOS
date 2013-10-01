<?php
/* ----------------------------------------------------------------------
   $Id: main_shopping_cart.php 431 2013-06-21 22:03:17Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/main_shopping_cart.php';

  
$hidden_field = '';
$shopping_cart_detail = '';
$any_out_of_stock = 0;


if ($_SESSION['cart']->count_contents() > 0) {

	$products = $_SESSION['cart']->get_products();
	$nProducts = count($products);

	for ($i=0, $nProducts; $i<$n; $i++) {

		// Push all attributes information in an array
		if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
			while (list($option, $value) = each($products[$i]['attributes'])) {

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

	require_once MYOOS_INCLUDE_PATH . '/includes/modules/order_details.php';
}


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['main_shopping_cart']));

$aTemplate['page'] = $sTheme . '/system/shopping_cart.tpl';

$nPageType = OOS_PAGE_TYPE_CATALOG;

require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
}

// assign Smarty variables;
$smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'cart.gif',

          'hidden_field'         => $hidden_field,
          'shopping_cart_detail' => $shopping_cart_detail,
          'oos_cart_total'       => $oCurrencies->format($_SESSION['cart']->show_total()),
          'any_out_of_stock'     => $any_out_of_stock
       )
);

$back = count($_SESSION['navigation']->path)-2;
if (isset($_SESSION['navigation']->path[$back])) {
	$back_link = oos_href_link($_SESSION['navigation']->path[$back]['modules'], $_SESSION['navigation']->path[$back]['file'], $_SESSION['navigation']->path[$back]['get'], $_SESSION['navigation']->path[$back]['mode']);
	$smarty->assign('back_link', $back_link);
}

// display the template
$smarty->display($aTemplate['page']);

