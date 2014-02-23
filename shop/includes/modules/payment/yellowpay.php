<?php
/* ----------------------------------------------------------------------
   $Id: yellowpay.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


  class yellowpay {
    var $code, $title, $description, $enabled;

// class constructor
    function yellowpay() {
      global $order, $aLang;

      $this->code = 'yellowpay';
      $this->title = $aLang['module_payment_yellowpay_text_title'];
      $this->description = $aLang['module_payment_yellowpay_text_description'];
      $this->sort_order = MODULE_PAYMENT_YELLOWPAY_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_YELLOWPAY_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_YELLOWPAY_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_YELLOWPAY_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      $this->form_action_url = 'https://yellowpaytest.postfinance.ch/checkout/Yellowpay.aspx?userctrl=Invisible';
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_YELLOWPAY_ZONE > 0) ) {
        $check_flag = false;

        $db =& oosDBGetConn();
        $oosDBTable = oosDBGetTables();

        $check_result = $db->Execute("SELECT zone_id FROM " . $oosDBTable['zones_to_geo_zones'] . " WHERE geo_zone_id = '" . MODULE_PAYMENT_YELLOWPAY_ZONE . "' AND zone_country_id = '" . $order->billing['country']['id'] . "' ORDER BY zone_id");
        while ($check = $check_result->fields) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;

          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;

          }
          $check_result->MoveNext();
        }

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
      global $order, $oCurrencies;

      if (MODULE_PAYMENT_YELLOWPAY_CURRENCY == 'Selected Currency') {
        $my_currency = $_SESSION['currency'];
      } else {
        $my_currency = substr(MODULE_PAYMENT_YELLOWPAY_CURRENCY, 5);
      }
      if (!in_array($my_currency, array('CHF', 'EUR', 'USD'))) {
        $my_currency = 'CHF';
      }


      switch ($_SESSION['language']) {

        case 'deu':
          $usedlanguage = 2055;
          break;

        case 'eng':
          $usedlanguage = 2057;
          break;

        case 'ita':
          $usedlanguage = 2064;
          break;

        default:
          $usedlanguage = MODULE_PAYMENT_YELLOWPAY_LANGUAGE;
          break;
      }


      $sidretour = oos_session_name() . '=' . oos_session_id();
      $usedtotal = number_format($order->info['total'] * $oCurrencies->get_value($my_currency), $oCurrencies->get_decimal_places($my_currency));

      $txtHash_tosecure = MODULE_PAYMENT_YELLOWPAY_SHOP_ID . $my_currency . $usedtotal . MODULE_PAYMENT_HASH_SEED;
      $txtHash = md5($txtHash_tosecure);

      $txtOrderIDShop = $_SESSION['customer_id'] . date("Y-m-d H:i:s");

      $process_button_string = oos_draw_hidden_field('txtShopID', MODULE_PAYMENT_YELLOWPAY_ID) .
                               oos_draw_hidden_field('txtShopPara', $sidretour) .
                               oos_draw_hidden_field('txtOrderTotal', $usedtotal) .
                               oos_draw_hidden_field('txtLangVersion', $usedlanguage) .
                               oos_draw_hidden_field('txtArtCurrency', $my_currency) .
                               oos_draw_hidden_field('txtHash', $txtHash) .
                               oos_draw_hidden_field('txtOrderIDShop', $txtOrderIDShop) .
                               oos_draw_hidden_field('txtBLastName', $order->billing['lastname']) .
                               oos_draw_hidden_field('txtBFirstName', $order->billing['firstname']) .
                               oos_draw_hidden_field('txtBAddr1', $order->billing['street_address']) .
                               oos_draw_hidden_field('txtBZipCode', $order->billing['postcode']) .
                               oos_draw_hidden_field('txtBCity', oos_replace_chars($order->billing['city'])) .
                               oos_draw_hidden_field('txtBZipCode', $order->billing['postcode']) .
                               oos_draw_hidden_field('txtBTel', $order->customer['customers_telephone']) .
                               oos_draw_hidden_field('txtBEmail', $order->customer['email_address']) .
                               oos_draw_hidden_field('txtHistoryBack', 'false');
      return $process_button_string;

    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function output_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_YELLOWPAY_STATUS');
      }

      return $this->_check;
    }

    function install() {

      $db =& oosDBGetConn();
      $oosDBTable = oosDBGetTables();

      $db->Execute("INSERT INTO " . $oosDBTable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_YELLOWPAY_STATUS', 'True', '6', '3', 'oosCfgSelectOption(array(\'True\', \'False\'), ', now())");
      $db->Execute("INSERT INTO " . $oosDBTable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_YELLOWPAY_ID', 'shop1000_yp', '6', '4', now())");
      $db->Execute("INSERT INTO " . $oosDBTable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_YELLOWPAY_SHOP_ID', '', '6', '5', now())");
      $db->Execute("INSERT INTO " . $oosDBTable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_HASH_SEED', '', '6', '6', now())");
      $db->Execute("INSERT INTO " . $oosDBTable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_YELLOWPAY_CURRENCY', 'Selected Currency', '6', '7', 'oosCfgSelectOption(array(\'Selected Currency\',\'Only CHF\',\'Only EUR\',\'Only USD\'), ', now())");
      $db->Execute("INSERT INTO " . $oosDBTable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_YELLOWPAY_SORT_ORDER', '0', '6', '9', now())");
      $db->Execute("INSERT INTO " . $oosDBTable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_YELLOWPAY_ZONE', '0', '6', '10', 'oosCfgGetZoneClassTitle', 'oosCfgPullDownZoneClasses(', now())");
      $db->Execute("INSERT INTO " . $oosDBTable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_YELLOWPAY_LANGUAGE', '2055', '6', '11', now())");
      $db->Execute("INSERT INTO " . $oosDBTable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_YELLOWPAY_ORDER_STATUS_ID', '0', '6', '12', 'oosCfgPullDownOrderStatuses(', 'oosCfgGetOrderStatusName', now())");
    }

    function remove() {

      $db =& oosDBGetConn();
      $oosDBTable = oosDBGetTables();

      $db->Execute("DELETE FROM " . $oosDBTable['configuration'] . " WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_YELLOWPAY_STATUS', 'MODULE_PAYMENT_YELLOWPAY_ID', 'MODULE_PAYMENT_YELLOWPAY_SHOP_ID', 'MODULE_PAYMENT_HASH_SEED', 'MODULE_PAYMENT_YELLOWPAY_CURRENCY', 'MODULE_PAYMENT_YELLOWPAY_ZONE', 'MODULE_PAYMENT_YELLOWPAY_LANGUAGE', 'MODULE_PAYMENT_YELLOWPAY_ORDER_STATUS_ID', 'MODULE_PAYMENT_YELLOWPAY_SORT_ORDER');
    }
  }
?>