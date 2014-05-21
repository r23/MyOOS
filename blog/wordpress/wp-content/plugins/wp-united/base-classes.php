<?php

/** 
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* Settings and base WP-United class. 
*/

/**
 */
if ( !defined('IN_PHPBB') && !defined('ABSPATH') ) exit;

/**
 *	A simple factory object that can store itself in phpBB.
 *  The constructor either returns ourself, initialised by WordPress, or a stored serialized 
 *  object that was passed to phpBB.
 *
*/
class WP_United_Settings {

	public
		$pluginPath = '',
		$wpPath = '',
		$wpHomeUrl = '',
		$wpBaseUrl = '',
		$pluginUrl = '',
		$wpDocRoot = '',
		$enabled = false,
		$settings = array();
	
	/**
	 * Not used. Use ::create() to receive an instantiated object from this factory
	 */
	public function __construct() {
		
	}

	/**
	 * Get an instantiated object from the factory
	 * Tries to load the settings object from WordPress. If WordPress is not available, falls back to the stored 
	 * phpBB settings object
	 * @return WP_United_Settings settings object
	 */
	public static function Create() {
		$s = new WP_United_Settings();
		if(!$s->load_from_wp()) {
			return($s->load_from_phpbb());
		}
		return $s;
	}
	
	/**
	 * Tries to initialise from WordPress options.
	 * @return bool true on success
	 */
	private function load_from_wp() {
		
		if(function_exists('get_option')) { 
			$savedSettings = (array)get_option('wpu-settings');
			$defaults = $this->get_defaults();
			$this->settings = array_merge($defaults, (array)$savedSettings);
			
			$this->wpPath = ABSPATH;
			$this->pluginPath = plugin_dir_path(__FILE__);
			$this->pluginUrl = plugins_url('wp-united') . '/';
			$this->wpHomeUrl = home_url('/');
			$this->wpBaseUrl = site_url('/');
			$this->wpDocRoot = wpu_get_doc_root();
			return true;
		}
		return false;
	}
	
	/**
	 * Tries to initialise by restoring a serialised object from phpBB config.
	 * Returns default settings if nothing can be loaded.
	 * @return WP_United_Settings settings object
	 */
	private function load_from_phpbb() {
		global $config;
		
		$wpuString = '';
		$key = 1;
		while(isset( $config["wpu_settings_{$key}"])) {
			$wpuString .= $config["wpu_settings_{$key}"];
			$key++;
		}

		// convert config value into something just like me :-)
		if(!empty($wpuString)) {
			$wpuString =  gzuncompress(base64_decode($wpuString));	
			$settingsObj = unserialize($wpuString); 
			if(is_object($settingsObj)) {
				return $settingsObj; 
			}
		}
		
		// failed on all accounts. Initialise ourselves with defaults
		$this->settings = $this->get_defaults();
		return $this;

	}

	/**
	 * Updates the settings in WordPress if WordPress is available
	 * @param array Array of settings to store
	 * @return void
	 */
	public function update_settings($data) {

		if(function_exists('update_option')) { 
			$data = array_merge($this->settings, (array)$data); 
			update_option('wpu-settings', $data);
			$this->settings = $data;
		}
	}

	/**
	 * Get the default settings
	 * @return array the default settings
	 */
	private function get_defaults() {
		return array(
			'phpbb_path' 				=> '',
			'integrateLogin' 			=> 0, 
			'showHdrFtr' 				=> 'NONE',
			'wpSimpleHdr' 				=> 1,
			'dtdSwitch' 				=> 0,
			'phpbbCensor' 				=> 1,
			'wpPageName' 				=> 'page.php',
			'phpbbPadding' 				=>  '6-12-6-12',
			'xposting' 					=> 0,
			'phpbbSmilies' 				=> 0,
			'avatarsync'				=> 1,
			'integcreatewp'				=> 1,
			'integcreatephpbb'			=> 1,
			'xpostautolink' 			=> 0,
			'xpostspam' 				=> 'all',
			'xpostforce' 				=> -1,
			'xposttype' 				=> 'excerpt',
			'xpostprefix'				=> '[BLOG] ',
			'cssMagic' 					=> 1,
			'templateVoodoo' 			=> 1,
			'useForumPage' 				=> 1
		);
	}
}


