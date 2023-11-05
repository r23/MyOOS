<?php
/**
   ---------------------------------------------------------------------
   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_top.php,v 1.155 2003/02/17 16:54:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


/**
 * ensure this file is being required by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


// debug
$debug = false;

// for debug set the level of error reporting
// error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE);
//   error_reporting(0);

date_default_timezone_set('Europe/Berlin');


// Disable use_trans_sid as oos_href_link_admin() does this manually
if (function_exists('ini_set')) {
    ini_set('session.use_trans_sid', 0);
}

// Set the local configuration parameters - mainly for developers
if (file_exists('../includes/local/configure.php')) {
    include '../includes/local/configure.php';
} else {
    // Include application configuration parameters
    include '../includes/configure.php';
}

// redirect to the installation module if DB_SERVER is empty
if (strlen((string) OOS_DB_TYPE) < 1) {
    if (is_dir('../install')) {
        header('Location: ../install/step.php');
        exit;
    }
}

if (!defined('MYOOS_INCLUDE_PATH')) {
    define('MYOOS_INCLUDE_PATH', OOS_ABSOLUTE_PATH);
}

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Symfony\Component\HttpFoundation\Request;

$autoloader = include_once MYOOS_INCLUDE_PATH . '/vendor/autoload.php';
$request = Request::createFromGLOBALs();

require 'includes/filename.php';
require_once MYOOS_INCLUDE_PATH . '/includes/tables.php';

// require Shop parameters
require_once MYOOS_INCLUDE_PATH . '/includes/define.php';


require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_global.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_norector.php';
require 'includes/functions/function_kernel.php';
require 'includes/functions/function_validations.php';

// Load server utilities
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_server.php';


require_once MYOOS_INCLUDE_PATH . '/includes/lib/Phoenix/Core/Session.php';
$session = new Phoenix_Session();
$session->setName('PHOENIXADMINSID');
$session->start();


// require the database functions
require_once MYOOS_INCLUDE_PATH . '/vendor/adodb/adodb-php/adodb-exceptions.inc.php'; // loads the ADOdb exception handling
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_db.php';

// make a connection to the database... now
if (!oosDBInit()) {
    die('Unable to connect to database server!');
}

$dbconn = & oosDBGetConn();
oosDB_importTables($oostable);


// set application wide parameters
$configurationtable = $oostable['configuration'];
$configuration_query = "SELECT configuration_key AS cfg_key, configuration_value AS cfg_value
                        FROM $configurationtable";
$configuration_result = $dbconn->Execute($configuration_query);

while ($configuration = $configuration_result->fields) {
    define($configuration['cfg_key'], $configuration['cfg_value']);
    // Move that ADOdb pointer!
    $configuration_result->MoveNext();
}


// set the language
$sLanguage = isset($_SESSION['language']) ? oos_var_prep_for_os($_SESSION['language']) : DEFAULT_LANGUAGE;
$nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_CUSTOMERS_STATUS_ID;
if (!isset($_SESSION['language']) || isset($_GET['language'])) {
    // include the language class
    include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_language.php';
    $oLang = new language();

    if (isset($_GET['language']) && is_string($_GET['language'])) {
        $oLang->set_language($_GET['language']);
    } else {
        $oLang->get_browser_language();
    }

    $sLanguage = $oLang->language['iso_639_2'];
    $nLanguageID = $oLang->language['id'];
    $_SESSION['language'] = $oLang->language['iso_639_2'];
    $_SESSION['language_id'] = $oLang->language['id'];
    $_SESSION['iso_639_1'] = $oLang->language['iso_639_1'];
    $_SESSION['languages_name'] = $oLang->language['name'];
}


// require the language translations
$aLang = [];
$sLanguage = oos_var_prep_for_os($_SESSION['language']);
require 'includes/languages/' . $sLanguage . '.php';
require 'includes/languages/' . $sLanguage . '/configuration_group.php';
$current_page = oos_var_prep_for_os(basename((string) $_SERVER['SCRIPT_NAME']));
if (file_exists('includes/languages/' . $sLanguage . '/' . $current_page)) {
    include 'includes/languages/' . $sLanguage . '/' . $current_page;
}


// define our general functions used application-wide
require 'includes/functions/function_output.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';

// setup our boxes
require 'includes/classes/class_table_block.php';
require 'includes/classes/class_box.php';

// initialize the message stack for output messages
require 'includes/classes/class_message_stack.php';
$messageStack = new messageStack();

// split-page-results
require 'includes/classes/class_split_page_results.php';

// entry/item info classes
require 'includes/classes/class_object_info.php';


// calculate category path
$cPath = isset($_GET['cPath']) ? oos_db_prepare_input($_GET['cPath']) : '';
if (strlen((string) $cPath) > 0) {
    $aPath = explode('_', (string) $cPath);
    $current_category_id = $aPath[(count($aPath) - 1)];
} else {
    $current_category_id = 0;
}

// default open navigation box
if (!isset($_SESSION['selected_box'])) {
    $_SESSION['selected_box'] = 'administrator';
}
if (isset($_GET['selected_box'])) {
    $_SESSION['selected_box'] = oos_db_prepare_input($_GET['selected_box']);
}

// check if a default currency is set
if (!defined('DEFAULT_CURRENCY')) {
    $messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
}

// check if a default language is set
if (!defined('DEFAULT_LANGUAGE')) {
    $messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
}

if (basename((string) $_SERVER['SCRIPT_NAME']) != $aContents['login']
    && basename((string) $_SERVER['SCRIPT_NAME']) != $aContents['password_forgotten']
) {
    oos_admin_check_login();

    $is_htaccess = (file_exists(OOS_ABSOLUTE_PATH . OOS_ADMIN . '.htaccess'));
    $is_protected = oos_check_is_access_protected();


    if (false === $is_protected) {
        $messageStack->add(ERROR_HTACC_CHECK_ERROR, 'error');
    } elseif (1 === $is_protected && !$is_htaccess) {
        // The program is protected by higher-level authorizations; local directory protection is not required.
    } elseif (1 === $is_protected && $is_htaccess) {
        // The program is protected, the directory protection is complete.
    } elseif (0 === $is_protected && $is_htaccess) {
        $messageStack->add(ERROR_HTACC_INCOMPLETE, 'error');
    } else {
        $messageStack->add(ERROR_HTACC_PROPOSED, 'error');
    }
}
