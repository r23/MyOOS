<?php defined( 'ABSPATH' ) or die( 'forbidden' );
// a basic wp w3all phpBB users importer into WordPress
  $up_conf_w3all_url = admin_url() . 'options-general.php?page=wp-w3all-users-to-wp';
  $w3warn = '';

  if ( !defined('PHPBB_INSTALLED') ){
   	die("<h2>Wp w3all miss phpBB configuration file: set the correct absolute path to phpBB by opening:<br /><br /> Settings -> WP w3all</h2>");

    }

 	global $w3all_config,$wpdb,$w3all_add_into_wp_u_capability;
  $phpbb_config = unserialize(W3PHPBBCONFIG);
  $phpbb_config_file = $w3all_config;
  $phpbb_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();
  $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
  $wpu_db_umtab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';

  if(!isset($_POST["start_select"])){
      $start_select = 0;
      $limit_select = 0;
       } else {
                $start_select = $_POST["start_select"] + $_POST["limit_select_prev"];
                $limit_select = $_POST["limit_select"];
             }

if(isset($_POST["w3Ins_phpbbU"]) && !empty(trim($_POST["w3Ins_phpbbU"])) && current_user_can( 'manage_options' )){
	define( "W3DISABLECKUINSERTRANSFER", true ); // or wp_w3all_phpbb_registration_save() will fire and block/remove!
	$phpbbUTOadd = $_POST["w3Ins_phpbbUsername"];
  $escU = esc_sql($phpbbUTOadd);

 $phpbbU = $phpbb_conn->get_results("SELECT *
   FROM ". $phpbb_config_file["table_prefix"] ."users
   JOIN ". $phpbb_config_file["table_prefix"] ."groups ON
    ".$phpbb_config_file["table_prefix"] ."groups.group_id = ".$phpbb_config_file["table_prefix"] ."users.group_id
     WHERE ". $phpbb_config_file["table_prefix"] ."users.username = '".$escU."'
    ");

 if(!empty($phpbbU)){
  $phpbbUname  = $phpbbU[0]->username;
  $phpbbUid    = $phpbbU[0]->user_id;
  $phpbbUemail = $phpbbU[0]->user_email;

//  $u_real_username = sanitize_user( $phpbbUname, $strict = false );
  $user_id = username_exists( $phpbbUname );
  $user_email = email_exists( $phpbbUemail );
 } else {
      	$w3warn = '<h2 style="color:red">Error: this username do not exists into phpBB</h2>';
      	$not_import_user = 1;
      }

  if( !empty($phpbbU) && $user_id > 0 OR !empty($phpbbU) && $user_email > 0 ){
  	// there is something wrong here ...
  	$wpuserID = $user_id > 0 ? $user_id : $user_email;
  	$exist_userWP = get_user_by( 'ID', $wpuserID );
  	$w3warn = '<h2 style="color:red">Error: this username OR the email associated to the phpBB username already exists into WordPress!</h2>';
  	$not_import_user = 1;
  }

 if( !isset($not_import_user) ){

  	if ( $phpbbU[0]->group_name == 'ADMINISTRATORS' ){
      	      $role = 'administrator';
            } elseif ( $phpbbU[0]->group_name == 'GLOBAL_MODERATORS' ){
            	   $role = 'editor';
               }  else { // $role = 'subscriber'; // for all others phpBB Groups default to WP subscriber
               	 $role = $w3all_add_into_wp_u_capability;
               	}

              $userdata = array(
               'user_login'       =>  $phpbbUname,
               'user_pass'        =>  $phpbbU[0]->user_password,
               'user_email'       =>  $phpbbU[0]->user_email,
               'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $phpbbU[0]->user_regdate ),
               'role'             =>  $role
               );

  $user_id = wp_insert_user( $userdata );

 if ( ! is_wp_error( $user_id ) ) {
         // update user_login and user_nicename and force to be what needed

         $user_username_clean = esc_sql(mb_strtolower($phpbbUname,'UTF-8'));
         $phpbbUname = esc_sql($phpbbUname);
         $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbbUname."', user_nicename = '".$user_username_clean."' WHERE ID = ".$user_id."");
         $w3warn = '<h2 style="color:green">User '.$phpbbUname.' successfully added into WordPress</h2>';
 } else {
         $w3warn = '<h2 style="color:red">Error:' . $user_id->get_error_message() . '</h2>';
   }
}

}


