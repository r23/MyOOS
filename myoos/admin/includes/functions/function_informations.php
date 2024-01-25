<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


function oos_get_informations_name($informations_id, $language_id = '')
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    $information_descriptiontable = $oostable['information_description'];
    $query = "SELECT information_name
              FROM " . $information_descriptiontable . "
              WHERE information_id = '" . intval($informations_id) . "'
                AND information_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $information_name = $result->fields['information_name'] ?? '';


    return $information_name;
}



function oos_get_informations_description($informations_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $information_descriptiontable = $oostable['information_description'];
    $query = "SELECT information_description
              FROM " . $information_descriptiontable . "
              WHERE information_id = '" . intval($informations_id) . "'
                AND information_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $information_description = $result->fields['information_description'] ?? '';

    return $information_description;
}


function oos_get_informations_heading_title($informations_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $information_descriptiontable = $oostable['information_description'];
    $query = "SELECT information_heading_title
              FROM " . $information_descriptiontable . "
              WHERE information_id = '" . intval($informations_id) . "'
                AND information_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $information_heading_title = $result->fields['information_heading_title'] ?? '';

    return  $information_heading_title;
}
