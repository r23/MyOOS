=== Shariff Wrapper ===
Contributors: 3UU, starguide
Tags: Facebook, VKontakte, VK, GooglePlus, Twitter, sharebutton, sharing, privacy, social, whatsapp
Requires at least: 3.0.1
Tested up to: 4.4
Stable tag: trunk
License: MIT
License URI: http://opensource.org/licenses/MIT
Donate link: http://folge.link/?bitcoin=1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP

This is a wrapper to Shariff. It enables shares with Twitter, Facebook ... on posts, pages and themes with no harm for visitors privacy.

== Description ==

The "original" share buttons send data of your visitors to the social
network sites, even if they do not click on a share button. The
German computer magazin CT has developed "Shariff" `(/ˈʃɛɹɪf/)` that 
fullfills the strict data protection laws in Germany. This plugin is a 
wrapper to this software. It allows you to use it within your posts 
with the shorttag `[shariff]` and provides all design options of the 
original code.

For more informations about the Shariff software check out the 
[GitHub project](https://github.com/heiseonline/shariff) and
read about the project itself [c’t information page](http://ct.de/shariff) (in German).

You can use this plugin on posts, pages, the main page, product sites and 
as a widget.

== Installation ==

1. Upload everything to the `/wp-content/plugins/` directory
2. Activate the plugin using the plugins menu in WordPress
3. Use <code>[shariff]</code> anywhere in your post and/or use the admin menu. 

To enable it for all posts please check the options in the admin menu.

== Screenshots ==

1. Differently styled share buttons (all small).
2. Basic options.
3. Design options.
4. Advanced options.
5. Mail form options.
6. Help section.
7. Manual shorttag with options in a post.
8. Widget menu. Shorttag works like in posts.

== Frequently Asked Questions ==

= Q: Can I use it in my theme? =
A: Yes. 
`<?=do_shortcode('[shariff backend="on"]')?>` 
Backend="on" is an example. You can use all options of the shorttag, but first you should have a look at the widget.

= Q: How can I configure the widget? =
A: It uses the same options that have been configured on the plugin options page. However, you can put in a shorttag that overwrites the default options. It has the same format as you use in posts. Take a look at the help section of the plugin options page for more information.

= Q: Can I change the options on a single post? =
A: Yes. If the [shariff...] shortcut is found in a post, it has priority over all settings from the plugin options page.

= Q: Why are shares not listed? =
A: Shariff tries to protect the privacy of your visitors. In order to do this, the statistics have to be requested by your server, so social networks only see a request of your blog server and not from your visitor. However, we do not know, if you want this. Therefore it is not enabled by default.

= Q: How can I show the share counts? =
A: Enable it on the plugin options page in general or add `backend="on"` to the shariff shorttag in your post.

= Q: I still do not see share counts =
A: Please have a look at the status section of the plugin options page. It states wether share counts are enabled and if there is a problem with the cache directory. Please also keep in mind that the plugin has a minimum refresh time of 60 seconds.

= Q: Why can't I change the TTL to a smaller / bigger value? =
A: The time to live (TTL) value determines, if a share count of a post or page gets refreshed when someone visits this specific page / post of your blog. Too small values create too much useless traffic, too high values negate the goal of motivating visitors to also share a post. The value can be adjusted between 60 and 7200 seconds.

= Q: I get the Facebook API error message "request limit reached" =
A: Facebook has a rate limit of 600 requests per 600 seconds per IP address. Especially in shared hosting environments many domains share the same IP address and therefore the same limit. To avoid this you can try to raise the TTL value or provide a Facebook App ID and Secret. Google "facebook app id secret" will provide many guides on how to get these.

= Q: How can I change the position of all buttons =
A: Have a look at the alignment options in the admin menu or checkout the 
style option.

= Q: How can I change the design? =
A: Have a look at the parameters "theme", "orientation" and "buttonsize". They work mostly like the original code parameters that are explained at http://heiseonline.github.io/shariff/ Or you can have a look at the test page at http://shariff.3uu.net/shariff-sample-page-with-all-options to get an
overview. But please be warned: This is a test page! It is possible that you find features that are only provided in the development version. Use it only to get an impression of the design options.

= Q: How can I change the design of a single button? =
A: If you are a CSS guru please feel free to modify the css file. But of course this is a bad idea, because all changes will be destroyed with the next update! Instead take a look at the style attribute of the shorttag. If you put in any value it will create a DIV container with the ID "ShariffSC" around the buttons. If you are really a CSS guru you will know what does the magic from here on out. ;-)

= Q: I want the buttons to stay fixed while scrolling! =
A: No problem. Just use the style attribute to add some CSS to the shorttag. For example in a widget (adjust the width as needed):
`[shariff style="position:fixed;width:250px"]`
Of course you can use all other options in that shorttag as well. It also works with the CSS style option on the plugins design options page, if you really want this applied to all buttons on your page.

= Q: I want a horizontal line above my Shariff buttons! =
A: You can use the headline option on the design tab. For example, enter the following code to create a horizontal line and a headline:
`<hr style='margin:20px 0'><p>Please share this post:</p>`

= Q: I want a different or no headline in a single widget, post or page! =
A: Use the headline attribute to add or remove it. For example, you can use the following shorttag to remove a headline set on the plugins options page in a single widget:
`[shariff headline=""]`
Of course you can use all other options in that shorttag as well.

= Q: Can I add [shariff] on all posts? =
A: Yes, check out the plugin options page. 

= Q: But I want to hide it on a single post! =
A: Do you really know what you want? ;-) However, it is possible. Write anywhere in your post "hideshariff". It will be removed and Shariff will not be added. This will make it compatible with the other plugin "Shariff for Wordpress". You can also use "/hideshariff" to write "hideshariff" in your post.

