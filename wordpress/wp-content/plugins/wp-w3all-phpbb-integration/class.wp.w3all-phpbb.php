<?php
//Copyright (C) 2024 - axew3.com
class WP_w3all_phpbb {

// lost on the way
 //protected $config = '';
 //protected $w3db_conn = '';
 //protected $phpbb_config = '';
 //protected $phpbb_user_session = '';

 #static $phpbbconnection,$phpbbconfig;
 #static $phpbbusersession;

public static function wp_w3all_phpbb_init() {

  global $phpbb_config,$w3all_get_phpbb_avatar_yn;

    self::w3all_db_connect();

  if(empty($phpbb_config)){
    return;
  }

  if ( ! defined("WPW3ALL_NOT_ULINKED") ):
    self::verify_phpbb_credentials();
  endif;

  if ( $w3all_get_phpbb_avatar_yn > 0 ):
    self::init_w3all_avatars();
  endif;

}

private static function w3all_wp_logout($redirect = ''){
        global $w3all_phpbb_connection,$phpbb_config,$w3all_config,$w3cookie_domain,$w3all_useragent;

      if (empty($phpbb_config)){
        return;
       }

        $k   = $phpbb_config["cookie_name"].'_k';
        $sid = $phpbb_config["cookie_name"].'_sid';
        $u   = $phpbb_config["cookie_name"].'_u';

 if( isset($_COOKIE[$k]) OR isset($_COOKIE[$sid]) ){

   $k_md5 = isset($_COOKIE[$k]) ? md5($_COOKIE[$k]) : '';
   $u_id =  isset($_COOKIE[$u]) ? $_COOKIE[$u] : '';
   $s_id =  isset($_COOKIE[$sid]) ? $_COOKIE[$sid] : '';

         if ( preg_match('/[^0-9A-Za-z]/',$k_md5) OR preg_match('/[^0-9A-Za-z]/',$s_id) OR preg_match('/[^0-9]/',$u_id) ){
               die( "Please clean up cookies on your browser." );
          }

  if( $u_id == 2 ){  return; }

  // avoid to delete session for a legit user
  if($redirect != 'not_logged_delete_only_cookie'){
   if( !empty($s_id) && !empty($u_id) ){
    $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."sessions WHERE session_id = '$s_id' AND session_user_id = '$u_id' OR session_user_id = '$u_id' AND session_browser = '$w3all_useragent'");
   }
   if( !empty($k_md5) && !empty($u_id) ){
    $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."sessions_keys WHERE key_id = '$k_md5' AND user_id = '$u_id'");
   }
  }

 }

    // remove phpBB cookies
      setcookie ("$k", "", 1, "/");
      setcookie ("$sid", "", 1, "/");
      setcookie ("$u", "", 1, "/");
      setcookie ("$k", "", 1, "/", "$w3cookie_domain");
      setcookie ("$sid", "", 1, "/", "$w3cookie_domain");
      setcookie ("$u", "", 1, "/", "$w3cookie_domain");

       //wp_logout(); // return error in some configuration and external plugins
       clean_user_cache(get_current_user_id());
       wp_destroy_current_session();
       wp_clear_auth_cookie();
       wp_set_current_user(0);

    if($redirect == 'wp_login_url' OR $redirect == 'not_logged_delete_only_cookie'){
      wp_redirect( wp_login_url() ); exit;
    }

    wp_redirect( home_url() ); exit;

 }

# get the phpBB connections values using a direct wpdb call
private static function get_w3all_config_db_if_empty(){
   global $wpdb;
   $wpdb_opt = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'options' : $wpdb->prefix . 'options';
   $w3all_config_db = $wpdb->get_row("SELECT * FROM $wpdb_opt WHERE option_name = 'w3all_phpbb_dbconn'",ARRAY_A);
  if(!empty($w3all_config_db['option_value'])){
     $r = unserialize($w3all_config_db['option_value']);
   return $r;
  }
  return false;
}

private static function w3all_db_connect(){
 global $w3all_phpbb_connection,$w3all_config_db,$w3all_config;

 if(defined('W3PHPBBDBCONN')){
  $w3all_config_db = W3PHPBBDBCONN; // switch to W3PHPBBDBCONN-only
 }

 if(defined('W3ALLCONNWRONGPARAMS')){ return; }

# On some configuration and cases (ex mums) the call to get_option('w3all_phpbb_dbconn') return empty value (ex when deleting an user):
# So get the phpBB connections values using a direct wpdb call
 if(empty($w3all_config_db)){
  $dbc = self::get_w3all_config_db_if_empty();
  $w3all_config_db = array();
  if(!empty($dbc["w3all_phpbb_dbuser"])){
   $w3all_config_db["dbhost"] = $dbc["w3all_phpbb_dbhost"];
   $w3all_config_db["dbport"] = $dbc["w3all_phpbb_dbport"];
   $w3all_config_db["dbname"] = $dbc["w3all_phpbb_dbname"];
   $w3all_config_db["dbuser"] = $dbc["w3all_phpbb_dbuser"];
   $w3all_config_db["dbpasswd"] = $dbc["w3all_phpbb_dbpasswd"];
   $w3all_config_db["table_prefix"] = $dbc["w3all_phpbb_dbtableprefix"];
  }
 }

if(empty($w3all_config_db) OR defined('W3ALLCONNWRONGPARAMS'))
 { return;
  } else
     {
       $w3all_config_db["dbhost"] = empty($w3all_config_db["dbport"]) ? $w3all_config_db["dbhost"] : $w3all_config_db["dbhost"] . ':' . $w3all_config_db["dbport"];
       $w3all_phpbb_connection = new wpdb($w3all_config_db["dbuser"], $w3all_config_db["dbpasswd"], $w3all_config_db["dbname"], $w3all_config_db["dbhost"]);
      }

  if(!empty($w3all_phpbb_connection->error)){
      define('W3ALLCONNWRONGPARAMS',true); // to cut any other db loop request, if db connection values or table prefix are wrong
    if (!defined('WPW3ALL_NOT_ULINKED')){
      define('WPW3ALL_NOT_ULINKED', true);
    }

  if(!empty($w3all_phpbb_connection->error->errors)){
    foreach($w3all_phpbb_connection->error->errors as $k => $v){
     foreach($v as $vv){
      $dberror = $vv;
     }
    }
   }

     if(isset($_GET['page']) && $_GET['page'] == 'wp-w3all-options' OR current_user_can('manage_options')){
       echo __('<div class="" style="font-size:0.9em;margin:30px;width:40%;background-color:#F1F1F1;border:3px solid red;position:fixed;top:120;right:0;text-align:center;z-index:99999999;padding:20px;max-height:140px;overflow:scroll"><h3 style="margin:0 10px 10px 10px"><span style="color:#FF0000;">WARNING</span></h3><strong>phpBB database connection error.</strong><br /><span style="color:#FF0000">Integration Running as USERS NOT LINKED</span> until this message will display.<br />Double check phpBB db connection values <span style="color:#FF0000">into the integration plugin admin page and <b>NOT the wp-config.php</span> (as may suggested on the warning message below).'.$dberror.'</div><br />', 'wp-w3all-phpbb-integration');
      }
    return;
   }

   self::w3all_get_phpbb_config();
  return $w3all_phpbb_connection;
}

private static function w3all_get_phpbb_config(){

   global $phpbb_config,$w3all_phpbb_connection,$w3all_config,$w3cookie_domain;

   if(!empty($phpbb_config)){ return $phpbb_config; }

   if( empty($w3all_phpbb_connection) OR empty($w3all_config["table_prefix"])){
    return array();
   }

   $a = $w3all_phpbb_connection->get_results("SELECT config_value FROM ". $w3all_config["table_prefix"] ."config WHERE config_name IN('allow_autologin','avatar_gallery_path','avatar_path','avatar_salt','cookie_domain','cookie_name','default_dateformat','default_lang','load_online_time','max_autologin_time','newest_user_id','newest_username','num_posts','num_topics','num_users','rand_seed','rand_seed_last_update','record_online_users','script_path','session_length','version') ORDER BY config_name ASC");

    if(empty($a)){ $phpbb_config = ''; return array(); }

      // alphabetical order
      $res = array( 'allow_autologin' => $a[0]->config_value,
                    'avatar_gallery_path' => $a[1]->config_value,
                    'avatar_path' => $a[2]->config_value,
                    'avatar_salt' => $a[3]->config_value,
                    'cookie_domain' => $a[4]->config_value,
                    'cookie_name' => $a[5]->config_value,
                    'default_dateformat' => $a[6]->config_value,
                    'default_lang' => $a[7]->config_value,
                    'load_online_time' => $a[8]->config_value,
                    'max_autologin_time' => $a[9]->config_value,
                    'newest_user_id' => $a[10]->config_value,
                    'newest_username' => $a[11]->config_value,
                    'num_posts' => $a[12]->config_value,
                    'num_topics' => $a[13]->config_value,
                    'num_users' => $a[14]->config_value,
                    'rand_seed' => $a[15]->config_value,
                    'rand_seed_last_update' => $a[16]->config_value,
                    'record_online_users' => $a[17]->config_value,
                    'script_path' => $a[18]->config_value,
                    'session_length' => $a[19]->config_value,
                    'version'  => $a[20]->config_value
                  );

 if( $res["cookie_domain"] != $w3cookie_domain ){
  update_option( 'w3all_phpbb_cookie', $res["cookie_domain"] );
 }

  # self::$phpbbconfig = $res;
  define("W3PHPBBCONFIG", $res);
  $phpbb_config = $res;

  return $res;

}

