<?php defined( 'ABSPATH' ) or die( 'forbidden' );
  if ( !defined('PHPBB_INSTALLED') ){
   	die("<h2>Wp w3all miss phpBB configuration file: set the correct absolute path to phpBB by opening:<br /><br /> Settings -> WP w3all</h2>");
    }
 global $w3all_add_into_spec_group;
 echo'<div><h3>NOTE: you\'re going to insert users into phpBB Group ID -> '.$w3all_add_into_spec_group.' <br />Check that this exists: look into the WP w3all Config Page, and/or you can change this value where related option<br /><i>"Add newly WordPress registered users into a specified phpBB group"</i></h4>';
  $up_conf_w3all_url = admin_url() . 'options-general.php?page=wp-w3all-users-to-phpbb';
 	global $w3all_config,$wpdb;
  $phpbb_config = unserialize(W3PHPBBCONFIG);
  $default_dateformat = $phpbb_config["default_dateformat"];
  $default_lang = $phpbb_config["default_lang"];
  	if(empty($default_lang)){ $wp_lang_x_phpbb = 'en'; }
     else { $wp_lang_x_phpbb = $default_lang; }
  $phpbb_config_file = $w3all_config;
  $phpbb_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();
  $phpbb_version = substr($phpbb_config["version"], 0, 3);
  $w3all_phpbb_u_with_reg_date = (isset($_POST['w3all_phpbb_u_with_reg_date']) && $_POST['w3all_phpbb_u_with_reg_date']) > 0 ? $_POST['w3all_phpbb_u_with_reg_date'] : 0;

  if(!isset($_POST["start_select"])){
      $start_select = 0;
      $limit_select = 0;

       } else {
                $start_select = $_POST["start_select"] + $_POST["limit_select_prev"];
                $limit_select = $_POST["limit_select"];
             }

    if(isset($_POST["start_select"])){
      $args = array(
      'blog_id'      => $GLOBALS['blog_id'],
      'fields' => 'all',
    	'number'       => $limit_select,
    	'offset'       => $start_select,
    	'meta_query'   => array(),
    	'date_query'   => array(),
	    'orderby'      => 'ID',
	    'order'        => 'ASC',
    	'count_total'  => false
);

$user_query = new WP_User_Query( $args );

if ( ! empty( $user_query->results ) ) {

	 $phpbb_group = $phpbb_conn->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."ranks
   RIGHT JOIN ".$w3all_config["table_prefix"]."groups ON ".$w3all_config["table_prefix"]."groups.group_rank = ".$w3all_config["table_prefix"]."ranks.rank_id
   AND ".$w3all_config["table_prefix"]."ranks.rank_min = '0'
   AND ".$w3all_config["table_prefix"]."groups.group_id = '$w3all_add_into_spec_group'",ARRAY_A);

 if(!empty($phpbb_group)){
 	$urank_id_a = array();
   foreach($phpbb_group as $kv){
   	foreach($kv as $k => $v){
     if($k == 'group_id' && $v == $w3all_add_into_spec_group){
    	$urank_id_a = $kv;
     }
   }}
 if (empty($urank_id_a)){
   foreach($phpbb_group as $kv){
   	foreach($kv as $k => $v){
   	if($k == 'rank_special' && $v == 0){
    $urank_id_a = $kv;
    goto this1; // break to the first found ('it seem' to me the default phpBB behavior)??
    }
   }}
 }
this1:
if ( empty($urank_id_a) ){
	$rankID = 0;
	$group_color = '';
 } else {
if ( empty($urank_id_a['rank_id']) ){
	$rankID = 0; $group_color = $urank_id_a['group_colour'];
	} else {
	$rankID = $urank_id_a['rank_id']; $group_color = $urank_id_a['group_colour'];
}}

} // END if(!empty($phpbb_group) OR ! $phpbb_group){
else { 	$rankID = 0; $group_color = ''; }



	foreach ( $user_query->results as $wpu ) {

if( $wpu->ID == 1 ){
 	        echo"<h4 style=\"color:#135b0f\">Skip the default install admin UID 1 in WordPress: NOT transferred into phpBB</h4>";
  }
 // could exists users in wordpress with no email, like bots
if( $wpu->ID > 1 && !is_email($wpu->user_email) ) {
 		      echo"<h4 style=\"color:#135b0f\">User ".$wpu->user_login." with no email in WordPress: NOT transferred into phpBB</h4>";
  }

if( $wpu->ID > 1 && is_email($wpu->user_email) ){

       $wplang = isset($wp_lang_x_phpbb) ? strtolower($wp_lang_x_phpbb) : 'en';
       $phpbb_user_type = ( empty($wpu->roles) ) ? '1' : '0'; // if no capabilities on WP, added as deactivated
	     $user_email_hash = sprintf('%u', crc32(strtolower($wpu->user_email))) . strlen($wpu->user_email); // as phpBB do
       $wpul = esc_sql($wpu->user_login);
       $wpunn = esc_sql(strtolower($wpu->user_login));
       $wpup = $wpu->user_pass;
       $wpue = $wpu->user_email;
       $uavatar = $avatype = '';
       $wpunc = strtolower($wpul);


    if($w3all_phpbb_u_with_reg_date > 0){
       $date = new DateTime($wpu->user_registered);
       $wpur = $date->getTimestamp();
       if(empty($wpur)){
	      $wpur = time();
       }} else {
       	$wpur = time();
       }

       $user_exist = $phpbb_conn->get_results("SELECT * FROM ".$phpbb_config_file["table_prefix"]."users WHERE username = '$wpul' OR user_email = '$wpue' AND username != '$wpul'");

      $nd = 0;
     foreach($user_exist as $ue){
      if ( $ue->user_email == $wpue && $ue->username != $wpul  ){
      	 echo "<b><span style=\"color:red;font-size:120%\">WARNING!</span> <b>-> Existent username <span style=\"color:red\">". $ue->username ."</span></b> in phpBB </b> with same email address of WordPress user <b><span style=\"color:red\">". $wpul ."</b>.<br /> <span style=\"color:red\">-- Change the email address</span> for the user <span style=\"color:red\">". $ue->username ."</span> in phpBB via ACP and restart transfer. <b><span style=\"color:red\">". $wpul ."</span> NOT transferred into phpBB</b><br />";
        $nd++;
       }
     }

  if( $nd == 0  ){
   if($w3all_phpbb_u_with_reg_date != 2){ // updating date
  	if($phpbb_version == '3.2'){
		 $phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_email_hash, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
      VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','','0','', '$wpur', '$wpul', '$wpunc', '$wpup', '0', '$wpue', '$user_email_hash', '', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '0', '$wp_lang_x_phpbb', 'Europe/Rome', '$default_dateformat', '1', '$rankID', '$group_color', '0', '0', '0', '0', '-3', '0', '0', 't', 'd', 0, 't', 'a', '0', '1', '0', '1', '1', '1', '1', '230271', '', '', '0', '0', '', '', '', '', '', '', '', '0', '0', '0') ON DUPLICATE KEY UPDATE user_regdate = '$wpur', user_password = '$wpup',user_email = '$wpue',user_email_hash = '$user_email_hash'");
     }
    if($phpbb_version == '3.3'){
		 $phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, reset_token, reset_token_expiration, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
      VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','','0','','$wpur','$wpul','$wpunn','$wpup','0','$wpue','','0','0','0','index.php','','0','0','0','0','0','0','0','$wp_lang_x_phpbb','','d M Y H:i','1','0','$group_color','0','0','0','0','-3','0','0','t','d','0','t','a','0','1','0','1','1','1','1','230271','$uavatar','$avatype','50','50','','','','','','','0','','','0','0','0') ON DUPLICATE KEY UPDATE user_regdate = '$wpur', user_password = '$wpup',user_email = '$wpue',user_email = '$wpue'");
     }
    } else { // without updating date
      if($phpbb_version == '3.2'){
		   $phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_email_hash, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
       VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','','0','', '$wpur', '$wpul', '$wpunc', '$wpup', '0', '$wpue', '$user_email_hash', '', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '0', '$wp_lang_x_phpbb', 'Europe/Rome', '$default_dateformat', '1', '$rankID', '$group_color', '0', '0', '0', '0', '-3', '0', '0', 't', 'd', 0, 't', 'a', '0', '1', '0', '1', '1', '1', '1', '230271', '', '', '0', '0', '', '', '', '', '', '', '', '0', '0', '0') ON DUPLICATE KEY UPDATE user_password = '$wpup',user_email = '$wpue',user_email_hash = '$user_email_hash'");
      }
      if($phpbb_version == '3.3'){
		   $phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, reset_token, reset_token_expiration, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
       VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','','0','','$wpur','$wpul','$wpunn','$wpup','0','$wpue','','0','0','0','index.php','','0','0','0','0','0','0','0','$wp_lang_x_phpbb','','d M Y H:i','1','0','$group_color','0','0','0','0','-3','0','0','t','d','0','t','a','0','1','0','1','1','1','1','230271','$uavatar','$avatype','50','50','','','','','','','0','','','0','0','0') ON DUPLICATE KEY UPDATE user_password = '$wpup',user_email = '$wpue',user_email = '$wpue'");
      }
    }

     $phpBBlid = $phpbb_conn->insert_id;

     if( null == $user_exist && $phpBBlid > 2 ){ // or will broken tables: there is no unique key in these tables

      $phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('$w3all_add_into_spec_group','$phpBBlid','0','0')");
      //$phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('7','$phpBBlid','0','0')");
      //$phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) VALUES ('$phpBBlid','0','0','6','0')");

	    echo "<b>Transferred user -> <span style=\"color:green\">". $wpu->user_login ."</span></b><br />";

     } else {
     	       echo "<b>Overwritten existent user -> <span style=\"color:#f24507\">". $wpu->user_login ."</span></b> (email and password and/or registration date)<br />";
            }
    }
  }
}

      echo "<h2><span style=\"color:#f24507\">Continue transfer WP users into phpBB by clicking the</span> \"Continue to transfer WP users into phpBB\" button ...</h2>";

} else {
	      echo '<h1 style="margin-top:1.0em;color:green">Fix phpBB Total and Newest Members here below, or do these two steps on phpBB ACP.</h1><h1><span style="color:green">No more WordPress users found. WP user\'s transfer into phpBB completed!</span></h1>';
	      echo '<h2>All users have been added on phpBB as default Registered users.<br /> Users with no-role on WordPress have been added as deactivated phpBB users.<br />The WP install admin (uid 1) has been excluded by the transfer process.</h2>';
        $socm = 'Transfer Complete! Reload this page if you want to re-start the transfer process';
    }
}

 	$start_or_continue_msg = (!isset($_POST["start_select"])) ? 'Start transfer WP users to phpBB' : 'Continue to transfer WP users into phpBB';
  if(isset($socm)){ $start_or_continue_msg = $socm; }
 ?>

