<?php
/* ----------------------------------------------------------------------
   $Id: checkout_process.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_process.php,v 1.6.2.1 2003/05/03 23:41:23 wilt 
   orig: checkout_process.php,v 1.125 2003/02/16 13:21:43 thomasamoulton 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_process.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';

// if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot(array('mode' => 'SSL', 'content' => $aContents['checkout_payment']));
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

  if (!isset($_SESSION['sendto'])) {
    oos_redirect(oos_href_link($aContents['checkout_payment'], '', 'SSL'));
  }

  if ( (oos_is_not_null(MODULE_PAYMENT_INSTALLED)) && (!isset($_SESSION['payment'])) ) {
    oos_redirect(oos_href_link($aContents['checkout_payment'], '', 'SSL'));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($_SESSION['cart']->cartID) && isset($_SESSION['cartID'])) {
    if ($_SESSION['cart']->cartID != $_SESSION['cartID']) {
      oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
    }
  }

// load selected payment module
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_payment.php';
  $payment_modules = new payment($_SESSION['payment']);

// load the selected shipping module
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shipping.php';
  $shipping_modules = new shipping($_SESSION['shipping']);

  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';
  $oOrder = new order;

  if ( (isset($_SESSION['shipping'])) && ($_SESSION['shipping']['id'] == 'free_free')) {
    if ( ($oOrder->info['total'] - $oOrder->info['shipping_cost']) < MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER ) {
      oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
    }
  }

// load the before_process function from the payment modules
  $payment_modules->before_process();

  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order_total.php';
  $order_total_modules = new order_total;

  $order_totals = $order_total_modules->process();

  $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
						  'customers_cid' => 
						  'customers_firstname' => $oOrder->customer['firstname'],
						  'customers_lastname' => $oOrder->customer['lastname'],
                          'customers_name' => $oOrder->customer['firstname'] . ' ' . $oOrder->customer['lastname'],
                          'customers_company' => $oOrder->customer['company'],
                          'customers_street_address' => $oOrder->customer['street_address'],
                          'customers_suburb' => $oOrder->customer['suburb'],
                          'customers_city' => $oOrder->customer['city'],
                          'customers_postcode' => $oOrder->customer['postcode'],
                          'customers_state' => $oOrder->customer['state'],
                          'customers_country' => $oOrder->customer['country']['title'],
                          'customers_telephone' => $oOrder->customer['telephone'],
                          'customers_email_address' => $oOrder->customer['email_address'],
                          'customers_address_format_id' => $oOrder->customer['format_id'],
                          'delivery_firstname' => $oOrder->delivery['firstname'],
                          'delivery_lastname' => $oOrder->delivery['lastname'],
                          'delivery_name' => $oOrder->delivery['firstname'] . ' ' . $oOrder->delivery['lastname'],
                          'delivery_company' => $oOrder->delivery['company'],
                          'delivery_street_address' => $oOrder->delivery['street_address'],
                          'delivery_suburb' => $oOrder->delivery['suburb'],
                          'delivery_city' => $oOrder->delivery['city'],
                          'delivery_postcode' => $oOrder->delivery['postcode'],
                          'delivery_state' => $oOrder->delivery['state'],
                          'delivery_country' => $oOrder->delivery['country']['title'],
                          'delivery_address_format_id' => $oOrder->delivery['format_id'],
                          'billing_firstname' => $oOrder->billing['firstname'],
                          'billing_lastname' => $oOrder->billing['lastname'],			  
                          'billing_name' => $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname'],
                          'billing_company' => $oOrder->billing['company'],
                          'billing_street_address' => $oOrder->billing['street_address'],
                          'billing_suburb' => $oOrder->billing['suburb'],
                          'billing_city' => $oOrder->billing['city'],
                          'billing_postcode' => $oOrder->billing['postcode'],
                          'billing_state' => $oOrder->billing['state'],
                          'billing_country' => $oOrder->billing['country']['title'],
                          'billing_address_format_id' => $oOrder->billing['format_id'],
                          'payment_method' => $oOrder->info['payment_method'],
                          'cc_type' => $oOrder->info['cc_type'],
                          'cc_owner' => $oOrder->info['cc_owner'],
                          'cc_number' => $oOrder->info['cc_number'],
                          'cc_expires' => $oOrder->info['cc_expires'],
                          'date_purchased' => 'now()',
                          'last_modified' => 'now()',
                          'campaigns' => $_SESSION['campaigns_id'],
                          'orders_status' => $oOrder->info['order_status'],
                          'currency' => $oOrder->info['currency'],
                          'currency_value' => $oOrder->info['currency_value'],
                          'orders_language' => $_SESSION['language']);

  oos_db_perform($oostable['orders'], $sql_data_array);
  $insert_id = $dbconn->Insert_ID();
  for ($i=0, $n=count($order_totals); $i<$n; $i++) {
    $sql_data_array = array('orders_id' => $insert_id,
                            'title' => $order_totals[$i]['title'],
                            'text' => $order_totals[$i]['text'],
                            'value' => $order_totals[$i]['value'],
                            'class' => $order_totals[$i]['code'],
                            'sort_order' => $order_totals[$i]['sort_order']);
    oos_db_perform($oostable['orders_total'], $sql_data_array);
  }

  $customer_notification = ($oEvent->installed_plugin('reviews')) ? '1' : '0';
  $sql_data_array = array('orders_id' => $insert_id,
                          'orders_status_id' => $oOrder->info['order_status'],
                          'date_added' => 'now()',
                          'customer_notified' => $customer_notification,
                          'comments' => $oOrder->info['comments']);
  oos_db_perform($oostable['orders_status_history'], $sql_data_array);

// initialized for the email confirmation
  $products_ordered = '';
  $subtotal = 0;
  $total_tax = 0;

  for ($i=0, $n=count($oOrder->products); $i<$n; $i++) {
    // Stock Update - Joao Correia
    if (STOCK_LIMITED == 'true') {
      if (DOWNLOAD_ENABLED == 'true') {
        $productstable = $oostable['products'];
        $products_attributestable = $oostable['products_attributes'];
        $products_attributes_downloadtable = $oostable['products_attributes_download'];
        $stock_result_raw = "SELECT products_quantity, pad.products_attributes_filename 
                             FROM $productstable p LEFT JOIN 
                                  $products_attributestable pa ON p.products_id = pa.products_id LEFT JOIN 
                                  $products_attributes_downloadtable pad ON pa.products_attributes_id = pad.products_attributes_id
                             WHERE p.products_id = '" . intval(oos_get_product_id($oOrder->products[$i]['id'])) . "'";
        // Will work with only one option for downloadable products
        // otherwise, we have to build the query dynamically with a loop
        $products_attributes = $oOrder->products[$i]['attributes'];
        if (is_array($products_attributes)) {
          $stock_result_raw .= " AND pa.options_id = '" . intval($products_attributes[0]['option_id']) . "' AND pa.options_values_id = '" . intval($products_attributes[0]['value_id']) . "'";
        }
        $stock_result = $dbconn->Execute($stock_result_raw);
      } else {
        $productstable = $oostable['products'];
        $sql = "SELECT products_quantity
                FROM $productstable
                WHERE products_id = '" . intval(oos_get_product_id($oOrder->products[$i]['id'])) . "'";
        $stock_result = $dbconn->Execute($sql);
      }
      if ($stock_result->RecordCount() > 0) {
        $stock_values = $stock_result->fields;
        // do not decrement quantities if products_attributes_filename exists
        if ((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename'])) {
          $stock_left = $stock_values['products_quantity'] - $oOrder->products[$i]['qty'];
        } else {
          $stock_left = $stock_values['products_quantity'];
        }
        $productstable = $oostable['products'];
        $dbconn->Execute("UPDATE $productstable
                          SET products_quantity = '" . oos_db_input($stock_left) . "'
                          WHERE products_id = '" . intval(oos_get_product_id($oOrder->products[$i]['id'])) . "'");
        if ($stock_left < 1) {
          $productstable = $oostable['products'];
          $dbconn->Execute("UPDATE $productstable
                        SET products_status = '0' 
                        WHERE products_id = '" . intval(oos_get_product_id($oOrder->products[$i]['id'])) . "'");
        }
      }
    }

// Update products_ordered (for bestsellers list)
    $productstable = $oostable['products'];
    $dbconn->Execute("UPDATE $productstable
                  SET products_ordered = products_ordered + " . sprintf('%d', intval($oOrder->products[$i]['qty'])) . " 
                  WHERE products_id = '" . intval(oos_get_product_id($oOrder->products[$i]['id'])) . "'");

    $sql_data_array = array('orders_id' => $insert_id, 
                            'products_id' => oos_get_product_id($oOrder->products[$i]['id']),
                            'products_model' => $oOrder->products[$i]['model'],
                            'products_ean' => $oOrder->products[$i]['ean'],
                            'products_name' => $oOrder->products[$i]['name'],
                            'products_price' => $oOrder->products[$i]['price'],
                            'final_price' => $oOrder->products[$i]['final_price'],
                            'products_tax' => $oOrder->products[$i]['tax'],
                            'products_quantity' => $oOrder->products[$i]['qty']);
    oos_db_perform($oostable['orders_products'], $sql_data_array);
    $order_products_id = $dbconn->Insert_ID();
    $order_total_modules->update_credit_account($i);//ICW ADDED FOR CREDIT CLASS SYSTEM
//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';
    if (isset($oOrder->products[$i]['attributes'])) {
      $attributes_exist = '1';
      for ($j=0, $n2=count($oOrder->products[$i]['attributes']); $j<$n2; $j++) {
        if (DOWNLOAD_ENABLED == 'true') {

          $products_optionstable = $oostable['products_options'];
          $products_options_valuestable = $oostable['products_options_values'];
          $products_attributestable = $oostable['products_attributes'];
          $products_attributes_downloadtable = $oostable['products_attributes_download'];

          if ($oOrder->products[$i]['attributes'][$j]['value_id'] == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
            $attributes_result = "SELECT popt.products_options_name, poval.products_options_values_name,
                                         pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, 
                                         pad.products_attributes_maxcount , pad.products_attributes_filename 
                                  FROM $products_optionstable popt,
                                       $products_options_valuestable poval,
                                       $products_attributestable pa LEFT JOIN
                                       $products_attributes_downloadtable pad ON pa.products_attributes_id = pad.products_attributes_id
                                 WHERE pa.products_id = '" . intval($oOrder->products[$i]['id']) . "'
                                   AND pa.options_id = '" . intval($oOrder->products[$i]['attributes'][$j]['option_id']) . "'
                                   AND pa.options_id = popt.products_options_id
                                   AND popt.products_options_languages_id = '" .  intval($nLanguageID) . "'";
          } else {
            $attributes_result = "SELECT popt.products_options_name, poval.products_options_values_name,
                                         pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, 
                                         pad.products_attributes_maxcount , pad.products_attributes_filename 
                                  FROM $products_optionstable popt,
                                       $products_options_valuestable poval,
                                       $products_attributestable pa LEFT JOIN
                                       $products_attributes_downloadtable pad ON pa.products_attributes_id = pad.products_attributes_id
                                  WHERE pa.products_id = '" . intval($oOrder->products[$i]['id']) . "'
                                    AND pa.options_id = '" . intval($oOrder->products[$i]['attributes'][$j]['option_id']) . "'
                                    AND pa.options_id = popt.products_options_id
                                    AND pa.options_values_id = '" . intval($oOrder->products[$i]['attributes'][$j]['value_id']) . "'
                                    AND pa.options_values_id = poval.products_options_values_id
                                    AND popt.products_options_languages_id = '" .  intval($nLanguageID) . "'
                                    AND poval.products_options_values_languages_id = '" .  intval($nLanguageID) . "'";
          }
          $attributes = $dbconn->Execute($attributes_result);

        } else {

          $products_optionstable = $oostable['products_options'];
          $products_options_valuestable = $oostable['products_options_values'];
          $products_attributestable = $oostable['products_attributes'];

          if ($oOrder->products[$i]['attributes'][$j]['value_id'] == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
            $sql = "SELECT popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix 
                    FROM $products_optionstable popt,
                         $products_options_valuestable poval,
                         $products_attributestable pa
                    WHERE pa.products_id = '" . intval($oOrder->products[$i]['id']) . "'
                      AND pa.options_id = '" . intval($oOrder->products[$i]['attributes'][$j]['option_id']) . "'
                      AND pa.options_id = popt.products_options_id
                      AND popt.products_options_languages_id = '" .  intval($nLanguageID) . "'";
          } else {
            $sql = "SELECT popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix 
                    FROM $products_optionstable popt,
                         $products_options_valuestable poval,
                         $products_attributestable pa
                    WHERE pa.products_id = '" . intval($oOrder->products[$i]['id']) . "'
                      AND pa.options_id = '" . intval($oOrder->products[$i]['attributes'][$j]['option_id']) . "'
                      AND pa.options_id = popt.products_options_id
                      AND pa.options_values_id = '" . intval($oOrder->products[$i]['attributes'][$j]['value_id']) . "' 
                      AND pa.options_values_id = poval.products_options_values_id 
                      AND popt.products_options_languages_id = '" .  intval($nLanguageID) . "' 
                      AND poval.products_options_values_languages_id = '" .  intval($nLanguageID) . "'";
          }
          $attributes = $dbconn->Execute($sql);
        }
        $attributes_values = $attributes->fields;
        $sql_data_array = array('orders_id' => $insert_id,
                                'orders_products_id' => $order_products_id,
                                'products_options' => $attributes_values['products_options_name'],
                                'products_options_values' => $oOrder->products[$i]['attributes'][$j]['value'],
                                'options_values_price' => $attributes_values['options_values_price'],
                                'price_prefix' => $attributes_values['price_prefix']);
        // insert
        oos_db_perform($oostable['orders_products_attributes'], $sql_data_array);

        if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && oos_is_not_null($attributes_values['products_attributes_filename'])) {
          $sql_data_array = array('orders_id' => $insert_id, 
                                  'orders_products_id' => $order_products_id, 
                                  'orders_products_filename' => $attributes_values['products_attributes_filename'], 
                                  'download_maxdays' => $attributes_values['products_attributes_maxdays'], 
                                  'download_count' => $attributes_values['products_attributes_maxcount']);
          // insert
          oos_db_perform($oostable['orders_products_download'], $sql_data_array);
        }
        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . oos_decode_special_chars($oOrder->products[$i]['attributes'][$j]['value']);
      }
    }
//------insert customer choosen option eof ----
    $total_weight += ($oOrder->products[$i]['qty'] * $oOrder->products[$i]['weight']);
    $total_tax += oos_calculate_tax($total_products_price, $products_tax) * $oOrder->products[$i]['qty'];
    $total_cost += $total_products_price;

    $products_ordered .= $oOrder->products[$i]['qty'] . ' x ' . $oOrder->products[$i]['name'] . ' (' . $oOrder->products[$i]['model'] . ') = ' . $oCurrencies->display_price($oOrder->products[$i]['final_price'], $oOrder->products[$i]['tax'], $oOrder->products[$i]['qty']) . $products_ordered_attributes . "\n";
  }
  $order_total_modules->apply_credit();

  // lets start with the email confirmation
  $email_order = STORE_NAME . "\n" .
                 $aLang['email_separator'] . "\n" .
                 $aLang['email_text_order_number'] . ' ' . $insert_id . "\n" .
                 $aLang['email_text_invoice_url'] . ' ' . oos_href_link($aContents['account_history_info'], 'order_id=' . $insert_id, 'SSL', false) . "\n" .
                 $aLang['email_text_date_ordered'] . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n";
  if ($oOrder->info['comments']) {
    $email_order .= oosDBOutput($oOrder->info['comments']) . "\n\n";
  }

  if (isset($_SESSION['campaigns_id'])) {
    $email_order .= $aLang['email_separator'] . "\n" .
                    $aLang['email_text_campaigns'] . "\n" .
                    oos_get_campaigns_name($_SESSION['campaigns_id']) . "\n\n";
  }


  $email_order .= $aLang['email_text_products'] . "\n" .
                  $aLang['email_separator'] . "\n" .
                  $products_ordered .
                  $aLang['email_separator'] . "\n";

  for ($i=0, $n=count($order_totals); $i<$n; $i++) {
    $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "\n";
  }

  if ($oOrder->content_type != 'virtual') {
    $email_order .= "\n" . $aLang['email_text_delivery_address'] . "\n" .
                    $aLang['email_separator'] . "\n" .
                    oos_address_label($_SESSION['customer_id'], $_SESSION['sendto'], 0, '', "\n") . "\n";
  }

  $email_order .= "\n" . $aLang['email_text_billing_address'] . "\n" .
                  $aLang['email_separator'] . "\n" .
                  oos_address_label($_SESSION['customer_id'], $_SESSION['billto'], 0, '', "\n") . "\n\n";
  if (is_object($$_SESSION['payment'])) {
    $email_order .= $aLang['email_text_payment_method'] . "\n" .
                    $aLang['email_separator'] . "\n";
    $payment_class = $$_SESSION['payment'];
    $email_order .= $payment_class->title . "\n\n";
    if ($payment_class->email_footer) { 
      $email_order .= $payment_class->email_footer . "\n\n";
    }
  }
  if (!isset($_SESSION['man_key'])) {
    oos_mail($oOrder->customer['firstname'] . ' ' . $oOrder->customer['lastname'], $oOrder->customer['email_address'], $aLang['email_text_subject'], nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
  }

// send emails to other people
  if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
    if (SEND_BANKINFO_TO_ADMIN == 'true') {
      if ($_POST['banktransfer_fax'] != "on"){
        $email_order .= "\n";
        $email_order .= "Kontoinhaber: ". $banktransfer_owner . "\n";
        $email_order .= "BLZ:          ". $banktransfer_blz . "\n";
        $email_order .= "Konto:        ". $banktransfer_number . "\n";
        $email_order .= "Bank:         ". $banktransfer_bankname . "\n";

        if ($_POST['banktransfer_status'] == 0 || $_POST['banktransfer_status'] == 2){
          $email_order .= "Puerfstatus:   OK\r\n";
        } else {
          $email_order .= "Puerfstatus:   Es ist ein Problem aufgetreten, bitte beobachten!\r\n";
        }
      } else {
        $email_order .= "\n";
        $email_order .= "Kontodaten werden per Fax bestaetigt!\n";
      }
    }
    oos_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $aLang['email_text_subject'], nl2br($email_order), $oOrder->customer['firstname'] . ' ' . $oOrder->customer['lastname'], $oOrder->customer['email_address'], true);
  }



// load the after_process function from the payment modules
  $payment_modules->after_process();

  $order_total_modules->sendFax();
  $order_total_modules->sendSMS();

  $_SESSION['cart']->reset(true);

// unregister session variables used during checkout
  unset($_SESSION['sendto']);
  unset($_SESSION['billto']);
  unset($_SESSION['shipping']);
  unset($_SESSION['payment']);
  unset($_SESSION['comments']);

  $order_total_modules->clear_posts();

  oos_redirect(oos_href_link($aContents['checkout_success'], '', 'SSL'));

