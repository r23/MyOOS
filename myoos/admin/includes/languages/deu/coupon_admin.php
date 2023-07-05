<?php
/**
   ----------------------------------------------------------------------
   $Id: coupon_admin.php,v 1.8 2008/01/20 12:42:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: coupon_admin.php,v 1.1.2.2 2003/05/15 23:10:55 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('TOP_BAR_TITLE', 'Statistik');
define('HEADING_TITLE', 'Gutscheine');
define('HEADING_TITLE_STATUS', 'Status : ');
define('TEXT_CUSTOMER', 'Kunde:');
define('TEXT_COUPON', 'Gutschein Name');
define('TEXT_COUPON_ALL', 'Alle Gutscheine');
define('TEXT_COUPON_ACTIVE', 'Aktive Gutscheine');
define('TEXT_COUPON_INACTIVE', 'Inaktive Gutscheine');
define('TEXT_SUBJECT', 'Betreff:');
define('TEXT_FROM', 'Von:');
define('TEXT_FREE_SHIPPING', 'Versandkostenfrei');
define('TEXT_MESSAGE', 'Mitteilung:');
define('TEXT_SELECT_CUSTOMER', 'Kunde auswählen');
define('TEXT_ALL_CUSTOMERS', 'Alle Kunden');
define('TEXT_NEWSLETTER_CUSTOMERS', 'An alle Newsletter Abonnenten');
define('TEXT_CONFIRM_DELETE', 'Sind Sie sicher, dass Sie diesen Gutschein löschen möchten?');
define('TEXT_TO_REDEEM', 'Sie können den Gutschein bei Ihrer Bestellung einlösen. Geben Sie dafür Ihren Gutschein-Nummer in das Feld Gutscheine ein.');
define('TEXT_IN_CASE', ' im Falle dass Sie Probleme haben.');
define('TEXT_VOUCHER_IS', 'Ihre Gutschein-Nummer lautet: ');
define('TEXT_REMEMBER', 'Heben Sie Ihre Gutschein-Nummer gut auf, nur so können Sie von diesem Angebot profitieren!');
define('TEXT_VISIT', 'Besuchen Sie unsere Seite: ' . OOS_HTTPS_SERVER . OOS_SHOP);
define('TEXT_ENTER_CODE', ' und geben Sie die Gutschein-Nummer ein.');

define('TABLE_HEADING_ACTION', 'Aktion');

define('CUSTOMER_ID', 'Kunden-Nummer');
define('CUSTOMER_NAME', 'Kunden Name');
define('REDEEM_DATE', 'Einlösedatum');
define('IP_ADDRESS', 'IP Adresse');

define('TEXT_REDEMPTIONS', 'Einlöseoptionen');
define('TEXT_REDEMPTIONS_TOTAL', 'Gesamtsumme');
define('TEXT_REDEMPTIONS_CUSTOMER', 'Für diesen Kunden');
define('TEXT_NO_FREE_SHIPPING', 'Nicht versandkostenfrei.');

define('NOTICE_EMAIL_SENT_TO', 'Zu Ihrer Information! Es wurde eine E-Mail an %s gesendet.');
define('ERROR_NO_CUSTOMER_SELECTED', 'Fehler: Sie haben keinen Kunden ausgewählt!');
define('COUPON_NAME', 'Gutschein Name');

define('COUPON_AMOUNT', 'Gutscheinwert');
define('COUPON_CODE', 'Gutscheincode');
define('COUPON_STARTDATE', 'Startdatum');
define('COUPON_FINISHDATE', 'Enddatum');
define('COUPON_FREE_SHIP', 'Versandkostenfrei');
define('COUPON_DESC', 'Gutscheinbeschreibung');
define('COUPON_MIN_ORDER', 'Gutschein Mindestbestellwert');
define('COUPON_USES_COUPON', 'Gutschein wie oft einlösbar?');
define('COUPON_USES_USER', 'Gutschein je Kunde?');
define('COUPON_PRODUCTS', 'Zulässige Produkte');
define('COUPON_CATEGORIES', 'Zulässige Kategorien');
define('VOUCHER_NUMBER_USED', 'Zu benutzende Nummern');
define('DATE_CREATED', 'Erstelldatum');
define('DATE_MODIFIED', 'Änderungsdatum');
define('TEXT_HEADING_NEW_COUPON', 'Erzeuge neuen Gutschein');
define('TEXT_NEW_INTRO', 'Bitte geben Sie die folgenden Informationen ein, um einen neuen Gutschein zu erzeugen.<br>');

define('ERROR_NO_COUPON_AMOUNT', 'Fehler: Kein Gutschein Wert festgelegt.');
define('ERROR_NO_COUPON_NAME', 'Fehler: Kein Gutschein Name angegeben.');
define('ERROR_COUPON_EXISTS', 'Fehler: Der Gutscheincode existiert schon.');


define('TEXT_FROM_NAME', 'Absender Name:');
define('TEXT_FROM_MAIL', 'Absender eMail:');

define('COUPON_NAME_HELP', 'Ein kurzer Name für den Gutschein.');
define('COUPON_AMOUNT_HELP', 'Geben Sie einen Gutscheinwert an. Entweder einen bestimmten Betrag oder ein Prozentzeichen (%) am Ende für einen prozentualen Nachlass.');
define('COUPON_CODE_HELP', 'Sie können hier einen Gutscheincode eingeben, oder das Feld leer lassen. Es wird dann ein automatisch erzeugter Gutscheincode verwendet.');
define('COUPON_STARTDATE_HELP', 'Ab wann ist der Gutschein gültig? ');
define('COUPON_FINISHDATE_HELP', 'Bis wann ist der Gutschein gültig? ');
define('COUPON_FREE_SHIP_HELP', 'Mit diesen Gutschein kann der Kunde versandkostenfrei bestellen! Bitte beachten Sie: Diese Auswahl überschreibt den Gutscheinwert, berücksichtigt jedoch den Mindestbestellwert!');
define('COUPON_DESC_HELP', 'Eine Gutscheinbeschreibung für den Kunden.');
define('COUPON_MIN_ORDER_HELP', 'Einen Mindestbestellwert eingeben. Unterhalb dieses Wertes wird der Gutschein nicht eingelöst!');
define('COUPON_USES_COUPON_HELP', 'Wie oft kann der Gutschein benutzt werden? Soll die Anzahl unlimitiert möglich sein, lassen Sie das Feld leer.');
define('COUPON_USES_USER_HELP', 'Wie oft kann ein Kunde den Gutschein benutzen? Soll die Anzahl unlimitiert möglich sein, lassen Sie das Feld leer.');
define('COUPON_PRODUCTS_HELP', 'Ein Liste von erlaubten Produkt-IDs (mit Komma getrennt). Lassen Sie dieses Feld leer, falls Sie keine Beschränkungen machen wollen.');
define('COUPON_CATEGORIES_HELP', 'Eine Liste von erlaubten Kategorien (mit Komma getrennt). Lassen Sie dieses Feld leer, falls Sie keine Beschränkungen machen wollen.');
