<?php
/** 
*
* WP-United WordPress template tags
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
* 
* WordPress template functions -- see the readme included in the mod download for how to use these.
*
*/

/**
 */

if ( !defined('IN_PHPBB') && !defined('ABSPATH') ) {
	exit;
}


/**
 * Inserts the commenter's avatar
 * @param bool $default Use default avatars if no avatar is present? Defaults to true
 * @param int $id User ID (optional)
 * @author John Wells
 */
function avatar_commenter($id = '') {
	echo get_avatar_commenter($default, $id);
}

/** 
 * Returns the commenter avatar without displaying it.
 * @param bool $default Use default avatars if no avatar is present? Defaults to true
 * @param int $id User ID (optional)
 * @author John Wells
 */
function get_avatar_commenter($id = '') {
	global $comment, $images, $wpUnited;

	if ( empty($id) ) {
		if ( !empty($comment) ) {
			$id = $comment->user_id;
		} 
	}
	return wpu_avatar_create_image($id);

}



/**
 * Inserts the author's avatar
 * @param bool $default Use default avatars if no avatar is present? Defaults to true
  * @author John Wells
 */
function avatar_poster() {
	echo get_avatar_poster();
}

/** 
 * Returns the author's avatar without displaying it.
 * @param bool $default Use default avatars if no avatar is present? Defaults to true
 * @author John Wells
 */
function get_avatar_poster() {
	global $images, $authordata, $phpbbForum;
	return wpu_avatar_create_image($authordata->ID);
}




/**
 * Inserts the reader's (logged in user's) avatar
 * @param bool $default Use default avatars if no avatar is present? Defaults to true
 * @author John Wells
 */
function avatar_reader() {
	echo get_avatar_reader();
}

/**
 * Returns the reader's avatar without displaying it
 * @param bool $default Use default avatars if no avatar is present? Defaults to true
 * @author John Wells
 */
function get_avatar_reader() {
	global $images, $wpUnited, $userdata, $user_ID;
	get_currentuserinfo();
	return wpu_avatar_create_image($user_ID);
}



/**
 * Generates the avatar image
 * @author John Wells
 * @access private
 */
function wpu_avatar_create_image($userID) {
	global $wpUnited, $phpbbForum, $user_ID, $phpbb_root_path;

	$avatar = '';
	$phpbbUserID = 0;
	
	get_currentuserinfo();
	if($userID == $user_ID) {
		if($phpbbForum->user_logged_in()) {
			$phpbbUserData = $phpbbForum->get_userdata();
			$phpbbUserID = $phpbbUserData['user_id'];
		}
	}
	
	
	if (!$phpbbUserID && !empty($userID) && $wpUnited->get_setting('integrateLogin')) {
		$phpbbUserID = wpu_get_integrated_phpbbuser($userID) ;
	}
	
	if(!$phpbbUserID) {
		$avatar = get_avatar($userID);
	} else {
		$avatar = $phpbbForum->get_avatar($phpbbUserID);
	}
	

	
	if(!empty($avatar)) {
		if(!preg_match('/src\s*=\s*[\'"]([^\'"]+)[\'"]/', $avatar, $matches)) {
			return '';
		} 
		$avatar = $matches[1];
		$avatar = str_replace($phpbb_root_path, $phpbbForum->get_board_url(), $avatar); //stops trailing slashes in URI from killing avatars
		return $avatar;
	}

	return '';
	
}



/**
 * Displays the logged in user's phpBB username, or 'Guest' if they are logged out
 * @author John Wells
 */
function wpu_phpbb_username() {
	echo get_wpu_phpbb_username();
}

/**
 * Returns the phpBB username without displaying it
 * @author John Wells
 */
function get_wpu_phpbb_username() {
	global $phpbbForum;
	$usrName = '';
	if ( $phpbbForum->user_logged_in() ) {
		$usrName = $phpbbForum->get_username();
	} 
	return ($usrName == '') ? $phpbbForum->lang['GUEST'] : $usrName;
}

/**
 * Displays a link to the user's phpBB profile
 * @param int $wpID the WordPress User ID, leave blank for currently logged-in user
 * @author John Wells
 */
function wpu_phpbb_profile_link($wpID = false) {
	echo get_wpu_phpbb_profile_link($wpID);
}

/**
 * Returns a link to the user's phpBB profile without displaying it
 * @param int $wpID the WordPress ID, leave blank for currently logged-in user
 */
function get_wpu_phpbb_profile_link($wpID = false) {
	global $phpbbForum, $wpUnited, $phpEx;
	
	if(!$wpUnited->is_working()) {
		return false;
	}
	
	if($wpID == false) {
		if(!$phpbbForum->user_logged_in()) {
			return false;
		} else {
			$phpbbID = $phpbbForum->get_userdata('user_id');
		}
	} else {
		if(!$wpUnited->get_setting('integrateLogin')) {
			return false;
		} else {
			$phpbbID = wpu_get_integrated_phpbbuser($wpID);
		}
	}
	
	if ($phpbbID) {
		$profile_path = "memberlist.$phpEx";
		return add_trailing_slash($phpbbForum->get_board_url()) . "{$profile_path}?mode=viewprofile&amp;u={$phpbbID}";
	}
	return false;
}

/**
 * Displays the user's phpBB rank
 * @param int $wpID the WordPress ID, leave blank for currently logged-in user
 */
