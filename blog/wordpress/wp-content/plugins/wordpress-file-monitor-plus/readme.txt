=== Plugin Name ===
Contributors: l3rady
Donate link: http://l3rady.com/donate
Tags: security, files, monitor, plugin
Requires at least: 3.1
Tested up to: 3.4
Stable tag: 2.2

Monitor files under your WP installation for changes.  When a change occurs, be notified via email. This plugin is a fork of WordPress File Monitor.

== Description ==

Monitors your WordPress installation for added/deleted/changed files.  When a change is detected an email alert can be sent to a specified address.

*Features*

- Monitors file system for added/deleted/changed files
- Sends email when a change is detected
- Administration area alert to notify you of changes in case email is not received
- Ability to monitor files for changes based on file hash, time stamp and/or file size
- Ability to exclude files and directories from scan (for instance if you use a caching system that stores its files within the monitored zone)
- Site URL included in notification email in case plugin is in use on multiple sites
- Ability to run the file checking via an external cron so not to slow down visits to your website and to give greater flexibility over scheduling
- Ability to set file extension to be ignored or only scanned.
- Multisite support.

This plugin is a fork of [WordPress File Monitor](http://wordpress.org/extend/plugins/wordpress-file-monitor/). This plugin was forked because there seemed to be no further development on the existing plugin and there was no way to contact the original author to ask about taking ownership. WordPress File Monitor Plus has the following improvements over Updates Notifier:

- Completely rewritten from the ground up using best practises for writing WordPress plugins
- Code wrapped in a class so better namespace.
- You can set the cron interval, allowing for more frequent checks.
- Cron can be run externally to allow finer control over when the scan runs.
- No code is inserted into the front end website.
- Allows you to set the 'from address'.
- A couple more configurable options.
- Makes use of the Settings API.
- A number of available hooks and filters for advanced users.
- Active support and development.

*Languages*

- Arabic by [Yaser](http://www.englize.com/) - *Added 06 March 2012*
- German by [Johannes Freudendahl](http://freudendahl.net/) - *Added 26 March 2012*
- Japanese by [o6asan](http://o6asan.com/BLOG-J/) - *Updated 06 March 2012*
- Russian by [Flector](http://www.wordpressplugins.ru/) - *Added 23 April 2012*

== Installation ==

* Upload to a directory named "wordpress-file-monitor-plus" in your plugins directory (usually wp-content/plugins/).
* Activate plugin
* Visit Settings page under Settings -> WordPress File Monitor Plus in your WordPress Administration Area
* Configure plugin settings
* Optionally change the path to the 'File Check Root'.  If you install WordPress inside a subdirectory for instance, you could set this to the directory above that to monitor files outside of the WordPress installation.

== Frequently Asked Questions ==

= I don't think the plugin is working. What should I do? =

[Enable debuging mode in WordPress] and run the plugin and see if any errors are reported from the plugin. If you find errors and don't know how to fix head over to the [WordPress forums]

[Enable debuging mode in WordPress]: http://codex.wordpress.org/Editing_wp-config.php#Debug
[WordPress forums]: http://wordpress.org/tags/wordpress-file-monitor-plus?forum_id=10

= I'm running this plugin on a multi-site. What should I do? =

Install the plugin via the network admin section and network activate the plugin. By doing this the plugin will actually only install and run on the main multi-site site. This way the plugin is silent to all child blogs. To configure and setup the plugin the settings can be found in its usual place on the main site only.

= Only admins can see the admin alert. Is it possible to let other user roles see the admin notice? =

Yes you can, add the following code to your wp-config.php file: `define('SC_WPFMP_ADMIN_ALERT_PERMISSION', 'capability');` and change the capability to a level you want. Please visit [Roles and Capabilities] to see all available capabilities that you can set to.

[Roles and Capabilities]: http://codex.wordpress.org/Roles_and_Capabilities

= My website has error_log files that are always changing. How do I get WPFMP to ignore them? =

In settings you can add `error_log` to 'File Names To Ignore' if you want to ignore the error_log file in any directory or add `/full/path/to/error_log` to 'Exact Files To Ignore' if you just want to ignore one perticular error log

= How do I get WPFMP to ignore multiple files and directories? =

Each of the settings 'File Names To Ignore/Dir Names To Ignore/Exact Files To Ignore/Exact Dirs To Ignore' allow multiple entries. To allow multiple entries make sure each entry is on a new line.

= What is the 'other cron' method? =

What this does is stops WordPress from running the 'File Check Scan' on the built in cron scheduler and allows you to run it externally. If you know how to setup a cron externally its recommended that you use this method. WordPress by default has a limited number of scan intervals which wont allow you to run the file cron at lets say 2:46AM and then every 3 hours. An external cron gives you greater flexibility on when to run the file monitor scan.

= I'm worried that the data files that are created by your plugin are viewable by the public, which poses a security risk. =

This plugin ships with a .htaccess file that denies any access to any file in the data dir. But if you feel you want to add more security you can CHMOD the two files `.sc_wpfmp_scan_data` and `.sc_wpfmp_admin_alert_content` to 0600 which only allows the owner (PHP) read and write access.

== Screenshots ==

1. Settings page
2. Admin alert
3. Admin changed files report
4. Email changed files report

== Changelog ==

= 2.2 =
* Added a last scanned date and time to settings page.
* Added the ability to set more than one notify address by separating email addresses by a comma (,).
* Added 'save settings with test email' button to settings screen.
* Uninstall now cleans up its data dir usually found in wp-content/uploads
* Tested and works with WordPress 3.4

= 2.1 =
* Changed data directory to WordPress uploads dir so that when updating plugin in future you don't lose your old scan data.
* Dropped the option to allow the choice on where to store data. The save method is always `file` now.

= 2.0.1 =
* Fixed bug that would stop external cron from working since version 2.0.

= 2.0 =
* Full refactor of code, optimizing, documenting and overall tidy.
* Corrected the way plugin includes thickbox in admin so viewing report always shows properly ( If you give thickbox chance to load before clicking button ).
* Better multisite support.

= 1.4.1 =
* fnmatch() wasn't working on Windows. Added `FNM_NOESCAPE` to fnmatch and changed code for fnmatch compatibility function to allow `FNM_NOESCAPE`.

= 1.4 =
* Fixed notices
* Added wildcard (*) support to exclude files/dirs using [fnmatch](http://php.net/manual/en/function.fnmatch.php). This will allow more control over what is ignored. Upon upgrading your existing exclude settings will be combined and converted to the new fnmatch format.
* Removed formatRawSize() function and replaced with size_format() that comes with WordPress already.

= 1.3 =
* Auto remove trailing slash off exact dirs to ignore.
* Altered `sc_wpfmp_format_file_modified_time` filter to include original file timestamp to give better way of generating output.
* Included new settings that allow you to ignore files with certain extensions or only scan files with certain extensions.
* Removed document root from being stored with every directory. This cuts down on memory usage and storage usage.
* Added a number of filters that can allow other plugin authors to add directories and files to be ignored by WPFMP. E.G. a WP caching plugin can automatically tell WPFMP to auto ignore it's cache directory.

= 1.2.1 =
* Fixed settings bug when installing fresh install.

= 1.2 =
* Edited external cron command to not output anything to file system.
* Re-coded many parts.
* Made use of the Settings API.
* Created a filter to deal with formatting the file modified time in the report. This filter makes use of default WordPress settings and correctly shows the modified time in your set timezone.
* If saving to file, the two files that are needed to be ignored are now auto ignored rather than relying on the user to add them to the ignore file list.
* Made use of `DIRECTORY_SEPARATOR` constant to make sure compatibility with Windows OS and backslash directories.
* Added functionality to reset settings to defaults.
* Added manual scan quick link to plugin listing.
* Added clear admin alert link to the email that's sent.

= 1.1.1 =
* Added .htaccess file to the data directory just in case your web host doesn't already block access to dot files.
* Wrapped wget URL with quotes to make work properly. Thank you Luciano Passuello for spotting this.

= 1.1 =
* Added setting to be able to save scan data and admin alert content to file rather than the database.

= 1.0 =
* Initial release.