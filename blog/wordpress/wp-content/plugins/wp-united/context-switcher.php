<?php
/** 
*
* @package WP-United
* @version $Id: 0.9.2.0  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* The base class for the phpBB abstraction layer
* This contains the core context switching functions; separated from the rest of the rather huge abstraction layer.
* 
*/

/**
 */
if ( !defined('ABSPATH') && !defined('IN_PHPBB') ) exit;

abstract class WPU_Context_Switcher {
	private
		
		$wpTablePrefix,
		$wpUser,
		$wpCache,
		$phpbbTablePrefix,
		$phpbbUser,
		$phpbbCache,
		$phpbbDbName,
		$phpbbTemplate,
		$wpTemplate,
		$wpConfig,
		$phpbbConfig,
		$wpAuth,
		$phpbbAuth,
		$wpEnv,
		$phpbbEnv,
		$state,
		$was_out;
	
	public
		$lang;

	public function __construct() {
	
		if(defined('IN_PHPBB')) { 
			$this->lang = $GLOBALS['user']->lang;
			$this->state = 'phpbb';
			$this->phpbbTemplate = $GLOBALS['template'];
			$this->phpbbTablePrefix = $GLOBALS['table_prefix'];
			$this->phpbbUser = $GLOBALS['user'];
			$this->phpbbCache = $GLOBALS['cache'];		
			$this->phpbbConfig = $GLOBALS['config'];	
			$this->phpbbAuth = $GLOBALS['auth'];	
		}
		
		$this->was_out = false;
		$this->wpEnv = array();
		$this->phpbbEnv = array();
	}

	/**
	 * Gets the current forum/WP status
	 */
	public function get_state() {
		return $this->state;
	}
	
	/**
	 * Sends phpBB into the background, restoring WP database and vars
	 * @param bool $state whether to perform the action. Optional -- shortcut for if in the calling function
	 */
	public function background($state = true) {
		if($state) {
			$this->leave();
		}
	}
	
	/**
	 * Alias for background(). However $state must be provided
	 * @ param bool $state whether to perform the action (shortcut for if in the calling function.)
	 */
	public function restore_state($state) {
		$this->background($state);
	}
	
	/**
	 * Brings phpBB (db, conflicting vars, etc) to the foreground
	 * calling functions can track the returned $state parameter and use it to restore the same state when
	 * they exit.
	 * @return bool whether phpBB was in the background and we actually had to do anything.
	 */
	public function foreground() {
		if($this->state != 'phpbb') {
			$this->enter();
			return true;
		}
		return false;
	}	
		
	/**
	 * Enters the phpBB environment
	 * @access private
	 */
	private function enter() { 
		$this->lang = (isset($this->phpbbUser->lang)) ? $this->phpbbUser->lang : $this->lang;
		if($this->state != 'phpbb') {
			$this->backup_wp_conflicts();
			$this->restore_phpbb_state();
			$this->make_phpbb_env();
			$this->switch_to_phpbb_db();
		}
	}
	
	/**
	 * Returns to WordPress
	 */
	private function leave() { 
		if(isset($GLOBALS['user']) && is_object($GLOBALS['user'])) {
			$this->lang = (sizeof($GLOBALS['user']->lang)) ? $GLOBALS['user']->lang : $this->lang;
		}
		if($this->state == 'phpbb') {
			$this->backup_phpbb_state();
			if(defined('DB_NAME')) {
				$this->switch_to_wp_db();
			}
			$this->restore_wp_conflicts();
			$this->make_wp_env();
		}
	}
	

	
	/**
	 * @access private
	 */
	protected function make_phpbb_env() {
		global $IN_WORDPRESS;
		
		$IN_WORDPRESS = 1; 
		$this->state = 'phpbb';
	}

	/**
	 * @access private
	 */	
	protected function make_wp_env() {
		$this->state = 'wp';
		$this->disable_phpbb_err_handler();
	}

	/**
	 * @access private
	 */	
	protected function backup_wp_conflicts() {
		global $table_prefix, $user, $cache, $template, $config, $auth;
		
		$this->wpTemplate = $template;
		$this->wpTablePrefix = $table_prefix;
		$this->wpUser = (isset($user)) ? $user: '';
		$this->wpCache = (isset($cache)) ? $cache : '';
		// $config isn't generally used by WP, but W3 total cache apparently uses it.
		$this->wpConfig = (isset($config)) ? $config : '';
		$this->wpAuth = (isset($auth)) ? $auth : '';
		$this->wpEnv = array(
			'GET' 		=> $_GET,
			'POST' 		=> $_POST,
			'COOKIE' 	=> $_COOKIE,
			'REQUEST' 	=> $_REQUEST,
			'SERVER' 	=> $_SERVER
		);
	}

