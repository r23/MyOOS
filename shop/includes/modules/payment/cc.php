<?php
/* ----------------------------------------------------------------------
   $Id: cc.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: cc.php,v 1.53 2003/02/04 09:55:01 project3000 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class cc {
    var $code, $title, $description, $enabled = FALSE;

// class constructor
    function cc() {
      global $oOrder, $aLang;

      $this->code = 'cc';
      $this->title = $aLang['module_payment_cc_text_title'];
      $this->description = $aLang['module_payment_cc_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_CC_STATUS') && (MODULE_PAYMENT_CC_STATUS == 'True') ? TRUE : FALSE);
      $this->sort_order = (defined('MODULE_PAYMENT_CC_SORT_ORDER') ? MODULE_PAYMENT_CC_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_CC_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_CC_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();
    }

// class methods
    function update_status() {
      global $oOrder;

      if ($_SESSION['shipping']['id'] == 'selfpickup_selfpickup') {
        $this->enabled = FALSE;
      }

      if ( ($this->enabled == TRUE) && ((int)MODULE_PAYMENT_CC_ZONE > 0) ) {
        $check_flag = FALSE;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_CC_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
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
      global $aLang;

      $js = '  if (payment_value == "' . $this->code . '") {' . "\n" .
            '    var cc_owner = document.checkout_payment.cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . $aLang['module_payment_cc_text_js_cc_owner'] . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . $aLang['module_payment_cc_text_js_cc_number'] . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '  }' . "\n";

      return $js;
    }

    function selection() {
      global $oOrder, $aLang;

      for ($i=1; $i<13; $i++) {
        $expires_month[] = array('id' => sprintf('%02d', $i), 'text' => strftime('%B',mktime(0,0,0,$i,1,2000)));
      }

      $today = getdate(); 
      for ($i=$today['year']; $i < $today['year']+10; $i++) {
        $expires_year[] = array('id' => strftime('%y',mktime(0,0,0,1,1,$i)), 'text' => strftime('%Y',mktime(0,0,0,1,1,$i)));
      }

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => $aLang['module_payment_cc_text_credit_card_owner'],
                                                 'field' => oos_draw_input_field('cc_owner', $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname'])),
                                           array('title' => $aLang['module_payment_cc_text_credit_card_number'],
                                                 'field' => oos_draw_input_field('cc_number')),
                                           array('title' => $aLang['module_payment_cc_text_credit_card_expires'],
                                                 'field' => oos_draw_pull_down_menu('cc_expires_month', $expires_month) . '&nbsp;' . oos_draw_pull_down_menu('cc_expires_year', $expires_year))));

      return $selection;
    }

    function pre_confirmation_check() {
      global $aLang;

      include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_cc_validation.php';

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['cc_number'], $_POST['cc_expires_month'], $_POST['cc_expires_year']);

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

      if ( ($result == FALSE) || ($result < 1) ) {
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&cc_owner=' . urlencode($_POST['cc_owner']) . '&cc_expires_month=' . $_POST['cc_expires_month'] . '&cc_expires_year=' . $_POST['cc_expires_year'];
        $aContents = oos_get_content();
        
        oos_redirect(oos_href_link($aContents['checkout_payment'], $payment_error_return, 'SSL', true, false));
      }

      $this->cc_card_type = $cc_validation->cc_type;
      $this->cc_card_number = $cc_validation->cc_number;
    }

    function confirmation() {
      global $aLang;

      $confirmation = array('title' => $this->title . ': ' . $this->cc_card_type,
                            'fields' => array(array('title' => $aLang['module_payment_cc_text_credit_card_owner'],
                                                    'field' => $_POST['cc_owner']),
                                              array('title' => $aLang['module_payment_cc_text_credit_card_number'],
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => $aLang['module_payment_cc_text_credit_card_expires'],
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['cc_expires_month'], 1, '20' . $_POST['cc_expires_year'])))));

      return $confirmation;
    }

    function process_button() {

      $process_button_string = oos_draw_hidden_field('cc_owner', $_POST['cc_owner']) .
                               oos_draw_hidden_field('cc_expires', $_POST['cc_expires_month'] . $_POST['cc_expires_year']) .
                               oos_draw_hidden_field('cc_type', $this->cc_card_type) .
                               oos_draw_hidden_field('cc_number', $this->cc_card_number);

      return $process_button_string;
    }

    function before_process() {
      global $oOrder;

      if ( (defined('MODULE_PAYMENT_CC_EMAIL')) && (oos_validate_is_email(MODULE_PAYMENT_CC_EMAIL)) ) {
        $len = strlen($_POST['cc_number']);

        $this->cc_middle = substr($_POST['cc_number'], 4, ($len-8));
        $oOrder->info['cc_number'] = substr($_POST['cc_number'], 0, 4) . str_repeat('X', (strlen($_POST['cc_number']) - 8)) . substr($_POST['cc_number'], -4);
      }
    }

    function after_process() {
      global $insert_id;

      if ( (defined('MODULE_PAYMENT_CC_EMAIL')) && (oos_validate_is_email(MODULE_PAYMENT_CC_EMAIL)) ) {
        $message = 'Order #' . $insert_id . "\n\n" . 'Middle: ' . $this->cc_middle . "\n\n";

        oos_mail('', MODULE_PAYMENT_CC_EMAIL, 'Extra Order Info: #' . $insert_id, $message, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
      }
    }

    function get_error() {
      global $aLang;

      $error = array('title' => $aLang['module_payment_cc_text_error'],
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_CC_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_CC_STATUS', 'True', '6', '0', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_CC_EMAIL', '', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_CC_SORT_ORDER', '0', '6', '0' , now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_CC_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_CC_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_CC_STATUS', 'MODULE_PAYMENT_CC_EMAIL', 'MODULE_PAYMENT_CC_ZONE', 'MODULE_PAYMENT_CC_ORDER_STATUS_ID', 'MODULE_PAYMENT_CC_SORT_ORDER');
    }
  }
?>
