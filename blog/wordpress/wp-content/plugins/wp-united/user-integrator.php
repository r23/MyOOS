<?php

/** 
*
* WP-United User Integrator
*
* @package WP-United
* @version $Id: 0.9.2.5  2013/02/08 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
*/

/**
 */
if ( !defined('ABSPATH') ) {
	exit;
}

/**
 * The main login integration routine
 */
function wpu_integrate_login() {
	global $wpUnited, $phpbbForum, $wpuDebug;

	// cache and prevent recursion
	static $result = -1;
	static $doingLogin = false;

	if(!$doingLogin) {
	
		// sometimes this gets called early, e.g. for admin ajax calls.
		if(!$wpUnited->is_working()) {
			return;
		}

		$wpuDebug->add('User integration active.');
		$doingLogin = true;		
		
		// If this is a logout request, just do that!
		if($wpUnited->should_do_action('logout')) {
			wp_logout();
			wp_set_current_user(0);
			$wpuDebug->add('Logged out of WordPress');
			return;
		}
		

		if( !$phpbbForum->user_logged_in() ) { 
			$result = wpu_int_phpbb_logged_out(); 
		} else { 
			$result = wpu_int_phpbb_logged_in();
		}
		
		$doingLogin = false;
	}
	
	return $result;

}

/**
 * What to do when the user is logged out of phpBB
 * in WP-United prior to v0.9.0, we would forcibly log them out
 * However this is left open as a prelude to bi-directional user integration
 */
function wpu_int_phpbb_logged_out() { 
	global $wpuDebug, $phpbbForum, $wpUnited, $current_user;

	// Check if user is logged into WP
	get_currentuserinfo();  
	$wpUser = $current_user;
	if(!$wpUser->ID) {
		$wpuDebug->add('phpBB &amp; WP both logged out.');
		return false;
	}
	
	// no native way to tell if login is persistent
	$persist = (bool)get_user_meta($wpUser->ID, 'wpu-remember-login', true);
	
	$wpuDebug->add('WP already logged in, phpBB logged out.');
	$createdUser = false;

	$phpbbId = wpu_get_integrated_phpbbuser($wpUser->ID);
	
	if(!$phpbbId) { // The user has no account in phpBB, so we create one:
		
		if(!$wpUnited->get_setting('integcreatephpbb')) {
			$wpuDebug->add('No integrated phpBB account, leaving unintegrated.');
			return $wpUser->ID;
		}
		$wpuDebug->add('No integrated phpBB account. Creating.');
		// We just create standard users here for now, no setting of roles
		$phpbbId = wpu_create_phpbb_user($wpUser->ID);
		
		if($phpbbId == 0) {
			$wpuDebug->add("Couldn't create phpBB user. Giving up.");
			//We couldn't create a user in phPBB. Before we wp_die()d. But just handle it silently.
			return $wpUser->ID;
		}
		$createdUser = true;
		$wpuDebug->add("Created phpBB user ID = {$phpbbId}.");
	} 
	$wpuDebug->add("Logging in to integrated phpBB account, user ID = {$phpbbId}.");
	
	
	// the user now has an integrated phpBB account, log them into it
	if(headers_sent()) {
		$wpuDebug->add("WARNING: headers have already been sent, won't be able to set phpBB cookie!");
	}
	
	
	if ($phpbbForum->create_phpbb_session($phpbbId, $persist)) {
		$wpuDebug->add("Established Session for user {$phpbbId}.");
	} else {
		$wpuDebug->add("Could not establish session for user {$phpbbId}. Maybe they were deleted? Giving up.");
		return $wpUser->ID;
	}
	
	if($createdUser) {
		wpu_sync_profiles($wpUsr, $phpbbForum->get_userdata(), 'sync');
	}
	
	// if this is a phpBB-in-WordPress page, this has probably just been called after phpBB has already been generated.
	if($wpUnited->should_do_action('template-p-in-w')) {
		wpu_reload_page_if_no_post();
	}
	
	return $wpUser->ID;
	
		
}



