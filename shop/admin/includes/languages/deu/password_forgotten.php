<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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

define('ADMIN_PASSWORD_SUBJECT', STORE_NAME . ' - Ihr neues Passwort.');
define('ADMIN_EMAIL_TEXT', '�er die Adresse ' . oos_server_get_var('REMOTE_ADDR') . ' haben wir eine Anfrage zur Passworterneuerung erhalten.' . "\n\n" . 'Ihr neues Passwort f&uuml;r \'' . STORE_NAME . '\' lautet ab sofort:' . "\n\n" . '   %s' . "\n\n");

define('HEADING_PASSWORD_FORGOTTEN', 'Passwort vergessen');
define('TEXT_PASSWORD_INFO', 'Bitte geben Sie Ihren Vornamen und Ihre E-Mail Adresse ein und klicken Sie auf Passwort senden. <br />In Krze erhalten Sie ein neues Passwort. Verwenden Sie dieses Passwort, um sich anzumelden.');
?>
