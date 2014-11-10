<?php
/* ----------------------------------------------------------------------
   $Id: ticket_priority.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

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

define('HEADING_TITLE', 'Ticket Priority');

define('TABLE_HEADING_TEXT_PRIORITY', 'Ticket Priority');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_PRIORITY','Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Priorities');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_TEXT_PRIORITY_NAME', 'Ticket Priority:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new ticket priority with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this ticket priority?');
define('TEXT_INFO_HEADING_NEW_TEXT_PRIORITY', 'New Ticket Priority');
define('TEXT_INFO_HEADING_EDIT_TEXT_PRIORITY', 'Edit Ticket Priority');
define('TEXT_INFO_HEADING_DELETE_TEXT_PRIORITY', 'Delete Ticket Priority');

define('ERROR_REMOVE_DEFAULT_TEXT_PRIORITY', 'Error: The default ticket priority can not be removed. Please set another ticket priority as default, and try again.');
define('ERROR_PRIORITY_USED_IN_TICKET', 'Error: This ticket priority is currently used in tickets.');
define('ERROR_PRIORITY_USED_IN_HISTORY', 'Error: This ticket priority is currently used in the ticket history.');
?>
