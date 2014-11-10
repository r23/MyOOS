<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.1 2007/06/14 17:11:36 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.8 2003/02/16 00:42:03 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('ADMIN_PASSWORD_SUBJECT', STORE_NAME . ' - Uw nieuwe wachtwoord.');
define('ADMIN_EMAIL_TEXT', 'Via het adres ' . oos_server_get_var('REMOTE_ADDR') . ' hebben wij een verzoek voor een nieuw wachtwoord gekregen.' . "\n\n" . 'Uw nieuwe wachtwoord voor \'' . STORE_NAME . '\' is vanaf nu:' . "\n\n" . '   %s' . "\n\n");

define('HEADING_PASSWORD_FORGOTTEN', 'Wachtwoord vergeten:');
define('TEXT_PASSWORD_INFO', 'Please enter your Username and e-mail address then click on the Send Password button.<br />You will receive a new password shortly. Use this new password to access the site.');
?>
