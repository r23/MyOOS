<?php
/* ----------------------------------------------------------------------
   $Id: function_password.php 425 2013-06-16 07:05:28Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_funcs.php,v 1.10 2003/02/11 01:31:02 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

 /**
  * This funstion validates a plain text password with an
  * encrpyted password
  *
  * @param $sPlain
  * @param $sEncrypted
  * @return boolean
  */
  function oos_validate_password($sPlain, $sEncrypted) {

    if (oos_is_not_null($sPlain) && oos_is_not_null($sEncrypted)) {
      // split apart the hash / salt
      $aStack = explode(':', $sEncrypted);

      if (count($aStack) != 2) return false;

      if (md5($aStack[1] . $sPlain) == $aStack[0]) {
        return true;
      }
    }

    if (oos_is_not_null($_COOKIE['password']) && oos_is_not_null($sEncrypted)) {
      if ($_COOKIE['password'] == $sEncrypted) {
        return true;
      }
    }

    return false;
  }


 /**
  * This function makes a new password from a plaintext password. 
  *
  * @param $sPlain
  * @return string
  */
  function oos_encrypt_password($sPlain) {
    $sPassword = '';

    for ($i=0; $i<10; $i++) {
      $sPassword .= oos_rand();
    }

    $sSalt = substr(md5($sPassword), 0, 2);

    $sPassword = md5($sSalt . $sPlain) . ':' . $sSalt;

    return $sPassword;
  }

