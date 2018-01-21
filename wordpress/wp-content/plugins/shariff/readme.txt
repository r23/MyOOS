=== Shariff Wrapper ===
Contributors: 3UU, starguide
Tags: Shariff, Facebook, Twitter, VKontakte, VK, GooglePlus, WhatsApp, share buttons, sharing, privacy, social
Requires at least: 4.4
Tested up to: 4.6
Stable tag: 4.3.0
License: MIT
License URI: http://opensource.org/licenses/mit
Donate link: http://folge.link/?bitcoin=1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP

The Shariff Wrapper provides share buttons that respect the privacy of your visitors and are compliant to German data protection laws.

== Description ==

The "original" share buttons automatically transmit data of your visitors to the social network sites as soon as they visit your website. They do not need to click on a share button for this and therefore have no choice, if they want their data to be send. The German computer magazin CT has developed "Shariff" `(/ˈʃɛɹɪf/)` that fullfills the strict data protection laws in Germany. This plugin adapts the Shariff concept and provides an easy to use solution for WordPress. We currently support 22 services like Facebook, Twitter, GooglePlus, Xing, LinkedIn and many more.

For more informations about the Shariff project check out the original [GitHub project](https://github.com/heiseonline/shariff) and read about the project itself [c’t information page](http://ct.de/shariff) (in German).

You can automatically add share buttons to posts, pages, the main blog page, product sites and many more as well as use it as a widget or add the shortcode `[shariff]` manually to your pages or themes.

== Installation ==

1. Upload everything to the `/wp-content/plugins/` directory
2. Activate the plugin using the plugins menu in WordPress
3. Use <code>[shariff]</code> anywhere in your post and/or use the Shariff settings menu. 

To enable it for all posts please check the options in the plugin settings.

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

= Q: Can I use the Shariff buttons in my theme? =
A: Yes.
`<?=do_shortcode('[shariff]')?>` 
You can use all options of the shorttag as described on the help tab in the plugin settings.

= Q: Can I use the total amount of shares in my theme? =
A: Yes. You can use
`<?=do_shortcode('[shariff services="totalnumber"]')?>` 
to simply output the total amount of shares for a post in the loop. It will return the number itself wrapped in a `<span class="shariff-totalnumber"></span>` in order for the shariff.js to update the count. Also only cached data is used, in order to not slow down your site.

= Q: Is there an action hook to use the share counts every time they get updated? =
A: Yes. You can use
`function your_awesome_function( $share_counts ) {
   // $share_counts is an array including all enabled services, the timestamp of the update and the url of the post.
   // do stuff
} 
add_action( 'shariff_share_counts', 'your_awesome_function' );` 
WARNING: This hook will get called A LOT. So be sure you know what you are doing.

= Q: How can I configure the widget? =
A: It uses the same options that have been configured on the plugin options page. However, you can put in a shorttag that overwrites the default options. It has the same format as you use in posts. Take a look at the help section of the plugin options page for more information.

= Q: Can I change the options on a single post? =
A: Yes. You can change all options using the shorttag in the Shariff meta box on the right side of the post edit screen.

= Q: Why are shares not listed? =
A: Shariff tries to protect the privacy of your visitors. In order to do this, the statistics have to be requested by your server, so social networks only see a request of your server and not from your visitor. However, we do not know, if you want this. Therefore it is not enabled by default.

= Q: How can I show the share counts? =
A: Enable it on the plugin options page in general or add `backend="on"` to the shariff shorttag in your post.

= Q: I still do not see share counts =
A: Please have a look at the status tab on the plugin options page. It states wether share counts are enabled and if there is a problem with a service. Please also keep in mind that the plugin has a minimum refresh time of 60 seconds and that each service has their own cache as well.

= Q: Why can't I change the TTL to a smaller / bigger value? =
A: The time to live (TTL) value determines, if a share count of a post or page gets refreshed when someone visits this specific page / post of your blog. Too small values create too much useless traffic, too high values negate the goal of motivating visitors to also share a post. The value can be adjusted between 60 and 7200 seconds. Keep in mind, the actual lifespan depends on the age of the post as well.

= Q: I get the Facebook API error message "request limit reached"! =
A: Facebook has a rate limit of 600 requests per 600 seconds per IP address. Especially in shared hosting environments many domains share the same IP address and therefore the same limit. To avoid this you can try to raise the TTL value or provide a Facebook App ID and Secret. Google "facebook app id secret" will provide many guides on how to get these.

= Q: How can I change the position of all buttons? =
A: Have a look at the alignment options in the admin menu or checkout the 
style option.

= Q: How can I change the design? =
A: Have a look at the parameters "theme", "orientation" and "buttonsize". They work mostly like the original code parameters that are explained at http://heiseonline.github.io/shariff/ Or you can have a look at the test page at http://shariff.3uu.net/shariff-sample-page-with-all-options to get an
overview. But please be warned: This is a test page! It is possible that you find features that are only provided in the development version. Use it only to get an impression of the design options.

= Q: How can I change the design of a single button? =
A: If you are a CSS guru please feel free to modify the css file. But of course this is a bad idea, because all changes will be destroyed with the next update! Instead take a look at the style and class attribute of the shorttag. If you put in any value it will create a DIV container with the ID "ShariffSC" around the buttons. If you are really a CSS guru you will know what does the magic from here on out. ;-)

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
A: Yes, check out the plugin options. 

