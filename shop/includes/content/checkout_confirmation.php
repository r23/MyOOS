<?php
/* ----------------------------------------------------------------------
   $Id: checkout_confirmation.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_confirmation.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';

// if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot(array('mode' => 'SSL', 'content' => $aContents['checkout_payment']));
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    oos_redirect(oos_href_link($aContents['main_shopping_cart']));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($_SESSION['cart']->cartID) && isset($_SESSION['cartID'])) {
    if ($_SESSION['cart']->cartID != $_SESSION['cartID']) {
      oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
    }
  }

  if (isset($_POST['payment'])) $_SESSION['payment'] = oos_db_prepare_input($_POST['payment']);


  if ( (isset($_POST['comments'])) && (empty($_POST['comments'])) ) {
    $_SESSION['comments'] = '';
  } else if (oos_is_not_null($_POST['comments'])) {
    $_SESSION['comments'] = oos_db_prepare_input($_POST['comments']);
  }

  if (isset($_POST['campaign_id']) && is_numeric($_POST['campaign_id'])) {
    $_SESSION['campaigns_id'] = intval($_POST['campaign_id']);
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!isset($_SESSION['shipping'])) {
    oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
  }

// if conditions are not accepted, redirect the customer to the payment method selection page
  if ( (DISPLAY_CONDITIONS_ON_CHECKOUT == 'true') && (empty($_POST['gv_redeem_code'])) ) {
    if ($_POST['conditions'] == false) {

      oos_redirect(oos_href_link($aContents['checkout_payment'], 'error_message=' . urlencode(decode($aLang['error_conditions_not_accepted'])), 'SSL', true, false));
    }
  }


// load the selected payment module
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_payment.php';

  if ($credit_covers) $_SESSION['payment'] = '';

  $payment_modules = new payment($_SESSION['payment']);
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order_total.php';

  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';
  $oOrder = new order;

  if ( (isset($_SESSION['shipping'])) && ($_SESSION['shipping']['id'] == 'free_free')) {
    if ( ($oOrder->info['total'] - $oOrder->info['shipping_cost']) < MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER ) {
      oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
    }
  }

  $payment_modules->update_status();
  $order_total_modules = new order_total;
  $order_total_modules->collect_posts();


  if (isset($_SESSION['cot_gv'])) {
    $credit_covers = $order_total_modules->pre_confirmation_check();
  }


  if ( (is_array($payment_modules->modules)) && (count($payment_modules->modules) > 1) && (!is_object($$_SESSION['payment'])) && (!$credit_covers) ) {
    oos_redirect(oos_href_link($aContents['checkout_payment'], 'error_message=' . urlencode(decode($aLang['error_no_payment_module_selected'])), 'SSL'));
  }

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

// load the selected shipping module
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shipping.php';
  $shipping_modules = new shipping($_SESSION['shipping']);


// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=count($oOrder->products); $i<$n; $i++) {
      if (oos_check_stock($oOrder->products[$i]['id'], $oOrder->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      oos_redirect(oos_href_link($aContents['main_shopping_cart']));
    }
  }

  // links breadcrumb
  $oBreadcrumb->add(decode($aLang['navbar_title_1']), oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
  $oBreadcrumb->add(decode($aLang['navbar_title_2']));

  $aTemplate['page'] = $sTheme . '/modules/checkout_confirmation.tpl';

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
          'oos_heading_image' => 'confirmation.gif'
      )
  );

  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    $order_total_output = $order_total_modules->output();
    $smarty->assign('order_total_output', $order_total_output);
  }

  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
      $smarty->assign('confirmation', $confirmation);
    }
  }

  if (isset($$_SESSION['payment']->form_action_url)) {
    $form_action_url = $$_SESSION['payment']->form_action_url;
  } else {
    $form_action_url = oos_href_link($aContents['checkout_process'], '', 'SSL');
  }
  $smarty->assign('form_action_url', $form_action_url);

  if (is_array($payment_modules->modules)) {
    $payment_modules_process_button =  $payment_modules->process_button();
  }

  $smarty->assign('payment_modules_process_button', $payment_modules_process_button);
  $smarty->assign('order', $oOrder);

// display the template
$smarty->display($aTemplate['page']);

