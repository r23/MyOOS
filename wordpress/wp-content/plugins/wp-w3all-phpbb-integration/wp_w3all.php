<?php
/**
 * @package wp_w3all
 */
/*
Plugin Name: WordPress w3all phpBB integration
Plugin URI: http://axew3.com/w3
Description: Integration plugin between WordPress and phpBB. It provide free integration - users transfer/login/register. Easy, light, secure, powerful
Version: 2.8.4
Author: axew3
Author URI: http://www.axew3.com/w3
License: GPLv2 or later
Text Domain: wp-w3all-phpbb-integration

=====================================================================================
Copyright (C) 2024 - axew3.com
=====================================================================================
*/

// FORCE Deactivation WP_w3all plugin //
// $w3deactivate_wp_w3all_plugin = 'true';

// FORCE the reset of cookie domain
// $w3reset_cookie_domain = '.mydomain.com';

// Security
defined( 'ABSPATH' ) or die( 'forbidden' );
if ( !function_exists( 'add_action' ) ) {
  die( 'forbidden' );
}

if ( defined( 'W3PHPBBDBCONN' ) OR defined( 'W3PHPBBUSESSION' ) OR defined( 'W3PHPBBLASTOPICS' ) OR defined( 'W3PHPBBCONFIG' ) OR defined( 'W3UNREADTOPICS' ) OR defined( 'W3ALLPHPBBUAVA' ) OR defined("W3BANCKEXEC") ):
  die( 'Forbidden' );
endif;

