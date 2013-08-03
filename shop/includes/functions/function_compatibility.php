<?php
/* ----------------------------------------------------------------------
   $Id: function_compatibility.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: compatibility.php,v 1.22 2004/07/22 16:36:22 hpdl
         compatibility.php,v 1.18 2003/02/11 01:31:01 hpdl
         compatibility.php 1498 2009-03-29 14:04:50Z hpdl $
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2009 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/**
 * For compatibility
 *
 * @package  core
 * @access   public
 *
 * @author   r23 <info@r23.de>
 * @since    OOS 1.3.1
 */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


/**
 * Forcefully disable register_globals if enabled
 *
 * Based from work by Richard Heyes (http://www.phpguru.org)
 */
if ((int)ini_get('register_globals') > 0) {
    if (isset($_REQUEST['GLOBALS'])) {
        die('GLOBALS overwrite attempt detected');
    }

    $noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');

    $input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());

    foreach ($input as $k => $v) {
        if (!in_array($k, $noUnset) && isset($GLOBALS[$k])) {
            $GLOBALS[$k] = NULL;
            unset($GLOBALS[$k]);
        }
    }

    unset($noUnset);
    unset($input);
    unset($k);
    unset($v);
}


/**
 * Forcefully disable magic_quotes_gpc if enabled
 *
 * @link http://www.oos-shop.de/doc/php_manual_de/html/security.magicquotes.disabling.html
 */
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);

        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
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
 * @category    PHP
 * @package     PHP_Compat
 * @link        http://php.net/function.file_get_contents
 * @author      Aidan Lister <aidan - php - net>
 * @version     $Revision: 1.12 $
 * @internal    resource_context is not supported
 * @since       PHP 5
 */
if (!function_exists('file_get_contents')) {
    function file_get_contents($filename, $incategory = false, $resource_context = null) {
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
    function checkdnsrr($host, $type) {
      if(!empty($host) && !empty($type)) {
          @exec('nslookup -type=' . escapeshellarg($type) . ' ' . escapeshellarg($host), $output);

          foreach ($output as $k => $line) {
              if(preg_match('/^' . $host . '/i', $line)) {
                  return true;
              }
          }
      }

      return false;
    }
}

