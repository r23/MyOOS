<?php defined( 'ABSPATH' ) or die( 'forbidden' );
  if ( !current_user_can('manage_options') ) {
    die('<h3>Forbidden</h3>');
  }
// a basic wp w3all phpBB users importer into WordPress
    $up_conf_w3all_url = admin_url() . 'tools.php?page=wp-w3all-users-to-wp';
  $w3warn = '';
  global $w3all_config,$wpdb,$w3all_add_into_wp_u_capability;

 if ( !defined('W3PHPBBCONFIG') ){
    die("<h2>Wp w3all miss phpBB db configuration. Set phpBB connection values by opening:<br /><br /> Settings -> WP w3all</h2>");
  }

  $phpbb_config = W3PHPBBCONFIG;
  $phpbb_config_file = $w3all_config;
  $phpbb_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();
  $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
  $wpu_db_umtab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';

  set_time_limit(300); // set execution time to 5 min "This function has no effect when PHP is running in safe mode. There is no workaround other than turning off safe mode or changing the time limit in the php.ini."

  if(!isset($_POST["start_select"])){
      $start_select = 0;
      $limit_select = 0;
       } else {
                $start_select = $_POST["start_select"] + $_POST["limit_select_prev"];
                $limit_select = $_POST["limit_select"];
             }

if(isset($_POST["w3Ins_phpbbU"]) && !empty(trim($_POST["w3Ins_phpbbU"])) && current_user_can( 'manage_options' ))
{
  define( "W3DISABLECKUINSERTRANSFER", true ); // or wp_w3all_phpbb_registration_save() will fire and block/remove!
  $phpbbUTOadd = $_POST["w3Ins_phpbbUsername"];
  $escU = mb_strtolower($phpbbUTOadd,'UTF-8');
  $escU = esc_sql($escU);

 $phpbbU = $phpbb_conn->get_results("SELECT *
   FROM ". $phpbb_config_file["table_prefix"] ."users
   JOIN ". $phpbb_config_file["table_prefix"] ."groups ON
    ".$phpbb_config_file["table_prefix"] ."groups.group_id = ".$phpbb_config_file["table_prefix"] ."users.group_id
     WHERE LOWER(". $phpbb_config_file["table_prefix"] ."users.username) = '".$escU."'
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
         $user_username_clean = esc_sql(mb_strtolower($phpbbUname,'UTF-8'));
         $phpbbUname = esc_sql($phpbbUname);
         $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbbUname."', user_pass = '".$phpbbU[0]->user_password."', user_nicename = '".$user_username_clean."' WHERE ID = ".$user_id."");
         $w3warn = '<h2 style="color:green">User '.$phpbbUname.' successfully added into WordPress</h2>';
  } else {
         $w3warn = '<h2 style="color:red">Error:' . $user_id->get_error_message() . '</h2>';
     }
 }

}


