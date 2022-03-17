<?php defined( 'ABSPATH' ) or die( 'forbidden' );
// the check mess (that basically and by the way should work fine)
   global $w3all_config,$wpdb,$w3all_phpbb_dbconn;
if ( defined('W3PHPBBCONFIG') ){
  if (class_exists('WP_w3all_phpbb')) {
   $wp_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
   $w3db_conn = WP_w3all_phpbb::w3all_db_connect_res();
   //$ck_w3all_phpbb_url = admin_url() . 'options-general.php?page=wp-w3all-users-check';
   $ck_w3all_phpbb_url = admin_url() . 'tools.php?page=wp-w3all-users-check';

   $phpBBuck = array();
   $wps_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'signups' : $wpdb->prefix . 'signups';
   $wpp_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'posts' : $wpdb->prefix . 'posts';
   $wpdb->query("SHOW TABLES LIKE '$wps_db_utab'");
    if($wpdb->num_rows > 0){
       $signup_yn = true;
     }
  }
} else {
    die("<h2>Wp w3all miss phpBB db configuration. Set phpBB connection values by opening:<br /><br /> Settings -> WP w3all</h2>");
  }

$act = array_keys($_POST);
$action = '';

if(in_array('submit_w3all_phpbb_u_0posts',$act)){
  $action = 'submit_w3all_phpbb_u_0posts';
}
if(in_array('submit_w3all_wpu_not_in_phpbb_mismatch_email',$act)){
  $action = 'submit_w3all_wpu_not_in_phpbb_mismatch_email';
}
if(in_array('submit_w3all_wpu_notINphpBB_delete',$act)){
  $action = 'submit_w3all_wpu_notINphpBB_delete';
}
if(in_array('submit_w3all_phpbb_duplicated_usernames_emails',$act)){
 $action = 'submit_w3all_phpbb_duplicated_usernames_emails';
 $phpbb_duplicated_usernames = $w3db_conn->get_results("SELECT username, COUNT(username) FROM ". $w3all_config["table_prefix"] ."users GROUP BY username HAVING COUNT(username) > 1");
 $phpbb_duplicated_emails = $w3db_conn->get_results("SELECT user_email, COUNT(user_email) FROM ". $w3all_config["table_prefix"] ."users WHERE user_email != '' GROUP BY user_email HAVING COUNT(user_email) > 1");
}
if(in_array('submit_w3all_phpbb_noallowed_usernames_chars',$act)){
 $action = 'submit_w3all_phpbb_noallowed_usernames_chars';
 //$phpbb_unwanted_usernames_chars = $w3db_conn->get_results("SELECT username FROM ". $w3all_config["table_prefix"] ."users WHERE username REGEXP '[^-0-9A-Za-z _.@]' AND user_email != ''");
 // this works for all non latin
 $phpbb_unwanted_usernames_chars = $w3db_conn->get_results("SELECT username FROM ". $w3all_config["table_prefix"] ."users WHERE username REGEXP '[^-[:alnum:] _.@]' AND user_email != ''");

 }

$start_select = $limit_select = $start_select2 = $limit_select2 = $start_select3 = $limit_select3 = 0;

if( $action == 'submit_w3all_phpbb_u_0posts' ){
            $start_select = $_POST["start_select"] + $_POST["limit_select_prev"];
            $limit_select = $_POST["limit_select"];
}

if( $action == 'submit_w3all_wpu_not_in_phpbb_mismatch_email' ){
            $start_select2 = $_POST["start_select2"] + $_POST["limit_select_prev2"];
            $limit_select2 = $_POST["limit_select2"];
}

if( $action == 'submit_w3all_wpu_notINphpBB_delete' ){
            $start_select3 = $_POST["start_select3"] + $_POST["limit_select_prev3"];
            $limit_select3 = $_POST["limit_select3"];
}

//////////////////////////////////
//////////////////////////////////

