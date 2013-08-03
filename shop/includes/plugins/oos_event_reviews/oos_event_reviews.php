<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_reviews.php 470 2013-07-08 12:16:25Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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


  class oos_event_reviews {

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
    function oos_event_reviews() {

      $this->name          = PLUGIN_EVENT_REVIEWS_NAME;
      $this->description   = PLUGIN_EVENT_REVIEWS_DESC;
      $this->uninstallable = true;
      $this->author        = 'OOS Development Team';
      $this->version       = '2.0';
      $this->requirements  = array(
                               'oos'         => '1.7.0',
                               'smarty'      => '2.6.9',
                               'adodb'       => '4.62',
                               'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {

      return true;
    }

    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $blocktable = $oostable['block'];
      $dbconn->Execute("UPDATE $blocktable
                        SET block_status = 1
                        WHERE block_file = 'reviews'");

      $today = date("Y-m-d H:i:s");

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MAX_RANDOM_SELECT_REVIEWS', '10', 6, 1, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('MAX_DISPLAY_NEW_REVIEWS', '6', 6, 2, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('REVIEW_TEXT_MIN_LENGTH', '50', 6, 3, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");

      return true;
    }

    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->config_item()) . "')");

      return true;
    }

    function config_item() {
      return array('MAX_RANDOM_SELECT_REVIEWS', 'MAX_DISPLAY_NEW_REVIEWS', 'REVIEW_TEXT_MIN_LENGTH');

    }
  }


