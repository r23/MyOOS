<?php
/* ----------------------------------------------------------------------
   $Id: oos_main.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_top.php,v 1.155 2003/02/17 16:54:11 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being required by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

// debug
$debug = 'true';

// for debug set the level of error reporting
error_reporting(E_ALL & ~E_NOTICE);
//   error_reporting(0);


// Disable use_trans_sid as oos_href_link_admin() does this manually
if (function_exists('ini_set')) {
	ini_set('session.use_trans_sid', 0);
}

// Set the local configuration parameters - mainly for developers
if (file_exists('../includes/local/configure.php')) include('../includes/local/configure.php');

// Include application configuration parameters
require '../includes/configure.php';
require 'includes/oos_define.php';

require 'includes/oos_filename.php';
require_once MYOOS_INCLUDE_PATH . 'includes/oos_tables.php';

require_once MYOOS_INCLUDE_PATH . 'includes/functions/function_global.php';
require 'includes/functions/function_kernel.php';

// Load server utilities  
require_once MYOOS_INCLUDE_PATH . 'includes/functions/function_server.php';

// todo remove
  if (isset($_POST)) {
    foreach ($_POST as $key=>$value) {
      $$key = oos_prepare_input($value);
    }
  }

// define how the session functions will be used 
require_once MYOOS_INCLUDE_PATH . 'includes/functions/function_session.php';

// set the session ID if it exists
if (isset($_POST[oos_session_name()])) {
	oos_session_id($_POST[oos_session_name()]);
} elseif (isset($_GET[oos_session_name()])) {
	oos_session_id($_GET[oos_session_name()]);
}

oos_session_name('OOSADMINSID');
oos_session_start();

if (!isset($_SESSION)) {
	$_SESSION = array();
}

// require the database functions
if (!defined('ADODB_LOGSQL_TABLE')) {
	define('ADODB_LOGSQL_TABLE', $oostable['adodb_logsql']);
}
require_once MYOOS_INCLUDE_PATH . 'includes/lib/adodb/toexport.inc.php';
require_once MYOOS_INCLUDE_PATH . 'includes/lib/adodb/adodb-errorhandler.inc.php';
require_once MYOOS_INCLUDE_PATH . 'includes/lib/adodb/adodb.inc.php';
require_once MYOOS_INCLUDE_PATH . 'includes/lib/adodb/tohtml.inc.php';
require_once MYOOS_INCLUDE_PATH . 'includes/functions/function_db.php';

// make a connection to the database... now
if (!oosDBInit()) {
	die('Unable to connect to database server!');
}

$dbconn =& oosDBGetConn();
oosDB_importTables($oostable);

// set application wide parameters
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

// some code to solve compatibility issues
require 'includes/functions/function_compatibility.php';

// language
if (!isset($_SESSION['language']) || isset($_GET['language'])) {
	// require the language class
	require_once MYOOS_INCLUDE_PATH . 'includes/classes/class_language.php';
	$oLang = new language;

	if (isset($_GET['language']) && oos_is_not_null($_GET['language'])) {
		$oLang->set($_GET['language']);
	} else {
		$oLang->get_browser_language();
	}
}

 

// require the language translations
$aLang = array();
$sLanguage = oos_var_prep_for_os($_SESSION['language']);
require 'includes/languages/' . $sLanguage . '.php';
$current_page = basename($_SERVER['SCRIPT_NAME']);
if (file_exists('includes/languages/' . $sLanguage . '/' . $current_page)) {
	require 'includes/languages/' . $sLanguage . '/' . $current_page;
}


// define our general functions used application-wide
require 'includes/functions/function_output.php';
require_once MYOOS_INCLUDE_PATH . 'includes/functions/function_password.php';


// setup our boxes
require 'includes/classes/class_table_block.php';
require 'includes/classes/class_box.php';

// initialize the message stack for output messages
require 'includes/classes/class_message_stack.php';
$messageStack = new messageStack;

// split-page-results
require 'includes/classes/class_split_page_results.php';

// entry/item info classes
require 'includes/classes/class_object_info.php';

// email classes
require_once MYOOS_INCLUDE_PATH . 'includes/lib/phpmailer/class.phpmailer.php';

// calculate category path
$cPath = $_GET['cPath'];
if (strlen($cPath) > 0) {
	$cPath_array = explode('_', $cPath);
	$current_category_id = $cPath_array[(count($cPath_array)-1)];
} else {
	$current_category_id = 0;
}

// default open navigation box
if (!isset($_SESSION['selected_box'])) {
	$_SESSION['selected_box'] = 'administrator';
}
if (isset($_GET['selected_box'])) {
	$_SESSION['selected_box'] = $_GET['selected_box'];
}

// check if a default currency is set
if (!defined('DEFAULT_CURRENCY')) {
	$messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
}

// check if a default language is set
if (!defined('DEFAULT_LANGUAGE')) {
	$messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
}

require 'includes/functions/function_added.php';

if (basename($_SERVER['SCRIPT_NAME']) != $aFilename['login'] 
   && basename($_SERVER['SCRIPT_NAME']) != $aFilename['password_forgotten']) { 
    oos_admin_check_login(); 
}

