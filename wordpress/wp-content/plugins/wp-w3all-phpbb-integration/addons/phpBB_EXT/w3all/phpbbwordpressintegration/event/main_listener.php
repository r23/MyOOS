<?php
/**
 *
 * phpBB WordPress Integration Common Tasks. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, axew3 - axew3.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace w3all\phpbbwordpressintegration\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * phpBB WordPress Integration Common Tasks Event listener.
 */
class main_listener implements EventSubscriberInterface
{
  protected $config;
  protected $user;
  protected $request;
  protected $phpbb_root_path;

  public function __construct(\phpbb\config\config $config, \phpbb\user $user, \phpbb\request\request_interface $request, $phpbb_root_path)
  {
    $this->config = $config;
    $this->user = $user;
    $this->request = $request;
    $this->phpbb_root_path = $phpbb_root_path;
   include_once($this->phpbb_root_path.'ext/w3all/phpbbwordpressintegration/custom/config.php');
    $this->wp_w3all_dbhost = $wp_w3all_dbhost;
    $this->wp_w3all_dbport = $wp_w3all_dbport;
    $this->wp_w3all_dbname = $wp_w3all_dbname;
    $this->wp_w3all_dbuser = $wp_w3all_dbuser;
    $this->wp_w3all_dbpasswd = $wp_w3all_dbpasswd;
    $this->wp_w3all_table_prefix = $wp_w3all_table_prefix;
    $this->wp_w3all_wordpress_url = isset($wp_w3all_wordpress_url) ? $wp_w3all_wordpress_url : '';
    $this->wp_w3all_autologin = false;
   unset($wp_w3all_dbhost, $wp_w3all_dbuser, $wp_w3all_dbpasswd, $wp_w3all_dbname, $wp_w3all_table_prefix);

  }


