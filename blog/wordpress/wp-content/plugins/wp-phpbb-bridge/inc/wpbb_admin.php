<?php

class WPBB_AdminPanel
{
    /**
     * Class Constructor
     */
    function WPBB_AdminPanel()
    {
        add_menu_page(
            'WP phpBB Bridge - Xtnd.it Group',
            'WP phpBB Bridge',
            'activate_plugins',
            'wpbb',
            array(
                $this,
                'WPBB_AdminPage'
            ),
            WPBB_URL . '/img/bridge.png',
            71
        );
        
        add_submenu_page(
            'wpbb',
            'WP phpBB Bridge ' . __('settings', 'wpbb'),
            __('Settings', 'wpbb'),
            'activate_plugins',
            'wpbb_settings',
            array(
                $this,
                'WPBB_SettingsPage'
            )
        );
        
        add_submenu_page(
            'wpbb',
            'WP phpBB Bridge ' . __('donators', 'wpbb'),
            __('Donators', 'wpbb'),
            'activate_plugins',
            'wpbb_donators',
            array(
                $this,
                'WPBB_Donators'
            )
        );
    }
    
    function WPBB_AdminPage()
    {
        do_action('wpbb_before_admin_dashboard');
        require_once(WPBB_FILE_PATH . DS . 'inc' . DS . 'admin_pages' . DS . 'dashboard.php');
        do_action('wpbb_after_admin_dashboard');
    }
    
    function WPBB_SettingsPage()
    {
        do_action('wpbb_before_admin_settings');
        
        if(isset($_POST['action']) && $_POST['action'] == 'update')
        {
            $e = new WP_Error();
            
            if(!wp_verify_nonce($_POST['_wpnonce'], 'wpbb_settings_page'))
            {    
                $e->add('access_denied', __('You submition does not meet the WordPress security level.', 'wpbb'));
            }
            else
            {
                $wpbb_activate = $_POST['wpbb_activate'];
                
                if(!is_file($_POST['wpbb_config_path']))
                {
                    $e->add('file_not_exists', __('The file config.php does not exists in the path you have enter', 'wpbb'));
                    $wpbb_activate == 'no';
                }
                
                if(!page_exists($_POST['wpbb_ucp_path']))
                {
                    $e->add('file_not_exists', __('The file ucp.php does not exists in the url you have enter', 'wpbb'));
                    $wpbb_activate == 'no';
                }
                
                $wpbb_avatars = $_POST['wpbb_avatars'];
                $wpbb_deactivation_password = $_POST['wpbb_deactivation_password'];
                $wpbb_dbms_charset = $_POST['wpbb_dbms_charset'];
                $wpbb_config_path = stripslashes($_POST['wpbb_config_path']);
                $wpbb_ucp_path = $_POST['wpbb_ucp_path'];
                $wpbb_maximu_retries = $_POST['wpbb_maximu_retries'];
                $wpbb_post_posts = isset($_POST['wpbb_post_posts']) ? 'yes' : 'no';
                $wpbb_post_locked = isset($_POST['wpbb_post_locked']) ? 'yes' : 'no';
                $wpbb_backlink = (isset($_POST['wpbb_backlink']) && $_POST['wpbb_backlink'] == 'wpbb_backlink') ? 1 : 0;
                
                update_option('wpbb_activate', $wpbb_activate);
                update_option('wpbb_config_path', $wpbb_config_path);
                update_option('wpbb_ucp_path', $wpbb_ucp_path);
                update_option('wpbb_avatars', $wpbb_avatars);
                update_option('wpbb_deactivation_password', $wpbb_deactivation_password);
                update_option('wpbb_dbms_charset', $wpbb_dbms_charset);
                update_option('wpbb_maximu_retries', $wpbb_maximu_retries);
                update_option('wpbb_post_posts', $wpbb_post_posts);
                update_option('wpbb_post_locked', $wpbb_post_locked);
                update_option('wpbb_width', $wpbb_width);
                update_option('wpbb_backlink', $wpbb_backlink);
            }
        }
        
        $wpbb_activate = trim(get_option('wpbb_activate', 'no'));
        $wpbb_avatars = trim(get_option('wpbb_avatars', 'no'));
    	$wpbb_config_path = trim(get_option('wpbb_config_path', ABSPATH . 'phpbb3/config.php'));
    	$wpbb_ucp_path = trim(get_option('wpbb_ucp_path', get_bloginfo('home') . '/phpbb3/ucp.php'));
        $wpbb_deactivation_password = trim(get_option('wpbb_deactivation_password', hash_generator()));
        $wpbb_dbms_charset = trim(get_option('wpbb_dbms_charset', 'utf8'));
        $wpbb_maximu_retries = trim(get_option('wpbb_maximu_retries', 3));
        $wpbb_post_posts = trim(get_option('wpbb_post_posts', 'yes'));
        $wpbb_post_locked = trim(get_option('wpbb_post_locked', 'yes'));
        $wpbb_width = trim(get_option('wpbb_width', __('Auto', 'wpbb')));
        $wpbb_backlink = trim(get_option('wpbb_backlink', true));
        
        require_once(WPBB_FILE_PATH . DS . 'inc' . DS . 'admin_pages' . DS . 'settings.php');
        
        do_action('wpbb_after_admin_settings');
    }
    
    function WPBB_Donators()
    {
        do_action('wpbb_before_donators');
        
        require_once(WPBB_FILE_PATH . DS . 'inc' . DS . 'admin_pages' . DS . 'donators.php');
        
        do_action('wpbb_after_donators');
    }
}

$wpbb_admin = null;

function create_admin_menu()
{
    global $wpbb_admin;
    $wpbb_admin = new WPBB_AdminPanel();
}

add_action('admin_menu', 'create_admin_menu');

?>