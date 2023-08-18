<?php
/**
   ----------------------------------------------------------------------
   $Id: weight.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: weight.php,v 1.05 2003/02/18 03:37:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class weight
{
    public $code = 'weight';
    public $title;
    public $description;
    public $icon = '';
    public $enabled = false;

    // class constructor
    public function __construct()
    {
        global $oOrder, $aLang;
        $this->title = $aLang['module_shipping_weight_text_title'];
        $this->description = $aLang['module_shipping_weight_text_description'];
        $this->sort_order = (defined('MODULE_SHIPPING_WEIGHT_SORT_ORDER') ? MODULE_SHIPPING_WEIGHT_SORT_ORDER : null);
        $this->enabled = (defined('MODULE_SHIPPING_WEIGHT_STATUS') && (MODULE_SHIPPING_WEIGHT_STATUS == 'true') ? true : false);

        if (($this->enabled == true) && ((defined('MODULE_SHIPPING_WEIGHT_ZONE') && (int)MODULE_SHIPPING_WEIGHT_ZONE > 0))) {
            $check_flag = false;

            if (!is_object($oOrder)) {
                $dest_country = (isset($_SESSION['delivery_country_id'])) ? intval($_SESSION['delivery_country_id']) : STORE_COUNTRY;
            } else {
                $dest_country = $oOrder->delivery['country']['id'];
            }


            // Get database information
            $dbconn =& oosDBGetConn();
            $oostable =& oosDBGetTables();

            $check_result = $dbconn->Execute("SELECT zone_id FROM " . $oostable['zones_to_geo_zones'] . " WHERE geo_zone_id = '" . MODULE_SHIPPING_WEIGHT_ZONE . "' AND zone_country_id = '" . intval($dest_country) . "' ORDER BY zone_id");
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

            // Close result set
            $check_result->Close();

            if ($check_flag == false) {
                $this->enabled = false;
            }
        }
    }

    // class methods
    public function quote($method = '')
    {
        global $oOrder, $aLang, $shipping_weight;

        $weight_cost = preg_split("/[:,]/", (string) MODULE_SHIPPING_WEIGHT_COST);

        if ($shipping_weight > $weight_cost[(is_countable($weight_cost) ? count($weight_cost) : 0)-2]) {
            $shipping = ($shipping_weight-$weight_cost[(is_countable($weight_cost) ? count($weight_cost) : 0)-2])* MODULE_SHIPPING_WEIGHT_STEP +$weight_cost[(is_countable($weight_cost) ? count($weight_cost) : 0)-1];
        }
        for ($i = 0; $i < (is_countable($weight_cost) ? count($weight_cost) : 0); $i+=2) {
            if ($shipping_weight <= $weight_cost[$i]) {
                $shipping = $weight_cost[$i+1];
                break;
            }
        }

        $this->quotes = ['id' => $this->code, 'module' => $aLang['module_shipping_weight_text_title'], 'methods' => [['id' => $this->code, 'title' => $aLang['module_shipping_weight_text_way'], 'cost' => $shipping + MODULE_SHIPPING_WEIGHT_HANDLING]]];


        if (oos_is_not_null($this->icon)) {
            $this->quotes['icon'] = oos_image($this->icon, $this->title);
        }

        return $this->quotes;
    }


    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_SHIPPING_WEIGHT_STATUS');
        }

        return $this->_check;
    }


    public function install()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_STATUS', 'true', '6', '0', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_HANDLING', '5', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_ZONE', '0', '6', '0', '', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_SORT_ORDER', '0', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_COST', '31:15,40:28,50:30.5,100:33', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_STEP', '0.28', '6', '0', 'currencies->format', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_WEIGHT_MODE', 'weight', '6', '0', 'oos_cfg_select_option(array(\'weight\', \'price\'), ', now())");
    }


    public function remove()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }


    public function keys()
    {
        return ['MODULE_SHIPPING_WEIGHT_STATUS', 'MODULE_SHIPPING_WEIGHT_HANDLING', 'MODULE_SHIPPING_WEIGHT_COST', 'MODULE_SHIPPING_WEIGHT_STEP', 'MODULE_SHIPPING_WEIGHT_ZONE', 'MODULE_SHIPPING_WEIGHT_SORT_ORDER', 'MODULE_SHIPPING_WEIGHT_MODE'];
    }
}
