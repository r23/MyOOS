<?php
/* ----------------------------------------------------------------------
   $Id: edit_orders.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: edit_orders.php,v 1.25 2003/08/07 00:28:44 jwh 
   ----------------------------------------------------------------------
   Order Editor
   
   Contribution based on:
   
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Aanpassen bestelling');
define('HEADING_TITLE_SEARCH', 'Bestel ID:');
define('HEADING_TITLE_STATUS', 'Status:');
define('ADDING_TITLE', 'Een produkt aan de bestelling toevoegen');

define('ENTRY_UPDATE_TO_CC', '(Update naat <b>Credietkaart</b> om de CC velden te zien.)');
define('TABLE_HEADING_COMMENTS', 'Commentaar');
define('TABLE_HEADING_CUSTOMERS', 'Klanten');
define('TABLE_HEADING_ORDER_TOTAL', 'Bestel totaal');
define('TABLE_HEADING_DATE_PURCHASED', 'Datum van aankoop');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Actie');
define('TABLE_HEADING_QUANTITY', 'Hoeveelheid.');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Produkten');
define('TABLE_HEADING_TAX', 'B.T.W.');
define('TABLE_HEADING_TOTAL', 'Totaal');
define('TABLE_HEADING_UNIT_PRICE', 'Stuksprijs');
define('TABLE_HEADING_TOTAL_PRICE', 'Totaalprijs');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Klant bericht gestuurd');
define('TABLE_HEADING_DATE_ADDED', 'Datum toegevoegd');

define('ENTRY_CUSTOMER', 'Klant:');
define('ENTRY_CUSTOMER_NAME', 'Naam');
define('ENTRY_CUSTOMER_COMPANY', 'Bedrijf');
define('ENTRY_CUSTOMER_ADDRESS', 'Adres');
define('ENTRY_CUSTOMER_SUBURB', 'Stadsdeel');
define('ENTRY_CUSTOMER_CITY', 'Woonplaats');
define('ENTRY_CUSTOMER_STATE', 'Provincie');
define('ENTRY_CUSTOMER_POSTCODE', 'Postcode');
define('ENTRY_CUSTOMER_COUNTRY', 'Land');

define('ENTRY_SOLD_TO', 'Verkocht aan:');
define('ENTRY_DELIVERY_TO', 'Geleverd aan:');
define('ENTRY_SHIP_TO', 'Verstuurd naar:');
define('ENTRY_SHIPPING_ADDRESS', 'Verzendadres:');
define('ENTRY_BILLING_ADDRESS', 'Factuuradres:');
define('ENTRY_PAYMENT_METHOD', 'Betaalwijze:');
define('ENTRY_CREDIT_CARD_TYPE', 'Credietkaarttype:');
define('ENTRY_CREDIT_CARD_OWNER', 'Credietkaarteigenaar:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Credietkaartnummer:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Credietkaart vervalt:');
define('ENTRY_SUB_TOTAL', 'Subtotaal:');
define('ENTRY_TAX', 'B.T.W.:');
define('ENTRY_SHIPPING', 'Vrachtkosten:');
define('ENTRY_TOTAL', 'Totaal:');
define('ENTRY_DATE_PURCHASED', 'Aankoopdatum:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Datum laatste update:');
define('ENTRY_NOTIFY_CUSTOMER', 'Klant berichten:');
define('ENTRY_NOTIFY_COMMENTS', 'Commentaar toevoegen:');
define('ENTRY_PRINTABLE', 'Factuur afdrukken');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Verwijder bestelling');
define('TEXT_INFO_DELETE_INTRO', 'weet u zeker dat u deze bestelling wilt wissen?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Produkt op voorraad plaatsen');
define('TEXT_DATE_ORDER_CREATED', 'Aanmaakdatum:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Laatste verandering:');
define('TEXT_DATE_ORDER_ADDNEW', 'Produkt toevoegen.');
define('TEXT_INFO_PAYMENT_METHOD', 'Betaalwijze:');

define('TEXT_ALL_ORDERS', 'Alle bestellingen');
define('TEXT_NO_ORDER_HISTORY', 'Geen besteloverzicht beschikbaar');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Update bestelling');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellingsnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Gedetailleerde factuur:');
define('EMAIL_TEXT_DATE_ORDERED', 'Datum bestelling:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Uw bestelling is opgewaardeerd naar de volgende status.' . "\n\n" . 'Nieuwe status: %s' . "\n\n" . 'Reageer op deze email als u vragen hebt.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Het commentaar toegevoegd aan uw bestelling is' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fout: Bestelling bestaat niet.');
define('SUCCESS_ORDER_UPDATED', 'Succes: Bestelling is succesvol geupdated.');
define('WARNING_ORDER_NOT_UPDATED', 'Waarschuwing: Niets om te veranderen. de bestelling is niet geupdated.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Selecteer produkt');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Selecteer opties');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Geen opties: Overgeslagen..');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'Hoeveelheid.');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Nu toevoegen');


?>
