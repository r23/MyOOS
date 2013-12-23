<?php
/** 
*
* WP-United Hooks
*
* @package WP-United
* @version $Id: 0.9.2.0  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
*/


/**
 */
if ( !defined('IN_PHPBB') ) { 
	exit;
}

define('WPU_HOOK_ACTIVE', TRUE);



// If the user has deleted the wp-united directory, do nothing
if(!file_exists($phpbb_root_path . 'wp-united/')) {
	return;
}

if(!wpu_bootstrap()) {
	return;
}

// We don't need anything if this is a stylesheet call to css magic (style-fixer.php)
if(defined('WPU_STYLE_FIXER')) {
	return;
}

wpu_set_buffering_init_level();


/**
 * INVOKE THE WP ENVIRONMENT NOW. This ***must*** be run in the global scope, for compatibility.
*/

if($wpUnited->should_run_wordpress()) {
	$user->session_begin(false);
	require_once($wpUnited->get_plugin_path() . 'wordpress-runner.php'); 
}

$phpbb_hook->register('phpbb_user_session_handler', 'wpu_init');
$phpbb_hook->register(array('template', 'display'), 'wpu_execute', 'last');
$phpbb_hook->register('exit_handler', 'wpu_continue');




/**
 * Since WordPress uses PHP timezone handling in PHP 5.3+, we need to do in phpBB too to suppress warnings
 * @TODO: In future phpBB releases (> 3.0.11), see if the devs hav added this to phpBB, and remove if so
 */
if ( function_exists('date_default_timezone_set') && !defined('WPU_BLOG_PAGE') && !defined('WPU_PHPBB_IS_EMBEDDED') ) {
	date_default_timezone_set('UTC');
}	



/**
 * Initialise WP-United variables and template strings
 */
function wpu_init(&$hook) { 
	global $wpUnited, $template, $user, $config, $phpbbForum;
	
	if($wpUnited->should_do_action('logout')) { 
		$phpbbForum->background();
		wpu_initialise_wp();
		// logout itself is handled by user-integrator.
		$phpbbForum->foreground();
	}


		
	// Add lang strings if this isn't blog.php
	if( !defined('WPU_BLOG_PAGE')  && !defined('WPU_PHPBB_IS_EMBEDDED') ) {
		$user->add_lang('mods/wp-united');
	}	
	
	// Since we will buffer the page, we need to start doing so after the gzip handler is set
	// to prevent phpBB from setting the handler twice, we unset the option.
	if(!defined('ADMIN_START') ) { //&& (defined('WPU_BLOG_PAGE') || ($wpUnited->get_setting('showHdrFtr') == 'REV'))
		if ($config['gzip_compress']) {
			if (@extension_loaded('zlib') && !headers_sent()) {
				ob_start('ob_gzhandler');
				$config['wpu_gzip_compress'] = 1;
				$config['gzip_compress'] = 0;
			}
		}	
	}	

	/** 
	 * Do a template integration?
	 * @TODO: Clean up, remove defines
	 */
	if (($wpUnited->get_setting('showHdrFtr') == 'REV') && !defined('WPU_BLOG_PAGE')) { 
		ob_start();
	}
	if ($wpUnited->should_do_action('template-w-in-p')) {
		ob_start();
		register_shutdown_function('wpu_wp_shutdown');
	}
}

function wpu_wp_shutdown() { 
	global $phpbbForum, $wpUnited;
	if ($wpUnited->should_do_action('template-w-in-p')) {
		
		$wpUnited->set_inner_content(ob_get_contents());
		ob_end_clean(); 
		$phpbbForum->foreground();
		
		require_once($wpUnited->get_plugin_path() . 'template-integrator.php');
		wpu_integrate_templates();

	}
}

/**
 * Capture the outputted page, and prevent phpBB from exiting
 * @todo: use better check to ensure hook is called on template->display and just drop for everything else
 */
