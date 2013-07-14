<?php

class wpbb_users_widget extends WP_Widget
{
    function wpbb_users_widget()
    {
        $widget_ops = array(
            'classname' => 'phpBB3 Users Widget',
            'description' => __('Use that widget to allow user login on phpBB and WordPress from the WordPress side', 'wpbb')
        );
        
        $control_ops = array(
            'width' => 250,
            'height' => 250,
            'id_base' => 'phpbb3-users-widget'
        );
        
        /* Create the widget. */
        $this->WP_Widget(
            'phpbb3-users-widget', 
            'WP phpBB Bridge ' . __('Users', 'wpbb'), 
            $widget_ops, 
            $control_ops
        );
    }
        
    function form($instance)
    {
		$defaults = array(
            'wpbb_user_login_title' => __('Login', 'wpbb'),
            'wpbb_user_info_title' => '{USERNAME}',
            'wpbb_user_show_meta' => 'yes'
        );
        
        $instance = wp_parse_args(
                        (array)$instance,
                        $defaults
                    );
        
        ?>
        <div class="widget-content">
            <p>
                <label for="<?php echo $this->get_field_id('wpbb_user_login_title'); ?>">
                    <?php _e('Login box title:', 'wpbb'); ?>
                </label>
                <input class="widefat" id="<?php echo $this->get_field_id('wpbb_user_login_title') ?>" name="<?php echo $this->get_field_name('wpbb_user_login_title') ?>" type="text" value="<?php echo $instance['wpbb_user_login_title']; ?>" />
                <span class="description">
                    <?php
                        _e('The widget title before the user login', 'wpbb');
                    ?>
                </span>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('wpbb_user_info_title'); ?>">
                    <?php _e('User info title:', 'wpbb'); ?>
                </label>
                <input class="widefat" id="<?php echo $this->get_field_id('wpbb_user_info_title') ?>" name="<?php echo $this->get_field_name('wpbb_user_info_title') ?>" type="text" value="<?php echo $instance['wpbb_user_info_title']; ?>" />
                <span class="description">
                    <?php
                        _e('The widget title after the user login.<br /><br /><strong>NOTE</strong>: You can use the keyword {USERNAME} to display the current username. In exmple: Welcome {USERNAME}', 'wpbb');
                    ?>
                </span>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('wpbb_user_show_meta'); ?>">
                    <input type="checkbox" id="<?php echo $this->get_field_id('wpbb_user_show_meta'); ?>" name="<?php echo $this->get_field_name('wpbb_user_show_meta'); ?>" value="yes" <?php echo $instance['wpbb_user_show_meta'] == "yes" ? 'checked="checked"' : ''; ?> /> <?php _e('Display user meta info', 'wpbb'); ?>
                </label>
            </p>
        </div>
        <?php
	}

	function update($new_instance, $old_instance)
    {
		$instance = $old_instance;
        
        $instance['wpbb_user_login_title'] = $new_instance['wpbb_user_login_title'];
        $instance['wpbb_user_info_title'] = $new_instance['wpbb_user_info_title'];
        $instance['wpbb_user_show_meta'] = $new_instance['wpbb_user_show_meta'];
        
        return $instance;
	}

