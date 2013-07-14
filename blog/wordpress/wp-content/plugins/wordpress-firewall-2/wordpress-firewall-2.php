<?php
/*
Plugin Name: Wordpress Firewall 2
Plugin URI: http://matthewpavkov.com/wordpress-plugins/wordpress-firewall-2.html
Description: This Wordpress plugin monitors web requests to identify and stop the most obvious attacks.
Version: 1.3
Author: Matthew Pavkov
Author URI: http://matthewpavkov.com

Updated: October 26, 2010

This plugin was originally written by SEO Egghead, Inc.
The plugin info listed below is from the last release by them:

- Plugin Name: Wordpress Firewall
- Plugin URI: http://www.seoegghead.com/software/wordpress-firewall.seo
- Description: Blocks suspicious-looking requests to WordPress.
- Author: SEO Egghead, Inc.
- Version: 1.25 for WP 2.x
- Author URI: http://www.seoegghead.com/
*/

// Function necessary to make this plugin works, if it doesn't exist, create it
if(!function_exists('array_diff_key')){
    if ((@include_once 'PHP/Compat/Function/array_diff_key.php')) {}
	else{   	
	// Borrowed from PEAR_PHP_Compat.	
	function php_compat_array_diff_key()
	{
	    $args = func_get_args();
	    if (count($args) < 2) {
	        user_error('Wrong parameter count for array_diff_key()', E_USER_WARNING);
	        return;
	    }
	    // Check arrays
	    $array_count = count($args);
	    for ($i = 0; $i !== $array_count; $i++) {
	        if (!is_array($args[$i])) {
	            user_error('array_diff_key() Argument #' .
	                ($i + 1) . ' is not an array', E_USER_WARNING);
	            return;
	        }
	    }
	    $result = $args[0];
	    if (function_exists('array_key_exists')) {
	        // Optimize for >= PHP 4.1.0
	        foreach ($args[0] as $key => $value) {
	            for ($i = 1; $i !== $array_count; $i++) {
	                if (array_key_exists($key,$args[$i])) {
	                    unset($result[$key]);
	                    break;
	                }
	            }
	        }
	    } else {
	        foreach ($args[0] as $key1 => $value1) {
	            for ($i = 1; $i !== $array_count; $i++) {
	                foreach ($args[$i] as $key2 => $value2) {
	                    if ((string) $key1 === (string) $key2) {
	                        unset($result[$key2]);
	                        break 2;
	                    }
	                }
	            }
	        }
	    }
	    return $result; 
	}		
	    function array_diff_key()
	    {
	        $args = func_get_args();
	        return call_user_func_array('php_compat_array_diff_key', $args);
	    }
	}
}

// Security check to see if someone is accessing this file directly
if(preg_match("#^wordpress-firewall-2.php#", basename($_SERVER['PHP_SELF']))) exit();



/***
* Install
****/
add_option('WP_firewall_redirect_page', 'homepage');
add_option('WP_firewall_exclude_directory', 'allow');
add_option('WP_firewall_exclude_queries', 'allow');
add_option('WP_firewall_exclude_terms', 'allow');
add_option('WP_firewall_exclude_spaces', 'allow');
add_option('WP_firewall_exclude_file', 'allow');
add_option('WP_firewall_exclude_http', 'disallow');
add_option('WP_firewall_email_enable', 'enable');
add_option('WP_firewall_email_type', 'html');
add_option('WP_firewall_email_address', get_option('admin_email'));
add_option('WP_firewall_whitelisted_ip', 
	serialize(
		array(
			'0' => $_SERVER['REMOTE_ADDR']
		)
	)
);
add_option('WP_firewall_whitelisted_page', '');
add_option('WP_firewall_whitelisted_variable', '');
add_option('WP_firewall_plugin_url', get_option('siteurl') . '/wp-admin/options-general.php?page=' . basename(__FILE__));
add_option('WP_firewall_default_whitelisted_page', 
	serialize(
		array(
			array(
				'.*/wp-comments-post\.php',
				array(
					'url', 'comment'
				)
			),
			array(
				'.*/wp-admin/.*',
				array(
					'_wp_original_http_referer',
					'_wp_http_referer'
				)
			),
			array(
				'.*wp-login.php',
				array(
					'redirect_to'
				)
			),
			array(
				'.*',
				array(
					'comment_author_url_.*',
					'__utmz'
				)
			),
			'.*/wp-admin/options-general\.php',
			'.*/wp-admin/post-new\.php',
			'.*/wp-admin/page-new\.php',
			'.*/wp-admin/link-add\.php',
			'.*/wp-admin/post\.php',
			'.*/wp-admin/page\.php',
			'.*/wp-admin/admin-ajax.php'
		)
	)
);
add_option('WP_firewall_previous_attack_var', '');
add_option('WP_firewall_previous_attack_ip', '');
add_option('WP_firewall_email_limit', 'off');



