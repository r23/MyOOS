<?php
/* ----------------------------------------------------------------------
   $Id: orders.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders.php,v 1.27 2003/02/16 02:09:20 harley_vb 
   ----------------------------------------------------------------------
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

define('HEADING_TITLE', 'Bestellingen');
define('HEADING_TITLE_SEARCH', 'Bestelnr.:');
define('HEADING_TITLE_STATUS', 'Status:');

define('TABLE_HEADING_COMMENTS', 'Commentaar');
define('TABLE_HEADING_CUSTOMERS', 'Klant');
define('TABLE_HEADING_ORDER_TOTAL', 'Totaalbedrag');
define('TABLE_HEADING_DATE_PURCHASED', 'Besteldatum');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Actie');
define('TABLE_HEADING_QUANTITY', 'Aantal');
define('TABLE_HEADING_PRODUCTS_SERIAL_NUMBER', 'SN');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Artikelnr.');
define('TABLE_HEADING_PRODUCTS', 'Artikel');
define('TABLE_HEADING_TAX', 'B.T.W.');
define('TABLE_HEADING_TOTAL', 'Totaalbedrag');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Prijs (excl.)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Prijs (incl.)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Totaal (excl.)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Totaal (incl.)');

define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Klant mededelen');
define('TABLE_HEADING_DATE_ADDED', 'Toegevoegd op:');

define('ENTRY_CUSTOMER', 'Klant:');
define('ENTRY_SOLD_TO', 'Klant:');
define('ENTRY_STREET_ADDRESS', 'Straat:');
define('ENTRY_SUBURB', 'Evt. toevoeging:');
define('ENTRY_CITY', 'Woonplaats:');
define('ENTRY_POST_CODE', 'Postcode:');
define('ENTRY_STATE', 'Provincie:');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_TELEPHONE', 'Telefoon:');
define('ENTRY_EMAIL_ADDRESS', 'Emailadres:');
define('ENTRY_DELIVERY_TO', 'Afleveradres:');
define('ENTRY_SHIP_TO', 'Afleveradres:');
define('ENTRY_SHIPPING_ADDRESS', 'Verzendadres:');
define('ENTRY_BILLING_ADDRESS', 'Factuuradres:');
define('ENTRY_ORDER_NUMBER', 'Order #');
define('ENTRY_ORDER_DATE', 'Order Date & Time');
define('ENTRY_CAMPAIGNS', 'How you came to us?');
define('ENTRY_PAYMENT_METHOD', 'Betaalwijze:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credietkaarttype:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credietkaarteigenaar:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credietkaartnummer:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credietkaart geldig tot:');
define('ENTRY_SUB_TOTAL', 'Subtotaal:');
define('ENTRY_TAX', 'B.T.W.:');
define('ENTRY_SHIPPING', 'Verzendkosten:');
define('ENTRY_TOTAL', 'Totaalbedrag:');
define('ENTRY_DATE_PURCHASED', 'Besteldatum:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Laatste actualisering op:');
define('ENTRY_NOTIFY_CUSTOMER', 'Klant berichten:');
define('ENTRY_NOTIFY_COMMENTS', 'Commentaar meezenden:');
define('ENTRY_PRINTABLE', 'factuur afdrukken');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Bestelling wissen');
define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze bestelling wilt wissen?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Artikelenaantal bij de voorraad toegevoegd');
define('TEXT_DATE_ORDER_CREATED', 'aangemaakt op:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Laatste verandering:');
define('TEXT_INFO_PAYMENT_METHOD', 'Betaalwijze:');

define('TEXT_ALL_ORDERS', 'Alle Bestellingen');
define('TEXT_NO_ORDER_HISTORY', 'Geen bestelgeschiedenis aanwezig');

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fout: De bestelling bestaat niet!.');
define('SUCCESS_ORDER_UPDATED', 'Attentie: De bestellung werd succesvis geactualiseerd.');
define('WARNING_ORDER_NOT_UPDATED', 'Attentie: Er is niets veranderd . Daarom is de bestelling niet geactualiserd.');

define('TEXT_BANK', 'Incasso');
define('TEXT_BANK_OWNER', 'Rekeninghouder:');
define('TEXT_BANK_NUMBER', 'Rekeningnummer:');
define('TEXT_BANK_BLZ', 'IBAN nummer:');
define('TEXT_BANK_NAME', 'Bank:');
define('TEXT_BANK_FAX', 'Incassomachtiging wordt per fax bevestigd');
define('TEXT_BANK_STATUS', 'Controlestatus:');
define('TEXT_BANK_PRZ', 'Controlevoortgang:');

define('TEXT_BANK_ERROR_1', 'Rekeningnummer komt niet overeen met IBAN nummer!');
define('TEXT_BANK_ERROR_2', 'Voor dir rekeningnummer is geen controlevoortgang gedefinieerd!');
define('TEXT_BANK_ERROR_3', 'Rekeningnummer niet te controleren! Controle niet geimplementeerd');
define('TEXT_BANK_ERROR_4', 'Rekeningnummer technisch niet te controleren!');
define('TEXT_BANK_ERROR_5', 'IBAN nummer niet gevonden!');
define('TEXT_BANK_ERROR_8', 'Geen IBAN nummer opgegeven!');
define('TEXT_BANK_ERROR_9', 'Geen rekeningnummer opgegeven!');
define('TEXT_BANK_ERRORCODE', 'Foutcode:');
?>
