<?php
/* ----------------------------------------------------------------------
   $Id: configuration.php,v 1.6 2009/01/23 06:23:43 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configuration.php,v 1.8 2002/01/04 03:51:40 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('TABLE_HEADING_CONFIGURATION_TITLE', 'Name');
define('TABLE_HEADING_CONFIGURATION_VALUE', 'Wert');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_INFO_EDIT_INTRO', 'Bitte f&uuml;hren Sie alle notwendigen &Auml;nderungen durch');
define('TEXT_INFO_DATE_ADDED', 'hinzugef&uuml;gt am:');
define('TEXT_INFO_LAST_MODIFIED', 'letzte &Auml;nderung:');


define('STORE_NAME_TITLE', 'Shop Name');
define('STORE_NAME_DESC', 'Der Name meines Shops');

define('STORE_OWNER_TITLE', 'Shop Inhaber');
define('STORE_OWNER_DESC', 'Der Name des Shop-Betreibers');

define('STORE_OWNER_EMAIL_ADDRESS_TITLE', 'E-Mail Adresse');
define('STORE_OWNER_EMAIL_ADDRESS_DESC', 'Die E-Mail Adresse des Shop-Betreibers');

define('STORE_OWNER_VAT_ID_TITLE' , 'Umsatzsteuer ID');
define('STORE_OWNER_VAT_ID_DESC' , 'Die Umsatzsteuer ID ihres Unternehmens');

define('STORE_ADDRESS_STREET_TITLE', 'Adressinformation: Straße des Shops');
define('STORE_ADDRESS_STREET_DESC', 'Die Kontaktinformationen des Shops, welche sowohl in Dokumenten als auch Online ausgegeben werden');

define('STORE_ADDRESS_POSTCODE_TITLE', 'Adressinformation: PLZ des Shops');
define('STORE_ADDRESS_POSTCODE_DESC', 'Die Kontaktinformationen des Shops, welche sowohl in Dokumenten als auch Online ausgegeben werden');

define('STORE_ADDRESS_CITY_TITLE', 'Adressinformation: Stadt des Shops');
define('STORE_ADDRESS_CITY_DESC', 'Die Kontaktinformationen des Shops, welche sowohl in Dokumenten als auch Online ausgegeben werden');

define('STORE_ADDRESS_TELEPHONE_NUMBER_TITLE', 'Adressinformation: Telefone des Shops');
define('STORE_ADDRESS_TELEPHONE_NUMBER_DESC', 'Die Kontaktinformationen des Shops, welche sowohl in Dokumenten als auch Online ausgegeben werden');

define('STORE_ADDRESS_EMAIL_TITLE', 'Adressinformation: Die E-Mail Adresse des Shops');
define('STORE_ADDRESS_EMAIL_DESC', 'Die E-Mail Adresse des Shops, welche sowohl in Dokumenten als auch Online ausgegeben werden');

define('STORE_COUNTRY_TITLE', 'Land');
define('STORE_COUNTRY_DESC', 'In welchem Land wird der Shop betrieben <br><br><b>Hinweis: Bitte vergessen Sie nicht, das Bundesland zu aktualisieren</b>');

define('STORE_ZONE_TITLE', 'Bundesland');
define('STORE_ZONE_DESC', 'In welchem Bundesland wird der Shop betrieben?');

define('EXPECTED_PRODUCTS_SORT_TITLE', 'Sortierreihenfolge erwartete Produkte');
define('EXPECTED_PRODUCTS_SORT_DESC', 'Sortierreihenfolge, die im \'erwartete Produkte\'-Block verwendet wird.');

define('EXPECTED_PRODUCTS_FIELD_TITLE', 'Sortierspalte erwartete Produkte');
define('EXPECTED_PRODUCTS_FIELD_DESC', 'Die Spalte, nach der im \'erwartete Produkte\'-Block sortiert wird.');

define('USE_DEFAULT_LANGUAGE_CURRENCY_TITLE', 'W&auml;hrung automatisch wechseln');
define('USE_DEFAULT_LANGUAGE_CURRENCY_DESC', 'Wechselt automatisch die W&auml;hrung anhand der eingestellten Sprache');

define('ADVANCED_SEARCH_DEFAULT_OPERATOR_TITLE', 'Standardoperator f&uuml;r Suchfunktionen');
define('ADVANCED_SEARCH_DEFAULT_OPERATOR_DESC', 'Die Standardverkn&uuml;pfung, mit der mehrere Suchbegriffe verkn&uuml;pft werden');

define('STORE_NAME_ADDRESS_TITLE', 'Adressinformationen des Shops');
define('STORE_NAME_ADDRESS_DESC', 'Die Kontaktinformationen des Shops, welche sowohl in Dokumenten als auch Online ausgegeben werden');

define('TAX_DECIMAL_PLACES_TITLE', 'Dezimalstellen der Steuer');
define('TAX_DECIMAL_PLACES_DESC', 'Anzahl der Dezimalstellen der Steuer');

define('DISPLAY_PRICE_WITH_TAX_TITLE', 'Preise inkl. Steuer');
define('DISPLAY_PRICE_WITH_TAX_DESC', 'Preise incl. Steuer anzeigen (true) oder die Steuer dem Gesamtbetrag hinzurechnen (false)');

define('DISPLAY_CONDITIONS_ON_CHECKOUT_TITLE', 'Unterzeichnen der AGB');
define('DISPLAY_CONDITIONS_ON_CHECKOUT_DESC', 'Im Bestellvorgang Ihre Allgemeine Gesch&auml;fts- und Lieferbedingungen anzeigen, bevor fortgefahren werden kann.');

define('PRODUCTS_OPTIONS_SORT_BY_PRICE_TITLE', 'Sortierung Produktoptionen');
define('PRODUCTS_OPTIONS_SORT_BY_PRICE_DESC', 'M&ouml;chten Sie die Produktopionen nach Preisen sortieren?');

define('WEB_SEARCH_GOOGLE_KEY_TITLE', 'Google API Lizenzschl&uuml;ssel');
define('WEB_SEARCH_GOOGLE_KEY_DESC', 'Google API Lizenzschl&uuml;ssel (kostenlos!) <a href="http://www.google.com/apis" target="_blank">http://www.google.com/apis</a>.');

define('ENTRY_FIRST_NAME_MIN_LENGTH_TITLE', 'Vorname');
define('ENTRY_FIRST_NAME_MIN_LENGTH_DESC', 'Mindestl&auml;nge des Vornames');

define('ENTRY_LAST_NAME_MIN_LENGTH_TITLE', 'Nachname');
define('ENTRY_LAST_NAME_MIN_LENGTH_DESC', 'Mindestl&auml;nge des Nachnames');

define('ENTRY_DOB_MIN_LENGTH_TITLE', 'Geburtsdatum');
define('ENTRY_DOB_MIN_LENGTH_DESC', 'Mindestl&auml;nge des Geburtsdatums');

define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_TITLE', 'E-Mail Adresse');
define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_DESC', 'Mindestl&auml;nge der E-Mail Adresse');

define('ENTRY_STREET_ADDRESS_MIN_LENGTH_TITLE', 'Strasse');
define('ENTRY_STREET_ADDRESS_MIN_LENGTH_DESC', 'Mindestl&auml;nge des Strassennamens');

define('ENTRY_COMPANY_LENGTH_TITLE', 'Firma');
define('ENTRY_COMPANY_LENGTH_DESC', 'Mindestl&auml;nge des Firmennames');

define('ENTRY_POSTCODE_MIN_LENGTH_TITLE', 'Postleitzahl');
define('ENTRY_POSTCODE_MIN_LENGTH_DESC', 'Mindestl&auml;nge der Postleitzahl');

define('ENTRY_CITY_MIN_LENGTH_TITLE', 'Stadt');
define('ENTRY_CITY_MIN_LENGTH_DESC', 'Mindestl&auml;nge des Namens der Stadt');

define('ENTRY_STATE_MIN_LENGTH_TITLE', 'Bundesland');
define('ENTRY_STATE_MIN_LENGTH_DESC', 'Mindestl&auml;nge des Namens des Bundeslandes');

define('ENTRY_TELEPHONE_MIN_LENGTH_TITLE', 'Telefonnummer');
define('ENTRY_TELEPHONE_MIN_LENGTH_DESC', 'Mindestl&auml;nge der Telefonnummer');

define('ENTRY_PASSWORD_MIN_LENGTH_TITLE', 'Passwort');
define('ENTRY_PASSWORD_MIN_LENGTH_DESC', 'Mindestl&auml;nge des Passworts');

define('CC_OWNER_MIN_LENGTH_TITLE', 'Name Kreditkarteneigent&uuml;mer');
define('CC_OWNER_MIN_LENGTH_DESC', 'Mindestl&auml;nge des Names vom Kreditkarteneigent&uuml;mer');

define('CC_NUMBER_MIN_LENGTH_TITLE', 'Kreditkartennummer');
define('CC_NUMBER_MIN_LENGTH_DESC', 'Mindestl&auml;nge der Kreditkartennummer');

define('MIN_DISPLAY_BESTSELLERS_TITLE', 'Verkaufsschlager');
define('MIN_DISPLAY_BESTSELLERS_DESC', 'Minimale Anzahl der Verkaufsschlager, die angezeigt werden.');

define('MIN_DISPLAY_ALSO_PURCHASED_TITLE', 'Kunden kauften auch');
define('MIN_DISPLAY_ALSO_PURCHASED_DESC', 'Minimale Anzahl von Produkten, die im \'Kunden kauften auch\'-Block angezeigt werden.');

define('MIN_DISPLAY_XSELL_PRODUCTS_TITLE', 'Produkt-Empfehlungen');
define('MIN_DISPLAY_XSELL_PRODUCTS_DESC', 'Minimale Anzahl von Produkten, die im \'Produkt-Empfehlungen\'-Block angezeigt werden.');

define('MIN_DISPLAY_PRODUCTS_NEWSFEED_TITLE', 'Neue Produkte im Newsfeed');
define('MIN_DISPLAY_PRODUCTS_NEWSFEED_DESC', 'Minimale Anzahl von Produkten, die im \'Newsfeed\'-Block angezeigt werden.');

define('MIN_DISPLAY_NEW_NEWS_TITLE', 'News Meldungen');
define('MIN_DISPLAY_NEW_NEWS_DESC', 'Minimale Anzahl von Meldungen, die auf der \'Startseite\' angezeigt werden.');

define('MAX_ADDRESS_BOOK_ENTRIES_TITLE', 'Anzahl Adressbucheintr&auml;ge');
define('MAX_ADDRESS_BOOK_ENTRIES_DESC', 'Maximale Anzahl von Adressbucheintr&auml;gen, die ein Kunde besitzen darf.');

define('MAX_DISPLAY_SEARCH_RESULTS_TITLE', 'Anzahl Suchergebnisse');
define('MAX_DISPLAY_SEARCH_RESULTS_DESC', 'Maximal Anzahl der Artikel, die als Suchergebnis angezeigt werden.');

define('MAX_DISPLAY_PAGE_LINKS_TITLE', 'Seiten-Links');
define('MAX_DISPLAY_PAGE_LINKS_DESC', 'Number of \'number\' links use for page-sets');

define('MAX_DISPLAY_NEW_PRODUCTS_TITLE', 'Neue Produkte');
define('MAX_DISPLAY_NEW_PRODUCTS_DESC', 'Maximale Anzahl von neuen Produkten, die in jeder Kategorie angezeigt werden.');

define('MAX_DISPLAY_UPCOMING_PRODUCTS_TITLE', 'Erwartete Produkte');
define('MAX_DISPLAY_UPCOMING_PRODUCTS_DESC', 'Maximale Anzahl der erwarteten Produkte, die angezeigt werden.');

define('MAX_RANDOM_SELECT_NEW_TITLE', 'Zuf&auml;llige Produktanzeigen');
define('MAX_RANDOM_SELECT_NEW_DESC', 'Die Menge der neuen Produkte, aus denen per Zufall ein Produkt angezeigt wird');

define('MAX_DISPLAY_NEW_NEWS_TITLE', 'Anzahl der News Meldungen');
define('MAX_DISPLAY_NEW_NEWS_DESC', 'Maximale Anzahl von Meldungen, die auf der Startseite angezeigt werden');

define('MAX_DISPLAY_CATEGORIES_PER_ROW_TITLE', 'Anzahl Kategorien pro Zeile');
define('MAX_DISPLAY_CATEGORIES_PER_ROW_DESC', 'Anzahl der Kategorien, die pro Zeile maximal angezeigt werden');

define('MAX_DISPLAY_PRODUCTS_NEW_TITLE', 'Anzahl neue Produkte');
define('MAX_DISPLAY_PRODUCTS_NEW_DESC', 'Anzahl der neuen Produkte, die in der &Uuml;bersicht der neuen Produkte maximal angezeigt werden');

define('MAX_DISPLAY_BESTSELLERS_TITLE', 'Verkaufsschlager');
define('MAX_DISPLAY_BESTSELLERS_DESC', 'Maximale Anzahl der anzuzeigenden Verkaufsschlager');

define('MAX_DISPLAY_ALSO_PURCHASED_TITLE', 'Kunden kauften auch');
define('MAX_DISPLAY_ALSO_PURCHASED_DESC', 'Maximale Anzahl von Produkten die im \'Kunden kauften auch\'-Block angezeigt werden');

define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_TITLE', 'Produktanzahl Bestell&uuml;bersicht-Block');
define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_DESC', 'Maximale Anzahl von Produkten, die im Bestell&uuml;bersichts-Block angezeigt werden');

define('MAX_DISPLAY_ORDER_HISTORY_TITLE', 'Anzahl Bestellungen im Bestell&uuml;bersicht-Block');
define('MAX_DISPLAY_ORDER_HISTORY_DESC', 'Maximale Anzahl von Bestellungen im Bestell&uuml;bersichts-Block');

define('MAX_DISPLAY_XSELL_PRODUCTS_TITLE', 'Produkt-Empfehlungen');
define('MAX_DISPLAY_XSELL_PRODUCTS_DESC', 'Maximale Anzahl von Produkten, die im \'Produkt-Empfehlungen\'-Block angezeigt werden');

define('MAX_DISPLAY_WISHLIST_PRODUCTS_TITLE', 'Wunschzettel');
define('MAX_DISPLAY_WISHLIST_PRODUCTS_DESC', 'Maximale Anzahl von Produkten auf der Wunschzettel-Seite');

define('MAX_DISPLAY_WISHLIST_BOX_TITLE', 'Wunschzettel-Infobox');
define('MAX_DISPLAY_WISHLIST_BOX_DESC', 'Maximale Anzahl von Produkten, die im \'Wunschzettel\'-Block angezeigt werden');

define('MAX_DISPLAY_PRODUCTS_NEWSFEED_TITLE', 'Neue Produkte im Newsfeed');
define('MAX_DISPLAY_PRODUCTS_NEWSFEED_DESC', 'Maximale Anzahl von Produkten, die im \'Newsfeed\' angezeigt werden');

define('MAX_RANDOM_SELECT_NEWSFEED_TITLE', 'Newsfeed');
define('MAX_RANDOM_SELECT_NEWSFEED_DESC', 'Die Menge der Newsfeeds, aus denen per Zufall ein Newsfeed angezeigt wird');

define('MAX_DISPLAY_NEW_NEWS_TITLE', 'Anzahl der News Meldungen');
define('MAX_DISPLAY_NEW_NEWS_DESC', 'Maximale Anzahl von Meldungen, die auf der Startseite angezeigt werden');

define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_TITLE', 'Anzahl der k&uuml;rzlich besuchten Produkte');
define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_DESC', 'Maximale Anzahl von Produkten, die im \'Products History\'-Block angezeigt werden. Dies sind die Produkte, die sich der Shopbesucher k&uuml;rzlich angesehen hat.');

define('SMALL_IMAGE_WIDTH_TITLE', 'Breite kleine Bilder');
define('SMALL_IMAGE_WIDTH_DESC', 'Die Breite von kleinen Bildern in Pixeln');

define('SMALL_IMAGE_HEIGHT_TITLE', 'H&ouml;he kleine Bilder');
define('SMALL_IMAGE_HEIGHT_DESC', 'Die H&ouml;he von kleinen Bildern in Pixeln');

define('HEADING_IMAGE_WIDTH_TITLE', 'Breite &Uuml;berschrift-Bilder');
define('HEADING_IMAGE_WIDTH_DESC', 'Die Breite von Bildern, die als &Uuml;berschrift verwendet werden, in Pixeln');

define('HEADING_IMAGE_HEIGHT_TITLE', 'H&ouml;he &Uuml;berschrift-Bilder');
define('HEADING_IMAGE_HEIGHT_DESC', 'Die H&ouml;he von Bildern, die als &Uuml;berschrift verwendet werden, in Pixeln');

define('SUBCATEGORY_IMAGE_WIDTH_TITLE', 'Breite Unterkategorie-Bilder');
define('SUBCATEGORY_IMAGE_WIDTH_DESC', 'Die Breite von Unterkategorie-Bildern in Pixeln');

define('SUBCATEGORY_IMAGE_HEIGHT_TITLE', 'H&ouml;he Unterkategorie-Bilder');
define('SUBCATEGORY_IMAGE_HEIGHT_DESC', 'Die H&ouml;he von Unterkategorie-Bildern in Pixeln');

define('CONFIG_CALCULATE_IMAGE_SIZE_TITLE', 'Berechnen der Bildgr&ouml;sse');
define('CONFIG_CALCULATE_IMAGE_SIZE_DESC', 'Soll die Bildgr&ouml;sse berechnet werden?');

define('IMAGE_REQUIRED_TITLE', 'Bild erforderlich');
define('IMAGE_REQUIRED_DESC', 'Einschalten, um tote Links zu Bildern darzustellen. Hilfreich bei der Entwicklung.');

define('CUSTOMER_NOT_LOGIN_TITLE', 'Zugangsberechtigung');
define('CUSTOMER_NOT_LOGIN_DESC', 'Die Zugangsberechtigung wird durch den Administrator nach Pr&uuml;fung der Kundendaten erteilt');

define('SEND_CUSTOMER_EDIT_EMAILS_TITLE', 'Kundendaten per Mail');
define('SEND_CUSTOMER_EDIT_EMAILS_DESC', 'Die Kundendaten werden per E-Mail an den Shopbetreiber versandt');

define('DEFAULT_MAX_ORDER_TITLE', 'Kundenkredit');
define('DEFAULT_MAX_ORDER_DESC', 'Maximaler Wert einer Bestellung');

define('ACCOUNT_GENDER_TITLE', 'Anrede');
define('ACCOUNT_GENDER_DESC', 'Die Anrede wird angezeigt und als Eingabe zwingend gefordert, wenn auf \'true\' gesetzt wird. Sonst wird es als nicht als Eingabem&ouml;glichkeit angezeigt.');

define('ACCOUNT_DOB_TITLE', 'Geburtsdatum');
define('ACCOUNT_DOB_DESC', 'Das Gebutsdatum wird als Eingabe zwingend gefordert, wenn auf \'true\' gesetzt wird. Sonst wird es als nicht als Eingabem&ouml;glichkeit angezeigt.');

define('ACCOUNT_NUMBER_TITLE', 'Kundennummer');
define('ACCOUNT_NUMBER_DESC', 'Verwaltung von eigenen Kundenummern, wenn auf \'true\' gesetzt wird. Sonst wird es als nicht als Eingabem&ouml;glichkeit angezeigt. Die Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_COMPANY_TITLE', 'Firmenname');
define('ACCOUNT_COMPANY_DESC', 'Ein Firmenname f&uuml;r gewerbliche Kunden kann eingegeben werden. Die Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_OWNER_TITLE', 'Inhaber');
define('ACCOUNT_OWNER_DESC', 'Der Inhaber der Firmen bei gewerblichen Kunden kann eingegeben werden. Die Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_VAT_ID_TITLE', 'Umsatzsteuer ID');
define('ACCOUNT_VAT_ID_DESC', 'Die Umsatzsteuer ID bei gewerblichen Kunden kann eingegeben werden.');


define('ACCOUNT_SUBURB_TITLE', 'Stadtteil');
define('ACCOUNT_SUBURB_DESC', 'Stadtteil wird angezeigt und kann eingegeben werden. Eine Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_STATE_TITLE', 'Bundesland');
define('ACCOUNT_STATE_DESC', 'Die Anzeige und Eingabe des Bundeslandes wird erm&ouml;glicht. Die Eingabe ist bei Anzeige zwingend notwendig.');

define('STORE_ORIGIN_COUNTRY_TITLE', 'L&auml;ndercode');
define('STORE_ORIGIN_COUNTRY_DESC', 'Eingabe des &quot;ISO 3166&quot;-L&auml;ndercodes des Shops, der im Versandbereich benutzt werden soll. Zum Finden Ihres L&auml;ndercodes besuchen Sie die <a href="http://www.din.de/gremien/nas/nabd/iso3166ma/codlstp1/index.html" target="_blank">ISO 3166');

define('STORE_ORIGIN_ZIP_TITLE', 'Postleitzahl');
define('STORE_ORIGIN_ZIP_DESC', 'Eingabe der Postleitzahl des Shops, die im Versandbereich benutzt werden soll.');

define('SHIPPING_MAX_WEIGHT_TITLE', 'Maximales Gewicht einer Bestellung');
define('SHIPPING_MAX_WEIGHT_DESC', 'Versandunternehmen haben ein H&ouml;chstgewicht f&uuml;r einzelne Pakete. Dies hier ist ein Wert, der f&uuml;r alle Unternehmen gleicherma&szlig;en gilt.');

define('SHIPPING_BOX_WEIGHT_TITLE', 'Gewicht der Verpackung.');
define('SHIPPING_BOX_WEIGHT_DESC', 'Wie hoch ist im Schnitt das Gewicht der Verpackung eines kleinen bis mittleren Paketes?');

define('SHIPPING_BOX_PADDING_TITLE', 'Prozentuale Mehrkosten f&uuml;r schwerere Pakete.');
define('SHIPPING_BOX_PADDING_DESC', 'Prozentuale Mehrkosten f&uuml;r schwerere Pakete. F&uuml;r 10% einfach 10 eingeben.');

define('PRODUCT_LIST_IMAGE_TITLE', 'Artikelbild anzeigen');
define('PRODUCT_LIST_IMAGE_DESC', 'M&ouml;chten Sie ein Artikelbild anzeigen?');

define('PRODUCT_LIST_MANUFACTURER_TITLE', 'Artikelhersteller anzeigen');
define('PRODUCT_LIST_MANUFACTURER_DESC', 'M&ouml;chten Sie den Hersteller des Artikels anzeigen?');

define('PRODUCT_LIST_MODEL_TITLE', 'Artikelmodell anzeigen');
define('PRODUCT_LIST_MODEL_DESC', 'M&ouml;chten Sie das Artikelmodell anzeigen?');

define('PRODUCT_LIST_NAME_TITLE', 'Artikelname anzeigen');
define('PRODUCT_LIST_NAME_DESC', 'M&ouml;chten Sie den Artikelnamen anzeigen?');

define('PRODUCT_LIST_UVP_TITLE', 'empfohlenen Verkaufspreis anzeigen');
define('PRODUCT_LIST_UVP_DESC', 'M&ouml;chten Sie den empfohlenen Verkaufspreis anzeigen?');

define('PRODUCT_LIST_PRICE_TITLE', 'Artikelpreis anzeigen');
define('PRODUCT_LIST_PRICE_DESC', 'M&ouml;chten Sie den Artikelpreis anzeigen?');

define('PRODUCT_LIST_QUANTITY_TITLE', 'Artikelanzahl anzeigen');
define('PRODUCT_LIST_QUANTITY_DESC', 'M&ouml;chten Sie die Anzahl der vorhandenen Artikel anzeigen?');

define('PRODUCT_LIST_WEIGHT_TITLE', 'Artikelgewicht anzeigen');
define('PRODUCT_LIST_WEIGHT_DESC', 'M&ouml;chten Sie das Artikelgewicht anzeigen?');

define('PRODUCT_LIST_BUY_NOW_TITLE', 'Jetzt Kaufen anzeigen');
define('PRODUCT_LIST_BUY_NOW_DESC', 'M&ouml;chten Sie den \'Jetzt Kaufen\' Button anzeigen?');

define('PRODUCT_LIST_FILTER_TITLE', 'Kategorie/Hersteller Filter anzeigen');
define('PRODUCT_LIST_FILTER_DESC', 'M&ouml;chten Sie den Kategorie/Hersteller Filter anzeigen (0:aus,1:an)?');

define('PRODUCT_LIST_SORT_ORDER_TITLE', 'Display Product Sort Order');
define('PRODUCT_LIST_SORT_ORDER_DESC', 'Do you want to display the Product Sort Order column?');

define('PREV_NEXT_BAR_LOCATION_TITLE', 'Position der Zur&uuml;ck/Vor Navigation');
define('PREV_NEXT_BAR_LOCATION_DESC', 'Legt die Position der Zur&uuml;ck/Vor Navigation fest (1:oben, 2:unten, 3:beides)');

define('STOCK_CHECK_TITLE', 'Bestandspr&uuml;fung');
define('STOCK_CHECK_DESC', 'Soll der Shop eine Bestandspr&uuml;fung durchf&uuml;hren?');

define('STOCK_LIMITED_TITLE', 'Lagerbestand aktualisieren');
define('STOCK_LIMITED_DESC', 'Soll der Shop nach einem Kauf den Artikel vom Bestand abziehen?');

define('STOCK_ALLOW_CHECKOUT_TITLE', 'Kaufen erlauben');
define('STOCK_ALLOW_CHECKOUT_DESC', 'Darf ein Kunde die Kaufabwicklung auch abschlie&szlig;en, wenn er Artikel gekauft hat, die nicht mehr vorr&auml;tig sind?');

define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_TITLE', 'Produktmarkierung, wenn nicht auf Lager');
define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_DESC', 'Kennzeichnung f&uuml;r Produkte, die nicht mehr vorr&auml;tig sind');


define('STOCK_REORDER_LEVEL_TITLE', 'Unterschrittene Mengen im Lagerbestand');
define('STOCK_REORDER_LEVEL_DESC', 'Ab diesem Bestand erfolgt eine Meldung an den Administrator');

define('STORE_PAGE_PARSE_TIME_TITLE', 'Speichere die Erstellungszeit einer Seite');
define('STORE_PAGE_PARSE_TIME_DESC', 'Die Zeit, die der Server zur Erstellung der Seite ben&ouml;tigt, wird gespeichert.');

define('STORE_PAGE_PARSE_TIME_LOG_TITLE', 'Ziel der Protokolldatei');
define('STORE_PAGE_PARSE_TIME_LOG_DESC', 'Verzeichnis und Dateiname der Datei, in der die Seitenerstellungszeiten gespeichert werden.');

define('STORE_PARSE_DATE_TIME_FORMAT_TITLE', 'Datumsformat der Protokolldatei');
define('STORE_PARSE_DATE_TIME_FORMAT_DESC', 'Format von Datum und Uhrzeit.');

define('DISPLAY_PAGE_PARSE_TIME_TITLE', 'Anzeige der Erstellungszeit einer Seite');
define('DISPLAY_PAGE_PARSE_TIME_DESC', 'Die Erstellungszeit einer Seite ist f&uuml;r den Besucher des Shops sichtbar. (\'Speichere die Erstellungszeit einer Seite\' mu&szlig; aktiviert sein.)');

define('USE_CACHE_TITLE', 'Benutze Cache');
define('USE_CACHE_DESC', 'Soll die Seite zwischengespeichert werden?');

define('DOWNLOAD_ENABLED_TITLE', 'Erm&ouml;gliche Download');
define('DOWNLOAD_ENABLED_DESC', 'Aktiviert die Shop-Funktionen, die es erm&ouml;glichen Datei herunterzuladen.');

define('DOWNLOAD_BY_REDIRECT_TITLE', 'Download by redirect');
define('DOWNLOAD_BY_REDIRECT_DESC', 'Use browser redirection for download. Disable on non-Unix systems.');

define('DOWNLOAD_MAX_DAYS_TITLE', 'Ablaufzeit (Tage)');
define('DOWNLOAD_MAX_DAYS_DESC', 'Setzt die Anzahl der Tage, nach denen der Link ung&uuml;ltig wird. 0 hei&szlig;t immer g&uuml;tig.');

define('DOWNLOAD_MAX_COUNT_TITLE', 'Maximale Anzahl der Downloads');
define('DOWNLOAD_MAX_COUNT_DESC', 'Setzt die maximal m&ouml;gliche Anzahl der Downloads, 0 hei&szlig;t dass kein Download erlaubt ist.');

define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_TITLE', 'Downloads Controller Update Status Value');
define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_DESC', 'What orders_status resets the Download days and Max Downloads - Default is 4');

define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_TITLE', 'Downloads Controller Download on hold message');
define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_DESC', 'Downloads Controller Download on hold message');

define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_TITLE', 'Downloads Controller Order Status Value');
define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_DESC', 'Downloads Controller Order Status Value - Default=2');

define('PDF_DATA_SHEET_TITLE', 'Erm&ouml;gliche PDF-Prospekt');
define('PDF_DATA_SHEET_DESC', 'M&ouml;chten Sie die Produktinformationen als PDF-Datei zum download anbieten?');

define('HEADER_COLOR_TABLE_TITLE', 'Farbe: Prospektkopf-Tabelle');
define('HEADER_COLOR_TABLE_DESC', 'Farbe in R, G, B, Werten (Beispiel: 230,230,230)');

define('PRODUCT_NAME_COLOR_TABLE_TITLE', 'Farbe: Produkname-Tabelle');
define('PRODUCT_NAME_COLOR_TABLE_DESC', 'Farbe in R, G, B, Werten (Beispiel: 230,230,230)');

define('FOOTER_CELL_BG_COLOR_TITLE', 'Hintergundfarbe: Prospektfuss');
define('FOOTER_CELL_BG_COLOR_DESC', 'Farbe in R, G, B, Werten (Beispiel: 210,210,210)');

define('SHOW_BACKGROUND_TITLE', 'Hintergrund');
define('SHOW_BACKGROUND_DESC', 'M&ouml;chten Sie die Hintergrundfarbe angezeigen?');

define('PAGE_BG_COLOR_TITLE', 'Hintergundfarbe: Prospekt');
define('PAGE_BG_COLOR_DESC', 'Farbe in R, G, B, Werten (Beispiel: 250,250,250)');

define('SHOW_WATERMARK_TITLE', 'Wasserzeichen');
define('SHOW_WATERMARK_DESC', 'M&ouml;chten Sie Ihren Firmenname als Wasserzeichen angezeigen?');

define('PAGE_WATERMARK_COLOR_TITLE', 'Wasserzeichenfarbe');
define('PAGE_WATERMARK_COLOR_DESC', 'Farbe in R, G, B, Werten (Beispiel: 236,245,255)');

define('PDF_IMAGE_KEEP_PROPORTIONS_TITLE', 'Produktbilder');
define('PDF_IMAGE_KEEP_PROPORTIONS_DESC', 'M&ouml;chten Sie die maximale bzw. minimale Produktgr&ouml;sse verwenden?');

define('MAX_IMAGE_WIDTH_TITLE', 'Breite der Produktbilder');
define('MAX_IMAGE_WIDTH_DESC', 'max. Breite in mm der Produktbilder');

define('MAX_IMAGE_HEIGHT_TITLE', 'H&ouml;he der Produktbilder');
define('MAX_IMAGE_HEIGHT_DESC', 'max. H&ouml;he in mm der Produktbilder');

define('PDF_TO_MM_FACTOR_TITLE', 'Faktor');
define('PDF_TO_MM_FACTOR_DESC', 'Produktbilder');

define('SHOW_PATH_TITLE', 'Kategoriename');
define('SHOW_PATH_DESC', 'M&ouml;chten Sie den Kategorienamen anzeigen?');

define('SHOW_IMAGES_TITLE', 'Produktbild');
define('SHOW_IMAGES_DESC', 'M&ouml;chten Sie das Produktbild anzeigen?');

define('SHOW_NAME_TITLE', 'Produktname');
define('SHOW_NAME_DESC', 'M&ouml;chten Sie den Produktnamen in der Beschreibung anzeigen?');

define('SHOW_MODEL_TITLE', 'Bestellnummer');
define('SHOW_MODEL_DESC', 'M&ouml;chten Sie die Bestellnummer anzeigen?');

define('SHOW_DESCRIPTION_TITLE', 'Produktbeschreibung');
define('SHOW_DESCRIPTION_DESC', 'M&ouml;chten Sie die Produktbeschreibung anzeigen?');

define('SHOW_MANUFACTURER_TITLE', 'Hersteller');
define('SHOW_MANUFACTURER_DESC', 'M&ouml;chten Sie den Hersteller anzeigen?');

define('SHOW_PRICE_TITLE', 'Produktpreis');
define('SHOW_PRICE_DESC', 'M&ouml;chten Sie den Produktpreis anzeigen?');

define('SHOW_SPECIALS_PRICE_TITLE', 'Sonderangebote');
define('SHOW_SPECIALS_PRICE_DESC', 'M&ouml;chten Sie den Angebotspreis anzeigen?');

define('SHOW_SPECIALS_PRICE_EXPIRES_TITLE', 'Datum Sonderangebote');
define('SHOW_SPECIALS_PRICE_EXPIRES_DESC', 'M&ouml;chten Sie das G&uuml;ltigkeitsdatum der Angebotspreise anzeigen?');

define('SHOW_TAX_CLASS_ID_TITLE', 'Steuersatz');
define('SHOW_TAX_CLASS_ID_DESC', 'M&ouml;chten Sie den Steuersatz anzeigen?');

define('SHOW_OPTIONS_TITLE', 'Produktoptionen');
define('SHOW_OPTIONS_DESC', 'M&ouml;chten Sie die Produktoptionen anzeigen?');

define('SHOW_OPTIONS_PRICE_TITLE', 'Preis der Produktoptionen');
define('SHOW_OPTIONS_PRICE_DESC', 'M&ouml;chten Sie die Preise der Produktoptionen anzeigen?');

define('TICKET_ENTRIES_MIN_LENGTH_TITLE', 'Supporttickets');
define('TICKET_ENTRIES_MIN_LENGTH_DESC', 'Die minimale Zeichenanzahl f&uuml;r Supporttickets');

define('TICKET_ADMIN_NAME_TITLE', 'Ticket Admin Name');
define('TICKET_ADMIN_NAME_DESC', 'Name des Administrators');

define('TICKET_USE_STATUS_TITLE', 'Statusanzeige im Shop');
define('TICKET_USE_STATUS_DESC', 'M&ouml;chten Sie den Supportticketstatus anzeigen?');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_TITLE', 'Erlaube &Auml;nderungen vom Kunden');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_DESC', 'Erlaube dem Kunden beim Antworten den Status zu &auml;ndern.');

define('TICKET_USE_DEPARTMENT_TITLE', 'Benutze Abteilung');
define('TICKET_USE_DEPARTMENT_DESC', 'Zeige die Abteilung im Ticket an.');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_TITLE', 'Abteilung');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_DESC', 'Erlaube dem Kunden beim Antworten die Abteilung zu &auml;ndern.');

define('TICKET_USE_PRIORITY_TITLE', 'Benutze Priorit&auml;t');
define('TICKET_USE_PRIORITY_DESC', 'Use Priority in Catalog');

define('TICKET_USE_ORDER_IDS_TITLE', 'Auftragsnummer');
define('TICKET_USE_ORDER_IDS_DESC', 'Wenn der Benutzer angemeldet ist, sind seine Auftragsnummern sichtbar.');

define('TICKET_USE_SUBJECT_TITLE', 'Show Subject');
define('TICKET_USE_SUBJECT_DESC', 'Show Subject');

define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_TITLE', 'Login');
define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_DESC', 'if you set this to true you can allow - notallow registered customers to view tickets without beeing logged in');

define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_TITLE', 'Shop - Login');
define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_DESC', '0 registered Customer must not be logged in to view ticket<br>1 registered Customer must  be logged in to view ticket');

define('SECURITY_CODE_LENGTH_TITLE', 'Einl&ouml;sungscode');
define('SECURITY_CODE_LENGTH_DESC', 'Setzt die L&auml;nge des Einl&ouml;ngscodes, je l&auml;nger dieser ist, desto sicherer ist er.');

define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_TITLE', 'Neukunden Gutschein');
define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_DESC', 'Setzt die H&ouml;he des Gutscheines, den ein Neukunde geschenkt bekommt fest. Feld leer lassen, wenn Neukunden kein \'Begr&uuml;&szlig;ungsgeschenk\' bekommen sollen.');

define('NEW_SIGNUP_DISCOUNT_COUPON_TITLE', 'Coupon-ID');
define('NEW_SIGNUP_DISCOUNT_COUPON_DESC', 'Dies ist die Coupon-ID, die ein Neukunde per E-Mail erh&auml;lt. Ist keine ID gesetzt, wird keine E-Mail verschickt.');

define('STORE_TEMPLATES_TITLE', 'Layout Vorlage');
define('STORE_TEMPLATES_DESC', 'Shop Templates');

define('SHOW_DATE_ADDED_AVAILABLE_TITLE', 'Produkt - Datum');
define('SHOW_DATE_ADDED_AVAILABLE_DESC', 'M&ouml;chten Sie im Shop das Datum von der Aufnahme des Produktes zeigen?');

define('SHOW_COUNTS_TITLE', 'Artikelanzahl hinter den Kategorienamen');
define('SHOW_COUNTS_DESC', 'Anzeigen, wieviele Produkte in jeder Kategorie vorhanden sind');

define('CATEGORIES_BOX_SCROLL_LIST_ON_TITLE', 'Kategorien-Auswahlliste');
define('CATEGORIES_BOX_SCROLL_LIST_ON_DESC', 'M&ouml;chten Sie die Kategorien als Auswahlliste anzeigen?');

define('CATEGORIES_SCROLL_BOX_LEN_TITLE', 'Kategorie-Menge');
define('CATEGORIES_SCROLL_BOX_LEN_DESC', 'Wenn Sie die Kategorien als Auswahlliste anzeigen wollen, legen Sie hier die L&auml;nge fest');

define('SHOPPING_CART_IMAGE_ON_TITLE', 'Bild im Warenkorbinhalt');
define('SHOPPING_CART_IMAGE_ON_DESC', 'M&ouml;chten Sie in der Detailansicht vom Warenkorb das Porduktbild anzeigen?');

define('SHOPPING_CART_MINI_IMAGE_TITLE', 'Bildverkleinerung');
define('SHOPPING_CART_MINI_IMAGE_DESC', 'Wert f&uuml;r die Verkleinerung in der Detailansicht vom Warenkorb');

define('DISPLAY_CART_TITLE', 'Warenkorb anzeigen');
define('DISPLAY_CART_DESC', 'Zeigt den Warenkorb an, nachdem diesem ein Produkt hinzugef&uuml;gt wurde');

define('ALLOW_GUEST_TO_TELL_A_FRIEND_TITLE', 'Empfehlen auch f&uuml;r G&auml;ste');
define('ALLOW_GUEST_TO_TELL_A_FRIEND_DESC', 'G&auml;sten erlauben, ein Produkt zu empfehlen');

define('ALLOW_CATEGORY_DESCRIPTIONS_TITLE', 'Erlaube Kategorienbeschreibung');
define('ALLOW_CATEGORY_DESCRIPTIONS_DESC', 'Erlaubt eine ausf&uuml;hrliche Beschreibung der einzelnen Kategorien');

define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_TITLE', 'Erlaube News-Kategorienbeschreibung');
define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_DESC', 'Erlaubt eine ausf&uuml;hrliche Beschreibung der einzelnen News-Kategorien');

define('SHOW_PRODUCTS_MODEL_TITLE', 'Navigation mit Bestellnummer');
define('SHOW_PRODUCTS_MODEL_DESC', 'M&ouml;chten Sie die auf der Produkt-Informations-Seite die Bestellnummer in der Navation anzeigen?');

define('BREADCRUMB_SEPARATOR_TITLE', 'Trenner f&uuml;r Men&uuml;ebenenanzeige');
define('BREADCRUMB_SEPARATOR_DESC', 'Trenner f&uuml;r die Anzeige der Men&uuml;ebene, in der sich der Kunde gerade aufh&auml;lt.');

define('BLOCK_BEST_SELLERS_IMAGE_TITLE', 'Bild im Block Verkaufschlager');
define('BLOCK_BEST_SELLERS_IMAGE_DESC', 'Bild im Content-Block Verkaufschlager anzeigen?');

define('BLOCK_PRODUCTS_HISTORY_IMAGE_TITLE', 'Bild im Block besuchte Produkte');
define('BLOCK_PRODUCTS_HISTORY_IMAGE_DESC', 'Bild im Content-Block gekaufte Produkte anzeigen?');

define('BLOCK_WISHLIST_IMAGE_TITLE', 'Bild im Block Wunschliste');
define('BLOCK_WISHLIST_IMAGE_DESC', 'Bild im Content-Block Wunschliste anzeigen?');

define('BLOCK_XSELL_PRODUCTS_IMAGE_TITLE', 'Bild im Block &auml;hnliche Produkte');
define('BLOCK_XSELL_PRODUCTS_IMAGE_DESC', 'Bild im Content-Block &auml;hnliche Produkte anzeigen?');

define('OOS_GD_LIB_VERSION_TITLE', 'GD-Bibliothek');
define('OOS_GD_LIB_VERSION_DESC', '1 f&uuml;r alte GD-Lib Version (1.x)<br> 2 f&uuml;r aktuelle GD-Lib Version (2.x)');

define('OOS_SMALLIMAGE_WAY_OF_RESIZE_TITLE', 'Bildbearbeitung kleines Bild');
define('OOS_SMALLIMAGE_WAY_OF_RESIZE_DESC', '0: proportionale Verkleinerung; Breite oder H&ouml;he ist die maximale Gr&ouml;&szlig;e<br> 1: Bild wird proportional in das neue Bild kopiert. Die Hintergrundfarbe wird  ber&uuml;cksichtigt.<br> 2: ein Ausschnitt wird in das neue Bild kopiert');

define('OOS_SMALL_IMAGE_WIDTH_TITLE', 'Breite kleine Bilder');
define('OOS_SMALL_IMAGE_WIDTH_DESC', 'Die Breite von kleinen Bildern in Pixeln');

define('OOS_SMALL_IMAGE_HEIGHT_TITLE', 'H&ouml;he kleine Bilder');
define('OOS_SMALL_IMAGE_HEIGHT_DESC', 'Die H&ouml;he von kleinen Bildern in Pixeln');

define('OOS_IMAGE_BGCOLOUR_R_TITLE', 'Hintergrund kleines Bild R');
define('OOS_IMAGE_BGCOLOUR_R_DESC', 'Rotwert f&uuml;r kleines Produktbild');

define('OOS_IMAGE_BGCOLOUR_G_TITLE', 'Hintergrund kleines Bild G');
define('OOS_IMAGE_BGCOLOUR_G_DESC', 'Gr&uuml;nwert f&uuml;r kleines Produktbild');

define('OOS_IMAGE_BGCOLOUR_B_TITLE', 'Hintergrund kleines Bild B');
define('OOS_IMAGE_BGCOLOUR_B_DESC', 'Blauwert f&uuml;r kleines Produktbild');

define('OOS_BIGIMAGE_WAY_OF_RESIZE_TITLE', 'Bildbearbeitung grosses Bild');
define('OOS_BIGIMAGE_WAY_OF_RESIZE_DESC', '0: proportionale Verkleinerung; Breite oder H&ouml;he ist die maximale Gr&ouml;&szlig;e<br> 1: Bild wird proportional in das neue Bild kopiert. Die Hintergrundfarbe wird  ber&uuml;cksichtigt.<br> 2: ein Ausschnitt wird in das neue Bild kopiert');

define('OOS_BIGIMAGE_WIDTH_TITLE', 'Breite grosses Bild');
define('OOS_BIGIMAGE_WIDTH_DESC', 'Breite vom grossen Bild in Pixel');

define('OOS_BIGIMAGE_HEIGHT_TITLE', 'H&ouml;he grosses Bild');
define('OOS_BIGIMAGE_HEIGHT_DESC', 'H&ouml;he vom grossen Bild in Pixel');

define('OOS_WATERMARK_TITLE', 'Wasserzeichen');
define('OOS_WATERMARK_DESC', 'M&ouml;chten Sie im grossen Bild ein Wasserzeichen einf&uuml;gen?');

define('OOS_WATERMARK_QUALITY_TITLE', 'Qualit&auml;t vom Wasserzeichen');
define('OOS_WATERMARK_QUALITY_DESC', 'Hier legen Sie die Qualit&auml;t vom Wasserzeichen fest');

define('OOS_IMAGE_SWF_TITLE', 'Ming');
define('OOS_IMAGE_SWF_DESC', 'Ist Ming installiert?');

define('OOS_SWF_MOVIECLIP_TITLE', 'Flash-Film');
define('OOS_SWF_MOVIECLIP_DESC', 'M&ouml;chten Sie das kleine Produktbild in einen Flash-Film umwandeln?');

define('OOS_SWF_BGCOLOUR_R_TITLE', 'Hintergrund vom Flashfilm R');
define('OOS_SWF_BGCOLOUR_R_DESC', 'Rotwert f&uuml;r kleines Produktbild im Flashfilm');

define('OOS_SWF_BGCOLOUR_G_TITLE', 'Hintergrund vom Flashfilm G');
define('OOS_SWF_BGCOLOUR_G_DESC', 'Gr&uuml;nwert f&uuml;r kleines Produktbild im Flashfilm');

define('OOS_SWF_BGCOLOUR_B_TITLE', 'Hintergrund vom Flashfilm B');
define('OOS_SWF_BGCOLOUR_B_DESC', 'Blauwert f&uuml;r kleines Produktbild im Flashfilm');

define('OOS_RANDOM_PICTURE_NAME_TITLE', 'Dateiname');
define('OOS_RANDOM_PICTURE_NAME_DESC', 'Zuf&auml;llig erzeugter Dateiname f&uuml;r die Grafik');

define('OOS_MO_PIC_TITLE', 'Mehr Produktbilder');
define('OOS_MO_PIC_DESC', 'Weitere Produktbilder auf der Produktinfoseite zeigen?');

define('PSM_TITLE', 'Preissuchmaschine');
define('PSM_DESC', 'M&ouml;chten Sie Die Schnittstelle zur Preissuchmaschine verwenden? Hierf&uuml;r ist eine Anmeldung bei <a href="http://www.preissuchmaschine.de/psm_frontend/main.asp?content=mitmachenreissuchmaschine" target="_blank">http://www.preissuchmaschine.de</a> n');

define('OOS_PSM_DIR_TITLE', 'Verzeichnis Preissuchmaschine');
define('OOS_PSM_DIR_DESC', 'Die Datei f&uuml;r die Preissuchmaschine soll in diesem Shop-Verzeichnis gespeichert werden.');

define('OOS_PSM_FILE_TITLE', 'Dateiname');
define('OOS_PSM_FILE_DESC', 'Die Datei f&uuml;r die Preissuchmaschine');

define('OOS_META_TITLE_TITLE', 'Shop Titel');
define('OOS_META_TITLE_DESC', 'Der Titel');

define('OOS_META_DESCRIPTION_TITLE', 'Beschreibung');
define('OOS_META_DESCRIPTION_DESC', 'Die Beschreibung Ihres Shop(max. 250 Zeichen)');

define('OOS_META_KEYWORDS_TITLE', 'Suchworte');
define('OOS_META_KEYWORDS_DESC', 'Geben Sie hier Ihre Schl&uuml;sselw&ouml;rter(durch Komma getrennt) ein(max. 250 Zeichen)');

define('OOS_META_PAGE_TOPIC_TITLE', 'Thema');
define('OOS_META_PAGE_TOPIC_DESC', 'Das Thema Ihres Shop');

define('OOS_META_AUDIENCE_TITLE', 'Zielgruppe');
define('OOS_META_AUDIENCE_DESC', 'Ihre Zielgruppe');

define('OOS_META_AUTHOR_TITLE', 'Autor');
define('OOS_META_AUTHOR_DESC', 'Der Autor des Shop');

define('OOS_META_COPYRIGHT_TITLE', 'Copyright');
define('OOS_META_COPYRIGHT_DESC', 'Der Entwickler des Shop');

define('OOS_META_PAGE_TYPE_TITLE', 'Seitentyp');
define('OOS_META_PAGE_TYPE_DESC', 'Typ der Internetpr&auml;senz');

define('OOS_META_PUBLISHER_TITLE', 'Herausgeber');
define('OOS_META_PUBLISHER_DESC', 'Der Herausgeber');

define('OOS_META_ROBOTS_TITLE', 'Indizierung');
define('OOS_META_ROBOTS_DESC', 'Typ der Indizierung');

define('OOS_META_EXPIRES_TITLE', 'G&uuml;ltigkeitsdauer');
define('OOS_META_EXPIRES_DESC', 'Angebot verf&auml;llt am:( 0 f&uuml;r h&auml;ufig ge&auml;nderte Sites)');

define('OOS_META_PAGE_PRAGMA_TITLE', 'Proxy Caching');
define('OOS_META_PAGE_PRAGMA_DESC', 'Ihr Shop soll von Proxys zwischengespeichert werden?');

define('OOS_META_REVISIT_AFTER_TITLE', 'Wiederbesuchen nach');
define('OOS_META_REVISIT_AFTER_DESC', 'Wann soll die Suchmaschine Ihre Seite wiederbesuchen?');

define('OOS_META_PRODUKT_TITLE', 'Pflege im Artikel');
define('OOS_META_PRODUKT_DESC', 'M&ouml;chten Sie Keywords und Description f&uuml;r jeden Artikel pflegen?');

define('OOS_META_KATEGORIEN_TITLE', 'Pflege in Kategorien');
define('OOS_META_KATEGORIEN_DESC', 'M&ouml;chten Sie Keywords und Description f&uuml;r jede Kategorie pflegen');

define('OOS_META_INDEX_PAGE_TITLE', 'Index Seite erzeugen');
define('OOS_META_INDEX_PAGE_DESC', 'M&ouml;chten Sie eine Index-Seite mit allen Artikeln f&uuml;r Suchmaschinen erzeugen?');

define('OOS_META_INDEX_PATH_TITLE', 'Pfad f&uuml;r IndexSeite');
define('OOS_META_INDEX_PATH_DESC', 'Die Datei f&uuml;r die Suchmaschinen soll in diesem Shop-Verzeichnis gespeichert werden.');


define('ENABLE_SPIDER_FRIENDLY_LINKS_TITLE', 'Spider-frundliche Links');
define('ENABLE_SPIDER_FRIENDLY_LINKS_DESC', 'Erm&ouml;gliche Spider-freundliche Links (empfohlen). ACHTUNG: Es sind ggf. &Auml;nderungen in der Konfiguration des Webservers notwendig!');

define('MULTIPLE_CATEGORIES_USE_TITLE', 'Multi-Kategorien nutzen');
define('MULTIPLE_CATEGORIES_USE_DESC', 'Auf true setzen, um das Hinzuf&uuml;gen eines Produkts zu mehreren Kategorien mit einem Klick zu erm&ouml;glichen.');

define('OOS_SPAW_TITLE', 'SPAW PHP WYSIWYG Editor');
define('OOS_SPAW_DESC', 'SPAW PHP WYSIWYG bei der Datenerfassung verwenden?');

define('SLAVE_LIST_IMAGE_TITLE', 'Anzeige des Slave-Bildes');
define('SLAVE_LIST_IMAGE_DESC', 'Soll das Produktbild gezeigt werden?');

define('SLAVE_LIST_MANUFACTURER_TITLE', 'Anzeige des Slave-Herstellers');
define('SLAVE_LIST_MANUFACTURER_DESC', 'Soll der Name des Produktherstellers angezeigt werden?');

define('SLAVE_LIST_MODEL_TITLE', 'Anzeige des Slave-Modells');
define('SLAVE_LIST_MODEL_DESC', 'Soll das Produktmodell angezeigt werden?');

define('SLAVE_LIST_NAME_TITLE', 'Anzeige des Slave-Names');
define('SLAVE_LIST_NAME_DESC', 'Soll der Produktname angezeigt werden?');

define('SLAVE_LIST_PRICE_TITLE', 'Anzeige des Slave-Preises');
define('SLAVE_LIST_PRICE_DESC', 'Soll der Produktpreis angezeigt werden?');

define('SLAVE_LIST_QUANTITY_TITLE', 'Anzeige der Slave-Anzahl');
define('SLAVE_LIST_QUANTITY_DESC', 'Soll die Anzahl der Produkte angezeigt werden?');

define('SLAVE_LIST_WEIGHT_TITLE', 'Anzeige des Slave-Gewichts');
define('SLAVE_LIST_WEIGHT_DESC', 'Soll das Produktgewicht angezeigt werden?');

define('SLAVE_LIST_BUY_NOW_TITLE', 'Jetzt kaufen');
define('SLAVE_LIST_BUY_NOW_DESC', 'Soll die \'Jetzt kaufen\'-Zeile angezeigt werden?');

