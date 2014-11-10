<?php
/* ----------------------------------------------------------------------
   $Id: nochex.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: nochex.php,v 1.12 2003/01/29 19:57:15 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class nochex {
    var $code, $title, $description, $enabled = false;

// class constructor
    function nochex() {
      global $oOrder, $aLang;

      $this->code = 'nochex';
      $this->title = $aLang['module_payment_nochex_text_title'];
      $this->description = $aLang['module_payment_nochex_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_NOCHEX_STATUS') && (MODULE_PAYMENT_NOCHEX_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_NOCHEX_SORT_ORDER') ? MODULE_PAYMENT_NOCHEX_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_NOCHEX_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_NOCHEX_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();

      $this->form_action_url = 'https://www.nochex.com/nochex.dll/checkout';
    }

// class methods
    function update_status() {
      global $oOrder;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_NOCHEX_ZONE > 0) ) {
        $check_flag = false;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_NOCHEX_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
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

      $aFilename = oos_get_filename();
      $aModules = oos_get_modules();

      $process_button_string = oos_draw_hidden_field('cmd', '_xclick') .
                               oos_draw_hidden_field('email', MODULE_PAYMENT_NOCHEX_ID) .
                               oos_draw_hidden_field('amount', number_format($oOrder->info['total'] * $oCurrencies->currencies['GBP']['value'], $oCurrencies->currencies['GBP']['decimal_places'])) .
                               oos_draw_hidden_field('ordernumber', $_SESSION['customer_id'] . '-' . date('Ymdhis')) .
                               oos_draw_hidden_field('returnurl', oos_href_link($aModules['checkout'], $aFilename['checkout_process'], '', 'SSL')) .
                               oos_draw_hidden_field('cancel_return', oos_href_link($aModules['checkout'], $aFilename['checkout_payment'], '', 'SSL'));

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
        $this->_check = defined('MODULE_PAYMENT_NOCHEX_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_NOCHEX_STATUS', 'True', '6', '3', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_NOCHEX_ID', 'you@yourbuisness.com',  '6', '4', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_NOCHEX_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_NOCHEX_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_NOCHEX_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_NOCHEX_STATUS', 'MODULE_PAYMENT_NOCHEX_ID', 'MODULE_PAYMENT_NOCHEX_ZONE', 'MODULE_PAYMENT_NOCHEX_ORDER_STATUS_ID', 'MODULE_PAYMENT_NOCHEX_SORT_ORDER');
    }
  }
?>
