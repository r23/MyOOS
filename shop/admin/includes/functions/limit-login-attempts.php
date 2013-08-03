<?php
/* ----------------------------------------------------------------------
   $Id: $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Plugin Name: Limit Login Attempts
   Plugin URI: http://devel.kostdoktorn.se/limit-login-attempts
   Description: Limit rate of login attempts, including by way of cookies, for each IP.
   Author: Johan Eenfeldt
   Author URI: http://devel.kostdoktorn.se
   Text Domain: limit-login-attempts
   Version: 1.7.1

   Copyright 2008 - 2012 Johan Eenfeldt

   Thanks to Michael Skerwiderski for reverse proxy handling suggestions.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );
  
/*
 * Constants
 */

/* Different ways to get remote address: direct & behind proxy */
define('LIMIT_LOGIN_DIRECT_ADDR', 'REMOTE_ADDR');
define('LIMIT_LOGIN_PROXY_ADDR', 'HTTP_X_FORWARDED_FOR');

/* Notify value checked against these in limit_login_sanitize_variables() */
define('LIMIT_LOGIN_LOCKOUT_NOTIFY_ALLOWED', 'log,email');

/*
 * Variables
 *
 * Assignments are for default value -- change on admin page.
 */

$limit_login_options =
	array(
		  /* Lock out after this many tries */
		  , 'allowed_retries' => 4

		  /* Lock out for this many seconds */
		  , 'lockout_duration' => 1200 // 20 minutes

		  /* Long lock out after this many lockouts */
		  , 'allowed_lockouts' => 4

		  /* Long lock out for this many seconds */
		  , 'long_duration' => 86400 // 24 hours

		  /* Reset failed attempts after this many seconds */
		  , 'valid_duration' => 43200 // 12 hours

		  /* Also limit malformed/forged cookies? */
		  , 'cookies' => true

		  /* Notify on lockout. Values: '', 'log', 'email', 'log,email' */
		  , 'lockout_notify' => 'log'

		  /* If notify by email, do so after this number of lockouts */
		  , 'notify_email_after' => 4
		  );

$limit_login_my_error_shown = false; /* have we shown our stuff? */
$limit_login_just_lockedout = false; /* started this pageload??? */
$limit_login_nonempty_credentials = false; /* user and pwd nonempty */


/*
 * Startup
 */

/*
 * Functions start here
 */

 /* Get current option value */
function limit_login_option($option_name) {
	global $limit_login_options;

	if (isset($limit_login_options[$option_name])) {
		return $limit_login_options[$option_name];
	} else {
		return null;
	}
}



/*
 * Check if IP is whitelisted.
 *
 * This function allow external ip whitelisting using a filter. Note that it can
 * be called multiple times during the login process.
 *
 * Note that retries and statistics are still counted and notifications
 * done as usual for whitelisted ips , but no lockout is done.
 *
 * Example:
 * function my_ip_whitelist($allow, $ip) {
 * 	return ($ip == 'my-ip') ? true : $allow;
 * }
 * add_filter('limit_login_whitelist_ip', 'my_ip_whitelist', 10, 2);
 */
function is_limit_login_ip_whitelisted($ip = null) {
	if (is_null($ip)) {
		$ip = oos_server_get_remote();
	}
	$whitelisted = apply_filters('limit_login_whitelist_ip', false, $ip);

	return ($whitelisted === true);
}


/* Check if it is ok to login */
function is_limit_login_ok() {
	$ip = oos_server_get_remote();

	/* Check external whitelist filter */
	if (is_limit_login_ip_whitelisted($ip)) {
		return true;
	}

	/* lockout active? */
	$lockouts = get_option('limit_login_lockouts');
	return (!is_array($lockouts) || !isset($lockouts[$ip]) || time() >= $lockouts[$ip]);
}


/* Filter: allow login attempt? (called from wp_authenticate()) */
function limit_login_wp_authenticate_user($user, $password) {
	if (is_wp_error($user) || is_limit_login_ok() ) {
		return $user;
	}

	global $limit_login_my_error_shown;
	$limit_login_my_error_shown = true;

	$error = new WP_Error();
	// This error should be the same as in "shake it" filter below
	$error->add('too_many_retries', limit_login_error_msg());
	return $error;
}


