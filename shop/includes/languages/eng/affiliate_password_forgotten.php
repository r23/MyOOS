<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_password_forgotten.php,v 1.3 2007/06/12 16:51:19 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_password_forgotten.php,v 1.3 2003/02/15 18:41:15 harley_vb 
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


$aLang['navbar_title_1'] = 'Login';
$aLang['navbar_title_2'] = 'Affiliate Password Forgotten';
$aLang['heading_title'] = 'I\'ve Forgotten My Affiliate Password!';
$aLang['text_no_email_address_found'] = '<font color="#ff0000"><b>NOTE:</b></font> The E-Mail Address was not found in our records. Please try again.';
$aLang['email_password_reminder_subject'] = STORE_NAME . ' - New Affiliate Password';
$aLang['email_password_reminder_body'] = 'A new affiliate password was requested from ' . oos_server_get_remote() . '.' . "\n\n" . 'Your new affiliate password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n";
$aLang['text_password_sent'] = 'A New Affiliate Password Has Been Sent To Your Email Address';
?>
