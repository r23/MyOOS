<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$products_history_block = 'false';

if ($_SESSION['products_history']->count_history() > 0) {
    $products_history_block = 'true';

    $product_ids = $_SESSION['products_history']->get_product_id_list();

    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $products_sql = "SELECT p.products_id, p.products_image, pd.products_name, pd.products_short_description
                     FROM $productstable p,
                          $products_descriptiontable pd
                     WHERE p.products_id IN (" . $product_ids . ")
                       AND p.products_id = pd.products_id
                       AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
                     ORDER BY products_name";
    $smarty->assign('history_products', $dbconn->GetAll($products_sql));
}

$smarty->assign('products_history_block', $products_history_block);
