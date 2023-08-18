<?php
/**
   ----------------------------------------------------------------------
   $Id: dp.php,v 1.3 2008/06/04 14:41:38 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: dp.php,v 1.36 2003/03/09 02:14:35 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class dp
{
    public $code = 'dp';
    public $title;
    public $description;
    public $icon;
    public $num_dp;
    public $enabled = false;

    // class constructor
    public function __construct()
    {
        global $oOrder, $aLang;
        $this->title = $aLang['module_shipping_dp_text_title'];
        $this->description = $aLang['module_shipping_dp_text_description'];
        $this->sort_order = (defined('MODULE_SHIPPING_DP_SORT_ORDER') ? MODULE_SHIPPING_DP_SORT_ORDER : null);
        $this->icon = OOS_ICONS . 'shipping_dp.gif';
        $this->enabled = (defined('MODULE_SHIPPING_DP_STATUS') && (MODULE_SHIPPING_DP_STATUS == 'true') ? true : false);

        if (($this->enabled == true) && isset($oOrder->delivery['country']['id']) && ((int)MODULE_SHIPPING_DP_ZONE > 0)) {
            $check_flag = false;

            // Get database information
            $dbconn =& oosDBGetConn();
            $oostable =& oosDBGetTables();

            $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
            $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_SHIPPING_DP_ZONE . "' AND zone_country_id = '" . intval($oOrder->delivery['country']['id']) . "' ORDER BY zone_id");
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

        // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
        $this->num_dp = 6;
    }

    // class methods
    public function quote($method = '')
    {
        global $aLang, $oOrder, $shipping_weight, $shipping_num_boxes;

        if (!is_object($oOrder)) {
            $dest_country = isset($_SESSION['delivery_zone']) ? oos_prepare_input($_SESSION['delivery_zone']) : STORE_ORIGIN_COUNTRY;
        } else {
            $dest_country = $oOrder->delivery['country']['iso_code_2'];
        }
        $dest_zone = 0;
        $error = false;

        for ($i=1; $i<=$this->num_dp; $i++) {
            $countries_table = constant('MODULE_SHIPPING_DP_COUNTRIES_' . $i);
            $country_zones = preg_split("/[,]/", (string) $countries_table);
            if (in_array($dest_country, $country_zones)) {
                $dest_zone = $i;
                break;
            }
        }

        if ($dest_zone == 0) {
            $error = true;
        } else {
            $shipping = -1;
            $dp_cost = constant('MODULE_SHIPPING_DP_COST_' . $i);

            $dp_table = preg_split("/[:,]/", (string) $dp_cost);
            for ($i=0; $i<(is_countable($dp_table) ? count($dp_table) : 0); $i+=2) {
                if ($shipping_weight <= $dp_table[$i]) {
                    $shipping = $dp_table[$i+1];
                    $shipping_method = $aLang['module_shipping_dp_text_way'] . ' ' . $dest_country . ': ';
                    break;
                }
            }

            if ($shipping == -1) {
                $shipping_cost = 0;
                $shipping_method = $aLang['module_shipping_dp_undefined_rate'];
            } else {
                $shipping_cost = ($shipping + MODULE_SHIPPING_DP_HANDLING);
            }
        }

        $this->quotes = ['id' => $this->code, 'module' => $aLang['module_shipping_dp_text_title'], 'methods' => [['id' => $this->code, 'title' => $shipping_method . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . ' ' . $aLang['module_shipping_dp_text_units'] .')', 'cost' => $shipping_cost * $shipping_num_boxes]]];

        if (oos_is_not_null($this->icon)) {
            $this->quotes['icon'] = oos_image($this->icon, $this->title);
        }

        if ($error == true) {
            $this->quotes['error'] = $aLang['module_shipping_dp_invalid_zone'];
        }

        return $this->quotes;
    }


    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_SHIPPING_DP_STATUS');
        }

        return $this->_check;
    }


    public function install()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_DP_STATUS', 'true', '6', '0', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_HANDLING', '0', '6', '0', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_SHIPPING_DP_ZONE', '0', '6', '0', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_SORT_ORDER', '0', '6', '0', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COUNTRIES_1', 'AD,AT,BE,CZ,DK,FO,FI,FR,GR,GL,IE,IT,LI,LU,MC,NL,PL,PT,SM,SK,SE,CH,VA,GB,SP', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COST_1', '5:16.50,10:20.50,20:28.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COUNTRIES_2', 'AL,AM,AZ,BY,BA,BG,HR,CY,GE,GI,HU,IS,KZ,LT,MK,MT,MD,NO,SI,UA,TR,YU,RU,RO,LV,EE', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COST_2', '5:25.00,10:35.00,20:45.00', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COUNTRIES_3', 'DZ,BH,CA,EG,IR,IQ,IL,JO,KW,LB,LY,OM,SA,SY,US,AE,YE,MA,QA,TN,PM', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COST_3', '5:29.00,10:39.00,20:59.00', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COUNTRIES_4', 'AF,AS,AO,AI,AG,AR,AW,AU,BS,BD,BB,BZ,BJ,BM,BT,BO,BW,BR,IO,BN,BF,BI,KH,CM,CV,KY,CF,TD,CL,CN,CC,CO,KM,CG,CR,CI,CU,DM,DO,EC,SV,ER,ET,FK,FJ,GF,PF,GA,GM,GH,GD,GP,GT,GN,GW,GY,HT,HN,HK,IN,ID,JM,JP,KE,KI,KG,KP,KR,LA,LS', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COST_4', '5:35.00,10:50.00,20:80.00', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COUNTRIES_5', 'MO,MG,MW,MY,MV,ML,MQ,MR,MU,MX,MN,MS,MZ,MM,NA,NR,NP,AN,NC,NZ,NI,NE,NG,PK,PA,PG,PY,PE,PH,PN,RE,KN,LC,VC,SN,SC,SL,SO,LK,SR,SZ,ZA,SG,TG,TH,TZ,TT,TO,TM,TV,VN,WF,VE,UG,UZ,UY,ST,SH,SD,TW,GQ,LR,DJ,CG,RW,ZM,ZW', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COST_5', '5:35.00,10:50.00,20:80.00', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COUNTRIES_6', 'DE', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_DP_COST_6', '5:6.70,10:9.70,20:13.00', '6', '0', now())");
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
        $keys = ['MODULE_SHIPPING_DP_STATUS', 'MODULE_SHIPPING_DP_HANDLING', 'MODULE_SHIPPING_DP_ZONE', 'MODULE_SHIPPING_DP_SORT_ORDER'];

        for ($i = 1; $i <= $this->num_dp; $i ++) {
            $keys[count($keys)] = 'MODULE_SHIPPING_DP_COUNTRIES_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_DP_COST_' . $i;
        }

        return $keys;
    }
}
