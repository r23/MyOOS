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
 * Return options name
 *
 * @param  $options_id
 * @return string
 */
function oos_options_name($options_id)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    if (empty($options_id) || ($options_id == 0)) {
        return '';
    }

    $products_optionstable = $oostable['products_options'];
    $query = "SELECT products_options_name
              FROM $products_optionstable
              WHERE products_options_id = '" . intval($options_id) . "'
                AND products_options_languages_id = '" . intval($_SESSION['language_id']) . "'";
    $result = $dbconn->Execute($query);

    $products_options_name = $result->fields['products_options_name'];

    return $products_options_name;
}


/**
 * Return values name
 *
 * @param  $values_id
 * @return string
 */
function oos_values_name($values_id)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    if (empty($values_id) || ($values_id == 0)) {
        return '';
    }


    $products_options_valuestable = $oostable['products_options_values'];
    $query = "SELECT products_options_values_name
              FROM $products_options_valuestable
              WHERE products_options_values_id = '" . intval($values_id) . "'
                AND products_options_values_languages_id = '" . intval($_SESSION['language_id']) . "'";
    $result = $dbconn->Execute($query);

    $products_options_values_name = $result->fields['products_options_values_name'];

    return $products_options_values_name;
}


/**
 * Draw a pulldown for Option Types
 *
 * @param $name
 * @param $default
 */
function oos_draw_option_type_pull_down_menu($name, $default = '')
{
    global $products_options_types_list;

    $values = [];
    foreach ($products_options_types_list as $id => $text) {
        $values[] = ['id' => $id, 'text' => $text];
    }
    return oos_draw_pull_down_menu($name, '', $values, $default);
}


/**
 * Return options type name
 *
 * @param $opt_type
 */
function oos_options_type_name($opt_type)
{
    global $products_options_types_list;

    return $products_options_types_list[$opt_type] ?? 'Error ' . $opt_type;
}


/*
* changes the status
*
* 0 = not visible
* 1 = visible
*/
function oos_set_attributes_status($products_attributes_id, $status)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $products_attributestable = $oostable['products_attributes'];
    if ($status == 1) {
        $query = "UPDATE $products_attributestable
				SET options_values_status = '1'
				WHERE products_attributes_id = '" . intval($products_attributes_id) . "'";
        $result = $dbconn->Execute($query);
        return;
    } elseif ($status == '0') {
        $query = "UPDATE $products_attributestable
				SET options_values_status = '0'
				WHERE products_attributes_id = '" . intval($products_attributes_id) . "'";
        $result = & $dbconn->Execute($query);
        return;
    } else {
        return false;
    }
}
