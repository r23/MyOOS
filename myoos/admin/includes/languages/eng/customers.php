<?php
/**
   ----------------------------------------------------------------------
   $Id: customers.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers.php,v 1.12 2002/01/12 18:46:27 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */
/**
   ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Customers');
define('HEADING_TITLE_SEARCH', 'Search:');

define('TABLE_HEADING_FIRSTNAME', 'First Name');
define('TABLE_HEADING_LASTNAME', 'Last Name');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Account Created');
define('TABLE_HEADING_ACTION', 'Action');
define('HEADING_TITLE_STATUS', 'Status:');
define('TEXT_ALL_CUSTOMERS', 'All Customers');
define('HEADING_TITLE_LOGIN', 'Login');
define('HEADING_TITLE_2FA_LOGIN', '2FA');

define('TEXT_INFO_HEADING_STATUS_CUSTOMER', 'Edit Customer Status');
define('TEXT_NO_CUSTOMER_HISTORY', 'No Customer History Available');
define('TABLE_HEADING_NEW_VALUE', 'New Value');
define('TABLE_HEADING_OLD_VALUE', 'Old Value');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');

define('CATEGORY_MAX_ORDER', 'Maximum Order');
define('ENTRY_MAX_ORDER', 'Credit Limit:');

define('ENTRY_VAT_ID_STATUS', 'Vat ID check');
define('ENTRY_VAT_ID_STATUS_YES', 'yes');
define('ENTRY_VAT_ID_STATUS_NO', 'no');

define('TEXT_DATE_ACCOUNT_CREATED', 'Account Created:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Last Modified:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Last Logon:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Number of Logons:');
define('TEXT_INFO_COUNTRY', 'Country:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'Number of Reviews:');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this customer?');
define('TEXT_DELETE_REVIEWS', 'Delete %s review(s)');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Delete Customer');
define('TYPE_BELOW', 'Type below');
define('PLEASE_SELECT', 'Select One');

define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear Mr. ');
define('EMAIL_GREET_MS', 'Dear Ms. ');
define('EMAIL_GREET_NONE', 'Dear Sir');
define('EMAIL_WELCOME', 'We welcome you to <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'You can now take part in the <b>various services</b> we have to offer you. Some of these services include:' . "\n\n" . '<li><b>Permanent Cart</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n\n");
define('EMAIL_CONTACT', 'For help with any of our online services, please email the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> This email address was given to us by one of our customers. If you did not signup to be a member, please send a email to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_PASSWORD_BODY', 'Your password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");

define('EMAIL_GV_INCENTIVE_HEADER', 'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for is %s, you can enter the redeem code when checking out, after making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define(
    'EMAIL_COUPON_INCENTIVE_HEADER',
    'Congratulation, to make your first visit to our online shop a more rewarding experience' . "\n" .
    '  below are details of a Discount Coupon created just for you' . "\n\n"
);
define(
    'EMAIL_COUPON_REDEEM',
    'To use the coupon enter the redeem code which is %s during checkout, ' . "\n" .
    'after making a purchase'
);
