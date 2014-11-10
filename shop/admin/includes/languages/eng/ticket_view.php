<?php
/* ----------------------------------------------------------------------
   $Id: ticket_view.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_view.php,v 1.5 2003/04/25 21:37:11 hook 
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


define('HEADING_TITLE', 'Supporttickets');
define('HEADING_TITLE_STATUS','Ticket Status:');
define('HEADING_TITLE_DEPARTMENT','Ticket Department:');
define('HEADING_TITLE_PRIORITY','Ticket Priority:');

define('TABLE_HEADING_ACTION', 'Action');

define('TABLE_HEADING_CUSTOMER_ID','Cid.');
define('TABLE_HEADING_DATE','last change');
define('TABLE_HEADING_DEPARTMENT','Department');
define('TABLE_HEADING_NAME','Name');
define('TABLE_HEADING_ORDER_ID', 'OrderID');
define('TABLE_HEADING_PRIORITY','Priority');
define('TABLE_HEADING_STATUS','Status');
define('TABLE_HEADING_TICKET_SUBJECT','Subject');

define('TEXT_ALL_TICKETS','all');
define('TEXT_ALL_DEPARTMENTS','all');
define('TEXT_ALL_PRIORITYS','all');
define('TEXT_ADMIN','Admin');
define('TEXT_BY', 'from');
define('TEXT_CUSTOMERS_EMAIL','Email:');
define('TEXT_CUSTOMERS_ID','Customerid:');
define('TEXT_CUSTOMERS_NAME','Name:');
define('TEXT_CUSTOMERS_ORDERS_ID','Orderid.:');
define('TEXT_CUSTOMER_LOGIN_YES', 'Customer must be logged in to view the ticket');
define('TEXT_CUSTOMER_LOGIN_NO', 'Customer must not be logged in to view the ticket');
define('TEXT_COMMENT','Reply:');
define('TEXT_DATE','Date:');
define('TEXT_DATE_TICKET_CREATED','Ticket created: ');
define('TEXT_DATE_TICKET_LAST_MODIFIED','last change: '); 
define('TEXT_DATE_TICKET_LAST_CUSTOMER_MODIFIED','letzte Customerchange:');
define('TEXT_DEPARTMENT','Department: ');
define('TEXT_DISPLAY_NUMBER_OF_TICKET','Display <b>%d</b> to <b>%d</b> (of <b>%d</b> Tickets)');
define('TEXT_INSERT','Insert');
define('TEXT_OPENED', 'opend:');
define('TEXT_PRIORITY','Priority: ');
define('TEXT_REPLY','Reply');
define('TEXT_STATUS', 'Status: ');
define('TEXT_TICKET_NR','TicketNr.: ');

define('TEXT_INFO_HEADING_DELETE_TICKET','Are you sure you want to delete this ticket?');

define('TICKET_EMAIL_SUBJECT', 'Update of your Support-Ticket ');
define('TICKET_EMAIL_message_HEADER',"Your Support Ticket has been worked on.\nYou can view the changes at:");
define('TICKET_EMAIL_message_FOOTER',"If you have more Questions, please use our supporticketsystem\n\nDo not reply to this email.");

define('SUCCESS_TICKET_UPDATED','Ticket has been updated');
define('ERROR_TICKET_DOES_NOT_EXIST','Error: Ticket does not exist!');
define('WARNING_TICKET_NOT_UPDATED','Ticket has not been updated!');
define('WARNING_ENTRY_TO_SHORT', 'The length of the reply is to short!');
?>
