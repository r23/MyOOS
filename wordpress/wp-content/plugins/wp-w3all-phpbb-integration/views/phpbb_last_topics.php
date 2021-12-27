<?php defined( 'ABSPATH' ) or die( 'forbidden' ); 

//$phpbb_on_template_iframe = get_option( 'w3all_iframe_phpbb_link_yn' );
global $w3all_iframe_custom_w3fancyurl,$w3all_iframe_phpbb_link_yn,$w3cookie_domain,$wp_w3all_forum_folder_wp;

if(empty($wp_w3all_forum_folder_wp) && $w3all_iframe_phpbb_link_yn == 1){
	echo 'Notice: the forum\'s page name has not been setup and "Links for embedded phpBB iframe into WordPress (option)" is set to Yes. Links points to phpBB iframe into WP page. Change settings into the plugin admin page';
}
$w3all_avatars_yn = ( $w3all_get_phpbb_avatar_yn == 1 && $w3all_last_t_avatar_yn == 1 ) ? true : false; // avatars or not
$w3all_lastopics_style_ul = 'list-style:none;margin:0px'; // change into whatever you need
$w3all_lastopics_style_ul_class = 'w3all_ul_widgetLastTopics'; // declare this class .w3all_ul_widgetLastTopics into your css template and style ul element as needed 
$w3all_lastopics_style_li_class = 'w3all_li_widgetLastTopics'; // declare this class .w3all_li_widgetLastTopics into your css template and style li elements as needed 

