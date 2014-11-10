<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_password_forgotten.php,v 1.3 2007/06/12 17:03:32 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_password_forgotten.php,v 1.3 2003/02/15 18:41:15 harley_vb 
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


$aLang['navbar_title_1'] = 'Партнёрская Программа: Вход';
$aLang['navbar_title_2'] = 'Восстановление Забытого Пароля';
$aLang['heading_title'] = 'Я Забыл Пароль для Входа в Партнёрскую Программу!';
$aLang['text_no_email_address_found'] = '<font color="#ff0000"><b>ВНИМАНИЕ:</b></font> Указаный E-Mail Адрес не зарегистрирован в партнёрской программе. Попробуйте ещё раз.';
$aLang['email_password_reminder_subject'] = STORE_NAME . ' - Новый Пароль для Партнёрской Программы';
$aLang['email_password_reminder_body'] = 'Новый пароль для партнёрской программы был запрошен ' . oos_server_get_remote() . '.' . "\n\n" . 'Ваш новый пароль для партнёрской программы \'' . STORE_NAME . '\':' . "\n\n" . '   %s' . "\n\n";
$aLang['text_password_sent'] = 'Новый Пароль для Партнёрской Программы Отправлен на Ваш Email.';
?>