<?php
/* ----------------------------------------------------------------------
   $Id: ticket_admin.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_admin.php,v 1.3 2003/04/25 21:37:11 hook
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

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Amministratori Ticket');

define('TABLE_HEADING_TEXT_ADMIN', 'Amministratori Ticket');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_ADMIN','Visualizzati <b>%d</b> di <b>%d</b> (di <b>%d</b> Amministratori)');

define('TEXT_INFO_EDIT_INTRO', 'Fai le modifche neccessarie');
define('TEXT_INFO_TEXT_ADMIN_NAME', 'Amministratori Ticket:');
define('TEXT_INFO_INSERT_INTRO', 'Fornire il nuovo Amministratore con i relativi dati');
define('TEXT_INFO_DELETE_INTRO', 'Sei sicuro di voler cancellare questo Amministratore?');
define('TEXT_INFO_HEADING_NEW_TEXT_ADMIN', 'Nuovo Amministratore Ticket');
define('TEXT_INFO_HEADING_EDIT_TEXT_ADMIN', 'Modifica Amministratore Ticket');
define('TEXT_INFO_HEADING_DELETE_TEXT_ADMIN', 'Cancella Amministratore Ticket');

define('ERROR_REMOVE_DEFAULT_TEXT_ADMIN', 'Errore: L\' Amministratore predefinito non può essere cancellato. Inserisci un nuovo Amministratore predefinto prima di riprovare.');
define('ERROR_ADMIN_USED_IN_TICKET', 'Errore: Questo Amministratore è attualmente utilizzato.');
define('ERROR_ADMIN_USED_IN_HISTORY', 'Errore: Questo Amministratore è attualmente utilizzato nella history');
?>
