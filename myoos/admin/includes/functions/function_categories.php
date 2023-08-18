<?php
/**
 * ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.151 2003/02/07 21:46:49 dgw_
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

function oos_get_path($current_category_id = '')
{
    global $cPath_array;

    if (!is_array($cPath_array)) {
        $cPath_array = [];
    }

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
                  WHERE categories_id = '" . intval($cPath_array[(count($cPath_array)-1)]) . "'";
            $last_category_result = $dbconn->Execute($query);
            $last_category = $last_category_result->fields;

            $categoriestable = $oostable['categories'];
            $query = "SELECT parent_id
                  FROM $categoriestable
                  WHERE categories_id = '" . intval($current_category_id) . "'";
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
            if (str_starts_with($cPath_new, '_')) {
                $cPath_new = substr($cPath_new, 1);
            }
        }
    }

    return 'cPath=' . $cPath_new;
}


function oos_get_category_tree($parent_id = '0', $spacing = '', $exclude = '', $aCategoryTree = '', $include_itself = false)
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    if (!is_array($aCategoryTree)) {
        $aCategoryTree = [];
    }
    if ((count($aCategoryTree) < 1) && ($exclude != '0')) {
        $aCategoryTree[] = ['id' => '0', 'text' => TEXT_TOP];
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if ($include_itself) {
        $categories_descriptiontable = $oostable['categories_description'];
        $query = "SELECT cd.categories_name
                FROM $categories_descriptiontable cd
                WHERE cd.categories_languages_id = '" . intval($language_id) . "'
                  AND cd.categories_id = '" . intval($parent_id) . "'";
        $category_result = $dbconn->Execute($query);

        $category = $category_result->fields;
        $aCategoryTree[] = ['id' => $parent_id, 'text' => $category['categories_name']];
    }

    $categoriestable = $oostable['categories'];
    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT c.categories_id, cd.categories_name, c.parent_id, c.sort_order
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE c.categories_status != 0
				AND c.parent_id = '" . intval($parent_id) . "'
				AND c.categories_id = cd.categories_id 
                AND cd.categories_languages_id = '" . intval($language_id) . "'              
           ORDER BY c.sort_order, cd.categories_name";
    $categories_result = $dbconn->Execute($query);

    while ($categories = $categories_result->fields) {
        if ($exclude != $categories['categories_id']) {
            $aCategoryTree[] = ['id' => $categories['categories_id'], 'text' => $spacing . $categories['categories_name']];
        }

        $aCategoryTree = oos_get_category_tree($categories['categories_id'], $spacing . '&nbsp;&nbsp;&nbsp;', $exclude, $aCategoryTree);

        // Move that ADOdb pointer!
        $categories_result->MoveNext();
    }

    return $aCategoryTree;
}


function oos_get_category_name($category_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_name
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_name = $result->fields['categories_name'] ?? '';

    return $categories_name;
}



function oos_get_categories_page_title($category_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_page_title
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_page_title = $result->fields['categories_page_title'] ?? '';

    return $categories_page_title;
}


function oos_get_products_description($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_description
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_description = $result->fields['products_description'] ?? '';

    return $products_description;
}


function oos_get_products_short_description($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_short_description
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_short_description = $result->fields['products_short_description'] ?? '';

    return $products_short_description;
}




function oos_get_products_essential_characteristicsn($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_essential_characteristics
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_essential_characteristics = $result->fields['products_essential_characteristics'] ?? '';

    return $products_essential_characteristics;
}



function oos_get_products_description_meta($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_description_meta
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_description_meta = $result->fields['products_description_meta'] ?? '';

    return $products_description_meta;
}


function oos_get_products_old_electrical_equipment_description($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_old_electrical_equipment_description
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_old_electrical_equipment_description = $result->fields['products_old_electrical_equipment_description'] ?? '';

    return $products_old_electrical_equipment_description;
}



function oos_get_products_used_goods_description($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_used_goods_description
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_used_goods_description = $result->fields['products_used_goods_description'] ?? '';

    return $products_used_goods_description;
}





function oos_get_products_facebook_title($product_id, $language_id)
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_facebook_title
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_facebook_title = $result->fields['products_facebook_title'] ?? '';

    return $products_facebook_title;
}

function oos_get_products_facebook_description($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_facebook_description
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_facebook_description = $result->fields['products_facebook_description'] ?? '';

    return $products_facebook_description;
}

function oos_get_products_twitter_title($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_twitter_title
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_twitter_title = $result->fields['products_twitter_title'] ?? '';

    return $products_twitter_title;
}

function oos_get_products_twitter_description($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_twitter_description
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_twitter_description = $result->fields['products_twitter_description'] ?? '';

    return $products_twitter_description;
}







function oos_get_categories_facebook_title($category_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_facebook_title
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_facebook_title = $result->fields['categories_facebook_title'] ?? '';

    return $categories_facebook_title;
}


function oos_get_categories_facebook_description($category_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_facebook_description
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_facebook_description = $result->fields['categories_facebook_description'] ?? '';

    return $categories_facebook_description;
}


function oos_get_categories_twitter_title($category_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_twitter_title
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_twitter_title = $result->fields['categories_twitter_title'] ?? '';


    return $categories_twitter_title;
}


function oos_get_categories_twitter_description($category_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_twitter_description
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_twitter_description = $result->fields['categories_twitter_description'] ?? '';

    return $categories_twitter_description;
}



function oos_get_products_url($product_id, $language_id)
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_url
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_url = $result->fields['products_url'] ?? '';

    return $products_url;
}


function oos_products_in_category_count($categories_id, $include_deactivated = false)
{
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

    return $products_count;
}


function oos_childs_in_category_count($categories_id)
{
    $categories_count = 0;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    $query = "SELECT categories_id
              FROM $categoriestable
              WHERE parent_id = '" . intval($categories_id) . "'";
    $result = $dbconn->Execute($query);

    while ($categories = $result->fields) {
        $categories_count++;
        $categories_count += oos_childs_in_category_count($categories['categories_id']);

        // Move that ADOdb pointer!
        $result->MoveNext();
    }

    return $categories_count;
}


function oos_set_categories_status($categories_id, $status)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    if ($status == '1') {
        $query = "UPDATE $categoriestable
                SET categories_status = '1',
					last_modified = now()
                WHERE categories_id = '" . intval($categories_id) . "'";
        $result = $dbconn->Execute($query);

        $categories_query = "SELECT categories_id
                           FROM $categoriestable
                            WHERE parent_id = '" . intval($categories_id) . "'";
        $categories_result = $dbconn->Execute($categories_query);

        while ($categories = $categories_result->fields) {
            oos_set_categories_status($categories['categories_id'], 1);
            // Move that ADOdb pointer!
            $categories_result->MoveNext();
        }
        return;
    } elseif ($status == '2') {
        $query = "UPDATE $categoriestable
                SET categories_status = '2',
					last_modified = now()
                WHERE categories_id = '" . intval($categories_id) . "'";
        $result = $dbconn->Execute($query);

        return;
    } else {
        return false;
    }
}


function oos_set_product_status($products_id, $status)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "UPDATE $productstable
              SET products_setting = '" . intval($status) . "',
                  products_last_modified = now()
              WHERE products_id = '" . intval($products_id) . "'";
    $result = $dbconn->Execute($query);

    return;
}


function product_move_to_trash($products_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "UPDATE $productstable
              SET products_setting = '0',
                  products_last_modified = now()
              WHERE products_id = '" . intval($products_id) . "'";
    $result = $dbconn->Execute($query);

    return;
}


function category_move_to_trash($categories_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    $query = "UPDATE $categoriestable
                SET categories_status = '0',
					last_modified = now()
                WHERE categories_id = '" . intval($categories_id) . "'";
    $result = $dbconn->Execute($query);

    return;
}


function oos_generate_category_path($id, $from = 'category', $categories_array = '', $index = 0)
{
    if (!is_array($categories_array)) {
        $categories_array = [];
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_to_categoriestable = $oostable['products_to_categories'];
    $categoriestable = $oostable['categories'];
    $categories_descriptiontable = $oostable['categories_description'];

    if ($from == 'product') {
        $categories_query = "SELECT categories_id
                           FROM $products_to_categoriestable
                           WHERE products_id = '" . intval($id) . "'";
        $categories_result = $dbconn->Execute($categories_query);

        while ($categories = $categories_result->fields) {
            if ($categories['categories_id'] == '0') {
                $categories_array[$index][] = ['id' => '0', 'text' => TEXT_TOP];
            } else {
                $category_query = "SELECT cd.categories_name, c.parent_id
                             FROM $categoriestable c,
                                  $categories_descriptiontable cd
                             WHERE c.categories_id = '" . intval($categories['categories_id']) . "'
                               AND c.categories_id = cd.categories_id
                               AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'";
                $category_result = $dbconn->Execute($category_query);

                $category = $category_result->fields;

                $categories_array[$index][] = ['id' => $categories['categories_id'], 'text' => $category['categories_name']];
                if ((oos_is_not_null($category['parent_id'])) && ($category['parent_id'] != '0')) {
                    $categories_array = oos_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
                }
                $categories_array[$index] = array_reverse($categories_array[$index]);
            }
            $index++;

            // Move that ADOdb pointer!
            $categories_result->MoveNext();
        }
    } elseif ($from == 'category') {
        $category_query = "SELECT cd.categories_name, c.parent_id
                           FROM $categoriestable c,
                                $categories_descriptiontable cd
                          WHERE c.categories_id = '" . intval($id) . "' 
                            AND c.categories_id = cd.categories_id
                            AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'";
        $category_result = $dbconn->Execute($category_query);
        $category = $category_result->fields;

        if (!is_array($category)) {
            $category = [];
        }
        $category['categories_name'] ??= '';
        $categories_array[$index][] = ['id' => $id, 'text' => $category['categories_name']];
        if ((isset($category['parent_id'])) && ($category['parent_id'] != '0')) {
            $categories_array = oos_generate_category_path($category['parent_id'], 'category', $categories_array, $index);
        }
    }

    return $categories_array;
}


function oos_output_generated_category_path($id, $from = 'category')
{
    $calculated_category_path_string = '';
    $calculated_category_path = oos_generate_category_path($id, $from);
    for ($i = 0, $n = is_countable($calculated_category_path) ? count($calculated_category_path) : 0; $i < $n; $i++) {
        for ($j = 0, $k = is_countable($calculated_category_path[$i]) ? count($calculated_category_path[$i]) : 0; $j < $k; $j++) {
            $calculated_category_path_string .= $calculated_category_path[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
        }
        $calculated_category_path_string = substr($calculated_category_path_string, 0, -16) . '<br>';
    }

    $calculated_category_path_string = substr($calculated_category_path_string, 0, -4);
    if (strlen($calculated_category_path_string) < 1) {
        $calculated_category_path_string = TEXT_TOP;
    }

    return $calculated_category_path_string;
}

function oos_remove_category_image($image)
{
    $sImage = oos_var_prep_for_os($image);

    if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'category/originals/' .$sImage)) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'category/large/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'category/medium/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'category/small/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'category/originals/' .$sImage);
    }
}


function oos_remove_category_banner($image)
{
    $sImage = oos_var_prep_for_os($image);

    if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'banners/originals/' .$sImage)) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'banners/large/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'banners/medium/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'banners/originals/' .$sImage);
    }
}


function oos_remove_panorama_preview_image($image)
{
    $sImage = oos_var_prep_for_os($image);

    if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/originals/' .$sImage)) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/large/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/medium/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/originals/' .$sImage);
    }
}


function oos_remove_scene_image($image)
{
    $sImage = oos_var_prep_for_os($image);

    if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/' .$sImage)) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/originals/' .$sImage);
    }
}



function oos_remove_slider_image($image)
{
    $sImage = oos_var_prep_for_os($image);

    if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'slieder/' .$sImage)) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'slieder/' .$sImage);
    }
}


function oos_remove_category($category_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    $category_image_query = "SELECT categories_image
                             FROM $categoriestable
                             WHERE categories_id = '" . intval($category_id) . "'";
    $category_image_result = $dbconn->Execute($category_image_query);
    $category_image = $category_image_result->fields;

    $duplicate_image_query = "SELECT COUNT(*) AS total
                              FROM $categoriestable
                              WHERE categories_image = '" . oos_db_input($category_image['categories_image']) . "'";
    $duplicate_image_result = $dbconn->Execute($duplicate_image_query);
    $duplicate_image = $duplicate_image_result->fields;

    if ($duplicate_image['total'] < 2) {
        oos_remove_category_image($category_image['categories_image']);
    }

    $categories_imagestable = $oostable['categories_images'];
    $category_image_query = "SELECT categories_image
                             FROM $categories_imagestable
                             WHERE categories_id = '" . intval($category_id) . "'";
    $category_image_result = $dbconn->Execute($category_image_query);
    while ($category_image = $category_image_result->fields) {
        $duplicate_image = "SELECT COUNT(*) AS total
                              FROM $categories_imagestable
                              WHERE categories_image = '" . oos_db_input($category_image['categories_image']) . "'";
        $duplicate_image_result = $dbconn->Execute($duplicate_image);
        $duplicate_image = $duplicate_image_result->fields;

        if ($duplicate_image['total'] < 2) {
            oos_remove_category_image($category_image['categories_image']);
        }
        // Move that ADOdb pointer!
        $category_image_result->MoveNext();
    }

    $dbconn->Execute("DELETE FROM " . $oostable['categories'] . " WHERE categories_id = '" . intval($category_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['categories_description'] . " WHERE categories_id = '" . intval($category_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['categories_images'] . " WHERE categories_id = '" . intval($category_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_to_categories'] . " WHERE categories_id = '" . intval($category_id) . "'");

    $categories_panoramatable = $oostable['categories_panorama'];
    $category_panorama_query = "SELECT panorama_id
                             FROM $categories_panoramatable
                             WHERE categories_id = '" . intval($category_id) . "'";
    $category_panorama_result = $dbconn->Execute($category_panorama_query);
    if ($category_panorama_result->RecordCount()) {
        $category_panorama = $category_panorama_result->fields;

        oos_remove_panorama($category_panorama['panorama_id']);
    }
}


/**
 * Code:     categories_description
 * Author:   Brian Lowe <blowe@wpcusrgrp.org>
 * Date:     June 2002
 */
