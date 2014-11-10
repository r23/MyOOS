<?php
/* ----------------------------------------------------------------------
   $Id: tcpayment_cc.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce Payment-Modul TeleCash Click&Pay easy
   Version 0.8 vom 23.03.2004

   (c) 2004: Dieter H�auf
   mailto:kontakt@dieter-hoerauf.de
   http://jana.dieter-hoerauf.de/

   ----------------------------------------------------------------------
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class tcpayment_cc {
    var $code, $title, $description, $enabled = false;

// class constructor
    function tcpayment_cc() {
      global $oOrder, $aLang;

      $this->code = 'tcpayment_cc';
      $this->title = $aLang['module_payment_tcpayment_cc_text_title'];
      $this->description = $aLang['module_payment_tcpayment_cc_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_TCPAYMENT_CC_STATUS') && (MODULE_PAYMENT_TCPAYMENT_CC_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_TCPAYMENT_CC_SORT_ORDER') ? MODULE_PAYMENT_TCPAYMENT_CC_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_TCPAYMENT_CC_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_TCPAYMENT_CC_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();

      // Ersetzen Sie hier die URL des TeleCash Testsystems durch die URL des Produktivsystems
      $this->form_action_url = 'https://easy-demo.tcinternet.de/hosting/servlet/WalletPage';
    }

// class methods
    function update_status() {
      global $oOrder;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_TCPAYMENT_CC_ZONE > 0) ) {
        $check_flag = false;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_TCPAYMENT_CC_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
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

 //     $tcphAmount = number_format($oOrder->info['total'] * $oCurrencies->currencies['EUR']['value'], $oCurrencies->currencies['EUR']['decimal_places']);
      $tcphAmount = number_format($oOrder->info['total'], $oCurrencies->currencies['EUR']['decimal_places']);
      $tcphCurrency = $oOrder->info['currency'];
      $tcphOrderDescription = date('ymdHm');
      $tcphSessionID = oos_session_id();

      $tcphPaymentInfo = array('tcph_amount' => $tcphAmount, 
                               'tcph_currency' => $tcphCurrency, 
                               'tcph_order_description' => $tcphOrderDescription, 
                               'tcph_session_id' => $tcphSessionID);

      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      oos_db_perform($oostable['telecash_info'], $tcphPaymentInfo);
      $tcphTransactionID = $dbconn->Insert_ID();

      $process_button_string = oos_draw_hidden_field('tcphMerchantID', MODULE_PAYMENT_TCPAYMENT_CC_ID) .
                               oos_draw_hidden_field('tcphTransactionID', $tcphTransactionID) .
                               oos_draw_hidden_field('tcphOrderDescription', $tcphOrderDescription) .
                               oos_draw_hidden_field('tcphPaymentType', 'CreditCard') .
                               oos_draw_hidden_field('tcphAmount', $tcphAmount) .
                               oos_draw_hidden_field('tcphCurrency', $tcphCurrency) .
                               oos_draw_hidden_field('tcphLanguage', $_SESSION['language']);

      return $process_button_string;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_TCPAYMENT_CC_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_PAYMENT_TCPAYMENT_CC_STATUS', 'True', '6', '3', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_TCPAYMENT_CC_ID', 'Your Merchant-ID', '6', '4', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_PAYMENT_TCPAYMENT_CC_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_PAYMENT_TCPAYMENT_CC_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) values ('MODULE_PAYMENT_TCPAYMENT_CC_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_TCPAYMENT_CC_STATUS', 'MODULE_PAYMENT_TCPAYMENT_CC_ID', 'MODULE_PAYMENT_TCPAYMENT_CC_ZONE', 'MODULE_PAYMENT_TCPAYMENT_CC_ORDER_STATUS_ID', 'MODULE_PAYMENT_TCPAYMENT_CC_SORT_ORDER');
    }
  }
?>
