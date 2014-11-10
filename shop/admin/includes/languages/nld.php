<?php
/* ----------------------------------------------------------------------
   $Id: nld.php,v 1.3 2007/06/13 17:20:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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
  * on RedHat try 'nl_NL'
  * on FreeBSD try 'nl_NL.ISO_8859-1'
  * on Windows try 'nl' or 'Dutch'
  */
  @setlocale(LC_TIME, 'nl_NL');
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
define('HTML_PARAMS','dir="ltr" lang="nl"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// page title
define('TITLE', 'Bos Ruitersport Webwinkel');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Beheer');
define('HEADER_TITLE_SUPPORT_SITE', 'Hulppagina');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online catalogus');
define('HEADER_TITLE_ADMINISTRATION', 'Beheer');
define('HEADER_TITLE_LOGOFF', 'Uitloggen');

$aLang['header_title_top'] = 'Beheer';
$aLang['header_title_support_site'] = 'Hulppagina';
$aLang['header_title_online_catalog'] = 'Online catalogus';
$aLang['header_title_administration'] = 'Beheer';
$aLang['header_title_account'] = 'Mijn rekening';
$aLang['header_title_logoff'] = 'Uitloggen';

// text for gender
define('MALE', 'Mijnheer');
define('FEMALE', 'Mevrouw');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd.mm.jjjj');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configuratie');
define('BOX_CONFIGURATION_MYSTORE', 'Mijn winkel');
define('BOX_CONFIGURATION_LOGGING', 'Loggen');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Module');
define('BOX_MODULES_PAYMENT', 'Betaalwijze');
define('BOX_MODULES_SHIPPING', 'Verzendwijze');
define('BOX_MODULES_ORDER_TOTAL', 'Samenvatting');

// plugins box text in includes/boxes/plugins.php
define('BOX_HEADING_PLUGINS', 'Plugins');
define('BOX_PLUGINS_EVENT', 'Event Plugins');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Catalogus');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categorie&euml;n/Artikelen');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Produktkenmerken');
define('BOX_CATALOG_PRODUCTS_STATUS', 'Produktstatus');
define('BOX_CATALOG_PRODUCTS_UNITS', 'Packing unit');
define('BOX_CATALOG_MANUFACTURERS', 'Fabrikant');
define('BOX_CATALOG_REVIEWS', 'Produktbeoordelingen');
define('BOX_CATALOG_SPECIALS', 'Speciale aanbiedingen');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Verwachte artikelen');
define('BOX_CATALOG_QADD_PRODUCT', 'Produkt toevoegen');
define('BOX_CATALOG_PRODUCTS_FEATURED', 'Aangewezen');
define('BOX_CATALOG_EASYPOPULATE', 'Gemakkelijk winkelen');
define('BOX_CATALOG_EXPORT_EXCEL', 'Export Excel Product');
define('BOX_CATALOG_IMPORT_EXCEL', 'Update Excel Price');
define('BOX_CATALOG_XSELL_PRODUCTS', 'Koppelverkoop produkten');
define('BOX_CATALOG_UP_SELL_PRODUCTS', 'UP Sell Products');
define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Quick Stock Update');

// categories box text in includes/boxes/content.php
define('BOX_HEADING_CONTENT', 'Winkelpagina');
define('BOX_CONTENT_BLOCK', 'Winkelpagina opmaak');
define('BOX_CONTENT_NEWS', 'Nieuws');
define('BOX_CONTENT_INFORMATION', 'Informatie');
define('BOX_CONTENT_PAGE_TYPE', 'Paginainhoud type');

// categories box text in includes/boxes/newsfeed.php
define('BOX_HEADING_NEWSFEED', 'Nieuwsaanvoer');
define('BOX_NEWSFEED_MANAGER', 'Nieuwsaanvoer beheer');
define('BOX_NEWSFEED_CATEGORIES', 'Nieuwsmeldingen categorie&euml;n');


// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Klanten');
define('BOX_CUSTOMERS_CUSTOMERS', 'Klanten');
define('BOX_CUSTOMERS_ORDERS', 'Bestellingen');
define('BOX_CAMPAIGNS', 'Campaigns');
define('BOX_ADMIN_LOGIN', 'Inloggen beheerder');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Land/Belasting');
define('BOX_TAXES_COUNTRIES', 'Land');
define('BOX_TAXES_ZONES', 'Provincies');
define('BOX_TAXES_GEO_ZONES', 'Belastingdistricten');
define('BOX_TAXES_TAX_CLASSES', 'Belastinggroepen');
define('BOX_TAXES_TAX_RATES', 'Belastingtarieven');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Berichten');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Bezocht artikel');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Gekocht artikel');
define('BOX_REPORTS_ORDERS_TOTAL', 'Klanten bestelstatistiek');
define('BOX_REPORTS_STOCK_LEVEL', 'Voorraadbestand');
define('BOX_REPORTS_SALES_REPORT2', 'Omzetbericht');
define('BOX_REPORTS_KEYWORDS', 'Zoekwoord beheerder');
define('BOX_REPORTS_REFERER' , 'HTTP Referers');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Hulpprogramma');
define('BOX_TOOLS_BACKUP', 'Databankbackup');
define('BOX_TOOLS_BANNER_MANAGER', 'Bannerbeheer');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Talen instellen');
define('BOX_TOOLS_FILE_MANAGER', 'Bestandsbeheer');
define('BOX_EXPORT_PREISSUCHMASCHINE', 'Export prijszoekmachine.nl');
define('BOX_TOOLS_MAIL', 'Email versturen');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Nieuwsbrief beheer');
define('BOX_TOOLS_SERVER_INFO', 'Server info');
define('BOX_TOOLS_WHOS_ONLINE', 'Wie is er Online');
define('BOX_TOOLS_KEYWORD_SHOW', 'Zoekwoorden tonen');
define('BOX_HEADING_ADMINISTRATORS', 'Beheerders');
define('BOX_ADMINISTRATORS_SETUP', 'Instellen');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Valuta/Talen');
define('BOX_LOCALIZATION_CURRENCIES', 'Valuta');
define('BOX_LOCALIZATION_LANGUAGES', 'Talen');
define('BOX_LOCALIZATION_CUSTOMERS_STATUS', 'Klantengroepen');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Bestelstatus');

// links box text in includes/boxes/links.php
define('BOX_HEADING_LINKS', 'Links beheer');
define('BOX_CONTENT_LINKS', 'Links');
define('BOX_CONTENT_LINK_CATEGORIES', 'Link categorie&euml;n');
define('BOX_CONTENT_LINKS_CONTACT', 'Links contacten');

// export
define('BOX_HEADING_EXPORT', 'Export');
define('BOX_EXPORT_PREISSUCHMASCHINE', 'Export prijszoekmachine.nl');
define('BOX_EXPORT_GOOGLEBASE', 'Googlebase');

//rss
define('BOX_HEADING_RSS', 'RSS');
define('BOX_RSS_CONF', 'RSS');

//information
define('BOX_HEADING_INFORMATION', 'Informatie');
define('BOX_INFORMATION', 'Informatie');

// javascript messages
define('JS_ERROR', 'Tijdens de invoer zijn fouten opgetreden!\nA.u.b. corrigeer het volgende:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* U moet deze waarde een prijs toekennen\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* U moet een voorzet voor de prijs aangeven (+/-)\n');

define('JS_PRODUCTS_NAME', '* Het nieuwe artikel moet een naam hebben\n');
define('JS_PRODUCTS_DESCRIPTION', '* Het nieuwe artikel moet een beschrijving hebben\n');
define('JS_PRODUCTS_PRICE', '* Het nieuwe artikel moet een prijs hebben\n');
define('JS_PRODUCTS_WEIGHT', '* Het nieuwe artikel met een gewicht hebben\n');
define('JS_PRODUCTS_QUANTITY', '* U moet het nieuwe artikel een voorraadaantal toekennen\n');
define('JS_PRODUCTS_MODEL', '* U moet het nieuwe artikel een artikelnummer toekennen\n');
define('JS_PRODUCTS_IMAGE', '* U moet het nieuwe artikel een afbeelding toekennen\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* Er moet een nieuwe prijs voor dit artikel aangegeven worden\n');

