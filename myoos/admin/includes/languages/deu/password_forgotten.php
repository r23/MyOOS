<?php
/**
   ----------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.8 2003/02/16 00:42:03 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('ADMIN_PASSWORD_SUBJECT', STORE_NAME . ' - Ihr neues Passwort.');
define('ADMIN_PASSWORD_EMAIL_TEXT', '&Uumlber die Adresse ' . oos_server_get_var('REMOTE_ADDR') . ' haben wir eine Anfrage zur Passworterneuerung erhalten.' . "\n\n" . 'Ihr neues Passwort für \'' . STORE_NAME . '\' lautet ab sofort:' . "\n\n" . '   %s' . "\n\n");

define('HEADING_PASSWORD_FORGOTTEN', 'Passwort vergessen');
define('TEXT_PASSWORD_INFO', 'Bitte geben Sie Ihren Vornamen und Ihre E-Mail Adresse ein und klicken Sie auf Passwort senden. <br>In Kürze erhalten Sie ein neues Passwort. Verwenden Sie dieses Passwort, um sich anzumelden.');
