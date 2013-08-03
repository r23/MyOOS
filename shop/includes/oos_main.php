<?php
/* ----------------------------------------------------------------------
   $Id: oos_main.php 477 2013-07-14 21:57:50Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
$debug = 'false';


// Set the local configuration parameters - mainly for developers
if (is_readable('includes/local/configure.php')) {
	require_once MYOOS_INCLUDE_PATH . '/includes/local/configure.php';
} else {
	require_once MYOOS_INCLUDE_PATH . '/includes/configure.php';
}

// Version information
define('OOS_VERSION', '2.0.12 -dev');
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
					(isset($_SERVER['HTTP_X_FORWARDED_BY']) && strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_BY']), 'SSL') !== false) ||
					(isset($_SERVER['HTTP_X_FORWARDED_HOST']) && (strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_HOST']), 'SSL') !== false || strpos(strtoupper($_SERVER['HTTP_X_FORWARDED_HOST']), str_replace('https://', '', HTTPS_SERVER)) !== false)) ||
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

// initialize the logger class
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_member.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_products_history.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shopping_cart.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_navigation_history.php';

// require  the mail classes
require_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/class.phpmailer.php';

require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_session.php';


// require  the database functions
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
// Close result set
$configuration_result->Close();


require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_plugin_event.php';
$oEvent = new plugin_event;
$oEvent->getInstance();

// set the language
$nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : DEFAULT_CUSTOMERS_STATUS_ID;
$sLanguage = oos_var_prep_for_os($_SESSION['language']);

// set the Group
$nGroupID = isset($_SESSION['member']) ? $_SESSION['member']->group['id']+0 : 1;

// Cross-Site Scripting attack defense
oos_secure_input();

// POST overrides GET data
// We don't use $_REQUEST here to avoid interference from cookies...
$aData = array();
$aData = $_POST + $_GET;

// determine the page directory
if (isset($aData['content'])) {
    $sContent = oos_var_prep_for_os($aData['content']);
}

if ( empty( $sContent ) || !is_string( $sContent ) ) {
    $sContent = $aContents['main'];
}


// initialize the message stack for output messages
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_message_stack.php';
$oMessage = new messageStack;

// templates selection
$sTheme = STORE_TEMPLATES;

  // PAngV
  if ($_SESSION['member']->group['show_price'] == 1) {
    if ($_SESSION['member']->group['show_price_tax'] == 1) {
      $sPAngV = $aLang['text_taxt_incl'];
    } else {
      $sPAngV = $aLang['text_taxt_add'];
    }

    if (isset($_SESSION['customers_vat_id_status']) && ($_SESSION['customers_vat_id_status'] == 1)) {
      $sPAngV = $aLang['tax_info_excl'];
    }

    $sPAngV .= ', <br />';
    $sPAngV .= sprintf($aLang['text_shipping'], oos_href_link($aContents['information'], 'information_id=1'));
  }


// Shopping cart actions
if ( isset($_GET['action'])
   || ( isset($_POST['action']) && isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) ){
		require_once MYOOS_INCLUDE_PATH . '/includes/oos_cart_actions.php';
}





  $products_unitstable = $oostable['products_units'];
  $query = "SELECT products_units_id, products_unit_name
            FROM $products_unitstable
            WHERE languages_id = '" . intval($nLanguageID) . "'";
  $products_units = $dbconn->GetAssoc($query);


  $aTemplate = array();

