<?php
/**
   ----------------------------------------------------------------------
   $Id: chp.php,v 1.2 2007/08/11 05:59:52 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: chp.php,v 1.02 2003/02/18 03:37:00 harley_vb
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
class chp
{
    public $code = 'chp';
    public $title;
    public $description;
    public $icon;
    public $num_chp;
    public $types;
    public $enabled = false;

    // class constructor
    public function __construct()
    {
        global $oOrder, $aLang;
        $this->title = $aLang['module_shipping_chp_text_title'];
        $this->description = $aLang['module_shipping_chp_text_description'];
        $this->sort_order = (defined('MODULE_SHIPPING_CHP_SORT_ORDER') ? MODULE_SHIPPING_CHP_SORT_ORDER : null);
        $this->icon = OOS_ICONS . 'shipping_chp.gif';
        $this->enabled = (defined('MODULE_SHIPPING_CHP_STATUS') && (MODULE_SHIPPING_CHP_STATUS == 'true') ? true : false);

        if (($this->enabled == true) && isset($oOrder->delivery['country']['id']) && ((int)MODULE_SHIPPING_CHP_ZONE > 0)) {
            $check_flag = false;

            // Get database information
            $dbconn = & oosDBGetConn();
            $oostable = & oosDBGetTables();

            $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
            $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_SHIPPING_CHP_ZONE . "' AND zone_country_id = '" . intval($oOrder->delivery['country']['id']) . "' ORDER BY zone_id");
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

        $this->types = ['ECO' => 'Economy', 'PRI' => 'Priority', 'URG' => 'Urgent'];

        // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
        $this->num_chp = 7;
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

        for ($j = 1; $j <= $this->num_chp; $j++) {
            $countries_table = constant('MODULE_SHIPPING_CHP_COUNTRIES_' . $j);
            $country_zones = preg_split("/[:,]/", (string) $countries_table);
            if (in_array($dest_country, $country_zones)) {
                $dest_zone = $j;
                break;
            }
        }

        if ($dest_zone == 0) {
            $error = true;
        } else {
            $shipping = -1;
            $chp_cost_eco = @constant('MODULE_SHIPPING_CHP_COST_ECO_' . $j);
            $chp_cost_pri = @constant('MODULE_SHIPPING_CHP_COST_PRI_' . $j);
            $chp_cost_urg = @constant('MODULE_SHIPPING_CHP_COST_URG_' . $j);

            $methods = [];

            if ($chp_cost_eco != '') {
                $chp_table_eco = preg_split("/[:,]/", (string) $chp_cost_eco);

                for ($i = 0; $i < (is_countable($chp_table_eco) ? count($chp_table_eco) : 0); $i += 2) {
                    if ($shipping_weight <= $chp_table_eco[$i]) {
                        $shipping_eco = $chp_table_eco[$i + 1];
                        break;
                    }
                }

                if ($shipping_eco == -1) {
                    $shipping_cost = 0;
                    $shipping_method = $aLang['module_shipping_chp_undefined_rate'];
                } else {
                    $shipping_cost_1 = ($shipping_eco + MODULE_SHIPPING_CHP_HANDLING);
                }

                if ($shipping_eco != 0) {
                    $methods[] = ['id' => 'ECO', 'title' => 'Economy', 'cost' => (MODULE_SHIPPING_CHP_HANDLING + $shipping_cost_1) * $shipping_num_boxes];
                }
            }

            if ($chp_cost_pri != '') {
                $chp_table_pri = preg_split("/[:,]/", (string) $chp_cost_pri);

                for ($i = 0; $i < (is_countable($chp_table_pri) ? count($chp_table_pri) : 0); $i += 2) {
                    if ($shipping_weight <= $chp_table_pri[$i]) {
                        $shipping_pri = $chp_table_pri[$i + 1];
                        break;
                    }
                }

                if ($shipping_pri == -1) {
                    $shipping_cost = 0;
                    $shipping_method = $aLang['module_shipping_chp_undefined_rate'];
                } else {
                    $shipping_cost_2 = ($shipping_pri + MODULE_SHIPPING_CHP_HANDLING);
                }

                if ($shipping_pri != 0) {
                    $methods[] = ['id' => 'PRI', 'title' => 'Priority', 'cost' => (MODULE_SHIPPING_CHP_HANDLING + $shipping_cost_2) * $shipping_num_boxes];
                }
            }

            if ($chp_cost_urg != '') {
                $chp_table_urg = preg_split("/[:,]/", (string) $chp_cost_urg);

                for ($i = 0; $i < (is_countable($chp_table_urg) ? count($chp_table_urg) : 0); $i += 2) {
                    if ($shipping_weight <= $chp_table_urg[$i]) {
                        $shipping_urg = $chp_table_urg[$i + 1];
                        break;
                    }
                }

                if ($shipping_urg == -1) {
                    $shipping_cost = 0;
                    $shipping_method = $aLang['module_shipping_chp_undefined_rate'];
                } else {
                    $shipping_cost_3 = ($shipping_urg + MODULE_SHIPPING_CHP_HANDLING);
                }

                if ($shipping_urg != 0) {
                    $methods[] = ['id' => 'URG', 'title' => 'Urgent', 'cost' => (MODULE_SHIPPING_CHP_HANDLING + $shipping_cost_3) * $shipping_num_boxes];
                }
            }
        }

        $this->quotes = ['id' => $this->code, 'module' => $this->title . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . ' ' . $aLang['module_shipping_chp_text_units'] .')'];
        $this->quotes['methods'] = $methods;


        if (oos_is_not_null($this->icon)) {
            $this->quotes['icon'] = oos_image($this->icon, $this->title);
        }

        if ($error == true) {
            $this->quotes['error'] = $aLang['module_shipping_chp_invalid_zone'];
        }

        if ((oos_is_not_null($method)) && (isset($this->types[$method]))) {
            for ($i = 0; $i < count($methods); $i++) {
                if ($method == $methods[$i]['id']) {
                    $methodsc = [];
                    $methodsc[] = ['id' => $methods[$i]['id'], 'title' => $methods[$i]['title'], 'cost' => $methods[$i]['cost']];
                    break;
                }
            }
            $this->quotes['methods'] = $methodsc;
        }

        return $this->quotes;
    }


    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_SHIPPING_CHP_STATUS');
        }

        return $this->_check;
    }


    public function install()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_CHP_STATUS', 'true', '6', '0', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_HANDLING', '0', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_SHIPPING_CHP_ZONE', '0', '6', '0', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_SORT_ORDER', '0', '6', '0', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COUNTRIES_1', 'CH,LI', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_ECO_1', '2:6.00,5:8.00,10:11.00,20:16.00,30:23.00', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_PRI_1', '2:8.00,5:10.00,10:13.00,20:19.00,30:26.00', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COUNTRIES_2', 'AD,AT,BE,FR,DE,VA,IT,LU,MC,NL', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_ECO_2', '2:29,3:32,4:35,5:38,6:39,7:40,8:41,9:42,10:43,11:44,12:45,13:46,14:47,15:48,16:49,17:50,18:51,19:52,20:53,21:54,22:55,23:56,24:57,25:58,26:59,27:60,28:61,29:62,30:63', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_PRI_2', '2:33,3:37,4:41,5:46,6:49,7:51,8:53,9:55,10:57,11:60,12:61,13:62,14:63,15:64,16:65,17:66,18:67,19:68,20:69,21:70,22:71,23:72,24:73,25:74,26:75,27:76,28:77,29:78,30:79', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_URG_2', '0.5:51,1:57,1.5:62,2:67,2.5:72,3:78,3.5:83,4:89,4.5:95,5:100,6:107,7:115,8:123,9:130,10:138,11:144,12:151,13:158,14:164,15:171,16:177,17:184,18:191,19:197,20:204,21:210,22:217,23:224,24:230,25:237,26:243,27:250,28:256,29:263,30:269', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COUNTRIES_3', 'AL,BA,BG,HR,CZ,DK,EE,FI,GI,GR,HU,IS,IE,LV,LT,MK,MT,MH,NO,PL,PT,RO,SK,SI,ES,SE,GB,YU', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_ECO_3', '2:34,3:38,4:42,5:46,6:49,7:52,8:55,9:58,10:61,11:63,12:65,13:67,14:69,15:71,16:72,17:73,18:74,19:75,20:76,21:77,22:78,23:79,24:80,25:81,26:82,27:83,28:84,29:85,30:86', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_PRI_3', '2:38,3:43,4:48,5:53,6:58,7:62,8:66,9:68,10:71,11:75,12:78,13:81,14:84,15:87,16:90,17:91,18:92,19:93,20:94,21:95,22:96,23:97,24:98,25:99,26:100,27:101,28:102,29:103,30:104', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_URG_3', '0.5:57,1:63,1.5:68,2:73,2.5:78,3:84,3.5:90,4:95,4.5:100,5:106,6:114,7:124,8:132,9:141,10:149,11:158,12:165,13:172,14:180,15:187,16:196,17:203,18:210,19:218,20:226,21:234,22:241,23:248,24:256,25:264,26:272,27:279,28:286,29:295,30:302', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COUNTRIES_4', 'DZ,BY,CA,CY,EG,IL,JO,LB,LY,MD,MA,RU,PM,SY,TN,TR,UA,US', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_ECO_4', '2:38,3:44,4:50,5:56,6:62,7:68,8:74,9:80,10:86,11:92,12:98,13:104,14:110,15:116,16:121,17:126,18:131,19:136,20:141,21:145,22:149,23:153,24:157,25:161,26:165,27:169,28:173,29:177,30:181', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_PRI_4', '2:46,3:55,4:66,5:77,6:86,7:95,8:104,9:113,10:122,11:130,12:138,13:146,14:154,15:162,16:170,17:178,18:186,19:194,20:202,21:209,22:216,23:223,24:230,25:237,26:244,27:251,28:258,29:265,30:272', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_URG_4', '0.5:65,1:72,1.5:79,2:87,2.5:94,3:101,3.5:108,4:115,4.5:123,5:130,6:141,7:152,8:164,9:175,10:186,11:198,12:209,13:220,14:232,15:243,16:254,17:266,18:277,19:288,20:300,21:309,22:318,23:328,24:337,25:346,26:355,27:365,28:374,29:383,30:392', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COUNTRIES_5', 'AF,AO,AI,AG,AM,AZ,BS,BH,BD,BB,BZ,BJ,BM,BT,BW,BF,BI,KY,KH,CM,CV,CF,TD,CN,KM,CG,CR,CI,CU,DJ,DM,DO,SV,GQ,ER,ET,GA,GM,GE,GH,GD,GP,GT,GN,GW,HT,HN,HK,IN,IR,IQ,JM,JP,YE,KZ,KE,KP,KR,KW,KG,LA,LS', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_ECO_5', '2:43,3:50,4:57,5:64,6:71,7:78,8:85,9:92,10:99,11:104,12:109,13:114,14:119,15:124,16:129,17:134,18:139,19:144,20:149,21:153,22:157,23:161,24:165,25:169,26:173,27:177,28:181,29:185,30:189', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_PRI_5', '2:53,3:67,4:80,5:94,6:107,7:120,8:133,9:146,10:159,11:167,12:177,13:187,14:197,15:207,16:215,17:223,18:231,19:239,20:247,21:255,22:263,23:271,24:279,25:287,26:295,27:303,28:311,29:319,30:327', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_URG_5', '0.5:83,1:96,1.5:108,2:121,2.5:133,3:145,3.5:158,4:170,4.5:182,5:195,6:214,7:234,8:253,9:273,10:293,11:311,12:330,13:348,14:367,15:385,16:404,17:422,18:441,19:459,20:478,21:495,22:513,23:530,24:548,25:565,26:583,27:600,28:618,29:636,30:653', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COUNTRIES_6', 'LR,MO,MG,MW,MY,MV,ML,MQ,MR,MU,YT,MX,MN,MS,MZ,MM,NA,NP,NI,NE,NG,OM,PK,PA,QA,RE,RW,KN,LC,VC,SH,ZM,SM,ST,SA,SN,SC,SL,SG,SO,ZA,LK,SD,SZ,TW,TJ,TZ,TH,TG,TM,TC,UG,AE,UZ,VN,VG,VI,ZW', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_ECO_6', '2:43,3:50,4:57,5:64,6:71,7:78,8:85,9:92,10:99,11:104,12:109,13:114,14:119,15:124,16:129,17:134,18:139,19:144,20:149,21:153,22:157,23:161,24:165,25:169,26:173,27:177,28:181,29:185,30:189', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_PRI_6', '2:53,3:67,4:80,5:94,6:107,7:120,8:133,9:146,10:159,11:167,12:177,13:187,14:197,15:207,16:215,17:223,18:231,19:239,20:247,21:255,22:263,23:271,24:279,25:287,26:295,27:303,28:311,29:319,30:327', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_URG_6', '0.5:83,1:96,1.5:108,2:121,2.5:133,3:145,3.5:158,4:170,4.5:182,5:195,6:214,7:234,8:253,9:273,10:293,11:311,12:330,13:348,14:367,15:385,16:404,17:422,18:441,19:459,20:478,21:495,22:513,23:530,24:548,25:565,26:583,27:600,28:618,29:636,30:653', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COUNTRIES_7', 'AR,AW,AU,BO,BR,BN,CL,CO,CK,EC,FK,FJ,GF,PF,GY,ID,KI,NR,AN,NC,NZ,NF,PG,PY,PE,PH,PN,WS,SB,SR,TP,TO,TT,TV,UY,VU,VE,WF', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_ECO_7', '2:47,3:55,4:63,5:71,6:79,7:87,8:95,9:103,10:111,11:118,12:125,13:132,14:139,15:146,16:152,17:160,18:166,19:172,20:178,21:184,22:190,23:196,24:202,25:206,26:211,27:216,28:221,29:226,30:231', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_PRI_7', '2:65,3:83,4:101,5:119,6:136,7:153,8:170,9:187,10:204,11:219,12:234,13:249,14:264,15:279,16:294,17:309,18:324,19:339,20:354,21:367,22:380,23:393,24:406,25:419,26:432,27:445,28:458,29:471,30:484', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_CHP_COST_URG_7', '0.5:92,1:106,1.5:121,2:135,2.5:149,3:164,3.5:178,4:193,4.5:207,5:221,6:241,7:261,8:280,9:300,10:319,11:338,12:356,13:375,14:393,15:412,16:431,17:449,18:468,19:486,20:505,21:522,22:540,23:557,24:575,25:592,26:610,27:627,28:645,29:662,30:680', '6', '0', now())");
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
        $keys = ['MODULE_SHIPPING_CHP_STATUS', 'MODULE_SHIPPING_CHP_HANDLING', 'MODULE_SHIPPING_CHP_ZONE', 'MODULE_SHIPPING_CHP_SORT_ORDER'];

        for ($i = 1; $i <= $this->num_chp; $i++) {
            $keys[count($keys)] = 'MODULE_SHIPPING_CHP_COUNTRIES_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_CHP_COST_ECO_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_CHP_COST_PRI_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_CHP_COST_URG_' . $i;
        }

        return $keys;
    }
}
