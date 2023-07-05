<?php
/**
   ----------------------------------------------------------------------
   $Id: admin_account.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_members.php,v 1.13 2002/08/19 01:45:58 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Redaktionskonten');

define('TABLE_HEADING_ACCOUNT', 'Mein Konto');

define('TEXT_INFO_FULLNAME', '<b>Name: </b>');
define('TEXT_INFO_FIRSTNAME', '<b>Vorname: </b>');
define('TEXT_INFO_LASTNAME', '<b>Nachname: </b>');
define('TEXT_INFO_EMAIL', '<b>Email Adresse: </b>');
define('TEXT_INFO_PASSWORD_HIDDEN', '-Versteckt-');
define('TEXT_INFO_PASSWORD_CONFIRM', '<b>Bestätige Passwort: </b>');
define('TEXT_INFO_CREATED', '<b>Konto angelegt: </b>');
define('TEXT_INFO_LOGDATE', '<b>Letzter Zugriff: </b>');
define('TEXT_INFO_LOGNUM', '<b>Zugriffs-Nummer: </b>');
define('TEXT_INFO_GROUP', '<b>Gruppenstufe: </b>');
define('TEXT_INFO_ERROR', '<font color="red">Email-Adresse wird schon verwendet! Versuchen Sie es erneut.</font>');
define('TEXT_INFO_MODIFIED', 'Verändert: ');

define('TEXT_INFO_HEADING_DEFAULT', 'Bearbeite Konto ');
define('TEXT_INFO_HEADING_CONFIRM_PASSWORD', 'Passwort Bestätigung ');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD', 'Passwort:');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR', '<font color="red"><b>FEHLER:</b> falsches Passwort!</font>');
define('TEXT_INFO_INTRO_DEFAULT', 'Klicken Sie den <b>Bearbeiten Button</b> unten um das Konto zu ändern.');
define('TEXT_INFO_INTRO_DEFAULT_FIRST_TIME', '<br><b>WARNUNG:</b><br>Hallo <b>%s</b>, sie haben sich hier zum ersten mal angemeldet. Wir empfehlen Ihnen Ihr Passwort zu ändern!');
define('TEXT_INFO_INTRO_DEFAULT_FIRST', '<br><b>WARNUNG:</b><br>Hallo <b>%s</b>, wir empfehlen Ihnen Ihre E-Mail Adresse zu änderndern (<font color="red">admin@localhost</font>) und das Passwort!');
define('TEXT_INFO_INTRO_EDIT_PROCESS', 'Sämtliche Felder werden benötigt. Klicken sie sichern zur Datenbertragung.');

define('JS_ALERT_FIRSTNAME', '- Benötigt: Vorname \n');
define('JS_ALERT_LASTNAME', '- Benötigt: Nachname \n');
define('JS_ALERT_EMAIL', '- Benötigt: Email-Adresse \n');
define('JS_ALERT_PASSWORD', '- Benötigt: Passwort \n');
define('JS_ALERT_FIRSTNAME_LENGTH', '- Die Länge des Vornamens muss über ');
define('JS_ALERT_LASTNAME_LENGTH', '- Die Länge des Nachnamens muss über ');
define('JS_ALERT_PASSWORD_LENGTH', '- Die Länge des Passwortes muss über  ');
define('JS_ALERT_EMAIL_FORMAT', '- Das Format der Email-Adresse ist ungültig! \n');
define('JS_ALERT_EMAIL_USED', '- Diese Email-Adresse wird schon verwendet! \n');
define('JS_ALERT_PASSWORD_CONFIRM', '- Im Passwort-Bestätigungsfeld ist keine Eintragung vorgenommen worden! \n');
