<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.151 2003/02/07 21:46:49 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

 /**
  * ready operating system output
  * <br>
  * Gets a variable, cleaning it up such that any attempts
  * to access files outside of the scope of the PostNuke
  * system is not allowed
  *
  * @author    PostNuke Content Management System
  * @copyright Copyright (C) 2001 by the Post-Nuke Development Team.
  * @version   Revision: 2.0  - changed by Author: r23  on Date: 2004/01/12 06:02:08
  * @access    private
  * @param     let variable to prepare
  * @param     ...
  * @returns   string/array
  * in, otherwise an array of prepared variables
  * @noRector
  */
function oos_var_prep_for_os()
{
    static $search = [
        '!\.\./!si',
        // .. (directory traversal)
        '!^.*://!si',
        // .*:// (start of URL)
        '!/!si',
        // Forward slash (directory traversal)
        '!\\\\!si',
    ]; // Backslash (directory traversal)

    static $replace = ['', '', '_', '_'];

    $resarray = [];
	
	// Pass through each argument that is passed to the function
    foreach (func_get_args() as $ourvar) {
        $ourvar = preg_replace($search, $replace, $ourvar);

        // Clean up the string further with oos_sanitize_string()
        $ourlet = oos_sanitize_string($ourvar);


        // Add the cleaned string to the result array
        array_push($resarray, $ourvar);
    }

    // Return vars
    if (func_num_args() == 1) {
        return $resarray[0];
    } else {
        return $resarray;
    }
}

