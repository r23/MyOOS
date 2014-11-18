<?php
/* ----------------------------------------------------------------------
   $Id: function_session.php,v 1.1 2007/06/12 16:49:27 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/**
 * Session Support
 *
 * @package sessions
 * @license GPL <http://www.gnu.org/licenses/gpl.html>
 * @link http://www.oos-shop.de
 */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


/**
 * Return session_id
 *
 * @private
 */
function oos_session_id($sSessid = '')
{
	if (!empty($sSessid)) {
		return session_id($sSessid);
	} else {
		return session_id();
    }
}


/**
 * Return session_name
 *
 * @private
*/
function oos_session_name($sName = '')
{
	if (!empty($sName)) {
		return session_name($sName);
	} else {
		return session_name();
    }
}


/**
 * PHP function to close the session
 *
 * @private
 */
function oos_session_close()
{
	if (function_exists('session_close')) {
		return session_close();
    }
}


/**
 * Return session_save_path
 *
 * @private
 */
function oos_session_save_path($sPath = '')
{
	if (!empty($sPath)) {
		return session_save_path($sPath);
	} else {
		return session_save_path();
	}
}


 /**
  * PHP function to start the session
  *
  * @private
  */
function oos_session_start()
{

	// Session
	$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$spider_flag = FALSE;
	$spider_kill_sid = 'false';

	// set the top level domains
	$http_domain = oos_server_get_top_level_domain(OOS_HTTP_SERVER);
	$https_domain = oos_server_get_top_level_domain(OOS_HTTPS_SERVER);
	$current_domain = (($request_type == 'NONSSL') ? $http_domain : $https_domain);

	// set the session cookie parameters
	if (function_exists('session_set_cookie_params')) {
		session_set_cookie_params(0, '/', (oos_is_not_null($current_domain) ? '.' . $current_domain : ''));
	} elseif (function_exists('ini_set')) {
		@ini_set('session.cookie_lifetime', '0');
		@ini_set('session.cookie_path', '/');
		@ini_set('session.cookie_domain', (oos_is_not_null($current_domain) ? '.' . $current_domain : ''));
	}

	// garbage collection may disabled by default (e.g., Debian)
	if (ini_get('session.gc_probability') == 0) {
		@ini_set('session.gc_probability', 1);
	}


	// set the session ID if it exists
	if (isset($_POST[oos_session_name()])) {
		oos_session_id($_POST[oos_session_name()]);
	} elseif (isset($_GET[oos_session_name()])) {
		oos_session_id($_GET[oos_session_name()]);
	}



	if (empty($user_agent) === FALSE) {
		$spider_agent = @parse_ini_file('includes/ini/spiders.ini');

		foreach ($spider_agent as $spider) {
			if (empty($spider) === FALSE) {
				if (strpos($user_agent, trim($spider)) !== FALSE) {
					$spider_kill_sid = 'true';
					$spider_flag = TRUE;
					break;
				}
			}
		}
	}


	
	if ($spider_flag === FALSE) {
		// set the session name and save path
		oos_session_name('OOSSID');

		// lets start our session
		session_start();
	}

	if (!isset($_SESSION)) {
		$_SESSION = array();
	}

	// create the shopping cart
	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = new shoppingCart();
	}

	// navigation history
	if (!isset($_SESSION['navigation'])) {
		$_SESSION['navigation'] = new oosNavigationHistory();
	}

	// products history
	if (!isset($_SESSION['products_history'])) 	{
		$_SESSION['products_history'] = new oosProductsHistory();
	}

	if (!isset($_SESSION['member'])) {
		$_SESSION['member'] = new oosMember();
		$_SESSION['member']->default_member();
	}

	$aContents = oos_get_content();
	
	// verify the browser user agent
	$http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

	if (!isset($_SESSION['session_user_agent'])) {
		$_SESSION['session_user_agent'] = $http_user_agent;
	}

	if ($_SESSION['session_user_agent'] != $http_user_agent) {
		session_destroy();
		oos_redirect(oos_link($aContents['login'], '', 'SSL'));
	}

	// verify the IP address
	if (!isset($_SESSION['session_ip_address'])) {
		$_SESSION['session_ip_address'] = oos_server_get_remote();
	}

	if ($_SESSION['session_ip_address'] != oos_server_get_remote()) {
		session_destroy();
		oos_redirect(oos_link($aContents['login'], '', 'SSL'));
	}

}


/**
 * @return bool
 */
function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}
