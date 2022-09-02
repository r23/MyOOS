=== WP w3all phpBB ===
Contributors: axewww
Donate link: http://www.paypal.me/alessionanni/
Tags: wordpress, phpbb, integration, template, users
Stable tag: 2.6.5
License: GPLv2 or later
Requires at least: 5.0.0
Tested up to: 6.0.3
Requires PHP: 7.0

WordPress w3all phpBB integration - easy, light, secure, powerful

== Description ==
WP w3all phpBB provides free user login and registration integration between a phpBB bulletin board and WordPress CMS.
Easy, fast, light and secure.

= Wp w3all phpBB integration =
Integration cookie based between WordPress and phpBB installed on same and subdomains.

= Widgets =
* Login/logout widget (users can login/logout/register on either Wordpress or phpBB).
* Last Topic Posts widget(Links, Links and Text, With or Without Avatars), Read/Unread Topics/Posts.

= Auto Embed phpBB into WordPress Template =
WP w3all phpBB is capable of running in iframe mode and automatically embedding phpBB into WordPress template. Setup for the iframe responsive embedded procedure is quick and quite easy!

= WP to phpBB and phpBB to WP users transfer =
Transfer over pre-existing WP users into your phpBB forums (and vice versa) when integration first begins. After the setup and initialization of the plugin, WP users will automatically be added into phpBB upon their first login, and vice versa. But you could use the plugin just only to transfer users between phpBB and Wordpress by activating the plugin as not linked (read the help install page), or to show phpBB posts into a WordPress hosted into another domain.

= phpBB avatars into WordPress options =
Option to use phpBB avatars to replace WP Gravatars.

= WordPress MUMS ready =
It is possible to integrate a WP Multisite network, but linking the same phpBB forum into each subsite.

