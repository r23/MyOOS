<div class="wrap">
    <div id="wpbb_admin_dashboard" class="icon32"></div>
    <h2><?php _e('WP phpBB Bridge', 'wpbb'); ?></h2>
    
    <div id="dashboard-widgets-wrap" class="ngg-overview">
        <div id="dashboard-widgets" class="metabox-holder">
            <div id="post-body">
                <div id="dashboard-widgets-main-content">
                    <div class="postbox-container" style="width:75%;">
                        <div id="left-sortables" class="meta-box-sortables ui-sortable">
                            <div id="wpbb_status" class="postbox ">
                                <div class="handlediv" title="<?php _e('Toggle', 'wpbb'); ?>"><br /></div>
                                <h3 class="hndle">
                                    <span>
                                        <?php _e('WP phpBB Bridge status', 'wpbb'); ?>
                                    </span>
                                </h3>
                                <div class="inside">
                                    <div class="table table_content">
                                        <p>
                                            <?php _e('Fast preview of WP phpBB Bridge status &amp; configuration', 'wpbb'); ?>
                                        </p>
                                    </div>
                                    <table>
                                        <tr>
                                            <td valign="top">
                                                <?php _e('phpBB config.php location', 'wpbb'); ?> : 
                                            </td>
                                            <td valign="top">
                                                <strong>
                                                    <?php 
                                                        echo get_option(
                                                                'wpbb_config_path', 
                                                                __('The path to config.php is not set yet', 'wpbb')
                                                            ); 
                                                    ?>
                                                </strong>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <?php _e('phpBB ucp.php url', 'wpbb'); ?> : 
                                            </td>
                                            <td valign="top">
                                                <strong>
                                                    <?php 
                                                        echo get_option(
                                                                'wpbb_ucp_path', 
                                                                __('The path to ucp.php is not set yet', 'wpbb')
                                                            ); 
                                                    ?>
                                                </strong>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <?php _e('Avatar integration', 'wpbb'); ?> : 
                                            </td>
                                            <td valign="top">
                                                <strong>
                                                    <?php 
                                                        echo (get_option('wpbb_avatars', 'no') == 'no' ? __('No', 'wpbb') : __('Yes', 'wpbb')); 
                                                    ?>
                                                </strong>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <?php _e('Plugin status', 'wpbb'); ?> : 
                                            </td>
                                            <td valign="top">
                                                <strong>
                                                    <?php 
                                                        get_option('wpbb_activate', 'no') == 'no' ? _e('Disabled', 'wpbb') : _e('Enabled', 'wpbb'); 
                                                    ?>
                                                </strong>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <?php _e('phpBB database encoding', 'wpbb'); ?> : 
                                            </td>
                                            <td valign="top">
                                                <strong>
                                                    <?php 
                                                        echo get_option('wpbb_dbms_charset', 'utf8'); 
                                                    ?>
                                                </strong>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <?php _e('Plugin deactivation URL', 'wpbb'); ?> : 
                                            </td>
                                            <td valign="top">
                                                <strong>
                                                    <?php 
                                                        echo get_bloginfo('home') . '/wpbbreset/<span class="red">' . get_option('wpbb_deactivation_password', __('Your Password is not yet set', 'wpbb')) . '</span>'; 
                                                    ?>
                                                </strong>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <?php _e('Reset retries', 'wpbb'); ?> : 
                                            </td>
                                            <td valign="top">
                                                <strong>
                                                    <?php 
                                                        echo get_option('wpbb_maximu_retries', '0') . ' / ' . get_option('wpbb_times', '0') . ' ' . __('(Total available / Tries)', 'wpbb'); 
                                                    ?>
                                                </strong>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <?php _e('Autocreate forum topics', 'wpbb'); ?> : 
                                            </td>
                                            <td valign="top">
                                                <strong>
                                                    <?php 
                                                        get_option('wpbb_post_posts', 'no') == "no" ? _e('No', 'wpbb') : _e('Yes', 'wpbb'); 
                                                    ?>
                                                </strong>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                <?php _e('Autocreate forum topics on locked forums', 'wpbb'); ?> : 
                                            </td>
                                            <td valign="top">
                                                <strong>
                                                    <?php 
                                                        get_option('wpbb_post_locked', 'no') == "no" ? _e('No', 'wpbb') : _e('Yes', 'wpbb'); 
                                                    ?>
                                                </strong>
                                                <br />
                                                <br />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="wpbb_xtndit_info" class="postbox ">
                                <div class="handlediv" title="<?php _e('Toggle', 'wpbb'); ?>"><br /></div>
                                <h3 class="hndle">
                                    <span>
                                        <?php _e('eXtnd.it latest news', 'wpbb'); ?>
                                    </span>
                                </h3>
                                <div class="inside">
                                    <br />
                                    <?php
                                        include_once(ABSPATH . WPINC . '/rss.php');
                                        $rss = fetch_rss('http://www.e-xtnd.it/feed/');
                                        $maxitems = 5;
                                        $extnd_items = array_slice($rss->items, 0, $maxitems);
                                        
                                        $rss = fetch_rss('http://www.stigmahost.com/en/feed/');
                                        $maxitems = 5;
                                        $stigma_items = array_slice($rss->items, 0, $maxitems);
                                        
                                        function date_short($a, $b)
                                        {
                                            $a_time = strtotime($a['pubdate']);
                                            $b_time = strtotime($b['pubdate']);
                                            
                                            if($a_time == $b_time)
                                            {
                                                return 0;
                                            }
                                             
                                            return ($a_time < $b_time)? -1 : 1;
                                        }
                                        
                                        $items = array_merge($extnd_items, $stigma_items);
                                        
                                        usort($items, 'date_short');
                                        
                                        $items = array_reverse($items);
                                        
                                        $items = array_slice($items, 0, 5);
                                                                                
                                        foreach($items as $i)
                                        {
                                            $d = dateDiff(time(), strtotime($i['pubdate']));
                                            
                                            $month = key_exists('month', $d) ? $d['month'] : 0;
                                            $dt = date(get_option('date_format'), strtotime($i['pubdate']));
                                    ?>
                                        <a href="<?php echo $i['link']; ?>" title="<?php echo $i['title']; ?>" target="_blank"><?php echo $i['title']; ?></a> <span class="rss-date"><?php echo $dt; ?></span>
                                        <p>
                                            <strong><?php $month != 1 ? printf(__('%1$d months old', 'wpbb'), $month) : printf(__('%1$d month old', 'wpbb'), $month) ?></strong> - <?php echo $i['description']; ?>
                                        </p>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </div>
                            <div id="wpbb_server_info" class="postbox ">
                                <div class="handlediv" title="<?php _e('Toggle', 'wpbb'); ?>"><br /></div>
                                <h3 class="hndle">
                                    <span>
                                        <?php _e('Server info', 'wpbb'); ?>
                                    </span>
                                </h3>
                                <div class="inside">
                                    <br />
                                    <?php
                                        global $wpdb, $ngg;
                                    	$sqlversion = $wpdb->get_var("SELECT VERSION() AS version");
                                    	$mysqlinfo = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
                                        
                                    	if(is_array($mysqlinfo))
                                        {
                                            $sql_mode = $mysqlinfo[0]->Value;
                                        }
                                        
                                    	if(empty($sql_mode))
                                        {
                                            $sql_mode = __('Is not set', 'wpbb');
                                        }
                                    	
                                    	if(ini_get('safe_mode'))
                                        {
                                            $safe_mode = __('Enabled', 'wpbb');
                                        }
                                    	else
                                        {
                                            $safe_mode = __('Disabled', 'wpbb');
                                        }
                                        
                                    	if(ini_get('allow_url_fopen'))
                                        {
                                            $allow_url_fopen = __('Enabled', 'wpbb');
                                        }
                                    	else
                                        {
                                            $allow_url_fopen = __('Disabled', 'wpbb');
                                        }
                                        
                                    	
                                    	if(ini_get('upload_max_filesize'))
                                        {
                                            $upload_max = ini_get('upload_max_filesize');
                                        }	
                                    	else
                                        {
                                            $upload_max = __('Not Available', 'wpbb');
                                        }
                                        
                                    	if(ini_get('post_max_size'))
                                        {
                                            $post_max = ini_get('post_max_size');
                                        }
                                    	else
                                        {
                                            $post_max = __('Not Available', 'wpbb');
                                        }
                                        
                                    	if(ini_get('max_execution_time'))
                                        {
                                            $max_execute = ini_get('max_execution_time');
                                        }
                                    	else
                                        {
                                            $max_execute = __('Not Available', 'wpbb');
                                        }
                                        
                                    	if(function_exists('memory_get_usage'))
                                        {
                                            $memory_usage = round(memory_get_usage() / 1024 / 1024, 2) . ' MB';
                                        }
                                    	else
                                        {
                                            $memory_usage = __('Not Available', 'wpbb');
                                        }
    
                                    	if(is_callable('xml_parser_create'))
                                        {
                                            $xml = __('Supported', 'wpbb');
                                        }
                                    	else
                                        {
                                            $xml = __('Not supported', 'wpbb');
                                        }
                                    ?>
                                    <table>
                                        <tr>
                                            <td style="width: 15%;" valign="top"><?php _e('Operating system', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo PHP_OS . ' ' . (PHP_INT_SIZE * 8) . ' Bit'; ?><br /><br /></td>
                                            <td style="width: 15%;" valign="top"><?php _e('Server', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $_SERVER["SERVER_SOFTWARE"]; ?><br /><br /></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;" valign="top"><?php _e('Memory usage', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $memory_usage; ?><br /><br /></td>
                                            <td style="width: 15%;" valign="top"><?php _e('MYSQL Version', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $sqlversion; ?><br /><br /></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;" valign="top"><?php _e('SQL Mode', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $sql_mode; ?><br /><br /></td>
                                            <td style="width: 15%;" valign="top"><?php _e('PHP Version', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo PHP_VERSION; ?><br /><br /></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;" valign="top"><?php _e('PHP Safe Mode', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $safe_mode; ?><br /><br /></td>
                                            <td style="width: 15%;" valign="top"><?php _e('PHP Allow URL fopen', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $allow_url_fopen; ?><br /><br /></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;" valign="top"><?php _e('PHP Max Upload Size', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $upload_max; ?><br /><br /></td>
                                            <td style="width: 15%;" valign="top"><?php _e('PHP Max Post Size', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $post_max; ?><br /><br /></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;" valign="top"><?php _e('PHP Max Script Execute Time', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $max_execute; ?><br /><br /></td>
                                            <td style="width: 15%;" valign="top"><?php _e('PHP XML support', 'wpbb'); ?> : <br /><br /></td>
                                            <td style="width: 35%;" valign="top"><?php echo $xml; ?><br /><br /></td>
                                        </tr>
                                    </table>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="postbox-container" style="width:24%;">
                        <div id="right-sortables" class="meta-box-sortables ui-sortable">
                            <div id="wpbb_plugin_info" class="postbox ">
                                <div class="handlediv" title="<?php _e('Toggle', 'wpbb'); ?>">
                                    <br />
                                </div>
                                <h3 class="hndle">
                                    <span>
                                        <?php _e('Do you like WP phpBB Bridge?', 'wpbb'); ?>
                                    </span>
                                </h3>
                                <div class="inside">
                                    <p>
                                        <?php
                                            _e('The WP phpBB Bridge is the development of «WordPress to phpBB3 Bridge» which is written by Jason Sanborn.', 'wpbb');
                                        ?>
                                    </p>
                                    <p>
                                        <?php
                                            _e('The first version of the plugin was designed to synchronize users of phpBB users to WordPress so that the user is once in phpBB and be simultaneously connected and WordPress.', 'wpbb');
                                        ?>
                                    </p>
                                    <p>
                                        <?php
                                            _e('In now days the plugin is re-writed from scratch, to fix errors from the past, and add new functionality in case to make the usage of phpBB in compination with WordPress a peace of cake.', 'wpbb')
                                        ?>
                                    </p>
                                    <h4>
                                        <?php _e('Do you like to help us?', 'wpbb'); ?>
                                    </h4>
                                    <ul>
                                        <li>
                                            <a href="http://wordpress.org/extend/plugins/wp-phpbb-bridge/" target="_blank" title="<?php _e('Give a possitive rating', 'wpbb'); ?>">
                                                <?php _e('Give it a good rating on WordPress.org', 'wpbb'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=info%40xtnd.it&lc=US&item_name=WP+phpBB+Bridge&item_number=42&no_note=1&currency_code=EUR&bn=eXtndit_Donation&rm=2&no_shipping=1" target="_blank" title="<?php _e('Donate us', 'wpbb'); ?>">
                                                <?php _e('Donate the work via paypal', 'wpbb'); ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="wpbb_locale" class="postbox">
                                <div class="handlediv" title="Εναλλαγή">
                                    <br />
                                </div>
                                <h3 class="hndle">
                                    <span>
                                        <?php _e('Translation', 'wpbb'); ?>
                                    </span>
                                </h3>
                                <div class="inside" style="">
                                    <p>
                                        <?php
                                            _e(
                                                'If you cannot find the plugin in your native language, you may like to translated and send the translation back to us. Then a new plugin version will be available in a few days with your language package installed',
                                                'wpbb'
                                            );
                                        ?>
                                    </p>
                                    <p>
                                        <?php
                                            _e(
                                                'Click the download button below to get the latest English translation file. Use the poEdit, save the file in the form of wpbb-YourLocale.po and send us the *.po and *.mo files with e-mail here: support@wordpress-gr.org',
                                                'wpbb'
                                            );
                                        ?>
                                    </p>
                                    <p class="textright">
                                        <a class="button" href="<?php echo WPBB_URL . '/i18n/wpbb-en.po' ?>" title="<?php _e('Translation file', 'wpbb'); ?>" target="_blank">
                                            <?php _e('Download', 'wpbb'); ?>
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div id="wpbb_donators" class="postbox ">
                                <div class="handlediv" title="<?php _e('Toggle', 'wpbb'); ?>">
                                    <br />
                                </div>
                                <h3 class="hndle">
                                    <span>
                                        <?php _e('Recent donators', 'wpbb'); ?>
                                    </span>
                                </h3>
                                <div class="inside" style="">
                                    <div id="dashboard_server_settings" class="dashboard-widget-holder">
                                        <div class="ngg-dashboard-widget">
                                            <div class="dashboard-widget-content">
                                                <br />
                                                <ul class="settings">
                                                    <?php
                                                        $content = file_get_contents('http://www.e-xtnd.it/donators/5/');
                                                        
                                                        if($content !== false)
                                                        {
                                                           $content = json_decode($content);
                                                           
                                                           foreach($content as $c)
                                                           {
                                                        ?>
                                                            <li>
                                                                <a target="_blank" href="http://<?php echo $c->DOMAIN; ?>" title="<?php echo $c->NAME; ?>"><?php echo $c->NAME; ?></a>
                                                                <br />
                                                                <small><?php _e('Donation date : ', 'wpbb'); ?><?php echo date(get_option('date_format', 'd/m/Y'), strtotime($c->CREATED)); ?></small>
                                                            </li>
                                                        <?php
                                                           }
                                                        }
                                                        else
                                                        {
                                                        ?>
                                                            <li>
                                                                <?php _e('There are no donations yet', 'wpbb'); ?>
                                                                <br />
                                                                <?php _e('Be the first to make a donation', 'wpbb'); ?>
                                                            </li>
                                                        <?php   
                                                        }
                                                    ?>
                                                </ul>
                                                <?php
                                                    if($content !== false)
                                                    {
                                                ?>
                                                <p class="textright">
                                                    <a class="button" href="admin.php?page=wpbb_donators">
                                                        <?php
                                                            _e('View all', 'wpbb');
                                                        ?>
                                                    </a>
                                                </p>
                                                <?php
                                                    }
                                                    else
                                                    {
                                                ?>
                                                <p class="textright">
                                                    <a class="button" href="admin.php?page=wpbb_settings">
                                                        <?php
                                                            _e('Donate us', 'wpbb');
                                                        ?>
                                                    </a>
                                                </p>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>