= Q: What are the differences between these two plugins? =
A: One is developed by us, one by someone else. ;-) The main difference is that this plugin has a few more options and great support. :-) Neither of the plugins are "official" or directly developed by Heise.

= Q: Does it work with a CDN? =
A: Yes, but in order for the font-icons to be loaded you have to exclude 
`​{plugins_dir}/shariff/fonts/*
{plugins_dir}/shariff/shariff.min.local.css`
from it (e.g. for W3TC enter this at "Rejected Files").

= Q: But I want shariff.min.local.css on my CDN as well! =
A: In this case edit your themes style.css to include
`@font-face {
    font-family: "shariff3uu";
    font-style: normal;
    font-weight: 400;
    src: url("http://www.EXAMPLE.com/wp-content/plugins/shariff/fonts/shariff3uu.eot") format("embedded-opentype"),
         url("http://www.EXAMPLE.com/wp-content/plugins/shariff/fonts/shariff3uu.woff") format("woff"),
         url("http://www.EXAMPLE.com/wp-content/plugins/shariff/fonts/shariff3uu.ttf") format("truetype"),
         url("http://www.EXAMPLE.com/wp-content/plugins/shariff/fonts/shariff3uu.svg") format("svg");
}`
Be sure to replace www.EXAMPLE.com!

= Q: Pinterest does not show an image =
A: You can add media="http://wwww.example.com/yourImage.png"
within the [shariff...] shorttag or add it in on the plugin options page - of course with the link to your image.

= Q: Can I set a fixed URL to share? =
A: You can use the "url" parameter within the shortcode
`[shariff url="http://www.example.com/"]`
This is also available within widgets. However, it is not a good idea to manipulate the URI, because it could mislead your visitors. So you should only use it, if this is really needed and you do really know what you are doing. Therefore it is not available on the plugin options page in general. 

