<?php

class wpbb_topics_widget extends WP_Widget
{
    function wpbb_topics_widget()
    {
        /* Widget settings. */
        $widget_ops = array(
            'classname' => 'phpBB3 Posts Widget',
            'description' => __('Allows you to display a list of recent topics within a specific forum id\'s.', 'wpbb')
        );

        /* Widget control settings. */
        $control_ops = array(
            'width' => 250,
            'height' => 250,
            'id_base' => 'phpbb3-posts-widget'
        );

        /* Create the widget. */
        $this->WP_Widget(
            'phpbb3-posts-widget',
            'WP phpBB Bridge posts',
            $widget_ops,
            $control_ops
        );
    }
    
    function form($instance)
    {
        $defaults = array(
                        'wpbb_title' => __('Latest posts', 'wpbb'),
        				'wpbb_forums' => 0,
        				'wpbb_total' => '10',
                        'wpbb_show_forum' => 'yes',
                        'wpbb_show_username' => 'yes',
                        'wpbb_show_total_posts' => 'yes',
                        'wpbb_show_total_views' => 'yes'
                    );
            
        $instance = wp_parse_args(
                        (array)$instance,
                        $defaults
                    );
                    
        ?>
            <div class="widget-content">
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_title'); ?>">
                        <?php 
                            echo _e('Title:', 'wpbb'); 
                        ?>
                    </label>
                    <input class="widefat" id="<?php echo $this->get_field_id('wpbb_title'); ?>" name="<?php echo $this->get_field_name('wpbb_title'); ?>" type="text" value="<?php echo $instance['wpbb_title']; ?>" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_forums'); ?>">
                        <?php echo _e('Forums:', 'wpbb'); ?>
                    </label>
                    <input class="widefat" name="<?php echo $this->get_field_name('wpbb_forums'); ?>" type="text" id="<?php echo $this->get_field_id('wpbb_forums'); ?>" value="<?php echo $instance['wpbb_forums']; ?>" />
                    <small><?php _e('Enter the id of the forum you like to get topics from. You can get topics from more than one forums by seperating the forums id with commas. ex: 3,5,6,12','wpbb'); ?></small>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_total'); ?>">
                        <?php 
                            echo _e('Total posts:', 'wpbb'); 
                        ?>
                    </label>
                    <input class="widefat" name="<?php echo $this->get_field_name('wpbb_total'); ?>" type="text" id="<?php echo $this->get_field_id('wpbb_total'); ?>" value="<?php echo $instance['wpbb_total']; ?>" />
                </p>
                <p>
                    <label>
                        <input name="<?php echo $this->get_field_name('wpbb_show_forum'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_show_forum'); ?>" value="yes" <?php if ($instance['wpbb_show_forum'] == "yes") { echo 'checked="checked" '; } ?> />&nbsp;
                        <?php 
                            echo _e('Display forum name', 'wpbb');
                        ?>
                    </label>
                </p>
                <p>
                    <label>
                        <input name="<?php echo $this->get_field_name('wpbb_show_username'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_show_username'); ?>" value="yes" <?php if($instance['wpbb_show_username'] == "yes") { echo 'checked="checked" '; } ?> />&nbsp;
                        <?php 
                            echo _e('Display author name', 'wpbb');
                        ?>
                    </label>
                </p>
                <p>
                    <label>
                        <input name="<?php echo $this->get_field_name('wpbb_show_total_posts'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_show_total_posts'); ?>" value="yes" <?php if ($instance['wpbb_show_total_posts'] == "yes") { echo 'checked="checked" '; } ?> />&nbsp;
                        <?php 
                            echo _e('Display total replies', 'wpbb');
                        ?>
                    </label>
                </p>
                <p>
                    <label>
                        <input name="<?php echo $this->get_field_name('wpbb_show_total_views'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_show_total_views'); ?>" value="yes" <?php if ($instance['wpbb_show_total_views'] == "yes") { echo 'checked="checked" '; } ?> />&nbsp;
                        <?php 
                            echo _e('Display total views', 'wpbb');
                        ?>
                    </label>
                </p>
            </div>
        <?php
    }
    
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['wpbb_title'] = $new_instance['wpbb_title'];
        $instance['wpbb_forums'] = $new_instance['wpbb_forums'];
        $instance['wpbb_total'] = $new_instance['wpbb_total'];
        $instance['wpbb_show_forum'] = $new_instance['wpbb_show_forum'];
        $instance['wpbb_show_username'] = $new_instance['wpbb_show_username'];
        $instance['wpbb_show_total_posts'] = $new_instance['wpbb_show_total_posts'];
        $instance['wpbb_show_total_views'] = $new_instance['wpbb_show_total_views'];
        
