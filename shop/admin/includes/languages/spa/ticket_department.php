<?php
/* ----------------------------------------------------------------------
   $Id: ticket_department.php,v 1.3 2007/06/13 16:51:46 r23 Exp $

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


define('HEADING_TITLE', 'Abteilungen');

define('TABLE_HEADING_TEXT_DEPARTMENT', 'Ticketabteilung');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_DEPARTMENT','Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Abteilungen)');

define('TEXT_INFO_EDIT_INTRO', 'Bitte f&uuml;hren Sie alle notwendigen &Auml;nderungen durch');
define('TEXT_INFO_TEXT_DEPARTMENT_NAME', 'Ticketabteilung:');
define('TEXT_INFO_INSERT_INTRO', 'Bitte geben Sie die neue Abteilung mit allen relevanten Daten ein');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie diese Abteilung l&ouml;schen m&ouml;chten?');
define('TEXT_INFO_HEADING_NEW_TEXT_DEPARTMENT', 'Neue Ticketabteilung');
define('TEXT_INFO_HEADING_EDIT_TEXT_DEPARTMENT', 'Ticketabteilung bearbeiten');
define('TEXT_INFO_HEADING_DELETE_TEXT_DEPARTMENT', 'Ticketabteilung l&ouml;schen');

define('ERROR_REMOVE_DEFAULT_TEXT_DEPARTMENT', 'Fehler: Die Standard-Ticketabteilungs kann nicht gel&ouml;scht werden. Bitte definieren Sie eine neue Standard-Abteilung und wiederholen Sie den Vorgang.');
define('ERROR_DEPARTMENT_USED_IN_TICKET', 'Fehler: Diese Ticketabteilung wird zur Zeit noch bei den Tickets verwendet.');
define('ERROR_DEPARTMENT_USED_IN_HISTORY', 'Fehler: Diese Ticketabteilung wird zur Zeit noch in der Tickethistorie verwendet.');
?>