function wpu_int_phpbb_logged_in() { 
	global $wpUnited, $wpuDebug, $phpbbForum, $wpUnited, $current_user;
	
	$wpuDebug->add('phpBB already logged in.');
	
	// Check if user is logged into WP
	get_currentuserinfo();  
	$currWPUser = $current_user;
	if($currWPUser->ID) {
		$wpuDebug->add('WP already logged in, user =' . $currWPUser->ID);
	}
	
	
	
	$persist = (bool)$phpbbForum->get_userdata('session_autologin');
	
	// This user is logged in to phpBB and needs to be integrated. Do they already have an integrated WP account?
	if($integratedID = wpu_get_integration_id() ) {
		
		$wpuDebug->add("phpBB account is integrated to WP account ID = {$integratedID}.");
		
		if($currWPUser->ID === (int)$integratedID) {
			$wpuDebug->add('User is already logged in and integrated to correct account, nothing to do.');
			return $currWPUser->ID;
		} else {
			$wpuDebug->add(sprintf('Integrated user ID is %d but WordPress ID is %s', $integratedID, $currWPUser->ID));
		}
		
		// they already have a WP account, log them in to it and ensure they have the correct details
		if(!$currWPUser = get_userdata($integratedID)) {
			$wpuDebug->add("Failed to fetch WordPress user details for user ID = {$integratedID}. Maybe they were deleted? Giving up.");
			return false;
		}
		
		wp_set_current_user($currWPUser->ID);		
		wp_set_auth_cookie($currWPUser->ID, $persist);
		
		$wpuDebug->add('WordPress user set to integrated user.');
		
		return $currWPUser->ID;
		
	} else { 
	
		$wpuDebug->add('User is not integrated yet.');
	
		//Is this user already logged into WP? If so then just link the two logged in accounts
		if($currWPUser->ID) {
			
			$wpuDebug->add('User is already logged into WP, linking two logged-in accounts.');
			
			wpu_update_int_id($phpbbForum->get_userdata('user_id'), $currWPUser->ID);
			// sync but don't modify passwords:
			wpu_sync_profiles($currWPUser, $phpbbForum->get_userdata(), 'sync', true);
			return $currWPUser->ID; 
		}
		
		$wpuDebug->add('Not yet logged into WP.');
	
		// Should this phpBB user get an account? If not, we can just stay unintegrated
		if(!$wpUnited->get_setting('integcreatewp') || !$userLevel = wpu_get_user_level()) {
			$wpuDebug->add('No permissions or auto-create switched off. Not creating integrated account.');
			return false;
		}

		// they don't have an account yet, create one
		$signUpName = $phpbbForum->get_username();
		
		$wpuDebug->add("Creating integrated account with name {$signUpName}");
		
		$newUserID = wpu_create_wp_user($signUpName, $phpbbForum->get_userdata('user_password'), $phpbbForum->get_userdata('user_email'));
		
		if($newUserID) { 
			
		   if(!is_a($newUserID, 'WP_Error')) {
				$currWPUser = get_userdata($newUserID);
				
				$wpuDebug->add("Created new WordPress user, ID = {$currWPUser->ID}.");
				
				// must set this here to prevent recursion
				wp_set_current_user($currWPUser->ID);
				wpu_set_role($currWPUser->ID, $userLevel);		
				wpu_update_int_id($phpbbForum->get_userdata('user_id'), $currWPUser->ID);
				wpu_sync_profiles($currWPUser, $phpbbForum->get_userdata(), 'sync');
				wp_set_auth_cookie($currWPUser->ID, $persist);
				
				$createdUser = $currWPUser->ID;
				
				//do_action('auth_cookie_valid', $cookie_elements, $currWPUser->ID);
				return $currWPUser->ID; 
			}
			$wpuDebug->add('Error when creating integrated account. Giving up.');
		}
		
		$wpuDebug->add('Failed to create integrated account. Giving up.');
	}
	return false;		
	
}


/**
 * Simple function to add a new user while preventing firing of the WPU user register hook
 */
function wpu_create_wp_user($signUpName, $password, $email) {
	global $wpUnited;
	

	if(! $foundName = wpu_find_next_avail_name($signUpName, 'wp') ) {

			return false;
	}

	$newWpUser = array(
		'user_login'	=> 	$foundName,
		'user_pass'		=>	$password,
		'user_email'	=>	$email
	); 

	// remove WP-United hook so we don't get stuck in a loop on new user creation
	if(!remove_action('user_register', array($wpUnited, 'process_new_wp_reg'), 10, 1)) {
		return false;
	}

	$newUserID = wp_insert_user($newWpUser);
	
	// reinstate the hook
	add_action('user_register', array($wpUnited, 'process_new_wp_reg'), 10, 1); 
	
	return $newUserID;
	
}


/**
 * Finds the next available username in WordPress or phpBB
 * @param string $name the desired username
 * @param $package the application to search in
 */
