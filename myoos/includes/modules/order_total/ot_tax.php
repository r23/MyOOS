<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_tax.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_tax.php,v 1.14 2003/02/14 05:58:35 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class ot_tax
{
    public $title;
    public $output = [];
    public $enabled = false;

    public function __construct()
    {
        global $aLang;

        $this->code = 'ot_tax';
        $this->title = $aLang['module_order_total_tax_title'];
        $this->description = $aLang['module_order_total_tax_description'];
        $this->enabled = (defined('MODULE_ORDER_TOTAL_TAX_STATUS') && (MODULE_ORDER_TOTAL_TAX_STATUS == 'true') ? true : false);
        $this->sort_order = (defined('MODULE_ORDER_TOTAL_TAX_SORT_ORDER') ? MODULE_ORDER_TOTAL_TAX_SORT_ORDER : null);
    }

    public function process()
    {
        global $oOrder, $oCurrencies, $aUser, $aLang;

        reset($oOrder->info['tax_groups']);
        if ($aUser['price_with_tax'] == 1) {
            $info = $aLang['module_order_total_included_tax'];
        } else {
            $info = $aLang['module_order_total_ex_tax'];
        }

        foreach ($oOrder->info['tax_groups'] as $key => $value) {
            if ($value > 0) {
                $this->output[] = ['title' => $info . $this->title . ' (' . number_format(floatval($key), 2) . '%):', 'text' => $oCurrencies->format($value, true, $oOrder->info['currency'], $oOrder->info['currency_value']), 'info' => '', 'value' => $value];
            }
        }
    }


    public function shopping_cart_process()
    {
        global $oCurrencies, $aUser, $aLang;

        reset($_SESSION['cart']->info['tax_groups']);
        if ($aUser['price_with_tax'] == 1) {
            $info = $aLang['module_order_total_included_tax'];
        } else {
            $info = $aLang['module_order_total_ex_tax'];
        }

        $currency = $_SESSION['currency'];
        $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

        foreach ($_SESSION['cart']->info['tax_groups'] as $key => $value) {
            if ($value > 0) {
                $this->output[] = ['title' => '<small>' . $info . $this->title . ' (' . number_format(floatval($key), 2) . '%):</small>', 'text' => '<small>' . $oCurrencies->format($value, true, $currency, $currency_value) . '</small>', 'info' => '', 'value' => $value];
            }
        }
    }


    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_ORDER_TOTAL_TAX_STATUS');
        }

        return $this->_check;
    }

    public function keys()
    {
        return ['MODULE_ORDER_TOTAL_TAX_STATUS', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER'];
    }

    public function install()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_TAX_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '7', '6', '2', now())");
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
