<?php

/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* The polls widget
* 
*/

class WPU_Forum_Polls_Widget extends WP_Widget {

	private 
		$parentExtra = false,
		$addedPollScript = false;

	public function __construct() {
		global $wpUnited;

		$this->parentExtra = $wpUnited->get_extra('quickpoll');

		if(!is_object($this->parentExtra)) {
			return;
		}
		
		$widget_ops = array('classname' => 'wp-united-forum-polls', 'description' => __('Displays a selected poll from your forum. Users must have the relevant permissions to view and/or vote on the poll.', 'wp-united') );
		$this->WP_Widget('wp-united-forum-polls', __('WP-United Forum Quick Poll', 'wp-united'), $widget_ops);
	
	}
	
	public function widget($args, $instance) {
		global $wpUnited, $phpbbForum;
		
		extract($args, EXTR_SKIP);
		
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
		$pollId = $instance['pollId'];
		$hideIfNoPerms = $instance['hideIfNoPerms'];
		$showTopicLink = $instance['showTopicLink'];
		$nativeCSS = $instance['nativeCSS'];
		$template = $instance['useTemplate'];
		
		if($template == 'guess') {
			$template = $phpbbForum->guess_style_type();
		}
		
		$poll = $this->parentExtra->get_poll($pollId, $showTopicLink, $template);

		
		if((($poll == '') && $hideIfNoPerms) || is_admin()) {
			return;
		}
		
		if (!empty($poll) && is_active_widget(false, false, $this->id_base) && !$wpUnited->should_do_action('template-w-in-p') && !$nativeCSS) {
			wpu_add_board_styles(false);
		}
		
		$poll = ($poll == '') ? __('You do not have permission to view this poll', 'wp-united') : $poll;
		$isleClass = ($nativeCSS) ? 'wpunative' : 'wpuisle';
		$linkClass = ($showTopicLink) ? 'wpushowlink ' : '';
		$isleClass = $linkClass . $isleClass;
		echo $before_widget;
		echo $before_title . $title . $after_title; ?>
		<div class="wpuldg" style="display: none;position: relative; padding-top: 12px;">
			<?php _e('Loading.', 'wp-united'); ?> <?php _e('Please wait...', 'wp-united'); ?>
			<img src="<?php _e($wpUnited->get_plugin_url()); ?>images/settings/wpuldg.gif" />
		</div>
		<div class="wpuquickpoll <?php echo $isleClass; ?> textwidget wpupoll-<?php echo $pollId; ?>"><div class="<?php echo $isleClass; ?>2">
			<?php echo $poll; ?>
		</div></div>
		<?php $this->add_poll_script(); 
		echo $after_widget;
		
	}
	
	public function update($new_instance, $old_instance) {
		//save the widget
		$instance = $old_instance;
		
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['pollId'] 	= (int)($new_instance['pollId']);
		$instance['hideIfNoPerms'] 	= (strip_tags(stripslashes($new_instance['hideIfNoPerms'])) == 	'ok')? 1 : 0;
		$instance['showTopicLink'] 	= (strip_tags(stripslashes($new_instance['showTopicLink'])) == 	'ok')? 1 : 0;
		$instance['nativeCSS'] 		= (strip_tags(stripslashes($new_instance['nativeCSS'])) == 	'ok')? 	1 : 0;
		$instance['useTemplate'] 	= (string)strip_tags(stripslashes($new_instance['useTemplate']));

		if(trim($instance['useTemplate']) == '') {
			$instance['useTemplate'] == 'prosilver';
		}

		return $instance;
	}
	