private static function verify_phpbb_credentials(){
  global $w3all_link_roles_groups,$w3all_phpbb_profile_fields,$w3all_url_to_cms,$w3all_phpbb_unotifications,$w3all_phpbb_unotifications_yn,$w3all_phpbb_connection,$phpbb_config,$w3all_phpbb_usession,$w3all_config,$w3all_oninsert_wp_user,$wpdb,$w3cookie_domain,$w3all_anti_brute_force_yn,$w3all_bruteblock_phpbbulist,$w3all_phpbb_lang_switch_yn,$w3all_useragent,$wp_w3all_forum_folder_wp,$w3all_add_into_wp_u_capability;

  // 2fa or some other case may require to not execute verify_phpbb_credentials() tasks when are performing some operation
  if( isset( $_GET['action'] ) && $_GET['action'] == 'validate_2fa' ){
    return;
  }

  if( isset($_GET['action']) && $_GET['action'] == 'logout' ){
         self::w3all_wp_logout();
  }

   $wpu_db_utab  = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
   $wpu_db_umtab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';

    if(empty($phpbb_config)){ // this seem stupid, but as the code flow is, you'll experience that may it is not...
     if(empty($phpbb_config)){ // may the connection db values have not still been set
       return;
     }}

        $k   = $phpbb_config["cookie_name"].'_k';
        $sid = $phpbb_config["cookie_name"].'_sid';
        $u   = $phpbb_config["cookie_name"].'_u';

        $_COOKIE[$u] = (isset($_COOKIE[$u])) ? intval($_COOKIE[$u]) : 1;
        $_COOKIE[$sid] = (isset($_COOKIE[$sid])) ? $_COOKIE[$sid] : '';
        $_COOKIE[$k] = (isset($_COOKIE[$k])) ? $_COOKIE[$k] : '';

        $current_user = wp_get_current_user();

    // before this
     if( $current_user->ID == 1 OR intval($_COOKIE[$u]) == 2 ){ return; } // exclude WP admin UID1 and phpBB admin UID2
    // then this
     if ( intval($_COOKIE[$u]) < 2 && is_user_logged_in() ) { self::w3all_wp_logout(); }

// HERE INSIDE WE ARE SECURE //
     if ( $_COOKIE[$u] > 1 ){
             if ( preg_match('/[^0-9A-Za-z]/',$_COOKIE[$k]) OR preg_match('/[^0-9A-Za-z]/',$_COOKIE[$sid]) OR preg_match('/[^0-9]/',$_COOKIE[$u]) ){
                die( "Clean up browser's cookies" );
              }

             $phpbb_k   = $_COOKIE[$k];
             $phpbb_sid = $_COOKIE[$sid];
             $phpbb_u   = $_COOKIE[$u];

 // user_type: 1 = confirmation email, deactivated

 // Bruteforce phpBB session keys Prevention check
 // see -> w3all Brute Force Prevention (more below) //
 //$w3all_bruteblock_phpbbulist[80]= 80;
 //$w3all_bruteblock_phpbbulist[150]= 150;
 //update_option( 'w3all_bruteblock_phpbbulist', $w3all_bruteblock_phpbbulist );
 // The presented cookie uid is in the black list and the wp user is not logged in?
 if( $w3all_anti_brute_force_yn == 1 && ! is_user_logged_in() && isset($w3all_bruteblock_phpbbulist[$phpbb_u]) ){
      setcookie ("w3all_set_cmsg", "phpbb_sess_brutef_error", 0, "/", $w3cookie_domain, false); // expire session, removed on phpBB_user_session_set()
       self::w3all_wp_logout('not_logged_delete_only_cookie');
      return;
 }

 if ( empty( $phpbb_k ) ){ // it is not a remember me login
  $phpbb_user_session = $w3all_phpbb_connection->get_results("SELECT *
    FROM ". $w3all_config["table_prefix"] ."sessions
    JOIN ". $w3all_config["table_prefix"] ."users ON ". $w3all_config["table_prefix"] ."users.user_id =  ". $phpbb_u ."
    AND ". $w3all_config["table_prefix"] ."sessions.session_id = '".$phpbb_sid."'
     AND ". $w3all_config["table_prefix"] ."sessions.session_user_id = ". $phpbb_u ."
     AND ". $w3all_config["table_prefix"] ."sessions.session_user_id > 2
    JOIN ". $w3all_config["table_prefix"] ."groups ON ". $w3all_config["table_prefix"] ."groups.group_id = ". $w3all_config["table_prefix"] ."users.group_id
      LEFT JOIN ". $w3all_config["table_prefix"] ."profile_fields_data ON ". $w3all_config["table_prefix"] ."profile_fields_data.user_id = ". $phpbb_u ."
      LEFT JOIN ". $w3all_config["table_prefix"] ."banlist ON ". $w3all_config["table_prefix"] ."banlist.ban_userid = ". $phpbb_u ." AND ban_exclude = 0
     GROUP BY ". $w3all_config["table_prefix"] ."sessions.session_user_id");
  } elseif ( !empty( $phpbb_k ) ){ // remember me auto login
   $phpbb_user_session = $w3all_phpbb_connection->get_results("SELECT *
    FROM ". $w3all_config["table_prefix"] ."sessions_keys
    JOIN ". $w3all_config["table_prefix"] ."users ON ". $w3all_config["table_prefix"] ."users.user_id = ".$w3all_config["table_prefix"] ."sessions_keys.user_id
    AND ". $w3all_config["table_prefix"] ."sessions_keys.key_id = '".md5($phpbb_k)."'
    AND ". $w3all_config["table_prefix"] ."users.user_id = ". $phpbb_u ."
    JOIN ". $w3all_config["table_prefix"] ."groups ON ". $w3all_config["table_prefix"] ."groups.group_id = ". $w3all_config["table_prefix"] ."users.group_id
      LEFT JOIN ". $w3all_config["table_prefix"] ."profile_fields_data ON ". $w3all_config["table_prefix"] ."profile_fields_data.user_id = ". $phpbb_u ."
      LEFT JOIN ". $w3all_config["table_prefix"] ."banlist ON ". $w3all_config["table_prefix"] ."banlist.ban_userid = ". $phpbb_u ." AND ban_exclude = 0
     GROUP BY ". $w3all_config["table_prefix"] ."sessions_keys.user_id");
  } else {
     $phpbb_user_session = array();
    }

  // Based on the above executed query '$phpbb_user_session[0]->user_id' could result empty
  // so assure after that this var is set with the correct uid

  // If it is a multisite, then Usernames can only contain lowercase letters (a-z) and numbers.
  // Setup as not linked this user (or get a loop)

    //if( is_multisite() && !empty($phpbb_user_session) && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user_session[0]->username) ){
    if( is_multisite() && !defined('WPW3ALL_USE_DEFAULT_WP_UCHARS') && !empty($phpbb_user_session) && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$phpbb_user_session[0]->username) ){
     setcookie ("w3all_set_cmsg", "phpbb_uname_chars_error", 0, "/", $w3cookie_domain, false);
     echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: logged in username contains illegal characters forbidden on this CMS. Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
     if (!defined('WPW3ALL_NOT_ULINKED')){
      define('WPW3ALL_NOT_ULINKED', true);
      return;
     }
    }

   // May phpBB session expired while the WP user is still logged in
   // keep the user logged in resetting the user's phpBB session
   // remove -> $noBFF = true; on next and -> !isset($noBFF) on following two instructions
   // to let fire self::w3all_wp_logout(); instead, so to logout the logged WP user when no valid phpBB session found

   // !! Security NOTE: 'self::phpBB_user_session_set' will not populate $phpbb_user_session
   // then setup the phpBB session for the current WP logged in user.
   // A possible bruteforce done by a logged in user will fail because the function will setup the phpBB session only for the SAME logged in WP user
   // and DO NOT populate $phpbb_user_session: the code just below wrapped inside
   // if(isset($phpbb_user_session[0])){
   // that could update user's email and pass when found mismatching, will NEVER execute
    if ( empty($phpbb_user_session) && $phpbb_u > 2 && is_user_logged_in() ) {
        self::phpBB_user_session_set($current_user);
        $noBFF = 1;
       }

  // START // w3all Brute Force Prevention // record addition
  if ( empty($phpbb_user_session) && $phpbb_u > 2 && !isset($noBFF) ){ // do not match any session and the user is not logged in WP
    // may should get only required values, not all users values
    $phpbb_user = $w3all_phpbb_connection->get_row("SELECT * FROM ".$w3all_config["table_prefix"]."users WHERE user_id = '".$phpbb_u."'");
    // index uid
    // will contain phpBB uid as value, but everything else could be added to be used
    // $w3all_bruteblock_phpbbulist[$phpbb_user->user_id] = array($phpbb_user->username, time(), IP, etc);
   if( $w3all_anti_brute_force_yn == 1 && isset($phpbb_user->user_id) ){
     $w3all_bruteblock_phpbbulist[$phpbb_user->user_id] = $phpbb_user->user_id;
     update_option( 'w3all_bruteblock_phpbbulist', $w3all_bruteblock_phpbbulist );
    }

    // Fire a fake 'not matching' login for this user, so Firewall plugins will also log this event and make their job if a bruteforce feature/option exist and it is active
   $randfpass = str_shuffle(substr(md5(time()), 0, 12));
   $phpbb_uck = !isset($phpbb_user->username) ? '' : $phpbb_user->username;
   $ulogF = wp_signon( array('user_login' => $phpbb_uck, 'user_password' => $randfpass, 'remember' => false), is_ssl() );

  } // END // w3all Brute Force Prevention // record addition

  // Since the above this should be not required, but leave in place
  if ( empty( $phpbb_user_session ) && !isset($noBFF) && is_user_logged_in() ){
     self::w3all_wp_logout();
   }

 if(isset($phpbb_user_session[0])){

   // **(more below) Assure that this array will contain the user_id
   $phpbb_user_session[0]->user_id = $phpbb_u;
   // lowercase email
   $phpbb_user_session[0]->user_email = strtolower($phpbb_user_session[0]->user_email);
   $current_user->user_email = strtolower($current_user->user_email);

 // START Get all phpBB notifications for this user
 // See Table: phpbb_notification_types
 // and https://area51.phpbb.com/docs/code/3.3.x/phpbb/notification/type.html
 // Able to improve the query? Let know!
 // The output of phpBB notifications is on file: /wp-content/plugins/wp-w3all-phpbb-integration/views/wp_w3all_phpbb_unotifications_short.php

 // NOTE that SELECT MAX(post_id) has been added for conditions that have no references within the posts table
 // so the user's data result into the array will contain records that are not as expected in these cases (as you can easily argue)
 // these cases contains the user's data to display, into the serialized $nnn->notification_data record (see wp_w3all_phpbb_unotifications_short.php)

 // Exclude bots and deactivated phpBB users
  if( $w3all_phpbb_unotifications_yn > 0 && $phpbb_user_session[0]->user_type != 1 && $phpbb_user_session[0]->group_id != 6 ) // there is almost an unread notification for this user
  {
   // Get PMs only if there are news, or the query will be slower (if there are, but even if there are no new PMS)

   // !!! NOTE that $phpbb_user_session[0]->user_new_privmsg is set to 0 if the user visit the phpBB page
   // 'User Control Panel -> Overview -> Manage notifications' EVEN if there are unread messages
   // so user_unread_privmsg used

   if($w3all_phpbb_unotifications_yn == 4) // only get the count of any notification, included non phpBB default
   {
    $w3all_phpbb_unotifications = $w3all_phpbb_connection->get_var("SELECT COUNT(*) FROM ".$w3all_config["table_prefix"]."notifications
     WHERE ".$w3all_config["table_prefix"]."notifications.user_id = ". $phpbb_u ."
     AND ".$w3all_config["table_prefix"]."notifications.notification_read = 0");
   }
    else { // default phpBB notifications

    if( $phpbb_user_session[0]->user_unread_privmsg > 0 && $w3all_phpbb_unotifications_yn == 1 )
    {
     $pmq =  "OR N.user_id = ". $phpbb_u ."
       AND N.notification_read = 0
       AND N.notification_type_id = NT.notification_type_id
       AND NT.notification_type_name IN('notification.type.pm')
       AND N.item_id = PM.msg_id
       AND U.user_id = PM.author_id
       AND P.post_id = (SELECT MAX(post_id) FROM ".$w3all_config["table_prefix"]."posts AS post_id)";
     $pmq0 = ",PM.msg_id,PM.author_id";
     $pmq1 = "JOIN ".$w3all_config["table_prefix"]."privmsgs AS PM";
    } else { $pmq = $pmq0 = $pmq1 = ''; }

     $w3all_phpbb_unotifications = $w3all_phpbb_connection->get_results("SELECT N.*,NT.notification_type_name,P.post_id,P.topic_id,P.forum_id,P.poster_id,P.post_time,P.post_subject,U.username".$pmq0."
        FROM ".$w3all_config["table_prefix"]."notifications AS N
         JOIN ".$w3all_config["table_prefix"]."notification_types AS NT
         JOIN ".$w3all_config["table_prefix"]."users AS U
         JOIN ".$w3all_config["table_prefix"]."posts AS P
         ".$pmq1."
        ON N.user_id = ". $phpbb_u ."
         AND N.notification_read = 0
         AND N.notification_type_id = NT.notification_type_id
         AND NT.notification_type_name IN('notification.type.quote','notification.type.bookmark','notification.type.post','notification.type.approve_post','notification.type.post_in_queue','notification.type.report_post','post_in_queue','notification.type.forum')
         AND P.post_id = N.item_id
         AND U.user_id = P.poster_id
        OR N.user_id = ". $phpbb_u ."
         AND N.notification_read = 0
         AND N.notification_type_id = NT.notification_type_id
         AND NT.notification_type_name IN('notification.type.topic','notification.type.approve_topic','notification.type.topic_in_queue','topic_in_queue')
         AND P.topic_id = N.item_id
         AND U.user_id = P.poster_id
        OR N.user_id = ". $phpbb_u ."
         AND N.notification_read = 0
         AND N.notification_type_id = NT.notification_type_id
         AND NT.notification_type_name IN('notification.type.group_request','notification.type.admin_activate_user')
         AND U.user_id = N.item_id
         AND P.post_id = (SELECT MAX(post_id) FROM ".$w3all_config["table_prefix"]."posts AS post_id)
        OR N.user_id = ". $phpbb_u ."
         AND N.notification_read = 0
         AND N.notification_type_id = NT.notification_type_id
         AND NT.notification_type_name IN('notification.type.disapprove_post','notification.type.disapprove_topic','notification.type.group_request_approved','notification.type.report_pm','notification.type.report_pm_closed','notification.type.report_post_closed','report_pm')
         AND U.user_id = 1
         AND P.post_id = (SELECT MAX(post_id) FROM ".$w3all_config["table_prefix"]."posts AS post_id)
          ".$pmq."
        GROUP BY N.notification_id ORDER BY N.notification_id DESC");

   }
  } // END Get all phpBB notifications for this user

  if( $current_user->user_email != $phpbb_user_session[0]->user_email )
  {
    // If this is an user logged in phpBB, with another username/email, and the WP user logged in is another
    // avoid going on that will cause an update to the same (and of the other) phpbb user email
    // reset the wp user, so after let login the presented phpBB user session
     if(email_exists($phpbb_user_session[0]->user_email)){
       clean_user_cache($current_user->ID);
       wp_destroy_current_session();
       wp_clear_auth_cookie();
       wp_set_current_user(0);
     }
  }

 // Banned Deactivated trick // +- the same done into w3_check_phpbb_profile_wpnu()
 // Check for ban_id: if not empty then almost a ban by IP or EMAIL or USERNAME exists
 // Do not know if there is some other ban row that can exists into 'banlist', because only the first found retrieved into the query above

 // The complete ban check is done when user login in wordpress, because on the above main query this has been removed
 // REMOVED
      //OR ". $w3all_config["table_prefix"] ."banlist.ban_email = ". $w3all_config["table_prefix"] ."users.user_email AND ban_exclude = 0
      //OR ". $w3all_config["table_prefix"] ."banlist.ban_ip = ". $w3all_config["table_prefix"] ."sessions.session_ip AND ban_exclude = 0
 // THESE ARE ONLY EXECUTED, WHEN USER LOGIN (ON WP)
 // LIKE PHPBB DO: to cause an immediate user logout, the user in phpBB NEED to be banned by username! (so it is stored as ban_userid value)

 if( !empty($phpbb_user_session[0]->ban_id) && !defined("W3BANCKEXEC") ){

  if( $current_user->ID > 1 )
  {

  if( $phpbb_user_session[0]->ban_end == 0 OR $phpbb_user_session[0]->ban_end > time() ){ // no further check necessary, the only one ban value retrieved here, is a ban that never expire or that is still active
     setcookie ("w3all_set_cmsg", "phpbb_ban", 0, "/", $w3cookie_domain, false);
     //$wpdb->query("UPDATE $wpu_db_utab SET meta_value = 'a:0:{}' WHERE user_id = '$wp_user_data->ID' AND meta_key = 'wp_capabilities'");
     self::w3all_wp_logout('wp_login_url');
  }

     // NOTE TODO: could be set the bruteforce record for this user (also) here, so at next time, the code flow will stop more above without wasting resources until here

    // but if a ban exist, even if expired, check by the way this user for bans
    // w3_phpbb_ban() function will remove expired bans: so the next time we'll be here up and running, in case

   if( self::w3_phpbb_ban($phpbb_u, $phpbb_user_session[0]->username, $phpbb_user_session[0]->user_email) === true && $phpbb_user_session[0]->group_name != 'ADMINISTRATORS' OR $phpbb_user_session[0]->ban_userid > 2 && $phpbb_user_session[0]->group_name != 'ADMINISTRATORS' )
   {
    setcookie ("w3all_set_cmsg", "phpbb_ban", 0, "/", $w3cookie_domain, false);
     //$wpdb->query("UPDATE $wpu_db_utab SET meta_value = 'a:0:{}' WHERE user_id = '$wp_user_data->ID' AND meta_key = 'wp_capabilities'");
    self::w3all_wp_logout('wp_login_url');
   }

  }
 } // end ban

 if ( $phpbb_user_session[0]->user_type == 1 ){
    setcookie ("w3all_set_cmsg", "phpbb_deactivated", 0, "/", $w3cookie_domain, false);
     //$wpdb->query("UPDATE $wpu_db_utab SET meta_value = 'a:0:{}' WHERE user_id = '$wp_user_data->ID' AND meta_key = 'wp_capabilities'");
    self::w3all_wp_logout('wp_login_url');
  }

   $w3all_phpbb_usession = $phpbb_user_session[0];
   // old way
   $w3_phpbb_user_session = serialize($phpbb_user_session);
   define("W3PHPBBUSESSION", $w3_phpbb_user_session);

    // some lang may differ about notation on both phpBB and WP ... so some edit may sometime will be necessary to adjust ...
    // for example: phpBB Persian lang is 'fa' while in WP  could be 'ps'. Check also that could be the contrary
    if( $phpbb_user_session[0]->user_lang == 'fa' ){ $phpbb_user_session[0]->user_lang = 'ps'; }

 if ( is_user_logged_in() ) {
      // expired session // assumed _k as valid without checking if it is expired
      if ( empty( $phpbb_k ) && ( time() - $phpbb_config["session_length"] ) > $phpbb_user_session[0]->session_time )
      {
        self::w3all_wp_logout();
      } else
      { // UPDATE

# If option enabled: Switch WP user to specified Role in WordPRess, when Group updated in phpBB
# switch groups/roles: also see -> public static function phpbb_update_profile($user_id, $old_user_data) {

 if( $w3all_link_roles_groups > 0 )
 {
   if( $w3all_link_roles_groups == 3 )
   {
    // include/execute code for this specific case (when on verify_credentials)
    // update user's Role in WP, if main group changed in some way in phpBB
    $w3all_groups_to_roles_on_verify_credentials = true;
   } elseif( $w3all_link_roles_groups == 2 )
    {
     // include/execute code for this specific case (when on verify_credentials)
     // update user's Group in phpBB, if main role changed in some way in WP (not updated by admin -> phpbb_update_profile() but due to plugins or some else )
     $w3all_roles_to_groups_on_verify_credentials = true;
    }

   if (file_exists(ABSPATH.'wp-content/plugins/wp-w3all-custom/wpRoles_phpbbGroups.php')){
     include(ABSPATH.'wp-content/plugins/wp-w3all-custom/wpRoles_phpbbGroups.php');
   } else {
      include(WPW3ALL_PLUGIN_DIR.'common/wpRoles_phpbbGroups.php');
     }
 }

    $update = $w3all_phpbb_connection->query("UPDATE ". $w3all_config["table_prefix"] ."sessions SET session_time = '".time()."' WHERE session_id = '$phpbb_sid' OR session_user_id = '".$phpbb_user_session[0]->user_id."' AND session_browser = '".$w3all_useragent ."'");
   // check for match between wp and phpbb profile fields
    $phpbb_user_session[0]->pf_phpbb_website = (!empty($phpbb_user_session[0]->pf_phpbb_website)) ? $phpbb_user_session[0]->pf_phpbb_website : $current_user->user_url;

  // only if something to be updated
  if( $phpbb_user_session[0]->user_password != $current_user->user_pass OR strtolower($phpbb_user_session[0]->user_email) != strtolower($current_user->user_email) OR $phpbb_user_session[0]->pf_phpbb_website != $current_user->user_url )
     {
       $phpbb_upass = $phpbb_user_session[0]->user_password;
       $phpbb_uemail = $phpbb_user_session[0]->user_email;
       $phpbb_uurl = $phpbb_user_session[0]->pf_phpbb_website;
       $current_userID = $current_user->ID;
       $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '$phpbb_upass', user_email = '$phpbb_uemail', user_url = '$phpbb_uurl' WHERE ID = '$current_userID'");

  if( $w3all_phpbb_lang_switch_yn == 1 )
   { // this impact the global performance
      $wp_umeta = get_user_meta($current_user->ID, '', false);

      if( empty($wp_umeta['locale'][0]) ){ // wp lang for this user ISO 639-1 Code. en_EN // en = Lang code _ EN = Country code
            if( strlen(get_locale()) == 2 ){ $wp_lang_x_phpbb = strtolower(get_locale());
          } else {
           $wp_lang_x_phpbb = substr(get_locale(), 0, strpos(get_locale(), '_')); // should extract Lang code ISO Code phpBB suitable for this lang
           }
       } else {
          if( strlen($wp_umeta['locale'][0]) == 2 ){ $wp_lang_x_phpbb = strtolower($wp_umeta['locale'][0]);
          } else {
            $wp_lang_x_phpbb = substr($wp_umeta['locale'][0], 0, strpos($wp_umeta['locale'][0], '_')); // should extract Lang code ISO Code phpBB suitable for this lang
           }
        }

        $wp_lang_x_phpbb = empty($wp_lang_x_phpbb) ? 'en' : $wp_lang_x_phpbb;

      if( $phpbb_user_session[0]->user_lang != $wp_lang_x_phpbb )
      {
             // get array of installed langs in WP and build one to check against
             // NOTE: to switch to a lang with different notation, example x Persian, FA x phpBB and PE x WP, this switch need to be added above where line:
             // if( $phpbb_user_session[0]->user_lang == 'fa' ){ $phpbb_user_session[0]->user_lang = 'ps'; }
             // adding the needed switch in case ...
            $wpLangs = wp_get_installed_translations('core');
             foreach ($wpLangs as $k => $v){
              if($k=='default'){
               $langAK=$v;
              }
             }
         $wp_langs = array_keys($langAK);
        if(is_array($wp_langs) && in_array($phpbb_user_session[0]->user_lang,$wp_langs)){
           $x_wp_locale = $phpbb_user_session[0]->user_lang; // assign the same if found
        }
        if (!isset($x_wp_locale)){
                $x_wp_locale = empty($phpbb_user_session[0]->user_lang) ? get_locale() : $phpbb_user_session[0]->user_lang . '_' . strtoupper($phpbb_user_session[0]->user_lang); // should build to be WP compatible into something like it_IT or set emtpy for en WP default
           }
        update_user_meta($current_user->ID, 'locale', $x_wp_locale);
      }
    }


   if ( !function_exists( 'refresh_user_details' ) ) {
    require_once ABSPATH . '/wp-admin/includes/ms.php';
   }

   refresh_user_details($current_user->ID);

  } // END // only if something to update

} // END update

    return;

} // END is_user_logged_in()

     // fix nicenames after addition
     // * update user_login and user_nicename for Cyrillic
     // [^-0-9A-Za-z _.@] check done before insertion

      // the username is too long or contain illegal chars
      // DISMISSED --> here checking only for , ' " \ and / chars, but regexp for legal chars in WP should be this: '/[^-0-9A-Za-z _.@]/'

      // if mums allowed only letters and numbers '/[^0-9A-Za-z]/' mums
      // preg_match('/[^-0-9A-Za-z _.@]/',$phpbb_user_session[0]->username) // default wp

      $phpbb_user_session[0]->username = mb_convert_encoding($phpbb_user_session[0]->username, "UTF-8");
      $contains_cyrillic = (bool) preg_match('/[\p{Cyrillic}]/u', $phpbb_user_session[0]->username);
      $phpBB_user_sanitized = sanitize_user( $phpbb_user_session[0]->username, $strict = false );

      if ( is_multisite() && !defined('WPW3ALL_USE_DEFAULT_WP_UCHARS') && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$phpbb_user_session[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user_session[0]->username) OR strlen($phpbb_user_session[0]->username) > 50 OR strlen($phpBB_user_sanitized) < 1 ){
        // if ( is_multisite() && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user_session[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user_session[0]->username) OR strlen($phpbb_user_session[0]->username) > 50 OR strlen($phpBB_user_sanitized) < 1 ){
        // avoid a loop if on forum's page
        if( isset($_SERVER['REQUEST_URI']) && !empty($wp_w3all_forum_folder_wp) && strstr($_SERVER['REQUEST_URI'], $wp_w3all_forum_folder_wp) ){
         echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your username contains illegal characters not allowed on this cms or contains more than 50 characters.<br />The forum cannot be displayed on this page.<br />Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
         exit;
        }

       echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your username contains illegal characters not allowed on this cms or contains more than 50 characters.<br />Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
       return;

      }

      $ck_wpun_exists = username_exists($phpbb_user_session[0]->username); // this way, if allowing any char, is not the right way to check if phpBB usernames allowed with forbidden chars in wp
      $user_id = email_exists($phpbb_user_session[0]->user_email); // this is the right way, if email update only allowed in wp, or login and email update done only in phpBB side

 if ( ! $user_id && ! $ck_wpun_exists ) { // this phpBB user do not exist in WP

        if ( $phpbb_user_session[0]->group_name == 'ADMINISTRATORS' ){
                 $role = 'administrator';
            } elseif ( $phpbb_user_session[0]->group_name == 'GLOBAL_MODERATORS' ){
                 $role = 'editor';
               }  else {
                 $role = $w3all_add_into_wp_u_capability;
                }

              $userdata = array(
               'user_login'       =>  $phpbb_user_session[0]->username,
               'user_pass'        =>  $phpbb_user_session[0]->user_password,
               //'user_email'       =>  $phpbb_user_session[0]->user_email,
               'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $phpbb_user_session[0]->user_regdate ),
               'role'             =>  $role,
               );

   $w3all_oninsert_wp_user = 1; // declared into wp_w3all.php: if creating an user in wp, avoid checks about duplicate users email into -> wp_w3all_phpbb_registration_save()
   $user_id = wp_insert_user( $userdata );
   $on_ins = 1;

  if ( is_wp_error( $user_id ) ) {
    echo '<div style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><p>' . $user_id->get_error_message() . '</p></div>';
    echo __('<div><p style="padding:30px;background-color:#fff;color:#000;font-size:1.0em"><strong>ERROR: try to reload page, but if the error persist may mean that the forum\'s logged in username contains illegal characters that are not allowed on this system. Please contact an administrator.</strong></p></div>', 'wp-w3all-phpbb-integration');
    exit;
   }

// NOTE: duplicated users insertions on iframe first time login resolved more below, on redirect for iframe
// if  $on_ins , do not redirect to same page forum, but to another wp page, or duplicated user insertion will may happen
// if redirect will not fire, assure all will go as needed here, removing the duplicated user

  $wp_duplicated_u = $wpdb->get_results("SELECT * FROM $wpu_db_utab WHERE LOWER(user_email) = '".$phpbb_user_session[0]->user_email."'",ARRAY_A);
  if(count($wp_duplicated_u) > 1){
   $dupuser = array_pop($wp_duplicated_u);
   if($dupuser['ID'] > 1){
     if ( !function_exists( 'wp_delete_user' ) ) {
       require_once ABSPATH . '/wp-admin/includes/user.php';
     }
    wp_delete_user( $dupuser['ID'] );
    if( is_multisite() ){
      if ( !function_exists( 'wpmu_delete_user' ) ) {
       require_once ABSPATH . '/wp-admin/includes/ms.php';
      }
     wpmu_delete_user( $dupuser['ID'] );
     }
   }
  }

       $phpbb_username = preg_replace( '/\s+/', ' ', $phpbb_user_session[0]->username );
       $phpbb_username = esc_sql($phpbb_username);
       $phpbb_uemail = $phpbb_user_session[0]->user_email;
       $phpbb_upass = $phpbb_user_session[0]->user_password;
       $user_username_clean = sanitize_user( $phpbb_user_session[0]->username, $strict = false );
       $user_username_clean = esc_sql(mb_strtolower($user_username_clean,'UTF-8'));

        if ( ! is_wp_error( $user_id ) && $contains_cyrillic ) {
         // update user_login and user_nicename and force to be what needed
         $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbb_username."', user_pass = '".$phpbb_upass."', user_nicename = '".$user_username_clean."', user_email = '".$phpbb_uemail."', display_name = '".$phpbb_username."' WHERE ID = ".$user_id."");
         $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
        } elseif ( ! is_wp_error( $user_id ) ) { // leave as is (may cleaned and different) the just created user_login
            $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '".$phpbb_upass."', user_email = '".$phpbb_uemail."', display_name = '".$phpbb_username."' WHERE ID = '$user_id'");
            $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
          }
 }

  if ( ! is_user_logged_in() && ! is_wp_error( $user_id ) && $user_id > 1 )
  {

    $user = $wpdb->get_row("SELECT * FROM $wpu_db_utab WHERE ID = '".$user_id."' OR LOWER(user_email) = '".$phpbb_user_session[0]->user_email."' ");
       if(empty($user)){
        $user = get_user_by( 'ID', $user_id );
       }

    if( empty($user) OR $user->ID < 2 ) { return; }

       $remember = ( empty($phpbb_k) ) ? false : 1;
       wp_set_current_user( $user->ID, $user->user_login );
       wp_set_auth_cookie( $user->ID, $remember, is_ssl() );
       if(!defined("PHPBBAUTHCOOKIEREL")){
        define("PHPBBAUTHCOOKIEREL",true);
       }

       if ( !defined( 'WP_ADMIN' ) ) // or throw Fatal error: Uncaught TypeError: call_user_func_array(): Argument #1 ($callback) must be a valid callback, function "wp_w3all_phpbb_login" not found or invalid function name ...
       {
        //do_action( 'wp_login', $user->user_login, $user );
       }

    if ( ! empty( $_REQUEST['redirect_to'] ) ) {
      $redirect_to = $_REQUEST['redirect_to'];
    }

    if ( ( empty( $redirect_to ) || $redirect_to == admin_url() ) )
    {
      // If the user doesn't belong to a blog, send them to user admin. If the user can't edit posts, send them to their profile.
      if ( is_multisite() && !get_active_blog_for_user($user->ID) && !is_super_admin( $user->ID ) )
        $redirect_to = user_admin_url();
      elseif ( is_multisite() )
        $redirect_to = get_dashboard_url( $user->ID );
      elseif ( is_admin() )
        $redirect_to = admin_url( 'profile.php' );
     // (try) check if it is a login done via phpBB into WP iframed page AND AVOID redirect to iframe if it is the first time login, so subsequent addition of the user in WP
     // or duplicate WP insertion in wp will happen!!
     // if it is a first time login, AVOID the redirect to page-forum (if in iframe mode and user regitered then login in phpBB iframed)
     if( !isset($on_ins) && isset($_SERVER['REQUEST_URI']) && !empty($wp_w3all_forum_folder_wp) && strstr($_SERVER['REQUEST_URI'], $wp_w3all_forum_folder_wp) )
     {
      $redirect_to = home_url() . '/' . $wp_w3all_forum_folder_wp; // this will cause duplicated user insert in wp, if phpBB login first time done in phpBB iframed
      wp_redirect( $redirect_to ); exit();
     }

     if( !empty($redirect_to) ){
      wp_redirect($redirect_to); exit();
     }
    }
    // again, may $redirect_to is not emtpy
     if( !isset($on_ins) && isset($_SERVER['REQUEST_URI']) && !empty($wp_w3all_forum_folder_wp) && strstr($_SERVER['REQUEST_URI'], $wp_w3all_forum_folder_wp) )
     {
      $redirect_to = home_url() . '/' . $wp_w3all_forum_folder_wp; // this will cause duplicated user insert in wp, if phpBB login first time done in phpBB iframed
      wp_redirect( $redirect_to ); exit();
     }

  }

    return;

  } // END //  if(isset($phpbb_user_session[0])){

 }  // END if ( $_COOKIE[$u] > 1 ){ // HERE INSIDE WE ARE SECURE // END //

 return;

}  // END // verify_phpbb_credentials(){ // END //


private static function last_forums_topics($ntopics = 10){

# Note that userID 2 in phpBB goes into default phpBB guest user group
# because $w3all_phpbb_usession is empty for this uid (excluded)
 global $w3all_phpbb_connection,$w3all_phpbb_usession,$w3all_config,$w3all_exclude_phpbb_forums,$w3all_wlastopicspost_max,$w3all_lasttopic_avatar_num,$w3all_get_topics_x_ugroup,$w3all_heartbeat_phpbb_lastopics_num;

 if( empty($w3all_phpbb_connection) ){ return; }

 /*$topics = array();
 $ntopics0 = $w3all_wlastopicspost_max > $w3all_lasttopic_avatar_num ? $w3all_wlastopicspost_max : $w3all_lasttopic_avatar_num;
 $ntopics  = $ntopics0 > $ntopics ? $ntopics0 : 10;
 $ntopics  = $w3all_heartbeat_phpbb_lastopics_num > $ntopics ? $w3all_heartbeat_phpbb_lastopics_num : $ntopics;
 $topics_x_ugroup = $no_forums_list = '';*/
 $topics = array();
 $ntopics0 = $w3all_wlastopicspost_max > $w3all_lasttopic_avatar_num ? $w3all_wlastopicspost_max : $w3all_lasttopic_avatar_num;
 $ntopics  = $ntopics0 > $ntopics ? $ntopics0 : $ntopics;
 $ntopics  = $w3all_heartbeat_phpbb_lastopics_num > $ntopics ? $w3all_heartbeat_phpbb_lastopics_num : $ntopics;
 $ntopics = $ntopics > $ntopics0 ? $ntopics : 10;
 $topics_x_ugroup = $no_forums_list = '';

 if($w3all_get_topics_x_ugroup == 1){

  if (!empty($w3all_phpbb_usession)) {
    $ug = $w3all_phpbb_usession->group_id;
    $ui = $w3all_phpbb_usession->user_id;
   } else {
     $ug = $ui = 1; // the default phpBB guest user group
    }

  # 16 ROLE_FORUM_NOACCESS into acl_roles table
  # get all forums ids which the user have no permissions to read
  $gaf = $w3all_phpbb_connection->get_results("SELECT AG.forum_id
    FROM ".$w3all_config["table_prefix"]."acl_groups AS AG
    JOIN ".$w3all_config["table_prefix"]."user_group AS UG ON( UG.user_id = ".$ui."
     AND UG.group_id = AG.group_id
     AND AG.auth_role_id = 16 )");

   if(!empty($gaf)){
      $gf = '';
       foreach( $gaf as $v ){
        $gf .= $v->forum_id.',';
       }
    $gf = substr($gf, 0, -1);
    $topics_x_ugroup = "AND T.forum_id NOT IN(".$gf.")";
   }
 }

  if ( preg_match('/^[0-9,]+$/', $w3all_exclude_phpbb_forums ))
  {
    $exp = explode(",", $w3all_exclude_phpbb_forums);
    $exc_forums_list = '';
     foreach($exp as $k => $v){
      $exc_forums_list .= "'".$v."',";
     }
    $nfl = substr($exc_forums_list, 0, -1);
    $exc_forums_list = "AND T.forum_id NOT IN(".$nfl.")";
  } else { $exc_forums_list = ''; }

      $topics = $w3all_phpbb_connection->get_results("SELECT
       T.topic_id,T.forum_id,T.topic_title,T.topic_last_post_id,T.topic_last_poster_id,T.topic_last_poster_name,T.topic_last_post_time,
       P.post_id,P.topic_id,P.forum_id,P.poster_id,P.post_time,P.post_username,P.post_subject,P.post_text,P.post_visibility,
       U.user_id,U.username,U.user_email
       FROM ".$w3all_config["table_prefix"]."topics AS T
       JOIN ".$w3all_config["table_prefix"]."posts AS P on (T.topic_last_post_id = P.post_id and T.forum_id = P.forum_id)
       JOIN ".$w3all_config["table_prefix"]."users AS U on U.user_id = T.topic_last_poster_id
       WHERE T.topic_visibility = 1
       ".$exc_forums_list."
       ".$topics_x_ugroup."
       AND P.post_visibility = 1
       ORDER BY T.topic_last_post_time DESC
       LIMIT 0,$ntopics");

      /*$topics = $w3all_phpbb_connection->get_results("SELECT T.*, P.*, U.*
       FROM ".$w3all_config["table_prefix"]."topics AS T
       JOIN ".$w3all_config["table_prefix"]."posts AS P on (T.topic_last_post_id = P.post_id and T.forum_id = P.forum_id)
       JOIN ".$w3all_config["table_prefix"]."users AS U on U.user_id = T.topic_last_poster_id
       WHERE T.topic_visibility = 1
       ".$no_forums_list."
       ".$topics_x_ugroup."
       AND P.post_visibility = 1
       ORDER BY T.topic_last_post_time DESC
       LIMIT 0,$ntopics");*/

     $t = is_array($topics) ? serialize($topics) : serialize(array());
     if(!defined("W3PHPBBLASTOPICS")){
      define( "W3PHPBBLASTOPICS", $t ); // see also wp_w3all.php and wp_w3all_assoc_phpbb_wp_users in this class
     }

    return $topics;
}


# heartbeat last_forums_topics
public static function heartbeat_last_forums_topics($ntopics = 12){

 #$ntopics = 12; // Set as max 12 topics/posts, the shortcode param can define only less than 12

 global $w3all_phpbb_connection,$w3all_phpbb_usession,$w3all_config,$w3all_exclude_phpbb_forums,$w3all_wlastopicspost_max,$w3all_lasttopic_avatar_num,$w3all_get_topics_x_ugroup;

 if( empty($w3all_phpbb_connection) ){ return; }

 $topics = array();
 #$ntopics0 = $w3all_wlastopicspost_max > $w3all_lasttopic_avatar_num ? $w3all_wlastopicspost_max : $w3all_lasttopic_avatar_num;
 #$ntopics  = $ntopics0 > $ntopics ? $ntopics0 : 10;
 $topics_x_ugroup = $no_forums_list = '';

  if (empty( $w3all_exclude_phpbb_forums )){

   $topics = $w3all_phpbb_connection->get_results("SELECT T.*, P.*, U.*
    FROM ".$w3all_config["table_prefix"]."topics AS T
    JOIN ".$w3all_config["table_prefix"]."posts AS P on (T.topic_last_post_id = P.post_id and T.forum_id = P.forum_id)
    JOIN ".$w3all_config["table_prefix"]."users AS U on U.user_id = T.topic_last_poster_id
    WHERE T.topic_visibility = 1
    ".$topics_x_ugroup."
    AND P.post_visibility = 1
    ORDER BY T.topic_last_post_time DESC
    LIMIT 0,$ntopics");

  } else {
    if ( preg_match('/^[0-9,]+$/', $w3all_exclude_phpbb_forums )) {
          $exp = explode(",", $w3all_exclude_phpbb_forums);
          $no_forums_list = '';
           foreach($exp as $k => $v){
            $no_forums_list .= "'".$v."',";
           }
            $nfl = substr($no_forums_list, 0, -1);
            $no_forums_list = "AND T.forum_id NOT IN(".$nfl.")";
    } else {
            $no_forums_list = '';
           }

   $topics = $w3all_phpbb_connection->get_results("SELECT T.*, P.*, U.*
    FROM ".$w3all_config["table_prefix"]."topics AS T
    JOIN ".$w3all_config["table_prefix"]."posts AS P on (T.topic_last_post_id = P.post_id and T.forum_id = P.forum_id)
    JOIN ".$w3all_config["table_prefix"]."users AS U on U.user_id = T.topic_last_poster_id
    WHERE T.topic_visibility = 1
    ".$no_forums_list."
    ".$topics_x_ugroup."
    AND P.post_visibility = 1
    ORDER BY T.topic_last_post_time DESC
    LIMIT 0,$ntopics");

  }

    return $topics;
}


private static function phpBB_user_session_set($wp_user_data){

  if(defined("W3ALL_SESSION_ARELEASED")){ return; }
// SECURITY NOTE - NEVER add/populate the '$phpbb_user_session' var (see verify_credentials)
  global $w3all_phpbb_connection,$phpbb_config,$w3all_config,$wpdb,$w3all_useragent,$w3all_anti_brute_force_yn,$w3all_bruteblock_phpbbulist,$w3cookie_domain;

  if ( empty($w3all_phpbb_connection) OR !isset($wp_user_data->ID) OR $wp_user_data->ID == 1 ){
   return;
  }

 if(empty($phpbb_config)){
  self::w3all_get_phpbb_config();
 }
  if(empty($phpbb_config)){
  return;
 }

        $k   = $phpbb_config["cookie_name"].'_k';
        $sid = $phpbb_config["cookie_name"].'_sid';
        $u   = $phpbb_config["cookie_name"].'_u';
     // if the email changed in phpBB without using the phpBB WordPress extension, by email here will not match
     $wp_user_data->user_email = $uemail = strtolower($wp_user_data->user_email);
     $wp_user_data->user_login = $ulogin = esc_sql(mb_strtolower($wp_user_data->user_login,'UTF-8'));
     $phpbb_u = $w3all_phpbb_connection->get_row("SELECT * FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(user_email) = '$uemail'");

 if( empty($phpbb_u) ){ return; }

  $wpum_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';

// Start ban/deactivated

  if( self::w3_phpbb_ban($phpbb_u->user_id, $phpbb_u->username, $phpbb_u->user_email) === true ){
    setcookie ("w3all_set_cmsg", "phpbb_ban", 0, "/", $w3cookie_domain, false);
    self::w3all_wp_logout('wp_login_url');
   }

 if($phpbb_u->user_type == 1){
    setcookie ("w3all_set_cmsg", "phpbb_deactivated", 0, "/", $w3cookie_domain, false);
    self::w3all_wp_logout('wp_login_url');
  }
// End ban/deactivated

    $phpbb_user_id = $phpbb_u->user_id;

      if ( $phpbb_u->user_type == 1 && isset($wp_user_data->ID) && $wp_user_data->ID > 1 ){ // is this user deactivated/banned in phpBB? / logout/and deactivate in WP
         //update_user_meta($user_id, 'wp_capabilities', 'a:0:{}'); maybe substitute with this
         //$wpu_db_utab = $wpdb->prefix . 'usermeta';
         $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';
         $wpdb->query("UPDATE $wpu_db_utab SET meta_value = 'a:0:{}' WHERE user_id = '$wp_user_data->ID' AND meta_key = 'wp_capabilities'");
          return;
       }

       $time = time();
       $val = md5($phpbb_config["rand_seed"] . microtime()); // to user_form_salt
       $phpbb_rand_seed = md5( $phpbb_config["rand_seed"] . $val . rand() ); // the rand seed to be updated

        $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."config SET config_value = '$phpbb_rand_seed' WHERE config_name = 'rand_seed'");
        $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."config SET config_value = '$time' WHERE config_name = 'rand_seed_last_update'");

        $w3session_id = md5(substr($val, 4, 16));
        $uip = (! filter_var(trim($_SERVER["REMOTE_ADDR"]), FILTER_VALIDATE_IP)) ? '127.0.0.1' : trim($_SERVER["REMOTE_ADDR"]);

       $auto_login = 1; // should be based on phpBB config setting, while it release a remember me login in any case in this way

       $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."sessions WHERE session_user_id = '$phpbb_user_id' AND session_browser = '$w3all_useragent'");
       $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."sessions (session_id, session_user_id, session_last_visit, session_start, session_time, session_ip, session_browser, session_forwarded_for, session_page, session_viewonline, session_autologin, session_admin, session_forum_id)
          VALUES ('$w3session_id', '$phpbb_user_id', '$time', '$time', '$time', '$uip', '$w3all_useragent', '', 'index.php', '1', '$auto_login', '0', '0')");

      $key_id = hexdec(substr($w3session_id, 0, 8));
      $valk = md5($phpbb_rand_seed . microtime() . $key_id);

      // 20 to 32 rand string
      $valplus = str_shuffle(md5(time()) . md5($phpbb_rand_seed));
      $key_id_k = str_shuffle(substr($valk, rand(1,10), 16) . substr($valplus, rand(1,10), rand(4,16))); // to k
      $key_id_sk = md5($key_id_k); // to sessions_keys

         $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."sessions_keys (key_id, user_id, last_ip, last_login)
          VALUES ('$key_id_sk', '$phpbb_user_id', '$uip', '$time')");

   // --> Brute force related <-- // Clean up phpBB login_attempts and maintain healty array
      $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."login_attempts WHERE user_id = '$phpbb_user_id' AND attempt_browser = '$w3all_useragent'");

      $tot = is_array($w3all_bruteblock_phpbbulist) ? count($w3all_bruteblock_phpbbulist) : '';

     if($w3all_anti_brute_force_yn == 1 && !empty($w3all_bruteblock_phpbbulist)){

      if( is_array($w3all_bruteblock_phpbbulist) && $tot > 4000 ){
       $w3all_bruteblock_phpbbulist = array_slice($w3all_bruteblock_phpbbulist, 100, $tot, true); // reduce of 100 removing olders
      }

       unset($w3all_bruteblock_phpbbulist[$phpbb_user_id]);
       $w3all_bruteblock_phpbbulist = empty($w3all_bruteblock_phpbbulist) ? '' : $w3all_bruteblock_phpbbulist;
       update_option( 'w3all_bruteblock_phpbbulist', $w3all_bruteblock_phpbbulist );

       // Remove cookie msg that fire on wp login if it exist
        setcookie ("w3all_bruteblock", "", time() - 31622400, "/", "$w3cookie_domain");
      }
   // END --> Brute force related <--

      // This way affected by phpBB ACP setting
      // $cookie_expire = $phpbb_config['max_autologin_time'] > 0 ? time() + (86400 * (int) $phpbb_config['max_autologin_time']) : time() + 31536000;
      // This way, setup for 1 year by the way
      $cookie_expire = time() + 31536000;

      $secure = is_ssl();
      if(empty($w3cookie_domain)){
        $w3cookie_domain = 'localhost';
      }

      if( $secure && version_compare(phpversion(), '7.3.0', '>') ){
       setcookie("$k", "$key_id_k", [ 'expires' => $cookie_expire, 'path' => '/', 'domain' => $w3cookie_domain, 'secure' => $secure, 'httponly' => true, 'samesite' => 'None' ]);
       setcookie("$sid", "$w3session_id", [ 'expires' => $cookie_expire, 'path' => '/', 'domain' => $w3cookie_domain, 'secure' => $secure, 'httponly' => true, 'samesite' => 'None' ]);
       setcookie("$u", "$phpbb_user_id", [ 'expires' => $cookie_expire, 'path' => '/', 'domain' => $w3cookie_domain, 'secure' => $secure, 'httponly' => true, 'samesite' => 'None' ]);
      } else {
        setcookie ("$k", "$key_id_k", $cookie_expire, "/", $w3cookie_domain, $secure, true);
        setcookie ("$sid", "$w3session_id", $cookie_expire, "/", $w3cookie_domain, $secure, true);
        setcookie ("$u", "$phpbb_user_id", $cookie_expire, "/", $w3cookie_domain, $secure, true);
       }

   define("W3ALL_SESSION_ARELEASED", true);

   # Fix the fact, that when the login is done on frontend pages, the user seem to be not logged even if the session has been released
   # Anyway, here has been fixed only for specific Ultimate Member plugin that result to be affected by this problem
   # If any other to be added, add it here and maybe please report at axew3.com
   if ( !defined( 'WP_ADMIN' ) && class_exists( 'UM' ) ){
    header("Refresh:0"); exit;
   }
}


public static function w3_phpbb_ban($phpbb_uid = '', $uname = '', $uemail = ''){

      if( defined("W3BANCKEXEC") ) { return false; }

    global $w3all_config,$phpbb_config,$w3all_phpbb_connection;

    if(empty($phpbb_config)){
     self::w3all_get_phpbb_config();
    }

    $uemail = sanitize_email($uemail);
    if( !is_email( $uemail ) ) {
     return false;
    }
    $uemail = esc_sql(strtolower($uemail));

    $user_REMOTE_ADDR = (! filter_var(trim($_SERVER["REMOTE_ADDR"]), FILTER_VALIDATE_IP)) ? '' : $_SERVER["REMOTE_ADDR"];

    $timenow = time();
    $uname = esc_sql($uname);

  if(empty($phpbb_uid)){
    $phpbb_uid = $w3all_phpbb_connection->get_var("SELECT user_id FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(user_email) = '$uemail'");
   }

   $phpbb_uid = empty($phpbb_uid) ? 0 : $phpbb_uid;

   $phpbb_ban_exclude = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."banlist WHERE ban_exclude = 1 AND LOWER(ban_email) = '$uemail' OR ban_exclude = 1 AND ban_id = '$phpbb_uid'", ARRAY_A);

   if( !empty($phpbb_ban_exclude) )
   {
    if(!defined("W3BANCKEXEC")){
     define("W3BANCKEXEC", true);
    }
     return false; // excluded id or email
   }


if( $phpbb_uid > 2 )
{ // check uid, ip and email for ban
    $phpbb_banl = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."banlist WHERE ban_userid = '$phpbb_uid' AND ban_exclude = 0 OR ban_userid = '0' AND ban_exclude = 0", ARRAY_A);
 if( !empty($phpbb_banl) )
 {
       $ban_userid = array_column($phpbb_banl, 'ban_userid');
       $ban_userid = array_filter( $ban_userid, 'strlen' ); // remove empty keys
       $ban_user_ip = array_column($phpbb_banl, 'ban_ip');
       $ban_user_ip = array_filter( $ban_user_ip, 'strlen' );
       $ban_user_email = array_column($phpbb_banl, 'ban_email');
       $ban_user_email = array_filter( $ban_user_email, 'strlen' );
       $ban_user_email = array_filter( $ban_user_email, 'strtolower' );

      // check for expired bans, remove from array, collect for removal on phpBB db
     $ban_ids_remove = '';
     foreach($phpbb_banl as $k => $banl){

        if($banl['ban_end'] > 0 && $timenow > $banl['ban_end']){ // expired ban: remove from array so the following check is only for not expired bans, if a match found
          $ban_ids_remove .= $banl['ban_id'].','; // collect expired to be removed into phpBB
          unset($phpbb_banl[$k]);
        }
      }

    $ban_ids_remove = !empty($ban_ids_remove) ? substr($ban_ids_remove, 0, -1) : '';

    if(!empty($ban_ids_remove)){
     $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."banlist WHERE ban_id IN($ban_ids_remove)");
    }

  if ( in_array($phpbb_uid, $ban_userid) OR in_array($user_REMOTE_ADDR, $ban_user_ip) OR in_array($uemail, $ban_user_email) )
  {
   if(!defined("W3BANCKEXEC")){
    define("W3BANCKEXEC", true);
   }
    return true; // the user is banned on phpBB forum
  }
 }
}