define('JS_GENDER', '* De \'Aanspreektitel\' moet gekozen worden.\n');
define('JS_FIRST_NAME', '* De \'Voornaam\' moet minstens uit ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' karakters bestaan.\n');
define('JS_LAST_NAME', '* De \'Achternaam\' moet minstens uit ' . ENTRY_LAST_NAME_MIN_LENGTH . ' karakters bestaan.\n');
define('JS_DOB', '* De \'Geboortedatum\' moet minstens dit formaat: dd.mm.jjjj (Dag/Maand/Jaar).\n');
define('JS_EMAIL_ADDRESS', '* Het \'Emailadres\' moet minstens uit ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' karakters bestaan.\n');
define('JS_ADDRESS', '* Die \'Straat\' moet minstens uit ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' karakters bestaan.\n');
define('JS_POST_CODE', '* De \'Postcode\' moet minstens uit ' . ENTRY_POSTCODE_MIN_LENGTH . ' karakters bestaan.\n');
define('JS_CITY', '* De \'Woonplaats\' moet minstens uit ' . ENTRY_CITY_MIN_LENGTH . ' karakters bestaan.\n');
define('JS_STATE', '* De \'Provincie\' moet gekozen worden.\n');
define('JS_STATE_SELECT', '-- Kies bovenaan --');
define('JS_ZONE', '* De \'Provincie\' moet uit de lijst van die land gekozen worden.');
define('JS_COUNTRY', '* Het \'Land\' moet gekozen worden.\n');
define('JS_TELEPHONE', '* Het \'Telefoonnummer\' moet minstens uit ' . ENTRY_TELEPHONE_MIN_LENGTH . ' karakters bestaan.\n');
define('JS_PASSWORD', '* Het \'Wachtwoord\' en de \'Wachtwoordbevestiging\' moeten overeenkomen en uit minstens ' . ENTRY_PASSWORD_MIN_LENGTH . ' karakters bestaan.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Ordernummer %s bestaat niet!');

define('CATEGORY_PERSONAL', 'Persoonlijke gegevens');
define('CATEGORY_ADDRESS', 'Adres');
define('CATEGORY_CONTACT', 'Contact');
define('CATEGORY_PASSWORD', 'Wachtwoord');
define('CATEGORY_COMPANY', 'Bedrijf');
define('CATEGORY_OPTIONS', 'Opties');
define('ENTRY_GENDER', 'Aanspreektitel:');
define('ENTRY_FIRST_NAME', 'Voornaam:');
define('ENTRY_LAST_NAME', 'Achternaam:');
define('ENTRY_NUMBER', 'Klantennummer:');
define('ENTRY_DATE_OF_BIRTH', 'Geboortedatum:');
define('ENTRY_EMAIL_ADDRESS', 'Emailadres:');
define('ENTRY_COMPANY', 'Bedrijsnaam:');
define('ENTRY_OWNER', 'Eigenaar:');
define('ENTRY_VAT_ID', 'VAT ID:');
define('ENTRY_STREET_ADDRESS', 'Straat:');
define('ENTRY_SUBURB', 'overige adressering:');
define('ENTRY_POST_CODE', 'Postcode:');
define('ENTRY_CITY', 'Woonplaats:');
define('ENTRY_STATE', 'Provincie:');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_TELEPHONE_NUMBER', 'Telefoonnummer:');
define('ENTRY_FAX_NUMBER', 'Faxnummer:');
define('ENTRY_NEWSLETTER', 'Nieuwsbrief:');
define('ENTRY_NEWSLETTER_YES', 'geabonneerd');
define('ENTRY_NEWSLETTER_NO', 'niet geabonneerd');
define('ENTRY_PASSWORD', 'Wachtwoord:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Wachtwoord bevestiging:');
define('PASSWORD_HIDDEN', '--VERBORGEN--');

// images
define('IMAGE_ANI_SEND_EMAIL', 'Email versturen');
define('IMAGE_BACK', 'Vorige');
define('IMAGE_BACKUP', 'Gegevens backup');
define('IMAGE_CANCEL', 'Afbreken');
define('IMAGE_CONFIRM', 'Bevestigen');
define('IMAGE_COPY', 'Kopi&euml;ren');
define('IMAGE_COPY_TO', 'Kopi&euml;ren naar');
define('IMAGE_DEFINE', 'Defini&euml;ren');
define('IMAGE_DELETE', 'Wissen');
define('IMAGE_EDIT', 'Bewerken');
define('IMAGE_EMAIL', 'Email versturen');
define('IMAGE_FEATURED', 'Featured');
define('IMAGE_FILE_MANAGER', 'Bestandsbeheer');
define('IMAGE_ICON_STATUS_GREEN', 'actief');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'activeren');
define('IMAGE_ICON_STATUS_RED', 'inactief');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'deactiveren');
define('IMAGE_ICON_INFO', 'Informatie');
define('IMAGE_INSERT', 'Invoegen');
define('IMAGE_LOCK', 'Blokkeren');
define('IMAGE_MOVE', 'Verschuiven');
define('IMAGE_NEW_BANNER', 'Nieuwe banner opnemen');
define('IMAGE_NEW_CATEGORY', 'Nieuwe categorie maken');
define('IMAGE_NEW_COUNTRY', 'Nieuw land invoeren');
define('IMAGE_NEW_CURRENCY', 'Nieuwe valuta invoeren');
define('IMAGE_NEW_FILE', 'Nieuw bestand');
define('IMAGE_NEW_FOLDER', 'Nieuwe map');
define('IMAGE_NEW_LANGUAGE', 'Nieuwe taal aanmaken');
define('IMAGE_NEW_NEWS', 'Nieuwe nieuwsflits aanmaken');
define('IMAGE_NEW_NEWSLETTER', 'Nieuwe nieuwsbrief');
define('IMAGE_NEW_PRODUCT', 'Nieuw artikel invoeren');
define('IMAGE_NEW_TAX_CLASS', 'Nieuwe belastinggroep');
define('IMAGE_NEW_TAX_RATE', 'Nieuw belastingtarief aanmaken');
define('IMAGE_NEW_TAX_ZONE', 'Nieuwe belastingzone aanmaken');
define('IMAGE_NEW_ZONE', 'Nieuwe provincie aanmaken');
define('IMAGE_ORDERS', 'Bestellingen');
define('IMAGE_ORDERS_INVOICE', 'Rekening');
define('IMAGE_ORDERS_PACKINGSLIP', 'Afleverbon');
define('IMAGE_ORDERS_WEBPRINTER', 'Weprinter');
define('IMAGE_PLUGINS_INSTALL', 'Install Plugins');
define('IMAGE_PLUGINS_REMOVE', 'Remove Plugins');
define('IMAGE_PREVIEW', 'Vooruitblik');
define('IMAGE_RESET', 'Terugzetten');
define('IMAGE_RESTORE', 'Terugplaatsen');
define('IMAGE_SAVE', 'Opslaan');
define('IMAGE_SEARCH', 'Zoeken');
define('IMAGE_SELECT', 'Uitzoeken');
define('IMAGE_SEND', 'Versturen');
define('IMAGE_SEND_EMAIL', 'Email versturen');
define('IMAGE_SPECIALS', 'Speciale aanbiedingen');
define('IMAGE_STATUS', 'Klantengroep');
define('IMAGE_UNLOCK', 'Deblokkeren');
define('IMAGE_UPDATE', 'Actualiseren');
define('IMAGE_UPDATE_CURRENCIES', 'Wisselkoers actualiseren');
define('IMAGE_UPLOAD', 'Uploaden');
define('IMAGE_WISHLIST', 'Verlanglijst');

define('IMAGE_NEW_TAX_RATE', 'Nieuw belastingtarief aanmaken');
define('IMAGE_NEW_ZONE', 'Nieuwe provincie aanmaken');

define('ICON_CROSS', 'Verkeerd');
define('ICON_CURRENT_FOLDER', 'actuele orders');
define('ICON_DELETE', 'Wissen');
define('ICON_ERROR', 'Fout');
define('ICON_FILE', 'Bestand');
define('ICON_FILE_DOWNLOAD', 'Downloaden');
define('ICON_FOLDER', 'Mappen');
define('ICON_LOCKED', 'Geblokkeerd');
define('ICON_PREVIOUS_LEVEL', 'Vorige niveau');
define('ICON_PREVIEW', 'Vooruitblik');
define('ICON_STATISTICS', 'Statistiek');
define('ICON_SUCCESS', 'Succes');
define('ICON_TICK', 'Waar');
define('ICON_UNLOCKED', 'Gedeblokkeerd');
define('ICON_WARNING', 'Waarschuwing');

// constants for use in oos_prev_next_display function
define('TEXT_RESULT_PAGE', 'Pagina %s van %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> banners)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> landen)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> klanten)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> valuta)');
define('TEXT_DISPLAY_NUMBER_OF_HTTP_REFERERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b>  HTTP Referers)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> talen)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> fabrikanten)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> nieuwsbrieven)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> bestellingen)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> bestelstatus)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> artikelen)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> verwachte artikelen)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_UNITS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> packing unit)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> beoordelingen)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> speciale aanbiedingen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> belastinggroepen)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> belastingzones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> belastingtarieven)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> provincies)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> klantgroepen)');
define('TEXT_DISPLAY_NUMBER_OF_BLOCKES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> velden)');
define('TEXT_DISPLAY_NUMBER_OF_RSS', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> )');
define('TEXT_DISPLAY_NUMBER_OF_NEWSFEED_CATEGORIES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> categori&euml;en)');
define('TEXT_DISPLAY_NUMBER_OF_PAGE_TYPES', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> )');
define('TEXT_DISPLAY_NUMBER_OF_INFORMATION', 'Getoond wordt <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> information)');


define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'Standaard');
define('TEXT_SET_DEFAULT', 'als standaard defini&euml;ren');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* verplicht</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Fout: Er werd geen standaardvaluta gedefini&euml;erd. Definieer onder <b>Beheer -> Talen / Valuta -> Valuta</b> een standaardvaluta.');
define('ERROR_USER_FOR_THIS_PAGE', 'Fout: U hebt voor die gebied geen toegangsrechten.');

