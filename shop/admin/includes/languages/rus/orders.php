<?php
/* ----------------------------------------------------------------------
   $Id: orders.php,v 1.1 2007/06/13 17:03:54 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders.php,v 1.24 2003/02/09 13:15:22 thomasamoulton 
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

define('HEADING_TITLE', 'Список заказов');
define('HEADING_TITLE_SEARCH', 'Поиск по ID заказа');
define('HEADING_TITLE_STATUS', 'Состояние:');

define('TABLE_HEADING_COMMENTS', 'Комментарий');
define('TABLE_HEADING_CUSTOMERS', 'Клиенты');
define('TABLE_HEADING_ORDER_TOTAL', 'Сумма заказа');
define('TABLE_HEADING_DATE_PURCHASED', 'Дата покупки');
define('TABLE_HEADING_ACTION', 'Действие');
define('TABLE_HEADING_QUANTITY', 'Количество.');
define('TABLE_HEADING_PRODUCTS_SERIAL_NUMBER', 'Serial Number');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Модель');
define('TABLE_HEADING_PRODUCTS', 'Товары');
define('TABLE_HEADING_TAX', 'Налог');
define('TABLE_HEADING_TOTAL', 'Всего');
define('TABLE_HEADING_STATUS', 'Сеатус');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Цена (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Цена (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Общая (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Общая (inc)');

define('TABLE_HEADING_STATUS', 'Состояние');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Клиент извещен');
define('TABLE_HEADING_DATE_ADDED', 'Дата добавления');

define('ENTRY_CUSTOMER', 'Клиент:'); 
define('ENTRY_SOLD_TO', 'Продано:');
define('ENTRY_STREET_ADDRESS', 'Адрес:');
define('ENTRY_SUBURB', 'Область:');
define('ENTRY_CITY', 'Город:');
define('ENTRY_POST_CODE', 'Почтовый индекс:');
define('ENTRY_STATE', 'Регион:');
define('ENTRY_COUNTRY', 'Страна:');
define('ENTRY_TELEPHONE', 'Телефон:');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail адрес:');
define('ENTRY_DELIVERY_TO', 'Доставить в :'); 
define('ENTRY_SHIP_TO', 'Доставка в:');
define('ENTRY_SHIPPING_ADDRESS', 'Адрес доставки:');
define('ENTRY_BILLING_ADDRESS', 'Адрес плательщика:');
define('ENTRY_ORDER_NUMBER', 'Order #');
define('ENTRY_ORDER_DATE', 'Order Date & Time');
define('ENTRY_CAMPAIGNS', 'How you came to us?');
define('ENTRY_PAYMENT_METHOD', 'Метод оплаты:');
define('ENTRY_CREDIT_CARD_TYPE', 'Тип кредитной карты:');
define('ENTRY_CREDIT_CARD_OWNER', 'Владелец кредитной карты:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Номер кредитной карты:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Срок окончания действия кредитной карты:');
define('ENTRY_SUB_TOTAL', 'Итого:');
define('ENTRY_TAX', 'Налог:');
define('ENTRY_SHIPPING', 'Доставка:');
define('ENTRY_TOTAL', 'Всего:');
define('ENTRY_DATE_PURCHASED', 'Дата покупки:');
define('ENTRY_STATUS', 'Состояние:');
define('ENTRY_DATE_LAST_UPDATED', 'Последнее изменение:');
define('ENTRY_NOTIFY_CUSTOMER', 'Уведомление клиета:'); 
define('ENTRY_NOTIFY_COMMENTS', 'Дополнительный комментарий:');
define('ENTRY_PRINTABLE', 'Распечатать Инвойс');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Удалить заказ');
define('TEXT_INFO_DELETE_INTRO', 'Вы действительно хотите удалить этот заказ?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Количество продукта для дополнения');
define('TEXT_DATE_ORDER_CREATED', 'Дата создания:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Последние изменения:');
define('TEXT_INFO_PAYMENT_METHOD', 'Метод оплаты:');

define('TEXT_ALL_ORDERS', 'Все заказы');
define('TEXT_NO_ORDER_HISTORY', 'История заказа отсутствует');

define('ERROR_ORDER_DOES_NOT_EXIST', 'Ошибка: Такого заказа у нас не существует.');
define('SUCCESS_ORDER_UPDATED', 'Заказ был успешно скорректирован.');
define('WARNING_ORDER_NOT_UPDATED', 'Предупреждение: Ничего не измененно. Заказ не был скорректирован.');

define('TEXT_BANK', 'Bankeinzug');
define('TEXT_BANK_OWNER', 'Kontoinhaber:');
define('TEXT_BANK_NUMBER', 'Kontonummer:');
define('TEXT_BANK_BLZ', 'BLZ:');
define('TEXT_BANK_NAME', 'Bank:');
define('TEXT_BANK_FAX', 'Einzugserm&auml;chtigung wird per Fax best&auml;tigt');
define('TEXT_BANK_STATUS', 'Pr&uuml;fstatus:');
define('TEXT_BANK_PRZ', 'Pr&uuml;fverfahren:');

define('TEXT_BANK_ERROR_1', 'Kontonummer stimmt nicht mit BLZ &uuml;berein!');
define('TEXT_BANK_ERROR_2', 'F&uuml;r diese Kontonummer ist kein Pr&uuml;fverfahren definiert!');
define('TEXT_BANK_ERROR_3', 'Kontonummer nicht pr&uuml;fbar! Pr&uuml;fverfahren nicht implementiert');
define('TEXT_BANK_ERROR_4', 'Kontonummer technisch nicht pr&uuml;fbar!');
define('TEXT_BANK_ERROR_5', 'Bankleitzahl nicht gefunden!');
define('TEXT_BANK_ERROR_8', 'Keine Bankleitzahl angegeben!');
define('TEXT_BANK_ERROR_9', 'Keine Kontonummer angegeben!');
define('TEXT_BANK_ERRORCODE', 'Fehlercode:');
?>