<?php
/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* phpBB abstraction layer
* When in WordPress, we often want to switch between phpBB & WordPress functions
* By accessing through this class, it ensures that things are done cleanly.
* 
*/

/**
 */
if ( !defined('ABSPATH') && !defined('IN_PHPBB') ) exit;

/**
 * phpBB abstraction class -- a neat way to access phpBB from WordPress
 * 
 */
class WPU_Phpbb extends WPU_Context_Switcher {


	private 
		$_savedID,
		$_savedIP,
		$_savedAuth,
		$tokens,
		$_loaded,
		$url;
	
	public 
		$seo,
		$_transitioned_user;		
	
	/**
	 * Class initialisation
	 */
	public function __construct() {
	
		parent::__construct();
	
		$this->_loaded = false;
		if(defined('IN_PHPBB')) { 
			$this->_loaded = true;
		}
		$this->tokens = array();
		$this->seo = false;
		$this->url = '';
		
		$this->_transitioned_user = false;
		$this->_savedID = -1;
		$this->_savedIP = '';
		$this->_savedAuth = NULL;
		
	}	
	
	public function is_phpbb_loaded() {
		return $this->_loaded;
	}
	
	public function can_connect_to_phpbb() {
		global $wpUnited;
		
		$rootPath = $wpUnited->get_setting('phpbb_path');
		
		if(!$rootPath) {
			return false;
		}
		
		static $canConnect = false;
		static $triedToConnect = false;
		
		if($triedToConnect) {
			return $canConnect;
		}
		
		 $canConnect = @file_exists($rootPath);
		 $triedToConnect = true;
		 
		 
		 return $canConnect;
		
	}

	/**
	 * Loads the phpBB environment if it is not already
	 */
	public function load() {
		global $phpbb_hook, $phpbb_root_path, $phpEx, $IN_WORDPRESS, $db, $table_prefix, $wp_table_prefix, $wpUnited;
		global $dbms, $auth, $user, $cache, $cache_old, $user_old, $config, $template, $dbname, $SID, $_SID;

		if($this->is_phpbb_loaded()) {
			return;
		}
		$this->_loaded = true;

		$this->backup_wp_conflicts();

		if ( !defined('IN_PHPBB') ) {
			$phpEx = substr(strrchr(__FILE__, '.'), 1);
			define('IN_PHPBB', true);
		}

		$phpbb_root_path = $wpUnited->get_setting('phpbb_path');
		$phpEx = substr(strrchr(__FILE__, '.'), 1);

		$this->make_phpbb_env();

		if(!$this->can_connect_to_phpbb()) {
			$wpUnited->disable_connection('error'); 
			die();
		}
		require_once($phpbb_root_path . 'common.' . $phpEx);

		// various tests for success:
		if(!isset($user)) {
			$wpUnited->disable_connection('error');
		}

		if(!is_object($user)) {
			$wpUnited->disable_connection('error');
		}

		// phpBB's deregister_globals is unsetting $template if it is also set as a WP post var
		// so we just set it global here
		$GLOBALS['template'] = &$template;

		$user->session_begin();
		$auth->acl($user->data);

		if(!is_admin()) {
			if ($config['board_disable'] && !defined('IN_LOGIN') && !$auth->acl_gets('a_', 'm_') && !$auth->acl_getf_global('m_')) {
				// board is disabled. 
				$user->add_lang('common');
				define('WPU_BOARD_DISABLED', (!empty($config['board_disable_msg'])) ? '<strong>' . $user->lang['BOARD_DISABLED'] . '</strong><br /><br />' . $config['board_disable_msg'] : $user->lang['BOARD_DISABLE']);
			} else {
				if(($wpUnited->get_setting('showHdrFtr') == 'FWD') && (defined('WPU_INTEG_DEFAULT_STYLE') && WPU_INTEG_DEFAULT_STYLE)) {
					// This option forces the default phpBB style in a forward integration
					$user->setup('mods/wp-united', $config['default_style']);
				} else {
					$user->setup('mods/wp-united');
				}
			}
		} else {	
			$user->setup('mods/wp-united');
		}
		
		if(defined('WPU_BLOG_PAGE') && !defined('WPU_HOOK_ACTIVE')) {
			$cache->purge();
			trigger_error(__('The WP-United phpBB hook file, hook_wp-united.php, was not loaded. Either it is missing, or you need to clear the phpBB cache. <br /><br />Attempted to automatically clear the phpBB cache. Try <a href="#" onclick="document.location.reload(); return false;">refreshing the page</a> to see if it worked. <br /><br />If this error persists, check that includes/hooks/hook_wp-united.php exists, and try manually purging your phpBB cache.', 'wp-united'), E_USER_ERROR);
		}
		
		
		//fix phpBB SEO mod
		global $phpbb_seo;
		if (empty($phpbb_seo) ) {
			if(@file_exists($phpbb_root_path . 'phpbb_seo/phpbb_seo_class.'.$phpEx)) {
				require_once($phpbb_root_path . 'phpbb_seo/phpbb_seo_class.'.$phpEx);
				$phpbb_seo = new phpbb_seo();
				$this->seo = true;
			}
		}

		$this->lang = $GLOBALS['user']->lang;

		$this->backup_phpbb_state();
		$this->switch_to_wp_db();
		$this->restore_wp_conflicts();
		$this->make_wp_env();
	}
	
	// try to guess what kind of phpBB template this user's style is based on
	
	public function guess_style_type() {
		global $user;
		
		static $useTemplate = false;
		
		if(!empty($useTemplate)) {
			return $useTemplate;
		}
		
		$fStateChanged = $this->foreground();
		
		if(stristr($user->theme['theme_path'], 'pro') !== false) {
			$useTemplate = 'prosilver';
		} else if(stristr($user->theme['theme_path'], 'sub') !== false) {
			$useTemplate = 'subsilver2';
		} elseif(!@file_exists($wpUnited->get_setting['phpbb_path'] . 'styles/' . $user->theme['theme_path'] . '/theme/styleswitcher.js')) {
			$useTemplate = 'subsilver2';
		}
		
		return $useTemplate;
	
	
	}
	
