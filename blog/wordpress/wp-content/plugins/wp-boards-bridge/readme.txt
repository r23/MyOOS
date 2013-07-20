=== WP phpBB Bridge ===
Contributors: merianos
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=info%40xtnd.it&lc=US&item_name=WP+phpBB+Bridge&item_number=42&no_note=1&currency_code=EUR&bn=eXtndit_Donation&rm=2&no_shipping=1&return=http%3A%2F%2Fwww.e-xtnd.it%2Fdonate-and-download%2F&shopping_url=http%3A%2F%2Fwww.e-xtnd.it
Tags: WordPress, phpBB, bridge, integration, connection, WP, WP phpBB, users, user integration
Requires at least: 3.0.4
Tested up to: 3.3.1
Stable tag: 2.0.7

Shares user authentication with phpBB3, by forcing phbBB to handle all the authentication.

== Description ==

Because we had some difficulties in the past versions, we have remove some of the features of the plugin. The version 2.0.7 DOES NOT require any donation to remove the backlink. With the new version, you will not be anymore able to autosubmit posts from WordPress into forums as a threads.

The WP phpBB Bridge is the evolution of «WordPress to phpBB3 Bridge». The first version of the plugin was designed to synchronize users of phpBB  to WordPress, so when a user log-in to phpBB would be simultaneously connected to WordPress also.

The new version by e-xtnd.it added new features and improvements to exploit many of the features of WordPress previously were not available. Also has been added one new Widget to give more options for your blog.

After activating the Plugin, the user is redirected to connect and disconnect at phpBB. Also keeps the record within the phpBB3 and not WordPress. If a user does not exist in the tables in WordPress, then a new user is created . It should also be noted that after the activation several of the functions of phpBB will be available in WordPress in widget area.

Feature List:

* Shares user authentication with phpBB3, by forcing phbBB to handle all the authentication.
* Adds new users to WordPress user table from the phpBB3 user table.
* Synchronizes WordPress user table from phpBB3 user table.
* Allows WordPress themes to display current/last visit date/time as displayed in phpBB3
* Widget for user login/user information from phpBB3 to be displayed.
* Meta links widget that has registration, logging in, WordPress admin, Forum moderation, and Forum administration links. phpBB3 links visible based on user authentication.
* Forum links widget that has links to various forum functions and pages based on the phpBB3 user authentication level.
* Multilanguage support
* Displays latest’s post’s of specific forum id’s
* You can create instances of the same widget.

New Features

* New easy way to deactivate the plugin in case you are locked out (There is no need to access again your database to deactivate the WP phpBB Bridge).
* A new user-friendly dashboard with a quick view information about plugin status
* You can specify the phpBB encoding for the WP phpBB Bridge Topics widget
* You can be informed of your server settings, software, memory usage and many other related information
* You can download from WP phpBB Bridge dashboard the english language *.po file to translate it into your language.The translations of the previews version 1.x are not compatible with the 2.x version of WP phpBB Bridge.
* You can remove the backlink by unchecking only one checkbox

for more info visit: http://www.e-xtnd.it/wp-phpbb-bridge/

== Installation ==

This section describes how to install the plugin and get it working.

1. Download wp_phpbb_bridge
2. Extract file in: wp-content/plugins
3. Log in to wordpress backend (admin panel)
4. Go to plugins
5. Go in menu:  "WP phpBB Bridge"
6. Fill up the form and update options
7. You can find 4 new widgets in : appearance/widgets !!!!widget (phpBB3 Users MUST be enabled BEFORE the bridge is «turned on»)!!!!
8. Activate plugin  «WordPress to phpBB 3.0.x Bridge»
9. You are good to go…

== Screenshots ==

1. New menu position
3. Organized dashboard for one look fast informations
4. Recent donators widget in WP phpBB Bridge dashboard
5. One look settings informations
6. One look server settings information
7. Better organized settings page
11. List of donators with serial number
12. Widgets coming with WP phpBB Bridge
13. WP phpBB Bridge Users Widget
14. WP phpBB Bridge Meta Widget
15. WP phpBB Bridge Posts Widget
16. WP phpBB Bridge Links Widget
17. WP phpBB Bridge Users Widget, front end
18. WP phpBB Bridge Posts Widget, front end
19. WP phpBB Bridge Meta Widget, front end
20. WP phpBB Bridge Links Widget, front end

== Developers ==

In new WP phpBB Bridge there are many hooks you can use with your code in order to modify native data, or run some functions in some points of WP phpBB Bridge execution period.

The following hooks are available.

Actions:

