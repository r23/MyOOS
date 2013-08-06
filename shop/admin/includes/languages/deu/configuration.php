<?php
/* ----------------------------------------------------------------------
   $Id: configuration.php 442 2013-06-27 00:04:01Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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

define('TEXT_INFO_EDIT_INTRO', 'Bitte führen Sie alle notwendigen Änderungen durch');
define('TEXT_INFO_DATE_ADDED', 'hinzugefügt am:');
define('TEXT_INFO_LAST_MODIFIED', 'letzte Änderung:');


define('STORE_NAME_TITLE', 'Shop Name');
define('STORE_NAME_DESC', 'Der Name meines Shops');

define('STORE_OWNER_TITLE', 'Shop Inhaber');
define('STORE_OWNER_DESC', 'Der Name des Shop-Betreibers');

define('STORE_OWNER_EMAIL_ADDRESS_TITLE', 'E-Mail Adresse');
define('STORE_OWNER_EMAIL_ADDRESS_DESC', 'Die E-Mail Adresse des Shop-Betreibers');

define('STORE_OWNER_VAT_ID_TITLE' , 'Umsatzsteuer ID');
define('STORE_OWNER_VAT_ID_DESC' , 'Die Umsatzsteuer ID Ihres Unternehmens');

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


define('SKYPE_ME_TITLE', 'Skype-Name');
define('SKYPE_ME_DESC', 'Wenn Sie noch keinen Skype-Namen haben, können Sie <a href="http://www.skype.com/go/download" target="_blank">Skype herunterladen</a>, um einen Skype-Namen zu erstellen.');

define('STORE_COUNTRY_TITLE', 'Land');
define('STORE_COUNTRY_DESC', 'In welchem Land wird der Shop betrieben <br><br><b>Hinweis: Bitte vergessen Sie nicht, das Bundesland zu aktualisieren</b>');

define('STORE_ZONE_TITLE', 'Bundesland');
define('STORE_ZONE_DESC', 'In welchem Bundesland wird der Shop betrieben?');

define('EXPECTED_PRODUCTS_SORT_TITLE', 'Sortierreihenfolge erwartete Produkte');
define('EXPECTED_PRODUCTS_SORT_DESC', 'Sortierreihenfolge, die im \'erwartete Produkte\'-Block verwendet wird.');

define('EXPECTED_PRODUCTS_FIELD_TITLE', 'Sortierspalte erwartete Produkte');
define('EXPECTED_PRODUCTS_FIELD_DESC', 'Die Spalte, nach der im \'erwartete Produkte\'-Block sortiert wird.');

define('USE_DEFAULT_LANGUAGE_CURRENCY_TITLE', 'Währung automatisch wechseln');
define('USE_DEFAULT_LANGUAGE_CURRENCY_DESC', 'Wechselt automatisch die Währung anhand der eingestellten Sprache');

define('ADVANCED_SEARCH_DEFAULT_OPERATOR_TITLE', 'Standardoperator für Suchfunktionen');
define('ADVANCED_SEARCH_DEFAULT_OPERATOR_DESC', 'Die Standardverknüpfung, mit der mehrere Suchbegriffe verknüpft werden');

define('TAX_DECIMAL_PLACES_TITLE', 'Dezimalstellen der Steuer');
define('TAX_DECIMAL_PLACES_DESC', 'Anzahl der Dezimalstellen der Steuer');

define('DISPLAY_PRICE_WITH_TAX_TITLE', 'Preise inkl. Steuer');
define('DISPLAY_PRICE_WITH_TAX_DESC', 'Preise incl. Steuer anzeigen (true) oder die Steuer dem Gesamtbetrag hinzurechnen (false)');

define('DISPLAY_CONDITIONS_ON_CHECKOUT_TITLE', 'Unterzeichnen der AGB');
define('DISPLAY_CONDITIONS_ON_CHECKOUT_DESC', 'Im Bestellvorgang Ihre Allgemeine Geschäfts- und Lieferbedingungen anzeigen, bevor fortgefahren werden kann.');

define('PRODUCTS_OPTIONS_SORT_BY_PRICE_TITLE', 'Sortierung Produktoptionen');
define('PRODUCTS_OPTIONS_SORT_BY_PRICE_DESC', 'Möchten Sie die Produktopionen nach Preisen sortieren?');

define('WEB_SEARCH_GOOGLE_KEY_TITLE', 'Google API Lizenzschlüssel');
define('WEB_SEARCH_GOOGLE_KEY_DESC', 'Google API Lizenzschlüssel (kostenlos!) <a href="http://www.google.com/apis" target="_blank">http://www.google.com/apis</a>.');

define('ENTRY_FIRST_NAME_MIN_LENGTH_TITLE', 'Vorname');
define('ENTRY_FIRST_NAME_MIN_LENGTH_DESC', 'Mindestlänge des Vornames');

define('ENTRY_LAST_NAME_MIN_LENGTH_TITLE', 'Nachname');
define('ENTRY_LAST_NAME_MIN_LENGTH_DESC', 'Mindestlänge des Nachnames');

define('ENTRY_DOB_MIN_LENGTH_TITLE', 'Geburtsdatum');
define('ENTRY_DOB_MIN_LENGTH_DESC', 'Mindestlänge des Geburtsdatums');

define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_TITLE', 'E-Mail Adresse');
define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_DESC', 'Mindestlänge der E-Mail Adresse');

define('ENTRY_STREET_ADDRESS_MIN_LENGTH_TITLE', 'Strasse');
define('ENTRY_STREET_ADDRESS_MIN_LENGTH_DESC', 'Mindestlänge des Strassennamens');

define('ENTRY_COMPANY_LENGTH_TITLE', 'Firma');
define('ENTRY_COMPANY_LENGTH_DESC', 'Mindestlänge des Firmennames');

define('ENTRY_POSTCODE_MIN_LENGTH_TITLE', 'Postleitzahl');
define('ENTRY_POSTCODE_MIN_LENGTH_DESC', 'Mindestlänge der Postleitzahl');

define('ENTRY_CITY_MIN_LENGTH_TITLE', 'Stadt');
define('ENTRY_CITY_MIN_LENGTH_DESC', 'Mindestlänge des Namens der Stadt');

define('ENTRY_STATE_MIN_LENGTH_TITLE', 'Bundesland');
define('ENTRY_STATE_MIN_LENGTH_DESC', 'Mindestlänge des Namens des Bundeslandes');

define('ENTRY_TELEPHONE_MIN_LENGTH_TITLE', 'Telefonnummer');
define('ENTRY_TELEPHONE_MIN_LENGTH_DESC', 'Mindestlänge der Telefonnummer');

define('ENTRY_PASSWORD_MIN_LENGTH_TITLE', 'Passwort');
define('ENTRY_PASSWORD_MIN_LENGTH_DESC', 'Mindestlänge des Passworts');

define('CC_OWNER_MIN_LENGTH_TITLE', 'Name Kreditkarteneigentümer');
define('CC_OWNER_MIN_LENGTH_DESC', 'Mindestlänge des Names vom Kreditkarteneigentümer');

define('CC_NUMBER_MIN_LENGTH_TITLE', 'Kreditkartennummer');
define('CC_NUMBER_MIN_LENGTH_DESC', 'Mindestlänge der Kreditkartennummer');

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

define('MAX_ADDRESS_BOOK_ENTRIES_TITLE', 'Anzahl Adressbucheinträge');
define('MAX_ADDRESS_BOOK_ENTRIES_DESC', 'Maximale Anzahl von Adressbucheinträgen, die ein Kunde besitzen darf.');

define('MAX_DISPLAY_SEARCH_RESULTS_TITLE', 'Anzahl Suchergebnisse');
define('MAX_DISPLAY_SEARCH_RESULTS_DESC', 'Maximal Anzahl der Artikel, die als Suchergebnis angezeigt werden.');

define('MAX_DISPLAY_PAGE_LINKS_TITLE', 'Seiten-Links');
define('MAX_DISPLAY_PAGE_LINKS_DESC', 'Number of \'number\' links use for page-sets');

define('MAX_DISPLAY_NEW_PRODUCTS_TITLE', 'Neue Produkte');
define('MAX_DISPLAY_NEW_PRODUCTS_DESC', 'Maximale Anzahl von neuen Produkten, die in jeder Kategorie angezeigt werden.');

define('MAX_DISPLAY_UPCOMING_PRODUCTS_TITLE', 'Erwartete Produkte');
define('MAX_DISPLAY_UPCOMING_PRODUCTS_DESC', 'Maximale Anzahl der erwarteten Produkte, die angezeigt werden.');

define('MAX_RANDOM_SELECT_NEW_TITLE', 'Zufällige Produktanzeigen');
define('MAX_RANDOM_SELECT_NEW_DESC', 'Die Menge der neuen Produkte, aus denen per Zufall ein Produkt angezeigt wird');

define('MAX_DISPLAY_NEW_NEWS_TITLE', 'Anzahl der News Meldungen');
define('MAX_DISPLAY_NEW_NEWS_DESC', 'Maximale Anzahl von Meldungen, die auf der Startseite angezeigt werden');

define('MAX_DISPLAY_CATEGORIES_PER_ROW_TITLE', 'Anzahl Kategorien pro Zeile');
define('MAX_DISPLAY_CATEGORIES_PER_ROW_DESC', 'Anzahl der Kategorien, die pro Zeile maximal angezeigt werden');

define('MAX_DISPLAY_PRODUCTS_NEW_TITLE', 'Anzahl neue Produkte');
define('MAX_DISPLAY_PRODUCTS_NEW_DESC', 'Anzahl der neuen Produkte, die in der Übersicht der neuen Produkte maximal angezeigt werden');

define('MAX_DISPLAY_BESTSELLERS_TITLE', 'Verkaufsschlager');
define('MAX_DISPLAY_BESTSELLERS_DESC', 'Maximale Anzahl der anzuzeigenden Verkaufsschlager');

define('MAX_DISPLAY_ALSO_PURCHASED_TITLE', 'Kunden kauften auch');
define('MAX_DISPLAY_ALSO_PURCHASED_DESC', 'Maximale Anzahl von Produkten die im \'Kunden kauften auch\'-Block angezeigt werden');

define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_TITLE', 'Produktanzahl Bestellübersicht-Block');
define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_DESC', 'Maximale Anzahl von Produkten, die im Bestellübersichts-Block angezeigt werden');

define('MAX_DISPLAY_ORDER_HISTORY_TITLE', 'Anzahl Bestellungen im Bestellübersicht-Block');
define('MAX_DISPLAY_ORDER_HISTORY_DESC', 'Maximale Anzahl von Bestellungen im Bestellübersichts-Block');

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

define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_TITLE', 'Anzahl der kürzlich besuchten Produkte');
define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_DESC', 'Maximale Anzahl von Produkten, die im \'Products History\'-Block angezeigt werden. Dies sind die Produkte, die sich der Shopbesucher kürzlich angesehen hat.');

define('SMALL_IMAGE_WIDTH_TITLE', 'Breite kleine Bilder');
define('SMALL_IMAGE_WIDTH_DESC', 'Die Breite von kleinen Bildern in Pixeln');

define('SMALL_IMAGE_HEIGHT_TITLE', 'Höhe kleine Bilder');
define('SMALL_IMAGE_HEIGHT_DESC', 'Die Höhe von kleinen Bildern in Pixeln');

define('HEADING_IMAGE_WIDTH_TITLE', 'Breite Überschrift-Bilder');
define('HEADING_IMAGE_WIDTH_DESC', 'Die Breite von Bildern, die als Überschrift verwendet werden, in Pixeln');

define('HEADING_IMAGE_HEIGHT_TITLE', 'Höhe Überschrift-Bilder');
define('HEADING_IMAGE_HEIGHT_DESC', 'Die Höhe von Bildern, die als Überschrift verwendet werden, in Pixeln');

define('SUBCATEGORY_IMAGE_WIDTH_TITLE', 'Breite Unterkategorie-Bilder');
define('SUBCATEGORY_IMAGE_WIDTH_DESC', 'Die Breite von Unterkategorie-Bildern in Pixeln');

define('SUBCATEGORY_IMAGE_HEIGHT_TITLE', 'Höhe Unterkategorie-Bilder');
define('SUBCATEGORY_IMAGE_HEIGHT_DESC', 'Die Höhe von Unterkategorie-Bildern in Pixeln');

define('CONFIG_CALCULATE_IMAGE_SIZE_TITLE', 'Berechnen der Bildgrösse');
define('CONFIG_CALCULATE_IMAGE_SIZE_DESC', 'Soll die Bildgrösse berechnet werden?');

define('IMAGE_REQUIRED_TITLE', 'Bild erforderlich');
define('IMAGE_REQUIRED_DESC', 'Einschalten, um tote Links zu Bildern darzustellen. Hilfreich bei der Entwicklung.');

define('CUSTOMER_NOT_LOGIN_TITLE', 'Zugangsberechtigung');
define('CUSTOMER_NOT_LOGIN_DESC', 'Die Zugangsberechtigung wird durch den Administrator nach Prüfung der Kundendaten erteilt');

define('SEND_CUSTOMER_EDIT_EMAILS_TITLE', 'Kundendaten per Mail');
define('SEND_CUSTOMER_EDIT_EMAILS_DESC', 'Die Kundendaten werden per E-Mail an den Shopbetreiber versandt');

define('DEFAULT_MAX_ORDER_TITLE', 'Kundenkredit');
define('DEFAULT_MAX_ORDER_DESC', 'Maximaler Wert einer Bestellung');

define('ACCOUNT_GENDER_TITLE', 'Anrede');
define('ACCOUNT_GENDER_DESC', 'Die Anrede wird angezeigt und als Eingabe zwingend gefordert, wenn auf \'true\' gesetzt wird. Sonst wird es als nicht als Eingabemöglichkeit angezeigt.');

define('ACCOUNT_DOB_TITLE', 'Geburtsdatum');
define('ACCOUNT_DOB_DESC', 'Das Gebutsdatum wird als Eingabe zwingend gefordert, wenn auf \'true\' gesetzt wird. Sonst wird es als nicht als Eingabemöglichkeit angezeigt.');

define('ACCOUNT_NUMBER_TITLE', 'Kundennummer');
define('ACCOUNT_NUMBER_DESC', 'Verwaltung von eigenen Kundenummern, wenn auf \'true\' gesetzt wird. Sonst wird es als nicht als Eingabemöglichkeit angezeigt. Die Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_COMPANY_TITLE', 'Firmenname');
define('ACCOUNT_COMPANY_DESC', 'Ein Firmenname für gewerbliche Kunden kann eingegeben werden. Die Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_OWNER_TITLE', 'Inhaber');
define('ACCOUNT_OWNER_DESC', 'Der Inhaber der Firmen bei gewerblichen Kunden kann eingegeben werden. Die Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_VAT_ID_TITLE', 'Umsatzsteuer ID');
define('ACCOUNT_VAT_ID_DESC', 'Die Umsatzsteuer ID bei gewerblichen Kunden kann eingegeben werden.');


define('ACCOUNT_SUBURB_TITLE', 'Stadtteil');
define('ACCOUNT_SUBURB_DESC', 'Stadtteil wird angezeigt und kann eingegeben werden. Eine Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_STATE_TITLE', 'Bundesland');
define('ACCOUNT_STATE_DESC', 'Die Anzeige und Eingabe des Bundeslandes wird ermöglicht. Die Eingabe ist bei Anzeige zwingend notwendig.');

define('STORE_ORIGIN_COUNTRY_TITLE', 'Ländercode');
define('STORE_ORIGIN_COUNTRY_DESC', 'Eingabe des &quot;ISO 3166&quot;-Ländercodes des Shops, der im Versandbereich benutzt werden soll. Zum Finden Ihres Ländercodes besuchen Sie die <a href="http://www.din.de/gremien/nas/nabd/iso3166ma/codlstp1/index.html" target="_blank">ISO 3166');

define('STORE_ORIGIN_ZIP_TITLE', 'Postleitzahl');
define('STORE_ORIGIN_ZIP_DESC', 'Eingabe der Postleitzahl des Shops, die im Versandbereich benutzt werden soll.');

define('SHIPPING_MAX_WEIGHT_TITLE', 'Maximales Gewicht einer Bestellung');
define('SHIPPING_MAX_WEIGHT_DESC', 'Versandunternehmen haben ein Höchstgewicht für einzelne Pakete. Dies hier ist ein Wert, der für alle Unternehmen gleichermaßen gilt.');

define('SHIPPING_BOX_WEIGHT_TITLE', 'Gewicht der Verpackung.');
define('SHIPPING_BOX_WEIGHT_DESC', 'Wie hoch ist im Schnitt das Gewicht der Verpackung eines kleinen bis mittleren Paketes?');

define('SHIPPING_BOX_PADDING_TITLE', 'Prozentuale Mehrkosten für schwerere Pakete.');
define('SHIPPING_BOX_PADDING_DESC', 'Prozentuale Mehrkosten für schwerere Pakete. Für 10% einfach 10 eingeben.');

define('PRODUCT_LIST_IMAGE_TITLE', 'Artikelbild anzeigen');
define('PRODUCT_LIST_IMAGE_DESC', 'Möchten Sie ein Artikelbild anzeigen?');

define('PRODUCT_LIST_MANUFACTURER_TITLE', 'Artikelhersteller anzeigen');
define('PRODUCT_LIST_MANUFACTURER_DESC', 'Möchten Sie den Hersteller des Artikels anzeigen?');

define('PRODUCT_LIST_MODEL_TITLE', 'Artikelmodell anzeigen');
define('PRODUCT_LIST_MODEL_DESC', 'Möchten Sie das Artikelmodell anzeigen?');

define('PRODUCT_LIST_NAME_TITLE', 'Artikelname anzeigen');
define('PRODUCT_LIST_NAME_DESC', 'Möchten Sie den Artikelnamen anzeigen?');

define('PRODUCT_LIST_UVP_TITLE', 'empfohlenen Verkaufspreis anzeigen');
define('PRODUCT_LIST_UVP_DESC', 'Möchten Sie den empfohlenen Verkaufspreis anzeigen?');

define('PRODUCT_LIST_PRICE_TITLE', 'Artikelpreis anzeigen');
define('PRODUCT_LIST_PRICE_DESC', 'Möchten Sie den Artikelpreis anzeigen?');

define('PRODUCT_LIST_QUANTITY_TITLE', 'Artikelanzahl anzeigen');
define('PRODUCT_LIST_QUANTITY_DESC', 'Möchten Sie die Anzahl der vorhandenen Artikel anzeigen?');

define('PRODUCT_LIST_WEIGHT_TITLE', 'Artikelgewicht anzeigen');
define('PRODUCT_LIST_WEIGHT_DESC', 'Möchten Sie das Artikelgewicht anzeigen?');

define('PRODUCT_LIST_BUY_NOW_TITLE', 'Jetzt Kaufen anzeigen');
define('PRODUCT_LIST_BUY_NOW_DESC', 'Möchten Sie den \'Jetzt Kaufen\' Button anzeigen?');

define('PRODUCT_LIST_FILTER_TITLE', 'Kategorie/Hersteller Filter anzeigen');
define('PRODUCT_LIST_FILTER_DESC', 'Möchten Sie den Kategorie/Hersteller Filter anzeigen (0:aus,1:an)?');

define('PRODUCT_LIST_SORT_ORDER_TITLE', 'Display Product Sort Order');
define('PRODUCT_LIST_SORT_ORDER_DESC', 'Do you want to display the Product Sort Order column?');

define('PREV_NEXT_BAR_LOCATION_TITLE', 'Position der Zurück/Vor Navigation');
define('PREV_NEXT_BAR_LOCATION_DESC', 'Legt die Position der Zurück/Vor Navigation fest (1:oben, 2:unten, 3:beides)');

define('STOCK_CHECK_TITLE', 'Bestandsprüfung');
define('STOCK_CHECK_DESC', 'Soll der Shop eine Bestandsprüfung durchführen?');

define('STOCK_LIMITED_TITLE', 'Lagerbestand aktualisieren');
define('STOCK_LIMITED_DESC', 'Soll der Shop nach einem Kauf den Artikel vom Bestand abziehen?');

define('STOCK_ALLOW_CHECKOUT_TITLE', 'Kaufen erlauben');
define('STOCK_ALLOW_CHECKOUT_DESC', 'Darf ein Kunde die Kaufabwicklung auch abschließen, wenn er Artikel gekauft hat, die nicht mehr vorrätig sind?');

define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_TITLE', 'Produktmarkierung, wenn nicht auf Lager');
define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_DESC', 'Kennzeichnung für Produkte, die nicht mehr vorrätig sind');


define('STOCK_REORDER_LEVEL_TITLE', 'Unterschrittene Mengen im Lagerbestand');
define('STOCK_REORDER_LEVEL_DESC', 'Ab diesem Bestand erfolgt eine Meldung an den Administrator');

define('USE_CACHE_TITLE', 'Benutze Cache');
define('USE_CACHE_DESC', 'Soll die Seite zwischengespeichert werden?');

define('DOWNLOAD_ENABLED_TITLE', 'Ermögliche Download');
define('DOWNLOAD_ENABLED_DESC', 'Aktiviert die Shop-Funktionen, die es ermöglichen Datei herunterzuladen.');

define('DOWNLOAD_BY_REDIRECT_TITLE', 'Download by redirect');
define('DOWNLOAD_BY_REDIRECT_DESC', 'Use browser redirection for download. Disable on non-Unix systems.');

define('DOWNLOAD_MAX_DAYS_TITLE', 'Ablaufzeit (Tage)');
define('DOWNLOAD_MAX_DAYS_DESC', 'Setzt die Anzahl der Tage, nach denen der Link ungültig wird. 0 heißt immer gütig.');

define('DOWNLOAD_MAX_COUNT_TITLE', 'Maximale Anzahl der Downloads');
define('DOWNLOAD_MAX_COUNT_DESC', 'Setzt die maximal mögliche Anzahl der Downloads, 0 heißt dass kein Download erlaubt ist.');

define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_TITLE', 'Downloads Controller Update Status Value');
define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_DESC', 'What orders_status resets the Download days and Max Downloads - Default is 4');

define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_TITLE', 'Downloads Controller Download on hold message');
define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_DESC', 'Downloads Controller Download on hold message');

define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_TITLE', 'Downloads Controller Order Status Value');
define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_DESC', 'Downloads Controller Order Status Value - Default=2');

define('PDF_DATA_SHEET_TITLE', 'Ermögliche PDF-Prospekt');
define('PDF_DATA_SHEET_DESC', 'Möchten Sie die Produktinformationen als PDF-Datei zum download anbieten?');

define('HEADER_COLOR_TABLE_TITLE', 'Farbe: Prospektkopf-Tabelle');
define('HEADER_COLOR_TABLE_DESC', 'Farbe in R, G, B, Werten (Beispiel: 230,230,230)');

define('PRODUCT_NAME_COLOR_TABLE_TITLE', 'Farbe: Produkname-Tabelle');
define('PRODUCT_NAME_COLOR_TABLE_DESC', 'Farbe in R, G, B, Werten (Beispiel: 230,230,230)');

define('FOOTER_CELL_BG_COLOR_TITLE', 'Hintergundfarbe: Prospektfuss');
define('FOOTER_CELL_BG_COLOR_DESC', 'Farbe in R, G, B, Werten (Beispiel: 210,210,210)');

define('SHOW_BACKGROUND_TITLE', 'Hintergrund');
define('SHOW_BACKGROUND_DESC', 'Möchten Sie die Hintergrundfarbe angezeigen?');

define('PAGE_BG_COLOR_TITLE', 'Hintergundfarbe: Prospekt');
define('PAGE_BG_COLOR_DESC', 'Farbe in R, G, B, Werten (Beispiel: 250,250,250)');

define('SHOW_WATERMARK_TITLE', 'Wasserzeichen');
define('SHOW_WATERMARK_DESC', 'Möchten Sie Ihren Firmenname als Wasserzeichen angezeigen?');

define('PAGE_WATERMARK_COLOR_TITLE', 'Wasserzeichenfarbe');
define('PAGE_WATERMARK_COLOR_DESC', 'Farbe in R, G, B, Werten (Beispiel: 236,245,255)');

define('PDF_IMAGE_KEEP_PROPORTIONS_TITLE', 'Produktbilder');
define('PDF_IMAGE_KEEP_PROPORTIONS_DESC', 'Möchten Sie die maximale bzw. minimale Produktgrösse verwenden?');

define('MAX_IMAGE_WIDTH_TITLE', 'Breite der Produktbilder');
define('MAX_IMAGE_WIDTH_DESC', 'max. Breite in mm der Produktbilder');

define('MAX_IMAGE_HEIGHT_TITLE', 'Höhe der Produktbilder');
define('MAX_IMAGE_HEIGHT_DESC', 'max. Höhe in mm der Produktbilder');

define('PDF_TO_MM_FACTOR_TITLE', 'Faktor');
define('PDF_TO_MM_FACTOR_DESC', 'Produktbilder');

define('SHOW_PATH_TITLE', 'Kategoriename');
define('SHOW_PATH_DESC', 'Möchten Sie den Kategorienamen anzeigen?');

define('SHOW_IMAGES_TITLE', 'Produktbild');
define('SHOW_IMAGES_DESC', 'Möchten Sie das Produktbild anzeigen?');

define('SHOW_NAME_TITLE', 'Produktname');
define('SHOW_NAME_DESC', 'Möchten Sie den Produktnamen in der Beschreibung anzeigen?');

define('SHOW_MODEL_TITLE', 'Bestellnummer');
define('SHOW_MODEL_DESC', 'Möchten Sie die Bestellnummer anzeigen?');

define('SHOW_DESCRIPTION_TITLE', 'Produktbeschreibung');
define('SHOW_DESCRIPTION_DESC', 'Möchten Sie die Produktbeschreibung anzeigen?');

define('SHOW_MANUFACTURER_TITLE', 'Hersteller');
define('SHOW_MANUFACTURER_DESC', 'Möchten Sie den Hersteller anzeigen?');

define('SHOW_PRICE_TITLE', 'Produktpreis');
define('SHOW_PRICE_DESC', 'Möchten Sie den Produktpreis anzeigen?');

define('SHOW_SPECIALS_PRICE_TITLE', 'Sonderangebote');
define('SHOW_SPECIALS_PRICE_DESC', 'Möchten Sie den Angebotspreis anzeigen?');

define('SHOW_SPECIALS_PRICE_EXPIRES_TITLE', 'Datum Sonderangebote');
define('SHOW_SPECIALS_PRICE_EXPIRES_DESC', 'Möchten Sie das Gültigkeitsdatum der Angebotspreise anzeigen?');

define('SHOW_TAX_CLASS_ID_TITLE', 'Steuersatz');
define('SHOW_TAX_CLASS_ID_DESC', 'Möchten Sie den Steuersatz anzeigen?');

define('SHOW_OPTIONS_TITLE', 'Produktoptionen');
define('SHOW_OPTIONS_DESC', 'Möchten Sie die Produktoptionen anzeigen?');

define('SHOW_OPTIONS_PRICE_TITLE', 'Preis der Produktoptionen');
define('SHOW_OPTIONS_PRICE_DESC', 'Möchten Sie die Preise der Produktoptionen anzeigen?');

define('TICKET_ENTRIES_MIN_LENGTH_TITLE', 'Supporttickets');
define('TICKET_ENTRIES_MIN_LENGTH_DESC', 'Die minimale Zeichenanzahl für Supporttickets');

define('TICKET_ADMIN_NAME_TITLE', 'Ticket Admin Name');
define('TICKET_ADMIN_NAME_DESC', 'Name des Administrators');

define('TICKET_USE_STATUS_TITLE', 'Statusanzeige im Shop');
define('TICKET_USE_STATUS_DESC', 'Möchten Sie den Supportticketstatus anzeigen?');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_TITLE', 'Erlaube Änderungen vom Kunden');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_DESC', 'Erlaube dem Kunden beim Antworten den Status zu ändern.');

define('TICKET_USE_DEPARTMENT_TITLE', 'Benutze Abteilung');
define('TICKET_USE_DEPARTMENT_DESC', 'Zeige die Abteilung im Ticket an.');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_TITLE', 'Abteilung');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_DESC', 'Erlaube dem Kunden beim Antworten die Abteilung zu ändern.');

define('TICKET_USE_PRIORITY_TITLE', 'Benutze Priorität');
define('TICKET_USE_PRIORITY_DESC', 'Use Priority in Catalog');

define('TICKET_USE_ORDER_IDS_TITLE', 'Auftragsnummer');
define('TICKET_USE_ORDER_IDS_DESC', 'Wenn der Benutzer angemeldet ist, sind seine Auftragsnummern sichtbar.');

define('TICKET_USE_SUBJECT_TITLE', 'Show Subject');
define('TICKET_USE_SUBJECT_DESC', 'Show Subject');

define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_TITLE', 'Login');
define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_DESC', 'if you set this to true you can allow - notallow registered customers to view tickets without beeing logged in');

define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_TITLE', 'Shop - Login');
define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_DESC', '0 registered Customer must not be logged in to view ticket<br>1 registered Customer must  be logged in to view ticket');

define('SECURITY_CODE_LENGTH_TITLE', 'Einlösungscode');
define('SECURITY_CODE_LENGTH_DESC', 'Setzt die Länge des Einlöngscodes, je länger dieser ist, desto sicherer ist er.');

define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_TITLE', 'Neukunden Gutschein');
define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_DESC', 'Setzt die Höhe des Gutscheines, den ein Neukunde geschenkt bekommt fest. Feld leer lassen, wenn Neukunden kein \'Begrüßungsgeschenk\' bekommen sollen.');

define('NEW_SIGNUP_DISCOUNT_COUPON_TITLE', 'Coupon-ID');
define('NEW_SIGNUP_DISCOUNT_COUPON_DESC', 'Dies ist die Coupon-ID, die ein Neukunde per E-Mail erhält. Ist keine ID gesetzt, wird keine E-Mail verschickt.');

define('STORE_TEMPLATES_TITLE', 'Layout Vorlage');
define('STORE_TEMPLATES_DESC', 'Shop Templates');

define('SHOW_DATE_ADDED_AVAILABLE_TITLE', 'Produkt - Datum');
define('SHOW_DATE_ADDED_AVAILABLE_DESC', 'Möchten Sie im Shop das Datum von der Aufnahme des Produktes zeigen?');

define('SHOW_COUNTS_TITLE', 'Artikelanzahl hinter den Kategorienamen');
define('SHOW_COUNTS_DESC', 'Anzeigen, wieviele Produkte in jeder Kategorie vorhanden sind');

define('CATEGORIES_SCROLL_BOX_LEN_TITLE', 'Kategorie-Menge');
define('CATEGORIES_SCROLL_BOX_LEN_DESC', 'Wenn Sie die Kategorien als Auswahlliste anzeigen wollen, legen Sie hier die Länge fest');

define('SHOPPING_CART_IMAGE_ON_TITLE', 'Bild im Warenkorbinhalt');
define('SHOPPING_CART_IMAGE_ON_DESC', 'Möchten Sie in der Detailansicht vom Warenkorb das Porduktbild anzeigen?');

define('SHOPPING_CART_MINI_IMAGE_TITLE', 'Bildverkleinerung');
define('SHOPPING_CART_MINI_IMAGE_DESC', 'Wert für die Verkleinerung in der Detailansicht vom Warenkorb');

define('DISPLAY_CART_TITLE', 'Warenkorb anzeigen');
define('DISPLAY_CART_DESC', 'Zeigt den Warenkorb an, nachdem diesem ein Produkt hinzugefügt wurde');

define('ALLOW_GUEST_TO_TELL_A_FRIEND_TITLE', 'Empfehlen auch für Gäste');
define('ALLOW_GUEST_TO_TELL_A_FRIEND_DESC', 'Gästen erlauben, ein Produkt zu empfehlen');

define('ALLOW_CATEGORY_DESCRIPTIONS_TITLE', 'Erlaube Kategorienbeschreibung');
define('ALLOW_CATEGORY_DESCRIPTIONS_DESC', 'Erlaubt eine ausführliche Beschreibung der einzelnen Kategorien');

define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_TITLE', 'Erlaube News-Kategorienbeschreibung');
define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_DESC', 'Erlaubt eine ausführliche Beschreibung der einzelnen News-Kategorien');

define('SHOW_PRODUCTS_MODEL_TITLE', 'Navigation mit Bestellnummer');
define('SHOW_PRODUCTS_MODEL_DESC', 'Möchten Sie die auf der Produkt-Informations-Seite die Bestellnummer in der Navation anzeigen?');

define('BREADCRUMB_SEPARATOR_TITLE', 'Trenner für Menüebenenanzeige');
define('BREADCRUMB_SEPARATOR_DESC', 'Trenner für die Anzeige der Menüebene, in der sich der Kunde gerade aufhält.');

define('BLOCK_BEST_SELLERS_IMAGE_TITLE', 'Bild im Block Verkaufschlager');
define('BLOCK_BEST_SELLERS_IMAGE_DESC', 'Bild im Content-Block Verkaufschlager anzeigen?');

define('BLOCK_PRODUCTS_HISTORY_IMAGE_TITLE', 'Bild im Block besuchte Produkte');
define('BLOCK_PRODUCTS_HISTORY_IMAGE_DESC', 'Bild im Content-Block gekaufte Produkte anzeigen?');

define('BLOCK_WISHLIST_IMAGE_TITLE', 'Bild im Block Wunschliste');
define('BLOCK_WISHLIST_IMAGE_DESC', 'Bild im Content-Block Wunschliste anzeigen?');

define('BLOCK_XSELL_PRODUCTS_IMAGE_TITLE', 'Bild im Block ähnliche Produkte');
define('BLOCK_XSELL_PRODUCTS_IMAGE_DESC', 'Bild im Content-Block ähnliche Produkte anzeigen?');

define('OOS_SMALLIMAGE_WAY_OF_RESIZE_TITLE', 'Bildbearbeitung kleines Bild');
define('OOS_SMALLIMAGE_WAY_OF_RESIZE_DESC', '0: proportionale Verkleinerung; Breite oder Höhe ist die maximale Größe<br> 1: Bild wird proportional in das neue Bild kopiert. Die Hintergrundfarbe wird  berücksichtigt.<br> 2: ein Ausschnitt wird in das neue Bild kopiert');

define('OOS_SMALL_IMAGE_WIDTH_TITLE', 'Breite kleine Bilder');
define('OOS_SMALL_IMAGE_WIDTH_DESC', 'Die Breite von kleinen Bildern in Pixeln');

define('OOS_SMALL_IMAGE_HEIGHT_TITLE', 'Höhe kleine Bilder');
define('OOS_SMALL_IMAGE_HEIGHT_DESC', 'Die Höhe von kleinen Bildern in Pixeln');

define('OOS_IMAGE_BGCOLOUR_R_TITLE', 'Hintergrund kleines Bild R');
define('OOS_IMAGE_BGCOLOUR_R_DESC', 'Rotwert für kleines Produktbild');

define('OOS_IMAGE_BGCOLOUR_G_TITLE', 'Hintergrund kleines Bild G');
define('OOS_IMAGE_BGCOLOUR_G_DESC', 'Grünwert für kleines Produktbild');

define('OOS_IMAGE_BGCOLOUR_B_TITLE', 'Hintergrund kleines Bild B');
define('OOS_IMAGE_BGCOLOUR_B_DESC', 'Blauwert für kleines Produktbild');

define('OOS_BIGIMAGE_WAY_OF_RESIZE_TITLE', 'Bildbearbeitung grosses Bild');
define('OOS_BIGIMAGE_WAY_OF_RESIZE_DESC', '0: proportionale Verkleinerung; Breite oder Höhe ist die maximale Größe<br> 1: Bild wird proportional in das neue Bild kopiert. Die Hintergrundfarbe wird  berücksichtigt.<br> 2: ein Ausschnitt wird in das neue Bild kopiert');

define('OOS_BIGIMAGE_WIDTH_TITLE', 'Breite grosses Bild');
define('OOS_BIGIMAGE_WIDTH_DESC', 'Breite vom grossen Bild in Pixel');

define('OOS_BIGIMAGE_HEIGHT_TITLE', 'Höhe grosses Bild');
define('OOS_BIGIMAGE_HEIGHT_DESC', 'Höhe vom grossen Bild in Pixel');

define('OOS_WATERMARK_TITLE', 'Wasserzeichen');
define('OOS_WATERMARK_DESC', 'Möchten Sie im grossen Bild ein Wasserzeichen einfügen?');

define('OOS_WATERMARK_QUALITY_TITLE', 'Qualität vom Wasserzeichen');
define('OOS_WATERMARK_QUALITY_DESC', 'Hier legen Sie die Qualität vom Wasserzeichen fest');


define('PSM_TITLE', 'Preissuchmaschine');
define('PSM_DESC', 'Möchten Sie Die Schnittstelle zur Preissuchmaschine verwenden? Hierfür ist eine Anmeldung bei <a href="http://www.preissuchmaschine.de/psm_frontend/main.asp?content=mitmachenreissuchmaschine" target="_blank">http://www.preissuchmaschine.de</a> n');

define('OOS_PSM_DIR_TITLE', 'Verzeichnis Preissuchmaschine');
define('OOS_PSM_DIR_DESC', 'Die Datei für die Preissuchmaschine soll in diesem Shop-Verzeichnis gespeichert werden.');

define('OOS_PSM_FILE_TITLE', 'Dateiname');
define('OOS_PSM_FILE_DESC', 'Die Datei für die Preissuchmaschine');

define('OOS_META_TITLE_TITLE', 'Shop Titel');
define('OOS_META_TITLE_DESC', 'Der Titel');

define('OOS_META_DESCRIPTION_TITLE', 'Beschreibung');
define('OOS_META_DESCRIPTION_DESC', 'Die Beschreibung Ihres Shop(max. 250 Zeichen)');

define('OOS_META_AUTHOR_TITLE', 'Autor');
define('OOS_META_AUTHOR_DESC', 'Der Autor des Shop');

define('OOS_META_COPYRIGHT_TITLE', 'Copyright');
define('OOS_META_COPYRIGHT_DESC', 'Der Entwickler des Shop');

define('MULTIPLE_CATEGORIES_USE_TITLE', 'Multi-Kategorien nutzen');
define('MULTIPLE_CATEGORIES_USE_DESC', 'Auf true setzen, um das Hinzufügen eines Produkts zu mehreren Kategorien mit einem Klick zu ermöglichen.');

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

define('RCS_BASE_DAYS_TITLE', 'Look back days');
define('RCS_BASE_DAYS_DESC', 'Number of days to look back from today for abandoned cards.');

define('RCS_REPORT_DAYS_TITLE', 'Sales Results Report days');
define('RCS_REPORT_DAYS_DESC', 'Number of days the sales results report takes into account. The more days the longer the SQL queries!.');

define('RCS_EMAIL_TTL_TITLE', 'E-Mail time to live');
define('RCS_EMAIL_TTL_DESC', 'Number of days to give for emails before they no longer show as being sent');

define('RCS_EMAIL_FRIENDLY_TITLE', 'Friendly E-Mails');
define('RCS_EMAIL_FRIENDLY_DESC', 'If <b>true</b> then the customer\'s name will be used in the greeting. If <b>false</b> then a generic greeting will be used.');

define('RCS_SHOW_ATTRIBUTES_TITLE', 'Show Attributes');
define('RCS_SHOW_ATTRIBUTES_DESC', 'Controls display of item attributes.<br><br>Some sites have attributes for their items.<br><br>Set this to <b>true</b> if yours does and you want to show them, otherwise set to <b>false</b>.');

define('RCS_CHECK_SESSIONS_TITLE', 'Ignore Customers with Sessions');
define('RCS_CHECK_SESSIONS_DESC', 'If you want the tool to ignore customers with an active session (ie, probably still shopping) set this to <b>true</b>.<br><br>Setting this to <b>false</b> will operate in the default manner of ignoring session data &amp; using less resources');

define('RCS_CURCUST_COLOR_TITLE', 'Current Customer Hilight');
define('RCS_CURCUST_COLOR_DESC', 'Color for the word/phrase used to notate a current customer<br><br>A current customer is someone who has purchased items from your store in the past.');

define('RCS_UNCONTACTED_COLOR_TITLE', 'Uncontacted hilight Hilight');
define('RCS_UNCONTACTED_COLOR_DESC', 'Row highlight color for uncontacted customers.<br><br>An uncontacted customer is one that you have <i>not</i> used this tool to send an email to before.');

define('RCS_CONTACTED_COLOR_TITLE', 'Contacted hilight Hilight');
define('RCS_CONTACTED_COLOR_DESC', 'Row highlight color for contacted customers.<br><br>An contacted customer is one that you <i>have</i> used this tool to send an email to before.');

define('RCS_MATCHED_ORDER_COLOR_TITLE', 'Matching Order Hilight');
define('RCS_MATCHED_ORDER_COLOR_DESC', 'Row highlight color for entrees that may have a matching order.<br><br>An entry will be marked with this color if an order contains one or more of an item in the abandoned cart <b>and</b> matches either the cart\'s customer email address or database ID.');

define('RCS_SKIP_MATCHED_CARTS_TITLE', 'Skip Carts w/Matched Orders');
define('RCS_SKIP_MATCHED_CARTS_DESC', 'To ignore carts with an a matching order set this to <b>true</b>.<br><br>Setting this to <b>false</b> will cause entries with a matching order to show, along with the matching order\'s status.<br><br>See documentation for details.');

define('RCS_PENDING_SALE_STATUS_TITLE', 'Lowest Pending sales status');
define('RCS_PENDING_SALE_STATUS_DESC', 'The highest value that an order can have and still be considered pending. Any value higher than this will be considered by RCS as sale which completed.<br><br>See documentation for details.');

define('RCS_REPORT_EVEN_STYLE_TITLE', 'Report Even Row Style');
define('RCS_REPORT_EVEN_STYLE_DESC', 'Style for even rows in results report. Typical options are <i>dataTableRow</i> and <i>attributes-even</i>.');

define('RCS_REPORT_ODD_STYLE_TITLE', 'Report Odd Row Style');
define('RCS_REPORT_ODD_STYLE_DESC', 'Style for odd rows in results report. Typical options are NULL (ie, no entry) and <i>attributes-odd</i>.');

define('RCS_EMAIL_COPIES_TO_TITLE', 'E-Mail Copies to');
define('RCS_EMAIL_COPIES_TO_DESC', 'If you want copies of emails that are sent to customers by this contribution, enter the email address here. If empty no copies are sent');

define('RCS_AUTO_CHECK_TITLE', 'Autocheck "safe" carts to email');
define('RCS_AUTO_CHECK_DESC', 'To check entries which are most likely safe to email (ie, not existing customers, not previously emailed, etc.) set this to <b>true</b>.<br><br>Setting this to <b>false</b> will leave all entries unchecked (you will have to check each entry you want to send an email for).');

define('RCS_CARTS_MATCH_ALL_DATES_TITLE', 'Match orders from any date');
define('RCS_CARTS_MATCH_ALL_DATES_DESC', 'If <b>true</b> then any order found with a matching item will be considered a matched order.<br><br>If <b>false</b> only orders placed after the abandoned cart are considered.');
