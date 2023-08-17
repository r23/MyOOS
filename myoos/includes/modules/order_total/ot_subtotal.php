<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_subtotal.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_subtotal.php,v 1.7 2003/02/13 00:12:04 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class ot_subtotal
{
    public $title;
    public $output = [];
    public $enabled = false;

    public function __construct()
    {
        global $aLang;

        $this->code = 'ot_subtotal';
        $this->title = $aLang['module_order_total_subtotal_title'];
        $this->description = $aLang['module_order_total_subtotal_description'];
        $this->enabled = (defined('MODULE_ORDER_TOTAL_SUBTOTAL_STATUS') && (MODULE_ORDER_TOTAL_SUBTOTAL_STATUS == 'true') ? true : false);
        $this->sort_order = (defined('MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER') ? MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER : null);
    }

    public function process()
    {
        global $oOrder, $oCurrencies;

        $this->output[] = array('title' => $this->title . ':',
                            'text' => $oCurrencies->format($oOrder->info['subtotal'], true, $oOrder->info['currency'], $oOrder->info['currency_value']),
                            'info' => '',
                            'value' => $oOrder->info['subtotal']);
    }


    public function shopping_cart_process()
    {
        global $oCurrencies;

        $currency = $_SESSION['currency'];
        $currency_value = $oCurrencies->currencies[$_SESSION['currency']]['value'];

        $this->output[] = array('title' => '<strong>' . $this->title . ':</strong>',
                              'text' => $oCurrencies->format($_SESSION['cart']->info['subtotal'], true, $currency, $currency_value),
                              'info' => '',
                              'value' => $_SESSION['cart']->info['subtotal']);
    }


    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_ORDER_TOTAL_SUBTOTAL_STATUS');
        }

        return $this->_check;
    }

    public function keys()
    {
        return array('MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER');
    }

    public function install()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_SUBTOTAL_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_SUBTOTAL_SORT_ORDER', '1', '6', '2', now())");
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
