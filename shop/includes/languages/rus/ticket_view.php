<?php
/* ----------------------------------------------------------------------
   $Id: ticket_view.php,v 1.3 2007/06/12 17:03:33 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2005 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_view.php,v 1.3 2003/04/25 21:37:12 hook 
   ----------------------------------------------------------------------
   OSC-SupportTicketSystem
   Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


$aLang['heading_title'] = ' Сервис Посмотреть запрос';
$aLang['navbar_title'] = 'Посмотреть запрос';

$aLang['table_heading_nr'] = '№ Запроса';
$aLang['table_heading_subject'] = 'Относительно';
$aLang['table_heading_status'] = 'Статус';
$aLang['table_heading_department'] = 'Отдел';
$aLang['table_heading_priority'] = 'Приоритет';
$aLang['table_heading_created'] = 'Открыт';
$aLang['table_heading_last_modified'] = 'Последнее измененме';

$aLang['text_ticket_by'] = 'от';
$aLang['text_comment'] = 'Ответ:';
$aLang['text_date'] = 'Дата:';
$aLang['text_department'] = 'Отдел:';
$aLang['text_priority'] = 'Приоритет:';
$aLang['text_opened'] = 'Открыт:';
$aLang['text_status'] = 'Статус:';
$aLang['text_ticket_nr'] = '№ Запроса:';
$aLang['text_customers_orders_id'] = '№ Заказа:';
$aLang['text_view_ticket_nr'] = 'Пожалуйста задайте № Запроса';

$aLang['ticket_warning_enquiry_too_short'] = 'ОШИБКА: Внесённые данные не соответствуют минимальной длинне знаков ' . TICKET_ENTRIES_MIN_LENGTH . ' .';
$aLang['ticket_message_updated'] = 'Ihr Ticket wurde aktualisiert';

$aLang['text_view_ticket_login'] = '<a href="%s">Чтобы посмотреть статус вашего запроса, войдете прежде в систему.</a>';
?>