function wpu_find_next_avail_name($name, $package = 'wp') {
	
	global $phpbbForum, $phpbb_root_path, $phpEx;
	
	$i = 0;
	$foundFreeName = $result = false;
	
	
	//start with the plain username, if unavailable then append a number onto the login name until we find one that is available
	if($package == 'wp') {
		$name = $newName = sanitize_user($name, true);
		while ( !$foundFreeName ) {
			if ( !username_exists($newName) ) {
				$foundFreeName = true;
			} else {
				// This username already exists.
				$i++; 
				$newName = $name . $i;
			}
		}
		return $newName;
	} else {
			// search in phpBB
			$fStateChanged = $phpbbForum->foreground();
			require_once($phpbb_root_path . 'includes/functions_user.' . $phpEx);
			$newName = $name;
			while ( !$foundFreeName ) {
				$result = phpbb_validate_username($newName);
				if($result === false) {
					$foundFreeName = true;
				} else if($result != 'USERNAME_TAKEN') {
					$phpbbForum->restore_state($fStateChanged);
					return false;
				} else {
					$i++;
					$newName = $name . $i;
				}
			}
			$phpbbForum->restore_state($fStateChanged);
		
		return $newName;
	}
}

/**
* Function 'wpu_fix_blank_username()' - Generates a username in WP when the sanitized username is blank,
* as phpbb is more liberal in user naming
* Originally by Wintermute
* If the sanitized userLogin is blank, create a random
* username inside WP. The userLogin begins with WPU followed
* by a random number (1-10) of digits between 0 & 9
* Also, check to make sure the userLogin is unique
* @since WP-United 0.7.1
*/
function wpu_fix_blank_username($userLogin) {
	if (empty($userLogin)){
		$foundFreeName = FALSE;
		while ( !$foundFreeName ) {
			$userLogin = 'WPU';
			srand(time());
			for ($i=0; $i < (rand()%9)+1; $i++)
				$userLogin .= (rand()%9);
			if ( !username_exists($userLogin) )
				$foundFreeName = TRUE;
		}
	}
	return $userLogin;
}


/**
 * Validates a new or prospective WordPress user in phpBB
 * @param string $username username
 * @param string $email e-mail
 * @param WP_Error $errors WordPress error object
 * @return bool|WP_Error false (on success) or modified WP_Error object (on failure)
 */
function wpu_validate_new_user($username, $email, $errors) {
	global $phpbbForum;
	$foundErrors = 0;
	
	
	if(function_exists('phpbb_validate_username')) {
		$fStateChanged = $phpbbForum->foreground();
		$result = phpbb_validate_username($username, false);
		$emailResult = validate_email($email);
		$phpbbForum->restore_state($fStateChanged);

		if($result !== false) {
			switch($result) {
				case 'INVALID_CHARS':
					$errors->add('phpbb_invalid_chars', __('The username contains invalid characters', 'wp-united'));
					$foundErrors++;
					break;
				case 'USERNAME_TAKEN':
					$errors->add('phpbb_username_taken', __('The username is already taken', 'wp-united'));
					$foundErrors++;
					break;
				case 'USERNAME_DISALLOWED':
					default;
					$errors->add('phpbb_username_disallowed', __('The username you chose is not allowed', 'wp-united'));
					$foundErrors++;
					break;
			}
		}
		
		if($emailResult !== false) {
			switch($emailResult) {
				case 'DOMAIN_NO_MX_RECORD':
					$errors->add('phpbb_invalid_email_mx', __('The email address does not appear to exist (No MX record)', 'wp-united'));
					$foundErrors++;
					break;
				case 'EMAIL_BANNED':
					$errors->add('phpbb_email_banned', __('The e-mail address is banned', 'wp-united'));
					$foundErrors++;
					break;
				case 'EMAIL_TAKEN':
					$errors->add('phpbb_email_taken', __('The e-mail address is already taken', 'wp-united'));
					break;
				case 'EMAIL_INVALID':
					default;
					$errors->add('phpbb_invalid_email', __('The email address is invalid', 'wp-united'));
					$foundErrors++;
					break;									
			}
		}

	}
	return ($foundErrors) ? $errors : false;
	
}



/**
 * Creates a new integrated user in phpBB to match a given WordPress user
 * @param int $userID the WordPress userID
 * @return int < 1 on failure; >=1 phpbb User ID on success
 */
