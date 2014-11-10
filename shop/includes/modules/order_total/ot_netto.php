<?php
/* ----------------------------------------------------------------------
   $Id: ot_netto.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_netto.php,v 1.0.0.0 2004/03/07 19:30:00 Stephan Hilchenbach 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  class ot_netto {
    var $title, $output, $enabled = false;

    function ot_netto() {
      global $aLang;

      $this->code = 'ot_netto';
      $this->title = $aLang['module_order_total_netto_title'];
      $this->description = $aLang['module_order_total_netto_description'];
      $this->enabled = (defined('MODULE_ORDER_TOTAL_NETTO_STATUS') && (MODULE_ORDER_TOTAL_NETTO_STATUS == 'true') ? true : false);
      $this->sort_order = (defined('MODULE_ORDER_TOTAL_NETTO_SORT_ORDER') ? MODULE_ORDER_TOTAL_NETTO_SORT_ORDER : null);

      $this->output = array();
    }

    function process() {
      global $oOrder, $oCurrencies, $aLang;

      $tax_total = 0;

      reset($oOrder->info['tax_groups']);
      while (list($key, $value) = each($oOrder->info['tax_groups'])) {
        // sum all tax values to calculate total tax:
        if ($value > 0) $tax_total += $value;
      }

      // subtract total tax from total invoice amount to calculate net amount:
      $netto = $oOrder->info['total']-$tax_total;

      // output net amount:
      $this->output[] = array('title' => '(' . $this->title . ':',
                        'text' => $oCurrencies->format($netto, true, $oOrder->info['currency'], $oOrder->info['currency_value']) . ')',
                        'value' => $netto);
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_ORDER_TOTAL_NETTO_STATUS');
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_NETTO_STATUS', 'MODULE_ORDER_TOTAL_NETTO_SORT_ORDER');
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_NETTO_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_NETTO_SORT_ORDER', '10', '6', '10', now())");
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
