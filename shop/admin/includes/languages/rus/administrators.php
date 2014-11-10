<?php
/* ----------------------------------------------------------------------
   $Id: administrators.php,v 1.1 2007/06/13 17:03:53 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Login and Logoff for osCommerce Administrators.

   Original Version by Blake Schwendiman
   blake@intechra.net

   Updated Version 1.1.0 (03/01/2002) by Christopher Conkie
   chris@conkiec.freeserve.co.uk

   updated version 1.2.0 (06/27/2002) by Steve Myers
   info@megashare.net

   updated version 1.3.0 (03/06/2003) by Steve Myers
   chinaz@cga.net.cn
   ----------------------------------------------------------------------
   The Exchange Project - Community Made Shopping!
   http://www.theexchangeproject.org

   Copyright (c) 2000,2001 The Exchange Project

   Implemented by Blake Schwendiman (blake@intechra.net)
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('TOP_BAR_TITLE', 'Админы');
define('HEADING_TITLE', 'Установка Админов');
define('SUB_BAR_TITLE', 'Добавить, обработать или удалить данные входа для админов.');

define('TEXT_ADMINISTRATOR_USERNAME', 'Имя пользователя:');
define('TEXT_ADMINISTRATOR_PASSWORD', 'Пассворд:');
define('TEXT_ADMINISTRATOR_CONFPWD', 'Пассворд подтверждение:');
define( 'TEXT_CURRENT_ADMINISTRATORS', 'Текущие Администраторы' );
define( 'TEXT_ADD_ADMINISTRATOR', 'Добавить админа' );
define( 'TEXT_NO_CURRENT_ADMINS', 'В настоящее время нет никаких определенных администраторов.' );
define( 'TEXT_ADMIN_DELETE', 'Удалить' );
define( 'TEXT_ADMIN_SAVE', 'Сохранить' );
define( 'TEXT_PWD_ERROR', '<br /><p class="main">Пароль не соответствовал паролю подтверждения, или пароль был пуст.Новый Админ <b>не добавленный</b>.</p>' );
define( 'TEXT_UNAME_ERROR', '<br /><p class="main">Имя пользователя не может быть пустым.Новый администратор <b>не добавленн</b>.</p>' );
define( 'TEXT_FULL_ACCESS', 'Этот администратор имеет<b>неограниченный</b> доступ.' );
define( 'TEXT_PARTIAL_ACCESS', 'Этот администратор имеет доступ к следующим областям.  CTRL+Click выбирать многократный.' );
define( 'TEXT_ADMIN_HAS_ACCESS_TO', 'Права Админа' );
?>