function wpu_create_phpbb_user($userID) {
	global $phpbbForum, $config, $db;

	if(!$userID) {
		return -1;
	}

	$wpUsr = get_userdata($userID);
	
	$fStateChanged = $phpbbForum->foreground();
	
	$password = wpu_convert_password_format($wpUsr->user_pass, 'to-phpbb');

	// validates and finds a unique username
	if(! $signUpName = wpu_find_next_avail_name($wpUsr->user_login, 'phpbb') ) {
		$phpbbForum->restore_state($fStateChanged);
		return -1;
	}

	
	
	$userToAdd = array(
		'username' => $signUpName,
		'user_password' => $password,
		'user_email' => $wpUsr->user_email,
		'user_type' => USER_NORMAL,	
	);
	
	// add to newly registered group if needed
	if ($config['new_member_post_limit']) {
		$userToAdd['user_new'] = 1;
	}
				
	// Which group by default?
	$sql = 'SELECT group_id
		FROM ' . GROUPS_TABLE . "
		WHERE group_name = '" . $db->sql_escape('REGISTERED') . "'
			AND group_type = " . GROUP_SPECIAL;
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$groupID = (int)$row['group_id'];
	$userToAdd['group_id'] = (empty($groupID)) ? 2 : $groupID;
	

	$pUserID = 0;		
	if ($pUserID = user_add($userToAdd)) {

		wpu_update_int_id($pUserID, $wpUsr->ID);
		update_user_meta( $wpUsr->ID, 'phpbb_userid', $pUserID);
		

	}

	$phpbbForum->restore_state($fStateChanged);

	
	return $pUserID;
}





/**
 * Determines if a non-integrated WP user can integrate into a new phpBB account.
 * @return array permission details in the user's language
 */
 
function wpu_assess_newuser_perms() {
	global $phpbbForum;

	static $perms = false;
	
	if(is_array($perms)) {
		return $perms;
	}
	

	$groups = $phpbbForum->get_newuser_group();
	
	return wpu_assess_perms($groups, true);

}

/**
 * Takes a list of group names to check, and returns an array of permissions due to role and direct permissions
 * (phpBB likes to over-complicate things, so need to check both :-/ )
 * @param array $groupList list of group names (language keys) to check
 * @return array permission details in the user's language
 */

function wpu_get_perms($groupList = '') {
	global $phpbbForum, $config, $db, $user;
	
	$fStateChanged = $phpbbForum->foreground();
	
	$permCacheKey = '';
	
	if( ($groupList == '') || $groupList == array() ) {
		$where = '';
		$permCacheKey = '[BLANK]';
	} else {
		$groupList = (array)$groupList;
		if(sizeof($groupList) > 1) {
			$where = 'AND ' . $db->sql_in_set('group_name', $groupList);
		} else {
			$where = "AND group_name = '" . $db->sql_escape($groupList[0]) . "";
		}
		$permCacheKey = implode('|', $groupList);
	}
	
	
	static $cachedPerms = array();
	
	if (isset($cachedPerms[$permCacheKey])) {
			return $cachedPerms[$permCacheKey];
	}
	
	
	
	$user->add_lang('acp/permissions');
		
	$perms = wpu_permissions_list();
	
	$sqlArr = array(
		'SELECT' 			=> 	'g.group_name, ao.auth_option, ag.auth_setting AS groupsetting, ar.role_name, ar.role_id, ar.role_type, ad.auth_setting AS rolesetting',
		
		'FROM' 			=> 	array(
			GROUPS_TABLE 			=> 	'g',
			ACL_GROUPS_TABLE 	=> 	'ag'
		),
	
		'LEFT_JOIN' 	=> 	array(
			array(
				'FROM'		=>	array(ACL_ROLES_TABLE => 'ar'),
				'ON'				=> 	'ag.auth_role_id = ar.role_id'
			),
			array(
				'FROM'		=>	array(ACL_ROLES_DATA_TABLE => 'ad'),
				'ON'				=> 	'ar.role_id = ad.role_id'
			),
			array(
				'FROM'		=>	array(ACL_OPTIONS_TABLE => 'ao'),
				'ON'				=>	'ag.auth_option_id = ao.auth_option_id 
													OR ad.auth_option_id = ao.auth_option_id'
			),		
		),
		'WHERE' 			=> 	'ag.group_id = g.group_id
											AND ' . $db->sql_in_set('ao.auth_option', array_keys($perms)) . 
												$where,
									
		'ORDER_BY'	=>	'g.group_type DESC, g.group_name ASC',
	);
	
		
	$sql = $db->sql_build_query('SELECT',$sqlArr);
	$result = $db->sql_query($sql);
	
	$calculatedPerms = array();
	
	while ($row = $db->sql_fetchrow($result)) {
		$group = (isset($user->lang['G_' . $row['group_name']])) ? $user->lang['G_' . $row['group_name']] : $row['group_name'];
		$stg = (!empty($row['role_name'])) ? $row['rolesetting'] : $row['groupsetting']; 
		switch($stg) {
			case ACL_YES:
				$setting = $user->lang['ACL_YES'];
				break;
			case ACL_NEVER:
				$setting = $user->lang['ACL_NEVER'];
				break;
			case ACL_NO:
			default;
				$setting = $user->lang['ACL_NO'];
		}
		
		$roleType = '';
		if(!empty($row['role_name'])) {
			$role = (isset($user->lang[$row['role_name']])) ? $user->lang[$row['role_name']] : $row['role_name'];
			switch($row['role_type']) {
				case 'm_':
					$roleType = 'mod';
					break;
				case 'a_':
					$roleType = 'admin';
					break;
				case 'f_':
				case 'u_':
				default;
					// we only want global roles, so for f_, fall back to user
					$roleType = 'user';
					break;
			}
		} else {
			$role = '';
		}
		if(!isset($calculatedPerms[$group])) {
			$calculatedPerms[$group] = array();
		}
		$calculatedPerms[$group][] = array(
			'rolename' 		=> 	$role,
			'perm'				=>	$row['auth_option'],
			'settingText'	=>	$setting,
			'setting'			=>	$stg,
			'roleid'				=>	$row['role_id'],
			'roletype'		=>	$roleType
		);
	}
	
	$db->sql_freeresult($result);
	
	$phpbbForum->restore_state($fStateChanged);
	
	$cachedPerms[$permCacheKey] = (array)$calculatedPerms;

	return $cachedPerms[$permCacheKey];
	
}