function oos_get_category_heading_title($category_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_heading_title
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_heading_title = $result->fields['categories_heading_title'] ?? '';

    return $categories_heading_title;
}

function oos_get_category_description($category_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_description
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($category_id) . "'
                AND categories_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $categories_description = $result->fields['categories_description'] ?? '';

    return $categories_description;
}


function oos_get_category_description_meta($category_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_description_meta
                FROM $categories_descriptiontable
               WHERE categories_id = '" . intval($category_id) . "'
                 AND categories_languages_id = '" . intval($language_id). "'";
    $result = $dbconn->Execute($query);

    $categories_description_meta = $result->fields['categories_description_meta'] ?? '';

    return $categories_description_meta;
}


function oos_duplicate_product_image_check($image)
{

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


function oos_remove_product_image($image)
{
    $sImage = oos_var_prep_for_os($image);

    if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/originals/' .$sImage)) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/large/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/medium_large/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/medium/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/small/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/min/' .$sImage);
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/originals/' .$sImage);
    }
}

function oos_remove_products_model($model)
{
    if (empty($model)) {
        return;
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_modelstable = $oostable['products_models'];
    $product_models_query = "SELECT models_id
                            FROM $products_modelstable
                            WHERE models_webgl_gltf =  '" . oos_db_input($model) . "'";
    $models_result = $dbconn->Execute($product_models_query);
    if (!$models_result->RecordCount()) {
        $products_model_viewertable = $oostable['products_model_viewer'];
        $product_models_query = "SELECT model_viewer_id
                            FROM $products_model_viewertable
                            WHERE model_viewer_glb =  '" . oos_db_input($model) . "'";
        $models_result = $dbconn->Execute($product_models_query);
        if (!$models_result->RecordCount()) {
            $sName = oos_strip_suffix($model);
            $dir = OOS_ABSOLUTE_PATH . OOS_MEDIA . 'models/gltf/' . oos_var_prep_for_os($sName);
            oos_remove($dir);
        }
    }

    return;
}


function oos_remove_model_usds($model)
{
    if (empty($model)) {
        return;
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_model_viewertable = $oostable['products_model_viewer'];
    $product_models_query = "SELECT model_viewer_id
                            FROM $products_model_viewertable
                            WHERE model_viewer_usdz =  '" . oos_db_input($model) . "'";
    $models_result = $dbconn->Execute($product_models_query);
    if (!$models_result->RecordCount()) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_MEDIA . 'models/usdz/' . oos_var_prep_for_os($model));
    }

    return;
}



