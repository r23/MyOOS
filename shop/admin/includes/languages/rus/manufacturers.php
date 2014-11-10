<?php
/* ----------------------------------------------------------------------
   $Id: manufacturers.php,v 1.1 2007/06/13 17:03:54 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: manufacturers.php,v 1.10 2002/08/19 01:58:58  
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('HEADING_TITLE', 'Производители');

define('TABLE_HEADING_MANUFACTURERS', 'Производители');
define('TABLE_HEADING_ACTION', 'Действие');

define('TEXT_HEADING_NEW_MANUFACTURER', 'Новый Производитель');
define('TEXT_HEADING_EDIT_MANUFACTURER', 'Изменить Производителя');
define('TEXT_HEADING_DELETE_MANUFACTURER', 'Удалить Производителя');

define('TEXT_MANUFACTURERS', 'Производителм:');
define('TEXT_DATE_ADDED', 'Дата Добавления:');
define('TEXT_LAST_MODIFIED', 'Последнее Изменение:');
define('TEXT_PRODUCTS', 'Товары:');
define('TEXT_IMAGE_NONEXISTENT', 'КАРТИНКА ОТСУТСТВУЕТ');

define('TEXT_NEW_INTRO', 'Пожалуйста, внесите требуемую информацию для нового производителя');
define('TEXT_EDIT_INTRO', 'Пожалуйста, внесите необходимые изменения');

define('TEXT_MANUFACTURERS_NAME', 'Название Производителя:');
define('TEXT_MANUFACTURERS_IMAGE', 'Картинка Производителя:');
define('TEXT_MANUFACTURERS_URL', 'URL Производителя:');

define('TEXT_DELETE_INTRO', 'Вы действительно хотите удалить этого производителя?'); 
define('TEXT_DELETE_IMAGE', 'Удалить фото производителя?');
define('TEXT_DELETE_PRODUCTS', 'Удалить товары этого производителя? (включая отзывы, специальные предложения и предстоящие поступления)');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>ПРЕДУПРЕЖДЕНИЕ:</b> %s наименований товара связаны с данным производителем!');  

define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Ошибка: Неверные права доступа директории. Пожалуйста, установите права доступа правильно в: %s');
define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Ошибка: Директория не существует: %s');
?>
