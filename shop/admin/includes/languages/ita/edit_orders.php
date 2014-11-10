<?php
/* ----------------------------------------------------------------------
   $Id: edit_orders.php,v 1.3 2007/06/13 16:39:12 r23 Exp $

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

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Modifica Ordine');
define('HEADING_TITLE_SEARCH', 'ID Ordine:');
define('HEADING_TITLE_STATUS', 'Stato:');
define('ADDING_TITLE', 'Aggiungi un prodotto all\' ordine');

define('ENTRY_UPDATE_TO_CC', '(Aggiorna il metodo di pagamento come "Carta di Credito" per mostrare dei campi aggiuntivi.)');
define('TABLE_HEADING_COMMENTS', 'Note - Commenti');
define('TABLE_HEADING_CUSTOMERS', 'Clienti');
define('TABLE_HEADING_ORDER_TOTAL', 'Totale Ordine');
define('TABLE_HEADING_DATE_PURCHASED', 'Data Ordine');
define('TABLE_HEADING_STATUS', 'Nuovo Stato');
define('TABLE_HEADING_ACTION', 'Azione');
define('TABLE_HEADING_QUANTITY', 'Qta');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Mod. Prodotto');
define('TABLE_HEADING_PRODUCTS', 'Prodotti');
define('TABLE_HEADING_TAX', 'Tasse');
define('TABLE_HEADING_TOTAL', 'Totale');
define('TABLE_HEADING_UNIT_PRICE', 'Prezzo (escl.)');
define('TABLE_HEADING_TOTAL_PRICE', 'Totale (escl.)');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Cliente notificato');
define('TABLE_HEADING_DATE_ADDED', 'Data aggiunta');

define('ENTRY_CUSTOMER', 'Informazioni Cliente');
define('ENTRY_CUSTOMER_NAME', 'Nome');
define('ENTRY_CUSTOMER_COMPANY', 'Azienda');
define('ENTRY_CUSTOMER_ADDRESS', 'Indirizzo');
define('ENTRY_ADDRESS', 'Indirizzo');
define('ENTRY_CUSTOMER_SUBURB', 'Frazione');
define('ENTRY_CUSTOMER_CITY', 'Città');
define('ENTRY_CUSTOMER_STATE', 'Stato');
define('ENTRY_CUSTOMER_POSTCODE', 'CAP');
define('ENTRY_CUSTOMER_COUNTRY', 'Nazione');

define('ENTRY_SOLD_TO', 'Venduto a:');
define('ENTRY_DELIVERY_TO', 'Consegnato a:');
define('ENTRY_SHIP_TO', 'Spedito a:');
define('ENTRY_SHIPPING_ADDRESS', 'Indirizzo di Spedizione');
define('ENTRY_BILLING_ADDRESS', 'Indirizzo di Fatturazione');
define('ENTRY_PAYMENT_METHOD', 'Metodo di Pagamento:');
define('ENTRY_CREDIT_CARD_TYPE', 'Typo Carta:');
define('ENTRY_CREDIT_CARD_OWNER', 'Intestatario Carta:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Numero Carta:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Scadenza Carta:');
define('ENTRY_SUB_TOTAL', 'Sub Totale:');
define('ENTRY_TAX', 'Tasse:');
define('ENTRY_SHIPPING', 'Spedizione:');
define('ENTRY_TOTAL', 'Totale:');
define('ENTRY_DATE_PURCHASED', 'Data di Acquisto:');
define('ENTRY_STATUS', 'Stato Ordine:');
define('ENTRY_DATE_LAST_UPDATED', 'Ultimo aggiornamento:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notifica Cliente:');
define('ENTRY_NOTIFY_COMMENTS', 'Invia Commenti:');
define('ENTRY_PRINTABLE', 'Stampa fattura');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Cancella Ordine');
define('TEXT_INFO_DELETE_INTRO', 'Cancellare realmente l\'ordine ?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Modifica Quantità');
define('TEXT_DATE_ORDER_CREATED', 'Creato il:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Ultimo aggiornamento:');
define('TEXT_DATE_ORDER_ADDNEW', 'Aggiungi nuovo prodotto');
define('TEXT_INFO_PAYMENT_METHOD', 'Metodo di Pagamento:');

define('TEXT_ALL_ORDERS', 'Tutti gli ordini');
define('TEXT_NO_ORDER_HISTORY', 'Nessun ordine trovato');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Il tuo ordine è stato aggiornato');
define('EMAIL_TEXT_ORDER_NUMBER', 'Numero ordine:');
define('EMAIL_TEXT_INVOICE_URL', 'Fattura dettagliata a questo URL:');
define('EMAIL_TEXT_DATE_ORDERED', 'Data ordine:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Lo status del tuo ordine è cambiato.' . "\n\n" . 'Nuovo status: %s' . "\n\n" . 'Per qualsiasi informazione rispondi a questa email.' . "\n\n" . 'Cordiali saluti,' . "\n". 'Lo staff' . "\n");
define('EMAIL_TEXT_STATUS_UPDATE2', 'In caso avesse dubbi o dmande, risponda a questa mail.' . "\n\n" . 'Calorosi saluti dai suoi amici di ' . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Commenti' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Errore: Nessun ordine.');
define('SUCCESS_ORDER_UPDATED', 'Completato: L\' ordine è stato completato correttamente.');
define('WARNING_ORDER_NOT_UPDATED', 'Attenzione: nessun cambiamento effettuato.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Scegli un prodotto');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Scegli una opzione');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Il prodotto non ha opzioni quindi andiamo avanti...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'unità di questo prodotto');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Aggiungi');

?>
