<div id="overlay"><div id="loading"></div></div>
<?php
    global $wpdb;
?>
<div class="wrap">
    <div id="wpbb_admin_dashboard" class="icon32"></div>
    <h2><?php _e('WP phpBB Bridge', 'wpbb'); ?> - <?php _e('Settings', 'wpbb'); ?></h2>
    <?php
        if(isset($e) && sizeof($e->get_error_messages()) > 0)
        {
    ?>
    <div class="error">
        <br />
        <?php
            foreach($e->get_error_messages() as $er)
            {
                echo $er;
                echo "<br />";
            }
        ?>
        <br />
    </div>
    <?php
        }
    ?>
    <form method="post" action="">
        <input type="hidden" name="action" value="update" />
        <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce('wpbb_settings_page') ?>" />
        
        <h3>
            <?php
                
                _e('Files options', 'wpbb');
            
            ?>
        </h3>
        
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="wpbb_config_path">
                            <?php
                                _e('config.php path', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <input name="wpbb_config_path" type="text" id="wpbb_config_path" value="<?php echo $wpbb_config_path; ?>" class="regular-text" />
                        <br />
                        <span class="description">
                            <?php _e('Enter the full path to phpBB config.php file', 'wpbb'); ?>
                        </span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="wpbb_ucp_path">
                            <?php
                                _e('ucp.php url', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <input name="wpbb_ucp_path" type="text" id="wpbb_ucp_path" value="<?php echo $wpbb_ucp_path; ?>" class="regular-text" />
                        <br />
                        <span class="description">
                            <?php _e('Enter the url to phpBB ucp.php file', 'wpbb'); ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <h3>
            <?php _e('Security options', 'wpbb'); ?>
        </h3>
        
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="wpbb_deactivation_password">
                            <?php
                                _e('Deactivation password', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <input name="wpbb_deactivation_password" type="text" id="wpbb_deactivation_password" value="<?php echo $wpbb_deactivation_password; ?>" class="regular-text" />
                        <br />
                        <span class="description">
                            <?php _e('Enter a password you will use to diactivate the plugin in case you are locked out', 'wpbb'); ?>
                            <br />
                            <?php 
                                echo sprintf(
                                    __('Your reset url is the following : <strong id="resetCode">%1$s<span class="red">%2$s</span></strong>', 'wpbb'),
                                    get_bloginfo('home') . '/wpbbreset/',
                                    $wpbb_deactivation_password
                                ); 
                            ?>
                        </span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="wpbb_maximu_retries">
                            <?php
                                _e('Maximum reset retries', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <input name="wpbb_maximu_retries" type="text" id="wpbb_maximu_retries" value="<?php echo $wpbb_maximu_retries; ?>" class="regular-text" />
                        <br />
                        <span class="description">
                            <?php 
                                _e('Enter the maximum retries for plugin diactivation.<br /><strong>WARNING</strong> : A very large amount of retries can make the plugin diactivation vulnerable on brute force attacks', 'wpbb');
                            ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3>
            <?php
                
                _e('Forum posts options', 'wpbb');
            
            ?>
        </h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label>
                            <?php
                                _e('New forum posts on post creation', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <label for="wpbb_post_posts">
                            <input name="wpbb_post_posts" type="checkbox" id="wpbb_post_posts" <?php echo $wpbb_post_posts == "yes" ? 'checked="checked"' : ''; ?> />
                            <?php _e('Enable', 'wpbb'); ?>
                        </label>
                        <br />
                        <span class="description">
                            <?php _e('Check that option if you like to enable the posting of new WordPress posts on specific forums.', 'wpbb'); ?>
                        </span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label>
                            <?php
                                _e('Post on locked forums', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <label for="wpbb_post_locked">
                            <input name="wpbb_post_locked" type="checkbox" id="wpbb_post_locked" <?php echo $wpbb_post_locked == "yes" ? 'checked="checked"' : ''; ?> />
                            <?php _e('Enable', 'wpbb'); ?>
                        </label>
                        <br />
                        <span class="description">
                            <?php _e('By checking that option you will be able to choose locked posts on witch the plugin will posting.', 'wpbb'); ?>
                        </span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="wpbb_dbms_charset">
                            <?php
                                _e('phpBB database encoding', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <select name="wpbb_dbms_charset" id="wpbb_dbms_charset">
                            <?php
                                $r = $wpdb->get_results('SELECT CHARACTER_SET_NAME FROM information_schema.CHARACTER_SETS ORDER BY CHARACTER_SET_NAME;');
                            
                                foreach($r as $rs)
                                {
                            ?>
                                <option value="<?php echo $rs->CHARACTER_SET_NAME; ?>" <?php echo $wpbb_dbms_charset == $rs->CHARACTER_SET_NAME ? 'selected="selected"' : ''; ?>><?php echo $rs->CHARACTER_SET_NAME; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <br />
                        <span class="description">
                            <?php _e('Select the database connection character set for phpBB', 'wpbb'); ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3>
            <?php
                
                _e('Plugin options', 'wpbb');
            
            ?>
        </h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label>
                            <?php
                                _e('Integrate phpBB avatars', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <label for="wpbb_avatars_yes">
                            <input type="radio" name="wpbb_avatars" value="yes" id="wpbb_avatars_yes" <?php echo $wpbb_avatars == 'yes' ? 'checked="checked"' : ''; ?> /> <?php _e('Yes', 'wpbb'); ?>
                        </label>&nbsp;
                        <label for="wpbb_avatars_no">
                            <input type="radio" name="wpbb_avatars" value="no" id="wpbb_avatars_no" <?php echo $wpbb_avatars == 'no' ? 'checked="checked"' : ''; ?> /> <?php _e('No', 'wpbb'); ?>
                        </label>
                        <br />
                        <span class="description">
                            <?php _e('Choose if you like to integrate phpBB user avatars on WordPress.', 'wpbb'); ?>
                        </span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label>
                            <?php
                                _e('Activate phpBB bridge', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <label for="wpbb_activate_yes">
                            <input type="radio" name="wpbb_activate" value="yes" id="wpbb_activate_yes" <?php echo $wpbb_activate == 'yes' ? 'checked="checked"' : ''; ?> /> <?php _e('Yes', 'wpbb'); ?>
                        </label>&nbsp;
                        <label for="wpbb_activate_no">
                            <input type="radio" name="wpbb_activate" value="no" id="wpbb_activate_no" <?php echo $wpbb_activate == 'no' ? 'checked="checked"' : ''; ?> /> <?php _e('No', 'wpbb'); ?>
                        </label>
                        <br />
                        <span class="description">
                            <?php _e('Choose if you like to activate the plugin. <br /><div class="red"><strong>WARNING</strong> : Be sure you have already isntalled the WP phpBB Bridge Users widget.</div>', 'wpbb'); ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <h3>
            <?php
                
                _e('Support options', 'wpbb');
            
            ?>
        </h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="wpbb_backlink">
                            <?php
                                _e('Backlink', 'wpbb');
                            ?>
                        </label>
                    </th>
                    <td>
                        <label for="wpbb_backlink">
                            <input type="checkbox" name="wpbb_backlink" value="wpbb_backlink" id="wpbb_backlink" <?php echo ($wpbb_backlink == 1) ? 'checked="checked"' : ''; ?> />
                            <?php _e('Allow backlink', 'wpbb'); ?>
                        </label>
                        <br />
                        <span class="description">
                            <?php 
                                _e('While WP phpBB Bridge plugin is free for use, we need your assistance, in order to make a famous plugin.', 'wpbb');
                                echo "<br />";
                                _e('This will help us to extend the plugin options and functionality, thus you will enjoy more free features and capabilities.', 'wpbb');
                            ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save options', 'wpbb'); ?>" />
        </p>
    </form>
</div>