abstract class WP_United_Plugin_Base {
	protected 		
		$filters = array(),
		$actions = array(),
		$lastRun = false,
		$settings = false;
		
		
		
	public function __construct($initWithSettingsObj = false) {
		
		if(!$initWithSettingsObj) {
			$this->load_settings();
		} else {
			$this->settings = $initWithSettingsObj;
		}
	
	}
	
	protected function load_settings() {
		$this->settings = WP_United_Settings::Create();
	}
	
	public function get_plugin_path() {
		return $this->settings->pluginPath;
	}
	
	public function get_wp_path() {
		return $this->settings->wpPath;
	}
	
	public function get_wp_doc_root() {
		return $this->settings->wpDocRoot;
	}
	
	public function get_wp_home_url() {
		return $this->settings->wpHomeUrl;
	}
	
	public function get_wp_base_url() {
		return $this->settings->wpBaseUrl;
	}
	
	public function get_plugin_url() {
		return $this->settings->pluginUrl;
	}
		
	
	
	public function is_enabled() { 
		
		if (defined('WPU_DISABLE') && WPU_DISABLE) { 
			return false;
		}

		if($this->is_wordpress_loaded()) {
			$this->settings->enabled = get_option('wpu-enabled'); 
		}
		return $this->settings->enabled;
	}
	
	public function is_working() {
		// if ABSPATH is not defined, we must be loaded from phpBB
		if(!defined('ABSPATH')) {
			return true;
		} else {
			return (defined('IN_PHPBB') && ($this->get_last_run() == 'working') && ($this->is_enabled()));
		}
	}
	public function get_setting($key = '') { 
		
		if(!$key) {
			return (array) $this->settings->settings;
		}
		
		if(isset($this->settings->settings[$key])) { 
			return $this->settings->settings[$key];
		}
		return false;
	}
	
	/**
	 * Returns how the last fun of phpBB went
	 * @return string disconnected|connected|working
	 */
	public function get_last_run() {
	
		if(empty($this->lastRun) && $this->is_wordpress_loaded()) {
			$this->lastRun = get_option('wpu-last-run');
		}

		 return $this->lastRun;
	}	
	
	
	public function is_wordpress_loaded() {
		if(defined('ABSPATH')) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
		
	protected function add_actions() { 
		foreach($this->actions as $actionArray) {
			list($action, $details, $whenToLoad) = $actionArray;

			if(!$this->should_load_filteraction($whenToLoad)) {
				continue;
			}

			switch(sizeof((array)$details)) {
				case 3:
					add_action($action, array($this, $details[0]), $details[1], $details[2]);
				break;
				case 2:
					add_action($action, array($this, $details[0]), $details[1]);
				break;
				case 1:
				default:
					add_action($action, array($this, $details));
			}
		}	
	}
	
	protected function add_filters() {
		foreach($this->filters as $filterArray) {
			list($filter, $details, $whenToLoad) = $filterArray;
			
			if(!$this->should_load_filteraction($whenToLoad)) {
				continue;
			}
			
			switch(sizeof((array)$details)) {
				case 3:
					add_filter($filter, array($this, $details[0]), $details[1], $details[2]);
				break;
				case 2:
					add_filter($filter, array($this, $details[0]), $details[1]);
				break;
				case 1:
				default:
					add_filter($filter, array($this, $details));
			}
		}	
	}	
	// Should we load this filter or action? 
	private function should_load_filteraction($whenToLoad) {
	
		if(!$this->is_enabled() && ($whenToLoad != 'all')) {
			return false;
		}
		
		switch($whenToLoad) {
			case 'user-int':
				if(!$this->get_setting('integrateLogin')) {
					return false;
				}
			break;
			case 'template-int':
				if($this->get_setting('showHdrFtr') == 'NONE') {
					return false;
				}				
			break;
			case 'all':
			case 'enabled':
				return true;
			break;
			default:
				if(!$this->get_setting($whenToLoad)) {
					return false;
				}
			break;
		}
		
		return true;

	}
}



abstract class WP_United_Plugin_Main_Base extends WP_United_Plugin_Base {

