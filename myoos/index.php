<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * Set the error reporting level. Unless you have a special need, E_ALL is a
 * good level for error reporting.
 */
// error_reporting(0);
error_reporting(E_ALL & ~E_STRICT);

//setting basic configuration parameters
if (function_exists('ini_set')) {
    ini_set('session.use_trans_sid', 0);
    ini_set('url_rewriter.tags', '');
    ini_set('xdebug.show_exception_trace', 0);
    ini_set('magic_quotes_runtime', 0);
    ini_set('display_errors', true);
}



use Symfony\Component\HttpFoundation\Request;

//Load Composer's autoloader
require 'vendor/autoload.php';

$request = Request::createFromGlobals();

define('MYOOS_INCLUDE_PATH', __DIR__ == '/' ? '' : __DIR__);

define('OOS_VALID_MOD', true);
require 'includes/main.php';


if (empty($sContent) || !is_string($sContent)) {
    $sContent = $aContents['403'];
    include MYOOS_INCLUDE_PATH . '/includes/content/error403.php'; // 403 Forbidden
} elseif (is_readable('includes/content/' . $sContent . '.php')) {
    include MYOOS_INCLUDE_PATH . '/includes/content/' . $sContent . '.php';
} else {
    $sContent = $aContents['404'];
    include MYOOS_INCLUDE_PATH . '/includes/content/error404.php'; // Module not found
}

require 'includes/nice_exit.php';
