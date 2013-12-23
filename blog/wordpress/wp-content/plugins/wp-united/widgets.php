<?php
/** 
*
* New WP-United widgets
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

// this file will also be called in wp admin panel, when phpBB is not loaded. 
if ( !defined('ABSPATH') && !defined('IN_PHPBB') ) {
	exit;
}

/**
 * Returns a nice block containing info about the phpBB user that is currently logged in *to phpBB*
 */
class WPU_Login_User_info_Widget extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array('classname' => 'wp-united-loginuser-info', 'description' => __('Displays the logged-in user\'s details, such as username, avatar, and number of posts since last visit, together with varius meta links. If the user is logged out, displays a phpBB login form.', 'wp-united') );
		$this->WP_Widget('wp-united-loginuser-info', __('WP-United Login / User Info Box', 'wp-united'), $widget_ops);
	}
	
	public function widget($args, $instance) {
		// prints the widget
		global $phpbbForum;
		
		extract($args, EXTR_SKIP);
		
		$titleLoggedIn = empty($instance['title-logged-in']) ? '&nbsp;' : apply_filters('widget_title', $instance['title-logged-in']);
		$titleLoggedOut = empty($instance['title-logged-out']) ? '&nbsp;' : apply_filters('widget_title', $instance['title-logged-out']);

		$loginForm = $instance['login-form'];
		$rankBlock = $instance['rank'];
		$newPosts = $instance['new'];
		$write = $instance['write'];
		$admin = $instance['admin'];

		if ( !function_exists('wpu_login_user_info') ) return;
		
		$title =  ($phpbbForum->user_logged_in()) ? $titleLoggedIn : $titleLoggedOut;

		echo $before_widget . $before_title . $title . $after_title;
		echo '<ul class="wpulogininfo">';
		wpu_login_user_info("showLoginForm={$loginForm}&showRankBlock={$rankBlock}&showNewPosts={$newPosts}&showWriteLink={$write}&showAdminLinks={$admin}");
		echo '</ul>';
		echo $after_widget;
	}
 
	public function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;

		$instance['title-logged-in'] = strip_tags(stripslashes($new_instance['title-logged-in']));
		$instance['title-logged-out'] = strip_tags(stripslashes($new_instance['title-logged-out']));
		
		$instance['login-form'] = (strip_tags(stripslashes($new_instance['login-form'])) == 'login-form')? 1 : 0;
		$instance['rank'] = (strip_tags(stripslashes($new_instance['rank'])) == 'rank')? 1 : 0;
		$instance['new'] = (strip_tags(stripslashes($new_instance['new'])) == 'new')? 1 : 0;
		$instance['write'] = (strip_tags(stripslashes($new_instance['write'])) == 'write')? 1 : 0;
		$instance['admin'] = (strip_tags(stripslashes($new_instance['admin'])) == 'admin')? 1 : 0;

		return $instance;
	}
 
	public function form($instance) {
		//widget form
		
		$instance = wp_parse_args( (array) $instance, array( 
			'title-logged-in'=> __('You are logged in as:', 'wp-united'),
			'title-logged-out'=> __('You are not logged in.', 'wp-united'),
			'rank'=>1, 
			'new'=>1, 
			'write' => 0,
			'admin' => 0,
			'login-form'=>1
		));
		
		$titleLoggedIn = strip_tags($instance['title-logged-in']);
		$titleLoggedOut = strip_tags($instance['title-logged-out']);
		 
		$rank= (!empty($instance['rank'])) ? 'checked="checked"' : '';
		$new = (!empty($instance['new'])) ? 'checked="checked"' : '';
		$write = (!empty($instance['write'])) ? 'checked="checked"' : '';
		$admin = (!empty($instance['admin'])) ? 'checked="checked"' : '';
		$loginForm = (!empty($instance['login-form'])) ? 'checked="checked"' : '';
		?>
		
		<p><label for="<?php echo $this->get_field_id('title-logged-in'); ?>"><?php _e('You are logged in as:', 'wp-united'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title-logged-in'); ?>" name="<?php echo $this->get_field_name('title-logged-in'); ?>" type="text" value="<?php echo esc_attr($titleLoggedIn); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('title-logged-out'); ?>"><?php _e('You are not logged in.', 'wp-united'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title-logged-out'); ?>" name="<?php echo $this->get_field_name('title-logged-out'); ?>" type="text" value="<?php echo esc_attr($titleLoggedOut); ?>" /></label></p>
		<p><input id="<?php echo $this->get_field_id('rank'); ?>" name="<?php echo $this->get_field_name('rank'); ?>" type="checkbox" value="rank" <?php echo $rank ?> /> <label for="<?php echo $this->get_field_id('rank'); ?>"><?php _e('Show rank title &amp; image?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('new'); ?>" name="<?php echo $this->get_field_name('new'); ?>" type="checkbox" value="new"  <?php echo $new ?> /> <label for="<?php echo $this->get_field_id('new'); ?>"><?php _e('Show new posts?', 'wp-united');?></label></p>
		<p><input id="<?php echo $this->get_field_id('write'); ?>" name="<?php echo $this->get_field_name('write'); ?>" type="checkbox" value="write" <?php echo $write ?> /> <label for="<?php echo $this->get_field_id('write'); ?>"><?php _e('Show write post link?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('admin'); ?>" name="<?php echo $this->get_field_name('admin'); ?>" type="checkbox" value="admin" <?php echo $admin ?> /> <label for="<?php echo $this->get_field_id('admin'); ?>"><?php _e('Show Admin link?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('login-form'); ?>" name="<?php echo $this->get_field_name('login-form'); ?>" type="checkbox" value="login-form" <?php echo $loginForm ?> /> <label for="<?php echo $this->get_field_id('login-form'); ?>"><?php _e('Show phpBB login form if logged out?', 'wp-united'); ?></label></p>
		
		<?php
	}	
}


/**
 * Latest phpBB topics widget
 * Returns a lsit of recent topics, in the format XXXXX posted by YYYYYY in ZZZZZZZ.
 */
class WPU_Latest_Phpbb_Topics_Widget extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array('classname' => 'wp-united-latest-topics', 'description' => __('Shows the latest topics posted in the phpBB forum.', 'wp-united') );
		$this->WP_Widget('wp-united-latest-topics', __('WP-United Latest phpBB Topics', 'wp-united'), $widget_ops);
	}
	
	public function widget($args, $instance) {
		// prints the widget
		
		extract($args, EXTR_SKIP);
		
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		$maxEntries = empty($instance['max']) ? 25 : $instance['max'];
		$showReplyCount = empty($instance['replies']) ? 0 : 1;

		if ( !function_exists('wpu_latest_phpbb_topics') ) return false;
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo '<ul class="wpulatesttopics">';
		wpu_latest_phpbb_topics('limit='.$maxEntries . '&showReplyCount=' . $showReplyCount);
		echo '</ul>' . $after_widget;

	}
 
	public function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;

		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['max'] = (int) $new_instance['max'];
		$instance['replies'] = ($new_instance['replies'] == 'replies') ? 1 : 0;

		return $instance;
	}
 
	public function form($instance) {
		//widget form
		
		$instance = wp_parse_args((array) $instance, array( 
			'title' => __('Recent Forum Topics', 'wp-united'),
			'max' => 25,
			'replies' => 0
		));
		
		$title = strip_tags($instance['title']);
		$max = strip_tags($instance['max']);
		$showReplyCount = (!empty($instance['replies'])) ? 'checked="checked"' : '';

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title: ', 'wp-united'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('max'); ?>"><?php _e('Maximum Entries:', 'wp-united') ?> <input class="widefat" id="<?php echo $this->get_field_id('max'); ?>" maxlength="3" name="<?php echo $this->get_field_name('max'); ?>" type="text" value="<?php echo esc_attr($max); ?>" /></label></p>
		<p><input id="<?php echo $this->get_field_id('replies'); ?>" name="<?php echo $this->get_field_name('replies'); ?>" type="checkbox" value="replies" <?php echo $showReplyCount ?> /> <label for="<?php echo $this->get_field_id('replies'); ?>"><?php _e('Show reply count?', 'wp-united'); ?></label></p>
		<?php
	}	
}