function wpu_phpbb_ranktitle($wpID = '') {
	echo get_wpu_phpbb_ranktitle($wpID);
}

/**
 * Returns the user's phpBB rank without displaying it
 * @param int $wpID the WordPress ID, leave blank for currently logged-in user
 */
function get_wpu_phpbb_ranktitle($wpID = '') {
	$rank = _wpu_get_user_rank_info($wpID);
	if ( $rank ) {
		return $rank['text'];
	}
}

/**
 * Displays the user's phpBB rank image
 * @param int $wpID the WordPress ID, leave blank for currently logged-in user
 * @author John Wells
 */
function wpu_phpbb_rankimage($wpID = '') {
	echo get_wpu_phpbb_rankimage($wpID);
}

/**
 * Returns the user's phpBB rank image without displaying it
 * @param int $wpID the WordPress ID, leave blank for currently logged-in user
 * @author John Wells
 */
function get_wpu_phpbb_rankimage($wpID = '') {
	$rank = _wpu_get_user_rank_info($wpID);
	if ( $rank ) {
		return $rank['image'];
	}
}

/**
 * Displays a phpBB rank lockup with rank and image
 * @param int $wpID the WordPress ID, leave blank for currently logged-in user
 * @author John Wells
 */
function wpu_phpbb_rankblock($wpID = '') {
	echo get_wpu_phpbb_rankblock($wpID);
}

/**
 * Returns a phpBB rank lockup without displaying it
 * @param int $wpID the WordPress ID, leave blank for currently logged-in user
 * @author John Wells
 */
function get_wpu_phpbb_rankblock($wpID = '') {
	global $phpbbForum;
	$rank = _wpu_get_user_rank_info($wpID);
	if ( $rank ) {
		$block = '<p class="wpu_rank">' . $rank['text'];
		if ( $rank['image'] ) {
			$block .= '<br />' . '<img src="' . $rank['image'] . '" alt="' . $phpbbForum->lang['SORT_RANK'] . '" />';
		}
		$block .= '</p>';
		return $block;
	}
}



/**
 * Displays phpBB forum stats
 * @param string args
 * @example wpu_phpbb_stats('limit=20&before=<li>&after=</li>');
 * @author John Wells
 */
function wpu_phpbb_stats($args='') {
	echo get_wpu_phpbb_stats($args);
}

/**
 * Returns phpBB forum stats without displaying them
 * @param string args
 * @example wpu_phpbb_stats('limit=20&before=<li>&after=</li>');
 * @author John Wells
 */
function get_wpu_phpbb_stats($args='') {
	global $phpbbForum,  $phpEx;
	$defaults = array('before' => '<li>', 'after' => '</li>');
	extract(_wpu_process_args($args, $defaults));

	
	$fStateChanged = $phpbbForum->foreground();
	
	$output = $before .  sprintf(__('Forum Posts: %s', 'wp-united'),  '<strong>' 	. $phpbbForum->stats('num_posts') . '</strong>') . "$after\n";
	$output .= $before .  sprintf(__('Forum Threads: %s', 'wp-united'), '<strong>' 	. $phpbbForum->stats('num_topics') . '</strong>') . "$after\n";
	$output .= $before .  sprintf(__('Registered Users: %s', 'wp-united'), '<strong>' 	. $phpbbForum->stats('num_users')  . '</strong>') . "$after\n";	
	$output .= $before . sprintf(__(' Newest User: %s', 'wp-united'), $phpbbForum->get_username_link('full', $phpbbForum->stats('newest_user_id'), $phpbbForum->stats('newest_username'), $phpbbForum->stats('newest_user_colour'))) . "$after\n";
	$phpbbForum->restore_state($fStateChanged);
	return $output;

}


/**
 * Displays a link to search phpBB posts since the user's last visit (together with number of posts)
 * @param string args
 * @author John Wells
 */
function wpu_newposts_link() {
	echo get_wpu_newposts_link();
}
/**
 * Returns the link to phpBB posts since the user's last visit without displaying it
 * @param string args
 * @author John Wells
 */
function get_wpu_newposts_link() {
	global $phpbbForum, $phpEx;
	if( $phpbbForum->user_logged_in() ) {
		return '<a href="'. $phpbbForum->append_sid($phpbbForum->get_board_url() . 'search.'.$phpEx.'?search_id=newposts') . '"><strong>' . get_wpu_newposts() ."</strong>&nbsp;". __('posts since last visit', 'wp-united') . "</a>";
	}
}

/**
 * Returns the number of posts since the user's last visit
 * @author John Wells
 */
function get_wpu_newposts() {
	global $db, $phpbbForum;
	if( $phpbbForum->user_logged_in() ) {
		$fStateChanged = $phpbbForum->foreground();
		$sql = "SELECT COUNT(post_id) as total
				FROM " . POSTS_TABLE . "
				WHERE post_time >= " . $phpbbForum->get_userdata('user_lastvisit');
		$result = $db->sql_query($sql);
		if( $result ) {
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$phpbbForum->restore_state($fStateChanged);
			return $row['total'];
		}
	}
}

/**
 * Displays a nice list of latest phpBB forum posts
 * @author John Wells
 * @example: wpu_latest_phpbb_posts('limit=10&forum=1,2,3&before=<li>&after=</li>&dateformat=Y-m-j')
 */
