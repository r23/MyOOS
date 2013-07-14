<?php
/* ----------------------------------------------------------------------
   $Id: nochex.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: nochex.php,v 1.3 2002/11/01 05:38:19 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_NOCHEX_STATUS_TITLE', 'Enable NOCHEX Module');
define('MODULE_PAYMENT_NOCHEX_STATUS_DESC', 'M&ouml;chten Sie Zahlungen per NOCHEX akzeptieren?');

define('MODULE_PAYMENT_NOCHEX_ID_TITLE', 'eMail Adresse');
define('MODULE_PAYMENT_NOCHEX_ID_DESC', 'eMail Adresse, welche f&uuml;r NOCHEX verwendet wird');

define('MODULE_PAYMENT_NOCHEX_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_NOCHEX_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_NOCHEX_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_NOCHEX_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_NOCHEX_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_NOCHEX_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');

$aLang['module_payment_nochex_text_title'] = 'NOCHEX';
$aLang['module_payment_nochex_text_description'] = 'NOCHEX<br />Erfordert die W&auml;hrung GBP.';

?>
