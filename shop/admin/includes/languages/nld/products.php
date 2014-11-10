<?php
/* ----------------------------------------------------------------------
   $Id: products.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/


   Copyright (c) 2003 - 2007 by the OOS Development Team.
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

define('TEXT_NEW_PRODUCT', 'Nieuw artikel in &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Artikel:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Prijs:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Belastingtarief:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Gemiddelde beoordeling:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Aantal:');
define('TEXT_DATE_ADDED', 'Toegevoegd op:');
define('TEXT_DATE_AVAILABLE', 'Verschijningsdatum:');
define('TEXT_LAST_MODIFIED', 'Laatste verandering:');
define('TEXT_IMAGE_NONEXISTENT', 'GEEN AFBEELDING');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Voor meer informatie bezoek dan de <a href="http://%s" target="blank"><u>Homepage</u></a> van de farikant.');
define('TEXT_PRODUCT_DATE_ADDED', 'Dit artikel hebben wij in onze catalogus opgenomen.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Dit artikel is verkrijgbaar vanaf %s.');

define('TEXT_TAX_INFO', 'Netto:');
define('TEXT_PRODUCTS_LIST_PRICE', 'Adviesprijs:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED', 'Kortingsmaximum:');

define('TEXT_PRODUCTS_BASE_PRICE', 'Basisprijs ');
define('TEXT_PRODUCTS_BASE_UNIT', 'Basiseenheid:');
define('TEXT_PRODUCTS_BASE_PRICE_FACTOR', 'Factor om de basisprijs te berekenen:');
define('TEXT_PRODUCTS_BASE_QUANTITY', 'Basishoeveelheid:');
define('TEXT_PRODUCTS_PRODUCT_QUANTITY', 'Artikelhoeveelheid:');
define('TEXT_PRODUCTS_DECIMAL_QUANTITY', 'Decimal Quantity');
define('TEXT_PRODUCTS_UNIT', 'Product Unit');

define('TEXT_PRODUCTS_IMAGE_REMOVE', '<b>Verwijderen</b> van de afbeelding van het artikel?');
define('TEXT_PRODUCTS_IMAGE_DELETE', '<b>Wissen</b> van de afbeelding van de server?');
define('TEXT_PRODUCTS_ZOOMIFY', 'Zoomify');

define('TEXT_PRODUCTS_STATUS', 'Produktstatus:');
define('TEXT_CATEGORIES', 'Categorie&euml;n:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Leverbaar op:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Niet leverbaar');
define('TEXT_PRODUCTS_MANUFACTURER', 'Artikelfabrikant:');
define('TEXT_PRODUCTS_NAME', 'Artikelnaam:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Artikelbeschrijving:');
define('TEXT_PRODUCTS_DESCRIPTION_META', 'Artikelbeschrijving voor Description Tag (max. 250 karakters)');
define('TEXT_PRODUCTS_KEYWORDS_META', 'Artikel zoekwoord voor Keyword Tag (Zoekwoorden door komma gescheiden - max. 250 karakters)');
define('TEXT_PRODUCTS_QUANTITY', 'Artikelaantal:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Minimale voorraad:');
define('TEXT_PRODUCTS_MODEL', 'Artikelnr.:');
define('TEXT_PRODUCTS_EAN', 'EAN :');
define('TEXT_PRODUCTS_IMAGE', 'Artikelafbeelding:');
define('TEXT_PRODUCTS_URL', 'Fabrikant link:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(zonder voorzet van http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Artikelprijs:');
define('TEXT_PRODUCTS_WEIGHT', 'Artikelgewicht:');
define('TEXT_PRODUCTS_SORT_ORDER', 'Sort Order:');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Fout: Produkten kunnen niet binnen de zelfde categorie gelinkt worden.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fout: De map \'images\' in catalogusmap is schrijfbeschermd: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fout: De map \'images\' in catalogusmap bestaat niet: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
?>