// the follow just check ever for '.domain.com' where banned emails contain '*' (wildcard check)

 $uemail_domain = substr(strrchr($uemail, "@"), 1);
 $uemail_domain = '.' . $uemail_domain;
 $ed = explode(".", $uemail_domain);
 $ed = array_slice($ed, -2, 2);
  if(!empty($ed) && count($ed) == 2){
    $ued = '';
   foreach($ed as $e){
    $ued .= $e . '.'; // .domain.com
   }
  }

 $ued = substr($ued, 0, -1);
 $phpbb_banl = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."banlist WHERE ban_userid = '0' AND ban_exclude = 0", ARRAY_A);

 if( empty($phpbb_banl) ){
   if(!defined("W3BANCKEXEC")){
    define("W3BANCKEXEC", true);
   }
  return false;
 }

 $ban_user_email = array_column($phpbb_banl, 'ban_email');
 $ban_user_email = array_filter($ban_user_email, 'strlen');

if(!empty($ban_user_email)){
 foreach($ban_user_email as $bue){
  if (stristr($bue, '*')){
    $eb = explode("*", $bue);
    if(in_array('.'.$ued,$eb) OR in_array('@'.$ued,$eb) OR in_array($ued,$eb))
    {
     if(!defined("W3BANCKEXEC")){
       define("W3BANCKEXEC", true);
      }
      return true; // the email is banned on phpBB forum
    }
   }
  }
}

if( ! empty($uemail) ){
  // remove empty keys, or if empty IP/email will match
       $ban_user_ip = array_column($phpbb_banl, 'ban_ip');
       $ban_user_ip = array_filter( $ban_user_ip, 'strlen' );
       $ban_user_email = array_column($phpbb_banl, 'ban_email');
       $ban_user_email = array_filter( $ban_user_email, 'strlen' );
       $ban_user_email = array_filter( $ban_user_email, 'strtolower' );

  if ( in_array($user_REMOTE_ADDR, $ban_user_ip) OR in_array($uemail, $ban_user_email) )
  {
    if(!defined("W3BANCKEXEC")){
     define("W3BANCKEXEC", true);
    }
    return true; // the user is banned on phpBB forum
  }
}
   if(!defined("W3BANCKEXEC")){
    define("W3BANCKEXEC", true);
   }
  return false;
}