/****
* Main function
****/
WP_firewall_check_exclusions();

function WP_firewall_check_exclusions() {
	$request_string = WP_firewall_check_whitelisted_variable();
	
	if($request_string == false) {
		//nothing to do
	} else {
		// Directory traversal - check directories
		if(get_option('WP_firewall_exclude_directory') == 'allow') {
			$exclude_terms = array('#etc/passwd#', '#proc/self/environ#', '#\.\./#');
			foreach($exclude_terms as $preg) {
				foreach($request_string as $key=>$value) {
					if(preg_match($preg, $value)) {
						if(!WP_firewall_check_ip_whitelist()) {
							WP_firewall_send_log_message($key, $value, 'directory-traversal-attack', 'Directory Traversal');
							WP_firewall_send_redirect();
						}
					}
				}
			}
		}
		// SQL injection - check queries
		if(get_option('WP_firewall_exclude_queries') == 'allow') {
			$exclude_terms = array('#concat\s*\(#i', '#group_concat#i', '#union.*select#i');
			foreach($exclude_terms as $preg) {
				foreach($request_string as $key=>$value) {
					if(preg_match($preg, $value)) {
						if(!WP_firewall_check_ip_whitelist()) {
							WP_firewall_send_log_message($key, $value, 'sql-injection-attack', 'SQL Injection');
							WP_firewall_send_redirect();
						}
					}
				}
			}
		}
		// WP SQL injection - check wp terms
		if(get_option('WP_firewall_exclude_terms') == 'allow') {
			$exclude_terms = array('#wp_#i', '#user_login#i', '#user_pass#i', '#0x[0-9a-f][0-9a-f]#i', '#/\*\*/#');
			foreach($exclude_terms as $preg) {
				foreach($request_string as $key=>$value) {
					if(preg_match($preg, $value)) {
						if(!WP_firewall_check_ip_whitelist()) {
							WP_firewall_send_log_message($key, $value, 'wp-specific-sql-injection-attack', 'WordPress-Specific SQL Injection');
							WP_firewall_send_redirect();
						}
					}
				}
			}
		}
		// Field truncation - check ... not sure yet
		if(get_option('WP_firewall_exclude_spaces') == 'allow') {
			$exclude_terms = array('#\s{49,}#i','#\x00#');
			foreach($exclude_terms as $preg) {
				foreach($request_string as $key=>$value) {
					if(preg_match('#\s{49,}#i', $value)) {
						if(!WP_firewall_check_ip_whitelist()) {
							WP_firewall_send_log_message($key, $value, 'field-truncation-attack', 'Field Truncation');
							WP_firewall_send_redirect();
						}
					}
				}
			}
		}
		// Block executable file upload - check exluded file types
		if(get_option('WP_firewall_exclude_file') == 'allow') {
			foreach ($_FILES as $file) {
				$file_extensions = 
					array(
						'#\.dll$#i', '#\.rb$#i', '#\.py$#i', '#\.exe$#i', '#\.php[3-6]?$#i', '#\.pl$#i', 
						'#\.perl$#i', '#\.ph[34]$#i', '#\.phl$#i', '#\.phtml$#i', '#\.phtm$#i'
					);
				 foreach($file_extensions as $regex) {
					if(preg_match($regex, $file['name'])) {
						// no ip check, should there be one?
				 		WP_firewall_send_log_message('$_FILE', $file['name'], 'executable-file-upload-attack', 'Executable File Upload');
						WP_firewall_send_redirect();	
					}
				 }
			}
		}
		// Block remote file execution - check for leading http/https
		// This can be problematic with many plugins, as it'll break requests
		// starting with http/https, however, may be still be useful
		if(get_option('WP_firewall_exclude_http') == 'allow') {
			$exclude_terms = array('#^http#i', '#\.shtml#i');
			foreach($exclude_terms as $preg) {
				foreach($request_string as $key=>$value) {
					if(preg_match($preg, $value)) {
						if(!WP_firewall_check_ip_whitelist()) {
							WP_firewall_send_log_message($key, $value, 'remote-file-execution-attack', 'Remote File Execution');
							WP_firewall_send_redirect();
						}
					}
				}
			}
		}
	}
}



