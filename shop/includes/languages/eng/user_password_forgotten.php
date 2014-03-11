<?php
/* ----------------------------------------------------------------------
   $Id: user_password_forgotten.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.6 2002/11/19 01:48:08 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = 'Login';
$aLang['navbar_title_2'] = 'Password Forgotten';
$aLang['heading_title'] = 'I\'ve Forgotten My Password!';
$aLang['text_email_address_into'] = 'Please enter the email address you used to register. We will then send you a new password.';

$aLang['text_no_email_address_found'] = ' The E-Mail Address was not found in our records, please try again.';
$aLang['email_password_reminder_subject'] = STORE_NAME . ' - New Password';
$aLang['email_password_reminder_body'] = 'A new password was requested from ' . oos_server_get_remote() . '.' . "\n\n" . 'Your new password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n";
$aLang['text_password_sent'] = 'A New Password Has Been Sent To Your Email Address';

