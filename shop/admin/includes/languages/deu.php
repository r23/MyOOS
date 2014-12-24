<?php
/* ----------------------------------------------------------------------
   $Id: deu.php,v 1.4 2009/08/17 14:22:11 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: german.php,v 1.95 2003/02/16 01:33:14 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

   
 /**
  * look in your $PATH_LOCALE/locale directory for available locales..
  * on RedHat try 'de_DE'
  * on FreeBSD try 'de_DE.ISO_8859-1'
  * on Windows try 'de' or 'German'
  */
  @setlocale(LC_TIME, 'de_DE');
  define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
  define('DATE_FORMAT', 'd.m.Y');  // this is used for strftime()
  define('PHP_DATE_TIME_FORMAT', 'd.m.Y H:i:s'); // this is used for date()
  define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');


 /**
  * Return date in raw format
  * $date should be in format mm/dd/yyyy
  * raw date is in format YYYYMMDD, or DDMMYYYY
  *
  * @param $date
  * @param $reverse
  * @return string
  */
  function oos_date_raw($date, $reverse = false) {
    if ($reverse) {
      return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
    } else {
      return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
    }
  }

// Global entries for the <html> tag
define('HTML_PARAMS',' lang="de"');

// charset for web pages and emails
define('CHARSET', 'utf-8');

// page title
define('TITLE', 'OSIS Online Shop');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administration');
define('HEADER_TITLE_SUPPORT_SITE', 'Supportseite');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online Katalog');
define('HEADER_TITLE_ADMINISTRATION', 'Administration');
define('HEADER_TITLE_LOGOFF', 'Logoff');

$aLang['header_title_top'] = 'Administration';
$aLang['header_title_support_site'] = 'Supportseite';
$aLang['header_title_online_catalog'] = 'Online Katalog';
$aLang['header_title_administration'] = 'Administration';
$aLang['header_title_account'] = 'Mein Konto';
$aLang['header_title_logoff'] = 'Abmelden';

// text for gender
define('MALE', 'Herr');
define('FEMALE', 'Frau');

// text for date of birth example
define('DOB_FORMAT_STRING', 'tt.mm.jjjj');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Konfiguration');
define('BOX_CONFIGURATION_MYSTORE', 'Mein Shop');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Module');
define('BOX_MODULES_PAYMENT', 'Zahlungsweise');
define('BOX_MODULES_SHIPPING', 'Versandart');
define('BOX_MODULES_ORDER_TOTAL', 'Zusammenfassung');

// plugins box text in includes/boxes/plugins.php
define('BOX_HEADING_PLUGINS', 'Plugins');
define('BOX_PLUGINS_EVENT', 'Event Plugins');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Katalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Kategorien / Artikel');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Produktmerkmale');
define('BOX_CATALOG_PRODUCTS_STATUS', 'Produktstatus');
define('BOX_CATALOG_PRODUCTS_UNITS', 'Einheiten');
define('BOX_CATALOG_MANUFACTURERS', 'Hersteller');
define('BOX_CATALOG_REVIEWS', 'Produktbewertungen');
define('BOX_CATALOG_SPECIALS', 'Sonderangebote');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'erwartete Artikel');
define('BOX_CATALOG_QADD_PRODUCT', 'Artikel hinzufgen');
define('BOX_CATALOG_PRODUCTS_FEATURED', 'Top-Angebote');
define('BOX_CATALOG_EASYPOPULATE', 'EasyPopulate');
define('BOX_CATALOG_EXPORT_EXCEL', 'Export Excel Product');
define('BOX_CATALOG_IMPORT_EXCEL', 'Update Excel Preis');
define('BOX_CATALOG_XSELL_PRODUCTS', 'Cross Sell Products');
define('BOX_CATALOG_UP_SELL_PRODUCTS', 'UP Sell Products');
define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Quick Stock Update');

