<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_confirmation.php,v 1.6.2.1 2003/05/03 23:41:23 wilt
   orig: checkout_confirmation.php,v 1.135 2003/02/14 20:28:46 dgw_
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

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_confirmation.php';
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
    $_SESSION['navigation']->set_snapshot(['content' =>$aContents['checkout_payment']]);
    oos_redirect(oos_href_link($aContents['login']));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($_SESSION['cart']->count_contents() < 1) {
    oos_redirect(oos_href_link($aContents['shopping_cart']));
}

// avoid hack attempts during the checkout procedure by checking the internal cartID
if (isset($_SESSION['cart']->cartID) && isset($_SESSION['cartID'])) {
    if ($_SESSION['cart']->cartID != $_SESSION['cartID']) {
        oos_redirect(oos_href_link($aContents['checkout_shipping']));
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

if (isset($_POST['payment'])) {
    $_SESSION['payment'] = oos_db_prepare_input($_POST['payment']);
}


if ((isset($_POST['comments'])) && (is_string($_POST['comments']))) {
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    $_SESSION['comments'] = $purifier->purify($_POST['comments']);
}
$_SESSION['comments'] ??= '';



// if no shipping method has been selected, redirect the customer to the shipping method selection page
if (!isset($_SESSION['shipping'])) {
    oos_redirect(oos_href_link($aContents['checkout_shipping']));
}


// Minimum Order Value
if (defined('MINIMUM_ORDER_VALUE') && oos_is_not_null(MINIMUM_ORDER_VALUE)) {
    $minimum_order_value = str_replace(',', '.', (string) MINIMUM_ORDER_VALUE);
    $subtotal = $_SESSION['cart']->info['subtotal'];
    if ($subtotal < $minimum_order_value) {
        oos_redirect(oos_href_link($aContents['shopping_cart']));
    }
}



// load the selected payment module
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_payment.php';

if (!isset($credit_covers)) {
    $credit_covers = false;
}

if ($credit_covers) {
    unset($_SESSION['payment']);
    $_SESSION['payment'] = '';
}


$payment_modules = new payment($_SESSION['payment']);

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order_total.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';
$oOrder = new order();

if ((isset($_SESSION['shipping'])) && ($_SESSION['shipping']['id'] == 'free_free')) {
    if (($oOrder->info['total'] - $oOrder->info['shipping_cost']) < MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) {
        oos_redirect(oos_href_link($aContents['checkout_shipping']));
    }
}


$payment_modules->update_status();
$order_total_modules = new order_total();
$order_total_modules->collect_posts();


if (isset($_SESSION['cot_gv'])) {
    $credit_covers = $order_total_modules->pre_confirmation_check();
}

if (($_SESSION['payment'] == '' || !is_object(${$_SESSION['payment']})) && $credit_covers === false) {
    $oMessage->add_session('danger', $aLang['error_no_payment_module_selected']);
}

if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
}

if ($oMessage->size('checkout_payment') > 0) {
    oos_redirect(oos_href_link($aContents['checkout_payment']));
}


// load the selected shipping module
$module = substr((string) $_SESSION['shipping']['id'], 0, strpos((string) $_SESSION['shipping']['id'], '_'));
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shipping.php';
$shipping_modules = new shipping($module);



// Stock Check
$any_out_of_stock = false;
if (STOCK_CHECK == 'true') {
    for ($i=0, $n=is_countable($oOrder->products) ? count($oOrder->products) : 0; $i<$n; $i++) {
        if (oos_check_stock($oOrder->products[$i]['id'], $oOrder->products[$i]['qty'])) {
            $any_out_of_stock = true;
        }
    }
    // Out of Stock
    if ((STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true)) {
        oos_redirect(oos_href_link($aContents['shopping_cart']));
    }
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['checkout_shipping']));
$oBreadcrumb->add($aLang['navbar_title_2']);

$aTemplate['page'] = $sTheme . '/page/checkout_confirmation.html';

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

if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    $order_total_output = $order_total_modules->output();
    $smarty->assign('order_total_output', $order_total_output);
}

if (is_array($payment_modules->modules)) {
    if ($confirmation == $payment_modules->confirmation()) {
        $smarty->assign('confirmation', $confirmation);
    }
}

if (is_array($payment_modules->modules)) {
    $payment_modules_process_button = $payment_modules->process_button();
}


if (isset(${$_SESSION['payment']}->form_action_url)) {
    $form_action_url = ${$_SESSION['payment']}->form_action_url;
} else {
    $form_action_url = oos_href_link($aContents['checkout_process']);
}
$smarty->assign('form_action_url', $form_action_url);

$smarty->assign('payment_modules_process_button', $payment_modules_process_button);
$smarty->assign('order', $oOrder);
$smarty->assign('text_conditions', sprintf($aLang['text_conditions'], oos_href_link($aContents['information'], 'information_id=2'), oos_href_link($aContents['information'], 'information_id=3'), oos_href_link($aContents['information'], 'information_id=4')));

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