function oos_remove_products_video($video_files)
{
    if (empty($video_files)) {
        return;
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $path = OOS_ABSOLUTE_PATH . OOS_MEDIA . 'video/';
    $products_videotable = $oostable['products_video'];

    if (!empty($video_files['video_source'])) {
        $product_videos_query = "SELECT video_source
                            FROM $products_videotable
                            WHERE video_source =  '" . oos_db_input($video_files['video_source']) . "'";
        $videos_result = $dbconn->Execute($product_videos_query);
        if (!$videos_result->RecordCount()) {
            @unlink($path . oos_var_prep_for_os($video_files['video_source']));
        }
    }

    if (!empty($video_files['video_mp4'])) {
        $product_videos_query = "SELECT video_mp4
                            FROM $products_videotable
                            WHERE video_mp4 =  '" . oos_db_input($video_files['video_mp4']) . "'";
        $videos_result = $dbconn->Execute($product_videos_query);
        if (!$videos_result->RecordCount()) {
            @unlink($path . oos_var_prep_for_os($video_files['video_mp4']));
        }
    }

    if (!empty($video_files['video_webm'])) {
        $product_videos_query = "SELECT video_webm
                            FROM $products_videotable
                            WHERE video_webm =  '" . oos_db_input($video_files['video_webm']) . "'";
        $videos_result = $dbconn->Execute($product_videos_query);
        if (!$videos_result->RecordCount()) {
            @unlink($path . oos_var_prep_for_os($video_files['video_webm']));
        }
    }

    if (!empty($video_files['video_ogv'])) {
        $product_videos_query = "SELECT video_ogv
                            FROM $products_videotable
                            WHERE video_ogv =  '" . oos_db_input($video_files['video_ogv']) . "'";
        $videos_result = $dbconn->Execute($product_videos_query);
        if (!$videos_result->RecordCount()) {
            @unlink($path . oos_var_prep_for_os($video_files['video_ogv']));
        }
    }

    if (!empty($video_files['video_poster'])) {
        $product_videos_query = "SELECT video_poster
                            FROM $products_videotable
                            WHERE video_poster =  '" . oos_db_input($video_files['video_poster']) . "'";
        $videos_result = $dbconn->Execute($product_videos_query);
        if (!$videos_result->RecordCount()) {
            @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'video/' . oos_var_prep_for_os($video_files['video_poster']));
        }
    }

    return;
}