<div>
	<hr style="background-color:#333;height:2px;" />
<div class=""><h1>Transfer WordPress Users to phpBB forum ( raw w3_all )</h1></div>
<h4><span style="color:red">Notice</span>: do not put so hight value for users to transfer each time. It is set by default to 20 users x time, but you can change the value.<br />Try out: maybe 50, 100 or also 1000 or more users to transfer x time is ok for your system/server resources.<br />If Php/server error come out due to max execution time, it is necessary to adjust to a lower value the number of users to transfer x time.<br />Refresh browser window: this will "reset the counter" of the transfer procedure.<br />
 Repeat the process by setting a lower value for users to be added x time: continue adding users until a <span style="color:green">green message</span> will display that the transfer has been completed.<br />After this remember to Fix phpBB values about <i>Total</i> and <i>Newest Members</i> here below, or do these two steps directly on phpBB ACP.<br />If there is an existent same username on phpBB, his email address and password are overwrite by the email address and password of the transferred WP user. The process exclude both WP and phpBB default install admins.
 All users are transferred on phpBB as registered users if they have a role on WP, as deactivated in phpBB if no roles on WP.<br />Note: if some modification to the default phpBB database user's tables structure, this procedure will return error and no user will be transferred into phpBB.
 <br /><br /><span style="color:red">Note important</span>: if there are users in phpBB using same email address and with different usernames, <span style="color:red">a warning</span> will appear for these users:<br />it is mandatory that you change the email address for these users in phpBB, as indicated on warning (if it show up).<br /><span style="color:red">It is mandatory that in phpBB do NOT exists users sharing the same email address</span> (because it is possible option in phpBB but not in Wordpress).<br />
 <br /><span style="color:red">Note important</span> this example: while transferring 500 users, at record 250 the log report duplicated email/username found: next 250 users on queue will not be transferred, and you'll have to restart the transfer to complete the transfer task or these users will results (obviusly because skipped) not added in WordPress.
 <br />The best way would be to check and adjust these possible old phpBB users created with same email, via phpBB ACP, before to start the transfer process. <br />But the transfer can be restarted any time you like, so you can adjust warnings and repeat the process from begin.
	<br /><br />
	Note: this procedure can be used to fix registration date for old transferred WordPress users into phpBB, that may were added with wrong registration date time<br />Just repeat/execute the transfer process, existent users will be overwritten in phpBB with actual email, password and registration date based on selected option

		</h4>

