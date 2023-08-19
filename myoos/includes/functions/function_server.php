<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: xarServer.php 1.62 03/10/28 19:11:18+01:00 mikespub
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  /**
   * HTTP Protocol Server/Request/Response utilities
   *
   * @package   server
   * @copyright (C) 2002 by the Xaraya Development Team.
   * @license   GPL <http://www.gnu.org/licenses/gpl.html>
   * @link      http://www.xaraya.com
   * @author    Marco Canini <marco@xaraya.com>
   */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

  /**
   * Gets a server variable
   *
   * Returns the value of $name server variable.
   * Accepted values for $name are exactly the ones described by the
   * {@link http://www.php.net/manual/en/reserved.variables.html#reserved.variables.server PHP manual}.
   * If the server variable doesn't exist void is returned.
   *
   * Last Editor: Author: r23
   *
   * @author Marco Canini <marco@xaraya.com>, Michel Dalle
   * @access public
   * @param  name string the name of the variable
   * @return mixed value of the variable
   */
function oos_server_get_var($sKey)
{
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
   * @author r23 <info@r23.de>
   * @access public
   * @param  string
   * @return mixed
   */
function oos_server_has_var($sKey)
{
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
   *
   * @author Marco Canini <marco@xaraya.com>
   * @access public
   * @return string HTTP host name
   */
function oos_server_get_host()
{
    $sServer = oos_server_get_var('HTTP_HOST');
    if (empty($sServer)) {
        // HTTP_HOST is reliable only for HTTP 1.1
        $sServer = oos_server_get_var('SERVER_NAME');
        $port = oos_server_get_var('SERVER_PORT');
        if ($port != '80') {
            $sServer .= ":$port";
        }
    }
    return $sServer;
}


  /**
   * Gets the current protocol
   *
   * Returns the HTTP protocol used by current connection, it could be 'http' or 'https'.
   *
   * Last Editor: Author: r23
   *
   * @author Marco Canini <marco@xaraya.com>
   * @access public
   * @return string current HTTP protocol
   */
function oos_server_get_protocol()
{
    $sProtocol = 'http';
    if (strtolower((string) oos_server_has_var('HTTPS')) == 'on'
        || oos_server_has_var('SSL_PROTOCOL')
    ) {
        $sProtocol = 'https';
    }

    return $sProtocol . '://';
}


  /**
   * Get base URI for oos
   *
   * @access public
   * @return string base URI for oos
   */
function oos_server_get_base_uri()
{

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

    $sPath = preg_replace('/[#\?].*/', '', (string) $sPath);

    $sPath = preg_replace('/\.php\/.*$/', '', $sPath);
    if (str_ends_with($sPath, '/')) {
        $sPath .= 'dummy';
    }
    $sPath = dirname((string) $sPath);

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
function oos_server_get_base_url()
{
    static $sBaseurl = null;

    if (isset($sBaseurl)) {
        return $sBaseurl;
    }

    $sServer = oos_server_get_host();
    $sProtocol = oos_server_get_protocol();
    $sPath = oos_server_get_base_uri();

    $sBaseurl = trim((string) $sProtocol . $sServer . $sPath . '/');
    return $sBaseurl;
}


 /**
  * get top level domain
  *
  * @copyright (C) 2003 by osCommerce.
  * @license   GPL <http://www.gnu.org/licenses/gpl.html>
  * @link      http://www.oscommerce.com
  * @access    public
  * @param     $sUrl
  * @return    mixed
  */
function oos_server_get_top_level_domain($sUrl)
{
    if (strpos((string) $sUrl, '://')) {
        $sUrl = parse_url((string) $sUrl);
        $sUrl = $sUrl['host'];
    }

    $aDomain = explode('.', (string) $sUrl);
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
  * @license   GPL <http://www.gnu.org/licenses/gpl.html>
  * @link      http://www.oscommerce.com
  * @access    public
  * @return    string client ip
  */
function oos_server_get_remote()
{
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $remote_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $remote_addr = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $remote_addr = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $remote_addr = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $remote_addr = getenv('HTTP_CLIENT_IP');
        } else {
            $remote_addr = getenv('REMOTE_ADDR');
        }
    }

	$remote_addr = filter_var($remote_addr, FILTER_VALIDATE_IP);

    return $remote_addr;
}

/**
 * Determines the maximum upload size allowed in php.ini.
 *
 * @copyright (C) 2022 by WordPress
 * @link      https://wordpress.org/
 *
 * @return int Allowed upload size.
 */
function oos_max_upload_size()
{
    $u_bytes = oos_convert_hr_to_bytes(ini_get('upload_max_filesize'));
    $p_bytes = oos_convert_hr_to_bytes(ini_get('post_max_size'));

    /**
     * @param int $size    Max upload size limit in bytes.
     * @param int $u_bytes Maximum upload filesize in bytes.
     * @param int $p_bytes Maximum size of POST data in bytes.
     */
    return min($u_bytes, $p_bytes);
}


/**
 * Converts a shorthand byte value to an integer byte value.
 *
 * @copyright (C) 2022 by WordPress
 * @link      https://wordpress.org/
 *
 * @link https://www.php.net/manual/en/function.ini-get.php
 * @link https://www.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
 *
 * @param  string $value A (PHP ini) byte value, either shorthand or ordinary.
 * @return int An integer byte value.
 */
function oos_convert_hr_to_bytes($value)
{
    $value = strtolower(trim((string) $value));
    $bytes = (int) $value;

    if (str_contains($value, 'g')) {
        $bytes *= GB_IN_BYTES;
    } elseif (str_contains($value, 'm')) {
        $bytes *= MB_IN_BYTES;
    } elseif (str_contains($value, 'k')) {
        $bytes *= KB_IN_BYTES;
    }

    // Deal with large (float) values which run into the maximum integer size.
    return min($bytes, PHP_INT_MAX);
}


/**
 * Composes human readable file size representation.
 *
 * @link https://gist.github.com/ffraenz/ec58809debb210b4567e53a9d9f413ce

 * @param  int    $size     File size in bytes
 * @param  int    $decimals Optional: Precision of the number of decimal places
 * @param  string $point    Optional: Sets the separator for the decimal point.
 * @return string Formatted number
 */
function size_format(int $size, int $decimals = 0, string $decimal_separator = '.', string $thousands_separator = ','): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    for ($index = 0; $index < count($units) - 1, $size > 1000; $index++, $size /= 1000);
    $number = number_format($size, $decimals, $decimal_separator, $thousands_separator);
    return $number . ' ' . $units[$index];
}
