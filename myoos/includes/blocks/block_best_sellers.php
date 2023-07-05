<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: best_sellers.php,v 1.20 2003/02/10 22:30:57 hpdl
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

if (!is_numeric(MAX_DISPLAY_BESTSELLERS)) {
    return false;
}

$best_sellers_block = false;

if (isset($nCurrentCategoryID) && ($nCurrentCategoryID > 0)) {
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $products_to_categoriestable = $oostable['products_to_categories'];
    $categoriestable = $oostable['categories'];
    $query = "SELECT DISTINCT p.products_id, p.products_image, pd.products_name, pd.products_short_description
              FROM $productstable p,
                   $products_descriptiontable pd,
                   $products_to_categoriestable p2c,
                   $categoriestable c
              WHERE p.products_setting = '2'
                AND p.products_ordered > 0
                AND p.products_id = pd.products_id
                AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
                AND p.products_id = p2c.products_id
                AND p2c.categories_id = c.categories_id
                AND '" . intval($nCurrentCategoryID) . "' IN (c.categories_id, c.parent_id)
              ORDER BY p.products_ordered DESC, pd.products_name";
} else {
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT DISTINCT p.products_id, p.products_image, pd.products_name,
                      pd.products_short_description
              FROM $productstable p,
                   $products_descriptiontable pd
              WHERE p.products_setting = '2'
                AND p.products_ordered > 0
                AND p.products_id = pd.products_id
                AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
              ORDER BY p.products_ordered DESC, pd.products_name";
}
$best_sellers_result = $dbconn->SelectLimit($query, MAX_DISPLAY_BESTSELLERS);
if ($best_sellers_result->RecordCount() >= MIN_DISPLAY_BESTSELLERS) {
    $best_sellers_block = true;

    $smarty->assign('best_sellers_list', $best_sellers_result->GetArray());
    $smarty->assign('block_heading_best_sellers', $block_heading);
}

$smarty->assign('best_sellers_block', $best_sellers_block);
