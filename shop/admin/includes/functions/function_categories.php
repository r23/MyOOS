<?php
/* ----------------------------------------------------------------------
   $Id: function_categories.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.151 2003/02/07 21:46:49 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  function oos_get_path($current_category_id = '') {
    global $cPath_array;

    if ($current_category_id == '') {
      $cPath_new = implode('_', $cPath_array);
    } else {
      if (count($cPath_array) == 0) {
        $cPath_new = $current_category_id;
      } else {
        $cPath_new = '';

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $categoriestable = $oostable['categories'];
        $query = "SELECT parent_id
                  FROM $categoriestable
                  WHERE categories_id = '" . $cPath_array[(count($cPath_array)-1)] . "'";
        $last_category_result = $dbconn->Execute($query);
        $last_category = $last_category_result->fields;

        // Close result set
        $last_category_result->Close();

        $categoriestable = $oostable['categories'];
        $query = "SELECT parent_id
                  FROM $categoriestable
                  WHERE categories_id = '" . $current_category_id . "'";
        $current_category_result = $dbconn->Execute($query);

        $current_category = $current_category_result->fields;
        if ($last_category['parent_id'] == $current_category['parent_id']) {
          for ($i = 0, $n = count($cPath_array) - 1; $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        } else {
          for ($i = 0, $n = count($cPath_array); $i < $n; $i++) {
            $cPath_new .= '_' . $cPath_array[$i];
          }
        }
        $cPath_new .= '_' . $current_category_id;
        if (substr($cPath_new, 0, 1) == '_') {
          $cPath_new = substr($cPath_new, 1);
        }

        // Close result set
        $current_category_result->Close();

      }
    }



    return 'cPath=' . $cPath_new;
  }


  function oos_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $category_tree_array = '', $include_itself = false) {

    if (!is_array($category_tree_array)) $category_tree_array = array();
    if ( (count($category_tree_array) < 1) && ($exclude != '0') ) $category_tree_array[] = array('id' => '0', 'text' => TEXT_TOP);

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($include_itself) {

      $categories_descriptiontable = $oostable['categories_description'];
      $query = "SELECT cd.categories_name
                FROM $categories_descriptiontable cd
                WHERE cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'
                  AND cd.categories_id = '" . intval($parent_id) . "'";
      $category_result = $dbconn->Execute($query);

      $category = $category_result->fields;
      $category_tree_array[] = array('id' => $parent_id, 'text' => $category['categories_name']);

      // Close result set
      $category_result->Close();
    }

    $categoriestable = $oostable['categories'];
    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT c.categories_id, cd.categories_name, c.parent_id
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE c.categories_id = cd.categories_id 
                AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'
                AND c.parent_id = '" . intval($parent_id) . "'
           ORDER BY c.sort_order, cd.categories_name";
    $categories_result = $dbconn->Execute($query);

    while ($categories = $categories_result->fields) {
      if ($exclude != $categories['categories_id']) $category_tree_array[] = array('id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']);
      $category_tree_array = oos_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $category_tree_array);

      // Move that ADOdb pointer!
      $categories_result->MoveNext();
    }

    // Close result set
    $categories_result->Close();

    return $category_tree_array;
  }


  function oos_get_category_name($category_id, $lang_id = '') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_name
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_name = $result->fields['categories_name'];

    // Close result set
    $result->Close();

    return $categories_name;
  }


  function oos_get_products_description($product_id, $lang_id = '') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_description
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $products_description = $result->fields['products_description'];

    // Close result set
    $result->Close();

    return $products_description;
  }

  function oos_get_products_description_meta($product_id, $lang_id = '') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_description_meta
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $products_description_meta = $result->fields['products_description_meta'];

    // Close result set
    $result->Close();

    return $products_description_meta;
  }

  function oos_get_products_keywords_meta($product_id, $lang_id = '') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_keywords_meta
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "' 
                AND products_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $products_keywords_meta = $result->fields['products_keywords_meta'];

    // Close result set
    $result->Close();

    return $products_keywords_meta;
  }

function oos_get_products_short_description($product_id, $lang_id = '') {

	// Get database information
	$dbconn =& oosDBGetConn();
	$oostable =& oosDBGetTables();

	if (!$lang_id) $lang_id = $_SESSION['language_id'];

	$products_descriptiontable = $oostable['products_description'];
	$query = "SELECT products_short_description
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($lang_id) . "'";
	$result = $dbconn->Execute($query);

	$products_short_description = $result->fields['products_short_description'];

	return $products_short_description;
} 
  


  function oos_get_products_url($product_id, $lang_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_url
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $products_url = $result->fields['products_url'];

    // Close result set
    $result->Close();

    return $products_url;
  }


  function oos_products_in_category_count($categories_id, $include_deactivated = false) {

    $products_count = 0;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $products_to_categoriestable = $oostable['products_to_categories'];
    if ($include_deactivated) {
      $products_query = "SELECT COUNT(*) AS total
                         FROM $productstable p,
                              $products_to_categoriestable p2c
                         WHERE p.products_id = p2c.products_id 
                           AND p2c.categories_id = '" . intval($categories_id) . "'";
      $products_result = $dbconn->Execute($products_query);

    } else {
      $products_query = "SELECT COUNT(*) AS total
                         FROM $productstable p,
                              $products_to_categoriestable p2c
                         WHERE p.products_id = p2c.products_id 
                           AND p.products_status >= '1' 
                           AND p2c.categories_id = '" . intval($categories_id) . "'";
      $products_result = $dbconn->Execute($products_query);
    }

    $products = $products_result->fields;

    // Close result set
    $products_result->Close();


    $products_count += $products['total'];

    $categoriestable = $oostable['categories'];
    $childs_query = "SELECT categories_id
                     FROM $categoriestable
                     WHERE parent_id = '" . intval($categories_id) . "'";
    $childs_result = $dbconn->Execute($childs_query);

    if ($childs_result->RecordCount()) {
      while ($childs = $childs_result->fields) {
        $products_count += oos_products_in_category_count($childs['categories_id'], $include_deactivated);

        // Move that ADOdb pointer!
        $childs_result->MoveNext();
      }
    }

    // Close result set
    $childs_result->Close();

    return $products_count;
  }


  function oos_childs_in_category_count($categories_id) {

    $categories_count = 0;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    $query = "SELECT categories_id
              FROM $categoriestable
              WHERE parent_id = '" . (int)$categories_id . "'";
    $result = $dbconn->Execute($query);

    while ($categories = $result->fields) {
      $categories_count++;
      $categories_count += oos_childs_in_category_count($categories['categories_id']);

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    // Close result set
    $result->Close();

    return $categories_count;
  }


  function oos_set_categories_status($categories_id, $status) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    if ($status == '1') {
      $query = "UPDATE $categoriestable
                SET categories_status = '1'
                WHERE categories_id = '" . intval($categories_id) . "'";
      $result = $dbconn->Execute($query);

      // Close result set
      $result->Close();
      return;
    } elseif ($status == '0') {
      $query = "UPDATE $categoriestable
                SET categories_status = '0'
                WHERE categories_id = '" . intval($categories_id) . "'";
      $result = $dbconn->Execute($query);

      // Close result set
      $result->Close();
      return;
    } else {
      return false;
    }

  }


  function oos_set_product_status($products_id, $status) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "UPDATE $productstable
              SET products_status = '" . intval($status) . "',
                  products_last_modified = now()
              WHERE products_id = '" . intval($products_id) . "'";
    $result = $dbconn->Execute($query);

    // Close result set
    $result->Close();

    return;
  }


  function oos_generate_category_path($id, $from = 'category', $categories_array = '', $index = 0) {

    if (!is_array($categories_array)) $categories_array = array();

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_to_categoriestable = $oostable['products_to_categories'];
    $categoriestable             = $oostable['categories'];
    $categories_descriptiontable = $oostable['categories_description'];

    if ($from == 'product') {
      $categories_query = "SELECT categories_id
                           FROM $products_to_categoriestable
                           WHERE products_id = '" . $id . "'";
      $categories_result = $dbconn->Execute($categories_query);

      while ($categories = $categories_result->fields) {
        if ($categories['categories_id'] == '0') {
          $categories_array[$index][] = array('id' => '0', 'text' => TEXT_TOP);
        } else {
          $category_query = "SELECT cd.categories_name, c.parent_id
                             FROM $categoriestable c,
                                  $categories_descriptiontable cd
                             WHERE c.categories_id = '" . $categories['categories_id'] . "'
                               AND c.categories_id = cd.categories_id
                               AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'";
          $category_result = $dbconn->Execute($category_query);

          $category = $category_result->fields;

          // Close result set
          $category_result->Close();

          $categories_array[$index][] = array('id' => $categories['categories_id'], 'text' => $category['categories_name']);
          if ( (oos_is_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = oos_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
          $categories_array[$index] = array_reverse($categories_array[$index]);
        }
        $index++;

        // Move that ADOdb pointer!
        $categories_result->MoveNext();
      }

      // Close result set
      $categories_result->Close();

    } elseif ($from == 'category') {

      $category_query = "SELECT cd.categories_name, c.parent_id
                           FROM $categoriestable c,
                                $categories_descriptiontable cd
                          WHERE c.categories_id = '" . $id . "' 
                            AND c.categories_id = cd.categories_id
                            AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'";
      $category_result = $dbconn->Execute($category_query);

      $category = $category_result->fields;

      // Close result set
      $category_result->Close();

      $categories_array[$index][] = array('id' => $id, 'text' => $category['categories_name']);
      if ( (oos_is_not_null($category['parent_id'])) && ($category['parent_id'] != '0') ) $categories_array = oos_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
    }

    return $categories_array;
  }


  function oos_output_generated_category_path($id, $from = 'category') {
    $calculated_category_path_string = '';
    $calculated_category_path = oos_generate_category_path($id, $from);
    for ($i = 0, $n = count($calculated_category_path); $i < $n; $i++) {
      for ($j = 0, $k = count($calculated_category_path[$i]); $j < $k; $j++) {
        $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
      }
      $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br />';
    }
    $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);

    if (strlen($calculated_category_path_string) < 1) $calculated_category_path_string = TEXT_TOP;

    return $calculated_category_path_string;
  }


  function oos_remove_category($category_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    $category_image_query = "SELECT categories_image
                             FROM $categoriestable
                             WHERE categories_id = '" . oos_db_input($category_id) . "'";
    $category_image_result = $dbconn->Execute($category_image_query);
    $category_image = $category_image_result->fields;

    // Close result set
    $category_image_result->Close();

    $duplicate_image_query = "SELECT COUNT(*) AS total
                              FROM $categoriestable
                              WHERE categories_image = '" . oos_db_input($category_image['categories_image']) . "'";
    $duplicate_image_result = $dbconn->Execute($duplicate_image_query);
    $duplicate_image = $duplicate_image_result->fields;

    if ($duplicate_image['total'] < 2) {
      if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . $category_image['categories_image'])) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . $category_image['categories_image']);
      }
    }

    // Close result set
    $duplicate_image_result->Close();

    $dbconn->Execute("DELETE FROM " . $oostable['categories'] . " WHERE categories_id = '" . oos_db_input($category_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['categories_description'] . " WHERE categories_id = '" . oos_db_input($category_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_to_categories'] . " WHERE categories_id = '" . oos_db_input($category_id) . "'");
  }


/**
 * Code:     categories_description
 * Author:   Brian Lowe <blowe@wpcusrgrp.org>
 * Date:     June 2002
 */
  function oos_get_category_heading_title($category_id, $lang_id = '') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_heading_title
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_heading_title = $result->fields['categories_heading_title'];

    // Close result set
    $result->Close();

    return $categories_heading_title;
  }

  function oos_get_category_description($category_id, $lang_id = '') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_description
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_description = $result->fields['categories_description'];

    // Close result set
    $result->Close();

    return $categories_description;
  }

  function oos_get_category_description_meta($category_id, $lang_id = '') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_description_meta
                FROM $categories_descriptiontable
               WHERE categories_id = '" . intval($category_id) . "'
                 AND categories_languages_id = '" . intval($lang_id). "'";
    $result = $dbconn->Execute($query);

    $categories_description_meta = $result->fields['categories_description_meta'];

    // Close result set
    $result->Close();

    return $categories_description_meta;
  }

  function oos_get_category_keywords_meta($category_id, $lang_id = '') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_keywords_meta
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_keywords_meta = $result->fields['categories_keywords_meta'];

    // Close result set
    $result->Close();

    return $categories_keywords_meta;
  }


  function oos_duplicate_product_image_check($image) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT COUNT(*) AS total
              FROM $productstable
              WHERE  products_image = '" . oos_db_input($image) . "'";
    $result = $dbconn->Execute($query);

    if ($result->fields['total'] == 1) {
      return true;
    } else {
      return false;
    }
  }


  function oos_remove_product_image($image) {
    if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . $image)) {
      @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . $image);
    }
    if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . OOS_POPUP_IMAGES . $image)) {
      @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . OOS_POPUP_IMAGES . $image);
    }
  }


