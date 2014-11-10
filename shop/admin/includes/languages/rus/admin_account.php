<?php
/* ----------------------------------------------------------------------
   $Id: admin_account.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

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

define('HEADING_TITLE', 'Редактирование Аккаунтов');

define('TABLE_HEADING_ACCOUNT', 'Мой Аккаунт');

define('TEXT_INFO_FULLNAME', '<b> Полное Имя: </b>');
define('TEXT_INFO_FIRSTNAME', '<b>Имя: </b>');
define('TEXT_INFO_LASTNAME', '<b>Фамилия: </b>');
define('TEXT_INFO_EMAIL', '<b>Ваш Email: </b>');
define('TEXT_INFO_PASSWORD', '<b>Пассворд: </b>');
define('TEXT_INFO_PASSWORD_HIDDEN', '-Скрыто-');
define('TEXT_INFO_PASSWORD_CONFIRM', '<b>Пассворд подтверждение: </b>');
define('TEXT_INFO_CREATED', '<b>Аккаунт Создан: </b>');
define('TEXT_INFO_LOGDATE', '<b>Последнее посещение: </b>');
define('TEXT_INFO_LOGNUM', '<b>Номер Посещения: </b>');
define('TEXT_INFO_GROUP', '<b>Группа: </b>');
define('TEXT_INFO_ERROR', '<font color="red">Этот Email адрес уже занят! Попробуйте снова.</font>');
define('TEXT_INFO_MODIFIED', 'Изменено: ');

define('TEXT_INFO_HEADING_DEFAULT', 'Редактировать Аккаунт ');
define('TEXT_INFO_HEADING_CONFIRM_PASSWORD', 'Пассворд подтверждение ');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD', 'Пассворд:');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR', '<font color="red"><b>Ошибка:</b> неправильный пассворд!</font>');
define('TEXT_INFO_INTRO_DEFAULT', 'Нажмите внизу на <b>Кнопку Редактировать</b> чтобы изменить аккаунт.');
define('TEXT_INFO_INTRO_DEFAULT_FIRST_TIME', '<br /><b>ПРЕДУПРЕЖДЕНИЕ:</b><br />Привет <b>%s</b>, вы зарегестрировались первый раз. Мы рекомендуем вам изменить ваш пассворд!');
define('TEXT_INFO_INTRO_DEFAULT_FIRST', '<br /><b>ПРЕДУПРЕЖДЕНИЕ:</b><br />Привет <b>%s</b>, мы рекомендуем вам изменить ваш E-Mail(<font color="red">admin@localhost</font>) и пассворд!');
define('TEXT_INFO_INTRO_EDIT_PROCESS', 'Все поля нужны. Нажмите сохранить для передачи данных.');

define('JS_ALERT_FIRSTNAME',        '- Требуется: Имя \n');
define('JS_ALERT_LASTNAME',         '- Требуется: Фамилия \n');
define('JS_ALERT_EMAIL',            '- Требуется: Ваш Email \n');
define('JS_ALERT_PASSWORD',         '- Требуется: Пассворд \n');
define('JS_ALERT_FIRSTNAME_LENGTH', '- Кол-во знаков в Имени должно быть больше чем ');
define('JS_ALERT_LASTNAME_LENGTH',  '- Кол-во знаков в Фамилии должно быть больше чем ');
define('JS_ALERT_PASSWORD_LENGTH',  '- Кол-во знаков в Пассворде должно быть больше чем  ');
define('JS_ALERT_EMAIL_FORMAT',     '- Формат этого Email недействителен! \n');
define('JS_ALERT_EMAIL_USED',       '- Этот адрес Email уже занят! \n');
define('JS_ALERT_PASSWORD_CONFIRM', '- В поле подтверждения пассворда небыло заполнено! \n');

?>