define( 'WPW3ALL_VERSION', '2.8.4' );
define( 'WPW3ALL_MINIMUM_WP_VERSION', '6.0' );
define( 'WPW3ALL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPW3ALL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

$wp_userphpbbavatar = $w3all_u_ava_urls = $w3all_bbcode_tohtml = $w3all_phpbb_connection = $phpbb_config = $phpbb_user_data = $w3all_phpbb_usession = $w3all_wp_email_exist_inphpbb = $w3all_oninsert_wp_user = $w3all_wpusers_delete_ary = $w3all_wpusers_delete_once = $phpbb_online_udata = $w3all_widget_phpbb_onlineStats_exec = $w3all_user_email_hash = $phpbb_unread_topics = ''; // $w3all_oninsert_wp_user used to check if WP is creating an user, switch to 1 before wp_insert_user fire so to avoid user's email check into phpBB
$w3all_phpbb_profile_fields = $w3all_phpbb_unotifications = array();

$w3all_w_lastopicspost_max = get_option( 'widget_wp_w3all_widget_last_topics' );
$config_avatars = get_option('w3all_conf_avatars');
$w3all_conf_pref = get_option('w3all_conf_pref');
$w3cookie_domain = get_option('w3all_phpbb_cookie');
$w3all_bruteblock_phpbbulist = empty(get_option('w3all_bruteblock_phpbbulist')) ? array() : get_option('w3all_bruteblock_phpbbulist');
$w3all_path_to_cms = get_option('w3all_path_to_cms');
$w3all_pass_hash_way = get_option('w3all_pass_hash_way'); // WP == 1 / rewrited by $w3all_phpbb_dbconn['w3all_pass_hash_way']
$wp_w3all_forum_folder_wp = get_option('w3all_forum_template_wppage');

// rewrite old options vars
$w3all_url_to_cms = get_option('w3all_url_to_cms');

$w3all_phpbb_dbconn = empty(get_option('w3all_phpbb_dbconn')) ? array() : get_option('w3all_phpbb_dbconn');
$w3all_phpbb_url = isset($w3all_phpbb_dbconn['w3all_phpbb_url']) ? $w3all_phpbb_dbconn['w3all_phpbb_url'] : '';

if( !empty($w3all_phpbb_url) ){
  $w3all_url_to_cms = $w3all_phpbb_url;
}
if( !empty($w3all_phpbb_dbconn['w3all_pass_hash_way']) ){
  $w3all_pass_hash_way = $w3all_phpbb_dbconn['w3all_pass_hash_way']; // WP way == 1
}
// Set the integration as 'Not Linked users'
if( !empty($w3all_phpbb_dbconn['w3all_not_link_phpbb_wp']) && $w3all_phpbb_dbconn['w3all_not_link_phpbb_wp'] > 0 ){
   define('WPW3ALL_NOT_ULINKED', true);
} elseif ( get_option('w3all_not_link_phpbb_wp') == 1 ){ // to be removed
 define('WPW3ALL_NOT_ULINKED', true);
}

$phpbb_on_template_iframe = get_option( 'w3all_iframe_phpbb_link_yn' ); // old way: leave this for custom files compatibility
// changed into follow: the name of the option should also change
$w3all_iframe_phpbb_link = unserialize(get_option('w3all_conf_pref_template_embed_link'));

// $w3all_iframe_phpbb_link_yn has been removed all over: no requirement due to the overall_header.html js code in phpBB. Leave only for compatibility with old way
$w3all_iframe_phpbb_link_yn = isset($w3all_iframe_phpbb_link["w3all_iframe_phpbb_link_yn"]) ? $w3all_iframe_phpbb_link["w3all_iframe_phpbb_link_yn"] : 0;
$w3all_iframe_custom_w3fancyurl = isset($w3all_iframe_phpbb_link["w3all_iframe_custom_w3fancyurl"]) ? $w3all_iframe_phpbb_link["w3all_iframe_custom_w3fancyurl"] : 'w3';
$w3all_iframe_custom_top_gap = isset($w3all_iframe_phpbb_link["w3all_iframe_custom_top_gap"]) ? intval($w3all_iframe_phpbb_link["w3all_iframe_custom_top_gap"]) : '100';

if(isset($w3reset_cookie_domain)){
  update_option( 'w3all_phpbb_cookie', $w3reset_cookie_domain );
  $w3cookie_domain = $w3reset_cookie_domain;
}

   $w3all_useragent = (!empty($_SERVER['HTTP_USER_AGENT'])) ? esc_sql(trim($_SERVER['HTTP_USER_AGENT'])) : 'unknown';
   $w3all_config_avatars = unserialize($config_avatars);
   $w3all_get_phpbb_avatar_yn = isset($w3all_config_avatars['w3all_get_phpbb_avatar_yn']) ? $w3all_config_avatars['w3all_get_phpbb_avatar_yn'] : '';
   $w3all_last_t_avatar_yn = isset($w3all_config_avatars['w3all_avatar_on_last_t_yn']) ? $w3all_config_avatars['w3all_avatar_on_last_t_yn'] : '';
   $w3all_last_t_avatar_dim = isset($w3all_config_avatars['w3all_lasttopic_avatar_dim']) ? $w3all_config_avatars['w3all_lasttopic_avatar_dim'] : '';
   $w3all_lasttopic_avatar_num = isset($w3all_config_avatars['w3all_lasttopic_avatar_num']) ? $w3all_config_avatars['w3all_lasttopic_avatar_num'] : '';

   $w3all_conf_pref = empty($w3all_conf_pref) ? array() : unserialize($w3all_conf_pref);
   $w3all_transfer_phpbb_yn = isset($w3all_conf_pref['w3all_transfer_phpbb_yn']) ? $w3all_conf_pref['w3all_transfer_phpbb_yn'] : '';
   $w3all_phpbb_widget_mark_ru_yn = isset($w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn']) ? $w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn'] : '';
   $w3all_phpbb_widget_FA_mark_yn = isset($w3all_conf_pref['w3all_phpbb_widget_FA_mark_yn']) ? $w3all_conf_pref['w3all_phpbb_widget_FA_mark_yn'] : 0;
   $w3all_phpbb_user_deactivated_yn = isset($w3all_conf_pref['w3all_phpbb_user_deactivated_yn']) ? $w3all_conf_pref['w3all_phpbb_user_deactivated_yn'] : 0;

   $w3all_phpbb_wptoolbar_pm_yn = isset($w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn']) ? $w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn'] : '';
   $w3all_exclude_phpbb_forums = isset($w3all_conf_pref['w3all_exclude_phpbb_forums']) ? $w3all_conf_pref['w3all_exclude_phpbb_forums'] : '';
   $w3all_phpbb_lang_switch_yn = isset($w3all_conf_pref['w3all_phpbb_lang_switch_yn']) ? $w3all_conf_pref['w3all_phpbb_lang_switch_yn'] : 0;
   $w3all_get_topics_x_ugroup = isset($w3all_conf_pref['w3all_get_topics_x_ugroup']) ? $w3all_conf_pref['w3all_get_topics_x_ugroup'] : 0;
   $w3all_custom_output_files = isset($w3all_conf_pref['w3all_custom_output_files']) ? $w3all_conf_pref['w3all_custom_output_files'] : 0;
   $w3all_add_into_spec_group = isset($w3all_conf_pref['w3all_add_into_spec_group']) ? $w3all_conf_pref['w3all_add_into_spec_group'] : 2;
   $w3all_wp_phpbb_lrl_links_switch_yn = isset($w3all_conf_pref['w3all_wp_phpbb_lrl_links_switch_yn']) ? $w3all_conf_pref['w3all_wp_phpbb_lrl_links_switch_yn'] : 0;
   $w3all_anti_brute_force_yn = isset($w3all_conf_pref['w3all_anti_brute_force_yn']) ? $w3all_conf_pref['w3all_anti_brute_force_yn'] : 1;
   $w3all_custom_iframe_yn = isset($w3all_conf_pref['w3all_custom_iframe_yn']) ? $w3all_conf_pref['w3all_custom_iframe_yn'] : 0;
   $w3all_add_into_wp_u_capability = isset($w3all_conf_pref['w3all_add_into_wp_u_capability']) ? $w3all_conf_pref['w3all_add_into_wp_u_capability'] : 'subscriber';
   $w3all_add_into_phpBB_after_confirm = isset($w3all_conf_pref['w3all_add_into_phpBB_after_confirm']) ? $w3all_conf_pref['w3all_add_into_phpBB_after_confirm'] : 0;
   $w3all_push_new_pass_into_phpbb = isset($w3all_conf_pref['w3all_push_new_pass_into_phpbb']) ? $w3all_conf_pref['w3all_push_new_pass_into_phpbb'] : 0;
   $w3all_disable_ck_email_before_wp_update = isset($w3all_conf_pref['w3all_disable_ck_email_before_wp_update']) ? $w3all_conf_pref['w3all_disable_ck_email_before_wp_update'] : 0;
   $w3all_delete_users_into_phpbb_ext = isset($w3all_conf_pref['w3all_delete_users_into_phpbb_ext']) ? $w3all_conf_pref['w3all_delete_users_into_phpbb_ext'] : 0;
   $wp_w3all_phpbb_iframe_short_pages_yn = (isset($w3all_conf_pref['wp_w3all_phpbb_iframe_short_pages_yn']) && ! empty($w3all_conf_pref['wp_w3all_phpbb_iframe_short_pages_yn'])) ? trim($w3all_conf_pref['wp_w3all_phpbb_iframe_short_pages_yn']) : '';
   $wp_w3all_phpbb_iframe_short_token_yn = (isset($w3all_conf_pref['wp_w3all_phpbb_iframe_short_token_yn']) && ! empty($w3all_conf_pref['wp_w3all_phpbb_iframe_short_token_yn'])) ? trim($w3all_conf_pref['wp_w3all_phpbb_iframe_short_token_yn']) : '';
   $w3all_phpbb_unotifications_yn = (isset($w3all_conf_pref['w3all_phpbb_unotifications_yn']) && ! empty($w3all_conf_pref['w3all_phpbb_unotifications_yn'])) ? $w3all_conf_pref['w3all_phpbb_unotifications_yn'] : 0;
   $w3all_link_roles_groups = (isset($w3all_conf_pref['w3all_link_roles_groups']) && ! empty($w3all_conf_pref['w3all_link_roles_groups'])) ? $w3all_conf_pref['w3all_link_roles_groups'] : 0;
   $wp_w3all_heartbeat_phpbb_lastopics_pages = isset($w3all_conf_pref['w3all_heartbeat_phpbb_lastopics_pages']) && ! empty($w3all_conf_pref['w3all_heartbeat_phpbb_lastopics_pages']) ? $w3all_conf_pref['w3all_heartbeat_phpbb_lastopics_pages'] : '';
   $w3all_heartbeat_phpbb_lastopics_num = isset($w3all_conf_pref['w3all_heartbeat_phpbb_lastopics_num']) && ! empty($w3all_conf_pref['w3all_heartbeat_phpbb_lastopics_pages']) ? $w3all_conf_pref['w3all_heartbeat_phpbb_lastopics_num'] : '';

   // to set W3PHPBBLASTOPICS
   // then used to avoid more calls in case of multiple widgets (not x shortcode by forums ids)

   if(!empty($w3all_w_lastopicspost_max)){
     foreach ($w3all_w_lastopicspost_max as $row) {
        if(isset($row['topics_number'])){
          $w3all_wlastopicspost_max[] = $row['topics_number'];
        }
     }
      $w3all_wlastopicspost_max = isset($w3all_wlastopicspost_max) && is_array($w3all_wlastopicspost_max) ? max($w3all_wlastopicspost_max) : 10;
    } else { $w3all_wlastopicspost_max = 10; }


if ( defined( 'WP_ADMIN' ) )
{

  function w3all_VAR_IF_U_CAN(){

    if ( isset($_POST["w3all_phpbb_dbconn"]) )
    {
     if ( !current_user_can('manage_options') ) {
      die('<h3>Forbidden</h3>');
     } elseif ( isset($_POST["w3all_phpbb_dbconn"]) && is_array($_POST['w3all_phpbb_dbconn']) ){
      $_POST['w3all_phpbb_dbconn'] = array_map('trim',$_POST['w3all_phpbb_dbconn']);
     } else { return; }

     $w3all_phpbb_dbconn = array();
     $w3all_phpbb_dbconn['w3all_phpbb_url'] = (!filter_var($_POST['w3all_phpbb_dbconn']['w3all_phpbb_url'], FILTER_VALIDATE_URL)) ? '' : $_POST['w3all_phpbb_dbconn']['w3all_phpbb_url'];
     $w3all_phpbb_dbconn['w3all_phpbb_dbhost'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbhost'];
     $w3all_phpbb_dbconn['w3all_phpbb_dbname'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbname'];
     $w3all_phpbb_dbconn['w3all_phpbb_dbuser'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbuser'];
     $w3all_phpbb_dbconn['w3all_phpbb_dbpasswd'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbpasswd'];
     $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbtableprefix'];
     $w3all_phpbb_dbconn['w3all_phpbb_dbport'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbport'];
     $w3all_phpbb_dbconn['w3all_pass_hash_way'] = intval($_POST['w3all_phpbb_dbconn']['w3all_pass_hash_way']);
     $w3all_phpbb_dbconn['w3all_not_link_phpbb_wp'] = intval($_POST['w3all_phpbb_dbconn']['w3all_not_link_phpbb_wp']);
      update_option('w3all_phpbb_dbconn',$w3all_phpbb_dbconn);
      $w3all_config_db = array( 'dbhost' => $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbhost'], 'dbport' => $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbport'], 'dbname' => $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbname'], 'dbuser' => $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbuser'], 'dbpasswd' => $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbpasswd'], 'table_prefix' => $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbtableprefix'] );
      $w3all_config = array( 'table_prefix' => $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbtableprefix'] );

      $opt_w3all_url = admin_url() . 'options-general.php?page=wp-w3all-options';
      wp_redirect($opt_w3all_url); exit();
    }
  } // END function w3all_VAR_IF_U_CAN(){

  // phpBB file config inclusion, or not
 if( !isset($_POST['w3all_phpbb_dbconn']) && empty($w3all_phpbb_dbconn) && !isset($w3deactivate_wp_w3all_plugin) )
 { // config file inclusion

    if(!empty($w3all_path_to_cms)){   // or may will search for some config file elsewhere

      $config_file = get_option( 'w3all_path_to_cms' ) . '/config.php';
      if (file_exists($config_file)) {
       ob_start();
         include( $config_file );
       ob_end_clean();
      }
     }

  if (defined('PHPBB_INSTALLED'))
  {
   if ( defined('WP_W3ALL_MANUAL_CONFIG') )
   { // custom phpBB config
      $w3all_config_db = array( 'dbhost' => $w3all_dbhost, 'dbport' => $w3all_dbport, 'dbname' => $w3all_dbname, 'dbuser'   => $w3all_dbuser, 'dbpasswd' => $w3all_dbpasswd, 'table_prefix' => $w3all_table_prefix );
      $w3all_config = array( 'table_prefix' => $w3all_table_prefix );
   } else
     { // default phpBB config.php
      $w3all_config_db = array( 'dbhost' => $dbhost, 'dbport' => $dbport, 'dbname' => $dbname, 'dbuser' => $dbuser, 'dbpasswd' => $dbpasswd, 'table_prefix' => $table_prefix );
      $w3all_config = array( 'table_prefix' => $table_prefix );
     }

    define('W3PHPBBDBCONN', $w3all_config_db);
    //unset($w3all_config_db,$w3all_phpbb_dbconn);
  }

   // go with $w3all_phpbb_dbconn values, no config.php inclusion
 } elseif ( !isset($_POST['w3all_phpbb_dbconn']) && !empty($w3all_phpbb_dbconn) && !isset($w3deactivate_wp_w3all_plugin) )
   {
      $w3all_config_db = array( 'dbhost' => $w3all_phpbb_dbconn['w3all_phpbb_dbhost'], 'dbport' => $w3all_phpbb_dbconn['w3all_phpbb_dbport'], 'dbname' => $w3all_phpbb_dbconn['w3all_phpbb_dbname'], 'dbuser'   => $w3all_phpbb_dbconn['w3all_phpbb_dbuser'], 'dbpasswd' => $w3all_phpbb_dbconn['w3all_phpbb_dbpasswd'], 'table_prefix' => $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] );
      define('W3PHPBBDBCONN', $w3all_config_db);
      $w3all_config = array( 'table_prefix' => $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] );
      //unset($w3all_config_db,$w3all_phpbb_dbconn);
   }
// if $_POST['w3all_phpbb_dbconn'] isset and
// nothing above until here fired: let add_action( 'init', 'w3all_VAR_IF_U_CAN', 1 ); setup vars and redirect to plugin admin

      require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-phpbb.php' );
      require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-admin.php' );
      require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all.widgets-phpbb.php' );

      add_action( 'init', array( 'WP_w3all_admin', 'wp_w3all_init' ) );
      add_action( 'init', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_init' ), 3 );
      add_action( 'init', 'w3all_VAR_IF_U_CAN', 4 );

   if ( defined('W3PHPBBDBCONN') && !isset($w3deactivate_wp_w3all_plugin) OR defined('PHPBB_INSTALLED') && !isset($w3deactivate_wp_w3all_plugin) ){

    function wp_w3all_phpbb_registration_save_adm( $user_id ) {
      global $w3all_oninsert_wp_user; // if 1 we are inserting an user and if wp_insert_user into function 'verify_phpbb_credentials()' do not return error, there is no need to delete or check the user: it is an user just inserted, the email would be found in phpBB!
     if ( is_multisite() OR defined('W3DISABLECKUINSERTRANSFER') ) { return; } // or get error on activating MUMS user ... msmu user will use a different way
     // the same transferring users from phpBB to Wp, the check in this case is done directly within the transfer process

      $wpu  = get_user_by('id', $user_id);

      if(!$wpu){ return; }

       $uexist = WP_w3all_phpbb::phpBB_user_check($wpu->user_login, $wpu->user_email, 1);

        if($uexist === true && $w3all_oninsert_wp_user != 1){
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

       if(!$uexist){
         $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_res($wpu);
       }
 }

function wp_w3all_up_phpbb_prof($user_id, $old_user_data) {

   $phpBB_upp = WP_w3all_phpbb::phpbb_update_profile($user_id, $old_user_data);

    if($phpBB_upp === true && current_user_can( 'manage_options' )){
        $redirect_to = admin_url() . 'user-edit.php?user_id='.$user_id;
     }
}

if(! defined("WPW3ALL_NOT_ULINKED")){
 add_action( 'user_profile_update_errors', 'w3all_user_profile_update_errors', 10, 1 );
 add_action( 'profile_update', 'wp_w3all_up_phpbb_prof', 20, 2 ); // since WP 5.8 $userdata added
 //add_action( 'profile_update', array( 'WP_w3all_phpbb', 'wp_phpbb_update_profile' ), 10, 3 );
 add_action( 'user_register', 'wp_w3all_phpbb_registration_save_adm', 10, 1 );
 add_action( 'delete_user', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_delete_user' ) ); // for phpBB ext
 // if these two fires because activated, may then the other add_action( 'delete_user' should not fire while this is active. It only deactivate in phpBB
 // Do not go to disable anyway, because if this will fail (it is one cURL call that will execute on start)
 // the other will work by the way. This also, run only once in case, at first delete delete_user
 // to understand these two hooks, see phpBB/ext/w3all/phpbbwordpress/event/main_listener.php
 if( $w3all_delete_users_into_phpbb_ext > 0 )
 {
  include( WPW3ALL_PLUGIN_DIR.'common/phpbb_endpoints_ext_functions.php' );
  add_action( 'delete_user', 'w3all_usersdata_predelete_in_phpbb_exec', 10, 3);
  add_action( 'deleted_user', 'w3all_usersdata_deleted_in_phpbb_exec', 10, 3);
 }

  add_action( 'set_logged_in_cookie', 'wp_w3all_user_session_set', 10, 5 );

 if(!empty($w3all_phpbb_wptoolbar_pm_yn)){
  add_action( 'admin_bar_menu', 'wp_w3all_toolbar_new_phpbbpm', 999 );  // notify about new phpBB pm
 }
}

function wp_w3all_user_session_set( $logged_in_cookie, $expire, $expiration, $user_id, $scheme ) {
  $user = get_user_by( 'ID', $user_id );
    $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($user);
    return;
}

} // defined('W3PHPBBDBCONN') WP admin end

} else { // not in WP admin

 if( empty($w3all_phpbb_dbconn) && !isset($w3deactivate_wp_w3all_plugin) )
 {
  // or will search for some config file elsewhere
  $w3all_path_to_cms = get_option( 'w3all_path_to_cms' );
  if(!empty($w3all_path_to_cms)){
   $config_file = get_option( 'w3all_path_to_cms' ) . '/config.php';
   if (file_exists($config_file)) {
     ob_start();
      include_once( $config_file );
     ob_end_clean();
    }
   }

  if (defined('PHPBB_INSTALLED')){

    if (defined('WP_W3ALL_MANUAL_CONFIG')){
      $w3all_config_db = array( 'dbhost' => $w3all_dbhost,'dbport' => $w3all_dbport,'dbname' => $w3all_dbname,'dbuser' => $w3all_dbuser,'dbpasswd' => $w3all_dbpasswd,'table_prefix' => $w3all_table_prefix );
      $w3all_config = array( 'table_prefix' => $w3all_table_prefix );
    } else {
      $w3all_config_db = array( 'dbhost' => $dbhost,'dbport' => $dbport,'dbname' => $dbname,'dbuser' => $dbuser,'dbpasswd' => $dbpasswd,'table_prefix' => $table_prefix );
      $w3all_config = array( 'table_prefix' => $table_prefix );
    }
      define('W3PHPBBDBCONN', $w3all_config_db);
      unset($w3all_config_db,$w3all_phpbb_dbconn);
   }
 }
  elseif ( !empty($w3all_phpbb_dbconn) && !isset($w3deactivate_wp_w3all_plugin) )
   {  // go with $w3all_phpbb_dbconn values, no config.php inclusion
      $w3all_config_db = array( 'dbhost' => $w3all_phpbb_dbconn['w3all_phpbb_dbhost'], 'dbport' => $w3all_phpbb_dbconn['w3all_phpbb_dbport'], 'dbname' => $w3all_phpbb_dbconn['w3all_phpbb_dbname'], 'dbuser'   => $w3all_phpbb_dbconn['w3all_phpbb_dbuser'], 'dbpasswd' => $w3all_phpbb_dbconn['w3all_phpbb_dbpasswd'], 'table_prefix' => $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] );
      define('W3PHPBBDBCONN', $w3all_config_db);
      $w3all_config = array( 'table_prefix' => $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] );
      unset($w3all_config_db,$w3all_phpbb_dbconn);
   }


  // 1st of 2
  if ( defined('W3PHPBBDBCONN') && !isset($w3deactivate_wp_w3all_plugin) ){

     require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all-phpbb.php' );
     require_once( WPW3ALL_PLUGIN_DIR . 'class.wp.w3all.widgets-phpbb.php' );

      add_action( 'init', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_init'), 3);

function w3all_login_widget(){

  if(isset($_POST['log']) && isset($_POST['pwd']))
  {
   $_POST['w3all_username'] = $_POST['log'];
   $_POST['w3all_password'] = $_POST['pwd'];
  }

  $passed_uname = str_replace(chr(0), '', $_POST['w3all_username']);
  $passed_uname = trim(stripcslashes($passed_uname));
    $_POST['redirect_to'] = (!empty($_POST['redirect_to'])) ? str_replace(chr(0), '', $_POST['redirect_to']) : home_url();

    if ( empty($passed_uname) ){
        if(strpos($_POST['redirect_to'],'?')){
      wp_safe_redirect( $_POST['redirect_to'] . '&reauth=2' ); exit;
     } else {
       wp_safe_redirect( $_POST['redirect_to'] . '?reauth=2' ); exit;
      }
         return;
     }

  global $wpdb,$w3all_oninsert_wp_user,$w3all_anti_brute_force_yn,$w3all_bruteblock_phpbbulist,$w3cookie_domain,$w3all_add_into_wp_u_capability,$wp_w3all_forum_folder_wp,$w3all_push_new_pass_into_phpbb;
  $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
  $wpu_db_umtab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';

  $user = empty($passed_uname) ? array() : WP_w3all_phpbb::wp_w3all_get_phpbb_user_info($passed_uname);

///////////
// If option "force the password update into phpBB onlogin in wordpress" active
 if(isset($user[0])){

 if( $w3all_push_new_pass_into_phpbb == 1 ){

  $wpuck = get_user_by( 'email', $user[0]->user_email );

   if( isset($user[0]->user_id) && isset($wpuck->user_pass) && $wpuck->user_pass != $user[0]->user_password && $user[0]->user_id > 2 )
   {
     $new_pass_push = $user[0]->user_password = $wpuck->user_pass;
     $qres = WP_w3all_phpbb::wp_w3all_update_phpBB_udata($user[0]->user_email, $wpuck->user_pass, $update="pass");
    }
  }

// mums allow only '[0-9a-z]' // wp-includes/ms-functions.php -> function wpmu_validate_user_signup
// default wp allow allow only [-0-9A-Za-z _.@] //  if( preg_match('/[^-0-9A-Za-z _.@]/',$phpbb_user[0]->username) ){
   $contains_cyrillic = (bool) preg_match('/[\p{Cyrillic}]/u', $user[0]->username);
  // if do not contain non latin chars, let wp create any wp user_login with this passed username
  if ( is_multisite() && !defined('WPW3ALL_USE_DEFAULT_WP_UCHARS') && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$user[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$user[0]->username) OR strlen($user[0]->username) > 50 ){
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
               //'user_email'       =>  $user[0]->user_email,
               'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $user[0]->user_regdate ),
               'role'             =>  $role,
               'nickname'         =>  $user[0]->username
               );

      $w3all_oninsert_wp_user = 1;
      $user_id = wp_insert_user( $userdata );

    if ( is_wp_error( $user_id ) ) {
    echo '<div style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><p>' . $user_id->get_error_message() . '</p></div>';
    echo __('<div><p style="padding:30px;background-color:#fff;color:#000;font-size:1.0em"><strong>Error: try to reload page, but if the error persist may mean that the forum\'s logged in username contains illegal characters that are not allowed on this system. Please contact an administrator.</strong></p></div>', 'wp-w3all-phpbb-integration');
    exit;
   }

      $phpbb_username = preg_replace( '/\s+/', ' ', $user[0]->username );
      $phpbb_username = esc_sql($phpbb_username);
      $phpbb_user_email = $user[0]->user_email;
      $phpbb_user_pass = $user[0]->user_password;
      $user_username_clean = sanitize_user( $user[0]->username, $strict = false );
      $user_username_clean = esc_sql(mb_strtolower($user_username_clean,'UTF-8'));

     if ( ! is_wp_error( $user_id ) ) {
       if ($contains_cyrillic) {
          // update user_login and user_nicename and force to be what needed
          // also update the pass, since re-hashed by wp_insert_user()
          $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbb_username."', user_pass = '".$phpbb_user_pass."', user_nicename = '".$user_username_clean."', user_email = '".$phpbb_user_email."', display_name = '".$phpbb_username."' WHERE ID = ".$user_id."");
          $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
         } else { // leave as is (may cleaned and different) the just created user_login
            $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '".$phpbb_user_pass."', user_email = '".$phpbb_user_email."', display_name = '".$phpbb_username."' WHERE ID = '$user_id'");
            $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
          }

      }

    }
   } // END // add this phpBB user in Wp

  } // END // isset($user[0])
   // if user just inserted, may at this point $wp_signon fail, despite valid credentials

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
        //update_user_caches(new WP_User($wpu->ID));
        do_action( 'wp_login', $wpu->user_login, $wpu );
       if(!defined("W3ALL_SESSION_ARELEASED")){
        $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($wpu);
       }
      }
    } else {
     $w3all_exec_u_login = wp_signon( array('user_login' => $_POST['w3all_username'], 'user_password' => trim($_POST['w3all_password']), 'remember' => 1), is_ssl() ); // remember = true -> lead to fail login
    }

  // signon fail
   if( isset($w3all_exec_u_login) && is_wp_error($w3all_exec_u_login) OR isset($pass_match) && !$pass_match )
   {
      if( $w3all_anti_brute_force_yn == 1 && isset($user[0]->user_id) ){
       $w3all_bruteblock_phpbbulist[$user[0]->user_id] = $user[0]->user_id;
       update_option( 'w3all_bruteblock_phpbbulist', $w3all_bruteblock_phpbbulist );
      }

     if(strpos($_POST['redirect_to'],'?')){
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
     return __('<strong>Notice: account Locked<br />Login to unlock your account</strong><br />Mismatching session<br /><strong>To fix the problem please login now here!</strong>', 'wp-w3all-phpbb-integration');
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
     return __('Notice: the username is currently inactive into our forum. Please contact an administrator.', 'wp-w3all-phpbb-integration');
    }
    if(trim($_COOKIE["w3all_set_cmsg"]) == 'phpbb_uname_chars_error'){
     return __('Notice: the forum username contains forbidden characters into this system. Please contact an administrator.', 'wp-w3all-phpbb-integration');
    }
    if(trim($_COOKIE["w3all_set_cmsg"]) == 'phpbb_sess_brutef_error'){
     return __('Notice: mismatching session. Please login here to unlock your account.', 'wp-w3all-phpbb-integration');
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

     $u = WP_w3all_phpbb::ck_phpbb_user($sanitized_user_login, $user_email);

      if(!empty($u)){
         $errors->add( 'w3all_user_exist', __( '<strong>Error</strong>: provided email or username already exists on our forum database.', 'wp-w3all-phpbb-integration' ) );
         return $errors;
      }

      return $errors;
}

function wp_w3all_wp_after_password_reset($user, $new_pass) {
  $phpBB_user_pass_set = WP_w3all_phpbb::phpbb_pass_update_res($user, $new_pass);
  $phpBB_user_activate = WP_w3all_phpbb::wp_w3all_wp_after_pass_reset($user);
}


function wp_w3all_phpbb_registration_save($user_id) {

     $wpu = get_user_by('ID', $user_id);
    if( !empty($wpu->user_email) && empty( WP_w3all_phpbb::wp_w3all_get_phpbb_user_info($wpu->user_email)) ){
     $phpBB_user_add = WP_w3all_phpbb::create_phpBB_user_res($wpu);
    }
}

 function wp_w3all_phpbb_login($user_login, $user = '') {

   if( ! defined("PHPBBAUTHCOOKIEREL") ){
     $phpBB_user_session_set = WP_w3all_phpbb::phpBB_user_session_set_res($user);
   }
 }

   function wp_w3all_up_wp_prof_on_phpbb($user_id, $old_user_data) {

     $phpBB_user_up_prof_on_wp_prof_up = WP_w3all_phpbb::phpbb_update_profile($user_id, $old_user_data);

      /*if($phpBB_user_up_prof_on_wp_prof_up === true){
        temp_wp_w3_error_on_update();
        exit;
      }*/
   }


function w3all_password_reset($user, $new_pass) {
    $phpBB_user_pass_set = WP_w3all_phpbb::phpbb_pass_update_res($user, $new_pass);
    $phpBB_user_activate = WP_w3all_phpbb::wp_w3all_wp_after_pass_reset($user);
}

function wp_w3all_add_phpbb_font_awesome(){
  global $w3all_phpbb_url;
// retrieve css font awesome from phpBB
 echo "<link rel=\"stylesheet\" href=\"" . $w3all_phpbb_url . "/assets/css/font-awesome.min.css\" />
";
}

  function phpbb_auth_login_url( $login_url, $redirect, $force_reauth ) {
    global $w3all_url_to_cms;
     $redirect = $w3all_url_to_cms . '/ucp.php?mode=login';
    return $redirect;
   }

  function phpbb_reset_pass_url( $lostpassword_url, $redirect ) {
    global $w3all_url_to_cms;
     $redirect = $w3all_url_to_cms . '/ucp.php?mode=sendpassword';
    return $redirect;
  }

 function phpbb_register_url( $register_url ) {
   global $w3all_url_to_cms;
    $redirect = $w3all_url_to_cms . '/ucp.php?mode=register';
   return $redirect;
 }


if(! defined("WPW3ALL_NOT_ULINKED")){
  if ($w3all_disable_ck_email_before_wp_update < 1){ // 0 enabled
  // this is inside // not in WP admin{} so it do not run into default WP admin profile pages
  // note that 'user_profile_update_errors' hook will run instead into default wp profile wp-admin pages
   add_filter( 'pre_user_email', 'w3all_filter_pre_user_email', 10, 1 ); // check for possible duplicated email in phpBB, BEFORE the email being updated in WP
  }
  // OR isset($_POST['log']) && isset($_POST['pwd']) should be added to let add in WP, users that login via (example) memberpress front-page account:
  // when no other hook run, it is possible to let it fire the user addition in WP onlogin, changing into this the line below
  // note that adding more vars than $_POST['log'] and $_POST['pwd'] for some other plugin and make it work all, it is necessary to add related vars on top into w3all_login_widget() function
//if( isset($_POST['w3all_username']) && isset($_POST['w3all_password']) OR isset($_POST['log']) && isset($_POST['pwd']) ){
  if( isset($_POST['w3all_username']) && isset($_POST['w3all_password']) ){
   add_action( 'init', 'w3all_login_widget');
  }
  add_filter( 'auth_cookie_expiration', 'w3all_rememberme_long' );
  // this is not required since 2.4.0, because registrations allowed only in phpBB OR WP. Anyway leave it here to may check for problems
  add_filter( 'registration_errors', 'wp_w3all_check_fields', 10, 3 ); // prevent user addition (may not external plugins) if phpBB email or username already exist in phpBB, into default wordpress
  add_action( 'user_register', 'wp_w3all_phpbb_registration_save', 10, 1 );
  add_action( 'password_reset', 'wp_w3all_wp_after_password_reset', 10, 2 );
  // a phpBB user not logged into phpBB, WP login first time, (may still) not existent in wp
  add_action( 'wp_authenticate', array( 'WP_w3all_phpbb', 'w3_check_phpbb_profile_wpnu' ), 10, 1 );
  add_action( 'wp_logout', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_logout' ) );
  add_action( 'profile_update', 'wp_w3all_up_wp_prof_on_phpbb', 10, 2 );
  add_action( 'delete_user', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_delete_user' ) ); // x buddypress or ohers plugins that allow users to delete their own user's account on frontend
  add_action( 'user_profile_update_errors', 'w3all_user_profile_update_errors', 10, 1 );
  if(!empty($w3all_phpbb_wptoolbar_pm_yn)){
   add_action( 'admin_bar_menu', 'wp_w3all_toolbar_new_phpbbpm', 999 );  // notify about new phpBB pms
  }

} // END this -> if(! defined("WPW3ALL_NOT_ULINKED")){


 } // end if ( defined('W3PHPBBDBCONN')
} // end not in admin


 if ( defined('W3PHPBBDBCONN') && !isset($w3deactivate_wp_w3all_plugin) )
 {
  ######
  # Gutenberg blocks start

  function w3all_last_phpbb_topics___register_blocks() {
   register_block_type( WPW3ALL_PLUGIN_DIR . 'common/apps/phpbb_last_topics' );
  }
  add_action( 'init', 'w3all_last_phpbb_topics___register_blocks' );

  # Gutenberg blocks end
  ######

   if (function_exists('wp_w3all_phpbb_login')){
    add_action( 'wp_login', 'wp_w3all_phpbb_login', 10, 2);
   }
   if ( $w3all_phpbb_widget_mark_ru_yn == 1 ){
    add_action( 'init', array( 'WP_w3all_phpbb', 'w3all_get_unread_topics'), 9);
   if( $w3all_phpbb_widget_FA_mark_yn == 1 ){
    add_action('wp_head','wp_w3all_add_phpbb_font_awesome');
   }
  }

// Swap WordPress default Register and Lost Password links
if( $w3all_wp_phpbb_lrl_links_switch_yn > 0 ){
 add_filter( 'lostpassword_url', 'phpbb_reset_pass_url', 10, 2 );
 add_filter( 'register_url', 'phpbb_register_url', 10, 1);
}

if(! is_admin())
{
 add_shortcode( 'w3allphpbbupm', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_upm_short' ) );
 add_shortcode( 'w3allforumpost', array( 'WP_w3all_phpbb', 'wp_w3all_get_phpbb_post_short' ) );
 add_shortcode( 'w3allastopics', array( 'WP_w3all_phpbb', 'wp_w3all_get_phpbb_lastopics_short' ) );
 add_shortcode( 'w3allastopicforumsids', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_last_topics_single_multi_fp_short' ) );
 // the query inside the function search all latest updated topics that contains ALMOST an attach and will return only the older (so the first, time based) inserted attachment that belong to the topic
 add_shortcode( 'w3allastopicswithimage', array( 'WP_w3all_phpbb', 'wp_w3all_get_phpbb_lastopics_short_wi' ) );
 add_shortcode( 'w3allogin', array( 'WP_w3all_phpbb', 'w3all_login_short' ) );

 if( $w3all_custom_iframe_yn == 1 ){
  add_shortcode( 'w3allcustomiframe', array( 'WP_w3all_phpbb', 'wp_w3all_custom_iframe_short' ) );
  // do not re-add the iframe lib if on page-forum.php and if not possible to check add by the way
   if ( ! empty($_SERVER['REQUEST_URI']) && ! strpos($_SERVER['REQUEST_URI'], $wp_w3all_forum_folder_wp ) OR empty($_SERVER['REQUEST_URI']) ){
    add_action('wp_head', array( 'WP_w3all_phpbb', 'wp_w3all_add_iframeResizer_lib' ) );
   }
 }

 if(!empty($wp_w3all_phpbb_iframe_short_pages_yn))
 {
   add_action( 'init', 'wp_w3all_phpbb_iframe_shortif');
 }

  add_shortcode( 'w3allfeed', array( 'WP_w3all_phpbb', 'wp_w3all_feeds_short' ) );

 if($w3all_phpbb_unotifications_yn > 0)
 {
  add_shortcode( 'w3all_phpbb_unotifications', array( 'WP_w3all_phpbb', 'wp_w3all_phpbb_unotifications_short' ) );
 }

}

// replaced by 'wp_w3all_toolbar_new_phpbbpm', where page-forum push with js on parent
// add_action('wp_head','wp_w3all_new_phpbbpm_wp_menu_item_push');

// signup common check and common checks
if(! defined("WPW3ALL_NOT_ULINKED")){
 add_filter( 'validate_username', 'w3all_on_signup_check', 1, 2 ); // give precedence, prevent others to add a signup not needed
 add_action( 'init', 'w3all_add_phpbb_user' );
  if ( ! defined( 'WP_ADMIN' ) ){ // let run it only for front end plugin pages
   add_filter( 'wp_pre_insert_user_data', 'w3all_wp_pre_insert_user_data', 10, 4 );
  }
}

// jQuery
add_action('wp_enqueue_scripts', 'wp_w3all_enqueue_scripts');
 function wp_w3all_enqueue_scripts() {
  wp_enqueue_script("jquery");
 }

function w3all_rememberme_long($expire) { // Set remember me wp cookie to expire in one year
    return 31536000; // YEAR_IN_SECONDS;
   }


function w3all_filter_pre_user_email($raw_user_email) {

  if ( is_user_logged_in() && ! is_admin() ) { // front-end page

  global $phpbb_config;

   // there is only the passed email to be updated (maybe): check if it exist
   // Buddypress email notification is sent before this
   $raw_user_email = sanitize_email($raw_user_email);
   if( is_email($raw_user_email) && !email_exists($raw_user_email) ){ // if the email do not exist, then maybe it is an update
    $ck = WP_w3all_phpbb::ck_phpbb_user( $user_login = '', $raw_user_email );
     if( !empty($ck) ){
        $wpu = get_user_by('ID',get_current_user_id());
        $raw_user_email = $wpu->user_email; // Not so elegant: reset to the old one, instead to stop with an error that will go to broke ajax calls or something else (ex in woocommerce transactions)

        $error = new WP_Error();
        $error->add( 'invalid_email', 'Error: username or email address already exist into our forum.' );
        //the user will not be updated but (maybe, it depend by plugins) no message will be thrown, and the user's email notification (email changed), may will fire, even if the email do not changed at all
        //the email notification action should be suppressed here in case
      }
    }
  }

  return $raw_user_email;
}

function wp_w3all_phpbb_iframe_shortif()
 {

   global $wp_w3all_phpbb_iframe_short_pages_yn;

  if (  is_admin() OR is_customize_preview() ) { return; }

  $wp_w3all_phpbb_iframe_pages = explode(',',trim($wp_w3all_phpbb_iframe_short_pages_yn));
  if(is_array($wp_w3all_phpbb_iframe_pages))
  {
    $wp_w3all_phpbb_iframe_pages = array_map('trim', $wp_w3all_phpbb_iframe_pages);
    $shortonpage = $shortonpage_home = false;
    foreach($wp_w3all_phpbb_iframe_pages as $p)
    { // detect which/if page match in the request and check if inhomepage-phpbbiframe for home has been set: check it after
      if($p=='inhomepage-phpbbiframe'){ $shortonpage_home = true; }
      if(strpos($_SERVER['REQUEST_URI'], $p))
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
     }

     $siteUrl = get_option('siteurl');

     if(substr($siteUrl, -1, 1) == '/'){
      $siteUrl = substr($siteUrl, 0, -1);
     }

    if(!empty($REQUEST_URI)){
      $r = strpos($siteUrl, $REQUEST_URI);
      if($r !== false)
      { $shortonpage = true; }
     } elseif ( $_SERVER['REQUEST_URI'] == '/' ){ $shortonpage = true; }
    }
   }
  } else { return; }

  if($shortonpage){
   include( WPW3ALL_PLUGIN_DIR.'common/wp_phpbb_iframe_shortcode.php' );
  }
}

function w3all_edit_profile_url( $url, $user_id, $scheme ) {
    global $w3all_url_to_cms;
     $url = $w3all_url_to_cms . '/ucp.php?i=ucp_profile&amp;mode=profile_info';
    return $url;
}


 function w3all_on_signup_check( $valid, $username ) {

      if( isset($_POST['signup_username']) && isset($_POST['signup_email']) OR isset($_POST['username']) && isset($_POST['email']) )
      {
         $username = isset($_POST['signup_username']) ? $_POST['signup_username'] : $_POST['username'];
         $email = isset($_POST['signup_email']) ? $_POST['signup_email'] : $_POST['email'];
          $username = trim( $username );
          $email = sanitize_email($email);
          if ( !is_email( $email ) ) {
          echo $message = __( '<h3>Error: email address not valid.</h3><br />', 'wp-w3all-phpbb-integration' ) . '<h4><a href="'.get_edit_user_link().'">' . __( 'Return back', 'wp-w3all-phpbb-integration' ) . '</a><h4>';
           return false;
         }

         $wp_w3_ck_phpbb_ue_exist = WP_w3all_phpbb::ck_phpbb_user($username, $email);
         if(!empty($wp_w3_ck_phpbb_ue_exist))
         {

          if (defined('BP_VERSION')) { // detect if Buddypress installed
           $bp = buddypress();
           $bp->signup->errors['signup_email'] = __( 'Error: Username or email address exists into our forum.', 'wp-w3all-phpbb-integration' );
           return false;
          }

          $error = new WP_Error();
          $error->add( 'invalid_email', 'Error: Username or email address exists into our forum.' );
          return false;

         }
      }

    return $valid;
 }

 function wp_w3all_toolbar_new_phpbbpm( $wp_admin_bar ) {
    global $w3all_phpbb_wptoolbar_pm_yn,$w3all_url_to_cms,$w3all_phpbb_usession;

  if ( !empty($w3all_phpbb_usession) && $w3all_phpbb_wptoolbar_pm_yn == 1 ) {
        if($w3all_phpbb_usession->user_unread_privmsg > 0){
        $hrefmode = $w3all_url_to_cms.'/ucp.php?i=pm&amp;folder=inbox';
        $args_meta = array( 'class' => 'w3all_phpbb_pmn' );
        $args = array(
                'id'    => 'w3all_phpbb_pm',
                'title' => __( 'You have ', 'wp-w3all-phpbb-integration' ) . $w3all_phpbb_usession->user_unread_privmsg .' '. __( 'unread forum PM', 'wp-w3all-phpbb-integration' ),
                'href'  => $hrefmode,
                'meta' => $args_meta );

       $wp_admin_bar->add_node( $args );

     }
  } else { return false; }
}


function wp_w3all_new_phpbbpm_wp_menu_item_push($elemID, $msg='') {
 global $w3all_custom_output_files,$w3all_url_to_cms,$w3all_phpbb_usession;

 if ( is_user_logged_in() ) {
 // NOTE: primary-menu OR THE ID of the UL that contain li menu items
 $elemID = empty($elemID) ? 'wp-admin-bar-my-account' : $elemID;

  if ( !empty($w3all_phpbb_usession) ) {
   if($w3all_phpbb_usession->user_unread_privmsg > 0){

    $w3all_url_to_phpbb_ib = $w3all_url_to_cms . "/ucp.php?i=pm&folder=inbox";

     $s = "<script>
     jQuery(document).ready(function() {
     var msgs = '".__( 'You have ', 'wp-w3all-phpbb-integration' )."' + ".$w3all_phpbb_usession->user_unread_privmsg." + ' ".__( 'unread forum PM', 'wp-w3all-phpbb-integration' )."';
     jQuery('#".$elemID."').after('<li id=\"wp-admin-bar-phpbb-pm\" class=\"menupop\"><a class=\"ab-item\" href=\"".$w3all_url_to_phpbb_ib."\">' + msgs + '</li>');
     });
     </script>
    ";
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
     $password = trim($password);
     $password = stripslashes(htmlspecialchars($password, ENT_COMPAT)); // " do not need to be converted
     $pass = password_hash($password, PASSWORD_BCRYPT,['cost' => 12]); // phpBB min cost 12

 return $pass;

}

endif;
endif;

if ( ! function_exists( 'wp_check_password' ) && ! defined("WPW3ALL_NOT_ULINKED") ) :

function wp_check_password($password, $hash, $user_id = '') {

// wp do not allow char \ on password
// phpBB allow \ char on password

   global $wpdb,$wp_hasher,$w3all_add_into_phpBB_after_confirm;
   $password = trim(str_replace(chr(0), '', $password));
   $check = false;
   $hash_x_wp = $hash;

     $wpu = get_user_by( 'ID', $user_id );

    if(empty($wpu)){
      $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
      $wpu = $wpdb->get_row("SELECT * FROM $wpu_db_utab WHERE ID = '".$user_id."'");
     }

    if( empty($wpu) OR empty($password) OR empty($hash) ){
      return apply_filters( 'check_password', false, $password, $hash, $user_id );
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
 if( $hash && substr($hash, 0, 8) == '$argon2i' ){
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

 if ( $hash && $check !== true && strlen($hash) > 32 && !isset($HArgon2i)){ // Wp check failed, check phpBB pass that's may not Argon2i
    $password = stripslashes($password);
    $password = htmlspecialchars($password, ENT_COMPAT);
    $check = password_verify($password, $hash);
  }

     if ($check === true){

///////////
// check that this user do not need to be added into phpBB, due to $w3all_add_into_phpBB_after_confirm

       if( $w3all_add_into_phpBB_after_confirm == 1 )
       {

      // this is for Ultimate Member plugin, but the logic can be the same for any other plugin
       if(defined( 'um_plugin' )){
          $umeta = get_user_meta($wpu->ID);
         if( isset($umeta['account_status'][0]) && $umeta['account_status'][0] != 'approved' ){
           return;
          }
        }

      // the following can be added earlier into w3all_add_phpbb_user() function, to check for the hash and may autologin the user
        /*if( isset($_GET['hash']) && $_GET['hash'] != $umeta['account_secret_hash'][0] )
         {
           //create user in phpBB, login the user wp (that will setup the phpbb session also)
          }*/

        WP_w3all_phpbb::create_phpBB_user_res($wpu, 'add_u_phpbb_after_login');
       }

        WP_w3all_phpbb::phpBB_user_session_set_res($wpu);
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

 $post_str = trim(str_replace(chr(0), '', $post_str));
 $post_string = preg_replace('/[[\/\!]*?[^\[\]]*?]/', '', $post_str);
 $post_string = strip_tags($post_string);

 $post_s = $post_string;

 $post_string = explode(' ',$post_string);

  if( count($post_string) < $words ) : return $post_s; endif;

 $post_std = ''; $i = 0; $b = $words;

  foreach ($post_string as $post_st) {

    if( $i < $b ){

      $post_std .= $post_st . ' ';
    }
   $i++;
  }

return $post_std;

}


} // END // 2nd // // if ( defined('W3PHPBBDBCONN') && !isset($w3deactivate_wp_w3all_plugin) && !defined( 'WP_ADMIN' ) )

// avoid user additon in wp when registration done into WP front end pages (like mepr membership subscription page)
// wp_pre_insert_user_data wp-includes/user.php
function w3all_wp_pre_insert_user_data($data, $update, $updating_user_id, $userdata){
  global $phpbb_config;

   if(!empty($phpbb_config)){
    $cu = $phpbb_config["cookie_name"].'_u';
   }

  # param $updating_user_id -> null when inserting a new user
  if(empty($updating_user_id) && isset($cu) && intval($_COOKIE[$cu]) < 3)
  {
   if(is_array($userdata) && !empty($userdata['user_email'])){
    $u = WP_w3all_phpbb::ck_phpbb_user($userdata['user_login'], $userdata['user_email']);
   } elseif(is_object($userdata) && !empty($userdata->user_email)){
      $u = WP_w3all_phpbb::ck_phpbb_user($userdata->user_login, $userdata->user_email);
     }

    if( ! empty($u) ){
     $data = '';
     $error = new WP_Error();
     $error->add( 'invalid_email', 'Error: Username or email address exist into our forum.' );
    }
   } /*elseif($updating_user_id > 1){ // updating user
      }*/
   return $data;
}

 function temp_wp_w3_error_on_update($redirect_to = ''){

  if( $redirect_to == 'onlymsg' ){
    echo $message = __( '<h3>Error: the provided email is paired to another user into our forum</h3>', 'wp-w3all-phpbb-integration' );
  } elseif (!empty($redirect_to) && current_user_can( 'manage_options' )){
      echo $message = __( '<h3>Error: username or email already exists into our forum.</h3>', 'wp-w3all-phpbb-integration' ) . '<h4><a href="'.$redirect_to.'">' . __( 'Return back', 'wp-w3all-phpbb-integration' ) . '</a><h4>';
      } else {
        echo $message = __( '<h3>Error: username or email already exists into our forum.</h3>', 'wp-w3all-phpbb-integration' ) . '<h4><a href="'.get_edit_user_link().'">' . __( 'Return back', 'wp-w3all-phpbb-integration' ) . '</a><h4>';
       }
 }

function wp_w3_error($redirect_to='', $message=''){
   if(empty($message)){
        echo $message = __( '<h3>Error: username or email already exists into our forum.</h3>', 'wp-w3all-phpbb-integration' );
      }
}

function w3all_user_profile_update_errors( $array ) {

 // this do not work on frontend plugin like on mepr

 // on front-end pages the check is done by add_filter( 'pre_user_email' (do not work on mepr)
 // and wp_pre_insert_user_data (work on mepr)

 // check for duplicated emails into phpBB
 // Note that this fire after user's email change request fired, if update done on wp profile: so remove and return error, if email update occur
 // and return error any time, any wp profile field updated, like password, if more than one email records found into phpBB. Or the update will occur for all those users with same email in phpBB

 // if there are errors already do not follow
  if(!empty($array->errors) OR !empty($array->error_data)){
   return;
  }

  if(!isset($_POST['user_id'])){ // adding an user in wp-admin, there is no $_POST['user_id'] here
   return;
  }

  global $wpdb;
  $wpu_db_umtab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';
  $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';

  //if ( get_current_user_id() == $_POST['user_id'] ){ // then the changed email is stored as temp email
  if ( $_POST['checkuser_id'] == $_POST['user_id'] OR get_current_user_id() == $_POST['user_id'] ){ // same user updating, then the changed email is stored as temp email
   $uid = intval($_POST['user_id']);
   $wpunewemail = $wpdb->get_row("SELECT * FROM $wpu_db_umtab WHERE user_id = '".$uid."' AND meta_key = '_new_email'");
    if(!empty($wpunewemail)){
     $wpunewemail = unserialize($wpunewemail->meta_value);
     $phpbb_u = WP_w3all_phpbb::ck_phpbb_user( '', $wpunewemail['newemail'] );
    }

   if( !empty($phpbb_u) ){
     delete_user_meta($uid, '_new_email'); // remove new email change request
     $array->add( 'w3_ck_phpbb_duplicated_email_error', __( '<strong>Error</strong>: the email belong to another user into our forum.', 'wp-w3all-phpbb-integration' ) );
    }
    return;
  }

  // if ( get_current_user_id() != $_POST['user_id'] ){ // user editing another user: then the changed email can be checked as $_POST['email']
  if( !empty($_POST['email']) && $_POST['checkuser_id'] != $_POST['user_id'] ) // user editing another user: then the changed email can be checked as $_POST['email']
  {

    if( ! email_exists(sanitize_email($_POST['email'])) ){ // only if the update is going to change the email also, check then

    $phpbb_u = WP_w3all_phpbb::ck_phpbb_user( '', $_POST['email'] );
     if(!empty($phpbb_u))
    {
     wp_w3_error($redirect_to='', $message='');
     exit;
    }
   }
  }

}

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
    if( $i < $b ){ // offset of 1
      $post_std .= $post_st . ' ';
    }
   $i++;
  }

 $post_std .= ' ...';

} else { $post_std = $pcontent; }

$post_std .= $pinfo;

return $post_std;

}


function w3all_add_phpbb_user() {

  // work with the phpBB WordPress extension -> cURL
  // add user into WP, do NOT setup a phpBB session for the just inserted WP user

  global $w3all_add_into_wp_u_capability,$phpbb_config,$w3all_oninsert_wp_user,$wpdb,$wp_w3all_forum_folder_wp;
     if( isset($_REQUEST["w3alladdphpbbuid"]) ){
      $phpbbuid = intval($_REQUEST["w3alladdphpbbuid"]);
      $uemail = str_replace(chr(0), '', $_REQUEST["w3alladdphpbbuemail"]);
      $w3allastoken = trim(str_replace(chr(0), '', $_REQUEST["w3allastoken"]));

      if( $phpbbuid < 3 ){ return; }
     } else { return; }

    if (empty($phpbb_config)){
     $phpbb_config = WP_w3all_phpbb::w3all_get_phpbb_config_res();
    }

   // check that the presented token match

      if(!empty($phpbb_config["avatar_salt"]))
      {
       if( ! password_verify($phpbb_config["avatar_salt"], $w3allastoken) )
       { return; }
      } else { return; }

   $email = sanitize_email($uemail);
   if( is_email($email) ) {
     $user = get_user_by( 'email', $email );
   }

     if( !isset($user) OR !empty($user) ) // existent user into wp, or something else goes wrong
   { return; }

   $phpbb_user = WP_w3all_phpbb::wp_w3all_get_phpbb_user_info_by_email($email);

   if(!isset($phpbb_user[0])){ return; }

   if( $phpbb_user[0]->user_type == 1 ){ // user deactivated in phpBB
    // OR more below**:
    // $role = $phpbb_user[0]->user_type == 1 ? '' : $role;
     echo __('<p style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><strong>Notice: your account is not active into our forum.</strong></p>', 'wp-w3all-phpbb-integration');
      exit;
   }

   $contains_cyrillic = (bool) preg_match('/[\p{Cyrillic}]/u', $phpbb_user[0]->username);

  if ( is_multisite() && !defined('WPW3ALL_USE_DEFAULT_WP_UCHARS') && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$phpbb_user[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user[0]->username) OR strlen($phpbb_user[0]->username) > 50 )
  {

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

   // mums allow only '[0-9a-z]'
   // if do not contain non latin chars, let wp create any wp user_login with this passed username
  if ( is_multisite() && !defined('WPW3ALL_USE_DEFAULT_WP_UCHARS') && preg_match('/[^0-9A-Za-z\p{Cyrillic}]/u',$phpbb_user[0]->username) OR $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$phpbb_user[0]->username) OR strlen($phpbb_user[0]->username) > 50 ){

    if (!defined('WPW3ALL_NOT_ULINKED')){
     define('WPW3ALL_NOT_ULINKED', true);
    }
       // since this function is intented to be executed only when NOT on iframe mode, this should be not required here
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
        } else { // as option is set
                 $role = $w3all_add_into_wp_u_capability;
                }
         //** if deactivated, since the code above, execution never arrive here
          $role = $phpbb_user[0]->user_type == 1 ? array() : $role;

             $userdata = array(
               'user_login'       =>  $phpbb_user[0]->username,
               'user_pass'        =>  $phpbb_user[0]->user_password,
               //'user_email'       =>  $phpbb_user[0]->user_email, // on WP 6.2 the wp_insert_user function, on cULR tests, fail with error "Not enough data provided" when email value provided
               'user_registered'  =>  date_i18n( 'Y-m-d H:i:s', $phpbb_user[0]->user_regdate ),
               'role'             =>  $role
               );

      $userdata = array_map ('trim', $userdata);
      $w3all_oninsert_wp_user = 1;
      $user_id = wp_insert_user( $userdata );

   if ( is_wp_error( $user_id ) ) {
    echo '<div style="padding:30px;background-color:#fff;color:#000;font-size:1.3em"><p>' . $user_id->get_error_message() . '</p></div>';
    echo __('<div><p style="padding:30px;background-color:#fff;color:#000;font-size:1.0em"><strong>Error: try to reload page, but if the error persist may mean that the forum\'s logged in username contains illegal characters that are not allowed on this system. Please contact an administrator.</strong></p></div>', 'wp-w3all-phpbb-integration');
    exit;
   }

  if ( ! is_wp_error( $user_id ) ) {

     $phpbb_username = preg_replace( '/\s+/', ' ', $phpbb_user[0]->username );
     $phpbb_username = esc_sql($phpbb_username);
     $phpbb_user_email = $phpbb_user[0]->user_email;
     $phpbb_user_pass = $phpbb_user[0]->user_password;
     $user_username_clean = sanitize_user( $phpbb_user[0]->username, $strict = false );
     $user_username_clean = esc_sql(mb_strtolower($user_username_clean,'UTF-8'));
     $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
     $wpu_db_umtab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';

    if ($contains_cyrillic) {
       // update user_login and user_nicename and force to be what needed
       // update the pass, since re-hashed by wp_insert_user()
       //$wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbb_username."', user_pass = '".$phpbb_user[0]->user_password."', user_nicename = '".$user_username_clean."', display_name = '".$phpbb_username."' WHERE ID = ".$user_id."");
       $wpdb->query("UPDATE $wpu_db_utab SET user_login = '".$phpbb_username."', user_pass = '".$phpbb_user_pass."', user_nicename = '".$user_username_clean."', user_email = '".$phpbb_user_email."', display_name = '".$phpbb_username."' WHERE ID = ".$user_id."");
       $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
     } else { // leave as is (may cleaned and different) the just created user_login
        $wpdb->query("UPDATE $wpu_db_utab SET user_pass = '".$phpbb_user_pass."', user_email = '".$phpbb_user_email."', display_name = '".$phpbb_username."' WHERE ID = '$user_id'");
        $wpdb->query("UPDATE $wpu_db_umtab SET meta_value = '".$phpbb_username."' WHERE user_id = '$user_id' AND meta_key = 'nickname'");
       }

   }

} // END function w3all_add_phpbb_user()

// let users login with any hash, if integration connection problems occurs and they have been logged out, and hash changed
 if ( !defined('W3PHPBBDBCONN') )
 {

  if ( ! function_exists( 'wp_check_password' ) ) :

function wp_check_password($password, $hash, $user_id = '') {

// wp do not allow char \ on password
// phpBB allow \ char on password

   global $wpdb,$wp_hasher;
   #$password = trim($password);
   $password = trim(str_replace(chr(0), '', $password));
   $check = false;
   $hash_x_wp = $hash;

    if( empty($password) OR empty($hash) ){
      return apply_filters( 'check_password', false, $password, $hash, $user_id );
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
      return apply_filters( 'check_password', $check, $password, $hash, $user_id );
      } else {
      return apply_filters( 'check_password', false, $password, $hash, $user_id );
      }

}

endif;

 }

 function w3all_get_phpbb_onlineStats() {

    global $w3all_config,$phpbb_config,$w3all_phpbb_connection,$phpbb_online_udata;

    if (empty($phpbb_config)){
     $phpbb_config = WP_w3all_phpbb::w3all_get_phpbb_config_res();
    }

   if(!empty($phpbb_online_udata)) { return $phpbb_online_udata; }

   if( $phpbb_config['load_online_time'] > 0 )
   {
    $losTime = time()-($phpbb_config['load_online_time']*60);
    $phpbb_uonline_udata = $w3all_phpbb_connection->get_results("SELECT S.session_id, S.session_user_id, S.session_time AS session_time, S.session_ip, U.user_id, U.username, U.user_email
     FROM ".$w3all_config["table_prefix"]."sessions AS S
     JOIN ".$w3all_config["table_prefix"]."users AS U on U.user_id = S.session_user_id
     WHERE S.session_time > $losTime
     GROUP BY S.session_ip
     ORDER BY U.username",ARRAY_A);
   }
    /*
    // this include sessions from same IP, existing (may) due to same user, navigating the forum with different browsers
      GROUP BY S.session_id
      ORDER BY U.username",ARRAY_A);
    // this way, Guests from same IP should be purged and so it should be done after.
    */

  $phpbb_online_udata = empty($phpbb_uonline_udata) ? array() : $phpbb_uonline_udata;

   return $phpbb_online_udata;

}

#######
# W3ALL WPMS MU START

if( is_multisite() && ! defined("WPW3ALL_NOT_ULINKED") ){

function w3all_wpmu_validate_user_signup( $result ){
 if( empty($result['errors']->errors) ){
  // if no errors on wp side, then check into phpBB and enqueue error, if user/email found
  // this fire before user signup, so to prevent wp user signup/addition and/or errors, if the user already exist into phpBB
    $test =  WP_w3all_phpbb::ck_phpbb_user($result['user_name'], $result['user_email']);
  if( !empty($test) ){
    $errA = array("user_email" => array( "0" => "Error: the username and/or email address already exists, or is associated with another existing user account into our forum database"));
   $result['errors']->errors = $errA;
  }
 }
   return $result;
}

function w3all_wpmu_activate_user_phpbb( $user_id, $password, $meta='' ) {
     global $w3all_phpbb_connection,$w3all_config,$w3all_phpbb_user_deactivated_yn,$w3all_pass_hash_way;

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
      $res = $w3all_phpbb_connection->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_type = '0', user_password = '".$password."' WHERE LOWER(user_email) = '".$user->user_email."'");
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
     // 'if you specify null to the network_id then all site infomation in the network are returned'
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


// admin
add_action( 'init', 'w3all_network_admin_actions' );
function w3all_network_admin_actions() {
 if ( defined( 'WP_ADMIN' ) && current_user_can( 'create_users' ) ){
//add_action( 'wp_insert_site', 'w3all_wpmu_new_blog_by_admin', 10, 6 );
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

} // END if( is_multisite() && ! defined("WPW3ALL_NOT_ULINKED") )


# W3ALL WPMS MU END
#######

#######
# Start About HearthBeat last topics short

 if( !empty($wp_w3all_heartbeat_phpbb_lastopics_pages) && class_exists( 'WP_w3all_phpbb' ) ){
  add_action( 'wp_footer', 'w3all_heartbeat_last_posts', 80);
  add_action( 'init', 'w3all_get_heartbeat', 10 );
  add_filter( 'heartbeat_received', 'w3all_get_WPheartbeat', 10, 2 );
 }


function w3all_get_WPheartbeat( array $response, array $data ) {

 if(!class_exists( 'WP_w3all_phpbb' ) OR is_customize_preview()) return;

     global $w3cookie_domain, $w3all_phpbb_url;
    # If plugin vars are not set
    if(empty($w3all_phpbb_url)) return;

    # Note: strpos($_SERVER['REQUEST_URI'], "admin-ajax.php")
    # /wp-admin/admin-ajax.php exec/fire (also) on the frontend heartbeat
    if( isset($_SERVER['SCRIPT_NAME']) && true === strpos($_SERVER['SCRIPT_NAME'], "/wp-admin")
     OR
     isset($_SERVER['PHP_SELF']) && true === strpos($_SERVER['PHP_SELF'], "/wp-admin")
     OR
     isset($_SERVER['SCRIPT_FILENAME']) && true === strpos($_SERVER['SCRIPT_FILENAME'], "/wp-admin")
    ) { return $response; }

# When the heartbeat occur, all the plugin code execute by the way, so that would be useful to not update contents when there are no new posts?
# Using javascript instead, but this will remain here for future use cases
# **by cookies and wp Option using the phpBB ext
/*
    $w3all_last_phpbb_topics_uptime = get_option('w3all_last_phpbb_topics_uptime');
    $w3all_last_phpbb_topics_uptime = empty($w3all_last_phpbb_topics_uptime) ? 1713262784 : $w3all_last_phpbb_topics_uptime;

    $w3cookie_domain = ($w3cookie_domain == 'localhost' OR $w3cookie_domain == '') ? $w3cookie_domain : '.' . $w3cookie_domain;

    if( !isset($_COOKIE['w3all_last_phpbb_topics_uptime']) OR $w3all_last_phpbb_topics_uptime > intval($_COOKIE['w3all_last_phpbb_topics_uptime']) )
    {
        $cookie_optAry = array (
           'expires' => time() + 129600,
           'path' => '/',
           'domain' => $w3cookie_domain,
           'secure' => true,
           'httponly' => false,
           'samesite' => 'None'
        );

      setcookie('w3all_last_phpbb_topics_uptime', $w3all_last_phpbb_topics_uptime, $cookie_optAry);
    }

    if( isset($_COOKIE['w3all_last_phpbb_topics_uptime']) && $w3all_last_phpbb_topics_uptime > intval($_COOKIE['w3all_last_phpbb_topics_uptime']) )
    {
      $up = WP_w3all_phpbb::wp_w3all_get_phpbb_lastopics_short( $atts = '', $from_hb = true );
    }
*/

   $up = WP_w3all_phpbb::wp_w3all_get_phpbb_lastopics_short( $atts = '', $from_hb = true );

   if(!empty($up)){
    $response['w3all_short_up_last_topicsData'] = json_encode($up);
   }

    return $response;
}

function w3all_heartbeat_last_posts() {

echo '<script>
// going to update the content, only when required
var w3allTestShortPCont = w3execShortPCont = "";
jQuery( document ).on( "heartbeat-send", function ( event, data ) {
  data.w3all_short_up_last_topicsData = "";
});
jQuery( document ).on( "heartbeat-tick", function ( event, data ) {

  if ( ! data.w3all_short_up_last_topicsData || data.w3all_short_up_last_topicsData == "" ) {
   return;
  }

  var posts = JSON.parse(data.w3all_short_up_last_topicsData);

  if(w3allTestShortPCont != posts){
   w3execShortPCont = 1;
   w3allTestShortPCont = posts;
  }

  if( w3execShortPCont == 1 && document.getElementById("w3all_div_last_topics_short_wrapper") !== null && posts != "" )
  {
    document.getElementById("w3all_ul_last_topics_short").remove();
    document.getElementById("w3all_div_last_topics_short_wrapper").insertAdjacentHTML("afterbegin", posts);
    w3execShortPCont = 0;
  }
});

</script>';
}

function w3all_get_heartbeat() {

  if(!class_exists( 'WP_w3all_phpbb' ) OR is_customize_preview()) return;

   # Note:
   # /wp-admin/admin-ajax.php always fire when a frontend heartbeat occur

   # Try to not load when in wp admin
    if( !WP_w3all_phpbb::w3all_ck_if_onpage()
     && !empty($_SERVER['REQUEST_URI']) && false === strpos($_SERVER['REQUEST_URI'], "wp-cron.php")
     && false === strpos($_SERVER['REQUEST_URI'], "admin-ajax.php")
     OR
     !empty($_SERVER['SCRIPT_NAME']) && true === strpos($_SERVER['SCRIPT_NAME'], "/wp-admin")
     OR
     !empty($_SERVER['PHP_SELF']) && true === strpos($_SERVER['PHP_SELF'], "/wp-admin")
     OR
     !empty($_SERVER['SCRIPT_FILENAME']) && true === strpos($_SERVER['SCRIPT_FILENAME'], "/wp-admin")
    ) { return; }

  global $w3cookie_domain, $w3all_phpbb_url;

  # Detect if the plugin is working
  # and check if it is a page containing the short
  if( empty($w3all_phpbb_url) OR !WP_w3all_phpbb::w3all_ck_if_onpage() )
  return;

  wp_enqueue_script('heartbeat');

# Using javascript instead, but this will remain here for future use cases
# To update contents only when there are new posts
# **by cookies and wp Option using the phpBB ext

  /*$w3cookie_domain = ($w3cookie_domain == 'localhost' OR $w3cookie_domain == '') ? $w3cookie_domain : '.' . $w3cookie_domain;

   if( !isset($_COOKIE['w3all_last_phpbb_topics_uptime']) )
   {
          $cookie_optAry = array (
           'expires' => time() + 129600,
           'path' => '/',
           'domain' => $w3cookie_domain,
           'secure' => true,
           'httponly' => false,
           'samesite' => 'None'
        );

     setcookie('w3all_last_phpbb_topics_uptime', time(), $cookie_optAry);
   }*/

}

# End About HearthBeat last topics short
#######