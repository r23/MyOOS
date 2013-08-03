<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_whos_online.php 470 2013-07-08 12:16:25Z r23 $

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

  class oos_event_whos_online {

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
    function oos_event_whos_online() {

      $this->name          = PLUGIN_EVENT_WHOS_ONLINE_NAME;
      $this->description   = PLUGIN_EVENT_WHOS_ONLINE_DESC;
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

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      if (isset($_SESSION['customer_id'])) {
        $wo_customer_id = $_SESSION['customer_id'];
        $wo_full_name = addslashes($_SESSION['customer_first_name'] . ' ' . $_SESSION['customer_lastname']);
      } else {
        $wo_customer_id = '';
        $wo_full_name = 'Guest';
      }

      $wo_session_id = oos_session_id();
      $wo_ip_address = oos_server_get_remote();
      $wo_last_page_url = addslashes(oos_server_get_var('REQUEST_URI'));

      $current_time = time();
      $xx_mins_ago = ($current_time - 900);

      // remove entries that have expired
      $whos_onlinetable = $oostable['whos_online'];
      $dbconn->Execute("DELETE FROM $whos_onlinetable
                        WHERE time_last_click < '" . oos_db_input($xx_mins_ago) . "'");

      $whos_onlinetable = $oostable['whos_online'];
      $query = "SELECT COUNT(*) AS total
                FROM $whos_onlinetable
                WHERE session_id = '" .  oos_db_input($wo_session_id) . "'";
      $stored_customer = $dbconn->Execute($query);

      if ($stored_customer->fields['total'] > 0) {
        $whos_onlinetable = $oostable['whos_online'];
        $query = "UPDATE $whos_onlinetable"
            . " SET customer_id = ?, full_name = ?, ip_address = ?, time_last_click = ?, last_page_url = ?"
            . " WHERE session_id = ?";
        $result = $dbconn->Execute($query, array((string)$wo_customer_id, (string)$wo_full_name, (string)$wo_ip_address, (string)$current_time, (string)$wo_last_page_url, (string)$wo_session_id));

      } else {
        $whos_onlinetable = $oostable['whos_online'];
        $dbconn->Execute("INSERT INTO " . $whos_onlinetable . "
                     (customer_id,
                      full_name,
                      session_id,
                      ip_address,
                      time_entry,
                      time_last_click,
                      last_page_url) VALUES ('" . oos_db_input($wo_customer_id) . "',
                                             '" . oos_db_input($wo_full_name) . "',
                                             '" . oos_db_input($wo_session_id) . "',
                                             '" . oos_db_input($wo_ip_address) . "',
                                             '" . oos_db_input($current_time) . "',
                                             '" . oos_db_input($current_time) . "',
                                             '" . oos_db_input($wo_last_page_url) . "')");
      }

      return true;
    }

    function install() {
      return true;
    }

    function remove() {
      return true;
    }

    function config_item() {
      return false;
    }
  }