	protected
		
		$version = '',
		$revision = '',
		$styleKeys = array(),
		$updatedStyleKeys = false,
		$styleKeysLoaded = false,
		$integActions = array(),
		$integActionsFor = 0,
		$connectedToWp = false,
		$innerContent = '',
		$outerContent = '',
		$innerHeadInfo = '';
		
		

	/**
	* Initialise the WP-United class
	*/
	public function __construct() {
		
		$currPath = dirname(__FILE__);
		require_once($currPath . '/functions-general.php');
		require_once($currPath . '/options.php');
		require_once($currPath . '/debugger.php');
		require_once($currPath . '/context-switcher.php');
		require_once($currPath . '/phpbb.php');
		require_once($currPath . '/cache.php');
		
		global $wpuDebug;
		$wpuDebug = new WPU_Debug();
		$wpuDebug->start_stats();

		global $phpbbForum;
		$phpbbForum = new WPU_Phpbb();

		parent::__construct();
		
	}
	
	// override the load_settings function for the base class as we want to load style keys too:
	protected function load_settings() {
		$this->settings = WP_United_Settings::Create();
		$this->init_style_keys();
	}

	public function enable() {
		$this->settings->enabled = true;
		if($this->is_wordpress_loaded()) {
			update_option('wpu-enabled', true);
		}
	}
	public function disable() {
		$this->settings->enabled = false;
		if($this->is_wordpress_loaded()) {
			update_option('wpu-enabled', false);
		}
	}

	

	
	public function phpbb_logout() {
		if($this->is_working()) {
			global $phpbbForum;
			$phpbbForum->logout();
		}
	}
	
	public function get_version($includeRevision = false) {
		if(empty($this->version)) {
			require_once ($this->get_plugin_path() . 'version.php');
			global $wpuVersion, $wpuRevision; 
			$this->version = $wpuVersion;
			$this->revision = $wpuRevision;
		}
		$rev = ($includeRevision) ? $this->revision : '';
		return $this->version . $rev;
	}
	
	public function check_mod_version() {
		global $phpEx;
		
		static $checked = false;
		
		if(is_array($checked)) {
			return $checked;
		}

		global $wpuAutoPackage, $wpuReleasePackage;
		$wpuWpPackage = (isset($wpuReleasePackage)) ? 'wp-united-nightly-phpbb' : 'wp-united-latest-phpbb';
		
		$phpbbUpgradeBase = sprintf(__('%1$sClick here%2$s to download the modification package. '),"<a href=\"http://www.wp-united.com/releases/{$wpuWpPackage}\">", '</a>');
		$phpbbInstallMsg = $phpbbUpgradeBase . sprintf(__('You can apply it using %1$sAutoMod%2$s (recommended), or manually by reading the install.xml file and following %3$sthese instructions%4$s. When done, click &quot;Connect&quot; to try again.', 'wp-united'), '<a href="http://www.phpbb.com/mods/automod/">', '</a>', '<a href="http://www.phpbb.com/mods/installing/">', '</a>');
		$phpbbUpgradeMsg = $phpbbUpgradeBase . sprintf(__('Find the %1$s file inside the %2$s folder and open it in your browser. Then follow the instructions to upgrade. Don\'t forget to copy over the new files to your phpBB forum.', 'wp-united'), 'upgrade.xml', 'contrib');
		$phpbbUpgradeSimpleMsg = $phpbbUpgradeBase . __('You can just copy over the files from the /root folder to your phpBB forum.', 'wp-united');
		
		$verMismatchMsg = __('You are running WP-United version %1$s, but the WP-United phpBB MOD version you have installed is version %2$s.');
		
	
		$pLoc = $this->get_setting('phpbb_path');
		
		if(empty($pLoc)) {
			$checked =  array(
				'result'	=>	'OK',
				'message'	=> 	__('The location to phpBB is not set.')
			);
			return $checked;
		}
		
		// Not installed!
		if(!@file_exists($pLoc . 'wp-united/')) {
			$checked = array(
				'result'	=> 'ERROR',
				'message'	=> __('You need to install the WP-United phpBB MOD.', 'wp-united') . '<br /><br />' . $phpbbInstallMsg
			);
			return $checked;
		}
		
		$version = str_replace('-r', '#', $this->get_version(true));
		
		// Installed, but version < 0.9.1.0
		if(!@file_exists($pLoc . 'wp-united/version.php')) {
			$checked = array(
				'result'	=> 'ERROR',
				'message'	=> sprintf($verMismatchMsg, $version, '0.9.0.x') . 
								'<br /><br />' . $phpbbUpgradeMsg
			);
			return $checked;
		}
		
		@include_once($pLoc . 'wp-united/version.php');
		
		/**
		 * Version checks for 0.9.1.0 and above...
		 */
		if($wpuVersion_phpbb != $version) {
			
			// upgrade to v0.9.2.5; full xml upgrade
			if(version_compare($wpuVersion_phpbb, '0.9.2.5', '<')) {
				$checked = array(
					'result'	=> 'ERROR',
					'message'	=> sprintf($verMismatchMsg, $version, 'v0.9.2.4 or earlier') . 
									'<br /><br />' . $phpbbUpgradeMsg
				);
				return $checked;
			}
			

			
			
						
			// Add future upgrade checks here
			
			
			
			
		}
		
		$checked = array(
			'result' 		=> 'OK',
			'mesage' 	=> ''
		);
		return $checked;

	}
	