function wpu_latest_phpbb_posts($args='') {
	echo get_wpu_latest_phpbb_posts($args);
}
/**
 * Returns a nice list of latest phpBB forum posts without displaying them
 * @author Japgalaxy & John Wells
 * @example: get_wpu_latest_phpbb_posts('limit=10&forum=1,2,3&before=<li>&after=</li>')
 * Modified for v0.8x to use proper WP widget styling and args, and date format
 */
function get_wpu_latest_phpbb_posts($args='') {
		global $phpEx, $phpbbForum;
		$defaults = array('limit' => 10, 'before' => '<li>', 'after' => '</li>', 'forum' => '');
		extract(_wpu_process_args($args, $defaults));
		
		$limit = ($limit > 50 ) ? 50 : $limit;
		
		if ($posts = $phpbbForum->get_recent_posts($forum, $limit)) {
			$profile_path = "memberlist.$phpEx";
			$i=0;
			$output = '';
			foreach ($posts as $post) {
				$first = ($i==0) ? 'wpufirst ' : '';
				$post_link = ($phpbbForum->seo) ? "post{$post['post_id']}.html#p{$post['post_id']}" : "viewtopic.$phpEx?f={$post['forum_id']}&amp;t={$post['topic_id']}&amp;p={$post['post_id']}#p{$post['post_id']}";
				$post_link = '<a href="' . $phpbbForum->get_board_url() .  $phpbbForum->append_sid($post_link) . '">' . $post['post_title'] . '</a>';
				$forum_link = '<a href="' . $phpbbForum->get_board_url() . "viewforum.$phpEx?f=" . $post['forum_id'] . '">' . $post['forum_name'] . '</a>';
				$output .= _wpu_add_class($before, $first . 'wpuforum' . $post['forum_id']) .  sprintf(__('%1$s, posted by %2$s on %3$s', 'wp-united'),$post_link, $post['user_link'],  $post['post_time'])  ."$after\n";
				$i++;
			}
		} else {
			$output = $before. __('Nothing found.', 'wp-united'). $after;
		}
		return $output;
		
	}


/**
 * Displays a nice list of latest phpBB forum topics
 * @author John Wells
 * @example: wpu_latest_phpbb_topics('limit=10&forum=1,2,3&before=<li>&after=</li>')
 */
function wpu_latest_phpbb_topics($args = '') {
	echo get_wpu_latest_phpbb_topics($args);
}

/**
 * Returns a nice list of latest phpBB forum topics without displaying it
 * @author John Wells
 * @example: get_wpu_latest_phpbb_topics('limit=10&forum=1,2,3&before=<li>&after=</li>&showReplyCount=0')
 */
function get_wpu_latest_phpbb_topics($args = '') {
	global $phpEx, $phpbbForum;
	$defaults = array('limit' => 10, 'before' => '<li>', 'after' => '</li>', 'forum' => '', 'showReplyCount' => 0);
	extract(_wpu_process_args($args, $defaults));
	
	$limit = ($limit > 50 ) ? 50 : $limit;
	
	if ($posts = $phpbbForum->get_recent_topics($forum, $limit)) {
		$profile_path = "memberlist.$phpEx";
		$i=0;
		$output = '';
		foreach ($posts as $post) {
			$first = ($i==0) ? 'wpufirst ' : '';
			
			$topic_link = ($phpbbForum->seo) ? "topic{$post['topic_id']}.html" : "viewtopic.$phpEx?f={$post['forum_id']}&amp;t={$post['topic_id']}";
			$topic_link = '<a href="' . $phpbbForum->get_board_url() .  $phpbbForum->append_sid($topic_link) .  '">' . $post['topic_title'] . '</a>';


			$forum_link = '<a href="' . $phpbbForum->get_board_url() . "viewforum.$phpEx?f=" . $post['forum_id'] . '">' . $post['forum_name'] . '</a>';
			$repliesText = ($post['topic_replies'] == 1) ? __(' (1 reply)') : __(' (%d replies)');
			$replyCount = ($showReplyCount) ? '<em>' . sprintf(__($repliesText, 'wp-united'), $post['topic_replies']) . '</em>' : '';
			$output .= _wpu_add_class($before, $first . 'wpuforum' . $post['forum_id']) . sprintf(__('%1$s, posted by %2$s in %3$s', 'wp-united'),$topic_link, $post['user_link'], $forum_link) . $replyCount  ."$after\n";
			$i++;
		}
	} else {
		$output = $before. __('Nothing found.', 'wp-united'). $after;
	}
	return $output;
	
}



/**
 * Retrieve the phpBB user ID from a given WordPress ID
 * @author John Wells
 * @param $wp_userID. The WordPress user ID. Leave blank to use the currently logged-in user.
 * @since v0.7.0
 */
function get_wpu_user_id($wp_userID = 0) {
	if ($wp_userID == 0) {  
		$userdata = wp_get_current_user(); 
		$uID = $userdata->phpbb_userid;	
	} else {
		$uID = get_user_meta($wp_userID, 'phpbb_userid', true);
	}
	return $uID;
}

/**
 * Display the phpBB user ID from a given WordPress ID
 * @author John Wells
 * @param $wp_userID. The WordPress user ID. Leave blank to use the currently logged-in user.
 * @since v0.7.0
 */
function wpu_user_id($wp_userID = '') {
	echo get_wpu_user_id($wp_userID);
}

/**
 * Returns the phpBB user profile link for the current commenter
 * @author John Wells
 * @since v0.7.0
 */
