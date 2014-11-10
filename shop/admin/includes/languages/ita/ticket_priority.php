<?php
/* ----------------------------------------------------------------------
   $Id: ticket_priority.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_priority.php,v 1.3 2003/04/25 21:37:11 hook
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

define('HEADING_TITLE', 'Priorità Ticket');

define('TABLE_HEADING_TEXT_PRIORITY', 'Priorità Ticket');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_PRIORITY','Visualizzati<b>%d</b> di <b>%d</b> (di <b>%d</b> Priorità');

define('TEXT_INFO_EDIT_INTRO', 'Fai le modifche neccessarie');
define('TEXT_INFO_TEXT_PRIORITY_NAME', 'Priorità Ticket:');
define('TEXT_INFO_INSERT_INTRO', 'Fornire la nuova priorità con i relativi dati');
define('TEXT_INFO_DELETE_INTRO', 'Sei sicuro di voler cancellare questa Priorità Ticket?');
define('TEXT_INFO_HEADING_NEW_TEXT_PRIORITY', 'Nuova Priorità Ticket');
define('TEXT_INFO_HEADING_EDIT_TEXT_PRIORITY', 'Modifica Priorità Ticket');
define('TEXT_INFO_HEADING_DELETE_TEXT_PRIORITY', 'Cancella Priorità Ticket');

define('ERROR_REMOVE_DEFAULT_TEXT_PRIORITY', 'Errore: La Priorità predefinita non può essere cancellata. Inserisci una nuova Priorità predefinta prima di riprovare.');
define('ERROR_PRIORITY_USED_IN_TICKET', 'Errore: Questa Priorità è attualmente utilizzata.');
define('ERROR_PRIORITY_USED_IN_HISTORY', 'Errore: Questa Priorità è attualmente utilizzata nello history');
?>