/**
 * Assess permissions for a group or groups, returning an array of groups and "Yes" permissions.
 * @param mixed array|empty string $groupList, the groups to check
 * @param bool $singleUser, all these groups belong to a single user, so remove any permissions from all groups if a "Never" is found.
 * @param bool $getNevers, just get a list of never permissions
 * @return aray
 */
 function wpu_assess_perms($groupList = '', $singleUser = false, $getNevers = false) {
	
	static $cachedResults = array();
	
	$cachedGroupList = (empty($groupList)) ? '[EMPTY]' : implode(',', $groupList);
	
	if(!isset($cachedResults[$cachedGroupList])) {

		$cachedResults[$cachedGroupList] = array();
		$cachedResults[$cachedGroupList]['yeses'] = array();
		$cachedResults[$cachedGroupList]['nevers'] = array();
		$cachedResults[$cachedGroupList]['yeses-single'] = array();
		
		$setPerms = wpu_get_perms($groupList);
									
		$yeses = array();
		$nevers = array();

		if(sizeof($setPerms)) {
			
			foreach($setPerms as $groupName => $permList) { 
				foreach($permList as $permItem) { 
					if($permItem['setting'] == ACL_YES) {
						if(!isset($yeses[$groupName])) {
							$yeses[$groupName] = array();
						}
						$yeses[$groupName][] = $permItem['perm'];
					} else if($permItem['setting'] == ACL_NEVER) {
						if(!isset($nevers[$groupName])) {
							$nevers[$groupName] = array();
						}					
						$nevers[$groupName][] = $permItem['perm'];
					}
				}
				// remove ACL_NEVERS for corresponding groups
				if(isset($yeses[$groupName]) && isset($nevers[$groupName])) {
					$yeses[$groupName] = array_diff($yeses[$groupName], $nevers[$groupName]);
				}
			}
			
			$cachedResults[$cachedGroupList]['yeses'] = $yeses;
			$cachedResults[$cachedGroupList]['nevers'] = $nevers;
			$cachedResults[$cachedGroupList]['yeses-single'] = $yeses;
			// if all these groups belong to a single user, also remove *any* items which also have ACL_NEVER set
			if(sizeof($nevers)) {
				$y = array();
				$n = array();
				foreach(array_values($nevers) as $never => $perm) {
					$n = array_merge($n, $perm);
				}
				
				foreach($yeses as $groupName => $perms) {
					$result = array_diff($perms, $n);
					if(sizeof($result)) {
						$y[$groupName] = $result;
					}
				}
				$cachedResults[$cachedGroupList]['yeses-single'] = $y;
			}
		}
	}
	
	if($singleUser) {
		return $cachedResults[$cachedGroupList]['yeses-single'];
	} else if($getNevers) {
		return $cachedResults[$cachedGroupList]['nevers'];
	} else {
		return $cachedResults[$cachedGroupList]['yeses'];
	}
}

function wpu_get_wp_role_for_group($groupList = '') {
	
	$permArr = wpu_assess_perms($groupList, false);

	$result = array();
	$wpuPerms = array_keys(wpu_permissions_list());
	// get the highest role  for the given group(s), as nothing else really matters
	foreach($permArr as $group => $roleArr) {
		foreach($wpuPerms as $wpuPerm) {
			if(in_array($wpuPerm, $roleArr)) {
				$result[$group] = $wpuPerm;
			}
		}
	}
	return $result;
	
}