function wpu_get_comment_author_link($link = '') {
global $comment, $phpbb_root_path, $phpbbForum;
	
	// comment URL could already be filled by cross-posted comments
	if(!empty($comment->phpbb_id) && !empty($link)) {
		return $link;
	}
	
	if(empty($comment->user_id)) {
		return (empty($link)) ? '<a href="' . $comment->comment_author_url . '" rel="nofollow">' . $comment->comment_author . '</a>' : $link;
	}
	$uID = get_wpu_user_id($comment->user_id);
	
	if (empty($uID)) { 
		return (empty($link)) ? '<a href="' . $comment->comment_author_url . '" rel="nofollow">' . $comment->comment_author . '</a>' : $link;
	} else {
		if ($phpbbForum->seo) {
			return $wpu_link = '<a href="' . $phpbbForum->get_board_url() . 'member' . $uID . '.html">' . $comment->comment_author . '</a>';
		} else {
			return $wpu_link = '<a href="' . $phpbbForum->get_board_url() . 'memberlist.php?mode=viewprofile&u=' . $uID  . '" rel="nofollow">' . $comment->comment_author . '</a>';
		}
	}
}

/**
 * Displays the phpBB user profile link for the current commenter
 * @author John Wells
 * @since v0.7.0
 */
function wpu_comment_author_link () {
	echo  wpu_get_comment_author_link();
}

/**
 * Displays the logged in user list
 * @author John Wells
 * @since v0.8.0
 * @example wpu_useronlinelist('before=<li>&after=</li>&showBreakdown=1&showRecord=1&showLegend=1');
 */
function wpu_useronlinelist($args = '') {
	echo get_wpu_useronlinelist($args);
}

/**
 * Returns the logged in user list without displaying it
 * @author John Wells
 * @since v0.8.0
 * @example wpu_useronlinelist('before=<li>&after=</li>&showBreakdown=1&showRecord=1&showLegend=1');
 */
function get_wpu_useronlinelist($args = '') {
	global $phpbbForum, $template, $auth, $db, $config, $user, $phpEx, $phpbb_root_path;
	
	$defaults = array('before' => '<li>', 'after' => '</li>', 'showCurrent' => 1, 'showRecord' => 1, 'showLegend' => 1);
	extract(_wpu_process_args($args, $defaults));
	
	$fStateChanged = $phpbbForum->foreground();
	
	if( (!empty($template)) && (!empty($legend))  && ($theList = $template->_rootref['LOGGED_IN_USER_LIST'])) {
		// On the phpBB index page -- everything's already in template
		$legend = $template->_rootref['LEGEND'];
		$l_online_users = $template->_rootref['TOTAL_USERS_ONLINE'];
		$l_online_time = $template->_rootref['L_ONLINE_EXPLAIN'];
		$l_online_record = $template->_rootref['RECORD_USERS'];
		
	} else {
		// On other pages, get the list
		
		
		
		$online_users = obtain_users_online();
		$list = obtain_users_online_string($online_users);
		
		
		// Grab group details for legend display
		if ($auth->acl_gets('a_group', 'a_groupadd', 'a_groupdel'))	{
			$sql = 'SELECT group_id, group_name, group_colour, group_type
				FROM ' . GROUPS_TABLE . '
				WHERE group_legend = 1
				ORDER BY group_name ASC';
		} else {
			$sql = 'SELECT g.group_id, g.group_name, g.group_colour, g.group_type
				FROM ' . GROUPS_TABLE . ' g
				LEFT JOIN ' . USER_GROUP_TABLE . ' ug
					ON (
						g.group_id = ug.group_id
						AND ug.user_id = ' . (int)$phpbbForum->get_userdata('user_id') . '
						AND ug.user_pending = 0
					)
				WHERE g.group_legend = 1
					AND (g.group_type <> ' . GROUP_HIDDEN . ' OR ug.user_id = ' . (int)$phpbbForum->get_userdata('user_id') . ')
				ORDER BY g.group_name ASC';
		}
		$result = $db->sql_query($sql);

		$legend = array();
		while ($row = $db->sql_fetchrow($result)) {
			$colour_text = ($row['group_colour']) ? ' style="color:#' . $row['group_colour'] . '"' : '';
			$group_name = ($row['group_type'] == GROUP_SPECIAL) ? $phpbbForum->lang['G_' . $row['group_name']] : $row['group_name'];

			if ($row['group_name'] == 'BOTS' || ($phpbbForum->get_userdata('user_id') != ANONYMOUS && !$auth->acl_get('u_viewprofile'))) {
				$legend[] = '<span' . $colour_text . '>' . $group_name . '</span>';
			} else {
				$legend[] = '<a' . $colour_text . ' href="' . $phpbbForum->append_sid("{$phpbbForum->get_board_url()}memberlist.{$phpEx}", 'mode=group&amp;g=' . $row['group_id']) . '">' . $group_name . '</a>';
			}
		}
		$db->sql_freeresult($result);
		

		$legend = implode(', ', $legend);
		$l_online_time = ($config['load_online_time'] == 1) ? 'VIEW_ONLINE_TIME' : 'VIEW_ONLINE_TIMES';
		$l_online_time = sprintf($phpbbForum->lang[$l_online_time], $config['load_online_time']);
		$l_online_record = sprintf($phpbbForum->lang['RECORD_ONLINE_USERS'], $config['record_online_users'], $user->format_date($config['record_online_date']));
		$l_online_users = $list['l_online_users'];
		$theList = str_replace($phpbb_root_path, $phpbbForum->get_board_url(), $list['online_userlist']);
			
	} 
	
	$phpbbForum->restore_state($fStateChanged);
	
	$ret = "{$before}{$theList}{$after}";
	if ($showBreakdown) {
		$ret .= "{$before}{$l_online_users} ({$l_online_time}){$after}";
	}
	if ($showRecord) {
		$ret .= "{$before}{$l_online_record}{$after}";
	}
	
	if($showLegend) {
		$ret .= "{$before}<em>{$phpbbForum->lang['LEGEND']}: {$legend}</em>{$after}";
	}
	
	return $ret;
	
}