= Q: What is the differenz between the services `mailform` and `mailto´? =
A: mailform will provide an e-mail form on top of the post or page and mailto will draw a link with the mailto-protocol to open the default e-mail application of the client.

= Q: What happened to `mail`? =
A: mail was replaced with mailform to provide an easier way of distinguishing between the two types of mail buttons.

= Q: Can I disable the mail form completely? =
A: Yes, take a look at the Mail Form tab on the plugin options page.

= Q: What happened to the Twitter share counts and what is OpenShareCount? =
A: Please read: https://www.jplambeck.de/twitter-saveoursharecounts/

= EXPERIMENTAL FEATURES =

Features marked as "experimental" in the admin menu are experimental! This means: We think it is a good extension to our plugin that we would like to include in future versions, but we are not sure yet about the best solution that works for all or most people. So please feel free to use and test it and report back to us about it. Experimental features might be removed in an update, if it does not work out. So please pay close attention to our changelog!

= Q: What does the external server URL is good for? =
A: First of all: Usually you do not need it! The Plugin uses the WP host to get all the files, share counts and statistics. However, there are some good reasons to put the backend on another server:
- avoid requests from you WP server to other servers
- use a more powerfull server for the statistic
- use the original backend implementation of Heise or your own solution
- make your own backend available for more than one WP installation
But please have in mind that there are also some good reasons not to use external servers:
- at least one additional DNS lookup and an additional connection is needed
- you need an additional installation of WP and the plugin or have to create your own implementation of Shariff
- if it is not your host you will break the privacy protection of your visitors and defeat the purpose of Shariff
- we CAN NOT provide support for your own implementation
- some options of the admin menu (backend checks, statistic) will only work on the external server
- you have to use SHARIFF_FRONTENDS as an array with all your frontend domains to enable the backend or find your own solution

= Q: How does the parameter backend-url work with an external server URL set? =
A: It overrides the URL of the backend to use. So usually it makes no sense to use both.

= Q: How to configure the external backend? =
A: At your WP host fill in the URL of the backend server. You can add it with or without the protocol. So usually `//backend.example.com/wp-content/plugins/shariff/` will do the job. For the WP installation on the external server you have to create an array as a "constant" called SHARIFF_FRONTENDS to permit your WP installations on other domains to use it. Please have in mind that you have to fill in all subdomains you want to use! The domain must be the array key and the values can be empty.
`static $SHARIFF_FRONTENDS =
  array('www.example.com'=>'',
        'example.com'=>'',
        'blog.example.com'=>'');`
Please also make sure to set the Access-Control-Allow-Origin header right.

= KNOWN BUGS =

These are bugs or unexpected glitches that we know of, but that do not
have an impact on the majority of users, are not security relevant and will perhaps be
fixed in the future - if we have time to spend or you provide us with a lot of "K&#xF6;lsch" ;-)

- If the first post on the start page is password protected and Shariff is
  disabled on protected posts, a widget at the end of the loop will not be
  rendered.

== Changelog ==

= 3.4.1 =
- changed diaspora share url to official one
- fix css of rss button when using round theme in widget
- fix accessibility of share button text 
- added rssfeed option to help section
- update to Heise version 1.23.0

= 3.4.0 =
- new service rss
- minor bug fixes
- update to Heise version 1.22.0

= 3.3.3 =
- fix anonymous function request for PHP < version 5.3

= 3.3.2 =
- improve/extend handling of custom post types
- fix Facebook ID call

= 3.3.1 =
- fix ttl setting on statistic tab
- reduce timeout for post requests to five seconds

= 3.3.0 =
- new option to use an external host for Shariff and the share count backend
- new share count service OpenShareCount.com for Twitter
- new settings tab "Statistic" for all options regarding the share counts
- new settings tab "Status" for all system checks to increase performance
- new design preview button without Twitter
- fix counter VK on design round
- fix Facebook total_count again
- fix double Twitter share windows under certain conditions
- reactivate Flattr counts, since they fixed their API
- purge Shariff transients on update, deactivate and uninstall
- code cleanup
- add vk to help section
- add a known bugs section to the readme