	public function upgrade() {
		global $wpuDebug, $phpbbForum, $wpuRevision;
		
		$installedVer = get_option('wpu-version');
		$isNewInstall = false;
		
		if(empty($installedVer)) {
			$installedVer = '';
			$isNewInstall = (get_option('wpu-new-install') == 'yes');
			
		}
		
		$v = explode('-r', $installedVer);
		$baseInstalledVer = $v[0];
		
		$actualVer = $this->get_version(true);
		$upgradeAction = false;
		
		// if this is a new install, there will be no options set, but we won't need to upgrade.
		if(!$isNewInstall) {
			switch($installedVer) {
			
				case '':
					$upgradeAction = 'from <0.9.2.0';
				break;
				case '0.9.2.0':
					$upgradeAction = 'from 0.9.2.0-r0';
				break;
				case '0.9.2.0-r1':
					$upgradeAction = "from $installedVer";
				break;
			}
		}
		
		
		// Add additional version compare here for future versions



		if(!empty($upgradeAction)) {
			require_once($this->get_plugin_path() . 'upgrade.php');
			wpu_do_upgrade($upgradeAction);
		}	
		
		
		if($installedVer != $actualVer) {
			update_option('wpu-version', $this->get_version(true));
		}
		
		if($isNewInstall) {
			delete_option('wpu-new-install');
		}
		
		
	}
	
	
	public function init_style_keys() {
		global $phpbbForum;
		/**
		 * Handle style keys for CSS Magic
		 * We load them here so that we can auto-remove them if CSS Magic is disabled
		 */ 
		 
		if($this->styleKeysLoaded) {
			return;
		}
		 
		if($this->is_working()) {
			$this->styleKeysLoaded = true;
			
			$this->styleKeys = $phpbbForum->load_style_keys(); 
		}
	}

	// TODO: PUT THIS IN phpBBFORUM?
	public function clear_style_keys()	{
		global $phpbbForum;
		
		$phpbbForum->erase_style_keys();
		$this->styleKeys = array();
	}
	
	public function set_ran_patched_wordpress() {
		$this->connectedToWp = true;
	}
	
