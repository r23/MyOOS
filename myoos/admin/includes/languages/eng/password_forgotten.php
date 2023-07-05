<?php
/**
   ----------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.6 2002/11/19 01:48:08 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('ADMIN_PASSWORD_SUBJECT', STORE_NAME . ' - New Password');
define('ADMIN_PASSWORD_EMAIL_TEXT', 'A new password was requested from ' . oos_server_get_var('REMOTE_ADDR') . '.' . "\n\n" . 'Your new password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");

define('HEADING_PASSWORD_FORGOTTEN', 'Password Forgotten:');
define('TEXT_PASSWORD_INFO', 'Please enter your Username and e-mail address then click on the Send Password button.<br>You will receive a new password shortly. Use this new password to access the site.');
