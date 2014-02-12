<?php
/* ----------------------------------------------------------------------
   $Id: index.php 299 2013-04-13 16:10:28Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 20013 by the MyOOS Development Team.
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
if (version_compare(PHP_VERSION, '5.2.3', '<'))
{
    header('Content-type: text/html; charset=utf-8', true, 503);

    echo '<h2>Fehler</h2>';
    echo 'Auf Ihrem Server läuft PHP version ' . PHP_VERSION . ', MyOOS benötigt mindestens PHP 5.2.3';

    echo '<h2>Error</h2>';
    echo 'Your server is running PHP version ' . PHP_VERSION . ' but MyOOS requires at least PHP 5.2.3';
    return;
}

//setting basic configuration parameters
if (function_exists('ini_set'))
{
	ini_set('session.use_trans_sid', 0);
	ini_set('url_rewriter.tags', '');
	ini_set('xdebug.show_exception_trace', 0);
	ini_set('magic_quotes_runtime', 0);
	// ini_set('display_errors', false);
}


define('MYOOS_DOCUMENT_ROOT', dirname(__FILE__)=='/'?'':dirname(__FILE__));
if(is_readable(MYOOS_DOCUMENT_ROOT . '/bootstrap.php'))
{
	require_once MYOOS_DOCUMENT_ROOT . '/bootstrap.php';
}

if(!defined('MYOOS_INCLUDE_PATH'))
{
	define('MYOOS_INCLUDE_PATH', MYOOS_DOCUMENT_ROOT);
}

define('OOS_VALID_MOD', 'yes');
require_once MYOOS_INCLUDE_PATH . '/includes/oos_main.php';

if (is_readable('includes/content/' . $sContent . '.php'))
{
    require_once MYOOS_INCLUDE_PATH . '/includes/content/' . $sContent . '.php';
}
else
{
    oos_redirect(oos_href_link($aContents['error404']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/oos_nice_exit.php';


