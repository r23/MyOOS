<?php defined( 'ABSPATH' ) or die( 'forbidden' );
  if ( !current_user_can('manage_options') ) {
    die('<h3>Forbidden</h3>');
   }
 global $w3all_config,$wpdb;

    set_time_limit(300); // set execution time to 5 min "This function has no effect when PHP is running in safe mode. There is no workaround other than turning off safe mode or changing the time limit in the php.ini."

   if ( !defined('W3PHPBBCONFIG') ){
   	die("<h2>Wp w3all miss phpBB db configuration. Set phpBB connection values by opening:<br /><br /> Settings -> WP w3all</h2>");
   }

   $up_conf_w3all_url = admin_url() . 'tools.php?page=wp-w3all-common-tasks';
   $wpu_db_utab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'users' : $wpdb->prefix . 'users';
   $wpu_db_umtab = (is_multisite()) ? WPW3ALL_MAIN_DBPREFIX . 'usermeta' : $wpdb->prefix . 'usermeta';

// w3all_switch_phpbb_uemail form START
 if( !empty($_POST['switch_phpbbu_email']) && !empty($_POST['switch_phpbbu_uname']) )
 {
  if ( is_email(($_POST['switch_phpbbu_email'])) && !empty(trim($_POST['switch_phpbbu_uname'])) && strlen($_POST['switch_phpbbu_uname']) < 255 )
  {

   $phpbb_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();
   $uname = esc_sql(trim($_POST['switch_phpbbu_uname']));

   $_POST['switch_phpbbu_email'] = trim(strtolower($_POST['switch_phpbbu_email']));
   $qr = $phpbb_conn->query("UPDATE ".$w3all_config["table_prefix"]."users SET user_email = '".$_POST['switch_phpbbu_email']."' WHERE username = '".$uname."'");
   if( empty($qr) )
     { $w3warn = '<h4 style="color:#FF0000">Error: email update failed. Seem that this username do not exist into phpBB</h4>';
    } else { $w3warn = '<h4 style="color:green">phpBB user '.$_POST['switch_phpbbu_uname'].' has been updated with email '.$_POST['switch_phpbbu_email'].'</h4>'; }

  } else { $w3warn = '<h4 style="color:#FF0000">Error: one or both username and email fields are wrong</h4>'; }

 } elseif( isset($_POST['switch_phpbbu_email']) && empty($_POST['switch_phpbbu_email']) OR isset($_POST['switch_phpbbu_uname']) && empty($_POST['switch_phpbbu_uname']) )
   {
    $w3warn = '<h4 style="color:#FF0000">Error: both username and email fields are required</h4>';
   }
// w3all_switch_phpbb_uemail form END

// w3all_switch_wp_uemail form START
 if( !empty($_POST['switch_wpu_email']) && !empty($_POST['switch_wpu_uname']) )
 {

  if ( isset($_POST['switch_wpu_email']) && is_email(trim($_POST['switch_wpu_email'])) && !empty($_POST['switch_wpu_uname']) && strlen($_POST['switch_wpu_uname']) < 255 )
  {
   if( !email_exists( $_POST['switch_wpu_email']) )
   {
    $phpbb_conn = WP_w3all_phpbb::wp_w3all_phpbb_conn_init();
    $uname = esc_sql(trim($_POST['switch_wpu_uname']));
    $_POST['switch_wpu_email'] = strtolower(trim($_POST['switch_wpu_email']));
    $qr = $wpdb->query("UPDATE $wpu_db_utab SET user_email = '".$_POST['switch_wpu_email']."' WHERE user_login = '".$uname."'");
    if( empty($qr) )
     { $w3warn0 = '<h4 style="color:#FF0000">Error: email update failed. Seem that this username do not exist into WordPress</h4>';
     } else { $w3warn0 = '<h4 style="color:green">WordPress user '.$_POST['switch_wpu_uname'].' has been updated with email '.$_POST['switch_wpu_email'].'</h4>';
       }
    } else { $w3warn0 = '<h4 style="color:#FF0000">Error: this email already exist into WordPress database</h4>'; ;}
  } else { $w3warn0 = '<h4 style="color:#FF0000">Error: one or both username and email fields are wrong</h4>'; }

 } elseif( isset($_POST['switch_wpu_email']) && empty($_POST['switch_wpu_email']) OR isset($_POST['switch_wpu_uname']) && empty($_POST['switch_wpu_uname']) )
   {
    $w3warn0 = '<h4 style="color:#FF0000">Error: both username and email fields are required</h4>';
   }
// w3all_switch_wp_uemail form END

?>

<div class="" style=""><!-- start main wrapper -->
<h1>WP_W3ALL common tasks phpBB/WordPress</h1>
<br />
<div style="margin:2.0em">
 <h2>Change email only into phpBB by username (not in WP)</h2>
 <h4>Insert a single phpBB username which will be updated to the provided email, only into phpBB</h4>
 <?php if(!empty($w3warn)){ echo $w3warn; } ?>
 <form name="w3all_switch_phpbb_uemail" id="w3all-switch-phpbb-uemail" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
 <p>
  <input type="text" name="switch_phpbbu_uname" value="" /> <strong>phpBB username</strong> that will be updated with this email
  <br /><br />
  <input type="text" name="switch_phpbbu_email" value="" /> <strong>email</strong> to switch to into phpBB
  <br /><br />
 <input type="submit" name="submit" id="submit" class="button button-primary" value="Change phpBB user email">
 </p>
 </form>
</div>

<hr /><hr />

<div style="margin:2.0em">
 <h2>Change email only into WP by username (not in phpBB)</h2>
 <h4>Insert a single WordPress username which will be updated to the provided email, only into WordPress</h4>
 <?php if(!empty($w3warn0)){ echo $w3warn0; } ?>
 <form name="w3all_switch_wp_uemail" id="w3all-switch-wp-uemail" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
 <p>
  <input type="text" name="switch_wpu_uname" value="" /> <strong>WordPress username</strong> that will be updated with this email
  <br /><br />
  <input type="text" name="switch_wpu_email" value="" /> <strong>email</strong> to switch to into WordPress
  <br /><br />
 <input type="submit" name="submit" id="submit" class="button button-primary" value="Change WordPress user email">
 </p>
 </form>
</div>

<hr /><hr />


</div><!-- end main wrapper -->