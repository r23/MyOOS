<?php
/* ----------------------------------------------------------------------
   $Id: categories.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
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

define('HEADING_TITLE', 'Categorie&euml;n / Artikelen');
define('HEADING_TITLE_SEARCH', 'Zoeken: ');
define('HEADING_TITLE_GOTO', 'Ga naar:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categorie&euml;n / Artikelen');
define('TABLE_HEADING_ACTION', 'Aktie');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_MANUFACTURERS', 'Fabrikant');
define('TABLE_HEADING_PRODUCT_SORT', 'Sort Order');

define('TEXT_NEW_PRODUCT', 'Nieuw artikel in &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Categorie&euml;n:');
define('TEXT_SUBCATEGORIES', 'Subcategorie&euml;n:');
define('TEXT_PRODUCTS', 'Artikel:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Prijs:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Belastinggroep:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Doorsnee beoordeling:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Aantal:');
define('TEXT_DATE_ADDED', 'Toevoegen op:');
define('TEXT_DATE_AVAILABLE', 'Verschijningsdatum:');
define('TEXT_LAST_MODIFIED', 'Laatste verandering:');
define('TEXT_IMAGE_NONEXISTENT', 'GEEN AFBEELDING');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Voeg een nieuwe categorie of een artikel toe in: <br />&nbsp;<br /><b>%s</b>');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Voor verdere informatie bezoek de <a href="http://%s" target="blank"><u>Website</u></a> van de fabrikant.');
define('TEXT_PRODUCT_DATE_ADDED', 'Dit artikel hebben wij op %s in onze catalogus opgenomen.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Dit artikel is verkrijgbaar vanaf %s.');

define('TEXT_INFO_PERCENTAGE', 'Procent:');
define('TEXT_INFO_EXPIRES_DATE', 'Geldig tot:');

define('TEXT_EDIT_INTRO', 'Voer a.u.b. alle noodzakelijke veranderingen in.');
define('TEXT_EDIT_CATEGORIES_ID', 'Categorie ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Categorienaam:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Categorie afbeelding:');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Categorie koptekst');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Categoriebeschrijving');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION_META', 'Categoriebeschrijving voor Description Tag (max. 250 karakters)');
define('TEXT_EDIT_CATEGORIES_KEYWORDS_META', 'Categorie zoekwoord voor Keyword Tag (korte woorden door komma gescheiden - max. 250 karakters)');
define('TEXT_EDIT_SORT_ORDER', 'Sorteervolgorde:');
define('TEXT_TAX_INFO', 'Netto:');
define('TEXT_PRODUCTS_LIST_PRICE', 'Adviesprijs:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED', 'Kortingsmaximum:');


define('TEXT_INFO_COPY_TO_INTRO', 'Selecteer a.u.b. een nieuwe categorie, waarnaar u het artikel copi&euml; wilt:');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Aktuele categorie&euml;n:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Nieuwe categorie');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Categorie bewerken');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Categorie verwijderen');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Categorie verplaatsen');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Artikel verwijderen');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Artikel verplaatsen');
define('TEXT_INFO_HEADING_COPY_TO', 'Copi&euml;eren naar');

define('TEXT_DELETE_CATEGORY_INTRO', 'Weet u zeker, dat u deze categorie verwijderen wilt?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Weet u zeker, dat u dit artikel verwijderen wilt?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WAARSCHUWING:</b> Er zijn nog %s (Sub-)Categorie&euml;n, die met deze categorie gekoppeld zijn!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>WAARSCHUWING:</b> Er zijn nog %s artikelen, die met deze categorie gekoppeld zijn!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Selecteer a.u.b.  de bovenliggende categorie, waarin u <b>%s</b> verplaatsen wilt');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Selecteerd a.u.b. de bovenliggende categorie, waarin u <b>%s</b> verplaatsen wilt');
define('TEXT_MOVE', 'Verplaatsen <b>%s</b> naar:');

define('TEXT_NEW_CATEGORY_INTRO', 'Voer alle relevante gegevens in voor nieuwe categorie.');
define('TEXT_CATEGORIES_NAME', 'Categorienaam:');
define('TEXT_CATEGORIES_IMAGE', 'Categorieafbeelding:');
define('TEXT_SORT_ORDER', 'Sorteervolgorde:');

define('TEXT_PRODUCTS_STATUS', 'Productstatus:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Verschijningsdatum:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Niet leverbaar');
define('TEXT_PRODUCTS_MANUFACTURER', 'Artikelfabrikant:');
define('TEXT_PRODUCTS_NAME', 'Artikelnaam:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Artikelbeschrijving:');
define('TEXT_PRODUCTS_DESCRIPTION_META', 'Artikelbeschrijving voor de Description Tag (max. 250 karakters)');
define('TEXT_PRODUCTS_KEYWORDS_META', 'Artikel zoekwoorden voor Keyword Tag (korte woorden door komma gescheiden - max. 250 karakters)');
define('TEXT_PRODUCTS_QUANTITY', 'Artikelaantal:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Minimale voorraad:');
define('TEXT_PRODUCTS_MODEL', 'Artikelnr.:');
define('TEXT_PRODUCTS_IMAGE', 'Artikelafbeelding:');
define('TEXT_PRODUCTS_URL', 'Fabrikant link:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(zonder voorvoegsel http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Artikelprijs:');
define('TEXT_PRODUCTS_WEIGHT', 'Artikelgewicht:');

define('EMPTY_CATEGORY', 'Lege categorie');

define('TEXT_HOW_TO_COPY', 'Kopieermethode:');
define('TEXT_COPY_AS_LINK', 'Product linken');
define('TEXT_COPY_AS_DUPLICATE', 'Product dupliceren');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Fout: Producten kunnen niet in de zelfde categorie gelinkt worden.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fout: De map \'images\' in de webwinkelmap is schrijfbeschermd: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fout: De map \'images\' in de webwinkelmap bestaat niet: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);

define('TEXT_ADD_SLAVE_PRODUCT','Voer de product ID in om dit product aan te koppelen als subproduct:');
define('IMAGE_SLAVE','Sub producten');
define('TEXT_CURRENT_SLAVE_PRODUCTS','<b>Huidige sub producten:</b>');
define('IMAGE_DELETE_SLAVE','Verwijder dit sub product');
?>
