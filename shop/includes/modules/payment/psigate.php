<?php
/* ----------------------------------------------------------------------
   $Id: psigate.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: psigate.php,v 1.16 2003/01/29 19:57:15 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class psigate {
    var $code, $title, $description, $enabled = false;

// class constructor
    function psigate() {
      global $oOrder, $aLang;

      $this->code = 'psigate';
      $this->title = $aLang['module_payment_psigate_text_title'];
      $this->description = $aLang['module_payment_psigate_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_PSIGATE_STATUS') && (MODULE_PAYMENT_PSIGATE_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_PSIGATE_SORT_ORDER') ? MODULE_PAYMENT_PSIGATE_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();

      $this->form_action_url = 'https://order.psigate.com/psigate.asp';
    }

// class methods
    function update_status() {
      global $oOrder;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_PSIGATE_ZONE > 0) ) {
        $check_flag = false;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_PSIGATE_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
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
      global $aLang;

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'Local') {
        $js = 'if (payment_value == "' . $this->code . '") {' . "\n" .
              '  var psigate_cc_number = document.checkout_payment.psigate_cc_number.value;' . "\n" .
              '  if (psigate_cc_number == "" || psigate_cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
              '    error_message = error_message + "' . $aLang['module_payment_psigate_text_js_cc_number'] . '";' . "\n" .
              '    error = 1;' . "\n" .
              '  }' . "\n" .
              '}' . "\n";

        return $js;
      } else {
        return false;
      }
    }

    function selection() {
      global $oOrder, $aLang;

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'Local') {
        for ($i=1; $i<13; $i++) {
          $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
        }

        $today = getdate(); 
        for ($i=$today['year']; $i < $today['year']+10; $i++) {
          $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
        }

        $selection = array('id' => $this->code,
                           'module' => $this->title,
                           'fields' => array(array('title' => $aLang['module_payment_psigate_text_credit_card_owner'],
                                                   'field' => $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname']),
                                             array('title' => $aLang['module_payment_psigate_text_credit_card_number'],
                                                   'field' => oos_draw_input_field('psigate_cc_number')),
                                             array('title' => $aLang['module_payment_psigate_text_credit_card_expires'],
                                                   'field' => oos_draw_pull_down_menu('psigate_cc_expires_month', $expires_month) . '&nbsp;' . oos_draw_pull_down_menu('psigate_cc_expires_year', $expires_year))));
      } else {
        $selection = array('id' => $this->code,
                           'module' => $this->title);
      }

      return $selection;
    }

    function pre_confirmation_check() {
      global $aLang;

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'Local') {
        include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_cc_validation.php';

        $cc_validation = new cc_validation();
        $result = $cc_validation->validate($_POST['psigate_cc_number'], $_POST['psigate_cc_expires_month'], $_POST['psigate_cc_expires_year']);

        $error = '';
        switch ($result) {
          case -1:
            $error = sprintf($aLang['text_ccval_error_unknown_card'], substr($cc_validation->cc_number, 0, 4));
            break;

          case -2:
          case -3:
          case -4:
            $error = $aLang['text_ccval_error_invalid_date'];
            break;

          case false:
            $error = $aLang['text_ccval_error_invalid_number'];
            break;
        }

        if ( ($result == false) || ($result < 1) ) {
          $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&psigate_cc_owner=' . urlencode($_POST['psigate_cc_owner']) . '&psigate_cc_expires_month=' . $_POST['psigate_cc_expires_month'] . '&psigate_cc_expires_year=' . $_POST['psigate_cc_expires_year'];
          $aContents = oos_get_content();
          
          oos_redirect(oos_href_link($aContents['checkout_payment'], $payment_error_return, 'SSL', true, false));
        }

        $this->cc_card_type = $cc_validation->cc_type;
        $this->cc_card_number = $cc_validation->cc_number;
        $this->cc_expiry_month = $cc_validation->cc_expiry_month;
        $this->cc_expiry_year = $cc_validation->cc_expiry_year;
      } else {
        return false;
      }
    }

    function confirmation() {
      global $aLang, $oOrder;

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'Local') {
        $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                              'fields' => array(array('title' => $aLang['module_payment_psigate_text_credit_card_owner'],
                                                      'field' => $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname']),
                                                array('title' => $aLang['module_payment_psigate_text_credit_card_number'],
                                                      'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                                array('title' => $aLang['module_payment_psigate_text_credit_card_expires'],
                                                      'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['psigate_cc_expires_month'], 1, '20' . $_POST['psigate_cc_expires_year'])))));

        return $confirmation;
      } else {
        return false;
      }
    }

    function process_button() {
      global $oOrder, $oCurrencies;

      switch (MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE) {
        case 'Always Good':
          $transaction_mode = '1';
          break;

        case 'Always Duplicate':
          $transaction_mode = '2';
          break;

        case 'Always Decline':
          $transaction_mode = '3';
          break;

        case 'Production':
        default:
          $transaction_mode = '0';
          break;
      }

      switch (MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE) {
        case 'Sale':
          $transaction_type = '0';
          break;

        case 'PostAuth':
          $transaction_type = '2';
          break;

        case 'PreAuth':
        default:
          $transaction_type = '1';
          break;
      }

      $aContents = oos_get_content();
      
      $process_button_string = oos_draw_hidden_field('MerchantID', MODULE_PAYMENT_PSIGATE_MERCHANT_ID) .
                               oos_draw_hidden_field('FullTotal', number_format($oOrder->info['total'] * $oCurrencies->get_value(MODULE_PAYMENT_PSIGATE_CURRENCY), $oCurrencies->currencies[MODULE_PAYMENT_PSIGATE_CURRENCY]['decimal_places'])) .
                               oos_draw_hidden_field('ThanksURL', oos_href_link($aContents['checkout_process'], '', 'SSL', true)) . 
                               oos_draw_hidden_field('NoThanksURL', oos_href_link($aContents['checkout_payment'], 'payment_error=' . $this->code, 'NONSSL', true)) . 
                               oos_draw_hidden_field('Bname', $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname']) .
                               oos_draw_hidden_field('Baddr1', $oOrder->billing['street_address']) .
                               oos_draw_hidden_field('Bcity', $oOrder->billing['city']) .
                               oos_draw_hidden_field('Bstate', $oOrder->billing['state']) .
                               oos_draw_hidden_field('Bzip', $oOrder->billing['postcode']) .
                               oos_draw_hidden_field('Bcountry', $oOrder->billing['country']['iso_code_2']) .
                               oos_draw_hidden_field('Phone', $oOrder->customer['telephone']) .
                               oos_draw_hidden_field('Email', $oOrder->customer['email_address']) .
                               oos_draw_hidden_field('Sname', $oOrder->delivery['firstname'] . ' ' . $oOrder->delivery['lastname']) .
                               oos_draw_hidden_field('Saddr1', $oOrder->delivery['street_address']) .
                               oos_draw_hidden_field('Scity', $oOrder->delivery['city']) .
                               oos_draw_hidden_field('Sstate', $oOrder->delivery['state']) .
                               oos_draw_hidden_field('Szip', $oOrder->delivery['postcode']) .
                               oos_draw_hidden_field('Scountry', $oOrder->delivery['country']['iso_code_2']) .
                               oos_draw_hidden_field('ChargeType', $transaction_type) .
                               oos_draw_hidden_field('Result', $transaction_mode) .
                               oos_draw_hidden_field('IP', $_SERVER['REMOTE_ADDR']);

      if (MODULE_PAYMENT_PSIGATE_INPUT_MODE == 'Local') {
        $process_button_string .= oos_draw_hidden_field('CardNumber', $this->cc_card_number) .
                                  oos_draw_hidden_field('ExpMonth', $this->cc_expiry_month) .
                                  oos_draw_hidden_field('ExpYear', substr($this->cc_expiry_year, -2));
      }

      return $process_button_string;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $aLang;

      if (isset($_GET['ErrMsg']) && (strlen($_GET['ErrMsg']) > 0)) {
        $error = stripslashes(urldecode($_GET['ErrMsg']));
      } elseif (isset($_GET['error']) && (strlen($_GET['error']) > 0)) {
        $error = stripslashes(urldecode($_GET['error']));
      } else {
        $error = $aLang['module_payment_psigate_text_error_message'];
      }

      return array('title' => $aLang['module_payment_psigate_text_error'],
                   'error' => $error);
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_PSIGATE_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PSIGATE_STATUS', 'True', '6', '1', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_PSIGATE_MERCHANT_ID', 'teststorewithcard', '6', '2', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE', 'Production', '6', '3', 'oos_cfg_select_option(array(\'Production\', \'Always Good\', \'Always Duplicate\', \'Always Decline\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE', 'PreAuth', '6', '4', 'oos_cfg_select_option(array(\'Sale\', \'PreAuth\', \'PostAuth\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PSIGATE_INPUT_MODE', 'Local', '6', '5', 'oos_cfg_select_option(array(\'Local\', \'Remote\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_PSIGATE_CURRENCY', 'USD', '6', '6', 'oos_cfg_select_option(array(\'CAD\', \'USD\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_PSIGATE_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_PSIGATE_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PSIGATE_STATUS', 'MODULE_PAYMENT_PSIGATE_MERCHANT_ID', 'MODULE_PAYMENT_PSIGATE_TRANSACTION_MODE', 'MODULE_PAYMENT_PSIGATE_TRANSACTION_TYPE', 'MODULE_PAYMENT_PSIGATE_INPUT_MODE', 'MODULE_PAYMENT_PSIGATE_CURRENCY', 'MODULE_PAYMENT_PSIGATE_ZONE', 'MODULE_PAYMENT_PSIGATE_ORDER_STATUS_ID', 'MODULE_PAYMENT_PSIGATE_SORT_ORDER');
    }
  }
?>