	/**
	 * @access private
	 */	
	protected function backup_phpbb_state() {
		global $table_prefix, $user, $cache, $dbname, $template, $config, $auth;

		$this->phpbbTemplate = $template;
		$this->phpbbTemplate = $template;
		$this->phpbbTablePrefix = $table_prefix;
		$this->phpbbUser = (isset($user)) ? $user: '';
		$this->phpbbCache = (isset($cache)) ? $cache : '';
		$this->phpbbDbName = $dbname;
		$this->phpbbConfig = $config;
		$this->phpbbAuth = $auth;
		$this->phpbbEnv = array(
			'GET' 			=> $_GET,
			'POST' 		=> $_POST,
			'COOKIE' 	=> $_COOKIE,
			'REQUEST' 	=> $_REQUEST,
			'SERVER' 	=> $_SERVER
		);
	}

	/**
	 * @access private
	 */	
	protected function restore_wp_conflicts() {
		global $table_prefix, $user, $cache, $template, $config, $auth;
		
		$template = $this->wpTemplate;
		$user = $this->wpUser;
		$cache = $this->wpCache;
		$config = $this->wpConfig;
		$auth = $this->wpAuth;
		$table_prefix = $this->wpTablePrefix;
		if(sizeof($this->wpEnv)) {
			 $_GET 			= $this->wpEnv['GET'];
			 $_POST 			= $this->wpEnv['POST'];
			 $_COOKIE 		= $this->wpEnv['COOKIE'];
			 $_REQUEST 	= $this->wpEnv['REQUEST'];
			 $_SERVER 		= $this->wpEnv['SERVER'];
		}
	}

	/**
	 * @access private
	 */	
	protected function restore_phpbb_state() {
		global $table_prefix, $user, $cache, $template, $config, $auth;
		
		$template = $this->phpbbTemplate;
		$table_prefix = $this->phpbbTablePrefix;
		$user = $this->phpbbUser;
		$cache = $this->phpbbCache;
		$config = $this->phpbbConfig;
		$auth = $this->phpbbAuth;
		
		$this->restore_phpbb_err_handler();
		
		if(sizeof($this->phpbbEnv)) {
			 $_GET 			= $this->phpbbEnv['GET'];
			 $_POST 			= $this->phpbbEnv['POST'];
			 $_COOKIE 		= $this->phpbbEnv['COOKIE'];
			 $_REQUEST 	= $this->phpbbEnv['REQUEST'];
			 $_SERVER 		= $this->phpbbEnv['SERVER'];
		}

	}

	/**
	 * @access private
	 */	
	protected function switch_to_wp_db() {
		global $wpdb;
		if (defined('DB_NAME') && ($this->phpbbDbName != DB_NAME) && (!empty($wpdb->dbh))) {
			@mysql_select_db(DB_NAME, $wpdb->dbh);
		}      
	}
	
	/**
	 * @access private
	 */	
	protected function switch_to_phpbb_db() {
		global $db, $dbms;
		if (defined('DB_NAME') && ($this->phpbbDbName != DB_NAME) && (!empty($db->db_connect_id))) {
			if($dbms=='mysqli') {
				@mysqli_select_db($this->phpbbDbName, $db->db_connect_id);
			} else if($dbms=='mysql') {
				@mysql_select_db($this->phpbbDbName, $db->db_connect_id);
			}
		}
	}
	
	protected function get_phpbb_user_object() {
		if($this->get_state() == 'wp') {
			return $this->phpbbUser;
		} else {
			return $GLOBALS['user'];
		}
	}
	
	protected function disable_phpbb_err_handler() {
		restore_error_handler();
	}

	protected function restore_phpbb_err_handler() {
		// restore phpBB error handler
		if(function_exists('msg_handler') || defined('PHPBB_MSG_HANDLER')) {
			set_error_handler(defined('PHPBB_MSG_HANDLER') ? PHPBB_MSG_HANDLER : 'msg_handler');
		}
	}	
	

}


// Done