/**
 * Displays info about the current user, or a login form if they are logged out
 */
	function wpu_login_user_info($args) {
	echo get_wpu_login_user_info($args);
}

/**
 * Gets info about the current user, or a login form if they are logged out, without displaying it
 * @author Japgalaxy, updated by John Wells
 * @example wpu_login_user_info("before=<li>&after=</li>&showLoginForm=1&showRankBlock=1&showNewPosts=1&showWriteLink=1&showAdminLinks=1&showPMs=1&autoLogin=1");
 */
function get_wpu_login_user_info($args) {
	global $user_ID, $db, $auth, $phpbbForum, $wpUnited, $phpEx, $config;
	
	$defaults = array('before' => '<li>', 'after' => '</li>', 'showPMs' => 1, 'showLoginForm' => 1, 'showRankBlock' => 1, 'showNewPosts' => 1, 'showWriteLink' => 1, 'showAdminLinks' => 1, 'autoLogin' => 1);
	extract(_wpu_process_args($args, $defaults));

	$ret = '';

	get_currentuserinfo();
	$loggedIn = $phpbbForum->user_logged_in();
	
	$loginLang = ($loggedIn) ? sprintf($phpbbForum->lang['LOGOUT_USER'], $phpbbForum->get_username()) : $phpbbForum->lang['LOGIN'];
	$loginAction = ($loggedIn) ? '?mode=logout' : '?mode=login';
					
	
	if($loggedIn) {
		$wpu_usr = get_wpu_phpbb_username(); 
		$colour = $phpbbForum->get_userdata('user_colour');
		$colour = ($colour) ? ' style="color: #' . $colour . '" ' : '';
		$ret .= _wpu_add_class($before, 'wpu-widget-lu-username'). '<a href="' . $phpbbForum->get_board_url() . 'ucp.' . $phpEx . '" ' . $colour . '><strong>' . $wpu_usr . '</strong></a>' . $after;
		$ret .= _wpu_add_class($before, 'wpu-widget-lu-avatar') . '<img src="' . get_avatar_reader() . '" alt="' . $phpbbForum->lang['USER_AVATAR'] . '" />' . $after; 

		if ( $showRankBlock ) {
			$ret .= _wpu_add_class($before, 'wpu-widget-lu-rankblock') . get_wpu_phpbb_rankblock() . $after;
		}

		if ( $showNewPosts ) {
			$ret .= $before .  get_wpu_newposts_link() . $after;
		}
		
		$fStateChanged = $phpbbForum->foreground();
		$admin = $auth->acl_get('a_');
		$autoLogin = $config['allow_autologin'];
		$PMs = $phpbbForum->get_user_pm_details();
		$phpbbForum->restore_state($fStateChanged);

		// Handle new PMs
		if($showPMs) {
			if ($PMs['text']) {
				$ret .= _wpu_add_class($before, 'wpu-has-pms'). '<a title="' . $PMs['text'] . '" href="' . $phpbbForum->get_board_url() . 'ucp.' . $phpEx . '?i=pm&folder=inbox">' . $PMs['text']. '</a>' . $after;
			} else {
				$ret .= _wpu_add_class($before, 'wpu-no-pms') . '<a title="' . $phpbbForum->lang['NO_NEW_PM'] . '" href="' . $phpbbForum->get_board_url() . 'ucp.' . $phpEx . '?i=pm&folder=inbox">' . $phpbbForum->lang['NO_NEW_PM'] . '</a>' . $after;
			}	
		}

		if ($showWriteLink) {
			if (current_user_can('edit_posts')) {
				$ret .= $before . '<a href="'. $wpUnited->get_wp_base_url() .'wp-admin/post-new.php" title="' . __('Write a Post', 'wp-united') . '">' . __('Write a Post', 'wp-united') . '</a>' . $after;
			}
		}
		if ($showAdminLinks) {
			if (current_user_can('read')) {
				$ret .= $before . '<a href="'.$wpUnited->get_wp_base_url() .'wp-admin/" title="' . __('Dashboard', 'wp-united') . '">' . __('Dashboard', 'wp-united') . '</a>' . $after;
			}
			
			if($admin) {
				$ret .= $before . '<a href="'. $phpbbForum->append_sid($phpbbForum->get_board_url() . 'adm/index.' . $phpEx) . '" title="' .  $phpbbForum->lang['ACP'] . '">' . $phpbbForum->lang['ACP'] . '</a>' . $after;
			}
		}
		$ret .= $before . '<a href="' . $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx . $loginAction) . '" title="' . $loginLang . '">' .  $loginLang . '</a>' . $after;
	} else {
		if ( $showLoginForm ) {
			$redir = wpu_get_redirect_link();
			$login_link = $phpbbForum->append_sid('ucp.'.$phpEx.'?mode=login') . '&amp;redirect=' . $redir;
			$ret .= '<form class="wpuloginform" method="post" action="' . $phpbbForum->get_board_url() . $login_link . '">';
			$ret .= $before . '<label for="phpbb_username">' . $phpbbForum->lang['USERNAME'] . '</label> <input tabindex="1" class="inputbox autowidth" type="text" name="username" id="phpbb_username"/>' . $after;
			$ret .= $before . '<label for="phpbb_password">' . $phpbbForum->lang['PASSWORD'] . '</label> <input tabindex="2" class="inputbox autowidth" type="password" name="password" id="phpbb_password" maxlength="32" />' . $after;
			if ( $autoLogin ) {
				$ret .= $before . '<input tabindex="3" type="checkbox" id="phpbb_autologin" name="autologin" /><label for="phpbb_autologin"> ' . __('Remember me', 'wp-united') . '</label>' . $after;
			}
			$ret .= $before . '<input type="submit" name="login" class="wpuloginsubmit" value="' . __('Login') . '" />' . $after;
			$ret .= $before . '<a href="' . $phpbbForum->append_sid($phpbbForum->get_board_url()."ucp.php?mode=register") . '">' . __('Register', 'wp-united') . '</a>' . $after;
			$ret .= $before . '<a href="'. $phpbbForum->append_sid($phpbbForum->get_board_url().'ucp.php?mode=sendpassword') . '">' . __('Forgot Password?', 'wp-united') . '</a>' . $after;
			$ret .= '</form>';
		} else {
			$ret .= $before . '<a href="' . $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx . $loginAction) . '" title="' . $loginLang . '">' .  $loginLang . '</a>';
		}
	}
	
	return $ret;
}


