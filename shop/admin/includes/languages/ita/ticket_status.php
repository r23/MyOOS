<?php
/* ----------------------------------------------------------------------
   $Id: ticket_status.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_status.php,v 1.3 2003/04/25 21:37:11 hook
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

define('HEADING_TITLE', 'Stato Ticket');

define('TABLE_HEADING_TEXT_STATUS', 'Stao Ticket');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_DEFAULT_REPLY','Risposta-Cliente-Stato predefinita');
define('TEXT_DISPLAY_NUMBER_OF_TEXT_STATUS','Visualizzati <b>%d</b> di <b>%d</b> (di <b>%d</b> Stati tickets)');

define('TEXT_INFO_EDIT_INTRO', 'Fai le modifche neccessarie');
define('TEXT_INFO_TEXT_STATUS_NAME', 'Stato Ticket:');
define('TEXT_INFO_INSERT_INTRO', 'Fornire il nuovo status con i relativi dati');
define('TEXT_INFO_DELETE_INTRO', 'Sei sicuro di voler cancellare questo stato?');
define('TEXT_INFO_HEADING_NEW_TEXT_STATUS', 'Nuovo Stato Ticket');
define('TEXT_INFO_HEADING_EDIT_TEXT_STATUS', 'Modifica Stato Ticket');
define('TEXT_INFO_HEADING_DELETE_TEXT_STATUS', 'Cancella Stato Ticket');

define('TEXT_SET_DEFAULT_REPLY','Nuovo stato se il cliente risponde');

define('ERROR_REMOVE_DEFAULT_TEXT_STATUS', 'Errore: Lo stato predefinito non può essere cancellato. Inserisci un nuovo stato predefinto prima di riprovare.');
define('ERROR_STATUS_USED_IN_TICKET', 'Errore: Questo stato è attualmente utilizzato.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Errore: Questo stato è attualmente utilizzato nello status history');

?>
