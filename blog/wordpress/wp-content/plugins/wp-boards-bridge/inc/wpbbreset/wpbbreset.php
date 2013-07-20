<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=<?php bloginfo('charset'); ?>" />

	<title><?php _e('WP phpBB Bridge reset page', 'wpbb'); ?></title>
    <link href="<?php echo WPBB_URL . '/css/wpbb_reset.css'; ?>" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="msg_board">
        <h3><?php _e('WP phpBB Bridge plugin diactivate', 'wpbb'); ?></h3>
        
        <?php
            if(
                $wp_query->get('wpbb_reset_code') == get_option('wpbb_deactivation_password')
                && 
                (get_option('wpbb_times') > get_option('wpbb_maximu_retries', 3)) == false
                
            )
            {
                update_option('wpbb_activate', 'no');
                
                ?>
                    <div class="success">
                        <?php
                            printf(
                                __('WP phpBB Bridge is now disabled. You can click <a href="%1$s">here</a> to login with WordPress.', 'wpbb'),
                                get_bloginfo('home') . '/wp-admin/'
                            );
                        ?>
                    </div>
                <?php   
            }
            else
            {
                if(get_option('wpbb_times') > get_option('wpbb_maximu_retries'))
                {
        ?>
            <div class="error">
                <?php
                    _e('You already have exceed the maximum amount of times to reset the WP phpBB Bridge. You are not allowed anymore to try reset the plugin this way for security reasons. Try diactivate the plugin from database.', 'wpbb');
                ?>
            </div>
        <?php
                }
            }
        ?>
    </div>
</body>
</html>