/**
 * Latest phpBB posts widget
 * Returns a lsit of recent posts
 */
class WPU_Latest_Phpbb_Posts_Widget extends WP_Widget {
	
	public function __construct() {
		global $phpbbForum;
		$widget_ops = array('classname' => 'wp-united-latest-posts', 'description' => __('Shows the latest posts posted in the phpBB forum.', 'wp-united') );
		$this->WP_Widget('wp-united-latest-posts', __('WP-United Latest Forum Posts', 'wp-united'), $widget_ops);
	}
	
	function widget($args, $instance) {
		// prints the widget
		
		extract($args, EXTR_SKIP);
		
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		$maxEntries = empty($instance['max']) ? 25 : (int)$instance['max'];

		if ( !function_exists('wpu_latest_phpbb_posts') ) return false;
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo '<ul class="wpulatestposts">';
		wpu_latest_phpbb_posts('limit='.$maxEntries); 
		echo '</ul>' . $after_widget;

	}
 
	public function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;

		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['max'] = (int) strip_tags(stripslashes($new_instance['max']));

		return $instance;
	}
 
	public function form($instance) {
		//widget form
		
		$instance = wp_parse_args((array) $instance, array( 
			'title' => __('Recent Forum Posts', 'wp-united'),
			'max' => 25
		));
		
		$title = strip_tags($instance['title']);
		$max = strip_tags($instance['max']);

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php  _e('Title: ', 'wp-united'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('max'); ?>"><?php _e('Maximum Entries: ', 'wp-united') ?> <input class="widefat" id="<?php echo $this->get_field_id('max'); ?>" maxlength="3" name="<?php echo $this->get_field_name('max'); ?>" type="text" value="<?php echo esc_attr($max); ?>" /></label></p>
		<?php
	}	
}


