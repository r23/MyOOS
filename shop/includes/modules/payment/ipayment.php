<?php
/* ----------------------------------------------------------------------
   $Id: ipayment.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ipayment.php,v 1.32 2003/01/29 19:57:14 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class ipayment {
    var $code, $title, $description, $enabled = false;

// class constructor
    function ipayment() {
      global $oOrder, $aLang;

      $this->code = 'ipayment';
      $this->title = $aLang['module_payment_ipayment_text_title'];
      $this->description = $aLang['module_payment_ipayment_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_IPAYMENT_STATUS') && (MODULE_PAYMENT_IPAYMENT_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_IPAYMENT_SORT_ORDER') ? MODULE_PAYMENT_IPAYMENT_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_IPAYMENT_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_IPAYMENT_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();

      $this->form_action_url = 'https://ipayment.de/merchant/' . MODULE_PAYMENT_IPAYMENT_ID . '/processor.php';
    }

// class methods
    function update_status() {
      global $oOrder;

      if ($_SESSION['shipping']['id'] == 'selfpickup_selfpickup') {
        $this->enabled = false;
      }

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_IPAYMENT_ZONE > 0) ) {
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

    function javascript_validation() {
      global $aLang;

      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.ipayment_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.ipayment_cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . $aLang['module_payment_ipayment_text_js_cc_owner'] . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . $aLang['module_payment_ipayment_text_js_cc_number'] . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    function selection() {
      global $oOrder, $aLang;

      for ($i=1; $i < 13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate(); 
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => $aLang['module_payment_ipayment_text_credit_card_owner'],
                                                 'field' => oos_draw_input_field('ipayment_cc_owner', $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname'])),
                                           array('title' => $aLang['module_payment_ipayment_text_credit_card_number'],
                                                 'field' => oos_draw_input_field('ipayment_cc_number')),
                                           array('title' => $aLang['module_payment_ipayment_text_credit_card_expires'],
                                                 'field' => oos_draw_pull_down_menu('ipayment_cc_expires_month', $expires_month) . '&nbsp;' . oos_draw_pull_down_menu('ipayment_cc_expires_year', $expires_year)),
                                           array('title' => $aLang['module_payment_ipayment_text_credit_card_checknumber'],
                                                 'field' => oos_draw_input_field('ipayment_cc_checkcode', '', 'size="4" maxlength="4"') . '&nbsp;<small>' . $aLang['module_payment_ipayment_text_credit_card_checknumber_location'] . '</small>')));

      return $selection;
    }

    function pre_confirmation_check() {
      global $aLang;

      include 'includes/classes/class_cc_validation.php';

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['ipayment_cc_number'], $_POST['ipayment_cc_expires_month'], $_POST['ipayment_cc_expires_year']);

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
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&ipayment_cc_owner=' . urlencode($_POST['ipayment_cc_owner']) . '&ipayment_cc_expires_month=' . $_POST['ipayment_cc_expires_month'] . '&ipayment_cc_expires_year=' . $_POST['ipayment_cc_expires_year'] . '&ipayment_cc_checkcode=' . $_POST['ipayment_cc_checkcode'];
        $aFilename = oos_get_filename();
        $aModules = oos_get_modules();
        oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_payment'], $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }

    function confirmation() {
      global $aLang;

      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => $aLang['module_payment_ipayment_text_credit_card_owner'],
                                                    'field' => $_POST['ipayment_cc_owner']),
                                              array('title' => $aLang['module_payment_ipayment_text_credit_card_number'],
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => $aLang['module_payment_ipayment_text_credit_card_expires'],
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['ipayment_cc_expires_month'], 1, '20' . $_POST['ipayment_cc_expires_year'])))));

      if (oos_is_not_null($_POST['ipayment_cc_checkcode'])) {
        $confirmation['fields'][] = array('title' => $aLang['module_payment_ipayment_text_credit_card_checknumber'],
                                          'field' => $_POST['ipayment_cc_checkcode']);
      }

      return $confirmation;
    }

    function process_button() {
      global $oOrder, $oCurrencies;

      switch (MODULE_PAYMENT_IPAYMENT_CURRENCY) {
        case 'Always EUR':
          $trx_currency = 'EUR';
          break;
        case 'Always USD':
          $trx_currency = 'USD';
          break;
        case 'Either EUR or USD, else EUR':
          if ( ($_SESSION['currency'] == 'EUR') || ($_SESSION['currency'] == 'USD') ) {
            $trx_currency = $_SESSION['currency'];
          } else {
            $trx_currency = 'EUR';
          }
          break;
        case 'Either EUR or USD, else USD':
          if ( ($_SESSION['currency'] == 'EUR') || ($_SESSION['currency'] == 'USD') ) {
            $trx_currency = $_SESSION['currency'];
          } else {
            $trx_currency = 'USD';
          }
          break;
      }

      $aFilename = oos_get_filename();
      $aModules = oos_get_modules();
      $process_button_string = oos_draw_hidden_field('silent', '1') .
                               oos_draw_hidden_field('trx_paymenttyp', 'cc') .
                               oos_draw_hidden_field('trxuser_id', MODULE_PAYMENT_IPAYMENT_USER_ID) .
                               oos_draw_hidden_field('trxpassword', MODULE_PAYMENT_IPAYMENT_PASSWORD) .
                               oos_draw_hidden_field('item_name', STORE_NAME) .
                               oos_draw_hidden_field('trx_currency', $trx_currency) .
                               oos_draw_hidden_field('trx_amount', number_format($oOrder->info['total'] * 100 * $oCurrencies->get_value($trx_currency), 0, '','')) .
                               oos_draw_hidden_field('cc_expdate_month', $_POST['ipayment_cc_expires_month']) .
                               oos_draw_hidden_field('cc_expdate_year', $_POST['ipayment_cc_expires_year']) .
                               oos_draw_hidden_field('cc_number', $_POST['ipayment_cc_number']) .
                               oos_draw_hidden_field('cc_checkcode', $_POST['ipayment_cc_checkcode']) .
                               oos_draw_hidden_field('addr_name', $_POST['ipayment_cc_owner']) .
                               oos_draw_hidden_field('addr_email', $oOrder->customer['email_address']) .
                               oos_draw_hidden_field('redirect_url', oos_href_link($aModules['checkout'], $aFilename['checkout_process'], '', 'SSL', true)) .
                               oos_draw_hidden_field('silent_error_url', oos_href_link($aModules['checkout'], $aFilename['checkout_payment'], 'payment_error=' . $this->code . '&ipayment_cc_owner=' . urlencode($_POST['ipayment_cc_owner']), 'SSL', true));

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

      $error = array('title' => $aLang['ipayment_error_heading'],
                     'error' => ((isset($_GET['error'])) ? stripslashes(urldecode($_GET['error'])) : $aLang['ipayment_error_message']));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_IPAYMENT_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_IPAYMENT_STATUS', 'True', '6', '1', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_IPAYMENT_ID', '99999', '6', '2', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_IPAYMENT_USER_ID', '99999', '6', '3', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_IPAYMENT_PASSWORD', '0', '6', '4', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_IPAYMENT_CURRENCY', 'Either EUR or USD, else EUR', '6', '5', 'oos_cfg_select_option(array(\'Always EUR\', \'Always USD\', \'Either EUR or USD, else EUR\', \'Either EUR or USD, else USD\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_IPAYMENT_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_IPAYMENT_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_IPAYMENT_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_IPAYMENT_STATUS', 'MODULE_PAYMENT_IPAYMENT_ID', 'MODULE_PAYMENT_IPAYMENT_USER_ID', 'MODULE_PAYMENT_IPAYMENT_PASSWORD', 'MODULE_PAYMENT_IPAYMENT_CURRENCY', 'MODULE_PAYMENT_IPAYMENT_ZONE', 'MODULE_PAYMENT_IPAYMENT_ORDER_STATUS_ID', 'MODULE_PAYMENT_IPAYMENT_SORT_ORDER');
    }
  }
?>