function wpu_execute(&$hook, $handle) { 
	global $wpUnited, $wpuBuffered, $wpuRunning, $template,  $db, $cache, $phpbbForum;
	
	// We only want this action to fire once, and only on a real $template->display('body') event
	if ( (!$wpuRunning)  && (isset($template->filename[$handle])) ) {

		// perform profile sync if required
		if($wpUnited->should_do_action('profile')) {
			global $phpbbForum, $user;

			$idToFetch = ($wpUnited->actions_for_another()) ? $wpUnited->actions_for_another() : $user->data['user_id'];

			// have to reload data from scratch otherwise cached $user is used
			$newUserData = $phpbbForum->get_userdata('', $idToFetch, true);
			
			// only sync password if it is changing
			$ignorePassword = (request_var('new_password', '') == '');
			
			$phpbbForum->background(); 
			
			wpu_initialise_wp();
			
			$wpUserData = get_userdata($newUserData['user_wpuint_id']);
			wpu_sync_profiles($wpUserData, $newUserData, 'phpbb-update', $ignorePassword);
			$phpbbForum->foreground();
		}


		if($handle != 'body') {
			return;
		}

		/**
		 * An additional check to ensure we don't act on a $template->assign_display('body') event --
		 * if a mod is doing weird things with $template instead of creating their own $template object
		 */
		if($wpUnited->should_do_action('template-w-in-p')) {
			if($wpuBuffered = wpu_am_i_buffered()) {
				return;
			}
		}
		
		// nested hooks don't work, and append_sid calls a hook. Furthermore we will call ->display again anyway:
		$wpuRunning = true;
		if(defined('SHOW_BLOG_LINK') && SHOW_BLOG_LINK) {
			$template->assign_vars(array(
				'U_BLOG'	 =>	append_sid($wpUnited->get_wp_home_url(), false, false, $GLOBALS['user']->session_id),
				'S_BLOG'	=>	TRUE,
			)); 
		}
		
		if($wpUnited->should_do_action('template-p-in-w')) { 
			$template->display($handle);
			$wpUnited->set_inner_content(ob_get_contents()); 
			ob_end_clean(); 
			if(in_array($template->filename[$handle], (array)$GLOBALS['WPU_NOT_INTEGRATED_TPLS'])) {
				//Don't reverse-integrate pages we know don't want header/footers
				echo $wpUnited->get_inner_content();
			} else {  
				//insert phpBB into a wordpress page
				require_once($wpUnited->get_plugin_path() . 'template-integrator.php');
				wpu_integrate_templates();

			}
			
		
		} elseif (defined('PHPBB_EXIT_DISABLED')) {
			/**
			 * page_footer was called, but we don't want to close the DB connection & cache yet
			 */
			$template->display($handle);
			$GLOBALS['bckDB'] = $db;
			$GLOBALS['bckCache'] = $cache;
			$db = ''; $cache = '';
			
			return '';
		} 
	}
	
}


/**
 * Prevent phpBB from exiting
 */
function wpu_continue(&$hook) {
	global $wpuRunning, $wpuBuffered, $wpUnited;
	
	if (defined('PHPBB_EXIT_DISABLED') && !defined('WPU_FINISHED')) {
		return '';
	} else if ( $wpuBuffered && (!$wpuRunning) && $wpUnited->should_do_action('template-p-in-w') ) {
		/** if someone else was buffering the page and are now asking to exit,
		 * wpu_execute won't have run yet
		 */
		$buff = false;
		// flush the buffer until we get to our reverse integrated layer
		while(wpu_am_i_buffered()) {
			ob_end_flush();
			$buff = true;
		}
		if($buff) {
			wpu_execute($hook, 'body');
		}
	}
}

/**
 * This is the last line of defence against mods which might be calling $template->assign_display('body')
 *
 * We err on the side of caution -- only intervening if we are undoubtedly buffered. As a result,
 * This may on occasion return false negative
 * 
 */
function wpu_am_i_buffered() {
	global $config, $wpuBufferLevel;
	// + 1 to account for reverse integration buffer
	
	$level = (isset($config['wpu_gzip_compress']) && $config['wpu_gzip_compress'] && @extension_loaded('zlib') && !headers_sent()) + 1;
	if(ob_get_level() > ($wpuBufferLevel + $level)) {
		return true;
	}
	return false;
}

/**
 * Find base level of buffering -- e.g. if php.ini buffering is taking place
 * this ensures wpu_am_i_buffered detection is correct
 */
function wpu_set_buffering_init_level() {
	global $wpuBufferLevel;
	$wpuBufferLevel = ob_get_level();
}


function wpu_bootstrap() {
	global $config, $wpUnited;
	if(!class_exists('WP_United_Plugin')) {
		if(isset($config['wpu_location'])) {
			if(file_exists($config['wpu_location'])) {
				require_once($config['wpu_location'] . 'base-classes.php');
				require_once($config['wpu_location'] . 'plugin-main.php');
				$wpUnited = new WP_United_Plugin();	
			}
		}
	}
	if(!isset($wpUnited) || !is_object($wpUnited) || !$wpUnited->is_enabled()) {
		return false;
	}
	return true;
}

/**
 * Clear integration settings
 * Completely removes all traces of WP-united settings
 */
function wpu_clear_integration_settings() {
	global $wpUnited, $phpbbForum, $cache;
	
	$phpbbForum->clear_settings();
	$wpUnited->clear_style_keys();
	
	$cache->destroy('config');
}

// That's all. For documentation for hackers and developers, please look in the plugin -> wp-united.php