if(isset($_POST["start_select"]) && !isset($phpbbUTOadd) && current_user_can( 'manage_options' )){
  define( "W3DISABLECKUINSERTRANSFER", true ); // or wp_w3all_phpbb_registration_save() will fire and block/remove!

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

      if( $user_id > 1 ){
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

  if( !$user_id && $not_import_user == 0 ){

     $role = $w3all_add_into_wp_u_capability;
     $contains_cyrillic = (bool) preg_match('/[\p{Cyrillic}]/u', $u->username);

  if ( is_multisite() && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$u->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$u->username) OR strlen($u->username) > 50 ){
  // if ( is_multisite() && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$u->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$u->username) OR strlen($u->username) > 50 ){
         echo '<strong>' . $u->username.'</strong><span style="color:red"> - User not added:</span> username contain characters not allowed for WordPress usernames<br />';
        } else {
                $userdata = array(
                 'user_login'       =>  $u->username,
                 'user_pass'        =>  $u->user_password,
                 'user_email'       =>  $u->user_email,
                 'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $u->user_regdate ),
                 'role'             =>  $role,
                 'user_url'         =>  $u->pf_phpbb_website
                );

          $user_id = wp_insert_user( $userdata );

      if ( ! is_wp_error( $user_id ) ) {

          $phpbb_username = preg_replace( '/\s+/', ' ', $u->username );
          $phpbb_username = esc_sql($phpbb_username);
          $user_username_clean = sanitize_user( $u->username, $strict = false );
          $user_username_clean = esc_sql(mb_strtolower($user_username_clean,'UTF-8'));

         if ($contains_cyrillic) {
          // update user_login and user_nicename and force to be what needed
          // also update the pass, since re-hashed by wp_insert_user()
          $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbb_username."', user_pass = '".$u->user_password."', user_nicename = '".$user_username_clean."', display_name = '".$phpbb_username."' WHERE ID = ".$user_id."");
          $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
          } else { // leave as is (may cleaned and different) the just created user_login
             $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '".$u->user_password."', display_name = '".$phpbb_username."' WHERE ID = '$user_id'");
             $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
            }

       }


          if ( ! is_wp_error( $user_id ) ) {
             echo "<b>Added user -> <span style=\"color:green\">". $u->username ."</span></b><br />";
          } else {
            echo $user_id->get_error_message() . ' - user <strong>'. $u->username .'</strong> not imported<br />';
          }
       }
  }

 $not_import_user = 0;

} // END foreach

  echo "<h2 style=\"color:brown\">Continue adding phpBB users into WordPress by clicking the \"Continue to transfer phpBB users into WordPress\" button ...</h2>";

} // END if ( ! empty( $phpbb_users ) ) {


  if ( empty( $phpbb_users ) ) {
        echo '<h1 style="margin-top:2.0em;color:green">No more phpBB users found. User\'s transfer into WordPress has been completed!</h1>';
        echo '<h2>All users have been transferred based on setting <i>"Add phpBB users into WordPress with specified WordPress capability"</i> (main plugin options page).<br />Deactivated users on phpBB or usernames that contains illegal WordPress characters have not been added. Existent usernames and the phpBB install admin (uid 2) have been excluded from the transfer process.</h2>';
        $t_end = true;
    }
 }

  $start_or_continue_msg = (!isset($_POST["start_select"])) ? 'Start transfer phpBB users into WordPress' : 'Continue to transfer phpBB users into WordPress';
  if( isset($t_end) ){ $start_or_continue_msg = 'Transfer complete! To re-start the transfer, reload this page'; }
 ?>

<div class="wrap" style="margin-top:4.0em;">
<div class=""><h1>Transfer phpBB Users into WordPress ( raw w3_all )</h1><h3>Note: this step is may not required (if the phpBB integration extension has been installed into phpBB), but normally when integration start it is may mandatory to transfer old existent WordPress users into phpBB using the <a href="<?php echo admin_url(); ?>tools.php?page=wp-w3all-users-to-phpbb">WP w3all transfer</a></h3>
  <h3>Note that phpBB usernames containing illegal characters into WordPress, being "cleaned" before to be added in wordpress, so that maybe phpBB's username will not match the same in WordPress in certain cases. Ex: <i style="color:#e52800">a'l @ewdw/&%$&pound;$&pound;</i> will be added as <i style="color:#e52800">al @ewdw</i><br />Resulting empty usernames after cleaned up, being reported and not added/imported</h3>
  </div>

<h4><span style="color:red">Notice</span>: do not put so hight value for users to transfer each time. It is set by default to 20 users x time, but you can change the value.<br />Try out: maybe 50, 100 or also 500 or more users to be added x time is ok for your system/server resources.<br />Let the process run on browser.<br />If error come out due to max execution time, it is necessary to adjust to a lower value the number of users to be added x time.<br />Refresh manually from browser: it will "reset the counter" of the transfer procedure.<br />
 Repeat the process by setting a lower value for users to be added x time: continue adding users until a <span style="color:green">green message</span> will display that the transfer has been completed.<br /><br />If there is an existent phpBB username on WordPress it will not be imported.
 <br />All users are transferred in WordPress based on setting <i>"Add phpBB users into WordPress with specified WordPress capability"</i> (main plugin options page).<br /> Deactivated users on phpBB, existent usernames/emails in WordPress, phpBB usernames which after clean up results to be empty and the phpBB install admin (uid 2) are excluded by the transfer process</h4>
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