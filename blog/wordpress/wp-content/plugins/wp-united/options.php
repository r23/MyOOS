<?php
/** 
*
* WP-United Extra Options
*
* @package WP-United
* @version $Id: 0.9.2.0  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
*	You can change the options in this file -- they alter the way WP-United behaves.
*/


/**
 * This seciton is for security. Do not modify this part:
 * @ignore
 */
if ( !defined('IN_PHPBB') && !defined('ABSPATH') ) exit;

//The options you can set begin below:

/**
 * (TEMPORARILY) DISABLE WP-UNITED?
 * This is useful if you have locked yourself out of your forum -- for example, if you have deleted 
 * your WordPress plugin, but have template integration on, you might not be able to see
 * your forum.
 * 
 * Temporarily change this to TRUE to completely disable the integration so that you can log in and
 * get to the ACP. 
 * 
 * To permanently remove WP-United do not use this: disconnect it in the settings panel, 
 * and then use the WordPress plugins panel to uninstall.
 */
define('WPU_DISABLE', FALSE);


/**
 * USE TEMPLATE CACHE?
 * The template cache is only used when you use the 'phpBB inside WordPress' template integraqtion in 'simple' mode. 
 * It SIGNIFICANTLY improves page generation time, as WordPress no longer needs to be invoked on phpBB pages just to get a header and footer.
 * However, if you have dynamic elements in your header or footer, then you will want to keep this option off.
 * To turn it on, change FALSE to TRUE.
 */
define('WPU_CACHE_ENABLED', TRUE);



/**
 * USE WORDPRESS CORE CACHE?
 * When invoking WordPress, WP-United reads the WordPress core code and makes some minor changes to ensure compatibility.
 * With this option turned on, this changed core code is cached to reduce processor and memory load (generation time isn't affected much).
 * There should be no reason to turn this off, since this core code should never change, and does not need to be prepared each time. 
 * If you are receiving unknown PHP errors and think this might be the cause, you can turn it off to aid in debugging.
 */
define('WPU_CORE_CACHE_ENABLED', TRUE);


/**
 * ENABLE LOGIN INTEGRATION DEBUG MODE?
 * Enabling the below option displays debug information that could be useful in tracking down problems with integrated logins
 * It should be left off on a production site!
 */
define('WPU_DEBUG', FALSE);

/**
 * OVERRIDE WORDPRESS SITE COOKIE PATH?
 * This sets the WordPress cookie path to '/'.
 * Could be useful if your WordPress base install is in a path that is rewritten by Apache mod_rewrite, but most users will be fine if they leave this off.
 */
define('WP_ROOT_COOKIE', FALSE);


/**
 * SHOW PAGE STATISTICS?
 * Turn this option on to see the WP-United execution time and memory footprint.
 * WP-United execution time is the time spent by WP-United doing integration, and includes
 * WordPress run time, but not necessarily PHP run time.
 * This is a good way to gauge how various options affect server load.
 * It should be left OFF on production servers.
 */
define('WPU_SHOW_STATS', FALSE);

/**
 * Disable wordpress header & footer on the following pages
 * For some mods, such as shoutboxes, we don't want the WordPress header & footer to show
 * Add the names of their templates to the list here to force that page to be unintegrated
 */
$GLOBALS['WPU_NOT_INTEGRATED_TPLS'] =  array('posting_smilies.html', 'tag_board.html', 'tag_board_edit.html', 'tag_board_bbcodes.html', 'tag_board_layout.html', 'tag_board_smilies.html', 'tag_board_palette.html', 'chat_body.html', 'mchat_body.html', 'abbcode.html', 'posting_abbcode_buttons.html', 'posting_abbcode_wizards.html', 'jquery_base/quickedit.html', 'jquery_base/quickreply.html', 'jquery_base/login.html');


/**
 *  phpBB CSS?
 * These options control whether phpBB CSS is displayed, and whether it comes before
 * or after WordPress CSS.
 * Unless you specifically want to disable all phpBB styles, or change the order, leave these at their
 * default settings
 * If you change these, you my need to purge the cache for them to take effect properly.
 */
define('DISABLE_PHPBB_CSS', FALSE);
define('PHPBB_CSS_FIRST', FALSE);

/**
 * Show blog link
 * This is a quick way to remove the blog link from the top of all your phpBB styles. If you want to temporarily hide it,
 * Set this to false.
 */
define('SHOW_BLOG_LINK', TRUE);

/**
 * Show tags & categories in crossed-posts?
 * Set this to false to suppress the display of tags & categories in blog posts cross-posted to the forum
 */
define('WPU_SHOW_TAGCATS', TRUE);

/**
 * WordPress-in-phpBB use default style only?
 * Set this to true to stick to the board default style on WordPress-in-phpBB pages.
 */
define('WPU_INTEG_DEFAULT_STYLE', FALSE);


// end of file