private static function create_phpBB_user($wpu, $action = ''){

   if( empty($wpu) ){ return; }

   global $w3all_phpbb_connection,$phpbb_config,$w3all_config,$w3all_oninsert_wp_user,$wpdb, $w3all_phpbb_user_deactivated_yn, $w3all_phpbb_lang_switch_yn, $w3all_add_into_spec_group, $w3all_add_into_phpBB_after_confirm;

    if(empty($phpbb_config)){
     self::w3all_get_phpbb_config();
    }

   // skip, if 'create phpBB user after account confirmation' option enabled
   // if this option active, the user will be added into phpBB only after his first successsful login
   // via 'wp_check_password'
   // avoid if admin action

   if( $w3all_add_into_phpBB_after_confirm == 1 && $action != 'add_u_phpbb_after_login' && !current_user_can( 'create_users' ) )
    {
      return; // due to the above, if not admin, return here
    }

 // re-check if username or email already exists, and avoid to follow here if the case, may some plugin bypass others checks, or check only done by email elsewhere

   if(!empty(self::ck_phpbb_user( $wpu->user_login, $wpu->user_email ))){ return false; } // username or email exists into phpBB

   $default_dateformat = $phpbb_config["default_dateformat"];
   $default_lang = $phpbb_config["default_lang"];
   $phpbb_version = substr($phpbb_config["version"], 0, 3);
   $wpu->user_email = strtolower($wpu->user_email);

  $phpbb_group = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."ranks
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
     goto this1; // break to the first found ('it seem' to be the default phpBB behavior)
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

 } else {   $rankID = 0; $group_color = ''; }

  if(!isset($default_lang) OR empty($default_lang)){ $wp_lang_x_phpbb = 'en'; }
  else { $wp_lang_x_phpbb = $default_lang; }

     // maybe to be added as option
     // setup gravatar by default into phpBB profile for the user when register in WP
     $uavatar = $avatype = '';
     //$uavatar = get_option('show_avatars') == 1 ? $wpu->user_email : '';
     //$avatype = (empty($uavatar)) ? '' : 'avatar.driver.gravatar';

     $u = $phpbb_config["cookie_name"].'_u';

     if ( preg_match('/[^0-9]/',$_COOKIE[$u]) ){
      die( "Clean up cookie on your browser please!" );
     }

   $phpbb_u = intval($_COOKIE[$u]);

   // only need to fire when user do not exist on phpBB already, and/or user is an admin that add an user manually
  if ( !defined( 'WP_ADMIN' ) OR $phpbb_u < 2 OR !empty($phpbb_u) && current_user_can( 'create_users' ) === true ) {

      // check that the user need to be added as activated or not into phpBB

        if( current_user_can('create_users') === true OR $action = 'add_u_phpbb_after_login' ){ // an admin adding user
          $phpbb_user_type = 0;
        } else {
          $phpbb_user_type = $w3all_phpbb_user_deactivated_yn;
        }

      $wpus_db_tab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'signups' : $wpdb->prefix . 'signups';
       $wpdb->query("SHOW TABLES LIKE '$wpus_db_tab'");
      if($wpdb->num_rows > 0){
       $wpuS = $wpdb->get_row("SELECT * FROM ".$wpus_db_tab." WHERE LOWER(user_email) = '$wpu->user_email'");
       if(!empty( $wpuS )){
        $metavS = unserialize($wpuS->meta);
        }
      }

     if( isset($metavS['user_pass']) ){ // if multisite and hashed already on signup table, set this for mums
         $wpup = $metavS['user_pass'];
       } else { // not on multisite
          $wpup = $wpu->user_pass;
         }

      $wpul = $wpu->user_login;
      $wpue = $wpu->user_email;
      $time = $wpur = time();
      $w3all_user_email_hash = self::w3all_phpbb_email_hash($wpu->user_email);
      $wpunn = esc_sql(mb_strtolower($wpul,'UTF-8'));
      $wpul  = esc_sql($wpul);

     // if added as newely registered user, then the user need to be also added into Registered Group
     // and as user_new 1 into users tab (to be correctly removed from newbie group when promoted based on posts)
      $user_new = 0;
      if($w3all_add_into_spec_group == 7){
        $w3all_add_into_spec_group = 2;
        $w3all_add_into_spec_group_def = true;
        $user_new = 1;
      }

 // We assume here to not add the created phpBB user
 // into the phpBB newbie Group, if it is created by an admin
 // If more switches required, maybe should be added here
   if ( current_user_can('create_users') )
   {
     $user_new = 0;

     if(isset($wpu->roles[0])){
      if($wpu->roles[0] == 'subscriber'){
        $w3all_add_into_spec_group = 2; // registered phpBB
      }elseif($wpu->roles[0] == 'editor'){
        $w3all_add_into_spec_group = 4; // global moderator
       }elseif($wpu->roles[0] == 'administrator'){
         $w3all_add_into_spec_group = 5; // admin
        }
     }
   }

      // phpBB 3.2.0 >
      if($phpbb_version == '3.2'){
       $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_email_hash, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
         VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','','0','', '$wpur', '$wpul', '$wpunn', '$wpup', '0', '$wpue', '$w3all_user_email_hash', '', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '0', '$wp_lang_x_phpbb', 'Europe/Rome', '$default_dateformat', '1', '$rankID', '$group_color', '0', '0', '0', '0', '-3', '0', '0', 't', 'd', 0, 't', 'a', '0', '1', '0', '1', '1', '1', '1', '230271', '$uavatar', '$avatype', '0', '0', '', '', '', '', '', '', '', '$user_new', '0', '0')");
      }
      // phpBB 3.3.0 >
      if($phpbb_version == '3.3'){
       // all users db fields insert
       // $w3phpbb_conn->query("INSERT INTO ".$w3all_config["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, reset_token, reset_token_expiration, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
       //  VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','','0','','$wpur','$wpul','$wpunn','$wpup','0','$wpue','','0','0','0','index.php','','0','0','0','0','0','0','0','$wp_lang_x_phpbb','','d M Y H:i','1','0','$group_color','0','0','0','0','-3','0','0','t','d','0','t','a','0','1','0','1','1','1','1','230271','$uavatar','$avatype','50','50','','','','','','','0','','','$user_new','0','0')");
       // only users db required fields insert
          $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."users (user_id, user_type, group_id, user_regdate, username, username_clean, user_password, user_email, user_lang, user_colour, user_avatar, user_avatar_type, user_new)
           VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','$wpur','$wpul','$wpunn','$wpup','$wpue','$wp_lang_x_phpbb','$group_color','$uavatar','$avatype','$user_new')");
      }

      $phpBBlid = $w3all_phpbb_connection->insert_id;

     $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('$w3all_add_into_spec_group','$phpBBlid','0','0')");
     //$w3phpbb_conn->query("INSERT INTO ".$w3all_config["table_prefix"]."acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) VALUES ('$phpBBlid','0','0','6','0')");

      // if added as newely registered user, then the user need to be also added as newbie (group id 7)
      if(isset($w3all_add_into_spec_group_def)){ // add into 7, newely
       $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('7','$phpBBlid','0','0')");
      }

      // TODO: unify all updates
     $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."config SET config_value = config_value + 1 WHERE config_name = 'num_users'");

       $newest_member = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."users WHERE user_id = (SELECT Max(user_id) FROM ".$w3all_config["table_prefix"]."users) AND group_id != '6'");
       $uname = $newest_member[0]->username;
       $uid   = $newest_member[0]->user_id;

     $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."config SET config_value = '$wpul' WHERE config_name = 'newest_username'");
     $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."config SET config_value = '$uid' WHERE config_name = 'newest_user_id'");
  }

  // FIX AUTOLOGIN for woocommerce or any other plugin:
  // when user registered and need to be logged in automatically, then avoid to follow without phpBB session setup
  // or since the phpBB cookie is not released at this point, when verify_credentials will fire, the user will be logged out
  // add any other here, ex:
  // for any: if ( $w3all_phpbb_user_deactivated_yn != 1 && !current_user_can( 'create_users' ) )
  // that check both if the user need to be logged in, because it is not an admin creating users
  // or: if ( class_exists('WooCommerce') OR isset($_POST['createaccount']) OR class_exists('somethingelse') ) {
 if ( class_exists('WooCommerce') && $w3all_phpbb_user_deactivated_yn != 1 && !current_user_can('create_users') )
 { // or may restrict more based on if some $_POST var exist or not
     if( ! defined("PHPBBAUTHCOOKIEREL") ){
      self::phpBB_user_session_set_res($wpu);
    }
 }

 if(intval($phpBBlid) > 2){
  define("W3ALL_INSERTED_PHPBBUID", intval($phpBBlid));
  $w3all_oninsert_wp_user = 1; // or get email exist, because w3all_filter_pre_user_email() will fire after, wp update fire by the way
  return $phpBBlid;
 }
 return;
}


public static function phpBB_user_check( $sanitized_user_login, $user_email, $is_admin_action = 1 ){

     if( defined('W3ALL_PRE_UCK_EXEC') ): return false; endif;
     global $w3all_config,$phpbb_config,$w3all_phpbb_connection;

    if(empty($phpbb_config)){
     self::w3all_get_phpbb_config();
    }

     $u = $phpbb_config["cookie_name"].'_u';

      if ( isset($_COOKIE["$u"]) && preg_match('/[^0-9]/',$_COOKIE[$u]) ){
       die( "Clean up cookie on your browser." );
      }

      $_COOKIE[$u] = (isset($_COOKIE[$u])) ? $_COOKIE[$u] : '';

        $user = get_user_by( 'email', $user_email );

        if(empty($user)){ return; }

        $user_login = esc_sql(mb_strtolower($user->user_login,'UTF-8'));
        $user_email = esc_sql(strtolower($user_email));

       if( $is_admin_action == 1 OR defined( 'WP_ADMIN' ) ){
         $phpbb_any = $w3all_phpbb_connection->get_row("SELECT username, user_email FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(user_email) = '".$user_email."' OR LOWER(username) = '".$user_login."'");

         if ( null !== $phpbb_any ) {
          define('W3ALL_PRE_UCK_EXEC', true);
           return true;
         }
       }

    if ( $_COOKIE[$u] < 2 ){ // check only for phpBB user that come as NOT logged in - or get 'undefined wp_delete' error
      $phpbb_any = $w3all_phpbb_connection->get_row("SELECT username, user_email FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(user_email) = '$user_email' OR LOWER(username) = '$user_login'");
      define('W3ALL_PRE_UCK_EXEC', true);
      if ( null !== $phpbb_any ) {
        return true;
      }
    }

     return false;
}


public static function ck_phpbb_user( $user_login = '', $user_email = '' ){

   global $w3all_config,$w3all_phpbb_connection;

  $user_login = trim(str_replace(chr(0), '', $user_login));
  if(!empty($user_login)){
   $user_login = esc_sql(mb_strtolower($user_login,'UTF-8'));
  } else { $user_login = ''; }

  $user_email = trim(str_replace(chr(0), '', $user_email));
  if(is_email($user_email)){
   $user_email = esc_sql(strtolower($user_email));
  } else { $user_email = ''; }

  if ( !is_email( $user_email ) && empty($user_login) OR empty($user_login) && empty($user_email) ){
    return;
  }

 if(empty($user_login)){
    $res = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(user_email) LIKE '".$user_email."'");
  } else {
    $res = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(username) LIKE '".$user_login."' OR LOWER(user_email) LIKE '".$user_email."'");
   }

     return $res;
}


public static function check_phpbb_passw_match_on_wp_auth ( $user_email, $is_phpbb_admin = 0, $wpu = '' ) {

     global $wpdb,$w3all_config,$w3all_phpbb_connection;

     $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';

   if( empty($user_email) ){ return; }

    if(empty($wpu)){
     $wpu = get_user_by('email', $user_email);
    }
    if( empty($wpu) OR $wpu->ID == 1 ){
     return false;
    }

    if ( $wpu->ID == 1 ) {
     if(!defined("WPW3ALL_NOT_ULINKED")){
      define('WPW3ALL_NOT_ULINKED', true);
     }
      return;
    }

      $user_email = esc_sql(strtolower($wpu->user_email));
      $user_login = esc_sql(mb_strtolower($wpu->user_login,'UTF-8'));

      $phpbb_pae = $w3all_phpbb_connection->get_row("SELECT user_password, user_email FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(user_email) = '".$user_email."'");

       if( defined("WPUSERCREATED") OR !empty($phpbb_pae) && $phpbb_pae->user_password != $wpu->user_pass ){
          $user_password = $phpbb_pae->user_password;
          $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '".$user_password."' WHERE LOWER(user_email) = '".$user_email."'");

        return $phpbb_pae->user_password;
       }

  return false;

}


public static function wp_w3all_phpbb_logout() {
   global $w3all_phpbb_connection,$phpbb_config,$w3all_config,$w3cookie_domain,$w3all_useragent;

    if(empty($phpbb_config)){
     self::w3all_get_phpbb_config();
    }

        $k   = $phpbb_config["cookie_name"].'_k';
        $sid = $phpbb_config["cookie_name"].'_sid';
        $u   = $phpbb_config["cookie_name"].'_u';

  if( isset($_COOKIE[$u]) && intval($_COOKIE[$u]) < 2 ){ return; }
  if( empty($_COOKIE[$sid]) ){ return; }

     if(isset($_COOKIE[$k])){
      if ( preg_match('/[^0-9A-Za-z]/',$_COOKIE[$k]) OR preg_match('/[^0-9A-Za-z]/',$_COOKIE[$sid]) OR preg_match('/[^0-9]/',$_COOKIE[$u]) ){
               die( "Please clean up cookies on your browser." );
              }

   $k_md5 = empty( md5($_COOKIE[$k]) ) ? 0 : md5($_COOKIE[$k]);
   $u_id = $_COOKIE[$u];
   $s_id = $_COOKIE[$sid];

    // logout phpBB user
    $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."sessions WHERE session_id = '$s_id' AND session_user_id = '$u_id' OR session_user_id = '$u_id' AND session_browser = '$w3all_useragent'");
    $w3all_phpbb_connection->query("DELETE FROM ".$w3all_config["table_prefix"]."sessions_keys WHERE key_id = '$k_md5' AND user_id = '$u_id'");

  // remove phpBB cookies
      setcookie ("$k", "", time() - 31622400, "/");
      setcookie ("$sid", "", time() - 31622400, "/");
      setcookie ("$u", "", time() - 31622400, "/");
      setcookie ("$k", "", time() - 31622400, "/", "$w3cookie_domain");
      setcookie ("$sid", "", time() - 31622400, "/", "$w3cookie_domain");
      setcookie ("$u", "", time() - 31622400, "/", "$w3cookie_domain");
      setcookie ("$k", "", time() - 31622400, "/", true);
      setcookie ("$sid", "", time() - 31622400, "/", true);
      setcookie ("$u", "", time() - 31622400, "/", true);
      setcookie ("$k", "", time() - 31622400, "/", "$w3cookie_domain", true);
      setcookie ("$sid", "", time() - 31622400, "/", "$w3cookie_domain", true);
      setcookie ("$u", "", time() - 31622400, "/", "$w3cookie_domain", true);
   }

}


public static function wp_w3all_wp_after_pass_reset( $user ) {

  if(defined('WPW3ALL_PASSW_AUPADTED') OR !$user) { return; }

   global $w3all_phpbb_connection,$w3all_config,$phpbb_user_data,$w3all_phpbb_user_deactivated_yn;

    $user_info = get_userdata($user->ID);
    $wp_user_role = implode(', ', $user_info->roles);

    self::wp_w3all_get_phpbb_user_info($user->user_email);

  if(!empty($phpbb_user_data)){

    $user->user_email = strtolower($user->user_email);

    if ( isset($phpbb_user_data[0]->user_type) && $phpbb_user_data[0]->user_type == 1 ) {
      $res = $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0' WHERE LOWER(user_email) = '".$user->user_email."'");
     }
    // keep separated
    if( $user->user_pass != $phpbb_user_data[0]->user_password ) {
       $res = $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_password = '".$user->user_pass."' WHERE LOWER(user_email) = '".$user->user_email."'");
     }
   }

}


public static function phpbb_pass_update($user, $new_pass) {
 // $new_pass is plain-text

   if(defined('WPW3ALL_PASSW_AUPADTED')) { return; }

       global $w3all_config,$wpdb,$w3all_phpbb_connection;

     $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
     $ud = $wpdb->get_row("SELECT * FROM  $wpu_db_utab WHERE ID = '$user->ID'");

     if(empty($ud)){
        return;
      }


   $new_pass = wp_hash_password($new_pass);
   $user->user_email = strtolower($user->user_email);

    if ( $user->ID > 1 ){

      $umeta = get_userdata($user->ID);
     if( empty($umeta->roles) ) // the user have no role in wp, do not (re)activate in phpBB
     {
       $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_password = '$new_pass' WHERE LOWER(user_email) = '".$user->user_email."'");
     } else {
       $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_password = '$new_pass' WHERE LOWER(user_email) = '".$user->user_email."'");
      }
       $wpdb->query("UPDATE ".$wpu_db_utab." SET user_pass = '$new_pass' WHERE LOWER(user_email) = '".$user->user_email."'");

       // force redirect or password in phpBB will be re-setted after, to the old one
       // OR if some external plugin require immediate access after pass reset, set the phpBB session then here, instead then logout
       // self::phpBB_user_session_set_res($user);
       // wp_redirect( wp_login_url() ); exit;

      define('WPW3ALL_PASSW_AUPADTED', true); // OR wp_w3all_wp_after_pass_reset and wp_w3all_wp_after_pass_reset_mu will reset
    }

}

 public static function phpbb_update_profile($user_id, $old_user_data) {

// since WP 5.8 $userdata added, but with Buddypress installed it return same values for $old_user_data and $userdata
// same goes for $wpu = get_user_by('ID', $user_id), at this execution time, if Buddypress is active it return old data

// the profile_update hook fire also just after an user is created.
// so return here if the $_GET['action'] == 'register' detected
// or if the user still do not exist in phpBB

 if ( $user_id < 2 OR isset($_GET['action']) && $_GET['action'] == 'register'
      OR defined('WPW3ALL_UPROFILE_UPDATED') ){ return; }

   global $w3all_link_roles_groups,$w3all_phpbb_connection,$phpbb_config,$wpdb,$w3all_config,$w3all_phpbb_lang_switch_yn;

    if(empty($phpbb_config)){
     self::w3all_get_phpbb_config();
    }

     $phpbb_version = substr($phpbb_config["version"], 0, 3);
     $wpu = get_user_by('ID', $user_id); // updated udata -> but should be used $userdata since 5.8

     if(empty($wpu)){ return; }

     $phpbb_user_type = ( empty($wpu->roles) ) ? '1' : '0';
     $umeta = get_user_meta($user_id);

    if ( is_multisite() ) {
     // $wp_user_p_blog = get_user_meta($user_id, 'primary_blog', true);
     // a normal user result with no capability in MU
     // temp fix: set user type by the way as active in phpBB
     $phpbb_user_type = 0;
    }

     foreach($umeta as $u => $um){
       if( strpos($u,'_new_email') !== false ){
        $ned = unserialize($um[0]);
        $new_email = $ned['newemail'];
        $ne_option_meta_key = $u;
       }
     }

   // if not updating email, but only other fields, then skip to the actual user email, $new_email is not set then
   // check that email has been validated/confirmed, before to update also into phpBB

     $username = esc_sql(mb_strtolower($old_user_data->user_login,'UTF-8'));
     $wpu->user_login = esc_sql(mb_strtolower($wpu->user_login,'UTF-8'));
     $old_user_data->user_email = $old_user_data_user_email = strtolower($old_user_data->user_email);
     $wpu->user_email = strtolower($wpu->user_email);
     $user_email = isset($new_email) ? $old_user_data->user_email : $wpu->user_email;
     // if all the above failed on detect that there is the email update and:
     if( current_user_can('edit_user') && get_current_user_id() != $user_id ){
      $wpu->user_email = isset($_REQUEST['email']) && $old_user_data->user_email != $_REQUEST['email'] && is_email(sanitize_email($_REQUEST['email'])) ? $_REQUEST['email'] : $old_user_data->user_email;
      $wpu->user_email = esc_sql(strtolower($wpu->user_email));
     }

   $w3all_user_email_hash = sprintf('%u', crc32(strtolower($wpu->user_email))) . strlen($wpu->user_email);

   $phpbbug = $w3all_phpbb_connection->get_results("SELECT user_id, group_id FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(user_email) = '$old_user_data_user_email'");

     if(empty($phpbbug[0])){ return; } // if the user (still?) do not exist

   $uid  = $phpbbug[0]->user_id;
   $ugid = $phpbbug[0]->group_id;

# If option enabled: Switch WP user to specified Group in phpBB, when Role updated in WordPress
# switch Roles/Groups: see also $user_id for the update of the user Role, of the current logged in user, when Group changed in phpBB

  if( $w3all_link_roles_groups == 1 OR $w3all_link_roles_groups == 2 )
  {
   // execute code for this specific case (when on wp admin profile update)
   $w3all_roles_to_groups_on_wpupdate_profile = true;

      if (file_exists(ABSPATH.'wp-content/plugins/wp-w3all-custom/wpRoles_phpbbGroups.php')){
       include(ABSPATH.'wp-content/plugins/wp-w3all-custom/wpRoles_phpbbGroups.php');
      } else {
       include(WPW3ALL_PLUGIN_DIR.'common/wpRoles_phpbbGroups.php');
      }
   }

    $user_updated_url = (! filter_var(trim($_SERVER["REMOTE_ADDR"]), FILTER_VALIDATE_IP)) ? '' : $_SERVER["REMOTE_ADDR"];
    $u_url = $wpu->user_url;

  if (defined('BP_VERSION')) // Buddypress compatibility
  {
   // Buddypress switch url field
   if( isset($_REQUEST['url']) && $wpu->user_url != $_REQUEST['url'] ){
    if (filter_var($_REQUEST['url'], FILTER_VALIDATE_URL)) {
     $u_url = $_REQUEST['url']; // buddypress
    }
   }
  // Buddypress switch pass field: $_REQUEST['pass1'] and $_REQUEST['pass2'] results to be the same and are in plain text
   if( isset($_REQUEST['pass1']) && $wpu->user_pass != $_REQUEST['pass1'] ){
     $wpu->user_pass = wp_hash_password(trim($_REQUEST['pass2']));
   }
  }

     $wp_umeta = get_user_meta($wpu->ID, '', false);

      if( empty($wp_umeta['locale'][0]) ){ // wp lang for this user ISO 639-1 Code. en_EN // en = Lang code _ EN = Country code
            if( strlen(get_locale()) == 2 ){ $wp_lang_x_phpbb = strtolower(get_locale());
          } else {
            $wp_lang_x_phpbb = substr(get_locale(), 0, strpos(get_locale(), '_')); // should extract Lang code ISO Code phpBB suitable for this lang
           }
      } else {
          if( strlen($wp_umeta['locale'][0]) == 2 ){ $wp_lang_x_phpbb = strtolower($wp_umeta['locale'][0]);
          } else {
            $wp_lang_x_phpbb = substr($wp_umeta['locale'][0], 0, strpos($wp_umeta['locale'][0], '_')); // should extract Lang code ISO Code phpBB suitable for this lang
           }
        }
          // switch for different languages notations
          if( $wp_lang_x_phpbb == 'ps' ){ $wp_lang_x_phpbb = 'fa'; // persian
             }
          // assure the default
          if(!isset($wp_lang_x_phpbb) OR empty($wp_lang_x_phpbb)){ $wp_lang_x_phpbb = 'en'; }


  // note: here the user_type in phpBB will be set as 2 for any user on reactivation, but may should be based on group that user belong to, may 3 if admin and so on ... so the above '$uid SELECT' query should be changed to achieve this
   $user_email = $wpu->user_email;
   $user_pass  = $wpu->user_pass;

  // phpBB 3.2
     if( $phpbb_version == '3.2' ){
      if( $w3all_phpbb_lang_switch_yn == 1 ){ // do not update lang if not activated option
         $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '$phpbb_user_type', user_password = '$user_pass', user_email = '$user_email', user_email_hash = '$w3all_user_email_hash', user_lang = '$wp_lang_x_phpbb' WHERE user_id = '$uid'");
       } else {
               $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '$phpbb_user_type', user_password = '$user_pass', user_email = '$user_email', user_email_hash = '$w3all_user_email_hash' WHERE user_id = '$uid'");
              }
      }

  // phpBB 3.3
    if( $phpbb_version == '3.3' ){
      if( $w3all_phpbb_lang_switch_yn == 1 ){
         $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '$phpbb_user_type', user_password = '$user_pass', user_email = '$user_email', user_lang = '$wp_lang_x_phpbb' WHERE user_id = '$uid'");
       } else {
               $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '$phpbb_user_type', user_password = '$user_pass', user_email = '$user_email' WHERE user_id = '$uid'");
              }
     }

  $phpbb_pf_cols = $w3all_phpbb_connection->get_results("SHOW COLUMNS FROM ". $w3all_config["table_prefix"] ."profile_fields_data");
  $pfields = $pvals = '';

  if(!empty($phpbb_pf_cols))
  {
   foreach($phpbb_pf_cols as $ppfc)
   {
    foreach($ppfc as $ppf_cols => $val)
     {
      // COLUMNS fields
      if($ppf_cols == 'Field')
      {
       $pfields .= $val . ','; # build fields
      // VALUES
       if ($val == 'user_id')
       { $pvals .= "'".$uid."',"; }
       # here can be added any field update, in several convenient ways (strpos)
        elseif ( $val == 'pf_phpbb_website' )
       #elseif ( strpos($val, 'afield') !== false )
        {
          $fieldExist = true;
          $pvals .= "'".$u_url."',";
        }
        else { $pvals   .= "'',"; }
      }
     }
    }
      # update only if the field effectively exist so to avoid any possible error
    if(isset($fieldExist))
    {
      $qtf = '('.substr($pfields, 0, -1).')';
      $qtv = '('.substr($pvals, 0, -1).')';

      $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."profile_fields_data ".$qtf."
      VALUES ".$qtv." ON DUPLICATE KEY UPDATE pf_phpbb_website = '$u_url'");
     }

   } # END if(!empty($phpbb_pf_cols))

  define('WPW3ALL_UPROFILE_UPDATED',true);
}

