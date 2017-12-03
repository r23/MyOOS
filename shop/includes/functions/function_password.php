<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
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
		if (!class_exists('PasswordHash')) {
			require_once MYOOS_INCLUDE_PATH . '/includes/lib/phpass/PasswordHash.php';
		}

		$oHasher = new PasswordHash( 8, TRUE );

		return $oHasher->CheckPassword($sPlain, $sEncrypted);
	}

    return FALSE;
}


/**
 * This function makes a new password from a plaintext password.
 *
 * @param $sPlain
 * @return string
 */
function oos_encrypt_password($sPlain) {

	if (!class_exists('PasswordHash')) {
		require_once MYOOS_INCLUDE_PATH . '/includes/lib/phpass/PasswordHash.php';
	}

	$oHasher = new PasswordHash( 8, TRUE );

    return $oHasher->HashPassword($sPlain);
}