if( $action == 'submit_w3all_phpbb_u_0posts' ){

    //$phpBBuck = $w3db_conn->get_results("SELECT * FROM ". $w3all_config["table_prefix"] ."users WHERE user_posts < 1 AND group_id != 6 AND user_id > 2 LIMIT ". $start_select .", ". $limit_select ."");

    $phpbb_deactivated_inc_yn = $_POST['w3all_phpbb_u_0posts_exclude_phpbb_deactivated'] == 1 ? ' AND user_type != 1 ' : '';

    $phpBBuck = $w3db_conn->get_results("SELECT * FROM ". $w3all_config["table_prefix"] ."users WHERE user_posts < 1 AND user_id > 2 ".$phpbb_deactivated_inc_yn." LIMIT ". $start_select .", ". $limit_select ."");

  if(!empty($phpBBuck)){
   $wpun = '';
     foreach($phpBBuck as $phpBBu){
      $wpun .= "'".mb_strtolower($phpBBu->username,'UTF-8')."',";
     }
     $wpun = substr($wpun, 0, -1);

    $wpuids = $wpdb->get_results("SELECT ID FROM $wp_db_utab WHERE LOWER(user_login) IN(".$wpun.")");
    $wpuids = array_values($wpuids);

     $wpu_ids = '';
     foreach($wpuids as $wpuidss){
      $wpu_ids .= "'".$wpuidss->ID."',";
     }
    $wpu_ids = substr($wpu_ids, 0, -1);

if(strlen($wpu_ids) < 2){
 $wpu_with_posts = '';
} else {
 $wpu_with_posts = $wpdb->get_results("SELECT user_login FROM ". $wp_db_utab ." JOIN
  ". $wpp_db_utab ." ON ". $wpp_db_utab .".post_author = ". $wp_db_utab .".ID
  AND ". $wp_db_utab .".ID IN(".$wpu_ids.") group by ". $wp_db_utab .".ID");
/* $wpu_with_posts = $wpdb->get_results("SELECT user_login FROM ". $wp_db_utab ." JOIN
  ". $wpp_db_utab ." ON ". $wpp_db_utab .".post_author = ". $wp_db_utab .".ID
   group by ". $wp_db_utab .".ID having ". $wp_db_utab .".ID IN(".$wpu_ids.")"); */
  }
 if(!empty($wpu_with_posts)){
   $wpu_have_post = array_column($wpu_with_posts,'user_login');
  } else { $wpu_have_post = array(); }
 } else {
  $t_end = true;
 }
} // END


   if( $action == 'submit_w3all_wpu_not_in_phpbb_mismatch_email' ){
      $args = array(
      'blog_id'      => $GLOBALS['blog_id'],
      'fields' => 'all',
      'number'       => $limit_select2,
      'offset'       => $start_select2,
      'meta_query'   => array(),
      'date_query'   => array(),
      'orderby'      => 'ID',
      'order'        => 'ASC',
      'count_total'  => false
     );

$phpBBuck = new WP_User_Query( $args );

    if(empty($phpBBuck->results)){
      $t_end = true;
    }
  }
 if( $action == 'submit_w3all_wpu_notINphpBB_delete' ){
   // $phpBBuck = $w3db_conn->get_results("SELECT * FROM ". $w3all_config["table_prefix"] ."users WHERE group_id != 6 AND user_id > 2 LIMIT ". $start_select3 .", ". $limit_select3 ."");
  $args = array(
      'blog_id'      => $GLOBALS['blog_id'],
      'fields' => 'all',
      'number'       => $limit_select3,
      'offset'       => $start_select3,
      'meta_query'   => array(),
      'date_query'   => array(),
      'orderby'      => 'ID',
      'order'        => 'ASC',
      'count_total'  => false
     );

$phpBBuck = new WP_User_Query( $args );

    if(empty($phpBBuck->results)){
      $t_end = true;
    }

 }


// subsequents
#### START // users with 0 posts
 if( isset($_POST['phpbb_uname_deact_wp_delete']) && count($_POST['phpbb_uname_deact_wp_delete']) > 0 && $action == 'submit_w3all_phpbb_u_0posts'){

  // ids to delete
    $unames = array_values($_POST['phpbb_uname_deact_wp_delete']);
    $unamexx = '';
   foreach($unames as $uname){
     $unamexx .= "'".esc_sql(mb_strtolower($uname,'UTF-8'))."',";
    }
    $unamex = substr($unamexx, 0, -1);
    $wpuids = $wpdb->get_results("SELECT ID FROM $wp_db_utab WHERE LOWER(user_login) IN(".$unamex.")");
    $wpuids = array_values($wpuids);

   if(!empty($wpuids)){
    foreach($wpuids as $wpuid){
     wp_delete_user( $wpuid->ID );
     if(is_multisite() == true){
      wpmu_delete_user( $wpuid->ID );
     }
    }
   }

  if(isset($signup_yn)){
   $wpdb->query("DELETE FROM $wps_db_utab WHERE LOWER(user_login) IN(".$unamex.")");
  }

 $w3db_conn->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '1' WHERE LOWER(username) IN(".$unamex.")");
}
#### END // of $_POST['w3all_phpbb_u_0posts']


// START phpbb_uname_notinwp_wp_delete
if( isset($_POST['phpbb_uname_notinwp_wp_delete']) && $action == 'submit_w3all_wpu_notINphpBB_delete' ){

 $unames = array_values($_POST['phpbb_uname_notinwp_wp_delete']);

    $unamexx = '';
   foreach($unames as $uname){
     $unamexx .= "'".esc_sql(mb_strtolower($uname,'UTF-8'))."',";
    }
    $unamex = substr($unamexx, 0, -1);
    $wpuids = $wpdb->get_results("SELECT ID FROM $wp_db_utab WHERE LOWER(user_login) IN(".$unamex.")");
    $wpuids = array_values($wpuids);

   if(!empty($wpuids)){
    foreach($wpuids as $wpuid){
     wp_delete_user( $wpuid->ID );
     if(is_multisite() == true){
      wpmu_delete_user( $wpuid->ID );
     }
    }
   }

  if(isset($signup_yn)){
   $wpdb->query("DELETE FROM $wps_db_utab WHERE LOWER(user_login) IN(".$unamex.")");
  }

 $w3db_conn->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '1' WHERE LOWER(username) IN(".$unamex.")");

 } // END phpbb_uname_notinwp_wp_delete

// END

echo '<script>function w3all_toggle(source) {
    var checkboxes = document.querySelectorAll(\'input[type="checkbox"]\');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}</script>';

  $start_or_continue_msg = (!isset($_POST["start_select"])) ? 'Start check phpBB/WordPress users' : 'Continue check phpBB/WordPress users';
  if( isset($t_end) ){ $start_or_continue_msg = 'Check complete! To re-start the check reload this page'; }
if($action != 'submit_w3all_phpbb_duplicated_usernames_emails' && $action != 'submit_w3all_phpbb_noallowed_usernames_chars'){
echo __('<!-- wrapper --><div style="margin-top:4.0em;"><h2>WP_w3all phpBB WP users check (RAW WP_w3all)</h2>simply tasks to check problems between linked phpBB and WordPress users<br /><br /><hr />', 'wp-w3all-phpbb-integration');
echo __('<div>
<h4><span style="color:red">Notice</span>: do not put so hight value for users to check each time. It is set by default to 500 users x time, but you can change the value.<br />Try out: maybe 10000 or 20000 or more users to check x time is ok for your system/server resources.<br />If error come out due to max execution time, just adjust to a lower value the number of users to check x time.<br />Refresh manually the browser: this will "reset the counter".<br />
 Repeat the process by setting a lower value for users to check x time: continue to check users until a <span style="color:green">green message</span> will display that the check has been completed.<br /><br /></h4><hr /></div>', 'wp-w3all-phpbb-integration');
} else { echo '<div>'; } // wrapper <div> if not echo above



echo '<form name="submit_w3all_phpbb_u_0posts" id="" action="'.esc_url( $ck_w3all_phpbb_url ).'" method="POST">';

// LIST PHPBB users with 0 posts
if ( $action == 'submit_w3all_phpbb_u_0posts' && count($phpBBuck) > 1 ){
echo __('<h3><span style="color:red">List users with 0 posts in phpBB: delete in WordPress and deactivate in phpBB</span></h3>', 'wp-w3all-phpbb-integration');

echo '<input type="checkbox" onclick="w3all_toggle(this);" /><strong>Check all</strong><br /><br />';
foreach($phpBBuck as $phpbbu){
  if(strlen($phpbbu->user_email) > 3){ // may exclude bots: they not have an email in phpBB and the query above return all users with 0 posts. It has not been used 'AND group_id != 6' to exclude default phpBB bots group ...
  $phpbbu_deact = $phpbbu->user_type == 1 ? '<span style="color:#FF0000"> - deactivated user in phpBB</span>' : '';
  $wpu_haveposts = in_array($phpbbu->username, $wpu_have_post) ? '-<span style="color:#b00069;font-weight:900"> have posts in WordPress</span>' : '';
  echo '<input type="checkbox" name="phpbb_uname_deact_wp_delete[]" value="'.$phpbbu->username.'" /> ' . $phpbbu->username . ' - ' . $phpbbu->user_email . $phpbbu_deact . ' ' . $wpu_haveposts .'<br />';
 }
}

echo'<br /><input type="checkbox" onclick="w3all_toggle(this);" /><strong>Check all</strong>';
} elseif (isset($t_end) && $action == 'submit_w3all_phpbb_u_0posts'){
  echo __('<h3><span style="color:green;font-weight:900">Check has been completed! To re-start the check reload this page</span></h3>', 'wp-w3all-phpbb-integration');
} elseif ($action == 'submit_w3all_phpbb_u_0posts' && count($phpBBuck) < 1){
  echo __('<h3>No record match this criteria</h3>', 'wp-w3all-phpbb-integration');
}

?>
<!-- START -->
<?php
if( $action == 'submit_w3all_phpbb_u_0posts' OR empty($action) ):
echo __('<h3><span style="color:red">List users with 0 posts in phpBB: delete in WordPress and deactivate in phpBB</span></h3>note that listed and selected phpBB users will be deactivated even if they not exists in WordPress', 'wp-w3all-phpbb-integration');
endif;
if ( $action == 'submit_w3all_phpbb_u_0posts' && is_array($phpBBuck) && count($phpBBuck) > 1 ){
echo __('<br /><span style="color:#FF0000;font-weight:900">WARNING:</span> selected users will be <b>deactivated in phpBB</b> (then will be possible to delete all those users in one click in phpBB ACP)<br />and <span style="color:#FF0000;font-weight:900">DELETED</span> in WordPress!', 'wp-w3all-phpbb-integration');
}
if( $action == 'submit_w3all_phpbb_u_0posts' OR empty($action) ):
?>
<div>
<p>Check <input type="text" name="limit_select" value="500" /> users x time (<strong>you'll have to select users you want remove/delete</strong>)
  <input type="hidden" name="w3all_phpbb_u_0posts" value="1" />
  <input type="hidden" name="limit_select_prev" value="<?php echo $limit_select; ?>" />
  <input type="hidden" name="start_select" value="<?php echo $start_select;?>" /><br /><br />
  <input type="radio" name="w3all_phpbb_u_0posts_exclude_phpbb_deactivated" value="1" checked> Exclude existent and already deactivated phpBB users<br />
  <input type="radio" name="w3all_phpbb_u_0posts_exclude_phpbb_deactivated" value="0"> Include existent and already deactivated phpBB users
<br /><br /><input type="submit" name="submit_w3all_phpbb_u_0posts" id="submit1" class="button button-primary" value="<?php echo $start_or_continue_msg;?>">
</p></div>
</form>
<!-- END -->

<!-- START -->
<?php
endif;
if( $action == 'submit_w3all_wpu_not_in_phpbb_mismatch_email' OR empty($action) ):
echo __('<hr /><br /><br /><hr /><h3><span style="color:red">List existent WordPress users that not exists in phpBB, and WordPress phpBB users with mismatching emails</span></h3>', 'wp-w3all-phpbb-integration');
endif;
if ( $action == 'submit_w3all_wpu_not_in_phpbb_mismatch_email' && is_countable($phpBBuck) && count($phpBBuck) > 1 ){
echo __('<br /><span style="color:#FF0000;font-weight:900">User\'s list to be fixed:</span><br /><br />', 'wp-w3all-phpbb-integration');
}

// LIST WP users not existent in PHPBB AND mismatching emails
if ( $action == 'submit_w3all_wpu_not_in_phpbb_mismatch_email' && isset($phpBBuck->results) && is_countable($phpBBuck->results) && count($phpBBuck->results) > 1 ){
echo '<input type="hidden" name="w3all_wpu_not_in_phpbb_mismatch_email" value="1" />';

$wpun = '';
foreach ( $phpBBuck->results as $u ) {
     $wpun .= "'".esc_sql(mb_strtolower($u->user_login,'UTF-8'))."',";
 }
 $wpun = substr($wpun, 0, -1);
 $phpbbusers = $w3db_conn->get_results("SELECT username, user_email FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(username) IN(".$wpun.")", ARRAY_A);
 $users_login_arr = array_column($phpbbusers, 'username');

 $users_login_arr = array_map('mb_strtolower',$users_login_arr);

foreach( $phpBBuck->results as $u ) {
    if (!in_array(mb_strtolower($u->user_login,'UTF-8'), $users_login_arr)) {
        //echo $wpu_notin_phpbb = '<br /><span style="color:#FF0000;font-weight:900"> -> WARNING!</span> user <span style="color:#FF0000;font-weight:900">'. $u->user_login .'</span> exist in WordPress but not in phpBB!<br />Add same username <span style="color:#FF0000;font-weight:900">'. $u->user_login .'</span>  associated with same email address in phpBB or delete this user in WordPress!<br />';
        echo $wpu_notin_phpbb = '<br /><span style="color:#FF0000;font-weight:900"> -> WARNING!</span> username <span style="color:#FF0000;font-weight:900">'. $u->user_login .'</span> exist in WordPress but not in phpBB!<br />';
     $allOKNot = true;
    }

  foreach($phpbbusers as $p){
    if (in_array(strtolower($u->user_login), $p)) {
      if(strtolower($p['user_email']) != strtolower($u->user_email)){
       echo '<br /><span style="color:#85144b;font-weight:900"> -> WARNING!</span> username <span style="color:#FF0000;font-weight:900">'. $u->user_login .'</span> email mismatch in phpBB!<br />User\'s email in WordPress <span style="color:#FF0000;font-weight:900">'. $u->user_email .'</span> - user\'s email in phpBB <span style="color:#FF0000;font-weight:900">'. $p['user_email'] .'</span><br />';
      }
     }
   }
 }

  $allOKorNot = isset($allOKNot) ? '' : '<h2>processed users results OK, follow checking users ...</h2>';
  echo $allOKorNot;
  $start_or_continue_msg = 'Continue check phpBB/WordPress users';
} elseif (isset($_POST['w3all_wpu_not_in_phpbb_mismatch_email']) && isset($t_end) && $action == 'submit_w3all_wpu_not_in_phpbb_mismatch_email' ){
  echo __('<h3><span style="color:green;font-weight:900">Check has been completed! To re-start the check reload this page</span></h3>', 'wp-w3all-phpbb-integration');
} elseif (isset($_POST['w3all_wpu_not_in_phpbb_mismatch_email']) && is_countable($phpBBuck) && count($phpBBuck) < 1 && $action == 'submit_w3all_wpu_not_in_phpbb_mismatch_email' ){
  echo __('<h3>No record match this criteria</h3>', 'wp-w3all-phpbb-integration');
}
if( $action == 'submit_w3all_wpu_not_in_phpbb_mismatch_email' OR empty($action) ):
 echo '<form name="w3all_wpu_not_in_phpbb_mismatch_email" id="" action="'.esc_url( $ck_w3all_phpbb_url ).'" method="POST">';
?>
<div>
<p>Check <input type="text" name="limit_select2" value="500" /> users x time
  <input type="hidden" name="w3all_wpu_not_in_phpbb_mismatch_email" value="1" />
  <input type="hidden" name="limit_select_prev2" value="<?php echo $limit_select2; ?>" />
  <input type="hidden" name="start_select2" value="<?php echo $start_select2;?>" /><br /><br />
<input type="submit" name="submit_w3all_wpu_not_in_phpbb_mismatch_email" id="submit2" class="button button-primary" value="<?php echo $start_or_continue_msg;?>">
</p></div></form>
<!-- END -->
<?php
endif; // END

///////////////////////////////////////////

// submit_w3all_wpu_notINphpBB_delete
if(empty($action)){
   echo '<form name="w3all_phpbbu_notINwp_delete" id="" action="'.esc_url( $ck_w3all_phpbb_url ).'" method="POST">';

}
if ( $action == 'submit_w3all_wpu_notINphpBB_delete' && isset($phpBBuck->results) && is_countable($phpBBuck->results) && count($phpBBuck->results) > 1 )
{
   echo '<form name="w3all_phpbbu_notINwp_delete" id="" action="'.esc_url( $ck_w3all_phpbb_url ).'" method="POST">';

echo '<input type="hidden" name="w3all_phpbbu_notINwp_delete" value="1" />';

$wpun = '';
foreach ( $phpBBuck->results as $u ) {
     $wpun .= "'".mb_strtolower($u->user_login,'UTF-8')."',";
 }

 $wpun = substr($wpun, 0, -1);
 $phpbbusers = $w3db_conn->get_results("SELECT username, user_email FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(username) IN(".$wpun.")", ARRAY_A);
 $users_login_arr0 = array_column($phpbbusers, 'username');
// $users_email_arr0 = array_column($phpbbusers, 'user_email');
 $users_login_arr = array_map('mb_strtolower',$users_login_arr0);
// $users_email_arr = array_map('mb_strtolower',$users_email_arr0);

if ( $action == 'submit_w3all_wpu_notINphpBB_delete' && is_countable($phpBBuck->results) && count($phpBBuck->results) > 1 ){
echo __('<br /><span style="color:#FF0000;font-weight:900">User\'s list to be fixed:</span><br /><br />', 'wp-w3all-phpbb-integration');
  $start_or_continue_msg = 'Continue check phpBB/WordPress users';
}

echo '<br /><span style="color:#FF0000;font-weight:900">WARNING! selected users will be removed/deleted in WordPress at next submit!</span>';
echo '<br /><br /><input type="checkbox" onclick="w3all_toggle(this);" /><strong>Check all</strong><br /><br />';

foreach ( $phpBBuck->results as $u ) {

    if (!in_array(mb_strtolower($u->user_login,'UTF-8'), $users_login_arr)) {
  echo '<input type="checkbox" name="phpbb_uname_notinwp_wp_delete[]" value="'.$u->user_login.'" /> ' . $u->user_login . ' - ' . $u->user_email . ' not existent in phpBB<br />';
     $allOKNot = true;
    }
 }

  $allOKorNot = isset($allOKNot) ? '' : '<h2>processed users results OK, follow checking users ...</h2>';
  echo $allOKorNot;
} elseif ($action == 'submit_w3all_wpu_notINphpBB_delete' && isset($t_end)){
  echo __('<h3><span style="color:green;font-weight:900">Check has been completed! To re-start the check reload this page</span></h3>', 'wp-w3all-phpbb-integration');
} elseif ($action == 'submit_w3all_wpu_notINphpBB_delete' && is_countable($phpBBuck->results) && count($phpBBuck->results) < 1 ){
  echo __('<h3>No record match this criteria</h3>', 'wp-w3all-phpbb-integration');
}
if( $action == 'submit_w3all_wpu_notINphpBB_delete' OR empty($action) ):
echo __('<hr /><br /><br /><hr /><h3><span style="color:red">List WordPress users that not exists in phpBB: delete WordPress users that not exists in phpBB</span></h3>', 'wp-w3all-phpbb-integration');
endif;

if( $action == 'submit_w3all_wpu_notINphpBB_delete' OR empty($action) ):
 ?>
<div>
<p>Check <input type="text" name="limit_select3" value="500" /> users x time (<strong>you'll have to select users you want remove/delete</strong>)
  <input type="hidden" name="limit_select_prev3" value="<?php echo $limit_select3; ?>" />
  <input type="hidden" name="start_select3" value="<?php echo $start_select3;?>" /><br /><br />
<input type="submit" name="submit_w3all_wpu_notINphpBB_delete" id="submit3" class="button button-primary" value="<?php echo $start_or_continue_msg;?>">
</p></div></form>
<!-- END -->
<?php
endif; // END

if( $action == 'submit_w3all_phpbb_duplicated_usernames_emails' OR empty($action) ):
echo __('<hr /><br /><br /><hr /><h3><span style="color:red">List phpBB users with duplicated usernames or emails</span></h3>', 'wp-w3all-phpbb-integration');
 echo '<form name="submit_w3all_phpbb_duplicated_usernames_emails" id="" action="'.esc_url( $ck_w3all_phpbb_url ).'" method="POST">';

if(isset($phpbb_duplicated_usernames)){

    echo'<p>Check phpBB emails and usernames that are sharing same email address or same username into<br />
    <strong><i>phpBB -> ACP -> TAB Users and Groups -> Manage users -></i> click into <span style="color:#C70039;font-size:120%;">Find a Member</span> link</strong><br />and insert username or email to find out users sharing same email address or having same username and then fix issues.<br />If all result ok (no duplicated usernames or emails found), <span style="color:#2f6e17">GREEN</span> message will display</p>';
echo 'To return to the main WP users check page, reload this page or click <a href="' . admin_url() . 'tools.php?page=wp-w3all-users-check'.'">here</a><br /><br /><hr />';


}

 if(isset($phpbb_duplicated_usernames) && !empty($phpbb_duplicated_usernames)){
    echo '<h4 style="color:#FF0000">Warning: there are users sharing same <span style="color:#333">username</span> in phpBB:</h4>';

    foreach($phpbb_duplicated_usernames as $k ){
    foreach($k as $vv){
      echo $vv . ' &nbsp;';
      if(is_numeric($vv)){ echo'<br />'; }
    }
    }
  } elseif (isset($phpbb_duplicated_usernames) && empty($phpbb_duplicated_usernames)) {
    echo '<h4 style="color:#2f6e17">All ok about <span style="color:#333">usernames</span>: there are no usernames shared between different users in phpBB</h4>';
  }

 if(isset($phpbb_duplicated_emails) && !empty($phpbb_duplicated_emails)){
    echo '<h4 style="color:#FF0000">Warning: there are users sharing same <span style="color:#333">email address</span> in phpBB:</h4>';

    foreach($phpbb_duplicated_emails as $k ){
    foreach($k as $vv){
      echo $vv . ' &nbsp;';
      if(is_numeric($vv)){ echo'<br />'; }
    }
    }
  } elseif(isset($phpbb_duplicated_emails) && empty($phpbb_duplicated_emails)) {
    echo '<h4 style="color:#2f6e17">All ok about <span style="color:#333">emails</span>: there are no emails shared between different users in phpBB</h4>';
  }
 ?>

<div><p>
<input type="hidden" name="submit_w3all_phpbb_duplicated_usernames_emails" value="1" />
<input type="submit" name="submit_w3all_phpbb_duplicated_ue" id="submit4" class="button button-primary" value="Check duplicated phpBB Usernames or Emails" />
</p></div></form>
<!-- END -->
<?php
endif; // END

if( $action == 'submit_w3all_phpbb_noallowed_usernames_chars' OR empty($action) ):
echo __('<hr /><br /><br /><hr /><h3><span style="color:red">List phpBB usernames containing not allowed characters into WordPress</span></h3>', 'wp-w3all-phpbb-integration');
echo '<form name="submit_w3all_phpbb_noallowed_usernames_chars" id="" action="'.esc_url( $ck_w3all_phpbb_url ).'" method="POST">';

if(isset($phpbb_unwanted_usernames_chars)){

    echo'<p>Check phpBB usernames for not allowed characters in WordPress<br />
    If all checked usernames will results ok, so not containing not allowed characters into WordPress, <span style="color:#2f6e17">GREEN</span> message will display</p>';
echo 'To return to the main WP users check page, reload this page or click <a href="' . admin_url() . 'tools.php?page=wp-w3all-users-check'.'">here</a><br /><br /><hr />';


}

 if(isset($phpbb_unwanted_usernames_chars) && !empty($phpbb_unwanted_usernames_chars)){
    echo '<h4 style="color:#FF0000">Warning - there are <span style="font-size:120%">'. count($phpbb_unwanted_usernames_chars) .'</span> usernames in phpBB containing <span style="color:#333">not allowed characters</span> in WordPress:</h4>';

    foreach($phpbb_unwanted_usernames_chars as $k ){
     echo $k->username . '<br />';
    }
    exit;
  } elseif (isset($phpbb_unwanted_usernames_chars) && empty($phpbb_unwanted_usernames_chars)) {
    echo '<h4 style="color:#2f6e17">Not found. All ok about <span style="color:#333">usernames</span> in phpBB containing not allowed characters in WordPress</h4>';
  }

 ?>

<div><p>
<input type="hidden" name="submit_w3all_phpbb_noallowed_usernames_chars" value="1" />
<input type="submit" name="submit_w3all_phpbb_noallowed_uc" id="submit5" class="button button-primary" value="Check phpBB Usernames with not allowed charaters in WordPress" />
</p></div></form>
<!-- END -->
<?php
endif; // END


//////////////////////////////////////////
echo '</form></div><!-- / wrapper -->';