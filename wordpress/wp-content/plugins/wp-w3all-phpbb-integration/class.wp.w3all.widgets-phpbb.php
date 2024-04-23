<?php
class WP_w3all_widget_login extends WP_Widget
{
  function __construct() {
    parent::__construct(
      'wp_w3all_widget_login',
      'WP phpBB w3all Login',
      array( 'description' => __( 'Display the widget WP phpBB login form' , 'wp-w3all-phpbb-integration') )
    );
  }

public function widget( $args, $instance ) {

    echo $args['before_widget'];
    $display_phpbb_user_info_yn = ! empty( $instance['display_phpbb_user_info'] ) ? $instance['display_phpbb_user_info'] : 0;
    if ( ! empty( $instance['title'] ) ) {
     echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }
     echo self::wp_w3all_to_phpbb_form($display_phpbb_user_info_yn);
    echo $args['after_widget'];
}

public function form( $instance ) {


    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Login', 'wp-w3all-phpbb-integration' );
    $title_logout = ! empty( $instance['title_logout'] ) ? $instance['title_logout'] : __( 'Logout', 'wp-w3all-phpbb-integration' );
    $display_phpbb_user_info = ! empty( $instance['display_phpbb_user_info'] ) ? $instance['display_phpbb_user_info'] : 0;
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Text for login:', 'wp-w3all-phpbb-integration' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
    <label for="<?php echo $this->get_field_id( 'title_logout' ); ?>"><?php _e( 'Text for logout:', 'wp-w3all-phpbb-integration' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title_logout' ); ?>" name="<?php echo $this->get_field_name( 'title_logout' ); ?>" type="text" value="<?php echo esc_attr( $title_logout ); ?>">
    </p>
    <p>
    <label for="<?php echo $this->get_field_id( 'display_phpbb_user_info' ); ?>"><?php _e( 'Display phpBB user\'s info on Login Widget if user logged in?', 'wp-w3all-phpbb-integration'); ?></label>
    <p><label""><input class="widefat" name="<?php echo $this->get_field_name( 'display_phpbb_user_info' ); ?>" id="<?php echo $this->get_field_id( 'post_text' ); ?>" type="radio" value="0" <?php if ( 0 == $display_phpbb_user_info ) echo 'checked="checked"'; ?> /> <?php esc_html_e('No', 'wp-w3all-phpbb-integration'); ?></label></p>
    <p><label""><input class="widefat" name="<?php echo $this->get_field_name( 'display_phpbb_user_info' ); ?>" id="<?php echo $this->get_field_id( 'post_text' ); ?>" type="radio" value="1" <?php if ( 1 == $display_phpbb_user_info ) echo 'checked="checked"'; ?> /> <?php esc_html_e('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>

    </p>
    <?php
}
// substantially this should be renamed, since now the Login Widget do not point anymore to phpBB, but login within WP: see // w3all Login widget check credentials
public function wp_w3all_to_phpbb_form($display_phpbb_user_info_yn = 0) {

      global $w3all_url_to_cms,$w3all_phpbb_usession,$w3all_custom_output_files,$wp,$w3all_last_t_avatar_dim,$w3cookie_domain;

    if ( is_user_logged_in() && !empty($w3all_phpbb_usession) ) { // maintain compatibility with old way for views file
        $phpbb_user_session[0] = $w3all_phpbb_usession;
     }

// see wp_w3all.php
//if(isset($_POST['w3all_username']) && isset($_POST['w3all_password'])){

if( $w3all_custom_output_files == 1 ) { // custom file include
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/wp_w3all_login_form.php';
   if (!file_exists($file)){
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/wp_w3all_login_form.php';// old way
   }
  } else { // default plugin file include
    $file = WPW3ALL_PLUGIN_DIR . 'views/wp_w3all_login_form.php';
  }

  include($file);

}

} // END CLASS



class WP_w3all_widget_last_topics extends WP_Widget
{
  function __construct() {
    parent::__construct(
      'wp_w3all_widget_last_topics',
      __( 'WP phpBB w3all Last Topics' , 'wp-w3all-phpbb-integration'),
      array( 'description' => __( 'Display the widget WP phpBB last forums topics' , 'wp-w3all-phpbb-integration') )
    );
  }

public function widget( $args, $instance ) {

    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    } else {
        echo $args['before_title'] . apply_filters( 'widget_title', 'WP phpBB w3all Last Topics' ). $args['after_title'];
    }
    //$instance['title'] = isset($instance['title']) && !empty($instance['title']) ? $instance['title'] : 'WP phpBB w3all Last Topics';
    $instance['post_text'] = !empty($instance['post_text']) ? $instance['post_text'] : 0; // need 0 or 1
    $instance['topics_number'] = !empty($instance['topics_number']) ? $instance['topics_number'] : 5;
    $instance['post_text_words'] = !empty($instance['post_text_words']) ? $instance['post_text_words'] : 30;

    echo self::wp_w3all_phpbb_last_topics( $instance['post_text'], $instance['topics_number'], $instance['post_text_words'] );
    echo $args['after_widget'];

  }

public function form( $instance ) {
    $instance['title'] = !empty($instance['title']) ? $instance['title'] : __( 'Last Topics Posts', 'wp-w3all-phpbb-integration' );
    $instance['topics_number'] = !empty($instance['topics_number']) ? $instance['topics_number'] : 5;
    $instance['post_text'] = !empty($instance['post_text']) ? $instance['post_text'] : 0;
    $instance['post_text_words'] = !empty($instance['post_text_words']) ? $instance['post_text_words'] : 30;
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
    </p>
      <p>
    <label for="<?php echo $this->get_field_id( 'topics_number' ); ?>"><?php _e( 'Number of last topics to display:', 'wp-w3all-phpbb-integration'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'topics_number' ); ?>" name="<?php echo $this->get_field_name( 'topics_number' ); ?>" type="text" value="<?php echo esc_attr( $instance['topics_number'] ); ?>">
    </p>
      <p>
    <label for="<?php echo $this->get_field_id( 'post_text' ); ?>"><?php _e( 'Display listed topics with post text?', 'wp-w3all-phpbb-integration'); ?></label>
    <p><label""><input class="widefat" name="<?php echo $this->get_field_name( 'post_text' ); ?>" id="<?php echo $this->get_field_id( 'post_text' ); ?>" type="radio" value="0" <?php if ( 0 == $instance['post_text'] ) echo 'checked="checked"'; ?> /> <?php esc_html_e('No', 'wp-w3all-phpbb-integration'); ?></label></p>
    <p><label""><input class="widefat" name="<?php echo $this->get_field_name( 'post_text' ); ?>" id="<?php echo $this->get_field_id( 'post_text' ); ?>" type="radio" value="1" <?php if ( 1 == $instance['post_text'] ) echo 'checked="checked"'; ?> /> <?php esc_html_e('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
    </p>
      <p>
    <label for="<?php echo $this->get_field_id( 'post_text_words' ); ?>"><?php _e( 'Number of text words to display for each listed topic on this widget (affect only if above option is active).', 'wp-w3all-phpbb-integration'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'post_text_words' ); ?>" name="<?php echo $this->get_field_name( 'post_text_words' ); ?>" type="text" value="<?php echo esc_attr( $instance['post_text_words'] ); ?>">
    </p>
    <?php


}
public function update( $new_instance, $old_instance ) {

     $instance = array();
     $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : 'Last Topics Posts';
     $instance['topics_number'] = ( ! empty( $new_instance['topics_number'] ) ) ? strip_tags( $new_instance['topics_number'] ) : '5';
     $instance['post_text'] = ( ! empty( $new_instance['post_text'] ) ) ? strip_tags( $new_instance['post_text'] ) : '0';
     $instance['post_text_words'] = ( ! empty( $new_instance['post_text_words'] ) ) ? strip_tags( $new_instance['post_text_words'] ) : '30';

    return $instance;
  }

public function wp_w3all_phpbb_last_topics($post_text, $topics_number, $text_words) {

  global $w3all_url_to_cms, $wp_w3all_forum_folder_wp, $w3all_get_phpbb_avatar_yn, $w3all_last_t_avatar_yn, $w3all_last_t_avatar_dim, $w3all_phpbb_widget_mark_ru_yn, $w3all_custom_output_files, $w3all_phpbb_widget_FA_mark_yn;
  $topics_number;
  $wp_w3all_post_text = $post_text;
  $wp_w3all_text_words = $text_words;

  if ( $w3all_phpbb_widget_mark_ru_yn == 1 && is_user_logged_in() ) {

    if (defined("W3UNREADTOPICS")){
     $phpbb_unread_topics = unserialize(W3UNREADTOPICS);
    }
   }

   if (defined("W3PHPBBLASTOPICS")){
    $last_topics = unserialize(W3PHPBBLASTOPICS); // see wp_w3all.php
    # re-define and re-call, if topics are more due some sub-sequent instance call, on same page/post
    if(is_array($last_topics) && count($last_topics) < $topics_number)
    { $last_topics = WP_w3all_phpbb::last_forums_topics_res($topics_number); }
   } else {
      $last_topics = WP_w3all_phpbb::last_forums_topics_res($topics_number);
     }

 if(empty($last_topics)){ return; }

  if( $w3all_custom_output_files == 1 ) {
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/phpbb_last_topics.php';
   if (!file_exists($file)){
   $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/phpbb_last_topics.php';// old way
   }
      include($file);
  } else {
     $file = WPW3ALL_PLUGIN_DIR . 'views/phpbb_last_topics.php';
      include( $file );
    }
}

} // END CLASS

class WP_w3all_widget_phpbb_onlineStats extends WP_Widget
{
  function __construct() {
    parent::__construct(
      'wp_w3all_widget_phpbb_onlineStats',
      __( 'WP phpBB w3all phpBB online stats' , 'wp-w3all-phpbb-integration'),
      array( 'description' => __( 'Display phpBB online users and stats' , 'wp-w3all-phpbb-integration') )
    );

   // here, it fire before any init hook: if the widget is active, get the online users data to provide/add for avatars
    if ( is_active_widget( false, false, $this->id_base, true ) ) {
      global $w3all_widget_phpbb_onlineStats_exec;
      $w3all_widget_phpbb_onlineStats_exec = 1;
    }

 }

public function widget( $args, $instance ) {

  global $phpbb_online_udata,$phpbb_config,$w3all_url_to_cms,$w3all_custom_output_files,$w3all_last_t_avatar_dim;
  $guests_num = $reg_num = 0;

 if(!empty($phpbb_online_udata)){
   foreach($phpbb_online_udata as $v){
    if($v['user_id'] < 2){ $guests_num++; } else { $reg_num++; }
   }
 }
  echo $args['before_widget'];

    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    } else {
      echo $args['before_title'] . apply_filters( 'widget_title', 'phpBB online users and stats' ). $args['after_title'];
    }

   $ava_dimension = !empty($instance['ava_dimension']) ? $instance['ava_dimension'] : '';
   $online_ulink_yn = $instance['online_ulink_yn'];
   $ava_or_ulinks = $instance['ava_or_ulinks']; // 0 links - 1 avatars

   if( $w3all_custom_output_files == 1 ) {
    $file = ABSPATH . 'wp-content/plugins/wp-w3all-custom/phpbb_uonline_stats.php';
    if (!file_exists($file)){
    $file = ABSPATH . 'wp-content/plugins/wp-w3all-config/phpbb_uonline_stats.php';// old way
    }
    include($file);
   } else {
      $file = WPW3ALL_PLUGIN_DIR . 'views/phpbb_uonline_stats.php';
      include( $file );
     }

  echo $args['after_widget'];

}

public function form( $instance ) {
    $instance['title'] = !empty($instance['title']) ? $instance['title'] : __( 'phpBB online users and stats', 'wp-w3all-phpbb-integration' );
    $instance['online_ulink_yn'] = !empty($instance['online_ulink_yn']) ? $instance['online_ulink_yn'] : 0;
    $instance['ava_dimension'] = !empty($instance['ava_dimension']) ? $instance['ava_dimension'] : '';
    $instance['ava_or_ulinks'] = !empty($instance['ava_or_ulinks']) ? $instance['ava_or_ulinks'] : 'links';

    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
    </p>
     <p>
    <label for="<?php echo $this->get_field_id( 'ava_or_ulinks' ); ?>"><?php _e( 'Display/list online usernames as text links or avatars', 'wp-w3all-phpbb-integration'); ?></label>
    <p><label""><input class="widefat" name="<?php echo $this->get_field_name( 'ava_or_ulinks' ); ?>" id="<?php echo $this->get_field_id( 'ava_or_ulinks' ); ?>" type="radio" value="links" <?php if ( 'links' == $instance['ava_or_ulinks'] ) echo 'checked="checked"'; ?> /> <?php esc_html_e('Links', 'wp-w3all-phpbb-integration'); ?></label></p>
    <p><label""><input class="widefat" name="<?php echo $this->get_field_name( 'ava_or_ulinks' ); ?>" id="<?php echo $this->get_field_id( 'ava_or_ulinks' ); ?>" type="radio" value="avatars" <?php if ( 'avatars' == $instance['ava_or_ulinks'] ) echo 'checked="checked"'; ?> /> <?php esc_html_e('Avatars', 'wp-w3all-phpbb-integration'); ?></label></p>
    </p>
     <p>
    <label for="<?php echo $this->get_field_id( 'online_ulink_yn' ); ?>"><?php _e( 'Display the username as link that point to the phpBB user profile under avatars:', 'wp-w3all-phpbb-integration'); ?></label>
    <p><label""><input class="widefat" name="<?php echo $this->get_field_name( 'online_ulink_yn' ); ?>" id="<?php echo $this->get_field_id( 'online_ulink_yn' ); ?>" type="radio" value="0" <?php if ( 0 == $instance['online_ulink_yn'] ) echo 'checked="checked"'; ?> /> <?php esc_html_e('No', 'wp-w3all-phpbb-integration'); ?></label></p>
    <p><label""><input class="widefat" name="<?php echo $this->get_field_name( 'online_ulink_yn' ); ?>" id="<?php echo $this->get_field_id( 'online_ulink_yn' ); ?>" type="radio" value="1" <?php if ( 1 == $instance['online_ulink_yn'] ) echo 'checked="checked"'; ?> /> <?php esc_html_e('Yes', 'wp-w3all-phpbb-integration'); ?></label></p>
    </p>
    <p>
    <label for="<?php echo $this->get_field_id( 'ava_dimension' ); ?>"><?php _e( 'Avatar dimension - example 20 (parsed in pixel). If empty the default WP value will be used:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'ava_dimension' ); ?>" name="<?php echo $this->get_field_name( 'ava_dimension' ); ?>" type="text" value="<?php echo esc_attr( $instance['ava_dimension'] ); ?>">
    </p>
    <?php
}
public function update( $new_instance, $old_instance ) {

     $instance = array();
     $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : 'phpBB online users and stats';
     $instance['online_ulink_yn'] = ( intval($new_instance['online_ulink_yn']) > 0 ) ? intval($new_instance['online_ulink_yn']) : 0;
     $instance['ava_or_ulinks'] = ( $new_instance['ava_or_ulinks'] == 'avatars' ) ? 'avatars' : 'links';
     $instance['ava_dimension'] = ( intval($new_instance['ava_dimension']) > 0 ) ? intval($new_instance['ava_dimension']) : '';

    return $instance;
  }

} // END CLASS

function wp_w3all_register_widgets() {
  register_widget( 'WP_w3all_widget_login' );
  register_widget( 'WP_w3all_widget_last_topics' );
  register_widget( 'WP_w3all_widget_phpbb_onlineStats' );
}

if(defined('W3PHPBBDBCONN')){
 add_action( 'widgets_init', 'wp_w3all_register_widgets' );
}