# TODO: switch since WP 5.8 $userdata added
 public static function wp_phpbb_update_profile($user_id, $old_user_data, $userdata) {

// since WP 5.8 $userdata added, but it return same values for $old_user_data and $userdata with Buddypress installed

// the profile_update hook fire also just after an user is created.
// so return here if the $_GET['action'] == 'register' detected
// or if the user still do not exist in phpBB

 if ( $user_id < 2 OR isset($_GET['action']) && $_GET['action'] == 'register'
      OR defined('WPW3ALL_UPROF_UPDATED') ){ return; }

   global $w3all_link_roles_groups,$w3all_phpbb_connection,$phpbb_config,$wpdb,$w3all_config,$w3all_phpbb_lang_switch_yn;

    if(empty($phpbb_config)){
     self::w3all_get_phpbb_config();
    }

     $phpbb_version = substr($phpbb_config["version"], 0, 3);
     $wpu = get_user_by('ID', $user_id); // updated udata -> but should be used $userdata since 5.8

     if(empty($wpu)){ return; }

     $phpbb_user_type = ( empty($wpu->roles) ) ? '1' : '0';
     $umeta = get_user_meta($user_id);

    if ( is_multisite() ) {
     // $wp_user_p_blog = get_user_meta($user_id, 'primary_blog', true);
     // a normal user result with no capability in MU
     // temp fix: set user type by the way as active in phpBB
     $phpbb_user_type = 0;
    }

   # ............

  define('WPW3ALL_UPROF_UPDATED',true);
}


