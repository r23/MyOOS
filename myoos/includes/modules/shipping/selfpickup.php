<?php
/**
   ----------------------------------------------------------------------
   $Id: selfpickup.php,v 1.2 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: selfpickup.php,v 1.39 2003/02/05 22:41:52 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class selfpickup
{
    public $code = 'selfpickup';
    public $title;
    public $description;
    public $icon = '';
    public $enabled = false;

    // class constructor
    public function __construct()
    {
        global $oOrder, $aLang;
        $this->title = $aLang['module_shipping_selfpickup_text_title'];
        $this->description = $aLang['module_shipping_selfpickup_text_description'];
        $this->sort_order = (defined('MODULE_SHIPPING_SELFPICKUP_SORT_ORDER') ? MODULE_SHIPPING_SELFPICKUP_SORT_ORDER : null);
        $this->enabled = (defined('MODULE_SHIPPING_SELFPICKUP_STATUS') && (MODULE_SHIPPING_SELFPICKUP_STATUS == 'true') ? true : false);

        if (($this->enabled == true) && isset($oOrder->delivery['country']['id']) && ((int)MODULE_SHIPPING_SELFPICKUP_ZONE > 0)) {
            $check_flag = false;

            // Get database information
            $dbconn = & oosDBGetConn();
            $oostable = & oosDBGetTables();

            $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
            $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_SHIPPING_SELFPICKUP_ZONE . "' and zone_country_id = '" . intval($oOrder->delivery['country']['id']) . "' ORDER BY zone_id");
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
        global $oOrder, $aLang,  $total_count;

        $this->quotes = ['id' => $this->code, 'module' => $aLang['module_shipping_selfpickup_text_title'], 'methods' => [['id' => $this->code, 'title' => $aLang['module_shipping_selfpickup_text_way'], 'cost' => 0]]];



        if (oos_is_not_null($this->icon)) {
            $this->quotes['icon'] = oos_image($this->icon, $this->title);
        }

        return $this->quotes;
    }


    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_SHIPPING_SELFPICKUP_STATUS');
        }

        return $this->_check;
    }


    public function install()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_SELFPICKUP_STATUS', 'true', '6', '0', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_SHIPPING_SELFPICKUP_ZONE', '0', '6', '0', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_SELFPICKUP_SORT_ORDER', '0', '6', '0', now())");
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
        return ['MODULE_SHIPPING_SELFPICKUP_STATUS', 'MODULE_SHIPPING_SELFPICKUP_ZONE', 'MODULE_SHIPPING_SELFPICKUP_SORT_ORDER'];
    }
}
