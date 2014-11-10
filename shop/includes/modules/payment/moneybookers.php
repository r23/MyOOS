<?php
/* ----------------------------------------------------------------------
   $Id: moneybookers.php,v 1.3 2008/10/03 15:38:16 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: moneybookers.php,v 1.38 2003/01/28 12:00:00 gbunte_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class moneybookers {
    var $code, $title, $description, $enabled = false;

// class constructor
    function moneybookers() {
      global $oOrder, $aLang;

      $this->code = 'moneybookers';
      $this->title = $aLang['module_payment_moneybookers_text_title'];
      $this->description = $aLang['module_payment_moneybookers_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_MONEYBOOKERS_STATUS') && (MODULE_PAYMENT_MONEYBOOKERS_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_MONEYBOOKERS_SORT_ORDER') ? MODULE_PAYMENT_MONEYBOOKERS_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_MONEYBOOKERS_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_MONEYBOOKERS_ORDER_STATUS_ID;
      }

      $my_actionurl = 'https://www.moneybookers.com/app/payment.pl';
      if  (strlen(MODULE_PAYMENT_MONEYBOOKERS_REFID) <= '5') {
        $my_actionurl = $my_actionurl . '?rid=' . MODULE_PAYMENT_MONEYBOOKERS_REFID;
      }

      $this->form_action_url = $my_actionurl;
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

      if (MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE == 'Selected Language') {
        $my_language = 'EN';
      } else {
        $my_language = MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE;
      }

      if (MODULE_PAYMENT_MONEYBOOKERS_CURRENCY == 'Selected Currency') {
        $my_currency = $_SESSION['currency'];
      } else {
        $my_currency = substr(MODULE_PAYMENT_MONEYBOOKERS_CURRENCY, 5);
      }
      if (!in_array($my_currency, array('EUR', 'USD', 'GBP', 'HKD', 'SGD', 'JPY', 'CAD', 'AUD', 'CHF', 'DKK', 'SEK', 'NOK', 'ILS', 'MYR', 'NZD', 'TWD', 'THB', 'CZK', 'HUF', 'SKK', 'ISK', 'INR'))) {
        $my_currency = 'EUR';
      }

      $aFilename = oos_get_filename();
      $aModules = oos_get_modules();
      $process_button_string = oos_draw_hidden_field('pay_to_email', MODULE_PAYMENT_MONEYBOOKERS_ID) .
                               oos_draw_hidden_field('language', $my_language) .
                               oos_draw_hidden_field('amount', number_format($oOrder->info['total'] * $oCurrencies->get_value($my_currency), $oCurrencies->get_decimal_places($my_currency))) .
                               oos_draw_hidden_field('currency', $my_currency) .
                               oos_draw_hidden_field('detail1_description', STORE_NAME) .
                               oos_draw_hidden_field('detail1_text', 'Order - ' . date('d. M Y - H:i')) .
                               oos_draw_hidden_field('firstname', $oOrder->billing['firstname']) .
                               oos_draw_hidden_field('lastname', $oOrder->billing['lastname'] ) .
                               oos_draw_hidden_field('address', $oOrder->billing['street_address']) .
                               oos_draw_hidden_field('postal_code', $oOrder->billing['postcode']) .
                               oos_draw_hidden_field('city', $oOrder->billing['city']) .
                               oos_draw_hidden_field('country', $oOrder->billing['country']['moneybookers']) .
                               oos_draw_hidden_field('pay_from_email', $oOrder->customer['email_address']) .
                               oos_draw_hidden_field('return', oos_href_link($aModules['checkout'], $aFilename['checkout_process'], '', 'SSL')) .
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
        $this->_check = defined('MODULE_PAYMENT_MONEYBOOKERS_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'True', '6', '3', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_MONEYBOOKERS_ID', '', '6', '4', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_MONEYBOOKERS_REFID', '', '6', '7', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_MONEYBOOKERS_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_MONEYBOOKERS_CURRENCY', 'Selected Currency', '6', '5', 'oos_cfg_select_option(array(\'Selected Currency\',\'EUR\', \'USD\', \'GBP\', \'HKD\', \'SGD\', \'JPY\', \'CAD\', \'AUD\', \'CHF\', \'DKK\', \'SEK\', \'NOK\', \'ILS\', \'MYR\', \'NZD\', \'TWD\', \'THB\', \'CZK\', \'HUF\', \'SKK\', \'ISK\', \'INR\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE', 'Selected Language', '6', '6', 'oos_cfg_select_option(array(\'Selected Language\',\'EN\', \'DE\', \'ES\', \'FR\'), ', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_MONEYBOOKERS_STATUS', 'MODULE_PAYMENT_MONEYBOOKERS_ID', 'MODULE_PAYMENT_MONEYBOOKERS_REFID', 'MODULE_PAYMENT_MONEYBOOKERS_LANGUAGE', 'MODULE_PAYMENT_MONEYBOOKERS_CURRENCY', 'MODULE_PAYMENT_MONEYBOOKERS_SORT_ORDER');
    }
  }
?>
