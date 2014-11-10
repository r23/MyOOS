<?php
/* ----------------------------------------------------------------------
   $Id: function_compatibility.php,v 1.4 2008/08/09 04:52:04 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );


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
  * Based from work by Ilia Alshanetsky (Advanced PHP Security)
  */
  if ((int)get_magic_quotes_gpc() > 0) {
    $in = array(&$_GET, &$_POST, &$_COOKIE);

    while (list($k, $v) = each($in)) {
      foreach ($v as $key => $val) {
        if (!is_array($val)) {
          $in[$k][$key] = stripslashes($val);

          continue;
        }

        $in[] =& $in[$k][$key];
      }
    }

    unset($in);
    unset($k);
    unset($v);
    unset($key);
    unset($val);
  }


 /**
  * Fix for PHP as CGI hosts that set SCRIPT_FILENAME to 
  * something ending in php.cgi for all requests
  */
  if (strpos(php_sapi_name(), 'cgi') !== false) {
    $_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];
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
  * @version     $Revision: 1.4 $
  * @internal    resource_context is not supported
  * @since       PHP 5
  */
  if (!function_exists('file_get_contents')) {
    function file_get_contents($filename, $incpath = false, $resource_context = null) {
     if (false === $fh = fopen($filename, 'rb', $incpath)) {
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
          if(eregi('^' . $host, $line)) {
            return true;
          }
        }
      }

      return false;
    }
  }


 /**
  * ctype_alnum() natively supported from PHP 4.3
  */
  if (!function_exists('ctype_alnum')) {
    function ctype_alnum($string) {
      return (eregi('^[a-z0-9]*$', $string) > 0);
    }
  }


 /**
  * ctype_xdigit() natively supported from PHP 4.3
  */
  if (!function_exists('ctype_xdigit')) {
    function ctype_xdigit($string) {
      return (eregi('^([a-f0-9][a-f0-9])*$', $string) > 0);
    }
  }


 /**
 * is_a() natively supported from PHP 4.2
 */
  if (!function_exists('is_a')) {
    function is_a($object, $class) {
      if (!is_object($object)) {
        return false;
      }

      if (get_class($object) == strtolower($class)) {
        return true;
      } else {
        return is_subclass_of($object, $class);
      }
    }
  }


 /**
  * floatval() natively supported from PHP 4.2
  */
  if (!function_exists('floatval')) {
    function floatval($float) {
      return doubleval($float);
    }
  }


 /**
  * stream_get_contents() natively supported from PHP 5.0
  */
  if (!function_exists('stream_get_contents')) {
    function stream_get_contents($resource) {
      $result = '';

      if (is_resource($resource)) {
        while (!feof($resource)) {
          $result .= @fread($resource, 2048);
        }
      }

      return $result;
    }
  }


 /**
  * sha1() natively supported from PHP 4.3
  */
  if (!function_exists('sha1')) {
    function sha1($source) {
      if (function_exists('mhash')) {
        if (($hash = @mhash(MHASH_SHA1, $source)) !== false) {
          return bin2hex($hash);
        }
      }

      if (!function_exists('calc_sha1')) {
        include('ext/sha1/sha1.php');
      }

      return calc_sha1($source);
    }
  }


 /**
  * html_entity_decode() natively supported from PHP 4.3
  */
  if ( !function_exists( 'html_entity_decode' ) ) {
    function html_entity_decode( $string )  {
      // Replace numeric entities
      $string = preg_replace( '~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string );
      $string = preg_replace( '~&#([0-9]+);~e', 'chr(\\1)', $string );

      // Replace literal entities
      $trans_tbl = get_html_translation_table( HTML_ENTITIES );
      $trans_tbl = array_flip( $trans_tbl );
      return strtr( $string, $trans_tbl );
    }
  }


?>