class WPU_Forum_Stats_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array('classname' => 'wp-united-forum-stats', 'description' => __('Show key forum statistics and information.', 'wp-united') );
		$this->WP_Widget('wp-united-forum-stats', __('WP-United Forum Statistics', 'wp-united'), $widget_ops);
	}
	
	public function widget($args, $instance) {
		
		extract($args, EXTR_SKIP);
		
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		
		if ( !function_exists('wpu_phpbb_stats') ) return false;
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo '<ul class="wpuforumstats">';
		wpu_phpbb_stats();
		echo '</ul>' . $after_widget;
		
		
	}
	
	public function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;

		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		
		return $instance;
	}
	
	public function form($instance) {
		//widget form
		
		$instance = wp_parse_args((array) $instance, array( 
			'title' => __('Forum Stats', 'wp-united')
		));
		
		$title = strip_tags($instance['title']);

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php  _e('Title: ', 'wp-united'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<?php		
	}
	
	
	
	
}

class WPU_Forum_Users_Online_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array('classname' => 'wp-united-users-online', 'description' => __('Show information about users that are currently online, in the usual phpBB format.', 'wp-united') );
		$this->WP_Widget('wp-united-users-online', __('WP-United Users Online', 'wp-united'), $widget_ops);
	}
	
	public function widget($args, $instance) {
		
		extract($args, EXTR_SKIP);
		
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		$showBreakdown = $instance['showBreakdown'];
		$showRecord = $instance['showRecord'];
		$showLegend = $instance['showLegend'];
		

		if ( !function_exists('wpu_useronlinelist') ) return false;
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo '<div class="wpuusersonline textwidget">';
		wpu_useronlinelist("before= &after=<br />&showBreakdown={$showBreakdown}&showRecord={$showRecord}&showLegend={$showLegend}");
		echo '</div>' . $after_widget;

	}
	
	public function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;

		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		
		$instance['showBreakdown'] 	= (strip_tags(stripslashes($new_instance['showBreakdown'])) == 	'brk')? 1 : 0;
		$instance['showRecord'] 	= (strip_tags(stripslashes($new_instance['showRecord'])) 	== 	'rec')? 1 : 0;
		$instance['showLegend'] 	= (strip_tags(stripslashes($new_instance['showLegend'])) 	== 	'leg')? 1 : 0;
		
		return $instance;
	}
	
	public function form($instance) {
		//widget form
		
		$instance = wp_parse_args( (array) $instance, array( 
			'title' 			=> __('Users Online', 'wp-united'),
			'showBreakdown'		=> 1, 
			'showRecord'		=> 1, 
			'showLegend' 		=> 1
		));
		
		$title = strip_tags($instance['title']);
		 
		$showBreakdown	= (!empty($instance['showBreakdown'])) 	? 'checked="checked"' : '';
		$showRecord 	= (!empty($instance['showRecord'])) 	? 'checked="checked"' : '';
		$showLegend 	= (!empty($instance['showLegend'])) 	? 'checked="checked"' : '';

		?>
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php  _e('Title: ', 'wp-united'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><input id="<?php echo $this->get_field_id('showBreakdown'); ?>" name="<?php echo $this->get_field_name('showBreakdown'); ?>" type="checkbox" value="brk"  <?php echo $showBreakdown ?> /> <label for="<?php echo $this->get_field_id('showBreakdown'); ?>"><?php _e('Show a breakdown of user types?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showRecord'); ?>" name="<?php echo $this->get_field_name('showRecord'); ?>" type="checkbox" value="rec" <?php echo $showRecord ?> /> <label for="<?php echo $this->get_field_id('showRecord'); ?>"><?php _e('Show record number of users?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showLegend'); ?>" name="<?php echo $this->get_field_name('showLegend'); ?>" type="checkbox" value="leg" <?php echo $showLegend ?> /> <label for="<?php echo $this->get_field_id('showLegend'); ?>"><?php _e('Show legend?', 'wp-united'); ?></label></p>
		
		<?php
	}

}

