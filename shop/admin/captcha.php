<?php
/* ----------------------------------------------------------------------
   $Id: captcha.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');

  ob_start();
// for debug set the level of error reporting
// error_reporting(E_ALL);
  error_reporting(0);


// Disable use_trans_sid as oos_href_link_admin() does this manually
  if (function_exists('ini_set')) {
    ini_set('session.use_trans_sid', 0);
  }

// Set the local configuration parameters - mainly for developers
  if (file_exists('../includes/local/configure.php')) {
    require '../includes/local/configure.php';
  }

// Include application configuration parameters
  require '../includes/configure.php';

  require '../includes/oos_tables.php';

// Load server utilities
  require '../includes/functions/function_server.php';

// define how the session functions will be used
  require '../includes/functions/function_session.php';

  if (isset($_COOKIE[oos_session_name()])) {
    oos_session_id($_COOKIE[oos_session_name()]);
  } else if (isset($_GET[oos_session_name()])) {
    oos_session_id($_GET[oos_session_name()]);
  }


// lets start our session
  oos_session_name('OOSADMINSID');
  oos_session_start();

  if (!isset($_SESSION)) {
    $_SESSION = array();
  }

  $_SESSION['oos_captcha_string'] = '';
  require '../includes/classes/thirdparty/captcha/captcha.class.php';

  $captcha = new captcha();
  $_SESSION['oos_captcha_string'] = $captcha->getCaptcha();
?>