define('TEXT_INFO_USER_NAME', 'Gebruikersnaam:');
define('TEXT_INFO_PASSWORD', 'Wachtwoord:');

define('TEXT_NONE', '--geen--');
define('TEXT_TOP', 'Bovenaan');

define('ENTRY_YES','ja');
define('ENTRY_NO','nee');

// reports box text in includes/boxes/affiliate.php
define('BOX_HEADING_AFFILIATE', 'Partnerprogramma');
define('BOX_AFFILIATE_SUMMARY', 'Samenvatting');
define('BOX_AFFILIATE', 'Partner');
define('BOX_AFFILIATE_PAYMENT', 'Provisiebetalingen');
define('BOX_AFFILIATE_BANNERS', 'Banner');
define('BOX_AFFILIATE_CONTACT', 'Contact');
define('BOX_AFFILIATE_SALES', 'Partner verkopen');
define('BOX_AFFILIATE_CLICKS', 'Kliks');

define ('BOX_HEADING_TICKET','Hulp aanvragen');
define ('BOX_TICKET_VIEW','Kaarten');
define ('BOX_TEXT_ADMIN','Beheerders');
define ('BOX_TEXT_DEPARTMENT','Afdelingen');
define ('BOX_TEXT_PRIORITY','Prioriteiten');
define ('BOX_TEXT_REPLY','Antwoorden');
define ('BOX_TEXT_STATUS','Status');