/****
* Functions
****/
function WP_firewall_send_redirect() {
	$home_url = get_option('siteurl');
	if(get_option('WP_firewall_redirect_page') == '404page') {
		// Not clear if just including the 404 template is safe.
		// Not sure why it wouldn't be safe, but better safe than sorry...?
		// 404 could contain errors relaying info which could be useful to attacker...?
		header ("Location: $home_url/404/");
		exit();
	} else {
		header ("Location: $home_url");
		exit();
	}
}

function WP_firewall_check_whitelisted_variable() {
	preg_match('#([^?]+)?.*$#', $_SERVER['REQUEST_URI'], $url);
	$page_name = $url[1];
	$_a = array();
	$new_arr = WP_firewall_array_flatten($_REQUEST, $_a);

	foreach(unserialize(get_option('WP_firewall_default_whitelisted_page')) as $whitelisted_page) {
		if(!is_array($whitelisted_page)) {
			if(preg_match('#^' . $whitelisted_page . '$#', $page_name)) {
				return false;
			}
		} else {
			if(preg_match('#^' . $whitelisted_page[0] . '$#', $page_name)) {
				foreach($whitelisted_page[1] as $whitelisted_variable) {
					foreach(array_keys($new_arr) as $var) {
						if(preg_match('#^' . $whitelisted_variable . '$#', $var)) {
							$new_arr = array_diff_key($new_arr,array($var=>''));
						}
					}
				}
			}
		}
	}
	
	$pages = unserialize(get_option('WP_firewall_whitelisted_page'));
	$variables = unserialize(get_option('WP_firewall_whitelisted_variable'));
	$count = 0;
	
	while($count < sizeof($pages)) {
		$page_regex = preg_quote($pages[$count], '#');
		$page_regex = str_replace('\*', '.*', $page_regex);
		$var_regex = preg_quote($variables[$count], '#');
		$var_regex = str_replace('\*', '.*', $var_regex);
		
		if( $variables[$count] != '') {
			if(($pages[$count] == '') || (preg_match('#^' . $page_regex . '$#', $page_name))) {
				$temp_arr = $new_arr;
				foreach(array_keys($new_arr) as $var) {
					if(preg_match('#^' . $var_regex . '$#', $var)) {
						$new_arr = array_diff_key($new_arr, array($var=>''));
					}
				}
			}
		} elseif($pages[$count] != '') {
			if(preg_match('#^' . $page_regex . '$#', $page_name)) {
				return false;
			}
		}
		$count++;
	}
	return $new_arr;
}

function WP_firewall_check_ip_whitelist() {
	$current_ip = $_SERVER['REMOTE_ADDR'];
	$ips = unserialize(get_option('WP_firewall_whitelisted_ip'));
	if(is_array($ips)) {
		foreach($ips as $ip) {
			if(($current_ip == $ip) || ($current_ip == gethostbyname($ip))) {
				return true;
			}
		}
	}
	return false;
}

function WP_firewall_array_flatten($array, &$newArray, $prefix='', $delimiter='][', $level=0) {
	foreach($array as $key => $child) {
		if (is_array($child)) {
			$newPrefix = $prefix . $key . $delimiter;
			if($level == 0) {
				$newPrefix = $key . '[';
			}
			$newArray =& WP_firewall_array_flatten($child, $newArray, $newPrefix, $delimiter, $level+1);
		} else {
			(!$level) ? $post='' : $post=']';
			$newArray[$prefix . $key . $post] = $child;
		}
	}
	return $newArray;
}

function WP_firewall_assert_first() {
	$active_plugs = (get_option('active_plugins'));
	$active_plugs = array_diff($active_plugs, array("wordpress-firewall-2.php"));
	array_unshift($active_plugs, "wordpress-firewall-2.php");
}