  public static function getSubscribedEvents()
  {
    return array(
      'core.login_box_redirect' => 'login_box_redirect',
      'core.session_create_after' => 'session_create_after',
      'core.ucp_register_data_after' => 'ucp_register_data_after',
      'core.user_add_modify_data' => 'user_add_modify_data',
      'core.user_add_after' => 'user_add_after',
      'core.ucp_activate_after' => 'ucp_activate_after', // Note: email update/change in WordPress, should instead happen when email successfully confirmed (like WP do)?
      'core.ucp_profile_info_modify_sql_ary' => 'ucp_profile_info_modify_sql_ary',
      'core.ucp_profile_reg_details_validate' => 'ucp_profile_reg_details_validate',
    );
  }

// check that the username or email do not exist into the linked WordPress, before the user addition into phpBB
     public function ucp_register_data_after($e)
  {

   if( empty($e['error']) ) // only if there is not an error already follow check
   {

    if(!defined("W3ALLCKWPUSEREXIST")){
      return;
    } else
     {

      // phpBB configuration should be setup to allow only spaces, a-zA-z0-9 range and _.@ chars, and usernames lenght limit to 50 chars
      // check if it is not
      // max username lenght in wp: 50 , range of allowed usernames chars in wp: '-0-9A-Za-z _.@'
      // NOTE that WP Multisite only allow '0-9A-Za-z' range for usernames (no spaces nor -_.@ chars)

      $contains_cyrillic = (bool) preg_match('/[\p{Cyrillic}]/u', $e['data']['username']);
      if ( $contains_cyrillic && preg_match('/[^-0-9A-Za-z _.@\p{Cyrillic}]/u',$e['data']['username']) OR preg_match('/[^-0-9A-Za-z _.@]/',$e['data']['username']) OR strlen($e['data']['username']) > 50 )
      {
       $e['error'] = [ "0" => 'Username containing illegal characters for the linked CMS. Change the username.' ];
        return;
      } elseif ( !filter_var($e['data']['email'], FILTER_VALIDATE_EMAIL) ) {
       $e['error'] = [ "0" => 'Wrong email format.' ];
        return;
      }

      $db = new \phpbb\db\driver\mysqli();
      $db->sql_connect($this->wp_w3all_dbhost, $this->wp_w3all_dbuser, $this->wp_w3all_dbpasswd, $this->wp_w3all_dbname, $this->wp_w3all_dbport, false, false);
      $sql = "SELECT * FROM ".$this->wp_w3all_table_prefix."users WHERE user_login = '". $db->sql_escape($e['data']['username']) ."' OR user_email = '". $db->sql_escape($e['data']['email']) ."'";
      $result = $db->sql_query($sql);
      $wpu = $db->sql_fetchrow($result);
      $db->sql_freeresult($result);
      $db->sql_close();

       if(!empty($wpu))
       {
         $e['error'] = [ "0" => 'The username or email exists into the linked CMS' ];
       }
     }

   }
  }


// redirect after a phpBB login
     public function login_box_redirect($event)
  {
    global $phpbb_adm_relative_path; // detect (and exclude) redirect if accessing phpBB admin
   if( false === strpos( $this->request->server('REQUEST_URI'), $phpbb_adm_relative_path ) )
   {
     if(defined("W3ALLREDIRECTEVERAFTERLOGIN")){
      header("Location:$this->wp_w3all_wordpress_url"); exit;
     }
    }
  }

// redirect after phpBB session setup
     public function session_create_after($event)
  {
    global $phpbb_adm_relative_path; // detect (and exclude) redirect if accessing phpBB admin
   if( false === strpos( $this->request->server('REQUEST_URI'), $phpbb_adm_relative_path ) )
   {
    if( $this->wp_w3all_autologin === true OR defined("W3ALLREDIRECTEVERAFTERLOGIN") ){
     header("Location:$this->wp_w3all_wordpress_url"); exit;
    }
   }
  }

// if updating when on ucp 'Edit profile'
   public function ucp_profile_info_modify_sql_ary($event)
  {

     if( empty($event['cp_data']['pf_phpbb_website']) OR $this->user->data['user_id'] < 3 )
     {
      return;
     }

    if( ! empty($event['cp_data']['pf_phpbb_website']) )
    {
     $db = new \phpbb\db\driver\mysqli();
     $db->sql_connect($this->wp_w3all_dbhost, $this->wp_w3all_dbuser, $this->wp_w3all_dbpasswd, $this->wp_w3all_dbname, $this->wp_w3all_dbport, false, false);
     $sql = "UPDATE ".$this->wp_w3all_table_prefix."users SET user_url = '". $event['cp_data']['pf_phpbb_website'] ."' WHERE user_email = '". $this->user->data['user_email'] ."'";
     $result = $db->sql_query($sql);
     $db->sql_close();
    }
  }

// if updating when on ucp 'Edit account setting'
   public function ucp_profile_reg_details_validate($event)
  {
     if( !empty($event['error']) OR $this->user->data['user_id'] < 3 ){ // exclude phpBB id2
      return;
     }

    // $user->data['user_email'] = old or actual email
    // $event['data']['email'] = new email or actual email

    if($event['data']['email'] != $this->user->data['user_email'] OR !empty($event['data']['new_password']) && !empty($event['data']['password_confirm']) )
    {

     if(!empty($event['data']['new_password']))
     {
       $new_password = trim($event['data']['new_password']);
       $password = stripslashes(htmlspecialchars($new_password, ENT_COMPAT));
       $new_password = password_hash($password, PASSWORD_BCRYPT,['cost' => 12]); // phpBB min cost 12
       $newpQ = "user_pass = '". $new_password ."',";
      } else {
       $newpQ = '';
     }

     $db = new \phpbb\db\driver\mysqli();
     $db->sql_connect($this->wp_w3all_dbhost, $this->wp_w3all_dbuser, $this->wp_w3all_dbpasswd, $this->wp_w3all_dbname, $this->wp_w3all_dbport, false, false);
     $sql = "UPDATE ".$this->wp_w3all_table_prefix."users SET ".$newpQ." user_email = '". $event['data']['email'] ."' WHERE user_email = '". $this->user->data['user_email'] ."'";
     $result = $db->sql_query($sql);
     $db->sql_close();
    }
   }


