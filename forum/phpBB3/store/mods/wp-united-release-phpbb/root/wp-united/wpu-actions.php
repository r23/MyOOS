<?php

/** 
*
* WP-United Mod Edits
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* This is the file for accessing WordPress from inside phpBB pages. Most of this stuff is in flux, meaning that users had to constantly re-mod their phpBB.
* Moving them off into this file is intended to alleviate that. 
*/

/**
 */
if ( !defined('IN_PHPBB') ) {
	exit;
}

/**
 * A set of procedural actions called as "code edits" in phpBB. 
 * by abstracting all the edits into this file, only two-line additions need to be made to phpBB
 * core files
 */
class WPU_Actions {
	/**
	 * logs out of WordPress when the phpBB logout is called
	 */
	public function do_logout() {
		return;
	}
	/**
	 * Updates the WordPress user profile when the phpBB profile is updated
	 */
	public function profile_update($mode, $phpbb_id, $integration_id, $data) {
		return;
	}
	

	
	/**
	 * adds blog links to users' profiles.
	 */
	public function generate_profile_link($bloglink_id, &$template) {
		global $wpUnited, $phpbb_root_path, $phpEx;
		
		if (isset($wpUnited) && $wpUnited->get_setting('buttonsProfile')) {
			if ( !empty($bloglink_id) ) {
				$blog_uri = append_sid($wpUnited->get_wp_home_url() . "?author=" . $bloglink_id);
				$blog_img = '';   //TODO: SET FOR SUBSILVER!!
				$template->assign_vars(array(
					'BLOG_IMG' 		=> $blog_img,
					'U_BLOG_LINK'		=> $blog_uri,
				));
			} else {
				$blog_img = '';
			}
		}
	}
	/**
	 * creates blog links for users' posts
	 * @todo set blog images for subSilver template
	 */
	public function generate_viewtopic_link($bloglink_id, &$cache) { 
		global $wpUnited, $phpbb_root_path, $phpEx;
		if  ( isset($wpUnited) && $wpUnited->is_enabled() ) { 
			if ($wpUnited->get_setting('buttonsPost')) {
				if ((!isset($user_cache[$poster_id])) && !empty($bloglink_id)) {
					if ($poster_id == ANONYMOUS) {
						$cache['blog_img'] = '';
						$cache['blog_link'] = '';
					} else {
						$cache['blog_img'] = '';   //TODO: SET FOR SUBSILVER!!
						$cache['blog_link'] = append_sid($wpUnited->get_wp_home_url() . "?author=" . $bloglink_id);			
					}
				}
			}
		}	
	}
	 /**
	 * adds blog links to users' posts.
	 */
	public function show_viewtopic_link($cache, &$postrow) {
		if (isset($cache['blog_link'])) {
			$postrow['BLOG_IMG'] = $cache['blog_img'];
			$postrow['U_BLOG_LINK'] = $cache['blog_link'];
		}		
	
	}
	
	
	/**
	 * Arbitrate between phpBB & WordPress' make clickable functions
	 */
	public function do_make_clickable($text, $serverUrl = 'init', $class = 'init') {
	
		global $wpUnited, $phpbbForum;
		
		$realServerUrl = ($serverUrl == 'init') ? false : $serverUrl;
		$realClass = ($class == 'init') ? 'postlink' : $class;

		if(!isset($wpUnited) || !is_object($wpUnited) || !$wpUnited->is_working()) {
			return phpbb_make_clickable($text, $realServerUrl, $realClass);
		}
	
		if(!isset($phpbbForum) || !is_object($phpbbForum) || ($phpbbForum->get_state == 'phpbb')) {
			return phpbb_make_clickable($text, $realServerUrl, $realClass);
		}
	
		if($phpbbForum->get_state != 'phpbb') {
			// if additional args are supplied, or the WP function wasn't redefined, they want the phpBB func
			if(($serverUrl != 'init') || ($class != 'init') || !function_exists('wp_make_clickable')) {
				$phpbbForum->foreground();
				return phpbb_make_clickable($text, $realServerUrl, $realClass);
				$phpbbForum->background();
			}
			
			return wp_make_clickable($text);
		}
	}
	
		
	
	
	
	
	
