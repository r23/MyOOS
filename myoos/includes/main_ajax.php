<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_top.php,v 1.264 2003/02/17 16:37:52 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being require d by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// debug
$debug = false;

date_default_timezone_set('Europe/Berlin');

// Set the local configuration parameters - mainly for developers
if (is_readable('includes/local/configure.php')) {
    include_once MYOOS_INCLUDE_PATH . '/includes/local/configure.php';
} else {
    include_once MYOOS_INCLUDE_PATH . '/includes/configure.php';
}


require_once MYOOS_INCLUDE_PATH . '/includes/version.php';

// require Shop parameters
require_once MYOOS_INCLUDE_PATH . '/includes/define.php';

// Load server utilities
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_server.php';

//for debugging purposes
require_once MYOOS_INCLUDE_PATH . '/includes/debug.php';

// require  the list of project filenames
require_once MYOOS_INCLUDE_PATH . '/includes/filename.php';

// require  the list of project database tables
require_once MYOOS_INCLUDE_PATH . '/includes/tables.php';

// define general functions used application-wide
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_global.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_kernel.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_input.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_output.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_encoded.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_coupon.php';

// initialize
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_user.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_products_history.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_shopping_cart.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_navigation_history.php';


// require the database functions
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
$configuration_result = $dbconn->Execute($configuration_query);


while ($configuration = $configuration_result->fields) {
    define($configuration['cfg_key'], $configuration['cfg_value']);
    // Move that ADOdb pointer!
    $configuration_result->MoveNext();
}

require_once MYOOS_INCLUDE_PATH . '/includes/lib/Phoenix/Core/Session.php';
$session = new Phoenix_Session();

// set the session name and save path
$session->setName('PHOENIXSID');

$sSid = $session->getName();
// set the session ID if it exists
if (isset($_POST[$sSid]) && !empty($_POST[$sSid])) {
    $session->start();
} elseif (isset($_COOKIE[$sSid])) {
    $session->start();
} elseif (isset($_GET[$sSid]) && !empty($_GET[$sSid])) {
    $session->start();
}

// Cross-Site Scripting attack defense
// oos_secure_input();


// set the language
$sLanguage = isset($_SESSION['language']) ? oos_var_prep_for_os($_SESSION['language']) : DEFAULT_LANGUAGE;
$nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_LANGUAGE_ID;
$sLanguageCode = isset($_SESSION['iso_639_1']) ? oos_var_prep_for_os($_SESSION['iso_639_1']) : DEFAULT_LANGUAGE_CODE;
$sLanguageName = isset($_SESSION['languages_name']) ? oos_var_prep_for_os($_SESSION['languages_name']) : DEFAULT_LANGUAGE_NAME;

$ADODB_LANG = $sLanguageCode;

if (!isset($_SESSION['language']) || isset($_GET['language'])) {
    // include the language class
    include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_language.php';
    $oLang = new language();

    if (isset($_GET['language']) && is_string($_GET['language'])) {
        // start the session
        if ($session->hasStarted() === false) {
            $session->start();
        }

		$language = filter_string_polyfill(filter_input(INPUT_GET, 'language'));
        $oLang->set_language($language);
    } else {
        $oLang->get_browser_language();
    }

    $sLanguage = $oLang->language['iso_639_2'];
    $nLanguageID = $oLang->language['id'];
    $sLanguageCode = $oLang->language['iso_639_1'];
    $sLanguageName = $oLang->language['name'];

    if (isset($_SESSION)) {
        $_SESSION['language'] = $oLang->language['iso_639_2'];
        $_SESSION['language_id'] = $oLang->language['id'];
        $_SESSION['iso_639_1'] = $oLang->language['iso_639_1'];
        $_SESSION['languages_name'] = $oLang->language['name'];
    }
}
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . oos_var_prep_for_os($sLanguage) . '.php';


// currency
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_currencies.php';
$oCurrencies = new currencies();
$sCurrency = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
if (!isset($_SESSION['currency']) || isset($_GET['currency'])) {
    if (isset($_GET['currency']) && oos_currency_exits($_GET['currency'])) {
        // start the session
        if ($session->hasStarted() === false) {
            $session->start();
        }

		$sCurrency = filter_string_polyfill(filter_input(INPUT_GET, 'currency'));
    }

    if (isset($_SESSION)) {
        $_SESSION['currency'] = $sCurrency;
        $_SESSION['currency_title'] = $oCurrencies->get_currencies_title($sCurrency);
    }
}


if ($session->hasStarted() === true) {
    if (!(preg_match('/^[a-z0-9]{26}$/i', $session->getId()) || preg_match('/^[a-z0-9]{32}$/i', $session->getId()))) {
        $session->regenerate(true);
    }

    if (!isset($_SESSION['user'])) {
        $_SESSION['user'] = new oosUser();
        $_SESSION['user']->anonymous();
    }

    // products history
    if (!isset($_SESSION['products_history'])) {
        $_SESSION['products_history'] = new oosProductsHistory();
    }

    // navigation history
    if (!isset($_SESSION['navigation'])) {
        $_SESSION['navigation'] = new navigationHistory();
    }

    // verify the browser user agent
    $http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? oos_var_prep_for_os($_SERVER['HTTP_USER_AGENT']) : '';

    if (!isset($_SESSION['session_user_agent'])) {
        $_SESSION['session_user_agent'] = $http_user_agent;
    }

    if ($_SESSION['session_user_agent'] != $http_user_agent) {
        $session->expire();
        exit(json_encode('No User Agent.'));
    }

    // verify the IP address
    if (!isset($_SESSION['session_ip_address'])) {
        $_SESSION['session_ip_address'] = oos_server_get_remote();
    }

    if ($_SESSION['session_ip_address'] != oos_server_get_remote()) {
        $session->expire();
        exit(json_encode('No IP Address.'));
    }
} else {
    $oUser = new oosUser();
    $oUser->anonymous();
}

$aUser = [];
$aUser = isset($_SESSION['user']) ? $_SESSION['user']->group : $oUser->group;


if ($session->hasStarted() === true) {
    // create the shopping cart
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = new shoppingCart();
    }
    $_SESSION['cart']->calculate();
}


require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_plugin_event.php';
$oEvent = new plugin_event();
$oEvent->getInstance();


require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validations.php';

// Shopping cart actions
if (isset($_GET['action']) || isset($_POST['action'])) {
    // Shopping cart actions
    include_once MYOOS_INCLUDE_PATH . '/includes/cart_actions.php';
}
