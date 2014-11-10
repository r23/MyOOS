<?php
/* ----------------------------------------------------------------------
   $Id: index.php,v 1.3 2008/08/15 16:44:22 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/**
 * Set the error reporting level. Unless you have a special need, E_ALL is a
 * good level for error reporting.
 */
error_reporting(E_ALL);
// error_reporting(E_ALL & ~E_STRICT);
   
   
/**
 * Test to make sure that MyOOS is running on PHP 5.2.3 or newer. Once you are
 * sure that your environment is compatible with MyOOS, you can comment this
 * line out. When running an application on a new server, uncomment this line
 * to check the PHP version quickly.
 */
if (version_compare(PHP_VERSION, '5.2.3', '<')) {
    header('Content-type: text/html; charset=utf-8', true, 503);

    echo '<h2>Fehler</h2>';
    echo 'Auf Ihrem Server läuft PHP version ' . PHP_VERSION . ', MyOOS benötigt mindestens PHP 5.2.3';

    echo '<h2>Error</h2>';
    echo 'Your server is running PHP version ' . PHP_VERSION . ' but MyOOS requires at least PHP 5.2.3';
    return;
}

//setting basic configuration parameters
if (function_exists('ini_set')) {
	ini_set('session.use_trans_sid', 0);
	ini_set('url_rewriter.tags', '');
	ini_set('xdebug.show_exception_trace', 0);
	ini_set('magic_quotes_runtime', 0);
	// ini_set('display_errors', false);
}


define('MYOOS_DOCUMENT_ROOT', dirname(__FILE__)=='/'?'':dirname(__FILE__));


if(!defined('MYOOS_INCLUDE_PATH')) {
	define('MYOOS_INCLUDE_PATH', MYOOS_DOCUMENT_ROOT);
}

define('OOS_VALID_MOD', 'yes');
require 'includes/oos_main.php';


  $sMp = oos_var_prep_for_os($sMp);
  $sFile = oos_var_prep_for_os($sFile);
  $sLanguage = oos_var_prep_for_os($_SESSION['language']);
  $sTheme = oos_var_prep_for_os($_SESSION['theme']);

  if (file_exists('includes/pages/' . $sMp . '/' . $sFile . '.php')) {
    if (isset($_GET['history_back'])){
      $_SESSION['navigation']->remove_last_page();
    } else {
      $_SESSION['navigation']->add_current_page();
    }

    include 'includes/pages/' . $sMp . '/' . $sFile . '.php';

  } else {
    // Module not found

    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));

  }

  include 'includes/oos_nice_exit.php';

