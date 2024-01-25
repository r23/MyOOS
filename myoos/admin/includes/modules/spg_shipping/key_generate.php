<?php
/**
   ----------------------------------------------------------------------
   $Id: key_generate.php,v 1.1 2007/06/08 14:09:43 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: key_generateb.php
   ----------------------------------------------------------------------
   P&G Shipping Module Version 0.4 12/03/2002
   osCommerce Shipping Management Module
   Copyright (c) 2002  - Oliver Baelde
   http://www.francecontacts.com
   dev@francecontacts.com
   - eCommerce Solutions development and integration -

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

function RandomPassword($passwordLength)
{
    $newkey = "";

    for ($index = 1; $index <= $passwordLength; $index++) {
        // Pick random number between 1 and 62
        $randomNumber = random_int(1, 62);
        // Select random character based on mapping.
        if ($randomNumber < 11) {
            $newkey .= Chr($randomNumber + 48 - 1);
        } // [ 1,10] => [0,9]
        elseif ($randomNumber < 37) {
            $newkey .= Chr($randomNumber + 65 - 10);
        } // [11,36] => [A,Z]
        else {
            $newkey .= Chr($randomNumber + 97 - 36);
        } // [37,62] => [a,z]
    }
    return $newkey;
}


$newkey = RandomPassword(24);
$dbconn->Execute("UPDATE " . $oostable['manual_info'] . " SET man_key  = '" . oos_db_input($newkey) . "', man_key2  = '', man_key3  = '' WHERE man_info_id = '1' ");