/* Filter: add this failure to login page "Shake it!" */
function limit_login_failure_shake($error_codes) {
	$error_codes[] = 'too_many_retries';
	return $error_codes;
}


/*
 * Must be called in plugin_loaded (really early) to make sure we do not allow
 * auth cookies while locked out.
 */
function limit_login_handle_cookies() {
	if (is_limit_login_ok()) {
		return;
	}

	limit_login_clear_auth_cookie();
}


/*
 * Action: failed cookie login hash
 *
 * Make sure same invalid cookie doesn't get counted more than once.
 *
 * Requires WordPress version 3.0.0, previous versions use limit_login_failed_cookie()
 */
function limit_login_failed_cookie_hash($cookie_elements) {
	limit_login_clear_auth_cookie();

	/*
	 * Under some conditions an invalid auth cookie will be used multiple
	 * times, which results in multiple failed attempts from that one
	 * cookie.
	 *
	 * Unfortunately I've not been able to replicate this consistently and
	 * thus have not been able to make sure what the exact cause is.
	 *
	 * Probably it is because a reload of for example the admin dashboard
	 * might result in multiple requests from the browser before the invalid
	 * cookie can be cleard.
	 *
	 * Handle this by only counting the first attempt when the exact same
	 * cookie is attempted for a user.
	 */

	extract($cookie_elements, EXTR_OVERWRITE);

	// Check if cookie is for a valid user
	$user = get_userdatabylogin($username);
	if (!$user) {
		// "shouldn't happen" for this action
		limit_login_failed($username);
		return;
	}

	$previous_cookie = get_user_meta($user->ID, 'limit_login_previous_cookie', true);
	if ($previous_cookie && $previous_cookie == $cookie_elements) {
		// Identical cookies, ignore this attempt
		return;
	}

	// Store cookie
	if ($previous_cookie)
		update_user_meta($user->ID, 'limit_login_previous_cookie', $cookie_elements);
	else
		add_user_meta($user->ID, 'limit_login_previous_cookie', $cookie_elements, true);

	limit_login_failed($username);
}


/*
 * Action: successful cookie login
 *
 * Clear any stored user_meta.
 *
 * Requires WordPress version 3.0.0, not used in previous versions
 */
function limit_login_valid_cookie($cookie_elements, $user) {
	/*
	 * As all meta values get cached on user load this should not require
	 * any extra work for the common case of no stored value.
	 */

	if (get_user_meta($user->ID, 'limit_login_previous_cookie')) {
		delete_user_meta($user->ID, 'limit_login_previous_cookie');
	}
}


/* Action: failed cookie login (calls limit_login_failed()) */
function limit_login_failed_cookie($cookie_elements) {
	limit_login_clear_auth_cookie();

	/*
	 * Invalid username gets counted every time.
	 */

	limit_login_failed($cookie_elements['username']);
}


/* Make sure auth cookie really get cleared (for this session too) */
function limit_login_clear_auth_cookie() {
	wp_clear_auth_cookie();

	if (!empty($_COOKIE[AUTH_COOKIE])) {
		$_COOKIE[AUTH_COOKIE] = '';
	}
	if (!empty($_COOKIE[SECURE_AUTH_COOKIE])) {
		$_COOKIE[SECURE_AUTH_COOKIE] = '';
	}
	if (!empty($_COOKIE[LOGGED_IN_COOKIE])) {
		$_COOKIE[LOGGED_IN_COOKIE] = '';
	}
}

/*
 * Action when login attempt failed
 *
 * Increase nr of retries (if necessary). Reset valid value. Setup
 * lockout if nr of retries are above threshold. And more!
 *
 * A note on external whitelist: retries and statistics are still counted and
 * notifications done as usual, but no lockout is done.
 */