class WPU_Useful_Forum_Links_Widget extends WP_Widget {

	public	$defaultArgs;




	public function __construct() {
		
		$this->defaultArgs = array( 
			'title' 			=> __('Useful Forum Links', 'wp-united'),
			'showForumIndex'	=> 1,
			'showForumSearch'	=> 1,
			'showUnanswered'	=> 0,
			'showActive'		=> 0,
			'showMyPosts'		=> 1,
			'showNewPosts'		=> 1,
			'showProfile'		=> 1,
			'showPMs'			=> 1,
			'showACP'			=> 0
		);
	
	
		$widget_ops = array('classname' => 'wp-united-useful-links', 'description' => __('Useful customizable links to forum features', 'wp-united') );
		$this->WP_Widget('wp-united-useful-links', __('WP-United Useful Forum Links', 'wp-united'), $widget_ops);
	}
	
	public function widget($args, $instance) {
		global $phpbbForum, $phpEx, $auth;
		
		extract($args, EXTR_SKIP);
		
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		$before = '<li><a href="';
		$after = '</a></li>';
		$adj = '">';
		echo '<ul class="wpuusefullinks">';
		if($instance['showForumIndex']) {
			echo $before . $phpbbForum->append_sid($phpbbForum->get_board_url()) . $adj . $phpbbForum->lang['FORUM_INDEX'] . $after;
		}
		if($instance['showForumSearch']) {
			echo $before . $phpbbForum->append_sid($phpbbForum->get_board_url() . 'search.' . $phpEx) . $adj . $phpbbForum->lang['SEARCH'] . $after;
		}
		if($instance['showUnanswered']) {
			echo $before . $phpbbForum->append_sid($phpbbForum->get_board_url() . 'search.' . $phpEx . '?search_id=unanswered') . $adj . $phpbbForum->lang['SEARCH_UNANSWERED'] . $after;
		}
		if($instance['showActive']) {
			echo $before . $phpbbForum->append_sid($phpbbForum->get_board_url() . 'search.' . $phpEx . '?search_id=active_topics') . $adj . $phpbbForum->lang['SEARCH_ACTIVE_TOPICS'] . $after;
		}		
		if($phpbbForum->user_logged_in()) {
			if($instance['showMyPosts']) {
				echo $before . $phpbbForum->append_sid($phpbbForum->get_board_url() . 'search.' . $phpEx . '?search_id=egosearch') . $adj . $phpbbForum->lang['SEARCH_SELF'] . $after;
			}
			if($instance['showNewPosts']) {
				echo '<li>' . get_wpu_newposts_link() . '</li>';
			}
			if($instance['showProfile']) {
				echo $before . $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx) . $adj . $phpbbForum->lang['PROFILE'] . $after;
			}				
			if($instance['showPMs']) {
				if ($phpbbForum->get_userdata('user_new_privmsg')) {
					$l_message_new = ($phpbbForum->get_userdata('user_new_privmsg') == 1) ? $phpbbForum->lang['NEW_PM'] : $phpbbForum->lang['NEW_PMS'];
					$l_privmsgs_text = sprintf($l_message_new, $phpbbForum->get_userdata('user_new_privmsg'));
					echo $before . $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx . '?i=pm&folder=inbox') . $adj . $l_privmsgs_text . $after;
				} else {
					$l_privmsgs_text = $phpbbForum->lang['NO_NEW_PM'];
					$s_privmsg_new = false;
					echo $before . $phpbbForum->append_sid($phpbbForum->get_board_url() . 'ucp.' . $phpEx . '?i=pm&folder=inbox') . $adj . $l_privmsgs_text . $after;
				}	
			}
			if($instance['showACP']) {
				$fStateChanged = $phpbbForum->foreground();
				if($auth->acl_get('a_')) {
					echo $before . $phpbbForum->append_sid($phpbbForum->get_board_url() . ('adm/index.' . $phpEx)) . $adj . $phpbbForum->lang['ACP'] . $after;
				}
				$phpbbForum->restore_state($fStateChanged);
			}	
		}
		
		echo '</ul>';
	}
	
	public function update($new_instance, $old_instance) {

		//save the widget
		$instance = $old_instance;
		
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));

		foreach($this->defaultArgs as $var => $default) {
			if($var == 'title') {
				$instance[$var] = strip_tags(stripslashes($new_instance[$var]));
			} else {
				$instance[$var] = (strip_tags(stripslashes($new_instance[$var])) == 'ok')? 1 : 0;
			}
		}

		return $instance;
	}
	
	public function form($instance) {
		//widget form
		
		
		$instance = wp_parse_args( (array) $instance, $this->defaultArgs);
		 
		foreach ($this->defaultArgs as $var => $default) {
			if($var == 'title') {
				$$var = strip_tags($instance[$var]);
			} else {
				$$var = (!empty($instance[$var])) ? 'checked="checked"' : '';
			}
		}


		?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php  _e('Title: ', 'wp-united'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><input id="<?php echo $this->get_field_id('showForumIndex'); ?>" name="<?php echo $this->get_field_name('showForumIndex'); ?>" type="checkbox" value="ok"  <?php echo $showForumIndex ?> /> <label for="<?php echo $this->get_field_id('showForumIndex'); ?>"><?php _e('Show forum index link?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showForumSearch'); ?>" name="<?php echo $this->get_field_name('showForumSearch'); ?>" type="checkbox" value="ok" <?php echo $showForumSearch ?> /> <label for="<?php echo $this->get_field_id('showForumSearch'); ?>"><?php _e('Show search link?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showUnanswered'); ?>" name="<?php echo $this->get_field_name('showUnanswered'); ?>" type="checkbox" value="ok" <?php echo $showUnanswered ?> /> <label for="<?php echo $this->get_field_id('showUnanswered'); ?>"><?php _e('Show unanswered posts link?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showActive'); ?>" name="<?php echo $this->get_field_name('showActive'); ?>" type="checkbox" value="ok" <?php echo $showActive ?> /> <label for="<?php echo $this->get_field_id('showActive'); ?>"><?php _e('Show active topics link?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showMyPosts'); ?>" name="<?php echo $this->get_field_name('showMyPosts'); ?>" type="checkbox" value="ok" <?php echo $showMyPosts ?> /> <label for="<?php echo $this->get_field_id('showMyPosts'); ?>"><?php _e('Show my posts link?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showNewPosts'); ?>" name="<?php echo $this->get_field_name('showNewPosts'); ?>" type="checkbox" value="ok" <?php echo $showNewPosts ?> /> <label for="<?php echo $this->get_field_id('showNewPosts'); ?>"><?php _e('Show new posts?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showProfile'); ?>" name="<?php echo $this->get_field_name('showProfile'); ?>" type="checkbox" value="ok" <?php echo $showProfile ?> /> <label for="<?php echo $this->get_field_id('showProfile'); ?>"><?php _e('Show User Control Panel link?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showPMs'); ?>" name="<?php echo $this->get_field_name('showPMs'); ?>" type="checkbox" value="ok" <?php echo $showPMs ?> /> <label for="<?php echo $this->get_field_id('showPMs'); ?>"><?php _e('Show PMs?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showACP'); ?>" name="<?php echo $this->get_field_name('showACP'); ?>" type="checkbox" value="ok" <?php echo $showACP ?> /> <label for="<?php echo $this->get_field_id('showACP'); ?>"><?php _e('Show Admin link?', 'wp-united'); ?></label></p>

		<?php
	}

}


