<?php
/** 
*
* WP-United CSS Magic style call backend
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
*/

/**
* @ignore
*/
define('IN_PHPBB', true);
define('WPU_STYLE_FIXER', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);


// Process the inbound args -- request_var is not available yet
if(!isset($_GET['usecssm'])) exit;
if(!isset($_GET['style'])) exit;

/**
 * We load in a simplified skeleton phpBB, based on the code in style.php
 * We just need enough to get $config filled so we can get our cache salt and unencrypt the passed strings.
 * @TODO: move to phpBB abstraction layer $phpbbForum->load_simple(); ?
 */

// Report all errors, except notices
error_reporting(E_ALL ^ E_NOTICE);
require($phpbb_root_path . 'config.' . $phpEx);

if (!defined('PHPBB_INSTALLED') || empty($dbms) || empty($acm_type)) {
	exit;
}

if (version_compare(PHP_VERSION, '6.0.0-dev', '<')) {
	@set_magic_quotes_runtime(0);
}

// Include files
require($phpbb_root_path . 'includes/acm/acm_' . $acm_type . '.' . $phpEx);
require($phpbb_root_path . 'includes/cache.' . $phpEx);
require($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);
require($phpbb_root_path . 'includes/constants.' . $phpEx);
require($phpbb_root_path . 'includes/functions.' . $phpEx);


$db = new $sql_db();
$cache = new cache();

// Connect to DB
if (!@$db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, false)) {
	exit;
}
unset($dbpasswd);

$config = $cache->obtain_config();
$user = false;


/**
 * Initialise variables
 */
$pos = (request_var('pos', 'outer') 	== 'inner') 		? 'inner' 		: 'outer';
$pkg = (request_var('pkg', 'wp') 		== 'phpbb') 	? 'phpbb' 	: 'wp';
$islandBlock = (request_var('island', 0) == 1);

$cssFileToFix = request_var('style', 0);

$useTV = -1;
if(isset($_GET['tv']) && $pos == 'inner') { 
	$useTV = request_var('tv', -1);
}

// require file late, so it doesn't load beore phpBB $config etc

require($phpbb_root_path . 'includes/hooks/hook_wp-united.' . $phpEx);

if(!isset($wpUnited) || !$wpUnited->get_plugin_path() || !file_exists($wpUnited->get_plugin_path()) || !$wpUnited->is_enabled()) {
	die('not setup properly');
}

// We load the bare minimum to get our data
require($wpUnited->get_plugin_path() . 'functions-css-magic.php');


$wpuCache = WPU_Cache::getInstance();

$cssFileToFix = $wpUnited->get_style_key($cssFileToFix);

/*
 * Some rudimentary additional security
 */
$cssFileToFix = str_replace("http:", "", $cssFileToFix);
$cssFileToFix = str_replace("//", "", $cssFileToFix);
$cssFileToFix = str_replace("@", "", $cssFileToFix);
$cssFileToFix = str_replace(".php", "", $cssFileToFix);

/**
 * Some stylesheets to ignore -- not uccrently used. Add terms here to prevent CSS Magic processing
 */
$ignoreMe = false;
$ignores = array(
	// none currently -- add search terms here to prevent them from being modified by CSS Magic
);
foreach($ignores as $ignore) {
	if(stristr($cssFileToFix, $ignore)) {
		$ignoreMe = true;
		break;
	}
}


$tvFailed = false;
if(file_exists($cssFileToFix) && !$ignoreMe) {
	
	$baseName = basename($cssFileToFix);

	/**
	 * First check cache
	 */
	if($useTV > -1) {
		// template voodoo-modified CSS already cached?
		if($cacheLocation = $wpuCache->get_css_magic($cssFileToFix, $pos, $useTV, $islandBlock)) {
			$css = @file_get_contents($cacheLocation);
		}
	} else {
		// Try loading CSS-magic-only CSS from cache
		if($cacheLocation = $wpuCache->get_css_magic($cssFileToFix, $pos, -1, $islandBlock)) {
			$css = @file_get_contents($cacheLocation);
		}
	}
	
	// Load and CSS-Magic-ify the CSS file. If an outer file, just cache it
	if(empty($css)) {
		require($wpUnited->get_plugin_path() . 'css-magic.php');
		
		if($pkg == 'phpbb') {
			$packagePath = $wpUnited->get_setting('phpbb_path');
			$packageUrl = $phpbbForum->get_board_url();
		} else {
			$packagePath = $wpUnited->get_wp_path();
			$packageUrl = $wpUnited->get_wp_base_url();
		}
		$processImports = ($useTV == -1);
		
		$cssMagic = new CSS_Magic($processImports, $packageUrl, $packagePath);
		if($cssMagic->parseFile($cssFileToFix)) {
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
			
			$cssMagic->fix_urls();
			

			$desc= ($pos == 'inner') ? 'modified to make it more specific' : 'parsed and cached so the style fixer can read it';
			$now = date("F j, Y, g:i a");
			$preHeader = <<<COUT
/**
	This CSS Stylesheet has been parsed with WP-United. The source is $baseName.
	----------------------------------------------------------------------------
	The CSS in this file has been $desc.
	You should refer to the original CSS files for the underlying style rules.
	Purge the phpBB cache to re-generate this CSS.	
	Date/Time generated: $now
	
	WP-United (c) John Wells, licensed under the GNU GPL v2. Underlying CSS copyright not affected.
**/	


COUT;

			
			
			$css = $preHeader . $cssMagic->getCSS();
			$cssMagic->clear();

			
		}
			
		//cache fixed CSS
		if(!$tvFailed) {
			$wpuCache->save_css_magic($css, $cssFileToFix, $pos, $useTV, $islandBlock);
		}
	}
} else if($ignoreMe) {
	$css = file_get_contents($cssFileToFix);
}

if(!empty($css)) {
	$expire_time = 7*86400;
	header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + $expire_time));
	header('Content-type: text/css; charset=UTF-8');

	echo $css;

}

if (!empty($cache)) {
	$cache->unload();
}
$db->sql_close();

// end of file