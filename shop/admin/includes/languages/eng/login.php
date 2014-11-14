<?php
/* ----------------------------------------------------------------------
   $Id: login.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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

define('HEADING_RETURNING_ADMIN', 'Login Panel:');
define('TEXT_RETURNING_ADMIN', 'Staff only!');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_FIRSTNAME', 'First Name:');
define('IMAGE_BUTTON_LOGIN', 'Submit');

define('SECURITYCODE', 'Securitycode:');
define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten?');
define('TEXT_WELCOME', 'Welcome to <br />MyOOS [Shopsystem]!</p><p>Use a valid eMail and password to gain access to the administration console.');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> Wrong username or password!');
define('TEXT_FORGOTTEN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> first name and password not match!');
define('TEXT_FORGOTTEN_FAIL', 'You have try over 3 times. For security reason, please contact your Web Administrator to get new password.');
define('TEXT_FORGOTTEN_SUCCESS', 'The new password have sent to your email address. Please check your email and click back to login.');

define('ADMIN_EMAIL_SUBJECT', 'New Password'); 
define('ADMIN_EMAIL_TEXT', 'Hi %s,' . "\n\n" . 'You can access the admin panel with the following password. Once you access the admin, please change your password!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is an automated response, please do not reply!'); 

