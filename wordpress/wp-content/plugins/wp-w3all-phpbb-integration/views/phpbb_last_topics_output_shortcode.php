<?php defined( 'ABSPATH' ) or die( 'forbidden' );

 $w3all_avatars_yn = ( $w3all_get_phpbb_avatar_yn == 1 && $w3all_last_t_avatar_yn == 1 ) ? true : false; // avatars or not
 // if yes, check that it has not been disabled/redefined into this shortcode via no_avatar param
 if ( $w3all_avatars_yn ) {
  $w3all_avatars_yn = $no_avatars > 0 ? false : $w3all_avatars_yn;
 }

 $dateformat = get_option('date_format');
 $gmtoffset = get_option('gmt_offset');
 $timeformat = get_option('time_format');

 // compatibility with old way
 if(empty($w3_ul_class_lt)){
  $w3all_lastopics_style_ul = 'list-style:none;margin:0px'; // inline style - change into whatever you need
  $w3all_lastopics_style_ul_class = 'w3all_ul_widgetLastTopics'; // declare this class .w3all_ul_widgetLastTopics into your css template and style ul element as needed
  $w3all_lastopics_style_li_class = 'w3all_li_widgetLastTopics'; // declare this class .w3all_li_widgetLastTopics into your css template and style li elements as needed
 } else {
   $w3all_lastopics_style_ul = $w3_inline_style_lt;
   $w3all_lastopics_style_ul_class = $w3_ul_class_lt;
   $w3all_lastopics_style_li_class = $w3_li_class_lt;
  }

 if(!empty($last_topics)){

  if(is_array($wp_userphpbbavatar)){
   $w3phpbbuava = $wp_userphpbbavatar;
  } else { $w3phpbbuava = array(); }

   $countn = 0;

 echo "<div id=\"w3all_div_last_topics_short_wrapper\"><ul id=\"w3all_ul_last_topics_short\" class=\"".$w3all_lastopics_style_ul_class."\" style=\"".$w3all_lastopics_style_ul."\">\n";

 foreach ($last_topics as $key => $value) {

   if(!empty($w3phpbbuava)){
    foreach($w3phpbbuava as $k){
     if($k['phpbbuid'] == $value->user_id && $k['phpbbuid'] > 1){
      $phpbbUAVA = $k['phpbbavaurl'];
     }
    }
   }

  if ( $countn < $topics_number )
  {
   // wp_w3all_phpbb_last_topics() on class.wp.w3all.widgets-phpbb.php
   if($w3all_phpbb_widget_FA_mark_yn > 0){ // Font Awesome to notify about read/unread
    $w3all_post_state_ru = (isset($phpbb_unread_topics) && is_array($phpbb_unread_topics) && array_key_exists($value->topic_id, $phpbb_unread_topics)) ? $w3all_post_state_ru = ' &nbsp; <span style="color:#BC2A4D"><i class="fa fa-comment" aria-hidden="true"></i></span>' : '';
   } else { // No Fontawesome
    $w3all_post_state_ru = (isset($phpbb_unread_topics) && is_array($phpbb_unread_topics) && array_key_exists($value->topic_id, $phpbb_unread_topics)) ? $w3all_post_state_ru = ' &nbsp; <span style="color:#BC2A4D">&star;</span>' : '';
   }

   if ( $w3all_avatars_yn )
   {
    if( $value->user_id == 2 ){ // switch if install admins (uid 1 WP - uid 2 phpBB) have different usernames
     $wpu = get_user_by('ID', 1);
    } else
      { $wpu = get_user_by('email', $value->user_email); }

     if( ! $wpu && isset($phpbbUAVA) ){
      $w3all_avatar_display = ( is_email( $phpbbUAVA ) !== false ) ? get_avatar($phpbbUAVA, $w3all_last_t_avatar_dim) : '<img alt="" src="'.$phpbbUAVA.'" class="avatar" width="'.$w3all_last_t_avatar_dim.'" height="'.$w3all_last_t_avatar_dim.'">';
     } elseif ( ! $wpu && !isset($phpbbUAVA) ) {
         $w3all_avatar_display = get_avatar(0, $w3all_last_t_avatar_dim);
        } else {
           $w3all_avatar_display = get_avatar($wpu->ID, $w3all_last_t_avatar_dim);
          }
   }

    $value->topic_last_poster_name = (empty($value->topic_last_poster_name)) ? __( 'Guest', 'wp-w3all-phpbb-integration' ) : $value->topic_last_poster_name;

       if ( $wp_w3all_post_text == 0 ){ // only links author and date

        if ( $w3all_avatars_yn ){
          echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table style=\"border-spacing:0;border-collapse:collapse;vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a> ".$w3all_post_state_ru."<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name<br />". date_i18n( $dateformat, $value->topic_last_post_time + ( 3600 * $gmtoffset) ) ." ". date_i18n( $timeformat, $value->topic_last_post_time + ( 3600 * $gmtoffset) ) ."</td></tr></table></li>\n";

         } else {
                  echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a>  ".$w3all_post_state_ru."<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name<br />". date_i18n( $dateformat, $value->topic_last_post_time + ( 3600 * $gmtoffset) ) ." ". date_i18n( $timeformat, $value->topic_last_post_time + ( 3600 * $gmtoffset) ) ."</li>\n";
                }
       }

         if ( $wp_w3all_post_text == 1 ){ // links, post text, author and date

         // $value->post_text = WP_w3all_phpbb::w3all_bbcodeconvert($value->post_text);
         // but after, the wp_w3all_remove_bbcode_tags() strip html converted tags: so to get the html result, strip_tags() should be removed on it

          $value->post_text = wp_w3all_remove_bbcode_tags($value->post_text, $wp_w3all_text_words);

           if ( $w3all_avatars_yn ){
             echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table style=\"border-spacing:0;border-collapse:collapse;vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a> ".$w3all_post_state_ru."<br />$value->post_text ...<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name<br />". date_i18n( $dateformat, $value->topic_last_post_time + ( 3600 * $gmtoffset) ) ." ". date_i18n( $timeformat, $value->topic_last_post_time + ( 3600 * $gmtoffset) ) ."</td></tr></table></li>\n";
            } else {
               echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a>  ".$w3all_post_state_ru."<br />$value->post_text ...<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name<br />". date_i18n( $dateformat, $value->topic_last_post_time + ( 3600 * $gmtoffset) ) ." ". date_i18n( $timeformat, $value->topic_last_post_time + ( 3600 * $gmtoffset) ) ."</li>\n";
              }
         }

    }

 $countn++;

}

     echo "</ul></div>";

}