define('BOX_HEADING_GV_ADMIN', 'Tegoedbon');
define('BOX_GV_ADMIN_QUEUE', 'Tegoedbon afroep');
define('BOX_GV_ADMIN_MAIL', 'Tegoedbon email');
define('BOX_GV_ADMIN_SENT', 'Tegoedbon verzenden');
define('BOX_HEADING_COUPON_ADMIN','Tegoedbonnen');
define('BOX_COUPON_ADMIN','Tegoedbon beheerder');

define('IMAGE_RELEASE', 'Tegoedbon inwisselen');

define('_JANUARY', 'Januari');
define('_FEBRUARY', 'Februari');
define('_MARCH', 'Maart');
define('_APRIL', 'April');
define('_MAY', 'Mei');
define('_JUNE', 'Juni');
define('_JULY', 'Juli');
define('_AUGUST', 'Augustus');
define('_SEPTEMBER', 'September');
define('_OCTOBER', 'Oktober');
define('_NOVEMBER', 'November');
define('_DECEMBER', 'December');

define('TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS', 'Getoond worden <b>%d</b> tot <b>%d</b> (van totaal <b>%d</b> tegoedbonnen)');
define('TEXT_DISPLAY_NUMBER_OF_COUPONS', 'Getoond worden <b>%d</b> tot <b>%d</b> (van totaal<b>%d</b> tegoedbonnen)');

