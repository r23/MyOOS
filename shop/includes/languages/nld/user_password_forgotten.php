<?php
/* ----------------------------------------------------------------------
   $Id: user_password_forgotten.php,v 1.3 2007/06/12 17:09:44 r23 Exp $

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

$aLang['navbar_title_1'] = 'Aanmelden';
$aLang['navbar_title_2'] = 'Wachtwoord vergeten';
$aLang['heading_title'] = 'Wat was ook alweer mijn wachtwoord?';
$aLang['text_no_email_address_found'] = '<font color="#ff0000"><b>ATTENTIE:</b></font> Het ingevoerde emailadres is niet geregistreerd. probeer het a.u.b. nog een keer.';
$aLang['email_password_reminder_subject'] = STORE_NAME . ' - Uw nieuwe wachtwoord.';
$aLang['email_password_reminder_body'] = 'Via het adres ' . oos_server_get_remote() . ' hebben wij een verzoek voor wachtwoordvenieuwing.' . "\n\n" . 'Uw nieuwe wachtwoord voor \'' . STORE_NAME . '\' is vanaf heden:' . "\n\n" . '   %s' . "\n\n";
$aLang['text_password_sent'] = 'Een nieuw wachtwoord is per email verstuurd.';
?>
