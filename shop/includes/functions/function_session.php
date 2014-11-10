<?php
/* ----------------------------------------------------------------------
   $Id: function_session.php,v 1.1 2007/06/12 16:49:27 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /**
   * Session Support
   *
   * @package sessions
   * @license GPL <http://www.gnu.org/licenses/gpl.html>
   * @link http://www.oos-shop.de
   */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );


  if (STORE_SESSIONS == 'true') {
    $host = OOS_DB_SERVER;
    $driver = OOS_DB_TYPE;
    $database = OOS_DB_DATABASE;

    // Decode encoded DB parameters
    if (OOS_ENCODED == '1') {
      $user = base64_decode(OOS_DB_USERNAME);
      $pwd = base64_decode(OOS_DB_PASSWORD);
    } else {
      $user = OOS_DB_USERNAME;
      $pwd = OOS_DB_PASSWORD;
    }
    $options['table'] = $oostable['sessions'];

    include_once OOS_ABSOLUTE_PATH.'includes/classes/thirdparty/adodb/adodb.inc.php';
    if (STORE_SESSIONS_CRYPT == 'true') {
      include OOS_ABSOLUTE_PATH.'includes/classes/thirdparty/adodb/session/adodb-cryptsession2.php';
    } else {
      include OOS_ABSOLUTE_PATH.'includes/classes/thirdparty/adodb/session/adodb-session2.php';
    }

    ADOdb_Session::config($driver, $host, $user, $pwd, $database, $options);
    adodb_sess_open(false,false,$connectMode=false);
  }


 /**
  * Return session_id
  *
  * @private
  */
  function oos_session_id($sSessid = '') {
     if (!empty($sSessid)) {
      return session_id($sSessid);
    } else {
      return session_id();
    }
  }


 /**
  * Return session_name
  *
  * @private
  */
  function oos_session_name($sName = '') {
    if (!empty($sName)) {
      return session_name($sName);
    } else {
      return session_name();
    }
  }


 /**
  * PHP function to close the session
  *
  * @private
  */
  function oos_session_close() {
    if (STORE_SESSIONS != 'true') {
      if (function_exists('session_close')) {
        return session_close();
      }
    }
  }


 /**
  * Return session_save_path
  *
  * @private
  */
  function oos_session_save_path($sPath = '') {
     if (!empty($sPath)) {
       return session_save_path($sPath);
     } else {
       return session_save_path();
     }
   }


 /**
  * PHP function to start the session
  *
  * @private
  */
  function oos_session_start() {
     return session_start();
  }


 /**
  * ADOdb function to regenerate a new session id
  *
  * @private
  */
  function oos_session_regenerate_id() {
    if (function_exists('adodb_session_regenerate_id')) {
      return adodb_session_regenerate_id();
    }
  }

?>
