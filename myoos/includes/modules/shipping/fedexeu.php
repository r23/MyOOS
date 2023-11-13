<?php
/**
   ----------------------------------------------------------------------
   $Id: fedexeu.php,v 1.2 2007/08/11 05:59:52 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: fedexeu.php,v 1.02 2003/02/18 03:37:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Copyright (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Pl�kers
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
class fedexeu
{
    public $code = 'fedexeu';
    public $title;
    public $description;
    public $icon;
    public $num_fedexeu;
    public $types;
    public $enabled = false;

    // class constructor
    public function __construct()
    {
        global $oOrder, $aLang;
        $this->title = $aLang['module_shipping_fedexeu_text_title'];
        $this->description = $aLang['module_shipping_fedexeu_text_description'];
        $this->sort_order = (defined('MODULE_SHIPPING_FEDEXEU_SORT_ORDER') ? MODULE_SHIPPING_FEDEXEU_SORT_ORDER : null);
        $this->icon = OOS_ICONS . 'shipping_fedexeu.gif';
        $this->enabled = (defined('MODULE_SHIPPING_FEDEXEU_STATUS') && (MODULE_SHIPPING_FEDEXEU_STATUS == 'true') ? true : false);

        if (($this->enabled == true) && isset($oOrder->delivery['country']['id']) && ((int)MODULE_SHIPPING_FEDEXEU_ZONE > 0)) {
            $check_flag = false;

            // Get database information
            $dbconn = & oosDBGetConn();
            $oostable = & oosDBGetTables();

            $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
            $check_result = $dbconn->Execute("SELECT zone_id FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . MODULE_SHIPPING_FEDEXEU_ZONE . "' AND zone_country_id = '" . intval($oOrder->delivery['country']['id']) . "' ORDER BY zone_id");
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

        $this->types = ['PAK' => 'FedEx Pak', 'BOX' => 'FedEx Box'];

        // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
        $this->num_fedexeu = 8;
    }

    // class methods
    public function quote($method = '')
    {
        global $oOrder, $aLang, $shipping_weight, $shipping_num_boxes;

        if (!is_object($oOrder)) {
            $dest_country = isset($_SESSION['delivery_zone']) ? oos_prepare_input($_SESSION['delivery_zone']) : STORE_ORIGIN_COUNTRY;
        } else {
            $dest_country = $oOrder->delivery['country']['iso_code_2'];
        }


        $dest_zone = 0;
        $error = false;

        for ($j = 1; $j <= $this->num_fedexeu; $j++) {
            $countries_table = constant('MODULE_SHIPPING_FEDEXEU_COUNTRIES_' . $j);
            $country_zones = preg_split("/[,]/", (string) $countries_table);
            if (in_array($dest_country, $country_zones)) {
                $dest_zone = $j;
                break;
            }
        }

        if ($dest_zone == 0) {
            $error = true;
        } else {
            $shipping = -1;
            $fedexeu_cost_pak = @constant('MODULE_SHIPPING_FEDEXEU_COST_PAK_' . $j);
            $fedexeu_cost_box = @constant('MODULE_SHIPPING_FEDEXEU_COST_BOX_' . $j);

            $methods = [];

            if ($fedexeu_cost_pak != '') {
                $fedexeu_table_pak = preg_split("/[:,]/", (string) $fedexeu_cost_pak);

                for ($i = 0; $i < (is_countable($fedexeu_table_pak) ? count($fedexeu_table_pak) : 0); $i += 2) {
                    if ($shipping_weight <= $fedexeu_table_pak[$i]) {
                        $shipping_pak = $fedexeu_table_pak[$i + 1];
                        break;
                    }
                }

                if ($shipping_pak == -1) {
                    $shipping_cost = 0;
                    $shipping_method = $aLang['module_shipping_fedexeu_undefined_rate'];
                } else {
                    $shipping_cost_1 = ($shipping_pak + MODULE_SHIPPING_FEDEXEU_HANDLING);
                }

                if ($shipping_pak != 0) {
                    $methods[] = ['id' => 'PAK', 'title' => 'FedEx Pak', 'cost' => (MODULE_SHIPPING_FEDEXEU_HANDLING + $shipping_cost_1) * $shipping_num_boxes];
                }
            }

            if ($fedexeu_cost_box != '') {
                $fedexeu_table_box = preg_split("/[:,]/", (string) $fedexeu_cost_box);
                if (($shipping_weight > 10) and ($shipping_weight <= 20)) {
                    $shipping_box = number_format((($shipping_weight - 10) * 2 + 0.5), 0) * constant('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_' .$j) + $fedexeu_table_box[(is_countable($fedexeu_table_box) ? count($fedexeu_table_box) : 0) - 1];
                } elseif (($shipping_weight > 20) and ($shipping_weight <= 40)) {
                    $shipping_box = number_format((($shipping_weight - 20) * 2 + 0.5), 0) * constant('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_' .$j) + 20 * constant('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_' .$j) + $fedexeu_table_box[(is_countable($fedexeu_table_box) ? count($fedexeu_table_box) : 0) - 1];
                } elseif (($shipping_weight > 40) and ($shipping_weight <= 70)) {
                    $shipping_box = number_format((($shipping_weight - 40) * 2 + 0.5), 0) * constant('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_' .$j) + 20 * constant('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_' .$j) + 40 * constant('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_' .$j) + $fedexeu_table_box[(is_countable($fedexeu_table_box) ? count($fedexeu_table_box) : 0) - 1];
                } else {
                    for ($i = 0; $i < (is_countable($fedexeu_table_box) ? count($fedexeu_table_box) : 0); $i += 2) {
                        if ($shipping_weight <= $fedexeu_table_box[$i]) {
                            $shipping_box = $fedexeu_table_box[$i + 1];
                            break;
                        }
                    }
                }

                if ($shipping_box == -1) {
                    $shipping_cost = 0;
                    $shipping_method = $aLang['module_shipping_fedexeu_undefined_rate'];
                } else {
                    $shipping_cost_2 = ($shipping_box + MODULE_SHIPPING_FEDEXEU_HANDLING);
                }

                if ($shipping_box != 0) {
                    $methods[] = ['id' => 'BOX', 'title' => 'FedEx Box', 'cost' => (MODULE_SHIPPING_FEDEXEU_HANDLING + $shipping_cost_2) * $shipping_num_boxes];
                }
            }
        }

        $this->quotes = ['id' => $this->code, 'module' => $this->title . ' (' . $shipping_num_boxes . ' x ' . $shipping_weight . ' ' . $aLang['module_shipping_fedexeu_text_units'] .')'];

        $this->quotes['methods'] = $methods;

        if (oos_is_not_null($this->icon)) {
            $this->quotes['icon'] = oos_image($this->icon, $this->title);
        }

        if ($error == true) {
            $this->quotes['error'] = $aLang['module_shipping_fedexeu_invalid_zone'];
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
            $this->_check = defined('MODULE_SHIPPING_FEDEXEU_STATUS');
        }

        return $this->_check;
    }


    public function install()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_STATUS', 'true', '6', '0', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_HANDLING', '0', '6', '0', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_ZONE', '0', '6', '0', 'oos_cfg_get_zone_class_title', 'oos_cfg_pull_down_zone_classes(', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SORT_ORDER', '0', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COUNTRIES_1', 'AT,AD,BE,DK,DE,FI,FO,FR,GR,GL,GB,IE,IT,LU,MC,NL,PT,SE,SM,ES,VA', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_PAK_1', '0.5:41.40,1:48.20,1.5:51.30,2:54.40,2.5:57.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_BOX_1', '0.5:41.40,1:48.20,1.5:51.30,2:54.40,2.5:57.50,3:60.30,3.5:63.00,4:65.70,4.5:68.50,5:71.20,5.5:75.20,6:77.80,6.5:80.30,7:82.90,7.5:85.50,8:88.10,8.5:90.60,9:93.20,9.5:95.80,10:98.40', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_1', '1.70', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_1', '1.30', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_1', '1.10', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COUNTRIES_2', 'GI,IS,LI,NO,CH', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_PAK_2', '0.5:51.90,1:58.20,1.5:64.40,2:70.70,2.5:77.00', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_BOX_2', '0.5:71.50,1:77.80,1.5:84.20,2:90.40,2.5:96.70,3:103.10,3.5:108.50,4:113.90,4.5:119.40,5:124.80,5.5:129.50,6:134.30,6.5:139.10,7:143.80,7.5:148.50,8:153.30,8.5:158.00,9:162.80,9.5:167.60,10:172.40', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_2', '1.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_2', '1.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_2', '1.60', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COUNTRIES_3', 'AL,BA,BG,EE,HR,LV,LT,MK,MD,PL,RO,RU,SK,SI,CZ,TR,UA,HU,YU,BY', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_PAK_3', '0.5:51.10,1:57.60,1.5:64.20,2:70.70,2.5:77.30', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_BOX_3', '0.5:70.70,1:79.80,1.5:86.20,2:92.70,2.5:99.10,3:104.50,3.5:109.90,4:115.20,4.5:120.60,5:126.00,5.5:130.70,6:135.30,6.5:140.00,7:144.60,7.5:149.20,8:153.90,8.5:158.50,9:163.20,9.5:167.90,10:172.40', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_3', '2.10', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_3', '1.40', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_3', '1.70', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COUNTRIES_4', 'CA,MX,PR,US',  '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_PAK_4', '0.5:50.30,1:58.30,1.5:66.10,2:74.10,2.5:81.90', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_BOX_4', '0.5:70.90,1:78.10,1.5:86.00,2:93.80,2.5:101.70,3:109.50,3.5:117.30,4:125.20,4.5:133.10,5:141.00,5.5:148.80,6:156.70,6.5:164.50,7:172.40,7.5:180.20,8:187.10,8.5:194.90,9:202.80,9.5:210.60,10:218.40', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_4', '4.10', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_4', '3.90', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_4', '3.80', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COUNTRIES_5', 'AU,CN,HK,ID,JP,KR,MO,MY,NZ,PH,SG,TW,TH,VN', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_PAK_5', '0.5:55.80,1:74.60,1.5:93.20,2:111.90,2.5:130.40', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_BOX_5', '0.5:72.90,1:91.50,1.5:110.10,2:128.80,2.5:147.40,3:164.60,3.5:181.70,4:198.80,4.5:216.00,5:233.10,5.5:242.40,6:251.80,6.5:261.10,7:270.40,7.5:279.80,8:289.10,8.5:298.40,9:307.60,9.5:317.00,10:326.40', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_5', '4.30', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_5', '4.30', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_5', '3.70', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COUNTRIES_6', 'BH,BD,BT,BN,KH,CY,EG,IN,IL,YE,JO,QA,KW,LA,LB,MT,MM,NP,OM,PK,SA,LK,SY,AE', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_PAK_6', '0.5:59.60,1:79.00,1.5:96.70,2:114.40,2.5:132.20', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_BOX_6', '0.5:81.20,1:100.50,1.5:118.30,2:136.10,2.5:153.90,3:171.70,3.5:189.50,4:207.30,4.5:225.10,5:242.70,5.5:251.70,6:260.70,6.5:269.70,7:278.50,7.5:287.40,8:296.30,8.5:305.10,9:314.10,9.5:322.90,10:331.90', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_6', '4.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_6', '4.30', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_6', '3.80', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COUNTRIES_7', 'AI,AG,AR,AW,BS,BB,BZ,BM,BO,BR,KY,CL,CR,CO,DM,DO,EC,SV,GF,GD,GP,GT,GY,HT,HN,JM,VG,VI,MQ,MS,NI,AN,PA,PY,PE,KN,LC,VC,ZA,SR,TT,TC,UY,VE', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_PAK_7', '0.5:67.00,1:85.60,1.5:104.30,2:122.90,2.5:114.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_BOX_7', '0.5:84.20,1:102.80,1.5:121.40,2:140.00,2.5:158.70,3:175.70,3.5:192.90,4:210.10,4.5:227.20,5:244.30,5.5:254.40,6:264.50,6.5:274.50,7:284.60,7.5:294.60,8:304.60,8.5:314.70,9:324.80,9.5:334.90,10:344.90', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_7', '4.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_7', '4.30', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_7', '4.30', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COUNTRIES_8', 'DZ,AS,AO,AM,AZ,BJ,BW,BF,BI,CM,CV,TD,CK,CG,DJ,GQ,ET,ER,FJ,FM,PF,GA,GM,GN,GW,GE,GH,GU,KZ,KE,KG,LS,LR,MG,MW,MV,ML,MA,MR,MU,MN,MZ,NA,NC,NE,NG,PW,PG,RE,RW,ZM,ZW,SN,SC,SL,SD,SZ,TZ,TG,TN,TM,UG,UZ,VU,WF', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_PAK_8', '0.5:68.50,1:86.90,1.5:105.50,2:124.00,2.5:142.40', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_COST_BOX_8', '0.5:88.60,1:107.10,1.5:125.60,2:144.10,2.5:162.40,3:179.50,3.5:196.50,4:213.40,4.5:230.50,5:247.40,5.5:257.30,6:267.30,6.5:277.30,7:287.20,7.5:297.20,8:307.20,8.5:317.20,9:327.20,9.5:337.20,10:347.20', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_8', '5.50', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_8', '4.70', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_8', '4.70', '6', '0', now())");
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
        $keys = ['MODULE_SHIPPING_FEDEXEU_STATUS', 'MODULE_SHIPPING_FEDEXEU_HANDLING', 'MODULE_SHIPPING_FEDEXEU_ZONE', 'MODULE_SHIPPING_FEDEXEU_SORT_ORDER'];

        for ($i = 1; $i <= $this->num_fedexeu; $i ++) {
            $keys[count($keys)] = 'MODULE_SHIPPING_FEDEXEU_COUNTRIES_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_FEDEXEU_COST_PAK_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_FEDEXEU_COST_BOX_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_' . $i;
            $keys[count($keys)] = 'MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_' . $i;
        }

        return $keys;
    }
}
