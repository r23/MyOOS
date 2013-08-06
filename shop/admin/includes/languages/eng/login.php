<?php
/* ----------------------------------------------------------------------
   $Id: login.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/ 
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.11 2002/06/03 13:19:42 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['heading_title'] = 'Login Panel';
$aLang['text_returning_admin'] = 'Staff only!';
$aLang['entry_email_address'] = 'Email Address:';
$aLang['placeholder_email_address'] = 'Your Email Address';
$aLang['entry_password'] = 'Password:';

$aLang['button_login'] = 'Log in';
$aLang['text_password_forgotten'] = 'Lost password?';
$aLang['text_welcome'] = '<h4>Welcome to MyOOS!</h4>Use a valid eMail and password to gain access to the administration console.';
$aLang['text_login_error'] = '<strong>ERROR:</strong> Wrong username or password!';

$aLang['text_forgotten_fail'] = 'You have try over 3 times. For security reason, please contact your Web Administrator to get new password.';
$aLang['text_forgotten_success'] = 'The new password have sent to your email address. Please check your email to login.';

$aLang['admin_email_subject'] = 'New Password'; 
$aLang['admin_email_text'] = 'Hi %s,' . "\n\n" . 'You can access the admin panel with the following password. Once you access the admin, please change your password!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is an automated response, please do not reply!'; 

