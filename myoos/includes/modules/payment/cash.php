<?php
/* ----------------------------------------------------------------------
   $Id: cash.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: cash.php,v 1.01 2003/02/19 01:59:00 harley_vb  
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Copyright (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Plänkers
       http://www.themedia.at & http://www.oscommerce.at

                    All rights reserved.

   This program is free software licensed under the GNU General Public License (GPL).

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
   USA
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

class cash {
    var $code, $title, $description, $enabled;

// class constructor
    public function __construct() {
		global $oOrder, $aLang;

		$this->code = 'cash';
		$this->title = $aLang['module_payment_cash_text_title'];
		$this->description = $aLang['module_payment_cash_text_description'];
		$this->enabled = (defined('MODULE_PAYMENT_CASH_STATUS') && (MODULE_PAYMENT_CASH_STATUS == 'True') ? TRUE : FALSE);
	  
		$this->sort_order = (defined('MODULE_PAYMENT_CASH_SORT_ORDER') ? MODULE_PAYMENT_CASH_SORT_ORDER : NULL);

		if ((defined('MODULE_PAYMENT_CASH_ORDER_STATUS_ID') && (int)MODULE_PAYMENT_CASH_ORDER_STATUS_ID > 0)) {
			$this->order_status = MODULE_PAYMENT_CASH_ORDER_STATUS_ID;
		}
	  
		if ( $this->enabled === TRUE ) {
			if ( isset($oOrder) && is_object($oOrder) ) {
				$this->update_status();
			}
		}	
    }

// class methods
    function update_status() {
		global $oOrder;


		if ($_SESSION['shipping']['id'] != 'selfpickup_selfpickup') {
			$this->enabled = FALSE;
		}


		if ( ($this->enabled == TRUE) && ((int)MODULE_PAYMENT_CASH_ZONE > 0) ) {
			$check_flag = FALSE;

			// Get database information
			$dbconn =& oosDBGetConn();
			$oostable =& oosDBGetTables();

			$zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
			$check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_PAYMENT_CASH_ZONE . "' AND zone_country_id = '" . $oOrder->delivery['country']['id'] . "' ORDER BY zone_id");
						
			while ($check = $check_result->fields) {
				if ($check['zone_id'] < 1) {
					$check_flag = TRUE;
					break;
				} elseif ($check['zone_id'] == $oOrder->delivery['zone_id']) {
					$check_flag = TRUE;
					break;
				}

				// Move that ADOdb pointer!
				$check_result->MoveNext();
			}

			if ($check_flag == FALSE) {
				$this->enabled = FALSE;
			}		
		}

// disable the module if the order only contains virtual products
		if ($this->enabled == TRUE) {
			if ($oOrder->content_type == 'virtual') {			
				$this->enabled = FALSE;
			}
		}

	}

// class methods
    function javascript_validation() {
		return FALSE;
    }

    function selection() {
		return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check(){
		return FALSE;
    }

    function confirmation() {
		return FALSE;
    }

    function process_button() {
		return FALSE;
    }

    function before_process() {
		return FALSE;
    }

    function after_process() {
		return FALSE;
    }

    function get_error() {
		return FALSE;
    }

    function check() {
		if (!isset($this->_check)) {
			$this->_check = defined('MODULE_PAYMENT_CASH_STATUS');
		}

		return $this->_check;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_PAYMENT_CASH_STATUS', 'True',  '6', '0', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now());");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_PAYMENT_CASH_ZONE', '0', '6', '0', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_PAYMENT_CASH_SORT_ORDER', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('MODULE_PAYMENT_CASH_ORDER_STATUS_ID', '0', '6', '0', 'oos_cfg_pull_down_order_statuses(', 'oos_cfg_get_order_status_name', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
		return array('MODULE_PAYMENT_CASH_STATUS', 'MODULE_PAYMENT_CASH_ZONE', 'MODULE_PAYMENT_CASH_ORDER_STATUS_ID', 'MODULE_PAYMENT_CASH_SORT_ORDER');
    }
}

