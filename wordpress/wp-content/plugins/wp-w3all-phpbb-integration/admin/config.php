<?php defined( 'ABSPATH' ) or die( 'forbidden' );

   if ( !current_user_can( 'manage_options' ) ) {
      die('<h3>Forbidden</h3>');
    }

 /*if(isset($_POST['w3all_phpbb_dbconn']))
  {
    //global $w3all_phpbb_dbconn;
   $w3all_phpbb_dbconn['w3all_phpbb_url'] = (!filter_var($_POST['w3all_phpbb_dbconn']['w3all_phpbb_url'], FILTER_VALIDATE_URL)) ? '' : $_POST['w3all_phpbb_dbconn']['w3all_phpbb_url'];
   $w3all_phpbb_dbconn['w3all_phpbb_dbhost'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbhost'];
   $w3all_phpbb_dbconn['w3all_phpbb_dbname'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbname'];
   $w3all_phpbb_dbconn['w3all_phpbb_dbuser'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbuser'];
   $w3all_phpbb_dbconn['w3all_phpbb_dbpasswd'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbpasswd'];
   $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] = $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbtableprefix'];
   $w3all_phpbb_dbconn['w3all_phpbb_dbport'] =  $_POST['w3all_phpbb_dbconn']['w3all_phpbb_dbport'];
   $w3all_phpbb_dbconn['w3all_pass_hash_way'] = intval($_POST['w3all_phpbb_dbconn']['w3all_pass_hash_way']);
   $w3all_phpbb_dbconn['w3all_not_link_phpbb_wp'] = intval($_POST['w3all_phpbb_dbconn']['w3all_not_link_phpbb_wp']);
    update_option('w3all_phpbb_dbconn',$w3all_phpbb_dbconn);
  } else*/if ( defined('PHPBB_INSTALLED') && empty(get_option('w3all_phpbb_dbconn')) )
   {

    $config_file =  get_option( 'w3all_path_to_cms' ) . '/config.php';

   if (file_exists($config_file))
   {
    ob_start();
     include( $config_file );
     if ( defined('WP_W3ALL_MANUAL_CONFIG') )
     { // custom phpBB config
        $phpbbc = array( 'dbhost' => $w3all_dbhost, 'dbport' => $w3all_dbport, 'dbname' => $w3all_dbname, 'dbuser'   => $w3all_dbuser, 'dbpasswd' => $w3all_dbpasswd, 'table_prefix' => $w3all_table_prefix );
     } else
       { // default phpBB config.php
        $phpbbc = array( 'dbhost' => $dbhost, 'dbport' => $dbport, 'dbname' => $dbname, 'dbuser' => $dbuser, 'dbpasswd' => $dbpasswd, 'table_prefix' => $table_prefix );
       }
    ob_end_clean();
   }

    $w3all_phpbb_dbconn['w3all_phpbb_url'] = !empty(get_option('w3all_url_to_cms')) ? get_option('w3all_url_to_cms') : '';
    $w3all_phpbb_dbconn['w3all_phpbb_dbhost'] = isset($phpbbc['dbhost']) ? $phpbbc['dbhost'] : '';
    $w3all_phpbb_dbconn['w3all_phpbb_dbname'] = isset($phpbbc['dbname']) ? $phpbbc['dbname'] : '';
    $w3all_phpbb_dbconn['w3all_phpbb_dbuser'] = isset($phpbbc['dbuser']) ? $phpbbc['dbuser'] : '';
    $w3all_phpbb_dbconn['w3all_phpbb_dbpasswd'] = isset($phpbbc['dbpasswd']) ? $phpbbc['dbpasswd'] : '';
    $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] = isset($phpbbc['table_prefix']) ? $phpbbc['table_prefix'] : '';
    $w3all_phpbb_dbconn['w3all_phpbb_dbport'] = isset($phpbbc['dbport']) ? $phpbbc['dbport'] : '';
    $w3all_phpbb_dbconn['w3all_pass_hash_way'] = !empty(get_option('w3all_pass_hash_way')) ? get_option('w3all_pass_hash_way') : 0;
    $w3all_phpbb_dbconn['w3all_not_link_phpbb_wp'] = !empty(get_option('w3all_not_link_phpbb_wp')) ? get_option('w3all_not_link_phpbb_wp') : 0;
     update_option('w3all_phpbb_dbconn',$w3all_phpbb_dbconn);
   }
    else {
     $w3all_phpbb_dbconn = get_option('w3all_phpbb_dbconn');
    }

  if(empty($w3all_phpbb_dbconn)){
    $colorwarn = 'red;';
  } else { $colorwarn = 'green;'; }

   $w3db_conn = WP_w3all_phpbb::w3all_db_connect_res();

   if( $w3db_conn !== null ){
   	ob_start(); // suppress error if table_prefix is wrong
     $phpBBgroups = $w3db_conn->get_results("SELECT * FROM ". $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] ."groups");
    ob_get_contents();
    ob_end_clean();
   } else { $colorwarn = 'red;'; }

   if(empty($phpBBgroups)){ // presumably then, connection values are ok, but the table prefix is wrong
    $colorwarn = 'red;';
   }

   if(empty($w3all_phpbb_dbconn['w3all_phpbb_url'])){
    $colorwarn = 'red;';
   }

$w3_wp_roles = wp_roles();
$w3wp_roles = isset($w3_wp_roles->role_names) ? $w3_wp_roles->role_names : array();
$current_user = wp_get_current_user();
?>

<div style="background-color:#FFF;margin:0 20px 0 0;display:flex;flex-direction:row-reverse;align-items:center;justify-content:center;">
<h4 style="font-weight:900;padding:2.5em 2.5em 0;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="GUPQNQPZ6V9NG">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" style="border:0;" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" style="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></h4>


<h4 style="font-weight:900;padding:2.5em 2.5em 0;"><a target="_blank"href="https://www.axew3.com/w3/wp-w3all-wordpress-to-phpbb-install-and-how-to/#commonHowto">How To and all Shortcodes list</a></h4>
<h4 style="font-weight:900;padding:2.5em 2.5em 0;"><a target="_blank"href="https://www.axew3.com/w3/wp-w3all-wordpress-to-phpbb-install-and-how-to/">READ - Install Steps, Help and FAQ</a></h4>

<h4 style="padding:2.5em 2.5em 0;font-weight:900"><span style="font-size:150%;color:red;">&hearts;</span> <a href="https://www.paypal.me/alessionanni" target="_blank">Support this Plugin</a> <span style="font-size:150%;color:red;">&hearts;</span></h4>
</div>

<?php
$config_file = get_option('w3all_path_to_cms');
$config_avatars = get_option('w3all_conf_avatars');
$w3all_config_avatars = unserialize($config_avatars);
$w3all_conf_pref = get_option('w3all_conf_pref');
$w3all_conf_pref = empty(trim($w3all_conf_pref)) ? array() : unserialize($w3all_conf_pref);
$w3all_phpbb_dbconn = get_option('w3all_phpbb_dbconn');