function WP_firewall_send_log_message($bad_variable = '', $bad_value = '', $attack_type = '', $attack_category = '') {
	$bad_variable = htmlentities($bad_variable);
	$bad_value = htmlentities($bad_value);
	$offender_ip = $_SERVER['REMOTE_ADDR'];

	$limit_check = (
		get_option('WP_firewall_email_limit') == 'on' && 
		$offender_ip == get_option('WP_firewall_previous_attack_ip') && 
		$bad_variable == get_option('WP_firewall_previous_attack_var')
	);
	
	if(($address = get_option('WP_firewall_email_address')) && !$limit_check) {
		if(get_option('WP_firewall_email_limit') == 'off') {
			if(get_option(WP_firewall_email_type) == 'html') {
				$suppress_message = 'Repeated warnings for similar attacks are currently sent via email, <a href="' . get_option('WP_firewall_plugin_url') . '&suppress=0">click here</a> to suppress them.';
			} else {
				$suppress_message = 'Repeated warnings for similar attacks are currently sent via email, to suppress them: ' . get_option('WP_firewall_plugin_url') . '&suppress=0';
			}
		}
		$offending_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$variable_explain_url = 'http://matthewpavkov.com/wordpress-plugins/wordpress-attacks.html';// . $attack_type;
		$turn_off_email_url = get_option('WP_firewall_plugin_url') . '&turn_off_email=1';
		$whitelist_varibale_url = get_option('WP_firewall_plugin_url') . '&set_whitelist_variable=' . $bad_variable;
		
		$message_html = 
<<<EndMessage
<h3>WordPress Firewall has <font color="red">detected and blocked</font> a potential attack!</h3>
<table border="0" cellpadding="5">
	<tr>
		<td align="right"><strong>Web Page:&nbsp;&nbsp;</strong></td>
		<td>$offending_url<br /><small>Warning:&nbsp;URL may contain dangerous content!</small></td>
	</tr>
	<tr>
		<td align="right"><strong>Offending IP:&nbsp;&nbsp;</strong></td>
		<td>$offender_ip <a href="http://ip-lookup.net/?ip=$offender_ip">[ Get IP location ]</a></td>
	</tr>
	<tr>
		<td align="right"><strong>Offending Parameter:&nbsp;&nbsp;</strong></td>
		<td><font color="red"><strong>$bad_variable = $bad_value</strong></font></td>
	</tr>
</table>
<br />
<table border="0" cellpadding="5">
	<tr>
		<td align="left">This may be a "$attack_category Attack."<br /><br /><a href="$variable_explain_url">Click here</a> for more information on this type of attack.<br /><br />If you suspect this may be a false alarm because of something you recently did, try to confirm by repeating those actions. If so, whitelist it via the "whitelist this variable" link below. This will prevent future false alarms.<br /><br /><a href="$whitelist_varibale_url">Click here</a> to whitelist this variable.<br /><a href="$turn_off_email_url ">Click here</a> to turn off these emails.</td>
	</tr>
	<tr>
		<td>$suppress_message</td>
	</tr>
</table>
EndMessage;

		$message_text = 'WordPress Firewall has detected and blocked a potential attack!' . "\r\n\r\n" . 
			'Web Page: $offending_url' . "\r\n" . 
			'Warning: URL may contain dangerous content!' . "\r\n\r\n" . 
			'Offending IP: ' . $offender_ip . ' - http://ip-lookup.net/?ip=' . $offender_ip . "\r\n" . 
			'Offending Parameter: ' . $bad_variable . ' = ' . $bad_value . "\r\n\r\n" . 
			'This may be a "' . $attack_category . ' Attack."' . "\r\n\r\n" . 
			'For more information on this type of attack see: ' . $variable_explain_url . "\r\n\r\n" . 
			'If you suspect this may be a false alarm because of something you recently did, try to confirm by repeating those actions. If so, whitelist it via the "whitelist this variable" link below. This will prevent future false alarms.' . "\r\n\r\n" . 
			'To whitelist this variable: ' . $whitelist_varibale_url . "\r\n" . 
			'To turn off these emails: ' . $turn_off_email_url . "\r\n\r\n" . $suppress_message;

		$address = get_option('WP_firewall_email_address');
		$subject = 'Alert from WordPress Firewall on ' . get_option('siteurl');
		if(get_option(WP_firewall_email_type) == 'html') {
			$header = "Content-Type: text/html\r\n";
			$message = $message_html;
		} else {
			$message = $message_text;
		}
		$header .= "From: " . $address . "\r\n";
		mail($address, $subject, $message, $header);
	}
	
	update_option('WP_firewall_previous_attack_var', $bad_variable);
	update_option('WP_firewall_previous_attack_ip', $offender_ip);
}



