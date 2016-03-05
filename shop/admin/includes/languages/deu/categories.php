<?php
/* ----------------------------------------------------------------------
   $Id: categories.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.22 2002/08/17 09:43:33 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Kategorien / Artikel');
define('HEADING_TITLE_SEARCH', 'Suche: ');
define('HEADING_TITLE_GOTO', 'Gehe zu:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Kategorien / Artikel');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_MANUFACTURERS', 'Hersteller');
define('TABLE_HEADING_PRODUCT_SORT', 'Sort Order');

define('TEXT_NEW_PRODUCT', 'Neuer Artikel in &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Kategorien:');
define('TEXT_SUBCATEGORIES', 'Unterkategorien:');
define('TEXT_PRODUCTS', 'Artikel:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Preis:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Steuerklasse:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'durchschnittl. Bewertung:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Anzahl:');
define('TEXT_DATE_ADDED', 'hinzugef&uuml;gt am:');
define('TEXT_DATE_AVAILABLE', 'Erscheinungsdatum:');
define('TEXT_LAST_MODIFIED', 'letzte &Auml;nderung:');
define('TEXT_IMAGE_NONEXISTENT', 'BILD EXISTIERT NICHT');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Bitte f&uuml;gen Sie eine neue Kategorie oder einen Artikel in <br />&nbsp;<br /><b>%s</b> ein.');
define('TEXT_PRODUCT_MORE_INFORMATION', 'F&uuml;r weitere Informationen, besuchen Sie bitte die <a href="http://%s" target="blank"><u>Homepage</u></a> des Herstellers.');
define('TEXT_PRODUCT_DATE_ADDED', 'Diesen Artikel haben wir am %s in unseren Katalog aufgenommen.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Dieser Artikel ist erh&auml;ltlich ab %s.');

define('TEXT_INFO_PERCENTAGE', 'Prozent:');
define('TEXT_INFO_EXPIRES_DATE', 'G&uuml;ltig bis:');

define('TEXT_EDIT_INTRO', 'Bitte f&uuml;hren Sie alle notwendigen &Auml;nderungen durch.');
define('TEXT_EDIT_CATEGORIES_ID', 'Kategorie ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Kategorie Name');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Kategorie Bild');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Meta Tag Titel');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Kategorie Beschreibung');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION_META', 'Meta Tag Beschreibung');
define('TEXT_EDIT_CATEGORIES_KEYWORDS_META', 'Meta Tag Suchworte');
define('TEXT_EDIT_SORT_ORDER', 'Sortierreihenfolge');
define('TEXT_EDIT_STATUS', 'Status');
define('TEXT_TAX_INFO', 'Netto:');
define('TEXT_PRODUCTS_LIST_PRICE', 'empf VK:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED', 'Discountmaximum:');


define('TEXT_INFO_COPY_TO_INTRO', 'Bitte w&auml;hlen Sie eine neue Kategorie aus, in die Sie den Artikel kopieren m&ouml;chten:');
define('TEXT_INFO_CURRENT_CATEGORIES', 'aktuelle Kategorien:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Neue Kategorie');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Kategorie bearbeiten');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Kategorie l&ouml;schen');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Kategorie verschieben');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Artikel l&ouml;schen');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Artikel verschieben');
define('TEXT_INFO_HEADING_COPY_TO', 'Kopieren nach');

define('TEXT_DELETE_CATEGORY_INTRO', 'Sind Sie sicher, dass Sie diese Kategorie l&ouml;schen m&ouml;chten?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Sind Sie sicher, dass Sie diesen Artikel l&ouml;schen m&ouml;chten?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNUNG:</b> Es existieren noch %s (Unter-)Kategorien, die mit dieser Kategorie verbunden sind!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WARNING:</b> Es existieren noch %s Artikel, die mit dieser Kategorie verbunden sind!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Bitte w&auml;hlen Sie die &uuml;bergordnete Kategorie, in die Sie <b>%s</b> verschieben m&ouml;chten');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Bitte w&auml;hlen Sie die &uuml;bergordnete Kategorie, in die Sie <b>%s</b> verschieben m&ouml;chten');
define('TEXT_MOVE', 'Verschiebe <b>%s</b> nach:');

define('TEXT_NEW_CATEGORY_INTRO', 'Bitte geben Sie die neue Kategorie mit allen relevanten Daten ein.');
define('TEXT_CATEGORIES_NAME', 'Kategorie Name:');
define('TEXT_CATEGORIES_IMAGE', 'Kategorie Bild:');
define('TEXT_SORT_ORDER', 'Sortierreihenfolge:');

define('TEXT_PRODUCTS_STATUS', 'Produktstatus:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Erscheinungsdatum:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'nicht lieferbar');
define('TEXT_PRODUCTS_MANUFACTURER', 'Artikel-Hersteller:');
define('TEXT_PRODUCTS_NAME', 'Artikelname:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Artikelbeschreibung:');
define('TEXT_PRODUCTS_DESCRIPTION_META', 'Artikelbeschreibung f&uuml;r Description Tag (max. 250 Zeichen)');
define('TEXT_PRODUCTS_KEYWORDS_META', 'Artikel Suchworte f&uuml;r Keyword Tag (Stichworte durch Komma getrennt - max. 250 Zeichen)');
define('TEXT_PRODUCTS_QUANTITY', 'Artikelanzahl:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Mindestlagerbestand:');
define('TEXT_PRODUCTS_MODEL', 'Artikel-Nr.:');
define('TEXT_PRODUCTS_IMAGE', 'Artikelbild:');
define('TEXT_PRODUCTS_URL', 'Herstellerlink:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(ohne f&uuml;hrendes http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Artikelpreis:');
define('TEXT_PRODUCTS_WEIGHT', 'Artikelgewicht:');

define('EMPTY_CATEGORY', 'Leere Kategorie');

define('TEXT_HOW_TO_COPY', 'Kopiermethode:');
define('TEXT_COPY_AS_LINK', 'Produkt verlinken');
define('TEXT_COPY_AS_DUPLICATE', 'Produkt duplizieren');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Fehler: Produkte k&ouml;nnen nicht in der gleichen Kategorie verlinkt werden.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fehler: Das Verzeichnis \'images\' im Katalogverzeichnis ist schreibgesch&uuml;tzt: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Das Verzeichnis \'images\' im Katalogverzeichnis ist nicht vorhanden: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);

define('TEXT_ADD_SLAVE_PRODUCT', 'Geben Sie in die Produkt-ID, die Sie als Zubeh&ouml;r / Option hinzuzuf&uuml;gen m&ouml;chten ein:');
define('IMAGE_SLAVE', 'Produktoptionen');
define('TEXT_CURRENT_SLAVE_PRODUCTS', 'Aktuelle Produktoptionen:</b>');
define('BUTTON_DELETE_SLAVE', 'Lösche diese Produktoption');

// Qty Min/Units and List/Rebates
// categories.php definitions
define('CAT_CATEGORY_ID_TEXT', 'ID # ');
define('CAT_PRODUCT_ID_TEXT', 'ID # ');
define('CAT_ATTRIBUTES_BASE_PRICE_TEXT', 'Basispreis : ');
define('CAT_LIST_PRICE_TEXT',  'empf. VK.:');
define('CAT_REBATE_PRICE_TEXT', 'Rabate: ');
define('CAT_QUANTITY_MIN_TEXT', 'Mindestbestellmenge:');
define('CAT_QUANTITY_UNITS_TEXT', 'Verpackungseinheit: ');

// Attribute Copy Option
define('TEXT_COPY_ATTRIBUTES_ONLY', 'Nur Produktinformationen kopieren ...');
define('TEXT_COPY_ATTRIBUTES', 'Artikeloptionen kopieren?');
define('TEXT_COPY_ATTRIBUTES_YES', 'Ja');
define('TEXT_COPY_ATTRIBUTES_NO', 'Nein');

define('TEXT_DATA', 'Daten');
define('TEXT_IMAGES', 'Bilder');
define('TEXT_UPLOAD', 'Bilder hochladen');
define('TEXT_GRAPHICS_INFO', 'Das webbasierten Hochladen akzeptiert die folgenden Formate: %s, und %s.');
define('TEXT_GRAPHICS_NOTE', 'Anmerkung:');
define('TEXT_GRAPHICS_ZIP', 'ZIP-Dateien d&uuml;rfen nur von MyOOS unterst&uuml;tzte Bildformate enthalten.');
define('TEXT_GRAPHICS_MAXIMUM', 'Die maximale Gr&ouml;&szlig;e pro Hochlade-Vorgang ist <strong>%sB</strong> und die maximale Größe insgesamt <strong>%sB</strong>. Diese sind von <code>upload_max_filesize</code> und <code>post_max_size</code> Ihrer PHP-Konfiguration so vorgegeben.');
define('TEXT_GRAPHICS_MAX_SIZE', 'Die maximale Größe pro Hochlade-Vorgang ist <strong>%sB</strong>. Dies ist von <code>post_max_size</code> Ihrer PHP-Konfiguration vorgegeben.');