= Q: But I want to hide it on a single post! =
A: Do you really know what you want? ;-) However, it is possible. Write anywhere in your post "hideshariff". It will be removed and Shariff will not be added. You can also use "/hideshariff" to write "hideshariff" in your post. You might also want to take a look at the Shariff meta box on the right side of your post edit screen.

= Q: What are the differences between the two Shariff plugins? =
A: One is developed by us, one by someone else. ;-) The main difference is that this plugin has a few more options and a great support. :-) Neither of the plugins are "official" or directly developed by Heise.

= Q: Does it work with a CDN? =
A: Yes.

= Q: Pinterest does not show an image! =
A: You can add media="http://wwww.example.com/yourImage.png"
within the [shariff] shorttag or add it in on the plugin options page - of course with the link to your image.

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

= Q: The buttons are not correctly beeing shown on my custom theme! =
A: Please make sure that wp_footer(); has been added to your theme. For more information please visit: https://codex.wordpress.org/Function_Reference/wp_footer

= EXPERIMENTAL FEATURES =

Features marked as "experimental" in the admin menu are experimental! This means: We think it is a good extension to our plugin that we would like to include in a future version, but we are not sure yet about the best solution that works for all or most people. So please feel free to use and test it and report back to us about it. Experimental features might be removed in an update, if it does not work out. So please pay close attention to our changelog!

= Q: What is the external API feature? =
A: First of all: Usually you do not need it! The plugin requests all share counts itself. However, there are some reasons to put the backend on another server:
- avoid requests from you WP server to all the social networks
- use a more powerful server for the statistic
- use the original backend implementation of Heise or your own solution
- make your own backend available for more than one WP installation
But please have in mind that there are also some good reasons not to use external servers:
- you need an additional installation of WP and the plugin or have to create your own implementation of a Shariff backend
- some plugin settings (backend checks, statistic, etc.) will only work on the external server
- you have to use SHARIFF_FRONTENDS as an array with all your frontend domains to enable the backend or find your own solution
- we CANNOT provide support for your own implementation

= Q: How can I configure the external API? =
A: In the statistic settings fill in the URL to the API of the external server. For the WordPress installation on the external server you have to create a "constant" called SHARIFF_FRONTENDS to permit other domains to use it. Please have in mind that you have to fill in all subdomains you want to use! The domains must be defined like this:
`define( 'SHARIFF_FRONTENDS', 'example.com|www.example.com|blog.example.com|another-domain.com' );`

= Q: What does "Request external API directly." means? =
A: By default, the browser request the share counts from the server your site is running on. If you have entered an external API your server will then request the counts from this external API instead of fetching them itself. Therefore, the external server will only see the IP from your server and not the one from your visitors. If you check this option, the browser of your visitors will instead directly request the share counts from the external API and therefore reveal their IP address to them. This might be faster, but it is less secure. Please also make sure to set the Access-Control-Allow-Origin header right. If your site is available using https, your external API will need to be reached by https as well. Otherwise the request will get blocked for security reasons. All options and features (e.g. the ranking tab) regarding the statistic will only work on the external server.

= KNOWN BUGS =

These are bugs or unexpected glitches that we know of, but that do not
have an impact on the majority of users, are not security relevant and will perhaps be
fixed in the future - if we have time to spend or you provide us with a lot of "K&#xF6;lsch" ;-)

- If the first post on the start page is password protected and Shariff is
  disabled on protected posts, a widget at the end of the loop will not be
  rendered.

== Changelog ==

