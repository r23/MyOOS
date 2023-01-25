<?php defined( 'ABSPATH' ) or die( 'forbidden' );
# (C) 2023 axew3.com
/*
 All default phpBB (3.3>) notifications types that can be expected to be retrieved:
type.post_in_queue
type.approve_post
type.pm
type.topic
type.group_request
type.approve_topic
type.quote
type.bookmark
type.post
type.admin_activate_user
type.group_request_approved
type.report_pm
type.report_post_closed
type.disapprove_post
type.disapprove_topic
type.forum

 See: $w3all_phpbb_unotifications query into -> /class.wp.w3all-phpbb.php -> private static function verify_phpbb_credentials()
*/
 global $w3all_url_to_cms;

     $notifi_lang_types_ary = array(
      'new_topic'  => __('<b>New topic</b> by ', 'wp-w3all-phpbb-integration'),
      'topic_approval'  => __('<b>Topic approval</b> request by ', 'wp-w3all-phpbb-integration'),
      'topic__approval' => __('<b>Topic approval</b> request by ', 'wp-w3all-phpbb-integration'),
      'quoted_by'  => __('<b>Quoted by</b> ', 'wp-w3all-phpbb-integration'),
      'bookmarked_topic'  => __('<b>Bookmarked Topic.</b> Reply from ', 'wp-w3all-phpbb-integration'),
      'reply_from'  => __('<b>Reply</b> from ', 'wp-w3all-phpbb-integration'),
      'post_approval'  => __('<b>Post approval request</b> by ', 'wp-w3all-phpbb-integration'),
      'group_request'  => __('<b>Group request</b> from ', 'wp-w3all-phpbb-integration'),
      'post_inqueue'  => __('<b>Post approval request</b> by ', 'wp-w3all-phpbb-integration'),
      'post_reported'  => __('<b>Post reported:</b> ', 'wp-w3all-phpbb-integration'),
      'priv_msg'  => __('<b>Private Message</b> from ', 'wp-w3all-phpbb-integration'),
      'activation_required' => __('<b>Activation required</b> for deactivated or newly registered user: ', 'wp-w3all-phpbb-integration'),
      'group_request_approved' => __('<b>Group request approved</b> to join the group ', 'wp-w3all-phpbb-integration'),
      'priv_msg_reported' => __('<b>Private Message reported:</b> ', 'wp-w3all-phpbb-integration'),
      'priv_msg_reported_closed' => __('<b>Private Message report closed:</b>', 'wp-w3all-phpbb-integration'),
      'report_post_closed' => __('<b>Report closed for post:</b> ', 'wp-w3all-phpbb-integration'),
      'topic_disapproved'  => __('<b>Topic disapproved:</b> ', 'wp-w3all-phpbb-integration'),
      'post_disapproved'  => __('<b>Post disapproved:</b> ', 'wp-w3all-phpbb-integration'),
      "on" => __('on ', 'wp-w3all-phpbb-integration'),
      "forum" => __('Forum: ', 'wp-w3all-phpbb-integration'),
      "reason" => __('Reason: ', 'wp-w3all-phpbb-integration'),
     );

 // see: /wp-w3all-phpbb-integration/class.wp.w3all-phpbb.php
 // private static function verify_phpbb_credentials(){
 // global $w3all_phpbb_unotifications

 # type.post_in_queue before type.post, or type.post will always match. Same goes for type.topic_in_queue and type.topic (due to strpos())
 # so the order here is !important

  if(!empty($w3all_phpbb_unotifications))
  {

    echo '<ul class="'.$ul_phpbb_unotifications_class.'">';

    foreach( $w3all_phpbb_unotifications as $nnn )
    {

      echo '<li class="'.$li_phpbb_unotifications_class.'">';

     foreach( $nnn as $nn => $n )
     {
       if ( $nn == 'notification_type_name' && strpos($n,'type.approve_topic')
           OR $nn == 'notification_type_name' && strpos($n,'type.topic_in_queue') )
        {
             $n_url = $w3all_url_to_cms . '/viewtopic.php?t='.$nnn->topic_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['topic_approval'] . $nnn->username . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a>' . '<br />Forum: ' . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.topic') )
        { // after type.topic_in_queue or type.topic will match!
             $n_url = $w3all_url_to_cms . '/viewtopic.php?t='.$nnn->topic_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['new_topic'] . $nnn->username . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a>' . '<br />' . $notifi_lang_types_ary["forum"] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.quote') )
        {
             $n_url = $w3all_url_to_cms . '/viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['quoted_by'] . $nnn->username . ' in:<br /><a href="'.$n_url.'">' . $nd["topic_title"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.bookmark') )
        {
             $n_url = $w3all_url_to_cms . '/viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['bookmarked_topic'] . $nnn->username . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a>' . '<br />' . $notifi_lang_types_ary["forum"] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.post_in_queue')
                   OR $nn == 'notification_type_name' && strpos($n,'type.approve_post') )
        {
             $n_url = $w3all_url_to_cms . '/viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             $pu = empty($nd["post_username"]) ? $nnn->username : $nd["post_username"]; // or will display 'Anonimous' if from a guest
             echo $notifi_lang_types_ary['post_approval'] . $pu . ':<br /><a href="'.$n_url.'">' . $nnn->post_subject . '</a><br />'. $notifi_lang_types_ary['forum'] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        }
        elseif( $nn == 'notification_type_name' && strpos($n,'type.post') )
        {
             $n_url = $w3all_url_to_cms . '/viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['reply_from'] . $nnn->username . ' in topic:<br /><a href="'.$n_url.'">' . $nd["topic_title"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.group_request') )
        {
             $nd = unserialize($nnn->notification_data);
             $n_url = $w3all_url_to_cms . '/ucp.php?i=ucp_groups&mode=manage&action=list&g='.$nnn->item_parent_id;
             echo $notifi_lang_types_ary['group_request'] . $nnn->username . ' to join the group: <a href="'.$n_url.'">' . $nd["group_name"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.pm') )
        {
             $n_url = $w3all_url_to_cms . '/ucp.php?i=pm&mode=view&f=0&p='.$nnn->item_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['priv_msg'] . $nnn->username . ': <a href="'.$n_url.'">' . $nd["message_subject"] . '</a>' . '<br />' . date('D M j, G:i a', $nnn->notification_time);


        } elseif( $nn == 'notification_type_name' && strpos($n,'type.admin_activate_user') )
        {
             $n_url = $w3all_url_to_cms . '/memberlist.php?mode=viewprofile&u='.$nnn->item_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['activation_required'] . ' <a href="'.$n_url.'">' . $nnn->username . '</a>' . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.group_request_approved') )
        {
             $nd = unserialize($nnn->notification_data);
             $n_url = $w3all_url_to_cms . '/memberlist.php?mode=group&g='.$nnn->item_id;
             echo $notifi_lang_types_ary['group_request_approved'] . '<a href="'.$n_url.'">' . $nd["group_name"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.report_pm') )
        {
             $n_url = $w3all_url_to_cms . '/mcp.php?r='.$nnn->item_parent_id.'&i=pm_reports&mode=pm_report_details';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['priv_msg_reported'] . '<a href="'.$n_url.'">' . $nd["message_subject"] . '</a><br />' . $notifi_lang_types_ary["reason"] . $nd["report_text"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.report_pm_closed') )
        {
             $n_url = $w3all_url_to_cms . '/ucp.php?i=ucp_notifications&mode=notification_list';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['priv_msg_reported_closed'] . ' <a href="'.$n_url.'">' . $nd["message_subject"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.report_post_closed') ) // notification.type.report_post_closed
        {
             $n_url = $w3all_url_to_cms . '/mcp.php?i=mcp_reports';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['report_post_closed'] . ' <a href="'.$n_url.'">' . $nd["post_subject"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.forum') )
        {
             $n_url = $w3all_url_to_cms . '/viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['reply_from'] . $nnn->username . ' in topic:<br /><a href="'.$n_url.'">' . $nd["topic_title"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.report_post') )
        {
             $n_url = $w3all_url_to_cms . '/viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             $reason = ( !empty($nd['report_text']) ? $notifi_lang_types_ary['reason'] . $nd['report_text'] : $nd['reason_title'] );
             echo $notifi_lang_types_ary['post_reported'] . '<br /><a href="'.$n_url.'">' . $nd["topic_title"] . '</a><br />' . $reason  . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.disapprove_topic') )
        {
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['topic_disapproved'] . $nd['topic_title'] . '<br />' . $notifi_lang_types_ary['forum'] . $nd['forum_name'] . '<br />' . $notifi_lang_types_ary['reason'] . $nd['disapprove_reason'];

        } elseif( $nn == 'notification_type_name' && strpos($n,'type.disapprove_post') )
        {
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary['post_disapproved'] . $nd['topic_title'] . '<br />' . $notifi_lang_types_ary['forum'] . $nd['forum_name'] . '<br />' . $notifi_lang_types_ary['reason'] . $nd['disapprove_reason'];
        }

      } // END foreach

      echo '</li>';

     } // END foreach

    echo '</ul>';

   } // END if(!empty($w3all_phpbb_unotifications))
