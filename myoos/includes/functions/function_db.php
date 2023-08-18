<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


 /**
  * ADODB Database Abstraction Layer API Helpers
  *
  * @package    database
  * @copyright  (C) 2022 by the MyOOS Development Team.
  * @license    GPL <http://www.gnu.org/licenses/gpl.html>
  * @link       https://www.oos-shop.de
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
function oosDBInit()
{
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
    $dbconn->setConnectionParameter(MYSQLI_SET_CHARSET_NAME, 'utf8mb4');

    if (!$dbconn->Connect($dbhost, $dbuname, $dbpass, $dbname)) {
        $dbpass = "****";
        $dbuname = "****";
        die("$dbtype://$dbuname:$dbpass@$dbhost/$dbname failed to connect " . $dbconn->ErrorMsg());
    }

    // $dbconn->debug = true;

    global $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

    $GLOBALS['oosDB_connections'][0] = $dbconn;
    $GLOBALS['oosDB_tables'] = [];

    $dbconn->Execute("SET NAMES 'utf8mb4'");

    return true;
}

 /**
  * Get a list of database connections
  *
  * @access public
  * @global array xarDB_connections array of database connection objects
  * @return array array of database connection objects
  */
function &oosDBGetConn()
{

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
function &oosDBGetTables()
{
    return $GLOBALS['oosDB_tables'];
}

  /**
   * Import module tables in the array of known tables
   *
   * @access protected
   * @global oostable array
   */
function oosDB_importTables($tables)
{
    // assert('is_array($tables)');
    $GLOBALS['oosDB_tables'] = array_merge($GLOBALS['oosDB_tables'], $tables);
}

function oos_db_input($sStr)
{
    $sStr = (string)$sStr;

    if (function_exists('mysqli::escape_string')) {
        return (new mysqli())->escape_string($sStr);
    }

    return addslashes($sStr);
}

function oos_db_perform($table, $data, $action = 'INSERT', $parameters = '')
{

    // Get database information
    $dbconn =& oosDBGetConn();

    reset($data);
    if ($action == 'INSERT') {
        $query = 'INSERT INTO ' . $table . ' (';
        foreach (array_keys($data) as $columns) {
            $query .= $columns . ', ';
        }
        $query = substr($query, 0, -2) . ') values (';
        reset($data);
        foreach ($data as $value) {
            switch ((string)$value) {
            case 'now()':
                $query .= 'now(), ';
                break;

            case 'null':
                $query .= 'null, ';
                break;

            default:
				$qString = $dbconn->qStr($value);
                $query .= $qString . ', ';
                break;
            }
        }
        $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'UPDATE') {
        $query = 'UPDATE ' . $table . ' set ';
        foreach ($data as $columns => $value) {
            switch ((string)$value) {
            case 'now()':
                $query .= $columns . ' = now(), ';
                break;

            case 'null':
                $query .= $columns .= ' = null, ';
                break;

            default:
				$qString = $dbconn->qStr($value);
                $query .= $columns . ' = ' . $qString . ', ';
                break;
            }
        }
        $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return $dbconn->Execute($query);
}

function oos_db_prepare_input($sStr)
{
    if (is_string($sStr)) {
        return trim((string) stripslashes($sStr));
    } elseif (is_array($sStr)) {
        reset($sStr);
        foreach ($sStr as $key => $value) {
            $sStr[$key] = oos_db_prepare_input($value);
        }
        return $sStr;
    } else {
        return $sStr;
    }
}

function oos_db_output($sStr)
{
    return trim((string) stripslashes((string) $sStr));
}

function dosql($table, $flds)
{
    // Get database information
    $dbconn =& oosDBGetConn();
    $dict = NewDataDictionary($dbconn);

    $taboptarray = ['mysql' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;', 'REPLACE'];

    $sqlarray = $dict->createTableSQL($table, $flds, $taboptarray);
    $dict->executeSqlArray($sqlarray);
}

function idxsql($idxname, $table, $idxflds)
{
    // Get database information
    $dbconn =& oosDBGetConn();
    $dict = NewDataDictionary($dbconn);

    $sqlarray = $dict->CreateIndexSQL($idxname, $table, $idxflds);
    $dict->executeSqlArray($sqlarray);
}
