<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.6 2002/11/19 01:48:08 dgw_
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

define('ADMIN_PASSWORD_SUBJECT', STORE_NAME . ' - Nuova Password');
define('ADMIN_EMAIL_TEXT', 'Una nuova password è stata richiesta da ' . oos_server_get_var('REMOTE_ADDR') . '.' . "\n\n" . 'La tua nuova passowrd per \'' . STORE_NAME . '\' è:' . "\n\n" . '   %s' . "\n\n");

define('HEADING_PASSWORD_FORGOTTEN', 'Password Dimenticata:');
define('TEXT_PASSWORD_INFO', 'Inserisci il tuo nome utente e la e-mail e clicca sul bottone.<br />Riceverai una nuova password al piu presto. Usa poi la nuova password per accedere al sito.');

?>
