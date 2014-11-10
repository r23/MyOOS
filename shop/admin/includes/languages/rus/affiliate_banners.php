<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_banners.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_banners.php,v 1.3 2003/02/16 23:44:24 harley_vb  
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('HEADING_TITLE', 'Партнерская программа, управление баннерами.');

define('TABLE_HEADING_BANNERS', 'Баннер');
define('TABLE_HEADING_GROUPS', 'Группа');
define('TABLE_HEADING_ACTION', 'Акция');
define('TABLE_HEADING_STATISTICS', 'Показывать / Нажатий');
define('TABLE_HEADING_PRODUCT_ID', 'Продукт ID');

define('TEXT_BANNERS_TITLE', 'Название баннера:');
define('TEXT_BANNERS_GROUP', 'Группа баннеров:');
define('TEXT_BANNERS_NEW_GROUP', ', или задайте внизу новую группу баннеров');
define('TEXT_BANNERS_IMAGE', 'Картинка (Файл):');
define('TEXT_BANNERS_IMAGE_LOCAL', ', или задайте внизу локальный файл на вфшем сервере');
define('TEXT_BANNERS_IMAGE_TARGET', 'Директория картинки (сохранить в):');
define('TEXT_BANNERS_HTML_TEXT', 'HTML Текст');
define('TEXT_AFFILIATE_BANNERS_NOTE', '<b>Banner Bemerkung:</b><ul><li>Вы можете использовать картинку или HTML код баннера, и то и другое одновременно невозможно.</li><li>Если вы установите и то и другое, то будет виден только баннер с HTML кодом.</li></ul>');

define('TEXT_BANNERS_LINKED_PRODUCT','Продукт ID');
define('TEXT_BANNERS_LINKED_PRODUCT_NOTE','Если вы хотите сделать ссылку баннера на продукт, тогда задайте продукт ID. Если вы хотите сделать ссылку баннера на первую страницу, задайте "0" ');

define('TEXT_BANNERS_DATE_ADDED', 'добавлен:');
define('TEXT_BANNERS_STATUS_CHANGE', 'Статус изменён: %s');

define('TEXT_INFO_DELETE_INTRO', 'Вы уверенны, что хотите удалить этот баннер?');
define('TEXT_INFO_DELETE_IMAGE', 'Удалить картинку баннера');

define('SUCCESS_BANNER_INSERTED', 'Баннер был удачно вставлен.');
define('SUCCESS_BANNER_UPDATED', 'Баннер был удачно актуализирован.');
define('SUCCESS_BANNER_REMOVED', 'Баннер был удачно удалён.');

define('ERROR_BANNER_TITLE_REQUIRED', 'Ошибка: Нужно название баннера.');
define('ERROR_BANNER_GROUP_REQUIRED', 'Ошибка: Нужна группа баннера.');
define('ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Ошибка: Директория не существует: %s.');
define('ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Ошибка: Директория недоступна для записи: %s');
define('ERROR_IMAGE_DOES_NOT_EXIST', 'Ошибка: Нет картинки.');
define('ERROR_IMAGE_IS_NOT_WRITEABLE', 'Ошибка: Немогу удалить картинку.');
?>