if(isset($_POST["start_select"]) && !isset($phpbbUTOadd) && current_user_can( 'manage_options' )){
	define( "W3DISABLECKUINSERTRANSFER", true ); // or wp_w3all_phpbb_registration_save() will fire and block/remove!

	// on wp_w3all.php:
	// if ( is_multisite() OR defined('W3DISABLECKUINSERTRANSFER') ) { return; }

      // exclude bots, banned/guests groups, and install admin
 $phpbb_users = $phpbb_conn->get_results("SELECT *
 FROM ". $phpbb_config_file["table_prefix"] ."users
   LEFT JOIN ". $phpbb_config_file["table_prefix"] ."profile_fields_data ON ".$phpbb_config_file["table_prefix"] ."profile_fields_data.user_id = ".$phpbb_config_file["table_prefix"] ."users.user_id
  WHERE ". $phpbb_config_file["table_prefix"] ."users.group_id != 6
    AND ". $phpbb_config_file["table_prefix"] ."users.group_id != 1
    AND ". $phpbb_config_file["table_prefix"] ."users.user_type != 1
    AND ". $phpbb_config_file["table_prefix"] ."users.user_id != 2
  LIMIT ". $start_select .", ". $limit_select ."");

if ( ! empty( $phpbb_users ) ) {
	echo '<br /><br />';
foreach ( $phpbb_users as $u ) {

    //  $u_real_username = sanitize_user( $u->username, $strict = false );
    	$user_id = username_exists( $u->username );
    	$user_email = email_exists( $u->user_email );
      $not_import_user = 0;

    	if( $user_id ){
    		$user = get_user_by( 'ID', $user_id );
    		if( $user->user_email != $u->user_email ){
    			echo '<span style="color:#FF0000"> -> WARNING!</span> User <strong>'.$user->user_login.'</strong> existent in WP and email mismatch!<br /> -> User: <strong>'.$user->user_login.'</strong> email in WordPress -> <strong>'.$user->user_email.'</strong>, email in phpBB -> <strong>'.$u->user_email.'</strong>. <span style="color:red">Change email for this user to match the same in WP and phpBB!</span><br />';
    		  $not_import_user = 1;
    		} else {
    			echo 'Existent user: <strong>'.$user->user_login.'</strong> -> not imported<br />';
    			$not_import_user = 1;
    		  }
    	}

    	if( $user_email > 0 ){ // but this check is needed by the way, as it is done (no good practice, but work)
    		$user1 = get_user_by( 'ID', $user_email );
    		if( $user1->user_email != $u->user_email ){
    		echo 'Existent email associated with another username: <strong>'.$user->user_login.'</strong> -> not imported<br />';
    			$not_import_user = 1;
    		}
    	}

  if( !$user_id ){

     $role = $w3all_add_into_wp_u_capability;

     //////// phpBB username chars fix
     // phpBB need to have users without characters like ' that is not allowed in WP as username? // REVIEW
      $pattern = '/[^-0-9A-Za-z _.@]/';
      //$pattern = '/\'/';
      preg_match($pattern, $u->username, $matches);
       if($matches){
	       echo '<strong>' . $u->username.'</strong><span style="color:red"> - User not added:</span> username contain characters not allowed for WordPress usernames<br />';
        } else { // add the user in WP

          	// as is, it add just url of the phpBB profile fields for this user in WP
          	if(!empty($u->pf_phpbb_website)){ // (checking only for the only one we go to add) ... there are also profile fields for this user
          	// documentation not explain if is passed an empty value for url (x example) it will return some error: to avoid (and not go to check) two short arrays have been created

          		  // may add any profile field to be added in WP
          		  $userdata = array( // with profile fields array (user_url) // unique profile field in a default WP that match phpBB (after email, username and password of course)
                 'user_login'       =>  $u->username,
                 'user_pass'        =>  $u->user_password,
                 'user_email'       =>  $u->user_email,
                 'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $u->user_regdate ),
                 'role'             =>  $role,
                 'user_url'         =>  $u->pf_phpbb_website
                );
          	} else { // without profile fields array
                $userdata = array(
                 'user_login'       =>  $u->username,
                 'user_pass'        =>  $u->user_password,
                 'user_email'       =>  $u->user_email,
                 'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $u->user_regdate ),
                 'role'             =>  $role
                );
              }

if( $not_import_user == 0 ){
  $user_id = wp_insert_user( $userdata );
 if ( ! is_wp_error( $user_id ) ) {
  // update user_login and user_nicename and force to be what needed
         $user_username_clean = esc_sql(mb_strtolower($u->username,'UTF-8'));
         $u->username = esc_sql($u->username);
         $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$u->username."', user_nicename = '".$user_username_clean."', display_name = '".$u->username."' WHERE ID = ".$user_id."");
         $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$u->username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
 }
}

          if ( ! is_wp_error( $user_id ) ) {
          	 echo "<b>Added user -> <span style=\"color:green\">". $u->username ."</span></b><br />";
          } else {
            echo $user_id->get_error_message();
          }
       }
  }


} // END foreach

  echo "<h2 style=\"color:brown\">Continue adding phpBB users into WordPress by clicking the \"Continue to transfer phpBB users into WordPress\" button ...</h2>";

} // END if ( ! empty( $phpbb_users ) ) {


if ( ! empty( $phpbb_users ) ) {

     // echo "<h2 style=\"color:brown\">Continue adding WP users to phpBB by clicking the \"Continue to transfer WP users to phpBB\" button ...</h2>";

} else {
	      echo '<h1 style="margin-top:2.0em;color:green">No more phpBB users found. User\'s transfer into WordPress has been completed!</h1>';
	      echo '<h2>All users have been transferred based on setting <i>"Add newly WordPress registered users into a specified phpBB group"</i> (main plugin options page).<br />Deactivated users on phpBB or usernames that contains illegal WordPress characters have not been added. Existent usernames and the phpBB install admin (uid 2) have been excluded from the transfer process.</h2>';
        $t_end = true;
    }
}

 	$start_or_continue_msg = (!isset($_POST["start_select"])) ? 'Start transfer phpBB users into WordPress' : 'Continue to transfer phpBB users into WordPress';
  if( isset($t_end) ){ $start_or_continue_msg = 'Transfer complete! To re-start the transfer, reload this page'; }
 ?>

