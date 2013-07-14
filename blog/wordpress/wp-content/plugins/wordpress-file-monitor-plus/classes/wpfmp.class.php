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
if ( ! class_exists( 'sc_WordPressFileMonitorPlus' ) )
{
    class sc_WordPressFileMonitorPlus
    {
        static public $settings_option_field = "sc_wpfmp_settings"; // Option name for settings
        static public $settings_option_field_ver = "sc_wpfmp_settings_ver"; // Option name for settings version
        static public $settings_option_field_current_ver = "2.2"; // Current settings version
        static public $cron_name = "sc_wpfmp_scan"; // Name of cron
        static public $frequency_intervals = array("hourly", "twicedaily", "daily", "manual"); // What cron schedules are available

        static public function init()
        {
            // Set up language
            load_plugin_textdomain( 'wordpress-file-monitor-plus', false, dirname( plugin_basename( SC_WPFMP_PLUGIN_FILE ) ) . '/languages/' );

            // Deal with activation and deactivation
            register_activation_hook( SC_WPFMP_PLUGIN_FILE, array( __CLASS__, 'activate' ) );
            register_deactivation_hook( SC_WPFMP_PLUGIN_FILE, array( __CLASS__, 'deactive' ) );

            // Create and/or setup actions
            add_action( 'init', array( __CLASS__, 'external_cron_run_check' ) ); // Add a check for external cron trigger
            add_action( 'admin_notices', array( __CLASS__, 'admin_alert' ) ); // Admin alert show in dashboard
            add_action( self::$cron_name, array( __CLASS__, 'scan' ) ); // Create a hook for scanning
            add_action( 'sc_wpfmp_enable_wp_cron', array( __CLASS__, 'enable_cron' ) ); // Create a hook for enabling WPFMP cron
            add_action( 'sc_wpfmp_disable_wp_cron', array( __CLASS__, 'disable_cron' ) ); // Create a hook for disabling WPFMP cron
            add_action( 'sc_wpfmp_send_notify_email', array( __CLASS__, 'send_notify_email' ) ); // Create a hook for sending alert email

            // Create Plugin Filters
            add_filter('sc_wpfmp_format_file_modified_time', array(__CLASS__, 'format_file_modified_time'), 10, 2); // Create filter for formatting the file modified time
        }


        /**
        * What to do when plugin is activated
        *
        * @return void
        */
        static public function activate()
        {
            do_action( "sc_wpfmp_enable_wp_cron" ); // Go enable cron
        }


        /**
        * What to do when plugin is deactivated
        *
        * @return void
        */
        static public function deactive()
        {
            do_action( "sc_wpfmp_disable_wp_cron" ); // Go disable cron
        }


        /**
        * Sets up cron schedule in WP if needed.
        *
        * @param bool|string $manual_interval
        * @return void
        */
        static public function enable_cron( $manual_interval = false )
        {
            $options = get_option( self::$settings_option_field ); // Get settings
            $currentSchedule = wp_get_schedule( self::$cron_name ); // find if a schedule already exists

            // if a manual cron interval is set, use this
            if( ! empty( $manual_interval ) )
                $options['file_check_interval'] = $manual_interval;

            if( "manual" == $options['file_check_interval'] )
            {
                do_action( "sc_wpfmp_disable_wp_cron" ); // Make sure no cron is setup as we are manual
            } else
            {
                // check if the current schedule matches the one set in settings
                if( $currentSchedule == $options['file_check_interval'] )
                    return;

                // check the cron setting is valid
                if( ! in_array( $options['file_check_interval'], self::$frequency_intervals ) )
                    return;

                // Remove any cron's for this plugin first so we don't end up with multiple cron's doing the same thing.
                do_action( "sc_wpfmp_disable_wp_cron" );

                // Schedule cron for this plugin.
                wp_schedule_event( time(), $options['file_check_interval'], self::$cron_name );
            }
        }


        /**
        * Remove any WordPress cron our plugin may have created
        *
        * @return void
        */
        static public function disable_cron()
       {
            wp_clear_scheduled_hook( self::$cron_name );
       }


        /**
        * Check if external cron is being triggered
        *
        * @return void
        */
        static public function external_cron_run_check()
        {
            $options = get_option( self::$settings_option_field ); // get settings

            // Check if a scan is being requested externally
            // and that the correct security key is provided
            // and the that the settings allow an external cron.
            if( ! isset( $_GET['sc_wpfmp_scan'] ) || ! isset( $_GET['sc_wpfmp_key'] ) || 1 != $_GET['sc_wpfmp_scan'] || $options['security_key'] != $_GET['sc_wpfmp_key'] || "other" != $options['cron_method'] )
                return;

            // Go do scan
            do_action( 'sc_wpfmp_scan' );

            // Die with message saying scan ran
            die( __( "Scan Successful", "wordpress-file-monitor-plus" ) );
        }


        /**
        * Scan files and compare new scan data against old
        *
        * @return void
        */
        static public function scan()
        {
            $options = get_option( self::$settings_option_field ); // Get settings

            // Set time of this scan.
            $options['last_scan_time'] = time();
            update_option( self::$settings_option_field, $options );

            // Get old data from DB/file
            $oldScanData = self::getPutScanData();

            // Get new data by scanning
            $newScanData = (array) self::scan_dirs();

             // Lets make sure that the new data is always sorted
            ksort( $newScanData );

            // Save new scan data to DB/file
            self::getPutScanData( "put", $newScanData );

            // Only do checks for file ammends/additions/removals if we have some old data to compare against
            if( ! is_array( $oldScanData ) )
                return;

            // See what files have been added and removed since last scan
            $files_added = array_diff_assoc( $newScanData, $oldScanData );
            $files_removed = array_diff_assoc( $oldScanData, $newScanData );

            // Build compare arrays by removing `added` and `removed` files
            $comp_newdata = array_diff_key( $newScanData, $files_added );
            $comp_olddata = array_diff_key( $oldScanData, $files_removed );

            // Do compare
            $changed_files = self::array_compare( $comp_newdata, $comp_olddata );

            // Get counts of files added/removed/changed
            $count_files_changed = count( $changed_files[0] );
            $count_files_added = count( $files_added );
            $count_files_removed = count( $files_removed );

            // Check we have some changes since last scan by checking above counts
            if( ! max( $count_files_changed, $count_files_added, $count_files_removed ) )
                return;

            // Generate HTML alert
            $alertMessage = self::format_alert( $files_added, $files_removed, $changed_files, $oldScanData, $newScanData );

            // Save HTML alert into DB/file
            self::getPutAlertContent( "put", $alertMessage );

            // Update options to say there is an admin alert to be shown
            $options["is_admin_alert"] = 1;
            update_option( self::$settings_option_field, $options );

            // Are we to notify by email? If so lets send email alert
            if( 1 == $options['notify_by_email'] )
                do_action( "sc_wpfmp_send_notify_email", $alertMessage );

        }


        /**
         * Recursively scan directories
         *
         * @param string $path full path to scan
         * @return array $dirs holds array of all captured files and their details.
        */
        static protected function scan_dirs( $path = "" )
        {
            // Set settings as static so not to repeat get options as we repeat.
            static $options;

            // Are the settings set?
            if( ! $options )
            {
                $options = get_option( self::$settings_option_field ); // Get settings

                // Add WPFMP data files to exclude list
                $options['exclude_paths_files'][] = SC_WPFMP_FILE_SCAN_DATA;
                $options['exclude_paths_files'][] = SC_WPFMP_FILE_ALERT_CONTENT;

                // Allow other developers to use filters to remove/add extensions and paths/files to settings
                if( 1 == $options['file_extension_mode'] )
                    $options['file_extensions'] = apply_filters( "sc_wpfmp_filter_ignore_extensions", $options['file_extensions'] );
                elseif( 2 == $options['file_extension_mode'] )
                    $options['file_extensions'] = apply_filters( "sc_wpfmp_filter_scan_extensions", $options['file_extensions'] );

                $options['exclude_paths_files'] = apply_filters( "sc_wpfmp_filter_exclude_paths_files", $options['exclude_paths_files'] );
            }

            $dirs = array();

            $handle = opendir( $options['site_root'] . $path );

            if( ! $handle )
                return $dirs;

            // loop through dirs/files
            while ( false !== ( $file = readdir( $handle ) ) )
            {
                // Ignore . and ..
                if( "." == $file || ".." == $file )
                    continue;

                $full_file_name = $path . DIRECTORY_SEPARATOR . $file;
                $full_dir_file_name = $options['site_root'] . $full_file_name;

                // Are we to exclude paths/files? Yes lets check
                if( isset( $options['exclude_paths_files'] ) )
                {
                    // loop through dirs/folders to exclude
                    foreach( $options['exclude_paths_files'] as $exclude )
                    {
                        // If matches then don't go deeper
                        if( fnmatch( $exclude, $full_dir_file_name, FNM_NOESCAPE ) )
                            continue 2;
                    }
                }

                // Directory? else file
                if( 'dir' === filetype( $full_dir_file_name ) )
                {
                    // We are on a directory lets go one deeper
                    $dirs = array_merge( (array) $dirs, (array) self::scan_dirs( $full_file_name ) );
                } else
                {
                    // Are we ignoring extensions and is this file extension in the list?
                    if( 1 == $options['file_extension_mode'] && in_array( strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ), $options['file_extensions'] ) )
                        continue;

                    // Are we only allowing certain extensions and is this file extension in the list?
                    if( 2 == $options['file_extension_mode'] && ! in_array( strtolower( pathinfo( $file, PATHINFO_EXTENSION ) ), $options['file_extensions'] ) )
                        continue;

                    $dirs[$full_file_name] = array();

                    if( 1 == $options['file_check_method']['size'] )
                        $dirs[$full_file_name]["size"] = filesize( $full_dir_file_name );

                    if( 1 == $options['file_check_method']['modified'] )
                        $dirs[$full_file_name]["modified"] = filemtime( $full_dir_file_name );

                    if( 1 == $options['file_check_method']['md5'] )
                        $dirs[$full_file_name]["md5"] = md5_file( $full_dir_file_name );
                }
            }

            // Close connection
            closedir( $handle );

            return $dirs;
        }


        /**
        * Creates HTML for email and admin alert
        *
        * @param array $files_added Array holding any files that have been added
        * @param array $files_removed Array holding any files that have been removed
        * @param array $changed_files Array holding any files that have been changed
        * @param array $oldScanData Array holding all files in old scan data
        * @param array $newScanData Array holding all files in new scan data
        * @return string $alertMessage return formatted HTML
        */
        static protected function format_alert( $files_added, $files_removed, $changed_files, $oldScanData, $newScanData )
        {
            $options = get_option( self::$settings_option_field ); // Get settings

            $alertMessage = "";

            if( 1 == $options['display_admin_alert'] )
                $alertMessage .= "<a class='button-secondary' href='" . admin_url( "options-general.php?page=wordpress-file-monitor-plus&sc_wpfmp_action=sc_wpfmp_clear_admin_alert" ) . "'>" . __( "Clear admin alert", "wordpress-file-monitor-plus" ) . "</a><br /><br />";

            $alertMessage .= sprintf( __( "Files Changed: %d", "wordpress-file-monitor-plus" ), count( $changed_files[0] ) ) . "<br />";
            $alertMessage .= sprintf( __( "Files Added: %d", "wordpress-file-monitor-plus" ), count( $files_added ) ) . "<br />";
            $alertMessage .= sprintf( __( "Files Removed: %d", "wordpress-file-monitor-plus" ), count( $files_removed ) ) . "<br />";
            $alertMessage .= "<br />";
            
             // Only do this if some changed files
            if( count( $changed_files[0] ) >= 1 )
            {
                $alertMessage .= "<strong>" . __( "Files Changed:", "wordpress-file-monitor-plus" ) . "</strong>";
                $alertMessage .= "<table class='widefat' width='100%' border='1' cellspacing='0' cellpadding='2'>";
                $alertMessage .= "  <thead>";
                $alertMessage .= "  <tr>";
                $alertMessage .= "    <th width='100%'>" . __( "File", "wordpress-file-monitor-plus" ) . "</th>";

                if( 1 == $options['file_check_method']['size'] )
                {
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "New Filesize", "wordpress-file-monitor-plus" ) . "</th>";
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "Old Filesize", "wordpress-file-monitor-plus" ) . "</th>";
                }

                if( 1 == $options['file_check_method']['modified'] )
                {
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "New Modified", "wordpress-file-monitor-plus" ) . "</th>";
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "Old Modified", "wordpress-file-monitor-plus" ) . "</th>";
                }

                if( 1 == $options['file_check_method']['md5'] )
                {
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "New Hash", "wordpress-file-monitor-plus" ) . "</th>";
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "Old Hash", "wordpress-file-monitor-plus" ) . "</th>";
                }

                $alertMessage .= "  </tr>";
                $alertMessage .= "  </thead>";
                $alertMessage .= "  <tbody>";

                foreach( $changed_files[0] as $key => $data )
                {
                    $alertMessage .= "  <tr>";
                    $alertMessage .= "    <td>" . $key . "</td>";

                    if( 1 == $options['file_check_method']['size'] )
                    {
                        $alertMessage .= "    <td nowrap='nowrap'>" . size_format( $newScanData[$key]["size"] ) . "</td>";
                        $alertMessage .= "    <td nowrap='nowrap'>" . size_format( $oldScanData[$key]["size"] ) . "</td>";
                    }

                    if( 1 == $options['file_check_method']['modified'] )
                    {
                        $alertMessage .= "    <td nowrap='nowrap'>" . apply_filters( "sc_wpfmp_format_file_modified_time", NULL, $newScanData[$key]["modified"] ) . "</td>";
                        $alertMessage .= "    <td nowrap='nowrap'>" . apply_filters( "sc_wpfmp_format_file_modified_time", NULL, $oldScanData[$key]["modified"] ) . "</td>";
                    }

                    if( 1 == $options['file_check_method']['md5'] )
                    {
                        $alertMessage .= "    <td nowrap='nowrap'>" . $newScanData[$key]["md5"] . "</td>";
                        $alertMessage .= "    <td nowrap='nowrap'>" . $oldScanData[$key]["md5"] . "</td>";
                    }

                    $alertMessage .= "  </tr>";
                }

                $alertMessage .= "  </tbody>";
                $alertMessage .= "</table>";
                $alertMessage .= "<br /><br />";
            }
            
            if( count( $files_added ) >= 1 )
            {
                $alertMessage .= "<strong>" . __( "Files Added:", "wordpress-file-monitor-plus" ) . "</strong>";
                $alertMessage .= "<table class='widefat' width='100%' border='1' cellspacing='0' cellpadding='2'>";
                $alertMessage .= "  <thead>";
                $alertMessage .= "  <tr>";
                $alertMessage .= "    <th width='100%'>" . __( "File", "wordpress-file-monitor-plus" ) . "</th>";

                if( 1 == $options['file_check_method']['size'] )
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "New Filesize", "wordpress-file-monitor-plus" ) . "</th>";

                if( 1 == $options['file_check_method']['modified'] )
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "New Modified", "wordpress-file-monitor-plus" ) . "</th>";

                if( 1 == $options['file_check_method']['md5'] )
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "New Hash", "wordpress-file-monitor-plus" ) . "</th>";

                $alertMessage .= "  </tr>";
                $alertMessage .= "  </thead>";
                $alertMessage .= "  <tbody>";

                foreach( $files_added as $key => $data )
                {
                    $alertMessage .= "  <tr>";
                    $alertMessage .= "    <td>" . $key . "</td>";

                    if( 1 == $options['file_check_method']['size'] )
                        $alertMessage .= "    <td nowrap='nowrap'>" . size_format( $newScanData[$key]["size"] ) . "</td>";

                    if( 1 == $options['file_check_method']['modified'] )
                        $alertMessage .= "    <td nowrap='nowrap'>" . apply_filters( "sc_wpfmp_format_file_modified_time", NULL, $newScanData[$key]["modified"] ) . "</td>";

                    if( 1 == $options['file_check_method']['md5'] )
                        $alertMessage .= "    <td nowrap='nowrap'>" . $newScanData[$key]["md5"] . "</td>";

                    $alertMessage .= "  </tr>";
                }

                $alertMessage .= "  </tbody>";
                $alertMessage .= "</table>";
                $alertMessage .= "<br /><br />";
            }

            if( count( $files_removed ) >= 1 )
            {
                $alertMessage .= "<strong>" . __( "Files Removed:", "wordpress-file-monitor-plus" ) . "</strong>";
                $alertMessage .= "<table class='widefat' width='100%' border='1' cellspacing='0' cellpadding='2'>";
                $alertMessage .= "  <thead>";
                $alertMessage .= "  <tr>";
                $alertMessage .= "    <th width='100%'>" . __( "File", "wordpress-file-monitor-plus" ) . "</th>";

                if( 1 == $options['file_check_method']['size'] )
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "Old Filesize", "wordpress-file-monitor-plus" ) . "</th>";

                if( 1 == $options['file_check_method']['modified'] )
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "Old Modified", "wordpress-file-monitor-plus" ) . "</th>";

                if( 1 == $options['file_check_method']['md5'] )
                    $alertMessage .= "    <th nowrap='nowrap'>" . __( "Old Hash", "wordpress-file-monitor-plus" ) . "</th>";

                $alertMessage .= "  </tr>";
                $alertMessage .= "  </thead>";
                $alertMessage .= "  <tbody>";

                foreach( $files_removed as $key => $data )
                {
                    $alertMessage .= "  <tr>";
                    $alertMessage .= "    <td>" . $key . "</td>";

                    if( 1 == $options['file_check_method']['size'] )
                        $alertMessage .= "    <td nowrap='nowrap'>" . size_format( $oldScanData[$key]["size"] ) . "</td>";

                    if( 1 == $options['file_check_method']['modified'] )
                        $alertMessage .= "    <td nowrap='nowrap'>" . apply_filters( "sc_wpfmp_format_file_modified_time", NULL, $oldScanData[$key]["modified"] ) . "</td>";

                    if( 1 == $options['file_check_method']['md5'] )
                        $alertMessage .= "    <td nowrap='nowrap'>" . $oldScanData[$key]["md5"] . "</td>";

                    $alertMessage .= "  </tr>";
                }

                $alertMessage .= "  </tbody>";
                $alertMessage .= "</table>";
                $alertMessage .= "<br /><br />";
            }

            if( 1 == $options['display_admin_alert'] )
                $alertMessage .= "<a class='button-secondary' href='" . admin_url( "options-general.php?page=wordpress-file-monitor-plus&sc_wpfmp_action=sc_wpfmp_clear_admin_alert" ) . "'>" . __( "Clear admin alert", "wordpress-file-monitor-plus" ) . "</a><br /><br />";

            return $alertMessage;
        }


        /**
        * Sends admin alert email
        *
        * @param $alertMessage string
        * @return void
        */
        static public function send_notify_email( $alertMessage )
        {
            $options = get_option( self::$settings_option_field ); // Get settings

            // Build email subject and allow to be filtered
            $subject = apply_filters("sc_wpfmp_format_email_subject", sprintf( __( "WordPress File Monitor Plus: Alert (%s)", "wordpress-file-monitor-plus" ), site_url() ) );

            // Add filters for sending email
            add_filter( 'wp_mail_from', array( __CLASS__, 'sc_wpfmp_wp_mail_from' ) );
            add_filter( 'wp_mail_from_name', array( __CLASS__, 'sc_wpfmp_wp_mail_from_name' ) );
            add_filter( 'wp_mail_content_type', array( __CLASS__, 'sc_wpfmp_wp_mail_content_type' ) );

            // Send email
            wp_mail( $options['notify_address'], $subject, $alertMessage );

            // Remove previously added filters so not to be in place for other plugins
            remove_filter( 'wp_mail_from', array( __CLASS__, 'sc_wpfmp_wp_mail_from' ) );
            remove_filter( 'wp_mail_from_name', array( __CLASS__, 'sc_wpfmp_wp_mail_from_name' ) );
            remove_filter( 'wp_mail_content_type', array( __CLASS__, 'sc_wpfmp_wp_mail_content_type' ) );
        }


        /**
        * Set from address for email notification
        *
        * @return string $options['from_address']
        */
        static public function sc_wpfmp_wp_mail_from()
        {
            $options = get_option( self::$settings_option_field ); // Get settings
            return $options['from_address'];
        }


        /**
        * Set from name for email notification
        *
        * @return string $from_name
        */
        static public function sc_wpfmp_wp_mail_from_name()
        {
            return apply_filters( "sc_wpfmp_format_email_from_name", __( "WordPress File Monitor Plus", "wordpress-file-monitor-plus" ) );
        }


        /**
        * Set content type for email notification
        *
        * @return string
        */
        static public function sc_wpfmp_wp_mail_content_type()
        {
            return "text/html";
        }


        /**
        * Function deals with getting and putting scan data to and from DB or FILE
        *
        * @param string $getorput "get" to get data "put" to put data
        * @param string $data if putting data this should contain array of new scan data
        * @return array|string $data if getting data this should contain array of old scan data
        */
        static public function getPutScanData( $getorput = "get", $data = "" )
        {
            if( "get" == $getorput )
            {
                if( file_exists( SC_WPFMP_FILE_SCAN_DATA ) )
                    return maybe_unserialize( file_get_contents( SC_WPFMP_FILE_SCAN_DATA ) );

                return $data;
            }

            file_put_contents( SC_WPFMP_FILE_SCAN_DATA, maybe_serialize( $data ) );

            return $data;
        }


        /**
        * Function deals with getting and putting Admin Alert Content to and from DB or FILE
        *
        * @param string $getorput "get" to get data "put" to put data
        * @param string $data if putting data this should contain alert data
        * @return string $data if getting data this should contain alert data
        */
        static public function getPutAlertContent( $getorput = "get", $data = "" )
        {
            if( "get" == $getorput )
            {
                if( file_exists( SC_WPFMP_FILE_ALERT_CONTENT ) )
                    return file_get_contents( SC_WPFMP_FILE_ALERT_CONTENT );

                return "";
            }

            file_put_contents( SC_WPFMP_FILE_ALERT_CONTENT, $data );

            return $data;
        }


        /**
        * Admin notice
        *
        * @return void
        */
        public function admin_alert()
        {
            $options = get_option(self::$settings_option_field); // Get settings

            // Only show error notice if there is one, they are enabled and the user has permission to see the error
            if( 1 != $options['is_admin_alert'] || 1 != $options['display_admin_alert'] || ! current_user_can( SC_WPFMP_ADMIN_ALERT_PERMISSION ) )
                return;

            ?>
            <div class="error">
                <p>
                    <?php _e( "<strong>Warning!</strong> WordPress File Monitor Plus has detected a change in the files on your site.", "wordpress-file-monitor-plus" ); ?>
                    <br /><br />
                    <a class="button-secondary thickbox" href="<?php echo admin_url( "options-general.php?page=wordpress-file-monitor-plus&sc_wpfmp_action=sc_wpfmp_view_alert" ); ?>" title="<?php _e( "View file changes and clear this alert", "wordpress-file-monitor-plus" ); ?>"><?php _e( "View file changes and clear this alert", "wordpress-file-monitor-plus" ); ?></a>
                </p>
            </div>
            <?php
        }


        /**
         * Compares two arrays and returns the difference
         *
         * This is a function I picked up from PHP.net some time ago
         * and can no longer find the author so unable to give credit.
         *
         * @param array $array1
         * @param array $array2
         * @return array $diff
        */
        public function array_compare( $array1, $array2 )
        {
            $diff = false;

            foreach( $array1 as $key => $value )
            {
                if( ! array_key_exists( $key, $array2 ) )
                {
                    $diff[0][$key] = $value;
                } elseif ( is_array( $value ) )
                {
                    if ( ! is_array( $array2[$key] ) )
                    {
                        $diff[0][$key] = $value;
                        $diff[1][$key] = $array2[$key];
                    } else
                    {
                        $new = self::array_compare( $value, $array2[$key] );

                        if ( $new !== false )
                        {
                            if ( isset( $new[0] ) )
                                $diff[0][$key] = $new[0];

                            if ( isset( $new[1] ) )
                                $diff[1][$key] = $new[1];
                        }
                    }
                } elseif ( $array2[$key] !== $value )
                {
                    $diff[0][$key] = $value;
                    $diff[1][$key] = $array2[$key];
                }
            }

            foreach ( $array2 as $key => $value )
            {
                if ( ! array_key_exists( $key, $array1 ) )
                    $diff[1][$key] = $value;
            }

            return $diff;
        }


        /**
        * Filter for formatting the file modified time
        *
        * @param string $formatted
        * @param int $timestamp unix timestamp
        * @return string
        */
        public function format_file_modified_time( $formatted = NULL, $timestamp )
        {
            $date_format = get_option( 'date_format' ); // Get wordpress date format
            $time_format = get_option( 'time_format' ); // Get wordpress time format
            $gmt_offset = get_option( 'gmt_offset' ); // Get wordpress gmt offset

            return gmdate( $date_format." @ " . $time_format, ( $timestamp + ( $gmt_offset * 3600 ) ) );
        }
    }
}