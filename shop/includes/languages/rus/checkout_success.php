<?php
/* ----------------------------------------------------------------------
   $Id: checkout_success.php,v 1.3 2007/06/12 17:03:32 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_success.php,v 1.11 2002/11/01 04:27:01 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = 'Оформление Заказа';

$aLang['navbar_title_2'] = 'Выполнено';

$aLang['heading_title'] = 'Ваш Заказ Оформлен!';

$aLang['text_success'] = 'Ваш заказ успешно оформлен! В ближайшее время с Вами свяжется менеджер, для уточнения информации по Вашему заказу.';

$aLang['text_notify_products'] = 'Сообщайте мне об изменениях цены на товары, отмеченные ниже:';
$aLang['text_see_orders'] = 'Вы можете просмотреть историю заказов перейдя к странице <a href="' . oos_href_link($aModules['user'], $aFilename['account'], '', 'SSL') . '">\'Мой Профиль\'</a> и далее к странице <a href="' . oos_href_link($aModules['account'], $aFilename['account_history'], '', 'SSL') . '">\'История Заказов\'</a>.';
$aLang['text_contact_store_owner'] = 'Вы можете задать любые вопросы напрямую <a href="' . oos_href_link($aModules['main'], $aFilename['contact_us']) . '">владельцу магазина</a>.';
$aLang['text_thanks_for_shopping'] = 'Спасибо за Ваш заказ!';

$aLang['table_heading_download_date'] = 'Дата окончания: ';
$aLang['table_heading_download_count'] = ' загрузок осталось.';
$aLang['heading_download'] = 'Ссылка для скачивания файла:';
$aLang['footer_download'] = 'Вы можете скачать, купленные товары в течении \'%s\'';
?>