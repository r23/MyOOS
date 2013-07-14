<?php

class wpbb_meta_widget extends WP_Widget
{
    function wpbb_meta_widget()
    {
        /* Widget settings. */
        $widget_ops = array(
            'classname' => 'phpBB3 Meta Widget',
            'description' => __('Allows you to display several usefull informations about the user.', 'wpbb')
        );
        
        /* Widget control settings. */
        $control_ops = array(
            'width' => 250,
            'height' => 250,
            'id_base' => 'phpbb3-meta-widget'
        );
        
        /* Create the widget. */
        $this->WP_Widget(
            'phpbb3-meta-widget', 
            'WP phpBB Bridge Meta', 
            $widget_ops, 
            $control_ops
        );
    }
    
    function form($instance)
    {
        $defaults = array(
                        'wpbb_meta_title' => __('Forum Meta', 'wpbb')
                    );
                    
        $instance = wp_parse_args(
                        (array)$instance,
                        $defaults
                    );
                    
        ?>
            <div class="widget-content">
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_meta_title') ?>">
                        <?php 
                            _e('Title:', 'wpbb'); 
                        ?> 
                    </label>
                    <input id="<?php echo $this->get_field_id('wpbb_meta_title'); ?>" name="<?php echo $this->get_field_name('wpbb_meta_title') ?>" type="text" value="<?php echo $instance['wpbb_meta_title']; ?>" class="widefat" />
                </p>
            </div>
        <?php
    }
    
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        
        $instance['wpbb_meta_title'] = $new_instance['wpbb_meta_title'];
        
        return $instance;
    }
    
    function widget($args, $instance)
    {
        extract($args);

    	$title = $instance['wpbb_meta_title'];
        
        $ucp_url = trim(get_option('wpbb_ucp_path'));
    	$admin_url = wpbb_get_admin_link();
    	$mcp_url = wpbb_get_mcp_link();
    	$permission_url = wpbb_get_restore_permissions_link();
        
    	if(empty($title))
    	{
    		$title = "&nbsp;";
    	}
        
        echo $before_widget . $before_title . $title . $after_title;
        
        ?>
            <ul>
                <?php
                    if(wpbb_is_user_logged_in())
                    {
                ?>
                    <li>
                        <a href="<?php bloginfo('home'); ?>/wp-admin/">
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
                        
                        if(!empty($admin_url))
                        {
                        ?>
                            <li>
                                <a href="<?php echo $admin_url; ?>">
                                    <?php 
                                        echo _e('Forum administration', 'wpbb'); 
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
                <li>
                    <a href="<?php echo $ucp_url; ?>?mode=delete_cookies">
                        <?php 
                            echo _e('Delete site cookies', 'wpbb'); 
                        ?>
                    </a>
                </li>
            </ul>
        <?php
        
        echo $after_widget;
    }
}

?>