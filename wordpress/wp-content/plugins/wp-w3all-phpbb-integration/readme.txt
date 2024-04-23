=== WP w3all phpBB ===
Contributors: axewww
Donate link: http://www.paypal.me/alessionanni/
Tags: phpbb, integration, template, user, login
Stable tag: 2.8.4
License: GPLv2 or later
Requires at least: 6.0.0
Tested up to: 6.6
Requires PHP: 7.2

WordPress w3all phpBB integration - easy, light, secure, powerful.

== Description ==
WP w3all phpBB provides free user login and registration integration between a phpBB bulletin board and WordPress CMS.

= Wp w3all phpBB integration =
Integration cookie based between WordPress and phpBB installed on same and subdomains

After the setup and initialization of the plugin, WP users will automatically be added into phpBB when they register in WordPress, while without using the phpBB extension installed into phpBB, if users are allowed to register in phpBB, they will be added into WordPress when they will visit the WordPress side as logged in or at their first login in WordPress (or install the phpBB extension to add users at same time into WordPress when they register in phpBB). But you could use the plugin just only to transfer users between phpBB and Wordpress by activating the plugin as not linked (read the help install page), or to show phpBB posts into a WordPress hosted into another domain

= Widgets =
* Login/logout widget (anyway users can login/logout/register on any Wordpress or phpBB login form)
* Last Topic Posts widget(Links, Links and Text, With or Without Avatars), Read/Unread Topics/Posts

= Auto Embed phpBB into WordPress Template =
WP w3all phpBB is capable of running in iframe mode and automatically embedding phpBB into WordPress template. Setup for the iframe responsive embedded procedure is quick and quite easy!

= WP to phpBB and phpBB to WP users =
Transfer WP users into your phpBB forums and vice versa

= phpBB avatars into WordPress options =
Option to use phpBB avatars to replace WP Gravatars

= WordPress MUMS ready =
It is possible to integrate a WP Multisite network, but linking the same phpBB forum into each subsite

