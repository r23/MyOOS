<?php
/**
   ----------------------------------------------------------------------
   $Id: user_create_account.php,v 1.3 2007/06/12 16:51:19 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account.php,v 1.8 2002/11/19 01:48:08 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

$aLang['navbar_title'] = 'Create an Account';
$aLang['heading_title'] = 'My Account Information';
$aLang['text_origin_login'] = '<strong>NOTE:</strong> If you already have an account with us, please login at the <a href="%s" class="alert-link"><u>login page</u></a>.';

$aLang['email_subject'] = 'Welcome to ' . STORE_NAME;
$aLang['email_greet_mr'] = 'Dear Mr. ' . stripslashes((string) $lastname) . ',' . "\n\n";
$aLang['email_greet_ms'] = 'Dear Ms. ' . stripslashes((string) $lastname) . ',' . "\n\n";
$aLang['email_greet_none'] = 'Dear ' . stripslashes((string) $firstname) . ',' . "\n\n";
$aLang['email_welcome'] = 'We welcome you to <strong>' . STORE_NAME . '</strong>.' . "\n\n";
$aLang['email_text'] = 'You can now take part in the <strong>various services</strong> we have to offer you. Some of these services include:' . "\n\n" . '<li><strong>Permanent Cart</strong> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><strong>Address Book</strong> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><strong>Order History</strong> - View your history of purchases that you have made with us.' . "\n" . '<li><strong>Products Reviews</strong> - Share your opinions on products with our other customers.' . "\n\n";
$aLang['email_contact'] = 'For help with any of our online services, please email the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";
$aLang['email_warning'] = '<strong>Note:</strong> This email address was given to us by one of our customers. If you did not signup to be a member, please send a email to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n";

$aLang['email_gv_incentive_header'] = 'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s';
$aLang['email_gv_redeem'] = 'The redeem code for is %s, you can enter the redeem code when checking out, after making a purchase';
$aLang['email_gv_link'] = 'or by following this link ';
$aLang['email_coupon_incentive_header'] = 'Congratulation, to make your first visit to our online shop a more rewarding experience' . "\n" .
                                        '  below are details of a Discount Coupon created just for you' . "\n\n";
$aLang['email_coupon_redeem'] = 'To use the coupon enter the redeem code which is %s during checkout, ' . "\n" .
                               'after making a purchase';

$aLang['email_password'] = 'Ihr Passwort fr \'' . STORE_NAME . '\' lautet:' . "\n\n" . '   %s' . "\n\n";


$aLang['owner_email_subject'] = 'New Customer';
$aLang['owner_email_date'] = 'Date:';
$aLang['owner_email_company_info'] = 'Company Details';
$aLang['owner_email_contact'] = 'Contact Information';
$aLang['owner_email_options'] = 'Options';
$aLang['owner_email_company'] = 'Company Name:';
$aLang['owner_email_owner'] = 'Owner';
$aLang['owner_email_gender'] = 'Gender:';
$aLang['owner_email_first_name'] = 'First Name:';
$aLang['owner_email_last_name'] = 'Last Name:';
$aLang['owner_email_date_of_birth'] = 'Date of Birth:';
$aLang['owner_email_address'] = 'E-Mail-Address:';
$aLang['owner_email_street'] = 'Street Address:';
$aLang['owner_email_post_code'] = 'Post Code:';
$aLang['owner_email_city'] = 'City:';
$aLang['owner_email_state'] = 'State/Province:';
$aLang['owner_email_country'] = 'Country:';
$aLang['owner_email_telephone_number'] = 'Telephone Number:';
$aLang['owner_email_newsletter'] = 'Newsletter:';
$aLang['owner_email_newsletter_yes'] = 'Subscribed';
$aLang['owner_email_newsletter_no'] = 'Unsubscribed';
$aLang['email_separator'] = '------------------------------------------------------';
