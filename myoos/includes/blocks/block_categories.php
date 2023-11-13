<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.23 2002/11/12 14:09:30 dgw_
   ----------------------------------------------------------------------
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
 * Return the number of products in a category
 *
 * @param  $category_id
 * @param  $include_inactive
 * @return string
 */
function oos_count_products_in_category($category_id, $include_inactive = false)
{
    $products_count = 0;

    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $productstable = $oostable['products'];
    $products_to_categoriestable = $oostable['products_to_categories'];

    if ($include_inactive == true) {
        $products = $dbconn->Execute("SELECT COUNT(*) AS total FROM $productstable p, $products_to_categoriestable p2c WHERE p.products_id = p2c.products_id AND p2c.categories_id = '" . intval($category_id) . "'");
    } else {
        $products = $dbconn->Execute("SELECT COUNT(*) AS total FROM $productstable p, $products_to_categoriestable p2c WHERE p.products_id = p2c.products_id AND p.products_setting = '2' AND p2c.categories_id = '" . intval($category_id) . "'");
    }
    $products_count += $products->fields['total'];

    $categoriestable = $oostable['categories'];
    $child_categories_result = $dbconn->Execute("SELECT categories_id FROM $categoriestable WHERE parent_id = '" . intval($category_id) . "'");
    if ($child_categories_result->RecordCount()) {
        while ($child_categories = $child_categories_result->fields) {
            $products_count += oos_count_products_in_category($child_categories['categories_id'], $include_inactive);

            // Move that ADOdb pointer!
            $child_categories_result->MoveNext();
        }
    }

    return $products_count;
}


/**
 * Return true if the category has subcategories
 *
 * @param  $category_id
 * @return boolean
 */
function oos_has_category_subcategories($category_id)
{
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $categoriestable = $oostable['categories'];
    $query = "SELECT COUNT(*) AS total
              FROM $categoriestable
              WHERE parent_id = '" . intval($category_id) . "'";
    $child_category = $dbconn->Execute($query);
    if ($child_category->fields['total'] > 0) {
        return true;
    } else {
        return false;
    }
}

/**
 * Return Show Category
 *
 * @param  $nCounter
 * @return string
 */
function oos_show_category($nCounter)
{
    global $nPrevID, $aFoo, $aCategories, $sCategory_new, $id, $parent_child, $nCurrentCategoryID;

    $aCategory = ['counter' => $nCounter];

    if ((isset($id)) && (in_array($nCounter, $id))) {
        $aCategory['isSelected'] = 1;
    } else {
        $aCategory['isSelected'] = 0;
    }

    if ($nCounter == $nCurrentCategoryID) {
        $aCategory['isActive'] = 1;
    } else {
        $aCategory['isActive'] = 0;
    }

    if ((isset($parent_child)) && (is_array($parent_child))) {
        foreach ($parent_child as $index_of => $sub_parent_child) {
            if ($nCounter == $sub_parent_child['parent_id']) {
                $aCategory['isHasSubCategories'] = 1;
                break;
            } else {
                $aCategory['isHasSubCategories'] = 0;
            }
        }
    }

    if (SHOW_COUNTS == 'true') {
        $products_in_category = oos_count_products_in_category($nCounter);
        $aCategory['countProductsInCategory'] = $products_in_category;
    }

    if ((isset($aFoo)) && (is_array($aFoo))) {
        if (!isset($nPrevID)) {
            $nPrevID = $nCounter;
        }

        if ($aFoo[$nPrevID]['level'] < $aFoo[$nCounter]['level']) {
            $aCategory['isGroupStart'] = 1;
            $nPrevID = $nCounter;
        } else {
            $aCategory['isGroupStart'] = 0;
        }

        if ($aFoo[$nCounter]['next_id']) {
            $nNextID = $aFoo[$nCounter]['next_id'];

            if ($aFoo[$nCounter]['level'] < $aFoo[$nNextID]['level']) {
                $aCategory['isHasSubElements'] = 1;
            } else {
                $aCategory['isHasSubElements'] = 0;
            }

            if ($aFoo[$nNextID]['level'] < $aFoo[$nCounter]['level']) {
                $nElem = $aFoo[$nCounter]['level'] - $aFoo[$nNextID]['level'] ;
                $aCategory['nElements'] = $nElem;
            } else {
                $aCategory['nElements'] = 0;
            }
        }

        if ($aFoo[$nNextID]['level'] < $aFoo[$nCounter]['level']) {
            $aCategory['isGroupEnd'] = 1;
        } else {
            $aCategory['isGroupEnd'] = 0;
        }


        $aCategory = array_merge($aCategory, $aFoo[$nCounter]);
    }

    $aCategories[] = $aCategory;

    if ($aFoo[$nCounter]['next_id']) {
        oos_show_category($aFoo[$nCounter]['next_id']);
    }
}