	public function form($instance) {
		//widget form
		
		$instance = wp_parse_args( (array) $instance, array( 
			'title' 			=> __('Quick Poll', 'wp-united'),
			'pollId'			=> 0,
			'hideIfNoPerms'		=> 1,
			'showTopicLink'		=> 1,
			'nativeCSS'			=> 0,
			'useTemplate'		=> 'guess'
		));
		
		$title = strip_tags($instance['title']);
		$hideIfNoPerms	= (!empty($instance['hideIfNoPerms'])) 	? 'checked="checked"' : '';
		$showTopicLink	= (!empty($instance['showTopicLink'])) 	? 'checked="checked"' : '';
		$nativeCSS		= (!empty($instance['nativeCSS'])) 		? 'checked="checked"' : '';
		$template 		= ((string)$instance['useTemplate']);
		
		if(empty($template)) {
			$template = 'guess';
		}
		
		$pollId = $instance['pollId'];
		
		$polls = $this->parentExtra->get_poll_list();
		
		?>
		
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php  _e('Title: ', 'wp-united'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('pollId'); ?>"><?php _e('Select a poll: ', 'wp-united'); ?></label><br />
			<select name="<?php echo $this->get_field_name('pollId'); ?>" id="<?php echo $this->get_field_name('pollId'); ?>">
				<option value="0">--- <?php _e('No poll selected', 'wp-united'); ?> ---</option>
					<?php
						foreach($polls as $pollIndex => $pollData) {
							$isSelected = ($pollData['topic_id'] == $pollId) ? ' selected="selected" ' : '';
							$pollText = sprintf(__("'%1\$s', in topic: '%2\$s'", 'wp-united'), $pollData['poll_title'], $pollData['topic_title']);
							$pollText = (strlen($pollText) > 38) ? substr($pollText, 0, 38) . '&hellip;' : $pollText;
							echo '<option value="' . $pollData['topic_id'] . '"' . $isSelected . '>' . $pollText . '</option>';
						}
					?>
			</select>
		</p>
		<p><input id="<?php echo $this->get_field_id('hideIfNoPerms'); ?>" name="<?php echo $this->get_field_name('hideIfNoPerms'); ?>" type="checkbox" value="ok"  <?php echo $hideIfNoPerms ?> /> <label for="<?php echo $this->get_field_id('hideIfNoPerms'); ?>"><?php _e('Hide widget if the user has no permissions to view this poll?', 'wp-united'); ?></label></p>
		<p><input id="<?php echo $this->get_field_id('showTopicLink'); ?>" name="<?php echo $this->get_field_name('showTopicLink'); ?>" type="checkbox" value="ok"  <?php echo $showTopicLink ?> /> <label for="<?php echo $this->get_field_id('showTopicLink'); ?>"><?php _e('Show a link to the poll topic?', 'wp-united'); ?></label></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('useTemplate'); ?>"><?php _e('Use template: ', 'wp-united'); ?></label><br />
			<select name="<?php echo $this->get_field_name('useTemplate'); ?>" id="<?php echo $this->get_field_name('useTemplate'); ?>">
				<option value="guess" <?php if($template == 'guess'){ ?>selected="selected"<?php } ?>><?php _e('Make best guess for user', 'wp-united'); ?></option>
				<option value="prosilver" <?php if($template == 'prosilver') { ?>selected="selected"<?php } ?>><?php _e('Force prosilver', 'wp-united'); ?></option>
				<option value="subsilver2" <?php if($template == 'subsilver2') { ?>selected="selected"<?php } ?>><?php _e('Force subsilver2', 'wp-united'); ?></option>
			</select>
		</p>
		
		<p><input id="<?php echo $this->get_field_id('nativeCSS'); ?>" name="<?php echo $this->get_field_name('nativeCSS'); ?>" type="checkbox" value="ok"  <?php echo $nativeCSS ?> /> <label for="<?php echo $this->get_field_id('nativeCSS'); ?>"><?php _e("Don't add CSS, I will style this myself", 'wp-united'); ?></label></p>
		<?php
	}
	
	
	public function add_poll_script() {
		global $wpUnited;
		
		if($this->addedPollScript) {
			return;
		}
		
		$this->addedPollScript = true;
		
		$pollNonce = wp_create_nonce('wpu-poll-submit');
		
		wp_enqueue_script(
			'wpu-poll', 
			$wpUnited->get_plugin_url() . 'extras/quickpoll/js/poll.js', 
			array( 
					'jquery-effects-core',
					'jquery'
				), 
				$wpUnited->get_version(), 
				false
			);	
		
		?>
			<script type="text/javascript">//<![CDATA[
				var wpuPollNonce = '<?php echo $pollNonce; ?>';
				var wpuHomeURL = '<?php echo $wpUnited->get_wp_home_url(); ?>';
			// ]]>
			</script>

		<?php
	
	
	}
}

// Done. End of file.