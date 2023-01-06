<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_loworderfee.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_loworderfee.php,v 1.11 2003/02/14 06:03:32 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class ot_loworderfee
{
    public $title;
    public $output;
    public $enabled = false;

    public function __construct()
    {
        global $aLang;

        $this->code = 'ot_loworderfee';
        $this->title = $aLang['module_order_total_loworderfee_title'];
        $this->description = $aLang['module_order_total_loworderfee_description'];
        $this->enabled = (defined('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS') && (MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS == 'true') ? true : false);
        $this->sort_order = (defined('MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER') ? MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER : null);

        $this->output = [];
    }

    public function process()
    {
        global $oOrder, $oCurrencies;

        if (MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE == 'true') {
            switch (MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION) {
            case 'national':
                if ($oOrder->delivery['country_id'] == STORE_COUNTRY) {
                    $pass = true;
                }
                break;
            case 'international':
                if ($oOrder->delivery['country_id'] != STORE_COUNTRY) {
                    $pass = true;
                }
                break;
            case 'both':
                $pass = true;
                break;
            default:
                $pass = false;
                break;
            }

            if (($pass == true) && (($oOrder->info['total'] - $oOrder->info['shipping_cost']) < MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER)) {
                $tax = oos_get_tax_rate(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
                $tax_description = oos_get_tax_description(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);

                $oOrder->info['tax'] += oos_calculate_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);
                $oOrder->info['tax_groups']["$tax_description"] += oos_calculate_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);
                $oOrder->info['total'] += MODULE_ORDER_TOTAL_LOWORDERFEE_FEE + oos_calculate_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);

                $this->output[] = array('title' => $this->title . ':',
                                'text' => $oCurrencies->format(oos_add_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax), true, $oOrder->info['currency'], $oOrder->info['currency_value']),
                                'info' => '',
                                'value' => oos_add_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax));
            }
        }
    }


    public function shopping_cart_process()
    {
        global $oCurrencies;

        if (MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE == 'true') {
            switch (MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION) {
            case 'national':
                if ($oOrder->delivery['country_id'] == STORE_COUNTRY) {
                    $pass = true;
                }
                break;
            case 'international':
                if ($oOrder->delivery['country_id'] != STORE_COUNTRY) {
                    $pass = true;
                }
                break;
            case 'both':
                $pass = true;
                break;
            default:
                $pass = false;
                break;
            }

            if (($pass == true) && (($_SESSION['cart']->info['total'] - $_SESSION['cart']->info['shipping_cost']) < MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER)) {
                $tax = oos_get_tax_rate(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $oOrder->billing['country']['id'], $oOrder->billing['zone_id']);
                // $tax_description = oos_get_tax_description(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $oOrder->delivery['country']['id'], $oOrder->delivery['zone_id']);
                $tax_description = oos_get_tax_rate(MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS, $oOrder->billing['country']['id'], $oOrder->billing['zone_id']);

                $_SESSION['cart']->info['tax'] += oos_calculate_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);
                $_SESSION['cart']->info['tax_groups']["$tax_description"] += oos_calculate_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);
                $_SESSION['cart']->info['total'] += MODULE_ORDER_TOTAL_LOWORDERFEE_FEE + oos_calculate_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax);

                $currency = $_SESSION['currency'];
                $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

                $this->output[] = array('title' => $this->title . ':',
                                'text' => $oCurrencies->format(oos_add_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax), true, $currency, $currency_value),
                                'info' => '',
                                'value' => oos_add_tax(MODULE_ORDER_TOTAL_LOWORDERFEE_FEE, $tax));
            }
        }
    }

    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS');
        }

        return $this->_check;
    }

    public function keys()
    {
        return array('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS', 'MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER', 'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE', 'MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER', 'MODULE_ORDER_TOTAL_LOWORDERFEE_FEE', 'MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION', 'MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS');
    }

    public function install()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER', '2', '6', '2', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE', 'false', '6', '3', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, date_added) VALUES ('MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER', '50', '6', '4', '$oCurrencies->format', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, date_added) VALUES ('MODULE_ORDER_TOTAL_LOWORDERFEE_FEE', '5', '6', '5', '$oCurrencies->format', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION', 'both', '6', '6', 'oos_cfg_select_option(array(\'national\', \'international\', \'both\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS', '0', '6', '7', 'oos_cfg_get_tax_class_title', 'oos_cfg_pull_down_tax_classes(', now())");
    }

    public function remove()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
}