= 3.2.0 =
- new service VK
- new share count service VK
- new dynamic cache lifespan (ttl) based on post / page age (last modified)
- new option to disable individual services (only share counts)
- fix Facebook share counts now use total_counts again
- fix search for custom WP locations
- backend optimization
- temporarily disabled the Flattr counts (statistic) due to ongoing problems of the Flattr API
- fix use of wp_title() is/was deprecated in WP4.4

= 3.1.3 =
- fix ajax call when a custom permalink structure is used

= 3.1.2 =
- fix Facebook ID on 32-bit systems

= 3.1.1 =
- make admin help compatible to the new backend

= 3.1.0 =
- new option to add buttons before/after the excerpt
- new service Threema (thanks to medienverbinder)
- new service Diaspora (thanks to craiq)
- new service AddThis (thanks to bozana)
- new share count service AddThis (thanks to bozana)
- new service PayPal.Me
- new google icon
- fix title tag usage in some cases
- fix rel to data-rel popup
- fix round buttons in certain themes
- fix Flattr API to fetch counts again
- workaround to fix the wrong JSON answer of xing API
- up to date with Heise code version 1.21.0 2015-11-06

= 3.0.0 =
- new WP specific statistics backend for share counts
- new SHARIFF_WP_ROOT_PATH constant for custom wp locations
- automatic search for custom WP locations (thanks to hirasso)
- fix timeout issues and a lot of other backend issues
- deprecated Heise shariff-backend-php
- deprecated ZendFramework
- deprecated shariff3uu_cache directory
- deprecated SHARIFF_BACKEND_TMPDIR constant

= 2.4.3 =
- fix proxy settings
- fix PHP error notice caused by a race condition around concurrent requests in Zend_Cache
- fix PHP notice and error in backend on multisite

= 2.4.2 =
- fix lang attribute again
- fix update notice

= 2.4.1 =
- fix lang attribute
- nicer support hints about GD lib
- cleanup readme.txt

= 2.4.0 =
- ensure compatibility to WordPress 4.3
- new service Tumblr
- new service Patreon
- new service PayPal
- new service Bitcoin
- new supporting bbpress
- new using proxy settings from wp_config (thanks to Shogathu)
- fix automatic button language
- fix button language for Facebook
- fix problems with plugin "Hide Title"
- fix backend (statistic) for multisite environments
- fix backend (statistic) if WP_DEBUG is set to true
- fix language info in help section
- update to Heise version 1.16.0
- remove unnesseray guzzle docs

= 2.3.4 =
- add Italian language to the mailform

= 2.3.3 =
- fix Pinterest button, if pinit.js is present
- small css fixes

= 2.3.2 =
- add French (thanks Charlotte) and Italian (thanks Pier) translations
- improve screen reader compatibility
- fix: prefill mail_comment in case of an error
- fix: do not send more than 1 email as CC. Use a new mail for all recipients.
- fix: fallback to English at the email form only if language is not supported by this plugin
- cleanup mf_wait + extend time to wait of robots blocker to 2 sec 

= 2.3.1 =
- fix facebook api (app id & secret)
- fix CSS mailform label

= 2.3.0 =
- redesing of the plugins options page
- mail form improved (a lot)
- split mail into mailform and mailto (check your manual shorttags!)
- new backend status section
- new option to use Facebook Graph API ID in case of rate limit problems
- new option to stretch the buttons horizontally
- new option to add a headline above the Shariff buttons
- many new button languages
- new default cache directory
- fix creation of default cache directory in case it is not month / year based
- fix url-shorttag-option in widget
- fix widget mailform link, if pressed twice
- fix widget mailform on blog page
- fix responsive flow of buttons in IE
- fix Twitter: prevent double encoding with text longer than 120 chars
- update shariff backend to 1.5.0 (Heise)
- update shariff JS to 1.14.0 (Heise)
- many more minor improvements
- code cleanup

= 2.2.4 =
- security fix

= 2.2.3 =
- extend blocking of shariff buttons within password protected posts