// categories box text in includes/boxes/content.php
define('BOX_HEADING_CONTENT', 'Content Manager');
define('BOX_CONTENT_BLOCK', 'Block Manager');
define('BOX_CONTENT_NEWS', 'Nachrichten');
define('BOX_CONTENT_INFORMATION', 'Informationen');
define('BOX_CONTENT_PAGE_TYPE', 'Content Seiten Type');

// categories box text in includes/boxes/newsfeed.php
define('BOX_HEADING_NEWSFEED', 'News Feed');
define('BOX_NEWSFEED_MANAGER', 'News Feed Manager');
define('BOX_NEWSFEED_CATEGORIES', 'News Feed Kategorien');


// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Kunden');
define('BOX_CUSTOMERS_CUSTOMERS', 'Kunden');
define('BOX_CUSTOMERS_ORDERS', 'Bestellungen');
define('BOX_CAMPAIGNS', 'Kampagnen');
define('BOX_ADMIN_LOGIN', 'Admin login');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Land / Steuer');
define('BOX_TAXES_COUNTRIES', 'Land');
define('BOX_TAXES_ZONES', 'Bundesl&auml;nder');
define('BOX_TAXES_GEO_ZONES', 'Steuerzonen');
define('BOX_TAXES_TAX_CLASSES', 'Steuerklassen');
define('BOX_TAXES_TAX_RATES', 'Steuers&auml;tze');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Berichte');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'besuchte Artikel');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'gekaufte Artikel');
define('BOX_REPORTS_ORDERS_TOTAL', 'Kunden-Bestellstatistik');
define('BOX_REPORTS_STOCK_LEVEL', 'Lagerbestand');
define('BOX_REPORTS_SALES_REPORT2', 'Umsatzbericht');


// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Hilfsprogramme');
define('BOX_TOOLS_BACKUP', 'Datenbanksicherung');

define('BOX_TOOLS_DEFINE_LANGUAGE', 'Sprachen definieren');

define('BOX_EXPORT_PREISSUCHMASCHINE', 'Export preissuchmaschine.de');
define('BOX_TOOLS_MAIL', 'eMail versenden');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Rundschreiben Manager');
define('BOX_TOOLS_WHOS_ONLINE', 'Wer ist Online');
define('BOX_HEADING_ADMINISTRATORS', 'Administrators');
define('BOX_ADMINISTRATORS_SETUP', 'Set Up');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Sprachen/W&auml;hrungen');
define('BOX_LOCALIZATION_CURRENCIES', 'W&auml;hrungen');
define('BOX_LOCALIZATION_LANGUAGES', 'Sprachen');
define('BOX_LOCALIZATION_CUSTOMERS_STATUS', 'Kundengruppen');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Bestellstatus');


// export
define('BOX_HEADING_EXPORT', 'Export');
define('BOX_EXPORT_PREISSUCHMASCHINE', 'Export preissuchmaschine.de');
define('BOX_EXPORT_GOOGLEBASE', 'Googlebase');


//rss
define('BOX_HEADING_RSS', 'RSS');
define('BOX_RSS_CONF', 'RSS');

//information
define('BOX_HEADING_INFORMATION', 'Information');
define('BOX_INFORMATION', 'Information');

// javascript messages
define('JS_ERROR', 'Während der Eingabe sind Fehler aufgetreten!\nBitte korrigieren Sie folgendes:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* Sie müssen diesem Wert einen Preis zuordnen\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* Sie müssen ein Vorzeichen fr den Preis angeben (+/-)\n');