	/**
	 * Return a URI that will use the style-fixer to return an island stylesheet
	 */
	public function get_island_stylesheet() {
		global $user, $phpEx, $wpUnited, $phpbbForum;

		$fStateChanged = $this->foreground();

		if(!$user->theme['theme_storedb']) {
			$styleSheet = "{$wpUnited->get_setting('phpbb_path')}styles/" . rawurlencode($user->theme['theme_path']) . '/theme/stylesheet.css';
			$modStyleSheet = $phpbbForum->get_board_url() . 'wp-united/style-fixer.php?usecssm=1&amp;island=1&amp;style=';
		} else {
			$styleSheet = "{$phpbbForum->get_board_url()}style.$phpEx" .  '?id=' . $user->theme['style_id'] . '&amp;lang=' . $user->lang_name;
			$modStyleSheet = $styleSheet . '&amp;island=1&amp;usecssm=1&amp;cloc=';
		}

		$wpuCache = WPU_Cache::getInstance();
		$cacheName = $wpuCache->issue_style_key($styleSheet, 'inner');
		$wpUnited->commit_style_keys();
		
		$modStyleSheet = $modStyleSheet . $cacheName;

		$this->restore_state($fStateChanged);
		
		return $modStyleSheet;
		
	}
	
	public function get_theme_path() {
		global $user;
		
		$fStateChanged = $this->foreground();
		$result = $this->get_board_url() . 'styles/' . rawurlencode($user->theme['template_path']) . '/theme/';
		$this->restore_state($fStateChanged);
		
		return $result;
	}
	
	//TODO: UNNEEDED?
	public function get_super_template_path() {
		global $user;
		
		$fStateChanged = $this->foreground();
		$result = $this->get_board_url() . '/styles/' . rawurlencode((isset($user->theme['template_inherit_path']) && $user->theme['template_inherit_path']) ? $user->theme['template_inherit_path'] : $user->theme['template_path']) . '/template/';
		$this->restore_state($fStateChanged);
		
		return $result;
	}
	
	
	
	public function append_sid($url) {
		global $user;
		
		$fStateChanged = $this->foreground();
		$result = append_sid($url, false, true, $user->session_id);
		$this->restore_state($fStateChanged);
		
		return $result;
	}
	
	public function get_board_url() {
		global $config;
		
		if(empty($this->url)) {
			$fStateChanged = $this->foreground();
			$config['force_server_vars'] = 1;
			$this->url = add_trailing_slash(generate_board_url());
			$this->restore_state($fStateChanged);
		}
		
		return $this->url;
	}
	

	
	/**
	 * Passes content through the phpBB word censor
	 */
	public function censor($content) { 

		if(!$this->is_phpbb_loaded()) {
			return $content;
		}
		$fStateChanged = $this->foreground();
		$content = censor_text($content);
		$this->restore_state($fStateChanged);
		return $content;
	}
	
	/**
	 * Returns if the current user is logged in
	 */
	public function user_logged_in() {
		return (empty($this->get_phpbb_user_object()->data['is_registered'])) ? false : true;		
	}
	
	/**
	 * Returns the ACP link if the user should be able to see it
	 */
	public function get_acp_url() {
		global $wpUnited, $auth, $user, $phpEx;
		static $acpLink = false;
		
		if(!$wpUnited->is_working()) {
			return '';
		}
		
		if($acpLink !== false) {
			return $acpLink;
		}
		
		$acpLink = '';
		
		$fStateChanged = $this->foreground();
		if($auth->acl_get('a_') && !empty($user->data['is_registered'])) {
			$acpLink = $this->append_sid($this->get_board_url() . "adm/index.$phpEx");
		}
		$this->restore_state($fStateChanged);
		
		return $acpLink;
		
	}
	
	/**
	 * Returns the currently logged-in user's username
	 */
	public function get_username() {
		$fStateChanged = $this->foreground();
		$result = $GLOBALS['user']->data['username'];
		$this->restore_state($fStateChanged);
		return $result;
	}
	
	/**
	 * Returns a userdata item (or full data array) for a user
	 * Caches the result for the session
	 */
	public function get_userdata($key = '', $userID = false, $refreshCache = false) {
		
		static $userDataCache = array();
		
		$userCacheKey = ($userID === false) ? '[BLANK]' : $userID;
	
		if(!$refreshCache) {
			if(isset($userDataCache[$userCacheKey])) {
				if(empty($key)) {
					return $userDataCache[$userCacheKey];
				} else {
					if(isset($userDataCache[$userCacheKey][$key])) {
						return $userDataCache[$userCacheKey][$key];
					}
				}
			}
		}
		
		
		$fStateChanged = $this->foreground();
		

		if($userID !== false) {
			$result = $this->fetch_userdata_for($userID);
		} else {
			$result = $GLOBALS['user']->data;
		}
		$userDataCache[$userCacheKey] = $result;
		
		$this->restore_state($fStateChanged);
		
		if ( !empty($key) ) {
			if(isset($userDataCache[$userCacheKey][$key])) {
				return $userDataCache[$userCacheKey][$key];
			} else {
				return false;
			}
		} else {
			return $userDataCache[$userCacheKey];
		}

	}
	
	public function reset_userdata_cache($userID = false) {
			$this->get_userdata('', $userID, true);
	}
	
	public function handle_session_msgbox($errNo, $errMsg, $errfile, $errLine) {
		if($errNo !== E_USER_NOTICE) {
			return msg_handler($errNo, $errMsg, $errFile, $errLine);
		}
		wp_logout();
		wp_die($errMsg);
	}
	
	public function create_phpbb_session($userID, $persist = false) {
		global $config, $user;
		
		$fStateChanged = $this->foreground();
		
		set_error_handler(array($this, 'handle_session_msgbox'));

		$user->session_create($userID, false, $persist);
		restore_error_handler();		
		
		$_COOKIE[$config['cookie_name'] . '_sid'] = $user->session_id;
		unset($_COOKIE[$config['cookie_name'] . '_k']);
		$_COOKIE[$config['cookie_name'] . '_u'] = $user->data['user_id'];
		
		// refresh the userdata cache
		$this->reset_userdata_cache();
		
		$success = ($user->data['user_id'] == $userID);
		
		$this->restore_state($fStateChanged);
		
		return $success;
	}	
	
	/**
	 * 	fetch data for a specific user
	 */
	private function fetch_userdata_for($id) {
		global $db;
		
		
	    $sql = 'SELECT * FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int) $id;
    
		$result = $db->sql_query($sql);
		$user_row = @$db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (!$user_row) {
			return false;
		}	
		
		return $user_row;
		 
	}
	