/**
 * Return a product's manufacturer
 *
 * @param  $products_id
 * @return string
 */
function oos_get_manufacturers_name($product_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $manufacturerstable = $oostable['manufacturers'];
    $manufacturers_infotable = $oostable['manufacturers_info'];
    $productstable = $oostable['products'];
    $query = "SELECT m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url
              FROM $manufacturerstable m,
                   $manufacturers_infotable mi,
                   $productstable p
              WHERE p.products_id = '" . intval($product_id) . "'
                AND p.manufacturers_id = m.manufacturers_id
                AND mi.manufacturers_id = m.manufacturers_id";
    $result = $dbconn->Execute($query);

    $manufacturers_name = $result->fields['manufacturers_name'] ?? '';

    return $manufacturers_name;
}


/**
 * Return Products Special Price
 *
 * @param  $nProductID
 * @return string
 */
function oos_get_products_special_price($nProductID)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $specialstable = $oostable['specials'];
    $query = "SELECT specials_new_products_price
              FROM $specialstable
              WHERE products_id = '" . intval($nProductID) . "'
                AND status";
    $specials_new_products_price = $dbconn->GetOne($query);

    return $specials_new_products_price;
}


/**
 * Find a Categories Name
 *
 * @param  $who_am_i
 * @return string
 */
