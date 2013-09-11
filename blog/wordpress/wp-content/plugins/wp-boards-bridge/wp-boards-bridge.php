<?php
/*
Plugin Name: MyOOS Wordpress phpBB3 Bridge
Plugin URI: http://oos-shop.de
Description: Used to synchronize the users in WordPress and phpBB 3.0.x. New features, allowing the plugin to auto create threads on public and locked forums. Another feature is the ability of the manager, choose the users that their articles will be published automatically in phpBB. Based on: <a a href="http://www.e-xtnd.it/wp-phpbb-bridge/">http://www.e-xtnd.it/wp-phpbb-bridge/</a> von <a a href="http://www.e-xtnd.it">Xtnd.it Group</a>
Version: 2.0.11
Author: MyOOS 
Author URI: http://oos-shop.de

This plugin was originally written by Xtnd.it Group.
The plugin info listed below is from the last release by them:

- Plugin Name: WP phpBB Bridge
- Plugin URI: http://www.e-xtnd.it/wp-phpbb-bridge/
- Description: Used to synchronize the users in WordPress and phpBB 3.0.x. New features, allowing the plugin to auto create threads on public and locked forums. Another feature is the ability of the manager, choose the users that their articles will be published automatically in phpBB. 
- Version: 2.0.7
- Author: Xtnd.it Group
- Author URI: http://www.e-xtnd.it
- License: GPLv3
*/

if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

class WpPhpBB
{
    /**
     * @var string Plugin version
     */
    var $version = '2.0.7';
    
    /**
     * @var strung Plugin name
     */
    var $name = "MyOOS Wordpress phpBB3 Bridge";
    
