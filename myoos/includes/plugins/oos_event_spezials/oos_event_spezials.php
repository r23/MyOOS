<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_spezials.php,v 1.1 2007/06/13 15:41:56 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2021 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class oos_event_spezials {

    var $name;
    var $description;
    var $uninstallable;
    var $depends;
    var $preceeds;
    var $author;
    var $version;
    var $requirements;


   /**
    *  class constructor
    */
    public function __construct() {

      $this->name          = PLUGIN_EVENT_SPEZIALS_NAME;
      $this->description   = PLUGIN_EVENT_SPEZIALS_DESC;
      $this->uninstallable = true;
      $this->author        = 'MyOOS Development Team';
      $this->version       = '2.0';
      $this->requirements  = array(
                               'oos'         => '1.7.0',
                               'smarty'      => '2.6.9',
                               'adodb'       => '4.62',
                               'php'         => '5.9.0'
      );
    }

    static function create_plugin_instance() {

      include_once MYOOS_INCLUDE_PATH . '/includes/functions/function_spezials.php';
      oos_expire_spezials();

      return true;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $blocktable = $oostable['block'];
      $dbconn->Execute("UPDATE $blocktable
                        SET block_status = 1
                        WHERE block_file = 'specials'");

      $today = date("Y-m-d H:i:s");

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MIN_DISPLAY_NEW_SPEZILAS', '1', 6, 1, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MAX_DISPLAY_NEW_SPEZILAS', '4', 6, 2, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MAX_RANDOM_SELECT_SPECIALS', '10', 6, 3, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MAX_DISPLAY_SPECIAL_PRODUCTS', '9', 6, 4, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");

      return true;
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $blocktable = $oostable['block'];
      $dbconn->Execute("UPDATE $blocktable SET block_status = 0 WHERE block_file = 'specials'");

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->config_item()) . "')");

      return true;
    }

    function config_item() {
      return array('MIN_DISPLAY_NEW_SPEZILAS', 'MAX_DISPLAY_NEW_SPEZILAS', 'MAX_RANDOM_SELECT_SPECIALS', 'MAX_DISPLAY_SPECIAL_PRODUCTS');

    }
  }


