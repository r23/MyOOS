<?php defined( 'ABSPATH' ) or die( 'forbidden' );

 global $w3all_url_to_cms;

     $notifi_lang_types_ary = array(
      1  => __('<b>New topic</b> by ', 'wp-w3all-phpbb-integration'),
      2  => __('<b>Topic approval</b> request by ', 'wp-w3all-phpbb-integration'),
      3  => __('<b>Quoted by</b> ', 'wp-w3all-phpbb-integration'),
      4  => __('<b>Bookmarked Topic.</b> Reply from ', 'wp-w3all-phpbb-integration'),
      5  => __('<b>Reply</b> from ', 'wp-w3all-phpbb-integration'),
      6  => __('This is the phrase for TYPE 6', 'wp-w3all-phpbb-integration'),
      7  => __('<b>Group request</b> from ', 'wp-w3all-phpbb-integration'),
      8  => __('<b>Post approval request</b> by ', 'wp-w3all-phpbb-integration'),
      9  => __('<b>Post reported:</b> ', 'wp-w3all-phpbb-integration'),
      10 => __('<b>Topic approval</b> request by ', 'wp-w3all-phpbb-integration'),
      11 => __('<b>Private Message</b> from ', 'wp-w3all-phpbb-integration'),
      12 => __('<b>Activation required</b> for deactivated or newly registered user: ', 'wp-w3all-phpbb-integration'),
      13 => __('This is the phrase for TYPE 13', 'wp-w3all-phpbb-integration'),
      14 => __('"This is the phrase for TYPE 14', 'wp-w3all-phpbb-integration'),
      15 => __('<b>Group request approved</b> to join the group ', 'wp-w3all-phpbb-integration'),
      16 => __('<b>Private Message reported:</b> ', 'wp-w3all-phpbb-integration'),
      17 => __('<b>Private Message report closed:</b>', 'wp-w3all-phpbb-integration'),
      18 => __('<b>Report closed for post:</b> ', 'wp-w3all-phpbb-integration'),
      19 => __('This is the phrase for TYPE 19', 'wp-w3all-phpbb-integration'),
      20 => __('This is the phrase for TYPE 20', 'wp-w3all-phpbb-integration'),
      21 => __('This is the phrase for TYPE 21', 'wp-w3all-phpbb-integration'),
      22 => __('This is the phrase for TYPE 22', 'wp-w3all-phpbb-integration'),
      23 => __('This is the phrase for TYPE 23', 'wp-w3all-phpbb-integration'),
      "on" => __('on ', 'wp-w3all-phpbb-integration'),
      "forum" => __('Forum: ', 'wp-w3all-phpbb-integration'),
      "reason" => __('Reason: ', 'wp-w3all-phpbb-integration'),
     );


 // see: /wp-w3all-phpbb-integration/class.wp.w3all-phpbb.php
 // private static function verify_phpbb_credentials(){
 // global $w3all_phpbb_unotifications

  if(!empty($w3all_phpbb_unotifications))
  {

    echo '<ul class="'.$ul_phpbb_unotifications_class.'">';

    foreach( $w3all_phpbb_unotifications as $nnn )
    {

      echo '<li class="'.$li_phpbb_unotifications_class.'">';

     foreach( $nnn as $nn => $n )
     {
       // Table: phpbb_notification_types

       if( $nn == 'notification_type_id' && $n == 1 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?t='.$nnn->topic_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[1] . $nnn->username . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a>' . '<br />' . $notifi_lang_types_ary["forum"] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 2 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?t='.$nnn->topic_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[10] . $nnn->username . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a>' . '<br />Forum: ' . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 3 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[3] . $nnn->username . ' in:<br /><a href="'.$n_url.'">' . $nd["topic_title"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 4 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[4] . $nnn->username . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a>' . '<br />' . $notifi_lang_types_ary["forum"] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 5 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[5] . $nnn->username . ' in topic:<br /><a href="'.$n_url.'">' . $nd["topic_title"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 6 )
        {
         //echo 'notification_type_id 6 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 7 )
        {
             $nd = unserialize($nnn->notification_data);
             $n_url = $w3all_url_to_cms . 'ucp.php?i=ucp_groups&mode=manage&action=list&g='.$nnn->item_parent_id;
             echo $notifi_lang_types_ary[7] . $nnn->username . ' to join the group: <a href="'.$n_url.'">' . $nd["group_name"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 8 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?p='.$nnn->post_id.'#p'.$nnn->post_id;
             $nd = unserialize($nnn->notification_data);
             $pu = empty($nd["post_username"]) ? $nnn->username : $nd["post_username"]; // or will display 'Anonimous' if from a guest
             echo $notifi_lang_types_ary[8] . $pu . ':<br /><a href="'.$n_url.'">' . $nnn->post_subject . '</a><br />'. $notifi_lang_types_ary['forum'] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 9 )
        {
             $n_url = $w3all_url_to_cms . 'ucp.php?i=mcp_reports';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[9] . ' <a href="'.$n_url.'">' . $nd["post_subject"] . '</a><br />Reason: ' . $nd["report_text"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);


        } elseif ( $nn == 'notification_type_id' && $n == 10 )
        {
             $n_url = $w3all_url_to_cms . 'viewtopic.php?t='.$nnn->topic_id;
             $nd = unserialize($nnn->notification_data);
             $pu = empty($nd["post_username"]) ? $nnn->username : $nd["post_username"]; // or will display 'Anonimous' if from a guest
             echo $notifi_lang_types_ary[10] . $pu . ': <a href="'.$n_url.'">' . $nnn->post_subject . '</a><br />' . $notifi_lang_types_ary["forum"] . $nd["forum_name"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 11 )
        {
             $n_url = $w3all_url_to_cms . 'ucp.php?i=pm&mode=view&f=0&p='.$nnn->item_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[11] . $nnn->username . ': <a href="'.$n_url.'">' . $nd["message_subject"] . '</a>' . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 12 )
        {
             $n_url = $w3all_url_to_cms . 'memberlist.php?mode=viewprofile&u='.$nnn->item_id;
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[12] . ' <a href="'.$n_url.'">' . $nnn->username . '</a>' . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 13 )
        {
         //echo 'notification_type_id 13 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 14 )
        {
         //echo 'notification_type_id 14 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 15 )
        {
             $nd = unserialize($nnn->notification_data);
             $n_url = $w3all_url_to_cms . 'memberlist.php?mode=group&g='.$nnn->item_id;
             echo $notifi_lang_types_ary[15] . '<a href="'.$n_url.'">' . $nd["group_name"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 16 )
        {
             $n_url = $w3all_url_to_cms . 'mcp.php?r='.$nnn->item_parent_id.'&i=pm_reports&mode=pm_report_details';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[16] . '<a href="'.$n_url.'">' . $nd["message_subject"] . '</a><br />' . $notifi_lang_types_ary["reason"] . $nd["report_text"] . '<br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 17 )
        {
             $n_url = $w3all_url_to_cms . 'ucp.php?i=ucp_notifications&mode=notification_list';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[17] . ' <a href="'.$n_url.'">' . $nd["message_subject"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 18 ) // notification.type.report_post_closed
        {
             $n_url = $w3all_url_to_cms . 'mcp.php?i=mcp_reports';
             $nd = unserialize($nnn->notification_data);
             echo $notifi_lang_types_ary[18] . ' <a href="'.$n_url.'">' . $nd["post_subject"] . '</a><br />' . date('D M j, G:i a', $nnn->notification_time);

        } elseif ( $nn == 'notification_type_id' && $n == 19 )
        {
         //echo 'notification_type_id 19 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 20 )
        {
         //echo 'notification_type_id 20 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 21 )
        {
         //echo 'notification_type_id 21 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 22 )
        {
         //echo 'notification_type_id 22 found!';
        } elseif ( $nn == 'notification_type_id' && $n == 23 )
        {
         //echo 'notification_type_id == 23 found!';
        }

      } // END foreach

      echo '</li>';

     } // END foreach

    echo '</ul>';

   } // END if(!empty($w3all_phpbb_unotifications))
