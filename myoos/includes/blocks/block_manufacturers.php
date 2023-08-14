<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: manufacturers.php,v 1.18 2003/02/10 22:31:01 hpdl
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

if (!$oEvent->installed_plugin('manufacturers')) {
    return false;
}

$manufacturers_block = false;
$display_a_list = false;

$manufacturerstable = $oostable['manufacturers'];

$query = "SELECT manufacturers_id, manufacturers_name
            FROM $manufacturerstable
            ORDER BY manufacturers_name";
$manufacturers_result = $dbconn->Execute($query);
$nManufacturersRecordCount = $manufacturers_result->RecordCount();

if ($nManufacturersRecordCount < 1) {
    $manufacturers_block = false;
} elseif ($nManufacturersRecordCount <= 9) {

    // Display a list
    $display_a_list = true;
    $manufacturers_block = true;
    $manufacturers_list = [];

    while ($manufacturers = $manufacturers_result->fields) {
        $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);

        if (isset($_GET['manufacturers_id']) && ($_GET['manufacturers_id'] == $manufacturers['manufacturers_id'])) {
            $manufacturers_name = '<strong>' . $manufacturers_name .'</strong>';
        }

        $manufacturer_info = array('id' => $manufacturers['manufacturers_id'], 'name' => $manufacturers_name);
        $manufacturers_list[] = $manufacturer_info;

        // Move that ADOdb pointer!
        $manufacturers_result->MoveNext();
    }

    $smarty->assign('manufacturers_list', $manufacturers_list);
} else {
    // Display a drop-down
    $manufacturers_block = true;
    $manufacturers_names = [];
    $manufacturers_values = [];
    if (MAX_MANUFACTURERS_LIST < 2) {
        $manufacturers_values[] = '';
        $manufacturers_names[] = $aLang['pull_down_default'];
    }

    while ($manufacturers = $manufacturers_result->fields) {
        $manufacturers_name = ((strlen($manufacturers['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturers['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturers['manufacturers_name']);
        $manufacturers_values[] = $manufacturers['manufacturers_id'];
        $manufacturers_names[] = $manufacturers_name;

        // Move that ADOdb pointer!
        $manufacturers_result->MoveNext();
    }

    $smarty->assign(
        array(
            'manufacturers_values' => $manufacturers_values,
            'manufacturers_names' => $manufacturers_names
        )
    );

    if (isset($_GET['manufacturers_id'])) {
		$manufacturers_id = filter_input(INPUT_GET, 'manufacturers_id', FILTER_VALIDATE_INT);
        $smarty->assign('select_manufacturers', $manufacturers_id));
    }
}

$smarty->assign(
    array(
        'block_heading_manufacturers' => $block_heading,
        'manufacturers_block' => $manufacturers_block,
        'display_a_list' => $display_a_list
    )
);