$w3all_iframe_phpbb_link = unserialize(get_option('w3all_conf_pref_template_embed_link'));
$w3all_iframe_phpbb_link_yn = isset($w3all_iframe_phpbb_link["w3all_iframe_phpbb_link_yn"]) ? $w3all_iframe_phpbb_link["w3all_iframe_phpbb_link_yn"] : 0;
$w3all_iframe_custom_w3fancyurl = isset($w3all_iframe_phpbb_link["w3all_iframe_custom_w3fancyurl"]) ? $w3all_iframe_phpbb_link["w3all_iframe_custom_w3fancyurl"] : 'w3';
$w3all_iframe_custom_top_gap = isset($w3all_iframe_phpbb_link["w3all_iframe_custom_top_gap"]) ? intval($w3all_iframe_phpbb_link["w3all_iframe_custom_top_gap"]) : '100';

$w3all_bruteblock_phpbbulist_count = empty(get_option('w3all_bruteblock_phpbbulist')) ? array() : count(get_option('w3all_bruteblock_phpbbulist'));

$w3all_config_avatars['w3all_get_phpbb_avatar_yn'] = isset($w3all_config_avatars['w3all_get_phpbb_avatar_yn']) ? $w3all_config_avatars['w3all_get_phpbb_avatar_yn'] : 0;
$w3all_config_avatars['w3all_avatar_on_last_t_yn'] = isset($w3all_config_avatars['w3all_avatar_on_last_t_yn']) ? $w3all_config_avatars['w3all_avatar_on_last_t_yn'] : 0;
$w3all_config_avatars['w3all_lasttopic_avatar_dim'] = isset($w3all_config_avatars['w3all_lasttopic_avatar_dim']) ? $w3all_config_avatars['w3all_lasttopic_avatar_dim'] : 50;
$w3all_config_avatars['w3all_lasttopic_avatar_num'] = isset($w3all_config_avatars['w3all_lasttopic_avatar_num']) ? $w3all_config_avatars['w3all_lasttopic_avatar_num'] : 10;

$w3all_conf_pref['w3all_exclude_phpbb_forums'] = isset($w3all_conf_pref['w3all_exclude_phpbb_forums']) ? $w3all_conf_pref['w3all_exclude_phpbb_forums'] : '';
$w3all_conf_pref['w3all_phpbb_user_deactivated_yn'] = isset($w3all_conf_pref['w3all_phpbb_user_deactivated_yn']) ? $w3all_conf_pref['w3all_phpbb_user_deactivated_yn'] : 0;
$w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn'] = isset($w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn']) ? $w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn'] : 0;
$w3all_conf_pref['w3all_phpbb_widget_FA_mark_yn'] = isset($w3all_conf_pref['w3all_phpbb_widget_FA_mark_yn']) ? $w3all_conf_pref['w3all_phpbb_widget_FA_mark_yn'] : 0;
$w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn'] = isset($w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn']) ? $w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn'] : 0;
$w3all_conf_pref['w3all_transfer_phpbb_yn'] = isset($w3all_conf_pref['w3all_transfer_phpbb_yn']) ? $w3all_conf_pref['w3all_transfer_phpbb_yn'] : 0;
$w3all_conf_pref['w3all_phpbb_lang_switch_yn'] = isset($w3all_conf_pref['w3all_phpbb_lang_switch_yn']) ? $w3all_conf_pref['w3all_phpbb_lang_switch_yn'] : 0;
$w3all_conf_pref['w3all_get_topics_x_ugroup'] = isset($w3all_conf_pref['w3all_get_topics_x_ugroup']) ? $w3all_conf_pref['w3all_get_topics_x_ugroup'] : 0;
$w3all_conf_pref['w3all_custom_output_files'] = isset($w3all_conf_pref['w3all_custom_output_files']) ? $w3all_conf_pref['w3all_custom_output_files'] : 0;
//$w3all_conf_pref['w3all_profile_sync_bp_yn'] = isset($w3all_conf_pref['w3all_profile_sync_bp_yn']) ? $w3all_conf_pref['w3all_profile_sync_bp_yn'] : 0;
$w3all_conf_pref['w3all_add_into_spec_group'] = isset($w3all_conf_pref['w3all_add_into_spec_group']) ? $w3all_conf_pref['w3all_add_into_spec_group'] : 2;
$w3all_conf_pref['w3all_wp_phpbb_lrl_links_switch_yn'] = isset($w3all_conf_pref['w3all_wp_phpbb_lrl_links_switch_yn']) ? $w3all_conf_pref['w3all_wp_phpbb_lrl_links_switch_yn'] : 0;
//$w3all_conf_pref['w3all_phpbb_mchat_get_opt_yn'] = isset($w3all_conf_pref['w3all_phpbb_mchat_get_opt_yn']) ? $w3all_conf_pref['w3all_phpbb_mchat_get_opt_yn'] : 0;
$w3all_conf_pref['w3all_anti_brute_force_yn'] = isset($w3all_conf_pref['w3all_anti_brute_force_yn']) ? $w3all_conf_pref['w3all_anti_brute_force_yn'] : 1;
$w3all_conf_pref['w3all_custom_iframe_yn'] = isset($w3all_conf_pref['w3all_custom_iframe_yn']) ? $w3all_conf_pref['w3all_custom_iframe_yn'] : 0;
$w3all_conf_pref['w3all_add_into_wp_u_capability'] = isset($w3all_conf_pref['w3all_add_into_wp_u_capability']) ? $w3all_conf_pref['w3all_add_into_wp_u_capability'] : 'subscriber';
$w3all_conf_pref['w3all_wp_signup_fix_yn'] = isset($w3all_conf_pref['w3all_wp_signup_fix_yn']) ? $w3all_conf_pref['w3all_wp_signup_fix_yn'] : 0;
$w3all_conf_pref['w3all_add_into_phpBB_after_confirm'] = isset($w3all_conf_pref['w3all_add_into_phpBB_after_confirm']) ? $w3all_conf_pref['w3all_add_into_phpBB_after_confirm'] : 0;
$w3all_conf_pref['w3all_push_new_pass_into_phpbb'] = isset($w3all_conf_pref['w3all_push_new_pass_into_phpbb']) ? $w3all_conf_pref['w3all_push_new_pass_into_phpbb'] : 0;

