<?php
/* ----------------------------------------------------------------------
   $Id: authorizenet.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: authorizenet.php,v 1.47 2003/02/14 05:51:31 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
 
  class authorizenet {
    var $code, $title, $description, $enabled = false;

// class constructor
    function authorizenet() {
      global $oOrder, $aLang;

      $this->code = 'authorizenet';
      $this->title = $aLang['module_payment_authorizenet_text_title'];
      $this->description = $aLang['module_payment_authorizenet_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_AUTHORIZENET_STATUS') && (MODULE_PAYMENT_AUTHORIZENET_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER') ? MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID;
      }

      if (is_object($oOrder)) $this->update_status();

      $this->form_action_url = 'https://secure.authorize.net/gateway/transact.dll';
    }

// Authorize.net utility functions
// DISCLAIMER:
//     This code is distributed in the hope that it will be useful, but without any warranty; 
//     without even the implied warranty of merchantability or fitness for a particular purpose.

// Main Interfaces:
//
// function InsertFP ($loginid, $txnkey, $amount, $sequence) - Insert HTML form elements required for SIM
// function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp) - Returns Fingerprint.

// compute HMAC-MD5
// Uses PHP mhash extension. Pl sure to enable the extension
// function hmac ($key, $data) {
//   return (bin2hex (mhash(MHASH_MD5, $data, $key)));
//}

// Thanks is lance from http://www.php.net/manual/en/function.mhash.php
//lance_rushing at hot* spamfree *mail dot com
//27-Nov-2002 09:36 
// 
//Want to Create a md5 HMAC, but don't have hmash installed?
//
//Use this:

function hmac ($key, $data)
{
   // RFC 2104 HMAC implementation for php.
   // Creates an md5 HMAC.
   // Eliminates the need to install mhash to compute a HMAC
   // Hacked by Lance Rushing

   $b = 64; // byte length for md5
   if (strlen($key) > $b) {
       $key = pack("H*",md5($key));
   }
   $key  = str_pad($key, $b, chr(0x00));
   $ipad = str_pad('', $b, chr(0x36));
   $opad = str_pad('', $b, chr(0x5c));
   $k_ipad = $key ^ $ipad ;
   $k_opad = $key ^ $opad;

   return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
}
// end code from lance (resume authorize.net code)

// Calculate and return fingerprint
// Use when you need control on the HTML output
function CalculateFP ($loginid, $txnkey, $amount, $sequence, $tstamp, $currency = "") {
  return ($this->hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency));
}

// Inserts the hidden variables in the HTML FORM required for SIM
// Invokes hmac function to calculate fingerprint.

function InsertFP ($loginid, $txnkey, $amount, $sequence, $currency = "") {
  $tstamp = time ();
  $fingerprint = $this->hmac ($txnkey, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

  $str = oos_draw_hidden_field('x_fp_sequence', $sequence) .
         oos_draw_hidden_field('x_fp_timestamp', $tstamp) .
         oos_draw_hidden_field('x_fp_hash', $fingerprint);

  return $str;
}

// class methods
    function update_status() {
      global $oOrder;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_AUTHORIZENET_ZONE > 0) ) {
        $check_flag = false;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_AUTHORIZENET_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
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
            '    var cc_owner = document.checkout_payment.authorizenet_cc_owner.value;' . "\n" .
            '    var cc_number = document.checkout_payment.authorizenet_cc_number.value;' . "\n" .
            '    if (cc_owner == "" || cc_owner.length < ' . CC_OWNER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . $aLang['module_payment_authorizenet_text_js_cc_owner'] . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (cc_number == "" || cc_number.length < ' . CC_NUMBER_MIN_LENGTH . ') {' . "\n" .
            '      error_message = error_message + "' . $aLang['module_payment_authorizenet_text_js_cc_number'] . '";' . "\n" .
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
                         'fields' => array(array('title' => $aLang['module_payment_authorizenet_text_credit_card_owner'],
                                                 'field' => oos_draw_input_field('authorizenet_cc_owner', $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname'])),
                                           array('title' => $aLang['module_payment_authorizenet_text_credit_card_number'],
                                                 'field' => oos_draw_input_field('authorizenet_cc_number')),
                                           array('title' => $aLang['module_payment_authorizenet_text_credit_card_expires'],
                                                 'field' => oos_draw_pull_down_menu('authorizenet_cc_expires_month', $expires_month) . '&nbsp;' . oos_draw_pull_down_menu('authorizenet_cc_expires_year', $expires_year))));

      return $selection;
    }

    function pre_confirmation_check() {
      global $aLang;

      include 'includes/classes/class_cc_validation.php';

      $cc_validation = new cc_validation();
      $result = $cc_validation->validate($_POST['authorizenet_cc_number'], $_POST['authorizenet_cc_expires_month'], $_POST['authorizenet_cc_expires_year']);
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
        $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&authorizenet_cc_owner=' . urlencode($_POST['authorizenet_cc_owner']) . '&authorizenet_cc_expires_month=' . $_POST['authorizenet_cc_expires_month'] . '&authorizenet_cc_expires_year=' . $_POST['authorizenet_cc_expires_year'];
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
                            'fields' => array(array('title' => $aLang['module_payment_authorizenet_text_credit_card_owner'],
                                                    'field' => $_POST['authorizenet_cc_owner']),
                                              array('title' => $aLang['module_payment_authorizenet_text_credit_card_number'],
                                                    'field' => substr($this->cc_card_number, 0, 4) . str_repeat('X', (strlen($this->cc_card_number) - 8)) . substr($this->cc_card_number, -4)),
                                              array('title' => $aLang['module_payment_authorizenet_text_credit_card_expires'],
                                                    'field' => strftime('%B, %Y', mktime(0,0,0,$_POST['authorizenet_cc_expires_month'], 1, '20' . $_POST['authorizenet_cc_expires_year'])))));

      return $confirmation;
    }

    function process_button() {
      global $oOrder;

      $sequence = rand(1, 1000);
      $aFilename = oos_get_filename();
      $aModules = oos_get_modules();

      $process_button_string = oos_draw_hidden_field('x_Login', MODULE_PAYMENT_AUTHORIZENET_LOGIN) .
                               oos_draw_hidden_field('x_Card_Num', $this->cc_card_number) .
                               oos_draw_hidden_field('x_Exp_Date', $this->cc_expiry_month . substr($this->cc_expiry_year, -2)) .
                               oos_draw_hidden_field('x_Amount', number_format($oOrder->info['total'], 2)) .
                               oos_draw_hidden_field('x_Relay_URL', oos_href_link($aModules['checkout'], $aFilename['checkout_process'], '', 'SSL', false)) .
                               oos_draw_hidden_field('x_Method', ((MODULE_PAYMENT_AUTHORIZENET_METHOD == 'Credit Card') ? 'CC' : 'ECHECK')) .
                               oos_draw_hidden_field('x_Version', '3.0') .
                               oos_draw_hidden_field('x_Cust_ID', $_SESSION['customer_id']) .
                               oos_draw_hidden_field('x_Email_Customer', ((MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER == 'True') ? 'TRUE': 'FALSE')) .
                               oos_draw_hidden_field('x_first_name', $oOrder->customer['firstname']) .
                               oos_draw_hidden_field('x_last_name', $oOrder->customer['lastname']) .
                               oos_draw_hidden_field('x_address', $oOrder->customer['street_address']) .
                               oos_draw_hidden_field('x_city', $oOrder->customer['city']) .
                               oos_draw_hidden_field('x_state', $oOrder->customer['state']) .
                               oos_draw_hidden_field('x_zip', $oOrder->customer['postcode']) .
                               oos_draw_hidden_field('x_country', $oOrder->customer['country']['title']) .
                               oos_draw_hidden_field('x_phone', $oOrder->customer['telephone']) .
                               oos_draw_hidden_field('x_email', $oOrder->customer['email_address']) .
                               oos_draw_hidden_field('x_ship_to_first_name', $oOrder->delivery['firstname']) .
                               oos_draw_hidden_field('x_ship_to_last_name', $oOrder->delivery['lastname']) .
                               oos_draw_hidden_field('x_ship_to_address', $oOrder->delivery['street_address']) .
                               oos_draw_hidden_field('x_ship_to_city', $oOrder->delivery['city']) .
                               oos_draw_hidden_field('x_ship_to_state', $oOrder->delivery['state']) .
                               oos_draw_hidden_field('x_ship_to_zip', $oOrder->delivery['postcode']) .
                               oos_draw_hidden_field('x_ship_to_country', $oOrder->delivery['country']['title']) .
                               oos_draw_hidden_field('x_Customer_IP', $_SERVER['REMOTE_ADDR']) .
                               $this->InsertFP(MODULE_PAYMENT_AUTHORIZENET_LOGIN, MODULE_PAYMENT_AUTHORIZENET_TXNKEY, number_format($oOrder->info['total'], 2), $sequence);
      if (MODULE_PAYMENT_AUTHORIZENET_TESTMODE == 'Test') $process_button_string .= oos_draw_hidden_field('x_Test_Request', 'TRUE');

      $process_button_string .= oos_draw_hidden_field(oos_session_name(), oos_session_id());

      return $process_button_string;
    }

    function before_process() {
      global $aLang;

      $aFilename = oos_get_filename();
      $aModules = oos_get_modules();

      if ($_POST['x_response_code'] == '1') return;
      if ($_POST['x_response_code'] == '2') {
        oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_payment'], 'error_message=' . urlencode($aLang['module_payment_authorizenet_text_declined_message']), 'SSL', true, false));
      }
      // Code 3 is an error - but anything else is an error too (IMHO)
      oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_payment'], 'error_message=' . urlencode($aLang['module_payment_authorizenet_text_error_message']), 'SSL', true, false));
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $aLang;

      $error = array('title' => $aLang['module_payment_authorizenet_text_error'],
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_AUTHORIZENET_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_AUTHORIZENET_STATUS', 'True', '6', '0', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'testing', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_AUTHORIZENET_TXNKEY', 'Test', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'Test', '6', '0', 'oos_cfg_select_option(array(\'Test\', \'Production\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_AUTHORIZENET_METHOD', 'Credit Card', '6', '0', 'oos_cfg_select_option(array(\'Credit Card\', \'eCheck\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER', 'False', '6', '0', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_AUTHORIZENET_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_AUTHORIZENET_STATUS', 'MODULE_PAYMENT_AUTHORIZENET_LOGIN', 'MODULE_PAYMENT_AUTHORIZENET_TXNKEY', 'MODULE_PAYMENT_AUTHORIZENET_TESTMODE', 'MODULE_PAYMENT_AUTHORIZENET_METHOD', 'MODULE_PAYMENT_AUTHORIZENET_EMAIL_CUSTOMER', 'MODULE_PAYMENT_AUTHORIZENET_ZONE', 'MODULE_PAYMENT_AUTHORIZENET_ORDER_STATUS_ID', 'MODULE_PAYMENT_AUTHORIZENET_SORT_ORDER');
    }
  }
?>
