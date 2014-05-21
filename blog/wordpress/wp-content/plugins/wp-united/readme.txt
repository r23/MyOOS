=== WP-United : phpBB WordPress Integration ===
Contributors: Jhong
Donate link: http://www.wp-united.com/
Tags: phpbb, phpBB3, forum, social, integrate, bridge, integration, widgets, template, sign-on, theme, user integration, database, wp-united, cross-post, post to forum
Requires at least: 3.4.0
Tested up to: 3.9.1
Stable tag: 0.9.2.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP-United integrates phpBB and WordPress to make a social site. Enable any of these modules: sign-on, theming, widgets, cross-posting and behaviour.

== Description ==

Bridge phpBB and WordPress!

WP-United glues together WordPress sites and phpBB forums. Get the full power of WordPress publishing and plugins, with the familiar and established community format of phpBB.

WP-United is fully modular and individual features can be turned on and off. These features include:

* **User integration**: including single sign-on, synchronised profiles and avatars, and user management. Works with external registration modules such as social login plugins. Users in phpBB get accounts in WordPress and vice versa. Completely controllable and customisable by setting permissions for who can integrate and at what level, using a unique drag-and-drop interface.
* **Template integration**: Have your phpBB forum appear inside your WordPress site. Or vice-versa; integrate your WordPress blog inside your phpBB header and footer. Includes a "one-click" mode that analyses your theme's CSS and modifies it on the fly to avoid CSS and HTML conflicts. This is no iFrame solution, it is a complete and automatic template integration.
* **Behaviour integration**: Use phpBB smilies and word censor features in WordPress 
* **Cross-posting**: Post something in WordPress and have it cross-posted to your forum -- Automatically or manually, you choose! Once an item is cross-posted, comments in phpBB and WordPress can be set to sync up under the blog item too!

WP-United also includes TEN widgets for you to drop into your WordPress page. Each widget is configurable and displays a wealth of forum information to increase engagement on your site. All of these widgets work even with the above modules turned off. They are:

* Latest forum posts
* Latest forum topics
* users online list
* Forum statistics
* An integrated login/meta/avatar/profile block
* Birthday list
* Quick poll (select from active phpBB polls in an ajax widget)
* Useful forum links list
* Forum top bar (with breadcrumbs that work in WordPress)
* Forum bottom bar (to match the top bar!)