/****
* Menu and page display
****/
add_action('admin_menu', 'WP_firewall_admin_menu');
function WP_firewall_admin_menu() {
	add_submenu_page('options-general.php', 'Firewall', 'Firewall', 10, __FILE__, 'WP_firewall_submenu');
}

// Add Settings link to plugins - code from GD Star Ratings
function add_settings_link($links, $file) {
	static $this_plugin;
	if(!$this_plugin) {
		$this_plugin = plugin_basename(__FILE__);
	}

	if($file == $this_plugin) {
		$settings_link = '<a href="options-general.php?page=' . $this_plugin . '">' . __("Settings", "wordpress-firewall-2") . '</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'add_settings_link', 10, 2);

function WP_firewall_submenu() {
	WP_firewall_assert_first();
	
	$action_url = $_SERVER['REQUEST_URI'];
	if ($_REQUEST['set_exclusions']) {
		update_option('WP_firewall_redirect_page', $_REQUEST['redirect_type']);
		update_option('WP_firewall_exclude_directory', $_REQUEST['block_directory']);
		update_option('WP_firewall_exclude_queries', $_REQUEST['block_queries']);
		update_option('WP_firewall_exclude_terms', $_REQUEST['block_terms']);
		update_option('WP_firewall_exclude_spaces', $_REQUEST['block_spaces']);
		update_option('WP_firewall_exclude_file', $_REQUEST['block_file']);
		update_option('WP_firewall_exclude_http', $_REQUEST['block_http']);
		echo '<div class="updated fade"><p>Security Filters and Redirect page updated.</p></div>';
		
	} elseif($_REQUEST['turn_off_email']) {
		update_option('WP_firewall_email_address', '');
		$action_url = str_replace('&turn_off_email=1', '', $_SERVER['REQUEST_URI']);
		echo '<div class="updated fade"><p>Emails are now turned off.</p></div>';
		
	} elseif($_REQUEST['set_whitelist_variable']) {
		echo '<div class="updated fade"><p>Whitelisted ' . $_REQUEST['set_whitelist_variable'] . '</p></div>';
		$pages = unserialize(get_option('WP_firewall_whitelisted_page'));
		$variables = unserialize(get_option('WP_firewall_whitelisted_variable'));
		$pages[] = '';
		$variables[] = $_REQUEST['set_whitelist_variable'];
		update_option('WP_firewall_whitelisted_page', serialize($pages));
		update_option('WP_firewall_whitelisted_variable', serialize($variables));
		$action_url = str_replace(('&set_whitelist_variable=' . $_REQUEST['set_whitelist_variable']), '', $_SERVER['REQUEST_URI']);
		echo '<div class="updated fade"><p>Whitelisted Variable set.</p></div>';
		
	} elseif($_REQUEST['set_email']) {
		update_option('WP_firewall_email_address', $_REQUEST['email_address']);
		update_option('WP_firewall_email_limit', $_REQUEST['email_limit']);
		update_option('WP_firewall_email_type', $_REQUEST['email_type']);
		echo '<div class="updated fade"><p>Email settings updated.</p></div>';
		
	} elseif($_REQUEST['set_whitelist_ip']) {
		update_option('WP_firewall_whitelisted_ip', serialize($_REQUEST['whitelisted_ip']));
		echo '<div class="updated fade"><p>Whitelisted IP set.</p></div>';
		
	} elseif($_REQUEST['set_whitelist_page']) {
		update_option('WP_firewall_whitelisted_page', serialize($_REQUEST['whitelist_page']));
		update_option('WP_firewall_whitelisted_variable', serialize($_REQUEST['whitelist_variable']));
		echo '<div class="updated fade"><p>Whitelisted Page set.</p></div>';
		
	} elseif($_REQUEST['suppress'] === '0') {
		update_option('WP_firewall_email_limit', 'off');
		echo '<div class="updated fade"><p>Email limit set.</p></div>';
		$action_url = str_replace('&suppress=0', '', $_SERVER['REQUEST_URI']);
	}
	?>
	<div class="wrap">
		<div id="icon-tools" class="icon32"></div>
		<h2>Firewall Options:</h2>
			<form name="set-exclusion-options" action="<?php echo $action_url; ?>" method="post" class="widefat" style="padding:0 0 20px; margin:20px 0 0;">
			<div style="padding:0 20px;">
				<h3>Apply Security Filters:</h3>
				<p><input type="checkbox" value="allow" name="block_directory" <?php echo (get_option('WP_firewall_exclude_directory') == 'allow') ? 'checked="checked"' : '' ?> /> Block directory traversals (../, ../../etc/passwd, etc.) in application parameters.</p>
				<p><input type="checkbox" value="allow" name="block_queries" <?php echo (get_option('WP_firewall_exclude_queries') == 'allow') ? 'checked="checked"' : '' ?> /> Block SQL queries (union select, concat(, /**/, etc.) in application parameters.</p>
				<p><input type="checkbox" value="allow" name="block_terms" <?php echo (get_option('WP_firewall_exclude_terms') == 'allow') ? 'checked="checked"' : ''?> /> Block WordPress specific terms (wp_, user_login, etc.) in application parameters.</p>
				<p><input type="checkbox" value="allow" name="block_spaces" <?php echo (get_option('WP_firewall_exclude_spaces') == 'allow') ? 'checked="checked"' : '' ?> /> Block field truncation attacks in application parameters.</p>
				<p><input type="checkbox" value="allow" name="block_file" <?php echo (get_option('WP_firewall_exclude_file') == 'allow') ? 'checked="checked"' : '' ?> /> Block executable file uploads (.php, .exe, etc.)</p>
				<p><input type="checkbox" value="allow" name="block_http" <?php echo (get_option('WP_firewall_exclude_http') == 'allow') ? 'checked="checked"' : '' ?> /> Block leading http:// and https:// in application parameters (<em>off</em> by default; may cause problems with many plugins).</p>
				
				<h4>Upon Detecting Attack:</h4>
				<table border="0" cellpadding="0" cellspacing="0" style="width:260px; margin-top:0; padding:0;">
					<tr>
						<td><strong>Show 404 Error Page:</strong></td>
						<td><input type="radio" name="redirect_type" value="404page" <?php echo (get_option('WP_firewall_redirect_page') == '404page') ? 'checked="checked"' : '' ?> /></td>
					</tr>
					<tr>
						<td><strong>Redirect To Homepage:</strong></td>
						<td><input type="radio" name="redirect_type" value="homepage" <?php echo (get_option('WP_firewall_redirect_page') == 'homepage') ? 'checked="checked"' : '' ?> /></td>
					</tr>
				</table>
				<p style="margin-top:5px;"><small><em>Note: All filters are subject to "Whitelisted IPs" and "Whitelisted Pages" below.</em></small></p>
				<input type="submit" name="set_exclusions" value="Set Security Filters" class="button-secondary" />
			</div>
			</form>
			
			
			<form name="email_address" action="<?php echo $action_url; ?>" method="post" class="widefat" style="padding:0 0 20px; margin:20px 0 0;">
			<div style="padding:0 20px;">
				<h3>Email:</h3>
				<p><strong>Enter an email address for attack reports:</strong></p>
				<input type="text" value="<?php echo get_option('WP_firewall_email_address') ?>" name="email_address" />
				<p style="margin-top:5px;"><small><em>Note: Leave this setting blank to disable emails.</em></small></p>
				<p><strong>Email type:</strong> <input type="radio" name="email_type" value="html"<?php echo (get_option('WP_firewall_email_type') == 'html') ? 'checked="checked"' : '' ?> />html <input type="radio" name="email_type" value="text" <?php echo (get_option('WP_firewall_email_type') == 'text') ? 'checked="checked"' : '' ?> />text</p>
				<p><strong>Suppress similar attack warning emails:</strong> <input type="radio" name="email_limit" value="on"<?php echo (get_option('WP_firewall_email_limit') == 'on') ? 'checked="checked"' : '' ?> />On <input type="radio" name="email_limit" value="off" <?php echo (get_option('WP_firewall_email_limit') == 'off') ? 'checked="checked"' : '' ?> />Off</p>
				<input type="submit" name="set_email" value="Set Email"  class="button-secondary" />
			</div>
			</form>
			
			<form name="whitelist_ip" action="<?php echo $action_url; ?>" method="post" class="widefat" style="padding:0 0 20px; margin:20px 0 0;">
			<div style="padding:0 20px;">
				<h3>Whitelisted IPs:</h3>
				<p>Enter IP(s) that are whitelisted &mdash; and not subject to security rules.</p>
				<?php
					if( !get_option('WP_firewall_whitelisted_ip')) {
						echo '<input type="text" value="" name="whitelisted_ip[]" /><br />';
					} else {
						//$ips = array_unique( unserialize(get_option('WP_firewall_whitelisted_ip')) );
						$ips_options = get_option('WP_firewall_whitelisted_ip');
						$ips_options_unserialized = unserialize($ips_options);
						
						// Check to see if data needs to be unserialzed or not
						if($ips_options_unserialized !== FALSE) {
							$ips = array_unique($ips_options_unserialized);
							foreach($ips as $ip){
								if($ip != '') {
									echo '<input type="text" value="' . $ip . '" name="whitelisted_ip[]" /><br />';
								}
							}
						} else {
							$ips = array_unique($ips_options);
							foreach($ips as $ip) {
								if($ip != '') {
									echo '<input type="text" value="' . $ip . '" name="whitelisted_ip[]" /><br />';
								}
							}
						}
						echo  '<input type="text" value="" name="whitelisted_ip[]" /><br />';
					}
				?>
				<p style="margin-top:5px;"><small><em>Note: Set field(s) to blank to disable IP whitelist. Your current IP is: <strong><?php echo $_SERVER['REMOTE_ADDR']?></strong>.</em></small></p>
				<input type="submit" name="set_whitelist_ip" value="Set Whitelisted IPs" class="button-secondary" />
			</div>
			</form>
			
			<form name="whitelist_page_or_variable" action="<?php echo $action_url; ?>" method="post" class="widefat" style="padding:0 0 20px; margin:20px 0 0;">
			<div style="padding:0 20px;">
				<h3>Whitelisted Pages:</h3>
				<p>Enter page and/or form variables that are whitelisted &mdash; and not subject to security rules:</p>
				<table cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td><strong>Page:</strong></td>
						<td><strong>Form Variable:</strong></td>
					</tr>
					<?php
					//!unserialize(get_option('WP_firewall_whitelisted_page')) && !unserialize(get_option('WP_firewall_whitelisted_variable'))
					$whitelist_pages = get_option('WP_firewall_whitelisted_page');
					$whitelist_variables = get_option('WP_firewall_whitelisted_variable');
					$whitelist_pages_unserialized = unserialize($whitelist_pages);
					$whitelist_variables_unserialized = unserialize($whitelist_variables);
					
					if(($whitelist_pages == '') && ($whitelist_variables == '')) {
						echo '<tr><td><input type="text" name="whitelist_page[]" /></td>';
						echo '<td><input type="text" name="whitelist_variable[]" /></td></tr>';
					} else {
						//$pages = unserialize(get_option('WP_firewall_whitelisted_page'));
						//$variables = unserialize(get_option('WP_firewall_whitelisted_variable'));
						if(($whitelist_pages_unserialized !== FALSE) && ($whitelist_pages_unserialized !== FALSE)) {
							$pages = $whitelist_pages_unserialized;
							$variables = $whitelist_variables_unserialized;
							$count = 0;
							while($count < sizeof($pages)) {
								if(($pages[$count] != '') || ($variables[$count] != '')) {
									echo '<tr><td><input type="text" value="'. $pages[$count] . '" name="whitelist_page[]" /></td>';
									echo '<td><input type="text" value="' . $variables[$count] . '" name="whitelist_variable[]" /></td></tr>';
								}
								$count++;
							}
						} else {
							$pages = $whitelist_pages;
							$variables = $whitelist_variables;
							$count = 0;
							while($count < sizeof($pages)) {
								if(($pages[$count] != '') || ($variables[$count] != '')) {
									echo '<tr><td><input type="text" value="'. $pages[$count] . '" name="whitelist_page[]" /></td>';
									echo '<td><input type="text" value="' . $variables[$count] . '" name="whitelist_variable[]" /></td></tr>';
								}
								$count++;
							}
						}
						echo '<tr><td><input type="text" name="whitelist_page[]" /></td>';
						echo '<td><input type="text" name="whitelist_variable[]" /></td></tr>';
					}
					?>
				</table>
				<p style="margin-top:5px;"><small><em>Note: Set field(s) to blank to disable page whitelist.<br />Note: Use *'s for wildcard characters.</em></small></p>
				<input type="submit" name="set_whitelist_page" value="Set Whitelisted Pages" class="button-secondary" />
			</div>
			</form>
				
		<?php WP_firewall_show_plugin_link(); ?>
	</div>
<?php
}

function WP_firewall_show_plugin_link() { ?>
	<div style="margin:30px 0 20px; text-align:right;">
		<small>Modifications to this plugin by <a href="http://matthewpavkov.com" target="_blank">Matthew Pavkov</a>.<br />Please use the <a href="http://wordpress.org/tags/wordpress-firewall-2?forum_id=10" target="_blank">Wordpress Plugin Forum</a> to report bugs, suggestions, etc.</small>
		<br /><br />
		<small>Original plugin by <a href="http://www.seoegghead.com/software/" target="_blank">SEO Egghead</a>.</small>
		<!--
		<a href="http://www.seoegghead.com/software/wordpress-firewall.seo" style="text-decoration:none;" target="_blank">
		<?php if(preg_match('#MSIE#', $_SERVER['HTTP_USER_AGENT']) == 0) { ?>
			<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAAUCAMAAA
			BxjAnBAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADBQTFRFz6Ol
			opmRs21w48/P+Pb218bF9Orr6OTit7CpqVpdqaGZrmJlvYCD0ry769vc////wFM5SwAAAB
			B0Uk5T////////////////////AOAjXRkAAAJLSURBVHjatFXtDiMhCERF3Q/R93/bGxDX
			Xu5Pr2lJNk7pVHQYujS+FImov09Ng75VmM4zvU+V7xVu583vU7sVZo/CG7+kDRcL27qs4J
			VWfJ+N/+GMzdi7cAQVhfm4PEJlcXjUkVf64CHxtogySnN8N64Ppn6erc10Gd3zERQn3DGN
			ZIQGKq5LQ8ITnB+Y6073dK5oTBtDsxWUH3gmXGlF33nytFIFhfkKV68avSpOQDhLwhmk9t
			6vECoKUEpJtHA8bwFWg5T7jHlifZSSsXnBORtwArWWZIEdREUBvYAK/1MJ4bC6aAhumRkQ
			ResRLusW1oKmDJxBj1pMJ3OmCiET36Wdkadxon1XwNcc6+G76tTFfT+ptCWVkVAwGb7qFQ
			5lQYRDm1JdRhTPy8Tz6EPNUvVsaqpoHbh5dqEVW+e5QC9KuY1KnK+nxTKfoCLg8rqTrmkW
			xAZ/FfOjDxWhuxC2RuuI0oXcGTNldKfSGhuVVh8PXNz+iHS1Yj4Ubf+a/ZaquLgQKqd1w8
			cIxSrGFHqR33Q4lbIHJIWsQeYn9fqBVWA3lauRBWNcb6ymOAootnO5jNregu/ipCfQDWIH
			1c2wWk6Ze2oSP/CApx78Mh3McxXzj7cdLa0uo8qpV/KQsoes7Jk0Kh0rEvR98CjiMDMG3w
			MKioFqhqWJo3qoWYtt9TyiD1mw8U7Psfjoj5m9X2Ob5b9fKp+9A1+KiU/zbwvn6ZqozpQH
			958X3j6Ly2c2pj8vXB5/VLyCFi7j9zeWGQSvF/IP6ZMLjz8CDACmemOuUH7ZzQAAAABJRU
			5ErkJggg==" alt="" />
		<?php } ?>
		<br />
		<small>Click here for plugin documentation.</small>
		</a><br />
		<small>Got Questions or Feedback? <a style="text-decoration:none;" href="http://www.seoegghead.com/about/contact-us.seo?subject=WP+Firewall+Feedback" target="_blank">Click here.</a></small>
		<br />
		<small>By using this plugin you agree to <a style="text-decoration:none;" href="http://www.seoegghead.com/software/free-software-disclaimer.seo" target="_blank">this simple disclaimer</a>.</small>
		-->
	</div>
<?php } ?>