function _wpu_get_breadcrumbs($showSiteHome)  {
	global $wpUnited, $phpbbForum;
	
	static $crumbStr = false;
	
	if(!empty($crumbStr)) {
		return $crumbStr;
	}
	
	$crumbs = array();
	$accessKey = 'accesskey="h"';
	$crumbStr = '';
	
	if($showSiteHome) {
		$crumbs[] = '<a href="' . $phpbbForum->append_sid($phpbbForum->get_board_url()) . '" ' . $accessKey . '>' . $phpbbForum->lang['FORUM_INDEX'] . '</a>';
		$accessKey = '';
	}
	
	$crumbs[] = '<a href="' . $wpUnited->get_wp_home_url() . '" ' . $accessKey . '>' . get_option('blogname') . '</a>';
	
	if(!is_home()){
		//TODO: These are loop functions, change!
		if (is_category()) {
			$category = get_the_category(); 
			$crumbs[] = '<a href="' . esc_url($_SERVER['REQUEST_URI']) . '">' . $category[0]->cat_name . '</a>';
		} else if(is_single() || is_page()) {
			$crumbs[] = '<a href="' . esc_url($_SERVER['REQUEST_URI']) . '">' . get_the_title() . '</a>';
		} 
	}
	
	foreach($crumbs as $crumbID => $crumb) { 
		if($crumbID > 0) {
			$crumbStr .= ' <strong>&#8249;</strong> ';
		}
		$crumbStr .= $crumb;
	}
	
	return $crumbStr;
	
	
}

/**
 * Displays the phpBB nav block.
 */

