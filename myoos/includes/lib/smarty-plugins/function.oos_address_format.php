<?php
/**
   ----------------------------------------------------------------------
   $Id: function.oos_address_format.php,v 1.1 2007/06/08 13:34:16 r23 Exp $

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

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     oos_address_format
 * Version:  1.0
 * Date:
 * Purpose:
 *
 *
 * Install:  Drop into the plugin directory
 * Author:
 * -------------------------------------------------------------
 */

function smarty_function_oos_address_format($params, &$smarty)
{
    include_once SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php';

    $address_format_id = '';
    $address = '';
    $html = '';
    $boln = '';
    $eoln = '<br />';

    foreach ($params as $_key => $_val) {
        ${$_key} = smarty_function_escape_special_chars($_val);
    }

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $address_formattable = $oostable['address_format'];
    $query = "SELECT address_format AS format
             FROM $address_formattable
             WHERE address_format_id = '" . intval($address_format_id) . "'";
    $address_format = $dbconn->GetRow($query);


	$company = (isset($address['company'])) ? oos_db_output($address['company']) : '';
	$firstname = (isset($address['firstname'])) ? oos_db_output($address['firstname']) : '';
	$lastname = (isset($address['lastname'])) ? oos_db_output($address['lastname']) : '';
	$street = (isset($address['street_address'])) ? oos_db_output($address['street_address']) : '';	
	$city = (isset($address['city'])) ? oos_db_output($address['city']) : '';
	$state = (isset($address['state'])) ? oos_db_output($address['state']) : '';
	$country_id = (isset($address['country_id'])) ? intval($address['country_id']) : STORE_COUNTRY;
	$country_id = (isset($address['country_id'])) ? intval($address['country_id']) : STORE_COUNTRY;	
	$zone_id = (isset($address['zone_id'])) ? oos_db_output($address['zone_id']) : '';	
	$zip = (isset($address['postcode'])) ? oos_db_output($address['postcode']) : '';

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
		$firstname = (isset($address['name'])) ? oos_db_output($address['name']) : '';
    }
    if ($country == '') {
		$country = (isset($address['country'])) ? oos_db_output($address['country']) : '';
    }
    if ($state != '') {
        $statecomma = $state . ', ';
    }

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");
    $address = stripslashes($address);

    if ((ACCOUNT_COMPANY == 'true') && (oos_is_not_null($company))) {
        $address = $company . $cr . $address;
    }

    print $boln . $address . $eoln;
}