= Shortcodes and more options =
* [Shortcode to display phpBB posts on WordPress posts/pages as formatted bbcode or plain text](https://www.axew3.com/w3/2017/07/wordpress-shortcode-phpbb-posts-into-wp-post/)
* [Shortcode to display recent phpBB Topics/Posts on WordPress posts/pages](https://www.axew3.com/w3/2017/09/wordpress-shortcode-last-phpbb-topics-posts-into-wp-post/)
* [Check the list of others available Shortcodes on the Common How To section of the install help page](https://www.axew3.com/w3/wordpress-phpbb-integration-install/)
* Users Transfer options
* Check more options and features in the WP admin Settings -> WP_w3all (config page). More documentation can be found at the [WP w3all phpBB help page](https://www.axew3.com/w3/wordpress-phpbb-integration-install/) and into inline plugin admin page hints

= WordPress phpBB integration without linking users =
* [Display phpBB posts and Last Topics Widgets into WordPress posts/pages, also cross domain, without linking users](https://www.axew3.com/w3/2018/01/wordpress-phpbb-integration-without-linking-users/)

= Help pages =
WP w3all phpBB help page with common questions, setup and usage guides, and answers to frequently asked questions to be up and running in minutes are available here:
[WP w3all phpBB help page](https://www.axew3.com/w3/wordpress-phpbb-integration-install/)

== Installation ==
* [Read this page at axew3.com for the installation guide](https://www.axew3.com/w3/wordpress-phpbb-integration-install/)

= Summary =
* Download the WP w3all plugin onto your WP site and activate it.
* Navigate to the WP w3all settings page underneath the settings tab in your WP admin dashboard.
* Configure phpBB database connection values. This is REQUIRED.
* Configure the url of your phpBB forums. This value is REQUIRED.
* Maybe navigate to the WP w3all tranfer settings page under the settings tab in your WP admin dashboard.
* So follow the instructions to transfer all of your existing WP users over into phpBB.

= Optionally =
* Activate widgets or use shortcodes
* Detailed instructions at [WP phpBB integration help install docs page](https://www.axew3.com/w3/wordpress-phpbb-integration-install/)

== Frequently Asked Questions ==
[Read this page at axew3.com for further helps: it contain detailed easy how to install and faq](https://www.axew3.com/w3/wordpress-phpbb-integration-install/).

== Screenshots ==
1. Wp w3all phpBB integration main config
2. Wp w3all (raw) WP users transfer to phpBB
3. WP w3all auto embed phpBB into your WordPress template

== Changelog ==

= 2.8.4 =
*Release Date - 22 Apr, 2024*
* Fix: option 'Retrieve posts on Last Topics Widget based on phpBB user's group' to return the correct result, and the option has been improved to become: Display topics/posts on Shortcodes and Widgets based on the phpBB user's groups permissions
* Fix: improve the 'private static function last_forums_topics($ntopics = 10)' so to get only required values and make it faster. Fix and remove the code on same function.
* Add: Gutenberg w3all 'phpBB last topics block' widget
* Fix: (secondary) security bug into the function 'public static function w3all_bbcodeconvert($text)'
* Fix: minor fixes
* Logs: https://www.axew3.com/w3/forums/viewtopic.php?t=1870

= 2.8.3 =
*Release Date - 13 Apr, 2024*
* Add: the 'wp_w3all_heartbeat_phpbb_lastopics shortcode' option that allow to get last posts/topics from phpBB and update the content without having to reload the WP page: https://www.axew3.com/w3/2024/04/w3all-heartbeat-phpbb-lastopics/
* Fix: Fix shortcode (and page-forum) to always return index.php as Url when the page is the phpBB home index: https://www.axew3.com/w3/forums/viewtopic.php?p=6264#p6264
* Fix: the famous Fatal error: Uncaught Error: Call to a member function get_results() on string: https://www.axew3.com/w3/forums/viewtopic.php?p=6267#p6267
* More important fixes: https://www.axew3.com/w3/forums/viewtopic.php?t=1865

= 2.8.2 =
*Release Date - 22 Mar, 2024*
* Fix: 'Not enough data to create user' error, when an user should be created on WP login, because existent (and active) in phpBB
* Fix: same issue for all 'create user' instances throwing same error

= 2.8.1 =
*Release Date - 14 Mar, 2024*
* Fix: reported warning errors when plugin db settings have still not setup
* Logs: https://www.axew3.com/w3/forums/viewtopic.php?p=6223#p6223

= 2.8.0 =
*Release Date - 05 Mar, 2024*
* Fix: All about profile fields in phpBB has been coded to be shorter and to fit any phpBB possible configuration
* Fix: All queries about user's updates have been reviewed and fixed.
* Fix: Remove unwanted pieces of code and fix some little discrepancy.
* Logs: https://www.axew3.com/w3/forums/viewtopic.php?t=1854

= 2.7.9 =
*Release Date - 03 Mar, 2024*
* Fix: 'error Notice: logged in username contains illegal characters forbidden on this CMS.
* Fix: page-forum.php for the template iframe integration (not working on safary). Require to rebuild it or manually apply changes
* Fix: WP user addition into phpBB, when the registration is a signup to a membership into front end pages
* Fix: some WP-MS issues and add option that allow (multisite installations) to add phpBB users into WordPress, using all allowed default WP characters and not only alphanumeric
* Fix: more fixes all around
* All logs (and report bugs): https://www.axew3.com/w3/forums/viewtopic.php?t=1825

= 1.0.1 =
*Release Date - 2 Febrary, 2016*
* Fix problem about default install administrators (Uid 1 on WP and Uid 2 in phpBB) with different usernames.
* Added to the widget w3all Login the option to choose different text to display on login/out.

= 1.0.0 =
*Release Date - 1 Febrary, 2016*