/**
 * Gets the integration ID for the current phpBB user, or for a provided phpBB user ID
 * @param in $userID phpBB user ID (optional)
 * @return int WordPress User ID, or zero
 */
function wpu_get_integration_id($userID = false) {
	global $phpbbForum, $db;
	
	return $phpbbForum->get_userdata('user_wpuint_id', $userID);
	
}

/**
 * Gets the integration ID for the current WordPress user, or for a provided WordPress user ID
 * @param in $userID WordPress user ID (optional)
 * @return int phpBB User ID, or zero
 */
function wpu_get_integrated_phpbbuser($userID = 0) {
	global $current_user, $phpbbForum, $db;

	$userID = (int)$userID;
	
	if($userID == 0) {
	
		$current_user =  wp_get_current_user();
		$userID = $current_user->ID;
		
		if($userID == 0) {
			return 0;
		}
		
	}
	
	$fStateChanged = $phpbbForum->foreground();
		
	$sql = 'SELECT user_id FROM ' . USERS_TABLE . ' 
				WHERE user_wpuint_id = ' . (int)$userID;
		
	if(!$result = $db->sql_query_limit($sql, 1)) {
		$pUserID = 0;
	} else {
		$pUserID = $db->sql_fetchfield('user_id');
	}
	$db->sql_freeresult();
			
	$phpbbForum->restore_state($fStateChanged);
	
	return $pUserID;

}


/**
 * Gets the logged-in user's effective WP-United permissions
 * @return mixed string|bool WordPress user level, or false if no permissions
 */
function wpu_get_user_level($userID = false) {
	global $wpUnited, $phpbbForum, $wpuDebug, $auth;

	$fStateChanged = $phpbbForum->foreground();
		

	$userLevel = false;
	
	// if checking for the current user, do a sanity check
	if((!$userID && !$phpbbForum->user_logged_in()) || ($userID == ANONYMOUS)) { 
		$phpbbForum->restore_state($fStateChanged);
		return false;
	} else {
		$userDetails = $phpbbForum->get_userdata('', $userID);
		$phpbbForum->transition_user($userID, $userDetails->user_ip);
	}
	
	
	
	if(!in_array($phpbbForum->get_userdata('user_type'), array(USER_NORMAL, USER_FOUNDER))) {
		if($userID !== false) {
			$phpbbForum->transition_user();
		}
		$phpbbForum->restore_state($fStateChanged);
		return false;
	}
	
	$wpuPermissions = wpu_permissions_list();
	
	// Higher permissions override lower ones, so we work from the bottom up to find the users'
	// actual level
	$debug = 'Checking permissions: ';
	foreach($wpuPermissions as $perm => $desc) {
		if( $auth->acl_get($perm) ) {
			$userLevel = $desc;
			$debug .= '[' . $desc . ']';
		}
	}
	
	$wpuDebug->add($debug);
	$wpuDebug->add('User level set to: ' . $userLevel);
	
	if($userID !== false) {
		$phpbbForum->transition_user();
	}
	
	$phpbbForum->restore_state($fStateChanged);
	
	return $userLevel;

}


/** 
 * returns an array of WP-United permissions
 * Dead simple -- but called in several places
 * @TODO: Add custom wordpress roles?
 */

function wpu_permissions_list() {
	return array(
		'u_wpu_subscriber' 			=>	'subscriber',
		'u_wpu_contributor' 		=>	'contributor',
		'u_wpu_author' 				=>	'author',
		'm_wpu_editor' 				=>	'editor',
		'a_wpu_administrator' 		=>	'administrator'
	);
}


/**
 * Updates the Integration ID stored in phpBB profile
 */
function wpu_update_int_id($pID, $intID) {
	global $db, $cache, $phpbbForum;

	//Do we need to update the integration ID?
	if ( empty($intID) ) {
		return false;
	} 
	//Switch back to the phpBB DB:
	$fStateChanged = $phpbbForum->foreground();
	
	$updated = FALSE;
	if ( !empty($pID) ) { 
		$sql = 'UPDATE ' . USERS_TABLE . '
			SET user_wpuint_id = ' . (int)$intID . '  
			WHERE user_id = ' . (int)$pID;
		if(!$result = $db->sql_query($sql)) {
			trigger_error(__('WP-United could not update your integration ID in phpBB, due to a database access error. Please contact an administrator and inform them of this error.', 'wp-united'));
		} else {
			$updated = TRUE;
		}
	}
	//Switch back to the WP DB:
	$phpbbForum->restore_state($fStateChanged);
	if ( !$updated ) {
		trigger_error(__('Could not update integration data: WP-United could not update your integration ID in phpBB, due to an unknown error. Please contact an administrator and inform them of this error.', 'wp-united'));
	}
}	

