<?php
/**
   ----------------------------------------------------------------------
   $Id: configuration.php,v 1.6 2009/01/23 06:23:43 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configuration.php,v 1.8 2002/01/04 03:51:40 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('TABLE_HEADING_CONFIGURATION_TITLE', 'Name');
define('TABLE_HEADING_CONFIGURATION_VALUE', 'Wert');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_INFO_EDIT_INTRO', 'Bitte führen Sie alle notwendigen Änderungen durch');
define('TEXT_INFO_DATE_ADDED', 'hinzugefügt am:');
define('TEXT_INFO_LAST_MODIFIED', 'letzte Änderung:');
define('TEXT_IMAGE_NONEXISTENT', 'BILD EXISTIERT NICHT');

define('STORE_NAME_TITLE', 'Shop Name');
define('STORE_NAME_DESC', 'Der Name meines Shops');

define('STORE_LOGO_TITLE', 'Shop Logo');
define('STORE_LOGO_DESC', 'Das Logo meines Shops');

define('STORE_OWNER_TITLE', 'Shop Inhaber');
define('STORE_OWNER_DESC', 'Der Name des Shop-Betreibers');

define('STORE_OWNER_EMAIL_ADDRESS_TITLE', 'E-Mail Adresse');
define('STORE_OWNER_EMAIL_ADDRESS_DESC', 'Die E-Mail Adresse des Shop-Betreibers');

define('STORE_OWNER_VAT_ID_TITLE', 'Umsatzsteuer ID');
define('STORE_OWNER_VAT_ID_DESC', 'Die Umsatzsteuer ID Ihres Unternehmens');

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

define('BASE_PRICE_TITLE', 'Grundpreis verwenden');
define('BASE_PRICE_DESC', 'Möchten Sie Produkte mit einem Grundpreis verwenden?');

define('TAKE_BACK_OBLIGATION_TITLE', 'Rücknahmepflicht für Elektroaltgeräte verwenden');
define('TAKE_BACK_OBLIGATION_DESC', 'Sind Sie verpflichtet, bei bestimmten Geräten, Altgeräte bei Ablieferung des Neugerätes gleich mitzunehmen?');

define('OFFER_B_WARE_TITLE', 'B-Ware / Gebrauchtware');
define('OFFER_B_WARE_DESC', 'Bieten Sie in Ihrem Onlineshop B-Ware / Gebrauchtware an?');


define('MINIMUM_ORDER_VALUE_TITLE', 'Mindestbestellwert verwenden');
define('MINIMUM_ORDER_VALUE_DESC', 'Legen Sie einen Mindestbestellwert fest. Eine Zahl ohne Währung.');


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

define('ENTRY_STREET_ADDRESS_MIN_LENGTH_TITLE', 'Straße');
define('ENTRY_STREET_ADDRESS_MIN_LENGTH_DESC', 'Mindestlänge des Straßennamens');

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

define('IMAGE_ZOOM_TITLE', 'Bild auf der Produktinfo Seite vergrößern.');
define('IMAGE_ZOOM_DESC', 'Bild vergrößern bei:');

define('ZOOM_BUTTON_TITLE', 'Position der Schaltfläche \'Zoom In\'.');
define('ZOOM_BUTTON_DESC', 'Die Button Position auf dem Bild der Produktinfo Seite');

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

define('ACCOUNT_TELEPHONE_TITLE', 'Telefonnummer');
define('ACCOUNT_TELEPHONE_DESC', 'Die Anzeige und Eingabe der Telefonnummer wird ermöglicht. Die Eingabe ist nicht zwingend notwendig.');

define('NEWSLETTER_TITLE', 'Newsletter');
define('NEWSLETTER_DESC', 'Möchten Sie einen Newsletter anbieten?');

define('PRODUCTS_CHARTS_TITLE', 'Graph über Preisentwicklung');
define('PRODUCTS_CHARTS_DESC', 'Möchten Sie die Preisentwicklung als Grafik auf der Produktinformationsseite anzeigen?');

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

define('SHIPPING_PRICE_WITH_TAX_TITLE', 'Erfasste Versandkosten enthalten die Umsatzsteuer?');
define('SHIPPINGPRICE_WITH_TAX_DESC', 'Versandkosten incl. Steuer erfassen (true) oder die Steuer vom Shop hinzurechnen (false)');

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

define('BLOG_URL_TITLE', 'Blog');
define('BLOG_URL_DESC', 'URL der Blog Seite');

define('PHPBB_URL_TITLE', 'phpBB 3');
define('PHPBB_URL_DESC', 'URL der phpBB 3 Seite');

define('WARN_INSTALL_EXISTENCE_TITLE', 'Warnung: Das Installationsverzeichnis ist noch vorhanden');
define('WARN_INSTALL_EXISTENCE_DESC', 'Der Shop warnt vor dem Installationsverzeichnis, wenn auf \'true\' gesetzt wird und das Verzeichnis vorhanden ist.');

define('WARN_CONFIG_WRITEABLE_TITLE', 'Warnung: MyOOS [Shopsystem] kann in die Konfigurationsdatei schreiben');
define('WARN_CONFIG_WRITEABLE_DESC', 'Der Shop warnt vor den Schreibrechten, wenn auf \'true\' gesetzt wird und die Konfigurationsdatei beschreibbar ist.');

define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE_TITLE', 'Warnung: Das Verzeichnis für den Artikel Download existiert nicht');
define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE_DESC', 'Der Shop warnt vor Verzeichnises, wenn auf \'true\' gesetzt wird und das Verzeichnis fehlt.');


define('DOWNLOAD_ENABLED_TITLE', 'Ermögliche Download');
define('DOWNLOAD_ENABLED_DESC', 'Aktiviert die Shop-Funktionen, die es ermöglichen Datei herunterzuladen.');

define('DOWNLOAD_BY_REDIRECT_TITLE', 'Download durch Umleitung (redirect)');
define('DOWNLOAD_BY_REDIRECT_DESC', 'Browserumleitung für den Download verwenden. Bitte auch Nicht-Unix-Systemen deaktivieren.');

define('DOWNLOAD_MAX_DAYS_TITLE', 'Ablaufzeit (Tage)');
define('DOWNLOAD_MAX_DAYS_DESC', 'Setzt die Anzahl der Tage, nach denen der Link ungültig wird. 0 hei&szlig;t immer gütig.');

define('DOWNLOAD_MAX_COUNT_TITLE', 'Maximale Anzahl der Downloads');
define('DOWNLOAD_MAX_COUNT_DESC', 'Setzt die maximal mögliche Anzahl der Downloads, 0 hei&szlig;t dass kein Download erlaubt ist.');

define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_TITLE', 'Downloads Controller Auftragsstatus Wert');
define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_DESC', 'Downloads Controller Auftragsstatus Wert - Standard=2');

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

define('OOS_META_AUTHOR_TITLE', 'Autor');
define('OOS_META_AUTHOR_DESC', 'Der Autor des Shop');

define('OOS_META_COPYRIGHT_TITLE', 'Copyright');
define('OOS_META_COPYRIGHT_DESC', 'Der Entwickler des Shop');

define('SITE_ICONS_TITLE', 'Website Icons');
define('SITE_ICONS_DESC', 'Website-Icons erscheinen in Browser-Tabs und Lesezeichenleisten. Laden Sie hier eins hoch!');

define('OPEN_GRAPH_THUMBNAIL_TITLE', 'OpenGraph Thumbnail');
define('OPEN_GRAPH_THUMBNAIL_DESC', 'Wenn ein Bild nicht eingestellt ist, wird dieses Bild als Miniaturbild verwendet, wenn Ihr Online Shop auf Facebook geteilt wird. Empfohlene Bildgröße 1200 x 630 Pixel.');

define('SITE_NAME_TITLE', 'Shop Name');
define('SITE_NAME_DESC', 'Der Name meines Shops');

define('TWITTER_CARD_TITLE', 'Twitter Kartentyp');
define('TWITTER_CARD_DESC', 'Kartentyp, der beim Erstellen eines neuen Beitrags ausgewählt wurde. Dies gilt auch für Beiträge ohne gewählten Kartentyp.');

define('TWITTER_CREATOR_TITLE', 'Twitter Benutzername');
define('TWITTER_CREATOR_DESC', 'Geben Sie den Twitter-Benutzernamen des Autors ein, um twitter:creator-Tag zu den Artikeln  hinzuzufügen.');



define('FACEBOOK_URL_TITLE', 'Facebook');
define('FACEBOOK_URL_DESC', 'URL der Facebook Seite');

define('SKYPE_URL_TITLE', 'Skype');
define('SKYPE_URL_DESC', 'URL der Skype Seite');

define('LINKEDIN_URL_TITLE', 'Linkedin');
define('LINKEDIN_URL_DESC', 'URL der Linkedin Seite');

define('PINTEREST_URL_TITLE', 'Pinterest');
define('PINTEREST_URL_DESC', 'URL der Pinterest Seite');

define('TWITTER_URL_TITLE', 'Twitter');
define('TWITTER_URL_DESC', 'URL der Twitter Seite');

define('DRIBBBLE_URL_TITLE', 'Dribbble');
define('DRIBBBLE_URL_DESC', 'URL der Dribbble Seite');


define('ENABLE_SPIDER_FRIENDLY_LINKS_TITLE', 'Spider-frundliche Links');
define('ENABLE_SPIDER_FRIENDLY_LINKS_DESC', 'Ermögliche Spider-freundliche Links (empfohlen). ACHTUNG: Es sind ggf. Änderungen in der Konfiguration des Webservers notwendig!');
