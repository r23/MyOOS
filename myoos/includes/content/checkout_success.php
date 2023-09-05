<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_success.php,v 1.6.2.1 2003/05/03 23:41:23 wilt
   orig: checkout_success.php,v 1.48 2003/02/17 11:51:16 hpdl
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

// if the customer is not logged on, redirect them to the shopping cart page
if (!isset($_SESSION['customer_id'])) {
    oos_redirect(oos_href_link($aContents['shopping_cart']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_success.php';

if (isset($_POST['action']) && ($_POST['action'] == 'notify_process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    if (isset($_POST['notify']) && !empty($_POST['notify'])) {
        $notify = oos_db_prepare_input($_POST['notify']);

        if (!is_array($notify)) {
            $notify = [$notify];
        }

        $products_notificationstable = $oostable['products_notifications'];
        for ($i=0, $n=count($notify); $i<$n; $i++) {
            $sql = "SELECT COUNT(*) AS total
                FROM $products_notificationstable
                WHERE products_id = '" . intval($notify[$i]) . "'
                  AND customers_id = '" . intval($_SESSION['customer_id']) . "'";
            $check = $dbconn->Execute($sql);
            if ($check->fields['total'] < 1) {
                $today = date("Y-m-d H:i:s");
                $sql = "INSERT INTO $products_notificationstable
                  (products_id,
                   customers_id,
                   date_added) VALUES (" . $dbconn->qstr($notify[$i]) . ','
                                         . $dbconn->qstr($_SESSION['customer_id']) . ','
                                         . $dbconn->DBTimeStamp($today) . ")";
                $result = $dbconn->Execute($sql);
            }
        }
    }
    oos_redirect(oos_href_link($aContents['home']));
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1']);
$oBreadcrumb->add($aLang['navbar_title_2']);

  $customers_infotable = $oostable['customers_info'];
  $sql = "SELECT global_product_notifications 
          FROM $customers_infotable
          WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'";
  $global_result = $dbconn->Execute($sql);
  $global = $global_result->fields;

if ($global['global_product_notifications'] != '1') {
    $orderstable = $oostable['orders'];
    $sql = "SELECT orders_id
            FROM $orderstable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
            ORDER BY date_purchased desc LIMIT 1";
    $orders_result = $dbconn->Execute($sql);
    $orders = $orders_result->fields;

    $products_array = [];
    $orders_productstable = $oostable['orders_products'];
    $sql = "SELECT products_id, products_name
            FROM $orders_productstable
            WHERE orders_id = '" . intval($orders['orders_id']) . "'
            ORDER BY products_name";
    $products_result = $dbconn->Execute($sql);
    while ($products = $products_result->fields) {
        $products_array[] = ['id' => $products['products_id'], 'text' => $products['products_name']];
        $products_result->MoveNext();
    }
}

//ICW ADDED FOR ORDER_TOTAL CREDIT SYSTEM - Start Addition
$coupon_gv_customertable = $oostable['coupon_gv_customer'];
$sql = "SELECT amount
          FROM $coupon_gv_customertable
          WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'";
$gv_amount = $dbconn->GetOne($sql);

$products_notify = '';
if ($global['global_product_notifications'] != '1') {
    $products_notify .= $aLang['text_notify_products'] . '<br /><p class="productsNotifications">';

    $products_displayed = [];
    for ($i=0, $n=is_countable($products_array) ? count($products_array) : 0; $i<$n; $i++) {
        if (!in_array($products_array[$i]['id'], $products_displayed)) {
            $products_notify .= oos_draw_checkbox_field('notify[]', $products_array[$i]['id']) . ' ' . $products_array[$i]['text'] . '<br />';
            $products_displayed[] = $products_array[$i]['id'];
        }
    }
    $products_notify .= '</p>';
} else {
    $products_notify .= $aLang['text_see_orders'] . '<br /><br />' . $aLang['text_contact_store_owner'];
}


$aTemplate['page'] = $sTheme . '/page/checkout_success.html';

$nPageType = OOS_PAGE_TYPE_CHECKOUT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'        => $oBreadcrumb->trail(), 'heading_title'        => $aLang['heading_title'], 'robots'            => 'noindex,nofollow,noodp,noydir', 'checkout_active'    => 1, 'gv_amount'            => $gv_amount, 'products_notify'    => $products_notify]
);

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);

if ($_SESSION['guest_account'] == 1) {
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_wishlist_link_id']);
    unset($_SESSION['customer_default_address_id']);
    unset($_SESSION['customer_gender']);
    unset($_SESSION['customer_first_name']);
    unset($_SESSION['customer_lastname']);
    unset($_SESSION['customer_country_id']);
    unset($_SESSION['customer_zone_id']);
    unset($_SESSION['comments']);
    unset($_SESSION['customer_max_order']);
    unset($_SESSION['man_key']);
    unset($_SESSION['customers_email_address']);
    unset($_SESSION['guest_account']);
}
