<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2020 by the MyOOS Development Team.
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
   
//setting basic configuration parameters
if (function_exists('ini_set')) {
	ini_set('session.use_trans_sid', 0);
	ini_set('url_rewriter.tags', '');
	ini_set('xdebug.show_exception_trace', 0);
	ini_set('magic_quotes_runtime', 0);
	// ini_set('display_errors', false);
}

use Symfony\Component\HttpFoundation\Request;


$autoloader = require_once __DIR__ . '/vendor/autoload.php';
$request = Request::createFromGlobals();

define('MYOOS_INCLUDE_PATH', dirname(__FILE__)=='/'?'':dirname(__FILE__));
define('OOS_VALID_MOD', true);

// Ajax request 
if ($request->isXmlHttpRequest()) {  

	require 'includes/main_ajax.php';
 
	$headers = apache_request_headers();
	if (isset($headers['X-CSRF-TOKEN'])) {
		if ($headers['X-CSRF-TOKEN'] !== $_SESSION['csrf_token']) {
			// Reset token
			unset($_SESSION["csrf_token"]);
			exit(json_encode('Wrong CSRF token.'));
		}
	} else {
		exit(json_encode('No CSRF token.'));
	}


	if (isset($_POST['name']) || is_string($_POST['name'])) {
		$sContent = oos_var_prep_for_os($_POST['name']);
	}

// shopping_cart, clear_cart

	if ( empty( $sContent ) || !is_string( $sContent ) ) {
		exit(json_encode('403 Forbidden'));
	} elseif (is_readable('includes/ajax/' . $sContent . '.php')) {
		require MYOOS_INCLUDE_PATH . '/includes/ajax/' . $sContent . '.php';
	} else {
		exit(json_encode(' Module not found'));
	}

	require 'includes/nice_exit.php';

} else {  
	http_response_code(403);
	echo 'Error 403 Forbidden'; 	
} 