if(!empty($last_topics)){

if(defined("W3ALLPHPBBUAVA")){
$w3phpbbuava = unserialize(W3ALLPHPBBUAVA);
} else { $w3phpbbuava = array(); }

echo "<ul class=\"".$w3all_lastopics_style_ul_class."\" style=\"".$w3all_lastopics_style_ul."\">\n";

$countn = 0;
foreach ($last_topics as $key => $value) {

if(!empty($w3phpbbuava)){
	foreach($w3phpbbuava as $k){
		if($k['phpbbuid'] == $value->user_id && $k['phpbbuid'] > 1){
		 $phpbbUAVA = $k['phpbbavaurl'];
		}
  }
}

if ( $countn < $topics_number ){ // instance topics number

 if($w3all_phpbb_widget_FA_mark_yn > 0){ // Font Awesome to notify about read/unread
  $w3all_post_state_ru = (isset($phpbb_unread_topics) && is_array($phpbb_unread_topics) && array_key_exists($value->topic_id, $phpbb_unread_topics)) ? $w3all_post_state_ru = ' &nbsp; <span style="color:#BC2A4D"><i class="fa fa-comment" aria-hidden="true"></i></span>' : '';
 } else { // No Fontawesome, output just an &star; html entity (or whatever you like) for read/unread
  $w3all_post_state_ru = (isset($phpbb_unread_topics) && is_array($phpbb_unread_topics) && array_key_exists($value->topic_id, $phpbb_unread_topics)) ? $w3all_post_state_ru = ' &nbsp; <span style="color:#BC2A4D">&star;</span>' : '';
 }

	if ( $w3all_avatars_yn ){
	
	 if( $value->user_id == 2 ){ // switch if install admins (uid 1 WP - uid 2 phpBB) have different usernames
  	 $wpu = get_user_by('ID', 1); 
    } else {
			    $wpu = get_user_by('login', $value->topic_last_poster_name); 
			  }
			  
   if( ! $wpu && isset($phpbbUAVA) ){
   		$w3all_avatar_display = ( is_email( $phpbbUAVA ) !== false ) ? get_avatar($phpbbUAVA, $w3all_last_t_avatar_dim) : '<img alt="" src="'.$phpbbUAVA.'" class="avatar" width="'.$w3all_last_t_avatar_dim.'" height="'.$w3all_last_t_avatar_dim.'">';
  	} elseif ( ! $wpu && !isset($phpbbUAVA) ) {
     	      $w3all_avatar_display = get_avatar(0, $w3all_last_t_avatar_dim);
          } else {
     	      $w3all_avatar_display = get_avatar($wpu->ID, $w3all_last_t_avatar_dim);
          }
  } 
 
	$value->topic_last_poster_name = (empty($value->topic_last_poster_name)) ? __( 'Guest', 'wp-w3all-phpbb-integration' ) : $value->topic_last_poster_name;

 if ( $wp_w3all_post_text == 1 ){
   $value->post_text = wp_w3all_remove_bbcode_tags($value->post_text, $wp_w3all_text_words);
  }

  if ( $w3all_iframe_phpbb_link_yn == 1 ){ // if on iframe mode, links are in this way (js trick x seo added)
  	// this build the iframe href to switch to, if iframe links are active: the url to iframed Wp page is only passed onmouseover, so a crawler will see the real phpBB url to topic/post, while user will be redirect to iframed WP page
  	// see wp_w3all.php -> function w3all_widget_iframe_href(){
  	$ih = get_home_url()."/index.php/$wp_w3all_forum_folder_wp/?forum_id=$value->forum_id&amp;topic_id=$value->topic_id&amp;post_id=$value->post_id#p$value->post_id";  
  	$idsh = 'w3all' . $countn . mt_rand(999,500000); // guess (+- surely) to not build a duplicated ID for the 'a' element on page output, even with multiple widgets/shortcodes instances on same page ...
 	     		
  	if ( $wp_w3all_post_text == 0 ){ // only links author and date
  	
  	     	if ( $w3all_avatars_yn ){
  	     		  echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table style=\"border-spacing:0;border-collapse:collapse;vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a id=\"".$idsh."\" onmouseover=\"w3allIframeHref('".$idsh."','".$ih."');\" href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a> ".$w3all_post_state_ru."<br />".__( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ".__( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</td></tr></table></li>\n";
  	     	
  	     	} else {
  	     			echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a id=\"".$idsh."\" onmouseover=\" w3allIframeHref('".$idsh."','".$ih."');\" href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a> ".$w3all_post_state_ru."<br />".__( 'by' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ".__( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</li>\n";
  	     	}
    } 
      
  if ( $wp_w3all_post_text == 1 ){ // links, post text, author and date
  	   
  	   if ( $w3all_avatars_yn ){
  	   	  echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table style=\"border-spacing:0;border-collapse:collapse;vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a id=\"".$idsh."\" onmouseover=\"w3allIframeHref('".$idsh."','".$ih."');\" href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a> ".$w3all_post_state_ru."<br />$value->post_text ...<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ". __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</td></tr></table></li>\n";
      
       } else {
  	  	        echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a id=\"".$idsh."\" onmouseover=\"w3allIframeHref('".$idsh."','".$ih."');\" href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a> ".$w3all_post_state_ru."<br />$value->post_text ...<br />".__( 'by' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ".__( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</li>\n";
  	          }
  }
  
  
   } else { // if not iframe phpBB embedded mode, direct url
   	
     	 if ( $wp_w3all_post_text == 0 ){ // only links author and date
     		
     		if ( $w3all_avatars_yn ){
     			
     			echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table style=\"border-spacing:0;border-collapse:collapse;vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a> ".$w3all_post_state_ru."<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ". __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</td></tr></table></li>\n";
         
         } else {
     			        
     			        echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a>  ".$w3all_post_state_ru."<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name " . __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</li>\n";
                }
       }
    
         if ( $wp_w3all_post_text == 1 ){ // links, post text, author and date
         	
         	if ( $w3all_avatars_yn ){
         		
         		 echo "<li class=\"".$w3all_lastopics_style_li_class."\"><table style=\"border-spacing:0;border-collapse:collapse;vertical-align:middle;margin:0;border:0;\"><tr><td style=\"border:0;width:".$w3all_last_t_avatar_dim."px;\">".$w3all_avatar_display."</td><td style=\"border:0;width:auto\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a> ".$w3all_post_state_ru."<br />$value->post_text ...<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ". __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</td></tr></table></li>\n";
          
            } else {
           	
         		         echo "<li class=\"".$w3all_lastopics_style_li_class."\"><a href=\"$w3all_url_to_cms/viewtopic.php?f=$value->forum_id&amp;t=$value->topic_id&amp;p=$value->post_id#p$value->post_id\">$value->topic_title</a>  ".$w3all_post_state_ru."<br />$value->post_text ...<br />". __( 'by ' , 'wp-w3all-phpbb-integration' )." $value->topic_last_poster_name ". __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $value->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) ."</li>\n";
                  }
         }
     }
     
    }
     
  $countn++;
}

	   echo "</ul>";

}
?>
