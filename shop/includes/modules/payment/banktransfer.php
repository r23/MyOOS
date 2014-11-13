<?php
/* ----------------------------------------------------------------------
   $Id: banktransfer.php,v 1.2 2007/10/24 23:38:34 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banktransfer.php,v 1.15 2003/02/18 18:33:15 dogu
   ----------------------------------------------------------------------
   OSC German Banktransfer
   (http://www.oscommerce.com/community/contributions,826)

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class banktransfer {
    var $code, $title, $description, $enabled;

// class constructor
    function banktransfer() {
      global $oOrder, $aLang;

      $this->code = 'banktransfer';
      $this->title = $aLang['module_payment_banktransfer_text_title'];
      $this->description = $aLang['module_payment_banktransfer_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_BANKTRANSFER_STATUS') && (MODULE_PAYMENT_BANKTRANSFER_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER') ? MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID;
      }
      if (is_object($oOrder)) $this->update_status();

      if (isset($_POST['banktransfer_fax']) && $_POST['banktransfer_fax'] == 'on') {
        $this->email_footer = $aLang['module_payment_banktransfer_text_email_footer'];
      }
    }

// class methods
    function update_status() {
      global $oOrder, $oCurrencies;

      if ($_SESSION['shipping']['id'] == 'selfpickup_selfpickup') {
        $this->enabled = false;
      }

      $my_currency = $_SESSION['currency'];
      if (!in_array($my_currency, array('CHF', 'EUR', 'USD'))) {
        $my_currency = 'EUR';
      }

      $nAmount = number_format(($oOrder->info['total'] - $oOrder->info['shipping_cost']) * $oCurrencies->get_value($my_currency), $oCurrencies->get_decimal_places($my_currency));

      if ($nAmount > (int)MODULE_PAYMENT_BANKTRANSFER_MAX_ORDER) {
        $this->enabled = false;
      }

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_BANKTRANSFER_ZONE > 0) ) {
        $check_flag = false;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_BANKTRANSFER_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
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
    // disable the module if the order only contains virtual products
      if ($this->enabled == true) {
        if ($oOrder->content_type == 'virtual') {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      global $aLang;

      $js = 'if (payment_value == "' . $this->code . '") {' . "\n" .
            '  var banktransfer_blz = document.checkout_payment.banktransfer_blz.value;' . "\n" .
            '  var banktransfer_number = document.checkout_payment.banktransfer_number.value;' . "\n" .
            '  var banktransfer_owner = document.checkout_payment.banktransfer_owner.value;' . "\n";

      if (MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION == 'true'){
        $js .= '  var banktransfer_fax = document.checkout_payment.banktransfer_fax.checked;' . "\n" .
               '  if (banktransfer_fax == false) {' . "\n";
      }

      $js .= '    if (banktransfer_owner == "") {' . "\n" .
             '       error_message = error_message + "' . $aLang['js_bank_owner'] . '";' . "\n" .
             '       error = 1;' . "\n" .
             '     }' . "\n" .
             '     if (banktransfer_blz == "") {' . "\n" .
             '       error_message = error_message + "' . $aLang['js_bank_blz'] . '";' . "\n" .
             '       error = 1;' . "\n" .
             '     }' . "\n" .
             '     if (banktransfer_number == "") {' . "\n" .
             '       error_message = error_message + "' . $aLang['js_bank_number'] . '";' . "\n" .
             '       error = 1;' . "\n" .
             '    }' . "\n";

      if (MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION == 'true'){
        $js .= '  }' . "\n" ;
      }

      $js .= '}' . "\n";

      return $js;
    }

    function selection() {
      global $oOrder, $aLang;

      $selection = array('id' => $this->code,
                         'module' => $this->title,
      	                 'fields' => array(array('title' => $aLang['module_payment_banktransfer_text_note'],
      	                                         'field' => $aLang['module_payment_banktransfer_text_bank_info']),
      	                                   array('title' => $aLang['module_payment_banktransfer_text_bank_owner'],
      	                                         'field' => oos_draw_input_field('banktransfer_owner', $oOrder->billing['firstname'] . ' ' . $oOrder->billing['lastname'])),
      	                                   array('title' => $aLang['module_payment_banktransfer_text_bank_blz'],
      	                                         'field' => oos_draw_input_field('banktransfer_blz', '', 'size="8" maxlength="8"')),
      	                                   array('title' => $aLang['module_payment_banktransfer_text_bank_number'],
      	                                         'field' => oos_draw_input_field('banktransfer_number', '', 'size="16" maxlength="32"')),
      	                                   array('title' => $aLang['module_payment_banktransfer_text_bank_name'],
      	                                         'field' => oos_draw_input_field('banktransfer_bankname')),
      	                                   array('title' => '',
      	                                         'field' => oos_draw_hidden_field('recheckok', $_POST['recheckok']))
      	                                   ));

      if (MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION =='true'){
        $selection['fields'][] = array('title' => $aLang['module_payment_banktransfer_text_note'],
      	                               'field' => $aLang['module_payment_banktransfer_text_note2'] . '<a href="' . MODULE_PAYMENT_BANKTRANSFER_URL_NOTE . '" target="_blank"><b>' . $aLang['module_payment_banktransfer_text_note3'] . '</b></a>' . $aLang['module_payment_banktransfer_text_note4']);
      	$selection['fields'][] = array('title' => $aLang['module_payment_banktransfer_text_bank_fax'],
      	                               'field' => oos_draw_checkbox_field('banktransfer_fax', 'on'));

      }

      return $selection;
    }

    function pre_confirmation_check(){
      global $banktransfer_number, $banktransfer_blz, $aLang;

      if ($_POST['banktransfer_fax'] == false) {
        include 'includes/classes/class_banktransfer_validation.php';

        $banktransfer_validation = new AccountCheck;
        $banktransfer_result = $banktransfer_validation->CheckAccount($banktransfer_number, $banktransfer_blz);

        if ($banktransfer_result > 0 ||  $_POST['banktransfer_owner'] == '') {
          if ($_POST['banktransfer_owner'] == '') {
            $error = 'Name des Kontoinhabers fehlt!';
            $recheckok = '';
          } else {
            switch ($banktransfer_result) {
              case 1: // number & blz not ok
                $error = $aLang['module_payment_banktransfer_text_bank_error_1'];
                $recheckok = 'true';
                break;

              case 5: // BLZ not found
                $error = $aLang['module_payment_banktransfer_text_bank_error_5'];
                $recheckok = 'true';
                break;

              case 8: // no blz entered
                $error = $aLang['module_payment_banktransfer_text_bank_error_8'];
                $recheckok = '';
                break;

              case 9: // no number entered
                $error = $aLang['module_payment_banktransfer_text_bank_error_9'];
                $recheckok = '';
                break;

              default:
                $error = $aLang['module_payment_banktransfer_text_bank_error_4'];
                $recheckok = 'true';
                break;
            }
          }

          if ($_POST['recheckok'] != 'true') {
            $payment_error_return = 'payment_error=' . $this->code . '&error=' . urlencode($error) . '&banktransfer_owner=' . urlencode($_POST['banktransfer_owner']) . '&banktransfer_number=' . urlencode($_POST['banktransfer_number']) . '&banktransfer_blz=' . urlencode($_POST['banktransfer_blz']) . '&banktransfer_bankname=' . urlencode($_POST['banktransfer_bankname']) . '&recheckok=' . $recheckok;
            $aFilename = oos_get_filename();
            $aModules = oos_get_modules();
            oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_payment'], $payment_error_return, 'SSL', true, false));
          }
        }
        $this->banktransfer_owner = oos_prepare_input($_POST['banktransfer_owner']);
        $this->banktransfer_blz = oos_prepare_input($_POST['banktransfer_blz']);
        $this->banktransfer_number = oos_prepare_input($_POST['banktransfer_number']);
        $this->banktransfer_prz = $banktransfer_validation->PRZ;
        $this->banktransfer_status = $banktransfer_result;
        if ($banktransfer_validation->Bankname != '')
          $this->banktransfer_bankname = $banktransfer_validation->Bankname;
        else
          $this->banktransfer_bankname = oos_prepare_input($_POST['banktransfer_bankname']);
      }
    }

    function confirmation() {
      global $aLang, $banktransfer_val, $banktransfer_owner, $banktransfer_bankname, $banktransfer_blz, $banktransfer_number, $checkout_form_action, $checkout_form_submit;

      if (!$_POST['banktransfer_owner'] == '') {
        $confirmation = array('title' => $this->title,
                              'fields' => array(array('title' => $aLang['module_payment_banktransfer_text_bank_owner'],
                                                      'field' => $this->banktransfer_owner),
                                                array('title' => $aLang['module_payment_banktransfer_text_bank_blz'],
                                                      'field' => $this->banktransfer_blz),
                                                array('title' => $aLang['module_payment_banktransfer_text_bank_number'],
                                                      'field' => $this->banktransfer_number),
                                                array('title' => $aLang['module_payment_banktransfer_text_bank_name'],
                                                      'field' => $this->banktransfer_bankname)
                                                ));
      }
      if ($_POST['banktransfer_fax'] == "on") {
        $confirmation = array('fields' => array(array('title' => $aLang['module_payment_banktransfer_text_bank_fax'])));
        $this->banktransfer_fax = "on";
      }
      return $confirmation;
    }

    function process_button() {

      $process_button_string = oos_draw_hidden_field('banktransfer_blz', $this->banktransfer_blz) .
                               oos_draw_hidden_field('banktransfer_bankname', $this->banktransfer_bankname).
                               oos_draw_hidden_field('banktransfer_number', $this->banktransfer_number) .
                               oos_draw_hidden_field('banktransfer_owner', $this->banktransfer_owner) .
                               oos_draw_hidden_field('banktransfer_status', $this->banktransfer_status) .
                               oos_draw_hidden_field('banktransfer_prz', $this->banktransfer_prz) .
                               oos_draw_hidden_field('banktransfer_fax', $this->banktransfer_fax);

      return $process_button_string;

    }

    function before_process() {
      return false;
    }

    function after_process() {
      global $insert_id, $banktransfer_val, $banktransfer_owner, $banktransfer_bankname, $banktransfer_blz, $banktransfer_number, $banktransfer_status, $banktransfer_prz, $banktransfer_fax, $checkout_form_action, $checkout_form_submit;

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $dbconn->Execute("INSERT INTO " . $oostable['banktransfer'] . " 
                  (orders_id, 
                   banktransfer_blz, 
                   banktransfer_bankname, 
                   banktransfer_number, 
                   banktransfer_owner, 
                   banktransfer_status, 
                   banktransfer_prz) VALUES ('" . (int)$insert_id . "', 
                                             '" . oos_db_input($banktransfer_blz) . "', 
                                             '" . oos_db_input($banktransfer_bankname) . "', 
                                             '" . oos_db_input($banktransfer_number) . "', 
                                             '" . oos_db_input($banktransfer_owner) ."', 
                                             '" . oos_db_input($banktransfer_status) ."', 
                                             '" . oos_db_input($banktransfer_prz) ."')");
      if ($_POST['banktransfer_fax'])
        $dbconn->Execute("UPDATE " . $oostable['banktransfer'] . " 
                      SET banktransfer_fax = '" . oos_db_input($banktransfer_fax) ."' 
                      WHERE orders_id = '" . (int)$insert_id . "'");
    }

    function get_error() {
      global $aLang;

      $error = array('title' => $aLang['module_payment_banktransfer_text_bank_error'],
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_BANKTRANSFER_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_BANKTRANSFER_STATUS', 'True', '6', '1', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_BANKTRANSFER_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_BANKTRANSFER_MAX_ORDER', '', '6', '5', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION', 'false', '6', '2', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_BANKTRANSFER_URL_NOTE', 'fax.html',  '6', '0', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_BANKTRANSFER_STATUS', 'MODULE_PAYMENT_BANKTRANSFER_ZONE', 'MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID', 'MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER', 'MODULE_PAYMENT_BANKTRANSFER_MAX_ORDER', 'MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION', 'MODULE_PAYMENT_BANKTRANSFER_URL_NOTE');
    }
  }

