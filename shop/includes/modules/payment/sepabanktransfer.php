<?php
/* ----------------------------------------------------------------------
   $Id: sepabanktransfer.php,v 1.2 2007/10/24 23:38:34 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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

  class sepabanktransfer {
    var $code, $title, $description, $enabled;

// class constructor
    function sepabanktransfer() {
      global $oOrder, $aLang;

      $this->code = 'sepabanktransfer';
      $this->title = $aLang['module_payment_sepabt_text_title'];
      $this->description = $aLang['module_payment_sepabt_text_description'];
      $this->enabled = (defined('MODULE_PAYMENT_SEPABT_STATUS') && (MODULE_PAYMENT_SEPABT_STATUS == 'True') ? true : false);
      $this->sort_order = (defined('MODULE_PAYMENT_SEPABT_SORT_ORDER') ? MODULE_PAYMENT_SEPABT_SORT_ORDER : null);

      if ((int)MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID;
      }
      if (is_object($oOrder)) $this->update_status();

		if (isset($_GET['oID']) && is_numeric($_GET['oID'])) {
			// Get database information
			$dbconn =& oosDBGetConn();
			$oostable =& oosDBGetTables();		
			$oID = intval($_GET['oID']);
			$orders_totaltable = $oostable['order_transactions'];
			$orders_sql = "SELECT amount
                           FROM  $orders_totaltable 
                           WHERE orders_id = '" . intval($oID) . "'";
			$nTotal = $dbconn->GetOne($orders_sql);	
			if ($nTotal > (int)MODULE_PAYMENT_SEPABT_MAX_ORDER) {
				$this->enabled = false;
			}
	    }
	  
      if (isset($_POST['sepabanktransfer_fax']) && $_POST['sepabanktransfer_fax'] == 'on') {
        $this->email_footer = $aLang['module_payment_sepabanktransfer_text_email_footer'];
      }
    }

// class methods
    function update_status() {
      global $oOrder, $oCurrencies;

		if ($_SESSION['shipping']['id'] == 'selfpickup_selfpickup') {
			$this->enabled = false;
		}

		if (isset($_SESSION['guest_account']) && ($_SESSION['guest_account'] == '1')) {	
			$this->enabled = false;
		}
		
		#   $nAmount = number_format(($oOrder->info['total'] - $oOrder->info['shipping_cost']) * $oCurrencies->get_value($my_currency), $oCurrencies->get_decimal_places($my_currency));
  
		$nAmount = $oOrder->info['total'] - $oOrder->info['shipping_cost'];
		if ($nAmount > (int)MODULE_PAYMENT_SEPABT_MAX_ORDER) {
			$this->enabled = false;
		}

	
		
		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_SEPABT_ZONE > 0) ) {
			$check_flag = false;

			// Get database information
			$dbconn =& oosDBGetConn();
			$oostable =& oosDBGetTables();

			$zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
			$check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_SEPABT_ZONE . "' AND zone_country_id = '" . $oOrder->billing['country']['id'] . "' ORDER BY zone_id");
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

      return $js;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
	  
    }

    function pre_confirmation_check(){
      global $sepabanktransfer_number, $sepabanktransfer_blz, $aLang;

	  /*
      if ($_POST['sepabanktransfer_fax'] == false) {
        include 'includes/classes/class_sepabanktransfer_validation.php';

        $sepabanktransfer_validation = new AccountCheck;
        $sepabanktransfer_result = $sepabanktransfer_validation->CheckAccount($sepabanktransfer_number, $sepabanktransfer_blz);

		if (isset($_SESSION['payment_error']) ) {
			$_SESSION['payment_error'] = '';
		}
        if ($sepabanktransfer_result > 0 ||  $_POST['sepabanktransfer_owner'] == '') {
          if ($_POST['sepabanktransfer_owner'] == '') {
            $error = 'Name des Kontoinhabers fehlt!';
            $recheckok = '';
          } else {
            switch ($sepabanktransfer_result) {
              case 1: // number & blz not ok
                $error = $aLang['module_payment_sepabanktransfer_text_bank_error_1'];
                $recheckok = 'true';
                break;

              case 5: // BLZ not found
                $error = $aLang['module_payment_sepabanktransfer_text_bank_error_5'];
                $recheckok = 'true';
                break;

              case 8: // no blz entered
                $error = $aLang['module_payment_sepabanktransfer_text_bank_error_8'];
                $recheckok = '';
                break;

              case 9: // no number entered
                $error = $aLang['module_payment_sepabanktransfer_text_bank_error_9'];
                $recheckok = '';
                break;

              default:
                $error = $aLang['module_payment_sepabanktransfer_text_bank_error_4'];
                $recheckok = 'true';
                break;
            }
          }

          if ($_POST['recheckok'] != 'true') {
            $payment_error_return = 'sepabanktransfer=true&payment_error=' . $this->code . '&error=' . urlencode($error) . '&sepabanktransfer_owner=' . urlencode($_POST['sepabanktransfer_owner']) . '&sepabanktransfer_number=' . urlencode($_POST['sepabanktransfer_number']) . '&sepabanktransfer_blz=' . urlencode($_POST['sepabanktransfer_blz']) . '&sepabanktransfer_bankname=' . urlencode($_POST['sepabanktransfer_bankname']) . '&recheckok=' . $recheckok;
            $aFilename = oos_get_filename();
            $aModules = oos_get_modules();
			
				if (isset($_POST['oID']) && is_numeric($_POST['oID'])) {
					$oID = intval($_POST['oID']);
					$payment_error_return .= '&oID=' . $oID;

					$_SESSION['payment_error'] = $error;
					# $payment_error_return = str_replace(array('?', '&amp;', '='), '/', $payment_error_return);
					oos_redirect(oos_href_link($aModules['payment'], $aFilename['payment_process'], $payment_error_return, 'SSL', true, false));
				} else {
					oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_confirmation'], $payment_error_return, 'SSL', true, false));			
				
				}
          }
        }

        $this->sepabanktransfer_owner = oos_prepare_input($_POST['sepabanktransfer_owner']);
        $this->sepabanktransfer_blz = oos_prepare_input($_POST['sepabanktransfer_blz']);
        $this->sepabanktransfer_number = oos_prepare_input($_POST['sepabanktransfer_number']);
        $this->sepabanktransfer_prz = $sepabanktransfer_validation->PRZ;
        $this->sepabanktransfer_status = $sepabanktransfer_result;
        if ($sepabanktransfer_validation->Bankname != '')
          $this->sepabanktransfer_bankname = $sepabanktransfer_validation->Bankname;
        else
          $this->sepabanktransfer_bankname = oos_prepare_input($_POST['sepabanktransfer_bankname']);
      }
	  */
    }

    function confirmation() {
      global $aLang, $sepabanktransfer_val, $sepabanktransfer_owner, $sepabanktransfer_bankname, $sepabanktransfer_blz, $sepabanktransfer_number, $checkout_form_action, $checkout_form_submit;

      if (!$_POST['sepabanktransfer_owner'] == '') {
        $confirmation = array('title' => $this->title,
                              'fields' => array(array('title' => $aLang['module_payment_sepabanktransfer_text_bank_owner'],
                                                      'field' => $this->sepabanktransfer_owner),
                                                array('title' => $aLang['module_payment_sepabanktransfer_text_bank_blz'],
                                                      'field' => $this->sepabanktransfer_blz),
                                                array('title' => $aLang['module_payment_sepabanktransfer_text_bank_number'],
                                                      'field' => $this->sepabanktransfer_number),
                                                array('title' => $aLang['module_payment_sepabanktransfer_text_bank_name'],
                                                      'field' => $this->sepabanktransfer_bankname)
                                                ));
      }
      if ($_POST['sepabanktransfer_fax'] == "on") {
        $confirmation = array('fields' => array(array('title' => $aLang['module_payment_sepabanktransfer_text_bank_fax'])));
        $this->sepabanktransfer_fax = "on";
      }
      return $confirmation;
    }

    function process_button() {

      $process_button_string = oos_draw_hidden_field('sepabanktransfer_blz', $this->sepabanktransfer_blz) .
                               oos_draw_hidden_field('sepabanktransfer_bankname', $this->sepabanktransfer_bankname).
                               oos_draw_hidden_field('sepabanktransfer_number', $this->sepabanktransfer_number) .
                               oos_draw_hidden_field('sepabanktransfer_owner', $this->sepabanktransfer_owner) .
                               oos_draw_hidden_field('sepabanktransfer_status', $this->sepabanktransfer_status) .
                               oos_draw_hidden_field('sepabanktransfer_prz', $this->sepabanktransfer_prz) .
                               oos_draw_hidden_field('sepabanktransfer_fax', $this->sepabanktransfer_fax);

      return $process_button_string;

    }

    function before_process() {
      return false;
    }

    function after_process() {
      global $insert_id, $sepabanktransfer_val, $sepabanktransfer_owner, $sepabanktransfer_bankname, $sepabanktransfer_blz, $sepabanktransfer_number, $sepabanktransfer_status, $sepabanktransfer_prz, $sepabanktransfer_fax, $checkout_form_action, $checkout_form_submit;

	  
      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();
		if (isset($_SESSION['payment_error']) ) {
			$_SESSION['payment_error'] = '';
		}
		
		
		if (isset($_POST['oID']) && is_numeric($_POST['oID'])) {
			// Get database information

			$oID = intval($_POST['oID']);
			$insert_id = $oID;			
			$title = $this->title; 
			$orders_table = $oostable['orders'];
			$dbconn->Execute("UPDATE $orders_table SET payment_method = '" . $title . "' WHERE orders_id  = " . intval($oID));

		
			if ($_SESSION['guest_account'] == 1) {
				$customers_id = intval($_SESSION['customer_id']);
				// $dbconn->Execute("DELETE FROM " . $oostable['reviews'] . " WHERE customers_id = '" . intval($customers_id) . "'");
				$dbconn->Execute("DELETE FROM " . $oostable['address_book'] . " WHERE customers_id = '" . intval($customers_id) . "'");
				$dbconn->Execute("DELETE FROM " . $oostable['customers'] . " WHERE customers_id = '" . intval($customers_id) . "'");
				$dbconn->Execute("DELETE FROM " . $oostable['customers_info'] . " WHERE customers_info_id = '" . intval($customers_id) . "'");
				$dbconn->Execute("DELETE FROM " . $oostable['customers_basket'] . " WHERE customers_id = '" . intval($customers_id) . "'");
				$dbconn->Execute("DELETE FROM " . $oostable['customers_basket_attributes'] . " WHERE customers_id = '" . intval($customers_id) . "'");
				$dbconn->Execute("DELETE FROM " . $oostable['customers_wishlist'] . " WHERE customers_id = '" . intval($customers_id) . "'");
				$dbconn->Execute("DELETE FROM " . $oostable['customers_wishlist_attributes'] . " WHERE customers_id = '" . intval($customers_id) . "'");
				$dbconn->Execute("DELETE FROM " . $oostable['customers_status_history'] . " WHERE customers_id = '" . intval($customers_id) . "'");
				// $dbconn->Execute("DELETE FROM " . $oostable['whos_online'] . " WHERE customer_id = '" . intval($customers_id)  . "'");
			}
		}
	  
	  
	  
      $dbconn->Execute("INSERT INTO " . $oostable['sepabanktransfer'] . "
                  (orders_id,
                   sepabanktransfer_blz,
                   sepabanktransfer_bankname,
                   sepabanktransfer_number,
                   sepabanktransfer_owner,
                   sepabanktransfer_status,
                   sepabanktransfer_prz) VALUES ('" . (int)$insert_id . "',
                                             '" . oos_db_input($sepabanktransfer_blz) . "',
                                             '" . oos_db_input($sepabanktransfer_bankname) . "',
                                             '" . oos_db_input($sepabanktransfer_number) . "',
                                             '" . oos_db_input($sepabanktransfer_owner) ."',
                                             '" . oos_db_input($sepabanktransfer_status) ."',
                                             '" . oos_db_input($sepabanktransfer_prz) ."')");
      if ($_POST['sepabanktransfer_fax']) {
        $dbconn->Execute("UPDATE " . $oostable['sepabanktransfer'] . "
                      SET sepabanktransfer_fax = '" . oos_db_input($sepabanktransfer_fax) ."'
                      WHERE orders_id = '" . (int)$insert_id . "'");
		}
					  
		if (isset($_POST['oID']) && is_numeric($_POST['oID'])) {
		$aFilename = oos_get_filename();
		$aModules = oos_get_modules();
			oos_redirect(oos_href_link($aModules['checkout'], $aFilename['checkout_success'], '', 'SSL'));
		}
    }

    function get_error() {
      global $aLang;

      $error = array('title' => $aLang['module_payment_sepabanktransfer_text_bank_error'],
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_PAYMENT_SEPABT_STATUS');
      }

      return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_SEPABT_STATUS', 'True', '6', '1', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_SEPABT_ZONE', '0', '6', '2', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_SEPABT_MAX_ORDER', '500', '6', '5', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_SEPABT_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_SEPABT_CREDITORID', '',  '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_SEPABT_PAYEE', '',  '6', '0', now())");
	  }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_SEPABT_STATUS', 'MODULE_PAYMENT_SEPABT_ZONE', 'MODULE_PAYMENT_SEPABT_ORDER_STATUS_ID', 'MODULE_PAYMENT_SEPABT_SORT_ORDER', 'MODULE_PAYMENT_SEPABT_MAX_ORDER', 'MODULE_PAYMENT_SEPABT_CREDITORID', 'MODULE_PAYMENT_SEPABT_PAYEE');
    }
  }
