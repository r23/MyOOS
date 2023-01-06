<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_total.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File:  ot_total.php,v 1.7 2003/02/13 00:12:04 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */
 
#[AllowDynamicProperties]
class ot_total
{
    public $title;
    public $output;
    public $enabled = false;

    public function __construct()
    {
        global $aLang;

        $this->code = 'ot_total';
        $this->title = $aLang['module_order_total_total_title'];
        $this->description = $aLang['module_order_total_total_description'];
        $this->enabled = (defined('MODULE_ORDER_TOTAL_TOTAL_STATUS') && (MODULE_ORDER_TOTAL_TOTAL_STATUS == 'true') ? true : false);
        $this->sort_order = (defined('MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER') ? MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER : null);

        $this->output = [];
    }

    public function process()
    {
        global $oOrder, $oCurrencies, $aLang;

        $currency = $_SESSION['currency'];
        $info = sprintf($aLang['total_info'], $currency);
        $this->output[] = array('title' => $this->title . ':',
                              'text' => '<strong>' . $oCurrencies->format($oOrder->info['total'], true, $oOrder->info['currency'], $oOrder->info['currency_value']) . '</strong>',
                              'info' => '<small>' . $info . '</small>',
                              'value' => $oOrder->info['total']);
    }

    public function shopping_cart_process()
    {
        global $oCurrencies, $aLang;

        $currency = $_SESSION['currency'];
        $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];
        $info = sprintf($aLang['total_info'], $currency);

        $this->output[] = array('title' => $this->title . ':',
                                'text' => '<strong>' . $oCurrencies->format($_SESSION['cart']->info['total'], true, $currency, $currency_value) . '</strong>',
                                'info' => '<small>' . $info . '</small>',
                                'value' => $_SESSION['cart']->info['total']);
    }



    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_ORDER_TOTAL_TOTAL_STATUS');
        }

        return $this->_check;
    }

    public function keys()
    {
        return array('MODULE_ORDER_TOTAL_TOTAL_STATUS', 'MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER');
    }

    public function install()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_TOTAL_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_TOTAL_SORT_ORDER', '7', '6', '2', now())");
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
