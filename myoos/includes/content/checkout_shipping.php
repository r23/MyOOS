<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_shipping.php,v 1.9 2003/02/22 17:34:00 wilt
   orig:  checkout_shipping.php,v 1.14 2003/02/14 20:28:47 dgw_
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


// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

// if the customer is not logged on, redirect them to the login page
if (!isset($_SESSION['customer_id'])) {
    // navigation history
    if (!isset($_SESSION['navigation'])) {
        $_SESSION['navigation'] = new navigationHistory();
    }
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login']));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($_SESSION['cart']->count_contents() < 1) {
    oos_redirect(oos_href_link($aContents['shopping_cart']));
}

// Minimum Order Value
if (defined('MINIMUM_ORDER_VALUE') && oos_is_not_null(MINIMUM_ORDER_VALUE)) {
    $minimum_order_value = str_replace(',', '.', (string) MINIMUM_ORDER_VALUE);
    $subtotal = $_SESSION['cart']->info['subtotal'];
    if ($subtotal < $minimum_order_value) {
        oos_redirect(oos_href_link($aContents['shopping_cart']));
    }
}


if (TAKE_BACK_OBLIGATION == 'true') {
    $products = $_SESSION['cart']->get_products();
    $n = is_countable($products) ? count($products) : 0;
    for ($i = 0, $n; $i < $n; $i++) {
        if (($products[$i]['old_electrical_equipment'] == 1) && ($products[$i]['return_free_of_charge'] == '')) {
            oos_redirect(oos_href_link($aContents['shopping_cart']));
        }
    }
}


// check for maximum order
if ($_SESSION['cart']->show_total() > $_SESSION['customer_max_order']) {
    oos_redirect(oos_href_link($aContents['info_max_order']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_shipping.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';


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
        $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
    }
}

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';
$oOrder = new order();

// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
$_SESSION['cartID'] = $_SESSION['cart']->cartID;


// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
if (($oOrder->content_type == 'virtual') || ($_SESSION['cart']->show_subtotal() == 0)) {
    $_SESSION['shipping'] = false;
    $_SESSION['sendto'] = false;
    oos_redirect(oos_href_link($aContents['checkout_payment']));
}


/*
$total_weight = $_SESSION['cart']->show_weight();
$total_count = $_SESSION['cart']->count_contents();
*/

if ($oOrder->delivery['country']['iso_code_2'] != '') {
    $_SESSION['delivery_zone'] = $oOrder->delivery['country']['iso_code_2'];
}

// load all enabled shipping modules
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shipping.php';
$shipping_modules = new shipping();

// if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
if (defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER > 0)) {
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
        case 'national':
            if ($oOrder->delivery['country_id'] == STORE_COUNTRY) {
                $pass = true;
            }
            break;

        case 'international':
            if ($oOrder->delivery['country_id'] != STORE_COUNTRY) {
                $pass = true;
            }
            break;

        case 'both':
            $pass = true;
            break;

        default:
            $pass = false;
            break;
    }

    $free_shipping = false;
    if (($pass == true) && ($oOrder->info['subtotal'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) {
        $free_shipping = true;

        include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/modules/order_total/ot_shipping.php';
    }
} else {
    $free_shipping = false;
}



// process the selected shipping method
if (isset($_POST['action']) && ($_POST['action'] == 'process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    if ((isset($_POST['comments'])) && (is_string($_POST['comments']))) {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $_SESSION['comments'] = $purifier->purify($_POST['comments']);
    }
    $_SESSION['comments'] = isset($_SESSION['comments']) ? oos_db_prepare_input($_SESSION['comments']) : '';

    if ((oos_count_shipping_modules() > 0) || ($free_shipping == true)) {
        if ((isset($_POST['shipping'])) && (strpos((string) $_POST['shipping'], '_'))) {
            $_SESSION['shipping'] = oos_db_prepare_input($_POST['shipping']);

            [$module, $method] = explode('_', (string) $_SESSION['shipping']);
            if (is_object(${$module}) || ($_SESSION['shipping'] == 'free_free')) {
                if ($_SESSION['shipping'] == 'free_free') {
                    $quote[0]['methods'][0]['title'] = $aLang['free_shipping_title'];
                    $quote[0]['methods'][0]['cost'] = '0';
                } else {
                    $quote = $shipping_modules->quote($method, $module);
                }
                if (isset($quote['error'])) {
                    unset($_SESSION['shipping']);
                } else {
                    if ((isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost']))) {
                        $sWay = '';
                        if (!empty($quote[0]['methods'][0]['title'])) {
                            $sWay = ' (' . $quote[0]['methods'][0]['title'] . ')';
                        }
                        $_SESSION['shipping'] = ['id' => $quote[0]['id'] . '_' . $quote[0]['methods'][0]['id'], 'title' => (($free_shipping == true) ? $quote[0]['methods'][0]['title'] : $quote[0]['module'] . $sWay), 'cost' => $quote[0]['methods'][0]['cost']];

                        oos_redirect(oos_href_link($aContents['checkout_payment']));
                    }
                }
            } else {
                unset($_SESSION['shipping']);
            }
        }
    } else {
        $_SESSION['shipping'] = false;

        oos_redirect(oos_href_link($aContents['checkout_payment']));
    }
}

// get all available shipping quotes
$quotes = $shipping_modules->quote();

// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
/*
if (!isset($_SESSION['shipping']) || (!isset($_SESSION['shipping']['id']) || $_SESSION['shipping']['id'] == '')) {
    $_SESSION['shipping'] = $shipping_modules->cheapest();
}
*/

$shipping = isset($_SESSION['shipping']['id']) ? oos_db_prepare_input($_SESSION['shipping']['id']) : DEFAULT_SHIPPING_METHOD . '_' . DEFAULT_SHIPPING_METHOD;
$module = substr((string) $shipping, 0, strpos((string) $shipping, '_'));

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['checkout_shipping']));
$oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['checkout_shipping']));

$aTemplate['page'] = $sTheme . '/page/checkout_shipping.html';

$nPageType = OOS_PAGE_TYPE_CHECKOUT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}



// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'        => $oBreadcrumb->trail(), 'heading_title'        => $aLang['heading_title'], 'robots'            => 'noindex,nofollow,noodp,noydir', 'checkout_active'    => 1, 'sess_method'        => $module, 'counts_shipping_modules' => oos_count_shipping_modules(), 'quotes'            => $quotes, 'free_shipping'        => $free_shipping, 'oos_free_shipping_description' => sprintf($aLang['free_shipping_description'], $oCurrencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER))]
);

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval' 'strict-dynamic' 'unsafe-inline'; object-src 'none'; base-uri 'self'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