	/**
	 * Returns whether the current user has unread PMs since last visit
	 * Resets the number to 0 if they do...
	 * @return array of text to display, and whether to spawn a popup
	 */
	
	public function get_user_pm_details() {
		global $user, $db;
		
		static $result = false;

		if(is_array($result)) {
			return $result;
		}
		
		$fStateChanged = $this->foreground();
		
		$result = array(
			'new'			=> false,
			'text' 			=> '',
			'unread_text' 	=> '',
			'popup'			=> false
		);
		
		if (!empty($user->data['is_registered'])) {
			if ($user->data['user_new_privmsg']) {
				$msgNew = ($user->data['user_new_privmsg'] == 1) ? $user->lang['NEW_PM'] : $user->lang['NEW_PMS'];
				$result['text'] = sprintf($msgNew, $user->data['user_new_privmsg']);

				if (!$user->data['user_last_privmsg'] || $user->data['user_last_privmsg'] > $user->data['session_last_visit']) {
					$sql = 'UPDATE ' . USERS_TABLE . '
						SET user_last_privmsg = ' . $user->data['session_last_visit'] . '
						WHERE user_id = ' . (int)$user->data['user_id'];
					$db->sql_query($sql);
					$result['new'] = true;
				} 
			} else {
				$result['text'] = $user->lang['NO_NEW_PM'];
				$result['new'] = false;
			}

			if ($user->data['user_unread_privmsg'] && $user->data['user_unread_privmsg'] != $user->data['user_new_privmsg']) {
				$msgUnread = ($user->data['user_unread_privmsg'] == 1) ? $user->lang['UNREAD_PM'] : $user->lang['UNREAD_PMS'];
				$result['unread_text'] = sprintf($msgUnread, $user->data['user_unread_privmsg']);
			}

			$result['popup'] = $user->optionget('popuppm');
		}

		$this->restore_state($fStateChanged);

		return $result;

	}

	public function get_style_cookie_settings() {
		global $config;

		$fStateChanged = $this->foreground();

		$settings = addslashes('; path=' . $config['cookie_path'] . ((!$config['cookie_domain'] || $config['cookie_domain'] == 'localhost' || $config['cookie_domain'] == '127.0.0.1') ? '' : '; domain=' . $config['cookie_domain']) . ((!$config['cookie_secure']) ? '' : '; secure'));

		$this->restore_state($fStateChanged);

		return $settings;
	}

	/**
	 * Returns the user's IP address
	 */
	public function get_userip() {
		$fStateChanged = $this->foreground();
		$result = $GLOBALS['user']->ip;
		$this->restore_state($fStateChanged);
		return $result;
	}

	/**
	 * Returns a statistic
	 */
	public function stats($stat) {
		 return $GLOBALS['config'][$stat];
	}

	/**
	 * Returns rank info for currently logged in, or specified, user.
	 */
	public function get_user_rank_info($userID = '') {
		global $db;
		$fStateChanged = $this->foreground();

		if (!$userID ) {
			if( $this->user_logged_in() ) {
				$usrData = $this->get_userdata();
			}
		} else {
			$sql = 'SELECT user_rank, user_posts 
						FROM ' . USERS_TABLE .
						' WHERE user_wpuint_id = ' . (int)$userID;
				if(!($result = $db->sql_query($sql))) {
					wp_die(__('Could not access the database.', 'wp-united'));
				}
				$usrData = $db->sql_fetchrow($result);
		}
		if( $usrData ) {
				global $phpbb_root_path, $phpEx;
				if (!function_exists('get_user_rank')) {
					require_once($phpbb_root_path . 'includes/functions_display.php');
				}
				$rank = array();
				$rank['text'] = $rank['image_tag'] = $rank['image']  = '';
				get_user_rank($usrData['user_rank'], $usrData['user_posts'], $rank['text'], $rank['image_tag'], $rank['image']);
				$this->restore_state($fStateChanged);
				return $rank;
		}
		$this->restore_state($fStateChanged);
	}
	
	/**
	 * Gets the birthday list
	 */
	public function get_birthday_list() {
		global $config, $auth, $db, $user;
 
		$birthday_list = '';

		$fStateChanged = $this->foreground();
 
		if ($config['load_birthdays'] && $config['allow_birthdays'] && $auth->acl_gets('u_viewprofile', 'a_user', 'a_useradd', 'a_userdel')) {

			$now = phpbb_gmgetdate(time() + $user->timezone + $user->dst);

			// Display birthdays of 29th february on 28th february in non-leap-years
			$leap_year_birthdays = '';
			if ($now['mday'] == 28 && $now['mon'] == 2 && !$user->format_date(time(), 'L')) {
				$leap_year_birthdays = " OR u.user_birthday LIKE '" . $db->sql_escape(sprintf('%2d-%2d-', 29, 2)) . "%'";
			}

			$sql = 'SELECT u.user_id, u.username, u.user_colour, u.user_birthday, u.user_type 
				FROM ' . USERS_TABLE . ' u
				LEFT JOIN ' . BANLIST_TABLE . " b ON (u.user_id = b.ban_userid)
				WHERE (b.ban_id IS NULL
					OR b.ban_exclude = 1)
					AND (u.user_birthday LIKE '" . $db->sql_escape(sprintf('%2d-%2d-', $now['mday'], $now['mon'])) . "%' $leap_year_birthdays)
					AND u.user_type IN (" . USER_NORMAL . ', ' . USER_FOUNDER . ')';

			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result)) {
				$birthday_list .= (($birthday_list != '') ? ', ' : '') . $this->get_username_link($row['user_type'], $row['user_id'], $row['username'], $row['user_colour']);

				if ($age = (int) substr($row['user_birthday'], -4)) {
					$birthday_list .= ' (' . max(0, $now['year'] - $age) . ')';
				}
			}
	
			$db->sql_freeresult($result);
		}
		
		$this->restore_state($fStateChanged);
		
