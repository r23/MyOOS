<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_cod_fee.php,v 1.1 2007/06/07 17:30:50 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_cod_fee.php,v 1.02 2003/02/24 06:05:00 harley_vb
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
class ot_cod_fee
{
    public $title;
    public $output = [];
    public $enabled = false;

    public function __construct()
    {
        global $aLang;

        $this->code = 'ot_cod_fee';
        $this->title = $aLang['module_order_total_cod_title'];
        $this->description = $aLang['module_order_total_cod_description'];
        $this->enabled = (defined('MODULE_ORDER_TOTAL_COD_STATUS') && (MODULE_ORDER_TOTAL_COD_STATUS == 'true') ? true : false);
        $this->sort_order = (defined('MODULE_ORDER_TOTAL_COD_SORT_ORDER') ? MODULE_ORDER_TOTAL_COD_SORT_ORDER : null);
    }

    public function process()
    {
        global $oOrder, $oCurrencies, $cod_cost, $cod_country;

        if (MODULE_ORDER_TOTAL_COD_STATUS == 'true') {

            //Will become true, if cod can be processed.
            $cod_country = false;

            //check if payment method is cod. If yes, check if cod is possible.
            if (isset($_SESSION['payment'])  && ($_SESSION['payment'] == 'cod')) {
                $shipping_array = explode('_', (string) $_SESSION['shipping']['id']);
                $shipping_code = strtoupper(array_shift($shipping_array));
                $shipping_code = 'FEE_' . $shipping_code;
                if (defined('MODULE_ORDER_TOTAL_COD_'. $shipping_code)) {
                    $cod_zones = preg_split("/[:,]/", (string) constant('MODULE_ORDER_TOTAL_COD_'. $shipping_code));

                    for ($i = 0; $i < (is_countable($cod_zones) ? count($cod_zones) : 0); $i++) {
                        if ($cod_zones[$i] == $order->delivery['country']['iso_code_2']) {
                            $cod_cost = $cod_zones[$i + 1];
                            $cod_country = true;
                            break;
                        } elseif ($cod_zones[$i] == '00') {
                            $cod_cost = $cod_zones[$i + 1];
                            $cod_country = true;
                            break;
                        }
                        $i++;
                    }
                }
            }

            if ($cod_country) {
                if (MODULE_ORDER_TOTAL_COD_TAX_CLASS > 0) {
                    $cod_tax = oos_get_tax_rate(MODULE_ORDER_TOTAL_COD_TAX_CLASS, $oOrder->billing['country']['id'], $oOrder->billing['zone_id']);
                    // $cod_tax_description = oos_get_tax_description(MODULE_ORDER_TOTAL_COD_TAX_CLASS, $oOrder->billing['country']['id'], $oOrder->billing['zone_id']);
                    $cod_tax_description = oos_get_tax_rate(MODULE_ORDER_TOTAL_COD_TAX_CLASS, $oOrder->billing['country']['id'], $oOrder->billing['zone_id']);


                    $oOrder->info['tax'] += oos_calculate_tax($cod_cost, $cod_tax);
                    $oOrder->info['tax_groups']["$cod_tax_description"] += oos_calculate_tax($cod_cost, $cod_tax);
                    $oOrder->info['total'] += $cod_cost + oos_calculate_tax($cod_cost, $cod_tax);

                    $this->output[] = ['title' => $this->title . ':', 'text' => $oCurrencies->format(oos_add_tax($cod_cost, $cod_tax), true, $oOrder->info['currency'], $oOrder->info['currency_value']), 'info' => '', 'value' => oos_add_tax($cod_cost, $cod_tax)];
                } else {
                    $oOrder->info['total'] += $cod_cost;
                    $this->output[] = ['title' => $this->title . ':', 'text' => $oCurrencies->format($cod_cost, true, $oOrder->info['currency'], $oOrder->info['currency_value']), 'info' => '', 'value' => $cod_cost];
                }
            } else {
                //Following code should be improved if we can't get the shipping modules disabled, who don't allow COD
                // as well as countries who do not have cod
                //          $this->output[] = array('title' => $this->title . ':',
                //                                  'text' => 'No COD for this module.',
                //                                  'value' => '');
            }
        }
    }