function limit_login_failed($username) {
	$ip = oos_server_get_remote();

	/* if currently locked-out, do not add to retries */
	$lockouts = get_option('limit_login_lockouts');
	if (!is_array($lockouts)) {
		$lockouts = array();
	}
	if(isset($lockouts[$ip]) && time() < $lockouts[$ip]) {
		return;
	}

	/* Get the arrays with retries and retries-valid information */
	$retries = get_option('limit_login_retries');
	$valid = get_option('limit_login_retries_valid');
	if (!is_array($retries)) {
		$retries = array();
		add_option('limit_login_retries', $retries, '', 'no');
	}
	if (!is_array($valid)) {
		$valid = array();
		add_option('limit_login_retries_valid', $valid, '', 'no');
	}

	/* Check validity and add one to retries */
	if (isset($retries[$ip]) && isset($valid[$ip]) && time() < $valid[$ip]) {
		$retries[$ip] ++;
	} else {
		$retries[$ip] = 1;
	}
	$valid[$ip] = time() + limit_login_option('valid_duration');

	/* lockout? */
	if($retries[$ip] % limit_login_option('allowed_retries') != 0) {
		/* 
		 * Not lockout (yet!)
		 * Do housecleaning (which also saves retry/valid values).
		 */
		limit_login_cleanup($retries, null, $valid);
		return;
	}

	/* lockout! */

	$whitelisted = is_limit_login_ip_whitelisted($ip);

	$retries_long = limit_login_option('allowed_retries')
		* limit_login_option('allowed_lockouts');

	/* 
	 * Note that retries and statistics are still counted and notifications
	 * done as usual for whitelisted ips , but no lockout is done.
	 */
	if ($whitelisted) {
		if ($retries[$ip] >= $retries_long) {
			unset($retries[$ip]);
			unset($valid[$ip]);
		}
	} else {
		global $limit_login_just_lockedout;
		$limit_login_just_lockedout = true;

		/* setup lockout, reset retries as needed */
		if ($retries[$ip] >= $retries_long) {
			/* long lockout */
			$lockouts[$ip] = time() + limit_login_option('long_duration');
			unset($retries[$ip]);
			unset($valid[$ip]);
		} else {
			/* normal lockout */
			$lockouts[$ip] = time() + limit_login_option('lockout_duration');
		}
	}

	/* do housecleaning and save values */
	limit_login_cleanup($retries, $lockouts, $valid);

	/* do any notification */
	limit_login_notify($username);

	/* increase statistics */
	$total = get_option('limit_login_lockouts_total');
	if ($total === false || !is_numeric($total)) {
		add_option('limit_login_lockouts_total', 1, '', 'no');
	} else {
		update_option('limit_login_lockouts_total', $total + 1);
	}
}


/* Clean up old lockouts and retries, and save supplied arrays */
function limit_login_cleanup($retries = null, $lockouts = null, $valid = null) {
	$now = time();
	$lockouts = !is_null($lockouts) ? $lockouts : get_option('limit_login_lockouts');

	/* remove old lockouts */
	if (is_array($lockouts)) {
		foreach ($lockouts as $ip => $lockout) {
			if ($lockout < $now) {
				unset($lockouts[$ip]);
			}
		}
		update_option('limit_login_lockouts', $lockouts);
	}

	/* remove retries that are no longer valid */
	$valid = !is_null($valid) ? $valid : get_option('limit_login_retries_valid');
	$retries = !is_null($retries) ? $retries : get_option('limit_login_retries');
	if (!is_array($valid) || !is_array($retries)) {
		return;
	}

	foreach ($valid as $ip => $lockout) {
		if ($lockout < $now) {
			unset($valid[$ip]);
			unset($retries[$ip]);
		}
	}

	/* go through retries directly, if for some reason they've gone out of sync */
	foreach ($retries as $ip => $retry) {
		if (!isset($valid[$ip])) {
			unset($retries[$ip]);
		}
	}

	update_option('limit_login_retries', $retries);
	update_option('limit_login_retries_valid', $valid);
}


/* Is this WP Multisite? */
function is_limit_login_multisite() {
	return function_exists('get_site_option') && function_exists('is_multisite') && is_multisite();
}


