<?php
/* ----------------------------------------------------------------------
   $Id: rus.php,v 1.3 2007/06/13 17:20:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: english.php,v 1.101 2002/11/11 13:30:16 project3000 
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

 /**
  * look in your $PATH_LOCALE/locale directory for available locales..
  * on RedHat try 'ru_RU'
  * on FreeBSD try 'ru_RU.CP1251'
  * on Windows try 'ru', or 'Russian'
  */
  @setlocale(LC_TIME, 'ru_RU.CP1251');
  define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
  define('DATE_FORMAT', 'd/m/Y'); // this is used for date()
  define('PHP_DATE_TIME_FORMAT', 'd/m/Y H:i:s'); // this is used for date()
  define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');


 /**
  * Return date in raw format
  * $date should be in format mm/dd/yyyy
  * raw date is in format YYYYMMDD, or DDMMYYYY
  *
  * @param $date
  * @param $reverse
  * @return string
  */
  function oos_date_raw($date, $reverse = false) {
    if ($reverse) {
      return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
    } else {
      return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
    }
  }

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="ru"');

// charset for web pages and emails
define('CHARSET', 'windows-1251');

// page title
define('TITLE', 'OSIS Online Shop');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Администрирование');
define('HEADER_TITLE_SUPPORT_SITE', 'Сайт Поддержки');
define('HEADER_TITLE_ONLINE_CATALOG', 'Каталог');
define('HEADER_TITLE_ADMINISTRATION', 'Администрация');
define('HEADER_TITLE_LOGOFF', 'Выход');

$aLang['header_title_top'] = 'Администрирование';
$aLang['header_title_support_site'] = 'Сайт Поддержки';
$aLang['header_title_online_catalog'] = 'Каталог';
$aLang['header_title_administration'] = 'Администрация';
$aLang['header_title_account'] = 'My Account';
$aLang['header_title_logoff'] = 'Выход';

// text for gender
define('MALE', 'Мужчина');
define('FEMALE', 'Женщина');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Настройки');
define('BOX_CONFIGURATION_MYSTORE', 'Магазин');
define('BOX_CONFIGURATION_LOGGING', 'Логфайлы');
define('BOX_CONFIGURATION_CACHE', 'Кэш');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Модули');
define('BOX_MODULES_PAYMENT', 'Оплата');
define('BOX_MODULES_SHIPPING', 'Доставка');
define('BOX_MODULES_ORDER_TOTAL', 'Заказ итого');

// plugins box text in includes/boxes/plugins.php
define('BOX_HEADING_PLUGINS', 'Plugins');
define('BOX_PLUGINS_EVENT', 'Event Plugins');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Каталог');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Категории/Товары');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Опции товаров');
define('BOX_CATALOG_PRODUCTS_STATUS', 'Статусы товаров');
define('BOX_CATALOG_PRODUCTS_UNITS', 'Packing unit');
define('BOX_CATALOG_MANUFACTURERS', 'Производители');
define('BOX_CATALOG_REVIEWS', 'Отзывы');
define('BOX_CATALOG_SPECIALS', 'Скидки');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Ожидаемые товары'); 
define('BOX_CATALOG_QADD_PRODUCT', 'Добавить Товар');
define('BOX_CATALOG_PRODUCTS_FEATURED', 'Закладки');
define('BOX_CATALOG_EASYPOPULATE', 'EasyPopulate');
define('BOX_CATALOG_EXPORT_EXCEL', 'Export Excel Product');
define('BOX_CATALOG_IMPORT_EXCEL', 'Update Excel Price');
define('BOX_CATALOG_XSELL_PRODUCTS', 'Cross Sell Products');
define('BOX_CATALOG_UP_SELL_PRODUCTS', 'UP Sell Products');
define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Quick Stock Update');

// categories box text in includes/boxes/content.php
define('BOX_HEADING_CONTENT', 'Content Manager');
define('BOX_CONTENT_BLOCK', 'Block Manager');
define('BOX_CONTENT_NEWS', 'Новости');
define('BOX_CONTENT_INFORMATION', 'Информация');
define('BOX_CONTENT_PAGE_TYPE', 'Conten Page Type');

// categories box text in includes/boxes/newsfeed.php
define('BOX_HEADING_NEWSFEED', 'News Feed');
define('BOX_NEWSFEED_MANAGER', 'News Feed Manager');
define('BOX_NEWSFEED_CATEGORIES', 'News Feed Categories');


// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Клиенты');
define('BOX_CUSTOMERS_CUSTOMERS', 'Клиенты');
define('BOX_CUSTOMERS_ORDERS', 'Заказы');
define('BOX_CAMPAIGNS', 'Campaigns');
define('BOX_ADMIN_LOGIN', 'Администрирование');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Места / Налоги');
define('BOX_TAXES_COUNTRIES', 'Страны');
define('BOX_TAXES_ZONES', 'Регионы');
define('BOX_TAXES_GEO_ZONES', 'Налоговые зоны');
define('BOX_TAXES_TAX_CLASSES', 'Типы налогов');
define('BOX_TAXES_TAX_RATES', 'Ставки налогов');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Отчёты');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Просмотренные товары');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Заказанные товары');
define('BOX_REPORTS_ORDERS_TOTAL', 'Лучшие клиенты');
define('BOX_REPORTS_STOCK_LEVEL', 'Low Stock Report');
define('BOX_REPORTS_SALES_REPORT2', 'SalesReport2');
define('BOX_REPORTS_KEYWORDS', 'Поисковые Запросы');
define('BOX_REPORTS_REFERER' , 'Переходы');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Инструменты');
define('BOX_TOOLS_BACKUP', 'Резервное копирование БД');
define('BOX_TOOLS_BANNER_MANAGER', 'Менеджер баннеров');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Языковые файлы');
define('BOX_TOOLS_FILE_MANAGER', 'Файловый менеджер');
define('BOX_EXPORT_PREISSUCHMASCHINE', 'Export preissuchmaschine.de');
define('BOX_TOOLS_MAIL', 'Послать Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Менеджер почтовых рассылок');
define('BOX_TOOLS_SERVER_INFO', 'Информация о сервере');
define('BOX_TOOLS_WHOS_ONLINE', 'Кто в онлайне');
define('BOX_TOOLS_KEYWORD_SHOW', 'Keyword Show');
define('BOX_HEADING_ADMINISTRATORS', 'Администраторы');
define('BOX_ADMINISTRATORS_SETUP', 'Set Up');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Локализация');
define('BOX_LOCALIZATION_CURRENCIES', 'Валюты');
define('BOX_LOCALIZATION_LANGUAGES', 'Языки');
define('BOX_LOCALIZATION_CUSTOMERS_STATUS', 'Статусы Клиентов');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Статусы Заказов');

// links box text in includes/boxes/links.php
define('BOX_HEADING_LINKS', 'Менеджер Ссылок');
define('BOX_CONTENT_LINKS', 'Ссылки');
define('BOX_CONTENT_LINK_CATEGORIES', 'Категории Ссылок');
define('BOX_CONTENT_LINKS_CONTACT', 'Links Contact');

// export
define('BOX_HEADING_EXPORT', 'Экспорт');
define('BOX_EXPORT_PREISSUCHMASCHINE', 'Export preissuchmaschine.de');
define('BOX_EXPORT_GOOGLEBASE', 'Googlebase');

//rss
define('BOX_HEADING_RSS', 'RSS');
define('BOX_RSS_CONF', 'RSS');

//information
define('BOX_HEADING_INFORMATION', 'Информация');
define('BOX_INFORMATION', 'Информация');

// javascript messages
define('JS_ERROR', 'При заполнении формы Вы допустили ошибки!\nСделайте, пожалуйста, следующие исправления:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* Новый атрибут товара дожен иметь цену\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* Новый атрибут товара дожен иметь ценовой префикс\n');

define('JS_PRODUCTS_NAME', '* Для нового товара должно быть указано наименование\n');
define('JS_PRODUCTS_DESCRIPTION', '* Для нового товара должно быть указано описание\n');
define('JS_PRODUCTS_PRICE', '* Для нового товара должна быть указана цена\n');
define('JS_PRODUCTS_WEIGHT', '* Для нового товара должен быть указан вес\n');
define('JS_PRODUCTS_QUANTITY', '* Для нового товара должно быть указано количество\n');
define('JS_PRODUCTS_MODEL', '* Для нового товара должен быть указан код товара\n');
define('JS_PRODUCTS_IMAGE', '* Для нового товара должна быть картинка\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* Для этого товара должна быть установлена новая цена\n');

