<?php
/**
   ----------------------------------------------------------------------
   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Bestellung bearbeiten');
define('HEADING_TITLE_SEARCH', 'Bestell-Nr.:');
define('HEADING_TITLE_STATUS', 'Status:');
define('ADDING_TITLE', 'Ein Produkt zur Bestellung hinzufügen');

define('ENTRY_UPDATE_TO_CC', '(Aktualisieren Sie auf <b>Kreditkarte</b>, um die CC-Felder anzuzeigen.)');
define('TABLE_HEADING_COMMENTS', 'Kommentar');
define('TABLE_HEADING_CUSTOMERS', 'Kunde');
define('TABLE_HEADING_ORDER_TOTAL', 'Gesamtwert');
define('TABLE_HEADING_DATE_PURCHASED', 'Bestelldatum');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TABLE_HEADING_QUANTITY', 'Anzahl.');
define('TABLE_HEADING_PRODUCTS_SERIAL_NUMBER', 'SN');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Artikel-Nr.');
define('TABLE_HEADING_PRODUCTS', 'Artikel');
define('TABLE_HEADING_TAX', 'MwSt');
define('TABLE_HEADING_TOTAL', 'Gesamtsumme');
define('TABLE_HEADING_UNIT_PRICE', 'Preis pro Einheit');
define('TABLE_HEADING_TOTAL_PRICE', 'Gesamtpreis');


define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Kunde benachrichtigt');
define('TABLE_HEADING_DATE_ADDED', 'hinzugefügt am:');

define('ENTRY_CUSTOMER', 'Kunde:');
define('ENTRY_CUSTOMER_NAME', 'Name');
define('ENTRY_CUSTOMER_COMPANY', 'Unternehmen');
define('ENTRY_CUSTOMER_ADDRESS', 'Adresse');
define('ENTRY_CUSTOMER_CITY', 'Stadt');
define('ENTRY_CUSTOMER_STATE', 'Bundesland');
define('ENTRY_CUSTOMER_POSTCODE', 'Postleitzahl');
define('ENTRY_CUSTOMER_COUNTRY', 'Land');

define('ENTRY_SOLD_TO', 'Kunde:');
define('ENTRY_DELIVERY_TO', 'Lieferung an:');
define('ENTRY_SHIP_TO', 'Versand an:');
define('ENTRY_SHIPPING_ADDRESS', 'Lieferadresse:');
define('ENTRY_BILLING_ADDRESS', 'Rechnungsadresse:');
define('ENTRY_PAYMENT_METHOD', 'Zahlungsmethode:');
define('ENTRY_SUB_TOTAL', 'Zwischensumme:');
define('ENTRY_TAX', 'Steuer:');
define('ENTRY_SHIPPING', 'Versand:');
define('ENTRY_TOTAL', 'Gesamt:');
define('ENTRY_DATE_PURCHASED', 'Kaufdatum:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_DATE_LAST_UPDATED', 'Datum der letzten Aktualisierung:');
define('ENTRY_NOTIFY_CUSTOMER', 'Kunde benachrichtigen:');
define('ENTRY_NOTIFY_COMMENTS', 'Kommentare anhängen:');
define('ENTRY_PRINTABLE', 'Rechnung drucken');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Auftrag löschen');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie diese Bestellung löschen wollen?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Produktmenge wieder einlagern');
define('TEXT_DATE_ORDER_CREATED', 'Erstellungsdatum:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Letzte Änderung:');
define('TEXT_DATE_ORDER_ADDNEW', 'Produkt hinzufügen.');
define('TEXT_INFO_PAYMENT_METHOD', 'Zahlungsmethode:');

define('TEXT_ALL_ORDERS', 'Alle Bestellungen');
define('TEXT_NO_ORDER_HISTORY', 'Keine Bestellhistorie verfügbar');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Aktualisierung der Bestellung');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestellnummer:');
define('EMAIL_TEXT_INVOICE_URL', 'Detaillierte Rechnung:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestelltes Datum:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Ihre Bestellung wurde auf den folgenden Status aktualisiert.' . "\n\n" . 'Neuer Status: %s' . "\n\n" . 'Bitte antworten Sie auf diese E-Mail, wenn Sie Fragen haben.' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Die Kommentare zu Ihrer Bestellung sind' . "\n\n%s\n\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fehler: Bestellung existiert nicht.');
define('SUCCESS_ORDER_UPDATED', 'Erfolg: Die Bestellung wurde erfolgreich aktualisiert.');
define('WARNING_ORDER_NOT_UPDATED', 'Warnung: Nichts zu ändern. Die Bestellung wurde nicht aktualisiert.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Produkt auswählen');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Optionen auswählen');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'Keine Optionen: Übersprungen..');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'Menge.');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Jetzt hinzufügen');