/* Email notification of lockout to admin (if configured) */
function limit_login_notify_email($user) {
	$ip = oos_server_get_remote();
	$whitelisted = is_limit_login_ip_whitelisted($ip);

	$retries = get_option('limit_login_retries');
	if (!is_array($retries)) {
		$retries = array();
	}

	/* check if we are at the right nr to do notification */
	if ( isset($retries[$ip])
		 && ( ($retries[$ip] / limit_login_option('allowed_retries'))
			  % limit_login_option('notify_email_after') ) != 0 ) {
		return;
	}

	/* Format message. First current lockout duration */
	if (!isset($retries[$ip])) {
		/* longer lockout */
		$count = limit_login_option('allowed_retries')
			* limit_login_option('allowed_lockouts');
		$lockouts = limit_login_option('allowed_lockouts');
		$time = round(limit_login_option('long_duration') / 3600);
		$when = sprintf(_n('%d hour', '%d hours', $time, 'limit-login-attempts'), $time);
	} else {
		/* normal lockout */
		$count = $retries[$ip];
		$lockouts = floor($count / limit_login_option('allowed_retries'));
		$time = round(limit_login_option('lockout_duration') / 60);
		$when = sprintf(_n('%d minute', '%d minutes', $time, 'limit-login-attempts'), $time);
	}

	if ($whitelisted) {
		$subject = sprintf(__("[%s] Failed login attempts from whitelisted IP"
				      , 'limit-login-attempts')
				   , STORE_NAME);
	} else {
		$subject = sprintf(__("[%s] Too many failed login attempts"
				      , 'limit-login-attempts')
				   , STORE_NAME);
	}

	$message = sprintf(__("%d failed login attempts (%d lockout(s)) from IP: %s"
			      , 'limit-login-attempts') . "\r\n\r\n"
			   , $count, $lockouts, $ip);
	if ($user != '') {
		$message .= sprintf(__("Last user attempted: %s", 'limit-login-attempts')
				    . "\r\n\r\n" , $user);
	}
	if ($whitelisted) {
		$message .= __("IP was NOT blocked because of external whitelist.", 'limit-login-attempts');
	} else {
		$message .= sprintf(__("IP was blocked for %s", 'limit-login-attempts'), $when);
	}


    oos_mail(STORE_NAME, STORE_OWNER_EMAIL_ADDRESS, $subject, $message);
  
}


/* Logging of lockout (if configured) */
function limit_login_notify_log($user) {
	$log = $option = get_option('limit_login_logged');
	if (!is_array($log)) {
		$log = array();
	}
	$ip = oos_server_get_remote();

	/* can be written much simpler, if you do not mind php warnings */
	if (isset($log[$ip])) {
		if (isset($log[$ip][$user])) {	
			$log[$ip][$user]++;
		} else {
			$log[$ip][$user] = 1;
		}
	} else {
		$log[$ip] = array($user => 1);
	}

	if ($option === false) {
		add_option('limit_login_logged', $log, '', 'no'); /* no autoload */
	} else {
		update_option('limit_login_logged', $log);
	}
}


/* Handle notification in event of lockout */
function limit_login_notify($user) {
	$args = explode(',', limit_login_option('lockout_notify'));

	if (empty($args)) {
		return;
	}

	foreach ($args as $mode) {
		switch (trim($mode)) {
		case 'email':
			// limit_login_notify_email($user);
			break;
		case 'log':
			limit_login_notify_log($user);
			break;
		}
	}
}


/* Construct informative error message */
function limit_login_error_msg() {
	$ip = oos_server_get_remote();
	$lockouts = get_option('limit_login_lockouts');

	$msg = __('<strong>ERROR</strong>: Too many failed login attempts.', 'limit-login-attempts') . ' ';

	if (!is_array($lockouts) || !isset($lockouts[$ip]) || time() >= $lockouts[$ip]) {
		/* Huh? No timeout active? */
		$msg .=  __('Please try again later.', 'limit-login-attempts');
		return $msg;
	}

	$when = ceil(($lockouts[$ip] - time()) / 60);
	if ($when > 60) {
		$when = ceil($when / 60);
		$msg .= sprintf(_n('Please try again in %d hour.', 'Please try again in %d hours.', $when, 'limit-login-attempts'), $when);
	} else {
		$msg .= sprintf(_n('Please try again in %d minute.', 'Please try again in %d minutes.', $when, 'limit-login-attempts'), $when);
	}

	return $msg;
}


