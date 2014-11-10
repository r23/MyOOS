<?php
/* ----------------------------------------------------------------------
   $Id: categories.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2005 by the OOS Development Team.
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

define('HEADING_TITLE', 'Категории / Артикли');
define('HEADING_TITLE_SEARCH', 'Поиск: ');
define('HEADING_TITLE_GOTO', 'Перейти к:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Категории / Артикли');
define('TABLE_HEADING_ACTION', 'Акция');
define('TABLE_HEADING_STATUS', 'Статус');
define('TABLE_HEADING_MANUFACTURERS', 'Производитель');
define('TABLE_HEADING_PRODUCT_SORT', 'Заказ Вида');

define('TEXT_NEW_PRODUCT', 'Новый артикль в &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Категории:');
define('TEXT_SUBCATEGORIES', 'Подкатегории');
define('TEXT_PRODUCTS', 'Продукты:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Цена:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Налог');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Средняя оценка:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Кол-во:');
define('TEXT_DATE_ADDED', 'добавленно:');
define('TEXT_DATE_AVAILABLE', 'Дата выпуска:');
define('TEXT_LAST_MODIFIED', 'последнее изменение:');
define('TEXT_IMAGE_NONEXISTENT', 'НЕТ КАРТИНКИ');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Внесите новую категорию или продукт в <br />&nbsp;<br /><b>%s</b> ');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Для дополнительной информации посетите сайт изготовителя <a href="http://%s" target="blank"><u>Сайт Изготовителя</u></a>');
define('TEXT_PRODUCT_DATE_ADDED', 'Этот продукт занесён в каталог %s ');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Этот продукт доступен с %s.');

define('TEXT_EDIT_INTRO', 'Пожалуйстасделайте все нужные изменения.');
define('TEXT_EDIT_CATEGORIES_ID', 'ID Категории:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Имя Категории:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Картинка категории:');
define('TEXT_EDIT_CATEGORIES_HEADING_TITLE', 'Название категории:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION', 'Описание категории:');
define('TEXT_EDIT_CATEGORIES_DESCRIPTION_META', 'Описание категории для Description Тэга (максимально 250 знаков)');
define('TEXT_EDIT_CATEGORIES_KEYWORDS_META', 'Слова поиска категории для Keyword Тэга (Слова через запятую максимально 250 знаков)');
define('TEXT_EDIT_SORT_ORDER', 'Порядок сортировки:');
define('TEXT_TAX_INFO', 'Нетто:');
define('TEXT_PRODUCTS_LIST_PRICE', 'Рекомендуемая цена продажи:');
define('TEXT_PRODUCTS_DISCOUNT_ALLOWED', 'Скидка Максимум:');


define('TEXT_INFO_COPY_TO_INTRO', 'Выберите новую категорию в которую вы хотите скопировать продукт:');
define('TEXT_INFO_CURRENT_CATEGORIES', 'актуальные категории:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Новая Категория');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Обработать категорию');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Удалить категорию');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Переместить категорию');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Удалить продукт');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Переместить продукт');
define('TEXT_INFO_HEADING_COPY_TO', 'Копировать в');

define('TEXT_DELETE_CATEGORY_INTRO', 'Вы уверены, что вы хотите удалить эту категорию?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Вы уверены, что вы хотите удалить этот продукт?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>ПРЕДУПРЕЖДЕНИЕ:</b> Ещё существуют %s подкатегории которые связанны с этой категорией!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>ПРЕДУПРЕЖДЕНИЕ:</b> Ещё существуют %s продуктыe которые связанны с этой категорией!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Выберите вышестоящую категорию в которую вы хотите перемемтить <b>%s</b> ');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Выберите вышестоящую категорию в которую вы хотите перемемтить <b>%s</b>');
define('TEXT_MOVE', 'Перемещаю в <b>%s</b>');

define('TEXT_NEW_CATEGORY_INTRO', 'Создайте новую категорию со всеми нужными датами.');
define('TEXT_CATEGORIES_NAME', 'Имя категории:');
define('TEXT_CATEGORIES_IMAGE', 'Картинка категории:');
define('TEXT_SORT_ORDER', 'Порядок сортировки:');

define('TEXT_PRODUCTS_STATUS', 'Статус продукта:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Дата выпуска:');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'временно недоступен');
define('TEXT_PRODUCTS_MANUFACTURER', 'Производитель:');
define('TEXT_PRODUCTS_NAME', 'Название продукта:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Описание продукта:');
define('TEXT_PRODUCTS_DESCRIPTION_META', 'Описание продукта для Description Тэга (максимально 250 знаков)');
define('TEXT_PRODUCTS_KEYWORDS_META', 'Слова поиска продукта для Keyword Тэга (Слова через запятую максимально 250 знаков)');
define('TEXT_PRODUCTS_QUANTITY', 'Кол-во продуктов:');
define('TEXT_PRODUCTS_REORDER_LEVEL', 'Минимальное кол-во на складе:');
define('TEXT_PRODUCTS_MODEL', '№ Артикля:');
define('TEXT_PRODUCTS_IMAGE', 'Картинка продукта:');
define('TEXT_PRODUCTS_URL', 'Ссылка на производителя:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(без http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Цена:');
define('TEXT_PRODUCTS_WEIGHT', 'Масса продукта');

define('EMPTY_CATEGORY', 'Пустая категория');

define('TEXT_HOW_TO_COPY', 'Метод копирования:');
define('TEXT_COPY_AS_LINK', 'Ссылку на продукт:');
define('TEXT_COPY_AS_DUPLICATE', 'Удвоить продукт:');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Ошибка: Нельзя делать ссылки на продукты в одинаковой категории.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Ошибка: Директория \'images\' в директории каталога защищена от записи: ' . OOS_ABSOLUTE_PATH_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Ошибка: Директории  \'images\' в директории каталога несуществует ' . OOS_ABSOLUTE_PATH_IMAGES);

define('TEXT_ADD_SLAVE_PRODUCT','Enter in the Product ID to add this product as a slave:');
define('IMAGE_SLAVE','Slave Products');
define('TEXT_CURRENT_SLAVE_PRODUCTS','<b>Current Slave products:</b>');
define('IMAGE_DELETE_SLAVE','Delete this slave product');
?>