define('JS_GENDER', '* Поле \'Пол\' должно быть выбрано.\n');
define('JS_FIRST_NAME', '* Поле \'Имя\' должно содержать не менее ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' символов.\n');
define('JS_LAST_NAME', '* Поле \'Фамилия\' должно содержать не менее ' . ENTRY_LAST_NAME_MIN_LENGTH . ' символов.\n');
define('JS_DOB', '* Поле \'День рождения\' должно иметь формат: xx/xx/xxxx (день/месяц/год).\n');
define('JS_EMAIL_ADDRESS', '* Поле \'E-Mail адрес\' должно содержать не менее ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' символов.\n');
define('JS_ADDRESS', '* Поле \'Адрес\' должно содержать не менее ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' символов.\n');
define('JS_POST_CODE', '* Поле \'Индекс\' должно содержать не менее ' . ENTRY_POSTCODE_MIN_LENGTH . ' символов.\n');
define('JS_CITY', '* Поле \'Город\' должно содержать не менее ' . ENTRY_CITY_MIN_LENGTH . ' символов.\n');
define('JS_STATE', '* Поле \'Регион\' должно быть выбрано.\n');
define('JS_STATE_SELECT', '-- Выберите выше --');
define('JS_ZONE', '* Поле \'Регион\' должно соответствовать выбраной стране.');
define('JS_COUNTRY', '* Поле \'Страна\' дожно быть заполнено.\n');
define('JS_TELEPHONE', '* Поле \'Телефон\' должно содержать не менее ' . ENTRY_TELEPHONE_MIN_LENGTH . ' символов.\n');
define('JS_PASSWORD', '* Поля \'Пароль\' и \'Подтверждение\' должны совпадать и содержать не менее ' . ENTRY_PASSWORD_MIN_LENGTH . ' символов.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Заказ номер %s не найден!');

define('CATEGORY_PERSONAL', 'Персональный');
define('CATEGORY_ADDRESS', 'Адрес');
define('CATEGORY_CONTACT', 'Для контакта');
define('CATEGORY_COMPANY', 'Компания');
define('CATEGORY_PASSWORD', 'Пароль');
define('CATEGORY_OPTIONS', 'Рассылка');
define('ENTRY_GENDER', 'Пол:');
define('ENTRY_FIRST_NAME', 'Имя:');
define('ENTRY_LAST_NAME', 'Фамилия:');
define('ENTRY_NUMBER', 'Customer Number:');
define('ENTRY_DATE_OF_BIRTH', 'Дата рождения:');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Адрес:');
define('ENTRY_COMPANY', 'Название компании:');
define('ENTRY_OWNER', 'Owner name:');
define('ENTRY_VAT_ID', 'VAT ID:');
define('ENTRY_STREET_ADDRESS', 'Адрес:');
define('ENTRY_SUBURB', 'Район:');
define('ENTRY_POST_CODE', 'Индекс:');
define('ENTRY_CITY', 'Город:');
define('ENTRY_STATE', 'Регион:');
define('ENTRY_COUNTRY', 'Страна:');
define('ENTRY_TELEPHONE_NUMBER', 'Телефон:');
define('ENTRY_FAX_NUMBER', 'Факс:');
define('ENTRY_NEWSLETTER', 'Получать рассылку:');
define('ENTRY_NEWSLETTER_YES', 'Подписан');
define('ENTRY_NEWSLETTER_NO', 'Не подписан');
define('ENTRY_PASSWORD', 'Пароль:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Подтверждение Пароля:');
define('PASSWORD_HIDDEN', '--СКРЫТО--');

// images
define('IMAGE_ANI_SEND_EMAIL', 'Отправить E-Mail');
define('IMAGE_BACK', 'Назад');
define('IMAGE_BACKUP', 'Рез. копия');
define('IMAGE_CANCEL', 'Отменить');
define('IMAGE_CONFIRM', 'Подтвердить');
define('IMAGE_COPY', 'Копировать');
define('IMAGE_COPY_TO', 'Копировать в');
define('IMAGE_DETAILS', 'Подробнее');
define('IMAGE_DELETE', 'Удалить');
define('IMAGE_EDIT', 'Редактировать');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FEATURED', 'Featured');
define('IMAGE_FILE_MANAGER', 'Менеджер файлов');
define('IMAGE_ICON_STATUS_GREEN', 'Активный');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Активизировать');
define('IMAGE_ICON_STATUS_RED', 'Неактивный');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Сделать неактивным');
define('IMAGE_ICON_INFO', 'Информация');
define('IMAGE_INSERT', 'Добавить');
define('IMAGE_LOCK', 'Замок');
define('IMAGE_MOVE', 'Переместить');
define('IMAGE_NEW_BANNER', 'Новый баннер');
define('IMAGE_NEW_CATEGORY', 'Новая категория');
define('IMAGE_NEW_COUNTRY', 'Новая страна');
define('IMAGE_NEW_CURRENCY', 'Новая валюта'); 
define('IMAGE_NEW_FILE', 'Новый файл');
define('IMAGE_NEW_FOLDER', 'Новая папка');
define('IMAGE_NEW_LANGUAGE', 'Новый язык');
define('IMAGE_NEW_NEWS', 'Новая Новость');
define('IMAGE_NEW_NEWSLETTER', 'Новое письмо новостей');
define('IMAGE_NEW_PRODUCT', 'Новый товар');
define('IMAGE_NEW_TAX_CLASS', 'Новый налог'); 
define('IMAGE_NEW_TAX_RATE', 'Новая ставка налога');
define('IMAGE_NEW_TAX_ZONE', 'Новая налоговая зона');
define('IMAGE_ORDERS', 'Заказы');
define('IMAGE_ORDERS_INVOICE', 'Счёт-фактура');
define('IMAGE_ORDERS_PACKINGSLIP', 'Накладная');
define('IMAGE_ORDERS_WEBPRINTER', 'WebPrinter');
define('IMAGE_PLUGINS_INSTALL', 'Install Plugins');
define('IMAGE_PLUGINS_REMOVE', 'Remove Plugins');
define('IMAGE_PREVIEW', 'Предпросмотр');
define('IMAGE_RESTORE', 'Восстановить');
define('IMAGE_RESET', 'Сброс');
define('IMAGE_SAVE', 'Сохранить');
define('IMAGE_SEARCH', 'Искать');
define('IMAGE_SELECT', 'Выбрать');
define('IMAGE_SEND', 'Отправить');
define('IMAGE_SEND_EMAIL', 'Отправить Email');
define('IMAGE_SPECIALS', 'Скидки');
define('IMAGE_STATUS', 'Статус Клиентов');
define('IMAGE_UNLOCK', 'Разблокировать');
define('IMAGE_UPDATE', 'Обновить');
define('IMAGE_UPDATE_CURRENCIES', 'Скорректировать курсы валют');
define('IMAGE_UPLOAD', 'Загрузить');
define('IMAGE_WISHLIST', 'Пожелания');

$aLang['image_new_tax_rate'] = 'New Tax Rate';
$aLang['image_new_zone'] = 'New Zone';


define('ICON_CROSS', 'Недействительно');
define('ICON_CURRENT_FOLDER', 'Текущая директория');
define('ICON_DELETE', 'Удалить');
define('ICON_ERROR', 'Ошибка');
define('ICON_FILE', 'Файл');
define('ICON_FILE_DOWNLOAD', 'Загрузка');
define('ICON_FOLDER', 'Папка');
define('ICON_LOCKED', 'Заблокировать');
define('ICON_PREVIOUS_LEVEL', 'Предыдущий уровень');
define('ICON_PREVIEW', 'Предпросмотр');
define('ICON_STATISTICS', 'Статистика');
define('ICON_SUCCESS', 'Выполнено');
define('ICON_TICK', 'Истина');
define('ICON_UNLOCKED', 'Разблокировать');
define('ICON_WARNING', 'ВНИМАНИЕ');

// constants for use in oos_prev_next_display function
define('TEXT_RESULT_PAGE', 'Страница %s из %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> баннеров)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> стран)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> клиентов)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> валют)');
define('TEXT_DISPLAY_NUMBER_OF_HTTP_REFERERS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> HTTP Referers)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> языковых модулей)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> производителей)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> рассылок)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> заказов)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> статуса)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> позиций)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> ожидаемых товаров)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_UNITS', 'Показано <b>%d</b> to <b>%d</b> (всего<b>%d</b> packing unit)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> отызов о товарах)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> специальных предложений)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> типов налогов)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> налоговых зон)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> ставок налогов)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> зон)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> статусов клиентов)');
define('TEXT_DISPLAY_NUMBER_OF_BLOCKES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> блоков)');
define('TEXT_DISPLAY_NUMBER_OF_RSS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b>)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSFEED_CATEGORIES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> категорий)');
define('TEXT_DISPLAY_NUMBER_OF_PAGE_TYPES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> )');
define('TEXT_DISPLAY_NUMBER_OF_INFORMATION', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> информационных страниц)');


define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'по умолчанию');
define('TEXT_SET_DEFAULT', 'Установить по умолчанию');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Обязательно</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Ошибка: К настоящему времени ни одна валюта не была установлена по умолчанию. Пожалуйста, установите одну из них в: Локализация -> Валюта');
define('ERROR_USER_FOR_THIS_PAGE', 'Fehler: Sie haben f&uuml;r diesen Bereich keine Zugangsrechte.');

define('TEXT_INFO_USER_NAME', 'Пользователь:');
define('TEXT_INFO_PASSWORD', 'Пароль:');

define('TEXT_NONE', '--нет--');
define('TEXT_TOP', 'Начало');

define('ENTRY_YES','да');
define('ENTRY_NO','нет');

// reports box text in includes/boxes/affiliate.php
define('BOX_HEADING_AFFILIATE', 'Affiliates');
define('BOX_AFFILIATE_SUMMARY', 'Summary');
define('BOX_AFFILIATE', 'Affiliates');
define('BOX_AFFILIATE_PAYMENT', 'Payment');
define('BOX_AFFILIATE_BANNERS', 'Banners');
define('BOX_AFFILIATE_CONTACT', 'Contact');
define('BOX_AFFILIATE_SALES', 'Sales');
define('BOX_AFFILIATE_CLICKS', 'Clicks');

define ('BOX_HEADING_TICKET','Supporttickets');
define ('BOX_TICKET_VIEW','Tickets');
define ('BOX_TEXT_ADMIN','Admins');
define ('BOX_TEXT_DEPARTMENT','Departments');
define ('BOX_TEXT_PRIORITY','Priorities');
define ('BOX_TEXT_REPLY','Replys');
define ('BOX_TEXT_STATUS','Statuse');

define('BOX_HEADING_GV_ADMIN', 'Vouchers/Coupons');
define('BOX_GV_ADMIN_QUEUE', 'Gift Voucher Queue');
define('BOX_GV_ADMIN_MAIL', 'Mail Gift Voucher');
define('BOX_GV_ADMIN_SENT', 'Gift Vouchers sent');
define('BOX_HEADING_COUPON_ADMIN','Rabattkupons');
define('BOX_COUPON_ADMIN','Coupon Admin');

define('IMAGE_RELEASE', 'Redeem Gift Voucher');

define('_JANUARY', 'Январь');
define('_FEBRUARY', 'Февраль');
define('_MARCH', 'Март');
define('_APRIL', 'Апрель');
define('_MAY', 'Май');
define('_JUNE', 'Июнь');
define('_JULY', 'Июль');
define('_AUGUST', 'Август');
define('_SEPTEMBER', 'Сентябрь');
define('_OCTOBER', 'Октябрь');
define('_NOVEMBER', 'Ноябрь');
define('_DECEMBER', 'Декабрь');

define('TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> gift vouchers)');
define('TEXT_DISPLAY_NUMBER_OF_COUPONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> coupons)');

define('TEXT_VALID_PRODUCTS_LIST', 'Products List');
define('TEXT_VALID_PRODUCTS_ID', 'Products ID');
define('TEXT_VALID_PRODUCTS_NAME', 'Products Name');
define('TEXT_VALID_PRODUCTS_MODEL', 'Products Model');

define('TEXT_VALID_CATEGORIES_LIST', 'Categories List');
define('TEXT_VALID_CATEGORIES_ID', 'Category ID');
define('TEXT_VALID_CATEGORIES_NAME', 'Category Name');

define('HEADER_TITLE_TOP', 'Redaktion');
define('HEADER_TITLE_ADMINISTRATION', 'Redaktion');

define('HEADER_TITLE_ACCOUNT', 'My Account');
define('HEADER_TITLE_LOGOFF', 'Logoff');

// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'My Account');
define('BOX_MY_ACCOUNT', 'My Account');
define('BOX_MY_ACCOUNT_LOGOFF', 'Выход');

// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Администратор');
define('BOX_ADMINISTRATOR_MEMBERS', 'Member Groups');
define('BOX_ADMINISTRATOR_MEMBER', 'Members');
define('BOX_ADMINISTRATOR_BOXES', 'File Access');

// images
define('IMAGE_FILE_PERMISSION', 'File Permission');
define('IMAGE_GROUPS', 'Groups List');
define('IMAGE_INSERT_FILE', 'Insert File');
define('IMAGE_MEMBERS', 'Members List');
define('IMAGE_NEW_GROUP', 'New Group');
define('IMAGE_NEW_MEMBER', 'New Member');
define('IMAGE_NEXT', 'Next');

// constants for use in oosPrevNextDisplay function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> файлов)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> members)');


define('PULL_DOWN_DEFAULT', 'Please Select');

define('BOX_REPORTS_RECOVER_CART_SALES', 'Recover Carts');
define('BOX_TOOLS_RECOVER_CART', 'Recover Carts');

// BOF: WebMakers.com Added: All Add-Ons
// Download Controller
// Add a new Order Status to the orders_status table - Updated
define('ORDERS_STATUS_UPDATED_VALUE','4'); // set to the Updated status to update max days and max count

// Quantity Definitions
require('includes/languages/' . $_SESSION['language'] . '/' . 'quantity_control.php');
require('includes/languages/' . $_SESSION['language'] . '/' . 'mo_pics.php');

?>