class WPU_Forum_Nav_Block_Widget extends WP_Widget {
	
	public function __construct() {
		$widget_ops = array('classname' => 'wp-united-forum-navblock', 'description' => __('Shows the top phpBB prosilver forum navigation / breadcrumb bar. If you have template integration turned on, this will only appear on non-forum pages. Your forum must be using a prosilver-based theme for this to work.', 'wp-united') );
		$this->WP_Widget('wp-united-forum-navblock', __('WP-United Forum Navigation Bar', 'wp-united'), $widget_ops);
	}
	
	public function widget($args, $instance) {
		global $wpUnited;
		
		extract($args, EXTR_SKIP);
		
		$showSiteHome = $instance['showSiteHome'];
		$showMemberList = $instance['showMemberList'];
		$showRegisterLink = $instance['showRegisterLink'];
		$showStyleSwitcher = $instance['showStyleSwitcher'];
		$nativeCSS = $instance['nativeCSS'];
		
		if(!defined('WPU_BLOG_PAGE') || is_admin()) {
			return;
		}
		
		
		if (is_active_widget(false, false, $this->id_base) && !$wpUnited->should_do_action('template-w-in-p') && !$nativeCSS) {
			wpu_add_board_styles($showStyleSwitcher);
		}
		

		echo $before_widget;
		wpu_phpbb_nav_block("showSiteHome={$showSiteHome}&showMemberList={$showMemberList}&showRegisterLink={$showRegisterLink}&useNativeCSS={$nativeCSS}&showStyleSwitcher={$showStyleSwitcher}");	
		echo $after_widget;

	}
	