= Shortcodes and more options =
* [Shortcode to display phpBB posts on WordPress posts/pages as formatted bbcode or plain text](https://www.axew3.com/w3/2017/07/wordpress-shortcode-phpbb-posts-into-wp-post/)
* [Shortcode to display recent phpBB Topics/Posts on WordPress posts/pages](https://www.axew3.com/w3/2017/09/wordpress-shortcode-last-phpbb-topics-posts-into-wp-post/)
* [Check the list of others available Shortcodes on the Common How To section of the install help page](https://www.axew3.com/w3/wordpress-phpbb-integration-install/)
* Transfer phpBB users into WordPress and vice versa
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
* Navigate to the WP w3all tranfer settings page under the settings tab in your WP admin dashboard.
* Follow the instructions to transfer all of your existing WP users over into phpBB.

= Optionally =
* Activate, configure and save the login and last forum topic widgets
* Detailed instructions at [WP phpBB integration help install docs page](https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/)

== Frequently Asked Questions ==
[Read this page at axew3.com for further helps: it contain detailed easy how to install and faq](http://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/).

== Screenshots ==
1. Wp w3all phpBB integration main config
2. Wp w3all (raw) WP users transfer to phpBB
3. WP w3all auto embed phpBB into your WordPress template

== Changelog ==

= 2.6.5 =
*Release Date - 1 Sep, 2022*
* Fix: keep the user logged in, resetting the user's phpBB session, if the user result to be logged in WordPress and the phpBB expired. This will fix several aspects and simplify phpBB configuration.
* Release Logs: https://www.axew3.com/w3/forums/viewtopic.php?t=1758
* Minor fixes

= 2.6.4 =
*Release Date - 2 Jun, 2022*
* Fix: the query that retrieve online users into phpBB Stats widget
* Fix: the fact, that if the resulting array of online users is empty, (no guests and no registered) the phpBB Stats widget do not display at all
* Optimize/remove some line of redundant and repetitive code in some function
* Remove the custom iframe shortcode option, may used by few and confusing things, since the introduction of 'The (awesome) iframe template integration using shortcode'
* Release Logs: https://www.axew3.com/w3/forums/viewtopic.php?t=1743

= 2.6.3 =
*Release Date - 18 May, 2022*

* Fix: optimize the query that retrieve config values, retrieve now only 21 values and not 63
* Fix: PHP Warning: Undefined array key "switch_wpu_uemail" in /wp-content/plugins/wp-w3all-phpbb-integration/admin/wp_w3all_phpbb_common_tasks.php on line 49
* Fix: Definitively remove the username/user_login param from the WP login hook function
* Fix: function w3all_get_phpbb_onlineStats() that get the correct number of users online
* Fix: PHP Warning:  Undefined variable $w3all_url_to_cms_sw in /wp/wp-content/plugins/wp-w3all-phpbb-integration/views/wp_w3all_phpbb_iframe_short.php on line 64
* Fix: Clean up several hints into the main admin plugin page, and set as Enabled by default the option: Enable/Disable check of the email in phpBB before it is updated in WordPress (front-end plugins pages)
* Change: constant W3PHPBBCONFIG has been deprecated and replaced by the var $phpbb_config (but it is still defined)
* Minor fixes
* Release Logs: https://www.axew3.com/w3/forums/viewtopic.php?t=1729

= 2.6.2 =
*Release Date - 4 Apr, 2022*

* Fix: phpBB STATS and online users widget query to get the correct online guests users number
* Fix: addons/page-forum and /views/wp_w3all_phpbb_iframe_short.php fixed to NOT overwrite the global var $w3all_url_to_cms, leading to wrong widgets/shortcodes avatars links, if avatars option enabled
* Minor fixes
* Release Logs: https://www.axew3.com/w3/forums/viewtopic.php?t=1724

= 2.6.1 =
*Release Date - 26 Mar, 2022*

* Fix: some secondary issues with last added Stats Widget
* Fix: behavior when integration used together with the phpBB WP extension, to delete users
* Release Logs: https://www.axew3.com/w3/forums/viewtopic.php?t=1720

= 2.6.0 =
*Release Date - 17 Mar, 2022*

* Fix: multisite user deletion and users deletion all over
* Add: new shortcode/widget (online users and forums stats). Related output file is /views/phpbb_uonline_stats.php
* Fix: correct user switch, when session mismatching due to different logins on different tabs using different users
* Fix: avatars to correctly get avatar by email and not username
* Add: iframe param "scroll_default" for the awesome shortcode, so that it is possible to disable the scroll behavior (may when the phpBB iframed into a post that will display as url default a specific topic)
* Update iframe files and page-forum. If you wish to update follow update steps (only) here: https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/
* Change: the shortcode param security_token, available for all shortcodes where applied
* Minor code fixes
* Minor hints fixes
* All logs 2.6.0: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1715

= 2.5.9 =
*Release Date - 5 Mar, 2022*

* Fix: fix option "Disable existence check of the email in phpBB before it is updated in WordPress"
* Fix: fix wp_w3all.php and admin/config.php to correctly manage the last added phpbb_iframe_short_pages shortcode option
* Fix: [w3allphpbbiframe] shortcode under several aspects and add 2 new params: check it here https://www.axew3.com/w3/2022/02/the-awesome-iframe-template-integration-using-shortcode/
* Fix: the js overall_footer.html code and page-forum code have been updated to fix some secondary but important bug https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/
* Fix: minor code fixes and hints. See some logs here: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1708

= 2.5.8 =
*Release Date - 22 Feb, 2022*

* Fix: definitively fix the important option "Disable existence check of the email in phpBB before it is updated in WordPress" that was setting at contrary the resulting effect enabled/disabled
* Fix: fix wp_w3all.php and admin/config.php to correctly manage the last added phpbb_iframe_short_pages shortcode option

= 2.5.7 =
*Release Date - 18 Feb, 2022*

* Add: param 'phpbb_default_url' for the "awesome phpBB iframe shortcode" and explain what it is possible to do with it, and more with the others params, see: https://www.axew3.com/w3/2022/02/the-awesome-iframe-template-integration-using-shortcode/
* Fix: hint explain for option "Disable existence check of the email in phpBB before it is updated in WordPress" that was leading to a misundertanding as it was written, because the explain was substantially wrong (sorry). It is important hint. Read it, (in the hope) it is clear now!
* Move to: (if related option active) max 25 users per time the max allowed users that will be possible to delete at mean time in phpBB, while users deleted in WP
* Fix: more hints into admin plugin page
* Fix: the overall_footer.html js code for iframe has been patched to avoid that bots load the code. Are just two lines, one on top and one on the bottom, that wraps the overall_footer code (like it was already for the overall_header): https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/
* Fix: minor security fixes
* Minor fixes
* See: release logs https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1704

= 2.5.6 =
*Release Date - 15 Feb, 2022*

* Add: the awesome iframe phpBB template integration that will fit any template. You'll find it into plugin admin page
* See: see the new awesome iframe shortcode home page here: https://www.axew3.com/w3/2022/02/the-awesome-iframe-template-integration-using-shortcode/
* See: easy example online: https://www.axew3.com/w3/board/
* Fix: a possible (even very hard) secondary security bug into page forum and /common/phpbb_endpoints_ext_functions.php
* Remove: finally clean up 'Links for embedded phpBB iframe into WordPress' option, not required anymore by long time, since V5 js code. Remove all related code, into all functions, also about a jquery function inclusion
* Fix: several language hints (lang file updated) and minor clean up all over
* Enjoy

= 2.5.5 =
*Release Date - 9 Feb, 2022*

* Fix: correctly remove all db options (famous 5/6 rows) when plugin uninstalled
* Fix: clean up function wp_w3all_phpbb_delete_user_signup() from not necessary code
* Add: no_avatars="1" param into shortcodes 'w3allastopics' and 'w3allastopicforumsids'. If the paramater is set and is set to 1, avatars will not display into the used shortcode, even if into the plugin admin, avatars settings have been set as active. Note that to update to this if using custom shortcodes files you have to substitute these files or this shortcode option will not affect
* Fix: 'w3allforumpost' shortcode to correctly display or not the attachment panel into rendered posts
* Add: option delete users in phpBB when deleted in WordPress. It require the 'phpBB WordPress integration common tasks extension' installed in phpBB to work as expected. It do not require to activate any option into the extension
* Note about delete users option: by default users deleted in WP are deactivated in phpBB, and it will continue to be the default behavior if option not active (and even if active: so that if the cURL will fail, and users not deleted in phpBB, users will be by the way deactivated in phpBB)
* See logs: https://www.axew3.com/w3/forums/viewtopic.php?p=5511#p5511
* phpBB extension: download and follow instructions if you wish to update to 1.0.2 (that only add the above mentioned feature about users deletion) here: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1670
* Note: the phpBB extension will not anymore provided within the plugin. To make it more comfortable to be updated all will be ever into one single place: https://www.axew3.com/w3/forums/viewtopic.php?p=5509#p5509
* Note: the phpBB extension main page link has been added into plugin admin page
* Fix: more hints simplified (to be more clear, like about bruteforce behavior) into phpBB admin
* New: help install simplified, shortened and improved has been linked into plugin admin and the old marked as obsolete: https://www.axew3.com/w3/wordpress-phpbb-integration-install/
* More minor fixes and clean up

= 2.5.4 =
*Release Date - 29 Jan, 2022*

* Fix: error when user deleted on WordPress Multisite

= 2.5.3 =
*Release Date - 27 Jan, 2022*

* Fix: 'Error: the provided email is associated with another account into our forum' coming out when user register in phpBB and then should be added into WordPress due to a valid phpBB session or explicit login into WP
* Fix:  Fix: all others cases, where an user registered in phpBB and then should be added in WP, included user addition in WP using the phpBB integration extension option (that if used do not need to be updated, the bug was affecting the plugin code and not the phpBB extension)
* Add: option 'Disable the check of the email existance in phpBB before it is updated in WordPress (front end plugins profile pages)'. The option mean is explained within same option hint into plugin admin page
* Minor lang hint fixes

= 2.5.2 =
*Release Date - 24 Jan, 2022*

* Fix: language file wp-w3all-phpbb-integration.pot into /wp-content/plugins/wp-w3all-phpbb-integration/languages that can be used to correctly create .po lang files. en_EN, us_US, fr_FR, es_ES and it_IT have been added into languages folder by default, ready in place, even if not still translated (have been translated just front-end strings into it_IT)
* Fix: the shortcode phpBB posts into WP post has been updated to correctly parse any attachment, inline and not inline, exactly like phpBB do
* phpBB Post Shortcode is here: https://www.axew3.com/w3/2017/07/wordpress-shortcode-phpbb-posts-into-wp-post/
* Fix admin hints all over
* Minor fixes

= 2.5.1 =
*Release Date - 21 Jan, 2022*

* Fix: function verify_phpbb_credentials() to correctly setup main connection config vars, so to correctly execute all subsequent main tasks into widgets etc
* Fix: check valid emails formats for frontend plugins, before being updated into WordPress

= 2.5.0 =
*Release Date - 20 Jan, 2022*

* Fix: unique database connection instance (faster)
* Fix: front-end plugins email check before update: if the email match another existent into phpBB the update will be rejected with a message. It has been tested working fine into Memberpress and should be ok into any other
* Note: to test that it is working on any plugin and that an existent email is found and the email update rejected, may as obvious, it sould be tested against an email that do not exist in WP, but that exist in phpBB and belong to another user
* Fix: user insertion query into phpBB, using only required values
* Add: common tasks screen, where at moment it is possible to change email for an user only in phpBB or only in WordPress by username, so to make it easy to fix any user's email problem between phpBB and WP
* Fix: all transfers, check and common tasks options that are now ever available: into related option on plugin admin page, or under WP Tools menu
* Some hints have been simplified and updated to be more clear
* Fix: Last posts widgets and shortcodes have been updated to display the same data/hour format of the WordPress setting. Changing WP settings about time/hour format will change the output to the same into widgets/shortcodes
* Add: parsing of the [attachment] bbcode into phpBB post shortcode: see example here https://www.axew3.com/w3/2017/07/wordpress-shortcode-phpbb-posts-into-wp-post/
* Minor code fixes

= 2.4.9 =
*Release Date - 10 Jan, 2022*

* Fix: remove 'all db fields' when phpBB user's insertion, reducing to the minimum insertion query (only phpBB 3.3>)
* Fix: hooks execution flow all around, removing and executing only when needed
* Update: page-forum.php to fix the easy preloader, adding the text above as default, that will display the domain name automatically. To update if you want, rebuild page-forum on plugin admin or substitute the one into your active template folder (page-forum(orWhatEverYouNamedIt).php, with the new page-forum.php you find into the plugin '/addons' folder, renaming it as needed
* Fix: definitively page-forum to resolve the loop problem when UID 2 login in phpBB and into page-forum get loop (because users uid1 in wp and uid2 in phpBB are not linked)
* Fix: some admin configuration hint and order fields
* Minor fixes
* Read: short 2.4.9 logs, preloader and more hints here: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1677

= 2.4.8 =
*Release Date - 7 Jan, 2022*

* Fix: profile fields correctly managed when admin or user update profile fields: 'error email existent into linked cms' was coming out, even when email not existent or email not updated
* Fix: optimize last added wp_check_password() that follow to correctly check password in any passed hash, even if plugin not correctly configured
* Fix: error on transfer processes tasks due to wrong serialize() still in place
* Minor fixes


= 2.4.7 =
*Release Date - 5 Jan, 2022*

* Fix: user not added into phpBB, when an user added by admin in WordPress
* Fix  all others actions that do not fires when editing users in wp-admin side


= 2.4.6 =
*Release Date - 4 Jan, 2022*

* Note: Php7 or better required since 2.4.6
* Replace: phpBB config.php file inclusion. phpBB database connection values and integration mode are now all inside the new option into integration plugin admin page.
* Note: active installations are automatically switched to the new configuration when: visit the WP_w3all plugin admin page after the plugin update. Check that all is in good order (GREEN BUTTON display).
* Note: open plugin admin page to automatically activate the new config (that now work without including a phpBB config.php file). If the GREEN BUTTON display it is all ok: if you were using the custom config.php file inclusion, may remove/delete the file from the filesystem because it will not be used anymore
* Fix function w3all_add_phpbb_user(). Was working fine, but it has been optimized to avoid waste of resources (when/if called) and minor cleanup
* Optimize verify_credentials() main function
* Fix: if integration active but db connection fail, and users have to login in WordPress, but the presented hash is a phpBB hash, the plugin will follow on recognize any presented hash and correctly login users without doing a password reset
* Remove js onmouseover to switch links to the iframe mode. There is no need to take this and more code into widget and shourtcode files (due to js overall_header.html code): all files about have been shortened and cleaned up: old custom files will follow to work as before (because functions about have not been removed)
* Logs 2.4.6 here: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1672

= 2.4.5 =
*Release Date - 26 Dec, 2021*

* Fix: w3all_user_profile_update_errors() function
* Fix: multisite user's email update: was impossible to update user's email in certain conditions for admins
* Fix: secondary security bug in certain conditions
* Add: phpBB WordPress Integration Common Task (phpBB extension) which you'll find from now on into /wp-content/plugins/wp-w3all-phpbb-integration/addons/phpBB_EXT folder
* Install how to for the 'unchained integration' is here: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1670
* Remove phpBB mChat and WP Buddypress integration options
* Remove option 'Retrieve phpBB avatars by using native phpBB file' into plugin Admin Config page (effectively not used anymore)
* Iframe integration code (page-forum.php has been fixed and cleaned under some important aspect) has been updated
* To completely update all to last version, you should follow the Update Procedure as explained: https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/
* Fix: several minor but important fixes and code cleanup all around (hints and options: some have been updated or removed, but the complete cleanup process will happen on next versions, step by step)

= 2.4.4 =
*Release Date - 2 Mar, 2021*

* Add: option "Add users in phpBB only after first successful login in WordPress" that return to be so useful under many aspects (especially to fix some frontend plugin registration problem. Read hints into the same plugin option)
* Add: option "Force correct WordPress password reset (front end plugins)" that resolve problems into front end plugins (read hints into the same plugin option)
* Fix: WP UID 1 wrong password on reset
* Fix: user addition in phpBB, if added as newbie, then promoted to Registered when reach specified number of posts: the user is now added/created in phpBB in the correct way, so to be removed from Newly Registered when the case, and promoted to Registered
* Fix: page-forum.php: fix uid1 WP and phpBB uid2 (install admins) loop on page-forum, given by the fact that uid1 in WP and uid2 in phpBB are not linked anymore. To fix this, it is necessary to rebuild page forum, and update overall_footer.html js code.
* Fix: history.pushState and history.replaceState on page-forum so to return to correct page when navigating back by clicking into Browser Back button. Fix the empty w3= (custom fancy= url) sometime happening on address bar. page-forum(orWhateverYouNamedIt).php require to be rebuilt into plugin admin or manually substituted, for changes to take effect, and for the Wp uid1 and phpBB uid2 loop into page-forum, the overall_footer.html code require to be updated: https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/ . If you want to update to fix the uid 1 loop on page-forum just looking what into overall_footer.html code changed, follow this link: https://www.axew3.com/w3/forums/viewtopic.php?p=5175#p5175
* Fix: function create_phpBB_user() to not follow on create the user if email or username already exists into phpBB, so to avoid an error in certain cases
* Fix: WP and MUMS under several aspects and errors messages
* Revert: MUMS to allow users coming from phpBB that need to be inserted into WordPress, to be accepted only if usernames are in the pattern range 0-9A-Za-z
* Fix: minor (but important) fixes

= 2.4.3 =
*Release Date - 9 Jan, 2021*

* Fix: username characters, allowing range -0-9A-Za-z _.@ for usernames on WordPress Network Multisite mode
* Fix: transfer process utility, WordPress users to phpBB (may still will not work properly for non latin chars)
* Fix: transfer process phpBB to Wp, allowing characters range -0-9A-Za-z _.@ for network multisites
* Fix: 'List WordPress users that not exists in phpBB: delete WordPress users that not exists in phpBB' on WP_w3all phpBB WP users check utility
* Minor: fixes

= 2.4.2 =
*Release Date - 5 Jan, 2021*

* Fix: all Bruteforce countermeasure flow, to avoid loops in certain cases and to correctly manage bruteforce array cleanup
* Fix: mChat flow and code, removing unwanted (and not necessary) phpBB user's capabilities query

= 2.4.1 =
*Release Date - 5 Jan, 2021*

* Fix: Bruteforce countermeasure, to avoid logout of the legit logged user in certain cases

= 2.4.0 =
*Release Date - 1 Jan, 2021*

* Switch to integration 'by email'
* To update from 2.3.9, it is only required to choose where users can update email, and may (may not) where to let users register and/or login.
* READ this to UPDATE: https://www.axew3.com/w3/forums/viewtopic.php?p=4975#p4975
* The new install steps page is here: https://www.axew3.com/w3/wp-w3all-wordpress-to-phpbb-install-and-how-to/
* The install help page contain steps and explainations on how to use the integration for different scenarios, also with mismatching usernames/emails pairs
* With mismatching usernames/email pairs, it is allowed to let users update their email, password and to register, only in WordPress or phpBB
* Read to know ways to integrate, into above linked pages/posts

= 2.3.9 =
*Release Date - 08 Aug, 2020*

* Fix: function w3_phpbb_ban() to not return phpBB 'excluded bans' as banned
* Fix: function w3all_add_phpbb_user() where username var missed (user's addition in WP with redirect from/after registration in phpBB)
* Minor fixes
* Note: the integration plugin since 2.4.0 > will be rewritten and will change some important thing, but maintaining all features: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1562

= 2.3.8 =
*Release Date - 20 Jul, 2020*

* Fix: avatars widgets, and avatar for the shortcode by forums ID [w3allastopicforumsids] where avatars do not display may because the forums id excluded on plugin admin option for widgets, but need posts/avatars retrieved after via [w3allastopicforumsids] shortcode, for the exluded forum on widgets
* Note: if you are using custom files to display shortcodes, to make it work the new fix above, you need to update your custom 'phpbb_last_topics_forums_ids_shortcode.php' with the last one you'll find into '/wp-content/plugins/wp-w3all-phpbb-integration/views/phpbb_last_topics_forums_ids_shortcode.php'
* Fix: 'Notice: Constant W3PHPBBLASTOPICS already defined in ..... class.wp.w3all-phpbb.php on line 2811' bug, reported here: https://www.axew3.com/w3/forums/viewtopic.php?p=4747#p4747
* Fix: if coming phpBB user presents a valid phpBB cookie, do not rewrite phpBB session in WordPress
* Fix: w3_phpbb_ban() function, to avoid return true (user banned) where passed empty values
* Remove: option 'Fix Users Signup process' on plugin admin, not useful since last updates
* Fix: MUMS last password hashed with cost 10 bug, switched to 12, so recognized in phpBB, x MUMS WordPress installations
* Minor fixes

= 2.3.7 =
*Release Date - 15 Jul, 2020*

* Fix: passwords flow, for new coming logged in users in phpBB, that are inserted and logged on fly in WordPress, without receiving 'Pass do not match' and a logout at first visit in place of an auto login
* Fix: password do not match in phpBB, if changed in WP profile (because phpBB require a min cost of 12 on hashing password, and last plugin update missed this aspect)
* Fix: bug on login widget/shortcode
* Fix: usernames correctly added in WordPress when coming from phpBB (lowercase bug)
* Fix: non latin chars like Cyrillic: users are now fully and correctly handled/inserted in WordPress (UTF-8)
* Fix: transfer process for users from phpBB to WordPress
* Fix: return correct notice to users, if they are deactivated in phpBB, and avoid to logout without informing the user of what happen to his account
* Fix: MUMS correct user addition into the first visited blog and fix more mums bugs
* Change: a phpBB banned user, will not be deactivated in WordPress, but will never be able to login: a notice will display to inform what's going on with his account. So when ban end, the user will be able to login again with same WP roles (that has been the more easy and 'less code solution')

= 2.3.6 =
*Release Date - 05 Jul, 2020*

* Fix: passwords reset/update flow and passwords containing special characters
* Fix: password's reset processes for frontend plugins pages
* Fix: remove old w3_Bcript() lib, switching to native Php password_hash()
* Fix: display user's avatar, when an admin edit user's profile (almost into a default WP profile view and/or all plugin's using $_GET['user_id']): https://www.axew3.com/w3/forums/viewtopic.php?p=4701#p4701
* Fix: set wp user to be the one that logged out/in into phpBB when profile page load. Example: an user logout and re-login with another username in phpBB, then reload his wp profile page that was already opened into another browse's tab, where was logged in as the logged out into phpBB
* Fix: minor security bug
* Minor fixes
* Add: param/attribute 'wordsnum' to the 'phpBB posts into WP post' shortcode: https://www.axew3.com/w3/2017/07/wordpress-shortcode-phpbb-posts-into-wp-post/
* Updated procedure explain: 'Securing WordPress and WP_w3all phpBB WordPress integration: HOW TO and WHY': https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=80

= 2.3.5 =
*Release Date - 16 Apr, 2020*

* Fix: definitevely fix duplicated WP user insertion, when registration done in iframed phpBB, then login first time, into phpBB iframed
* Fix: correct user's phpBB session cleanup, when user deleted into WordPress
* Fix: a secondary bug on empty value passed on query, when user deleted in wordpress (then it's deactivated in phpBB), and the user perform a Logout (very rare)
* Fix: several secondary Php warnings coming out (only) into certain conditions

= 2.3.4 =
*Release Date - 14 Apr, 2020*

* Fix: default page-forum.php, to correctly setup js array() of allowed domain, for subdomains installations (there is no need to update your working page-forum)
* Fix: WordPress to phpBB users transfer, to correctly transfer old existent WP users with the correct registration date in phpBB.
* Add: option 'Transfer WordpRess users into phpBB with WP user's registration date' OR 'Transfer WordpRess users into phpBB with actual (time now) registration time'
* Fix: re-add fix for duplicated user insertion in wordpress, when registration, and then login first time, done into phpBB via page-forum iframe mode, since it seem to follow the bug in some configurations

= 2.3.3 =
*Release Date - 15 Mar, 2020*
* Fix: error on activating signups, when option 'Fix Users Signup process' active (in certain conditions with front end plugins) thrown in /wp-content/plugins/wp-w3all-phpbb-integration/wp_w3all.php on line 941
* Fix/add: thrown error 'user exist into phpBB' output, without stop execution (partially added at moment only in certain cases)
* Fix: correct activate the user after signup, if option 'Deactivate phpBB user account until WP confirmation' active, in certain cases
* Fix: iframe code for Lightbox phpBB mod, see here how to easy fix, https://www.axew3.com/w3/forums/viewtopic.php?p=4522#p4522

= 2.3.2 =
*Release Date - 12 Mar, 2020*
* Fix: wp_w3all_phpbb_delete_user_signup() function to correctly remove signups, where on mums subsites, the table signup exist or not (it depend on how configured the network) and do not throw error
* Fix: signups to correctly search and retrieve db data by the right table, when on certain subsites mums configurations
* Fix: 'Fix Users Signup process' bug, password (maybe) do not match for the new WP registered user, using front end WP plugins
* Fix: minor security fix
* iframe V5: fix for correct Lightbox code execution, see the easy fix (that add just a line of code) here: https://www.axew3.com/w3/forums/viewtopic.php?p=4522#p4522

= 2.3.1 =
*Release Date - 09 Mar, 2020*
* Fix: option 'Fix Users Signup process' for mums. 'function w3_wp_pre_insert_user_meta()' return empty var, leading to error 'Invalid argument supplied for foreach() in /wp-includes/user.php on line 1839' on activating user (front-end plugins) if plugin's option active
* Fix: 'function w3all_iframe_href_switch()' to correctly switch href on widgets/shortcodes in certain conditions

= 2.3.0 =
*Release Date - 09 Mar, 2020*
* Fix: security bug into 'w3_phpbb_ban()' function (affecting banned accounts)
* Add: complete and correct handle banned phpBB users. A banned user in phpBB, will result immediately as 'User with No Role' in WordPress (do not wait until next user login in wp). Read more about here: https://www.axew3.com/w3/forums/viewtopic.php?p=4501#p4501
* Fix: display message to the user if the account results to be Banned or Deactivated into phpBB
* Fix: correct re-activation of a WP 'no role' user (based on rule setup into plugin admin), if user re-activated in phpBB, or correctly re-add user if the user was deleted in WordPress (then deactivated/re-activated in phpBB and login in WP).
* Fix: several important MUMS fixes
* Fix: error on profile update if Signups fix option active, and related correct user insertion in phpBB with right password, for custom external plugin's front end users registrations pages
* Fix: more minor but important fixes
* Fix: optimize '$w3all_bruteblock_phpbbulist' and $w3all_u_signups_data arrays, used when related options active. The code cleanup old records at certain conditions (array contain more than 3000 records). Faster, light.
* Fix: hints into plugin admin
* Notice: 2.3.0 has been quite deeply tested under any aspect to be released as a stable version that resolve many inconsistencies. However since mixed options and related possibilities are hundreds, it is important that you report any bug you may found!

= 2.2.9 =
*Release Date - 02 Mar, 2020*
* Fix: 'create_phpBB_user_wpms()' function, to correctly add users into phpBB without errors, on certain configurations
* Fix: same mentioned function to correctly handle last added 'Fix Signups' option
* Fix: (iframe links switch widgets/shortcode) 'function w3all_iframe_href_switch()' wrongly enqueued into wp_head hook (now it has been correctly switched into wp_footer). It was may causing that even with option 'switch links to point to iframe' active into main plugin admin page, the switch for links on widget and shorcodes was not working (and without overall_header.html code applied, phpBB real url display/open, because links were pointing to real phpBB urls (not switched))

= 2.2.8 =
*Release Date - 29 Feb, 2020*
* Fix:  Illegal string offset for arrays of 'Fix Users Signup process' and 'Activate w3all sessions keys Brute Force countermeasure' options
* Note: if not already done, to apply fixes as on 2.2.7, it is REQUIRED to SET to NO options 'Fix Users Signup process' and 'Activate w3all sessions keys Brute Force countermeasure' into plugin admin, then re-activate if necessary (if using)

= 2.2.7 =
*Release Date - 29 Feb, 2020*
* Fix: 'Fix Users Signup process' and 'Activate w3all sessions keys Brute Force countermeasure' options
* Note !Important: to apply the fix, it is REQUIRED to SET to NO options 'Fix Users Signup process' and 'Activate w3all sessions keys Brute Force countermeasure' into plugin admin, then re-activate if necessary (if using)
* Fix: code of both mentioned above options, has been further more updated to reset the value of arrays of records, if the count is > 2000
* Minor fixes

= 2.2.6 =
*Release Date - 26 Feb, 2020*
* Fix: bug WP user not added in phpBB when register in WordPress

= 2.2.5 =
*Release Date - 26 Feb, 2020*
* Fix: bug for last added 'Fix Users Signup process' option. Avoid to be partially executed, even if option set to NO, when existent phpBB user added after a login in WordPress first time, or WP user created
* Fix: function WP_w3all_phpbb::w3_check_phpbb_profile_wpnu() after reported: error PHP Recoverable fatal error: Object of class WP_Error could not be converted to string in /wp-content/plugins/wp-w3all-phpbb-integration/class.wp.w3all-phpbb.php on line 1734

= 2.2.4 =
*Release Date - 26 Feb, 2020*
* ADD/fix: 'Fix Users Signup process' option, into main plugin admin page. Read inline hints to know what it is used for, and do not activate on already working integrations
* Fix: two reported secondary Php notices: https://wordpress.org/support/topic/2-different-error/
* Fix: last topic widget, to correctly display read/unread icon (when no avatar option active, and the setup of the wodget is to display last topics with text)
* Clean up: secondary issues and inline hints
* Notice: report bugs!

= 2.2.3 =
*Release Date - 22 Feb, 2020*
* Fix: (iframe) page-forum.php security bug
* !important: Update as soon page-forum rebuilding it on plugin admin, or manually substituting it
* Fix: (iframe) page-forum.php: default /wp-content/plugins/wp-w3all-phpbb-integration/addons/page-forum.php has been updated again (and definitively fixed) to correctly process urls, if url coming from a click into WP widgets. To update to the latest page-forum, rebuild it into plugin admin or manually replace with the new one of this release: https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/
* Fix: PHP Notice: Undefined variable: topics_x_ugroup ON class.wp.w3all-phpbb.php on line 2161
* Fix: secondary notice error on line 1185 class.wp.w3all-phpbb.php
* Fix: the 'Transfer phpBB Users into WordPress' option
* ADD: single phpBB user addition into WordPress option into the 'Transfer phpBB Users into WordPress' page
* Fix: more important fixes
* Notice: Update as soon and report bugs!

= 2.2.2 =
*Release Date - 20 Feb, 2020*
* Fix for page-forum.php (iframe): default /wp-content/plugins/wp-w3all-phpbb-integration/addons/page-forum.php has been updated to correctly process urls, if default 'w3' query string, and not 'fancy' url setting used. To update the page forum, rebuild it into plugin admin or manually replace with the new one. If you wish to edit manually, or know more about this, follow here easy instructions: https://www.axew3.com/w3/forums/viewtopic.php?p=4446#p4446
* V5 iframe code: https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/

= 2.2.1 =
*Release Date - 20 Feb, 2020*
* Fix: optimize all 2.2.0 release changes, and speed up code execution (something only executed when/if required, not more than one time)
* Fix: compatibility with Php 7 <

= 2.2.0 =
*Release Date - 20 Feb, 2020*
* Fix: verify_phpbb_credentials() that was missing a correct return in certain conditions, that was sometime leading Php to throw warnings and notices
* Fix: any Php Warning 'Undefined constant W3PHPBBCONFIG' coming out into some configurations
* Fix: WP MUMS. Correctly add user into phpBB, when user added via 'wp-admin/network/user-new.php'
* Fix: minor (but important) code
* Note: iframe V5 overall_header.html javascript code has been updated to remove a (wrong) commented js instruction, responsible to correctly reload any page when phpBB accessed via direct url. Hints into help install page have been corrected to be more clear/precise
* V5 iframe code: https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/
* Fix: hints into plugin admin page

= 2.1.9 =
*Release Date - 04 Feb, 2020*
* Fix: PHP Warning (onlogin in certain conditions) - Use of undefined constant W3PHPBBCONFIG in /wp-content/plugins/wp-w3all-phpbb-integration/class.wp.w3all-phpbb.php on line 2740

= 2.1.8 =
*Release Date - 03 Feb, 2020*
* Fix: (on WP registration process) -> Warning: Creating default object from empty value in /wp-content/plugins/wp-w3all-phpbb-integration/class.wp.w3all-phpbb.php on line 667

= 2.1.7 =
*Release Date - 02 Feb, 2020*
* Fix: bug wp password error update, if PASSWORD_ARGON2I not compiled in php
* Fix: cleanup code for the hash_password and switch code to not hash password in PASSWORD_ARGON2I ( so if support PASSWORD_ARGON2I / and PASSWORD_ARGON2ID not compiled on php there are no problems )
* Minor fixes

= 2.1.6 =
*Release Date - 31 Gen, 2020*
* Fix: Warning: password_hash() expects parameter 2 to be integer error for certain Php versions: https://www.axew3.com/w3/forums/viewtopic.php?p=4372#p4372

= 2.1.5 =
*Release Date - 31 Gen, 2020*
* Fix: duplicate phpBB user insertion in WP definitively and properly, reordering code execution
* Fix: transfer phpBB users into WP via transfer option, with proper choosed role/capability (if updated before 2.1.4 patched)
* More secondary fixes

= 2.1.4 =
*Release Date - 31 Gen, 2020*
* Fix: bugs all related to new users insertions, users activation, redirects, login flows, iframe or not iframe mode
* Fix: correct user's update, when login done into login widget, and several adjustments for all tested and possible scenario (especially when an already phpBB registered user, need to be added when login via widget, but also for all others login flow on same scenario)
* Fix: several Php error/notices thrown in several circumstances
* Fix: check that WP email has been validated/confirmed, before to update also into phpBB, when user update profile into WP side
* Fix: if the phpBB user need to be validated/activated, when added in WordPress, will be added as no role. Will be then be activated correctly, when user will login first time in WP or will be auto logged in WP due to valid phpBB session found
* Fix: transfer phpBB users into WP via transfer option, with proper choosed role/capability
* More fixes all around

= 2.1.3 =
*Release Date - 29 Gen, 2020*
* Fix: Bug. Duplicated user in WordPress with a temporary (but working) trick. The problem seem to come out due to this: https://wordpress.org/support/topic/wp_insert_user-on-init-duplicate-created-user/
* Fix: Possible loop when user result with valid phpBB cookies/session, but deactivated in phpBB

= 2.1.2 =
*Release Date - 28 Gen, 2020*
* Fix: Bug. Duplicated user in WordPress in certain conditions (WordPress fail on recognize that an user already exists: was affected only the iframe mode, when user login done in iframe and user added at same time in wp). Check more about this here: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1474
* Fix: Secondary Bug. Php notice Trying to get property 'user_login' of non-object in /wp-content/plugins/wp-w3all-phpbb-integration/class.wp.w3all-phpbb.php on line 258 in certain conditions
* Fix: Secondary Bug. Php notice Trying to get property 'user_login' of non-object in /wp-content/plugins/wp-w3all-phpbb-integration/wp_w3all-phpbb.php on line 372 (login widget)
* Add: Option. WordPress capability, for phpBB users added into WordPress
* Add: Option. Exclude existent and already deactivated phpBB users for the "List users with 0 posts in phpBB: delete in WordPress and deactivate in phpBB" option, to allow a little more fast and clean check up
* Minor fixes

= 2.1.1 =
*Release Date - 21 Gen, 2020*
* Update: page-forum to be compatible with the new iframe v5 code (granting compatibility with v4 iframe code, which you can follow using): https://www.axew3.com/w3/2020/01/phpbb-wordpress-template-integration-iframe-v5/
* Add: fancy w3 var for query string (better later then never) new option into plugin admin page. 'Fancy URL query string for the WordPress Forum page' that allow to switch the name of the (infamous) var 'w3' in the URL query string, to something that more like. To use this it is required to update page-forum to latest version, and update the overall_header.html iframe js code to v5
* Fix: page-forum for correct targetOrigin value
* Fix: remove jQuery function, replaced by pure Js code, to allow URL seo switches javascript function work properly on widgets and shortcodes, also if the theme in use do not add the jQuery library by default
* Fix: phpBB PM shortcode, where error on html output
* Fix: WP admin plugin configuration page with some minor fixes and corrected hints and options
* Fix: remove unwanted code, partially cleanup issues to speed up execution
* Several fixes all around
* Hint (the same) like time ago: if you found one, please report bugs! You can also improve some aspect? You're really welcome!

= 2.1.0 =
*Release Date - 14 Gen, 2020*
* Fix: passwords hashing and password check flow, to be compatible between new phpBB3 3.3.0 and previous 3.2 versions
* Fix: password containing special chars like " or may not allowed in WP like \ to be recognized and hashed correctly
* Add: phpBB 3.3.0 PASSWORD_ARGON2I and PASSWORD_ARGON2ID support
* Minor fixes

= 2.0.9 =
*Release Date - 14 Gen, 2020*
* Fix: (reported bug) password check in WordPress fail, if password change done by user in phpBB profile
* Minor Fix: page-forum.php to correctly set the targetOrigin value
* Minor Fix: page-forum.php -> to have Template Forum as template option when creating blank page in WordPress, and installed WP theme let choose between different templates to create a page. The page Forums will be available to choose, and let work fit the template with no problems on layout. See: https://www.axew3.com/w3/2019/12/phpbb-wordpress-template-integration-iframe-v4/
* Note that also the iframe overall_footer.html v4 code has been updated to fix two issues (most important: correctly reposition iframe in certain conditions)

= 2.0.8 =
*Release Date - 09 Gen, 2020*
* Fix: registered users in wordpress added in phpBB all with date "01 Jan 1970" reported bug, both 3.2 and 3.3 affected
* Hint: remember to report bugs! Help to maintain the integration fully working!

= 2.0.7 =
*Release Date - 08 Gen, 2020*
* Fix: phpBB 3.2 versions. Php notice error (and subsequent query fail for user_email_hash value) for WP user profile update action
* Please update as soon if still in phpBB 3.2> releases.

= 2.0.6 =
*Release Date - 07 Gen, 2020*
* Fix: Wp_w3all WordPress to phpBB users transfer for the new phpBB 3.3.0
* Note: since 2.0.5 for phpBB 3.3.0. The plugin maintains compatibility with previsous 3.2 > phpBB versions

= 2.0.5 =
*Release Date - 07 Gen, 2020*
* phpBB 3.3.0 - Since phpBB 3.3.0 release change database tables respect previous phpBB versions, has been necessary to update plugin code to be suitable for new phpBB 3.3.0
* Fix: correct a bug for users update in mums installations
* Fix: bug on password hash: bcrypt with min salt of 16 updated to accomplish new passwords rules (and resolve hash pass error in certain circumstances)
* Fix: minor fixes

= 2.0.4 =
*Release Date - 06 Gen, 2020*
* Fix: the not working option "Exclude WP userID 1 and phpBB userID 2 association"
* Fix: redirect to user profile page, when an admin edit an user profile, and error on update due to duplicated email fire: after warn fire, provide link to redirect to the edited user profile screen, and not to own profile
* Minor fixes

= 2.0.3 =
*Release Date - 05 Gen, 2020*
* Fix: nicenames, usernames correct db storage, now suitable for usernames with latin and non latin characters
* Fix: login widget to insert a registered phpBB user still not added in WordPress, not logged into phpBB, that login wp first time, and to avoid redirects errors in some circumstances
* Fix: correct logout for user in WordPress, if logout done in phpBB (so fixing also possible loops problems into iframe mode, when logged user logout into iframed phpBB)
* More fixes all around
* Do NOT fix: wp_w3all users check procedure for usernames with non latin characters. May usernames will results in list like not suitable for wordpress, despite may correctly added into wordpress, or suitable to be added. This not affect the integration, because by the way a valid suitable user will be added on the normal frontend flow. An username that effectively contain unwanted chars will be warned and not added. Will be resolved/fixed on next coming 2.0.4

= 2.0.2 =
*Release Date - 29 Dic, 2019*
* Fix: mums network installations. The How To page has been updated: https://www.axew3.com/w3/2017/04/wp_w3all-for-wordpress-ms-mu-multisite-multiuser-network-installations/
* Fix: nicenames for network and common wp installations
* Fix: '/wp-content/plugins/wp-w3all-phpbb-integration/views/wp_w3all_login_form.php' for any kind of wp installation (mums network)
* Fix: (secondary) security fix for the custom phpBB config.php file '/wp-content/plugins/wp-w3all-phpbb-integration/addons/wp-w3all-config/config.php'. Please follow this step if you want to fix the issue: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1448&p=4241#p4241
* Fix: correct profile fields (email change) updates flow
* Minor fixes

= 2.0.1 =
*Release Date -  22 Dic, 2019*
* Fix: 'WP w3all check' page, that now work fine and add all the necessary to check/control users state between WP and phpBB
* Add: option 'List phpBB users with duplicated usernames or emails' AND 'usernames in phpBB containing characters not suitable for/as WordPress user_login name, into WP w3all check tasks page
* Add: Thickbox WP lib for phpbb_last_topics_withimage_output_shortcode.php, that open images into Thickbox when clicked
* Fix: new login widget to display failed login message if it occur, correct redirect on same page, remove cookie way to fire the fail warning 'views/wp_w3all_login_form.php'
* Fix (minor security): cookies released to be http only
* Change/fix: move admin files from folder 'views' to 'admin' folder: so entire 'views' folder can be copied as is for 'custom files' option
* Add: 'advanced' [w3allcustomiframe] shortcode, that is added option into plugin's admin page
* Fix: 'page-forum.php' (or whatever you named it) adding the js code that allow the origin check for the embedded iframe, ONLY allowed then on the domain it run
* The fix for 'page-forum.php', if you want manually apply and not rebuild or substitute the template file, can be found here: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1442

= 2.0.0 =
*Release Date - 14 Dic, 2019*
* Fix: login/out sessions bug
* Fix/rebuild: WP_w3all Login widget that now execute the login flow in WordPress side, right on same page it is placed (no more redirect into phpBB). Added in the way to be compatible and execute logins events for Firewall Plugins (so brute force attacks are under control)
* About: Fix/rebuild WP_w3all Login widget: files 'views/login_form_include_noiframe_mode_links.php' and 'views/login_form_include_iframe_mode_links.php' both substituted by 'views/wp_w3all_login_form.php'
* Memo: Fix/rebuild WP_w3all Login widget: check that if you're using/activated option 'Use custom files to display Last Topics Widgets ...', you'll need to add the new file 'views/wp_w3all_login_form.php' into the custom '/wp-content/plugins/wp-w3all-config' folder
* Add: compatibility to let external WordPress Firewall plugins to monitorate/log sessions activities
* Add: simple and rude (but effective) option to monitorate/prevent Brute Force into sessions keys. Read hints x 2.0.0 linked into new plugin Option and activate it!
* Fix: Wp_w3all Widgets. Fixed possibility to add plugin's widgets in the right way, also via external plugins like Elementor
* Fix: Last Topics widget multiple instances
* Fix more important bugs all around
* Fix minor security bug
* Merry Christmas time and best wishes for all cool people!

= 1.9.9 =
*Release Date - 5 Dic, 2019*
* Security patch: see https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=1432
* Add: Security patch -> random 20/32 string length to increase security
* Fix: Correctly connect to another database port if it is required by phpBB
* Add: display error message into plugin admin page, if (until) phpBB db connection fail, and set integration as NOT LINKED USERS in the while, to avoid configuration problems
* Add: Shortcode w3allphpbbupm for phpBB users PM notification in WordPress: https://www.axew3.com/w3/2019/12/shortcode-phpbb-pm-into-wordpress/
* Add: unique query into main verify_phpbb_credentials() to retrieve any phpBB user's Group permissions (become active only when mchat option active, since these kind of data are useful at moment only in this contest)
* Update all iframe resizer files to last version, update page-forum.php callback to last related code and iframe v4 code
* iframe code v4 procedure here:
* Minor fixes and code/inline comments cleanup

= 1.9.8 =
*Release Date - 8 Mag, 2019*
* Add - WP_w3all phpBB WP users check: simply tasks to check problems between linked phpBB and WordPress users. To use, just activate the option 'Activate WordPress to phpBB and phpBB to WP users transfer and/or the phpBB WP users check' on plugin admin page and read new option's hints
* Fix correct multisite user's deletion on certain circumstances
* Minor code and config hints adjustments

= 1.9.7 =
*Release Date - 14 Mar, 2019*
* Add - phpBB and/or any Feed shortcode - [w3allfeed w3feed_url="aFeedURL"] - check all features/attributes and how to use here: https://www.axew3.com/w3/2019/03/shorcode-rss-feeds-into-wordpress/
* Improve shortcodes [[w3allastopics] AND [w3allastopicforumsids]. Added shortcodes attributes to style each shortcode output with easy (like on latest added above feed shortcode). Maintain compatibility with old way. Check changes and how to use here: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=550
* The v3 iframe code can be improved to be as it is actually on the online iframe integration example at axew3.com. If you wish to update to the smooth scroll improvement, before iframe v4 code changes/release that has been moved to the vers. 2.0.0 plugin release date, follow hints here: https://www.axew3.com/w3/2019/03/smooth-scroll-for-phpbb-wordpress-template-integration-iframe-v3/
* Minor code adjustments

= 1.9.6 =
*Release Date - 21 Feb, 2019*
* Fix for shortcode [w3allphpbbmchat] as on this post: https://wordpress.org/support/topic/1-9-5-release-phpbb-mchat-integration/#post-11223053
* Add mChat preloader for toggled mChat shortcode (button) and should fix any mChat flow (linked or not linked users mode) - see example here: https://www.axew3.com/www/wordpress-rc/
* Remove not needed utf8_encode function
* Remove last added w3all_phpbb_get_ucap_opts() function (from wp_w3all.php) to retrieve phpBB users's capabilities (moved into class.wp.w3all-phpbb.php) inside verify_phpbb_credentials()
* Minor code adjustments/checks
* Note that the overall_footer.html code has been little updated after 1.9.5 release so if you want improve the code check it here: https://www.axew3.com/w3/2018/12/phpbb-wordpress-template-integration-iframe-v3/

= 1.9.5 =
*Release Date - 19 Feb, 2019*
* Add (raw) mChat integration. Check it here: https://www.axew3.com/w3/2019/01/wordpress-phpbb-mchat-integration/
* Minor fixes and code adjustments/checks
* Note that the overall_footer.html code has been updated several times after 1.9.4 release so if you want improve the code check it here: https://www.axew3.com/w3/2018/12/phpbb-wordpress-template-integration-iframe-v3/

= 1.9.4 =
*Release Date - 3 Gen, 2019*
* Add option - Activate integration without linking users between WordPress and phpBB - directly on plugin admin page
* Fixed avatars from phpBB for any taste: if integration with not linked users mode, or if phpBB user still not added into WordPress, even in users linked mode, Last Topics Widgets/shortcode did not show the phpBB avatar where available.
* Add option - Swap WordPress default Login, Register and Lost Password links to point to phpBB related pages -
* Implements the new page-forum.php and v3 iframe code. All reported iframe wrong behaviors fixed (you can follow using previous versions if you like). Check it here: https://www.axew3.com/w3/2018/12/phpbb-wordpress-template-integration-iframe-v3/
* Allow any name for the WordPress forum page (was only board, forum, forums, community ...). Note that may not all page names will be suitable to be used by the way.
* Minor fixes

= 1.9.3 =
*Release Date - 17 Sep, 2018*
* Fix correct email change confirmation behavior: check this to understand what's the problem and how it has been resolved: https://wordpress.org/support/topic/hook-for-change-pending-email/
* Fix Buddypress integration: fix avatar behavior, while about others profile fields integration, note that Option 'User groups' in WP admin -> Settings -> Buddypress, need to be active for the integration code to work correctly. Check updated help page https://www.axew3.com/w3/2017/09/wordpress-and-buddypress-phpbb-profile-fields-integration/ (added in the common How to List of the Help Install Page)
* If using iframe: updated all iframes files, so you need to replace the iframeResizer.contentWindow.min.js file you placed into phpBB root and recompile the phpBB template. Check in the help page how to: https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* If using iframe: updated/cleaned the phpBB overall_footer.html code (check the 'help iframe responsive procedure' on same previous linked page) for: correct workaround for 'Top distance gap in px when page scroll top' (read inline hint). Check here to understand where the problem was: https://github.com/davidjbradshaw/iframe-resizer/issues/628
* If using iframe: after plugin update and the two steps above, remember to recomplie phpBB template to make effective the update.
* Minor fixes

= 1.9.2 =
*Release Date - 5 Sep, 2018*
* Add option 'do not associate WP userID1 and phpBB userID 2' on plugin admin config page.
* Fix correct addition of newly WP users into specified phpBB group also for MUMS (multisite) installations and the transfer process (rank, group color, datetime, language)
* All fixes and patches listed on this topic, please see: https://wordpress.org/support/topic/1-9-1-patches-logs/
* Minor fixes

= 1.9.1 =
*Release Date - 22 Aug, 2018*
* Fix inefficient latest posts query by @reloadgg, see: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=850
* Fix error on shortcode x single or specified multiple forums and add improvement based on the above by @reloadgg
* All fixes listed on this topic: https://wordpress.org/support/topic/1-9-0-patches-logs/
* Multisite installations do not need anymore to add the 'define' line of code on wp-config.php file (fix suggested by Jakub), but the code is/remain compatible for the old way: documentation about has been updated: https://www.axew3.com/w3/2017/04/wp_w3all-for-wordpress-ms-mu-multisite-multiuser-network-installations/
* Minor fixes

= 1.9.0 =
*Release Date - 29 Jul, 2018*
* Add option: 'Add newly WordPress registered users into a specified phpBB group' into WP_w3all admin config page
* Fix Woocommerce (and all other plugins) miss parameter Php notice onlogin in wordpress, where woocommerce installed: the same php notice was coming out also into plugin WP Better Security, and many others
* Add Shortcode: display phpBB last topics grid in Wordpress with first topic's attached image (linked on the Help Install Page, where 'Common How To' list): the new linked shortcode is here: https://www.axew3.com/w3/forums/viewtopic.php?f=13&t=783
* Contain all patches listed on this topic: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=784
* More important and minor fixes.

= 1.8.9 =
*Release Date - 7 May, 2018*
* Contain all fixes listed on this topic: https://wordpress.org/support/topic/1-8-8-patches-log/
* Substantially, 1.8.9 apply (change) fixes only into two files: wp_w3all.php and class.wp.w3all-phpbb.php

= 1.8.8 =
*Release Date - 17 Apr, 2018*
* Contain fixes for all 1.8.7 reported bugs (and more important).
* Add shortcode for specified forums IDS (how to about has been added on Install Help Page, on the how to section). See how to use it here: https://www.axew3.com/w3/2018/04/latest-phpbb-topics-by-forum-ids-shortcode/
* Minor fixes.

= 1.8.7 =
*Release Date - 3 Apr, 2018*
* Fix a secondary problem about security and users. The problem: a WP actual bug allow to a logged in WP user (while it is logged in), to register a new WP user pointing to the 'wp-login.php?mode=register' url, that isn't a correct behavior (and that not need to happen with this integration plugin). The new created user in WP, if a new user is added/registered in this way in WP, it is not added at same time into phpBB also.
* Extend phpBB email ban options on WP registration: if a domain or subdomain email address result banned in phpBB, the user will not be able to register into WordPress using these email address.
* Contain all latest and tested 1.8.4, 1.8.5 and 1.8.6 fixes.
* Minor fixes

= 1.8.6 =
*Release Date - 23 Mar, 2018*
* Fix login page with 're-auth' presented, even if user logged in on certain situations.
* Minor fixes.

= 1.8.5 =
*Release Date - 20 Mar, 2018*
* Fix correct logout of user in WordPress, when logout done in phpBB side.
* Fix correct redirect of the user onlogin WordPress or adding/user/onlogin to proper page.
* Thank to @drauth bugs reports, all these reported issue have been now resolved and all should work very fine.
* Minor fixes.

= 1.8.4 =
*Release Date - 10 Mar, 2018*
* Fix correct addition of the phpBB user on WordPress, when coming as logged in from phpBB OR logging in in WP first time as not logged into phpBB. The code flow was causing a loop now resolved.
* Fix correct lang switch in (+-) any possible configuration, but with some exception: please read this:  https://wordpress.org/support/topic/default-language-24/
* Translation of the readme.txt instructions by @drauthr
* Minor fixes
* Update as soon to this stable (quite deep tested) 1.8.4 version!

= 1.8.3 =
*Release Date - 2 Mar, 2018*

* 1.8.3 important login/out bug fix: fix correct cookie value due to missed var on functions login/out/session set of file class.wp.w3all-phpbb.php. Now cookie setting has been definitively resolved. Please update to overwrite the unique file class.wp.w3all-phpbb.php, patched to fix this issue.

= 1.8.2 =
*Release Date - 20 Feb, 2018*

* Fix correct cookie setting, leading to several different bugs, on several circumstances, on different servers for localhost installations (also affecting the iframe mode on localhost).
* Fix for bug reported by @Alexvrs: display the correct link to phpBB ucp (phpBB PM page) (iframe or not iframe) on Admin Tool Bar.
* Add option about activate or not Font Awesome lib for Last Topics Widget and Shortcode read/unread option. Make in this way the integration without linking users, also suitable for phpBB versions 3.1 < where maybe font awesome was still not included by default. In any case, better because let choose to use or not Fontawesome for read/unread option purpose.
* Patch addons/page-forum.php to setup correct domain.name for the iframed WP page on localhost installations.
* Fix 'fail login' in localhost installations and certain server configuration/browser.

= 1.8.1 =
*Release Date - 2 Feb, 2018*

* Fix: install admin on phpBB UID 2 and WP admin UID 1 have different usernames, so if there are posts into Last Topics Widgets or Last Topics Shortcode about this user, the avatar fail to display.
* Fix an old - Admin related - avatar issue (secondary bug, but obscure the reason of this behavior): when an admin open the Discussion settings on WordPress administration: WP admin -> Settings -> Discussion, the avatars lists, show the viewing user avatar for all options, instead than different gravatars
* Add option: Retrieve phpBB avatars by using native phpBB file.php, that avoid to edit the .htaccess file on phpBB to get avatars available in WordPress, as suggested by Alexvrs. Check it here: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=622&p=2558#p2557
* Add feature: force user addition at same time into Wordpress when register and then login into phpBB (even if not in iframe mode). Check this: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=643
* Add feature: Mark Unread Messages into Last Topics using Font Awesome icons, as suggested by @kaspir. Check this: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=644#p2597
* Really improve the user transfer process WordPress users into phphBB. Check this https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=642#p2592
* More minor fixes
* The online Help Install, has been little improved, while adding the new features hints/explanations. https://www.axew3.com/w3/cms-plugins-scripts/wordpress-plugins-scripts-docs/wordpress-phpbb-integration/

= 1.8.0 =
*Release Date - 11 Gen, 2018*

* Add feature: 'integration without linking users', also cross domain: see https://www.axew3.com/w3/2018/01/wordpress-phpbb-integration-without-linking-users/
* Add (initial) SEO fix for links on Widgets Last Topics, that affect if on 'iframe links mode', see this: https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=620&p=2543#p2543 . Note that if you use custom template files to display Last Topics Widgets or Shorcodes, that you've copy/paste from views folder into the 'wp-w3all-config' folder, you can follow using old files you have edit, or switch to news with SEO links fixes that are contained into 'views' folder
* Remove the file 'views/phpbb_login_form.php': the code has been moved inside proper function that fire the Login Widget for a more clear code and fast execution. You can safely remove/delete this not more used file 'phpbb_login_form.php' from folder: /wp-content/plugins/wp-w3all-phpbb-integration/views
* Minor fixes

= 1.7.9 =
*Release Date - 15 Dec, 2017*

* Add option: 'Use custom files to display Last Topics Widgets, Login Widget or Last Topics Shortcode content'. The option is (quite well) self explained into WP_w3all config page, on related added option field
* Fix: avatar bug that may was coming out if no avatars retrieved to display in certain conditions
* Fix Error: username or email already exist, in case the coming logged phpBB user, or logging in WP, phpBB user, need to be add into WP side
* Fix: if login is done via phpBB into WP iframed page, redirect to the WP iframed forum page
* Minor fixes
* Iframe mode improvements, Common Tasks option, (integration without linking users mode option)?, have been moved to next 1.8.0 version

= 1.7.8 =
*Release Date - 13 Nov, 2017*

* Fix errors on two queries (in certain conditions)
* Fix redirect and profile fields update
* Fix register_uninstall_hook, now correctly wrapped to be executed only one time, when integration start, and not any time the WP admin was loaded
* Minor fix to add compatibility with old md5 phpBB passwords check if used to login on WP side
* Minor fixes

= 1.7.7 =
*Release Date - 28 Sep, 2017*

* If using embedded iframe: it is required to replace the old file 'iframeResizer.contentWindow.min.js' added into phpBB root with the new updated one you'll find inside folder: wp-content/plugins/wp-w3all-phpbb-integration/addons/resizer
* Fix important bug related to WP user ID 1 and phpBB user ID 2 (default install admins) if username mismatch. 1.7.6 was already patched about this: https://wordpress.org/support/topic/1-7-6-patches/
* Fix error on retrieve correctly user datas on certain user's actions. As above, 1.7.6 was already patched about this.
* Add WP integration compatibility with phpBB user's banning actions. A phpBB banned IP, email or username, will not be able to login or register WP. If user is banned by username in phpBB, he will be logged out immediately, even without visiting phpBB, when return to visit WP side with a valid cookie. But if a ban is issued by IP or email in phpBB and the user in the while return back to site visiting WP pages with valid cookie credentials, will result logged in until he not visit phpBB (so phpBB will reset his cookie and will be logged out). This secondary aspect will be fixed before, or on WP_w3all 1.8.0.
* Add [media] bbcode parsing, to show embedded phpBB posts video into WP posts, when bbcode about phpBB mod - mediaembed - is used/installed into phpBB: https://www.phpbb.com/customise/db/extension/mediaembed/
* iframe: fix iframe scroll for all iframed phpBB pages behaviors. The code on procedure as been updated and page-forum.php has been also substituted. The code has been reduced of several lines of code on both phpBB overall_footer.html and page-forum.php. See procedure page https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* iframe: fix iframe for correct URL on WP admin bar about PM. Now correctly point to iframe or full phpBB, based on WP_w3all configuration setting about iframe links.
* iframe: update iframe resizer JS library to latest available: https://github.com/davidjbradshaw/iframe-resizer (so as indicated above you'll need to replace the old 'iframeResizer.contentWindow.min.js' file on phpBB root, with the new one updated)
* Security fix for function wp_w3all_get_phpbb_lastopics_short (that display Last Topics Shortcode in WP)
* More minor fixes

= 1.7.6 =
*Release Date - 19 Sep, 2017*

* 1.7.6 contain important fixes about several problems in several circumstances and do not add any feature. Please update as soon.

= 1.7.5 =
*Release Date - 14 Sep, 2017*

* Add Buddypress avatar and Buddypress user's profile fields integration options: about how to setup Buddypress Profile Fields integration, please see https://www.axew3.com/w3/2017/09/wordpress-and-buddypress-phpbb-profile-fields-integration/
* Last Topics Widget re-fix: one single query, for all Last Topics widget's instances.
* Last Topics Read/unread fix (reported as bug, and finally found why it was coming out (no phpBB uid passed in certain conditions)).
* Add Shortcode to retrieve and display phpBB Last Topics on WP posts, pages etc. About how to use it, please see https://www.axew3.com/w3/2017/09/wordpress-shortcode-last-phpbb-topics-posts-into-wp-post/
* Functions about profile updates have been optimized (class WP_w3all_phpbb -> phpbb_update_profile AND class WP_w3all_phpbb -> verify_phpbb_credentials).
* Fix some text hints on Wp_w3all admin config page.
* More important fixes and code cleanup.

= 1.7.5 =
*Release Date - 14 Sep, 2017*

* Add Buddypress avatar and Buddypress user's profile fields integration options: about how to setup Buddypress Profile Fields integration, please see https://www.axew3.com/w3/2017/09/wordpress-and-buddypress-phpbb-profile-fields-integration/
* Last Topics Widget re-fix: one single query, for all Last Topics widget's instances.
* Last Topics Read/unread fix (reported as bug, and finally found why it was coming out (no phpBB uid passed in certain conditions)).
* Add Shortcode to retrieve and display phpBB Last Topics on WP posts, pages etc. About how to use it, please see https://www.axew3.com/w3/2017/09/wordpress-shortcode-last-phpbb-topics-posts-into-wp-post/
* Functions about profile updates have been optimized (class WP_w3all_phpbb -> phpbb_update_profile AND class WP_w3all_phpbb -> verify_phpbb_credentials).
* Fix some text hints on Wp_w3all admin config page.
* More important fixes and code cleanup.

= 1.7.4 =
*Release Date - 27 Jul, 2017*

* Fix woocommerce warning on WP login and WP_w3all plugin login flow code.
* Add shortcode to retrieve and display (formatted or plain text) phpBB posts (by ID), into WP posts, see https://www.axew3.com/w3/2017/07/wordpress-shortcode-phpbb-posts-into-wp-post/
* Add transfer phpBB users into WordPress (transfer option useful in some contest).
* Fix option 'Retrieve posts on Last Topics Widget based on phpBB user's permissions'.
* Fix option 'Last Posts on widget read/unread': eliminate a not needed query and add correct code to output icon read/unread in any configuration (not showed if avatars option disabled, due to a variable lack on output code, and never reported by anyone as bug)
* Fix/Add css classes for the widget WP_w3all Login (so it is possible to style it like the Last Topics Widget) https://www.axew3.com/w3/forums/viewtopic.php?f=2&t=550#p2158.
* Fix correct URL on WP_w3all login widget, to correctly point to PM phpBB folder, when there are new PM messages and notice url link is shown.
* More minor fixes.

= 1.7.3 =
*Release Date - 27 Giu, 2017*

* Maintenance release: just improve correct auto cookie setting for new installs, add hints on WP admin wp_w3all config page for correct installation and a fix latest option added.
* Fix add option 'Retrieve posts on Last Topics Widget based on phpBB user's permissions' html error due to not existent var and subsequent html error on related option in wp_w3all admin config page.
* Improve and fix cookie setting.

= 1.7.2 =
*Release Date - 20 Giu, 2017*

* Fix correct code flow, if registration on WP for the WP user fail (maybe due to a thrown WP error).
* Add option 'Retrieve posts on Last Topics Widget based on phpBB user's permissions'
* More easy procedure and code improvements/fixes for phpBB iframe emebedded page: you can follow use the old one page-forum.php. But if you like to update to the new version, the page-forum.php need to be rebuilt and the code of overall_footer.html also need to be updated to the last one: https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* Remove the modal login for iframe, fix correct url redirect for phpBB emailed notification links
* Minor security fixes
* Several code fixes
* Improve correct cookie value auto-setup

= 1.7.1 =
*Release Date - 8 May, 2017*

* Fix cookie setting to be detected and setup one time, not at 'runtime' when page load (that was since 1.7.0 to correct detect of cookie setting). Now the check is for any www top level domain (for advanced -> see new function: w3all_extract_cookie_domain() on wp_w3all.php).
* Fix x MUMS: user was not added into phpBB on creating site/blog and related new user subadmin via admin.
* Fix x MUMS: users addition error message: see https://wordpress.org/support/topic/1-7-0-released/#post-9106748
* Tested to fix adding/registering/login users into WP default, WP Network Multisite, Buddypress (and should fix any other plugin that use signups). Still not add all profile fields update for Buddypress, it will be done (hope) on 1.7.2.
* Added manual setting on wp_w3all.php to force plugin deactivation if necessary, to make possible a clean uninstall of the plugin even on most worse situations or just for testing purpose (this is completely redundant, if the plugin is installed using phpBB custom manual config.php, because you can deactivate the plugin within this file instead (as explained on inline hints in file)). A documentation help for common problems about will be done asap (if never necessary for someone).
* Added manual setting on wp_w3all.php also for cookie to force cookie setting change (option about this will be added also into wp_w3all admin config on next version).

= 1.7.0 =
*Release Date - 4 May, 2017*

* !Important WP MU Multisite: fix correct user addition into WordPress of registered phpBB users. Please read the procedure necesssary to setup correctly the plugin on a WP MSMU installation here: https://www.axew3.com/w3/2017/04/wp_w3all-for-wordpress-ms-mu-multisite-multiuser-network-installations/
* !Important: Fix cookie domain fail on domains suffix like .co.uk or subdomains. Now cookies are properly configured before to be set, for any possible suffix on domain or subdomain. It was causing mainly, also, the most famous problem: loop on login/out on domain suffix like co.uk and all others similars.
* Include all long list of patch fixes applied on 1.6.8 (and more). See this topic: https://wordpress.org/support/topic/1-6-9-release-additions-and-improvements/ or/and this https://www.axew3.com/w3/index.php/forum/?f=2&t=506&p=1969#1969 for (not complete) list of fixed bugs.
* Iframe improvements: all the js code has been patched and the page-forum.php has been improved for correct scroll on main events. The documentation about has been rewrite to be more easy (and i hope) in an Eng more understandable! The iframe, with proper settings, work on any integration type like domain/subdomain or subdomain/subdomain.
* Correct users addition/activation/redirection on all possibles done tests.
* Several important more fixes.

= 1.6.9 =
*Release Date - 26 Apr, 2017*

* Security fix.
* Add Multisite installation compatibility (1st version): it is possible to link only the same phpBB install if the plugin is activated as network. Is not possible to link a different phpBB on sub-sites! Explanation article will be available here: https://www.axew3.com/w3/2017/04/wp_w3all-for-wordpress-ms-mu-multisite-multiuser-network-installations/
* Fix user addition as active into phpBB, if the user is added manually by admin.
* Fix correct redirect existent phpBB user when added onlogin in WordPress.
* Add Buddypress compatibility (no profile fields updates for fields like Google, Twitter etc. This aspect is moved to next versions)
* Iframe mode: (this is not required, you can still use the old one phpBB overall_footer.html code with old WP page-forum.php). The page-forum.php has been heavily modified/improved, js/ajax code has changed, as well the rewrite/push mode of iframe links. Rebuild on wp_w3all config or manually substitute page-forum.php (contained in the plugin VIEWS folder) and change overall_footer.html phpbb JS code with the new one. To switch to ajax 1.6.9 iframe features please follow here: https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* Iframe mode for subdomains installation: added code and inline code hints (into views/page-forum.php file and overall_footer.html file) to resolve any possible iframe compatibility issue.
* More relevant fixes.
* Minors code clean up.

= 1.6.8 =
*Release Date - 4 Apr, 2017*

* Fix/option language switch for users between WP/phpBB: it is now option on wp_w3all config page to be activated or not (explanation added on same option field).
* Add user's last visit time update in phpBB even if user visit WordPress, but not phpBB forum. Added into existent time/update query, do not add any call more to phpBB db.
* Fix display 'You have n unread phpBB PM': by default it display only if there are new pm messages and not if 0 (zero).
* Replace preg_match to check for email, with native WP function about.
* Some code change into iframe default page-forum.php code: if you wish to update to latest iframe changes, you need to rebuild page forum into wp_w3all Admin config page (or do it manually) and it is necessary to update the overall_footer.html code of phpBB template with latest: https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* Minors code clean up.


= 1.6.7 =
*Release Date - 2 Apr, 2017*

* Add profile language update between phpBB and WordPress for users
* Fix deactivated phpBB user re-added in WordPress (maybe deactivated in phpBB due to deletion of the user in WordPress). Now a deactivated phpBB user will not be added or re-added into WordPress, as default behavior
* Fix display correct number of topics for each different widget instance setup
* Add avatar row on wp login widget user's info
* Fix Last Topics Widget html output to be more like any WP widget default output: fix correct separation between code and text output on same file (views/phpbb_last_topics.php)
* Improve query execution time for Last Topics Widget when Avatar active: the query is executed by searching for 'phpBB email hash' which is Key Index into phpBB db, and not by username.
* Fix uninitialized variables bug in WP_w3all admin config page, that was leading WP_w3all admin config page to emit notice errors as html output into Config fields for Avatars or Preferences (where php ini were set to display php errors/notices: it was breaking options fields).
* More minor fixes

= 1.6.6 =
*Release Date - 23 Mar, 2017*

* Fix scroll for +- all phpBB iframe instances in the definitive way. The page-forum.php need to be rebuilt and overall_footer.html in phpBB need to be updated with the new code to apply all javascript fixes. Substitute the old code added into phpBB overall_footer.html with the new one you can found here: https://www.axew3.com/w3/2016/02/embed-phpbb-into-wordpress-template-iframe-responsive/
* Fix partial execution of code about Last Topics Notification, fired also when option not active
* Add User Info option into Login Widget: Name, Registration Date, Unread PMs, Number of posts on phpBB. Avatar will be added on next versions.
* Add Notification about new messages in phpBB, into WP admin bar menu for users (not suitable maybe for iframe mode, as it need to reload WP page to update state read/unreads. It will be improved soon with ajax to be responsive in this sense)
* More minor code clean up and fixes

= 1.6.5 =
*Release Date - 14 Mar, 2017*

* Fix correct login for all WP Social plugins (and supposedly also for frontend ajax login plugins, but this has not been tested)
* With the above, should resolve also any login problem for all external plugins login pages
* Fix iframe scroll top for (+- all) phpBB events, except for Post Preview: onclick it is now correctly redirected inside iframe, but page will not scroll to top. To apply changes about correct scroll, it is required to rebuild the forum page on WP admin -> wp_w3all config page (or substitute it manually)
* Fix correct post's time for Last Topics Widget
* Remove the use of addons/ext_plugins_fixes.php file, since the fix above about Social plugins: this remove a bug related session keys on phpBB, that were duplicated WP onlogin event
* Correct WP error message 'user already exist on our forum' if user exist on WP registration
* More minor code fixes

= 1.6.4 =
*Release Date - 27 Feb, 2017*

* Security fixes
* Fix error mysql connect if wrong path set for the config.php file on wp_w3all config at first install (or maybe after, setting it as wrong for testing pourpose disabling wp_w3all)
* Speed up queries reducing to the minimum possible calls to phpBB (expecially when last topics and phpBB avatars active)
* Fixes Last Topic Widgets code execution
* More minor fixes


= 1.6.3 =
*Release Date - 22 Feb, 2017*

* Fix correct password check/set between phpBB and WordPress. All WordPress passwords are now hashed (and checked when required) as phpBB bcrypt(). Once the integration will be uninstalled, WP users with passwords containing certain chars, will have to reset their password to correctly login into WordPress.

= 1.6.2 =
*Release Date - 19 Feb, 2017*

* Fix correct redirect on login, where user was appearing as not logged (because redirected to the wp-login login page) also if correctly logged in, in certain conditions.
* Fix correct user activation/deactivation of the user in phpBB when user is set as No Role in WP admin, or is reactivated. When reactivated in WP, will be reactivated as normal user in phpBB, despite if you'll reactivate with different role in WP. Next version will fix also this.
* More minor fixes.

= 1.6.1 =
*Release Date - 16 Feb, 2017*

* !Important - Fix 'phpBB get config' function, which was called and repeated several times during plugin code execution. It is now exeuted only one time, on any possible plugin options configuration.
* !Important - Fix WP_w3all Preferences: no reports about this but, the only one option working was the 'Activate WordPress to phpBB users transfer', on 'WP_w3all Preferences' options section while all others fails.
* Add - notify about Read/Unread Topics/Posts option (WP_w3all Preferences), for registered users in Last Topics Widgets.
* More important fixes.

= 1.6.0 =
*Release Date - 12 Feb, 2017*

* Fix first login fail on register in WP definitively.
* Update all iframe resizer files. It is Request to Update the old iframeResizer.contentWindow.min.js into phpBB root. Please replace the old phpBB root iframeResizer.contentWindow.min.js file with new one contained inside the /wp-content/plugins/wp-w3all-phpbb-integration/addons/resizer folder.
* Minor fixes.

= 1.5.9 =
*Release Date - 28 Gen, 2017*

* Fix minor security bug.
* Fix any PHP error notice and warning (NOTE: except Warnings if path setting wrong on wp_w3all config).
* Fix correct redirect in WP page where login has been performed, if login done using the WP_w3all widget Login/out.
* Several code fixes.
* More minor fixes.

= 1.5.8 =
*Release Date - 24 Gen, 2017*

* Fix correct phpBB 3.2> - phpBB 3.1> profiles fields update about url (and fix ready code for all other fields).
* Fix bug avatar about: if on phpBB option avatar.driver.remote enabled, avatar in WP was not included for the user.
* Fix language files, adding all configuration page hints and frontend into translated strings. The unique NOT translated part, is the Admin Users Transfer page of wp_w3all.
* Fix logout loop, if on phpBB there is a wrong phpBB cookie settings.
* More minor fixes.

= 1.5.7 =
*Release Date - 9 Gen, 2017*

* Fix bug 'first user login on WP fail'.
* Fix correct redirect for widgets, and get vars, using native WP functions.
* Resolve 'Iframe, post preview fail': for this fix, that is related to phpBB overall_footer.html code, please just read this post: https://wordpress.org/support/topic/phpbb3-post-preview/#post-8630131 .
* Fix correct redirection to WP forum page, onlogin, if login done via modal/iframe.
* Add translation files for all front end strings output (if not please report), but not for admin configuration back-end.
* Fix 'Warning unwanted chars detected' issue, that was wrongly fired on some path settings.
* Fix a bug on widget file inclusion: translate function on wrong place has been removed.
* Fix a php Warning, that was suppressed by WP on redirect (so maybe never seen) by fixing the - add_action('wp_login' - adding correct params call.
* More fixes.

= 1.5.6 =
*Release Date - 11 Nov, 2016*

* Resolve problem about login, related latest security bug fix reported on post https://wordpress.org/support/topic/locked-out-of-admin-by-latest-update/
* Correct sanitization, that should solve (maybe) many other compatibility problems.
* Resolve correct addition and no more loop redirect, if user register and login directly in iframe mode.
* Fix more hints on Admin config page.
* Fix more issues and updates.

= 1.5.5 =
*Release Date - 8 Nov, 2016*

* Fix a security bug.

= 1.5.4 =
*Release Date - 7 Nov, 2016*

* Fix avatars bug. Should be the definitive fix that should resolve all reported bugs about avatars.
* Fix more secondary bugs.
* Fix some English hints on WP_w3all admin config page.

= 1.5.3 =
*Release Date - 23 Oct, 2016*

* Fix error user already added on login when user added from phpBB into WP (on first time login on WP).
* Fix avatar on Last Topics Widget bug when no number for avatars to retrieve was set on admin WP_w3all config.

= 1.5.2 =
*Release Date - 23 Sep, 2016*

* Fix user insertion into WP for certain phpBB users with not allowed specific chars. Please take a look into this post for further info about: http://www.axew3.com/w3/forum/?forum_id=2&topic_id=188&post_id=828#p828
* Fix avatar on Last Topics Widget bug, due the same above related problem, if user was containing unwanted char in WP the query was return error.
* Fix error "user already added" message if a phpBB user was added in WP after login action, and correct auto login.
* More fixes

= 1.5.1 =
*Release Date - 16 Sep, 2016*

* Fix definitively users transfer, and wrong user addition if username contain certain characters
* Fix wrong WP user addition on phpBB for the same above reason (user exist but after not found on ACP: after this release, will follow an article on forum on how to easily fix this problem in phpBB ACP, will be posted, if there is any to resolve.
* Fix missed <ul> tag on Last Topics Widget
* More fixes

= 1.5.0 =
*Release Date - 12 Sep, 2016*

* Warning for WP_w3all installations using manual config.php on WP_w3all admin config page! Please READ this http://www.axew3.com/w3/index.php/forum/?viewforum=2&viewtopic=173 before update to 1.5.0!
* Add option: replace WordPress Gravatars with user's profile phpBB avatars, if there is not a phpBB avatar for the user, Gravatar will be displayed (Vers 1.0)
* Improved Last Forums Topics query by Sitmo2012 https://wordpress.org/support/users/sitmo2012/
* Fix all Php Notices and Warnings
* Fix groups assignement for users added in WordPress from phpBB
* Hints: improvement and corrections
* More code fixes

= 1.4.9 =
*Release Date - 14 Aug, 2016*

* Add - Deactivate phpBB user account (option) until WP confirmation
* Fix profile fields updates (if empty URL field on user profile, update was not executed correctly)
* Fix minor iframe bug

= 1.4.8 =
*Release Date - 20 Jul, 2016*

* Fix 1.4.7 just released, more profile bugs. Update as soon please!

= 1.4.7 =
*Release Date - 19 Jul, 2016*

* Add Manual phpBB configuration mode for compatibility with some external plugins (and for a more easy and fast config include, expecially on subdomains installation). See wp_w3all config admin page for easy instructions to switch to manual config (that mean just change path to the new edited config.php file)
* Fix several compatibility problems about external, users login/registration profile update, plugins
* Fix correct wordpress default profile fields updates
* Fix minor security bug
* Fix minor bugs

= 1.4.6 =
*Release Date - 15 Giu, 2016*

* Only an important security fix. Update as soon.

= 1.4.5 =
*Release Date - 14 Giu, 2016*

* Fix stay on index and viewforum when refresh browser iframe mode
* Fix correct config include and code execution (on rare possible scenario)
* Fix Last Topics Widget notice error and overall WP on certain php.ini configurations
* Fix more minor bugs
* Add compatibility with plugins that replace default WP login page (not ajax frontend login widgets)

= 1.4.4 =
*Release Date - 9 Giu, 2016*

* Fix remember me auto login that was Not solved since 1.4.0

= 1.4.3 =
*Release Date - 8 Giu, 2016*

* Fix users deactivation/activation in WordPress, that are at same time activated/deactivated in phpBB
* Fix correct number of topics to display for each Last Topics Widget instance

= 1.4.2 =
*Release Date - 7 Giu, 2016*

* Fix logout and remember me auto login

= 1.4.1 =
*Release Date - 6 Giu, 2016*

* Fix WP logout loop redirection and logout (remain minor fix)
* Fix Last Topics Post - Text Post Mode fix, where was failing to clean/remove tags
* Fix correct session user update
* Fix, user active, or not on register: registered phpBB user, not active in phpBB, added as deactivated in WP (ex: awaiting validation)
* Fix more 1.4.0 bugs


= 1.4.0 =
*Release Date - 31 Mag, 2016*

* Fix user profile fields update: email, password, URL. Direct update in phpBB if profile update done on WP side. If user profile is modified via phpBB, the update in WP is done when user visit WP side
* Add Posts Text option on Last Topics widget - Post Text option to display latest topics, only with title/link author and date, or title/link, post text (choose how many words for each widget), author and date
* Ready (on next version) for more profile fields updates like (signature on phpBB to Bio info on WP and viceversa), Facebook, Google etc.
* Fix WP to phpBB users transfer definitively: users are correctly added as activated on phpBB if with roles on WP, deactivated in phpBB if no Role on WP
* Fix for more security on login
* Fix several minor bugs
* Add https

= 1.3.9 =
*Release Date - 23 Mag, 2016*

* Remove latest additions about ajax plugins with login on frontend ajax widget, due to several bugs that cause onlogin/out
* English language corrections: thank to member raykaii at WordPress.org

= 1.3.8 =
*Release Date - 21 Mag, 2016*

* Fix compatibility with ajax login plugins, ReCaptcha ...
* More very important fixes

= 1.3.7 =
*Release Date - 19 Mag, 2016*

* Minor (but important to fix) bug about function wp_delete_user function not exist.
* Minor fixes

= 1.3.6 =
*Release Date - 19 Mag, 2016*

* !Important Fix add user into phpBB bug on register WP (since 1.3.4)

= 1.3.5 =
*Release Date - 15 Mag, 2016*

* Fix user email address update on WordPress if user email is modified on phpBB profile. Update is done when user visit WordPress. If instead, email address change is done via WordPress Admin or user profile, it is immediately applied on both WP and phpBB.

= 1.3.4 =
*Release Date - 15 Mag, 2016*

* Fix wp_error coming logged as newer user into WordPress

= 1.3.3 =
*Release Date - 14 Mag, 2016*

* Fix 1.3.2 user logout bug when login done on WP side

= 1.3.2 =
*Release Date - 13 Mag, 2016*

* Fix users transfer, that can be executed safely also more than one time consecutively
* Deactivated/activated/banned (no role user) user in WP admin, is deactivated/activated on phpBB. Deactivated/banned etc user in phpBB ACP is auto deactivated only on user WP onlogin
* More important fixes

= 1.3.1 =
*Release Date - 3 Mag, 2016*

* Fix cookie check and reload problems in some installations and remember me logins

= 1.3.0 =
*Release Date - 2 Mag, 2016*

* Fix wrong old phpBB user addition in WordPress when username was containing wrong chars. It solve so probably, any other reported redirect error.

= 1.2.9 =
*Release Date - 1 Mag, 2016*

* Fix bug subdomains login/out due to a wrong setcookie

= 1.2.8 =
*Release Date - 29 Apr, 2016*
* Fix presistent autologin

= 1.2.7 =
*Release Date - 27 Apr, 2016*
* Important! Fix old phpBB users, that login first time in WP and aren't added: now are added and logged on fly
* Fix redirect problems on some installations
* Fix stay on topic or page on browser refresh (iframe mode)
* More important fixes

= 1.2.6 =
*Release Date - 22 Apr, 2016*
* Fix ONLY 1.2.5 bug about path setting not correct and WP admin logout can't login error

= 1.2.5 =
*Release Date - 21 Apr, 2016*
* Fix persistent login
* Fix changed user passw on phpBB, if first login in WP instead of phpBB
* Fix WP users transfer to phpBB

= 1.2.4 =
*Release Date - 18 Apr, 2016*
* Fix correct username/nicename registration insert into phpBB
* Fix users transfer for correct username/nicename registration insert into phpBB

= 1.2.3 =
*Release Date - 17 Apr, 2016*
* Fix user post count on transfer process where was set to 1 and should be 0
* Fix persistent rememberme autologin
* Fix user post count on phpBB user registration when done on WP side

= 1.2.2 =
*Release Date - 15 Apr, 2016*
* Unique config include that fix numerous problems and increase script execution speed
* Cookie domain fix for subdomains
* Security fix
* More minor fixes

= 1.2.1 =
*Release Date - 8 Apr, 2016*
* Fix sessions +- definitively. Remain to unify queries
* Fix password/email change for WP admin to match phpBB pass if different on WP login
* Links for iframe: viewtopic and viewforum
* WP 4.5 more fix

= 1.2.0 =
*Release Date - 5 Apr, 2016*
* Fix sessions
* Fix password/email change for admin and users
* WP 4.5 ready

= 1.1.9 =
*Release Date - 29 March, 2016*
* Fix bug user password reset on WP
* First fix for installations on WP multisite

= 1.1.8 =
*Release Date - 28 March, 2016*
* Fix bug about when wrong path and warning messages
* More fast (action wp_loaded replace init for the wp_w3all_phpbb_init)
* Prepare for next release and definitive sessions fix onlogin, when login performed on WP side

= 1.1.7 =
*Release Date - 27 March, 2016*
* Fix phpBB session logic
* Login/register/lostpass by default in WP if actions are performed on WP side

= 1.1.6 =
*Release Date - 25 March, 2016*
* Fix login out of admin in front end

= 1.1.5 =
*Release Date - 24 March, 2016*
* Users can now login/out/register and change profile email or password on WP or phpBB. More on next versions.
* Fix iframe mode logout when performed from WP: required to rebuild the forum page on wp_w3all or manually modify adding the new logout fix.
* Fix user transfer from WP to phpBB, where last insertid value was missed during insert query
* Fix several bugs

= 1.1.4 =
*Release Date - 10 March, 2016*
* Fix iframe mode
* Remove unuseful options on config wp_w3all
* Add modal CSS login for iframe mode
* Fix user transfer bug
* Fix several bugs

= 1.1.3 =
*Release Date - 7 March, 2016*
* Fix login/out

= 1.1.2 =
*Release Date - 5 March, 2016*
* Fix problems about correct links and inclusion for widgets
* Fix more bugs about iframe mode
* Added option for widget links iframe mode subdomains or not

= 1.1.1 =
*Release Date - 3 March, 2016*
* Fix problem for wordpress repo that do not correctly download the new inc folder, so it has been removed

= 1.1.0 =
*Release Date - 1 March, 2016*
* Solve more wp_w3all path setting bug

= 1.0.9 =
*Release Date - 29 Febrary, 2016*
* Solve wp_w3all path setting bug

= 1.0.8 =
*Release Date - 29 Febrary, 2016*
* Add responsive javascript or css iframe option that is created by default
* Added related documentation

= 1.0.7 =
*Release Date - 28 Febrary, 2016*
* Fix login/out widget links for iframe mode
* Fix correct template page name detection
* Fix minor bugs

= 1.0.6 =
*Release Date - 23 Febrary, 2016*
* Fix config problems
* Add more friendly configuration interface
* Fix minor bugs

= 1.0.5 =
*Release Date - 21 Febrary, 2016*
* Added phpBB embedded into WordPress template feature
* Added js library
* Fix minor bugs

= 1.0.4 =
*Release Date - 15 Febrary, 2016*
* Fix cookie logout bug for subdomains and minor bugs

= 1.0.3 =
*Release Date - 14 Febrary, 2016*
* Added subdomain capability to the wp_w3all integration plugin. You need to change the relative path on wp_w3all config and substitute with ABSOLUTE path.

= 1.0.2 =
*Release Date - 13 Febrary, 2016*
* Added WordPress users transfer to phpBB forum
* Added exclude Forums from last Topics option
* Fix database cleanup on plugin deactivation
* Fix redirect after login on WordPress to correct WP page
* Fix lost config values on plugin update
* Fix minor bugs

= 1.0.1 =
*Release Date - 2 Febrary, 2016*
* Fix problem about default install administrators (Uid 1 on WP and Uid 2 in phpBB) with different usernames.
* Added to the widget w3all Login the option to choose different text to display on login/out.

= 1.0.0 =
*Release Date - 1 Febrary, 2016*