	 /**
	 * CSS Magic actions in style.php.
	 */	
	public function css_magic($cssIn) {
		
		global $phpbb_root_path, $phpEx, $wpUnited, $phpbbForum;
		define('WPU_STYLE_FIXER', true);
		require($phpbb_root_path . 'includes/hooks/hook_wp-united.' . $phpEx);

				
		if(!isset($wpUnited) || !$wpUnited->is_enabled()) {
			return $cssIn; 
		}
		
		require_once($wpUnited->get_plugin_path() . 'functions-css-magic.php');

		$wpuCache = WPU_Cache::getInstance();

		if(!isset($_GET['usecssm'])) {
			return $cssIn;
		}
		$pos = (request_var('pos', 'outer') == 'inner') ? 'inner' : 'outer';
		$islandBlock = (request_var('island', 0) == 1);
		
		$cacheLocation = '';
		
		$tvFailed = false;
		
		$cssIdentifier = request_var('cloc', 0);
		$cssIdentifier = $wpUnited->get_style_key($cssIdentifier);
		
		$useTV = -1;
		if(isset($_GET['tv']) && $pos == 'inner') { 
			$useTV = request_var('tv', -1);
		}

		/**
		 * First check cache
		 */
		$css = '';
		if($useTV > -1) {
			// template voodoo-modified CSS already cached?
			if($cacheLocation = $wpuCache->get_css_magic($cssIdentifier, $pos, $useTV, $islandBlock)) {
				$css = @file_get_contents($cacheLocation);
			}
		} else {
			// Try loading CSS-magic-only CSS from cache
			if($cacheLocation = $wpuCache->get_css_magic($cssIdentifier, $pos, -1, $islandBlock)) {
				$css = @file_get_contents($cacheLocation);
			}
		}
		
		if(!empty($css)) {
			return $css;
		}
		
		// Apply or load css magic
		include($wpUnited->get_plugin_path() . 'css-magic.php');
		
		$packagePath = $wpUnited->get_setting('phpbb_path');
		$packageUrl = $phpbbForum->get_board_url();
		$processImports = !($useTV > -1);
				
		$cssMagic = new CSS_Magic($processImports, $packageUrl, $packagePath);
		
		if(!$cssMagic->parseString($cssIn)) {
			return $cssIn;
		}
		
		// if pos= outer, we just need to cache the CSS so that Template Voodoo can get at it
		
		if($pos=='inner') { 
			// Apply Template Voodoo
			if($useTV > -1) {
				if(!apply_template_voodoo($cssMagic, $useTV)) {
					// set useTV to -1 so that cache name reflects that we weren't able to apply TemplateVoodoo
					$useTV = -1;
					$tvFailed = true;
				}
			}	
			// Apply CSS Magic
			$cssMagic->makeSpecificByIdThenClass('wpucssmagic', false);
		}
		
		if($islandBlock) {
			$cssMagic->makeSpecificByClass('wpuisle2', false);
			$cssMagic->makeSpecificByClass('wpuisle', false);
		}
		
		$desc= ($pos == 'inner') ? 'modified to make it more specific' : 'parsed and cached so the style fixer can read it';
		$now = date("F j, Y, g:i a");
		$preHeader = <<<COUT
/**
	This CSS Stylesheet has been parsed with WP-United. The source is phpBB's style.php.
	--------------------------------------------------------------------------
	The CSS in this file has been $desc.
	You should refer to the original CSS files for the underlying style rules.
	Purge the phpBB cache to re-generate this CSS.
	Date/Time generated: $now
	
	WP-United (c) John Wells, licensed under the GNU GPL v2. Underlying CSS copyright not affected.
**/	


COUT;

		$css = $preHeader . $cssMagic->getCSS();
		$cssMagic->clear();
		
		//cache fixed CSS
		if(!$tvFailed) {
			$wpuCache->save_css_magic($css, $cssIdentifier, $pos, $useTV, $islandBlock);
		}
		
		return $css;
	}
	
	
	/**
	 * Simple call to cache purge. We include it here so that phpBB core edits are static
	 */
	public function purge_cache() {
		global $wpUnited, $phpEx;
		if(isset($wpUnited) && is_object($wpUnited) && $wpUnited->is_enabled()) {
			$wpuCache = WPU_Cache::getInstance();
			$wpuCache->purge();
		}
	}
}

global $wpu_actions;
$wpu_actions = new WPU_Actions;
?>