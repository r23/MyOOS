<?php
/* ----------------------------------------------------------------------
   $Id: currencies.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: currencies.php,v 1.10 2002/01/12 17:20:32 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('HEADING_TITLE', 'Валюты');

define('TABLE_HEADING_CURRENCY_NAME', 'Валюта');
define('TABLE_HEADING_CURRENCY_CODES', 'Код');
define('TABLE_HEADING_CURRENCY_VALUE', 'Величина');
define('TABLE_HEADING_ACTION', 'Действие');

define('TEXT_INFO_EDIT_INTRO', 'Сделайте пожалуйста любые необходимые изменения');
define('TEXT_INFO_CURRENCY_TITLE', 'Название:');
define('TEXT_INFO_CURRENCY_CODE', 'Код:');
define('TEXT_INFO_CURRENCY_SYMBOL_LEFT', 'Символ слева:');
define('TEXT_INFO_CURRENCY_SYMBOL_RIGHT', 'Символ справа:');
define('TEXT_INFO_CURRENCY_DECIMAL_POINT', 'Десятичный знак:');
define('TEXT_INFO_CURRENCY_THOUSANDS_POINT', 'Разделитель тысяч:');
define('TEXT_INFO_CURRENCY_DECIMAL_PLACES', 'Десятичные порядки:');
define('TEXT_INFO_CURRENCY_LAST_UPDATED', 'Последний раз скорректировано:');
define('TEXT_INFO_CURRENCY_VALUE', 'Величина:');
define('TEXT_INFO_CURRENCY_EXAMPLE', 'Пример:');
define('TEXT_INFO_INSERT_INTRO', 'Пожалуйста войдите в новую валюту со своими связанными данными');
define('TEXT_INFO_DELETE_INTRO', 'Вы действительно хотите удалить эту валюту?');
define('TEXT_INFO_HEADING_NEW_CURRENCY', 'Новая валюта'); 
define('TEXT_INFO_HEADING_EDIT_CURRENCY', 'Редактировать');
define('TEXT_INFO_HEADING_DELETE_CURRENCY', 'Удалить');
define('TEXT_INFO_SET_AS_DEFAULT', TEXT_SET_DEFAULT . ' (Эту валюту нужно корректировать вручную)');

define('ERROR_REMOVE_DEFAULT_CURRENCY', 'Ошибка: Валюта по умолчанию не может быть удалена. Определите новую валюту по умолчанию и попробуйте снова.');
?>
