<?php
/* ----------------------------------------------------------------------
   $Id: yellowpay.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
   If you made a translation, please send to 
      lang@oos-shop.de 
   the translated file. 
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_YELLOWPAY_STATUS_TITLE', 'PostFinance Modul aktivieren');
define('MODULE_PAYMENT_YELLOWPAY_STATUS_DESC', 'M&ouml;chten Sie die Bezahlung via Postcard akzeptieren?');

define('MODULE_PAYMENT_YELLOWPAY_ID_TITLE', 'ID Shop (Von der Post erhalten)');
define('MODULE_PAYMENT_YELLOWPAY_ID_DESC', 'ID Shop - Von der Post erhalten- (txtShopID)');

define('MODULE_PAYMENT_HASH_SEED_TITLE', 'Hash seed (Von der Post erhalten)');
define('MODULE_PAYMENT_HASH_SEED_DESC', 'Hash seed - Von der Post erhalten');

define('MODULE_PAYMENT_YELLOWPAY_CURRENCY_TITLE', 'Standard W&auml;hrung');
define('MODULE_PAYMENT_YELLOWPAY_CURRENCY_DESC', 'Standard W&auml;hrung die f&uuml;r die Transaktion verwendet wird (Bsp: CHF zwingt die CHF W&auml;hrung zu benutzen / leer wenn Navigationsw&auml;hrung benutzt wird)');

define('MODULE_PAYMENT_YELLOWPAY_SORT_ORDER_TITLE', 'Sortierung');
define('MODULE_PAYMENT_YELLOWPAY_SORT_ORDER_DESC', 'Reihenfolge f&uuml;r die Zahlungsm&ouml;glichkeiten (Die kleinste Nummer kommt als erstes)');

define('MODULE_PAYMENT_YELLOWPAY_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_YELLOWPAY_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_YELLOWPAY_LANGUAGE_TITLE', 'Mit welcher Standard Sprache soll die Zahlungsmaske aufgerufen werden?');
define('MODULE_PAYMENT_YELLOWPAY_LANGUAGE_DESC', 'Bitte w&auml;hlen Sie die Standard Sprache f&uuml;r die Zahlungsmaske, Franz&ouml;sisch (4108), Englisch (2057), Italienisch (2064), Deutsch (2055)');

define('MODULE_PAYMENT_YELLOWPAY_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_YELLOWPAY_ORDER_STATUS_ID_DESC', 'W&auml;hlen Sie den Status den Sie per default gesetzt haben m&ouml;chten.');

$aLang['module_payment_yellowpay_text_title'] = 'PostFinance';
$aLang['module_payment_yellowpay_text_description'] = 'PostFinance';
?>