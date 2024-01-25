<?php
/**
   ----------------------------------------------------------------------
   $Id: table.php,v 1.2 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: table.php,v 1.27 2003/02/05 22:41:52 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class table
{
    public $code = 'table';
    public $title;
    public $description;
    public $icon = '';
    public $enabled = false;

    // class constructor
    public function __construct()
    {
        global $oOrder, $aLang;
        $this->title = $aLang['module_shipping_table_text_title'];
        $this->description = $aLang['module_shipping_table_text_description'];
        $this->sort_order = (defined('MODULE_SHIPPING_TABLE_SORT_ORDER') ? MODULE_SHIPPING_TABLE_SORT_ORDER : null);
        $this->enabled = (defined('MODULE_SHIPPING_TABLE_STATUS') && (MODULE_SHIPPING_TABLE_STATUS == 'true') ? true : false);


        if (!is_object($oOrder)) {
            $dest_country = (isset($_SESSION['delivery_country_id'])) ? intval($_SESSION['delivery_country_id']) : STORE_COUNTRY;
        } else {
            $dest_country = $oOrder->delivery['country']['id'];
        }


        if (($this->enabled == true) && ((int)MODULE_SHIPPING_TABLE_ZONE > 0)) {
            $check_flag = false;

            // Get database information
            $dbconn = & oosDBGetConn();
            $oostable = & oosDBGetTables();

            $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
            $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_SHIPPING_TABLE_ZONE . "' AND zone_country_id = '" . intval($dest_country) . "' ORDER BY zone_id");
            while ($check = $check_result->fields) {
                if ($check['zone_id'] < 1) {
                    $check_flag = true;
                    break;
                } elseif ($check['zone_id'] == $oOrder->delivery['zone_id']) {
                    $check_flag = true;
                    break;
                }

                // Move that ADOdb pointer!
                $check_result->MoveNext();
            }

            if ($check_flag == false) {
                $this->enabled = false;
            }
        }
    }

    // class methods
    public function quote($method = '')
    {
        global $oOrder, $aLang, $shipping_weight, $shipping_num_boxes;

        if (MODULE_SHIPPING_TABLE_MODE == 'price') {
            $oOrder_total = $_SESSION['cart']->show_total();
        } else {
            $oOrder_total = $shipping_weight;
        }

        $table_cost = preg_split("/[:,]/", (string) MODULE_SHIPPING_TABLE_COST);
        $n = is_countable($table_cost) ? count($table_cost) : 0;
        for ($i = 0, $n; $i < $n; $i += 2) {
            if ($oOrder_total <= $table_cost[$i]) {
                $shipping = $table_cost[$i + 1];
                break;
            }
        }

        if (MODULE_SHIPPING_TABLE_MODE == 'weight') {
            $shipping = $shipping * $shipping_num_boxes;
        }

        $this->quotes = ['id' => $this->code, 'module' => $aLang['module_shipping_table_text_title'], 'methods' => [['id' => $this->code, 'title' => $aLang['module_shipping_table_text_way'], 'cost' => $shipping + MODULE_SHIPPING_TABLE_HANDLING]]];


        if (oos_is_not_null($this->icon)) {
            $this->quotes['icon'] = oos_image($this->icon, $this->title);
        }

        return $this->quotes;
    }

    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_SHIPPING_TABLE_STATUS');
        }
        return $this->_check;
    }


    public function install()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_TABLE_STATUS', 'true', '6', '0', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_TABLE_COST', '25:8.50,50:5.50,10000:0.00', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_TABLE_MODE', 'weight', '6', '0', 'oos_cfg_select_option(array(\'weight\', \'price\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, date_added) VALUES ('MODULE_SHIPPING_TABLE_HANDLING', '0', '6', '0', 'currencies->format', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_TABLE_ZONE', 'DE,AT', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_TABLE_SORT_ORDER', '0', '6', '0', now())");
    }

    public function remove()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }


    public function keys()
    {
        return ['MODULE_SHIPPING_TABLE_STATUS', 'MODULE_SHIPPING_TABLE_COST', 'MODULE_SHIPPING_TABLE_MODE', 'MODULE_SHIPPING_TABLE_HANDLING', 'MODULE_SHIPPING_TABLE_ZONE', 'MODULE_SHIPPING_TABLE_SORT_ORDER'];
    }
}
