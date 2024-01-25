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

class oos_event_specials
{
    public $name = PLUGIN_EVENT_SPECILAS_NAME;
    public $description = PLUGIN_EVENT_SPECILAS_DESC;
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
        include_once MYOOS_INCLUDE_PATH . '/includes/functions/function_specials.php';
        oos_expire_specials();

        return true;
    }

    public function install()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $blocktable = $oostable['block'];
        $dbconn->Execute(
            "UPDATE $blocktable
                        SET block_status = 1
                        WHERE block_file = 'specials'"
        );

        $today = date("Y-m-d H:i:s");

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MIN_DISPLAY_NEW_SPECILAS', '1', 6, 1, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MAX_DISPLAY_NEW_SPECILAS', '4', 6, 2, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MAX_RANDOM_SELECT_SPECIALS', '10', 6, 3, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MAX_DISPLAY_SPECIAL_PRODUCTS', '9', 6, 4, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");

        return true;
    }

    public function remove()
    {

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        $blocktable = $oostable['block'];
        $dbconn->Execute("UPDATE $blocktable SET block_status = 0 WHERE block_file = 'specials'");

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->config_item()) . "')");

        return true;
    }

    public function config_item()
    {
        return ['MIN_DISPLAY_NEW_SPECILAS', 'MAX_DISPLAY_NEW_SPECILAS', 'MAX_RANDOM_SELECT_SPECIALS', 'MAX_DISPLAY_SPECIAL_PRODUCTS'];
    }
}