/* Construct retries remaining message */
function limit_login_retries_remaining_msg() {
	$ip = oos_server_get_remote();
	$retries = get_option('limit_login_retries');
	$valid = get_option('limit_login_retries_valid');

	/* Should we show retries remaining? */

	if (!is_array($retries) || !is_array($valid)) {
		/* no retries at all */
		return '';
	}
	if (!isset($retries[$ip]) || !isset($valid[$ip]) || time() > $valid[$ip]) {
		/* no: no valid retries */
		return '';
	}
	if (($retries[$ip] % limit_login_option('allowed_retries')) == 0 ) {
		/* no: already been locked out for these retries */
		return '';
	}

	$remaining = max((limit_login_option('allowed_retries') - ($retries[$ip] % limit_login_option('allowed_retries'))), 0);
	return sprintf(_n("<strong>%d</strong> attempt remaining.", "<strong>%d</strong> attempts remaining.", $remaining, 'limit-login-attempts'), $remaining);
}


/* Return current (error) message to show, if any */
function limit_login_get_message() {
	/* Check external whitelist */
	if (is_limit_login_ip_whitelisted()) {
		return '';
	}

	/* Is lockout in effect? */
	if (!is_limit_login_ok()) {
		return limit_login_error_msg();
	}

	return limit_login_retries_remaining_msg();
}


/* Should we show errors and messages on this page? */
function should_limit_login_show_msg() {
	if (isset($_GET['key'])) {
		/* reset password */
		return false;
	}

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

	return ( $action != 'lostpassword' && $action != 'retrievepassword'
			 && $action != 'resetpass' && $action != 'rp'
			 && $action != 'register' );
}


/* Fix up the error message before showing it */
function limit_login_fixup_error_messages($content) {
	global $limit_login_just_lockedout, $limit_login_nonempty_credentials, $limit_login_my_error_shown;

	if (!should_limit_login_show_msg()) {
		return $content;
	}

	/*
	 * During lockout we do not want to show any other error messages (like
	 * unknown user or empty password).
	 */
	if (!is_limit_login_ok() && !$limit_login_just_lockedout) {
		return limit_login_error_msg();
	}

	/*
	 * We want to filter the messages 'Invalid username' and
	 * 'Invalid password' as that is an information leak regarding user
	 * account names (prior to WP 2.9?).
	 *
	 * Also, if more than one error message, put an extra <br /> tag between
	 * them.
	 */
	$msgs = explode("<br />\n", $content);

	if (strlen(end($msgs)) == 0) {
		/* remove last entry empty string */
		array_pop($msgs);
	}

	$count = count($msgs);
	$my_warn_count = $limit_login_my_error_shown ? 1 : 0;

	if ($limit_login_nonempty_credentials && $count > $my_warn_count) {
		/* Replace error message, including ours if necessary */
		$content = __('<strong>ERROR</strong>: Incorrect username or password.', 'limit-login-attempts') . "<br />\n";
		if ($limit_login_my_error_shown) {
			$content .= "<br />\n" . limit_login_get_message() . "<br />\n";
		}
		return $content;
	} elseif ($count <= 1) {
		return $content;
	}

	$new = '';
	while ($count-- > 0) {
		$new .= array_shift($msgs) . "<br />\n";
		if ($count > 0) {
			$new .= "<br />\n";
		}
	}

	return $new;
}


/* Add a message to login page when necessary */
function limit_login_add_error_message() {
	global $error, $limit_login_my_error_shown;

	if (!should_limit_login_show_msg() || $limit_login_my_error_shown) {
		return;
	}

	$msg = limit_login_get_message();

	if ($msg != '') {
		$limit_login_my_error_shown = true;
		$error .= $msg;
	}

	return;
}


