<?php
/* ----------------------------------------------------------------------
   $Id: function_informations.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


  function oos_get_informations_name($informations_id, $lang_id = '') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $information_descriptiontable = $oostable['information_description'];
    $query = "SELECT information_name
              FROM " . $information_descriptiontable . "
              WHERE information_id = '" . intval($informations_id) . "'
                AND information_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $information_name = $result->fields['information_name'];

    // Close result set
    $result->Close();

    return $information_name;
  }


  function oos_get_informations_url($informations_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $information_descriptiontable = $oostable['information_description'];
    $query = "SELECT information_url
              FROM " . $information_descriptiontable . "
              WHERE information_id = '" . intval($informations_id) . "'
                AND information_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $information_url = $result->fields['information_url'];

    // Close result set
    $result->Close();

    return $information_url;
  }


  function oos_get_informations_description($informations_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $information_descriptiontable = $oostable['information_description'];
    $query = "SELECT information_description
              FROM " . $information_descriptiontable . "
              WHERE information_id = '" . intval($informations_id) . "'
                AND information_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $information_description = $result->fields['information_description'];

    // Close result set
    $result->Close();

    return $information_description;
  }


  function oos_get_informations_heading_title($informations_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $information_descriptiontable = $oostable['information_description'];
    $query = "SELECT information_heading_title
              FROM " . $information_descriptiontable . "
              WHERE information_id = '" . intval($informations_id) . "'
                AND information_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $information_heading_title = $result->fields['information_heading_title'];

    // Close result set
    $result->Close();

    return  $information_heading_title;
  }

?>