	public function ran_patched_wordpress() {
		return $this->connectedToWp;
	}

	
	public function get_style_key($key = '') {
		if($key === '') {
			return $this->styleKeys;
		} else {
			if(array_key_exists($key, $this->styleKeys)) {
				return $this->styleKeys[$key];
			} else {
				return false;
			}
		}
	}
	
	
	// adds a new style key, or returns the existing one if it already exists
	public function add_style_key($fileName) {
		$key = array_search($fileName, (array)$this->styleKeys);
		if($key === false) {
			$this->styleKeys[] = $fileName;
			$key = sizeof($this->styleKeys) - 1;
			$this->updatedStyleKeys = true;
		} 
		return $key;
	}
	
	/**
	 * Saves updated style keys to the database.
	 * 
	 * @return int the number of config keys used
	 */ 
	public function commit_style_keys() { 
		global $phpbbForum;
		
		if(!$this->updatedStyleKeys) {
			return sizeof($this->styleKeys) - 1;
		}

		$result = $phpbbForum->commit_style_keys($this->styleKeys);
		$this->updatedStyleKeys = false;
		return $result;
	}



	
	/**
	 * Determine if we need to load WordPress, and compile a list of actions that will need to take place once we do
	 */
	protected function assess_required_wp_actions() {
		global $phpEx, $user;
		
		if(defined('WPU_PHPBB_IS_EMBEDDED')) { // phpBB embedded in WP admin page
			return 0;
		}
		
		$numActions = sizeof($this->integActions);
		if($numActions > 0) { 
			return $numActions;
		}
		
		
		if(!$this->is_wordpress_loaded()) {
			
			// Check for user integration-related actions
			if($this->get_setting('integrateLogin')) { 
			
				// Is this a login/out page or profile update?
				if(preg_match("/\/ucp\.{$phpEx}/", $_SERVER['REQUEST_URI'])) { 
					
					$actionMode = request_var('mode', '');	
					
					if($actionMode == 'logout') {
						$this->integActions[] = 'logout';
					//} else if($actionMode == 'login') { 
						//$this->integActions[] = 'login';
					} else if(($actionMode == 'profile_info') || ($actionMode == 'reg_details') || ($actionMode == 'avatar')) {
						
						$didSubmit = request_var('submit', '');
						if(!empty($didSubmit)) {
							$this->integActions[] = 'profile';
							$this->integActionsFor = 0;
						}
					}
				// Or is it an admin editing a user's profile?
				} else if(defined('ADMIN_START')) {
				
					$didSubmit = request_var('update', '');
					
					if(!empty($didSubmit)) {
						$actionMode = request_var('mode', '');
						$wpuActionsFor = (int)request_var('u', '');
						if(!empty($wpuActionsFor) && (($actionMode == 'profile') || ($actionMode == 'overview') || ($actionMode == 'profile'))) {
							$this->integActions[] = 'profile';
							$this->integActionsFor = $wpuActionsFor;
						}
					}
				}
			}
		
			// Check for template integration-related actions:
			if ($this->get_setting('showHdrFtr') == 'REV') {
				
				/**
				 *  We don't want to run template integration on an ACP page, unless this is the login message box.
				 * However we need to decide whether to run wordpress now, before $user exists. So we have to pre-load the session
				 * As it's only run on admin pages, it's not much of a bother even on busy forums.
				 * TODO: In future, we could add a core file edit to adm/index.php to defer the decision if this is considered too ugly
				 */
				$inAdmin = false;
				if(defined('ADMIN_START')) {
					$user->session_begin();
					 if(isset($user->data['session_admin']) && $user->data['session_admin']) {
						$inAdmin = true;
					}
				}
				if(!$inAdmin) {
					$this->integActions[] = 'template-p-in-w';
				}	
			}
		
		
		// if wordpress is loaded, we're only interested if this is a forward integration
		} else {
			if ($this->get_setting('showHdrFtr') == 'FWD') {
				$this->integActions[] = 'template-w-in-p';
			}
		}
		
		return sizeof($this->integActions);
	}
	
	public function get_num_actions() {
		return $this->assess_required_wp_actions();
	}
	
	public function should_run_wordpress() {
		$init = $this->assess_required_wp_actions();
		return (defined('ABSPATH')) ? false : $init;
	}
	

