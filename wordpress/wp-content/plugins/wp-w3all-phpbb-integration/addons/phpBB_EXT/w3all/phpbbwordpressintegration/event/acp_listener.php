<?php
/**
 *
 * phpBB WordPress Integration Common Tasks. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2022, axew3, axew3.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace w3all\phpbbwordpressintegration\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * ACP Event listener
 */
class acp_listener implements EventSubscriberInterface
{

	/** @var string $phpbb_root_path phpBB root path */
  protected $request;	
	protected $phpbb_root_path;


/*	public function __construct(\phpbb\config\config $config, \phpbb\user $user, $phpbb_root_path)
	{
		$this->config = $config;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
	}*/

		public function __construct(\phpbb\request\request_interface $request,$phpbb_root_path)
	{
		$this->request = $request;
		$this->phpbb_root_path = $phpbb_root_path;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getSubscribedEvents()
	{
		return array(
	    'core.acp_users_overview_modify_data' => 'acp_users_overview_modify_data',
	    'core.acp_users_profile_modify_sql_ary' => 'acp_users_profile_modify_sql_ary',
		);
	}

	public function acp_users_overview_modify_data($event)
	{

	 // $event['user_row']['user_email'] = old or actual email
   // $event['data']['email'] = new email or actual email

   // if there is some form error, like pass mismatch
   // phpBB return error before this event, so the follow will never fire

   if($event['data']['email'] != $event['user_row']['user_email'] OR !empty($event['data']['new_password']) && !empty($event['data']['password_confirm']))
   {

	 	include($this->phpbb_root_path.'ext/w3all/phpbbwordpressintegration/custom/config.php');

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
    $db->sql_connect($wp_w3all_dbhost, $wp_w3all_dbuser, $wp_w3all_dbpasswd, $wp_w3all_dbname, $wp_w3all_dbport, false, false);
		$sql = "UPDATE ".$wp_w3all_table_prefix."users SET ".$newpQ." user_email = '". $event['data']['email'] ."' WHERE user_email = '". $event['user_row']['user_email'] ."'";
		$result = $db->sql_query($sql);
		$db->sql_close();
		unset($wp_w3all_dbhost, $wp_w3all_dbuser, $wp_w3all_dbpasswd, $wp_w3all_dbname);
	 }

	}


		public function acp_users_profile_modify_sql_ary($e)
	{	
    include($this->phpbb_root_path.'ext/w3all/phpbbwordpressintegration/custom/config.php');
    $db = new \phpbb\db\driver\mysqli();
    $db->sql_connect($wp_w3all_dbhost, $wp_w3all_dbuser, $wp_w3all_dbpasswd, $wp_w3all_dbname, $wp_w3all_dbport, false, false);
		$sql = "UPDATE ".$wp_w3all_table_prefix."users SET user_url = '". $e['cp_data']['pf_phpbb_website'] ."' WHERE user_email = '". $e['user_row']['user_email'] ."'";
		$result = $db->sql_query($sql);
		$db->sql_close();
		unset($wp_w3all_dbhost, $wp_w3all_dbuser, $wp_w3all_dbpasswd, $wp_w3all_dbname);
	}

}
