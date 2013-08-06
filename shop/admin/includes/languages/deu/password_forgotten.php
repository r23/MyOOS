<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.8 2003/02/16 00:42:03 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['admin_password_subject'] =  STORE_NAME . ' - Ihr neues Passwort.';
$aLang['admin_email_text'] = 'Über die Adresse ' . oos_server_get_var('REMOTE_ADDR') . ' haben wir eine Anfrage zur Passworterneuerung erhalten.' . "\n\n" . 'Ihr neues Passwort für &quot;%s&quot;\'' . STORE_NAME . '\' lautet ab sofort:' . "\n\n" . '   %s' . "\n\n");

$aLang['heading_password_forgotten'] = 'Passwort vergessen';
$aLang['text_password_info'] = 'Bitte geben Sie Ihren Vornamen und Ihre E-Mail Adresse ein und klicken Sie auf Passwort senden. <br />In Kürze erhalten Sie ein neues Passwort. Verwenden Sie dieses Passwort, um sich anzumelden.';


