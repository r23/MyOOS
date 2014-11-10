<?php
/* ----------------------------------------------------------------------
   $Id: ticket_admin.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

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


define('HEADING_TITLE', 'Admins');

define('TABLE_HEADING_TEXT_ADMIN', 'Ticketadmins');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_ADMIN','Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Admins)');

define('TEXT_INFO_EDIT_INTRO', 'Bitte f&uuml;hren Sie alle notwendigen &Auml;nderungen durch');
define('TEXT_INFO_TEXT_ADMIN_NAME', 'Ticketadmin:');
define('TEXT_INFO_INSERT_INTRO', 'Bitte geben Sie den neuen Admin mit allen relevanten Daten ein');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie diesen Admin l&ouml;schen m&ouml;chten?');
define('TEXT_INFO_HEADING_NEW_TEXT_ADMIN', 'Neuer Ticketadmin');
define('TEXT_INFO_HEADING_EDIT_TEXT_ADMIN', 'Ticketadmin bearbeiten');
define('TEXT_INFO_HEADING_DELETE_TEXT_ADMIN', 'Ticketadmin l&ouml;schen');

define('ERROR_REMOVE_DEFAULT_TEXT_ADMIN', 'Fehler: Der Standard-Ticketadmin kann nicht gel&ouml;scht werden. Bitte definieren Sie einen neuen Standard-Admin und wiederholen Sie den Vorgang.');
define('ERROR_ADMIN_USED_IN_TICKET', 'Fehler: Dieser Ticketadmin wird zur Zeit noch bei den Tickets verwendet.');
define('ERROR_ADMIN_USED_IN_HISTORY', 'Fehler: Dieser Ticketadmin wird zur Zeit noch in der Tickethistorie verwendet.');
?>