function wpu_phpbb_nav_block($args) {

	global $phpbbForum, $phpEx, $wpUnited;
	
	$defaults = array('showSiteHome' => 1, 'showMemberList' => 1, 'showRegLink' => 1, 'useNativeCSS' => 0, 'showStyleSwitcher' => 1);
	extract(_wpu_process_args($args, $defaults));
	$ret = '';
	
	$nativeClass = (!$useNativeCSS) ? 'wpuisle' : 'wpunative';
	
	$PMs = $phpbbForum->get_user_pm_details();
	
	?>
	
	<div class="textwidget <?php echo $nativeClass; ?>"><div class="<?php echo $nativeClass; ?>2">
		<div class="navbar ">
			<div class="navinner"><span class="corners-top"><span></span></span>
			<ul class="linklist navlinks">
				<li class="icon-home">
					<?php echo _wpu_get_breadcrumbs($showSiteHome); ?> 
				</li>
				<?php if($showStyleSwitcher) { ?>
					<li class="rightside"><a href="#" onclick="fontsizeup(); return false;" onkeypress="return fontsizeup(event);" class="fontsize" title="<?php echo $phpbbForum->lang['CHANGE_FONT_SIZE']; ?>"><?php echo $phpbbForum->lang['CHANGE_FONT_SIZE']; ?></a></li>
				<?php } ?>
			</ul>
			
			<?php if($phpbbForum->user_logged_in() && !$phpbbForum->get_userdata('is_bot')) { ?>
			<ul class="linklist leftside">
				<li class="icon-ucp">
					<a href="<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx); ?>" title="<?php echo $phpbbForum->lang['PROFILE']; ?>" accesskey="e"><?php echo $phpbbForum->lang['PROFILE']; ?></a>
					<?php if( $PMs['text']) { 
						?>(<a href="<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx . '?i=pm&folder=inbox'); ?>"><?php echo $PMs['text']; ?></a>)
					<?php } ?>
					<?php if($phpbbForum->user_logged_in()) { ?> &bull;
						<a href="<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . 'search.' . $phpEx . '?search_id=egosearch'); ?>"><?php echo $phpbbForum->lang['SEARCH_SELF']; ?></a>
					<?php } ?>
				</li>
			</ul>
			<?php } ?>

			<ul class="linklist rightside">
				<li class="icon-faq"><a href="<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . 'faq.' . $phpEx); ?>" title="<?php echo $phpbbForum->lang['FAQ_EXPLAIN']; ?>"><?php echo $phpbbForum->lang['FAQ']; ?></a></li>
				<?php if(!$phpbbForum->get_userdata('is_bot')) { ?>
					<?php if($showMemberList) { ?>
						<li class="icon-members"><a href="<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . 'memberlist.' . $phpEx); ?>" title="<?php echo $phpbbForum->lang['MEMBERLIST_EXPLAIN']; ?>"><?php echo $phpbbForum->lang['MEMBERLIST']; ?></a></li>
					<?php }
					if(!$phpbbForum->user_logged_in() && $showRegLink) { ?>
						<li class="icon-register"><a href="<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . '.ucp.' . $phpEx . '?mode=' . 'register'); ?>"><?php echo $phpbbForum->lang['REGISTER']; ?></a></li>
					<?php } 
						$loginLang = ($phpbbForum->user_logged_in()) ? sprintf($phpbbForum->lang['LOGOUT_USER'], $phpbbForum->get_username()) : $phpbbForum->lang['LOGIN'];
						$loginAction = ($phpbbForum->user_logged_in()) ? '?mode=logout' : '?mode=login';
					?>	
					<li class="icon-logout"><a href="<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx . $loginAction); ?>" title="<?php echo $loginLang; ?>" accesskey="x"><?php echo $loginLang; ?></a></li>
				<?php } ?>
			</ul>

			<span class="corners-bottom"><span></span></span></div>
		</div>
	</div></div>
	
	<?php
}


/**
 * Nav block footer
 * @conceived by *daniel
 */
 
function wpu_phpbb_nav_block_footer($args) {

	global $phpbbForum, $phpEx, $wpUnited;
	
	$defaults = array('showSiteHome' => 1, 'useNativeCSS' => 0);
	extract(_wpu_process_args($args, $defaults));
	$ret = '';
	$timeZoneString = '';
	
	$nativeClass = (!$useNativeCSS) ? 'wpuisle' : 'wpunative';
	
	// get timezone
	$fStateChanged = $phpbbForum->foreground();
	global $config;
	$tz = ($phpbbForum->get_userdata('user_id') != ANONYMOUS) ? strval(doubleval($phpbbForum->get_userdata('user_timezone'))) : strval(doubleval($config['board_timezone']));
	if($phpbbForum->get_userdata('user_dst') || ($phpbbForum->get_userdata('user_id') == ANONYMOUS && $config['board_dst'])) {
		$timeZoneString = sprintf($phpbbForum->lang['ALL_TIMES'], $phpbbForum->lang['tz'][$tz], $phpbbForum->lang['tz']['dst']);
	} else {
		$timeZoneString = sprintf($phpbbForum->lang['ALL_TIMES'], $phpbbForum->lang['tz'][$tz], '');
	}
	$phpbbForum->restore_state($fStateChanged);
	
	
	?>
	
	<div class="textwidget <?php echo $nativeClass; ?>"><div class="<?php echo $nativeClass; ?>2">
		<div class="navbar ">
			<div class="navinner"><span class="corners-top"><span></span></span>
			<ul class="linklist">
				<li class="icon-home">
					<?php echo _wpu_get_breadcrumbs($showSiteHome); ?> 
				</li>			
			<li class="rightside"><a href="<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . 'memberlist.' . $phpEx . '?mode=leaders' ); ?>"><?php echo $phpbbForum->lang['THE_TEAM']; ?></a> &bull; <a href="<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx .'?mode=delete_cookies'); ?>"><?php echo $phpbbForum->lang['DELETE_COOKIES']; ?></a> &bull; <?php echo $timeZoneString; ?></li>
			</ul>
			<span class="corners-bottom"><span></span></span></div>
		</div>
	</div></div>
	
	<?php

}