<form name="w3all_conf_add_users_to_phpbb" id="w3all-conf-add-users-to-phpbb" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<p>
 Transfer <input type="text" name="limit_select" value="20" /> users x time
  <input type="hidden" name="limit_select_prev" value="<?php echo $limit_select; ?>" />
  <input type="hidden" name="start_select" value="<?php echo $start_select;?>" /><br /><br />
  <input type="radio" name="w3all_phpbb_u_with_reg_date" value="2" checked>Transfer WordPress users into phpBB <strong>without updating registration date for found existent phpBB users (default)</strong>. Not existent users will be added with the WordPress registration date<br />
  <input type="radio" name="w3all_phpbb_u_with_reg_date" value="1">Transfer WordPress users into phpBB updating <strong>with WP user's registration date</strong><br />
  <input type="radio" name="w3all_phpbb_u_with_reg_date" value="0">Transfer WordPress users into phpBB updating <strong>with actual (time now)</strong> registration time
 <br /><br />
<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $start_or_continue_msg;?>">
</p></form></div>
<hr /><hr />




	<div class=""><h1>Fix phpBB <i>Total Members and Newest Member</i> after users transfers complete</h1>
	or do these two steps via phpBB ACP<br /><br />

		<br /><b>Fix phpBB Total Members Counter</b><br /><br />
