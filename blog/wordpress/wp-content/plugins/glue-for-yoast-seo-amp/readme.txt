=== Glue for Yoast SEO & AMP ===
Contributors: joostdevalk
Tags: AMP, SEO
Requires at least: 4.2
Tested up to: 4.4
Stable tag: 0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin makes sure the default WordPress AMP plugin uses the proper Yoast SEO metadata.

== Description ==

This plugin makes sure the default [WordPress AMP plugin](https://wordpress.org/plugins/amp/) uses the proper [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/) metadata. Without this glue plugin things like canonical might go wrong.

To change your company name or logo, you now have to go to the SEO settings, General tab.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/yoast-seo-amp` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. You're done.

== Changelog ==

= 0.3 =
* Split the plugin into several classes.
* Added a settings page, found under SEO -> AMP
* This new settings page has:
    * A post types settings tab;
    * A design settings tab;
    * An analytics integration tab.
* Added sanitization functions that further clean up AMP output to make sure more pages pass validation.
* Added a default image (settable on the design tab) to use when a post has no image. This because the image in the JSON+LD output is required by Google.
* The plugin now automatically enables AMP GA tracking when GA by Yoast is enabled, but also allows you to add custom tracking.

= 0.1 =
* Initial version.