   public function user_add_modify_data($e)
  {

    // the user's 'reset_token' db field is not filled into the array of data for the being created new user:
    // so it is added to store a token that then can be checked for a legit WP user insertion
    // or something else. // db field limit 64 chars

    $token = str_shuffle(  bin2hex(random_bytes((rand(10,30)))) . strtoupper(bin2hex(random_bytes((rand(10,20))))) );

    if( strlen($token) > 64 )
    {
     $token = substr($token, -mt_rand(50,64));
    }

    $e['sql_ary'] += [ "reset_token" => $token ];

  }

// if account require activation
     public function ucp_activate_after($e)
  {

     // ACCOUNT_ACTIVE deactivated due to registration // email verification confirmed account activated
     // ACCOUNT_ACTIVE_PROFILE deactivated due to email change on profile // email change/verification confirmed

     // if( $event['message'] == 'ACCOUNT_ACTIVE' OR $event['message'] == 'ACCOUNT_ACTIVE_PROFILE' ){

    if( $e['message'] == 'ACCOUNT_ACTIVE' && !empty($this->wp_w3all_wordpress_url) ){

     if(self::w3_wp_curl($e['user_row']['user_id'], $e['user_row']['user_email'], $this->wp_w3all_wordpress_url, $this->config['avatar_salt'], $this->user->data['reset_token'] ) === true)
     { // disabled: redirect after session setup instead
      if( defined("W3ALLREDIRECTUAFTERADD") )
      {
      //$domain = (!$this->config['cookie_domain'] || $this->config['cookie_domain'] == '127.0.0.1' || strpos($this->config['cookie_domain'], '.') === false) ? '' : $this->config['cookie_domain'];
      //setcookie('W3ALLTOKENLOGINPHPBBWP', $e['user_row']['reset_token'], 0, '/', $this->config['cookie_domain']);
      // header("Location:$this->wp_w3all_wordpress_url");
      // exit;
      //$this->wp_w3all_autologin = true;
      }
     }
    }

  }

// no activation required, immediate access
     public function user_add_after($e)
  {

    // $e['user_row']['user_inactive_reason'] != 0 // the new account require email verification
    // $e['user_id'] // user id

    if( $e['user_row']['user_new'] != 1 OR $e['user_row']['user_inactive_reason'] != 0 )
    {
      return; // user to be added after email confirmation
    }

    if( $e['user_row']['user_inactive_reason'] == 0 && $e['user_row']['user_new'] == 1 && !empty($this->wp_w3all_wordpress_url) )
    {
      if(self::w3_wp_curl($e['user_id'], $e['user_row']['user_email'], $this->wp_w3all_wordpress_url, $this->config['avatar_salt'], $this->user->data['reset_token']) === true)
      {
       if( defined("W3ALLREDIRECTUAFTERADD") )
       {
       //$domain = (!$this->config['cookie_domain'] || $this->config['cookie_domain'] == '127.0.0.1' || strpos($this->config['cookie_domain'], '.') === false) ? '' : $this->config['cookie_domain'];
       //setcookie('W3ALLTOKENLOGINPHPBBWP', $e['user_row']['reset_token'], 0, '/', $this->config['cookie_domain']);
       //  header("Location:$this->wp_w3all_wordpress_url"); exit;
        $this->wp_w3all_autologin = true;
       }
      }
    }

  }


     public static function w3_wp_curl($uid, $email, $url = '', $avatar_salt = '', $ureset_token = '')
  {
    if(empty($url)){
      return; // Disable the user addition into wordpress, the extension used/run only for email, pass and url update
    }

   if( !in_array('curl', get_loaded_extensions()) ) {
    return; // cURL not available
   }

    if(!empty($avatar_salt)){
     $tk = stripslashes(htmlspecialchars($avatar_salt, ENT_COMPAT));
     $w3allastoken = password_hash($tk, PASSWORD_BCRYPT,['cost' => 12]);
    } elseif (!empty($ureset_token)){
      $w3allastoken = $ureset_token; // not used because require a query in wp to retrieve user's data for the check: the previous is faster because phpBB config values are already available into WP
     } else { return false; }

      $data = array( 'w3alladdphpbbuid' => $uid, 'w3alladdphpbbuemail' => $email, 'w3allastoken' => $w3allastoken );
      $data = http_build_query($data);
       $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if(curl_exec($ch) === false){
      curl_close ($ch);
     return false;
    } else {
       curl_close ($ch);
      return true;
    }
  }

}