        return $instance;
    }
    
    function widget($args, $instance)
    {
        global $wpdb, $phpbb_root_path, $phpEx, $auth, $user, $db, $config, $cache, $template;
        
        extract($args);

        $title = $instance['wpbb_title'];
        $forums = $instance['wpbb_forums'];
        $total = $instance['wpbb_total'];
        $showForum = $instance['wpbb_show_forum'];
        $showUsername = $instance['wpbb_show_username'];
        $showTotalPosts = $instance['wpbb_show_total_posts'];
        $showTotalViews = $instance['wpbb_show_total_views'];
        
        echo $before_widget . $before_title . $title . $after_title;
        
        $phpbb_config = trim(get_option('wpbb_config_path'));
    	$phpEx = substr(strrchr($phpbb_config, '.'), 1);
        $forum_url = str_replace("/ucp.php", "/", trim(get_option('wpbb_ucp_path')));
    
    	require($phpbb_config);
        
        // TODO : Next Version, to get connected with phpBB $db object in order to retrive the
        //        forum posts. Also to remove the database encoding from plugin options
        
        $cn = mysql_pconnect($dbhost . ":" . $dbport, $dbuser, $dbpasswd);
    
        mysql_set_charset(get_option('wpbb_dbms_charset'), $cn);
        
        if($cn)
        {
            if(@mysql_select_db($dbname))
            {
                $qr = "SELECT p.`topic_id` AS `ID`, p2.`forum_name` AS `FORUM`, p.`forum_id` AS `FORUM_ID`, p.`topic_title` AS `TITLE`, p.`topic_views` AS `VIEWS`, p.`topic_replies` AS `REPLIES`, `username_clean` AS `USERNAME`, p.`topic_poster` AS `USER_ID`, p.`topic_time` AS `TIME`
    FROM " . $table_prefix . "topics p
    LEFT JOIN " . $table_prefix . "forums p2 ON(p.`forum_id` = p2.`forum_id`)
    LEFT JOIN " . $table_prefix . "users p3 ON(p.`topic_poster` = p3.`user_id`)
    WHERE p.`forum_id` IN (" . $forums . ") ORDER BY p.`topic_time` DESC LIMIT " . $total;
    
                $rs = mysql_query($qr, $cn);
                
            }
        }
        
        if($rs)
        {
        ?>
            <ul>
                <?php
                    while($get_info = mysql_fetch_assoc($rs))
                    {
                ?>
                    <li>
                        <a rel="nofollow" href="<?php echo $forum_url; ?>viewtopic.php?f=<?php echo $get_info['FORUM_ID'] ?>&t=<?php echo $get_info['ID']; ?>" title="<?php echo __('View post','wpbb'); ?>">
                            <?php echo $get_info['TITLE']; ?>
                        </a>
                        <br />
                        <?php
                            if($showForum == 'yes')
                            {
                        ?>
                            <small>
                                <?php 
                                    echo __('Forum:', 'wpbb');
                                ?> 
                                <a rel="nofollow" href="<?php echo $forum_url; ?>viewforum.php?f=<?php echo $get_info['FORUM_ID']; ?>" title="<?php echo __('Go to forum', 'wpbb'); ?>">
                                    <?php 
                                        echo $get_info['FORUM']; 
                                    ?>
                                </a>
                            </small>
                            <br />
                        <?php
                            }
                            
                            if($showUsername == 'yes')
                            {
                        ?>
                            <small>
                                <?php 
                                    echo __('By:', 'wpbb'); 
                                ?>
                                <a rel="nofollow" href="<?php echo $forum_url; ?>memberlist.php?mode=viewprofile&u=<?php echo $get_info['USER_ID']; ?>" title="<?php echo __('View user info', 'wpbb'); ?>">
                                    <?php 
                                        echo $get_info['USERNAME']; 
                                    ?>
                                </a>
                            </small>
                            <br />
                        <?php
                            }
                            
                            if($showTotalViews == 'yes')
                            {
                        ?>
                            <small>
                                <?php 
                                    _e('Views:', 'wpbb'); 
                                ?> 
                                <strong>
                                    <?php 
                                        echo $get_info['VIEWS']; 
                                    ?>
                                </strong>
                            </small>
                        <?php
                            }
                            
                            if($showTotalViews == 'yes' && $showTotalPosts == 'yes')
                            {
                                echo "<small>&nbsp;|&nbsp;</small>";
                            }
                            
                            if($showTotalPosts == 'yes')
                            {
                        ?>
                            <small>
                                <?php 
                                    _e('Replies:', 'wpbb'); 
                                ?> 
                                <strong>
                                    <?php 
                                        echo $get_info['REPLIES']; 
                                    ?>
                                </strong>
                            </small>
                        <?php
                            }
                        ?>
                    </li>
                <?php
                    }
                ?>
            </ul>
        <?php
        }
        
        echo $after_widget;
    }
}

?>