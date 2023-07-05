<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: validations.php,v 1.11 2003/02/11 01:31:02 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * validations
 *
 * @package   validations
 * @copyright (C) 2013 by the MyOOS Development Team.
 * @license   GPL <http://www.gnu.org/licenses/gpl.html>
 * @link      https://www.oos-shop.de
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

/**
 * Valid e-Mail - Addresses
 *
 * @param  $value
 * @return boolean
 */
function is_email($value)
{
    if (!is_string($value)) {
        return false;
    }

    //Reject line breaks in addresses; it's valid RFC5322, but not RFC5321
    if (strpos($value, "\n") !== false or strpos($value, "\r") !== false) {
        return false;
    }
    return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
}


/**
 * test if a value is a valid URL
 *
 * @param string $sUrl the value being tested
 */
function oos_validate_is_url($sUrl)
{
    if (strlen($sUrl ?? '') == 0) {
        return false;
    }

    return preg_match('!^http(s)?://[\w-]+\.[\w-]+(\S+)?$!i', $sUrl);
}
