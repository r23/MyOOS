<?php
/* ----------------------------------------------------------------------
   $Id: ticket_view.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

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
define('HEADING_TITLE_DEPARTMENT','Ticket Abteilung:');
define('HEADING_TITLE_PRIORITY','Ticket Priorit�:');

define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_CUSTOMER_ID','Knr.');
define('TABLE_HEADING_DATE','letzte &Auml;nderung');
define('TABLE_HEADING_DEPARTMENT','Abteilung');
define('TABLE_HEADING_NAME','Name');
define('TABLE_HEADING_ORDER_ID', 'OrderID');
define('TABLE_HEADING_PRIORITY','Priorit&auml;t');
define('TABLE_HEADING_STATUS','Status');
define('TABLE_HEADING_TICKET_SUBJECT','Betreff');

define('TEXT_ALL_TICKETS','alle');
define('TEXT_ALL_DEPARTMENTS','alle');
define('TEXT_ALL_PRIORITYS','alle');
define('TEXT_ADMIN','Admin');
define('TEXT_BY', 'von');
define('TEXT_CUSTOMERS_EMAIL','Email:');
define('TEXT_CUSTOMERS_ID','Kundennr.:');
define('TEXT_CUSTOMERS_NAME','Name:');
define('TEXT_CUSTOMERS_ORDERS_ID','Bestellnr.:');
define('TEXT_CUSTOMER_LOGIN_YES', 'Kunde mu�eingeloggt sein um das Ticket zu sehen');
define('TEXT_CUSTOMER_LOGIN_NO', 'Kunde mu�nicht eingeloggt sein um das Ticket zu sehen');
define('TEXT_COMMENT','Antwort:');
define('TEXT_DATE','Datum:');
define('TEXT_DATE_TICKET_CREATED','Ticket erstellt: ');
define('TEXT_DATE_TICKET_LAST_MODIFIED','letzte �derung: '); 
define('TEXT_DATE_TICKET_LAST_CUSTOMER_MODIFIED','letzte Kunden&auml;nderung');
define('TEXT_DEPARTMENT','Abteilung: ');
define('TEXT_DISPLAY_NUMBER_OF_TICKET','Angezeigt werden <b>%d</b> bis <b>%d</b> (von insgesamt <b>%d</b> Tickets)');
define('TEXT_INSERT','Einfgen');
define('TEXT_OPENED', 'er&ouml;ffnet am:');
define('TEXT_PRIORITY','Priorit&auml;t: ');
define('TEXT_REPLY','Antwort');
define('TEXT_STATUS', 'Status: ');
define('TEXT_TICKET_NR','TicketNr.: ');

define('TEXT_INFO_HEADING_DELETE_TICKET','Sind Sie sicher, das Sie dieses Ticket l&ouml;schen m&ouml;chten?');

define('TICKET_EMAIL_SUBJECT', 'Update Ihres Support-Ticket ');
define('TICKET_EMAIL_message_HEADER',"Ihre Anfrage wurde soeben bearbeitet..\nSie k�nen Ihre Anfrage jederzeit unter folgenden Link einsehen:");
define('TICKET_EMAIL_message_FOOTER',"Sollten noch fragen offen sein benutzen Sie bitte unser Supportticketsystem\n\nBitte nicht auf diese Email antworten, sondern benutzen Sie das Supportticketsystem.");

define('SUCCESS_TICKET_UPDATED','Das Ticket wurde aktualisiert');
define('ERROR_TICKET_DOES_NOT_EXIST','Fehler: Ticket existiert nicht!');
define('WARNING_TICKET_NOT_UPDATED','Das Ticket wurde nicht upgedated!');
define('WARNING_ENTRY_TO_SHORT', 'Die Mindestl&auml;nger der Antwort wurde nicht erf&uuml;llt!');
?>