define('JS_PRODUCTS_NAME', '* Der neue Artikel muss einen Namen haben\n');
define('JS_PRODUCTS_DESCRIPTION', '* Der neue Artikel muss eine Beschreibung haben\n');
define('JS_PRODUCTS_PRICE', '* Der neue Artikel muss einen Preis haben\n');
define('JS_PRODUCTS_WEIGHT', '* Der neue Artikel muss eine Gewichtsangabe haben\n');
define('JS_PRODUCTS_QUANTITY', '* Sie müssen dem neuen Artikel eine verfgbare Anzahl zuordnen\n');
define('JS_PRODUCTS_MODEL', '* Sie müssen dem neuen Artikel eine Artikel-Nr. zuordnen\n');
define('JS_PRODUCTS_IMAGE', '* Sie müssen dem Artikel ein Bild zuordnen\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* Es muss ein neuer Preis für diesen Artikel festgelegt werden\n');

define('JS_GENDER', '* Die \'Anrede\' muss ausgewählt werden.\n');
define('JS_FIRST_NAME', '* Der \'Vorname\' muss mindestens aus ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_LAST_NAME', '* Der \'Nachname\' muss mindestens aus ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_DOB', '* Das \'Geburtsdatum\' muss folgendes Format haben: xx.xx.xxxx (Tag/Jahr/Monat).\n');
define('JS_EMAIL_ADDRESS', '* Die \'eMail-Adresse\' muss mindestens aus ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_ADDRESS', '* Die \'Strasse\' muss mindestens aus ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_POST_CODE', '* Die \'Postleitzahl\' muss mindestens aus ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_CITY', '* Die \'Stadt\' muss mindestens aus ' . ENTRY_CITY_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_STATE', '* Das \'Bundesland\' muss ausgewählt werden.\n');
define('JS_STATE_SELECT', '-- Wählen Sie oberhalb --');
define('JS_ZONE', '* Das \'Bundesland\' muss aus der Liste fr dieses Land ausgewählt werden.');
define('JS_COUNTRY', '* Das \'Land\' muss ausgewählt werden.\n');
define('JS_TELEPHONE', '* Die \'Telefonnummer\' muss aus mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zeichen bestehen.\n');
define('JS_PASSWORD', '* Das \'Passwort\' sowie die \'Passwortbestätigung\' müssen übereinstimmen und aus mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen bestehen.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Auftragsnummer %s existiert nicht!');

define('CATEGORY_PERSONAL', 'Pers&ouml;nliche Daten');
define('CATEGORY_ADDRESS', 'Adresse');
define('CATEGORY_CONTACT', 'Kontakt');
define('CATEGORY_PASSWORD', 'Passwort');
define('CATEGORY_COMPANY', 'Firma');
define('CATEGORY_OPTIONS', 'Optionen');
define('ENTRY_GENDER', 'Anrede:');
define('ENTRY_FIRST_NAME', 'Vorname:');
define('ENTRY_LAST_NAME', 'Nachname:');
define('ENTRY_NUMBER', 'Kundennummer:');
define('ENTRY_DATE_OF_BIRTH', 'Geburtsdatum:');
define('ENTRY_EMAIL_ADDRESS', 'eMail Adresse:');
define('ENTRY_COMPANY', 'Firmenname:');
define('ENTRY_OWNER', 'Inhaber:');
define('ENTRY_VAT_ID', 'Umsatzsteuer ID:');
define('ENTRY_STREET_ADDRESS', 'Strasse:');
define('ENTRY_SUBURB', 'Stadtteil:');
define('ENTRY_POST_CODE', 'Postleitzahl:');
define('ENTRY_CITY', 'Stadt:');
define('ENTRY_STATE', 'Bundesland:');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_TELEPHONE_NUMBER', 'Telefonnummer:');
define('ENTRY_FAX_NUMBER', 'Telefaxnummer:');
define('ENTRY_NEWSLETTER', 'Rundschreiben:');
define('ENTRY_NEWSLETTER_YES', 'abonniert');
define('ENTRY_NEWSLETTER_NO', 'nicht abonniert');
define('ENTRY_PASSWORD', 'Passwort:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Passwortbest&auml;tigung:');
define('PASSWORD_HIDDEN', '--VERSTECKT--');