<div class="wrap" style="margin-top:4.0em;">
<div class=""><h1>Transfer phpBB Users into WordPress ( raw w3_all )</h1><h3>Note: this step is not required, while when integration start, it is mandatory to transfer WordPress users into phpBB using the <a href="<?php echo admin_url(); ?>options-general.php?page=wp-w3all-users-to-phpbb">WP w3all transfer</a>.</h3></div>

<h4><span style="color:red">Notice</span>: do not put so hight value for users to transfer each time. It is set by default to 20 users x time, but you can change the value.<br />Try out: maybe 50, 100 or also 500 or more users to be added x time is ok for your system/server resources.<br />If error come out due to max execution time, it is necessary to adjust to a lower value the number of users to be added x time.<br />Refresh manually from browser: it will "reset the counter" of the transfer procedure.<br />
 Repeat the process by setting a lower value for users to be added x time: continue adding users until a <span style="color:green">green message</span> will display that the transfer has been completed.<br /><br />If there is an existent phpBB username on WordPress it will not be imported.
 <br />All users are transferred in WordPress based on setting <i>"Add newly WordPress registered users into a specified phpBB group"</i> (main plugin options page).<br /> Deactivated users on phpBB, existent usernames in WordPress, phpBB usernames which contain the character ' or similar illegal charcters and the phpBB install admin (uid 2) are excluded by the transfer process.</h4>
<form name="w3all_conf_add_users_to_phpbb" id="w3all-conf-add-users-to-phpbb" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<p>
 Transfer <input type="text" name="limit_select" value="20" /> users x time
  <input type="hidden" name="limit_select_prev" value="<?php echo $limit_select; ?>" />
  <input type="hidden" name="start_select" value="<?php echo $start_select;?>" /><br /><br />
<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $start_or_continue_msg;?>">
</p></form></div>


<div class="wrap" style="margin-top:4.0em;">
<div class=""><h1>Transfer single phpBB User into WordPress</h1><h3>Insert a single phpBB username</h3></div>
<?php if(!empty($w3warn)){ echo $w3warn; } ?>
<form name="w3all_conf_add_single_user_to_phpbb" id="w3all-conf-add-single-user-to-phpbb" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<p>
 Transfer <input type="text" name="w3Ins_phpbbUsername" value="" /> phpBB username into WordPress
  <input type="hidden" name="w3Ins_phpbbU" value="1" /><br /><br />
<input type="submit" name="submit" id="submit" class="button button-primary" value="Transfer single phpBB user into WordPress">
</p></form></div>