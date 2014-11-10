<?php
/* ----------------------------------------------------------------------
   $Id: ita.php,v 1.3 2007/06/13 17:20:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: english.php,v 1.101 2002/11/11 13:30:16 project3000
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
  * on RedHat try 'en_US'
  * on FreeBSD try 'en_US.ISO_8859-1'
  * on Windows try 'en', or 'English'
  */
  @setlocale(LC_TIME, 'it_IT');
  define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
  define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
  define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
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
      return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
    } else {
      return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
    }
  }

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="it"');

// charset for web pages and emails
define('CHARSET', 'UTF-8');

// page title
define('TITLE', 'OSIS Online Shop');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Amministrazione');
define('HEADER_TITLE_SUPPORT_SITE', 'Sito di Supporto');
define('HEADER_TITLE_ONLINE_CATALOG', 'Catalogo Online');
define('HEADER_TITLE_ADMINISTRATION', 'Amministrazione');
define('HEADER_TITLE_LOGOFF', 'Disconnetti');

$aLang['header_title_top'] = 'Amministrazione';
$aLang['header_title_support_site'] = 'Sito di Supporto';
$aLang['header_title_online_catalog'] = 'Catalogo Online';
$aLang['header_title_administration'] = 'Amministrazione';
$aLang['header_title_account'] = 'Il mio Account';
$aLang['header_title_logoff'] = 'Disconnetti';

// text for gender
define('MALE', 'Maschio');
define('FEMALE', 'Femmina');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configurazione');
define('BOX_CONFIGURATION_MYSTORE', 'Il Mio Negozio');
define('BOX_CONFIGURATION_LOGGING', 'Registro Eventi');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Moduli');
define('BOX_MODULES_PAYMENT', 'Pagamenti');
define('BOX_MODULES_SHIPPING', 'Spedizioni');
define('BOX_MODULES_ORDER_TOTAL', 'Totali Ordini');

// plugins box text in includes/boxes/plugins.php
define('BOX_HEADING_PLUGINS', 'Plugins');
define('BOX_PLUGINS_EVENT', 'Event Plugins');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Catalogo');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categorie/Prodotti');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Attributi Prodotti');
define('BOX_CATALOG_PRODUCTS_STATUS', 'Stato Prodotti');
define('BOX_CATALOG_PRODUCTS_UNITS', 'Packing unit');
define('BOX_CATALOG_MANUFACTURERS', 'Marche');
define('BOX_CATALOG_REVIEWS', 'Recensioni');
define('BOX_CATALOG_SPECIALS', 'Offerte');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Prodotti in Arrivo');
define('BOX_CATALOG_QADD_PRODUCT', 'Aggiungi Prodotto');
define('BOX_CATALOG_PRODUCTS_FEATURED', 'Prodotti In Rilievo');
define('BOX_CATALOG_EASYPOPULATE', 'EasyPopulate');
define('BOX_CATALOG_EXPORT_EXCEL', 'Export Excel Product');
define('BOX_CATALOG_IMPORT_EXCEL', 'Update Excel Price');
define('BOX_CATALOG_XSELL_PRODUCTS', 'Vendita Incrociata Prodotti');
define('BOX_CATALOG_UP_SELL_PRODUCTS', 'UP Sell Products');
define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Aggiornamento Veloce Stock');

// categories box text in includes/boxes/content.php
define('BOX_HEADING_CONTENT', 'Gestione Contenuti');
define('BOX_CONTENT_BLOCK', 'Gestione Blocchi');
define('BOX_CONTENT_NEWS', 'News');
define('BOX_CONTENT_INFORMATION', 'Informazioni');
define('BOX_CONTENT_PAGE_TYPE', 'Contenuto Pagine');

// categories box text in includes/boxes/newsfeed.php
define('BOX_HEADING_NEWSFEED', 'News Feed');
define('BOX_NEWSFEED_MANAGER', 'Gestione News Feed');
define('BOX_NEWSFEED_CATEGORIES', 'Categorie News Feed');

// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Clienti');
define('BOX_CUSTOMERS_CUSTOMERS', 'Clienti');
define('BOX_CUSTOMERS_ORDERS', 'Ordini');
define('BOX_CAMPAIGNS', 'Campaigns');
define('BOX_ADMIN_LOGIN', 'Login Amministratore');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Zone / Tasse');
define('BOX_TAXES_COUNTRIES', 'Nazioni');
define('BOX_TAXES_ZONES', 'Stati/Province');
define('BOX_TAXES_GEO_ZONES', 'Tasse stat./prov.');
define('BOX_TAXES_TAX_CLASSES', 'Tipi di Tasse');
define('BOX_TAXES_TAX_RATES', 'Aliquota Tasse');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Rapporti');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Prodotti Visti');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Prodotti Acquistati');
define('BOX_REPORTS_ORDERS_TOTAL', 'Ordini Clienti');
define('BOX_REPORTS_STOCK_LEVEL', 'Low Stock Report');
define('BOX_REPORTS_SALES_REPORT2', 'ReportVendita2');
define('BOX_REPORTS_KEYWORDS', 'Gestione Keyword');
define('BOX_REPORTS_REFERER' , 'HTTP Referers');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Strumenti Utili');
define('BOX_TOOLS_BACKUP', 'Salva Database');
define('BOX_TOOLS_BANNER_MANAGER', 'Gestione Banner');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Definizione Lingue');
define('BOX_TOOLS_FILE_MANAGER', 'Gestione File');
define('BOX_TOOLS_MAIL', 'Spedizione Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Gestione Newsletter');
define('BOX_TOOLS_SERVER_INFO', 'Server Info');
define('BOX_TOOLS_WHOS_ONLINE', 'Chi c\'è Online');
define('BOX_TOOLS_KEYWORD_SHOW', 'Keyword Show');
define('BOX_HEADING_ADMINISTRATORS', 'Amministratori');
define('BOX_ADMINISTRATORS_SETUP', 'Set Up');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Localizzazione');
define('BOX_LOCALIZATION_CURRENCIES', 'Valute/Monete');
define('BOX_LOCALIZATION_LANGUAGES', 'Lingue');
define('BOX_LOCALIZATION_CUSTOMERS_STATUS', 'Stato Clienti');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Stato Ordini');

// links box text in includes/boxes/links.php
define('BOX_HEADING_LINKS', 'Gestione Links');
define('BOX_CONTENT_LINKS', 'Links');
define('BOX_CONTENT_LINK_CATEGORIES', 'Categorie Link');
define('BOX_CONTENT_LINKS_CONTACT', 'Contatta Links');

// export
define('BOX_HEADING_EXPORT', 'Esportazione');
define('BOX_EXPORT_PREISSUCHMASCHINE', 'Export preissuchmaschine.de');
define('BOX_EXPORT_GOOGLEBASE', 'Googlebase');

//rss
define('BOX_HEADING_RSS', 'RSS');
define('BOX_RSS_CONF', 'RSS');

//information
define('BOX_HEADING_INFORMATION', 'Informazioni');
define('BOX_INFORMATION', 'Informazioni');

// javascript messages
define('JS_ERROR', 'Si sono verificati degli errori nel procedimento di compilazione del tuo modulo!!\nEseguire le seguenti correzioni:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* Definire per il nuovo attributo del Prodotto un prezzo\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* Definire per il nuovo attributo del Prodotto un prefisso di prezzo\n');

define('JS_PRODUCTS_NAME', '* Definire per il nuovo Prodotto un nome\n');
define('JS_PRODUCTS_DESCRIPTION', '* Definire per il nuovo Prodotto una descrizione\n');
define('JS_PRODUCTS_PRICE', '* Definire per il nuovo Prodotto necessita di un prezzo\n');
define('JS_PRODUCTS_WEIGHT', '* Definire per il  nuovo Prodotto un peso\n');
define('JS_PRODUCTS_QUANTITY', '* Definire per il nuovo Prodotto una quantità\n');
define('JS_PRODUCTS_MODEL', '* Definire per il  nuovo Prodotto un modello\n');
define('JS_PRODUCTS_IMAGE', '* Definire per il nuovo Prodotto un\'immagine\'\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* Definire un nuovo prezzo per questo prodotto.\n');