public static function w3_check_phpbb_profile_wpnu($username){ // email/user_login

 if( defined('W3ALL_WPNU_CKU') OR empty($username) ): return; endif;
  global $w3all_phpbb_connection,$w3all_config,$wpdb,$w3all_oninsert_wp_user,$w3all_add_into_wp_u_capability,$w3cookie_domain,$w3all_add_into_phpBB_after_confirm,$w3all_push_new_pass_into_phpbb;

  $username = trim($username);

  if ( strlen($username) > 50 ){
      return;
   }

  $user = is_email($username) ? get_user_by('email', $username) : get_user_by('login', $username);

  $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
  $wpu_db_umtab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';

  $username = esc_sql($username);
  //$db_eu = is_email($username) ? 'users.user_email) = \''.mb_strtolower($username,'UTF-8').'\'' : 'users.username) = \''.mb_strtolower($username,'UTF-8').'\'';
  $db_eu = is_email($username) ? 'users.user_email) = \''.strtolower($username).'\'' : 'users.username) = \''.mb_strtolower($username,'UTF-8').'\'';

  $phpbb_user = $w3all_phpbb_connection->get_results("SELECT *
    FROM ". $w3all_config["table_prefix"] ."groups
    JOIN ". $w3all_config["table_prefix"] ."users ON LOWER(". $w3all_config["table_prefix"] . $db_eu ."
    AND ". $w3all_config["table_prefix"] ."users.group_id = ". $w3all_config["table_prefix"] ."groups.group_id");

///////////
// If a frontend plugin bypass default password reset process, and do not let update the new wp password at same time also into phpBB
// force the password update into phpBB onlogin in wordpress.

 if( $w3all_push_new_pass_into_phpbb == 1 ){

  if( isset($phpbb_user[0]->user_id) && $user->user_pass != $phpbb_user[0]->user_password && $phpbb_user[0]->user_id > 2 )
   {
     $new_pass_push = $phpbb_user[0]->user_password = $user->user_pass;
     $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_password = '$new_pass_push' WHERE LOWER(user_email) = '".$user->user_email."'");
   }

  }

  if( !isset($phpbb_user[0]->user_id) OR $phpbb_user[0]->user_id < 3 ){ return; }

// mums allow only '[0-9A-Za-z]'
// default wp allow allow only [-0-9A-Za-z _.@]

  $contains_cyrillic = (bool) preg_match('/[\p{Cyrillic}]/u', $phpbb_user[0]->username);

  // if do not contain non latin chars, let wp create any wp user_login with this passed username
   if ( is_multisite() && !defined('WPW3ALL_USE_DEFAULT_WP_UCHARS') && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$phpbb_user[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user[0]->username) OR strlen($phpbb_user[0]->username) > 50 )
   {
    // if ( is_multisite() && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user[0]->username) OR strlen($phpbb_user[0]->username) > 50 ){

    if (!defined('WPW3ALL_NOT_ULINKED')){
     define('WPW3ALL_NOT_ULINKED', true);
    }
     setcookie ("w3all_set_cmsg", "phpbb_uname_chars_error", 0, "/", $w3cookie_domain, false);
     echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your username contains illegal characters that are not allowed in this system. Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
      return;
   }

  // activated in phpBB?
 if( $user && !empty($phpbb_user) && $phpbb_user[0]->user_type == 0 && empty($user->wp_capabilities) ){ // re-activate this 'No role' WP user
     $user_role_up = serialize(array($w3all_add_into_wp_u_capability => 1));
     $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '$user_role_up' WHERE user_id = '$user->ID' AND meta_key = 'wp_capabilities'");
  }

  // Banned or deactivated?
 if(!defined("W3BANCKEXEC") && !empty($phpbb_user)){
   if(self::w3_phpbb_ban($phpbb_user[0]->user_id, $phpbb_user[0]->username, $phpbb_user[0]->user_email) === true){
    setcookie ("w3all_set_cmsg", "phpbb_ban", 0, "/", $w3cookie_domain, false);
     self::w3all_wp_logout('wp_login_url'); // should be just a redirect, not a logout, since the user here isn't still logged!
   }
  }

 if ( !empty($phpbb_user) && $phpbb_user[0]->user_type == 1 ){
    setcookie ("w3all_set_cmsg", "phpbb_deactivated", 0, "/", $w3cookie_domain, false);
    self::w3all_wp_logout('wp_login_url');  // well, same as above ... should be just a redirect, not a logout, since the user here isn't still logged
    return;
  }
// END banned or deactivated


 if ( !is_multisite() && !empty($phpbb_user) ) {
  if( $user && $phpbb_user[0]->user_type == 1 && !empty($user->wp_capabilities) ){
   $user_email = strtolower($user_email);
   $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0' WHERE LOWER(user_email) = '$user_email'");
  }
 }

 if ( ! username_exists( $phpbb_user[0]->username ) && ! email_exists( $phpbb_user[0]->user_email ) && $phpbb_user[0]->user_type != 1 && ! $user && !empty($phpbb_user) ) {

     if ( $phpbb_user[0]->group_name == 'ADMINISTRATORS' ){
          $role = 'administrator';
        } elseif ( $phpbb_user[0]->group_name == 'GLOBAL_MODERATORS' ){
          $role = 'editor';
        } else { // $role = 'subscriber'; // for all others phpBB Groups default to WP subscriber
                 $role = $w3all_add_into_wp_u_capability;
                }

   $userdata = array(
     'user_login' => $phpbb_user[0]->username,
     'user_pass' => $phpbb_user[0]->user_password,
     //'user_email' => $phpbb_user[0]->user_email,
     'user_registered' => date_i18n( 'Y-m-d H:i:s', $phpbb_user[0]->user_regdate ),
     'role' => $role
    );

    $w3all_oninsert_wp_user = 1;
    $user_id = wp_insert_user( $userdata );

   if ( is_wp_error( $user_id ) ) {
    echo '<div style="padding:10px 30px;background-color:#fff;color:#000;font-size:1.3em"><p>' . $user_id->get_error_message() . '</p></div>';
    echo __('<div><p style="padding:10px 30px;background-color:#fff;color:#000;font-size:1.0em"><strong>ERROR: try to reload page, but if the error persist may it mean that the forum\'s logged in username contains illegal characters OR your forum\'s account is not active. Please contact an administrator.</strong></p></div>', 'wp-w3all-phpbb-integration');
    exit;
   }

   if ( ! is_wp_error( $user_id ) ) {
     $phpbb_username = preg_replace( '/\s+/', ' ', $phpbb_user[0]->username );
     $phpbb_username = esc_sql($phpbb_username);
     $uemail = $phpbb_user[0]->user_email;
     $upass = $phpbb_user[0]->user_email;
     $user_username_clean = sanitize_user( $phpbb_user[0]->username, $strict = false );
     $user_username_clean = esc_sql(mb_strtolower($user_username_clean,'UTF-8'));

   // workaround for cyrillic chars: or an username like 'Denis I.' in cyrillic alphabet, will be inserted as a single dot for the user_login value
     if ( $contains_cyrillic ) {
      $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbb_username."', user_pass = '".$upass."', user_nicename = '".$user_username_clean."', user_email = '".$uemail."', display_name = '".$phpbb_username."' WHERE ID = ".$user_id."");
      $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
     } else { // leave as is (may cleaned and different) the just created user_login
            $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '".$upass."', user_email = '".$uemail."', display_name = '".$phpbb_username."' WHERE ID = '$user_id'");
            $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
          }
   }

   if( is_wp_error( $user_id ) ){
      // TODO: return error via cookie instead
      echo '<h3>Error: '.$user_id->get_error_message().'</h3>' . '<h4><a href="'.get_edit_user_link().'">Return back</a><h4>';
      exit;
   } else {

    if($user){

      define("WPUSERCREATED",true);

      // let login the user, if pass match
      if(isset($_POST['log']) && isset($_POST['pwd'])){
       wp_check_password(trim($_POST['pwd']), $phpbb_user[0]->user_password, $user_id);
      }

       if ( is_multisite() ){
        if ( !function_exists( 'get_current_blog_id' ) ) {
         require_once ABSPATH . WPINC . '/load.php';
        }

        if ( !function_exists( 'add_user_to_blog' ) ) {
         require_once ABSPATH . WPINC . '/ms-functions.php';
        }

        $blogID = get_current_blog_id();

        // this way add only to the current visited blog
        // $role
        $result = add_user_to_blog($blogID, $user_id, $role);
       }
     }
    }
 }

 define('W3ALL_WPNU_CKU', true);

}


public static function wp_w3all_update_phpBB_udata($user_email, $data, $update="pass"){

 global $w3all_config,$w3all_phpbb_connection;

 $user_email = strtolower($user_email);
  if($update == 'pass'){
     $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_password = '$data' WHERE LOWER(user_email) = '".$user_email."'");
  }
}


public static function wp_w3all_get_phpbb_user_info($username){ // email/user_object/username

 global $w3all_config,$w3all_phpbb_connection,$phpbb_user_data;

   if (isset($username->user_login)) {
    $username = $username->user_login;
   }

    $username = trim(str_replace(chr(0), '', $username));

   if ( empty($username) OR strlen($username) > 50 ){
     echo '<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em">Your <strong>registered username on our forum contain characters that are not allowed on this CMS system, or your username is too long (max 49 chars allowed)</strong>: you can\'t be added or login in this site (and you\'ll see this message) until logged in on forums as <b>'.$username.'</b>. Please return back and contact the administrator reporting about this issue. Thank you <input type="button" value="Go Back" onclick="history.back(-1)" /></p>';
     return false;
   }

 if( is_email($username) ){
  $username = strtolower($username);
 } else { $username = mb_strtolower($username,'UTF-8'); }

  $username = esc_sql($username);
  $db_eu = is_email($username) ? 'users.user_email) = \''.$username.'\'' : 'users.username) = \''.$username.'\'';

    $phpbb_user_data = $w3all_phpbb_connection->get_results("SELECT *
    FROM ". $w3all_config["table_prefix"] ."groups
    JOIN ". $w3all_config["table_prefix"] ."users ON LOWER(". $w3all_config["table_prefix"] . $db_eu ."
    AND ". $w3all_config["table_prefix"] ."users.group_id = ". $w3all_config["table_prefix"] ."groups.group_id");

 return $phpbb_user_data;

}


public static function wp_w3all_get_phpbb_user_info_by_ID($id){

 global $w3all_config,$w3all_phpbb_connection,$phpbb_user_data;

  $id = intval($id);

  if($id < 3){ return false; }

    $phpbb_user_data = $w3all_phpbb_connection->get_results("SELECT *
    FROM ". $w3all_config["table_prefix"] ."groups
    JOIN ". $w3all_config["table_prefix"] ."users ON ". $w3all_config["table_prefix"] ."users.user_id = '".$id."'
    AND ". $w3all_config["table_prefix"] ."users.group_id = ". $w3all_config["table_prefix"] ."groups.group_id");

 return $phpbb_user_data;

}

public static function wp_w3all_get_phpbb_user_info_by_email($email){

 global $w3all_config,$w3all_phpbb_connection,$phpbb_user_data;

   $email = sanitize_email($email);
    if( !is_email($email) ) {
     return false;
    }

   $email = strtolower($email);

    $phpbb_user_data = $w3all_phpbb_connection->get_results("SELECT *
    FROM ". $w3all_config["table_prefix"] ."groups
    JOIN ". $w3all_config["table_prefix"] ."users ON LOWER(". $w3all_config["table_prefix"] ."users.user_email) = '".$email."'
    AND ". $w3all_config["table_prefix"] ."users.group_id = ". $w3all_config["table_prefix"] ."groups.group_id");

 return $phpbb_user_data;

}

// Only deactivate user in phpBB if deleted on WP
public static function wp_w3all_phpbb_delete_user ($user_id){

 global $w3all_config,$wpdb,$w3all_phpbb_connection,$phpbb_user_data;

 $user = get_user_by( 'ID', $user_id );

 if($user->ID < 2){
  return;
 }

 $email = strtolower($user->user_email);

 $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'signups' : $wpdb->prefix . 'signups';
 $wpdb->query("SHOW TABLES LIKE '$wpu_db_utab'");
  if($wpdb->num_rows > 0){
   $wpdb->query("DELETE FROM $wpu_db_utab WHERE LOWER(user_email) = '$email'");
  }

   $phpbb_user = self::wp_w3all_get_phpbb_user_info($email);
    if(empty($phpbb_user[0])){ return; }

   $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '1' WHERE LOWER(user_email) = '$email'");
   $uuid = $phpbb_user[0]->user_id;
   $w3all_phpbb_connection->query("DELETE FROM ". $w3all_config["table_prefix"] ."sessions WHERE ".$w3all_config["table_prefix"]."sessions.session_user_id = '$uuid'");
   $w3all_phpbb_connection->query("DELETE FROM ". $w3all_config["table_prefix"] ."sessions_keys WHERE ".$w3all_config["table_prefix"]."sessions_keys.user_id = '$uuid'");
}


public static function wp_w3all_phpbb_delete_user_signup($user_id, $blog_id = ''){

 global $w3all_config,$wpdb,$w3all_phpbb_connection,$phpbb_user_data;

// Only deactivate user in phpBB if deleted on WP

 $user = get_user_by('ID', $user_id);
  if(empty($user->user_email)){ return; }

 $phpbb_udata = self::wp_w3all_get_phpbb_user_info_by_email($user->user_email);

 if(empty($phpbb_udata[0])){ return; }

  $email = strtolower($user->user_email);
  $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '1' WHERE LOWER(user_email) = '".$email."'");

   $uuid = $phpbb_udata[0]->user_id;
   $w3all_phpbb_connection->query("DELETE FROM ". $w3all_config["table_prefix"] ."sessions WHERE ".$w3all_config["table_prefix"]."sessions.session_user_id = '$uuid'");
   $w3all_phpbb_connection->query("DELETE FROM ". $w3all_config["table_prefix"] ."sessions_keys WHERE ".$w3all_config["table_prefix"]."sessions_keys.user_id = '$uuid'");

  // clean also signup of this user if WPMU for compatibility with integration
  // the check is done against an user that exist into users table, not signup

  // cleanup signup if exist the table
 $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'signups' : $wpdb->prefix . 'signups';
 $wpdb->query("SHOW TABLES LIKE '$wpu_db_utab'");
 if($wpdb->num_rows > 0){
  $wpdb->query("DELETE FROM $wpu_db_utab WHERE LOWER(user_email) = '$email'");
 }

}

//#######################
// START SHORTCODEs for phpBB contents into WP
//#######################

public static function wp_w3all_phpbb_unotifications_short($atts){
  global $w3all_phpbb_unotifications,$w3all_custom_output_files;

  if (! empty($w3all_phpbb_unotifications))
  {
   if(is_array($atts))
   {
    $atts = array_map ('trim', $atts);
   }

   $ltm = shortcode_atts( array(
   'ul_phpbb_unotifications_class' => '',
   'li_phpbb_unotifications_class' => '',
   ), $atts );

    $ul_phpbb_unotifications_class = $ltm['ul_phpbb_unotifications_class'];
    $li_phpbb_unotifications_class = $ltm['li_phpbb_unotifications_class'];

    if( $w3all_custom_output_files > 0 )
    {
     $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/wp_w3all_phpbb_unotifications_short.php';
     if (!file_exists($file)){ // old way compatibility
     $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/wp_w3all_phpbb_unotifications_short.php';
     }
     ob_start();
      include($file);
     return ob_get_clean();
    } else {
     $file = WPW3ALL_PLUGIN_DIR . 'views/wp_w3all_phpbb_unotifications_short.php';
     ob_start();
      include($file);
     return ob_get_clean();
    }

  }

}

public static function wp_w3all_add_iframeResizer_lib(){
echo "<script type=\"text/javascript\" src=\"".plugins_url()."/wp-w3all-phpbb-integration/addons/resizer/iframeResizer.min.js\"></script>
";
}

public static function w3all_login_short($atts) {

  global $w3all_url_to_cms,$w3all_custom_output_files,$w3all_phpbb_usession,$w3all_last_t_avatar_dim;

   if( $w3all_custom_output_files == 1 ) {
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/wp_w3all_login_form_short.php';
   if (!file_exists($file)){
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/wp_w3all_login_form_short.php';// old way
   }
     ob_start();
      include($file);
     return ob_get_clean();
    } else {
     $file = WPW3ALL_PLUGIN_DIR . 'views/wp_w3all_login_form_short.php';
     ob_start();
      include( $file );
     return ob_get_clean();
    }
}

// wp_w3all_custom_iframe_short vers 1.0
public static function wp_w3all_custom_iframe_short( $atts ){
  global $w3all_config,$w3all_custom_output_files,$w3cookie_domain,$w3all_url_to_cms;

 if(is_array($atts)){
  $atts = array_map ('trim', $atts);
 } else {
  return;
 }

 if(!empty($w3cookie_domain)){
  $p = strpos($w3cookie_domain, '.');
   if($p !== false && $p === 0){
    $document_domain = substr($w3cookie_domain, 1);
   } else {
      $document_domain = $w3cookie_domain;
     }
  } else {
   $document_domain = 'localhost';
  }

    $ltm = shortcode_atts( array(
        'resizer' => 'no',
        'check_origin' => 'true',
        'url_to_display' => '',
        'css_iframe_wrapper_div' => '',
        'css_iframe_elem_iframe' => ''
    ), $atts );

 $w3check_origin = $ltm['check_origin'] == 'false' ? 'false' : "['".$ltm['check_origin']."','https://localhost','http://localhost']";
 $iframe_style = empty($ltm['css_iframe_elem_iframe']) ? 'width:1px;min-width:100%;*width:100%;border:0;' : $ltm['css_iframe_elem_iframe'];
 $schema = (is_ssl() == true) ? 'https://' : 'http://';

  if( empty($ltm['url_to_display']) OR !filter_var($ltm['url_to_display'], FILTER_VALIDATE_URL) ){
     ob_start();
      echo'<i>url_to_display</i> on Shortcode <i>w3allcustomiframe</i> not found or empty or contain wrong characters. Error:<br />the parameter <i>url_to_display</i> need to match a valid URL.</strong>';
     return ob_get_clean();
    }

   if( $w3all_custom_output_files == 1 ) {
     $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/wp_w3all_custom_iframe_short.php';
     if (!file_exists($file)){
     $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/wp_w3all_custom_iframe_short.php';// old way
     }
    ob_start();
      include($file);
    return ob_get_clean();
    } else {
     $file = WPW3ALL_PLUGIN_DIR . 'views/wp_w3all_custom_iframe_short.php';
    ob_start();
      include($file);
    return ob_get_clean();
    }
}

// w3allphpbbupm // wp_w3all_phpBB_u_pm_short vers 1.0 x phpBB PM
public static function wp_w3all_phpbb_upm_short( $atts ) {
 global $w3all_custom_output_files,$w3all_url_to_cms,$w3all_phpbb_usession;

if ( !empty($w3all_phpbb_usession) ){

   $phpbb_user_session[0] = $w3all_phpbb_usession; // maintain compatibility with old way for views file

   if($w3all_phpbb_usession->user_unread_privmsg > 0){

   $args = shortcode_atts( array(
    'w3pm_class' => 'w3pm_class',
    'w3pm_id' => 'w3pm_id',
    'w3pm_inline_style' => '',
    'w3pm_href_blank' => ''
   ), $atts );

 $w3pm_inline_style = empty($args['w3pm_inline_style']) ? '' : ' style="'.$args['w3pm_inline_style'].'"';
 $w3pm_href_blank = ($args['w3pm_href_blank'] > 0) ? ' target="_blank"' : '';
 $w3pm_href = $w3all_url_to_cms.'/ucp.php?i=pm&amp;folder=inbox';

 $w3pm_class = $args['w3pm_class'];
 $w3pm_id = $args['w3pm_id'];

  if( $w3all_custom_output_files == 1 ) {
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/wp_w3all_phpbb_upm_short.php';
   if (!file_exists($file)){
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/wp_w3all_phpbb_upm_short.php';// old way
   }
   ob_start();
    include($file);
   return ob_get_clean();
  } else {
   $file = WPW3ALL_PLUGIN_DIR . 'views/wp_w3all_phpbb_upm_short.php';
   ob_start();
    include($file);
   return ob_get_clean();
  }

 } // END if($phpbb_user_session[0]->user_unread_privmsg > 0){

}
  else { return; }
} // END function wp_w3all_phpbb_upm_short

// wp_w3all_feeds_short vers 1.0 x phpBB feeds
public static function wp_w3all_feeds_short( $atts ) {
  global $w3all_custom_output_files;
if(is_array($atts)){
  $atts = array_map ('trim', $atts);
} else {
  return;
}

/* if ( !function_exists( 'wp_simplepie_autoload' ) ) {
  require_once ABSPATH . WPINC . '/class-simplepie.php'; // native simplepie lib
 } */

include_once( ABSPATH . WPINC . '/feed.php' );

 $feed_v = shortcode_atts( array(
    'w3feed_url' => '',
    'w3feed_items_num' => '10',
    'w3feed_text_words' => 'content',
    'w3feed_ul_class' => '',
    'w3feed_li_class' => '',
    'w3feed_inline_style' => '',
    'w3feed_href_blank' => ''
  ), $atts );

 $w3feed_ul_class = $feed_v['w3feed_ul_class'];
 $w3feed_li_class = $feed_v['w3feed_li_class'];
 $w3feed_text_words = $feed_v['w3feed_text_words'];
 $w3feed_inline_style = $feed_v['w3feed_inline_style'];
 $w3feed_href_blank = $feed_v['w3feed_href_blank'];

 $w3feed_inline_style = (empty($w3feed_inline_style)) ? '' : ' style="'.$w3feed_inline_style.'"';
 $w3feed_href_blank = ($w3feed_href_blank > 0) ? ' target="_blank"' : '';

 if(parse_url($feed_v['w3feed_url']) == null){
   ob_start();
    echo'Error: passed (feed) URL is not valid';
   return ob_get_clean();
  }

// Get a SimplePie feed object from the specified feed source.
$rss = fetch_feed( $feed_v['w3feed_url'] );

$maxitems = 0;
 if ( ! is_wp_error( $rss ) ) {
    // Figure out how many total items there are, but limit it to passed val.
    $maxitems = $rss->get_item_quantity( intval($feed_v['w3feed_items_num']) );
    // Build an array of all the items, starting with element 0 (first element).
    $rss_items = $rss->get_items( 0, $maxitems );
  } else {
   ob_start();
    echo $rss->get_error_message();
   return ob_get_clean();
  }

   if( $w3all_custom_output_files == 1 ) {
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/wp_w3all_feeds_short.php';
   if (!file_exists($file)){
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/wp_w3all_feeds_short.php';// old way
   }
    ob_start();
      include($file);
    return ob_get_clean();
    } else {
     $file = WPW3ALL_PLUGIN_DIR . 'views/wp_w3all_feeds_short.php';
    ob_start();
      include($file);
    return ob_get_clean();
    }

}

// wp_w3all_get_phpbb_lastopics_short vers 1.0 x (phpbb_last_topics_forums_ids_shortcode.php) single or multiple forums
public static function wp_w3all_phpbb_last_topics_single_multi_fp_short( $atts ) {
  global $w3all_phpbb_connection,$wp_userphpbbavatar,$w3all_phpbb_usession,$w3all_config,$w3all_url_to_cms,$w3all_get_topics_x_ugroup,$w3all_lasttopic_avatar_num,$w3all_last_t_avatar_yn,$w3all_last_t_avatar_dim,$w3all_get_phpbb_avatar_yn,$w3all_phpbb_widget_mark_ru_yn,$w3all_custom_output_files,$w3all_phpbb_widget_FA_mark_yn,$phpbb_unread_topics;

 if(is_array($atts)){
  $atts = array_map('trim', $atts);
 } else {
  return;
 }

 if(empty($w3all_phpbb_connection)){ return; }

    $ltm = shortcode_atts( array(
        'forums_id' => '0',
        'page_in' => '0', // not used
        'topics_number' => '0',
        'post_text' => '0',
        'text_words' => '0',
        'w3_ul_class' => '',
        'w3_li_class' => '',
        'w3_inline_style' => '',
        'w3_href_blank' => '0',
        'no_avatars' => ''
    ), $atts );

    if( empty($ltm['forums_id']) OR preg_match('/[^[,0-9]/',$ltm['forums_id']) ){
      echo'Specified parameter <i>forums_id</i> on Shortcode <i>w3allastopicforumsids</i> not found or contain wrong characters. w3all shortcode error.<br /> The shortcode need to be added like this:<br /><pre>[w3allastopicforumsids topics_number="5" forums_id="4,8"]</pre><br />change \'4,8\' <strong>with existent phpBB forums ID to display here (also a single one).</strong>';
      return;
    }

    $no_avatars = intval($ltm['no_avatars']) > 0 ? 1 : 0;
    $topics_number = intval($ltm['topics_number']) > 0 ? intval($ltm['topics_number']) : 5; // 5 by default if not specified
    $wp_w3all_post_text = intval($ltm['post_text']) > 0 ? intval($ltm['post_text']) : 0;
    $wp_w3all_text_words = intval($ltm['text_words']) > 0 ? intval($ltm['text_words']) : 30;

    $w3_ul_class_ids = empty($ltm['w3_ul_class']) ? '' : $ltm['w3_ul_class'];
    $w3_li_class_ids = empty($ltm['w3_li_class']) ? '' : $ltm['w3_li_class'];
    $w3_inline_style_ids = empty($ltm['w3_inline_style']) ? '' : ' style="'.$ltm['w3_inline_style'].'"';
    $w3_href_blank_ids = intval($ltm['w3_href_blank'] > 0) ? ' target="_blank"' : '';

  $topics_x_ugroup = '';

if($w3all_get_topics_x_ugroup == 1){ // list of allowed forums to retrieve topics if option active
  if (!empty($w3all_phpbb_usession)) {
   $ug = $w3all_phpbb_usession->group_id;
  } else {
   $ug = 1; // the default phpBB guest user group
  }

 $gaf = $w3all_phpbb_connection->get_results("SELECT DISTINCT ".$w3all_config["table_prefix"]."acl_groups.forum_id FROM ".$w3all_config["table_prefix"]."acl_groups
  WHERE ".$w3all_config["table_prefix"]."acl_groups.auth_role_id != 16
  AND ".$w3all_config["table_prefix"]."acl_groups.group_id = ".$ug."");

  if(!empty($gaf)){
      $gf = '';
       foreach( $gaf as $v ){
        $gf .= $v->forum_id.',';
       }
    $gf = substr($gf, 0, -1);
    $topics_x_ugroup = "AND T.forum_id IN(".$gf.")";
   }} else {
  $topics_x_ugroup = '';
}

   $topics = $w3all_phpbb_connection->get_results("SELECT T.*, P.*, U.*
    FROM ".$w3all_config["table_prefix"]."topics AS T
    JOIN ".$w3all_config["table_prefix"]."posts AS P on (T.topic_last_post_id = P.post_id and T.forum_id = P.forum_id)
    JOIN ".$w3all_config["table_prefix"]."users AS U on U.user_id = T.topic_last_poster_id
    WHERE T.topic_visibility = 1
    AND T.forum_id IN(".$ltm['forums_id'].")
    ".$topics_x_ugroup."
    AND P.post_visibility = 1
    ORDER BY T.topic_last_post_time DESC
    LIMIT 0,$topics_number");

     $last_topics = is_array($topics) && !(empty($topics)) ? $topics : array();

// Unfortunately, it is needed to add avatars for these users, may extracted by an excluded forum ID on plugin admin option, but retrieved by ids on this shortcode
// Push these users into avatars list
if(!empty($last_topics)){
 $res_add_users_x_ava = serialize($last_topics);
 define("W3ALLFORUMSIDSSHORT",$res_add_users_x_ava);
 self::wp_w3all_assoc_phpbb_wp_users();
}

   if ( $w3all_phpbb_widget_mark_ru_yn == 1 && is_user_logged_in() ) {
    // $username = true is passed/used here to avoid the define Constant W3UNREADTOPICS, already defined for widgets and shortcodes that follow another flow
    // ... there is nothing to define in this case ... hope there is not more than one shortcode instance on same page!
    self::w3all_get_unread_topics($username = true, '', '', $topics_number, 0);
    $phpbb_unread_topics = empty($phpbb_unread_topics) ? array() : unserialize($phpbb_unread_topics);
   }

   if( $w3all_custom_output_files == 1 ) {
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/phpbb_last_topics_forums_ids_shortcode.php';
   if (!file_exists($file)){
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/phpbb_last_topics_forums_ids_shortcode.php';// old way
   }
    ob_start();
      include($file);
    return ob_get_clean();
   } else {
     $file = WPW3ALL_PLUGIN_DIR . 'views/phpbb_last_topics_forums_ids_shortcode.php';
    ob_start();
      include($file);
    return ob_get_clean();
    }
}

// wp_w3all_get_phpbb_lastopics_short vers 1.0
# if it is from an heartbeat, then the number of the topics when an heartbeat occur
# can be only a specified number -> $w3all_heartbeat_phpbb_lastopics_num
public static function wp_w3all_get_phpbb_lastopics_short( $atts, $from_hb = false ) {
  global $w3all_lasttopic_avatar_num,$wp_userphpbbavatar,$w3all_url_to_cms,$w3all_last_t_avatar_yn,$w3all_last_t_avatar_dim,$w3all_get_phpbb_avatar_yn,$w3all_phpbb_widget_mark_ru_yn,$w3all_custom_output_files,$w3all_phpbb_widget_FA_mark_yn,$w3all_heartbeat_phpbb_lastopics_num;

  if(is_array($atts)){
   $atts = array_map ('trim', $atts);
  } else {
     $atts = array();
    }

    $ltm = shortcode_atts( array(
        'mode' => '0',
        'topics_number' => '5',
        'post_text' => '0',
        'text_words' => '0',
        'w3_ul_class' => '',
        'w3_li_class' => '',
        'w3_inline_style' => '',
        'w3_href_blank' => '',
        'no_avatars' => ''
    ), $atts );

    //$mode = intval($ltm['mode']) > 0 ? 0 : 0; // not used
    $no_avatars = intval($ltm['no_avatars']) > 0 ? 1 : 0;
    $topics_number = intval($ltm['topics_number']) > 0 ? intval($ltm['topics_number']) : 5;

    $wp_w3all_post_text = intval($ltm['post_text']) > 0 ? intval($ltm['post_text']) : 0;
    $wp_w3all_text_words = intval($ltm['text_words']) > 0 ? intval($ltm['text_words']) : 0;

    $w3_ul_class_lt = empty($ltm['w3_ul_class']) ? '' : $ltm['w3_ul_class'];
    $w3_li_class_lt = empty($ltm['w3_li_class']) ? '' : $ltm['w3_li_class'];
    $w3_inline_style_lt = empty($ltm['w3_inline_style']) ? '' : $ltm['w3_inline_style'];
    $w3_href_blank_lt = $ltm['w3_href_blank'] > 0 ? ' target="_blank"' : ''; // not used at moment

   if ( $w3all_phpbb_widget_mark_ru_yn == 1 && is_user_logged_in() ) {
    if (defined("W3UNREADTOPICS")){
     $phpbb_unread_topics = unserialize(W3UNREADTOPICS);
    }
   }

  if (defined("W3PHPBBLASTOPICS")){
    $last_topics = unserialize(W3PHPBBLASTOPICS); // see wp_w3all.php
  } else {
   $last_topics = WP_w3all_phpbb::last_forums_topics_res($topics_number);
  }

  if( $from_hb ){ # it is from an heartbeat, cut the array based on option
   $w3all_heartbeat_phpbb_lastopics_num = $w3all_heartbeat_phpbb_lastopics_num > 0 ? $w3all_heartbeat_phpbb_lastopics_num : 5;
   $last_topics = array_slice($last_topics, 0, intval($w3all_heartbeat_phpbb_lastopics_num), true);
  }

   if( $w3all_custom_output_files == 1 ) {
    $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/phpbb_last_topics_output_shortcode.php';
    if (!file_exists($file)){
     $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/phpbb_last_topics_output_shortcode.php';// old way
    }
   } else {
      $file = WPW3ALL_PLUGIN_DIR . 'views/phpbb_last_topics_output_shortcode.php';
     }
  ob_start();
   include($file);
  return ob_get_clean();
}

// Detect if a shorcode (or some else) should be parsed on page based on
// $wp_w3all_heartbeat_phpbb_lastopics_pages value or passed $pages
// against actual $_SERVER['REQUEST_URI'] viewed page
public static function w3all_ck_if_onpage($pages='',$shortIndex='')
 {
   global $wp_w3all_heartbeat_phpbb_lastopics_pages;

   $wp_w3all_heartbeat_phpbb_lastopics_pages = empty($pages) ? $wp_w3all_heartbeat_phpbb_lastopics_pages : $pages;

  if(empty($wp_w3all_heartbeat_phpbb_lastopics_pages)) return false;

   if ( is_admin() OR is_customize_preview() OR empty($wp_w3all_heartbeat_phpbb_lastopics_pages) )
   { return false; }

   if( preg_match('/[^-0-9A-Za-z\._#\:\?\/=&%]/ui',$_SERVER['REQUEST_URI']) )
   { return false; }

  /*$homeurl = substr(get_home_url(), -1) == '/' ? substr(get_home_url(), 0, -1) : get_home_url();
    if(!filter_var($homeurl.$_SERVER['REQUEST_URI'], FILTER_VALIDATE_URL)){
     return false;
    }*/

 // Maybe Url encoded
  if( strpos($_SERVER['REQUEST_URI'], "%2E") OR strpos($_SERVER['REQUEST_URI'], "%2F") OR strpos($_SERVER['REQUEST_URI'], "%23") ){
   $_SERVER['REQUEST_URI'] = urldecode($_SERVER['REQUEST_URI']);
  }

   if(!empty($wp_w3all_heartbeat_phpbb_lastopics_pages)){
    $hb_phpbb_shortpages = explode(',',trim($wp_w3all_heartbeat_phpbb_lastopics_pages));
   }

  if(!empty($hb_phpbb_shortpages) && is_array($hb_phpbb_shortpages))
  {
    $hb_phpbb_shortpages = array_map('trim', $hb_phpbb_shortpages);
    $shortonpage = $shortonpage_home = false;

    foreach($hb_phpbb_shortpages as $p)
    { // detect which/if page match in the request and check if inhomepage-hb-phpbb-last-posts in home has been set: check it after
      if($p=='inhomepage-hb-phpbb-last-posts'){ $shortonpage_home = true; }
      if(strpos($_SERVER['REQUEST_URI'],'/'.$p))
      {
       $shortonpage = true;
      }
    }

  if(!$shortonpage && $shortonpage_home)
  {
    if(!empty($_SERVER['REQUEST_URI']))
    {
     if(substr($_SERVER['REQUEST_URI'], -1, 1) == '/'){
      $REQUEST_URI = substr($_SERVER['REQUEST_URI'], 0, -1);
     } else { $REQUEST_URI = $_SERVER['REQUEST_URI']; }

      $siteUrl = get_option('siteurl');

     if(substr($siteUrl, -1, 1) == '/'){
      $siteUrl = substr($siteUrl, 0, -1);
     }

     if(!empty($REQUEST_URI) && strpos($siteUrl, $REQUEST_URI))
     {
      if(strpos($siteUrl, $REQUEST_URI) !== false)
      { $shortonpage = true; }
     } elseif ( $_SERVER['REQUEST_URI'] == '/' )
       { $shortonpage = true; }
       elseif ( !strpos($siteUrl, $REQUEST_URI) )
       {
         $ck = explode('/?',$REQUEST_URI);
         if(isset($ck[0]) && true === strpos($siteUrl, $ck[0]))
          { $shortonpage = true; }

         $ck = explode('/index.php',$REQUEST_URI);
         if(isset($ck[0]) && true === strpos($siteUrl, $ck[0]))
          { $shortonpage = true; }
       }
    }
   }
   return $shortonpage;
  } else { return false; }

}


// wp_w3all_get_phpbb_lastopics_short_wi vers 1.0 (with images)
// retrieve for each post/topic, the first topic's post img attach to display into a grid
// NOTE: as is the query the result will contain only topics with almost an attach inside on one of their posts:
// only the first (time based) inserted, will be retrieved to display
public static function wp_w3all_get_phpbb_lastopics_short_wi( $atts ) {
  global $w3all_phpbb_connection,$phpbb_config,$w3all_config,$w3all_phpbb_usession,$w3all_url_to_cms,$w3all_lasttopic_avatar_num,$w3all_last_t_avatar_yn,$w3all_last_t_avatar_dim,$w3all_get_phpbb_avatar_yn,$w3all_phpbb_widget_mark_ru_yn,$w3all_custom_output_files,$w3all_phpbb_widget_FA_mark_yn,$w3all_get_topics_x_ugroup;

    if(empty($phpbb_config)){
     self::w3all_get_phpbb_config();
    }

   $atts = array_map ('trim', $atts);

    $ltm = shortcode_atts( array(
        'cat_id' => '0',
        'topics_number' => '0',
        'post_text' => '0',
        'text_words' => '0',
        'columns_number' => '2',
        'gap_columns' => '0',
    ), $atts );

    $cat_id = intval($ltm['cat_id']) > 0 ? intval($ltm['cat_id']) : 0;
    $topics_number = intval($ltm['topics_number']) > 0 ? intval($ltm['topics_number']) : 5;
    $wp_w3all_post_text = intval($ltm['post_text']) > 0 ? intval($ltm['post_text']) : 0;
    $wp_w3all_text_words = intval($ltm['text_words']) > 0 ? intval($ltm['text_words']) : 5;
    $wp_w3all_columns_number = intval($ltm['columns_number']) > 1 ? intval($ltm['columns_number']) : 2; // minimum 2 ... as code is on views/phpbb_last_topics_withimage_output_shortcode.php
    $wp_w3all_gap_columns = intval($ltm['gap_columns']) > 1 ? intval($ltm['gap_columns']) : 0; // gap space between columns, after calculated in %

   if ( $w3all_phpbb_widget_mark_ru_yn == 1 && is_user_logged_in() ) {
    if (defined("W3UNREADTOPICS")){
     $phpbb_unread_topics = unserialize(W3UNREADTOPICS);
    }
   }

if( $w3all_get_topics_x_ugroup == 1 ){
  if (!empty($w3all_phpbb_usession)) {
   $ug = $w3all_phpbb_usession->group_id;
  } else {
  $ug = 1; // the default phpBB guest user group
}
//$gaf = $w3db_conn->get_results("SELECT DISTINCT forum_id FROM ".$w3all_config["table_prefix"]."acl_groups WHERE group_id = ".$ug." ORDER BY forum_id");
  $gaf = $w3all_phpbb_connection->get_results("SELECT DISTINCT ".$w3all_config["table_prefix"]."acl_groups.forum_id FROM ".$w3all_config["table_prefix"]."acl_groups
  WHERE ".$w3all_config["table_prefix"]."acl_groups.auth_role_id != 16
  AND ".$w3all_config["table_prefix"]."acl_groups.group_id = ".$ug."");
 if(empty($gaf)){
   return array(); // no forum found that can show topics for this group ...
 } else {
    $gf = '';
      foreach( $gaf as $v ){
    $gf .= $v->forum_id.',';
   }
   $gf = substr($gf, 0, -1);
   $topics_x_ugroup = "AND ". $w3all_config["table_prefix"] ."topics.forum_id IN(".$gf.")";
 }
}
 else {
  $topics_x_ugroup = '';
}

 $last_topics = $w3all_phpbb_connection->get_results("SELECT * FROM  ". $w3all_config["table_prefix"] ."posts
  JOIN ". $w3all_config["table_prefix"] ."topics ON ". $w3all_config["table_prefix"] ."topics.topic_id = ". $w3all_config["table_prefix"] ."posts.topic_id
   AND ". $w3all_config["table_prefix"] ."topics.topic_visibility = 1
   AND ". $w3all_config["table_prefix"] ."topics.topic_last_post_id = ". $w3all_config["table_prefix"] ."posts.post_id
   ".$topics_x_ugroup."
  JOIN ". $w3all_config["table_prefix"] ."forums ON ". $w3all_config["table_prefix"] ."forums.parent_id =  '".$cat_id."'
   AND ". $w3all_config["table_prefix"] ."topics.forum_id = ". $w3all_config["table_prefix"] ."forums.forum_id
  JOIN ". $w3all_config["table_prefix"] ."attachments
   WHERE ". $w3all_config["table_prefix"] ."attachments.attach_id = (SELECT MIN(attach_id) FROM ". $w3all_config["table_prefix"] ."attachments WHERE ". $w3all_config["table_prefix"] ."attachments.topic_id = ". $w3all_config["table_prefix"] ."topics.topic_id)
  ORDER BY ". $w3all_config["table_prefix"] ."posts.post_time DESC LIMIT 0,$topics_number");

if ( count($last_topics) < 2 ) { echo 'Almost two topics with attachments required to display from choosen forums!'; return; }

   if( $w3all_custom_output_files == 1 ) {
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/phpbb_last_topics_withimage_output_shortcode.php';
   if (!file_exists($file)){
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/phpbb_last_topics_withimage_output_shortcode.php';// old way
   }
    ob_start();
      include($file);
    return ob_get_clean();
    } else {
     $file = WPW3ALL_PLUGIN_DIR . 'views/phpbb_last_topics_withimage_output_shortcode.php';
    ob_start();
      include( $file );
    return ob_get_clean();
    }
}

//#######################
// START SHORTCODE code for phpBB POST into WP
//#######################

// wp_w3all_get_phpbb_post_short Version 1.2 // 24/01/2022
// 1.1> add attachments
// To be improved about: cleanup fake html tags and carriage return to be ever exactly the same

public static function wp_w3all_get_phpbb_post_short( $atts ) {
  global $w3all_config,$w3all_phpbb_connection,$w3all_url_to_cms;

    $p = shortcode_atts( array(
        'id' => '0',
        'plaintext' => '0',
        'wordsnum' => '0'
    ), $atts );

  $p['id'] = intval($p['id']);
   if($p['id'] == 0){
    return "w3all shortcode error.<br /> The shortcode need to be added like this:<br />[w3allforumpost id=\"150\"]<br />change '150' <strong>with the (existent) phpBB post ID to display here.</strong>";
   }

   $phpbb_post = $w3all_phpbb_connection->get_results("SELECT *
    FROM ". $w3all_config["table_prefix"] ."topics
    JOIN ". $w3all_config["table_prefix"] ."posts ON ". $w3all_config["table_prefix"] ."posts.post_id =  ". $p['id'] ."
     AND ". $w3all_config["table_prefix"] ."topics.topic_visibility = 1
     AND ". $w3all_config["table_prefix"] ."topics.topic_id = ". $w3all_config["table_prefix"] ."posts.topic_id
     AND ". $w3all_config["table_prefix"] ."posts.post_visibility = 1",ARRAY_A);

     if( intval($p['plaintext']) == 1 && !empty($phpbb_post[0]) ){
      if($p['wordsnum'] > 0){
       return wp_w3all_remove_bbcode_tags($phpbb_post[0]['post_text'], $p['wordsnum']);
      }
       return preg_replace('/[[\/\!]*?[^\[\]]*?]/', '', $phpbb_post[0]['post_text']); // REVIEW // remove all bbcode tags (not html nor fake tags) //
     }

  if( empty($phpbb_post[0]) ){
   $res = '<b>Error:<br />the provided post ID do not match an existent phpBB post</b>';
   return $res;
  } elseif( isset($phpbb_post[0]['post_attachment']) && $phpbb_post[0]['post_attachment'] > 0 ) // get attachments if there are
  {
   // get attachments DESC ordered: array keys that match the inline placeholder id will correctly pair the attach (even when into post there are images named the same)
   // like [attachment=0]image.png[/attachment][attachment=5]image.png[/attachment][attachment=1]image.png[/attachment]
   $phpbb_post_attach = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."attachments WHERE post_msg_id = '".$p['id']."' ORDER BY attach_id DESC",ARRAY_A);
  }

  $phpbb_post = $phpbb_post[0]; // make it easy
  $validImgExt = array("jpg", "jpeg", "gif", "png");
  $suffixes = array('', 'kb', 'mb', 'gb', 'tb');
  $precision = 2;

 // TODO: clean up after all, before return the output, all the 'non existent' tags like:<e> and </e> and more

 // START Grab attachments "placed inline" on post and replace
 // remove each added inline attach from the attachments array: if there are more that belongs to the post, append to the output after

 // get the inline attach number, and all within a bbcode attach
  preg_match_all('~\[attachment=([0-9+])\]([^\[]*)\[/attachment\]~si', $phpbb_post['post_text'], $amatches, PREG_SET_ORDER);

 if( $amatches && !empty($phpbb_post_attach) )
 { // going to pair, replace (and remove from main attach array the inline attachments)

  foreach($amatches as $am => $amm)
  {
   foreach( $phpbb_post_attach as $pa => $ppa )
   {
    if( $pa == $amm[1] ) // placeholder id match the array key: this is our attach array to get values from and assign replacing for this placeholder...
    {
       $base = log($ppa['filesize'], 1024);
       $fsize_display = round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];

      if (!in_array(strtolower($ppa['extension']), $validImgExt)) { // this is not an image
       $areplace = '<br /><a style="font-weight:700" href="'.$w3all_url_to_cms.'/download/file.php?id='.$ppa['attach_id'].'">'.$ppa['real_filename'].'</a> <i>('.$fsize_display.')</i><br />';
      } else { // this is an image // maybe some img ext need to be added, because above array $validImgExt only contain "jpg", "jpeg", "gif", "png" so only these are considered to be parsed like an img here
         $areplace = '<br /><img src="'.$w3all_url_to_cms.'/download/file.php?id='.$ppa['attach_id'].'" alt="'.$ppa['real_filename'].'" /><br />'.$ppa['real_filename'].' <i>('.$fsize_display.')</i><br />';
        }

      $phpbb_post['post_text'] = preg_replace('~\[attachment='.$amm[1].'\]([^\[]*)\[/attachment\]~si', $areplace, $phpbb_post['post_text']);
      unset($phpbb_post_attach[$pa]); // remove this inline attach, only 'non added inline' need to display on bottom of the post
     }
   }
  }
 }
// If there are remaining attachments not inline, display them after the post content, more below
// END Grab attachments "placed inline" on post and replace. Inline attachments have been removed from attachments array

// START Grab the code and replace with a placeholder '#w3#bbcode#replace#'
// so, after the others bbcode tags conversions, re-add each, properly wrapped
preg_match_all('~\<s\>\[code\]\</s\>(.*?)\<e\>\[/code\]\</e\>~si', $phpbb_post['post_text'], $cmatches, PREG_SET_ORDER);
if($cmatches){ // remove and add custom placeholder
$cc = 0;
$phpbb_post['post_text'] = preg_replace('~\<s\>\[code\]\</s\>(.*?)\<e\>\[/code\]\</e\>~si', '#w3#bbcode#replace#', $phpbb_post['post_text'], -1 ,$cc);
// split and add 'placeholders'
$ps = preg_split('/#w3#bbcode#replace#/', $phpbb_post['post_text'], -1, PREG_SPLIT_DELIM_CAPTURE);
$ccc = 0;
$res = '';
foreach($ps as $p0 => $s){
if($ccc < $cc){
 $res .= $s.'#w3#bbcode#replace#'.$ccc++; // append/assing number to placeholder for this split/string
} else { $res .= $s; } // follow add the latest text, if no more placeholders ...
}
} else { $res = $phpbb_post['post_text']; }

$res = WP_w3all_phpbb::w3all_bbcodeconvert($res); // convert all bbcode tags except [code] and [attachment]

if($p['wordsnum'] > 0){

 $post_s = $res;
 $res0 = explode(' ',$post_s);

  if( count($res0) < $p['wordsnum'] ) : return $post_s; endif;

 $post_std = ''; $i = 0; $b = $p['wordsnum'];
 $res1 = '';
  foreach ($res0 as $post_st) {
    $i++;
    if( $i < $b + 1 ){ // offset of 1

      $res1 .= $post_st . ' ';
    }
  }
  $res = $res1;
}

if($cmatches){ // re-add grabbed bbcode blocks and wrap with html ...
$cccc = 0;
foreach($cmatches as $k => $v){
$res = str_ireplace('#w3#bbcode#replace#'.$cccc, '<code>'.$v[1].'</code>', $res);
$cccc++;
}
}
// END Grab the code and replace with a placeholder '#w3#bbcode#replace#'

// remaining attachments non-inline START
    if(!empty($phpbb_post_attach))
    {
      $attach_output = '';
      //$phpbb_post_attach = array_reverse($phpbb_post_attach, true); // DESC into query has been used
      foreach($phpbb_post_attach as $pa)
      {
       $base = log($pa['filesize'], 1024);
       $fsize_display = round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
        if (!in_array(strtolower($pa['extension']), $validImgExt)) {
         $attach_output .= '<div style="padding:5px 5px 0 5px;"><a style="font-weight:900" href="'.$w3all_url_to_cms.'/download/file.php?id='.$pa['attach_id'].'">'.$pa['real_filename'].'</a> &nbsp;<i>('.$fsize_display.')</i></div>';
        } else { // it is an image
           $attach_output .= '<div style="padding:5px 5px 0 5px;"><img src="'.$w3all_url_to_cms.'/download/file.php?id='.$pa['attach_id'].'" alt="'.$pa['real_filename'].'" /><br />'.$pa['real_filename'].' <i>('.$fsize_display.')</i></div>';
          }
       }
     }

    if(!empty($attach_output)){
     $attach_output = '<div style="background:#F3F3F3;margin:10px 0;padding:10px"><div><i>'.__( 'Attachments', 'wp-w3all-phpbb-integration' ).'</i><hr style="margin:0;" /></div>'.$attach_output .'</div>';
    }
    if(isset($phpbb_post_attach) && !empty($attach_output)){
      $res .= $attach_output;
    }
// attachments END

return $res;

}


public static function w3all_bbcodeconvert($text) {

  $text = trim(str_replace(chr(0), '', $text));

  // a default (+-- complete) phpBB bbcode array
  $find = array(
    '~\[b\](.*?)\[/b\]~usi',
    '~\[i\](.*?)\[/i\]~usi',
    '~\[u\](.*?)\[/u\]~usi',
    '~\[quote\](.*?)\[/quote\]~usi',
    '~\[size=(.*?)\](.*?)\[/size\]~usi',
    '~\[color=(.*?)\](.*?)\[/color\]~usi',
    '~\[url\](.*?)\[/url\]~s',
    '~\[url=(.*?)\](.*?)\[/url\]~s', // text url
    '~\[img\](http|https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~usi',
    '~\[media\](.*?)\[/media\]~usi', // media: see https://www.phpbb.com/customise/db/extension/mediaembed/
    '~<[/]?[r|s|e]>~usi', // no conversion, remove
    '~<[/]?[color][^>]+?>~usi', // no conversion, remove
    '~\[img\].*?\[/img\]~usi', // image link remove // // REVIEW THIS
    '~(^(\r\n|\r|\n))|^\s*$~m', // replace an empty line with <br /> // REVIEW THIS
// start ul/ol lists
    '~\[list\](.*?)\[/list\]~usi', // ul unordered/list
    '~\[list=(1){1}\](.*?)\[/list\]~ums', // ol lists // decimal list
    '~\[list=(a){1}\](.*?)\[/list\]~ums', // ol lists // lower-alpha list
    '~\[list=(A){1}\](.*?)\[/list\]~ums', // ol lists // upper-alpha list
    '~\[list=(i){1}\](.*?)\[/list\]~ums', // ol lists // lower-roman list
    '~\[list=(I){1}\](.*?)\[/list\]~ums' // ol lists // upper-roman list

  );
// html BBcode replaces
  $replace = array(
    '<b>$1</b>',
    '<span style="font-style: italic;">$1</span>',
    '<span style="text-decoration:underline;">$1</span>',
    '<blockquote style="font-style: italic;">$1</blockquote>',
    '<span style="font-size:$1%;">$2</span>', // % here
    '<span style="color:$1;">$2</span>',
    '<a href="$1">$1</a>',
    '<a href="$1">$2</a>', // text url
    '<img src="$1" alt="" />',
    '[wpw3allmediaconvert]$1[wpw3allmediaconvert]',
    '',
    '',
    '',
    '<br />',
// start ul/ol lists
    '<ul>$1</ul>',
    '<ol style="list-style-type: decimal">$2</ol>',
    '<ol style="list-style-type: lower-alpha">$2</ol>',
    '<ol style="list-style-type: upper-alpha">$2</ol>',
    '<ol style="list-style-type: lower-roman">$2</ol>',
    '<ol style="list-style-type: upper-roman">$2</ol>'
  );


$text = preg_replace($find, $replace, $text, PREG_OFFSET_CAPTURE);

$text = preg_replace_callback(
            "~<ul>(.*?)</ul>|<ol(.*?)</ol>~sm",
            "WP_w3all_phpbb::w3_bbcode_rep0",
            $text);

$text = preg_replace_callback(
            "~\[wpw3allmediaconvert\](.*?)\[wpw3allmediaconvert\]~sm",
            "WP_w3all_phpbb::w3_bbcode_media",
            $text);

  $text = preg_replace('/\[.*\].*\[.*\]/', '', $text); // remove any bbcode-like if still there is

  return $text;
}

public static function w3_bbcode_media($vmatches)
{
  // seem to work in few lines // to be fixed
 $vmatches[0] = str_replace('[wpw3allmediaconvert]', '', $vmatches[0]);
 $pos = strpos($vmatches[0], '">');
 $vmatches[0] = substr($vmatches[0], $pos+2);
 $vmatches[0] = str_replace('</URL>', '', $vmatches[0]);
 $vmatches[0] = wp_oembed_get($vmatches[0]);
return $vmatches[0];
}

public static function w3_bbcode_rep0($matches)
{
  $matches[0] = preg_replace('~\[\*\]~', '<li>', $matches[0]); // to be improved ... but work as is
  return $matches[0];
}

//#######################
// END SHORTCODE for phpBB POST into WP
//#######################

//#######################
// START ABOUT AVATARS
//#######################

public static function wp_w3all_assoc_phpbb_wp_users() {

  global $wp,$wp_userphpbbavatar,$w3all_u_ava_urls,$w3all_get_phpbb_avatar_yn,$phpbb_online_udata,$w3all_widget_phpbb_onlineStats_exec,$w3all_last_t_avatar_yn, $w3all_lasttopic_avatar_num, $w3all_wlastopicspost_max;

    $w3all_lasttopic_avatar_num = $w3all_wlastopicspost_max > $w3all_lasttopic_avatar_num ? $w3all_wlastopicspost_max : $w3all_lasttopic_avatar_num;

  $w3all_avatars_yn = $w3all_get_phpbb_avatar_yn == 1 ? true : false;
  $p_unames = array();

 $nposts = get_option('posts_per_page');
 $post_list = get_posts( array(
    'user_id',
    'numberposts'    => $nposts,
    'sort_order' => 'desc',
    'post_status' => 'publish'
  ) );

    foreach ( $post_list as $post ) {

     $uname = get_user_by('ID', $post->post_author);
     $p_unames[] = $uname->user_email;

     $comments = get_comments( array( 'post_id' => $post->ID ) );
      foreach ( $comments as $comment ) :
       if ( $comment->user_id > 0 ):
        $p_unames[] = $comment->comment_author_email;
       endif;
      endforeach;
   }

// if any other condition fail assigning avatars to users, add it here

// this is from wp_w3all_phpbb_last_topics_single_multi_fp_short() for views/phpbb_last_topics_forums_ids_shortcode.php
// it need to push users for wp_w3all_assoc_phpbb_wp_users() re-called to add users that may do not exists on widget calls, may because the forum ID is excluded into plugin option, but after used into [w3allastopicforumsids] shortcode, or because it contain different num of posts to retrieve
// see: views/phpbb_last_topics_forums_ids_shortcode.php file
// see: wp_w3all_phpbb_last_topics_single_multi_fp_short()
// see: wp_w3all_phpbb_custom_avatar()

if(defined("W3ALLFORUMSIDSSHORT")){
  $short_call_add_users_ava = unserialize(W3ALLFORUMSIDSSHORT);
  foreach ($short_call_add_users_ava as $e){
    $p_unames[] = $e->user_email;
  }
}

// $w3all_widget_phpbb_onlineStats_exec -> class WP_w3all_widget_phpbb_onlineStats extends WP_Widget
 if($w3all_widget_phpbb_onlineStats_exec > 0){
  w3all_get_phpbb_onlineStats();
  if(!empty($phpbb_online_udata))
  {
   foreach ($phpbb_online_udata as $p){
    $p_unames[] = $p['user_email'];
   }
  }
 }

   // add current user
   $current_user = wp_get_current_user();
   if ($current_user->ID > 0){
    $p_unames[] = $current_user->user_email;
   }

  // add current profile user ( may only work into default wp profile )
  if (isset($_GET['user_id'])){
   $guid = intval($_GET['user_id']);
    $u = get_user_by( 'ID', $guid );
   if(!empty($u)){
    $p_unames[] = $u->user_email;
   }
  }

  // add usernames for last topics widget, if needed
 if ( $w3all_avatars_yn ) :
  if (defined("W3PHPBBLASTOPICS")){
    $w3all_last_posts_users = unserialize(W3PHPBBLASTOPICS);
   } else {
    $w3all_last_posts_users = WP_w3all_phpbb::last_forums_topics($w3all_lasttopic_avatar_num);
    $t = is_array($w3all_last_posts_users) ? serialize($w3all_last_posts_users) : serialize(array());
   }

   if(!empty($w3all_last_posts_users)):
      foreach ( $w3all_last_posts_users as $post_uname ) :
       $p_unames[] = $post_uname->user_email;
      endforeach;
    endif;
 endif;

  $w3_un_results = array_unique($p_unames);
  $query_un ='';

   foreach($w3_un_results as $w3_unames_ava){
     $query_un .= '\''.$w3_unames_ava.'\',';
    }

 $query_un = substr($query_un, 0, -1);

 self::w3all_get_phpbb_avatars_url($query_un);

 if (!empty($w3all_u_ava_urls)){

    foreach( $w3all_u_ava_urls as $ava_set_x ){

  if($ava_set_x['puid'] == 2){ // switch if install admins (uid 1 WP - uid 2 phpBB) have different usernames
    $usid = get_user_by('ID', 1);
   } else { $usid = get_user_by('email', $ava_set_x['uemail']); }

      if($usid && $usid->ID > 0):
        $wp_user_phpbb_avatar[] = array("wpuserlogin" => $usid->user_login, "wpuid" => $usid->ID, "phpbbavaurl" => $ava_set_x['uavaurl'], "phpbbuid" => $ava_set_x['puid']);
      else:
      $usid_user_login = 'Guest';
        $wp_user_phpbb_avatar[] = array("wpuserlogin" => $usid_user_login, "wpuid" => 0, "phpbbavaurl" => $ava_set_x['uavaurl'], "phpbbuid" => $ava_set_x['puid']);
      endif;
  }
 } else { $w3all_u_ava_urls = array(); }

  $wp_userphpbbavatar = (isset($wp_user_phpbb_avatar)) ? $wp_user_phpbb_avatar : $w3all_u_ava_urls;
  $u_a = serialize($wp_userphpbbavatar);

  if(!defined("W3ALLPHPBBUAVA")){
   define("W3ALLPHPBBUAVA", $u_a);
  }
  if(defined("W3ALLFORUMSIDSSHORT") && !defined("W3ALLPHPBBUAVAADDSHORTUSERS")){
    define("W3ALLPHPBBUAVAADDSHORTUSERS", $u_a);
  }
  return $wp_userphpbbavatar;

}


public static function w3all_get_phpbb_avatars_url( $w3unames ) {

  global $w3all_phpbb_connection,$phpbb_config,$w3all_config, $w3all_avatar_via_phpbb_file,$w3all_url_to_cms,$w3all_u_ava_urls;

    if(empty($phpbb_config)){
     self::w3all_get_phpbb_config();
    }

    if(empty($phpbb_config)){
     return;
    }

   $w3unames = strtolower($w3unames);
   $uavatars = $w3all_phpbb_connection->get_results( "SELECT user_id, username, user_email, user_avatar, user_avatar_type FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(user_email) IN(".$w3unames.") ORDER BY user_id DESC" );

  if(!empty($uavatars)){

    foreach($uavatars as $user_ava) {

     if(!empty($user_ava->user_avatar)){ // has been selected above by the way, check it need to be added

        if ( $user_ava->user_avatar_type == 'avatar.driver.local' ){

          $phpbb_avatar_url = $w3all_url_to_cms . '/' . $phpbb_config["avatar_gallery_path"] . '/' . $user_ava->user_avatar;
          $u_a[] = array("puid" => $user_ava->user_id, "uname" => $user_ava->username, "uemail" => $user_ava->user_email, "uavaurl" => $phpbb_avatar_url);

        }  elseif ( $user_ava->user_avatar_type == 'avatar.driver.remote' ){
          $phpbb_avatar_url = $user_ava->user_avatar;
          $u_a[] = array("puid" => $user_ava->user_id, "uname" => $user_ava->username, "uemail" => $user_ava->user_email, "uavaurl" => $phpbb_avatar_url);

        } else {
           $avatar_entry = $user_ava->user_avatar;
            $ext = substr(strrchr($avatar_entry, '.'), 1);

           $avatar_entry = strtok($avatar_entry, '_');
           $phpbb_avatar_filename = $phpbb_config["avatar_salt"] . '_' . $avatar_entry . '.' . $ext;
           $phpbb_avatar_url = $w3all_url_to_cms . "/download/file.php?avatar=" . $avatar_entry . '.' . $ext;

      // in phpBB there is Gravatar as option available as profile image
        $phpbb_avatar_url = is_email( $user_ava->user_avatar ) ? $user_ava->user_avatar : $phpbb_avatar_url;
        $u_a[] = array("puid" => $user_ava->user_id, "uname" => $user_ava->username, "uemail" => $user_ava->user_email, "uavaurl" => $phpbb_avatar_url);
      }
     }
    }
  } else { $u_a = ''; }
    $w3all_u_ava_urls = (empty($u_a)) ? '' : $u_a;
  return $w3all_u_ava_urls;
}


public static function wp_w3all_phpbb_custom_avatar( $avatar, $id_or_email, $size, $default, $alt ) {

  $uids_urls = unserialize(W3ALLPHPBBUAVA);

if(defined("W3ALLPHPBBUAVAADDSHORTUSERS")){
  $uids_urls = unserialize(W3ALLPHPBBUAVAADDSHORTUSERS);
}

    if ( is_numeric( $id_or_email ) ) {

        $id = (int) $id_or_email;
        $user = get_user_by( 'ID' , $id );

    } elseif ( is_object( $id_or_email ) ) {

        if ( ! empty( $id_or_email->user_id ) ) {
            $id = (int) $id_or_email->user_id;
            $user = get_user_by( 'ID' , $id );
        }

    } else {
        $user = get_user_by( 'email', $id_or_email );
    }

  if ( isset($user) && $user && is_object( $user ) ) {
     if (!empty($uids_urls)){

       foreach($uids_urls as $w3all_wupa) {
        if(isset($w3all_wupa["phpbbavaurl"])){
         //could be an email, get so gravatar url if the case
          if( is_email( $w3all_wupa["phpbbavaurl"] ) ) {
           $w3all_wupa["phpbbavaurl"] = get_avatar_url( $w3all_wupa["phpbbavaurl"] );
          }
          if ( $user->data->ID == $w3all_wupa["wpuid"] ) {
              $avatar = $w3all_wupa["phpbbavaurl"];
              $alt = $w3all_wupa["wpuserlogin"];
              $avatar = "<img alt='{$alt}' src='{$avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
         }
        }
       }
      }
 }

    return $avatar;
}

public static function init_w3all_avatars(){
  global $phpbb_config;

    if(empty($phpbb_config)){
     $phpbb_config = self::w3all_get_phpbb_config();
    }

  self::wp_w3all_assoc_phpbb_wp_users();
  // - Try to avoid avatars examples shown all the same, as it is the viewing admin avatar, in /wp-admin/options-discussion.php
  // in change this user when view the page /wp-admin/options-discussion.php, will not see his phpBB avatar on top admin bar ... that can be acceptable
  // - If the avatar result to be overwritten by some other plugin, then the exec value should be increased
  if ( ! empty($_SERVER['REQUEST_URI']) && ! strpos($_SERVER['REQUEST_URI'], 'options-discussion.php') ){
   add_filter( 'get_avatar', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_custom_avatar' ), 110000 , 5  );
  } elseif ( empty($_SERVER['REQUEST_URI']) ) {
    add_filter( 'get_avatar', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_custom_avatar' ), 110000 , 5  );
   }
}

//////////////////////////////////
// START avatars ONLY X BUDDYPRESS
//////////////////////////////////

// The call to this has been added into wp_w3all-php: add_filter( 'bp_core_fetch_avatar' ...

public static function w3all_bp_core_fetch_avatar( $bp_img_element, $params, $params_item_id, $params_avatar_dir, $html_css_id, $html_width, $html_height, $avatar_folder_url, $avatar_folder_dir ) {

    $uids_urls = unserialize(W3ALLPHPBBUAVA);

 if ( !empty($uids_urls) ){
    // assign phpBB avatar to this user, if there is one
    foreach($uids_urls as $w3all_wupa) {
      if( is_email( $w3all_wupa["phpbbavaurl"] ) ) {
           $w3all_wupa["phpbbavaurl"] = get_avatar_url( $w3all_wupa["phpbbavaurl"] );
          }
      if ( $params_item_id == $w3all_wupa["wpuid"] ) {
            $avatar_url = $w3all_wupa["phpbbavaurl"];
           $bp_img_element = '<img src="'.$avatar_url.'" class="'.$params["class"].'" width="'.$params["width"].'" height="'.$params["height"].'" alt="'.$params["alt"].'" />';
         }
     }

 }
  return $bp_img_element; // or let go with the one assigned by BP

}
/////////////////////////////////
// END avatars ONLY X BUDDYPRESS
/////////////////////////////////

//#######################
// END ABOUT AVATARS
//#######################

public static function w3all_get_phpbb_config_res() {

    $res = self::w3all_get_phpbb_config();
    return $res;
 }

public static function create_phpBB_user_res($wpu, $action = '') {

   self::create_phpBB_user($wpu, $action);
 }

public static function phpBB_user_session_set_res($wp_user_data){

   self::phpBB_user_session_set($wp_user_data);

}


public static function phpbb_pass_update_res($user, $new_pass){

      $res = self::phpbb_pass_update($user, $new_pass);
     return $res;
}


public static function last_forums_topics_res($ntopics){

      $topics_display = WP_w3all_phpbb::last_forums_topics($ntopics);
     return $topics_display;
}

public static function wp_w3all_phpbb_conn_init() {

       $w3db_conn = self::w3all_db_connect();
      return $w3db_conn;
  }

//############################################
// START PHPBB TO WP FUNCTIONS
//############################################

// wp_w3all custom -> phpBB get_unread_topics() ... this should fit any needs about users read/unread topics/posts.
// using it only for WP registered users, as option to be activated on w3all_config admin page. Retrieve read/unread posts in phpBB for WP current user
// $sql_limit = wp_w3all last topics numb to retrieve option
// original function in phpBB: get_unread_topics($username = false, $sql_extra = '', $sql_sort = '', $sql_limit = 1001, $sql_limit_offset = 0) // the phpBB function into functions.php file

public static function w3all_get_unread_topics($username = false, $sql_extra = '', $sql_sort = '', $sql_limit = 1001, $sql_limit_offset = 0)
{
  // if passed var $username is true, then this call is done by phpbb_last_topics_single_multi_shortcode.php
  // $username is used here so, to switch, and not execute
  // define( "W3UNREADTOPICS", $unread_topics );
  // or get Notice: Constant W3UNREADTOPICS already defined

  global $w3all_phpbb_connection,$w3all_config,$w3all_phpbb_usession,$w3all_wlastopicspost_max,$w3all_lasttopic_avatar_num,$phpbb_unread_topics;

    $user = wp_get_current_user();
    if ( $user->ID < 2 ){ return false; } // only for WP logged in users, and exclude WP UID 1

     $user_email = strtolower($user->user_email);

 if (!empty($w3all_phpbb_usession)) {
   $user_id = $w3all_phpbb_usession->user_id;
   $last_mark = $w3all_phpbb_usession->user_lastmark; // when/if the user have mark all as read
  } else {
     $phpbb_u = $w3all_phpbb_connection->get_row("SELECT * FROM ".$w3all_config["table_prefix"]."users WHERE LOWER(user_email) = '$user_email'") ;
   if(!empty($phpbb_u)){
    $user_id = $phpbb_u->user_id;
    $last_mark = $phpbb_u->user_lastmark; // when/if the user have mark all as read
   } else {
    return;
   }
  }

   // NOTE: guess an user have setup a value for 'Last Forums Topics number of users's avatars to retrieve' on wp_w3all config: if not, will search until 50 by default. If a widget need to display more than 50 posts and no avatar option is active, than this value need to be changed here directly, or setting up the option value for 'Last Forums Topics number of users's avatars to retrieve' even if avatars aren't used
    //$sql_limit = empty($w3all_lasttopic_avatar_num) ? 50 : $w3all_lasttopic_avatar_num;
    $sql_limit = $w3all_wlastopicspost_max > $w3all_lasttopic_avatar_num ? $w3all_wlastopicspost_max : $w3all_lasttopic_avatar_num;

  // Data array we're going to return
  $unread_topics = array();

  if (empty($sql_sort))
  {
    $sql_sort = "ORDER BY ".$w3all_config["table_prefix"]."topics.topic_last_post_time DESC, ".$w3all_config["table_prefix"]."topics.topic_last_post_id DESC";
  }

  //if ($config['load_db_lastread'] && $user->data['is_registered']) // wp_w3all config active or not, and user logged or not. At moment all this is not necessary:
  if($user_id > 1)
  {
    // Get list of the unread topics

   $w3all_exec_sql_array = $w3all_phpbb_connection->get_results("SELECT ".$w3all_config["table_prefix"]."topics.topic_id, ".$w3all_config["table_prefix"]."topics.topic_last_post_time, ".$w3all_config["table_prefix"]."topics_track.mark_time as topic_mark_time, ".$w3all_config["table_prefix"]."forums_track.mark_time as forum_mark_time
     FROM ".$w3all_config["table_prefix"]."topics
      LEFT JOIN ".$w3all_config["table_prefix"]."topics_track
        ON ".$w3all_config["table_prefix"]."topics_track.user_id = '".$user_id."'
       AND ".$w3all_config["table_prefix"]."topics.topic_id = ".$w3all_config["table_prefix"]."topics_track.topic_id
      LEFT JOIN ".$w3all_config["table_prefix"]."forums_track
        ON ".$w3all_config["table_prefix"]."forums_track.user_id = '".$user_id."' AND ".$w3all_config["table_prefix"]."topics.forum_id = ".$w3all_config["table_prefix"]."forums_track.forum_id
     WHERE ".$w3all_config["table_prefix"]."topics.topic_last_post_time > '".$last_mark."'
       AND (
        (".$w3all_config["table_prefix"]."topics_track.mark_time IS NOT NULL AND ".$w3all_config["table_prefix"]."topics.topic_last_post_time > ".$w3all_config["table_prefix"]."topics_track.mark_time) OR
        (".$w3all_config["table_prefix"]."topics_track.mark_time IS NULL AND ".$w3all_config["table_prefix"]."forums_track.mark_time IS NOT NULL AND ".$w3all_config["table_prefix"]."topics.topic_last_post_time > ".$w3all_config["table_prefix"]."forums_track.mark_time) OR
        (".$w3all_config["table_prefix"]."topics_track.mark_time IS NULL AND ".$w3all_config["table_prefix"]."forums_track.mark_time IS NULL)
        )
      $sql_sort LIMIT $sql_limit");

if(!empty($w3all_exec_sql_array)){
    foreach( $w3all_exec_sql_array as $k => $v ):
      $topic_id = $v->topic_id;
      $unread_topics[$topic_id] = ($v->topic_mark_time) ? (int) $v->topic_mark_time : (($v->forum_mark_time) ? (int) $v->forum_mark_time : $last_mark);
    endforeach;
  }

   if(empty($unread_topics) OR !is_array($unread_topics)){
      $unread_topics = array();
    }

    $phpbb_unread_topics = serialize($unread_topics);
    if($username == false){ // switch for the phpbb_last_topics_single_multi_shortcode.php: if true do not define, or get Notice: Constant W3UNREADTOPICS already defined as exlained on above note
     define( "W3UNREADTOPICS", $phpbb_unread_topics );
    }

    return $phpbb_unread_topics;
  }

  return false;
}

public static function w3all_phpbb_email_hash($email)
{
  global $w3all_user_email_hash;
  $w3all_user_email_hash = sprintf('%u', crc32(strtolower($email))) . strlen($email);
   return $w3all_user_email_hash;
}
// just used in /admin/ config.php? // to be removed
public static function w3all_db_connect_res(){
  return self::w3all_db_connect();
}

//############################################
// END PHPBB TO WP FUNCTIONS
//############################################

//############################################
// START X WP MS MU
//############################################

public static function create_phpBB_user_wpms_res($username, $user_email, $key, $meta, $user){

  self::create_phpBB_user_wpms($username, $user_email, $key, $meta, $user);
}

public static function wp_w3all_wp_after_pass_reset_msmu( $user ) {

  if(defined('WPW3ALL_PASSW_AUPADTED') OR !$user) { return; }

   global $w3all_config,$wpdb,$w3all_phpbb_connection;

  $upass = $user->user_pass;
  $uemail = strtolower($user->user_email);
  $res = $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_password = '".$upass."' WHERE LOWER(user_email) = '".$uemail."'");

}

private static function create_phpBB_user_wpms($username_id_object = '', $user_email = '', $key = '', $meta = '', $user = ''){

  global $w3all_user_email_hash,$w3all_phpbb_connection,$wpdb,$phpbb_config,$w3all_config,$w3all_phpbb_lang_switch_yn,$w3all_add_into_spec_group,$w3all_phpbb_user_deactivated_yn,$w3all_add_into_phpBB_after_confirm;

   if(empty($phpbb_config)){
     $phpbb_config = self::w3all_get_phpbb_config();
   }

   // skip, if 'create phpBB user after account confirmation' option enabled
   // if this option active, the user will be added into phpBB only after his first successful login
   // via 'wp_authenticate' -> w3_check_phpbb_profile_wpnu() <-> self::create_phpBB_user($user, 'add_u_phpbb_after_login')
   // avoid if admin action
   //if( $w3all_add_into_phpBB_after_confirm == 1 && $action != 'add_u_phpbb_after_login' && !current_user_can( 'create_users' ) )
   if( $w3all_add_into_phpBB_after_confirm == 1 && !current_user_can( 'create_users' ) )
    {
      return; // due to the above, if not admin, return here
    }

   if(!empty($user->user_login)){
    $username = $user->user_login;
    $user_email = $user->user_email;
   } elseif(is_object( $username_id_object )){
     $username = $user->user_login;
     $user_email = $user->user_email;
   } elseif(is_numeric($username_id_object)){
     $user = get_user_by( 'ID', $username_id_object );
     if(!empty($user)){
      $username = $user->user_login;
      $user_email = $user->user_email;
     }
   } else {
     $username = $username_id_object;
     $user_email = $user_email;
    }

   if( empty($username) OR !is_email($user_email) ){ // see wp_w3all.php // add_action( 'init', 'w3all_network_admin_actions' );
     return;
    }

   $u_exist = self::ck_phpbb_user($username, $user_email);

   if(!empty($u_exist)){
     temp_wp_w3_error_on_update();
    }

   $default_dateformat = $phpbb_config["default_dateformat"];
   $default_lang = $phpbb_config["default_lang"];
   $phpbb_version = substr($phpbb_config["version"], 0, 3);

  $phpbb_group = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."ranks
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
    goto this1; // break to the first found (seem to be the default phpBB behavior)
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
else { $rankID = 0; $group_color = ''; }

      if(!isset($default_lang) OR empty($default_lang)){ $wp_lang_x_phpbb = 'en'; }
       else { $wp_lang_x_phpbb = $default_lang; }

     // maybe to be added as option
     // setup gravatar by default into phpBB profile for the user when register in WP
     $uavatar = $avatype = '';
     //$uavatar = get_option('show_avatars') == 1 ? $wpu->user_email : '';
     //$avatype = (empty($uavatar)) ? '' : 'avatar.driver.gravatar';

     $username = esc_sql($username);
     $u = $phpbb_config["cookie_name"].'_u';

        if ( isset($_COOKIE[$u]) && preg_match('/[^0-9]/',$_COOKIE[$u]) ){
                die( "Clean up cookie on your browser please!" );
         }

     $phpbb_u = isset($_COOKIE[$u]) ? $_COOKIE[$u] : '';
      // only need to fire when user do not exist on phpBB already, and/or user is an admin that add an user manually
   if ( $phpbb_u < 2 OR !empty($phpbb_u) && current_user_can( 'manage_options' ) === true ) {

      $phpbb_user_type = $w3all_phpbb_user_deactivated_yn == 1 ? 1 : 0;

      //$phpbb_user_type = 0; // modified // set to 1 as deactivated on phpBB on WP MSMU except for admin action (but it is not what needed, since activation link set to user if admin action via https://localhost/wp5/wp-admin/network/user-new.php)

      if(current_user_can( 'create_users' )){
        $phpbb_user_type = 0;
      }

      self::w3all_phpbb_email_hash($user_email);

     if( !empty($user) && isset($user->user_pass) OR is_object( $username_id_object ) ){
        $wpup = $user->user_pass;
      } else {
       $wpup = substr(str_shuffle(md5(time()) . 'ABCKDEFGLJZ'), 0, rand(8,16)); // a temp pass to be updated after signup finished
       $wpup = password_hash($wpup, PASSWORD_BCRYPT,['cost' => 12]);
      }

      $wpur = time();
      $wpul = $username;

 if($key != 'is_admin_action'){
   // password fix
   // password of the just created WP user, when 'insert_user_meta' fire, may require reset to what need to be
      $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
      $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '".$wpup."' WHERE user_email = '".$user_email."'");
 }

    if( $key == 'is_admin_action' ){
        $wpup = $user->user_pass; // if admin action, add the pass of this user, it exist at this point in this case
      }
      $wpue = $user_email;
      $time = time();

      $wpunn = esc_sql(strtolower($wpul));
      $wpul  = esc_sql($wpul);

     // if added as newely registered user, the user need to be also added into Registered Group
     // and as user new 1 into users tab (to be correctly removed from newbie group when promoted based on posts)
      $user_new = 0;
      if($w3all_add_into_spec_group == 7){
        $w3all_add_into_spec_group = 2;
        $w3all_add_into_spec_group_def = true;
        $user_new = 1;
      }

     // phpBB < 3.3.0
     if($phpbb_version == '3.2'){
      $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_email_hash, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
         VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','','0','', '$wpur', '$wpul', '$wpunn', '$wpup', '0', '$wpue', '$w3all_user_email_hash', '', '', '', '', '', '', '0', '0', '0', '0', '0', '0', '0', '$wp_lang_x_phpbb', 'Europe/Rome', '$default_dateformat', '1', '$rankID', '$group_color', '0', '0', '0', '0', '-3', '0', '0', 't', 'd', 0, 't', 'a', '0', '1', '0', '1', '1', '1', '1', '230271', '$uavatar', '$avatype', '0', '0', '', '', '', '', '', '', '', '$user_new', '0', '0')");
     }
     // phpBB 3.3.0 >
     if($phpbb_version == '3.3'){
      // $w3phpbb_conn->query("INSERT INTO ".$w3all_config["table_prefix"]."users (user_id, user_type, group_id, user_permissions, user_perm_from, user_ip, user_regdate, username, username_clean, user_password, user_passchg, user_email, user_birthday, user_lastvisit, user_lastmark, user_lastpost_time, user_lastpage, user_last_confirm_key, user_last_search, user_warnings, user_last_warning, user_login_attempts, user_inactive_reason, user_inactive_time, user_posts, user_lang, user_timezone, user_dateformat, user_style, user_rank, user_colour, user_new_privmsg, user_unread_privmsg, user_last_privmsg, user_message_rules, user_full_folder, user_emailtime, user_topic_show_days, user_topic_sortby_type, user_topic_sortby_dir, user_post_show_days, user_post_sortby_type, user_post_sortby_dir, user_notify, user_notify_pm, user_notify_type, user_allow_pm, user_allow_viewonline, user_allow_viewemail, user_allow_massemail, user_options, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, user_sig, user_sig_bbcode_uid, user_sig_bbcode_bitfield, user_jabber, user_actkey, reset_token, reset_token_expiration, user_newpasswd, user_form_salt, user_new, user_reminded, user_reminded_time)
      //   VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','','0','','$wpur','$wpul','$wpunn','$wpup','0','$wpue','','0','0','0','index.php','','0','0','0','0','0','0','0','$wp_lang_x_phpbb','','d M Y H:i','1','0','$group_color','0','0','0','0','-3','0','0','t','d','0','t','a','0','1','0','1','1','1','1','230271','$uavatar','$avatype','50','50','','','','','','','0','','','$user_new','0','0')");
          $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."users (user_id, user_type, group_id, user_regdate, username, username_clean, user_password, user_email, user_lang, user_colour, user_avatar, user_avatar_type, user_new)
           VALUES ('','$phpbb_user_type','$w3all_add_into_spec_group','$wpur','$wpul','$wpunn','$wpup','$wpue','$wp_lang_x_phpbb','$group_color','$uavatar','$avatype','$user_new')");
     }

       $phpBBlid = $w3all_phpbb_connection->insert_id; // memo: pass only assigned vars on queries using this, or will return null
       $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('$w3all_add_into_spec_group','$phpBBlid','0','0')");

       // if added as newely registered user, then the user need to be also added as newbie (group id 7)
      if(isset($w3all_add_into_spec_group_def)){ // add into 7, newely
       $w3all_phpbb_connection->query("INSERT INTO ".$w3all_config["table_prefix"]."user_group (group_id, user_id, group_leader, user_pending) VALUES ('7','$phpBBlid','0','0')");
      }

       $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."config SET config_value = config_value + 1 WHERE config_name = 'num_users'");

       $newest_member = $w3all_phpbb_connection->get_results("SELECT * FROM ".$w3all_config["table_prefix"]."users WHERE user_id = (SELECT Max(user_id) FROM ".$w3all_config["table_prefix"]."users) AND group_id != '6'");
       $uname = $newest_member[0]->username;
       $uid   = $newest_member[0]->user_id;

     $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."config SET config_value = '$wpul' WHERE config_name = 'newest_username'");
     $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."config SET config_value = '$uid' WHERE config_name = 'newest_user_id'");

 }

}

//############################################
// END X WP MS MU
//############################################

} // END class WP_w3all_phpbb
