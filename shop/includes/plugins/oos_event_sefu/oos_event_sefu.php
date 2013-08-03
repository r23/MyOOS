<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_sefu.php 470 2013-07-08 12:16:25Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class oos_event_sefu {

    var $name;
    var $description;
    var $uninstallable;
    var $depends;
    var $preceeds = 'session';
    var $author;
    var $version;
    var $requirements;


   /**
    *  class constructor
    */
    function oos_event_sefu() {

      $this->name          = PLUGIN_EVENT_SEFU_NAME;
      $this->description   = PLUGIN_EVENT_SEFU_DESC;
      $this->uninstallable = true;
      $this->preceeds      = 'session';
      $this->author        = 'OOS Development Team';
      $this->version       = '1.0';
      $this->requirements  = array(
                               'oos'         => '1.5.0',
                               'smarty'      => '2.6.9',
                               'adodb'       => '4.62',
                               'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {
      include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_url_rewrite.php';

      if (isset($_GET['rewrite'])) {
        $sUrl = oos_server_get_var('QUERY_STRING');
      }

      if (!empty($sUrl)) {

        while (strstr($sUrl, '&amp;'))  $sUrl = str_replace('&amp;', '&', $sUrl);
        while (strstr($sUrl, '&&')) $sUrl = str_replace('&&', '&', $sUrl);

        $sUrl = str_replace('?', '/', $sUrl);
        $sUrl = str_replace('=', '/', $sUrl);
        $sUrl = str_replace('&', '/', $sUrl);
        $sPathInfo = trim($sUrl, '/');
      } else {
        $sPathInfo = oos_server_get_var('PATH_INFO');
      }

      if (isset($sPathInfo) && (strlen($sPathInfo) > 1)) {
        $_SERVER['PHP_SELF'] = str_replace($sPathInfo, '', $_SERVER['PHP_SELF']);
        $aVars = explode('/', substr($sPathInfo, 1));

        $aGet = array();

        for ($i=0, $n=sizeof($aVars); $i<$n; $i++) {
          if (!isset($aVars[$i+1])) $aVars[$i+1] = '';

          if (strpos($aVars[$i], '[]')) {
            $aGet[substr($aVars[$i], 0, -2)][] = $aVars[$i+1];
          } else {
            $_GET[$aVars[$i]] = $aVars[$i+1];
          }
          $i++;
        }

        if (sizeof($aGet) > 0) {
          foreach ($aGet as $sKey => $sValue) {
            $_GET[$sKey] = $sValue;
          }
        }
      }

      return true;
    }


    function install() {

      $htaccess = @file_get_contents(OOS_ABSOLUTE_PATH . '.htaccess');

      if (php_sapi_name() == 'cgi' || php_sapi_name() == 'cgi-fcgi') {
        $htaccess_cgi = '_cgi';
      } else {
        $htaccess_cgi = '';
      }

      /* Detect comptability with php_value directives */

      if ($htaccess_cgi == '') {
        $response = '';
        $oos_host     = preg_replace('@^([^:]+):?.*$@', '\1', $_SERVER['HTTP_HOST']);

        $old_htaccess = @file_get_contents(OOS_ABSOLUTE_PATH . '.htaccess');
        $fp = @fopen(OOS_ABSOLUTE_PATH . '.htaccess', 'w');
        if ($fp) {
          fwrite($fp, 'php_value register_globals off'. "\n" .'php_value session.use_trans_sid 0');
          fclose($fp);

          $sock = @fsockopen($oos_host, $_SERVER['SERVER_PORT'], $errorno, $errorstring, 10);
          if ($sock) {
            fputs($sock, "GET {OOS_SHOP} HTTP/1.0\r\n");
            fputs($sock, "Host: $oos_host\r\n");
            fputs($sock, "User-Agent: OSIS Online Shop/{OOS_VERSION}\r\n");
            fputs($sock, "Connection: close\r\n\r\n");

            while (!feof($sock) && strlen($response) < 4096) {
              $response .= fgets($sock, 400);
            }
            fclose($sock);
          }

          /* If we get HTTP 500 Internal Server Error, we have to use the .cgi template */
          if (preg_match('@^HTTP/\d\.\d 500@', $response)) {
            $htaccess_cgi = '_cgi';
          }

          if (!empty($old_htaccess)) {
            $fp = @fopen(OOS_ABSOLUTE_PATH . '.htaccess', 'w');
            fwrite($fp, $old_htaccess);
            fclose($fp);
          } else {
            @unlink(OOS_ABSOLUTE_PATH. '.htaccess');
          }
        }
      }

      $template = 'htaccess' . $htaccess_cgi . '_rewrite.tpl';

      if (!($a = file(OOS_TEMP_PATH . 'htaccess/' . $template, 1))) {
        return false;
      }

      $content = str_replace(
                   array(
                     '{PREFIX}',
                     '{errorFile}',
                     '{indexFile}',
                     '{logFile}',
                   ),

                   array(
                     OOS_SHOP,
                     'error.php',
                     'index.php',
                     OOS_TEMP_PATH . 'logs/php_error.log',
                   ),

                   implode('', $a)
                );
      $fp = @fopen(OOS_ABSOLUTE_PATH . '.htaccess', 'w');
      if (!$fp) {
        return false;
      } else {
        // Check if an old htaccess file existed and try to preserve its contents. Otherwise completely wipe the file.
        if ($htaccess != '' && preg_match('@^(.*)#\s+BEGIN\s+OOS.*#\s+END\s+OOS(.*)$@isU', $htaccess, $match)) {
          // Code outside from oos-code was found.
          fwrite($fp, $match[1] . $content . $match[2]);
        } else {
          fwrite($fp, $content);
        }
        fclose($fp);
      }

      return true;
    }


    function remove() {
      $rewrite = 'none';
      $oos_host     = preg_replace('@^([^:]+):?.*$@', '\1', $_SERVER['HTTP_HOST']);
      $old_htaccess = @file_get_contents(OOS_ABSOLUTE_PATH . '.htaccess');


      $fp = @fopen(OOS_ABSOLUTE_PATH . '.htaccess', 'w');
      fwrite($fp, 'ErrorDocument 404 ' . $oos_root . 'index.php');
      fclose($fp);

      // Do a request on a nonexistant file to see, if our htaccess allows ErrorDocument
      $sock = @fsockopen($oos_host, $_SERVER['SERVER_PORT'], $errorno, $errorstring, 10);
      $response = '';

      if ($sock) {
        fputs($sock, "GET {$oos_root}nonexistant HTTP/1.0\r\n");
        fputs($sock, "Host: $oos_host\r\n");
        fputs($sock, "User-Agent: OOS[OSIS Online Shop]/{OOS_VERSION}\r\n");
        fputs($sock, "Connection: close\r\n\r\n");

        while (!feof($sock) && strlen($response) < 4096) {
          $response .= fgets($sock, 400);
        }
        fclose($sock);
      }

      if (preg_match('@^HTTP/\d\.\d 200@', $response)) {
        $rewrite = 'errordocs';
      }

      if (!empty($old_htaccess)) {
        $fp = @fopen(OOS_ABSOLUTE_PATH . '.htaccess', 'w');
        fwrite($fp, $old_htaccess);
        fclose($fp);
      } else {
        @unlink(OOS_ABSOLUTE_PATH . '.htaccess');
      }

      if ($rewrite == 'errordocs') {
        $template = 'htaccess' . $htaccess_cgi . '_errordocs.tpl';
      } else {
        $template = 'htaccess' . $htaccess_cgi . '_normal.tpl';
      }

      if (!($a = file(OOS_TEMP_PATH . 'htaccess/' . $template, 1))) {
        return false;
      }

      $content = str_replace(
                   array(
                     '{PREFIX}',
                     '{errorFile}',
                     '{indexFile}',
                     '{logFile}',
                   ),

                   array(
                     OOS_SHOP,
                     'error.php',
                     'index.php',
                     OOS_TEMP_PATH . 'logs/php_error.log',
                   ),

                   implode('', $a)
                );
      $fp = @fopen(OOS_ABSOLUTE_PATH . '.htaccess', 'w');
      if (!$fp) {
        return false;
      } else {
        // Check if an old htaccess file existed and try to preserve its contents. Otherwise completely wipe the file.
        if ($htaccess != '' && preg_match('@^(.*)#\s+BEGIN\s+OOS.*#\s+END\s+OOS(.*)$@isU', $htaccess, $match)) {
          // Code outside from oos-code was found.
          fwrite($fp, $match[1] . $content . $match[2]);
        } else {
          fwrite($fp, $content);
        }
        fclose($fp);
      }

      return true;
    }

    function config_item() {
      return false;
    }
  }


