<?php
/* ----------------------------------------------------------------------
   $Id: admin_files.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_categories.php,v 1.13 2002/08/19 01:45:58 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Редактирование элементов меню');

define('TABLE_HEADING_ACTION', 'Акция');
define('TABLE_HEADING_BOXES', 'Элементы');
define('TABLE_HEADING_FILENAME', 'Имя файлов');
define('TABLE_HEADING_GROUPS', 'Группы');
define('TABLE_HEADING_STATUS', 'Статус');

define('TEXT_COUNT_BOXES', 'Элементы: ');
define('TEXT_COUNT_FILES', 'Фаил(лы): ');

//categories access
define('TEXT_INFO_HEADING_DEFAULT_BOXES', 'Элементы: ');

define('TEXT_INFO_DEFAULT_BOXES_INTRO', 'Элемент активируется нажатием на зелёную кнопку, нажатие на красную деактивирует его.<br /><br /><b>ПРЕДУПРЕЖДЕНИЕ:</b> Если вы деактивируете элемент то с ним удаляются все в нём находящиеся файлы!');
define('TEXT_INFO_DEFAULT_BOXES_INSTALLED', ' установлено');
define('TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED', ' неустановлено');

define('STATUS_BOX_INSTALLED', 'Установлено');
define('STATUS_BOX_NOT_INSTALLED', 'Неустановлено');
define('STATUS_BOX_REMOVE', 'Удалить');
define('STATUS_BOX_INSTALL', 'Установить');

//files access
define('TEXT_INFO_HEADING_DEFAULT_FILE', 'Файл: ');
define('TEXT_INFO_HEADING_DELETE_FILE', 'Разрешение об удалении');
define('TEXT_INFO_HEADING_NEW_FILE', 'Store Файлы');

define('TEXT_INFO_DEFAULT_FILE_INTRO', 'Нажмите на кнопку <b>занести файлы</b> для занесения новогофайла в элемент: ');
define('TEXT_INFO_DELETE_FILE_INTRO', 'Удаляю <font color="red"><b>%s</b></font> из <b>%s</b> Элемента? ');
define('TEXT_INFO_NEW_FILE_INTRO', 'Проверьте <font color="red"><b>левое Меню</b></font> чтобы убедиться, что вы удалили правильные файлы.');

define('TEXT_INFO_NEW_FILE_BOX', 'Актуальный элемент: ');

?>