	public function actions_for_another() {
		return $this->integActionsFor;
	}
	
	
	public function should_do_action($actionName) {
		if(!sizeof($this->integActions)) {
			return false;
		}
		if(in_array($actionName, $this->integActions)) {
			return true;
		}
		return false;
	}
	
	public function set_inner_content($content) {
		$this->innerContent = $content;
	}
	
	public function set_outer_content($content) {
		$this->outerContent = $content;
	}	
	
	public function clear_inner_content() {
		$this->innerContent = '';
		$this->innerHeadInfo = '';
	}
	
	public function clear_content() {
		$this->clear_inner_content();
		$this->clear_outer_content();
	}
	
	public function clear_outer_content() {
		$this->outerContent = '';
	}
	
	public function get_inner_content() {
		return $this->innerContent;
	}
	
	public function get_outer_content() {
		return $this->outerContent;
	}
	
	public function get_wp_content() {
		if($this->should_do_action('template-p-in-w')) {
			return $this->outerContent;
		} else { 
			return $this->innerContent;
		}
	}
	
	public function get_phpbb_content() {
		if($this->should_do_action('template-p-in-w')) {
			return $this->innerContent;
		} else {
			return $this->outerContent;
		}
	}
	
	public function set_wp_content($content) {
		if($this->should_do_action('template-p-in-w')) { 
			$this->outerContent = $content;
		} else {
			$this->innerContent = $content;
		}
	}
	
	public function set_phpbb_content($content) {
		if($this->should_do_action('template-p-in-w')) {
			$this->innerContent = $content;
		} else {
			$this->outerContent = $content;
		}
	}
	
	public function set_inner_headinfo($content) {
		$this->innerHeadInfo = $content;
	}
	
	public function get_inner_headinfo() {
		return $this->innerHeadInfo;
	}
	
	public function get_inner_package() {
		if ($this->should_do_action('template-p-in-w')) {
			return 'phpbb';
		} else if ($this->should_do_action('template-w-in-p')) {
			return 'wp';
		}
		return 'none';
	}
	
	public function get_outer_package() {
		if ($this->should_do_action('template-p-in-w')) {
			return 'wp';
		} else if ($this->should_do_action('template-w-in-p')) {
			return 'phpbb';
		}
		return 'none';	
	}
	
	// Add copyright comment to the page source. It is also useful as a quick check to see if users actually have
	// WP-United installed.	
	public function add_boilerplate() {
		$boilerplate = "\n\n<!--\n phpBB <-> WordPress integration by John Wells, (c) 2006-2013 www.wp-united.com \n-->\n\n";
		$this->innerContent = $this->innerContent . $boilerplate;
	}
	
	protected function ajax_result($errMsg, $msgType = 'message') {
		if($msgType == 'error') {
			$errMsg = '[ERROR]' . $errMsg;
		}
		die($errMsg);
	}
	
	protected function ajax_ok() {
		$this->ajax_result('OK', 'message');
	}
	
	/**
	 * Disable WPU and output result directly to the calling script
	 *
	 */
	public function disable_connection($type) {
		
		if(!$this->is_enabled()) {
			$this->ajax_result(__('WP-United is already disabled', 'wp-united'), 'message');
		}
		
		$this->disable();
		
		switch ($type) {
			case 'error':
				switch ($this->get_last_run()) {
					case 'disconnected':
						$this->ajax_result(__('WP-United could not find phpBB at the selected path. WP-United is not connected.', 'wp-united'), 'error');
					break;
					case 'connected':
						$this->ajax_result(__('WP-United could not successfully run phpBB at the selected path. WP-United is halted.', 'wp-united'), 'error');
					break;
					default:
						$this->set_last_run('connected');
						$this->ajax_result(__('WP-United could not successfully run phpBB without errors. WP-United has been disconnected.', 'wp-united'), 'error');
				}
			break;
			case 'server-error':
				$this->ajax_ok();
			break;
			case 'manual':
				$this->ajax_result(__('WP-United Disabled Successfully', 'wp-united'), 'message');
			break;
			default:
				_e('WP-United Disabled', 'wp-united');
		}
		
		return;

	}	
}

// That's all. Done.