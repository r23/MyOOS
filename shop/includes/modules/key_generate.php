<?php
/* ----------------------------------------------------------------------
   $Id: key_generate.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: key_generate.php
   ----------------------------------------------------------------------
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
    $newkey2 = "";
    for ($index = 1; $index <= $passwordLength; $index++) {
      // Pick random number between 1 and 62
      $randomNumber = rand(1, 62);
      // Select random character based on mapping.
      if ($randomNumber < 11)
        $newkey2 .= Chr($randomNumber + 48 - 1); // [ 1,10] => [0,9]
      else if ($randomNumber < 37)
        $newkey2 .= Chr($randomNumber + 65 - 10); // [11,36] => [A,Z]
      else
        $newkey2 .= Chr($randomNumber + 97 - 36); // [37,62] => [a,z]
    }
    return $newkey2;
  }

  $passwordLength = 24 ;
  $newkey2 = RandomPassword($passwordLength);

  $manual_infotable = $oostable['manual_info'];
  $dbconn->Execute("UPDATE $manual_infotable
                    SET man_key2  = '" . oos_db_input($newkey2) . "'
                    WHERE man_info_id = '1'");

