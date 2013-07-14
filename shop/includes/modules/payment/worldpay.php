<?php
/* ----------------------------------------------------------------------
   $Id: worldpay.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: worldpay.php,v MS1a 2003/04/06 21:30
   ----------------------------------------------------------------------
   Author : Graeme Conkie (graeme@conkie.net)
   Title:   WorldPay Payment Callback Module V4.0 Version 1.4
   NOTE: YOU MUST CHANGE THE CALLBACK URL IN WP ADMIN TO <wpdisplay item="MC_callback">

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class worldpay {
    var $code, $title, $description, $enabled = false;

// class constructor
    function worldpay() {
      global $oOrder, $aLang;

      $this->code = 'worldpay';
      $this->title = $aLang['module_payment_worldpay_text_title'];
      $this->description = $aLang['module_payment_worldpay_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_WORLDPAY_STATUS') && (MODULE_PAYMENT_WORLDPAY_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_WORLDPAY_SORT_ORDER') ? MODULE_PAYMENT_WORLDPAY_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();

      $this->form_action_url = 'https://select.worldpay.com/wcc/purchase';

    }

// class methods
    function update_status() {
      global $oOrder;

      if ($_SESSION['shipping']['id'] == 'selfpickup_selfpickup') {
        $this->enabled = false;
      }

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_WORLDPAY_ZONE > 0) ) {
        $check_flag = false;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_IPAYMENT_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
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

// class methods
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

      $worldpay_cardId = oos_session_name() . '=' . oos_session_id();

      $aContents = oos_get_content();
      

      $callback_url = oos_href_link($aContents['checkout_process'], '', 'SSL', true);
      $worldpay_callback = explode('http://', $callback_url);

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $sLanguage = oos_var_prep_for_os($_SESSION['language']);

      $languagestable = $oostable['languages'];
      $query = "SELECT iso_639_1
                FROM $languagestable
                WHERE iso_639_2 = '" .  oos_db_input($sLanguage) . "'";
      $language_code = $dbconn->GetOne($query);

      $address = htmlspecialchars($oOrder->customer['street_address'] . "\n" . $oOrder->customer['suburb'] . "\n" . $oOrder->customer['city'] . "\n" . $oOrder->customer['state'], ENT_QUOTES);


      $process_button_string = oos_draw_hidden_field('instId', MODULE_PAYMENT_WORLDPAY_ID) .
                               oos_draw_hidden_field('currency', $_SESSION['currency']) .
                               oos_draw_hidden_field('desc', 'Purchase from '.STORE_NAME) .
                               oos_draw_hidden_field('cartId', $worldpay_cardId) .
                               oos_draw_hidden_field('amount', number_format($oOrder->info['total'] * $oCurrencies->get_value($_SESSION['currency']), $oCurrencies->get_decimal_places($_SESSION['currency']), '.', '')) ;

      if (MODULE_PAYMENT_WORLDPAY_USEPREAUTH == 'True') {
        $process_button_string .= oos_draw_hidden_field('authMode', MODULE_PAYMENT_WORLDPAY_PREAUTH);
      }

      $process_button_string .= oos_draw_hidden_field('testMode', MODULE_PAYMENT_WORLDPAY_MODE) .
                                oos_draw_hidden_field('name', $oOrder->customer['firstname'] . ' ' . $oOrder->customer['lastname']) .
                                oos_draw_hidden_field('address', $address) .
                                oos_draw_hidden_field('postcode', $oOrder->customer['postcode']) .
                                oos_draw_hidden_field('country', $oOrder->customer['country']['iso_code_2']) .
                                oos_draw_hidden_field('tel', $oOrder->customer['telephone']) .
                                oos_draw_hidden_field('myvar', 'Y') .
                                oos_draw_hidden_field('fax', $oOrder->customer['fax']) .
                                oos_draw_hidden_field('email', $oOrder->customer['email_address']) .
                                oos_draw_hidden_field('lang', $language_code) .
                                oos_draw_hidden_field('MC_callback', $worldpay_callback[1]) .
                                oos_draw_hidden_field('MC_oscsid', $oscSid);

      if (MODULE_PAYMENT_WORLDPAY_USEMD5 == '1') {
        $md5_signature_fields = 'amount:language:email';
        $md5_signature = MODULE_PAYMENT_WORLDPAY_MD5KEY . ':' . (number_format($oOrder->info['total'] * $oCurrencies->get_value($_SESSION['currency']), $oCurrencies->get_decimal_places($_SESSION['currency']), '.', '')) . ':' . $language_code . ':' . $oOrder->customer['email_address'];
        $md5_signature_md5 = md5($md5_signature);

        $process_button_string .= oos_draw_hidden_field('signatureFields', $md5_signature_fields ) .
                                  oos_draw_hidden_field('signature',$md5_signature_md5);
      }
      return $process_button_string ;
    }

    function before_process() {
      global $aLang;

      if (!isset($_GET['transStatus']) && $transStatus != "Y") { 
        $error = $aLang['module_payment_worldpay_text_error_1'];
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error);

        $aContents = oos_get_content();
        

        oos_redirect(oos_href_link($aContents['checkout_payment'], $payment_error_return, 'SSL', true, false));
      }
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $aLang;

      $error = array('title' => $aLang['module_payment_worldpay_text_error'],
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_WORLDPAY_STATUS');
      }

      return $this->_check;
    }


    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_WORLDPAY_STATUS', 'True', '6', '1', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_WORLDPAY_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_WORLDPAY_ID', '00000', '6', '2', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_WORLDPAY_MODE', '100', '6', '3', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_WORLDPAY_USEMD5', '0', '6', '4', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_WORLDPAY_MD5KEY', '', '6', '5', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_WORLDPAY_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_WORLDPAY_USEPREAUTH', 'False', '6', '6', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_WORLDPAY_PREAUTH', 'A', '6', '7', now())"); 
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_WORLDPAY_STATUS', 'MODULE_PAYMENT_WORLDPAY_ZONE', 'MODULE_PAYMENT_WORLDPAY_ID', 'MODULE_PAYMENT_WORLDPAY_MODE', 'MODULE_PAYMENT_WORLDPAY_USEMD5', 'MODULE_PAYMENT_WORLDPAY_MD5KEY', 'MODULE_PAYMENT_WORLDPAY_SORT_ORDER', 'MODULE_PAYMENT_WORLDPAY_USEPREAUTH', 'MODULE_PAYMENT_WORLDPAY_ORDER_STATUS_ID', 'MODULE_PAYMENT_WORLDPAY_PREAUTH');
    }
  }
?>
