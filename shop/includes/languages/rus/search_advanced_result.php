<?php
/* ----------------------------------------------------------------------
   $Id: search_advanced_result.php,v 1.3 2007/06/12 17:03:33 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search_result.php,v 1.12 2003/02/16 00:42:02 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title1'] = 'Расширенный поиск';
$aLang['navbar_title2'] = 'Продукты соответствующие поиску';
$aLang['heading_title'] = 'Результат поиска';
$aLang['table_heading_image'] = '';
$aLang['table_heading_model'] = 'Артикул №';
$aLang['table_heading_products'] = 'Наименование';
$aLang['table_heading_manufacturer'] = 'Производитель';
$aLang['table_heading_quantity'] = 'Коичество';
$aLang['table_heading_list_price'] = 'List';
$aLang['table_heading_price'] = 'Цена';
$aLang['table_heading_weight'] = 'Вес';
$aLang['table_heading_buy_now'] = 'Купить сейчас';
$aLang['table_heading_product_sort'] = 'Сорт';
$aLang['text_no_products'] = '<br /><span style="font-size:11px;">Ваш поиск - <b>' . stripslashes($_GET['keywords']) . '</b> - безрезультатен.</span><br /><br />Помощь:<ol><li>Проверьте все ли слова написаны правильно?</li><li>Попробуйте другие искомые слова.</li><li>Попробуйте задать меньше искомых слов.</li><li>Попробуйте общие искомые слова.</li></ol>';
$aLang['text_no_products2'] = '<br /><span style="font-size:11px;">Ваш поиск - <b>' . stripslashes($_GET['keywords']) . '</b> - безрезультатен.</span><br /><br />Помощь:<ol><li>Проверьте все ли слова написаны правильно?</li><li>Попробуйте другие искомые слова.</li><li>Попробуйте задать меньше искомых слов.</li></ol>';
$aLang['text_buy'] = '1 x \'';
$aLang['text_now'] = '\' заказать';
$aLang['text_replacement_suggestion'] = 'Вы можете так же использовать следующие искомые слова. ';
?>