	public function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;

		$instance['showSiteHome'] 		= (strip_tags(stripslashes($new_instance['showSiteHome'])) 		== 	'ok')? 1 : 0;
		$instance['showMemberList'] 	= (strip_tags(stripslashes($new_instance['showMemberList'])) 	== 	'ok')? 1 : 0;
		$instance['showRegisterLink'] 	= (strip_tags(stripslashes($new_instance['showRegisterLink'])) 	== 	'ok')? 1 : 0;
		$instance['nativeCSS'] 			= (strip_tags(stripslashes($new_instance['nativeCSS'])) 		== 	'ok')? 1 : 0;
		$instance['showStyleSwitcher'] 	= (strip_tags(stripslashes($new_instance['showStyleSwitcher']))	== 	'ok')? 1 : 0;
		
		return $instance;
	}
	
	public function form($instance) {
		//widget form
		
		$instance = wp_parse_args( (array) $instance, array( 
			'showSiteHome'			=> 0, 
			'showMemberList'		=> 1, 
			'showRegisterLink' 		=> 1,
			'nativeCSS' 			=> 0,
			'showStyleSwitcher' 	=> 1
		));
		
		$showSiteHome		= (!empty($instance['showSiteHome'])) 		? 'checked="checked"' : '';
		$showMemberList 	= (!empty($instance['showMemberList'])) 	? 'checked="checked"' : '';
		$showRegisterLink 	= (!empty($instance['showRegisterLink'])) 	? 'checked="checked"' : '';
		$nativeCSS 			= (!empty($instance['nativeCSS'])) 			? 'checked="checked"' : '';
		$showStyleSwitcher 	= (!empty($instance['showStyleSwitcher'])) 	? 'checked="checked"' : '';

		?>
		
		<p><input id="<?php echo $this->get_field_id('showSiteHome'); ?>" name="<?php echo $this->get_field_name('showSiteHome'); ?>" type="checkbox" value="ok"  <?php echo $showSiteHome ?> /> <label for="<?php echo $this->get_field_id('showSiteHome'); ?>"><?php _e('Show forum index as home breadcrumb?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showMemberList'); ?>" name="<?php echo $this->get_field_name('showMemberList'); ?>" type="checkbox" value="ok" <?php echo $showMemberList ?> /> <label for="<?php echo $this->get_field_id('showMemberList'); ?>"><?php _e('Show member list?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showRegisterLink'); ?>" name="<?php echo $this->get_field_name('showRegisterLink'); ?>" type="checkbox" value="ok" <?php echo $showRegisterLink ?> /> <label for="<?php echo $this->get_field_id('showRegisterLink'); ?>"><?php _e('Show register link?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('nativeCSS'); ?>" name="<?php echo $this->get_field_name('nativeCSS'); ?>" type="checkbox" value="ok"  <?php echo $nativeCSS ?> /> <label for="<?php echo $this->get_field_id('nativeCSS'); ?>"><?php _e("Don't add CSS, I will style this myself", 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showStyleSwitcher'); ?>" name="<?php echo $this->get_field_name('showStyleSwitcher'); ?>" type="checkbox" value="ok"  <?php echo $showStyleSwitcher ?> /> <label for="<?php echo $this->get_field_id('showStyleSwitcher'); ?>"><?php _e("Include phpBB's style switcher?", 'wp-united'); ?></label></p>
		<?php
	}
}

