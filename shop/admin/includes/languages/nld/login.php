<?php
/* ----------------------------------------------------------------------
   $Id: login.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.11 2002/06/03 13:19:42 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_RETURNING_ADMIN', 'Aanmeldveld:');

define('TEXT_RETURNING_ADMIN', 'Alleen voor medewerkers!');
define('ENTRY_EMAIL_ADDRESS', 'Emailadres:');
define('ENTRY_PASSWORD', 'Wachtwoord:');
define('ENTRY_FIRSTNAME', 'Voornaam:');
define('IMAGE_BUTTON_LOGIN', 'Invoeren');

define('SECURITYCODE', 'Veiligheidscode:');
define('TEXT_PASSWORD_FORGOTTEN', 'Wachtwoord vergeten?');
define('TEXT_WELCOME', 'Welcome to <br />OOS [OSIS Online Shop]!</p><p>Use a valid eMail and password to gain access to the administration console.');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> Verkeerde gebruikersnaam of wachtwoord!');
define('TEXT_FORGOTTEN_ERROR', '<font color="#ff0000"><b>FEHLER:</b></font> Voornaam en wachtwoord zijn niet ingevuld!');
define('TEXT_FORGOTTEN_FAIL', 'U hebt het meer als 3x geprobeerd. Uit veiligheidsoverweging dient u contact op te nemen met de beheerder om een nieuw wachtwoord aan te vragen.');
define('TEXT_FORGOTTEN_SUCCESS', 'het nieuwe wachtwoord werd per email naar uw emailadres verstuurd. Controleer uw email en klik naar ons terug om u aan te melden.');

define('ADMIN_EMAIL_SUBJECT', 'Nieuw wachtwoord'); 
define('ADMIN_EMAIL_TEXT', 'Hallo %s,' . "\n\n" . 'U kan het beheerdersgedeelte met volgende wachtwoord binnenkomen. Verander na succesvol inloggen uit veiligheidsoverweging uw wachtwoord!' . "\n\n" . 'Website : %s' . "\n" . 'Gebruikersnaam: %s' . "\n" . 'Wachtwoord: %s' . "\n\n" . 'Bedankt!' . "\n" . '%s' . "\n\n" . 'Dit is een automatisch beantwoorde email. Niet hier op antwoorden s.v.p.!'); 
?>
