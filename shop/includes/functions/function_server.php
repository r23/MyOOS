<?php
/* ----------------------------------------------------------------------
   $Id: function_server.php 425 2013-06-16 07:05:28Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: xarServer.php 1.62 03/10/28 19:11:18+01:00 mikespub
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /**
   * HTTP Protocol Server/Request/Response utilities
   *
   * @package server
   * @copyright (C) 2002 by the Xaraya Development Team.
   * @license GPL <http://www.gnu.org/licenses/gpl.html>
   * @link http://www.xaraya.com
   * @author Marco Canini <marco@xaraya.com>
   */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  /**
   * Gets a server variable
   *
   * Returns the value of $name server variable.
   * Accepted values for $name are exactly the ones described by the
   * {@link http://www.php.net/manual/en/reserved.variables.html#reserved.variables.server PHP manual}.
   * If the server variable doesn't exist void is returned.
   *
   * Last Editor: Author: r23
   * @author  Marco Canini <marco@xaraya.com>, Michel Dalle
   * @access  public
   * @param   name string the name of the variable
   * @return  mixed value of the variable
   */
   function oos_server_get_var($sKey) {
     if (isset($_SERVER[$sKey])) {
       return $_SERVER[$sKey];
     }
     if (isset($_ENV[$sKey])) {
       return $_ENV[$sKey];
     }

     if ($val = getenv($sKey)) {
       return $val;
     }
     return; // we found nothing here
   }


  /**
   * Has a server variable
   *
   * @author  r23 <info@r23.de>
   * @access  public
   * @param   string
   * @return  mixed
   */
   function oos_server_has_var($sKey) {
     if (isset($_SERVER[$sKey])) {
       return true;
     }
     return (bool)getenv($sKey);
   }


  /**
   * Gets the host name
   *
   * Returns the server host name fetched from HTTP headers when possible.
   * The host name is in the canonical form (host + : + port) when the port is different than 80.
   * 
   * Last Editor: Author: r23
   * @author Marco Canini <marco@xaraya.com>
   * @access public
   * @return string HTTP host name
   */
   function oos_server_get_host() {
     $sServer = oos_server_get_var('HTTP_HOST');
     if (empty($sServer)) {
       // HTTP_HOST is reliable only for HTTP 1.1
       $sServer = oos_server_get_var('SERVER_NAME');
       $port = oos_server_get_var('SERVER_PORT');
       if ($port != '80') $sServer .= ":$port";
     }
     return $sServer;
   }


  /**
   * Gets the current protocol
   *
   * Returns the HTTP protocol used by current connection, it could be 'http' or 'https'.
   *
   * Last Editor: Author: r23
   * @author Marco Canini <marco@xaraya.com>
   * @access public
   * @return string current HTTP protocol
   */
   function oos_server_get_protocol() {
     $sProtocol = 'http';
     if (ENABLE_SSL == 'true') {
       if (strtolower(oos_server_has_var('HTTPS')) == 'on'
         || oos_server_has_var('SSL_PROTOCOL')) {
         $sProtocol = 'https';
       }
     }
     return $sProtocol . '://';
   }


  /**
   * Get base URI for oos
   *
   * @access public
   * @return string base URI for oos
   */
   function oos_server_get_base_uri() {

    // Get the name of this URI
    $sPath = oos_server_get_var('REQUEST_URI');

    if (empty($sPath)) {
      // REQUEST_URI was empty or pointed to a path
      // adapted patch from Chris van de Steeg for IIS
      // Try SCRIPT_NAME
      $sPath = oos_server_get_var('SCRIPT_NAME');
      if (empty($sPath)) {
        // No luck there either
        // Try looking at PATH_INFO
        $sPath = oos_server_get_var('PATH_INFO');
      }
    }

    $sPath = preg_replace('/[#\?].*/', '', $sPath);

    $sPath = preg_replace('/\.php\/.*$/', '', $sPath);
    if (substr($sPath, -1, 1) == '/') {
      $sPath .= 'dummy';
    }
    $sPath = dirname($sPath);

    if (preg_match('!^[/\\\]*$!', $sPath)) {
      $sPath = '';
    }

    return $sPath;
  }


  /**
   * get base URL for OOS
   *
   * @access public
   * @return string base URL for OOS
   */
   function oos_server_get_base_url() {
     static $sBaseurl = null;

     if (isset($sBaseurl))  return $sBaseurl;

     $sServer = oos_server_get_host();
     $sProtocol = oos_server_get_protocol();
     $sPath = oos_server_get_base_uri();

     $sBaseurl = trim($sProtocol . $sServer . $sPath . '/');
     return $sBaseurl;
  }


 /**
  * get top level domain
  *
  * @copyright (C) 2003 by osCommerce.
  * @license GPL <http://www.gnu.org/licenses/gpl.html>
  * @link http://www.oscommerce.com
  * @access public
  * @param $sUrl
  * @return  mixed
  */
  function oos_server_get_top_level_domain($sUrl) {
    if (strpos($sUrl, '://')) {
      $sUrl = parse_url($sUrl);
      $sUrl = $sUrl['host'];
    }

    $aDomain = explode('.', $sUrl);
    $nDomainSize = count($aDomain);

    if ($nDomainSize > 1) {
      if (is_numeric($aDomain[$nDomainSize-2]) && is_numeric($aDomain[$nDomainSize-1])) {
        return false;
      } else {
        return $aDomain[$nDomainSize-2] . '.' . $aDomain[$nDomainSize-1];
      }
    } else {
      return false;
    }
  }


 /**
  * get client ip
  *
  * @copyright (C) 2003 by osCommerce.
  * @license GPL <http://www.gnu.org/licenses/gpl.html>
  * @link http://www.oscommerce.com
  * @access public
  * @return string client ip
  */
  function oos_server_get_remote() {
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }

    return $ip;
  }

