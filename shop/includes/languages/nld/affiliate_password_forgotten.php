<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_password_forgotten.php,v 1.3 2007/06/12 17:09:43 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_password_forgotten.php,v 1.4 2003/02/14 00:01:46 harley_vb
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


$aLang['navbar_title_1'] = 'Aanmelden';
$aLang['navbar_title_2'] = 'Wachtwoord voor het partnerprogramma vergeten';
$aLang['heading_title'] = 'Wat was ook alweer mijn wachtwoord?';
$aLang['text_no_email_address_found'] = '<font color="#ff0000"><b>ATTENTIE:</b></font> Het ingevoerde emailadres is niet geregistreerd. Probeer het a.u.b. nog een keer.';
$aLang['email_password_reminder_subject'] = STORE_NAME . ' - Nieuw wachtwoord voor het partnerprogramma';
$aLang['email_password_reminder_body'] = 'Via het adres ' . oos_server_get_remote() . ' hebben we het verzoek voor een wachtwoord vernieuwing gekregen voor toegang tot uw partnerprogramma.' . "\n\n" . 'Uw nieuwe wachtwoord voor toegang tot het partnerprogramma van \'' . STORE_NAME . '\' is vanaf nu:' . "\n\n" . '   %s' . "\n\n";
$aLang['text_password_sent'] = 'Een nieuw wachtwoord werd per email verstuurd.';
?>
