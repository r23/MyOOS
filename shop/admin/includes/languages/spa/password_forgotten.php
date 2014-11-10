<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.3 2007/06/13 17:02:38 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: password_forgotten.php,v 1.6 2002/11/12 00:45:21 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('ADMIN_PASSWORD_SUBJECT', STORE_NAME . ' - Nueva Contrase�');
define('ADMIN_EMAIL_TEXT', 'Ha solicitado una Nueva Contrase� desde ' . oos_server_get_var('REMOTE_ADDR') . '.' . "\n\n" . 'Su nueva contrase� para \'' . STORE_NAME . '\' es:' . "\n\n" . '   %s' . "\n\n");

define('HEADING_PASSWORD_FORGOTTEN', 'Password Forgotten:');
define('TEXT_PASSWORD_INFO', 'Please enter your Username and e-mail address then click on the Send Password button.<br />You will receive a new password shortly. Use this new password to access the site.');
?>
