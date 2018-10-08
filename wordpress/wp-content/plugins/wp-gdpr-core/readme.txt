=== WP GDPR ===
Contributors: mieke01,sebakurzyn,vanrijckel,kevinume,aytac,koenhuybrechts,markcreeten
Tags: Personal data, GDPR, compliance, regulations, protection, data protection, consent, European, regulation, privacy, RGPD, AVG, EU, Woocommerce, wc, Contact Form 7, cf7, cfdb7, gravity forms, gf, flamingo
Requires at least: 4.6.10
Tested up to: 4.9.8
Stable tag: 2.0.8
Requires PHP: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make your website GDPR compliant and automate the process of handling personal data while integrating with plugins.

== Description ==
This open source plugin will assist you making your website GDPR compliant by making personal data accessible to the owner of the data. Visitors (owners) don't need user accounts to access their data. Everything works through a unique link and e-mails.


WP-GDPR integrates with some of the most well-known plugins through add-ons. This will make the data stored by the plugins available for the visitor to manage it.
List of all add-ons:  [https://wp-gdpr.eu/add-ons/](https://wp-gdpr.eu/add-ons/).
Integration with:
 - [Gravity Forms](https://wp-gdpr.eu/add-ons/gravity-forms/)
 - [Contact Form DB 7](https://wp-gdpr.eu/add-ons/contact-form-db-7-addon/)
 - [WooCommerce](https://wp-gdpr.eu/add-ons/woocommerce-add-on/)
 - [Flamingo](https://wp-gdpr.eu/add-on/flamingo-add-on)

= How WP-GDPR Core works =

The plugin creates a page where users can request access to their personal data, stored on your website. You can find this page in the list of WordPress pages.
In the backend you'll get an overview of the requests users send and you can see which plugins collect personal data and need a 'ask for approval' checkbox.

Users who ask to view their personal data will get an email with a unique url on which they can view, update and download their comments and ask for a removal per comment.
When they ask for a removal, the admin has the ability to delete the comment through the wp-gdpr backend.
All emails will be sent automatically.

We made our code available on [Github](https://github.com/WP-GDPR/wp-gdpr-core/) and are welcoming Pull Request!

== Customization ==

= E-mails =

We have 5 e-mail templates and they are all editable through [filters](https://codex.wordpress.org/Plugin_API/Filter_Reference) or can be replaced in the theme.

= 1. Option one =

In order to replace the email template, you can copy the template from our plugin ( wp-gdpr-core/view/email ) and move it to your theme. Place it in under theme-name/wp-gdpr-core/email/samefilename.php

From now on you can style your new placed templates as you like.

= 2. Option two ( Email filters ) =


Here are the filters for the email that is send to requestor and the dpo.

* 'wp_gdpr_request_email': $email_template, $email, $url (email to requester)
* 'wp_gdpr_request_email_dpo': $email_template, $email, $url (email to dpo)


Here are the filters for the email that is send to the admin for a delete request.

* 'wp_gdpr_admin_new_delete_request': $email_template, $requested_email (email to admin)

Here are the filters for the email that is send to the requester and the dpo for a delete confirmation.

* 'wp_gdpr_delete_confirmation': $email_template, $date_of_request, $processed_data (email to requester)
* 'wp_gdpr_delete_confirmation_dpo': $email_template, $date_of_request, $processed_data (email to dpo)

= Request page filters =

We have added 3 filters to change the text on the request page.

0. 'Original text' => 'filter_name'
1. 'Submit' => 'wp-gdpr-submit-text'
2. 'Warning' => 'wp-gdpr-warning-text'
3. 'This link will become deprecated after 48 hours.' => 'wp-gdpr-link-text'

= Privacy Center page filters =

We have added filters to change the text on the navigation of the privacy center page.

0. 'Original text' => 'filter_name'
1. 'Wordpress Comments' => 'wp_gdpr_wp_comments'
2. 'Condolence Manager' => 'wp_gdpr_wp_condolance_manager'
3. 'Contact Form DB7' => 'wp_gdpr_wp_cfdb7'
4. 'Flamingo' => 'wp_gdpr_wp_flamingo'
5. 'Mailchimp' => 'wp_gdpr_wp_mailchimp'
6. 'Gravity Forms' => 'wp_gdpr_wp_grafity_form'
7. 'WooCommerce' => 'wp_gdpr_wp_woocommerce'


== Installation ==
1. Upload the plugin files to the /wp-content/plugins, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the ‘Plugins’ screen in WordPress
3. ‘WP GDPR’ will be created to view the requests in the backend
4. The page 'GDPR – Request personal data' will be created. This page displays the form where visitors can submit their request.

== Screenshots ==
1. WP-GDPR Requests - Overview of all requests from users regarding their personal data
2. WP-GDPR Settings - Change the text for consent boxes and add a DPO emailaddress
3. WP-GDPR Request page - A front-end view for users to request their personal data
4. WP-GDPR Email links - Users can access their personal data through a unique and secure email link
5. WP-GDPR Help Center - Get help with WP-GDPR: watch tutorials, read the FAQ or get support
6. WP-GDPR Your Plugins - Overview of plugins installed on your website who use personal data
7. WP-GDPR Add-ons - Overview of add-ons available

== Frequently Asked Questions ==

== Changelog ==

Version 2.0.8 (2018-08-01)
    - Add condolance manager in plugins.json

Version 2.0.7 (2018-07-19)
    - Css fix for tab-content

Version 2.0.6 (2018-07-12)
    - Css fix for privacy center sorting arrows
    - Update SE translation
    - Fix for html entities in email
    
Version 2.0.5 (2018-07-11)
    - Added new translations on the privacy center page
    - Update translations BE, IT, SE

Version 2.0.4 (2018-06-27)
    - Added 7 filters for the privacy center page
    - Translation fix for emails
    - Italian translation added some new strings

Version 2.0.3 (2018-06-12)
    - Fix German translation issue
    - Css conflixt fix
    - Remove font-awesome css

Version 2.0.2 (2018-06-05)
    - Fix jQuery conflict

Version 2.0.1 (2018-06-05)
    - Translation domain fix

Version 2.0.0 (2018-06-04)
    - New frontend Privacy Center UI for personal data management
    - New options for users to export their personal data : PDF / COPY / EXCEL / CSV / PRINT
    - Users can filter their personal data through search
    - Users can sort their Personal data in the Privacy Center
    - Privacy Center uses theme header + footer
    - Upgraded translations for NL BE FR SE

Version 1.5.7 (2018-05-22)
    - Change session_id check

Version 1.5.6 (2018-05-17)
    - Added the "thank you" text after mail is send

Version 1.5.5 (2018-05-16)
    - Removed error_log "WPCLI"
    - 3 filters added at the request page, can be overriden by a filter

Version 1.5.4 (2018-05-08)
    - Added API settings
    - Enhanced email template, can be overriden by filter or theme

Version 1.5.3 (2018-04-19)
    - Add Bulgarian translation
    - Css admin fixes
    - Log debug function
    - Hook privacy policy in settings
    - Minor bugfix for mail headers
    - Dpo / admin now get different mails for requests
    - Minor text adjustments

Version 1.5.2 (2018-04-12)
    - Hotfix svn conflicts

Version 1.5.1 (2018-04-12)
    - Minor bug fix

Version 1.5.0 (2018-04-12)
    - Improve interface in wp-admin
    - Improve labels and texts
    - Add data to plugin.json
    - Add Call To Action buttons to add-on overview
    - Add Norwegian translation
    - Add Italian translation

Version 1.4.4 (2018-03-30)
    - Fix compatible Jetpack
    - Added translation SV
    - Minor bug fix

Version 1.4.3 (2018-03-23)
    - Fix minor bugs
    
Version 1.4.2 (2018-03-16)
    - Fix deprecated warning
    - Fix when request form is embedded on a non-standard page. Until now, you got a 404-error when redirecting to the      "Thank you"-page
    - Fix confirmation of processing the delete request shows a short reference to what happened to the data
    - Enhancement add table header "request language"

Version 1.4.1 (2018-03-15)
    - Make checkbox compatible with jetpack

Version 1.4.0 (2018-03-09)
    - Add DPO e-mail address
    - Add dpo setting
    - Option to not show the comments section
    - Add settings feature
    - Stop form submition after refreshing
    - Add filter to implement checkbox in other commentforms
    - Update DE language

Version 1.3.3
    - Check version to create column

Version 1.3.2
    - Create colomn languages in table
    - Update autoloader

Version 1.3.1
    - Bugfix check if ICL_LANGUAGE_CODE is defined

Version 1.3.0
    - Add Spanish, Portuguese and Catalan languages translations
    - Make gdpr check fields customizable
    - Make check fields translatable

Version 1.2.4
    - Update readme with github repository
    - Change pot-file and po/mo-files
    - Update styling
    - Add hooks

Version 1.2.3
    - Check if is_plugin_active() exists

Version 1.2.2
    - Update de and po language

Version 1.2.1
    - Update .pot file
    - Quickfix dublicated GDPR checkbox

Version 1.2
    - Fix compatibility with WP Discuz
    - Add functionality to upadate default privacy url
    - Add grumphp configuration
    - Add CHANGELOG.md
    - Add README.md
    - Add git repo on Github: https://github.com/AppSaloon/WP-GDPR
    - Add email notification when sommeone askes for a "delete requests"

Version 1.1.6
    - Add .pot file
    - Add german translation

Version 1.1.5
    - Delete develop code

Version 1.1.4
    - Update typing errors

Version 1.1.3
    - Add admin css
    - Add gdpr-translation.php file

Version 1.1.2
    - Update page template comments overview page
    - Add checkbox when data is requested
    - Update front-end translation
    - Add translation PL

Version 1.1.1
    - Add update_comments.js

Version 1.1.0
    - Add name and email field to comments list
    - Let users update their name and email
    - Add download button to comments list
    - Make it possible for the admin to choose between delete comment or make comment anonymous