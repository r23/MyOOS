<?php
/* ----------------------------------------------------------------------
   $Id: pm2checkout.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: pm2checkout.php,v 1.19 2003/01/29 19:57:15 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class pm2checkout {
    var $code, $title, $description, $enabled = false;

// class constructor
    function pm2checkout() {
      global $oOrder, $aLang;

      $this->code = 'pm2checkout';
      $this->title = $aLang['module_payment_2checkout_text_title'];
      $this->description = $aLang['module_payment_2checkout_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_2CHECKOUT_STATUS') && (MODULE_PAYMENT_2CHECKOUT_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_2CHECKOUT_SORT_ORDER') ? MODULE_PAYMENT_2CHECKOUT_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();

      $this->form_action_url = 'https://www.2checkout.com/cgi-bin/Abuyers/purchase.2c';
    }

// class methods
    function update_status() {
      global $oOrder;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_2CHECKOUT_ZONE > 0) ) {
        $check_flag = false;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_2CHECKOUT_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
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
            '    var cc_number = document.checkout_payment.pm_2checkout_cc_number.value;' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . $aLang['module_payment_2checkout_text_js_cc_number'] . '";' . "\n" .
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
                         'fields' => array(array('title' => $aLang['module_payment_2checkout_text_credit_card_owner_first_name'],
                                                 'field' => oos_draw_input_field('pm_2checkout_cc_owner_firstname', $oOrder->billing['firstname'])),
                                           array('title' => $aLang['module_payment_2checkout_text_credit_card_owner_last_name'],
                                                 'field' => oos_draw_input_field('pm_2checkout_cc_owner_lastname', $oOrder->billing['lastname'])),
                                           array('title' => $aLang['module_payment_2checkout_text_credit_card_number'],
                                                 'field' => oos_draw_input_field('pm_2checkout_cc_number')),
                                           array('title' => $aLang['module_payment_2checkout_text_credit_card_expires'],
                                                 'field' => oos_draw_pull_down_menu('pm_2checkout_cc_expires_month', $expires_month) . '&nbsp;' . oos_draw_pull_down_menu('pm_2checkout_cc_expires_year', $expires_year)),
                                           array('title' => $aLang['module_payment_2checkout_text_credit_card_checknumber'],
                                                 'field' => oos_draw_input_field('pm_2checkout_cc_cvv', '', 'size="4" maxlength="3"') . '&nbsp;<small>' . $aLang['module_payment_2checkout_text_credit_card_checknumber_location'] . '</small>')));

      return $selection;
    }

    function pre_confirmation_check() {
      global $aLang;

      include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_cc_validation.php';

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['pm_2checkout_cc_number'], $_POST['pm_2checkout_cc_expires_month'], $_POST['pm_2checkout_cc_expires_year']);

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
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&pm_2checkout_cc_owner_firstname=' . urlencode($_POST['pm_2checkout_cc_owner_firstname']) . '&pm_2checkout_cc_owner_lastname=' . urlencode($_POST['pm_2checkout_cc_owner_lastname']) . '&pm_2checkout_cc_expires_month=' . $_POST['pm_2checkout_cc_expires_month'] . '&pm_2checkout_cc_expires_year=' . $_POST['pm_2checkout_cc_expires_year'];
        $aContents = oos_get_content();
        
        oos_redirect(oos_href_link($aContents['checkout_payment'], $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
      $this->cc_expiry_month = $cc_validation->cc_expiry_month;
      $this->cc_expiry_year = $cc_validation->cc_expiry_year;
    }

    function confirmation() {
      global $aLang;

      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => $aLang['module_payment_2checkout_text_credit_card_owner'],
                                                    'field' => $_POST['pm_2checkout_cc_owner_firstname'] . ' ' . $_POST['pm_2checkout_cc_owner_lastname']),
                                              array('title' => $aLang['module_payment_2checkout_text_credit_card_number'],
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => $aLang['module_payment_2checkout_text_credit_card_expires'],
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['pm_2checkout_cc_expires_month'], 1, '20' . $_POST['pm_2checkout_cc_expires_year'])))));

      return $confirmation;
    }

    function process_button() {
      global $oOrder;

      $aContents = oos_get_content();
      

      $process_button_string = oos_draw_hidden_field('x_login', MODULE_PAYMENT_2CHECKOUT_LOGIN) .
                               oos_draw_hidden_field('x_amount', number_format($oOrder->info['total'], 2)) .
                               oos_draw_hidden_field('x_invoice_num', date('YmdHis')) .
                               oos_draw_hidden_field('x_test_request', ((MODULE_PAYMENT_2CHECKOUT_TESTMODE == 'Test') ? 'Y' : 'N')) .
                               oos_draw_hidden_field('x_card_num', $this->cc_card_number) .
                               oos_draw_hidden_field('cvv', $_POST['pm_2checkout_cc_cvv']) .
                               oos_draw_hidden_field('x_exp_date', $this->cc_expiry_month . substr($this->cc_expiry_year, -2)) .
                               oos_draw_hidden_field('x_first_name', $_POST['pm_2checkout_cc_owner_firstname']) .
                               oos_draw_hidden_field('x_last_name', $_POST['pm_2checkout_cc_owner_lastname']) .
                               oos_draw_hidden_field('x_address', $oOrder->customer['street_address']) .
                               oos_draw_hidden_field('x_city', $oOrder->customer['city']) .
                               oos_draw_hidden_field('x_state', $oOrder->customer['state']) .
                               oos_draw_hidden_field('x_zip', $oOrder->customer['postcode']) .
                               oos_draw_hidden_field('x_country', $oOrder->customer['country']['title']) .
                               oos_draw_hidden_field('x_email', $oOrder->customer['email_address']) .
                               oos_draw_hidden_field('x_phone', $oOrder->customer['telephone']) .
                               oos_draw_hidden_field('x_ship_to_first_name', $oOrder->delivery['firstname']) .
                               oos_draw_hidden_field('x_ship_to_last_name', $oOrder->delivery['lastname']) .
                               oos_draw_hidden_field('x_ship_to_address', $oOrder->delivery['street_address']) .
                               oos_draw_hidden_field('x_ship_to_city', $oOrder->delivery['city']) .
                               oos_draw_hidden_field('x_ship_to_state', $oOrder->delivery['state']) .
                               oos_draw_hidden_field('x_ship_to_zip', $oOrder->delivery['postcode']) .
                               oos_draw_hidden_field('x_ship_to_country', $oOrder->delivery['country']['title']) .
                               oos_draw_hidden_field('x_receipt_link_url', oos_href_link($aContents['checkout_process'], '', 'SSL')) .
                               oos_draw_hidden_field('x_email_merchant', ((MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT == 'True') ? 'TRUE' : 'FALSE'));

      return $process_button_string;
    }

    function before_process() {
      global $aLang;

      if ($_POST['x_response_code'] != '1') {
        $aContents = oos_get_content();
        

        oos_redirect(oos_href_link($aContents['checkout_payment'], 'error_message=' . urlencode($aLang['module_payment_2checkout_text_error_message']), 'SSL', true, false));
      }
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $aLang;

      $error = array('title' => $aLang['module_payment_2checkout_text_error'],
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_2CHECKOUT_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_2CHECKOUT_STATUS', 'True', '6', '0', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_2CHECKOUT_LOGIN', '18157', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_2CHECKOUT_TESTMODE', 'Test', '6', '0', 'oos_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT', 'True', '6', '0', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_2CHECKOUT_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_2CHECKOUT_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_2CHECKOUT_STATUS', 'MODULE_PAYMENT_2CHECKOUT_LOGIN', 'MODULE_PAYMENT_2CHECKOUT_TESTMODE', 'MODULE_PAYMENT_2CHECKOUT_EMAIL_MERCHANT', 'MODULE_PAYMENT_2CHECKOUT_ZONE', 'MODULE_PAYMENT_2CHECKOUT_ORDER_STATUS_ID', 'MODULE_PAYMENT_2CHECKOUT_SORT_ORDER');
    }
  }
?>
