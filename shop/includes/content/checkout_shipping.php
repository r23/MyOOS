<?php
/* ----------------------------------------------------------------------
   $Id: checkout_shipping.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_shipping.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_http_client.php';

// if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    oos_redirect(oos_href_link($aContents['main_shopping_cart']));
  }

// check for maximum order
  if ($_SESSION['cart']->show_total() > 0) {
    if ($_SESSION['cart']->show_total() > $_SESSION['customer_max_order']) {
      oos_redirect(oos_href_link($aContents['info_max_order']));
    }
  }


  if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);

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
  $oOrder = new order;

// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
  $_SESSION['cartID'] = $_SESSION['cart']->cartID;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
  if (($oOrder->content_type == 'virtual') || ($_SESSION['cart']->show_total() == 0) ) {
    $_SESSION['shipping'] = false;
    $_SESSION['sendto'] = false;
    oos_redirect(oos_href_link($aContents['checkout_payment'], '', 'SSL'));
  }

  $total_weight = $_SESSION['cart']->show_weight();
  $total_count = $_SESSION['cart']->count_contents();

// load all enabled shipping modules
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shipping.php';
  $shipping_modules = new shipping;

  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($oOrder->delivery['country_id'] == STORE_COUNTRY) $pass = true; break;

      case 'international':
        if ($oOrder->delivery['country_id'] != STORE_COUNTRY) $pass = true; break;

      case 'both':
        $pass = true; break;

      default:
        $pass = false; break;
    }

    $free_shipping = false;
    if ( ($pass == true) && ($oOrder->info['subtotal'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;

      require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/modules/order_total/ot_shipping.php';
    }
  } else {
    $free_shipping = false;
  }



// process the selected shipping method
  if ( isset($_POST['action']) && ($_POST['action'] == 'process') ) {
    if ( (isset($_POST['comments'])) && (empty($_POST['comments'])) ) {
      $_SESSION['comments'] = '';
    } else if (oos_is_not_null($_POST['comments'])) {
      $_SESSION['comments'] = oos_db_prepare_input($_POST['comments']);
    }

    if (isset($_POST['campaign_id']) && is_numeric($_POST['campaign_id'])) {
      $_SESSION['campaigns_id'] = intval($_POST['campaign_id']);
    }

    if ( (oos_count_shipping_modules() > 0) || ($free_shipping == true) ) {
      if ( (isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {
        $_SESSION['shipping'] = $_POST['shipping'];

        list($module, $method) = explode('_', $_SESSION['shipping']);
        if ( is_object($$module) || ($_SESSION['shipping'] == 'free_free') ) {
          if ($_SESSION['shipping'] == 'free_free') {
            $quote[0]['methods'][0]['title'] = $aLang['free_shipping_title'];
            $quote[0]['methods'][0]['cost'] = '0';
          } else {
            $quote = $shipping_modules->quote($method, $module);
          }
          if (isset($quote['error'])) {
            unset($_SESSION['shipping']);
          } else {
            if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
              $_SESSION['shipping'] = array('id' => $_SESSION['shipping'],
                                            'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                            'cost' => $quote[0]['methods'][0]['cost']);

              oos_redirect(oos_href_link($aContents['checkout_payment'], '', 'SSL'));
            }
          }
        } else {
          unset($_SESSION['shipping']);
        }
      }
    } else {
      $_SESSION['shipping'] = false;

      oos_redirect(oos_href_link($aContents['checkout_payment'], '', 'SSL'));
    }
  }

// get all available shipping quotes
  $quotes = $shipping_modules->quote();

// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
  if ( !isset($_SESSION['shipping']) || ( isset($_SESSION['shipping']) && ($_SESSION['shipping'] == false) && (oos_count_shipping_modules() > 1) ) ) $_SESSION['shipping'] = $shipping_modules->cheapest();
  list ($sess_class, $sess_method) = explode ('_', $_SESSION['shipping']['id']);

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['checkout_shipping'], '', 'SSL'));

  $aTemplate['page'] = $sTheme . '/modules/checkout_shipping.tpl';

  $nPageType = OOS_PAGE_TYPE_CHECKOUT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

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

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'delivery.gif',

          'sess_method'       => $sess_method,

          'counts_shipping_modules' => oos_count_shipping_modules(),
          'quotes'                  => $quotes,

          'free_shipping'                 => $free_shipping,
          'oos_free_shipping_description' => sprintf($aLang['free_shipping_description'], $oCurrencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER))
      )
  );


  // JavaScript
  $smarty->assign('popup_window', 'checkout_shipping.js');


// display the template
$smarty->display($aTemplate['page']);

