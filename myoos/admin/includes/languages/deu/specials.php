<?php
/**
   ----------------------------------------------------------------------
   $Id: specials.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: specials.php,v 1.10 2002/01/31 01:17:51 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Sonderangebote');

define('TABLE_HEADING_PRODUCTS', 'Artikel');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Artikelpreis');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Aktion');
define('TEXT_TAX_INFO', 'Netto:');
define('TEXT_SPECIALS_PRODUCT', 'Produkt:');
define('TEXT_SPECIALS_SPECIAL_PRICE', 'Angebotspreis (Netto):');
define('TEXT_SPECIALS_SPECIAL_PRICE_WITH_TAX', 'Angebotspreis (Brutto):');

define('TEXT_SPECIALS_CROSS_OUT_PRICE', 'Streichpreis:');
define('TEXT_SPECIALS_EXPIRES_DATE', 'Gültig bis:<br><small>(YYYY-MM-DD)</small>');
define('TEXT_SPECIALS_PRICE_TIP', '<b>Bemerkung:</b><ul><li>Sie können im Feld Angebotspreis (Netto) auch prozentuale Werte angeben, z.B.: <b>20%</b></li><li>Wenn Sie einen neuen Preis eingeben, müssen die Nachkommastellen mit einem \'.\' getrennt werden, z.B.: <b>49.99</b></li></ul>');

define('TEXT_INFO_DATE_ADDED', 'hinzugefügt am:');
define('TEXT_INFO_LAST_MODIFIED', 'letzte Änderung:');
define('TEXT_INFO_NEW_PRICE', 'neuer Preis:');
define('TEXT_INFO_ORIGINAL_PRICE', 'alter Preis:');
define('TEXT_INFO_PERCENTAGE', 'Prozent:');
define('TEXT_INFO_EXPIRES_DATE', 'Gültig bis:');
define('TEXT_INFO_STATUS_CHANGE', 'Status geändert:');
define('TEXT_IMAGE_NONEXISTENT', 'BILD EXISTIERT NICHT');

define('TEXT_INFO_HEADING_DELETE_SPECIALS', 'Sonderangebot löschen');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie das Sonderangebot löschen möchten?');

define('TEXT_EXPIRES_DATE_ERROR', '<strong>Fehler:</strong> Das Gültigkeitsdatum fehlt!');
define('TEXT_PRODUCT_ERROR', '<strong>Fehler:</strong> Das Produkt ist noch keine 30 Tage im Onlineshop veröffentlicht!');
define('TEXT_PRICE_ERROR', '<strong>Fehler:</strong> Der Preis vom Sonderangebot ist nicht kleiner als der der niedrigste Gesamtpreis der letzten 30 Tage!');
