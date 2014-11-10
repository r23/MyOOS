<?php
/* ----------------------------------------------------------------------
   $Id: banner_manager.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banner_manager.php,v 1.17 2002/08/18 18:54:47 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Менеджер Баннеров');

define('TABLE_HEADING_BANNERS', 'Баннеры');
define('TABLE_HEADING_GROUPS', 'Группы');
define('TABLE_HEADING_STATISTICS', 'Показы / Клики');
define('TABLE_HEADING_STATUS', 'Статус');
define('TABLE_HEADING_ACTION', 'Действие');

define('TEXT_BANNERS_TITLE', 'Название Баннера:');
define('TEXT_BANNERS_URL', 'URL Баннера:');
define('TEXT_BANNERS_GROUP', 'Группа Баннера:');
define('TEXT_BANNERS_NEW_GROUP', ', выберите группу или создайте новую ниже');
define('TEXT_BANNERS_IMAGE', 'Баннер:');
define('TEXT_BANNERS_IMAGE_LOCAL', ', или введите локальный файл ниже');
define('TEXT_BANNERS_IMAGE_TARGET', 'Баннер (Сохранить как):');
define('TEXT_BANNERS_HTML_TEXT', 'HTML Код:');
define('TEXT_BANNERS_EXPIRES_ON', 'Должен показываться до:');
define('TEXT_BANNERS_OR_AT', ', или лимит');
define('TEXT_BANNERS_IMPRESSIONS', 'показов/кликов.');
define('TEXT_BANNERS_SCHEDULED_AT', 'Должен показываться с:');
define('TEXT_BANNERS_BANNER_NOTE', '<b>Примечание:</b><ul><li>Используйте для баннера только изображение или HTML Код, но не одновременно оба способа.</li><li>HTML Код имеет приоритет над изображением</li></ul>');
define('TEXT_BANNERS_INSERT_NOTE', '<b>Информация о загрузке баннера:</b><ul><li>Директория, в которую загружаются баннеры должна иметь соответствующие права доступа!</li><li>Не заполняйте область \'Сохранить Как\' если Вы не загружаете изображение на сервер (т.е., Вы используете баннер с локального диска).</li><li>Директория, указанная в поле \'Сохранить Как\' должна быть создана на сервере и должна заканчиваться косой чертой (например, banners/).</li></ul>');
define('TEXT_BANNERS_EXPIRCY_NOTE', '<b>Информация о показе баннера:</b><ul><li>Только одно из полей "Должен показываться до" или "Должен показываться с" должно быть заполнено, т.е. 2 поля одновременно заполнены быть не могут</li><li>Если баннер должен показываться постоянно, просто оставьте эти поля пустыми</li></ul>');
define('TEXT_BANNERS_SCHEDULE_NOTE', '<b>Информация о поле "Должен показываться с":</b><ul><li>Если Вы установили дату в этом поле, то баннер будет показываться с той даты, которую Вы указали.</li><li>Все баннеры, у которых заполнено поле "Должен показываться с" по умолчанию выключены, после того как наступит указанная дата, баннер будет активен.</li></ul>');

define('TEXT_BANNERS_DATE_ADDED', 'Дата добавления:');
define('TEXT_BANNERS_SCHEDULED_AT_DATE', 'Будет показан с: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_DATE', 'Показывается до: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_IMPRESSIONS', 'Осталось: <b>%s</b> показов');
define('TEXT_BANNERS_STATUS_CHANGE', 'Изменение Статуса: %s');

define('TEXT_BANNERS_DATA', 'Д<br>А<br>Т<br>А');
define('TEXT_BANNERS_LAST_3_DAYS', 'Последние 3 дня');
define('TEXT_BANNERS_BANNER_VIEWS', 'Показы');
define('TEXT_BANNERS_BANNER_CLICKS', 'Клики');

define('TEXT_INFO_DELETE_INTRO', 'Вы действительно хотите удалить этот баннер?');
define('TEXT_INFO_DELETE_IMAGE', 'Удалить баннер');

define('SUCCESS_BANNER_INSERTED', 'Выполнено: Баннер добавлен.');
define('SUCCESS_BANNER_UPDATED', 'Выполнено: Баннер изменён.');
define('SUCCESS_BANNER_REMOVED', 'Выполнено: Баннер удалён.');
define('SUCCESS_BANNER_STATUS_UPDATED', 'Выполнено: Статус баннера изменён.');

define('ERROR_BANNER_TITLE_REQUIRED', 'Ошибка: Введите название баннера.');
define('ERROR_BANNER_GROUP_REQUIRED', 'Ошибка: Введите группу баннера.');
define('ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Ошибка: Указанная директория отсутствует: %s');
define('ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Ошибка: Директория имеет неверные права доступа: %s');
define('ERROR_IMAGE_DOES_NOT_EXIST', 'Ошибка: Баннер отсутствует.');
define('ERROR_IMAGE_IS_NOT_WRITEABLE', 'Ошибка: Баннер не может быть удалён.');
define('ERROR_UNKNOWN_STATUS_FLAG', 'Ошибка: Неизвестный статус.');

define('ERROR_GRAPHS_DIRECTORY_DOES_NOT_EXIST', 'Ошибка:  Директории Graphs несуществует. Пожалуйста создайте директорию \'graphs\' внутри \'images\'.');
define('ERROR_GRAPHS_DIRECTORY_NOT_WRITEABLE', 'Ошибка: Директория Graphs недоступна для записи.');
?>
