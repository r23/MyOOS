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
 * @param $specials_id
 * @param $status
 */
function oos_set_account_status($customers_id)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $dbconn->Execute("DELETE FROM " . $oostable['address_book'] . " WHERE customers_id = '" . intval($customers_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers'] . " WHERE customers_id = '" . intval($customers_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_info'] . " WHERE customers_info_id = '" . intval($customers_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_basket'] . " WHERE customers_id = '" . intval($customers_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_basket_attributes'] . " WHERE customers_id = '" . intval($customers_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_wishlist'] . " WHERE customers_id = '" . intval($customers_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_wishlist_attributes'] . " WHERE customers_id = '" . intval($customers_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_status_history'] . " WHERE customers_id = '" . intval($customers_id) . "'");
	$dbconn->Execute("DELETE FROM " . $oostable['guest_account'] . " WHERE customers_id = '" . intval($customers_id) . "'");

    return;
}


/**
 * Auto expire products on special
 */
function oos_expire_account()
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $guest_accountstable = $oostable['guest_account'];
    $query = "SELECT customers_id
              FROM $guest_accountstable
              WHERE date_added < DATE_SUB(NOW(), INTERVAL 40 DAY)";
    $result = $dbconn->Execute($query);

    if (!$result) {
        return;
    }

    if ($result->RecordCount() > 0) {
        while ($account = $result->fields) {
            oos_set_account_status($account['customers_id']);

            // Move that ADOdb pointer!
            $result->MoveNext();
        }
    }
}