// images
define('IMAGE_ANI_SEND_EMAIL', 'eMail versenden');
define('IMAGE_BACK', 'Zur&uuml;ck');
define('IMAGE_BACKUP', 'Datensicherung');
define('IMAGE_CANCEL', 'Abbruch');
define('IMAGE_CONFIRM', 'Best&auml;tigen');
define('IMAGE_COPY', 'Kopieren');
define('IMAGE_COPY_TO', 'Kopieren nach');
define('IMAGE_DEFINE', 'Definieren');
define('IMAGE_DELETE', 'L&ouml;schen');
define('IMAGE_EDIT', 'Bearbeiten');
define('IMAGE_EMAIL', 'eMail versenden');
define('IMAGE_FEATURED', 'Top-Angebote');
define('IMAGE_FILE_MANAGER', 'Datei-Manager');
define('IMAGE_ICON_STATUS_GREEN', 'aktiv');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'aktivieren');
define('IMAGE_ICON_STATUS_RED', 'inaktiv');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'deaktivieren');
define('IMAGE_ICON_INFO', 'Information');
define('IMAGE_INSERT', 'Einf&uuml;gen');
define('IMAGE_LOCK', 'Sperren');
define('IMAGE_MOVE', 'Verschieben');
define('IMAGE_NEW_BANNER', 'Neuen Banner aufnehmen');
define('IMAGE_NEW_CATEGORY', 'Neue Kategorie erstellen');
define('IMAGE_NEW_COUNTRY', 'Neues Land aufnehmen');
define('IMAGE_NEW_CURRENCY', 'Neue W&auml;hrung einf&uuml;gen');
define('IMAGE_NEW_FILE', 'Neue Datei');
define('IMAGE_NEW_FOLDER', 'Neues Verzeichnis');
define('IMAGE_NEW_LANGUAGE', 'Neue Sprache anlegen');
define('IMAGE_NEW_NEWS', 'Neue News erstellen');
define('IMAGE_NEW_NEWSLETTER', 'Neues Rundschreiben');
define('IMAGE_NEW_PRODUCT', 'Neuen Artikel aufnehmen');
define('IMAGE_NEW_TAX_CLASS', 'Neue Steuerklasse erstellen');
define('IMAGE_NEW_TAX_RATE', 'Neuen Steuersatz anlegen');
define('IMAGE_NEW_TAX_ZONE', 'Neue Steuerzone erstellen');
define('IMAGE_ORDERS', 'Bestellungen');
define('IMAGE_ORDERS_INVOICE', 'Rechnung');
define('IMAGE_ORDERS_PACKINGSLIP', 'Lieferschein');
define('IMAGE_ORDERS_WEBPRINTER', 'WebPrinter');
define('IMAGE_PLUGINS_INSTALL', 'Plugins Installieren');
define('IMAGE_PLUGINS_REMOVE', 'Plugins Entfernen');
define('IMAGE_PREVIEW', 'Vorschau');
define('IMAGE_RESET', 'Zur&uuml;cksetzen');
define('IMAGE_RESTORE', 'Zur&uuml;cksichern');
define('IMAGE_SAVE', 'Speichern');
define('IMAGE_SEARCH', 'Suchen');
define('IMAGE_SELECT', 'Ausw&auml;hlen');
define('IMAGE_SEND', 'Versenden');
define('IMAGE_SEND_EMAIL', 'eMail versenden');
define('IMAGE_SPECIALS', 'Sonderangebot');
define('IMAGE_STATUS', 'Kundengruppe');
define('IMAGE_UNLOCK', 'Entsperren');
define('IMAGE_UPDATE', 'Aktualisieren');
define('IMAGE_UPDATE_CURRENCIES', 'Wechselkurse aktualisieren');
define('IMAGE_UPLOAD', 'Hochladen');
define('IMAGE_WISHLIST', 'Wunschzettel');

$aLang['image_new_tax_rate'] = 'Neuen Steuersatz anlegen';
$aLang['image_new_zone'] = 'Neues Bundesland einf&uuml;gen';


