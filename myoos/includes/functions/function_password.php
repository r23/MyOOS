<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2021 by the MyOOS Development Team.
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
 * @param $password
 * @param $sEncrypted
 * @return boolean
 */
function oos_validate_password($password, $hash) {

	if (oos_is_not_null($password) && oos_is_not_null($hash)) {
		return password_verify($password, $hash);;
	}

    return false;
}


/**
 * This function makes a new password from a plaintext password.
 *
 * @param $password
 * @return string
 */
function oos_encrypt_password($password) {

	$hash = password_hash($password, PASSWORD_DEFAULT);

    return $hash;
}