	function widget($args, $instance)
    {
        if(get_option('wpbb_activate', 'no') == 'no')
        {
            return;
        }
        
		global $user, $auth;
        
        extract($args);
        
        $login_title = $instance['wpbb_user_login_title'];
    	$info_title = $instance['wpbb_user_info_title'];
    	$meta_links = $instance['wpbb_user_show_meta'];
        
        $user_status = wpbb_is_user_logged_in();
        
        $title = '';
        
        if($user_status == false)
        {
            $title = $login_title;
        }
        else
        {
            $title = $info_title;
        }
        
        $title = str_replace('{USERNAME}', trim($user->data['username']), $title);
        
        $ucp_url = trim(get_option('wpbb_ucp_path'));
        
        echo $before_widget . $before_title . $title . $after_title;
        
        if($user_status == false)
        {
        	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        	$meta_links = $instance['wpbb_user_show_meta'];
            
            ?>
                <form action="<?php echo $ucp_url; ?>?mode=login" method="post" class="wp_phpbb_bridge_login" id="login">
                    <?php
                    
                        if(get_option('wpbb_width', '0') !== '0')
                        {
                            
                    ?>
                        <input type="hidden" name="wpbb_elements_width" id="wpbb_elements_width" value="<?php echo get_option('wpbb_width'); ?>" />
                    <?php
                    
                        }
                        
                    ?>
                    <label for="username">
                        <?php 
                            echo _e('Username:', 'wpbb'); 
                        ?>
                    </label>
                    <br />
                    <input type="text" name="username" id="wpbb_username" />
                    <br />
                    <label for="password">
                        <?php 
                            echo _e('Password:', 'wpbb'); 
                        ?>
                    </label>
                    <br /> 
                    <input type="password" id="wpbb_password" name="password" />
                    <br />
                    <label for="autologin">
                        <input type="checkbox" name="autologin" id="autologin" /> 
                        <?php 
                            echo _e('Remember me', 'wpbb'); 
                        ?>
                    </label>
                    <br />
                    <label for="viewonline">
                        <input type="checkbox" name="viewonline" id="viewonline" /> 
                        <?php 
                            echo _e('Login as hidden', 'wpbb'); 
                        ?>
                    </label>
                    <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                    <br />
                	<input type="submit" name="login" id="wpbb_login" value="<?php echo _e('Login', 'wpbb'); ?>" />
                    <?php 
                        if($meta_links == "yes")
                        { 
                    ?>
                        <br />
                        <a href="<?php echo $ucp_url; ?>?mode=sendpassword">
                            <?php 
                                echo _e('I forgot my password', 'wpbb'); 
                            ?>
                        </a>
                        <br />
                        <a href="<?php echo $ucp_url; ?>?mode=register">
                            <?php 
                                echo _e('Register new account', 'wpbb'); 
                            ?>
                        </a>
                    <?php 
                        } 
                    ?>
                </form>
            <?php
        }
        else
        {
            $avatar = wpbb_get_avatar();
        	$forum_url = str_replace("/ucp.php", "/", $ucp_url);
        	$admin_url = wpbb_get_admin_link();
            $mcp_url = wpbb_get_mcp_link();
        	$options = get_option('widget_wpb_user');
        	$meta_links = $instance['wpbb_user_show_meta'];
            
            if($avatar)
            {
                ?>
                    <a href="<?php echo $ucp_url; ?>" title="<?php _e('User control panel', 'wpbb'); ?>">
                        <img src="<?php echo $avatar ?>" alt="<?php printf(__('Avatar for %s'),$user->data['username']); ?>" />
                    </a>
                <?php
            }
            
            ?>
                <ul id="wp_phpbb_bridge_options">
                    <li>
                        <?php _e('Total posts', 'wpbb'); ?> : 
                        <a href="<?php echo $forum_url; ?>search.php?search_id=egosearch">
                            <?php
                                echo number_format($user->data['user_posts']);
                            ?>
                        </a>
                    </li>
                    <li>
                        <?php _e("New PM's", 'wpbb'); ?> : 
                        <a href="<?php echo $ucp_url; ?>?i=pm&amp;folder=inbox">
                            <?php
                                echo number_format($user->data['user_new_privmsg']);
                            ?>
                        </a>
                    </li>
                    <li>
                        <?php _e("Unread PMs", 'wpbb'); ?> : 
                        <a href="<?php echo $ucp_url; ?>?i=pm&amp;folder=inbox">
                            <?php
                                echo number_format($user->data['user_unread_privmsg']);
                            ?>
                        </a>
                    </li>
                    <?php
                        if($meta_links == "yes")
                        {
                            if(!empty($admin_url))
                            {
                                ?>
                                    <li> 
                                        <a href="<?php echo $admin_url; ?>">
                                            <?php
                                                _e('Forum administration', 'wpbb');
                                            ?>
                                        </a>
                                    </li>
                                <?php
                            }
                        }
                        
                        if(wpbb_is_user_logged_in())
                        {
                            ?>
                                <li> 
                                    <a href="<?php echo get_option('home'); ?>/wp-admin/">
                                        <?php
                                            echo _e('Blog control panel', 'wpbb');
                                        ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $ucp_url; ?>">
                                        <?php 
                                            echo _e('User control panel', 'wpbb'); 
                                        ?>
                                    </a>
                                </li>
                            <?php
                                if(!empty($mcp_url))
                                {
                                    ?>
                                        <li>
                                            <a href="<?php echo $mcp_url; ?>">
                                                <?php 
                                                    echo _e('Moderator control panel', 'wpbb'); 
                                                ?>
                                            </a>
                                        </li>
                                    <?php
                                }
                                
                                if(!empty($permission_url))
                                {
                                    ?>
                                        <li>
                                            <a href="<?php echo $permission_url; ?>">
                                                <?php 
                                                    echo _e('Restore permissions', 'wpbb'); 
                                                ?>
                                            </a>
                                        </li>
                                    <?php
                                }
                            ?>
                                <li> 
                                    <a href="<?php echo $ucp_url; ?>?mode=logout&sid=<?php echo wpbb_get_sessionid(); ?>">
                                        <?php
                                            echo _e('Log out', 'wpbb'); 
                                        ?>
                                    </a>
                                </li>
                            <?php
                        }
                        else
                        {
                            ?>
                                <li>
                                    <a href="<?php echo $ucp_url; ?>?mode=sendpassword">
                                        <?php 
                                            echo _e('I forgot my password', 'wpbb'); 
                                        ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $ucp_url; ?>?mode=resend_act">
                                        <?php 
                                            echo _e('Resend activation email', 'wpbb'); 
                                        ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $ucp_url; ?>?mode=register">
                                        <?php 
                                            echo _e('Register new account', 'wpbb'); 
                                        ?>
                                    </a>
                                </li>
                            <?php
                        }
                    ?>
                </ul>
            <?php
        }
        
        if(get_option('wpbb_backlink', 0) == 1)
        {
            ?>
                <p style="text-align: right; font-size: 72%;" id="blp">
                    Powered by <a href="http://www.e-xtnd.it" title="eXtnd.it" target="_blank" id="bl">eXtnd.it</a>
                </p>
            <?php
        }
        
        echo $after_widget;
	}
}

?>