define('ICON_CROSS', 'Falsch');
define('ICON_CURRENT_FOLDER', 'aktueller Ordner');
define('ICON_DELETE', 'L&ouml;schen');
define('ICON_ERROR', 'Fehler');
define('ICON_FILE', 'Datei');
define('ICON_FILE_DOWNLOAD', 'Herunterladen');
define('ICON_FOLDER', 'Ordner');
define('ICON_LOCKED', 'Gesperrt');
define('ICON_PREVIOUS_LEVEL', 'Vorherige Ebene');
define('ICON_PREVIEW', 'Vorschau');
define('ICON_STATISTICS', 'Statistik');
define('ICON_SUCCESS', 'Erfolg');
define('ICON_TICK', 'Wahr');
define('ICON_UNLOCKED', 'Entsperrt');
define('ICON_WARNING', 'Warnung');

// constants for use in oos_prev_next_display function
define('TEXT_RESULT_PAGE', 'Seite %s von %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bannern)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> L&auml;ndern)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Kunden)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> W&auml;hrungen)');
define('TEXT_DISPLAY_NUMBER_OF_HTTP_REFERERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> HTTP Referers)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Sprachen)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Herstellern)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Rundschreiben)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bestellungen)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bestellstatus)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Artikeln)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> erwarteten Artikeln)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_UNITS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Einheiten)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bewertungen)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Sonderangeboten)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuerklassen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuerzonen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuers&auml;tzen)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bundesl&auml;ndern)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Kundengruppen)');
define('TEXT_DISPLAY_NUMBER_OF_BLOCKES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Boxen)');
define('TEXT_DISPLAY_NUMBER_OF_RSS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> )');
define('TEXT_DISPLAY_NUMBER_OF_NEWSFEED_CATEGORIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Kategorien)');
define('TEXT_DISPLAY_NUMBER_OF_PAGE_TYPES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> )');
define('TEXT_DISPLAY_NUMBER_OF_INFORMATION', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Information)');


define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'Standard');
define('TEXT_SET_DEFAULT', 'als Standard definieren');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* erforderlich</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Fehler: Es wurde keine Standardw&auml;hrung definiert. Bitte definieren Sie unter Adminstration -> Sprachen/W&auml;hrungen -> W&auml;hrungen eine Standardw&auml;hrung.');
define('ERROR_USER_FOR_THIS_PAGE', 'Fehler: Sie haben f&uuml;r diesen Bereich keine Zugangsrechte.');

define('TEXT_INFO_USER_NAME', 'UserName:');
define('TEXT_INFO_PASSWORD', 'Password:');

define('TEXT_NONE', '--keine--');
define('TEXT_TOP', 'Top');

define('ENTRY_YES','ja');
define('ENTRY_NO','nein');

// reports box text in includes/boxes/affiliate.php
define('BOX_HEADING_AFFILIATE', 'Partnerprogramm');
define('BOX_AFFILIATE_SUMMARY', 'Zusammenfassung');
define('BOX_AFFILIATE', 'Partner');
define('BOX_AFFILIATE_PAYMENT', 'Provisionszahlungen');
define('BOX_AFFILIATE_BANNERS', 'Banner');
define('BOX_AFFILIATE_CONTACT', 'Kontakt');
define('BOX_AFFILIATE_SALES', 'Partner Verk&auml;ufe');
define('BOX_AFFILIATE_CLICKS', 'Klicks');

define ('BOX_HEADING_TICKET','Supporttickets');
define ('BOX_TICKET_VIEW','Tickets');
define ('BOX_TEXT_ADMIN','Admins');
define ('BOX_TEXT_DEPARTMENT','Departments');
define ('BOX_TEXT_PRIORITY','Pririt&auml;ten');
define ('BOX_TEXT_REPLY','Antworten');
define ('BOX_TEXT_STATUS','Statuse');

