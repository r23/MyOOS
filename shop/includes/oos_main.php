<?php
/* ----------------------------------------------------------------------
   $Id: oos_main.php 477 2013-07-14 21:57:50Z r23 $

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
if (USE_DB_CACHE == 'true')
{
	$configuration_result = $dbconn->CacheExecute(3600, $configuration_query);
} 
else
{
    $configuration_result = $dbconn->Execute($configuration_query);
}

while ($configuration = $configuration_result->fields)
{
	define($configuration['cfg_key'], $configuration['cfg_value']);
    // Move that ADOdb pointer!
    $configuration_result->MoveNext();
}


// Session
$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
$spider_flag = false;
$spider_kill_sid = 'false';

// set the top level domains
$http_domain = oos_server_get_top_level_domain(OOS_HTTP_SERVER);
$https_domain = oos_server_get_top_level_domain(OOS_HTTPS_SERVER);
$current_domain = (($request_type == 'NONSSL') ? $http_domain : $https_domain);

// set the session cookie parameters
if (function_exists('session_set_cookie_params'))
{
	session_set_cookie_params(0, '/', (oos_is_not_null($current_domain) ? '.' . $current_domain : ''));
} 
elseif (function_exists('ini_set')) 
{
	ini_set('session.cookie_lifetime', '0');
	ini_set('session.cookie_path', '/');
	ini_set('session.cookie_domain', (oos_is_not_null($current_domain) ? '.' . $current_domain : ''));
}

// set the session ID if it exists
if (isset($_POST[oos_session_name()]))
{
	oos_session_id($_POST[oos_session_name()]);
} 
elseif (isset($_GET[oos_session_name()]))
{
	oos_session_id($_GET[oos_session_name()]);
}


if (empty($user_agent) === false)
{
	$spider_agent = @parse_ini_file('includes/ini/spiders.ini');

	foreach ($spider_agent as $spider)
	{
		if (empty($spider) === false)
		{
			if (strpos($user_agent, trim($spider)) !== false)
			{
				$spider_kill_sid = 'true';
				$spider_flag = true;
				break;
            }
		}
	}
}

if ($spider_flag === false)
{
	// set the session name and save path
	oos_session_name('OOSSID');

	// lets start our session
	oos_session_start();
}

if (!isset($_SESSION))
{
	$_SESSION = array();
}

// create the shopping cart
if (!isset($_SESSION['cart']))
{
	$_SESSION['cart'] = new shoppingCart();
}

// navigation history
if (!isset($_SESSION['navigation']))
{
	$_SESSION['navigation'] = new oosNavigationHistory();
}

// products history
if (!isset($_SESSION['products_history']))
{
	$_SESSION['products_history'] = new oosProductsHistory();
}

if (!isset($_SESSION['member']))
{
	$_SESSION['member'] = new oosMember();
	$_SESSION['member']->default_member();
}
	  
if (!isset($_SESSION['error_cart_msg']))
{
	$_SESSION['error_cart_msg'] = '';
}



$aContents = oos_get_content();
// verify the browser user agent
$http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

if (!isset($_SESSION['session_user_agent']))
{
	$_SESSION['session_user_agent'] = $http_user_agent;
}

if ($_SESSION['session_user_agent'] != $http_user_agent)
{
	session_destroy();
	oos_redirect(oos_link($aContents['login'], '', 'SSL'));
}

// verify the IP address
if (!isset($_SESSION['session_ip_address']))
{
	$_SESSION['session_ip_address'] = oos_server_get_remote();
}

if ($_SESSION['session_ip_address'] != oos_server_get_remote())
{
	session_destroy();
	oos_redirect(oos_link($aContents['login'], '', 'SSL'));
}


// set the language
if (!isset($_SESSION['language']) || isset($_GET['language'])) {
	// include the language class
	include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_language.php';
	$oLang = new language();

	if (isset($_GET['language']) && oos_is_not_null($_GET['language'])) {
		// $oLang->set_language($_GET['language']);
		$oLang->set($_GET['language']);
	} else {
		$oLang->get_browser_language();
	}
	
	/*  todo
	$language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
	*/
}

	
// set the language
$nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : DEFAULT_CUSTOMERS_STATUS_ID;
$sLanguage = oos_var_prep_for_os($_SESSION['language']);


require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_plugin_event.php';
$oEvent = new plugin_event;
$oEvent->getInstance();


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
 
