<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.151 2003/02/07 21:46:49 dgw_
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
 * Returns Zone Class Name
 *
 * @param  $zone_class_id
 * @return string
 */
function oos_cfg_get_zone_class_title($zone_class_id)
{
    if ($zone_class_id == '0') {
        return TEXT_NONE;
    } else {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $query = "SELECT geo_zone_name 
                FROM " . $oostable['geo_zones'] . " 
                WHERE geo_zone_id = '" . intval($zone_class_id) . "'";
        $result = $dbconn->Execute($query);

        return $result->fields['geo_zone_name'];
    }
}


/**
 * Returns Order Status Name
 *
 * @param  $order_status_id
 * @param  $language
 * @return string
 */
function oos_cfg_get_order_status_name($order_status_id, $language_id = '')
{
    if ($order_status_id < 1) {
        return TEXT_DEFAULT;
    }

    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT orders_status_name
              FROM " . $oostable['orders_status'] . "
              WHERE orders_status_id = '" .  intval($order_status_id) . "'
                AND orders_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    return $result->fields['orders_status_name'];
}


/**
 * Returns Tax Class Name
 *
 * @param  $tax_class_id
 * @return string
 */
function oos_cfg_get_tax_class_title($tax_class_id)
{
    if ($tax_class_id == '0') {
        return TEXT_NONE;
    } else {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $query = "SELECT tax_class_title
                FROM " . $oostable['tax_class'] . "
                WHERE tax_class_id = '" . intval($tax_class_id) . "'";
        $result = $dbconn->Execute($query);

        return $result->fields['tax_class_title'];
    }
}


/**
 * Returns Zone Name
 *
 * @param  $zone_id
 * @return string
 */
function oos_cfg_get_zone_name($zone_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT zone_name
              FROM " . $oostable['zones'] . "
              WHERE zone_id = '" . intval($zone_id) . "'";
    $result = $dbconn->Execute($query);

    if (!$result->RecordCount()) {
        return $zone_id;
    } else {
        return $result->fields['zone_name'];
    }
}


/**
 * Function to read in text area in admin
 *
 * @param  $text
 * @return string
 */
function oos_cfg_textarea($text)
{
    return oos_draw_textarea_field('configuration_value', false, 35, 5, $text);
}


/**
 * Output a selection field
 *
 * @param     $select_array
 * @key_value
 * @key
 * @return    string
 */
function oos_cfg_select_option($select_array, $key_value, $key = '')
{
    $string = '';

    for ($i = 0, $n = count($select_array); $i < $n; $i++) {
        $name = ((oos_is_not_null($key)) ? 'configuration[' . $key . ']' : 'configuration_value');

        $string .= '<br><input class="' . $key . '" type="radio" name="' . $name . '" value="' . $select_array[$i] . '"';

        if ($key_value == $select_array[$i]) {
            $string .= ' checked="checked"';
        }

        $string .= ' /> ' . $select_array[$i];
    }

    return $string;
}



/**
 * Alias function to oos_get_country_name, which also returns the country name
 *
 * @param  $country_id
 * @return string
 */
function oos_cfg_get_country_name($country_id)
{
    return oos_get_country_name($country_id);
}


/**
 * Alias function for Store configuration values in the Administration Tool
 *
 * @param  $country_id
 * @return string
 */
function oos_cfg_pull_down_country_list($country_id)
{
    return oos_draw_pull_down_menu('configuration_value', oos_get_countries(), $country_id);
}


/**
 * Alias function for Store configuration values in the Administration Tool
 *
 * @param  $zone_id
 * @return string
 */
function oos_cfg_pull_down_zone_list($zone_id)
{
    return oos_draw_pull_down_menu('configuration_value', oos_get_country_zones(STORE_COUNTRY), $zone_id);
}


/**
 * Output a form pull down menu
 *
 * @param  $zone_class_id
 * @param  $key
 * @return string
 */
function oos_cfg_pull_down_zone_classes($zone_class_id, $key = '')
{
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $zone_class_array = array(array('id' => '0', 'text' => TEXT_NONE));

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT geo_zone_id, geo_zone_name
              FROM " . $oostable['geo_zones'] . "
              ORDER BY geo_zone_name";
    $result = $dbconn->Execute($query);

    while ($zone_class = $result->fields) {
        $zone_class_array[] = array('id' => $zone_class['geo_zone_id'],
                                  'text' => $zone_class['geo_zone_name']);

        // Move that ADOdb pointer!
        $result->MoveNext();
    }

    return oos_draw_pull_down_menu($name, $zone_class_array, $zone_class_id);
}


/**
 * Output a form pull down menu
 *
 * @param  $order_status_id
 * @param  $key
 * @return string
 */
function oos_cfg_pull_down_order_statuses($order_status_id, $key = '')
{
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $statuses_array = array(array('id' => '0', 'text' => TEXT_DEFAULT));

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT orders_status_id, orders_status_name
              FROM " . $oostable['orders_status'] . "
              WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'
              ORDER BY orders_status_name";
    $result = $dbconn->Execute($query);

    while ($statuses = $result->fields) {
        $statuses_array[] = array('id' => $statuses['orders_status_id'],
                                'text' => $statuses['orders_status_name']);

        // Move that ADOdb pointer!
        $result->MoveNext();
    }

    return oos_draw_pull_down_menu($name, $statuses_array, $order_status_id);
}


/**
 * Output a form pull down menu
 *
 * @param  $tax_class_id
 * @param  $key
 * @return string
 */
function oos_cfg_pull_down_tax_classes($tax_class_id, $key = '')
{
    $name = (($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $query = "SELECT tax_class_id, tax_class_title
              FROM " . $oostable['tax_class'] . "
              ORDER BY tax_class_title";
    $result = $dbconn->Execute($query);

    while ($tax_class = $result->fields) {
        $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);

        // Move that ADOdb pointer!
        $result->MoveNext();
    }

    return oos_draw_pull_down_menu($name, $tax_class_array, $tax_class_id);
}