$categoriestable = $oostable['categories'];
$categories_descriptiontable = $oostable['categories_description'];
$query = "SELECT c.categories_id, cd.categories_name, c.parent_id, c.categories_status
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE c.categories_status = '2'
                AND c.parent_id = '0'
                AND c.categories_id = cd.categories_id
                AND cd.categories_languages_id = '" . intval($nLanguageID) . "'
              ORDER BY c.sort_order, cd.categories_name";
$categories_result = $dbconn->Execute($query);
while ($categories = $categories_result->fields) {
    $list_of_categories_ids[] = intval($categories['categories_id']);
    $aFoo[$categories['categories_id']] = ['name' => $categories['categories_name'], 'parent' => $categories['parent_id'], 'level' => 0, 'path' => $categories['categories_id'], 'next_id' => false];

    if (isset($prev_id)) {
        $aFoo[$prev_id]['next_id'] = $categories['categories_id'];
    }

    $prev_id = $categories['categories_id'];

    if (!isset($first_element)) {
        $first_element = $categories['categories_id'];
    }

    // Move that ADOdb pointer!
    $categories_result->MoveNext();
}

if (!empty($sCategory)) {
    $new_path = '';
    $id = explode('_', (string) $sCategory);
    reset($id);
    foreach ($id as $key => $value) {
        unset($prev_id);
        unset($first_id);

        $categoriestable = $oostable['categories'];
        $categories_descriptiontable = $oostable['categories_description'];
        $query = "SELECT c.categories_id, cd.categories_name, c.parent_id, c.categories_status
                  FROM $categoriestable c,
                       $categories_descriptiontable cd
                  WHERE c.categories_status = '2'
                    AND c.parent_id = '" . intval($value) . "'
                    AND c.categories_id = cd.categories_id
                    AND cd.categories_languages_id = '" . intval($nLanguageID) . "'
                  ORDER BY c.sort_order, cd.categories_name";
        $categories_result = $dbconn->Execute($query);
        $category_check = $categories_result->RecordCount();
        if ($category_check > 0) {
            $new_path .= $value;
            while ($row = $categories_result->fields) {
                $list_of_categories_ids[] = intval($row['categories_id']);
                $aFoo[$row['categories_id']] = ['name' => $row['categories_name'], 'parent' => $row['parent_id'], 'level' => $key + 1, 'path' => $new_path . '_' . $row['categories_id'], 'next_id' => false];

                if (isset($prev_id)) {
                    $aFoo[$prev_id]['next_id'] = $row['categories_id'];
                }

                $prev_id = $row['categories_id'];

                if (!isset($first_id)) {
                    $first_id = $row['categories_id'];
                }

                $last_id = $row['categories_id'];

                // Move that ADOdb pointer!
                $categories_result->MoveNext();
            }

            $aFoo[$last_id]['next_id'] = $aFoo[$value]['next_id'];
            $aFoo[$value]['next_id'] = $first_id;
            $new_path .= '_';
        } else {
            break;
        }
    }
}

if (isset($list_of_categories_ids) && (sizeof($list_of_categories_ids) > 0)) {
    $select_list_of_cat_ids = implode(",", $list_of_categories_ids);

    $categoriestable = $oostable['categories'];
    $query = "SELECT categories_id, parent_id
                FROM $categoriestable
                WHERE parent_id in (" . $select_list_of_cat_ids . ")";
    $parent_child_result = $dbconn->Execute($query);

    while ($_parent_child = $parent_child_result->fields) {
        $parent_child[] = $_parent_child;

        // Move that ADOdb pointer!
        $parent_child_result->MoveNext();
    }
}

if (isset($first_element)) {
    oos_show_category($first_element);
}


$smarty->assign(
    ['block_heading_categories' => $block_heading, 'categories' => $aCategories]
);