/* Keep track of if user or password are empty, to filter errors correctly */
function limit_login_track_credentials($user, $password) {
	global $limit_login_nonempty_credentials;

	$limit_login_nonempty_credentials = (!empty($user) && !empty($password));
}


/*
 * Admin stuff
 */


/* Only change var if option exists */
function limit_login_get_option($option, $var_name) {
	$a = get_option($option);

	if ($a !== false) {
		global $limit_login_options;

		$limit_login_options[$var_name] = $a;
	}
}


/* Setup global variables from options */
function limit_login_setup_options() {
	limit_login_get_option('limit_login_allowed_retries', 'allowed_retries');
	limit_login_get_option('limit_login_lockout_duration', 'lockout_duration');
	limit_login_get_option('limit_login_valid_duration', 'valid_duration');
	limit_login_get_option('limit_login_cookies', 'cookies');
	limit_login_get_option('limit_login_lockout_notify', 'lockout_notify');
	limit_login_get_option('limit_login_allowed_lockouts', 'allowed_lockouts');
	limit_login_get_option('limit_login_long_duration', 'long_duration');
	limit_login_get_option('limit_login_notify_email_after', 'notify_email_after');

	limit_login_sanitize_variables();
}


/* Update options in db from global variables */
function limit_login_update_options() {
	update_option('limit_login_allowed_retries', limit_login_option('allowed_retries'));
	update_option('limit_login_lockout_duration', limit_login_option('lockout_duration'));
	update_option('limit_login_allowed_lockouts', limit_login_option('allowed_lockouts'));
	update_option('limit_login_long_duration', limit_login_option('long_duration'));
	update_option('limit_login_valid_duration', limit_login_option('valid_duration'));
	update_option('limit_login_lockout_notify', limit_login_option('lockout_notify'));
	update_option('limit_login_notify_email_after', limit_login_option('notify_email_after'));
	update_option('limit_login_cookies', limit_login_option('cookies') ? '1' : '0');
}


/* Make sure the variables make sense -- simple integer */
function limit_login_sanitize_simple_int($var_name) {
	global $limit_login_options;

	$limit_login_options[$var_name] = max(1, intval(limit_login_option($var_name)));
}


/* Make sure the variables make sense */
function limit_login_sanitize_variables() {
	global $limit_login_options;

	limit_login_sanitize_simple_int('allowed_retries');
	limit_login_sanitize_simple_int('lockout_duration');
	limit_login_sanitize_simple_int('valid_duration');
	limit_login_sanitize_simple_int('allowed_lockouts');
	limit_login_sanitize_simple_int('long_duration');

	$limit_login_options['cookies'] = !!limit_login_option('cookies');

	$notify_email_after = max(1, intval(limit_login_option('notify_email_after')));
	$limit_login_options['notify_email_after'] = min(limit_login_option('allowed_lockouts'), $notify_email_after);

	$args = explode(',', limit_login_option('lockout_notify'));
	$args_allowed = explode(',', LIMIT_LOGIN_LOCKOUT_NOTIFY_ALLOWED);
	$new_args = array();
	foreach ($args as $a) {
		if (in_array($a, $args_allowed)) {
			$new_args[] = $a;
		}
	}
	$limit_login_options['lockout_notify'] = implode(',', $new_args);

}


/* Add admin options page */
function limit_login_admin_menu() {
	global $wp_version;

	// Modern WP?
	if (version_compare($wp_version, '3.0', '>=')) {
	    add_options_page('Limit Login Attempts', 'Limit Login Attempts', 'manage_options', 'limit-login-attempts', 'limit_login_option_page');
	    return;
	}

	// Older WPMU?
	if (function_exists("get_current_site")) {
	    add_submenu_page('wpmu-admin.php', 'Limit Login Attempts', 'Limit Login Attempts', 9, 'limit-login-attempts', 'limit_login_option_page');
	    return;
	}

	// Older WP
	add_options_page('Limit Login Attempts', 'Limit Login Attempts', 9, 'limit-login-attempts', 'limit_login_option_page');
}

