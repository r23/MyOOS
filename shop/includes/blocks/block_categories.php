<?php
/* ----------------------------------------------------------------------
   $Id: block_categories.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.23 2002/11/12 14:09:30 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

 /**
  * Return the number of products in a category
  *
  * @param $category_id
  * @param $include_inactive
  * @return string
  */
  function oos_count_products_in_category($category_id, $include_inactive = false) {

    $products_count = 0;

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $products_to_categoriestable = $oostable['products_to_categories'];

    if ($include_inactive == true) {
      $products = $dbconn->Execute("SELECT COUNT(*) AS total FROM $productstable p, $products_to_categoriestable p2c WHERE p.products_id = p2c.products_id AND p2c.categories_id = '" . intval($category_id) . "'");
    } else {
      $products = $dbconn->Execute("SELECT COUNT(*) AS total FROM $productstable p, $products_to_categoriestable p2c WHERE p.products_id = p2c.products_id AND p.products_status >= '1' AND p2c.categories_id = '" . intval($category_id) . "'");
    }
    $products_count += $products->fields['total'];

    $nGroupID = intval($_SESSION['member']->group['id']);
    $categoriestable = $oostable['categories'];
    $child_categories_result = $dbconn->Execute("SELECT categories_id FROM $categoriestable WHERE ( access = '0' OR access = '" . intval($nGroupID) . "' ) AND parent_id = '" . intval($category_id) . "'");
    if ($child_categories_result->RecordCount()) {
      while ($child_categories = $child_categories_result->fields) {
        $products_count += oos_count_products_in_category($child_categories['categories_id'], $include_inactive);

        // Move that ADOdb pointer!
        $child_categories_result->MoveNext();
      }

      // Close result set
      $child_categories_result->Close();
    }

    return $products_count;
  }


 /**
  * Return Show Category
  *
  * @param $counter
  * @return string
  */
  function oos_show_category($counter) {
    global $foo, $aCategories, $category_new, $id, $parent_child;#vx

    $aCategory = array('counter' => $counter);

    if ( (isset($id)) && (in_array($counter, $id)) ) {
      $aCategory['isSelected'] = 1;
    } else {
      $aCategory['isSelected'] = 0;
    }

    if ( (isset($parent_child)) && (is_array($parent_child)) ) {
      foreach ($parent_child as $index_of => $sub_parent_child) {
        if ($counter == $sub_parent_child['parent_id']) {
          $aCategory['isHasSubCategories'] = 1;
          break;
        } else {
          $aCategory['isHasSubCategories'] = 0;
        }
      }
    }

    if (SHOW_COUNTS == 'true') {
      $products_in_category = oos_count_products_in_category($counter);
      $aCategory['countProductsInCategory'] = $products_in_category;
    }

    if ( (isset($foo)) && (is_array($foo)) ) {
      $aCategory = array_merge($aCategory, $foo[$counter]);
    }

    $aCategories[] = $aCategory;

    if ($foo[$counter]['next_id']) {
      oos_show_category($foo[$counter]['next_id']);
    }
  }

    // Normal Categories Display list
    $nGroupID = intval($_SESSION['member']->group['id']);
	$list_of_categories_ids = array();
	$aCategories = array();
	
    $categoriestable = $oostable['categories'];
    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT c.categories_id, cd.categories_name, c.parent_id, c.categories_status
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE c.categories_status = '1'
                AND c.parent_id = '0'
                AND ( c.access = '0' OR c.access = '" . intval($nGroupID) . "' )
                AND c.categories_id = cd.categories_id
                AND cd.categories_languages_id = '" . intval($nLanguageID) . "'
              ORDER BY c.sort_order, cd.categories_name";
    $categories_result = $dbconn->Execute($query);
    while ($categories = $categories_result->fields) {
      $list_of_categories_ids[] = intval($categories['categories_id']);#vx
      $foo[$categories['categories_id']] = array('name' => $categories['categories_name'],
                                                 'parent' => $categories['parent_id'],
                                                 'level' => 0,
                                                 'path' => $categories['categories_id'],
                                                 'next_id' => false);

      if (isset($prev_id)) {
        $foo[$prev_id]['next_id'] = $categories['categories_id'];
      }

      $prev_id = $categories['categories_id'];

      if (!isset($first_element)) {
        $first_element = $categories['categories_id'];
      }

      // Move that ADOdb pointer!
      $categories_result->MoveNext();
    }
    // Close result set
    $categories_result->Close();

    if (oos_is_not_null($category)) {
      $new_path = '';
      $id = explode('_', $category);
      reset($id);
      while (list($key, $value) = each($id)) {
        unset($prev_id);
        unset($first_id);

        $nGroupID = intval($_SESSION['member']->group['id']);

        $categoriestable = $oostable['categories'];
        $categories_descriptiontable = $oostable['categories_description'];
        $query = "SELECT c.categories_id, cd.categories_name, c.parent_id, c.categories_status
                  FROM $categoriestable c,
                       $categories_descriptiontable cd
                  WHERE c.categories_status = '1'
                    AND c.parent_id = '" . intval($value) . "'
                    AND ( c.access = '0' OR c.access = '" . intval($nGroupID) . "' )
                    AND c.categories_id = cd.categories_id
                    AND cd.categories_languages_id = '" . intval($nLanguageID) . "'
                  ORDER BY c.sort_order, cd.categories_name";
        $categories_result = $dbconn->Execute($query);
        $category_check = $categories_result->RecordCount();
        if ($category_check > 0) {
          $new_path .= $value;
          while ($row = $categories_result->fields) {
            $list_of_categories_ids[] = intval($row['categories_id']);#vx
            $foo[$row['categories_id']] = array('name' => $row['categories_name'],
                                                'parent' => $row['parent_id'],
                                                'level' => $key+1,
                                                'path' => $new_path . '_' . $row['categories_id'],
                                                'next_id' => false);

            if (isset($prev_id)) {
              $foo[$prev_id]['next_id'] = $row['categories_id'];
            }

            $prev_id = $row['categories_id'];

            if (!isset($first_id)) {
              $first_id = $row['categories_id'];
            }

            $last_id = $row['categories_id'];

            // Move that ADOdb pointer!
            $categories_result->MoveNext();
          }
          // Close result set
          $categories_result->Close();

          $foo[$last_id]['next_id'] = $foo[$value]['next_id'];
          $foo[$value]['next_id'] = $first_id;
          $new_path .= '_';
        } else {
          break;
        }
      }
    }
    if (sizeof($list_of_categories_ids) > 0 ) {#vx
      $select_list_of_cat_ids = implode(",", $list_of_categories_ids);

      $nGroupID = intval($_SESSION['member']->group['id']);

      $categoriestable = $oostable['categories'];
      $query = "SELECT categories_id, parent_id
                FROM $categoriestable
                WHERE ( access = '0' OR access = '" . intval($nGroupID) . "' )
                AND   parent_id in (" . $select_list_of_cat_ids . ")";
      $parent_child_result = $dbconn->Execute($query);

      while ($_parent_child = $parent_child_result->fields) {
        $parent_child[] = $_parent_child;

         // Move that ADOdb pointer!
        $parent_child_result->MoveNext();
      }
      // Close result set
      $parent_child_result->Close();
    }
    if (isset($first_element)) {
      oos_show_category($first_element);
    }


  $smarty->assign(
      array(
          'block_heading_categories' => $block_heading,
          'categories_contents' => $aCategories
      )
  );

