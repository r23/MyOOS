<?php
/* ----------------------------------------------------------------------
   $Id: function_news.php,v 1.1 2007/06/08 14:02:48 r23 Exp $

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
  * Content News
  *
  * @link http://www.oos-shop.de/
  * @package Content News
  * @author r23 <info@r23.de>
  * @copyright 2003 r23
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 14:02:48 $
  */


 /**
  * Return News Path
  *
  * @param $current_news_category_id
  * @param $spacing
  * @param $exclude
  * @param $news_category_tree_array
  * @param include_itself
  */
  function oos_get_news_path($current_news_category_id = '') {
    global $nPath_array;

    if ($current_news_category_id == '') {
      $nPath_new = implode('_', $nPath_array);
    } else {
      if (count($nPath_array) == 0) {
        $nPath_new = $current_news_category_id;
      } else {
        $nPath_new = '';

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $last_news_category_query = "SELECT parent_id
                                     FROM " . $oostable['news_categories'] . "
                                     WHERE news_categories_id = '" . $nPath_array[(count($nPath_array)-1)] . "'";
        $last_news_category_result =& $dbconn->Execute($last_news_category_query);
        $last_news_category = $last_news_category_result->fields;

        // Close result set
        $last_news_category_result->Close();

        $current_news_category_query = "SELECT parent_id
                                        FROM " . $oostable['news_categories'] . "
                                        WHERE news_categories_id = '" . $current_news_category_id . "'";
        $current_news_category_result =& $dbconn->Execute($current_news_category_query);
        $current_news_category = $current_news_category_result->fields;

        // Close result set
        $current_news_category_result->Close();

        if ($last_news_category['parent_id'] == $current_news_category['parent_id']) {
          for ($i = 0, $n = count($nPath_array) - 1; $i < $n; $i++) {
            $nPath_new .= '_' . $nPath_array[$i];
          }
        } else {
          for ($i = 0, $n = count($nPath_array); $i < $n; $i++) {
            $nPath_new .= '_' . $nPath_array[$i];
          }
        }
        $nPath_new .= '_' . $current_news_category_id;
        if (substr($nPath_new, 0, 1) == '_') {
          $nPath_new = substr($nPath_new, 1);
        }
      }
    }

    return 'nPath=' . $nPath_new;
  }

 /**
  * Return News Categories
  *
  * @param $parent_id
  * @param $spacing
  * @param $exclude
  * @param $news_category_tree_array
  * @param include_itself
  * @return array
  */
  function oos_get_news_category_tree($parent_id = '0', $spacing = '', $exclude = '', $news_category_tree_array = '', $include_itself = false) {

    if (!is_array($news_category_tree_array)) $news_category_tree_array = array();
    if ( (count($news_category_tree_array) < 1) && ($exclude != '0') ) $news_category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($include_itself) {
      $news_category_query = "SELECT news_categories_name
                              FROM " . $oostable['news_categories_description'] . "
                              WHERE news_categories_languages_id = '" . intval($_SESSION['language_id']) . "'
                              AND news_categories_id = '" . $parent_id . "'";
      $news_category_result =& $dbconn->Execute($news_category_query);

      $news_category = $news_category_result->fields;
      $news_category_tree_array[] = array('id' => $parent_id, 'text' => $news_category['news_categories_name']);

      // Close result set
      $news_category_result->Close();

    }

    $news_categories_query = "SELECT nc.news_categories_id, ncd.news_categories_name, nc.parent_id
                              FROM " . $oostable['news_categories'] . " nc,
                                   " . $oostable['news_categories_description'] . " ncd
                              WHERE nc.news_categories_id = ncd.news_categories_id
                                AND ncd.news_categories_languages_id = '" . intval($_SESSION['language_id']) . "'
                                AND nc.parent_id = '" . $parent_id . "'
                              ORDER BY nc.sort_order, ncd.news_categories_name";
    $news_categories_result =& $dbconn->Execute($news_categories_query);

    while ($news_categories = $news_categories_result->fields) {
      if ($exclude != $news_categories['news_categories_id']) $news_category_tree_array[] = array('id' => $news_categories['news_categories_id'], 'text' => $spacing . $news_categories['news_categories_name']);
      $news_category_tree_array = oos_get_news_category_tree($news_categories['news_categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $news_category_tree_array);

      // Move that ADOdb pointer!
      $news_categories_result->MoveNext();
    }

    // Close result set
    $news_categories_result->Close();

    return $news_category_tree_array;
  }

 /**
  * Return News Categories Title
  *
  * @param $news_category_id
  * @param $language
  * @return string
  */
  function oos_get_news_categories_title($news_category_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT news_categories_name
              FROM " . $oostable['news_categories_description'] . "
              WHERE news_categories_id = '" . $news_category_id . "'
                AND news_categories_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $news_categories_name = $result->fields['news_categories_name'];

    // Close result set
    $result->Close();

    return $news_categories_name;
  }

 /**
  * Return News 
  *
  * @param $news_id
  * @param $language
  * @return string
  */
  function oos_get_news_description($news_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT news_description
              FROM " . $oostable['news_description'] . "
              WHERE news_id = '" . $news_id . "'
               AND news_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $news_description = $result->fields['news_description'];

    // Close result set
    $result->Close();

    return $news_description;
  }

 /**
  * Return News Url
  *
  * @param $news_id
  * @param $language
  * @return string
  */
  function oos_get_news_url($news_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT news_url
              FROM " . $oostable['news_description'] . "
              WHERE news_id = '" . $news_id . "'
                AND news_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $news_url = $result->fields['news_url'];

    // Close result set
    $result->Close();

    return $news_url;
  }

 /**
  * Return Count News In Category
  *
  * @param $news_categories_id
  * @param $include_deactivated
  * @return string
  */
  function oos_news_in_category_count($news_categories_id, $include_deactivated = false) {

    $news_count = 0;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($include_deactivated) {
      $news_query = "SELECT COUNT(*) AS total
                     FROM " . $oostable['news'] . " n,
                          " . $oostable['news_to_categories'] . " n2c
                     WHERE n.news_id = n2c.news_id
                       AND n2c.news_categories_id = '" . $news_categories_id . "'";
      $news_result =& $dbconn->Execute($news_query);

    } else {
      $news_query = "SELECT COUNT(*) AS total
                     FROM " . $oostable['news'] . " n,
                          " . $oostable['news_to_categories'] . " n2c
                     WHERE n.news_id = n2c.news_id
                       AND n.news_status = '1'
                       AND n2c.news_categories_id = '" . $news_categories_id . "'";
      $news_result =& $dbconn->Execute($news_query);

    }

    $products = $news_result->fields;
    $news_count += $products['total'];

    // Close result set
    $news_result->Close();

    $childs_query = "SELECT news_categories_id
                     FROM " . $oostable['news_categories'] . "
                     WHERE parent_id = '" . $news_categories_id . "'";
    $childs_result =& $dbconn->Execute($childs_query);

    if ($childs_result->RecordCount()) {
      while ($childs = $childs_result->fields) {
        $news_count += oos_news_in_category_count($childs['news_categories_id'], $include_deactivated);

        // Move that ADOdb pointer!
        $childs_result->MoveNext();
      }
    }

    // Close result set
    $childs_result->Close();

    return $news_count;
  }

 /**
  * Return Counter
  *
  * @param $news_categories_id
  * @return string
  */
  function oos_childs_in_news_category_count($news_categories_id) {

    $news_categories_count = 0;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT news_categories_id 
              FROM " . $oostable['news_categories'] . " 
              WHERE parent_id = '" . (int)$news_categories_id . "'";
    $result =& $dbconn->Execute($query);

    while ($news_categories = $result->fields) {
      $news_categories_count++;
      $news_categories_count += oos_childs_in_news_category_count($news_categories['news_categories_id']);

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    // Close result set
    $result->Close();

    return $news_categories_count;
  }

 /**
  * Set News Categories Status
  *
  * @param $news_categories_id
  * @param $status
  */
  function oos_set_news_categories_status($news_categories_id, $status) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($status == '1') {
      $query = "UPDATE " . $oostable['news_categories'] . "
                   SET news_categories_status = '1' 
                WHERE news_categories_id = '" . $news_categories_id . "'";
      $result =& $dbconn->Execute($query);

      // Close result set
      $result->Close();

      return;
    } elseif ($status == '0') {
      $query = "UPDATE " . $oostable['news_categories'] . "
                   SET news_categories_status = '0'
                WHERE news_categories_id = '" . $news_categories_id . "'";
      $result =& $dbconn->Execute($query);

      // Close result set
      $result->Close();

      return;
    } else {
      return false;
    }
  }

 /**
  * Set News Status
  *
  * @param $news_id
  * @param $status
  */
  function oos_set_news_status($news_id, $status) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($status == '1') {
      $query = "UPDATE " . $oostable['news'] . " 
                   SET news_status = '1',
                       news_last_modified = now()
                WHERE news_id = '" . $news_id . "'";
      $result =& $dbconn->Execute($query);

      // Close result set
      $result->Close();

      return;
    } elseif ($status == '0') {
      $query = "UPDATE " . $oostable['news'] . " 
                   SET news_status = '2', 
                       news_last_modified = now()
                WHERE news_id = '" . $news_id . "'";
      $result =& $dbconn->Execute($query);

      // Close result set
      $result->Close();

      return;
    } else {
      return false;
    }
  }

 /**
  * Return News Author
  *
  * @param $author_id
  * @return string
  */
  function oos_get_news_author($author_id) {

    $author = '';

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT admin_firstname, admin_lastname
              FROM " . $oostable['admin'] . "
              WHERE admin_id = '" . $author_id . "'";
    $result =& $dbconn->Execute($query);

    if ($result->RecordCount()) {
      $author = $result->fields['admin_firstname'] . ' ' . $author_result->fields['admin_lastname'];
    }

    // Close result set
    $result->Close();

    return $author;
  }

 /**
  * Return News Title
  *
  * @param $news_id
  * @param $language
  * @return string
  */
  function oos_get_news_title($news_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT news_name 
              FROM " . $oostable['news_description'] . " 
              WHERE news_id = '" . $news_id . "' 
                AND news_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $news_name = $result->fields['news_name'];

    // Close result set
    $result->Close();

    return $news_name;
  }

 /**
  * Return Category Path
  *
  * @param $id
  * @param $from
  * @param $news_categories_array
  * @param $index
  * @return string
  */
  function oos_generate_news_category_path($id, $from = 'news_category', $news_categories_array = '', $index = 0) {

    if (!is_array($news_categories_array)) $news_categories_array = array();

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($from == 'news') {
      $news_categories_query = "SELECT news_categories_id
                                FROM " . $oostable['news_to_categories'] . "
                                WHERE news_id = '" . $id . "'";
      $news_categories_result =& $dbconn->Execute($news_categories_query);

      while ($news_categories = $news_categories_result->fields) {
        if ($news_categories['news_categories_id'] == '0') {
          $news_categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
        } else {
          $news_category_query = "SELECT ncd.news_categories_name, nc.parent_id
                                  FROM " . $oostable['news_categories'] . " nc,
                                       " . $oostable['news_categories_description'] . " ncd
                                 WHERE nc.news_categories_id = '" . $news_categories['news_categories_id'] . "' 
                                   AND nc.news_categories_id = ncd.news_categories_id
                                   AND ncd.news_categories_languages_id = '" . intval($_SESSION['language_id']) . "'";
          $news_category_result =& $dbconn->Execute($news_categories_query);

          $news_category = $news_category_result->fields;
          $news_categories_array[$index][] = array('id' => $news_categories['news_categories_id'], 'text' => $news_category['news_categories_name']);
          if ( (oos_is_not_null($news_category['parent_id'])) && ($news_category['parent_id'] != '0') ) $news_categories_array = oos_generate_news_category_path($news_category['parent_id'], 'news_category', $news_categories_array, $index);
          $news_categories_array[$index] = array_reverse($news_categories_array[$index]);
        }
        $index++;

        // Move that ADOdb pointer!
        $news_categories_result->MoveNext();
      }

      // Close result set
      $news_categories_result->Close();

    } elseif ($from == 'news_category') {
      $news_category_query = "SELECT ncd.news_categories_name, nc.parent_id
                              FROM " . $oostable['news_categories'] . " nc,
                                   " . $oostable['news_categories_description'] . " ncd
                              WHERE nc.news_categories_id = '" . $id . "'
                                AND nc.news_categories_id = ncd.news_categories_id
                                AND ncd.news_categories_languages_id = '" . intval($_SESSION['language_id']) . "'";
      $news_category_result =& $dbconn->Execute($news_category_query);

      $news_category = $news_category_result->fields;

      // Close result set
      $news_category_result->Close();

      $news_categories_array[$index][] = array('id' => $id, 'text' => $news_category['news_categories_name']);
      if ( (oos_is_not_null($news_category['parent_id'])) && ($news_category['parent_id'] != '0') ) $news_categories_array = oos_generate_news_category_path($news_category['parent_id'], 'news_category', $news_categories_array, $index);
    }

    return $news_categories_array;
  }

 /**
  * Return Generated News Category Path
  *
  * @param $id
  * @param $from
  * @return string
  */
  function oos_output_generated_news_category_path($id, $from = 'news_category') {
    $calculated_news_category_path_string = '';
    $calculated_news_category_path = oos_generate_news_category_path($id, $from);
    for ($i = 0, $n = count($calculated_news_category_path); $i < $n; $i++) {
      for ($j = 0, $k = count($calculated_news_category_path[$i]); $j < $k; $j++) {
        $calculated_news_category_path_string .= $calculated_news_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $calculated_news_category_path_string = substr($calculated_news_category_path_string, 0, -16) . '<br />';
    }
    $calculated_news_category_path_string = substr($calculated_news_category_path_string, 0, -4);

    if (strlen($calculated_news_category_path_string) < 1) $calculated_news_category_path_string = TEXT_TOP;

    return $calculated_news_category_path_string;
  }

 /**
  * Remove News Category
  *
  * @param $news_category_id
  */
  function oos_remove_newsCategory($news_category_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $news_category_image_query = "SELECT news_categories_image
                                  FROM " . $oostable['news_categories'] . "
                                  WHERE news_categories_id = '" . oos_db_input($news_category_id) . "'";
    $news_category_image_result =& $dbconn->Execute($news_category_image_query);
    $news_category_image = $news_category_image_result->fields;

    // Close result set
    $news_category_image_result->Close();

    $duplicate_image_query = "SELECT COUNT(*) AS total
                              FROM " . $oostable['news_categories'] . "
                              WHERE news_categories_image = '" . oos_db_input($news_category_image['news_categories_image']) . "'";
    $duplicate_image_result =& $dbconn->Execute($duplicate_image_query);
    $duplicate_image = $duplicate_image_result->fields;

    // Close result set
    $duplicate_image_result->Close();

    if ($duplicate_image['total'] < 2) {
      if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . $news_category_image['news_categories_image'])) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . $news_category_image['news_categories_image']);
      }
    }

    $dbconn->Execute("DELETE FROM " . $oostable['news_categories'] . " WHERE news_categories_id = '" . oos_db_input($news_category_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['news_categories_description'] . " WHERE news_categories_id = '" . oos_db_input($news_category_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['news_to_categories'] . " WHERE news_categories_id = '" . oos_db_input($news_category_id) . "'");
  }

 /**
  * Remove News
  *
  * @param $news_id
  */
  function oos_remove_news($news_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $news_image_query = "SELECT news_image
                         FROM " . $oostable['news'] . "
                         WHERE news_id = '" . oos_db_input($news_id) . "'";
    $news_image_result =& $dbconn->Execute($news_image_query);
    $news_image = $news_image_result->fields;

    // Close result set
    $news_image_result->Close();

    $duplicate_query = "SELECT COUNT(*) AS total
                        FROM " . $oostable['news'] . "
                        WHERE news_image = '" . oos_db_input($news_image['news_image']) . "'";
    $duplicate_result =& $dbconn->Execute($duplicate_query);

    // Close result set
    $duplicate_result->Close();

    if ($duplicate_result->fields['total'] < 2) {
      if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . $news_image['news_image'])) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . $news_image['news_image']);
      }
    }

    $dbconn->Execute("DELETE FROM " . $oostable['news'] . " WHERE news_id = '" . oos_db_input($news_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['news_to_categories'] . " WHERE news_id = '" . oos_db_input($news_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['news_description'] . " WHERE news_id = '" . oos_db_input($news_id) . "'");

    $news_reviews_query = "SELECT news_reviews_id
                           FROM " . $oostable['news_reviews'] . "
                           WHERE news_id = '" . oos_db_input($news_id) . "'";
    $news_reviews_result =& $dbconn->Execute($news_reviews_query);

    while ($news_reviews = $news_reviews_result->fields) {
      $dbconn->Execute("DELETE FROM " . $oostable['news_reviews_description'] . " WHERE news_reviews_id = '" . $news_reviews['news_reviews_id'] . "'");

       // Move that ADOdb pointer!
      $news_reviews_result->MoveNext();
    }
    // Close result set
    $news_reviews_result->Close();

    $dbconn->Execute("DELETE FROM " . $oostable['news_reviews'] . " WHERE news_id = '" . oos_db_input($news_id) . "'");

  }

 /**
  * Return News Category Title
  *
  * @param $news_category_id
  * @param $language
  * @return string
  */
  function oos_get_news_category_heading_title($news_category_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT news_categories_heading_title
              FROM " . $oostable['news_categories_description'] . "
              WHERE news_categories_id = '" . $news_category_id . "'
                AND news_categories_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $news_categories_heading_title = $result->fields['news_categories_heading_title'];

    // Close result set
    $result->Close();

    return $news_categories_heading_title;
  }

 /**
  * Return News Category Description
  *
  * @param $news_category_id
  * @param $language
  * @return string
  */
  function oos_get_news_category_description($news_category_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT news_categories_description
              FROM " . $oostable['news_categories_description'] . "
              WHERE news_categories_id = '" . $news_category_id . "'
                AND news_categories_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $news_categories_description = $result->fields['news_categories_description'];

    // Close result set
    $result->Close();

    return $news_categories_description;
  }

?>