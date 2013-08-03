<?php
/* ----------------------------------------------------------------------
   $Id: function_db.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: pnAPI.php,v 1.81.2.14 2002/05/17 16:50:12 byronmhome 
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   Based on:
   PHP-NUKE Web Portal System - http://phpnuke.org/
   Thatware - http://thatware.org/
   ----------------------------------------------------------------------

   File: database.php,v 1.21 2002/06/05 11:16:25 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------

   LICENSE

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License (GPL)
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   To read the license please visit http://www.gnu.org/copyleft/gpl.html
   ----------------------------------------------------------------------
   Original Author of file: Jim McDonald
   Purpose of file: The PostNuke API
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


 /**
  * ADODB Database Abstraction Layer API Helpers
  *
  * @package database
  * @copyright (C) 2013 by the MyOOS Development Team.
  * @license GPL <http://www.gnu.org/licenses/gpl.html>
  * @link http://www.oos-shop.de/
  * @subpackage adodb
  */

 /**
  * Initializes the database connection.
  *
  * This function loads up ADODB  and starts the database
  * connection using the required parameters then it sets
  * the table prefixes and xartables up and returns true
  *
  * @access protected
  * @global object db database connection object
  * @global integer ADODB_FETCH_MODE array fectching by associative or numeric keyed arrays
  * @global array oosDB_tables database tables used by MyOOS [Shopsystem]
  * @return bool true on success, false on failure
  */
  function oosDBInit() {
    // Get database parameters
    $dbtype = OOS_DB_TYPE;
    $dbhost = OOS_DB_SERVER;
    $dbname = OOS_DB_DATABASE;

    // Decode encoded DB parameters
    if (OOS_ENCODED == '1') {
      $dbuname = base64_decode(OOS_DB_USERNAME);
      $dbpass = base64_decode(OOS_DB_PASSWORD);
    } else {
      $dbuname = OOS_DB_USERNAME;
      $dbpass = OOS_DB_PASSWORD;
    }

    // Start connection
    global $ADODB_CACHE_DIR;
    $ADODB_CACHE_DIR = oos_get_local_path(OOS_TEMP_PATH . 'adodb_cache/');

    $dbconn = ADONewConnection($dbtype);
    if (!$dbconn->Connect($dbhost, $dbuname, $dbpass, $dbname)) {
      $dbpass = "****";
      $dbuname = "****";
      die("$dbtype://$dbuname:$dbpass@$dbhost/$dbname failed to connect " . $dbconn->ErrorMsg());
    }

    if (function_exists('memcache_pconnect')) {
      $dbconn->memCache = true; // should we use memCache instead of caching in files
      $dbconn->memCacheHost = '126.0.1.1'; // $db->memCacheHost = array($ip1, $ip2, $ip3); // $db->memCacheHost = $ip1; still works
      $dbconn->memCachePort = '11211'; // this is default memCache port
      $dbconn->memCacheCompress = false; // Use 'true' to store the item compressed (uses zlib)
    }


    global $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

    // force oracle to a consistent date format for comparison methods later on
    if (strcmp($dbtype, 'oci8') == 0) {
        $dbconn->Execute("alter session set NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS'");
    }
    //$dbconn->debug = true;
    if (OOS_LOG_SQL == 'true') {
      include_once MYOOS_INCLUDE_PATH . '/includes/lib/adodb/adodb-perf.inc.php';
      adodb_perf::table(ADODB_LOGSQL_TABLE);

      $dbconn->LogSQL();
    }

    $GLOBALS['oosDB_connections'][0] = $dbconn;
    $GLOBALS['oosDB_tables'] = array();

    return true;
  }

 /**
  * Get a list of database connections
  *
  * @access public
  * @global array xarDB_connections array of database connection objects
  * @return array array of database connection objects
  */
  function &oosDBGetConn() {

    // we only want to return the first connection here
    // perhaps we'll add linked list capabilities to this soon
    return $GLOBALS['oosDB_connections'][0];
  }

  /**
   * Get an array of database tables
   *
   * @access public
   * @global array oosDB_tables array of database tables
   * @return array array of database tables
   */
  function &oosDBGetTables() {
     return $GLOBALS['oosDB_tables'];
  }

  /**
   * Import module tables in the array of known tables
   *
   * @access protected
   * @global oostable array
   */
  function oosDB_importTables($tables) {
    assert('is_array($tables)');
    $GLOBALS['oosDB_tables'] = array_merge($GLOBALS['oosDB_tables'], $tables);
  }

  function oos_db_input($sStr) {

    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($sStr);
    }

    return addslashes($sStr);
  }

  function oos_db_perform($table, $data, $action = 'insert', $parameters = '') {

    // Get database information
    $dbconn =& oosDBGetConn();

    reset($data);
    if ($action == 'insert') {
      $query = 'INSERT INTO ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;

          case 'null':
            $query .= 'null, ';
            break;

          default:
            $query .= '\'' . oos_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'UPDATE ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;

          case 'null':
            $query .= $columns .= ' = null, ';
            break;

          default:
            $query .= $columns . ' = \'' . oos_db_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;
    }
    return $dbconn->Execute($query);
  }

  function oos_db_prepare_input($sStr) {
    if (is_string($sStr)) {
      return trim(stripslashes($sStr));
    } elseif (is_array($sStr)) {
      reset($sStr);
      while (list($key, $value) = each($sStr)) {
        $sStr[$key] = oos_db_prepare_input($value);
      }
      return $sStr;
    } else {
      return $sStr;
    }
  }

  function oosDBOutput($sStr) {
    if (get_magic_quotes_gpc()) {
      return mysql_real_escape_string(stripslashes($sStr));
    } else {
      return mysql_real_escape_string($sStr);
    }
  }

  function dosql($table, $flds) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $dict = NewDataDictionary($dbconn);

    $taboptarray = array('mysql' => 'TYPE=MyISAM', 'REPLACE');

    $sqlarray = $dict->CreateTableSQL($table, $flds, $taboptarray);
    $dict->ExecuteSQLArray($sqlarray);

  }

  function idxsql($idxname, $table, $idxflds) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $dict = NewDataDictionary($dbconn);

    $sqlarray = $dict->CreateIndexSQL($idxname, $table, $idxflds);
    $dict->ExecuteSQLArray($sqlarray);
  }

