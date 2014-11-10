<?php
/* ----------------------------------------------------------------------
   $Id: export_excel.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: backup.php,v 1.16 2002/03/16 21:30:02 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Резервное Копирование');

define('TABLE_HEADING_TITLE', 'Имя');
define('TABLE_HEADING_FILE_DATE', 'Дата');
define('TABLE_HEADING_FILE_SIZE', 'Размер');
define('TABLE_HEADING_ACTION', 'Действие');

define('TEXT_INFO_HEADING_NEW_BACKUP', 'Сохранить Заново');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Восстановить Локально');
define('TEXT_INFO_NEW_BACKUP', 'Не прерывайте процесс, который может занять пару минут.');
define('TEXT_INFO_UNPACK', '<br><br>(после распаковки файла из архива)');
define('TEXT_INFO_DATE', 'Дата:');
define('TEXT_INFO_SIZE', 'Размер:');
define('TEXT_INFO_COMPRESSION', 'Сжатие:');
define('TEXT_INFO_USE_GZIP', 'Использовать GZIP');
define('TEXT_INFO_USE_ZIP', 'Использовать ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'Без сжатия (Просто SQL)');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Только загрузка (Не загружайте на удаленный сервер)');
define('TEXT_INFO_BEST_THROUGH_HTTPS', 'Наилучший вариант - связь через HTTPS');
define('TEXT_DELETE_INTRO', 'Вы действительно хотите удалить эту копию?');
define('TEXT_NO_EXTENSION', 'Нет');
define('TEXT_EXPORT_DIRECTORY', 'Резервная Директория:');
define('TEXT_FORGET', '(<u>забыть</u>)');

define('ERROR_EXPORT_DIRECTORY_DOES_NOT_EXIST', 'Ошибка: Директория для резервного копирования не существует.');
define('ERROR_EXPORT_DIRECTORY_NOT_WRITEABLE', 'Ошибка: Директория для резервного копирования защищена от записи, установите верные права доступа.');  
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Ошибка: Ссылка для загрузки не приемлема.');

define('SUCCESS_DATABASE_SAVED', 'Выполнено: База данных сохранена.');
define('SUCCESS_DATABASE_RESTORED', 'Выполнено: База данных восстановлена.');
define('SUCCESS_EXPORT_DELETED', 'Выполнено: Копия удалена.');
?>
