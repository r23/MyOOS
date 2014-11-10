<?php
/* ----------------------------------------------------------------------
   $Id: links_submit.php,v 1.3 2007/06/12 16:36:39 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links_submit.php,v 1.00 2003/10/03 
   ----------------------------------------------------------------------
   Links Manager
   
   Contribution based on:
   
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = 'Links';
$aLang['navbar_title_2'] = 'Submit A Link';

$aLang['heading_title'] = 'Link Information';

$aLang['text_main'] = 'Please fill out the following form to submit your website.';

$aLang['email_subject'] = 'Welcome to ' . STORE_NAME . ' link exchange.';
$aLang['email_greet_none'] = 'Dear %s' . "\n\n";
$aLang['email_welcome'] = 'We welcome you to the <b>' . STORE_NAME . '</b> link exchange program.' . "\n\n";
$aLang['email_text'] = 'Your link has been successfully submitted at ' . STORE_NAME . '. It will be added to our listing as soon as we approve it. You will receive an email about the status of your submittal. If you have not received it within the next 48 hours, please contact us before submitting your link again.' . "\n\n";
$aLang['email_contact'] = 'For help with our link exchange program, please email the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";
$aLang['email_warning'] = '<b>Note:</b> This email address was given to us during a link submittal. If you have a problem, please send an email to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n";
$aLang['email_owner_subject'] = 'Link submittal at ' . STORE_NAME;
$aLang['email_owner_text'] = 'A new link was submitted at ' . STORE_NAME . '. It is not yet approved. Please verify this link and activate.' . "\n\n";

$aLang['text_links_help_link'] = '&nbsp;Help&nbsp;[?]';

$aLang['heading_links_help'] = 'Links Help';
$aLang['text_links_help'] = '<b>Site Title:</b> A descriptive title for your website.<br><br><b>URL:</b> The absolute web address of your website, including the \'http://\'.<br><br><b>Category:</b> Most appropriate category under which your website falls.<br><br><b>Description:</b> A brief description of your website.<br><br><b>Image URL:</b> The absolute URL of the image you wish to submit, including the \'http://\'. This image will be displayed along with your website link.<br>Eg: http://your-domain.com/path/to/your/image.gif <br><br><b>Full Name:</b> Your full name.<br><br><b>Email:</b> Your email address. Please enter a valid email, as you will be notified via email.<br><br><b>Reciprocal Page:</b> The absolute URL of your links page, where a link to our website will be listed/displayed.<br>Eg: http://your-domain.com/path/to/your/links_page.php';
$aLang['text_close_window'] = '<u>Close Window</u> [x]';

// VJ todo - move to common language file
$aLang['category_website'] = 'Website Details';
$aLang['category_reciprocal'] = 'Reciprocal Page Details';

$aLang['entry_links_title'] = 'Site Title:';
$aLang['entry_links_title_error'] = 'Link title must contain a minimum of ' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' characters.';
$aLang['entry_links_title_text'] = '*';
$aLang['entry_links_url'] = 'URL:';
$aLang['entry_links_url_error'] = 'URL must contain a minimum of ' . ENTRY_LINKS_URL_MIN_LENGTH . ' characters.';
$aLang['entry_links_url_text'] = '*';
$aLang['entry_links_category'] = 'Category:';
$aLang['entry_links_category_text'] = '*';
$aLang['entry_links_description'] = 'Description:';
$aLang['entry_links_description_error'] = 'Description must contain a minimum of ' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' characters.';
$aLang['entry_links_description_text'] = '*';
$aLang['entry_links_image'] = 'Image URL:';
$aLang['entry_links_image_text'] = '';
$aLang['entry_links_contact_name'] = 'Full Name:';
$aLang['entry_links_contact_name_error'] = 'Your Full Name must contain a minimum of ' . ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH . ' characters.';
$aLang['entry_links_contact_name_text'] = '*';
$aLang['entry_links_reciprocal_url'] = 'Reciprocal Page:';
$aLang['entry_links_reciprocal_url_error'] = 'Reciprocal page must contain a minimum of ' . ENTRY_LINKS_URL_MIN_LENGTH . ' characters.';
$aLang['entry_links_reciprocal_url_text'] = '*';
?>
