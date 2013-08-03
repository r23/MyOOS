<?php
/* ----------------------------------------------------------------------
   $Id: function_session.php 296 2013-04-13 14:48:55Z r23 $

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

  /**
   * Session Support
   *
   * @package sessions
   * @license GPL <http://www.gnu.org/licenses/gpl.html>
   * @link http://www.oos-shop.de
   */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


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
      if (function_exists('session_close')) {
        return session_close();
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

