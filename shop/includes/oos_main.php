<?php
/* ----------------------------------------------------------------------
   $Id: oos_main.php,v 1.3 2008/07/06 23:58:17 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_top.php,v 1.264 2003/02/17 16:37:52 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being require d by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

// debug
$debug = 'true';


// Set the local configuration parameters - mainly for developers
if (is_readable('includes/local/configure.php')) {
    require_once MYOOS_INCLUDE_PATH . '/includes/local/configure.php';
} else {
    require_once MYOOS_INCLUDE_PATH . '/includes/configure.php';
}

// Version information
define('OOS_VERSION', '2.0.18 -dev');
// Complete software name string
define('OOS_FULL_NAME', 'MyOOS ' . OOS_VERSION);

// require Shop parameters
require_once MYOOS_INCLUDE_PATH . '/includes/oos_define.php';

// Load server utilities
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_server.php';

//for debugging purposes
require_once MYOOS_INCLUDE_PATH . '/includes/oos_debug.php';

// redirect to the installation module if DB_SERVER is empty
if (strlen(OOS_DB_TYPE) < 1) {
    if (is_dir('install')) {
        header('Location: install/step.php');
        exit;
    }
}

// set the type of request (secure or not)
$request_type = 'NONSSL';
if (ENABLE_SSL == 'true') {
    $request_type = (((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1'))) ||
        (isset($_SERVER['HTTP_X_FORWARDED_BY']) && strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_BY']), 'SSL') !== FALSE) ||
        (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && (strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_HOST']), 'SSL') !== FALSE || strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_HOST']), str_replace('https://', '', HTTPS_SERVER)) !== FALSE)) ||
        (isset($_SERVER['SCRIPT_URI']) && strtolower(substr($_SERVER['SCRIPT_URI'], 0, 6)) == 'https:') ||
        (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && ($_SERVER['HTTP_X_FORWARDED_SSL'] == '1' || strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on')) ||
        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'ssl' || strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https')) ||
        (isset($_SERVER['HTTP_SSLSESSIONID']) && $_SERVER['HTTP_SSLSESSIONID'] != '') ||
        (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')) ? 'SSL' : 'NONSSL';
}


// require  the list of project filenames
require_once MYOOS_INCLUDE_PATH . '/includes/oos_filename.php';

// require  the list of project database tables
require_once MYOOS_INCLUDE_PATH . '/includes/oos_tables.php';

// define general functions used application-wide
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_global.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_kernel.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_input.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_output.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_encoded.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_coupon.php';

// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';

// require  validation functions (right now only email address)
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validations.php';

  if (isset($_POST)) {
    foreach ($_POST as $key=>$value) {
      $$key = oos_prepare_input($value);
    }
  }


// initialize the logger class

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_member.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_products_history.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shopping_cart.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_navigation_history.php';

require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_session.php';

// require the database functions
$adodb_logsqltable = $oostable['adodb_logsql'];
if (!defined('ADODB_LOGSQL_TABLE')) {
    define('ADODB_LOGSQL_TABLE', $adodb_logsqltable);
}
require_once MYOOS_INCLUDE_PATH . '/includes/lib/adodb/adodb-errorhandler.inc.php';
require_once MYOOS_INCLUDE_PATH . '/includes/lib/adodb/adodb.inc.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_db.php';

// make a connection to the database... now
if (!oosDBInit()) {
    die('Unable to connect to database server!');
}

$dbconn =& oosDBGetConn();
oosDB_importTables($oostable);

// set the application parameters
$configurationtable = $oostable['configuration'];
$configuration_query = "SELECT configuration_key AS cfg_key, configuration_value AS cfg_value
FROM $configurationtable";
if (USE_DB_CACHE == 'true') {
    $configuration_result = $dbconn->CacheExecute(3600, $configuration_query);
} else {
    $configuration_result = $dbconn->Execute($configuration_query);
}

while ($configuration = $configuration_result->fields) {
    define($configuration['cfg_key'], $configuration['cfg_value']);
    // Move that ADOdb pointer!
    $configuration_result->MoveNext();
}


// set the session name and save path
session_name('OOSSID');

// set the session ID if it exists
if (isset($_POST[session_name()]) && !empty($_POST[session_name()])){
    oos_session_start();
/*
} elseif (isset($_COOKIE[session_name()])) {
  session_id($_COOKIE[session_name()]);
   oos_session_start();
 */
} elseif (isset($_GET[session_name()]) && !empty($_GET[session_name()])) {
    oos_session_start();
}

if ( is_session_started() === TRUE ) {
    if (!(preg_match('/^[a-z0-9]{26}$/i', session_id()) || preg_match('/^[a-z0-9]{32}$/i', session_id()))) {
        session_regenerate_id(TRUE);
   }
}



if (!isset($_SESSION['language']) || isset($_GET['language'])) {
    // include the language class
    include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_language.php';
    $oLang = new language();

    if (isset($_GET['language']) && is_string($_GET['language'])) {
        // start the session
        if ( is_session_started() === FALSE ) oos_session_start();

        $oLang->set_language($_GET['language']);
    } else {
        $oLang->get_browser_language();
    }

    $sLanguage = $oLang->language['iso_639_2'];
    $nLanguageID = $oLang->language['id'];

    if (isset($_SESSION)) {
        $_SESSION['language'] = $oLang->language['iso_639_2'];
        $_SESSION['language_id'] = $oLang->language['id'];
        $_SESSION['iso_639_1'] = $oLang->language['iso_639_1'];
        $_SESSION['languages_name'] = $oLang->language['name'];
    }

}

// set the language
$sLanguage = isset($_SESSION['language']) ? $_SESSION['language'] : DEFAULT_LANGUAGE;
$nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : DEFAULT_CUSTOMERS_STATUS_ID;

include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '.php';


// currency
include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_currencies.php';
$oCurrencies = new currencies();
$sCurrency = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
if (!isset($_SESSION['currency']) || isset($_GET['currency'])) {
    if (isset($_GET['currency']) && oos_currency_exits($_GET['currency']))  {
        // start the session
        if ( is_session_started() === FALSE ) oos_session_start();

        $sCurrency = oos_var_prep_for_os($_GET['currency']);
    }

    if (isset($_SESSION)) {
        $_SESSION['currency'] = $sCurrency;
    }
}


//for debugging purposes
//require_once MYOOS_INCLUDE_PATH . '/includes/oos_debug.php';


require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_plugin_event.php';
$oEvent = new plugin_event;
$oEvent->getInstance();


// determine the page directory
if (isset($_GET['content'])) {
	$sContent = oos_var_prep_for_os($_GET['content']);
} elseif (isset($_POST['content'])) {
	$sContent = oos_var_prep_for_os($_POST['content']);
}
if ( empty( $sContent ) || !is_string( $sContent ) ) {
    $sContent = $aContents['main'];
}  
  
// Cross-Site Scripting attack defense
oos_secure_input();

// initialize the message stack for output messages
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_message_stack.php';
$oMessage = new messageStack;

// templates selection
$sTheme = STORE_TEMPLATES;
$aTemplate = array();

$today = date("Y-m-d H:i:s");

// Shopping cart actions
if ( isset($_GET['action']) || isset($_POST['action']) ) {
	require_once MYOOS_INCLUDE_PATH . '/includes/oos_cart_actions.php';
}

// split-page-results
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';
// infobox
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_boxes.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_coupon.php';

