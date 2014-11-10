<?php
/* ----------------------------------------------------------------------
   $Id: ticket_view.php,v 1.1 2007/06/13 16:39:16 r23 Exp $

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


define('HEADING_TITLE', 'Hulpaanvragen');
define('HEADING_TITLE_STATUS','Aanvraag status:');
define('HEADING_TITLE_DEPARTMENT','Aanvraag afdeling:');
define('HEADING_TITLE_PRIORITY','Aanvraag prioriteit:');

define('TABLE_HEADING_ACTION', 'Actie');
define('TABLE_HEADING_CUSTOMER_ID','Klantnr.');
define('TABLE_HEADING_DATE','Laatste verandering');
define('TABLE_HEADING_DEPARTMENT','Afdeling');
define('TABLE_HEADING_NAME','Naam');
define('TABLE_HEADING_ORDER_ID', 'BestelID');
define('TABLE_HEADING_PRIORITY','Prioriteit');
define('TABLE_HEADING_STATUS','Status');
define('TABLE_HEADING_TICKET_SUBJECT','Onderwerp');

define('TEXT_ALL_TICKETS','Alle');
define('TEXT_ALL_DEPARTMENTS','Alle');
define('TEXT_ALL_PRIORITYS','Alle');
define('TEXT_ADMIN','Beheerder');
define('TEXT_BY', 'van');
define('TEXT_CUSTOMERS_EMAIL','Email:');
define('TEXT_CUSTOMERS_ID','Klantnr.:');
define('TEXT_CUSTOMERS_NAME','Naam:');
define('TEXT_CUSTOMERS_ORDERS_ID','Bestelnr.:');
define('TEXT_CUSTOMER_LOGIN_YES', 'Klant moet ingelogd zijn om de aanvraag te zien.');
define('TEXT_CUSTOMER_LOGIN_NO', 'Klant hoeft niet ingelogd te zijn om de aanvraag te zien.');
define('TEXT_COMMENT','Antwoord:');
define('TEXT_DATE','Datum:');
define('TEXT_DATE_TICKET_CREATED','Aanvraag aangemaakt: ');
define('TEXT_DATE_TICKET_LAST_MODIFIED','Laatste verandering: '); 
define('TEXT_DATE_TICKET_LAST_CUSTOMER_MODIFIED','Laatste verandering');
define('TEXT_DEPARTMENT','Afdeling: ');
define('TEXT_DISPLAY_NUMBER_OF_TICKET','Toon <b>%d</b> tot <b>%d</b> (van <b>%d</b> aanvragen.)');
define('TEXT_INSERT','Invoegen');
define('TEXT_OPENED', 'Geopend op:');
define('TEXT_PRIORITY','Prioriteit: ');
define('TEXT_REPLY','Antwoord');
define('TEXT_STATUS', 'Status: ');
define('TEXT_TICKET_NR','Aanvraagnr.: ');

define('TEXT_INFO_HEADING_DELETE_TICKET','Weet u zeker dat u deze aanvraag wissen wilt?');

define('TICKET_EMAIL_SUBJECT', 'Update van uw hulpaanvraag ');
define('TICKET_EMAIL_message_HEADER',"Uw aanvraag werd zonet verwerkt..\nU kan uw aanvraag op ieder moment onder de volgende link inzien:");
define('TICKET_EMAIL_message_FOOTER',"Indien u nog vragen hebt maak dan gebruik van ons hulpaanvraag systeem\n\nA.u.b. niet op deze email antwoorden, maar gebruik het hulpaanvraagsysteem.");

define('SUCCESS_TICKET_UPDATED','De aanvraag werd geactualiseerd');
define('ERROR_TICKET_DOES_NOT_EXIST','Fout: Aanvraag bestaat niet!');
define('WARNING_TICKET_NOT_UPDATED','De aanvraag werd niet geupdate!');
define('WARNING_ENTRY_TO_SHORT', 'De minimale lengte van het antwoord wordt niet bereikt!');
?>