    public function shopping_cart_process()
    {
        global $oOrder, $oCurrencies, $cod_cost, $cod_country;

        if (MODULE_ORDER_TOTAL_COD_STATUS == 'true') {

            //Will become true, if cod can be processed.
            $cod_country = false;


            //check if payment method is cod. If yes, check if cod is possible.
            if (isset($_SESSION['payment'])  && ($_SESSION['payment'] == 'cod')) {
                $shipping_array = explode('_', (string) $_SESSION['shipping']['id']);
                $shipping_code = strtoupper(array_shift($shipping_array));
                $shipping_code = 'FEE_' . $shipping_code;
                if (defined('MODULE_ORDER_TOTAL_COD_'. $shipping_code)) {
                    $cod_zones = preg_split("/[:,]/", (string) constant('MODULE_ORDER_TOTAL_COD_'. $shipping_code));

                    if (!is_object($oOrder)) {
                        $dest_country = isset($_SESSION['delivery_zone']) ? oos_prepare_input($_SESSION['delivery_zone']) : STORE_ORIGIN_COUNTRY;
                    } else {
                        $dest_country = $oOrder->delivery['country']['iso_code_2'];
                    }

                    for ($i = 0; $i < (is_countable($cod_zones) ? count($cod_zones) : 0); $i++) {
                        if ($cod_zones[$i] == $dest_country) {
                            $cod_cost = $cod_zones[$i + 1];
                            $cod_country = true;
                            break;
                        } elseif ($cod_zones[$i] == '00') {
                            $cod_cost = $cod_zones[$i + 1];
                            $cod_country = true;
                            break;
                        }
                        $i++;
                    }
                }
            }


            $currency = $_SESSION['currency'];
            $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

            if ($cod_country) {
                if (MODULE_ORDER_TOTAL_COD_TAX_CLASS > 0) {
                    $cod_tax = oos_get_tax_rate(MODULE_ORDER_TOTAL_COD_TAX_CLASS, $oOrder->billing['country']['id'], $oOrder->billing['zone_id']);
                    // $cod_tax_description = oos_get_tax_description(MODULE_ORDER_TOTAL_COD_TAX_CLASS, $oOrder->billing['country']['id'], $oOrder->billing['zone_id']);
                    $cod_tax_description = oos_get_tax_rate(MODULE_ORDER_TOTAL_COD_TAX_CLASS, $oOrder->billing['country']['id'], $oOrder->billing['zone_id']);


                    $_SESSION['cart']->info['tax'] += oos_calculate_tax($cod_cost, $cod_tax);
                    $_SESSION['cart']->info['tax_groups']["$cod_tax_description"] += oos_calculate_tax($cod_cost, $cod_tax);
                    $_SESSION['cart']->info['total'] += $cod_cost + oos_calculate_tax($cod_cost, $cod_tax);

                    $this->output[] = ['title' => $this->title . ':', 'text' => $oCurrencies->format(oos_add_tax($cod_cost, $cod_tax), true, $currency, $currency_value), 'info' => '', 'value' => oos_add_tax($cod_cost, $cod_tax)];
                } else {
                    $_SESSION['cart']->info['total'] += $cod_cost;
                    $this->output[] = ['title' => $this->title . ':', 'text' => $oCurrencies->format($cod_cost, true, $currency, $currency_value), 'info' => '', 'value' => $cod_cost];
                }
            } else {
                //Following code should be improved if we can't get the shipping modules disabled, who don't allow COD
                // as well as countries who do not have cod
                //          $this->output[] = array('title' => $this->title . ':',
                //                                  'text' => 'No COD for this module.',
                //                                  'value' => '');
            }
        }
    }

    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_ORDER_TOTAL_COD_STATUS');
        }

        return $this->_check;
    }

    public function keys()
    {
        return ['MODULE_ORDER_TOTAL_COD_STATUS', 'MODULE_ORDER_TOTAL_COD_SORT_ORDER', 'MODULE_ORDER_TOTAL_COD_FEE_FLAT', 'MODULE_ORDER_TOTAL_COD_FEE_ITEM', 'MODULE_ORDER_TOTAL_COD_FEE_TABLE', 'MODULE_ORDER_TOTAL_COD_FEE_ZONES', 'MODULE_ORDER_TOTAL_COD_FEE_AP', 'MODULE_ORDER_TOTAL_COD_FEE_DP', 'MODULE_ORDER_TOTAL_COD_TAX_CLASS'];
    }

    public function install()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_STATUS', 'true', '6', '0','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_SORT_ORDER', '6', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_FEE_FLAT', 'AT:3.00,DE:3.58,00:9.99', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_FEE_ITEM', 'AT:3.00,DE:3.58,00:9.99', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_FEE_TABLE', 'AT:3.00,DE:3.58,00:9.99', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_FEE_ZONES', 'CA:4.50,US:3.00,00:9.99', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_FEE_AP', 'AT:3.63,00:9.99', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_FEE_DP', 'DE:3.58,00:9.99', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_FEE_CHP', 'CH:15.00,00:15.00', '6', '0', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_COD_TAX_CLASS', '0', '6', '0', 'oos_cfg_get_tax_class_title', 'oos_cfg_pull_down_tax_classes(', now())");
    }

    public function remove()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
}
