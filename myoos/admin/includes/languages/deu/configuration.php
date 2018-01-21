<?php
/* ----------------------------------------------------------------------
   $Id: configuration.php,v 1.6 2009/01/23 06:23:43 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
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

define('USE_DEFAULT_LANGUAGE_CURRENCY_TITLE', 'Währung automatisch wechseln');
define('USE_DEFAULT_LANGUAGE_CURRENCY_DESC', 'Wechselt automatisch die Währung anhand der eingestellten Sprache');

define('ADVANCED_SEARCH_DEFAULT_OPERATOR_TITLE', 'Standardoperator für Suchfunktionen');
define('ADVANCED_SEARCH_DEFAULT_OPERATOR_DESC', 'Die Standardverknüpfung, mit der mehrere Suchbegriffe verknüpft werden');

define('STORE_NAME_ADDRESS_TITLE', 'Adressinformationen des Shops');
define('STORE_NAME_ADDRESS_DESC', 'Die Kontaktinformationen des Shops, welche sowohl in Dokumenten als auch Online ausgegeben werden');

define('TAX_DECIMAL_PLACES_TITLE', 'Dezimalstellen der Steuer');
define('TAX_DECIMAL_PLACES_DESC', 'Anzahl der Dezimalstellen der Steuer');

define('DISPLAY_PRICE_WITH_TAX_TITLE', 'Preise inkl. Steuer');
define('DISPLAY_PRICE_WITH_TAX_DESC', 'Preise incl. Steuer anzeigen (true) oder die Steuer dem Gesamtbetrag hinzurechnen (false)');

define('PRODUCTS_OPTIONS_SORT_BY_PRICE_TITLE', 'Sortierung Produktoptionen');
define('PRODUCTS_OPTIONS_SORT_BY_PRICE_DESC', 'Möchten Sie die Produktopionen nach Preisen sortieren?');

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

define('MAX_DISPLAY_PRODUCTS_NEW_TITLE', 'Anzahl neue Produkte');
define('MAX_DISPLAY_PRODUCTS_NEW_DESC', 'Anzahl der neuen Produkte, die in der Übersicht der neuen Produkte maximal angezeigt werden');

define('MAX_DISPLAY_BESTSELLERS_TITLE', 'Verkaufsschlager');
define('MAX_DISPLAY_BESTSELLERS_DESC', 'Maximale Anzahl der anzuzeigenden Verkaufsschlager');

define('MAX_DISPLAY_ALSO_PURCHASED_TITLE', 'Kunden kauften auch');
define('MAX_DISPLAY_ALSO_PURCHASED_DESC', 'Maximale Anzahl von Produkten die im \'Kunden kauften auch\'-Block angezeigt werden');

define('MAX_DISPLAY_ORDER_HISTORY_TITLE', 'Anzahl Bestellungen im Bestellübersicht-Block');
define('MAX_DISPLAY_ORDER_HISTORY_DESC', 'Maximale Anzahl von Bestellungen im Bestellübersichts-Block');

define('MAX_DISPLAY_XSELL_PRODUCTS_TITLE', 'Produkt-Empfehlungen');
define('MAX_DISPLAY_XSELL_PRODUCTS_DESC', 'Maximale Anzahl von Produkten, die im \'Produkt-Empfehlungen\'-Block angezeigt werden');

define('MAX_DISPLAY_WISHLIST_PRODUCTS_TITLE', 'Wunschzettel');
define('MAX_DISPLAY_WISHLIST_PRODUCTS_DESC', 'Maximale Anzahl von Produkten auf der Wunschzettel-Seite');


define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_TITLE', 'Anzahl der kürzlich besuchten Produkte');
define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_DESC', 'Maximale Anzahl von Produkten, die im \'Products History\'-Block angezeigt werden. Dies sind die Produkte, die sich der Shopbesucher kürzlich angesehen hat.');

define('SMALL_IMAGE_WIDTH_TITLE', 'Breite kleine Bilder');
define('SMALL_IMAGE_WIDTH_DESC', 'Die Breite von kleinen Bildern in Pixeln');

define('SMALL_IMAGE_HEIGHT_TITLE', 'Höhe kleine Bilder');
define('SMALL_IMAGE_HEIGHT_DESC', 'Die Höhe von kleinen Bildern in Pixeln');

define('SUBCATEGORY_IMAGE_WIDTH_TITLE', 'Breite Unterkategorie-Bilder');
define('SUBCATEGORY_IMAGE_WIDTH_DESC', 'Die Breite von Unterkategorie-Bildern in Pixeln');

define('SUBCATEGORY_IMAGE_HEIGHT_TITLE', 'Höhe Unterkategorie-Bilder');
define('SUBCATEGORY_IMAGE_HEIGHT_DESC', 'Die Höhe von Unterkategorie-Bildern in Pixeln');

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

define('ACCOUNT_COMPANY_TITLE', 'Firmenname');
define('ACCOUNT_COMPANY_DESC', 'Ein Firmenname für gewerbliche Kunden kann eingegeben werden. Die Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_OWNER_TITLE', 'Inhaber');
define('ACCOUNT_OWNER_DESC', 'Der Inhaber der Firmen bei gewerblichen Kunden kann eingegeben werden. Die Eingabe ist nicht zwingend notwendig.');

define('ACCOUNT_VAT_ID_TITLE', 'Umsatzsteuer ID');
define('ACCOUNT_VAT_ID_DESC', 'Die Umsatzsteuer ID bei gewerblichen Kunden kann eingegeben werden.');

define('ACCOUNT_STATE_TITLE', 'Bundesland');
define('ACCOUNT_STATE_DESC', 'Die Anzeige und Eingabe des Bundeslandes wird ermöglicht. Die Eingabe ist bei Anzeige zwingend notwendig.');

define('NEWSLETTER_TITLE', 'Newsletter');
define('NEWSLETTER_DESC', 'Möchten Sie einen Newsletter anbieten?');

define('PRODUCTS_NOTIFICATIONS_TITLE', 'Produkt-Meldungen');
define('PRODUCTS_NOTIFICATIONS_DESC', 'Möchten Sie Produkt-Meldungen per Mail anbieten?');

define('STORE_ORIGIN_COUNTRY_TITLE', 'Ländercode');
define('STORE_ORIGIN_COUNTRY_DESC', 'Eingabe des &quot;ISO 3166&quot;-Ländercodes des Shops, der im Versandbereich benutzt werden soll. Zum Finden Ihres Ländercodes besuchen Sie die <a href="http://www.din.de/gremien/nas/nabd/iso3166ma/codlstp1/index.html" target="_blank">ISO 3166');

define('STORE_ORIGIN_ZIP_TITLE', 'Postleitzahl');
define('STORE_ORIGIN_ZIP_DESC', 'Eingabe der Postleitzahl des Shops, die im Versandbereich benutzt werden soll.');

define('SHIPPING_MAX_WEIGHT_TITLE', 'Maximales Gewicht einer Bestellung');
define('SHIPPING_MAX_WEIGHT_DESC', 'Versandunternehmen haben ein Höchstgewicht für einzelne Pakete. Dies hier ist ein Wert, der für alle Unternehmen gleicherma&szlig;en gilt.');

define('SHIPPING_BOX_WEIGHT_TITLE', 'Gewicht der Verpackung.');
define('SHIPPING_BOX_WEIGHT_DESC', 'Wie hoch ist im Schnitt das Gewicht der Verpackung eines kleinen bis mittleren Paketes?');

define('SHIPPING_BOX_PADDING_TITLE', 'Prozentuale Mehrkosten für schwerere Pakete.');
define('SHIPPING_BOX_PADDING_DESC', 'Prozentuale Mehrkosten für schwerere Pakete. Für 10% einfach 10 eingeben.');

define('STOCK_CHECK_TITLE', 'Bestandsprüfung');
define('STOCK_CHECK_DESC', 'Soll der Shop eine Bestandsprüfung durchführen?');

define('STOCK_LIMITED_TITLE', 'Lagerbestand aktualisieren');
define('STOCK_LIMITED_DESC', 'Soll der Shop nach einem Kauf den Artikel vom Bestand abziehen?');

define('STOCK_ALLOW_CHECKOUT_TITLE', 'Kaufen erlauben');
define('STOCK_ALLOW_CHECKOUT_DESC', 'Darf ein Kunde die Kaufabwicklung auch abschlie&szlig;en, wenn er Artikel gekauft hat, die nicht mehr vorrätig sind?');

define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_TITLE', 'Produktmarkierung, wenn nicht auf Lager');
define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_DESC', 'Kennzeichnung für Produkte, die nicht mehr vorrätig sind');


define('STOCK_REORDER_LEVEL_TITLE', 'Unterschrittene Mengen im Lagerbestand');
define('STOCK_REORDER_LEVEL_DESC', 'Ab diesem Bestand erfolgt eine Meldung an den Administrator');

define('STORE_PAGE_PARSE_TIME_TITLE', 'Speichere die Erstellungszeit einer Seite');
define('STORE_PAGE_PARSE_TIME_DESC', 'Die Zeit, die der Server zur Erstellung der Seite benötigt, wird gespeichert.');

define('STORE_PAGE_PARSE_TIME_LOG_TITLE', 'Ziel der Protokolldatei');
define('STORE_PAGE_PARSE_TIME_LOG_DESC', 'Verzeichnis und Dateiname der Datei, in der die Seitenerstellungszeiten gespeichert werden.');

define('STORE_PARSE_DATE_TIME_FORMAT_TITLE', 'Datumsformat der Protokolldatei');
define('STORE_PARSE_DATE_TIME_FORMAT_DESC', 'Format von Datum und Uhrzeit.');

define('DISPLAY_PAGE_PARSE_TIME_TITLE', 'Anzeige der Erstellungszeit einer Seite');
define('DISPLAY_PAGE_PARSE_TIME_DESC', 'Die Erstellungszeit einer Seite ist für den Besucher des Shops sichtbar. (\'Speichere die Erstellungszeit einer Seite\' mu&szlig; aktiviert sein.)');

define('USE_CACHE_TITLE', 'Benutze Cache');
define('USE_CACHE_DESC', 'Soll die Seite zwischengespeichert werden?');

define('DOWNLOAD_ENABLED_TITLE', 'Ermögliche Download');
define('DOWNLOAD_ENABLED_DESC', 'Aktiviert die Shop-Funktionen, die es ermöglichen Datei herunterzuladen.');

define('DOWNLOAD_BY_REDIRECT_TITLE', 'Download by redirect');
define('DOWNLOAD_BY_REDIRECT_DESC', 'Use browser redirection for download. Disable on non-Unix systems.');

define('DOWNLOAD_MAX_DAYS_TITLE', 'Ablaufzeit (Tage)');
define('DOWNLOAD_MAX_DAYS_DESC', 'Setzt die Anzahl der Tage, nach denen der Link ungültig wird. 0 hei&szlig;t immer gütig.');

define('DOWNLOAD_MAX_COUNT_TITLE', 'Maximale Anzahl der Downloads');
define('DOWNLOAD_MAX_COUNT_DESC', 'Setzt die maximal mögliche Anzahl der Downloads, 0 hei&szlig;t dass kein Download erlaubt ist.');

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

define('SECURITY_CODE_LENGTH_TITLE', 'Einlösungscode');
define('SECURITY_CODE_LENGTH_DESC', 'Setzt die Länge des Einlöngscodes, je länger dieser ist, desto sicherer ist er.');

define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_TITLE', 'Neukunden Gutschein');
define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_DESC', 'Setzt die Höhe des Gutscheines, den ein Neukunde geschenkt bekommt fest. Feld leer lassen, wenn Neukunden kein \'Begrü&szlig;ungsgeschenk\' bekommen sollen.');

define('NEW_SIGNUP_DISCOUNT_COUPON_TITLE', 'Coupon-ID');
define('NEW_SIGNUP_DISCOUNT_COUPON_DESC', 'Dies ist die Coupon-ID, die ein Neukunde per E-Mail erhält. Ist keine ID gesetzt, wird keine E-Mail verschickt.');

define('STORE_TEMPLATES_TITLE', 'Layout Vorlage');
define('STORE_TEMPLATES_DESC', 'Shop Templates');

define('SHOW_DATE_ADDED_AVAILABLE_TITLE', 'Produkt - Datum');
define('SHOW_DATE_ADDED_AVAILABLE_DESC', 'Möchten Sie im Shop das Datum von der Aufnahme des Produktes zeigen?');

define('SHOW_COUNTS_TITLE', 'Artikelanzahl hinter den Kategorienamen');
define('SHOW_COUNTS_DESC', 'Anzeigen, wieviele Produkte in jeder Kategorie vorhanden sind');

define('DISPLAY_CART_TITLE', 'Warenkorb anzeigen');
define('DISPLAY_CART_DESC', 'Zeigt den Warenkorb an, nachdem diesem ein Produkt hinzugefügt wurde');

define('OOS_SMALL_IMAGE_WIDTH_TITLE', 'Breite kleine Bilder');
define('OOS_SMALL_IMAGE_WIDTH_DESC', 'Die Breite von kleinen Bildern in Pixeln');

define('OOS_SMALL_IMAGE_HEIGHT_TITLE', 'Höhe kleine Bilder');
define('OOS_SMALL_IMAGE_HEIGHT_DESC', 'Die Höhe von kleinen Bildern in Pixeln');

define('OOS_BIGIMAGE_WIDTH_TITLE', 'Breite grosses Bild');
define('OOS_BIGIMAGE_WIDTH_DESC', 'Breite vom grossen Bild in Pixel');

define('OOS_BIGIMAGE_HEIGHT_TITLE', 'Höhe grosses Bild');
define('OOS_BIGIMAGE_HEIGHT_DESC', 'Höhe vom grossen Bild in Pixel');

define('OOS_META_TITLE_TITLE', 'Shop Titel');
define('OOS_META_TITLE_DESC', 'Der Titel');

define('OOS_META_DESCRIPTION_TITLE', 'Beschreibung');
define('OOS_META_DESCRIPTION_DESC', 'Die Beschreibung Ihres Shop(max. 250 Zeichen)');

define('OOS_META_KEYWORDS_TITLE', 'Suchworte');
define('OOS_META_KEYWORDS_DESC', 'Geben Sie hier Ihre Schlüsselwörter(durch Komma getrennt) ein(max. 250 Zeichen)');

define('OOS_META_PAGE_TOPIC_TITLE', 'Thema');
define('OOS_META_PAGE_TOPIC_DESC', 'Das Thema Ihres Shop');

define('OOS_META_AUDIENCE_TITLE', 'Zielgruppe');
define('OOS_META_AUDIENCE_DESC', 'Ihre Zielgruppe');

define('OOS_META_AUTHOR_TITLE', 'Autor');
define('OOS_META_AUTHOR_DESC', 'Der Autor des Shop');

define('OOS_META_COPYRIGHT_TITLE', 'Copyright');
define('OOS_META_COPYRIGHT_DESC', 'Der Entwickler des Shop');

define('OOS_META_PAGE_TYPE_TITLE', 'Seitentyp');
define('OOS_META_PAGE_TYPE_DESC', 'Typ der Internetpräsenz');

define('OOS_META_PUBLISHER_TITLE', 'Herausgeber');
define('OOS_META_PUBLISHER_DESC', 'Der Herausgeber');

define('OOS_META_ROBOTS_TITLE', 'Indizierung');
define('OOS_META_ROBOTS_DESC', 'Typ der Indizierung');

define('OOS_META_EXPIRES_TITLE', 'Gültigkeitsdauer');
define('OOS_META_EXPIRES_DESC', 'Angebot verfällt am:( 0 für häufig geänderte Sites)');

define('OOS_META_PAGE_PRAGMA_TITLE', 'Proxy Caching');
define('OOS_META_PAGE_PRAGMA_DESC', 'Ihr Shop soll von Proxys zwischengespeichert werden?');

define('OOS_META_REVISIT_AFTER_TITLE', 'Wiederbesuchen nach');
define('OOS_META_REVISIT_AFTER_DESC', 'Wann soll die Suchmaschine Ihre Seite wiederbesuchen?');

define('OOS_META_PRODUKT_TITLE', 'Pflege im Artikel');
define('OOS_META_PRODUKT_DESC', 'Möchten Sie Keywords und Description für jeden Artikel pflegen?');

define('OOS_META_INDEX_PAGE_TITLE', 'Index Seite erzeugen');
define('OOS_META_INDEX_PAGE_DESC', 'Möchten Sie eine Index-Seite mit allen Artikeln für Suchmaschinen erzeugen?');

define('OOS_META_INDEX_PATH_TITLE', 'Pfad für IndexSeite');
define('OOS_META_INDEX_PATH_DESC', 'Die Datei für die Suchmaschinen soll in diesem Shop-Verzeichnis gespeichert werden.');

define('ENABLE_SPIDER_FRIENDLY_LINKS_TITLE', 'Spider-frundliche Links');
define('ENABLE_SPIDER_FRIENDLY_LINKS_DESC', 'Ermögliche Spider-freundliche Links (empfohlen). ACHTUNG: Es sind ggf. Änderungen in der Konfiguration des Webservers notwendig!');
