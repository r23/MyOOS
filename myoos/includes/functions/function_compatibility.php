<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: compatibility.php,v 1.22 2004/07/22 16:36:22 hpdl
         compatibility.php,v 1.18 2003/02/11 01:31:01 hpdl
         compatibility.php 1498 2007-03-29 14:04:50Z hpdl $
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2007 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * For compatibility
 *
 * @package core
 * @access  public
 *
 * @author r23 <info@r23.de>
 * @since  OOS 1.3.1
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


/**
 * Forcefully disable register_globals if enabled
 *
 * Based from work by Richard Heyes (http://www.phpguru.org)
 */
if ((int)ini_get('register_globals') > 0) {
    if (isset($_REQUEST['GLOBALS'])) {
        die('GLOBALS overwrite attempt detected');
    }

    $noUnset = ['GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES'];

    $input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : []);

    foreach ($input as $k => $v) {
        if (!in_array($k, $noUnset) && isset($GLOBALS[$k])) {
            $GLOBALS[$k] = null;
            unset($GLOBALS[$k]);
        }
    }

    unset($noUnset);
    unset($input);
    unset($k);
    unset($v);
}



/**
 * Fix for PHP as CGI hosts that set SCRIPT_FILENAME to
 * something ending in php.cgi for all requests
 */
if (strpos(php_sapi_name(), 'cgi') !== false) {
    //   $_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];
}


/**
 * Fix for Dreamhost and other PHP as CGI hosts
 */
if (strpos($_SERVER['SCRIPT_NAME'], 'php.cgi') !== false) {
    unset($_SERVER['PATH_INFO']);
}


/**
 * Replace file_get_contents()
 *
 * @category PHP
 * @package  PHP_Compat
 * @link     http://php.net/function.file_get_contents
 * @author   Aidan Lister <aidan - php - net>
 * @version  $Revision: 1.12 $
 * @internal resource_context is not supported
 * @since    PHP 5
 */
if (!function_exists('file_get_contents')) {
    function file_get_contents($filename, $incategory = false, $resource_context = null)
    {
        if (false === $fh = fopen($filename, 'rb', $incategory)) {
            user_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
            return false;
        }

        clearstatcache();
        if ($fsize = @filesize($filename)) {
            $data = fread($fh, $fsize);
        } else {
            $data = '';
            while (!feof($fh)) {
                $data .= fread($fh, 8192);
            }
        }

        fclose($fh);
        return $data;
    }
}


/**
 * checkdnsrr() not implemented on Microsoft Windows platforms
 */
if (!function_exists('checkdnsrr')) {
    function checkdnsrr($host, $type)
    {
        if (!empty($host) && !empty($type)) {
            @exec('nslookup -type=' . escapeshellarg($type) . ' ' . escapeshellarg($host), $output);

            foreach ($output as $k => $line) {
                if (preg_match('/^' . $host . '/i', $line)) {
                    return true;
                }
            }
        }

        return false;
    }
}

if (!function_exists('http_response_code')) {
    function http_response_code($code = null)
    {
        if ($code !== null) {
            switch ($code) {
            case 100: $text = 'Continue';
                break;
            case 101: $text = 'Switching Protocols';
                break;
            case 200: $text = 'OK';
                break;
            case 201: $text = 'Created';
                break;
            case 202: $text = 'Accepted';
                break;
            case 203: $text = 'Non-Authoritative Information';
                break;
            case 204: $text = 'No Content';
                break;
            case 205: $text = 'Reset Content';
                break;
            case 206: $text = 'Partial Content';
                break;
            case 300: $text = 'Multiple Choices';
                break;
            case 301: $text = 'Moved Permanently';
                break;
            case 302: $text = 'Moved Temporarily';
                break;
            case 303: $text = 'See Other';
                break;
            case 304: $text = 'Not Modified';
                break;
            case 305: $text = 'Use Proxy';
                break;
            case 400: $text = 'Bad Request';
                break;
            case 401: $text = 'Unauthorized';
                break;
            case 402: $text = 'Payment Required';
                break;
            case 403: $text = 'Forbidden';
                break;
            case 404: $text = 'Not Found';
                break;
            case 405: $text = 'Method Not Allowed';
                break;
            case 406: $text = 'Not Acceptable';
                break;
            case 407: $text = 'Proxy Authentication Required';
                break;
            case 408: $text = 'Request Time-out';
                break;
            case 409: $text = 'Conflict';
                break;
            case 410: $text = 'Gone';
                break;
            case 411: $text = 'Length Required';
                break;
            case 412: $text = 'Precondition Failed';
                break;
            case 413: $text = 'Request Entity Too Large';
                break;
            case 414: $text = 'Request-URI Too Large';
                break;
            case 415: $text = 'Unsupported Media Type';
                break;
            case 500: $text = 'Internal Server Error';
                break;
            case 501: $text = 'Not Implemented';
                break;
            case 502: $text = 'Bad Gateway';
                break;
            case 503: $text = 'Service Unavailable';
                break;
            case 504: $text = 'Gateway Time-out';
                break;
            case 505: $text = 'HTTP Version not supported';
                break;
            default:
                exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
            }

            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

            header($protocol . ' ' . $code . ' ' . $text);

            $GLOBALS['http_response_code'] = $code;
        } else {
            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        }

        return $code;
    }
}

/**
 * Replace apache_request_headers()
 *
 * @category PHP
 * @package  PHP_Compat
 * @link     http://php.net/function.apache-request-headers.php
 * @author   uli dot staerk at globalways dot net
 * @author   limalopex dot eisfux dot de
*/
if (!function_exists('apache_request_headers')) {
    function apache_request_headers()
    {
        $arh = [];
        $rx_http = '/\AHTTP_/';
        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = [];
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', strtolower($arh_key));
                if (count($rx_matches) > 0 and strlen($arh_key ?? '') > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    }
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $arh['Content-Type'] = $_SERVER['CONTENT_TYPE'];
        }
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $arh['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
        }
        return($arh);
    }
}
