<?php
/**
   ----------------------------------------------------------------------
   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: downloads.php,v 1.2 2003/02/12 23:55:58 hpdl
   ----------------------------------------------------------------------

   WebMakers.com Added: Added: Downloads Controller
   Written by Linda McGrath osCOMMERCE@WebMakers.com
   http://www.thewebmakerscorner.com

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

if (oos_var_prep_for_os($sContent) != $aContents['account_history_info']) {
    // Get last order id for checkout_success
    $orderstable = $oostable['orders'];
    $orders_result = $dbconn->Execute("SELECT orders_id FROM $orderstable WHERE customers_id = '" . intval($_SESSION['customer_id']) . "' ORDER BY orders_id desc limit 1");
    $orders = $orders_result->fields;
    $last_order = $orders['orders_id'];
} else {
    $last_order = filter_input(INPUT_GET, 'order_id', FILTER_SANITIZE_STRING);
}

// Now get all downloadable products in that order
// BOF: WebMakers.com Added: Downloads Controller
// DEFINE WHICH ORDERS_STATUS TO USE IN function_downloads_controller.php
// USE last_modified instead of date_purchased
$orderstable = $oostable['orders'];
$orders_productstable = $oostable['orders_products'];
$orders_products_downloadtable = $oostable['orders_products_download'];
$sql = "SELECT o.orders_status, date_format(o.last_modified, '%Y-%m-%d') AS date_purchased_day,
                 opd.download_maxdays, op.products_name, opd.orders_products_download_id,
                 opd.orders_products_filename, opd.download_count, opd.download_maxdays
          FROM $orderstable o,
               $orders_productstable op,
               $orders_products_downloadtable opd
          WHERE o.customers_id = '" . intval($_SESSION['customer_id']) . "'
            AND o.orders_status >= '" . DOWNLOADS_CONTROLLER_ORDERS_STATUS . "'
            AND o.orders_id = '" . intval($last_order) . "'
            AND o.orders_id = op.orders_id
            AND op.orders_products_id = opd.orders_products_id
            AND opd.orders_products_filename != ''";
$downloads_result = $dbconn->Execute($sql);
if ($downloads_result->RecordCount() > 0) {
    $downloads_array = [];
    while ($downloads = $downloads_result->fields) {
        // MySQL 3.22 does not have INTERVAL
        list($dt_year, $dt_month, $dt_day) = explode('-', $downloads['date_purchased_day']);
        $download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);
        $download_expiry = date('Y-m-d H:i:s', $download_timestamp);
        $show_download_link = 'false';
        if (($downloads['download_count'] > 0) && (file_exists(OOS_DOWNLOAD_PATH . $downloads['orders_products_filename'])) && (($downloads['download_maxdays'] == 0) || ($download_timestamp > time()))) {
            $show_download_link = 'true';
        }
        $downloads_array[] = array('show_download_link' => $show_download_link,
                               'last_order' => $last_order,
                               'id' => $downloads['orders_products_download_id'],
                               'products_name' => $downloads['products_name'],
                               'download_expiry' => $download_expiry,
                               'download_count' => $downloads['download_count']);

        // Move that ADOdb pointer!
        $downloads_result->MoveNext();
    }

    $smarty->assign('downloads_array', $downloads_array);
}
