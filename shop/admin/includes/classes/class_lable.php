<?php
/* ----------------------------------------------------------------------
   $Id: class_lable.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: order.php,v 1.6 2003/02/06 17:37:10 thomasamoulton 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  class lable {
    var $info;
    var $totals;
    var $products;
    var $customer;
    var $delivery;

    function lable($order_id) {
      $this->info = array();
      $this->totals = array();
      $this->products = array();
      $this->customer = array();
      $this->delivery = array();

      $this->query($order_id);
    }

    function query($order_id) {

      // Get database information
      $dbconn =&oosDBGetConn();
      $oostable =& oosDBGetTables();

      $lable_query = "SELECT customers_name, customers_company, customers_street_address, customers_suburb,
                             customers_city, customers_postcode, customers_state, customers_country,
                             customers_telephone, customers_email_address, customers_address_format_id,
                             delivery_name, delivery_company, delivery_street_address, delivery_suburb,
                             delivery_city, delivery_postcode, delivery_state, delivery_country,
                             delivery_address_format_id, billing_name, billing_company, billing_street_address,
                             billing_suburb, billing_city, billing_postcode, billing_state, billing_country,
                             billing_address_format_id, payment_method, cc_type, cc_owner, cc_number,
                             cc_expires, currency, currency_value, date_purchased, orders_status, last_modified
                     FROM " . $oostable['orders'] . " 
                     WHERE orders_id = '" . oos_db_input($order_id) . "'";
      $lable_result = $dbconn->Execute($lable_query);
      $lable = $lable_result->fields;


      $totals_query = "SELECT title, text 
                       FROM " . $oostable['orders_total'] . " 
                       WHERE orders_id = '" . oos_db_input($order_id) . "'
                       ORDER BY sort_order";
      $totals_result = $dbconn->Execute($totals_query);

      while ($totals = $totals_result->fields) {
        $this->totals[] = array('title' => $totals['title'],
                                'text' => $totals['text']);

        // Move that ADOdb pointer!
        $totals_result->MoveNext();
      }

      $this->info = array('currency' => $lable['currency'],
                          'currency_value' => $lable['currency_value'],
                          'payment_method' => $lable['payment_method'],
                          'cc_type' => $lable['cc_type'],
                          'cc_owner' => $lable['cc_owner'],
                          'cc_number' => $lable['cc_number'],
                          'cc_expires' => $lable['cc_expires'],
                          'date_purchased' => $lable['date_purchased'],
                          'orders_status' => $lable['orders_status'],
                          'last_modified' => $lable['last_modified']);

      $this->customer = array('name' => $lable['customers_name'],
                              'company' => $lable['customers_company'],
                              'street_address' => $lable['customers_street_address'],
                              'suburb' => $lable['customers_suburb'],
                              'city' => $lable['customers_city'],
                              'postcode' => $lable['customers_postcode'],
                              'state' => $lable['customers_state'],
                              'country' => $lable['customers_country'],
                              'format_id' => $lable['customers_address_format_id'],
                              'telephone' => $lable['customers_telephone'],
                              'email_address' => $lable['customers_email_address']);

      $this->delivery = array('name' => $lable['delivery_name'],
                              'company' => $lable['delivery_company'],
                              'street_address' => $lable['delivery_street_address'],
                              'suburb' => $lable['delivery_suburb'],
                              'city' => $lable['delivery_city'],
                              'postcode' => $lable['delivery_postcode'],
                              'state' => $lable['delivery_state'],
                              'country' => $lable['delivery_country'],
                              'format_id' => $lable['delivery_address_format_id']);

      $this->billing = array('name' => $lable['billing_name'],
                             'company' => $lable['billing_company'],
                             'street_address' => $lable['billing_street_address'],
                             'suburb' => $lable['billing_suburb'],
                             'city' => $lable['billing_city'],
                             'postcode' => $lable['billing_postcode'],
                             'state' => $lable['billing_state'],
                             'country' => $lable['billing_country'],
                             'format_id' => $lable['billing_address_format_id']);

      $index = 0;

      $lables_products_query = "SELECT o.orders_products_id, o.products_id, o.products_name, o.products_model,
                                       o.products_price, o.products_tax, o.products_quantity, o.final_price,
                                       p.products_id, p.products_weight
                                FROM " . $oostable['orders_products'] . " o,
                                     " . $oostable['products'] . " p
                                WHERE o.products_id = p.products_id &&
                                      o.orders_id = '" . oos_db_input($order_id) . "'";
      $lables_products_result = $dbconn->Execute($lables_products_query);

      while ($lables_products = $lables_products_result->fields) {
        $this->products[$index] = array('qty' => $lables_products['products_quantity'],
                                        'name' => $lables_products['products_name'],
                                        'model' => $lables_products['products_model'],
                                        'tax' => $lables_products['products_tax'],
                                        'price' => $lables_products['products_price'],
                                        'weight' => $lables_products['products_weight'],
                                        'final_price' => $lables_products['final_price']);

        $subindex = 0;

        $attributes_query = "SELECT products_options, products_options_values, options_values_price, price_prefix
                             FROM " . $oostable['orders_products_attributes'] . "
                             WHERE orders_id = '" . oos_db_input($order_id) . "'
                               AND orders_products_id = '" . $orders_products['orders_products_id'] . "'";
        $attributes_result = $dbconn->Execute($attributes_query);

        if ($attributes_result->RecordCount()) {
          while ($attributes = $attributes_result->fields) {
            $this->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
                                                                     'value' => $attributes['products_options_values'],
                                                                     'prefix' => $attributes['price_prefix'],
                                                                     'price' => $attributes['options_values_price']);

            $subindex++;

            // Move that ADOdb pointer!
            $attributes_result->MoveNext();
          }
        }
        $index++;

        // Move that ADOdb pointer!
        $lables_products_result->MoveNext();
      }
    }
  }
?>