define('TEXT_VALID_PRODUCTS_LIST', 'Produktlijst');
define('TEXT_VALID_PRODUCTS_ID', 'Produkt ID');
define('TEXT_VALID_PRODUCTS_NAME', 'Produktnaam');
define('TEXT_VALID_PRODUCTS_MODEL', 'Produktmodel');

define('TEXT_VALID_CATEGORIES_LIST', 'Categorielijst');
define('TEXT_VALID_CATEGORIES_ID', 'Categorie ID');
define('TEXT_VALID_CATEGORIES_NAME', 'Categorienaam');

define('HEADER_TITLE_TOP', 'Beheer');
define('HEADER_TITLE_ADMINISTRATION', 'Beheer');

define('HEADER_TITLE_ACCOUNT', 'My Account');
define('HEADER_TITLE_LOGOFF', 'Logoff');

// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'Mijn rekening');
define('BOX_MY_ACCOUNT', 'Mijn rekening');
define('BOX_MY_ACCOUNT_LOGOFF', 'Uitloggen');

// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Beheerder');
define('BOX_ADMINISTRATOR_MEMBERS', 'Groepsleden');
define('BOX_ADMINISTRATOR_MEMBER', 'Leden');
define('BOX_ADMINISTRATOR_BOXES', 'Bestandsbeheer');

// images
define('IMAGE_FILE_PERMISSION', 'Bestandsbeheer-toestemming');
define('IMAGE_GROUPS', 'Groepslijst');
define('IMAGE_INSERT_FILE', 'Bestand toevoegen');
define('IMAGE_MEMBERS', 'Groeplijst');
define('IMAGE_NEW_GROUP', 'Nieuwe groep');
define('IMAGE_NEW_MEMBER', 'Nieuw lid');
define('IMAGE_NEXT', 'Volgende');

// constants for use in oosPrevNextDisplay function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Toon <b>%d</b> tot <b>%d</b> (van <b>%d</b> bestandsnamen)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Toon <b>%d</b> tot <b>%d</b> (van <b>%d</b> leden)');

define('PULL_DOWN_DEFAULT', 'Kiezen a.u.b.');

define('BOX_REPORTS_RECOVER_CART_SALES', 'Recover Carts');
define('BOX_TOOLS_RECOVER_CART', 'Recover Carts');

// BOF: WebMakers.com Added: All Add-Ons
// Download Controller
// Add a new Order Status to the orders_status table - Updated
define('ORDERS_STATUS_UPDATED_VALUE','4'); // set to the Updated status to update max days and max count

// Quantity Definitions
require('includes/languages/' . $_SESSION['language'] . '/' . 'quantity_control.php');
require('includes/languages/' . $_SESSION['language'] . '/' . 'mo_pics.php');

?>
