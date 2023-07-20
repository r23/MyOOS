<?php defined( 'ABSPATH' ) or die( 'forbidden' );

// remove adding // or activate by removing //
echo '<div>'.__( 'Most users ever online was: ', 'wp-w3all-phpbb-integration' ) . $phpbb_config['record_online_users'] . '</div>'
. '<div>'.__( 'Registered users: ', 'wp-w3all-phpbb-integration' ) . $phpbb_config['num_users'] . '</div>'
. '<div>'.__( 'Topics: ', 'wp-w3all-phpbb-integration' ) . $phpbb_config['num_topics'] . '</div>'
. '<div>'.__( 'Posts: ', 'wp-w3all-phpbb-integration' ) . $phpbb_config['num_posts'] . '</div>'
. '<div>'.__( 'There are', 'wp-w3all-phpbb-integration' ) . ' ' .$guests_num. ' '. __( 'guests ', 'wp-w3all-phpbb-integration' ) . __( 'and', 'wp-w3all-phpbb-integration' ) . ' ' .$reg_num. ' ' . __( 'users online ', 'wp-w3all-phpbb-integration' )
//. __( '<br />(based on users active over the past', 'wp-w3all-phpbb-integration' ).' '.W3PHPBBCONFIG['load_online_time']. ' ' .__( 'minutes)', 'wp-w3all-phpbb-integration' ).'</div>'
.'<br />';

if(!empty($phpbb_online_udata)){
echo'<div id="" class="w3_widget_online_udata" style="display:flex;flex-wrap:wrap;padding:0;">';

if( $ava_or_ulinks == 'avatars' ){ // avatars

$avatar_dim = empty(intval($ava_dimension)) ? $w3all_last_t_avatar_dim : $ava_dimension;
foreach($phpbb_online_udata as $udata) :
 if($udata['user_id'] > 2){
   echo'<div class="w3_ava_wonline" style="text-align:center;padding:5px 5px 5px 0;width:'.$avatar_dim.'px">';
   // use widget $ava_dimension if the value has been set for this widget
   // do not rewrite global $w3all_last_t_avatar_dim
    if( $online_ulink_yn > 0 ){
     echo get_avatar($udata['user_email'], $avatar_dim,'',$udata['username'])
     .'<a href="'.$w3all_url_to_cms.'/memberlist.php?mode=viewprofile&u='.$udata['user_id'].'">'.$udata['username'].'</a>';
    } else {
       echo '<a href="'.$w3all_url_to_cms.'/memberlist.php?mode=viewprofile&u='.$udata['user_id'].'">'.get_avatar($udata['user_email'], $avatar_dim,'',$udata['username']).'</a>';
      }
   echo'</div>';
 }
endforeach;

} // END with avatars

if( $ava_or_ulinks == 'links' ){ // usernames text links

 foreach($phpbb_online_udata as $udata) :
  echo'<div class="w3_ulinks_wonline" style="text-align:center;padding:0 5px 5px 0">';
  if($udata['user_id'] > 2){
   echo '<a href="'.$w3all_url_to_cms.'/memberlist.php?mode=viewprofile&u='.$udata['user_id'].'">'.$udata['username'].'</a>';
  }
  echo'</div>';
 endforeach;

} // END with usernames text links

echo '</div>';
}