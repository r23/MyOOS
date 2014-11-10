<?php
/* ----------------------------------------------------------------------
   $Id: ticket_admin.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

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

define('HEADING_TITLE', 'Ticket Admin');

define('TABLE_HEADING_TEXT_ADMIN', 'Ticket Admin');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_ADMIN','Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Admins)');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_TEXT_ADMIN_NAME', 'Ticket Admins:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new ticket admin with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this ticket admin');
define('TEXT_INFO_HEADING_NEW_TEXT_ADMIN', 'New Ticket Admin');
define('TEXT_INFO_HEADING_EDIT_TEXT_ADMIN', 'Edit Ticket Admin');
define('TEXT_INFO_HEADING_DELETE_TEXT_ADMIN', 'Delete Ticket Admin');

define('ERROR_REMOVE_DEFAULT_TEXT_ADMIN', 'Error: The default ticket admin can not be removed. Please set another ticket admin as default, and try again.');
define('ERROR_ADMIN_USED_IN_TICKET', 'Error: This ticket admin is currently used in tickets.');
define('ERROR_ADMIN_USED_IN_HISTORY', 'Error: This ticket admin department is currently used in the ticket history.');
?>