function oos_get_categories_name($who_am_i, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_name
              FROM $categories_descriptiontable
              WHERE categories_id = '" . intval($who_am_i) . "'
                AND categories_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);


    $categories_name = $result->fields['categories_name'] ?? '';

    return $categories_name;
}


 /**
  * Return 3D Model Name
  *
  * @param  $model_id
  * @param  $language
  * @return string
  */
function oos_get_models_name($model_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_models_descriptiontable = $oostable['products_models_description'];
    $query = "SELECT models_name
              FROM $products_models_descriptiontable
              WHERE models_id = '" . intval($model_id) . "'
                AND models_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $models_name = $result->fields['models_name'] ?? '';

    return $models_name;
}



 /**
  * Return 3D Model Page Title for SEO
  *
  * @param  $model_id
  * @param  $language
  * @return string
  */
function oos_get_models_title($model_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_models_descriptiontable = $oostable['products_models_description'];
    $query = "SELECT models_title
              FROM $products_models_descriptiontable
              WHERE models_id = '" . intval($model_id) . "'
                AND models_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $models_title = $result->fields['models_title'] ?? '';


    return $models_title;
}


function oos_get_models_description_meta($model_id, $language_id = '')
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    $products_models_descriptiontable = $oostable['products_models_description'];
    $query = "SELECT models_description_meta
              FROM $products_models_descriptiontable
              WHERE models_id = '" . intval($model_id) . "'
                AND models_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $models_description_meta = $result->fields['models_description_meta'] ?? '';

    return $models_description_meta;
}



 /**
  * Return Panorama Name
  *
  * @param  $panorama_id
  * @param  $language
  * @return string
  */