/**
 * Displays the bottom forum bar, with WordPress breadcrub navigation
 * @conceived by *daniel
 * 
 */
class WPU_Forum_Nav_Block_Footer_Widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array('classname' => 'wp-united-forum-navblock-ftr', 'description' => __('Shows the bottom phpBB prosilver forum footer bar. If you have template integration turned on, this will only appear on non-forum pages. Your forum must be using a prosilver-based theme for this to work.', 'wp-united') );
		$this->WP_Widget('wp-united-forum-navblock-ftr', __('WP-United Forum Navigation Footer Bar', 'wp-united'), $widget_ops);
	}
	
	public function widget($args, $instance) {
		global $wpUnited;
		
		extract($args, EXTR_SKIP);
		
		$showSiteHome = $instance['showSiteHome'];
		$nativeCSS = $instance['nativeCSS'];
		
		if(!defined('WPU_BLOG_PAGE') || is_admin()) {
			return;
		}
		
		if (is_active_widget(false, false, $this->id_base) && !$wpUnited->should_do_action('template-w-in-p') && !$nativeCSS) {
			wpu_add_board_styles(true);
		}
	
		
		echo $before_widget;
		wpu_phpbb_nav_block_footer("showSiteHome={$showSiteHome}&useNativeCSS={$nativeCSS}");	
		echo $after_widget;

	}
	
	public function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;

		$instance['showSiteHome'] 		= (strip_tags(stripslashes($new_instance['showSiteHome'])) 		== 	'ok')? 1 : 0;
		$instance['nativeCSS'] 			= (strip_tags(stripslashes($new_instance['nativeCSS'])) 		== 	'ok')? 1 : 0;
		
		return $instance;
	}
	
	public function form($instance) {
		//widget form
		
		$instance = wp_parse_args( (array) $instance, array( 
			'showSiteHome'			=> 0, 
			'nativeCSS' 			=> 0
		));
		
		$showSiteHome		= (!empty($instance['showSiteHome'])) 		? 'checked="checked"' : '';
		$nativeCSS 			= (!empty($instance['nativeCSS'])) 			? 'checked="checked"' : '';

		?>
		<p><input id="<?php echo $this->get_field_id('showSiteHome'); ?>" name="<?php echo $this->get_field_name('showSiteHome'); ?>" type="checkbox" value="ok"  <?php echo $showSiteHome ?> /> <label for="<?php echo $this->get_field_id('showSiteHome'); ?>"><?php _e('Show forum index as home breadcrumb?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('nativeCSS'); ?>" name="<?php echo $this->get_field_name('nativeCSS'); ?>" type="checkbox" value="ok"  <?php echo $nativeCSS ?> /> <label for="<?php echo $this->get_field_id('nativeCSS'); ?>"><?php _e("Don't add CSS, I will style this myself", 'wp-united'); ?></label></p>
		<?php
	}	
}

