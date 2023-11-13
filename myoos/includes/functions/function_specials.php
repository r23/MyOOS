<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.231 2003/07/09 01:15:48 hpdl
         general.php,v 1.212 2003/02/17 07:55:54 hpdl
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
 * Sets the status of a special product
 *
 * @param $specials_id
 * @param $status
 */
function oos_set_specials_status($nSpecialsId, $status)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $specialstable = $oostable['specials'];
    $query = "SELECT products_id FROM $specialstable WHERE specials_id = '" . intval($nSpecialsId) . "'";
    $products_id = $dbconn->GetOne($query);


    $productstable = $oostable['products'];
    $product_query = "SELECT products_price
                        FROM $productstable
                        WHERE products_id = '" . intval($products_id) . "'";
    $products_price = $dbconn->GetOne($product_query);

    // product price history
    $sql_price_array = ['products_id' => intval($products_id), 'products_price' => oos_db_input($products_price), 'date_added' => 'now()'];
    oos_db_perform($oostable['products_price_history'], $sql_price_array);

    $dbconn->Execute(
        "UPDATE $specialstable
                             SET status = '" . oos_db_input($status) . "',
                                 date_status_change = now()
                              WHERE specials_id = '" . intval($nSpecialsId) . "'"
    );

    return;
}


/**
 * Auto expire products on special
 */
function oos_expire_specials()
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $specialstable = $oostable['specials'];
    $query = "SELECT specials_id
              FROM $specialstable
              WHERE status = '1' 
                AND now() >= expires_date
                AND expires_date > 0";
    if (USE_CACHE == 'true') {
        $result = $dbconn->CacheExecute(3600, $query);
    } else {
        $result = $dbconn->Execute($query);
    }
    if (!$result) {
        return;
    }

    if ($result->RecordCount() > 0) {
        while ($specials = $result->fields) {
            oos_set_specials_status($specials['specials_id'], 0);

            // Move that ADOdb pointer!
            $result->MoveNext();
        }
    }
}
