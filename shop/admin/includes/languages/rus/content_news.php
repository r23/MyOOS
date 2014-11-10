<?php
/* ----------------------------------------------------------------------
   $Id: content_news.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

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

define('HEADING_TITLE', 'Категория / Новости');
define('HEADING_TITLE_SEARCH', 'Поиск: ');
define('HEADING_TITLE_GOTO', 'Перейти к:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_NEWS', 'Категория / Новости');
define('TABLE_HEADING_ACTION', 'Акция');
define('TABLE_HEADING_PUBLISHED', 'Опубликовать');
define('TABLE_HEADING_AUTHOR', 'Автор');

define('TEXT_NEW_NEWS', 'Новые новости в &quot;%s&quot;');
define('TABLE_NEWS_CATEGORIES', 'Категории:');
define('TEXT_SUBCATEGORIES', 'Подкатегории:');
define('TEXT_NEWS', 'Новости:');

define('TEXT_NEWS_AVERAGE_RATING', 'Среднее значение рейтинга');

define('TEXT_DATE_ADDED', 'добавленно:');
define('TEXT_DATE_EXPIRES', 'действительно до:');
define('TEXT_LAST_MODIFIED', 'изменено:');
define('TEXT_LAST_MODIFIED_BY', 'последнее изменение от:');
define('TEXT_DATE_ADDED_BY', 'Автор:');
define('TEXT_IMAGE_NONEXISTENT', 'НЕТ КАРТИНКИ');
define('TEXT_NO_CHILD_CATEGORIES_OR_NEWS', 'Внесите новую категорию или новость в <br />&nbsp;<br /><b>%s</b> ');
define('TEXT_NEWS_MORE_INFORMATION', 'Для дополнительной информации посетите пожалуйста <a href="http://%s" target="blank"><u>Сайт</u></a>.');
define('TEXT_NEWS_DATE_ADDED', 'Эти новости внесены %s ');
define('TEXT_NEWS_DATE_EXPIRES', 'Эти новости действительны до %s.');

define('TEXT_EDIT_INTRO', 'Пожалуйста внесите необходимые изменения.');
define('TEXT_EDIT_CATEGORIES_ID', 'ID категории:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Название категории:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Картинка категории:');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Заголовок Категории');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Описание категории:');
define('TEXT_EDIT_SORT_ORDER', 'Последовательность сортировки:');

define('TEXT_NEWSFEED_CATEGORIES', 'Newsfeed Категории'); 

define('TEXT_INFO_COPY_TO_INTRO', 'Выберите новую категорию в которую вы хотите скопировать новости:');
define('TEXT_INFO_CURRENT_CATEGORIES', 'актуальные Категории:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Новая категория');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Обработать категорию');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Удалить категорию');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Переместить категорию');
define('TEXT_INFO_HEADING_DELETE_NEWS', 'Удалить новость');
define('TEXT_INFO_HEADING_MOVE_NEWS', 'Переместить новость');
define('TEXT_INFO_HEADING_COPY_TO', 'Копировать в');

define('TEXT_DELETE_CATEGORY_INTRO', 'Вы уверенны, что хотите удалить категорию?');
define('TEXT_DELETE_NEWS_INTRO', 'Вы уверенны, что хотите удалить новости?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>ПРЕДУПРЕЖДЕНИЕ:</b> Существует ещё %s (Под)категория которые связаны с этой категорией!');
define('TEXT_DELETE_WARNING_NEWS', '<b>ПРЕДУПРЕЖДЕНИЕ:</b> Существует ещё %s Новости которые связаны с этой категорией!');

define('TEXT_MOVE_NEWS_INTRO', 'Выберите вышестоящую категорию в которую вы хотите переместить <b>%s</b>');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Пожалуйста выберите вышестоящую категорию в которую вы хотите переместить <b>%s</b> ');
define('TEXT_MOVE', 'Переместить в: <b>%s</b>');

define('TEXT_NEW_CATEGORY_INTRO', 'Пожалуйста создайте новую категорию с необходимыми к немй данными.');
define('TABLE_NEWS_CATEGORIES_NAME', 'Название категории:');
define('TABLE_NEWS_CATEGORIES_IMAGE', 'Картинка категории:');
define('TEXT_SORT_ORDER', 'Последовательность сортировки:');

define('TEXT_NEW_DATE_EXPIRES', 'Действительно до:');
define('TEXT_NEWS_NAME', 'Заголовок Новости:');
define('TEXT_NEWS_DESCRIPTION', 'Новости:');
define('TEXT_NEWS_IMAGE', 'Картинка новости:');
define('TEXT_NEWS_URL', 'Ссылка:');
define('TEXT_NEWS_URL_WITHOUT_HTTP', '<small>(без ведущего http://)</small>');

define('EMPTY_CATEGORY', 'Пустая категория');

define('TEXT_HOW_TO_COPY', 'Метод копирования:');
define('TEXT_COPY_AS_LINK', 'Ссылка на новости');
define('TEXT_COPY_AS_DUPLICATE', 'Дуплицировать новости');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Ошибка: Нельзя делать ссылку на новости в одной и той же категории.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Ошибка: Директория \'images\' недоступна для записи. в ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Ошибка: Директория \'images\' несуществует. в ' . OOS_ABSOLUTE_PATH . OOS_IMAGES);
?>