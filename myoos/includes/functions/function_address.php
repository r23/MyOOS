<?php
/**
   ----------------------------------------------------------------------

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
  * address
  *
  * @link    https://www.oos-shop.de
  * @package oos_address
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/12 16:49:27 $
  */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

 /**
  * Returns the zone (State/Province) code
  *
  * @param  $country_id
  * @param  $zone_id
  * @param  $default_zone
  * @return string
  */
function oos_get_zone_code($country_id, $zone_id, $default_zone)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $zonestable = $oostable['zones'];
    $zone = $dbconn->Execute("SELECT zone_code FROM $zonestable WHERE zone_country_id = '" . intval($country_id) . "' AND zone_id = '" . intval($zone_id) . "'");
    if ($zone->RecordCount() > 0) {
        return $zone->fields['zone_code'];
    } else {
        return $default_zone;
    }
}


 /**
  * Returns the address_format_id for the given country
  *
  * @param  $country_id
  * @return string
  */
function oos_get_address_format_id($country_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $countriestable = $oostable['countries'];
    $address_format = $dbconn->Execute("SELECT address_format_id AS format_id FROM $countriestable WHERE countries_id = '" . intval($country_id) . "'");
    if ($address_format->RecordCount() > 0) {
        return $address_format->fields['format_id'];
    } else {
        return '1';
    }
}


 /**
  * Return a formatted address
  *
  * @param  $address_format_id
  * @param  $address
  * @param  $html
  * @param  $boln
  * @param  $eoln
  * @return string
  */
function oos_address_format($address_format_id, $address, $html, $boln, $eoln)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $address_formattable = $oostable['address_format'];
    $address_format_result = $dbconn->Execute("SELECT address_format AS format FROM $address_formattable WHERE address_format_id = '" . intval($address_format_id) . "'");
    $address_format = $address_format_result->fields;

    $company = addslashes((string) $address['company']);
    $firstname = addslashes((string) $address['firstname']);
    $lastname = addslashes((string) $address['lastname']);
    $street = addslashes((string) $address['street_address']);
    $city = addslashes((string) $address['city']);
    $state = addslashes((string) $address['state']);
    $country_id = $address['country_id'];
    $zone_id = $address['zone_id'];
    $postcode = addslashes((string) $address['postcode']);
    $zip = $postcode;
    $country = oos_get_country_name($country_id);
    $state = oos_get_zone_code($country_id, $zone_id, $state);

    if ($html) {
        // HTML Mode
        $HR = '<hr>';
        $hr = '<hr>';
        if (($boln == '') && ($eoln == "\n")) { // Values not specified, use rational defaults
            $CR = '<br />';
            $cr = '<br />';
            $eoln = $cr;
        } else { // Use values supplied
            $CR = $eoln . $boln;
            $cr = $CR;
        }
    } else {
        // Text Mode
        $CR = $eoln;
        $cr = $CR;
        $HR = '----------------------------------------';
        $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($firstname == '') {
        $firstname = addslashes((string) $address['name']);
    }
    if ($country == '') {
        $country = addslashes((string) $address['country']);
    }
    if ($state != '') {
        $statecomma = $state . ', ';
    }

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");
    $address = stripslashes((string) $address);

    if ((ACCOUNT_COMPANY == 'true') && (oos_is_not_null($company))) {
        $address = $company . $cr . $address;
    }

    return $boln . $address . $eoln;
}


 /**
  * Return a formatted address
  *
  * @param $customers_id
  * @param $address_id
  * @param $html
  * @param $boln
  * @param $eoln
  * @param $address
  * @param $html
  * @param $boln
  * @param $eoln
  */
function oos_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n")
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $address_booktable = $oostable['address_book'];
    $query = "SELECT entry_firstname AS firstname, entry_lastname AS lastname, entry_company AS company,
                     entry_street_address AS street_address, entry_city AS city,
                     entry_postcode AS postcode, entry_state AS state, entry_zone_id AS zone_id,
                     entry_country_id AS country_id
              FROM  $address_booktable
              WHERE  customers_id = '" . intval($customers_id) . "' AND
                     address_book_id = '" . intval($address_id) . "'";
    $address = $dbconn->GetRow($query);

    $format_id = oos_get_address_format_id($address['country_id']);

    return oos_address_format($format_id, $address, $html, $boln, $eoln);
}


/**
 * Counts the customer address book entries
 *
 * @param  string $id
 * @param  bool   $check_session
 * @return int
 */
function oos_count_customer_address_book_entries($id = '', $check_session = true)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    if (is_numeric($id) == false) {
        if ($_SESSION['customer_id']) {
            $id = $_SESSION['customer_id'];
        } else {
            return 0;
        }
    }

    if ($check_session == true) {
        if (($_SESSION['customer_id'] == false) || ($id != $_SESSION['customer_id'])) {
            return 0;
        }
    }

    $address_booktable = $oostable['address_book'];
    $addresses_query = "SELECT COUNT(*) AS total
                        FROM $address_booktable
                        WHERE customers_id = " . intval($id);
    $addresses = $dbconn->Execute($addresses_query);

    return $addresses->fields['total'];
}