/**
 * Bi-direcitonal profile synchroniser.
 * 
 * @param mixed $wpData WordPress user object or array of data
 * @param mixed $pData phpBB user data
 * @param string $action = sync | phpbb-update | wp-update
 * @return bool true if something was updated
*/
function wpu_sync_profiles($wpData, $pData, $action = 'sync', $ignorePassword = false) {
	global $wpUnited, $phpbbForum, $wpdb, $wpuDebug;

	if(is_object($wpData)) { 
		$wpData = (array)get_object_vars($wpData->data); 
	} 
	
	$syncPassword = ($ignorePassword) ? ', ignoring password' : ', including password';
	$wpuDebug->add("Synchronising profiles, sync type is '{$action}'{$syncPassword}.");

	$wpMeta = get_user_meta($wpData['ID']);

	if( !isset($wpData['ID']) || empty($wpData['ID']) || empty($pData['user_id']) ) {
		return false;
	}

	/**
	 * 
	 *	First, update normal profile fields
	 *
	 */
	
	// Our profile fields to synchronise:
	$fields = array(
		array('wp'	=>	'user_nicename','phpbb'	=> 'username', 		'type'	=>	'main', 'dir' => 'wp-only'),
		array('wp'	=>	'nickname',		'phpbb'	=> 'username', 		'type'	=>	'main', 'dir' => 'wp-only'),
		array('wp'	=>	'display_name',	'phpbb'	=> 'username', 		'type'	=>	'main', 'dir' => 'wp-only'),
		array('wp'	=>	'user_email',	'phpbb'	=> 'user_email', 	'type'	=>	'main', 'dir' => 'bidi'),
		array('wp'	=>	'user_url',		'phpbb' => 'user_website', 	'type'	=>	'main', 'dir' => 'bidi'),
		array('wp'	=>	'phpbb_userid',	'phpbb' => 'user_id',		'type'	=>	'meta', 'dir' => 'wp-only'),
		array('wp'	=>	'aim',			'phpbb' => 'user_aim', 		'type'	=>	'meta', 'dir' => 'bidi'),
		array('wp'	=>	'yim',			'phpbb' => 'user_yim', 		'type'	=>	'meta', 'dir' => 'bidi'),
		array('wp'	=>	'jabber',		'phpbb' => 'user_jabber', 	'type'	=>	'meta', 'dir' => 'bidi')
	);	
	
	$updates = array('wp' => array(),	'phpbb' => array());
	

	foreach($fields as $field) {
		
		$type = $field['type'];
		$wpField = $field['wp'];
		$pField = $field['phpbb'];
		$dir = $field['dir'];
		
		// initialise items in both data arrays so we can compare them
		$pFieldData = (isset($pData[$pField])) ? $pData[$pField] : '';
		if($type == 'main') {
			$wpFieldData = (isset($wpData[$wpField])) ? $wpData[$wpField] : '';
		} else {
			$wpFieldData = (isset($wpMeta[$wpField])) ? $wpMeta[$wpField][0] : '';
		}
		
		switch($action) {
			case 'wp-update': // WP profile has been updated, so send to phpBB
				if((!empty($wpFieldData)) && ($dir != 'wp-only')) {
					$updates['phpbb'][$pField] = $wpFieldData;
				}
				break;
				
			case 'phpbb-update': // phpBB profile has been updated, so send to WP
				if((!empty($pFieldData)) && ($dir != 'phpbb-only')) {
					$updates['wp'][$wpField] = $pFieldData;
				}
				break;
				
			case 'sync': // initial sync of profiles, so fill in whatever we can on both sides
			default;
				if((!empty($wpFieldData)) && (empty($pFieldData)) &&  ($dir != 'wp-only')) {
					$updates['phpbb'][$pField] = $wpFieldData;
				}
				if((!empty($pFieldData)) && (empty($wpFieldData)) && ($dir != 'phpbb-only')) {
					$updates['wp'][$wpField] = $pFieldData;
				}
				break;
		}
	}
	
	/**
	 * 
	 *	Next, sync avatars
	 *   TODO: check if wpuput and if avatar_type == AVATAR_REMOTE. If so then can sync p -> w too!
	 */
				
	// sync avatar WP -> phpBB
	if(($action != 'phpbb-update') &&  ($wpUnited->get_setting('avatarsync'))){
		// is the phpBB avatar empty already, or was it already put by WP-United?
		if(empty($pData['user_avatar']) || (stripos($pData['user_avatar'], 'wpuput=1') !== false)) { 
			
			$avatarSize = 90;
			
			// we send an avatar. First we need to get the WP one -- remove our filter hook
			if(remove_action('get_avatar', array($wpUnited, 'get_avatar'), 10, 5)) {
				
				// Gravatars are predicated on user e-mail. If we send ID instead, get_avatar could just return a default as the user might not
				// have cached data yet. E-mail is also faster as it doesn't need to be converted.
				$avatar = get_avatar($wpData['user_email'], $avatarSize);
				if(!empty($avatar)) {
					if(stripos($avatar, includes_url('images/blank.gif')) === false) {
						$avatarDetails = $phpbbForum->convert_avatar_to_phpbb($avatar, $pData['user_id'], $avatarSize, $avatarSize);
						$updates['phpbb'] = array_merge($updates['phpbb'], $avatarDetails);
					}
				}
				// reinstate our action hook:
				add_action('get_avatar', array($wpUnited, 'get_avatar'), 10, 5);
			}
		}
	}	
		
	 /**
	 * 
	 *	Compare and update passwords
	 *
	 */
	if(!$ignorePassword) {
		if(($action == 'phpbb-update') || ($action == 'sync')) { // updating phpBB profile or syncing
			if(!empty($pData['user_password'])) {
				// convert password to WP format for comparison, as that will be the destination if it is different
				$pData['user_password'] = wpu_convert_password_format($pData['user_password'], 'to-wp');
				// wp_update_user double-hashes the password so we handle it separately, now
				if($pData['user_password'] != $wpData['user_pass']) {
					$wpdb->update($wpdb->users, array('user_pass' => stripslashes($pData['user_password'])) , array('ID' => (int)$wpData['ID']), '%s', '%d');
					wp_cache_delete($wpData['ID'], 'users');
					wp_cache_delete($wpData['ID'], 'userlogins');
				}
			}
			
		} else if($action == 'wp-update') {	// updating WP profile 
			if(!empty($wpData['user_pass'])) { 
				// convert password to phpBB format for comparison, as that will be the destination if it is different
				$wpData['user_pass'] = wpu_convert_password_format($wpData['user_pass'], 'to-phpbb');

				// for phpBB we can update along with everything else
				if($pData['user_password'] != $wpData['user_pass']) {
					$updates['phpbb']['user_password'] = $wpData['user_pass'];
				}
			}
			
		}
	}


	/**
	 * 
	 *	Commit changes
	 *
	 */
	$updated = false;
	
	// Update phpBB items
	if(sizeof($updates['phpbb'])) { 
		$phpbbForum->update_userdata($pData['user_id'], $updates['phpbb']);
		$updated = true;
	}
	
	// update WP items
	if(sizeof($updates['wp'])) {
		$updates['wp']['ID'] = $wpData['ID'];
		
		// prevent our hook from firing
		remove_action('profile_update', array($wpUnited, 'profile_update'), 10, 2);
		$userID = wp_update_user($updates['wp']);
		add_action('profile_update', array($wpUnited, 'profile_update'), 10, 2);
		$updated = true; 
	}

	return $updated;

}	

