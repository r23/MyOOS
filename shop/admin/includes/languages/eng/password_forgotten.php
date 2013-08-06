<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
   
$aLang['heading_title'] =  'Password Forgotten:';

$aLang['admin_password_subject'] =  STORE_NAME . ' - New Password';
$aLang['admin_email_text'] = 'Hallo %s,' . "\n\n" . 'A new password was requested from %s.' . "\n\n" . 'Your new password to %s is' . "\n\n" . '   %s' . "\n\n" . 'Best regards' . "\n\n";

$aLang['entry_firstname'] = 'First Name:';
$aLang['placeholder_firstname'] = 'Your First Name';
$aLang['entry_email_address'] = 'Email Address:';
$aLang['placeholder_email_address'] = 'Your Email Address';
$aLang['text_forgotten_error'] = '<strong>ERROR:</strong> first name or e-mail address not match!';
$aLang['text_password_info'] = 'Please enter your Username and e-mail address then click on the Send Password button.<br />You will receive a new password shortly. Use this new password to access the site.';

