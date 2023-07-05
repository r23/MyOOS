<?php
/**
   ----------------------------------------------------------------------
   $Id: gv_mail.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_mail.php,v 1.1.2.1 2003/05/15 23:10:55 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Gutschein an Kunden versenden');

define('TEXT_CUSTOMER', 'Kunde:');
define('TEXT_SUBJECT', 'Betreff:');
define('TEXT_FROM', 'Absender:');
define('TEXT_TO', 'eMail an:');
define('TEXT_AMOUNT', 'Betrag:');
define('TEXT_MESSAGE', 'Nachricht:');
define('TEXT_SELECT_CUSTOMER', 'Kunden auswählen');
define('TEXT_ALL_CUSTOMERS', 'Alle Kunden');
define('TEXT_NEWSLETTER_CUSTOMERS', 'An alle Newsletter-Abonnenten');
define('TEXT_FROM_NAME', 'Absender Name:');
define('TEXT_FROM_MAIL', 'Absender eMail:');

define('NOTICE_EMAIL_SENT_TO', 'Hinweis: eMail wurde versendet an: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Fehler: Es wurde kein Kunde ausgewählt.');
define('ERROR_NO_AMOUNT_SELECTED', 'Fehler: Sie haben keinen Betrag für den Gutschein eingegeben.');

define('TEXT_GV_WORTH', 'Gutscheinwert ');
define('TEXT_TO_REDEEM', 'Um den Gutschein einzulösen, klicken Sie auf den unten stehenden Link. Bitte notieren Sie sich den Gutschein-Code.');
define('TEXT_WHICH_IS', 'welcher ist');
define('TEXT_IN_CASE', ' falls Sie Probleme haben.');
define('TEXT_OR_VISIT', 'oder besuchen Sie ');
define('TEXT_ENTER_CODE', ' und geben den Gutschein-Code ein ');

define('TEXT_REDEEM_COUPON_MESSAGE_HEADER', 'Sie haben erfolgreich einen Gutschein von unserem Shop erworben. Aus Sicherheitsgrnden wir der Gutscheinwert nicht sofort Ihrem Konto gutgeschrieben. Der Shop-Besitzer wurde ber den Erwerb informiert.');
define('TEXT_REDEEM_COUPON_MESSAGE_AMOUNT', "\n\n" . 'Der Wert des Gutscheins beträgt: %s');
define('TEXT_REDEEM_COUPON_MESSAGE_BODY', "\n\n" . 'Sie können nun unsere Seite besuchen, sich einloggen und den Gutschein an jeden beliebigen Empfänger versenden.');
define('TEXT_REDEEM_COUPON_MESSAGE_FOOTER', "\n\n");
