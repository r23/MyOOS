<?php
/* ----------------------------------------------------------------------
   $Id: paypal.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: paypal.php,v 1.39 2003/01/29 19:57:15 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class paypal {
    var $code, $title, $description, $enabled = FALSE;

// class constructor
    function paypal() {
      global $oOrder, $aLang;

      $this->code = 'paypal';
      $this->title = $aLang['module_payment_paypal_text_title'];
      $this->description = $aLang['module_payment_paypal_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_PAYPAL_STATUS') && (MODULE_PAYMENT_PAYPAL_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_PAYPAL_SORT_ORDER') ? MODULE_PAYMENT_PAYPAL_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();

      $this->form_action_url = 'https://www.paypal.com/de/cgi-bin/webscr';
    }

// class methods
    function update_status() {
      global $oOrder;

      if ( ($this->enabled == TRUE) && ((int)MODULE_PAYMENT_PAYPAL_ZONE > 0) ) {
        $check_flag = FALSE;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_PAYPAL_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
        while ($check = $check_result->fields) {
          if ($check['zone_id'] < 1) {
            $check_flag = TRUE;
            break;

          } elseif ($check['zone_id'] == $oOrder->billing['zone_id']) {
            $check_flag = TRUE;
            break;

          }

          // Move that ADOdb pointer!
          $check_result->MoveNext();
        }

        // Close result set
        $check_result->Close();

        if ($check_flag == FALSE) {
          $this->enabled = FALSE;
        }
      }
    }

    function javascript_validation() {
      return FALSE;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return FALSE;
    }

    function confirmation() {
      return FALSE;
    }

    function process_button() {
      global $oOrder, $oCurrencies;

      if (MODULE_PAYMENT_PAYPAL_CURRENCY == 'Selected Currency') {
        $my_currency = $_SESSION['currency'];
      } else {
        $my_currency = substr(MODULE_PAYMENT_PAYPAL_CURRENCY, 5);
      }
      if (!in_array($my_currency, array('CAD', 'EUR', 'GBP', 'JPY', 'USD'))) {
        $my_currency = 'EUR';
      }
      $aContents = oos_get_content();

      $process_button_string = oos_draw_hidden_field('cmd', '_xclick') .
                               oos_draw_hidden_field('business', MODULE_PAYMENT_PAYPAL_ID) .
                               oos_draw_hidden_field('item_name', oos_replace_chars(STORE_NAME)) .
                               oos_draw_hidden_field('amount', number_format(($oOrder->info['total'] - $oOrder->info['shipping_cost']) * $oCurrencies->get_value($my_currency), $oCurrencies->get_decimal_places($my_currency))) .
                               oos_draw_hidden_field('first_name', oos_replace_chars($oOrder->billing['firstname'])) .
                               oos_draw_hidden_field('last_name', oos_replace_chars($oOrder->billing['lastname'])) .
                               oos_draw_hidden_field('address1', oos_replace_chars($oOrder->billing['street_address'])) .
                               oos_draw_hidden_field('address2', oos_replace_chars($oOrder->billing['suburb'])) .
                               oos_draw_hidden_field('city', oos_replace_chars($oOrder->billing['city'])) .
                               oos_draw_hidden_field('state', oos_replace_chars($oOrder->billing['state'])) .
                               oos_draw_hidden_field('zip', $oOrder->billing['postcode']) .
                               oos_draw_hidden_field('lc', $oOrder->billing['country']['iso_code_2']) .
                               oos_draw_hidden_field('email', $oOrder->customer['email_address']) .
                               oos_draw_hidden_field('shipping', number_format($oOrder->info['shipping_cost'] * $oCurrencies->get_value($my_currency), $oCurrencies->get_decimal_places($my_currency))) .
                               oos_draw_hidden_field('currency_code', $my_currency) .
                               oos_draw_hidden_field('rm', '2') .
                               oos_draw_hidden_field('bn', 'MyOOS [Shopsystem]') .
                               oos_draw_hidden_field('no_note', '1');
      $process_button_string .= '<input type="hidden" name="return" value="' . oos_href_link($aContents['checkout_process'], '', 'SSL') . '" >';
      $process_button_string .= '<input type="hidden" name="cancel_return" value="' . oos_href_link($aContents['checkout_payment'], '', 'SSL') . '" >';


      return $process_button_string;
    }

    function before_process() {
      return FALSE;
    }

    function after_process() {
      return FALSE;
    }

    function output_error() {
      return FALSE;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_PAYPAL_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYPAL_STATUS', 'True', '6', '3', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_PAYPAL_ID', 'you@yourbusiness.com', '6', '4', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYPAL_CURRENCY', 'Selected Currency', '6', '6', 'oos_cfg_select_option(array(\'Selected Currency\',\'Only USD\',\'Only CAD\',\'Only EUR\',\'Only GBP\',\'Only JPY\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_PAYPAL_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_PAYPAL_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PAYPAL_STATUS', 'MODULE_PAYMENT_PAYPAL_ID', 'MODULE_PAYMENT_PAYPAL_CURRENCY', 'MODULE_PAYMENT_PAYPAL_ZONE', 'MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID', 'MODULE_PAYMENT_PAYPAL_SORT_ORDER');
    }
  }
