<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.212 2003/02/17 07:55:54 hpdl
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
 * Generate a path to categories
 *
 * @param  $current_category_id
 * @return string
 */
function oos_get_path($current_category_id = '', $parent_id = '', $gparent_id = '')
{
    global $aCategoryPath;

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    if (!empty($current_category_id)) {
        $cp_size = count($aCategoryPath);
        if ($cp_size == 0) {
            $sCategoryNew = $current_category_id;
        } else {
            $sCategoryNew = '';
            if (oos_empty($parent_id) || oos_empty($gparent_id)) {
                $categoriestable = $oostable['categories'];
                $query = "SELECT c.parent_id, p.parent_id as gparent_id
                      FROM $categoriestable AS c,
                           $categoriestable AS p
                     WHERE c.categories_id = '" . intval($aCategoryPath[($cp_size - 1)]) . "'
                       AND p.categories_id = '" . intval($current_category_id) . "'";
                $parent_categories = $dbconn->GetRow($query);

                $gparent_id = $parent_categories['gparent_id'];
                $parent_id = $parent_categories['parent_id'];
            }
            if ($parent_id == $gparent_id) {
                for ($i = 0; $i < ($cp_size - 1); $i++) {
                    $sCategoryNew .= '_' . $aCategoryPath[$i];
                }
            } else {
                for ($i = 0; $i < $cp_size; $i++) {
                    $sCategoryNew .= '_' . $aCategoryPath[$i];
                }
            }
            $sCategoryNew .= '_' . $current_category_id;

            if (str_starts_with($sCategoryNew, '_')) {
                $sCategoryNew = substr($sCategoryNew, 1);
            }
        }
    } else {
        $sCategoryNew = implode('_', $aCategoryPath);
    }

    return $sCategoryNew;
}



/**
 * Return the number of products in a category
 *
 * @param  $category_id
 * @param  $include_inactive
 * @return string
 */
function oos_total_products_in_category($category_id)
{
    $products_count = 0;

    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $productstable = $oostable['products'];
    $products_to_categoriestable = $oostable['products_to_categories'];
    $products = $dbconn->Execute("SELECT COUNT(*) AS total FROM $productstable p, $products_to_categoriestable p2c WHERE p.products_id = p2c.products_id AND p.products_setting = '2' AND p2c.categories_id = '" . intval($category_id) . "'");

    $products_count += $products->fields['total'];

    return $products_count;
}