Visit [wp-united.com](http://www.wp-united.com) for more information and to view example integrations in the gallery.

The download iincludes the following languages:

* English
* Français
* Deutsch
* 中文（简体）
* русский
* Srpski (Latinica)
* Português Brasileiro

== Installation ==

For full instructions, please visit the [HowTo forum at wp-united.com](http://www.wp-united.com/viewforum.php?f=7)

1. Back up the current state of your site, just to be on the safe side.
1. Upload the `wp-united` directory and all its contents to the `/wp-content/plugins/` directory. (This will be done for you if you install through the WordPress Plugins menu).
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Install phpBB on your server somewhere -- anywhere is OK, even on a different subdomain -- and ensure it is set up and working OK. Pay particular attention to your phpBB "server settings" -- they need to be correct for WP-United to work.
1. Visit the WP-United section in the administration area to connect your WordPress site to your forum.
1. As part of the connection process, WP-United will prompt you to download and install a phpBB modification package. This will need to be installed into your phpBB3. We recommend you use [Automod](https://www.phpbb.com/mods/automod/) for this.

== Frequently Asked Questions ==

= Does this work with WordPress multisite? =
It works but is not recommended for use, especially with single sign-on currently, as it does not behave as expected. In future versions WP-United will allow per-user blogs with WordPress multisite, so stay tuned.

= I enabled the plugin but nothing is happening! =

Follow the instructions to connect to your phpBB forum in the WP-United section of the admin panel.

= I can't connect, I am getting errors. =

Ensure you have installed the phpBB modification package. You can download it from (WP-United.com)[http://www.wp-united.com/releases/wp-united-latest-phpbb] . You need to ensure that you install the package properly, for all installed phpBB templates and languages. The WP-United.com forum has more information.

= I am getting blank pages, I have no idea what is wrong! =

Please visit [This thread](http://www.wp-united.com/viewtopic.php?f=14&t=3314) to find out how to debug the problem.

= User integration is turned on, but I keep getting new WordPress accounts (e.g. `admin1`) instead of having existing accounts linked =

This is because you have "automatic user creation" turned on. It is just doing what you ask. To link together existing accounts, please disable "automatically create users in WordPress" option, and visit the WP-United User Mapper. There, you can clean up an unneeded accounts and link together the ones you want. When done, you can re-enable automatic user creation.

= User creation is turned on, but users keep getting logged out =

Please check your phpBB cookie settings. The "Cookie Domain" must be applicable for your whole site, including WordPress. Same for the "cookie path" (usuall '/' will be OK for this). More information is on the WP-United forum.

= Links to my forum are wrong! =

Check your phpBB server settings (phpBB ACP -> Server Settings). The domain and script path must be set correctly.

== Screenshots ==

1. The WP-United connection screen
2. The WP-United settings panel
3. The WP-United user mapper
4. The WP-United permissions mapper
5. A phpBB forum in a WordPress page
6. A WordPress page in a phpBB forum
7. Some WP-United widgets
8. The WP-United QuickPoll widget

== Changelog ==

= v0.9.2.8 RELEASE CANDIDATE 3 =
* BUGFIX: Fixed broken jQuery UI code causing path to show as unselected after saving settings.
* BUGFIX: Fixed broken draggable connectors on user mapper permissions page.


= v0.9.2.7 RELEASE CANDIDATE 3 =
* BUGFIX: Quick change to fix brokwn template integration in WP v3.9.1. Development has been paused for over a year, and there are more bugs to fix -- please watch this space.
* BUGFIX: Broken autocomplete in user mapper (thanks nata-lee)
* NEW: Replace phpBB avatars with a WordPress default when the user deletes it.

= v0.9.2.5 RELEASE CANDIDATE 3 =

* BUGFIX: Subscriber permission was missing in new installs, preventing permissions mapping from working and causing various other bugs
* NEW: Page is reloaded if WP-United manages to log in phpBB on a full-page phpBB-in-wordpress page after phpBB has already run
* NEW/BUGFIX: Arbitrate between phpBB & WordPress' make clickable functions properly. To use this new bugfix, you will need to upgrade the phpBB side
* BUGFIX: 404 headers sent on full page template integrations now properly fixed
* BUGFIX: Integrated phpBB users with WordPress counterparts that had been deleted were showing as "integrated" in the user mapper. Don't automatically re-integrate them, but show them as unintegrated so they can be attended to manually if needed.
* BUGFIX: Don't blindly follow and interpret stylesheet loops -- e.g. stylesheet A imports stylesheet B which imports stylesheet A and a black hole opens up
* BUGFIX: Cross-posting with explicit excerpts set were posting as full posts regardless of options
* BUGFIX: PM popup spawned from nav header widget was showing 404 error
* BUGFIX: Add users to default group correctly when crreated via WordPress, and add them to the "newly registered" group if needed
* BUGFIX: Integrated users couldn't log in to phpBB after doing a password reset in WordPress
* NEW: More donation options on the support page, including BitCoin and Google Checkout (Sorry, but every little bit is appreciated...!)
* NEW: Added new template edit to phpBB to fix issues with colour picker on posting page. You will need to apply the changes to each installed template
* NEW: Full translation into Serbian (Latin Script) (Thank you Uros Gavric!) and a partial translation into Brazillian Portuguese (Thank you Leonardo Silva!) 
* BUGFIX: Fixed corrupt characters in German translation


= v0.9.2.4 RELEASE CANDIDATE 3 =

* BUGFIX: style-fixer breaking images in imported stylesheets
* BUGFIX: style fixer dupe checker was comparing reset stylesheets
* BUGFIX: Errors on loading some stylesheets
* IMPROVEMENT: More robust setting and clearing of logged in cookies
* BUGFIX: colour chooser and and embedded width/height (old HTML4 html attributes) not working on integrated pages.
* BUGFIX: Fixed warnings on cross-posting due to deprecated WP-United "Users have own blogs" functions
* BUGFIX: Cross-posting spam filter settings couldn't be changed
* BUGFIX: User mapper view and suggestion autocomplete was dying on some non-ascii characters
* BUGFIX: Cross-posted topics published by a different user than the author were posting with the username of the publisher rather than the author
* BUGFIX/NEW: phpBB avatars can also be fetched for WordPress themes that call by e-mail.
* NEW: Added the option to remove the phpBB style switcher from the nav header bar widget
* NEW: Nicer headers on modified stylesheets
* NEW: reply counts on latest topics widget
* BUGFIX: Some integrated forums return 404 headers on some pages
* BUGFIX: Don't update session page when establishing phpBB session from WordPress, fixes broken topic view count
* BUGFIX: When cross-posting a scheduled blog post, get the excerpt/full-post choice right if set to "ask me".



= v0.9.2.3 RELEASE CANDIDATE 3 =

* Urgent bug-fix for improper error handling causing WP-United to catch all PHP errors, including those from other plugins.

= v0.9.2.2 RELEASE CANDIDATE 3 =

* NEW: Full French (Thank you Valbuena72) and Russian (Thank you Kot-Someone) translations
* NEW: Autologin / "Remember me" now mapped across between phpBB & WP when logging in.
* NEW: Template integrator can now recurse into most @imported child stylesheets -- and at least will not choke on those it can't
* BUGFIX: Better handling of modified wp-config file by core-patcher, and fix for short PHP tag in processed code (should fix some errors on logout, profile update, etc.)
* NEW: User banning and board shutdown now displays a WordPress message when user integration is active
* NEW: Allow selection of fall-back templates for older WordPress themes with no page templates
* NEW: template integrator can now handle absolute CSS URLs starting with /
* BUGFIX: Regression, missing cross-posted comments permission


= v0.9.2.1 RELEASE CANDIDATE 3 =

* NEW: Forum bottom nav bar widget (to match the top bar widget; thanks *daniel!)
* BUGFIX: Error with French phpBB language file
* BUGFIX: Javascript errors with some translations
* BUGFIX: Error in user mapper with russian localisation
* IMPROVEMENT: Minor cosmetic improvements to setings panels


= v0.9.2.0 RELEASE CANDIDATE 3 =

* NEW: Re-write of cross-posting and cross-posted comments. Cross-posted comments now appear mixed with WordPress comments, and can be viewed, filtered and managed from WordPress as well as phpBB. Cross-posted topics now also fully support custom ordering, threading and guest posting, and are stored and recalled more efficiently.
* NEW: Cross-posted comments (posted by guests) that are pending moderator approval in phpBB now show up in WordPress, with the appropriate "pending approval" message.
* NEW: Guest-cross-posted comments now store e-mail and website, just like native comments.
* NEW: New cross-posting comment permission in phpBB allows guests to cross-post comments without having to open your forum up to guests.
* NEW: Cross-posts by unauthenticated users can now be passed through WordPress filters (e.g. Akismet).
* NEW: The initial connection screen now falls back to manual path entry if your server has restrictions on scannng the document root
* NEW: You can choose to enter the phpBB path and document root manually, in case your phpBB root is under a different document root.
* BUGFIX: Users getting logged out of phpBB on full-page phpBB-in-WordPress
* WORKAROUND: Incorrect user integration flow when Ultimate TinyMCE (or similar plugins that set current user too early) are active.
* BUGFIX: Regression in avatars; default avatars getting syncd to phpBB rather than true Gravatars.
* BUGFIX: categories/tags & stats not showing up for some users if the same database user is used for phpBB & WordPress in some circumstances.
* BUGFIX: Initial connect screen was complaining about lack of phpBB MOD before even trying to connect
* NEW: More errors can now be passed through on the initial connect & settings screens: No more guessing what it is keeping you from installing.
* NEW / BUGFIX: The forum page title keeps getting reset back to "Forum" and the page creation date keeps updating.
* BUGFIX: Top navbar not correctly showing post name
* BUGFIX: Numerous issues with caching of template-integrated stylesheets leading to very full caches and some styling errors.
* BUGFIX: Userdata cache not cleared on first integrated login to phpBB
* BUGFIX: $table_prefix was getting unset on phpBB-in-WordPress pages, upsetting some mods
* BUGFIX: Improve handling of double-byte characters when escaping cross-posted topic titles and user names in user mapper.
* BUGFIX: Clash with the phpBB classifieds MOD
* BUGFIX: Synced avatars losing CSS styling
* ENHANCEMENT: Try multiple ways to initialise admin javascript, so it works even when other plugins with script errors halt JavaScript loading
* NEW: Lots more login integration debugging, so you can see what is causing login integration problems. Now works on WP pages and in admin too.
* NEW: Some more core rewriting and cleanup; the context switcher is now separated into its own parent class; the main plugin is now divided into auto-loading modules.



= v0.9.1.6 RELEASE CANDIDATE 3 =

* BUGFIX: Regression, profile update in WordPress was not triggering profile update in phpBB
* BUGFIX: Unread PMs not displaying in user login/profile widget or top navigation widget
* UPDATED: Removed output buffering intercept, should now work with gzip-enabled themes 
* UPDATED: Now works properly with W3 Total Cache and some other plugins that buffer output
* BUGFIX: Broken template tag for profile link (only affected legacy users with manually added wp-united template tags in their templates)


= v0.9.1.5 RELEASE CANDIDATE 3 =

* NEW/BUGFIX: The full page option now only allows page templates to be chosen, and works with child themes and subfolders
* BUGFIX: Error when updating avatars & profiles or logging out on full page reverse integration
* BUGFIX: suppressing unnecessarily triggered errors on initial connect and settings changes
* BUGFIX: Avatar marker added incorrectly to custom-set WordPress avatars
* BUGFIX: theme preview not working when WordPress-in-phpBB template integration is on
* BUGFIX: Difficult to change padding value in theme integration advanced settings, and the "reset to default" link didn't work
* BUGFIX: Autologin warning on login block widget in full page reverse integration
* CHANGE/BUGFIX: username & e-mail validation more reliable
* UPDATED: Specify index.php for forum link in admin bar in case index.php is not default served page


= v0.9.1.4 RELEASE CANDIDATE 3 =

* BUGFIX: WordPress users created in user mapper set to administrators. if you have created WordPress users using the user mapper previously, please check to ensure they are not administrators.
* BUGFIX: WordPress register date now showing correctly in user mapper and minor mapper display fixes
* BUGFIX: improper context switching in user mapper causing error with W3 Total Cache
* BUGFIX: phpBB normal ranks not showing for users in user mapper
* BUGFIX: minor avatar notice
* BUGFIX: Incorrect "forgot password" link in user login block widget
* NEW: integrated links in WP menu bar (admin-bar)
* Code cleanup and improved code documentation. Developers/hackers look at wp-united.php to get started.

= v0.9.1.3 RELEASE CANDIDATE 3 =
* This quick release fixes a missing file, a bug in the user mapper due to the large changes in v0.9.1.0, and the install.xml file. Please update.

= v0.9.1.0 RELEASE CANDIDATE 3 =

* NEW: Quick poll widget! Can have multiple polls per page, can submit via AJAX, BBCode & smilies etc. work. Can use prosilver or subsilver2 forum styles.
* NEW: Forum top bar widget, complete with phpBB-style breadcrumbs in WordPress!
* NEW: Forum birthdays widget!
* NEW: phpBB MOD installation is now more closely checked, and we also ensure the phpBB MOD and WordPress plugin versions match.
* NEW: The cross-post prefix, [BLOG], can now be changed in the settings panel.
* NEW: Smilies now obey phpBB's max smilies per post setting
* NEW: Get Help screen now shows active plugins, theme and memory settings to help in error reporting
* NEW: WP-United Extras: Drop-in plugins for easy additions to WP-United. The first 'extra' is the Quick poll widget! In future versions there will be a UI added to download additional extras.
* BUGFIX / NEW: Allow password portability for passwords with htmlentities or leading/trailing spaces
* BUGFIX / NEW: WordPress initial init is deferred when in phpBB until after phpBB auth has completed, this solves a number of login oddities with plugins and with admin bar not showing on phpBB-in-WordPress pages.
* BUGFIX: RTL layout not preserved when using template integration
* BUGFIX: Warnings on reply posting pages due to phpBB request variables interfering with WP_Query when template is integrated.
* BUGFIX: WordPress adding slashes to phpBB post and get variables
* BUGFIX: A number of minor bugs and error notices in widgets
* BUGFIX: Template cache not working when WordPress version has a dash in it (e.g. RC releases)
* BUGFIX: phpBB header/footer added to WP ajax on WordPress-in-phpBB pages
* BUGFIX: WP logout link in user profile/loginblock widget not showing phpBB status if user is not integrated
* BUGFIX: login/out link in "useful links" widget reversing login and logout actions
* BUGFIX: Better error handling if the plugin gets disabled due to errors

= v0.9.0.3 RELEASE CANDIDATE 3 =

* NEW: Quick user search box added to user mapper
* NEW: Synchronize profiles user mapper button and bulk action
* UPDATED: User mapper allows processing up to 250 users at once
* UPDATED: Don't repaginate user mapper after processing actions
* UPDATED: More permissive in returned messages from server when connecting and enabling (fixes errors on servers where files have leading or trailing garbage).
* BUGFIX: User mapper dying on entities in usernames
* BUGFIX: User mapper not displaying user names in alphabetical order when phpBB was on left
* BUGFIX: Load version when WP-United is disabled so things like "Get Help" still work.
* BUGFIX: Rank images had incorrect URLs

= v0.9.0.2 RELEASE CANDIDATE 3 =

* NEW: New widget: Useful forum links
* BUGFIX: Explicitly disable WP admin bar on simple header & footer phpBB-in-WordPress pages
* BUGFIX: Link to post not displaying in cross-post
* BUGFIX: Cross-post box not honouring excerpt/fullpost/askme choice


= v0.9.0.0/1 RELEASE CANDIDATE 3 =

* WP-United is, for the most part, completely rewritten, to improve flexibility and compatibility
* The vast majority of WP-United now sits under WordPress rather than phpBB. Find plugin, click install, done... that's what the aim is.
* Modular -- hooks and files are only loaded if those options are selected
* Brand new, modern, admin panel:
	* A completely re-imagined settings panel, with modern UI and with most options significantly simplified
	* No more Wizards!
	* Panel communicates with phpBB asynchronously -- no more blank pages!
	* New interactive user mapper that can integate, break integrations, create, delete and edit users in a few clicks
	* New draggable, connectable permissions mapper to hide the arcane phpBB permissions UI
	* Less options, more sensible defaults, all in one place
* Completely re-written user integration
	* User integration is now bi-directional. Log in or register in WordPress or phpBB, and seamlessly access the site
	* Roles are now set at user creation time, not every visit -- much more flexible
	* Bi-directional profile sync. Update your profile anywhere and it works
	* Auto-synced avatars. Get your Gravatar in phpBB -- without having to anything
	* Designed to work with external auth providers. e.g. click the Facebook button in the oneall plugin, and you get both a phpBB and WordPress account
* Widgets all ported to new WordPress API. 
* Widgets now colour phpBB usernames according to default group colour
* Numerous bugs addressed
* User blogs has been removed. It will be added back, working with WP-MS, in the next release.
* Translations all moved to WordPress


= v0.8.5 RELEASE CANDIDATE 2 =

* Fixed plugin fixer not covering all global variables
* Fixed plugin fixer not working with plugins tha use PHP short tags
* Suppress errors during path detection, etc, for people with open_basedir restrictions
* Fixed: If forum and blog are both in root directory, add explicit "index.php" to the forum link
* Added more classes to the login / userinfo widget to facilitate styling.
* New option: cross-post excerpts or full posts. Three choices: Excerpt / full post / ask each time
* Removed inline JavaScript for smilies. All WP-United JS moved to wp-united/js/wpu-min.js
* Fixed tags/categories not showing up properly on cross-posted posts
* Show "cross-post" in past tense in force xpost box if already xposted.
* Added two new options, SHOW_BLOG_LINK, and WPU_INTEG_DEFAULT_STYLE -- full info in options.php
* Fixed problem links in reverse integration, e.g. on phpBB FAQ page
* Fixed uncategorized checked when selecting cross-posting
* changed some BBCode translations
* Stopped allowing submission of blank cross-posted comments
* Fixed smiley path in cross-posted comments sometimes not correct
* User mapper is now case insensitive when looking for suggested phpBB username matches
* Added new option, WPU_SHOW_TAGCATS, to suppress display of categories & tags in cross-posts
* Fixed avatar and other details not synced when deleted by user in UCP
* Added option in options.php to define templates on which the WordPress header/footer should not appear, and pre-filled it with some shoutboxes.
* Installer auto-purges cache again when finished to ensure WP-United tab appears

= v0.8.4.1 RELEASE CANDIDATE 2 = 

* NEW: wpu-install.php removed, replaced by auto-installer
* Fixed comments closed for global announcements when they shouldn't be.
* Fixed unreliable display of comment date/time
* Added better error catching for people who don't install the mod properly (e.g. if they activate the plugin without installing/setting up, or run it without the plugin)
* Fixed various unfriendly error messages
* Fixed timezone of cross-posts
* Improved BBCode & magic URL checking, stopped double-pass of make clickable
* Fixed errors with user blogs list, and improved login_userinfo
* Fixed username incorrect when editing cross-posted posts
* Fixed integration error message after updating password or username under some circumstances
* ... and several other minor bug fixes

For previous changes, please view the full package at wp-united.com.

== Upgrade Notice ==

= 0.9.0.2 =
This version fixes a few minor bugs reported just after release of v0.9. You can update automatically or by simply copying over the WordPress plugin; You do not need to update the phpBB side.

= 0.9.0.3 =
This version improves the user mapper and addresses a few minor reported bugs. You can update automatically or by simply copying over the WordPress plugin; You do not need to update the phpBB side.

= 0.9.1.0 =
This version fixes a number of bugs with template integration and user integration. You should update as soon as possible. You will need to upgrade the phpBB portion in addition to the WordPress plugin by following the instructions in the contrib/.../upgrade.xml file.

= 0.9.1.2 =
This version adds a missing file from v0.9.1.0, please update to avoid errors

= 0.9.1.3 =
This version fixes a bug in the user mapper, please update.

= 0.9.1.4 =
This version fixes a few important bugs in the user mapper, please update ASAP.

= 0.9.1.5 =
This version fixes a few bugs associated with template integration and makes connecting and updating settings more reliable. Please update. If updating from v0.9.1.x, you only need to update the WordPress plugin portion of WP-United.

= 0.9.1.6 =
This version fixes a few minor bugs associated with profile updates and widgets, and improves compatibility with caching plugins. If updating from v0.9.1.x, you only need to update the WordPress plugin portion of WP-United.

= 0.9.2.0 =
This version fixes a lot of minor bugs and also makes many improvements to cross-posting. Please update. You need to update both the phpBB and WordPress side of WP-United. For the phpBB side, you only need to copy over the updated files.

= 0.9.2.1 =
This version fixes Some bugs with the non-english, localised versions of WP-United, and adds a new widget.

= 0.9.2.2 =
This version fixes a missing phpBB permission if you updated to v0.9.2.1 directly. It also improves the template integrator. 

= 0.9.2.3 =
This version fixes a problem with handling errors from other plugins in v0.9.2.2. Please update.

= 0.9.2.4 =
This version significantly improves the style fixer's handling of nested stylesheets, and fixes a number of minor bugs. Please update. You need to update both the phpBB and WordPress side of WP-United. For the phpBB side, you only need to copy over the updated files from the phpbb-forum/root folder in the download package to your forum root.

= 0.9.2.7 =
This version fixes a template integration bug with WordPress > 3.9. Development has been paused for a long time and there are other bugs to fix -- please watch this space. There is no need to upgrade the phpBB side if you are updating from v0.9.2.4.

= 0.9.2.8 =
This version fixes some outstanding JavaScript bugs in the settings panels.
