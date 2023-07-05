<?php
/**
   ----------------------------------------------------------------------
   $Id: customers.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers.php,v 1.13 2002/06/15 12:19:14 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */
/**
   ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Kunden');
define('HEADING_TITLE_SEARCH', 'Suche:');

define('TABLE_HEADING_FIRSTNAME', 'Vorname');
define('TABLE_HEADING_LASTNAME', 'Nachname');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Zugang erstellt am');
define('TABLE_HEADING_ACTION', 'Aktion');
define('HEADING_TITLE_STATUS', 'Status:');
define('TEXT_ALL_CUSTOMERS', 'Alle Kunden');
define('HEADING_TITLE_LOGIN', 'Zugang');
define('HEADING_TITLE_2FA_LOGIN', '2FA');

define('TEXT_INFO_HEADING_STATUS_CUSTOMER', 'Kundenstatus ändern');
define('TEXT_NO_CUSTOMER_HISTORY', 'Keine Kundenstatus History vorhanden');
define('TABLE_HEADING_NEW_VALUE', 'Neuer Status');
define('TABLE_HEADING_OLD_VALUE', 'Alter Status');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Kunde benachrichtigt');
define('TABLE_HEADING_DATE_ADDED', 'Hinzugefügt am:');

define('CATEGORY_MAX_ORDER', 'Max. Bestellwert');
define('ENTRY_MAX_ORDER', 'Kundenkredit:');

define('ENTRY_VAT_ID_STATUS', 'UmsatzsteuerID geprüft');
define('ENTRY_VAT_ID_STATUS_YES', 'ja');
define('ENTRY_VAT_ID_STATUS_NO', 'nein');

define('TEXT_DATE_ACCOUNT_CREATED', 'Zugang erstellt am:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'letzte Änderung:');
define('TEXT_INFO_DATE_LAST_LOGON', 'letzte Anmeldung:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Anzahl der Anmeldungen:');
define('TEXT_INFO_COUNTRY', 'Land:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'Anzahl der Artikelbewertungen:');
define('TEXT_DELETE_INTRO', 'Wollen Sie diesen Kunden wirklich löschen?');
define('TEXT_DELETE_REVIEWS', '%s Bewertung(en) löschen');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Kunden löschen');
define('TYPE_BELOW', 'Bitte unten eingeben');
define('PLEASE_SELECT', 'Auswählen');

define('EMAIL_SUBJECT', 'Willkommen bei ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Sehr geehrter Herr ');
define('EMAIL_GREET_MS', 'Sehr geehrte Frau ');
define('EMAIL_GREET_NONE', 'Sehr geehrte Damen und Herren,');
define('EMAIL_WELCOME', 'willkommen bei <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Sie können jetzt unseren <b>Online-Service</b> nutzen. Der Service bietet unter anderem:' . "\n\n" . '<li><b>Kundenwarenkorb</b> - Jeder Artikel bleibt registriert bis Sie zur Kasse gehen, oder die Produkte aus dem Warenkorb entfernen.' . "\n" . '<li><b>Adressbuch</b> - Wir können jetzt die Produkte zu der von Ihnen ausgesuchten Adresse senden. Der perfekte Weg ein Geburtstagsgeschenk zu versenden.' . "\n" . '<li><b>Vorherige Bestellungen</b> - Sie können jederzeit Ihre vorherigen Bestellungen überprüfen.' . "\n" . '<li><b>Meinungen über Produkte</b> - Teilen Sie Ihre Meinung zu unseren Produkten mit anderen Kunden.' . "\n\n");
define('EMAIL_CONTACT', 'Falls Sie Fragen zu unserem Kunden-Service haben, wenden Sie sich bitte an den Vertrieb: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Achtung:</b> Diese eMail-Adresse wurde uns von einem Kunden bekannt gegeben. Falls Sie sich nicht angemeldet haben, senden Sie bitte eine eMail an ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_PASSWORD_BODY', 'Ihr Passwort lautet:' . "\n\n" . '   %s' . "\n\n");

define('EMAIL_GV_INCENTIVE_HEADER', 'Um für Sie als Neukunden zu begrüßen, haben wir Ihnen einen Gutschein über %s gesendet.');
define('EMAIL_GV_REDEEM', 'Der Gutscheincode lautet: %s. Sie können diesen, beim Abschluß Ihrer Bestellung eingeben');
define('EMAIL_GV_LINK', 'Oder Sie benutzen den folgenden Link: ');
define(
    'EMAIL_COUPON_INCENTIVE_HEADER',
    'Herzlichen Glückwunsch! Um den ersten Besuch in unserm Shop attraktiver zu machen erhalten Sie diesen Gutschein!' . "\n" .
    'Es folgen weitere Details über Ihren persönlichen Einkaufsgutschein.' . "\n\n"
);
define(
    'EMAIL_COUPON_REDEEM',
    'Um den Einkaufsgutschein zu nutzen geben Sie bitte den Gutscheincode %s ' . "\n" .
    'beim Beenden Ihrer Bestellung ein!'
);
