=== Contact Form 7 ===
Contributors: takayukister
Donate link: https://contactform7.com/donate/
Tags: contact, form, contact form, feedback, email, ajax, captcha, akismet, multilingual
Requires at least: 4.8
Tested up to: 4.9
Stable tag: 5.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Just another contact form plugin. Simple but flexible.

== Description ==

Contact Form 7 can manage multiple contact forms, plus you can customize the form and the mail contents flexibly with simple markup. The form supports Ajax-powered submitting, CAPTCHA, Akismet spam filtering and so on.

= Docs & Support =

You can find [docs](https://contactform7.com/docs/), [FAQ](https://contactform7.com/faq/) and more detailed information about Contact Form 7 on [contactform7.com](https://contactform7.com/). If you were unable to find the answer to your question on the FAQ or in any of the documentation, you should check the [support forum](https://wordpress.org/support/plugin/contact-form-7/) on WordPress.org. If you can't locate any topics that pertain to your particular issue, post a new topic for it.

= Contact Form 7 Needs Your Support =

It is hard to continue development and support for this free plugin without contributions from users like you. If you enjoy using Contact Form 7 and find it useful, please consider [__making a donation__](https://contactform7.com/donate/). Your donation will help encourage and support the plugin's continued development and better user support.

= Recommended Plugins =

The following plugins are recommended for Contact Form 7 users:

* [Flamingo](https://wordpress.org/plugins/flamingo/) by Takayuki Miyoshi - With Flamingo, you can save submitted messages via contact forms in the database.
* [Bogo](https://wordpress.org/plugins/bogo/) by Takayuki Miyoshi - Bogo is a straight-forward multilingual plugin that doesn't cause headaches.

= Translations =

You can [translate Contact Form 7](https://contactform7.com/translating-contact-form-7/) on [__translate.wordpress.org__](https://translate.wordpress.org/projects/wp-plugins/contact-form-7).

== Installation ==

1. Upload the entire `contact-form-7` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

You will find 'Contact' menu in your WordPress admin panel.

For basic usage, you can also have a look at the [plugin web site](https://contactform7.com/).

== Frequently Asked Questions ==

Do you have questions or issues with Contact Form 7? Use these support channels appropriately.

1. [Docs](https://contactform7.com/docs/)
1. [FAQ](https://contactform7.com/faq/)
1. [Support Forum](https://wordpress.org/support/plugin/contact-form-7/)

[Support](https://contactform7.com/support/)

== Screenshots ==

1. screenshot-1.png

== Changelog ==

For more information, see [Releases](https://contactform7.com/category/releases/).

= 5.0.1 =

* Fixed incorrect uses of _n().
* Config validation: Fixed incorrect count of alerts in the Additional Settings tab panel.
* Config validation: Fixed improper treatment for the [_site_admin_email] special mail-tag in the From mail header field.
* Acceptance checkbox: The class and id attributes specified were applied to the wrong HTML element.
* Config validation: When there is an additional mail header for mailboxes like Cc or Reply-To, but it has a possible empty value, “Invalid mailbox syntax is used” error will be returned.
* Explicitly specify the fourth parameter of add_action() to avoid passing unintended parameter values.
* Check if the target directory is empty before removing the directory.

= 5.0 =

* Additional settings: on_sent_ok and on_submit have been removed.
* New additional setting: skip_mail
* Flamingo: Inbound channel title changes in conjunction with a change in the title of the corresponding contact form.
* DOM events: Make an entire API response object accessible through the event.detail.apiResponse property.
* HTML mail: Adds language-related attributes to the HTML header.
* File upload: Sets the accept attribute to an uploading field.
* Introduces the WPCF7_MailTag class.
* Allows aborting a mail-sending attempt using the wpcf7_before_send_mail action hook. Also, you can set a custom status and a message through the action hook.
* Acceptance checkbox: Allows the specifying of a statement of conditions in the form-tag’s content part.
* Acceptance checkbox: Supports the optional option.
* New special mail tags: [_site_title], [_site_description], [_site_url], [_site_admin_email], [_invalid_fields], [_user_login], [_user_email], [_user_url], [_user_first_name], [_user_last_name], [_user_nickname], and [_user_display_name]
* New filter hooks: wpcf7_upload_file_name, wpcf7_autop_or_not, wpcf7_posted_data_{$type}, and wpcf7_mail_tag_replaced_{$type}
* New form-tag features: zero-controls-container and not-for-mail
