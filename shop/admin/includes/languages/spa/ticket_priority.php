<?php
/* ----------------------------------------------------------------------
   $Id: ticket_priority.php,v 1.3 2007/06/13 17:02:38 r23 Exp $

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


define('HEADING_TITLE', 'Priorit&auml;ten');

define('TABLE_HEADING_TEXT_PRIORITY', 'Ticketpriorit&auml;t');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_PRIORITY','Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Priorit&auml;ten)');

define('TEXT_INFO_EDIT_INTRO', 'Bitte f&uuml;hren Sie alle notwendigen &Auml;nderungen durch');
define('TEXT_INFO_TEXT_PRIORITY_NAME', 'Ticketpriorit&auml;t:');
define('TEXT_INFO_INSERT_INTRO', 'Bitte geben Sie die neue Priorit&auml;t mit allen relevanten Daten ein');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie diese Priorit&auml;t l&ouml;schen m&ouml;chten?');
define('TEXT_INFO_HEADING_NEW_TEXT_PRIORITY', 'Neue Ticketpriorit&auml;t');
define('TEXT_INFO_HEADING_EDIT_TEXT_PRIORITY', 'Ticketpriorit&auml;t bearbeiten');
define('TEXT_INFO_HEADING_DELETE_TEXT_PRIORITY', 'Ticketpriorit&auml;t l&ouml;schen');

define('ERROR_REMOVE_DEFAULT_TEXT_PRIORITY', 'Fehler: Die Standard-Ticketpriorit&auml;ts kann nicht gel&ouml;scht werden. Bitte definieren Sie eine neue Standard-Priorit&auml;t und wiederholen Sie den Vorgang.');
define('ERROR_PRIORITY_USED_IN_TICKET', 'Fehler: Diese Ticketpriorit&auml;t wird zur Zeit noch bei den Tickets verwendet.');
define('ERROR_PRIORITY_USED_IN_HISTORY', 'Fehler: Diese Ticketpriorit&auml;t wird zur Zeit noch in der Tickethistorie verwendet.');
?>
