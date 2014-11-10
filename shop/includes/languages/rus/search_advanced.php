<?php
/* ----------------------------------------------------------------------
   $Id: search_advanced.php,v 1.3 2007/06/12 17:03:33 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2005 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search.php,v 1.18 2003/02/16 00:42:02 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Расширенный поиск';
$aLang['heading_title'] = 'Внесите критерии поиска';

$aLang['heading_search_criteria'] = 'Искомые слова';

$aLang['text_search_in_description'] = 'Искать в описаниях';
$aLang['entry_categories'] = 'Категории:';
$aLang['entry_include_subcategories'] = 'Искать в подкатегориях';
$aLang['entry_manufacturers'] = 'Производитель:';
$aLang['entry_price_from'] = 'По цене от:';
$aLang['entry_price_to'] = 'По цене до:';
$aLang['entry_date_from'] = 'Добавлен с:';
$aLang['entry_date_to'] = 'Добавлен до:';

$aLang['text_search_help_link'] = '<u>Помощь для расширенного поиска</u> [?]';

$aLang['text_all_categories'] = 'Все категории';
$aLang['text_all_manufacturers'] = 'Все производители';

$aLang['heading_search_help'] = 'Помощь для расширенного поиска';
$aLang['text_search_help'] = 'Функция расширенного поиска делает возможным поиск в наименованиях продуктов в их описаниях в производителях и по номеру Артикула.<br /><br />Вы можете использовать логические операторы "AND" ("И") или "OR" ("ИЛИ")<br /><br />К примеру:<u>Microsoft AND Maus</u>.<br /><br />Так же есть возможность использования скобокю Пример:<br /><br /><u>Microsoft AND (Maus OR Tastatur OR "Visual Basic")</u>.<br /><br />Используя Апостроф вы соединяете многие слова в одно искомое.';
$aLang['text_close_window'] = '<u>Закрыть окно</u> [x]';

$aLang['js_at_least_one_input'] = '* Одно из следующих полей должно быть заполнено:\n    Искомые слова\n    Добавлен с\n    Добавлен до\n    По цене от\n    По цене до\n';
$aLang['js_invalid_from_date'] = '* Недопустимое Добавлен с\n';
$aLang['js_invalid_to_date'] = '* Недопустимое Добавлен до\n';
$aLang['js_to_date_less_than_from_date'] = '* Дата с должна быть больше или равна сегоднящнему дню\n';
$aLang['js_price_from_must_be_num'] = '* По цене от, должно бать числом\n';
$aLang['js_price_to_must_be_num'] = '* По цене до, должно бать числом\n';
$aLang['js_price_to_less_than_price_from'] = '* По цене до должно быть больше или равно По цене от.\n';
$aLang['js_invalid_keywords'] = '* Искрмое слово недопустимо!\n';
?>