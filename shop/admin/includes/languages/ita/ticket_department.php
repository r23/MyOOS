<?php
/* ----------------------------------------------------------------------
   $Id: ticket_department.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_department.php,v 1.3 2003/04/25 21:37:11 hook
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
define('HEADING_TITLE', 'Reparto Ticket');

define('TABLE_HEADING_TEXT_DEPARTMENT', 'Reparto Ticket');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_DEPARTMENT','Visualizzati <b>%d</b> di <b>%d</b> (di <b>%d</b> Reparti)');

define('TEXT_INFO_EDIT_INTRO', 'Fai le modifche neccessarie');
define('TEXT_INFO_TEXT_DEPARTMENT_NAME', 'Reparti Ticket:');
define('TEXT_INFO_INSERT_INTRO', 'Fornire il nuovo Reparto con i relativi dati');
define('TEXT_INFO_DELETE_INTRO', 'Sei sicuro di voler cancellare questo Reparto Ticket?');
define('TEXT_INFO_HEADING_NEW_TEXT_DEPARTMENT', 'Nuovo Reparto Ticket');
define('TEXT_INFO_HEADING_EDIT_TEXT_DEPARTMENT', 'Modifica Reparto Ticket');
define('TEXT_INFO_HEADING_DELETE_TEXT_DEPARTMENT', 'Cancella Reparto Ticket');

define('ERROR_REMOVE_DEFAULT_TEXT_DEPARTMENT', 'Errore: Il Reparto predefinito non può essere cancellato. Inserisci un nuovo Reparto predefinto prima di riprovare.');
define('ERROR_DEPARTMENT_USED_IN_TICKET', 'Errore: Questo Reparto è attualmente utilizzato.');
define('ERROR_DEPARTMENT_USED_IN_HISTORY', 'Errore: Questo Reparto è attualmente utilizzato nella history');
?>
