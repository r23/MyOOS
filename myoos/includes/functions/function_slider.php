<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
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
 * @param $slider_id
 * @param $status
 */
function oos_set_slider_status($nSliderId, $status)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $slidertable = $oostable['categories_slider'];
    $dbconn->Execute(
        "UPDATE $slidertable
                             SET status = '" . oos_db_input($status) . "',
                                 date_status_change = now()
                              WHERE slider_id = '" . intval($nSliderId) . "'"
    );

    return;
}


/**
 * Auto expire products on special
 */
function oos_expire_slider()
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $slidertable = $oostable['categories_slider'];
    $query = "SELECT slider_id
              FROM $slidertable
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
        while ($slider = $result->fields) {
            oos_set_slider_status($slider['slider_id'], 0);

            // Move that ADOdb pointer!
            $result->MoveNext();
        }
    }
}
