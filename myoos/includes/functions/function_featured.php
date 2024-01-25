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
 * Sets the status of a featured product
 */
function oos_set_featured_status($nFeaturedId, $status)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $featuredtable = $oostable['featured'];
    return $dbconn->Execute(
        "UPDATE $featuredtable
                             SET status = '" . oos_db_input($status) . "',
                                 date_status_change = now()
                             WHERE featured_id = '" . intval($nFeaturedId) . "'"
    );
}


/**
 * Auto expire featured products
 */
function oos_expire_featured()
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $featuredtable = $oostable['featured'];
    $sql = "SELECT featured_id
            FROM $featuredtable
            WHERE status = '1'
              AND now() >= expires_date
              AND expires_date > 0";
    if (USE_CACHE == 'true') {
        $featured_result = $dbconn->CacheExecute(15, $sql);
    } else {
        $featured_result = $dbconn->Execute($sql);
    }
    if (!$featured_result) {
        return;
    }

    if ($featured_result->RecordCount() > 0) {
        while ($featured = $featured_result->fields) {
            oos_set_featured_status($featured['featured_id'], '0');
            // Move that ADOdb pointer!
            $featured_result->MoveNext();
        }
    }
}