= 4.3.0 =
- new service Odnoklassniki (thanks to rockhit)
- new meta box allows for individual settings per post or page
- new option to hide share counts that are zero
- new option to disable dynamic cache lifespan (not recommended)
- new option to set the button size to small, medium or large
- new option to add a custom class to the container around Shariff
- new option to open links in a popup (thanks to jackennils)
- new option to use NewShareCount instead of OpenShareCount (Twitter)
- added timestamp variable to be accessible via shortcode
- fixed post timestamp for caching under certain conditions
- fixed Facebook share count error for never crawled pages
- fixed empty tab after sharing on certain mobile devices
- fixed custom title attribute (thanks to kschlager)
- updated Flattr user id for the future (thanks to poetaster)
- reduced changelog on wordpress.org (thanks to timse201)
- minor css improvements
- updated help section

= 4.2.1 =
- fixed WhatsApp button on Android when using Chrome
- fixed Shariff being added to RSS feeds under certain conditions
- updated to latest Facebook Graph API for share count requests

= 4.2.0 =
- new option to set the rate limit for sending mails using the mail form
- added home url as fallback for share count requests
- added further anti-spam prevention mechanics
- added noopener and noreferrer to share links
- fixed double encoding of share count request links
- updated media uploader request for translation
- updated handling of admin notices following WordPress core
- tested and optimized for WordPress 4.6

= 4.1.2 =
- new fallback for share count requests in case pretty permalinks are disabled
- new filter shariff3UU_render_atts to change options on the fly (thx Ov3rfly)
- fixed share title in cases with html encoded characters
- fixed double counting on ranking tab under certain conditions
- fixed php info notice in admin notices

= 4.1.1 =
- new option to disable the Shariff buttons outside of the main loop
- fixed Facebook App ID request
- minor css fix

= 4.1.0 =
- new design option to set a custom button color for all buttons
- new design option to set a border radius for the round theme (up to a square)
- new design option to hide all buttons until the page is fully loaded
- new mailform option to use a html anchor (again)
- new statistic option to fill the cache automatically
- new statistic option to set the amount of posts for the ranking tab
- new statistic option to use share counts with PHP < 5.4
- fixed preventing buttons from beeing added to excerpts under certain conditions
- fixed urlencoding of share count requests
- improved handling of wrong or mistyped service entries
- minor bug fixes

= 4.0.8 =
- new workaround for sites running PHP 5.2 and older

= 4.0.7 =
- new option for WordPress installations with REST API not reachable in root

= 4.0.6 =
- fixed an error in combination with bbpress
- fixed ab error on very old PHP versions
- fixed ranking tab
- minor css improvements

= 4.0.5 =
- fixed mail form link
- fixed xmlns for w3c

= 4.0.4 =
- removed some remaining wrong text domains for translations
- minor css fixes

= 4.0.3 =
- fixed mobile services not showing on certain tablets
- fixed type error on totalnumber when cache is empty
- fixed share count requests when WordPress is installed in a subdirectory
- fixed urlencoding of share url, title and media
- added width and height to SVGs to prevent large initial icons prior to css
- new classes shariff-buttons and shariff-link added
- removed local translation files due to switching to wordpress.org language packs
- minor css resets added

= 4.0.2 =
- added minor css resets to prevent influence of theme css
- fixed LinkedIn share link

= 4.0.1 =
- prevent php warning messages on unsuccessful includes while WP_DEBUG is active
- changed text domain to match plugin slug

= 4.0.0 =
- complete overhaul of the plugin core
- buttons now also work without JavaScript
- icon font has been removed and replaced with SVGs
- share counts now use the WP REST API
- share counts now always show the last cached counts prior to updating them
- fixed duplicated share count requests
- new ranking tab shows the shares of your last 100 posts
- new service pocket
- new option to show the total amount of shares in the headline with %total
- new option to use the total amount of shares in your theme (see FAQ)
- new action hook shariff_share_counts (see FAQ)
- new option to change the priority of the shortcode filter
- new support for selective refresh introduced in WP 4.5
- new external API feature replaces the external host option (experimental, see FAQ)
- new support for SCRIPT_DEBUG
- css and js files are now only loaded on pages with Shariff buttons
- improved compatibility with plugin Autoptimize (force scripts in head)
- improved compatibility with multiple caching plugins
- all shortcodes are now being stripped from the mail message body
- fixed potential double sending of mails
- removed all jQuery dependencies
- requires at least WordPress 4.4 (only for share counts)
- we no longer support IE 8 (if it ever worked)
- updated status tab
- updated help section
- minor bug fixes
- code cleanup

The complete changelog can be found here: https://plugins.svn.wordpress.org/shariff/trunk/changelog.txt
