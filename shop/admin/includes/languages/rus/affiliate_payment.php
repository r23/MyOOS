<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_payment.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_payment.php,v 1.5 2003/02/17 14:18:30 harley_vb
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('HEADING_TITLE', 'Партнерская программа Выплата провизий');
define('HEADING_TITLE_SEARCH', 'Искать');
define('HEADING_TITLE_STATUS','Статус:');

define('TABLE_HEADING_ACTION', 'Акция');
define('TABLE_HEADING_STATUS', 'Статус');
define('TABLE_HEADING_AFILIATE_NAME', 'Партнер');
define('TABLE_HEADING_PAYMENT','Провизия (inkl.)');
define('TABLE_HEADING_NET_PAYMENT','Провизия (exkl.)');
define('TABLE_HEADING_DATE_BILLED','Дата Счета');
define('TABLE_HEADING_NEW_VALUE', 'Новый Статус');
define('TABLE_HEADING_OLD_VALUE', 'Старый Статус');
define('TABLE_HEADING_AFFILIATE_NOTIFIED', 'Уведомить Партнера');
define('TABLE_HEADING_DATE_ADDED', 'Добавлено:');

define('TEXT_DATE_PAYMENT_BILLED', 'Оплачено:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Последнее изменение');
define('TEXT_AFFILIATE_PAYMENT', 'Провизия');
define('TEXT_AFFILIATE_BILLED', 'Дата оплаты');
define('TEXT_AFFILIATE', 'Партнер');

define('TEXT_INFO_HEADING_DELETE_PAYMENT', 'Удалить оплату');
define('TEXT_INFO_DELETE_INTRO', 'Вы уверенны, что вы хотите удалить эту выплату провизии?');

define('TEXT_DISPLAY_NUMBER_OF_PAYMENTS', 'Показаны <b>%d</b> до <b>%d</b> (из <b>%d</b> Выплат провизий)');

define('TEXT_AFFILIATE_PAYING_POSSIBILITIES', 'Возможности выплат:');
define('TEXT_AFFILIATE_PAYMENT_CHECK', 'Чеком:');
define('TEXT_AFFILIATE_PAYMENT_CHECK_PAYEE', 'Получатель чека:');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL', 'через систему PayPal');
define('TEXT_AFFILIATE_PAYMENT_PAYPAL_EMAIL', 'PayPal Аккаунт eMail:');
define('TEXT_AFFILIATE_PAYMENT_BANK_TRANSFER', 'Переводом на счёт в Банке:');
define('TEXT_AFFILIATE_PAYMENT_BANK_NAME', 'Банк:');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME', 'Владелец счёта');
define('TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER', 'Номер Счёта');
define('TEXT_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER', 'Номер Банка');
define('TEXT_AFFILIATE_PAYMENT_BANK_SWIFT_CODE', 'SWIFT Code:');

define('IMAGE_AFFILIATE_BILLING', 'Start Billing Engine');

define('PAYMENT_STATUS', 'Статус оплаты');
define('PAYMENT_NOTIFY_AFFILIATE', 'Уведомить партнера');

define('TEXT_ALL_PAYMENTS', 'Все оплаты');
define('TEXT_NO_PAYMENT_HISTORY', 'История оплат недоступна !');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Изменения статуса выплат вашых провизий');
define('EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER', '№ Выплат провизий:');
define('EMAIL_TEXT_INVOICE_URL', 'Детальная информация выплат');
define('EMAIL_TEXT_PAYMENT_BILLED', 'Дата счета');
define('EMAIL_TEXT_STATUS_UPDATE', 'Статус ваши выплат провизий изменён.' . "\n\n" . 'Новый статус: %s' . "\n\n" . 'Свопросами обращайтесь по этому E-Mail.' . "\n\n" . 'С уважением.' . "\n");
define('EMAIL_TEXT_NEW_PAYMENT', 'Новый расчет ваших провизий создан.' . "\n");

define('SUCCESS_BILLING', 'Сообщение: Расчет произведён!');
define('SUCCESS_PAYMENT_UPDATED', 'Сообщение: Статус этого расчета обновлен.');
define('ERROR_PAYMENT_DOES_NOT_EXIST', 'Ошибка: Выплата провизий несуществует!');
?>