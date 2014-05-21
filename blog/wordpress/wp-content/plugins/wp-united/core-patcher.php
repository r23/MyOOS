<?php

/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* The core patcher for WordPress, when it needs to be run from the phPBB side
* This still contains a lot of old compatibility cruft that is likely no longer necessary as WP will always be called in the global scope with the new
* WP-United code flow. TODO: Remove wpu_compat paths and list of global vars
*
*/


/**
 */
if ( !defined('IN_PHPBB') && !defined('ABSPATH') ) exit;

/**
 * This class provides access to WordPress
 * It should be accessed as a singleton, and made global
 * It calculates and caches code pathways to invoke WordPress, which can then be evaluated in the global scope
 * 
 * It also handles modifying WordPress as necessary in order to integrate it.
 * @package WP-United Core Integration
 */
Class WPU_Core_Patcher {
 
	
	// The instructions we build in order to execute WordPress
	private $wpRun;
	
	/**
	 * This is a list of  vars phpBB also uses. We'll unset them when the class is instantiated, and restore them later. 
	 */
	private $varsToUnsetAndRestore = array('userdata', 'search', 'error', 'author');
	
	/**
	 * More vars that phpBB or MODS could use, that MUST be unset before WP runs
	 */
	private $varsToUnset = array('m', 'p', 'posts', 'w', 'cat', 'withcomments', 'withoutcomments', 's', 'search',
		'exact', 'sentence', 'debug', 'calendar', 'page', 'paged', 'more', 'tb', 'pb', 
		'author', 'order', 'orderby', 'year', 'monthnum', 'day', 'hour', 'minute', 'second',
		'name', 'category_name', 'feed', 'author_name', 'static', 'pagename', 'page_id', 'error',
		'comments_popup', 'attachment', 'attachment_id', 'subpost', 'subpost_id', 'preview', 'robots', 'entry');
		
	private $oldEnv;
	
	private 
		$sleepingVars = array(),
		$varsToSave,
		$wpVersion,
		$wpu_ver,
		$wpLoaded, // prevents the main WordPress script from being included more than once
		$wpu_compat; // Compatibility mode? TODO: REMOVE COMPAT MODE ONCE CONFIRMED UNNEEDED

	
	/*
	 * This class MUST be called as a singleton using this method
	 * @param array $varsToSave An array of variable names that should be saved in their current state
	 */
	public static function getInstance ($varsToSave = FALSE ) {
		static $instance;
		if (!isset($instance)) {
			$instance = new WPU_Core_patcher($varsToSave);
        } 
        	return $instance;
    	}

	/**
	 * Class constructor.
	 * Takes a snapshot of phpBB variables at this point.
	 * When we exit WordPress, these can be restored.
	 */
	public function __construct($varsToSave) {
		global $wpUnited;
		//these are constants that ain't gonna change - we're going to need them in our class
		$this->wpRun = '';

		// store all vars set by phpBB, ready for retrieval after we exit WP
		/**
		 * @todo disable passing in of varsToSave -- may be better to 100% manage
		 */
		if ($varsToSave === FALSE ) {
			$varsToSave = $this->varsToUnsetAndRestore;
		}
		$this->varsToSave = $varsToSave;
		$this->wpLoaded = FALSE;
		$this->wpVersion = 0;
		$this->wpu_ver = $GLOBALS['wpuVersion'];
		
		$this->wpu_compat = true;
		
	}
	
	/**
	 * Test connection to WordPress
	 */
	public function can_connect_to_wp() {
		global $wpUnited;
	
		return @file_exists( $wpUnited->get_wp_path() . 'wp-settings.php');

	}
	
	/**
	 * Tests if the core cache is ready
	 */
	public function core_cache_ready() {
		
		$wpuCache = WPU_Cache::getInstance();
	
		if ( !$wpuCache->core_cache_enabled() ){
			return false;
		}

		if ($wpuCache->use_core_cache($this->wpVersion, $this->wpu_compat)) {
			return true;
		}
		return false;
	} 
		

	
	/**
	 * Prepares code for execution by adding it to the internal code store
	 */
	private function prepare($wpCode) {
		$this->wpRun .= $wpCode . "\n";
	}
	
	
	/**
	 * Returns the code to be executed.
	 * eval() can be called directly on the returned string
	 */	
	public function exec() {
		$code = $this->wpRun;

		$this->wpRun = '';
		return $code;
	}
	
	/**
	 * Loads up the WordPress install, to just before the point that the template would be shown
	 */
	public function enter_wp_integration() {

		global $wpUnited;
		//Tell phpBB that we're in WordPress. This controls the branching of the duplicate functions get_userdata and make_clickable
		$GLOBALS['IN_WORDPRESS'] = 1;
		
		$wpuCache = WPU_Cache::getInstance();
		
		/**
		 * @since v0.8.0
		 */
		foreach($this->varsToUnsetAndRestore as $value) {
			if(isset($GLOBALS['value'])) {
				$this->sleepingVars[$value] = $GLOBALS[$value];
			}
		}
		
		// This is not strictly necessary, but it cleans up the vars we know are important to WP, or unsets variables we want to explicity get rid of.
		$toUnset=array_merge( $this->varsToUnsetAndRestore, $this->varsToUnset);
		foreach ( $toUnset as $varNames) {
			if(isset($GLOBALS[$varNames])) {
				unset($GLOBALS[$varNames]);
			}
		} 
		

		//Override site cookie path if set in options.php
		if ( (defined('WP_ROOT_COOKIE')) && (WP_ROOT_COOKIE) ) {
			define  ('SITECOOKIEPATH', '/');
			define  ('COOKIEPATH', '/');
		}		

		
		
		// do nothing if WP is already loaded
		if ($this->wpLoaded ) {
			$this->prepare('$GLOBALS[\'phpbbForum\']->background();');
			return FALSE;
		}
		
		$this->wpLoaded = true;
		
		//Which version of WordPress are we about to load?
		global $wp_version;
		require($wpUnited->get_wp_path() . 'wp-includes/version.php');
		$this->wpVersion = $wp_version;
		
		
		
		global $user;
		// Added realpath to account for symlinks -- 
		//otherwise it is inconsistent with __FILE__ in WP, which causes plugin inconsistencies.
		$realAbsPath = realpath($wpUnited->get_wp_path());
		$realAbsPath = ($realAbsPath[strlen($realAbsPath)-1] == "/" ) ? $realAbsPath : $realAbsPath . "/";
		define('ABSPATH',$realAbsPath);
		
		
		//require_once($wpUnited->get_plugin_path() . 'plugin-fixer.php');
		
		if (!$this->core_cache_ready()) {
			
			// Now wp-config can be moved one level up, so we try that as well:
			$wpConfigLoc = (!@file_exists($wpUnited->get_wp_path() . 'wp-config.php')) ? $wpUnited->get_wp_path() . '../wp-config.php' : $wpUnited->get_wp_path() . 'wp-config.php';

			$cConf = file_get_contents($wpConfigLoc);
			$cSet = file_get_contents($wpUnited->get_wp_path() . 'wp-settings.php');
			
			//Handle the make clickable conflict
			if (@file_exists($wpUnited->get_wp_path() . 'wp-includes/formatting.php')) {
				$fName='formatting.php'; 
			} else {
				trigger_error($user->lang['Function_Duplicate']);
			}
			$cFor = file_get_contents($wpUnited->get_wp_path() . "wp-includes/$fName");
			$cFor = '?'.'>'.trim(str_replace('function make_clickable', 'function wp_make_clickable', $cFor)).'[EOF]';
			$finds = array(
				'require (ABSPATH . WPINC . ' . "'/$fName",
				'require( ABSPATH . WPINC . ' . "'/$fName"
			);
			$cSet = str_replace($finds,"$cFor // ",$cSet);
			unset ($cFor); 
			
			// Fix plugins
			if($wpUnited->get_setting('pluginFixes')) {
				$strCompat = 'true'; //($this->wpu_compat) ? "true" : "false";
				
				
				// Must-use Plugins
				$token = 'foreach ( wp_get_mu_plugins() as $mu_plugin ) {';
				if(strstr($cSet, $token)) {
					$cSet = str_replace($token, '$GLOBALS[\'wpuMuPluginFixer\'] = new WPU_WP_Plugins(WPMU_PLUGIN_DIR, \'wpuMuPluginFixer\', \'' . $this->wpu_ver . '\', \'' .  $this->wpVersion . '\', ' . $strCompat . ');' . "\n\n" . $token, $cSet);
					$cSet = str_replace('include_once( $mu_plugin );', ' include_once( $GLOBALS[\'wpuMuPluginFixer\']->fix($mu_plugin, true));', $cSet);
				}
				
				
				// Multisite Plugins
				$token = 'foreach( wp_get_active_network_plugins() as $network_plugin )';
				if(strstr($cSet, $token)) {
					$cSet = str_replace($token, '$GLOBALS[\'wpuMsPluginFixer\'] = new WPU_WP_Plugins(WPMU_PLUGIN_DIR, \'wpuMsPluginFixer\', \'' . $this->wpu_ver . '\', \'' .  $this->wpVersion . '\', ' . $strCompat . ');' . "\n\n" . $token, $cSet);
					$cSet = str_replace('include_once( $network_plugin );', ' include_once( $GLOBALS[\'wpuMsPluginFixer\']->fix($network_plugin, true));', $cSet);
				}
				
				//WP Plugins
				$token = 'foreach ( wp_get_active_and_valid_plugins() as $plugin )';
				if(strstr($cSet, $token)) {
					$cSet = str_replace($token, '$GLOBALS[\'wpuPluginFixer\'] = new WPU_WP_Plugins(WP_PLUGIN_DIR, \'wpuPluginFixer\', \'' . $this->wpu_ver . '\', \'' .  $this->wpVersion . '\', ' . $strCompat . ');' . "\n\n" . $token, $cSet);
					$cSet = str_replace('include_once( $plugin );', ' include_once($GLOBALS[\'wpuPluginFixer\']->fix($plugin, true));', $cSet);
				}
				
				// Theme functions
				$token = 'if ( ! defined( \'WP_INSTALLING\' ) || \'wp-activate.php\' === $pagenow ) {';
				if(strstr($cSet, $token)) {
					$cSet = str_replace($token, 'global $wpuStyleFixer; $wpuStyleFixer = new WPU_WP_Plugins(STYLESHEETPATH, \'wpuThemeFixer\', \'' . $this->wpu_ver . '\', \'' .  $this->wpVersion . '\', ' . $strCompat . ');' . "\n" .  'global $wpuThemeFixer; $wpuThemeFixer = new WPU_WP_Plugins(TEMPLATEPATH, \'themes\', \'' . $this->wpu_ver . '\', \'' .  $this->wpVersion . '\', ' . $strCompat . ');' . "\n\n" . $token, $cSet);
					$cSet = str_replace('include( STYLESHEETPATH . \'/functions.php\' );', ' include_once($wpuStyleFixer->fix(STYLESHEETPATH . \'/functions.php\', true));', $cSet);
					$cSet = str_replace('include( TEMPLATEPATH . \'/functions.php\' );', ' include_once($wpuThemeFixer->fix(TEMPLATEPATH . \'/functions.php\', true));', $cSet);
				}
			}
			
			
			
			/**
			 * We want to defer initialisation of WP to later, so we wrap the last portion into a callable function
			 */
			$cSet = str_replace(
				array('$wp->init()', '$GLOBALS[\'wp\']->init()'), 
				'function wpu_deferred_wp_load() { $GLOBALS[\'wp\']->init()', 
				$cSet
			);
			
		
			$cSet = '?'.'>'.trim($cSet).'[EOF]';
			$cConf = trim($cConf).'[EOF]';
			
			//The callable function is closed here, in case any trailing content has been added to wp-config after the call to wp-settings.
			$cConf = str_replace('require_once',$cSet . ' // ',$cConf) . "\n" . '}' . '?' . '>';
			
			// replace EOFs  -- some versions of WP have closing ? >, others don't
			// We do it here to prevent expensive preg_replaces
			$cConf = str_replace(array('?'.'>[EOF]', '[EOF]'), array('?'.'><'.'?php ', ''), $cConf);

			$this->prepare($content = '?'.'>'. trim($cConf). '<' . '?php');
			unset ($cConf, $cSet);


			if ( $wpuCache->core_cache_enabled()) {
				$wpuCache->save_to_core_cache($content, $this->wpVersion, $this->wpu_compat);
			}
		} else { 
			$this->prepare('require_once(\'' . $wpuCache->coreCacheLoc . '\');');
		}
		
		if(defined('WPU_BOARD_DISABLED')) {
			$this->prepare('wp_die(\'' . WPU_BOARD_DISABLED . '\', \'' . $GLOBALS['user']->lang['BOARD_DISABLED'] . '\');');
		}
		
		
		return true;

	}
	

	
	/**
	 * Fixes and returns template-loader
	* @TODO this can use the plugin fixer tools to enter and fix themes too
	* @TODO cache
	* The plugin fixer should extend a base general compatibility class in order to do so.
	* This must be executed just-in-time as WPINC is not yet set
	*/
	public function load_template() {
		$wpuTemplate = file_get_contents(ABSPATH . WPINC . '/template-loader.php');
		$finds = array(
			'return;',
			'do_feed',
			'do_action(\'do_robots\');',
			'if ( is_trackback() ) {',
			'} else if ( is_author()',
			'} else {'
		);
		$repls = array(
			'',
			'$wpuNoHead = true; do_feed',
			'$wpuNoHead = true; do_action(\'do_robots\');',
			'if (is_trackback()) {$wpuNoHead=true;',
			'}else if(is_author()&& $wpUnited->get_setting(\'usersOwnBlogs\') && $wp_template=get_author_template()){include($wp_template);} else if ( is_author()',
			'} else { $wpuNoHead = true;'
		);
		$wpuTemplate = str_replace($finds, $repls, $wpuTemplate);
		eval("\n" . '?' . '>' . trim($wpuTemplate) . '?>');
	}

	

	
	/**
	 * Exits this class, and cleans up, restoring phpBB variable state
	 */
	public function exit_wp_integration() {
		global $phpbbForum;
		// check, in case user has deactivated wpu-plugin
		if(isset($phpbbForum)) {
			$phpbbForum->foreground();
		}

		// We previously here mopped up all the WP vars that had been created... but it is a waste of CPU and usually unnecessary

		//reinstate all the phpBB variables that we've put "on ice", let them overwrite any variables that were claimed by WP.
		foreach ($this->sleepingVars as $varName => $varVal) {
				if ( ($varName != 'wpuNoHead') && ($varName != 'wpUnited') && ($varName != 'phpbbForum') ) {
					global $$varName;
					$$varName = $varVal;
				}
		}
		 
		
		$GLOBALS['IN_WORDPRESS'] = 0; //just in case
		$this->wpRun = '';
	}
	


}

// Done. End of file.
