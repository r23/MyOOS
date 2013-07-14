=== WordPress Firewall 2 ===
Contributors: pavy, seoegghead
Donate link: http://matthewpavkov.com/wordpress-plugins/
Tags: security, firewall
Requires at least: 2.6.1
Tested up to: 3.0.1
Stable tag: 1.3

This WordPress plugin monitors web requests to identify and stop the most obvious attacks.

== Description ==

This is an updated version of the popular *WordPress Firewall* plugin, with fixes for all known bugs and a few new features!

This WordPress plugin investigates web requests with simple, WordPress-specific heuristics, to identify and stop the most obvious attacks. There are a few powerful, generic modules that do this; but they're not always installed on web servers, and usually difficult to configure.

This plugin intelligently whitelists and blacklists pathological-looking phrases, based on which field they appear within, in a page request (unknown/numeric parameters vs. known post bodies, comment bodies, etc.). Its purpose is not to replace prompt and responsible upgrading, but rather to mitigate 0-day attacks and let bloggers sleep better at night.

Originally developed by SEO Egghead and released as *WordPress Firewall*.

== Installation ==

1. Download the plugin.
2. Unzip the file that you downloaded.
3. Upload the contained program "wordpress-firewall-2.php" to your "wp-content/plugins/" folder.

== Frequently Asked Questions ==

= Upgrading from WordPress Firewall v1.25 =

1. Deactivate the plugin WordPress Firewall v1.25.
2. Delete the plugin from your plugins folder.
3. Install WordPress Firewall 2 (see installation instructions).
4. Your previous settings will be restored and used.

= What does this thing actually do? =

Lots of stuff - here's the list:

* Detect, intecept, and log suspicious-looking parameters — and prevent them compromising WordPress.
* Also protect most WordPress plugins from the same attacks.
* Respond with an innocuous-looking 404, or a home page redirect.
* Optionally send an email to you with a useful dump of information upon blocking a potential attack.
* Turn on or off directory traversal attack detection.
* Turn on or off SQL injection attack detection.
* Turn on or off WordPress-specific SQL injection attack detection.
* Turn on or off blocking executable file uploads.
* Turn on or off remote arbitrary code injection detection.
* Add whitelisted IPs.
* Add additional whitelisted pages and/or fields to allow pages/plugins/etc to get through when desirable.
* Optionally configure as the first plugin to load for maximum security.

== Screenshots ==

1. Full screenshot of the plugin.

== Changelog ==

= 1.3 =
* Fixed known bugs
* Added plain text email option
* IP of plugin activator added by default
* Other small, miscellaneous updates.
* Now maintained by Matthew Pavkov

= 1.25 =
* First release.
* Developed by SEO Egghead

= 0.5 =
* Unreleased.

== Upgrade Notice ==

= 1.3 =
The bugs reported for the original plugin have been fixed, a few modest features have been added.