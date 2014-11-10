<?php
/* ----------------------------------------------------------------------
   $Id: admin_members.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_members.php,v 1.13 2002/08/19 01:45:58 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if ($_GET['gID']) {
  define('HEADING_TITLE', 'Редекционные группы');
} elseif ($_GET['gPath']) {
  define('HEADING_TITLE', 'Создать группу');
} else {
  define('HEADING_TITLE', 'Участники Редактирования');
}

define('TEXT_COUNT_GROUPS', 'Группы: ');

define('TABLE_HEADING_NAME', 'Имя');
define('TABLE_HEADING_EMAIL', 'E-mail');
define('TABLE_HEADING_PASSWORD', 'Пасcворд');
define('TABLE_HEADING_CONFIRM', 'Пасcворд Подтверждение');
define('TABLE_HEADING_GROUPS', 'Уровень Группы');
define('TABLE_HEADING_CREATED', 'Аккаунт создан');
define('TABLE_HEADING_MODIFIED', 'Аккаунт создан');
define('TABLE_HEADING_LOGDATE', 'Последний вход ');
define('TABLE_HEADING_LOGNUM', 'Номер входа');
define('TABLE_HEADING_LOG_NUM', 'Номер входа');
define('TABLE_HEADING_ACTION', 'Акция');

define('TABLE_HEADING_GROUPS_NAME', 'Имя группы');
define('TABLE_HEADING_GROUPS_DEFINE', 'Выбор области и файлов');
define('TABLE_HEADING_GROUPS_GROUP', 'Уровень');
define('TABLE_HEADING_GROUPS_CATEGORIES', 'Разрешение категорий');


define('TEXT_INFO_HEADING_DEFAULT', 'Участники Редактирования');
define('TEXT_INFO_HEADING_DELETE', 'Разрешение для удаления');
define('TEXT_INFO_HEADING_EDIT', 'Обработка категории / ');
define('TEXT_INFO_HEADING_NEW', ' Новый Участник Редактирования');

define('TEXT_INFO_DEFAULT_INTRO', 'Группа соработников');
define('TEXT_INFO_DELETE_INTRO', 'Удалить?<nobr><b>%s</b></nobr> сейчас как <nobr>Участник Редактирования<br /> </nobr>');
define('TEXT_INFO_DELETE_INTRO_NOT', 'Вы неможете удалить <nobr>%s группу!</nobr>');
define('TEXT_INFO_EDIT_INTRO', 'Установите здесь права доступа: ');

define('TEXT_INFO_FULLNAME', 'Полное Имя: ');
define('TEXT_INFO_FIRSTNAME', 'Имя: ');
define('TEXT_INFO_LASTNAME', 'Фамилия: ');
define('TEXT_INFO_EMAIL', 'E-mail: ');
define('TEXT_INFO_PASSWORD', 'Пассворд: ');
define('TEXT_INFO_CONFIRM', 'Пассворд подтверждение: ');
define('TEXT_INFO_CREATED', 'Аккаунт создан: ');
define('TEXT_INFO_MODIFIED', 'Аккаунт изменён: ');
define('TEXT_INFO_LOGDATE', 'Последний вход: ');
define('TEXT_INFO_LOGNUM', 'Номер доступа: ');
define('TEXT_INFO_GROUP', 'Уровень группы: ');
define('TEXT_INFO_ERROR', '<font color="red">E-mail уже занят! Попробуйте ещё раз.</font>');

define('JS_ALERT_FIRSTNAME', '- Требуется: Имя \n');
define('JS_ALERT_LASTNAME', '- Требуется: Фамилия \n');
define('JS_ALERT_EMAIL', '- Требуется: E-mail \n');
define('JS_ALERT_EMAIL_FORMAT', '- E-mail формат недействителен \n');
define('JS_ALERT_EMAIL_USED', '- E-mail уже занят! \n');
define('JS_ALERT_LEVEL', '- Требуется: Участник группы \n');

define('ADMIN_EMAIL_SUBJECT', 'Новый Участник Редактирования');
define('ADMIN_EMAIL_TEXT', 'Привет %s,' . "\n\n" . 'Вы можете получить доступ в область редактирования со следующим пассвордом' . "\n" . 'При первом входе в область редактирования измените пожалуйста пассворд!' . "\n" . '' . "\n\n" . 'Страница: %s' . "\n" . 'Имя пользователя: %s' . "\n" . 'Пассворд: %s' . "\n\n" . 'Спасибо!' . "\n" . '%s' . "\n\n" . 'Неотвечайте на это сообщение оносоздано автоматически для вашей информации.'); 

define('TEXT_INFO_HEADING_DEFAULT_GROUPS', 'Группа редактирования');
define('TEXT_INFO_HEADING_DELETE_GROUPS', 'Удалить группу ');

define('TEXT_INFO_DEFAULT_GROUPS_INTRO', '<b>:</b><li><b>редактировать:</b> редактировать имя группы.</li><li><b>удалить:</b> Удалить группу.</li><li><b>Definieren:</b> Definieren Sie die Gruppenzugriffsrechte.</li>');
define('TEXT_INFO_DELETE_GROUPS_INTRO', 'Все находящиеся в ней участники будут удалены. Вы увуренны, что хотитеудалить группу?  <nobr><b>%s</b>Удалить?</nobr>');
define('TEXT_INFO_DELETE_GROUPS_INTRO_NOT', 'Вы неможете удалить эту группу!');
define('TEXT_INFO_GROUPS_INTRO', 'Назовите разовое имя группы. Нажмите дальше.');

define('TEXT_INFO_HEADING_GROUPS', 'Новая группа');
define('TEXT_INFO_HEADING_EDIT_GROUP', 'Изменить имя группы');
define('TEXT_INFO_EDIT_GROUP_INTRO', 'Задайте новое имя группы.');
define('TEXT_INFO_GROUPS_NAME', ' <b>Имя группы:</b><br />Назовите разовое имя группы. Нажмите дальше.<br />');
define('TEXT_INFO_GROUPS_NAME_FALSE', '<font color="red"><b>ОШИБКА:</b> Имя группы должно состоять минимум из 5 букв!</font>');
define('TEXT_INFO_GROUPS_NAME_USED', '<font color="red"><b>ОШИБКА:</b> Имя группы уже используется!</font>');
define('TEXT_INFO_GROUPS_LEVEL', 'Уровень группы: ');
define('TEXT_INFO_GROUPS_BOXES', '<b>Доступ в область:</b><br />Определить доступ к выбраным областям.');
define('TEXT_INFO_GROUPS_BOXES_INCLUDE', 'Содержит сохранённые файла в: ');

define('TEXT_INFO_HEADING_DEFINE', 'Определить группу');
if ($_GET['gPath'] == 1) {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br />Вы неможете определить доступ для этой группы.<br /><br />');
} else {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br />Измените права доступа для этой группы и в ней находящихся файлов. При помощи выбора областей. И нажмите <b>сохранить</b> для сохранения изменений.<br /><br />');
}
?>