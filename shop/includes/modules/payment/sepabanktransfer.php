<?php
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   CLASS SEPABANKTRANSFER

 /*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2007 osCommerce
  Released under the GNU General Public License
  /includes/modules/payment/sepabanktransfer.php
  
  Sepabanktransfer(Lastschrft)

  Erstellt    19.10.2010    Version 0.9
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

  // Zentrales Datenarray definieren.
  $aEbtVarSet = array();

  // class constructor.
  class sepabanktransfer {
    var $code, $title, $description, $sort_order, $enabled;

    // class methods.
    function sepabanktransfer() {
      global $aEbtVarSet, $order;
      // Zentrales Datenarray definieren und initialisieren.
      if (!isset($_SESSION['aEbtVarSet'])) {
        $aEbtVarSet = array();
        $aEbtVarSet['ebt_owner'] = '' ;
        $aEbtVarSet['ebt_iban'] = '' ;
        $aEbtVarSet['ebt_swift'] = '' ;
        $aEbtVarSet['ebt_swift_id'] = '';
        $aEbtVarSet['ebt_name'] = '' ;
        $aEbtVarSet['ebt_checked'] = false;
        $aGbtVarSet['recheckok']  = 'false';
        $aEbtVarSet["bt_order_total"] = '' ;
        tep_session_register('aEbtVarSet');
      }
      // Feste Werte besetzen.
      $this->code           = 'sepabanktransfer';
      $this->title          = MODULE_PAYMENT_SEPABT_TEXT_TITLE;
      $this->description    = MODULE_PAYMENT_SEPABT_TEXT_DESCRIPTION;
      $this->sort_order     = MODULE_PAYMENT_SEPABT_SORT_ORDER;
      $this->enabled        = ((MODULE_PAYMENT_SEPABT_STATUS == 'True') ? true : false); 
      
      // Statuse der Bestellung nach Zahlung festlegen.
      if ((int)MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID;
      }

      // Wenn Bestellung vorhanden, dann Status updaten.
      if (is_object($order)) $this->update_status();

    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION UPDATE_STATUS()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function update_status() {
      global $aEbtVarSet, $order, $customer_id;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_SEPABT_ZONE > 0) ) {
        $check_flag = false;
       $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_SEPABT_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
           $check_flag = true;
        }
        if ($check_flag == false) { $this->enabled = false; }
      }

      // check enable banktransfer after x times
      $test_query = tep_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id='" . $customer_id . "' AND orders_status = " . MODULE_PAYMENT_SEPABT_ENABLE_AFTER_ORDER_STATUS );
      $result = tep_db_fetch_array($test_query);

      $total = $result['total'];
      if ( $total + 1 < MODULE_PAYMENT_SEPABT_ENABLE_AFTER_TIMES) {
        $this->enabled = false;
      }


       // Modul abhaengig vom Bestellwert freigeben.
      if ($this->enabled == true) {
        // Bestellwert kleiner als Vorgabe ?
        if (intval(MODULE_PAYMENT_SEPABT_MIN_ORDERVALUE) > 0) {
          if ($order->info['subtotal'] < intval(MODULE_PAYMENT_SEPABT_MIN_ORDERVALUE)) {
            $this->enabled = false;
          }
        }
        // Bestellwert groesser als Vorgabe ?
        if (intval(MODULE_PAYMENT_SEPABT_MAX_ORDERVALUE) > 0) {
          if ($order->info['subtotal'] > intval(MODULE_PAYMENT_SEPABT_MAX_ORDERVALUE)) {
            $this->enabled = false;
          }
        }
      }
    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION javascript_validation()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

    function javascript_validation() {
      $js = 'if (payment_value == "' . $this->code . '") {' . "\n" .
            '  var sepabanktransfer_iban = document.checkout_payment.sepabanktransfer_iban.value;' . "\n" .
            '  var sepabanktransfer_swift = document.checkout_payment.sepabanktransfer_swift.value;' . "\n" .
            '  var sepabanktransfer_swift_id = document.checkout_payment.sepabanktransfer_swift_id.value;' . "\n" .
            '  var sepabanktransfer_owner = document.checkout_payment.sepabanktransfer_owner.value;' . "\n" ;

      $js .='    if (sepabanktransfer_owner == "") {' . "\n" .
            '      error_message = error_message + "' . JS_SEPABT_OWNER . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (sepabanktransfer_iban == "") {' . "\n" .
            '      error_message = error_message + "' . JS_SEPABT_IBAN . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (sepabanktransfer_swift == "") {' . "\n" .
            '      error_message = error_message + "' . JS_SEPABT_SWIFT . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" .
            '    if (sepabanktransfer_swift_id == 0) {' . "\n" .
            '      error_message = error_message + "' . JS_SEPABT_SWIFT_ID . '";' . "\n" .
            '      error = 1;' . "\n" .
            '    }' . "\n" ;
      $js .='}' . "\n";
      return $js;
    }
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION SELECTION()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function selection() {
      global $aEbtVarSet, $order, $customer_id;

      $aEbtVarSet   = $_SESSION['aEbtVarSet'];
      $customer_id  = $_SESSION['customer_id'];
      $bankdata_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id='" . $customer_id . "'");
      $bankdata = tep_db_fetch_array($bankdata_query);
      
       
       // Wenn die Bankverbindung noch nicht geprueft wurde, dann Kontodaten aus customers lesen.
      if ($aEbtVarSet["ebt_checked"] != true) {
        if (($bankdata['customers_sepabanktransfer_owner'] != '') and ($bankdata['customers_sepabanktransfer_iban'] != '') and ($bankdata['customers_sepabanktransfer_swift'] != '' ) and ($bankdata['customers_sepabanktransfer_swift_country_id'] != '0' )) {
          if ($bankdata['customers_sepabanktransfer_name'] == '') {
            $bankdata['customers_sepabanktransfer_name'] = MODULE_PAYMENT_BANKTRANSFER_ENTRY_BANK_NAME;
          }
          $selection = array('id' => $this->code,
                          'module' => $this->title,
                          'fields' => array(array('title' => '<img src="images/spacer.gif" border="0" width="28" height="5">',
                          'field' => MODULE_PAYMENT_SEPABT_TEXT_NOTE . '&nbsp;' . MODULE_PAYMENT_SEPABT_TEXT_BANK_INFO),
                                         array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_OWNER,
                                               'field' => tep_draw_input_field('sepabanktransfer_owner', $bankdata['customers_sepabanktransfer_owner'], 'size="36"')),
                                         array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_COUNTRY,
                                               'field' => tep_get_country_swift_list('sepabanktransfer_swift_id', $bankdata['customers_sepabanktransfer_swift_country_id'])),
                                         array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_SWIFT,
                                               'field' => tep_draw_input_field('sepabanktransfer_swift', $bankdata['customers_sepabanktransfer_swift'], 'size="16" maxlength="16"')),
                                         array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_IBAN,
                                               'field' => tep_draw_input_field('sepabanktransfer_iban', $bankdata['customers_sepabanktransfer_iban'], 'size="36" maxlength="36"')),
                                         array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_NAME,
                                              // 'field' => tep_draw_input_field('sepabanktransfer_name',$bankdata['customers_sepabanktransfer_name'], 'size="56"')),
                                               'field' => $bankdata['customers_sepabanktransfer_name'] . tep_draw_hidden_field('customers_sepabanktransfer_name'))
                                        ));
        // Nichts in customers gespeichert
        } else {
          $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_SEPABT_TEXT_NOTE,
                                                 'field' => MODULE_PAYMENT_SEPABT_TEXT_BANK_INFO),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_OWNER,
                                                 'field' => tep_draw_input_field('sepabanktransfer_owner', $order->billing['firstname'] . ' ' . $order->billing['lastname'])),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_COUNTRY,
                                                 'field' => tep_get_country_swift_list('sepabanktransfer_swift_id', $aEbtVarSet["ebt_swift_id"])),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_SWIFT,
                                                 'field' => tep_draw_input_field('sepabanktransfer_swift', $aEbtVarSet["ebt_swift"], 'size="36" maxlength="36"')),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_IBAN,
                                                 'field' => tep_draw_input_field('sepabanktransfer_iban', $aEbtVarSet["ebt_iban"], 'size="36" maxlength="36"')),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_NAME,
                                                 //'field' => tep_draw_input_field('sepabanktransfer_name',$aEbtVarSet["ebt_name"]))
                                                 'field' => $aEbtVarSet["bt_bankname"] . tep_draw_hidden_field('customers_sepabanktransfer_name'))
                               
                                                 
                                           ));
        }
      // Nicht aus customers lesen: Die Bankverbindung wurde bereits ueberprueft und der Kunde kommt zurueck...
      } else {
        $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array(array('title' => MODULE_PAYMENT_SEPABT_TEXT_NOTE . "xxxx",
                                                 'field' => MODULE_PAYMENT_SEPABT_TEXT_BANK_INFO),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_OWNER,
                                                 'field' => tep_draw_input_field('sepabanktransfer_owner', $aEbtVarSet["ebt_owner"])),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_COUNTRY,
                                                 'field' => tep_get_country_swift_list('sepabanktransfer_swift_id', $aEbtVarSet["ebt_swift_id"])),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_SWIFT,
                                                 'field' => tep_draw_input_field('sepabanktransfer_swift', $aEbtVarSet["ebt_swift"], 'size="36" maxlength="36"')),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_IBAN,
                                                 'field' => tep_draw_input_field('sepabanktransfer_iban', $aEbtVarSet["ebt_iban"], 'size="36" maxlength="36"')),
                                           array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_NAME,
                                                 //'field' => tep_draw_input_field('sepabanktransfer_name',$aEbtVarSet["ebt_name"]))
                                                 'field' => $aEbtVarSet["bt_bankname"] . tep_draw_hidden_field('customers_sepabanktransfer_name'))
                                                 
                                           ));
       }
    return $selection;
    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION PRE_CONFIRMATION_CHECK()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function pre_confirmation_check(){
      global $aEbtVarSet, $order, $customer_id, $currencies;

      // Schutz vor leeren und geaenderten Variablen.

      if (($aEbtVarSet["ebt_iban"] == '')        || ($aEbtVarSet["ebt_iban"]    != $_POST['sepabanktransfer_iban']))      $aEbtVarSet["ebt_iban"]      = $_POST['sepabanktransfer_iban'];
      if (($aEbtVarSet["ebt_swift"] == '')       || ($aEbtVarSet["ebt_swift"]   != $_POST['sepabanktransfer_swift']))     $aEbtVarSet["ebt_swift"]     = $_POST['sepabanktransfer_swift'];
      if (($aEbtVarSet["ebt_swift_id"] == '')    || ($aEbtVarSet["ebt_swift_id"]!= $_POST['sepabanktransfer_swift_id']))  $aEbtVarSet["ebt_swift_id"]  = $_POST['sepabanktransfer_swift_id'];
      if (($aEbtVarSet["ebt_owner"] == '')       || ($aEbtVarSet["ebt_owner"]   != $_POST['sepabanktransfer_owner']))     $aEbtVarSet["ebt_owner"]     = $_POST['sepabanktransfer_owner'];
      if (($aEbtVarSet["ebt_name"] == '')        || ($aEbtVarSet["ebt_name"]    != $_POST['sepabanktransfer_name']))      $aEbtVarSet["ebt_name"]      = $_POST['sepabanktransfer_name'];

      // Wieder in die Session speichern.
      $_SESSION['aEbtVarSet'] = $aEbtVarSet;

        $shipping_flat_status = (defined('MODULE_SHIPPING_FLAT_STATUS') && MODULE_SHIPPING_FLAT_STATUS == 'True');
        if ($shipping_flat_status) {
          if (DISPLAY_PRICE_WITH_TAX == 'true') {
            $shipping_flat_cost = tep_add_tax(MODULE_SHIPPING_FLAT_COST, tep_get_tax_rate(MODULE_SHIPPING_FLAT_TAX_CLASS, STORE_COUNTRY, MODULE_SHIPPING_FLAT_ZONE));
          } else {
            $shipping_flat_cost = MODULE_SHIPPING_FLAT_COST;
          }
          $aEbtVarSet["bt_order_total"] = $order->info['subtotal'] + $shipping_flat_cost;
        } else {
          $aEbtVarSet["bt_order_total"] = $order->info['total'];
        }

        // Lastschriftbetrag formatgerecht berechnen.
        $aEbtVarSet["bt_currency"]  = 'EUR'; // Lastschrift nur mit Euro erlaubt.
        $aEbtVarSet["trx_amount"]   = trim(number_format($aEbtVarSet["bt_order_total"] * 100 * $currencies->get_value($aEbtVarSet["bt_currency"]), 0, '',''));

         // Klasse laden
        include(DIR_WS_CLASSES . 'sepabanktransfer_validation.php');

        // Bankverbindung validieren
        $sepabanktransfer_validation            = new SepaAccountCheck;
        $aEbtVarSet["ebt_result"]                = $sepabanktransfer_validation->CheckAccount($aEbtVarSet["ebt_owner"], $aEbtVarSet["ebt_iban"], $aEbtVarSet["ebt_swift"], $aEbtVarSet["ebt_swift_id"]);
        $aEbtVarSet["ebt_name"]                  = $sepabanktransfer_validation->aBTValidationResultSet['V_BANK_NAME'];
        $aGbtVarSet["ebt_checked"]               = true;

        // Ergebnistext zuweisen.
        switch  ($aEbtVarSet["ebt_result"]) {
            case 0: // payment o.k.
            $error = 'O.K.';
            $recheckok = 'false';
            break;
          case 1: // 
            $error = SEPABT_TEXT_BANK_ERROR_1; $recheckok = 'true';  break;
          case 4: // 
            $error = SEPABT_TEXT_BANK_ERROR_4; $recheckok = 'true';  break;
          case 5: // 
            $error = SEPABT_TEXT_BANK_ERROR_5; $recheckok = 'true';  break;
          case 7: // 
            $error = SEPABT_TEXT_BANK_ERROR_7; $recheckok = 'true';  break;
          case 8: // 
            $error = SEPABT_TEXT_BANK_ERROR_8; $recheckok = 'true';  break;
          case 9: // 
            $error = SEPABT_TEXT_BANK_ERROR_9 . iban_country_get_iban_example($sepabanktransfer_validation->aBTValidationResultSet['V_BANK_COUNTRY_CODE']) . ' Länge: ' . (iban_country_get_bban_length($sepabanktransfer_validation->aBTValidationResultSet['V_BANK_COUNTRY_CODE']) + 4);
            $recheckok = 'true';
            break;
          case 10: // 
            $error = SEPABT_TEXT_BANK_ERROR_10 . ' Länge: ' . (iban_country_get_bban_length($sepabanktransfer_validation->aBTValidationResultSet['V_BANK_COUNTRY_CODE']) + 4); $recheckok = 'true'; break;
          default:
            $error = 'allgemeiner Fehler'; $recheckok = 'true'; break;
          }
        /* Log schreiben */
        $LogContent =   "\n\tKontoinhaber..........: " . $aEbtVarSet["ebt_owner"] .
                        "\n\tIBAN...........: "        . $aEbtVarSet["ebt_iban"] .
                        "\n\tSWIFT..........: "        . $aEbtVarSet["ebt_swift"] .
                        "\n\tBankname..............: " . $aEbtVarSet["ebt_name"].
                        "\n\tResult..............: "   . $aEbtVarSet["ebt_result"];

        $this->writelog( "LOG VALIDATION", $LogContent );
        
        // Mit dem Fehlercode zurück zur Eingabeseite 
        if ($aEbtVarSet["ebt_result"] > 0) {
          if ( $_POST['recheckok'] != "true") {
            // recheckok setzen, falls notwendig
            $aEbtVarSet['recheckok'] = $recheckok;
            $payment_error_return = 'payment_error=' . $this->code . '&recheckok=' . $recheckok . '&error=' . urlencode($error) ;
            //print_r ($payment_error_return);
            $_SESSION['aEbtVarSet'] = $aEbtVarSet;
            tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $payment_error_return, 'SSL', true, false));
          }
        }
    }
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION CONFIRMATION()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function confirmation() {
      global $aEbtVarSet, $checkout_form_action, $checkout_form_submit;

        $confirmation = array('title' => $this->title,
                       'fields' => array(array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_OWNER,
                                               'field' => $aEbtVarSet["ebt_owner"]),
                                         array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_IBAN ,
                                               'field' => $aEbtVarSet["ebt_iban"]),
                                         array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_SWIFT,
                                               'field' => $aEbtVarSet["ebt_swift"]),
                                         array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_NAME,
                                               'field' => $aEbtVarSet["ebt_name"])
                                               ));
      return $confirmation;
    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION PROCESS_BUTTON()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

    function process_button() {
      global $aEbtVarSet;
      return false;
    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION BEFORE_PROCESS()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

    function before_process() {
      global $aEbtVarSet;
      return false;
    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION AFTER_PROCESS()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function after_process() {
      global $aEbtVarSet, $customer_id, $insert_id;

      $aEbtVarSet   = $_SESSION['aEbtVarSet'];
      $customer_id  = $_SESSION['customer_id'];

      $sql_bankdata_array = array('customers_sepabanktransfer_owner'        => $aEbtVarSet['ebt_owner'],
                                  'customers_sepabanktransfer_iban'         => $aEbtVarSet["ebt_iban"],
                                  'customers_sepabanktransfer_swift'        => $aEbtVarSet['ebt_swift'],
                                  'customers_sepabanktransfer_swift_country_id'     => $aEbtVarSet['ebt_swift_id'],
                                  'customers_sepabanktransfer_name'         => $aEbtVarSet['ebt_name']);
                                  

      tep_db_perform(TABLE_CUSTOMERS, $sql_bankdata_array, 'update', "customers_id = '" . (int)$customer_id . "'");

      $sql_data_array = array('orders_id' => $insert_id,
                              'sepabanktransfer_owner'      => $aEbtVarSet['ebt_owner'],
                              'sepabanktransfer_iban'       => $aEbtVarSet['ebt_iban'],
                              'sepabanktransfer_swift'      => $aEbtVarSet["ebt_swift"],
                              'sepabanktransfer_name'       => $aEbtVarSet['ebt_name']);
      tep_db_perform(TABLE_SEPABT, $sql_data_array);
      // Order Status setzen.
      if ($this->order_status) {
        tep_db_query("UPDATE ". TABLE_ORDERS ." SET orders_status='".$this->order_status."' WHERE orders_id='".$insert_id."'");
      }
      // Array aus der Session nehmen
      tep_session_unregister('aEbtVarSet');
    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION GET_ERROR()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function get_error() {
      global $aEbtVarSet, $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_SEPABT_TEXT_BANK_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));
      return $error;
    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION CHECK()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function check() {
      global $aEbtVarSet;

      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SEPABT_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION INSTALL()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function install() {
      // Modulkonfiguration.
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Zahlung per SEPA Lastschrift erlauben.', 'MODULE_PAYMENT_SEPABT_STATUS', 'True', 'Hier legen Sie fest, ob das Lastschriftverfahren in Ihrem Shop grunds&auml;tzlich als Zahlungsoption angeboten wird.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Zahlungszone.', 'MODULE_PAYMENT_SEPABT_ZONE', '0', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Anzeigereihenfolge.', 'MODULE_PAYMENT_SEPABT_SORT_ORDER', '0', 'Reihenfolge der Anzeige in den Zahlungsmethoden. Die kleinste Ziffer wird hierbei zuerst angezeigt.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Mindestbestellwert in Euro.', 'MODULE_PAYMENT_SEPABT_MIN_ORDERVALUE', '0', 'Mindestbestellwert f&uuml;r das Lastschriftverfahren. Setzen Sie den Eintrag auf Null, um diese Einschr&auml;nkung abzuschalten.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Maximalbestellwert in Euro.', 'MODULE_PAYMENT_SEPABT_MAX_ORDERVALUE', '0', 'Maximalbestellwert f&uuml;r das Lastschriftverfahren. Setzen Sie den Eintrag auf Null, um diese Einschr&auml;nkung abzuschalten.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Bestellstatus festlegen.', 'MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID', '0', 'Die Bestellungen, welche mit diesem Modul bezahlt werden, auf diesen Status setzen.', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key,configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Notwendiger Status der Bestellung zur Berechnung.', 'MODULE_PAYMENT_SEPABT_ENABLE_AFTER_ORDER_STATUS', '3', 'Bestellungen m&uuml;ssen diesen Status haben, um gez&auml;hlt zu werden.', '6', '0', 'tep_cfg_pull_down_order_statuses(','tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Notwendige Bestellungen.', 'MODULE_PAYMENT_SEPABT_ENABLE_AFTER_TIMES', '0', 'Die Mindestanzahl an Bestellungen die ein Kunde ausgef&uuml;hrt haben muss, damit die Zahlungsoption Lastschriftverfahren zur Verf&uuml;gung steht.', '6', '0', now())");

      // Tabelle zur Speicherung der SEPA-Lastschriftdaten anlegen.
      tep_db_query("CREATE TABLE IF NOT EXISTS " . TABLE_SEPABT . " (orders_id int(11) NOT NULL default '0', sepabanktransfer_owner varchar(64) default NULL, sepabanktransfer_iban varchar(40) default NULL, sepabanktransfer_swift varchar(16) default NULL, sepabanktransfer_name varchar(64) default NULL, PRIMARY KEY (orders_id))");
      // Tabelle zur Speicherung der SWIFT-Codes anlegen
      tep_db_query("CREATE TABLE IF NOT EXISTS " . TABLE_SEPABT_SEPA . "(bic varchar(16) default NULL, i_country_id INTEGER DEFAULT 0, country varchar(20) default NULL, name varchar(128) default NULL, addresse varchar (128), ort varchar (128), gueltig_ab varchar (20), PRIMARY KEY (bic))");

        if (file_exists(DIR_FS_CATALOG.DIR_WS_MODULES .'payment/sepa.csv')){
          $data = file_get_contents(DIR_FS_CATALOG.DIR_WS_MODULES .'payment/sepa.csv');
          $lines = split("\n",$data);
          array_shift($lines); # drop leading description line
          # loop through lines
          foreach($lines as $line) {
           if($line!='') {
            # split to fields
            list($country,$name,$addresse,$ort,$bic,$gueltig_ab,$gueltig_bis) = split('\|',$line);
            # assign to registry
            $_sepa_registry = array(
                'country'       =>	$country,
                'name'	        =>	$name,
         				'addresse'	    =>	$addresse,
         				'ort'           =>	$ort,
        				'bic'			      =>	$bic,
        				'gueltig_ab'	  =>	$gueltig_ab
            );
            switch  ($country) {
             case 'AUSTRIA': $_sepa_registry['i_country_id'] = 14;  break;
             case 'BELGIUM': $_sepa_registry['i_country_id'] = 21;  break;
             case 'BULGARIA': $_sepa_registry['i_country_id'] = 33;  break;
             case 'SWITZERLAND': $_sepa_registry['i_country_id'] = 204;  break;
             case 'CYPRUS': $_sepa_registry['i_country_id'] = 55;  break;
             case 'CZECH REPUBLIC': $_sepa_registry['i_country_id'] = 56;  break;
             case 'GERMANY': $_sepa_registry['i_country_id'] = 81;  break;
             case 'DENMARK': $_sepa_registry['i_country_id'] = 57;  break;
             case 'SPAIN': $_sepa_registry['i_country_id'] = 195;  break;
             case 'FINLAND': $_sepa_registry['i_country_id'] = 72;  break;
             case 'FRANCE': $_sepa_registry['i_country_id'] = 73;  break;
             case 'UNITED KINGDOM': $_sepa_registry['i_country_id'] = 222;  break;
             case 'GREECE': $_sepa_registry['i_country_id'] = 84;  break;
             case 'HUNGARY': $_sepa_registry['i_country_id'] = 97;  break;
             case 'IRELAND': $_sepa_registry['i_country_id'] = 103;  break;
             case 'ITALY': $_sepa_registry['i_country_id'] = 105;  break;
             case 'LITHUANIA': $_sepa_registry['i_country_id'] = 123;  break;
             case 'LUXEMBOURG': $_sepa_registry['i_country_id'] = 124;  break;
             case 'LATVIA': $_sepa_registry['i_country_id'] = 117;  break;
             case 'MONACO': $_sepa_registry['i_country_id'] = 141;  break;
             case 'MALTA': $_sepa_registry['i_country_id'] = 132;  break;
             case 'NETHERLANDS': $_sepa_registry['i_country_id'] = 150;  break;
             case 'NORWAY': $_sepa_registry['i_country_id'] = 160;  break;
             case 'POLAND': $_sepa_registry['i_country_id'] = 170;  break;
             case 'PORTUGAL': $_sepa_registry['i_country_id'] = 171;  break;
             case 'ROMANIA': $_sepa_registry['i_country_id'] = 175;  break;
             case 'SWEDEN': $_sepa_registry['i_country_id'] = 203;  break;
             case 'SLOVENIA': $_sepa_registry['i_country_id'] = 190;  break;
             case 'SLOVAKIA': $_sepa_registry['i_country_id'] = 189;  break;
            }

           tep_db_perform(TABLE_SEPABT_SEPA, $_sepa_registry); 
           }
          }
         } 
        // Kundentabelle um die Bankinformationen erweitern, wenn notwendig.
      $must_alter_table = true;
      $fields = mysql_list_fields(DB_DATABASE, TABLE_CUSTOMERS);
      $columns = mysql_num_fields($fields);
      for ($i = 0; $i < $columns; $i++) {
        $field = mysql_field_name($fields, $i);
        if ($field == 'customers_sepabanktransfer_iban') {
          $must_alter_table = false;
        }
      }
      if ($must_alter_table == true) {
        tep_db_query("ALTER TABLE " . TABLE_CUSTOMERS . " ADD `customers_sepabanktransfer_owner` VARCHAR( 64 ) DEFAULT NULL AFTER `customers_newsletter`, ADD `customers_sepabanktransfer_iban` VARCHAR( 40 ) DEFAULT NULL AFTER `customers_sepabanktransfer_owner`, ADD `customers_sepabanktransfer_swift` VARCHAR( 16 ) DEFAULT NULL AFTER `customers_sepabanktransfer_iban`, ADD `customers_sepabanktransfer_name` VARCHAR( 64 ) DEFAULT NULL AFTER `customers_sepabanktransfer_swift`, ADD `customers_sepabanktransfer_swift_country_id` INT(11) UNSIGNED NOT NULL after `customers_sepabanktransfer_name`");
      }
    }
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION REMOVE()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "') " );
      tep_db_query("drop table " . TABLE_SEPABT_SEPA);
    }

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION KEYS()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function keys() {
      return array('MODULE_PAYMENT_SEPABT_STATUS',  'MODULE_PAYMENT_SEPABT_ZONE', 'MODULE_PAYMENT_SEPABT_SORT_ORDER', 'MODULE_PAYMENT_SEPABT_MIN_ORDERVALUE', 'MODULE_PAYMENT_SEPABT_MAX_ORDERVALUE', 'MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID',  'MODULE_PAYMENT_SEPABT_ENABLE_AFTER_TIMES', 'MODULE_PAYMENT_SEPABT_ENABLE_AFTER_ORDER_STATUS');
    }
/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   FUNCTION WRITELOG()
  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    function writelog( $descr, $lgString ) {
      global $aEbtVarSet;
      define('LOGFILE', DIR_WS_INCLUDES . 'data/ebt_validation.log');
      $dateTime   = date( "j F, Y, g:i a" );
      error_log ("[ebt_validation -> $dateTime] -> Referer: " . getenv( "HTTP_REFERER" ) .  "\n\t$descr: $lgString\n\n",  3, LOGFILE );
    }
  }   // End class

?>
