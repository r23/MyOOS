<?php
/* ----------------------------------------------------------------------
   $Id: orders.php,v 1.4 2007/06/21 15:34:11 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders.php,v 1.24 2003/02/09 13:15:22 thomasamoulton
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

define('HEADING_TITLE', 'Ordini');
define('HEADING_TITLE_SEARCH', 'ID Ordine:');
define('HEADING_TITLE_STATUS', 'Stato:');

define('TABLE_HEADING_COMMENTS', 'Commenti');
define('TABLE_HEADING_CUSTOMERS', 'Clienti');
define('TABLE_HEADING_ORDER_TOTAL', 'Totale Ordine');
define('TABLE_HEADING_DATE_PURCHASED', 'Data di acquisto');
define('TABLE_HEADING_STATUS', 'Stato');
define('TABLE_HEADING_ACTION', 'Azione');
define('TABLE_HEADING_QUANTITY', 'Quantità');
define('TABLE_HEADING_PRODUCTS_SERIAL_NUMBER', 'Numero di Serie');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modello');
define('TABLE_HEADING_PRODUCTS', 'Prodotti');
define('TABLE_HEADING_TAX', 'Tassa');
define('TABLE_HEADING_TOTAL', 'Totale');
define('TABLE_HEADING_STATUS', 'Stato');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Prezzo (ex)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Prezzo (inc)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Totale (ex)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Totale (inc)');
define('TABLE_HEADING_STATUS', 'Stato');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Notifica Cliente');
define('TABLE_HEADING_DATE_ADDED', 'Data di inserimento');

define('ENTRY_CUSTOMER', 'Cliente:');
define('ENTRY_SOLD_TO', 'VENDUTO A:');
define('ENTRY_STREET_ADDRESS', 'Via:');
define('ENTRY_SUBURB', 'Località:');
define('ENTRY_CITY', 'Città:');
define('ENTRY_POST_CODE', 'CAP:');
define('ENTRY_STATE', 'Stato:');
define('ENTRY_COUNTRY', 'Regione:');
define('ENTRY_TELEPHONE', 'Telefono:');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_DELIVERY_TO', 'Spedizione a:');
define('ENTRY_SHIP_TO', 'SPEDITO A:');
define('ENTRY_SHIPPING_ADDRESS', 'Indirizzo Spedizione:');
define('ENTRY_BILLING_ADDRESS', 'Indirizzo Fatturazione:');
define('ENTRY_ORDER_NUMBER', 'Ordine #');
define('ENTRY_ORDER_DATE' 'Data Ordine');
define('ENTRY_CAMPAIGNS', 'How you came to us?');
define('ENTRY_PAYMENT_METHOD', 'Metodo di Pagamento:');
define('ENTRY_CREDIT_CARD_TYPE', 'Tipo Carta di Credito:');
define('ENTRY_CREDIT_CARD_OWNER', 'Proprietario Carta di Credito:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Numero Carta di Credito:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Scadenza Carta di Credito:');
define('ENTRY_SUB_TOTAL', 'Sub-Totale:');
define('ENTRY_TAX', 'Tassa:');
define('ENTRY_SHIPPING', 'Spedizione:');
define('ENTRY_TOTAL', 'Totale:');
define('ENTRY_DATE_PURCHASED', 'Data di Acquisto:');
define('ENTRY_STATUS', 'Stato:');
define('ENTRY_DATE_LAST_UPDATED', 'Data ultimo aggiornamento:');
define('ENTRY_NOTIFY_CUSTOMER', 'Notifica Cliente:');
define('ENTRY_NOTIFY_COMMENTS', 'Aggiungi Commenti:');
define('ENTRY_PRINTABLE', 'Stampa Fattura');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Cancella Ordine');
define('TEXT_INFO_DELETE_INTRO', 'Sicuro di voler cancellare questo ordine?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Ristabilisci la quantità del Prodotto');
define('TEXT_DATE_ORDER_CREATED', 'Data di creazione:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Ultima modifica:');
define('TEXT_INFO_PAYMENT_METHOD', 'Metodo di Pagamento:');

define('TEXT_ALL_ORDERS', 'Tutti gli Ordini');
define('TEXT_NO_ORDER_HISTORY', 'Nessuna Cronologia Ordini disponibile');

define('ERROR_ORDER_DOES_NOT_EXIST', 'Errore: Ordine inesistente.');
define('SUCCESS_ORDER_UPDATED', 'Operazione Riuscita: Ordine aggiornato con successo.');
define('WARNING_ORDER_NOT_UPDATED', 'Attenzione: Nessun cambiamento. L\'ordine on è stato aggiornato.');

define('TEXT_BANK', 'Banca');
define('TEXT_BANK_OWNER', 'Intestatario:');
define('TEXT_BANK_NUMBER', 'Numero Conto:');
define('TEXT_BANK_BLZ', 'BLZ:');
define('TEXT_BANK_NAME', 'Banca:');
define('TEXT_BANK_FAX', 'L\'addebito è cofermato via fax.');
define('TEXT_BANK_STATUS', 'Stato di test:');
define('TEXT_BANK_PRZ', 'Metodi di test:');

define('TEXT_BANK_ERROR_1', 'Il numero abbonamento non corrisponde al codice della banca!');
define('TEXT_BANK_ERROR_2', 'Per questo abbonamento non è definito nessun metodo di test!');
define('TEXT_BANK_ERROR_3', 'Abbonamento non verificabile! Il metodo di test non è implementato');
define('TEXT_BANK_ERROR_4', 'Abbonamento tecnicamente non verificabile!');
define('TEXT_BANK_ERROR_5', 'Numero Codice banca non trovato!');
define('TEXT_BANK_ERROR_8', 'Nessun codice bancario dato(indicato)!');
define('TEXT_BANK_ERROR_9', 'Nessun numero abbonamento dato(indicato)!');
define('TEXT_BANK_ERRORCODE', 'Codice di errore:');

?>
