<?php
/* ----------------------------------------------------------------------
   $Id: orders.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
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
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Preis (exkl.)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Preis (inkl.)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Total (exkl.)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total (inkl.)');

define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Kunde benachrichtigt');
define('TABLE_HEADING_DATE_ADDED', 'hinzugef&uuml;gt am:');

define('ENTRY_CUSTOMER', 'Kunde:');
define('ENTRY_SOLD_TO', 'Kunde:');
define('ENTRY_STREET_ADDRESS', 'Strasse:');
define('ENTRY_SUBURB', 'zus. Anschrift:');
define('ENTRY_CITY', 'Stadt:');
define('ENTRY_POST_CODE', 'PLZ:');
define('ENTRY_STATE', 'Bundesland:');
define('ENTRY_COUNTRY', 'Land:');
define('ENTRY_TELEPHONE', 'Telefon:');
define('ENTRY_EMAIL_ADDRESS', 'eMail Adresse:');
define('ENTRY_DELIVERY_TO', 'Lieferanschrift:');
define('ENTRY_SHIP_TO', 'Lieferanschrift:');
define('ENTRY_SHIPPING_ADDRESS', 'Versandadresse:');
define('ENTRY_BILLING_ADDRESS', 'Rechnungsadresse:');
define('ENTRY_ORDER_NUMBER', 'Bestellnummer');
define('ENTRY_ORDER_DATE', 'Bestelldatum');

define('ENTRY_PAYMENT_METHOD', 'Zahlungsweise:');
define('ENTRY_CREDIT_CARD_TYPE', 'Kreditkartentyp:');
define('ENTRY_CREDIT_CARD_OWNER', 'Kreditkarteninhaber:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Kerditkartennnummer:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Kreditkarte l&auml;uft ab am:');
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

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Bestellung l&ouml;schen');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, das Sie diese Bestellung l&ouml;schen m&ouml;chten?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Artikelanzahl dem Lager gutschreiben');
define('TEXT_DATE_ORDER_CREATED', 'erstellt am:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'letzte &Auml;nderung:');
define('TEXT_INFO_PAYMENT_METHOD', 'Zahlungsweise:');

define('TEXT_ALL_ORDERS', 'Alle Bestellungen');
define('TEXT_NO_ORDER_HISTORY', 'Keine Bestellhistorie verf&uuml;gbar');

define('ERROR_ORDER_DOES_NOT_EXIST', 'Fehler: Die Bestellung existiert nicht!.');
define('SUCCESS_ORDER_UPDATED', 'Hinweis: Die Bestellung wurde erfolgreich aktualisiert.');
define('WARNING_ORDER_NOT_UPDATED', 'Hinweis: Es wurde nichts ge&auml;ndert. Daher wurde diese Bestellung nicht aktualisiert.');

define('TEXT_BANK', 'Bankeinzug');
define('TEXT_BANK_OWNER', 'Kontoinhaber:');
define('TEXT_BANK_NUMBER', 'Kontonummer:');
define('TEXT_BANK_BLZ', 'BLZ:');
define('TEXT_BANK_NAME', 'Bank:');
define('TEXT_BANK_FAX', 'Einzugserm&auml;chtigung wird per Fax best&auml;tigt');
define('TEXT_BANK_STATUS', 'Pr&uuml;fstatus:');
define('TEXT_BANK_PRZ', 'Pr&uuml;fverfahren:');

define('TEXT_BANK_ERROR_1', 'Kontonummer stimmt nicht mit BLZ &uuml;berein!');
define('TEXT_BANK_ERROR_2', 'F&uuml;r diese Kontonummer ist kein Pr&uuml;fverfahren definiert!');
define('TEXT_BANK_ERROR_3', 'Kontonummer nicht pr&uuml;fbar! Pr&uuml;fverfahren nicht implementiert');
define('TEXT_BANK_ERROR_4', 'Kontonummer technisch nicht pr&uuml;fbar!');
define('TEXT_BANK_ERROR_5', 'Bankleitzahl nicht gefunden!');
define('TEXT_BANK_ERROR_8', 'Keine Bankleitzahl angegeben!');
define('TEXT_BANK_ERROR_9', 'Keine Kontonummer angegeben!');
define('TEXT_BANK_ERRORCODE', 'Fehlercode:');

