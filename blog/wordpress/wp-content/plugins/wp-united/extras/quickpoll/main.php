<?php

/** 
*
* @package WP-United
* @version $Id: 0.9.1.5  2012/12/28 John Wells (Jhong) Exp $
* @copyright (c) 2006-2013 wp-united.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License  
* @author John Wells
*
* The quickpoll sub-plugin. This file is automatically loaded by the WP-United extras loader.
* 
*/

Class WP_United_Extra_quickpoll extends WP_United_Extra {

	public function on_init() {
		global $wpUnited;
		include_once($wpUnited->get_plugin_path() . 'extras/quickpoll/widget.php');
	}
	
	public function on_widget_init() {
		register_widget('WPU_Forum_Polls_Widget');
	}
	
	public function on_page_load() {
	
		if( isset($_POST['wpupoll']) && check_ajax_referer( 'wpu-poll-submit') ) {
			$this->get_poll();
			exit;
		}
	
	}
	
	
	/**
	 *
	 *	Fetch a list of active polls
	 */
	public function get_poll_list($forum_list = '', $limit = 50) {
		global $db, $auth, $wpUnited, $phpbbForum;
		
		$pollMarkup = '';
		
		$fStateChanged = $phpbbForum->foreground();

		$forums_check = array_unique(array_keys($auth->acl_getf('f_read', true))); //forums authorised to read posts in

		if (!sizeof($forums_check)) {
			return false;
		}
		$sql = '
			SELECT t.topic_id, t.topic_title, t.poll_title, t.poll_start, 
				t.forum_id, f.forum_name
			FROM ' . TOPICS_TABLE . ' AS t, ' . FORUMS_TABLE . ' AS f 
			WHERE ' . $db->sql_in_set('f.forum_id', $forums_check)  . ' 
				AND t.forum_id = f.forum_id 
				AND t.topic_status <> 2 
				AND t.poll_start > 0 
			ORDER BY t.topic_time DESC';
			
		if(!($result = $db->sql_query_limit($sql, $limit, 0))) {
			wp_die(__('Could not access the database.', 'wp-united'));
		}		

	
		$polls = $db->sql_fetchrowset($result);
		
		$db->sql_freeresult($result);
		$phpbbForum->restore_state($fStateChanged);
		return $polls;
	}
	
	
	
	/**
	 * 
	 * Displays a poll
	 * 
	 */
	public function get_poll($topicID = 0, $showLink = false, $template='prosilver') {
		 global $db, $user, $auth, $config, $phpEx, $wpUnited, $phpbbForum;
 
		 static $pollHasGenerated = false;

		 $fStateChanged = $phpbbForum->foreground();
 
		if(!$pollHasGenerated) {
			$user->add_lang('viewtopic');
			$pollHasGenerated = true;
		}
		
		$display = false;
		$ajax = false;
		$inboundVote = array();

		// Is this an AJAX request?
		if($topicID == 0) {
			$topicID = (int)request_var('pollid', 0);
			$template = (string)request_var('polltemplate', 'prosilver');
			$inboundVote = request_var('vote_id', array('' => 0));
			$display = ((int)request_var('display', 0) == 1);
			$ajax = ((int)request_var('ajax', 0) == 1);
			$showLink = ((int)request_var('showlink', 0) == 1);
			
		}
		if(!$topicID) {
			return '';
		}
		
		// Or was this form submitted without JS? If so, which poll was it for? (Unlike in phpBB, there could be more than one)
		if(!$ajax) {
			// submitted:
			if(isset($_POST['update']) && isset($_POST['vote_id'])) {
				$pollID = (int)request_var('pollid', 0);
				if($pollID == $topicID) {
					$inboundVote = request_var('vote_id', array('' => 0));
					// the same poll block could be on the page multiple times. We only want to register the vote once.
					unset($_POST['update']); unset($_POST['vote_id']);
				}
			}
			// view results link:
			if(isset($_GET['wpupolldisp'])) {
				$pollID = (int)request_var('pollid', 0);
				if($pollID == $topicID) {
					$display=1;
				}
			}
			
		}
		if(trim($template) == '') {
			$template = 'prosilver';
		}
		$currURL = wpu_get_curr_page_link();
		
		 $pollMarkup = '';
		 $actionMsg = '';
		 		 
		 $sql = '
			SELECT t.topic_id, t.topic_title, t.topic_status, t.poll_title, t.poll_start, t.poll_length, 
						t.poll_max_options, t.poll_last_vote, t.poll_vote_change, 
						p.bbcode_bitfield, p.bbcode_uid, 
						t.forum_id, u.user_id, f.forum_name, f.forum_status, u.username, u.user_colour, u.user_type
			FROM ' . TOPICS_TABLE . ' AS t, ' . USERS_TABLE . ' AS u, ' . FORUMS_TABLE . ' AS f, ' .
				POSTS_TABLE .  ' AS p
			WHERE t.topic_poster = u.user_id 
				AND t.forum_id = f.forum_id
				AND t.topic_id = ' . (int)$topicID . ' 
				AND p.post_id = t.topic_first_post_id';
				
		if(!($result = $db->sql_query($sql))) {
			$phpbbForum->restore_state($fStateChanged);
			wp_die(__('Could not access the database.', 'wp-united'));
		}		

		 
		$topicData = $db->sql_fetchrow($result);

		$db->sql_freeresult($result);
		
		if(!$topicData['poll_start'] || (!$auth->acl_get('f_read', $topicData['forum_id']))) {
			$phpbbForum->restore_state($fStateChanged);
			return $pollMarkup;
		}
		
		$pollOptions = array();
		$sql = '
			SELECT * 
			FROM ' . POLL_OPTIONS_TABLE . ' 
			WHERE topic_id = ' . (int)$topicID;
			
		$result = $db->sql_query($sql);
		
		while ($row = $db->sql_fetchrow($result)) {
			$pollOptions[] = $row;
		}
		

		$db->sql_freeresult($result);
		
		$currVotedID = array();
		if ($user->data['is_registered']) {
		
			$sql = '
				SELECT poll_option_id
				FROM ' . POLL_VOTES_TABLE . '
				WHERE topic_id = ' . (int)$topicID . '
				AND vote_user_id = ' . $user->data['user_id'];
		
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result)) {
				$currVotedID[] = $row['poll_option_id'];
			}
			$db->sql_freeresult($result);
		} else {
			// Cookie based guest tracking ... 
			if (isset($_COOKIE[$config['cookie_name'] . '_poll_' . $topicID])) {
				$currVotedID = explode(',', $_COOKIE[$config['cookie_name'] . '_poll_' . $topicID]);
				$currVotedID = array_map('intval', $currVotedID);
			}
		}
		
		// Can not vote at all if no vote permission
		$userCanVote = (
			$auth->acl_get('f_vote', $topicData['forum_id']) &&
			(
				($topicData['poll_length'] != 0 && $topicData['poll_start'] + $topicData['poll_length'] > time()) || 
				($topicData['poll_length'] == 0)
			) &&
			$topicData['topic_status'] != ITEM_LOCKED &&
			$topicData['forum_status'] != ITEM_LOCKED &&
			(
				!sizeof($currVotedID) ||
				($auth->acl_get('f_votechg', $topicData['forum_id']) && $topicData['poll_vote_change'])
			)
		)? true : false;
	
		$displayResults = (
			!$userCanVote || 
			($userCanVote && sizeof($currVotedID)) || 
			$display
		) ? true : false;

		if(sizeof($inboundVote) && $userCanVote) {
			//  ********   register vote here ********

			if (sizeof($inboundVote) > $topicData['poll_max_options'] || in_array(VOTE_CONVERTED, $currVotedID)){
				
				if (!sizeof($inboundVote)) {
					$actionMsg = $user->lang['NO_VOTE_OPTION'];
				} else if (sizeof($inboundVote) > $topicData['poll_max_options']) {
					$actionMsg = $user->lang['TOO_MANY_VOTE_OPTIONS'];
				} else if (in_array(VOTE_CONVERTED, $currVotedID)) {
					$actionMsg = $user->lang['VOTE_CONVERTED'];
				} 	
			} else {

				foreach ($inboundVote as $option) {
					if (in_array($option, $currVotedID)) {
						continue;
					}

					$sql = '
						UPDATE ' . POLL_OPTIONS_TABLE . '
						SET poll_option_total = poll_option_total + 1
						WHERE poll_option_id = ' . (int) $option . '
							AND topic_id = ' . (int) $topicID;
							
					$db->sql_query($sql);

					if ($user->data['is_registered']) {
						$sql_ary = array(
							'topic_id'			=> (int) $topicID,
							'poll_option_id'	=> (int) $option,
							'vote_user_id'		=> (int) $user->data['user_id'],
							'vote_user_ip'		=> (string) $user->ip,
						);
						$sql = 'INSERT INTO ' . POLL_VOTES_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
						$db->sql_query($sql);
					}
				}

				foreach ($currVotedID as $option) {
					if (!in_array($option, $inboundVote)) {
						$sql = '
							UPDATE ' . POLL_OPTIONS_TABLE . '
							SET poll_option_total = poll_option_total - 1
							WHERE poll_option_id = ' . (int) $option . '
								AND topic_id = ' . (int) $topicID;
						$db->sql_query($sql);

						if ($user->data['is_registered']) {
							$sql = '
								DELETE FROM ' . POLL_VOTES_TABLE . '
								WHERE topic_id = ' . (int) $topicID . '
									AND poll_option_id = ' . (int) $option . '
									AND vote_user_id = ' . (int) $user->data['user_id'];
							$db->sql_query($sql);
						}
					}
				}

				if (($user->data['user_id'] == ANONYMOUS) && !$user->data['is_bot']) {
					$user->set_cookie('poll_' . $topicID, implode(',', $inboundVote), time() + 31536000);
				}

				$sql = '
					UPDATE ' . TOPICS_TABLE . '
					SET poll_last_vote = ' . time() . "
					WHERE topic_id = $topicID";
				$db->sql_query($sql);

				$actionMsg = $user->lang['VOTE_SUBMITTED'] . '<br />';
				
				
				// Reload vote state:
				$pollOptions = array();
				 $sql = '
					SELECT * 
					FROM ' . POLL_OPTIONS_TABLE . ' 
					WHERE topic_id = ' . (int)$topicID;
					
				$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result)) {
					$pollOptions[] = $row;
				}
				$db->sql_freeresult($result);
				$currVotedID = $inboundVote;
				$userCanVote = ($auth->acl_get('f_votechg', $topicData['forum_id']) && $topicData['poll_vote_change']);
				$displayResults = true;
			}
			
			// ***** end of vote registration ******
		}
		
		$pollTotal = 0;
		foreach ($pollOptions as $pollOption) {
			$pollTotal += $pollOption['poll_option_total'];
		}
		
		$pollBBCode = false;
		if($topicData['bbcode_bitfield']) {
			require_once($wpUnited->get_setting('phpbb_path') . 'includes/functions_posting.' . $phpEx);
			require_once($wpUnited->get_setting('phpbb_path') . 'includes/bbcode.' . $phpEx);
			$pollBBCode = new bbcode();
		}

		for ($i = 0, $size = sizeof($pollOptions); $i < $size; $i++) {
			$pollOptions[$i]['poll_option_text'] = censor_text($pollOptions[$i]['poll_option_text']);

			if ($pollBBCode !== false) {
				$pollBBCode->bbcode_second_pass($pollOptions[$i]['poll_option_text'], $topicData['bbcode_uid'], $topicData['bbcode_bitfield']);
			}

			$pollOptions[$i]['poll_option_text'] = bbcode_nl2br($pollOptions[$i]['poll_option_text']);
			$pollOptions[$i]['poll_option_text'] = $phpbbForum->parse_phpbb_text_for_smilies($pollOptions[$i]['poll_option_text']);
		}

		$topicData['poll_title'] = $phpbbForum->censor($topicData['poll_title']);

		if ($pollBBCode !== false) {
			$pollBBCode->bbcode_second_pass($topicData['poll_title'], $topicData['bbcode_uid'], $topicData['bbcode_bitfield']);
		}

		$topicData['poll_title'] = bbcode_nl2br($topicData['poll_title']);
		$topicData['poll_title'] = $phpbbForum->parse_phpbb_text_for_smilies($topicData['poll_title']);

		unset($pollBBCode);
		
		$pollEnd = $topicData['poll_length'] + $topicData['poll_start'];
		$pollLength = ($topicData['poll_length']) ? sprintf($user->lang[($pollEnd > time()) ? 'POLL_RUN_TILL' : 'POLL_ENDED_AT'], $user->format_date($pollEnd)) : '';
		$topicLink = ($phpbbForum->seo) ? "topic{$topicID}.html" : "viewtopic.$phpEx?t={$topicID}";
		
		$pTemplate = new template();
		$pTemplate->set_custom_template($wpUnited->get_plugin_path() . 'extras/quickpoll/templates/', 'wpupoll');
		$pTemplate->set_filenames(array('poll' => "{$template}.html"));
		
		$pTemplate->assign_vars(array(
			'POLL_QUESTION'		=> $topicData['poll_title'],
			'TOTAL_VOTES' 		=> $pollTotal,
			'POLL_LEFT_CAP_IMG'	=> str_replace($wpUnited->get_setting('phpbb_path'), $phpbbForum->get_board_url(), $user->img('poll_left')),
			'POLL_RIGHT_CAP_IMG'=> str_replace($wpUnited->get_setting('phpbb_path'), $phpbbForum->get_board_url(), $user->img('poll_right')),
			'POLL_ID'			=> $topicID,
			'L_MAX_VOTES'		=> ($topicData['poll_max_options'] == 1) ? $user->lang['MAX_OPTION_SELECT'] : sprintf($user->lang['MAX_OPTIONS_SELECT'], $topicData['poll_max_options']),
			'L_POLL_LENGTH'		=> $actionMsg . $pollLength,
			
			'POLL_TEMPLATE'		=> $template,
			
			'S_CAN_VOTE'		=> $userCanVote,
			'S_DISPLAY_RESULTS'	=> $displayResults,
			
			'S_SHOW_LINK'		=> $showLink,
			'U_TOPIC_LINK'		=> $phpbbForum->get_board_url() . $topicLink,
			'L_TOPIC_LINK'		=> __('View poll in forum', 'wp-united'),
			
			'S_IS_MULTI_CHOICE'	=> ($topicData['poll_max_options'] > 1) ? true : false,
			'S_POLL_ACTION'		=> $currURL,
			'U_VIEW_RESULTS'	=> (!strstr($currURL, '?')) ? $currURL . '?wpupolldisp=1' : $currURL . '&amp;wpupolldisp=1'
		));
		
		foreach ($pollOptions as $pollOption) {
			$optionPct = ($pollTotal > 0) ? $pollOption['poll_option_total'] / $pollTotal : 0;
			$optionPctTxt = sprintf("%.1d%%", round($optionPct * 100));

			$pTemplate->assign_block_vars('poll_option', array(
				'POLL_OPTION_ID' 		=> $pollOption['poll_option_id'],
				'POLL_OPTION_CAPTION' 	=> $pollOption['poll_option_text'],
				'POLL_OPTION_RESULT' 	=> $pollOption['poll_option_total'],
				'POLL_OPTION_PERCENT' 	=> $optionPctTxt,
				'POLL_OPTION_PCT'		=> round($optionPct * 100),
				'POLL_OPTION_IMG' 		=> str_replace($wpUnited->get_setting('phpbb_path'), $phpbbForum->get_board_url(), $user->img('poll_center', $optionPctTxt, round($optionPct * 250))),
				'POLL_OPTION_VOTED'		=> (in_array($pollOption['poll_option_id'], $currVotedID)) ? true : false)
			);
		}
			
		ob_start();
		$pTemplate->display('poll');
		$pollMarkup = ob_get_contents();
		unset($pTemplate);
		ob_end_clean();
		
		
		$phpbbForum->restore_state($fStateChanged);

		if($ajax) {
			wpu_ajax_header();
			echo '<wpupoll>';
			echo '<newnonce>' . wp_create_nonce('wpu-poll-submit') . '</newnonce>';
			echo '<pollid>' . $topicID . '</pollid>';
			echo '<markup><![CDATA[' . base64_encode($pollMarkup) . ']]></markup>';
			echo '</wpupoll>';
			exit;
		}

		return $pollMarkup;
	}

}

// end of quickpoll extra