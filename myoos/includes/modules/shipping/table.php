<?php
/* ----------------------------------------------------------------------
   $Id: table.php,v 1.2 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2020 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: table.php,v 1.27 2003/02/05 22:41:52 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class table {
    var $code, $title, $description, $icon, $enabled = FALSE;

// class constructor
    public function __construct() {
      global $oOrder, $aLang;

      $this->code = 'table';
      $this->title = $aLang['module_shipping_table_text_title'];
      $this->description = $aLang['module_shipping_table_text_description'];
      $this->sort_order = (defined('MODULE_SHIPPING_TABLE_SORT_ORDER') ? MODULE_SHIPPING_TABLE_SORT_ORDER : null);
      $this->icon = '';
      $this->tax_class = (defined('MODULE_SHIPPING_TABLE_TAX_CLASS') ? MODULE_SHIPPING_TABLE_TAX_CLASS : null);
      $this->enabled = (defined('MODULE_SHIPPING_TABLE_STATUS') && (MODULE_SHIPPING_TABLE_STATUS == 'True') ? true : false);

      if ( ($this->enabled == TRUE) && ((int)MODULE_SHIPPING_TABLE_ZONE > 0) ) {
        $check_flag = FALSE;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_SHIPPING_TABLE_ZONE . "' AND zone_country_id = '" . $oOrder->delivery['country']['id'] . "' ORDER BY zone_id");
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

        // Close result set
        $check_result->Close();

        if ($check_flag == FALSE) {
          $this->enabled = FALSE;
        }
      }
    }

// class methods
    function quote($method = '') {
      global $oOrder, $aLang, $shipping_weight, $shipping_num_boxes;

      if (MODULE_SHIPPING_TABLE_MODE == 'price') {
        $oOrder_total = $_SESSION['cart']->show_total();
      } else {
        $oOrder_total = $shipping_weight;
      }

      $table_cost = preg_split("/[:,]/" , MODULE_SHIPPING_TABLE_COST);
      $size = count($table_cost);
      for ($i=0, $n=$size; $i<$n; $i+=2) {
        if ($oOrder_total <= $table_cost[$i]) {
          $shipping = $table_cost[$i+1];
          break;
        }
      }

      if (MODULE_SHIPPING_TABLE_MODE == 'weight') {
        $shipping = $shipping * $shipping_num_boxes;
      }

      $this->quotes = array('id' => $this->code,
                            'module' =>$aLang['module_shipping_table_text_title'],
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $aLang['module_shipping_table_text_way'],
                                                     'cost' => $shipping + MODULE_SHIPPING_TABLE_HANDLING)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = oos_get_tax_rate($this->tax_class, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
      }

      if (oos_is_not_null($this->icon)) $this->quotes['icon'] = oos_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_SHIPPING_TABLE_STATUS');
      }

      return $this->_check;
    }


    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_TABLE_STATUS', 'True', '6', '0', 'oos_cfg_select_option(array(\'True\', \'False\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_TABLE_COST', '25:8.50,50:5.50,10000:0.00', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_TABLE_MODE', 'weight', '6', '0', 'oos_cfg_select_option(array(\'weight\', \'price\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_TABLE_HANDLING', '0', '6', '0', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_SHIPPING_TABLE_TAX_CLASS', '0', '6', '0', 'oos_cfg_get_tax_class_title', 'oos_cfg_pull_down_tax_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_SHIPPING_TABLE_ZONE', '0', '6', '0', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_TABLE_SORT_ORDER', '0', '6', '0', now())");
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }


    function keys() {
      return array('MODULE_SHIPPING_TABLE_STATUS', 'MODULE_SHIPPING_TABLE_COST', 'MODULE_SHIPPING_TABLE_MODE', 'MODULE_SHIPPING_TABLE_HANDLING', 'MODULE_SHIPPING_TABLE_TAX_CLASS', 'MODULE_SHIPPING_TABLE_ZONE', 'MODULE_SHIPPING_TABLE_SORT_ORDER');
    }
  }

