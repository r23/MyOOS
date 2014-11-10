<?php
/* ----------------------------------------------------------------------
   $Id: email_orders.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
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


define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Обновление заказа');
define('EMAIL_TEXT_ORDER_NUMBER', 'Номер заказа:');
define('EMAIL_TEXT_INVOICE_URL', 'Подробная Счет-фактура:');
define('EMAIL_TEXT_DATE_ORDERED', 'Заказано на дату:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Статус Вашего заказа скорректирован.' . "\n\n" . 'Новый статус: %s' . "\n\n" . 'Ответьте на этот e-mail, если у Вас есть какие либо вопросы.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Комментарии для вашего заказа' . "\n\n%s\n\n");
?>
