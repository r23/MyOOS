<?php
/**
   ----------------------------------------------------------------------
   $Id: oos_event_specials.php,v 1.1 2007/06/13 15:41:56 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

class oos_event_cart
{
    public $name = PLUGIN_EVENT_CART_NAME;
    public $description = PLUGIN_EVENT_CART_DESC;
    public $uninstallable = true;
    public $depends;
    public $preceeds;
    public $author = 'MyOOS Development Team';
    public $version = '2.0';
    public $requirements = ['oos'         => '1.7.0', 'smarty'      => '2.6.9', 'adodb'       => '4.62', 'php'         => '5.9.0'];


    /**
     *  class constructor
     */
    public function __construct()
    {
    }

    public static function create_plugin_instance()
    {
        if (!is_numeric(AUTOMATICALLY_DELETE_DAY)) {
            return false;
        }

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $sql = "SELECT configuration_value FROM $configurationtable WHERE configuration_key = 'CRON_CART_RUN'";
        $prevent_result = $dbconn->Execute($sql);

        if ($prevent_result->RecordCount() > 0) {
            $prevent = $prevent_result->fields;
            if ($prevent['configuration_value'] == date("Ymd")) {
                // 'Halt! Already executed - should not execute more than once a day.');
                return false;
            } else {
                $configurationtable = $oostable['configuration'];
                $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . date("Ymd") . "' WHERE configuration_key = 'CRON_CART_RUN'");
            }
        } else {
            return false;
        }

        $sd = mktime(0, 0, 0, date("m"), date("d") - AUTOMATICALLY_DELETE_DAY, date("Y"));

        $customers_baskettable = $oostable['customers_basket'];
        $customers_basket_attributestable = $oostable['customers_basket_attributes'];
        $basket_result = $dbconn->Execute("SELECT customers_id FROM $customers_baskettable WHERE customers_basket_date_added <= '" . oos_db_input(date("Ymd", $sd)) . "'");

        if ($basket_result->RecordCount() > 0) {
            while ($basket = $basket_result->fields) {
                // echo $basket['customers_id'];
                // echo '<br>';
                $dbconn->Execute("DELETE FROM $customers_baskettable WHERE customers_id = '" . intval($basket['customers_id']) . "'");
                $dbconn->Execute("DELETE FROM $customers_basket_attributestable WHERE customers_id = '" . intval($basket['customers_id']) . "'");

                // Move that ADOdb pointer!
                $basket_result->MoveNext();
            }
        }
        return true;
    }

    public function install()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $today = date("Y-m-d H:i:s");

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('CRON_CART_RUN', '" . date("Ymd") . "', 6, 4, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AUTOMATICALLY_DELETE_DAY', '230', 6, 5, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");

        return true;
    }

    public function remove()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key = 'CRON_CART_RUN'");
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->config_item()) . "')");

        return true;
    }

    public function config_item()
    {
        return ['AUTOMATICALLY_DELETE_DAY'];
    }
}