		return $birthday_list;
	 
	 }

	/**
	 * Lifts latest phpBB posts from the DB. 
	 * $forum_list limits to a specific forum (comma delimited list). $limit sets the number of posts fetched. 
	 */
	public function get_recent_posts($forum_list = '', $limit = 50) {
		global $db, $auth, $wpUnited, $user;
		
		$fStateChanged = $this->foreground();

		$forum_list = (empty($forum_list)) ? array() :  explode(',', $forum_list); //forums to explicitly check
		$forums_check = array_unique(array_keys($auth->acl_getf('f_read', true))); //forums authorised to read posts in
		if (sizeof($forum_list)) {
			$forums_check = array_intersect($forums_check, $forum_list);
		}
		if (!sizeof($forums_check)) {
			return FALSE;
		}
			
		$sql = '
		
			SELECT p.post_id, p.topic_id, p.forum_id, p.post_time, t.topic_title, p.post_subject, f.forum_name, p.poster_id, u.username, f.forum_id, u.user_type, u.user_colour 
            FROM ' . POSTS_TABLE . ' AS p, ' . TOPICS_TABLE . ' AS t, ' . FORUMS_TABLE . ' AS f, ' . USERS_TABLE . ' AS u
			WHERE ' . $db->sql_in_set('f.forum_id', $forums_check)  . ' 
				AND  p.topic_id = t.topic_id
				AND u.user_id = p.poster_id
				AND f.forum_id = p.forum_id
			ORDER BY post_time DESC'; 	
			
		if(!($result = $db->sql_query_limit($sql, $limit, 0))) {
			wp_die(__('Could not access the database.', 'wp-united'));
		}		

		$posts = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result)) {
			$posts[$i] = array(
				'post_id' 				=> $row['post_id'],
				'topic_id' 				=> $row['topic_id'],
				'topic_title' 			=> $wpUnited->censor_content($row['topic_title']),
				'post_title' 			=> $wpUnited->censor_content($row['post_subject']),
				'post_time'			=>  $user->format_date($row['post_time']),
				'user_id' 				=> $row['poster_id'],
				'username' 			=> $row['username'],
				'forum_id' 			=> $row['forum_id'],
				'forum_name' 		=> $row['forum_name'],
				'user_type'			=> $row['user_type'],
				'user_colour'		=> $row['user_colour'],
				'user_link'			=> $this->get_username_link($row['user_type'], $row['poster_id'], $row['username'], $row['user_colour'])
			);
			$i++;
		}
		$db->sql_freeresult($result);
		$this->restore_state($fStateChanged);
		return $posts;
	}
	
	/**
	 * Lifts latest phpBB topics from the DB.
	 * $forum_list limits to a specific forum (comma delimited list). $limit sets the number of posts fetched. 
	 */
	public function get_recent_topics($forum_list = '', $limit = 50) {
		global $db, $auth, $wpUnited;
		
		$fStateChanged = $this->foreground();

		$forum_list = (empty($forum_list)) ? array() :  explode(',', $forum_list); //forums to explicitly check
		$forums_check = array_unique(array_keys($auth->acl_getf('f_read', true))); //forums authorised to read posts in
		if (sizeof($forum_list)) {
			$forums_check = array_intersect($forums_check, $forum_list);
		}
		if (!sizeof($forums_check)) {
			return FALSE;
		}
		$sql = '
		
			SELECT t.topic_id, t.topic_time, t.topic_title, u.username, u.user_id,
				t.topic_replies, t.forum_id, t.topic_poster, t.topic_status, f.forum_name, u.user_type, u.user_colour 
			FROM ' . TOPICS_TABLE . ' AS t, ' . USERS_TABLE . ' AS u, ' . FORUMS_TABLE . ' AS f 
			WHERE ' . $db->sql_in_set('f.forum_id', $forums_check)  . ' 
				AND t.topic_poster = u.user_id 
				AND t.forum_id = f.forum_id 
				AND t.topic_status <> 2 
			ORDER BY t.topic_time DESC';
			
		if(!($result = $db->sql_query_limit($sql, $limit, 0))) {
			$this->restore_state($fStateChanged);
			wp_die(__('Could not access the database.', 'wp-united'));
		}		

		$posts = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result)) {
			$posts[$i] = array(
				'topic_id' 			=> $row['topic_id'],
				'topic_replies' 	=> $row['topic_replies'],
				'topic_title' 			=> $wpUnited->censor_content($row['topic_title']),
				'user_id' 				=> $row['user_id'],
				'username' 			=> $row['username'],
				'forum_id' 			=> $row['forum_id'],
				'forum_name' 	=> $row['forum_name'],
				'user_type'			=> $row['user_type'],
				'user_colour'		=> $row['user_colour'],
				'user_link'			=> $this->get_username_link($row['user_type'], $row['user_id'], $row['username'], $row['user_colour'])
			);
			$i++;
		}
		$db->sql_freeresult($result);
		$this->restore_state($fStateChanged);
		return $posts;
	}
	

	
	/**
	 * returns a coloured username link for a phpBB user
	 */
	 public function get_username_link($type, $id, $username, $colour) {
		global $phpbb_root_path;
		
		$fStateChanged = $this->foreground();
		$string = get_username_string(($type <> USER_IGNORE) ? 'full' : 'no_profile', $id, $username, $colour);
		$string = str_replace($phpbb_root_path, $this->get_board_url(), $string);
		$this->restore_state($fStateChanged);
		return $string;
	}
	
	/**
	 * Transitions to/from the currently logged-in user
	 */
	 public function transition_user($toID = false, $toIP = false) {
		 global $auth, $user, $db;
		 
		 $fStateChanged = $this->foreground();
		 
		 if( ($toID === false) && ($this->_transitioned_user == true) ) {
			  // Transition back to the currently logged-in user
			$user->data = $this->_savedData;
			$user->ip = $this->_savedIP;
			$auth = $this->_savedAuth;
			$this->_transitioned_user = false;
		} else if(($toID !== false) && ($toID !== $user->data['user_id'])) {
			// Transition to a new user
			if($this->_transitioned == false) {
				// backup current user
				$this->_savedData= $user->data;
				$this->_savedIP = $user->ip;
				$this->_savedAuth = $auth;
			}
			$sql = 'SELECT *
				FROM ' . USERS_TABLE . '
				WHERE user_id = ' . (int)$toID;

			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			$user->data = array_merge($user->data, $row);
			$user->ip = $toIP;
			$auth->acl($user->data);
			$this->_transitioned_user = true;
		}
		
		$this->restore_state($fStateChanged);
		 
	}
	
	public function get_cookie_domain() {
		global $config;
		
		if(!$this->is_phpbb_loaded()) {
			return false;
		}
		
		$fStateChanged = $this->foreground();
		$cookieDomain = $config['cookie_domain'];
		$this->restore_state($fStateChanged);

		return $cookieDomain;
	}
	
	public function get_cookie_path() {
		global $config;
		
		if(!$this->is_phpbb_loaded()) {
			return false;
		}
		
		$fStateChanged = $this->foreground();
		$cookieDomain = $config['cookie_path'];
		$this->restore_state($fStateChanged);

		return $cookieDomain;
	}

	/**
	* Update group-specific ACL options. Function can grant or remove options. If option already granted it will NOT be updated.
	* Lifted from https://www.phpbb.com/kb/article/permission-system-overview-for-mod-authors-part-two/
	*
	* @param grant|remove $mode defines whether roles are granted to removed
	* @param string $group_name group name to update
	* @param mixed $options auth_options to grant (a auth_option has to be specified)
	* @param ACL_YES|ACL_NO|ACL_NEVER $auth_setting defines the mode acl_options are getting set with
	*
	*/
	public function update_group_permissions($mode = 'grant', $group_name, $options = array(), $auth_setting = ACL_YES) {
		global $db, $auth, $cache;
		
		$fStateChanged = $this->foreground();
		
		//First We Get Role ID
		$sql = "SELECT g.group_id
			FROM " . GROUPS_TABLE . " g
			WHERE group_name = '" . $db->sql_escape($group_name) . "'";
		$result = $db->sql_query($sql);
		$group_id = (int) $db->sql_fetchfield('group_id');
		$db->sql_freeresult($result);

		//Now Lets Get All Current Options For Role
		$group_options = array();
		$sql = "SELECT auth_option_id
			FROM " . ACL_GROUPS_TABLE . "
			WHERE group_id = " . (int)$group_id . "
			GROUP BY auth_option_id";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$group_options[] = $row;
		}
		$db->sql_freeresult($result);

		//Get Option ID Values For Options Granting Or Removing
		$sql = "SELECT auth_option_id
			FROM " . ACL_OPTIONS_TABLE . "
			WHERE " . $db->sql_in_set('auth_option', $options) . "
			GROUP BY auth_option_id";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) {
			$acl_options_ids[] = $row;
		}
		$db->sql_freeresult($result);


		//If Granting Permissions
		if ($mode == 'grant') {
			//Make Sure We Have Option IDs
			if (empty($acl_options_ids)) {
				$this->restore_state($fStateChanged);
				return false;
			}

			//Build SQL Array For Query
			$sql_ary = array();
			for ($i = 0, $count = sizeof($acl_options_ids);$i < $count; $i++) {
				
				//If Option Already Granted To Role Then Skip It
				if (in_array($acl_options_ids[$i]['auth_option_id'], $group_options)) {
					continue;
				}
				$sql_ary[] = array(
					'group_id'        => (int) $group_id,
					'auth_option_id'    => (int) $acl_options_ids[$i]['auth_option_id'],
					'auth_setting'        => $auth_setting,
				);
			}

			$db->sql_multi_insert(ACL_GROUPS_TABLE, $sql_ary);
			$cache->destroy('acl_options');
			$auth->acl_clear_prefetch();
		}

		//If Removing Permissions
		if ($mode == 'remove') {
			//Make Sure We Have Option IDs
			if (empty($acl_options_ids)) {
				$this->restore_state($fStateChanged);
				return false;
			}
			
			//Process Each Option To Remove
			for ($i = 0, $count = sizeof($acl_options_ids);$i < $count; $i++) {
				$sql = "DELETE
					FROM " . ACL_GROUPS_TABLE . "
					WHERE auth_option_id = " . $acl_options_ids[$i]['auth_option_id'];

				$db->sql_query($sql);
			}

			$cache->destroy('acl_options');
			$auth->acl_clear_prefetch();
		}
		
		$this->restore_state($fStateChanged);

		return;
	}
	
	/**
	 * Remove all WP-United permissions from phpBB groups
	 */
	
	public function clear_group_permissions() {
		global $db;
		
		$perms = array_keys(wpu_permissions_list());
		
		$fStateChanged = $this->foreground();
		
		$sql = 'SELECT group_name FROM ' . GROUPS_TABLE;
		$result = $db->sql_query($sql);

		$groups = array();
		while ($row = $db->sql_fetchrow($result)) {
			$groups[] = $row['group_name'];
		}
		$db->sql_freeresult($result);
		
		foreach($groups as $group) {
			$this->update_group_permissions('remove', $group, $perms);
		}
		
		$this->restore_state($fStateChanged);
	}

		
	
	/**
	 * Logs out the current user
	 */
	 public function logout() {
		 global $user;
		 
		 $fStateChanged = $this->foreground();
		 
		 if($user->data['user_id'] != ANONYMOUS) {
			$user->session_kill();
		}
		
		$this->restore_state($fStateChanged);
	}
	
	/**
	 * Returns a list of smilies
	 */
	public function get_smilies() {
		global $db;
		
		if(!$this->is_phpbb_loaded()) {
			return '';
		}
		
		$fStateChanged = $this->foreground();
	
		$result = $db->sql_query('SELECT code, emotion, smiley_url FROM '.SMILIES_TABLE.' GROUP BY emotion ORDER BY smiley_order ', 3600);

		$i = 0;
		$smlOutput =  '<span id="wpusmls">';
		while ($row = $db->sql_fetchrow($result)) {
			if (empty($row['code'])) {
				continue;
			}
			if ($i == 7) {
				$smlOutput .=  '<span id="wpu-smiley-more" style="display:none">';
			}
		
			$smlOutput .= '<a href="#"><img src="'.$this->get_board_url() . 'images/smilies/' . $row['smiley_url'] . '" alt="' . $row['code'] . '" title="' . $row['emotion'] . '" /></a> ';
			$i++;
		}
		$db->sql_freeresult($result);
	
		$this->restore_state($fStateChanged);
	
	
		if($i >= 7) {
			$smlOutput .= '</span>';
			if($i>7) {
				$smlOutput .= '<a id="wpu-smiley-toggle" href="#" onclick="return wpuSmlMore();">' . __('More smilies', 'wp-united') . '&nbsp;&raquo;</a></span>';
			}
		}
		$smlOutput .= '</span>';
	
		return $smlOutput;
		 
	}
	
	public function get_avatar($phpbbId, $width=0, $height=0, $alt='') {
		global $db, $config, $phpbb_root_path, $phpEx;
		
		$fStateChanged = $this->foreground();
		
		require_once($phpbb_root_path . 'includes/functions_display.' . $phpEx);
		
		$sql = 'SELECT user_avatar, user_avatar_type, user_avatar_width, user_avatar_height 
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int)$phpbbId;
		$result = $db->sql_query($sql);
		
		$avatarDetails = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		$width = (empty($width)) ? $avatarDetails['user_avatar_width'] : $width;
		$height = (empty($height)) ? $avatarDetails['user_avatar_height'] : $height;
		
		$phpbbAvatar = get_user_avatar($avatarDetails['user_avatar'], $avatarDetails['user_avatar_type'], $width, $height, $alt);
		
		$this->restore_state($fStateChanged);
		
		
		// convert path to URL for returned avatar HTML
		$phpbbAvatar = str_replace('src="' . $phpbb_root_path, 'src="' . $this->get_board_url(), $phpbbAvatar);

		return $phpbbAvatar;
	}
	
	
	// send the WP avatar to phpBB if the phpBB one is unset
	public function put_avatar($html, $id, $width=90, $height=90) {
		global $db;	
			
		$userItems = $this->convert_avatar_to_phpbb($html, $id, $width, $height);
		return $this->update_userdata($id, $userItems);
		
	}
	
	/**
	 * Update_userdata -- Updates user information for a given user
	 * @param integer $id phpBB user ID
	 * @param array $userItems an associative array of key names and values to update
	 * @return sql update result, false on failure (?)
	 */
	public function update_userdata($id, $userItems) {
		global $db;	
		
		if(!is_array($userItems) || !sizeof($userItems) || empty($id)) {
			return false;
		}

		$fStateChanged = $this->foreground();
		
		$sql = 'UPDATE ' . USERS_TABLE . '
			SET ' . $db->sql_build_array('UPDATE', $userItems) . '
			WHERE user_id = ' . (int)$id;
		$status = $db->sql_query($sql);		
		
		$this->restore_state($fStateChanged);
		
		return $status;
		
	
	}
	
	/**
	 * Convert an avatar into relevant $user array items for phpBB
	 * @return array array of user items or empty array on failure
	 */
	public function convert_avatar_to_phpbb($html, $id, $width=90, $height=90) {
		global $config;	
		
		
		$width = (int)$width;
		$height = (int)$height;
		
		if(($width < 50) || ($height < 50)) { 
			return array();
		}
		
		if(!preg_match('/src\s*=\s*[\'"]([^\'"]+)[\'"]/', $html, $matches)) {
			return array();
		} 
		$avatarUrl = $matches[1];
		
		if(!$avatarUrl) {
			return array();
		}
		
		// we leave a marker for ourselves to show this avatar was put by wpu
		$marker = (strstr($avatarUrl, '?') === false) ? '?wpuput=1' : '&amp;wpuput=1';
		
		$avatarUrl = $avatarUrl . $marker;

		$fStateChanged = $this->foreground();
		
		$userItems = array();
		
		if($config['allow_avatar'] && $config['allow_avatar_remote']) {
		
			// calling avatar_remote uses too many resources, so we put in the images directly to the DB
			
			$width = ($width > $config['avatar_max_width']) ? $config['avatar_max_width'] : $width;
			$height = ($height > $config['avatar_max_height']) ? $config['avatar_max_height'] : $height;
			
			$userItems = array(
				'user_avatar_type' 		=> AVATAR_REMOTE,
				'user_avatar'			=> $avatarUrl,
				'user_avatar_width'		=> $width,
				'user_avatar_height'	=> $height,
			);
					
		}
		
		$this->restore_state($fStateChanged);	
		
		return $userItems;
	}
	
	
	/**
	 * transmits new settings from the WP settings panel to phpBB
	 */
	public function synchronise_settings($dataToStore) {
		global $wpUnited, $cache, $user, $auth, $config, $db, $phpbb_root_path, $phpEx;
		
		
		if(empty($dataToStore)) {
			echo "No settings to process";
			return false;
		}
		
		$fStateChanged = $this->foreground();
				
		$adminLog = array();
		$adminLog[] = __('Receiving settings from WP-United...', 'wp-united');
		
		/**
		 * MySQL will not allow duplicate column names, so we can suppress errors (we still check anyway)
		 */
		 
		$db->sql_return_on_error(true);
		
		if  ( !array_key_exists('user_wpuint_id', $user->data) ) {
			$sql = 'ALTER TABLE ' . USERS_TABLE . ' 
				  ADD user_wpuint_id VARCHAR(10) NULL DEFAULT NULL';

			@$db->sql_query($sql);
			$adminLog[] = __('Modified USERS Table (Integration ID)', 'wp-united');
		}
		
		if  ( !array_key_exists('user_wpublog_id', $user->data) ) {
			$sql = 'ALTER TABLE ' . USERS_TABLE . ' 
				ADD user_wpublog_id VARCHAR(10) NULL DEFAULT NULL';
			@$db->sql_query($sql);
			$adminLog[] = __('Modified USERS Table (Blog ID)', 'wp-united');
		}
		
		//Add an x-posting column to topics
		$sql = 'SELECT * FROM ' . TOPICS_TABLE;
		$result = $db->sql_query_limit($sql, 1);
		$row = (array)$db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (!array_key_exists('topic_wpu_xpost', $row) ) {
			$sql = 'ALTER TABLE ' . TOPICS_TABLE . ' 
				ADD topic_wpu_xpost VARCHAR(10) NULL DEFAULT NULL';

			@$db->sql_query($sql);
			
			// Add new columns
			$sql = 'ALTER TABLE ' . POSTS_TABLE . ' 
				ADD post_wpu_xpost_parent VARCHAR(10) NULL DEFAULT NULL, 
				ADD post_wpu_xpost_meta1 VARCHAR(255) NULL DEFAULT NULL, 
				ADD post_wpu_xpost_meta2 VARCHAR(255) NULL DEFAULT NULL';

			@$db->sql_query($sql);
			
			$adminLog[] = __('Modified TOPICS and POSTS Tables (Cross-Posting ability)', 'wp-united');
			
		}
		
		$db->sql_return_on_error(false);

		$adminLog[] = __('Adding WP-United Permissions', 'wp-united');
		
		// Setup $auth_admin class so we can add permission options
		include_once($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
		$auth_admin = new auth_admin();
		
		// Add permissions
		$auth_admin->acl_add_option(array(
			'local'      => array('f_wpu_xpost', 'f_wpu_xpost_comment'),
			'global'   => array('u_wpu_subscriber', 'u_wpu_contributor','u_wpu_author','m_wpu_editor','a_wpu_administrator')
		));

		$adminLog[] = __('Storing the new WP-United settings', 'wp-united');
		
		// this stores the passed-in settings object, which is a bit brittle
		// TODO: ask $wpUnited->settings to store/reload itself, without making it public
		$this->clear_settings();
		$sql = array();
		$sql[] = array(
			'config_name'	=>	'wpu_location',
			'config_value'	=>	$wpUnited->get_plugin_path()
		);
		$dataIn = base64_encode(gzcompress(serialize($dataToStore)));
		$currPtr=1;
		$chunkStart = 0;
		while($chunkStart < strlen($dataIn)) {
			$sql[] = array(
				'config_name' 	=> 	"wpu_settings_{$currPtr}",
				'config_value' 	=>	substr($dataIn, $chunkStart, 255)
			);
			$chunkStart = $chunkStart + 255;
			$currPtr++;
		}
		
		$db->sql_multi_insert(CONFIG_TABLE, $sql);
		$cache->destroy('config');
		

		if($wpUnited->get_setting('integrateLogin') && $wpUnited->get_setting('avatarsync')) {
			if(!$config['allow_avatar'] || !$config['allow_avatar_remote']) {
				$adminLog[] = __('Updating avatar settings', 'wp-united');

				$sql = 'UPDATE ' . CONFIG_TABLE . ' 
					SET config_value = 1
					WHERE ' . $db->sql_in_set('config_name', array('allow_avatar', 'allow_avatar_remote'));
				$db->sql_query($sql);

				$cache->destroy('config');
			}
		}

		
		// clear out the WP-United cache on settings change
		$adminLog[] = __('Purging the WP-United cache', 'wp-united');
		
		$wpuCache = WPU_Cache::getInstance();
		$wpuCache->purge();
		
		$adminLog[] = __('Completed successfully', 'wp-united');
		
		// Save the admin log in a nice dropdown in phpBB admin log. 
		// Requires a bit of template hacking using JS since we don't want to have to do a mod edit for new script
		
		// generate unique ID for details ID
		$randSeed = rand(0, 99999);
		$bodyContent = '<div style="display: none; border: 1px solid #cccccc; background-color: #ccccff; padding: 3px;" id="wpulogdets' . $randSeed . '">';
		$bodyContent .= implode("<br />\n\n", $adminLog) . '</div>';
		$ln = "*}<span class='wpulog'><script type=\"text/javascript\">// <![CDATA[
		var d = document;
		function wputgl{$randSeed}() {
			var l = d.getElementById('wpulogdets{$randSeed}');
			var p = d.getElementById('wpulogexp{$randSeed}'); var n = p.firstChild.nodeValue;
			l.style.display = (n == '-') ? 'none' : 'block';
			p.firstChild.nodeValue = (n == '-') ? '+' : '-';
			return false;
		}
		if(typeof wpual == 'undefined') {
			var wpual = window.onload;
			window.onload = function() {
				if (typeof wpual == 'function') wpual();
				try {
					var hs = d.getElementsByClassName('wpulog');
					for(h in hs) {var p = hs[h].parentNode; p.firstChild.nodeValue = ''; p.lastChild.nodeValue = '';}
				} catch(e) {}	  
			};
		}
		// ]]>
		</script>";
		
		$ln .= '<strong><a href="#" onclick="return wputgl' . $randSeed . '();" title="click to see details">' . __('Changed WP-United Settings (click for details)', 'wp-united') . '<span id="wpulogexp' . $randSeed . '">+</span></a></strong>' . $bodyContent . '</span>{*';

		add_log('admin', $ln);
		
		$cache->purge();
		 

		

		$this->restore_state($fStateChanged);
		return true;

	}
	
public function load_style_keys() {
		global $config, $wpuDebug;
		$fStateChanged = $this->foreground();
		
		$key = 1;
		$fullKey = '';
		while(isset( $config["wpu_style_keys_{$key}"])) {
			$fullKey .= $config["wpu_style_keys_{$key}"];
			$key++;
		}
		$this->restore_state($fStateChanged);

		if(!empty($fullKey)) {
			$results = unserialize(base64_decode($fullKey));
			return $results;
		} else {
			return array();
		}
		
	}
	
	public function erase_style_keys()	{
		global $db, $cache;

		$fStateChanged = $this->foreground();
		
		// we used to check if style keys were in $config, but keys 
		// could be committed multiple times per session now
		$sql = 'DELETE FROM ' . CONFIG_TABLE . ' 
			WHERE config_name LIKE \'wpu_style_keys_%\'';
			$db->sql_query($sql);
		
		$cache->destroy('config');
		
		$this->restore_state($fStateChanged);
		
		
	}
	
	/**
	 * Saves updated style keys to the database.
	 * phpBB $config keys can only store 255 bytes of data, so we usually need to store the data
	 * split over several config keys
	  * We want changes to take place as a single transaction to avoid collisions, so we 
	  * access DB directly rather than using set_config
	 * @return int the number of config keys used
	 */ 
	public function commit_style_keys($styleKeys) {
		global $cache, $db;
		
		
		$fStateChanged = $this->foreground();
		
		$this->erase_style_keys();
		$fullLocs = (base64_encode(serialize($styleKeys))); 
		$currPtr=1;
		$chunkStart = 0;
		$sql = array();
		while($chunkStart < strlen($fullLocs)) {
			$sql[] = array(
				'config_name' 	=> 	"wpu_style_keys_{$currPtr}",
				'config_value' 	=>	substr($fullLocs, $chunkStart, 255)
			);
			$chunkStart = $chunkStart + 255;
			$currPtr++;
		}
		
		$db->sql_multi_insert(CONFIG_TABLE, $sql);
		$cache->destroy('config');
		
		$this->restore_state($fStateChanged);
	
		return $currPtr;
	}	
	
	public function clear_settings() {
		global $db;
		
		$fStateChanged = $this->foreground();
		
		$sql = 'DELETE FROM ' . CONFIG_TABLE . "
			WHERE config_name LIKE 'wpu_settings_%' 
			OR config_name LIKE 'wpu_location'";
		$db->sql_query($sql);
		
		$this->restore_state($fStateChanged);
	
	}
	
	// returns the default group for new phpBB users
	public function get_newuser_group() {
		global $config;
		
		$fStateChanged = $this->foreground();
		
		// if 0, they aren't added to the group -- else they are in group until they have this number of posts
		$newMemberGroup = ($config['new_member_post_limit'] != 0);
		
		$this->restore_state($fStateChanged);
		
		return ($newMemberGroup) ? array('REGISTERED', 'NEWLY_REGISTERED') : array('REGISTERED');
	
	}
	
	public function login($username, $password) {
		global $auth;
		
		$authenticated = false;
		
		$fStateChanged = $this->foreground();
		
		$result = $auth->login($username, $password);
		
		if($result['status'] == LOGIN_SUCCESS) {
			if($this->user_logged_in()) {
				$authenticated = $this->get_userdata('user_id');
			}
		}

		$this->restore_state($fStateChanged);
		
		return $authenticated;
		
	}
		

	
	// Update blog link column
	/**
	 * @TODO: this doesn't need to happen every time
	 */
	public function update_blog_link($author) {
		global $db;
		
		if(!$this->is_phpbb_loaded()) {
			return '';
		}
		
		$fStateChanged = $this->foreground();
		
		if ( !empty($author) ) {
			$sql = 'UPDATE ' . USERS_TABLE . ' SET user_wpublog_id = ' . $author . ' WHERE user_wpuint_id = ' . (int)$author;
			if (!$result = $db->sql_query($sql)) {
				return false;
			}
			$db->sql_freeresult($result);
		}
		$this->restore_state($fStateChanged);
		return true;
	}
	

	public function add_smilies($postContent) {
		static $match;
		static $replace;
		static $max = 1000;
		global $db, $config;

		// See if the static arrays have already been filled on an earlier invocation
		if (!is_array($match)) {
		
			$fStateChanged = $this->foreground();
			
			$max = ($config['max_post_smilies'] > 0) ? $config['max_post_smilies'] : $max;
			
			$result = $db->sql_query('SELECT code, emotion, smiley_url FROM '.SMILIES_TABLE.' ORDER BY smiley_order', 3600);

			while ($row = $db->sql_fetchrow($result)) {
				if (empty($row['code'])) {
					continue; 
				} 
				$match[] = '(?<=^|[\n .])' . preg_quote($row['code'], '#') . '(?![^<>]*>)';
				$replace[] = '<!-- s' . $row['code'] . ' --><img src="' . $this->get_board_url() . '/images/smilies/' . $row['smiley_url'] . '" alt="' . $row['code'] . '" title="' . $row['emotion'] . '" /><!-- s' . $row['code'] . ' -->';
			}
			$db->sql_freeresult($result);
			
			$this->restore_state($fStateChanged);
			
		}
		if (sizeof($match)) { 
			$num_matches = preg_match_all('#' . implode('|', $match) . '#', $postContent, $matches);
			unset($matches);
			
			// Make sure the delimiter # is added in front and at the end of every element within $match
			$postContent = trim(preg_replace(explode(chr(0), '#' . implode('#' . chr(0) . '#', $match) . '#'), $replace, $postContent, $max));
		}
		
		return $postContent;
	}	
	
	public function parse_phpbb_text_for_smilies($text) {
		global $phpbb_root_path;
		
		$fStateChanged = $this->foreground();
		
		$parsed = smiley_text($text);
		
		$result = str_replace($phpbb_root_path, $this->get_board_url(), $parsed);
		
		$this->restore_state($fStateChanged);
		
		return $result;
		
	}
	

	/**
	 * Originally by Poyntesm
	 * http://www.phpbb.com/community/viewtopic.php?f=71&t=545415&p=3026305
	 * @access private
	 */
	private function get_role_by_name($name) {
		$fStateChanged = $this->foreground();
		global $db;
		$data = null;

		$sql = "SELECT *
			FROM " . ACL_ROLES_TABLE . "
			WHERE role_name = '" . $db->sql_escape($name) . "'";
		$result = $db->sql_query($sql);
		
		$data = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		$this->restore_state($fStateChanged);
		return $data;
	}
	
	/**
	* Set role-specific ACL options without deleting enter existing options. If option already set it will NOT be updated.
	* 
	* @param int $role_id role id to update (a role_id has to be specified)
	* @param mixed $auth_options auth_options to grant (a auth_option has to be specified)
	* @param ACL_YES|ACL_NO|ACL_NEVER $auth_setting defines the mode acl_options are getting set with
	* @access private
	*
	*/
	private function acl_update_role($role_id, $auth_options, $auth_setting = ACL_YES) {
	   global $db, $cache, $auth;
		$fStateChanged = $this->foreground();
		$acl_options_ids = $this->get_acl_option_ids($auth_options);

		$role_options = array();
		$sql = "SELECT auth_option_id
			FROM " . ACL_ROLES_DATA_TABLE . "
			WHERE role_id = " . (int)$role_id . "
			GROUP BY auth_option_id";
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))	{
			$role_options[] = $row;
		}
		$db->sql_freeresult($result);

		$sql_ary = array();
		foreach($acl_options_ids as $option)	{
			if (!in_array($option, $role_options)) {
				$sql_ary[] = array(
					'role_id'      		=> (int) $role_id,
					'auth_option_id'	=> (int) $option['auth_option_id'],
					'auth_setting'      => $auth_setting
				);	
			}
		}

	   $db->sql_multi_insert(ACL_ROLES_DATA_TABLE, $sql_ary);

	   $cache->destroy('acl_options');
	   $auth->acl_clear_prefetch();
	   $this->restore_state($fStateChanged);
	}

	/**
	* Get ACL option ids
	*
	* @param mixed $auth_options auth_options to grant (a auth_option has to be specified)
	* @access private
	*/
	private function get_acl_option_ids($auth_options) {
		$fStateChanged = $this->foreground();
	   global $db;

	   $data = array();
	   $sql = "SELECT auth_option_id
		  FROM " . ACL_OPTIONS_TABLE . "
		  WHERE " . $db->sql_in_set('auth_option', $auth_options) . "
		  GROUP BY auth_option_id";
	   $result = $db->sql_query($sql);
	   while ($row = $db->sql_fetchrow($result))  {
		  $data[] = $row;
	   }
	   $db->sql_freeresult($result);
		$this->restore_state($fStateChanged);
	   return $data;
	}
	


	
}



// Done. End of file. At last.