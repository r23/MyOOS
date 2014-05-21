<?php

/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* WP-United Extras loader
* 
* This class auto-loads new WP-United sub-plugins.
* This is still a Work-in-progress: Eventually there will be a UI to downloads and install additional plugins.
*
* For now, the API is very simple. To add a plugin, create a subdirectory in the /extras folder. That subdirectory name is the 
* name of the plugin. In the subdirectory, create a file called main.php. 
* This file should contain only one class, WP_United_Extra_<name>, where <name> is the same as the subdirectory name.
* The class cannot have its own constructor, but MUST implement one event: on_init(). This is called if WP-United is up and working.
*/

/**
 */
if ( !defined('IN_PHPBB') && !defined('ABSPATH') ) exit;

Class WP_United_Extras_Loader {
	
	private 
		$baseDir,
		$extras;
	
	public function __construct() {
		global $wpUnited;
		
		$this->extras = array();
		
		$this->baseDir = $wpUnited->get_plugin_path() . 'extras/';

		$dirs = @glob($this->baseDir . "*", GLOB_ONLYDIR);

		foreach((array)$dirs as $dir) {
			$extraFile = $dir . '/main.php';

			if(@file_exists($extraFile)) {
				include($extraFile);
			}
			$extrasName = basename($dir);
			$className = 'WP_United_Extra_' . $extrasName;
			if(class_exists($className)) {
				$extra = false;
				$extra = (new $className);
				if(is_object($extra)) {
					$this->extras[$extrasName] = $extra;
				}
			}
		}
		
	}
	
	public function init() {
		global $wpUnited;
	
		if($wpUnited->is_working()) {

			foreach($this->extras as $wpUnitedExtra) {
				$wpUnitedExtra->init();
			}
		}
	}
	
	public function page_load_actions() {
		global $wpUnited;

		if($wpUnited->is_working()) {
			foreach($this->extras as $wpUnitedExtra) {
				$wpUnitedExtra->on_page_load();
			}
		}	
	}
	
	public function admin_load_actions() {
		global $wpUnited;

		if($wpUnited->is_working()) {
			foreach($this->extras as $wpUnitedExtra) {
				$wpUnitedExtra->on_admin_load();
			}
		}	
	}
	
	public function widgets_init() {
		global $wpUnited;
		if($wpUnited->is_working()) {
			foreach($this->extras as $wpUnitedExtra) {
				$wpUnitedExtra->on_widget_init();
			}
		}
	}
	
	public function get_extra($extraName) {
		if(isset($this->extras[$extraName])) {
			return $this->extras[$extraName];
		}
		return false;
	}
}

Abstract Class WP_United_Extra {

	private 
		$loaded,
		$name;
		
	final public function __construct() {
		$this->name = basename(dirname(__FILE__));
	}
	final public function init() {
		$this->loaded = true;
		return $this->on_init();
	}
	
	final public function get_name() {
		return $this->extraName;
	}
	
	final public function is_loaded() {
		return $this->loaded;
	}


	// Child classes should implement this
	public function on_init() {
		return false;
	}
	
	// Child classes can implement this
	public function on_page_load() {
		return false;
	}
	
	// Child classes can implement this
	public function on_admin_load() {
		return false;
	}

	// Child classes can implement this
	public function on_widget_init() {
		return false;
	}
	
}


// That's all. Simple but (I hope...) useful.