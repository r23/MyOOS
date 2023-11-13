<?php
/**
   ----------------------------------------------------------------------
   $Id: ap.php,v 1.4 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ap.php,v 1.05 2003/02/18 03:37:00 harley_vb
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
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class ap
{
    public $code = 'ap';
    public $title;
    public $description;
    public $icon;
    public $num_ap;
    public $enabled = false;

    // class constructor
    public function __construct()
    {
        global $oOrder, $aLang;
        $this->title = $aLang['module_shipping_ap_text_title'];
        $this->description = $aLang['module_shipping_ap_text_description'];
        $this->sort_order = (defined('MODULE_SHIPPING_AP_SORT_ORDER') ? MODULE_SHIPPING_AP_SORT_ORDER : null);
        $this->icon = OOS_ICONS . 'shipping_ap.gif';
        $this->enabled = (defined('MODULE_SHIPPING_AP_STATUS') && (MODULE_SHIPPING_AP_STATUS == 'true') ? true : false);

        if (($this->enabled == true) && isset($oOrder->delivery['country']['id']) && ((int)MODULE_SHIPPING_AP_ZONE > 0)) {
            $check_flag = false;

            // Get database information
            $dbconn = & oosDBGetConn();
            $oostable = & oosDBGetTables();

            $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
            $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_SHIPPING_AP_ZONE . "' AND zone_country_id = '" . intval($oOrder->delivery['country']['id']) . "' ORDER BY zone_id");
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

        // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
        $this->num_ap = 8;
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

        for ($i = 1; $i <= $this->num_ap; $i++) {
            $countries_table = constant('MODULE_SHIPPING_AP_COUNTRIES_' . $i);
            $country_zones = explode("[,]", (string) $countries_table);
            if (in_array($dest_country, $country_zones)) {
                $dest_zone = $i;
                break;
            }
        }

        if ($dest_zone == 0) {
            $error = true;
        } else {
            $shipping = -1;
            $ap_cost = constant('MODULE_SHIPPING_AP_COST_' . $i);

            $ap_table = preg_split("/[:,]/", (string) $ap_cost);
            for ($i = 0; $i < (is_countable($ap_table) ? count($ap_table) : 0); $i += 2) {
                if ($shipping_weight <= $ap_table[$i]) {
                    $shipping = $ap_table[$i + 1];
                    $shipping_method = $aLang['module_shipping_ap_text_way'] . ' ' . $dest_country . ' : ' . $shipping_weight . ' ' . $aLang['module_shipping_ap_text_units'];
                    break;
                }
            }

            if ($shipping == -1) {
                $shipping_cost = 0;
                $shipping_method = $aLang['module_shipping_ap_undefined_rate'];
            } else {
                $shipping_cost = ($shipping + MODULE_SHIPPING_AP_HANDLING);
            }
        }

        $this->quotes = ['id' => $this->code, 'module' => $aLang['module_shipping_ap_text_title'], 'methods' => [['id' => $this->code, 'title' => $shipping_method . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . ' ' . $aLang['module_shipping_ap_text_units'] .')', 'cost' => $shipping_cost * $shipping_num_boxes]]];

        if (oos_is_not_null($this->icon)) {
            $this->quotes['icon'] = oos_image($this->icon, $this->title);
        }

        if ($error == true) {
            $this->quotes['error'] = $aLang['module_shipping_ap_invalid_zone'];
        }

        return $this->quotes;
    }

    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_SHIPPING_AP_STATUS');
        }

        return $this->_check;
    }

    public function install()
    {
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_AP_STATUS', 'true', '6', '0', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_HANDLING', '0', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_SHIPPING_AP_ZONE', '0', '6', '0', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_SORT_ORDER', '0', '6', '0', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COUNTRIES_1', 'DE,IT,SM', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COST_1', '1:12.35,2:13.80,3:15.25,4:16.70,5:18.15,6:19.60,7:21.05,8:22.50,9:23.95,10:25.40,11:26.85,12:28.30,13:29.75,14:31.20,15:32.65,16:34.10,17:35.55,18:37.00,19:38.45,20:39.90', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COUNTRIES_2', 'AD,BE,DK,FO,GL,FI,FR,GR,GB,IE,LI,LU,MC,NL,PT,SE,CH,SK,SI,ES,CZ,HU,VA', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COST_2', '1:13.08,2:15.26,3:17.44,4:19.62,5:21.80,6:23.98,7:26.16,8:28.34,9:30.52,10:32.70,11:34.88,12:37.06,13:39.24,14:41.42,15:43.60,16:45.78,17:47.96,18:50.14,19:52.32,20:54.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COUNTRIES_3', 'EG,AL,DZ,AM,AZ,BA,BG,EE,GE,GI,IS,IL,YU,HR,LV,LB,LY,LT,MT,MA,MK,MD,NO,PL,RO,RU,SY,TN,TR,UA,CY', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COST_3', '1:14.53,2:18.16,3:21.79,4:25.42,5:29.05,6:32.68,7:36.31,8:39.94,9:43.57,10:47.20,11:50.83,12:54.46,13:58.09,14:61.72,15:65.35,16:68.98,17:72.61,18:76.24,19:79.87,20:83.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COUNTRIES_4', 'ET,BH,BJ,BF,CI,DJ,ER,GM,GH,GU,GN,GW,IQ,IR,YE,JO,CM,CA,CV,KZ,QA,KG,KW,LR,ML,MH,MR,FM,NE,NG,MP,OM,PR,SA,SN,SL,SO,SD,TJ,TG,TD,TM,UZ,AE,US,UM,CF', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COST_4', '1:17.44,2:23.98,3:30.52,4:37.06,5:43.60,6:50.14,7:56.68,8:63.22,9:69.76,10:76.30,11:82.84,12:89.38,13:95.92,14:102.46,15:109.00,16:115.54,17:122.08,18:128.62,19:135.16,20:141.70', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COUNTRIES_5', 'AF,AO,AI,AG,GQ,AR,BS,BD,BB,BZ,BM,BT,BO,BW,BR,BN,BI,KY,CL,CN,CR,DM,DO,EC,SV,FK,GF,GA,GD,GP,GT,GY,HT,HN,HK,IN,ID,TP,JM,JP,KH,KE,CO,KM,CG,KP,KR,CU,LA,LS', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COST_5', '1:19.62,2:28.34,3:37.06,4:45.78,5:54.50,6:63.22,7:71.94,8:80.66,9:89.38,10:98.10,11:106.82,12:115.54,13:124.26,14:132.98,15:141.70,16:150.42,17:159.14,18:167.86,19:176.58,20:185.30', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COUNTRIES_6', 'MO,MG,MW,MY,MV,MQ,MU,MX,MN,MS,MZ,MM,NA,NP,NI,AN,AW,PK,PA,PY,PE,PH,RE,RW,ZM,ST,SC,ZW,SG,LK,KN,LC,PM,VC,ZA,SR,SZ,TZ,TH,TT,TC,UG,UY,VE,VN,VG', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COST_6', '1:19.62,2:28.34,3:37.06,4:45.78,5:54.50,6:63.22,7:71.94,8:80.66,9:89.38,10:98.10,11:106.82,12:115.54,13:124.26,14:132.98,15:141.70,16:150.42,17:159.14,18:167.86,19:176.58,20:185.30', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COUNTRIES_7', 'AU,CK,FJ,PF,KI,NR,NC,NZ,PG,PN,SB,TO,TV,VU,WF,WS', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COST_7', '1:23.98,2:37.06,3:50.14,4:63.22,5:76.30,6:89.38,7:102.46,8:115.54,9:128.62,10:141.70,11:154.78,12:167.86,13:180.94,14:194.02,15:207.10,16:220.18,17:233.26,18:246.34,19:259.42,20:272.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COUNTRIES_8', 'AT', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_AP_COST_8', '2:3.56,4:4.36,8:5.45,12:6.90,20:9.08,31.5:12.72', '6', '0', now())");
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
        $keys = ['MODULE_SHIPPING_AP_STATUS', 'MODULE_SHIPPING_AP_HANDLING', 'MODULE_SHIPPING_AP_ZONE', 'MODULE_SHIPPING_AP_SORT_ORDER'];

        for ($i = 1; $i <= $this->num_ap; $i ++) {
            $keys[count($keys)] = 'MODULE_SHIPPING_AP_COUNTRIES_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_AP_COST_' . $i;
        }

        return $keys;
    }
}
