<?php
/**
   ----------------------------------------------------------------------
   $Id: user_login.php,v 1.3 2007/06/12 16:51:19 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.11 2002/11/12 00:45:21 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

$aLang['navbar_title_1'] = 'Login';
$aLang['navbar_title_2'] = 'Two-step verification';

$aLang['navbar_title'] = 'Two-step verification';
$aLang['heading_title'] = 'Two-step verification';

$aLang['text_2fa_title'] = 'Generate code with authentication app.';
$aLang['text_2fa_info'] = 'Each time you log in, generate a one-time code using an authentication app.';
$aLang['text_2fa_step1'] = 'Step 1: Scan the following QR code or enter the key manually into your authentication app.';
$aLang['text_2fa_key'] = 'Key';
$aLang['text_2fa_step2'] = 'Step 2: Enter the 6-digit security code from your authentication app.';
$aLang['text_2fa_placeholder'] = 'Authentication code';

$aLang['text_2fa_app'] = 'You need an authentication app?';
$aLang['text_2fa_app_info'] = 'You can easily download an authentication app. This will generate a unique security code that you can use in addition to your password to log in. However, these app providers will not have access to your account information.';
$aLang['text_2fa_app_download'] = 'To download an app, open the App Store on your phone. Search for &quot;Google Authenticator&quot; and download this app.';

$aLang['text_code_error'] = '<strong>Error:</strong> No match with the \'authentication code\' entered.';
$aLang['entry_code_error'] = '<strong>Error:</strong> The security code consists of 6 digits';
$aLang['entry_2fa_success'] = 'You have set up two-step verification for your account';