function oos_get_panorama_name($panorama_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_panorama_descriptiontable = $oostable['categories_panorama_description'];
    $query = "SELECT panorama_name
              FROM $categories_panorama_descriptiontable
              WHERE panorama_id = '" . intval($panorama_id) . "'
                AND panorama_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $panorama_name = $result->fields['panorama_name'] ?? '';

    return $panorama_name;
}



 /**
  * Return Panorama Title for SEO
  *
  * @param  $panorama_id
  * @param  $language
  * @return string
  */
function oos_get_panorama_title($panorama_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_panorama_descriptiontable = $oostable['categories_panorama_description'];
    $query = "SELECT panorama_title
              FROM $categories_panorama_descriptiontable
              WHERE panorama_id = '" . intval($panorama_id) . "'
                AND panorama_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $panorama_title = $result->fields['panorama_title'] ?? '';

    return $panorama_title;
}



function oos_get_panorama_description_meta($panorama_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_panorama_descriptiontable = $oostable['categories_panorama_description'];
    $query = "SELECT panorama_description_meta
              FROM $categories_panorama_descriptiontable
              WHERE panorama_id = '" . intval($panorama_id) . "'
                AND panorama_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $panorama_description_meta = $result->fields['panorama_description_meta'] ?? '';

    return $panorama_description_meta;
}


 /**
  * Return 3D Model Page for ALT-TAG
  *
  * @param  $model_viewer_id
  * @param  $language
  * @return string
  */
function oos_get_model_viewer_title($model_viewer_id, $language_id = '')
{
    if (empty($model_viewer_id) || !is_numeric($model_viewer_id)) {
        return '';
    }

    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_model_viewer_descriptiontable = $oostable['products_model_viewer_description'];
    $query = "SELECT model_viewer_title
              FROM $products_model_viewer_descriptiontable
              WHERE model_viewer_id = '" . intval($model_viewer_id) . "'
                AND model_viewer_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $model_viewer_title = $result->fields['model_viewer_title'] ?? '';

    return $model_viewer_title;
}


