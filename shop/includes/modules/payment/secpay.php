<?php
/* ----------------------------------------------------------------------
   $Id: secpay.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: secpay.php,v 1.31 2003/01/29 19:57:15 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class secpay {
    var $code, $title, $description, $enabled = false;

// class constructor
    function secpay() {
      global $oOrder, $aLang;

      $this->code = 'secpay';
      $this->title = $aLang['module_payment_secpay_text_title'];
      $this->description = $aLang['module_payment_secpay_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_SECPAY_STATUS') && (MODULE_PAYMENT_SECPAY_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_SECPAY_SORT_ORDER') ? MODULE_PAYMENT_SECPAY_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_SECPAY_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_SECPAY_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();

      $this->form_action_url = 'https://www.secpay.com/java-bin/ValCard';
    }

// class methods
    function update_status() {
      global $oOrder;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_SECPAY_ZONE > 0) ) {
        $check_flag = false;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_SECPAY_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
        while ($check = $check_result->fields) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $oOrder->billing['zone_id']) {
            $check_flag = true;
            break;
          }
          // Move that ADOdb pointer!
          $check_result->MoveNext();
        }

        // Close result set
        $check_result->Close();

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      global $oOrder, $oCurrencies;

      switch (MODULE_PAYMENT_SECPAY_CURRENCY) {
        case 'Default Currency':
          $sec_currency = DEFAULT_CURRENCY;
          break;
        case 'Any Currency':
        default:
          $sec_currency = $_SESSION['currency'];
          break;
      }

      switch (MODULE_PAYMENT_SECPAY_TEST_STATUS) {
        case 'Always Fail':
          $test_status = 'false';
          break;
        case 'Production':
          $test_status = 'live';
          break;
        case 'Always Successful':
        default:
          $test_status = 'true';
          break;
      }

      $aContents = oos_get_content();
      
      $process_button_string = oos_draw_hidden_field('merchant', MODULE_PAYMENT_SECPAY_MERCHANT_ID) .
                               oos_draw_hidden_field('trans_id', STORE_NAME . date('Ymdhis')) .
                               oos_draw_hidden_field('amount', number_format($oOrder->info['total'] * $oCurrencies->get_value($sec_currency), $oCurrencies->currencies[$sec_currency]['decimal_places'], '.', '')) .
                               oos_draw_hidden_field('bill_name', $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname']) .
                               oos_draw_hidden_field('bill_addr_1', $oOrder->billing['street_address']) .
                               oos_draw_hidden_field('bill_addr_2', $oOrder->billing['suburb']) .
                               oos_draw_hidden_field('bill_city', $oOrder->billing['city']) .
                               oos_draw_hidden_field('bill_state', $oOrder->billing['state']) .
                               oos_draw_hidden_field('bill_post_code', $oOrder->billing['postcode']) .
                               oos_draw_hidden_field('bill_country', $oOrder->billing['country']['title']) .
                               oos_draw_hidden_field('bill_tel', $oOrder->customer['telephone']) .
                               oos_draw_hidden_field('bill_email', $oOrder->customer['email_address']) .
                               oos_draw_hidden_field('ship_name', $oOrder->delivery['firstname'] . ' ' . $oOrder->delivery['lastname']) .
                               oos_draw_hidden_field('ship_addr_1', $oOrder->delivery['street_address']) .
                               oos_draw_hidden_field('ship_addr_2', $oOrder->delivery['suburb']) .
                               oos_draw_hidden_field('ship_city', $oOrder->delivery['city']) .
                               oos_draw_hidden_field('ship_state', $oOrder->delivery['state']) .
                               oos_draw_hidden_field('ship_post_code', $oOrder->delivery['postcode']) .
                               oos_draw_hidden_field('ship_country', $oOrder->delivery['country']['title']) .
                               oos_draw_hidden_field('currency', $sec_currency) .
                               oos_draw_hidden_field('callback', oos_href_link($aContents['checkout_process'], '', 'SSL', false) . ';' . oos_href_link($aContents['checkout_payment'], 'payment_error=' . $this->code, 'SSL', false)) .
                               oos_draw_hidden_field(oos_session_name(), oos_session_id()) .
                               oos_draw_hidden_field('options', 'test_status=' . $test_status . ',dups=false,cb_post=true,cb_flds=' . oos_session_name());

      return $process_button_string;
    }

    function before_process() {

      $aContents = oos_get_content();
      

      if ($_POST['valid'] == 'true') {
        if ($remote_host = oos_server_get_var('REMOTE_HOST')) {
          if ($remote_host != 'secpay.com') {
            $remote_host = @gethostbyaddr($remote_host);
          }
          if ($remote_host != 'secpay.com') {
            oos_redirect(oos_href_link($aContents['checkout_payment'], oos_session_name() . '=' . $_POST[oos_session_name()] . '&payment_error=' . $this->code, 'SSL', false, false));
          }
        } else {
          oos_redirect(oos_href_link($aContents['checkout_payment'], oos_session_name() . '=' . $_POST[oos_session_name()] . '&payment_error=' . $this->code, 'SSL', false, false));
        }
      }
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $aLang;

      if (isset($_GET['message']) && (strlen($_GET['message']) > 0)) {
        $error = stripslashes(urldecode($_GET['message']));
      } else {
        $error = $aLang['module_payment_secpay_text_error_message'];
      }

      return array('title' => $aLang['module_payment_secpay_text_error'],
                   'error' => $error);
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_SECPAY_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_SECPAY_STATUS', 'True', '6', '1', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_SECPAY_MERCHANT_ID', 'secpay', '6', '2', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_SECPAY_CURRENCY', 'Any Currency', '6', '3', 'oos_cfg_select_option(array(\'Any Currency\', \'Default Currency\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_SECPAY_TEST_STATUS', 'Always Successful', '6', '4', 'oos_cfg_select_option(array(\'Always Successful\', \'Always Fail\', \'Production\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_SECPAY_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_SECPAY_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_SECPAY_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_SECPAY_STATUS', 'MODULE_PAYMENT_SECPAY_MERCHANT_ID', 'MODULE_PAYMENT_SECPAY_CURRENCY', 'MODULE_PAYMENT_SECPAY_TEST_STATUS', 'MODULE_PAYMENT_SECPAY_ZONE', 'MODULE_PAYMENT_SECPAY_ORDER_STATUS_ID', 'MODULE_PAYMENT_SECPAY_SORT_ORDER');
    }
  }
?>
