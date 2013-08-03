<?php
/* ----------------------------------------------------------------------
   $Id: checkout_payment.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_payment.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';

// if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

  if (oos_empty($_SESSION['member']->group['payment'])) {
    oos_redirect(oos_href_link($aContents['main']));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    oos_redirect(oos_href_link($aContents['main_shopping_cart']));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!isset($_SESSION['shipping'])) {
    oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
  }


// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($_SESSION['cart']->cartID) && isset($_SESSION['cartID'])) {
    if ($_SESSION['cart']->cartID != $_SESSION['cartID']) {
      oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
    }
  }

// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $_SESSION['cart']->get_products();
    $any_out_of_stock = 0;
    for ($i=0, $n=count($products); $i<$n; $i++) {
      if (oos_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        $any_out_of_stock = 1;
      }
    }
    if ($any_out_of_stock == 1) {
      oos_redirect(oos_href_link($aContents['main_shopping_cart']));
    }
  }

// if no billing destination address was selected, use the customers own address as default
  if (!isset($_SESSION['billto'])) {
    $_SESSION['billto'] = $_SESSION['customer_default_address_id'];
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
      $_SESSION['billto'] = $_SESSION['customer_default_address_id'];
      if (isset($_SESSION['payment'])) unset($_SESSION['payment']);
    }
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';
  $oOrder = new order;
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order_total.php';
  $order_total_modules = new order_total;


  $total_weight = $_SESSION['cart']->show_weight();
  $total_count = $_SESSION['cart']->count_contents();
  $total_count = $_SESSION['cart']->count_contents_virtual();

// load all enabled payment modules
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_payment.php';
  $payment_modules = new payment;
  $selection = $payment_modules->selection();
  $credit_selection = $order_total_modules->credit_selection();

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['checkout_payment'], '', 'SSL'));

  if (ENABLE_SSL == 'true') {
    $condition_link = OOS_HTTPS_SERVER;
  } else {
    $condition_link = OOS_HTTP_SERVER;
  }
  $condition_link .= OOS_SHOP . OOS_MEDIA . $sLanguage . '/' . $aFilename['conditions_download'];

  ob_start();
  require 'js/checkout_payment.js.php';
  print $payment_modules->javascript_validation();
  $javascript = ob_get_contents();
  ob_end_clean();

  $aTemplate['page'] = $sTheme . '/modules/checkout_payment.tpl';

  $nPageType = OOS_PAGE_TYPE_CHECKOUT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'payment.gif'
      )
  );

  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
    $smarty->assign(
        array(
            'oos_payment_error' => 'true',
            'error' => $error
        )
    );
  }
  $smarty->assign('condition_link', $condition_link);
  $smarty->assign(
      array(
          'selection' => $selection,
          'credit_selection' => $credit_selection
      )
  );


  $campaignstable = $oostable['campaigns'];
  $sql = "SELECT campaigns_id FROM $campaignstable WHERE campaigns_languages_id = '" . intval($_SESSION['language_id']) . "'";
  $campaigns_result = $dbconn->Execute($sql);
  if ($campaigns_result->RecordCount()) {
    $smarty->assign('campaigns', 'true');

    if (isset($_SESSION['campaigns_id']) && is_numeric($_SESSION['campaigns_id'])) {
      $smarty->assign('campaigns_id', $_SESSION['campaigns_id']);
    } else {
      $smarty->assign('campaigns_id', DEFAULT_CAMPAIGNS_ID);
    }

    $campaignstable = $oostable['campaigns'];
    $campaigns_sql = "SELECT campaigns_id, campaigns_name
                      FROM $campaignstable
                      WHERE campaigns_languages_id = '" . intval($_SESSION['language_id']) . "'
                      ORDER BY campaigns_id";
    $smarty->assign('campaigns_radios', $dbconn->getAssoc($campaigns_sql));
  }

  // JavaScript
  $smarty->assign('oos_js', $javascript);

// display the template
$smarty->display($aTemplate['page']);