* wpbb_before_init
* wpbb_after_init
* wpbb_plugin_started
* wpbb_includes
* wpbb_phpbb_loaded
* wpbb_user_object_created
* wpbb_authentication_object_created
* wpbb_template_object_created
* wpbb_cache_object_created
* wpbb_db_object_created
* wpbb_phpbb_configurations_loaded
* wpbb_before_user_session_begin
* wpbb_after_user_session_begin
* wpbb_before_acl
* wpbb_after_acl
* wpbb_before_user_setup
* wpbb_after_user_setup
* wpbb_before_get_user_id
* wpbb_return_user_id
* wpbb_before_return_username
* wpbb_after_return_username
* wpbb_styles_loaded
* wpbb_scripts_loaded
* wpbb_ajax_url_created
* wpbb_before_display_warning
* wpbb_after_display_warning
* wpbb_uninstalled
* wpbb_before_flus_rewrite_rules
* wpbb_after_flus_rewrite_rules
* wpbb_before_add_rewrite_rules
* wpbb_after_add_rewrite_rules
* wpbb_before_add_query_variables
* wpbb_after_add_query_variables
* wpbb_before_disable_me
* wpbb_after_disable_me
* wpbb_before_admin_dashboard
* wpbb_after_admin_dashboard
* wpbb_before_admin_settings
* wpbb_after_admin_settings
* wpbb_before_forum_integration
* wpbb_after_forum_integration
* wpbb_before_author_integration
* wpbb_after_author_integration
* wpbb_before_donators
* wpbb_after_donators
* wpbb_before_get_avatar
* wpbb_after_get_avatar

Filters:

* wpbb_forums_categories - 1 argument - Array containing forums
* wpbb_user_obj - 1 argument - phpBB User object
* wpbb_auth_obj - 1 argument - phpBB Auth object
* wpbb_template_obj - 1 argument - phpBB Template object
* wpbb_cache_obj - 1 argument - phpBB Cache object
* wpbb_db_obj - 1 argument - phpBB Database object
* wpbb_phpbb_configs - 1 argument - phpBB Configurations object
* wpbb_new_username - 1 argument - New username for WordPress database
* wpbb_hash_generation - 1 argument - Hash
* wpbb_forums_list - 1 argument - Array with forums displayed in Forum Assign page
* wpbb_blog_categories - 1 argument - Array containing WordPress categories

== Changelog ==

= 2.0.7 =

* Dutch language added - Translator : Maarten de Boer
* Rusian language added - Translator : san8383
* Required donation system replaced with a checkbox you can use without a donation
* The option of submitting posts into forums removed.

= 2.0.6 =

* Italian language added - Translator : Slash
* Spanish language added - Translator : Nader [http://www.dmbnader.es/]
* Polish language added - Translator : Bavar [http://nooblandia.com/]
* Serial number problem solved
* WP phpBB Bridge Users Widget form elements width, can change from plugin settings page

= 2.0.5 =

* Brazilian Portuguese language added - Translator : Chico Gois [http://www.mundophpbb.com.br]

= 2.0.4 =

* cURL move from all plugin files and replaced with other methods cURL free.
* Link to settings page has been improved in order to work properly in WordPress installations into a sub folder.

= 2.0.3 =

* We added extra control for phpBB ACL object in order to stop generating Fatal errors into /inc/wpbb_functions.php
* We totaly removed the curl functionality from /wp_phpbb_bridge.php
* We changed the file existance for config.php and ucp.php
* We fixed I18n errors from wpbb_admin.php
* You can now delete the serial number from database, by empting the serial number field, and press the "Remove backlink" button 

= 2.0.2 =

* We modified the code to remove the problem with CURL

= 2.0.1 =

* We modified the WP phpBB Bridge Users widget, to stop return error in case that is installed in a widget position and the bridge is diactivated
* We fixed the bug produced by DateTime:diff()

= 2.0.0 =

* Plugin is re-writed from scratch in order to fix errors from the past and add new functionality and be more user friendly.
* New easy way to deactivate the plugin in case of a user is locked out 
* You can specify which authors posts in WordPress will be published in phpBB forums
* A new user friendly dashboard
* New way of donation


= 1.0.3 =

* We fixed bug, on avatars update. Now guests of blog can see the avatars from users are registed on phpBB. 

= 1.0.2 =

* phpBB3 Posts Widget now display the latests threads with the correct order.
* Avatars are integrated too. Comments made by registered users now will contain the avatar that is assigned to the user on phpBB.
* Spanish language translation (Thanks to bystander)
* Russian language translation (Thanks to indamix) 

= 1.0.1 =

* New options available on phpBB3 Posts Widget. You can specify the options you want to show. New options are available for show/hide forum name, author name, total posts and total views!