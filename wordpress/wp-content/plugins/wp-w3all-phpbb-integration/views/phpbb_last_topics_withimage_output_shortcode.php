<?php defined( 'ABSPATH' ) or die( 'forbidden' );

add_thickbox();
$w3commonImgMimes = array('jpeg','jpg','gif','png','svg','bmp');

$w3LastopicsIMG_TABwrapper = 'w3LastopicsIMG_TABwrapper'; // declare class .w3LastopicsIMG_TBwrapper into your theme css or style it somewhere here
$w3LSIMG_TDLeft = 'w3LSIMG_TDLeft'; // declare class .w3LSIMG_TDLeft into your theme css or style inline more below
$w3LSIMG_TDRight = 'w3LSIMG_TDRight'; // declare class .w3LSIMG_TDRight into your theme css or style inline more below
$w3LSIMG_DIV0 = 'w3LSIMG_DIV0'; // declare class .w3LSIMG_TDIN_TDL into your theme css or style inline more below
$w3TDSpacer = 'w3tdSpacer'; // declare class .$w3tdSpacer into your theme css or style inline more below

// NOTE: remove INLINE CSS here below if you want external css above take effect, because inline style take precedence

// css styles
$w3TABwrapper_instyle = ' style="border-collapse:collapse;border-width:0;"';
$w3timeSI_instyle = ' style="font-size:0.7em;"';

// maybe REMOVE or comment out these 3 if using same vars to switch column colors more below ...
$w3LSIMG_TDLeft_instyle = ' style="text-align:center;vertical-align:middle;padding:5px;border-width:0;"';
$w3LSIMG_TDRight_instyle = ' style="text-align:left;vertical-align:middle;padding:5px;border-width:0;"';
// GAP space between columns // increase or decrease this value to get more space or less between columns
$w3TDSpacer_instyle = ' style="width:'.$wp_w3all_gap_columns.'%;border-width:0;"';

// css styles

if( !empty($last_topics) ){

echo '<table class="'.$w3LastopicsIMG_TABwrapper.'"'.$w3TABwrapper_instyle.'><tbody><tr>';
// columns trick
$c0untn = 0;
$c1ount = 1;

$countn = 0;
foreach ($last_topics as $key => $w3topic) {
  if ( $countn < $topics_number ){
 if($w3all_phpbb_widget_FA_mark_yn > 0){ // Font Awesome to notify about read/unread
   $w3all_post_state_ru = (isset($phpbb_unread_topics) && is_array($phpbb_unread_topics) && array_key_exists($w3topic->topic_id, $phpbb_unread_topics)) ? $w3all_post_state_ru = ' &nbsp; <span style="color:#BC2A4D"><i class="fa fa-comment" aria-hidden="true"></i></span>' : '';
  } else { // No Fontawesome, &star; html entity for read/unread
   $w3all_post_state_ru = (isset($phpbb_unread_topics) && is_array($phpbb_unread_topics) && array_key_exists($w3topic->topic_id, $phpbb_unread_topics)) ? $w3all_post_state_ru = ' &nbsp; <span style="color:#BC2A4D">&star;</span>' : '';
  }

  $w3topic->topic_last_poster_name = (empty($w3topic->topic_last_poster_name)) ? __( 'Guest', 'wp-w3all-phpbb-integration' ) : $w3topic->topic_last_poster_name;
 if ( $wp_w3all_post_text == 1 ){ // post text
   $w3topic->post_text = wp_w3all_remove_bbcode_tags($w3topic->post_text, $wp_w3all_text_words) . ' ...';
  } else {
   $w3topic->post_text = ''; // no post text
  }

  $titleLinkToPost = '<a href="'.$w3all_url_to_cms.'/viewtopic.php?f='.$w3topic->forum_id.'&amp;t='.$w3topic->topic_id.'&amp;p='.$w3topic->post_id.'#p'.$w3topic->post_id.'">'.$w3topic->topic_title.'</a> '.$w3all_post_state_ru.'<br />';

/*
// switch for zebra colors 'inline' maybe in this way
if( $countn & 1 ) {
$w3LSIMG_TDLeft_instyle = ' style="vertical-align:middle;padding:5px;border-width:0;background-color:#f1f1f2"';
$w3LSIMG_TDRight_instyle = ' style="vertical-align:middle;padding:5px;border-width:0;background-color:#f1f1f2"';
$w3TDSpacer_instyle = ' style="width:10%;border-width:0;"';
} else {
$w3LSIMG_TDLeft_instyle = ' style="vertical-align:middle;padding:5px;border-width:0;background-color:#d8d8d8"';
$w3LSIMG_TDRight_instyle = ' style="vertical-align:middle;padding:5px;border-width:0;background-color:#d8d8d8"';
$w3TDSpacer_instyle = ' style="width:10%;border-width:0;"';
}
*/

if( $c0untn == 0 && $c1ount < $countn OR $c0untn == $wp_w3all_columns_number ){ echo '<tr>'; }
echo '<td class="'.$w3LSIMG_TDLeft.'"'.$w3LSIMG_TDLeft_instyle.'>';
if( in_array($w3topic->extension,$w3commonImgMimes) ){
// to apply to center image thinkbox iframe ... but can not ever work properly ... setTimeout may execute before iframe document loaded ...
// onclick="function w3centerImg(){var ifr=document.getElementById(\'TB_iframeContent\').contentDocument.body.setAttribute(\'style\',\'text-align:center\');}setTimeout(function(){w3centerImg();},500);return false;"
echo '<a style="border:0;margin:0;text-decoration:none;color:transparent" href="'.$w3all_url_to_cms.'/download/file.php?id='.$w3topic->attach_id.'&TB_iframe=true" class="thickbox"><img alt="'.$w3topic->topic_title.'" src="'.$w3all_url_to_cms.'/download/file.php?id='.$w3topic->attach_id.'" /></a>
</td><!-- close td left - if image -->';
} else {
echo '<a href="'.$w3all_url_to_cms.'/download/file.php?id='.$w3topic->attach_id.'" />'.$w3topic->real_filename.'</a>
</td><!-- close td left - if not an image -->';
}

echo '<td class="'.$w3LSIMG_TDRight.'"'.$w3LSIMG_TDRight_instyle.'>
<div class="'.$w3LSIMG_DIV0.'">'
.$titleLinkToPost;
if(!empty($w3topic->post_text)){
echo '<br />';
}
echo __( 'by ' , 'wp-w3all-phpbb-integration' ). $w3topic->topic_last_poster_name
. '<br />'
. '<span '.$w3timeSI_instyle.'>'
. __( 'at ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'H:i Y-m-d', $w3topic->topic_last_post_time + ( 3600 * get_option( 'gmt_offset' )) ) .'
</span></div>
</td><!-- close td right -->';


 if(  $c0untn == $wp_w3all_columns_number ){ echo '</tr>'; }
  $c0untn++; $countn++;
  
 if(  $c0untn == $wp_w3all_columns_number ){ $c0untn = 0; }
 else {  echo '<td class="'.$w3TDSpacer.'"'.$w3TDSpacer_instyle.'></td>';  }

} // END instance topics number
} // END foreach

echo '</tr></tbody></table><!-- close table wrapper -->';

}