define('BOX_HEADING_GV_ADMIN', 'Gutscheine');
define('BOX_GV_ADMIN_QUEUE', 'Gutschein Queue');
define('BOX_GV_ADMIN_MAIL', 'Gutschein eMail');
define('BOX_GV_ADMIN_SENT', 'Gutscheine versandt');
define('BOX_HEADING_COUPON_ADMIN','Rabattkupons');
define('BOX_COUPON_ADMIN','Kupon Administrator');

define('IMAGE_RELEASE', 'Gutschein einl&ouml;sen');

define('_JANUARY', 'Januar');
define('_FEBRUARY', 'Februar');
define('_MARCH', 'M&auml;rz');
define('_APRIL', 'April');
define('_MAY', 'Mai');
define('_JUNE', 'Juni');
define('_JULY', 'Juli');
define('_AUGUST', 'August');
define('_SEPTEMBER', 'September');
define('_OCTOBER', 'Oktober');
define('_NOVEMBER', 'November');
define('_DECEMBER', 'Dezember');

define('TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Gutscheinen)');
define('TEXT_DISPLAY_NUMBER_OF_COUPONS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von <b>%d</b> Gutscheinen)');

define('TEXT_VALID_PRODUCTS_LIST', 'Produkt Liste');
define('TEXT_VALID_PRODUCTS_ID', 'Produkt ID');
define('TEXT_VALID_PRODUCTS_NAME', 'Produkt Name');
define('TEXT_VALID_PRODUCTS_MODEL', 'Produkt Model');

define('TEXT_VALID_CATEGORIES_LIST', 'Kategorie Liste');
define('TEXT_VALID_CATEGORIES_ID', 'Kategorie ID');
define('TEXT_VALID_CATEGORIES_NAME', 'Kategorie Name');

define('HEADER_TITLE_TOP', 'Redaktion');
define('HEADER_TITLE_ADMINISTRATION', 'Redaktion');

define('HEADER_TITLE_ACCOUNT', 'Mein Konto');
define('HEADER_TITLE_LOGOFF', 'Abmelden');

// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'Mein Konto');
define('BOX_MY_ACCOUNT', 'Mein Konto');
define('BOX_MY_ACCOUNT_LOGOFF', 'Abmelden');

// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Redakteure');
define('BOX_ADMINISTRATOR_MEMBERS', 'Gruppenmitglieder');
define('BOX_ADMINISTRATOR_MEMBER', 'Mitglieder');
define('BOX_ADMINISTRATOR_BOXES', 'Dateizugriff');

// images
define('IMAGE_FILE_PERMISSION', 'Dateizugriffs-Erlaubnis');
define('IMAGE_GROUPS', 'Gruppenliste');
define('IMAGE_INSERT_FILE', 'Datei einfgen');
define('IMAGE_MEMBERS', 'Gruppenliste');
define('IMAGE_NEW_GROUP', 'Neue Gruppe');
define('IMAGE_NEW_MEMBER', 'Neues Mitglied');
define('IMAGE_NEXT', 'N?hster');

// constants for use in oosPrevNextDisplay function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Zeige an <b>%d</b> bis <b>%d</b> (von <b>%d</b> Dateinamen)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Zeige an <b>%d</b> bis <b>%d</b> (von <b>%d</b> Mitglieder)');


define('PULL_DOWN_DEFAULT', 'Bitte wählen');

define('BOX_REPORTS_RECOVER_CART_SALES', 'Recover Carts');
define('BOX_TOOLS_RECOVER_CART', 'Recover Carts');

// BOF: WebMakers.com Added: All Add-Ons
// Download Controller
// Add a new Order Status to the orders_status table - Updated
define('ORDERS_STATUS_UPDATED_VALUE','4'); // set to the Updated status to update max days and max count

// Quantity Definitions
require('includes/languages/' . $_SESSION['language'] . '/' . 'quantity_control.php');

