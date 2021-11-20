<?php
/**
 * @package wp_w3all
 */
/*
Plugin Name: WordPress w3all phpBB integration
Plugin URI: http://axew3.com/w3
Description: Integration plugin between WordPress and phpBB. It provide free integration - users transfer/login/register. Easy, light, secure, powerful
Version: 2.4.4
Author: axew3
Author URI: http://www.axew3.com/w3
License: GPLv2 or later
Text Domain: wp-w3all-phpbb-integration
Domain Path: /languages/

=====================================================================================
Copyright (C) 2021 - axew3.com
=====================================================================================
*/

// Security
defined( 'ABSPATH' ) or die( 'forbidden' );
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

if ( defined( 'W3PHPBBUSESSION' ) OR defined( 'W3PHPBBLASTOPICS' ) OR defined( 'W3PHPBBCONFIG' ) OR defined( 'W3UNREADTOPICS' ) OR defined( 'W3ALLPHPBBUAVA' ) OR defined("W3BANCKEXEC") ):
	die( 'Forbidden, something goes wrong' );
endif;

define( 'WPW3ALL_VERSION', '2.4.4' );
define( 'WPW3ALL_MINIMUM_WP_VERSION', '5.0' );
define( 'WPW3ALL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPW3ALL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); 

// Set the integration as 'Not Linked Users'
if( get_option('w3all_not_link_phpbb_wp') == 1 ){
 define('WPW3ALL_NOT_ULINKED', true);
}  
 
// FORCE Deactivation WP_w3all plugin //
// $w3deactivate_wp_w3all_plugin = 'true';

// FORCE the reset of cookie domain
// $w3reset_cookie_domain = '.mydomain.com'; 

$w3all_w_lastopicspost_max = get_option( 'widget_wp_w3all_widget_last_topics' );
$config_avatars = get_option('w3all_conf_avatars');
$w3all_conf_pref = get_option('w3all_conf_pref');
$w3cookie_domain = get_option('w3all_phpbb_cookie');
$w3all_bruteblock_phpbbulist = empty(get_option('w3all_bruteblock_phpbbulist')) ? array() : get_option('w3all_bruteblock_phpbbulist');
$w3all_path_to_cms = get_option('w3all_path_to_cms');
$w3all_pass_hash_way = get_option('w3all_pass_hash_way'); // WP == 1
$wp_w3all_forum_folder_wp = get_option( 'w3all_forum_template_wppage' ); 
$w3all_url_to_cms = get_option( 'w3all_url_to_cms' );

$phpbb_on_template_iframe = get_option( 'w3all_iframe_phpbb_link_yn' ); // old way: leave this for custom files compatibility
// changed: the name of the option should also change
$w3all_iframe_phpbb_link = unserialize(get_option('w3all_conf_pref_template_embed_link'));
$w3all_iframe_phpbb_link_yn = isset($w3all_iframe_phpbb_link["w3all_iframe_phpbb_link_yn"]) ? $w3all_iframe_phpbb_link["w3all_iframe_phpbb_link_yn"] : 0;
$w3all_iframe_custom_w3fancyurl = isset($w3all_iframe_phpbb_link["w3all_iframe_custom_w3fancyurl"]) ? $w3all_iframe_phpbb_link["w3all_iframe_custom_w3fancyurl"] : 'w3';
$w3all_iframe_custom_top_gap = isset($w3all_iframe_phpbb_link["w3all_iframe_custom_top_gap"]) ? intval($w3all_iframe_phpbb_link["w3all_iframe_custom_top_gap"]) : '100';

if(isset($w3reset_cookie_domain)){
	update_option( 'w3all_phpbb_cookie', $w3reset_cookie_domain );
	$w3cookie_domain = $w3reset_cookie_domain;
}

   $useragent = (!empty($_SERVER['HTTP_USER_AGENT'])) ? esc_sql(trim($_SERVER['HTTP_USER_AGENT'])) : 'unknown';
   $w3all_config_avatars = unserialize($config_avatars);
   $w3all_get_phpbb_avatar_yn = isset($w3all_config_avatars['w3all_get_phpbb_avatar_yn']) ? $w3all_config_avatars['w3all_get_phpbb_avatar_yn'] : '';
   $w3all_last_t_avatar_yn = isset($w3all_config_avatars['w3all_avatar_on_last_t_yn']) ? $w3all_config_avatars['w3all_avatar_on_last_t_yn'] : '';
   $w3all_last_t_avatar_dim = isset($w3all_config_avatars['w3all_lasttopic_avatar_dim']) ? $w3all_config_avatars['w3all_lasttopic_avatar_dim'] : '';
   $w3all_lasttopic_avatar_num = isset($w3all_config_avatars['w3all_lasttopic_avatar_num']) ? $w3all_config_avatars['w3all_lasttopic_avatar_num'] : '';
   $w3all_avatar_replace_bp_yn = isset($w3all_config_avatars['w3all_avatar_replace_bp_yn']) ? $w3all_config_avatars['w3all_avatar_replace_bp_yn'] : '0'; // not used
   $w3all_avatar_via_phpbb_file = isset($w3all_config_avatars['w3all_avatar_via_phpbb_file_yn']) ? $w3all_config_avatars['w3all_avatar_via_phpbb_file_yn'] : 0;

   $w3all_conf_pref = empty(trim($w3all_conf_pref)) ? array() : unserialize($w3all_conf_pref);
   $w3all_transfer_phpbb_yn = isset($w3all_conf_pref['w3all_transfer_phpbb_yn']) ? $w3all_conf_pref['w3all_transfer_phpbb_yn'] : '';
   $w3all_phpbb_widget_mark_ru_yn = isset($w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn']) ? $w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn'] : '';
   $w3all_phpbb_widget_FA_mark_yn = isset($w3all_conf_pref['w3all_phpbb_widget_FA_mark_yn']) ? $w3all_conf_pref['w3all_phpbb_widget_FA_mark_yn'] : 0;
   
   $w3all_phpbb_user_deactivated_yn = isset($w3all_conf_pref['w3all_phpbb_user_deactivated_yn']) ? $w3all_conf_pref['w3all_phpbb_user_deactivated_yn'] : 0;
   $w3all_phpbb_wptoolbar_pm_yn = isset($w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn']) ? $w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn'] : '';  
   $w3all_exclude_phpbb_forums = isset($w3all_conf_pref['w3all_exclude_phpbb_forums']) ? $w3all_conf_pref['w3all_exclude_phpbb_forums'] : '';  
   $w3all_phpbb_lang_switch_yn = isset($w3all_conf_pref['w3all_phpbb_lang_switch_yn']) ? $w3all_conf_pref['w3all_phpbb_lang_switch_yn'] : 0;
   $w3all_get_topics_x_ugroup = isset($w3all_conf_pref['w3all_get_topics_x_ugroup']) ? $w3all_conf_pref['w3all_get_topics_x_ugroup'] : 0;
   $w3all_custom_output_files = isset($w3all_conf_pref['w3all_custom_output_files']) ? $w3all_conf_pref['w3all_custom_output_files'] : 0;
   $w3all_profile_sync_bp_yn = isset($w3all_conf_pref['w3all_profile_sync_bp_yn']) ? $w3all_conf_pref['w3all_profile_sync_bp_yn'] : 0;
   $w3all_add_into_spec_group = isset($w3all_conf_pref['w3all_add_into_spec_group']) ? $w3all_conf_pref['w3all_add_into_spec_group'] : 2;
   $w3all_wp_phpbb_lrl_links_switch_yn = isset($w3all_conf_pref['w3all_wp_phpbb_lrl_links_switch_yn']) ? $w3all_conf_pref['w3all_wp_phpbb_lrl_links_switch_yn'] : 0;
   $w3all_phpbb_mchat_get_opt_yn = isset($w3all_conf_pref['w3all_phpbb_mchat_get_opt_yn']) ? $w3all_conf_pref['w3all_phpbb_mchat_get_opt_yn'] : 0;
   $w3all_anti_brute_force_yn = isset($w3all_conf_pref['w3all_anti_brute_force_yn']) ? $w3all_conf_pref['w3all_anti_brute_force_yn'] : 1;
   $w3all_custom_iframe_yn = isset($w3all_conf_pref['w3all_custom_iframe_yn']) ? $w3all_conf_pref['w3all_custom_iframe_yn'] : 0;
   $w3all_add_into_wp_u_capability = isset($w3all_conf_pref['w3all_add_into_wp_u_capability']) ? $w3all_conf_pref['w3all_add_into_wp_u_capability'] : 'subscriber';
   $w3all_add_into_phpBB_after_confirm = isset($w3all_conf_pref['w3all_add_into_phpBB_after_confirm']) ? $w3all_conf_pref['w3all_add_into_phpBB_after_confirm'] : 0;
   $w3all_push_new_pass_into_phpbb = isset($w3all_conf_pref['w3all_push_new_pass_into_phpbb']) ? $w3all_conf_pref['w3all_push_new_pass_into_phpbb'] : 0;
 

   // to define W3PHPBBLASTOPICS when 'at MAX'
   // used on last_forums_topics() to set W3PHPBBLASTOPICS
   // then used to avoid more calls in case of multiple widgets (not x shortcode by forums ids)

   if(!empty($w3all_w_lastopicspost_max)){
     foreach ($w3all_w_lastopicspost_max as $row) {
        if(isset($row['topics_number'])){
        	$w3all_wlastopicspost_max[] = $row['topics_number'];
        }
     }
      $w3all_wlastopicspost_max = isset($w3all_wlastopicspost_max) && is_array($w3all_wlastopicspost_max) ? max($w3all_wlastopicspost_max) : 10;
    } else { $w3all_wlastopicspost_max = 10; }

if ( defined( 'WP_ADMIN' ) ) {

 function w3all_VAR_IF_U_CAN(){
 	
    if ( !current_user_can( 'manage_options' ) && isset( $_POST["w3all_conf"]["w3all_url_to_cms"]) OR !current_user_can( 'manage_options' ) && isset( $_POST["w3all_conf"]["w3all_path_to_cms"] ) ) {
    	die('<h3>forbidden</h3>');
    }
    
     if ( isset($_POST["w3all_conf"]["w3all_url_to_cms"]) ){
      $_POST["w3all_conf"]["w3all_url_to_cms"] = trim($_POST["w3all_conf"]["w3all_url_to_cms"]);
     }
     
     if ( isset($_POST["w3all_conf"]["w3all_path_to_cms"]) ){
     	register_uninstall_hook( __FILE__, array( 'WP_w3all_admin', 'clean_up_on_plugin_off' ) );
    	$_POST["w3all_conf"]["w3all_path_to_cms"] = trim($_POST["w3all_conf"]["w3all_path_to_cms"]);
    	$up_conf_w3all_url = admin_url() . 'options-general.php?page=wp-w3all-options';
	     wp_redirect( $up_conf_w3all_url );
    	$config_file = $_POST["w3all_conf"]["w3all_path_to_cms"] . '/config.php';  
       ob_start();
		    include_once( $config_file );
       ob_end_clean(); 
     }
  }
	 add_action( 'init', 'w3all_VAR_IF_U_CAN' );

	  if(!empty($w3all_path_to_cms)){   // or may will search for some config file elsewhere instead 
	  	
      $config_file = get_option( 'w3all_path_to_cms' ) . '/config.php';
      if (file_exists($config_file)) {
       ob_start();
		     include_once( $config_file );
       ob_end_clean();
      }
     }
 
  if ( defined('PHPBB_INSTALLED') && !isset($w3deactivate_wp_w3all_plugin) ){
 
   if ( defined('WP_W3ALL_MANUAL_CONFIG') ){
  	  $w3all_config = array('dbms' => $w3all_dbms, 'dbhost' => $w3all_dbhost, 'dbport' => $w3all_dbport, 'dbname' => $w3all_dbname, 'dbuser'   => $w3all_dbuser, 'dbpasswd' => $w3all_dbpasswd, 'table_prefix' => $w3all_table_prefix, 'acm_type' => $w3all_acm_type );    	
   } else { 
      $w3all_config = array('dbms' => $dbms, 'dbhost' => $dbhost, 'dbport' => $dbport, 'dbname' => $dbname, 'dbuser' => $dbuser, 'dbpasswd' => $dbpasswd, 'table_prefix' => $table_prefix, 'acm_type' => $acm_type );
     }      

      require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-phpbb.php' );
      add_action( 'init', array( 'WP_w3all_phpbb', 'w3all_get_phpbb_config_res'), 1); // before any other
  }
      
      require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-admin.php' );
		  require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all.widgets-phpbb.php' );	
	  	add_action( 'init', array( 'WP_w3all_admin', 'wp_w3all_init' ) );
	   
 if ( defined('PHPBB_INSTALLED') && !isset($w3deactivate_wp_w3all_plugin) ){
     
	    add_action( 'init', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_conn_init' ), 2 );
      add_action( 'init', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_init' ), 3 );
	   
    function wp_w3all_phpbb_registration_save( $user_id ) {
  
     if ( is_multisite() OR defined('W3DISABLECKUINSERTRANSFER') ) { return; } // or get error on activating MUMS user ... msmu user will use a different way
     // the same transferring users from phpBB to Wp, the check in this case is done directly within the transfer process
     
      $wpu  = get_user_by('id', $user_id);
      
      if(!$wpu){ return; }
      
       $wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::phpBB_user_check($wpu->user_login, $wpu->user_email, 1);

        if($wp_w3_ck_phpbb_ue_exist === true){
         	if ( !function_exists( 'wp_delete_user' ) ) { 
           require_once ABSPATH . '/wp-admin/includes/user.php'; 
          } 
         wp_delete_user( $user_id ); // remove WP user just created, username or email exist on phpBB
          if(is_multisite() == true){
          	if ( !function_exists( 'wpmu_delete_user' ) ) { 
             require_once ABSPATH . '/wp-admin/includes/ms.php'; 
            } 
   	       wpmu_delete_user( $user_id );
   	      }
           temp_wp_w3_error_on_update();
           exit; 
        
        }
         
       if(!$wp_w3_ck_phpbb_ue_exist){
         $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_res($wpu);
       }
 }


function wp_w3all_up_phpbb_prof($user_id, $old_user_data) {

   $phpBB_upp = WP_w3all_phpbb::phpbb_update_profile($user_id, $old_user_data);

   $redirect_to = '';
    if($phpBB_upp === true && current_user_can( 'manage_options' )){
     	  $redirect_to = admin_url() . 'user-edit.php?user_id='.$user_id;
     }

     if($phpBB_upp === true){
      temp_wp_w3_error_on_update($redirect_to);
      exit;
     }
}
 
if(! defined("WPW3ALL_NOT_ULINKED")){ 

 add_action( 'user_profile_update_errors', 'w3all_user_profile_update_errors', 10, 1 ); 
 add_action( 'profile_update', 'wp_w3all_up_phpbb_prof', 10, 2 );
 add_action( 'user_register', 'wp_w3all_phpbb_registration_save', 10, 1 );
 add_action( 'delete_user', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_delete_user' ) );
 
 if(! defined("W3ALL_SESSION_ARELEASED") ){ 
  add_action( 'set_logged_in_cookie', 'wp_w3all_user_session_set', 10, 5 );
 }

 if(!empty($w3all_phpbb_wptoolbar_pm_yn)){
 add_action( 'admin_bar_menu', 'wp_w3all_toolbar_new_phpbbpm', 999 );  // notify about new phpBB pm
 }
}
 
function wp_w3all_user_session_set( $logged_in_cookie, $expire, $expiration, $user_id, $scheme ) {
	$user = get_user_by( 'ID', $user_id );
    $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($user); 
    return;
}

} // if defined phpbb installed end

} else { // not in admin

	// or will search for some config file elsewhere instead
	$w3all_path_to_cms = get_option( 'w3all_path_to_cms' );
	if(!empty($w3all_path_to_cms)){ 
   $config_file = get_option( 'w3all_path_to_cms' ) . '/config.php';
   if (file_exists($config_file)) {
     ob_start();
		  include_once( $config_file );
     ob_end_clean();       
    }
   }
    
  if ( defined('PHPBB_INSTALLED') && !isset($w3deactivate_wp_w3all_plugin) ){ 

  if ( defined('WP_W3ALL_MANUAL_CONFIG') ){
  	
  $w3all_config = array('dbms' => $w3all_dbms,'dbhost' => $w3all_dbhost,'dbport' => $w3all_dbport,'dbname' => $w3all_dbname,'dbuser' => $w3all_dbuser,'dbpasswd' => $w3all_dbpasswd,'table_prefix' => $w3all_table_prefix,'acm_type' => $w3all_acm_type);
      	
  } else { 

  $w3all_config = array('dbms' => $dbms,'dbhost' => $dbhost,'dbport' => $dbport,'dbname' => $dbname,'dbuser' => $dbuser,'dbpasswd' => $dbpasswd,'table_prefix' => $table_prefix,'acm_type' => $acm_type);
	
  }
  
	   require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-phpbb.php' ); 
     require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all.widgets-phpbb.php' );
  
      add_action( 'init', array( 'WP_w3all_phpbb', 'w3all_get_phpbb_config_res'), 1); // before any other wp_w3all
      add_action( 'init', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_init'), 2);
      
     if(!empty($w3all_phpbb_wptoolbar_pm_yn)){
      add_action( 'admin_bar_menu', 'wp_w3all_toolbar_new_phpbbpm', 999 );  // notify about new phpBB pm
     }


 function w3all_login_widget(){
 	
  $passed_uname = trim(stripcslashes($_POST['w3all_username']));
  
    if ( empty($passed_uname) ){
	      if(strpos($_POST['redirect_to'],'?')){
      wp_safe_redirect( $_POST['redirect_to'] . '&reauth=2' ); exit;
     } else { 
    	 wp_safe_redirect( $_POST['redirect_to'] . '?reauth=2' ); exit;
    	} 
         return;
     }
     
  global $wpdb,$w3all_anti_brute_force_yn,$w3all_bruteblock_phpbbulist,$w3cookie_domain,$w3all_add_into_wp_u_capability,$wp_w3all_forum_folder_wp,$w3all_push_new_pass_into_phpbb;
  $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
  $wpu_db_umtab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';

  $user = empty($passed_uname) ? array() : WP_w3all_phpbb::wp_w3all_get_phpbb_user_info($passed_uname);

///////////
// If option "force the password update into phpBB onlogin in wordpress" active 

 if( $w3all_push_new_pass_into_phpbb == 1 ){

  $wpuck = get_user_by( 'email', $user[0]->user_email );
  
   if( isset($user[0]->user_id) && isset($wpuck->user_pass) && $wpuck->user_pass != $user[0]->user_password && $user[0]->user_id > 2 )
   {
 	   $new_pass_push = $user[0]->user_password = $wpuck->user_pass;
     $qres = WP_w3all_phpbb::wp_w3all_update_phpBB_udata($user[0]->user_email, $wpuck->user_pass, $update="pass");
    }
  }

 if(isset($user[0])){

// mums allow only '[0-9A-Za-z]'
// default wp allow allow only [-0-9A-Za-z _.@] //  if( preg_match('/[^-0-9A-Za-z _.@]/',$phpbb_user[0]->username) ){
   $contains_cyrillic = (bool) preg_match('/[\p{Cyrillic}]/u', $user[0]->username);
  // if do not contain non latin chars, let wp create any wp user_login with this passed username
  if ( is_multisite() && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$user[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$user[0]->username) OR strlen($user[0]->username) > 50 ){
  //if ( is_multisite() && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$user[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$user[0]->username) OR strlen($user[0]->username) > 50 ){

  	if (!defined('WPW3ALL_NOT_ULINKED')){
  	 define('WPW3ALL_NOT_ULINKED', true);
  	}

      	if( isset($_SERVER['REQUEST_URI']) && !empty($wp_w3all_forum_folder_wp) && strstr($_SERVER['REQUEST_URI'], $wp_w3all_forum_folder_wp) ){
		     echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your forum username contains illegal characters not allowed in this system or contains more than 50 characters.<br />The forum cannot be displayed on this page.<br />Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
         exit;
        }	
        
        echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your forum username contains illegal characters not allowed in this system or contains more than 50 characters.<br />Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
        return;
  }
  
 if ( $user[0]->group_name == 'ADMINISTRATORS' ){
      	  $role = 'administrator';
      	} elseif ( $user[0]->group_name == 'GLOBAL_MODERATORS' ){
          $role = 'editor';
        } else { 
               	 $role = $w3all_add_into_wp_u_capability;
               	}

	 if(!empty($user)){ // add this phpBB user in Wp if still not existent
	 	 	$ck_wpu_exists = username_exists( $user[0]->username );
	 	 	$user_id = email_exists( $user[0]->user_email );

    if ( ! $user_id && ! $ck_wpu_exists ) {
              $userdata = array(
               'user_login'       =>  $user[0]->username,
               'user_pass'        =>  $user[0]->user_password,
               'user_email'       =>  $user[0]->user_email,
               'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $user[0]->user_regdate ),
               'role'             =>  $role,
               'nickname'         =>  $user[0]->username
               );
               
      $user_id = wp_insert_user( $userdata );
      
    if ( is_wp_error( $user_id ) ) {
    echo '<div style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><p>' . $user_id->get_error_message() . '</p></div>';
    echo __('<div><p style="padding:30px;background-color:#fff;color:#000;font-size:1.0em"><strong>Error: try to reload page, but if the error persist may mean that the forum\'s logged in username contains illegal characters that are not allowed on this system. Please contact an administrator.</strong></p></div>', 'wp-w3all-phpbb-integration');
    exit;
   }
         
      $phpbb_username = preg_replace( '/\s+/', ' ', $user[0]->username );
      $phpbb_username = esc_sql($phpbb_username);
      $user_username_clean = sanitize_user( $user[0]->username, $strict = false );
      $user_username_clean = esc_sql(mb_strtolower($user_username_clean,'UTF-8'));
      
     if ( ! is_wp_error( $user_id ) ) {
       if ($contains_cyrillic) {
          // update user_login and user_nicename and force to be what needed
          // also update the pass, since re-hashed by wp_insert_user()
          $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbb_username."', user_pass = '".$user[0]->user_password."', user_nicename = '".$user_username_clean."', display_name = '".$phpbb_username."' WHERE ID = ".$user_id."");
          $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
         } else { // leave as is (may cleaned and different) the just created user_login
        	  $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '".$user[0]->user_password."', display_name = '".$phpbb_username."' WHERE ID = '$user_id'");
    	      $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
          }
        
      } 
          
    }
	 } // END // add this phpBB user in Wp

	} // END // isset($user[0])
	 // if user just inserted, may at this point $wp_signon fail, despite valid passed credentials

	if( isset($user[0]) && $user_id > 1 ){
	  $pass_match = wp_check_password(trim($_POST['w3all_password']), $user[0]->user_password, $user_id);

    if( $pass_match ){
    	$remember = 1; // temp all remember
    	$user[0]->user_email = mb_strtolower($user[0]->user_email,'UTF-8');
      $wpu = $wpdb->get_row("SELECT * FROM $wpu_db_utab WHERE LOWER(user_email) = '".$user[0]->user_email."' OR ID = '".$user_id."'");
       if(empty($wpu)){
      	$wpu = get_user_by( 'ID', $user_id );
       }

        wp_set_current_user( $wpu->ID, $wpu->user_login );
        wp_set_auth_cookie( $wpu->ID, $remember, is_ssl() );
        do_action( 'wp_login', $wpu->user_login, $wpu );
       if(!defined("W3ALL_SESSION_ARELEASED")){
        $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($wpu); 
       }
      }
    } else {
	   $w3all_exec_u_login = wp_signon( array('user_login' => $_POST['w3all_username'], 'user_password' => trim($_POST['w3all_password']), 'remember' => 1), is_ssl() ); // remember = true -> lead to fail login
    }

  // signon fail
   if( isset($w3all_exec_u_login) && is_wp_error($w3all_exec_u_login) OR isset($pass_match) && !$pass_match ) { 
      if( $w3all_anti_brute_force_yn == 1 && isset($user[0]->user_id) ){
   	   $w3all_bruteblock_phpbbulist[$user[0]->user_id] = $user[0]->user_id;
   	   update_option( 'w3all_bruteblock_phpbbulist', $w3all_bruteblock_phpbbulist );
      }
    
     if(strpos($_POST['redirect_to'],'?')){
     	$_POST['redirect_to'] = str_replace(chr(0), '', $_POST['redirect_to']);
      wp_safe_redirect( $_POST['redirect_to'] . '&reauth=1' ); exit;
     } else { 
    	 wp_safe_redirect( $_POST['redirect_to'] . '?reauth=1' ); exit;
    	}
    	
   } else { // signon success
    if(isset($w3all_exec_u_login->data->user_login)){
    	wp_set_current_user( $w3all_exec_u_login->data->ID, $w3all_exec_u_login->data->user_login );
    	// Bruteforce phpBB session keys Prevention reset
    	
        if( isset($user[0]) ){
      		unset($w3all_bruteblock_phpbbulist[$user[0]->user_id]);
      		$w3all_bruteblock_phpbbulist = empty($w3all_bruteblock_phpbbulist) ? '' : $w3all_bruteblock_phpbbulist; // assure an empty array stored
          // maintain healty this array
          $tot = is_array($w3all_bruteblock_phpbbulist) ? count($w3all_bruteblock_phpbbulist) : '';
          if( $tot > 4000 && is_array($w3all_bruteblock_phpbbulist) ){
          	$w3all_bruteblock_phpbbulist = array_slice($w3all_bruteblock_phpbbulist, 100, $tot, true); // reduce of 100 removing olders
   	 	      update_option( 'w3all_bruteblock_phpbbulist', $w3all_bruteblock_phpbbulist );
          } else {
           update_option( 'w3all_bruteblock_phpbbulist', $w3all_bruteblock_phpbbulist );
          }
      	}
      	 
       // Remove cookie that fire wp_login block msg if it exist
        setcookie ("w3all_bruteblock", "", time() - 2592000, "/", "$w3cookie_domain");
     }
    }

     wp_safe_redirect( $_POST['redirect_to'] ); exit;
 }

// See step Bruteforce 'phpBB session keys Prevention check' for this, into class.wp.w3all-phpbb.php
if(isset($_COOKIE["w3all_bruteblock"]) && $_COOKIE["w3all_bruteblock"] > 0){  
  function w3all_bruteblock_login_message( $message ) { 
     return __('<strong>Notice: account Locked<br />Please re-login!</strong><br />Account logged out due to detected brute force attack against session or due to mismatching session<br /><strong>To fix the problem, please login now here!</strong>', 'wp-w3all-phpbb-integration');
  }
  add_filter( 'login_message', 'w3all_bruteblock_login_message', 10, 1 );
  setcookie ("w3all_bruteblock", "", time() - 2592000, "/", "$w3cookie_domain");
}

if(isset($_COOKIE["w3all_set_cmsg"]) && !empty($_COOKIE["w3all_set_cmsg"])){
	  
  function w3all_msgs( $message ) { 
  	global $w3cookie_domain;
  	if(trim($_COOKIE["w3all_set_cmsg"]) == 'phpbb_ban'){
     return __('Notice: your username, IP or email is currently banned into our forum. Please contact an administrator.', 'wp-w3all-phpbb-integration');
    }
    if(trim($_COOKIE["w3all_set_cmsg"]) == 'phpbb_deactivated'){
     return __('Notice: the specified username is currently inactive into our forum. Please contact an administrator.', 'wp-w3all-phpbb-integration');
    }
    if(trim($_COOKIE["w3all_set_cmsg"]) == 'phpbb_uname_chars_error'){
     return __('Notice: the specified username contains characters not allowed in this system. Please contact an administrator.', 'wp-w3all-phpbb-integration');
    }
    if(trim($_COOKIE["w3all_set_cmsg"]) == 'phpbb_sess_brutef_error'){
     return __('Notice: mismatching session OR bruteforce login detected. Please login here again to unlock your account.', 'wp-w3all-phpbb-integration');
    }  
  }
  add_filter( 'login_message', 'w3all_msgs', 10, 1 );   
    // Remove/empty cookie 
   setcookie ("w3all_set_cmsg", "", time() - 2592000, "/", "$w3cookie_domain");

}

function wp_w3all_check_fields($errors, $sanitized_user_login, $user_email) {

      global $wpdb;

       if( WP_w3all_phpbb::w3_phpbb_ban( $phpbb_u = '', $sanitized_user_login, $user_email ) === true ){
        $errors->add( 'w3all_user_banned', __( '<strong>Error</strong>: wrong email or the provided username, email or IP address result banned on our forum.', 'wp-w3all-phpbb-integration' ) );
        return $errors;
        } 
        
     $test = WP_w3all_phpbb::ck_phpbb_user_by_ue($sanitized_user_login, $user_email);
     
      if(!empty($test)){ 
      	 $errors->add( 'w3all_user_exist', __( '<strong>Error</strong>: provided email or username already exist on our forum database.', 'wp-w3all-phpbb-integration' ) );
         return $errors;
      }
   
      return $errors;
}

function wp_w3all_wp_after_password_reset($user, $new_pass) {
  $phpBB_user_pass_set = WP_w3all_phpbb::phpbb_pass_update_res($user, $new_pass); 
  $phpBB_user_activate = WP_w3all_phpbb::wp_w3all_wp_after_pass_reset($user); 
}


function wp_w3all_phpbb_registration_save2($user_id) {
	
     $wpu = get_user_by('ID', $user_id);
    if( empty( WP_w3all_phpbb::wp_w3all_get_phpbb_user_info($wpu->user_email )) ){
     $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_res($wpu);
    }
}
  
 function wp_w3all_phpbb_login($user_login, $user = '') {
   if( ! defined("W3ALL_SESSION_ARELEASED") && ! defined("PHPBBAUTHCOOKIEREL") ){ 
     $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($user);
     define("W3ALL_SESSION_ARELEASED", true);
   } 
 }
  
   function wp_w3all_up_wp_prof_on_phpbb($user_id, $old_user_data) {
    	
     $phpBB_user_up_prof_on_wp_prof_up = WP_w3all_phpbb::phpbb_update_profile($user_id, $old_user_data); 
     
             if($phpBB_user_up_prof_on_wp_prof_up === true){
         
        temp_wp_w3_error_on_update();
        exit;
      }
   }
   

function w3all_password_reset($user, $new_pass) { 
    $phpBB_user_pass_set = WP_w3all_phpbb::phpbb_pass_update_res($user, $new_pass); 
    $phpBB_user_activate = WP_w3all_phpbb::wp_w3all_wp_after_pass_reset($user);
}

if(! defined("WPW3ALL_NOT_ULINKED")){
	
	if(isset($_POST['w3all_username']) && isset($_POST['w3all_password'])){
   add_action( 'init', 'w3all_login_widget');
  }
	add_filter( 'auth_cookie_expiration', 'w3all_rememberme_long' );
	// this is not required since 2.4.0, because registrations allowed only in phpBB OR WP. Anyway leave it here to may check for problems
	add_filter( 'registration_errors', 'wp_w3all_check_fields', 10, 3 ); // this prevent any user addition (may not external plugins) if phpBB email or username already exist in phpBB, into default wordpress flavors
  add_action( 'user_register', 'wp_w3all_phpbb_registration_save2', 10, 1 );
  add_action( 'password_reset', 'wp_w3all_wp_after_password_reset', 10, 2 );
  // a phpBB user not logged into phpBB, WP login first time
  add_action( 'wp_authenticate', array( 'WP_w3all_phpbb', 'w3_check_phpbb_profile_wpnu' ), 10, 1 );
  add_action( 'wp_logout', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_logout' ) );
  add_action( 'profile_update', 'wp_w3all_up_wp_prof_on_phpbb', 10, 2 );
  add_action('wp_login', 'wp_w3all_phpbb_login', 10, 2);
  add_action( 'delete_user', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_delete_user' ) ); // x buddypress or ohers plugins that allow users to delete their own user's account on frontend profile
  add_action( 'user_profile_update_errors', 'w3all_user_profile_update_errors', 10, 1 );


} // END this -> if(! defined("WPW3ALL_NOT_ULINKED")){

if( $w3all_iframe_phpbb_link_yn == 1 ){
	function w3all_enq_jquery() { 
   wp_enqueue_script("jquery");
  }
 add_action('wp_enqueue_scripts', 'w3all_enq_jquery');
 add_action('wp_footer', 'w3all_iframe_href_switch');
}

function wp_w3all_add_phpbb_font_awesome(){
// retrieve css font awesome from phpBB 
 echo "<link rel=\"stylesheet\" href=\"" . get_option( 'w3all_url_to_cms' ) . "/assets/css/font-awesome.min.css\" />
";
}

function w3all_iframe_href_switch(){
 echo "<script type=\"text/javascript\">function w3allIframeHref(ids,res){ ids='#'+ids;jQuery(ids).attr('href',res); }</script>
";
}

  function phpbb_auth_login_url( $login_url, $redirect, $force_reauth ) {
   	
   	global $w3all_url_to_cms, $w3all_iframe_phpbb_link_yn, $wp_w3all_forum_folder_wp;
   	
    if( $w3all_iframe_phpbb_link_yn == 1 ){ 
    	
   	    $wp_w3all_forum_folder_wp = "index.php/" . $wp_w3all_forum_folder_wp;
   	   	$redirect = $wp_w3all_forum_folder_wp . '/?mode=login';
   	   return $redirect;
   	   	 
      } else { // lost pass no iframe
    	
   	           $redirect = $w3all_url_to_cms . '/ucp.php?mode=login';
               return $redirect;
             }
   }
  
  function phpbb_reset_pass_url( $lostpassword_url, $redirect ) {
   	
   	global $w3all_url_to_cms, $w3all_iframe_phpbb_link_yn, $wp_w3all_forum_folder_wp;
   	
    if( $w3all_iframe_phpbb_link_yn == 1 ){ // lost pass phpBB link iframe mode
    	
   	  $wp_w3all_forum_folder_wp = "index.php/" . $wp_w3all_forum_folder_wp;
   	  $redirect = $wp_w3all_forum_folder_wp . '/?mode=sendpassword';
   	   return $redirect;
   	   	 
     } else { // lost pass no iframe
   	       
   	    $redirect = $w3all_url_to_cms . '/ucp.php?mode=sendpassword';
          return $redirect;
       }
   }
   

    
 function phpbb_register_url( $register_url ) {
   global $w3all_url_to_cms, $w3all_iframe_phpbb_link_yn, $wp_w3all_forum_folder_wp;
   	
  if( $w3all_iframe_phpbb_link_yn == 1 ){ 
   $wp_w3all_forum_folder_wp = "index.php/" . $wp_w3all_forum_folder_wp;
   $redirect = $wp_w3all_forum_folder_wp . '/?mode=register';
   	return $redirect;
  } else { // register no iframe, direct link to phpBB
     $redirect = $w3all_url_to_cms . '/ucp.php?mode=register';
      return $redirect;
    }

 }

function w3all_rememberme_long($expire) { // Set remember me wp cookie to expire in one year
    return 31536000; // YEAR_IN_SECONDS;
   }   

   } // end PHPBB_INSTALLED
} // end not in admin

 if ( defined('PHPBB_INSTALLED') && !isset($w3deactivate_wp_w3all_plugin) ){

  if ( $w3all_phpbb_widget_mark_ru_yn == 1 ) {
   add_action( 'init', array( 'WP_w3all_phpbb', 'w3all_get_unread_topics'), 9);
    if( $w3all_phpbb_widget_FA_mark_yn == 1 ){
     add_action('wp_head','wp_w3all_add_phpbb_font_awesome');
    }
  }
// get all phpBB user capabilities
// TODO: put this into main user query, on class.wp.w3all-phpbb.php
if( $w3all_phpbb_mchat_get_opt_yn > 0 ){
   add_action('wp_head','wp_w3all_add_custom_js_css');
}


function w3all_user_profile_update_errors( $array ) {

 // note that this do not work on frontend plugin like on mepr
 // if a plugin do not let fire native wordpress hooks, then something like this will never fire
 // can be added custom forcing, via an init hook that could check for $_POST vars and enqueue if necessary

 // check for duplicated emails into phpBB
 // Note that this fire after user's email change request fired, if update done on wp profile: so remove and return error, if email update occur
 // and return error any time, any wp profile field updated, like password, if more than one email records found into phpBB. Or the update will occur for all those users with same email in phpBB
  
 // if there are errors already, there is no need to follow here
 // It should be, since user's email updates allowed only on wp or phpbb, then duplicated email error
 // will be thrown by wordpress earlier, so there should be no need to follow here
  if(!empty($array->errors) OR !empty($array->error_data)){
   return;
  }
  
  global $wpdb;
    $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
    // $_POST['user_id'] = attempted updated id
    $wpu = get_user_by('id', intval($_POST['user_id']));
    
  if( current_user_can( 'create_users' ) && $_POST['action'] == 'update' && $_POST['user_id'] > 1 ){

  	$phpbb_u = WP_w3all_phpbb::ck_phpbb_user( $wpu->user_login, $wpu->user_email );

  if( !empty($phpbb_u) && count($phpbb_u) > 1 ){ // for mismatching username integration
  //if(!empty($phpbb_u)){
  	    $array->add( 'w3_ck_phpbb_duplicated_email_error', __( '<strong>Error</strong>: email is paired to another username into our forum. The email update has been rejected.<br /><br />If you\'re running phpBB integration with mismatching usernames/email pairs, change email for this user into phpBB (and remember that until this user will not visit WordPress as logged in, his profile will not be updated with the new email', 'wp-w3all-phpbb-integration' ) );
   }
  }
  	
  if(isset($_POST['email']) && isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['user_id']))
  {
  	
  	// if update done by user into wp admin these contains
  	// $_REQUEST['email'] // contain attempted updated email
  	// $_POST['email'] // contain old email
  	// if update done by admins, both $_REQUEST['email'] AND $_POST['email'] contains attempted updated email

  if( !empty($wpu) )
   {
    $phpbb_u = WP_w3all_phpbb::ck_phpbb_user( $wpuser = '', $wpu->user_email );
    
    if( !empty($phpbb_u) && count($phpbb_u) > 1 ){ // for mismatching username integration
    //if( !empty($phpbb_u) ){
     delete_user_meta($wpu->ID, '_new_email'); // remove new email change request
     $array->add( 'w3_ck_phpbb_duplicated_email_error', __( '<strong>Error</strong>: email is paired to another username into our forum. The email update has been rejected.', 'wp-w3all-phpbb-integration' ) );
    }
   }
  }
  
}

function wp_w3all_add_custom_js_css() {
	global $w3all_custom_output_files,$wp_w3all_forum_folder_wp;
 if(is_page( $wp_w3all_forum_folder_wp )){ // avoid on page-forum? maybe yes maybe not: you need more options to check against in case, to make the joke work in any forum page situation. So in the while this is it
	return;
 }
echo '<script type="text/javascript" src="'.plugins_url().'/wp-w3all-phpbb-integration/addons/resizer/iframeResizer.min.js"></script>';
if( $w3all_custom_output_files == 1 ) { // custom file
	 include_once( ABSPATH . 'wp-content/plugins/wp-w3all-config/custom_js_css.php' );
} else { // default plugin file
	 include_once( WPW3ALL_PLUGIN_DIR . '/addons/custom_js_css.php' );
 }
}

// Swap WordPress default Login, Register and Lost Password links
if( $w3all_wp_phpbb_lrl_links_switch_yn > 0 ){
// this affect the lost password url on WP  
 add_filter( 'lostpassword_url', 'phpbb_reset_pass_url', 10, 2 ); 
// this affect the register url on WP
 add_filter( 'register_url', 'phpbb_register_url', 10, 1); 
// this affect the login url on WP
// try to avoid if direct call to wp-admin directly: in this case if option "Membership -> Anyone can register" is set to NO, this will return:
// Warning: call_user_func_array() expects parameter 1 to be a valid callback, function 'phpbb_auth_login_url' not found or invalid function name /wp-includes/class-wp-hook.php on line 288 
if(strpos($_SERVER['SCRIPT_NAME'],'wp-admin') === false){
	// removed, since to unlock a bruteforced login, the user need to login on wp side
	// and bruteforce login, become active by default, if not intentionally disabled
	//add_filter( 'login_url', 'phpbb_auth_login_url', 10, 3 );
}

}

if(! is_admin()){
add_shortcode( 'w3allphpbbupm', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_upm_short' ) );
add_shortcode( 'w3allforumpost', array( 'WP_w3all_phpbb', 'wp_w3all_get_phpbb_post_short' ) );
add_shortcode( 'w3allastopics', array( 'WP_w3all_phpbb', 'wp_w3all_get_phpbb_lastopics_short' ) );
add_shortcode( 'w3allastopicforumsids', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_last_topics_single_multi_fp_short' ) );
// the query inside the function search all latest updated topics that contains ALMOST an attach and will return only the older (so the first, time based) inserted attachment that belong to the topic
add_shortcode( 'w3allastopicswithimage', array( 'WP_w3all_phpbb', 'wp_w3all_get_phpbb_lastopics_short_wi' ) );
if($w3all_phpbb_mchat_get_opt_yn == 1){
add_shortcode( 'w3allphpbbmchat', array( 'WP_w3all_phpbb', 'wp_w3all_get_phpbb_mchat_short' ) );
}
if( $w3all_custom_iframe_yn == 1 ){
  add_shortcode( 'w3allcustomiframe', array( 'WP_w3all_phpbb', 'wp_w3all_custom_iframe_short' ) );
  // do not re-add the iframe lib if on page-forum.php and if not possible to check add by the way 
   if ( ! empty($_SERVER['REQUEST_URI']) && ! strpos($_SERVER['REQUEST_URI'], $wp_w3all_forum_folder_wp ) OR empty($_SERVER['REQUEST_URI']) ){
    add_action('wp_head', array( 'WP_w3all_phpbb', 'wp_w3all_add_iframeResizer_lib' ) );
   }
}

}

function w3all_edit_profile_url( $url, $user_id, $scheme ) {
   	
   	global $w3all_url_to_cms, $w3all_iframe_phpbb_link_yn, $wp_w3all_forum_folder_wp;
   	
    if( $w3all_iframe_phpbb_link_yn == 1 ){
   	    $wp_w3all_forum_folder_wp = "index.php/" . $wp_w3all_forum_folder_wp;
   	   	$url = $wp_w3all_forum_folder_wp . '/?ucp.php?i=ucp_profile&amp;mode=profile_info';
      } else { // lost pass no iframe
   	           $url = $w3all_url_to_cms . '/ucp.php?i=ucp_profile&amp;mode=profile_info';
             }

  return $url; 
}

// signup common check
if(! defined("WPW3ALL_NOT_ULINKED")){
add_filter( 'validate_username', 'w3all_on_signup_check', 1, 2 ); // give precedence, prevent others to add a signup not needed
add_action( 'init', 'w3all_add_phpbb_user' );
}

 function w3all_on_signup_check( $valid, $username ) { 

  		if( isset($_POST['signup_username']) && isset($_POST['signup_email']) OR isset($_POST['username']) && isset($_POST['email']) ){
  			 $username = isset($_POST['signup_username']) ? $_POST['signup_username'] : $_POST['username'];
  			 $email = isset($_POST['signup_email']) ? $_POST['signup_email'] : $_POST['email'];
  			  $username = trim( $username ); 
  			  $email = sanitize_email($email);
  			  if ( !is_email( $email ) ) {
          echo $message = __( '<h3>Error: email address not valid.</h3><br />', 'wp-w3all-phpbb-integration' ) . '<h4><a href="'.get_edit_user_link().'">' . __( 'Return back', 'wp-w3all-phpbb-integration' ) . '</a><h4>';
           return false;
         }
         
         $wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::ck_phpbb_user($username, $email);

         if(!empty($wp_w3_ck_phpbb_ue_exist)){
         	  temp_wp_w3_error_on_update();
          return false;
         }  
  		}
    //$valid=0;
  	return $valid; 	
 }


 function temp_wp_w3_error_on_update($redirect_to = ''){

     	if(!empty($redirect_to) && current_user_can( 'manage_options' )){
     		echo $message = __( '<h3>Error: username or email already exist.</h3> The username and/or email address provided already exist, or is associated with another existing user account on our forum database<br />', 'wp-w3all-phpbb-integration' ) . '<h4><a href="'.$redirect_to.'">' . __( 'Please return back', 'wp-w3all-phpbb-integration' ) . '</a><h4>';
      } else {
	       echo $message = __( '<h3>Error: username or email already exist.</h3> The username and/or email address provided already exist, or is associated with another existing user account on our forum database<br />', 'wp-w3all-phpbb-integration' ) . '<h4><a href="'.get_edit_user_link().'">' . __( 'Please return back', 'wp-w3all-phpbb-integration' ) . '</a><h4>';
       }
     }
     
function wp_w3_error($redirect_to='', $message=''){
   if(empty($message)){
     		echo $message = __( '<h3>Error: username or email already exist</h3> Error: username or email already exist. The username and/or email address provided already exist, or is associated with another existing user account on our forum database<br /><br />', 'wp-w3all-phpbb-integration' );
      } 
}

 function wp_w3all_toolbar_new_phpbbpm( $wp_admin_bar ) {
		global $w3all_phpbb_wptoolbar_pm_yn,$w3all_iframe_phpbb_link_yn,$wp_w3all_forum_folder_wp,$w3all_url_to_cms;

	if ( defined("W3PHPBBUSESSION") && $w3all_phpbb_wptoolbar_pm_yn == 1 ) {
        $phpbb_user_session = unserialize(W3PHPBBUSESSION);
        if($phpbb_user_session[0]->user_unread_privmsg > 0){
        $hrefmode = $w3all_iframe_phpbb_link_yn == 1 ? get_home_url() . "/index.php/".$wp_w3all_forum_folder_wp.'/?i=pm&amp;folder=inbox">' : $w3all_url_to_cms.'/ucp.php?i=pm&amp;folder=inbox';
        $args_meta = array( 'class' => 'w3all_phpbb_pmn' );
        $args = array(
                'id'    => 'w3all_phpbb_pm', 
                'title' => __( 'You have ', 'wp-w3all-phpbb-integration' ) . $phpbb_user_session[0]->user_unread_privmsg . __( ' unread forum PM', 'wp-w3all-phpbb-integration' ),
                'href'  => $hrefmode,
                'meta' => $args_meta );

       $wp_admin_bar->add_node( $args );
       unset($phpbb_user_session);
     }
  } else { return false; }
}

//add_action('wp_head','wp_w3all_new_phpbbpm_wp_menu_item_push');
function wp_w3all_new_phpbbpm_wp_menu_item_push($elemID, $msg='') {
 global $w3all_custom_output_files, $w3all_iframe_phpbb_link_yn, $wp_w3all_forum_folder_wp, $w3all_url_to_cms;

if ( is_user_logged_in() ) {
 // NOTE: primary-menu OR THE ID of the UL that contain li menu items	
 $elemID = empty($elemID) ? 'menu-main1' : $elemID;

if ( defined("W3PHPBBUSESSION") ) {
 $phpbb_user_session = unserialize(W3PHPBBUSESSION);
   if($phpbb_user_session[0]->user_unread_privmsg > 0){
	
	if ($w3all_iframe_phpbb_link_yn > 0){
		$w3all_url_to_phpbb_ib = get_home_url() . "/" . $wp_w3all_forum_folder_wp . "/?i=pm&folder=inbox";
	} else {
	        $w3all_url_to_phpbb_ib = $w3all_url_to_cms . "/ucp.php?i=pm&folder=inbox";
         }
        
$s = "<script>
jQuery(document).ready(function($) {
 var msgs = '".__( 'You have ', 'wp-w3all-phpbb-integration' )."' + ".$phpbb_user_session[0]->user_unread_privmsg." + '".__( ' unread forum PM', 'wp-w3all-phpbb-integration' )."';
 jQuery('#".$elemID."').append('<li id=\"menu-item-99\" class=\"menu-item\"><a href=\"".$w3all_url_to_phpbb_ib."\">' + msgs + '</li>');
});

</script>
<style type=\"text/css\"></style>";
	echo $s;
	
   }
  }
 }
}

if ( $w3all_pass_hash_way < 1 ): // 0 -> phpBB hash // 1 -> WP hash

if ( ! function_exists( 'wp_hash_password' ) && ! defined("WPW3ALL_NOT_ULINKED") ) :

function wp_hash_password( $password ) {

// wp do not allow char \ on password
// phpBB allow \ char on password

/* 
 if (version_compare(PHP_VERSION, '7.2.0') >= 0 && $phpbb_version == '3.3') {
 // phpBB 3.3.0 add/support PASSWORD_ARGON2I / and PASSWORD_ARGON2ID
   $password = stripslashes(htmlspecialchars($password, ENT_COMPAT));
   $pass = password_hash($password, PASSWORD_ARGON2I);
 }
*/ 

 // if( !isset($pass) OR $pass === false ) {
		 $password = trim($password);
     $password = stripslashes(htmlspecialchars($password, ENT_COMPAT)); // " do not need to be converted
		 $pass = password_hash($password, PASSWORD_BCRYPT,['cost' => 12]); // phpBB min cost 12
 //	}

 return $pass;

}

endif;
endif;

if ( ! function_exists( 'wp_check_password' ) && ! defined("WPW3ALL_NOT_ULINKED") ) :

function wp_check_password($password, $hash, $user_id = '') {

// wp do not allow char \ on password
// phpBB allow \ char on password

   global $wpdb,$wp_hasher,$w3all_add_into_phpBB_after_confirm;
   $password = trim($password);
   //$password = str_replace(chr(0), '', $password);
   $check = false;
   $hash_x_wp = $hash;

     $wpu = get_user_by( 'ID', $user_id );
     $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
    if(empty($wpu)){
      $wpu = $wpdb->get_row("SELECT * FROM $wpu_db_utab WHERE ID = '".$user_id."'");
     }  
     
    if( empty($wpu) OR empty($password) OR empty($hash) ){
     	return;
    }

 if(!empty($wpu)){
 	
 	 $is_phpbb_admin = ( $user_id == 1 ) ? 1 : 0; // switch for phpBB admin // 1 admin 0 all others
   $changed = WP_w3all_phpbb::check_phpbb_passw_match_on_wp_auth($wpu->user_email, $is_phpbb_admin, $wpu);
	 if ( $changed !== false && $wpu->ID > 1 ){
      $hash = $changed;
    }

	 // If the hash still old md5
    if ( $hash != null && strlen($hash) <= 32 ) {
        $check = hash_equals( $hash, md5( $password ) );
     }

 // Argon2i and Argon2id password hash
 if( substr($hash, 0, 8) == '$argon2i' ){
 	$password = stripslashes(htmlspecialchars($password, ENT_COMPAT)); // " do not need to be converted
  $check = password_verify($password, $hash);
  $HArgon2i = true;
 }
 
 if ( !isset($check) OR $check !== true && !isset($HArgon2i) ){ // check the default Wp pass: md5 check failed or not fired above
	 if ( empty($wp_hasher) ) {
		require_once( ABSPATH . WPINC . '/class-phpass.php'); 	
		$wp_hasher = new PasswordHash(8, true); // 8 wp default
	 } 
    $check = $wp_hasher->CheckPassword($password, $hash_x_wp);
  }
  	 
 if ($check !== true && strlen($hash) > 32 && !isset($HArgon2i)){ // Wp check failed, check phpBB pass that's may not Argon2i 
    $password = stripslashes($password);
    $password = htmlspecialchars($password, ENT_COMPAT);
    $check = password_verify($password, $hash);
  }
  
     if ($check === true){
     	
///////////
// check that this user do not need to be added into phpBB, due to $w3all_add_into_phpBB_after_confirm
// that if active, do not let add the user in phpBB, into create_phpBB_user()      	
     	 if( $w3all_add_into_phpBB_after_confirm == 1 )
     	 {

     		$wp_uid_added_into_phpbb = WP_w3all_phpbb::create_phpBB_user_res($wpu, 'add_u_phpbb_after_login');
     	 }
     	 
///////////     	

     	  $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($wpu);
      } else {
           $check = false;
        }
	   return apply_filters( 'check_password', $check, $password, $hash, $user_id );
 } else {
     	return apply_filters( 'check_password', false, $password, $hash, $user_id );
     }
}

endif;


function wp_w3all_remove_bbcode_tags($post_str, $words){

 $post_string = preg_replace('/[[\/\!]*?[^\[\]]*?]/', '', $post_str);
 
 $post_string = strip_tags($post_string);
 
 $post_s = $post_string;
  
 $post_string = explode(' ',$post_string);

  if( count($post_string) < $words ) : return $post_s; endif;

 $post_std = ''; $i = 0; $b = $words;
 
  foreach ($post_string as $post_st) {
	
	  $i++;
	  if( $i < $b + 1 ){ // offset of 1

      $post_std .= $post_st . ' ';
    }
  }

 //$post_std = $post_std . ' ...'; // if should be a link to the post, do it on phpbb_last_topics

return $post_std;

}

/////////////////////////   
// W3ALL WPMS MU START
/////////////////////////

function w3all_wpmu_validate_user_signup( $result ){
 if( empty($result['errors']->errors) ){
 	// if no errors on wp side, then check into phpBB and enqueue error, if user/email found
 	// this fire before user signup, so to prevent wp user signup/addition and/or errors, if the user already exist into phpBB
 		$test =  WP_w3all_phpbb::ck_phpbb_user($result['user_name'], $result['user_email']);
  if( !empty($test) ){  
    $errA = array("user_email" => array( "0" => "Error: username or email already exist. The username and/or email address provided already exist, or is associated with another existing user account on our forum database"));
   $result['errors']->errors = $errA;
  } 
 }
   return $result; 
}

function w3all_wpmu_activate_user_phpbb( $user_id, $password, $meta='' ) { 
  	 global $w3all_config,$w3all_phpbb_user_deactivated_yn,$w3all_pass_hash_way;

	$w3db_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();
  $user = get_user_by('id', $user_id);
  
  if(empty($user)){ return; }
  
  $user->user_email = mb_strtolower($user->user_email,'UTF-8');
  $user_info = get_userdata($user->ID);
  $wp_user_role = implode(', ', $user_info->roles);

		$phpbb_user_data = WP_w3all_phpbb::wp_w3all_get_phpbb_user_info($user->user_email);
		
	 if ( $w3all_pass_hash_way < 1 ){ // phpBB pass hash
    $password = password_hash(trim($password), PASSWORD_BCRYPT,['cost' => 12]);
   } else {
    if ( empty( $wp_hasher ) ) { // wp default hash pass
      require_once ABSPATH . WPINC . '/class-phpass.php';
       // By default, use the portable hash from phpass.
      $wp_hasher = new PasswordHash( 8, true );
      $password = $wp_hasher->HashPassword( trim( $password ) );
     }
    }
    
		if ( isset($phpbb_user_data[0]) && $phpbb_user_data[0]->user_type == 1 ) {
		 	$res = $w3db_conn->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_password = '".$password."' WHERE LOWER(user_email) = '".$user->user_email."'");
    }
    
   if ( is_multisite() ){
       if ( !function_exists( 'get_current_blog_id' ) ) { 
        require_once ABSPATH . WPINC . '/load.php'; 
       } 
     
       if ( !function_exists( 'add_user_to_blog' ) ) { 
        require_once ABSPATH . WPINC . '/ms-functions.php'; 
       } 
      
      // this way add only to the current visited blog
       global $w3all_add_into_wp_u_capability;
       $role = $w3all_add_into_wp_u_capability; 
       $blogID = get_current_blog_id();  
       $result = add_user_to_blog($blogID, $user_id, $role); 
     /*
     // this way add user to all existent network blogs, until 100
     // 'if you specify null to the ‘network_id’ then all site infomation in the network are returned'
     // see https://developer.wordpress.org/reference/functions/wp_get_sites/
       $args = array(
        'network_id' => null,
        'public'     => null,
        'archived'   => null,
        'mature'     => null,
        'spam'       => null,
        'deleted'    => null,
        'limit'      => 100,
        'offset'     => 0,
        );
 
     $bs = get_sites( $args );
     foreach($bs as $b => $val){
	    $blog_ids[] = $val->blog_id;
     }
     foreach($blog_ids as $b){
	    $result = add_user_to_blog($b, $user_id, $role); 
     }
    */
    
   }
}
  
function w3all_wpmu_new_user_up_pass( $user_id ) {
  $wpu  = get_user_by('id', $user_id);
  $phpBB_u_activate = WP_w3all_phpbb::wp_w3all_wp_after_pass_reset_msmu($wpu); // msmu: the pass updated is the one of WP
} 
         
function w3all_wpmu_new_user_signup( $username, $user_email, $key, $meta = '' ) {
  $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_wpms_res( $username, $user_email, $key, $meta, $user = '' );
}

function w3all_wpmu_delete_user( $id ) { 
global $wpdb;
   WP_w3all_phpbb::wp_w3all_phpbb_delete_user_signup($id);
   // for compatibility, this delete will remove user from wp signup table
}

function w3all_remove_user_from_blog( $id, $blog_id ) { 
global $wpdb;
   WP_w3all_phpbb::wp_w3all_phpbb_delete_user_signup($id, $blog_id);
   // for compatibility, this delete will remove user from wp signup table  
}

function w3all_after_signup_site( $domain, $path, $title, $user, $user_email, $key, $meta='' ) { 
	  $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_wpms_res( $user, $user_email, $key, $meta );
}

function w3all_wpmu_new_blog( $data ) {
	  $user = wp_get_current_user(); 
    $phpBB_u_activate = WP_w3all_phpbb::wp_w3all_wp_after_pass_reset_msmu($user);
}

function w3all_wpmu_network_user_new_created_user( $user_id ) {
	 $user = get_user_by('id', $user_id);
   $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_wpms_res( $user->user_login, $user->user_email, $key='is_admin_action', $meta='', $user);
  }
  
function w3all_wpmu_new_user( $user_id ) {

	 $user = get_user_by('id', $user_id);
	 	$wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::phpBB_user_check($user->user_login, $user->user_email, 1);
  if($wp_w3_ck_phpbb_ue_exist === false){ 
  	// note that could be added a site for an existent user, 
  	//so an admin that add a site, should be sure to associate a correct site name, that will be assigned so by wp as a new username, and correct email, to pair with existent phpBB user (if exist)
   $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_wpms_res( $user->user_login, $user->user_email, $key='', $meta='', $user);
  }
}

if( is_multisite() && ! defined("WPW3ALL_NOT_ULINKED") ){  
// admin	  
add_action( 'init', 'w3all_network_admin_actions' );
function w3all_network_admin_actions() { 
 if ( defined( 'WP_ADMIN' ) && current_user_can( 'create_users' ) ){
//add_action( 'wp_insert_site', 'w3all_wpmu_new_blog_by_admin', 10, 6 ); 	// function removed
  add_action( 'network_user_new_created_user', 'w3all_wpmu_network_user_new_created_user', 10, 1 ); 
 }
}
// user with site registration
//add_action( 'wp_insert_site', 'w3all_wpmu_new_blog', 10, 6 ); 
//add_action( 'after_signup_site', 'w3all_after_signup_site', 10, 7 );
add_filter( 'wpmu_validate_user_signup', 'w3all_wpmu_validate_user_signup', 10, 1 );
// no site user registration
add_action( 'wpmu_delete_user', 'w3all_wpmu_delete_user', 10, 1 );
//add_action( 'after_signup_user', 'w3all_wpmu_new_user_signup', 10, 4 ); // see wp_w3all_phpbb_registration_save more above about this
add_action( 'wpmu_activate_user', 'w3all_wpmu_activate_user_phpbb', 10, 3 );
//add_action( 'wpmu_new_user', 'w3all_wpmu_new_user_up_pass', 10, 1 );
add_action( 'wpmu_new_user', 'w3all_wpmu_new_user', 10, 1 );
add_action( 'remove_user_from_blog', 'w3all_remove_user_from_blog', 10, 2 ); 

}

/////////////////////////   
// W3ALL WPMS MU END
/////////////////////////

/////////////////////////////////////
// BUDDYPRESS profile fields and avatars integration START
/////////////////////////////////////

// This is UPDATE when it is done into WP side profile
// while fields UPDATE - phpBB -> WP - is done into class.wp.w3all-phpbb.php -> function verify_phpbb_credentials()

if (function_exists('buddypress')) { // IF Buddypress installed ...
	
	// as explained on procedure, these four (4) arrays can be populated with more and different values 
	// https://www.axew3.com/w3/2017/09/wordpress-and-buddypress-phpbb-profile-fields-integration/
	// so check that they not have been added already into phpBB root config.php or custom WP_w3all config.php file
	
  if( $w3all_custom_output_files == 1 ) { // custom file
	 include_once( ABSPATH . 'wp-content/plugins/wp-w3all-config/buddypress_array_profile_fields.php' );
  } else { // default plugin file
	 include_once( ABSPATH . 'wp-content/plugins/wp-w3all-phpbb-integration/addons/wp-w3all-config/buddypress_array_profile_fields.php' );
  }

// The check about email/url is done in another way, so this aim to update/check only (existent and that match!) profile fields
function w3all_xprofile_updated_profile( $user_id, $posted_field_ids, $errors, $old_values, $new_values ) {
	   global $wpdb,$w3all_config;
 
  if(!empty($errors) OR !defined('W3PHPBBCONFIG') OR !defined('W3PHPBBUSESSION')){ 
 	 return; 
 	} 
    
  $us = unserialize(W3PHPBBUSESSION);
  if ($us[0]->user_id < 3){ 
  	return; 
  }
  
  $uid = $us[0]->user_id;

/*
$uf = '';	   
	foreach($posted_field_ids as $f){   
   $uf .= "'".$f."',";
 }
$uf = substr($uf, 0, -1);

 $uf = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."bp_xprofile_data, ".$wpdb->prefix ."bp_xprofile_fields, ".$wpdb->prefix ."usermeta 
  WHERE ".$wpdb->prefix."bp_xprofile_data.user_id = $user_id 
   AND ".$wpdb->prefix."bp_xprofile_data.field_id = ".$wpdb->prefix."bp_xprofile_fields.id 
   AND ".$wpdb->prefix."bp_xprofile_data.field_id IN(".$uf.") 
   AND ".$wpdb->prefix."usermeta.user_id = ".$wpdb->prefix."bp_xprofile_data.user_id  
   AND ".$wpdb->prefix ."usermeta.meta_key = 'bp_xprofile_visibility_levels'");
*/
   
   $uf = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."bp_xprofile_data, ".$wpdb->prefix ."bp_xprofile_fields, ".$wpdb->prefix ."usermeta 
    WHERE ".$wpdb->prefix."bp_xprofile_data.user_id = $user_id 
    AND ".$wpdb->prefix."bp_xprofile_data.field_id = ".$wpdb->prefix."bp_xprofile_fields.id 
    AND ".$wpdb->prefix."usermeta.user_id = ".$wpdb->prefix."bp_xprofile_data.user_id  
    AND ".$wpdb->prefix ."usermeta.meta_key = 'bp_xprofile_visibility_levels'");
    
 if(empty($uf)){ return; }

 $meta_val = unserialize($uf[0]->meta_value);
 global $w3_bpl_profile_occupation, $w3_bpl_profile_location, $w3_bpl_profile_interests, $w3_bpl_profile_website;
 // init vars as empty x phpBB (default) profile values
$w3_youtube = '';
$w3_googleplus = '';
$w3_skype = '';
$w3_twitter = '';
$w3_facebook = '';
$w3_yahoo = '';
$w3_icq = '';
$w3_aol = '';
$w3_interests = '';
$w3_occupation = '';
$w3_location = '';
$w3_website = '';

	 foreach( $uf as $uu => $ff ){ // check what of these are public fields and assign update values, ignore all the rest
      // update cases
      // Buddypress can return, membersonly, public, adminsonly
      // so, leave the assigned empty var above (so reset in phpBB to empty value) if the field is not set as Public by the user in Buddypress profile

     if ( stripos($ff->name, 'youtube' ) !== false && $meta_val[$ff->field_id] == 'public' ){  
       $w3_youtube = $ff->value;
      }
      elseif ( stripos($ff->name, 'google' ) !== false && $meta_val[$ff->field_id] == 'public' ){  
       $w3_googleplus = $ff->value;
      }
      elseif ( stripos($ff->name, 'skype' ) !== false && $meta_val[$ff->field_id] == 'public' ){ 
       $w3_skype = $ff->value;
      } 
      elseif ( stripos($ff->name, 'twitter' ) !== false  && $meta_val[$ff->field_id] == 'public' ){  
       $w3_twitter = $ff->value;
      } 
      elseif ( stripos($ff->name, 'facebook' ) !== false && $meta_val[$ff->field_id] == 'public' ){  
       $w3_facebook = $ff->value;
      } 
      elseif ( stripos($ff->name, 'yahoo' ) !== false && $meta_val[$ff->field_id] == 'public' ){  
       $w3_yahoo = $ff->value;
      }      
      elseif ( stripos($ff->name, 'icq' ) !== false && $meta_val[$ff->field_id] == 'public' ){ 
       $w3_icq = $ff->value;
      }     
      elseif ( stripos($ff->name, 'aol' ) !== false && $meta_val[$ff->field_id] == 'public' ){ 
       $w3_aol = $ff->value;
      } 
      elseif ( array_search(trim(mb_strtolower($ff->name, 'UTF-8')), $w3_bpl_profile_interests ) && $meta_val[$ff->field_id] == 'public' ){  
       $w3_interests = $ff->value;
      }   
      elseif ( array_search(trim(mb_strtolower($ff->name, 'UTF-8')), $w3_bpl_profile_occupation ) && $meta_val[$ff->field_id] == 'public' ){
       $w3_occupation = $ff->value;
      } 
      elseif ( array_search(trim(mb_strtolower($ff->name, 'UTF-8')), $w3_bpl_profile_location ) && $meta_val[$ff->field_id] == 'public' ){
       $w3_location = $ff->value;
      }       	           
      elseif ( array_search(trim(mb_strtolower($ff->name, 'UTF-8')), $w3_bpl_profile_website ) && $meta_val[$ff->field_id] == 'public' ){
       $w3_website = $ff->value;
      } 
      else { }    	      
      
 }
      	
  $wpu = get_user_by( 'ID', $user_id );

	 if( !$wpu ){ return; }
    
  // is this an admin updating/editing this user profile?
  // if current_user_can( 'manage_options' ) here, but add (role) in case if some other group on WP allow powered users to edit others users profiles
  // OR updating the profile of another user, the 'logged' WP user executing the update will be updated in phpBB, and not the needed passed $user_id ...

 $us[0]->user_email = mb_strtolower($us[0]->user_email,'UTF-8');
 $wpu->user_email = strtolower($wpu->user_email);

 if( current_user_can( 'manage_options' ) && $wpu->user_email != $us[0]->user_email ){
		$uid = $w3phpbb_conn->get_var("SELECT user_id FROM ".$phpbb_config_file["table_prefix"]."users WHERE LOWER(user_email) = '$wpu->user_email'");
   if ( $uid < 2 ) { return; }
 } 

     $phpbb_version = substr($phpbb_config["version"], 0, 3);
      
      // phpBB version 3.3>
  	  if( $phpbb_version == '3.3' ){
  	  	// TODO: add all 3.3> profile fields
  	    $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."profile_fields_data (user_id, pf_phpbb_interests, pf_phpbb_occupation, pf_phpbb_location, pf_phpbb_youtube, pf_phpbb_facebook, pf_phpbb_icq, pf_phpbb_skype, pf_phpbb_twitter, pf_phpbb_googleplus, pf_phpbb_website, pf_phpbb_yahoo, pf_phpbb_aol)
        VALUES ('$uid','$w3_interests','$w3_occupation','$w3_location','$w3_youtube','$w3_facebook','$w3_icq','$w3_skype','$w3_twitter','$w3_googleplus','$w3_website','$w3_yahoo','$w3_aol') ON DUPLICATE KEY UPDATE 
        pf_phpbb_interests = '$w3_interests', pf_phpbb_occupation = '$w3_occupation', pf_phpbb_location = '$w3_location', pf_phpbb_youtube = '$w3_youtube', pf_phpbb_facebook = '$w3_facebook', pf_phpbb_icq = '$w3_icq', pf_phpbb_skype = '$w3_skype', pf_phpbb_twitter = '$w3_twitter', pf_phpbb_googleplus = '$w3_googleplus', pf_phpbb_website = '$w3_website', pf_phpbb_yahoo = '$w3_yahoo', pf_phpbb_aol = '$w3_aol'");
      }
  	// phpBB version 3.2>
  	  if( $phpbb_version == '3.2' ){	
  	   $w3phpbb_conn->query("INSERT INTO ".$phpbb_config_file["table_prefix"]."profile_fields_data (user_id, pf_phpbb_interests, pf_phpbb_occupation, pf_phpbb_location, pf_phpbb_youtube, pf_phpbb_twitter, pf_phpbb_googleplus, pf_phpbb_skype, pf_phpbb_facebook, pf_phpbb_icq, pf_phpbb_website, pf_phpbb_yahoo, pf_phpbb_aol)
        VALUES ('$uid','$w3_interests','$w3_occupation','$w3_location','$w3_youtube','$w3_twitter','$w3_googleplus','$w3_skype','$w3_facebook','$w3_icq','$w3_website','$w3_yahoo','$w3_aol') ON DUPLICATE KEY UPDATE 
         pf_phpbb_interests = '$w3_interests', pf_phpbb_occupation = '$w3_occupation', pf_phpbb_location = '$w3_location', pf_phpbb_youtube = '$w3_youtube', pf_phpbb_twitter = '$w3_twitter', pf_phpbb_googleplus = '$w3_googleplus', pf_phpbb_skype = '$w3_skype', pf_phpbb_facebook = '$w3_facebook', pf_phpbb_icq = '$w3_icq', pf_phpbb_website = '$w3_website', pf_phpbb_yahoo = '$w3_yahoo', pf_phpbb_aol = '$w3_aol'");
      } 

}

// ... custom avatar URL for users, remote avatar need to be enabled in phpBB for this to work ...
function w3all_bp_members_avatar_uploaded( $item_id, $avatar_data_type,  $avatar_data ) { 
 	 global $w3all_config;
 	 
 $args = array( 'item_id' => $item_id, 'html' => false );
 $avaUrl = bp_core_fetch_avatar($args);
	// extract the img url in old way not working anymore
  //preg_match('~.*?[src=]"(.*?)".*?~i', bp_core_fetch_avatar($args), $matches, PREG_OFFSET_CAPTURE);
  //if(isset($matches[1][0])){ 
  //$wp_ava_url = $matches[1][0];

 if(!empty($avaUrl)){ 
  $wpu = get_user_by( 'ID', $item_id );
  
  if(empty($wpu)){ return; }
  
  $wpu->user_email = mb_strtolower($wpu->user_email,'UTF-8');
  $w3db_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();
  $res = $w3db_conn->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_avatar = '".$avaUrl."', user_avatar_type = 'avatar.driver.remote' WHERE LOWER(user_email) = '".$wpu->user_email."'");
 }
}

// when avatar deletion, should be instead set in phpBB as Gravatar like WP/BP do?
function w3all_bp_avatar_phpbb_delete($args) { 
 	 global $w3all_config;
 	 
  $wpu = get_user_by( 'ID', $args['item_id'] );
  if(empty($wpu)){ return; }
  
  $wpu->user_email = mb_strtolower($wpu,'UTF-8');
  $w3db_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();
	$res = $w3db_conn->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_avatar = '', user_avatar_type = '' WHERE LOWER(user_email) = '".$wpu->user_email."'");
}

if(! defined("WPW3ALL_NOT_ULINKED")){
 if ( $w3all_profile_sync_bp_yn == 1 ){
  add_action( 'xprofile_updated_profile', 'w3all_xprofile_updated_profile', 10, 5 );
 }
 if ( $w3all_get_phpbb_avatar_yn == 1 && $w3all_avatar_replace_bp_yn == 1 ){  
  add_action( 'bp_members_avatar_uploaded', 'w3all_bp_members_avatar_uploaded', 10, 3 );
  add_action( 'bp_core_delete_existing_avatar', 'w3all_bp_avatar_phpbb_delete', 10, 1 );
  add_filter( 'bp_core_fetch_avatar', array( 'WP_w3all_phpbb', 'w3all_bp_core_fetch_avatar' ), 10, 9 );
  }
}


} // END - IF Buddypress installed 

///////////////////////////////////
// BUDDYPRESS profile fields and avatars END
///////////////////////////////////


} // END   if ( defined('PHPBB_INSTALLED') ){ // 2nd //

// This is for feed shortcode param 'w3feed_text_words' and may valid only for a phpBB feed
// If this param passed, and is a phpBB feed, as on 3.2.5 the feed content return
// last part containing 'Statistics:' text - the follow grab and reassign 
// statistics on bottom of the item, removing 'Statistics:'
function wp_w3all_R_num_of_words_parse($post_str, $words){
$pos0 = strpos($post_str, "<p>");
if( preg_match('/(.+)(<p>.?Statistics:(.+)<\/p>)/', $post_str, $str_post_data) > 0 ){

 if(isset($str_post_data[1])){
  $pcontent = '<p>' . trim($str_post_data[1]) .' </p>';
 }
 if(isset($str_post_data[3])){
  $pinfo = '<p>' . $str_post_data[3].'</p>';
 }
} else { $pinfo = ''; $pcontent = $post_str; }
  
 $post_string = explode(' ',$pcontent);

  if( count($post_string) > $words ){

 $post_std = ''; $i = 0; $b = $words;
 
  foreach ($post_string as $post_st) {
	
	  $i++;
	  if( $i < $b + 1 ){ // offset of 1

      $post_std .= $post_st . ' ';
    }
  }

 $post_std .= ' ...';

} else { $post_std = $pcontent; }
	
$post_std .= $pinfo;

return $post_std;

}


if(! is_admin()){
	//require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-phpbb.php' );
  add_shortcode( 'w3allfeed', array( 'WP_w3all_phpbb', 'wp_w3all_feeds_short' ) );
}


function w3all_add_phpbb_user() {
// to be executed, only when NOT on iframe mode
	global $w3all_add_into_wp_u_capability,$wpdb,$wp_w3all_forum_folder_wp;
     if(isset($_GET["w3insu"])){
     	$uw = base64_decode(trim($_GET["w3insu"]));
     } else { return; }

   $phpBB_un = trim( $uw );
   if(empty($phpBB_un)){ return; }
   
   $contains_cyrillic = (bool) preg_match('/[\p{Cyrillic}]/u', $phpBB_un);

  if ( is_multisite() && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$phpBB_un) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpBB_un) OR strlen($phpBB_un) > 50 ){

  	if (!defined('WPW3ALL_NOT_ULINKED')){
  	 define('WPW3ALL_NOT_ULINKED', true);
  	}

      	if( isset($_SERVER['REQUEST_URI']) && !empty($wp_w3all_forum_folder_wp) && strstr($_SERVER['REQUEST_URI'], $wp_w3all_forum_folder_wp) ){
		     echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your forum username contains illegal characters not allowed in this system or contains more than 50 characters.<br />The forum cannot be displayed on this page.<br />Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
         exit;
        }	
        
        echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your forum username contains illegal characters not allowed in this system or contains more than 50 characters.<br />Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
        return;
  }
   
 $user = get_user_by( 'login', $phpBB_un );    
     
 if( ! $user ){
	
	$phpbb_user = WP_w3all_phpbb::wp_w3all_get_phpbb_user_info($phpBB_un);
	
	if(!isset($phpbb_user[0])){ return; }
	
	if( email_exists($phpbb_user[0]->user_email) > 0 ){
	 echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: the email associated with username you are registered into our forum, result to be associated to another username into this system. Please inform an administrator</strong></p>', 'wp-w3all-phpbb-integration');
    exit;
	}
   
// mums allow only '[0-9A-Za-z]'
  // if ( is_multisite() && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$user[0]->username) OR preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$user[0]->username) OR strlen($user[0]->username) > 50 ){   
   // if do not contain non latin chars, let wp create any wp user_login with this passed username
  if ( is_multisite() && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$phpbb_user[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user[0]->username) OR strlen($phpbb_user[0]->username) > 50 ){

  	if (!defined('WPW3ALL_NOT_ULINKED')){
  	 define('WPW3ALL_NOT_ULINKED', true);
  	}
       // since this function is intented to be executed only when NOT on iframe mode, this should be really not required/useful here
      	if( isset($_SERVER['REQUEST_URI']) && !empty($wp_w3all_forum_folder_wp) && strstr($_SERVER['REQUEST_URI'], $wp_w3all_forum_folder_wp) ){
		     echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your forum username contains illegal characters not allowed in this system or contains more than 50 characters.<br />The forum cannot be displayed on this page.<br />Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
         exit;
        }	
        
        echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your forum username contains illegal characters not allowed in this system or contains more than 50 characters.<br />Please contact an administrator.</strong></p>', 'wp-w3all-phpbb-integration');
        return;
  }
     
  
  if ( $phpbb_user[0]->group_name == 'ADMINISTRATORS' ){
      	  $role = 'administrator';
      	} elseif ( $phpbb_user[0]->group_name == 'GLOBAL_MODERATORS' ){
          $role = 'editor';
        } else { // default to WP subscriber
               	 $role = $w3all_add_into_wp_u_capability;
               	}
               	
          $role = $phpbb_user[0]->user_type == 1 ? '' : $role;
               	
              $userdata = array(
               'user_login'       =>  $phpbb_user[0]->username,
               'user_pass'        =>  $phpbb_user[0]->user_password,
               'user_email'       =>  $phpbb_user[0]->user_email,
               'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $phpbb_user[0]->user_regdate ),
               'role'             =>  $role
               );
               
      $user_id = wp_insert_user( $userdata );

   if ( is_wp_error( $user_id ) ) {
    echo '<div style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><p>' . $user_id->get_error_message() . '</p></div>';
    echo __('<div><p style="padding:30px;background-color:#fff;color:#000;font-size:1.0em"><strong>Error: try to reload page, but if the error persist may mean that the forum\'s logged in username contains illegal characters that are not allowed on this system. Please contact an administrator.</strong></p></div>', 'wp-w3all-phpbb-integration');
    exit;
   }  
   
  if ( ! is_wp_error( $user_id ) ) {
  	
  	 $phpbb_username = preg_replace( '/\s+/', ' ', $phpbb_user[0]->username );
     $phpbb_username = esc_sql($phpbb_username);
     $user_username_clean = sanitize_user( $phpbb_user[0]->username, $strict = false );
     $user_username_clean = esc_sql(mb_strtolower($user_username_clean,'UTF-8'));
      
    if ($contains_cyrillic) {
       // update user_login and user_nicename and force to be what needed
       // also update the pass, since re-hashed by wp_insert_user()
       $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbb_username."', user_pass = '".$phpbb_user[0]->user_password."', user_nicename = '".$user_username_clean."', display_name = '".$phpbb_username."' WHERE ID = ".$user_id."");
       $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
     } else { // leave as is (may cleaned and different) the just created user_login
        $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '".$phpbb_user[0]->user_password."', display_name = '".$phpbb_username."' WHERE ID = '$user_id'");
    	  $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
       }
        
   }
   
   if(isset($_GET["w3rtb"])){
   	 $br = base64_decode(trim($_GET["w3rtb"]));
     header("Location: $br"); /* Redirect to phpBB */
     exit; 
   }
 }
}


// Trashed START

// WP_w3all - extract ever the correct cookie domain (except for sub hosted/domains like: mydomain.my-hostingService-domain.com)
// not used since 1.9.0
function w3all_extract_cookie_domain( $w3cookie_domain ) {

require_once( WPW3ALL_PLUGIN_DIR . 'addons/w3_icann_domains.php' );

$count_dot = substr_count($w3cookie_domain, ".");

	 if($count_dot >= 3){
	  preg_match('/.*(\.)([-a-z0-9]+)(\.[-a-z0-9]+)(\.[a-z]+)/', $w3cookie_domain, $w3m0, PREG_OFFSET_CAPTURE);
	  $w3cookie_domain = $w3m0[2][0].$w3m0[3][0].$w3m0[4][0];
   }
   
   $ckcd = explode('.',$w3cookie_domain);

  if(!in_array('.'.$ckcd[1], $w3all_domains)){
   $w3cookie_domain = preg_replace('/^[^\.]*\.([^\.]*)\.(.*)$/', '\1.\2', $w3cookie_domain);
  }

	$w3cookie_domain = '.' . $w3cookie_domain;

 $pos = strpos($w3cookie_domain, '.');
 if($pos != 0){
	$w3cookie_domain = '.' . $w3cookie_domain;
 }

 return $w3cookie_domain;

}

// Trashed END

?>
