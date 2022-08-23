<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: edit_orders.php,v 1.2 2003/08/08 13:50:00 jwh
   ----------------------------------------------------------------------
   Order Editor

   Written by Jonathan Hilgeman of SiteCreative.com (osc@sitecreative.com)
   Contribution based on:

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

 /**
  * Return the country_id based on the country's name
  *
  * @param  $country_name string
  * @return integer
  */
function oos_get_country_id($country_name)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();
    $query = "SELECT countries_id
              FROM " . $oostable['countries'] . " 
              WHERE countries_name = '" . oos_db_input($country_name) . "'";
    $result = $dbconn->Execute($query);
    if (!$result->RecordCount()) {
        $country_id = 0;
    } else {
        $country_id = $result->fields['countries_id'];
    }

    return $country_id;
}

/**
 * Return the country_iso_code_2 based on the country's id
 *
 * @param  $country_id integer
 * @return string
 */
function oos_get_country_isocode2($country_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();
    $query = "SELECT countries_iso_code_2
              FROM " . $oostable['countries'] . "
              WHERE countries_id = '" . intval($country_id) . "'";
    $result = $dbconn->Execute($query);
    if (!$result->RecordCount()) {
        $country_iso = 0;
    } else {
        $country_iso = $result->fields['countries_iso_code_2'];
    }

    return $country_iso;
}

/**
 * Return the zone_id based on the zone's name
 *
 * @param  $country_id integer
 * @param  $zone_name  string
 * @return integer
 */
function oos_get_zone_id($country_id, $zone_name)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();
    $query = "SELECT zone_id
              FROM " . $oostable['zones'] . "
              WHERE zone_country_id = '" . intval($country_id) . "'
                AND zone_name = '" . oos_db_input($zone_name) . "'";
    $result = $dbconn->Execute($query);
    if (!$result->RecordCount()) {
        $zone_id = 0;
    } else {
        $zone_id = $result->fields['zone_id'];
    }

    return $zone_id;
}

 /**
  * Return result of check the existence of a database field
  *
  * @param  $table string
  * @param  $field string
  * @return boolean
  */
function oos_field_exists($table, $field)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $describe_result = $dbconn->Execute("describe $table");
    while ($d_row = $describe_result->fields) {
        if ($d_row["field"] == "$field") {
            return true;
        }

        // Move that ADOdb pointer!
        $describe_result->MoveNext();
    }

    return false;
}

/**
 * Return string with changed quotes to HTML equivalents for form inputs.
 *
 * @param  $string string
 * @return string
 */
function oos_html_quotes($string)
{
    return str_replace("'", "&#39;", $string);
}

/**
 * Return string with changed HTML equivalents back to quotes
 *
 * @param  $string string
 * @return string
 */
function oos_html_unquote($string)
{
    return str_replace("&#39;", "'", $string);
}