$w3all_phpbb_dbconn['w3all_phpbb_url'] = isset($w3all_phpbb_dbconn['w3all_phpbb_url']) ? $w3all_phpbb_dbconn['w3all_phpbb_url'] : '';
$w3all_phpbb_dbconn['w3all_phpbb_dbhost'] = isset($w3all_phpbb_dbconn['w3all_phpbb_dbhost']) ? $w3all_phpbb_dbconn['w3all_phpbb_dbhost'] : '';
$w3all_phpbb_dbconn['w3all_phpbb_dbname'] = isset($w3all_phpbb_dbconn['w3all_phpbb_dbname']) ? $w3all_phpbb_dbconn['w3all_phpbb_dbname'] : '';
$w3all_phpbb_dbconn['w3all_phpbb_dbuser'] = isset($w3all_phpbb_dbconn['w3all_phpbb_dbuser']) ? $w3all_phpbb_dbconn['w3all_phpbb_dbuser'] : '';
$w3all_phpbb_dbconn['w3all_phpbb_dbpasswd'] = isset($w3all_phpbb_dbconn['w3all_phpbb_dbpasswd']) ? $w3all_phpbb_dbconn['w3all_phpbb_dbpasswd'] : '';
$w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] = isset($w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix']) ? $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] : '';
$w3all_phpbb_dbconn['w3all_phpbb_dbport'] = isset($w3all_phpbb_dbconn['w3all_phpbb_dbport']) ? $w3all_phpbb_dbconn['w3all_phpbb_dbport'] : '';
$w3all_phpbb_dbconn['w3all_pass_hash_way'] = isset($w3all_phpbb_dbconn['w3all_pass_hash_way']) ? $w3all_phpbb_dbconn['w3all_pass_hash_way'] : 0;
$w3all_phpbb_dbconn['w3all_not_link_phpbb_wp'] = isset($w3all_phpbb_dbconn['w3all_not_link_phpbb_wp']) ? $w3all_phpbb_dbconn['w3all_not_link_phpbb_wp'] : 0;

// reset the option, when/if disabled
 if($w3all_conf_pref['w3all_anti_brute_force_yn'] < 1){
      delete_option( 'w3all_bruteblock_phpbbulist');
  }

 if(isset( $_POST["w3all_conf"]["w3all_path_to_cms"]  ) OR isset( $_POST["w3all_phpbb_dbconn"] )){
  register_uninstall_hook( __FILE__, array( 'WP_w3all_admin', 'clean_up_on_plugin_off' ) );
 }

$up_conf_w3all_url = admin_url() . 'options-general.php?page=wp-w3all-options';

if (isset( $_POST["w3all_conf_pref_template_embed"]["w3all_forum_template_wppage"] ) ){

$w3all_embed_page_name =  get_option( 'w3all_forum_template_wppage' );
$w3all_emb_page = 'page-' . $w3all_embed_page_name . '.php';
$w3all_page_td = get_template_directory() . '/' . $w3all_emb_page;
$w3fpath = WPW3ALL_PLUGIN_DIR . 'addons/page-forum.php';
$w3all_default_template = file_get_contents($w3fpath);
file_put_contents($w3all_page_td, $w3all_default_template);

}

?>



<div class="" style="margin-top:4.0em;margin-right:1.0em;">
<form name="w3all_phpbb_dbconn" id="w3all-phpbb-dbconn" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">

<h1 style="color:green">WP_w3all phpBB db connection and main settings</h1>
<hr />
<h3><?php echo __('Setup phpBB database connection, Url, Password hash and Integration mode config', 'wp-w3all-phpbb-integration');?></h3>

<?php
    if( !defined('W3PHPBBDBCONN') ){
     echo __('<h3 style="color:#ff0000">Before to activate the integration by setting phpBB db connection values it is mandatory to<br /><br /> <a target="_blank" href="https://www.axew3.com/w3/2016/02/configure-phpbb-cookie-all-domain/">setup the correct cookie setting into phpBB</a> (and read the <a target="_blank" href="https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/">Install Help Page</a>)</h3>', 'wp-w3all-phpbb-integration');
     echo __('<h4 style="color:#ff0000">Wp w3all miss phpBB db connection values.</h4>', 'wp-w3all-phpbb-integration');
    }
?>

<button id="w3all_config_b" class="button" style="background-color:<?php echo $colorwarn;?>color:#FFF">phpBB connection and main options</button> &nbsp; <?php if( $colorwarn == 'red;' ){ echo __('<span style="color:red;font-weight:700;">WP_w3all miss phpBB db connection values</span>', 'wp-w3all-phpbb-integration'); } ?>
<br /><br />
<div id="w3allconfigoption" style="padding:5px;margin:0 20px; 0 0">
<script>
jQuery('#w3allconfigoption').hide();
jQuery( "#w3all_config_b" ).click(function(e) {
 e.preventDefault();
jQuery( "#w3allconfigoption" ).toggle();
});
</script>
<?php echo __('<span style="color:red;font-weight:900;">NOTE for developers:</span> beware that <span style="color:red;font-weight:700;">if WordPress is running in debug mode</span>, failing on setting up db connection values here, lead WordPress to end up with a MySQL error warning and no way to update connection settings and get out by this situation (if not accessing db and changing related option values, or disabling the plugin etc).<br />Instead, open the <i>wp-config.php</i> file and temporarily <span style="color:red;font-weight:700;">disable the debug in WordPress</span><br />comment ( prepend with // ) the \'WP_DEBUG\' line<i>&nbsp;&nbsp; // define( \'WP_DEBUG\', true );</i><br />So WordPress will not end up with an error that stop any other WP code execution, when it is in DEBUG mode and a MySQL connection error occur'); ?>
<br /><br />
phpBB real URL: do NOT add final slash and set https or http, the same as WordPress<br /><br />
<input id="w3all_phpbb_url" name="w3all_phpbb_dbconn[w3all_phpbb_url]" type="text" size="35" value="<?php echo esc_attr( $w3all_phpbb_dbconn['w3all_phpbb_url'] ); ?>"> <b><span style="<?php if(empty($w3all_phpbb_dbconn['w3all_phpbb_url'])) {echo 'color:red';}else{echo 'display:none';} ?>"> <?php echo __('(REQUIRED)', 'wp-w3all-phpbb-integration');?></span></b> <?php echo __(' Real phpBB URL - NOTE: do NOT add final slash / &nbsp;&nbsp;Example: https://www.axew3.com/phpbb', 'wp-w3all-phpbb-integration'); ?>
<br /><br /><br />
phpBB database connection values<br /><br />
<input id="w3all_phpbb_dbhost" name="w3all_phpbb_dbconn[w3all_phpbb_dbhost]" type="text" size="35" value="<?php echo esc_attr( $w3all_phpbb_dbconn['w3all_phpbb_dbhost'] ); ?>"> <b><span style="<?php if(empty($w3all_phpbb_dbconn['w3all_phpbb_dbhost'])) echo 'color:red'; ?>"> <?php echo __('(REQUIRED)', 'wp-w3all-phpbb-integration');?></span> Db host</b>
<br /><br />
<input id="w3all_phpbb_dbname" name="w3all_phpbb_dbconn[w3all_phpbb_dbname]" type="text" size="35" value="<?php echo esc_attr( $w3all_phpbb_dbconn['w3all_phpbb_dbname'] ); ?>"> <b><span style="<?php if(empty($w3all_phpbb_dbconn['w3all_phpbb_dbname'])) echo 'color:red'; ?>"> <?php echo __('(REQUIRED)', 'wp-w3all-phpbb-integration');?></span> Db name</b>
<br /><br />
<input id="w3all_phpbb_dbuser" name="w3all_phpbb_dbconn[w3all_phpbb_dbuser]" type="text" size="35" value="<?php echo esc_attr( $w3all_phpbb_dbconn['w3all_phpbb_dbuser'] ); ?>"> <b><span style="<?php if(empty($w3all_phpbb_dbconn['w3all_phpbb_dbuser'])) echo 'color:red'; ?>"> <?php echo __('(REQUIRED)', 'wp-w3all-phpbb-integration');?></span> Db user</b>
<br /><br />
<input id="w3all_phpbb_dbpasswd" name="w3all_phpbb_dbconn[w3all_phpbb_dbpasswd]" type="text" size="35" value="<?php echo esc_attr( $w3all_phpbb_dbconn['w3all_phpbb_dbpasswd'] ); ?>"> <b><span style="<?php if(empty($w3all_phpbb_dbconn['w3all_phpbb_dbpasswd'])) echo 'color:red'; ?>"> <?php echo __('(REQUIRED)', 'wp-w3all-phpbb-integration');?></span> Db password</b>
<br /><br />
<input id="w3all_phpbb_dbtableprefix" name="w3all_phpbb_dbconn[w3all_phpbb_dbtableprefix]" type="text" size="35" value="<?php echo esc_attr( $w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix'] ); ?>"> <b><span style="<?php if(empty($w3all_phpbb_dbconn['w3all_phpbb_dbtableprefix']) OR empty($phpBBgroups) && !defined('W3ALLCONNWRONGPARAMS') ) echo 'color:red'; ?>"> <?php echo __('(REQUIRED)', 'wp-w3all-phpbb-integration');?></span> Db table prefix</b>
<br /><br />
<input id="w3all_phpbb_dbport" name="w3all_phpbb_dbconn[w3all_phpbb_dbport]" type="text" size="35" value="<?php echo esc_attr( $w3all_phpbb_dbconn['w3all_phpbb_dbport'] ); ?>"> <b> Db port (if not default 3306, it is maybe required)</b>
<br /><br />
<h3><?php echo __('Password hash: WordPress or phpBB mode', 'wp-w3all-phpbb-integration');?></h3>
<?php
echo __('Choosing to hash passwords in phpBB mode, when/if integration disabled, WordPress users will have to reset their password to correctly login into WordPress (because maybe (maybe not) the password hash won\'t match). Choosing instead to hash passwords in the WordPress way, phpBB users will have to reset their password to log into phpBB correctly, once integration plugin disabled.<br /><br /><b>Important note: choosing the WordPress password hash, it is mandatory that you let your users log in AND update their password only in WordPress and not in phpBB.</b><br />By using phpBB hashes, users can login both in WordPress and phpBB, since WordPress with the integration plugin active will recognize any phpBB/wordpress hash, while phpBB will not recognize WordPress hashes (if you do not add an extension into phpBB that could do this). <b>phpBB hash password</b> is the default setting (that\'s the default setting since ever)', 'wp-w3all-phpbb-integration');
?>
<p><label""><input type="radio" name="w3all_phpbb_dbconn[w3all_pass_hash_way]" id="w3all_pass_hash_way_1" value="1" <?php checked('1', $w3all_phpbb_dbconn['w3all_pass_hash_way']); ?> /> <?php echo __('<b>WordPress hash password</b> (user\'s log in and password update only in WordPress)', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_phpbb_dbconn[w3all_pass_hash_way]" id="w3all_pass_hash_way_0" value="0" <?php checked('0', $w3all_phpbb_dbconn['w3all_pass_hash_way']); ?> /> <?php echo __('<b>phpBB hash password (default if not set)</b>', 'wp-w3all-phpbb-integration'); ?></label></p>
<hr />
<h3><?php echo __('Activate integration without linking WordPress and phpBB users', 'wp-w3all-phpbb-integration');?></h3>
<?php echo __('In <b><i>not linked users</i></b> mode it is possible to use transfers options and shortcodes or widgets<br />Hints: in <i>not linked users</i> mode you can use the iframe template integration also cross domain. You can also retrieve posts and topics from different domain'); ?>
<p><label""><input type="radio" name="w3all_phpbb_dbconn[w3all_not_link_phpbb_wp]" id="w3all_not_link_phpbb_wp_1" value="1" <?php checked('1', $w3all_phpbb_dbconn['w3all_not_link_phpbb_wp']); ?> /> <?php echo __('<b>Do not</b> link phpBB and WordPress users', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_phpbb_dbconn[w3all_not_link_phpbb_wp]" id="w3all_not_link_phpbb_wp_0" value="0" <?php checked('0', $w3all_phpbb_dbconn['w3all_not_link_phpbb_wp']); ?> /> <?php echo __('<b>Link phpBB and WordPress users (default if not set)', 'wp-w3all-phpbb-integration'); ?></label></p>
<br />
<input type="submit" name="submit" class="button button-primary" value="<?php echo __('Save phpBB db connection and configuration values', 'wp-w3all-phpbb-integration');?>">
<?php wp_nonce_field( 'w3all_phpbbdbconn_nonce', 'w3all_phpbbdbconn_nonce_f' ); ?>
</form>
</div><!-- close <div id="w3all_config_w" -->
<hr /><hr />
</div>


<div class="" style="margin-top:3.0em;margin-right:1.0em;padding:0 10px 0 0">
<form name="w3all_conf_pref" id="w3all-conf-pref" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<h1 style="color:green">WP_w3all users transfer, check options and common tasks</h1>
<hr />
<strong><span style="color:#ff0000">NOTE: IT IS MANDATORY</span></strong> to transfer existent WordPress users into phpBB when integration start<br /><br />
<button id="w3ckoption" class="button">Users transfer, Check options and Common tasks</button>
<br /><br />
<div id="w3all_ck_page" style="padding:5px;margin:0 20px; 0 0">
<script>
jQuery('#w3all_ck_page').hide();
jQuery( "#w3ckoption" ).click(function(e) {
 e.preventDefault();
jQuery( "#w3all_ck_page" ).toggle();
});
</script>
<?php
echo __('it depend on how Cms are configured to run as integrated (where you allow users to update email)<br />but normally all users must exists into both CMS with unique username/email pairs<br />Use the <i>WP w3all check -> List phpBB users with duplicated usernames or emails</i> task<br /><strong style="color:#FF0000">NOTE: IT IS MANDATORY</strong> as explained on the <a target="_blank" href="https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/">Help Install Page</a><br /><strong style="color:#FF0000">to transfer existent WordPress users into phpBB when integration start</strong><br />
  While <strong>it is (may) not mandatory</strong> to transfer phpBB users into WordPress when integration start<br />Run check tasks before to start the integration or use it to check for user\'s problems time after time<br />These options are also visible into WordPress admin under Tools Menu', 'wp-w3all-phpbb-integration'); 

/*if($w3all_conf_pref['w3all_transfer_phpbb_yn'] == 1){
 echo '<a href="' . admin_url() . 'options-general.php?page=wp-w3all-users-to-phpbb">wp-w3all-users-to-phpbb</a><br />';
 echo '<a href="' . admin_url() . 'options-general.php?page=wp-w3all-users-to-wp">wp-w3all-users-to-wp</a><br />';
 echo '<a href="' . admin_url() . 'options-general.php?page=wp-w3all-users-to-phpbb">wp-w3all-users-check</a>';
}*/
 echo '<br /><br /><a href="' . admin_url() . 'tools.php?page=wp-w3all-users-to-phpbb">wp-w3all WordPress users to phpbb</a><br />';
 echo '<a href="' . admin_url() . 'tools.php?page=wp-w3all-users-to-wp">wp-w3all phpBB users to WordPress</a><br />';
 echo '<a href="' . admin_url() . 'tools.php?page=wp-w3all-users-check">wp-w3all users check tasks</a><br />';
 echo '<a href="' . admin_url() . 'tools.php?page=wp-w3all-common-tasks">wp-w3all users common tasks</a>';

?>
</div>
<form name="w3all_conf_pref" id="w3all-conf-pref" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<hr /><hr />
<h1 style="color:green">WP_w3all Preferences</h1>
<hr />
<h3><?php echo __('Add newly WordPress registered users into specified phpBB group', 'wp-w3all-phpbb-integration');?></h3>

<?php
if(isset($phpBBgroups) && !empty($phpBBgroups)):

echo'<b>Exisitent phpBB groups IDs list:</b><br /><br />';

$existentGroups = array();
foreach($phpBBgroups as $k){
  foreach($k as $kk => $v){
   if($kk == 'group_name'){ echo ' &harr; <b>Group Name</b> = ' . str_replace("_", " ", $v); }
   if($kk == 'group_id'){ echo '<b>Group ID</b> = <b style="color:#FF0000">' . $v . '</b>';
     $existentGroups[] = $v;
    }
 }
 echo '<br />';
}

if (! in_array($w3all_conf_pref['w3all_add_into_spec_group'], $existentGroups)) {
   echo __('<br /><span style="color:#FF0000;font-weight:900">WARNING</span>: the default Group in phpBB for newly registered users do not match any existent phpBB group!');
}
?>
<p><input id="w3all_add_into_spec_group" name="w3all_conf_pref[w3all_add_into_spec_group]" type="text" size="10" value="<?php echo $w3all_conf_pref['w3all_add_into_spec_group']; ?>"> <?php echo __('Insert the ID value of the phpBB group where you want new WordPress registered users added (<b>one</b> single integer value allowed)<br />Set one single integer value, that need to be one of the IDs listed in <span style="color:#FF0000">red</span> here above (<b>only one allowed</b>) <b>Correct example: 2</b><br /><b>Note:</b> If not set, users are added by default into the phpBB Group with <b>ID 2</b>, which is the <i>Registered</i> Group ID into a default phpBB installation<br /><b>Note:</b> If the Group ID 2 or the one you go to setup here do not exist in phpBB, then the user in phpBB will result belong to no group at all, then may you\'ll have to add him manually to some existent phpBB Group via phpBB ACP. Be carefull and accurate on setup this setting. Just set as value one of the IDs listed above in <span style="color:#FF0000">red</span>', 'wp-w3all-phpbb-integration');?></p>

<?php endif; // END if(isset($phpBBgroups) && !empty($phpBBgroups)):
?>

<hr />

<?php echo __('<h3>w3all sessions keys Brute Force countermeasure</h3><strong style="color:#FF0000">Note:</strong> do not deactivate/disable this option if you do not really know what it exactly mean</strong><br /><strong style="color:#FF0000">Note -> read this thread to know how a Secure Integration works:</strong> <a target="_blank" href="https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=80&p=320#p320">How to secure WP_w3all phpBB WordPress integration</a><br />Note: to reset/empty data of this option, set to NO and <i>Save WP_w3all Preferences</i>. If array of data will exceed 4000 records, a notice will display here', 'wp-w3all-phpbb-integration');

if ( !empty($w3all_bruteblock_phpbbulist_count) && $w3all_bruteblock_phpbbulist_count > 4000 ){
  echo __('<br /><br /><strong style="color:#FF0000;font-size:140%">Notice:</strong> the Brute Force list contain ', 'wp-w3all-phpbb-integration') . $w3all_bruteblock_phpbbulist_count . __(' records.<br />If you wish to empty/reset to 0 the list, disable the option and click <i>Save WP_w3all Preferences</i> button (then re-enable it)', 'wp-w3all-phpbb-integration');
 }
?>

<p><input type="radio" name="w3all_conf_pref[w3all_anti_brute_force_yn]" id="w3all_anti_brute_force_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_anti_brute_force_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_anti_brute_force_yn]" id="w3all_anti_brute_force_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_anti_brute_force_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>

<hr />

<h3><?php echo __('Add phpBB users into WordPress with specified WordPress capability', 'wp-w3all-phpbb-integration');?></h3>
<?php echo __('WordPress capability you want a new phpBB user added with in WordPress (affect only if you let register users in phpBB). Default <span style="color:#464da0">subscriber</span>', 'wp-w3all-phpbb-integration');?>
<?php
if(!empty($w3wp_roles)):

echo'<br /><br /><b>Available WordPress <span style="color:#464da0">capabilities:</span></b><br /><br />';

foreach($w3wp_roles as $k => $v){
  echo $v . ' &harr; <b style="color:#464da0">' . $k.'</b><br />';
  $existentWPRoles[] = $k;
 }

if (! in_array($w3all_conf_pref['w3all_add_into_wp_u_capability'], $existentWPRoles)) {
   echo __('<br /><span style="color:#FF0000;font-weight:900">WARNING</span>: capability not existent, <span style="color:#464da0">subscriber</span> will be used by default');
}
?>
<p><input id="w3all_add_into_wp_u_capability" name="w3all_conf_pref[w3all_add_into_wp_u_capability]" type="text" size="25" value="<?php echo $w3all_conf_pref['w3all_add_into_wp_u_capability']; ?>"> <?php echo __('Copy/paste here one of the values in <span style="color:#464da0">blue</span> ', 'wp-w3all-phpbb-integration');?></p>

<?php endif; // END if(!empty($w3wp_roles)):
?>

<hr />

<?php echo __('<h3>Force WordPress password reset (front end plugins)</h3>', 'wp-w3all-phpbb-integration'); ?>
<?php echo __('If a frontend plugin bypass the default password reset process, so that this do not let update the new WordPress password at same time into phpBB, force the user\'s password update into phpBB when user login in WordPress<br /><br />This option is may not required into default WordPress installations. Test your installation before to activate', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref[w3all_push_new_pass_into_phpbb]" id="w3all_push_new_pass_into_phpbb_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_push_new_pass_into_phpbb']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_push_new_pass_into_phpbb]" id="w3all_push_new_pass_into_phpbb_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_push_new_pass_into_phpbb']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<h3><?php echo __('Exclude phpBB forums ids to be listed on Last Topics Posts widgets', 'wp-w3all-phpbb-integration');?></h3>
<p><input id="w3all_exclude_phpbb_forums" name="w3all_conf_pref[w3all_exclude_phpbb_forums]" type="text" size="25" value="<?php echo $w3all_conf_pref['w3all_exclude_phpbb_forums']; ?>"> <?php echo __('Comma separated, phpBB forums ID to be excluded from w3all Last Topics Posts widget<br /><b>Note</b>: if string contain a different sequence than <b>NumberCommaNumber</b> the option will not work (or return error inside the front end widget) <b>Correct example: 2,3,7,12,20</b>', 'wp-w3all-phpbb-integration');?></p>
<hr />
<?php echo __('<h3>Retrieve posts on Last Topics Widget based on phpBB user\'s permissions</h3>', 'wp-w3all-phpbb-integration'); ?>
<?php echo __('If some forum require specific permissions to be viewed and the user do not belong to this specific allowed group, posts/topics from these forums are not retrieved to be displayed into Last Topics Widgets', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref[w3all_get_topics_x_ugroup]" id="w3all_get_topics_x_ugroup_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_get_topics_x_ugroup']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_get_topics_x_ugroup]" id="w3all_get_topics_x_ugroup_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_get_topics_x_ugroup']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<?php echo __('<h3>Use custom files to display Last Topics Widgets, Login Widget or Shortcodes content</h3>', 'wp-w3all-phpbb-integration'); ?>
<?php echo __('Files that display widgets output and that reside into folder <i>/wp-content/plugins/wp-w3all-phpbb-integration<b>/views</b></i><br />can be copied/pasted into the custom folder (that you may already manually created, if you choosen to include/use the custom phpBB <i>config.php</i> file):<br /><i>/wp-content/plugins<b>/wp-w3all-config</b></i><br />custom modifications done into these files, aren\'t overwritten when plugin update.<br /><br /><b>NOTE:</b> if you activate this option, <b>it is mandatory</b> that you copy all files inside <i>views</i> folder and paste all files into the custom created <i>/wp-content/plugins/<b>wp-w3all-config</b></i> folder', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref[w3all_custom_output_files]" id="w3all_custom_output_files_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_custom_output_files']); ?> /> <?php echo __('Yes, use custom files copied into <i>/wp-content/plugins/<b>wp-w3all-config</b></i> folder', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_custom_output_files]" id="w3all_custom_output_files_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_custom_output_files']); ?> /> <?php echo __('No, use default folder\'s plugin files', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<?php echo __('<h3>Add users in phpBB only after first successful login in WordPress</h3>', 'wp-w3all-phpbb-integration'); ?>
<?php echo __('Note: if the option below<br />- <i>Deactivate phpBB user account until WP confirmation</i><br />is set to Yes, then the user will be added into phpBB as <i>deactivated</i> and will not be able to login until not activated via admin in phpBB<br /><br /> Note: may activating this option, you\'ll have to allow user\'s logins as mandatory only into WordPress, or the user that try to login in phpBB, will fail until not created in phpBB due to an explicit and successful login into WordPress<br /><br />This option affect/work both default WordPress and WP Multisite installations, where there are signups registrations processes, but also will work for any plugin that use signups processes for user\'s registration into front-end<br /><br />Note that if some frontend plugin, after the user\'s account confirmation action, allow the user\'s autologin via some option, may the autologin after confirmation will fail and the user will have to login by the way', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref[w3all_add_into_phpBB_after_confirm]" id="w3all_add_into_phpBB_after_confirm_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_add_into_phpBB_after_confirm']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_add_into_phpBB_after_confirm]" id="w3all_add_into_phpBB_after_confirm_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_add_into_phpBB_after_confirm']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<?php echo __('<h3>Deactivate phpBB user account until WP confirmation</h3>If this option is set to Yes, users are added in phpBB as <b><i>deactivated</i></b> when they register on WordPress. The phpBB user account will be <b><i>activated</i></b> only after his first login on WordPress. Normally it is not necessary and all will work as expected with users that you want to approve, before to be activated in WP/phpBB, but in case you can force this behavior by setting to yes this option<br /><br />Note: if the option above<br /> - <i>Add users in phpBB only after first successful login in WordPress</i><br />is set to Yes, then the user will be added into phpBB as deactivated and will not be able to login until not activated via admin in phpBB', 'wp-w3all-phpbb-integration');?>
<?php echo __('<br /><!--<span style="text-decoration: line-through;"><b>Note</b>: this work only with default WP registration system where WP send an email link to set first user\'s password, that user do not know at this time. If you installed an external registration plugin that let choose the password to the user on register, than this option may will not affect. If your registration plugin provide option to let choose password or not on register for users, than set no the option, and all here should work as expected about WP/phpBB account confirmation/activation</span>-->', 'wp-w3all-phpbb-integration');?>
<p><input type="radio" name="w3all_conf_pref[w3all_phpbb_user_deactivated_yn]" id="w3all_phpbb_user_deactivated_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_phpbb_user_deactivated_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_phpbb_user_deactivated_yn]" id="w3all_phpbb_user_deactivated_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_phpbb_user_deactivated_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<?php echo __('<h3>Activate notify Read/Unread Topics/Posts into Last Topics widgets </h3>Set to <b>Yes</b>, to notify on Last Topics Widgets if listed topics are <i>read</i> or <i>unread Topics/Posts</i>. This will affect only registered users', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref[w3all_phpbb_widget_mark_ru_yn]" id="w3all_phpbb_widget_mark_ru_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_phpbb_widget_mark_ru_yn]" id="w3all_phpbb_widget_mark_ru_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_phpbb_widget_mark_ru_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<?php echo __('<h3>Activate Font Awesome icons to notify Read/Unread Topics/Posts on Last Topics widgets or shortcodes </h3>If activated, the CSS Font Awesome library will be retrieved from phpBB and included into WordPress: Font Awesome icon will be used to notify about read/unread posts on Last Topics widget or Shortcode', 'wp-w3all-phpbb-integration'); ?>
<br /><b>Note</b>: affect only if the above option "Activate notify Read/Unread Topics/Posts into Last Topics widgets" is active
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_widget_FA_mark_yn]" id="w3all_phpbb_widget_FA_mark_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_phpbb_widget_FA_mark_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
<p><label""><input type="radio" name="w3all_conf_pref[w3all_phpbb_widget_FA_mark_yn]" id="w3all_phpbb_widget_FA_mark_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_phpbb_widget_FA_mark_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></label></p>
<hr />
<?php echo __('<h3>Activate notify Read/Unread Private Messages into Admin Tool Bar </h3>Display new user\'s phpBB Private Messages notification into WP admin user\'s toolbar', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref[w3all_phpbb_wptoolbar_pm_yn]" id="w3all_phpbb_wptoolbar_pm_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_phpbb_wptoolbar_pm_yn]" id="w3all_phpbb_wptoolbar_pm_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_phpbb_wptoolbar_pm_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<?php echo __('<h3>Activate language update/switch on profile for users between WordPress and phpBB</h3>When user change language on profile, it will be so updated also on phpBB/WP. <br /><strong>Note: if same language do not exist</strong> installed also into phpBB, (ex. an user switch on his WP profile to a language available into WordPress, but that has not been installed into phpBB) phpBB may will return error for this user on certain situations (on send out a PM for example)', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref[w3all_phpbb_lang_switch_yn]" id="w3all_phpbb_lang_switch_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_phpbb_lang_switch_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_phpbb_lang_switch_yn]" id="w3all_phpbb_lang_switch_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_phpbb_lang_switch_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<?php echo __('<h3>Swap WordPress default <i>Register and Lost Password</i> links to point to phpBB related pages</h3>Note: option <i>Links for embedded phpBB iframe into WordPress</i> more below, affect this option', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref[w3all_wp_phpbb_lrl_links_switch_yn]" id="w3all_wp_phpbb_lrl_links_switch_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_wp_phpbb_lrl_links_switch_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_wp_phpbb_lrl_links_switch_yn]" id="w3all_wp_phpbb_lrl_links_switch_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_wp_phpbb_lrl_links_switch_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<?php echo __('<h3 style="color:#003333">Activate [w3allcustomiframe] shortcode</h3>Advanced. This shortcode can (also) be used in <span style="color:#003333">Not Linked Users Mode</span>. Read <b>How To use</b> here: <a target="_blank" href="https://www.axew3.com/w3/2019/12/w3allcustomiframe-shortcode-how-to/">[w3allcustomiframe] shortcode how to</a>.<br />Activating this option, the iframe js resizer library will be added/loaded into WordPress header<br />to allow the javascript resizer code work properly on each WordPress page this shorcode will be added', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref[w3all_custom_iframe_yn]" id="w3all_custom_iframe_yn_1" value="1" <?php checked('1', $w3all_conf_pref['w3all_custom_iframe_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref[w3all_custom_iframe_yn]" id="w3all_custom_iframe_yn_0" value="0" <?php checked('0', $w3all_conf_pref['w3all_custom_iframe_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<input type="submit" name="submit" class="button button-primary" value="<?php echo __('Save WP_w3all Preferences', 'wp-w3all-phpbb-integration');?>">
</form>

</div>


<div style="margin-top:4.0em;margin-right:1.0em;">
<form name="w3all_conf_avatars" id="w3all-conf-pref" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<h1 style="color:green">WP_w3all Avatars Options (1.0)</h1>
<hr />
<?php echo __('<h3>Use phpBB avatar to replace WordPress user\'s avatar</h3>If set to Yes, Gravatars profiles images on WordPress, are replaced by phpBB user\'s avatars images, where an avatar image is available in phpBB for the user. Return WP Gravatar of the user, if no avatar image has been found in phpBB (one single fast query to get avatars for all users).
<br /><b>Note</b>: you can activate only this option, if you do not want to display user\'s avatars on WP_w3all Last Forum Topics Widgets, but only on WP posts.
<br /><b>Note: if this option is set to No (not active) others avatar\'s options <i>Last Forums Topics widgets</i> here below, do not affect</b>.
<br />Check that on <i>WordPress Admin -> Settings -> Discussion</i> the setting about avatars is enabled. Check also that it isn\'t set to BLANK this setting (if you do not want really it)', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_avatars[w3all_get_phpbb_avatar_yn]" id="w3all_conf_pref_avatar_1" value="1" <?php checked('1', $w3all_config_avatars['w3all_get_phpbb_avatar_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_avatars[w3all_get_phpbb_avatar_yn]" id="w3all_conf_pref_avatar_0" value="0" <?php checked('0', $w3all_config_avatars['w3all_get_phpbb_avatar_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<div style="padding:20px 35px;background-color:#fff;border-top:2px solid #869eff;border-bottom:2px solid #869eff">
<?php echo __('<h3 style="color:#869eff">Activate phpBB avatars on Last Forums Topics widgets</h3>Add avatars for each user on Last Forums Topics widget', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_avatars[w3all_avatar_on_last_t_yn]" id="w3all_avatar_on_last_t_1" value="1" <?php checked('1', $w3all_config_avatars['w3all_avatar_on_last_t_yn']); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_avatars[w3all_avatar_on_last_t_yn]" id="w3all_avatar_on_last_t_0" value="0" <?php checked('0', $w3all_config_avatars['w3all_avatar_on_last_t_yn']); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<hr />
<?php echo __('<h3>Last Forums Topics Widget avatar\'s dimension</h3>Set the avatar dimension (in pixel) for Last Forum Topics Widget (Ex: 50).<br />Note: affect only if the above <i style="color:#869eff">Activate phpBB avatars on Last Forums Topics widgets</i> option is set to yes', 'wp-w3all-phpbb-integration'); ?>
<p><input id="w3all_lasttopic_avatar_dim" name="w3all_conf_avatars[w3all_lasttopic_avatar_dim]" type="text" size="25" value="<?php echo esc_attr( $w3all_config_avatars['w3all_lasttopic_avatar_dim'] ); ?>"></p>
<hr />
<?php echo __('<h3>Last Forums Topics number of users\'s avatars to retrieve</h3><strong><span style="color:red">Note:</span> if not set, 10 by default, but this value need to be set the same as is the most hight value of topic\'s numbers you choose to display on Last Topics Widgets OR last Topics shortcodes. Example:</strong> if activating different Last Forums Topics shortcodes/widgets, you choose to display 5 topics in one widget or shortcode instance, 15 into another, and 20 topics into another, then set 20 as value here.<br />Note: affect only if the above <i style="color:#869eff">Activate phpBB avatars on Last Forums Topics widgets</i> option is set to yes', 'wp-w3all-phpbb-integration'); ?>
<p><input id="w3all_lasttopic_avatar_num" name="w3all_conf_avatars[w3all_lasttopic_avatar_num]" type="text" size="25" placeholder="10" value="<?php echo esc_attr( $w3all_config_avatars['w3all_lasttopic_avatar_num'] ); ?>"></p>
</div><!-- close <div style="padding:20px 35px -->
<br />
<input type="submit" name="submit" class="button button-primary" value="<?php echo __('Save WP_w3all Avatars Options', 'wp-w3all-phpbb-integration');?>">
<br />
</form>
</div>

<div style="padding:20px 35px;margin-top:4.0em;margin-right:1.0em;background-color:#dcccff;border-top:2px solid #cbb3ff;border-bottom:2px solid #cbb3ff">
<form name="w3all_conf_pref_template_embed" id="w3all-conf-pref-template-embed" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<h1 style="color:green">WP_w3all phpBB embedded on WordPress Template</h1>
<hr style="border-color:gray" />
<?php echo __('<h4 style="color:#333">Before to activate this option, <b><a href="https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/" target="_blank">read this</a></b><br />it is necessary to edit the phpBB overall_footer.html template file, and to add the "iframeResizer.contentWindow.min.js" file into phpBB root folder.
<br />Note: you can completely ignore this part about using iframe mode and use wp_w3all without embed phpBB template in a WP page.</h4><h4>Note that this is totally SEO ok. Using this, DO NOT change/interferes with the way Spiders will index your phpBB Urls (real phpBB Urls)<br /></h4>
<h3>Create or rebuild WordPress forum page template</h3>', 'wp-w3all-phpbb-integration'); ?>
<p><input id="w3all_forum_template_wppage" name="w3all_conf_pref_template_embed[w3all_forum_template_wppage]" type="text" size="25" value="<?php echo get_option('w3all_forum_template_wppage'); ?>"><?php echo __(' This option set the name of (and create) the page template that will embed the phpBB forum iframe on WordPress.<br />It is required to create a new BLANK page on WordPress (WP admin -> Pages -> Add New), with the same title as set here that will contain the embedded iframe phpBB  forum on WordPress. Ex: if you entered "board" as the value you will need to create a new page in wp named board. Open this page after to see your embedded phpbb forum in WP.
<br /><br />The created template file will be located inside your WordPress <b>wp-content/themes/yourtheme</b> template folder. It can be edited as any other WordPress template page.
<br /><br />The template file name to search for, inside the active theme, template directory, can be: <b>page-forum.php</b> or <b>page-board.php</b>, and so on, depending on how you set the value here.<br />
<b>Note:</b> if there is not a created <i>page-forum(or board etc).php</i> file into your active Wp template folder, manually copy it in <i>plugins/wp-w3all-phpbb-integration/addons</i> and paste Or upload into your WP template folder. Rename it as needed if necessary (so into <i>page-board.php</i> if you set <i>board</i> as name here).<br />
<b>Note:</b> the page name here is a required value to be set for iframe mode (as well you need to create a blank page in <i>WP -> pages -> Add New</i>).

<br /><br /><b style="color:#FF0000">Warning (same domain installations)</b>: if your forum folder is located into same WP root directory, like <i>/forum</i> in this case it is required to choose a different name than <i>forum</i> for the template page to be created here. If not, WordPress will point to the existent <i>forum folder</i> that\'s on same directory, and will return <i>content not found</i>.
<br /><br /><b style="color:#FF0000">Warning</b>: Any click on "Create WP_w3all phpBB Page Template" button, will replace the template page with the default content file: the previous created template page if rebuilt with same name will be removed and substituted with the default content file. In case you made modifications to the template page after its his creation, and that you do not want to lose, you should rename or move the template file in some different folder than the theme template folder, before you click on "Create WP_w3all phpBB Page Template" button.', 'wp-w3all-phpbb-integration'); ?>
</p>
<input type="submit" name="submit" class="button button-primary" value="<?php echo __('Create/Rebuild WP_w3all phpBB Page Template', 'wp-w3all-phpbb-integration');?>">
</form>
<!--
</div>
<div style="margin-top:4.0em;">
-->
<form name="w3all_conf_pref_template_embed_link" id="w3all-conf-pref-template-embed-link" action="<?php echo esc_url( $up_conf_w3all_url ); ?>" method="POST">
<h3><?php echo __('Links for embedded phpBB iframe into WordPress', 'wp-w3all-phpbb-integration'); ?></h3>
<?php echo __('Note important: if using the iframe mode and the phpBB JS overall_header.html code applied (that redirect any direct link to phpBB, to the WP page forum, where phpBB iframed display), there is no reason to activate this OBSOLETE option that will be soon removed.<br /><br />Change links for wp_w3all Last Topics Post widgets/shortcodes to point to the WP forum page:<br />if set to Yes, it changes links on <i>Last Topics Posts Widgets/shortcodes</i> that will points to the created WP page that contain the embedded phpBB forum iframe, if set to No it will link to the real phpbb URL/folder.<br /><br /><strong>Note:</strong> to point WordPress Registration, Login and Lost Password links to iframe, activate the option<br /><i>Swap WordPress default Login, Register and Lost Password links to point to phpBB related pages</i><br />more above into preferences options.<br/><br />Changing these settings, do NOT change/interferes the way Spiders will index phpBB Urls (real phpBB Urls)', 'wp-w3all-phpbb-integration'); ?>
<p><input type="radio" name="w3all_conf_pref_template_embed_link[w3all_iframe_phpbb_link_yn]" id="w3all_iframe_phpbb_link_1" value="1" <?php checked('1', $w3all_iframe_phpbb_link_yn); ?> /> <?php echo __('Yes', 'wp-w3all-phpbb-integration'); ?></p>
<p><input type="radio" name="w3all_conf_pref_template_embed_link[w3all_iframe_phpbb_link_yn]" id="w3all_iframe_phpbb_link_0" value="0" <?php checked('0', $w3all_iframe_phpbb_link_yn); ?> /> <?php echo __('No', 'wp-w3all-phpbb-integration'); ?></p>
<h3><?php echo __('Fancy URL query string for the WordPress page forum that embed phpBB', 'wp-w3all-phpbb-integration'); ?></h3>
<p><input id="w3all_iframe_custom_w3fancyurl" name="w3all_conf_pref_template_embed_link[w3all_iframe_custom_w3fancyurl]" type="text" size="25" value="<?php echo $w3all_iframe_custom_w3fancyurl; ?>"> default <i>w3</i></p>
<?php echo __('Change the <i>w3</i> var of the WordPress forum\'s page query URL part<br />If you change the default value <strong><i>w3</i></strong>, then you have to setup the same value as it is here, to match the same into <i>overall_footer.html</i> and <i>overall_header.html</i> javascript code you added, as inline code hints indicate (recompile the phpBB template if you change overall_header.html or overall_footer.html code)<br /><strong>Note: you can not use</strong> as value for the <i>fancy query string</i> any of the <a target="_blank" href="https://codex.wordpress.org/Reserved_Terms">RESERVED WordPress Terms</a><br /><br />Doing this, will change the default (for example):<br />
&nbsp;&nbsp;<i>https://www.mysite.com/forums/?w3=dmlld3RvcGljLnBoc</i><br />into:<br />&nbsp;&nbsp;<i>https://www.mysite.com/forums/?coding=dmlld3RvcGljLnBoc</i><br /> by setting <i>coding</i> as value here, and into the javascript <i>overall_header.html</i> and <i>overall_footer.html</i> code', 'wp-w3all-phpbb-integration'); ?>
<h3><?php echo __('Re-position top gap', 'wp-w3all-phpbb-integration'); ?></h3>
<p><input id="w3all_iframe_custom_top_gap" name="w3all_conf_pref_template_embed_link[w3all_iframe_custom_top_gap]" type="text" size="15" value="<?php echo $w3all_iframe_custom_top_gap; ?>"> default 100. Use an integer</p>
<?php echo __('Change (in pixel) the distance gap where the WordPress page Forum will scroll to by default (useful to fit different theme headers)', 'wp-w3all-phpbb-integration'); ?>

<br /><br />
<input type="submit" name="submit" class="button button-primary" value="<?php echo __('Save Last Topics widgets/shortcodes links, top gap and Fancy Url settings', 'wp-w3all-phpbb-integration');?>">
</form><br /><hr style="border-color:gray" />
</div>

<div style="display: flex;flex-direction: row-reverse;align-items: center;justify-content: center;">
<h4 style="font-weight:900;padding:2.5em 2.5em 0;"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="GUPQNQPZ6V9NG">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" style="border:0;" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" style="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></h4>
<h4 style="font-weight:900;padding:2.5em 2.5em 0;"><a target="_blank"href="https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/">Install Help and all FAQ</a></h4>
<h4 style="font-weight:900;padding:2.5em 2.5em 0;"><a target="_blank"href="https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/#installAndConfig">Install Steps</a></h4>
<h4 style="font-weight:900;padding:2.5em 2.5em 0;"><a target="_blank"href="https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/#commonHowto">Common How To and all Shortcodes list</a></h4>
<h4 style="padding:2.5em 2.5em 0;font-weight:900"><span style="font-size:150%;color:red;">&hearts;</span> <a href="https://www.paypal.me/alessionanni" target="_blank">Support this Plugin</a> <span style="font-size:150%;color:red;">&hearts;</span></h4>
</div>
