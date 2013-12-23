<?php

/** 
*
* WP-United Debugger
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
*/

/**
 */
if ( !defined('IN_PHPBB') && !defined('ABSPATH') ) exit;

class WPU_Debug {
	
	private 
		$debugBuffer,
		$sanitisedParts;
	
	public function __construct() {
		$this->debugBuffer = array();
		$this->sanitisedParts = array();
	}
	
	public function add($debug, $type = 'login') {
		if ( defined('WPU_DEBUG') && (WPU_DEBUG == TRUE) ) {
				if(!isset($this->debugBuffer[$type])) {
					$this->debugBuffer[$type] = array();
				}
				$this->debugBuffer[$type][] = $debug;
		}
	}
	
	private function get($type) {
		if(!isset($this->debugBuffer[$type])) {
			return '';
		}
		$result = implode('<br />', $this->debugBuffer[$type]);
		$this->debugBuffer[$type] = array();
		return $result;
	}
	
	public function get_block($type = 'login') {
		$result = '<!-- wpu-debug --><div style="border: 1px solid #8f1fff; background-color: #cc99ff; padding: 3px; margin: 6px; color: #ffff99;clear: both;">';
		$result .= '<strong>DEBUG</strong><br />WP Version = ' . $GLOBALS['wp_version'] . '<br />';
		$result .= $this->get($type);
		$result .= '</div><!-- /wpu-debug -->';
		return $result;
	}
	
	public function display($type = 'login') {
		echo $this->get_block($type);
	}
	
	public function add_debug_box($content, $type = 'login') {
		if(stristr($content, '</body>') !== false) {
			return str_replace('</body>', $this->get_block($type) . '</body>', $content);
		} else {
			return $content . $this->get_block($type);
		}
		
	}
	
	public function start_stats() {
		$timeStart = explode(' ', microtime());
		$this->scriptTime = $timeStart[0] + $timeStart[1];
	}
	
	public function get_stats() {
		
		$wpuCache = WPU_Cache::getInstance();
		
		$endTime = explode(' ', microtime());
		$endTime = $endTime[1] + $endTime[0];
		$pageLoad = round($endTime - $this->scriptTime, 4) . " seconds";
	
		$memUsage = (function_exists('memory_get_peak_usage')) ? round(memory_get_peak_usage()/1024, 0) . "kB" : (function_exists('memory_get_usage')) ? round(memory_get_usage() / 1024, 0) . "kB" : "[Not supported on your server]";
		return "<p style='clear: both;background-color: #999999;color: #ffffff !important;display: block;'><strong style='text-decoration: underline;'>WP-United Statistics </strong><br />Script Time: " . $pageLoad . "<br />Memory usage: " . $memUsage . "<br />" . $wpuCache->get_logged_actions() . "</p>";		
	}
	
	public function display_stats() {
		echo $this->get_stats();
	}
	
	public function add_stats_box($content) {
		if(stristr($content, '</body>') !== false) {
			return str_replace('</body>', $this->get_stats() . '</body>', $content);
		} else {
			return $content . $$this->get_stats();
		}
		
	}
	
	public function get_debug_info($sanitise) {
		global $wpUnited, $wp_version, $phpbbForum, $locale;
		
		$s = $sanitise;
		
		$settings = $wpUnited->get_setting();
		$memLimit = ini_get('memory_limit');
		$mainEntries = array(
			'WP-United Version' 			=> 	$wpUnited->get_version(true),
			'WordPress Version' 			=> 	$wp_version,
			'PHP Version'						=>	PHP_VERSION,
			'WP-United enabled?'			=>	($wpUnited->is_enabled())? 'Yes' : 'No',
			'WordPress Home URL'		=>	($s) ? $this->sanitise($wpUnited->get_wp_home_url()) : $wpUnited->get_wp_home_url(),
			'WordPress Base URL'		=>	($s) ? $this->sanitise($wpUnited->get_wp_base_url()) : $wpUnited->get_wp_base_url(),
			'WordPress Plugin URL'		=>	($s) ? $this->sanitise($wpUnited->get_plugin_url()) : $wpUnited->get_plugin_url(),			
			'phpBB URL'					=>	($wpUnited->is_enabled() && $phpbbForum->is_phpbb_loaded())? (($s) ? $this->sanitise($phpbbForum->get_board_url()) : $phpbbForum->get_board_url()): 'Unknown',			
			'Plugin Path'				=>	($s) ? $this->sanitise($wpUnited->get_plugin_path()) : $wpUnited->get_plugin_path(),
			'WordPress Path'			=>	($s) ? $this->sanitise($wpUnited->get_wp_path()) : $wpUnited->get_wp_path(),
			'phpBB Path'				=>	($s) ? $this->sanitise($wpUnited->get_setting('phpbb_path')) : $wpUnited->get_setting('phpbb_path'),
			'Active plugins'			=>  implode(', ', get_option('active_plugins')),
			'Current theme'				=>	wp_get_theme(),
			'Allocated memory'			=>	(memory_get_usage()/(1024^2)) . 'M',
			'Memory limit'				=>	($memLimit == '-1') ? 'Unlimited' : $memLimit,
			'Locale'					=>  $locale
		); 
	
		$settings = array_merge($mainEntries, $settings);
		$result  = '';
		
		foreach($settings as $setting => $value) {
			if($setting != 'phpbb_path') {
				$result .= '[b]<strong>' . $setting . '</strong>[/b]' . str_repeat('&nbsp;', 25 - strlen($setting)) . ':' . str_repeat('&nbsp;', 5) . $value . '<br />';
			}
		}
		return $result;
		
	}
	
	private function sanitise($pathOrUrl) {
	
		$san = array(
			'san', 'it', 'ised', 'all', 'ele', 'ments', 
			'are', 'hidden', 'for', 'safety', 'some', 
			'thing', 'here', 'there', 'this', 'that',
			'abc', 'def', 'ghi', 'jkl', 'mno', 'pqr', 
			'stu', 'vwx', 'yz'
		);
		
		$ignores = array(
			'http:', 'https:', 'localhost', 'localdomain', 'com', 'net', 'org', 'www', '127', '0', '1', 'php'
		);
		
		$toSanA = explode('\\', $pathOrUrl);
		$result = array();
		
		foreach($toSanA as $sanA) {
			$toSanB = explode('/', $sanA);
			$resultB = array();
			foreach($toSanB as $sanB) {
				$toSan = explode('.', $sanB);
				$innerResult = array();
				foreach($toSan as $item) {
					if(!strlen($item) || (in_array(strtolower($item), $ignores))) {
						$innerResult[] = $item;
						continue;
					}
					$alreadyUsed = array_keys($this->sanitisedParts);
					if(!in_array($item, $alreadyUsed)) {
						$newSanIndex = sizeof($alreadyUsed);
						$suffix = '';
						$index = $newSanIndex;
						if ($newSanIndex > (sizeof($san) -1)) {
							$suffix = (int)($newSanIndex / (sizeof($san) -1));
							$index = $newSanIndex - ($suffix * (sizeof($san) -1));
						}
						$this->sanitisedParts[$item] =  $san[$index] . $suffix;
					}
					$innerResult[] = $this->sanitisedParts[$item];
						
				}
				$resultB[] = implode('.', $innerResult);
			}
			
			$result[] = implode('/', $resultB);
		
		}
		
		$result = implode('\\', $result);
		
		return $result;
	
	}
}

// There really should be more here. But there isn't. Yet.