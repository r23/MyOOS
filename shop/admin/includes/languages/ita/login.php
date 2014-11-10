<?php
/* ----------------------------------------------------------------------
   $Id: login.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.11 2002/06/03 13:19:42 hpdl 
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
define('HEADING_RETURNING_ADMIN', 'Pannello di Login:');
define('TEXT_RETURNING_ADMIN', 'Solo Staff!');
define('ENTRY_EMAIL_ADDRESS', 'Indirizzo E-Mail:');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_FIRSTNAME', 'Nome:');
define('IMAGE_BUTTON_LOGIN', 'Invia');

define('SECURITYCODE', 'Codice di sicurezza:');
define('TEXT_PASSWORD_FORGOTTEN', 'Password dimenticata?');
define('TEXT_WELCOME', 'Welcome to <br />OOS [OSIS Online Shop]!</p><p>Use a valid eMail and password to gain access to the administration console.');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> Username o Password non validi!');
define('TEXT_FORGOTTEN_ERROR', '<font color="#ff0000"><b>ERRORE:</b></font> Nome e Password non riscontrati!');
define('TEXT_FORGOTTEN_FAIL', 'Hai effettuato più di 3 tentativi. Per ragioni di sicurezza, contatta l\'amministratore del sito per richiedere una nuova password.<br>&nbsp;<br>&nbsp;');
define('TEXT_FORGOTTEN_SUCCESS', 'La nuova password è stata inviata al tuo indirizzo email. Controlla la tua casella di posta ed poi effettua il login.<br>&nbsp;<br>&nbsp;');

define('ADMIN_EMAIL_SUBJECT', 'Nuova Password'); 
define('ADMIN_EMAIL_TEXT', 'Salve %s,' . "\n\n" . 'Puoi accedere al pannello di login con la seguente password. Dopo esserti autenticato, cambia la tua password!' . "\n\n" . 'Sito Web : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Grazie!' . "\n" . '%s' . "\n\n" . 'Questo è un messaggio inviato automaticamente, a cui non bisogna rispondere!'); 
?>