<?php
if( isset($_POST["phpbb_fix_members_counter"]) ){
// phpBB: ID 0 guest ID 6 Bots
 $tot_users_count = $phpbb_conn->get_var("SELECT COUNT(*) FROM ".$phpbb_config_file["table_prefix"]."users WHERE group_id !='6' AND group_id !='1'");

 $phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$tot_users_count' WHERE config_name = 'num_users'");

 echo "<h1 style=\"color:green\">phpBB Total Members Counter value has been fixed</h1>";
}
?>

<form name="w3all_fix_phpbb_total_members_count" id="w3all-fix-total-members-count" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
 <input type="hidden" name="phpbb_fix_members_counter" value="1" />
<input type="submit" name="submit" id="submit" class="button button-primary" value="Fix phpBB Total members">
</form>
		</div>
<div class="">
		<br /><br /><br /><b>Fix phpBB Newest Member</b><br /><br />
<?php
if( isset($_POST["phpbb_fix_newest_member"]) ){

$newest_member = $phpbb_conn->get_results(" SELECT * FROM ".$phpbb_config_file["table_prefix"]."users WHERE user_id = (SELECT Max(user_id) FROM ".$phpbb_config_file["table_prefix"]."users) AND group_id != '6'");
$uname = esc_sql($newest_member[0]->username);
$uid   = $newest_member[0]->user_id;
$phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$uname' WHERE config_name = 'newest_username'");
$phpbb_conn->query("UPDATE ".$phpbb_config_file["table_prefix"]."config SET config_value = '$uid' WHERE config_name = 'newest_user_id'");

	   echo "<h1 style=\"color:green\">phpBB Newest Member value has been fixed</h1>";

}
?>
<form name="w3all_fix_phpbb_newest_member" id="w3all-fix-total-members-count" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
 <input type="hidden" name="phpbb_fix_newest_member" value="1" />
<input type="submit" name="submit" id="submit" class="button button-primary" value="Fix phpBB Newest Member">
</form>
		</div>

</div>