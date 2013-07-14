<?php
/*  Copyright 2012  Scott Cariss  (email : scott@l3rady.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Not a WordPress context? Stop.
! defined( 'ABSPATH' ) and exit;

// Only load class if it hasn't already been loaded
if ( ! class_exists( 'sc_WordPressFileMonitorPlusSettings' ) )
{
    class sc_WordPressFileMonitorPlusSettings
    {
        static public function init()
        {
            add_action( 'init', array( __CLASS__, 'settings_up_to_date' ), 9 );
            add_action( 'admin_init', array( __CLASS__, 'admin_register_scripts_styles' ) );
            add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
            add_action( 'admin_init', array( __CLASS__, 'admin_plugin_actions' ) );
            add_action( 'admin_init', array( __CLASS__, 'admin_settings_init' ) );
            add_filter( 'plugin_action_links', array( __CLASS__, 'plugin_action_links' ), 10, 2 );
        }


        /**
         * Check if this plugin settings are up to date. Firstly check the version in
         * the DB. If they don't match then load in defaults but don't override values
         * already set. Also this will remove obsolete settings that are not needed.
         * Since version 2.1 we also check that data files are in the correct upload
         * directory.
         *
         * @return void
         */
        static public function settings_up_to_date()
        {
            // Get current plugin version
            $current_ver = get_option( sc_WordPressFileMonitorPlus::$settings_option_field_ver );

            // Does the current version number in DB store match the current version
            if( sc_WordPressFileMonitorPlus::$settings_option_field_current_ver == $current_ver )
                return;

            // Get existing options
            $options = (array) maybe_unserialize( get_option( sc_WordPressFileMonitorPlus::$settings_option_field ) );

            // Are we before 1.3? If so we need to do a conversion process to 1.4
            if( isset( $current_ver ) && ( $current_ver <= 1.4 ) )
                $options = self::update_settings_pre_1_4_to_1_4( $options );

            if( isset( $current_ver ) && ( $current_ver <= 2.1 ) )
                $options = self::update_settings_pre_2_1_to_2_1( $options );

            // Default setting values for WPFMP
            $defaults = array(
                'cron_method' => 'wordpress', // Cron method to be used for scheduling scans
                'file_check_interval' => 'daily', // How often should the cron run
                'notify_by_email' => 1, // Do we want to be notified by email when there is a file change?
                'from_address' => get_option( 'admin_email' ), // Email address the notification comes from
                'notify_address' => get_option( 'admin_email' ), // Email address the notification is sent to
                'site_root' => realpath( ABSPATH ), // The file check path to start checking files from
                'exclude_paths_files' => array(), // What files and dirs should we ignore?
                'file_check_method' => array(
                    'size' => 1, // Should we log the filesize of files?
                    'modified' => 1, // Should we log the modified date of files?
                    'md5' => 1 // Should we log the hash of files using md5_file()?
                ),
                'display_admin_alert' => 1, // Do we allow the plugin to notify us when there is a change in the admin area?
                'is_admin_alert' => 0, // Is there a live admin alert?
                'security_key' => sha1( microtime( true ) . mt_rand( 10000, 90000 ) ), // Generate a random key to be used for Other Cron Method
                // The security key is only shown to the admin and has to be used for triggering a manual scan via an external cron.
                // This is to stop non admin users from being able to trigger the cron and potentially abuse server resources.
                'file_extension_mode' => 0, // 0 = Disabled, 1 = ignore below extensions, 2 = only scan below extensions.
                'file_extensions' => array('jpg', 'jpeg', 'jpe', 'gif', 'png', 'bmp', 'tif', 'tiff', 'ico'), // List of extensions separated by pipe.
                'last_scan_time' => false
            );

            // Intersect current options with defaults. Basically removing settings that are obsolete
            $options = array_intersect_key( $options, $defaults );

            // Merge current settings with defaults. Basically adding any new settings with defaults that we don't have.
            $options = array_merge( $defaults, $options );

            // Update settings and version number
            update_option( sc_WordPressFileMonitorPlus::$settings_option_field, $options ); // update settings
            update_option( sc_WordPressFileMonitorPlus::$settings_option_field_ver, sc_WordPressFileMonitorPlus::$settings_option_field_current_ver ); // update settings version

            // Check that data files exist
            if( file_exists( SC_WPFMP_FILE_SCAN_DATA ) && file_exists( SC_WPFMP_FILE_ALERT_CONTENT ) )
                return;

            // Check dir exists, if not make it.
            if( ! is_dir( SC_WPFMP_DATA_FOLDER ) )
                mkdir( SC_WPFMP_DATA_FOLDER );

            // Files don't exist so copy them across.
            self::xcopy( SC_WPFMP_DATA_FOLDER_OLD, SC_WPFMP_DATA_FOLDER );
        }


        /**
        * Upgrades settings from pre version 1.4 to version 1.4
        *
        * Now combined all excluding of files and dirs into one
        * setting that now uses fnmatch(). Because of this the old
        * settings need copying over to the new setting as well as
        * converting to an fnmatch() compatible format.
        *
        * @param array $options
        * @return array $options
        */
        static private function update_settings_pre_1_4_to_1_4( $options )
        {
            $options['exclude_paths_files'] = array();

            if( isset( $options['exclude_paths'] ) )
            {
                foreach( $options['exclude_paths'] as $exclude )
                    $options['exclude_paths_files'][] = rtrim( $exclude, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . "*";
            }

            if( isset( $options['exclude_files'] ) )
            {
                foreach( $options['exclude_files'] as $exclude )
                    $options['exclude_paths_files'][] = $exclude;
            }

            if( isset( $options['exclude_paths_wild'] ) )
            {
                foreach( $options['exclude_paths_wild'] as $exclude )
                    $options['exclude_paths_files'][] = "*" . DIRECTORY_SEPARATOR . trim( $exclude, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR . "*";
            }

            if( isset( $options['exclude_files_wild'] ) )
            {
                foreach( $options['exclude_files_wild'] as $exclude )
                    $options['exclude_paths_files'][] = "*" . DIRECTORY_SEPARATOR . ltrim( $exclude, DIRECTORY_SEPARATOR );
            }

            return $options;
        }


        /**
         * Version 2.1 no longer uses DB store for data and alert, so do a little clean up.
         * Also clear admin alert as the new data layout changed would break an existing alert.
         *
         * @param array $options
         * @return array $options
         */
        static private function update_settings_pre_2_1_to_2_1( $options )
        {

            // Clear any previous alert that might be in DB as new plugin settings
            $options['is_admin_alert'] = 0;

            delete_option( "sc_wpfmp_scan_data" );
            delete_option( "sc_wpfmp_admin_alert_content" );

            return $options;
        }


        /**
         * Resisters plugin JS for enqueue elsewhere.
         * Also registers thickbox on all admin isf needed
         *
         * @return void
         */
        static public function admin_register_scripts_styles()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field ); // get settings

            // Register plugin settings page JS
            wp_register_script( 'wordpress_file_monitor_plus_js_function', plugins_url( 'js/function.js', SC_WPFMP_PLUGIN_FILE ), array( 'jquery' ), '1.2', true );

            // Don't load thickbox if not needed. Must have a warning to show
            if( 1 != $options['is_admin_alert'] || 1 != $options['display_admin_alert'] || ! current_user_can( SC_WPFMP_ADMIN_ALERT_PERMISSION ) )
                return;

            // add_thickbox() is a WordPress function
            add_action( "admin_enqueue_scripts", 'add_thickbox' );
        }


        /**
         * Adds plugin settings page.
         * Tells where to enqueue plugin scripts
         *
         * @return void
         */
        static public function add_settings_page()
        {
            $page = add_options_page( 'WordPress File Monitor Plus', 'WordPress File Monitor Plus', 'manage_options', 'wordpress-file-monitor-plus', array( __CLASS__, 'settings_page' ) );
            add_action( "admin_print_scripts-{$page}" , array( __CLASS__, 'enqueue_plugin_script' ) );
        }


        /**
         * Enqueues plugin script
         *
         * @return void
         */
        static public function enqueue_plugin_script()
        {
            wp_enqueue_script( 'wordpress_file_monitor_plus_js_function' );
        }


        static public function admin_plugin_actions()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field ); // get settings

            // No action to preform if all variables are not set.
            if( ! isset( $_GET['sc_wpfmp_action'] ) || ! isset( $_GET['page'] ) || ! current_user_can( SC_WPFMP_ADMIN_ALERT_PERMISSION ) || "wordpress-file-monitor-plus" != $_GET['page'] )
                return;

            // Switch through actions
            switch( $_GET['sc_wpfmp_action'] )
            {
                // Manual scan?
                case "sc_wpfmp_scan":
                    do_action( sc_WordPressFileMonitorPlus::$cron_name );
                    add_settings_error( "sc_wpfmp_settings_main", "sc_wpfmp_settings_main_error", __( "Manual scan completed", "wordpress-file-monitor-plus" ), "updated" );
                break;

                // Reset settings?
                case "sc_wpfmp_reset_settings":
                    delete_option( sc_WordPressFileMonitorPlus::$settings_option_field );
                    delete_option( sc_WordPressFileMonitorPlus::$settings_option_field_ver );
                    self::settings_up_to_date();
                    add_settings_error( "sc_wpfmp_settings_main", "sc_wpfmp_settings_main_error", __( "Settings reset", "wordpress-file-monitor-plus" ), "updated" );
                break;

                // Clear admin alert
                case "sc_wpfmp_clear_admin_alert":
                    $options['is_admin_alert'] = 0;
                    update_option( sc_WordPressFileMonitorPlus::$settings_option_field, $options );
                    add_settings_error( "sc_wpfmp_settings_main", "sc_wpfmp_settings_main_error", __( "Admin alert cleared", "wordpress-file-monitor-plus" ), "updated" );
                break;

                // View admin alert?
                case "sc_wpfmp_view_alert":
                    $alert_content = sc_WordPressFileMonitorPlus::getPutAlertContent();
                    die( $alert_content );
                break;

                // Wrong action...
                default:
                    add_settings_error( "sc_wpfmp_settings_main", "sc_wpfmp_settings_main_error", __( "Invalid action encountered", "wordpress-file-monitor-plus" ), "error" );
                break;
            }
        }


        /**
        * Adds settings/manual scan link on plugin list
        *
        * @param array $links
        * @param string $file
        * @return array $links
        */
        static public function plugin_action_links( $links, $file )
        {
            static $this_plugin;

            if ( ! $this_plugin )
                $this_plugin = "wordpress-file-monitor-plus/wordpress-file-monitor-plus.php";

            if ( $this_plugin == $file )
            {
                $settings_link = '<a href="' . admin_url( "options-general.php?page=wordpress-file-monitor-plus" ) . '">' . __( "Settings", "wordpress-file-monitor-plus" ) . '</a>';
                array_unshift( $links, $settings_link );
                $settings_link = '<a href="' . admin_url( "options-general.php?page=wordpress-file-monitor-plus&sc_wpfmp_action=sc_wpfmp_scan" ) . '">' . __( "Manual Scan", "wordpress-file-monitor-plus" ) . '</a>';
                array_unshift($links, $settings_link);
            }

            return $links;
        }


        /*
         * EVERYTHING SETTINGS
         *
         * I'm not going to comment any of this as its all pretty
         * much straight forward use of the WordPress Settings API.
         */
        static public function settings_page()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field ); // Get settings
            ?>
            <div class="wrap">
                <?php screen_icon('options-general'); ?>
                <h2><?php _e( "WordPress File Monitor Plus", "wordpress-file-monitor-plus" ); ?></h2>
                <p>
                    <span class="description">
                    <?php
                    if( false === $options["last_scan_time"] )
                        $scan_date = __("Never", "wordpress-file-monitor-plus");
                    else
                        $scan_date = apply_filters( "sc_wpfmp_format_file_modified_time", NULL, $options["last_scan_time"] );

                    echo sprintf( __("Last scanned: %s", "wordpress-file-monitor-plus"), $scan_date );
                    ?>
                    </span>
                </p>
                <form action="options.php" method="post">
                    <?php
                    $_SERVER['REQUEST_URI'] = remove_query_arg( array( 'sc_wpfmp_action', 'sc_wpfmp_scan', 'sc_wpfmp_reset_settings', 'sc_wpfmp_clear_admin_alert', 'sc_wpfmp_clear_admin_alert' ) );
                    settings_fields( "sc_wpfmp_settings" );
                    do_settings_sections( "wordpress-file-monitor-plus" );
                    ?>
                    <p class="submit">
                        <?php submit_button( __( "Save changes", "wordpress-file-monitor-plus" ), "primary", "submit", false ); ?>
                        <input class="button" name="submitwithemail" type="submit" value="<?php _e("Save settings with test email", "wordpress-file-monitor-plus"); ?>" />
                        <a class="button-secondary" href="<?php echo admin_url( "options-general.php?page=wordpress-file-monitor-plus&sc_wpfmp_action=sc_wpfmp_scan" ); ?>"><?php _e( "Manual scan", "wordpress-file-monitor-plus" ); ?></a>
                        <a class="button-secondary" href="<?php echo admin_url( "options-general.php?page=wordpress-file-monitor-plus&sc_wpfmp_action=sc_wpfmp_reset_settings" ); ?>"><?php _e( "Reset settings to defaults", "wordpress-file-monitor-plus" ); ?></a>
                    </p>
                </form>
            </div>
            <?php
        }


        static public function admin_settings_init()
        {
            register_setting( sc_WordPressFileMonitorPlus::$settings_option_field, sc_WordPressFileMonitorPlus::$settings_option_field, array( __CLASS__, "sc_wpfmp_settings_validate" ) );
            add_settings_section( "sc_wpfmp_settings_main", __( "Settings", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_text" ), "wordpress-file-monitor-plus" );
            add_settings_field( "sc_wpfmp_settings_main_cron_method", __( "Cron Method", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_cron_method" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_file_check_interval", __( "File Check Interval", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_file_check_interval" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_notify_by_email", __( "Notify By Email", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_notify_by_email" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_notify_address", __( "Notify Email Address", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_notify_address" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_from_address", __( "From Email Address", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_from_address" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_display_admin_alert", __( "Admin Alert", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_display_admin_alert" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_file_check_method", __( "File Check Method", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_file_check_method" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_site_root", __( "File Check Root", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_site_root" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_exclude_paths_files", __( "Dirs/Files To Ignore", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_exclude_paths_files" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_file_extension_mode", __( "File Extensions Scan", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_file_extension_mode" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
            add_settings_field( "sc_wpfmp_settings_main_file_extensions", __( "File Extensions", "wordpress-file-monitor-plus" ), array( __CLASS__, "sc_wpfmp_settings_main_field_file_extensions" ), "wordpress-file-monitor-plus", "sc_wpfmp_settings_main" );
        }
        
        
        static public function sc_wpfmp_settings_validate( $input )
        {
            $valid = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            
            if( in_array( $input['cron_method'], array( "wordpress", "other" ) ) )
                $valid['cron_method'] = $input['cron_method'];
            else
                add_settings_error( "sc_wpfmp_settings_main_cron_method", "sc_wpfmp_settings_main_cron_method_error", __( "Invalid cron method selected", "wordpress-file-monitor-plus" ), "error" );

            if("other" == $valid['cron_method'])
                $input['file_check_interval'] = "manual";

            if( in_array( $input['file_check_interval'], sc_WordPressFileMonitorPlus::$frequency_intervals ) )
            {
                $valid['file_check_interval'] = $input['file_check_interval'];
                sc_WordPressFileMonitorPlus::enable_cron( $input['file_check_interval'] );
            } else
            {
                add_settings_error( "sc_wpfmp_settings_main_file_check_interval", "sc_wpfmp_settings_main_file_check_interval_error", __( "Invalid file check interval selected", "wordpress-file-monitor-plus" ), "error" );
            }

            $sanitized_notify_by_email = absint($input['notify_by_email']);
            
            if( 1 === $sanitized_notify_by_email || 0 === $sanitized_notify_by_email )
                $valid['notify_by_email'] = $sanitized_notify_by_email;
            else
                add_settings_error( "sc_wpfmp_settings_main_notify_by_email", "sc_wpfmp_settings_main_notify_by_email_error", __( "Invalid notify by email selected", "wordpress-file-monitor-plus" ), "error" );

            $emails_to = explode( ",", $input['notify_address'] );
            if( ! empty( $emails_to ) )
            {
                $sanitized_emails = array();
                $was_error = false;
                foreach( $emails_to as $email_to )
                {
                    $address = sanitize_email( trim( $email_to ) );
                    if( ! is_email( $address ) )
                    {
                        add_settings_error( "sc_wpfmp_settings_main_notify_address", "sc_wpfmp_settings_main_notify_address_error", __( "One or more email to addresses are invalid", "wordpress-file-monitor-plus" ), "error" );
                        $was_error = true;
                        break;
                    }
                    $sanitized_emails[] = $address;
                }
                if( ! $was_error)
                    $valid['notify_address'] = implode(',', $sanitized_emails);
            } else
            {
                add_settings_error( "sc_wpfmp_settings_main_notify_address", "sc_wpfmp_settings_main_notify_address_error", __( "No email to address entered", "wordpress-file-monitor-plus" ), "error" );
            }

            $sanitized_email_from = sanitize_email($input['from_address']);
            
            if( is_email( $sanitized_email_from ) )
                $valid['from_address'] = $sanitized_email_from;
            else
                add_settings_error( "sc_wpfmp_settings_main_from_address", "sc_wpfmp_settings_main_from_address_error", __( "Invalid from email address entered", "wordpress-file-monitor-plus" ), "error" );
            
            $sanitized_display_admin_alert = absint($input['display_admin_alert']);
            
            if( 1 === $sanitized_display_admin_alert || 0 === $sanitized_display_admin_alert )
                $valid['display_admin_alert'] = $sanitized_display_admin_alert;
            else
                add_settings_error( "sc_wpfmp_settings_main_display_admin_alert", "sc_wpfmp_settings_main_display_admin_alert_error", __( "Invalid display admin alert selected", "wordpress-file-monitor-plus" ), "error" );
            
            $valid['file_check_method'] = array_map( array( __CLASS__, 'file_check_method_func' ), $input['file_check_method'] );
            
            $sanitized_site_root = realpath( $input['site_root'] );
            
            if( is_dir( $sanitized_site_root ) && is_readable( $sanitized_site_root ) )
                $valid['site_root'] = $sanitized_site_root;
            else
                add_settings_error( "sc_wpfmp_settings_main_site_root", "sc_wpfmp_settings_main_site_root_error", __( "File check root is not valid. Make sure that PHP has read permissions of the entered file check root", "wordpress-file-monitor-plus" ), "error" );
            
            $valid['exclude_paths_files'] = self::textarea_newlines_to_array( $input['exclude_paths_files'] );
            
            $sanitized_file_extension_mode = absint( $input['file_extension_mode'] );
            
            if( 2 === $sanitized_file_extension_mode || 1 === $sanitized_file_extension_mode || 0 === $sanitized_file_extension_mode )
                $valid['file_extension_mode'] = $sanitized_file_extension_mode;
            else
                add_settings_error( "sc_wpfmp_settings_main_file_extension_mode", "sc_wpfmp_settings_main_file_extension_mode_error", __( "Invalid file extension mode selected", "wordpress-file-monitor-plus" ), "error" );
            
            $valid['file_extensions'] = self::file_extensions_to_array( $input['file_extensions'] );

            if( isset( $_POST['submitwithemail'] ) )
                add_filter( 'pre_set_transient_settings_errors', array( __CLASS__, "send_test_email" ) );

            return $valid;
        }


        static public function send_test_email( $settings_errors )
        {
            if( isset( $settings_errors[0]['type'] ) && $settings_errors[0]['type'] == "updated" )
                sc_WordPressFileMonitorPlus::send_notify_email( __( "This is a test message from WordPress File Monitor Plus.", "wordpress-file-monitor-plus" ) );
        }


        static public function sc_wpfmp_settings_main_text()
        {
            return;
        }
        
        
        static public function sc_wpfmp_settings_main_field_cron_method()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?>
            <select name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[cron_method]">
                <option value="wordpress" <?php selected( $options['cron_method'], "wordpress" ); ?>><?php _e( "WordPress Cron", "wordpress-file-monitor-plus" ); ?></option>
                <option value="other" <?php selected( $options['cron_method'], "other" ); ?>><?php _e( "Other Cron", "wordpress-file-monitor-plus" ); ?></option>
            </select>
            <div>
                <br />
                <span class="description"><?php _e( "Cron Command: ", "wordpress-file-monitor-plus" ); ?></span>
                <pre>wget -q "<?php echo site_url(); ?>/index.php?sc_wpfmp_scan=1&amp;sc_wpfmp_key=<?php echo $options['security_key']; ?>" -O /dev/null >/dev/null 2>&amp;1</pre>
            </div>
            <?php
        }
        
        
        static public function sc_wpfmp_settings_main_field_file_check_interval()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?>
            <select name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[file_check_interval]">
                <option value="<?php echo sc_WordPressFileMonitorPlus::$frequency_intervals[0]; ?>" <?php selected( $options['file_check_interval'], sc_WordPressFileMonitorPlus::$frequency_intervals[0] ); ?>><?php _e( "Hourly", "wordpress-file-monitor-plus" ); ?></option>
                <option value="<?php echo sc_WordPressFileMonitorPlus::$frequency_intervals[1]; ?>" <?php selected( $options['file_check_interval'], sc_WordPressFileMonitorPlus::$frequency_intervals[1] ); ?>><?php _e( "Twice Daily", "wordpress-file-monitor-plus" ); ?></option>
                <option value="<?php echo sc_WordPressFileMonitorPlus::$frequency_intervals[2]; ?>" <?php selected( $options['file_check_interval'], sc_WordPressFileMonitorPlus::$frequency_intervals[2] ); ?>><?php _e( "Daily", "wordpress-file-monitor-plus" ); ?></option>
                <option value="<?php echo sc_WordPressFileMonitorPlus::$frequency_intervals[3]; ?>" <?php selected( $options['file_check_interval'], sc_WordPressFileMonitorPlus::$frequency_intervals[3] ); ?>><?php _e( "Manual", "wordpress-file-monitor-plus" ); ?></option>
            </select>
            <?php
        }
        
        
        static public function sc_wpfmp_settings_main_field_notify_by_email()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?>
            <select name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[notify_by_email]">
                <option value="1" <?php selected( $options['notify_by_email'], 1 ); ?>><?php _e( "Yes", "wordpress-file-monitor-plus" ); ?></option>
                <option value="0" <?php selected( $options['notify_by_email'], 0 ); ?>><?php _e( "No", "wordpress-file-monitor-plus" ); ?></option>
            </select>
            <?php
        }


        static public function sc_wpfmp_settings_main_field_notify_address()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?><input class="regular-text" name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[notify_address]" value="<?php echo $options['notify_address']; ?>" />  <span class="description"><?php _e("Separate multiple email address with a comma (,)", "wordpress-file-monitor-plus"); ?></span><?php
        }


        static public function sc_wpfmp_settings_main_field_from_address()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?><input class="regular-text" name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[from_address]" value="<?php echo $options['from_address']; ?>" /><?php
        }


        static public function sc_wpfmp_settings_main_field_display_admin_alert()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?>
            <select name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[display_admin_alert]">
                <option value="1" <?php selected( $options['display_admin_alert'], 1 ); ?>><?php _e( "Yes", "wordpress-file-monitor-plus" ); ?></option>
                <option value="0" <?php selected( $options['display_admin_alert'], 0 ); ?>><?php _e( "No", "wordpress-file-monitor-plus" ); ?></option>
            </select>
            <?php
        }


        static public function sc_wpfmp_settings_main_field_file_check_method()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?>
            <input name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[file_check_method][size]" type="checkbox" value="1" <?php checked( $options['file_check_method']['size'], 1 ); ?> /><?php _e( " File Size", "wordpress-file-monitor-plus" ); ?><br />
            <input name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[file_check_method][modified]" type="checkbox" value="1" <?php checked( $options['file_check_method']['modified'], 1 ); ?> /><?php _e( " Date Modified", "wordpress-file-monitor-plus" ); ?><br />
            <input name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[file_check_method][md5]" type="checkbox" value="1" <?php checked( $options['file_check_method']['md5'], 1 ); ?> /><?php _e( " File Hash", "wordpress-file-monitor-plus" ); ?>
            <?php
        }


        static public function sc_wpfmp_settings_main_field_site_root()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?><input name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[site_root]" value="<?php echo $options['site_root']; ?>" /> <span class="description"><?php printf(__( "Default: %s", "wordpress-file-monitor-plus" ), realpath(ABSPATH)); ?></span><?php
        }


        static public function sc_wpfmp_settings_main_exclude_paths_files()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?><textarea name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[exclude_paths_files]" cols="60" rows="8"><?php echo implode("\n", $options['exclude_paths_files']); ?></textarea><?php
        }


        static public function sc_wpfmp_settings_main_field_file_extension_mode()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?>
            <select name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[file_extension_mode]">
                <option value="0" <?php selected( $options['file_extension_mode'], 0 ); ?>><?php _e( "Disabled", "wordpress-file-monitor-plus" ); ?></option>
                <option value="1" <?php selected( $options['file_extension_mode'], 1 ); ?>><?php _e( "Exclude files that have an extension listed below", "wordpress-file-monitor-plus" ); ?></option>
                <option value="2" <?php selected( $options['file_extension_mode'], 2 ); ?>><?php _e( "Only scan files that have an extension listed below", "wordpress-file-monitor-plus" ); ?></option>
            </select>
            <?php
        }


        static public function sc_wpfmp_settings_main_field_file_extensions()
        {
            $options = get_option( sc_WordPressFileMonitorPlus::$settings_option_field );
            ?><input class="regular-text" name="<?php echo sc_WordPressFileMonitorPlus::$settings_option_field ?>[file_extensions]" value="<?php echo implode($options['file_extensions'], "|" ); ?>" /> <span class="description"><?php _e( "Separate extensions with | character.", "wordpress-file-monitor-plus" ); ?></span><?php
        }


        /**
         * Anything not a 1 is made 0
         *
         * @param int $n value to check
         * @return int $n value as 1 or 0
         */
        static protected function file_check_method_func( $n )
        {
            $n = absint( $n );

            if( 1 !== $n )
                $n = 0;

            return $n;
        }


        /**
         * Takes multiline input from textarea and splits newlines into an array.
         *
         * @param string $input Text from textarea
         * @return array $output
         */
        static protected function textarea_newlines_to_array( $input )
        {
            $output = (array) explode( "\n", $input ); // Split textarea input by new lines
            $output = array_map( 'trim', $output ); // trim whitespace off end of line.
            $output = array_filter( $output ); // remove empty lines from array
            return $output;
        }


        /**
         * Takes extension list "foo|bar|foo|bar" and converts into array.
         *
         * @param string $input Extension list from settings page input
         * @return array $output
         */
        static protected function file_extensions_to_array( $input )
        {
            $output = strtolower( $input ); // set all to lower case
            $output = preg_replace( "/[^a-z0-9|]+/", "", $output ); // strip characters that cannot make up valid extension
            $output = (array) explode( "|", $output ); // Split into array
            $output = array_filter( $output ); // remove empty entries from array
            return $output;
        }


        /**
         * Recursively copy a folder
         *
         * @param string $src Source folder
         * @param string $dest Destination folder
         */
        static public function xcopy( $src, $dest )
        {
            foreach( scandir( $src ) as $file )
            {
                if ( '.' == $file || '..' == $file || ! is_readable( $src . DIRECTORY_SEPARATOR . $file ) )
                    continue;

                if ( is_dir( $file ) )
                {
                    mkdir( $dest . DIRECTORY_SEPARATOR . $file );
                    self::xcopy( $src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file );
                } else
                {
                    copy( $src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file );
                }
            }
        }


        /**
         * Delete folder and all its contents
         *
         * @param $dir string Directory of folder to be deleted.
         * @return bool Returns false on none directory passed.
         */
        static public function rrmdir( $dir )
        {
            if( ! is_dir( $dir ) )
                return false;

            $objects = scandir( $dir );

            foreach ( $objects as $object )
            {
                if( in_array( $object, array( ".", ".." ) ) )
                    continue;

                if ( is_dir( $dir . DIRECTORY_SEPARATOR . $object ) )
                    self::rrmdir( $dir. DIRECTORY_SEPARATOR .$object );
                else
                    unlink( $dir . DIRECTORY_SEPARATOR . $object );
            }

            reset( $objects );
            rmdir( $dir );

            return true;
        }


        /**
         * Function that runs on uninstall. Called from the uninstall.php file
         */
        static public function uninstall()
        {
            delete_option( "sc_wpfmp_settings" );
            delete_option( "sc_wpfmp_settings_ver" );

            self::rrmdir( SC_WPFMP_DATA_FOLDER );
        }
    }
}
?>