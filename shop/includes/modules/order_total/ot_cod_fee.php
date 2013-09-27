<?php
/* ----------------------------------------------------------------------
   $Id: ot_cod_fee.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

  class ot_cod_fee {
    var $title, $output, $enabled = false;

    function ot_cod_fee() {
      global $aLang;

      $this->code = 'ot_cod_fee';
      $this->title = $aLang['module_order_total_cod_title'];
      $this->description = $aLang['module_order_total_cod_description'];
      $this->enabled = (defined('MODULE_ORDER_TOTAL_COD_STATUS') && (MODULE_ORDER_TOTAL_COD_STATUS == 'true') ? true : false);
      $this->sort_order = (defined('MODULE_ORDER_TOTAL_COD_SORT_ORDER') ? MODULE_ORDER_TOTAL_COD_SORT_ORDER : null);

      $this->output = array();
    }

    function process() {
      global $oOrder, $oCurrencies, $cod_cost, $cod_country;

      if (MODULE_ORDER_TOTAL_COD_STATUS == 'true') {

        //Will become true, if cod can be processed.
        $cod_country = false;

        //check if payment method is cod. If yes, check if cod is possible.
        if ($_SESSION['payment'] == 'cod') {
          //process installed shipping modules
          if ($_SESSION['shipping']['id'] == 'flat_flat') $cod_zones = explode("[:,]", MODULE_ORDER_TOTAL_COD_FEE_FLAT);
          if ($_SESSION['shipping']['id'] == 'item_item') $cod_zones = explode("[:,]", MODULE_ORDER_TOTAL_COD_FEE_ITEM);
          if ($_SESSION['shipping']['id'] == 'table_table') $cod_zones = explode("[:,]", MODULE_ORDER_TOTAL_COD_FEE_TABLE);
          if ($_SESSION['shipping']['id'] == 'zones_zones') $cod_zones = explode("[:,]", MODULE_ORDER_TOTAL_COD_FEE_ZONES);
          if ($_SESSION['shipping']['id'] == 'ap_ap') $cod_zones = explode("[:,]", MODULE_ORDER_TOTAL_COD_FEE_AP);
          if ($_SESSION['shipping']['id'] == 'dp_dp') $cod_zones = explode("[:,]", MODULE_ORDER_TOTAL_COD_FEE_DP);
          if ($_SESSION['shipping']['id'] == 'chp_ECO') $cod_zones = explode("[:,]", MODULE_ORDER_TOTAL_COD_FEE_CHP);
          if ($_SESSION['shipping']['id'] == 'chp_PRI') $cod_zones = explode("[:,]", MODULE_ORDER_TOTAL_COD_FEE_CHP);
          if ($_SESSION['shipping']['id'] == 'chp_URG') $cod_zones = explode("[:,]", MODULE_ORDER_TOTAL_COD_FEE_CHP);

            for ($i = 0; $i < count($cod_zones); $i++) {
            if ($cod_zones[$i] == $oOrder->delivery['country']['iso_code_2']) {
                  $cod_cost = $cod_zones[$i + 1];
                  $cod_country = true;
                  //print('match' . $i . ': ' . $cod_cost);
                  break;
                } elseif ($cod_zones[$i] == '00') {
                  $cod_cost = $cod_zones[$i + 1];
                  $cod_country = true;
                  //print('match' . $i . ': ' . $cod_cost);
                  break;
                } else {
                  //print('no match');
                }
              $i++;
            }
          } else {
            //COD selected, but no shipping module which offers COD
          }
        if ($cod_country) {
          if (MODULE_ORDER_TOTAL_COD_TAX_CLASS > 0) {
            $cod_tax = oos_get_tax_rate(MODULE_ORDER_TOTAL_COD_TAX_CLASS, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
            // $cod_tax_description = oos_get_tax_description(MODULE_ORDER_TOTAL_COD_TAX_CLASS, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
            $cod_tax_description = oos_get_tax_rate(MODULE_ORDER_TOTAL_COD_TAX_CLASS, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);


            $oOrder->info['tax'] += oos_calculate_tax($cod_cost, $cod_tax);
            $oOrder->info['tax_groups']["$cod_tax_description"] += oos_calculate_tax($cod_cost, $cod_tax);
            $oOrder->info['total'] += $cod_cost + oos_calculate_tax($cod_cost, $cod_tax);

            $this->output[] = array('title' => $this->title . ':',
                                    'text' => $oCurrencies->format(oos_add_tax($cod_cost, $cod_tax), true, $oOrder->info['currency'], $oOrder->info['currency_value']),
                                    'value' => oos_add_tax($cod_cost, $cod_tax));
            } else {
              $oOrder->info['total'] += $cod_cost;
	      $this->output[] = array('title' => $this->title . ':',
	                              'text' => $oCurrencies->format($cod_cost, true, $oOrder->info['currency'], $oOrder->info['currency_value']),
                                      'value' => $cod_cost);
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

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_ORDER_TOTAL_COD_STATUS');
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_COD_STATUS', 'MODULE_ORDER_TOTAL_COD_SORT_ORDER', 'MODULE_ORDER_TOTAL_COD_FEE_FLAT', 'MODULE_ORDER_TOTAL_COD_FEE_ITEM', 'MODULE_ORDER_TOTAL_COD_FEE_TABLE', 'MODULE_ORDER_TOTAL_COD_FEE_ZONES', 'MODULE_ORDER_TOTAL_COD_FEE_AP', 'MODULE_ORDER_TOTAL_COD_FEE_DP', 'MODULE_ORDER_TOTAL_COD_TAX_CLASS');
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

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

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
