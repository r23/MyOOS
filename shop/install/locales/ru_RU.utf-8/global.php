<?php 
/* ----------------------------------------------------------------------
   $Id: global.php,v 1.1 2007/06/13 16:41:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: global.php,v 1.17.2.1 2002/04/03 21:03:19 jgm 
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   Original Author of file: Gregor J. Rothfuss
   Purpose of file: Installer language defines.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if (strstr($_ENV["OS"],"Win")) {
  @setlocale(LC_TIME, 'ru'); 
} else {
  @setlocale(LC_TIME, 'ru_RU.utf-8'); 
}

define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_LONG . ' %H:%M:%S');

define('HTML_PARAMS','dir="LTR" lang="ru"');
define('CHARSET', 'utf-8');
define('INSTALLATION', 'Установка OOS [OSIS Online Shop]');

define('BTN_CONTINUE', 'Дальше');
define('BTN_NEXT' ,'Дальше');
define('BTN_RECHECK', 'Повторить');
define('BTN_SET_LANGUAGE', 'Выбор языка');
define('BTN_START','Начать Установку');
define('BTN_SUBMIT','Подтвердить');
define('BTN_NEW_INSTALL', 'Новая Установка');
define('BTN_UPGARDE', 'Обновление');
define('BTN_CHANGE_INFO', 'Изменить данные');
define('BTN_LOGIN_SUBMIT','Установить Админ');
define('BTN_SET_LOGIN', 'Дальше');
define('BTN_FINISH', 'Завершить');

define('GREAT', 'Добро пожаловать в OOS [OSIS Online Shop]!');
define('GREAT_1', 'OOS [OSIS Online Shop] это многофункциональнальная платформа. С помощью которой Вы сможете создать собственный интернет-магазин. OOS [OSIS Online Shop] - это програмное обеспечение, оснащенное всеми необходимыми функциями, такими как: каталог продукции, покупательская корзина, форма заказа, различные формы оплаты, статистика, аминистрирование, другие... Работа с каталогом товаров может производиться в реальном времени. Таким образом Вы сможете предоставить Вашим покупателям наиболее актуальные данные.');
define('SELECT_LANGUAGE_1', 'Выбор Вашего языка');
define('SELECT_LANGUAGE_2', 'Языки: ');

define('DEFAULT_1', 'GNU/GPL Лицензия:');
define('DEFAULT_2', 'OOS [OSIS Online Shop] это свободно распространяемое програмное обеспечение.');
define('DEFAULT_3', 'Я согласен с лицензией');

define('METHOD_1', 'Пожалуйста выберите способ установки <b>Новая установка</b> или <b>Обновление</b>');

define('PHP_CHECK_1', 'Проверка PHP');
define('PHP_CHECK_2', 'Настройки PHP на Вашем сервере <a href=\'phpinfo.php\' target=\'_blank\'>PHP Info</a>');
define('PHP_CHECK_3', 'Версия PHP ');
define('PHP_CHECK_4', 'Пожалуйста обновите версию PHP. - <a href=\'http://www.php.net\' target=\'_blank\'>http://www.php.net</a>');
define('PHP_CHECK_OK', 'Установленная версия PHP совместима c [OSIS Online Shop] ');
define('PHP_CHECK_6', 'magic_quotes_gpc выключено.');
define('PHP_CHECK_7', 'Добавьте в Ваш .htaccess файл следующую строку:<br />php_flag magic_quotes_gpc On');
define('PHP_CHECK_8', 'magic_quotes_gpc включено.');
define('PHP_CHECK_9', 'magic_quotes_runtime включено.');
define('PHP_CHECK_10', 'Добавьте в Ваш .htaccess файл следующую строку:<br />php_flag magic_quotes_runtime Off');
define('PHP_CHECK_11', 'magic_quotes_runtime выключено.');
define('PHP_CHECK_12', 'недоступна функция графики'); 
define('PHP_CHECK_13', 'Для графики вам нужна GD-Библиотека gd-lib (рекомендуемая версия 2.0 или выше) <br />Актуальная GD Библиотека на - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_14', 'недоступна функция графики truecolor'); 
define('PHP_CHECK_15', 'Для графических функций в OOS [OSIS Online Shop] мы рекомендуем GD Библиотеку <br />GD-Bibliothek gd-lib Версия 2.0 или выше - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_16', 'PHP_SELF');
define('PHP_CHECK_17', 'Имя файла скрипта, который был выведен, относительно к корню каталога документа недоступено.');

define('CHMOD_CHECK_1', 'Права доступа к файлам (CHMOD Проверка)');
define('CHMOD_CHECK_2', 'Проводится проверка прав доступа (CHMOD) к файлам configure.php и configure-old.php, если у скрипта автоматической установки не будет прав доступа к этим файлам, информация о базе данных не будет сохранена.');
define('CHMOD_CHECK_3', 'CHMOD ~/includes/configure.php - 666 -- ПРАВИЛЬНО');
define('CHMOD_CHECK_4', 'Пожалуйста установите права доступа (CHMOD 666) к файлу ~/includes/configure.php ');
define('CHMOD_CHECK_7', 'CHMOD ~/includes/configure-old.php - 666 -- ПРАВИЛЬНО');
define('CHMOD_CHECK_8', 'Пожалуйста установите права доступа к (CHMOD 666) файлу ~/includes/configure-old.php ');

define('CHM_CHECK_1', 'Настройка Базы Данных. <br /> Если у вас нет прав администратора на сервере Баз Данных вы не можете создавать новые Базы Данных. В этом случае укажите уже существующую Базу Данных. ');
define('DBINFO', 'Настройки подключения к БД');
define('DBHOST', 'Имя сервера БД');
define('DBHOST_DESC', 'Имя сервера Баз Данных');
define('DBNAME', 'Имя БД');
define('DBNAME_DESC', 'Имя Базы Данных');
define('DBPASS', 'Пароль');
define('DBPASS_DESC', 'Пароль пользователя Базы Данных');
define('DBPREFIX', 'Префикс БД');
define('DBPREFIX_DESC', 'Префикс таблиц, для паралельного использования');
define('DBTYPE', 'Версия БД');
define('DBTYPE_DESC', 'Версия сервера Баз Данных');
define('DBUNAME', 'Имя пользователя БД');
define('DBUNAME_DESC', 'Имя пользователя Базы Данных');

define('SUBMIT_1', 'Пожалуйста проверьте введённые данные.');
define('SUBMIT_2', 'Вы ввели следующие данные');
define('SUBMIT_3', '<b>Новая установка</b> или <b>Обновление</b> подтвердить соответственно <b>Изменить информацию</b> откорректировать данные ');

define('CHANGE_INFO_1', 'Настройки подключения к БД');
define('CHANGE_INFO_2', ' Измените Ваши Настройки подключения к БД');
define('NEW_INSTALL_1', 'Новая Установка');
define('NEW_INSTALL_2', 'Вами выбрана <b>Новая Установка.</b> ;hlt.<br />Пожалуйста проверьте введённые данные.');
define('NEW_INSTALL_3', 'Внимание: опция <b>создать новую Базу Данных</b> доступна только при наличии прав администратора.');
define('NEW_INSTALL_4', 'создать новую Базу Данных');

define('UPGRADE_1', 'Обновление');
define('UPGRADE_2', 'Доступ к Базе Данных OOS [OSIS Online Shop] будет осуществлён со следующими Настройками подключения к БД:');
define('UPGRADE_3', 'Выберите установленную версию Магазина:');
define('UPGRADE_INFO', 'ВНИМАНИЕ: перед обновлением <b>обязательно сохраните</b> предыдущую версию! Мы не даём каких-либо гарантий сохранения работоспособности после обновления!');

define('OOSUPGRADE_1', 'OOS [OSIS Online Shop]');
define('OOSUPGRADE_2', 'Если вы используете OOS [OSIS Online Shop] 1.0.1 выбирите пожалуйста <samp>OOS 1.0.1</samp>');
define('OOSUPGRADE_3', 'OOS [OSIS Online Shop]');
define('OOSUPGRADE_4', 'Если вы используете OOS [OSIS Online Shop] 1.0.2 выбирете пожалуйста <samp>OOS 1.0.2</samp>');
define('OOSUPGRADE_5', 'Вашу OOS [OSIS Online Shop] версию Вы можете посмотреть в файле: <samp>~/shop/includes/oos_version.php</samp>');

define('MADE', ' создано.');
define('MAKE_DB_1', 'немогу создать БД');
define('MAKE_DB_2', 'была создана.');
define('MAKE_DB_3', 'База Данных не создана.');
define('MODIFY_FILE_1', 'Ошибка: файл недоступен для записи:');

define('MODIFY_FILE_2', 'Ошибка: файл недоступен для записи:');
define('MODIFY_FILE_3', 'Ошибка: изменения были внесены ранее');
define('SHOW_ERROR_INFO', 'Ошибка:</b> OOS [OSIS Online Shop] Инсталляция не смогла записать данные в файл \'configure.php\' <br /> Вы можете записать данные в этот файл сами используя Эдитор. <br />Вот информации которые вы должны внести:');

define('VIRTUAL_1', 'Веб Сервер');
define('VIRTUAL_2', 'Введите Настройки Веб Cервера для OOS [OSIS Online Shop].');
define('VIRTUAL_3', 'SSL Шифрование');
define('VIRTUAL_4', 'Корневая Директория на Веб Сервере');
define('VIRTUAL_5', 'Директория Магазина на Веб Сервере');

define('VIRTUAL_7', 'WWW Директория Магазина');

define('VIRTUAL_9', 'Директория Шаблонов на Веб Сервере');

define('CONFIG_VIRTUAL_1', 'SSL-Кодировка');
define('CONFIG_VIRTUAL_2', 'Проверьте введённые данные');
define('CONFIG_VIRTUAL_3', 'Если данные введены верно, нажмите: <code>дальше</code>');


define('INSTALL_WRITE_FILE', 'Attemping to write %s file...');
define('ERROR_TEMPLATE_FILE', 'Невозможно открыть файл template!');
define('FILE_WRITE_ERROR', 'Can\'t write to file %s.');
define('COPY_CODE_BELOW', '<br />* Just copy the code below and place it in %s in your %s folder:<b><pre>%s</pre></b>' . "\n");
define('DONE', 'Done');

define('ERROR_NO_HTTPS_SERVER', 'Ошибка: Сервер %s несуществует');
define('ERROR_NO_DIRECTORY', 'Ошибка: Директория %s несуществует');
define('ERROR_NO_INFO', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');
define('INSTALL_REWRITE', 'URL Rewriting');
define('INSTALL_REWRITE_DESC', 'Select which rules you wish to use when generating URLs. Enabling rewrite rules will make pretty URLs for your blog and make it better indexable for spiders like google. The webserver needs to support either mod_rewrite or "AllowOverride All" for your OOS dir. The default setting is auto-detected');

define('HTACCESS_ERROR', 'To check your local webserver installation, serendipity needs to be able to write the file ".htaccess". This was not possible because of permission errors. Please adjust the permissions like this: <br />&nbsp;&nbsp;%s<br />and reload this page.');


define('TMP_VIRTUAL_1', 'Установки Сессии');
define('TMP_VIRTUAL_2', 'Поддержка Sessions в OOS [OSIS онлайн Магазин] предлагает возможность держать определенные данные во время последовательных вызовов Вашего магазина \'s. Вы можете выбирать между стандартной процедурой сохранения в файлах и хранения данных сессии в Вашу базу данных. При сохранении в Вашу базу данных Вы можете ещё дополнительно выбирать, должны ли писаться данные в неё кодируемо.');
define('TMP_VIRTUAL_3', 'Сохранить сессию в файлах   - активировать:');
define('TMP_VIRTUAL_4', 'Сохранить сессию в БД   - активировать:');
define('TMP_VIRTUAL_5', 'Сессию закодировать и сохранить в БД - активировать:');

define('TMP_CONFIG_VIRTUAL_2', 'Пожалуйста проверьте заданные данные:');
define('TMP_CONFIG_VIRTUAL_3', 'Сессии сохраняются в БД.');
define('TMP_CONFIG_VIRTUAL_4', 'Сессия сохраняется в БД.');
define('TMP_CONFIG_VIRTUAL_5', 'Кодировка дат сессии.');
define('TMP_SESSION_NON_EXISTENT', 'Предупреждение: Нет папки для сессий! ' . session_save_path() . '. Сессии не будут функционировать пока не будет создана папка.');

define('TMP_SESSION_DIRECTORY_NOT_WRITEABLE', 'Предупреждение: OOS [OSIS Online Shop] неможет писать в дирректорию Сессии ' . session_save_path() . '. Сессии не будут функционировать пока правильные права пользователя не будут установлены!');
define('TMP_ADODB_DIRECTORY', 'Ошибка: Databaseabstraktions Layer директория не существует');
define('TMP_ADODB_DIRECTORY_NOT_WRITEABLE', 'Ошибка: Databaseabstraktions Layer директория зашищена от записи.');

define('TMP_ADODB_FILE', 'Ошибка: Log файл не существует: Обработка ошибок базы данных в OOS [OSIS Online Shop] не будет функционировать пока не будет создан файл!');
define('TMP_ADODB_FILE_NOT_WRITEABLE', 'Ошибка: Log файл зашищен от записи.');

define('YES', 'активировано');
define('NO', 'деактивировано');

define('NOTMADE', ' не создано');
define('NOTUPDATED', '<img src="images/no.gif" alt="FEHLER" border="0" align="absmiddle">  ОШИБКА ');
define('UPDATED', 'обновлено');
define('NOW_104', 'Обновление Вашей БД OOS [OSIS Online Shop] успешно завершено!');

define('CONTINUE_1', 'Администратор Магазина');
define('CONTINUE_2', 'Теперь создайте Аккаунт Администратора OOS [OSIS Online Shop]. Позже Вы можете конфигурировать Ваш OOS [OSIS Online Shop] с E-mail - адресом и пассвордом ');
define('CONTINUE_3', 'Пожалуйста проверте заданную информацию. Изменение этих данных позже невозможно!');

define('ADMIN_GENDER', 'Обращение к Админу');
define('MALE', 'Господин');
define('FEMALE', 'Госпожа');

define('ADMIN_FIRSTNAME', 'Имя Админа');
define('ADMIN_NAME', 'Фамилия Админа');
define('ADMIN_EMAIL','E-Mail Админа');
define('ADMIN_PHONE', 'Тел. Админа');
define('ADMIN_FAX', 'Факс Админа');
define('ADMIN_PASS','Пассворд Админа');
define('ADMIN_REPEATPASS','Подтверждение Пассворда');
define('PASSWORD_HIDDEN', '--СКРЫТО--');
define('OWP_URL', 'Виртуальный Путь (URL)');
define('ROOT_DIR', 'Вебсервер Root Директория');
define('ADMIN_INSTALL', 'Проверьте правильность данных и нажмите <code>Инсталлировать Админа</code>');
define('PASSWORD_ERROR', '\'Passwort\' и \'Подтверждение\' должны всегда совпадать!');
define('ADMIN_ERROR', 'Ошибка:');
define('ADMIN_PASSWORD_ERROR', 'Пожалуйста задайте Ваш \'Passwort\'');
define('ADMIN_EMAIL_ERROR', 'Пожалуйста задайте Ваш \'E-Mail\'');

define('INPUT_DATA', 'База Данных OOS [OSIS Online Shop] ');

define('FINISH_1', 'Благодарность');
define('FINISH_2', 'При этой возможности мы хотим поблагодарить всех кто участвовал в разработке проекта OOS [OSIS Online Shop]. Наша специальная благодарность разработчикам PHP. ');
define('FINISH_3', 'Вы удачно установили OOS [OSIS Online Shop]. Теперь удалите пожалуйста директорию установки! install');
define('FINISH_4', 'OOS [OSIS Online Shop] Админ');

// All entries use ISO 639-2/T
// http://www.loc.gov/standards/iso639-2/langcodes.html

define('LANGUAGE_DAN', 'Датский');
define('LANGUAGE_NLD', 'Нидерландский');
define('LANGUAGE_ENG', 'Английский');
define('LANGUAGE_FIN', 'Финский');
define('LANGUAGE_FRA', 'Французский');
define('LANGUAGE_DEU', 'Немецкий');
define('LANGUAGE_ITA', 'Итальянский');
define('LANGUAGE_NOR', 'Норвежский');
define('LANGUAGE_POR', 'Португальский');
define('LANGUAGE_RUS', 'Русский');
define('LANGUAGE_SLV', 'Чехословатский');
define('LANGUAGE_SPA', 'Испанский');
define('LANGUAGE_SWE', 'Шведский');

define('FOOTER', 'Эта страница сделана с <a target="_blank" href="http://www.oos-shop.de/">OOS [OSIS Online Shop]</a> <br /><a target="_blank" href="http://www.oos-shop.de/">OOS [OSIS Online Shop]</a>это свободное программное обеспечение по лицензии GPL. <a target="_blank" href="http://www.gnu.org/">GNU/GPL Lizenz</a>');

define('STEP_1', 'Добро пожаловать');
define('STEP_2', 'Лицензия');
define('STEP_3', 'Проверка');
define('STEP_4', 'База данных');
define('STEP_5', 'Конфигурация');
define('STEP_6', 'Сессия');
define('STEP_7', 'Администратор');
define('STEP_8', 'Готово');

define('LINK_BACK', 'Назад');
define('LINK_TOP', 'На верх');

?>
