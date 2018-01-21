<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: validations.php,v 1.11 2003/02/11 01:31:02 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/**
 * validations
 *
 * @package validations
 * @copyright (C) 2006 by the MyOOS Development Team.
 * @license GPL <http://www.gnu.org/licenses/gpl.html>
 * @link https://www.oos-shop.de
 */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

/**
 * Valid e-Mail - Addresses
 *
 * This function is converted from a JavaScript written by
 * Sandeep V. Tamhankar (stamhankar@hotmail.com). The original JavaScript
 * is available at http://javascript.internet.com
 *
 * @param $sEmail
 * @return boolean
 */
function oos_validate_is_email($value) {
 
    if (!is_string($value)) return FALSE;
 
    // in case value is several addresses separated by newlines
    $_addresses = preg_split('![\n\r]+!', $value);

    foreach($_addresses as $_address) {
		$_is_valid = !(preg_match('!@.*@|\.\.|\,|\;!', $_address) ||
	        !preg_match('!^.+\@(\[?)[a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$!', $_address));
        
        if(!$_is_valid)
            return FALSE;
    }
    return TRUE;
}


/**
 * test if a value is a valid URL
 *
 * @param string $sUrl the value being tested
 */
function oos_validate_is_url($sUrl) {
   if (strlen($sUrl) == 0) {
     return FALSE;
   }

   return preg_match('!^http(s)?://[\w-]+\.[\w-]+(\S+)?$!i', $sUrl);
}

