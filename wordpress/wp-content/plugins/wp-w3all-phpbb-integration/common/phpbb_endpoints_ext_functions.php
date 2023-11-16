<?php defined( 'ABSPATH' ) or die( 'forbidden' );
// to understand these, see phpBB/ext/w3all/phpbbwordpressintegration/event/main_listener.php
// included into wp_w3all.php only if option/s active
// list of hooks for endpoints in wp_w3all.php:
 // add_action( 'delete_user', 'w3all_usersdata_predelete_in_phpbb_exec', 10, 3);
 // add_action( 'deleted_user', 'w3all_usersdata_deleted_in_phpbb_exec', 10, 3);

 function w3all_usersdata_predelete_in_phpbb_exec($id, $reassign, $user)
 {
    if ( ! current_user_can( 'delete_users' ) ) { return; }

    global $wpdb,$w3all_wpusers_delete_ary_once,$w3all_url_to_cms;

  if( $w3all_wpusers_delete_ary_once < 1 && isset($_REQUEST['action']) && $_REQUEST['action'] == 'dodelete' )
   {
     $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';

    if(isset($_REQUEST['user'])) // single user delete WP ADMIN
    {
      $emailist = $wpdb->get_var( "SELECT user_email FROM ".$wpu_db_utab." WHERE ID = '".intval($_REQUEST['user'])."'" );
      if(!empty($emailist)){
       $emailist = ['0' => $emailist];
      }
    } elseif ( isset($_REQUEST['users']) ) // bulk users delete WP ADMIN
      {
        $uids = ''; $_REQUEST['users'] = array_map('intval',$_REQUEST['users']);
        foreach ( $_REQUEST['users'] as $u => $v ){
         $uids .= $v.',';
        }
        $uids = substr($uids, 0, -1);
        $emailist = $wpdb->get_results( "SELECT user_email FROM ".$wpu_db_utab." WHERE ID IN(".$uids.")" );

        if(!empty($emailist)){
         $emailist = array_column($emailist, 'user_email');
        }
      }

     if( count($emailist) > 25 ) // max 25 users per time
     {
       echo __('<h4>ERROR: too much users to be deleted per time (max 25). Return back and disable the option you activated that allow to delete users in phpBB (if you wish to delete more than 25 users per time) into the WP_w3all integration plugin admin page. Remember deativating the option: in this case, deleted users in WP, are deactivated in phpBB and not deleted<.</h4>', 'wp-w3all-phpbb-integration');
       exit;
     }

     if(! empty($emailist) )
     {
         // random bytes
       if (!function_exists('random_bytes')) {
        $rbytes = substr(md5(uniqid('', true)), mt_rand(3,15));
       } else { $rbytes = substr(bin2hex(random_bytes(rand(15,20))), mt_rand(3,10)); }
         // append a time nonce and random bytes
        $w3all_nonce_reqtime_rand = time().'___'.$rbytes;
        $emailist[] = ['w3all_nonce_reqtime_rand' => $w3all_nonce_reqtime_rand];

       update_user_meta( get_current_user_id(), 'w3all_wpdelete_phpbbulist_delby', $emailist);
     } else { return; }

     $phpbb_config = WP_w3all_phpbb::w3all_get_phpbb_config_res();
     $phpbb_config = W3PHPBBCONFIG;

     $tk = stripslashes(htmlspecialchars($phpbb_config["avatar_salt"], ENT_COMPAT));
     $tk = substr(md5($tk), 4, -8);
     $tk0 = substr(strtoupper(md5($tk)), 0, -16);
     $tk .= $tk0;
     $w3allastoken = password_hash($tk, PASSWORD_BCRYPT,['cost' => 12]);

     // phpBB Mode of posts deletion (retain|remove)
     // WP (int|null)
     $reassign_m = $reassign > 0 ? 'retain' : 'remove';
     $w3all_phpBB_function_endpoint_ck_randvar = 'w3all_phpBB_function_endpoint___'.$tk0;
     $data = array( $w3all_phpBB_function_endpoint_ck_randvar => 'w3all_phpBB_function_endpoint_ck_randvar', 'w3all_phpBB_function_endpoint' => 'user_delete', 'w3all_wpdelete_phpbbulist_delby' => get_current_user_id(), 'w3allastoken' => $w3allastoken, 'w3all_delmode' => $reassign_m, 'w3all_nonce_reqtime_rand' => $w3all_nonce_reqtime_rand );
     $data = http_build_query($data);
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL,$w3all_url_to_cms.'/index.php');
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

     if(curl_exec($ch) === false){
      curl_close ($ch);
       echo __('<h4>ERROR: cannot cURL the linked phpBB to delete users. Return back and disable the option that allow to delete users in phpBB, into the WP_w3all integration plugin admin page.</h4>', 'wp-w3all-phpbb-integration');
       exit;
       return false;
     } else {
     // since WP delete, fire for each deleted user this hook
     $w3all_wpusers_delete_ary_once = 1; // to run only once
     }

    }
 }

  function w3all_usersdata_deleted_in_phpbb_exec($id, $reassign, $user)
  {
    global $wpdb,$w3all_wpusers_delete_ary_once;

    if ( ! current_user_can( 'delete_users' ) OR $w3all_wpusers_delete_ary_once == 1 )
    { return; }
     // remove all, just after phpBB retrieved the necessary after first delete in WP occur
     // could be: empty this with a query from/in phpBB, as soon the $_POST received and data retrieved
     update_user_meta( get_current_user_id(), 'w3all_wpdelete_phpbbulist_delby', 'done'); // empty the meta field, it is like an 'auto nonce'
     $w3all_wpusers_delete_ary_once++;
  }
