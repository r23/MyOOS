<?php

/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
* 
* Runs WordPress in phpBB
*/

/**
 */
if ( !defined('ABSPATH') && !defined('IN_PHPBB') ) {
	exit;
}

//Initialise the cache
require_once($wpUnited->get_plugin_path() . 'cache.php'); //@TODO: INIT THIS IN WP-UNITED CLASS
$wpuCache = WPU_Cache::getInstance();


// When PermaLinks are turned on, a trailing slash is added to the blog.php. Some templates also have trailing slashes hard-coded.
// This results in a single slash in PATH_INFO, which screws up WP_Query.
if ( isset($_SERVER['PATH_INFO']) ) {
	$_SERVER['PATH_INFO'] = ( $_SERVER['PATH_INFO'] == "/" ) ? '' : $_SERVER['PATH_INFO'];
}

// some WP pages don't want a phpBB header & footer (e.g. feeds). TODO: improve this check and move from wpUtdInt into a filter.
$wpuNoHead = false;


/**
 *  Run WordPress
 *  If this is phpBB-in-wordpress, we just need to get WordPress header & footer, and store them as "outer content"
 *  if a valid WordPress template cache is available, we just do that and don't need to run WordPress at all.
 */

if ( !$wpuCache->use_template_cache()) { 

	require_once($wpUnited->get_plugin_path() . 'core-patcher.php');
	$wpUtdInt = WPU_Core_Patcher::getInstance();

	//We really want WordPress to run in the global scope. So, our integration class really just prepares
	// a whole set of code to run, and passes it back to us for us to eval.
	if ($wpUtdInt->can_connect_to_wp()) {
		// This generates the code for all the preparatory steps -- cleans up the scope, and 
		// analyses and modifies WordPress core files as appropriate
		$wpUtdInt->enter_wp_integration();
		
		$phpbbForum->background();
		eval($wpUtdInt->exec()); 
		$phpbbForum->foreground();

		$wpUnited->set_ran_patched_wordpress();
	}
	
}

//Run deferred load from patched core;
function wpu_initialise_wp() {
	global $wpUnited;
	
	static $initialised = false;
	
	if(!$initialised && function_exists('wpu_deferred_wp_load')) {
		$initialised = true;
		
		add_filter('request', array($wpUnited, 'alter_query_for_template_int'));
		wpu_deferred_wp_load();
			
	}
}



function wpu_wp_template_load() {
	global $phpbbForum, $wpUtdInt, $wpUnited;
	
	$phpbbForum->background();

	// get the page
	ob_start();
	
	$oldGET = $_GET; $_GET = array();
	
	wpu_initialise_wp();
	
	// items usually set by wordpress template loads:
	define("WP_USE_THEMES", true);
	global $wp_did_header; $wp_did_header = true;
	
	wp();
	
	if (!$wpUnited->should_do_action('template-p-in-w')) {
		$wpUtdInt->load_template();
	}
	$_GET = $oldGET;
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}







// WordPress ran inside phpBB, or we pulled a header/footer from the cache
// this was either to integrate templates, or to perform actions.
function wpu_get_wordpress() {
	global $wpUnited, $phpbbForum, $wpUtdInt, $wp_version;
	
	$wpuCache = WPU_Cache::getInstance();

	// Initialise the loaded WP
	if($wpUnited->ran_patched_wordpress()) { // Wordpress ran inside phpBB
		$wpUnited->set_wp_content(wpu_wp_template_load());
	}	
	



	/**
	 * Generate the WP header/footer for phpBB-in-WordPress
	 */
	if ($wpUnited->should_do_action('template-p-in-w')) {
	
		define('PHPBB_CONTENT_ONLY', TRUE);
		

		if ($wpUnited->get_setting('wpSimpleHdr')) {
			//
			//	Simple header and footer
			//
			if ( !$wpuCache->use_template_cache() ) {
				//
				// Need to rebuld the cache
				//
				
				// some theme/plugin options and workarounds for reverse integration
				// inove -- no sidebar on simple page
				global $inove_sidebar; $inove_sidebar = true;
				
				// Disable the admin bar on cached/simple phpBB-in-WordPress:
				add_filter('show_admin_bar', '__return_false');
		
				ob_start();
			
				get_header();
				$wpUnited->set_outer_content(ob_get_contents());
				ob_end_clean();
		
	
				$wpUnited->set_outer_content($wpUnited->get_outer_content() . '<!--[**INNER_CONTENT**]-->');
				if ( $wpuCache->template_cache_enabled() ) {
					$wpUnited->set_outer_content($wpUnited->get_outer_content() . '<!--cached-->');
				}				
				
				ob_start();
				get_footer();
				$wpUnited->set_outer_content($wpUnited->get_outer_content() . ob_get_contents());
				ob_end_clean();
				
				if ( $wpuCache->template_cache_enabled() ) {
					$wpuCache->save_to_template_cache($wp_version, $wpUnited->get_outer_content());
				}
				
			} else { 
				//
				// Just pull the header and footer from the cache
				//
				$wpUnited->set_outer_content($wpuCache->get_from_template_cache());

			}
		} else {
			//
			//	Full WP page
			//
			

			ob_start();
			
			
			// Fall back to page.php, then index.php (for the old Classic template)
			// Locate_template prefers child themes.
			// The second parameter includes the found file.
			locate_template(
				array(
					$wpUnited->get_setting('wpPageName'),
					'page.php',
					'index.php'
				), true
			);
			
			$wpUnited->set_outer_content(ob_get_contents());
			ob_end_clean();

		}
		
		
		// clean up, go back to normal :-)
		if ( !$wpuCache->use_template_cache() ) {
			$wpUtdInt->exit_wp_integration();
			$wpUtdInt = null; unset ($wpUtdInt);
		}

	}
	
}

/**
 * Work-around for plugins that force exit.
 * Some plugins include an exit() command after outputting content.
 *
 *  In the Integration Class, we can try to detect these, and insert a wpu_complete()
 * prior to the exit(). 
 * 
 * This function tries to complete the remaining tasks as best possile so that
 * WordPress still appears inside the phpBB header/footer in these circumstances.
 */
function wpu_complete() {
	global $wpUnited, $wpUtdInt;
	
	$wpuCache = WPU_Cache::getInstance();
	
	$wpUnited->set_wp_content(ob_get_contents());
	ob_end_clean();
	// clean up, go back to normal :-)
	if ( !$wpuCache->use_template_cache() ) {
		$wpUtdInt->exit_wp_integration();
		$wpUtdInt = null; unset ($wpUtdInt);
	}

	require($wpUnited->get_plugin_path() .'template-integrator.php');
}



?>
