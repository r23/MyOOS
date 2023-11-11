<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: shopping_cart.php,v 1.71 2003/02/14 05:51:28 hpdl
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

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/shopping_cart.php';

$hidden_field = '';
$any_out_of_stock = 0;
$order_total_output = [];
$country = STORE_COUNTRY;

if (isset($_SESSION)) {
    if (is_object($_SESSION['cart'])) {
        if ($_SESSION['cart']->count_contents() > 0) {

            // Add Shipping Cost
            include_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';
            include_once MYOOS_INCLUDE_PATH . '/includes/functions/function_word_cleaner.php';
            include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_shipping.php';
            include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/modules/order_total/ot_shipping.php';

            $country = (isset($_SESSION['delivery_country_id'])) ? intval($_SESSION['delivery_country_id']) : STORE_COUNTRY;

            if (isset($_GET['action']) && ($_GET['action'] == 'shipping')
                && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
            ) {
                $country = oos_db_prepare_input($_POST['country']);
                if (is_numeric($country) == false) {
                    $oMessage->add($aLang['entry_country_error']);
                } else {
                    $_SESSION['delivery_country_id'] = intval($country);

                    $aCountries = oos_get_countries($_SESSION['delivery_country_id'], true);
                    if (!empty($aCountries['countries_iso_code_2'])) {
                        $_SESSION['delivery_zone'] = $aCountries['countries_iso_code_2'];
                    }
                }

                if (isset($_POST['postcode'])) {
                    $postcode = oos_db_prepare_input($_POST['postcode']);
                    $postcode = strtoupper((string) $postcode);
                    /* todo: postcode
                    if (strlen($postcode ?? '') < ENTRY_POSTCODE_MIN_LENGTH) {
                        $oMessage->add($aLang['entry_post_code_error']);
                    }
                    */
                }

                if (isset($_POST['city'])) {
                    $city = oos_db_prepare_input($_POST['city']);
                    $city = oos_remove_shouting($city);
                    /* todo: city
                    if (strlen($city ?? '') < ENTRY_CITY_MIN_LENGTH) {
                        $oMessage->add($aLang['entry_city_error']);
                    }
                    */
                }
            }


            // if no shipping destination address was selected, use the customers own address as default
            if (!isset($_SESSION['sendto'])) {
                // $_SESSION['sendto'] = intval($_SESSION['customer_default_address_id']);
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
                    // $_SESSION['sendto'] = intval($_SESSION['customer_default_address_id']);
                }
            }

            $content_type = $_SESSION['cart']->get_content_type();
            $total_weight = $_SESSION['cart']->info['weight'];
            $subtotal = $_SESSION['cart']->info['subtotal'];
            $total_count = $_SESSION['cart']->count_contents();


            // if the order contains only virtual products
            if (($content_type == 'virtual') || ($_SESSION['cart']->show_total() == 0)) {
                $_SESSION['shipping'] = false;
                $_SESSION['sendto'] = false;
                $free_shipping = true;
            }

            $shipping = isset($_SESSION['shipping']['id']) ? oos_db_prepare_input($_SESSION['shipping']['id']) : DEFAULT_SHIPPING_METHOD . '_' . DEFAULT_SHIPPING_METHOD;
            [$module, $method] = explode('_', (string) $shipping);

            // load all enabled shipping modules
            include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shipping.php';
            $shipping_modules = new shipping($module);

            // shipping quotes
            $quote = $shipping_modules->quote($method, $module);

            if ((isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost']))) {
                $_SESSION['shipping'] = ['id' => $quote[0]['id'] . '_' . $quote[0]['methods'][0]['id'], 'title' => (($free_shipping == true) ? $quote[0]['methods'][0]['title'] : $quote[0]['module']), 'cost' => $quote[0]['methods'][0]['cost']];
            }

            // load all enabled order total modules
            include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order_total.php';
            $order_total_modules = new order_total();

            // Redeem coupons
            if (isset($_GET['action']) && ($_GET['action'] == 'promocode')
                && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
                && (isset($_POST['gv_redeem_code']) && is_string($_POST['gv_redeem_code']))
            ) {
                $order_total_modules->shopping_cart_collect_posts();
            }


            $order_total_modules->shopping_cart_process();
            $order_total_output = $order_total_modules->output();

            // Minimum Order Value
            if (defined('MINIMUM_ORDER_VALUE') && oos_is_not_null(MINIMUM_ORDER_VALUE)) {
                $minimum_order_value = str_replace(',', '.', (string) MINIMUM_ORDER_VALUE);

                if ($subtotal < $minimum_order_value) {
                    $oMessage->add(sprintf($aLang['warning_minimum_order_value'], $oCurrencies->format($minimum_order_value)));
                }
            }


            /*
             * Shopping Cart
            */
            $products = $_SESSION['cart']->get_products();
            $n = is_countable($products) ? count($products) : 0;
            $nError = 0;

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

                if (($products[$i]['old_electrical_equipment'] == 1) && ($products[$i]['return_free_of_charge'] == '') && ($nError == 0)) {
                    $nError = 1;
                    $oMessage->add($aLang['text_error']);
                }

                // Push all attributes information in an array
                if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
                    foreach ($products[$i]['attributes'] as $option => $value) {
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
                            $hidden_field .=  oos_draw_hidden_field('id[' . $products[$i]['id'] . '][' . TEXT_PREFIX . $option . ']', $products[$i]['attributes_values'][$option]);
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
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title_cart'], 'robots'        => 'noindex,follow,noodp,noydir', 'cart_active'   => 1, 'canonical'     => $sCanonical, 'hidden_field'  => $hidden_field, 'products'      => $products, 'error'         => $nError, 'any_out_of_stock'    => $any_out_of_stock, 'order_total_output'  => $order_total_output, 'country'             => $country, 'city'                => $city, 'postcode'            => $postcode]
);

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval' 'strict-dynamic' 'unsafe-inline'; object-src 'none'; base-uri 'self'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