/** 
 * Adds prosilver styles to (the bottom of) the page
 *
 */
 function wpu_add_board_styles($includeStyleSwitcher = false) {
	global $phpbbForum, $wpUnited, $phpEx;
	static $addedStyles = false;
	static $addedStyleSwitcher = false;
	static $themePath = '';
	static $addedBaseScript = false;

	if(!$addedStyles) {
		$addedStyles = true;
		
		wp_enqueue_style('wpu-island-reset', $wpUnited->get_plugin_url() . 'theme/island-reset.css');
		wp_enqueue_style('wpu-island', $phpbbForum->get_island_stylesheet());
	}
	
	if($includeStyleSwitcher && !$addedStyleSwitcher) {
		$themePath =  $phpbbForum->get_theme_path();
		wp_enqueue_style('wpu-nav-blk-2', $themePath . 'normal.css', true);
		wp_enqueue_style('wpu-nav-blk-3', $themePath . 'medium.css', true);
		wp_enqueue_style('wpu-nav-blk-4', $themePath . 'large.css', true);
		
		global $wp_styles;
		$wp_styles->add_data( 'wpu-nav-blk-2', 'title', 'A' );
		$wp_styles->add_data( 'wpu-nav-blk-3', 'title', 'A+' );
		$wp_styles->add_data( 'wpu-nav-blk-3', 'alt', true );
		$wp_styles->add_data( 'wpu-nav-blk-4', 'title', 'A++' );
		$wp_styles->add_data( 'wpu-nav-blk-4', 'alt', true);
		
		wp_enqueue_script('wpu-nav-blk-j', $phpbbForum->get_super_template_path() . 'styleswitcher.js');
	}
	
	if(!$addedBaseScript) { 
		$PMs = $phpbbForum->get_user_pm_details();
		?>
		<script type="text/javascript">// <![CDATA[
			var base_url = '<?php echo $phpbbForum->get_board_url(); ?>';
			var style_cookie = 'phpBBstyle';
			var style_cookie_settings = '<?php $phpbbForum->get_style_cookie_settings(); ?>';
			var onload_functions = new Array();
			var onunload_functions = new Array();
			<?php if($PMs['new'] && $PMs['popup']) { ?>
				var url = '<?php echo $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx . '?i=pm&amp;mode=popup'); ?>';
				window.open(url.replace(/&amp;/g, '&'), '_phpbbprivmsg', 'height=225,resizable=yes,scrollbars=yes, width=400');
			<?php } ?>
			window.onload = function(){for (var i = 0; i < onload_functions.length; i++)eval(onload_functions[i]);};
			window.onunload = function(){for(var i = 0; i < onunload_functions.length; i++)eval(onunload_functions[i]);};
		// ]]>
		</script>
		<?php 
		$addedBaseScript = true;
	}
		

 }
 



/**
 * Displays the comment link, with the number of phpBB comments
 * @author Japgalaxy
 * @todo allow to specify a specific post ID
 * @todo combine these three queries with a JOIN
 * @version v0.8.0
 * @access private
 */
function wpu_comment_number () {
	echo get_wpu_comment_number();
}


/**
 * Display replies of a cross-posted post and a comment-form if user is logged in.
 * @author Japgalaxy
 * @since v0.8.0
 */



/**
 * Helper / Private functions
 */

/**
 * Returns a URL suitable for sending as a redirect instruction to phpBB
  * @ since v0.8.1
 */
function wpu_get_redirect_link() {
	global $phpbbForum;
	if(!empty( $_SERVER['REQUEST_URI'])) {
		$protocol = empty($_SERVER['HTTPS']) ? 'http:' : ((strtolower($_SERVER["HTTPS"]) == 'on') ? 'https:' : 'http:');
		$protocol = ($_SERVER['SERVER_PORT'] == '80') ? $protocol : $protocol . $_SERVER['SERVER_PORT'];
		$link = $protocol . '//' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	} else {
		$link = get_option('home');
	}
	$fStateChanged = $phpbbForum->foreground();
	$link = reapply_sid($link);
	$phpbbForum->restore_state($fStateChanged);
	return urlencode(esc_attr($link));
}

/**
 * In order to make the comment link a consistent template tag, and split to get_/echo
 * we need to create this missing WordPress get_ equivalent for comment link
 * @author John Wells
 * @since v0.8.0
 */
if(!function_exists('get_comments_popup_link')) {
	function get_comments_popup_link($no, $com, $coms, $closed) {
		ob_start();
		comments_popup_link($no, $com, $coms, $closed);
		$link = ob_get_contents();
		ob_end_clean();
		return $link;
	}
}

/**
 * In order to make the loginout link a consistent template tag, and split to get_/echo
 * we need to create this missing WordPress get_ equivalent
 * @author John Wells
 * @since v0.8.0
 */
if(!function_exists('get_wp_loginout')) {
	function get_wp_loginout() {
		ob_start();
		wp_loginout();
		$link = ob_get_contents();
		ob_end_clean();
		return $link;
	}
}

/**
 * Adds a classname to an element if it doesn't already exist
 * @since v0.8.5/v0.9.0
 * @param string $el the element in which to add a class
 * @param string $class the name of the class to insert
 * @access private
 */
function _wpu_add_class($el, $class) {
	$find = '>';
	$repl = ' class="%s">';
	if(stristr($el, 'class="') > 0) {
		$find = 'class="';
		$repl = 'class="%s ';
	} else if(stristr($el, "class='") > 0) {
		$find = "class='";
		$repl = "class='%s ";
	}
	return str_replace($find, sprintf($repl, $class), $el);
	
}


/**
 * Load the rank details for the user
 * @access private
 */
function _wpu_get_user_rank_info($userID = '') {

	global $phpbbForum, $phpbb_root_path;
	$fStateChanged = $phpbbForum->foreground();
	$rank = $phpbbForum->get_user_rank_info($userID);
	$phpbbForum->restore_state($fStateChanged);
	$rank['image'] = (empty($rank['image'])) ? '' : str_replace($phpbb_root_path, $phpbbForum->get_board_url(), $rank['image']);
	return $rank;
}
	
/**
 * Process argument string for template functions
 * @author John Wells
 * @access private
 */
function _wpu_process_args($args, $defaults='') {
	if ( is_array($args) ) {
		$r = &$args;
	} else {
		parse_str($args, $r);
	}
	if ( is_array($defaults) ) {
		$r = array_merge($defaults, $r);
	}
	return $r;
}


// That's all. Nothing else to see here.