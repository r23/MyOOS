<?php
/* ----------------------------------------------------------------------
   $Id: ticket_status.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

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


define('HEADING_TITLE', 'Ticket Status');

define('TABLE_HEADING_TEXT_STATUS', 'Ticket Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_DEFAULT_REPLY','default Customer-Answer-Status');
define('TEXT_DISPLAY_NUMBER_OF_TEXT_STATUS','Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tickets status)');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_TEXT_STATUS_NAME', 'Ticket Status:');
define('TEXT_INFO_INSERT_INTRO', 'Please enter the new status with its related data');
define('TEXT_INFO_DELETE_INTRO', 'Are you sure you want to delete this ticket status?');
define('TEXT_INFO_HEADING_NEW_TEXT_STATUS', 'New Ticket Status');
define('TEXT_INFO_HEADING_EDIT_TEXT_STATUS', 'Edit Ticket Status');
define('TEXT_INFO_HEADING_DELETE_TEXT_STATUS', 'Delete Ticket Status');

define('TEXT_SET_DEFAULT_REPLY','New status if the customer replies');

define('ERROR_REMOVE_DEFAULT_TEXT_STATUS', 'Error: The default ticket status can not be removed. Please set another ticket status as default, and try again.');
define('ERROR_STATUS_USED_IN_TICKET', 'Error: This ticket status is currently used in ticket.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Error: This status is currently used in the status history.');
?>
