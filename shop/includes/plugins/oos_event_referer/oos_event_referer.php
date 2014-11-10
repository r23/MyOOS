<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_referer.php,v 1.1 2007/06/12 17:11:55 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: referer.php,v 1.4 2004/08/20 15:50:53 markwest
   ----------------------------------------------------------------------
   This is a new referer function for PostNuke Instead of logging each URL
   as its coming in, it logs the frequency of that URL. This function was written
   first by Michael Yarbrough [gte649i@prism.gatech.edu]. Bjorn Sodergren re-wrote
   it to what it is now and added more complete/descriptive comments.

   modified from Postnuke 0.750 GOLD to OOS 1.5.n by
   r23 [info@r23.de]

   modified from PHP-Nuke 4.4 to Postnuke .6* by
   Timothy Litwiller [timlitw@onemain.com]

   Re-Written by
   Bjorn Sodergren [sweede@gallatinriver.net]

   Originally written by
   Michael Yarbrough [gte649i@prism.gatech.edu]
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );


  class oos_event_referer {

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
    function oos_event_referer() {

      $this->name          = PLUGIN_EVENT_REFERER_NAME;
      $this->description   = PLUGIN_EVENT_REFERER_DESC;
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

     /**
     * Here we set up some variables for the rest of the script.
     * if you want to see whats going on, set $bDebug to 1
     * I use $httphost here because i dont want to deal with the need to have
     * to see if $nuke_url is set correctly and whatnot. if you prefer to use
     * $oos_url isntead of HTTP_HOST, just uncomment the appropriate lines.
     */
      $httpreferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
      $httphost = 'http://' . $_SERVER['HTTP_HOST'];

     /**
     * This is the first thing we need to check. what this does is see if
     * HTTP_HOST is anywhere in HTTP_REFERER. This is so we dont log hits coming 
     * from our own domain.
     */
      if (!ereg("$httphost", $httpreferer)) {
        if ($httpreferer == '' ) {
          $httpreferer = 'Bookmark';
        }

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        // Check to see if the referer is already in DB
        $referertable = $oostable['referer'];
        $check_sql = "SELECT COUNT(referer_id) AS total
                      FROM $referertable
                      WHERE url = '" . oos_db_input($httpreferer) . "'";
        $result = $dbconn->Execute($check_sql);

        $count = $result->fields['total'];

        if ($count == 1) {
          $referertable = $oostable['referer'];
          $update_sql = "UPDATE $referertable
                         SET frequency = frequency + 1
                         WHERE url = '" . oos_db_input($httpreferer) . "'";
        } else {
          $referertable = $oostable['referer'];
          $update_sql = "INSERT INTO $referertable
                         (url,
                          frequency) VALUES('" . oos_db_input($httpreferer) . "',
                                            1)";
        }

        $result = $dbconn->Execute($update_sql);

      }

      return true;
    }


    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $admin_filestable = $oostable['admin_files'];
      $query = "UPDATE $admin_filestable
                SET admin_groups_id = 1
                WHERE admin_files_name = 'stats_referer'";
      $dbconn->Execute($query);

      $table = $oostable['referer'];
      $flds = "
        referer_id I NOTNULL AUTO PRIMARY,
        url C(255) NOTNULL,
        frequency I DEFAULT '0' NOTNULL
      ";
      dosql($table, $flds);

      return true;
    }


    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $admin_filestable = $oostable['admin_files'];
      $query = "UPDATE $admin_filestable
                SET admin_groups_id = 0
                WHERE admin_files_name = 'stats_referer'";
      $dbconn->Execute($query);


      // Drop the table
      $referertable = $oostable['referer'];
      $sql = "DROP TABLE $referertable";
      $result = $dbconn->Execute($sql);

      return true;
    }

    function config_item() {
      return false;
    }
  }

?>
