<?php
/* ----------------------------------------------------------------------
   $Id: function_links.php,v 1.1 2007/06/08 14:02:48 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links.php,v 1.00 2003/10/02
   ----------------------------------------------------------------------
   Links Manager

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

 /**
  * Links Manager
  *
  * @link http://www.oos-shop.de/
  * @package Links Manager
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 14:02:48 $
  */

 /**
  * Return Link Category Name
  *
  * @param $link_category_id
  * @param $language
  * @return string
  */
  function oos_get_link_category_name($link_category_id, $lang_id) {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT link_categories_name
              FROM " . $oostable['link_categories_description'] . "
              WHERE link_categories_id = '" . (int)$link_category_id . "'
                AND link_categories_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $link_categories_name = $result->fields['link_categories_name'];

    // Close result set
    $result->Close();

    return $link_categories_name;
  }

 /**
  * Return Link Category Description
  *
  * @param $link_category_id
  * @param $language
  * @return string
  */
  function oos_get_link_category_description($link_category_id, $lang_id) {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT link_categories_description
              FROM " . $oostable['link_categories_description'] . "
              WHERE link_categories_id = '" . (int)$link_category_id . "'
                AND link_categories_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $link_categories_description = $result->fields['link_categories_description'];

    // Close result set
    $result->Close();

    return $link_categories_description;
  }

 /**
  * Remove Link Category
  *
  * @param $link_category_id
  */
  function oos_remove_linkCategory($link_category_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $image_query = "SELECT link_categories_image
                    FROM " . $oostable['link_categories'] . "
                    WHERE link_categories_id = '" . (int)$link_category_id . "'";
    $image_result =& $dbconn->Execute($image_query);
    $link_category_image = $image_result->fields;

    // Close result set
    $image_result->Close();

    $duplicate_query = "SELECT COUNT(*) as total
                        FROM " . $oostable['link_categories'] . "
                        WHERE link_categories_image = '" . oos_db_input($link_category_image['link_categories_image']) . "'";
    $duplicate_result =& $dbconn->Execute($duplicate_query);

    if ($duplicate_result->fields['total'] < 2) {
      if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . $link_category_image['link_categories_image'])) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . $link_category_image['link_categories_image']);
      }
    }

    // Close result set
    $duplicate_result->Close();

    $dbconn->Execute("DELETE FROM " . $oostable['link_categories'] . " WHERE link_categories_id = '" . (int)$link_category_id . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['link_categories_description'] . " WHERE link_categories_id = '" . (int)$link_category_id . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['links_to_link_categories'] . " WHERE link_categories_id = '" . (int)$link_category_id . "'");
  }

 /**
  * Remove Link Category
  *
  * @param $link_category_id
  */
  function oos_remove_link($link_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $dbconn->Execute("DELETE FROM " . $oostable['links'] . " WHERE links_id = '" . (int)$link_id . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['links_to_link_categories'] . " WHERE links_id = '" . (int)$link_id . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['links_description'] . " WHERE links_id = '" . (int)$link_id . "'");
  }


 /**
  * clone of oos_info_image() sans file_exists (which doesn't work on remote files)
  *
  * @param $image
  * @param $alt
  * @param $width
  * @param $height
  * @return string
  */
  function oos_href_link_admin_info_image($image, $alt, $width = '', $height = '') {
    if (oos_is_not_null($image)) {
      $image = oos_image($image, $alt, $width, $height);
    } else {
      $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
  }

?>