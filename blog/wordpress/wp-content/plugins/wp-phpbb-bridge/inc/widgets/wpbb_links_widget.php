<?php

class wpbb_links_widget extends WP_Widget
{
    function wpbb_links_widget()
    {
        $widget_ops = array(
            'classname' => 'phpBB3 Links Widget',
            'description' => __('Allows you to display a list of usefull links to your phpBB.', 'wpbb')
        );
        
        $control_ops = array(
            'width' => 250,
            'height' => 250,
            'id_base' => 'phpbb3-links-widget'
        );

        $this->WP_Widget(
            'phpbb3-links-widget', 
            'WP phpBB Bridge Links', 
            $widget_ops, 
            $control_ops
        );
    }
    
    function form($instance)
    {
        $defaults = array(
            'wpbb_links_title' => __('Forum Links', 'wpbb'),
    		'wpbb_links_index' => 'yes',
    		'wpbb_faq' => 'yes',
    		'wpbb_search' => 'yes',
    		'wpbb_active_topics' => 'yes',
    		'wpbb_unanswered_posts' => 'yes',
    		'wpbb_your_posts' => 'yes',
    		'wpbb_new_posts' => 'yes',
    		'wpbb_pms' => 'yes',
    		'wpbb_send_pm' => 'yes',
    		'wpbb_members' => 'yes',
    		'wpbb_team' => 'yes',
    		'wpbb_whos_online' => 'yes',
        );
        
        $instance = wp_parse_args(
            (array)$instance, 
            $defaults
        );
        
        ?>
            <div class="widget-content">
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_links_title'); ?>">
                        <?php 
                            _e('Title:', 'wpbb'); 
                        ?>
                    </label>
                    <input id="<?php echo $this->get_field_id('wpbb_links_title'); ?>" name="<?php echo $this->get_field_name('wpbb_links_title'); ?>" type="text" value="<?php echo $instance['wpbb_links_title']; ?>" class="widefat" />
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_links_index'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_links_index'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_links_index'); ?>" value="yes" <?php if($instance['wpbb_links_index'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        &nbsp;
                        <?php 
                            _e('Display Forum Index Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_faq'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_faq'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_faq'); ?>" value="yes" <?php if($instance['wpbb_faq'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        &nbsp;
                        <?php 
                            _e('Display FAQ Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_search'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_search'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_search'); ?>" value="yes" <?php if($instance['wpbb_search'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        &nbsp;
                        <?php 
                            _e('Display Forum Search Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_active_topics'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_active_topics'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_active_topics'); ?>" value="yes" <?php if($instance['wpbb_active_topics'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        <?php 
                            _e('Display View Active Topics Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_unanswered_posts'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_unanswered_posts'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_unanswered_posts'); ?>" value="yes" <?php if($instance['wpbb_unanswered_posts'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        <?php 
                            _e('Display View Unanswered Posts Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_your_posts'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_your_posts'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_your_posts'); ?>" value="yes" <?php if($instance['wpbb_your_posts'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        <?php 
                            _e('Display View Your Posts Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_new_posts'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_new_posts'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_new_posts'); ?>" value="yes" <?php if($instance['wpbb_new_posts'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        <?php 
                            _e('Display View New Posts Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_pms'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_pms'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_pms'); ?>" value="yes" <?php if($instance['wpbb_pms'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        <?php 
                            _e('Display Private Messages Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_send_pm'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_send_pm'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_send_pm'); ?>" value="yes" <?php if($instance['wpbb_send_pm'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        <?php 
                            _e('Display Send Private Message Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_members'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_members'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_members'); ?>" value="yes" <?php if($instance['wpbb_members'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        <?php 
                            _e('Display Member List Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p> 
                    <label for="<?php echo $this->get_field_id('wpbb_team'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_team'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_team'); ?>" value="yes" <?php if($instance['wpbb_team'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        <?php 
                            _e('Display The Team Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
                <p>
                    <label for="<?php echo $this->get_field_id('wpbb_whos_online'); ?>">
                        <input name="<?php echo $this->get_field_name('wpbb_whos_online'); ?>" type="checkbox" id="<?php echo $this->get_field_id('wpbb_whos_online'); ?>" value="yes" <?php if($instance['wpbb_whos_online'] == 'yes'){ echo 'checked="checked" '; } ?> />
                        <?php 
                            _e('Display Who is Online Link', 'wpbb'); 
                        ?>
                    </label>
                </p>
            </div>
        <?php
    }
    
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        
        $instance['wpbb_links_title'] = $new_instance['wpbb_links_title'];
        $instance['wpbb_links_index'] = $new_instance['wpbb_links_index'];
        $instance['wpbb_faq'] = $new_instance['wpbb_faq'];
        $instance['wpbb_search'] = $new_instance['wpbb_search'];
        $instance['wpbb_active_topics'] = $new_instance['wpbb_active_topics'];
        $instance['wpbb_unanswered_posts'] = $new_instance['wpbb_unanswered_posts'];
        $instance['wpbb_your_posts'] = $new_instance['wpbb_your_posts'];
        $instance['wpbb_new_posts'] = $new_instance['wpbb_new_posts'];
        $instance['wpbb_pms'] = $new_instance['wpbb_pms'];
        $instance['wpbb_send_pm'] = $new_instance['wpbb_send_pm'];
        $instance['wpbb_members'] = $new_instance['wpbb_members'];
        $instance['wpbb_team'] = $new_instance['wpbb_team'];
        $instance['wpbb_whos_online'] = $new_instance['wpbb_whos_online'];
        
        return $instance;
    }
    
    function widget($args, $instance)
    {
        extract($args);

        $ucp_url = trim(get_option('wpbb_ucp_path'));
        $forum_url = str_replace("/ucp.php", "/", $ucp_url);
        $search_url = $forum_url . 'search.php';
        $member_url = $forum_url . 'memberlist.php';
        $session_id = 'sid=' . wpbb_get_sessionid();
        
        $title = $instance['wpbb_links_title'];
        $display_index = $instance['wpbb_links_index'];
        $display_faq = $instance['wpbb_faq'];
        $display_search = $instance['wpbb_search'];
        $display_active_topics = $instance['wpbb_active_topics'];
        $display_unanswered_posts = $instance['wpbb_unanswered_posts'];
        $display_your_posts = $instance['wpbb_your_posts'];
        $display_new_posts = $instance['wpbb_new_posts'];
        $display_pms = $instance['wpbb_pms'];
        $display_send_pm = $instance['wpbb_send_pm'];
        $display_members = $instance['wpbb_members'];
        $display_team = $instance['wpbb_team'];
        $display_whos_online = $instance['wpbb_whos_online'];
        
        echo $before_widget . $before_title . $title . $after_title . '<ul>';
        
        if($display_index == 'yes')
        {
            echo '<li><a href="' . $forum_url . '?' . $session_id . '">';
            echo _e('Forum index', 'wpbb');
            echo '</a></li>';
        }
        
        if($display_faq == 'yes')
        {
            echo '<li><a href="' . $forum_url . 'faq.php?' . $session_id . '">';
            echo _e('FAQ', 'wpbb');
            echo '</a></li>';
        }
        
        if($display_search == 'yes')
        {
            echo '<li><a href="' . $search_url . '?' . $session_id . '">';
            echo _e('Forum search', 'wpbb');
            echo '</a></li>';
        }
        
        if($display_active_topics == 'yes')
        {
            echo '<li><a href="' . $search_url . '?search_id=active_topics&amp;' . $session_id . '">';
            echo _e('View active topics', 'wpbb');
            echo '</a></li>';
        }
        
        if($display_unanswered_posts == 'yes')
        {
            echo '<li><a href="' . $search_url . '?search_id=unanswered&amp;' . $session_id . '">';
            echo _e('View unanswered posts', 'wpbb');
            echo '</a></li>';
        }
        
        if(wpbb_is_user_logged_in())
        {
            if($display_your_posts == 'yes')
            {
                echo '<li><a href="' . $search_url . '?search_id=egosearch&amp;' . $session_id . '">';
                echo _e('View your posts', 'wpbb');
                echo '</a></li>';
            }
            
            if($display_new_posts == 'yes')
            {
                echo '<li><a href="' . $search_url . '?search_id=newposts&amp;' . $session_id . '">';
                echo _e('View new posts', 'wpbb');
                echo '</a></li>';
            }
            
            if($display_pms == 'yes')
            {
                echo '<li><a href="' . $ucp_url . '?i=pm&amp;folder=inbox&amp;' . $session_id . '">';
                echo _e('Private messages', 'wpbb');
                echo '</a></li>';
            }
            
            if($display_send_pm == 'yes')
            {
                echo '<li><a href="' . $ucp_url . '?i=pm&amp;mode=compose&amp;' . $session_id . '">';
                echo _e('Send private message', 'wpbb');
                echo '</a></li>';
            }
            
            if($display_members == 'yes')
            {
                echo '<li><a href="' . $member_url . '?' . $session_id . '">';
                echo _e('Member list', 'wpbb');
                echo '</a></li>';
            }
            
            if($display_team == 'yes')
            {
                echo '<li><a href="' . $member_url . '?mode=leaders&amp;' . $session_id . '">';
                echo _e('The team', 'wpbb');
                echo '</a></li>';
            }
            
            if($display_whos_online == 'yes')
            {
                echo '<li><a href="' . $forum_url . 'viewonline.php?' . $session_id . '">';
                echo _e('Who is online', 'wpbb');
                echo '</a></li>';
            }
        }
        
        echo '</ul>' . $after_widget;
    }
}

?>