define('JS_GENDER', '* La scelta del Sesso è obbligatoria.\n');
define('JS_FIRST_NAME', '* Il Nome deve contenere almeno ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' caratteri.\n');
define('JS_LAST_NAME', '* Il Cognome deve contenere almeno ' . ENTRY_LAST_NAME_MIN_LENGTH . ' caratteri.\n');
define('JS_DOB', '* La Data di Nascita deve avere il formato: xx/xx/xxxx (mese/giorno/anno).\n');
define('JS_EMAIL_ADDRESS', '* L\'indirizzo di E-mail deve contenere almeno\' ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' caratteri.\n');
define('JS_ADDRESS', '* L\'indirizzo deve contenere almeno\' ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' caratteri.\n');
define('JS_POST_CODE', '* Il CAP deve contenere almeno ' . ENTRY_POSTCODE_MIN_LENGTH . ' caratteri.\n');
define('JS_CITY', '* Il nome della Citta\' deve contenere almeno ' . ENTRY_CITY_MIN_LENGTH . ' caratteri.\n');
define('JS_STATE', '* Lo Stato/Provincia deve essere selezionato.\n');
define('JS_STATE_SELECT', '-- Seleziona Sotto --');
define('JS_ZONE', '* Lo Stato/Provincia deve essere scelto dalla lista.');
define('JS_COUNTRY', '* Lo Stato/Provincia deve essere scelto.\n');
define('JS_TELEPHONE', '* Il Numero di Telefono deve contenere almeno ' . ENTRY_TELEPHONE_MIN_LENGTH . ' caratteri.\n');
define('JS_PASSWORD', '* La Password e la Conferma devono contenere almeno ' . ENTRY_PASSWORD_MIN_LENGTH . ' caratteri.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Il Numero dell\'Ordine\' %s non esiste!');

define('CATEGORY_PERSONAL', 'Personale');
define('CATEGORY_ADDRESS', 'Indirizzo');
define('CATEGORY_CONTACT', 'Contatti');
define('CATEGORY_COMPANY', 'Azienda');
define('CATEGORY_PASSWORD', 'Password');
define('CATEGORY_OPTIONS', 'Opzioni');
define('ENTRY_GENDER', 'Sesso:');
define('ENTRY_FIRST_NAME', 'Nome:');
define('ENTRY_LAST_NAME', 'Cognome:');
define('ENTRY_NUMBER', 'Numero Cliente:');
define('ENTRY_DATE_OF_BIRTH', 'Data di Nascita:');
define('ENTRY_EMAIL_ADDRESS', 'Indirizzo E-Mail:');
define('ENTRY_COMPANY', 'Nome Azienda:');
define('ENTRY_OWNER', 'Nome Propietario:');
define('ENTRY_VAT_ID', 'VAT ID:');
define('ENTRY_STREET_ADDRESS', 'Indirizzo:');
define('ENTRY_SUBURB', 'Frazione:');
define('ENTRY_POST_CODE', 'CAP:');
define('ENTRY_CITY', 'Città:');
define('ENTRY_STATE', 'Stato/Provincia:');
define('ENTRY_COUNTRY', 'Nazione:');
define('ENTRY_TELEPHONE_NUMBER', 'Numero di telefono:');
define('ENTRY_FAX_NUMBER', 'Numero di Fax:');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_YES', 'Mi iscrivo');
define('ENTRY_NEWSLETTER_NO', 'Non mi iscrivo');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Conferma Password:');
define('PASSWORD_HIDDEN', '--HIDDEN--');

// images
define('IMAGE_ANI_SEND_EMAIL', 'Spedisci E-Mail');
define('IMAGE_BACK', 'Indietro');
define('IMAGE_BACKUP', 'Salva');
define('IMAGE_CANCEL', 'Cancella');
define('IMAGE_CONFIRM', 'Conferma');
define('IMAGE_COPY', 'Copia');
define('IMAGE_COPY_TO', 'Copia In');
define('IMAGE_DEFINE', 'Dettagli');
define('IMAGE_DELETE', 'Cancella');
define('IMAGE_EDIT', 'Modifica');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FEATURED', 'In Rilievo');
define('IMAGE_FILE_MANAGER', 'Gestione File');
define('IMAGE_ICON_STATUS_GREEN', 'Attiva');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Imposta come Attivo');
define('IMAGE_ICON_STATUS_RED', 'Inattiva');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Imposta come Inattivo');
define('IMAGE_ICON_INFO', 'Informazioni');
define('IMAGE_INSERT', 'Inserisci');
define('IMAGE_LOCK', 'Blocca');
define('IMAGE_MOVE', 'Muovi');
define('IMAGE_NEW_BANNER', 'Nuovo Banner');
define('IMAGE_NEW_CATEGORY', 'Nuova Categoria');
define('IMAGE_NEW_COUNTRY', 'Nuova Nazione');
define('IMAGE_NEW_CURRENCY', 'Nuova Valuta');
define('IMAGE_NEW_FILE', 'Nuovo File');
define('IMAGE_NEW_FOLDER', 'Nuova Cartella');
define('IMAGE_NEW_LANGUAGE', 'Nuova Lingua');
define('IMAGE_NEW_NEWS', 'Nuova News');
define('IMAGE_NEW_NEWSLETTER', 'Nuova Newsletter');
define('IMAGE_NEW_PRODUCT', 'Nuovo Prodotto');
define('IMAGE_NEW_TAX_CLASS', 'Nuovo Tipo di Tassa');
define('IMAGE_NEW_TAX_RATE', 'Nuova Aliquota Tassa');
define('IMAGE_NEW_TAX_ZONE', 'Nuova Tassa Stat./Prov.');
define('IMAGE_ORDERS', 'Ordini');
define('IMAGE_ORDERS_INVOICE', 'Fattura');
define('IMAGE_ORDERS_PACKINGSLIP', 'Ordini evasi');
define('IMAGE_ORDERS_WEBPRINTER', 'WebPrinter');
define('IMAGE_PLUGINS_INSTALL', 'Installa Plugins');
define('IMAGE_PLUGINS_REMOVE', 'Rimuovi Plugins');
define('IMAGE_PREVIEW', 'Anteprima');
define('IMAGE_RESTORE', 'Ripristina');
define('IMAGE_RESET', 'Resetta');
define('IMAGE_SAVE', 'Salva');
define('IMAGE_SEARCH', 'Cerca');
define('IMAGE_SELECT', 'Seleziona');
define('IMAGE_SEND', 'Spedisci');
define('IMAGE_SEND_EMAIL', 'Invia Email');
define('IMAGE_SPECIALS', 'Specials');
define('IMAGE_STATUS', 'Customers Status');
define('IMAGE_UNLOCK', 'Sblocca');
define('IMAGE_UPDATE', 'Aggiorna');
define('IMAGE_UPDATE_CURRENCIES', 'Aggiorna Tasso di Cambio');
define('IMAGE_UPLOAD', 'Upload');
define('IMAGE_WISHLIST', 'Lista dei desideri');

$aLang['image_new_tax_rate'] = 'Nuova Tassa Stat./Prov.';
$aLang['image_new_zone'] = 'Nuovo Stato/Provincia';

define('ICON_CROSS', 'Falso');
define('ICON_CURRENT_FOLDER', 'Cartella Corrente');
define('ICON_DELETE', 'Cancella');
define('ICON_ERROR', 'Errore');
define('ICON_FILE', 'File');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Cartella');
define('ICON_LOCKED', 'Bloccato');
define('ICON_PREVIOUS_LEVEL', 'Livello Precedente');
define('ICON_PREVIEW', 'Anteprima');
define('ICON_STATISTICS', 'Statistiche');
define('ICON_SUCCESS', 'Riuscito');
define('ICON_TICK', 'Vero');
define('ICON_UNLOCKED', 'Sbloccato');
define('ICON_WARNING', 'Attenzione');

// constants for use in oos_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> banners)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Visualizzate <b>%d</b> su <b>%d</b> (di <b>%d</b> nazioni)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> clienti)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Visualizzate <b>%d</b> su <b>%d</b> (di <b>%d</b> valute)');
define('TEXT_DISPLAY_NUMBER_OF_HTTP_REFERERS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b>  HTTP Referers)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Visualizzate <b>%d</b> su <b>%d</b> (di <b>%d</b> lingue)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> produttori)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Visualizzate <b>%d</b> su <b>%d</b> (di <b>%d</b> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> ordini)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> stato ordini)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> prodotti)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> prodotti in attesa)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_UNITS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> packing unit)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> recensioni prodotto)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> prodotti in offerta)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> tipi di tassa)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Visualizzate <b>%d</b> su <b>%d</b> (di <b>%d</b> tasse stat./prov)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Visualizzate <b>%d</b> su <b>%d</b> (di <b>%d</b> aliquote di tassa)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> stati/Province)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> stato clienti)');
define('TEXT_DISPLAY_NUMBER_OF_BLOCKES', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> box)');
define('TEXT_DISPLAY_NUMBER_OF_RSS', 'Visualizzati <b>%d</b> su <b>%d</b> (of <b>%d</b>)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSFEED_CATEGORIES', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> categorie)');
define('TEXT_DISPLAY_NUMBER_OF_INFORMATION', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> Informazioni)');


define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'default');
define('TEXT_SET_DEFAULT', 'Setta come Default');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Richiesto</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Errore: Non c\'è un valore di Default settato. Settane uno da: Tool Amministrazione->Localizzazione->Valute');
define('ERROR_USER_FOR_THIS_PAGE', 'Fehler: Sie haben f&uuml;r diesen Bereich keine Zugangsrechte.');

define('TEXT_INFO_USER_NAME', 'Nome Utente:');
define('TEXT_INFO_PASSWORD', 'Password:');

define('TEXT_NONE', '--niente--');
define('TEXT_TOP', 'Top');

define('ENTRY_TAX_YES','Si');
define('ENTRY_TAX_NO','No');


// reports box text in includes/boxes/affiliate.php
define('BOX_HEADING_AFFILIATE', 'Affiliati');
define('BOX_AFFILIATE_SUMMARY', 'Sommario');
define('BOX_AFFILIATE', 'Affiliati');
define('BOX_AFFILIATE_PAYMENT', 'Pagamenti');
define('BOX_AFFILIATE_BANNERS', 'Banners');
define('BOX_AFFILIATE_CONTACT', 'Contatta');
define('BOX_AFFILIATE_SALES', 'Vendite');
define('BOX_AFFILIATE_CLICKS', 'Clicks');

define ('BOX_HEADING_TICKET','Biglietti di supporto');
define ('BOX_TICKET_VIEW','Tickets');
define ('BOX_TEXT_ADMIN','Amministratori');
define ('BOX_TEXT_DEPARTMENT','Reparto');
define ('BOX_TEXT_PRIORITY','Priorità');
define ('BOX_TEXT_REPLY','Risposte');
define ('BOX_TEXT_STATUS','Stati');

define('BOX_HEADING_GV_ADMIN', 'Buoni/Sconto');
define('BOX_GV_ADMIN_QUEUE', 'Coda Bonus Sconto');
define('BOX_GV_ADMIN_MAIL', 'Mail Bonus Sconto');
define('BOX_GV_ADMIN_SENT', 'Bonus Sconto spediti');
define('BOX_COUPON_ADMIN','Amministrazione Bonus Sconto');

define('IMAGE_RELEASE', 'Riacquista Bonus Sconto');

define('_JANUARY', 'Gennaio');
define('_FEBRUARY', 'Febbraio');
define('_MARCH', 'Marzo');
define('_APRIL', 'Aprile');
define('_MAY', 'Maggio');
define('_JUNE', 'Giugno');
define('_JULY', 'Luglio');
define('_AUGUST', 'Agosto');
define('_SEPTEMBER', 'Settembre');
define('_OCTOBER', 'Ottobre');
define('_NOVEMBER', 'Novembre');
define('_DECEMBER', 'Dicembre');

define('TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> buoni sconto)');
define('TEXT_DISPLAY_NUMBER_OF_COUPONS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> buoni)');

define('TEXT_VALID_PRODUCTS_LIST', 'Lista Prodotti');
define('TEXT_VALID_PRODUCTS_ID', 'ID Prodotto');
define('TEXT_VALID_PRODUCTS_NAME', 'Nome Prodotto');
define('TEXT_VALID_PRODUCTS_MODEL', 'Modello Prodotto');

define('TEXT_VALID_CATEGORIES_LIST', 'Lista Categorie');
define('TEXT_VALID_CATEGORIES_ID', 'ID Categoria');
define('TEXT_VALID_CATEGORIES_NAME', 'Nome Categoria');

define('HEADER_TITLE_ACCOUNT', 'Il mio Account');
define('HEADER_TITLE_LOGOFF', 'Disconnetti');

// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'Il mio Account');
define('BOX_MY_ACCOUNT', 'Il mio Account');
define('BOX_MY_ACCOUNT_LOGOFF', 'Disconnetti');

// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Amministratori');
define('BOX_ADMINISTRATOR_MEMBERS', 'Gruppi Utenti');
define('BOX_ADMINISTRATOR_MEMBER', 'Utenti');
define('BOX_ADMINISTRATOR_BOXES', 'Accesso ai Files');

// images
define('IMAGE_FILE_PERMISSION', 'Permessi File');
define('IMAGE_GROUPS', 'Lista Gruppi');
define('IMAGE_INSERT_FILE', 'Inserisci File');
define('IMAGE_MEMBERS', 'Lista Utenti');
define('IMAGE_NEW_GROUP', 'Nuovo Gruppo');
define('IMAGE_NEW_MEMBER', 'Nuovo Utente');
define('IMAGE_NEXT', 'Avanti');

// constants for use in oosPrevNextDisplay function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> filenames)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> utenti)');

define('PULL_DOWN_DEFAULT', 'Seleziona');

define('BOX_REPORTS_RECOVER_CART_SALES', 'Recupera Carrello');
define('BOX_TOOLS_RECOVER_CART', 'Recupera Carrello');

// BOF: WebMakers.com Added: All Add-Ons
// Download Controller
// Add a new Order Status to the orders_status table - Updated
define('ORDERS_STATUS_UPDATED_VALUE','4'); // set to the Updated status to update max days and max count

// Quantity Definitions
require('includes/languages/' . $_SESSION['language'] . '/' . 'quantity_control.php');
require('includes/languages/' . $_SESSION['language'] . '/' . 'mo_pics.php');

?>
