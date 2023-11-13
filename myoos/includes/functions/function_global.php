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
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


/**
 * Filter a string value by removing null bytes and HTML tags and encoding quotes and
 * special characters.
 *
 * This function mimics the behavior of the deprecated FILTER_SANITIZE_STRING filter,
 * which was used to sanitize strings by removing HTML tags and encoding quotes and
 * certain special characters. This filter was unclear in its purpose and behavior
 * and was therefore deprecated as of PHP 8.1.0. It is recommended to use
 * htmlspecialchars () instead.
 *
 * @param  mixed $string The value to be filtered. If it is not a string, null is returned.
 * @return mixed The filtered value as a string, or null if the input is not a string.
 * @see    https://www.php.net/manual/en/filter.filters.sanitize.php
 * @see    https://www.php.net/manual/en/function.htmlspecialchars.php
 */
function filter_string_polyfill(mixed $string): mixed
{
    // Check if the input is a valid string value
    if (!is_string($string)) {
        // If not, return null
        return null;
    }
    // Otherwise, perform the filtering as usual
    $str = preg_replace('/\\x00|< [^>]*>?/', '', $string);
    return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
}


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

    $year = substr((string) $raw_date, 0, 4);
    $month = intval(substr((string) $raw_date, 5, 2));
    $day = intval(substr((string) $raw_date, 8, 2));
    $hour = intval(substr((string) $raw_date, 11, 2));
    $minute = intval(substr((string) $raw_date, 14, 2));
    $second = intval(substr((string) $raw_date, 17, 2));

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

    $year = substr((string) $raw_date, 0, 4);
    $month = intval(substr((string) $raw_date, 5, 2));
    $day = intval(substr((string) $raw_date, 8, 2));
    $hour = intval(substr((string) $raw_date, 11, 2));
    $minute = intval(substr((string) $raw_date, 14, 2));
    $second = intval(substr((string) $raw_date, 17, 2));

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
    if (str_ends_with((string) $sPath, '/')) {
        $sPath = substr((string) $sPath, 0, -1);
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
    $pieces = explode('{', (string) $uprid);

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
        if (($value ?? '') != '' && (strtolower((string) $value) != 'null') && (strlen(trim((string) $value)) > 0)) {
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
        mt_srand((float)microtime() * 1_000_000);
        $seeded = true;
    }

    if (isset($min) && isset($max)) {
        if ($min >= $max) {
            return $min;
        } else {
            return random_int($min, $max);
        }
    } else {
        return random_int(0, mt_getrandmax());
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
