<?php
/**
   ----------------------------------------------------------------------
   $Id: function.oos_address_label.php,v 1.1 2007/06/08 13:34:16 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.212 2003/02/17 07:55:54 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */


/**
 * Smarty {oos_address_label} function plugin
 *
 * Type:     function
 * Name:     oos_address_label
 * Version:  1.0
 * -------------------------------------------------------------
 */

function smarty_function_oos_address_label($params, &$smarty)
{
    include_once SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/lib/smarty-plugins/function.oos_address_format.php';

    $customers_id = '';
    $address_id = 1;
    $html = true;
    $boln = '';
    $eoln = '<br>';

    foreach ($params as $_key => $_val) {
        ${$_key} = smarty_function_escape_special_chars($_val);
    }

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $address_result = $dbconn->Execute("SELECT entry_firstname AS firstname, entry_lastname AS lastname, entry_company AS company, entry_street_address AS street_address, entry_city AS city, entry_postcode AS postcode, entry_state AS state, entry_zone_id AS zone_id, entry_country_id AS country_id FROM " . $oostable['address_book'] . " WHERE customers_id = '" . (int)$customers_id . "' AND address_book_id = '" . (int)$address_id . "'");
    $address = $address_result->fields;

    $format_id = oos_get_address_format_id($address['country_id']);


    return smarty_function_oos_address_format(
        ['address_format_id' => $format_id, 'address'   => $address, 'html'      => $html],
        $smarty
    );
}