= 2.2.2 =
- allow email functionality only if service email is configured within the
  admin menu as common service for all posts

= 2.2.1 =
- "fix" fallback to old twitter api again

= 2.2.0 =
- add option to hide Shariff on password protected posts
- tested up to WP 4.2.2
- "fix" fallback to old twitter api if another twitter script is found in order to avoid opening the share window twice
- share text of the mailto link now is "email"
- fix typo and cleanup code

= 2.1.2 =
- fix to make it work with PHP < 5.3 (you should really update your PHP version or change your hoster)

= 2.1.1 =
- change code because of a error with some PHP versions

= 2.1.0 =
- replace sender name if a name is provided with the mail form or set in admin menu
- add option to append the post content to the mail
- add mail header "Precedence: bulk" to avoid answers of autoresponder
- fix: rename a function to avoid problems with other plugins
- improve css

= 2.0.2 =
- fix: mail URLs must be a real link and not url-encoded
- hotfix: mail form disabled if not on single post. Avoid the 
  self destruction by the DOS-checker ;-)
- cleanup Shariff JS code (mailURL no longer needed)

= 2.0.1 =
- fix email form method POST

= 2.0.0 =
- changes to stay compatible to Heise implementation
- remove obsolet SHARIFF_ALL_POSTS
- fix some small css attributes (size)
- code clean up

= 1.9.9 =
- fix widget bug (wrong share links)

= 1.9.8 =
- add headers to avoid caching of backend data
- tested with WP 4.2 beta
- add option to use on custom pages (e.g. WooCommerce)
- better handling of pinterest media attribute
- bugfix: SHARIFF_BACKEND_TMPDIR in backend
- improve uninstal of cache dir (todo: change to a better named dir)
- add option to use smaller size buttons
- fix again target of the mailto-link
- cleanup code

= 1.9.7 =
- roll back to stable

= 1.9.6 =
- now really go back to 1st block in loop at WMPU

= 1.9.5 =
- nu abba: missing version update 

= 1.9.4 =
- fix update bug on WPMS

= 1.9.3 =
- add missing backend files. Last change for this Sunday ;-)

