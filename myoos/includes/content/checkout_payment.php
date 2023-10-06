<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_payment.php,v 1.6.2.1 2003/05/03 23:41:23 wilt
   orig: checkout_payment.php,v 1.109 2003/02/14 20:28:47 dgw_
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


require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_payment.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';

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

if (oos_empty($aUser['payment'])) {
    oos_redirect(oos_href_link($aContents['403']));
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
    for ($i=0, $n; $i<$n; $i++) {
        if (($products[$i]['old_electrical_equipment'] == 1) && ($products[$i]['return_free_of_charge'] == '')) {
            oos_redirect(oos_href_link($aContents['shopping_cart']));
        }
    }
}


// if no shipping method has been selected, redirect the customer to the shipping method selection page
if (!isset($_SESSION['shipping'])) {
    oos_redirect(oos_href_link($aContents['checkout_shipping']));
}


// avoid hack attempts during the checkout procedure by checking the internal cartID
if (isset($_SESSION['cart']->cartID) && isset($_SESSION['cartID'])) {
    if ($_SESSION['cart']->cartID != $_SESSION['cartID']) {
        oos_redirect(oos_href_link($aContents['checkout_shipping']));
    }
}

// Stock Check
if ((STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true')) {
    $products = $_SESSION['cart']->get_products();
    $any_out_of_stock = 0;
	$n = is_countable($products) ? count($products) : 0;
    for ($i=0, $n; $i<$n; $i++) {
        if (oos_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
            $any_out_of_stock = 1;
        }
    }
    if ($any_out_of_stock == 1) {
        oos_redirect(oos_href_link($aContents['shopping_cart']));
    }
}

// if no billing destination address was selected, use the customers own address as default
if (!isset($_SESSION['billto'])) {
    $_SESSION['billto'] = intval($_SESSION['customer_default_address_id']);
} else {
    // verify the selected billing address
    $address_booktable = $oostable['address_book'];
    $sql = "SELECT COUNT(*) AS total
            FROM $address_booktable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
              AND address_book_id = '" . intval($_SESSION['billto']) . "'";
    $check_address_result = $dbconn->Execute($sql);
    $check_address = $check_address_result->fields;

    if ($check_address['total'] != '1') {
        $_SESSION['billto'] = intval($_SESSION['customer_default_address_id']);
        if (isset($_SESSION['payment'])) {
            unset($_SESSION['payment']);
        }
    }
}

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';
$oOrder = new order();

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order_total.php';
$order_total_modules = new order_total();

/*
$total_weight = $_SESSION['cart']->show_weight();
$total_count = $_SESSION['cart']->count_contents();
$total_count = $_SESSION['cart']->count_contents_virtual();
*/

if ($oOrder->delivery['country']['iso_code_2'] != '') {
    $_SESSION['delivery_zone'] = $oOrder->delivery['country']['iso_code_2'];
}

// load all enabled payment modules
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_payment.php';
$payment_modules = new payment();
$selection = $payment_modules->selection();

$credit_selection = $order_total_modules->credit_selection();

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['checkout_shipping']));
$oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['checkout_payment']));

$aTemplate['page'] = $sTheme . '/page/checkout_payment.html';

$nPageType = OOS_PAGE_TYPE_CHECKOUT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb' => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title'], 'robots'        => 'noindex,nofollow,noodp,noydir', 'checkout_active' => 1]
);

$smarty->assign(
    ['selection' => $selection, 'credit_selection' => $credit_selection]
);

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