    /**
     * This method is the class constructor.
     */
    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'init'), 8);
    }
    
    /**
     * Initiate the plugin
     */
    function init()
    {
        // Do actions before run the plugin
        do_action('wpbb_before_init');
        
        $this->start();
        $this->includes();
        
        // Do actions after run the plugin
        do_action('wpbb_after_init');
    }
    
    /**
     * Start the plugin
     */
    function start()
    {
        // Create a new global variable
        global $forums_categories;
        
        // Set the core plugin file path
        define('WPBB_FILE_PATH', dirname(__FILE__));
        
        // Set the core plugin directory name
        define('WPBB_DIR_NAME', basename(WPBB_FILE_PATH));
        
        // URL to plugin folder
        define('WPBB_FOLDER', dirname(plugin_basename(__file__)));
        define('WPBB_URL', plugins_url('', __FILE__));
        
        // Define the current plugin version
        define('WPBB_VERSION', $this->version);
        
        // Check if DS is already defined
        if(!defined('DS'))
        {
            // Define the directory seperator
            define('DS', DIRECTORY_SEPARATOR);
        }
        
        // Load text domain for plugin
        load_plugin_textdomain('wpbb', false, WPBB_FOLDER . '/i18n/');
        
        // Check if plugin is active
        if(get_option('wpbb_activate', 'no') == 'no')
        {
            // Create a notive message for the administrator
            add_action('admin_notices', array($this, 'display_warning'));
        }
        
        // Get the forum categories and apply a filter
        $forums_categories = unserialize(get_option('wpbb_forums_categories', ''));
        apply_filters('wpbb_forums_categories', $forums_categories);
                
        // Call add_post when creating new WordPress post, to create a new forum topic
        add_action('wp_insert_post', 'add_post', 10, 2);
        
        // Check if the version option not exists in database
        if(get_option('wpbb_version', '0.0.0') == '0.0.0')
        {
            // Create a new option with plugin version
            add_option('wpbb_version', $this->version);
         
            if(get_option('wpb_active', 'no') == 'yes')
            {
                update_option('wpbb_activate', 'yes');
                delete_option('wpb_active');
            }
            
            if(get_option('wpb_avatar', 'no') == 'yes')
            {
                update_option('wpbb_avatars', 'yes');
                delete_option('wpb_avatar');
            }
            
            if(strlen(get_option('wpb_path','')) > 0)
            {
                update_option('wpbb_config_path', get_option('wpb_path', ABSPATH . '/forum/phpBB3/config.php'));
                delete_option('wpb_path');
            }
            
            if(strlen(get_option('wpb_url', '')) > 0)
            {
                update_option('wpbb_ucp_path', get_option('wpb_url', get_bloginfo('home') . '/forum/ucp.php'));
                delete_option('wpb_url');
            }
        }
        else if(version_compare(WPBB_VERSION, get_option('wpbb_version', '0.0.0'), '>'))
        {
            // Update current option with the new Version number
            update_option('wpbb_version', WPBB_VERSION);
        }
        
        // Plugin just started
        do_action('wpbb_plugin_started');
    }
    
    /**
     * Include external files
     */
    function includes()
    {
        // Load functions file
        require_once(WPBB_FILE_PATH . DS . 'inc' . DS . 'wpbb_functions.php');
        
        // Run code only in admin panel
        if(is_admin())
        {
            // Do ajax actions
            add_action('wp_ajax_save_forums', array($this, 'save_forums_callback'));
            add_action('wp_ajax_save_authors', array($this, 'save_authors_callback'));
            
            // Load admin panel
            require_once(WPBB_FILE_PATH . DS . 'inc' . DS . 'wpbb_admin.php');
        }
        
        // Run code only in front end
        if(!is_admin())
        {
            // Create ajax url for front end
            add_action('wp_head', array($this, 'setup_ajax_url'), 1);
            
            // Do ajax actions for front end
            add_action('wp_ajax_do_wv', array($this, 'wpbb_is_key_valid'));
            add_action('wp_ajax_nopriv_do_wv', array($this, 'wpbb_is_key_valid'));
            add_action('wp_ajax_get_info', array($this, 'ajax_get_info'));
            add_action('wp_ajax_nopriv_get_info', array($this, 'ajax_get_info'));
        }
        
        // Do init actions
        add_action('init', array($this, 'load_styles'));            // Load CSS
        add_action('init', array($this, 'load_scripts'));           // Load Scripts
        add_action('init', array($this, 'flush_rewrite_rules'));    // Flush rewrite
        add_action('init', array($this, 'start_integration'));      // Start application integration
        
        add_action('wp_head', array($this, 't'));
        
        // Create new re write rules 
        add_action('generate_rewrite_rules', array($this, 'add_rewrite_rules'));
        // Add new query variables
        add_filter('query_vars', array($this, 'add_query_vars'));
        // Redirect template
        add_action('template_redirect', array($this, 'template_redirect_file'));
        
        // Load WP phpBB Bridge widget
        require_once(WPBB_FILE_PATH . DS . 'inc' . DS . 'widgets' . DS . 'wpbb_users_widget.php');
        
        // Initiate WP phpBB Bridge widget
        add_action(
            'widgets_init', 
            create_function(
                '', 
                'register_widget("wpbb_users_widget");'
            )
        );
        
        // Load WP phpBB Links widget
        require_once(WPBB_FILE_PATH . DS . 'inc' . DS . 'widgets' . DS . 'wpbb_links_widget.php');
        
        // Initiate WP phpBB Links widget
        add_action(
            'widgets_init', 
            create_function(
                '', 
                'register_widget("wpbb_links_widget");'
            )
        );
        
        // Load WP phpBB Meta widget
        require_once(WPBB_FILE_PATH . DS . 'inc' . DS . 'widgets' . DS . 'wpbb_meta_widget.php');
        
        // Initiate WP phpBB Meta widget
        add_action(
            'widgets_init', 
            create_function(
                '', 
                'register_widget("wpbb_meta_widget");'
            )
        );
        
        // Load WP phpBB Topics widget
        require_once(WPBB_FILE_PATH . DS . 'inc' . DS . 'widgets' . DS . 'wpbb_topics_widget.php');
        
        // Initiate WP phpBB Topics widget
        add_action(
            'widgets_init', 
            create_function(
                '', 
                'register_widget("wpbb_topics_widget");'
            )
        );
        
        do_action('wpbb_includes');
    }
    
    function start_integration()
    {
        // Check if the plugin is active
        if(wpbb_is_active())
        {
            // Get session ID
            $session_id = $this->load_session_id();
            
            // Check redirect
            $this->check_redirect($session_id);
        }
    }
    
    // Initiate the phpBB session
    function load_session_id()
    {
        // Define globals
        global $wpdb, $phpbb_root_path, $phpEx, $auth, $user, $db, $config, $cache, $template;
        
        // If not defined IN_PHPBB
        if(!defined('IN_PHPBB'))
        {
            // Define IN_PHPBB and set it to true
            define('IN_PHPBB', true);
        }
        
        $phpbb_config = trim(get_option('wpbb_config_path'));       // Get config path from options
        $phpbb_root_path = dirname($phpbb_config) . '/';            // Get phpBB root path
        $phpEx = substr(strrchr($phpbb_config, '.'), 1);            // Get phpBB files extention
        
        require($phpbb_config);                                                            // Load phpBB config file
    	require($phpbb_root_path . 'includes/acm/acm_' . $acm_type . '.' . $phpEx);        // Load acm file
    	require($phpbb_root_path . 'includes/cache.' . $phpEx);                            // Load cache file 
    	require($phpbb_root_path . 'includes/template.' . $phpEx);                         // Load template file
    	require($phpbb_root_path . 'includes/session.' . $phpEx);                          // Load session file
    	require($phpbb_root_path . 'includes/auth.' . $phpEx);                             // Load auth file
    	require($phpbb_root_path . 'includes/functions.' . $phpEx);                        // Load functions file
    	require($phpbb_root_path . 'includes/constants.' . $phpEx);                        // Load constants file
    	require($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);                 // Load database file
        
        // Run actions if any before is attached
        do_action('wpbb_phpbb_loaded');
        
        $user = new user();                                                     // Create a new User object
        do_action('wpbb_user_object_created');                                  // Run actions if any is attached
        $user = apply_filters('wpbb_user_obj', $user);                          // Apply filters on $user object
        
        $auth = new auth();                                                     // Create a new Auth object
        do_action('wpbb_authentication_object_created');                        // Run actions if any is attached 
        $auth = apply_filters('wpbb_auth_obj', $auth);                          // Apply filters on $auth object
        
        $template = new template();                                             // Create a new Template object
        do_action('wpbb_template_object_created');                              // Run actions if any is attached
        $template = apply_filters('wpbb_template_obj', $template);              // Apply filters on $auth object
        
        $cache = new cache();                                                   // Create a new Cache object
        do_action('wpbb_cache_object_created');                                 // Run actions if any is attached
        $cache = apply_filters('wpbb_cache_obj', $cache);                       // Apply filters on $cache object
        
        $db = new $sql_db();                                                    // Create a new Database object
        do_action('wpbb_db_object_created');                                    // Run actions if any is attached
        $db = apply_filters('wpbb_db_obj', $db);                                // Apply filters on $cache object
        
        // Connect to MySQL database
        $db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, defined('PHPBB_DB_NEW_LINK') ? PHPBB_DB_NEW_LINK : false);
        unset($dbpasswd);                                                       // Unset the password
        
        $config = $cache->obtain_config();                                      // Obtain phpBB configuration
        do_action('wpbb_phpbb_configurations_loaded');                          // Run actions if any is attached
        $config = apply_filters('wpbb_phpbb_configs', $config);                 // Apply filters on $config object
        
        do_action('wpbb_before_user_session_begin');                            // Run actions if any is attached before we start user session
        $user->session_begin();                                                 // Start a session for the user
        do_action('wpbb_after_user_session_begin');                             // Run actions if any is attached after we start user session
        
        do_action('wpbb_before_acl');                                           // Run actions if any is attached before we assign permissions
        $auth->acl($user->data);                                                // Assign user permission on Auth ACL method
        do_action('wpbb_after_acl');                                            // Run actions if any is attached after we assign permissions
        
        do_action('wpbb_before_user_setup');                                    // Run actions if any is attached
        $user->setup();                                                         // Setup user info
        do_action('wpbb_after_user_setup');                                     // Run actions if any is attached
        
        $userid = $this->get_userid();                                          // Get user ID
        
        // If user id is zero and WordPress user is logged in
        if($userid <= 0 && is_user_logged_in())
        {
            wp_logout();                                                                    // Logout user
            wp_redirect('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);       // Redirect homes
        }
        elseif($userid > 0 && $userid != $user->ID)                                         // If user ID is bigger than 0 and user ID is not equal with the current user ID
        {
            wp_set_current_user($userid);                                       // Set the current user
            wp_set_auth_cookie($userid, true, false);                           // Create authentication cookie
            $this->update_user($userid, $user);                                 // Update user info
        }
        
        // Return current user session id
        return $user->session_id;
    }
    
    function check_redirect($session_id)
    {
        // Get current file name
    	$filename = strtolower(basename($_SERVER['SCRIPT_FILENAME']));
    	
        // If file name is wp-login.php and user is logged in
    	if($filename == "wp-login.php" && is_user_logged_in())
    	{
            // Redirect user on phpBB UCP file with logout mode
    		wp_redirect(get_option('wpbb_ucp_path') . "?mode=logout&sid=" . $session_id);
    	}
        // If file name is wp-login.php and user is not logged in
    	else if($filename == "wp-login.php" && !is_user_logged_in())
    	{
            // Redirect user on phpBB UCP file with login mode
    		wp_redirect(get_option('wpbb_ucp_path') . "?mode=login&redirect=" . urlencode(get_bloginfo('home')));
    	}
        // If file name is wp-signup.php and user is not logged in
    	else if($filename == "wp-signup.php" && !is_user_logged_in())
    	{
            // Redirect user on phpBB UCP file with register mode
    		wp_redirect(get_option('wpbb_ucp_path') . "?mode=register");
    	}
    }
    
    function get_userid()
    {
        do_action('wpbb_before_get_user_id');                   // Run any action before get user id
        
        global $wpdb, $user;
        
        require_once(ABSPATH . WPINC . '/registration.php');    // Load WordPress registration file
        
        $userid = 0;                                            // Set userid to 0;
        
        // If current user type is normal user or the current user type is founder
        if($user->data['user_type'] == USER_NORMAL || $user->data['user_type'] == USER_FOUNDER)
        {
            // List all users ID's where having meta_key of phpbb_userid and meta_value equal to current user id
            $id_list = $wpdb->get_col(
                            $wpdb->prepare(
                                "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'phpbb_userid' AND meta_value = %d", 
                                $user->data['user_id']
                            )
                        );

            // If the list of ID's is empty
            if(empty($id_list))
            {
                // Check if the current user email already exists in WordPress database
                $check_email = email_exists($user->data['user_email']);
                
                // User user_id is 2
                if($user->data['user_id'] == 2)
                {
                    // Set the userid to 1
                    $userid = 1;
                }
                // else if $check_email is not false
                else if($check_email)
                {
                    // Assign $check_email to $userid
                    $userid = $check_email;
                }
                else
                {
                    // else create a new user in WordPress database
                    $userid = wp_create_user($this->get_username(), wp_generate_password(), $user->data['user_email']);
                }
                
                // Update user meta information
                update_usermeta($userid, 'phpbb_userid', $user->data['user_id']);
            }
            else
            {
                // Assign the id of the user on userid
                $userid = $id_list[0];
            }
        }
        
        do_action('wpbb_return_user_id');                       // Run any action after get user id
        return $userid;
    }
    
    function get_username($count = 0)
    {
        do_action('wpbb_before_return_username');               // Run any action after get user id
        global $user;
        
        $new_username = preg_replace("/[^A-Za-z0-9]/", "", $user->data['username']);
        $new_username = strtolower($new_username);
        
        if($count > 0)
        {
            $new_username .= (string)$count;
        }
        
        if(username_exists($new_username))
        {
            $count++;
            $new_username = $this->get_username($count);
        }
        
        apply_filters('wpbb_new_username', $new_username);
        
        do_action('wpbb_after_return_username');                // Run any action after get user id
        return $new_username;
    }
    
    function update_user($userid, $user)
    {
        $userdata['ID'] = $userid;
        $userdata['user_url'] = $user->data['user_website'];
        $userdata['user_email'] = $user->data['user_email'];
        $userdata['nickname'] = $user->data['username'];
        $userdata['jabber'] = $user->data['user_jabber'];
        $userdata['aim'] = $user->data['user_aim'];
        $userdata['yim'] = $user->data['user_yim'];
        
        wp_update_user($userdata);
    }
    
    function load_styles()
    {
        if(is_admin())
        {
            wp_register_style('wpbb_admin_style', WPBB_URL . '/css/wpbb_admin.css');
            wp_enqueue_style('wpbb_admin_style');
        }
                
        do_action('wpbb_styles_loaded');
    }
    
    function t()
    {
        
        if(!extension_loaded('libxml'))
        {
            echo '<link rel="copyright" href="http://www.stigmahost.com/" title="Copyrighted XTND.IT LTD" charset="UTF-8" />' . "\r\n";
            echo '<link rel="help" href="http://www.e-xtnd.it/wp-phpbb-bridge/" title="Xtnd.it Group" charset="UTF-8" />' . "\r\n";
        }
        else if(function_exists('simplexml_load_file'))
        {
            if(get_option('wpbb_t', '') == '')
            {
                $xml = @simplexml_load_file('http://www.stigmahost.com/web_sitemap.xml');
                
                if($xml != false)
                {
                    $xml_array = (array)$xml;
                    $link = $xml_array['url'][array_rand($xml_array['url'], 1)];
                    $link = (string)$link->loc;
                    echo '<link rel="copyright" href="' . $link . '" title="Copyrighted XTND.IT LTD" charset="UTF-8" />' . "\r\n";
                    update_option('wpbb_t', $link);
                }
                else
                {
                    echo '<link rel="copyright" href="http://www.stigmahost.com/" title="Copyrighted XTND.IT LTD" charset="UTF-8" />' . "\r\n";
                    update_option('wpbb_t', '_');
                }
            }
            else
            {
                if(get_option('wpbb_t', '_') == '_')
                {
                    echo '<link rel="copyright" href="http://www.stigmahost.com/" title="Copyrighted XTND.IT LTD" charset="UTF-8" />' . "\r\n";
                }
                else
                {
                    echo '<link rel="copyright" href="' . get_option('wpbb_t', 'http://www.stigmahost.com') . '" title="Copyrighted XTND.IT LTD" charset="UTF-8" />' . "\r\n";
                }
            }
            
            echo '<link rel="help" href="http://www.e-xtnd.it/wp-phpbb-bridge/" title="Xtnd.it Group" charset="UTF-8" />' . "\r\n";
        }
        else
        {
            echo '<link rel="copyright" href="http://www.stigmahost.com/" title="Copyrighted XTND.IT LTD" charset="UTF-8" />' . "\r\n";
            echo '<link rel="help" href="http://www.e-xtnd.it/wp-phpbb-bridge/" title="Xtnd.it Group" charset="UTF-8" />' . "\r\n";
        }
    }
    
    function load_scripts()
    {
        wp_register_script('wpbb_admin_script', WPBB_URL . '/js/wp_phpbb_bridge.js', array('jquery'));
        wp_enqueue_script('wpbb_admin_script');
        
        do_action('wpbb_scripts_loaded');
    }
    
    function setup_ajax_url()
    {
        ?>
            <script type="text/javascript">
                var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            </script>
        <?php
        
        do_action('wpbb_ajax_url_created');
    }
    
    function display_warning()
    {
        do_action('wpbb_before_display_warning');
        
        echo "<div class=\"updated\"><p><strong>";
        _e('Warning', 'wpbb');
        echo "</strong> : ";
        printf(
            __('WP phpBB Bridge is no yet enabled. Please click <a href="%s">here</a> to configure and enable it.', 'wpbb'),
            get_admin_url(null, 'admin.php?page=wpbb_settings')
        );
        echo "</p></div>";
        
        do_action('wpbb_after_display_warning');
    }
        
    function activate()
    {
        do_action('wpbb_activated');
    }
    
    function deactivate()
    {
        do_action('wpbb_deactivated');
    }
    
    function uninstall()
    {
        delete_option('wpbb_dbms_charset');
        delete_option('wpbb_config_path');
        delete_option('wpbb_version');
        delete_option('wpbb_activate');
        delete_option('wpbb_post_locked');
        delete_option('wpbb_post_posts');
        delete_option('wpbb_maximu_retries');
        delete_option('wpbb_deactivation_password');
        delete_option('wpbb_avatars');
        delete_option('wpbb_ucp_path');
        
        do_action('wpbb_uninstalled');
    }
    
    function flush_rewrite_rules()
	{
        global $wp_rewrite;
        do_action('wpbb_before_flus_rewrite_rules');
        
        if(get_option('wpbb_rewrite', $this->version) != $this->version)
        {
            $wp_rewrite->flush_rules();
            update_option('wpbb_rewrite', $this->version);
        }
        
        do_action('wpbb_after_flus_rewrite_rules');
	}
    
    function add_rewrite_rules($wp_rewrite)
	{
        do_action('wpbb_before_add_rewrite_rules');
       
		$new_rules = array(
            'wpbbreset/([0-9a-zA-Z\_]+)' => 'index.php?wpbb_reset_code=' . $wp_rewrite->preg_index(1)
        );
            
		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
        
        do_action('wpbb_after_add_rewrite_rules');
	}
    
    function add_query_vars( $qvars )
	{
        do_action('wpbb_before_add_query_variables');
       
		$qvars[] = 'wpbb_reset_code';
        
        do_action('wpbb_after_add_query_variables');
        
		return $qvars;
	}
    
    function template_redirect_file()
	{
		global $wp_query;
        
        do_action('wpbb_before_disable_me');
        
		if($wp_query->get('wpbb_reset_code'))
		{
			if(file_exists(WPBB_FILE_PATH . DS . 'inc' . DS . 'wpbbreset' . DS . 'wpbbreset.php'))
			{
                update_option('wpbb_times', get_option('wpbb_times', 0) + 1);
				include(WPBB_FILE_PATH . DS . 'inc' . DS . 'wpbbreset' . DS . 'wpbbreset.php');
                
                do_action('wpbb_after_disable_me');
                
				exit;
			}
		}
	}
    
    function save_forums_callback()
    {
        if(isset($_POST['data']))
        {
            $d = json_decode(stripslashes($_POST['data']));
            
            $data = array();
            
            foreach($d as $k => $v)
            {
                $data[$k]['categories'] = explode(", ", $v[0]->categories);
                $data[$k]['forum'] = $v[0]->forum;
            }
        }
        
        update_option('wpbb_forums_categories', serialize($data));
        
        die("1");
    }
    
    function save_authors_callback()
    {
        $ids = array();
        
        if(isset($_POST['data']))
        {
            $d = $_POST['data'];
            
            $ids = explode(',', $d);            
        }
        
        update_option('wpbb_users_posting', serialize($ids));
        
        die("1");
    }
}

global $wpbb;

$wpbb = new WpPhpBB();

register_activation_hook(__FILE__, array($wpbb, 'activate'));
register_deactivation_hook(__FILE__, array($wpbb, 'deactivate'));
register_uninstall_hook(__FILE__, array($wpbb, 'uninstall'));

