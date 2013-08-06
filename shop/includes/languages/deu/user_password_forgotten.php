<?php
/* ----------------------------------------------------------------------
   $Id: user_password_forgotten.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
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

$aLang['navbar_title_1'] = 'Anmelden';
$aLang['navbar_title_2'] = 'Passwort vergessen';
$aLang['heading_title'] = 'Wie war noch mal mein Passwort?';
$aLang['text_no_email_address_found'] = '<font color="#ff0000"><b>ACHTUNG:</b></font> Die eingegebene eMail-Adresse ist nicht registriert. Bitte versuchen Sie es noch einmal.';
$aLang['email_password_reminder_subject'] = STORE_NAME . ' - Ihr neues Passwort.';
$aLang['email_password_reminder_body'] = 'Über die Adresse ' . oos_server_get_remote() . ' haben wir eine Anfrage zur Passworterneuerung erhalten.' . "\n\n" . 'Ihr neues Passwort für \'' . STORE_NAME . '\' lautet ab sofort:' . "\n\n" . '   %s' . "\n\n";
$aLang['text_password_sent'] = 'Ein neues Passwort wurde per eMail verschickt.';

