<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: download.php,v 1.9 2003/02/13 03:01:48 hpdl
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

if (!isset($_SESSION['customer_id'])) {
    die;
}

// Check download.php was called with proper GET parameters
if ((isset($_GET['order']) && !is_numeric($_GET['order'])) || (isset($_GET['id']) && !is_numeric($_GET['id']))) {
    die;
}


/**
 * Returns a random name, 16 to 20 characters long
 * There are more than 10^28 combinations
 * The directory is "hidden", i.e. starts with '.'
 *
 * @return string
 */
function oos_random_name()
{
    $letters = 'abcdefghijklmnopqrstuvwxyz';
    $dirname = '.';
    $length = floor(oos_rand(16, 20));
    for ($i = 1; $i <= $length; $i++) {
        $q = floor(oos_rand(1, 26));
        $dirname .= $letters[$q];
    }

    return $dirname;
}


/**
 * Unlinks all subdirectories and files in $dir
 * Works only on one subdir level, will not recurse
 */
function oos_unlink_temp_dir($dir)
{
    $h1 = opendir($dir);

    while ($subdir = readdir($h1)) {
        // Ignore non directories
        if (!is_dir($dir . $subdir)) {
            continue;
        }

        // Ignore . and .. and CVS
        if ($subdir == '.' || $subdir == '..' || $subdir == 'CVS') {
            continue;
        }

        // Loop and unlink files in subdirectory
        $h2 = opendir($dir . $subdir);
        while ($file = readdir($h2)) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            @unlink($dir . $subdir . '/' . $file);
        }

        closedir($h2);
        @rmdir($dir . $subdir);
    }

    closedir($h1);
}

$order = filter_input(INPUT_GET, 'order', FILTER_VALIDATE_INT);
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Check that order_id, customer_id and filename match
$sql = "SELECT date_format(o.date_purchased, '%Y-%m-%d') AS date_purchased_day, 
                  opd.download_maxdays, opd.download_count, opd.download_maxdays, 
                  opd.orders_products_filename 
           FROM " . $oostable['orders'] . " o, 
                " . $oostable['orders_products'] . " op, 
                " . $oostable['orders_products_download'] . " opd 
           WHERE o.customers_id = '" . intval($_SESSION['customer_id']) . "' 
             AND o.orders_id = '" . intval($order) . "' 
             AND o.orders_id = op.orders_id 
             AND op.orders_products_id = opd.orders_products_id 
             AND opd.orders_products_download_id = '" . intval($id) . "' 
             AND opd.orders_products_filename != ''";
$downloads_result = $dbconn->Execute($sql);
if (!$downloads_result->RecordCount()) {
    die;
}
$downloads = $downloads_result->fields;
// MySQL 3.22 does not have INTERVAL
[$dt_year, $dt_month, $dt_day] = explode('-', (string) $downloads['date_purchased_day']);
$download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads['download_maxdays'], $dt_year);


// Die if time expired (maxdays = 0 means no time limit)
if (($downloads['download_maxdays'] != 0) && ($download_timestamp <= time())) {
    die;
}

// Die if remaining count is <=0
if ($downloads['download_count'] <= 0) {
    die;
}

// Die if file is not there
if (!file_exists(OOS_DOWNLOAD_PATH . $downloads['orders_products_filename'])) {
    die;
}


// Now decrement counter
$dbconn->Execute(
    "UPDATE " . $oostable['orders_products_download'] . " 
                    SET download_count = download_count-1 
                    WHERE orders_products_download_id = '" . intval($_GET['id']) . "'"
);


// Now send the file with header() magic
header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: Application/octet-stream");
header("Content-disposition: attachment; filename=" . $downloads['orders_products_filename']);

if (DOWNLOAD_BY_REDIRECT == 'true') {
    // This will work only on Unix/Linux hosts
    oos_unlink_temp_dir(OOS_DOWNLOAD_PATH_PUBLIC);
    $tempdir = oos_random_name();
    umask(0000);
    mkdir(OOS_DOWNLOAD_PATH_PUBLIC . $tempdir, 0777);
    symlink(OOS_DOWNLOAD_PATH . $downloads['orders_products_filename'], OOS_DOWNLOAD_PATH_PUBLIC . $tempdir . '/' . $downloads['orders_products_filename']);
    oos_redirect(OOS_DOWNLOAD . $tempdir . '/' . $downloads['orders_products_filename']);
} else {
    // This will work on all systems, but will need considerable resources
    // We could also loop with fread($fp, 4096) to save memory
    readfile(OOS_DOWNLOAD_PATH . $downloads['orders_products_filename']);
}
