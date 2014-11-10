<?php
/* ----------------------------------------------------------------------
   $Id: admin_account.php,v 1.3 2007/06/13 16:39:11 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_members.php,v 1.13 2002/08/19 01:45:58 hpdl
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

define('HEADING_TITLE', 'Account Amministratore');

define('TABLE_HEADING_ACCOUNT', 'Il mio Account');

define('TEXT_INFO_FULLNAME', '<b>Login: </b>');
define('TEXT_INFO_FIRSTNAME', '<b>Nome: </b>');
define('TEXT_INFO_LASTNAME', '<b>Cognome: </b>');
define('TEXT_INFO_EMAIL', '<b>Indirizzo Email: </b>');
define('TEXT_INFO_PASSWORD', '<b>Password: </b>');
define('TEXT_INFO_PASSWORD_HIDDEN', '-Nascosta-');
define('TEXT_INFO_PASSWORD_CONFIRM', '<b>Conferma Password: </b>');
define('TEXT_INFO_CREATED', '<b>Account Creato: </b>');
define('TEXT_INFO_LOGDATE', '<b>Ultimo Access: </b>');
define('TEXT_INFO_LOGNUM', '<b>Log Numero: </b>');
define('TEXT_INFO_GROUP', '<b>Livello Gruppo: </b>');
define('TEXT_INFO_ERROR', '<font color="red">Idirizzo Email già utilizzato!</font>');
define('TEXT_INFO_MODIFIED', 'Modificato: ');

define('TEXT_INFO_HEADING_DEFAULT', 'Modifica Account ');
define('TEXT_INFO_HEADING_CONFIRM_PASSWORD', 'Conferma Password ');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD', 'Password:');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR', '<font color="red"><b>ERRORE:</b> password sbagliata!</font>');
define('TEXT_INFO_INTRO_DEFAULT', 'Clicca il <b>bottone modifica</b> sotto per modificare il tuo account.');
define('TEXT_INFO_INTRO_DEFAULT_FIRST_TIME', '<br /><b>ATTENZIONE:</b><br />Ciao <b>%s</b>, ti sei appena autenticato per la prima volta. Ti raccomandiamo di cambiare la tua password!');
define('TEXT_INFO_INTRO_DEFAULT_FIRST', '<br /><b>ATTENZIONE:</b><br />Ciao <b>%s</b>, ti raccomandiamo di cambiare la tua e-mail (<font color="red">admin@localhost</font>) e la passworld!');
define('TEXT_INFO_INTRO_EDIT_PROCESS', 'Tutti i campi sono richiesti. Clicca su Salva per andare avanti.');

define('JS_ALERT_FIRSTNAME',        '- Richiesto: Nome \n');
define('JS_ALERT_LASTNAME',         '- Richiesto: Cognome \n');
define('JS_ALERT_EMAIL',            '- Richiesto: Indirizzo E-Mail \n');
define('JS_ALERT_PASSWORD',         '- Richiesto: Password \n');
define('JS_ALERT_FIRSTNAME_LENGTH', '- La lunghezza del nome deve essere lunga ');
define('JS_ALERT_LASTNAME_LENGTH',  '- La lunghezza del cognome deve essere lunga');
define('JS_ALERT_PASSWORD_LENGTH',  '- La lunghezza della Password deve essere lunga');
define('JS_ALERT_EMAIL_FORMAT',     '- Il formato della e-mail non è valido! \n');
define('JS_ALERT_EMAIL_USED',       '- Questa e-mail è già stata utilizzata! \n');
define('JS_ALERT_PASSWORD_CONFIRM', '- Devi inserire anche la password di conferma!!! \n');

?>
