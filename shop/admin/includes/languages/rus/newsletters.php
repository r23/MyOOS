<?php
/* ----------------------------------------------------------------------
   $Id: newsletters.php,v 1.1 2007/06/13 17:03:54 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: newsletters.php,v 1.5 2002/03/08 22:10:08 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('HEADING_TITLE', 'Менеджер рассылки новостей');

define('TABLE_HEADING_NEWSLETTERS', 'Новости');
define('TABLE_HEADING_SIZE', 'Размер');
define('TABLE_HEADING_MODULE', 'Модуль');
define('TABLE_HEADING_SENT', 'Послано');
define('TABLE_HEADING_STATUS', 'Статус');
define('TABLE_HEADING_ACTION', 'Действие');

define('TEXT_NEWSLETTER_MODULE', 'Модуль:');
define('TEXT_NEWSLETTER_TITLE', 'Название:');
define('TEXT_NEWSLETTER_CONTENT', 'Содержание:');

define('TEXT_NEWSLETTER_DATE_ADDED', 'Дата добавления:');
define('TEXT_NEWSLETTER_DATE_SENT', 'Дата отправки:');

define('TEXT_INFO_DELETE_INTRO', '- Вы действительно хотите удалить это информационное письмо?');

define('TEXT_PLEASE_WAIT', 'Пожалуйста ожидайте .. отправляются emails ..<br><br>Пожалуйста не прерывайте этот процесс!');
define('TEXT_FINISHED_SENDING_EMAILS', 'Завершение отправки e-mails!');

define('ERROR_NEWSLETTER_TITLE', 'Error: Название обязательно');
define('ERROR_NEWSLETTER_MODULE', 'Error: Модуль обязателен');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', 'Error: Пожалуйста заблокируйте информационное письмо перед удалением этого.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', 'Error: Пожалуйста заблокируйте информационное письмо перед редактированием этого.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', 'Error: Пожалуйста заблокируйте информационное письмо перед отправкой этого.');
?>