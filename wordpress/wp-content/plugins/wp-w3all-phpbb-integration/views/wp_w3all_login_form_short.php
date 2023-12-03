<?php defined( 'ABSPATH' ) or die( 'forbidden' );

if(is_home()){
$current_url = get_home_url();
} else {
$current_url = get_permalink();
}

if( is_multisite() && empty($current_url) ){
 if ( !function_exists( 'get_current_blog_id' ) ) {
    require_once ABSPATH . WPINC . '/load.php';
 }
  $current_url = get_blogaddress_by_id(get_current_blog_id());
 }

if(!empty($current_url) && !strpos($current_url,'?')){
$current_url .= (substr($current_url, -1) == '/' ? '' : '/'); // fix empty $_POST when action="" url may not contain last slash
} else {
 $current_url = './';
}

$w3all_loginform_style_ul = 'list-style:none;margin:0px';
$w3all_loginform_style_ul_class = 'w3all_ul_widgetLogin';
$w3all_loginform_style_li_class = 'w3all_li_widgetLogin';
?>

 <?php if ( ! is_user_logged_in() ){

 if(!empty($_COOKIE["w3all_set_cmsg"])){
    global $w3cookie_domain;
    if(trim($_COOKIE["w3all_set_cmsg"]) == 'phpbb_ban'){
     echo __('<strong>Notice: your username, IP or email is currently banned into our forum. Please contact an administrator.</strong>', 'wp-w3all-phpbb-integration');
    }
    if(trim($_COOKIE["w3all_set_cmsg"]) == 'phpbb_deactivated'){
     echo __('<strong>Notice: the specified username is currently inactive into our forum. Please contact an administrator.</strong>', 'wp-w3all-phpbb-integration');
    }
    // Remove/empty cookie
    ob_start(); // avoid to output something
     setcookie ("w3all_set_cmsg", "", time() - 2592000, "/", "$w3cookie_domain");
    ob_end_clean();
  }

      if(isset($_REQUEST['reauth']) && $_REQUEST['reauth'] == 1){
        echo __( '<strong style="color:#FF0000">ERROR: Invalid credentials</strong><br /><br />' , 'wp-w3all-phpbb-integration' );
      } elseif(isset($_REQUEST['reauth']) && $_REQUEST['reauth'] == 2){
            echo __( '<strong style="color:#FF0000">ERROR: wrong username, or username contain more than 50 chars</strong><br /><br />' , 'wp-w3all-phpbb-integration' );
      }
      ?>
<form method="post" action="<?php echo $current_url; ?>" class="w3all_login_form">
  <h3><a href="<?php echo wp_registration_url(); ?>"><?php esc_html_e( 'Register' , 'wp-w3all-phpbb-integration' ); ?></a></h3>
      <input style="width:60%;" type="text" tabindex="1" name="w3all_username" id="username" size="10" class="" title="Username" /> <?php esc_html_e( '&nbsp;&nbsp;Username' , 'wp-w3all-phpbb-integration' ); ?>
      <br /><br />
      <input style="width:60%;" type="password" tabindex="2" name="w3all_password" id="password" size="10" class="" title="Password" autocomplete="off" /> <?php esc_html_e( '&nbsp;&nbsp;Password' , 'wp-w3all-phpbb-integration' ); ?>
      <br /><br />
      <?php esc_html_e( 'Remember me' , 'wp-w3all-phpbb-integration' ); ?> <input type="checkbox" tabindex="4" name="autologin" id="autologin" />
      <br /><br />
      <input type="submit" tabindex="5" name="wp-submit" value="<?php esc_html_e( 'Log In' , 'wp-w3all-phpbb-integration' ); ?>" class="" />
      <br /><br />
      <a href="<?php echo wp_lostpassword_url(); ?>"><?php esc_html_e( 'I forgot my password', 'wp-w3all-phpbb-integration' ); ?></a>
      <input type="hidden" name="redirect_to" value="<?php echo $current_url; ?>">

  </form>
  <?php }

if ( is_user_logged_in() && !empty($w3all_phpbb_usession) ){

   // if( $display_phpbb_user_info_yn == 1 ){ // if widget instance option is set to yes
     echo '<ul class="'.$w3all_loginform_style_ul_class.'" style="'.$w3all_loginform_style_ul.'">';
     echo '<li class="'.$w3all_loginform_style_li_class.'">' . get_avatar(get_current_user_id(), $w3all_last_t_avatar_dim) . '</li>';
     echo '<li class="'.$w3all_loginform_style_li_class.'">' . __( 'Hello ' , 'wp-w3all-phpbb-integration' ) . $w3all_phpbb_usession->username . '</li>';
     if($w3all_phpbb_usession->user_unread_privmsg > 0){
      echo '<li class="'.$w3all_loginform_style_li_class.'">' . __( 'You have ' , 'wp-w3all-phpbb-integration' ) . '<a href="'.$w3all_url_to_cms.'/ucp.php?i=pm&amp;folder=inbox">' . $w3all_phpbb_usession->user_unread_privmsg . '</a> ' . __( 'unread forum\'s pm' , 'wp-w3all-phpbb-integration' ) . '</li>';
     }
     echo '<li class="'.$w3all_loginform_style_li_class.'">' . __( 'Forum\'s posts count: ' , 'wp-w3all-phpbb-integration' ) . $w3all_phpbb_usession->user_posts . '</li>';
     echo '<li class="'.$w3all_loginform_style_li_class.'">' . __( 'Registered on: ' , 'wp-w3all-phpbb-integration' ) . date_i18n( 'd M Y', $w3all_phpbb_usession->user_regdate + ( 3600 * get_option( 'gmt_offset' )) ) . '</li>';
     echo '</ul>';
    //}
?>

      <a class="button" href="<?php echo wp_logout_url(); ?>"><?php echo __('Logout' , 'wp-w3all-phpbb-integration' ); ?></a>

<?php }