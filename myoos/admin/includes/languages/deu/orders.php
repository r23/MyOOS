<?php
/**
   ----------------------------------------------------------------------
   $Id: orders.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders.php,v 1.27 2003/02/16 02:09:20 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */
/**
   ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Bestellungen');
define('HEADING_TITLE_SEARCH', 'Bestell-Nr.:');
define('HEADING_TITLE_STATUS', 'Status:');

define('TABLE_HEADING_COMMENTS', 'Kommentar');
define('TABLE_HEADING_CUSTOMERS', 'Kunde');
define('TABLE_HEADING_ORDER_TOTAL', 'Gesamtwert');
define('TABLE_HEADING_DATE_PURCHASED', 'Bestelldatum');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_QUANTITY', 'Anzahl');
define('TABLE_HEADING_PRODUCTS_SERIAL_NUMBER', 'SN');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Artikel-Nr.');
define('TABLE_HEADING_PRODUCTS', 'Artikel');
define('TABLE_HEADING_TAX', 'MwSt.');
define('TABLE_HEADING_TOTAL', 'Gesamtsumme');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Preis (exkl.)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Preis (inkl.)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (exkl.)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inkl.)');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Kunde benachrichtigt');
define('TABLE_HEADING_DATE_ADDED', 'hinzugefügt am:');

define('ENTRY_CUSTOMER', 'Kunde:');
define('ENTRY_SOLD_TO', 'Kunde:');
define('ENTRY_DELIVERY_TO', 'Lieferanschrift:');
define('ENTRY_SHIP_TO', 'Lieferanschrift:');
define('ENTRY_SHIPPING_ADDRESS', 'Versandadresse:');
define('ENTRY_BILLING_ADDRESS', 'Rechnungsadresse:');
define('ENTRY_ORDER_NUMBER', 'Bestellnummer');
define('ENTRY_ORDER_DATE', 'Bestelldatum');

define('ENTRY_PAYMENT_METHOD', 'Zahlungsweise:');
define('ENTRY_SUB_TOTAL', 'Zwischensumme:');
define('ENTRY_TAX', 'MwSt.:');
define('ENTRY_SHIPPING', 'Versandkosten:');
define('ENTRY_TOTAL', 'Gesamtsumme:');
define('ENTRY_DATE_PURCHASED', 'Bestelldatum:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'letzte Aktualisierung am:');
define('ENTRY_NOTIFY_CUSTOMER', 'Kunde benachrichtigen:');
define('ENTRY_NOTIFY_COMMENTS', 'Kommentare mitsenden:');
define('ENTRY_PRINTABLE', 'Rechnung drucken');

define('TEXT_YES', 'Ja, ich möchte bei Auslieferung des neuen Gerätes ein Altgerät unentgeltlich zurückgeben.');
define('TEXT_NO', 'Nein, ich möchte bei Auslieferung des neuen Gerätes kein Altgerät unentgeltlich zurückgeben.');


define('TEXT_INFO_HEADING_DELETE_ORDER', 'Bestellung löschen');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, das Sie diese Bestellung löschen möchten?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Artikelanzahl dem Lager gutschreiben');
define('TEXT_DATE_ORDER_CREATED', 'erstellt am:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'letzte Änderung:');
define('TEXT_INFO_PAYMENT_METHOD', 'Zahlungsweise:');

define('TEXT_ALL_ORDERS', 'Alle Bestellungen');
define('TEXT_NO_ORDER_HISTORY', 'Keine Bestellhistorie verfügbar');

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fehler: Die Bestellung existiert nicht!.');
define('SUCCESS_ORDER_UPDATED', 'Hinweis: Die Bestellung wurde erfolgreich aktualisiert.');
define('WARNING_ORDER_NOT_UPDATED', 'Hinweis: Es wurde nichts geändert. Daher wurde diese Bestellung nicht aktualisiert.');

define('TEXT_BANK', 'Bankeinzug');
define('TEXT_BANK_OWNER', 'Kontoinhaber:');
define('TEXT_BANK_NUMBER', 'Kontonummer:');
define('TEXT_BANK_BLZ', 'BLZ:');
define('TEXT_BANK_NAME', 'Bank:');
define('TEXT_BANK_STATUS', 'Prüfstatus:');
define('TEXT_BANK_PRZ', 'Prüfverfahren:');

define('TEXT_BANK_ERROR_1', 'Kontonummer stimmt nicht mit BLZ überein!');
define('TEXT_BANK_ERROR_2', 'Für diese Kontonummer ist kein Prüfverfahren definiert!');
define('TEXT_BANK_ERROR_3', 'Kontonummer nicht prüfbar! Prüfverfahren nicht implementiert');
define('TEXT_BANK_ERROR_4', 'Kontonummer technisch nicht prüfbar!');
define('TEXT_BANK_ERROR_5', 'Bankleitzahl nicht gefunden!');
define('TEXT_BANK_ERROR_8', 'Keine Bankleitzahl angegeben!');
define('TEXT_BANK_ERROR_9', 'Keine Kontonummer angegeben!');
define('TEXT_BANK_ERRORCODE', 'Fehlercode:');
