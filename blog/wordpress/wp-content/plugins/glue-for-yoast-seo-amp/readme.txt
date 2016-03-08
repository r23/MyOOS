=== Glue for Yoast SEO & AMP ===
Contributors: joostdevalk
Tags: AMP, SEO
Requires at least: 4.2
Tested up to: 4.4
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin makes sure the default WordPress AMP plugin uses the proper Yoast SEO metadata.

== Description ==

This plugin makes sure the default [WordPress AMP plugin](https://wordpress.org/plugins/amp/) uses the proper [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/) metadata. Without this glue plugin things like canonical might go wrong.

This replaces the following values with Yoast SEO data, all in the JSON+LD data on posts:

* Organization name (uses the blog name by default, now replaced by the Company name from the SEO General settings, Your info tab)
* Organization logo (uses the blog icon by default, now replaced by the Company logo from the SEO General settings, Your info tab)
* Post canonical (replaced by the Yoast SEO canonical, usually the same but makes sure its consistent when changed through Yoast SEO)
* Post image (uses the featured image by default, replaced by the Facebook image if one is set)
* Post description (added from the meta description, if set, by default the WordPress AMP plugin doesn't add one)

> *Bugs? Ideas?*<br>
> File them [on the GitHub repo for this plugin](https://github.com/Yoast/yoastseo-amp) please!

== Frequently Asked Questions ==

= How do I test whether it all works? =

Use Google's [Structured Data testing tool](https://developers.google.com/structured-data/testing-tool/) and enter the AMP URL of one of your posts.

= How do I change the Organization name or logo? =

Go to SEO -> General -> Your Info tab.

== Screenshots ==

1. Screenshot of the structured data testing tool testing an AMP page with the Yoast SEO plugin and this glue plugin.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/yoast-seo-amp` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. You're done.

== Changelog ==

= 0.1 =
* Initial version.
