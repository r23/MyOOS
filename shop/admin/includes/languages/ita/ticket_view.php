<?php
/* ----------------------------------------------------------------------
   $Id: ticket_view.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

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

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Ticket di Assistenza');
define('HEADING_TITLE_STATUS','Stao Ticke:');
define('HEADING_TITLE_DEPARTMENT','Dipartimento Ticket:');
define('HEADING_TITLE_PRIORITY','Priorità Ticket:');

define('TABLE_HEADING_ACTION', 'Azione');

define('TABLE_HEADING_CUSTOMER_ID','Cid.');
define('TABLE_HEADING_DATE','ultima modifica');
define('TABLE_HEADING_DEPARTMENT','Dipartimento');
define('TABLE_HEADING_NAME','Nome');
define('TABLE_HEADING_ORDER_ID', 'IdOrdine');
define('TABLE_HEADING_PRIORITY','Priorità');
define('TABLE_HEADING_STATUS','Stato');
define('TABLE_HEADING_TICKET_SUBJECT','Soggetto');

define('TEXT_ALL_TICKETS','tutti');
define('TEXT_ALL_DEPARTMENTS','tutti');
define('TEXT_ALL_PRIORITYS','tutti');
define('TEXT_ADMIN','Amministrazione');
define('TEXT_BY', 'da');
define('TEXT_CUSTOMERS_EMAIL','Email:');
define('TEXT_CUSTOMERS_ID','CodiceCliente:');
define('TEXT_CUSTOMERS_NAME','Nome:');
define('TEXT_CUSTOMERS_ORDERS_ID','IdOrdine.:');
define('TEXT_CUSTOMER_LOGIN_YES', 'I Clienti devono essere autenticati per vedere i ticket');
define('TEXT_CUSTOMER_LOGIN_NO', 'I Clienti non devono essere autenticati per vedere i ticket');
define('TEXT_COMMENT','Rispondi:');
define('TEXT_DATE','Data:');
define('TEXT_DATE_TICKET_CREATED','Ticket Creato: ');
define('TEXT_DATE_TICKET_LAST_MODIFIED','ultima modifica: ');
define('TEXT_DATE_TICKET_LAST_CUSTOMER_MODIFIED','ultima modifica cliente:');
define('TEXT_DEPARTMENT','Dipartimento: ');
define('TEXT_DISPLAY_NUMBER_OF_TICKET','Visualizza <b>%d</b> di <b>%d</b> (di <b>%d</b> Tickets)');
define('TEXT_INSERT','Inserisci');
define('TEXT_OPENED', 'aperto:');
define('TEXT_PRIORITY','Priorità: ');
define('TEXT_REPLY','Rispondi');
define('TEXT_STATUS', 'Stato: ');
define('TEXT_TICKET_NR','TicketNum.: ');

define('TEXT_INFO_HEADING_DELETE_TICKET','Sei sicuro di voler cancellare questo ticket?');

define('TICKET_EMAIL_SUBJECT', 'Aggiornamento per la tua richiesta di assistenza');
define('TICKET_EMAIL_message_HEADER',"La tua richiesta di assistenza è stata elaborata.\nPuoi vedere le modifiche su:");
define('TICKET_EMAIL_message_FOOTER',"Se hai ulteriori domande da fare, utilizzi per cortesia il nostro sistema di assistenza.\n\nNon rispondere a questa mail.");

define('SUCCESS_TICKET_UPDATED','Il Ticket è stato aggiornato');
define('ERROR_TICKET_DOES_NOT_EXIST','Errore: questo Ticket non esiste!');
define('WARNING_TICKET_NOT_UPDATED','Il Ticket non è stato aggiornato!');
define('WARNING_ENTRY_TO_SHORT', 'La lunghezza della risposta è troppo corta!!');

?>
