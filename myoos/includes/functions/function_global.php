<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * global
 *
 * @package   global
 * @copyright (C) 2022 by the MyOOS Development Team.
 * @license   GPL <http://www.gnu.org/licenses/gpl.html>
 * @link      https://www.oos-shop.de
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


/**
 * Output a raw date string in the selected locale date format
 * $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
 *
 * @param  $raw_date
 * @return string
 */
function oos_date_long($raw_date)
{
    if (($raw_date == '0000-00-00 00:00:00') || ($raw_date == '')) {
        return false;
    }

    $locale = THE_LOCALE;
    $dateType = IntlDateFormatter::FULL;//type of date formatting
    $timeType = IntlDateFormatter::NONE;//type of time formatting setting to none, will give you date itself
    $formatter = new IntlDateFormatter($locale, $dateType, $timeType);
    $dateTime = new DateTime($raw_date);

    return $formatter->format($dateTime);
}


/**
 * Output a raw date string in the selected locale date format
 * $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
 *
 * @param  $raw_date
 * @return string
 */
function oos_date_short($raw_date)
{
    if (($raw_date == '0000-00-00 00:00:00') || ($raw_date == '')) {
        return false;
    }

    $year = substr($raw_date, 0, 4);
    $month = intval(substr($raw_date, 5, 2));
    $day = intval(substr($raw_date, 8, 2));
    $hour = intval(substr($raw_date, 11, 2));
    $minute = intval(substr($raw_date, 14, 2));
    $second = intval(substr($raw_date, 17, 2));

    if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
        return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    } else {
        return preg_match('/2037' . '$/', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }
}


/**
 * Output a raw date string in the selected locale date format
 * $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
 *
 * @param  $raw_date
 * @return string
 */
function oos_date_short_schema($raw_date)
{
    if (($raw_date == '0000-00-00 00:00:00') || ($raw_date == '')) {
        return false;
    }

    $year = substr($raw_date, 0, 4);
    $month = intval(substr($raw_date, 5, 2));
    $day = intval(substr($raw_date, 8, 2));
    $hour = intval(substr($raw_date, 11, 2));
    $minute = intval(substr($raw_date, 14, 2));
    $second = intval(substr($raw_date, 17, 2));

    if (@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year) {
        return date('Y-m-d', mktime($hour, $minute, $second, $month, $day, $year));
    } else {
        return preg_match('/2037' . '$/', $year, date('Y-m-d', mktime($hour, $minute, $second, $month, $day, 2037)));
    }
}


/**
 * Return a local directory path (without trailing slash)
 *
 * @param  $sPath
 * @return string
 */
function oos_get_local_path($sPath)
{
    if (substr($sPath, -1) == '/') {
        $sPath = substr($sPath, 0, -1);
    }

    return $sPath;
}


/**
 * Return a product ID from a product ID with attributes
 *
 * @param  $uprid
 * @return string
 */
function oos_get_product_id($uprid)
{
    $pieces = explode('{', $uprid);

    if (is_numeric($pieces[0])) {
        return $pieces[0];
    } else {
        return false;
    }
}


function oos_is_not_null($value)
{
    if (is_array($value)) {
        if (!empty($value)) {
            return true;
        } else {
            return false;
        }
    } else {
		if (($value ?? '') != '' && (strtolower($value) != 'null') && (strlen(trim((string) $value)) > 0)) {
            return true;
        } else {
            return false;
        }
    }
}



function oos_empty($value)
{
    if (is_array($value)) {
        if (sizeof($value) > 0) {
            return false;
        } else {
            return true;
        }
    } else {
		if ((strtolower($value ?? '') != 'null') && (strlen(trim((string) $value)) > 0)) {
            return false;
        } else {
            return true;
        }
    }
}


/**
 * Return a random value
 *
 * @param  $min
 * @param  $max
 * @return string
 */
function oos_rand($min = null, $max = null)
{
    static $seeded;

    if (!isset($seeded)) {
        mt_srand((float)microtime()*1000000);
        $seeded = true;
    }

    if (isset($min) && isset($max)) {
        if ($min >= $max) {
            return $min;
        } else {
            return mt_rand($min, $max);
        }
    } else {
        return mt_rand();
    }
}

function oos_create_random_value($length, $type = 'mixed')
{
    if (($type != 'mixed') && ($type != 'chars') && ($type != 'digits')) {
        return false;
    }

    $rand_value = '';
    while (strlen($rand_value ?? '') < $length) {
        if ($type == 'digits') {
            $char = oos_rand(0, 9);
        } else {
            $char = chr(oos_rand(0, 255));
        }
        if ($type == 'mixed') {
            if (preg_match('!^[a-z0-9]$!', $char)) {
                $rand_value .= $char;
            }
        } elseif ($type == 'chars') {
            if (preg_match('!^[a-z]$!', $char)) {
                $rand_value .= $char;
            }
        } elseif ($type == 'digits') {
            if (preg_match('!^[0-9]$!', $char)) {
                $rand_value .= $char;
            }
        }
    }

    return $rand_value;
}


/**
 * Return XML
 *
 * @param  $url
 * @return xml
 */
function oos_load_xml($url)
{
    if (empty($url)) {
        return;
    }

    if (ini_get('allow_url_fopen')) {
        return simplexml_load_file($url);
    }

    if (function_exists('curl_init')) {
        // create a new cURL resource
        // $curl is the handle of the resource
        $curl = curl_init();

        // set the URL and other options
        curl_setopt($curl, CURLOPT_URL, $url);

        //  return the response body
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);

        // execute and pass the result
        return simplexml_load_string(curl_exec($curl));
    }
}
