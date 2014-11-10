<?php
/* ----------------------------------------------------------------------
   $Id: admin_members.php,v 1.3 2007/06/13 16:39:11 r23 Exp $

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
if ($_GET['gID']) {
  define('HEADING_TITLE', 'Amministra Gruppi');
} elseif ($_GET['gPath']) {
  define('HEADING_TITLE', 'Definisci Gruppi');
} else {
  define('HEADING_TITLE', 'Membri Admin');
}

define('TEXT_COUNT_GROUPS', 'Gruppi: ');

define('TABLE_HEADING_NAME', 'Nome');
define('TABLE_HEADING_EMAIL', 'Indirizzo Email');
define('TABLE_HEADING_PASSWORD', 'Password');
define('TABLE_HEADING_CONFIRM', 'Conferma Password');
define('TABLE_HEADING_GROUPS', 'Livello Gruppi');
define('TABLE_HEADING_CREATED', 'Account Creato');
define('TABLE_HEADING_MODIFIED', 'Account Modificato');
define('TABLE_HEADING_LOGDATE', 'Ultimo Accesso');
define('TABLE_HEADING_LOGNUM', 'LogNum');
define('TABLE_HEADING_LOG_NUM', 'Log Numero');
define('TABLE_HEADING_ACTION', 'Azione');

define('TABLE_HEADING_GROUPS_NAME', 'Nome Gruppi');
define('TABLE_HEADING_GROUPS_DEFINE', 'Selezione Box e File');
define('TABLE_HEADING_GROUPS_GROUP', 'Livello');
define('TABLE_HEADING_GROUPS_CATEGORIES', 'Permesso Categorie');


define('TEXT_INFO_HEADING_DEFAULT', 'Membri Admin ');
define('TEXT_INFO_HEADING_DELETE', 'Permesso Cancellazione ');
define('TEXT_INFO_HEADING_EDIT', 'Modifica Categoria / ');
define('TEXT_INFO_HEADING_NEW', 'Nuovo Membro Admin ');

define('TEXT_INFO_DEFAULT_INTRO', 'gruppo Membro');
define('TEXT_INFO_DELETE_INTRO', 'Rimuovo <nobr><b>%s</b></nobr> da <nobr>Membri Admin?</nobr>');
define('TEXT_INFO_DELETE_INTRO_NOT', 'non puoi cancllare <nobr>gruppo %s!</nobr>');
define('TEXT_INFO_EDIT_INTRO', 'Imposta livello permessi qui: ');

define('TEXT_INFO_FULLNAME', 'Nome Completo: ');
define('TEXT_INFO_FIRSTNAME', 'Nome: ');
define('TEXT_INFO_LASTNAME', 'Cognome: ');
define('TEXT_INFO_EMAIL', 'Indirizzo Email: ');
define('TEXT_INFO_PASSWORD', 'Password: ');
define('TEXT_INFO_CONFIRM', 'Conferma Password: ');
define('TEXT_INFO_CREATED', 'Creazione Account: ');
define('TEXT_INFO_MODIFIED', 'Ultima Modifica: ');
define('TEXT_INFO_LOGDATE', 'Ultimo Accesso: ');
define('TEXT_INFO_LOGNUM', 'Numero Log: ');
define('TEXT_INFO_GROUP', 'Livello Gruppo: ');
define('TEXT_INFO_ERROR', '<font color="red">L\'indirizzo email è già stato utilizzato!. Riprova.</font>');

define('JS_ALERT_FIRSTNAME', '- Richiesto: Nome \n');
define('JS_ALERT_LASTNAME', '- Richiesto: Cognome \n');
define('JS_ALERT_EMAIL', '- Richiesto: indirizzo Email \n');
define('JS_ALERT_EMAIL_FORMAT', '- indirizzo Email non valido! \n');
define('JS_ALERT_EMAIL_USED', '- indirizzo Email già usato! \n');
define('JS_ALERT_LEVEL', '- Richiesto: Membro Gruppo \n');

define('ADMIN_EMAIL_SUBJECT', 'Nuovo Membro Admin');
define('ADMIN_EMAIL_TEXT', 'Ciao %s,' . "\n\n" . 'Puoi accedere al pannello di amministrazione usando la seguente password. Una volta effettuato il login, cambia la tua password!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Grazie!' . "\n" . '%s' . "\n\n" . 'Questa è una email inviata automaticamente, quindi non rispondere ad essa!'); 

define('TEXT_INFO_HEADING_DEFAULT_GROUPS', 'Gruppo Admin ');
define('TEXT_INFO_HEADING_DELETE_GROUPS', 'Cancella Group ');

define('TEXT_INFO_DEFAULT_GROUPS_INTRO', '<b>NOTA:</b><li><b>modifica:</b> modifica nome gruppo.</li><li><b>cancella:</b> cancella gruppo.</li><li><b>definisci</b> definisce accesso gruppo.</li>');
define('TEXT_INFO_EDIT_GROUP_INTRO', 'Modifica Nome Gruppo');
define('TEXT_INFO_DELETE_GROUPS_INTRO', 'Verranno cancellati anche i membri di questo gruppo. Sicuro di voler cancellare il  <nobr><b>gruppo %s</b>?</nobr>');
define('TEXT_INFO_DELETE_GROUPS_INTRO_NOT', 'Non puoi cancellare questo gruppo!');
define('TEXT_INFO_GROUPS_INTRO', 'Fornire un nome gruppo univoco. Continua per inviare i dati.');

define('TEXT_INFO_HEADING_EDIT_GROUP', 'Gruppo Admin');
define('TEXT_INFO_HEADING_GROUPS', 'Nuovo Gruppo');
define('TEXT_INFO_GROUPS_NAME', ' <b>Nome Gruppo:</b><br>Fornire un nome gruppo univoco. Quindi, clicca <b>avanti</b> per continuare.<br>');
define('TEXT_INFO_GROUPS_NAME_FALSE', '<font color="red"><b>ERRORE:</b> Il nome gruppo deve avere pi di 5 caratteri!</font>');
define('TEXT_INFO_GROUPS_NAME_USED', '<font color="red"><b>ERRORE:</b> Nome gruppo gi�utilizzato!</font>');
define('TEXT_INFO_GROUPS_LEVEL', 'Livello Gruppo: ');
define('TEXT_INFO_GROUPS_BOXES', '<b>Permesso Box:</b><br>Dai l\'accesso ai box selezionati.');
define('TEXT_INFO_GROUPS_BOXES_INCLUDE', 'Includi files salvati in: ');

define('TEXT_INFO_HEADING_DEFINE', 'Definisci Gruppo');
if ($_GET['gPath'] == 1) {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br>Non puoi modificare i permessi dei file di questo gruppo.<br><br>');
} else {
  define('TEXT_INFO_DEFINE_INTRO', '<b>%s :</b><br>Cambia file dei permessi per questo gruppo selezionando o deselezionando box e file. Poi <b>salva</b> le modifiche.<br><br>');
}
?>
