<?php
/* ----------------------------------------------------------------------
   $Id: key_generate.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  function RandomPassword( $passwordLength ) {
    $newkey = "";

    for ($index = 1; $index <= $passwordLength; $index++) {
      // Pick random number between 1 and 62
      $randomNumber = rand(1, 62);
      // Select random character based on mapping.
      if ($randomNumber < 11)
        $newkey .= Chr($randomNumber + 48 - 1); // [ 1,10] => [0,9]
      else if ($randomNumber < 37)
        $newkey .= Chr($randomNumber + 65 - 10); // [11,36] => [A,Z]
      else
        $newkey .= Chr($randomNumber + 97 - 36); // [37,62] => [a,z]
      }
    return $newkey;
  }

  $passwordLength = 24 ;
  $newkey=RandomPassword($passwordLength);

  $dbconn->Execute("UPDATE " . $oostable['manual_info'] . " SET man_key  = '" . $newkey . "', man_key2  = '', man_key3  = '' WHERE man_info_id = '1' ");
?>
