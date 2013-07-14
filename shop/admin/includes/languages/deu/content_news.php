<?php
/* ----------------------------------------------------------------------
   $Id: content_news.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Kategorien / News');
define('HEADING_TITLE_SEARCH', 'Suche: ');
define('HEADING_TITLE_GOTO', 'Gehe zu:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_NEWS', 'Kategorien / News');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_PUBLISHED', 'ver&ouml;ffentlichen');
define('TABLE_HEADING_AUTHOR', 'Autor');

define('TEXT_NEW_NEWS', 'Neue News in &quot;%s&quot;');
define('TABLE_NEWS_CATEGORIES', 'Kategorien:');
define('TEXT_SUBCATEGORIES', 'Unterkategorien:');
define('TEXT_NEWS', 'News:');

define('TEXT_NEWS_AVERAGE_RATING', 'durchschnittl. Bewertung:');

define('TEXT_DATE_ADDED', 'hinzugef&uuml;gt am:');
define('TEXT_DATE_EXPIRES', 'Gltig bis:');
define('TEXT_LAST_MODIFIED', 'am:');
define('TEXT_LAST_MODIFIED_BY', 'letzte &Auml;nderung von:');
define('TEXT_DATE_ADDED_BY', 'Autor:');
define('TEXT_IMAGE_NONEXISTENT', 'BILD EXISTIERT NICHT');
define('TEXT_NO_CHILD_CATEGORIES_OR_NEWS', 'Bitte f&uuml;gen Sie eine neue Kategorie oder eine News in <br />&nbsp;<br /><b>%s</b> ein.');
define('TEXT_NEWS_MORE_INFORMATION', 'F&uuml;r weitere Informationen, besuchen Sie bitte die <a href="http://%s" target="blank"><u>Homepage</u></a>.');
define('TEXT_NEWS_DATE_ADDED', 'Diese News haben wir am %s aufgenommen.');
define('TEXT_NEWS_DATE_EXPIRES', 'Diese News sind gltig bis %s.');

define('TEXT_EDIT_INTRO', 'Bitte f&uuml;hren Sie alle notwendigen &Auml;nderungen durch.');
define('TEXT_EDIT_CATEGORIES_ID', 'Kategorie ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Kategorie Name:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Kategorie Bild:');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Kategorie &Uuml;berschrift');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Kategorie Beschreibung');
define('TEXT_EDIT_SORT_ORDER', 'Sortierreihenfolge:');

define('TEXT_NEWSFEED_CATEGORIES', 'Newsfeed Kategorien'); 

define('TEXT_INFO_COPY_TO_INTRO', 'Bitte w&auml;hlen Sie eine neue Kategorie aus, in die Sie die News kopieren m&ouml;chten:');
define('TEXT_INFO_CURRENT_CATEGORIES', 'aktuelle Kategorien:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Neue Kategorie');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Kategorie bearbeiten');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Kategorie l&ouml;schen');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Kategorie verschieben');
define('TEXT_INFO_HEADING_DELETE_NEWS', 'News l&ouml;schen');
define('TEXT_INFO_HEADING_MOVE_NEWS', 'News verschieben');
define('TEXT_INFO_HEADING_COPY_TO', 'Kopieren nach');

define('TEXT_DELETE_CATEGORY_INTRO', 'Sind Sie sicher, dass Sie diese Kategorie l&ouml;schen m&ouml;chten?');
define('TEXT_DELETE_NEWS_INTRO', 'Sind Sie sicher, dass Sie diese News l&ouml;schen m&ouml;chten?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNUNG:</b> Es existieren noch %s (Unter-)Kategorien, die mit dieser Kategorie verbunden sind!');
define('TEXT_DELETE_WARNING_NEWS', '<b>WARNING:</b> Es existieren noch %s News, die mit dieser Kategorie verbunden sind!');

define('TEXT_MOVE_NEWS_INTRO', 'Bitte w&auml;hlen Sie die &uuml;bergordnete Kategorie, in die Sie <b>%s</b> verschieben m&ouml;chten');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Bitte w&auml;hlen Sie die &uuml;bergordnete Kategorie, in die Sie <b>%s</b> verschieben m&ouml;chten');
define('TEXT_MOVE', 'Verschiebe <b>%s</b> nach:');

define('TEXT_NEW_CATEGORY_INTRO', 'Bitte geben Sie die neue Kategorie mit allen relevanten Daten ein.');
define('TABLE_NEWS_CATEGORIES_NAME', 'Kategorie Name:');
define('TABLE_NEWS_CATEGORIES_IMAGE', 'Kategorie Bild:');
define('TEXT_SORT_ORDER', 'Sortierreihenfolge:');

define('TEXT_NEW_DATE_EXPIRES', 'G&uuml;ltig bis:');
define('TEXT_NEWS_NAME', 'News Titel:');
define('TEXT_NEWS_DESCRIPTION', 'News:');
define('TEXT_NEWS_IMAGE', 'Newsbild:');
define('TEXT_NEWS_URL', 'Link:');
define('TEXT_NEWS_URL_WITHOUT_HTTP', '<small>(ohne f&uuml;hrendes http://)</small>');

define('EMPTY_CATEGORY', 'Leere Kategorie');

define('TEXT_HOW_TO_COPY', 'Kopiermethode:');
define('TEXT_COPY_AS_LINK', 'News verlinken');
define('TEXT_COPY_AS_DUPLICATE', 'News duplizieren');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Fehler: News k&ouml;nnen nicht in der gleichen Kategorie verlinkt werden.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Fehler: Das Verzeichnis \'images\' im Katalogverzeichnis ist schreibgesch&uuml;tzt: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Fehler: Das Verzeichnis \'images\' im Katalogverzeichnis ist nicht vorhanden: ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
?>