function oos_get_model_viewer_description($model_viewer_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_model_viewer_descriptiontable = $oostable['products_model_viewer_description'];
    $query = "SELECT model_viewer_description
              FROM $products_model_viewer_descriptiontable
              WHERE model_viewer_id = '" . intval($model_viewer_id) . "'
                AND model_viewer_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $model_viewer_description = $result->fields['model_viewer_description'] ?? '';

    return $model_viewer_description;
}



 /**
  * Return Hotspot Text
  *
  * @param  $hotspot_id
  * @param  $language
  * @return string
  */
function oos_get_hotspot_text($hotspot_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categories_panorama_scene_hotspot_texttable = $oostable['categories_panorama_scene_hotspot_text'];
    $query = "SELECT hotspot_text
              FROM $categories_panorama_scene_hotspot_texttable
              WHERE hotspot_id = '" . intval($hotspot_id) . "'
                AND hotspot_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $hotspot_text = $result->fields['hotspot_text'] ?? '';

    return $hotspot_text;
}


function oos_remove_panorama($panorama_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories_panorama'];
    $panorama_preview_query = "SELECT panorama_preview
                             FROM $categoriestable
                             WHERE panorama_id = '" . intval($panorama_id) . "'";
    $panorama_preview_result = $dbconn->Execute($panorama_preview_query);
    $panorama_preview = $panorama_preview_result->fields;

    oos_remove_panorama_preview_image($panorama_preview['panorama_preview']);


    $categories_panorama_scenetable = $oostable['categories_panorama_scene'];
    $scene_image_query = "SELECT scene_image
                             FROM $categories_panorama_scenetable
                             WHERE panorama_id = '" . intval($panorama_id) . "'";
    $scene_image_result = $dbconn->Execute($scene_image_query);
    $scene_image = $scene_image_result->fields;

    oos_remove_scene_image($scene_image['scene_image']);

    $categories_panorama_scene_hotspot = $oostable['categories_panorama_scene_hotspot'];
    $query = "SELECT hotspot_id
              FROM $categories_panorama_scene_hotspot
              WHERE panorama_id = '" . intval($panorama_id) . "'";
    $hotspot_result = $dbconn->Execute($query);
    while ($hotspot = $hotspot_result->fields) {
        $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_scene_hotspot'] . " WHERE hotspot_id = '" . intval($hotspot['hotspot_text']) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_scene_hotspot_text'] . " WHERE hotspot_id = '" . intval($hotspot['hotspot_text']) . "'");


        // Move that ADOdb pointer!
        $hotspot_result->MoveNext();
    }

    $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama'] . " WHERE panorama_id = '" . intval($panorama_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_description'] . " WHERE panorama_id = '" . intval($panorama_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_scene'] . " WHERE panorama_id = '" . intval($panorama_id) . "'");
}


 /**
  * Return Video Title
  *
  * @param  $video_id
  * @param  $language
  * @return string
  */
function oos_get_video_title($video_id, $language_id = '')
{
    if (empty($video_id) || !is_numeric($video_id)) {
        return '';
    }

    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_video_descriptiontable = $oostable['products_video_description'];
    $query = "SELECT video_title
              FROM $products_video_descriptiontable
              WHERE video_id = '" . intval($video_id) . "'
                AND video_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $video_title = $result->fields['video_title'] ?? '';

    return $video_title;
}


 /**
  * Return Video Description
  *
  * @param  $video_id
  * @param  $language
  * @return string
  */
function oos_get_video_description($video_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_video_descriptiontable = $oostable['products_video_description'];
    $query = "SELECT video_description
              FROM $products_video_descriptiontable
              WHERE video_id = '" . intval($video_id) . "'
                AND video_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $video_description = $result->fields['video_description'] ?? '';

    return $video_description;
}