/**
 * phpBB and WordPress passwords are compatible. phPBB marks the hash with a $H$, while WordPress uses $P$
 * So we just need to convert between them.
 */

function wpu_convert_password_format($password, $direction = 'to-phpbb') {

	switch($direction) {
	
		case 'to-phpbb':
			$from = '$P$';
			$to = '$H$';
			break;
			
		case 'to-wp':
			$from = '$H$';
			$to = '$P$';
			break;
			
		default;
			return $password;
	}
	
	if(substr($password, 0, 3) == $from) { 
		$password = substr_replace($password, $to, 0, 3); 
	}
	return $password;

}
	




/**
 * Sets the user role before they get logged in
 * This writes the data to the DB
 * Only updates if the role is not already correct
 * @param int $id WordPress user ID
 * @param string $userLevel WordPress role
 */
function wpu_set_role($id, $userLevel) { 
	$user = new WP_User($id);
	if($user->roles != array($userLevel)) { 
		$user->set_role($userLevel);
	}
}


/**
 * Sets the phpBB permissions for a group if they don't have permission to do that already
 * @param int $id phpBB ID
 * @param string $perm phPBB WP-United permission
 */
function wpu_set_phpbb_group_permissions($groupName, $perm, $type = ACL_YES) {
	global $phpbbForum;
	

	// Not a valid WP-United permission
	if(!in_array($perm, array_keys(wpu_permissions_list()))) {
		return false;
	}
	$phpbbForum->update_group_permissions('grant', $groupName, $perm, $type);
	return true;
}


// That's all. Done.