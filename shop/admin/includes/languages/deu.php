<?php
/* ----------------------------------------------------------------------
   $Id: deu.php 475 2013-07-13 08:22:26Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
define('HTML_PARAMS','dir="ltr" lang="de"');
define('LANG', 'de');

// charset for web pages and emails
define('CHARSET', 'UTF-8');

// page title
define('TITLE', 'MyOOS [Shopsystem]');
$aLang['page_title'] = 'MyOOS';
$aLang['page_headline'] = 'kostenlos, intuitiv, einfach';

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administration');
define('HEADER_TITLE_SUPPORT_SITE', 'Supportseite');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online Shop');
define('HEADER_TITLE_ADMINISTRATION', 'Administration');

$aLang['header_title_top'] = 'Administration';
$aLang['header_title_support_site'] = 'Supportseite';
$aLang['header_title_online_catalog'] = 'Online Shop';
$aLang['header_title_administration'] = 'Administration';
$aLang['header_title_account'] = 'Mein Konto';
$aLang['header_title_logoff'] = 'Abmelden';

$aLang['collapse_menu'] = 'Menue ein/ausklappen';
$aLang['login'] = 'Anmelden';


// text for gender
define('MALE', 'Herr');
define('FEMALE', 'Frau');

// text for date of birth example
define('DOB_FORMAT_STRING', 'tt.mm.jjjj');

// configuration box text in includes/boxes/configuration.php
$aLang['box_heading_configuration'] = 'Konfiguration';
define('BOX_HEADING_CONFIGURATION', 'Konfiguration');
define('BOX_CONFIGURATION_MYSTORE', 'Mein Shop');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// modules box text in includes/boxes/modules.php
$aLang['box_heading_modules'] = 'Module';
define('BOX_HEADING_MODULES', 'Module');
define('BOX_MODULES_PAYMENT', 'Zahlungsweise');
define('BOX_MODULES_SHIPPING', 'Versandart');
define('BOX_MODULES_ORDER_TOTAL', 'Zusammenfassung');

// plugins box text in includes/boxes/plugins.php
$aLang['box_heading_plugins'] = 'Event Plugins';
define('BOX_HEADING_PLUGINS', 'Plugins');
define('BOX_PLUGINS_EVENT', 'Event Plugins');

// categories box text in includes/boxes/catalog.php
$aLang['box_heading_catalog'] = 'Artikelkatalog';
define('BOX_HEADING_CATALOG', 'Artikelkatalog');
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
$aLang['box_heading_content'] = 'Content Manager';
define('BOX_HEADING_CONTENT', 'Content Manager');
define('BOX_CONTENT_BLOCK', 'Block Manager');
define('BOX_CONTENT_INFORMATION', 'Informationen');
define('BOX_CONTENT_PAGE_TYPE', 'Content Seiten Type');

// customers box text in includes/boxes/customers.php
$aLang['box_heading_customers'] = 'Kunden';
define('BOX_HEADING_CUSTOMERS', 'Kunden');
define('BOX_CUSTOMERS_CUSTOMERS', 'Kunden');
define('BOX_CUSTOMERS_ORDERS', 'Bestellungen');
define('BOX_CAMPAIGNS', 'Kampagnen');
define('BOX_ADMIN_LOGIN', 'Admin login');

// gv_admin
$aLang['box_heading_gv_admin'] = 'Gutscheine';
define('BOX_HEADING_GV_ADMIN', 'Gutscheine');
define('BOX_GV_ADMIN_QUEUE', 'Gutschein Queue');
define('BOX_GV_ADMIN_MAIL', 'Gutschein eMail');
define('BOX_GV_ADMIN_SENT', 'Gutscheine versandt');
define('BOX_HEADING_COUPON_ADMIN','Rabattkupons');
define('BOX_COUPON_ADMIN','Kupon Administrator');

// taxes box text in includes/boxes/taxes.php
$aLang['box_heading_location_and_taxes'] = 'Land / Steuer';
define('BOX_HEADING_LOCATION_AND_TAXES', 'Land / Steuer');
define('BOX_TAXES_COUNTRIES', 'Land');
define('BOX_TAXES_ZONES', 'Bundesländer');
define('BOX_TAXES_GEO_ZONES', 'Steuerzonen');
define('BOX_TAXES_TAX_CLASSES', 'Steuerklassen');
define('BOX_TAXES_TAX_RATES', 'Steuersätze');

// reports box text in includes/boxes/reports.php
$aLang['box_heading_reports'] = 'Berichte';
define('BOX_HEADING_REPORTS', 'Berichte');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'besuchte Artikel');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'gekaufte Artikel');
define('BOX_REPORTS_ORDERS_TOTAL', 'Kunden-Bestellstatistik');
define('BOX_REPORTS_STOCK_LEVEL', 'Lagerbestand');
define('BOX_REPORTS_SALES_REPORT2', 'Umsatzbericht');
define('BOX_REPORTS_KEYWORDS', 'Keyword Manager');
define('BOX_REPORTS_REFERER' , 'HTTP Referers');

// tools text in includes/boxes/tools.php
$aLang['box_heading_tools'] = 'Hilfsprogramme';
define('BOX_HEADING_TOOLS', 'Hilfsprogramme');
define('BOX_TOOLS_BACKUP', 'Datenbanksicherung');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Sprachen definieren');
define('BOX_TOOLS_MAIL', 'eMail versenden');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Rundschreiben Manager');
define('BOX_TOOLS_WHOS_ONLINE', 'Wer ist Online');
define('BOX_HEADING_ADMINISTRATORS', 'Administrators');

define('BOX_ADMINISTRATORS_SETUP', 'Set Up');

// localizaion box text in includes/boxes/localization.php
$aLang['box_heading_localization'] = 'Sprachen/Währungen';
define('BOX_HEADING_LOCALIZATION', 'Sprachen/Währungen');
define('BOX_LOCALIZATION_CURRENCIES', 'Währungen');
define('BOX_LOCALIZATION_LANGUAGES', 'Sprachen');
define('BOX_LOCALIZATION_CUSTOMERS_STATUS', 'Kundengruppen');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Bestellstatus');

//information
$aLang['box_heading_information'] = 'Information';
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

define('CATEGORY_PERSONAL', 'Persönliche Daten');
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
define('ENTRY_PASSWORD_CONFIRMATION', 'Passwortbestätigung:');
define('PASSWORD_HIDDEN', '--VERSTECKT--');

// images
define('IMAGE_ANI_SEND_EMAIL', 'eMail versenden');
define('IMAGE_BACK', 'Zurück');
define('IMAGE_BACKUP', 'Datensicherung');
define('IMAGE_CANCEL', 'Abbruch');
define('IMAGE_CONFIRM', 'Bestätigen');
define('IMAGE_COPY', 'Kopieren');
define('IMAGE_COPY_TO', 'Kopieren nach');
define('IMAGE_DEFINE', 'Definieren');
define('IMAGE_DELETE', 'Löschen');
define('IMAGE_EDIT', 'Bearbeiten');
define('IMAGE_EMAIL', 'eMail versenden');
define('IMAGE_FEATURED', 'Top-Angebote');
define('IMAGE_FILE_MANAGER', 'Datei-Manager');
define('IMAGE_ICON_STATUS_GREEN', 'aktiv');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'aktivieren');
define('IMAGE_ICON_STATUS_RED', 'inaktiv');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'deaktivieren');
define('IMAGE_ICON_INFO', 'Information');
define('IMAGE_INSERT', 'Einfügen');
define('IMAGE_LOCK', 'Sperren');
define('IMAGE_MOVE', 'Verschieben');
define('IMAGE_NEW_BANNER', 'Neuen Banner aufnehmen');
define('IMAGE_NEW_CATEGORY', 'Neue Kategorie erstellen');
define('IMAGE_NEW_COUNTRY', 'Neues Land aufnehmen');
define('IMAGE_NEW_CURRENCY', 'Neue Währung einfügen');
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
define('IMAGE_RESET', 'Zurücksetzen');
define('IMAGE_RESTORE', 'Zurücksichern');
define('IMAGE_SAVE', 'Speichern');
define('IMAGE_SEARCH', 'Suchen');
define('IMAGE_SELECT', 'Auswählen');
define('IMAGE_SEND', 'Versenden');
define('IMAGE_SEND_EMAIL', 'eMail versenden');
define('IMAGE_SPECIALS', 'Sonderangebot');
define('IMAGE_STATUS', 'Kundengruppe');
define('IMAGE_UNLOCK', 'Entsperren');
define('IMAGE_UPDATE', 'Aktualisieren');
define('IMAGE_UPDATE_CURRENCIES', 'Wechselkurse aktualisieren');
define('IMAGE_UPLOAD', 'Hochladen');
define('IMAGE_WISHLIST', 'Wunschzettel');

$aLang['button_ani_send_email'] = 'eMail versenden';
$aLang['button_back'] = 'Zurück';
$aLang['button_backup'] = 'Datensicherung';
$aLang['button_cancel'] = 'Abbruch';
$aLang['button_confirm'] = 'Bestätigen';
$aLang['button_copy'] = 'Kopieren';
$aLang['button_copy_to'] = 'Kopieren nach';
$aLang['button_define'] = 'Definieren';
$aLang['button_delete'] = 'Löschen';
$aLang['button_edit'] = 'Bearbeiten';
$aLang['button_email'] = 'eMail versenden';
$aLang['button_featured'] = 'Top-Angebote';
$aLang['button_file_manager'] = 'Datei-Manager';
$aLang['button_icon_status_green'] = 'aktiv';
$aLang['button_icon_status_green_light'] = 'aktivieren';
$aLang['button_icon_status_red'] = 'inaktiv';
$aLang['button_icon_status_red_light'] = 'deaktivieren';
$aLang['button_icon_info'] = 'Information';
$aLang['button_insert'] = 'Einfügen';
$aLang['button_lock'] = 'Sperren';
$aLang['button_move'] = 'Verschieben';
$aLang['button_new_category'] = 'Neue Kategorie erstellen';
$aLang['button_new_country'] = 'Neues Land aufnehmen';
$aLang['button_new_currency'] = 'Neue Währung einfügen';
$aLang['button_new_file'] = 'Neue Datei';
$aLang['button_new_folder'] = 'Neues Verzeichnis';
$aLang['button_new_language'] = 'Neue Sprache anlegen';
$aLang['button_new_news'] = 'Neue News erstellen';
$aLang['button_new_newsletter'] = 'Neues Rundschreiben';
$aLang['button_new_product'] = 'Neuen Artikel aufnehmen';
$aLang['button_new_tax_class'] = 'Neue Steuerklasse erstellen';
$aLang['button_new_tax_rate'] = 'Neuen Steuersatz anlegen';
$aLang['button_new_tax_zone'] = 'Neue Steuerzone erstellen';
$aLang['button_orders'] = 'Bestellungen';
$aLang['button_orders_invoice'] = 'Rechnung';
$aLang['button_orders_packingslip'] = 'Lieferschein';
$aLang['button_orders_webprinter'] = 'WebPrinter';
$aLang['button_plugins_install'] = 'Plugins Installieren';
$aLang['button_plugins_remove'] = 'Plugins Entfernen';
$aLang['button_preview'] = 'Vorschau';
$aLang['button_reset'] = 'Zurücksetzen';
$aLang['button_restore'] = 'Zurücksichern';
$aLang['button_save'] = 'Speichern';
$aLang['button_search'] = 'Suchen';
$aLang['button_select'] = 'Auswählen';
$aLang['button_send'] = 'Versenden';
$aLang['button_send_email'] = 'eMail versenden';
$aLang['button_send_password'] = 'Passwort senden';
$aLang['button_specials'] = 'Sonderangebot';
$aLang['button_status'] = 'Kundengruppe';
$aLang['button_unlock'] = 'Entsperren';
$aLang['button_update'] = 'Aktualisieren';
$aLang['button_update_currencies'] = 'Wechselkurse aktualisieren';
$aLang['button_upload'] = 'Hochladen';
$aLang['button_wishlist'] = 'Wunschzettel';
$aLang['button_new_tax_rate'] = 'Neuen Steuersatz anlegen';
$aLang['button_new_zone'] = 'Neues Bundesland einfügen';


define('ICON_CROSS', 'Falsch');
define('ICON_CURRENT_FOLDER', 'aktueller Ordner');
define('ICON_DELETE', 'Löschen');
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
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Ländern)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Kunden)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Währungen)');
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
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Steuersätzen)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Bundesländern)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Kundengruppen)');
define('TEXT_DISPLAY_NUMBER_OF_BLOCKES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Boxen)');
define('TEXT_DISPLAY_NUMBER_OF_RSS', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> )');
define('TEXT_DISPLAY_NUMBER_OF_NEWSFEED_CATEGORIES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Kategorien)');
define('TEXT_DISPLAY_NUMBER_OF_PAGE_TYPES', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> )');
define('TEXT_DISPLAY_NUMBER_OF_INFORMATION', 'Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Information)');


define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

$aLang['prevnext_title_first_page'] = 'erste Seite';
$aLang['prevnext_title_previous_page'] = 'vorherige Seite';
$aLang['prevnext_title_next_page'] = 'nächste Seite';
$aLang['prevnext_title_last_page'] = 'letzte Seite';
$aLang['prevnext_title_page_no'] = 'Seite %d';
$aLang['prevnext_title_prev_set_of_no_page'] = 'Vorhergehende %d Seiten';
$aLang['prevnext_title_next_set_of_no_page'] = 'Nächste %d Seiten';
$aLang['prevnext_button_first'] = '&lt;&lt;ERSTE';
$aLang['prevnext_button_prev'] = '&lt;&lt;&nbsp;vorherige';
$aLang['prevnext_button_next'] = 'nächste&nbsp;&gt;&gt;';
$aLang['prevnext_button_last'] = 'LETZTE&gt;&gt;';


define('TEXT_DEFAULT', 'Standard');
define('TEXT_SET_DEFAULT', 'als Standard definieren');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* erforderlich</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Fehler: Es wurde keine Standardwährung definiert. Bitte definieren Sie unter Adminstration -> Sprachen/Währungen -> Währungen eine Standardwährung.');
define('ERROR_USER_FOR_THIS_PAGE', 'Fehler: Sie haben für diesen Bereich keine Zugangsrechte.');

define('TEXT_INFO_USER_NAME', 'UserName:');
define('TEXT_INFO_PASSWORD', 'Password:');

define('TEXT_NONE', '--keine--');
define('TEXT_TOP', 'Top');

define('ENTRY_YES','ja');
define('ENTRY_NO','nein');


define('IMAGE_RELEASE', 'Gutschein einlösen');

define('_JANUARY', 'Januar');
define('_FEBRUARY', 'Februar');
define('_MARCH', 'März');
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

$aLang['box_heading_administrator'] = 'Redakteure';

// images
define('IMAGE_FILE_PERMISSION', 'Dateizugriffs-Erlaubnis');
define('IMAGE_GROUPS', 'Gruppenliste');
define('IMAGE_INSERT_FILE', 'Datei einfgen');
define('IMAGE_MEMBERS', 'Gruppenliste');
define('IMAGE_NEW_GROUP', 'Neue Gruppe');
define('IMAGE_NEW_MEMBER', 'Neues Mitglied');
define('IMAGE_NEXT', 'Nächster');

// constants for use in oosPrevNextDisplay function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Zeige an <b>%d</b> bis <b>%d</b> (von <b>%d</b> Dateinamen)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Zeige an <b>%d</b> bis <b>%d</b> (von <b>%d</b> Mitglieder)');


define('PULL_DOWN_DEFAULT', 'Bitte wählen');
$aLang['bulk_actions'] = 'Aktion wählen';

define('BOX_REPORTS_RECOVER_CART_SALES', 'Warenkorbabbrüche');
define('BOX_TOOLS_RECOVER_CART', 'Warenkorbabbrüche');

// BOF: WebMakers.com Added: All Add-Ons
// Download Controller
// Add a new Order Status to the orders_status table - Updated
define('ORDERS_STATUS_UPDATED_VALUE','4'); // set to the Updated status to update max days and max count

// Quantity Definitions
require('includes/languages/deu/quantity_control.php');

