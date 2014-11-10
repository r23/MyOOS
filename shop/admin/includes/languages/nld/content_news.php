<?php
/* ----------------------------------------------------------------------
   $Id: content_news.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Categorie&euml;n / Nieuws');
define('HEADING_TITLE_SEARCH', 'Zoeken: ');
define('HEADING_TITLE_GOTO', 'Ga naar:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_NEWS', 'Categorie&euml;n / Nieuws');
define('TABLE_HEADING_ACTION', 'Aktie');
define('TABLE_HEADING_PUBLISHED', 'Uitgeven');
define('TABLE_HEADING_AUTHOR', 'Auteur');

define('TEXT_NEW_NEWS', 'Nieuwe nieuwsberichten in &quot;%s&quot;');
define('TABLE_NEWS_CATEGORIES', 'Categorie&euml;n:');
define('TEXT_SUBCATEGORIES', 'Sub-categorie&euml;n:');
define('TEXT_NEWS', 'Nieuws:');

define('TEXT_NEWS_AVERAGE_RATING', 'Gemiddelde beoordeling:');

define('TEXT_DATE_ADDED', 'Toegevoegd op:');
define('TEXT_DATE_EXPIRES', 'Geldig tot:');
define('TEXT_LAST_MODIFIED', 'op:');
define('TEXT_LAST_MODIFIED_BY', 'Laatste verandering van:');
define('TEXT_DATE_ADDED_BY', 'Auteur:');
define('TEXT_IMAGE_NONEXISTENT', 'GEEN AFBEELDING');
define('TEXT_NO_CHILD_CATEGORIES_OR_NEWS', 'Voeg een nieuwe categorie of een nieuw nieuwbericht toe in: <br />&nbsp;<br /><b>%s</b>');
define('TEXT_NEWS_MORE_INFORMATION', 'Voor verdere informatie, bezoek dan <a href="http://%s" target="blank"><u>Homepage</u></a>.');
define('TEXT_NEWS_DATE_ADDED', 'Dit nieuwsbericht is op %s ingevoerd.');
define('TEXT_NEWS_DATE_EXPIRES', 'Deze nieuwsberichten zijn geldig tot %s.');

define('TEXT_EDIT_INTRO', 'Voer a.u.b. alle noodzakelijke veranderingen in.');
define('TEXT_EDIT_CATEGORIES_ID', 'Categorie ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Categorienaam:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Categorieafbeelding:');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Categoriekop');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Categoriebeschrijving');
define('TEXT_EDIT_SORT_ORDER', 'Sorteervolgorde:');

define('TEXT_NEWSFEED_CATEGORIES', 'Nieuwsberichten categorie&euml;n'); 

define('TEXT_INFO_COPY_TO_INTRO', 'Selecteer een nieuwe categorie, waarin u de nieuwberichten kopi&euml;ren wilt:');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Actuele categorie&euml;n:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Nieuwe categorie');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Categorie bewerken');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Categorie verwijderen');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Categorie verplaatsen');
define('TEXT_INFO_HEADING_DELETE_NEWS', 'Nieuwsbericht verwijderen');
define('TEXT_INFO_HEADING_MOVE_NEWS', 'Nieuwsbericht verplaatsen');
define('TEXT_INFO_HEADING_COPY_TO', 'Kopi&euml;ren naar');

define('TEXT_DELETE_CATEGORY_INTRO', 'Weet u zeker dat u deze categorie verwijderen wilt?');
define('TEXT_DELETE_NEWS_INTRO', 'Weet u zeker dat u dit nieuwsbericht verwijderen wilt?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WAARSCHUWING:</b> Er bestaan nog  %s (Sub-)Categorie&euml;n, die met deze categorie gekopeld zijn!');
define('TEXT_DELETE_WARNING_NEWS', '<b>WARNING:</b> Er bestaan nog %s Nieuwsberichten, die met deze categorie gekoppeld zijn!');

define('TEXT_MOVE_NEWS_INTRO', 'Selecteer de bovenliggende categorie, waarin u <b>%s</b> verplaatsen wilt');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Selecteer a.u.b. de bovenliggende categorie, waarin u <b>%s</b> verplaatsen wilt');
define('TEXT_MOVE', 'Verplaats <b>%s</b> naar:');

define('TEXT_NEW_CATEGORY_INTRO', 'Voer de nieuwe categorie met alle relevante gegevens in.');
define('TABLE_NEWS_CATEGORIES_NAME', 'Categorienaam:');
define('TABLE_NEWS_CATEGORIES_IMAGE', 'Categorieafbeelding:');
define('TEXT_SORT_ORDER', 'Sorteervolgorde:');

define('TEXT_NEW_DATE_EXPIRES', 'Geldig tot:');
define('TEXT_NEWS_NAME', 'Nieuwsbericht titel:');
define('TEXT_NEWS_DESCRIPTION', 'Nieuwsbericht:');
define('TEXT_NEWS_IMAGE', 'Nieuwsberichtafbeelding:');
define('TEXT_NEWS_URL', 'Koppeling:');
define('TEXT_NEWS_URL_WITHOUT_HTTP', '<small>(zonder voorzet van http://)</small>');

define('EMPTY_CATEGORY', 'Lege categorie');

define('TEXT_HOW_TO_COPY', 'Kopieermethode:');
define('TEXT_COPY_AS_LINK', 'Nieuwsberichten koppelen');
define('TEXT_COPY_AS_DUPLICATE', 'Nieuwsbericht dupliceren');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Fout: Nieuwsberichten kunnen niet in de zelfde categorie gekoppeld worden.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fout: De map \'images\' in de webwinkelmap is schrijfbeschermd: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fout: De map \'images\' in de webwinkelmap bestaat niet: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
?>