= 1.9.2 =
- fix stupid bug with the update file :-( Sorry!
- fix initialisation of REMOTEHOSTS 

= 1.9.1 =
- merge with original Shariff JS/CSS code version 1.9.3
- CSS theme default like Heise default again + "add" theme color
- fix the theme "white"
- backend now up to date
- disable WPDebug in backend config
- improve uninstall (options shariff3UU, shariff3UUversion,
  widget_shariff) and compatible with multisite installations
- improve deactivation

= 1.9 =
- add Flattr
- improve version control
- update frensh translations
- use configuration from admin menu for blank shariff shortcodes
- own (much smaller) fonts

= 1.8.1 =
- remove the relativ network-path from service declarations (pinterest,
  reddit, stumbleupon, xing) because it really makes no sense to change 
  the protocol within a popup of a secure target to unsecure connections
  depending on the protocol the page is using
- change name of jQuery object to avoid conflicts with other plugins/themes

= 1.8 =
- add options to place Shariff (right/left/center)
- fix: migration check
- css optimized
- use the WP bundled jQuery now (save about 80% bandwidth :-)

= 1.7.1 =
- optimize css (thanks again to @jplambeck)
- code cleanup (No more warnings in debug mode. Perhaps ;-)
- sanitize admin input (thanks again to @jplambeck)
- set the title attribute to overwrite get_the_title() now supported
- fix: check SHARIFF_BACKEND_TMPDIR
- add uninstall script

= 1.7 =
- CHANGES: if no attributes are configured within a shorttag first try to
  use the option from admin page. However if there are no services
  configured use the old defaults of Heise to make it backward compatible
- add the new service attribut `mailto` to prepare getting the original
  behavior of the Heise code that provide a email form with `mail`
- add option to put Shariff on overview page too
- add internal version tracker to enable better migration options in the
  future
- optimized css for the info attribute, added priority to the title
  attribute over DC.title attribute (thanks again to @jplambeck )

= 1.6.1 =
- fix: again enable WhatsUp on mobile now also works with Mozilla. Sorry
  this has not been become part of the main branche. Therefor it was lost 
  with the last update :-(
- added .shariff span { color: inherit; } (thanks again to @jplambeck )

= 1.6. =
- adopted new responsive css code (thanks to @jplambeck )
- update included fa-fonts
- fix: descrition "printer" 
- fix: use WP_CONTENT_URL in admin menu

= 1.5.4 =
- remove alternativ css with links to external hosters. If do you really
  want enable breaking privacy plz feel free to code you own css 

= 1.5.3 =
- hide counter within the theme round 

= 1.5.2 =
- default backend temp dir now uses wp content dir 
- updated original shariff JS code

= 1.5.1 =
- fix: constant had have a wrong declaration check

= 1.5.0 =
- add option "url" to set a fixed URI for all sites (only in shorttag and
  widgets) because usually it is not a good idea to manipulate this
- fix: do not show error in elseif (/tmp check in admin menu) 

= 1.4.4 =
- add option to force frensh and spanish buttons
- clean up theme selection

= 1.4.3 =
- look like wp_enqueue_script has problems with the shariff js code. Now own
  script link at the end of the site. Should also improve performance ;-)

= 1.4.2 =
- fix: add the attribute data-title that is needed by the shariff on some
  themes to render it above the other div containers. 
- only long PHP-Tags because auf problems with WAMPs
- some code clean up. Hopefully it will become more robust on WAMP systems.

= 1.4.1 =
- fixed stupid typo with the SHARIFF_BACKEND_TMP constant

= 1.4.0 =
- add a DIV container to use positioning with CSS
- remove long PHP-tags that cause parse problem on Windows

= 1.3.0 =
- clean up the code
- add backend options (TTL and temp dir)

= 1.2.7 =
- fixed: enable WhatsUp on mobile now also works with Mozilla 
- print button does not open a new window
- removed min.js make it more readable for own local changes

= 1.2.6 =
- add print button
- add default image for pinterest to avoid broken design and giuve a hint

= 1.2.5 =
- hotfix for pinterest (see FAQ) 

= 1.2.4 =
- bugfix: widget does not work if SHARIFF_ALL_POSTS is set but not enabled
  in the admin menu (Please remember, that SHARIFF_ALL_POSTS will be 
  removed with next major version)
- add option to add shariff at the begin of a post
- merge with new original backend for counters
- add spanish language on buttons; hide whatsup on mobile devices
  (merge with yanniks code)
- add reddit
- add stumbleupon

= 1.2.3 =
- add round theme to the admin menu

= 1.2.2 =
- tested with WP 4.1
- added french language for buttons
- added theme "round" (round buttons without text but with fixed width)

= 1.2.1 =
- typos

= 1.2 =
- add widget support

= create a Stable1.0 tag =
- no new funtionality to this tag. Only bugfixes!

= 1.1.1 =
- add french language for the admin menu (thanks Celine ;-)
- fix backend problem on shared hosting with no writeable tmp dir

= 1.1 =
- add whatsapp|pinterest|linkedin|xing
- include latest upstream changes (fix mail etc.)
- add old default selection of services to make it backward compatible

= 1.0.2 =
- add German translation to the admin menu (Admin-Menue in Deutsch)
- code cleanup

= 1.0.1 =
- add PHP version check (5.4 is needed by the backend option)

= 1.0 =
- add admin menu page
- disable the default add on a post if a special formed tag was found
- add support for the theme attribute

= 0.4 =
- Include latest upstream changes
- use get_permalink() to set the parameter data-url

= 0.3 =
- add support for "hideshariff" 
- add screenshots

= 0.2 =
- removed the private update server and changed test domain 

= 0.1 = 
- initial release
