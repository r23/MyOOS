=== WordPress SEO by Yoast ===
Contributors: joostdevalk
Donate link: http://yoast.com/
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: seo, SEO, google, meta, meta description, search engine optimization, xml sitemap, xml sitemaps, google sitemap, sitemap, sitemaps, robots meta, rss, rss footer, yahoo, bing, news sitemaps, XML News Sitemaps, WordPress SEO, WordPress SEO by Yoast, yoast, multisite, canonical, nofollow, noindex, keywords, meta keywords, description, webmaster tools, google webmaster tools, seo pack
Requires at least: 3.3
Tested up to: 3.6
Stable tag: 1.4.15

Improve your WordPress SEO: Write better content and have a fully optimized WordPress site using the WordPress SEO plugin by Yoast.

== Description ==

WordPress out of the box is already technically quite a good platform for SEO, this was true when I wrote my original [WordPress SEO](http://yoast.com/articles/wordpress-seo/) article in 2008 and it's still true today, but that doesn't mean you can't improve it further! This plugin is written from the ground up by [WordPress SEO consultant](http://yoast.com/hire-me/wordpress-seo-consultant/) and [WordPress developer](http://yoast.com/hire-me/wordpress-developer/) Joost de Valk to improve your site's SEO on *all* needed aspects. While this [WordPress SEO plugin](http://yoast.com/wordpress/seo/) goes the extra mile to take care of all the technical optimization, more on that below, it first and foremost helps you write better content.  WordPress SEO forces you to choose a focus keyword when you're writing your articles, and then makes sure you use that focus keyword everywhere.

= Write better content with WordPress SEO =
Using the snippet preview you can see a rendering of what your post or page will look like in the search results, whether your title is too long or too short and your meta description makes sense in the context of a search result. This way the plugin will help you not only increase rankings but also increase the click through for organic search results.

= Page Analysis =
The WordPress SEO plugins [Linkdex Page Analysis](http://yoast.com/content-seo-wordpress-linkdex/) functionality checks simple things you're bound to forget. It checks, for instance, if you have images in your post and whether they have an alt tag containing the focus keyword for that post. It also checks whether your posts are long enough, if you've written a meta description and if that meta description contains your focus keyword, if you've used any subheadings within your post, etc. etc.

The plugin also allows you to write meta titles and descriptions for all your category, tag and custom taxonomy archives, giving you the option to further optimize those pages.

Combined, this plugin makes sure that your content is the type of content search engines will love!

= Technical WordPress Search Engine Optimization =
While out of the box WordPress is pretty good for SEO, it needs some tweaks here and there. This WordPress SEO plugin guides you through some of the settings needed, for instance by reminding you to enable pretty permalinks. But it also goes beyond that, by automatically optimizing and inserting the meta tags and link elements that Google and other search engines like so much:

= Meta & Link Elements =
With the WordPress SEO plugin you can control which pages Google shows in its search results and which pages it doesn't show. By default, it will tell search engines to index all of your pages, including category and tag archives, but only show the first pages in the search results. It's not very useful for a user to end up on the third page of your "personal" category, right?

WordPress itself only shows canonical link elements on single pages, WordPress SEO makes it output canonical link elements everywhere. Google has recently announced they would also use `rel="next"` and `rel="prev"` link elements in the `head` section of your paginated archives, this plugin adds those automatically, see [this post](http://yoast.com/rel-next-prev-paginated-archives/ title="rel=next & rel=prev for paginated archives") for more info.

= XML Sitemaps =
WordPress SEO has the most advanced XML Sitemaps functionality in any WordPress plugin. Once you check the box, it automatically creates XML sitemaps and notifies Google & Bing of the sitemaps existence. These XML sitemaps include the images in your posts & pages too, so that your images may be found better in the search engines too.

These XML Sitemaps will even work on large sites, because of how they're created, using one index sitemap that links to sub-sitemaps for each 1,000 posts. They will also work with custom post types and custom taxonomies automatically, while giving you the option to remove those from the XML sitemap should you wish to.

Because of using [XSL stylesheets for these XML Sitemaps](http://yoast.com/xsl-stylesheet-xml-sitemap/), the XML sitemaps are easily readable for the human eye too, so you can spot things that shouldn't be in there.

= RSS Optimization =
Are you being outranked by scrapers? Instead of cursing at them, use them to your advantage! By automatically adding a link to your RSS feed pointing back to the original article, you're telling the search engine where they should be looking for the original. This way, the WordPress SEO plugin increases your own chance of ranking for your chosen keywords and gets rid of scrapers in one go!

= Breadcrumbs =
If your theme is compatible, and themes based on Genesis or by WooThemes for instance often are, you can use the built-in Breadcrumbs functionality. This allows you to create an easy navigation that is great for both users and search engines and will support the search engines in understanding the structure of your site.

Making your theme compatible isn't hard either, check [these instructions](http://yoast.com/wordpress/breadcrumbs/).

= Edit your .htaccess and robots.txt file =
Using the built-in file editor you can edit your WordPress blogs .htaccess and robots.txt file, giving you direct access to the two most powerful files, from an SEO perspective, in your WordPress install.

= Social Integration =
SEO and Social Media are heavily intertwined, that's why this plugin also comes with a Facebook OpenGraph implementation and will soon also support Google+ sharing tags.

= Multi-Site Compatible =
This WordPress SEO plugin, unlike some others, is fully Multi-Site compatible. The XML Sitemaps work fine in all setups and you even have the option, in the Network settings, to copy the settings from one blog to another, or make blogs default to the settings for a specific blog.

= Import & Export functionality =
If you have multiple blogs, setting up plugins like this one on all of them might seem like a daunting task. Except that it's not, because what you can do is simple: you set up the plugin once. You then export your settings and simply import them on all your other sites. It's that simple!

= Import functionality for other WordPress SEO plugins =
If you've used All In One SEO Pack or HeadSpace2 before using this plugin, you might want to import all your old titles and descriptions. You can do that easily using the built-in import functionality. There's also import functionality for some of the older Yoast plugins like Robots Meta and RSS footer.

Should you have a need to import from another SEO plugin or from a theme like Genesis or Thesis, you can use the [SEO Data Transporter](http://wordpress.org/extend/plugins/seo-data-transporter/) plugin, that'll easily convert your SEO meta data from and to a whole set of plugins like Platinum SEO, SEO Ultimate, Greg's High Performance SEO and themes like Headway, Hybrid, WooFramework, Catalyst etc.

Read [this migration guide](http://yoast.com/all-in-one-seo-pack-migration/) if you still have questions about migrating from another SEO plugin to WordPress SEO.

= WordPress SEO Plugin in your Language! =
Currently a huge translation project is underway, translating WordPress SEO in as much as 24 languages. So far, the translations for French and Dutch are complete, but we still need help on a lot of other languages, so if you're good at translating, please join us at [translate.yoast.com](http://translate.yoast.com).

= News SEO =
Be sure to also check out the [News SEO module](http://yoast.com/wordpress/seo/news-seo/) if you need Google News Sitemaps. It tightly integrates with WordPress SEO to give you the combined power of News Sitemaps and full Search Engine Optimization.

= Further Reading =
For more info, check out the following articles:

* [WordPress SEO - The definitive Guide by Yoast](http://yoast.com/articles/wordpress-seo/).
* Once you have great SEO, you'll need the [best WordPress Hosting](http://yoast.com/articles/wordpress-hosting/).
* The [WordPress SEO Plugin](http://yoast.com/wordpress/seo/) official homepage.
* Other [WordPress Plugins](http://yoast.com/wordpress/) by the same author.
* Follow Yoast on [Facebook](https://facebook.com/yoast) & [Twitter](http://twitter.com/yoast).

== Installation ==

1. Upload the `wordress-seo` folder to the `/wp-content/plugins/` directory
1. Activate the WordPress SEO plugin through the 'Plugins' menu in WordPress
1. Configure the plugin by going to the `SEO` menu that appears in your admin menu

== Frequently Asked Questions ==

You'll find the [FAQ on Yoast.com](http://yoast.com/wordpress/seo/faq/).

== Screenshots ==

1. The WordPress SEO plugin general meta box. You'll see this on edit post pages, for posts, pages and custom post types.
2. Some of the sites using this WordPress SEO plugin.
3. The WordPress SEO settings for a taxonomy.
4. The fully configurable XML sitemap for WordPress SEO.
5. Easily import SEO data from All In One SEO pack and HeadSpace2 SEO.
6. Example of the Page Analysis functionality.
7. The advanced section of the WordPress SEO meta box.

== Changelog ==

= 1.4.15 =

* Bugfixes
	* Fix the white XML sitemap errors caused by non-working XSL.
	* Fixed the errors in content analysis reporting an H2 was not found when it was really there.
	* Fix slug stopwords removal, props [amm350](https://github.com/amm350).
	* Fix PHP Notice logged when site has capabilities created without 3rd value in args array, props [mbijon](https://github.com/mbijon).
	* Fix the fact that meta description template for archive pages didn't work, props [MarcQueralt](https://github.com/MarcQueralt).
	* Prevent wrong shortcodes (that echo instead of return) from causing erroneous output.
	* Fix edge cases issue for keyword in first paragraph test not working.
	* Revert change in 1.4.14 that did a `do_shortcode` while in the `head` to retrieve images from posts, as too many plugins crash then, instead added `wpseo_pre_analysis_post_content` filter there as well.

= 1.4.14 =

This release contains tons and tons of bugfixes, thanks in *large* part to [Jrf](http://profiles.wordpress.org/jrf), who now has commit rights to the code on Github directly. Please join me in thanking her for her efforts!

* Notes:
    * Our GitHub repository moved to [https://github.com/Yoast/wordpress-seo](https://github.com/Yoast/wordpress-seo), old links should redirect but please check.

* Bugfixes
    * Switch to stock autocomplete file and fix clash with color picker, props [Heinrich Luehrsen](http://www.luehrsen-heinrich.de/).
    * Prevent strip category base code from breaking Custom Post Type rewrites, props [Steve Hulet](http://about.me/stevehulet).
    * Fixed [issue with canonical links](http://wordpress.org/support/topic/serious-canonical-issue-with-paginated-posts) on last page of paginated posts - props [maxbugfiy](http://wordpress.org/support/profile/maxbuxfiy)
    * Fixed bug in shortcode removal from meta description as reported by [professor44](http://profiles.wordpress.org/professor44/) - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed bug preventing saving of taxonomy meta data on first try - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed small (potential) issue in wpseo_title_test() - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed bug where RSS excerpt would be double wrapped in `&lt;p&gt;` tags as reported by [mikeprince](http://profiles.wordpress.org/mikeprince) - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed HTML validation error: Duplicate id Twitter on Social tab - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed undefined index notice as reported by [szepeviktor](http://profiles.wordpress.org/szepeviktor).
    * Fixed error in a database query as reported by [Watch Teller](http://wordpress.org/support/profile/watchteller) - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed small issue with how styles where enqueued/registered - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed bug in alt text of score dots as [reported by Rocket Pixels](http://wordpress.org/support/topic/dots-on-hover-over-show-na-tooltip) - props [Jrf](http://profiles.wordpress.org/jrf).
    * Applied best practices to all uses of preg_ functions fixing some bugs in the process - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed bug in processing of `%%ct_<custom-tax-name>%%` as [reported by Joy](http://wordpress.org/support/topic/plugin-dies-when-processing-ct_desc_) - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed: no more empty og: or twitter: tags. Also added additional escaping where needed - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed: Meta description tag discovery looked in parent theme header file even when a child theme is the current theme - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed: Using the 'Fix it' button would remove the meta description tag from the parent theme header file, even when a child theme is the current theme - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed: Using the 'Fix it' button would fail if it had already been used once (i.e. if a wpseo backup file already existed) - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed repeated unnecessary meta description tag checks on each visit to dashboard page - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed: Meta description 'Fix it' feedback message was not shown - props [Jrf](http://profiles.wordpress.org/jrf).
    * Mini-fix for plugin_dir_url - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed Author Highlighting to only show authors as possible choice for Google+ Plus author as reported by [Sanoma](https://github.com/jdevalk/wordpress-seo/issues/131) - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fixed `adjacent_rel_links()` for Genesis users - props [benjamin74](https://github.com/benjamin74) for reporting.
    * Replace jQuery .live function with .on(), as .live() has been deprecated and deleted. Props [Viktor Kostadinov](http://www.2buy1click.com/) & [Taco Verdonschot](http://yoast.com/about-us/taco-verdonschot/).
    * Fix how breadcrumbs deal with taxonomy orders. Props [Gaya Kessler](http://www.gayadesign.com/).
    * Fixed some PHP warnings

* Enhancements
    * Added `wpseo_pre_analysis_post_content` filter. This allows plugins to add content to the content that is analyzed by the page analysis functionality.
    * Added `wpseo_genesis_force_adjacent_rel_home` filter to allow forcing of rel=next / rel=prev links on the homepage pagination for Genesis users, they're off by default.
    * Make `$wpseo_metabox` a global, props [Peter Chester](http://tri.be/).
    * No need to show Twitter image when OpenGraph is showing, props [Gary Jones](http://garyjones.co.uk/).
    * Make sure WPML works again, props [dominykasgel](https://github.com/dominykasgel).
    * Added checks for the meta description tag on theme switch, on theme update and on (re-)activation of the WP SEO plugin including a visual warning if the check would warrant it - props [Jrf](http://profiles.wordpress.org/jrf).
    * Added the ability to request re-checking a theme for the meta description tag. Useful when you've manually removed it (to get rid of the warning), inspired by [tzeldin88](http://wordpress.org/support/topic/plugin-wordpress-seo-by-yoast-your-theme-contains-a-meta-description-which-blocks-wordpress-seo) - props [Jrf](http://profiles.wordpress.org/jrf).
    * OpenGraph image tags will now also be added for images added to the post via shortcodes, as suggested by [msebald](http://wordpress.org/support/topic/ogimage-set-to-default-image-but-articlepage-has-own-images?replies=3#post-4436317) - props [Jrf](http://profiles.wordpress.org/jrf).
    * Added 'wpseo_breadcrumb_single_link_with_sep' filter which allows users to filter a complete breadcrumb element including the separator - props [Jrf](http://profiles.wordpress.org/jrf).
    * Added 'wpseo_stopwords' filter which allows users to filter the stopwords list - props [Jrf](http://profiles.wordpress.org/jrf).
    * Added 'wpseo_terms' filter which allows users to filter the terms string - props [Jrf](http://profiles.wordpress.org/jrf).
    * Hide advanced tab for users for which it has been disabled, as [suggested by jrgmartin](https://github.com/jdevalk/wordpress-seo/issues/93) - props [Jrf](http://profiles.wordpress.org/jrf).
    * Updated Facebook supported locales list for og:locale

* i18n
    * Updated languages tr_TK, fi, ru_RU & da_DK
    * Added language hi_IN
    * Updated wordpress-seo.pot file

= 1.4.13 =

* Bugfixes
	* Fixed ampersand (&) in sitetitle in Title Templates loading as &amp;
	* Fixed error when focus keyword contains a / - props [Jrf](http://profiles.wordpress.org/jrf).
	* Fixed issue with utf8 characters in meta description - props [Jrf](http://profiles.wordpress.org/jrf).
	* Fixed undefined property error - props [Jrf](http://profiles.wordpress.org/jrf).
	* Fixed undefined index error for the last page of the tour - props [Jrf](http://profiles.wordpress.org/jrf).
	* Fixed undefined index error for images without alt - props [Jrf](http://profiles.wordpress.org/jrf).
    * Fix output of author for Google+ when using a static front page - props [petervanderdoes](https://github.com/petervanderdoes).
    * Keyword density calculation not working when special character in focus keyword - props [siriuzwhite](https://github.com/siriuzwhite).
    * Reverse output buffer cleaning for XML sitemaps, as that collides with WP Super Cache, thanks to [Rarst](https://github.com/Rarst) for finding this.
    * Fix canonical and rel=prev / rel=next links for paginated home pages using index.php links.
    * Fixed og:title not following title settings.
* Enhancements
	* Improved breadcrumbs and titles for 404 pages - props [Jrf](http://profiles.wordpress.org/jrf).
    * Moved XSL stylesheet from a static file in wp-content folder to a dynamic one, allowing it to work for sites that prevented the wp-content dir from being opened directly, f.i. through Sucuri's hardening.
    * Added a link in the XSL pointing back to the sitemap index on individual sitemaps.
    * When remove replytocom is checked in the permalink settings, these are now also redirected out.
    * Added filters to OpenGraph output functions that didn't have them yet.

= 1.4.12 =

* Bugfixes
	* Submit button displays again on Titles & Metas page.
	* SEO Title now calculates length correctly.
	* Force rewrite titles should no longer reset wrongly on update.

= 1.4.11 =

* i18n
	* Updated de_DE, ru_RU, zh_CN.
* Bugfixes
    * Make rel="publisher" markup appear on every page.
    * Prevent empty property='article:publisher' markup from being output .
    * Fixed twitter:description tag should only appears if OpenGraph is inactive.
    * og:description will default to get_the_excerpt when meta description is blank (similar to how twitter:description works).
	* Fixes only 25 tags (and other taxonomy) are being indexed in taxonomy sitemaps.
	* Fix lastmod dates for taxonomies in XML sitemap index file.
* Enhancements
	* Changed Social Admin section to have a tab-layout.
	* Moved Google+ section from Homepage tab of Titles & Metas to Social tab.
	* Make twitter:domain use WordPress site name instead of domainname.
	* Added more output filters in the Twitter class.

= 1.4.10 =

* Fixes
    * Caching was disabled in certain cases, this update fixes that.
* Enhancements
    * Added option to disable author sitemap.
    * If author pages are disabled, author sitemaps are now automatically disabled.

= 1.4.9 =

* i18n
    * Updated .pot file
    * Updated ar, da_DK, de_DE, el_GR, es_ES, fa_IR, fr_FR, he_IL, id_ID, nl_NL, ro_RO, sv_SE & tr_TK
    * Added hr & sl_SI
    * Many localization fixes
* Bugfixes
    * Fixed sitemap "loc" element to have encoded entities.
    * Honor the language setting if other plugins set the language.
    * sitemap.xml will now redirect to sitemap_index.xml if it doesn't exist statically.
    * Added filters 'wpseo_sitemap_exclude_post_type' and 'wpseo_sitemap_exclude_taxonomy' to allow themes/plugins to exclude entries in the XML sitemap.
    * Added RTL support, some CSS fixes.
    * Focus word gets counted in meta description when defined by a template.
    * Fixed some bugs with the focus keyword in the first paragraph test.
    * Fixed display bug in SEO Title column when defined by a template ('Page # of #').
    * Fixed a few strict notices that would pop up in WP 3.6.
    * Prevent other plugins from overriding the WP SEO menu position.
    * Enabled the advanced tab for site-admins on a multi-site install.
	* Fixed post save error when page analysis is disabled.
	* OpenGraph frontpage og:description and og:image tags now properly added to the frontpage.
* Enhancements
    * Added an HTML sitemap shortcode [wpseo_sitemap].
    * Added an XML sitemap listing the author profile URLs.
    * Added detection of Yoast's robots meta plugin and All In One SEO plugins, plugin now gives a notice to import settings and disable those plugins.
    * Prevent empty image tags in Twitter Cards - props [Mike Bijon](https://github.com/mbijon).
    * Add new `twitter:domain` tag  - props [Mike Bijon](https://github.com/mbijon).
    * Add support for Facebooks new OG tags for media publishers.
	* Allow authorship to be removed per post type.

= 1.4.7 =

* Properly fix security bug that should've been fixed in 1.4.5.
* Move from using several $options arrays in the frontend to 1 class wide option.
* Instead of firing all plugin options as function within head function, attach them to `wpseo_head` action, allowing easier filtering and changing.
* Where possible, use larger images for Facebook Opengraph.
* Add several filters and actions around social settings.

= 1.4.6 =

* Fix a possible fatal error in tracking.

= 1.4.5 =

* Bug fixes:
    * Fix security issue which allowed any user to reset settings.
    * Allow saving of SEO metadata for attachments.
    * Set the max-width of the snippet preview to 520px to look more like Google search results, while still allowing it to work on lower resolutions.
* Enhancements:
    * Remove the shortlink http header when the hide shortlink checkbox is checked.
    * Added a check on focus keyword in the page analysis functionality, checking whether a focus keyword has already been used before.
    * Update how the tracking class calculates users to improve speed.

= 1.4.4 =

* Fix changelog for 1.4.3
* Bugfixes
    * Fix activation bug.
* i18n
	* Updated es_ES, id_ID, he_IL.

= 1.4.3 =

* Bugfixes
    * Register core SEO menu at a lower than default prio so other plugins can tie in more easily.
    * Remove alt= from page analysis score divs.
    * Make site tracking use the site hash consistently between plugins.
    * Improve popup pointer removal.

= 1.4.2 =

* Bugfixes
    * Made the sitemaps class load in backend too so it always generates rewrites correctly.
    * Changed > to /> in class-twitter.php for validation as XHTML.
    * Small fix in metabox CSS for small screens (thx [Ryan Hellyer](http://ryanhellyer.net)).
    * Load classes on plugins_loaded instead of immediately on load to allow WPML to filter options.
* i18n
    * Updated bs_BA, cs_CZ, da_DK, de_DE, fa_IR, fr_FR, he_IL, hu_HU, id_ID, it_IT, nl_NL, pl_PL, pt_BR, ru_RU and tr_TR

= 1.4.1 =

* i18n:
    * Updated .pot file
    * Updated bg_BG, bs_BA, cs_CZ, fa_IR, hu_HU, pl_PL & ru_RU
* Bugfixes:
    * Focus keyword check now works again in all cases.
    * Fix typo in Video SEO banner.
* Enhancements:
    * Don't show banners for plugins you already have.

= 1.4 =

* i18n & documentation:
    * Updated Hebrew (he_IL)
    * Updated Italian (it_IT)
    * Updated Dutch (nl_NL)
    * Updated Swedish (sv_SE)
    * Updated some strings to fix typos.
    * Removed affiliate links from readme.txt.
* Bugfixes:
    * Fixed a bug in saving post meta details for revisions.
    * Prevent an error when there are no posts for post type.
    * Fix the privacy warning to point to the right place.
* Enhancements:
    * Slight performance improvement in <head> functionality by not resetting query when its not needed (kudos to @Rarst).
    * Slight performance improvement in options call by adding some caching (kudos to @Rarst as well).
    * Changed inner workings of search engine ping, adding YOAST_SEO_PING_IMMEDIATELY constant to allow immediate ping on publish.
    * Changed design of meta box, moving much of the help text out in favor of clicking on a help icon.
    * Removed Linkdex branding from page analysis functionality.

= Older changelogs =

Can be found in changelog.txt in the zip.