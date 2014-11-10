<?php
/* ----------------------------------------------------------------------
   $Id: function_newsfeed.php,v 1.1 2007/06/08 14:02:48 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

 /**
  * @package Newsfeed
  */

 /**
  * Return Newsfeed Name
  *
  * @param $newsfeed_id
  * @param $language
  * @return string
  */
  function oos_get_newsfeed_name($newsfeed_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT newsfeed_name
              FROM " . $oostable['newsfeed_info'] . "
              WHERE newsfeed_id = '" . $newsfeed_id . "'
                AND newsfeed_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $newsfeed_name = $result->fields['newsfeed_name'];

    // Close result set
    $result->Close();

    return $newsfeed_name;
  }

 /**
  * Return Newsfeed Title
  *
  * @param $newsfeed_id
  * @param $language
  * @return string
  */
  function oos_get_newsfeed_title($newsfeed_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT newsfeed_title
              FROM " . $oostable['newsfeed_info'] . "
              WHERE newsfeed_id = '" . $newsfeed_id . "'
                AND newsfeed_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $newsfeed_title = $result->fields['newsfeed_title'];

    // Close result set
    $result->Close();

    return $newsfeed_title;
  }

 /**
  * Return Newsfeed Description
  *
  * @param $newsfeed_id
  * @param $language
  * @return string
  */
  function oos_get_newsfeed_description($newsfeed_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT newsfeed_description 
              FROM " . $oostable['newsfeed_info'] . " 
              WHERE newsfeed_id = '" . $newsfeed_id . "' 
                AND newsfeed_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $newsfeed_description = $result->fields['newsfeed_description'];

    // Close result set
    $result->Close();

    return $newsfeed_description;
  }
?>