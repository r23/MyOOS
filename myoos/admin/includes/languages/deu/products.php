<?php
/**
   ----------------------------------------------------------------------
   $Id: products.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.22 2002/08/17 09:43:33 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Neuer Artikel');

define('TEXT_NEW_PRODUCT', 'Neuer Artikel in &quot;%s&quot;');
define('TEXT_EDIT_PRODUCT', 'Artikel in &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Artikel');
define('TEXT_PRODUCTS_DATA', 'Artikeldaten');
define('TEXT_PRODUCTS_INFORMATION_OBLIGATIONS', 'Informationspflichten');
define('TEXT_OLD_ELECTRICAL_EQUIPMENT_OBLIGATIONS', 'Rücknahmepflicht für Elektroaltgeräte');
define('TEXT_OLD_ELECTRICAL_EQUIPMENT_OBLIGATIONS_NOTE', 'Hinweistext über Rücknahmepflicht');
define('TEXT_OFFER_B_WARE_INFO', 'Gebrauchtware (B-Ware)');
define('TEXT_OFFER_B_WARE_INFO_NOTE', 'Hinweistext über Gebrauchtware (B-Ware)');

define('TEXT_PRODUCTS_ATTRIBUTES', 'Artikelmerkmale');
define('TEXT_HEADER_ATTRIBUTES', 'Artikelmerkmale');


define('TEXT_HEADER_INFORMATION_OBLIGATIONS', 'Informationspflichten');
define('TEXT_PRODUCTS_PRICE_INFO', 'Preis:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Steuerklasse:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'durchschnittl. Bewertung:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Anzahl:');
define('TEXT_DATE_ADDED', 'hinzugefügt am:');
define('TEXT_DATE_AVAILABLE', 'Erscheinungsdatum:');
define('TEXT_LAST_MODIFIED', 'letzte Änderung:');
define('TEXT_IMAGE_NONEXISTENT', 'BILD EXISTIERT NICHT');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Für weitere Informationen, besuchen Sie bitte die <a href="http://%s" target="blank"><u>Homepage</u></a> des Herstellers.');
define('TEXT_PRODUCT_DATE_ADDED', 'Diesen Artikel haben wir am %s in unseren Katalog aufgenommen.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Dieser Artikel ist erhältlich ab %s.');

define('TEXT_TAX_INFO', 'Netto:');

define('TEXT_PRODUCTS_BASE_PRICE', 'Grundpreis ');
define('TEXT_PRODUCTS_BASE_UNIT', 'Mengeneinheit:');
define('TEXT_PRODUCTS_BASE_PRICE_FACTOR', 'Faktor zum Berechnen des Grundpreises:');
define('TEXT_PRODUCTS_BASE_QUANTITY', 'Basismenge:');
define('TEXT_PRODUCTS_PRODUCT_QUANTITY', 'Artikelmenge:');
define('TEXT_PRODUCTS_PRODUCT_MINIMUM_ORDER', 'Mindestbestellmenge:');
define('TEXT_PRODUCTS_PRODUCT_PACKAGING_UNIT', 'Verpackungseinheit:');
define('TEXT_PRODUCTS_PRODUCT_MAXIMUM_ORDER', 'maximale Bestellmenge:');

define('TEXT_PRODUCTS_UNIT', 'Produkteinheit:');

define('TEXT_SOCIAL', 'Social');
define('TEXT_HEADER_FACEBOOK', 'Facebook');
define('TEXT_HEADER_TWITTER', 'Twitter');
define('TEXT_TITLE', 'Titel:');
define('TEXT_DESCRIPTION', 'Beschreibung:');
define('TEXT_DATA_FROM_FACEBOOK', 'Daten von Facebook verwenden?');

define('TEXT_PRODUCTS_IMAGE_REMOVE', '<b>Entfernen</b> des Bildes vom Artikel?');
define('TEXT_PRODUCTS_BUTTON_DELETE', '<b>Löschen</b> des Bildes vom Server?');
define('TEXT_ADD_MORE_UPLOAD', 'Mehr Felder zum Hochladen hinzufügen');
define('TEXT_NOT_RELOAD', 'Lädt nicht erneut!');

define('TEXT_INFO_DETAILS', 'Details');
define('TEXT_INFO_PREVIEW', 'Voransicht');

define('ENTRY_STATUS', 'Status:');
define('TEXT_PRODUCTS_STATUS', 'Produktstatus:');
define('TEXT_CATEGORIES', 'Kategorien:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Erscheinungsdatum:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'nicht lieferbar');
define('TEXT_PRODUCTS_MANUFACTURER', 'Artikel-Hersteller:');
define('TEXT_PRODUCTS_NAME', 'Artikelname:');
define('TEXT_PRODUCTS_TITLE', 'Artikel-Titel für SEO:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Artikelbeschreibung:');
define('TEXT_PRODUCTS_SHORT_DESCRIPTION', 'Artikelkurzbeschreibung:');
define('TEXT_PRODUCTS_ESSENTIAL_CHARACTERISTICS', 'Die wesentlichen Merkmale:');
define('TEXT_PRODUCTS_DESCRIPTION_META', 'Artikelbeschreibung für Description Tag (max. 250 Zeichen)');
define('TEXT_PRODUCTS_QUANTITY', 'Lagerbestand:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Mindestlagerbestand:');
define('TEXT_REPLACEMENT_PRODUCT', 'Ersatzprodukt:');
define('TEXT_PRODUCTS_MODEL', 'Artikel-Nr.:');
define('TEXT_PRODUCTS_EAN', 'EAN :');
define('TEXT_PRODUCTS_IMAGE', 'Artikelbilder');
define('TEXT_PRODUCTS_URL', 'Herstellerlink:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(ohne führendes http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Artikelpreis (Netto):');
define('TEXT_PRODUCTS_PRICE_WITH_TAX', 'Artikelpreis (Brutto):');
define('TEXT_PRODUCTS_LIST_PRICE', 'empfohlener Verkaufspreis des Herstellers (Netto):');
define('TEXT_PRODUCTS_LIST_PRICE_WITH_TAX', 'empfohlener Verkaufspreis des Herstellers (Brutto):');

define('TEXT_PRODUCTS_WEIGHT', 'Artikelgewicht:');
define('TEXT_IMAGE_REMOVE', '<b>Entfernen</b> des Bildes vom Artikel?');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Fehler: Produkte können nicht in der gleichen Kategorie verlinkt werden.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fehler: Das Verzeichnis \'images\' im Katalogverzeichnis ist schreibgeschützt: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Das Verzeichnis \'images\' im Katalogverzeichnis ist nicht vorhanden: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);

define('ERROR_OUTOFSTOCK', 'Der Lagerbestand dieses Artikels ist nicht ausreichend Verfügbar.');
define('ERROR_REPLACEMENT', 'Es wurde ein Ersatzprodukt angeben. Der Status wurde geändert auf: Nicht mehr verfügbar/Es gibt ein Ersatzproduk.');

define('TEXT_DISCOUNTS_TITLE', 'Staffelpreise');
define('TEXT_DISCOUNTS_BREAKS', 'Staffel');
define('TEXT_DISCOUNTS_QTY', 'Menge');
define('TEXT_DISCOUNTS_PRICE', 'Preis (Netto):');
define('TEXT_DISCOUNTS_PRICE_WITH_TAX', 'Preis (Brutto):');