class WPU_Forum_Birthdays_Widget extends WP_Widget {


	public function __construct() {
	
		$widget_ops = array('classname' => 'wp-united-forum-birthdays', 'description' => __('Shows a list of users whose birthday it is today. Note that users must be logged in to phpBB and birthday entry must be activated on your forum for this to work.', 'wp-united') );
		$this->WP_Widget('wp-united-forum-birthdays', __('WP-United User Birthdays', 'wp-united'), $widget_ops);
	
	}
	
	public function widget($args, $instance) {
		global $phpbbForum;
		
		extract($args, EXTR_SKIP);
		
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		$hideIfNothing = $instance['hideIfNothing'];
		
		$birthdays = $phpbbForum->get_birthday_list();
		
		if(($birthdays == '') && $hideIfNothing) {
			return;
		}
		
		$birthdays = ($birthdays == '') ? __('No users have birthdays today', 'wp-united') : $birthdays;
		
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo '<div class="wpuuserbirthdays textwidget">';
		echo $birthdays;
		echo'</div>';
		echo $after_widget;

	}
	
	public function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;
		
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['hideIfNothing'] 	= (strip_tags(stripslashes($new_instance['hideIfNothing'])) == 	'ok')? 1 : 0;
		
		return $instance;
	}
	
	public function form($instance) {
		//widget form
		
		$instance = wp_parse_args( (array) $instance, array( 
			'title' 			=> __('Happy Birthday To: ', 'wp-united'),
			'hideIfNothing'		=> 1
		));
		
		$title = strip_tags($instance['title']);
		$hideIfNothing	= (!empty($instance['hideIfNothing'])) 	? 'checked="checked"' : '';
		
		?>
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php  _e('Title: ', 'wp-united'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><input id="<?php echo $this->get_field_id('hideIfNothing'); ?>" name="<?php echo $this->get_field_name('hideIfNothing'); ?>" type="checkbox" value="ok"  <?php echo $hideIfNothing ?> /> <label for="<?php echo $this->get_field_id('hideIfNothing'); ?>"><?php _e('Hide widget if there is nothing to show?', 'wp-united'); ?></label></p>
		
		<?php
	}
}


/**
 * Wrapper function for initialising widgets
 */
function wpu_widgets_init() {
	
	register_widget('WPU_Login_User_info_Widget');
	register_widget('WPU_Latest_Phpbb_Topics_Widget');
	register_widget('WPU_Latest_Phpbb_Posts_Widget');
	register_widget('WPU_Forum_Stats_Widget');
	register_widget('WPU_Forum_Users_Online_Widget');
	register_widget('WPU_Useful_Forum_Links_Widget');
	register_widget('WPU_Forum_Nav_Block_Widget');
	register_widget('WPU_Forum_Nav_Block_Footer_Widget');
	register_widget('WPU_Forum_Birthdays_Widget');

}


// Done. Nothing else to see here.