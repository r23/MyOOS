<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

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
 * Return a product's catagory
 *
 * @param  $products_id
 * @return string  boolean
 */
function oos_get_product_path($products_id)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $products_to_categoriestable = $oostable['products_to_categories'];
    $sql = "SELECT categories_id
            FROM $products_to_categoriestable
            WHERE products_id = '" . intval($products_id) . "'";
    $cat_id_data = $dbconn->SelectLimit($sql, 1);
    if ($cat_id_data->RecordCount()) {
        return $cat_id_data->fields['categories_id'];
    }

    return false;
}
